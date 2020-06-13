<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsModelGestionLieux extends JModelLegacy
{
	var $_data = null;

	var $_total = null;

	var $_pagination = null;

		function __construct()
		{
			parent::__construct();
			
			$app = JFactory::getApplication(); 
			$input = $app->input;
			$option = $input->get('option'); 
			
			$limit = $app->getUserStateFromRequest( $option.'limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );	

			// In case limit has been changed, adjust limitstart accordingly
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit', $limit);
			$this->setState('limitstart', $limitstart);

			//récupération des id cochés
			$array = $input->get('cid',  0, '', 'array');
			$this->setId((int)$array[0]);
			
		}
		
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
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
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT a.*, b.title '
			. ' FROM #__gmapfp AS a, #__categories AS b '.
			$where.
			$orderby
		;
		//die(print_r($query));
		return $query;
	}

	function _buildContentWhere()
	{
		$app = JFactory::getApplication(); 
		$input = $app->input;
		$option    = $input->get('option'); 
		$db	= JFactory::getDBO();
		
        $filtreville 		= $app->getUserStateFromRequest( $option.'filtreville', 		'filtreville', 			'-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 		'string' );
        $filtredepartement 	= $app->getUserStateFromRequest( $option.'filtredepartement', 'filtredepartement', 	'-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 	'string' );
        $filtrecategorie 	= $app->getUserStateFromRequest( $option.'filtrecategorie', 	'filtrecategorie', 		'', 	'string' );

		$filter_order		= $app->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $app->getUserStateFromRequest( $option.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		$where = array();
        $where[] = ' b.id = a.catid ';

		//filtre par rapport au user
		$user		= JFactory::getUser();
        $where[] = ' a.userid = '.$user->get('id').' ';
		

		if ($search) {
			$where[] = 'LOWER(a.nom) LIKE '.$db->Quote( '%'.$search.'%' );
		}

        if ($filtreville<>'-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --') {
            $where[] = 'ville = '.$db->Quote($filtreville);
        }           

		if ($filtredepartement<>'-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --') {
			$where[] = 'departement = '.$db->Quote($filtredepartement);
		}

        if (!empty($filtrecategorie)) {
            $where[] = 'a.catid = '.$db->Quote($filtrecategorie);
        }

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildContentOrderBy()
	{
		$app = JFactory::getApplication(); 
		$input = $app->input;
		$option    = $input->get('option'); 

		$filter_order		= $app->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

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
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}		
	
	function move($direction)
	{
		$row = $this->getTable('GMapFP', 'GMapFPTable');
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' 1 AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function saveorder($cid = array(), $order)
	{
		$row = $this->getTable('GMapFP', 'GMapFPTable');

		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		$row->reorder();

		return true;
	}

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

	function delete()
	{
		$app = JFactory::getApplication(); 
		$input = $app->input;
		$cids = $input->get( 'cid', array(0), 'post', 'array' );
		// die(print_r($cids));

		$row = $this->getTable('GMapFP', 'GMapFPTable');

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}						
		}
		return true;
	}
	
}
