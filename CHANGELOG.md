## [10.1.1](https://github.com/camunda/orchestration-cluster-api-php/compare/v10.1.0...v10.1.1) (2026-11-23)


### Bug Fixes

* **worker:** forked children use their own HTTP client and exit without destructors (backport [#188](https://github.com/camunda/orchestration-cluster-api-php/issues/188) to stable/10) ([#189](https://github.com/camunda/orchestration-cluster-api-php/issues/189)) ([bb1fe78](https://github.com/camunda/orchestration-cluster-api-php/commit/bb1fe78a0e48fd15a6b27d67481e0fd84f997308)), closes [#187](https://github.com/camunda/orchestration-cluster-api-php/issues/187)

# [10.1.0](https://github.com/camunda/orchestration-cluster-api-php/compare/v10.0.3...v10.1.0) (2026-11-21)


### Features

* send the default tenant on tenant-scoped commands (backport [#139](https://github.com/camunda/orchestration-cluster-api-php/issues/139) to stable/10) ([#181](https://github.com/camunda/orchestration-cluster-api-php/issues/181)) ([50f4d9b](https://github.com/camunda/orchestration-cluster-api-php/commit/50f4d9b95b3ec8c669e2bf0c7485117b36a1d2cb)), closes [#138](https://github.com/camunda/orchestration-cluster-api-php/issues/138) [#180](https://github.com/camunda/orchestration-cluster-api-php/issues/180)

## [10.0.3](https://github.com/camunda/orchestration-cluster-api-php/compare/v10.0.2...v10.0.3) (2026-11-20)


### Bug Fixes

* serialize explicit null on nullable fields instead of dropping it ([#172](https://github.com/camunda/orchestration-cluster-api-php/issues/172)) ([#173](https://github.com/camunda/orchestration-cluster-api-php/issues/173)) ([f7708c3](https://github.com/camunda/orchestration-cluster-api-php/commit/f7708c3670cf0f4f0e1e59292fa58cc82329a7a9)), closes [#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62) [#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62) [#158](https://github.com/camunda/orchestration-cluster-api-php/issues/158) [#161](https://github.com/camunda/orchestration-cluster-api-php/issues/161)

## [10.0.2](https://github.com/camunda/orchestration-cluster-api-php/compare/v10.0.1...v10.0.2) (2026-11-19)


### Bug Fixes

* keep key and enum exact-match filter values (stable/10 backport of [#145](https://github.com/camunda/orchestration-cluster-api-php/issues/145)) ([#149](https://github.com/camunda/orchestration-cluster-api-php/issues/149)) ([dcbef7b](https://github.com/camunda/orchestration-cluster-api-php/commit/dcbef7b83a5519d544a2d3446291c4bf53ce9cc9)), closes [#147](https://github.com/camunda/orchestration-cluster-api-php/issues/147) [#147](https://github.com/camunda/orchestration-cluster-api-php/issues/147) [#147](https://github.com/camunda/orchestration-cluster-api-php/issues/147) [#147](https://github.com/camunda/orchestration-cluster-api-php/issues/147) [#147](https://github.com/camunda/orchestration-cluster-api-php/issues/147)

## [10.0.1](https://github.com/camunda/orchestration-cluster-api-php/compare/v10.0.0...v10.0.1) (2026-11-19)


### Bug Fixes

* async client rejects with ApiException on HTTP 4xx/5xx (backport [#86](https://github.com/camunda/orchestration-cluster-api-php/issues/86) to stable/10) ([#159](https://github.com/camunda/orchestration-cluster-api-php/issues/159)) ([f7be540](https://github.com/camunda/orchestration-cluster-api-php/commit/f7be54072d27655ad31a7dc37114f1d6da22996a))
* backport conformance fixes to stable/10 ([#61](https://github.com/camunda/orchestration-cluster-api-php/issues/61)) ([fbdb016](https://github.com/camunda/orchestration-cluster-api-php/commit/fbdb016675ac46e87932e647fe4543b2c7bc34d2)), closes [#51](https://github.com/camunda/orchestration-cluster-api-php/issues/51) [#52](https://github.com/camunda/orchestration-cluster-api-php/issues/52) [#54](https://github.com/camunda/orchestration-cluster-api-php/issues/54) [#60](https://github.com/camunda/orchestration-cluster-api-php/issues/60)
* fail the job when a worker handler throws (backport [#114](https://github.com/camunda/orchestration-cluster-api-php/issues/114) to stable/10) ([#157](https://github.com/camunda/orchestration-cluster-api-php/issues/157)) ([b5b2f5b](https://github.com/camunda/orchestration-cluster-api-php/commit/b5b2f5b2cfe16223287755704fbf688434d32a75)), closes [#109](https://github.com/camunda/orchestration-cluster-api-php/issues/109)
* follow allOf-inherited properties when retyping semantic value objects ([#66](https://github.com/camunda/orchestration-cluster-api-php/issues/66)) ([8936ee7](https://github.com/camunda/orchestration-cluster-api-php/commit/8936ee7ce399a7f85f262c18188956adfa93f9e1)), closes [#63](https://github.com/camunda/orchestration-cluster-api-php/issues/63) [#65](https://github.com/camunda/orchestration-cluster-api-php/issues/65)
* omit unset optional fields from serialized request bodies (backport [#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62) to stable/10) ([#158](https://github.com/camunda/orchestration-cluster-api-php/issues/158)) ([41de098](https://github.com/camunda/orchestration-cluster-api-php/commit/41de098d0ee7e553617310744d9176c26f28d711)), closes [#66](https://github.com/camunda/orchestration-cluster-api-php/issues/66)
* redact config credentials from var_dump and json_encode (backport [#116](https://github.com/camunda/orchestration-cluster-api-php/issues/116) to stable/10) ([#167](https://github.com/camunda/orchestration-cluster-api-php/issues/167)) ([05e3fd1](https://github.com/camunda/orchestration-cluster-api-php/commit/05e3fd166cfcbb17c3bc4ff90ec2022566313eb9))
* stop echoing the oauth error body into the exception message ([#115](https://github.com/camunda/orchestration-cluster-api-php/issues/115)) ([#160](https://github.com/camunda/orchestration-cluster-api-php/issues/160)) ([c4be208](https://github.com/camunda/orchestration-cluster-api-php/commit/c4be208de85de811282cddc3eb92f5e7b569f2af)), closes [#110](https://github.com/camunda/orchestration-cluster-api-php/issues/110)
* surface getter exceptions for unset model properties during serialization ([#169](https://github.com/camunda/orchestration-cluster-api-php/issues/169)) ([c93b572](https://github.com/camunda/orchestration-cluster-api-php/commit/c93b57260eca7f7b2979f2a86ea417f757a2e9aa)), closes [#62](https://github.com/camunda/orchestration-cluster-api-php/issues/62) [#158](https://github.com/camunda/orchestration-cluster-api-php/issues/158) [#168](https://github.com/camunda/orchestration-cluster-api-php/issues/168)
* throw ApiException on HTTP 4xx/5xx for sync no-content operations (stable/10) ([#98](https://github.com/camunda/orchestration-cluster-api-php/issues/98)) ([846fedf](https://github.com/camunda/orchestration-cluster-api-php/commit/846fedf203c7c678db2e2edada4afc7e511e7ffa)), closes [#94](https://github.com/camunda/orchestration-cluster-api-php/issues/94) [#97](https://github.com/camunda/orchestration-cluster-api-php/issues/97)
