<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.13F
	* Creation date: Decembre 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsViewAuteur extends JViewLegacy
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$controller = JRequest::getWord('controller');

		$task = JRequest::getWord('task');

			$text = JText::_( 'Edit' );
			JToolBarHelper::title(   JText::_( 'GMAPFP_USER_MANAGER' ).': <small>[ ' . $text.' ]</small>' );
			JToolBarHelper::save();

				JToolBarHelper::cancel();

		JHTML::_('behavior.tooltip');

		$cid	= JRequest::getVar( 'cid', null, 'post', 'array' );
		$rows = $this->get('cid');

		$listusers = array();
		$listusers = $this->get('ListeUsers');
		if (!empty($listusers))
		{
			foreach($listusers as $temp) {
				$list_users[] = JHTML::_('select.option', $temp->id, $temp->name);
			}			
			$lists['users']	= JHTML::_('select.genericlist',   $list_users, 'users[]', 'class="inputbox" size="15" ', 'value', 'text', $rows[0]->userid, '' );
		}
		$this->assignRef('lists', $lists);
		$this->assignRef('rows', $rows);
		
		parent::display($tpl);

	}
}
