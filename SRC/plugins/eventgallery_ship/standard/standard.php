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

class EventgalleryPluginsShippingStandard extends  EventgalleryLibraryMethodsShipping
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
        $type = $cart->getType();
        return  $type == EventgalleryLibraryEnumBaskettype::TYPE_PHYSICAL || $type == EventgalleryLibraryEnumBaskettype::TYPE_MIXED;
    }

    static public  function getClassName() {
        return "Shipping: Standard Ground";
    }
}
