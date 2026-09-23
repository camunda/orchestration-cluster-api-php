# AGENTS.md

> **Note:** This file delegates to a central AGENTS.md. Read and apply it before proceeding.

**URL:**
https://raw.githubusercontent.com/camunda/.github/refs/heads/main/AGENTS.md

Treat the central file's contents as if they were written directly in this file.
Instructions below extend those guidelines and take precedence if there is any conflict.

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
| `tests/Acceptance/` | Fast unit tests (semantic types, config, serialization). No live Camunda required. |
| `tests/Integration/` | Integration tests against a real Camunda instance. Skipped unless `CAMUNDA_INTEGRATION=1`. |
| `docker/` | Local Camunda compose stack for integration tests. |
| `scripts/` | Bundle, README-sync, docs, config-reference, example-coverage, and release helpers. |

### Non-negotiable invariants

- **`generated/` is never hand-edited.** All changes to generated output happen through a hook in `hooks/post_gen/`, so the pipeline stays reproducible (`make generate`).
- **`src/` stays green at PHPStan `max`** and passes PHP-CS-Fixer. `examples/` and `tests/` are analysed at the same level.
- **Semantic value objects are the core feature.** Every identifier is a distinct type in `Camunda\Orchestration\Semantic`. Do not collapse them back to `string`.
- **`src/GeneratedOperations.php` / `src/GeneratedAsyncOperations.php` are generated**, not hand-edited. They come from `hooks/post_gen/0500_flat_facade.php`; change the hook and re-run generation. They are marked `linguist-generated`.
- **The README configuration reference is generated** from `ConfigResolver::configReference()`. Edit that method and run `make config-reference`; CI enforces `make config-reference-check`.
- **`examples/operation-map.json` integrity is enforced** by `make example-coverage` in CI: every entry must resolve to a real spec operation and an existing example region.
- **README snippets are generated**, not written by hand. Edit the region in `examples/readme.php` and run `make sync-readme`.
- **`src/Version.php` is stamped by release tooling.** Do not bump it manually.
- **Spec ref is `stable/8.10`.** SDK major `n` ↔ server `8.n`.

### Common commands

| Command | Purpose |
| --- | --- |
| `make install` | Install PHP + Node dependencies. |
| `make generate` | Full pipeline: bundle spec → generate → hooks → lint-fix → phpstan → test. |
| `make generate-only` | Regenerate from the already-bundled spec (fast hook iteration). |
| `make test` | Run the acceptance test suite. |
| `make itest` | Run integration tests (requires a running cluster; see `make docker-start`). |
| `make phpstan` | Static analysis at level `max`. |
| `make lint` / `make lint-fix` | Coding-standards check / auto-fix. |
| `make sync-readme` / `make sync-readme-check` | Regenerate / verify README snippets. |
| `make docs-md` | Regenerate `docs/` reference markdown. |
| `make docs-docusaurus` | Regenerate `docs-md/php-sdk/` Docusaurus pages consumed by camunda-docs. |
| `make config-reference` / `make config-reference-check` | Regenerate / verify the README configuration reference. |
| `make example-coverage` | Verify `examples/operation-map.json` integrity and report example coverage. |

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
