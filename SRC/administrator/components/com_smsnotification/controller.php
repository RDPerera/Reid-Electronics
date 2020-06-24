<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// NO direct access to this file
defined('_JEXEC') or die;

/**
 * General Controller of iSMS component
 */
class SMSNotificationController extends JControllerLegacy {
    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false, $urlparams = false) {
        // set default view if not set
        $input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'SMSNotification'));
        
        // call parent behavior
        parent::display($cachable);
    }
}
?>