<?php

/**
 * Post-gen hook 0400 — (re)generate the typed API-accessor trait.
 *
 * Emits src/ApiAccessors.php: one lazily-cached, typed accessor per generated API
 * group, consumed by the CamundaClient / CamundaAsyncClient facades. Regenerated on
 * every pipeline run so the facade always covers exactly the generated API surface.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $apiDir = $ctx['out_dir'] . '/src/Api';
    $names = [];
    foreach (glob($apiDir . '/*.php') ?: [] as $file) {
        $names[] = basename($file, '.php');
    }
    sort($names);

    $methods = '';
    foreach ($names as $name) {
        $accessor = lcfirst(preg_replace('/Api$/', '', $name) ?? $name);
        $fqcn = '\\Camunda\\Orchestration\\Api\\Api\\' . $name;
        $methods .= "    public function {$accessor}(): {$fqcn}\n"
            . "    {\n"
            . "        /** @var {$fqcn} */\n"
            . "        return \$this->api({$fqcn}::class);\n"
            . "    }\n\n";
    }

    $header = <<<'PHP'
<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

/**
 * Typed accessors for every generated Camunda Orchestration Cluster API group.
 *
 * Each accessor returns a lazily-constructed, shared API instance wired with the
 * client's configuration, authentication and HTTP stack. This trait is regenerated
 * from the generated API classes by hooks/post_gen/0400_api_accessors.php; do not edit
 * by hand.
 *
 * @internal
 */
trait ApiAccessors
{

PHP;

    $target = $ctx['root'] . '/src/ApiAccessors.php';
    file_put_contents($target, $header . $methods . "}\n");
    fwrite(STDOUT, '  [api-accessors] wrote ' . count($names) . " accessors to src/ApiAccessors.php\n");
};
