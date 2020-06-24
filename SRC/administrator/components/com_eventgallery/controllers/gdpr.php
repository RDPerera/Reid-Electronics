<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );

class EventgalleryControllerGdpr extends JControllerForm
{

    protected $default_view = 'gdpr';

    public function __construct($config = array())
    {

        parent::__construct($config);
    }

    public function getModel($name = 'Gdpr', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function export()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();

        $email = $app->input->getString('email');
        $filename = "user-data-export.json";

        /**
         * @var EventgalleryModelGdpr $model
         */
        $model = $this->getModel();

        $carts = $model->getCarts($email);
        $orders = $model->getOrders($email);
        $users = $model->getUsers($email);

        $data = [];

        $data[JText::_('COM_EVENTGALLERY_GDPR_CARTS')] = array_map(array($this,'renderCart'), $carts);
        $data[JText::_('COM_EVENTGALLERY_GDPR_ORDERS')] = array_map(array($this,'renderOrder'), $orders);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS')] = array_map(array($this,'renderUser'), $users);

        echo json_encode($data, JSON_PRETTY_PRINT);

        header('Content-type: text/plain');
        header('Content-disposition: attachment; filename="' . $filename . '"');

        $app->close();

    }

    /**
     * @param $cart EventgalleryLibraryCart
     * @return array
     */
    private function renderCart($cart) {
        $data = [];
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_ID')] = $cart->getId();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_FIRSTNAME')] = $cart->getFirstname();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_LASTNAME')] = $cart->getLastname();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_PHONE')] = $cart->getPhone();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_MESSAGE')] = $cart->getMessage();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_EMAIL')] = $cart->getEMail();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_BILLING')] = $this->renderAddress($cart->getBillingAddress());
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_SHIPPING')] = $this->renderAddress($cart->getShippingAddress());
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_SHIPPINGMETHOD')] = $this->renderShippingMethod($cart->getShippingMethodServiceLineItem());
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_LINEITEMCONTAINER_PAYMENTMETHOD')] = $this->renderPaymentMethod($cart->getPaymentMethodServiceLineItem());

        return $data;
    }

    private function renderOrder($order) {
        return $this->renderCart($order);
    }

    /**
     * @param $user \Joomla\CMS\User\User
     * @return array
     */
    private function renderUser($user) {

        $data = [];
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_ID')] = $user->id;
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EMAIL')] = $user->email;
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_SENDEMAIL')] = $user->sendEmail;
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_NAME')] = $user->name;
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_USERNAME')] = $user->username;

        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_FIRSTNAME')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_BASIC_FIRSTNAME_KEY);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_LASTNAME')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_BASIC_LASTNAME_KEY);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_EMAIL')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_BASIC_EMAIL_KEY);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_MESSAGE')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_BASIC_MESSAGE_KEY);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_PHONE')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_BASIC_PHONE_KEY);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_BILLING')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_BILLING_KEY);
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_USER_EG_SHIPPING')] = $user->getParam(EventgalleryLibraryAddress::USER_ADDRESS_SHIPPING_KEY);

        return $data;
    }

    private function renderShippingMethod($shippingMethod) {
        return $this->renderMethod($shippingMethod);
    }

    private function renderPaymentMethod($paymentMethod) {
        return $this->renderMethod($paymentMethod);
    }

    /**
     * @param $method EventgalleryLibraryMethodsMethod
     */
    private function renderMethod($method) {
        $data = [];

        if ($method == null) {
            return $data;
        }
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_METHOD_NAME')] = $method->getName();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_METHOD_DISPLAYNAME')] = $method->getDisplayName();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_METHOD_DATA')] = $method->getData();
        return $data;
    }


    /**
     * @param $address EventgalleryLibraryAddress
     */
    private function renderAddress($address) {
        $data = [];

        if (null == $address) {
            return $data;
        }

        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_FIRSTNAME')] = $address->getFirstName();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_LASTNAME')] = $address->getLastName();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_ADDRESS1')] = $address->getAddress1();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_ADDRESS2')] = $address->getAddress2();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_ADDRESS3')] = $address->getAddress3();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_CITY')] = $address->getCity();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_ZIP')] = $address->getZip();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_COUNTRY')] = $address->getCountry();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_STATE')] = $address->getState();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_COMPANY')] = $address->getCompanyName();
        $data[JText::_('COM_EVENTGALLERY_GDPR_USERS_ADDRESS_TAX_ID')] = $address->getTaxId();

        return $data;
    }


}
