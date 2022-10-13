<?php

namespace SD;

use Closure;
use MediaWiki\MediaWikiServices;
use PageProps;
use SD\Parameters\LoadParameters;
use SD\ParserFunctions\DrilldownInfo;
use SD\ParserFunctions\DrilldownLink;
use SD\Specials\BrowseData\DrilldownQuery;
use SD\Specials\BrowseData\QueryPage;
use SD\Specials\BrowseData\SpecialBrowseData;
use SD\Specials\BrowseData\UrlService;
use SpecialPage;
use Title;
use WebRequest;
use Wikimedia\Rdbms\DBConnRef;
use WikiPage;

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
			$s->getDbService(), $s->getNewUrlService(),
			$s->getLoadParameters(), $s->getNewQuery(),
			$s->getNewQueryPage(), $s->getBuildFilters() );
	}

	private function getDbService(): DbService {
		return new DbService( $this->getPrimaryDbConnectionRef(), $this->getReplicaDbConnectionRef() );
	}

	private function getBuildFilters(): BuildFilters {
		return new BuildFilters( $this->getNewFilter(), $this->getGetPageSchema() );
	}

	private function getGetPageSchema(): Closure {
		return fn( $category ) => class_exists( 'PSSchema' ) ? new \PSSchema( $category ) : null;
	}

	private function getNewQuery(): Closure {
		return fn( $category, $subcategory, $filters, $applied_filters, $remaining_filters ) =>
			new DrilldownQuery( $this->getDbService(),
				$category, $subcategory, $filters, $applied_filters, $remaining_filters );
	}

	private function getNewQueryPage(): Closure {
		// Using a prefix different from wg, the ServiceOptions approach does not work anymore;
		// use the global variable instead:
		global $sdgResultFormatTypes;

		return fn( $context, $parameters, $query, $offset, $limit ) =>
			new QueryPage(
				$sdgResultFormatTypes,
				$this->getDbService(), $this->getPageProps(), $this->getNewUrlService(),
				$this->getGetPageFromTitleText(),
				$context, $parameters, $query, $offset, $limit );
	}

	private function getNewUrlService(): Closure {
        // phpcs:ignore MediaWiki.Usage.AssignmentInReturn.AssignmentInReturn
		return fn( WebRequest $request, ?DrilldownQuery $query = null ) =>
			new UrlService(
				SpecialPage::getTitleFor( 'BrowseData' )->getLocalURL(), $request, $query );
	}

	private function getNewFilter(): Closure {
		// phpcs:ignore MediaWiki.Usage.AssignmentInReturn.AssignmentInReturn
		return fn( $name, $property, $category, $requiredFilters, $int, $propertyType = null,
				   $timePeriod = null, $allowedValues = null ) =>
			new Filter( $this->getDbService(),
			   $name, $property, $category, $requiredFilters, $int, $propertyType, $timePeriod, $allowedValues );
	}

	private function getLoadParameters(): LoadParameters {
		return new LoadParameters( $this->getPageProps() );
	}

	private function getPageProps(): PageProps {
		return PageProps::getInstance();
	}

	private function getGetPageFromTitleText(): Closure {
		return static function ( string $text ) {
			$title = Title::newFromText( $text );
			return WikiPage::factory( $title );
		};
	}

	private function getPrimaryDbConnectionRef(): DBConnRef {
		return $this->services->getDBLoadBalancer()->getConnectionRef( DB_PRIMARY );
	}

	private function getReplicaDbConnectionRef(): DBConnRef {
		return $this->services->getDBLoadBalancer()->getConnectionRef( DB_REPLICA );
	}

}
