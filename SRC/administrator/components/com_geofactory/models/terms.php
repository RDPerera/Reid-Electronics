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

class GeofactoryModelTerms extends JModelLegacy{

	public function setTerms($iEnable){
		$config = JComponentHelper::getParams('com_geofactory');
		$config->set('showTerms',$iEnable);

$componentid = JComponentHelper::getComponent('com_geofactory')->id;
$table = JTable::getInstance('extension');
$table->load($componentid);
$table->bind(array('params' => $config->toString()));

// check for error
if (!$table->check()) {
    $this->setError('lastcreatedate: check: ' . $table->getError());
    return false;
}

// Save to database
if (!$table->store()) {
    $this->setError('lastcreatedate: store: ' . $table->getError());
    return false;
}

/*
		// Get a new database query instance
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query
		$query->update(	'#__extensions AS a');
		$query->set(	'a.params = ' . $db->quote((string)$config));
		$query->where(	'a.element = "com_geofactory"');

		// Execute the query
		$db->setQuery($query);
		$db->execute();*/

		// Clean the component cache.
		$this->cleanCache('com_geofactory');
	}
}