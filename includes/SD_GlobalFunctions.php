<?php
/**
 * Global functions and constants for Semantic Drilldown.
 *
 * @author Yaron Koren
 */

define('SD_VERSION','0.1');

// constants for special properties
define('SD_SP_HAS_FILTER', 1);
define('SD_SP_COVERS_PROPERTY', 2);
define('SD_SP_HAS_VALUE', 3);
define('SD_SP_GETS_VALUES_FROM_CATEGORY', 4);
define('SD_SP_HAS_LABEL', 5);

$wgExtensionFunctions[] = 'sdgSetupExtension';

require_once($sdgIP . '/languages/SD_Language.php');

/**
 *  Do the actual intialization of the extension. This is just a delayed init that makes sure
 *  MediaWiki is set up properly before we add our stuff.
 */
function sdgSetupExtension() {
	global $sdgNamespace, $sdgIP, $wgExtensionCredits, $wgArticlePath, $wgScriptPath, $wgServer;

	sdfInitMessages();

	require_once($sdgIP . '/includes/SD_Filter.php');
	require_once($sdgIP . '/includes/SD_AppliedFilter.php');

	/**********************************************/
	/***** register specials                  *****/
	/**********************************************/

	require_once($sdgIP . '/specials/SD_ViewData.php');
	require_once($sdgIP . '/specials/SD_Filters.php');

	/**********************************************/
	/***** register hooks                     *****/
	/**********************************************/

	/**********************************************/
	/***** create globals for outside hooks   *****/
	/**********************************************/

	/**********************************************/
	/***** credits (see "Special:Version")    *****/
	/**********************************************/
	$wgExtensionCredits['specialpage'][]= array('name'=>'Semantic Drilldown', 'version'=>SD_VERSION, 'author'=>'Yaron Koren',
          'url'=>'http://www.mediawiki.org/wiki/Extension:Semantic_Drilldown', 'description' => 'A drilldown interface for navigating through semantic data');

	return true;
}

/**********************************************/
/***** namespace settings                 *****/
/**********************************************/

	/**
	 * Init the additional namespaces used by Semantic Drilldown.
	 */
	function sdfInitNamespaces() {
		global $sdgNamespaceIndex, $wgExtraNamespaces, $wgNamespaceAliases, $wgNamespacesWithSubpages, $smwgNamespacesWithSemanticLinks;
		global $wgLanguageCode, $sdgContLang;

		if (!isset($sdgNamespaceIndex)) {
			$sdgNamespaceIndex = 170;
		}

		define('SD_NS_FILTER',       $sdgNamespaceIndex);
		define('SD_NS_FILTER_TALK',  $sdgNamespaceIndex+1);

		sdfInitContentLanguage($wgLanguageCode);

		// Register namespace identifiers
		if (!is_array($wgExtraNamespaces)) { $wgExtraNamespaces=array(); }
		$wgExtraNamespaces = $wgExtraNamespaces + $sdgContLang->getNamespaces();
		// this code doesn't work, for some reason - leave it out for now
		//$wgNamespaceAliases = $wgNamespaceAliases + $sdgContLang->getNamespaceAliases();

		// Support subpages only for talk pages by default
		$wgNamespacesWithSubpages = $wgNamespacesWithSubpages + array(
			      SD_NS_FILTER_TALK => true
		);

		// Enable semantic links on filter pages
		$smwgNamespacesWithSemanticLinks = $smwgNamespacesWithSemanticLinks + array(
                      SD_NS_FILTER => true,
                      SD_NS_FILTER_TALK => false
		);
	}

/**********************************************/
/***** language settings                  *****/
/**********************************************/

	/**
	 * Initialise a global language object for content language. This
	 * must happen early on, even before user language is known, to
	 * determine labels for additional namespaces. In contrast, messages
	 * can be initialised much later when they are actually needed.
	 */
	function sdfInitContentLanguage($langcode) {
		global $sdgIP, $sdgContLang;

		if (!empty($sdgContLang)) { return; }

		$sdContLangClass = 'SD_Language' . str_replace( '-', '_', ucfirst( $langcode ) );

		if (file_exists($sdgIP . '/languages/'. $sdContLangClass . '.php')) {
			include_once( $sdgIP . '/languages/'. $sdContLangClass . '.php' );
		}

		// fallback if language not supported
		if ( !class_exists($sdContLangClass)) {
			include_once($sdgIP . '/languages/SD_LanguageEn.php');
			$sdContLangClass = 'SD_LanguageEn';
		}

		$sdgContLang = new $sdContLangClass();
	}

	/**
	 * Initialise the global language object for user language. This
	 * must happen after the content language was initialised, since
	 * this language is used as a fallback.
	 */
	function sdfInitUserLanguage($langcode) {
		global $sdgIP, $sdgLang;

		if (!empty($sdgLang)) { return; }

		$sdLangClass = 'SD_Language' . str_replace( '-', '_', ucfirst( $langcode ) );

		if (file_exists($sdgIP . '/languages/'. $sdLangClass . '.php')) {
			include_once( $sdgIP . '/languages/'. $sdLangClass . '.php' );
		}

		// fallback if language not supported
		if ( !class_exists($sdLangClass)) {
			global $sdgContLang;
			$sdgLang = $sdgContLang;
		} else {
			$sdgLang = new $sdLangClass();
		}
	}

	/**
	 * Initialise messages. These settings must be applied later on, since
	 * the MessageCache does not exist yet when the settings are loaded in
	 * LocalSettings.php.
	 */
	function sdfInitMessages() {
		global $sdgMessagesInPlace; // record whether the function was already called
		if ($sdgMessagesInPlace) { return; }

		global $wgMessageCache, $sdgContLang, $sdgLang, $wgContLang, $wgLang;
		// make sure that language objects exist
		sdfInitContentLanguage($wgContLang->getCode());
		sdfInitUserLanguage($wgLang->getCode());

		$wgMessageCache->addMessages($sdgContLang->getContentMsgArray(), $wgContLang->getCode());
		$wgMessageCache->addMessages($sdgLang->getUserMsgArray(), $wgLang->getCode());

		$sdgMessagesInPlace = true;
	}

