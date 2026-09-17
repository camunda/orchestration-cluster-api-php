<?php

/**
 * Post-gen hook 0150 — preserve multipart field names for arrays of uploaded files.
 *
 * php-nextgen flattens `array<binary>` form fields as `resources[0]`, but the
 * Orchestration API expects one `resources` part per uploaded resource. Preserve the
 * field name and let MultipartStream repeat it for every file stream.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $file = $ctx['out_dir'] . '/src/FormDataProcessor.php';
    if (!is_file($file)) {
        throw new RuntimeException('hook 0150: FormDataProcessor.php not found');
    }

    $source = (string) file_get_contents($file);
    $hasNewFlatten = str_contains($source, 'if (self::containsOnlyFileValues($val)) {');
    $hasOldHelper = str_contains($source, 'private static function containsFileValue');
    $hasNewHelper = str_contains($source, 'private static function containsOnlyFileValues');
    if ($hasNewFlatten && $hasNewHelper && !$hasOldHelper) {
        fwrite(STDOUT, "  [multipart] FormDataProcessor already handles file arrays\n");
        return;
    }

    $flattenReplacement = <<<'PHP'
            if (is_array($val) && !empty($val)) {
                if (self::containsOnlyFileValues($val)) {
                    $result[$currentName] = array_values($val);
                } else {
                    $currentName .= $currentSuffix;
                    $result += self::flatten($val, $currentName);
                }
            } else {
PHP;
    $flattenNeedles = [
        <<<'PHP'
            if (is_array($val) && !empty($val)) {
                $currentName .= $currentSuffix;
                $result += self::flatten($val, $currentName);
            } else {
PHP,
        <<<'PHP'
            if (is_array($val) && !empty($val)) {
                if (array_is_list($val) && self::containsFileValue($val)) {
                    $result[$currentName] = $val;
                } else {
                    $currentName .= $currentSuffix;
                    $result += self::flatten($val, $currentName);
                }
            } else {
PHP,
    ];
    $helper = <<<'PHP'
    /**
     * @param array<mixed> $values
     */
    private static function containsOnlyFileValues(array $values): bool
    {
        foreach ($values as $value) {
            if (!is_resource($value) && !$value instanceof StreamInterface) {
                return false;
            }
        }

        return true;
    }

    /**
     * formdata must be limited to scalars or arrays of scalar values,
PHP;
    $helperAnchor = <<<'PHP'
    /**
     * formdata must be limited to scalars or arrays of scalar values,
PHP;
    $oldHelper = <<<'PHP'
    /**
     * @param array<mixed> $values
     */
    private static function containsFileValue(array $values): bool
    {
        foreach ($values as $value) {
            if (is_resource($value) || $value instanceof StreamInterface) {
                return true;
            }
        }

        return false;
    }
PHP;

    if (!$hasNewFlatten) {
        $source = replace_first($source, $flattenNeedles, $flattenReplacement, 'flatten file-array branch');
    }

    if ($hasOldHelper) {
        $source = replace_once($source, $oldHelper, '', 'file-array helper removal');
    }

    if (!$hasNewHelper) {
        $source = replace_once($source, $helperAnchor, $helper, 'file-array helper anchor');
    }

    if (file_put_contents($file, $source) === false) {
        throw new RuntimeException('hook 0150: cannot write FormDataProcessor.php');
    }

    fwrite(STDOUT, "  [multipart] patched FormDataProcessor file-array encoding\n");
};

function replace_once(string $source, string $needle, string $replacement, string $description): string
{
    $position = strpos($source, $needle);
    if ($position === false) {
        throw new RuntimeException("hook 0150: anchor for $description not found");
    }

    return substr($source, 0, $position) . $replacement . substr($source, $position + strlen($needle));
}

/**
 * @param non-empty-list<string> $needles
 */
function replace_first(string $source, array $needles, string $replacement, string $description): string
{
    foreach ($needles as $needle) {
        $position = strpos($source, $needle);
        if ($position !== false) {
            return substr($source, 0, $position) . $replacement . substr($source, $position + strlen($needle));
        }
    }

    throw new RuntimeException("hook 0150: anchor for $description not found");
}
