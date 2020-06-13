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
/**
 * provides a money object which handled amount and currency.
 *
 * Class EventgalleryLibraryCommonMoney
 */
class EventgalleryLibraryCommonMoney
{

    protected $_amount;
    protected $_currency;
    protected $_currencyCode;

    /**
     * @param float $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        $this->_amount=$amount;
        #$this->_currency=$currency;

        $config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();
        $this->_currency = $config->getCheckout()->getCurrencySymbol();
        $this->_currencyCode = $config->getCheckout()->getCurrencyCode();
    }


    /**
     * @return string
     */
    public function __toString() {        
        return JText::sprintf('COM_EVENTGALLERY_MONEY_FORMAT', $this->getCurrency(), $this->getAmount() );
    }


    /**
     * @return float
     */
    public function getAmount() {
        return $this->_amount;
    }

    /**
     * Returns the display name of the currency
     *
     * @return string
     */
    public function getCurrency() {
        return $this->_currency;
    }

    /**
     * Return the Currency Code like EUR or USD
     *
     * @return string
     */
    public function getCurrencyCode() {
        return $this->_currencyCode;
    }
}