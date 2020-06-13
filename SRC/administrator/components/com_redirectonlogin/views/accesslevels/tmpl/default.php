<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');

$j3br = ' ';
$j3reorder_width = '';
$j3reorder_header_width = '180';
if($this->helper->joomla_version >= '3.0'){
	$j3br = '<br />';
	$j3reorder_width = ' style="width: 200px;"';
	$j3reorder_header_width = '100';
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
<form action="<?php echo JRoute::_('index.php?option=com_redirectonlogin&view=accesslevels'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>			
		</div>
	<?php endif; ?>	
	<div id="j-main-container"<?php echo empty($this->sidebar) ? '' : ' class="span10"'; ?>>
		<h2 style="padding-left: 10px;"><?php echo JText::_('COM_REDIRECTONLOGIN_SUBTITLE_ACCESSLEVELS'); ?></h2>
		<?php
		if($this->controller->rol_config['frontend_u_or_a']=='u'){
			echo '<div class="rol_fontsize rol_warning rol_padleft">';
			echo JText::_('COM_REDIRECTONLOGIN_NOT_SET_TO_ACCESSLEVELS').' <a href="index.php?option=com_redirectonlogin&view=configuration&tab=frontend">'.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_CONFIGURATION')).'</a>.</div>';
		}
		?>
		<fieldset id="filter-bar">
			<?php	
			
			//search bar						
			$sortfields = JHtml::_('select.options', $this->getSortFields(), 'value', 'text', $listOrder);			
			echo $this->helper->search_toolbar(1, 1, 1, 1, $this->state->get('filter.search'), $sortfields, $listDirn, $this->pagination->getLimitBox());			
			
			?>			
		</fieldset>
		<div class="clr"> </div>
		<table class="adminlist table table-striped" width="100%">	
			<thead>
				<tr>				
					<th class="left" style="width: 30%; text-align: left;">					
						<?php 								
						echo JHtml::_('grid.sort', 'COM_REDIRECTONLOGIN_ACCESS_LEVEL_NAME', 'a.title', $listDirn, $listOrder); ?>
					</th>	
					<th class="left" style="white-space: nowrap;">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN');				
						echo JHtml::_('grid.sort',  $label, 't.frontend_type', $listDirn, $listOrder); 
						?>					
					</th>
					<th class="left" style="white-space: nowrap; padding-right: 20px;">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE');					
						echo JHtml::_('grid.sort',  $label, 't.opening_site', $listDirn, $listOrder); 
						?>					
					</th>
					<th class="left" style="white-space: nowrap;">
						<?php 
						$label = JText::_('COM_REDIRECTONLOGIN_FRONTEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT');
						echo JHtml::_('grid.sort', $label, 't.frontend_type_logout', $listDirn, $listOrder); 
						?>					
					</th>
					<th class="left" style="white-space: nowrap;">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_BACKEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGIN');				
						echo JHtml::_('grid.sort',  $label, 't.loginbackend_type', $listDirn, $listOrder); 
						?>					
					</th>
					<th class="left" style="white-space: nowrap;">
						<?php 	
						$label = JText::_('COM_REDIRECTONLOGIN_BACKEND').' '.JText::_('COM_REDIRECTONLOGIN_LOGOUT');				
						echo JHtml::_('grid.sort',  $label, 't.logoutbackend_type', $listDirn, $listOrder); 
						?>					
					</th>
					<th>
						<div style="width: <?php echo $j3reorder_header_width; ?>px; margin: 0 auto;">
							<?php 
							$label = JText::_('COM_REDIRECTONLOGIN_ORDERING').$j3br.JText::_('COM_REDIRECTONLOGIN_FRONTEND');
							echo JHtml::_('grid.sort',  $label, 'o.redirect_order', $listDirn, $listOrder); 
							?>					
							<a href="javascript:submitform('save_order_accesslevels');" class="saveorder" title="Save Order"><?php 
							if($this->helper->joomla_version >= '3.0'){
								echo '<img src="components/com_redirectonlogin/images/save.png" alt="save" />';
							}
							?></a>
						</div>
					</th>
					<th>					
						<div style="width: <?php echo $j3reorder_header_width; ?>px; margin: 0 auto;">
							<?php 
							$label = JText::_('COM_REDIRECTONLOGIN_ORDERING').$j3br.JText::_('COM_REDIRECTONLOGIN_BACKEND');						
							echo JHtml::_('grid.sort', $label, 'o.order_backend', $listDirn, $listOrder);
							?>				
							<a href="javascript:submitform('save_order_accesslevels_backend');" class="saveorder" title="Save Order"><?php 
							if($this->helper->joomla_version >= '3.0'){
								echo '<img src="components/com_redirectonlogin/images/save.png" alt="save" />';
							}
							?></a>
						</div>
					</th>
					<th width="5%">
						<?php 
						$label = JText::_('COM_REDIRECTONLOGIN_ID');
						echo JHtml::_('grid.sort',  $label, 'a.id', $listDirn, $listOrder); 
						?>
					</th>			
				</tr>
			</thead>		
			<tbody>
			<tr>
				<td>&nbsp;
				</td>
				<td>&nbsp;
				</td>
				<td>
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div style="color: red;" class="center">';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td>
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div style="color: red;" class="center">';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td>
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div style="color: red;" class="center">';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>	
				<td>
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div style="color: red;" class="center">';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td colspan="2" class="center"<?php echo $j3reorder_width; ?>>
					<p<?php echo $j3reorder_width; ?>>
					<?php echo JText::_('COM_REDIRECTONLOGIN_ACCESSLEVEL_ORDER_INFO_B'); ?> <a href="index.php?option=com_redirectonlogin&view=users"><?php echo JText::_('COM_REDIRECTONLOGIN_USERS'); ?></a>.
					</p>
					<?php
					if($this->controller->get_version_type()=='free'){
						echo '<div style="color: red;">';
						echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
						echo '</div>';
					}
					?>
				</td>
				<td>&nbsp;
				</td>
			</tr>
		<?php
		foreach ($this->items as $i => $item) :
		?>
		<tr class="row<?php echo $i % 2; ?>">
			<td>	
				<a href="index.php?option=com_redirectonlogin&view=accesslevel&group_id=<?php echo $item->id;?>">			
				<?php echo $item->title; ?>	
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
				echo $this->helper->redirect_type_list('normal', $item->loginbackend_type);
				?>			
			</td>
			<td class="center">	
				<?php 			
				echo $this->helper->redirect_type_list('normal', $item->logoutbackend_type);
				?>			
			</td>
			<td class="center">
			
				<?php 
				$group_order = '0';
				if($item->redirect_order){
					$group_order = $item->redirect_order; 
				}			
				?>			
				<input type="text" name="order[]" class="text-area-order rol_reorder" size="5" value="<?php echo $group_order; ?>" />	
				<input type="hidden" name="level_id[]" value="<?php echo $item->id; ?>" />	
				<input type="hidden" name="order_id[]" value="<?php echo $item->order_id; ?>" />		
			</td>
			<td class="center">
			
				<?php 
				$level_order_backend = '0';
				if($item->order_backend){
					$level_order_backend = $item->order_backend; 
				}			
				?>			
				<input type="text" name="order_backend[]" class="text-area-order rol_reorder" size="5" value="<?php echo $level_order_backend; ?>" />
			</td>
			<td class="center">
				<?php echo $item->id; ?>
			</td>
		</tr>
		<?php
		endforeach;
		?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="task" value="" />	
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
