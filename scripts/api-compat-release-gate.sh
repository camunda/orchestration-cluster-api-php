#!/usr/bin/env bash
# Release gate for public API compatibility (issue #153), run by publish.yml on stable/**
# after `make generate-only` and before semantic-release.
#
# The release job regenerates from the upstream spec, so what gets released is the working
# tree, not HEAD: upstream drift lands here with no PR to review it. This compares the
# working tree with the newest published release of the branch's major and blocks the
# release on a break unless it is approved by either:
#   - the `api-break-approved` label on a PR merged into this branch since that release.
#     An approval covers the API as of that PR's merge: the working tree is then compared
#     with the newest approved merge, and any break added since (a later PR, or spec drift
#     picked up by this regeneration) needs its own approval; or
#   - DISPATCH_APPROVED=true (the api_break_approved input of a manual run), for drift no
#     PR introduced.
# A no-op on main, whose prereleases make no compatibility promise.
#
# Env: BRANCH (e.g. stable/10), GITHUB_REPOSITORY, GH_TOKEN, DISPATCH_APPROVED,
#      GITHUB_STEP_SUMMARY (optional).
# Exit codes: 0 release may proceed, 1 blocked by a break, 2 setup error.
set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)" || exit 2
cd "$ROOT" || exit 2
CHECK="${ROOT}/scripts/api-compat.sh"
LABEL="api-break-approved"
SUMMARY="${GITHUB_STEP_SUMMARY:-/dev/null}"

: "${BRANCH:?BRANCH is required}"
: "${GITHUB_REPOSITORY:?GITHUB_REPOSITORY is required}"
DISPATCH_APPROVED="${DISPATCH_APPROVED:-false}"

case "$BRANCH" in
  stable/*) ;;
  *)
    echo "::notice::${BRANCH} is not a stable branch; its releases make no compatibility promise."
    exit 0
    ;;
esac

major="${BRANCH#stable/}"
release=$(bash "$CHECK" baseline "$major") || exit 2
# The newest stable tag git holds for this major. Packagist can lag a just-pushed tag, so
# git is the cross-check.
newest_tag=$(git tag --list "v${major}.*" --sort=-v:refname \
  | grep -E "^v${major}\.[0-9]+\.[0-9]+$" | head -n1 || true)
if [ -z "$release" ]; then
  if [ -n "$newest_tag" ]; then
    echo "::error::Packagist lists no ${major}.x release but git has ${newest_tag}. Re-run once Packagist has indexed it."
    exit 2
  fi
  echo "::notice::No ${major}.x release published yet; nothing to compare against."
  exit 0
fi
version="${release% *}"
tag="v${version#v}"
git rev-parse -q --verify "refs/tags/${tag}" >/dev/null || {
  echo "::error::Baseline ${version} has no ${tag} tag to list the PRs merged since."
  exit 2
}
# If git already has a newer stable tag for this major than Packagist returned, the
# Packagist baseline is stale: comparing with it could pass a change that breaks the real
# latest release. Fail closed (a setup error) rather than guess.
if [ -n "$newest_tag" ] && [ "$newest_tag" != "$tag" ]; then
  higher=$(printf '%s\n%s\n' "${tag#v}" "${newest_tag#v}" | sort -V | tail -n1)
  if [ "$higher" = "${newest_tag#v}" ]; then
    echo "::error::Packagist baseline ${tag} is stale: git already has the newer tag ${newest_tag}. Re-run once Packagist has indexed it."
    exit 2
  fi
fi

echo "Baseline: ${tag}"
pipeline_status=(0 0)
bash "$CHECK" check "$tag" --worktree | tee "${RUNNER_TEMP:-/tmp}/api-compat.txt" || pipeline_status=("${PIPESTATUS[@]}")
[ "${pipeline_status[1]}" -eq 0 ] || exit 2
rc="${pipeline_status[0]}"
{
  echo '## API compatibility'
  echo '```'
  cat "${RUNNER_TEMP:-/tmp}/api-compat.txt" || exit 2
  echo '```'
} >> "$SUMMARY" || exit 2
[ "$rc" -eq 1 ] || exit "$rc"

if [ "$DISPATCH_APPROVED" = "true" ]; then
  echo "::warning::Breaking API changes approved by the api_break_approved input of this run."
  exit 0
fi

# PRs labelled api-break-approved, merged into this branch since the baseline tag.
approved=""
merges=""
# A reset or merged-back branch can reach the tag only through a second parent.
tag_sha=$(git rev-parse --verify "${tag}^{commit}") || exit 2
line=$(git rev-list --first-parent HEAD) || exit 2
commits=""
if grep -Fxq "$tag_sha" <<< "$line"; then
  commits=$(git log --first-parent --format=%H "${tag}..HEAD") || exit 2
else
  echo "::notice::${tag} is not on the first-parent history of HEAD; no label can approve this release."
fi
while read -r sha; do
  [ -n "$sha" ] || continue
  prs=$(gh api "repos/${GITHUB_REPOSITORY}/commits/${sha}/pulls" \
    --jq ".[] | select(.merged_at != null) | select(any(.labels[]; .name == \"${LABEL}\")) | [.number, .base.ref, .merge_commit_sha] | @tsv") || exit 2
  while IFS=$'\t' read -r number base merge; do
    if [ "$base" = "$BRANCH" ] && [ -n "$merge" ] && grep -Fxq "$merge" <<< "$commits"; then
      approved="${approved} #${number}"
      merges="${merges}${merge} #${number}"$'\n'
    fi
  done <<< "$prs"
done <<< "$commits"
approved=$(echo "$approved" | xargs -n1 2>/dev/null | sort -u | xargs)
if [ -z "$approved" ]; then
  echo "::error::Release blocked: the public API breaks compatibility with ${tag}. If intended, label the PR that made the change ${LABEL} (or re-run this workflow manually with api_break_approved, e.g. for upstream spec drift), then re-run."
  exit 1
fi

# An approval covers the API as of that PR's merge. `git log` lists the newest first.
latest=""
latest_pr=""
while read -r sha; do
  hit=$(grep -m1 "^${sha} " <<< "$merges" || true)
  if [ -n "$hit" ]; then
    latest="$sha"
    latest_pr="${hit#* }"
    break
  fi
done <<< "$commits"
# Compare even when the newest approved merge is HEAD: the working tree was regenerated
# from the current upstream spec and can differ from the committed code.
since=0
bash "$CHECK" check "$latest" --worktree || since=$?
if [ "$since" -eq 1 ]; then
  echo "::error::Release blocked: breaking API changes were added after ${latest_pr} (${latest}), the newest approved PR. Label the PR that made them ${LABEL} (or re-run this workflow manually with api_break_approved, e.g. for upstream spec drift), then re-run."
  exit 1
fi
[ "$since" -eq 0 ] || exit "$since"
echo "::warning::Breaking API changes approved with the ${LABEL} label on: ${approved}"
exit 0
