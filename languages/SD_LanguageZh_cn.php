<?php
/**
 *@author Yaron Koren 翻译:张致信 本档系以电子字典译自繁体版，请自行修订(Translation: Roc Michael Email:roc.no1@gmail.com. This file is translated from Tradition Chinese by useing electronic dictionary. Please correct the file by yourself.) 
 */

class SD_LanguageZh_cn extends SD_Language {

/* private */ var $m_ContentMessages = array(
);

/* private */ var $m_UserMessages = array(
	'viewdata' => '查看数据',
	'sd_viewdata_choosecategory' => '选取分类(category)',
	'sd_viewdata_subcategory' => '子分类(Subcategory)',
	'sd_viewdata_other' => '其他的',
	'sd_viewdata_none' => '无',
	'filters' => '筛选器',
	'sd_filters_docu' => '此wiki系统内已设有如下的筛选器(filters)：'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
        SD_SP_HAS_FILTER  => '设置筛选器',　　//It's mean "Setting Filter" in English.
        SD_SP_COVERS_PROPERTY  => '涵盖性质', 
        SD_SP_HAS_VALUE  => '筛选值',  //It's mean "the values which the filter depend on them" in English.
	SD_SP_GETS_VALUES_FROM_CATEGORY => '应用筛选值于分类', //It's mean "applying to filter values on the category" in English.
        SD_SP_HAS_LABEL  => '设置标签'  
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> '筛选器',
	SD_NS_FILTER_TALK	=> '筛选器讨论'
);

}

?>
