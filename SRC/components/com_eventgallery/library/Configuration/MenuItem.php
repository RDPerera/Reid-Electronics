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

class MenuItem extends Configuration
{
    public function getPageHeading() {
        return $this->get('page_heading', null);
    }

    public function getPageTitle() {
        return $this->get('page_title', null);
    }

    public function getMetaDescription() {
        return $this->get('menu-meta_description', null);
    }

    public function getMetaKeywords() {
        return $this->get('menu-meta_keywords', null);
    }

    public function getRobots() {
        return $this->get('robots', null);
    }

    public function doShowPageHeading() {
        return $this->get('show_page_heading', 1) == 1;
    }

    public function getMenuItemId() {
        return $this->get('menuitemid', null);
    }
}
