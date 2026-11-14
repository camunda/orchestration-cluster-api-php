#!/usr/bin/env bash
# Structural assertion for the taint-analysis self-test (see security-selftest.sh).
#
# Usage: assert-selftest-sarif.sh <psalm.sarif>
#
# Passes only when the SARIF report from the self-test run contains at least one result and
# EVERY result is a TaintedShell issue located in the deliberately vulnerable fixture.
# It fails on zero results, on parse errors or any other issue type, and on a TaintedShell
# reported anywhere else.
#
# Psalm's SARIF `ruleId` is the issue *shortcode* (a number, e.g. 246), not the type name, so the
# type is resolved through `tool.driver.rules[]` (`id` -> `name`). Matching on text output instead
# would be unsafe: the fixture's file name itself contains "TaintedShell".
set -uo pipefail

sarif="${1:?usage: assert-selftest-sarif.sh <psalm.sarif>}"

if [ ! -s "$sarif" ]; then
  echo "security-selftest FAILED: no SARIF report was produced at ${sarif}." >&2
  exit 1
fi

# One line per result: "<issue type> <file uri>" (empty type when the rule is unknown).
if ! summary="$(jq -r '
  (.runs[0].tool.driver.rules // []) as $rules
  | ([$rules[] | {key: (.id | tostring), value: .name}] | from_entries) as $types
  | (.runs[0].results // [])[]
  | "\($types[.ruleId | tostring] // "")\t\(.locations[0].physicalLocation.artifactLocation.uri // "")"
' "$sarif" 2>&1)"; then
  echo "security-selftest FAILED: cannot read ${sarif} as SARIF: ${summary}" >&2
  exit 1
fi

if [ -z "$summary" ]; then
  echo "security-selftest FAILED: Psalm reported no results for the vulnerable fixture (silent no-op scan)." >&2
  exit 1
fi

unexpected=0
while IFS=$'\t' read -r type uri; do
  case "$uri" in
    selftest/TaintedShell.php | */selftest/TaintedShell.php) located=1 ;;
    *) located=0 ;;
  esac
  if [ "$type" != "TaintedShell" ] || [ "$located" -ne 1 ]; then
    echo "security-selftest FAILED: unexpected result: type='${type:-unknown}' file='${uri}'." >&2
    unexpected=1
  fi
done <<<"$summary"

if [ "$unexpected" -ne 0 ]; then
  echo "Expected only TaintedShell findings in selftest/TaintedShell.php. All results:" >&2
  printf '%s\n' "$summary" >&2
  exit 1
fi

echo "security-selftest OK: $(printf '%s\n' "$summary" | wc -l | tr -d ' ') TaintedShell finding(s) in the fixture."
