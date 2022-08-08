<?php

namespace SD;

use MediaWiki\Hook\ParserFirstCallInitHook;
use SD\ParserFunctions\DrilldownInfo;
use SD\ParserFunctions\DrilldownLink;
use SD\Specials\BrowseData\SpecialBrowseData;
use Wikimedia\Rdbms\DBConnRef;

/**
 * The service locator of the SemanticDrilldown extension.
 * In the best case, only methods defined here are referenced by extension.json.
 */
class Services implements ParserFirstCallInitHook {

	private const PARSER_FUNCTIONS = [
		'drilldowninfo' => DrilldownInfo::class,
		'drilldownlink' => DrilldownLink::class,
	];

	public function onParserFirstCallInit( $parser ) {
		foreach ( self::PARSER_FUNCTIONS as $name => $class ) {
			$parser->setFunctionHook( $name,
				fn( $parser, ...$params ) => ( new $class( $parser ) )( $params ) );
		}
	}

	public static function getSpecialBrowseData(): SpecialBrowseData {
		return new SpecialBrowseData( self::getRepository() );
	}

	private static function getRepository(): Repository {
		return new Repository( self::getDbw() );
	}

	private static function getDbw(): DBConnRef {
		return wfGetDB( DB_MASTER );
	}

}
