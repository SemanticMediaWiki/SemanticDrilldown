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
