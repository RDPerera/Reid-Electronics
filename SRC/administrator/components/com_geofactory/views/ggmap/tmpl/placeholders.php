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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');

// liste des MS_xx dispo pour dyncat 
JPluginHelper::importPlugin('geocodefactory');
$dsp = JDispatcher::getInstance();

$available = array();
$dsp->trigger('getCodeDynCat', array(&$available));

// plugin installé ?
$buylevel = (!JPluginHelper::getPlugin('geocodefactory', 'plg_geofactory_levels'))?'<br />'. JText::_('COM_GEOFACTORY_BUY_LEVEL_PLUGIN'):'' ;

$vCodes = array() ;
$vCodes['Content']											= array() ;
$vCodes['Content']['{map}']									= JText::_('COM_GEOFACTORY_PH_VAR_MAP') ;
$vCodes['Content']['{number}']								= JText::_('COM_GEOFACTORY_PH_VAR_NUMBER') ;
$vCodes['Content']['{route}']								= JText::_('COM_GEOFACTORY_PH_VAR_ROUTE') ;
$vCodes['Level plugin']										= array() ; // bien sur je pourrais aussi n'afficher ces placeholders en passant par le plugins, et si pas de plugin, il n'apparaissent pas, mais c'est pas très vendeur
$vCodes['Level plugin']['{level_simple_check}']				= JText::_('COM_GEOFACTORY_PLG_LEVEL_CHECK')		.$buylevel ;
$vCodes['Level plugin']['{level_icon_simple_check}']		= JText::_('COM_GEOFACTORY_PLG_LEVEL_ICON_CHECK')	.$buylevel ;
$vCodes['Localisation']										= array() ;
$vCodes['Localisation']['{radius_form}']					= JText::_('COM_GEOFACTORY_PH_VAR_RADIUS') ;
$vCodes['Localisation']['{reset_map}']						= JText::_('COM_GEOFACTORY_PH_VAR_RESET_MAP') ;
$vCodes['Localisation']['{locate_me}']	 					= JText::_('COM_GEOFACTORY_PH_VAR_LOCATE') ;
//$vCodes['Localisation']['{save_me}']						= JText::_('COM_GEOFACTORY_PH_VAR_SAVE') ;
//$vCodes['Localisation']['{save_me_full}']					= JText::_('COM_GEOFACTORY_PH_VAR_SAVE_FULL') ;
//$vCodes['Localisation']['{reset_me}']						= JText::_('COM_GEOFACTORY_PH_VAR_RESET') ;
$vCodes['Localisation']['{near_me}']						= JText::_('COM_GEOFACTORY_PH_VAR_NEAR_ME') ;
$vCodes['Markerset selector']								= array() ;
$vCodes['Markerset selector']['{selector}']					= JText::_('COM_GEOFACTORY_PH_VAR_SELECTOR') ;
$vCodes['Markerset selector']['{selector_1}']				= JText::_('COM_GEOFACTORY_PH_VAR_SELECTOR') ;
$vCodes['Markerset selector']['{multi_selector}']			= JText::_('COM_GEOFACTORY_PH_VAR_MULTI_SELECTOR') ;
$vCodes['Markerset selector']['{multi_selector_1}']			= JText::_('COM_GEOFACTORY_PH_VAR_MULTI_SELECTOR') ;
$vCodes['Markerset selector']['{ullist_img}']				= JText::_('COM_GEOFACTORY_PH_VAR_TOGGLE_SELECTOR') ;
$vCodes['Markerset selector']['{toggle_selector}']			= JText::_('COM_GEOFACTORY_PH_VAR_TOGGLE_SELECTOR') ;
$vCodes['Markerset selector']['{toggle_selector_1}']		= JText::_('COM_GEOFACTORY_PH_VAR_TOGGLE_SELECTOR') ;
$vCodes['Markerset selector']['{toggle_selector_icon_1}']	= JText::_('COM_GEOFACTORY_PH_VAR_TOGGLE_SELECTOR') ;
$vCodes['Markerset selector']['{toggle_selector_icon}']		= JText::_('COM_GEOFACTORY_PH_VAR_TOGGLE_SELECTOR') ;
$vCodes['Markerset selector']['{sidelists_premium}']		= JText::_('COM_GEOFACTORY_PH_VAR_PREMIUM') ;

