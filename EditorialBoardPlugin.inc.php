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
				import('plugins.generic.editorialBoard.classes.EditorialMembersDAO');
				$editorialMembersDAO = new EditorialMembersDAO();
				DAORegistry::registerDAO('EditorialMembersDAO', $editorialMembersDAO);

				// HookRegistry::register('LoadHandler', array($this, 'callbackHandleContent'));

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
		$this->import('EditorialMembersSchemaMigration');
		return new EditorialMembersSchemaMigration();
	}

	function getJavaScriptURL($request) {
		return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
	}

	
}