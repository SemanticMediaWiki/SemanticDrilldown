<?php
/**
 * Internationalization file for the Semantic Drilldown extension
 *
 * @ingroup Language
 * @ingroup I18n
 * @ingroup SDLanguage
*/

$messages = array();

/** English
 * @author Yaron Koren
 */
$messages['en'] = array(
	// user messages
	'semanticdrilldown-desc'		=> 'A drilldown interface for navigating through semantic data',
	'specialpages-group-sd_group'           => 'Semantic Drilldown',
	'browsedata'                            => 'Browse data',
	'sd_browsedata_choosecategory'          => 'Choose a category',
	'sd_browsedata_viewcategory'            => 'view category',
	'sd_browsedata_docu'                    => 'Click on one or more items below to narrow your results.',
	'sd_browsedata_subcategory'             => 'Subcategory',
	'sd_browsedata_other'                   => 'Other',
	'sd_browsedata_none'                    => 'None',
	'sd_browsedata_filterbyvalue'           => 'Filter by this value',
	'sd_browsedata_filterbysubcategory'     => 'Filter by this subcategory',
	'sd_browsedata_otherfilter'             => 'Show pages with another value for this filter',
	'sd_browsedata_nonefilter'              => 'Show pages with no value for this filter',
	'sd_browsedata_or'			=> 'or',
	'sd_browsedata_removefilter'            => 'Remove this filter',
	'sd_browsedata_removesubcategoryfilter' => 'Remove this subcategory filter',
	'sd_browsedata_resetfilters'            => 'Reset filters',
	'sd_browsedata_addanothervalue'		=> 'Click arrow to add another value',
	'sd_browsedata_daterangestart'		=> 'Start:',
	'sd_browsedata_daterangeend'		=> 'End:',
	'sd_browsedata_novalues'		=> 'There are no values for this filter',
	'filters'                               => 'Filters',
	'sd_filters_docu'                       => 'The following filters exist in {{SITENAME}}:',
	'sd_formcreate'                         => 'Create with form',
	'sd_viewform'                           => 'View form',
	'createfilter'                          => 'Create a filter',
	'sd-createfilter-with-name'             => 'Create filter: $1',
	'sd_createfilter_name'                  => 'Name:',
	'sd_createfilter_property'              => 'Property that this filter covers:',
	'sd_createfilter_usepropertyvalues'     => 'Use all values of this property for the filter',
	'sd_createfilter_usecategoryvalues'     => 'Get values for filter from this category:',
	'sd_createfilter_requirefilter'         => 'Require another filter to be selected before this one is displayed:',
	'sd_createfilter_label'                 => 'Label for this filter (optional):',
	'sd_blank_error'                        => 'cannot be blank',
	'sd-pageschemas-filter'			=> 'Filter',
	'sd-pageschemas-values'			=> 'Values',

	// content messages
	'sd_filter_coversproperty'         => 'This filter covers the property $1.',
	'sd_filter_getsvaluesfromcategory' => 'It gets its values from the category $1.',
	'sd_filter_requiresfilter'         => 'It requires the presence of the filter $1.',
	'sd_filter_haslabel'               => 'It has the label $1.',
);

/** Message documentation (Message documentation)
 * @author EugeneZelenko
 * @author Jon Harald Søby
 * @author Kghbln
 * @author Purodha
 * @author Shirayuki
 * @author Siebrand
 * @author The Evil IP address
 * @author Umherirrender
 */
$messages['qqq'] = array(
	'semanticdrilldown-desc' => '{{desc|name=Semantic Drilldown|url=http://www.mediawiki.org/wiki/Extension:SemanticDrilldown}}',
	'specialpages-group-sd_group' => '{{doc-special-group|that=are related to Semantic Drilldown|like=[[Special:Filters]], [[Special:CreateFilter]], [[Special:BrowseData]]}}',
	'browsedata' => '{{doc-special|BrowseData}}',
	'sd_browsedata_choosecategory' => 'This it the title of the box on [[Special:BrowseData]] that displays the categories that my be browsed.',
	'sd_browsedata_viewcategory' => '{{doc-special|BrowseData}}',
	'sd_browsedata_docu' => 'This is an informatory message providing help for the usage of [[Special:BrowseData]].',
	'sd_browsedata_subcategory' => 'This is the title of the section on [[Special:BrowseData]] listing the subcategories of the category currently browsed.',
	'sd_browsedata_other' => 'This is the name of the value automatically shown on [[Special:BrowseData]] in case some preconditions apply. 
{{Identical|Other}}',
	'sd_browsedata_none' => 'This is the name of the value automatically shown on [[Special:BrowseData]] in case some preconditions apply.
{{Identical|None}}',
	'sd_browsedata_filterbyvalue' => 'This is the content of a tooltip on [[Special:BrowseData]] when you hover your mouse over a browsable property value.',
	'sd_browsedata_filterbysubcategory' => 'This is the content of a tooltip on [[Special:BrowseData]] when you hover your mouse over a browsable subcategory.',
	'sd_browsedata_otherfilter' => '{{doc-special|BrowseData}}',
	'sd_browsedata_nonefilter' => '{{doc-special|BrowseData}}',
	'sd_browsedata_or' => '{{Identical|Or}}',
	'sd_browsedata_removefilter' => 'This is the content of a tooltip on [[Special:BrowseData]] when you hover your mouse over a filter currently in use.',
	'sd_browsedata_removesubcategoryfilter' => 'This is the content of a tooltip on [[Special:BrowseData]] when you hover your mouse over a filter currently in use.',
	'sd_browsedata_resetfilters' => 'This is the content of a tooltip on [[Special:BrowseData]] when you hover your mouse over the category currently used for a filter.',
	'sd_browsedata_addanothervalue' => 'This is an informatory message providing help for the usage of [[Special:BrowseData]].',
	'sd_browsedata_daterangestart' => 'The title of the input field on [[Special:BrowseData]] that allows to enter the start date of a filter.
{{Identical|Start}}',
	'sd_browsedata_daterangeend' => 'The title of the input field on [[Special:BrowseData]] that allows to enter the end date of a filter.',
	'sd_browsedata_novalues' => 'This is an informatory message on [[Special:BrowseData]].',
	'filters' => '{{doc-special|Filter}}
{{Identical|Filter}}',
	'sd_filters_docu' => 'This is an informatory message at the top of [[Special:Filters]].',
	'sd_formcreate' => 'This is the text of the tab at the top of a page that allows to create a filter with a form.',
	'sd_viewform' => 'This is the text of the tab at the top of a page that allows to look at form.',
	'createfilter' => '{{doc-special|CreateFilter}}',
	'sd-createfilter-with-name' => 'This is the title of an non-existing page in namespace filter upon creation of a new filter with a form.
* $1 - name of the filter to be created',
	'sd_createfilter_name' => 'The title of the input field on [[Special:CreateFilter]] that allows to enter the name of a filter. 
{{Identical|Name}}',
	'sd_createfilter_property' => 'The title of the drop-down list on [[Special:CreateFilter]] that allows to select an available property.',
	'sd_createfilter_usepropertyvalues' => 'An option on [[Special:CreateFilter]] that may be chosen upon creation of a filter.',
	'sd_createfilter_usecategoryvalues' => 'An option on [[Special:CreateFilter]] that may be chosen upon creation of a filter. It is followed by a drop-down list that allows to select an available category.',
	'sd_createfilter_requirefilter' => 'The title of the drop-down list on [[Special:CreateFilter]] that allows to select an available filter.',
	'sd_createfilter_label' => 'The title of the field on [[Special:CreateFilter]] that allows to enter the label of a filter.',
	'sd_blank_error' => 'This is an error message that gets shown if a required input was not provided.
{{Identical|Cannot be blank}}',
	'sd-pageschemas-filter' => 'The title of the section displaying information about a single filter used by the page schema. Provided for the [[mw:Extension:Page_Schemas|Page Schemas]] extension.
{{Identical|Filter}}',
	'sd-pageschemas-values' => 'The title of the subsection to {{msg-mw|Sd-pageschemas-filter}} displaying information about the set of values for a single filter used by the page schema. Provided for the [[mw:Extension:Page_Schemas|Page Schemas]] extension.
{{Identical|Value}}',
	'sd_filter_coversproperty' => 'This is a content message of a page in namespace filter describing an aspect of the filter defined on this page.
* $1 - name of the property',
	'sd_filter_getsvaluesfromcategory' => 'This is a content message of a page in namespace filter describing an aspect of the filter defined on this page.
* $1 - name of the category',
	'sd_filter_requiresfilter' => 'This is a content message of a page in namespace filter describing an aspect of the filter defined on this page.
* $1 - name of the filter',
	'sd_filter_haslabel' => 'This is a content message of a page in namespace filter describing an aspect of the filter defined on this page.
* $1 - name of the label',
);

/** Afrikaans (Afrikaans)
 * @author Arnobarnard
 * @author Naudefj
 * @author SPQRobin
 */
$messages['af'] = array(
	'browsedata' => 'Bekyk gegewens',
	'sd_browsedata_choosecategory' => "Kies 'n kategorie",
	'sd_browsedata_viewcategory' => 'wys kategorie',
	'sd_browsedata_subcategory' => 'Subkategorie',
	'sd_browsedata_other' => 'Ander',
	'sd_browsedata_none' => 'Geen',
	'sd_browsedata_filterbyvalue' => 'Filter op hierdie waarde',
	'sd_browsedata_filterbysubcategory' => 'Filter op hierdie subkategorie',
	'sd_browsedata_or' => 'of',
	'sd_browsedata_removefilter' => 'Verwyder hierdie filter',
	'sd_browsedata_removesubcategoryfilter' => 'Verwyder hierdie subkategorie-filter',
	'sd_browsedata_resetfilters' => 'Herstel filters',
	'sd_browsedata_addanothervalue' => "Kliek die pyltjie om nog 'n waarde by te voeg",
	'sd_browsedata_daterangestart' => 'Begin:',
	'sd_browsedata_daterangeend' => 'Einde:',
	'sd_browsedata_novalues' => 'Daar is geen waardes vir hierdie filter nie',
	'filters' => 'Filters',
	'sd_filters_docu' => 'Die volgende filters bestaan in {{SITENAME}}:',
	'createfilter' => "Skep 'n filter",
	'sd_createfilter_name' => 'Naam:',
	'sd_createfilter_label' => 'Etiket vir hierdie filter (opsioneel):',
	'sd_blank_error' => 'mag nie leeg wees nie',
);

/** Amharic (አማርኛ)
 * @author Codex Sinaiticus
 */
$messages['am'] = array(
	'sd_createfilter_name' => 'ስም:',
);

/** Aragonese (aragonés)
 * @author Juanpabl
 */
$messages['an'] = array(
	'sd_browsedata_other' => 'Un atro',
	'sd_browsedata_none' => 'Garra',
	'filters' => 'Filtros',
	'sd_createfilter_name' => 'Nombre:',
);

/** Arabic (العربية)
 * @author Alnokta
 * @author Meno25
 * @author OsamaK
 * @author Ouda
 */
$messages['ar'] = array(
	'semanticdrilldown-desc' => 'واجهة نزول للإبحار خلال بيانات سيمانتيك',
	'specialpages-group-sd_group' => 'سيمانتيك دريل داون',
	'browsedata' => 'تصفح البيانات',
	'sd_browsedata_choosecategory' => 'اختر تصنيفا',
	'sd_browsedata_viewcategory' => 'عرض التصنيف',
	'sd_browsedata_docu' => 'اضغط على واحد أو أكثر من المدخلات بالأسفل لتضييق نتائجك.',
	'sd_browsedata_subcategory' => 'تصنيف فرعي',
	'sd_browsedata_other' => 'آخر',
	'sd_browsedata_none' => 'لا شيء',
	'sd_browsedata_filterbyvalue' => 'ترشيح بناءً هذه القيمة',
	'sd_browsedata_filterbysubcategory' => 'ترشيح بناءً على هذا التصنيف الفرعي',
	'sd_browsedata_otherfilter' => 'اعرض الصفحات بقيمة أخرى لهذا الفلتر',
	'sd_browsedata_nonefilter' => 'اعرض الصفحات التي هي بدون قيمة لهذا الفلتر',
	'sd_browsedata_or' => 'أو',
	'sd_browsedata_removefilter' => 'أزل هذا المُرشِّح',
	'sd_browsedata_removesubcategoryfilter' => 'أزل مُرشّح التصنيف الفرعي هذا',
	'sd_browsedata_resetfilters' => 'أعد ضبط المُرشِّحات',
	'sd_browsedata_addanothervalue' => 'اضغط على السهم لإضافة قيمة أخرى',
	'sd_browsedata_daterangestart' => 'البداية:',
	'sd_browsedata_daterangeend' => 'النهاية:',
	'sd_browsedata_novalues' => 'لا توجد قيم لهذا المرشح',
	'filters' => 'مُرشّحات',
	'sd_filters_docu' => 'المرشحات التالية موجودة في {{SITENAME}}:',
	'sd_formcreate' => 'أنشئ بنموذج',
	'sd_viewform' => 'عرض من',
	'createfilter' => 'أنشئ مُرشِّحًا',
	'sd_createfilter_name' => 'الاسم:',
	'sd_createfilter_property' => 'الخاصية التي يغطيها هذا الفلتر:',
	'sd_createfilter_usepropertyvalues' => 'استخدم كل قيم هذه الخاصية للفلتر',
	'sd_createfilter_usecategoryvalues' => 'احصل على القيم للفلتر من هذا التصنيف:',
	'sd_createfilter_requirefilter' => 'يتطلب اختيار مُرشّح آخر قبل أن يتم عرض هذا:',
	'sd_createfilter_label' => 'علامة لهذا الفلتر (اختياري):',
	'sd_blank_error' => 'لا يمكن أن يكون فارغا',
	'sd_filter_coversproperty' => 'هذا الفلتر يغطي الخاصية $1.',
	'sd_filter_getsvaluesfromcategory' => 'يحصل على قيمه من التصنيف $1.',
	'sd_filter_requiresfilter' => 'يتطلب وجود الفلتر $1.',
	'sd_filter_haslabel' => 'يمتلك العلامة $1.',
);

/** Aramaic (ܐܪܡܝܐ)
 * @author Basharh
 */
$messages['arc'] = array(
	'sd_browsedata_choosecategory' => 'ܓܒܝ ܣܕܪܐ',
	'sd_browsedata_viewcategory' => 'ܚܘܝ ܣܕܪܐ',
	'sd_browsedata_subcategory' => 'ܣܕܪ̈ܐ ܦܪ̈ܥܝܐ',
	'sd_browsedata_other' => 'ܐܚܪܢܐ',
	'sd_browsedata_none' => 'ܠܐ ܡܕܡ',
	'sd_browsedata_or' => 'ܐܘ',
	'sd_browsedata_daterangestart' => 'ܫܘܪܝܐ:',
	'sd_browsedata_daterangeend' => 'ܫܘܠܡܐ:',
	'createfilter' => 'ܒܪܝ ܡܨܦܝܢܝܬܐ',
	'sd_createfilter_name' => 'ܫܡܐ:',
	'sd_blank_error' => 'ܠܐ ܡܬܡܨܝܢܐ ܐܝܬܘܗܝ ܕܢܗܘܐ ܣܦܝܩܐ',
);

/** Egyptian Spoken Arabic (مصرى)
 * @author Meno25
 * @author Ouda
 * @author Ramsis II
 */
$messages['arz'] = array(
	'semanticdrilldown-desc' => 'واجهة نزول للإبحار خلال بيانات سيمانتيك',
	'specialpages-group-sd_group' => 'سيمانتيك دريل داون',
	'browsedata' => 'تصفح البيانات',
	'sd_browsedata_choosecategory' => 'اختر تصنيفا',
	'sd_browsedata_viewcategory' => 'عرض التصنيف',
	'sd_browsedata_docu' => 'اضغط على واحد أو أكثر من المدخلات بالأسفل لتضييق نتائجك.',
	'sd_browsedata_subcategory' => 'تصنيف فرعي',
	'sd_browsedata_other' => 'آخر',
	'sd_browsedata_none' => 'لا شيء',
	'sd_browsedata_filterbyvalue' => 'فلترة بواسطة هذه القيمة',
	'sd_browsedata_filterbysubcategory' => 'فلترة بواسطة هذا التصنيف الفرعي',
	'sd_browsedata_otherfilter' => 'اعرض الصفحات بقيمة أخرى لهذا الفلتر',
	'sd_browsedata_nonefilter' => 'اعرض الصفحات التى هى بدون قيمة لهذا الفلتر',
	'sd_browsedata_or' => 'أو',
	'sd_browsedata_removefilter' => 'أزل هذا الفلتر',
	'sd_browsedata_removesubcategoryfilter' => 'أزل فلتر التصنيف الفرعى هذا',
	'sd_browsedata_resetfilters' => 'أعد ضبط الفلاتر',
	'sd_browsedata_addanothervalue' => 'اضغط على السهم لإضافة قيمة أخرى',
	'sd_browsedata_daterangestart' => ':البداية',
	'sd_browsedata_daterangeend' => ':النهاية',
	'filters' => 'فلاتر',
	'sd_filters_docu' => 'الفلاتر التالية موجودة فى {{SITENAME}}:',
	'createfilter' => 'إنشاء فلتر',
	'sd_createfilter_name' => 'الاسم:',
	'sd_createfilter_property' => 'الخاصية التى يغطيها هذا الفلتر:',
	'sd_createfilter_usepropertyvalues' => 'استخدم كل قيم هذه الخاصية للفلتر',
	'sd_createfilter_usecategoryvalues' => 'احصل على القيم للفلتر من هذا التصنيف:',
	'sd_createfilter_requirefilter' => 'يتطلب اختيار فلتر آخر قبل أن يتم عرض هذا:',
	'sd_createfilter_label' => 'علامة لهذا الفلتر (اختياري):',
	'sd_blank_error' => 'لا يمكن أن يكون فارغا',
	'sd_filter_coversproperty' => 'هذا الفلتر يغطى الخاصية $1.',
	'sd_filter_getsvaluesfromcategory' => 'يحصل على قيمه من التصنيف $1.',
	'sd_filter_requiresfilter' => 'يتطلب وجود الفلتر $1.',
	'sd_filter_haslabel' => 'يمتلك العلامة $1.',
);

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'semanticdrilldown-desc' => "Interfaz de ''drilldown'' pa navegar pelos datos semánticos.",
	'specialpages-group-sd_group' => 'Semantic Drilldown',
	'browsedata' => 'Navegar pelos datos',
	'sd_browsedata_choosecategory' => 'Escoyer una categoría',
	'sd_browsedata_viewcategory' => 'ver categoría',
	'sd_browsedata_docu' => "Calca nún o más elementos d'abaxo p'acotar los resultaos.",
	'sd_browsedata_subcategory' => 'Subcategoría',
	'sd_browsedata_other' => 'Otru',
	'sd_browsedata_none' => 'Dengún',
	'sd_browsedata_filterbyvalue' => 'Peñerar por esti valor',
	'sd_browsedata_filterbysubcategory' => 'Peñerar por esta subcategoría',
	'sd_browsedata_otherfilter' => 'Amosar páxines con otru valor pa esta peñera',
	'sd_browsedata_nonefilter' => 'Amosar páxines con dengún valor pa esta peñera',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Desaniciar esta peñera',
	'sd_browsedata_removesubcategoryfilter' => 'Desaniciar esta peñera de subcategoría',
	'sd_browsedata_resetfilters' => 'Reestablecer peñeres',
	'sd_browsedata_addanothervalue' => "Calca na flecha p'amestar otru valor",
	'sd_browsedata_daterangestart' => 'Aniciu:',
	'sd_browsedata_daterangeend' => 'Fin:',
	'sd_browsedata_novalues' => 'Nun hai valores pa esta peñera',
	'filters' => 'Peñeres',
	'sd_filters_docu' => 'En {{SITENAME}} esisten les siguientes peñeres:',
	'sd_formcreate' => 'Crear con formulariu',
	'sd_viewform' => 'Ver formulariu',
	'createfilter' => 'Crear una peñera',
	'sd-createfilter-with-name' => 'Crear peñera: $1',
	'sd_createfilter_name' => 'Nome:',
	'sd_createfilter_property' => 'Propiedá que cubre esta peñera:',
	'sd_createfilter_usepropertyvalues' => "Usar tolos valores d'esta propiedá pa la peñera",
	'sd_createfilter_usecategoryvalues' => "Sacar los valores pa la peñera d'esta categoría:",
	'sd_createfilter_requirefilter' => "Otra peñera que se tien de seleicionar enantes d'amosar esta:",
	'sd_createfilter_label' => 'Etiqueta pa esta peñera (opcional):',
	'sd_blank_error' => 'nun pue tar balero',
	'sd-pageschemas-filter' => 'Peñera',
	'sd-pageschemas-values' => 'Valores',
	'sd_filter_coversproperty' => 'Esta peñera cubre la propiedá $1.',
	'sd_filter_getsvaluesfromcategory' => 'Saca los valores de la categoría $1.',
	'sd_filter_requiresfilter' => 'Se requier la presencia de la peñera $1.',
	'sd_filter_haslabel' => 'Tien la etiqueta $1.',
);

/** Kotava (Kotava)
 * @author Wikimistusik
 */
$messages['avk'] = array(
	'browsedata' => 'Origwigara',
	'sd_browsedata_choosecategory' => 'Kiblara va loma',
	'sd_browsedata_viewcategory' => 'Wira va loma',
	'sd_browsedata_subcategory' => 'Volveyloma',
	'sd_browsedata_other' => 'Ar',
	'sd_browsedata_none' => 'Mek',
	'sd_browsedata_filterbyvalue' => 'Espara kan bata voda',
	'sd_browsedata_filterbysubcategory' => 'Espara kan bata volveyloma',
	'sd_browsedata_otherfilter' => 'Nedira va bueem vadjes va ara esparavoda',
	'sd_browsedata_nonefilter' => 'Nedira va bueem mevadjes va bata espara',
	'sd_browsedata_removefilter' => 'Tioltera va bata espara',
	'sd_browsedata_removesubcategoryfilter' => 'Tioltera va bata volveylomafa espara',
	'sd_browsedata_resetfilters' => 'Dimplekura va espara',
	'filters' => 'Espasikieem',
	'sd_filters_docu' => 'Bata espara se tid in {{SITENAME}} :',
	'createfilter' => 'Redura va espara',
	'sd_createfilter_name' => 'Yolt :',
	'sd_createfilter_property' => 'Pilkaca espanon skuna :',
	'sd_createfilter_usepropertyvalues' => 'Favera va vodeem ke bata esparapilkaca',
	'sd_createfilter_usecategoryvalues' => 'Plekura va esparavoda mal bata loma :',
	'sd_createfilter_label' => 'Kral tori bata espara (rotisa) :',
	'sd_blank_error' => 'me rotir vlardafa',
	'sd_filter_coversproperty' => 'Bata espara va $1 pilkaca skur.',
	'sd_filter_getsvaluesfromcategory' => 'Mal $1 loma in va voda plekur.',
	'sd_filter_requiresfilter' => 'Batcoba va tira ke $1 espasiki kucilar.',
	'sd_filter_haslabel' => 'In tir dem $1 kral.',
);

/** Azerbaijani (azərbaycanca)
 * @author Cekli829
 */
$messages['az'] = array(
	'sd_browsedata_other' => 'Digər',
	'sd_browsedata_none' => 'Heç biri',
	'sd_browsedata_daterangestart' => 'Başla:',
	'sd_browsedata_daterangeend' => 'Son:',
	'sd_createfilter_name' => 'Ad:',
);

/** Belarusian (беларуская)
 * @author Тест
 */
$messages['be'] = array(
	'filters' => 'Фільтры',
);

/** Belarusian (Taraškievica orthography) (беларуская (тарашкевіца)‎)
 * @author EugeneZelenko
 * @author Jim-by
 */
$messages['be-tarask'] = array(
	'semanticdrilldown-desc' => 'Інтэрфэйс для навігацыі па сэмантычным зьвесткам',
	'specialpages-group-sd_group' => 'Сэмантычная навігацыя',
	'browsedata' => 'Прагляд зьвестак',
	'sd_browsedata_choosecategory' => 'Выберыце катэгорыю',
	'sd_browsedata_viewcategory' => 'паказаць катэгорыю',
	'sd_browsedata_docu' => 'Пазначце адзін ці больш элемэнтаў для абмежаваньня Вашых вынікаў.',
	'sd_browsedata_subcategory' => 'Падкатэгорыя',
	'sd_browsedata_other' => 'Іншыя',
	'sd_browsedata_none' => 'Не',
	'sd_browsedata_filterbyvalue' => 'Фільтраваць па гэтаму значэньню',
	'sd_browsedata_filterbysubcategory' => 'Фільтраваць па гэтай падкатэгорыі',
	'sd_browsedata_otherfilter' => 'Паказваць старонкі зь іншымі значэньнямі па гэтаму фільтру',
	'sd_browsedata_nonefilter' => 'Паказваць старонкі без значэньняў па гэтаму фільтру',
	'sd_browsedata_or' => 'ці',
	'sd_browsedata_removefilter' => 'Выдаліць гэты фільтар',
	'sd_browsedata_removesubcategoryfilter' => 'Выдаліць гэты фільтар падкатэгорыі',
	'sd_browsedata_resetfilters' => 'Ачысьціць фільтры',
	'sd_browsedata_addanothervalue' => 'Націсьніце стрэлку каб дадаць іншае значэньне',
	'sd_browsedata_daterangestart' => 'Пачатак:',
	'sd_browsedata_daterangeend' => 'Канец:',
	'sd_browsedata_novalues' => 'Няма значэньняў для гэтага фільтру',
	'filters' => 'Фільтры',
	'sd_filters_docu' => 'У {{GRAMMAR:месны|{{SITENAME}}}} існуюць наступныя фільтры:',
	'createfilter' => 'Стварыць фільтар',
	'sd_createfilter_name' => 'Назва:',
	'sd_createfilter_property' => 'Уласьцівасьць, якую пакрывае гэты фільтар:',
	'sd_createfilter_usepropertyvalues' => 'Выкарыстоўваць усе значэньні гэтай ўласьцівасьці для фільтру',
	'sd_createfilter_usecategoryvalues' => 'Атрымаць значэньні для фільтру з гэтай катэгорыі:',
	'sd_createfilter_requirefilter' => 'Патрабуецца выбар іншага фільтру перад тым, як будзе паказаны гэты:',
	'sd_createfilter_label' => 'Метка для гэтага фільтру (неабавязкова):',
	'sd_blank_error' => 'ня можа быць незапоўненым',
	'sd_filter_coversproperty' => 'Гэты фільтар хавае ўласьцівасьць $1.',
	'sd_filter_getsvaluesfromcategory' => 'Атрымлівае свае значэньні з катэгорыі $1.',
	'sd_filter_requiresfilter' => 'Патрабуе наяўнасьць фільтру $1.',
	'sd_filter_haslabel' => 'Мае метку $1.',
);

