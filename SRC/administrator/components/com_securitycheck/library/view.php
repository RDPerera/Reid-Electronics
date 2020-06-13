<?php
/**
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\View as JView;
use Joomla\CMS\Language\Text as JText;

if(!class_exists('JoomlaCompatView')) {
	if(interface_exists('JView')) {
		abstract class JoomlaCompatView extends JViewLegacy {}
	} else {
		class JoomlaCompatView extends JView {}
	}
}

class SecuritycheckView extends JoomlaCompatView {

function __construct() 	{
	parent::__construct();
	
	JToolBarHelper::title( JText::_( 'Securitycheck' ).' | ' .JText::_('COM_SECURITYCHECK_CPANEL_FIREWALL_CONFIGURATION'), 'securitycheck' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	JToolBarHelper::custom('redireccion_control_panel','arrow-left','arrow-left','COM_SECURITYCHECK_REDIRECT_CONTROL_PANEL',false);
	JToolBarHelper::custom('redireccion','arrow-left','arrow-left','COM_SECURITYCHECK_REDIRECT',false);	
}
	
}