<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.54F
	* Creation date: Novembre 2019
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 

$height_msg = '450';
$width_msg = '350';

$itemid = JRequest::getVar('Itemid', 0, '', 'int');
$layout = JRequest::getVar('layout', '', '', 'str');

$row=$this->lieu;

$image=JURI::base().$this->params->get('gmapfp_chemin_img').$row->img;
$clock=JURI::base().'components/com_gmapfp/images/clock_32.png';
$globe=JURI::base().'components/com_gmapfp/images/globe_32.png';
$printer=JURI::base().'components/com_gmapfp/images/printer.png';
$msg=JURI::base().'components/com_gmapfp/images/xfmail.png';
$loupe=JURI::base().'components/com_gmapfp/images/recherche.png';

?>
<div class="gmapfp item-page<?php echo $this->params->get('pageclass_sfx')?>">
	<div class="pull-right">
		<?php
			if (($row->horaires_prix!=null)&&($this->params->get('gmapfp_afficher_horaires_prix')==1)) {
				$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=horaires_item&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
				echo ' <a class="modal" rel="{size: {x:550, y:400}, handler:\'iframe\'}" href="'.$link.'"  title="'.JText::_('GMAPFP_HORAIRES_PRIX').'"><img src='.$clock.' /></a>&nbsp;&nbsp;&nbsp; ';
			};
			$width_carte=$this->params->get('gmapfp_width')+17;
			$link = JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=item_carte&flag=1&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
			if ($this->params->get('gmapfp_itineraire')==1) {
				$height_carte=$this->params->get('gmapfp_height')+150;
				if (strpos($this->params->get('gmapfp_width'),'%')){
					$width_carte=600;
				}else{
					$width_carte=$this->params->get('gmapfp_width')+20;
				}
			}else{
				$height_carte=$this->params->get('gmapfp_height')+0;
				if (strpos($this->params->get('gmapfp_width'),'%')){
					$width_carte=600;
				}else{
					$width_carte=$this->params->get('gmapfp_width');
				}
			};
			echo '<a class="modal" rel="{size: {x:'.$width_carte.', y:'.$height_carte.'}, handler:\'iframe\'}" href="'.$link.'"  title="'.JText::_('GMAPFP_CARTE').'"><img src='.$globe.' /></a>&nbsp;&nbsp;&nbsp;';
			$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=print_article&flag=1&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
			echo '<a href="'.$link.'" class="modal" rel="{size: {x:'.$width_carte.', y:800}, handler:\'iframe\'}" title="'.JText::_('GMAPFP_IMPRIMER').'"><img src='.$printer.' /></a>&nbsp;&nbsp;&nbsp;';
			if (($row->email!=null)and($this->params->get('gmapfp_msg')==1)) {
				$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=item_msg&flag=1&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
				echo '<a href="'.$link.'" class="modal" rel="{size: {x:'.$width_msg.', y:'.$height_msg.'}, handler:\'iframe\'}" title="'.JText::_('GMAPFP_DISPLAY_MSG').'"><img src='.$msg.' /></a>&nbsp;&nbsp;&nbsp;';
			};
			$link4=substr($row->link,0,4);
			$link5=substr($row->link,0,5);
			$link9=substr($row->link,0,9);
			$link10=substr($row->link,0,10);
			$linkok=0;
			if ((!empty($row->icon))||(!$row->icon='')) {
				if (($row->article_slug>=1)&&($row->link)) {
			//if ($row->id==11) die(print_r('alias : '.$row->article_alias. 'id : '.$row->article_id.' slug : '.$row->article_slug));
					require_once JPATH_SITE . '/components/com_content/helpers/route.php';
					$linkmap=JRoute::_(ContentHelperRoute::getArticleRoute($row->article_slug, $row->article_id.':'.$row->article_alias, 0));
					$linkmap .= '?&tmpl=component';
					$linkok=1;
				};
				if (($link5=="http:")||($link4=="www.")||($link9=="index.php")) {
					$linkmap=$row->link;
					if ($link4=="www.") {$linkmap="http://".$linkmap;};
					if ($link10=="index.php?") {$linkmap=JURI::base().$linkmap."&tmpl=component";};
					$linkok=1;
				};
				$icon=JURI::base().'images/gmapfp/icons/'.$row->icon;
			};
			if (($linkok)&&($row->icon<>'')) {
				echo '<a href="'.$linkmap.'" class="modal" rel="{size: {x:800, y:600}, handler:\'iframe\'}" title="'.$row->icon_label.'"><img src='.$icon.' /></a>&nbsp;&nbsp;&nbsp;';
			};

			if ($layout!='article') {
				// insertion de l'icon JComments
				$jcomments =  JPATH_SITE.'/components/com_jcomments/jcomments.php';
				if ((file_exists($jcomments))and($this->params->get('gmapfp_jcomments'))) {
					$icon_jcomment=JURI::base().'administrator/components/com_jcomments/assets/icon-48-jcomments.png';
					$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=jcomment_item&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
					echo '<a class="modal" rel="{size: {x:600, y:600}, handler:\'iframe\'}" href="'.$link.'"  title="'.JText::_('GMAPFP_JCOMMENT').'"><img src='.$icon_jcomment.' /></a>';
					require_once($jcomments);
					echo JComments::getCommentsCount($row->id, 'com_gmapfp');
				}
			}
		?>
    </div>
		<h2 class="item-title"><?php echo $row->nom; ?></h2>
		<?php
			//si affichage d'un article ou d'une catégorie d'article, n'affiche pas le nom de la catégorie
			if (($layout!='article')&&($layout!='categorie'))
				echo '<div class="category-name">('.$row->title.')</div>';
		?>
	<div class="gmapfp_detail">
		<div class="gmapfp_taille1 pull-left">
			<?php
				if ($row->img!=null) { ?> <a class="modal" href='<?php echo $image; ?>' ><img src=<?php echo $image; ?> style="height:<?php echo $this->params->get('gmapfp_hauteur_img')?>px;"/><img src=<?php echo $loupe; ?> style="height:18px;"/></a> <?php }; ?>
		</div>
		<div class="gmapfp_taille2 pull-left">
			<?php
			if ($row->adresse!=null) {echo JText::_('GMAPFP_ADRESSE').'<span>'.$row->adresse.'</span><br />';};
			if ($row->adresse2!=null) {echo JText::_('GMAPFP_ADRESSE').'<span>'.$row->adresse2.'</span><br />';};
			if ($row->codepostal!=null) {echo JText::_('GMAPFP_CODEPOSTAL').'<span>'.$row->codepostal.'</span><br />';};
			if ($row->ville!=null) {echo JText::_('GMAPFP_VILLE').'<span>'.$row->ville.'</span><br />';};
			if ($row->departement!=null) {echo JText::_('GMAPFP_DEPARTEMENT').'<span>'.$row->departement.'</span><br />';};
			if ($row->pay!=null) {echo JText::_('GMAPFP_PAYS').'<span>'.$row->pay.'</span><br />';};
			?>
		</div>
		<div class="pull-left">
			<?php
			if ($row->tel!=null) {echo JText::_('GMAPFP_TEL').'<span>'.$row->tel.'</span><br />';};
			if ($row->tel2!=null) {echo JText::_('GMAPFP_TEL').'<span>'.$row->tel2.'</span><br />';};
			if ($row->fax!=null) {echo JText::_('GMAPFP_FAX').'<span>'.$row->fax.'</span><br />';};
			if (($row->email!=null)and($this->params->get('gmapfp_msg')==0)) {echo JText::_('GMAPFP_EMAIL').'<span>'.@JHTML::_('email.cloak',$row->email).'</span><br />';};
			if ($row->web!=null) {
				if (substr($row->web,0,4)!="http") {$lien_web = "//".$row->web;} else {$lien_web = $row->web;};
				echo JText::_('GMAPFP_SITE_WEB').'<span><a href='.$lien_web.' target="_blank" > '.$row->web.' </a></span> <br />';
			};
			?>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php if ($layout!='article') {?>
		<div class="gmapfp_article">
			<?php
			if ($row->intro!=null) {echo JText::_('GMAPFP_MESSAGE').'<span>'.$row->intro.'</span>';};
			if ($row->message!=null) {
				echo '<br />';
				$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=article&flag=1&id='.$row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
				echo '<p class="readmore"><a class="modal" rel="{size: {x:650, y:500}, handler:\'iframe\'}" href="'.$link.'">'.JText::_('GMAPFP_LIRE_SUITE').'</a></p>';
			}; ?>
		</div>
	<?php } ?>
</div>