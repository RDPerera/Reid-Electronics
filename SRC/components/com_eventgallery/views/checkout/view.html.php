<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


jimport('joomla.application.component.view');


class EventgalleryViewCheckout extends EventgalleryLibraryCommonView
{

    /**
     * @var JDocument
     */
    public $document;

    /**
     * @var JForm
     */
    protected $billingform;
    /**
     * @var EventgalleryLibraryCart
     */
    protected $cart;
    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;

    /**
     * @var JForm
     */
    protected $shippingform;

    /**
     * @var JForm
     */
    protected $loginform;

    protected $state;

    /**
     * @var JForm
     */
    protected $userdataform;

    /**
     * @var JForm
     */
    protected $userdataformwithname;


    function display($tpl = null)
    {
        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();
        $this->state = $this->get('State');
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance($app->getParams());

        /* @var EventgalleryLibraryManagerCart $cartMgr */
        $cartMgr = EventgalleryLibraryManagerCart::getInstance();
        $this->cart = $cartMgr->getCart();

        if ($this->cart->getShippingMethod() != null && !$this->cart->getShippingMethod()->needsAddressData()) {
            $skipAddressForms = true;
        } else {
            $skipAddressForms = false;
        }

        // set the default view
        if ($this->getLayout() == 'default') {
            $this->setLayout('review');
        }


        // if the current layout is not confirm and some details are missing, display the change page.
        // if there are no items in the cart, go the the cart page.
        if ($this->getLayout() != 'confirm') {

            if ($this->cart->getLineItemsCount()==0) {
                $app->redirect(
                    JRoute::_("index.php?option=com_eventgallery&view=cart", false)
                );
            }

            if ($this->cart->getShippingMethodServiceLineItem() == null
                || $this->cart->getPaymentMethodServiceLineItem() == null
                || ($this->cart->getShippingAddress() == null && !$skipAddressForms)
                || ($this->cart->getBillingAddress() == null && !$skipAddressForms)
            ) {

                $this->setLayout('change');

            }

        }

        if ($this->getLayout() == 'change') {

            $xmlPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery'
                . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR;

            $this->userdataform = JForm::getInstance('userdata', $xmlPath . 'userdata.xml');
            $this->userdataform->reset();
            $this->userdataform->bind(
                array(
                    'message' => $this->cart->getMessage(),
                    'email' => $this->cart->getEMail(),
                    'phone' => $this->cart->getPhone()
                )
            );
            $this->userdataform->bind($app->input->post->getArray());

            $this->userdataformwithname = JForm::getInstance('userdata_withname', $xmlPath . 'userdata_withname.xml');
            $this->userdataformwithname->reset();
            $this->userdataformwithname->bind(
                array(
                    'firstname' => $this->cart->getFirstname(),
                    'lastname' => $this->cart->getLastname(),
                    'message' => $this->cart->getMessage(),
                    'email' => $this->cart->getEMail(),
                    'phone' => $this->cart->getPhone()
                )
            );
            $this->userdataformwithname->bind($app->input->post->getArray());

            $this->billingform = JForm::getInstance('billing', $xmlPath . 'billingaddress.xml');

            if (!$this->config->getCheckout()->doEnableBusinessCustomerData()) {
                $this->billingform->removeField('billing_companyname');
                $this->billingform->removeField('billing_taxid');
            }

            if (!$this->config->getCheckout()->doUseAddressFieldState()) {
                $this->billingform->removeField('billing_state');
            }

            if (!$this->config->getCheckout()->doUseAddressFieldCountry()) {
                $this->billingform->removeField('billing_country');
            }

            $this->billingform->reset();

            if ($this->cart->getBillingAddress() != null) {
                $this->billingform->bind($this->cart->getBillingAddress()->_getData('billing_'));
            }
            $this->billingform->bind($app->input->post->getArray());


            $this->shippingform = JForm::getInstance('shipping', $xmlPath . 'shippingaddress.xml');

            if (!$this->config->getCheckout()->doEnableBusinessCustomerData()) {
                $this->shippingform->removeField('shipping_companyname');
            }

            if (!$this->config->getCheckout()->doUseAddressFieldState()) {
                $this->shippingform->removeField('shipping_state');
            }

            if (!$this->config->getCheckout()->doUseAddressFieldCountry()) {
                $this->shippingform->removeField('shipping_country');
            }

            if ($this->cart->getShippingAddress() != null) {
                $this->shippingform->bind($this->cart->getShippingAddress()->_getData('shipping_'));
            }
            $this->shippingform->bind($app->input->post->getArray());

            // Get the form.
            // Joomla 4 FIX TODO: Delete if not supporting Joomla 3 any longer
            JForm::addFormPath(JPATH_BASE . '/components/com_users/models/forms');
            JForm::addFieldPath(JPATH_BASE . '/components/com_users/models/fields');

            JForm::addFormPath(JPATH_BASE . '/components/com_users/forms');

            $this->loginform = JForm::getInstance('com_users.login', 'login');


        }

        $pathway = $app->getPathWay();
        $pathway->addItem(JText::_('COM_EVENTGALLERY_CART_CHECKOUT_PATH'));

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app = JFactory::getApplication();
        $title = null;

        $title = $this->config->getMenuItem()->getPageTitle();

        $title .= " - " . JText::_('COM_EVENTGALLERY_CART_CHECKOUT_PATH');


        // Check for empty title and add site name if param is set
        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }


        if ($this->document) {

            $this->document->setTitle($title);

        }
    }

}
