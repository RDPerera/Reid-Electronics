<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: sexypolls.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

jimport('joomla.application.component.controllerform');

class CreativeimagesliderControllerCreativeslider extends JControllerForm
{
	function __construct($default = array()) {
		parent::__construct($default);
	
		$this->registerTask('save', 'saveSlider');
		$this->registerTask('apply', 'saveSlider');

		$this->registerTask('save2copy', 'copySlider');
	}
	
	function saveSlider() {
		$id = JRequest::getInt('id',0);
		$model = $this->getModel('creativeslider');
	
		if ($model->saveSlider()) {
			$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_SAVED' );
		} else {
			$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_ERROR_SAVING_SLIDER' );
		}
	
		if($_REQUEST['task'] == 'apply' && $id != 0)
			$link = 'index.php?option=com_creativeimageslider&view=creativeslider&layout=edit&id='.$id;
		else
			$link = 'index.php?option=com_creativeimageslider&view=creativesliders';
		$this->setRedirect($link, $msg);
	}

	function copySlider() {
		$id = JRequest::getInt('id',0);
		$model = $this->getModel('creativeslider');
	
		$response = $model->copySlider();

		$msg_string = $response[0];
		$insert_id = $response[1];

		$id = $insert_id; 

		if ($msg_string == 'no') {
			$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_SLIDER_COPIED' );
			$msg_type = 'message';
		} else {
			$msg = JText::_( $msg_string );
			$msg_type = 'error';
		}
		
		if ($msg_string == 'no')
			$link = 'index.php?option=com_creativeimageslider&view=creativeslider&layout=edit&id='.$id;
		else
			$link = 'index.php?option=com_creativeimageslider&view=creativesliders';

		$this->setRedirect($link, $msg, $msg_type);
	}
}
