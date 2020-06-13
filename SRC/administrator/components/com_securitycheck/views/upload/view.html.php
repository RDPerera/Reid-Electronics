<?php

/**
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Securitycheck View
   */
class SecuritychecksViewUpload extends JViewLegacy
{
	/**
	 * Método display de la vista Securitycheck (muestra los detalles de las vulnerabilidades del producto escogido)
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_( 'Securitycheck' ).' | '.JText::_('COM_SECURITYCHECKPRO_IMPORT_CONFIG_TITLE'), 'securitycheck' );
		JToolBarHelper::custom('redireccion_control_panel','arrow-left','arrow-left','COM_SECURITYCHECK_REDIRECT_CONTROL_PANEL',false);				
							
		parent::display($tpl);
	}
}