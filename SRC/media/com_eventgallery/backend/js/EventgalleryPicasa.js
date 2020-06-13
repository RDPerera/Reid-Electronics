(function(Eventgallery, jQuery){
    "use strict";

    /**
     * PICASA process selector input
     */
    jQuery(window).on('message', function(e) {
        var dataStr = e.originalEvent.data;
        if (typeof dataStr !== 'string') {
            return;
        }
        if (dataStr.startsWith('eventgalleryAlbum_')) {
            var data = JSON.parse(dataStr.replace('eventgalleryAlbum_',''));
            jQuery('#foldertype-1-user').val(data.userid).trigger('onchange');
            jQuery('#foldertype-1-album').val(data.albumid).trigger('onchange');
            jQuery('#foldertype-1-picasakey').val(data.authkey).trigger('onchange');
            document.querySelector('#album-selector-modal .modal-header button.close').click();
        }
    });

    /**
     * PICASA open oauth window
     */

    jQuery(document).on('click', '.google-oauth-trigger-button', function(e){
        e.preventDefault();
        var oauthWindow = window.open("https://accounts.google.com/o/oauth2/auth?scope=https://picasaweb.google.com/data/%20https://www.googleapis.com/auth/youtubepartner&response_type=code&access_type=offline&redirect_uri=https://www.svenbluege.de/picasa/v1.4/oauth2.php&approval_prompt=force&client_id=765859880369-7ouk5plitha96v57hbkbpko5tgnmhv8g.apps.googleusercontent.com","_blank","width=700,height=400");
        if(!oauthWindow || oauthWindow.closed || typeof oauthWindow.closed=='undefined')
        {
            alert('Failed');
        }
    });


    /**
     * PICASA refresh token
     */
    jQuery(window).on('message', function(e) {
        var dataStr = e.originalEvent.data;
        if (typeof dataStr !== 'string') {
            return;
        }
        if (dataStr.startsWith('eventgallery_')) {
            var data = JSON.parse(dataStr.replace('eventgallery_',''));
            jQuery('.google-oauth-input').val(data.refresh_token);
        }
    });

    /**
     * process selector input
     */
    jQuery(window).on('message', function(e) {
        var dataStr = e.originalEvent.data;
        if (typeof dataStr !== 'string') {
            return;
        }
        if (dataStr.startsWith('eventgalleryGooglePhotosAlbum_')) {
            var data = JSON.parse(dataStr.replace('eventgalleryGooglePhotosAlbum_',''));
            jQuery('#foldertype-4-user').val(data.userid).trigger('onchange');
            jQuery('#foldertype-4-album').val(data.albumid).trigger('onchange');
            jQuery('#foldertype-4-title').val(data.title).trigger('onchange');
            document.querySelector('#google-photos-album-selector-modal .modal-header button.close').click();
        }
    });

    /**
     * open oauth window for Google Photos API
     */

    jQuery(document).on('click', '.google-photos-api-oauth-trigger-button', function(e){
        e.preventDefault();
        var id = e.target.getAttribute('data-id');

        var oauthWindow = window.open("index.php?option=com_eventgallery&view=googlephotos&layout=getauthtoken&tmpl=component&id="+id,"_blank","width=700,height=400");
        if(!oauthWindow || oauthWindow.closed || typeof oauthWindow.closed === 'undefined')
        {
            alert('Failed');
        }
    });



})(Eventgallery, Eventgallery.jQuery);
