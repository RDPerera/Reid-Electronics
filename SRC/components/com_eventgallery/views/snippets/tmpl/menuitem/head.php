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


$ls = new EventgalleryLibraryDatabaseLocalizablestring($this->config->getEventsList()->getGreetings());
$greetings = JHtml::_('content.prepare', $ls->get(), '', 'com_eventgallery.event');

?>

<?php IF ($this->config->getMenuItem()->doShowPageHeading()) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->config->getMenuItem()->getPageHeading()); ?> </h1>
    </div>
<?php ENDIF; ?>

<?php IF (strlen($greetings)>0): ?>
    <p class="greetings"><?php echo $greetings; ?></p>
<?php ENDIF; ?>