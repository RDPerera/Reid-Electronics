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

$checked = 'checked="checked"';

?>
<script language="JavaScript" type="text/javascript">

Joomla.submitbutton = function(task){	
	submitform(task);	
}

function do_tab_session(id, href){	
	pos_tabname = href.indexOf("#");
	href_length = href.length;
	tabname = href.substring(pos_tabname+1, href_length);
	var JNC_jQuery = jQuery.noConflict();	
	ajax_url = "index.php?option=com_redirectonlogin&task=tab_session_save&format=raw&id="+id+"&active="+tabname;	
	JNC_jQuery.ajax({	  
		url: ajax_url	   
	});	
}

</script>
<form action="" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
	<?php endif; ?>	
	<div id="j-main-container"<?php echo empty($this->sidebar) ? '' : ' class="span10"'; ?>>
		<div class="clr"> </div><!-- needed for some admin templates -->		
		<div class="fltlft">
			<?php 				
			$tabs = array('frontend', 'backend');//compatibility with j2.5
			redirectonloginHelper::tab_set_start('rol_allusers', 'frontend', 1, $tabs); 
			redirectonloginHelper::tab_add('rol_allusers', 'frontend', JText::_('COM_REDIRECTONLOGIN_FRONTEND')); 
			?>						
			<a name="frontend"></a>
			<fieldset class="adminform pi_wrapper_nice">
				<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_FRONTEND'); ?></legend>				
				<table class="adminlist pi_table tabletop">					
					<tr>
						<td style="width: 250px;">
							<label class="hasTip required"><?php echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_LOGIN'); ?></label>
						</td>
						<td colspan="3">
							<?php echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_INFO_FRONTEND'); ?>.
						</td>					
					</tr>
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap" style="width: 150px;">
							<label><input type="radio" name="redirect_type_frontend" value="none" <?php if($this->controller->rol_config['redirect_type_frontend']=='none'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL'); ?></label>
						</td>
						<td colspan="2">
							<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL').' Joomla '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGIN')); ?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend" value="same" <?php if($this->controller->rol_config['redirect_type_frontend']=='same'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_SAME_PAGE'); ?></label>
						</td>
						<td colspan="2">
							
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['redirect_type_frontend']=='menuitem' && !$this->menuitem_login){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_MENUITEM_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend" value="menuitem" <?php if($this->controller->rol_config['redirect_type_frontend']=='menuitem'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_MENUITEM'); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->menuitem_login_select; ?>
						</td>
					</tr>				
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['redirect_type_frontend']=='url' && $this->controller->rol_config['redirect_url_frontend']==''){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_URL');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend" value="url" <?php if($this->controller->rol_config['redirect_type_frontend']=='url'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
						</td>
						<td colspan="2">
							<input type="text" name="redirect_url_frontend" style="width: 450px;" value="<?php echo $this->controller->rol_config['redirect_url_frontend'];?>" /> <?php echo JText::_('COM_REDIRECTONLOGIN_URL_FULL'); ?>.
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['redirect_type_frontend']=='dynamic' && !$this->dynamic_login){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend" value="dynamic" <?php if($this->controller->rol_config['redirect_type_frontend']=='dynamic'){echo $checked;}?> /> 
							<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
						</td>
						<td>
							<?php echo $this->dynamic_login_select; ?>
						</td>
						<td>
							<?php
								if($this->controller->get_version_type()=='free'){
									echo '<div style="color: red;">';
									echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
									echo '</div>';
								}
							?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend" value="logout" <?php if($this->controller->rol_config['redirect_type_frontend']=='logout'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_BLOCK_LOGIN'); ?></label>
						</td>
						<td colspan="2">
							<?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_A'); ?>. <?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_B'); ?> <a href="index.php?option=com_redirectonlogin&view=configuration&tab=frontend#messages_frontend"><?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_CONFIGURATION')); ?></a>.																											
						</td>
					</tr>				
					<tr>
						<td colspan="4">&nbsp;
							<a name="opening_site"></a>
						</td>
					</tr>
					<tr>
						<td class="rol_nowrap">
							<?php echo JText::_('JDEFAULT').' '.JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE'); ?>
						</td>
						<td colspan="3">
							<?php 
							if($this->controller->get_version_type()=='free'){
								echo '<div style="color: red;">';
								echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
								echo '</div>';
							}
							echo JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE_INFO').' '.JText::_('COM_REDIRECTONLOGIN_SESSION'); ?>. <?php echo JText::_('COM_REDIRECTONLOGIN_UNLESS_OVERRULED_B'); ?>.
						</td>					
					</tr>
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="opening_site" value="no" <?php if($this->controller->rol_config['opening_site']=='no' || $this->controller->rol_config['opening_site']==''){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_NO'); ?></label>
						</td>
						<td colspan="2">
							
						</td>
					</tr>
					<tr>
						<td>&nbsp;
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="opening_site" value="yes" <?php if($this->controller->rol_config['opening_site']=='yes'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_YES'); ?></label>
						</td>
						<td colspan="2" style="display: none;">
							<label><input type="radio" name="opening_site_type" value="loggedin" <?php if($this->controller->rol_config['opening_site_type']=='loggedin' || $this->controller->rol_config['opening_site_type']==''){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_ONLY_STILL_LOGGEDIN'); ?></label>					
						</td>
					</tr>
					<tr style="display: none;">
						<td>&nbsp;
							
						</td>
						<td>&nbsp;
													
						</td>
						<td colspan="2">					
							<label><input type="radio" name="opening_site_type" value="notloggedin" <?php if($this->controller->rol_config['opening_site_type']=='notloggedin'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_ONLY_GUESTS'); ?></label>
						</td>
					</tr>	
					<tr style="display: none;">
						<td>&nbsp;
							
						</td>
						<td>&nbsp;
													
						</td>
						<td colspan="2">						
							<label><input type="radio" name="opening_site_type" value="all" <?php if($this->controller->rol_config['opening_site_type']=='all'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_ALL_USERS_SITE_OPEN_B'); ?></label>						
						</td>
					</tr>	
					<tr>
						<td>&nbsp;
							
						</td>
						<td>&nbsp;
													
						</td>
						<td colspan="2">						
							<label><input type="checkbox" name="opening_site_home" value="1" <?php if($this->controller->rol_config['opening_site_home']){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_SITE_HOME'); ?></label>
						</td>
					</tr>
					<tr>
						<td>&nbsp;						
						</td>
						<td style="text-align: right; color: red;"  class="rol_nowrap">
							<?php 
							if($this->controller->rol_config['opening_site']=='yes' && !$this->controller->rol_config['menuitem_open'] && $this->controller->rol_config['opening_site_type2']=='menuitem'){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_MENUITEM_SELECTED');							
							}									
							?>						
						</td>
						<td width="150">						
							<label><input type="radio" name="opening_site_type2" value="menuitem" <?php if($this->controller->rol_config['opening_site_type2']=='menuitem'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_MENUITEM'); ?></label>
						</td>
						<td>						
							<?php echo $this->menuitem_open_select; ?>
						</td>
					</tr>	
					<tr>
						<td>&nbsp;						
						</td>
						<td style="text-align: right; color: red;" class="rol_nowrap">
							<?php 
							if($this->controller->rol_config['opening_site']=='yes' && $this->controller->rol_config['opening_site_url']=='' && $this->controller->rol_config['opening_site_type2']=='url'){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_URL');							
							}									
							?>						
						</td>
						<td width="150">						
							<label><input type="radio" name="opening_site_type2" value="url" <?php if($this->controller->rol_config['opening_site_type2']=='url'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
						</td>
						<td>						
							<input type="text" name="opening_site_url" style="width: 450px;" value="<?php echo $this->controller->rol_config['opening_site_url'];?>" /> <?php echo JText::_('COM_REDIRECTONLOGIN_URL_FULL'); ?>.	
						</td>
					</tr>				
					<tr>
						<td>&nbsp;						
						</td>
						<td style="text-align: right; color: red;" class="rol_nowrap">
							<?php 
							if($this->controller->rol_config['opening_site']=='yes' && !$this->controller->rol_config['dynamic_open'] && $this->controller->rol_config['opening_site_type2']=='dynamic'){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
							}									
							?>						
						</td>
						<td width="150">						
							<label><input type="radio" name="opening_site_type2" value="dynamic" <?php if($this->controller->rol_config['opening_site_type2']=='dynamic'){echo $checked;}?> /> 
							<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
						</td>
						<td>						
							<?php echo $this->dynamic_open_select; ?>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<a name="frontend_logout"></a>
							&nbsp;													
						</td>
					</tr>
					<tr>
						<td>
							<label><?php echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_LOGOUT'); ?></label>
						</td>
						<td colspan="3">
							<?php 
							if($this->controller->get_version_type()=='free'){
								echo '<div style="color: red;">';
								echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
								echo '</div>';
							}
							echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_INFO_FRONTEND_LOGOUT'); ?>.
						</td>					
					</tr>
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend_logout" id="type_frontend_none_logout" value="none" <?php if($this->controller->rol_config['redirect_type_frontend_logout']=='none'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL'); ?></label>
						</td>
						<td colspan="2">
							<?php 
								echo JText::_('COM_REDIRECTONLOGIN_NORMAL').' Joomla '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGOUT'));
							?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend_logout" id="type_frontend_same_logout" value="same" <?php if($this->controller->rol_config['redirect_type_frontend_logout']=='same'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_SAME_PAGE'); ?></label>
						</td>
						<td colspan="2">						
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['redirect_type_frontend_logout']=='menuitem' && !$this->controller->rol_config['menuitem_logout']){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_MENUITEM_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend_logout" value="menuitem" <?php if($this->controller->rol_config['redirect_type_frontend_logout']=='menuitem'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_MENUITEM'); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->menuitem_logout_select; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['redirect_type_frontend_logout']=='url' && $this->controller->rol_config['redirect_url_frontend_logout']==''){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_URL');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend_logout" value="url" <?php if($this->controller->rol_config['redirect_type_frontend_logout']=='url'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
						</td>
						<td colspan="2">
							<input type="text" name="redirect_url_frontend_logout" style="width: 450px;" value="<?php echo $this->controller->rol_config['redirect_url_frontend_logout'];?>" /> <?php echo JText::_('COM_REDIRECTONLOGIN_URL_FULL'); ?>.
						</td>
					</tr>				
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['redirect_type_frontend_logout']=='dynamic' && !$this->controller->rol_config['dynamic_logout']){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="redirect_type_frontend_logout" value="dynamic" <?php if($this->controller->rol_config['redirect_type_frontend_logout']=='dynamic'){echo $checked;}?> />			
							<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->dynamic_logout_select; ?>
						</td>
					</tr>										
					<tr>
						<td colspan="4">&nbsp;							
						</td>
					</tr>	
					<tr>
						<td>
							<?php echo JText::_('JDEFAULT').' '.JText::_('COM_REDIRECTONLOGIN_AFTER_REGISTRATION'); ?>
						</td>						
						<td colspan="3">
							<?php 
							if($this->controller->get_version_type()=='free'){
								echo '<div style="color: red;">';
								echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
								echo '</div>';
							}
							echo JText::_('COM_REDIRECTONLOGIN_AFTER_REGISTRATION_INFO'); ?>.							
						</td>
					</tr>	
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_registration" value="none" <?php if($this->controller->rol_config['type_registration']=='none'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL'); ?></label>
						</td>
						<td colspan="2">
							<?php 
								echo JText::_('COM_REDIRECTONLOGIN_NORMAL').' Joomla '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_ACTIVATION'));
							?>
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['type_registration']=='menuitem' && !$this->controller->rol_config['menuitem_registration']){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_MENUITEM_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_registration" value="menuitem" <?php if($this->controller->rol_config['type_registration']=='menuitem'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_MENUITEM'); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->menuitem_registration_select; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['type_registration']=='url' && $this->controller->rol_config['url_registration']==''){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_URL');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_registration" value="url" <?php if($this->controller->rol_config['type_registration']=='url'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
						</td>
						<td colspan="2">
							<input type="text" name="url_registration" style="width: 450px;" value="<?php echo $this->controller->rol_config['url_registration'];?>" /> <?php echo JText::_('COM_REDIRECTONLOGIN_URL_FULL'); ?>.
						</td>
					</tr>				
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['type_registration']=='dynamic' && !$this->controller->rol_config['dynamic_registration']){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_registration" value="dynamic" <?php if($this->controller->rol_config['type_registration']=='dynamic'){echo $checked;}?> />			
							<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->dynamic_registration_select; ?>
						</td>
					</tr>					
					<tr>
						<td colspan="4">&nbsp;
							<a name="first"></a>
						</td>
					</tr>	
					<tr>
						<td>
							<?php echo JText::_('JDEFAULT').' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FIRST_TIME_LOGIN')); ?>
						</td>						
						<td colspan="3">
							<?php 
							if($this->controller->get_version_type()=='free'){
								echo '<div style="color: red;">';
								echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
								echo '</div>';
							}
							echo JText::_('COM_REDIRECTONLOGIN_FIRST_TIME_LOGIN_INFO').'. '.JText::_('COM_REDIRECTONLOGIN_FIRST_TIME_LOGIN_B'); ?>.							
						</td>
					</tr>	
					<tr>
						<td>&nbsp;
							
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_first" value="none" <?php if($this->controller->rol_config['type_first']=='none'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL'); ?></label>
						</td>
						<td colspan="2">
							<?php 
								echo JText::_('COM_REDIRECTONLOGIN_AS_SET_IN');
								echo ' <a href="index.php?option=com_redirectonlogin&view=allusers&tab=frontend">';
								echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_LOGIN'));
								echo '</a>';
							?>.
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['type_first']=='menuitem' && !$this->controller->rol_config['menuitem_first']){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_MENUITEM_SELECTED');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_first" value="menuitem" <?php if($this->controller->rol_config['type_first']=='menuitem'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_MENUITEM'); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->menuitem_first_select; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['type_first']=='url' && $this->controller->rol_config['url_first']==''){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_URL');							
							}				
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_first" value="url" <?php if($this->controller->rol_config['type_first']=='url'){echo $checked;}?> />			
							<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
						</td>
						<td colspan="2">
							<input type="text" name="url_first" style="width: 450px;" value="<?php echo $this->controller->rol_config['url_first'];?>" /> <?php echo JText::_('COM_REDIRECTONLOGIN_URL_FULL'); ?>.
						</td>
					</tr>				
					<tr>
						<td style="text-align: right; color: red;">
							<?php 
							if($this->controller->rol_config['type_first']=='dynamic' && !$this->controller->rol_config['dynamic_first']){							
								echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
							}
							?>
						</td>
						<td class="rol_nowrap">
							<label><input type="radio" name="type_first" value="dynamic" <?php if($this->controller->rol_config['type_first']=='dynamic'){echo $checked;}?> />			
							<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
						</td>
						<td colspan="2">
							<?php echo $this->dynamic_first_select; ?>
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;
						</td>
					</tr>	
					<tr>
						<td>
							<?php echo JText::_('COM_REDIRECTONLOGIN_RUN_SCRIPT'); ?>
						</td>
						<td>
							<?php echo $this->dynamic_run_script_select; ?>
						</td>						
						<td colspan="2">
							<?php 
							if($this->controller->get_version_type()=='free'){
								echo '<div style="color: red;">';
								echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
								echo '</div>';
							}
							echo JText::_('COM_REDIRECTONLOGIN_RUN_SCRIPT_B').'.';
							echo '<br />'.JText::_('COM_REDIRECTONLOGIN_RUN_SCRIPT_C');
							?>. 
							<a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_run_script" target="_blank">?</a>							
						</td>
					</tr>											
					<tr>
						<td colspan="4">&nbsp;
						</td>
					</tr>							
				</table>			
			</fieldset>	
			<?php redirectonloginHelper::tab_end(); 	
			$label = JText::_('COM_REDIRECTONLOGIN_BACKEND');
			redirectonloginHelper::tab_add('rol_allusers', 'backend', $label); 
			?>	
			<a name="backend"></a>
			<fieldset class="adminform pi_wrapper_nice">
			<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_BACKEND'); ?></legend>		
			<table class="adminlist pi_table tabletop">				
				<tr>
					<td class="rol_nowrap" style="width: 250px;">
						<label class="hasTip required"><?php echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_LOGIN'); ?></label>
					</td>
					<td colspan="3">
						<?php 
						if($this->controller->get_version_type()=='free'){
							echo '<div style="color: red;">';
							echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
							echo '</div>';
						}
						echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_INFO'); ?>.
					</td>					
				</tr>
				<tr>
					<td>&nbsp;
						
					</td>
					<td class="rol_nowrap" style="width: 150px;">
						<label><input type="radio" name="redirect_type_backend" value="none" <?php if($this->controller->rol_config['redirect_type_backend']=='none'){echo $checked;}?> /> 
						<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL'); ?></label>
					</td>
					<td colspan="2">
						<?php echo JText::_('COM_REDIRECTONLOGIN_TO_CONTROL_PANEL'); ?>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; color: red;">
						<?php 
						if($this->controller->rol_config['redirect_type_backend']=='url' && $this->controller->rol_config['redirect_url_backend']==''){						
							echo JText::_('COM_REDIRECTONLOGIN_NO_URL');						
						}				
						?>
					</td>
					<td class="rol_nowrap">
						<label><input type="radio" name="redirect_type_backend" value="url" <?php if($this->controller->rol_config['redirect_type_backend']=='url'){echo $checked;}?> /> 
						<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
					</td>
					<td colspan="2">
						administrator/<input type="text" name="redirect_url_backend" style="width: 450px;" value="<?php echo $this->controller->rol_config['redirect_url_backend'];?>" />
					</td>
				</tr>
				<tr>
					<td style="text-align: right; color: red;">
						<?php 
						if($this->controller->rol_config['redirect_type_backend']=='component' && $this->controller->rol_config['redirect_component_backend']=='0'){						
							echo JText::_('COM_REDIRECTONLOGIN_NO_COMPONENT_SELECTED');						
						}						
						?>
					</td>
					<td class="rol_nowrap">
						<label><input type="radio" name="redirect_type_backend" value="component" <?php if($this->controller->rol_config['redirect_type_backend']=='component'){echo $checked;}?> /> 
						<?php echo JText::_('COM_REDIRECTONLOGIN_COMPONENT'); ?>&nbsp;</label>
					</td>
					<td colspan="2">
						<select name="redirect_component_backend">	
						<?php
						echo '<option value="0"> - '.JText::_('COM_REDIRECTONLOGIN_SELECT_COMPONENT').' - </option>';							
						for($n = 0; $n < count($this->components); $n++){							
							echo '<option value="'.$this->components[$n][1].'"';
							if($this->controller->rol_config['redirect_component_backend']==$this->components[$n][1]){
								echo ' selected="selected"';
							}
							echo '>';												
							echo $this->controller->rol_strtolower($this->components[$n][0]);
							echo '</option>';								
						}
						?>						
						</select> 
					</td>
				</tr>
				<tr>
					<td style="text-align: right; color: red;">
						<?php 
						if($this->controller->rol_config['redirect_type_backend']=='dynamic' && !$this->controller->rol_config['loginbackend_dynamic']){							
							echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
						}				
						?>
					</td>
					<td class="rol_nowrap">
						<label><input type="radio" name="redirect_type_backend" value="dynamic" <?php if($this->controller->rol_config['redirect_type_backend']=='dynamic'){echo $checked;}?> />			
						<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
					</td>
					<td colspan="2">
						<?php echo $this->loginbackend_dynamic_select; ?> 						
					</td>
				</tr>
				<tr>
					<td>&nbsp;
						
					</td>
					<td>
						<label><input type="radio" name="redirect_type_backend" value="logout" <?php if($this->controller->rol_config['redirect_type_backend']=='logout'){echo $checked;}?> /> 
						<?php echo JText::_('COM_REDIRECTONLOGIN_BLOCK_LOGIN'); ?></label>
					</td>
					<td colspan="2">
						<?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_A'); ?>. 
						<?php echo JText::_('COM_REDIRECTONLOGIN_DONT_LOCK_YOURSELF_OUT'); ?>! 
						<?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_C'); ?>.
						<br />
						<?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_B'); ?> <a href="index.php?option=com_redirectonlogin&view=configuration&tab=frontend#messages_frontend"><?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_CONFIGURATION')); ?></a>.				
					</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;
						<a name="logoutbackend"></a>
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap" style="width: 250px;">
						<label><?php echo JText::_('COM_REDIRECTONLOGIN_DEFAULT_REDIRECT_TYPE_LOGOUT'); ?></label>
					</td>
					<td colspan="3">
						<?php 
						if($this->controller->get_version_type()=='free'){
							echo '<div style="color: red;">';
							echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
							echo '</div>';
						}
						echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_BACKEND_INFO').'. '.JText::_('COM_REDIRECTONLOGIN_LOGOUT_BACKEND_INFO_B'); ?>.	
					</td>					
				</tr>
				<tr>
					<td>&nbsp;
						
					</td>
					<td class="rol_nowrap" style="width: 150px;">
						<label><input type="radio" name="logoutbackend_type" value="none" <?php if($this->controller->rol_config['logoutbackend_type']=='none'){echo $checked;}?> /> 
						<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL'); ?></label>
					</td>
					<td colspan="2">
						<?php echo JText::_('COM_REDIRECTONLOGIN_NORMAL_LOGOUT'); ?>
					</td>
				</tr>				
				<tr>
					<td style="text-align: right; color: red;">
						<?php 
						if($this->controller->rol_config['logoutbackend_type']=='menuitem' && !$this->controller->rol_config['logoutbackend_menuitem']){							
							echo JText::_('COM_REDIRECTONLOGIN_NO_MENUITEM_SELECTED');							
						}				
						?>
					</td>
					<td class="rol_nowrap">
						<label><input type="radio" name="logoutbackend_type" value="menuitem" <?php if($this->controller->rol_config['logoutbackend_type']=='menuitem'){echo $checked;}?> />			
						<?php echo JText::_('COM_REDIRECTONLOGIN_MENUITEM'); ?></label>
					</td>
					<td colspan="2">
						<?php echo $this->menuitem_logoutbackend_select; ?>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; color: red;">
						<?php 
						if($this->controller->rol_config['logoutbackend_type']=='url' && $this->controller->rol_config['logoutbackend_url']==''){							
							echo JText::_('COM_REDIRECTONLOGIN_NO_URL');							
						}				
						?>
					</td>
					<td class="rol_nowrap">
						<label><input type="radio" name="logoutbackend_type" value="url" <?php if($this->controller->rol_config['logoutbackend_type']=='url'){echo $checked;}?> />			
						<?php echo JText::_('COM_REDIRECTONLOGIN_URL'); ?></label>
					</td>
					<td colspan="2">
						<input type="text" name="logoutbackend_url" style="width: 450px;" value="<?php echo $this->controller->rol_config['logoutbackend_url'];?>" /> <?php echo JText::_('COM_REDIRECTONLOGIN_URL_FULL'); ?>.
						<?php echo JText::_('COM_REDIRECTONLOGIN_LINK_TO_FRONTEND'); ?> = ../index.php
					</td>
				</tr>				
				<tr>
					<td style="text-align: right; color: red;">
						<?php 
						if($this->controller->rol_config['logoutbackend_type']=='dynamic' && !$this->controller->rol_config['logoutbackend_dynamic']){							
							echo JText::_('COM_REDIRECTONLOGIN_NO_DYNAMIC_REDIRECTS_SELECTED');							
						}				
						?>
					</td>
					<td class="rol_nowrap">
						<label><input type="radio" name="logoutbackend_type" value="dynamic" <?php if($this->controller->rol_config['logoutbackend_type']=='dynamic'){echo $checked;}?> />			
						<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); ?></label>
					</td>
					<td colspan="2">
						<?php echo $this->logoutbackend_dynamic_select; ?> 
						<span><?php echo JText::_('COM_REDIRECTONLOGIN_LINK_TO_FRONTEND'); ?>: 
						<a href="index.php?option=com_redirectonlogin&view=dynamicredirect&id=0#example19" target="_blank"><?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_EXAMPLE')); ?> 19</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;
					</td>
				</tr>
			</table>				
			</fieldset>	
			<?php
			redirectonloginHelper::tab_end();			
			redirectonloginHelper::tab_set_end(); 
			?>
			<a name="redirect_order"></a>
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
		</div>			
		<input type="hidden" name="task" value="" />	
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
