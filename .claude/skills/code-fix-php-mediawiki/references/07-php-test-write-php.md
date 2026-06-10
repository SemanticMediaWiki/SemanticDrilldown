**Procedure — test:write · PHP**

- Test class name = class under test + `Test` suffix (e.g.
  `MyClassTest`)

- Test class extends `\PHPUnit\Framework\TestCase` (or a subclass)

- One test class per production class; mirror the source tree under
  `tests/`

- Test methods start with `test` (e.g. `testParsesEmptyString`)

- Use `DataProvider` attribute (PHP 8.1+) for parameterised test cases:

``` php
#[DataProvider( 'provideParseData' )]
public function testParse( string $input, string $expected ): void {
    $this->assertSame( $expected, ( new MyParser() )->parse( $input ) );
}

public static function provideParseData(): array {
    return [
        'empty string' => [ '', '' ],
        'simple value' => [ 'foo', 'FOO' ],
    ];
}
```

- Assertions: prefer specific assertions (`assertSame`, `assertCount`,
  `assertInstanceOf`) over `assertTrue`

- One logical assertion per test; multiple assert\* calls are fine when
  they verify the same concern

- Test names describe behavior, not implementation:
  `testReturnsNullForMissingKey`, not `testMethod1`
