<?php
/**
 * @author Yaron Koren
 */

/**
 * Base class for all language classes - heavily based on Semantic MediaWiki's
 * 'SMW_Language' class
 */
abstract class SD_Language {

	// the message arrays ...
	protected $m_ContentMessages;
	protected $m_UserMessages;
	protected $m_SpecialProperties;
	protected $m_SpecialPropertyAliases = array();
	protected $m_Namespaces;
	protected $m_NamespaceAliases = array();


	/**
	 * Function that returns an array of namespace identifiers.
	 */
	function getNamespaces() {
		return $this->m_Namespaces;
	}

	/**
	 * Function that returns an array of namespace aliases, if any.
	 */
	function getNamespaceAliases() {
		return $this->m_NamespaceAliases;
	}

	/**
	 * Function that returns the labels for the special properties.
	 */
	function getSpecialPropertiesArray() {
		return $this->m_SpecialProperties;
	}

	/**
	 * Aliases for special properties, if any.
	 */
	function getSpecialPropertyAliases() {
		return $this->m_SpecialPropertyAliases;
	}

	/**
	 * Function that returns all content messages (those that are stored
	 * in some article, and thus cannot be translated to individual users).
	 */
	function getContentMsgArray() {
		return $this->m_ContentMessages;
	}

	/**
	 * Function that returns all user messages (those that are given only to
	 * the current user, and thus can be given in the individual user language).
	 */
	function getUserMsgArray() {
		return $this->m_UserMessages;
	}
}
