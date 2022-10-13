<?php

namespace SD\Specials\BrowseData;

use TemplateParser;

class ProcessTemplate {

	public function __invoke( $template, $vm ) {
		$templateParser = new TemplateParser( __DIR__ . "/templates" );

		$messages = [
			'sd_browsedata_subcategory',
			'sd_browsedata_resetfilters',
			'sd_browsedata_removesubcategoryfilter',
			'sd_browsedata_removefilter',
			'sd_browsedata_or',
			'sd_browsedata_docu',
			'sd_browsedata_filterbysubcategory',
			'sd_browsedata_choosecategory',
			'colon-separator',
			'sd_browsedata_addanothervalue',
			'sd_browsedata_other',
			'sd_browsedata_none',
			'sd_browsedata_filterbyvalue',
			'sd_browsedata_search',
			'sd_browsedata_daterangestart',
			'sd_browsedata_daterangeend',
			'searchresultshead',
			'sd_browsedata_novalues',
			'sd_displayparameters_with_unknown_format',
			'sd_displayparameters_with_unsupported_format'
		];
		foreach ( $messages as $message ) {
			$msg[ "msg_$message" ] = wfMessage( $message )->text();
		}

		$html = $templateParser->processTemplate( $template, $vm + $msg );

		// Remove leading whitespace from all lines as the parser will interpret lines containing
		// text starting with whitespace as preformatted text later;
		// see Parser::internalParseHalfParsed where $text is first unstripped (the previously
		// inserted marker is replaced with the corresponding HTML from the transcluded page) and
		// afterwards the BlockLevelPass::doBlockLevels is executed (<pre>s are inserted etc.)
		return preg_replace( '/^\s+/m', '', $html );
	}

}
