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

class CreativeimagesliderControllerCreativeimage extends JControllerForm
{
	function __construct($default = array()) {
		parent::__construct($default);

		$this->registerTask('add', 'addimage');
	
		$this->registerTask('save', 'saveImage');
		$this->registerTask('apply', 'saveImage');
		$this->registerTask('save2new', 'saveImage');

		$this->registerTask('save2copy', 'copyImage');
	}

	function addimage() {
		$slider_id = (int)$_REQUEST['filter_slider_id'];
		$link = 'index.php?option=com_creativeimageslider&view=creativeimage&layout=edit&filter_slider_id='.$slider_id;
		$this->setRedirect($link, $msg);
	}	

	
	function saveImage() {
		$id = JRequest::getInt('id',0);
		$slider_id = (int)$_REQUEST['id_slider'];
		$model = $this->getModel('creativeimage');
	
		$msg_string = $model->saveImage();
		if ($msg_string == 'no') {
			$msg = JText::_( 'COM_CREATIVEIMAGESLIDER_IMAGE_SAVED' );
			$msg_type = 'message';
		} else {
			$msg = JText::_( $msg_string );
			$msg_type = 'error';
		}
		
		if($_REQUEST['task'] == 'apply' && $id != 0)
			$link = 'index.php?option=com_creativeimageslider&view=creativeimage&layout=edit&id='.$id;
		elseif($_REQUEST['task'] == 'save2new')
			$link = 'index.php?option=com_creativeimageslider&view=creativeimage&layout=edit&filter_slider_id='.$slider_id;
		else
			$link = 'index.php?option=com_creativeimageslider&view=creativeimages';
		$this->setRedirect($link, $msg, $msg_type);
	}

	function copyImage() {
		$id = JRequest::getInt('id',0);
		$slider_id = (int)$_REQUEST['id_slider'];
		$model = $this->getModel('creativeimage');
	
		$response = $model->copyImage();

		$msg_string = $response[0];
		$insert_id = $response[1];

		$id = $insert_id; 

		if ($msg_string == 'no') {
			$msg = JText::_( 'COM_CREATIVECONTACTFORM_FIELD_COPIED' );
			$msg_type = 'message';
		} else {
			$msg = JText::_( $msg_string );
			$msg_type = 'error';
		}
		
		if ($msg_string == 'no')
			$link = 'index.php?option=com_creativeimageslider&view=creativeimage&layout=edit&id='.$id;
		else
			$link = 'index.php?option=com_creativeimageslider&view=creativeimage&layout=edit&id='.$id;

		$this->setRedirect($link, $msg, $msg_type);
	}
}
