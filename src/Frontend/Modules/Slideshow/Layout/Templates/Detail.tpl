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
                {option:gallery.description}{$gallery.description}{/option:gallery.description}
                </li>
            </ul>

        {* Slideshow *}
            <div class="flex-container" style="max-width:{$gallery.width}px">
                <div class="flexslider">
                    <div class="flexsliderwrap{$gallery.id}">
                        <ul class="slides">
                            {iteration:slideshow}
                            <li data-thumb="/src/Frontend/Files/userfiles/images/slideshow/thumbnails/{$slideshow.filename}">
                                {option:slideshow.link}<a href="{$slideshow.link}">{/option:slideshow.link}
                                    <img src="/src/Frontend/Files/userfiles/images/slideshow/{$slideshow.filename}" alt="{$slideshow.title}" />
                                {option:slideshow.link}</a>{/option:slideshow.link}
                                {option:slideshow.title}<p class="flex-caption">{$slideshow.title}</p>{/option:slideshow.title}
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
        function flex{$gallery.id}() {
            $('.flexsliderwrap{$gallery.id}').flexslider({
                controlsContainer: ".flex-container",
                animation: "{$slideshowSettings.animation_type}",
                slideDirection: "{$slideshowSettings.slide_direction}",
                slideshowSpeed: {$slideshowSettings.slideshow_speed}000,
                animationDuration:{$slideshowSettings.animation_duration}000,
                directionNav:{$slideshowSettings.direct_navigation},
                controlNav:{$slideshowSettings.control_navigation},
                keyboardNav:{$slideshowSettings.keyboard_navigation},
                mousewheel:{$slideshowSettings.mousewheel_navigation},
                randomize:{$slideshowSettings.random_order},
                slideshow:{$slideshowSettings.auto_animate},
                animationLoop:{$slideshowSettings.animation_loop}
            });
        };

        addLoadEvent(flex{$gallery.id});
        </script>

{/option:gallery}
