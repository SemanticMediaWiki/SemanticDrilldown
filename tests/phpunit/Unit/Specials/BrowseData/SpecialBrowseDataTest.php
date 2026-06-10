<?php

declare( strict_types=1 );

namespace SD\Tests\Unit\Specials\BrowseData;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SD\Specials\BrowseData\SpecialBrowseData;

/**
 * @covers \SD\Specials\BrowseData\SpecialBrowseData
 */
class SpecialBrowseDataTest extends TestCase {

	private function filterNameToRequestKey( string $name ): string {
		$method = new ReflectionMethod( SpecialBrowseData::class, 'filterNameToRequestKey' );
		$method->setAccessible( true );
		return $method->invoke( null, $name );
	}

	/**
	 * @dataProvider provideFilterNameToRequestKey
	 */
	public function testFilterNameToRequestKey( string $input, string $expected ): void {
		$this->assertSame( $expected, $this->filterNameToRequestKey( $input ) );
	}

	public static function provideFilterNameToRequestKey(): array {
		return [
			'plain name unchanged' => [ 'MyFilter', 'MyFilter' ],
			'spaces replaced by underscores' => [ 'My Filter', 'My_Filter' ],
			'apostrophe preserved as-is' => [ "Date_d'écriture", "Date_d'écriture" ],
			'apostrophe and space' => [ "L'auteur du texte", "L'auteur_du_texte" ],
		];
	}

}
