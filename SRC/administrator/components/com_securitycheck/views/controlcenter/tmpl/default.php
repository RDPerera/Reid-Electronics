<?php
/**
* Securitycheck Pro ControlCenter View para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die();

use Joomla\CMS\Session\Session as JSession;
use Joomla\CMS\Language\Text as JText;

JSession::checkToken( 'get' ) or die( 'Invalid Token' );

$arr = array(
	JHTML::_('select.option',  '0', JText::_( 'COM_SECURITYCHECK_NO' ) ),
	JHTML::_('select.option',  '1', JText::_( 'COM_SECURITYCHECK_YES' ) )
);



// Add style declaration
$media_url = "media/com_securitycheck/stylesheets/cpanelui.css";
JHTML::stylesheet($media_url);

$bootstrap_css = "media/com_securitycheck/stylesheets/bootstrap.min.css";
JHTML::stylesheet($bootstrap_css);

$site_url = JURI::base();
?>

<script type="text/javascript" language="javascript">

var Password = {
 
  _pattern : /[a-zA-Z0-9]/, 
  
  _getRandomByte : function()
  {
    // http://caniuse.com/#feat=getrandomvalues
    if(window.crypto && window.crypto.getRandomValues) 
    {
      var result = new Uint8Array(1);
      window.crypto.getRandomValues(result);
      return result[0];
    }
    else if(window.msCrypto && window.msCrypto.getRandomValues) 
    {
      var result = new Uint8Array(1);
      window.msCrypto.getRandomValues(result);
      return result[0];
    }
    else
    {
      return Math.floor(Math.random() * 256);
    }
  },
  
  generate : function(length)
  {
    return Array.apply(null, {'length': length})
      .map(function()
      {
        var result;
        while(true) 
        {
          result = String.fromCharCode(this._getRandomByte());
          if(this._pattern.test(result))
          {
            return result;
          }
        }        
      }, this)
      .join('');  
  }    
    
};
</script>


<?php if ( function_exists('openssl_encrypt') ) { ?>

<form action="index.php" name="adminForm" id="adminForm" method="post" class="form form-horizontal">
	<input type="hidden" name="option" value="com_securitycheck" />
	<input type="hidden" name="view" value="controlcenter" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="task" id="task" value="save" />
	<input type="hidden" name="controller" value="controlcenter" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
	<fieldset>
		<legend><?php echo JText::_('COM_SECURITYCHECKPRO_CONTROLCENTER_TEXT') ?></legend>
		
		<div class="alert alert-info">
			<?php echo JText::_('COM_SECURITYCHECKPRO_CONTROLCENTER_EXPLAIN'); ?>	
		</div>
		
		<div class="control-group">
			<label for="control_center_enabled" class="control-label-more-width" title="<?php echo JText::_('COM_SECURITYCHECKPRO_CONTROLCENTER_ENABLED_EXPLAIN') ?>"><?php echo JText::_('COM_SECURITYCHECKPRO_CONTROLCENTER_ENABLED_TEXT'); ?></label>
			<div class="controls controls-row">
				<select name="control_center_enabled" class="custom-select">
					<?php echo JHtml::_('select.options', $arr, 'value', 'text', $this->control_center_enabled);?>
				</select>
							
			</div>
			<blockquote><p class="text-info"><small><?php echo JText::_('COM_SECURITYCHECKPRO_CONTROLCENTER_ENABLED_EXPLAIN') ?></small></p></blockquote>
		</div>
		
		<div class="control-group">
			<label for="secret_key" class="control-label-more-width" title="<?php echo JText::_('COM_SECURITYCHECKPRO_SECRET_KEY_EXPLAIN') ?>"><?php echo JText::_('COM_SECURITYCHECKPRO_SECRET_KEY_TEXT'); ?></label>
				<?php 
				if ( version_compare(JVERSION, '3.20', 'lt') ) {										
				?>				
					<div class="input-prepend">
						<input class="input-xlarge" type="text" name="secret_key" id="secret_key" value="<?php echo $this->secret_key ?>" readonly>
					</div>
									
					<div class="input-append">
						
					</div>
				<?php } else {	?>
					<div class="input-group mb-3">
					  <input type="text" class="form-control" name="secret_key" id="secret_key" style="max-width: 360px;" value="<?php echo $this->secret_key ?>" readonly aria-label="secret_key" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<input type='button' class="btn btn-primary" value='<?php echo JText::_('COM_SECURITYCHECKPRO_HIDE_BACKEND_GENERATE_KEY_TEXT') ?>' onclick='document.getElementById("secret_key").value = Password.generate(32)' />
						</div>
					</div>								
				<?php } ?>
				<div class="controls controls-row">
				</div>
			<blockquote><p class="text-info"><small><?php echo JText::_('COM_SECURITYCHECKPRO_SECRET_KEY_EXPLAIN') ?></small></p></blockquote>
		</div>
	</fieldset>
</form>

<?php } else { ?>
	<div class="alert alert-error">
		<?php echo JText::_('COM_SECURITYCHECKPRO_CONTROLCENTER_ENCRYPT_LIBRARY_NOT_PRESENT'); ?>	
	</div>

<?php } ?>
