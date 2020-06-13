(function(Eventgallery, jQuery){

    jQuery( document ).ready(function() {

        jQuery('.scale-price-editor').each(function(index, item){
            new Eventgallery.ScalePriceEditor(item);
        });

        let googlePhotosProcessor = new Eventgallery.GooglePhotosProcessor();
        googlePhotosProcessor.processImages();

    });

})(Eventgallery, Eventgallery.jQuery);
