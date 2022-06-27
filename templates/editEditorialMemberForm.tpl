<script src="{$pluginJavaScriptURL}/EditorialMemberFormHandler.js"></script>
<script>
    $(function() {ldelim}
    // Attach the form handler
    $('#editorialMemberForm').pkpHandler(
        '$.pkp.controllers.form.editorialBoard.EditorialMemberFormHandler',
        {ldelim}
            previewUrl: {url|json_encode router=$smarty.const.ROUTE_PAGE page="pages" op="preview"}
            {ldelim}
    );
    {ldelim});
</script>

{capture assign=actionUrl}{url router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridHandler" op="updateEditorialMember"}