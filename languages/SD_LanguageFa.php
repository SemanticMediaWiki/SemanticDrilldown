<?php
/**
 * @author Yaron Koren (Translation:Ghassem Tofighi Email:[MyFamily]@gmail.com, HomePage:http://ght.ir)
 */

class SD_LanguageFa extends SD_Language {

/* private */ var $m_ContentMessages = array(
	'sd_filter_coversproperty' => 'این فیلتر ویژگی $1 را شامل می‌شود.',//This filter covers the property $1.
	'sd_filter_getsvaluesfromcategory' => 'مقادیرش را از رده $1 می‌گیرد',//It gets its values from the category $1.
	'sd_filter_hasvalues' => 'مقادیر $1 را دارد.',//It has the values $1.
	'sd_filter_haslabel' => 'برچسب $1 دارد.'//It has the label $1.
);

/* private */ var $m_UserMessages = array(
	'viewdata' => 'نمایش اطلاعات',//View data
	'sd_viewdata_choosecategory' => 'انتخاب یک رده',//Choose a category
	'sd_viewdata_subcategory' => 'زیررده',//Subcategory
	'sd_viewdata_other' => 'دیگر',//Other
	'sd_viewdata_none' => 'هیچکدام',//None
	'filters' => 'فیلترها',//Filters
	'sd_filters_docu' => 'فیلترهای زیر در این ویکی وجود دارد:',//The following filters exist in the wiki:
	'createfilter' => 'فیلتر بسازید',//Create a filter
	'sd_createfilter_name' => 'نام:',//Name:
	'sd_createfilter_property' => 'ویژگی که این فیلتر شامل آن می‌شود:',//Property that this filter covers:
	'sd_createfilter_usepropertyvalues' => 'مقادیر مجاز این ویژگی را برای فیلتر بکار برید',//Use allowed values for this property for the filter
	'sd_createfilter_usecategoryvalues' => 'مقادیر فیلتر را از این رده بگیرید:',//Get values for filter from this category:
	'sd_createfilter_entervalues' => 'مقادیر فیلتر را دستی وارد کنید (مقادیر باید با کاما جدا شوند، اگر یک مقدار کاما دارد، آن‌را با "\،" جایگزین کنید):',//Enter values for filter manually (values should be separated by commas - if a value contains a comma, replace it with "\,"):
	'sd_createfilter_label' => 'برچسب این فیلتر (دلخواه)',//Label for this filter (optional):

	'sd_blank_error' => 'نمی‌تواند خالی باشد'//cannot be blank
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
	// category properties
        SD_SP_HAS_FILTER  => 'فیلتر دارد',//Has filter
	// filter properties
        SD_SP_COVERS_PROPERTY  => 'ویژگی را شامل می‌شود',//Covers property
        SD_SP_HAS_VALUE  => 'مقدار دارد',//Has value
	SD_SP_GETS_VALUES_FROM_CATEGORY => 'مقادیر را از رده می‌گیرد',//Gets values from category
        SD_SP_HAS_LABEL  => 'برچسب دارد'//Has label
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> 'فیلتر',//Filter
	SD_NS_FILTER_TALK	=> 'بحث_فیلتر'//Filter_talk
);

}
