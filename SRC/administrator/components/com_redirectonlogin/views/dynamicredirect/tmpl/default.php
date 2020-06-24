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

echo '
<style>
body textarea{
	font-size: 13px;
}
</style>
';
?>
<script language="Javascript" type="text/javascript" src="components/com_redirectonlogin/edit_area/edit_area_full.js"></script>
<script language="JavaScript" type="text/javascript">

Joomla.submitbutton = function(task){		
	if (task=='cancel'){			
		document.location.href = 'index.php?option=com_redirectonlogin&view=dynamicredirects';		
	} else {
		if (document.getElementById('redirect_name').value == '') {			
			alert('<?php echo addslashes(JText::_('COM_REDIRECTONLOGIN_NO_NAME_ENTERED')); ?>');
			return;
		}
		document.getElementById('redirect_code').value = editAreaLoader.getValue('redirect_code');
		if (document.getElementById('redirect_code').value == '') {			
			alert('<?php echo addslashes(JText::_('COM_REDIRECTONLOGIN_NO_CODE_ENTERED')); ?>');
			return;
		}
		if (task=='dynamicredirect_apply'){	
			document.adminForm.apply.value = '1';
		}
		submitform('dynamicredirect_save');
	}	
}

editAreaLoader.init({
	id: "redirect_code"	// id of the textarea to transform		
	,start_highlight: true	// if start with highlight
	,allow_resize: "both"
	,allow_toggle: true
	,word_wrap: true
	,language: "en"
	,syntax: "php"	
	,toolbar: "select_font,word_wrap"
	,display: "later"
});

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
		<h2 style="padding-left: 10px;"><?php echo JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT').': '.$this->redirect->name; ?></h2>				
		<fieldset class="adminform pi_wrapper_nice">										
			<table class="adminlist pi_table tabletop">	
				<tr>
					<td colspan="3">
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
					<td class="rol_nowrap" style="width: 150px;">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_NAME');
						?>
						:						
					</td>
					<td>
						<input type="text" name="redirect_name" id="redirect_name" style="width: 450px;" value="<?php echo str_replace('"', '&quot;', $this->redirect->name);?>" />
					</td>
					<td>&nbsp;
																		
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<br />
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_CODE');
						?>
						:	
					</td>
					<td>
						&lt;?php<br />
						<textarea name="redirect_code" id="redirect_code" style="width: 450px;" rows="20" cols="60"><?php echo $this->redirect->value; ?></textarea>
						<br />
						?&gt;
						<br />
						<img src="components/com_redirectonlogin/images/comment.gif" title="do only use /**/ to comment code" />
						<br />
						<br />
						<?php 
							echo JText::_('COM_REDIRECTONLOGIN_LINK_TO');
							echo ' '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_DYNAMIC_REDIRECT')); 
						?>:<br />
						index.php?option=com_redirectonlogin&view=dynamicredirect&id=<?php echo $this->redirect->id; ?>											
					</td>
					<td>
						<br />
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_CODE_INFO_A').' $redirect_menuitem_id ('.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_url).<br /><br />'.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_C').' $redirect_url / $redirect_menuitem_id '.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_D').'.<br />'.JText::_('COM_REDIRECTONLOGIN_IF').' $redirect_url '.JText::_('COM_REDIRECTONLOGIN_AND').' $redirect_menuitem_id '.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_E').', Redirect-on-Login '.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_F').' \''.JText::_('COM_REDIRECTONLOGIN_NORMAL').'\', '.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_G').' <a href="index.php?option=com_redirectonlogin&view=configuration&tab=options#redirect_order">'.JText::_('COM_REDIRECTONLOGIN_CONFIGURATION').' > '.JText::_('COM_REDIRECTONLOGIN_REDIRECT_ORDER').'</a>)'. '.<br />'.JText::_('COM_REDIRECTONLOGIN_IF').' $redirect_url '.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_H').' \'no\', '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_CODE_INFO_I')).'.<br />'.JText::_('COM_REDIRECTONLOGIN_IF_BOTH').' $redirect_url '.JText::_('COM_REDIRECTONLOGIN_AND').' $redirect_menuitem_id '.JText::_('COM_REDIRECTONLOGIN_ARE_SET').', '.JText::_('COM_REDIRECTONLOGIN_THEN').' $redirect_menuitem_id '.JText::_('COM_REDIRECTONLOGIN_OVERRULES').' $redirect_url.<br /><br />'.JText::_('COM_REDIRECTONLOGIN_YOU_NEED_TO_SET').':<br />$redirect_menuitem_id<br />'.JText::_('COM_REDIRECTONLOGIN_OR').'<br />$redirect_url <span style="color: #999;">'.JText::_('COM_REDIRECTONLOGIN_NEED_UNSEF').'! '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_EXAMPLE')).' index.php?option=com_something&id=2</span><br /><br />'.JText::_('COM_REDIRECTONLOGIN_YOU_CAN_ALSO_SET').':<br />$message<br />$message_type <span style="color: #999;">\'notice\', \'error\', \'message\' (=default)</span><br />$logout <span style="color: #999;">1='.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FORCE_LOGOUT')).'</span><br /><br />'.JText::_('COM_REDIRECTONLOGIN_CODE_INFO_B').':<br />$user_id<br />$user_name<br />$database<br />$usergroups <span style="color: #999;">(array)</span><br />$accesslevels <span style="color: #999;">(array)</span><br />$current_url<br />$first_time_login <span style="color: #999;">0 '.JText::_('COM_REDIRECTONLOGIN_OR').' 1</span><br />$country_code <span style="color: #999;">'.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_EXAMPLE')).' NL UK US iso-codes, '.JText::_('COM_REDIRECTONLOGIN_BASED_ON').' ip, 95% '.JText::_('COM_REDIRECTONLOGIN_ACCURACY').'</span><br />$language <span style="color: #999;">'.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_EXAMPLE')).' en-GB nl-NL iso-codes</span><br />$template <span style="color: #999;">'.JText::_('COM_INSTALLER_TYPE_TYPE_TEMPLATE').'</span><br />$device <span style="color: #999;">\'mobile\', \'tablet\', \'desktop\', \'bot\'</span><br />$ip';
						?>						
					</td>
				</tr>								
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 1
					</td>
					<td>
						<textarea name="example_2" style="width: 450px;" rows="1" cols="60">
