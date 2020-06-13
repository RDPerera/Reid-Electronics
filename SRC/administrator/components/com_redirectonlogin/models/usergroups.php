<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class redirectonloginModelUsergroups extends JModelList{	

	protected $option = 'com_redirectonlogin';	
	
	public function __construct($config = array()){
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'title', 'a.title',
				'ordering', 'a.lft',
				'frontend_type', 't.frontend_type',
				'opening_site', 't.opening_site',
				'frontend_type_logout', 't.frontend_type_logout',
				'backend_type', 't.backend_type',
				'redirect_order_front', 'o.redirect_order_front',
				'redirect_order_back', 'o.redirect_order_back',
				'logoutbackend_type', 't.logoutbackend_type',
				'id', 'a.id'							
			);
		}
		parent::__construct($config);
	}	

	protected function populateState($ordering = NULL, $direction = NULL){
	
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_redirectonlogin');
		$this->setState('params', $params);

		// List state information.		
		parent::populateState('a.lft', 'asc');	
		
	}
	
	protected function getStoreId($id = ''){
	
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}
	
	protected function getListQuery(){
	
		// Create a new query object.
		$db	= $this->getDbo();
		$query = $db->getQuery(true);
		
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*, '.
				'o.redirect_order_front AS redirect_order_front, '.
				'o.redirect_order_back AS redirect_order_back, '.
				'o.id AS order_id, '.
				't.frontend_type AS frontend_type, '.
				't.frontend_type_logout AS frontend_type_logout, '.
				't.backend_type AS backend_type, '.
				't.logoutbackend_type AS logoutbackend_type, '.
				't.open_type AS open_type, '.
				't.opening_site AS opening_site '
			)
		);
		$query->from('`#__usergroups` AS a');
		
		// Join with group order
		$query->join('LEFT', '#__redirectonlogin_order_groups AS o ON a.id = o.group_id');
		
		// Join with group type
		$query->join('LEFT', '#__redirectonlogin_groups AS t ON a.id = t.group_id');
		
		// Add the level in the tree.
		$query->select('COUNT(DISTINCT c2.id) AS level');
		$query->join('LEFT OUTER', '`#__usergroups` AS c2 ON a.lft > c2.lft AND a.rgt < c2.rgt');
		$query->group('a.id');
		
		// Filter the comments over the search string if set.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('a.title LIKE '.$search);
			}
		}
		
		//not the public group
		$query->where('a.id<>1');		
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');		
		$query->order($db->escape($orderCol.' '.$orderDirn));	
		
		//echo nl2br(str_replace('#__','jos_',$query));
		
		return $query;
		
	}
}
?>