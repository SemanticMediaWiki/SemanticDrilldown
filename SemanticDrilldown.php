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

// Ensure that the script cannot be executed outside of MediaWiki
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This is an extension to MediaWiki and cannot be run standalone.' );
}

// Define extension's version
define( 'SD_VERSION', '2.1' );

// Display extension's information on "Special:Version"
$wgExtensionCredits['semantic'][] = [
	'path'           => __FILE__,
	'name'           => 'Semantic Drilldown',
	'version'        => SD_VERSION,
	'author'         => [ 'Yaron Koren', '...' ],
	'url'            => 'https://www.mediawiki.org/wiki/Extension:Semantic_Drilldown',
	'descriptionmsg' => 'semanticdrilldown-desc',
	'license-name'   => 'GPL-2.0-or-later'
];

$sdgIP = __DIR__;

$wgMessagesDirs['SemanticDrilldown'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['SemanticDrilldownAlias'] = $sdgIP . '/languages/SD_Aliases.php';
$wgExtensionMessagesFiles['SemanticDrilldownMagic'] = $sdgIP . '/languages/SemanticDrilldown.i18n.magic.php';

$wgAutoloadClasses['SDBrowseData'] = $sdgIP . '/specials/SD_BrowseData.php';
$wgAutoloadClasses['SDBrowseDataPage'] = $sdgIP . '/specials/SD_BrowseData.php';

$wgAutoloadClasses['SDUtils'] = $sdgIP . '/includes/SD_Utils.php';
$wgAutoloadClasses['SDFilter'] = $sdgIP . '/includes/SD_Filter.php';
$wgAutoloadClasses['SDFilterValue'] = $sdgIP . '/includes/SD_FilterValue.php';
$wgAutoloadClasses['SDAppliedFilter'] = $sdgIP . '/includes/SD_AppliedFilter.php';
$wgAutoloadClasses['SDPageSchemas'] = $sdgIP . '/includes/SD_PageSchemas.php';
$wgAutoloadClasses['SDParserFunctions'] = $sdgIP . '/includes/SD_ParserFunctions.php';
$wgAutoloadClasses['TemporaryTableManager'] = "$sdgIP/includes/TemporaryTableManager.php";

// register all special pages and other classes
$wgSpecialPages['BrowseData'] = 'SDBrowseData';

$wgHooks['AdminLinks'][] = 'SDUtils::addToAdminLinks';
$wgHooks['MagicWordwgVariableIDs'][] = 'SDUtils::addMagicWordVariableIDs';
$wgHooks['MakeGlobalVariablesScript'][] = 'SDUtils::setGlobalJSVariables';
$wgHooks['ParserAfterTidy'][] = 'SDUtils::handleShowAndHide';
$wgHooks['PageSchemasRegisterHandlers'][] = 'SDPageSchemas::registerClass';
$wgHooks['ParserFirstCallInit'][] = 'SDParserFunctions::registerFunctions';
$wgHooks['UnitTestsList'][] = 'SDUtils::onUnitTestsList';

# ##
# This is the path to your installation of Semantic Drilldown as
# seen from the web. Change it if required ($wgScriptPath is the
# path to the base directory of your wiki). No final slash.
# # TODO: fix hardcoded path
$sdgScriptPath = $wgScriptPath . '/extensions/SemanticDrilldown';
# #

# ##
# # Variables for display
# ##
// Set to true to have Special:BrowseData show only categories that have
// __SHOWINDRILLDOWN__ set.
$sdgHideCategoriesByDefault = false;
$sdgNumResultsPerPage = 250;
// set these to a positive value to trigger the "tag cloud" display
$sdgFiltersSmallestFontSize = -1;
$sdgFiltersLargestFontSize = -1;
// print categories list as tabs
$sdgShowCategoriesAsTabs = false;
// other display settings
$sdgMinValuesForComboBox = 40;
$sdgNumRangesForNumberFilters = 6;

$sdgResourceTemplate = [
	'localBasePath' => $sdgIP,
	'remoteExtPath' => 'SemanticDrilldown'
];

$wgResourceModules += [
	'ext.semanticdrilldown.main' => $sdgResourceTemplate + [
		'styles' => [
			'skins/SD_main.css',
			'skins/SD_jquery_ui_overrides.css',
		],
		'scripts' => [
			'libs/SemanticDrilldown.js',
		],
		'dependencies' => version_compare( $wgVersion, '1.34', '>=' )
			? [
				'jquery.ui',
			]
			: [
				'jquery.ui.autocomplete',
				'jquery.ui.button',
			],
	],
	'ext.semanticdrilldown.info' => $sdgResourceTemplate + [
		'styles' => [
			'skins/SD_info.css',
		],
	],
];
