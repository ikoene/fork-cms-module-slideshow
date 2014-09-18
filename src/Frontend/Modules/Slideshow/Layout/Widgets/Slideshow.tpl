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

        <script type="text/javascript">
        // loading multiple onload functions when using multiple widgets on one page
        function addLoadEvent(func) {
            var oldonload = window.onload;
                if (typeof window.onload != 'function') {
                    window.onload = func;
                } else {
                    window.onload = function() {
                    if (oldonload) {
                        oldonload();
                    }
                func();
                }
            }
        }

        // set custom slideshow options
        function flex{$widgetGallery.id}() {
            $('.flexsliderwrap{$widgetGallery.id}').flexslider({
                controlsContainer: ".flexsliderwrap{$widgetGallery.id}",
                animation: "{$widgetSlideshowSettings.animation}",
                direction: "{$widgetSlideshowSettings.direction}",
                slideshowSpeed: {$widgetSlideshowSettings.slideshow_speed}000,
                animationSpeed:{$widgetSlideshowSettings.animation_speed}000,
                directionNav:{$widgetSlideshowSettings.direct_navigation},

                {option:!widgetSlideshowSettings.thumbnail_navigation}
                    controlNav:{$widgetSlideshowSettings.control_navigation},
                {/option:!widgetSlideshowSettings.thumbnail_navigation}
                {option:widgetSlideshowSettings.thumbnail_navigation}
                    controlNav: "thumbnails",
                {/option:widgetSlideshowSettings.thumbnail_navigation}

                keyboard:{$widgetSlideshowSettings.keyboard},
                mousewheel:{$widgetSlideshowSettings.mousewheel},
                randomize:{$widgetSlideshowSettings.randomize},
                slideshow:{$widgetSlideshowSettings.auto_animate},
                animationLoop:{$widgetSlideshowSettings.animation_loop}
            });
        };

        addLoadEvent(flex{$widgetGallery.id});
    </script>

    {/option:widgetGallery}
