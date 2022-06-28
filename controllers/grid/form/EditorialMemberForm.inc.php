<?php

import('lib.pkp.classes.form.Form');

class EditorialMemberForm extends Form {
	/** @var int Context (press / journal) ID */
	var $contextId;

	/** @var string Editorial member name */
	var $editorialMemberId;

	/** @var EditorialBoardPlugin Editorial board plugin */
	var $plugin;


    function __construct($editorialBoardPlugin, $contextId, $editorialMemberId = null) {
		parent::__construct($editorialBoardPlugin->getTemplateResource('editEditorialMemberForm.tpl'));

		$this->contextId = $contextId;
		$this->editorialMemberId = $editorialMemberId;
		$this->plugin = $editorialBoardPlugin;

		// Add form checks
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
		$this->addCheck(new FormValidator($this, 'title', 'required', 'plugins.generic.editorialBoard.nameRequired'));
		$this->addCheck(new FormValidatorRegExp($this, 'path', 'required', 'plugins.generic.editorialBoard.pathRegEx', '/^[a-zA-Z0-9\/._-]+$/'));
		$form = $this;
		$this->addCheck(new FormValidatorCustom($this, 'path', 'required', 'plugins.generic.editorialBoard.duplicatePath', function($path) use ($form) {
			$editorialBoardDAO = DAORegistry::getDAO('EditorialBoardDAO');
			$page = $editorialBoardDAO->getByPath($form->contextId, $path);
			return !$page || $page->getId()==$form->editorialMemberId;
		}));
	}

	function initData()
	{
		$templateMgr = TemplateManager::getManager();
		if ($this->editorialMemberId) {
			$editorialBoardDAO = DAORegistry::getDao('EditorialBoardDAO');
			$editorialMember = $editorialBoardDAO->getById($this->editorialMemberId, $this->contextId);
			$this->setData('path', $editorialMember->getPath());
			$this->setData('title', $editorialMember->getTitle(null));  // Localized
			$this->setData('affiliation', $editorialMember->getAffiliation(null));  // Localized
			$this->setData('bio', $editorialMember->getBio(null));  // Localized
			$this->setData('references', $editorialMember->getReferences());  //! Localized

		}
	}

	function readInputData()
	{
		$this->readUserVars(array('path', 'title', 'affiliation', 'bio', 'references'));
	}


	function fetch($request, $template = null, $display = false)
	{
		$templateMgr = TemplateManager::getManager();
		$templateMgr->assign(array(
			'editorialMemberId' => $this->editorialMemberId,
			'pluginJavaScriptURL' => $this->plugin->getJavaScriptURL($request),
		));

		if ($context = $request->getContext()) $templateMgr->assign('allowedVariables', array(
			'contactName' => __('plugins.generic.tinymce.variables.principalContactName', array('value' => $context->getData('contactName'))),
			'contactEmail' => __('plugins.generic.tinymce.variables.principalContactEmail', array('value' => $context->getData('contactEmail'))),
			'supportName' => __('plugins.generic.tinymce.variables.supportContactName', array('value' => $context->getData('supportName'))),
			'supportPhone' => __('plugins.generic.tinymce.variables.supportContactPhone', array('value' => $context->getData('supportPhone'))),
			'supportEmail' => __('plugins.generic.tinymce.variables.supportContactEmail', array('value' => $context->getData('supportEmail'))),
		));

		return parent::fetch($request, $template, $display);
	}

	function execute(...$functionParams)
	{
		parent::execute(...$functionParams);

		$editorialBoardDAO = DAORegistry::getDAO('EditorialBoardDAO');
		if ($this->editorialMemberId)
		{
			// Load and update existing member
			$editorialMember = $editorialBoardDAO->getById($this->editorialMemberId, $this->contextId);
		} else {
			// Create a new member
			$editorialMember = $editorialBoardDAO->newDataObject();
			$editorialMember->setContextId($this->contextId);
		}

		$editorialMember->setPath($this->getData('path'));
		$editorialMember->setTitle($this->getData('title'), null); // Localized
		$editorialMember->setAffiliation($this->getData('affiliation'), null); // Localized
		$editorialMember->setBio($this->getData('bio'), null); // Localized
		$editorialMember->setReferences($this->getData('references'), null); //! Localized

		if ($this->editorialMemberId) {
			$editorialBoardDAO->updateObject($editorialMember);
		} else {
			$editorialBoardDAO->insertObject($editorialMember);
		}
	}

}