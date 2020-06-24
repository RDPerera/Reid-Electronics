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
$i=0 ;
$vSamples = array(
	"http://sourceUrl.org/#Z#/#X#/#Y#.png|Name|maxzoom|alt title|png|tileSize; ",
	"http://tile.openstreetmap.org/#Z#/#X#/#Y#.png|Mapnik|18|Open Streetmap Mapnik|true|256; ",
	"http://tile.xn--pnvkarte-m4a.de/tilegen/#Z#/#X#/#Y#.png|OPNV|18|Open Streetmap OPNV|true|256; ",
	"http://bing.com/aerial|Aerial|18|Bing! aerial|true|256; ",
	"http://bing.com/label|Labels|18|Bing! label|true|256; ",
	"http://bing.com/road|Roads|18|Bing! roads|true|256; ",
	"http://b.tile.opencyclemap.org/cycle/#Z#/#X#/#Y#.png|Bicycle|18|Open cycle|true|256; ",
	"http://b.tile2.opencyclemap.org/transport/#Z#/#X#/#Y#.png|Transport|18|Open transport|true|256; ",
	"http://b.tile3.opencyclemap.org/landscape/#Z#/#X#/#Y#.png|Landscape|18|Open landscape|true|256; ")
?>

<fieldset>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'codes')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'codes', JText::_('COM_GEOFACTORY_TILES_INSERT')); ?>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th width="33%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_TILES_ELEMENT'); ?></th>
				<th width="77%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_TILES_ELEMENT_DESC'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="row<?php echo $i % 2; $i++?>">
				<td><input type="text" value="" id="gf_tile_name"></td>
				<td><?php echo JText::_('COM_GEOFACTORY_TILES_NAME'); ?></td>
			</tr>
			<tr class="row<?php echo $i % 2; $i++ ?>">
				<td><input type="text" value="" id="gf_tile_desc"></td>
				<td><?php echo JText::_('COM_GEOFACTORY_TILES_TOOTIPS'); ?></td>
			</tr>
			<tr class="row<?php echo $i % 2; $i++ ?>">
				<td><input type="text" value="" id="gf_tile_url"></td>
				<td><?php echo JText::_('COM_GEOFACTORY_TILES_URL'); ?></td>
			</tr>
			<tr class="row<?php echo $i % 2; $i++ ?>">
				<td><input type="text" value="18" id="gf_tile_zoom"></td>
				<td><?php echo JText::_('COM_GEOFACTORY_TILES_ZOOM'); ?></td>
			</tr>
			<tr class="row<?php echo $i % 2; $i++ ?>">
				<td><select  id="gf_tile_png"><option selected="selected" value="true">Yes</option><option value="false">No</option></select></td>
				<td><?php echo JText::_('COM_GEOFACTORY_TILES_ISPNG'); ?></td>
			</tr>
			<tr class="row<?php echo $i % 2; $i++ ?>">
				<td><input type="text" value="256" id="gf_tile_size"></td>
				<td><?php echo JText::_('COM_GEOFACTORY_TILES_SIZE'); ?></td>
			</tr>
			<tr class="row<?php echo $i % 2; $i++ ?>">
				<td><input type="button" style="width:150px;" onclick="if (window.parent)  window.parent.insertNewTile(jQuery('#gf_tile_url').val(),jQuery('#gf_tile_name').val(),jQuery('#gf_tile_zoom').val(),jQuery('#gf_tile_desc').val(),jQuery('#gf_tile_png').val(),jQuery('#gf_tile_size').val());" value="<?php echo JText::_('COM_GEOFACTORY_INSERT'); ?>" /></td>
				<td><?php echo JText::_('COM_GEOFACTORY_INSERT'); ?></td>
			</tr>
		</tbody>
	</table>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'sample', JText::_('COM_GEOFACTORY_TILES_SAMPLES')); ?>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th width="77%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_TILES_SAMPLE_INSERT'); ?></th>
				<th width="33%" class="nowrap"><?php echo JText::_('COM_GEOFACTORY_TILES_SAMPLE'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0 ; foreach ($vSamples as $sample) : $i++ ; ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><input type="button" style="width:150px;" onclick="if (window.parent)  window.parent.insertSampleTile('<?php echo $sample; ?>');" value="<?php echo JText::_('COM_GEOFACTORY_INSERT'); ?>" /></td>
				<td><?php echo $sample; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
</fieldset>
