<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.23F
	* Creation date: Janvier 2015
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 

$ordering = ($this->lists['order'] == 'a.ordering');
JHTML::_('behavior.tooltip');
	
$saveOrder 	= ($this->lists['order'] == 'a.ordering');
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_gmapfp&task=gestionlieux.saveOrderAjax&tmpl=component';
	$saveOrderingUrl = JUri::base().'index.php?option=com_gmapfp&controller=gestionlieux&task=saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower(@$this->lists['order_Dir']), $saveOrderingUrl);
}

if ($this->params->get('show_page_heading')) : ?>
		<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
<?php endif;

?>
<div style="height:70px">
<div class="toolbar" id="toolbar"> 
    <table class="toolbar"><tr> 
        <td> 
            <button name="publish" class="button" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_( 'GMAPFP_CHOISIR_DANS_LISTE' ).' '.JText::_( 'JPUBLISHED' ); ?>');}else{  submitbutton('publish')}">
                <span class="icon-32-publish" title="<?php echo JText::_( 'JPUBLISHED' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'JPUBLISHED' ).'&nbsp;'; ?>
            </button>
        </td> 
        <td> 
            <button name="unpublish" class="button" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_( 'GMAPFP_CHOISIR_DANS_LISTE' ).' '.JText::_( 'JUNPUBLISHED' ); ?>');}else{  submitbutton('unpublish')}">
                <span class="icon-32-unpublish" title="<?php echo JText::_( 'JUNPUBLISHED' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'JUNPUBLISHED' ).'&nbsp;'; ?>
            </button>
        </td> 
        <td> 
            <button name="copy" class="button" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_( 'GMAPFP_CHOISIR_DANS_LISTE' ).' '.JText::_( 'GMAPFP_COPIER' ); ?>');}else{  submitbutton('copy')}">
                <span class="icon-32-copy" title="<?php echo JText::_( 'GMAPFP_COPIER' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'GMAPFP_COPIER' ).'&nbsp;'; ?>
            </button>
        </td> 
        <td> 
            <button name="delete" class="button" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_( 'GMAPFP_CHOISIR_DANS_LISTE' ).' '.JText::_( 'JACTION_DELETE' ); ?>');}else{  submitbutton('remove')}">
                <span class="icon-32-delete" title="<?php echo JText::_( 'JACTION_DELETE' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'JACTION_DELETE' ).'&nbsp;'; ?>
            </button>
        </td> 
        <td> 
            <button name="edit" class="button" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_( 'GMAPFP_CHOISIR_DANS_LISTE' ).' '.JText::_( 'JACTION_EDIT' ); ?>');}else{  submitbutton('edit')}">
                <span class="icon-32-edit" title="<?php echo JText::_( 'JACTION_EDIT' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'JACTION_EDIT' ).'&nbsp;'; ?>
            </button>
        </td> 
        <td> 
            <button name="add" class="button" onclick="javascript: submitbutton('add')">
                <span class="icon-32-new" title="<?php echo JText::_( 'GMAPFP_NEW' ); ?>"> 
                </span> 
                <?php echo '&nbsp;'.JText::_( 'GMAPFP_NEW' ).'&nbsp;'; ?>
            </button>
        </td> 
    </tr></table> 
</div>
</div>
<form action=<?php echo JRoute::_('index.php?option=com_gmapfp&view=gestionlieux&controller=gestionlieux&task=view') ?> method="post" name="adminForm" id="adminForm">
	<table  class="adminform">
		<tr>
			<td nowrap="nowrap">
				<?php echo JText::_( 'GMAPFP_FILTER' ); ?>:
				<input type="text" name="search" id="search_gmapfp" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();" class="button"><?php echo '&nbsp;'.JText::_( 'GMAPFP_GO_FILTER' ).'&nbsp;'; ?></button>
				<button onclick="document.getElementById('search_gmapfp').value='';
                        this.form.getElementById('filtreville').value='-- <?php echo JText::_( 'GMAPFP_VILLE_FILTRE' ) ?> --';
                        this.form.getElementById('filtredepartement').value='-- <?php echo JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ) ?> --';
                        this.form.getElementById('filtrecategorie').value='-- <?php echo JText::_( 'GMAPFP_CATEGORIE_FILTRE' ) ?> --';
		                this.form.submit();" class="button"><?php echo '&nbsp;'.JText::_( 'GMAPFP_RESET' ).'&nbsp;'; ?></button>
			</td>
			<td>
				<?php
				echo $this->lists['departement'];
				echo $this->lists['ville'];
				echo $this->lists['categorie'];
				?>
			</td>
		</tr>
	</table>
<div id="editcell">
	<table class="table table-striped" id="articleList">
	<thead>
		<tr>
			<th width="20">
				<?php echo JText::_( 'JGLOBAL_DISPLAY_NUM' ); ?>
			</th>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', @$this->lists['order_Dir'], @$this->lists['order'], null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
			<th width="5%" align="center">
				<?php echo JHTML::_('grid.sort',   'JPUBLISHED', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th  width="40%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'GMAPFP_NOM', 'nom', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th  width="30%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'GMAPFP_VILLE', 'ville', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th  width="20%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'GMAPFP_DEPARTEMENT', 'departement', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
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
		
		$published	= $this->published($row, $i);
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_gmapfp&view=editlieux&layout=edit_form&controller=editlieux&task=edit&cid='. $row->id.':'.$row->alias );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pageNav->getRowOffset( $i ); ?>
			</td>
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
</div>
<input type="hidden" name="option" value="com_gmapfp" />
<input type="hidden" name="task" value="view" />
<input type="hidden" name="controller" value="gestionlieux" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
<form\>