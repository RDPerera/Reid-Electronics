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

class EventgalleryLibraryFactoryCart extends EventgalleryLibraryFactoryFactory
{

    /**
     * Trys to find a cart for the given user.
     *
     * @param $userId
     * @return EventgalleryLibraryCart
     */
    public function getCartByUserId($userId) {
        $db = $this->db;
        $query = $db->getQuery(true);

        $query->select('c.*');
        $query->from('#__eventgallery_cart as c');
        $query->where('c.statusid is null');
        $query->where('c.userid = ' . $db->quote($userId));
        $db->setQuery($query);

        $object = $db->loadObject();
        if (null == $object) {
            return null;
        }
        return new EventgalleryLibraryCart($object);
    }

    /**
     * @param $id
     * @return EventgalleryLibraryCart|null
     */
    public function getCartById($id) {
        $db = $this->db;
        $query = $db->getQuery(true);

        $query->select('c.*');
        $query->from('#__eventgallery_cart as c');
        $query->where('c.id = ' . $db->quote($id));
        $db->setQuery($query);

        $object = $db->loadObject();
        if (null == $object) {
            return null;
        }
        return new EventgalleryLibraryCart($object);
    }

    /**
     * creates a cart for the given user.
     *
     * @param $userId
     * @return EventgalleryLibraryCart
     * @throws Exception
     */
    public function createCart($userId) {
        $db = $this->db;
        $uuid = str_replace('.','0',uniqid("", true));
        $uuid = base_convert($uuid,16,10);

        /**
         * @var EventgalleryTableCart $data
         */

        // this code is necessary because I want to have a special id. If I don't
        // add the column first, the Joomla code would just try to fire updates
        // so a cart is never created.
        $query = $db->getQuery(true);
        $query->insert("#__eventgallery_cart");
        $query->columns(array("id, userid, email, phone, subtotalcurrency, totalcurrency"));
        $query->values($db->quote($uuid).','. $db->quote($userId).",'','', 0, ''");
        $db->setQuery($query);
        $db->execute();

        $data = JTable::getInstance('cart', 'EventgalleryTable');
        $data->userid = $userId;
        $data->id=$uuid;

        return new EventgalleryLibraryCart($this->store((array)$data, 'Cart'));

    }

    /**
     * @param $email
     * @return array
     */
    public function getCartsByEmail($email) {
        $db = $this->db;
        $query = $db->getQuery(true);

        $query->select('id');
        $query->from('#__eventgallery_cart');
        $query->where('email = ' . $db->quote($email));
        $db->setQuery($query);

        $cartIds = $db->loadColumn(0);

        $carts = [];
        foreach($cartIds as $cartId) {
            array_push($carts, $this->getCartById($cartId));
        }

        return $carts;

    }


}