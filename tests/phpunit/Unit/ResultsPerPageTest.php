<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\Parameters\Parameters;
use SD\Parameters\ResultsPerPage;

/**
 * @covers \SD\Parameters\ResultsPerPage
 * @covers \SD\Parameters\Parameters::resultsPerPage
 */
class ResultsPerPageTest extends TestCase {

	/**
	 * @dataProvider valueProvider
	 */
	public function testToNumber( ?string $value, ?int $expected ) {
		$this->assertSame( $expected, ResultsPerPage::toNumber( $value ) );
	}

	public static function valueProvider(): array {
		return [
			'no value' => [ null, null ],
			'empty' => [ '', null ],
			'number' => [ '25', 25 ],
			'number with surrounding space' => [ ' 25 ', 25 ],
			'one' => [ '1', 1 ],
			'zero' => [ '0', null ],
			'negative' => [ '-5', null ],
			'text' => [ 'many', null ],
			'text after a number' => [ '25 results', null ],
			'decimal' => [ '2.5', null ],
			'too large for an integer' => [ '99999999999999999999', null ],
		];
	}

	public function testParametersProvideTheNumber() {
		$parameters = new Parameters( null, null, null, null, null, null, '40' );

		$this->assertSame( 40, $parameters->resultsPerPage() );
	}

	public function testParametersWithoutTheParameterHaveNoNumber() {
		$this->assertNull( ( new Parameters() )->resultsPerPage() );
	}

	public function testParametersIgnoreAnInvalidNumber() {
		$this->assertNull( ( new Parameters( null, null, null, null, null, null, 'many' ) )->resultsPerPage() );
	}
}
