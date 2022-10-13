<?php

namespace SD;

use SD\Sql\PropertyTypeDbInfo;
use SD\Sql\SqlProvider;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IResultWrapper;

class DbService {

	private DBConnRef $dbw;
	private DBConnRef $dbr;

	public function __construct( ?DBConnRef $dbw, ?DBConnRef $dbr ) {
		$this->dbw = $dbw;
		$this->dbr = $dbr;
	}

	/**
	 * Execute a (readonly) query to the replica connection
	 *
	 * @param string $sql
	 * @return bool|IResultWrapper
	 */
	public function query( string $sql ) {
		return $this->dbr->query( $sql );
	}

	/**
	 * Creates a temporary database table of values that match the current
	 * set of filters selected by the user - used for displaying
	 * all remaining filters
	 */
	public function createTempTable( $category, $subcategory, $subcategories, $applied_filters ) {
		$temporaryTableManager = new TemporaryTableManager( $this->dbw );

		$sql0 = "DROP TABLE IF EXISTS semantic_drilldown_values;";
		$temporaryTableManager->queryWithAutoCommit( $sql0, __METHOD__ );

		$sql1 = "CREATE TEMPORARY TABLE semantic_drilldown_values ( id INT NOT NULL )";
		$temporaryTableManager->queryWithAutoCommit( $sql1, __METHOD__ );

		$sql2 = "CREATE INDEX id_index ON semantic_drilldown_values ( id )";
		$temporaryTableManager->queryWithAutoCommit( $sql2, __METHOD__ );

		$sql3 = "INSERT INTO semantic_drilldown_values SELECT ids.smw_id AS id\n";
		$sql3 .= SqlProvider::getSQLFromClause( $category, $subcategory, $subcategories, $applied_filters );
		$temporaryTableManager->queryWithAutoCommit( $sql3, __METHOD__ );
	}

	/**
	 * Creates a temporary database table, semantic_drilldown_filter_values,
	 * that holds every value held by any page in the wiki that matches
	 * the property associated with this filter. This table is useful
	 * both for speeding up later queries (at least, that's the hope)
	 * and for getting the set of 'None' values.
	 */
	public function createFilterValuesTempTable( $propertyType, $escaped_property ) {
		$smw_ids = $this->dbr->tableName( Utils::getIDsTableName() );

		$valuesTable = $this->dbr->tableName( PropertyTypeDbInfo::tableName( $propertyType ) );
		$value_field = PropertyTypeDbInfo::valueField( $propertyType );

		$query_property = $escaped_property;

		$sql = <<<END
	CREATE TEMPORARY TABLE semantic_drilldown_filter_values
	AS SELECT s_id AS id, $value_field AS value
	FROM $valuesTable
	JOIN $smw_ids p_ids ON $valuesTable.p_id = p_ids.smw_id

END;
		if ( $propertyType === 'page' ) {
			$sql .= "	JOIN $smw_ids o_ids ON $valuesTable.o_id = o_ids.smw_id\n";
		}
		$sql .= "	WHERE p_ids.smw_title = '$query_property'";

		$temporaryTableManager = new TemporaryTableManager( $this->dbw );
		$temporaryTableManager->queryWithAutoCommit( $sql, __METHOD__ );
	}

	/**
	 * Deletes the temporary table.
	 */
	public function dropFilterValuesTempTable() {
		// DROP TEMPORARY TABLE would be marginally safer, but it's
		// not supported on all RDBMS's.
		$sql = "DROP TABLE semantic_drilldown_filter_values";

		$temporaryTableManager = new TemporaryTableManager( $this->dbw );
		$temporaryTableManager->queryWithAutoCommit( $sql, __METHOD__ );
	}

	/**
	 * Gets the number of pages matching both the currently-selected
	 * set of filters and either a new subcategory or a new filter.
	 */
	public function getNumResults( $subcategory, $subcategories, $new_filter = null ) {
		// Escape the given values to prevent SQL injection
		$subcategory = $this->dbr->addQuotes( $subcategory );
		foreach ( $subcategories as $key => $value ) {
			$subcategories[$key] = $this->dbr->addQuotes( $value );
		}

		$sql = "SELECT COUNT(DISTINCT sdv.id) ";
		if ( $new_filter ) {
			$sql .= SqlProvider::getSQLFromClauseForField( $new_filter );
		} else {
			$sql .= SqlProvider::getSQLFromClauseForCategory( $subcategory, $subcategories );
		}
		$res = $this->query( $sql );
		$row = $res->fetchRow();
		return $row[0];
	}

