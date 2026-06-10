# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [5.0.0] - 2026-06-10

Initial stable release. Establishes release management and changelog baseline.

### Fixed
- Restore singleton and avoid stale container reference [`df129d5`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/df129d5)
- Treat year-only and month-precision dates as YYYY/01/01 in date range filters [`298659f`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/298659f)
- Ignore blank search terms before creating applied filter [`137aed8`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/137aed8) ([#121](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/121))
- Downgrade eslint-config-wikimedia to 0.31.0, disable crashing mediawiki rule [`627ec63`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/627ec63)

### Changed
- Make mwServices() protected for testability [`a83e8c5`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/a83e8c5)
- Modernize JavaScript [`15362013`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/15362013) ([#132](https://github.com/SemanticMediaWiki/SemanticDrilldown/pull/132))
- Update docker-compose-ci submodule [`49c10d9`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/49c10d9)
- Migrate to DOCSMP attributes file, add CONTRIBUTING guide [`0e13037`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/0e13037)

[Unreleased]: https://github.com/SemanticMediaWiki/SemanticDrilldown/compare/5.0.0...HEAD
[5.0.0]: https://github.com/SemanticMediaWiki/SemanticDrilldown/releases/tag/5.0.0
