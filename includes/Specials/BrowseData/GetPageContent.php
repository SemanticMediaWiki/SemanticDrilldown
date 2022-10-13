<?php

namespace SD\Specials\BrowseData;

use Closure;
use IContextSource;
use OutputPage;

/**
 * Return HTML and resource loader modules corresponding to a page
 * as an array [ $html, $modules ] ([ '', [] ] if the page does not exist).
 */
class GetPageContent {

	private $getPageFromTitleText;
	private $context;

	public function __construct(
		Closure $getPageFromTitleText, IContextSource $context
	) {
		$this->getPageFromTitleText = $getPageFromTitleText;
		$this->context = $context;
	}

	public function __invoke( $titleText ): array {
		if ( $titleText !== null ) {
			$page = ( $this->getPageFromTitleText )( $titleText );
			if ( $page->exists() ) {
				$pageContent = $page->getContent()->serialize();
				$out = new OutputPage( $this->context );
				$out->addWikiTextAsInterface( $pageContent );
				return [ $out->getHTML(), $out->getModules() ];
			}
		}
		return [ '', [] ];
	}

}
