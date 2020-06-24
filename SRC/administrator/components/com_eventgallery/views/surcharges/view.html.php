<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;



jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewSurcharges extends EventgalleryLibraryCommonView
{    

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     * @param null $tpl
     * @return bool|mixed
     */
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        $this->addToolbar();
        EventgalleryHelpersEventgallery::addSubmenu('surcharges');      
        $this->sidebar = JHtmlSidebar::render();
        return parent::display($tpl);
    }

    protected function addToolbar() {
        JToolbarHelper::title(   JText::_( 'COM_EVENTGALLERY_SURCHARGES' ), 'generic.png' );            
        JToolbarHelper::addNew('surcharge.add');
        JToolbarHelper::editList('surcharge.edit');
        JToolbarHelper::publishList('surcharges.publish');
        JToolbarHelper::unpublishList('surcharges.unpublish');
        JToolbarHelper::deleteList('Remove all selected Events?','surcharges.delete','Remove');
    }

}
