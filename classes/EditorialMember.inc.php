<?php

/**
 * @file classes/EditorialMember.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.editorialBoard
 * @class EditorialMember
 */

class EditorialMember extends DataObject {

	//
	// Get/set methods
	//

	/**
	 * Get context ID
	 * @return string
	 */
	function getContextId(){
		return $this->getData('contextId');
	}

	/**
	 * Set context ID
	 * @param $contextId int
	 */
	function setContextId($contextId) {
		return $this->setData('contextId', $contextId);
	}


	/**
	 * Set page title
	 * @param string string
	 * @param locale
	 */
	function setTitle($title, $locale) {
		return $this->setData('title', $title, $locale);
	}

	/**
	 * Get page title
	 * @param locale
	 * @return string
	 */
	function getTitle($locale) {
		return $this->getData('title', $locale);
	}

	/**
	 * Get Localized page title
	 * @return string
	 */
	function getLocalizedTitle() {
		return $this->getLocalizedData('title');
	}


    	/**
	 * Set page affiliation
	 * @param $affiliation string
	 * @param locale
	 */
	function setAffiliation($affiliation, $locale) {
		return $this->setData('affiliation', $affiliation, $locale);
	}

	/**
	 * Get page affiliation
	 * @param locale
	 * @return string
	 */
	function getAffiliation($locale) {
		return $this->getData('affiliation', $locale);
	}
    

	/**
	 * Get "localized" affiliation
	 * @return string
	 */
	function getLocalizedAffiliation() {
		return $this->getLocalizedData('affiliation');
	}

    	/**
	 * Set page bio
	 * @param $bio string
	 * @param locale
	 */
	function setBio($bio, $locale) {
		return $this->setData('bio', $bio, $locale);
	}

	/**
	 * Get page bio
	 * @param locale
	 * @return string
	 */
	function getBio($locale) {
		return $this->getData('bio', $locale);
	}
    

	/**
	 * Get "localized" bio
	 * @return string
	 */
	function getLocalizedBio() {
		return $this->getLocalizedData('bio');
	}


    /**
	 * Set page references
	 * @param $references string
	 * @param locale
	 */
	function setReferences($references, $locale) {
		return $this->setData('references', $references, $locale);
	}

	/**
	 * Get page references
	 * @param locale
	 * @return string
	 */
	function getReferences($locale) {
		return $this->getData('references', $locale);
	}
    

	/**
	 * Get "localized" references
	 * @return string
	 */
	function getLocalizedReferences() {
		return $this->getLocalizedData('references');
	}
    

	/**
	 * Get page path string
	 * @return string
	 */
	function getPath() {
		return $this->getData('path');
	}

	 /**
	  * Set page path string
	  * @param $path string
	  */
	function setPath($path) {
		return $this->setData('path', $path);
	}
}

