<?php

namespace SD;

use Generator;
use IteratorAggregate;

class PossibleFilterValues implements IteratorAggregate {

	/** @var PossibleFilterValue[] */
	public array $values;
	private ?string $minDate;
	private ?string $maxDate;

	/**
	 * @param PossibleFilterValue[] $values
	 * @param ?string $minDate
	 * @param ?string $maxDate
	 */
	public function __construct(
		array $values, ?string $minDate = null, ?string $maxDate = null
	) {
		$this->values = $values;
		$this->minDate = $minDate;
		$this->maxDate = $maxDate;
	}

	/**
	 * @return Generator|PossibleFilterValue[]
	 */
	public function getIterator(): Generator {
		yield from $this->values;
	}

	public function count(): int {
		return count( $this->values );
	}

	/**
	 * @return ?string[]
	 */
	public function dateRange(): array {
		return [ $this->minDate, $this->maxDate ];
	}

	/**
	 * @return int[]
	 */
	public function countRange(): array {
		return [
			min( array_map( fn( $v ) => $v->count(), $this->values ) ),
			max( array_map( fn( $v ) => $v->count(), $this->values ) )
		];
	}

	/**
	 * Creates a new instance of PossibleFilterValues with $additionalPossibleValues
	 * added.
	 *
	 * @param PossibleFilterValue[] $additionalPossibleValues
	 * @return PossibleFilterValues
	 */
	public function merge( array $additionalPossibleValues ) {
		$merged = array_merge( $this->values, $additionalPossibleValues );
		return new self( $merged, $this->minDate, $this->maxDate );
	}

}
