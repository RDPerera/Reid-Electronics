<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();

$document->addScript('//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.js');
$document->addScript('//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/locale/bootstrap-table-en-US.min.js');
$document->addStyleSheet('//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.css');

$dataUrl = JRoute::_('index.php?option=com_eventgallery&task=googlephotos.getAlbums', false);
?>

<p>

</p>

<script>
    var tableEntries,
        profile,
        id_token,
        access_token,
        dataUrl,
        element,
        accountid;

    /**
     * try to grab the accountid from the window which opened this iframe.
     *
     * @type {string}
     */
    dataUrl = '<?php echo $dataUrl ?>';
    element = parent.document.getElementById('foldertype_4_account');
    accountid = null;
    if (element) {
        accountid = element.value;
        dataUrl = dataUrl +'&id=' + accountid
    }

    function hideLoading() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('table').style.opacity = 1;
    }

    function showLoading() {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('table').style.opacity = 0.2;
    }

    function loadAlbums() {

        showLoading();

        jQuery.ajax(
            {
                url: dataUrl,
                dataType: 'json',
                method:'GET',
                success: (data, textStatus, xhr) => {
                    console.log('request done', data);
                    renderAlbums(data.albums);
                },
                error: (xhr, error, err) => {console.log(error, err)}
            });

    }

    function renderAlbums(albums) {
        let tableEntries = [];

        if(albums !== undefined) {


            for (let i = 0; i < albums.length; i++) {

                let entry = albums[i],
                    tableEntry = {};

                tableEntry.link = entry.productUrl;
                tableEntry.title = entry.title;
                tableEntry.albumid = entry.id;
                tableEntry.previewThumbnail = entry.coverPhotoBaseUrl + '=w200-h200';
                tableEntry.mediaItemsCount = entry.mediaItemsCount;

                tableEntries.push(tableEntry);
            }
        }
        jQuery('#table').bootstrapTable('load', tableEntries);

        hideLoading();
    }

    jQuery( document ).ready(function() {

        jQuery('#table').bootstrapTable({
            search: true,
            columns: [
                {
                    field: 'operations',
                    title: 'Operations',
                    formatter: function(value, row, index) {
                        return '<button data-title="'+ encodeURI(row.title) +'" data-albumid="' + row.albumid + '" class="btn btn-default select-album" href="">Select</button>';
                    }
                },
                {
                    field: 'previewThumbnail',
                    formatter: function(value) { return '<img src="' + value + '">'; },
                    title: 'Cover'
                },
                {
                    field: 'title',
                    title: 'Title',
                    sortable: true
                },
                {
                    field: 'mediaItemsCount',
                    title: 'Item Count',
                    sortable: true
                },
                {
                    field: 'link',
                    formatter: function(value) { return '<a class="albumlink" href="' + value + '" target="_blank">Link</a>'; },
                    title: 'Link'
                }
            ], data: tableEntries

        });

        jQuery(document).on('click', '.select-album', function(e){
            e.preventDefault();
            e.stopPropagation();
            let $target = jQuery(e.target),
                data = {
                    albumid: $target.attr("data-albumid"),
                    title: decodeURI($target.attr("data-title"))
                };

            parent.postMessage('eventgalleryGooglePhotosAlbum_' + JSON.stringify(data), '*');
        });

        loadAlbums();
    });
</script>

<p id="login-info"></p>

<div id="loading" class="well">
    <p>
        <?php echo JText::_('COM_EVENTGALLERY_OPTIONS_COMMON_GOOGLE_PHOTOS_API_ALBUM_SELECTOR_LOADING');?>
    </p>
</div>



<table id="table" class="table table-striped"></table>
