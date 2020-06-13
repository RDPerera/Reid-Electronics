<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * @var \de\svenbluege\joomla\eventgallery\ObjectWithConfiguration $this
 */
?>

<div class="eventgallery-thumbnails eventgallery-imagelist thumbnails"
						data-rowheight="<?php echo $this->config->getEventImagelist()->getThumbnailHeight(); ?>"
	                    data-rowheightjitter="<?php echo $this->config->getEventImagelist()->getThumbnailJitter(); ?>"
	                    data-firstimagerowheight="<?php echo $this->config->getEventImagelist()->getFirstItemRowHeight(); ?>"
	                    data-dofilllastrow="<?php echo (isset($this->dofilllastrow) && $this->dofilllastrow==true)?"true":"false"; ?>">
    <?php foreach ($this->entries as $entry) : /** @var EventgalleryLibraryFile $entry */ ?>

	        <div class="thumbnail-container">

	            <?php $this->showContent=true; $this->entry=$entry; $this->cssClass="thumbnail"; echo $this->loadSnippet('event/inc/thumb'); ?>
	        </div>

    <?php endforeach ?>
    <div style="clear: both"></div>
</div>
