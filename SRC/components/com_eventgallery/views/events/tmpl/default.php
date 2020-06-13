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

<?php echo  $this->loadSnippet("cart"); ?>

<?php echo  $this->loadSnippet("menuitem/head"); ?>

<?php echo  $this->loadSnippet("events/" . $this->config->getEventsList()->getEventsLayout() ); ?>

<?php echo $this->loadSnippet('footer_disclaimer'); ?>