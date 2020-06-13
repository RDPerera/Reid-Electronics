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

/**
 * Profile plugin for sms_notification
 *
 * @package     Joomla.Plugins
 * @subpackage  user.profile
 * @version     1.6
 */
class plgUserSMSNotificationProfile extends JPlugin {    
    protected $autoloadLanguage = true;
    
    /**
     * @param       string  The context for the data
     * @param       init    The user int
     * @param       object
     * @return      boolean
     * @since       1.6
     */
    function onContentPrepareData($context, $data) {
        // Check we are manipulating a valid form.
        if (!in_array($context, array('com_users.profile', 'com_users.registration', 'com_users.user', 'com_admin.profile'))) {
            return true;
        }
                
        $userId = isset($data->id) ? $data->id : 0;
                
        if (!isset($data->smsnotificationprofile) and $userId > 0) {
            // Load the profile data from the database.
            $db = JFactory::getDbo();
            
            // Create a query object.
            $query = $db->getQuery(true);
            
            $query
                ->select($db->quoteName(array('profile_key', 'profile_value')))
                ->from($db->quoteName('#__user_profiles'))
                ->where($db->quoteName('user_id') . ' = ' . (int) $userId)
                ->where($db->quoteName('profile_key') . ' LIKE ' . $db->quote('smsnotificationprofile.%'))
                ->order('ordering');
            $db->setQuery($query);
            $results = $db->loadRowList();
                        
            // Check for a database error
            if ($db->getErrorNum()) {
                $this->_subject->setError($db->getErrorMsg());
                return false;
            }
            
            // Merge the profile data
            $data->smsnotificationprofile = array();
            foreach ($results as $value) {
                $key = str_replace('smsnotificationprofile.', '', $value[0]);
                $data -> smsnotificationprofile[$key] = json_decode($value[1], true);
            }
        }
        return true;
    }
    
    /**
     * @param       JForm   The form to be altered
     * @param       array   The associated data for the form.
     * @return      boolean
     * @since       1.6
     */
    function onContentPrepareForm($form, $data) {
        if (!($form instanceof JForm)) {
            $this->_subject->setError('JERROR_NOT_A_FORM');
            return false;
        }
        
        // Check we are manipulating a valid form
        if (!in_array($form->getName(), array('com_users.profile', 'com_users.registration', 'com_users.user', 'com_admin.profile'))) {
            return true;
        }
        
        if ($form->getName()=='com_users.profile') {
            // Add the profile fields to the form
            JForm::addFormPath(dirname(__FILE__).'/profiles');
            $form->loadFile('profile', false);
            
            // Toggle whether the phone number field is required
            if ($this->params->get('profile-require_phone_number', 1) > 0) {
                $form->setFieldAttribute('phone_number', 'required', $this->params->get('profile-require_phone_number') == 2, 'smsnotificationprofile');
            } else {
                $form->removeField('phone_number', 'smsnotificationprofile');
            }
        }
        
        // In this example, we treat the frontend registration and the back end user create or edit as the same.
        elseif ($form->getName()=='com_users.registration' || $form->getName()=='com_users.user') {
            // Add the registration fields to the form.
            JForm::addFormPath(dirname(__FILE__).'/profiles');
            $form->loadFile('profile', false);
            
            // Toggle whether the something field is required.
            if ($this->params->get('register-require_phone_number', 1) > 0) {
                $form->setFieldAttribute('phone_number', 'required', $this->params->get('register-require_phone_number') == 2, 'smsnotificationprofile');
            } else {
                $form->removeField('phone_number', 'smsnotificationprofile');
            }
        }
    }
    
    /**
	 * saves user profile data
	 *
	 * @param   array    $data    entered user data
	 * @param   boolean  $isNew   true if this is a new user
	 * @param   boolean  $result  true if saving the user worked
	 * @param   string   $error   error message
	 *
	 * @return bool
	 */
    
    function onUserAfterSave($data, $isNew, $result, $error) {
        $userId = JArrayHelper::getValue($data, 'id', 0, 'int');
                
        if ($userId && $result && isSet($data['smsnotificationprofile']) && (count($data['smsnotificationprofile']))) {
            try {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);           
                $query
                    ->delete($db->quoteName('#__user_profiles'))
                    ->where($db->quoteName('user_id') . ' = ' . (int) $userId)
                    ->where($db->quoteName('profile_key') . ' LIKE ' . $db->quote('smsnotificationprofile.%'));
                
                $db->setQuery($query);
                
                if (!$db->execute()) {
                    throw new Exception($db->getErrorMsg());
                }
                                
                $tuples = array();
                $order = 1;
                foreach ($data['smsnotificationprofile'] as $key=>$value) {
                    $tuples[] = '(' . $userId . ', ' . $db->quote('smsnotificationprofile.' . $key) . ', ' . $db->quote(json_encode($value)) . ', ' . ($order++) . ')';
                }
                
                $query2 = $db->getQuery(true);
                $query2->setQuery('INSERT INTO #__user_profiles VALUES ' . implode(', ', $tuples));
                
                $db->setQuery($query2);
                
                if (!$db->execute()) {
                    throw new Exception($db->getErrorMsg());
                }
            } catch (JException $error) {
                $this->_subject->setError($error->getMessage());
                return false;
            }
            if ($isNew) {
            	JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_smsnotification/models');
            	
            	$ismsModel = JModelLegacy::getInstance('SMSNotification', 'SMSNotificationModel');
            	
            	$params = JComponentHelper::getParams('com_smsnotification');
            	$enabled = $params->get('email_alert_enabled');
            	
            	if ($enabled == 'false') {
            		return;
            	}
            	
            	$phoneNumber = $params->get('admin_phone_number');
            	$alertParams = $params->get('register_alert_parameters');
            	$smsCount = $params->get('register_alert_sms_count');
            	
            	$parameterStrings = array();
            	array_push($parameterStrings, 'New user registered');

            	$name = $data['name'];
            	$username = $data['username'];
            	$email = $data['email'];
            	
            	foreach ($alertParams as $alertParam) {
            		switch ($alertParam) {
            			case 'name':
            				array_push($parameterStrings, 'Name:' . $name);
            				break;
            			case 'username':
            				array_push($parameterStrings, 'Username:' . $username);
            				break;
            			case 'email':
            				array_push($parameterStrings, 'Email:' . $email);
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
        return true;
    }
    
    /**
     * Remove all user profile information from the give user ID
     *
     * Method is called after user data is deleted from the database
     *
     * @param   array   $user       Holds the user data
     * @param   boolean $success    True if user was successfully stored in the database
     * @param   string  $msg        Message
     */
    function onUserAfterDelete($user, $success, $msg) {
        if (!$success) {
            return false;
        }
        
        $userId = JArrayHelper::getValue($user, 'id', 0, 'int');
        
        if ($userId) {
            try {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $conditions = array(
                    $db->quoteName('user_id') . ' = ' . $db->quote($userId),
                    $db->quoteName('profile_key') . ' LIKE ' . $db->quote('smsnotificationprofile.%')
                );
                $query
                    ->delete($db->quoteName('#__user_profiles'))
                    ->where($conditions);
                $db->setQuery($query);
                
                if (!$db->query()) {
                    throw new Exception ($db->getErrorMsg());
                }
            } catch (JExecption $e) {
                $this->_subject->setError($e->getMessage());
                return false;
            }
        }
        return true;
    }
}