jsBackend.slideshow =
{
    // init, something like a constructor
    init: function()
    {
        // index stuff
        if($('#dataGridSlideshowHolder').length > 0)
        {
            // destroy default drag and drop
            $('.sequenceByDragAndDrop tbody').sortable('destroy');

            // drag and drop
            jsBackend.slideshow.bindDragAndDropCategorySlideshow();
            jsBackend.slideshow.checkForEmptyCategories();
        }
        
        // variables
        $title = $('#title');
        
        // do meta
        if($title.length > 0) $title.doMeta();
    }
}


$(document).ready(function() {jsBackend.slideshow.init();});

$(document).ready(function() { jsBackend.photogallery.init(); });