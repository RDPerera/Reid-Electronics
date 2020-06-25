<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.29F
	* Creation date: Juillet 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 

//fonction pour execution des plugins dans la personnalisation
$dispatcher = JDispatcher::getInstance(); 
JPluginHelper::importPlugin('content'); 

$mainframe = JFactory::getApplication();
$document   = JFactory::getDocument();

$active = $mainframe->getMenu()->getActive();

if ($active->params->get('menu-meta_description'))
{
	$document->setDescription($active->params->get('menu-meta_description'));
}

if ($active->params->get('menu-meta_keywords'))
{
	$document->setMetadata('keywords', $active->params->get('menu-meta_keywords'));
}
//largeur de la carte
	switch (substr($this->params->get('gmapfp_width'),-1)){
		case '%' :
			$width_carte  = $this->params->get('gmapfp_width');
			$width_carte2 = substr($this->params->get('gmapfp_width'),0 ,-1);
			$unite_carte  = '%';
			break;
		case 'x' :
			$width_carte  = $this->params->get('gmapfp_width');
			$width_carte2 = substr($this->params->get('gmapfp_width'),0 ,-2);
			$unite_carte  = 'px';
			break;
		default :
			$width_carte  = $this->params->get('gmapfp_width').'px';
			$width_carte2 = $this->params->get('gmapfp_width');
			$unite_carte  = 'px';
	}

//largeur de la liste
//si carte et listing les un en dessous des autres, la largeur de la liste = la largeur de la carte
	if (($this->params->get('gmapfp_position_liste')==2)or($this->params->get('gmapfp_position_liste')==3)) {
		$width_liste  = $width_carte;
		$width_liste2 = $width_carte2;
		$unite_liste  = $unite_carte;
	}else{
		switch (substr($this->params->get('gmapfp_width_list'),-1)){
			case '%' :
				$width_liste  = '200px';
				$width_liste2 = 200;
				$unite_liste  = 'px';
				break;
			case 'x' :
				$width_liste  = $this->params->get('gmapfp_width_list');
				$width_liste2 = substr($this->params->get('gmapfp_width_list'),0 ,-2);
				$unite_liste  = 'px';
				break;
			default :
				$width_liste  = $this->params->get('gmapfp_width_list').'px';
				$width_liste2 = $this->params->get('gmapfp_width_list');
				$unite_liste  = 'px';
		}
	}

//	$width_liste2 = $this->params->get('gmapfp_width_list');
	$nbre_col	= (int)$this->params->get('gmapfp_nombre_col');
	if (!$nbre_col){$nbre_col=1;};
	$width_col 	= ((int)($width_liste2/$nbre_col)).$unite_liste;

?>
<?php if ($this->params->get('show_page_heading')) : ?>
		<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
<?php endif;
echo '<div class="gmapfp">';
//affichage des filtres
if ($this->params->get('gmapfp_filtre')==1) :
	$layout = JRequest::getVar('layout', '', '', 'str');
	$layout_str='';
	if ($layout) {
		$catid = JRequest::getVar('catid', 0, '', 'int');
		$layout_str='&layout='.$layout.'&catid='.$catid;
	};
	$itemid = JRequest::getVar('Itemid', 0, '', 'int');
	$perso = JRequest::getVar('id_perso', 0, '', 'int');
	
	echo '<form action="'.JRoute::_('index.php?option=com_gmapfp&view=gmapfplist'.$layout_str.'&id_perso='.$perso.'&Itemid='.$itemid).'" method="post" name="adminForm">';
	?>
		<table  class="gmapfpform">
			<tr>
				<td width="60%">
					<?php echo JText::_( 'GMAPFP_FILTER' ); ?>:
					<input type="text" size="20" name="search_gmapfp" id="search_gmapfp" value="<?php echo $this->lists['search_gmapfp'];?>" class="text" onchange="document.adminForm.submit();"/>
					<button onclick="this.form.submit();"><?php echo JText::_( 'GMAPFP_GO_FILTER' ); ?></button>
					<button onclick="
						document.getElementById('search_gmapfp').value='';
						<?php if (@$this->lists['ville']) {?>document.adminForm.filtreville.value='-- <?php echo JText::_( 'GMAPFP_VILLE_FILTRE' ) ?> --'; <?php };?>
						<?php if (@$this->lists['departement']) {?>document.adminForm.filtredepartement.value='-- <?php echo JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ) ?> --'; <?php };?>
						<?php if (@$this->lists['pays']) {?>document.adminForm.filtrepays.value='-- <?php echo JText::_( 'GMAPFP_PAYS_FILTRE' ) ?> --'; <?php };?>
						<?php if (@$this->lists['categorie']) {?>document.adminForm.filtrecategorie.value='-- <?php echo JText::_( 'GMAPFP_CATEGORIE_FILTRE' ) ?> --'; <?php };?>
						this.form.submit();
					"><?php echo JText::_( 'GMAPFP_RESET' ); ?>
					</button>
				</td>
				<td width="40%">
					<?php
					if (@$this->lists['ville']) {echo $this->lists['ville'].'<br />';};
					if (@$this->lists['departement']) {echo $this->lists['departement'].'<br />';};
					if (@$this->lists['pays']) {echo $this->lists['pays'].'<br />';};
					if (@$this->lists['categorie']) {echo $this->lists['categorie'].'<br />';};
					?>
				</td>
			</tr>
		</table>
	</form>
