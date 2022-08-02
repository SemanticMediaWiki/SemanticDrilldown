<?php

namespace SD\Parameters;

class SimpleParameter extends Parameter {

	/** @readonly */
	public ?string $value;

	public function __construct( ?string $value = null ) {
		$this->value = $value;
	}

	protected function propertyValue(): ?string {
		return $this->value;
	}

	protected static function fromPropertyValue( ?string $value ): SimpleParameter {
		return new self( $value );
	}

	public function __toString() {
		return $this->value ?? '';
	}

}
