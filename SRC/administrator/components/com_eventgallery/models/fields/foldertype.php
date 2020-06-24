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
class JFormFieldFoldertype extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'foldertype';


    public function getInput()
    {

        EventgalleryHelpersBackendmedialoader::load();

        /**
         * @var EventgalleryLibraryFactoryFoldertype $foldertypeFactory
         */
        $foldertypeFactory = EventgalleryLibraryFactoryFoldertype::getInstance();

        $foldertypes = $foldertypeFactory->getFolderTypes(true);

        if ($this->value == null  && $foldertypeFactory->getDefaultFolderType(false) != null) {
            $this->value = $foldertypeFactory->getDefaultFolderType(false)->getId();
        }

        $return = Array();

        $onchange = "Eventgallery.Tools.setCSSStyle(document.getElementsByClassName('foldertype-input'),'display', 'none'); Eventgallery.Tools.setCSSStyle(document.getElementsByClassName('foldertype-' + this.options[this.selectedIndex].value), 'display', 'block');";

        $return[]  = '<select onchange="'. $onchange .'" name="'.$this->name.'" id="'.$this->id.'">';
        foreach($foldertypes as $foldertype) {
            /**
             * @var EventgalleryLibraryFoldertype $foldertype
             */

            $this->value==$foldertype->getId()?$selected='selected="selected"':$selected ='';

            $return[] = '<option '.$selected.' value="'.$foldertype->getId().'">'.$foldertype->getDisplayName().'</option>';
        }
        $return[] = "</select>";

        $return[] = $this->getLocalInput();

        $return[] = $this->getPicasaInput();

        $return[] = $this->getFlickrInput();

        $return[] = $this->getS3Input();

        $return[] = $this->getGooglePhotosInput();

        $currentFolderType = 0;
        if (isset($this->value) ) {
            $currentFolderType = $this->value;
        }

        $return[] = "<script>document.addEventListener('DOMContentLoaded', (event) => {Eventgallery.Tools.setCSSStyle(document.getElementsByClassName('foldertype-input'), 'display', 'none'); Eventgallery.Tools.setCSSStyle(document.getElementsByClassName('foldertype-$currentFolderType'), 'display', 'block');});</script>";

        return implode('', $return);
    }

    protected function getLocalInput() {
        $result = Array();
        $value = $this->form->getValue("folder");
        $result[] = '<div class="foldertype-0 foldertype-input">';
        $result[] = "<br>Folder Name<br>";
        $result[] = '<input onchange="document.getElementById(\'jform_folder\').value=this.value" type="text" id="foldertype-0-foldername" value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'" />';
        $result[] = '</div>';

        return implode('', $result);
    }

    protected function getFlickrInput() {
        $result = Array();
        $value = $this->form->getValue("folder");
        $result[] = '<div class="foldertype-2 foldertype-input">';
        $result[] = "<br>Flickr Photo Set ID<br>";
        $result[] = '<input onchange="document.getElementById(\'jform_folder\').value=this.value" type="text" id="foldertype-2-photosetid" value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'" />';
        $result[] = '</div>';
        return implode('', $result);
    }


    protected function getPicasaInput() {

        $result = Array();
        $value = $this->form->getValue("folder");
        $picasakey = $this->form->getValue("picasakey");

        $temp = explode(EventgalleryLibraryFolderPicasa::PICASA_FOLDERID_DELIMITER, $value);

        $user = implode(EventgalleryLibraryFolderPicasa::PICASA_FOLDERID_DELIMITER, array_slice($temp, 0, count($temp)-1) );

        $album = "";
        if (count($temp) > 1) {
            $album = implode(EventgalleryLibraryFolderPicasa::PICASA_FOLDERID_DELIMITER, array_slice($temp, count($temp) - 1, 1));
        }

        $onchange = "document.getElementById('jform_folder').value = document.getElementById('foldertype-1-user').value + '@' + document.getElementById('foldertype-1-album').value;";
        $result[] = '<div class="foldertype-1 foldertype-input">';
        $result[] = "<br>User<br>";
        $result[] = '<input onchange="'. $onchange. '" type="text" id="foldertype-1-user" value="'.$user.'" />';
        $result[] = "<br>Album<br>";
        $result[] = '<input onchange="'. $onchange. '" type="text" id="foldertype-1-album" value="'.$album.'" />';
        $result[] = "<br>Picasa Key<br>";
        $result[] = '<input onchange="document.getElementById(\'jform_picasakey\').value=this.value" type="text" id="foldertype-1-picasakey" value="'.$picasakey.'" />';
        $result[] = "<br>";
        $result[] = "<br>";


        $result[] = JHtml::_(
                    'bootstrap.renderModal',
                    'album-selector-modal',
                    array(
                        'title'       => JText::_('COM_EVENTGALLERY_EVENT_PICASA_ABLUM_SELECTOR'),
                        'url'         => '//static.svenbluege.de/picasa/v1.4/index.html',
                        'bodyHeight'  => '80',
                        'modalWidth'  => '80',
                    )
                );

        $result[] = '<button data-toggle="modal" data-target="#album-selector-modal" class="btn">' .JText::_( 'COM_EVENTGALLERY_EVENT_PICASA_ABLUM_SELECTOR' ) . '</button>';
        $result[] = '<div id="foldertype-1-albumselectoriframe" ></div>';
        $result[] = "<br>";
        $result[] = '</div>';


        return implode('', $result);


    }

    protected function getS3Input() {
        $result = Array();
        $value = $this->form->getValue("folder");
        $result[] = '<div class="foldertype-3 foldertype-input">';
        $result[] = "<br>Key<br>";
        $result[] = '<input onchange="document.getElementById(\'jform_folder\').value=this.value" type="text" id="foldertype-3-foldername" value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'" />';
        $result[] = '</div>';

        return implode('', $result);
    }

    protected function getGooglePhotosInput() {

        $result = Array();
        $value = $this->form->getValue("folder");
        $accountid = $this->form->getValue("googlephotosaccountid");
        $album = $value;
        $title = $this->form->getValue("googlephotostitle");

        /**
         * @var EventgalleryLibraryFactoryGooglephotosaccount $accountFactory
         *
         */
        $accountFactory = EventgalleryLibraryFactoryGooglephotosaccount::getInstance();
        $accounts = $accountFactory->getUsableGooglePhotosAccounts();

        $onchange = "document.getElementById('jform_folder').value = document.getElementById('foldertype-4-album').value;";
        $onchange.= "document.getElementById('jform_googlephotosaccountid').value=document.getElementById('foldertype_4_account').options[document.getElementById('foldertype_4_account').selectedIndex].value;";
        $onchange.= "document.getElementById('jform_googlephotostitle').value=document.getElementById('foldertype-4-title').value;";

        $result[] = '<div class="foldertype-4 foldertype-input">';

        if (count($accounts) == 0) {
            $result[] = '<br><br><strong>' . JText::_('COM_EVENTGALLERY_OPTIONS_COMMON_GOOGLE_PHOTOS_API_WARNING') . '</strong>';
            $result[] = '<br><br><a href="'. JRoute::_('index.php?option=com_eventgallery&view=googlephotosaccounts') .'">'.JText::_('COM_EVENTGALLERY_GOOGLEPHOTOSACCOUNTS').'</a>';
        } else {

            $result[] = "<br><label for='foldertype_4_account'>".JText::_('COM_EVENTGALLERY_FIELD_FOLDERTYPE_GOOGLEPHOTOS_ACCOUNT')."</label><br>";
            $result[] = '<select id="foldertype_4_account" name="foldertype_4_account" onchange="' . $onchange . '">';
            foreach($accounts as $account) {
                /**
                 * @var EventgalleryLibraryGooglephotosaccount $account
                 */

                 $result[] = '<option '. ($accountid == $account->getId()?'selected':'') .' value="'.$account->getId().'">'.$account->getName().'</option>';

            }
            $result[] = "</select>";
            $result[] = "<br><br>".JText::_('COM_EVENTGALLERY_FIELD_FOLDERTYPE_GOOGLEPHOTOS_ALBUM')."<br>";
            $result[] = '<input onchange="' . $onchange . '" type="text" id="foldertype-4-album" value="' . $album . '" />';
            $result[] = "<br>";
            $result[] = "<br>";
            $result[] = JText::_('COM_EVENTGALLERY_FIELD_FOLDERTYPE_GOOGLEPHOTOS_ALBUMNAME')."<br>";
            $result[] = '<input disabled="true" onchange="' . $onchange . '"type="text" id="foldertype-4-title" value="' . $title . '" />';
            $result[] = "<br>";
            $result[] = "<br>";



            $result[] = JHtml::_(
                'bootstrap.renderModal',
                'google-photos-album-selector-modal',
                array(
                    'title' => JText::_('COM_EVENTGALLERY_EVENT_PICASA_ABLUM_SELECTOR'),
                    'url' => JRoute::_('index.php?option=com_eventgallery&view=googlephotos&layout=albumselector&tmpl=component'),
                    'bodyHeight' => '80',
                    'modalWidth' => '80',
                )
            );

            $result[] = '<button data-toggle="modal" data-target="#google-photos-album-selector-modal" class="btn">' . JText::_('COM_EVENTGALLERY_EVENT_PICASA_ABLUM_SELECTOR') . '</button>';
            $result[] = '<div id="foldertype-4-albumselectoriframe" ></div>';
        }
            $result[] = "<br>";
            $result[] = '</div>';


        return implode('', $result);


    }


}
