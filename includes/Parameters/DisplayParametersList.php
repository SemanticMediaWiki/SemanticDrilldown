<?php

namespace SD\Parameters;

use Generator;
use IteratorAggregate;

class DisplayParametersList extends Parameter implements IteratorAggregate {

	public const PAGE_PROPERTY_NAME = 'SDDisplayParams';

	/** @readonly */
	private array $list = [];

	/**
	 * Add a raw 'display parameters' parameter of the #drilldowninfo parser function
	 * (i.e. of the form 'x1=y1;...; xn=yn') to the list
	 * @param string $displayParameters
	 * @return void
	 */
	public function add( string $displayParameters ) {
		$this->list[] = new DisplayParameters( $displayParameters );
	}

	protected function propertyValue(): ?string {
		return empty( $this->list ) ? null
			: implode( '|',  array_map( fn( $dps ) => "$dps", $this->list ) );
	}

	public static function fromPropertyValue( ?string $value ): self {
		$result = new self;
		if ( !empty( $value ) ) {
			foreach ( explode( '|', $value ) as $displayParameters ) {
				$result->add( $displayParameters );
			}
		}

		return $result;
	}

	public function getIterator(): Generator {
		yield from $this->list;
	}

	public function strings(): Generator {
		foreach ( $this->list as $displayParameters ) {
			yield "$displayParameters";
		}
	}

	public function count(): int {
		return count( $this->list );
	}

}
