<?php 
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_9F
	* Creation date: Août 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die('Restricted access'); 
$config = JComponentHelper::getParams('com_gmapfp');
$layout = JRequest::getVar('layout', '', '', 'str');
$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$mod_class_suffix = $this->params->get('pageclass_sfx');

foreach ($this->lieux as $lieu) {
	$recipient = $lieu->email;
	$id = $lieu->id;
};

?>
<div class="contentpane<?php echo $config->get( 'pageclass_sfx' ); ?>">
<?php
	$user   = JFactory::getUser();
	$CORRECT_NOM=$user->name;
	$CORRECT_EMAIL=$user->email;
	$CORRECT_SUBJECT='';
	$CORRECT_MESSAGE='';

    if ($recipient === "") {
		JError::raiseWarning(0, JText::_('GMAPFP_EMAIL_RECEPTEUR'));
    }
	?>
    
    <div class="gmapfp_form_contact <?php echo $mod_class_suffix ?>">
    <form action="<?php echo $url ?>" method="post">
    	<table>
        	<tr><td>
            	<table>
    				<tr>
                    	<td><?php echo JText::_('GMAPFP_EMAIL_NOM') ?></td>
                    </tr>
    				<tr>
                        <td><input class="gmapfp inputbox <?php echo $mod_class_suffix ?>" type="text" name="nom" size="30" value="<?php echo $CORRECT_NOM?>"/></td>
                    </tr>
    				<tr>
                    	<td><?php echo JText::_('GMAPFP_VOTRE_EMAIL') ?></td>
                    </tr>
    				<tr>
                        <td><input class="gmapfp inputbox <?php echo $mod_class_suffix ?>" type="text" name="email" size="30" value="<?php echo $CORRECT_EMAIL?>"/></td>
                    </tr>
    				<tr>
                    	<td><?php echo JText::_('GMAPFP_EMAIL_SUJET') ?></td>
                    </tr>
    				<tr>
                        <td><input class="gmapfp inputbox <?php echo $mod_class_suffix ?>" type="text" name="subject" size="30" value="<?php echo $CORRECT_SUBJECT?>"/></td>
                    </tr>
                	<tr>
    					<td valign="top"><?php echo JText::_('GMAPFP_EMAIL_MESSAGE') ?></td>
                    </tr>
    				<tr>
                        <td><textarea class="gmapfp textarea <?php echo $mod_class_suffix ?>" name="message" cols="50" rows="8"><?php echo $CORRECT_MESSAGE?></textarea></td>
                    </tr>
					<?php 
                    if ($this->params->get( 'email_copy' )) : ?>
                        <table>
                            <tr>
                                 <td>
                                    <input type="checkbox" name="email_copy" id="email_copy" value="1"  />
                                    <label for="contact_email_copy">
                                        <?php echo JText::_( 'GMAPFP_EMAIL_A_COPY' ); ?>
                                    </label>
                                </td>
                           </tr>
                        </table>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2">
                            <?php 
							$user   = JFactory::getUser();
							if (($this->params->get('gmapfp_afficher_captcha'))&&!$user->get('gid')) { ?>
                            <div>
                                <?php $link = JURI :: root().'index.php?option=com_gmapfp&task=captacha&tmpl=component'; ?>
                                <p>
                                    <img class="captcha" onclick="jcomments.clear('captcha');" id="comments-form-captcha-image" name="captcha-image" src="<?php echo $link; ?>" width="121" height="60" />
                                    <script type="text/javascript" src="<?php echo JURI :: root() ?>components/com_gmapfp/libraries/reload.js"></script> 
                                    <a href="javascript:ReloadCaptchaImages('captcha-image');">
                                        <img alt='reload.gif' src="<?php echo JURI :: root() ?>components/com_gmapfp/images/reload.gif" id='ContactCaptchaReload' name='ContactCaptchaReload' border="0" />
                                    </a> 
                                </p>
                                <?php echo JText::_('GMAPFP_CAPTCHA') ?><br />
                                <input type="text" name="keystring">
                            </div>
                            <?php }; ?>
    
                            <button class="button validate" type="submit"><?php echo JText::_('GMAPFP_SOUMETTRE') ?></button>
                        </td>
                    </tr>
                </table>
            </td></tr>
    	</table>
	<input type="hidden" name="option" value="com_gmapfp" />
	<input type="hidden" name="view" value="gmapfp" />
	<input type="hidden" name="redirect" value="<?php echo $url; ?>" />
	<input type="hidden" name="controller" value="gmapfpcontact" />   
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<?php echo JHTML::_( 'form.token' ); ?>
  	</form>
	</div >
</div >