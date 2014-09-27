{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblSlideshow|ucfirst}: {$lblGalleries}</h2>
    <div class="buttonHolderRight">
        <a href="{$var|geturl:'Add'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">
            <span>{$lblAddGallery|ucfirst}</span>
        </a>
    </div>
</div>

<div id="dataGridModelsHolder">
    {option:dataGrids}
        {iteration:dataGrids}
            <div class="dataGridHolder" id="dataGrid-{$dataGrids.id}">
                <div class="tableHeading">
                    <h3>{$dataGrids.title}</h3>
                </div>
                {option:dataGrids.content}
                    {$dataGrids.content}
                {/option:dataGrids.content}
            </div>
        {/iteration:dataGrids}
    {/option:dataGrids}
</div>

{option:!dataGrids}
    <p>{$msgNoItems}</p>
{/option:!dataGrids}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
