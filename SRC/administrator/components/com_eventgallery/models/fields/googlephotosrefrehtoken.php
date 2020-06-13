<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
require_once JPATH_ADMINISTRATOR . '/components/com_eventgallery/helpers/backendmedialoader.php';

// The class name must always be the same as the filename (in camel case)
class JFormFieldGooglephotosrefrehtoken extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'googlephotosrefrehtoken';


    public function getInput()
    {

        EventgalleryHelpersBackendmedialoader::load();

        $id = $this->form->getValue("id");
        $clientid = $this->form->getValue("clientid");
        $secret = $this->form->getValue("secret");

        $return = Array();

        if (empty($clientid) or empty($secret)) {
            $return[] = JText::_('COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_REFRESHTOKEN_CLIENT_CREDENTIALS_MISSING_LABEL') ;
        } else {
            $return[] = '<input class="google-photos-api-oauth-input" name="' . $this->name . '" value="' . $this->value . '" id="' . $this->id . '"/>&nbsp';
            $return[] = '<button class="google-photos-api-oauth-trigger-button btn active btn-success" data-id="'.$id.'" id="' . $this->id . '-button">' . JText::_('COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNT_REFRESHTOKEN_BUTTON_LABEL') . '</botton>';
        }
        return implode('', $return);
    }

}