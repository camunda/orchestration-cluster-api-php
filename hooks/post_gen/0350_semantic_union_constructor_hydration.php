<?php

declare(strict_types=1);

return static function (array $ctx): void {
    $metadataPath = $ctx['metadata_path'] ?? null;
    if (!$metadataPath || !is_file($metadataPath)) {
        fwrite(STDERR, "  [semantic-union-hydration] metadata missing — skipping\n");
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
        fwrite(STDOUT, "  [semantic-union-hydration] no semantic unions to patch\n");
        return;
    }

    $modelDir = $ctx['out_dir'] . '/src/Model';
    $touched = 0;
    foreach (glob($modelDir . '/*.php') ?: [] as $file) {
        $src = (string) file_get_contents($file);
        if (!contains_semantic_union_type($src, $unionNames)) {
            continue;
        }

        [$patched, $replaced] = patch_semantic_union_set_if_exists($src);
        if (!$replaced) {
            throw new RuntimeException(sprintf(
                '[semantic-union-hydration] expected to patch setIfExists() in %s',
                basename($file)
            ));
        }
        if ($patched !== $src) {
            file_put_contents($file, $patched);
            ++$touched;
        }
    }

    fwrite(STDOUT, "  [semantic-union-hydration] patched {$touched} generated models\n");
};

/**
 * @param list<string> $unionNames
 */
function contains_semantic_union_type(string $src, array $unionNames): bool
{
    foreach ($unionNames as $name) {
        if (str_contains($src, '\\Camunda\\Orchestration\\Semantic\\' . $name)) {
            return true;
        }
    }

    return false;
}

/**
 * @return array{0: string, 1: bool}
 */
function patch_semantic_union_set_if_exists(string $src): array
{
    $scalarNeedle = <<<'PHP'
        $value = $fields[$variableName] ?? $defaultValue;
        $semanticType = static::$openAPITypes[$variableName] ?? null;
        if (
            is_string($value)
            && is_string($semanticType)
            && is_subclass_of($semanticType, \Camunda\Orchestration\Semantic\SemanticKey::class)
        ) {
            $value = new $semanticType($value);
        }
        $this->container[$variableName] = $value;
PHP;

    $plainNeedle = "        \$this->container[\$variableName] = \$fields[\$variableName] ?? \$defaultValue;";
    $replacement = <<<'PHP'
        $value = $fields[$variableName] ?? $defaultValue;
        $semanticType = static::$openAPITypes[$variableName] ?? null;
        if (is_string($semanticType)) {
            $isArray = str_ends_with($semanticType, '[]');
            $elementType = $isArray ? substr($semanticType, 0, -2) : $semanticType;
            if (
                is_string($elementType)
                && is_subclass_of($elementType, \Camunda\Orchestration\Semantic\SemanticKey::class)
            ) {
                if (is_string($value)) {
                    $value = new $elementType($value);
                } elseif ($isArray && is_array($value)) {
                    foreach ($value as $index => $item) {
                        if (is_string($item)) {
                            $value[$index] = new $elementType($item);
                        }
                    }
                }
            }
        }
        $this->container[$variableName] = $value;
PHP;

    $patched = str_replace($scalarNeedle, $replacement, $src, $scalarCount);
    if ($scalarCount > 0) {
        return [$patched, true];
    }

    $patched = str_replace($plainNeedle, $replacement, $src, $plainCount);
    if ($plainCount > 0) {
        return [$patched, true];
    }

    return [$src, str_contains($src, $replacement)];
}
