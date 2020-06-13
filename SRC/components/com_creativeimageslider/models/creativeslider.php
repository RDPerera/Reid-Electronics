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

// Import Joomla! libraries
jimport('joomla.application.component.model');

class CreativeimagesliderModelCreativeslider extends JModelLegacy {
    function __construct() {
		parent::__construct();
    }
    
	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function getData()
	{
		
	}
    
}
?>