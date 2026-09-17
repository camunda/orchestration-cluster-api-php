<?php

declare(strict_types=1);

return static function (array $ctx): void {
    $metadataPath = $ctx['metadata_path'] ?? null;
    if (!$metadataPath || !is_file($metadataPath)) {
        fwrite(STDERR, "  [semantic-unions] metadata missing — skipping\n");
        return;
    }

    $meta = json_decode((string) file_get_contents($metadataPath), true);
    $keyNames = [];
    foreach ($meta['semanticKeys'] ?? [] as $key) {
        if (is_string($key['name'] ?? null)) {
            $keyNames[$key['name']] = true;
        }
    }

    $unionNames = [];
    foreach ($meta['unions'] ?? [] as $union) {
        $name = $union['name'] ?? null;
        $branches = $union['branches'] ?? [];
        if (!is_string($name) || $branches === []) {
            continue;
        }

        $allSemantic = true;
        foreach ($branches as $branch) {
            $ref = $branch['ref'] ?? null;
            if (($branch['branchType'] ?? null) !== 'ref' || !is_string($ref) || !isset($keyNames[$ref])) {
                $allSemantic = false;
                break;
            }
        }

        if ($allSemantic) {
            $unionNames[] = $name;
        }
    }

    if ($unionNames === []) {
        fwrite(STDOUT, "  [semantic-unions] no semantic unions to retype\n");
        return;
    }

    $files = array_merge(
        glob($ctx['out_dir'] . '/src/Api/*.php') ?: [],
        glob($ctx['out_dir'] . '/src/Model/*.php') ?: [],
    );

    $touched = 0;
    foreach ($files as $file) {
        $src = (string) file_get_contents($file);
        $orig = $src;

        foreach ($unionNames as $name) {
            $src = retype_union_name($src, $name);
        }

        if ($src !== $orig) {
            file_put_contents($file, $src);
            ++$touched;
        }
    }

    fwrite(STDOUT, "  [semantic-unions] retyped semantic unions across {$touched} generated files\n");
};

function retype_union_name(string $src, string $name): string
{
    $target = '\\Camunda\\Orchestration\\Semantic\\' . $name;
    $variable = '$' . lcfirst($name);

    $src = preg_replace(
        '/\\\\Camunda\\\\Orchestration\\\\Api\\\\Model\\\\' . preg_quote($name, '/') . '(?![A-Za-z0-9_])/',
        $target,
        $src,
    ) ?? $src;

    $src = preg_replace(
        "/(=>\\s*)'" . preg_quote($name, '/') . "(\\[\\])?'/",
        '$1\'' . addcslashes($target, '\\') . '$2\'',
        $src,
    ) ?? $src;

    $src = preg_replace(
        '/([,(]\s*\??)' . preg_quote($name, '/') . '(\s+\$)/',
        '$1' . semantic_union_preg_quote_replacement($target) . '$2',
        $src,
    ) ?? $src;

    $src = preg_replace(
        '/(:\s*\??)' . preg_quote($name, '/') . '(?=(?:\[\])?(?:\||\s|,|\)))/',
        '$1' . semantic_union_preg_quote_replacement($target),
        $src,
    ) ?? $src;

    $src = preg_replace(
        '/(@(?:param|return|var)\s+\??)' . preg_quote($name, '/') . '(?=(?:\[\])?(?:\||\s|$))/',
        '$1' . semantic_union_preg_quote_replacement($target),
        $src,
    ) ?? $src;

    $src = str_replace(
        'ObjectSerializer::toPathValue(' . $variable . ')',
        'ObjectSerializer::toPathValue((string) ' . $variable . ')',
        $src,
    );

    return $src;
}

function semantic_union_preg_quote_replacement(string $s): string
{
    return str_replace('\\', '\\\\', str_replace('$', '\\$', $s));
}
