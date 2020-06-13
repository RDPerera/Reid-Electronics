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

class EventgalleryLibraryFactoryGooglephotosaccount extends EventgalleryLibraryFactoryFactory
{
    protected $_googlephotosaccounts;

    public function getGooglePhotosAccountById($id) {

        $sets = $this->getGooglePhotosAccounts();
        if (isset($sets[$id]))
        {
            return $sets[$id];
        }

        return null;
    }

    /**
     *
     *
     * @return EventgalleryLibraryGooglephotosaccount[]
     */
    public function getUsableGooglePhotosAccounts() {
        return array_filter($this->getGooglePhotosAccounts(), function(/** @var EventgalleryLibraryGooglephotosaccount $account */$account) {
            return $account->isUsable();
        });
    }

    /**
     * @return EventgalleryLibraryGooglephotosaccount[]
     */
    public function getGooglePhotosAccounts() {

        if ($this->_googlephotosaccounts == null) {

            $db = $this->db;
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__eventgallery_googlephotos_account');
            $query->order('ordering');
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $this->_googlephotosaccounts = array();


            foreach ($items as $item) {
                /**
                 * @var EventgalleryLibraryGooglephotosaccount $itemObject
                 */
                $itemObject = new EventgalleryLibraryGooglephotosaccount($item);
                $this->_googlephotosaccounts[$itemObject->getId()] = $itemObject;
            }
        }

        return $this->_googlephotosaccounts;
    }

}
