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

class EventgalleryPluginsPaymentStandard extends  EventgalleryLibraryMethodsPayment
{

    static public  function getClassName() {
        return "Payment: Standard";
    }

    public function onPrepareAdminForm($form) {

        /**
         * add the language files
         */

        $language = JFactory::getLanguage();
        $language->load('plg_eventgallery_pay_standard' , __DIR__ , $language->getTag(), true);

        /**
         * disable the default data field
         */
        $form->setFieldAttribute('data', 'required', 'false');
        $form->setFieldAttribute('data', 'disabled', 'true');

        $fields = new SimpleXMLElement(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.'fields.xml'));
        $form->setField($fields);

        if (isset($this->getData()->review_message)) {  $form->setValue("payment_standard_review_message", null, $this->getData()->review_message); }
        if (isset($this->getData()->confirmation_message)) {  $form->setValue("payment_standard_confirmation_message", null, $this->getData()->confirmation_message); }

        return $form;
    }

    public function onSaveAdminForm($data) {

        $object = new stdClass();

        $object->review_message = $data['payment_standard_review_message'];
        $object->confirmation_message = $data['payment_standard_confirmation_message'];

        $this->setData($object);

        return true;
    }

    public function getMethodReviewContent($lineitemcontainer, $isContentForMail) {
        $data = $this->getData();
        if (null == $data) {
            return "";
        }
        $string = new EventgalleryLibraryDatabaseLocalizablestring($data->review_message);
        return $string->get();

    }


    public function getMethodConfirmContent($lineitemcontainer, $isContentForMail) {
        $data = $this->getData();
        if (null == $data) {
            return "";
        }
        $string = new EventgalleryLibraryDatabaseLocalizablestring($data->confirmation_message);
        return $string->get();
    }

}