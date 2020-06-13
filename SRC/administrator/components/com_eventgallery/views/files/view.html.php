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


class EventgalleryViewFiles extends EventgalleryLibraryCommonView
{
    /**
     * @var EventgalleryLibraryFolder
     */
    protected $folder;
    protected $items;
    protected $pagination;
    protected $state;

    /**
     * @var \Joomla\Component\Eventgallery\Site\Library\Configuration\Main
     */
    protected $config;

    function display($tpl = null)
	{
        $this->folder = $this->get('Item');
        $this->state		= $this->get('State');
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->config = \Joomla\Component\Eventgallery\Site\Library\Configuration\Main::getInstance();

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();
        EventgalleryHelpersEventgallery::addSubmenu('files');      
        $this->sidebar = JHtmlSidebar::render();
        return parent::display($tpl);
	}

    protected function addToolbar() {
        $text = $this->folder->getFolderName();
        JToolbarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTS' ).': <small><small>[ ' . $text.' ]</small></small>' );

        JToolbarHelper::cancel('files.cancel', 'Close');

        if ($this->folder->supportsImageDataEditing()) {
            JToolbarHelper::custom('files.publish', 'eg-published');
            JToolbarHelper::custom('files.unpublish', 'eg-published-inactive');

            JToolbarHelper::custom('files.ismainimage', 'eg-mainimage');
            JToolbarHelper::custom('files.isnotmainimage', 'eg-mainimage-inactive');

            JToolbarHelper::custom('files.isnotmainimageonly', 'eg-mainimageonly');
            JToolbarHelper::custom('files.ismainimageonly', 'eg-mainimageonly-inactive');
        }

        JToolbarHelper::spacer(50);

        if ($this->folder->supportsFileDeletion()) {
            JToolbarHelper::deleteList(JText::_('COM_EVENTGALLERY_EVENT_IMAGE_ACTION_DELETE_ALERT'), 'files.delete');
        }
        $bar = JToolbar::getInstance('toolbar');
        if ($this->folder->isSortable()) {
            $bar->appendButton('Confirm', 'COM_EVENTGALLERY_EVENT_CLEAR_ORDERING_ALERT', 'trash', 'COM_EVENTGALLERY_EVENT_CLEAR_ORDERING', 'files.clearOrdering', false);
        }
        

        JToolbarHelper::spacer(100);
        $bar->appendButton('Link', 'edit', 'COM_EVENTGALLERY_BUTTON_EDIT_DESC',  JRoute::_('index.php?option=com_eventgallery&task=event.edit&id='.$this->folder->getId()), false);
        if ($this->folder->supportsFileUpload()) {
            $bar->appendButton('Link', 'upload', 'COM_EVENTGALLERY_BUTTON_UPLOAD_DESC', JRoute::_('index.php?option=com_eventgallery&view=upload&folderid=' . $this->folder->getId()), false);
            $url = JUri::base().substr(JRoute::_('index.php?option=com_eventgallery&view=files&layout=sorting&tmpl=component&folderid='.$this->folder->getId()), strlen(JUri::base(true)) + 1);

            $bar->appendButton('Custom',
                JHtml::_(
                    'bootstrap.renderModal',
                    'file-sorting-modal',
                    array(
                        'title'       => JText::_('COM_EVENTGALLERY_EVENT_PICASA_ABLUM_SELECTOR'),
                        'bodyHeight'  => '80',
                        'modalWidth'  => '80',
                    ),
                    $this->loadTemplate('sorting')
                 ).
                '<a class="btn hasTooltip" data-toggle="modal" data-target="#file-sorting-modal" title="'.JText::_('COM_EVENTGALLERY_FILE_SORTING_POPUP').'"><i class="icon-cog"></i> '.JText::_('COM_EVENTGALLERY_FILE_SORTING_POPUP').'</a>'
            );
        }
    }
}
