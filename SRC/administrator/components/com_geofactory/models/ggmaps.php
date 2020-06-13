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

class GeofactoryModelGgmaps extends JModelList{
	public function __construct($config = array()){
		$config['filter_fields'] = array(
			'id', 'a.id',
			'name', 'a.name',
			'state', 'a.state',
			'checked_out', 'a.checked_out',
			'checked_out_time', 'a.checked_out_time',
			'nbrMs'
		);

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null){
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		$msId = $this->getUserStateFromRequest($this->context . '.filter.markerset_id', 'filter_markerset_id', '');
		$this->setState('filter.markerset_id', $msId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_geofactory');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 */
	protected function getStoreId($id = ''){
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery(){
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id AS id,'.
				'a.name AS name,'.
				'a.extrainfo AS extrainfo,'.
				'a.checked_out AS checked_out,'.
				'a.checked_out_time AS checked_out_time,' .
				'a.state AS state'
			)
		);

		$query->from($db->quoteName('#__geofactory_ggmaps').' AS a');

		$query->select('COUNT(lm.id_map) as nbrMs');
		$query->join('LEFT', '#__geofactory_link_map_ms AS lm ON a.id = lm.id_map');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Filter by marekerset.
		$msId = $this->getState('filter.markerset_id');
		if (is_numeric($msId)){
			$query->where('lm.id_ms='.(int) $msId );
		}

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		$query->group('a.id, a.name, a.checked_out, a.checked_out_time, a.state, editor');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('a.name LIKE '.$search);
			}
		}
	
		$query->order($db->escape('a.name').' '.$db->escape($this->getState('list.direction', 'ASC')));

//		echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}
