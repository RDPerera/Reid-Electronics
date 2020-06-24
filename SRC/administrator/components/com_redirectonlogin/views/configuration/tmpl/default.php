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

function check_latest_version(){
	document.getElementById('version_checker_target').innerHTML = document.getElementById('version_checker_spinner').innerHTML;
	ajax_url = 'index.php?option=com_redirectonlogin&task=ajax_version_checker&format=raw';
	var req = new Request.HTML({url:ajax_url, update:'version_checker_target' });	
	req.send();
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
			//message in jomsocial facebook plugin is installed and enabled
			if($this->check_jomsocial_facebook_plugin()){
				echo '<div class="rol_warning">';
				echo 'jomsocial facebook plugin might conflict with first login ';
				echo '<a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_jomsocial_facebook" target="_blank">?</a>';
				echo '</div>';
			}
							
			$tabs = array('options', 'frontend', 'backend');//compatibility with j2.5
			redirectonloginHelper::tab_set_start('rol_config', 'options', 1, $tabs); 
			redirectonloginHelper::tab_add('rol_config', 'options', JText::_('JOPTIONS')); 
			?>				
			<fieldset class="adminform pi_wrapper_nice">
				<legend class="pi_legend"><?php echo JText::_('JSTATUS'); ?></legend>				
				<table class="adminlist pi_table tabletop">				
					<tr>
						<td class="rol_nowrap" style="width: 250px;">
							<label class="hasTip required"><?php echo JText::_('COM_REDIRECTONLOGIN_STATUS'); ?> Redirect-on-Login</label>
						</td>
						<td>
							<input type="radio" name="enable_redirection" id="enable_yes" value="yes" <?php if($this->controller->rol_config['enable_redirection']=='yes'){echo $checked;}?> />
							<label for="enable_yes"><?php echo JText::_('JENABLED'); ?></label>	
							<br /><br />			
							<input type="radio" name="enable_redirection" id="enable_no" value="no" <?php if($this->controller->rol_config['enable_redirection']=='no'){echo $checked;}?> />
							<label for="enable_no"><?php echo JText::_('JDISABLED'); ?></label>
						</td>					
					</tr>
					<tr>
						<td>
							<?php echo JText::_('COM_REDIRECTONLOGIN_PLUGIN_STATUS'); ?>
						</td>
						<td>
							<div><?php echo JText::_('COM_REDIRECTONLOGIN_USER_PLUGIN'); ?></div>
							<?php
							$user_plugin_installed = false;
							$user_plugin_enabled = false;
							
							//check if plugin is installed and published
							$this->controller->db->setQuery("SELECT enabled "
							."FROM #__extensions "
							."WHERE element='redirectonlogin' AND folder='user' AND type='plugin' "
							."LIMIT 1"					
							);
							$rows = $this->controller->db->loadObjectList();								
							foreach($rows as $row){	
								$user_plugin_installed = true;
								$user_plugin_enabled = $row->enabled;
							}
												
							if($user_plugin_installed){
								echo '<div style="color: #5F9E30;">'.JText::_('COM_REDIRECTONLOGIN_INSTALLED').'</div>';				
							}else{
								echo '<div style="color: red;">'.JText::_('COM_REDIRECTONLOGIN_NOT_INSTALLED').'</div>';
							}
							if($user_plugin_enabled=='1'){
								echo '<div style="color: #5F9E30;">'.JText::_('COM_REDIRECTONLOGIN_PUBLISHED').'</div>';				
							}else{
								echo '<span style="color: red;">'.JText::_('COM_REDIRECTONLOGIN_NOT_PUBLISHED').'</span>';
								echo ' <a href="index.php?option=com_redirectonlogin&task=enable_plugin_user">'.JText::_('COM_REDIRECTONLOGIN_ENABLE_PLUGIN').'</a>';
							}	
							?>
						</td>					
					</tr>
					<tr>
						<td>
							<?php echo JText::_('COM_REDIRECTONLOGIN_PLUGIN_STATUS'); ?>
						</td>					
						<td>
							<div><?php echo JText::_('COM_REDIRECTONLOGIN_SYSTEM_PLUGIN'); ?></div>
							<?php
							$system_plugin_installed = false;
							$system_plugin_enabled = false;
							
							//check if plugin is installed and published
							$this->controller->db->setQuery("SELECT enabled "
							."FROM #__extensions "
							."WHERE element='redirectonlogin' AND folder='system' AND type='plugin' "
							."LIMIT 1"					
							);
							$rows = $this->controller->db->loadObjectList();					
							foreach($rows as $row){	
								$system_plugin_installed = true;
								$system_plugin_enabled = $row->enabled;
							}
												
							if($system_plugin_installed){
								echo '<div style="color: #5F9E30;">'.JText::_('COM_REDIRECTONLOGIN_INSTALLED').'</div>';				
							}else{
								echo '<div style="color: red;">'.JText::_('COM_REDIRECTONLOGIN_NOT_INSTALLED').'</div>';
							}
							if($system_plugin_enabled=='1'){
								echo '<div style="color: #5F9E30;">'.JText::_('COM_REDIRECTONLOGIN_PUBLISHED').'</div>';				
							}else{
								echo '<span style="color: red;">'.JText::_('COM_REDIRECTONLOGIN_NOT_PUBLISHED').'</span>';
								echo ' <a href="index.php?option=com_redirectonlogin&task=enable_plugin_system">'.JText::_('COM_REDIRECTONLOGIN_ENABLE_PLUGIN').'</a>';
							}	
							?>
						</td>
					</tr>				
				</table>				
			</fieldset>
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
			<fieldset class="adminform pi_wrapper_nice">
				<legend class="pi_legend"><?php echo JText::_('JVERSION'); ?></legend>
				<table class="adminlist pi_table tabletop">	
					<tr>		
						<td class="rol_nowrap" style="width: 250px;">
							<?php echo $this->controller->rol_strtolower(JText::_('JVERSION')); ?>	
						</td>
						<td style="width: 250px;">
							<?php echo $this->controller->rol_version.' ('.$this->controller->get_version_type().' '.$this->controller->rol_strtolower(JText::_('JVERSION')).')'; ?>
						</td>
						<td>
							<input type="button" value="<?php echo JText::_('COM_REDIRECTONLOGIN_CHECK_LATEST_VERSION'); ?>" onclick="check_latest_version();" />					
							<div id="version_checker_target"></div>	
							<span id="version_checker_spinner"><img src="components/com_redirectonlogin/images/processing.gif" alt="processing" /></span>				
						</td>
					</tr>	
					<tr>		
						<td>
							<?php echo JText::_('COM_REDIRECTONLOGIN_VERSION_CHECKER'); ?>	
						</td>
						<td>
							<label><input type="checkbox" class="checkbox" name="version_checker" value="true" <?php if($this->controller->rol_config['version_checker']){echo 'checked="checked"';} ?> /> <?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_ENABLE')); ?></label>
						</td>
						<td>
							<?php 
								echo JText::_('COM_REDIRECTONLOGIN_VERSION_CHECKER_INFO_A').' ';
								echo 'Redirect-on-Login ';
								echo JText::_('COM_REDIRECTONLOGIN_VERSION_CHECKER_INFO_B');
							?>.				
						</td>
					</tr>			
					<tr>		
						<td colspan="3">&nbsp;
							
						</td>
					</tr>
				</table>
			</fieldset>
			<?php redirectonloginHelper::tab_end(); 	
			$label = JText::_('COM_REDIRECTONLOGIN_FRONTEND');
			redirectonloginHelper::tab_add('rol_config', 'frontend', $label); 
			?>
			<a name="frontend"></a>
			<fieldset class="adminform pi_wrapper_nice">
				<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_FRONTEND'); ?></legend>				
				<table class="adminlist pi_table tabletop">				
					<tr>
						<td class="rol_nowrap" style="width: 250px;">
							<label class="hasTip required"><?php echo JText::_('COM_REDIRECTONLOGIN_U_OR_A'); ?></label>
						</td>
						<td colspan="3">
							<input type="radio" name="frontend_u_or_a" id="frontend_u" value="u" <?php if($this->controller->rol_config['frontend_u_or_a']=='u'){echo $checked;}?> />
							<label for="frontend_u"><?php echo JText::_('COM_REDIRECTONLOGIN_USERGROUPS'); ?></label>
							<br /><br />	
							<input type="radio" name="frontend_u_or_a" id="frontend_a" value="a" <?php if($this->controller->rol_config['frontend_u_or_a']=='a'){echo $checked;}?> />
							<label for="frontend_a"><?php echo JText::_('COM_REDIRECTONLOGIN_ACCESSLEVELS'); ?></label>	
						</td>					
					</tr>
					<tr>
						<td colspan="4">&nbsp;
							
						</td>
					</tr>	
					<tr>
						<td>
							<?php echo JText::_('COM_REDIRECTONLOGIN_AFTER_NO_ACCESS_PAGE'); ?>
						</td>
						<td colspan="3">
							<?php
								if($this->controller->get_version_type()=='free'){
									echo '<div style="color: red;">';
									echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
									echo '</div>';
								}								
							?>	
							<?php echo JText::_('COM_REDIRECTONLOGIN_AFTER_NO_ACCESS_PAGE_INFO'); ?>.																									
						</td>
					</tr>				
					<tr>
						<td>&nbsp;
													
						</td>
						<td class="rol_nowrap"  style="width: 250px;">
							<label><input type="radio" name="after_no_access_page" value="rol" <?php if($this->controller->rol_config['after_no_access_page']=='rol'){echo $checked;}?> />
							<?php echo JText::_('COM_REDIRECTONLOGIN_AS_SET_IN'); ?> Redirect-on-Login</label> 
							<br />
							<br />
							<label><input type="radio" name="after_no_access_page" value="page" <?php if($this->controller->rol_config['after_no_access_page']=='page'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_PAGE'); ?></label>											
						</td>
						<td colspan="2">
							<?php 
								if($this->controller->rol_config['after_no_access_page']=='page'){
									if($this->check_cb_problem()){
										echo '<br />';
										echo '<br />';
										echo '<div class="pi_red">';
										echo JText::_('COM_REDIRECTONLOGIN_PROBLEM');
										echo ' '.JText::_('COM_REDIRECTONLOGIN_WITH');
										echo ' Community Builder.';
										echo ' <a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_noaccess" target="_blank">';
										echo JText::_('COM_REDIRECTONLOGIN_READ_MORE').'</a>.';
										echo ' <a href="javascript:submitbutton(\'fix_community_builder\');">';
										echo $this->controller->rol_strtolower(JText::_('COM_INSTALLER_TOOLBAR_DATABASE_FIX'));
										echo '</a>';
										echo '</div>';
									}
								}
							?>																															
						</td>
					</tr>					
					<tr>
						<td>
							<?php
								if($this->controller->rol_config['after_no_access_page']=='pagerolno' && !$this->controller->rol_config['rolno_frontend_login']){
									echo '<div style="color: red;">';
									echo 'rol=no '.$this->controller->rol_strtolower(JText::_('JDISABLED')).' '.JText::_('COM_REDIRECTONLOGIN_FOR').' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FRONTEND')).' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGIN'));
									echo '</div>';
								}								
							?>	
						</td>
						<td><label><input type="radio" name="after_no_access_page" value="pagerolno" <?php if($this->controller->rol_config['after_no_access_page']=='pagerolno'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_PAGE_ROLNO'); ?> rol=no</label>
						</td>
						<td colspan="2">
							
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<?php 				
								echo JText::_('COM_REDIRECTONLOGIN_NO_REDIRECT');	
							?>
						</td>					
						<td colspan="3">
							<?php
								if($this->controller->get_version_type()=='free'){
									echo '<div style="color: red;">';
									echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
									echo '</div>';
								}
							?>
							<?php echo JText::_('COM_REDIRECTONLOGIN_NOREDIR_A').':'; ?>
							<strong>rol=no</strong>
							<br />index.php?option=com_content&amp;<strong>rol=no</strong><br />some-sef-url?<strong>rol=no</strong><br />
							<?php echo JText::_('COM_REDIRECTONLOGIN_NOREDIR_B'); ?>.<br />
							<?php echo JText::_('JENABLED').' '.JText::_('COM_REDIRECTONLOGIN_FOR'); ?>:							
						</td>					
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="3"><label><input type="checkbox" class="checkbox" name="rolno_frontend_login" value="true" <?php if($this->controller->rol_config['rolno_frontend_login']){echo 'checked="checked"';} ?> /> <?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGIN')).' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FRONTEND')); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="3"><label><input type="checkbox" class="checkbox" name="rolno_frontend_open" value="true" <?php if($this->controller->rol_config['rolno_frontend_open']){echo 'checked="checked"';} ?> /> <?php echo JText::_('COM_REDIRECTONLOGIN_WHEN_OPENING_SITE').' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FRONTEND')); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="3"><label><input type="checkbox" class="checkbox" name="rolno_frontend_logout" value="true" <?php if($this->controller->rol_config['rolno_frontend_logout']){echo 'checked="checked"';} ?> /> <?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGOUT')).' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FRONTEND')); ?></label>	
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;	
						<a name="multilanguage"></a>											
						</td>
					</tr>
					<tr>
						<td>
							<?php 				
								echo JText::_('COM_REDIRECTONLOGIN_MULTILANG_A');	
							?>
						</td>
						<td class="rol_nowrap">
							<?php
								if($this->controller->get_version_type()=='free'){
									echo '<div style="color: red;">';
									echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
									echo '</div>';
								}
							?>
							<label><input type="checkbox" class="checkbox" name="multilanguage_menu_association" value="true" <?php if($this->controller->rol_config['multilanguage_menu_association']){echo 'checked="checked"';} ?> /> <?php echo JText::_('COM_REDIRECTONLOGIN_MULTILANG_B'); ?></label>
						</td>

						<td colspan="2">
							<?php echo JText::_('COM_REDIRECTONLOGIN_MULTILANG_F').'.<br />'.JText::_('COM_REDIRECTONLOGIN_MULTILANG_C').'. '.JText::_('COM_REDIRECTONLOGIN_MULTILANG_G').'. '.JText::_('COM_REDIRECTONLOGIN_MULTILANG_D'); ?>.
						</td>					
					</tr>
					<tr>
						<td colspan="4">&nbsp;
						<a name="messages_frontend"></a>
						</td>
					</tr>
					<tr>		
						<td class="rol_nowrap" style="width: 250px;">
							<?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_MESSAGE'); ?>
						</td>
						<td>
							<label>
								<input type="radio" name="lang_type_login_front" value="custom" <?php if($this->controller->rol_config['lang_type_login_front']=='custom'){echo $checked;}?> />		
								<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_SET_MESSAGE')); ?>						
							</label>
										
						</td>
						<td colspan="2">
							<input type="text" name="logout_message_frontend" style="width: 450px;" value="<?php echo str_replace('"', '&quot;', $this->controller->rol_config['logout_message_frontend']);?>" />	
							<br />									
							<?php echo JText::_('COM_REDIRECTONLOGIN_MORE_ELABORATE_MESSAGES'); ?>. <a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_more_elaborate_messages" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?></a>					
						</td>
					</tr>	
					<tr>		
						<td class="rol_nowrap" style="width: 250px;">&nbsp;
							
						</td>
						<td>
							<label>
								<input type="radio" name="lang_type_login_front" value="langfile" <?php if($this->controller->rol_config['lang_type_login_front']=='langfile'){echo $checked;}?> />
								<?php echo JText::_('COM_REDIRECTONLOGIN_FROM_LANGUAGE_FILE'); ?>
							</label>										
						</td>
						<td colspan="2">
							<input type="text" style="width: 450px;" name="" value="<?php 
								$lang->load('com_redirectonlogin', JPATH_ROOT, null, false); 
								echo JText::_('COM_REDIRECTONLOGIN_YOU_CANT_LOGIN_FRONTEND');
							?>" disabled="disabled" /><br />
							<?php echo JText::_('COM_REDIRECTONLOGIN_LANGOVERRIDE'); ?>. <a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_language-overrides" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?></a>
						</td>
					</tr>					
					<tr>
						<td colspan="4">&nbsp;
						<a name="messages_backend"></a>
						<a name="backend"></a>
						</td>
					</tr>								
				</table>			
			</fieldset>	
			<?php redirectonloginHelper::tab_end(); 	
			$label = JText::_('COM_REDIRECTONLOGIN_BACKEND');
			redirectonloginHelper::tab_add('rol_config', 'backend', $label); 
			?>			
			<fieldset class="adminform pi_wrapper_nice">
				<legend class="pi_legend"><?php echo JText::_('COM_REDIRECTONLOGIN_BACKEND'); ?></legend>
				<table class="adminlist pi_table tabletop">
					<tr>
						<td>
							<?php echo JText::_('COM_REDIRECTONLOGIN_LOGIN').' '.JText::_('COM_REDIRECTONLOGIN_WITH').' '.JText::_('COM_REDIRECTONLOGIN_DEEPLINK'); ?>
						</td>						
						<td colspan="3">
							<?php
								if($this->controller->get_version_type()=='free'){
									echo '<div style="color: red;">';
									echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
									echo '</div>';
								}
							?>
							<?php echo JText::_('COM_REDIRECTONLOGIN_DEEPLINK_INFO'); ?> 'administrator/index.php'.							
							<?php echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE'); ?>: 'administrator/index.php?option=com_modules'.																									
						</td>
					</tr>					
					<tr>
						<td>
						</td>
						<td colspan="3">
							<label><input type="radio" name="deeplink" value="rol" <?php if($this->controller->rol_config['deeplink']=='rol'){echo $checked;}?> />
							<?php echo JText::_('COM_REDIRECTONLOGIN_AS_SET_IN'); ?> Redirect-on-Login</label>
						</td>						
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="3">
							<label><input type="radio" name="deeplink" value="page" <?php if($this->controller->rol_config['deeplink']=='page'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_PAGE'); ?></label>
						</td>						
					</tr>

					<tr>
						<td>
							<?php
								if($this->controller->rol_config['deeplink']=='pagerolno' && !$this->controller->rol_config['rolno_backend_login']){
									echo '<div style="color: red;">';
									echo 'rol=no '.$this->controller->rol_strtolower(JText::_('JDISABLED')).' '.JText::_('COM_REDIRECTONLOGIN_FOR').' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_BACKEND')).' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGIN'));
									echo '</div>';
								}								
							?>	
						</td>
						<td colspan="3">
							<label><input type="radio" name="deeplink" value="pagerolno" <?php if($this->controller->rol_config['deeplink']=='pagerolno'){echo $checked;}?> /> 
							<?php echo JText::_('COM_REDIRECTONLOGIN_PAGE_ROLNO'); ?> rol=no</label>
						</td>						
					</tr>	
					<tr>
						<td colspan="4">&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<?php 				
								echo JText::_('COM_REDIRECTONLOGIN_NO_REDIRECT');	
							?>
						</td>					
						<td colspan="3">
							<?php
								if($this->controller->get_version_type()=='free'){
									echo '<div style="color: red;">';
									echo JText::_('COM_REDIRECTONLOGIN_NOT_IN_FREE_VERSION');
									echo '</div>';
								}
							?>
							<?php echo JText::_('COM_REDIRECTONLOGIN_NOREDIR_A').':'; ?>
							<strong>rol=no</strong>
							<br />administrator/index.php?option=com_modules&amp;<strong>rol=no</strong>							
							<br />
							<?php echo JText::_('JENABLED').' '.JText::_('COM_REDIRECTONLOGIN_FOR'); ?>:
						</td>					
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="3"><label><input type="checkbox" class="checkbox" name="rolno_backend_login" value="true" <?php if($this->controller->rol_config['rolno_backend_login']){echo 'checked="checked"';} ?> /> <?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGIN')).' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_BACKEND')); ?></label>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td colspan="3"><label><input type="checkbox" class="checkbox" name="rolno_backend_logout" value="true" <?php if($this->controller->rol_config['rolno_backend_logout']){echo 'checked="checked"';} ?> /> <?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_LOGOUT')).' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_BACKEND')); ?></label>
						</td>
					</tr>				
					<tr>
						<td colspan="4">&nbsp;
						</td>
					</tr>	
					<tr>		
						<td class="rol_nowrap" style="width: 250px;">
							<?php echo JText::_('COM_REDIRECTONLOGIN_LOGOUT_MESSAGE'); ?>							
						</td>
						<td style="width: 250px;">
							<label>
								<input type="radio" name="lang_type_login_back" value="custom" <?php if($this->controller->rol_config['lang_type_login_back']=='custom'){echo $checked;}?> />	
								<?php echo $this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_SET_MESSAGE')); ?>						
							</label>										
						</td>
						<td colspan="2">
							<input type="text" name="logout_message_backend" style="width: 450px;" value="<?php echo str_replace('"', '&quot;', $this->controller->rol_config['logout_message_backend']);?>" />	
							<br />									
							<?php echo JText::_('COM_REDIRECTONLOGIN_MORE_ELABORATE_MESSAGES'); ?>. <a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_more_elaborate_messages" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?></a>							
						</td>
					</tr>	
					<tr>		
						<td>&nbsp;
							
						</td>
						<td>
							<label>
								<input type="radio" name="lang_type_login_back" value="langfile" <?php if($this->controller->rol_config['lang_type_login_back']=='langfile'){echo $checked;}?> />
								<?php echo JText::_('COM_REDIRECTONLOGIN_FROM_LANGUAGE_FILE'); ?>
							</label>										
						</td>
						<td colspan="2">
							<input type="text" style="width: 450px;" name="" value="<?php echo JText::_('COM_REDIRECTONLOGIN_YOU_CANT_LOGIN_BACKEND'); ?>" disabled="disabled" /><br />
									<?php echo JText::_('COM_REDIRECTONLOGIN_LANGOVERRIDE'); ?>.  <a href="http://www.pages-and-items.com/extensions/redirect-on-login/faqs?faqitem=config_language-overrides" target="_blank"><?php echo JText::_('COM_REDIRECTONLOGIN_READ_MORE'); ?></a>
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
		</div>			
		<input type="hidden" name="task" value="" />	
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
