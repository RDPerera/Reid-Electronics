<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
jimport('joomla.access.access');
jimport('joomla.user.user');

/**
 * iSMS Controller
 *
 * @since   0.0.1
 */
class SMSNotificationControllerSMSNotification extends JControllerLegacy {
    public function send() {        
        // Get the form data
        $formData = new JRegistry($this->input->get('jform', '', 'array'));
        $recipient = $formData->get('recipient', 0);
        switch ($recipient) {
            case "phone_number":
                $this->sendPhoneNumber($formData);
                break;
            case "contact":
                $this->sendContact($formData);
                break;
            case "usergroup":
                if (JPluginHelper::isEnabled("user", "smsnotificationprofile")) {
                    $this->sendUsergroup($formData);
                } else {
                    $this->setMessage(JText::_('COM_SMSNOTIFICATION_ACTIVATE_USER_PLUGIN'));
                    $this->setRedirect('index.php?option=com_smsnotification');
                }
                break;
        }
    }
    
    private function sendPhoneNumber($formData) {
        $message = $formData->get('message', JText::_('COM_SMSNOTIFICATION_NO_MESSAGE'));
        $messageType = $formData->get('message_type', 1);
        $phoneNumber = $formData->get('to_phone_number');
        
        $returnMessage = "";
        $returnStatus = "message";
        
        $return = $this->getModel()->sendISMS($phoneNumber, $message, $messageType);
        
        if (!empty($return)) {
            if (strcmp($return[0], "2000") == 0) {
                $returnMessage = JText::_('COM_SMSNOTIFICATION_ALERT_SUCCESS');
            } else {
                $returnMessage = $return[1];
                $returnStatus = "error";
            }
        }
        
        $this->setMessage($returnMessage, $returnStatus);
        $this->setRedirect('index.php?option=com_smsnotification');
    }
    
    private function sendContact($formData) {
        $message = $formData->get('message', JText::_('COM_SMSNOTIFICATION_NO_MESSAGE'));
        $messageType = $formData->get('message_type', 1);
        $phoneNumber = str_replace('"', "", $formData->get('to_contact'));
        
        $returnMessage = "";
        $returnStatus = "message";
        
//         echo $message;
//         echo $messageType;
//         echo $phoneNumber;
//         print_r($formData);
//         jexit();
        
        $return = $this->getModel()->sendISMS($phoneNumber, $message, $messageType);
        
        if (!empty($return)) {
            if (strcmp($return[0], "2000") == 0) {
                $returnMessage = JText::_('COM_SMSNOTIFICATION_ALERT_SUCCESS');
            } else {
                $returnMessage = $return[1];
                $returnStatus = "error";
            }
        }
        
        $this->setMessage($returnMessage, $returnStatus);
        $this->setRedirect('index.php?option=com_smsnotification');
    }
    
    private function sendUsergroup($formData) {
        $message = $formData->get('message', JText::_('COM_SMSNOTIFICATION_NO_MESSAGE'));
        $messageType = $formData->get('message_type', 1);
        $userGroup = $formData->get('to_usergroup');
        $groupUsers = JAccess::getUsersByGroup($userGroup);
        
        $phoneNumbers = array();
        
        $notificationArray = array();
        $returnMessage = "";
        $returnStatus = "message";
        
        foreach($groupUsers as $user_id) {
            $user = JFactory::getUser($user_id);
            $profile = JUserHelper::getProfile($user_id);
            if (isset($profile->smsnotificationprofile['phone_number']) && $profile->smsnotificationprofile['phone_number'] != "") {
                $phoneNumbers[] = $profile->smsnotificationprofile['phone_number'];
            }
        }
        $phoneNumberString = implode(";", $phoneNumbers);
        $return = $this->getModel()->sendISMS($phoneNumberString, $message, $messageType);
        
        if (!empty($return)) {
            $status = $return[0];
            if (strcmp($return[0], "2000") == 0) {
                $notification = JText::_('COM_SMSNOTIFICATION_ALERT_SUCCESS');
            } else {
                $notification = $return[1];
                $returnStatus = "error";
            }
            
            $this->setMessage($notification, $returnStatus);
        }
        
        $this->setRedirect('index.php?option=com_smsnotification');
    }
}