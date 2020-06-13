(function ($) {
    $(document).ready(function() {
        var lightBox = new CreativeImageSliderLightbox();
        // create lightbox
        lightBox.createLightbox();


        $('div.cis_main_wrapper').each(function (index, el) {
            var options = $(el).data();
            options.lightbox = lightBox;

            var slider = new CreativeImageSlider(options);
        });

    });
})(creativeJ);



