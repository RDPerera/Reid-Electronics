<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3/0
	* Creation date: Mars 2013
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined( '_JEXEC' ) or die( 'Restricted access' );

	$mod_class_suffix=$this->params->get('pageclass_sfx');
	
	if(isset($this->error)) : ?>
<tr>
	<td><?php echo $this->error; ?></td>
</tr>
<?php endif; ?>
<tr>
	<td colspan="2">
    <div class="gmapfp_contact <?php echo $mod_class_suffix ?>">
	<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="emailForm" id="emailForm" class="form-validate">
    	<table>
        	<tr><td>
            	<table>
    				<tr>
                        <td>
                            <label id="name_textmsg" for="contact_name">
                                &nbsp;<?php echo JText::_( 'GMAPFP_EMAIL_NOM' );?>
                            </label>
                            <br />
                        	<input class="inputbox" type="text" name="nom" id="contact_name" size="30" value=""/>
                        </td>
                    </tr>
    				<tr>
                        <td>
                            <label id="email_textmsg" for="contact_email">
                                &nbsp;<?php echo JText::_( 'GMAPFP_VOTRE_EMAIL' );?>
                            </label>
                            <br />
                        	<input class="inputbox required validate-email" type="text" name="email" id="contact_email" size="30" value="" />
                        </td>
                    </tr>
    				<tr>
                        <td>
                            <label id="subject_textmsg" for="contact_subject">
                                &nbsp;<?php echo JText::_( 'GMAPFP_EMAIL_SUJET' );?>
                            </label>
                            <br />
                            <input class="inputbox" type="text" name="subject" id="contact_subject" size="30" value=""/>
                        </td>
                    </tr>
                </table>
           </td>
           <td>
                <table>
                	<tr>
                        <td>
                            <label id="contact_textmsg" for="contact_text">
                                &nbsp;<?php echo JText::_( 'GMAPFP_EMAIL_MESSAGE' );?>
                            </label>
                            <br />
                            <textarea class="inputbox required" name="message" id="contact_text" cols="50" rows="8"></textarea>
                        </td>
                    </tr>
                </table>
            </td></tr>
            <tr>
                	<td colspan="2">
                    	<button class="button validate" type="submit"><?php echo JText::_('GMAPFP_SOUMETTRE') ?></button>
                    </td>
           </tr>
    	</table>


	<?php 
	if ($this->params->get( 'email_copy' )) : ?>
		<table>
            <tr>
                 <td>
                    <input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
                    <label for="contact_email_copy">
                        <?php echo JText::_( 'GMAPFP_EMAIL_A_COPY' ); ?>
                    </label>
                </td>
           </tr>
        </table>
	<?php endif; ?>

	<input type="hidden" name="option" value="com_gmapfp" />
	<input type="hidden" name="view" value="gmapfpcontact" />
	<input type="hidden" name="controller" value="gmapfpcontact" />   
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<input type="hidden" name="redirect" value="<?php echo JRoute::_( 'index.php' );?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	</div>
	<br />
	</td>
</tr>
