<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.francejoomla.net
	* Author email: fayauxlogescpa@gmail.com
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsControllerPersonnalisation extends GMapFPsController
{

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'personnalisation' );
		JRequest::setVar( 'layout', 'form'  );

		parent::display();
	}

	/**
	 * save a record (and not redirect to main page)
	 * @return void
	 */
	function apply()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$post	= JRequest::get('post');
		if (!($user->authorise('core.create', 'com_gmapfp') and empty($post['id'])) and !($user->authorise('core.edit', 'com_gmapfp') and !empty($post['id'])))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'), 'error');
		} else {
			$model = $this->getModel('personnalisation');
			$returnid=$model->store($post);
			if ($returnid>0) {
				$msg = JText::_( 'GMAPFP_SAVED' );
			} else {
				$msg = JText::_( 'GMAPFP_SAVED_ERROR' );
			}
		}

		$link = 'index.php?option=com_gmapfp&controller=personnalisation&task=edit';
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link, $msg);
	}

	/**
	* cancel editing a record
	* @return void
	*/
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_gmapfp&controller=personnalisation&task=edit', $msg );
	}

}
?>
