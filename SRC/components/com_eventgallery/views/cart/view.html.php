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


class EventgalleryViewCart extends EventgalleryLibraryCommonView
{
    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;
    protected $state;

    /**
     * @var EventgalleryLibraryCart
     */
    protected $cart;

    /**
     * @var JDocument
     */
    public $document;

    function display($tpl = NULL)
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


        if ($this->cart->getLineItemsCount() == 0) {
            $this->setLayout("empty");
        }

        $pathway = $app->getPathWay();
        $pathway->addItem(JText::_('COM_EVENTGALLERY_CART_PATH'));

        // show a disabled message once the cart is not active
        if (!$this->config->getCart()->doUseCart()) {
            $this->setLayout('disabled');
        }

        $this->_prepareDocument();

        parent::display($tpl);
    }


    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app = JFactory::getApplication();
        $title = NULL;

        $title = $this->config->getMenuItem()->getPageTitle();

        $title .= " - " . JText::_('COM_EVENTGALLERY_CART_PATH');


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
