.PHONY: install generate generate-only bundle-spec clean test itest lint lint-fix phpstan check sync-readme sync-readme-check docs-md config-reference config-reference-check example-coverage

# Git ref/branch/tag/SHA in https://github.com/camunda/camunda.git to fetch the OpenAPI
# spec from. Override like: `make generate SPEC_REF=stable/8.9`
SPEC_REF ?= stable/8.10

BUNDLED_SPEC = external-spec/bundled/rest-api.bundle.json

install:
	composer install
	npm ci || npm install

# Fetch & bundle the upstream OpenAPI spec using camunda-schema-bundler.
# Produces external-spec/bundled/rest-api.bundle.json + spec-metadata.json
bundle-spec:
	SPEC_REF=$(SPEC_REF) bash scripts/bundle-spec.sh

# Full generate: bundle spec, run openapi-generator, run post-gen hooks, validate.
generate: clean bundle-spec generate-only lint-fix phpstan test

# Pure generation only (no bundle fetch, no dependency install) — fast local iteration
# and CI (which bundles the spec once as a separate step).
generate-only:
	php generate.php --spec $(BUNDLED_SPEC)
	composer dump-autoload -q

clean:
	rm -rf generated

test:
	vendor/bin/phpunit --testsuite acceptance

itest:
	CAMUNDA_INTEGRATION=1 vendor/bin/phpunit --testsuite integration

lint:
	vendor/bin/php-cs-fixer fix --dry-run --diff

lint-fix:
	vendor/bin/php-cs-fixer fix

phpstan:
	vendor/bin/phpstan analyse --memory-limit=1G

check: lint phpstan test

sync-readme:
	php scripts/sync-readme-snippets.php

sync-readme-check:
	php scripts/sync-readme-snippets.php --check

docs-md:
	php scripts/generate-docs.php

config-reference:
	php scripts/generate-config-reference.php

config-reference-check:
	php scripts/generate-config-reference.php --check

example-coverage:
	php scripts/check-example-coverage.php

docker-start:
	docker compose -f docker/docker-compose.yaml up -d

docker-stop:
	docker compose -f docker/docker-compose.yaml down
