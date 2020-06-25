<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.14F
	* Creation date: Janvier 2014
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die();

class GMapFPsViewGMapFPContact extends JViewLegacy
{
    function display($tpl = null)
    {
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 

        $document  = JFactory::getDocument();

        $params = clone($mainframe->getParams('com_gmapfp'));

        // Parametres
        $params->def('show_headings',           1);

        $model      = $this->getModel(); 
        $rows       = $model->getGMapFPList();
		$row		= @$rows[0];
       	$map        = $model->getView();
		$perso		= $model->getPersonnalisation();

		JHTML::_('behavior.formvalidation');
		
        $this->assignRef('map'    , $map );	        
        $this->assignRef('row'    , $row);
        $this->assignRef('perso'  , $perso);
        $this->assignRef('params' , $params);

        parent::display($tpl);
    }   
}
?>
