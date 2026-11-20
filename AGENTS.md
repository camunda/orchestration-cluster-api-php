# AGENTS.md

> **Note:** This file delegates to a central AGENTS.md. Read and apply it before proceeding.

**URL:**
https://raw.githubusercontent.com/camunda/.github/refs/heads/main/AGENTS.md

Treat the central file's contents as if they were written directly in this file.
Instructions below extend those guidelines and take precedence if there is any conflict.

<!-- sdk-infra-delegation:start -->
> **Note:** This file also delegates to the shared SDK AGENTS.md. Read and apply it too.

**URL:**
https://raw.githubusercontent.com/camunda/sdk-infra/refs/heads/main/policies/AGENTS.md

It holds the conventions shared by all Camunda SDK repos (commits, bug fixes, tests, opening issues). Precedence on conflict: this file, then the shared SDK file, then the central Camunda file.
<!-- sdk-infra-delegation:end -->

## Repo-specific instructions

### Role & boundary

This repo is the **PHP SDK Generator** for the Camunda 8 Orchestration Cluster API. It produces the `camunda8/orchestration-cluster-api` Composer package by generating a low-level client into `generated/` from an OpenAPI spec, then layering a hand-written runtime and a semantic value-object type system on top via post-generation hooks.

- **Project type**: PHP SDK generator + client library
- **Source language**: PHP 8.2+
- **Build/dependency**: `composer` (PHP), `npm` (release tooling), `make` (orchestration)
- **Generation**: `openapi-generator-cli` (`php-nextgen`, pinned in `openapitools.json`) + custom PHP hooks
- **Runtime**: Guzzle (PSR-18/PSR-7), `ext-pcntl` (optional, forked workers)
- **Analysis / testing**: PHPStan (level `max`), PHP-CS-Fixer, PHPUnit

Upstream dependencies — when they misbehave, fix them at the source rather than working around them here:

