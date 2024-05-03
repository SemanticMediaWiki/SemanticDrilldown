<?php

namespace SD\Parameters;

use Generator;
use IteratorAggregate;

class DisplayParameters implements IteratorAggregate {

	private const SEP = ';';
	private const CAPTION_EQ = 'caption=';
	private const FORMAT_EQ = 'format=';

	/**
	 * Array of strings of the form "x=y"
	 */
	private array $displayParameters = [];

	private ?string $caption = null;

	private ?string $format = null;

	/**
	 * @param string $displayParameters String of the form "x1=y1;...;xn=yn"
	 */
	public function __construct( string $displayParameters = '' ) {
		$displayParameters = array_map( 'trim', explode( self::SEP, $displayParameters ) );

		foreach ( $displayParameters as $dp ) {
			// make the format parameter available separately but keep it in place
			if ( strpos( $dp, self::FORMAT_EQ ) === 0 ) {
				$this->format = substr( $dp, strlen( self::FORMAT_EQ ) );
			}

			// filter out the caption parameter and store it separately
			if ( strpos( $dp, self::CAPTION_EQ ) === 0 ) {
				$this->caption = substr( $dp, strlen( self::CAPTION_EQ ) );
			} else {
				$this->displayParameters[] = $dp;
			}
		}
	}

	/**
	 * The caption passed as display parameter "caption=Foo"
	 */
	public function caption(): ?string {
		return $this->caption;
	}

	/**
	 * The result format to be used passed as display parameter "format=foo";
	 * if none has been passed, return the SMW default 'table'
	 */
	public function format(): string {
		return $this->format ?? 'table';
	}

	public function getIterator(): Generator {
		yield from $this->displayParameters;
	}

	public function __toString() {
		$additionalParameters = [];
		if ( $this->caption ) {
			$additionalParameters[] = self::CAPTION_EQ . $this->caption;
		}

		return implode( self::SEP, array_merge( $additionalParameters, $this->displayParameters ) );
	}

}
