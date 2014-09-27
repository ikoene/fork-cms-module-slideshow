{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

{form:edit_category}
    <div class="pageTitle">
        <h2>{$lblCategory|ucfirst}: {$title}</h2>
    </div>

    <div class="tabs">
        <ul>
            <li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
            <li><a href="#tabSEO">{$lblSEO|ucfirst}</a></li>
        </ul>

        <div class="ui-tabs">
            <div class="ui-tabs-panel">

                <div id="tabContent">
                    <div class="options">
                        <p>
                            <label for="name">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                            {$txtTitle} {$txtTitleError}
                        </p>
                    </div>
                </div>

                <div id="tabSEO">
                    {include:{$BACKEND_CORE_PATH}/Layout/Templates/Seo.tpl}
                </div>
            </div>
        </div>
    </div>

    <div class="fullwidthOptions">
        {option:showSlideshowDeleteCategory}
            <a href="{$var|geturl:'DeleteCategory'}&amp;id={$id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
                <span>{$lblDelete|ucfirst}</span>
            </a>
            <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
                <p>
                    {$msgConfirmDeleteCategory|sprintf:{$title}}
                </p>
            </div>
        {/option:showSlideshowDeleteCategory}
        <div class="buttonHolderRight">
            <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
        </div>
    </div>
{/form:edit_category}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
