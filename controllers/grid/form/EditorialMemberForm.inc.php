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
		// $this->addCheck(new FormValidatorPost($this));
		// $this->addCheck(new FormValidatorCSRF($this));
		// $this->addCheck(new FormValidator($this, 'title', 'required', 'plugins.generic.editorialBoard.nameRequired'));
		// $this->addCheck(new FormValidatorRegExp($this, 'path', 'required', 'plugins.generic.editorialBoard.pathRegEx', '/^[a-zA-Z0-9\/._-]+$/'));
		// $form = $this;
		// $this->addCheck(new FormValidatorCustom($this, 'path', 'required', 'plugins.generic.editorialBoard.duplicatePath', function($path) use ($form) {
		// 	$editorialBoardDao = DAORegistry::getDAO('EditorialBoardDAO');
		// 	$page = $editorialBoardDao->getByPath($form->contextId, $path);
		// 	return !$page || $page->getId()==$form->editorialMemberId;
		// }));
	}

}