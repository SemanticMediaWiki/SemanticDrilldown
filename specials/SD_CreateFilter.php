<?php
/**
 * A special page holding a form that allows the user to create a filter
 * page.
 *
 * @author Yaron Koren
 */

class SDCreateFilter extends SpecialPage {

	/**
	 * Constructor
	 */
	public function SDCreateFilter() {
		parent::__construct( 'CreateFilter' );
	}

	static function createFilterText( $property_name, $values_source, $category_used, $required_filter, $filter_label ) {
		global $sdgContLang;

		$sd_props = $sdgContLang->getPropertyLabels();
		$property_tag = "[[" . $sd_props[SD_SP_COVERS_PROPERTY] . "::$property_name]]";
		$text = wfMsgForContent( 'sd_filter_coversproperty', $property_tag );
		if ( $values_source == 'category' ) {
			$category_tag = "[[" . $sd_props[SD_SP_GETS_VALUES_FROM_CATEGORY] . "::$category_used]]";
			$text .= " " . wfMsgForContent( 'sd_filter_getsvaluesfromcategory', $category_tag );
		} elseif ( $values_source == 'property' ) {
			// do nothing
		}
		if ( $required_filter != '' ) {
			$sd_namespace_labels = $sdgContLang->getNamespaces();
			$filter_namespace = $sd_namespace_labels[SD_NS_FILTER];
			$filter_tag = "[[" . $sd_props[SD_SP_REQUIRES_FILTER] . "::$filter_namespace:$required_filter|$required_filter]]";
			$text .= " " . wfMsgForContent( 'sd_filter_requiresfilter', $filter_tag );
		}
		if ( $filter_label != '' ) {
			$filter_label_tag = "[[" . $sd_props[SD_SP_HAS_LABEL] . "::$filter_label]]";
			$text .= " " . wfMsgForContent( 'sd_filter_haslabel', $filter_label_tag );
		}
		return $text;
	}


	function execute( $query ) {
		global $wgOut, $wgRequest;

		$this->setHeaders();

		// Cycle through the query values, setting the appropriate
		// local variables
		$presetFilterName = str_replace( '_', ' ', $query );
		if ( $presetFilterName !== '' ) {
			$wgOut->setPageTitle( wfMsg( 'sd-createfilter-with-name', $presetFilterName) );
			$filter_name = $presetFilterName;
		} else {
			$filter_name = $wgRequest->getVal( 'filter_name' );
		}
		$values_source = $wgRequest->getVal( 'values_source' );
		$property_name = $wgRequest->getVal( 'property_name' );
		$category_name = $wgRequest->getVal( 'category_name' );
		$required_filter = $wgRequest->getVal( 'required_filter' );
		$filter_label = $wgRequest->getVal( 'filter_label' );

		$save_button_text = wfMsg( 'savearticle' );
		$preview_button_text = wfMsg( 'preview' );
		$filter_name_error_str = '';
		$save_page = $wgRequest->getCheck( 'wpSave' );
		$preview_page = $wgRequest->getCheck( 'wpPreview' );
		if ( $save_page || $preview_page ) {
			// Validate filter name.
			if ( $filter_name == '' ) {
				$filter_name_error_str = wfMsg( 'sd_blank_error' );
			} else {
				// Redirect to wiki interface.
				$title = Title::newFromText( $filter_name, SD_NS_FILTER );
				$full_text = self::createFilterText( $property_name, $values_source, $category_name, $required_filter, $filter_label );
				// HTML-encode.
				$full_text = str_replace( '"', '&quot;', $full_text );
				$text = SDUtils::printRedirectForm( $title, $full_text, "", $save_page, $preview_page, false, false, false );
				$wgOut->addHTML( $text );
				return;
			}
		}

		// Set 'title' as hidden field, in case there's no URL niceness
		global $wgContLang;
		$mw_namespace_labels = $wgContLang->getNamespaces();
		$special_namespace = $mw_namespace_labels[NS_SPECIAL];
		$name_label = wfMsg( 'sd_createfilter_name' );
		$property_label = wfMsg( 'sd_createfilter_property' );
		$label_label = wfMsg( 'sd_createfilter_label' );
		$text = <<<END

	<form action="" method="post">

END;
		if ( $presetFilterName === '' ) {
			$text .= "\t" . Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) . "\n";
			$text .= <<<END
	<p>$name_label <input size="25" name="filter_name" value="">
	<span style="color: red;">$filter_name_error_str</span></p>

END;
		}
		$text .= <<<END
	<p>$property_label
	<select id="property_dropdown" name="property_name">

END;
		$all_properties = SDUtils::getSemanticProperties();
		foreach ( $all_properties as $property_name ) {
			$text .= "	<option>$property_name</option>\n";
		}

		$values_from_property_label = wfMsg( 'sd_createfilter_usepropertyvalues' );
		$values_from_category_label = wfMsg( 'sd_createfilter_usecategoryvalues' );
		$require_filter_label = wfMsg( 'sd_createfilter_requirefilter' );
		$text .= <<<END
	</select>
	</p>
	<p><input type="radio" name="values_source" checked value="property">
	$values_from_property_label
	</p>
	<p><input type="radio" name="values_source" value="category">
	$values_from_category_label
	<select id="category_dropdown" name="category_name">

END;
		$categories = SDUtils::getCategoriesForBrowsing();
		foreach ( $categories as $category ) {
			$category = str_replace( '_', ' ', $category );
			$text .= "	<option>$category</option>\n";
		}
		$text .= <<<END
	</select>
	</p>
	<p>$require_filter_label
	<select id="required_filter_dropdown" name="required_filter">
	<option />

END;
		$filters = SDUtils::getFilters();
		foreach ( $filters as $filter ) {
			$filter = str_replace( '_', ' ', $filter );
			$text .= "	<option>$filter</option>\n";
		}
		$text .= <<<END
	</select>
	</p>
	<p>$label_label <input size="25" name="filter_label" value=""></p>
	<div class="editButtons">
	<p>
	<input type="submit" id="wpSave" name="wpSave" value="$save_button_text">
	<input type="submit" id="wpPreview" name="wpPreview" value="$preview_button_text"></p>
	</div>

END;

		$text .= "	</form>\n";

		$wgOut->addHTML( $text );
	}

}
