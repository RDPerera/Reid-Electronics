<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

jimport('joomla.application.component.helper');

/**
 * iSMS View
 */
class SMSNotificationViewSMSNotification extends JViewLegacy {
    /**
     * iSMS view display method
     * @param   string  $tpl    The name of the template file to parse; automatically searches throug the template paths/
     *
     * @return  mixed   A string if successful, otherwise a JError object.
     */
    function display($tpl = null) {
        // Get data from the model
        $form = $this->get('Form');
        $balance = $this->get('Balance');
        $contacts = $this->get('Contacts');
        $messageType = $this->get('Type');
                
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        
        $form->setValue('message_type', null, $messageType);
        
        // Assign data to the view
        $this->form = $form;
        $this->balance = $balance;
        $this->contacts = $contacts;
        
        // Set the toolbar
        $this->addToolBar();
        
        JHtml::_('jquery.framework');
        
        // Display the template
        parent::display($tpl);
    }
    
    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        JToolbarHelper::title(JText::_('COM_SMSNOTIFICATION_VIEW_SEND_SMS_TITLE'));
        JToolBarHelper::custom('smsnotification.send', 'redo', null, 'COM_ISMS_TOOLBAR_SEND', false);
        JToolBarHelper::preferences('com_smsnotification');
    }
}
?>