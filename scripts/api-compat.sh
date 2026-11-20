#!/usr/bin/env bash
# API compatibility checker for the public PHP API (issue #153).
#
# stable/N regenerates from upstream stable/8.N, so spec drift can change the public API
# with no one reviewing it (e.g. a getter's return type becoming nullable). This compares
# two revisions with roave/backward-compatibility-check, which reports removed or renamed
# classes, methods, properties and constants, changed signatures, parameter and return
# types (including nullability), and new abstract or interface methods. Additions pass.
# Symbols marked @internal are not covered.
#
# Usage:
#   scripts/api-compat.sh baseline <major>
#       Print "<version> <commit>" for the newest non-prerelease <major>.x.y on Packagist,
#       or nothing when that major has no stable release yet.
#   scripts/api-compat.sh check <from-ref> [<to-ref> | --worktree]
#       Compare <to-ref> (default HEAD) with <from-ref>. --worktree compares the working
#       tree, including uncommitted and untracked (non-ignored) files, e.g. right after
#       `make generate-only`.
#   scripts/api-compat.sh latest
#       Compare the working tree with the newest release of the major in src/Version.php
#       (`make api-compat`). Passes with a notice when that major has no release yet.
#
# Exit codes: 0 compatible, 1 breaking (each break listed), 2 setup error (including any
# checker exit other than 0 "completed, no breaks" or 3 "completed, breaks found").
#
# The checker lives in tools/api-compat (its own Composer project), so its dependencies
# never reach SDK consumers or constrain the SDK's dev dependencies. roave clones the
# repository it runs in, so run this from a regular clone, not a `git worktree`.
set -uo pipefail

PACKAGE="camunda8/orchestration-cluster-api"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)" || exit 2
TOOL_DIR="${ROOT}/tools/api-compat"
ROAVE="${TOOL_DIR}/vendor/bin/roave-backward-compatibility-check"

die() {
  echo "api-compat: $*" >&2
  exit 2
}

