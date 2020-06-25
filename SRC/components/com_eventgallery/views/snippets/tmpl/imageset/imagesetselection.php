<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 * @var EventgalleryLibraryFolder $folder

 */
$folder = $this->folder;

if (!$folder->isCartable()  || !$this->config->getCart()->doUseCart()) {
    return;
}

?>
<script>
    (function() {
        var event;
        if(typeof(Event) === 'function') {
            event = new Event('eventgallery-images-added');
        }else{
            event = document.createEvent('Event');
            event.initEvent('eventgallery-images-added', true, true);
        }
        document.dispatchEvent(event);
    })();
</script>


    <div class="imagetypeselection-container">
        <div class="imagetypeselection">


            <?php
            if (count($this->imageset->getImageTypes(true)) == 1) {
                echo $this->loadSnippet('imageset/imageselection/single');
            } else {
                echo $this->loadSnippet('imageset/imageselection/multiple');
            }
            ?>


            <div class="btn-group pull-right">
                <button class="btn btn-primary eventgallery-close-overlay" title="<?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES_CLOSE_DESCRIPTION') ?>"><?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES_CLOSE') ?></button>
                <button class="btn btn-default eventgallery-opencart" title=""  data-href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=cart")?>"><i class="egfa egfa-shopping-cart"></i> <?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES_OPEN_CART') ?></button>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
