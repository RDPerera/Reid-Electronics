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



<div class="eventgallery-checkout eventgallery-review-page">
    <h1><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_HEADLINE') ?></h1>
    <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_TEXT') ?>&nbsp;

    <form action="<?php echo JRoute::_("index.php?option=com_eventgallery&view=checkout&task=createOrder") ?>"
          method="post" class="form-validate form-horizontal checkout-form">

        <?php $this->set('edit',true); $this->set('lineitemcontainer',$this->cart); echo $this->loadSnippet('checkout/summary') ?>

        <div class="clearfix"></div>

        <?php IF ($this->config->getCheckout()->doUseTermsAndConditionsCheckbox()):?>
        <fieldset>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox">                  
                        <input type="checkbox" name="tac" class="validate required">    
                        <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_TERMCONDITIONS_CHECKBOX_LABEL') ?>                        
                    </label>
                </div>
            </div>
        </fieldset>
        <?php ENDIF; ?>
        <fieldset>
            <div class="control-group">
                <div class="controls">
                    <label>                                          
                        <?php
                            $disclaimerObject = new EventgalleryLibraryDatabaseLocalizablestring($this->config->getCheckout()->getCheckoutDisclaimer());
                            $disclaimer = strlen($disclaimerObject->get())>0?$disclaimerObject->get():JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_DISCLAIMER');
                        ?>
                        
                        <ul class="nav nav-pills">
                            <li><a class="disclaimer" data-eventgallery-overlay="content" href="#mb_disclaimer"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_DISCLAIMER')?></a></li>
                            <?php IF(strlen($this->config->getCheckout()->getPrivacyPolicyLink())>0):?>
                                <li><a class="pp"       target="_blank" rel="nofollow" href="<?php echo $this->config->getCheckout()->getPrivacyPolicyLink();?>"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_PRIVACYPOLICY');?></a></li>
                            <?php ENDIF?>
                            <?php IF(strlen($this->config->getCheckout()->getTermsAndConditionsLink())>0):?>
                                <li><a class="tac"      target="_blank" rel="nofollow" href="<?php echo $this->config->getCheckout()->getTermsAndConditionsLink();?>"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_TAC');?></a></li>
                            <?php ENDIF?>
                            <?php IF(strlen($this->config->getCheckout()->getImpressLink())>0):?>
                                <li><a class="impress"  target="_blank" rel="nofollow" href="<?php echo $this->config->getCheckout()->getImpressLink();?>"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_IMPRESS');?></a></li>
                            <?php ENDIF?>
                        </ul>
                    </label>
                     <div style="display:none">
                        <div id="mb_disclaimer" class="inline-content">
                            <div class="eventgallery-checkout-disclaimer-content">
                                <?php  echo $disclaimer; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-actions">
                  <a class="btn" href="<?php echo JRoute::_(
                        "index.php?option=com_eventgallery&view=checkout&task=change"
                    ) ?>"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?></a>
                
                <input name="continue" type="submit" class="btn btn-primary"
                       value="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CONTINUE') ?>"/>
            </div>
        </fieldset>
        <?php echo JHtml::_('form.token'); ?>
    </form>    
</div>



<?php echo $this->loadSnippet('footer_disclaimer'); ?>