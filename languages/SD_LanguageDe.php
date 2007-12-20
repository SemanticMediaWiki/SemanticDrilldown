<?php
/**
 * @author Yaron Koren (Translation: Bernhard Krabina:krabina@cornerstone.at)
 */

class SD_LanguageDe extends SD_Language {

/* private */ var $m_ContentMessages = array(
	'sd_filter_coversproperty' => 'Dieser Filter betrifft das Attribut $1.',
	'sd_filter_getsvaluesfromcategory' => 'Er erhält seine Werte aus der Kategorie $1.',
	'sd_filter_usestimeperiod' => 'Verwendet $1 als Zeitangabe.',
	'sd_filter_year' => 'Jahr',
	'sd_filter_month' => 'Monat',
	'sd_filter_hasvalues' => 'Hat den Wert $1.',
	'sd_filter_requiresfilter' => 'Setzt den Filter $1 voraus.',
	'sd_filter_haslabel' => 'Hat die Bezeichnung $1.'
);

/* private */ var $m_UserMessages = array(
	'viewdata' => 'Daten ansehen',
	'sd_viewdata_choosecategory' => 'Wählen Sie eine Kategorie',
	'sd_viewdata_viewcategory' => 'Kategorie ansehen',
	'sd_viewdata_subcategory' => 'Unterkategorie',
	'sd_viewdata_other' => 'Anderes',
	'sd_viewdata_none' => 'Keines',
	'filters' => 'Filter',
	'sd_filters_docu' => 'Die folgenden Filter existieren in diesem Wiki:',
	'createfilter' => 'Erstelle einen Filter',
	'sd_createfilter_name' => 'Name:',
	'sd_createfilter_property' => 'Attribut dieses Filters:',
	'sd_createfilter_usepropertyvalues' => 'Verwende die erlaubten Werte dieses Attributs für den Filter',
	'sd_createfilter_usecategoryvalues' => 'Verwende die Werte für den Filter von dieser Kategorie:',
	'sd_createfilter_usedatevalues' => 'Verwende folgende Zeitangabe für diesen Filter:',
	'sd_createfilter_entervalues' => 'Verwende diese Werte für den Filter (Werte durch Komma getrennt eingeben. Wenn ein Wert ein Komma enthält, mit "\," ersetzen.):',
	'sd_createfilter_label' => 'Bezeichnung dieses Filters (optional):',
	'sd_createfilter_requirefilter' => 'Bevor dieser Filter angezeigt wird,
muss folgender anderer Filter gesetzt sein:',

	'sd_blank_error' => 'darf nicht leer sein'
);

/* private */ var $m_SpecialProperties = array(
        //always start upper-case
	// category properties
	SD_SP_HAS_FILTER  => 'Hat Filter',
	// filter properties
	SD_SP_COVERS_PROPERTY  => 'Betrifft Attribut',
	SD_SP_HAS_VALUE  => 'Hat Wert',
	SD_SP_GETS_VALUES_FROM_CATEGORY => 'Erhält Werte aus der Kategorie',
	SD_SP_USES_TIME_PERIOD => 'Verwendet Zeitangabe',
	SD_SP_REQUIRES_FILTER => 'Benötigt Filter',
        SD_SP_HAS_LABEL  => 'Hat Bezeichnung'
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> 'Filter',
	SD_NS_FILTER_TALK	=> 'Filter_talk'
);

}
