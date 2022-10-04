<?php

namespace SD\Specials\BrowseData;

use Closure;
use OutputPage;

class GetPageContent {

	private $getPageFromTitleText;
	private $output;

	public function __construct(
		Closure $getPageFromTitleText, OutputPage $output
	) {
		$this->getPageFromTitleText = $getPageFromTitleText;
		$this->output = $output;
	}

	public function __invoke( $titleText ): string {
		if ( $titleText !== null ) {
			$page = ( $this->getPageFromTitleText )( $titleText );
			if ( $page->exists() ) {
				$content = $page->getContent();
				$pageContent = $content->serialize();
				return $this->output->parseInlineAsInterface( $pageContent );
			}
		}
		return '';
	}

}
