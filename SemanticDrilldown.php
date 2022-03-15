<?php
/**
 * Semantic Drilldown extension
 *
 * Defines a drill-down interface for data stored with the Semantic MediaWiki
 * extension, via the page Special:BrowseData.
 *
 * @file
 * @defgroup SD Semantic Drilldown
 * @ingroup SD
 * @author Yaron Koren
 */

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'SemanticDrilldown' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['SemanticDrilldown'] = __DIR__ . '/i18n';
	wfWarn(
		'Deprecated PHP entry point used for SemanticDrilldown extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
		);
	return;
} else {
	die( 'This version of the SemanticDrilldown extension requires MediaWiki 1.34+' );
}
