#!/usr/bin/env bash
# Red/green tests for assert-selftest-sarif.sh: it must accept a real taint finding and reject
# everything that is not one, in particular a failure that merely mentions the fixture's file
# name (which contains "TaintedShell"). The SARIF shape mirrors Psalm's SarifReport: `ruleId` is
# the issue shortcode and `tool.driver.rules[].name` is the issue type.
set -uo pipefail

cd "$(dirname "$0")/.."

dir="$(mktemp -d)"
trap 'rm -rf "$dir"' EXIT
failures=0

# sarif <file> <rule-id:type[,..]> <ruleId> <uri>...   (one result per "<ruleId>@<uri>" argument)
sarif() {
  local file="$1" rules="$2"
  shift 2
  jq -n --arg rules "$rules" --args '
    {version: "2.1.0", runs: [{
      tool: {driver: {name: "Psalm", rules: [
        ($rules | split(",") | .[] | select(. != "") | split(":") | {id: .[0], name: .[1]})
      ]}},
      results: [$ARGS.positional[] | split("@") | {
        ruleId: .[0],
        message: {text: "x"},
        locations: [{physicalLocation: {artifactLocation: {uri: .[1]}}}]
      }]
    }]}' "$@" >"$file"
}

expect() { # expect <pass|fail> <description> <sarif file>
  local want="$1" description="$2" file="$3" got
  if bash scripts/assert-selftest-sarif.sh "$file" >/dev/null 2>&1; then got=pass; else got=fail; fi
  if [ "$got" = "$want" ]; then
    echo "ok   - ${description} (${got})"
  else
    echo "FAIL - ${description}: wanted ${want}, got ${got}" >&2
    failures=$((failures + 1))
  fi
}

sarif "$dir/real.sarif" '246:TaintedShell' '246@selftest/TaintedShell.php'
expect pass 'real TaintedShell finding in the fixture' "$dir/real.sarif"

sarif "$dir/two.sarif" '246:TaintedShell' '246@selftest/TaintedShell.php' '246@selftest/TaintedShell.php'
expect pass 'several TaintedShell findings in the fixture' "$dir/two.sarif"

sarif "$dir/empty.sarif" ''
expect fail 'zero results' "$dir/empty.sarif"

sarif "$dir/parse.sarif" '3:ParseError' '3@selftest/TaintedShell.php'
expect fail 'parse error located in the fixture (file name contains TaintedShell)' "$dir/parse.sarif"

sarif "$dir/other.sarif" '3:UndefinedFunction' '3@selftest/TaintedShell.php'
expect fail 'other issue type in the fixture' "$dir/other.sarif"

sarif "$dir/mixed.sarif" '246:TaintedShell,3:ParseError' '246@selftest/TaintedShell.php' '3@selftest/TaintedShell.php'
expect fail 'real finding plus an unrelated issue' "$dir/mixed.sarif"

sarif "$dir/elsewhere.sarif" '246:TaintedShell' '246@src/Other.php'
expect fail 'TaintedShell reported in some other file' "$dir/elsewhere.sarif"

sarif "$dir/unknown.sarif" '' '246@selftest/TaintedShell.php'
expect fail 'result whose rule is not declared' "$dir/unknown.sarif"

printf 'not json' >"$dir/garbage.sarif"
expect fail 'unreadable report' "$dir/garbage.sarif"

: >"$dir/blank.sarif"
expect fail 'empty file' "$dir/blank.sarif"

expect fail 'missing file' "$dir/does-not-exist.sarif"

if [ "$failures" -ne 0 ]; then
  echo "assert-selftest-sarif tests: ${failures} failure(s)" >&2
  exit 1
fi
echo "assert-selftest-sarif tests passed"
