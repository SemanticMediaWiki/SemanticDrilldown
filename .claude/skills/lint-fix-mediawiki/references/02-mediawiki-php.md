**Coding Conventions — PHP · MediaWiki**

Tooling:
[mediawiki-codesniffer](https://github.com/wikimedia/mediawiki-tools-codesniffer)
via PHPCS. Run locally: `make composer-phpcs` (or `make ci`).

**Source directories**

- New code belongs in `src/` following PSR-4; `includes/` is legacy and
  should be migrated incrementally

**Namespaces**

- Top-level namespace = extension name (e.g.
  `MediaWiki\Extension\FooBar...`)

**Global variable prefix**

- Global variables: `$wg` prefix (e.g. `$wgPageFormsSettings`)

**Request handling**

- No superglobals (`$_GET`, `$_POST`) — use `WebRequest` via
  `RequestContext`

- No new global functions — use static utility classes (`Html`, `IP`) if
  needed
