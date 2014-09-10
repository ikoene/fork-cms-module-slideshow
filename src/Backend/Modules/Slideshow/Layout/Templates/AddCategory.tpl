{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

{form:add_category}
    <div class="pageTitle">
        <h2>{$lblSlideshow|ucfirst}: {$lblAddCategory}</h2>
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
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="addCategory" value="{$lblAddCategory|ucfirst}" />
        </div>
    </div>
{/form:add_category}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
