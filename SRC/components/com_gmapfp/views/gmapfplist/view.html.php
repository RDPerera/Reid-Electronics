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

class GMapFPsViewGMapFPList extends JViewLegacy
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
        $params->def('filter',                  1);

        $filtrevilles = array();
        $filtrevilles[] = JHTML::_('select.option', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --' );
                $cn = 0;
				$doublon_filtre ='';
                foreach($this->get('listville') as $temp) {
					if ($temp['ville']) {
						if ($temp['ville']!=$doublon_filtre) {
							$cn++;
							$filtrevilles[] = JHTML::_('select.option', $temp['id'], $temp['ville'] );
							$doublon_filtre=$temp['ville'];
						}
					} else {
						//$filtredepartements[] = JHTML::_('select.option', $temp['id'], '?' );
						//$cn++;
					};
                }
        $filtreville = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE' ).' --', 'string' );
        if ($cn>1) {
            $lists['ville'] = JHTML::_('select.genericlist', $filtrevilles, 'filtreville', 'size="1" class="inputbox" onchange="form.submit()"', 'value', 'text', $filtreville );
        }

        $filtredepartements = array();
        $filtredepartements[] = JHTML::_('select.option', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --' );
                $cn = 0;
				$doublon_filtre ='';
                foreach($this->get('listdepartement') as $temp) {
					if ($temp['departement']) {
						if ($temp['departement']!=$doublon_filtre) {
							$cn++;
							$filtredepartements[] = JHTML::_('select.option', $temp['id'], $temp['departement'] );
							$doublon_filtre=$temp['departement'];
						}
					} else {
						//$filtredepartements[] = JHTML::_('select.option', $temp['id'], '?' );
						//$cn++;
					};
                }
        $filtredepartement = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT' ).' --', 'string' );
        if ($cn>1) {
            $lists['departement'] = JHTML::_('select.genericlist', $filtredepartements, 'filtredepartement', 'size="1" class="inputbox" onchange="form.submit()"', 'value', 'text', $filtredepartement );
        }

        $filtrepayss = array();
        $filtrepayss[] = JHTML::_('select.option', '-- '.JText::_( 'GMAPFP_PAYS_FILTRE' ).' --' );
                $cn = 0;
				$doublon_filtre ='';
                foreach($this->get('listpays') as $temp) {
 					if ($temp['pay']) {
						if ($temp['pay']!=$doublon_filtre) {
							$cn++;
							$filtrepayss[] = JHTML::_('select.option', $temp['id'], $temp['pay'] );
							$doublon_filtre=$temp['pay'];
						}
					} else {
						//$filtrepayss[] = JHTML::_('select.option', $temp['id'], '?' );
						//$cn++;
					};
                }
        $filtrepays = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrepays', 'filtrepays', '-- '.JText::_( 'GMAPFP_PAYS' ).' --', 'string' );
        if ($cn>1) {
            $lists['pays'] = JHTML::_('select.genericlist', $filtrepayss, 'filtrepays', 'size="1" class="inputbox" onchange="form.submit()"', 'value', 'text', $filtrepays );
        }

        $filtrecategories = array();
        $filtrecategories[] = JHTML::_('select.option', '-- '.JText::_( 'GMAPFP_CATEGORIE_FILTRE' ).' --' );
                $cn = 0;
                foreach($this->get('listcategorie') as $temp) {
                    $cn++;
                    $filtrecategories[] = JHTML::_('select.option', $temp->id, $temp->title );
                }
        $filtrecategorie = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrecategorie', 'filtrecategorie', '-- '.JText::_( 'GMAPFP_CATEGORIE' ).' --', 'string' );
        if ($cn>1) {
            $lists['categorie'] = JHTML::_('select.genericlist', $filtrecategories, 'filtrecategorie', 'size="1" class="inputbox" onchange="form.submit()"', 'value', 'text', $filtrecategorie );
        }

        $search_gmapfp = $mainframe->getUserStateFromRequest( $option.$Itemid.'search_gmapfp', 'search_gmapfp', '',    'string' );
        $lists['search_gmapfp'] = $search_gmapfp;
        $this->assignRef('lists', $lists);

        $model      = $this->getModel(); 
        $rows       = $model->getGMapFPList();
       	$map        = $model->getView();
		$perso		= $model->getPersonnalisation();
		
        $this->assignRef('map'          , $map );	        
        $this->assignRef('rows'         , $rows);
        $this->assignRef('perso'        , $perso);
        $this->assignRef('params'       , $params);

        parent::display($tpl);
    }   
}
?>
