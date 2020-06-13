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
	class plggeocodefactoryplg_geofactory_gw_jc30InstallerScript{
		var $m_plgName = 'plg_geofactory_gw_jc30';

		function update($parent) { 
			// lance la procédure afin d'etre sur qu'il active le plugin...
			$this->install($parent);

			// mise à jour du stupide 'joomla_content' en com_xxx -> com_content en l'occurence, ainsi j'ouvre la porte a d'autres application de cette table
		 	$db = JFactory::getDBO();
			$db->setQuery('	SELECT COUNT(*) 
							FROM '.$db->QuoteName('#__geofactory_contents'). ' 
							WHERE type=' . $db->quote('joomla_content')) ;
			$nbr = $db->loadResult();

			if ($nbr<1)
				return ;

			$db->setQuery('	UPDATE '.$db->QuoteName('#__geofactory_contents'). ' 
							SET type='. $db->quote('com_content').' 
							WHERE type=' . $db->quote('joomla_content')) ;
			$db->execute() ; 

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

			echo JText::sprintf('PLG_GEOFACTORY_PLUGIN_ENABLED', JText::_($this->m_plgName));
		} 

	    
	}
	?>