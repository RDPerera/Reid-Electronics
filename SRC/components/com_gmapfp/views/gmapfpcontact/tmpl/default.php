<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.47F
	* Creation date: Janvier 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die; 

if (empty($this->row->email)) {
	JError::raiseWarning(0, JText::_('GMAPFP_EMAIL_RECEPTEUR'));
}

if (!empty ($this->row)) {

	if ($this->params->get('show_page_heading')) : ?>
		<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php endif;
	
	$config = JComponentHelper::getParams('com_gmapfp');
	$document  = JFactory::getDocument();
	$mainframe = JFactory::getApplication(); 
	
	if ($this->row->metadesc) {
		$document->setDescription( $this->row->metadesc );
	}
	if ($this->row->metakey) {
		$document->setMetadata('keywords', $this->row->metakey);
	}
	if ($mainframe->getCfg('MetaTitle') == '1') {
		$document->setMetaData('title', @$this->row->ville.' : '.$this->row->nom.' ('.$this->row->title.')');
	}
	?>
		<div id="enregistrement gmapfp">
			<table class="gmapfp_detail">
				<tr>
					<td><h2><?php echo $this->row->nom; ?></h2></td>
					<td><h4>(<?php echo $this->row->title; ?>)</h4></td><br>
				</tr>
				<tr>
					<td class="gmapfp_taille1">
						<?php
							$image=JURI::base().$config->get('gmapfp_chemin_img').$this->row->img;
							$clock=JURI::base().'components/com_gmapfp/images/clock_32.png';
							$printer=JURI::base().'components/com_gmapfp/images/printer.png';
							if ($this->row->img!=null) { ?> <a class="modal" href='<?php echo $image; ?>'><img src=<?php echo $image; ?> height="<?php echo $config->get('gmapfp_hauteur_img')?>"></a> <?php }; ?>
					</td>
					<td class="gmapfp_taille2">
				<?php if ($this->row->adresse!=null) {echo JText::_('GMAPFP_ADRESSE');?><span><?php echo $this->row->adresse;?></span><br /> <?php };?> 
				<?php if ($this->row->adresse2!=null) {echo JText::_('GMAPFP_ADRESSE');?><span><?php echo $this->row->adresse2;?></span><br /> <?php };?> 
				<?php if ($this->row->codepostal!=null) {echo JText::_('GMAPFP_CODEPOSTAL');?><span><?php echo $this->row->codepostal;?></span><br /> <?php };?> 
				<?php if ($this->row->ville!=null) {echo JText::_('GMAPFP_VILLE'); ?><span><?php  echo $this->row->ville;?></span><br /> <?php };?> 
				<?php if ($this->row->departement!=null) {echo JText::_('GMAPFP_DEPARTEMENT');?><span><?php echo $this->row->departement;?></span><br /> <?php };?> 
				<?php if ($this->row->pay!=null) {echo JText::_('GMAPFP_PAYS');?><span><?php echo $this->row->pay;?></span><br /> <?php };?> 
					</td>
					<td>
				<?php if ($this->row->tel!=null) {echo JText::_('GMAPFP_TEL');?><span><?php echo $this->row->tel;?></span><br /> <?php };?> 
				<?php if ($this->row->tel2!=null) {echo JText::_('GMAPFP_TEL');?><span><?php echo $this->row->tel2;?></span><br /> <?php };?> 
				<?php if ($this->row->fax!=null) {echo JText::_('GMAPFP_FAX');?><span><?php echo $this->row->fax;?></span><br /> <?php };?> 
				<?php if ($this->row->email!=null) {echo JText::_('GMAPFP_EMAIL');?><span><?php echo @JHTML::_('email.cloak',$this->row->email);?></span><br /> <?php };?> 
				<?php if ($this->row->web!=null) {
					if (substr($this->row->web,0,5)!="http:") {$lien_web = "http://".$this->row->web;} else {$lien_web = $this->row->web;};
					echo JText::_('GMAPFP_SITE_WEB');?> <span><a href= <?php echo $lien_web;?> target="_blank" > <?php echo $this->row->web;?> </a></span> <br /> <?php };?> 
				<?php if (JRequest::getVar('flag', 0, '0', 'int')==0) {
					if (($this->row->horaires_prix!=null)&&($config->get('gmapfp_afficher_horaires_prix')==1)) {
						$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=horaires_item&flag=1&id='.$this->row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int')) ?>
						<a class='modal' rel="{size: {x:550, y:400}, handler:'iframe'}" href="<?php echo $link ?>"  title="<?php echo JText::_('GMAPFP_HORAIRES_PRIX');?>"><img src=<?php echo $clock; ?> </a>&nbsp;&nbsp;&nbsp; <?php }; 
						$link = JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=item_carte&flag=1&id='.$this->row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int'));
							if (strpos($this->params->get('gmapfp_width'),'%')){
								$width_carte=600;
							}else{
								$width_carte=$this->params->get('gmapfp_width')+20;
							}
						if ($this->params->get('gmapfp_itineraire')==1) {
							$height_carte=$this->params->get('gmapfp_height')+170;
						}else{
							$height_carte=$this->params->get('gmapfp_height')+45;
						};
						$link =JRoute::_('index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=print_article&flag=1&id='.$this->row->id.'&Itemid='.JRequest::getVar('Itemid', 0, '', 'int')); ?>
						<a href="<?php echo $link ?>" class="modal" rel="{size: {x:<?php echo $width_carte; ?>, y:800}, handler:'iframe'}" title="<?php echo JText::_('GMAPFP_IMPRIMER');?>"><img src= <?php echo $printer; ?>  /></a>&nbsp;&nbsp;&nbsp;
					<?php };?>
							<?php
							$link4=substr($this->row->link,0,4);
							$link5=substr($this->row->link,0,5);
							$link9=substr($this->row->link,0,9);
							$link10=substr($this->row->link,0,10);
							$linkok=0;						
							if ((!empty($this->row->icon))||(!$this->row->icon='')) {
								if ($this->row->article_id>=1) {
									require_once JPATH_SITE . '/components/com_content/helpers/route.php';
									$linkmap=JRoute::_(ContentHelperRoute::getArticleRoute($this->row->article_slug, $this->row->article_id.':'.$this->row->article_alias, 0));
									$linkmap .= '?&tmpl=component';
									$linkok=1;
								};
								if (($link5=="http:")||($link4=="www.")||($link9=="index.php")) {
									$linkmap=$this->row->link;
									if ($link4=="www.") {$linkmap="http://".$linkmap;};
									if ($link10=="index.php?") {$linkmap=JURI::base().$linkmap."&tmpl=component";};
									$linkok=1;
								};
							$icon=JURI::base().'images/gmapfp/icons/'.$this->row->icon;
							};
							if (($linkok)&&($this->row->icon<>'')) { ?>
								<a href="<?php echo $linkmap ?>" class="modal" rel="{size: {x:800, y:800}, handler:'iframe'}" title="<?php echo $this->row->icon_label;?>"><img src= <?php echo $icon; ?>  /></a>&nbsp;&nbsp;&nbsp;
							<?php }; ?>
					</td>
				</tr>
			</table>
			<table class="gmapfp_message">
				<tr>
					<td>
					<?php
					if ($config->get('gmapfp_afficher_intro_italique')==1) { ?>
						<span><em><?php echo $this->row->intro; ?></em><?php echo $this->row->message; ?></span><?php ;
					} else { ?>
						<span><?php echo $this->row->intro; echo $this->row->message; ?></span><?php ;
					}?>
					 <br /> 
					 <br /> 
					</td>
				</tr>
			</table>
			<table
				<tr>
					<td>
						<?php echo $this->loadTemplate('form'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<div>
							<?php echo $this->map; ?>
						</div>
					</td>
				</tr>
			</table>
		<div\>
<?php };?>
