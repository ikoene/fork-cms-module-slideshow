{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblGallery|ucfirst}: {$item.title}</h2>
    <div class="buttonHolderRight">
        <a href="{$var|geturl:'add_image'}&amp;id={$item.id}" class="button icon iconAdd" title="{$lblAddImages|ucfirst}">
            <span>{$lblAddImages|ucfirst}</span>
        </a>
    </div>
</div>

{form:edit}
    {option:categories}
    <p>
            {$lblSlideshow|ucfirst}<br/>
            {$txtTitle} {$txtTitleError}
    </p>

    <div id="pageUrl">
        <div class="oneLiner">
            {option:detailURL}<p><span><a href="{$detailURL}">{$detailURL}/<span id="generatedUrl"></span></a></span></p>{/option:detailURL}
            {option:!detailURL}<p class="infoMessage">{$errNoModuleLinked}</p>{/option:!detailURL}
        </div>
    </div>

    <div class="tabs">
        <ul>
            <li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
            <li><a href="#images">{$lblImages|ucfirst}</a></li>
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
                                    <h3>{$lblDescription|ucfirst}<abbr title="{$lblRequiredField}"></abbr></h3>
                                </div>
                                <div class="optionsRTE">
                                    {$txtDescription} {$txtDescriptionError}
                                </div>
                            </div>

                                <p>
                                    {option:item.filename}
                                    <p>
                                        <img src="/src/Frontend/Files/userfiles/images/slideshow/thumbnails/{$item.filename}" width="200" alt="" />
                                    </p>
                                    <p>
                                        <label for="deleteImage">{$chkDeleteImage} {$lblDelete|ucfirst}</label>
                                        {$chkDeleteImageError}
                                    </p>
                                    {/option:item.filename}
                                    <label for="filename">{$lblImage|ucfirst}</label>
                                    {$fileFilename} {$fileFilenameError}
                                </p>

                        </td>

                        <td id="sidebar">

                            <div id="slideshowCategory" class="box">
                                <div class="heading">
                                    <h3>{$lblCategory|ucfirst}</h3>
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
                                        {$lblWidth|ucfirst}
                                        <p>
                                        {$txtWidth} {$txtWidthError}
                                        </p>
                                        {$lblHeight|ucfirst}
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
                <label for="animation_type">{$lblAnimationType|ucfirst}</label>
                {$ddmAnimationType} {$ddmAnimationTypeError}
            </p>
        </div>
        <div class="options">
            <p>
                <label for="slide_direction">{$lblSlideDirection|ucfirst}</label>
                {$ddmSlideDirection} {$ddmSlideDirectionError}
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
                <label for="animation_duration">{$lblAnimationDuration|ucfirst}</label>
                {$ddmAnimationDuration} {$ddmAnimationDurationError}
                <span class="helpTxt">
                {$msgHelpAnimationDuration}
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
                <li><label for="direct_navigation">{$chkDirectNavigation} {$lblDirectNavigation|ucfirst}</label>
                    <span class="helpTxt">
                    {$msgHelpDirectNavigation}
                    </span>
                </li>
                <li><label for="control_navigation">{$chkControlNavigation} {$lblControlNavigation|ucfirst}</label>
                    <span class="helpTxt">
                    {$msgHelpControlNavigation}
                    </span>
                </li>
                <li><label for="keyboard_navigation">{$chkKeyboardNavigation} {$lblKeyboardNavigation|ucfirst}</label>
                    <span class="helpTxt">
                    {$msgHelpKeyboardNavigation}
                    </span>
                </li>
                <li><label for="mousewheel_navigation">{$chkMousewheelNavigation} {$lblMousewheelNavigation|ucfirst}</label>
                    <span class="helpTxt">
                    {$msgHelpMousewheelNavigation}
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
                <li><label for="random_order">{$chkRandomOrder} {$lblRandomOrder|ucfirst}</label>
                    <span class="helpTxt">
                    {$msgHelpRandomOrder}
                    </span>
                </li>
                <li><label for="auto_animate">{$chkAutoAnimate} {$lblAutoAnimate|ucfirst}</label>
                    <span class="helpTxt">
                    {$msgHelpAutoAnimate}
                    </span>
                </li>
                <li><label for="animation_loop">{$chkAnimationLoop} {$lblAnimationLoop|ucfirst}</label>
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
            {include:{$BACKEND_CORE_PATH}/layout/templates/seo.tpl}
        </div>

        </div>
        <div class="fullwidthOptions">
            <a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
                <span>{$lblDelete|ucfirst}</span>
            </a>
            <div class="buttonHolderRight">
                <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblPublish|ucfirst}" />
            </div>
        </div>

        <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
            <p>
                Verwijderen? {$item.title}
            </p>
        </div>
    {/option:categories}

{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
