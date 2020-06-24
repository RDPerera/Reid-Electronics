<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: default.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

?>
<div id="m_wrapper">
<div id="cpanel">
	<div class="icon">
		<a href="index.php?option=com_creativeimageslider&view=creativesliders" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_SLIDERS' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/sliders.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_SLIDERS' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon">
		<a href="index.php?option=com_creativeimageslider&view=creativeimages" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_IMAGES' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/images.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_IMAGES' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon">
		<a href="index.php?option=com_creativeimageslider&view=creativecategories" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_CATEGORIES' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/category.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_CATEGORIES' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel" style="display: none;">
	<div class="icon">
		<a href="index.php?option=com_creativeimageslider&view=creativetemplates" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_TEMPLATES' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/template.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_TEMPLATES' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>

<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/shopping_cart.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_BUY_PRO_VERSION' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_RATE_US_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_RATE_US_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/icon-star-48.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_RATE_US' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_SUPPORT_FORUM_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_SUPPORT_FORUM_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/forum.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_SUPPORT_FORUM' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_PROJECT_HOMEPAGE_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_PROJECT_HOMEPAGE_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativeimageslider/assets/images/project.png" /><br />
						<?php echo JText::_( 'COM_CREATIVEIMAGESLIDER_SUBMENU_PROJECT_HOMEPAGE' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>


<?php include (JPATH_BASE.'/components/com_creativeimageslider/helpers/footer.php'); ?>
</div>
