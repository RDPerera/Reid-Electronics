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
jimport( 'joomla.application.component.view');

class CreativeimagesliderViewCreativeimages extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the view
	 *
	 * @return	void
	 */
    public function display($tpl = null) {
    	
    	$this->items		= $this->get('Items');
    	$this->pagination	= $this->get('Pagination');
    	$this->state		= $this->get('State');
    	$sliders	= $this->get('creativeSliders');
    	
    	//get category options
    	$options        = array();
    	foreach($sliders AS $slider) {
    		$options[]      = JHtml::_('select.option', $slider->id, $slider->name);
    	}
    	if(JV == 'j2') {
    		$this->assignRef( 'slider_options', $options );
    	}
    	else {
    		JHtmlSidebar::addFilter(
    				JText::_('JOPTION_SELECT_PUBLISHED'),
    				'filter_published',
    				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
    		);
    		 
    		JHtmlSidebar::addFilter(
    				JText::_('COM_CREATIVEIMAGESLIDER_SELECT_SLIDER'),
    				'filter_slider_id',
    				JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.slider_id'))
    		);
    	}
       	$this->addToolbar();
       	if(JV == 'j3')
       		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
    }
	    
    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar()
    {
    	JToolBarHelper::addNew('creativeimage.add');
    	JToolBarHelper::editList('creativeimage.edit');
	    	
		JToolBarHelper::divider();
 		JToolBarHelper::publish('creativeimages.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('creativeimages.unpublish', 'JTOOLBAR_UNPUBLISH', true);
    	JToolBarHelper::deleteList('', 'creativeimages.delete', 'JTOOLBAR_DELETE');
    }
    
    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields()
    {
    	return array(
    			'sa.ordering' => JText::_('JGRID_HEADING_ORDERING'),
    			'sa.name' => JText::_('COM_CREATIVEIMAGESLIDER_NAME'),
    			'slider_name' => JText::_('COM_CREATIVEIMAGESLIDER_SLIDER'),
    			'sa.published' => JText::_('JSTATUS'),
    			'sa.id' => JText::_('JGRID_HEADING_ID')
    	);
    }
}