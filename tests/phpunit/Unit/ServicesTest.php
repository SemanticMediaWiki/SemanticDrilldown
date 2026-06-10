<?php

declare( strict_types=1 );

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use SD\Services;

/**
 * @covers \SD\Services
 */
class ServicesTest extends TestCase {

	protected function setUp(): void {
		// Reset singleton state before each test
		$prop = new ReflectionProperty( Services::class, 'instance' );
		$prop->setAccessible( true );
		$prop->setValue( null, null );
	}

	protected function tearDown(): void {
		// Reset singleton state after each test to avoid leaking state
		$prop = new ReflectionProperty( Services::class, 'instance' );
		$prop->setAccessible( true );
		$prop->setValue( null, null );
	}

	public function testInstanceReturnsSameObjectOnSubsequentCalls(): void {
		$method = new ReflectionMethod( Services::class, 'instance' );
		$method->setAccessible( true );

		$first = $method->invoke( null );
		$second = $method->invoke( null );

		$this->assertSame( $first, $second,
			'Services::instance() must return the cached singleton, not a new object each call' );
	}

}
