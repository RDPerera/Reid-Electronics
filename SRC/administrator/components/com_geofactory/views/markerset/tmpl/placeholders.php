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

$app 		= JFactory::getApplication('site');
$typeListe	= $app->input->getSring('type', '');

JPluginHelper::importPlugin('geocodefactory');
$dsp = JDispatcher::getInstance();

// récupère tous les champs de SP ...
$placeHolders = null ;		
$dsp->trigger('getPlaceHoldersTemplate', array($typeListe, &$placeHolders));

$vCodes = array() ;
$vCodes['Common']											= array() ;
$vCodes['Common']['{ID}']									= 'COM_GEOFACTORY_PH_VAR_ID' ;
$vCodes['Common']['{TITLE}']								= 'COM_GEOFACTORY_PH_VAR_TITLE' ;
$vCodes['Common']['{LINK}']									= 'COM_GEOFACTORY_PH_VAR_LINK' ;
$vCodes['Common']['{DISTANCE}']								= 'COM_GEOFACTORY_PH_VAR_DISTANCE' ;
$vCodes['Common']['{WAYSEARCH}']							= 'COM_GEOFACTORY_PH_VAR_WAYSEARCH' ;

// ajout des customs du user
if (is_array($placeHolders) AND count($placeHolders)>0){
foreach($placeHolders as $h=>$phs){
	$vCodes[$h] = array() ;
	if (is_array($phs) AND count($phs)>0){
	foreach($phs as $hlp=>$ph){
		$vCodes[$h][$ph] = $hlp ;
	}
	}
}
}
?>

<fieldset>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'codes')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'codes', JText::_('COM_GEOFACTORY_PLACEHOLDERS')); ?>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th width="25%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_PLACEHOLDERS_INSERT_CODE_SIDEBAR'); ?></th>
				<th width="25%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_PLACEHOLDERS_INSERT_CODE_TEMPLATE'); ?></th>
				<th width="50%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_PLACEHOLDERS_HELP'); ?></th>
			</tr>
		</thead>

		<?php foreach ($vCodes as $title=>$curCodes) : ?>
		<thead>
			<tr>
				<td colspan="3"><h2><?php echo $title; ?></h2></td>
			</tr>
		</thead>
		<tbody>
			<?php $i=0 ; foreach ($curCodes as $code => $help) : $i++ ; ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><input type="button" style="width:150px;" onclick="if (window.parent) window.parent.addCtrlInTpl('<?php echo $code; ?>', '_b');" value="<?php echo $code; ?>" /></td>
				<td><input type="button" style="width:150px;" onclick="if (window.parent) window.parent.addCtrlInTpl('<?php echo $code; ?>', '_s');" value="<?php echo $code; ?>" /></td>
				<td><?php echo JText::_($help); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<?php endforeach; ?>

	</table>

	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'sample', JText::_('COM_GEOFACTORY_SAMPLE_TEMPLATE')); ?>

	<textarea class="field span12" rows="18">
<h2>{TITLE}</h2>
<p><a href="{LINK}">Detail link</a></p>
<p>The entry is located in : {field_city}</p>
<img src="{ICO_field_company_logo}"/>
	</textarea>

	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'sampletab', JText::_('COM_GEOFACTORY_SAMPLE_TEMPLATE_TAB').' '); ?>

	<textarea class="field span12" rows="18">
 <div id="gftabs">
  <ul>
   <li><a href="#tab1">Tab 1 title here</a></li>
   <li><a href="#tab2" id="SV">Streetview tab title here</a></li>
   <li><a href="#tab3">Tab 3 title here</a></li>
  </ul>
  <div id="tab1">
    Enter here what you want (html) content for the tab 1
  </div>
  <div id="tab2">
   Enter here what you want (html) content for the tab, in this sample it is a Streetview tab, the insert the following placeholder and DONT FORGET the id SV in the tab title see abowe
   {STREETVIEW}
  </div>
  <div id="tab3">
    Enter here what you want (html) content for the tab 3
  </div>
 </div>
	</textarea>

	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
</fieldset>
