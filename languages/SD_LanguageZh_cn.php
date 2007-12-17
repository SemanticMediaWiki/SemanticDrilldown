<?php
/**
 *@author Yaron Koren 翻译:张致信 本档系以电子字典译自繁体版，请自行修订(Translation: Roc Michael Email:roc.no1@gmail.com. This file is translated from Tradition Chinese by useing electronic dictionary. Please correct the file by yourself.) 
 */

class SD_LanguageZh_cn extends SD_Language {

/* private */ var $m_ContentMessages = array(
	'sd_filter_coversproperty' => '此筛选器涵盖了$1性质。',  //This filter covers the property $1.
	'sd_filter_getsvaluesfromcategory' => '其从$1取得它的值。',  //It gets its values from the category $1
	'sd_filter_hasvalues' => '其有着$1的这些值。', //It has the values $1.
	'sd_filter_haslabel' => '其有着此一$1标签'  //It has the label $1.
);

/* private */ var $m_UserMessages = array(
	'viewdata' => '查看资料',
	'sd_viewdata_choosecategory' => '选取某项分类(category)',
	'sd_viewdata_subcategory' => '子分类',
	'sd_viewdata_other' => '其他的',
	'sd_viewdata_none' => '无',
	'filters' => '筛选器',
	'sd_filters_docu' => '此wiki系统内已设有如下的筛选器(filters)',
	'createfilter' => '建立筛选器',
	'sd_createfilter_name' => '名称：',
	'sd_createfilter_property' => '此一筛选器所涵盖的性质：',  //Property that this filter covers:
	'sd_createfilter_usepropertyvalues' => '使用此一性质的「允许值」做为筛选器',  //Use allowed values for this property for the filter
	'sd_createfilter_usecategoryvalues' => '从此分类中为筛选器取得筛选值：',  //Get values for filter from this category:
	'sd_createfilter_entervalues' => '以手工的方式键入筛选器的筛选值(其值必须以半型逗号","分隔，如果您的输入值内包含半型逗号则须则"\,"取代):',
	'sd_createfilter_label' => '为此一筛选选器设置标签(选择性的)：',

	'sd_blank_error' => '不得为空白'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
	// category properties
        SD_SP_HAS_FILTER  => '设置筛选器',
	// filter properties
        SD_SP_COVERS_PROPERTY  => '涵盖性质',
        SD_SP_HAS_VALUE  => '筛选值',
	SD_SP_GETS_VALUES_FROM_CATEGORY => '应用筛选值于分类',
        SD_SP_HAS_LABEL  => '设置标签'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> '筛选器',
	SD_NS_FILTER_TALK	=> '筛选器讨论'
);

}
