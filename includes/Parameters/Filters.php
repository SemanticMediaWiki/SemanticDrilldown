<?php

namespace SD\Parameters;

use ArrayIterator;
use IteratorAggregate;
use SD\Filter;

class Filters extends Parameter implements IteratorAggregate {

	protected const PAGE_PROPERTY_NAME = 'SDFilters';

	private array $filters;

	/**
	 * Create Filters from the filter parameter of the #drilldowninfo parser function
	 * @param string|null $filtersString
	 */
	public function __construct( ?string $filtersString = null ) {
		$this->filters = $filtersString !== null
			? $this->parseFilters( $filtersString )
			: [];
	}

	protected function propertyValue(): ?string {
		return $this->filters === []
			? null
			: serialize( $this->filters );
	}

	protected static function fromPropertyValue( ?string $value ): self {
		$result = new self;

		$filters = [];
		if ( !empty( $value ) ) {
			$filtersInfo = unserialize( $value );
			foreach ( $filtersInfo as $filterName => $filterValues ) {
				$curFilter = new Filter();
				$curFilter->setName( $filterName );
				foreach ( $filterValues as $key => $value ) {
					if ( $key == 'property' ) {
						$curFilter->setProperty( $value );
						$curFilter->loadPropertyTypeFromProperty();
					} elseif ( $key == 'category' ) {
						$curFilter->setCategory( $value );
					} elseif ( $key == 'requires' ) {
						$curFilter->addRequiredFilter( $value );
					} elseif ( $key == 'int' ) {
						$curFilter->setInt( $value );
					}
				}
				$filters[] = $curFilter;
			}
		}

		$result->filters = $filters;
		return $result;
	}

	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->filters );
	}

	public static function forCategory( $category ): Parameter {
		$result = parent::forCategory( $category );

		// TODO: probably this does not belong here!
		// Read from the Page Schemas schema for this category, if
		// it exists, and add any filters defined there.
		if ( class_exists( 'PSSchema' ) ) {
			$pageSchemaObj = new \PSSchema( $category );
			if ( $pageSchemaObj->isPSDefined() ) {
				$filters_ps = Filter::loadAllFromPageSchema( $pageSchemaObj );
				$result->filters = array_merge( $result->filters, $filters_ps );
			}
		}

		return $result;
	}

	private static function parseFilters( $filtersStr ) {
		$filters = [];
		preg_match_all( '/([^()]*)\(([^)]*)\)/', $filtersStr, $matches );
		foreach ( $matches[1] as $i => $filterName ) {
			$filterName = trim( $filterName, ", \t\n\r\0\x0B" );
			$filters[$filterName] = [];

			$filterSettingsStr = $matches[2][$i];
			$filterSettings = explode( ',', $filterSettingsStr );
			foreach ( $filterSettings as $filterSettingStr ) {
				$filterSetting = explode( '=', $filterSettingStr, 2 );
				if ( count( $filterSetting ) != 2 ) {
					continue;
				}
				$key = trim( $filterSetting[0] );
				if ( $key != 'property' && $key != 'category' && $key != 'requires' && $key != 'int' ) {
					return "<div class=\"error\">Error: unknown setting, \"$key\".</div>";
				}

				$value = trim( $filterSetting[1] );
				// 'requires' holds a list, the other two
				// hold individual values.
				if ( $key == 'requires' ) {
					$values = explode( ',', $value );
					foreach ( $values as $realValue ) {
						$filters[$filterName][$key] = trim( $realValue );
					}
				} else {
					$filters[$filterName][$key] = $value;
				}
			}
		}
		return $filters;
	}

}
