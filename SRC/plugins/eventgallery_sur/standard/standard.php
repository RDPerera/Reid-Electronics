<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class EventgalleryPluginsSurchargeStandard extends  EventgalleryLibraryMethodsSurcharge
{


    /**
     * Returns if this method can be used with the current cart.
     *
     * @param EventgalleryLibraryLineitemcontainer $cart
     *
     * @return bool
     */
    public function isEligible($cart)
    {
        // if there is no rule, this method is valued
        if (!isset($this->getData()->rules)) {
            return true;
        }

        $itemCountBased = isset($this->getData()->rules->type) && $this->getData()->rules->type=='itemcount';

        if ($itemCountBased) {
            // if the minimum amount is not defined skip this
            if (isset($this->getData()->rules->minAmount)) {
                // if the item count is not high enough
                if ($cart->getLineItemsTotalCount()<$this->getData()->rules->minAmount ) {
                    return false;
                }
            }

            // if the maximum amount is not defined skip this
            if (isset($this->getData()->rules->maxAmount) && $this->getData()->rules->maxAmount>0) {
                // if the item count is too high
                if ($cart->getLineItemsTotalCount()>$this->getData()->rules->maxAmount ) {
                    return false;
                }
            }
        }
        else {
            // if the minimum amount is not defined skip this
            if (isset($this->getData()->rules->minAmount)) {
                // if the subtotal is not high enough
                if ($cart->getSubTotal()->getAmount()<$this->getData()->rules->minAmount ) {
                    return false;
                }
            }

            // if the maximum amount is not defined skip this
            if (isset($this->getData()->rules->maxAmount) && $this->getData()->rules->maxAmount>0) {
                // if the subtotal is too high
                if ($cart->getSubTotal()->getAmount()>$this->getData()->rules->maxAmount ) {
                    return false;
                }
            }
        }

        return true;
    }

    static public  function getClassName() {
        return "Surcharge: Standard";
    }

    public function onPrepareAdminForm($form) {

        /**
         * add the language files
         */

        $language = JFactory::getLanguage();
        $language->load('plg_eventgallery_sur_standard' , __DIR__ , $language->getTag(), true);

        /**
         * disable the default data field
         */
        $form->setFieldAttribute('data', 'required', 'false');
        $form->setFieldAttribute('data', 'disabled', 'true');

        $fields = new SimpleXMLElement(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.'fields.xml'));
        $form->setField($fields);

        if (isset($this->getData()->rules->type))      {  $form->setValue("surcharge_standard_type", null, $this->getData()->rules->type); }
        if (isset($this->getData()->rules->minAmount)) {  $form->setValue("surcharge_standard_min",  null, $this->getData()->rules->minAmount); }
        if (isset($this->getData()->rules->maxAmount)) {  $form->setValue("surcharge_standard_max",  null, $this->getData()->rules->maxAmount); }

        return $form;
    }

    public function onSaveAdminForm($data) {

        $object = new stdClass();

        $object->rules = array (
            "type"=> $data['surcharge_standard_type'],
            "minAmount"=>(float)$data['surcharge_standard_min'],
            "maxAmount"=>(float)$data['surcharge_standard_max'],
        );

        $this->setData($object);

        return true;
    }
}