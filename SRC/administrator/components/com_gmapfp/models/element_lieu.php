<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_26F
	* Creation date: Mars 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsModelElement_lieu extends JModelLegacy
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
			
			$search_lieu	= $mainframe->getUserStateFromRequest($option.'search_lieu', 'search_lieu', '',	'string' );
			$search_lieu	= JString::strtolower($search_lieu);
			$this->search_lieu = $search_lieu;

		}
		
		function setId($id)
		{
			$this->_id = intval( $id );
			$this->_data = null;
			$this->_total = null;
		}
	
	function getlistville()
	{
		$query = 'SELECT DISTINCT ville' .
				' FROM #__gmapfp' .
				' ORDER BY ville';
		return $this->_getList( $query );
	}
	
	function getlistdepartement()
	{
		$query = 'SELECT DISTINCT departement' .
				' FROM #__gmapfp' .
				' ORDER BY departement';
		return $this->_getList( $query );
	}
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$option = $option.'_lieux';
		$db		= $this->getDBO();
		
		$where[] = 'published = 1 ';

		if ($this->search_lieu) {
			$where[] = 'LOWER( nom ) LIKE '.$db->quote( '%'.$this->search_lieu.'%');
		}

		$filtreville = $mainframe->getUserStateFromRequest($option.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 'string' );
		$filtredepartement = $mainframe->getUserStateFromRequest($option.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 'string' );

		if ($filtreville<>'-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --') {
			$where[] = 'ville = '.$db->quote($filtreville);
		}			

		if ($filtredepartement<>'-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --') {
			$where[] = 'departement = '.$db->quote($filtredepartement);
		}
		
		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$query = ' SELECT * '
			. ' FROM #__gmapfp '.
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
