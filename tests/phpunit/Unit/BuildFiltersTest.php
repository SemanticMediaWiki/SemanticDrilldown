<?php

namespace SD\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SD\BuildFilters;
use SD\Filter;
use SD\Parameters\Filters;

/**
 * @covers \SD\BuildFilters
 */
class BuildFiltersTest extends TestCase {

	// ---------------------------------------------------------------------------
	// Helpers
	// ---------------------------------------------------------------------------

	/** Returns a spy closure that records all calls and returns a Filter mock per call. */
	private function makeFilterSpy( array &$calls ): \Closure {
		// PHPUnit mocks need $this, capture each separately so the closure stays static-compatible
		$test = $this;
		return static function () use ( &$calls, $test ): Filter {
			$calls[] = func_get_args();
			return $test->createMock( Filter::class );
		};
	}

	/** Returns a no-schema closure (simulates extension not installed or category without schema). */
	private function makeNoSchema(): \Closure {
		return static fn ( $category ) => null;
	}

	/** Builds a minimal anonymous PSSchema-like object with defined templates/fields. */
	private function makePsSchema( array $templates ): object {
		return new class( $templates ) {
			public function __construct( private array $templates ) {
			}

			public function isPSDefined(): bool {
				return true;
			}

			public function getTemplates(): array {
				return $this->templates;
			}
		};
	}

	/** Builds a template-like object from an array of field-like objects. */
	private function makeTemplate( array $fields ): object {
		return new class( $fields ) {
			public function __construct( private array $fields ) {
			}

			public function getFields(): array {
				return $this->fields;
			}
		};
	}

	/**
	 * Builds a field-like object.
	 *
	 * @param string $name Field name (fallback for filter name)
	 * @param array|null $filterArray Result of getObject('semanticdrilldown_Filter')
	 * @param array $propArray Result of getObject('semanticmediawiki_Property')
	 */
	private function makeField( string $name, ?array $filterArray, array $propArray = [] ): object {
		return new class( $name, $filterArray, $propArray ) {
			public function __construct(
				private string $name,
				private ?array $filterArray,
				private array $propArray
			) {
			}

			public function getName(): string {
				return $this->name;
			}

			public function getObject( string $key ): ?array {
				return match ( $key ) {
					'semanticdrilldown_Filter' => $this->filterArray,
					'semanticmediawiki_Property' => $this->propArray,
					default => null,
				};
			}
		};
	}

	// ---------------------------------------------------------------------------
	// Tests – no filters / no schema
	// ---------------------------------------------------------------------------

