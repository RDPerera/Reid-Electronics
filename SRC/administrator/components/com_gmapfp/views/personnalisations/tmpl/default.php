<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_50F
	* Creation date: Octobre 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 

?>
<?php if (!empty( $this->sidebar)) : ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
	<form action="index.php?option=com_gmapfp" method="post" id="adminForm" name="adminForm">
		<div id="editcell">
			<table class="adminlist" width="100%">
				<thead>
					<tr>
						<th width="10%">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->personnalisations ); ?>);" />
						</th>
						<th width="15%" align="center">
							<?php echo JHTML::_('grid.sort',   'JPUBLISHED', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
						<th  width="55%" nowrap="nowrap">
							<?php echo JHTML::_('grid.sort',   'GMAPFP_NOM', 'nom', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
						<th width="10%" nowrap="nowrap">
							<?php echo JHTML::_('grid.sort',   'ID', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
					</tr>
				</thead>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->personnalisations ); $i < $n; $i++)
				{
					$row = &$this->personnalisations[$i];
					
					$published		= JHTML::_('grid.published', $row, $i );
					$checked 	= JHTML::_('grid.id',   $i, $row->id );
					$link 		= JRoute::_( 'index.php?option=com_gmapfp&controller=personnalisations&task=edit&cid[]='. $row->id );

					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $checked; ?>
						</td>
						<td align="center">
							<?php echo $published;?>
						</td>
						<td align="center">
							<a href="<?php echo $link; ?>"><?php echo $row->nom; ?></a>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				<tfoot>
					<tr>
						<td colspan="15">
							<?php echo $this->pageNav->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="copyright" align="center">
				<br />
				<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
			</div>
		</div>
		<input type="hidden" name="option" value="com_gmapfp" />
		<input type="hidden" name="task" value="view" />
		<input type="hidden" name="controller" value="personnalisations" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>