<?php

namespace SD\Parameters;

use Generator;
use IteratorAggregate;

class Filters extends Parameter implements IteratorAggregate {

	public const PAGE_PROPERTY_NAME = 'SDFilters';

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

	public static function fromPropertyValue( ?string $value ): self {
		$result = new self;

		$filters = [];
		if ( !empty( $value ) ) {
			$filters = unserialize( $value );
		}

		$result->filters = $filters;
		return $result;
	}

	public function getIterator(): Generator {
		foreach ( $this->filters as $name => $settings ) {
			$value = fn( $key ) => array_key_exists( $key, $settings ) ? $settings[ $key ] : null;
			yield new Filter( $name,
				$value( 'property' ),
				$value( 'category' ),
				$value( 'requires' ),
				$value( 'int' )
			);
		}
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
					// TODO: only the last value is kept here
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
