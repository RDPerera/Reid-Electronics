<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_29F
	* Creation date: Juillet 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsModelGMapFP extends JModelLegacy
{
	var $_list;

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function _buildQuery()
	{
		$query = ' SELECT * '
			. ' FROM #__gmapfp_marqueurs where published = 1 '
		;

		return $query;
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__gmapfp '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->nom = null;
			$this->_data->alias = null;
			$this->_data->adresse = null;
			$this->_data->adresse2 = null;
			$this->_data->codepostal = null;
			$this->_data->ville = null;
			$this->_data->departement = null;
			$this->_data->pay = null;
			$this->_data->tel = null;
			$this->_data->tel2 = null;
			$this->_data->fax = null;
			$this->_data->email = null;
			$this->_data->web = null;
			$this->_data->img = null;
			$this->_data->album = null;
			$this->_data->intro = null;
			$this->_data->message = null;
			$this->_data->horaires_prix = null;
			$this->_data->affichage = null;
			$this->_data->marqueur = null;
			$this->_data->link = null;
			$this->_data->article_id = null;
			$this->_data->icon = null;
			$this->_data->icon_label = null;
			$this->_data->glat = null;
			$this->_data->glng = null;
			$this->_data->gzoom = null;
			$this->_data->catid = null;
			$this->_data->userid = null;
			$this->_data->published = null;
			$this->_data->checked_out = null;
			$this->_data->metadesc = null;
			$this->_data->metakey = null;
			$this->_data->ordering = 0;			
		}
		
		if (JString::strlen($this->_data->message) > 1) {
			$this->_data->text = $this->_data->intro . "<hr id=\"system-readmore\" />" . $this->_data->message;
		} else {
			$this->_data->text = $this->_data->intro;
		}
		return $this->_data;
	}

	function &getMarqueurs()
	{
		if (empty( $this->_list ))
		{
			$query = $this->_buildQuery();
			$this->_list = $this->_getList( $query );
		}

		return $this->_list;
	}

	public function getTable($type = 'gmapfp', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	function store()
	{
		$row = $this->getTable('GMapFP', 'GMapFPTable');

		$data = JRequest::get( 'post' );

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		GMapFPsHelper::saveGMapfpPrep( $row );

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		if (!$row->reorder()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return $row->id;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable('GMapFP', 'GMapFPTable');

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
	
	function move($direction)
	{
		$row =& $this->getTable('GMapFP', 'GMapFPTable');
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
		$row =& $this->getTable('GMapFP', 'GMapFPTable');

		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			//$groupings[] = $row->catid;

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
}
?>
