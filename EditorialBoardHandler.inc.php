<?php

import('classes.handler.Handler');

class EditorialBoardHandler extends Handler
{
    static $plugin;

    static $editorialMember;

    static function setPlugin($plugin)
    {
        self::$plugin = $plugin;
    }

    static function setPage($editorialMember)
    {
        self::$editorialMember = $editorialMember;
    }

    function index($args, $request)
    {
        $request->redirect(null, null, 'view', $request->getRequestedOp());
    }

    function view($args, $request)
    {
        $path = array_shift($args);

        AppLocale::requireComponents(LOCALE_COMPONENT_PKP_COMMON, LOCALE_COMPONENT_APP_COMMON, LOCALE_COMPONENT_PKP_USER);
        $context = $request->getContext();
        $contextId = $context?$context->getId():CONTEXT_ID_NONE;

        // Ensure that if we're previewing, the current user is a manager or admin.
        $roles = $this->getAuthorizedContextObject(ASSOC_TYPE_USER_ROLES);
        if (!self::$editorialMember->getId() && count(array_intersect(array(ROLE_ID_MANAGER, ROLE_ID_SITE_ADMIN), $roles))==0) {
        fatalError('The current user is not permitted to preview');
        } 

        // Assign the template vars needed and display
        $templateMgr = TemplateManager::getManager($request);
        $this->setupTemplate($request);
        $templateMgr->assign('title', self::$editorialMember->getLocalizedTitle());

        $vars = array();
        if ($context) $vars = array(
            '{$contactName}' => $context->getData('contactName'),
			'{$contactEmail}' => $context->getData('contactEmail'),
			'{$supportName}' => $context->getData('supportName'),
			'{$supportPhone}' => $context->getData('supportPhone'),
			'{$supportEmail}' => $context->getData('supportEmail'),
        );
        $templateMgr->assign('affiliation', self::$editorialMember->getLocalizedAffiliation());
        $templateMgr->assign('bio', self::$editorialMember->getLocalizedBio());
        $templateMgr->assign('references', self::$editorialMember->getReferences());
        $templateMgr->display(self::$plugin->getTemplateResource('editorialMember.tpl'));
    }
    
}