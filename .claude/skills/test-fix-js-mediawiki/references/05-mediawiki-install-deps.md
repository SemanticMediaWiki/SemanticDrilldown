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
