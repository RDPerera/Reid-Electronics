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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal', 'a.modal');

$config 		= JComponentHelper::getParams('com_geofactory');
$showMinimap 	= trim($config->get('showMinimap'));
$ggApikey 		= trim($config->get('ggApikeySt'));
$ggApikey 		= (strlen($ggApikey)>4)?"&key=".$ggApikey:'';
$geocoded		= $this->escape($this->state->get('filter.geocoded'));
$http 			= $config->get('sslSite');
if(empty($http))$http = (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS']))?"https://":"http://" ; 

?>

<script type="text/javascript">
function loadAddress(id, type, gglink){
	// prépare les données à envoyer
	var arData = {} ;
	arData['idCur'] 	= id ;
	arData['type']		= type ;
	arData['assign'] 	= jQuery('#assign').val() ;
	arData['gglink'] 	= gglink ;
	
	jQuery.ajax({
		url: 'index.php?option=com_geofactory&task=geocodes.getaddress',
		data: arData,
		onLoading: jQuery('#address_'+id).html('...loading...'),
		success:function(data){
			if (gglink)	{jQuery('#gglink_'+id).html(data);}
			else 		{jQuery('#address_'+id).html(data);}
		}
	})
}
function geocodeItem(id,type){
	// prépare les données à envoyer
	var ulrAx			= 'index.php?option=com_geofactory&task=geocodes.geocodeuniqueitem' ;
	var arData 			= {} ;
	arData['cur'] 		= id;
	arData['type'] 		= type ;
	arData['assign'] 	= jQuery('#assign').val() ;

	// update le current dans le form
	jQuery.ajax({
		url: ulrAx,
		data: arData,
		onLoading: jQuery('#gglink_'+id).html('...geocoding...'), 
		success:function(data){
			jQuery('#gglink_'+id).html(data);
			jQuery('#imagemap').hide();
		}
	})
}

</script>
<form action="<?php echo JRoute::_('index.php?option=com_geofactory&view=geocodes'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container_mj" class="span2">
      <?php echo $this->sidebar; ?> 
    </div>  
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif;?>

		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_GEOFACTORY_SEARCH_IN_TITLE');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_GEOFACTORY_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_GEOFACTORY_SEARCH_IN_TITLE'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="filter-geocoded btn-group pull-left">
				<label for="filter_geocoded" class="element-invisible"><?php echo JText::_('COM_GEOFACTORY_FILTER_GEOCODE');?></label>
				<select name="filter_geocoded" id="filter_geocoded" onchange="this.form.submit();" class="input-medium" onchange="Joomla.orderTable()">
					<option value="0"																><?php echo JText::_('COM_GEOFACTORY_ALL');?></option>
					<option value="1" <?php if ((int)$geocoded == 1) echo 'selected="selected"'; ?>	><?php echo JText::_('COM_GEOFACTORY_FILTER_GEOCODED');?></option>
					<option value="2" <?php if ((int)$geocoded == 2) echo 'selected="selected"'; ?>	><?php echo JText::_('COM_GEOFACTORY_FILTER_GEOCODED_NOT');?></option>
				</select>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
	
		</div>

		<div class="row-fluid">
			<!-- Begin Content -->
			<div class="span10">
				<div id="filter-bar" class="btn-toolbar">
				</div>
				<?php 
					if (count($this->items)==1 AND (!isset($this->items[0]->item_id) OR $this->items[0]->item_id == 0)){
						return ;
					}
				?>
				<div class="clearfix"> </div>

				<table class="table table-striped">
					<thead>
						<tr>
							<th width="1%" class="hidden-phone">
								<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
							</th>
							<th width="30%" class="hidden-phone">
								<?php echo JText::_('JFIELD_TITLE_DESC'); ?>
							</th>
							<th width="30%" class="hidden-phone">
								<?php echo JText::_('COM_GEOFACTORY_ADDRESS'); ?>
							</th>
							<th width="30%" class="hidden-phone">
								<?php echo JText::_('COM_GEOFACTORY_COORDINATES'); ?>
							</th>
							<th width="1%" class="nowrap hidden-phone">
								<?php echo JText::_('JGRID_HEADING_ID'); ?>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="6">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<?php foreach ($this->items as $i => $item) :?>
							<?php if(!isset($item->item_id) OR $item->item_id == 0) continue;?>

							<tr class="row<?php echo $i % 2; ?>">
								<td class="center hidden-phone">
									<?php echo JHtml::_('grid.id', $i, $item->item_id); ?>
								</td>
								<td class="hidden-phone">
									<?php echo $item->item_name;?>
								</td>
								<td class="hidden-phone">
									<input type="button" value="Load address" onclick="loadAddress('<?php echo $item->item_id; ?>', '<?php echo $item->type_ms; ?>', 0);">
									<div id="address_<?php echo $item->item_id; ?>"></div>
								</td>
								<td class="hidden-phone">
									<input type="button" value="<?php echo JText::_('COM_GEOFACTORY_GEOCODE_THIS'); ?>" onclick="geocodeItem('<?php echo $item->item_id; ?>', '<?php echo $item->type_ms; ?>');">
									<?php if ((strlen($item->item_latitude)>2) AND ($item->item_latitude!=255)) : ?>
										<?php $cooGg=$item->item_latitude.','.$item->item_longitude; ?>
									
										<?php if ($showMinimap==1) : ?>
										
											<br /><a href="<?php echo $http; ?>maps.google.com/maps?q=<?php echo $cooGg ?>" target="_blank"><img id="imagemap" src="<?php echo $http; ?>maps.googleapis.com/maps/api/staticmap?center=<?php echo $cooGg ?>&zoom=15&size=250x150&markers=<?php echo $cooGg ?><?php echo $ggApikey ?>" ></a>
										<?php else: ?>
											<br /><a href="<?php echo $http; ?>maps.google.com/maps?q=<?php echo $cooGg ?>" target="_blank"><?php echo JText::_('COM_GEOFACTORY_SEE_ON_GGMAP'); ?></a>
										<?php endif; ?>	

										<div id="gglink_<?php echo $item->item_id; ?>"></div>
									<?php else: ?>
										<input type="button" value="<?php echo JText::_('COM_GEOFACTORY_SHOW_RESULT'); ?>" onclick="loadAddress('<?php echo $item->item_id; ?>', '<?php echo $item->type_ms; ?>', 1);">
										<div id="gglink_<?php echo $item->item_id; ?>"></div>
									<?php endif; ?>
								</td>
								<td class="center hidden-phone">
									<?php echo $item->item_id; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</div>
			<!-- End Content -->
		</div>
	</div>
</form>
<?php $this->pagination; ?>
