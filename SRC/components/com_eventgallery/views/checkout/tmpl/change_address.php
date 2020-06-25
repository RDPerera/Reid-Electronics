<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

?>

<div class="eventgallery-checkout-address eventgallery-checkout-form-without-address">

    <fieldset class="userdata-fieldset">
        <?php foreach ($this->userdataformwithname->getFieldset() as $field): ?>
            <div class="control-group">
                <?php if (!$field->hidden): ?>
                    <?php echo $field->label; ?>
                <?php endif; ?>
                <div class="controls">
                    <?php echo $field->input; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </fieldset>

</div>


<div class="eventgallery-checkout-address eventgallery-checkout-form-with-address">

    <fieldset class="userdata-fieldset">
        <?php foreach ($this->userdataform->getFieldset() as $field): ?>
            <div class="control-group">
                <?php if (!$field->hidden): ?>
                    <?php echo $field->label; ?>
                <?php endif; ?>
                <div class="controls">
                    <?php echo $field->input; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </fieldset>
    <hr>


    <div id="address-input-area">
        <fieldset class="billing-address-fieldset">
            <?php foreach ($this->billingform->getFieldset() as $field): ?>
                <div class="control-group">
                    <?php if (!$field->hidden): ?>
                        <?php echo $field->label; ?>
                    <?php endif; ?>
                    <div class="controls">
                        <?php echo $field->input; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <hr>

        <fieldset class="ship-to_different-address-fieldset">
            <div class="control-group">
                <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPTODIFFERENTADDRESS') ?>
                <?php
                $checkF = '';
                $checkT = '';
                if ($this->cart->getShippingAddress() == NULL
                    || $this->cart->getBillingAddress() == NULL
                    || $this->cart->getShippingAddress()->getId() == $this->cart->getBillingAddress()->getId()
                ) {
                    $checkF = ' checked="checked" ';
                } else {
                    $checkT = ' checked="checked" ';
                }
                ?>
                <div class="controls">
                    <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPTODIFFERENTADDRESS_FALSE') ?>
                    <input title="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPTODIFFERENTADDRESS_FALSE')?>" autocomplete="off" type="radio" id="shiptodifferentaddress-false" name="shiptodifferentaddress"
                           value="false" <?php echo $checkF; ?>>
                    <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPTODIFFERENTADDRESS_TRUE') ?>
                    <input title="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPTODIFFERENTADDRESS_TRUE')?>" autocomplete="off" type="radio" id="shiptodifferentaddress-true" class="shiptodifferentaddress"
                           name="shiptodifferentaddress" value="true" <?php echo $checkT; ?>>
                </div>
            </div>
        </fieldset>
        <hr>

        <fieldset class="shipping-address-fieldset">
            <?php foreach ($this->shippingform->getFieldset() as $field): ?>
                <div class="control-group">
                    <?php if (!$field->hidden): ?>
                        <?php echo $field->label; ?>
                    <?php endif; ?>
                    <div class="controls">
                        <?php echo $field->input; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </fieldset>
    </div>

</div>




<script type="text/javascript">
(function(jQuery){

    jQuery( document ).ready(function() {
        /**
        * fixes HTML5Fallback issue where the disabled property was not set in the right way
        */
        function refreshShippingAddressFields() {
            jQuery('.eventgallery-checkout-address input').each(function()  {
                this.isRequired = !!(jQuery(this).attr("required"));
                this.isDisabled = !!(jQuery(this).attr("disabled"));
            });
        }

        /**
         * BEGIN  Handles the different shipping / billing address switch
         */
        function disableRequiredForShipping() {
            jQuery('.shipping-address').attr('disabled', 'disabled');       
            jQuery('.shipping-address-fieldset .is-required').removeClass('required');
            jQuery('.shipping-address-fieldset').hide();

            refreshShippingAddressFields();            
        }

        function enableReqiredForShipping() {
            jQuery('.shipping-address').removeAttr('disabled');
            jQuery('.shipping-address-fieldset .is-required').addClass('required');
            jQuery('.shipping-address-fieldset').show();
            refreshShippingAddressFields();

        }


        function handleShippingAddressRequiredField() {
            if (jQuery('#shiptodifferentaddress-false').is(':checked')) {
                disableRequiredForShipping();
            } else {
                enableReqiredForShipping();
            }
        }

        jQuery('#shiptodifferentaddress-false').click(disableRequiredForShipping);
        jQuery('#shiptodifferentaddress-true').click(enableReqiredForShipping);

        /**
         *  END different shippinng address handling
         *
         */

        function hideAddressForms(containerSelector) {
            jQuery(containerSelector + ' .eg-is-required').removeClass('required');
            jQuery(containerSelector + ' input').attr('disabled', 'disabled');
            jQuery(containerSelector + ' textarea').attr('disabled', 'disabled');
            handleShippingAddressRequiredField();
            jQuery(containerSelector).hide();
        }

        function showAddressForms(containerSelector) {
            jQuery(containerSelector + ' .eg-is-required').addClass('required');
            jQuery(containerSelector + ' input').removeAttr('disabled');
            jQuery(containerSelector + ' textarea').removeAttr('disabled');
            handleShippingAddressRequiredField();
            jQuery(containerSelector).show();
        }

        function handleAddressFormVisibility(element) {

            if (element.dataset.needsAddressData === 'false') {
                hideAddressForms('.eventgallery-checkout-form-with-address');
                showAddressForms('.eventgallery-checkout-form-without-address');
            } else {
                hideAddressForms('.eventgallery-checkout-form-without-address');
                showAddressForms('.eventgallery-checkout-form-with-address');
            }
            refreshShippingAddressFields();
        }


        jQuery('input[name=shippingid]').click(function() {
            handleAddressFormVisibility(this);
        });


        /*
        * Init the form state
         */

        // save the required state for restoring it if necessary
        jQuery('.required').addClass('eg-is-required');

        handleShippingAddressRequiredField();

        var checkedShippingElements = jQuery('input[name=shippingid][checked=checked]');
        if (checkedShippingElements.length>0) {
            handleAddressFormVisibility(checkedShippingElements[0]);
        } else {
            var shippingElements = jQuery('input[name=shippingid]');
            if (shippingElements.length>0) {
                shippingElements[0].click();
            }
        }

    });

})(eventgallery.jQuery);
</script>