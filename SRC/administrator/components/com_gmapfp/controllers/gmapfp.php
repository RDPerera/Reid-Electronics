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

class GMapFPsControllerGMapFP extends GMapFPsController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'unpublish', 	'publish');
	}

	function edit()
	{
		JRequest::setVar( 'view', 'gmapfp' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function view()
	{
		JRequest::setVar( 'view', 'gmapfps' );
		JRequest::setVar( 'layout', 'default'  );

		parent::display();
	}

	function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$post	= JRequest::get('post');
		if (!($user->authorise('core.create', 'com_gmapfp') and empty($post['id'])) and !($user->authorise('core.edit', 'com_gmapfp') and !empty($post['id'])))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'), 'error');
		} else {
			$model = $this->getModel('gmapfp');
			$returnid=$model->store($post);
			if ($returnid>0) {
				$msg = JText::_( 'GMAPFP_SAVED' );
			} else {
				$msg = JText::_( 'GMAPFP_SAVED_ERROR' );
			}
		}

		$link = 'index.php?option=com_gmapfp&controller=gmapfp&task=view';
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link, $msg);
	}

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
			$model = $this->getModel('gmapfp');
			$returnid=$model->store($post);
			if ($returnid>0) {
				$msg = JText::_( 'GMAPFP_SAVED' );
			} else {
				$msg = JText::_( 'GMAPFP_SAVED_ERROR' );
			}
		}

		$link = 'index.php?option=com_gmapfp&controller=gmapfp&task=edit&cid[]='.(int)$returnid;
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.delete', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), 'error');
		} else {
			$model = $this->getModel('gmapfp');
			if(!$model->delete()) {
				$msg = JText::_( 'Error: One or more GMapFPs could not be Deleted' );
			} else {
				$msg = JText::_( 'GMapFP(s) Deleted' );
			}
		}

		$this->setRedirect( 'index.php?option=com_gmapfp&controller=gmapfp&task=view', $msg );
	}

	function publish()
	{
		$this->setRedirect( 'index.php?option=com_gmapfp&controller=gmapfp&task=view' );

		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.edit.state', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'error');
		} else {
			// Initialize variables
			$db			= JFactory::getDBO();
			$user		= JFactory::getUser();
			$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
			$task		= JRequest::getCmd( 'task' );
			$publish	= ($task == 'publish');
			$n			= count( $cid );

			if (empty( $cid )) {
				return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
			}

			JArrayHelper::toInteger( $cid );
			$cids = implode( ',', $cid );
			$query = $db->getQuery(true);
				$fields = $db->quoteName('published').' = '.(int) $publish;
				$conditions = $db->quoteName('id').' IN ( '.$cids.' )';
				$query->update($db->quoteName('#__gmapfp'))->set($fields)->where($conditions);
			$db->setQuery($query);
			if (!$db->query()) {
				return JError::raiseWarning( 500, $row->getError() );
			}
			$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
		}

	}

	function copy()
	{
		$this->setRedirect( 'index.php?option=com_gmapfp&controller=gmapfp&task=view' );

		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$post	= JRequest::get('post');
		if (!$user->authorise('core.create', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'), 'error');
		} else {
			$cid	= JRequest::getVar( 'cid', null, 'post', 'array' );
			$db		= JFactory::getDBO();
			$model  = $this->getModel('gmapfp');
			$table	= $model->getTable('GMapFP', 'GMapFPTable');
			$user	= &JFactory::getUser();
			$n		= count( $cid );

			if ($n > 0)
			{
				foreach ($cid as $id)
				{
					if ($table->load( (int)$id ))
					{
						$table->id			= 0;
						$table->nom			= '('.JText::_( 'GMAPFP_COPIE_DE').') ' . $table->nom;
						$table->alias		= '';
						$table->published	= 0;
						$table->userid 		= $user->get('id');
				
						if (!$table->store()) {
							return JError::raiseWarning( $table->getError() );
						}
					}
					else {
						return JError::raiseWarning( 500, $table->getError() );
					}
				}
			}
			else {
				return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
			}
			$table->reorder();
			$this->setMessage( JText::sprintf( 'Items copied', $n ) );
		}
	}
	
	function user()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		JRequest::setVar( 'view', 'auteur' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_gmapfp&controller=gmapfp&task=view', $msg );
	}

	function edit_upload()
	{
		JRequest::setVar( 'view', 'gmapfp' );
		JRequest::setVar( 'layout', 'upload_form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function saveOrderAjax()
	{
		$this->setRedirect( 'index.php?option=com_gmapfp&controller=gmapfp&task=view' );

		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$post	= JRequest::get('post');
		if (!$user->authorise('core.edit', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'), 'error');
		} else {
			$pks = $this->input->post->get('cid', array(), 'array');
			$order = $this->input->post->get('order', array(), 'array');

			// Sanitize the input
			JArrayHelper::toInteger($pks);
			JArrayHelper::toInteger($order);

			// Get the model
			$model  = $this->getModel('gmapfp');

			// Save the ordering
			$return = $model->saveorder($pks, $order);

			if ($return)
			{
				echo "1";
			}
		}

		// Close the application
		JFactory::getApplication()->close();
	}
			
}
?>
