<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */
defined('_JEXEC') or die;



class GeofactoryController extends JControllerLegacy{
	protected $default_view = 'accueil';
	
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false){
		require_once JPATH_COMPONENT.'/helpers/geofactory.php';

		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->get('view', 'accueil');
		
		// faut il montrer les terms ?
		$config = JComponentHelper::getParams('com_geofactory');
		$terms = $config->get('showTerms', 1);

		if ($terms==1)	$vName = "terms" ;

		$vFormat = $document->getType();
		$lName   = $this->input->get('layout', 'default');

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {

			// Get the model for the view.
			$model = $this->getModel($vName);

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->document = $document;

			// Load the submenu.
			GeofactoryHelperAdm::addSubmenu($vName);
			$view->display();
		}

		return $this;
	}
}
