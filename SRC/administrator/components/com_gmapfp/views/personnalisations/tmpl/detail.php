<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_41F
	* Creation date: Mai 2016
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

$editor = JFactory::getEditor();
$config = JComponentHelper::getParams('com_gmapfp'); 
?>

<link rel="stylesheet" href="components/com_gmapfp/views/general.css" type="text/css" /> 
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		// do field validation
		if (form.nom.value == "") {
			alert( "<?php echo JText::_( 'GMAPFP_NOM_NON_VIDE', true ); ?>" );
		} else {
			<?php
			echo $editor->save( 'text_intro_detail' );
			echo $editor->save( 'text_conclusion_detail' );
			echo $editor->save( 'text_intro_carte' );
			echo $editor->save( 'text_conclusion_carte' );
			?>
			submitform( pressbutton );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="gmapfp">
	<div>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'GMAPFP_DETAILS' ); ?></legend>
			<table class="admintable">
				<tr>
					<td width="110" class="key">
						<label for="title">
							<?php echo JText::_( 'GMAPFP_NOM' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="nom" id="nom" size="60" value="<?php echo $this->personnalisations->nom; ?>" />
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
						<?php echo JText::_( 'GMAPFP_INTRO_CARTE' ); ?>:
						</label>
					</td>
					<td valign="top" class="inputbox">
						<?php
						echo $editor->display( 'text_intro_carte', $this->personnalisations->intro_carte, '100%', '200', '75', '20', false);
						?>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
						<?php echo JText::_( 'GMAPFP_CONCLUSION_CARTE' ); ?>:
						</label>
					</td>
					<td valign="top" class="inputbox">
						<?php
						echo $editor->display( 'text_conclusion_carte', $this->personnalisations->conclusion_carte, '100%', '200', '75', '20', false);
						?>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
						<?php echo JText::_( 'GMAPFP_INTRO_DETAIL' ); ?>:
						</label>
					</td>
					<td valign="top" class="inputbox">
						<?php
						echo $editor->display( 'text_intro_detail', $this->personnalisations->intro_detail, '100%', '200', '75', '20', false);
						?>
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="title">
						<?php echo JText::_( 'GMAPFP_CONCLUSION_DETAIL' ); ?>:
						</label>
					</td>
					<td valign="top" class="inputbox">
						<?php
						echo $editor->display( 'text_conclusion_detail', $this->personnalisations->conclusion_detail, '100%', '200', '75', '20', false);
						?>
					</td>
				</tr>
				<tr>
					<td width="120" class="key">
						<?php echo JText::_( 'JPUBLISHED' ); ?>:
					</td>
					<td >
						<?php echo JHTML::_( 'select.booleanlist',  'published', 'class="inputbox"', $this->personnalisations->published ); ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<div class="clr"></div>

	<input type="hidden" name="option" value="com_gmapfp" />
	<input type="hidden" name="id" value="<?php echo $this->personnalisations->id; ?>" />
	<input type="hidden" name="task" value="edit" />
	<input type="hidden" name="controller" value="personnalisations" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<div class="copyright" align="center">
	<br />
	<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
</div>
