<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class EditorialBoardPlugin extends GenericPlugin {
    	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		// return __('plugins.generic.editorialBoard.displayName');
        return 'Editorial Board';
	}

    function getDescription()
    {
        return 'Custom plugin in develop';
    }

	function isTinyMCEInstalled() 
	{
		$application = Application::get();
		$products = $application->getEnabledProducts('plugins.generic');
		return (isset($products['tinymce']));
	}

    function register($category, $path, $mainContextId = null) 
	{
		if (parent::register($category, $path, $mainContextId)) {
			if ($this->getEnabled($mainContextId)) {
				HookRegistry::register('PluginRegistry::loadCategory', [$this, 'updateSchema']);
				import('plugins.generic.editorialBoard.classes.EditorialBoardDAO');
				$editorialBoardDAO = new EditorialBoardDAO();
				DAORegistry::registerDAO('EditorialBoardDAO', $editorialBoardDAO);
				$this->getInstallMigration();

				HookRegistry::register('LoadHandler', array($this, 'callbackHandleContent'));

				HookRegistry::register('Template::Settings::website', array($this, 'callbackShowWebsiteSettingsTabs'));
				HookRegistry::register('LoadComponentHandler', array($this, 'setupGridHandler'));
			}
			return true;
		}
		return false;
    }

	function callbackShowWebsiteSettingsTabs($hookName, $args) 
	{
		$templateMgr = $args[1];
		$output =& $args[2];
		$request =& Registry::get('request');
		$dispatcher = $request->getDispatcher();

		$output .= $templateMgr->fetch($this->getTemplateResource('editorialBoardTab.tpl'));

		// Permit other plugins to continue interacting with this hook
		return false;
	}


	function setupGridHandler($hookName, $params)
	{
		$component =& $params[0];
		if ($component == 'plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridHandler') {
			import($component);
			EditorialMemberGridHandler::setPlugin($this);
			return true;
		}
		return false;
	}

	function getActions($request, $actionArgs)
	{
		$dispatcher = $request->getDispatcher();
		import('lib.pkp.classes.linkAction.request.RedirectAction');
		return array_merge(
			$this->getEnabled()?[
				new LinkAction(
					'settings',
					new RedirectAction($dispatcher->url(
						$request, ROUTE_PAGE,
						null, 'management', 'settings', 'website',
						array('uid' => uniqid()), // Force reload
						'editorialBoard' // Anchor for tab
					)),
					__('Edit editorial board'),
					null
				),
			]:[],
			parent::getActions($request, $actionArgs)
			);
	}

	function getInstallMigration()
	{
		$this->import('EditorialBoardSchemaMigration');
		return new EditorialBoardSchemaMigration();
	}

	function updateSchema($hookName, $args)
	{
		$migration = $this->getInstallMigration();
		if ($migration && !$migration->check()) {
			$migration->up();
		}
		return false;
	}

	function getJavaScriptURL($request) {
		return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
	}

	function callbackHandleContent($hookName, $args)
	{
		$request = Application::get()->getRequest();
		$templateMgr = TemplateManager::getManager($request);

		$page =& $args[0];
		$op =& $args[1];

		$editorialBoardDAO = DAORegistry::getDAO('EditorialBoardDAO');
		if ($page == 'members' && $op == 'preview') {
			$editorialMember = $editorialBoardDAO->newDataObject();
			$editorialMember->setTitle((array) $request->getUserVar('title'), null);
			$editorialMember->setAffiliation((array) $request->getUserVar('affiliation'), null);
			$editorialMember->setBio((array) $request->getUserVar('bio'), null);
			$editorialMember->setReferences((array) $request->getUserVar('references'));
		} else {
			$path = $page;
			if ($op !== 'index') $path .= "/$op";
			if ($ops = $request->getRequestedArgs()) $path .= '/' . implode('/', $ops);

			$context = $request->getContext();
			$editorialMember = $editorialBoardDAO->getByPath(
				$context?$context->getId():CONTEXT_ID_NONE,
				$path
			);
		}

		if ($editorialMember) {
			$page = 'members';
			$op = 'view';

			define('HANDLER_CLASS', 'EditorialBoardHandler');
			$this->import('EditorialBoardHandler');

			EditorialBoardHandler::setPlugin($this);
			EditorialBoardHandler::setPage($editorialMember);
			return true;
		}
		return false;
	}

	
}