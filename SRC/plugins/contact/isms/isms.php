<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// No direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla helper
jimport('joomla.application.component.helper');

// Load SMS Notification Model
jimport('joomla.application.component.model');

class plgContactISMS extends JPlugin {
    protected $autoloadLanguage = true;
    
    function onSubmitContact($contact, $data) {
        JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_smsnotification/models');
        
        $ismsModel = JModelLegacy::getInstance('SMSNotification', 'SMSNotificationModel');
        
        $params = JComponentHelper::getParams('com_smsnotification');
        $enabled = $params->get('email_alert_enabled');
        
        if ($enabled == 'false') {
            return;
        }
        
        $phoneNumber = $params->get('admin_phone_number');
        $alertParams = $params->get('email_alert_parameters');
        $smsCount = $params->get('email_alert_sms_count');
        
        $parameterStrings = array();
        
        $name = $data['contact_name'];
        $email = $data['contact_email'];
        $subject = $data['contact_subject'];
        $message = $data['contact_message'];
        
        foreach ($alertParams as $alertParam) {
            switch ($alertParam) {
                case 'name':
                    array_push($parameterStrings, 'Name:' . $name);
                    break;
                case 'email':
                    array_push($parameterStrings, 'Email:' . $email);
                    break;
                case 'subject':
                    array_push($parameterStrings, 'Subject:' . $subject);
                    break;
                case 'message':
                    array_push($parameterStrings, 'Message:' . $message);
                    break;
            }
        }
        
        $alertMessage = implode('%0a', $parameterStrings);
        
        if ($smsCount > 0) {
            if ($smsCount == 1) {
                $messageLength = 153;
            } else {
                $messageLength = 152;
            }
            $alertMessage = substr($alertMessage, 0, $messageLength * $smsCount);
        }
                
        if ($phoneNumber != "" && $alertMessage != "") {
            $ismsModel->sendISMS($phoneNumber, $alertMessage, '1');
        }
    }
}
?>