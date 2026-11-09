<?php

namespace SD\Parameters;

/**
 * The number of results of a category that are shown on one page of Special:BrowseData,
 * instead of the global $sdgNumResultsPerPage.
 */
class ResultsPerPage extends SimpleParameter {
	public const PAGE_PROPERTY_NAME = 'SDResultsPerPage';

	/**
	 * @param string|null $value as written in the parameter
	 * @return int|null the number if it is a positive integer, null otherwise (no value, text, zero,
	 *   negative numbers or numbers that are too large)
	 */
	public static function toNumber( ?string $value ): ?int {
		if ( $value === null ) {
			return null;
		}
		$number = filter_var( trim( $value ), FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1 ] ] );
		return $number === false ? null : $number;
	}
}
