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
        jsBackend.slideshow.toggleLinks();
        jsBackend.slideshow.toggleThumbnail();

        // variables
        $title = $('#title');

        // do meta
        if($title.length > 0) $title.doMeta();
    },

    toggleLinks: function()
    {
        // checkbox checked?
        if(!$('#externalLink').is(':checked'))
        {
            $('#externalLinks').toggle();

            $('#externalLink').click(function(){
                $('#externalLinks').toggle();
                $('#internalLinks').toggle();
            });
        }
        // nope
        else
        {
            $('#internalLinks').toggle();

            $('#externalLink').click(function(){
                $('#externalLinks').toggle();
                $('#internalLinks').toggle();
            });
        }
    },

    toggleThumbnail: function()
    {
        // checkbox checked?
        if(!$('#controlNavigation').is(':checked'))
        {
            $('#thumbnailNavigationBox').toggle();

            $('#controlNavigation').click(function(){
                $('#thumbnailNavigationBox').toggle();
                $('#thumbnailNavigation').attr('checked', false);
            });
        }
        // nope
        else
        {
            $('#controlNavigation').click(function(){
                $('#thumbnailNavigationBox').toggle();
                $('#thumbnailNavigation').attr('checked', false);
            });
        }
    }

}

$(jsBackend.slideshow.init);
