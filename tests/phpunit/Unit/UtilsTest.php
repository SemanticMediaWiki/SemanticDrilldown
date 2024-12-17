<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\Utils;

/**
 * @covers \SD\Utils
 */
class UtilsTest extends TestCase {

	public function testSetGlobalJSVariables() {
		$vars = [];
		Utils::setGlobalJSVariables( $vars );
		$this->assertArrayHasKey( 'sdgDownArrowImage', $vars );
		$this->assertArrayHasKey( 'sdgRightArrowImage', $vars );
		$this->assertStringContainsString( 'extensions/SemanticDrilldown/skins', $vars['sdgDownArrowImage'] );
	}

	/**
	 * @dataProvider monthToStringProvider
	 */
	public function testMonthToString( $monthNumber, $expectedMonthName ) {
		$this->assertEquals( $expectedMonthName, Utils::monthToString( $monthNumber ) );
	}

	/**
	 * @dataProvider stringToMonthProvider
	 * @covers ::stringToMonth
	 */
	public function testStringToMonth( $monthNameKey, $expectedMonth ) {
		// Mock wfMessage to return the correct month name
		$messageMock = $this->getMockBuilder( \Message::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'text' ] )
			->getMock();

		// Set up wfMessage to simulate returning text values
		$messageMock->method( 'text' )->willReturnCallback( static function () use ( $monthNameKey ) {
			$monthNames = [
				'january' => 'January',
				'february' => 'February',
				'march' => 'March',
				'april' => 'April',
				'may_long' => 'May',
				'june' => 'June',
				'july' => 'July',
				'august' => 'August',
				'september' => 'September',
				'october' => 'October',
				'november' => 'November',
				'december' => 'December',
			];
			return $monthNames[$monthNameKey] ?? '';
		} );

		// Mock wfMessage global function
		$this->setFunctionOverride( 'wfMessage', static function ( $key ) use ( $messageMock ) {
			return $messageMock;
		} );

		// Call stringToMonth() and assert the result
		$monthString = $messageMock->text();
		$this->assertEquals( $expectedMonth, Utils::stringToMonth( $monthString ) );
	}

	public function testBooleanToString() {
		$mockMessage = $this->getMockBuilder( \Message::class )
			->disableOriginalConstructor()
			->getMock();
		$mockMessage->method( 'inContentLanguage' )->willReturnSelf();
		$mockMessage->method( 'text' )->willReturn( 'Yes,Yes,Yes' );
		$this->assertEquals( 'Yes', Utils::booleanToString( true ) );
	}

	public function testEscapeString() {
		$this->assertEquals( 'abc', Utils::escapeString( 'abc' ) );
		$this->assertEquals( '&lt;&gt;&amp;&quot;&#039;', Utils::escapeString( '<>&"\'' ) );
	}

	public function testGetNiceFilterValue() {
		$this->assertEquals( 'Other', Utils::getNiceFilterValue( '', ' other' ) );
		$this->assertEquals( 'None', Utils::getNiceFilterValue( '', ' none' ) );
		$this->assertEquals( 'Yes', Utils::getNiceFilterValue( 'boolean', '1' ) );
		$this->assertEquals( '2023-10-10', Utils::getNiceFilterValue( 'date', '2023-10-10//T' ) );
	}

	/**
	 * Helper method to override global functions like wfMessage.
	 *
	 * @param string $functionName
	 * @param callable $function
	 */
	private function setFunctionOverride( string $functionName, callable $function ) {
		// Store the override function in a global array
		$GLOBALS["{$functionName}_override"] = $function;

		// Check if the function exists, if not, create it dynamically
		if ( !function_exists( $functionName ) ) {
			// Define the function dynamically by assigning it to the global function name
			$GLOBALS[$functionName] = static function () use ( $functionName ) {
				// Call the override function from the global registry
				return call_user_func_array( $GLOBALS["{$functionName}_override"], func_get_args() );
			};
		}
	}

	/**
	 * Provides test cases for stringToMonth()
	 *
	 * @return array
	 */
	public function stringToMonthProvider() {
		return [
			'January'   => [ 'january', 1 ],
			'February'  => [ 'february', 2 ],
			'March'     => [ 'march', 3 ],
			'April'     => [ 'april', 4 ],
			'May'       => [ 'may_long', 5 ],
			'June'      => [ 'june', 6 ],
			'July'      => [ 'july', 7 ],
			'August'    => [ 'august', 8 ],
			'September' => [ 'september', 9 ],
			'October'   => [ 'october', 10 ],
			'November'  => [ 'november', 11 ],
			'December'  => [ 'december', 12 ],
		];
	}

	/**
	 * Provides data for monthToString() tests.
	 *
	 * @return array
	 */
	public function monthToStringProvider() {
		return [
			[ 1, 'January' ],
			[ 2, 'February' ],
			[ 3, 'March' ],
			[ 4, 'April' ],
			[ 5, 'May' ],
			[ 6, 'June' ],
			[ 7, 'July' ],
			[ 8, 'August' ],
			[ 9, 'September' ],
			[ 10, 'October' ],
			[ 11, 'November' ],
			[ 12, 'December' ],
		];
	}
}
