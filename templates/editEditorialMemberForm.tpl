<script src="{$pluginJavaScriptURL}/EditorialMemberFormHandler.js"></script>
<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#editorialMemberForm').pkpHandler(
			'$.pkp.controllers.form.editorialBoard.EditorialMemberFormHandler',
			{ldelim}
				previewUrl: {url|json_encode router=$smarty.const.ROUTE_PAGE page="members" op="preview"}
			{rdelim}
		);
	{rdelim});
</script>

{capture assign=actionUrl}{url router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridHandler" op="updateEditorialMember" escape=false}{/capture}
<form class="pkp_form" id="editorialMemberForm" action="{$actionUrl}">
    {csrf}
    {if $editorialMemberId}
        <input type="hidden" name="editorialMemberId" value="{$editorialMemberId|escape}"> 
    {/if}
    {fbvFormArea id="editorialBoardFormArea" class="border"}
        {fbvFormSection}
            {fbvElement type="text" label="Title" id="title" value=$title maxlength="255" inline=true multilingual=true size=$fbvStyles.size.MEDIUM required=true}
            {fbvElement type="text" label="Path" id="path" value=$path maxlength="40" inline=true size=$fbvStyles.size.MEDIUM required=true}
        {/fbvFormSection}
        {fbvFormSection}
        {fbvElement type="text" label="Affiliation" id="affiliation" value=$affiliation maxlength="255" inline=false multilingual=true size=$fbvStyles.size.MEDIUM}
        {fbvElement type="text" label="Bio" id="bio" value=$bio maxlength="255" inline=false multilingual=true size=$fbvStyles.size.MEDIUM}
        {/fbvFormSection}
        {fbvFormSection label="References" for="references"}
          {fbvElement type="textarea" multilingual=false name="references" id="references" value=$references rich=true height=$fbvStyles.height.TALL variables=$allowedVariables}
        {/fbvFormSection}
    {/fbvFormArea}
      {fbvFormSection class="formButtons"}
      {fbvElement type="button" class="pkp_helpers_align_left" id="previewButton" label="common.preview"}
     {assign var=buttonId value="submitFormButton"|concat:"-"|uniqid}
     {fbvElement type="submit" class="submitFormButton" id=$buttonId label="common.save"}
    {/fbvFormSection}
</form>