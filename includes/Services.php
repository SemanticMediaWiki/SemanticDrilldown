<?php

namespace SD;

use Closure;
use MediaWiki\MediaWikiServices;
use OutputPage;
use PageProps;
use SD\ParserFunctions\DrilldownInfo;
use SD\ParserFunctions\DrilldownLink;
use SD\Specials\BrowseData\DrilldownQuery;
use SD\Specials\BrowseData\Printer;
use SD\Specials\BrowseData\QueryPage;
use SD\Specials\BrowseData\SpecialBrowseData;
use WebRequest;
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
			$s->getNewQuery(), $s->getNewQueryPage(),	$s->getFilterBuilder() );
	}

	private function getRepository(): Repository {
		return new Repository( $this->getDbConnectionRef() );
	}

	private function getFilterBuilder() {
		return new FilterBuilder( $this->getRepository(), $this->getGetPageSchema() );
	}

	private function getGetPageSchema(): Closure {
		return fn( $category ) => class_exists( 'PSSchema' )
			? new \PSSchema( $category )
			: null;
	}

	private function getNewQuery(): Closure {
		return fn( $category, $subcategory, $filters, $applied_filters, $remaining_filters ) =>
			new DrilldownQuery( $this->getRepository(),
				$category, $subcategory, $filters, $applied_filters, $remaining_filters );
	}

	private function getNewQueryPage(): Closure {
		return fn( $context, $query, $offset, $limit ) =>
			new QueryPage( $this->getNewPrinter(), $context, $query, $offset, $limit );
	}

	private function getNewPrinter(): Closure {
		return fn( OutputPage $output, WebRequest $request, DrilldownQuery $query ) =>
			new Printer( $this->getRepository(), $this->getPageProps(), $output, $request, $query );
	}

	private function getPageProps(): PageProps {
		return PageProps::getInstance();
	}

	private function getDbConnectionRef(): DBConnRef {
		return $this->services->getDBLoadBalancer()->getConnectionRef( DB_PRIMARY );
	}

}