/**********************************************/
/***** other global helpers               *****/
/**********************************************/


/**
 * Gets a list of the names of all categories in the wiki that aren't
 * children of some other category
 */
function sdfGetTopLevelCategories() {
        $categories = array();
        $dbr = wfGetDB( DB_SLAVE );
	$page = $dbr->tableName('page');
	$categorylinks = $dbr->tableName('categorylinks');
	$cat_ns = NS_CATEGORY;
	$sql = "SELECT page_title FROM $page p LEFT OUTER JOIN $categorylinks cl ON p.page_id = cl.cl_from WHERE p.page_namespace = $cat_ns AND cl.cl_to IS NULL";
	$res = $dbr->query($sql);
        if ($dbr->numRows( $res ) > 0) {
                while ($row = $dbr->fetchRow($res)) {
                        $categories[] = str_replace('_', ' ', $row[0]);
                }
        }
        $dbr->freeResult($res);
        return $categories;
}

/**
 * Generic database-access function - gets all the values that a specific
 * page points to with a specific property, that also match some other
 * constraints
 */
function sdfGetValuesForProperty($subject, $subject_namespace, $property, $is_relation, $object_namespace) {
	$values = array();
	$dbr = wfGetDB( DB_SLAVE );
	$cat_ns = NS_CATEGORY;
	if ($is_relation) {
		$table_name = $dbr->tableName( 'smw_relations' );
		$property_field = 'relation_title';
		$value_field = 'object_title';
	} else {
		$table_name = $dbr->tableName( 'smw_attributes' );
		$property_field = 'attribute_title';
		$value_field = 'value_xsd';
	}
	$property = str_replace(' ', '_', $property);
	$subject = str_replace(' ', '_', $subject);
	$sql = "SELECT $value_field
		FROM $table_name
		WHERE subject_title = '$subject'
		AND subject_namespace = $subject_namespace
		AND $property_field = '$property' ";
	if ($is_relation) {
		$sql .= "AND object_namespace = $object_namespace";
	}
	$res = $dbr->query($sql);
	while ($row = $dbr->fetchRow($res)) {
		$values[] = str_replace('_', ' ', htmlspecialchars_decode($row[0]));
	}
	$dbr->freeResult($res);
	return $values;
}

/**
 * Gets all the filters specified for a category.
 */
function sdfLoadFiltersForCategory($category) {
	global $sdgContLang;
	$sd_props = $sdgContLang->getSpecialPropertiesArray();
 
	$filters = array();
	$filter_names = sdfGetValuesForProperty(str_replace(' ', '_', $category), NS_CATEGORY, $sd_props[SD_SP_HAS_FILTER], true, SD_NS_FILTER);
	foreach ($filter_names as $filter_name) {
		$filters[] = SDFilter::load($filter_name);
	}
	return $filters;
}

function sdfGetCategoryChildren($category_name, $get_categories, $levels) {
	if ($levels == 0) {
		return array();
	}
	$pages = array();
	$subcategories = array();
	$dbr = wfGetDB( DB_SLAVE );
	$categorylinks = $dbr->tableName( 'categorylinks' );
	$page = $dbr->tableName( 'page' );
	$cat_ns = NS_CATEGORY;
	$query_category = str_replace(' ', '_', $category_name);
	$sql = "SELECT p.page_title, p.page_namespace FROM $categorylinks cl
		JOIN $page p on cl.cl_from = p.page_id
		WHERE cl.cl_to = '$query_category'\n";
	if ($get_categories)
		$sql .= "AND p.page_namespace = $cat_ns\n";
	$sql .= "ORDER BY cl.cl_sortkey";
	$res = $dbr->query($sql);
	while ($row = $dbr->fetchRow($res)) {
		if ($get_categories) {
			$subcategories[] = $row[0];
			$pages[] = $row[0];
		} else {
			if ($row[1] == $cat_ns)
				$subcategories[] = $row[0];
			else
				$pages[] = $row[0];
		}
	}
	$dbr->freeResult($res);
	foreach ($subcategories as $subcategory) {
		$pages = array_merge($pages, sdfGetCategoryChildren($subcategory, $get_categories, $levels - 1));
	}
	return $pages;
}

?>
