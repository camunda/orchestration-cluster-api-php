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
    if (str_contains($source, 'containsFileValue')) {
        fwrite(STDOUT, "  [multipart] FormDataProcessor already handles file arrays\n");
        return;
    }

    $flattenNeedle = <<<'PHP'
            if (is_array($val) && !empty($val)) {
                $currentName .= $currentSuffix;
                $result += self::flatten($val, $currentName);
            } else {
PHP;
    $flattenReplacement = <<<'PHP'
            if (is_array($val) && !empty($val)) {
                if (array_is_list($val) && self::containsFileValue($val)) {
                    $result[$currentName] = $val;
                } else {
                    $currentName .= $currentSuffix;
                    $result += self::flatten($val, $currentName);
                }
            } else {
PHP;
    $helperAnchor = <<<'PHP'
    /**
     * formdata must be limited to scalars or arrays of scalar values,
PHP;
    $helper = <<<'PHP'
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

    /**
     * formdata must be limited to scalars or arrays of scalar values,
PHP;

    $source = replace_once($source, $flattenNeedle, $flattenReplacement, 'flatten file-array branch');
    $source = replace_once($source, $helperAnchor, $helper, 'file-array helper anchor');

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