if (count($available)>0){
foreach($available as $av)
	$vCodes['Markerset selector']['{dyncat '.$av.'#00}']	= JText::_('COM_GEOFACTORY_PH_VAR_DYN_CAT') ;
}

$vCodes['Side content']										= array() ;
$vCodes['Side content']['{sidelists}']						= JText::_('COM_GEOFACTORY_PH_VAR_SIDELISTS') ;
$vCodes['Side content']['{sidebar}']					 	= JText::_('COM_GEOFACTORY_PH_VAR_SIDEBAR') ;
?>


<fieldset>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'codes')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'codes', JText::_('COM_GEOFACTORY_PLACEHOLDERS')); ?>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th width="33%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_PLACEHOLDERS_INSERT_CODE_TEMPLATE'); ?></th>
				<th width="77%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_PLACEHOLDERS_HELP'); ?></th>
			</tr>
		</thead>
		<?php foreach ($vCodes as $title=>$curCodes) : ?>
		<thead>
			<tr>
				<td colspan="2"><h2><?php echo $title; ?></h2></td>
			</tr>
		</thead>
		<tbody>
			<?php $i=0 ; foreach ($curCodes as $code => $help) : $i++ ; ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><input type="button" style="width:150px;" onclick="if (window.parent) window.parent.addCtrlInTpl('<?php echo $code; ?>');" value="<?php echo $code; ?>" /></td>
				<td><?php echo $help; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<?php endforeach; ?>

	</table>

	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'sample', JText::_('COM_GEOFACTORY_SAMPLE_TEMPLATE')); ?>

	<textarea class="field span12" rows="18">
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4" id="gf_sideTemplateCont">
			<div id="gf_sideTemplateCtrl">
				<div class="well"><div id="gf_btn_superfull" style="display:none;" onclick="superFull({mapvar}.map);return false;"><a id="reset" href="#"><i class="glyphicon glyphicon-chevron-right"></i> Reduce</a></div>
					<h4>Search near<small> (<a id="find_me" href="#" onClick="{mapvar}.LMBTN();">Find me</a>)</small></h4>
					<p>
						<input type="text" id="addressInput" value="" class="gfMapControls" placeholder="Enter an address or intersection" />
					</p>
					<p>
						<label>
							Within
							{rad_distances} 
						</label>
					</p>
					<h4>Categories</h4>
					{ullist_img}
					<br />

					<a class="btn btn-primary" id="search" href="#" onclick="{mapvar}.SLFI();">
						<i class="glyphicon glyphicon-search"></i>
						Search
					</a>
					<a class="btn btn-default" id="reset" href="#" onclick="{mapvar}.SLRES();">
						<i class="glyphicon glyphicon-repeat"></i>
						Reset
					</a>
					{layer_selector}
				</div>
				<div class="alert alert-info" id="result_box" ><h4>Result {number}</h4>{sidelists}</div>
			</div>
		</div>
		<div class="col-md-8">
			<noscript>
				<div class="alert alert-info">
					<h4>Your JavaScript is disabled</h4>
					<p>Please enable JavaScript to view the map.</p>
				</div>
			</noscript>
			{map}
			<br />
			<div class="alert alert-info" id="route_box" ><h4>Click a marker to reach</h4>{route}</div>
		</div>
	</div>
</div>
<div id="gf_panelback" style="cursor:pointer;float:right;display:none;position:fixed;width:20px;height:100%;top:0;right:0;zIndex:100; background-color:silver!important; background: url('.JURI::root().'media/com_geofactory/assets/arrow-left.png) no-repeat center" onclick="normalFull({mapvar}.map);"><div>

	</textarea>

	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
</fieldset>
