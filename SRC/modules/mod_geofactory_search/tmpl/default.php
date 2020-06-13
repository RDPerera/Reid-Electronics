<?php
/**
 * @name		Geocode Factory Search module
 * @package		mod_geofactory_search
 * @copyright	Copyright © 2014 - All rights reserved.
 * @license		GNU/GPL
 * @author		Cédric Pelloquin
 * @author mail	info@myJoom.com
 * @website		www.myJoom.com
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$item = JFactory::getApplication()->getMenu()->getItem( $params->get('sMapUrl') ); 
$url = JRoute::_($item->link . "&Itemid=" . $item->id); // use JRoute to make link from object

$tmplCode = $params->get('tmplCode');

if ($params->get('bRadius')){ ?>
	<form action="<?php echo JRoute::_($url, true); ?>" method="post" id="gf_search-form" >
		<?php 
			if (strlen($tmplCode)>3){
				$tmplCode = str_replace('[INPUT]', $radInpHtml, $tmplCode);
				$tmplCode = str_replace('[DISTANCE]', $radDistHtml, $tmplCode);
				$tmplCode = str_replace('[SEARCH_BTN]', $buttons, $tmplCode);
				echo $tmplCode;
			}else{
		?>
		<p id="rad-intro">
			<?php echo $radIntro ?>
		</p>
		<p id="rad-city">
			<label for="gf_mod_search"><?php echo $labels[0]; ?></label><br />
			<?php echo $radInpHtml ?>
		</p>
		<p id="rad-dist">
			<label for="gf_mod_radius"><?php echo $labels[1]; ?></label><br />
			<?php echo $radDistHtml ?>
		</p>
		<p id="rad-btn">
			<?php echo $buttons ?>
		</p>
		<?php
			}
		?>

	</form>
<?php } ?>
<?php echo $barHtml ; ?>
<?php echo $listHtml ; ?>

