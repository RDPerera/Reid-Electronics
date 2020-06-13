<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3.29F
	* Creation date: Juillet 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 

$height_msg = '500px';
$width_msg = '400px';

//fonction pour execution des plugins dans la personnalisation
$dispatcher = JDispatcher::getInstance(); 
JPluginHelper::importPlugin('content'); 

$mainframe = JFactory::getApplication(); 
$active = $mainframe->getMenu()->getActive();

if ($active->params->get('menu-meta_description'))
{
	$this->document->setDescription($active->params->get('menu-meta_description'));
}

if ($active->params->get('menu-meta_keywords'))
{
	$this->document->setMetadata('keywords', $active->params->get('menu-meta_keywords'));
}

?>
<?php if ($this->params->get('show_page_heading')) : ?>
		<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
<?php endif; ?>
<div class="gmapfp">
<?php
if ($this->params->get('gmapfp_filtre')==1) :
$itemid = JRequest::getVar('Itemid', 0, '', 'int');
$perso = JRequest::getVar('id_perso', 0, '', 'int');
?>
	<form action="<?php echo JRoute::_('index.php?option=com_gmapfp&view=gmapfp&id_perso='.$perso.'&Itemid='.$itemid); ?>" method="post" name="adminForm">
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
					<br />
				</td>
			</tr>
		</table>
	</form>
	<?php endif; ?>
		<div style="overflow: auto;">
		<?php if ((($this->params->get('type_affichage'))==0)||(($this->params->get('type_affichage'))==2)) : 
			echo $this->map;
		endif;
		?>
		</div>
	<?php 
	if (!empty($this->perso->intro_detail)) {
		$article = new stdclass;
		$article->text=$this->perso->intro_detail; 
			$results = $dispatcher->trigger('onContentPrepare', array ('com_gmapfp', & $article, & $this->params, 0)); 
		echo $article->text;
	}
	?>
	<div class="blog<?php echo $this->params->get('pageclass_sfx');?>">
	<?php
	 
	if (($this->params->get('type_affichage')<>2)&&($this->params->get('nombre_articles'))) : ?>
		<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('nombre_articles')); $i++) : 
			if ($i >= $this->total) : break; endif;
			$this->lieu = $this->lieux[$i];
			//affichage du détail d'un lieu
			$this->_layout='tmpl';
			echo JViewLegacy::Display('article');
			$this->_layout='default';
		endfor;
	?>
	<?php else : $i = $this->pagination->limitstart; endif; ?>
	</div>

	<?php if (($this->params->get('show_pagination') or $this->params->get('show_pagination_results'))&&(($this->params->get('type_affichage'))<>2)) : ?>
		<div class="pagination">
			<?php 
				if ($this->params->get('show_pagination_results'))
					echo '<p class="counter">'.$this->pagination->getPagesCounter().'</p>';
				if ($this->params->get('show_pagination'))
					echo $this->pagination->getPagesLinks(); 
			?>
			<br /><br />
		</div>
	<?php endif; ?>
	<?php
	if (!empty($this->perso->conclusion_detail)) {
		$article = new stdclass;
		$article->text=$this->perso->conclusion_detail; 
		$results = $dispatcher->trigger('onContentPrepare', array ('com_gmapfp', & $article, & $this->params, 0)); 
		echo $article->text;
	}
	?>
	<div style="overflow: auto;">
	<?php if (($this->params->get('type_affichage'))==1) : 
		echo $this->map;
	endif;
	?>
	</div >
<div\>
