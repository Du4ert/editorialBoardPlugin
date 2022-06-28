{include file="frontend/components/header.tpl" pageTitleTranslated=$title}


{assign var="defaultPic" value="/plugins/themes/{$currentContext->getData('themePluginPath')}/img/profile-photo.png"}

<div id="main-content" class="page page_about_editorial_team_bio">
    {include file="frontend/components/breadcrumbs_editorial.tpl" currentTitle=$title}

    {* Page Title *}
    <div class="page-header">
        {include file="frontend/components/editLink.tpl" page="management" op="settings" path="website"
        anchor="editorialBoard" sectionTitleKey="6"}
        <h1>{translate key="plugins.themes.ibsscustom.editorial.title"}</h1>
    </div>


    <div id="editorialTeamBio" class="row">

        <div id="profilePicContent" class="bio-picContent col-sm-3 col-md-3 center"><img class="bio-picture"
                src="{if $photo}{$photo}{else}{$defaultPic}{/if}"
                alt="Profile Image" width="150" height="150" />
        </div>

        <div class="bio-content col-sm-12 col-md-9">

            <h1 class="bio-fullName-custom">{$title|escape}</h1>
            <p class="bio-affiliation"><span class="bio-fieldName-custom">Affiliation: </span><a
                    href="https://ibss-ras.ru/" target="_blank" rel="noopener">{$affiliation|escape}</a></p>
            <p class="bio-statement"><span class="bio-fieldName-custom">Position, academic degree, rank:
                </span>{$bio|escape}</p>
            <div>
                <span class="bio-fieldName-custom">References:</span>
                {$references}
            </div>

        </div>
    </div>

</div>
{include file="frontend/components/footer.tpl"}