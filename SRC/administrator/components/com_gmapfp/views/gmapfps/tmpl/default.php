<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_51F
	* Creation date: Novembre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 
	
$ordering = ($this->lists['order'] == 'a.ordering');
JHTML::_('behavior.tooltip');

$saveOrder	= @$this->lists['order'] == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_gmapfp&task=gmapfp.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower(@$this->lists['order_Dir']), $saveOrderingUrl);
}

?>
<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
	<form action="index.php?option=com_gmapfp&controller=gmapfp&task=view" method="post" id="adminForm" name="adminForm">
		<table  class="adminform">
			<tr>
				<td width="100%" class="small">
					<?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?>
					<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="small inputbox" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?></button>
					<button onclick="document.getElementById('search').value='';
							this.form.getElementById('filtreville').value='-- <?php echo JText::_( 'GMAPFP_VILLE_FILTRE' ) ?> --'; 
							this.form.getElementById('filtredepartement').value='-- <?php echo JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ) ?> --';
							this.form.getElementById('filtrecategorie').value=0;
							this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?></button>
				</td>
				<td nowrap="nowrap">
					<?php
					echo $this->lists['ville'];
					echo $this->lists['departement'];
					echo $this->lists['categorie'];
					?>
				</td>
			</tr>
		</table>
	<div id="editcell">
		<table class="table table-striped" id="articleList">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', @$this->lists['order_Dir'], @$this->lists['order'], null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
				</th>
				<th width="5%" align="center">
					<?php echo JHTML::_('grid.sort',   'JPUBLISHED', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th  width="30%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'GMAPFP_NOM', 'nom', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th  width="20%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'GMAPFP_VILLE', 'ville', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th  width="15%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'GMAPFP_DEPARTEMENT', 'departement', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th  width="15%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'GMAPFP_PAYS', 'pay', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th  width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'JAUTHOR', 'auteur', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'JGRID_HEADING_ID', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			$row = &$this->items[$i];
			
			$published	= JHTML::_('grid.published', $row, $i );
			$checked 	= JHTML::_('grid.id',   $i, $row->id );
			$link 		= JRoute::_( 'index.php?option=com_gmapfp&controller=gmapfp&task=edit&cid[]='. $row->id );

			?>
			<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $row->catid?>">
				<td class="order nowrap center hidden-phone">
				<?php
					$disableClassName = '';
					$disabledLabel	  = '';

					if (!$saveOrder) :
						$disabledLabel    = JText::_('JORDERINGDISABLED');
						$disableClassName = 'inactive tip-top';
					endif; ?>
					<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
						<i class="icon-menu"></i>
					</span>
					<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $row->ordering;?>" class="width-20 text-area-order " />
				</td>
				<td>
					<?php echo $checked; ?>
				</td>
				<td align="center">
					<?php echo $published;?>
				</td>
				<td>
					<a href="<?php echo $link; ?>"><?php echo $row->nom; ?></a>
					<div class="small">
						<?php echo JText::_('JCATEGORY') . ": " . $this->escape($row->title); ?>
					</div>
				</td>
				<td>
					<?php echo $row->ville; ?>
				</td>
				<td>
					<?php echo $row->departement; ?>
				</td>
				<td>
					<?php echo $row->pay; ?>
				</td>
				<td>
					<a href="mailto:<?php echo $row->auteur_mail; ?>"><?php echo $row->auteur; ?></a>
				</td>
				<td align="center">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
			<tfoot>
				<tr>
					<td colspan="15">
						<?php echo $this->pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	<div class="copyright" align="center">
		<?php 
		$langue		=substr((@$lang->getTag()),0,2);
		if ($langue!='fr') $langue = 'en';
		echo '<h1 style="color:red;">'.JText::_( 'GMAPFP_DISCOVER_PRO_VERSION' ).' : '.'</h1>'; ?>
		<a href="http://pro.gmapfp.org/<?php echo $langue; ?>" target="_new"><?php echo '<h1 style="color:red; text-decoration: underline;">'.JText::_( 'GMapFP Pro' ).'</h1>'; ?></a>
		<br />
		<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
	</div>
	</div>
	<input type="hidden" name="option" value="com_gmapfp" />
	<input type="hidden" name="task" value="view" />
	<input type="hidden" name="controller" value="gmapfp" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
