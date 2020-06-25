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
 * @var EventgalleryLibraryLineitemcontainer $lineitemcontainer
 */
$lineitemcontainer = $this->lineitemcontainer;
?>

<div class="basic-information">
    <?php IF (!$lineitemcontainer->getShippingMethod()->needsAddressData()): ?>
        <p class="basic-name">
            <strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_NAME_LABEL') ?></strong><br />
            <?php echo $this->escape($lineitemcontainer->getFirstname()) ?> <?php echo $this->escape($lineitemcontainer->getLastname()) ?>
        </p>
    <?php ENDIF; ?>
    <p class="basic-email"><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_EMAIL_LABEL') ?></strong><br />
    <?php echo $this->escape($lineitemcontainer->getEMail()) ?></p>
    <?php IF (strlen($lineitemcontainer->getPhone())>0):?>
    <p class="basic-phone"><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_PHONE_LABEL') ?></strong><br />
    <?php echo $this->escape($lineitemcontainer->getPhone()) ?></p>
    <?php ENDIF; ?>
    <?php IF (strlen($lineitemcontainer->getMessage())>0):?>
    <p class="basic-message"><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_MESSAGE_LABEL') ?></strong><br />
    <?php echo $this->escape($lineitemcontainer->getMessage()) ?></p>
    <?php ENDIF; ?>
    <?php IF (null != $lineitemcontainer->getBillingAddress() && strlen($lineitemcontainer->getBillingAddress()->getTaxId())>0 ):?>
        <p class="basic-message"><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_BILLINGFORM_TAXID_LABEL') ?></strong><br />
        <?php echo $this->escape($lineitemcontainer->getBillingAddress()->getTaxId()); ?><br/>
    <?php ENDIF; ?>
</div>
