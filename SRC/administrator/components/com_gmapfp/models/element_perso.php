<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_13
	* Creation date: Decembre 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsModelElement_perso extends JModelLegacy
{
	/**
	 * GMapFPS data array
	 *
	 * @var array
	 */
	var $_data;

		function __construct()
		{
			parent::__construct();
			
			$mainframe = JFactory::getApplication(); 
			$option    = JRequest::getCMD('option'); 
			$option = $option.'_perso';
			
			$type = JRequest::getVar('type');
			if ($type != '0') {
				$user_id = JRequest::getVar('user_id');
				$this->setId($user_id);
			} else {
				$array = JRequest::getVar('cid',  0, '', 'array');
				$this->setId((int)$array[0]);
			}
			
			$limit = $mainframe->getUserStateFromRequest($option.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int' );	
			$this->setState('limit', $limit);
			$this->setState('limitstart', $limitstart);
			
		}
		
		function setId($id)
		{
			$this->_id = intval( $id );
			$this->_data = null;
			$this->_total = null;
		}
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$option = $option.'_perso';
		$db		= $this->getDBO();
		
		$where[] = 'published = 1 ';

		$search_perso	= $mainframe->getUserStateFromRequest($option.'search_perso', 'search_perso', '',	'string' );
		$search_perso	= JString::strtolower($search_perso);
		if ($search_perso) {
			$where[] = 'LOWER( nom ) LIKE '.$db->quote( '%'.$search_perso.'%');
		}

		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$query = ' SELECT * '
			. ' FROM #__gmapfp_personnalisation '.
			$where
		;

		return $query;
	}

	/**
	 * Retrieves the hello data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		// tri par ordre alphabétic
		if (!empty($this->_data))
			{usort($this->_data, array($this,'sortArray'));};
		
		$this->_total = count($this->_data );
		if ($this->_total < $this->getState('limit')) {
			$this->setState('limitstart', 0);
		}
		$this->_data = $this->limitArray($this->_data,$this->getState('limitstart'),$this->getState('limit'));

		return $this->_data;
	}

	function sortArray($a, $b) {
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$option = $option.'_perso';
		
		$filter_order = $mainframe->getUserStateFromRequest($option.'filter_order', 'filter_order', 'id', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
		if (empty ($filter_order)) {$filter_order='id';}
			
		if ($filter_order_Dir != 'asc') {
			$element1 = 'a';
			$element2 = 'b';
		} else {
			$element1 = 'b';
			$element2 = 'a';
		}
			
		return @strcasecmp(${$element1}->{$filter_order}, ${$element2}->{$filter_order});
	}

	function limitArray($array,$start,$limit) {
		$return = Array();
		for ($i=0;$i<count($array);$i++) {
			if ($i >= $start && $i < ($start+$limit)) {
				$return[] = $array[$i];
			}
		}
		return $return;
	}

	function getTotal()
	{
		return $this->_total;
	}

	function getPagination()
	{
		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		return $this->_pagination;
	}		

}
