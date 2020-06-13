<?php 

/**
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Session\Session as JSession;

JSession::checkToken() or die( 'Invalid Token' );

$kind_array = array(JHtml::_('select.option',JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_FILE'), JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_FILE')),
			JHtml::_('select.option',JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_FOLDER'), JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_FOLDER')));
$status_array = array(JHtml::_('select.option','0', JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_WRONG')),
			JHtml::_('select.option','1', JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_OK')),
			JHtml::_('select.option','2', JText::_('COM_SECURITYCHECK_FILEMANAGER_TITLE_EXCEPTIONS')));

// Add style declaration
$media_url = "media/com_securitycheck/stylesheets/cpanelui.css";
JHTML::stylesheet($media_url);

$bootstrap_css = "media/com_securitycheck/stylesheets/bootstrap.min.css";
JHTML::stylesheet($bootstrap_css);

?>

<?php if ( $this->database_error == "DATABASE_ERROR" ) { ?>
<div class="securitycheck-bootstrap">

<div class="alert alert-error">
	<?php echo JText::_('COM_SECURITYCHECK_FILEMANAGER_DATABASE_ERROR'); ?>
</div>
</div>
<?php } ?>

<?php if ( $this->files_with_incorrect_permissions >3000 ) { ?>
<div class="securitycheck-bootstrap">

<div class="alert alert-error">
	<?php echo JText::_('COM_SECURITYCHECK_FILEMANAGER_ALERT'); ?>
</div>
</div>
<?php } ?>

<?php if ( $this->show_all == 1 ) { ?>
<div class="securitycheck-bootstrap">

<div class="alert alert-info">
	<?php echo JText::_('COM_SECURITYCHECK_FILEMANAGER_INFO'); ?>
</div>
</div>
<?php } ?>

<form action="<?php echo JRoute::_('index.php?option=com_securitycheck&controller=filesstatus');?>" method="post" name="adminForm" id="adminForm">
<?php echo JHTML::_( 'form.token' ); ?>

<div id="filter-bar" class="btn-toolbar">
	<div class="filter-search btn-group pull-left">
		<input type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
	</div>
	<div class="btn-group pull-left">
		<button class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		<button class="btn tip" type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
	</div>
	
	<div class="btn-group pull-left" style="margin-left: 10px;">
			<select name="filter_filemanager_kind" class="custom-select" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_SECURITYCHECK_FILEMANAGER_KIND_DESCRIPTION');?></option>
				<?php echo JHtml::_('select.options', $kind_array, 'value', 'text', $this->state->get('filter.filemanager_kind'));?>
			</select>
			<select name="filter_filemanager_permissions_status" class="custom-select" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_SECURITYCHECK_FILEMANAGER_PERMISSIONS_STATUS_DESCRIPTION');?></option>
				<?php echo JHtml::_('select.options', $status_array, 'value', 'text', $this->state->get('filter.filemanager_permissions_status'));?>
			</select>
	</div>	
</div>


<div class="clearfix"> </div>

<div id="editcell">
<div class="accordion-group">
<table class="table table-striped">
<div style="font-weight:bold; font-size:10pt; text-align:center;"><?php echo JText::_( 'COM_SECURITYCHECK_COLOR_CODE' ); ?></div>
<thead>
	<tr>
		<td><span class="badge badge-success"> </span>
		</td>
		<td>
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_GREEN_COLOR' ); ?>
		</td>
		<td><span class="badge badge-warning"> </span>
		</td>
		<td>
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_YELLOW_COLOR' ); ?>
		</td>
		<td><span class="badge badge-danger"> </span>
		</td>
		<td>
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_RED_COLOR' ); ?>
		</td>
	</tr>
</thead>
</table>
</div>
</div>

<div>
	<span class="badge" style="background-color: #CEA0EA; padding: 10px 10px 10px 10px; float:right;"><?php echo JText::_('COM_SECURITYCHECK_FILEMANAGER_ANALYZED_FILES');?></span>
</div>

<table class="table table-bordered table-hover">
<thead>
	<tr>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_NAME' ); ?>
		</th>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_EXTENSION' ); ?>				
		</th>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_KIND' ); ?>				
		</th>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_RUTA' ); ?>
		</th>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_TAMANNO' ); ?>
		</th>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_PERMISSIONS' ); ?>
		</th>
		<th class="filesstatus-table">
			<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_LAST_MODIFIED' ); ?>
		</th>		
	</tr>
</thead>
<?php
if ( !empty($this->items) ) {	
	foreach ($this->items as &$row) {		
?>
	<td align="center">
		<?php echo $row['name']; ?>
	</td>
	<td align="center">
		<?php echo $row['extension']; ?>
	</td>
	<td align="center">
		<?php echo $row['kind']; ?>
	</td>
	<td align="center">
		<?php echo $row['path']; ?>
	</td>
	<td align="center">
		<?php echo $row['size']; ?>
	</td>
	<?php 
		$safe = $row['safe'];
		if ( $safe == '0' ) {
			echo "<td><span class=\"badge badge-danger\">";
		} else if ( $safe == '1' ) {
			echo "<td><span class=\"badge badge-success\">";
		} else if ( $safe == '2' ) {
			echo "<td><span class=\"badge badge-warning\">";
		} ?>
		<?php echo $row['permissions']; ?>
	</td>
	<td align="center">
		<?php echo $row['last_modified']; ?>
	</td>
</tr>
<?php
	}
}
?>
</table>
</div>

<?php
if ( !empty($this->items) ) {	
?>
<div class="margen">
	<?php echo $this->pagination->getListFooter(); ?>
</div>
<?php
}
?>

<input type="hidden" name="option" value="com_securitycheck" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="1" />
</form>