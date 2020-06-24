<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controlleradmin' );

class EventgalleryControllerImagetypegroups extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     * @param string $name
     * @param string $prefix
     * @param array $config
     * @return object
     */
    public function getModel($name = 'Imagetypegroup', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}
