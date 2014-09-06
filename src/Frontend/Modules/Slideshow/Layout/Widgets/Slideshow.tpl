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
                            <li data-thumb="/src/Frontend/Files/userfiles/images/slideshow/thumbnails/{$widgetSlideshow.filename}">
                                {option:widgetSlideshow.link}<a href="{$widgetSlideshow.link}">{/option:widgetSlideshow.link}
                                    <img src="/src/Frontend/Files/userfiles/images/slideshow/{$widgetSlideshow.filename}" alt="{$widgetSlideshow.title}" />
                                {option:widgetSlideshow.link}</a>{/option:widgetSlideshow.link}
                                {option:widgetSlideshow.title}<p class="flex-caption">{$widgetSlideshow.title}</p>{/option:widgetSlideshow.title}
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
                controlsContainer: "#flex{$widgetGallery.id}",
                animation: "{$widgetSlideshowSettings.animation_type}",
                slideDirection: "{$widgetSlideshowSettings.slide_direction}",
                slideshowSpeed: {$widgetSlideshowSettings.slideshow_speed}000,
                animationDuration:{$widgetSlideshowSettings.animation_duration}000,
                directionNav:{$widgetSlideshowSettings.direct_navigation},
                controlNav:{$widgetSlideshowSettings.control_navigation},
                keyboardNav:{$widgetSlideshowSettings.keyboard_navigation},
                mousewheel:{$widgetSlideshowSettings.mousewheel_navigation},
                randomize:{$widgetSlideshowSettings.random_order},
                slideshow:{$widgetSlideshowSettings.auto_animate},
                animationLoop:{$widgetSlideshowSettings.animation_loop}
            });
        };

        addLoadEvent(flex{$widgetGallery.id});
    </script>

    {/option:widgetGallery}
