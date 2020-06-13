<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

/**
 * Eventgallery Multi Language plugin
 *
 * We need this plugin to transform JSON data into something readable. This might happen if we render list of tags. Since
 * Event Gallery stores information encoded in JSON we need to trigger a transformation here.
 *
 */
class PlgContentEventgallery_multilangcontent extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;
    protected $entries;

    /**
     * defines how to display the images
     *
     * @var string
     */
    protected $mode;


    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        try {
            @include_once JPATH_ROOT . '/components/com_eventgallery/vendor/autoload.php';
            //load classes
            JLoader::registerPrefix('Eventgallery', JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery');
        }catch (Exception $e){

        }
    }

    /**
     * Plugin that adds a pagebreak into the text and truncates text at that point
     *
     * @param   string   $context  The context of the content being passed to the plugin.
     * @param   object   &$row     The article object.  Note $article->text is also available
     * @param   mixed    &$params  The article params
     * @param   integer  $page     The 'page' number
     *
     * @return  mixed  Always returns void or true
     *
     * @since   1.6
     */
    public function onContentPrepare($context, &$row, &$params, /** @noinspection PhpUnusedParameterInspection */ $page = 0)
    {
        $canProceed = in_array($context, array('com_tags.tag', 'com_search.search') );

        if (!$canProceed)
        {
            return;
        }

        if (isset($row->core_title)) {
            $title = new EventgalleryLibraryDatabaseLocalizablestring($row->core_title);
            $row->core_title = $title->get();
        }

        if (isset($row->core_body)) {
            $body= new EventgalleryLibraryDatabaseLocalizablestring($row->core_body);
            $row->core_body = $body->get();
        }

        if (isset($row->text)) {
            $text= new EventgalleryLibraryDatabaseLocalizablestring($row->text);
            $row->text = $text->get();
        }
    }

}
