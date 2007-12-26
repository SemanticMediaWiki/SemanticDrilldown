<?php
/**
 * @author Yaron Koren
 */

class SD_LanguageEn extends SD_Language {

/* private */ var $m_ContentMessages = array(
	'sd_filter_coversproperty' => 'This filter covers the property $1.',
	'sd_filter_getsvaluesfromcategory' => 'It gets its values from the category $1.',
	'sd_filter_usestimeperiod' => 'It uses $1 as the time period.',
	'sd_filter_year' => 'Year',
	'sd_filter_month' => 'Month',
	'sd_filter_hasvalues' => 'It has the values $1.',
	'sd_filter_requiresfilter' => 'It requires the presence of the filter $1.',
	'sd_filter_haslabel' => 'It has the label $1.'
);

/* private */ var $m_UserMessages = array(
	'viewdata' => 'View data',
	'sd_viewdata_choosecategory' => 'Choose a category',
	'sd_viewdata_viewcategory' => 'view category',
	'sd_viewdata_subcategory' => 'Subcategory',
	'sd_viewdata_other' => 'Other',
	'sd_viewdata_none' => 'None',
	'filters' => 'Filters',
	'sd_filters_docu' => 'The following filters exist in the wiki:',
	'createfilter' => 'Create a filter',
	'sd_createfilter_name' => 'Name:',
	'sd_createfilter_property' => 'Property that this filter covers:',
	'sd_createfilter_usepropertyvalues' => 'Use all values of this property for the filter',
	'sd_createfilter_usecategoryvalues' => 'Get values for filter from this category:',
	'sd_createfilter_usedatevalues' => 'Use date ranges for this filter with this time period:',
	'sd_createfilter_entervalues' => 'Enter values for filter manually (values should be separated by commas - if a value contains a comma, replace it with "\,"):',
	'sd_createfilter_label' => 'Label for this filter (optional):',
	'sd_createfilter_requirefilter' => 'Require another filter to be selected before this one is displayed:',

	'sd_blank_error' => 'cannot be blank'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
	// category properties
        SD_SP_HAS_FILTER  => 'Has filter',
	// filter properties
        SD_SP_COVERS_PROPERTY  => 'Covers property',
        SD_SP_HAS_VALUE  => 'Has value',
	SD_SP_GETS_VALUES_FROM_CATEGORY => 'Gets values from category',
	SD_SP_USES_TIME_PERIOD => 'Uses time period',
	SD_SP_REQUIRES_FILTER => 'Requires filter',
        SD_SP_HAS_LABEL  => 'Has label'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> 'Filter',
	SD_NS_FILTER_TALK	=> 'Filter_talk'
);

}
