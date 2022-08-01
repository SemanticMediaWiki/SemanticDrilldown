<?php

namespace SD;

use ParserOutput;

class Compat {
	public static function setPageProperty( ParserOutput $parserOutput, string $name, $value ) {
		if ( method_exists( $parserOutput, 'setPageProperty' ) ) {
			// MW 1.38
			$parserOutput->setPageProperty( $name, $value );
		} else {
			$parserOutput->setProperty( $name, $value );
		}
	}
}
