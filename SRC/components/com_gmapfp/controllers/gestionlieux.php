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

class GMapFPsControllerGestionLieux extends GMapFPsController
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
		$this->registerTask( 'unpublish', 	'publish');
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

	function view()
	{
		JRequest::setVar( 'view', 'gestionlieux' );
		JRequest::setVar( 'layout', 'default'  );

		parent::display();
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.delete', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), 'error');
		} else {
			$model = $this->getModel('gestionlieux');
			if(!$model->delete()) {
				$msg = JText::_( 'Error: One or more GMapFPs could not be Deleted' );
			} else {
				$msg = JText::_( 'GMapFP(s) Deleted' );
			}
		}

		$this->setRedirect( JRoute::_('index.php?option=com_gmapfp&view=gestionlieux&controller=gestionlieux&task=view'), $msg );
	}

	/**
	* Publishes or Unpublishes one or more records
	* @param array An array of unique category id numbers
	* @param integer 0 if unpublishing, 1 if publishing
	* @param string The current url option
	*/
	function publish()
	{
		$this->setRedirect( JRoute::_('index.php?option=com_gmapfp&view=gestionlieux&controller=gestionlieux&task=view') );;

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

			$query = 'UPDATE #__gmapfp'
			. ' SET published = ' . (int) $publish
			. ' WHERE id IN ( '. $cids .' )'
			;
			$db->setQuery( $query );
			if (!$db->query()) {
				return JError::raiseWarning( 500, $row->getError() );
			}
			$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
		}

	}

	/**
	 * Copie un lieux sélectionné
	 **/	
	function copy()
	{
		$this->setRedirect( JRoute::_('index.php?option=com_gmapfp&view=gestionlieux&controller=gestionlieux&task=view') );

		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.create', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'), 'error');
		} else {
			$cid	= JRequest::getVar( 'cid', null, 'post', 'array' );
			$db		= JFactory::getDBO();
			$model = $this->getModel('gestionlieux');
			$table	=$model->getTable('GMapFP', 'GMapFPTable');
			$user	= JFactory::getUser();
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
	
	public function saveOrderAjax()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_gmapfp'))
		{
			$this->setMessage(JText::_('JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED'), 'error');
		} else {
			$pks = $this->input->post->get('cid', array(), 'array');
			$order = $this->input->post->get('order', array(), 'array');

			// Sanitize the input
			JArrayHelper::toInteger($pks);
			JArrayHelper::toInteger($order);

			// Get the model
			$model  = $this->getModel('gestionlieux');

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
