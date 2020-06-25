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

class Categories extends Configuration
{
    public function doShowItemsPerCategoryCountRecursive() {
        return $this->get('show_items_per_category_count_recursive', 0) == 1;
    }

    public function doShowItemsPerCategoryRecursive() {
        return $this->get('show_items_per_category_recursive', 0) == 1;
    }

    public function getCategoriesLayout() {
        return $this->get('categories_layout', 'textlinks');
    }

    public function doShowItemsPerCategoryCount() {
        return $this->get('show_items_per_category_count', 0) == 1;
    }
}
