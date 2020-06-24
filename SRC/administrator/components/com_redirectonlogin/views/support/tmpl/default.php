<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 4.0.6
* @copyright Copyright (C) 2008 - 2019 Carsten Engel. All rights reserved.
* @license GPL versions free/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<form action="" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
	<?php endif; ?>	
	<div id="j-main-container"<?php echo empty($this->sidebar) ? '' : ' class="span10"'; ?>>
		<div class="clr"> </div><!-- needed for some admin templates -->					
		<fieldset class="adminform pi_wrapper_nice">
			<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_SUPPORT_INFO'); ?></legend>
			<table class="adminlist pi_table tabletop">	
				<tr>
					<td style="width: 10px;">
						1.
					</td>			
					<td>
						<a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_FAQS'); ?></a>
					</td>
					<td>
						<?php echo JText::_('COM_REDIRECTONLOGIN_FAQS_INFO'); ?>.
					</td>
				</tr>
				<tr>
					<td>
						2.
					</td>			
					<td>
						<a href="http://www.pages-and-items.com/forum/advsearch?catids=38" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_SEARCH_FORUM'); ?></a> 
					</td>
					<td>
						<?php echo JText::_('COM_REDIRECTONLOGIN_SEARCH_FORUM_INFO'); ?> Redirect-on-Login.
					</td>
				</tr>
				<tr>
					<td>
						3.
					</td>			
					<td>
						<a href="http://www.pages-and-items.com/forum/38-redirect-on-login" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_POST_FORUM'); ?></a>
					</td>
					<td>
						<?php echo JText::_('COM_REDIRECTONLOGIN_POST_FORUM_INFO'); ?> Redirect-on-Login.
					</td>
				</tr>
				<tr>
					<td>
						4.
					</td>			
					<td>
						<a href="http://www.pages-and-items.com/contact" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_CONTACT'); ?></a>
					</td>
					<td>
						<?php echo JText::_('COM_REDIRECTONLOGIN_CONTACT_INFO'); ?>.
					</td>
				</tr>
			</table>
		</fieldset>					
		<fieldset class="adminform pi_wrapper_nice">
		<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_UPDATE_NOTIFICATIONS'); ?></legend>			
		<table class="adminlist pi_table tabletop noimgmargin">	
			<tr>
				<td style="width: 10px;">
					<img src="components/com_redirectonlogin/images/mail.png" alt="mail" />
				</td>
				<td>
					<a href="http://www.pages-and-items.com/my-account/email-update-notifications" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_EMAIL_UPDATE_NOTIFICATIONS'); ?></a>
				</td>
			</tr>
			<tr>
				<td>
					<img src="components/com_redirectonlogin/images/rss.png" alt="rss" />
				</td>
				<td>
					<a href="http://www.pages-and-items.com/extensions/redirect-on-login/update-notifications-for-redirect-on-login" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_RSS'); ?></a>
				</td>
			</tr>
			<tr>
				<td>
					<img src="components/com_redirectonlogin/images/twitter.png" alt="twitter" />
				</td>
				<td>
					<a href="http://twitter.com/PagesAndItems" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_TWITTER'); ?> Twitter</a>
				</td>
			</tr>
		</table>
		</fieldset>					
		<fieldset class="adminform pi_wrapper_nice">
		<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_REVIEW'); ?></legend>		
		<table class="adminlist pi_table tabletop noimgmargin">		
			<tr>
				<td>
					<p>						
					<?php 
					echo JText::_('COM_REDIRECTONLOGIN_REVIEW_B'); 
					if($this->controller->get_version_type()=='pro'){
						$url_jed = '22806';
					}else{
						$url_jed = '15257';
					}		
					?>
					<a href="http://extensions.joomla.org/extensions/administration/admin-navigation/<?php echo $url_jed; ?>" target="_blank">
						Joomla! Extensions Directory</a>.
					</p>
				</td>
			</tr>
		</table>
		</fieldset>					
		<fieldset class="adminform pi_wrapper_nice">
			<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER'); ?></legend>							
			<table class="adminlist pi_table tabletop">	
				<tr>		
					<td>
						<?php echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER_INFO'); ?>.
						<br /><br />
						1. <?php echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER1'); ?><br />
						2. <?php echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER2'); ?><br />
						3. <?php echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER5'); ?><br />
						4. <?php echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER4'); ?>
					</td>
				</tr>
			</table>	
		</fieldset>				
		<fieldset class="adminform pi_wrapper_nice">
		<legend class="pi_legend"><?php echo JText::_('COM_INSTALLER_TYPE_COMPONENT'); ?> Admin Menu Manager</legend>		
		<table class="adminlist pi_table tabletop noimgmargin">		
			<tr>
				<td>
					<p>
					<br />	
					<?php echo JText::_('COM_REDIRECTONLOGIN_ADMIN_MENU_MANAGER'); ?>.	
					<br />
					<br />
					<?php
					if($this->helper->joomla_version >= '3.0'){
						$src = 'screenshot_amm_joomla3.png';
					}else{
						$src = 'screenshot_amm.jpg';
					}
					?>
					<a href="http://www.pages-and-items.com/extensions/admin-menu-manager" target="_blank">
						<img src="components/com_redirectonlogin/images/<?php echo $src; ?>" alt="Admin-Menu-Manager"  class="pi_imgborder" />
					</a>				
					<br /><br />
					<a href="http://www.pages-and-items.com/extensions/admin-menu-manager" target="_blank">
						<?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?>
					</a>
					</p>
				</td>
			</tr>
		</table>
		</fieldset>					
		<fieldset class="adminform pi_wrapper_nice">
		<legend class="pi_legend"><?php echo JText::_('COM_INSTALLER_TYPE_COMPONENT'); ?> Access Manager</legend>			
		<table class="adminlist pi_table tabletop noimgmargin">	
			<tr>
				<td>
					<p>
					<a href="http://www.pages-and-items.com/extensions/access-manager" target="_blank">
						<img src="components/com_redirectonlogin/images/screenshot_am2.jpg" alt="Access Manager" class="pi_imgborder" />
					</a>
					<br />
					<br />
					<a href="http://www.pages-and-items.com/extensions/access-manager" target="_blank">
						<img src="components/com_redirectonlogin/images/screenshot_am.jpg" alt="Access Manager" class="pi_imgborder" />
					</a>
					<br />
					<br />					
					<a href="http://www.pages-and-items.com/extensions/access-manager" target="_blank">Access Manager</a>
					<?php echo JText::_('COM_REDIRECTONLOGIN_ACCESS_MANAGER'); ?>:
					<ul class="show_bullets">
						<li><?php echo JText::_('COM_REDIRECTONLOGIN_VIEWING'); ?><br />(<?php echo JText::_('COM_REDIRECTONLOGIN_BASED_ON'); ?> Joomla <?php echo $this->controller->rol_strtolower(JText::_('MOD_MENU_COM_USERS_GROUPS')).' '.JText::_('COM_REDIRECTONLOGIN_OR').' '.$this->controller->rol_strtolower(JText::_('MOD_MENU_COM_USERS_LEVELS')); ?>)
							<ul>
								<li><?php echo $this->controller->rol_strtolower(JText::_('JGLOBAL_ARTICLES')); ?></li>
								<li><?php echo $this->controller->rol_strtolower(JText::_('JCATEGORIES')); ?></li>
								<li><?php echo JText::_('COM_INSTALLER_TYPE_TYPE_MODULE'); ?></li>
								<li><?php echo JText::_('COM_INSTALLER_TYPE_TYPE_COMPONENT'); ?></li>
								<li><?php echo $this->controller->rol_strtolower(JText::_('COM_MENUS_SUBMENU_ITEMS')); ?></li>
								<li><?php echo JText::_('COM_REDIRECTONLOGIN_PARTS_OF').' '.$this->controller->rol_strtolower(JText::_('JGLOBAL_ARTICLES')).' / '.$this->controller->rol_strtolower(JText::_('COM_MODULES_HEADING_TEMPLATES')); ?></li>
								<li><?php echo $this->controller->rol_strtolower(JText::_('JADMINISTRATOR')).' '.$this->controller->rol_strtolower(JText::_('COM_MENUS_SUBMENU_ITEMS')); ?></li>
							</ul>
						</li>
						<li><?php echo JText::_('COM_REDIRECTONLOGIN_EDITTING'); ?>
							<ul>
								<li><?php echo JText::_('COM_INSTALLER_TYPE_TYPE_MODULE'); ?></li>
								<li><?php echo JText::_('COM_INSTALLER_TYPE_TYPE_COMPONENT'); ?></li>
								<li><?php echo $this->controller->rol_strtolower(JText::_('COM_MENUS_SUBMENU_ITEMS')); ?></li>
								<li><?php echo JText::_('COM_INSTALLER_TYPE_TYPE_PLUGIN'); ?></li>
							</ul>
						</li>
					</ul>
					<br />
					<?php echo JText::_('COM_REDIRECTONLOGIN_ACCESS_MANAGER_B'); ?>.
					<br /><br />
					<a href="http://www.pages-and-items.com/extensions/access-manager" target="_blank">
						<?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?>
					</a>
					</p>
				</td>
			</tr>
		</table>
		</fieldset>	
		<fieldset class="adminform pi_wrapper_nice">	
		<legend class="pi_legend"><?php echo JText::_('COM_INSTALLER_TYPE_COMPONENT'); ?> User-Private-Page</legend>	
		<table class="adminlist pi_table pi_tableleft noimgmargin">		
			<tr>
				<td>
					<ul class="pi_show_bullets">
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_UPP_A'); ?>
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_UPP_B'); ?>
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_UPP_C'); ?>
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_UPP_D'); ?>
						</li>				
					</ul>				
					<br />	
					<a href="http://www.pages-and-items.com/extensions/user-private-page" target="_blank">
						<img src="components/com_redirectonlogin/images/screenshot-upp.jpg" alt="User-Private-Page" class="pi_imgborder" />
					</a>
					<br /><br />					
					<a href="http://www.pages-and-items.com/extensions/user-private-page" target="_blank" class="pi_font">
						<?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?>
					</a>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform pi_wrapper_nice">	
		<legend class="pi_legend"><?php echo JText::_('COM_INSTALLER_TYPE_COMPONENT'); ?> Dynamic-Menu-Links</legend>	
		<table class="adminlist pi_table pi_tableleft noimgmargin">		
			<tr>
				<td>
					<?php 
						echo JText::_('COM_REDIRECTONLOGIN_DML');
					?>:
					<ul class="pi_show_bullets">
						<li>
							<?php echo $this->controller->rol_strtolower(JText::_('COM_MODULES_OPTION_POSITION_USER_DEFINED')); ?>
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_USERGROUP'); ?>
						</li>
						<li>
							<?php echo $this->controller->rol_strtolower(JText::_('JDATE')).' / '.$this->controller->rol_strtolower(JText::_('MOD_STATS_TIME')); ?>
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_DEVICE'); ?>
						</li>
						<li>
							<?php echo $this->controller->rol_strtolower(JText::_('COM_CONTACT_FIELD_CONFIG_COUNTRY_LABEL')); ?>
						</li>
						<li>
							<?php echo $this->controller->rol_strtolower(JText::_('JGRID_HEADING_LANGUAGE')); ?>
						</li>
						<li>
							IP
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_CURRENTPAGE'); ?>
						</li>
						<li>
							<?php echo JText::_('COM_REDIRECTONLOGIN_ANDMUCHMORE'); ?>
						</li>							
					</ul>
					<br />
					<a href="http://www.pages-and-items.com/extensions/dynamic-menu-links" target="_blank">
						<img src="components/com_redirectonlogin/images/screenshot-dml.png" alt="Dynamic-Menu-Links" class="pi_imgborder" />
					</a>
					<br /><br />						
					<a href="http://www.pages-and-items.com/extensions/dynamic-menu-links" target="_blank" class="pi_font">
						<?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?>
					</a>
				</td>
			</tr>
		</table>
	</fieldset>	
	</div>	
</form>
