<?php

import('lib.pkp.classes.controllers.grid.GridHandler');
import('plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridRow');
import('plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridCellProvider');


// import('plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridRow');
// import('plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridCellProvider');

class EditorialMemberGridHandler extends GridHandler 
    {
    /** @var EditorialBoardPlugin The editorial board plugin */
	static $plugin;

    static function setPlugin($plugin)
    {
        self::$plugin = $plugin;
    }


    function __construct() 
    {
        parent::__construct();
        $this->addRoleAssignment(
            array(ROLE_ID_MANAGER),
            array('index', 'fetchGrid', 'fetchRow', 'addEditorialMember', 'editEditorialMember', 'updateEditorialMember', 'delete')
        );
    }


	function authorize($request, &$args, $roleAssignments) {
		import('lib.pkp.classes.security.authorization.ContextAccessPolicy');
		$this->addPolicy(new ContextAccessPolicy($request, $roleAssignments));
		return parent::authorize($request, $args, $roleAssignments);
	}


	function initialize($request, $args = null) 
	{
		parent::initialize($request, $args);
		$context = $request->getContext();

        // Set the grid details
        $this->setTitle('Editorial Board - grid');
        $this->setEmptyRowText('none created');

		// Get the pages and add the data to the grid
		$editorialMembersDAO = DAORegistry::getDAO('EditorialMembersDAO');
		$this->setGridDataElements($editorialMembersDAO->getByContextId($context->getId()));

        // Add grid-level actions
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		$this->addAction(
			new LinkAction(
				'addEditorialMember',
				new AjaxModal(
					$router->url($request, null, null, 'addEditorialMember'),
					__('plugins.generic.editorialBoard.addEditorialMember'),
					'modal_add_item'
				),
				__('plugins.generic.editorialBoard.addEditorialMember'),
				'add_item'
			)
		);


        // Columns
		$cellProvider = new EditorialMemberGridCellProvider();
		$this->addColumn(new GridColumn(
			'title',
			'plugins.generic.editorialBoard.pageTitle',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));
		$this->addColumn(new GridColumn(
			'path',
			'plugins.generic.editorialBoard.path',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));

	}


    function getRowInstance() 
	{
		return new EditorialMemberGridRow();
	}


	function addEditorialMember($args, $request) 
	{
		// Calling editEditorialMember with an empty ID will add
		// a new editorial member
		return $this->editEditorialMember($args, $request);
	}

    function editEditorialMember($args, $request) 
	{
        $editorialMemberId = $request->getUserVar('editorialMemberId');
        $context = $request->getContext();

        // Create edit form
        import('plugins.generic.editorialBoard.controllers.grid.form.EditorialMemberForm');
        $editorialBoardPlugin = self::$plugin;
        $editorialMemberForm = new EditorialMemberForm(self::$plugin, $context->getId(), $editorialMemberId);
        $editorialMemberForm->initData();
        return new JSONMessage(true, $editorialMemberForm->fetch($request));
    }

	function updateEditorialMemberPage($args, $request) 
	{
		$editorialMemberId = $request->getUserVar('editorialMemberId');
		$context = $request->getContext();
		$this->setupTemplate($request);

		// Create and populate the form
		import('plugins.generic.editorialBoard.controllers.grid.form.EditorialMemberForm');
		$editorialBoardPlugin = self::$plugin;
		$editorialMemberForm = new EditorialMemberForm(self::$plugin, $context->getId(), $editorialMemberId);
		$editorialMemberForm->readInputData();

		// Check the results
		if ($editorialMemberForm->validate()) {
			// Save the results
			$editorialMemberForm->execute();
			return DAO::getDataChangedEvent();
		} else {
			// Present any errors
			return new JSONMessage(true, $editorialMemberForm->fetch($request));
		}
	}


	function delete($args, $request)
	{
		$editorialMemberId = $request->getUserVar('editorialMemberId');
		$context = $request->getContext();

		// Delete the editorial member
		$editorialMembersDAO = DAORegistry::getDAO('EditorialMembersDAO');
		$editorialMember = $editorialMembersDAO->getById($editorialMemberId, $context->getId());
		$editorialMembersDAO->deleteObject($editorialMember);

		return DAO::getDataChangedEvent();
	}

	function index($args, $request) 
	{
		$context = $request->getContext();
		import('lib.pkp.classes.form.Form');
		$form = new Form(self::$plugin->getTemplateResource('editorialBoard.tpl'));
		return new JSONMessage(true, $form->fetch($request));
	}
}