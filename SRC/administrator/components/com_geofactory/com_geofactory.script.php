<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */
defined('_JEXEC') or die;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class com_geoFactoryInstallerScript {
	/**
	* Constructor
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*/
	//public function __construct(JAdapterInstance $adapter);

	/**
	* Called before any type of action
	*
	* @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	//public function preflight($route, JAdapterInstance $adapter);

	/**
	* Called after any type of action
	*
	* @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function postflight($route, JAdapterInstance $adapter){
		$config = JComponentHelper::getParams('com_geofactory');
		$config->set('showTerms', 1);

		// Get a new database query instance
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query
		$query->update(	'#__extensions AS a');
		$query->set(	'a.params = ' . $db->quote((string)$config));
		$query->where(	'a.element = "com_geofactory"');

		// Execute the query
		$db->setQuery($query);
		$db->execute();
	}

	/**
	* Called on installation
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function install(JAdapterInstance $adapter){
		$db = JFactory::getDbo();
		$db->setQuery('CREATE TABLE IF NOT EXISTS '.$db->QuoteName('#__geofactory_contents'). ' (
						id INTEGER NOT NULL AUTO_INCREMENT,
						type 			varchar(255) 	NOT NULL default "",
						id_content		int(11) 		NOT NULL,
						address 		TEXT default 		NULL,
						latitude 		varchar(255) 	NOT NULL default "",
						longitude 		varchar(255) 	NOT NULL default "",
						PRIMARY KEY (id)
					) CHARSET=utf8;');
		$db->execute() ;
	}

	/**
	* Called on update
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function update(JAdapterInstance $adapter){
        // $parent is the class calling this method
        //$parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';

		$this->addDbField('language', 	'#__geofactory_markersets', 'VARCHAR(255)', "NOT NULL DEFAULT '*'");
		$this->addDbField('language', 	'#__geofactory_ggmaps', 	'VARCHAR(255)', "NOT NULL DEFAULT '*'");
		$this->addDbField('mslevel', 	'#__geofactory_markersets', 'int(11)', 		"NOT NULL DEFAULT '0'");

		// supprimer les ancienne versions des fichiers JS (automatique)
		com_geoFactoryInstallerScript::deleteOldJs('map_api');
		com_geoFactoryInstallerScript::deleteOldJs('markerclusterer');

        echo '<p>Update successfully !</p>';
	}

	protected static function deleteOldJs($baseName){
		// recherche la plus grande...
		$bigger = '' ;
		$pathJs = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_geofactory'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR;
		foreach (glob($pathJs."{$baseName}*.js") as $filename) {
			$cur = substr($filename, -10);//	map_api-5150215.js

			// vielle version
			if ($cur == "{$baseName}.js"){
		   		continue ;
			}

			// pas une version connue ?
			if (strlen($cur)!=10)
				continue;

			// on enleve l'extention
			$cur = (int)substr($cur, 0, 7);//	5150215.js
			if ($bigger == '')
		   		$bigger = $cur;

		   	if ($cur >= $bigger) {
		   		$bigger = $cur;
		   	}
		}

		foreach (glob($pathJs."{$baseName}*.js") as $filename) {
			if ($filename == $pathJs."{$baseName}-{$bigger}.js")
				continue ;

			JFile::delete($filename);
		}
	}

	protected function addDbField($field, $table, $type, $default){
	    $db = JFactory::getDBO();
		$db->setQuery("SHOW COLUMNS FROM {$table} LIKE '{$field}'");
		$oVal = $db->loadObjectList() ;			
		if (count($oVal)<1) {
			$db->setQuery("ALTER TABLE {$table} ADD `{$field}` {$type} {$default}");
			$db->execute();
		} 
	}

	/**
	* Called on uninstallation
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*/
	//public function uninstall(JAdapterInstance $adapter);
}
