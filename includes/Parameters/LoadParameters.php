<?php

namespace SD\Parameters;

use PageProps;

class LoadParameters {

	private PageProps $pageProps;

	public function __construct( PageProps $pageProps ) {
		$this->pageProps = $pageProps;
	}

	public function __invoke( $category ): Parameters {
		$title = \Title::newFromText( $category, NS_CATEGORY );
		$properties = $this->pageProps->getProperties( $title, [
			Title::PAGE_PROPERTY_NAME,
			Header::PAGE_PROPERTY_NAME,
			Footer::PAGE_PROPERTY_NAME,
			Filters::PAGE_PROPERTY_NAME,
			DisplayParametersList::PAGE_PROPERTY_NAME
		] );

		if ( empty( $properties ) ) {
			return new Parameters();
		}

		$values = array_values( $properties )[0];
		$get = fn( $propertyName ) =>
			array_key_exists( $propertyName, $values ) ? $values[ $propertyName ] : null;
		return new Parameters(
			Title::fromPropertyValue( $get( Title::PAGE_PROPERTY_NAME ) )->value,
			Header::fromPropertyValue( $get( Header::PAGE_PROPERTY_NAME ) )->value,
			Footer::fromPropertyValue( $get( Footer::PAGE_PROPERTY_NAME ) )->value,
			Filters::fromPropertyValue( $get( Filters::PAGE_PROPERTY_NAME ) ),
			DisplayParametersList::fromPropertyValue( $get( DisplayParametersList::PAGE_PROPERTY_NAME ) )
		);
	}
}
