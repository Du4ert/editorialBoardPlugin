<?php

/**
* @brief Handle custom blocks grid row requests.
*/

import('lib.pkp.classes.controllers.grid.GridRow');

class EditorialMemberGridRow extends GridRow
{
    	//
	// Overridden template methods
	//
	/**
	 * @copydoc GridRow::initialize()
	 */
    function initialize($request, $template = null)
    {
        parent::initialize($request, $template);

        $editorialMemberId = $this->getId();
        if (!empty($editorialMemberId)) {
            $router = $request->getRouter();

            			// Create the "edit editorial member" action
			import('lib.pkp.classes.linkAction.request.AjaxModal');
			$this->addAction(
				new LinkAction(
					'editEditorialMember',
					new AjaxModal(
						$router->url($request, null, null, 'editEditorialMember', null, array('editorialMemberId' => $editorialMemberId)),
						__('grid.action.edit'),
						'modal_edit',
						true),
					__('grid.action.edit'),
					'edit'
				)
			);

			// Create the "delete editorial member" action
			import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
			$this->addAction(
				new LinkAction(
					'delete',
					new RemoteActionConfirmationModal(
						$request->getSession(),
						__('common.confirmDelete'),
						__('grid.action.delete'),
						$router->url($request, null, null, 'delete', null, array('editorialMemberId' => $editorialMemberId)), 'modal_delete'
					),
					__('grid.action.delete'),
					'delete'
				)
			);
        }
    }
}