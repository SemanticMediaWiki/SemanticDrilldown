<?php
/**
 * Internationalization file for the Semantic Drilldown extension
 *
 * @addtogroup Extensions
*/

$messages = array();

/** English
 * @author Yaron Koren
 */
$messages['en'] = array(
	// user messages
	'browsedata' => 'Browse data',
	'sd_browsedata_choosecategory' => 'Choose a category',
	'sd_browsedata_viewcategory' => 'view category',
	'sd_browsedata_subcategory' => 'Subcategory',
	'sd_browsedata_other' => 'Other',
	'sd_browsedata_none' => 'None',
	'sd_browsedata_filterbyvalue' => 'Filter by this value',
	'sd_browsedata_filterbysubcategory' => 'Filter by this subcategory',
	'sd_browsedata_otherfilter' => 'Show pages with another value for this filter',
	'sd_browsedata_nonefilter' => 'Show pages with no value for this filter',
        'sd_browsedata_removefilter' => 'Remove this filter',
        'sd_browsedata_removesubcategoryfilter' => 'Remove this subcategory filter',
        'sd_browsedata_resetfilters' => 'Reset filters',
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
	'sd_blank_error' => 'cannot be blank',

	// content messages
	'sd_filter_coversproperty' => 'This filter covers the property $1.',
	'sd_filter_getsvaluesfromcategory' => 'It gets its values from the category $1.',
	'sd_filter_usestimeperiod' => 'It uses $1 as its time period.',
	'sd_filter_year' => 'Year',
	'sd_filter_month' => 'Month',
	'sd_filter_hasvalues' => 'It has the values $1.',
	'sd_filter_requiresfilter' => 'It requires the presence of the filter $1.',
	'sd_filter_haslabel' => 'It has the label $1.',
);

/** German
 * @author Bernhard Krabina
 */
$messages['de'] = array(
	// user messages
	'browsedata' => 'Daten ansehen',
	'sd_browsedata_choosecategory' => 'Wähleneine Kategorie',
	'sd_browsedata_viewcategory' => 'Kategorie ansehen',
	'sd_browsedata_subcategory' => 'Unterkategorie',
	'sd_browsedata_other' => 'Anderes',
	'sd_browsedata_none' => 'Keines',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Die folgenden Filter existieren in diesem Wiki:',
	'createfilter' => 'Erstelle einen Filter',
	'sd_createfilter_name' => 'Name:',
	'sd_createfilter_property' => 'Attribut dieses Filters:',
	'sd_createfilter_usepropertyvalues' => 'Verwende alle Werte dieses Attributs für den Filter.',
	'sd_createfilter_usecategoryvalues' => 'Verwende die Werte für den Filter von dieser Kategorie:',
	'sd_createfilter_usedatevalues' => 'Verwende folgende Zeitangabe für diesen Filter:',
	'sd_createfilter_entervalues' => 'Verwende diese Werte für den Filter (Werte durch Komma getrennt eingeben. Wenn ein Wert ein Komma enthält, mit "\," ersetzen.):',
	'sd_createfilter_label' => 'Bezeichnung dieses Filters (optional):',
	'sd_createfilter_requirefilter' => 'Bevor dieser Filter angezeigt wird, muss folgender anderer Filter gesetzt sein:',
	'sd_blank_error' => 'darf nicht leer sein',

	// content messages
	'sd_filter_coversproperty' => 'Dieser Filter betrifft das Attribut $1.',
	'sd_filter_getsvaluesfromcategory' => 'Er erhält seine Werte aus der Kategorie $1.',
	'sd_filter_usestimeperiod' => 'Verwendet $1 als Zeitangabe.',
	'sd_filter_year' => 'Jahr',
	'sd_filter_month' => 'Monat',
	'sd_filter_hasvalues' => 'Hat den Wert $1.',
	'sd_filter_requiresfilter' => 'Setzt den Filter $1 voraus.',
	'sd_filter_haslabel' => 'Hat die Bezeichnung $1.'
);


/** Persian
 * @author Ghassem Tofighi
 */
$messages['fa'] = array(
	// user messages
	'browsedata' => 'نمایش اطلاعات',
	'sd_browsedata_choosecategory' => 'انتخاب یک رده',
	'sd_browsedata_viewcategory' => 'نمایش رده',
	'sd_browsedata_subcategory' => 'زیررده',
	'sd_browsedata_other' => 'دیگر',
	'sd_browsedata_none' => 'هیچکدام',
	'filters' => 'فیلترها',
	'sd_filters_docu' => 'فیلترهای زیر در این ویکی وجود دارد:',
	'createfilter' => 'فیلتر بسازید',
	'sd_createfilter_name' => 'نام:',
	'sd_createfilter_property' => 'ویژگی که این فیلتر شامل آن می‌شود:',
	'sd_createfilter_usepropertyvalues' => 'همه مقادیر این ویژگی را برای این فیلتر به‌کار برید',
	'sd_createfilter_usecategoryvalues' => 'مقادیر فیلتر را از این رده بگیرید:',
	'sd_createfilter_usedatevalues' => 'بازه زمانی که به عنوان پریود زمانی این فیلتر به‌کار گرفته شود:',
	'sd_createfilter_entervalues' => 'مقادیر فیلتر را دستی وارد کنید (مقادیر باید با کاما جدا شوند، اگر یک مقدار کاما دارد، آن‌را با "\،" جایگزین کنید):',
	'sd_createfilter_label' => 'برچسب این فیلتر (دلخواه)',
	'sd_createfilter_requirefilter' => 'قبل از نمایش این یکی، یک فیلتر دیگر باید انتخاب شود:',
	'sd_blank_error' => 'نمی‌تواند خالی باشد',

	// content messages
	'sd_filter_coversproperty' => 'این فیلتر ویژگی $1 را شامل می‌شود.',
	'sd_filter_getsvaluesfromcategory' => 'مقادیرش را از رده $1 می‌گیرد',
	'sd_filter_usestimeperiod' => '$1 را به عنوان پریود زمانی به‌کار می‌برد',
	'sd_filter_year' => 'سال',
	'sd_filter_month' => 'ماه',
	'sd_filter_hasvalues' => 'مقادیر $1 را دارد.',
	'sd_filter_requiresfilter' => 'به وجود فیلتر $1 احتیاج دارد.',
	'sd_filter_haslabel' => 'برچسب $1 دارد.',
);


