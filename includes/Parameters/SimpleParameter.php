<?php

namespace SD\Parameters;

/**
 * Parameter implementation for the most simple case where the value represented
 * is stored directly as a page property
 */
class SimpleParameter extends Parameter {

	/** @readonly */
	public ?string $value;

	public function __construct( ?string $value = null ) {
		$this->value = $value;
	}

	protected function propertyValue(): ?string {
		return $this->value;
	}

	public static function fromPropertyValue( ?string $value ): SimpleParameter {
		return new self( $value );
	}

	public function __toString() {
		return $this->value ?? '';
	}

}