/** Bulgarian (български)
 * @author DCLXVI
 * @author පසිඳු කාවින්ද
 */
$messages['bg'] = array(
	'browsedata' => 'Разглеждане на данните',
	'sd_browsedata_choosecategory' => 'Избор на категория',
	'sd_browsedata_viewcategory' => 'преглед на категорията',
	'sd_browsedata_subcategory' => 'Подкатегория',
	'sd_browsedata_other' => 'Други',
	'sd_browsedata_none' => 'Няма',
	'sd_browsedata_filterbyvalue' => 'Филтриране по тази стойност',
	'sd_browsedata_filterbysubcategory' => 'Филтриране по тази подкатегория',
	'sd_browsedata_otherfilter' => 'Показване на страниците с други стойности за този филтър',
	'sd_browsedata_nonefilter' => 'Показване на страниците без стойности за този филтър',
	'sd_browsedata_removefilter' => 'Премахване на филтъра',
	'sd_browsedata_removesubcategoryfilter' => 'Премахване на филтъра за подкатегория',
	'sd_browsedata_resetfilters' => 'Изчистване на филтрите',
	'sd_browsedata_addanothervalue' => 'Добавяне на друга стойност', # Fuzzy
	'sd_browsedata_daterangeend' => 'Край:',
	'filters' => 'Филтри',
	'sd_filters_docu' => 'В {{SITENAME}} съществуват следните филтри:',
	'createfilter' => 'Създаване на филтър',
	'sd_createfilter_name' => 'Име:',
	'sd_createfilter_requirefilter' => 'Изисква се да бъде избран друг филтър преди да може този да бъде показан:',
	'sd_createfilter_label' => 'Заглавие за този филтър (незадължително):',
	'sd_blank_error' => 'не може да бъде празно',
	'sd-pageschemas-filter' => 'Филтър',
	'sd_filter_requiresfilter' => 'Необходимо е наличието на филтър $1.',
);

/** Bengali (বাংলা)
 * @author Wikitanvir
 */
$messages['bn'] = array(
	'browsedata' => 'উপাত্ত পরিদর্শন',
	'sd_browsedata_choosecategory' => 'একটি বিষয়শ্রেণী পছন্দ করুন',
	'sd_browsedata_viewcategory' => 'বিষয়শ্রেণী প্রদর্শন করো',
	'sd_browsedata_subcategory' => 'উপবিষয়শ্রেণী',
	'sd_browsedata_other' => 'অন্যান্য',
	'sd_browsedata_none' => 'কোনটিই নয়',
	'sd_browsedata_filterbyvalue' => 'এই মান অনুসারে ফিল্টার করো',
	'sd_browsedata_filterbysubcategory' => 'এই উপবিষয়শ্রেণী অনুসারে ফিল্টার করো',
	'sd_browsedata_or' => 'অথবা',
	'sd_browsedata_removefilter' => 'এই ফিল্টারটি অপসারণ করো',
	'sd_browsedata_addanothervalue' => 'আরেকটি মান প্রবেশ করাতে তীর চিহ্নে ক্লিক করুন',
	'sd_browsedata_daterangestart' => 'শুরু:',
	'sd_browsedata_daterangeend' => 'শেষ:',
	'sd_browsedata_novalues' => 'এই ফিল্টারের জন্য কোনো মান নেই',
	'filters' => 'ফিল্টার',
	'sd_filters_docu' => '{{SITENAME}} সাইটে নিচের ফিল্টারগুলো রয়েছে:',
	'createfilter' => 'নতুন ফিল্টার তৈরি করুন',
	'sd_createfilter_name' => 'নাম:',
);

/** Breton (brezhoneg)
 * @author Fohanno
 * @author Fulup
 * @author Y-M D
 */
$messages['br'] = array(
	'semanticdrilldown-desc' => 'Un etrefas poelladennoù evit merdeiñ dre roadennoù ereadurel',
	'specialpages-group-sd_group' => 'Poelladenn semantek',
	'browsedata' => 'Furchal ar roadennoù',
	'sd_browsedata_choosecategory' => 'Dibab ur rummad',
	'sd_browsedata_viewcategory' => 'gwelet ar rummad',
	'sd_browsedata_docu' => "Klikit war unan pe meur a elfenn da-heul evit resisaat an disoc'hoù.",
	'sd_browsedata_subcategory' => 'Isrummad',
	'sd_browsedata_other' => 'Unan all',
	'sd_browsedata_none' => 'Hini ebet',
	'sd_browsedata_filterbyvalue' => 'Silañ war-bouez an talvoud-mañ',
	'sd_browsedata_filterbysubcategory' => 'Silañ gant an isrummad-mañ',
	'sd_browsedata_otherfilter' => 'Gwelet ar bajennoù gant un talvoud all evit ar sil-mañ',
	'sd_browsedata_nonefilter' => 'Gwelet ar pajennoù gant talvoud ebet evit ar sil-mañ',
	'sd_browsedata_or' => 'pe',
	'sd_browsedata_removefilter' => 'Lemel ar sil-mañ',
	'sd_browsedata_removesubcategoryfilter' => 'Tennañ an is-rummad a siloù',
	'sd_browsedata_resetfilters' => 'Adderaouekaat ar siloù',
	'sd_browsedata_addanothervalue' => 'Klikit war ar bir da ouzhpennañ un talvoud all',
	'sd_browsedata_daterangestart' => 'Penn kentañ :',
	'sd_browsedata_daterangeend' => 'Dibenn :',
	'sd_browsedata_novalues' => "N'eus talvoud ebet evit ar sil-mañ",
	'filters' => 'Siloù',
	'sd_filters_docu' => 'Bez ez eus eus ar siloù-mañ war {{SITENAME}} :',
	'createfilter' => 'Krouiñ ur sil',
	'sd-createfilter-with-name' => 'Krouiñ ar sil : $1',
	'sd_createfilter_name' => 'Anv :',
	'sd_createfilter_property' => "Perc'henniezh a vo goloet gant ar sil-mañ :",
	'sd_createfilter_usepropertyvalues' => "Implijout holl talvoudoù ar berc'henniezh evit ar sil-mañ",
	'sd_createfilter_usecategoryvalues' => 'Kaout an talvoudoù evit ar sil adalek ar rummad-mañ :',
	'sd_createfilter_requirefilter' => 'Goulenn ma vo diuzet ur sil all a-raok na zeufe hemañ war wel :',
	'sd_createfilter_label' => 'Tiketenn evit ar sil-mañ (diret) :',
	'sd_blank_error' => "n'hall ket chom goullo",
	'sd-pageschemas-filter' => 'Sil',
	'sd-pageschemas-values' => 'Talvoudoù',
	'sd_filter_coversproperty' => "Ar sil-mañ a ra war-dro ar perc'henniezh $1.",
	'sd_filter_getsvaluesfromcategory' => 'E dalvoudoù en deus eus ar rummad $1.',
	'sd_filter_requiresfilter' => 'Ezhomm en deus eus bezañs ar sil $1.',
	'sd_filter_haslabel' => 'An tikedenn $1 en deus.',
);

/** Bosnian (bosanski)
 * @author CERminator
 */
$messages['bs'] = array(
	'semanticdrilldown-desc' => 'Interfejs za postupnu navigaciju kroz semantičke podatke',
	'specialpages-group-sd_group' => 'Semantičko istančavanje',
	'browsedata' => 'Pregledaj podatke',
	'sd_browsedata_choosecategory' => 'Izaberi kategoriju',
	'sd_browsedata_viewcategory' => 'pogledaj kategoriju',
	'sd_browsedata_docu' => 'Kliknite na jednu ili više stavki ispod za sužavanje vaših rezultata.',
	'sd_browsedata_subcategory' => 'Podkategorija',
	'sd_browsedata_other' => 'Ostalo',
	'sd_browsedata_none' => 'Ništa',
	'sd_browsedata_filterbyvalue' => 'Filter po ovoj vrijednosti',
	'sd_browsedata_filterbysubcategory' => 'Filter po ovoj podkategoriji',
	'sd_browsedata_otherfilter' => 'Prikaži stranice sa drugom vrijednošću za ovaj filter',
	'sd_browsedata_nonefilter' => 'Pokaži stranice bez vrijednosti za ovaj filter',
	'sd_browsedata_or' => 'ili',
	'sd_browsedata_removefilter' => 'Ukloni ovaj filter',
	'sd_browsedata_removesubcategoryfilter' => 'Ukloni ovaj filter podkategorije',
	'sd_browsedata_resetfilters' => 'Resetuj filtere',
	'sd_browsedata_addanothervalue' => 'Klikni na strelicu za dodavanje druge vrijednosti',
	'sd_browsedata_daterangestart' => 'Početak:',
	'sd_browsedata_daterangeend' => 'Kraj:',
	'sd_browsedata_novalues' => 'Nema vrijednosti za ovaj filter',
	'filters' => 'Filteri',
	'sd_filters_docu' => 'Na {{SITENAME}} postoje slijedeći filteri:',
	'sd_formcreate' => 'Napravi sa obrascem',
	'sd_viewform' => 'Pogledaj obrazac',
	'createfilter' => 'Napravi filter',
	'sd_createfilter_name' => 'Ime:',
	'sd_createfilter_property' => 'Svojstvo koje ovaj filter pokriva:',
	'sd_createfilter_usepropertyvalues' => 'Koristi sve vrijednosti ovog svojstva za filter',
	'sd_createfilter_usecategoryvalues' => 'Preuzmi vrijednosti za filter iz ove kategorije:',
	'sd_createfilter_requirefilter' => 'Zahtijeva drugi filter da bude odabran prije nego se ovaj prikaže:',
	'sd_createfilter_label' => 'Naslov za ovaj filter (opcija):',
	'sd_blank_error' => 'ne može biti prazno',
	'sd_filter_coversproperty' => 'Ovaj filter pokriva svojstvo $1.',
	'sd_filter_getsvaluesfromcategory' => 'Uzima svoje vrijednosti iz kategorije $1.',
	'sd_filter_requiresfilter' => 'Zahtjeva prisustvo filtera $1.',
	'sd_filter_haslabel' => 'Ima oznaku $1.',
);

/** Catalan (català)
 * @author Dvdgmz
 * @author Jordi Roqué
 * @author Paucabot
 * @author SMP
 * @author Solde
 * @author Toniher
 */
$messages['ca'] = array(
	'semanticdrilldown-desc' => "Una interfície de ''drilldown'' per navegar a través de la informació semàntica",
	'specialpages-group-sd_group' => 'Semantic Drilldown',
	'browsedata' => 'Explorar dades',
	'sd_browsedata_choosecategory' => 'Esculli una categoria',
	'sd_browsedata_viewcategory' => 'veure la categoria',
	'sd_browsedata_docu' => 'Clica un o més ítems aquí sota per acotar els teus resultats.',
	'sd_browsedata_subcategory' => 'Subcategoria',
	'sd_browsedata_other' => 'Un altre',
	'sd_browsedata_none' => 'Cap',
	'sd_browsedata_filterbyvalue' => 'Filtra per aquest valor',
	'sd_browsedata_filterbysubcategory' => 'Filtra amb aquesta subcategoria',
	'sd_browsedata_otherfilter' => 'Mostra pàgines amb un altre valor per aquest filtre',
	'sd_browsedata_nonefilter' => 'Mostra les pàgines que no tenen cap valor per aquest filtre',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Elimina aquest filtre',
	'sd_browsedata_removesubcategoryfilter' => 'Elimina aquest filtre de subcategoria',
	'sd_browsedata_resetfilters' => 'Restaura filtres',
	'sd_browsedata_addanothervalue' => 'Feu clic sobre la fletxa per afegir un altre valor',
	'sd_browsedata_daterangestart' => 'Inici:',
	'sd_browsedata_daterangeend' => 'Fi:',
	'sd_browsedata_novalues' => "No s'han trobat valors per aquest filtre",
	'filters' => 'Filtres',
	'sd_filters_docu' => 'A {{SITENAME}} hi ha els filtres següents:',
	'sd_formcreate' => 'Crea amb formulari',
	'sd_viewform' => 'Visualitza el formulari',
	'createfilter' => 'Crea un filtre',
	'sd_createfilter_name' => 'Nom:',
	'sd_createfilter_property' => 'Propietat que cobreix aquest filtre:',
	'sd_createfilter_usepropertyvalues' => "Utilitza tots els valors d'aquesta propietat per el filtre",
	'sd_createfilter_usecategoryvalues' => "Pren els valors pel filtre d'aquesta categoria:",
	'sd_createfilter_requirefilter' => 'Cal seleccionar un altre filtre abans de mostrar aquest:',
	'sd_createfilter_label' => 'Rètol per aquest filtre (opcional):',
	'sd_blank_error' => 'no es pot deixar buit',
	'sd-pageschemas-filter' => 'Filtre',
	'sd-pageschemas-values' => 'Valors',
	'sd_filter_coversproperty' => 'Aquest filtre cobreix la propietat $1',
	'sd_filter_getsvaluesfromcategory' => 'Pren els seus valors de la categoria $1',
	'sd_filter_requiresfilter' => 'Es requereix la presencia del filtre $1',
	'sd_filter_haslabel' => 'Té el ròtul $1.',
);

/** Chechen (нохчийн)
 * @author Sasan700
 * @author Умар
 */
$messages['ce'] = array(
	'browsedata' => 'Хаамашка хьажар',
	'sd_browsedata_other' => 'Кхин',
	'sd_browsedata_none' => 'Яц',
	'sd_browsedata_otherfilter' => 'Гайта хӀокху литтарца кхин маьӀна долу агӀонаш',
	'createfilter' => 'Кхолла литтар',
);

/** Czech (čeština)
 * @author Juan de Vojníkov
 * @author Juandev
 * @author Vks
 */
$messages['cs'] = array(
	'sd_browsedata_subcategory' => 'Podkategorie',
	'sd_browsedata_other' => 'Jiné',
	'sd_browsedata_none' => 'Nic',
	'sd_browsedata_removefilter' => 'Odstanit tento filtr',
	'sd_browsedata_daterangeend' => 'Konec:',
	'sd_createfilter_name' => 'Jméno:',
	'sd-pageschemas-filter' => 'Filtr',
);

/** Church Slavic (словѣньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ)
 * @author ОйЛ
 */
$messages['cu'] = array(
	'sd_createfilter_name' => 'имѧ :',
);

/** Welsh (Cymraeg)
 * @author Lloffiwr
 */
$messages['cy'] = array(
	'browsedata' => "Pori'r data",
	'sd_browsedata_choosecategory' => 'Dewis categori',
	'sd_browsedata_viewcategory' => 'gweld y categori',
	'sd_browsedata_docu' => "Clicio ar un eitem neu ragor isod i gyfyngu'r canlyniadau.",
	'sd_browsedata_subcategory' => 'Isgategori',
	'sd_browsedata_other' => 'Arall',
	'sd_browsedata_none' => 'Dim',
	'sd_browsedata_filterbyvalue' => 'Hidlo yn ôl y gwerth hwn',
	'sd_browsedata_filterbysubcategory' => 'Hidlo yn ôl yr isgategori hwn',
	'sd_browsedata_otherfilter' => "Dangos tudalennau gyda gwerth arall i'r hidl hwn",
	'sd_browsedata_nonefilter' => "Dangos tudalennau heb unrhyw werth i'r hidl hwn",
	'sd_browsedata_or' => 'neu',
	'sd_browsedata_removefilter' => "Tynnu'r hidl hwn i ffwrdd",
	'sd_browsedata_removesubcategoryfilter' => "Tynnu'r hidl i'r isgategori hwn i ffwrdd",
	'sd_browsedata_resetfilters' => 'Ailosod yr hidlau',
	'sd_browsedata_addanothervalue' => "Clicio'r saeth i ychwanegu gwerth arall",
	'sd_browsedata_daterangestart' => 'Dechrau:',
	'sd_browsedata_daterangeend' => 'Diwedd:',
	'filters' => 'Hidlau',
	'sd_viewform' => 'Gweld y ffurflen',
	'createfilter' => 'Gwneud hidl',
	'sd-createfilter-with-name' => 'Gwneud yr hidl: $1',
	'sd_createfilter_name' => 'Enw:',
	'sd-pageschemas-filter' => 'Hidl',
	'sd-pageschemas-values' => 'Gwerthoedd',
	'sd_filter_haslabel' => "Mae'r label $1 ganddo.",
);

/** Danish (dansk)
 * @author Jon Harald Søby
 */
$messages['da'] = array(
	'sd_browsedata_none' => 'Ingen',
	'sd_createfilter_name' => 'Navn:',
);

/** German (Deutsch)
 * @author ChrisiPK
 * @author DaSch
 * @author Kghbln
 * @author Krabina
 * @author Lyzzy
 * @author Melancholie
 * @author Metalhead64
 * @author MichaelFrey
 * @author MovGP0
 * @author Purodha
 * @author Revolus
 * @author Umherirrender
 */
$messages['de'] = array(
	'semanticdrilldown-desc' => 'Ermöglicht eine Benutzerschnittstelle für eine gestufte Navigation durch semantische Daten',
	'specialpages-group-sd_group' => 'Semantische gestufte Navigation',
	'browsedata' => 'Daten ansehen',
	'sd_browsedata_choosecategory' => 'Kategorie auswählen',
	'sd_browsedata_viewcategory' => 'Kategorie ansehen',
	'sd_browsedata_docu' => 'Klick auf einen oder mehrere der Filter, um das Ergebnis einzuschränken.',
	'sd_browsedata_subcategory' => 'Unterkategorie',
	'sd_browsedata_other' => 'Anderes',
	'sd_browsedata_none' => 'Nichts',
	'sd_browsedata_filterbyvalue' => 'Über diesen Wert filtern',
	'sd_browsedata_filterbysubcategory' => 'Filtere über diese Unterkategorie',
	'sd_browsedata_otherfilter' => 'Zeige Seiten mit einem anderen Wert für diesen Filter',
	'sd_browsedata_nonefilter' => 'Zeige Seiten mit keinem Wert für diesen Filter',
	'sd_browsedata_or' => 'oder',
	'sd_browsedata_removefilter' => 'Diesen Filter entfernen',
	'sd_browsedata_removesubcategoryfilter' => 'Diesen Unterkategoriefilter entfernen',
	'sd_browsedata_resetfilters' => 'Filter zurücksetzen',
	'sd_browsedata_addanothervalue' => 'Klicke auf den Pfeil, um einen weiteren Wert hinzuzufügen.',
	'sd_browsedata_daterangestart' => 'Anfang:',
	'sd_browsedata_daterangeend' => 'Ende:',
	'sd_browsedata_novalues' => 'Es sind keine Werte für diesen Filter vorhanden.',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Die folgenden Filter sind in diesem Wiki vorhanden:',
	'sd_formcreate' => 'Mit Formular erstellen',
	'sd_viewform' => 'Formular anzeigen',
	'createfilter' => 'Einen Filter erstellen',
	'sd-createfilter-with-name' => 'Filter erstellen: $1',
	'sd_createfilter_name' => 'Name:',
	'sd_createfilter_property' => 'Attribut für diesen Filter:',
	'sd_createfilter_usepropertyvalues' => 'Verwende alle Werte dieses Attributs für den Filter.',
	'sd_createfilter_usecategoryvalues' => 'Verwende die Werte dieser Kategorie für den Filter:',
	'sd_createfilter_requirefilter' => 'Bevor dieser Filter angezeigt werden kann, muss folgender anderer Filter gesetzt sein:',
	'sd_createfilter_label' => 'Bezeichnung für diesen Filter (optional):',
	'sd_blank_error' => 'darf nicht leer sein',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Werte',
	'sd_filter_coversproperty' => 'Dieser Filter filtert nach dem Attribut $1.',
	'sd_filter_getsvaluesfromcategory' => 'Er erhält seine Werte aus der Kategorie $1.',
	'sd_filter_requiresfilter' => 'Setzt den Filter $1 voraus.',
	'sd_filter_haslabel' => 'Er hat die Bezeichnung $1.',
);

/** German (formal address) (Deutsch (Sie-Form)‎)
 * @author Dst
 * @author Imre
 * @author Kghbln
 */
$messages['de-formal'] = array(
	'sd_browsedata_docu' => 'Klicken Sie auf einen oder mehrere der Filter, um das Ergebnis einzuschränken.',
	'sd_browsedata_addanothervalue' => 'Klicken Sie auf den Pfeil, um einen weiteren Wert hinzuzufügen.',
);

/** Zazaki (Zazaki)
 * @author Belekvor
 * @author Erdemaslancan
 * @author Mirzali
 */
$messages['diq'] = array(
	'sd_browsedata_none' => 'çino',
	'sd_browsedata_or' => 'ya zi',
	'filters' => 'Avrêci',
	'sd_createfilter_name' => 'Name:',
	'sd-pageschemas-filter' => 'Avrêc',
	'sd-pageschemas-values' => 'Erci',
);

/** Lower Sorbian (dolnoserbski)
 * @author Michawiki
 */
$messages['dsb'] = array(
	'semanticdrilldown-desc' => 'Interfejs za nawigaciju pśez semantiske daty',
	'specialpages-group-sd_group' => 'Semantiska nawigacija',
	'browsedata' => 'Daty se woglědaś',
	'sd_browsedata_choosecategory' => 'Kategoriju wubraś',
	'sd_browsedata_viewcategory' => 'kategoriju se woglědaś',
	'sd_browsedata_docu' => 'Klikni na jaden zapisk abo někotare zapiski, aby zwuscył swóje wuslědki.',
	'sd_browsedata_subcategory' => 'Pódkategorija',
	'sd_browsedata_other' => 'Druge',
	'sd_browsedata_none' => 'Žedne',
	'sd_browsedata_filterbyvalue' => 'Pó toś tej gódnośe filtrowaś',
	'sd_browsedata_filterbysubcategory' => 'Pó toś tej pódkategoriji filtrowaś',
	'sd_browsedata_otherfilter' => 'Boki z drugeju gódnotu za toś ten filter pokazaś',
	'sd_browsedata_nonefilter' => 'Boki bźez gódnoty za toś ten filter pokazaś',
	'sd_browsedata_or' => 'abo',
	'sd_browsedata_removefilter' => 'Toś ten filter wótpóraś',
	'sd_browsedata_removesubcategoryfilter' => 'Toś ten filter za pódkategorije wótpóraś',
	'sd_browsedata_resetfilters' => 'Filter slědk stajiś',
	'sd_browsedata_addanothervalue' => 'Na šypku kliknuś, aby se druga gódnota pśidał',
	'sd_browsedata_daterangestart' => 'Zachopjeńk:',
	'sd_browsedata_daterangeend' => 'Kóńc:',
	'sd_browsedata_novalues' => 'Za tós ten filter gódnoty njejsu',
	'filters' => 'Filtry',
	'sd_filters_docu' => 'Slědujuce filtry eksistěruju w {{GRAMMAR:lokatiw|{{SITENAME}}}}:',
	'sd_formcreate' => 'Z formularom napóraś',
	'sd_viewform' => 'Formular se woglědaś',
	'createfilter' => 'Filter napóraś',
	'sd-createfilter-with-name' => 'Filter napóraś: $1',
	'sd_createfilter_name' => 'Mě:',
	'sd_createfilter_property' => 'Kakosć, kótaruž toś ten filter wopśimujo:',
	'sd_createfilter_usepropertyvalues' => 'Wše gódnoty toś teje kakosći za filter wužywaś',
	'sd_createfilter_usecategoryvalues' => 'Gódnoty za filter z toś teje kategorije wobstaraś:',
	'sd_createfilter_requirefilter' => 'Nježli toś ten filter dajo se zwobrazniś, musyš drugi filter wubraś:',
	'sd_createfilter_label' => 'Pomjenjenje za toś ten filter (opcionalny):',
	'sd_blank_error' => 'njesmějo prozny byś',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Gódnoty',
	'sd_filter_coversproperty' => 'Toś ten filter wopśimujo kakosć $1.',
	'sd_filter_getsvaluesfromcategory' => 'Dostawa swóje gódnoty z kategorije $1.',
	'sd_filter_requiresfilter' => 'Filter $1 musy eksistěrowaś.',
	'sd_filter_haslabel' => 'Ma pomjenjenje $1.',
);

/** Greek (Ελληνικά)
 * @author Consta
 * @author Crazymadlover
 * @author Omnipaedista
 * @author Protnet
 * @author ZaDiak
 */
$messages['el'] = array(
	'browsedata' => 'Δεδομένα πλοήγησης',
	'sd_browsedata_choosecategory' => 'Επιλέξτε μια κατηγορία',
	'sd_browsedata_viewcategory' => 'προβολή κατηγορίας',
	'sd_browsedata_subcategory' => 'Υποκατηγορία',
	'sd_browsedata_other' => 'Άλλος',
	'sd_browsedata_none' => 'Κανένα',
	'sd_browsedata_filterbyvalue' => 'Φιλτράρισμα βάσει αυτής της αξίας',
	'sd_browsedata_filterbysubcategory' => 'Φιλτράρισμα βάσει αυτής της υποκατηγορίας',
	'sd_browsedata_or' => 'ή',
	'sd_browsedata_removefilter' => 'ΑΦαίρεσυ αυτού του φίλτρου',
	'sd_browsedata_resetfilters' => 'Επαναφορά φίλτρων',
	'sd_browsedata_addanothervalue' => 'Κάνετε κλικ στο τόξο για την προσθήκη και άλλης αξίας',
	'sd_browsedata_daterangestart' => 'Έναρξη:',
	'sd_browsedata_daterangeend' => 'Λήξη:',
	'filters' => 'Φίλτρα',
	'sd_formcreate' => 'Δημιουργία με φόρμα',
	'sd_viewform' => 'Εμφάνιση φόρμας',
	'createfilter' => 'Δημιουργία ενός φίλτρου',
	'sd_createfilter_name' => 'Όνομα:',
	'sd_blank_error' => 'δεν μπορεί να είναι κενό',
	'sd_filter_requiresfilter' => 'Απαιτεί τη παρουσία του φίλτρου $1.',
	'sd_filter_haslabel' => 'Έχει την ετικέτα $1.',
);

