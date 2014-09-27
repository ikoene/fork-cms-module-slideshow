{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblSlideshow|ucfirst}: {$item.title} ({$item.width} x {$item.height}{option:!item.height}?{/option:!item.height})</h2>
</div>

{form:add}
    <div class="ui-tabs">
        <div class="ui-tabs-panel">
            <div class="options">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td id="leftColumn">
                            <p>
                                <label for="filename">{$lblImages|ucfirst}</label>
                                {iteration:imageInput}
                                <p>
                                {$imageInput.Image} {$imageInput.ImageError}
                                </p>
                                {/iteration:imageInput}
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="fullwidthOptions">
            <div class="buttonHolderRight">
                <input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblAdd|ucfirst}" />
            </div>
        </div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
