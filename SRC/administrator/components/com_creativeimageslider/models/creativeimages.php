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
jimport('joomla.application.component.modellist');
	
class CreativeimagesliderModelCreativeimages extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'id', 'sa.id',
					'name', 'sa.name',
					'slider_name',
					'slider_id',
					'published', 'sa.published',
					'ordering', 'sa.ordering',
					'publish_up', 'sa.publish_up',
					'publish_down', 'sa.publish_down'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to get category options
	 *
	 */
	public function getCreativeSliders() {
		$db		= $this->getDbo();
		$sql = "SELECT `id`, `name` FROM `#__cis_sliders` WHERE `published` <> '-2' order by `ordering`,`name` ";
		$db->setQuery($sql);
		return $opts = $db->loadObjectList();
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$sliderId = $this->getUserStateFromRequest($this->context.'.filter.slider_id', 'filter_slider_id');
		$this->setState('filter.slider_id', $sliderId);
			
		// List state information.
		parent::populateState('sa.ordering', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.slider_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select',
						'sa.id, sa.name, sa.img_name, sa.img_url, sa.published, sa.ordering'.
						', sa.publish_up, sa.publish_down'
				)
		);
		
		$query->from('#__cis_images AS sa');
			
		// get only published polls answers
		$query->join('INNER', '#__cis_sliders AS sp1 ON sp1.id=sa.id_slider AND sp1.published <> -2');
		
		// Join over the categories.
		$query->select('sp.name AS slider_name,sp.id AS slider_id');
		$query->join('left', '#__cis_sliders AS sp ON sp.id=sa.id_slider');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('sa.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(sa.published = 0 OR sa.published = 1)');
		}

		// Filter by a single or group of categories.
		$sliderId = $this->getState('filter.slider_id');
		if (is_numeric($sliderId)) {
			$query->where('sa.id_slider = '.(int) $sliderId);
		}
		elseif (is_array($sliderId)) {
			JArrayHelper::toInteger($sliderId);
			$sliderId = implode(',', $sliderId);
			$query->where('sa.id_slider IN ('.$sliderId.')');
		}

		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('sa.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(sa.name LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'sa.ordering');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		/*
			if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
		$orderCol = 'c.title '.$orderDirn.', a.ordering';
		}
		*/
		$query->order($db->escape($orderCol.' '.$orderDirn));
		$query->group('sa.id');

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}

?>
