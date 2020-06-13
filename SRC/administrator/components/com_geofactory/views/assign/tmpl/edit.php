<?php
/**
 * @name		Geocode Factory
 * @package		geoFactory
 * @copyright	Copyright © 2013 - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cédric Pelloquin aka Rick <info@myJoom.com>
 * @website		www.myJoom.com
 */
defined('_JEXEC') or die;

if (version_compare(JVERSION, '3.2', '>=')){
	// version 3.2.x
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	JHtml::_('behavior.formvalidation');
	JHtml::_('formbehavior.chosen', 'select');

	$app = JFactory::getApplication();

	// determine quel onglets il faut charger
	$fieldSetsUsed = array('general');
	if (empty($this->item->id)) {
		$fieldSetsUsed[] = 'assign-type';
	} else {
		$fieldSetsUsed[] = 'assign-type-hide';
		$fieldSetsUsed[] = 'assign-champs';
		$fieldSetsUsed[] = 'assign-address';
	}
	?>

	<script type="text/javascript">
		Joomla.submitbutton = function(task){
			if (task == 'assign.cancel' || document.formvalidator.isValid(document.id('assign-form'))) {
				Joomla.submitform(task, document.getElementById('assign-form'));
			}
		}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_geofactory&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="assign-form" class="form-validate">
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

	$canDo	= GeofactoryHelperAdm::getActions();

	// determine quel onglets il faut charger
	$fieldSetsUsed = array('base','general');
	if (empty($this->item->id)) {
		$fieldSetsUsed[] = 'assign-type';
	} else {
		$fieldSetsUsed[] = 'assign-type-hide';
		$fieldSetsUsed[] = 'assign-champs';
		$fieldSetsUsed[] = 'assign-address';
	}
	?>

	<script type="text/javascript">
		Joomla.submitbutton = function(task){
			if (task == 'assign.cancel' || document.formvalidator.isValid(document.id('assign-form'))) {
				Joomla.submitform(task, document.getElementById('assign-form'));
			}
		}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_geofactory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="assign-form" class="form-validate form-horizontal">
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
						<div class="control-group">
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
