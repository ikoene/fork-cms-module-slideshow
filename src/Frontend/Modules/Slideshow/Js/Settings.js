jsFrontend.slideshow =
{
    // init, something like a constructor
    init: function()
    {
        // build the settings
        var settings =
        {
            id: jsFrontend.data.get('Slideshow.slideshowSettings.id'),
            animation: jsFrontend.data.get('Slideshow.slideshowSettings.animation'),
            direction: jsFrontend.data.get('Slideshow.slideshowSettings.direction'),
            slideshowSpeed: jsFrontend.data.get('Slideshow.slideshowSettings.slideshow_speed') + '000',
            animationSpeed: jsFrontend.data.get('Slideshow.slideshowSettings.animation_speed') + '000',
            thumbnailNavigation: (jsFrontend.data.get('Slideshow.slideshowSettings.thumbnail_navigation') == "true") ? true : false,
            directionNav: (jsFrontend.data.get('Slideshow.slideshowSettings.direction_navigation') == "true") ? true : false,
            controlNav: (jsFrontend.data.get('Slideshow.slideshowSettings.control_navigation') == "true") ? true : false,
            randomize: (jsFrontend.data.get('Slideshow.slideshowSettings.randomize') == "true") ? true : false,
            keyboard: (jsFrontend.data.get('Slideshow.slideshowSettings.keyboard') == "true") ? true : false,
            mousewheel: (jsFrontend.data.get('Slideshow.slideshowSettings.mousewheel') == "true") ? true : false,
            touch: (jsFrontend.data.get('Slideshow.slideshowSettings.touch') == "true") ? true : false,
            auto_animate: (jsFrontend.data.get('Slideshow.slideshowSettings.auto_animate') == "true") ? true : false,
            animationLoop: (jsFrontend.data.get('Slideshow.slideshowSettings.animation_loop') == "true") ? true : false
        };

        // use thumbnails?
        if (settings.thumbnailNavigation) {
            settings.controlNav = 'thumbnails';
        }

        // set slideshow settings
        $('.flexsliderwrap' + settings.id).flexslider({
            controlsContainer: ".flexsliderwrap"  + settings.id,
            animation: settings.animation,
            direction: settings.direction,
            slideshowSpeed: settings.slideshowSpeed,
            animationSpeed: settings.animationSpeed,
            directionNav: settings.directionNav,
            controlNav: settings.controlNav,
            keyboard: settings.keyboard,
            mousewheel: settings.mousewheel,
            touch: settings.touch,
            randomize: settings.randomize,
            slideshow: settings.auto_animate,
            animationLoop: settings.animationLoop
        });
    }
}

$(jsFrontend.slideshow.init);
