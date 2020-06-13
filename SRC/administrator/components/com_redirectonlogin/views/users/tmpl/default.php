<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// No direct access.
defined('_JEXEC') or die;

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$j3br = ' ';
$j3reorder_width = '';
if($this->helper->joomla_version >= '3.0'){
	$j3br = '<br />';
	$j3reorder_width = ' style="width: 100px;"';	
}

?>
<script language="JavaScript" type="text/javascript">

Joomla.orderTable = function(){
	if(document.getElementById("sortTable")){
		sort_table = document.getElementById("sortTable").value;
	}else{
		sort_table = document.adminForm.filter_order.value;
	}
	if(document.getElementById("directionTable")){
		direction_table = document.getElementById("directionTable").value;
	}else{
		direction_table = document.adminForm.filter_order_Dir.value;
	}	
	Joomla.tableOrdering(sort_table, direction_table, '');	
}

</script>

<form action="<?php echo JRoute::_('index.php?option=com_redirectonlogin&view=users');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>			
		</div>
	<?php endif; ?>	
	<div id="j-main-container"<?php echo empty($this->sidebar) ? '' : ' class="span10"'; ?>>
		<h2 style="padding-left: 10px;"><?php echo JText::_('COM_REDIRECTONLOGIN_SUBTITLE_USERS'); ?></h2>	
		<fieldset id="filter-bar">
			<?php	
			
			//search bar						
			$sortfields = JHtml::_('select.options', $this->getSortFields(), 'value', 'text', $listOrder);			
			echo $this->helper->search_toolbar(1, 1, 1, 1, $this->state->get('filter.search'), $sortfields, $listDirn, $this->pagination->getLimitBox());			
			
			if($this->helper->joomla_version < '3.0'){
			?>	
			<div class="filter-select fltrt">
				<select name="filter_group_id" class="inputbox" onchange="this.form.submit()">
					<option value=""> - <?php echo JText::_('COM_REDIRECTONLOGIN_USERGROUPS');?> - </option>
					<?php echo JHtml::_('select.options', $this->get_groups(), 'value', 'text', $this->state->get('filter.group_id'));?>
				</select>
				<select name="filter_level_id" class="inputbox" onchange="this.form.submit()">
					<option value=""> - <?php echo JText::_('COM_REDIRECTONLOGIN_ACCESSLEVELS');?> - </option>
					<?php echo JHtml::_('select.options', $this->get_levels(), 'value', 'text', $this->state->get('filter.level_id'));?>
				</select>
			</div>
			<?php
			}
			?>
		</fieldset>
		<div class="clr"> </div>	
		<table class="adminlist table table-striped" width="100%">
			<thead>
				<tr>				
					<th class="left">
						<?php echo JHtml::_('grid.sort', 'COM_REDIRECTONLOGIN_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th class="nowrap" width="">
						<?php echo JHtml::_('grid.sort', 'COM_REDIRECTONLOGIN_USERNAME', 'a.username', $listDirn, $listOrder); ?>
					</th>
					<th class="nowrap" style="white-space: nowrap; padding-right: 20px;">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN');				
						echo JHtml::_('grid.sort', $label, 't.frontend_type', $listDirn, $listOrder); 					
						?>			
					</th>	
					<th class="nowrap" style="white-space: nowrap; padding-right: 20px;">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE');			
						echo JHtml::_('grid.sort', $label, 't.opening_site', $listDirn, $listOrder);  					
						?>			
					</th>
					<th class="nowrap" style="white-space: nowrap; padding-right: 20px;">
						<?php 	
						$label =  JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT');				
						echo JHtml::_('grid.sort', $label, 't.frontend_type_logout', $listDirn, $listOrder); 					
						?>			
					</th>
					<th class="nowrap">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_BACKEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN');				
						echo JHtml::_('grid.sort',  $label, 't.backend_type', $listDirn, $listOrder); 					
						?>					
					</th>	
					<th class="nowrap">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_BACKEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT');				
						echo JHtml::_('grid.sort',  $label, 't.logoutbackend_type', $listDirn, $listOrder); 					
						?>					
					</th>					
					<th class="nowrap" colspan="2">
						<?php echo ucfirst(JText::_('COM_REDIRECTONLOGIN_USERGROUPS')); ?>					
					</th>				
					<th class="nowrap" colspan="2">
						<?php echo ucfirst(JText::_('COM_REDIRECTONLOGIN_ACCESSLEVELS')); ?>
					</th>								
					<th class="nowrap" width="3%">
						<?php echo JHtml::_('grid.sort', 'COM_REDIRECTONLOGIN_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>			
			</thead>		
			<tbody>
			<tr>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td class="center rol_warning">
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div>';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td class="center rol_warning">
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div>';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td class="center rol_warning">
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div>';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td class="center rol_warning">
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div>';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td class="center rol_warning">
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div>';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td class="center"><?php echo JText::_('COM_REDIRECTONLOGIN_ORDER_FRONT'); ?>
				</td>
				<td class="center"><?php echo JText::_('COM_REDIRECTONLOGIN_ORDER_BACK'); ?>
				</td>
				<td class="center"><?php echo JText::_('COM_REDIRECTONLOGIN_ORDER_FRONT'); ?>
				</td>
				<td class="center"><?php echo JText::_('COM_REDIRECTONLOGIN_ORDER_BACK'); ?>
				</td>				
				<td>&nbsp;
				</td>
			</tr>
			<tr>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>				
				<td colspan="2" class="center">
					<p>
						<?php echo JText::_('COM_REDIRECTONLOGIN_ONLY_FIRST_GROUP'); ?>
						<br />
						<a href="index.php?option=com_redirectonlogin&view=usergroups">
						<?php echo $this->controller->rol_strtolower(JText::_('JACTION_EDIT')).' '.$this->controller->rol_strtolower(JText::_('JGLOBAL_FIELD_FIELD_ORDERING_LABEL')); ?>
						</a>
					</p>
				</td>
				<td colspan="2" class="center">
					<p>
						<?php echo JText::_('COM_REDIRECTONLOGIN_ONLY_FIRST_LEVEL'); ?>
						<br />
						<a href="index.php?option=com_redirectonlogin&view=accesslevels">
						<?php echo $this->controller->rol_strtolower(JText::_('JACTION_EDIT')).' '.$this->controller->rol_strtolower(JText::_('JGLOBAL_FIELD_FIELD_ORDERING_LABEL')); ?>
						</a>
					</p>
				</td>
				<td>&nbsp;
				</td>
			</tr>
			<?php 
			//print_r($this->items);
			foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo ($i+1) % 2; ?>">				
					<td>
						<?php echo $this->escape($item->name); ?>
					</td>
					<td class="center">
						<a href="index.php?option=com_redirectonlogin&view=user&user_id=<?php echo $item->id;?>">
						<?php echo $this->escape($item->username); ?>
						</a>
					</td>
					<td class="center">
						<?php 					
						echo $this->helper->redirect_type_list('normal', $item->frontend_type);
						?>					
					</td>
					<td class="center">					
						<?php
						echo $this->helper->redirect_type_list_yes('normal', $item->opening_site, $item->open_type);				
						?>					
					</td>
					<td class="center">					
						<?php 					
						echo $this->helper->redirect_type_list('normal', $item->frontend_type_logout);
						?>					
					</td>
					<td class="center">					
						<?php 					
						echo $this->helper->redirect_type_list('normal', $item->backend_type);			
						?>					
					</td>
					<td class="center">					
						<?php 					
						echo $this->helper->redirect_type_list('normal', $item->logoutbackend_type);			
						?>					
					</td>					
					<td class="center">
						<?php 
						//echo nl2br($item->group_names); 					
						//$group_ids_array = explode('-', $item->group_ids);
						$group_ids_array = $this->get_users_groups($item->id);					
						foreach($this->groups_title_order_front as $temp){
							if(in_array($temp[0], $group_ids_array)){
								echo $temp[1];
								echo '<br />';
							}
						}
						?>
					</td>
					<td class="center">
						<?php 									
						foreach($this->groups_title_order_back as $temp){
							if(in_array($temp[0], $group_ids_array)){
								echo $temp[1];
								echo '<br />';
							}
						}
						?>
					</td>
					<td class="center">
						<?php 
						
						$levels_ids_array = $this->get_groups_levels($group_ids_array);
							
						foreach($this->levels_title_order as $temp){
							if(in_array($temp->level_id, $levels_ids_array)){
								echo $temp->level_title;
								echo '<br />';
							}
						}
						
						?>
					</td>	
					<td class="center">
						<?php 					
												
						foreach($this->levels_title_order_backend as $temp){
							if(in_array($temp->level_id, $levels_ids_array)){
								echo $temp->level_title;
								echo '<br />';
							}
						}
						
						?>
					</td>							
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="12">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>	
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>