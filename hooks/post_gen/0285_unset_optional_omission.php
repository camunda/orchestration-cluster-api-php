<?php

/**
 * Post-gen hook 0285 — omit unset optional fields from serialized request bodies.
 *
 * https://github.com/camunda/orchestration-cluster-api-php/issues/53
 *
 * php-nextgen models initialise every property through `setIfExists($name, $data,
 * $default)`, which fills unset optional properties with the spec default
 * (`awaitCompletion` → false, `requestTimeout` → 0, `processDefinitionVersion` → -1, …).
 * The generated ObjectSerializer then emits every non-null container value, so requests
 * carry fields the caller never set — e.g. createProcessInstance sends
 * `"awaitCompletion":false,"requestTimeout":0` and defaulted version `-1`, diverging from
 * the other SDKs (and from the caller's intent: an absent field is not the same as an
 * explicitly defaulted one).
 *
 * This hook teaches the generated models to track which fields were *explicitly* provided
 * — via the array constructor, a setter, or ArrayAccess — and teaches the ObjectSerializer
 * to skip properties that were never set. An explicit `null` on a nullable field is still
 * serialized as `null` through the pre-existing `openAPINullablesSetToNull` mechanism, so
 * "absent" and "explicitly null" stay distinguishable.
 *
 * Concretely, every generated model that uses `setIfExists` gets:
 *
 *   - `protected array $openAPIFieldsSet = [];`      — explicit-field tracking
 *   - `public function isFieldSet(string $property): bool` — duck-typed read accessor
 *   - `setIfExists()` records `array_key_exists($variableName, $fields)`
 *   - every `setX()` records the field (and drops it again on `null`, matching the
 *     "unset" semantics of assigning null through ArrayAccess)
 *   - `offsetSet()` / `offsetUnset()` keep the tracking in sync
 *
 * and `ObjectSerializer::sanitizeForSerialization()` skips any `ModelInterface` property
 * for which `isFieldSet()` returns false.
 *
 * Runs after 0250/0275 (which rewrite setIfExists bodies) and before 0300 (which rewrites
 * setters): the anchors here match both the plain and the semantic-hydrated setIfExists
 * shapes, and the setter regex tolerates later retyping.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $modelDir = $ctx['out_dir'] . '/src/Model';
    $patchedModels = 0;

    foreach (glob($modelDir . '/*.php') ?: [] as $file) {
        $src = (string) file_get_contents($file);
        if (!str_contains($src, 'setIfExists(')) {
            continue; // enums, oneOf/anyOf interfaces, discriminator stubs
        }
        if (str_contains($src, 'openAPIFieldsSet')) {
            continue; // idempotency: already patched (rerun via --skip-openapi-generator)
        }

        $orig = $src;

        // 1. Instance state + accessor, next to $openAPINullablesSetToNull. ----------
        $src = str_replace_once_0285(
            $src,
            <<<'PHP'
    /**
     * If a nullable field gets set to null, insert it here
     *
     * @var array<string, bool>
     */
    protected array $openAPINullablesSetToNull = [];
PHP,
            <<<'PHP'
    /**
     * If a nullable field gets set to null, insert it here
     *
     * @var array<string, bool>
     */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Names of fields explicitly provided by the caller (constructor data, setter, or
     * ArrayAccess). Unset optional fields are omitted from serialized request bodies —
     * an absent field is not the same as its spec default.
     *
     * @var array<string, bool>
     */
    protected array $openAPIFieldsSet = [];
PHP,
            'field-tracking state'
        );

        // 2. Read accessor, next to isNullableSetToNull(). -----------------------------
        $src = str_replace_once_0285(
            $src,
            <<<'PHP'
    /**
     * {@inheritdoc}
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }
PHP,
            <<<'PHP'
    /**
     * {@inheritdoc}
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Whether the caller explicitly provided a value for the property (via the
     * constructor data array, a setter, or ArrayAccess). Unset optional properties are
     * omitted from serialized request bodies.
     *
     * @param string $property
     *
     * @return bool
     */
    public function isFieldSet(string $property): bool
    {
        return isset($this->openAPIFieldsSet[$property]);
    }
PHP,
            'isFieldSet accessor'
        );

        // 3. setIfExists(): record explicitly-provided constructor fields. -------------
        $src = str_replace_once_0285(
            $src,
            <<<'PHP'
    private function setIfExists(string $variableName, array $fields, mixed $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }
PHP,
            <<<'PHP'
    private function setIfExists(string $variableName, array $fields, mixed $defaultValue): void
    {
        if (array_key_exists($variableName, $fields)) {
            $this->openAPIFieldsSet[$variableName] = true;
        }

        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }
PHP,
            'setIfExists tracking'
        );

        // 4. Setters: record the field on assignment; assigning null unsets it.
        //    Property-less models (empty schemas) have no setters — nothing to patch.
        if (str_contains($src, 'public function set')) {
            $src = patch_setters_0285($src, $file);
        }

        // 5. ArrayAccess stays in sync. ------------------------------------------------
        $src = str_replace_once_0285(
            $src,
            <<<'PHP'
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }
PHP,
            <<<'PHP'
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
            if ($value === null) {
                unset($this->openAPIFieldsSet[$offset]);
            } else {
                $this->openAPIFieldsSet[$offset] = true;
            }
        }
    }
