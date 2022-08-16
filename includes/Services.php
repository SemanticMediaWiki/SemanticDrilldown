<?php

namespace SD;

use MediaWiki\MediaWikiServices;
use PageProps;
use SD\ParserFunctions\DrilldownInfo;
use SD\ParserFunctions\DrilldownLink;
use SD\Specials\BrowseData\SpecialBrowseData;
use Wikimedia\Rdbms\DBConnRef;

/**
 * The service locator of the SemanticDrilldown extension.
 * In the best case, only methods defined here are referenced by extension.json.
 */
class Services {

	private static ?Services $instance = null;

	private static function instance(): Services {
		if ( self::$instance === null ) {
			self::$instance = new Services();
		}
		return self::$instance;
	}

	private MediaWikiServices $services;

	private function __construct() {
		$this->services = MediaWikiServices::getInstance();
	}

	private const PARSER_FUNCTIONS = [
		'drilldowninfo' => DrilldownInfo::class,
		'drilldownlink' => DrilldownLink::class,
	];

	public static function onParserFirstCallInit( $parser ) {
		foreach ( self::PARSER_FUNCTIONS as $name => $class ) {
			$parser->setFunctionHook( $name,
				fn( $parser, ...$params ) => ( new $class( $parser ) )( $params ) );
		}
	}

	public static function getSpecialBrowseData(): SpecialBrowseData {
		$s = self::instance();
		return new SpecialBrowseData(
			$s->getRepository(),  $s->getPageProps(), $s->getFilterBuilder() );
	}

	private function getRepository(): Repository {
		return new Repository( $this->getDbConnectionRef() );
	}

	private function getFilterBuilder() {
		return new FilterBuilder( $this->getRepository(), $this->getPageSchemaFactory() );
	}

	private function getPageSchemaFactory(): PageSchemaFactory {
		return new class() implements PageSchemaFactory {
			public function get( $category ) {
				return class_exists( 'PSSchema' )
					? new \PSSchema( $category )
					: null;
			}
		};
	}

	private function getPageProps(): PageProps {
		return PageProps::getInstance();
	}

	private function getDbConnectionRef(): DBConnRef {
		return $this->services->getDBLoadBalancer()->getConnectionRef( DB_PRIMARY );
	}

}
