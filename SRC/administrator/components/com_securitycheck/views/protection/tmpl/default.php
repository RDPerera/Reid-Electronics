<?php
/**
* Securitycheck Protection View para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die();

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Session\Session as JSession;

JSession::checkToken( 'get' ) or die( 'Invalid Token' );

function booleanlist( $name, $attribs = null, $selected = null, $id=false )
{
	$arr = array(
		JHTML::_('select.option',  '0', JText::_( 'COM_SECURITYCHECK_NO' ) ),
		JHTML::_('select.option',  '1', JText::_( 'COM_SECURITYCHECK_YES' ) )
	);
	return JHTML::_('select.genericlist',  $arr, $name, $attribs, 'value', 'text', (int) $selected, $id );
}


// Add style declaration
$media_url = "media/com_securitycheck/stylesheets/cpanelui.css";
JHTML::stylesheet($media_url);

$bootstrap_css = "media/com_securitycheck/stylesheets/bootstrap.min.css";
JHTML::stylesheet($bootstrap_css);

$site_url = JURI::base();
?>

<?php
	if ($this->server == 'apache'){
?>
<div class="alert alert-warning">
	<?php echo JText::_('COM_SECURITYCHECK_USER_AGENT_INTRO'); ?>
</div>
<div class="alert alert-danger">
	<?php echo JText::_('COM_SECURITYCHECK_USER_AGENT_WARN'); ?>	
</div>
<div class="alert alert-info">
<?php if($this->ExistsHtaccess) { 
		echo JText::_('COM_SECURITYCHECK_USER_AGENT_HTACCESS');
	  } else { 
	  	echo JText::_('COM_SECURITYCHECK_USER_AGENT_NO_HTACCESS');
} ?>
</div>
<?php
	} else if ($this->server == 'nginx'){
?>
<div class="alert alert-danger">
	<?php echo JText::_('COM_SECURITYCHECK_NGINX_SERVER'); ?>	
</div>
<?php
	}
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

<form action="index.php" name="adminForm" id="adminForm" method="post" class="form form-horizontal">
	<input type="hidden" name="option" value="com_securitycheck" />
	<input type="hidden" name="view" value="protection" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="task" id="task" value="save" />
	<input type="hidden" name="controller" value="protection" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
		
	<fieldset>
		<legend><?php echo JText::_('COM_SECURITYCHECK_BACKEND_PROTECTION_TEXT') ?></legend>
		
		<div class="alert alert-danger">
			<?php echo JText::_('COM_SECURITYCHECK_BACKEND_PROTECTION_EXPLAIN'); ?>	
		</div>
		
		<div class="control-group">
			<label for="hide_backend_url" class="control-label-more-width" title="<?php echo JText::_('COM_SECURITYCHECK_HIDE_BACKEND_URL_EXPLAIN') ?>"><?php echo JText::_('COM_SECURITYCHECK_HIDE_BACKEND_URL_TEXT'); ?></label>
			<div class="controls controls-row">
				<?php 
				if ( version_compare(JVERSION, '3.20', 'lt') ) {										
				?>
					<div class="input-prepend">
						<span class="add-on" style="background-color: #FFBF60;"><?php echo $site_url ?>?</span>
						<input class="input-large" type="text" name="hide_backend_url" id="hide_backend_url" value="<?php echo $this->protection_config['hide_backend_url']?>" placeholder="<?php echo $this->protection_config['hide_backend_url'] ?>">					
					
				<?php } else {	?>
					<div class="input-group">
						<span class="input-group-addon" style="background-color: #FFBF60;"><?php echo $site_url ?>?</span>
						<input class="input-large" type="text" style="width: 260px;" name="hide_backend_url" id="hide_backend_url" value="<?php echo $this->protection_config['hide_backend_url']?>" placeholder="<?php echo $this->protection_config['hide_backend_url'] ?>">					
				
				<?php } ?>
				<?php
				// Obtenemos la longitud de la clave que tenemos que generar
				$params = JComponentHelper::getParams('com_securitycheck');
				$size = $params->get('secret_key_length',20);				
				?>
				<input type='button' class="btn btn-primary" style="margin-left: 10px;" value='<?php echo JText::_('COM_SECURITYCHECKPRO_HIDE_BACKEND_GENERATE_KEY_TEXT') ?>' onclick='document.getElementById("hide_backend_url").value = Password.generate(<?php echo $size; ?>)' />	
				</div>
				<?php if ( $this->config_applied['hide_backend_url'] ) {?>
					<span class="help-inline">
						<div class="applied">
							<?php echo JText::_('COM_SECURITYCHECK_APPLIED') ?>
						</div>
					</span>
				<?php } ?>
			</div>
			<blockquote><p class="text-info"><small><?php echo JText::_('COM_SECURITYCHECK_HIDE_BACKEND_URL_EXPLAIN') ?></small></p></blockquote>
		</div>
	</fieldset>
</form>
