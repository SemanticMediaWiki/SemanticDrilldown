<?php

declare( strict_types=1 );

namespace SD\Tests\Unit;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageProps;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use SD\Services;

/**
 * @covers \SD\Services
 */
class ServicesTest extends TestCase {

	// ---------------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------------

	/** Returns a Services subclass whose mwServices() returns a stub container. */
	private function makeTestableServices( MediaWikiServices $mwServices ): Services {
		return new class( $mwServices ) extends Services {
			public function __construct( private readonly MediaWikiServices $stub ) {
			}

			protected function mwServices(): MediaWikiServices {
				return $this->stub;
			}
		};
	}

	/** Resets the private static $instance so singleton tests start clean. */
	private function resetSingleton(): void {
		$prop = new ReflectionProperty( Services::class, 'instance' );
		$prop->setAccessible( true );
		$prop->setValue( null, null );
	}

	protected function setUp(): void {
		$this->resetSingleton();
	}

	protected function tearDown(): void {
		$this->resetSingleton();
	}

	public function testInstanceReturnsSameObjectOnSubsequentCalls(): void {
		$method = new ReflectionMethod( Services::class, 'instance' );
		$method->setAccessible( true );

		$first = $method->invoke( null );
		$second = $method->invoke( null );

		$this->assertSame( $first, $second,
			'Services::instance() must return the cached singleton, not a new object each call' );
	}

	public function testMwServicesOverrideAllowsStubbing(): void {
		$pageProps = $this->createMock( PageProps::class );

		$mwServices = $this->createMock( MediaWikiServices::class );
		$mwServices->method( 'getPageProps' )->willReturn( $pageProps );

		$services = $this->makeTestableServices( $mwServices );

		$method = new ReflectionMethod( $services, 'getLoadParameters' );
		$method->setAccessible( true );
		$loadParameters = $method->invoke( $services );

		$this->assertNotNull( $loadParameters );
	}

}
