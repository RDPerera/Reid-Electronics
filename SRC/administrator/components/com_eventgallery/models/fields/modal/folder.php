<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
require_once JPATH_ADMINISTRATOR . '/components/com_eventgallery/helpers/backendmedialoader.php';

/**
 * Supports a modal folder picker.
 *
 */
class JFormFieldModal_Folder extends  JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Modal_Folder';

    public function __construct($form = null) {
    	include_once JPATH_BASE.'/components/com_eventgallery/version.php';
        JLoader::registerPrefix('Eventgallery', JPATH_SITE.'/components/com_eventgallery');        
        parent::__construct();
    }
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
        JHtml::_('script', 'system/modal-fields.js', array('version' => 'auto', 'relative' => true));

		// Load necessary media files 
		EventgalleryHelpersBackendmedialoader::load();
		
		$allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_eventgallery', JPATH_ADMINISTRATOR);

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectEvent_'.$this->id.'(id, title, catid, object, url, language) {';
        $script[] = '	    window.processModalSelect(\'Event\', "' . $this->id . '", id, title);';
		$script[] = '	}';


        $modalId = 'Event_' . $this->id;

		// Clear button script
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = '	function jClearEvent(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('COM_EVENTGALLERY_SELECT_AN_EVENT', true), ENT_COMPAT, 'UTF-8').'";';
			$script[] = '       document.getElementById(id + "_clear").classList.add("hidden")';
			$script[] = '		if (document.getElementById("#" + id + "_edit")) {';
            $script[] = '           document.getElementById(id + "_edit").classList.add("hidden")';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_eventgallery&amp;view=events&amp;layout=modal&amp;tmpl=component&amp;function=jSelectEvent_'.$this->id;


		if (strlen($this->value) > 0)
		{

            /**
             * @var EventgalleryLibraryFactoryFolder $folderFactory
             */
            $folderFactory = EventgalleryLibraryFactoryFolder::getInstance();
            $folder = $folderFactory->getFolder($this->value);

            if (null != $folder) {
                $title = $folder->getDisplayName();
            }

		}

		if (empty($title))
		{
			$title = JText::_('COM_EVENTGALLERY_SELECT_AN_EVENT');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active event id field.
		if (strlen($this->value)==0)
		{
			$value = '';
		}
		else
		{
			$value = $this->value;
		}

		// The current event display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = JHtml::_(
                    'bootstrap.renderModal',
                    'ModalSelect' . $modalId,
                    array(
                        'title'       => JText::_('COM_EVENTGALLERY_LAYOUT_EVENT_FOLDER_LABEL'),
                        'url'         => $link.'&amp;'.JSession::getFormToken().'=1',
                        'bodyHeight'  => '80',
                        'modalWidth'  => '80',
                        'footer'      => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
                    )
                );

		$html[] = '<a class="btn hasTooltip" data-toggle="modal" data-target="#ModalSelect' . $modalId.'" title="'.JText::_('COM_EVENTGALLERY_CHANGE_EVENT').'"><i class="icon-file"></i> '.JText::_('JSELECT').'</a>';

		// Clear event button
		if ($allowClear)
		{
			$html[] = '<button id="'.$this->id.'_clear" class="btn'.($value ? '' : ' hidden').'" onclick="return jClearEvent(\''.$this->id.'\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';
		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return implode("\n", $html);
	}

}