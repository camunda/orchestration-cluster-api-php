#!/usr/bin/env bash
# Stamp the semantic-release-resolved version into src/Version.php.
# Invoked by @semantic-release/exec prepareCmd.
set -euo pipefail

VERSION="${1:?usage: prepare-release.sh <version>}"
REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
TARGET="$REPO_ROOT/src/Version.php"

cat > "$TARGET" <<PHP
<?php

declare(strict_types=1);

namespace Camunda\\Orchestration;

/**
 * The version of this SDK. Stamped by scripts/prepare-release.sh during release.
 *
 * @internal
 */
final class Version
{
    public const VALUE = '${VERSION}';
}
PHP

echo "[prepare-release] Wrote version ${VERSION} to ${TARGET}"
