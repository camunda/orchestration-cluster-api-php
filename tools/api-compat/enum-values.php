<?php

declare(strict_types=1);

/*
 * Supplement to roave/backward-compatibility-check, which reports a removed enum case
 * but not a changed backing value. A changed value breaks customer code just as hard:
 * stored values and `Enum::from()` calls stop resolving, and a case's `->value` changes
 * on the wire. Compares every backed enum's `case => value` map between two revisions.
 *
 * Usage: php enum-values.php <repo-dir> <from-rev> <to-rev>
 * Prints one "[BC] CHANGED: ..." line per changed value. Exit: 0 none, 1 changed, 2 error.
 */

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

require __DIR__ . '/vendor/autoload.php';

$args = $_SERVER['argv'] ?? [];
if (!is_array($args) || count($args) !== 4) {
    fwrite(STDERR, "usage: php enum-values.php <repo-dir> <from-rev> <to-rev>\n");
    exit(2);
}
[$repo, $from, $to] = array_map(
    static fn (mixed $a): string => is_string($a) ? $a : '',
    array_slice(array_values($args), 1),
);

// Same source roots as the composer.json autoload of the SDK.
const ROOTS = ['src', 'generated/src', 'generated/semantic'];

/**
 * @return array<string, array<string, string>> enum FQCN => [case => backing value]
 */
function enumValues(string $repo, string $rev): array
{
    $parser = (new ParserFactory())->createForNewestSupportedVersion();
    $finder = new NodeFinder();
    $files = git($repo, ['ls-tree', '-r', '--name-only', $rev, '--', ...ROOTS]);
    $enums = [];
    foreach (explode("\n", trim($files)) as $path) {
        if (!str_ends_with($path, '.php')) {
            continue;
        }
        $code = git($repo, ['show', "{$rev}:{$path}"]);
        if (!preg_match('/^\s*enum\s/m', $code)) {
            continue;
        }
        $ast = $parser->parse($code) ?? [];
        $namespace = '';
        foreach ($finder->findInstanceOf($ast, Node\Stmt\Namespace_::class) as $ns) {
            $namespace = $ns->name?->toString() ?? '';
        }
        foreach ($finder->findInstanceOf($ast, Node\Stmt\Enum_::class) as $enum) {
            if ($enum->scalarType === null || $enum->name === null) {
                continue;
            }
            $cases = [];
            foreach ($enum->stmts as $stmt) {
                if ($stmt instanceof Node\Stmt\EnumCase && $stmt->expr !== null) {
                    $cases[$stmt->name->toString()] = valueOf(
                        $stmt->expr,
                        "{$rev}:{$path} {$enum->name->toString()}::{$stmt->name->toString()}",
                    );
                }
            }
            $enums[ltrim($namespace . '\\' . $enum->name->toString(), '\\')] = $cases;
        }
    }
    return $enums;
}

/**
 * Evaluates a case's backing expression to its value. Only self-contained constant
 * expressions (literals, concatenation, arithmetic) are evaluated; anything that
 * references a constant fails closed, because identical source text can still yield
 * a different value when the referenced constant changes.
 */
function valueOf(Node\Expr $expr, string $where): string
{
    $evaluator = new PhpParser\ConstExprEvaluator(static function (Node\Expr $e) use ($where): never {
        throw new PhpParser\ConstExprEvaluationException(
            "cannot evaluate backing value of {$where} (unsupported expression "
            . (new PhpParser\PrettyPrinter\Standard())->prettyPrintExpr($e)
            . '); use a literal backing value',
        );
    });
    return var_export($evaluator->evaluateDirectly($expr), true);
}

/**
 * @param list<string> $args
 */
function git(string $repo, array $args): string
{
    $cmd = array_merge(['git', '-C', $repo], $args);
    $proc = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($proc)) {
        fwrite(STDERR, "enum-values: could not run git\n");
        exit(2);
    }
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    if (proc_close($proc) !== 0 || $out === false) {
        fwrite(STDERR, 'enum-values: git ' . implode(' ', $args) . " failed: {$err}\n");
        exit(2);
    }
    return $out;
}

try {
    $before = enumValues($repo, $from);
    $after = enumValues($repo, $to);
} catch (PhpParser\Error $e) {
    fwrite(STDERR, 'enum-values: parse error: ' . $e->getMessage() . "\n");
    exit(2);
} catch (PhpParser\ConstExprEvaluationException $e) {
    fwrite(STDERR, 'enum-values: ' . $e->getMessage() . "\n");
    exit(2);
}

$changed = 0;
foreach ($before as $enum => $cases) {
    foreach ($cases as $case => $value) {
        // Removed enums and cases are reported by roave.
        if (isset($after[$enum][$case]) && $after[$enum][$case] !== $value) {
            echo "[BC] CHANGED: Value of case {$enum}::{$case} changed from {$value} to {$after[$enum][$case]}\n";
            $changed++;
        }
    }
}
exit($changed > 0 ? 1 : 0);
