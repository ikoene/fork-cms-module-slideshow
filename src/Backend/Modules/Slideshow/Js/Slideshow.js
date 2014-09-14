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
    }

}

$(jsBackend.slideshow.init);
