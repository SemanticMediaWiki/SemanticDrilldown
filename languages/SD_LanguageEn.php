<?php
/**
 * @author Yaron Koren
 */

class SD_LanguageEn extends SD_Language {

// no need to define properties or namespaces - the English-language
// values are already specified in the default aliases
// (namespaces are still defined because namespace aliases don't work yet)

/* private */ var $m_SpecialProperties = array(
);

var $m_Namespaces = array(
	SD_NS_FILTER		=> 'Filter', 	 
	SD_NS_FILTER_TALK	=> 'Filter_talk'
);

}
