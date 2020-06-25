<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/0
	* Creation date: Mars 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Contact Component Category Tree
 *
 * @static
 * @package		Joomla
 * @subpackage	com_contact
 * @since 1.6
 */
class GmapfpCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__gmapfp';
		$options['extension'] = 'com_gmapfp';
		//$options['statefield'] = 'published';
		parent::__construct($options);
	}
}