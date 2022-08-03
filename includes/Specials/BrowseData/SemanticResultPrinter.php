<?php

namespace SD\Specials\BrowseData;

use Closure;
use SD\Utils;
use SMW\Query\PrintRequest;
use SMW\Query\QueryResult;
use SMWDIWikiPage;
use SMWQuery;
use SMWQueryProcessor;

/**
 * Allows to use an ordinary result set to be printed using one of the various semantic
 * result printers
 */
class SemanticResultPrinter {

	private Closure $getSmwQueryResult;

	public function __construct( $res, $num ) {
		$this->getSmwQueryResult = self::createGetSmwQueryResult( $res, $num );
	}

	public function getText( array $displayParameters ) {
		[ $querystring, $params, $printouts ] =
			SMWQueryProcessor::getComponentsFromFunctionParams( $displayParameters, false );

		$query = !empty( $querystring )
			? SMWQueryProcessor::createQuery( $querystring, $params )
			: new SMWQuery();

		if ( !array_key_exists( 'format', $params ) ) {
			$params['format'] = 'category';
		}

		$mainlabel = array_key_exists( 'mainlabel', $params )
			? $params['mainlabel']
			: '';

		// Must be fetched *before* the SMWQueryProcessor::addThisPrintout call
		// (Mainlabel will be fetched twice otherwise.)
		$smwQueryResults = ( $this->getSmwQueryResult )( $query, $mainlabel, $printouts );

		SMWQueryProcessor::addThisPrintout( $printouts, $params );
		$printer = SMWQueryProcessor::getResultPrinter( $params['format'], SMWQueryProcessor::SPECIAL_PAGE );
		$params = SMWQueryProcessor::getProcessedParams( $params, $printouts );

		$result = $printer->getResult( $smwQueryResults, $params, SMW_OUTPUT_WIKI );
		$text = is_array( $result ) ? $result[0] : $result;

		return $text;
	}

	private static function createGetSmwQueryResult( $res, $num ) {
		$qr = [];
		$count = 0;
		$store = Utils::getSMWStore();
		while ( ( $count < $num ) && ( $row = $res->fetchObject() ) ) {
			$count++;
			$qr[] = new SMWDIWikiPage( $row->t, $row->ns, '' );
			if ( method_exists( $store, 'cacheSMWPageID' ) ) {
				$store->cacheSMWPageID( $row->id, $row->t, $row->ns, $row->iw, '' );
			}
		}
		if ( $res->fetchObject() ) {
			$count++;
		}
		$furtherRes = $count > $num;

		return static function ( $query, $mainlabel, $printouts ) use ( $qr, $store, $furtherRes ) {
			$printrequest = new PrintRequest( PrintRequest::PRINT_THIS, $mainlabel );
			$main_printout = [];
			$main_printout[$printrequest->getHash()] = $printrequest;
			$printouts = array_merge( $main_printout, $printouts );
			return new QueryResult( $printouts, $query, $qr, $store, $furtherRes );
		};
	}

}