/** Esperanto (Esperanto)
 * @author Melancholie
 * @author Michawiki
 * @author Yekrats
 */
$messages['eo'] = array(
	'browsedata' => 'Rigardu datenojn',
	'sd_browsedata_choosecategory' => 'Elektu kategorion',
	'sd_browsedata_viewcategory' => 'rigardu kategorion',
	'sd_browsedata_subcategory' => 'Subkategorio',
	'sd_browsedata_other' => 'Alia',
	'sd_browsedata_none' => 'Neniu',
	'sd_browsedata_filterbyvalue' => 'Filtru laŭ ĉi tiu valoro',
	'sd_browsedata_filterbysubcategory' => 'Filtru laŭ ĉi tiu subkategorio',
	'sd_browsedata_otherfilter' => 'Montru paĝojn kun alia valoro por ĉi tiu filtrilo',
	'sd_browsedata_nonefilter' => 'Montru paĝojn kun neniu valoro por ĉi tiu filtrilo',
	'sd_browsedata_or' => 'aŭ',
	'sd_browsedata_removefilter' => 'Forigu filtrilon',
	'sd_browsedata_removesubcategoryfilter' => 'Forigu ĉi tiun subkategorian filtrilon',
	'sd_browsedata_resetfilters' => 'Restarigu filtrilojn',
	'sd_browsedata_addanothervalue' => 'Alklaku sagon por aldoni plian valoron',
	'sd_browsedata_daterangestart' => 'Ekde:',
	'sd_browsedata_daterangeend' => 'Al:',
	'sd_browsedata_novalues' => 'Estas neniuj valoroj por ĉi tiu filtrilo',
	'filters' => 'Filtriloj',
	'sd_filters_docu' => 'La jenaj filtriloj ekzistas en {{SITENAME}}:',
	'sd_formcreate' => 'Krei per formularo',
	'sd_viewform' => 'Rigardi kamparon',
	'createfilter' => 'Kreu filtrilon',
	'sd_createfilter_name' => 'Nomo:',
	'sd_createfilter_property' => 'Eco kovrita de ĉi tiu filtrilo:',
	'sd_createfilter_usepropertyvalues' => 'Uzu ĉiujn valorojn de ĉi tiu atributo por la filtrilo',
	'sd_createfilter_usecategoryvalues' => 'Akiru valorojn por filtrilo de ĉi tiu kategorio:',
	'sd_createfilter_requirefilter' => 'Devigu alian filtrilon esti selektita antaŭ ĉi tiu estas montrita:',
	'sd_createfilter_label' => 'Etikedo por ĉi tiu filtrilo (nedeviga):',
	'sd_blank_error' => 'ne povas esti malplena',
	'sd_filter_coversproperty' => 'Ĉi tiu filtrilo kovras la econ $1.',
	'sd_filter_getsvaluesfromcategory' => 'Ĝi akiras ties valorojn de la kategorio $1.',
	'sd_filter_requiresfilter' => 'Ĝi devigas la eston de la filtrilo $1.',
	'sd_filter_haslabel' => 'Ĝi havas etikedon $1.',
);

/** Spanish (español)
 * @author Armando-Martin
 * @author Bola
 * @author Crazymadlover
 * @author Dvdgmz
 * @author Fitoschido
 * @author Imre
 * @author Mor
 * @author Sanbec
 */
$messages['es'] = array(
	'semanticdrilldown-desc' => "Una interfaz de ''drilldown'' para navegar a través de los datos semánticos.",
	'specialpages-group-sd_group' => 'Semantic Drilldown',
	'browsedata' => 'Datos de navegación',
	'sd_browsedata_choosecategory' => 'Escoger una categoría',
	'sd_browsedata_viewcategory' => 'Ver categoría',
	'sd_browsedata_docu' => 'Haz click en uno o más items de abajo para precisar tus resultados.',
	'sd_browsedata_subcategory' => 'Subcategoría',
	'sd_browsedata_other' => 'Otro',
	'sd_browsedata_none' => 'Ninguno',
	'sd_browsedata_filterbyvalue' => 'Filtrar por este valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar por esta subcategoría',
	'sd_browsedata_otherfilter' => 'Mostrar páginas con otro valor para este filtro',
	'sd_browsedata_nonefilter' => 'Mostrar páginas sin valores para este filtro',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Quitar este filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Quitar este filtro de subcategoría',
	'sd_browsedata_resetfilters' => 'Restablecer filtros',
	'sd_browsedata_addanothervalue' => 'Haga click en la flecha para agregar otro valor',
	'sd_browsedata_daterangestart' => 'Inicio:',
	'sd_browsedata_daterangeend' => 'Fin:',
	'sd_browsedata_novalues' => 'No hay valores para este filtro',
	'filters' => 'Filtros',
	'sd_filters_docu' => 'Los siguientes filtros existen en {{SITENAME}}:',
	'sd_formcreate' => 'Crear con formulario',
	'sd_viewform' => 'Ver formulario',
	'createfilter' => 'Crear un filtro',
	'sd-createfilter-with-name' => 'Crear filtro: $1',
	'sd_createfilter_name' => 'Nombre:',
	'sd_createfilter_property' => 'Propiedad que este filtro cubre:',
	'sd_createfilter_usepropertyvalues' => 'Usar todos los valores de esta propiedad para el filtro',
	'sd_createfilter_usecategoryvalues' => 'Obtenga valores para el filtro desde esta categoría:',
	'sd_createfilter_requirefilter' => 'Requiere otro filtro a ser seleccionado antes que este sea mostrado:',
	'sd_createfilter_label' => 'Etiqueta para este filtro (opcional):',
	'sd_blank_error' => 'No puede estar en blanco',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valores',
	'sd_filter_coversproperty' => 'Este filtro cubre la propiedad $1.',
	'sd_filter_getsvaluesfromcategory' => 'Obtiene sus valores de la categoría $1.',
	'sd_filter_requiresfilter' => 'Requiere la presencia del filtro $1.',
	'sd_filter_haslabel' => 'Tiene la etiqueta $1.',
);

/** Estonian (eesti)
 * @author Avjoska
 * @author Pikne
 */
$messages['et'] = array(
	'sd_browsedata_choosecategory' => 'Vali kategooria',
	'sd_browsedata_viewcategory' => 'vaata kategooriat',
	'sd_browsedata_or' => 'või',
	'sd_browsedata_daterangestart' => 'Algus:',
	'sd_browsedata_daterangeend' => 'Lõpp:',
	'sd_createfilter_name' => 'Nimi:',
);

/** Basque (euskara)
 * @author An13sa
 * @author Kobazulo
 * @author පසිඳු කාවින්ද
 */
$messages['eu'] = array(
	'browsedata' => 'Datuak arakatu',
	'sd_browsedata_choosecategory' => 'Kategoria aukeratu',
	'sd_browsedata_viewcategory' => 'kategoria ikusi',
	'sd_browsedata_subcategory' => 'Azpikategoria',
	'sd_browsedata_other' => 'Bestelakoa',
	'sd_browsedata_none' => 'Bat ere ez',
	'sd_browsedata_or' => 'edo',
	'sd_browsedata_removefilter' => 'Iragazki hau kendu',
	'sd_browsedata_resetfilters' => 'Iragazkiak berrezarri',
	'sd_browsedata_addanothervalue' => 'Gezian klikatu beste balio bat gehitzeko',
	'sd_browsedata_daterangestart' => 'Hasiera:',
	'sd_browsedata_daterangeend' => 'Amaiera:',
	'filters' => 'Iragazkiak',
	'createfilter' => 'Iragazki bat sortu',
	'sd_createfilter_name' => 'Izena:',
	'sd-pageschemas-filter' => 'Iragazkia',
);

/** Persian (فارسی)
 * @author Ebraminio
 * @author Huji
 * @author Ibrahim
 * @author Mjbmr
 * @author Reza1615
 * @author Tofighi
 */
$messages['fa'] = array(
	'browsedata' => 'نمایش اطلاعات',
	'sd_browsedata_choosecategory' => 'انتخاب یک رده',
	'sd_browsedata_viewcategory' => 'نمایش رده',
	'sd_browsedata_subcategory' => 'زیررده',
	'sd_browsedata_other' => 'دیگر',
	'sd_browsedata_none' => 'هیچکدام',
	'sd_browsedata_filterbyvalue' => 'فیلتر با این مقدار',
	'sd_browsedata_filterbysubcategory' => 'فیلتر با این زیر رده',
	'sd_browsedata_otherfilter' => 'نمایش صفحاتی با مقدار دیگر برای این فیلتر',
	'sd_browsedata_nonefilter' => 'نمایش صفحه‌های فاقد مقدار برای این فیلتر',
	'sd_browsedata_or' => 'یا',
	'sd_browsedata_removefilter' => 'حذف این فیلتر',
	'sd_browsedata_removesubcategoryfilter' => 'حذف این فیلتر زیر رده',
	'sd_browsedata_resetfilters' => 'تنظیم فیلترها از نو',
	'sd_browsedata_addanothervalue' => 'برای اضافه‌کردن مقداری دیگر بر روی پیکان کلیک کنید.',
	'sd_browsedata_daterangestart' => ':شروع',
	'sd_browsedata_daterangeend' => 'پایان:',
	'filters' => 'پالایه‌ها',
	'sd_filters_docu' => 'فیلترهای زیر در این ویکی وجود دارد:',
	'createfilter' => 'پالایه‌ای بسازید',
	'sd_createfilter_name' => 'نام:',
	'sd_createfilter_property' => 'ویژگی که این فیلتر شامل آن می‌شود:',
	'sd_createfilter_usepropertyvalues' => 'همه مقادیر این ویژگی را برای این فیلتر به‌کار برید',
	'sd_createfilter_usecategoryvalues' => 'مقادیر فیلتر را از این رده بگیرید:',
	'sd_createfilter_requirefilter' => 'قبل از نمایش این یکی، یک فیلتر دیگر باید انتخاب شود:',
	'sd_createfilter_label' => 'برچسب این فیلتر (دلخواه)',
	'sd_blank_error' => 'نمی‌تواند خالی باشد',
	'sd-pageschemas-filter' => 'پالایه',
	'sd_filter_coversproperty' => 'این فیلتر ویژگی $1 را شامل می‌شود.',
	'sd_filter_getsvaluesfromcategory' => 'مقادیرش را از رده $1 می‌گیرد',
	'sd_filter_requiresfilter' => 'به وجود فیلتر $1 احتیاج دارد.',
	'sd_filter_haslabel' => 'برچسب $1 دارد.',
);

/** Finnish (suomi)
 * @author Beluga
 * @author Cimon Avaro
 * @author Crt
 * @author Nedergard
 * @author Nike
 * @author Str4nd
 * @author ZeiP
 */
$messages['fi'] = array(
	'semanticdrilldown-desc' => 'Semanttisen tiedonlouhinnan navigointiliittymä',
	'specialpages-group-sd_group' => 'Semanttinen tiedonlouhinta',
	'browsedata' => 'Datan selaus',
	'sd_browsedata_choosecategory' => 'Valitse luokka',
	'sd_browsedata_viewcategory' => 'näytä luokka',
	'sd_browsedata_docu' => 'Valitse alta yksi tai useampi kohde tulosten rajaamiseksi.',
	'sd_browsedata_subcategory' => 'Alaluokka',
	'sd_browsedata_other' => 'Muu',
	'sd_browsedata_none' => 'Ei mikään',
	'sd_browsedata_filterbyvalue' => 'Suodata tällä arvolla',
	'sd_browsedata_filterbysubcategory' => 'Suodata tämän alaluokan mukaan',
	'sd_browsedata_otherfilter' => 'Näytä sivut tämän suodattimen toisella arvolla',
	'sd_browsedata_nonefilter' => 'Näytä sivut ilman arvoa tällä suodattimella',
	'sd_browsedata_or' => 'tai',
	'sd_browsedata_removefilter' => 'Poista suodatin',
	'sd_browsedata_removesubcategoryfilter' => 'Poista tämä alaluokka-suodatin',
	'sd_browsedata_resetfilters' => 'Nollaa suodattimet',
	'sd_browsedata_addanothervalue' => 'Napsauta nuolta lisääksesi uuden arvon',
	'sd_browsedata_daterangestart' => 'Alku',
	'sd_browsedata_daterangeend' => 'Loppu',
	'sd_browsedata_novalues' => 'Tälle suodattimelle ei ole arvoja',
	'filters' => 'Suodattimet',
	'sd_filters_docu' => 'Sivustolla {{SITENAME}} on seuraavat suodattimet:',
	'sd_formcreate' => 'Luo lomakkeella',
	'sd_viewform' => 'Näytä lomake',
	'createfilter' => 'Luo suodatin',
	'sd-createfilter-with-name' => 'Luo suodatin: $1',
	'sd_createfilter_name' => 'Nimi',
	'sd_createfilter_property' => 'Ominaisuus, jota tämä suodatin koskee:',
	'sd_createfilter_usepropertyvalues' => 'Käytä tämän ominaisuuden kaikkia arvoja tälle suodattimelle',
	'sd_createfilter_usecategoryvalues' => 'Nouda suodattimen arvot tästä luokasta:',
	'sd_createfilter_requirefilter' => 'Toinen suodatin on valittava ennen kuin tämä näytetään:',
	'sd_createfilter_label' => 'Suodattimen nimi (valinnainen):',
	'sd_blank_error' => 'ei voi olla tyhjä',
	'sd-pageschemas-filter' => 'Suodatin',
	'sd-pageschemas-values' => 'Arvot',
	'sd_filter_coversproperty' => 'Tämä suodatin käyttää ominaisuutta $1.',
	'sd_filter_getsvaluesfromcategory' => 'Sen arvot tulevat luokasta $1.',
	'sd_filter_requiresfilter' => 'Se edellyttää suodattimen $1 käyttöä.',
	'sd_filter_haslabel' => 'Sen nimi on $1.',
);

/** French (français)
 * @author Crochet.david
 * @author Gomoko
 * @author Grondin
 * @author IAlex
 * @author Jean-Frédéric
 * @author Nicolas NALLET
 * @author PieRRoMaN
 * @author Sherbrooke
 * @author Tititou36
 * @author Urhixidur
 */
$messages['fr'] = array(
	'semanticdrilldown-desc' => 'Une interface de recherche multicritères au sein des données sémantiques',
	'specialpages-group-sd_group' => 'Exercice de sémantique',
	'browsedata' => 'Chercher les données',
	'sd_browsedata_choosecategory' => 'Choisissez une catégorie',
	'sd_browsedata_viewcategory' => 'Voir la catégorie',
	'sd_browsedata_docu' => 'Cliquez sur un ou plusieurs éléments pour filtrer vos résultats.',
	'sd_browsedata_subcategory' => 'Sous-catégorie',
	'sd_browsedata_other' => 'Autre',
	'sd_browsedata_none' => 'Néant',
	'sd_browsedata_filterbyvalue' => 'Filtrer par valeur',
	'sd_browsedata_filterbysubcategory' => 'Filtrer par cette sous-catégorie',
	'sd_browsedata_otherfilter' => 'Voir les pages avec une autre valeur pour ce filtre',
	'sd_browsedata_nonefilter' => 'Voir les pages avec aucune valeur pour ce filtre',
	'sd_browsedata_or' => 'ou',
	'sd_browsedata_removefilter' => 'Retirer ce filtre',
	'sd_browsedata_removesubcategoryfilter' => 'Retirer cette sous-catégorie du filtre',
	'sd_browsedata_resetfilters' => 'Remise à zéro des filtres',
	'sd_browsedata_addanothervalue' => 'Cliquez sur la flèche pour ajouter une autre valeur',
	'sd_browsedata_daterangestart' => 'Début :',
	'sd_browsedata_daterangeend' => 'Fin :',
	'sd_browsedata_novalues' => 'Il n’existe pas de valeur pour ce filtre',
	'filters' => 'Filtres',
	'sd_filters_docu' => 'Le filtre suivant existe sur {{SITENAME}} :',
	'sd_formcreate' => 'Créer avec un formulaire',
	'sd_viewform' => 'Voir le formulaire',
	'createfilter' => 'Créer un filtre',
	'sd-createfilter-with-name' => 'Créer le filtre : $1',
	'sd_createfilter_name' => 'Nom :',
	'sd_createfilter_property' => 'Propriété que ce filtre couvrira :',
	'sd_createfilter_usepropertyvalues' => 'Utiliser, pour ce filtre, toutes les valeurs de cette propriété',
	'sd_createfilter_usecategoryvalues' => 'Obtenir les valeurs pour ce filtre à partir de cette catégorie :',
	'sd_createfilter_requirefilter' => 'Exiger qu’un autre filtre soit sélectionné avant que celui-ci ne soit affiché :',
	'sd_createfilter_label' => 'Étiquette pour ce filtre (facultatif) :',
	'sd_blank_error' => 'ne peut être laissé en blanc',
	'sd-pageschemas-filter' => 'Filtre',
	'sd-pageschemas-values' => 'Valeurs',
	'sd_filter_coversproperty' => 'Ce filtre couvre la propriété $1.',
	'sd_filter_getsvaluesfromcategory' => 'Il obtient ses valeurs à partir de la catégorie $1.',
	'sd_filter_requiresfilter' => 'Il nécessite la présence du filtre $1.',
	'sd_filter_haslabel' => 'Étiqueté $1.',
);

/** Franco-Provençal (arpetan)
 * @author Cedric31
 * @author ChrisPtDe
 */
$messages['frp'] = array(
	'specialpages-group-sd_group' => 'Ègzèrcice de sèmantica',
	'browsedata' => 'Navegar les balyês',
	'sd_browsedata_choosecategory' => 'Chouèsir una catègorie',
	'sd_browsedata_viewcategory' => 'vêre la catègorie',
	'sd_browsedata_subcategory' => 'Sot-catègorie',
	'sd_browsedata_other' => 'Ôtro',
	'sd_browsedata_none' => 'Nion',
	'sd_browsedata_filterbyvalue' => 'Filtrar per ceta valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar per ceta sot-catègorie',
	'sd_browsedata_otherfilter' => 'Fâre vêre les pâges avouéc una ôtra valor por ceti filtro',
	'sd_browsedata_nonefilter' => 'Fâre vêre les pâges avouéc gins de valor por ceti filtro',
	'sd_browsedata_or' => 'ou ben',
	'sd_browsedata_removefilter' => 'Enlevar ceti filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Enlevar ceti filtro de sot-catègorie',
	'sd_browsedata_resetfilters' => 'Tornar inicialisar los filtros',
	'sd_browsedata_daterangestart' => 'Comencement :',
	'sd_browsedata_daterangeend' => 'Fin :',
	'filters' => 'Filtros',
	'sd_filters_docu' => 'Cetos filtros ègzistont dessus {{SITENAME}} :',
	'sd_formcreate' => 'Fâre avouéc un formulèro',
	'sd_viewform' => 'Vêre lo formulèro',
	'createfilter' => 'Fâre un filtro',
	'sd-createfilter-with-name' => 'Fâre lo filtro : $1',
	'sd_createfilter_name' => 'Nom :',
	'sd_createfilter_label' => 'Ètiquèta por ceti filtro (u chouèx) :',
	'sd_blank_error' => 'pôt pas étre vouedo',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valors',
	'sd_filter_coversproperty' => 'Ceti filtro côvre la propriètât $1.',
	'sd_filter_getsvaluesfromcategory' => 'Il at ses valors dês la catègorie $1.',
	'sd_filter_haslabel' => 'Il at l’ètiquèta $1.',
);

/** Western Frisian (Frysk)
 * @author Snakesteuben
 */
$messages['fy'] = array(
	'sd_browsedata_other' => 'Oare',
	'sd_browsedata_none' => 'Gjin',
	'filters' => 'Filters',
);

/** Irish (Gaeilge)
 * @author පසිඳු කාවින්ද
 */
$messages['ga'] = array(
	'sd_browsedata_other' => 'Eile',
	'sd_browsedata_none' => 'Tada',
	'sd_createfilter_name' => 'Ainm:',
);

/** Galician (galego)
 * @author Alma
 * @author Toliño
 */
$messages['gl'] = array(
	'semanticdrilldown-desc' => 'Unha interface para navegar a través de datos semánticos',
	'specialpages-group-sd_group' => 'Exercicio de semántica',
	'browsedata' => 'Datos do navegador',
	'sd_browsedata_choosecategory' => 'Elixir unha categoría',
	'sd_browsedata_viewcategory' => 'ver categoría',
	'sd_browsedata_docu' => 'Prema nun ou máis elementos dos de embaixo para estreitar os seus resultados.',
	'sd_browsedata_subcategory' => 'Subcategoría',
	'sd_browsedata_other' => 'Outro',
	'sd_browsedata_none' => 'Ningún',
	'sd_browsedata_filterbyvalue' => 'Filtrar por este valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar por esta subcategoría',
	'sd_browsedata_otherfilter' => 'Mostrar páxinas con outro valor para este filtro',
	'sd_browsedata_nonefilter' => 'Mostrar páxinas con ningún valor para este filtro',
	'sd_browsedata_or' => 'ou',
	'sd_browsedata_removefilter' => 'Eliminar este filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Eliminar este filtro de subcategorías',
	'sd_browsedata_resetfilters' => 'Eliminar filtros',
	'sd_browsedata_addanothervalue' => 'Prema na frecha para engadir outro valor',
	'sd_browsedata_daterangestart' => 'Comezo:',
	'sd_browsedata_daterangeend' => 'Fin:',
	'sd_browsedata_novalues' => 'Non hai valores para este filtro',
	'filters' => 'Filtros',
	'sd_filters_docu' => 'Os seguintes filtros existen en {{SITENAME}}:',
	'sd_formcreate' => 'Crear cun formulario',
	'sd_viewform' => 'Ver o formulario',
	'createfilter' => 'Crear un filtro',
	'sd-createfilter-with-name' => 'Crear o filtro: $1',
	'sd_createfilter_name' => 'Nome:',
	'sd_createfilter_property' => 'Propiedade que o filtro inclúe:',
	'sd_createfilter_usepropertyvalues' => 'Usar todos os valores da propiedade para o filtro',
	'sd_createfilter_usecategoryvalues' => 'Obter os valores para o filtro desta categoría:',
	'sd_createfilter_requirefilter' => 'Requírese que sexa seleccionado outro filtro antes de que este sexa amosado:',
	'sd_createfilter_label' => 'Lapela para este filtro (opcional):',
	'sd_blank_error' => 'non pode estar en branco',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valores',
	'sd_filter_coversproperty' => 'O filtro inclúe a propiedade $1.',
	'sd_filter_getsvaluesfromcategory' => 'Obtén os seus valores da categoría $1.',
	'sd_filter_requiresfilter' => 'Require a presenza do filtro $1.',
	'sd_filter_haslabel' => 'Ten a lapela $1.',
);

/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 * @author Crazymadlover
 * @author Omnipaedista
 */
$messages['grc'] = array(
	'browsedata' => 'Δεδομένα πλοηγήσεως',
	'sd_browsedata_viewcategory' => 'ὁρᾶν κατηγορίαν',
	'sd_browsedata_subcategory' => 'Ὑποκατηγορία',
	'sd_browsedata_other' => 'Ἄλλον',
	'sd_browsedata_none' => 'Οὐδεμία',
	'sd_browsedata_or' => 'ἢ',
	'sd_browsedata_daterangestart' => 'Ἐκκινεῖν:',
	'sd_browsedata_daterangeend' => 'Τέλος:',
	'filters' => 'Διηθητήρια',
	'sd_viewform' => 'Ὁρᾶν τύπον',
	'createfilter' => 'Ποιεῖν διηθητήριον',
	'sd_createfilter_name' => 'Ὄνομα:',
);

/** Swiss German (Alemannisch)
 * @author Als-Chlämens
 * @author Als-Holder
 */
$messages['gsw'] = array(
	'semanticdrilldown-desc' => 'E Drilldown-Benutzerschnittstell go dur semantischi Date z navigiere',
	'specialpages-group-sd_group' => 'Semantisch Drilldown',
	'browsedata' => 'Date aaluege',
	'sd_browsedata_choosecategory' => 'Kategorii uuswehle',
	'sd_browsedata_viewcategory' => 'Kategorii aaluege',
	'sd_browsedata_docu' => 'Druck uf ein oder meh Filter go s Ergebnis yyschränke.',
	'sd_browsedata_subcategory' => 'Unterkategorii',
	'sd_browsedata_other' => 'Anders',
	'sd_browsedata_none' => 'Keis',
	'sd_browsedata_filterbyvalue' => 'Iber dää Wärt filtere',
	'sd_browsedata_filterbysubcategory' => 'Filter fir die Subkategorii',
	'sd_browsedata_otherfilter' => 'Zeig Syte mit eme andere Wärt fir dää Filter',
	'sd_browsedata_nonefilter' => 'Zeig Syte mit keim Wärt fir dää Filter',
	'sd_browsedata_or' => 'oder',
	'sd_browsedata_removefilter' => 'Dää Filter lesche',
	'sd_browsedata_removesubcategoryfilter' => 'Dää Subkategorii-Filter lesche',
	'sd_browsedata_resetfilters' => 'Filter zruggsetze',
	'sd_browsedata_addanothervalue' => 'Druck uf dr Pfyyl go ne andere Wärt zuefiege',
	'sd_browsedata_daterangestart' => 'Aafang:',
	'sd_browsedata_daterangeend' => 'Änd:',
	'sd_browsedata_novalues' => 'S het kei Wärt fir dää Filter',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Die Filter git s in däm Wiki:',
	'sd_formcreate' => 'Mit Formular aalege',
	'sd_viewform' => 'Formular aazeige',
	'createfilter' => 'E Filter aalege',
	'sd_createfilter_name' => 'Name:',
	'sd_createfilter_property' => 'Eigeschaft vu däm Filter:',
	'sd_createfilter_usepropertyvalues' => 'Alli Wärt vu däre Eigeschaft fir dr Filter bruuche.',
	'sd_createfilter_usecategoryvalues' => 'D Wärt fir dr Filter vu däre Kategorii verwände:',
	'sd_createfilter_requirefilter' => 'Voreb dää Filter aazeigt wird, muess dää ander Filter gsetzt syy:',
	'sd_createfilter_label' => 'Bezeichnig vu däm Filter (optional):',
	'sd_blank_error' => 'derf nit läär syy',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Wärt',
	'sd_filter_coversproperty' => 'Dää Filter betrifft d Eigenschaft $1.',
	'sd_filter_getsvaluesfromcategory' => 'Är chunnt syni Wärt us dr Kategorii $1 iber.',
	'sd_filter_requiresfilter' => 'Är setzt dr Filter $1 vorus.',
	'sd_filter_haslabel' => 'Är het d Bezeichnig $1.',
);

