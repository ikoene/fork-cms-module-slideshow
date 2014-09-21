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
    slideshow: (jsFrontend.data.get('Slideshow.slideshowSettings.slideshow') == "true") ? true : false,
    animationLoop: (jsFrontend.data.get('Slideshow.slideshowSettings.animation_loop') == "true") ? true : false
};


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
};

// use thumbnails?
if (settings.thumbnailNavigation) {
    settings.controlNav = 'thumbnails';
}

// set slideshow settings
function flexslider () {
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
        randomize: settings.randomize,
        slideshow: settings.slideshow,
        animationLoop: settings.animationLoop
    });
};

addLoadEvent(flexslider);
