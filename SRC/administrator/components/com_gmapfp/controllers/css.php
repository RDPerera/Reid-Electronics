<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsControllerCSS extends GMapFPsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function view()
	{
		JRequest::setVar( 'view', 'css' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 0);

		parent::display();
	}

	/**
	 * save CSS
	 */
	function saveCss()
	{	
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'), 'error');
		} else {
			$post	= JRequest::get('post');
			$model = $this->getModel('css');
			if ($model->store($post)) {
				$msg = JText::_( 'GMAPFP_SAVED');
			} else {
				$msg = JText::_( 'GMAPFP_SAVED_ERROR');
			}
		}

		$link = 'index.php?option=com_gmapfp&controller=css&task=view';
		$this->setRedirect($link, $msg);
	
	}

}
?>
