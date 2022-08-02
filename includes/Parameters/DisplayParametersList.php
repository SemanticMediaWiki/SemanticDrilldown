<?php

namespace SD\Parameters;

use ArrayIterator;
use IteratorAggregate;

class DisplayParametersList extends Parameter implements IteratorAggregate {

	protected const PAGE_PROPERTY_NAME = 'SDDisplayParams';

	private array $list = [];

	/**
	 * Add a raw 'display parameters' parameter of the #drilldowninfo parser function
	 * (i.e. of the form 'x1=y1;...; xn=yn') to the list
	 * @param string $displayParameters
	 * @return void
	 */
	public function add( string $displayParameters ) {
		$this->list[] = array_map( 'trim', explode( ';', $displayParameters ) );
	}

	protected function propertyValue(): ?string {
		return empty( $this->list )
			? null
			: implode( '|',
				array_map( fn( $xs ) => implode( ';', $xs ), $this->list ) );
	}

	protected static function fromPropertyValue( ?string $value ): self {
		$result = new self;
		if ( !empty( $value ) ) {
			foreach ( explode( '|', $value ) as $displayParameters ) {
				$result->add( $displayParameters );
			}
		}
		return $result;
	}

	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->list );
	}

	public function strings() {
		foreach ( $this->list as $displayParameters ) {
			yield implode( ';', $displayParameters );
		}
	}
}
