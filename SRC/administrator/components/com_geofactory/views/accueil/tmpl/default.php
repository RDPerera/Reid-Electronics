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
JHTML::_( 'behavior.modal' ); 

?>
<script type="text/javascript">
</script>

<?php
function drawJ3Item($class, $text, $link=null){
?>
	<div class="row-fluid">
		<div class="span12">
			<?php if ($link) : ?>
				<a href="<?php echo $link; ?>"><i class="icon-<?php echo $class; ?>"></i> <span><?php echo $text; ?></span></a>
			<?php else : ?>
				<i class="icon-<?php echo $class; ?>"></i> <span><?php echo $text; ?></span>
			<?php endif;?>
		</div>
	</div>
<?php
}






?>

<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_geofactory&view=accueil');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<div class="span10">		
		<ul class="nav nav-tabs">
			<li class="active">	
				<a href="#welcome" data-toggle="tab"	><?php echo JText::_('COM_GEOFACTORY_MAIN_MENU'); ?></a>
			</li>
			<li>
				<a href="#changelog" data-toggle="tab"	><?php echo JText::_('COM_GEOFACTORY_CHANGE_LOG') ?></a>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="welcome">
			<div class="span6">
				<div class="well well-small">
					<div class="module-title nav-header">Quick Links</div>
					<div class="row-striped">
						<?php 
							$maps = GeofactoryHelperAdm::getLinksEditShortCuts(GeofactoryHelperAdm::getArrayListMaps(), 'ggmap');
							$markset = GeofactoryHelperAdm::getLinksEditShortCuts(GeofactoryHelperAdm::getArrayListMarkersets(), 'markerset');
							drawJ3Item("file-add", 	JText::_('COM_GEOFACTORY_CPANEL_CREATE_MAP'),		"index.php?option=com_geofactory&view=ggmap&layout=edit");
							drawJ3Item("file-add", 	JText::_('COM_GEOFACTORY_CPANEL_CREATE_MS'),		"index.php?option=com_geofactory&view=markersets&layout=edit");
							drawJ3Item("list-view", JText::_('COM_GEOFACTORY_MENU_ASSIGN_PATTERN'),		"index.php?option=com_geofactory&view=assigns");
							drawJ3Item("list-view", JText::_('COM_GEOFACTORY_MENU_MAPS_MANAGER').$maps,		"index.php?option=com_geofactory&view=ggmaps");
							drawJ3Item("list-view", JText::_('COM_GEOFACTORY_MENU_MARKERSETS_MANAGER').$markset,	"index.php?option=com_geofactory&view=markersets");
							drawJ3Item("flag", 		JText::_('COM_GEOFACTORY_MENU_GEOCODING'),			"index.php?option=com_geofactory&view=geocodes");
							drawJ3Item("cog", 		JText::_('COM_GEOFACTORY_CPANEL_CONFIGURATION'),	"index.php?option=com_config&view=component&component=com_geofactory");
						 	drawJ3Item("cogs", 		JText::_('COM_GEOFACTORY_PLUGIN_CONFIGURATION'),	"index.php?option=com_plugins&view=plugins&filter_folder=geocodefactory"); 
							drawJ3Item("cube",	 	JText::_('COM_GEOFACTORY_CPANEL_IMPORT_OLD'),		"index.php?option=com_geofactory&view=oldmaps"); 
						?>
					</div>
				</div>

				<div class="well well-small">
					<div class="module-title nav-header">Update center<a style="float:right;" target="_blank" href="http://www.myjoom.com/index.php/documentation?view=kb&prodid=4&kbartid=113"><span class="label label-important">help</span></a></div>
						<div class="row-striped">
						<?php 
							drawJ3Item("refresh", "Check for updates", 'index.php?option=com_geofactory&view=accueil&task=accueil.updates');

							// récupère la liste des extensions a mettre à jour
							$vExts = GeofactoryHelperUpdater::getUpdatesList();
							
							if (is_array($vExts) AND count($vExts)){
								// extensions installées
								foreach($vExts as $ext)
									drawJ3Item("puzzle", $ext); 
							}

							// une petite pub !
							drawJ3Item("download", JText::_('COM_GEOFACTORY_CPANEL_GET_MORE_PLUGINS'), 'http://www.myjoom.com" target="_blank');
						?>
					</div>
				</div>

				<?php 
					$edNone 	= GeofactoryHelperAdm::isEditorNoneEnabled();
					$codeMir 	= GeofactoryHelperAdm::isCodeMirrorEnabled();
					if (!$codeMir){
				?>
					<div class="well well-small">
						<div class="module-title nav-header">Warnings</div>
						<div class="row-striped">
							<?php
								if (!$codeMir && !$edNone){
									drawJ3Item("warning", JText::_('COM_GEOFACTORY_CPANEL_ENABLE_EDITORS')); 
								}
								if (!$codeMir){
									drawJ3Item("warning", JText::_('COM_GEOFACTORY_CPANEL_ENABLE_EDITOR')); 
								}
							?>
						</div>
					</div>
				<?php 
					}
				?>
			</div>
			<div class="span6">
				<div class="well well-small">
					<div class="module-title nav-header"><?php echo JText::_('COM_GEOFACTORY') ?></div>
					<div class="row-striped">
						<div class="row-fluid">
							<?php echo JText::_('COM_GEOFACTORY_DESCRIPTION_WELCOME') ?>
						</div>
					</div>
				</div>

				<div class="well well-small">
					<div class="module-title nav-header">Credits</div>
					<div class="row-striped">
						<div class="row-fluid">
							<p><?php echo JText::_('COM_GEOFACTORY_CPANEL_CREDITS') ?></p>
							<ul>
								<li>
									<strong>Kostas Stathakos - <a href='http://www.e-leven.net' target='_blank'>e-leven social webs</a></strong>
									<br />Product tester, documentation redactor
								</li>
								<li>
									<strong>Steve Hess - <a href='http://www.karaokeacrossamerica.com' target='_blank'>Karaoke Across America</a></strong>
									<br />Product tester, documentation redactor and proof reader 
								</li>
								<li>
									<strong>Fred Vogels - <a href='http://www.backtonormandy.org' target='_blank'>Backtonormandy historical site</a></strong>
									<br />Product tester 
								</li>
								<li>
									<strong>Mapicons - <a href='http://mapicons.nicolasmollet.com/' target='_blank'>Map Icons Collection</a></strong>
									<br />Geocode Factory includes about 200 map icons markers from Map Icons Collection. You can also customize the colors and get 500+ more on the autor's website.
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="well well-small">
					<div class="module-title nav-header"><?php echo JText::_('COM_GEOFACTORY_CPANEL_LASTNEWS') ?></div>
					<div class="row-striped">
						<div class="row-fluid">
							<iframe id="if_news" height="400px" width="100%" frameborder="0" border="0" src="http://www.myjoom.com/index.php?option=com_content&view=category&id=72&tmpl=component"></iframe>	
						</div>
					</div>
				</div>
			</div>
			</div>
			<div class="tab-pane" id="changelog">
				<iframe id="if_changelog" height="700px" width="100%" frameborder="0" border="0" src="http://www.myjoom.com/index.php?option=com_content&view=article&id=128&tmpl=component"></iframe>
			</div>
		</div>
		<input type="hidden" name="type" value="" />
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<!-- End Content -->
</form>