<?php

namespace SD;

use Closure;
use SD\Parameters\Filters;

class BuildFilters {

	private Closure $newFilter;
	private Closure $getPageSchema;

	public function __construct( Closure $newFilter, Closure $getPageSchema ) {
		$this->newFilter = $newFilter;
		$this->getPageSchema = $getPageSchema;
	}

	/**
	 * Builds a complete list of category filters consisting of filters defined by
	 * * the filter parameter of the #drilldowninfo parser function
	 * * the PageSchema of $category
	 *
	 * @param string $category
	 * @return Filter[]
	 */
	public function __invoke( $category, ?Filters $filterParameters ): array {
		return array_merge(
			$this->buildFilterList( $filterParameters ?? [] ),
			$this->buildPageSchemaFilters( $category ) );
	}

	private function buildFilterList( $filterParameters ) {
		$filters = [];
		foreach ( $filterParameters as $filterParameter ) {
			$filters[] = self::buildFilter( $filterParameter );
		}

		return $filters;
	}

	private function buildFilter( Parameters\Filter $parameter ): Filter {
		return ( $this->newFilter )(
			$parameter->name(),
			$parameter->property(),
			$parameter->category(),
			$parameter->requires(),
			$parameter->int()
		);
	}

	private function buildPageSchemaFilters( $category ) {
		// Read from the Page Schemas schema for this category, if
		// it exists, and add any filters defined there.
		$pageSchema = ( $this->getPageSchema )( $category );
		return $pageSchema !== null && $pageSchema->isPSDefined()
			? self::loadFiltersFromPageSchema( $pageSchema )
			: [];
	}

	private function loadFiltersFromPageSchema( $psSchemaObj ) {
		$result = [];
		$template_all = $psSchemaObj->getTemplates();
		foreach ( $template_all as $template ) {
			$field_all = $template->getFields();
			foreach ( $field_all as $fieldObj ) {
				$filter_array = $fieldObj->getObject( 'semanticdrilldown_Filter' );
				if ( $filter_array === null ) {
					continue;
				}

				$name = array_key_exists( 'name', $filter_array )
					? $filter_array['name']
					: $fieldObj->getName();

				$prop_array = $fieldObj->getObject( 'semanticmediawiki_Property' );
				$property = $prop_array['name'] != ''
					? $prop_array['name']
					: $name;

				$propertyType = array_key_exists( 'Type', $prop_array )
					// Thankfully, the property type names
					// assigned by SMW/Page Schemas, and the
					// internal ones used by SD, are the
					// same (for all the relevant types)
					// except for an uppercased first
					// letter.
					? strtolower( $prop_array['Type'] )
					: null;

				$category = null;
				$timePeriod = null;
				$allowedValues = null;
				if ( array_key_exists( 'ValuesFromCategory', $filter_array ) ) {
					$category = $filter_array['ValuesFromCategory'];
				} elseif ( array_key_exists( 'TimePeriod', $filter_array ) ) {
					$timePeriod = $filter_array['TimePeriod'];
					$allowedValues = [];
				} elseif ( $propertyType === 'boolean' ) {
					$allowedValues = [ '0', '1' ];
				} elseif ( array_key_exists( 'Values', $filter_array ) ) {
					$allowedValues = $filter_array['Values'];
				} else {
					$allowedValues = [];
				}

				$result[] = ( $this->newFilter )(
					$name,
					$property,
					$propertyType,
					$category,
					null,
					null,
					$timePeriod,
					$allowedValues
				);
			}
		}

		return $result;
	}

}
