<?php
/**
 * @package Package iSMS for Joomla! 3.3
 * @author Mobiweb
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// Load tooltip behavior
JHtml::_('behavior.tooltip');

// Include the HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "isms.cancel" || document.formvalidator.isValid(document.id("isms-form")))
		{
			Joomla.submitform(task, document.getElementById("isms-form"));
		}
	}
');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>
<form action="<?php echo JRoute::_('index.php?option=com_smsnotification'); ?>" method="post" name="adminForm" id="isms-form" class="form-validate">
	
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	
	<div class="form-horizontal">
		<div class="control-group">
			<div class="control-label">
				<?php echo JText::_('COM_ISMS_FIELD_BALANCE_LABEL');?>
			</div>
			<div class="controls">
				<?php echo $this->balance; ?>
			</div>
		</div>
		<?php
		foreach ($fieldsets as $fieldset) {
			echo $this->form->getControlGroups($fieldset->name);
		}
		?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
