<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// NO direct access to this file
defined('_JEXEC') or die;

// Get an istance of the contorller prefixed by ISMS
$controller = JControllerLegacy::getInstance('SMSNotification');

// Perform the Request task and Execute request task
$controller->execute(JFactory::getApplication()->input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();