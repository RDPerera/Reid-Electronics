<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

jimport('joomla.application.component.helper');

/**
 * iSMS Model
 */
class SMSNotificationModelSMSNotification extends JModelAdmin {
    // iSMS API
    protected $api_balance = 'https://www.isms.com.my/isms_balance.php';
    protected $api_contacts = 'https://www.isms.com.my/api_list_of_contact.php';
    protected $api_send = 'https://www.isms.com.my/isms_send.php';
    protected $validate_fail = 'Please set your iSMS username and password';
    
    /**
     * Method to get the record form
     *
     * @param       array   $data       Data for the form.
     * @param       boolean $loadData   True if the form is to load its own data (default case), false if not
     * @return      mixed   A JForm object on success, false on failure
     * @since       2.5
     */
    public function getForm($data=array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_smsnotification.smsnotification', 'smsnotification', array('control' => 'jform', 'load_data' => $loadData));
        if (!$form) {
            return false;
        } else {
            return $form;
        }
    }
    
    public function getType() {
        return JComponentHelper::getParams('com_smsnotification')->get('isms_message_type');
    }
    
    private function iSMScURL($link) {
        $http = curl_init($link);
        // Perform curl operation
        curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
        $http_result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);
        
        return $http_result;
    }
    
    public function getBalance() {
        $params = JComponentHelper::getParams('com_smsnotification');
        $username = $params->get('isms_username');
        $password = $params->get('isms_password');
        
        
        if (!$this->validateISMS($username, $password)) {
            JFactory::getApplication()->enqueueMessage($this->validate_fail, 'warning');
            return $this->validate_fail;
        }
        
        $link = $this->api_balance.'?';
        $link .= "un=".urlencode($username);
        $link .= "&pwd=".urlencode($password);
                        
        $result = $this->iSMScURL($link);
        $balance = (float)$result;
        
        if ($balance < 0) return substr($result, 8);
        else return $result;
    }
    
    public function getContacts() {
        $params = JComponentHelper::getParams('com_smsnotification');
        $username = $params->get('isms_username');
        $password = $params->get('isms_password');
        
        if (!$this->validateISMS($username, $password)) {
            JFactory::getApplication()->enqueueMessage($this->validate_fail, 'warning');
            return $this->validate_fail;
        }
        
        $link = $this->api_contacts.'?';
        $link .= "un=".urlencode($username);
        $link .= "&pwd=".urlencode($password);
        
        $result = $this->iSMScURL($link);
        $returnArray = array();
        $contacts = explode("||", $result, -1);
        foreach($contacts as $contact) {
            $values = explode("|v|", $contact);
            $name = $values[0];
            $phoneNumber = $values[1];
            $returnArray[] = array($name, $phoneNumber);
        }
        return $returnArray;
    }
    
    public function getUsers() {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query 
    	->select($db->quoteName(array('a.username', 'b.profile_value', 'b.profile_key', 'a.id', 'b.user_id')))
    	->from($db->quoteName('#__users', 'a'))
    	->join('LEFT', $db->quoteName('#__user_profiles', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.user_id') . ')')
    	->where($db->quoteName('b.profile_key') . ' LIKE ' . $db->quote('smsnotificationprofile.%'))
    	->order('ordering');
    	$db->setQuery($query);
    	$results = $db->loadRowList();
    	$returnArray = array();
    	foreach ($results as $result) {
            $name = $result[0];
			$phoneNumber = $result[1];
            $returnArray[] = array($name, $phoneNumber);
        }
        return $returnArray;
    }
    
    public function sendISMS($destination, $message, $messageType, $senderID = '') {
        $params = JComponentHelper::getParams('com_smsnotification');
        $username = $params->get('isms_username');
        $password = $params->get('isms_password');
        
        if (!$this->validateISMS($username, $password)) {
            JFactory::getApplication()->enqueueMessage($this->validate_fail, 'error');
        }
        
        $link = $this->api_send.'?';
        $link .= "un=".urldecode($username);
        $link .= "&pwd=".urlencode($password);
        $link .= "&dstno=".urlencode($destination);
        $link .= "&msg=".urlencode($message);
        $link .= "&type=".urlencode($messageType);
        $link .= "&sendid=".urlencode($senderID);
        
        $result = $this->iSMScURL($link);
        
        $resultValue = (float)$result;
        if ($resultValue < 0) return array($resultValue, substr($result, 8));
        else return array("2000", $result);
    }
    
    public function validateISMS($username, $password) {
        if ($username && $password) {
            return true;
        } else {
            return false;
        }
    }
}
?>