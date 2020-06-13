<?php
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

class plgContentEventgallery_fields_category extends JPlugin {

    /**
     * Load the language file on instantiation.
     * Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var boolean
     * @since 3.1
     */

    protected $autoloadLanguage = true;

    function onContentPrepareForm($form, $data) {

        $app = JFactory::getApplication();
        $option = $app->input->get('option');

        switch($option) {

            case 'com_categories':
                if ($app->isAdmin()) {


                    JForm::addFormPath(__DIR__ . '/forms');

                    /**
                     * @var JForm $form
                     */

                    $form->loadFile('content', false);

                }

                return true;

        }

        return true;

    }

}
