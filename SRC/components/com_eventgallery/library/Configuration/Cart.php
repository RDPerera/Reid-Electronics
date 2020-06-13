<?php

namespace Joomla\Component\Eventgallery\Site\Library\Configuration;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class Cart extends Configuration
{
    public function doUseCart() {
        return $this->get('use_cart', 1) == 1;
    }

    public function doUseCartInsideComponent() {
        return $this->get('use_cart_inside_component', 1) == 1;
    }

    public function doUseStickyImagetypeSelection() {
        return $this->get('use_sticy_imagetype_selection', 0) == 1;
    }

    public function doShowCartConnector() {
        return $this->get('show_cart_connector', 0) == 1;
    }

    public function getCartConnectorLink() {
        return $this->get('cart_connector_link', '');
    }

    public function getCartConnectorLinkRel() {
        return $this->get('cart_connector_link_rel', 'nofollow');
    }

}
