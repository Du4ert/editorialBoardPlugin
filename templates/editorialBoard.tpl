{capture assign=editorialMemberGridUrl}{url router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.editorialBoard.controllers.grid.EditorialMemberGridHandler" op="fetchGrid" escape=false}{/capture}
    {load_url_in_div id="editorialMemberGridContainer" url=$editorialMemberGridUrl}

    