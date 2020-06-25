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

/**
 * Main entry to the configuration management.
 *
 * Class Main
 * @package Joomla\Component\Eventgallery\Site\Library\Configuration
 */
class Main
{
    /**
     * @var Registry
     */
    private $configuration;

    /**
     * @var General
     */
    private $general;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var Categories
     */
    private $categories;

    /**
     * @var Checkout
     */
    private $checkout;

    /**
     * @var Event
     */
    private $event;
    /**
     * @var EventAjax
     */
    private $eventAjax;

    /**
     * @var EventsImagelist
     */
    private $eventsImagelist;

    /**
     * @var EventImagelist
     */
    private $eventImagelist;

    /**
     * @var EventPageable
     */
    private $eventPageable;

    /**
     * @var EventsList
     */
    private $eventsList;

    /**
     * @var Image
     */
    private $image;

    /**
     * @var Lightbox
     */
    private $lightbox;

    /**
     * @var Social
     */
    private $social;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var MenuItem
     */
    private $menuitem;

    /**
     * @var Slider
     */
    private $slider;

    private static $instances = [];

    /**
     * @param $configurationOverride Registry
     * @return Main
     */
    public static function getInstance($configurationOverride = null) {
        $hash = $configurationOverride == null?'':  md5(json_encode($configurationOverride));
        if (!array_key_exists($hash, self::$instances)) {
            self::$instances[$hash] = new Main($configurationOverride);
        }
        return self::$instances[$hash];
    }
    /**
     * Main constructor.
     *
     * @param $configurationOverride Registry
     */
    private function __construct($configurationOverride = null)
    {
        $newConfiguration = new Registry();
        $newConfiguration->merge(\JComponentHelper::getParams('com_eventgallery'));
        if ($configurationOverride !== null && is_object($configurationOverride)) {
            $newConfiguration->merge($configurationOverride, true);
        }
        $this->setConfiguration($newConfiguration);
    }

    public function setConfiguration($configuration) {
        $this->configuration = $configuration;
        $this->initSubConfigurations();
    }

    public function set($path, $value) {
        $this->configuration->set($path, $value);
    }

    /**
     * Used to map some old / non configuration values.
     *
     * @param $path string
     * @param $default mixed
     * @return mixed
     */
    public function getLegacy($path, $default = null) {
        return $this->configuration->get($path, $default);
    }
    /**
     * Initializes the sub configurations
     */
    private function initSubConfigurations() {
        $this->general = new General($this);
        $this->cart = new Cart($this);
        $this->categories = new Categories($this);
        $this->checkout = new Checkout($this);
        $this->event = new Event($this);
        $this->eventAjax = new EventAjax($this);
        $this->eventsImagelist = new EventsImagelist($this);
        $this->eventImagelist = new EventImagelist($this);
        $this->eventPageable = new EventPageable($this);
        $this->eventsList = new EventsList($this);
        $this->general = new General($this);
        $this->image = new Image($this);
        $this->lightbox = new Lightbox($this);
        $this->social = new Social($this);
        $this->storage = new Storage($this);
        $this->menuitem = new MenuItem($this);
        $this->slider = new Slider($this);
    }

    /**
     * @return Registry
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    /**
     * @return General
     */
    public function getGeneral() {
        return $this->general;
    }

    /**
     * @return Cart
     */
    public function getCart () {
        return $this->cart;
    }

    /**
     * @return Categories
     */
    public function getCategories () {
        return $this->categories;
    }

    /**
     * @return Checkout
     */
    public function getCheckout () {
        return $this->checkout;
    }

    /**
     * @return Event
     */
    public function getEvent () {
        return $this->event;
    }

    /**
     * @return EventAjax
     */
    public function getEventAjax () {
        return $this->eventAjax;
    }

    /**
     * @return EventsImagelist
     */
    public function getEventsImagelist () {
        return $this->eventsImagelist;
    }

    /**
     * @return EventImagelist
     */
    public function getEventImagelist () {
        return $this->eventImagelist;
    }

    /**
     * @return EventPageable
     */
    public function getEventPageable () {
        return $this->eventPageable;
    }

    /**
     * @return EventsList
     */
    public function getEventsList () {
        return $this->eventsList;
    }

    /**
     * @return Image
     */
    public function getImage () {
        return $this->image;
    }

    /**
     * @return Lightbox
     */
    public function getLightbox () {
        return $this->lightbox;
    }

    /**
     * @return Social
     */
    public function getSocial () {
        return $this->social;
    }

    /**
     * @return Storage
     */
    public function getStorage () {
        return $this->storage;

    }

    /**
     * @return MenuItem
     */
    public function getMenuItem() {
        return $this->menuitem;
    }

    /**
     * @return Slider
     */
    public function getSlider() {
        return $this->slider;
    }
}
