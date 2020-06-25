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

class EventgalleryLibraryFactoryImagetypegroup extends EventgalleryLibraryFactoryFactory
{
    protected $_imagetypegroups;
    protected $_imagetypegroups_published;

    /**
     * Determines a Image Type by ID
     *
     * @param $id
     * @return EventgalleryLibraryImagetypegroup
     */
    public function getImagetypegroupById($id) {

        $imagetypegroups = $this->getImageTypeGroups(false);

        if (!isset($imagetypegroups[$id])) {
            return null;
        }

        return $imagetypegroups[$id];
    }


    /**
     * Return all imagetypegroups
     *
     * @param $publishedOnly
     * @return array
     */
    public function getImageTypeGroups($publishedOnly) {
        if ($this->_imagetypegroups == null) {

            $db = $this->db;
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__eventgallery_imagetypegroup');
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $this->_imagetypegroups = array();
            $this->_imagetypegroups_published = array();

            foreach ($items as $item) {
                /**
                 * @var EventgalleryTableImagetypegroup $item
                 */

                if ($item->published==1) {
                    $this->_imagetypegroups_published[$item->id] = new EventgalleryLibraryImagetypegroup($item);
                }
                $this->_imagetypegroups[$item->id] = new EventgalleryLibraryImagetypegroup($item);
            }
        }
        if ($publishedOnly) {
            return $this->_imagetypegroups_published;
        } else {
            return $this->_imagetypegroups;
        }
    }

    public static function clear() {

        /**
         * @var EventgalleryLibraryFactoryImagetypegroup $imageTypeGroupFactory
         */
        $imageTypeGroupFactory = self::getInstance();
        $imageTypeGroupFactory->_imagetypegroups = null;
        $imageTypeGroupFactory->_imagetypegroups_published = null;


        parent::clear();
    }
}


