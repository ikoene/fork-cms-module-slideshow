/**
 * Interaction for the slideshows
 *
 * @author Koen Vinken <koen@tagz.be>
 */
jsBackend.slideshow =
{
    // init, something like a constructor
    init: function()
    {
        // variables
        $title = $('#title');

        // do meta
        if($title.length > 0) $title.doMeta();
    }
}

$(document).ready(function() {jsBackend.slideshow.init();});
