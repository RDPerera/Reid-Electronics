<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.x
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
	<form action="index.php?option=com_gmapfp&controller=marqueurs&task=view" method="post" id="adminForm" name="adminForm" class="gmapfp">
		<div id="editcell">
			<table class="adminlist" width="100%">
				<thead>
					<tr>
						<th width="10%" align="left">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->marqueurs ); ?>);" />
						</th>
						<th width="10%" align="center">
							<?php echo JHTML::_('grid.sort',   'JPUBLISHED', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
						<th  width="50%" class="title" align="left">
							<?php echo JHTML::_('grid.sort',   'GMAPFP_NOM', 'nom', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						</th>
						<th  width="20%" class="title">
							<?php echo JText::_('GMAPFP_APERCU'); ?>
						</th>
					</tr>			
				</thead>
				<tbody>
					<?php
					$k = 0;
					for ($i=0, $n=count( $this->marqueurs ); $i < $n; $i++)
					{
						$row = &$this->marqueurs[$i];
						
						$published		= JHTML::_('grid.published', $row, $i );
						$checked = JHTML::_('grid.id',   $i, $row->id );
						$link = JRoute::_( 'index.php?option=com_gmapfp&controller=marqueurs&task=edit&cid[]='. $row->id );
				
						?>
						<tr class="<?php echo "row$k"; ?>">
							<td>
								<?php echo $checked; ?>
							</td>
							<td align="center">
								<?php echo $published;?>
							</td>
							<td>
								<a href="<?php echo $link; ?>"><?php echo $row->nom; ?></a>
							</td>
							<td align="center">
								<img src="<?php echo $row->url; ?>" title="<?php echo $row->nom; ?>" />
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
		<input type="hidden" name="controller" value="marqueurs" />
		<input type="hidden" name="task" value="view" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<div class="copyright" align="center">
		<br />
		<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
	</div>
</div>
