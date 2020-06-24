<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: view.html.php 2012-04-05 14:30:25 svn $
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


class CreativeimagesliderViewCreativesliders extends JViewLegacy {
	
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
    	$category_options	= $this->get('category_options');
 
    	//get category options
    	$options        = array();
    	foreach($category_options AS $category) {
    		$options[]      = JHtml::_('select.option', $category->id, $category->name);
    	}
       	if(JV == 'j2') {
	    	$this->assignRef( 'category_options', $options );
       	}
       	else {
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_PUBLISHED'),
       				'filter_published',
       				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
       		);
       		
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_CATEGORY'),
       				'filter_category_id',
       				JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.category_id'))
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
		JToolBarHelper::addNew('creativeslider.add');
		JToolBarHelper::editList('creativeslider.edit');
		    	
		JToolBarHelper::divider();
		JToolBarHelper::publish('creativesliders.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('creativesliders.unpublish', 'JTOOLBAR_UNPUBLISH', true);
    	JToolBarHelper::deleteList('', 'creativesliders.delete', 'JTOOLBAR_DELETE');
		
	    
		JToolBarHelper::divider();
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
				'sp.ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'sp.name' => JText::_('COM_CREATIVEIMAGESLIDER_NAME'),
				'sp.published' => JText::_('JSTATUS'),
				'category_title' => JText::_('JCATEGORY'),
				'num_images' => JText::_('COM_CREATIVEIMAGESLIDER_NUM_IMAGES'),
				'sp.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}