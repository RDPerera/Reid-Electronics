<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 * J'ai un seul form général. 
 *  - les params communs sont dans des onglets communs
 *  - l'onglet images est différent pour chacun
 *  - l'onglet speciphique est le meme avec uen liste de champs available venant du plugin ce qui détermie ce qu'il faut dessiner.
 */
defined('_JEXEC') or die;

if (version_compare(JVERSION, '3.2', '>=')){
	// version 3.2.x
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	JHtml::_('behavior.formvalidation');
	JHtml::_('formbehavior.chosen', 'select');

	$app = JFactory::getApplication();
	$config 	= JComponentHelper::getParams('com_geofactory');
	$basicMode 	= $config->get('isBasic');//basic=0
	$expert		= $basicMode==0?GeofactoryHelperAdm::getExpertMarkerset():array();
	$message	= $basicMode==0?JText::_('COM_GEOFACTORY_RUNNING_BASIC'):JText::_('COM_GEOFACTORY_RUNNING_EXPERT') ; 


	// http://docs.joomla.org/Display_error_messages_and_notices
	JFactory::getApplication()->enqueueMessage($message, 'message');

	// determine quel onglets il faut charger
	$fieldSetsUsed = array('general','markerset-template','markerset-settings','markerset-radius');
	if (empty($this->item->id)) {
		$fieldSetsUsed[] = 'markerset-type';
		$fieldSetsUsed[] = 'markerset-type-settings-info';
	} else {
		$fieldSetsUsed[] = 'markerset-type-hide';
		$fieldSetsUsed[] = 'markerset-icon';
		$fieldSetsUsed[] = 'markerset-type-settings';
	}
	?>

	<style>.CodeMirror{height:200px!important;}</style>
	<script type="text/javascript">
		Joomla.submitbutton = function(task){
			if (task == 'markerset.cancel' || document.formvalidator.isValid(document.id('markerset-form'))) {
				Joomla.submitform(task, document.getElementById('markerset-form'));
			}
		}
		jQuery(document).ready(function(){
		    var codeMirors = jQuery('.CodeMirror');
		    jQuery('a[href="#markerset-template"],a[href="#markerset-settings"],a[href="#markerset-type-settings"]').on('shown', function (e) {
		            codeMirors.each(function(i, el){
		                el.CodeMirror.refresh();
		            });
		    });
		 })
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_geofactory&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="markerset-form" class="form-validate">
		<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
		<div class="form-horizontal">
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

				<?php
				$fieldSets = $this->form->getFieldsets();
				foreach ($fieldSets as $name => $fieldSet) :
					if (!in_array($name, $fieldSetsUsed))
						continue ;
					?>

					<?php echo JHtml::_('bootstrap.addTab', 'myTab', $name, JText::_($fieldSet->label, true)); ?>
					<div class="row-fluid">
						<div class="span9">
							<?php echo $this->form->getControlGroups($name); ?>
						</div>
					</div>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
				<?php endforeach ; ?>
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		</div>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<?php

}else{
	// version 3.1.x
	JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	JHtml::_('formbehavior.chosen', 'select');

	$config 	= JComponentHelper::getParams('com_geofactory');
	$basicMode 	= $config->get('isBasic');//basic=0
	$expert		= $basicMode==0?GeofactoryHelperAdm::getExpertMarkerset():array();
	$message	= $basicMode==0?JText::_('COM_GEOFACTORY_RUNNING_BASIC'):JText::_('COM_GEOFACTORY_RUNNING_EXPERT') ; 
	$canDo		= GeofactoryHelperAdm::getActions();

	// http://docs.joomla.org/Display_error_messages_and_notices
	JFactory::getApplication()->enqueueMessage($message, 'message');

	// determine quel onglets il faut charger
	$fieldSetsUsed = array('base','general','markerset-template','markerset-settings','markerset-radius');
	if (empty($this->item->id)) {
		$fieldSetsUsed[] = 'markerset-type';
		$fieldSetsUsed[] = 'markerset-type-settings-info';
	} else {
		$fieldSetsUsed[] = 'markerset-type-hide';
		$fieldSetsUsed[] = 'markerset-icon';
		$fieldSetsUsed[] = 'markerset-type-settings';
	}
	?>
	<style>.CodeMirror{height:200px!important;}</style>
	<script type="text/javascript">
		Joomla.submitbutton = function(task){
			if (task == 'markerset.cancel' || document.formvalidator.isValid(document.id('markerset-form'))) {
				Joomla.submitform(task, document.getElementById('markerset-form'));
			}
		}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_geofactory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="markerset-form" class="form-validate form-horizontal">
		<!-- Begin Content -->
			<ul class="nav nav-tabs">
				<?php			
				$fieldSets = $this->form->getFieldsets();
				foreach ($fieldSets as $name => $fieldSet) :
					if (in_array($name, $fieldSetsUsed)) :
				?>
						<li <?php echo $name=="general"?' class="active"':"";?>">
							<a href="#<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label);?></a>
						</li>
					<?php endif ; ?>
				<?php endforeach ; ?>
			</ul>

			<div class="tab-content">
				<!-- Begin Tabs -->
				<?php			
				foreach ($fieldSetsUsed as $name) :
					$fieldSet = $this->form->getFieldsets($name);////////////// pas utilisé ici !!!!!!!!!!!!!!!!!
				?>
					<div class="tab-pane<?php echo $name=="general"?" active":"";?>" id="<?php echo $name;?>">
					<?php foreach ($this->form->getFieldset($name) as $field) : ?>
						<?php $display=''; if ($basicMode AND in_array($field->fieldname, $expert))	$display='style="display:none;"' ; /*au debut je les ignorai, mais la carte ne se charge pas completement*/?>
						<div class="control-group" <?php echo $display; ?>>
							<div class="control-label"><?php echo $field->label; ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endforeach; ?>
					</div>
				<?php endforeach; ?>

				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		<!-- End Content -->
	</form>
	<?php
}
?>