$redirect_menuitem_id = 294;</textarea>
						<br />						
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_REDIRECT_TO_MENUITEM').'.<br /><br />';
							echo $this->menuitem_id_finder;
							echo '<br /><br />';
							echo JText::_('COM_REDIRECTONLOGIN_MULTILANG_C').'. '.JText::_('COM_REDIRECTONLOGIN_MULTILANG_G').'. '.JText::_('COM_REDIRECTONLOGIN_MULTILANG_E').'. <a href="index.php?option=com_redirectonlogin&view=configuration&tab=frontend#multilanguage">'.JText::_('COM_REDIRECTONLOGIN_READ_MORE').'</a>';
						?>						
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">										
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 2
					</td>
					<td>						
						<textarea name="example_1" style="width: 450px;" rows="2" cols="60">
$redirect_url = 'index.php?option=com_content&amp;view=article&amp;id=6&amp;Itemid=519';</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_SIMPLE_REDIRECT').'. '.JText::_('COM_REDIRECTONLOGIN_NEED_UNSEF').'.';
						?>				
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 3
					</td>
					<td>
						<textarea name="example_3" style="width: 450px;" rows="1" cols="60">
$redirect_url = 'no';</textarea>
						<br />						
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_CODE_INFO_I').'.';
						?>						
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 4
					</td>
					<td>
						<textarea name="example_4" style="width: 450px;" rows="1" cols="60">
$redirect_url = '';</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_NO_REDIR_MIGHT_INHERIT').'. '.JText::_('COM_REDIRECTONLOGIN_INHERIT_ONLY_LOGIN_LOGOUT').'.';
						?>						
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 5
					</td>
					<td>
						<textarea name="example_5" style="width: 450px;" rows="13" cols="60">
//latest article
$database->setQuery("SELECT id "
." FROM #__content "
." WHERE state='1' "
." ORDER BY created DESC "
);
$rows = $database->loadObjectList();
foreach($rows as $row){	
   $article_id = $row->id;	
   break;
}
$redirect_url = 'index.php?option=com_content&view=article&id='.$article_id;</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_TO_LATEST_ARTICLE').'.';
						?>						
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 6
					</td>
					<td>
						<textarea name="example_6" style="width: 450px;" rows="6" cols="60">
