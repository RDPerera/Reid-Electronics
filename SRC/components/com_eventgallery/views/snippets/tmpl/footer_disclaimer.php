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

<?php
$disclaimerObject = new EventgalleryLibraryDatabaseLocalizablestring($this->config->getCheckout()->getFooterDisclaimer());
$disclaimer = strlen($disclaimerObject->get())>0?$disclaimerObject->get():'';

?>

<?php IF ($this->config->getCart()->doUseCart() && $disclaimer!=''): ?>

<div class="eventgallery-footer-disclaimer">
    <small><?php echo $disclaimer; ?></small>
</div>

<?php ENDIF; ?>