/** Gujarati (ગુજરાતી)
 * @author Dsvyas
 */
$messages['gu'] = array(
	'filters' => 'ચાળણી',
);

/** Manx (Gaelg)
 * @author MacTire02
 */
$messages['gv'] = array(
	'sd_browsedata_viewcategory' => 'jeeagh er ronney',
	'sd_browsedata_other' => 'Elley',
	'sd_createfilter_name' => 'Ennym:',
);

/** Hawaiian (Hawai`i)
 * @author Singularity
 */
$messages['haw'] = array(
	'sd_createfilter_name' => 'Inoa:',
);

/** Hebrew (עברית)
 * @author Amire80
 * @author Rotemliss
 * @author YaronSh
 */
$messages['he'] = array(
	'semanticdrilldown-desc' => 'ממשק מעבר מהיר לניווט במידע סמנטי',
	'specialpages-group-sd_group' => 'מעבר מהיר במידע סמנטי',
	'browsedata' => 'עיון בנתונים',
	'sd_browsedata_choosecategory' => 'בחירת קטגוריה',
	'sd_browsedata_viewcategory' => 'צפייה בקטגוריה',
	'sd_browsedata_docu' => 'לחצו על פריט אחד או יותר להלן כדי לצמצם את התוצאות.',
	'sd_browsedata_subcategory' => 'קטגוריית משנה',
	'sd_browsedata_other' => 'אחר',
	'sd_browsedata_none' => 'ללא',
	'sd_browsedata_filterbyvalue' => 'סינון לפי ערך זה',
	'sd_browsedata_filterbysubcategory' => 'סינון לפי קטגוריית משנה זו',
	'sd_browsedata_otherfilter' => 'הצגת דפים עם ערך אחר עבור מסנן זה',
	'sd_browsedata_nonefilter' => 'הצגת דפים ללא ערך עבור מסנן זה',
	'sd_browsedata_or' => 'או',
	'sd_browsedata_removefilter' => 'הסרת מסנן זה',
	'sd_browsedata_removesubcategoryfilter' => 'הסרת המסנן של קטגוריית משנה זו',
	'sd_browsedata_resetfilters' => 'איפוס המסננים',
	'sd_browsedata_addanothervalue' => 'יש ללחוץ על החץ כדי להוסיף ערך נוסף',
	'sd_browsedata_daterangestart' => 'התחלה:',
	'sd_browsedata_daterangeend' => 'סיום:',
	'sd_browsedata_novalues' => 'אין ערכים עבור מסנן זה',
	'filters' => 'מסננים',
	'sd_filters_docu' => 'המסננים הבאים קיימים ב{{grammar:תחילית|{{SITENAME}}}}:',
	'sd_formcreate' => 'יצירה עם טופס',
	'sd_viewform' => 'הצגת הטופס',
	'createfilter' => 'יצירת מסנן',
	'sd-createfilter-with-name' => 'יצירת מסנן: $1',
	'sd_createfilter_name' => 'שם:',
	'sd_createfilter_property' => 'המאפיין אותו מכסה מסנן זה:',
	'sd_createfilter_usepropertyvalues' => 'שימוש בכל הערכים של מאפיין זה עבור המסנן',
	'sd_createfilter_usecategoryvalues' => 'קבלת הערכים עבור המסנן מקטגוריה זו:',
	'sd_createfilter_requirefilter' => 'הצבת דרישה לבחירת מסנן אחר לפני שזה יוצג:',
	'sd_createfilter_label' => 'תווית עבור מסנן זה (אופציונלי):',
	'sd_blank_error' => 'לא ניתן להשאיר ריק',
	'sd-pageschemas-filter' => 'מסנן',
	'sd-pageschemas-values' => 'ערכים',
	'sd_filter_coversproperty' => 'מסנן זה מכסה את המאפיין $1.',
	'sd_filter_getsvaluesfromcategory' => 'קבלת הערכים עבורו נעשית מהקטגוריה $1.',
	'sd_filter_requiresfilter' => 'נדרשת עבורו נוכחות של המסנן $1.',
	'sd_filter_haslabel' => 'חלה עליו התווית $1.',
);

/** Hindi (हिन्दी)
 * @author Kaustubh
 */
$messages['hi'] = array(
	'browsedata' => 'डाटा देखें',
	'sd_browsedata_choosecategory' => 'एक श्रेणी चुनें',
	'sd_browsedata_viewcategory' => 'श्रेणी देखें',
	'sd_browsedata_subcategory' => 'उपश्रेणी',
	'sd_browsedata_other' => 'अन्य',
	'sd_browsedata_none' => 'बिल्कुल नहीं',
	'sd_browsedata_filterbyvalue' => 'इस वैल्यू के अनुसार फ़िल्टर करें',
	'sd_browsedata_filterbysubcategory' => 'इस उपश्रेणी के अनुसार फ़िल्टर करें',
	'sd_browsedata_removefilter' => 'यह फ़िल्टर हटायें',
	'sd_browsedata_removesubcategoryfilter' => 'यह उपश्रेणी फ़िल्टर हटायें',
	'sd_browsedata_resetfilters' => 'फ़िल्टर रिसैट करें',
	'filters' => 'फ़िल्टर्स',
	'createfilter' => 'फ़िल्टर बनायें',
	'sd_createfilter_name' => 'नाम:',
	'sd_createfilter_property' => 'यह फ़िल्टर कौनसे गुणधर्मका इस्तेमाल करता हैं:',
	'sd_createfilter_usepropertyvalues' => 'इस फ़िल्टरके लिये इस गुणधर्मकी सभी वैल्यूओंका इस्तेमाल करें',
	'sd_createfilter_usecategoryvalues' => 'इस फ़िल्टरके लिये इस श्रेणी से वैल्यू लें:',
	'sd_createfilter_label' => 'इस फ़िल्टरका लेबल (वैकल्पिक):',
	'sd_blank_error' => 'खाली नहीं हो सकता',
	'sd_filter_haslabel' => 'इसको $1 यह लेबल हैं।',
);

/** Croatian (hrvatski)
 * @author Ex13
 */
$messages['hr'] = array(
	'filters' => 'Filteri',
);

/** Upper Sorbian (hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'semanticdrilldown-desc' => 'Interfejs Drilldown za nawigaciju znutřka semantiskich datow',
	'specialpages-group-sd_group' => 'Semantiska nawigacija',
	'browsedata' => 'Daty přepytać',
	'sd_browsedata_choosecategory' => 'Wubjer kategoriju',
	'sd_browsedata_viewcategory' => 'Kategoriju wobhladać',
	'sd_browsedata_docu' => 'Klikń na jedyn zapisk abo na wjacore zapiski, zo by wuslědki zamjezował.',
	'sd_browsedata_subcategory' => 'Podkategorija',
	'sd_browsedata_other' => 'Druhe',
	'sd_browsedata_none' => 'Žane',
	'sd_browsedata_filterbyvalue' => 'Po tutej hódnoće filtrować',
	'sd_browsedata_filterbysubcategory' => 'Po tutej podkategoriji filtorwać',
	'sd_browsedata_otherfilter' => 'Strony z druhej hódnotu za tutón filter pokazać',
	'sd_browsedata_nonefilter' => 'Strony bjez hódnoty za tutón filter pokazać',
	'sd_browsedata_or' => 'abo',
	'sd_browsedata_removefilter' => 'Tutón filter wotstronić',
	'sd_browsedata_removesubcategoryfilter' => 'Tutón podkategorijny filter wotstronić',
	'sd_browsedata_resetfilters' => 'Filtry wróćo stajić',
	'sd_browsedata_addanothervalue' => 'Na šipk kliknyć, zo by so druha hódnota přidała',
	'sd_browsedata_daterangestart' => 'Spočatk:',
	'sd_browsedata_daterangeend' => 'Kónc:',
	'sd_browsedata_novalues' => 'Za tutón filter hódnoty njejsu',
	'filters' => 'Filtry',
	'sd_filters_docu' => 'Slědowace filtry we {{GRAMMAR:Lokatiw|{{SITENAME}}}} eksistuja:',
	'sd_formcreate' => 'Z formularom wutworić',
	'sd_viewform' => 'Formular wobhladać',
	'createfilter' => 'Wutwor filter',
	'sd-createfilter-with-name' => 'Filter wutworić: $1',
	'sd_createfilter_name' => 'Mjeno:',
	'sd_createfilter_property' => 'Kajkosć tutho filtra:',
	'sd_createfilter_usepropertyvalues' => 'Wužij wšě hódnoty tuteje kajkosće za filter',
	'sd_createfilter_usecategoryvalues' => 'Wobstaraj hódnoty za filter z tuteje kategorije:',
	'sd_createfilter_requirefilter' => 'Zo by tutón filter zwobrazniło, je druhi filter trjeba:',
	'sd_createfilter_label' => 'Mjeno tutoho filtra (opcionalny):',
	'sd_blank_error' => 'njesmě prózdny być',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Hódnoty',
	'sd_filter_coversproperty' => 'Tutón filter wobsahuje kajkosć $1.',
	'sd_filter_getsvaluesfromcategory' => 'Wobsahuje swoje hódnoty z kategorije $1.',
	'sd_filter_requiresfilter' => 'Trjeba filter $1.',
	'sd_filter_haslabel' => 'Ma mjeno $1.',
);

/** Hungarian (magyar)
 * @author Dani
 * @author Dorgan
 * @author Glanthor Reviol
 * @author TK-999
 */
$messages['hu'] = array(
	'semanticdrilldown-desc' => 'Adatlefúró felület a szemantikus adatokban való navigációhoz',
	'specialpages-group-sd_group' => 'Szemantikus adatlefúrás',
	'browsedata' => 'Adatok böngészése',
	'sd_browsedata_choosecategory' => 'Válassz egy kategóriát',
	'sd_browsedata_viewcategory' => 'kategória megtekintése',
	'sd_browsedata_docu' => 'Kattints egy vagy több elemre alább, hogy pontosítsd az eredményeket.',
	'sd_browsedata_subcategory' => 'Alkategória',
	'sd_browsedata_other' => 'Egyéb',
	'sd_browsedata_none' => 'Nincs',
	'sd_browsedata_filterbyvalue' => 'Szűrés ezen érték alapján',
	'sd_browsedata_filterbysubcategory' => 'Szűrés ezen alkategória alapján',
	'sd_browsedata_otherfilter' => 'Olyan lapok megjelenítése, melyeken ennek a szűrőnek más az értéke',
	'sd_browsedata_nonefilter' => 'Olyan lapok megjelenítése, melyeken ennek a szűrőnek nincs értéke',
	'sd_browsedata_or' => 'vagy',
	'sd_browsedata_removefilter' => 'Szűrő eltávolítása',
	'sd_browsedata_removesubcategoryfilter' => 'Alkategória szűrő törlése',
	'sd_browsedata_resetfilters' => 'Szűrő alaphelyzetbe állítása',
	'sd_browsedata_addanothervalue' => 'Kattints a nyílra másik érték hozzáadásához',
	'sd_browsedata_daterangestart' => 'Kezdődátum:',
	'sd_browsedata_daterangeend' => 'Végdátum:',
	'sd_browsedata_novalues' => 'Nincsenek ehhez a szűrőhöz tartozó értékek',
	'filters' => 'Szűrők',
	'sd_filters_docu' => 'A következő szűrők vannak a(z) {{SITENAME}} wikin:',
	'sd_formcreate' => 'Létrehozás űrlappal',
	'sd_viewform' => 'Űrlap megtekintése',
	'createfilter' => 'Szűrő létrehozása',
	'sd_createfilter_name' => 'Név:',
	'sd_createfilter_property' => 'Tulajdonság, amit ez a szűrő lefed:',
	'sd_createfilter_usepropertyvalues' => 'A tulajdonság összes értékének használata ennél a szűrőnél',
	'sd_createfilter_usecategoryvalues' => 'A szűrő értékeinek felvétele ebből a kategóriából:',
	'sd_createfilter_requirefilter' => 'Egy másik szűrő legyen kiválasztva, mielőtt ez megjelenik:',
	'sd_createfilter_label' => 'Szűrő címkéje (nem kötelező):',
	'sd_blank_error' => 'nem lehet üres',
	'sd-pageschemas-filter' => 'Szűrő',
	'sd-pageschemas-values' => 'Értékek',
	'sd_filter_coversproperty' => 'Ez a szűrő lefedi a(z) $1 tulajdonságot.',
	'sd_filter_getsvaluesfromcategory' => 'Értékeit a következő kategóriából kapja: $1.',
	'sd_filter_requiresfilter' => 'Szükséges a(z) $1 szűrő megléte.',
	'sd_filter_haslabel' => 'A címkéje $1.',
);

/** Interlingua (interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'semanticdrilldown-desc' => 'Un interfacie de exercitio pro navigar per datos semantic',
	'specialpages-group-sd_group' => 'Exercitio de semantica',
	'browsedata' => 'Percurrer datos',
	'sd_browsedata_choosecategory' => 'Selige un categoria',
	'sd_browsedata_viewcategory' => 'vider categoria',
	'sd_browsedata_docu' => 'Clicca super un o plus entratas in basso pro restringer tu resultatos.',
	'sd_browsedata_subcategory' => 'Subcategoria',
	'sd_browsedata_other' => 'Altere',
	'sd_browsedata_none' => 'Nulle',
	'sd_browsedata_filterbyvalue' => 'Filtrar per iste valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar per iste subcategoria',
	'sd_browsedata_otherfilter' => 'Monstrar paginas con un altere valor pro iste filtro',
	'sd_browsedata_nonefilter' => 'Monstrar paginas sin valor pro iste filtro',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Remover iste filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Remover iste filtro de subcategoria',
	'sd_browsedata_resetfilters' => 'Reinitialisar filtros',
	'sd_browsedata_addanothervalue' => 'Clicca super le sagitta pro adder ancora un valor',
	'sd_browsedata_daterangestart' => 'Initio:',
	'sd_browsedata_daterangeend' => 'Fin:',
	'sd_browsedata_novalues' => 'Il non ha valores pro iste filtro',
	'filters' => 'Filtros',
	'sd_filters_docu' => 'Le sequente filtros existe in {{SITENAME}}:',
	'sd_formcreate' => 'Crear con formulario',
	'sd_viewform' => 'Vider formulario',
	'createfilter' => 'Crear un filtro',
	'sd-createfilter-with-name' => 'Crear filtro: $1',
	'sd_createfilter_name' => 'Nomine:',
	'sd_createfilter_property' => 'Le proprietate que iste filtro coperi:',
	'sd_createfilter_usepropertyvalues' => 'Usar tote le valores de iste proprietate pro le filtro',
	'sd_createfilter_usecategoryvalues' => 'Obtener valores pro filtro ab iste categoria:',
	'sd_createfilter_requirefilter' => 'Requirer que un altere filtro sia seligite ante que iste es monstrate:',
	'sd_createfilter_label' => 'Etiquetta pro iste filtro (optional):',
	'sd_blank_error' => 'non pote esser vacue',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valores',
	'sd_filter_coversproperty' => 'Iste filtro coperi le proprietate $1.',
	'sd_filter_getsvaluesfromcategory' => 'Illo obtene su valores ab le categoria $1.',
	'sd_filter_requiresfilter' => 'Illo require le presentia del filtro $1.',
	'sd_filter_haslabel' => 'Illo ha le etiquetta $1.',
);

/** Indonesian (Bahasa Indonesia)
 * @author Bennylin
 * @author Irwangatot
 * @author IvanLanin
 * @author Rex
 * @author පසිඳු කාවින්ද
 */
$messages['id'] = array(
	'semanticdrilldown-desc' => 'Suatu antarmuka penelusuran untuk menyelami data semantik',
	'specialpages-group-sd_group' => 'Penelusuran Semantik',
	'browsedata' => 'Jelajahi data',
	'sd_browsedata_choosecategory' => 'Pilih kategori',
	'sd_browsedata_viewcategory' => 'lihat kategori',
	'sd_browsedata_docu' => 'Klik satu atau lebih butir di bawah untuk mempersempit hasil pencarian.',
	'sd_browsedata_subcategory' => 'Subkategori',
	'sd_browsedata_other' => 'Lain-lain',
	'sd_browsedata_none' => 'Tidak ada',
	'sd_browsedata_filterbyvalue' => 'Filter menurut nilai ini',
	'sd_browsedata_filterbysubcategory' => 'Filter menurut subkategori ini',
	'sd_browsedata_otherfilter' => 'Tampilkan halaman dengan nilai lain dari filter ini',
	'sd_browsedata_nonefilter' => 'Tampilkan halaman tanpa nilai dari filter ini',
	'sd_browsedata_or' => 'atau',
	'sd_browsedata_removefilter' => 'Hilangkan filter',
	'sd_browsedata_removesubcategoryfilter' => 'Hilangkan filter subkategori ini',
	'sd_browsedata_resetfilters' => 'Atur ulang filter',
	'sd_browsedata_addanothervalue' => 'Klik tanda panah untuk menambahkan nilai lain',
	'sd_browsedata_daterangestart' => 'Awal:',
	'sd_browsedata_daterangeend' => 'Akhir:',
	'sd_browsedata_novalues' => 'Tidak ada nilai untuk filter ini',
	'filters' => 'Penyaring',
	'sd_filters_docu' => 'Filter berikut ada di {{SITENAME}}:',
	'sd_formcreate' => 'Buat dengan formulir',
	'sd_viewform' => 'Lihat formulir',
	'createfilter' => 'Buat filter',
	'sd_createfilter_name' => 'Nama:',
	'sd_createfilter_property' => 'Properti yang dicakup filter ini:',
	'sd_createfilter_usepropertyvalues' => 'Gunakan semua nilai dari properti ini untuk filter',
	'sd_createfilter_usecategoryvalues' => 'Dapatkan nilai untuk filter dari kategori ini:',
	'sd_createfilter_requirefilter' => 'Perlu memilih filter lain sebelum yang satu ini ditampilkan:',
	'sd_createfilter_label' => 'Label untuk filter ini (opsional):',
	'sd_blank_error' => 'tidak boleh kosong',
	'sd-pageschemas-filter' => 'Penyaring',
	'sd_filter_coversproperty' => 'Filter ini mencakup properti $1.',
	'sd_filter_getsvaluesfromcategory' => 'Ia mendapat nilainya dari kategori $1.',
	'sd_filter_requiresfilter' => 'Ia memerlukan keberadaan filter $1.',
	'sd_filter_haslabel' => 'Ia memiliki label $1.',
);

/** Igbo (Igbo)
 * @author Ukabia
 */
$messages['ig'] = array(
	'sd_browsedata_other' => 'Nke ozor',
	'sd_browsedata_or' => 'ma',
	'sd_createfilter_name' => 'Áhà:',
);

/** Icelandic (íslenska)
 * @author S.Örvarr.S
 */
$messages['is'] = array(
	'filters' => 'Síur',
	'sd_createfilter_name' => 'Nafn:',
);

/** Italian (italiano)
 * @author Beta16
 * @author Civvì
 * @author Darth Kule
 * @author Gianfranco
 */
$messages['it'] = array(
	'semanticdrilldown-desc' => "Un'interfaccia drilldown per navigare attraverso dati semantici",
	'specialpages-group-sd_group' => 'Drilldown semantico',
	'browsedata' => 'Esplora i dati',
	'sd_browsedata_choosecategory' => 'Scegli una categoria',
	'sd_browsedata_viewcategory' => 'vedi categoria',
	'sd_browsedata_docu' => 'Clicca su uno o più fra gli elementi sottostanti per restringere i tuoi risultati.',
	'sd_browsedata_subcategory' => 'Sottocategoria',
	'sd_browsedata_other' => 'Altro',
	'sd_browsedata_none' => 'Nessuno',
	'sd_browsedata_filterbyvalue' => 'Filtra per questo valore',
	'sd_browsedata_filterbysubcategory' => 'Filtra per questa sottocategoria',
	'sd_browsedata_otherfilter' => 'Mostra pagine con un altro valore per questo filtro',
	'sd_browsedata_nonefilter' => 'Mostra pagine senza valori per questo filtro',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Rimuovi questo filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Rimuovi questo filtro per sottocategoria',
	'sd_browsedata_resetfilters' => 'Azzera filtri',
	'sd_browsedata_addanothervalue' => 'Clicca la freccia per aggiungere un altro valore',
	'sd_browsedata_daterangestart' => 'Parti da:',
	'sd_browsedata_daterangeend' => 'Fino a:',
	'sd_browsedata_novalues' => 'Non ci sono valori per questo filtro',
	'filters' => 'Filtri',
	'sd_filters_docu' => 'In {{SITENAME}} ci sono i seguenti filtri:',
	'sd_formcreate' => 'Crea con un modulo',
	'sd_viewform' => 'Visualizza modulo',
	'createfilter' => 'Crea un filtro',
	'sd-createfilter-with-name' => 'Crea filtro: $1',
	'sd_createfilter_name' => 'Nome:',
	'sd_createfilter_property' => 'Proprietà interessate da questo filtro:',
	'sd_createfilter_usepropertyvalues' => 'Usa tutti i valori di questa proprietà per il filtro',
	'sd_createfilter_usecategoryvalues' => 'Ottieni i valori per il filtro da questa categoria:',
	'sd_createfilter_requirefilter' => 'Richiedi la selezione di un altro filtro prima che questo sia visualizzato:',
	'sd_createfilter_label' => 'Etichetta per questo filtro (facoltativa):',
	'sd_blank_error' => 'non può essere vuoto',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valori',
	'sd_filter_coversproperty' => 'Questo filtro riguarda la proprietà $1.',
	'sd_filter_getsvaluesfromcategory' => 'Prende i suoi valori dalla categoria $1.',
	'sd_filter_requiresfilter' => 'Richiede la presenza del filtro $1.',
	'sd_filter_haslabel' => "Ha l'etichetta $1.",
);

/** Japanese (日本語)
 * @author Aotake
 * @author Fryed-peach
 * @author Hosiryuhosi
 * @author Shirayuki
 * @author Whym
 */
$messages['ja'] = array(
	'semanticdrilldown-desc' => '意味的データを閲覧するための絞り込みインターフェイス',
	'specialpages-group-sd_group' => '意味的ドリルダウン',
	'browsedata' => 'データ閲覧',
	'sd_browsedata_choosecategory' => 'カテゴリを選択',
	'sd_browsedata_viewcategory' => 'カテゴリ表示',
	'sd_browsedata_docu' => '結果を絞り込むには、以下の項目を1つ以上クリックします。',
	'sd_browsedata_subcategory' => '下位カテゴリ',
	'sd_browsedata_other' => 'その他',
	'sd_browsedata_none' => 'なし',
	'sd_browsedata_filterbyvalue' => 'この値で絞り込む',
	'sd_browsedata_filterbysubcategory' => 'この下位カテゴリで絞り込む',
	'sd_browsedata_otherfilter' => 'このフィルターの別の値を持つページを表示',
	'sd_browsedata_nonefilter' => 'このフィルターの値を持たないページを表示',
	'sd_browsedata_or' => 'または',
	'sd_browsedata_removefilter' => 'このフィルターを除去',
	'sd_browsedata_removesubcategoryfilter' => 'この下位カテゴリ条件を除去',
	'sd_browsedata_resetfilters' => 'フィルターをリセット',
	'sd_browsedata_addanothervalue' => '矢印をクリックして別の値を追加できます',
	'sd_browsedata_daterangestart' => '始まり:',
	'sd_browsedata_daterangeend' => '終わり:',
	'sd_browsedata_novalues' => 'このフィルターには値がありません',
	'filters' => 'フィルター一覧',
	'sd_filters_docu' => '{{SITENAME}} には次のフィルターが存在します:',
	'sd_formcreate' => 'フォームを使用して作成',
	'sd_viewform' => 'フォームを表示',
	'createfilter' => 'フィルターを作成',
	'sd-createfilter-with-name' => 'フィルターを作成: $1',
	'sd_createfilter_name' => '名前:',
	'sd_createfilter_property' => 'このフィルターが対象とするプロパティ:',
	'sd_createfilter_usepropertyvalues' => 'フィルターにこのプロパティのすべての値を使用',
	'sd_createfilter_usecategoryvalues' => 'このカテゴリからフィルターの値を取得:',
	'sd_createfilter_requirefilter' => 'このフィルターが表示される前に、別のフィルターを選択するのを必須にする:',
	'sd_createfilter_label' => 'このフィルターのラベル (省略可能):',
	'sd_blank_error' => '空であってはならない',
	'sd-pageschemas-filter' => 'フィルター',
	'sd-pageschemas-values' => '値',
	'sd_filter_coversproperty' => 'このフィルターはプロパティ $1 を対象とします。',
	'sd_filter_getsvaluesfromcategory' => '値をカテゴリ $1 から取得します。',
	'sd_filter_requiresfilter' => 'フィルター $1 の存在を要求します。',
	'sd_filter_haslabel' => 'ラベル $1 を持ちます。',
);

/** Javanese (Basa Jawa)
 * @author Meursault2004
 */
$messages['jv'] = array(
	'sd_browsedata_choosecategory' => 'Pilih kategori',
	'sd_browsedata_viewcategory' => 'ndeleng kategori',
	'sd_browsedata_subcategory' => 'Subkategori',
	'sd_browsedata_other' => 'Liyané',
	'sd_browsedata_none' => 'Ora ana',
	'sd_browsedata_or' => 'utawa',
	'sd_browsedata_removefilter' => 'Ilangana filter iki',
	'sd_browsedata_addanothervalue' => 'Tambahna biji liya', # Fuzzy
	'filters' => 'Filter-filter',
	'createfilter' => 'Nggawé filter',
	'sd_createfilter_name' => 'Jeneng:',
	'sd_createfilter_property' => 'Sifat sing diliput filter iki:',
	'sd_createfilter_label' => 'Label kanggo filter (opsional):',
	'sd_blank_error' => 'ora bisa kosong',
	'sd_filter_requiresfilter' => 'Merlokaké anané filter $1.',
);

/** Georgian (ქართული)
 * @author David1010
 * @author Malafaya
 */