	public function testReturnsEmptyArrayWithNullFiltersAndNoSchema(): void {
		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), $this->makeNoSchema() );

		$result = $bf( 'TestCategory', null );

		$this->assertSame( [], $result );
		$this->assertSame( [], $calls );
	}

	public function testReturnsEmptyArrayWithEmptyFiltersAndNoSchema(): void {
		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), $this->makeNoSchema() );

		$result = $bf( 'TestCategory', new Filters() );

		$this->assertSame( [], $result );
		$this->assertSame( [], $calls );
	}

	// ---------------------------------------------------------------------------
	// Tests – parser-function driven filters (#drilldowninfo)
	// ---------------------------------------------------------------------------

	public function testBuildsOneFilterFromFilterParameter(): void {
		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), $this->makeNoSchema() );

		$filters = new Filters( 'MyFilter(property=MyProp)' );
		$result = $bf( 'TestCategory', $filters );

		$this->assertCount( 1, $result );
		$this->assertCount( 1, $calls );
		$this->assertSame( 'MyFilter', $calls[0][0] );
		$this->assertSame( 'MyProp', $calls[0][1] );
	}

	public function testBuildsMultipleFiltersFromFilterParameters(): void {
		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), $this->makeNoSchema() );

		$filters = new Filters( 'FilterA(property=PropA), FilterB(property=PropB)' );
		$result = $bf( 'TestCategory', $filters );

		$this->assertCount( 2, $result );
		$this->assertCount( 2, $calls );
		$this->assertSame( 'FilterA', $calls[0][0] );
		$this->assertSame( 'FilterB', $calls[1][0] );
	}

	public function testFilterParameterPassesCategoryAndRequiresAndInt(): void {
		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), $this->makeNoSchema() );

		$filters = new Filters( 'F(property=P, category=C, requires=R, int=1)' );
		$bf( 'TestCategory', $filters );

		// $newFilter($name, $property, $category, $requiredFilters, $int)
		$this->assertSame( 'F', $calls[0][0] );
		$this->assertSame( 'P', $calls[0][1] );
		$this->assertSame( 'C', $calls[0][2] );
		$this->assertSame( 'R', $calls[0][3] );
		$this->assertSame( '1', $calls[0][4] );
	}

	// ---------------------------------------------------------------------------
	// Tests – PageSchema-driven filters
	// ---------------------------------------------------------------------------

	public function testReturnsEmptyArrayWhenSchemaIsNull(): void {
		$calls = [];
		$bf = new BuildFilters(
			$this->makeFilterSpy( $calls ),
			static fn ( $cat ) => null
		);

		$result = $bf( 'TestCategory', null );

		$this->assertSame( [], $result );
		$this->assertSame( [], $calls );
	}

	public function testReturnsEmptyArrayWhenSchemaIsNotPSDefined(): void {
		$schema = new class {
			public function isPSDefined(): bool {
				return false;
			}
		};
		$calls = [];
		$bf = new BuildFilters(
			$this->makeFilterSpy( $calls ),
			static fn ( $cat ) => $schema
		);

		$result = $bf( 'TestCategory', null );

		$this->assertSame( [], $result );
		$this->assertSame( [], $calls );
	}

	public function testSkipsFieldWithoutDrilldownFilterObject(): void {
		$field = $this->makeField( 'NoFilterField', null, [ 'name' => 'Prop', 'Type' => 'Text' ] );
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );

		$result = $bf( 'TestCategory', null );

		$this->assertSame( [], $result );
		$this->assertSame( [], $calls );
	}

	public function testPageSchemaFilterUsesFieldNameWhenFilterArrayHasNoName(): void {
		$field = $this->makeField(
			'FieldName',
			// filter_array without 'name' key
			[],
			[ 'name' => 'PropName', 'Type' => 'Text' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		$this->assertSame( 'FieldName', $calls[0][0] );
	}

	public function testPageSchemaFilterUsesExplicitFilterName(): void {
		$field = $this->makeField(
			'FieldName',
			[ 'name' => 'ExplicitFilterName' ],
			[ 'name' => 'Prop', 'Type' => 'Text' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		$this->assertSame( 'ExplicitFilterName', $calls[0][0] );
	}

	public function testPageSchemaFilterUsesPropArrayNameForPropertyWhenNotEmpty(): void {
		$field = $this->makeField(
			'FieldName',
			[],
			[ 'name' => 'ExplicitPropName', 'Type' => 'Text' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		// $newFilter($name, $property, $category, $requiredFilters, $int, $propertyType, ...)
		$this->assertSame( 'ExplicitPropName', $calls[0][1] );
	}

	public function testPageSchemaFilterFallsBackToFilterNameForPropertyWhenPropNameIsEmpty(): void {
		$field = $this->makeField(
			'FieldName',
			// empty property name → falls back to filter name
			[],
			[ 'name' => '' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		$this->assertSame( 'FieldName', $calls[0][1] );
	}

	public function testPageSchemaFilterPassesPropertyTypeInCorrectPosition(): void {
		$field = $this->makeField(
			'F',
			[],
			[ 'name' => 'Prop', 'Type' => 'Page' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		// $newFilter($name, $property, $category, $requiredFilters, $int, $propertyType, ...)
		// category (pos 2) should be null, propertyType (pos 5) should be 'page'
		$this->assertNull( $calls[0][2], 'category should be null' );
		$this->assertNull( $calls[0][3], 'requiredFilters should be null' );
		$this->assertNull( $calls[0][4], 'int should be null' );
		$this->assertSame( 'page', $calls[0][5], 'propertyType should be lowercased Type' );
	}

	public function testPageSchemaFilterWithValuesFromCategory(): void {
		$field = $this->makeField(
			'F',
			[ 'ValuesFromCategory' => 'Animals' ],
			[ 'name' => 'Prop' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		// $newFilter($name, $property, $category, ...)
		$this->assertSame( 'Animals', $calls[0][2] );
	}

	public function testPageSchemaFilterWithTimePeriod(): void {
		$field = $this->makeField(
			'F',
			[ 'TimePeriod' => 'year' ],
			[ 'name' => 'Prop' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		// $newFilter($name, $property, $category, $requiredFilters, $int, $propertyType, $timePeriod, $allowedValues)
		$this->assertSame( 'year', $calls[0][6] );
		$this->assertSame( [], $calls[0][7] );
	}

	public function testPageSchemaFilterWithBooleanPropertyTypeUsesHardcodedAllowedValues(): void {
		$field = $this->makeField(
			'F',
			// no ValuesFromCategory, no TimePeriod, no Values
			[],
			[ 'name' => 'Prop', 'Type' => 'Boolean' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		$this->assertSame( [ '0', '1' ], $calls[0][7] );
	}

	public function testPageSchemaFilterWithExplicitValues(): void {
		$field = $this->makeField(
			'F',
			[ 'Values' => [ 'Alpha', 'Beta' ] ],
			[ 'name' => 'Prop' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		$this->assertSame( [ 'Alpha', 'Beta' ], $calls[0][7] );
	}

	public function testPageSchemaFilterWithNoValuesFallsBackToEmptyAllowedValues(): void {
		$field = $this->makeField(
			'F',
			// no ValuesFromCategory, no TimePeriod, no Values (and not boolean)
			[],
			[ 'name' => 'Prop' ]
		);
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );
		$bf( 'TestCategory', null );

		$this->assertSame( [], $calls[0][7] );
	}

	// ---------------------------------------------------------------------------
	// Tests – merging both sources
	// ---------------------------------------------------------------------------

	public function testMergesParserFiltersWithSchemaFilters(): void {
		$field = $this->makeField( 'SchemaFilter', [], [ 'name' => 'SchemaProp' ] );
		$schema = $this->makePsSchema( [ $this->makeTemplate( [ $field ] ) ] );

		$calls = [];
		$bf = new BuildFilters( $this->makeFilterSpy( $calls ), static fn () => $schema );

		$filters = new Filters( 'ParserFilter(property=ParserProp)' );
		$result = $bf( 'TestCategory', $filters );

		$this->assertCount( 2, $result );
		$this->assertCount( 2, $calls );
		// parser-function filters come first
		$this->assertSame( 'ParserFilter', $calls[0][0] );
		$this->assertSame( 'SchemaFilter', $calls[1][0] );
	}

	public function testPassesCategoryToGetPageSchemaCallback(): void {
		$receivedCategory = null;
		$getSchema = static function ( $cat ) use ( &$receivedCategory ) {
			$receivedCategory = $cat;
			return null;
		};

		$bf = new BuildFilters( static fn () => null, $getSchema );
		$bf( 'ExpectedCategory', null );

		$this->assertSame( 'ExpectedCategory', $receivedCategory );
	}
}
