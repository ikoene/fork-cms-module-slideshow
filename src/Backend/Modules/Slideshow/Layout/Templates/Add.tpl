{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblGallery|ucfirst}: {$lblAdd}</h2>
</div>

{form:add}
    {option:categories}
        <p>
            {$lblTitle|ucfirst}<br/>
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
                                    <img src="{$FRONTEND_FILES_URL}/userfiles/images/slideshow/thumbnails/{$item.filename}" width="200" alt="" />
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
            </div>

        <div id="tabSEO">
            {include:{$BACKEND_CORE_PATH}/layout/templates/seo.tpl}
        </div>

        </div>
        <div class="fullwidthOptions">
            <div class="buttonHolderRight">
                <input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblPublish|ucfirst}" />
            </div>
        </div>
    {/option:categories}

{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
