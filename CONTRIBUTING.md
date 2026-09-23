# Contributing

Thanks for helping improve the Camunda Orchestration Cluster API PHP SDK. This document covers the development workflow; see [AGENTS.md](AGENTS.md) for the repository's invariants and path map.

## Architecture in one minute

The published package is produced by a reproducible pipeline:

```
camunda-schema-bundler          openapi-generator (php-nextgen)      hooks/post_gen/*.php
upstream OpenAPI spec  ───▶  external-spec/bundled/  ───▶  generated/  ───▶  generated/ (transformed)
```

- `generated/` is **machine-produced** and committed to git (Packagist installs the repo directly). Never hand-edit it — change a hook instead.
- `src/` is the **hand-written runtime**: the sync/async clients, configuration resolver, auth providers, HTTP middleware, and the job worker.
- The **semantic value-object type system** (`generated/semantic/`, plus the model retyping) is the SDK's defining feature: every identifier is a distinct, self-validating type.

## Prerequisites

- PHP 8.2+ with `ext-json`, `ext-mbstring`, and (optionally) `ext-pcntl`
- Composer 2
- Node.js 20+ (release tooling and the schema bundler)
- Java 21 (openapi-generator runs on the JVM)
- Docker (integration tests)

## Getting started

```bash
make install          # composer install + npm install
make generate         # bundle spec, generate, run hooks, lint, phpstan, test
```

For fast iteration on the hooks, bundle once and then regenerate only:

```bash
make bundle-spec
make generate-only
```

## Before you open a PR

Run the full local gate:

```bash
make check              # lint + phpstan (level max) + acceptance tests
make sync-readme-check  # verify README snippets match examples/
make config-reference-check # verify the README config reference is in sync
make example-coverage   # verify examples/operation-map.json integrity
```

If you changed anything user-facing:

- Update the relevant region in `examples/readme.php` and run `make sync-readme`.
- Add or update an example in `examples/` and reference it from `examples/operation-map.json`.
- If you changed configuration, update `ConfigResolver::configReference()` and run `make config-reference`.
- Regenerate the reference docs with `make docs-md`.

## Testing

- **Acceptance tests** (`tests/Acceptance/`) are fast and require no cluster: `make test`.
- **Integration tests** (`tests/Integration/`) run against a live cluster:

  ```bash
  make docker-start
  make itest
  make docker-stop
  ```

  They are skipped automatically unless `CAMUNDA_INTEGRATION=1` is set.

## Commit messages & releases

This repo uses [Conventional Commits](https://www.conventionalcommits.org/) enforced by commitlint. Releases are automated with semantic-release: merging to `main` stamps `src/Version.php`, tags the commit, and Packagist syncs the new version from the tag.

- `feat:` / `fix:` drive minor / patch releases.
- Breaking changes (`!` or a `BREAKING CHANGE:` footer) drive a major release; the SDK major tracks the Camunda server minor (`n` ↔ `8.n`).

Packagist sync is driven from CI (no persistent webhook): on a successful release, `scripts/notify-packagist.sh` calls the Packagist `update-package` API. It needs two repo/org settings to be configured:

- `PACKAGIST_USERNAME` — Actions **variable**: the Packagist account name.
- `PACKAGIST_TOKEN` — Actions **secret**: the Packagist **SAFE** API token.

When they are absent the release still succeeds; the notification is skipped and Packagist picks up the tag on its next periodic crawl.

## Coding standards

- Keep `src/`, `examples/`, and `tests/` green at PHPStan level `max`.
- Follow the PHP-CS-Fixer ruleset (`make lint-fix`).
- Prefer editing a hook over hand-editing generated code — always.