if($first_time_login){
   $redirect_url = 'index.php?option=com_content&view=article&id=6&Itemid=519';
}else{
   $redirect_url = $current_url;
}</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_FIRST_TIME_LOGIN').'.';
						?>						
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 7
					</td>
					<td>
						<textarea name="example_7" style="width: 450px;" rows="7" cols="60">
if($country_code=='NL'){
   $redirect_url = 'index.php?language=nl';
}elseif($country_code=='DE'){
   $redirect_url = 'index.php?language=de';
}else{
   $redirect_url = 'index.php?language=en';
}</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_COUNTRY').'.';
						?>	
						<br />This product includes GeoLite data created by MaxMind, available from <a href="http://www.maxmind.com" target="_blank">http://www.maxmind.com/</a>.
						<?php echo JText::_('COM_REDIRECTONLOGIN_BASED_ON').' ip.<br />95% '.JText::_('COM_REDIRECTONLOGIN_ACCURACY'); ?>.
						<a name="example8"></a>													
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 8
					</td>
					<td>
						<textarea name="example_8" style="width: 450px;" rows="3" cols="60">
$message = 'welcome';
$message_type = 'info';
$redirect_url = $current_url;</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_SET_MESSAGE').'.<br />'.JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>																			
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 9
					</td>
					<td>
						<textarea name="example_9" style="width: 450px;" rows="2" cols="60">
$message = JText::_('JERROR_LOGIN_DENIED');
$redirect_url = $current_url;</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_SET_MESSAGE_USING_LANGUAGE_KEY').'.<br />'.JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>																			
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 10
					</td>
					<td>
						<textarea name="example_10" style="width: 450px;" rows="4" cols="60">
$lang = JFactory::getLanguage();
$lang->load('com_search', JPATH_BASE, null, false);
$message = JText::_('COM_SEARCH_MOST_POPULAR');
$redirect_url = $current_url;</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_SET_MESSAGE_USING_LANGUAGE_KEY_EXT').'.<br />'.JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>																			
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 11
					</td>
					<td>
						<textarea name="example_11" style="width: 450px;" rows="3" cols="60">
$message = 'you can not login';
$logout = 1;
$redirect_url = $current_url;</textarea>
						<br />
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_SET_MESSAGE').' & '.$this->controller->rol_strtolower(JText::_('COM_REDIRECTONLOGIN_FORCE_LOGOUT')).'.<br />'.JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>																			
					</td>
				</tr>				
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 12
					</td>
					<td>
						<textarea name="example_12" style="width: 450px;" rows="3" cols="60">
$message = 'Hello '.$user_name;
$message_type = 'message';
$redirect_url = $current_url;</textarea>
						<br />						
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_OUTPUT');
						?>
						:
						<p style="padding: 10px 10px 10px 40px; margin-bottom: 0; background: url(../templates/beez_20/images/system/notice-info.png); border-bottom: 2px solid #90B203;  border-top: 2px solid #90B203;">
						Hello Lars
						</p>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_LAYOUT_DIFFERENT').'.<br />';
							echo JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>							
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 13
					</td>
					<td>
						<textarea name="example_13" style="width: 450px;" rows="3" cols="60">
$message = 'Hello '.$user_name;
$message_type = 'notice';
$redirect_url = $current_url;</textarea>
						<br />						
					</td>
					<td>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_OUTPUT');
						?>
						:
						<p style="padding: 10px 10px 10px 40px; margin-bottom: 0; background: url(../templates/beez_20/images/system/notice-note.png);border-bottom: 2px solid #FAA528; border-top: 2px solid #FAA528;">
						Hello Lars
						</p>	
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_LAYOUT_DIFFERENT').'.<br />';
							echo JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>						
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 14
					</td>
					<td>
						<textarea name="example_14" style="width: 450px;" rows="3" cols="60">
