{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblGallery|ucfirst}: {$gallery.title}</h2>
</div>

{form:edit}
    <p>
            {$lblTitle|ucfirst}<br/>
            {$txtTitle} {$txtTitleError}
    </p>
    <p>
            {$lblLink|ucfirst}<br/>
            {$txtLink} {$txtLinkError}
    </p>
    <div class="ui-tabs">
        <div class="ui-tabs-panel">
            <div class="options">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td id="leftColumn">

                            <div class="box">
                                <div class="heading">
                                    <h3>{$lblDescription|ucfirst}<abbr title="{$lblRequiredField}"></abbr></h3>
                                </div>
                                <div class="optionsRTE">
                                    {$txtDescription} {$txtDescriptionError}
                                </div>
                            </div>
                            <p>
                                {option:item.filename}
                                    <img src="/src/Frontend/Files/userfiles/images/slideshow/thumbnails/{$item.filename}" alt="" />
                                {/option:item.filename}
                                <label for="filename">Afbeelding</label>
                                {$fileFilename} {$fileFilenameError}
                            </p>
                        </td>

                        <td id="sidebar">
                            <div id="publishOptions" class="box">
                                <div class="heading">
                                    <h3>{$lblStatus|ucfirst}</h3>
                                </div>
                                <div class="options">
                                    <ul class="inputList">
                                        {iteration:hidden}
                                        <li>
                                            {$hidden.rbtHidden}
                                            <label for="{$hidden.id}">{$hidden.label}</label>
                                        </li>
                                        {/iteration:hidden}
                                    </ul>
                                </div>
                            </div>
                        </td>

                    </tr>
                </table>
            </div>
        </div>

        <div class="fullwidthOptions">
            <a href="{$var|geturl:'delete_image'}&amp;id={$item.id}&amp;gallery_id={$item.gallery_id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
                <span>{$lblDelete|ucfirst}</span>
            </a>
            <div class="buttonHolderRight">
                <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
            </div>
        </div>

        <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
            <p>
                Verwijderen? {$item.title}
            </p>
        </div>

{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
