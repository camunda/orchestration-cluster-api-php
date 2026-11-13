#!/usr/bin/env bash
# Proves the Psalm taint analysis is alive: it must flag the deliberately vulnerable fixture in
# security/selftest/. Fails if Psalm finds nothing (a silent no-op scan), reports anything other
# than a TaintedShell in the fixture (for example a parse error), or produces no report.
set -uo pipefail

cd "$(dirname "$0")/.."

# The assertion itself is tested first, so a broken check cannot pass silently.
bash scripts/assert-selftest-sarif-test.sh || exit 1

dir="$(mktemp -d)"
trap 'rm -rf "$dir"' EXIT
sarif="$dir/selftest.sarif"

# `compact` (not the auto-selected GitHub format) keeps the expected finding from showing up as a
# red error annotation on a passing run. The exit status is deliberately ignored: Psalm exits 0
# under GitHub Actions and 2 elsewhere when it reports taint findings with --report. The SARIF is
# the source of truth, as in the real scan's gate.
vendor/bin/psalm --config=security/psalm-selftest.xml --taint-analysis --no-cache --no-progress \
  --output-format=compact --report="$sarif"
status=$?
echo "psalm exit status: ${status}"

bash scripts/assert-selftest-sarif.sh "$sarif"
