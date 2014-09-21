{*
    variables that are available:
    - {$gallery}: contains data about the gallery
    - {$slideshow}: contains an array with the images of the gallery, each element contains data about the image.
*}

    {option:widgetGallery}
        {* Title *}
            <h3>
                {$widgetGallery.title}
            </h3>

        {* Slideshow *}
            <div class="flex-container" id="flex{$widgetGallery.id}" style="max-width:{$widgetGallery.width}px">
                <div class="flexslider">
                    <div class="flexsliderwrap{$widgetGallery.id}">
                        <ul class="slides">
                        {iteration:widgetSlideshow}
                            <li data-thumb="/src/Frontend/Files/slideshow/thumbnails/{$widgetSlideshow.filename}">
                                {option:widgetSlideshow.data.link}
                                <a href="{$widgetSlideshow.data.link.url}"{option:widgetSlideshow.data.link.external} target="_blank" rel="nofollow"{/option:widgetSlideshow.data.link.external} title="{$widgetSlideshow.title}">
                                {/option:widgetSlideshow.data.link}
                                    <img src="/src/Frontend/Files/slideshow/{$widgetSlideshow.filename}" alt="{$widgetSlideshow.title}" />
                                {option:widgetSlideshow.data.link}
                                </a>
                                {/option:widgetSlideshow.data.link}
                                {option:widgetSlideshow.caption}<p class="flex-caption">{$widgetSlideshow.caption}</p>{/option:widgetSlideshow.caption}
                            </li>
                        {/iteration:widgetSlideshow}
                        </ul>
                    </div>
                </div>
            </div>

    {/option:widgetGallery}
