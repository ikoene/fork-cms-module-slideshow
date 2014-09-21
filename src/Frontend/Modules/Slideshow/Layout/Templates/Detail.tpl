{*
    variables that are available:
    - {$gallery}: contains data about the gallery
    - {$slideshow}: contains an array with the images of the gallery, each element contains data about the image.
    - {$navigation}: contains an array with data for previous and next post
*}

{option:gallery}

        {* Title *}
            <h3>
                {$gallery.title}
            </h3>

        {* Meta *}
            <ul>
                <li>
                {$gallery.publish_on|date:{$dateFormatLong}:{$LANGUAGE}}
                </li>
            </ul>

        {* Slideshow *}
            <div class="flex-container" style="max-width:{$gallery.width}px">
                <div class="flexslider">
                    <div class="flexsliderwrap{$gallery.id}">
                        <ul class="slides">
                            {iteration:slideshow}
                            <li data-thumb="/src/Frontend/Files/slideshow/thumbnails/{$slideshow.filename}">
                                {option:slideshow.data.link}
                                <a href="{$slideshow.data.link.url}"{option:slideshow.data.link.external} target="_blank" rel="nofollow"{/option:slideshow.data.link.external} title="{$slideshow.title}">
                                {/option:slideshow.data.link}
                                    <img src="/src/Frontend/Files/slideshow/{$slideshow.filename}" alt="{$slideshow.title}" />
                                {option:slideshow.data.link}
                                </a>
                                {/option:slideshow.data.link}
                                {option:slideshow.caption}<p class="flex-caption">{$slideshow.caption}</p>{/option:slideshow.caption}
                            </li>
                            {/iteration:slideshow}
                        </ul>
                    </div>
                </div>
            </div>

        {* Navigation *}
            <ul class="slideshowPageNavigation">
                {option:navigation.previous}
                    <li class="previousLink">
                        <a href="{$navigation.previous.url}" rel="prev">{$lblPreviousSlideshow|ucfirst}: {$navigation.previous.title}</a>
                    </li>
                {/option:navigation.previous}
                {option:navigation.next}
                    <li class="nextLink">
                        <a href="{$navigation.next.url}" rel="next">{$lblNextSlideshow|ucfirst}: {$navigation.next.title}</a>
                    </li>
                {/option:navigation.next}
            </ul>

{/option:gallery}
