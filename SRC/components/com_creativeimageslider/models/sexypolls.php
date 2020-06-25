<?php
/**
 * Joomla! component sexypolling
 *
 * @version $Id: sexypolls.php 2012-04-05 14:30:25 svn $
 * @author 2GLux.com
 * @package Sexy Polling
 * @subpackage com_sexypolling
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class SexypollingModelSexypolls extends JModelLegacy {
    function __construct() {
		parent::__construct();
		
		$app	 = JFactory::getApplication();
		$params	 = $app->getParams();
		$id_16 = $params->get('category',0);
		
		$id_15 = JRequest::getVar('category',  0, '', 'int');
		$id = $id_15 != 0 ? $id_15 : $id_16;
		$this->setId($id);
    }
    
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = 'SELECT '.
						'sp.id polling_id, '.
						'sp.id_template id_template, '.
						'sp.date_start date_start, '.
						'sp.date_end date_end, '.
						'sp.multiple_answers multiple_answers, '.
						'sp.voting_period voting_period, '.
						'sp.number_answers number_answers, '.
						'sp.voting_permission voting_permission, '.
						'sp.answerpermission answerpermission, '.
						'sp.autopublish autopublish, '.
						'sp.baranimationtype baranimationtype, '.
						'sp.coloranimationtype coloranimationtype, '.
						'sp.reorderinganimationtype reorderinganimationtype, '.
						'sp.dateformat dateformat, '.
						'sp.autoopentimeline autoopentimeline, '.
						'sp.autoanimate autoanimate, '.
						'sp.showresultbutton showresultbutton, '.
						'st.styles styles, '.
						'sp.name polling_name, '.
						'sp.question polling_question, '.
						'sa.id answer_id, '.
						'sa.name answer_name '.
					'FROM '.
						'`#__sexy_polls` sp '.
					'JOIN '.
						'`#__sexy_answers` sa ON sa.id_poll = sp.id '.
						'AND sa.published = \'1\' '.
					'LEFT JOIN '.
						'`#__sexy_templates` st ON st.id = sp.id_template '.
					'WHERE sp.published = \'1\' '.
					'AND sp.id_category = '.$this->_id.' '.
						'ORDER BY sp.ordering,sp.name,sa.ordering,sa.name';
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObjectList();
		}
		if (!$this->_data) {
			$this->_data = false;
		}
		return $this->_data;
	}
    
}
?>