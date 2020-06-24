<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.50F
	* Creation date: Octobre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;
JHTML::_( 'behavior .modal' );

$mainframe  = JFactory::getApplication(); 
$lang		= JFactory::getLanguage();
$template	= $mainframe->getTemplate();

$langue		=substr((@$lang->getTag()),0,2);
if ($langue!='fr') $langue = 'en';

$user	= JFactory::getUser();
?>
<link rel="stylesheet" href="components/com_gmapfp/views/general.css" type="text/css" /> 

<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

	<div class="row-fluid">
		<div class="span6">
			<div id="cpanel">
				<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
					<div class="icon">
						<a href="index.php?option=com_config&view=component&component=com_gmapfp&path=&return=<?php echo base64_encode(JURI::root()."administrator/index.php?option=com_gmapfp&controller=accueil&task=view") ?>">
							<?php echo '<img src="'.JURI::root().'administrator/components/com_gmapfp/images/parametres48.png" alt="'.JText::_('GMAPFP_PARAMETRES').'">'; ?>
							<div><?php echo JText::_('GMAPFP_PARAMETRES'); ?></div>
						</a>
					</div>
				</div>
				<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
					<div class="icon">
						<a href="index.php?option=com_gmapfp&controller=gmapfp&task=view">
							<?php echo '<img src="'.JURI::root().'administrator/components/com_gmapfp/images/lieux48.png" alt="'.JText::_('GMAPFP_LIEUX').'">'; ?>
							<div><?php echo JText::_('GMAPFP_LIEUX'); ?></div>
						</a>
					</div>
				</div>
				<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
					<div class="icon">
						<a href="index.php?option=com_gmapfp&controller=marqueurs&task=view">
							<?php echo '<img src="'.JURI::root().'administrator/components/com_gmapfp/images/marqueurs48.png" alt="'.JText::_('GMAPFP_MARQUEURS').'">'; ?>
							<div><?php echo JText::_('GMAPFP_MARQUEURS'); ?></div>
						</a>
					</div>
				</div>
				<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
					<div class="icon">
						<a href="index.php?option=com_categories&extension=com_gmapfp">
							<?php echo '<img src="'.JURI::root().'administrator/components/com_gmapfp/images/categories48.jpg" alt="'.JText::_('JCATEGORIES').'">'; ?>
							<div><?php echo JText::_('JCATEGORIES'); ?></div>
						</a>
					</div>
				</div>
				<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
					<div class="icon">
						<a href="index.php?option=com_gmapfp&controller=personnalisations&task=view">
							<?php echo '<img src="'.JURI::root().'administrator/components/com_gmapfp/images/personnalisation48.png" alt="'.JText::_('GMAPFP_PERSONNALISATION').'">'; ?>
							<div><?php echo JText::_('GMAPFP_PERSONNALISATION'); ?></div>
						</a>
					</div>
				</div>
				<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
					<div class="icon">
						<a href="index.php?option=com_gmapfp&controller=css&task=view">
							<?php echo '<img src="'.JURI::root().'administrator/components/com_gmapfp/images/css48.jpg" alt="'.JText::_('CSS').'">'; ?>
							<div>CSS</div>
						</a>
					</div>
				</div>
			</div>
			<div  style="clear:both;">
				<div class="icon"  style="width: 108px;">
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.0";
						fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>                        
					<div class="fb-like" data-href="http://www.facebook.com/gmapfp" data-layout="box_count" data-action="like" data-show-faces="true" data-share="true"></div>
				</div>
			</div>
			<div  style="clear:both;" class="google_map_key">
				<?php echo JText::_('COM_GMAPFP_HOW_CREATE_GOOGLE_KEY'); ?>
			</div>
		</div>
		<div class="span6">
		<?php
			echo JHtml::_('bootstrap.startAccordion', 'menuSlider', array('active' => 'collapse0'));
			$tabs	= $this->get('publishedTabs');
			$i = 0;

			foreach ($tabs as $tab) {
				$title = JText::_($tab->title);
				echo JHtml::_('bootstrap.addSlide', 'menuSlider', JText::_($tab->title), 'collapse' . $i++);
				$contenu = 'infos_' .$tab->name;
				echo $this->$contenu();
				echo JHtml::_('bootstrap.endSlide');
			}

			echo JHtml::_('bootstrap.endAccordion');
		 ?>
		</div>
	</div>
	<table class="admintable">
		<tr>
			<td class="key">
				<?php echo JText::_( 'Forum' );?>
			</td>
			<td>
				<a href="http://www.gmapfp.org/<?php echo $langue; ?>/forum" target="_new">www.gmapfp.org/<?php echo $langue; ?>/forum</a>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_( 'GMAPFP_DOCUMENTATION' );?>
			</td>
			<td>
				<a href="http://www.gmapfp.org/<?php if ($langue=="fr") {echo "fr/telechargement/2---Documentation";} else {echo "en/download/2---Documentation/";}; ?>/documentation" target="_new">www.gmapfp.org/<?php if ($langue=="fr") {echo "fr/telechargement/2---Documentation";} else {echo "en/download/2---Documentation/";}; ?></a>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo '<h1 style="color:red;">'.JText::_( 'GMAPFP_DISCOVER_PRO_VERSION' ).' : '.'</h1>'; ?>
			</td>
			<td>
				<a href="http://pro.gmapfp.org/<?php echo $langue; ?>" target="_new"><?php echo '<h1 style="color:red; text-decoration: underline;">'.JText::_( 'GMapFP Pro' ).'</h1>'; ?></a>
			</td>
		</tr>
	</table>
	<div class="donation">
	<?php 
		echo $this->infos_donation();
	?>
	</div>
</div>
<div class="copyright" align="center">
	<br />
	<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
</div>
<div class="clr"></div>

