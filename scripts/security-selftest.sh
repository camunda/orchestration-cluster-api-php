#!/usr/bin/env bash
# Proves the Psalm taint analysis is alive: it must flag the deliberately vulnerable
# fixture in security/selftest/. Fails if Psalm finds nothing (a silent no-op scan).
set -uo pipefail

cd "$(dirname "$0")/.."

# `compact` (not the auto-selected GitHub format) so the expected finding is not surfaced as a
# red error annotation on a passing run.
output="$(vendor/bin/psalm --config=security/psalm-selftest.xml --taint-analysis --no-cache --no-progress --output-format=compact 2>&1)"
status=$?
printf '%s\n' "$output"

if [ "$status" -eq 0 ] || ! grep -q 'TaintedShell' <<<"$output"; then
  echo "security-selftest FAILED: expected Psalm to report TaintedShell for security/selftest/TaintedShell.php (exit status ${status})." >&2
  exit 1
fi

echo "security-selftest OK: taint analysis reported TaintedShell on the fixture."
