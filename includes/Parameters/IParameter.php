<?php

namespace SD\Parameters;

use ParserOutput;

/**
 * Interface for handling the #drilldowninfo parameters providing methods
 * - to store the parameter values as page props (used from DrilldownInfo)
 * - to load them again when processing a drilldown page (used from SpecialBrowseData)
 */
interface IParameter {
	public function setPageProperty( ParserOutput $parserOutput );

	public static function forCategory( $category ): self;
}
