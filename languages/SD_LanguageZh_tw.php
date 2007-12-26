<?php
/**
 *@author Yaron Koren 翻譯:張致信(Translation: Roc Michael Email:roc.no1@gmail.com) 
 */

class SD_LanguageZh_tw extends SD_Language {

/* private */ var $m_ContentMessages = array(
	'sd_filter_coversproperty' => '此篩選器涵蓋了$1性質。',  //This filter covers the property $1.
	'sd_filter_getsvaluesfromcategory' => '其從$1分類取得它的值。',  //It gets its values from the category $1
	'sd_filter_usestimeperiod' => '其使用「$1」做為時間期限值',  //It uses $1 as the time period
	'sd_filter_year' => '年',
	'sd_filter_month' => '月',
	'sd_filter_hasvalues' => '其含有$1值。',  //It has the values $1
	'sd_filter_requiresfilter' => '其以$1篩選器為基礎。',  //It requires the presence of the filter $1.
	'sd_filter_hasvalues' => '其有著$1的這些值。', //It has the values $1.
	'sd_filter_haslabel' => '其有著此一$1標籤'  //It has the label $1.
);

/* private */ var $m_UserMessages = array(
	'viewdata' => '查看資料',
	'sd_viewdata_choosecategory' => '選取某項分類(category)',
	'sd_viewdata_viewcategory' => '查看分類頁面', 
	'sd_viewdata_subcategory' => '子分類',
	'sd_viewdata_other' => '其他的',
	'sd_viewdata_none' => '無',
	'filters' => '篩選器',
	'sd_filters_docu' => '此wiki系統內已設有如下的篩選器(filters)',
	'createfilter' => '建立篩選器',
	'sd_createfilter_name' => '名稱：',
	'sd_createfilter_property' => '此一篩選器所涵蓋的性質：',  //Property that this filter covers:
	'sd_createfilter_usepropertyvalues' => '將此一性質的值設給篩選器所用',  //Use all of this property's values for the filter
	'sd_createfilter_usecategoryvalues' => '從此分類中為篩選器取得篩選值：',  //Get values for filter from this category:
	'sd_createfilter_usedatevalues' => '以此一期間為此篩選器設置日期範圍值：',  //Use date ranges for this filter with this time period:
	'sd_createfilter_entervalues' => '以手工賦予值的方式設置此一篩選器(其值必須以半型逗號分隔「,」，如果您的資料中已含有半型逗號，則須以「\,」符號取代)：',  //Enter values for filter manually (values should be separated by commas - if a value contains a comma, replace it with "\,"):
	'sd_createfilter_label' => '為此一篩選器設置標籤(選擇性的)：',
	'sd_createfilter_requirefilter' => '在此一篩選器展示其作用之前要求須選取其他的篩選器(即此一篩選器的作用係以另一篩選器為其基礎)：',  //'Require another filter to be selected before this one is displayed:',  
	'sd_createfilter_entervalues' => '以手工的方式鍵入篩選器的篩選值(其值必須以半型逗號","分隔，如果您的輸入值內包含半型逗號則須則"\,"取代):',
	'sd_createfilter_label' => '為此一篩選選器設定標籤(選擇性的)：',

	'sd_blank_error' => '不得為空白'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
	// category properties
        SD_SP_HAS_FILTER  => '設置篩選器', //'Has filter'
	// filter properties
        SD_SP_COVERS_PROPERTY  => '涵蓋性質', //'Covers property',
        SD_SP_HAS_VALUE  => '篩選值',  //'Has value',
        SD_SP_GETS_VALUES_FROM_CATEGORY => '設分類為篩選值', //'Gets values from category',
        SD_SP_USES_TIME_PERIOD => '時間期限', //'Uses time period', 
        SD_SP_REQUIRES_FILTER => '基礎篩選器', //'Requires filter', 
        SD_SP_HAS_LABEL  => '設置標籤'  //'Has label'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> '篩選器',
	SD_NS_FILTER_TALK	=> '篩選器討論'
);

}
