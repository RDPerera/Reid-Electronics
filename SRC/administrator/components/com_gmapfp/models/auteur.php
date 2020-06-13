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

class GMapFPsModelAuteur extends JModelLegacy
{
	function __construct()
	{
		parent::__construct();

		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$controller = JRequest::getWord('controller');
	}

	function getCid()
	{
		$cid = JRequest::getVar('cid',  0, '', 'array');
		$where 		= ( count( $cid ) ? ' WHERE id='. implode( ' OR id=', $cid ) : '' );

		//die(print_r($where));
		$query = 'SELECT id, nom, userid'
			. ' FROM #__gmapfp'
			. $where
			. ' ORDER BY nom';
		return $this->_getList( $query );
	}
	
	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$data = JRequest::get( 'post' );
		$id = explode(',',@$data[cid]);
		$id = implode (' OR id =',$id);
        $db = JFactory::getDBO();
		$query = $db->getQuery(true);
			$fields = $db->quoteName('userid').' = '.@$data[users][0];
			$conditions = $db->quoteName('id').' = '.$id;
			$query->update($db->quoteName('#__gmapfp'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->query();

		return true;
	}

	function getListeUsers()
	{
		$query = 'SELECT id, name'
			. ' FROM #__users'
			. ' WHERE block = 0'
			. ' ORDER BY name';
		return $this->_getList( $query );
	}
}