$messages['ka'] = array(
	'sd_browsedata_choosecategory' => 'კატეგორიის არჩევა',
	'sd_browsedata_viewcategory' => 'კატეგორიის ხილვა',
	'sd_browsedata_subcategory' => 'ქვეკატეგორია',
	'sd_browsedata_other' => 'სხვა',
	'sd_browsedata_none' => 'არა',
	'sd_browsedata_or' => 'ან',
	'sd_browsedata_removefilter' => 'ამ ფილტრის მოშორება',
	'sd_browsedata_daterangestart' => 'დასაწყისი:',
	'sd_browsedata_daterangeend' => 'დასასრული:',
	'filters' => 'ფილტრები',
	'sd_formcreate' => 'ფორმით შექმნა',
	'sd_viewform' => 'ფორმის ხილვა',
	'createfilter' => 'ფილტრის შექმნა',
	'sd-createfilter-with-name' => 'ფილტრის შექმნა: $1',
	'sd_createfilter_name' => 'სახელი:',
	'sd-pageschemas-filter' => 'ფილტრი',
	'sd-pageschemas-values' => 'მნიშვნელობები',
);

/** Khmer (ភាសាខ្មែរ)
 * @author Chhorran
 * @author Lovekhmer
 * @author Thearith
 * @author គីមស៊្រុន
 */
$messages['km'] = array(
	'browsedata' => 'រាវរកទិន្នន័យ',
	'sd_browsedata_choosecategory' => 'ជ្រើសរើសចំណាត់ថ្នាក់ក្រុម',
	'sd_browsedata_viewcategory' => 'មើលចំណាត់ថ្នាក់ក្រុម',
	'sd_browsedata_subcategory' => 'ចំណាត់ក្រុមរង',
	'sd_browsedata_other' => 'ផ្សេងៗទៀត',
	'sd_browsedata_none' => 'ទទេ',
	'sd_browsedata_filterbyvalue' => 'តម្រង​តាមរយៈ​តម្លៃ​នេះ',
	'sd_browsedata_filterbysubcategory' => 'តម្រង​តាមរយៈ​ចំណាត់ថ្នាក់ក្រុម​នេះ',
	'sd_browsedata_otherfilter' => 'បង្ហាញ​ទំព័រ​ជាមួយ​តម្លៃ​ផ្សេង​សម្រាប់​តម្រង​នេះ',
	'sd_browsedata_nonefilter' => 'បង្ហាញ​ទំព័រ​ដោយ​គ្មាន​តម្លៃ​សម្រាប់​តម្រង​នេះ',
	'sd_browsedata_or' => 'ឬ',
	'sd_browsedata_removefilter' => 'ដក​តម្រង​នេះចេញ',
	'sd_browsedata_removesubcategoryfilter' => 'ដក​តម្រង​ចំណាត់ថ្នាក់ក្រុមរង​នេះ​ចេញ',
	'sd_browsedata_resetfilters' => 'កំណត់​តម្រង​ឡើងវិញ',
	'sd_browsedata_addanothervalue' => 'ចុចលើសញ្ញាព្រួញដើម្បីបន្ថែម​តម្លៃ​ផ្សេង',
	'sd_browsedata_daterangestart' => 'ចាប់ផ្ដើម:',
	'sd_browsedata_daterangeend' => 'បញ្ចប់:',
	'filters' => 'តម្រងការពារនានា',
	'sd_filters_docu' => 'តម្រង​ដូចតទៅនេះ​មាន​នៅក្នុង {{SITENAME}}:',
	'sd_viewform' => 'មើលបែបបទ',
	'createfilter' => 'បង្កើត​តម្រង',
	'sd_createfilter_name' => 'ឈ្មោះ៖',
	'sd_createfilter_property' => 'លក្ខណៈសម្បត្តិ​ដែល​តម្រង​នេះ​គ្រប:',
	'sd_createfilter_usepropertyvalues' => 'តម្លៃ​ទាំងអស់​នៃលក្ខណៈសម្បត្តិ​នេះ​សម្រាប់​តម្រង',
	'sd_createfilter_usecategoryvalues' => 'ទទួល​តម្លៃ​សម្រាប់​តម្រង​ពី​ចំណាត់ថ្នាក់ក្រុម​នេះ:',
	'sd_createfilter_requirefilter' => 'ទាមទារ​តម្រង​ផ្សេងទៀត​ដើម្បី​ធ្វើការ​ជ្រើសរើស មុនពេល​តម្រង​មួយនេះ​ត្រូវ​បាន​បង្ហាញ:',
	'sd_createfilter_label' => 'ស្លាក​សម្រាប់​តម្រង​នេះ (តាមបំណង):',
	'sd_blank_error' => 'មិន​អាច​ទទេ​បាន​ឡើយ',
	'sd_filter_coversproperty' => 'តម្រង​នេះ​គ្របដណ្ដប់​ចំណាត់ថ្នាក់ក្រុម $1 ។',
	'sd_filter_getsvaluesfromcategory' => 'វា​ទទួល​តម្លៃ​របស់​ខ្លួន​ពី​ចំណាត់ថ្នាក់ក្រុម $1 ។',
	'sd_filter_requiresfilter' => 'វា​ទាមទារ​ឱ្យ​មាន​វត្តមាន​របស់​តម្រង $1 ។',
	'sd_filter_haslabel' => 'វា​មាន​ស្លាក $1 ។',
);

/** Kannada (ಕನ್ನಡ)
 * @author Nayvik
 */
$messages['kn'] = array(
	'sd_browsedata_other' => 'ಇತರ',
	'sd_createfilter_name' => 'ಹೆಸರು:',
);

/** Korean (한국어)
 * @author Albamhandae
 * @author Priviet
 */
$messages['ko'] = array(
	'semanticdrilldown-desc' => '시맨틱 데이터를 탐색하기 위한 드릴다운 인터페이스',
	'specialpages-group-sd_group' => '시맨틱 드릴다운',
	'browsedata' => '데이터 찾아보기',
	'sd_browsedata_choosecategory' => '분류 선택하기',
	'sd_browsedata_viewcategory' => '분류 보기',
	'sd_browsedata_docu' => '검색 결과를 줄이려면 하나 또는 그 이상의 항목을 선택하세요.',
	'sd_browsedata_subcategory' => '하위 분류',
	'sd_browsedata_other' => '기타',
	'sd_browsedata_none' => '없음',
	'sd_browsedata_filterbyvalue' => '이 값으로 필터',
	'sd_browsedata_filterbysubcategory' => '하위 분류로 필터',
	'sd_browsedata_otherfilter' => '이 필터에 대한 다른 값을 보이기',
	'sd_browsedata_nonefilter' => '이 필터에 대한 값이 없는 문서 보이기',
	'sd_browsedata_or' => '또는',
	'sd_browsedata_removefilter' => '이 필터를 제거',
	'sd_browsedata_removesubcategoryfilter' => '하위 분류 필터 제거',
	'sd_browsedata_resetfilters' => '필터 다시 설정',
	'sd_browsedata_addanothervalue' => '화살표를 클릭하여 다른 값을 추가',
	'sd_browsedata_daterangestart' => '시작:',
	'sd_browsedata_daterangeend' => '끝:',
	'sd_browsedata_novalues' => '이 필터에 대한 값이 없습니다',
	'filters' => '필터',
	'sd_filters_docu' => '다음 필터가 {{SITENAME}}에 존재합니다:',
	'sd_formcreate' => '양식으로 만들기',
	'sd_viewform' => '양식 보기',
	'createfilter' => '필터 만들기',
	'sd-createfilter-with-name' => '필터 만들기: $1',
	'sd_createfilter_name' => '이름:',
	'sd_createfilter_property' => '이 필터가 다루는 속성:',
	'sd_createfilter_usepropertyvalues' => '필터에 대한 속성의 모든 값을 사용',
	'sd_createfilter_usecategoryvalues' => '이 분류에서 필터를 위한 값 얻기:',
	'sd_createfilter_label' => '이 필터의 레이블(선택 사항):',
	'sd_blank_error' => '비워둘 수 없음',
	'sd-pageschemas-filter' => '필터',
	'sd-pageschemas-values' => '값',
	'sd_filter_coversproperty' => '이 필터는 $1 속성을 다룹니다.',
	'sd_filter_getsvaluesfromcategory' => '분류 $1의 값을 얻습니다.',
	'sd_filter_requiresfilter' => '$1 필터가 있어야 합니다.',
	'sd_filter_haslabel' => '$1 레이블을 갖고 있습니다.',
);

/** Colognian (Ripoarisch)
 * @author Purodha
 */
