<?php
/**
 *@author Yaron Koren 翻譯:張致信(Translation: Roc Michael Email:roc.no1@gmail.com) 
 */

class SD_LanguageZh_tw extends SD_Language {

/* private */ var $m_ContentMessages = array(
	'sd_filter_coversproperty' => '此篩選器涵蓋了$1性質。',  //This filter covers the property $1.
	'sd_filter_getsvaluesfromcategory' => '其從$1取得它的值。',  //It gets its values from the category $1
	'sd_filter_hasvalues' => '其有著$1的這些值。', //It has the values $1.
	'sd_filter_haslabel' => '其有著此一$1標籤'  //It has the label $1.
);

/* private */ var $m_UserMessages = array(
	'viewdata' => '查看資料',
	'sd_viewdata_choosecategory' => '選取某項分類(category)',
	'sd_viewdata_subcategory' => '子分類',
	'sd_viewdata_other' => '其他的',
	'sd_viewdata_none' => '無',
	'filters' => '篩選器',
	'sd_filters_docu' => '此wiki系統內已設有如下的篩選器(filters)',
	'createfilter' => '建立篩選器',
	'sd_createfilter_name' => '名稱：',
	'sd_createfilter_property' => '此一篩選器所涵蓋的性質：',  //Property that this filter covers:
	'sd_createfilter_usepropertyvalues' => '使用此一性質的「允許值」做為篩選器',  //Use allowed values for this property for the filter
	'sd_createfilter_usecategoryvalues' => '從此分類中為篩選器取得篩選值：',  //Get values for filter from this category:
	'sd_createfilter_entervalues' => '以手工的方式鍵入篩選器的篩選值(其值必須以半型逗號","分隔，如果您的輸入值內包含半型逗號則須則"\,"取代):',
	'sd_createfilter_label' => '為此一篩選選器設定標籤(選擇性的)：',

	'sd_blank_error' => '不得為空白'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
	// category properties
        SD_SP_HAS_FILTER  => '設置篩選器',
	// filter properties
        SD_SP_COVERS_PROPERTY  => '涵蓋性質',
        SD_SP_HAS_VALUE  => '篩選值',
	SD_SP_GETS_VALUES_FROM_CATEGORY => '應用篩選值於分類',
        SD_SP_HAS_LABEL  => '設置標籤'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> '篩選器',
	SD_NS_FILTER_TALK	=> '篩選器討論'
);

}
