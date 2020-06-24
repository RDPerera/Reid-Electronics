<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'GMAPFP_DETAILS' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="title">
					<?php 
						echo JText::_( 'GMAPFP_NEW_AUTEUR' ); 
					?>:
				</label>
			</td>
			<td>
				<label for="title">
					<?php 
						foreach ($this->rows as $row){
							echo $row->nom.'<br/>'; 
							$cid[]=$row->id;
						}
					?>
				</label>
			</td>
			<td>
				<?php echo $this->lists['users']; ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_gmapfp" />
<input type="hidden" name="cid" value="<?php echo implode(',',$cid); ?>" />
<input type="hidden" name="task" value="view" />
<input type="hidden" name="controller" value="auteur" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
