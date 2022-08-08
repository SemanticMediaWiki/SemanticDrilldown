<?php

namespace SD\Sql;

class PropertyTypeDbInfo {

	public static function tableName( string $propertyType ): ?string {
		switch ( $propertyType ) {
			case 'page':
				return 'smw_di_wikipage';
			case 'boolean':
				return 'smw_di_bool';
			case 'date':
				return 'smw_di_time';
			case 'number':
				return 'smw_di_number';
			default:
				return 'smw_di_blob';
		}
	}

	public static function valueField( string $propertyType ): ?string {
		global $wgDBtype;
		switch ( $propertyType ) {
			case 'page':
				return 'o_ids.smw_title';
			case 'boolean':
				return 'o_value';
			case 'date':
			case 'number':
				return 'o_serialized';
			default:
				// CONVERT() is also supported in PostgreSQL,
				// but it doesn't seem to work the same way.
				// IF() is not supported in PostgreSQL - there
				// is an alternative CASE() statement, but
				// let's just keep it simple - in part in
				// order to also support other DB types.
				return $wgDBtype == 'mysql'
					? '(IF(o_blob IS NULL, o_hash, CONVERT(o_blob using utf8)))' : 'o_hash';
		}
	}

	/**
	 * Used for getting year and month from the date field.
	 */
	public static function dateField( string $propertyType ): ?string {
		global $wgDBtype;
		switch ( $propertyType ) {
			case 'date':
				$result = 'SUBSTR(o_serialized, 3, 100)';
				if ( $wgDBtype == 'mysql' ) {
					// SMW date field has the following format: 2000/2/11/22/0/1/0, where every /-separated
					// segment is optional. All of these are valid: 2000, 2000/2, 2000/2/11.
					// However, YEAR(), MONTH() and DAY() would return NULL for incomplete date,
					// so STR_TO_DATE is required.
					$result = "STR_TO_DATE($result, '%Y/%m/%d')";
				} elseif ( $wgDBtype == 'sqlite' ) {
					// probably one of the greatest moments in software development:
					$result = "DATE(SUBSTR(
						REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE($result || '/', '/', '//'), 
						'/1/', '/01/'), '/2/', '/02/'), '/3/', '/03/'), '/4/', '/04/'), '/5/', '/05/'), '/6/', '/06/'), '/7/', '/07/'), '/8/', '/08/'), '/9/', '/09/'),
						'//', '-'), 1, 10))";
				}

				return $result;
			default:
				return null;
		}
	}

}
