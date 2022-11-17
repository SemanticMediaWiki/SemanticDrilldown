<?php

namespace SD\Specials\BrowseData;

class NumberUtils {

	/**
	 * Copied from Miga, also written by Yaron Koren
	 * (https://github.com/yaronkoren/miga/blob/master/NumberUtils.js)
	 * - though that one is in Javascript.
	 */
	public static function generateFilterValuesFromNumbers( array $numberArray ) {
		global $sdgNumRangesForNumberFilters;

		$numNumbers = count( $numberArray );

		// First, find the number of unique values - if it's the value
		// of $sdgNumRangesForNumberFilters, or fewer, just display
		// each one as its own bucket.
		$numUniqueValues = 0;
		$uniqueValues = [];
		foreach ( $numberArray as $curNumber ) {
			if ( !array_key_exists( $curNumber, $uniqueValues ) ) {
				$uniqueValues[$curNumber] = 1;
				$numUniqueValues++;
				if ( $numUniqueValues > $sdgNumRangesForNumberFilters ) {
					continue;
				}
			} else {
				// We do this now to save time on the next step,
				// if we're creating individual filter values.
				$uniqueValues[$curNumber]++;
			}
		}

		if ( $numUniqueValues <= $sdgNumRangesForNumberFilters ) {
			return self::generateIndividualFilterValuesFromNumbers( $uniqueValues );
		}

		$propertyValues = [];
		$separatorValue = $numberArray[0];

		// Make sure there are at least, on average, five numbers per
		// bucket.
		// @HACK - add 3 to the number so that we don't end up with
		// just one bucket ( 7 + 3 / 5 = 2).
		$numBuckets = min( $sdgNumRangesForNumberFilters, floor( ( $numNumbers + 3 ) / 5 ) );
		$bucketSeparators = [];
		$bucketSeparators[] = $numberArray[0];
		for ( $i = 1; $i < $numBuckets; $i++ ) {
			$separatorIndex = floor( $numNumbers * $i / $numBuckets ) - 1;
			$previousSeparatorValue = $separatorValue;
			$separatorValue = $numberArray[$separatorIndex];
			if ( $separatorValue == $previousSeparatorValue ) {
				continue;
			}
			$bucketSeparators[] = $separatorValue;
		}
		$lastValue = ceil( $numberArray[count( $numberArray ) - 1] );
		if ( $lastValue != $separatorValue ) {
			$bucketSeparators[] = $lastValue;
		}

		// Get the closest "nice" (few significant digits) number for
		// each of the bucket separators, with the number of significant digits
		// required based on their proximity to their neighbors.
		// The first and last separators need special handling.
		$bucketSeparators[0] = self::getNearestNiceNumber( $bucketSeparators[0], null,
			$bucketSeparators[1] );
		for ( $i = 1; $i < count( $bucketSeparators ) - 1; $i++ ) {
			$bucketSeparators[$i] = self::getNearestNiceNumber( $bucketSeparators[$i], $bucketSeparators[$i - 1], $bucketSeparators[$i + 1] );
		}
		$bucketSeparators[count( $bucketSeparators ) - 1] = self::getNearestNiceNumber( $bucketSeparators[count( $bucketSeparators ) - 1], $bucketSeparators[count( $bucketSeparators ) - 2], null );

		$oldSeparatorValue = $bucketSeparators[0];
		for ( $i = 1; $i < count( $bucketSeparators ); $i++ ) {
			$separatorValue = $bucketSeparators[$i];
			$propertyValues[] = [
				'lowerNumber' => $oldSeparatorValue,
				'higherNumber' => $separatorValue,
				'numValues' => 0,
			];
			$oldSeparatorValue = $separatorValue;
		}

		$curSeparator = 0;
		for ( $i = 0; $i < count( $numberArray ); $i++ ) {
			if ( $curSeparator < count( $propertyValues ) - 1 ) {
				$curNumber = $numberArray[$i];
				while ( ( $curSeparator < count( $bucketSeparators ) - 2 ) && ( $curNumber >= $bucketSeparators[$curSeparator + 1] ) ) {
					$curSeparator++;
				}
			}
			$propertyValues[$curSeparator]['numValues']++;
		}

		return $propertyValues;
	}

	/**
	 * Copied from Miga, also written by Yaron Koren
	 * (https://github.com/yaronkoren/miga/blob/master/NumberUtils.js)
	 * - though that one is in Javascript.
	 */
	private static function getNearestNiceNumber( $num, $previousNum, $nextNum ) {
		if ( $previousNum == null ) {
			$smallestDifference = $nextNum - $num;
		} elseif ( $nextNum == null ) {
			$smallestDifference = $num - $previousNum;
		} else {
			$smallestDifference = min( $num - $previousNum, $nextNum - $num );
		}

		$base10LogOfDifference = log10( $smallestDifference );
		$significantFigureOfDifference = floor( $base10LogOfDifference );

		$powerOf10InCorrectPlace = pow( 10, $significantFigureOfDifference );
		$significantDigitsOnly = round( $num / $powerOf10InCorrectPlace );
		$niceNumber = $significantDigitsOnly * $powerOf10InCorrectPlace;

		// Special handling if it's the first or last number in the
		// series - we have to make sure that the "nice" equivalent is
		// on the right "side" of the number.

		// That's especially true for the last number -
		// it has to be greater, not just equal to, because of the way
		// number filtering works.
		// ...or does it??
		if ( $previousNum == null && $niceNumber > $num ) {
			$niceNumber -= $powerOf10InCorrectPlace;
		}
		if ( $nextNum == null && $niceNumber < $num ) {
			$niceNumber += $powerOf10InCorrectPlace;
		}

		return $niceNumber;
	}

	/**
	 * Copied from Miga, also written by Yaron Koren
	 * (https://github.com/yaronkoren/miga/blob/master/NumberUtils.js)
	 * - though that one is in Javascript.
	 */
	private static function generateIndividualFilterValuesFromNumbers( $uniqueValues ) {
		$propertyValues = [];
		foreach ( $uniqueValues as $uniqueValue => $numInstances ) {
			$curBucket = [
				'lowerNumber' => $uniqueValue,
				'higherNumber' => null,
				'numValues' => $numInstances
			];
			$propertyValues[] = $curBucket;
		}
		return $propertyValues;
	}

}
