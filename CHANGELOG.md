# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

## [5.0.2] - 2026-06-11

Patch release fixing apostrophe handling in filter parameters, DROP privilege requirement, and config resolution during web-based upgrades.

### Fixed

- Filter lookup now works correctly when a property name contains an apostrophe character (e.g. `Date_d'écriture`). The apostrophe was previously escaped in the request-parameter key, causing a mismatch with the actual URL parameter sent by the browser. [`a324514`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/a324514) ([#55](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/55))
- Temporary tables are now dropped with `DROP TEMPORARY TABLE` instead of `DROP TABLE`, so the web database user no longer requires the `DROP` privilege. [`6a502b3`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/6a502b3) ([#144](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/144))
- Configuration values (`sdg*`) are now read via `GlobalVarConfig` instead of raw PHP globals, so they are correctly resolved from extension defaults during web-based upgrades (`mw-config` / `update.php`) where PHP globals are not populated. Values set in `LocalSettings.php` continue to work as before. [`2494aad`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/2494aad) ([#23](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/23))

## [5.0.1] - 2026-06-10

Patch release with bug fixes for filter behaviour, sorting, subcategory navigation, and combobox handling.

### Fixed
- Fix `requires` filter option having no effect: filters with `requires=SomeFilter` were never shown because the required-filter name was iterated as individual characters instead of as a filter name [`1b49cf3`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/1b49cf3) ([#4](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/4))
- Fix sorting to respect per-category sort keys (`[[Category:Foo|SortKey]]`) by joining `categorylinks` and using `cl_sortkey` instead of `smw_sortkey` alone [`a804fee`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/a804fee) ([#12](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/12))
- Fix missing back-navigation when browsing subcategories of subcategories: encode the full subcategory path in `_subcat` (slash-separated), render each level as a separate breadcrumb with its own remove link, and use only the deepest segment for DB queries [`6092eb7`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/6092eb7) ([#15](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/15))
- Fix "None(N)" filter returning fewer pages than its count: use `LEFT OUTER JOIN` for non-page property tables when the filter includes "none", allow `p_id IS NULL` in the WHERE clause for non-page types, and re-compute `$includes_none` per filter to prevent scoping bleed across multiple applied filters [`8ba2dbc`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/8ba2dbc) ([#17](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/17))
- Fix combobox filter failing for property values containing `&`: replace the first-option-value hack with `data-mw-input-name`/`data-mw-filter-name` attributes; when a value is selected from the dropdown, submit it as a direct-match parameter instead of a search term, consistent with clicking a filter link [`6f84591`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/6f84591) ([#18](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/18))

### Changed
- Replace global config access with `getConfig()`/`GlobalVarConfig` in `SpecialBrowseData`; remove `FunctionConfigUsage` PHPCS exclude [`98a7d4e`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/98a7d4e) ([#139](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/139))
- Apply JS coding conventions to `ui.combobox`: `$`-prefix for jQuery objects, explicit `return undefined` in `map` callback, `{jQuery}` JSDoc type, `.trigger('focus')` over `.focus()`; add 18 QUnit tests covering widget init, source callback, select handler, button click, `_renderItem`, `toggleValuesDisplay`, and `removePagingIfNotRequired` — raising statement coverage from 57 % to 98 % [`e926161`](https://github.com/SemanticMediaWiki/SemanticDrilldown/commit/e926161) ([#142](https://github.com/SemanticMediaWiki/SemanticDrilldown/issues/142))

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

[Unreleased]: https://github.com/SemanticMediaWiki/SemanticDrilldown/compare/5.0.2...HEAD
[5.0.2]: https://github.com/SemanticMediaWiki/SemanticDrilldown/compare/5.0.1...5.0.2
[5.0.1]: https://github.com/SemanticMediaWiki/SemanticDrilldown/compare/5.0.0...5.0.1
[5.0.0]: https://github.com/SemanticMediaWiki/SemanticDrilldown/releases/tag/5.0.0