# Newest stable <major>.x.y on Packagist, as "<version> <commit>".
baseline() {
  local major="$1"
  [[ "$major" =~ ^[0-9]+$ ]] || die "major must be a number, got '${major}'"
  local url="https://repo.packagist.org/p2/${PACKAGE}.json"
  local json
  json=$(curl -fsSL --retry 3 --max-time 60 "$url") || die "could not fetch ${url}"
  local versions
  versions=$(jq -r --arg pkg "$PACKAGE" --arg major "$major" '
    .packages[$pkg][]
    | select(.version | test("^v?" + $major + "\\.[0-9]+\\.[0-9]+$"))
    | "\(.version) \(.source.reference)"' <<<"$json") \
    || die "could not parse the Packagist metadata"
  # Packagist lists releases newest first; sort anyway rather than rely on it.
  [ -z "$versions" ] || sort -V <<<"$versions" | tail -n1
}

ensure_tool() {
  if [ ! -x "$ROAVE" ]; then
    composer install --working-dir="$TOOL_DIR" --no-interaction --no-progress --quiet \
      || die "could not install tools/api-compat"
  fi
}

check() {
  local from="$1" to="${2:-HEAD}"
  cd "$ROOT" || exit 2
  [ -d .git ] || die "${ROOT} is not a regular git clone (roave cannot read a git worktree)"
  git rev-parse -q --verify "${from}^{commit}" >/dev/null || die "unknown revision '${from}'"

  SNAPSHOT_REF=""
  if [ "$to" = "--worktree" ]; then
    # Snapshot the working tree as a commit on a private ref (HEAD, the index and the
    # branch are left untouched). roave clones this repository, so the ref keeps the
    # snapshot's objects reachable in that clone.
    local index tree commit
    index=$(mktemp) || exit 2
    rm -f "$index"
    GIT_INDEX_FILE="$index" git read-tree HEAD || die "could not read HEAD"
    GIT_INDEX_FILE="$index" git add -A || die "could not stage the working tree"
    tree=$(GIT_INDEX_FILE="$index" git write-tree) || die "could not write the snapshot tree"
    rm -f "$index"
    commit=$(git -c user.name=api-compat -c user.email=api-compat@localhost \
      commit-tree "$tree" -p HEAD -m "api-compat working tree snapshot") \
      || die "could not create the snapshot commit"
    SNAPSHOT_REF="refs/api-compat/worktree"
    git update-ref "$SNAPSHOT_REF" "$commit" || die "could not create ${SNAPSHOT_REF}"
    trap 'git -C "$ROOT" update-ref -d "$SNAPSHOT_REF" >/dev/null 2>&1 || true' EXIT
    to="$commit"
  fi
  git rev-parse -q --verify "${to}^{commit}" >/dev/null || die "unknown revision '${to}'"

  ensure_tool
  local log
  log=$(mktemp) || exit 2
  local rc=0
  # The checker parses every class of both revisions; the default 128M is not enough.
  php -d memory_limit=-1 "$ROAVE" --from="$from" --to="$to" --format=console >"$log" 2>&1 || rc=$?

  local from_label to_label
  from_label=$(git describe --tags --exact-match "$from" 2>/dev/null || git rev-parse --short "$from")
  to_label=$(git describe --tags --exact-match "$to" 2>/dev/null || git rev-parse --short "$to")
  [ -z "$SNAPSHOT_REF" ] || to_label="working tree (${to_label})"
  echo "Compared ${to_label} with ${from_label}."

  # roave's contract: exit 0 = completed, no breaks; exit 3 = completed, breaks found
  # (AssertBackwardsCompatible). Any other exit means the run did not complete
  # (crashed, OOM'd, was killed) — possibly after printing some [BC] lines, so a
  # partial result must never be filtered into a pass or an overridable break.
  local breaks roave_status
  if [ "$rc" -ne 0 ] && [ "$rc" -ne 3 ]; then
    echo "api-compat: the checker did not complete (exit ${rc}):" >&2
    grep -v -E '^\s*$' "$log" | tail -n 40 >&2
    rm -f "$log"
    return 2
  fi
  # roave prints its results among composer install logs and a banner from one of its
  # dependencies: keep the result lines only.
  breaks=$(grep -E '^\[BC\] ' "$log" || true)
  if [ "$rc" -eq 0 ]; then
    roave_status=0
  else
    roave_status=1
    # roave counts a new enum case as a break (an exhaustive `match` without a default
    # arm stops being exhaustive). Upstream adds enum values routinely, and the server can
    # return values newer than the SDK anyway, so customer code needs a default arm
    # regardless: an added case is an addition, and additions pass. Other ADDED findings
    # (e.g. a method added to an interface, which breaks implementers) still fail.
    local added_cases
    added_cases=$(grep -E '^\[BC\] ADDED: Case ' <<<"$breaks" || true)
    breaks=$(grep -v -E '^\[BC\] ADDED: Case ' <<<"$breaks" || true)
    if [ -n "$added_cases" ]; then
      echo "Enum cases added (not a break):"
      sed -E 's/^\[BC\] ADDED: /  /' <<<"$added_cases"
    fi
    [ -n "$breaks" ] || roave_status=0
  fi
  rm -f "$log"

  # roave reports a removed enum case but not a changed backing value.
  local enum_breaks enum_status=0
  enum_breaks=$(php "${TOOL_DIR}/enum-values.php" "$ROOT" "$from" "$to") || enum_status=$?
  [ "$enum_status" -le 1 ] || return 2
  [ -z "$enum_breaks" ] || breaks=$(printf '%s\n%s' "$breaks" "$enum_breaks" | sed '/^$/d')

  if [ "$roave_status" -eq 0 ] && [ "$enum_status" -eq 0 ]; then
    echo "No backwards-incompatible changes."
    return 0
  fi
  echo "$breaks"
  echo "$(grep -c '^\[BC\] ' <<<"$breaks") backwards-incompatible changes."
  return 1
}

case "${1:-}" in
  baseline)
    [ $# -eq 2 ] || die "usage: $0 baseline <major>"
    baseline "$2"
    ;;
  check)
    [ $# -ge 2 ] && [ $# -le 3 ] || die "usage: $0 check <from-ref> [<to-ref> | --worktree]"
    check "$2" "${3:-HEAD}"
    ;;
  latest)
    # The working tree against the newest release of the major in src/Version.php.
    [ $# -eq 1 ] || die "usage: $0 latest"
    major=$(sed -n "s/.*const VALUE = '\([0-9]*\)\..*/\1/p" "${ROOT}/src/Version.php")
    [ -n "$major" ] || die "could not read the major version from src/Version.php"
    release=$(baseline "$major") || exit 2
    if [ -z "$release" ]; then
      echo "No ${major}.x release published yet; nothing to compare against."
      exit 0
    fi
    sha="${release#* }"
    git -C "$ROOT" cat-file -e "${sha}^{commit}" 2>/dev/null \
      || git -C "$ROOT" fetch -q --tags origin \
      || die "could not fetch ${release% *}"
    check "$sha" --worktree
    ;;
  *)
    die "usage: $0 baseline <major> | check <from-ref> [<to-ref> | --worktree] | latest"
    ;;
esac
