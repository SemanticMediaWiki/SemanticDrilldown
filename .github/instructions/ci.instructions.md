---
applyTo: ".github/**,Makefile,build/**"
---
<!-- AUTO-GENERATED from docs/gesinn-it-docs-master-pub/documents/mediawiki/instructions/ci.adoc -->

**Execution — Install Dependencies · MediaWiki**

All tests run inside a containerized MediaWiki environment managed via
[docker-compose-ci](https://github.com/gesinn-it-pub/docker-compose-ci)
(the `build/` submodule). Never run tests directly against a local PHP
or Node.js installation.

Always run `make install` before executing tests to ensure that the
latest file changes are copied into the container. Changes to source or
test files on the host are **not** automatically reflected in a running
container.

<div class="note">

When a `docker-compose.override.yml` with a bind-mount of the extension
source directory is active (local development setup), `make install` is
only required at the start of a new session or after dependency changes.
For iterative test runs, use `make php-test` or `make dev-test`
directly.

</div>

``` console
make install
```

**Execution — Run Tests (PHPUnit) · MediaWiki**

Run all PHPUnit tests:

``` console
make install composer-phpunit
```

Run a single test class or method (filtered):

``` console
make install composer-phpunit COMPOSER_PARAMS="-- --filter YourTestName"
```

Run a specific test suite:

``` console
make install composer-phpunit COMPOSER_PARAMS="-- --testsuite your-suite-name"
```

For interactive use, bash into the running container:

``` console
make bash
> composer phpunit -- --filter YourTestName
```

**Execution — Run Phan · MediaWiki**

Run Phan against the codebase:

``` console
make composer-phan
```

**Fixing issues**

- Fix genuine type errors, undeclared-method, and undeclared-class
  issues in new code

- For issues in legacy code not touched by the current change, update
  the baseline instead of adding `@suppress`:

  ``` console
  make composer-phan-update-baseline
  ```

  This target re-generates the baseline and post-processes it with
  `unexpand` to convert Phan’s hardcoded 4-space indentation to tabs.
  Never run `--save-baseline` directly — the unprocessed output fails
  MediaWiki PHPCS.

- When `@suppress` is unavoidable, add an explanatory comment directly
  above it

**Baseline updates**

`.phan/baseline.php` is auto-generated. After updating it, commit it
together with the code change that necessitated the update.

**Execution — Run Tests (QUnit) · MediaWiki**

Run all JavaScript tests:

``` console
make install npm-test
```

There is no direct `make` target for filtering individual tests. Bash
into the running container to run a specific test file or test case:

``` console
make bash
> npm run node-qunit -- tests/node-qunit/yourtest.test.js
```

Filter by test description:

``` console
make bash
> npx qunit --require ./tests/node-qunit/setup.js 'tests/node-qunit/**/*.test.js' --filter "your test description"
```

**Execution — Pre-Commit Gate · MediaWiki**

Before every commit, run the full CI suite to confirm nothing is broken:

``` console
make ci
```

For interactive use (volume-mounted extension, no container rebuild),
use the faster pre-commit gate:

``` console
make dev-test
```

`dev-test` runs: lint → PHPCS → Phan → PHPUnit — without destroying
Docker volumes. Reserve `make ci` for the full pipeline verification.