$message = 'Hello '.$user_name;
$message_type = 'error';
$redirect_url = $current_url;</textarea>
						<br />						
					</td>
					<td>						
						<?php							
							echo JText::_('COM_REDIRECTONLOGIN_OUTPUT');
						?>
						:
						<p style="padding: 10px 10px 10px 40px; margin-bottom: 0; background: url(../templates/beez_20/images/system/notice-alert.png); border-bottom: 2px solid #990000; border-top: 2px solid #990000;">
						Hello Lars
						</p>
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_LAYOUT_DIFFERENT').'.<br />';
							echo JText::_('COM_REDIRECTONLOGIN_NEEDS_REDIRECT').' ($redirect_url '.JText::_('COM_REDIRECTONLOGIN_OR').' $redirect_menuitem_id).';
						?>					
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 15
					</td>
					<td>
						<textarea name="example_15" style="width: 450px;" rows="7" cols="60">
$redirect_menuitem_id = 222;
if($language=='en-US'){
$redirect_menuitem_id = 333;
}
if($language=='nl-NL'){
$redirect_menuitem_id = 444;
}</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_REDIR_PER_LANG').'. '.JText::_('COM_REDIRECTONLOGIN_MULTILANG_G').'.';							
						?>											
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 16
					</td>
					<td>
						<textarea name="example_16" style="width: 450px;" rows="36" cols="60">
if($user_id){
   //user is logged in		

   //assuming 1 usergroup
   //take the group is which is first in order 
   //(frontend)
   $group = $usergroups[0];
   $category = 0;

   //pairs of groups-categories

   if($group==10){
      $category = 12;
   }	

   if($group==11){
      $category = 78;
   }

   if($group==19){
      $category = 33;	
   }

   //if there is a category set, set the redirect
   if($category){
      $redirect_url = 'index.php?option=com_content&view=category&layout=blog&id='.$category;
   } 
}else{
   //not logged in
   $message = 'you need to login to see this page';
   $message_type = 'info';
   $redirect_url = 'index.php?option=com_users&view=login';
}</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_REDIRECT').' '.JText::_('COM_REDIRECTONLOGIN_USERGROUP').' -> '.JText::_('JCATEGORY');							
						?>.											
					</td>
				</tr>
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 17
					</td>
					<td>
						<textarea name="example_17" style="width: 450px;" rows="8" cols="60">
$hours_offset = "-2";//set your time zone here
$hour = date("G", time()-($hours_offset*60*60));
if((9 < $hour) && ($hour < 17)){
   //office hours between 9 and 5
   $redirect_menuitem_id = 1;
}else{   
   $redirect_menuitem_id = 2;
}</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_TIMEBASED_REDIRECT').'.';							
						?>											
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 18
					</td>
					<td>
						<textarea name="example_18" style="width: 450px;" rows="2" cols="60">
$redirect_url = 'administrator/index.php?option=com_modules';</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_FRONTEND').' > '.JText::_('COM_REDIRECTONLOGIN_BACKEND');							
						?>.											
					</td>
				</tr>				
				<tr>
					<td class="rol_nowrap">
						<a name="example19"></a>					
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 19
					</td>
					<td>
						<textarea name="example_19" style="width: 450px;" rows="2" cols="60">
$redirect_url = '../index.php?option=com_content';</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_BACKEND').' > '.JText::_('COM_REDIRECTONLOGIN_FRONTEND');							
						?>.											
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">											
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 20
					</td>
					<td>
						<textarea name="example_20" style="width: 450px;" rows="5" cols="60">
if($device=='mobile'){
   $redirect_menuitem_id = 294;
}else{
   $redirect_menuitem_id = 200;
}</textarea>
						<br />						
					</td>
					<td>						
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_DEVICE');							
						?>
						<br />
						'mobile', 'tablet', 'desktop', 'bot'											
					</td>
				</tr>	
				<tr>
					<td class="rol_nowrap">											
						<?php
							echo JText::_('COM_REDIRECTONLOGIN_EXAMPLE');
						?>
						 21
					</td>
					<td>
						<textarea name="example_21" style="width: 450px;" rows="5" cols="60">
if($ip=='123.45.6.7'){
   $redirect_menuitem_id = 294;
}else{
   $redirect_menuitem_id = 200;
}</textarea>
						<br />						
					</td>
					<td>						
						IP											
					</td>
				</tr>				
			</table>				
		</fieldset>
		</div>	
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="apply" value="" />
	<input type="hidden" name="redirect_id" value="<?php echo $this->redirect->id; ?>" />
	<input type="hidden" name="redirect_type" value="<?php echo $this->redirect->type; ?>" />		
	<?php echo JHtml::_('form.token'); ?>
</form>
