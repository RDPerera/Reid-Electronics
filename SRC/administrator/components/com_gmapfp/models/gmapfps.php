<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/13
	* Creation date: Décembre 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class GMapFPsModelGMapFPs extends JModelLegacy
{
	/**
	 * GMapFPS data array
	 *
	 * @var array
	 */
	var $_data = null;
	var $_total = null;
	var $_pagination = null;

		function __construct()
		{
			parent::__construct();
			
			$mainframe = JFactory::getApplication(); 
			$option    = JRequest::getCMD('option'); 
			
			$limit = $mainframe->getUserStateFromRequest( $option.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );	

			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit', $limit);
			$this->setState('limitstart', $limitstart);
			
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
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT a.*, b.title, c.name AS auteur, c.email AS auteur_mail'
			. ' FROM #__gmapfp a LEFT JOIN #__users c ON c.id = a.userid, #__categories AS b  '.
			$where.
			$orderby
		;
		//die(print_r($query));
		return $query;
	}

	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 

		$db					= JFactory::getDBO();
		
        $filtreville 		= $mainframe->getUserStateFromRequest( $option.'filtreville', 		'filtreville', 			'-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 		'string' );
        $filtredepartement 	= $mainframe->getUserStateFromRequest( $option.'filtredepartement', 'filtredepartement', 	'-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 	'string' );
        $filtrecategorie 	= $mainframe->getUserStateFromRequest( $option.'filtrecategorie', 	'filtrecategorie', 		0, 	'int' );

		
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();
        $where[] = ' b.id = a.catid ';
        //$where[] = ' c.id = a.userid ';

		if ($search) {
			$where[] = 'LOWER(a.nom) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
		}

        if ($filtreville<>'-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --') {
            $where[] = 'ville = \''.addslashes($filtreville).'\'';
        }           

		if ($filtredepartement<>'-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --') {
			$where[] = 'departement = \''.addslashes($filtredepartement).'\'';
		}

        if ($filtrecategorie) {
            $where[] = 'a.catid = \''.$filtrecategorie.'\'';
        }

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY  a.ordering '.$filter_order_Dir;
		} else {
			if ($filter_order) {
				$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , catid, a.ordering ';
			}else{
				$orderby 	= ' ORDER BY catid, a.ordering ';
			};
		}

		return $orderby;
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
            $this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
	}

	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

        return $this->_total;
	}

	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}		
}
