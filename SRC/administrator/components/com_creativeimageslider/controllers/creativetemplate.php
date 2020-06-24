<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: default.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

jimport('joomla.application.component.controllerform');

class CreativeimagesliderControllerCreativeTemplate extends JControllerForm
{
	protected $view_item = 'aaa';
	public function edit($key = null, $urlVar = null)
	{
		$id = $_POST['cid'][0];
		$id = $id == 0 ? (int)$_GET['id'] : $id;
		JRequest::setVar( 'view', 'creativetemplate' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		
		$link = 'index.php?option=com_creativeimageslider&view=creativetemplate&layout=form&id='.$id;
		$this->setRedirect($link, $msg);
		//parent::display();
	}
	
	
	public function add()
	{
		JRequest::setVar( 'view', 'creativetemplate' );
		JRequest::setVar( 'layout', 'add'  );
		JRequest::setVar('hidemainmenu', 1);
	
		parent::display();
	}
	
	public function save($key = null, $urlVar = null)
	{
		$id = $_POST[cid][0];
		$id = $id == 0 ? (int)$_GET['id'] : $id;
		
		$task = $_REQUEST['task'];
		$model = $this->getModel('creativetemplate');
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_TEMPLATE_SAVED' );
		} else {
			$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_ERROR_SAVING_TEMPLATE' );
		}
	
		// Check the table in so it can be edited.... we are done with it anyway
		if($task == 'apply' && $id != 0) {

			$link = 'index.php?option=com_creativeimageslider&view=creativetemplate&layout=form&id='.$id;
		}
		else
			$link = 'index.php?option=com_creativeimageslider&view=creativetemplates';
		$this->setRedirect($link, $msg);
	}
	
	public function cancel($key = null, $urlVar = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_creativeimageslider&view=creativetemplates', $msg );
	}
}
