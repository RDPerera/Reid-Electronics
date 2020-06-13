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

jimport( 'joomla.application.component.controller' );


/**
 * sexy_polling Controller
 *
 * @package Joomla
 * @subpackage sexy_polling
 */
class CreativeimagesliderController extends JControllerLegacy {
	
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'creativeslider';

    public function display($cachable = false, $urlparams = false) {
		parent::display();
    }
}
?>