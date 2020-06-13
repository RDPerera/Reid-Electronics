<?php
/**
 *
 * @name        Geocode Factory
 * @package     geoFactory
 * @copyright   Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Cédric Pelloquin <info@myJoom.com>
 * @website     www.myJoom.com
 *
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class plgeditorsxtdplg_geofactory_btn_jc30InstallerScript{
    var $m_plgName = "plg_geofactory_btn_jc30" ;

    function update($parent) { 
        $this->install($parent);
    }

    function install($parent) { 
        $db = JFactory::getDbo();
        $tableExtensions = $db->quoteName("#__extensions");
        $columnElement   = $db->quoteName("element");
        $columnType      = $db->quoteName("type");
        $columnEnabled   = $db->quoteName("enabled");

        // Enable plugin
        $db->setQuery("UPDATE   $tableExtensions 
                        SET     $columnEnabled=1 
                        WHERE   $columnElement='{$this->m_plgName}' 
                        AND     $columnType='plugin'");
        $db->execute();

        echo "<br /><p>The plugin <strong>{$this->m_plgName}</strong> is automatically enabled. You can manage your <a href='index.php?option=com_plugins&view=plugins&filter_folder=geocodefactory'>Geocode Factory plugins here</a></p><br />";
    } 
}
?>