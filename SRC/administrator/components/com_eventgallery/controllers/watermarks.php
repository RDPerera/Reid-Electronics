<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controlleradmin' );

class EventgalleryControllerWatermarks extends JControllerAdmin
{

    protected $model_name = 'Watermarks';
    protected $default_view = 'watermarks';

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->registerTask('default', 'setDefault');
        $this->registerTask('undefault', 'setDefault');
    }

    /**
     * Proxy for getModel.
     * @param string $name
     * @param string $prefix
     * @param array $config
     * @return object
     */
    public function getModel($name = 'Watermark', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function setDefault() {

        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $data = array('undefault' => 0, 'default' => 1);
        $task = $this->getTask();
        $value = Joomla\Utilities\ArrayHelper::getValue($data, $task, 0, 'int');
        if (!is_array($cid) || count($cid) < 1)
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            Joomla\Utilities\ArrayHelper::toInteger($cid);


            // Remove the items.
            if ($model->setDefault($cid, $value))
            {
                if ($value == 1)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_DEFAULT';
                }
                else
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNDEFAULT';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
            else
            {
                $this->setMessage($model->getError());
            }
        }
        $this->setRedirect( 'index.php?option=com_eventgallery&view='.$this->default_view);

    }
}