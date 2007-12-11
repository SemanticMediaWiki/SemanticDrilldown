<?php
/**
 * @author Yaron Koren
 */

class SD_LanguageEn extends SD_Language {

/* private */ var $m_ContentMessages = array(
);

/* private */ var $m_UserMessages = array(
	'viewdata' => 'View data',
	'sd_viewdata_choosecategory' => 'Choose a category',
	'sd_viewdata_subcategory' => 'Subcategory',
	'sd_viewdata_other' => 'Other',
	'sd_viewdata_none' => 'None',
	'filters' => 'Filters',
	'sd_filters_docu' => 'The following filters exist in the wiki:'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
        SD_SP_HAS_FILTER  => 'Has filter',
        SD_SP_COVERS_PROPERTY  => 'Covers property',
        SD_SP_HAS_VALUE  => 'Has value',
	SD_SP_GETS_VALUES_FROM_CATEGORY => 'Gets values from category',
        SD_SP_HAS_LABEL  => 'Has label'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> 'Filter',
	SD_NS_FILTER_TALK	=> 'Filter_talk'
);

}
