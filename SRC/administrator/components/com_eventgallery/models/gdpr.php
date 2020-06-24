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

jimport( 'joomla.application.component.modellist' );

class EventgalleryModelGdpr  extends JModelList
{
    /**
     * @param $email
     * @return array
     */
    public function getCarts($email) {
        /**
         * @var EventgalleryLibraryFactoryCart $cartFactory
         */
        $cartFactory = EventgalleryLibraryFactoryCart::getInstance();
        return $cartFactory->getCartsByEmail($email);
    }

    /**
     * @param $email
     * @return array
     */
    public function getOrders($email) {
        /**
         * @var EventgalleryLibraryFactoryOrder $orderFactory
         */
        $orderFactory = EventgalleryLibraryFactoryOrder::getInstance();

        return $orderFactory->getOrdersByEmail($email);
    }

    /**
     * @param $email
     * @return array
     */
    public function getUsers($email) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id')->from('#__users')->where('email='.$db->quote($email));

        $db->setQuery($query);
        $dbUserIds = $db->loadColumn(0);

        $users = [];
        foreach($dbUserIds as $userId) {
            array_push($users, JFactory::getUser($userId));
        }

        return $users;
    }
}
