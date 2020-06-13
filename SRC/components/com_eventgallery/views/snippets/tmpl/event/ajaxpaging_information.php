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
 */
?>

<?php IF ($this->config->getEvent()->doShowDate()): ?>
    <h4 class="date">
        <?php echo JHtml::date($this->folder->getDate()); ?>
    </h4>
<?php ENDIF ?>

<?php echo $this->config->getEventsList()->renderEventHeadTag($this->folder->getDisplayName(), 'displayname'); ?>

<div class="text">
	<?php echo JHtml::_('content.prepare', $this->folder->getText(), '', 'com_eventgallery.event'); ?>
</div>

<div style="display:none">
	<?php 
	if (isset($this->titleEntries[0])) {
	    echo '<meta itemprop="image" content="'. $this->titleEntries[0]->getSharingImageUrl() .'" />';
        echo '<link rel="image_src" type="image/jpeg" href="'. $this->titleEntries[0]->getSharingImageUrl() .'" />';
	}
	?>
</div>
