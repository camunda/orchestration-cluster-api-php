#!/usr/bin/env bash
# Fetch (optionally) and bundle the upstream OpenAPI spec using camunda-schema-bundler.
#
# Environment variables:
#   SPEC_REF                     Git ref to fetch (default: stable/8.10). Passed as --ref.
#   CAMUNDA_SDK_SKIP_FETCH_SPEC  If "1", bundle the already-fetched spec under
#                                external-spec/upstream (skip network fetch).
#   BUNDLER_BIN                  Path to a camunda-schema-bundler binary (optional).
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SPEC_DIR="external-spec/upstream/zeebe/gateway-protocol/src/main/proto/v2"
BUNDLED_DIR="external-spec/bundled"
BUNDLED_SPEC="$BUNDLED_DIR/rest-api.bundle.json"
METADATA="$BUNDLED_DIR/spec-metadata.json"

cd "$REPO_ROOT"
mkdir -p "$BUNDLED_DIR"

if [[ -n "${BUNDLER_BIN:-}" ]]; then
    BUNDLER_CMD="$BUNDLER_BIN"
elif command -v npx &>/dev/null; then
    BUNDLER_CMD="npx --yes camunda-schema-bundler"
else
    echo "Error: No bundler available. Set BUNDLER_BIN or install Node.js (for npx)." >&2
    exit 1
fi

if [ "${CAMUNDA_SDK_SKIP_FETCH_SPEC:-0}" = "1" ]; then
    echo "[bundle-spec] Bundling with local spec (skip fetch)"
    $BUNDLER_CMD \
        --spec-dir "$SPEC_DIR" \
        --deref-path-local \
        --output-spec "$BUNDLED_SPEC" \
        --output-metadata "$METADATA"
else
    REF="${SPEC_REF:-stable/8.10}"
    echo "[bundle-spec] Fetching (ref: $REF) and bundling spec"
    $BUNDLER_CMD \
        --ref "$REF" \
        --deref-path-local \
        --output-spec "$BUNDLED_SPEC" \
        --output-metadata "$METADATA"
fi

echo "[bundle-spec] Bundled spec: $BUNDLED_SPEC"
echo "[bundle-spec] Metadata:     $METADATA"
