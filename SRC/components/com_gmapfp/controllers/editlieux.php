<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class GMapFPsControllerEditLieux extends GMapFPsController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'editlieux' );
		JRequest::setVar( 'layout', 'edit_form'  );

		parent::display();
	}

	function soumission()
	{
		JRequest::setVar( 'view', 'editlieux' );
		JRequest::setVar( 'layout', 'soumission'  );

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.create', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'error');
		} else {
			$input 	= JFactory::getApplication()->input;
			
			$post	= $input->post->getArray(array());
			
			$model = $this->getModel('editlieux');
			$returnid=$model->store($post);
			if ($returnid>0) {
				$msg = JText::_( 'GMAPFP_SAVED' );
			} else {
				$msg = JText::_( 'GMAPFP_SAVED_ERROR' );
			}
		}

		$link = JRoute::_('index.php?option=com_gmapfp&view=gestionlieux&controller=gestionlieux&task=view');
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link, $msg);
	}

	/**
	 * soumettre un enregistrement (and redirect to main page)
	 * @return void
	 */
	function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$input 	= JFactory::getApplication()->input;

		$post	= $input->post->getArray(array());
		$itemid =@ $post[itemid];

		$user = JFactory::getUser();
		if (!$user->authorise('core.create', 'com_gmapfp'))
		{
			$msg = JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED');
		} else {

			$model    = $this->getModel('editlieux');
			$returnid = $model->store($post);
			if ($returnid > 0) {
				$msg = JText::_( 'GMAPFP_SUBMIT' );
			} else {
				$msg = JText::_( 'GMAPFP_SUBMIT_ERROR' );
			}
		}

		$link = JRoute::_('index.php?Itemid='.$itemid,false);
		$this->setRedirect($link, $msg);
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
		if (!$user->authorise('core.create', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'), 'error');
		} else {
			$post	= JRequest::get('post');
			$model = $this->getModel('editlieux');
			$returnid=$model->store($post);
			if ($returnid>0) {
				$msg = JText::_( 'GMAPFP_SAVED' );
			} else {
				$msg = JText::_( 'GMAPFP_SAVED_ERROR' );
			}
		}

		$link = JRoute::_('index.php?option=com_gmapfp&view=editlieux&layout=edit_form&controller=editlieux&task=edit&cid='.(int)$returnid, false);
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( JRoute::_('index.php?option=com_gmapfp&view=gestionlieux&controller=gestionlieux&task=view'), $msg );
	}
}
?>
