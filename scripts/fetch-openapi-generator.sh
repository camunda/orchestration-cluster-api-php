#!/usr/bin/env bash
# Pre-fetch the openapi-generator jar pinned in openapitools.json into the
# storageDir configured there, so `npx @openapitools/openapi-generator-cli`
# finds it already downloaded instead of fetching it itself.
#
# Why: the CLI downloads the jar from Maven Central with a single attempt and no
# retry. A transient 404/5xx from the Maven CDN then fails `make generate-only`
# with "Unable to access jarfile". Here the download is retried, written
# atomically, and (in CI) restored from actions/cache so most runs make no
# network request at all.
set -euo pipefail

cd "$(dirname "$0")/.."

version=$(php -r '$c = json_decode(file_get_contents("openapitools.json"), true); echo $c["generator-cli"]["version"];')
storage=$(php -r '$c = json_decode(file_get_contents("openapitools.json"), true); echo $c["generator-cli"]["storageDir"] ?? "";')

if [[ -z "$version" || -z "$storage" ]]; then
  echo "openapitools.json must set generator-cli.version and generator-cli.storageDir" >&2
  exit 1
fi

storage="${storage/#\~/$HOME}"
jar="$storage/$version.jar"

if [[ -s "$jar" ]]; then
  echo "openapi-generator $version already present at $jar"
  exit 0
fi

mkdir -p "$storage"
url="https://repo1.maven.org/maven2/org/openapitools/openapi-generator-cli/$version/openapi-generator-cli-$version.jar"
echo "Downloading openapi-generator $version from $url"

# --retry-all-errors also retries on HTTP 4xx such as the transient 404 seen from
# the Maven CDN. The retry budget is a safety net against CDN blips, not a
# correctness signal: a version that genuinely does not exist still fails after
# the last attempt.
curl --fail --silent --show-error --location \
  --retry 6 --retry-delay 5 --retry-all-errors \
  --output "$jar.tmp" "$url"
mv "$jar.tmp" "$jar"
echo "openapi-generator $version saved to $jar"