/** Mainland Chinese
 * @author Roc Michael
 */
$messages['zh-cn'] = array(
	// user messages
	'browsedata' => '查看资料',
	'sd_browsedata_choosecategory' => '选取某项分类(category)',
	'sd_browsedata_viewcategory' => '查看分类页面',
	'sd_browsedata_subcategory' => '子分类',
	'sd_browsedata_other' => '其他的',
	'sd_browsedata_none' => '无',
	'filters' => '筛选器',
	'sd_filters_docu' => '此wiki系统内已设有如下的筛选器(filters)',
	'createfilter' => '建立筛选器',
	'sd_createfilter_name' => '名称：',
	'sd_createfilter_property' => '此一筛选器所涵盖的性质：',
	'sd_createfilter_usepropertyvalues' => '将此一性质的值设给筛选器所用',
	'sd_createfilter_usecategoryvalues' => '从此分类中为筛选器取得筛选值：',
	'sd_createfilter_usedatevalues' => '以此一期间为此筛选器设置日期范围值：',
	'sd_createfilter_entervalues' => '以手工赋予值的方式设置此一筛选器(其值必须以半型逗号分隔「,」，如果您的资料中已含有半型逗号，则须以「\,」符号取代)：',
	'sd_createfilter_label' => '为此一筛选器设置标签(选择性的)：',
	'sd_createfilter_requirefilter' => '在此一筛选器展示其作用之前要求须选取其他的筛选器：',
	'sd_createfilter_entervalues' => '以手工的方式键入筛选器的筛选值(其值必须以半型逗号","分隔，如果您的输入值内包含半型逗号则须则"\,"取代):',
	'sd_createfilter_label' => '为此一筛选选器设置标签(选择性的)：',

	'sd_blank_error' => '不得为空白',

	// content messages
	'sd_filter_coversproperty' => '此筛选器涵盖了$1性质。',
	'sd_filter_getsvaluesfromcategory' => '其从$1分类取得它的值。',
	'sd_filter_usestimeperiod' => '其使用「$1」做为时间期限值',
	'sd_filter_year' => '年',
	'sd_filter_month' => '月',
	'sd_filter_hasvalues' => '其含有$1值。',
	'sd_filter_requiresfilter' => '其以$1筛选器为基础。',
	'sd_filter_hasvalues' => '其有着$1的这些值。',
	'sd_filter_haslabel' => '其有着此一$1标签'  ,
);

/** Taiwanese Chinese
 * @author Roc Michael
 */
$messages['zh-tw'] = array(
	// user messages
	'browsedata' => '查看資料',
	'sd_browsedata_choosecategory' => '選取某項分類(category)',
	'sd_browsedata_viewcategory' => '查看分類頁面',
	'sd_browsedata_subcategory' => '子分類',
	'sd_browsedata_other' => '其他的',
	'sd_browsedata_none' => '無',
	'filters' => '篩選器',
	'sd_filters_docu' => '此wiki系統內已設有如下的篩選器(filters)',
	'createfilter' => '建立篩選器',
	'sd_createfilter_name' => '名稱：',
	'sd_createfilter_property' => '此一篩選器所涵蓋的性質：',
	'sd_createfilter_usepropertyvalues' => '將此一性質的值設給篩選器所用',
	'sd_createfilter_usecategoryvalues' => '從此分類中為篩選器取得篩選值：',
	'sd_createfilter_usedatevalues' => '以此一期間為此篩選器設置日期範圍值：',
	'sd_createfilter_entervalues' => '以手工賦予值的方式設置此一篩選器(其值必須以半型逗號分隔「,」，如果您的資料中已含有半型逗號，則須以「\,」符號取代)：',
	'sd_createfilter_label' => '為此一篩選器設置標籤(選擇性的)：',
	'sd_createfilter_requirefilter' => '在此一篩選器展示其作用之前要求須選取其他的篩選器(即此一篩選器的作用係以另一篩選器為其基礎)：',
	'sd_createfilter_entervalues' => '以手工的方式鍵入篩選器的篩選值(其值必須以半型逗號","分隔，如果您的輸入值內包含半型逗號則須則"\,"取代):',
	'sd_createfilter_label' => '為此一篩選選器設定標籤(選擇性的)：',
	'sd_blank_error' => '不得為空白',

	// content messages
	'sd_filter_coversproperty' => '此篩選器涵蓋了$1性質。',
	'sd_filter_getsvaluesfromcategory' => '其從$1分類取得它的值。',
	'sd_filter_usestimeperiod' => '其使用「$1」做為時間期限值',
	'sd_filter_year' => '年',
	'sd_filter_month' => '月',
	'sd_filter_hasvalues' => '其含有$1值。',
	'sd_filter_requiresfilter' => '其以$1篩選器為基礎。',
	'sd_filter_hasvalues' => '其有著$1的這些值。',
	'sd_filter_haslabel' => '其有著此一$1標籤'  ,
);