$messages['ksh'] = array(
	'semanticdrilldown-desc' => 'En Schnettshtell för der Metmaacher, öm sesh en einzel Schrette dorsch de semantische Date ze wöhle.',
	'specialpages-group-sd_group' => 'Semantsch Nohbohre',
	'browsedata' => 'En dä Date bläddere',
	'sd_browsedata_choosecategory' => 'Donn en Saachjrupp ußwähle',
	'sd_browsedata_viewcategory' => 'Saachjropp beloore',
	'sd_browsedata_docu' => 'Donn op eine, odder och ettlijje, fun dä Felltere unge klecke, öm dat wat erus kütt, jet kleiner ze maache.',
	'sd_browsedata_subcategory' => 'Ungerjrupp',
	'sd_browsedata_other' => 'Söns wat',
	'sd_browsedata_none' => 'Kei',
	'sd_browsedata_filterbyvalue' => 'Donn övver dä Wäät felltere',
	'sd_browsedata_filterbysubcategory' => 'Donn övver di Unger-Saachjropp felltere',
	'sd_browsedata_otherfilter' => 'Donn Sigge zeije met enem andere Wäät för hee dä Felter',
	'sd_browsedata_nonefilter' => 'Donn Sigge zeije oohne Wäät för hee dä Felter',
	'sd_browsedata_or' => 'udder',
	'sd_browsedata_removefilter' => 'Donn dä Felter hee fottschmiiße',
	'sd_browsedata_removesubcategoryfilter' => 'Donn dä Felter övver en Ungersaachjropp fott schmiiße',
	'sd_browsedata_resetfilters' => 'Donn de Feltere widder op Shtandat setze',
	'sd_browsedata_addanothervalue' => 'Donn op dä Piel klecke, öm noch ene Wäät dobei ze zälle',
	'sd_browsedata_daterangestart' => 'Aanfang:',
	'sd_browsedata_daterangeend' => 'Engk:',
	'sd_browsedata_novalues' => 'Et sin kein Wääte för dä Felter do',
	'filters' => 'Feltere',
	'sd_filters_docu' => 'Mer han hee di Feltere em Wiki;',
	'sd_formcreate' => 'Övver e Fommulaa aanlääje',
	'sd_viewform' => 'Dat Fommullaa aanzeije',
	'createfilter' => 'Ene Felter aanlääje',
	'sd-createfilter-with-name' => 'Don ene Felter aanlääje: $1',
	'sd_createfilter_name' => 'Name:',
	'sd_createfilter_property' => 'De Eijeschaff, die hee jefeltert weed:',
	'sd_createfilter_usepropertyvalues' => 'Donn all de Wääte us hee dä Eijeschaff för dä Felter bruche',
	'sd_createfilter_usecategoryvalues' => 'De müjjelesche Wääte för noh ze feltere kumme us dä Saachjrupp:',
	'sd_createfilter_requirefilter' => 'Ih dat hee dä Felter aanjezeish weede kann, moß vörher ald ene andere Felter ußjesooht gewääse sin, un zwa dä:',
	'sd_createfilter_label' => 'Et Etikättsche för dä Felter (kam_mer fott lohße):',
	'sd_blank_error' => 'kann nit leddesch bliive',
	'sd-pageschemas-filter' => 'Fėlter',
	'sd-pageschemas-values' => 'Wääte',
	'sd_filter_coversproperty' => 'Dä Felter betref de Eijeschaff $1.',
	'sd_filter_getsvaluesfromcategory' => 'Hä kritt sing Wääte us de Saachjrupp $1.',
	'sd_filter_requiresfilter' => 'Dä hät dä Felter $1 eets ens nüdesch.',
	'sd_filter_haslabel' => 'Däm sing Etikättsche es „$1“',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Les Meloures
 * @author Robby
 * @author Soued031
 */
$messages['lb'] = array(
	'semanticdrilldown-desc' => "En ''Drilldown''-Interface, fir duerch semantesch Daten ze navigéieren",
	'specialpages-group-sd_group' => "Semnateschen ''Drilldown''",
	'sd_browsedata_choosecategory' => 'Eng Kategorie wielen',
	'sd_browsedata_viewcategory' => 'Kategorie weisen',
	'sd_browsedata_docu' => 'Klickt op eent oder méi Elementer hei ënnendrënner fir Är Resultater anzegrenzen.',
	'sd_browsedata_subcategory' => 'Ënnerkategorie',
	'sd_browsedata_other' => 'Aner',
	'sd_browsedata_none' => 'Keen',
	'sd_browsedata_filterbyvalue' => 'Filter fir dëse Wäert',
	'sd_browsedata_filterbysubcategory' => 'No dëser Ënnerkategorie filteren',
	'sd_browsedata_otherfilter' => 'Säite weisen déi mat engem anere Wäert fir dëse Filter',
	'sd_browsedata_nonefilter' => 'Säite weisen déi kee Wäert fir dëse Filter hunn',
	'sd_browsedata_or' => 'oder',
	'sd_browsedata_removefilter' => 'Dëse Filter ewechhuelen',
	'sd_browsedata_removesubcategoryfilter' => 'Dëse Filter vun den Ënnerkategorien ewechhuelen',
	'sd_browsedata_resetfilters' => 'Filteren zrécksetzen',
	'sd_browsedata_addanothervalue' => 'Klickt op de Feil fir een anere Wäert derbäisetzen',
	'sd_browsedata_daterangestart' => 'Ufank:',
	'sd_browsedata_daterangeend' => 'Enn:',
	'sd_browsedata_novalues' => 'Et gëtt keng Wäerter fir dëse Filter',
	'filters' => 'Filteren',
	'sd_filters_docu' => 'Dës Filtere gëtt et op {{SITENAME}}:',
	'sd_formcreate' => 'Mat engem Formulaire gemaach',
	'sd_viewform' => 'Formulaire weisen',
	'createfilter' => 'E Filter uleeën',
	'sd_createfilter_name' => 'Numm:',
	'sd_createfilter_usepropertyvalues' => 'All Wäerter vun dëser Eegeschaft fir de Filter benotzen',
	'sd_createfilter_usecategoryvalues' => 'Werter fir dëse Filter vun dëser Kategorie kréien:',
	'sd_createfilter_requirefilter' => 'Verlaangen dat en anere Filter gewielt gëtt ier dësen ugewise gëtt:',
	'sd_createfilter_label' => 'Etikett fir dëse Filter (fakultativ):',
	'sd_blank_error' => 'däerf net eidel sinn',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Wäerter',
	'sd_filter_coversproperty' => "Dëse Filter betrefft d'Eegeschaft $1.",
	'sd_filter_getsvaluesfromcategory' => 'E kritt seng Werter aus der Kategorie $1.',
	'sd_filter_requiresfilter' => "E verlaangt d'Presenz vum Filter $1.",
	'sd_filter_haslabel' => "en huet d'Etikett $1.",
);

/** Latvian (latviešu)
 * @author Ilmarmors
 */
$messages['lv'] = array(
	'browsedata' => 'Pārlūkot datus',
	'sd_browsedata_choosecategory' => 'Izvēlieties kategoriju',
	'sd_browsedata_viewcategory' => 'skatīt kategoriju',
	'sd_browsedata_subcategory' => 'Apakškategorija',
	'sd_browsedata_other' => 'Cita',
	'sd_browsedata_none' => 'Neviena',
	'sd_browsedata_filterbyvalue' => 'Filtrēt pēc šīs vērtības',
	'sd_browsedata_filterbysubcategory' => 'Filtrēt pēc šīs apakškategorijas',
	'sd_browsedata_otherfilter' => 'Parādīt lapas ar citu šī filtra vērtību',
	'sd_browsedata_nonefilter' => 'Rādīt lapas bez šī filtra vērtības',
	'sd_browsedata_or' => 'vai',
	'sd_browsedata_removefilter' => 'Noņemt šo filtru',
	'sd_browsedata_removesubcategoryfilter' => 'Noņemt šo apakškategorijas filtru',
	'sd_browsedata_resetfilters' => 'Atiestatīt filtrus',
	'sd_browsedata_addanothervalue' => 'Noklikšķiniet uz bultiņas, lai pievienotu citu vērtību',
	'sd_browsedata_daterangestart' => 'Sākums:',
	'sd_browsedata_daterangeend' => 'Beigas:',
	'filters' => 'Filtri',
	'sd_formcreate' => 'Izveidot ar formu',
	'sd_viewform' => 'Skatīt formu',
	'createfilter' => 'Izveidot filtru',
	'sd_createfilter_name' => 'Nosaukums:',
	'sd_createfilter_label' => 'Filtra etiķete (nav obligāta):',
	'sd_blank_error' => 'nevar būt tukšs',
	'sd-pageschemas-filter' => 'Filtrs',
	'sd-pageschemas-values' => 'Vērtības',
);

/** Eastern Mari (олык марий)
 * @author Сай
 */
$messages['mhr'] = array(
	'filters' => 'Фильтр-влак',
);

/** Minangkabau (Baso Minangkabau)
 * @author Naval Scene
 */
$messages['min'] = array(
	'sd_viewform' => 'Caliak formulir',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 * @author Brest
 */
$messages['mk'] = array(
	'semanticdrilldown-desc' => 'Посредник за истенчена навигација по семантички податоци',
	'specialpages-group-sd_group' => 'Семантичко истенчување',
	'browsedata' => 'Прелистај податоци',
	'sd_browsedata_choosecategory' => 'Одберете категорија',
	'sd_browsedata_viewcategory' => 'види категорија',
	'sd_browsedata_docu' => 'Стиснете на еден или повеќе елементи подолу за да ги истенчите резултатите.',
	'sd_browsedata_subcategory' => 'Поткатегорија',
	'sd_browsedata_other' => 'Други',
	'sd_browsedata_none' => 'Нема',
	'sd_browsedata_filterbyvalue' => 'Филтрирај по оваа вредност',
	'sd_browsedata_filterbysubcategory' => 'Филтрирај по оваа категорија',
	'sd_browsedata_otherfilter' => 'Прикажи страници со друга вредност за овој филтер',
	'sd_browsedata_nonefilter' => 'Прикажи страници без вредности за овој филтер',
	'sd_browsedata_or' => 'или',
	'sd_browsedata_removefilter' => 'Отстрани го филтерот',
	'sd_browsedata_removesubcategoryfilter' => 'Отстрани го овој филтер за поткатегории',
	'sd_browsedata_resetfilters' => 'Врати ги филтрите по основно',
	'sd_browsedata_addanothervalue' => 'Стиснете на стрелката за да додадете друга вредност',
	'sd_browsedata_daterangestart' => 'Почеток:',
	'sd_browsedata_daterangeend' => 'Крај:',
	'sd_browsedata_novalues' => 'Нема зададено вредности за овој филтер',
	'filters' => 'Филтри',
	'sd_filters_docu' => '{{SITENAME}} ги има следниве филтри:',
	'sd_formcreate' => 'Создај со образец',
	'sd_viewform' => 'Погл. образецот',
	'createfilter' => 'Создај филтер',
	'sd-createfilter-with-name' => 'Создај филтер: $1',
	'sd_createfilter_name' => 'Име:',
	'sd_createfilter_property' => 'Својство кое го покрива овој филтер:',
	'sd_createfilter_usepropertyvalues' => 'Користи ги сите вредности на ова својство за филтерот',
	'sd_createfilter_usecategoryvalues' => 'Преземи вредности за филтер од оваа категорија:',
	'sd_createfilter_requirefilter' => 'Побарувај да биде избран друг филтер пред да се прикаже овој:',
	'sd_createfilter_label' => 'Наслов за овој филтер (незадолжително)',
	'sd_blank_error' => 'не може да стои празно',
	'sd-pageschemas-filter' => 'Филтер',
	'sd-pageschemas-values' => 'Вредности',
	'sd_filter_coversproperty' => 'Овој филтер го покрива својството $1.',
	'sd_filter_getsvaluesfromcategory' => 'Ги добива своите вредности од категоријата $1.',
	'sd_filter_requiresfilter' => 'Бара присуство на филтер $1.',
	'sd_filter_haslabel' => 'Има наслов $1.',
);

/** Malayalam (മലയാളം)
 * @author Praveenp
 * @author Shijualex
 */
$messages['ml'] = array(
	'sd_browsedata_choosecategory' => 'ഒരു വർഗ്ഗം തിരഞ്ഞെടുക്കുക',
	'sd_browsedata_viewcategory' => 'വർഗ്ഗം കാണുക',
	'sd_browsedata_subcategory' => 'ഉപവർഗ്ഗം',
	'sd_browsedata_other' => 'മറ്റുള്ളവ',
	'sd_browsedata_none' => 'ഒന്നുമില്ല',
	'sd_browsedata_filterbyvalue' => 'ഈ മൂല്യം ഉപയോഗിച്ച് അരിക്കുക',
	'sd_browsedata_filterbysubcategory' => 'ഈ ഉപവർഗ്ഗം ഉപയോഗിച്ച് അരിക്കുക',
	'sd_browsedata_otherfilter' => 'ഈ ഫിൽറ്റർ ഉപയോഗിച്ച് മറ്റൊരു മൂല്യത്തിലുള്ള താളുകൾ കാണിക്കുക',
	'sd_browsedata_nonefilter' => 'മൂല്യമൊന്നും ചേർക്കാതെ ഈ ഫിൽറ്റർ ഉപയോഗിച്ച് താളുകൾ കാണിക്കുക',
	'sd_browsedata_or' => 'അല്ലെങ്കിൽ',
	'sd_browsedata_removefilter' => 'ഈ ഫിൽറ്റർ ഒഴിവാക്കുക',
	'sd_browsedata_removesubcategoryfilter' => 'ഈ ഉപവർഗ്ഗ ഫിൽറ്റർ ഒഴിവാക്കുക',
	'sd_browsedata_resetfilters' => 'അരിപ്പകൾ പുനഃക്രമീകരിക്കുക',
	'sd_browsedata_addanothervalue' => 'മറ്റൊരു മൂല്യം ചേർക്കുക', # Fuzzy
	'filters' => 'അരിപ്പകൾ',
	'sd_filters_docu' => '{{SITENAME}} സം‌രംഭത്തിൽ താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്ന ഫിൽറ്ററുകൾ നിലവിലുണ്ട്:',
	'sd_viewform' => 'ഫോം കാണുക',
	'createfilter' => 'അരിപ്പ സൃഷ്ടിക്കുക',
	'sd_createfilter_name' => 'പേര്‌:',
	'sd_blank_error' => 'ശൂന്യമാക്കിയിടുന്നത് അനുവദനീയമല്ല',
	'sd_filter_getsvaluesfromcategory' => '$1 എന്ന വർഗ്ഗത്തിൽ നിന്നാണ്‌ ഇതിനു മൂല്യങ്ങൾ കിട്ടുന്നത്.',
);

/** Mongolian (монгол)
 * @author Chinneeb
 */
$messages['mn'] = array(
	'sd_browsedata_other' => 'Бусад',
	'filters' => 'Шүүлтүүрүүд',
);

/** Marathi (मराठी)
 * @author Htt
 * @author Kaustubh
 * @author Mahitgar
 */
$messages['mr'] = array(
	'browsedata' => 'डाटा न्याहाळा',
	'sd_browsedata_choosecategory' => 'एक वर्ग निवडा',
	'sd_browsedata_viewcategory' => 'वर्ग पहा',
	'sd_browsedata_subcategory' => 'उपवर्ग',
	'sd_browsedata_other' => 'इतर',
	'sd_browsedata_none' => '(काहीही नाही)',
	'sd_browsedata_filterbyvalue' => 'या किंमती प्रमाणे फिल्टर करा',
	'sd_browsedata_filterbysubcategory' => 'या उपवर्गा प्रमाणे फिल्टर करा',
	'sd_browsedata_otherfilter' => 'या फिल्टरच्या दुसर्‍या किंमतीसाठीची पाने दाखवा',
	'sd_browsedata_nonefilter' => 'या फिल्टरच्या शून्य किंमतीसाठीची पाने दाखवा',
	'sd_browsedata_or' => 'किंवा',
	'sd_browsedata_removefilter' => 'हा फिल्टर काढा',
	'sd_browsedata_removesubcategoryfilter' => 'हा उपवर्ग फिल्टर काढा',
	'sd_browsedata_resetfilters' => 'फिल्टर पूर्ववत करा',
	'sd_browsedata_addanothervalue' => 'दुसरी किंमत वाढवा', # Fuzzy
	'filters' => 'फिल्टर्स',
	'sd_filters_docu' => '{{SITENAME}} वर खालील फिल्टर्स उपलब्ध आहेत:',
	'createfilter' => 'नवीन फिल्टर बनवा',
	'sd_createfilter_name' => 'नाव:',
	'sd_createfilter_property' => 'हा फिल्टर कुठल्या गुणधर्मासाठी वापरायचा आहे:',
	'sd_createfilter_usepropertyvalues' => 'या फिल्टरकरीता या गुणधर्माच्या सर्व किंमती वापरा',
	'sd_createfilter_usecategoryvalues' => 'या फिल्टरकरीता या वर्गातून किंमती मिळवा:',
	'sd_createfilter_requirefilter' => 'हा फिल्टर दर्शविण्याआधी जर दुसरा फिल्टर वापरायचा असेल तर त्याचे नाव:',
	'sd_createfilter_label' => 'या फिल्टरकरीत लेबल (वैकल्पिक):',
	'sd_blank_error' => 'रिकामे असू शकत नाही',
	'sd_filter_coversproperty' => 'हा फिल्टर $1 या गुणधर्मावर चालतो.',
	'sd_filter_getsvaluesfromcategory' => 'तो $1 या वर्गातून किंमती घेतो.',
	'sd_filter_requiresfilter' => 'या साठी $1 हा फिल्टर असणे आवश्यक आहे.',
	'sd_filter_haslabel' => 'त्याला $1 हे लेबल आहे.',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'sd_browsedata_none' => 'Tiada',
	'sd_browsedata_or' => 'atau',
	'sd_formcreate' => 'Buat dengan borang',
	'sd_viewform' => 'Lihat borang',
	'sd_createfilter_name' => 'Nama:',
);

/** Erzya (эрзянь)
 * @author Botuzhaleny-sodamo
 */
$messages['myv'] = array(
	'sd_browsedata_subcategory' => 'Алкс категория',
	'sd_browsedata_other' => 'Лия',
	'sd_browsedata_or' => 'эли',
	'filters' => 'Сувтеметь',
	'createfilter' => 'Шкамс сувтеме',
	'sd_createfilter_name' => 'Лемезэ:',
);

/** Nahuatl (Nāhuatl)
 * @author Fluence
 */
$messages['nah'] = array(
	'sd_browsedata_other' => 'Occē',
	'sd_browsedata_none' => 'Ahtlein',
	'sd_browsedata_or' => 'nozo',
	'sd_createfilter_name' => 'Tōcāitl:',
);

/** Norwegian Bokmål (norsk bokmål)
 * @author Event
 * @author Harald Khan
 * @author Nghtwlkr
 * @author Njardarlogar
 */
$messages['nb'] = array(
	'semanticdrilldown-desc' => 'Et «drilldown»-grensesnitt for navigering gjennom semantiske data',
	'specialpages-group-sd_group' => 'Semantisk «drilldown»',
	'browsedata' => 'Bla gjennom data',
	'sd_browsedata_choosecategory' => 'Velg en kategori',
	'sd_browsedata_viewcategory' => 'se kategori',
	'sd_browsedata_docu' => 'Klikk på en eller flere enheter nedenfor for å smalne inn søket.',
	'sd_browsedata_subcategory' => 'Underkategori',
	'sd_browsedata_other' => 'Annen',
	'sd_browsedata_none' => 'Ingen',
	'sd_browsedata_filterbyvalue' => 'Filtrer etter denne verdien',
	'sd_browsedata_filterbysubcategory' => 'Filtrer etter denne underkategorien',
	'sd_browsedata_otherfilter' => 'Vis sider med en annen verdi for dette filteret',
	'sd_browsedata_nonefilter' => 'Vis sider uten noen verdi for dette filteret',
	'sd_browsedata_or' => 'eller',
	'sd_browsedata_removefilter' => 'Fjern dette filteret',
	'sd_browsedata_removesubcategoryfilter' => 'Fjern dette underkategorifilteret',
	'sd_browsedata_resetfilters' => 'Resett filtre',
	'sd_browsedata_addanothervalue' => 'Klikk på pilen for å legge til enda en verdi',
	'sd_browsedata_daterangestart' => 'Start:',
	'sd_browsedata_daterangeend' => 'Slutt:',
	'sd_browsedata_novalues' => 'Det er ingen verdier for dette filteret',
	'filters' => 'Filtre',
	'sd_filters_docu' => 'Følgende filtre finnes på {{SITENAME}}:',
	'sd_formcreate' => 'Opprett med skjema',
	'sd_viewform' => 'Se skjema',
	'createfilter' => 'Opprett et filter',
	'sd-createfilter-with-name' => 'Opprett filter: $1',
	'sd_createfilter_name' => 'Navn:',
	'sd_createfilter_property' => 'Egenskap dette filteret dekker:',
	'sd_createfilter_usepropertyvalues' => 'Bruk alle verdier av denne egenskapen for filteret',
	'sd_createfilter_usecategoryvalues' => 'Få verdier for filteret fra denne kategorien:',
	'sd_createfilter_requirefilter' => 'Krev at et annet filter velges før dette vises:',
	'sd_createfilter_label' => 'Etikett for dette filteret (valgfritt):',
	'sd_blank_error' => 'kan ikke være blank',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Verdier',
	'sd_filter_coversproperty' => 'Dette filteret dekker egenskapen $1.',
	'sd_filter_getsvaluesfromcategory' => 'Det får verdiene sine fra kategorien $1.',
	'sd_filter_requiresfilter' => 'Det krever at filteret $1 er til stede.',
	'sd_filter_haslabel' => 'Det har etiketten $1.',
);

/** Low German (Plattdüütsch)
 * @author Slomox
 */
$messages['nds'] = array(
	'sd_createfilter_name' => 'Naam:',
);

/** Dutch (Nederlands)
 * @author McDutchie
 * @author SPQRobin
 * @author Siebrand
 */
$messages['nl'] = array(
	'semanticdrilldown-desc' => 'Een drilldowninterface voor het navigeren door semantische gegevens',
	'specialpages-group-sd_group' => 'Semantic Drilldown',
	'browsedata' => 'Gegevens bekijken',
	'sd_browsedata_choosecategory' => 'Kies een categorie',
	'sd_browsedata_viewcategory' => 'categorie bekijken',
	'sd_browsedata_docu' => 'Selecteer een of meer van de onderstaande termen om het aantal resultaten te verkleinen.',
	'sd_browsedata_subcategory' => 'Ondercategorie',
	'sd_browsedata_other' => 'Andere',
	'sd_browsedata_none' => 'Geen',
	'sd_browsedata_filterbyvalue' => 'Op deze waarde filteren',
	'sd_browsedata_filterbysubcategory' => 'Op deze ondercategorie filteren',
	'sd_browsedata_otherfilter' => "Pagina's met een andere waarde voor deze filter bekijken",
	'sd_browsedata_nonefilter' => "Pagina's zonder waarde voor deze filter bekijken",
	'sd_browsedata_or' => 'of',
	'sd_browsedata_removefilter' => 'Deze filter verwijderen',
	'sd_browsedata_removesubcategoryfilter' => 'Deze ondercategoriefilter verwijderen',
	'sd_browsedata_resetfilters' => 'Filters opnieuw instellen',
	'sd_browsedata_addanothervalue' => 'Klik op de pijl om nog een waarde toe te voegen',
	'sd_browsedata_daterangestart' => 'Begin:',
	'sd_browsedata_daterangeend' => 'Einde:',
	'sd_browsedata_novalues' => 'Er zijn geen waarden voor dit filter',
	'filters' => 'Filters',
	'sd_filters_docu' => 'In {{SITENAME}} bestaan de volgende filters:',
	'sd_formcreate' => 'Via formulier aanmaken',
	'sd_viewform' => 'Formulier bekijken',
	'createfilter' => 'Filter aanmaken',
	'sd-createfilter-with-name' => 'Filter aanmaken: $1',
	'sd_createfilter_name' => 'Naam:',
	'sd_createfilter_property' => 'Eigenschap voor deze filter:',
	'sd_createfilter_usepropertyvalues' => 'Alle waarden voor deze eigenschap voor deze filter gebruiken',
	'sd_createfilter_usecategoryvalues' => 'Waarden voor deze filter uit de volgende categorie halen:',
	'sd_createfilter_requirefilter' => 'Selectie van een andere filter voor deze filter zichtbaar is vereisen:',
	'sd_createfilter_label' => 'Label voor deze filter (optioneel):',
	'sd_blank_error' => 'mag niet leeg blijven',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Waarden',
	'sd_filter_coversproperty' => 'Deze filter heeft betrekking op de eigenschap $1.',
	'sd_filter_getsvaluesfromcategory' => 'Het haalt de waarden van de categorie $1.',
	'sd_filter_requiresfilter' => 'Het filter $1 moet aanwezig zijn.',
	'sd_filter_haslabel' => 'Het heeft het label $1.',
);

/** Norwegian Nynorsk (norsk nynorsk)
 * @author Harald Khan
 * @author Jon Harald Søby
 * @author Njardarlogar
 */
$messages['nn'] = array(
	'semanticdrilldown-desc' => 'Eit «drilldown»-brukargrensesnitt for navigering gjennom semantiske data',
	'specialpages-group-sd_group' => 'Semantisk «drilldown»',
	'browsedata' => 'Bla gjennom data',
	'sd_browsedata_choosecategory' => 'Vel ein kategori',
	'sd_browsedata_viewcategory' => 'sjå kategori',
	'sd_browsedata_docu' => 'Trykk på ein eller fleire einingar nedanfor for å avgrensa resultata.',
	'sd_browsedata_subcategory' => 'Underkategori',
	'sd_browsedata_other' => 'Annan',
	'sd_browsedata_none' => 'Ingen',
	'sd_browsedata_filterbyvalue' => 'Filtrer etter denne verdien',
	'sd_browsedata_filterbysubcategory' => 'Filtrer etter denne underkategorien',
	'sd_browsedata_otherfilter' => 'Syn sider med ein annan verdi for dette filteret',
	'sd_browsedata_nonefilter' => 'Syn sider med null verdi for dette fileteret',
	'sd_browsedata_or' => 'eller',
	'sd_browsedata_removefilter' => 'Fjern dette filteret',
	'sd_browsedata_removesubcategoryfilter' => 'Fjern dette underkategorifilteret',
	'sd_browsedata_resetfilters' => 'Nullstill filter',
	'sd_browsedata_addanothervalue' => 'Legg til ny verdi', # Fuzzy
	'sd_browsedata_daterangestart' => 'Byrjing:',
	'sd_browsedata_daterangeend' => 'Slutt:',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Følgjande filter finst på {{SITENAME}}:',
	'sd_formcreate' => 'Opprett med skjema',
	'sd_viewform' => 'Sjå skjema',
	'createfilter' => 'Opprett eit filter',
	'sd_createfilter_name' => 'Namn:',
	'sd_createfilter_property' => 'Eigenskap som dette fileteret dekkjer:',
	'sd_createfilter_usepropertyvalues' => 'Nytt alle verdiar av denne eigenskapen for filteret:',
	'sd_createfilter_usecategoryvalues' => 'Få verdiar for filteret frå denne kategorien:',
	'sd_createfilter_requirefilter' => 'Krev at eit anna filter blir valt før dette blir vist:',
	'sd_createfilter_label' => 'Merkelapp for dette filteret (valfritt):',
	'sd_blank_error' => 'kan ikkje vera tom',
	'sd_filter_coversproperty' => 'Dette filteret dekkjer eigenskapen $1.',
	'sd_filter_getsvaluesfromcategory' => 'Det får verdiane sine frå kategorien $1.',
	'sd_filter_requiresfilter' => 'Det krev at filteret $1 er til stades.',
	'sd_filter_haslabel' => 'Det har merkelappen $1.',
);

/** Northern Sotho (Sesotho sa Leboa)
 * @author Mohau
 */
$messages['nso'] = array(
	'sd_browsedata_viewcategory' => 'Nyakorela sehlopha',
	'sd_browsedata_subcategory' => 'Sehlophana',
	'sd_createfilter_name' => 'Leina:',
);

/** Occitan (occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'semanticdrilldown-desc' => 'Una interfàcia d’exercici per la navigacion a travèrs de semantic data',
	'specialpages-group-sd_group' => 'Exercici de semantica',
	'browsedata' => 'Cercar las donadas',
	'sd_browsedata_choosecategory' => 'Causir una categoria',
	'sd_browsedata_viewcategory' => 'Veire la categoria',
	'sd_browsedata_docu' => 'Clicar sus un o maites elements per resarrar vòstres resultats.',
	'sd_browsedata_subcategory' => 'Soscategoria',
	'sd_browsedata_other' => 'Autre',
	'sd_browsedata_none' => 'Nonrés',
	'sd_browsedata_filterbyvalue' => 'Filtrat per valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar per aquesta soscategoria',
	'sd_browsedata_otherfilter' => 'Veire las paginas amb una autra valor per aqueste filtre',
	'sd_browsedata_nonefilter' => 'Veire las paginas amb pas cap de valor per aqueste filtre',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Levar aqueste filtre',
	'sd_browsedata_removesubcategoryfilter' => 'Levar aquesta soscategoria de filtre',
	'sd_browsedata_resetfilters' => 'Remesa a zèro dels filtres',
	'sd_browsedata_addanothervalue' => 'Clicatz sus la sageta per apondre una autra valor',
	'sd_browsedata_daterangestart' => 'Començament :',
	'sd_browsedata_daterangeend' => 'Fin :',
	'sd_browsedata_novalues' => 'Existís pas de valor per aqueste filtre',
	'filters' => 'Filtres',
	'sd_filters_docu' => 'Lo filtre seguent existís sus {{SITENAME}} :',
	'sd_formcreate' => 'Crear amb un formulari',
	'sd_viewform' => 'Veire lo formulari',
	'createfilter' => 'Crear un filtre',
	'sd_createfilter_name' => 'Nom :',
	'sd_createfilter_property' => "Proprietat qu'aqueste filtre cobrirà :",
	'sd_createfilter_usepropertyvalues' => "Utilizar, per aqueste filtre, totas las valors d'aquesta proprietat",
	'sd_createfilter_usecategoryvalues' => "Obténer las valors per aqueste filtre a partir d'aquesta categoria :",
	'sd_createfilter_requirefilter' => "Necessita un filtre devent èsser seleccionat abans qu'aqueste siá afichat :",
	'sd_createfilter_label' => 'Etiqueta per aqueste filtre (facultatiu) :',
	'sd_blank_error' => 'pòt pas èsser daissat en blanc',
	'sd_filter_coversproperty' => 'Aqueste filtre cobrís la proprietat $1.',
	'sd_filter_getsvaluesfromcategory' => 'Obten sas valors a partir de la categoria $1.',
	'sd_filter_requiresfilter' => 'Necessita la preséncia del filtre $1.',
	'sd_filter_haslabel' => 'Dispausa del labèl $1.',
);

/** Ossetic (Ирон)
 * @author Amikeco
 */
$messages['os'] = array(
	'sd_browsedata_none' => 'Нæй',
);

/** Deitsch (Deitsch)
 * @author Xqt
 */
$messages['pdc'] = array(
	'sd_browsedata_other' => 'Anneres',
	'sd_browsedata_none' => 'Ken',
	'sd_browsedata_or' => 'odder',
	'sd_createfilter_name' => 'Naame:',
);

/** Pälzisch (Pälzisch)
 * @author Manuae
 * @author Xqt
 */
$messages['pfl'] = array(
	'sd_browsedata_none' => 'Kääns',
	'sd_browsedata_or' => 'oda',
);

/** Polish (polski)
 * @author Airwolf
 * @author BeginaFelicysym
 * @author Chrumps
 * @author Maikking
 * @author Maire
 * @author Sp5uhe
 * @author ToSter
 */
$messages['pl'] = array(
	'semanticdrilldown-desc' => 'Interfejs umożliwiający zgłębianie danych semantycznych',
	'specialpages-group-sd_group' => 'Ćwiczenia semantyczne',
	'browsedata' => 'Przeglądaj dane',
	'sd_browsedata_choosecategory' => 'Wybierz kategorię',
	'sd_browsedata_viewcategory' => 'podgląd kategorii',
	'sd_browsedata_docu' => 'Kliknij jeden lub więcej z poniższych elementów, aby zawęzić wyniki.',
	'sd_browsedata_subcategory' => 'Kategoria podrzędna',
	'sd_browsedata_other' => 'Inne',
	'sd_browsedata_none' => 'Brak',
	'sd_browsedata_filterbyvalue' => 'Filtruj według tej wartości',
	'sd_browsedata_filterbysubcategory' => 'Filtruj według tej podkategorii',
	'sd_browsedata_otherfilter' => 'Wyświetla strony z innymi wartościami dla tego filtru',
	'sd_browsedata_nonefilter' => 'Pokaż strony bez wartości dla tego filtru',
	'sd_browsedata_or' => 'lub',
	'sd_browsedata_removefilter' => 'Usuń ten filtr',
	'sd_browsedata_removesubcategoryfilter' => 'Usuń ten filtr podkategorii',
	'sd_browsedata_resetfilters' => 'Wyzeruj filtry',
	'sd_browsedata_addanothervalue' => 'Kliknij strzałkę aby dodać inną wartość',
	'sd_browsedata_daterangestart' => 'Początek',
	'sd_browsedata_daterangeend' => 'Koniec',
	'sd_browsedata_novalues' => 'Nie ma żadnych wartości dla tego filtru',
	'filters' => 'Filtry',
	'sd_filters_docu' => 'Na {{GRAMMAR:MS.lp|{{SITENAME}}}} zdefiniowano następujące filtry:',
	'sd_formcreate' => 'Utwórz korzystając z formularza',
	'sd_viewform' => 'Zobacz formularz',
	'createfilter' => 'Utwórz filtr',
	'sd_createfilter_name' => 'Nazwa',
	'sd_createfilter_property' => 'Właściwość przesłonięta tym filtrem',
	'sd_createfilter_usepropertyvalues' => 'Użyj wszystkich wartości tej własności dla filtru',
	'sd_createfilter_usecategoryvalues' => 'Użyj wartości dla filtru z kategorii',
	'sd_createfilter_requirefilter' => 'Wymagaj użycia innego filtru przed tym',
	'sd_createfilter_label' => 'Etykieta filtru (nieobowiązkowa)',
	'sd_blank_error' => 'nie może być puste',
	'sd-pageschemas-filter' => 'Filtr',
	'sd-pageschemas-values' => 'Wartości',
	'sd_filter_coversproperty' => 'Ten filtr przesłania właściwość $1.',
	'sd_filter_getsvaluesfromcategory' => 'Otrzymuje wartości z kategorii $1.',
	'sd_filter_requiresfilter' => 'Wymaga obecności filtru $1.',
	'sd_filter_haslabel' => 'Ma etykietę $1.',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 */
$messages['pms'] = array(
	'semanticdrilldown-desc' => "N'antërfacia a cascada për esploré dat semàntich",
	'specialpages-group-sd_group' => 'Cascada Semàntica',
	'browsedata' => 'Sërché ij dat',
	'sd_browsedata_choosecategory' => 'Sern na categorìa',
	'sd_browsedata_viewcategory' => 'varda categorìa',
	'sd_browsedata_docu' => "Sgnaca su un o pi element sì-sota për strenze j'arzultà",
	'sd_browsedata_subcategory' => 'Sotcategorìa',
	'sd_browsedata_other' => 'Àutr',
	'sd_browsedata_none' => 'Gnun',
	'sd_browsedata_filterbyvalue' => 'Filtra për sto valor-sì',
	'sd_browsedata_filterbysubcategory' => 'Filtra për sta sotcategorìa-sì',
	'sd_browsedata_otherfilter' => "Mostra pàgine con n'àutr valor për sto filtr-sì",
	'sd_browsedata_nonefilter' => 'Mostra pàgine con gnun valor për sto filtr-sì',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Gava sto filtr-sì',
	'sd_browsedata_removesubcategoryfilter' => 'Gava sto filtr ëd sotcategorìa',
	'sd_browsedata_resetfilters' => 'Spian-a filtr',
	'sd_browsedata_addanothervalue' => "Sgnaca la flecia për gionté n'àutr valor",
	'sd_browsedata_daterangestart' => 'Prinsipi:',
	'sd_browsedata_daterangeend' => 'Fin:',
	'sd_browsedata_novalues' => 'A-i é pa gnun valor për sto filtr-sì',
	'filters' => 'Filtr',
	'sd_filters_docu' => 'I filtr sì-sota a esisto an {{SITENAME}}:',
	'sd_formcreate' => 'Creé con un formolari',
	'sd_viewform' => 'Visualisé ël formolari',
	'createfilter' => 'Crea un filtr',
	'sd-createfilter-with-name' => 'Creé ël filtr: $1',
	'sd_createfilter_name' => 'Nòm:',
	'sd_createfilter_property' => 'Proprietà che sto filtr-sì a coata:',
	'sd_createfilter_usepropertyvalues' => 'Dovré tùit ij valor dë sta proprietà-sì për ël filtr',
	'sd_createfilter_usecategoryvalues' => 'Pija ij valor për filtr da sta categorìa-sì:',
	'sd_createfilter_requirefilter' => "Ciama ëd selessioné n'àutr filtr prima che sto-sì a sia visualisà:",
	'sd_createfilter_label' => 'Tichëtta për sto filtr-sì (opsional):',
	'sd_blank_error' => 'a peul pa esse veuid',
	'sd-pageschemas-filter' => 'Filtr',
	'sd-pageschemas-values' => 'Valor',
	'sd_filter_coversproperty' => 'Sto filtr-sì a coata la proprietà $1.',
	'sd_filter_getsvaluesfromcategory' => 'A pija ij sò valor da la categorìa $1.',
	'sd_filter_requiresfilter' => 'A veul la presensa dël filtr $1.',
	'sd_filter_haslabel' => "A l'ha l'etichëtta $1.",
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'browsedata' => 'مالومات سپړل',
	'sd_browsedata_choosecategory' => 'يوه وېشنيزه ټاکل',
	'sd_browsedata_viewcategory' => 'وېشنيزه ښکاره کول',
	'sd_browsedata_subcategory' => 'څېرمه وېشنيزه',
	'sd_browsedata_other' => 'بل',
	'sd_browsedata_none' => 'هېڅ',
	'sd_browsedata_filterbysubcategory' => 'د همدې وړې-وېشنيزې له مخې چاڼول',
	'sd_browsedata_or' => 'يا',
	'sd_browsedata_removefilter' => 'همدا چاڼگر لرې کول',
	'sd_browsedata_daterangestart' => 'پيل:',
	'sd_browsedata_daterangeend' => 'پای:',
	'filters' => 'چاڼگرونه',
	'createfilter' => 'يو چاڼگر جوړول',
	'sd_createfilter_name' => 'نوم:',
	'sd_blank_error' => 'بايد تش نه وي',
	'sd-pageschemas-filter' => 'چاڼگر',
);

/** Portuguese (português)
 * @author Hamilton Abreu
 * @author Lijealso
 * @author Luckas
 * @author Malafaya
 * @author SandroHc
 * @author Waldir
 * @author 555
 */
$messages['pt'] = array(
	'semanticdrilldown-desc' => 'Uma interface de prospecção para navegar através de dados semânticos',
	'specialpages-group-sd_group' => 'Prospecção Semântica',
	'browsedata' => 'Navegar pelos dados',
	'sd_browsedata_choosecategory' => 'Escolha uma categoria',
	'sd_browsedata_viewcategory' => 'ver categoria',
	'sd_browsedata_docu' => 'Clique abaixo em um ou mais itens para restringir os seus resultados.',
	'sd_browsedata_subcategory' => 'subcategoria',
	'sd_browsedata_other' => 'Outro',
	'sd_browsedata_none' => 'Nenhum',
	'sd_browsedata_filterbyvalue' => 'Filtrar por este valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar por esta subcategoria',
	'sd_browsedata_otherfilter' => 'Apresentar páginas com outro valor para este filtro',
	'sd_browsedata_nonefilter' => 'Apresentar páginas sem valores para este filtro',
	'sd_browsedata_or' => 'ou',
	'sd_browsedata_removefilter' => 'Remover este filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Remover esta subcategoria da função de filtro',
	'sd_browsedata_resetfilters' => 'Repor filtros',
	'sd_browsedata_addanothervalue' => 'Clique na seta para adicionar outro valor',
	'sd_browsedata_daterangestart' => 'Início:',
	'sd_browsedata_daterangeend' => 'Fim:',
	'sd_browsedata_novalues' => 'Não há valores para este filtro',
	'filters' => 'Filtros',
	'sd_filters_docu' => 'Na {{SITENAME}} existem os seguintes filtros:',
	'sd_formcreate' => 'Criar com formulário',
	'sd_viewform' => 'Ver formulário',
	'createfilter' => 'Criar um filtro',
	'sd_createfilter_name' => 'Nome:',
	'sd_createfilter_property' => 'Propriedades que este filtro abrange:',
	'sd_createfilter_usepropertyvalues' => 'Usar todos os valores desta propriedade no filtro',
	'sd_createfilter_usecategoryvalues' => 'Obter valores de filtro a partir desta categoria:',
	'sd_createfilter_requirefilter' => 'Exigir que outro filtro seja selecionado antes de apresentar este:',
	'sd_createfilter_label' => 'Etiqueta para este filtro (opcional):',
	'sd_blank_error' => 'não pode estar em branco',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valores',
	'sd_filter_coversproperty' => 'Este filtro abrange a propriedade $1.',
	'sd_filter_getsvaluesfromcategory' => 'Extrai os seus valores da categoria $1.',
	'sd_filter_requiresfilter' => 'Requer a presença do filtro $1.',
	'sd_filter_haslabel' => 'Tem a etiqueta $1.',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Eduardo.mps
 * @author Enqd
 * @author Giro720
 * @author Jaideraf
 */
$messages['pt-br'] = array(
	'semanticdrilldown-desc' => 'Uma interface de introspecção para navegar através de dados semânticos',
	'specialpages-group-sd_group' => 'Introspecção Semântica',
	'browsedata' => 'Navegar pelo dados',
	'sd_browsedata_choosecategory' => 'Escolha uma categoria',
	'sd_browsedata_viewcategory' => 'ver categoria',
	'sd_browsedata_docu' => 'Clique abaixo em um ou mais itens para restringir os seus resultados.',
	'sd_browsedata_subcategory' => 'Subcategoria',
	'sd_browsedata_other' => 'Outro',
	'sd_browsedata_none' => 'Nenhum',
	'sd_browsedata_filterbyvalue' => 'Filtrar por este valor',
	'sd_browsedata_filterbysubcategory' => 'Filtrar por esta subcategoria',
	'sd_browsedata_otherfilter' => 'Exibir páginas com outro valor para este filtro',
	'sd_browsedata_nonefilter' => 'Exibir páginas sem valores para este filtro',
	'sd_browsedata_or' => 'ou',
	'sd_browsedata_removefilter' => 'Remover este filtro',
	'sd_browsedata_removesubcategoryfilter' => 'Remover o filtro por esta subcategoria',
	'sd_browsedata_resetfilters' => 'Zerar filtros',
	'sd_browsedata_addanothervalue' => 'Clique na seta para adicionar outro valor',
	'sd_browsedata_daterangestart' => 'Início:',
	'sd_browsedata_daterangeend' => 'Fim:',
	'sd_browsedata_novalues' => 'Não há valores para este filtro',
	'filters' => 'Filtros',
	'sd_filters_docu' => '{{SITENAME}} possui os seguintes filtros:',
	'sd_formcreate' => 'Criar com formulário',
	'sd_viewform' => 'Ver formulário',
	'createfilter' => 'Criar um filtro',
	'sd-createfilter-with-name' => 'Criar filtro: $1',
	'sd_createfilter_name' => 'Nome:',
	'sd_createfilter_property' => 'Propriedades que este filtro abrange:',
	'sd_createfilter_usepropertyvalues' => 'Usar todos os valores desta propriedade no filtro',
	'sd_createfilter_usecategoryvalues' => 'Obter valores de filtro a partir desta categoria:',
	'sd_createfilter_requirefilter' => 'Necessita de outro filtro selecionado antes deste ser exibido:',
	'sd_createfilter_label' => 'Etiqueta para este filtro (opcional):',
	'sd_blank_error' => 'não pode estar em branco',
	'sd-pageschemas-filter' => 'Filtro',
	'sd-pageschemas-values' => 'Valores',
	'sd_filter_coversproperty' => 'Este filtro abrange a propriedade $1.',
	'sd_filter_getsvaluesfromcategory' => 'Extrai os seus valores da categoria $1.',
	'sd_filter_requiresfilter' => 'Requer a presença do filtro $1.',
	'sd_filter_haslabel' => 'Possui a etiqueta $1.',
);

/** Romanian (română)
 * @author Firilacroco
 * @author KlaudiuMihaila
 * @author Mihai
 * @author Minisarm
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'browsedata' => 'Răsfoiți data',
	'sd_browsedata_choosecategory' => 'Alegeți o categorie',
	'sd_browsedata_viewcategory' => 'vedeți categoria',
	'sd_browsedata_subcategory' => 'Subcategorie',
	'sd_browsedata_other' => 'Altul',
	'sd_browsedata_none' => 'Nimic',
	'sd_browsedata_filterbyvalue' => 'Filtrează după această valoare',
	'sd_browsedata_filterbysubcategory' => 'Filtrează după această subcategorie',
	'sd_browsedata_otherfilter' => 'Arată paginile cu o altă valoare pentru acest filtru',
	'sd_browsedata_nonefilter' => 'Arată paginile cu nicio valoare pentru acest filtru',
	'sd_browsedata_or' => 'sau',
	'sd_browsedata_removefilter' => 'Elimină acest filtru',
	'sd_browsedata_removesubcategoryfilter' => 'Elimină acest filtru de subcategorie',
	'sd_browsedata_resetfilters' => 'Resetați filtrele',
	'sd_browsedata_addanothervalue' => 'Adaugă altă valoare', # Fuzzy
	'sd_browsedata_daterangestart' => 'Început:',
	'sd_browsedata_daterangeend' => 'Sfârșit:',
	'sd_browsedata_novalues' => 'Nu există valori pentru acest filtru',
	'filters' => 'Filtre',
	'sd_filters_docu' => 'Următoarele filtre există la {{SITENAME}}:',
	'sd_formcreate' => 'Creare cu formular',
	'sd_viewform' => 'Vizualizare formular',
	'createfilter' => 'Creați un filtru',
	'sd_createfilter_name' => 'Nume:',
	'sd_blank_error' => 'nu poate fi gol',
	'sd-pageschemas-filter' => 'Filtru',
	'sd-pageschemas-values' => 'Valori',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'sd_browsedata_choosecategory' => "Scacchie 'na categorije",
	'sd_browsedata_viewcategory' => "vide 'a categorije",
	'sd_browsedata_docu' => 'Cazze sus a une o cchiù vôsce aqquà sotte pe stringere le resultate tune.',
	'sd_browsedata_subcategory' => 'Sotte Categorije',
	'sd_browsedata_other' => 'Otre',
	'sd_browsedata_none' => 'Ninde',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_daterangestart' => 'Accumenze:',
	'sd_browsedata_daterangeend' => 'Spiccie:',
	'filters' => 'Filtre',
	'createfilter' => "Ccreje 'nu filtre",
	'sd-createfilter-with-name' => "Ccreje 'u filtre: $1",
	'sd_createfilter_name' => 'Nome:',
	'sd-pageschemas-filter' => 'Filtre',
	'sd-pageschemas-values' => 'Valore',
);

/** Russian (русский)
 * @author Ferrer
 * @author Innv
 * @author Lockal
 * @author Okras
 * @author Александр Сигачёв
 */
$messages['ru'] = array(
	'semanticdrilldown-desc' => 'Развёрнутый интерфейс для навигации в семантических данных',
	'specialpages-group-sd_group' => 'Развёрнутая семантика',
	'browsedata' => 'Обзор данных',
	'sd_browsedata_choosecategory' => 'Выберите категорию',
	'sd_browsedata_viewcategory' => 'просмотр категории',
	'sd_browsedata_docu' => 'Нажмите на одном или больше элементов для уменьшения ваших результатов.',
	'sd_browsedata_subcategory' => 'Подкатегория',
	'sd_browsedata_other' => 'Другие',
	'sd_browsedata_none' => 'Нет',
	'sd_browsedata_filterbyvalue' => 'Фильтр по этому значению',
	'sd_browsedata_filterbysubcategory' => 'Фильтр по этой подкатегории',
	'sd_browsedata_otherfilter' => 'Показать страницы с другими значениями по этому фильтру',
	'sd_browsedata_nonefilter' => 'Показать страницы без значений по этому фильтру',
	'sd_browsedata_or' => 'или',
	'sd_browsedata_removefilter' => 'Убрать этот фильтр',
	'sd_browsedata_removesubcategoryfilter' => 'Убрать этот фильтр по подкатегории',
	'sd_browsedata_resetfilters' => 'Сбросить фильтры',
	'sd_browsedata_addanothervalue' => 'Нажмите на стрелку, чтобы добавить другое значение',
	'sd_browsedata_daterangestart' => 'Начало:',
	'sd_browsedata_daterangeend' => 'Конец:',
	'sd_browsedata_novalues' => 'Нет значений для этого фильтра',
	'filters' => 'Фильтры',
	'sd_filters_docu' => '{{SITENAME}} содержит следующие фильтры:',
	'sd_formcreate' => 'Создать с формой',
	'sd_viewform' => 'Смотреть форму',
	'createfilter' => 'Создать фильтр',
	'sd-createfilter-with-name' => 'Создать фильтр: $1',
	'sd_createfilter_name' => 'Имя:',
	'sd_createfilter_property' => 'Свойство, которое покрывает этот фильтр:',
	'sd_createfilter_usepropertyvalues' => 'Использовать все значения этого свойства для фильтра',
	'sd_createfilter_usecategoryvalues' => 'Получить значения для фильтра из этой категории:',
	'sd_createfilter_requirefilter' => 'Требовать выбора другого фильтра, перед тем, как отображать этот:',
	'sd_createfilter_label' => 'Пометка для этого фильтра (необязательно):',
	'sd_blank_error' => 'не может быть пустым',
	'sd-pageschemas-filter' => 'Фильтр',
	'sd-pageschemas-values' => 'Значения',
	'sd_filter_coversproperty' => 'Этот фильтр покрывает свойство $1.',
	'sd_filter_getsvaluesfromcategory' => 'Получает свои значения из категории $1.',
	'sd_filter_requiresfilter' => 'Требует наличия фильтра $1.',
	'sd_filter_haslabel' => 'Имеет пометку $1.',
);

/** Rusyn (русиньскый)
 * @author Gazeb
 */
$messages['rue'] = array(
	'sd_browsedata_choosecategory' => 'Выбрати катеґорію',
	'sd_browsedata_viewcategory' => 'видїти катеґорію',
	'sd_browsedata_subcategory' => 'Підкатегорія',
	'sd_browsedata_other' => 'Інше',
	'sd_browsedata_none' => 'Жадне',
);

/** Sinhala (සිංහල)
 * @author Singhalawap
 * @author පසිඳු කාවින්ද
 */
$messages['si'] = array(
	'specialpages-group-sd_group' => 'අර්ථ විචාර විදුම්බූව',
	'browsedata' => 'දත්ත විමසන්න',
	'sd_browsedata_choosecategory' => 'ප්‍රවර්ගයක් තෝරාගන්න',
	'sd_browsedata_viewcategory' => 'ප්‍රවර්ගය නරඹන්න',
	'sd_browsedata_subcategory' => 'උපප්‍රවර්ගය',
	'sd_browsedata_other' => 'වෙනත්',
	'sd_browsedata_none' => 'කිසිවක් නොමැත',
	'sd_browsedata_filterbyvalue' => 'මෙම අගය මත පෙරන්න',
	'sd_browsedata_filterbysubcategory' => 'මෙම උපප්‍රවර්ගය මත පෙරන්න',
	'sd_browsedata_or' => 'හෝ',
	'sd_browsedata_removefilter' => 'මෙම පෙරහන ඉවත් කරන්න',
	'sd_browsedata_removesubcategoryfilter' => 'මෙම උපප්‍රවර්ග පෙරහන ඉවත් කරන්න',
	'sd_browsedata_resetfilters' => 'පෙරහන් නැවත සකසන්න',
	'sd_browsedata_daterangestart' => 'ඇරඹුම:',
	'sd_browsedata_daterangeend' => 'අවසානය:',
	'sd_browsedata_novalues' => 'මෙම පෙරහන සඳහා අගයන් කිසිවක් නැත',
	'filters' => 'පෙරහන්',
	'sd_filters_docu' => '{{SITENAME}} හී පහත පෙරහන් පවතී:',
	'sd_formcreate' => 'ෆෝමය සමග තනන්න',
	'sd_viewform' => 'ෆෝමය (form)බලන්න',
	'createfilter' => 'පෙරහනක් තනන්න',
	'sd-createfilter-with-name' => 'පෙරහන තනන්න: $1',
	'sd_createfilter_name' => 'නම:',
	'sd_createfilter_property' => 'මෙම පෙරහන ආරක්ෂා කරන වත්කම:',
	'sd_createfilter_usecategoryvalues' => 'පෙරහන සඳහා අගයන් මෙම ප්‍රවර්ගයෙන් ලබා ගන්න:',
	'sd_createfilter_label' => 'මෙම පෙරහන සඳහා ලේබලය (වෛකල්පික):',
	'sd_blank_error' => 'හිස් නොවිය යුතුය',
	'sd-pageschemas-filter' => 'පෙරහන',
	'sd-pageschemas-values' => 'අගයන්',
	'sd_filter_haslabel' => 'එය සතුව $1 ලේබලය ඇත.',
);

/** Slovak (slovenčina)
 * @author Helix84
 * @author Teslaton
 */
$messages['sk'] = array(
	'semanticdrilldown-desc' => 'Rozhranie na navigáciu sémantickými údajmi',
	'specialpages-group-sd_group' => 'Sémantické operácie',
	'browsedata' => 'Prehliadať údaje',
	'sd_browsedata_choosecategory' => 'Vyberte kategóriu',
	'sd_browsedata_viewcategory' => 'zobraziť kategóriu',
	'sd_browsedata_docu' => 'Výsledky môžete zúžiť kliknutím na jednu alebo viac dolu uvedených položiek.',
	'sd_browsedata_subcategory' => 'Podkategória',
	'sd_browsedata_other' => 'Iné',
	'sd_browsedata_none' => 'Žiadne',
	'sd_browsedata_filterbyvalue' => 'Filtrovať podľa tejto hodnoty',
	'sd_browsedata_filterbysubcategory' => 'Filtrovať podľa tejto podkategórie',
	'sd_browsedata_otherfilter' => 'Zobraziť stránky s inou hodnotou tohto filtra',
	'sd_browsedata_nonefilter' => 'Zobraziť stránky s bez hodnoty tohto filtra',
	'sd_browsedata_or' => 'alebo',
	'sd_browsedata_removefilter' => 'Odstrániť tento filter',
	'sd_browsedata_removesubcategoryfilter' => 'Odstrániť tento filter podkategórie',
	'sd_browsedata_resetfilters' => 'Resetovať filtre',
	'sd_browsedata_addanothervalue' => 'Ďalšiu hodnotu pridáte kliknutím na šípku',
	'sd_browsedata_daterangestart' => 'Začiatok:',
	'sd_browsedata_daterangeend' => 'Koniec:',
	'sd_browsedata_novalues' => 'Pre tento filter nie sú žiadne hodnoty',
	'filters' => 'Filtre',
	'sd_filters_docu' => 'Na {{GRAMMAR:lokál|{{SITENAME}}}} existujú nasledovné filtre:',
	'sd_formcreate' => 'Vytvoriť s formulárom',
	'sd_viewform' => 'Zobraziť formulár',
	'createfilter' => 'Vytvoriť filter',
	'sd_createfilter_name' => 'Názov:',
	'sd_createfilter_property' => 'Vlastnosť, ktorú tento filter pokrýva:',
	'sd_createfilter_usepropertyvalues' => 'Použiť všetky hodnoty tejto vlastnosti pre tento filter',
	'sd_createfilter_usecategoryvalues' => 'Získať hodnoty filtra z tejto kategórie:',
	'sd_createfilter_requirefilter' => 'Vyžadovať, aby bol vybraný iný filter než sa zobrazí tento:',
	'sd_createfilter_label' => 'Označenie tohto filtra (voliteľné):',
	'sd_blank_error' => 'nemôže byť nevyplnené',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Hodnoty',
	'sd_filter_coversproperty' => 'Tento filter pokrýva vlastnosť $1.',
	'sd_filter_getsvaluesfromcategory' => 'Získava hodnoty z kategórie $1.',
	'sd_filter_requiresfilter' => 'Vyžaduje prítomnosť filtra $1.',
	'sd_filter_haslabel' => 'Má označenie $1.',
);

/** Slovenian (slovenščina)
 * @author Dbc334
 */
$messages['sl'] = array(
);

/** Serbian (Cyrillic script) (српски (ћирилица)‎)
 * @author Rancher
 * @author Sasa Stefanovic
 * @author Михајло Анђелковић
 */
$messages['sr-ec'] = array(
	'sd_browsedata_choosecategory' => 'Изабери категорију',
	'sd_browsedata_viewcategory' => 'погледај категорију',
	'sd_browsedata_subcategory' => 'Поткатегорија',
	'sd_browsedata_other' => 'Друго',
	'sd_browsedata_none' => 'Нема',
	'sd_browsedata_or' => 'или',
	'sd_browsedata_daterangestart' => 'Почетак:',
	'sd_browsedata_daterangeend' => 'Крај:',
	'filters' => 'Филтери',
	'sd_createfilter_name' => 'Име:',
);

/** Serbian (Latin script) (srpski (latinica)‎)
 * @author Michaello
 * @author Rancher
 */
$messages['sr-el'] = array(
	'sd_browsedata_choosecategory' => 'Izaberi kategoriju',
	'sd_browsedata_viewcategory' => 'pogledaj kategoriju',
	'sd_browsedata_subcategory' => 'Potkategorija',
	'sd_browsedata_other' => 'Drugo',
	'sd_browsedata_none' => 'Nema',
	'sd_browsedata_or' => 'ili',
	'sd_browsedata_daterangestart' => 'Početak:',
	'sd_browsedata_daterangeend' => 'Kraj:',
	'filters' => 'Filteri',
	'sd_createfilter_name' => 'Ime:',
);

/** Seeltersk (Seeltersk)
 * @author Pyt
 */
$messages['stq'] = array(
	'semanticdrilldown-desc' => 'Ne Drilldown-Benutsersnitsteede, uum truch semantiske Doaten tou navigierjen',
	'specialpages-group-sd_group' => 'Semantisk Drill-Down',
	'browsedata' => 'Doaten bekiekje',
	'sd_browsedata_choosecategory' => 'Wääl ne Kategorie',
	'sd_browsedata_viewcategory' => 'Kategorie bekiekje',
	'sd_browsedata_docu' => 'Klik ap een of moorere fon do Sieuwen uum dät Resultoat ientoutuunjen.',
	'sd_browsedata_subcategory' => 'Unnerkategorie',
	'sd_browsedata_other' => 'Uur',
	'sd_browsedata_none' => 'Neen',
	'sd_browsedata_filterbyvalue' => 'Sieuwe foar dissen Wäid',
	'sd_browsedata_filterbysubcategory' => 'Sieuwe foar disse Subkategorie',
	'sd_browsedata_otherfilter' => 'Wies Sieden mäd n uur Wäid foar disse Sieuwe',
	'sd_browsedata_nonefilter' => 'Wies Sieden mäd naan Wäid foar disse Sieuwe',
	'sd_browsedata_or' => 'of',
	'sd_browsedata_removefilter' => 'Läskje disse Sieuwe',
	'sd_browsedata_removesubcategoryfilter' => 'Läskje disse Subkategorie-Sieuwe',
	'sd_browsedata_resetfilters' => 'Sieuwen touräächsätte',
	'sd_browsedata_addanothervalue' => 'Uur Wäid bietouföigje', # Fuzzy
	'sd_browsedata_daterangestart' => 'Ounfang:',
	'sd_browsedata_daterangeend' => 'Eende:',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Do foulgjende Sieuwen existierje in disse Wiki:',
	'createfilter' => 'Moak ne Sieuwe',
	'sd_createfilter_name' => 'Noome:',
	'sd_createfilter_property' => 'Attribut fon disse Sieuwe:',
	'sd_createfilter_usepropertyvalues' => 'Ferweend aal Wäide fon dit Attribut foar ju Sieuwe.',
	'sd_createfilter_usecategoryvalues' => 'Ferweend do Wäide foar ju Sieuwe fon disse Kategorie:',
	'sd_createfilter_requirefilter' => 'Eer disse Sieuwe anwiesd wäd, mout foulgjende uur Sieuwe sät weese:',
	'sd_createfilter_label' => 'Beteekenge fon disse Sieuwe (optionoal):',
	'sd_blank_error' => 'duur nit loos weese',
	'sd_filter_coversproperty' => 'Disse Sieuwe beträft dät Attribut $1.',
	'sd_filter_getsvaluesfromcategory' => 'Hie kricht sien Wäide uut ju Kategorie $1.',
	'sd_filter_requiresfilter' => 'Hie sät ju Sieuwe $1 foaruut.',
	'sd_filter_haslabel' => 'Häd ju Beteekenge $1.',
);

/** Swedish (svenska)
 * @author M.M.S.
 * @author Martinwiss
 * @author Per
 */
$messages['sv'] = array(
	'semanticdrilldown-desc' => 'Ett gränssnitt för att navigera sig igenom semantiska data',
	'specialpages-group-sd_group' => 'Semantic Drilldown',
	'browsedata' => 'Bläddra genom data',
	'sd_browsedata_choosecategory' => 'Välj en kategori',
	'sd_browsedata_viewcategory' => 'visa kategori',
	'sd_browsedata_docu' => 'Klocka på minst ett av flera val här nedanför för att avgränsa ditt sökresultat',
	'sd_browsedata_subcategory' => 'Subkategori',
	'sd_browsedata_other' => 'Andra',
	'sd_browsedata_none' => 'Ingen',
	'sd_browsedata_filterbyvalue' => 'Filtrera efter det här värdet',
	'sd_browsedata_filterbysubcategory' => 'Filtrera efter den här underkategorin',
	'sd_browsedata_otherfilter' => 'Visa sidor med ett annat värde för det här filtret',
	'sd_browsedata_nonefilter' => 'Visa sidor utan några värden för detta filter',
	'sd_browsedata_or' => 'eller',
	'sd_browsedata_removefilter' => 'Ta bort detta filter',
	'sd_browsedata_removesubcategoryfilter' => 'Ta bort detta underkategorifiltret',
	'sd_browsedata_resetfilters' => 'Återställ filter',
	'sd_browsedata_addanothervalue' => 'Klicka på pilen för att lägga till ytterligare värde',
	'sd_browsedata_daterangestart' => 'Start:',
	'sd_browsedata_daterangeend' => 'Slut:',
	'sd_browsedata_novalues' => 'Det finns inga värden för detta filter',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Följande filter finns på {{SITENAME}}:',
	'sd_formcreate' => 'Skapa med formulär',
	'sd_viewform' => 'Visa formulär',
	'createfilter' => 'Skapa ett filter',
	'sd-createfilter-with-name' => 'Skapa filter: $1',
	'sd_createfilter_name' => 'Namn:',
	'sd_createfilter_property' => 'Egenskaper som detta filter döljer:',
	'sd_createfilter_usepropertyvalues' => 'Använd alla värden av den här egenskapen för filtret',
	'sd_createfilter_usecategoryvalues' => 'Hämta värden för filtret från den här kategorin:',
	'sd_createfilter_requirefilter' => 'Kräv att ett annat filter väljs före detta visas:',
	'sd_createfilter_label' => 'Etikett för det här filtret (valfritt):',
	'sd_blank_error' => 'kan inte vara tom',
	'sd-pageschemas-filter' => 'Filter',
	'sd-pageschemas-values' => 'Värden',
	'sd_filter_coversproperty' => 'Detaa filter döljer egenskapen $1.',
	'sd_filter_getsvaluesfromcategory' => 'Det får sina värden från kategorin $1.',
	'sd_filter_requiresfilter' => 'Det kräver att filtret $1 är på plats.',
	'sd_filter_haslabel' => 'Det har etiketten $1.',
);

/** Swahili (Kiswahili)
 * @author Stephenwanjau
 */
$messages['sw'] = array(
	'browsedata' => 'Vinjari takwimu',
	'sd_browsedata_choosecategory' => 'Chagua jamii',
	'sd_browsedata_viewcategory' => 'tazama jamii',
	'sd_browsedata_or' => 'au',
	'sd_browsedata_daterangestart' => 'Anza:',
	'sd_viewform' => 'Tazama fomu',
	'sd_createfilter_name' => 'Jina:',
);

/** Silesian (ślůnski)
 * @author Herr Kriss
 */
$messages['szl'] = array(
	'sd_createfilter_name' => 'Mjano:',
);

/** Tamil (தமிழ்)
 * @author Karthi.dr
 * @author Shanmugamp7
 */
$messages['ta'] = array(
	'sd_browsedata_choosecategory' => 'ஒரு பகுப்பைத் தேர்ந்தெடுக்கவும்',
	'sd_browsedata_viewcategory' => 'பகுப்பைக் காண்',
	'sd_browsedata_docu' => 'உங்கள் முடிவுகளை குறுக்க கீழே ஒன்று அல்லது அதற்கு மேற்பட்ட உருப்படிகளை சொடுக்கவும்',
	'sd_browsedata_subcategory' => 'துணைப் பகுப்பு',
	'sd_browsedata_other' => 'மற்றவை',
	'sd_browsedata_none' => 'எதுவுமில்லை',
	'sd_browsedata_filterbyvalue' => 'இந்த மதிப்பைப் பொறுத்து வடிகட்டு',
	'sd_browsedata_filterbysubcategory' => 'இந்த துணைப் பகுப்பைப் பொறுத்து வடிகட்டு',
	'sd_browsedata_otherfilter' => 'இந்த வடிகட்டியின் மற்றொரு மதிப்புடன் கூடிய பக்கங்களைக் காண்பி',
	'sd_browsedata_nonefilter' => 'இந்த் வடிகட்டிக்கு மதிப்பு இல்லாத பக்கங்களைக் காண்பி',
	'sd_browsedata_or' => 'அல்லது',
	'sd_browsedata_removefilter' => 'இந்த வடிகட்டியை நீக்கு',
	'sd_browsedata_removesubcategoryfilter' => 'இந்த துணைப்பகுப்பு வடிகட்டியை நீக்கு',
	'sd_browsedata_resetfilters' => 'வடிகட்டிகளை மீட்டமை',
	'sd_browsedata_daterangestart' => 'தொடக்கம்:',
	'sd_browsedata_daterangeend' => 'இறுதி:',
	'sd_browsedata_novalues' => 'இந்த வடிகட்டிக்கு மதிப்பு எதுவுமில்லை',
	'filters' => 'வடிகட்டிகள்',
	'createfilter' => 'ஒரு வடிகட்டியை உருவாக்கவும்',
	'sd-createfilter-with-name' => 'வடிகட்டியை உருவாக்கு :$1',
	'sd_createfilter_name' => 'பெயர்:',
	'sd-pageschemas-filter' => 'வடிகட்டி',
	'sd-pageschemas-values' => 'மதிப்புகள்',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'browsedata' => 'భోగట్టాని చూడండి',
	'sd_browsedata_choosecategory' => 'ఓ వర్గాన్ని ఎంచుకోండి',
	'sd_browsedata_viewcategory' => 'వర్గాన్ని చూడండి',
	'sd_browsedata_subcategory' => 'ఉపవర్గం',
	'sd_browsedata_other' => 'ఇతర',
	'sd_browsedata_none' => 'ఏమీలేదు',
	'sd_browsedata_or' => 'లేదా',
	'sd_browsedata_removefilter' => 'ఈ వడపోతని తొలగించు',
	'sd_browsedata_addanothervalue' => 'మరో విలువని చేర్చండి', # Fuzzy
	'sd_browsedata_daterangestart' => 'మొదలు:',
	'sd_browsedata_daterangeend' => 'ముగింపు:',
	'sd_browsedata_novalues' => 'ఈ వడపోతకి విలువలు ఏమీ లేవు',
	'filters' => 'వడపోతలు',
	'sd_filters_docu' => '{{SITENAME}}లో ఈ క్రింది వడపోతలు ఉన్నాయి:',
	'sd_createfilter_name' => 'పేరు:',
	'sd_createfilter_usecategoryvalues' => 'వడపోతకి విలువలని ఈ వర్గంనుండి తీసుకోవాలి:',
	'sd_blank_error' => 'ఖాళీగా ఉండకూడదు',
	'sd-pageschemas-values' => 'విలువలు',
);

/** Tetum (tetun)
 * @author MF-Warburg
 */
$messages['tet'] = array(
	'sd_browsedata_other' => 'Seluk',
	'sd_createfilter_name' => 'Naran:',
);

/** Tajik (Cyrillic script) (тоҷикӣ)
 * @author Ibrahim
 */
$messages['tg-cyrl'] = array(
	'browsedata' => 'Мурури дода',
	'sd_browsedata_choosecategory' => 'Гурӯҳеро интихоб кунед',
	'sd_browsedata_viewcategory' => 'нигаристан гурӯҳ',
	'sd_browsedata_subcategory' => 'Зергурӯҳ',
	'sd_browsedata_other' => 'Дигар',
	'sd_browsedata_none' => 'Ҳеҷ',
	'sd_browsedata_or' => 'ё',
	'sd_browsedata_daterangestart' => 'Шурӯъ:',
	'sd_browsedata_daterangeend' => 'Охир:',
	'filters' => 'Филтрҳо',
	'sd_createfilter_name' => 'Ном:',
	'sd_createfilter_requirefilter' => 'Қабл аз намоиши ин яке, филтри дигар бояд интихоб шавад:',
	'sd_createfilter_label' => 'Барчасб барои ин филтр (ихтиёрӣ):',
	'sd_blank_error' => 'наметавонад холӣ бошад',
	'sd_filter_coversproperty' => 'Ин филтр вижагии $1-ро шомил мешавад.',
	'sd_filter_getsvaluesfromcategory' => 'Миқдорҳояшро аз гурӯҳи $1 мегирад.',
	'sd_filter_requiresfilter' => 'Ба вуҷуди филтри $1 эҳтиёҷ дорад.',
	'sd_filter_haslabel' => 'Ин барчасби $1 дорад.',
);

/** Tajik (Latin script) (tojikī)
 * @author Liangent
 */
$messages['tg-latn'] = array(
	'browsedata' => 'Mururi doda',
	'sd_browsedata_choosecategory' => 'Gurūhero intixob kuned',
	'sd_browsedata_viewcategory' => 'nigaristan gurūh',
	'sd_browsedata_subcategory' => 'Zergurūh',
	'sd_browsedata_other' => 'Digar',
	'sd_browsedata_none' => 'Heç',
	'sd_browsedata_or' => 'jo',
	'sd_browsedata_daterangestart' => "Şurū':",
	'sd_browsedata_daterangeend' => 'Oxir:',
	'filters' => 'Filtrho',
	'sd_createfilter_name' => 'Nom:',
	'sd_createfilter_requirefilter' => 'Qabl az namoişi in jake, filtri digar bojad intixob şavad:',
	'sd_createfilter_label' => 'Barcasb baroi in filtr (ixtijorī):',
	'sd_blank_error' => 'nametavonad xolī boşad',
	'sd_filter_coversproperty' => 'In filtr viƶagiji $1-ro şomil meşavad.',
	'sd_filter_getsvaluesfromcategory' => 'Miqdorhojaşro az gurūhi $1 megirad.',
	'sd_filter_requiresfilter' => 'Ba vuçudi filtri $1 ehtijoç dorad.',
	'sd_filter_haslabel' => 'In barcasbi $1 dorad.',
);

/** Thai (ไทย)
 * @author Octahedron80
 * @author Passawuth
 */
$messages['th'] = array(
	'sd_browsedata_none' => 'ไม่มี',
	'filters' => 'ตัวกรอง',
);

/** Turkmen (Türkmençe)
 * @author Hanberke
 */
$messages['tk'] = array(
	'filters' => 'Filtrler',
	'sd_createfilter_name' => 'At:',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'semanticdrilldown-desc' => 'Isang pambutas ("pambarena") na ugnayang-hangganan para sa paglilibot sa kahabaan ng datong semantiko (hinggil sa kahulugan ng salita)',
	'specialpages-group-sd_group' => 'Semantikong Pababang Pagbubutas ("Pagbabarena")',
	'browsedata' => 'Tumingin-tingin sa dato',
	'sd_browsedata_choosecategory' => 'Pumili ng isang kaurian (kategorya)',
	'sd_browsedata_viewcategory' => 'tingnan ang kaurian',
	'sd_browsedata_docu' => 'Pindutin ang isa o mahigit pang mga bagay na nasa ibaba upang mapakipot/mapakaunti pa ang mga kinalabasan.',
	'sd_browsedata_subcategory' => 'Kabahaging kaurian',
	'sd_browsedata_other' => 'Iba pa',
	'sd_browsedata_none' => 'Wala',
	'sd_browsedata_filterbyvalue' => 'Salain sa pamamagitan ng ganitong halaga',
	'sd_browsedata_filterbysubcategory' => 'Salain sa pamamagitan ng ganitong kabahaging kaurian',
	'sd_browsedata_otherfilter' => 'Ipakita ang mga pahinang may iba pang halaga para sa ganitong pansala',
	'sd_browsedata_nonefilter' => 'Ipakita ang mga pahinang walang halaga para sa pansalang ito',
	'sd_browsedata_or' => 'o',
	'sd_browsedata_removefilter' => 'Tanggalin ang pansalang ito',
	'sd_browsedata_removesubcategoryfilter' => 'Tanggalin ang ganitong pansala ng kabahaging kaurian',
	'sd_browsedata_resetfilters' => 'Muling itakda ang mga pansala',
	'sd_browsedata_addanothervalue' => 'Pindutin ang palaso upang makapagdagdag ng ibang halaga',
	'sd_browsedata_daterangestart' => 'Simula:',
	'sd_browsedata_daterangeend' => 'Wakas:',
	'sd_browsedata_novalues' => 'Walang mga halaga para sa pansalang ito',
	'filters' => 'Mga pansala',
	'sd_filters_docu' => 'Umiiral ang sumusunod na mga pansala sa loob ng {{SITENAME}}:',
	'sd_formcreate' => 'Likhain na mayroong pormularyo',
	'sd_viewform' => 'Tingnan ang pormularyo',
	'createfilter' => 'Lumikha ng isang pansala',
	'sd-createfilter-with-name' => 'Likhain ang pansala na: $1',
	'sd_createfilter_name' => 'Pangalan:',
	'sd_createfilter_property' => 'Pag-aaring nasasakop ng pansalang ito:',
	'sd_createfilter_usepropertyvalues' => 'Gamitin ang lahat ng mga halaga ng pag-aaring ito para sa pansalang ito',
	'sd_createfilter_usecategoryvalues' => 'Kumuha ng mga halaga para sa pansala mula sa kauriang ito:',
	'sd_createfilter_requirefilter' => 'Pilitin ang iba pang pansala na mapili bago ipakita ang isang ito:',
	'sd_createfilter_label' => 'Tatak para pansalang ito (maaaring wala nito):',
	'sd_blank_error' => 'hindi maaaring walang laman/patlang',
	'sd-pageschemas-filter' => 'Pansala',
	'sd-pageschemas-values' => 'Mga halaga',
	'sd_filter_coversproperty' => 'Nasasakop ng pansalang ito ang ari-ariang $1.',
	'sd_filter_getsvaluesfromcategory' => 'Kumukuha ito ng sarili niyang mga halaga mula sa kauriang $1.',
	'sd_filter_requiresfilter' => 'Kinakailangan nito ang pagkakaroon ng pansalang $1.',
	'sd_filter_haslabel' => 'Mayroon itong tatak na $1.',
);

/** Turkish (Türkçe)
 * @author Karduelis
 * @author Suelnur
 * @author Vito Genovese
 */
$messages['tr'] = array(
	'browsedata' => 'Verilere gözat',
	'sd_browsedata_choosecategory' => 'Bir kategori seç',
	'sd_browsedata_viewcategory' => 'kategoriyi gör',
	'sd_browsedata_subcategory' => 'Alt kategori',
	'sd_browsedata_other' => 'Diğer',
	'sd_browsedata_none' => 'Hiçbiri',
	'sd_browsedata_removefilter' => 'Bu süzgeci kaldır',
	'sd_browsedata_removesubcategoryfilter' => 'Bu alt kategori filtresini kaldır',
	'sd_browsedata_resetfilters' => 'Filtreleri sıfırla',
	'sd_browsedata_daterangestart' => 'Başlangıç:',
	'sd_browsedata_daterangeend' => 'Bitiş:',
	'sd_browsedata_novalues' => 'Bu filtre için değer yok',
	'filters' => 'Filtreler',
	'sd_viewform' => 'Formu gör',
	'createfilter' => 'Bir filtre oluştur',
	'sd_createfilter_name' => 'İsim:',
	'sd_createfilter_property' => 'Bu filtrenin kapsadığı özellik:',
	'sd_createfilter_label' => 'Bu filtre için etiket (opsiyonel):',
	'sd-pageschemas-filter' => 'Süzgeç',
);

/** Central Atlas Tamazight (ⵜⴰⵎⴰⵣⵉⵖⵜ)
 * @author Tifinaghes
 */
$messages['tzm'] = array(
	'sd_browsedata_or' => 'ⵏⵖ',
	'sd_browsedata_daterangestart' => 'ⵜⴰⵣⵡⴰⵔⴰ:',
	'sd_browsedata_daterangeend' => 'ⵜⴰⴳⴰⵔⴰ:',
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 * @author Base
 * @author Steve.rusyn
 * @author SteveR
 * @author Тест
 */
$messages['uk'] = array(
	'semanticdrilldown-desc' => 'Розгорнутий інтерфейс для навігації у семантичних даних',
	'specialpages-group-sd_group' => 'Семантична розгорненість',
	'browsedata' => 'Огляд даних',
	'sd_browsedata_choosecategory' => 'Виберіть категорію',
	'sd_browsedata_viewcategory' => 'перегляд категорії',
	'sd_browsedata_docu' => 'Натистіть на один або більше елементів нижче для обмеження результатів.',
	'sd_browsedata_subcategory' => 'Підкатегорія',
	'sd_browsedata_other' => 'Інші',
	'sd_browsedata_none' => 'Немає',
	'sd_browsedata_filterbyvalue' => 'Фільтрувати за цим значенням',
	'sd_browsedata_filterbysubcategory' => 'Фільтрувати за цією підкатегорією',
	'sd_browsedata_otherfilter' => 'Показати сторінки з іншими значеннями за цим фільтром',
	'sd_browsedata_nonefilter' => 'Показати сторінки без значень за цим фільтром',
	'sd_browsedata_or' => 'або',
	'sd_browsedata_removefilter' => 'Вилучити цей фільтр',
	'sd_browsedata_removesubcategoryfilter' => 'Вилучити цей фільтр за підкатегорією',
	'sd_browsedata_resetfilters' => 'Скинути фільтри',
	'sd_browsedata_addanothervalue' => 'Натисніть на стрілку, щоб додати інше значення',
	'sd_browsedata_daterangestart' => 'Початок:',
	'sd_browsedata_daterangeend' => 'Кінець:',
	'sd_browsedata_novalues' => 'Немає значень для цього фільтра',
	'filters' => 'Фільтри',
	'sd_filters_docu' => '{{SITENAME}} містить наступні фільтри:',
	'sd_formcreate' => 'Створити з формою',
	'sd_viewform' => 'Переглянути форму',
	'createfilter' => 'Створити фільтр',
	'sd-createfilter-with-name' => 'Створити фільтр: $1',
	'sd_createfilter_name' => 'Назва:',
	'sd_createfilter_property' => 'Властивість, яку цей фільтр покриває:',
	'sd_createfilter_usepropertyvalues' => 'Використовуйте усі значення цієї властивості для фільтру',
	'sd_createfilter_usecategoryvalues' => 'Отримати значення для фільтру із цієї категорії:',
	'sd_createfilter_requirefilter' => 'Вимагати вибору іншого фільтра, перед тим, як відображати цей:',
	'sd_createfilter_label' => "Позначка для цього фільтра (необов'язково):",
	'sd_blank_error' => 'не може бути порожнім',
	'sd-pageschemas-filter' => 'Фільтр',
	'sd-pageschemas-values' => 'Значення',
	'sd_filter_coversproperty' => 'Цей фільтр охоплює властивість  $1.',
	'sd_filter_getsvaluesfromcategory' => 'Отримує свої значення з категорії $1.',
	'sd_filter_requiresfilter' => 'Вимагає наявності фільтру $1.',
	'sd_filter_haslabel' => 'Має позначку  $1.',
);

/** Urdu (اردو)
 * @author පසිඳු කාවින්ද
 */
$messages['ur'] = array(
	'sd_browsedata_choosecategory' => 'زمرہ انتخاب کریں',
	'sd_browsedata_viewcategory' => 'قول زمرہ',
	'sd_browsedata_other' => 'دیگر',
	'sd_browsedata_none' => 'کوئی بھی نہیں',
	'sd_browsedata_or' => 'یا',
	'sd_browsedata_removefilter' => 'اس فلٹر حذف کریں',
	'sd_browsedata_resetfilters' => 'فلٹر کو دوبارہ مرتب کریں',
	'sd_browsedata_daterangestart' => 'شروع:',
	'sd_browsedata_daterangeend' => 'ديكھيں:',
	'sd_browsedata_novalues' => 'وہاں کوئی اقدار اس فلٹر کریں',
	'filters' => 'فلٹر',
	'sd_viewform' => 'قول فارم',
	'createfilter' => 'ایک فلٹر تخلیق کریں',
	'sd_createfilter_name' => 'نام:',
	'sd-pageschemas-filter' => 'فلٹر کریں',
	'sd-pageschemas-values' => 'اقدار',
);

/** Veps (vepsän kel’)
 * @author Игорь Бродский
 */
$messages['vep'] = array(
	'sd_browsedata_viewcategory' => 'nähta kategorii',
	'sd_browsedata_other' => 'Toižed',
	'sd_browsedata_none' => 'Ei ole',
	'sd_browsedata_or' => 'vai',
	'sd_browsedata_daterangestart' => 'Aug:',
	'sd_browsedata_daterangeend' => 'Lop:',
	'filters' => "Fil'trad",
	'sd_filters_docu' => "{{SITENAME}}-wikiš om ningoižed fil'troid:",
	'createfilter' => "Säta fil'tr",
	'sd_createfilter_name' => 'Nimi:',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 * @author Vinhtantran
 * @author පසිඳු කාවින්ද
 */
$messages['vi'] = array(
	'browsedata' => 'Duyệt dữ liệu',
	'sd_browsedata_choosecategory' => 'Chọn một thể loại',
	'sd_browsedata_viewcategory' => 'xem thể loại',
	'sd_browsedata_subcategory' => 'Thể loại con',
	'sd_browsedata_other' => 'Khác',
	'sd_browsedata_none' => 'Không có',
	'sd_browsedata_filterbyvalue' => 'Lọc theo giá trị này',
	'sd_browsedata_filterbysubcategory' => 'Lọc theo thể loại con này',
	'sd_browsedata_otherfilter' => 'Hiển thị trang với giá trị khác cho bộ lọc này',
	'sd_browsedata_nonefilter' => 'Hiển thị không có giá trị nào đối với bộ lọc này',
	'sd_browsedata_or' => 'hoặc',
	'sd_browsedata_removefilter' => 'Bỏ bộ lọc này',
	'sd_browsedata_removesubcategoryfilter' => 'Bỏ bộ lọc thể loại con này',
	'sd_browsedata_resetfilters' => 'Tái tạo bộ lọc',
	'sd_browsedata_addanothervalue' => 'Nhấn chuột vào tên mũi để thêm giá trị khác',
	'sd_browsedata_daterangestart' => 'Bắt đầu:',
	'sd_browsedata_daterangeend' => 'Kết thúc:',
	'filters' => 'Bộ lọc',
	'sd_filters_docu' => 'Bộ lọc sau tồn tại trong {{SITENAME}}:',
	'sd_formcreate' => 'Tạo bằng biểu mẫu',
	'sd_viewform' => 'Xem mẫu',
	'createfilter' => 'Tạo bộ lọc',
	'sd_createfilter_name' => 'Tên:',
	'sd_createfilter_property' => 'Tính chất bộ lọc này bao phủ:',
	'sd_createfilter_usepropertyvalues' => 'Sử dụng tất cả các giá trị của thuộc tính này cho bộ lọc',
	'sd_createfilter_usecategoryvalues' => 'Lấy giá trị cho bộ lọc từ thể loại này:',
	'sd_createfilter_requirefilter' => 'Cần bộ lọc khác được chọn trước khi hiển thị cái này:',
	'sd_createfilter_label' => 'Đánh nhãn cho bộ lọc này (tùy chọn):',
	'sd_blank_error' => 'không được để trống',
	'sd-pageschemas-values' => 'Các giá trị',
	'sd_filter_coversproperty' => 'Bộ lọc này bao phủ thuộc tính $1.',
	'sd_filter_getsvaluesfromcategory' => 'Nó có giá trị từ thể loại $1.',
	'sd_filter_requiresfilter' => 'Nó yêu cầu sự hiện diện của bộ lọc $1.',
	'sd_filter_haslabel' => 'Nó có nhãn $1.',
);

/** Volapük (Volapük)
 * @author Malafaya
 * @author Smeira
 */
$messages['vo'] = array(
	'browsedata' => 'Padön da nünods',
	'sd_browsedata_choosecategory' => 'Välön kladi',
	'sd_browsedata_viewcategory' => 'logön kladi',
	'sd_browsedata_subcategory' => 'Donaklad',
	'sd_browsedata_other' => 'Votik',
	'sd_browsedata_none' => 'Nonik',
	'sd_browsedata_filterbyvalue' => 'Sulön ma völad at',
	'sd_browsedata_filterbysubcategory' => 'Sulön ma donaklad at',
	'sd_browsedata_otherfilter' => 'Jonön padis labü völadi votik tefü sul at',
	'sd_browsedata_nonefilter' => 'Jonön padis labü völad nonik tefü sul at',
	'sd_browsedata_or' => 'u',
	'sd_browsedata_removefilter' => 'Moükön suli at',
	'sd_browsedata_removesubcategoryfilter' => 'Moükön donakladasuli at',
	'sd_browsedata_resetfilters' => 'Geükön sulis ad stad kösömik',
	'sd_browsedata_addanothervalue' => 'Läükön völadi votik', # Fuzzy
	'sd_browsedata_daterangestart' => 'Prim:',
	'sd_browsedata_daterangeend' => 'Fin:',
	'filters' => 'Suls',
	'sd_filters_docu' => 'Suls sököl dabinons in {{SITENAME}}:',
	'sd_viewform' => 'Logön fometi',
	'createfilter' => 'Jafön suli',
	'sd_createfilter_name' => 'Nem:',
	'sd_createfilter_property' => 'Patöf fa sul at pageböl:',
	'sd_createfilter_usepropertyvalues' => 'Gebön völadis valik patöfa at pro sul.',
	'sd_createfilter_usecategoryvalues' => 'Tuvön völadis pro sul in klad at:',
	'sd_createfilter_requirefilter' => 'Flagön, das sul votik puvälon büä sul at pojonon:',
	'sd_createfilter_label' => 'Nem sula at (no paflagöl):',
	'sd_blank_error' => 'no dalon vagön',
	'sd_filter_coversproperty' => 'Sul at tefon patöfi: $1.',
	'sd_filter_getsvaluesfromcategory' => 'Tuvon völadis okik in klad: $1.',
	'sd_filter_requiresfilter' => 'Flagon komi sula: $1.',
	'sd_filter_haslabel' => 'Labon nemi: $1.',
);

/** Yiddish (ייִדיש)
 * @author פוילישער
 * @author පසිඳු කාවින්ද
 */
$messages['yi'] = array(
	'sd_browsedata_other' => 'אנדער',
	'sd_browsedata_none' => 'קיין',
	'sd_createfilter_name' => 'נאָמען:',
);

/** Chinese (China) (‪中文(中国大陆)‬)
 * @author Roc Michael
 */
$messages['zh-cn'] = array(
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
	'sd_createfilter_requirefilter' => '在此一筛选器展示其作用之前要求须选取其他的筛选器：',
	'sd_createfilter_label' => '为此一筛选选器设置标签(选择性的)：',
	'sd_blank_error' => '不得为空白',
	'sd_filter_coversproperty' => '此筛选器涵盖了$1性质。',
	'sd_filter_getsvaluesfromcategory' => '其从$1分类取得它的值。',
	'sd_filter_requiresfilter' => '其以$1筛选器为基础。',
	'sd_filter_haslabel' => '其有着此一$1标签',
);

/** Simplified Chinese (中文（简体）‎)
 * @author Liangent
 * @author Linforest
 * @author PhiLiP
 * @author Yfdyh000
 */
$messages['zh-hans'] = array(
	'semanticdrilldown-desc' => '用于在语义数据之中导航的钻取界面',
	'specialpages-group-sd_group' => '语义钻取',
	'browsedata' => '浏览数据',
	'sd_browsedata_choosecategory' => '选择一个分类',
	'sd_browsedata_viewcategory' => '检视分类',
	'sd_browsedata_docu' => '点击下面的一个或多个项目来缩小您的结果。',
	'sd_browsedata_subcategory' => '子类别',
	'sd_browsedata_other' => '其他',
	'sd_browsedata_none' => '无',
	'sd_browsedata_filterbyvalue' => '按此值筛选',
	'sd_browsedata_filterbysubcategory' => '按此子类别筛选',
	'sd_browsedata_otherfilter' => '显示具有该筛选器另一个取值的页面',
	'sd_browsedata_nonefilter' => '显示没有该筛选器的取值的页面',
	'sd_browsedata_or' => '或',
	'sd_browsedata_removefilter' => '删除此筛选器',
	'sd_browsedata_removesubcategoryfilter' => '删除此子类别筛选器',
	'sd_browsedata_resetfilters' => '重置筛选器',
	'sd_browsedata_addanothervalue' => '单击箭头来添加另一个取值',
	'sd_browsedata_daterangestart' => '开始：',
	'sd_browsedata_daterangeend' => '结束：',
	'sd_browsedata_novalues' => '该筛选器没有任何取值',
	'filters' => '过滤器',
	'sd_filters_docu' => '下列筛选器存在于{{SITENAME}}之上：',
	'sd_formcreate' => '用表单创建',
	'sd_viewform' => '检视表格',
	'createfilter' => '创建筛选器',
	'sd-createfilter-with-name' => '创建过滤器：$1',
	'sd_createfilter_name' => '名称：',
	'sd_createfilter_property' => '此筛选器所涵盖的属性：',
	'sd_createfilter_usepropertyvalues' => '对该筛选器使用此属性的所有取值',
	'sd_createfilter_usecategoryvalues' => '从该类别当中为筛选器获取取值：',
	'sd_createfilter_requirefilter' => '在显示该筛选器之前，要求选择另一个筛选器：',
	'sd_createfilter_label' => '此筛选器的标签（可选）：',
	'sd_blank_error' => '不可留空',
	'sd-pageschemas-filter' => '筛选器',
	'sd-pageschemas-values' => '取值',
	'sd_filter_coversproperty' => '此筛选器涵盖属性$1。',
	'sd_filter_getsvaluesfromcategory' => '它从类别$1之中获得其取值。',
	'sd_filter_requiresfilter' => '它需要筛选器$1的存在。',
	'sd_filter_haslabel' => '它具有标签$1。',
);

/** Traditional Chinese (中文（繁體）‎)
 * @author Cwlin0416
 * @author Mark85296341
 * @author Oapbtommy
 * @author Wrightbus
 */
$messages['zh-hant'] = array(
	'semanticdrilldown-desc' => '用於在語義數據之中導航的鑽取界面',
	'specialpages-group-sd_group' => '語義鑽取',
	'browsedata' => '瀏覽數據',
	'sd_browsedata_choosecategory' => '選擇一個分類',
	'sd_browsedata_viewcategory' => '檢視分類',
	'sd_browsedata_docu' => '點擊下面的一個或多個項目來縮小您的結果。',
	'sd_browsedata_subcategory' => '子類別',
	'sd_browsedata_other' => '其他',
	'sd_browsedata_none' => '無',
	'sd_browsedata_filterbyvalue' => '按此值篩選',
	'sd_browsedata_filterbysubcategory' => '按此子類別篩選',
	'sd_browsedata_otherfilter' => '顯示具有該篩選器另一個取值的頁面',
	'sd_browsedata_nonefilter' => '顯示沒有該篩選器的取值的頁面',
	'sd_browsedata_or' => '或者',
	'sd_browsedata_removefilter' => '刪除此篩選器',
	'sd_browsedata_removesubcategoryfilter' => '刪除此子類別篩選器',
	'sd_browsedata_resetfilters' => '重置篩選器',
	'sd_browsedata_addanothervalue' => '單擊箭頭來添加另一個取值',
	'sd_browsedata_daterangestart' => '開始：',
	'sd_browsedata_daterangeend' => '結束：',
	'sd_browsedata_novalues' => '該篩選器沒有任何取值',
	'filters' => '搜尋',
	'sd_filters_docu' => '下列篩選器存在於{{SITENAME}}之上：',
	'sd_formcreate' => '用表單創建',
	'sd_viewform' => '檢視表格',
	'createfilter' => '創建篩選器',
	'sd_createfilter_name' => '名稱：',
	'sd_createfilter_property' => '此篩選器所涵蓋的屬性：',
	'sd_createfilter_usepropertyvalues' => '對該篩選器使用此屬性的所有取值',
	'sd_createfilter_usecategoryvalues' => '從該類別當中為篩選器獲取取值：',
	'sd_createfilter_requirefilter' => '在顯示該篩選器之前，要求選擇另一個篩選器：',
	'sd_createfilter_label' => '此篩選器的標籤（可選）：',
	'sd_blank_error' => '不可留空',
	'sd-pageschemas-filter' => '篩選器',
	'sd-pageschemas-values' => '取值',
	'sd_filter_coversproperty' => '此篩選器涵蓋屬性$1。',
	'sd_filter_getsvaluesfromcategory' => '它從類別$1之中獲得其取值。',
	'sd_filter_requiresfilter' => '它需要篩選器$1的存在。',
	'sd_filter_haslabel' => '它具有標籤$1。',
);

/** Chinese (Taiwan) (‪中文(台灣)‬)
 * @author Roc michael
 */
$messages['zh-tw'] = array(
	'browsedata' => '瀏覽資料',
	'sd_browsedata_choosecategory' => '選取某項分類(category)',
	'sd_browsedata_viewcategory' => '查看分類頁面',
	'sd_browsedata_docu' => '選取1項或多項以限制輸出的結果',
	'sd_browsedata_subcategory' => '子分類',
	'sd_browsedata_other' => '其他的',
	'sd_browsedata_none' => '無',
	'sd_browsedata_filterbyvalue' => '依此值設置篩選器',
	'sd_browsedata_filterbysubcategory' => '依此子分類(subcategory)設置篩選器',
	'sd_browsedata_otherfilter' => '查看屬於此篩選器中其他值的頁面，',
	'sd_browsedata_nonefilter' => '查看此篩選器設置條件中無任何值的頁面',
	'sd_browsedata_or' => '或',
	'sd_browsedata_removefilter' => '移除此篩選器',
	'sd_browsedata_removesubcategoryfilter' => '移除此子分類(subcategory)篩選器',
	'sd_browsedata_resetfilters' => '重置篩選器',
	'sd_browsedata_addanothervalue' => '增加其他值',
	'sd_browsedata_daterangestart' => '起始於：',
	'sd_formcreate' => '以表單建立',
	'sd_viewform' => '查看表單',
	'sd_browsedata_daterangeend' => '結束於：',
	'filters' => '篩選器',
	'sd_filters_docu' => '此wiki系統內已設有如下的篩選器(filters)',
	'createfilter' => '建立篩選器',
	'sd_createfilter_name' => '名稱：',
	'sd_createfilter_property' => '此一篩選器所涵蓋的性質：',
	'sd_createfilter_usepropertyvalues' => '將此一性質的值設給篩選器所用',
	'sd_createfilter_usecategoryvalues' => '從此分類中為篩選器取得篩選值：',
	'sd_createfilter_requirefilter' => '在此一篩選器展示其作用之前要求須選取其他的篩選器(即此一篩選器的作用係以另一篩選器為其基礎)：',
	'sd_createfilter_label' => '為此一篩選選器設定標籤(選擇性的)：',
	'sd_blank_error' => '不得為空白',
	'sd_filter_coversproperty' => '此篩選器涵蓋了$1性質。',
	'sd_filter_getsvaluesfromcategory' => '其從$1分類取得它的值。',
	'sd_filter_requiresfilter' => '其以$1篩選器為基礎。',
	'sd_filter_haslabel' => '其有著此一$1標籤',
);
