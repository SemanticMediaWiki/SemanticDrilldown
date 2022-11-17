<?php

namespace SD\Specials\BrowseData;

use OutputPage;

class GetSemanticResults {

	public function __invoke( $displayParametersList, OutputPage $out, $res, $num = null ): ?array {
		$results = [];
		$semanticResultPrinter = new SemanticResultPrinter( $res, $num );
		foreach ( $displayParametersList as $displayParameters ) {
			$text = $semanticResultPrinter->getText( iterator_to_array( $displayParameters ) );
			$results[] = [
				'heading' => $displayParameters->caption(),
				'body' => $out->parseAsInterface( $text ),
			];
			// Do we additionally need to add MetaData to $out here?
		}

		return $results;
	}

}
