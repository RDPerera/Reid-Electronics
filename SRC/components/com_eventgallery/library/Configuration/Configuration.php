<?php

namespace Joomla\Component\Eventgallery\Site\Library\Configuration;

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

abstract class Configuration
{
    /**
     * @var Main
     */
    private $parent;



    /**
     * Configuration constructor.
     * @param $configuration Main
     */
    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Registry
     */
    protected function getConfig() {
        return $this->parent->getConfiguration();
    }

    /**
     * @param $path string
     * @param $default mixed
     * @return mixed
     */
    public function get($path, $default) {
        return $this->getConfig()->get($path, $default);
    }


}