	public function getCategoryChildren( $category_name, $get_categories, $levels ) {
		if ( $levels == 0 ) {
			return [];
		}
		$pages = [];
		$subcategories = [];
		$conds = [ 'cl_to' => str_replace( ' ', '_', $category_name ), ];
		if ( $get_categories ) {
			$conds['page_namespace'] = NS_CATEGORY;
		}

		$res = $this->dbr->select(
			[ 'categorylinks', 'page' ],
			[ 'page_title', 'page_namespace' ],
			$conds,
			__METHOD__,
			[
				'ORDER BY' => 'cl_sortkey',
			],
			[
				'page' => [
					'JOIN',
					[
						'cl_from = page_id'
					]
				]
			]
		);

		foreach ( $res as $row ) {
			if ( $get_categories ) {
				$subcategories[] = $row->page_title;
				$pages[] = $row->page_namespace;
			} else {
				if ( $row->page_title == NS_CATEGORY ) {
					$subcategories[] = $row->page_title;
				} else {
					$pages[] = $row->page_title;
				}
			}
		}
		foreach ( $subcategories as $subcategory ) {
			$pages = array_merge( $pages, $this->getCategoryChildren( $subcategory, $get_categories, $levels - 1 ) );
		}
		return $pages;
	}

	/**
	 * Returns the list of categories that will show up in the
	 * header/sidebar of the 'BrowseData' special page.
	 */
	public function getCategoriesForBrowsing() {
		global $sdgHideCategoriesByDefault;

		if ( $sdgHideCategoriesByDefault ) {
			return $this->getOnlyExplicitlyShownCategories();
		} else {
			return $this->getTopLevelCategories();
		}
	}

	/**
	 * Gets the list of names of only those categories in the wiki
	 * that have a __SHOWINDRILLDOWN__ declaration on their page.
	 */
	private function getOnlyExplicitlyShownCategories() {
		$shown_cats = [];

		$res = $this->dbr->select(
			[ 'p' => 'page', 'pp' => 'page_props' ],
			'p.page_title',
			[
				'p.page_namespace' => NS_CATEGORY,
				'pp.pp_propname' => 'showindrilldown',
				'pp.pp_value' => 'y'
			],
			self::class . '::getOnlyExplicitlyShownCategories',
			[ 'ORDER BY' => 'p.page_title' ],
			[ 'pp' => [ 'JOIN', 'p.page_id = pp.pp_page' ] ]
		);

		foreach ( $res as $row ) {
			$shown_cats[] = str_replace( '_', ' ', $row->page_title );
		}

		return $shown_cats;
	}

	/**
	 * Gets a list of the names of all categories in the wiki that aren't
	 * children of some other category - this list additionally includes,
	 * and excludes, categories that are manually set with
	 * 'SHOWINDRILLDOWN' and 'HIDEFROMDRILLDOWN', respectively.
	 */
	public function getTopLevelCategories() {
		$categories = [];
		$res = $this->dbr->select(
			[ 'page', 'categorylinks' ],
			'page_title',
			[
				'page_namespace' => NS_CATEGORY,
				'cl_to' => null,
			],
			__METHOD__,
			[],
			[
				'categorylinks' => [
					'LEFT OUTER JOIN',
					[
						'page_id = cl_from'
					]
				],
			]
		);
		foreach ( $res as $row ) {
			$categories[] = str_replace( '_', ' ', $row->page_title );
		}

		// get 'hide' and 'show' categories
		$hidden_cats = $shown_cats = [];
		$res2 = $this->dbr->select(
			[ 'page', 'page_props' ],
			[ 'page_title', 'pp_propname' ],
			[
				'page_namespace' => NS_CATEGORY,
				'pp_propname' => [ 'hidefromdrilldown', 'showindrilldown' ],
				'pp_value' => 'y',
			],
			__METHOD__,
			[],
			[
				'page_props' => [
					'JOIN',
					[
						'page_id = pp_page'
					]
				],
			]
		);
		foreach ( $res2 as $row ) {
			if ( $row->pp_propname == 'hidefromdrilldown' ) {
				$hidden_cats[] = str_replace( '_', ' ', $row->page_title );
			} else {
				$shown_cats[] = str_replace( '_', ' ', $row->page_title );
			}
		}
		$categories = array_merge( $categories, $shown_cats );
		foreach ( $hidden_cats as $hidden_cat ) {
			foreach ( $categories as $i => $cat ) {
				if ( $cat == $hidden_cat ) {
					unset( $categories[$i] );
				}
			}
		}
		sort( $categories );
		// This shouldn't be necessary, but sometimes it is, due
		// to faulty storage in either MW or SMW.
		$categories = array_unique( $categories );
		return $categories;
	}

}
