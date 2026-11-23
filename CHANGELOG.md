# [11.0.0-alpha.5](https://github.com/camunda/orchestration-cluster-api-php/compare/v11.0.0-alpha.4...v11.0.0-alpha.5) (2026-11-23)


### Bug Fixes

* **worker:** forked children use their own HTTP client and exit without destructors ([#188](https://github.com/camunda/orchestration-cluster-api-php/issues/188)) ([3985e6c](https://github.com/camunda/orchestration-cluster-api-php/commit/3985e6c5352af153a3efda79952ec191c44a9469)), closes [#187](https://github.com/camunda/orchestration-cluster-api-php/issues/187) [#187](https://github.com/camunda/orchestration-cluster-api-php/issues/187) [#187](https://github.com/camunda/orchestration-cluster-api-php/issues/187) [#187](https://github.com/camunda/orchestration-cluster-api-php/issues/187) [#187](https://github.com/camunda/orchestration-cluster-api-php/issues/187)

# [11.0.0-alpha.4](https://github.com/camunda/orchestration-cluster-api-php/compare/v11.0.0-alpha.3...v11.0.0-alpha.4) (2026-11-21)


### Bug Fixes

* derive default-tenant injection from the request body shape ([#179](https://github.com/camunda/orchestration-cluster-api-php/issues/179)) ([7beba21](https://github.com/camunda/orchestration-cluster-api-php/commit/7beba21ce337eeaec85f78cb3f9d55a1fbfb49d9)), closes [#178](https://github.com/camunda/orchestration-cluster-api-php/issues/178) [#178](https://github.com/camunda/orchestration-cluster-api-php/issues/178) [#178](https://github.com/camunda/orchestration-cluster-api-php/issues/178) [#178](https://github.com/camunda/orchestration-cluster-api-php/issues/178) [#178](https://github.com/camunda/orchestration-cluster-api-php/issues/178)

# [11.0.0-alpha.3](https://github.com/camunda/orchestration-cluster-api-php/compare/v11.0.0-alpha.2...v11.0.0-alpha.3) (2026-11-20)


### Bug Fixes

* **api-compat:** evaluate enum backing values; fix gate input description ([#177](https://github.com/camunda/orchestration-cluster-api-php/issues/177)) ([d8a7e89](https://github.com/camunda/orchestration-cluster-api-php/commit/d8a7e8914835ea446a4de5964e692222c71ba068)), closes [#176](https://github.com/camunda/orchestration-cluster-api-php/issues/176) [#153](https://github.com/camunda/orchestration-cluster-api-php/issues/153)

# [11.0.0-alpha.2](https://github.com/camunda/orchestration-cluster-api-php/compare/v11.0.0-alpha.1...v11.0.0-alpha.2) (2026-11-20)


### Bug Fixes

* serialize explicit null on nullable fields instead of dropping it ([#172](https://github.com/camunda/orchestration-cluster-api-php/issues/172)) ([3bc6f44](https://github.com/camunda/orchestration-cluster-api-php/commit/3bc6f44cce70e4e49bdb7dea27ccd6cc8a8f1be2)), closes [#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62) [#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62) [#158](https://github.com/camunda/orchestration-cluster-api-php/issues/158) [#161](https://github.com/camunda/orchestration-cluster-api-php/issues/161)

# [11.0.0-alpha.1](https://github.com/camunda/orchestration-cluster-api-php/compare/v10.0.0...v11.0.0-alpha.1) (2026-11-19)


### Bug Fixes

* async client rejects with ApiException on HTTP 4xx/5xx ([#86](https://github.com/camunda/orchestration-cluster-api-php/issues/86)) ([d30832c](https://github.com/camunda/orchestration-cluster-api-php/commit/d30832ce49e64e7cbfd230e8aa220169f522401e)), closes [#85](https://github.com/camunda/orchestration-cluster-api-php/issues/85) [#85](https://github.com/camunda/orchestration-cluster-api-php/issues/85)
* **ci:** run the release job on Node 22 ([#50](https://github.com/camunda/orchestration-cluster-api-php/issues/50)) ([4958e0d](https://github.com/camunda/orchestration-cluster-api-php/commit/4958e0dd974734a49e7f4e9b4a05f8767cd7e915))
* fail the job when a worker handler throws ([#114](https://github.com/camunda/orchestration-cluster-api-php/issues/114)) ([e7bf52e](https://github.com/camunda/orchestration-cluster-api-php/commit/e7bf52ecf928cf351b2d53dbe32c433209170904)), closes [#109](https://github.com/camunda/orchestration-cluster-api-php/issues/109)
* **generator:** detect setTenantId() type for default-tenant injection ([#137](https://github.com/camunda/orchestration-cluster-api-php/issues/137)) ([65a8e70](https://github.com/camunda/orchestration-cluster-api-php/commit/65a8e70654667b54ec8139f9565b87f9ac3d7c93)), closes [camunda/camunda#67056](https://github.com/camunda/camunda/issues/67056) [#136](https://github.com/camunda/orchestration-cluster-api-php/issues/136) [#136](https://github.com/camunda/orchestration-cluster-api-php/issues/136)
* inject CAMUNDA_TENANT_ID into tenant-aware operations ([#58](https://github.com/camunda/orchestration-cluster-api-php/issues/58)) ([8554fd4](https://github.com/camunda/orchestration-cluster-api-php/commit/8554fd44b409815e378f5824a2a9af4e3e168d76)), closes [#54](https://github.com/camunda/orchestration-cluster-api-php/issues/54)
* keep examples and tests phpstan-clean across spec refs ([#59](https://github.com/camunda/orchestration-cluster-api-php/issues/59)) ([52c23a6](https://github.com/camunda/orchestration-cluster-api-php/commit/52c23a6a16899372272bf4e6de4057bcd114746c)), closes [#55](https://github.com/camunda/orchestration-cluster-api-php/issues/55)
* omit unset optional fields from serialized request bodies ([#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62)) ([3dbb98d](https://github.com/camunda/orchestration-cluster-api-php/commit/3dbb98dee2f4bb9c1ddc108a468e457e2a789634)), closes [#53](https://github.com/camunda/orchestration-cluster-api-php/issues/53)
* redact config credentials from var_dump and json_encode ([#116](https://github.com/camunda/orchestration-cluster-api-php/issues/116)) ([d0e0b3e](https://github.com/camunda/orchestration-cluster-api-php/commit/d0e0b3e8ff174180083d8c032839c7b24f88c293)), closes [#111](https://github.com/camunda/orchestration-cluster-api-php/issues/111)
* retain semantic types on allOf-inherited model properties ([#64](https://github.com/camunda/orchestration-cluster-api-php/issues/64)) ([d74fadf](https://github.com/camunda/orchestration-cluster-api-php/commit/d74fadf258f28833e63a7cf26a7e0df356d156d5)), closes [#63](https://github.com/camunda/orchestration-cluster-api-php/issues/63)
* serialize boolean query parameters as true/false ([#57](https://github.com/camunda/orchestration-cluster-api-php/issues/57)) ([6758f46](https://github.com/camunda/orchestration-cluster-api-php/commit/6758f4667edebc05691d9b693a7111a58c0e513d)), closes [#51](https://github.com/camunda/orchestration-cluster-api-php/issues/51)
* serialize empty object-typed fields as {} instead of [] ([#56](https://github.com/camunda/orchestration-cluster-api-php/issues/56)) ([ee5b071](https://github.com/camunda/orchestration-cluster-api-php/commit/ee5b07116ade01d0153158b1a600bc6d54f1f602)), closes [#52](https://github.com/camunda/orchestration-cluster-api-php/issues/52)
* serialize key and enum exact-match filter properties ([#148](https://github.com/camunda/orchestration-cluster-api-php/issues/148)) ([48fd41f](https://github.com/camunda/orchestration-cluster-api-php/commit/48fd41fc879328992c6ee2436708229c6bb60e9e)), closes [#145](https://github.com/camunda/orchestration-cluster-api-php/issues/145) [#145](https://github.com/camunda/orchestration-cluster-api-php/issues/145)
* stop echoing the oauth error body into the exception message ([#115](https://github.com/camunda/orchestration-cluster-api-php/issues/115)) ([3b3f67e](https://github.com/camunda/orchestration-cluster-api-php/commit/3b3f67edf0082929237fee8936d4a926a2be8932)), closes [#110](https://github.com/camunda/orchestration-cluster-api-php/issues/110)
* sync client throws ApiException on HTTP 4xx/5xx for no-content operations ([#96](https://github.com/camunda/orchestration-cluster-api-php/issues/96)) ([409c288](https://github.com/camunda/orchestration-cluster-api-php/commit/409c288d8d8e1e431ef71b1adb81c659f9a043e3)), closes [#94](https://github.com/camunda/orchestration-cluster-api-php/issues/94) [#94](https://github.com/camunda/orchestration-cluster-api-php/issues/94)


### Build System

* track the Camunda 8.11 spec and server on main ([d6343f4](https://github.com/camunda/orchestration-cluster-api-php/commit/d6343f4abb6bff895c02abcbb6dd320210c92c7b))


### Features

* retry transient HTTP failures with exponential backoff ([#118](https://github.com/camunda/orchestration-cluster-api-php/issues/118)) ([4c1e829](https://github.com/camunda/orchestration-cluster-api-php/commit/4c1e829d367ef538a1e335d7329fd87d1e1ab368)), closes [#112](https://github.com/camunda/orchestration-cluster-api-php/issues/112) [#112](https://github.com/camunda/orchestration-cluster-api-php/issues/112) [#132](https://github.com/camunda/orchestration-cluster-api-php/issues/132) [#133](https://github.com/camunda/orchestration-cluster-api-php/issues/133) [#134](https://github.com/camunda/orchestration-cluster-api-php/issues/134) [#135](https://github.com/camunda/orchestration-cluster-api-php/issues/135)
* send the default tenant on tenant-scoped commands ([#139](https://github.com/camunda/orchestration-cluster-api-php/issues/139)) ([d631680](https://github.com/camunda/orchestration-cluster-api-php/commit/d63168032355e28c99e469e2f70d30df29f19423)), closes [#138](https://github.com/camunda/orchestration-cluster-api-php/issues/138) [#138](https://github.com/camunda/orchestration-cluster-api-php/issues/138) [#138](https://github.com/camunda/orchestration-cluster-api-php/issues/138) [#138](https://github.com/camunda/orchestration-cluster-api-php/issues/138)


### BREAKING CHANGES

* the SDK major tracks the Camunda server minor, so main
now builds the 11.x line (Camunda 8.11) and publishes 11.0.0-alpha.N
prereleases. 10.x continues on stable/10.
