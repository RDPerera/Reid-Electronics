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

class GeofactoryTableAssign extends JTable{
	public function __construct(&$_db){
		$this->checked_out_time = $_db->getNullDate();
		parent::__construct('#__geofactory_assignation', 'id', $_db);
	}

/*	public function bind($array, $ignore = ''){
		return parent::bind($array, $ignore);
	}*/

	public function check(){
		$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);
		return true ;
	}
	
	public function _existDefault($code, $name){
		$name = "Default - " . $name ;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('COUNT(id)');
		$query->from($db->quoteName($this->getTableName()));
		$query->where($db->quoteName('typeList').'='.$db->quote(strtoupper($code)).' AND '.$db->quoteName('name').'=' .$db->quote($name));
		$db->setQuery($query);

		try{
			$count = $db->loadResult();
		}
		catch (RuntimeException $e){
			$this->setError($e->getMessage());
			return 0;
		}

		return $count ;
	}

	public function _createDefault($code, $name){

/*

if code == CB
   
id
name
typeList
extrainfo
field_latitude
field_longitude



*/


		$nameFormated = "Default - " . $name ;
		$new = JTable::getInstance('Assign', 'GeofactoryTable');
		$new->name = $nameFormated ;
		$new->typeList = $code ;
		$new->extrainfo = JText::sprintf('COM_GEOFACTORY_DEFAULT_ASSIGN_FOR', $name); 
		$new->state = 1 ;
		$new->check();
		if (!$new->store()){
			$new->setError($new->getError());
		}				
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed	An optional array of primary key values to update.  If not
	 *					set the instance property value is used.
	 * @param   integer The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer The user id of the user performing the operation.
	 * @return  boolean  True on success.
	 * @since   1.0.4
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE '.$this->_db->quoteName($this->_tbl).
			' SET '.$this->_db->quoteName('state').' = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);

		try
		{
			$this->_db->execute();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
}
