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
JHTML::_('behavior.tooltip');
$template_path = JPATH_COMPONENT_SITE.'/views/gmapfp/gmapfp.css';
if ($fp = @fopen($template_path, 'r')) {
	$csscontent = @fread($fp, @filesize($template_path));
	$csscontent = htmlspecialchars($csscontent);
} else {
	echo 'Error reading template file: '.$template_path;
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
	<form action="index.php" method="post" name="adminForm" id="adminForm">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>
				<p><?php echo JText::_( 'GMAPFP_CSS_DETAIL' ); ?></p>
				<table class="adminform">
				<tr>
					<th>
					<?php echo $template_path; ?>
					<span class="componentheading">
					<?php
					echo is_writable($template_path) ? ' - <strong style="color:green;">'.JText::_( 'GMAPFP_CSS_WRITABLE' ).'</strong>' :'<strong style="color:red;">'.JText::_( 'GMAPFP_CSS_NOT_WRITABLE' ).'</strong>';?>
					</span>
					</th>
				</tr>
				<tr>
					<td>
						<textarea style="width: 100%; height: 600px" cols="80" rows="25" name="csscontent" class="inputbox"><?php echo $csscontent; ?></textarea>
					</td>
				</tr>
			</table>
		<input type="hidden" name="option" value="com_gmapfp" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="css" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<div class="copyright" align="center">
		<br />
		<?php echo JText::_( 'GMAPFP_COPYRIGHT' );?>
	</div>
</div>