<?php endif; 
//fin affichage des filtres
?>
<div <?php //echo $map_px; ?>>
<table class="blog<?php echo $this->params->get('pageclass_sfx'); ?>" cellpadding="0" cellspacing="0">
    <?php
	//carte en dessous du listing
	if ($this->params->get('gmapfp_position_liste')==2) {?>
        <tr style="vertical-align: top;" width="<?php echo $width_carte;?>">
        	<td>
				<div> <?php echo $this->map; ?> <div>
            </td>
        </tr>
    <?php };?>
    <tr>
    	<?php 
		//carte à gauche du listing
		if ($this->params->get('gmapfp_position_liste')==1) {?>
            <td style="vertical-align: top;" width="<?php echo $width_carte;?>">
                <div><?php echo $this->map;?> </div>
            </td>
        <?php };
        //listing
		?>
        <td style="vertical-align: top;" >
            <div class="gmapfp_enveloppe_liste" style="overflow:auto; <?php 
				if ($this->params->get('gmapfp_position_liste')<2) { 
					echo 'width:'.($width_liste2+22).'px; '; 
					echo 'height:'.($this->params->get('gmapfp_height')+0).'px; '; 
				} ?>
            ">
            <div class="gmapfp_liste" >
		<?php 
			if (!empty($this->perso->intro_detail)) {
				$article->text=$this->perso->intro_detail; 
				$results = $dispatcher->trigger('onContentPrepare', array ('com_gmapfp', & $article, & $this->params, 0));
				echo $article->text;
			}
            $compte = 0;
			$index_list = 0;
			$decale=false;
			echo '<table  style="width:'.$width_liste.'"><tr>';
            foreach ($this->rows as $row) : ?>
            	<td nowrap="nowrap" class="gmapfp_article_listing_<?php $index_list++; echo (($index_list%2) XOR $decale); ?>">
                <?php 
                if (empty($row->glat)or empty($row->glng)) {
					echo '<span class="sidebar">';
				}else{
                    echo '<span class="sidebar" onmouseover=\'google.maps.event.trigger(marker['.$compte.'],"mouseover")\' onmousedown=\'google.maps.event.trigger(marker['.$compte.'],"mousedown")\' onmouseout=\'google.maps.event.trigger(marker['.$compte.'],"mouseout")\' >';
					$compte ++;
				};
                $affichage = "";
                if ($this->params->get('gmapfp_view_marqueur'))
                	$affichage="<img src=".$row->marqueur.">";
                if ($this->params->get('gmapfp_view_ville'))
                    $affichage .= $row->ville." : ";
                echo $affichage.$row->nom; 
                ?>
                	</span>
            	</td>
            <?php 
			if ($index_list>=$nbre_col) { 
				echo '</tr><tr>';
				$index_list=0;
				$decale=!$decale;
			};

			endforeach;?>
            </tr></table>
            </div>
            </div>
        </td>
    	<?php 
		//carte dà droite du listing
		if ($this->params->get('gmapfp_position_liste')==0) {?>
            <td style="vertical-align: top;" width="<?php echo $width_carte;?>">
                <div>   <?php echo $this->map;?></div>
            </td>
        <?php };?>
    </tr>
    <?php 
	//carte sous le listing
	if ($this->params->get('gmapfp_position_liste')==3) {?>
        <tr style="vertical-align: top;" width="<?php echo $width_carte;?>">
        	<td>
				<div> <?php echo $this->map; ?> <div>
            </td>
        </tr>
    <?php };?>
</table>
</div>
<?php
if (!empty($this->perso->conclusion_detail)) {
	$article->text=$this->perso->conclusion_detail; 
	$results = $dispatcher->trigger('onContentPrepare', array ('com_gmapfp', & $article, & $this->params, 0));
	echo $article->text;
}
?>
<div\>