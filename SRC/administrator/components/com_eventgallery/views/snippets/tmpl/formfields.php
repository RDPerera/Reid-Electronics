<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

?>

<div class="adminform form-horizontal">
    <fieldset>

        <?php IF (count($this->form->getFieldsets())>1): ?>


            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
            <?php foreach($this->form->getFieldsets() as $fieldset): ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', $fieldset->name, JText::_($fieldset->label, true)); ?>
                <?php IF (strlen(JText::_($fieldset->description))>0): ?>
                    <div><?php echo JText::_($fieldset->description); ?></div>
                    <hr>
                <?php ENDIF ?>
                <?php foreach ($this->form->getFieldset($fieldset->name) as $field): ?>
                    <?php $dataShowOn = ''; ?>
                    <?php if ($field->showon) : ?>
                        <?php JHtml::_('jquery.framework'); ?>
                        <?php JHtml::_('script', 'jui/cms.js', array('version' => 'auto', 'relative' => true)); ?>
                        <?php $dataShowOn = ' data-showon=\'' . json_encode(JFormHelper::parseShowOnConditions($field->showon, $field->formControl, $field->group)) . '\''; ?>
                    <?php endif; ?>
                    <div class="control-group" <?php echo $dataShowOn; ?>>
                        <?php if (!$field->hidden): ?>
                            <div class="control-label"><?php echo $field->label; ?></div>
                        <?php endif; ?>
                        <div class="controls">
                            <?php echo $field->input; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php endforeach ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>


        <?php ELSE: ?>


            <?php
                $fieldsets =  array_values($this->form->getFieldsets());
                $fieldset= $fieldsets[0]; ?>
            <?php IF (strlen(JText::_($fieldset->label))>0): ?><legend><?php echo JText::_($fieldset->label); ?></legend><?php ENDIF; ?>
                <?php IF (strlen(JText::_($fieldset->description))>0): ?>
                <div><?php echo JText::_($fieldset->description); ?></div>
                <hr>
            <?php ENDIF ?>
            <?php foreach ($this->form->getFieldset($fieldset->name) as $field): ?>
                <?php $dataShowOn = ''; ?>
                <?php if ($field->showon) : ?>
                    <?php JHtml::_('jquery.framework'); ?>
                    <?php JHtml::_('script', 'jui/cms.js', array('version' => 'auto', 'relative' => true)); ?>
                    <?php $dataShowOn = ' data-showon=\'' . json_encode(JFormHelper::parseShowOnConditions($field->showon, $field->formControl, $field->group)) . '\''; ?>
                <?php endif; ?>
                <div class="control-group" <?php echo $dataShowOn; ?>>

                <?php if (!$field->hidden): ?>
                        <div class="control-label"><?php echo $field->label; ?></div>
                    <?php endif; ?>
                    <div class="controls">
                        <?php echo $field->input; ?>
                    </div>
                </div>
            <?php endforeach; ?>


        <?php ENDIF ?>
    </fieldset>
</div>
