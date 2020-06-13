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

// The class name must always be the same as the filename (in camel case)
class JFormFieldImagetypegroups extends JFormField
{

    //The field class must know its own type through the variable $type.
    protected $type = 'imagetypegroups';


    public function getInput()
    {
        /**
         * @var EventgalleryLibraryFactoryImagetypegroup $imagetypegroupFactory
         */
        $imagetypegroupFactory = EventgalleryLibraryFactoryImagetypegroup::getInstance();
        $imagetypegroups = $imagetypegroupFactory->getImageTypeGroups(false);

        $id = $this->form->getField('id')->value;

        /**
         * @var EventgalleryLibraryImagetypegroup $imagetypegroup
         */

        $return  = '<select name="'.$this->name.'" id="'.$this->id.'">';
        $return .= '<option value=""></option>';
        foreach($imagetypegroups as $imagetypegroup) {

            $selected = $imagetypegroup->getId() == $this->value ? 'selected="selected"' : '';

            $return .= '<option '. $selected .'value="'.$imagetypegroup->getId().'">'.$imagetypegroup->getName().'</option>';
        }
        $return .= "</select>";

        return $return;

    }
}
