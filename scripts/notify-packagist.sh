#!/usr/bin/env bash
# Ask Packagist to fetch the tag that semantic-release just pushed.
#
# Invoked by @semantic-release/exec successCmd, so it only runs when a release
# is actually published. Uses the Packagist "update-package" API, which the
# Packagist docs recommend driving from CI with the SAFE API token.
#
# Credentials come from the environment (set by the Release job):
#   PACKAGIST_USERNAME - Packagist account name
#   PACKAGIST_TOKEN    - Packagist SAFE API token
#
# When credentials are absent (forks, re-runs without secrets) the script
# no-ops so the release still succeeds. A failed notification is logged but
# never fails the release: the tag is already pushed and Packagist also crawls
# tracked repositories periodically as a fallback.
set -euo pipefail

VERSION="${1:-unknown}"
REPO_URL="https://github.com/camunda/orchestration-cluster-api-php"

if [[ -z "${PACKAGIST_USERNAME:-}" || -z "${PACKAGIST_TOKEN:-}" ]]; then
  echo "[notify-packagist] PACKAGIST_USERNAME/PACKAGIST_TOKEN not set — skipping Packagist sync for ${VERSION}." >&2
  exit 0
fi

echo "[notify-packagist] Requesting Packagist update for ${REPO_URL} (release ${VERSION})"

response_body="$(mktemp)"
trap 'rm -f "${response_body}"' EXIT

http_code="$(curl -sS -o "${response_body}" -w '%{http_code}' \
  --connect-timeout 10 --max-time 30 \
  -X POST \
  -H 'Content-Type: application/json' \
  -H 'User-Agent: camunda-orchestration-cluster-api-php-release (mailto:info@camunda.com)' \
  "https://packagist.org/api/update-package?username=${PACKAGIST_USERNAME}&apiToken=${PACKAGIST_TOKEN}" \
  -d "{\"repository\":{\"url\":\"${REPO_URL}\"}}" || echo "000")"

echo "[notify-packagist] HTTP ${http_code}: $(cat "${response_body}")" >&2

if [[ "${http_code}" != "200" ]]; then
  echo "[notify-packagist] WARNING: Packagist update was not accepted (HTTP ${http_code}). The tag is published; Packagist will pick it up on its next crawl." >&2
  exit 0
fi

echo "[notify-packagist] Packagist update accepted for release ${VERSION}."