PHP,
            'offsetSet tracking'
        );

        $src = str_replace_once_0285(
            $src,
            <<<'PHP'
    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }
PHP,
            <<<'PHP'
    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
        unset($this->openAPIFieldsSet[$offset]);
    }
PHP,
            'offsetUnset tracking'
        );

        if ($src !== $orig) {
            file_put_contents($file, $src);
            $patchedModels++;
        }
    }

    // 6. ObjectSerializer: skip properties the caller never set. ------------------------
    patch_object_serializer_0285($ctx['out_dir'] . '/src/ObjectSerializer.php');

    fwrite(STDOUT, "  [unset-omission] tracked explicit fields in $patchedModels models; serializer omits unset optionals\n");
};

/**
 * Record explicit assignments in every generated setter.
 *
 * Setter bodies end with `$this->container['prop'] = $var;` (optionally followed by a
 * `return $this;`). Rewrites that assignment to keep `openAPIFieldsSet` in sync. Runs
 * before hook 0300 retypes setters, and its own output is idempotency-guarded, so both
 * setter shapes are handled.
 */
function patch_setters_0285(string $src, string $file): string
{
    $pattern = '/(?P<indent>        )\$this->container\[\'(?P<prop>[A-Za-z0-9_]+)\'\] = \$(?P<var>[A-Za-z0-9_]+);\n(?=\n        return \$this;\n    \}\n)/';

    $count = 0;
    $patched = preg_replace_callback(
        $pattern,
        static function (array $m): string {
            $indent = $m['indent'];
            $prop = $m['prop'];
            $var = $m['var'];

            return $indent . "\$this->container['$prop'] = \$$var;\n"
                . $indent . "if (\$$var === null) {\n"
                . $indent . "    unset(\$this->openAPIFieldsSet['$prop']);\n"
                . $indent . "} else {\n"
                . $indent . "    \$this->openAPIFieldsSet['$prop'] = true;\n"
                . $indent . "}\n";
        },
        $src,
        -1,
        $count
    );

    if ($patched === null || $count === 0) {
        throw new RuntimeException('hook 0285: no setter assignments patched in ' . basename($file));
    }

    return $patched;
}

/**
 * Teach sanitizeForSerialization() to omit ModelInterface properties that were never
 * explicitly set.
 */
function patch_object_serializer_0285(string $file): void
{
    if (!is_file($file)) {
        throw new RuntimeException('hook 0285: ObjectSerializer.php not found');
    }
    $src = (string) file_get_contents($file);

    if (str_contains($src, 'isFieldSet')) {
        return; // already patched (rerun via --skip-openapi-generator)
    }

    $anchor = <<<'PHP'
                foreach ($data::openAPITypes() as $property => $openAPIType) {
                    $getter = $data::getters()[$property];
                    $value = $data->$getter();
PHP;

    $replacement = <<<'PHP'
                foreach ($data::openAPITypes() as $property => $openAPIType) {
                    if (method_exists($data, 'isFieldSet') && !$data->isFieldSet($property)) {
                        // The caller never provided this optional field — omit it from
                        // the request body instead of emitting its spec default.
                        continue;
                    }
                    $getter = $data::getters()[$property];
                    $value = $data->$getter();
PHP;

    $src = str_replace_once_0285($src, $anchor, $replacement, 'serializer unset-skip');

    file_put_contents($file, $src);
}

function str_replace_once_0285(string $haystack, string $needle, string $replace, string $what): string
{
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        throw new RuntimeException("hook 0285: anchor for '$what' not found");
    }
    return substr($haystack, 0, $pos) . $replace . substr($haystack, $pos + strlen($needle));
}
