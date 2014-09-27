{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

{form:edit}

    <div class="pageTitle">
        <h2>{$lblGallery|ucfirst}: {$gallery.title}</h2>
        <div class="buttonHolderRight">
            <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
        </div>
    </div>

    <div class="ui-tabs">
        <div class="ui-tabs-panel">
            <div class="options">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td id="leftColumn">
                            <div class="box">
                                <div class="heading">
                                    <h3>{$lblTitle|ucfirst}</h3>
                                </div>
                                <div class="options">
                                    {$txtTitle} {$txtTitleError}
                                </div>
                            </div>

                            <div class="box">
                                <div class="heading">
                                    <h3>{$lblImage|ucfirst}</h3>
                                </div>
                                <div class="options">
                                    {$fileFilename} {$fileFilenameError}
                                </div>
                                <div class="options">
                                    {option:item.filename}
                                        <img src="/src/Frontend/Files/slideshow/thumbnails/{$item.filename}" />
                                    {/option:item.filename}
                                </div>
                            </div>

                            <div class="box">
                                <div class="heading">
                                    <h3>{$lblLink|ucfirst}</h3>
                                </div>
                                <div class="options">
                                    <p>
                                        <label for="externalLink">{$chkExternalLink|ucfirst} {$chkExternalLinkError}{$lblExternalLink|ucfirst}</label>
                                    </p>
                                    <p id="internalLinks">
                                        <label for="internalLink">{$lblInternalLink|ucfirst}</label>
                                        {$ddmInternalUrl} {$ddmInternalUrlError}
                                    </p>
                                    <p id="externalLinks">
                                        <label for="externalUrl">{$lblExternalLink|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                                        {$txtExternalUrl} {$txtExternalUrlError}
                                    </p>
                                </div>
                            </div>

                            <div class="box">
                                <div class="heading">
                                    <div class="oneLiner">
                                        <h3>{$lblCaption|ucfirst}</h3>
                                        <abbr class="help">(?)</abbr>
                                        <div class="tooltip" style="display: none;">
                                            <p>{$msgHelpCaption}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="optionsRTE">
                                    {$txtCaption} {$txtCaptionError}
                                </div>
                            </div>

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
            <a href="{$var|geturl:'DeleteImage'}&amp;id={$item.id}&amp;gallery_id={$item.gallery_id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
                <span>{$lblDelete|ucfirst}</span>
            </a>
            <div class="buttonHolderRight">
                <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
            </div>
        </div>

        <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
            <p>
                {$msgConfirmDeleteImage}
            </p>
        </div>

{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
