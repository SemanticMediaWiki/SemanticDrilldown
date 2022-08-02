<?php

namespace SD\Parameters;

use ParserOutput;
use SD\Compat;

abstract class Parameter implements IParameter {

	/** Which page property name to use; must be set in every subclass */
	private const PAGE_PROPERTY_NAME = null;

	abstract protected function propertyValue(): ?string;

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
