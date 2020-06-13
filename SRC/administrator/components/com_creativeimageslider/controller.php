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
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

class CreativeimagesliderController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'creativeimageslider';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */

	public function display($cachable = false, $urlparams = false)
	{
		// Load the submenu.
		CreativeimagesliderHelper::addSubmenu( 'Overview', 'creativeimageslider');
		CreativeimagesliderHelper::addSubmenu( 'Sliders', 'creativesliders');
		CreativeimagesliderHelper::addSubmenu( 'Items', 'creativeimages');
		CreativeimagesliderHelper::addSubmenu( 'Categories', 'creativecategories');
		//CreativeimagesliderHelper::addSubmenu( 'Templates', 'creativetemplates');

		parent::display();

		return $this;
	}
}
