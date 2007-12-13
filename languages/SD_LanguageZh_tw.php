<?php
/**
 *@author Yaron Koren 翻譯:張致信(Translation: Roc Michael Email:roc.no1@gmail.com)
 */

class SD_LanguageZh_tw extends SD_Language {

/* private */ var $m_ContentMessages = array(
);

/* private */ var $m_UserMessages = array(
	'viewdata' => '查看數據',
	'sd_viewdata_choosecategory' => '選取分類(category)',
	'sd_viewdata_subcategory' => '子分類(Subcategory)',
	'sd_viewdata_other' => '其他的',
	'sd_viewdata_none' => '無',
	'filters' => '篩選器',
	'sd_filters_docu' => '此wiki系統內已設有如下的篩選器(filters)：'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
        SD_SP_HAS_FILTER  => '設置篩選器',//It's mean "Setting Filter" in English.
        SD_SP_COVERS_PROPERTY  => '涵蓋性質',
        SD_SP_HAS_VALUE  => '篩選值',  //It's mean "the values which the filter depend on them" in English.
	SD_SP_GETS_VALUES_FROM_CATEGORY => '應用篩選值於分類', //It's mean "applying to filter values on the category" in English.
        SD_SP_HAS_LABEL  => '設置標籤'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> '篩選器',
	SD_NS_FILTER_TALK	=> '篩選器討論'
);

}
