<?php

namespace SD\Parameters;

use Generator;
use IteratorAggregate;

class DisplayParameters implements IteratorAggregate {

	private const SEP = ';';

	/**
	 * Array of strings of the form "x=y"
	 * @readonly
	 */
	private array $displayParameters = [];

	/**
	 * The caption passed as display parameter "caption=Foo"
	 * @readonly
	 */
	public ?string $caption = null;

	/**
	 * @param string $displayParameters String of the form "x1=y1;...;xn=yn"
	 */
	public function __construct( string $displayParameters ) {
		$displayParameters = array_map( 'trim', explode( self::SEP, $displayParameters ) );

		$caption = 'caption=';
		foreach ( $displayParameters as $dp ) {
			// filter out the caption parameter and store it separately
			if ( strpos( $dp, $caption ) === 0 ) {
				$this->caption = substr( $dp, strlen( $caption ) );
			} else {
				$this->displayParameters[] = $dp;
			}
		}
	}

	public function getIterator(): Generator {
		yield from $this->displayParameters;
	}

	public function __toString() {
		$displayParameters =
			$this->caption ? array_merge( [ "caption=$this->caption" ], $this->displayParameters )
				: $this->displayParameters;

		return implode( self::SEP, $displayParameters );
	}

}
