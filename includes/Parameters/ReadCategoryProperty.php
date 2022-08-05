<?php

namespace SD\Parameters;

use Title;

class ReadCategoryProperty {

	public static function for( $category, $propertyName ) {
		$title = Title::newFromText( $category, NS_CATEGORY );

		// Return false if the title object couldn't be created.
		// This mainly happens if people change the $_cat in the url.
		if ( $title === null ) {
			return null;
		}

		$pageID = $title->getArticleID();
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->selectField( 'page_props', 'pp_value',
			[
				'pp_page' => $pageID,
				'pp_propname' => $propertyName
			]
		) ?: null;
	}

}
