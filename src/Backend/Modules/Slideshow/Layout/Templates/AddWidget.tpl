{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

{form:add_widget}
    <div class="pageTitle">
        <h2>{$lblSlideshow|ucfirst}: {$lblAddWidget}</h2>
    </div>

    <div class="box horizontal">
        <div class="heading">
            <h3>{$lblSlideshow|ucfirst}: {$lblAddWidget}</h3>
        </div>
        <div class="options">
            <p>
                <label for="name">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                {$txtTitle} {$txtTitleError}
            </p>
        </div>
        <div class="options">
            <p>
                <label for="name">{$lblSlideshows|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                {$ddmSlideshows} {$ddmSlideshowsError}
            </p>
        </div>
    </div>

    <div class="fullwidthOptions">
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="addCategory" value="{$lblAddWidget|ucfirst}" />
        </div>
    </div>
{/form:add_widget}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
