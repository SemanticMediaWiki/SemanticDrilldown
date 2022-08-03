<?php

namespace SD\Parameters;

use ParserOutput;
use SD\Compat;

/**
 * Allow classes a simple implementation of the IParameter interface by implementing
 * functions to
 * - construct the string to be stored as page property (propertyValue) and
 * - create an object from this string again (fromPropertyValue)
 */
abstract class Parameter implements IParameter {

	/** Which page property name to use; must be set in every subclass */
	private const PAGE_PROPERTY_NAME = null;

	/**
	 * @return string|null The string to be stored as a page property of the category
	 */
	abstract protected function propertyValue(): ?string;

	/**
	 * How to restore an object from the page property string
	 * @param string|null $value
	 * @return static
	 */
	abstract protected static function fromPropertyValue( ?string $value ): self;

	public function setPageProperty( ParserOutput $parserOutput ) {
		$propertyValue = $this->propertyValue();
		if ( $propertyValue !== null ) {
			Compat::setPageProperty( $parserOutput, static::PAGE_PROPERTY_NAME, $propertyValue );
		}
	}

	public static function forCategory( $category ): self {
		$value = ReadCategoryProperty::for( $category, static::PAGE_PROPERTY_NAME );
		return static::fromPropertyValue( $value );
	}

}
