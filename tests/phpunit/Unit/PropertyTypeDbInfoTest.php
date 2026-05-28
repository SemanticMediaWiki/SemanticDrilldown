<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\Sql\PropertyTypeDbInfo;

/**
 * @covers \SD\Sql\PropertyTypeDbInfo
 */
class PropertyTypeDbInfoTest extends TestCase {

	// ---------------------------------------------------------------------------

	/** Tests – dateField() */
	public function testDateFieldMysqlPadsIncompleteYearOnlyDatesWithSlashZeroOneZeroOne(): void {
		global $wgDBtype;
		$wgDBtype = 'mysql';

		$result = PropertyTypeDbInfo::dateField( 'date' );

		// The expression must CONCAT '/01/01' so that year-only values like
		// '1900' become '1900/01/01' before STR_TO_DATE parses them.
		// Without this padding, STR_TO_DATE('%Y/%m/%d') returns NULL for
		// year-only or month-precision dates (PR #134).
		$this->assertStringContainsString( "CONCAT", $result );
		$this->assertStringContainsString( "'/01/01'", $result );
		$this->assertStringContainsString( "STR_TO_DATE", $result );
		$this->assertStringContainsString( "SUBSTR(o_serialized, 3, 100)", $result );
	}

	public function testDateFieldMysqlExpressionDoesNotReturnNullForYearOnlySerializedValue(): void {
		global $wgDBtype;
		$wgDBtype = 'mysql';

		$result = PropertyTypeDbInfo::dateField( 'date' );

		// STR_TO_DATE with '%Y/%m/%d' requires all three components.
		// The fix appends '/01/01' so year-only (e.g. '1900') becomes
		// '1900/01/01' – MySQL surplus-input tolerance makes this safe for
		// full dates too (e.g. '1900/6/15/01/01' → 1900-06-15).
		$this->assertStringContainsString( "STR_TO_DATE(CONCAT(SUBSTR(o_serialized, 3, 100), '/01/01'), '%Y/%m/%d')", $result );
	}

	public function testDateFieldNonDateTypeReturnsNull(): void {
		$this->assertNull( PropertyTypeDbInfo::dateField( 'text' ) );
		$this->assertNull( PropertyTypeDbInfo::dateField( 'number' ) );
		$this->assertNull( PropertyTypeDbInfo::dateField( 'page' ) );
	}

	public function testDateFieldSqliteProducesDateExpression(): void {
		global $wgDBtype;
		$wgDBtype = 'sqlite';

		$result = PropertyTypeDbInfo::dateField( 'date' );

		$this->assertStringContainsString( 'DATE(', $result );
		$this->assertStringContainsString( 'SUBSTR', $result );
	}

}