- [`camunda-schema-bundler`](https://github.com/camunda/camunda-schema-bundler) — fetches and bundles the upstream OpenAPI spec.
- [`openapi-generator`](https://github.com/OpenAPITools/openapi-generator) — generates `generated/`.
- [`camunda/camunda`](https://github.com/camunda/camunda) — source of the OpenAPI spec.

**Path map:**

| Path | Ownership and intent |
| --- | --- |
| `generate.php` | Main generation entry point: runs openapi-generator then the sorted post-gen hooks. |
| `hooks/post_gen/` | Hooks that transform the generated PHP. **Primary edit surface** for generator output and the semantic type system. |
| `hooks/post_gen/0100_semantic_types.php` | Emits the semantic value objects into `generated/semantic/`. |
| `hooks/post_gen/0200_serializer_semantic.php` | Patches `ObjectSerializer` to (de)serialize value objects. |
| `hooks/post_gen/0300_retype_models.php` | Retypes generated model properties from scalars to value objects. |
| `hooks/post_gen/0400_api_accessors.php` | Generates `src/ApiAccessors.php` (one accessor per API group). |
| `hooks/post_gen/0500_flat_facade.php` | Generates `src/GeneratedOperations.php` + `src/GeneratedAsyncOperations.php` — the flat facade forwarding all 244 operations onto the clients. |
| `generated/` | **Generated.** Produced by `make generate`. Never hand-edit. Tracked in git (Packagist installs the repo). |
| `src/` | Hand-written runtime: clients, config, auth, HTTP, job worker. **Primary edit surface** for runtime behaviour. Held at PHPStan `max`. |
| `examples/` | Compilable, statically-analysed usage examples. `examples/readme.php` is the source of truth for `README.md` snippets. |
| `examples/operation-map.json` | Maps API operations to example file + region, for docs coverage. |
| `tests/Acceptance/` | Fast unit tests (semantic types, config, serialization). No live Camunda required. `tests/Acceptance/Runtime/` holds the isolated tests of the hand-written runtime (auth, config, worker, HTTP wiring). |
| `tests/Support/` | Shared test doubles (queued PSR-18 client, fake clock, recording Guzzle, environment sandbox, job fixtures). |
| `tests/Integration/` | Integration tests against a real Camunda instance. Skipped unless `CAMUNDA_INTEGRATION=1`. |
| `docker/` | Local Camunda compose stack for integration tests. |
| `scripts/` | Bundle, README-sync, docs, config-reference, example-coverage, and release helpers. |
| `scripts/api-compat.sh`, `scripts/api-compat-release-gate.sh` | Public API compatibility checker and its release gate (see "API compatibility on stable branches"). |
| `tools/api-compat/` | The checker's own Composer project (`roave/backward-compatibility-check` + an enum-value check), kept apart so its dependencies never reach SDK consumers. Needs PHP 8.4+. |

### Non-negotiable invariants

- **`generated/` is never hand-edited.** All changes to generated output happen through a hook in `hooks/post_gen/`, so the pipeline stays reproducible (`make generate`).
- **`src/` stays green at PHPStan `max`** and passes PHP-CS-Fixer. `examples/` and `tests/` are analysed at the same level.
- **Semantic value objects are the core feature.** Every identifier is a distinct type in `Camunda\Orchestration\Semantic`. Do not collapse them back to `string`.
- **`src/GeneratedOperations.php` / `src/GeneratedAsyncOperations.php` are generated**, not hand-edited. They come from `hooks/post_gen/0500_flat_facade.php`; change the hook and re-run generation. They are marked `linguist-generated`.
- **The README configuration reference is generated** from `ConfigResolver::configReference()`. Edit that method and run `make config-reference`; CI enforces `make config-reference-check`.
- **`examples/operation-map.json` integrity is enforced** by `make example-coverage` in CI: every entry must resolve to a real spec operation and an existing example region.
- **README snippets are generated**, not written by hand. Edit the region in `examples/readme.php` and run `make sync-readme`.
- **`src/Version.php` is stamped by release tooling.** Do not bump it manually.
- **Spec ref is camunda `main` on this branch (Camunda 8.11, SDK 11.0.0-alpha.N); `stable/10` builds `stable/8.10`.** SDK major `n` ↔ server `8.n`. There is no `stable/8.11` until 8.11 ships.

### Common commands

| Command | Purpose |
| --- | --- |
| `make install` | Install PHP + Node dependencies. |
| `make generate` | Full pipeline: bundle spec → generate → hooks → lint-fix → phpstan → test. |
| `make generate-only` | Regenerate from the already-bundled spec (fast hook iteration). |
| `make test` | Run the acceptance test suite. |
| `make coverage` | Acceptance suite with a line-coverage report of `src/` (generated facades excluded). Needs pcov or Xdebug. |
| `make itest` | Run integration tests (requires a running cluster; see `make docker-start`). |
| `make phpstan` | Static analysis at level `max`. |
| `make security-scan` / `make security-selftest` | Psalm taint analysis of `src/` (CodeQL has no PHP support) / proof that the scan can find a known-vulnerable fixture. |
| `make lint` / `make lint-fix` | Coding-standards check / auto-fix. |
| `make sync-readme` / `make sync-readme-check` | Regenerate / verify README snippets. |
| `make docs-md` | Regenerate `docs/` reference markdown. |
| `make docs-docusaurus` | Regenerate `docs-md/php-sdk/` Docusaurus pages consumed by camunda-docs. |
| `make config-reference` / `make config-reference-check` | Regenerate / verify the README configuration reference. |
| `make example-coverage` | Verify `examples/operation-map.json` integrity and report example coverage. |
| `make api-compat` | Compare the working tree's public API with the newest release of its major (PHP 8.4+). |

### API compatibility on stable branches

`stable/N` regenerates from upstream `stable/8.N`, which keeps moving, so spec drift can
change the public API with no one reviewing it (e.g. a getter's return type becoming
nullable). `scripts/api-compat.sh` compares two revisions with
`roave/backward-compatibility-check`: removed or renamed classes, methods, properties,
constants and enum cases, changed signatures, parameter and return types (including
nullability) and new abstract or interface methods fail it; additions pass.
`tools/api-compat/enum-values.php` adds what roave misses: a changed enum backing value.
A new enum case passes: roave counts it as a break (an exhaustive `match` without a
`default` arm), but upstream adds values routinely and the server can return values newer
than the SDK, so customer code needs a `default` arm anyway.
Symbols marked `@internal` are not covered. A field that becomes required in a request
model is not a signature change (models take an array), so it is not caught. Exit codes:
0 compatible, 1 breaking (each break listed), 2 setup error.

- `make api-compat` checks the working tree against the newest Packagist release of the
  major in `src/Version.php`, and passes with a notice when that major has no release yet
  (`main` during alphas). `scripts/api-compat.sh check <from> [<to> | --worktree]`
  compares any two revisions. Run it from a regular clone: roave cannot read a
  `git worktree`.
- The `API compatibility` check (`api-compat.yml`) runs on PRs to `stable/**` and
  compares each PR with its base commit, so it answers only for its own changes. If a
  break is intended, label the PR `api-break-approved`: the check still lists the breaks
  but passes.
- The `API compatibility gate` job in `publish.yml` regenerates `stable/**` from the
  release's bundled spec and compares the result with the newest release. It passes on
  the `api-break-approved` label of a PR merged into the branch since that release, but
  only for the API as of that PR's merge: a break added afterwards, by a later PR or by
  spec drift picked up at release time, needs its own approval. A manual run with the
  `api_break_approved` input covers drift no PR introduced. On `main` it is a no-op.

### Backporting to stable branches

`.github/workflows/backport.yml` calls camunda/sdk-infra's shared `sdk-backport.yml` (`korthout/backport-action`, running as `camunda-sdk-automation`; the same flow as the other SDKs). It is configured by `.github/backport.yml` and `.github/actions/codegen-setup` (PHP, Java, Node), both read from the **target** branch, so keep them on `main` and on every live `stable/N`.

- Add the label `backport stable/<N>` to a PR. The backport PR opens when the PR merges, or right away if it is already merged.
- Or comment `/backport` on a merged PR to re-run it for its labels. PRs from forks can only be backported this way.

Backport hand-written runtime, hook and infra changes when the affected code exists on the stable branch. Never backport generated output on its own; each branch regenerates from its own spec.

After the cherry-pick, the workflow adds a `chore(gen): regenerate for backport of #N` commit. The derived files (`generated-paths`: `external-spec/`, `generated/`, `src/GeneratedOperations.php`, `src/GeneratedAsyncOperations.php`, `src/ApiAccessors.php`) are restored from the target branch and regenerated with `make generate-only`, then checked with `make test`. PRs are squash-merged, so the cherry-pick carries `main`'s copies, but a stable branch must build from its own spec. This also clears cherry-pick conflicts in those files. When a hook starts writing a new file outside `generated/`, add it to `generated-paths` on every branch.

If the cherry-pick conflicts, the action opens a **draft** PR with the conflicts committed. A draft always means a conflict, even without conflict markers: if the source changes a file the target lacks, `main`'s whole file is committed. Resolve it on the backport branch, then comment `/backport` on the source PR to regenerate. If fix B builds on fix A, merge A's backport first, then rebuild B's branch from the updated target (`git switch -C backport-<B>-to-stable/N origin/stable/N && git cherry-pick -x <B's merge commit>`, push with `--force-with-lease`, comment `/backport`).

If regenerating fails, the bot comments on the backport PR. If the change depends on spec the stable branch doesn't have, it can't be backported as-is: close the backport PR, or reduce it to what applies to the stable spec.

## Working on an issue

Every change must be traceable to a tracked, claimed, in-progress work item **before implementation starts**.

- **Start from a tracked issue or PR.** If none exists, create one first.
- **Claim it** and move it to in-progress so it is visibly owned.
- **Reference it** from the branch, commits, and PR (e.g. `Closes #123`).
- **One concern per issue/PR.** File a separate issue for unrelated problems.

The only exceptions are trivial, self-evident fixes (typos, broken links, formatting).

## Documentation (Audiences)

This repo serves two audiences:

- **End users** of the SDK → `README.md` (drives Docusaurus generation in `camunda-docs`; snippets are injected from `examples/readme.php`).
- **Contributors / agents** working on the generator → this file and `CONTRIBUTING.md`.
