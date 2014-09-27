{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblGallery|ucfirst}: {$item.title}</h2>
    <div class="buttonHolderRight">
        {option:detailURL}
            <a href="{$detailURL}/{$item.url}" class="button icon iconZoom previewButton targetBlank">
                <span>{$lblView|ucfirst}</span>
            </a>
        {/option:detailURL}
    </div>
</div>

{form:edit}
    <p>
        <label for="title">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
        {$txtTitle} {$txtTitleError}
    </p>

    <div id="pageUrl">
        <div class="oneLiner">
            {option:detailURL}<p><span><a href="{$detailURL}/{$item.url}">{$detailURL}/<span id="generatedUrl">{$item.url}</span></a></span></p>{/option:detailURL}
            {option:!detailURL}<p class="infoMessage">{$errNoModuleLinked}</p>{/option:!detailURL}
        </div>
    </div>

    <div class="tabs">
        <ul>
            <li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
            <li><a href="#images">{$lblImages|ucfirst} ({$imageCount})</a></li>
            {option:settingsPerSlideshow}
            <li><a href="#settings">{$lblSettings|ucfirst}</a></li>
            {/option:settingsPerSlideshow}
            <li><a href="#tabSEO">{$lblSEO|ucfirst}</a></li>
        </ul>

    <div class="ui-tabs">
        <div class="ui-tabs-panel">
            <div id="tabContent">

            <div class="options">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td id="leftColumn">

                            <div class="box">
                                <div class="heading">
                                    <h3>{$lblDescription|ucfirst}</h3>
                                </div>
                                <div class="optionsRTE">
                                    {$txtDescription} {$txtDescriptionError}
                                </div>
                            </div>

                            <div class="box">
                                <div class="heading">
                                    <h3>
                                        <label for="profile picture">{$lblImage|ucfirst}</label>
                                    </h3>
                                </div>
                                <div class="options">
                                    {$fileFilename} {$fileFilenameError}
                                </div>
                                {option:item.filename}
                                    <div class="options">
                                            <img src="/src/Frontend/Files/slideshow/{$item.filename}" width="140" />
                                            <ul class="inputList">
                                                <li>
                                                    {$chkDeleteImage} <label for="deleteImage">{$lblDelete|ucfirst}</label>
                                                </li>
                                            </ul>
                                    </div>
                                {/option:item.filename}

                            </div>

                        </td>

                        <td id="sidebar">

                            <div id="slideshowCategory" class="box">
                                <div class="heading">
                                    <h3>{$lblCategory|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></h3>
                                </div>

                                <div class="options">
                                    <p>
                                        {$ddmCategories} {$ddmCategoriesError}
                                    </p>
                                </div>
                            </div>

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

                                <div class="options">
                                    <p class="p0"><label for="publishOnDate">{$lblPublishOn|ucfirst}</label></p>
                                    <div class="oneLiner">
                                    <p>
                                        {$txtPublishOnDate} {$txtPublishOnDateError}
                                    </p>
                                    <p>
                                        <label for="publishOnTime">{$lblAt}</label>
                                    </p>
                                    <p>
                                        {$txtPublishOnTime} {$txtPublishOnTimeError}
                                    </p>
                                    </div>
                                </div>
                            </div>

                                <div id="publishOptions" class="box">
                                    <div class="heading">
                                        <h3>{$lblDimensions|ucfirst}</h3>
                                    </div>
                                    <div class="options">
                                        <label for="width">{$lblWidth|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                                        <p>
                                            {$txtWidth} {$txtWidthError}
                                        </p>
                                        <label for="height">{$lblHeight|ucfirst}</label>
                                        <p>
                                            {$txtHeight} {$txtHeightError}
                                        </p>
                                    </div>
                                </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="images">
            <p>
                <a href="{$var|geturl:'AddImage'}&amp;id={$item.id}" class="button icon iconAdd" title="{$lblAddImages|ucfirst}">
                    <span>{$lblAddImages|ucfirst}</span>
                </a>
            </p>
            {option:dataGrid}
            <div class="dataGridHolder">
            {$dataGrid}
            </div>
            {/option:dataGrid}

            {option:!dataGrid}
                <p>{$msgNoItems}</p>
            {/option:!dataGrid}
        </div>

{option:settingsPerSlideshow}
<div id="settings">
    <div class="box horizontal">
        <div class="heading">
            <h3>{$lblGeneralSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <p>
                <label for="animation">{$lblAnimation|ucfirst}</label>
                {$ddmAnimation} {$ddmAnimationError}
            </p>
        </div>
        <div class="options">
            <p>
                <label for="direction">{$lblDirection|ucfirst}</label>
                {$ddmDirection} {$ddmDirectionError}
            </p>
        </div>
        <div class="options">
            <p>
                <label for="slideshow_speed">{$lblSlideshowSpeed|ucfirst}</label>
                {$ddmSlideshowSpeed} {$ddmSlideshowSpeedError}
                <span class="helpTxt">
                {$msgHelpSlideshowSpeed}
                </span>
            </p>
        </div>
        <div class="options">
            <p>
                <label for="animation_speed">{$lblAnimationSpeed|ucfirst}</label>
                {$ddmAnimationSpeed} {$ddmAnimationSpeedError}
                <span class="helpTxt">
                {$msgHelpAnimationSpeed}
                </span>
            </p>
        </div>
    </div>

    <div class="box">
        <div class="heading">
            <h3>{$lblNavigationSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <ul class="inputList">
                <li>
                    <label for="direction_navigation">
                        {$chkDirectionNavigation} {$lblDirectionNavigation|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpDirectionNavigation}
                    </span>
                </li>
                <li>
                    <label for="control_navigation">
                        {$chkControlNavigation} {$lblControlNavigation|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpControlNavigation}
                    </span>
                </li>
                <li id="thumbnailNavigationBox">
                    <label for="thumbnail_navigation">
                        {$chkThumbnailNavigation} {$lblThumbnailNavigation|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpThumbnailNavigation}
                    </span>
                </li>
                <li>
                    <label for="keyboard">
                        {$chkKeyboard} {$lblKeyboard|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpKeyboard}
                    </span>
                </li>
                <li>
                    <label for="mousewheel">
                        {$chkMousewheel} {$lblMousewheel|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpMousewheel}
                    </span>
                </li>
                <li>
                    <label for="touch">
                        {$chkTouch} {$lblTouch|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpTouch}
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="box">
        <div class="heading">
            <h3>{$lblOptionalSlideshowSettings|ucfirst}</h3>
        </div>
        <div class="options">
            <ul class="inputList">
                <li>
                    <label for="randomize">
                        {$chkRandomize} {$lblRandomize|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpRandomize}
                    </span>
                </li>
                <li>
                    <label for="auto_animate">
                        {$chkAutoAnimate} {$lblAutoAnimate|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpAutoAnimate}
                    </span>
                </li>
                <li>
                    <label for="animation_loop">
                        {$chkAnimationLoop} {$lblAnimationLoop|ucfirst}
                    </label>
                    <span class="helpTxt">
                        {$msgHelpAnimationLoop}
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
{/option:settingsPerSlideshow}

        <div id="tabSEO">
            {include:{$BACKEND_CORE_PATH}/Layout/Templates/Seo.tpl}
        </div>

        </div>
        <div class="fullwidthOptions">
            <a href="{$var|geturl:'Delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
                <span>{$lblDelete|ucfirst}</span>
            </a>
            <div class="buttonHolderRight">
                <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
            </div>
        </div>

        <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
            <p>
                {$msgConfirmDeleteCategory|sprintf:{$item.title}}
            </p>
        </div>

{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
