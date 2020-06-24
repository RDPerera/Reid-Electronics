<?php
/**
* Securitycheck Control Panel View para el Componente Securitycheck
* @ author Jose A. Luque
* @ Copyright (c) 2011 - Jose A. Luque
* @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
*/

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Uri\Uri as JURI;
use Joomla\CMS\Router\Route as JRoute;

// Load language
$lang = JFactory::getLanguage();
$lang->load('com_securitycheck.sys');

$review = sprintf( $lang->_('COM_SECURITYCHECK_REVIEW'), '<a href="http://extensions.joomla.org/extensions/extension/access-a-security/site-security/securitycheck" target="_blank"  rel="noopener noreferrer">', '</a>' );
$translator_name = $lang->_('COM_SECURITYCHECK_TRANSLATOR_NAME');
$firewall_plugin_status = $lang->_('COM_SECURITYCHECK_FIREWALL_PLUGIN_STATUS');
$cron_plugin_status = $lang->_('COM_SECURITYCHECK_CRON_PLUGIN_STATUS');
$update_database_plugin_status = $lang->_('COM_SECURITYCHECKPRO_UPDATE_DATABASE_PLUGIN_STATUS');
$spam_protection_plugin_status = $lang->_('COM_SECURITYCHECKPRO_SPAM_PROTECTION_PLUGIN_STATUS');
$logs_status = $lang->_('COM_SECURITYCHECK_LOGS_STATUS');
$autoupdate_status = $lang->_('COM_SECURITYCHECK_AUTOUPDATE_STATUS');
$translator_url = $lang->_('COM_SECURITYCHECK_TRANSLATOR_URL');
if (!file_exists(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . "language" . DIRECTORY_SEPARATOR . $lang->get("tag") . DIRECTORY_SEPARATOR . $lang->get("tag") . ".com_securitycheck.ini")){
	// No existe traducción
	$translator_name = "<blink>" . $lang->get("name") . " translation is missing.</blink> Please contribute writing this translation. It's easy. Click to see how.";
	$translator_url = "http://securitycheck.protegetuordenador.com/index.php/forum/13-news-and-announcement/4-contribute-send-us-your-translation";
}

if ( version_compare(JVERSION, '3.20', 'lt') )
{
	JHTML::_('behavior.framework');
	JHtml::_('behavior.modal');
}

// Css circle
JHTML::stylesheet('media/com_securitycheck/stylesheets/circle.css');

// Add style declaration
$media_url = "media/com_securitycheck/stylesheets/font-awesome.min.css";
JHTML::stylesheet($media_url);

// Url to be used on statistics
$logUrl = 'index.php?option=com_securitycheck&controller=securitycheck&view=logs&datefrom=%s&dateto=%s';
?>

<script src="<?php echo JURI::root(); ?>media/com_securitycheck/javascript/chart.js/Chart.min.js"></script>

<?php 
if ( version_compare(JVERSION, '3.20', 'lt') ) {
?>
<!-- Bootstrap core CSS-->
<link href="<?php echo JURI::root(); ?>media/com_securitycheck/stylesheets/cpanelui.css" rel="stylesheet">

<style type="text/css">
*, *::after, *::before {
    box-sizing: border-box;
}
</style>
<?php } else {?>
<!-- Bootstrap core CSS-->
<link href="<?php echo JURI::root(); ?>media/com_securitycheck/stylesheets/cpanelui_j4.css" rel="stylesheet">
<?php }?>

<script type="text/javascript" language="javascript">

	jQuery(document).ready(function() {
		// Actualizamos los datos del gráfico 'pie'
		Chart.defaults.global.defaultFontFamily='-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif',Chart.defaults.global.defaultFontColor="#292b2c";var ctx=document.getElementById("piechart"),piechart=new Chart(ctx,{type:"pie",data:{labels:['<?php echo JText::_('COM_SECURITYCHECK_FIREWALL_RULES_APLIED'); ?>','<?php echo JText::_('COM_SECURITYCHECK_BLOCKED_ACCESS'); ?>','<?php echo JText::_('COM_SECURITYCHECK_USER_AND_SESSION_PROTECTION'); ?>'],datasets:[{data:['<?php echo $this->total_firewall_rules; ?>','<?php echo $this->total_blocked_access; ?>','<?php echo $this->total_user_session_protection; ?>'],backgroundColor:["#007bff","#dc3545","#ffc107"]}]}});
		
	});

	function Set_Easy_Config() {
		url = 'index.php?option=com_securitycheck&controller=cpanel&format=raw&task=Set_Easy_Config';
		jQuery.ajax({
			url: url,							
			method: 'GET',
			success: function(data){
				location.reload();				
			}
		});
	}
	
	function Set_Default_Config() {
		url = 'index.php?option=com_securitycheck&controller=cpanel&format=raw&task=Set_Default_Config';
		jQuery.ajax({
			url: url,							
			method: 'GET',
			success: function(data){
				location.reload();				
			}
		});
	}
	
	function hideElement(Id) {
		document.getElementById(Id).style.display = "none";
	}
	
	var cont_initialize = 0;
	var etiqueta_initialize = '';
	var url_initialize = '';
	var request_initialize = '';
	var ended_string_initialize = '<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_ENDED' ); ?>';
		
	function clear_data_button() {
		if ( cont_initialize == 0 ){							
			document.getElementById('loading-container').innerHTML = '<?php echo ('<img src="../media/com_securitycheck/images/loading.gif" title="' . JText::_( 'loading' ) .'" alt="' . JText::_( 'loading' ) .'">'); ?>';
			document.getElementById('warning_message').innerHTML = '<?php echo addslashes(JText::_( 'COM_SECURITYCHECK_FILEMANAGER_WARNING_MESSAGE' )); ?>';
		} else if ( cont_initialize == 1 ){
			url_initialize = 'index.php?option=com_securitycheck&controller=filemanager&format=raw&task=acciones_clear_data';
			etiqueta_initialize = 'current_task';
		} else {
			url_initialize = 'index.php?option=com_securitycheck&controller=filemanager&format=raw&task=getEstadoClearData';
			etiqueta_initialize = 'warning_message';
		}
		
		jQuery.ajax({
			url: url_initialize,							
			method: 'GET',
			success: function(response){				
				document.getElementById(etiqueta_initialize).innerHTML = response;
				request_initialize = response;						
			}
		});
			
		cont_initialize = cont_initialize + 1;
		
		if ( request_initialize == ended_string_initialize ) {			
			hideElement('loading-container');
			hideElement('warning_message');
			document.getElementById('completed_message').innerHTML = '<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_PROCESS_COMPLETED' ); ?>';
			document.getElementById('buttonclose').style.display = "block";			
		} else {
			var t = setTimeout("clear_data_button()",1000);						
		}
												
	}	
	
</script>

<div class="modal fade" id="initialize_data" tabindex="-1" role="dialog" aria-labelledby="initializedataLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header alert alert-info">
				<h2 class="modal-title" id="initializedataLabel"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_INITIALIZE_DATA'); ?></h2>				
			  </div>
			  <div class="modal-body text-center">	
				<div id="warning_message" class="margen-loading texto_14">
					<?php echo JText::_( 'COM_SECURITYCHECK_FILEMANAGER_CLEAR_DATA_WARNING_START_MESSAGE' ); ?>
				</div>
				<div id="completed_message" class="margen-loading texto_14 color_verde">	
				</div>
				<div id="loading-container" class="text-center margen">	
				</div>		
			  </div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="buttonwrapper" type="button" onclick="hideElement('buttonwrapper'); hideElement('buttonclose'); clear_data_button();"><i class="fa fa-fw fa-fire"></i><?php echo JText::_( 'COM_SECURITYCHECK_CLEAR_DATA_CLEAR_BUTTON' ); ?></button>
					<button type="button" id="buttonclose" class="btn btn-default" data-dismiss="modal"><?php echo JText::_('COM_SECURITYCHECKPRO_CLOSE'); ?></button>
				</div>			  
			</div>
		  </div>
		</div>

<form action="<?php echo JRoute::_('index.php?option=com_securitycheck');?>" method="post" name="adminForm" id="adminForm">

<div class="container-fluid">
<div class="row">
	<div class="col-lg-9 col-md-8">
	<div class="row">
        <div class="card" style="margin-left: 20px;">
            <div class="card-body">
                <div class="rotate">
					<i class="fa fa-power-off fa-3x"></i>
                </div>
                <h6 class="text-uppercase"><?php echo($firewall_plugin_status); ?></h6>
				<?php $enabled = $this->firewall_plugin_enabled; ?>				
				<div style="text-align: center;">
				<?php
						if ($enabled){ ?>
							<span class="badge badge-success"><?php echo(JText::_( 'COM_SECURITYCHECK_PLUGIN_ENABLED' )); ?></span>
				<?php 	}else{ ?>
							<span class="badge badge-important"><?php echo(JText::_( 'COM_SECURITYCHECK_PLUGIN_DISABLED' )); ?></span>
				<?php	}  ?>
				</div>
            </div>
        </div>
		
		<div class="card" style="margin-left: 20px;">
            <div class="card-body">
                <div class="rotate">
					<i class="fa fa-pencil fa-3x"></i>
                </div>
                <h6 class="text-uppercase"><?php echo($logs_status); ?></h6>							
				<div style="text-align: center;">
				<?php
						if ($this->logs_pending == 0){ ?>
							<span class="badge badge-success"><?php echo(JText::_( 'COM_SECURITYCHECK_NO_LOGS_PENDING' )); ?></span>
				<?php 	}else{ ?>
							<span class="badge badge-warning"><?php echo(JText::_( 'COM_SECURITYCHECK_LOGS_PENDING' )); ?></span>
				<?php	}  ?>
				</div>
				<h1><?php echo($this->logs_pending); ?></h1>
            </div>
        </div>
		
		<div class="card" style="margin-left: 20px;">
            <div class="card-body">
                <div class="rotate">
					<i class="fa fa-refresh fa-3x"></i>
                </div>
                <?php $exists = $this->update_database_plugin_exists; 
					$enabled = $this->update_database_plugin_enabled; 
				?>
				<div><?php echo($update_database_plugin_status); ?></div>
				<div style="text-align: center;">
				<?php
						if (!$exists) { ?>
							<span class="badge badge-dark"><?php echo(JText::_( 'COM_SECURITYCHECKPRO_PLUGIN_NOT_INSTALLED' )); ?></span>
							
				<?php  } else if ($enabled && $exists) { ?>
							<span class="badge badge-success"><?php echo(JText::_( 'COM_SECURITYCHECK_PLUGIN_ENABLED' )); ?></span>
				<?php 	}else if (!$enabled && $exists) { ?>
							<span class="badge badge-important"><?php echo(JText::_( 'COM_SECURITYCHECK_PLUGIN_DISABLED' )); ?></span>
				<?php	}  ?>
				</div>
				<div style="text-align: center; margin-top: 10px;">
				<?php
					if ($enabled && $exists ){ 
				?>
					<button class="btn btn-danger" onclick="Joomla.submitbutton('disable_update_database')" href="#">
						<i class="icon-off icon-white"> </i>
						<?php echo JText::_('COM_SECURITYCHECKPRO_DISABLE'); ?>
					</button>
				<?php } else if (!$enabled && $exists ) { ?>
					<button class="btn btn-success" onclick="Joomla.submitbutton('enable_update_database')" href="#">
						<i class="icon-ok icon-white"> </i>
						<?php echo JText::_('COM_SECURITYCHECKPRO_ENABLE'); ?>
					</button>
				<?php } else if (!$exists ) { ?>
					<a class="btn btn-info" type="button" href="http://securitycheck.protegetuordenador.com/index.php/our-products/securitycheck-pro-database-update" target="_blank"  rel="noopener noreferrer"><?php echo JText::_('COM_SECURITYCHECKPRO_MORE_INFO'); ?></a>
				<?php } ?>
				</div>
            </div>
        </div>
		
		<div class="card" style="margin-left: 20px;">
            <div class="card-body">
                <div class="rotate">
					<i class="fa fa-user fa-3x"></i>
                </div>
                <?php $exists = $this->spam_protection_plugin_exists; 
					$enabled = $this->spam_protection_plugin_enabled; 
				?>
				<div><?php echo($spam_protection_plugin_status); ?></div>
				<div style="text-align: center;">
				<?php
						if (!$exists) { ?>
							<span class="badge badge-dark"><?php echo(JText::_( 'COM_SECURITYCHECKPRO_PLUGIN_NOT_INSTALLED' )); ?></span>
							
				<?php  } else if ($enabled && $exists) { ?>
							<span class="badge badge-success"><?php echo(JText::_( 'COM_SECURITYCHECK_PLUGIN_ENABLED' )); ?></span>
				<?php 	}else if (!$enabled && $exists) { ?>
							<span class="badge badge-important"><?php echo(JText::_( 'COM_SECURITYCHECK_PLUGIN_DISABLED' )); ?></span>
				<?php	}  ?>
				</div>
				<div style="text-align: center; margin-top: 10px;">
				<?php
					if ($enabled && $exists ){ 
				?>
					<button class="btn btn-danger" onclick="Joomla.submitbutton('disable_spam_protection')" href="#">
						<i class="icon-off icon-white"> </i>
						<?php echo JText::_('COM_SECURITYCHECKPRO_DISABLE'); ?>
					</button>
				<?php } else if (!$enabled && $exists ) { ?>
					<button class="btn btn-success" onclick="Joomla.submitbutton('enable_spam_protection')" href="#">
						<i class="icon-ok icon-white"> </i>
						<?php echo JText::_('COM_SECURITYCHECKPRO_ENABLE'); ?>
					</button>
				<?php } else if (!$exists ) { ?>
					<a class="btn btn-info" type="button" href="https://securitycheck.protegetuordenador.com/index.php/our-products/securitycheck-spam-protection"  target="_blank"  rel="noopener noreferrer"><?php echo JText::_('COM_SECURITYCHECKPRO_MORE_INFO'); ?></a>
				<?php } ?>
				</div>
            </div>
        </div>		
		
		<div class="col-sm-3">
			<div class="card" style="margin-left: 20px;">
				<div class="card-body">
					<div style="text-align: right;">
						<button class="btn btn-info btn-mini right" type="button" onclick="Joomla.submitbutton('Go_system_info')" href="#"><?php echo JText::_( 'COM_SECURITYCHECKPRO_CHECK_STATUS' ); ?></i></button>
						</div>					
						<?php 						
							$class = "c100 p" .$this->overall . " green";
							if ( ($this->overall > 0) && ($this->overall < 60) ) {
								$class = "c100 p" .$this->overall . " orange";
							} else if ( ($this->overall >= 60) && ($this->overall < 80) ) {
								$class = "c100 p" .$this->overall . '"';
							} 
						?>
						<div class="<?php echo $class; ?>">
						<span><?php echo $this->overall . "%"; ?></span>
						<div class="slice">
							<div class="bar"></div>
							<div class="fill"></div>
						</div>					
					</div>
				</div>
			</div>	
		</div>
	</div>
	
	<div class="alert alert-warning" style="margin-top: 10px;" role="alert">
		<?php echo JText::_('COM_SECURITYCHECKPRO_CPANEL_HELP'); ?>
	</div>
	
	</div>

	<div class="col-lg-3 col-md-4" style="margin-bottom: 10px;">
		<div class="card border-success">
			<div class="card-body">
				<div class="card-block">   
					<div class="row">   
						<div class="col-12" style="text-align: center; font-size:20px; font-weight: bold;"> 
							<h3>Everything you need to protect and manage your Joomla! websites</h3> 
							<img src="https://securitycheck.protegetuordenador.com/modules/mod_image_show_gk4/cache/laptopgk-is-106.png" class="img-fluid"> 
							<hr>                         
							<h3 align="center" style="color: #FF4500;"><?php echo JText::_('COM_SECURITYCHECK_FREE_VERSION_LINE2') ?></h3>
							<h2 align="center"><?php echo JText::_('COM_SECURITYCHECK_FREE_VERSION_LINE3') ?></h2>
							<a class="btn btn-info" type="button" href="https://securitycheck.protegetuordenador.com/index.php/subscriptions/levels"  target="_blank"  rel="noopener noreferrer"><?php echo JText::_('COM_SECURITYCHECK_FREE_VERSION_LINE4'); ?></a>
						</div>  
					</div>    
				</div>                            
			</div>
		</div>	
	</div>
</div>


<div class="row">
	<div class="col-md-4">
		<div class="card">
			<h5 class="card-header"><i class="fa fa-home"></i><?php echo ' ' . JText::_('COM_SECURITYCHECK_CPANEL_MAIN_MENU'); ?></h5>
			<div class="card-body">
				<legend><?php echo JText::_('COM_SECURITYCHECK_CPANEL_OPTIONS'); ?></legend>				
				<div class="btn-group-vertical" role="group" aria-label="Cpanel options">
					<a href="<?php echo JRoute::_( 'index.php?option=com_securitycheck&controller=securitycheck&'. JSession::getFormToken() .'=1' );?>" class="btn btn-primary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_CHECK_VULNERABILITIES_TEXT'); ?></a>
					<a href="<?php echo JRoute::_( 'index.php?option=com_securitycheck&controller=filemanager&view=filemanager&'. JSession::getFormToken() .'=1' );?>" class="btn btn-primary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_FILE_MANAGER_TEXT'); ?></a>
					<a href="<?php echo 'index.php?option=com_securitycheck&controller=securitycheck&view=logs'?>" class="btn btn-primary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_VIEW_FIREWALL_LOGS'); ?></a>
					<a href="<?php echo JRoute::_( 'index.php?option=com_securitycheck&controller=protection&view=protection&'. JSession::getFormToken() .'=1' );?>" class="btn btn-primary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_HTACCESS_PROTECTION_TEXT'); ?></a>
				</div>

				<legend><?php echo JText::_('COM_SECURITYCHECK_CPANEL_CONFIGURATION'); ?></legend>
				<div class="btn-group-vertical" role="group" aria-label="Cpanel options">
					<a href="index.php?option=com_config&view=component&component=com_securitycheck&path=&return=<?php echo base64_encode(JURI::getInstance()->toString()) ?>" class="btn btn-secondary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_GLOBAL_CONFIGURATION'); ?></a>
					<a href="<?php echo 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $this->sc_plugin_id?>" class="btn btn-secondary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_FIREWALL_CONFIGURATION'); ?></a>
					<a href="<?php echo JRoute::_( 'index.php?option=com_securitycheck&controller=filemanager&view=sysinfo&'. JSession::getFormToken() .'=1' );
	?>" class="btn btn-secondary" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_SYSINFO_TEXT'); ?></a>
					<a href="<?php echo JRoute::_( 'index.php?option=com_securitycheck&controller=controlcenter&view=controlcenter&'. JSession::getFormToken() .'=1' );
				?>" class="btn btn-secondary" role="button"><?php echo JText::_('COM_SECURITYCHECKPRO_CPANEL_CONTROLCENTER_TEXT'); ?></a>
				</div>
								
				<legend><?php echo JText::_('COM_SECURITYCHECK_CPANEL_TASKS'); ?></legend>
				<div class="btn-group-vertical" role="group" aria-label="Cpanel options">
					<a href="#initialize_data" data-toggle="modal" data-target="#initialize_data" class="btn btn-info" role="button"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_INITIALIZE_DATA'); ?></a>
					<a href="#" onclick="Joomla.submitbutton('Export_config');" class="btn btn-info" role="button"><?php echo JText::_('COM_SECURITYCHECKPRO_CPANEL_EXPORT_CONFIG'); ?></a>
					<a href="<?php echo JRoute::_( 'index.php?option=com_securitycheck&controller=filemanager&view=upload&'. JSession::getFormToken() .'=1' );?>" class="btn btn-info" role="button"><?php echo JText::_('COM_SECURITYCHECKPRO_CPANEL_IMPORT_CONFIG'); ?></a>					
				</div>			
			</div>
		</div>		
	</div>

	<div class="col-md-4">
		<div class="card">
			<h5 class="card-header"><?php echo ' ' . JText::_('COM_SECURITYCHECK_CPANEL_STATISTICS'); ?></h5>
			<div class="card-body">
				<ul class="nav nav-tabs" role="tablist" id="WafConfigurationTabs">
					<li class="nav-item"">
						<a class="nav-link active" href="#historic" data-toggle="tab" role="tab"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_HISTORIC'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#detail" data-toggle="tab" role="tab"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_DETAIL'); ?></a>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane show active" id="historic" role="tabpanel">
						<h5 style="text-align: center;"><?php echo JText::_('COM_SECURITYCHECK_GRAPHIC_HEADER'); ?></h5>
						<canvas id="piechart" width="100%" height="40"></canvas>				
					</div>
					
					<div class="tab-pane" id="detail" role="tabpanel">
						<table class="table table-striped">
							<thead>
							  <tr>
								  <th><?php echo JText::_('COM_SECURITYCHECK_CPANEL_PERIOD'); ?></th>
								  <th class="center"><?php echo JText::_('COM_SECURITYCHECK_CPANEL_ENTRIES'); ?></th>
							  </tr>
							</thead> 
							<tbody>
								<tr>
									<td>
										<a href="<?php echo sprintf($logUrl, (gmdate('Y')-1).'-01-01 00:00:00', (gmdate('Y')-1).'-12-31 23:59:59')?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_LAST_YEAR'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->last_year_logs ?></b>
									</td>						
								</tr>
								<tr>
									<td>
										<a href="<?php echo sprintf($logUrl, gmdate('Y').'-01-01', gmdate('Y').'-12-31 23:59:59')?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_THIS_YEAR'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->this_year_logs ?></b>
									</td>						
								</tr>
								<tr>
									<?php
										$y = gmdate('Y');
										$m = gmdate('m');
										if($m == 1) {
											$m = 12; $y -= 1;
										} else {
											$m -= 1;
										}
										switch($m) {
											case 1: case 3: case 5: case 7: case 8: case 10: case 12:
												$lmday = 31; break;
											case 4: case 6: case 9: case 11:
												$lmday = 30; break;
											case 2:
												if( !($y % 4) && ($y % 400) ) {
													$lmday = 29;
												} else {
													$lmday = 28;
												}
										}
										if($y < 2011) $y = 2011;
										if($m < 1) $m = 1;
										if($lmday < 1) $lmday = 1;
									?>
									<td>
										<a href="<?php echo sprintf($logUrl, $y.'-'.$m.'-01', $y.'-'.$m.'-'.$lmday.' 23:59:59')?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_LAST_MONTH'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->last_month_logs ?></b>
									</td>						
								</tr>
								<tr>
									<?php
										switch(gmdate('m')) {
											case 1: case 3: case 5: case 7: case 8: case 10: case 12:
												$lmday = 31; break;
											case 4: case 6: case 9: case 11:
												$lmday = 30; break;
											case 2:
												$y = gmdate('Y');
												if( !($y % 4) && ($y % 400) ) {
													$lmday = 29;
												} else {
													$lmday = 28;
												}
										}
										if($lmday < 1) $lmday = 28;
									?>
									<td>
										<a href="<?php echo sprintf($logUrl, gmdate('Y').'-'.gmdate('m').'-01', gmdate('Y').'-'.gmdate('m').'-'.$lmday.' 23:59:59')?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_THIS_MONTH'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->this_month_logs ?></b>
									</td>						
								</tr>
								<tr>
									<td>
										<a href="<?php echo sprintf($logUrl, gmdate('Y-m-d', time()-7*24*3600), gmdate('Y-m-d 23:59:59'))?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_LAST_7_DAYS'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->last_7_days ?></b>
									</td>						
								</tr>
								<tr>
									<?php
										$date = new DateTime();
										$date->setDate(gmdate('Y'), gmdate('m'), gmdate('d'));
										$date->modify("-1 day");
										$yesterday = $date->format("Y-m-d");
										$date->modify("+1 day")
									?>
									<td>
										<a href="<?php echo sprintf($logUrl, $yesterday, $date->format("Y-m-d"))?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_YESTERDAY'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->yesterday ?></b>
									</td>						
								</tr>
								<tr>
									<?php
										$expiry = clone $date;
										$expiry->modify('+1 day');
									?>
									<td>
										<a href="<?php echo sprintf($logUrl, $date->format("Y-m-d"), $expiry->format("Y-m-d"))?>">
										<?php echo JText::_('COM_SECURITYCHECK_CPANEL_TODAY'); ?></a>
									</td>
									<td class="center">
										<b><?php echo $this->today ?></b>
									</td>						
								</tr>
							</tbody>
						</table>					
					</div>					
				</div>			
			</div>	
		</div>	
	</div>
	
	<div class="col-md-2">
		<div class="card">
			<h5 class="card-header"><?php echo ' ' . JText::_('COM_SECURITYCHECK_CPANEL_EASY_CONFIG'); ?></h5>
			<div class="card-body" style="text-align: center;">
				<?php $easy_config_applied = $this->easy_config_applied; ?>
				<div class="buttonwrapper"><?php echo JText::_( 'COM_SECURITYCHECK_CPANEL_EASY_CONFIG_STATUS' ); ?></div>
				<?php
						if ($easy_config_applied){ ?>
							<span class="badge badge-success"><?php echo(JText::_( 'COM_SECURITYCHECK_CPANEL_APPLIED' )); ?></span>
				<?php 	}else{ ?>
							<span class="badge badge-info"><?php echo(JText::_( 'COM_SECURITYCHECK_CPANEL_NOT_APPLIED' )); ?></span>
				<?php	}  ?>
				<div class="easy_config"><?php echo(JText::_( 'COM_SECURITYCHECK_CPANEL_EASY_CONFIG_DEFINITION' )); ?></div>
				<?php
						if ($easy_config_applied){ ?>
							<button class="btn btn-primary" type="button" onclick="Set_Default_Config();"><?php echo JText::_( 'COM_SECURITYCHECK_CPANEL_APPLY_DEFAULT_CONFIG' ); ?></button>
				<?php 	}else{ ?>
							<button class="btn btn-success" type="button" onclick="Set_Easy_Config();"><?php echo JText::_( 'COM_SECURITYCHECK_CPANEL_APPLY_EASY_CONFIG' ); ?></button>							
						</p>
						<p class="center">
				<?php	}  ?>
			</div>
		</div>				
	</div>
	
	<div class="col-md-2">
		<div class="card">
			<h5 class="card-header"><i class="fa fa-thumbs-up"></i><?php echo ' ' . JText::_('COM_SECURITYCHECK_CPANEL_HELP_US'); ?></h5>
			<div class="card-body" style="text-align: center;">
				<div><?php echo($review); ?></div>
				<div><?php echo('<a href="' . $translator_url . '" target="_blank"  rel="noopener noreferrer">' . $translator_name . '</a>'); ?></div>
			</div>
		</div>				
		
		<div class="card text-white bg-info" style="margin-top: 10px;">
			<h5 class="card-header"><i class="fa fa-bullhorn"></i><?php echo ' ' . JText::_('COM_SECURITYCHECK_CPANEL_DISCLAIMER'); ?></h5>
			<div class="card-body" style="text-align: center;">
				<p><?php echo JText::_('COM_SECURITYCHECK_CPANEL_DISCLAIMER_TEXT'); ?></p>
			</div>
		</div>
	</div>

</div>
</div>

<input type="hidden" name="option" value="com_securitycheck" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="1" />
<input type="hidden" name="controller" value="cpanel" />
</form>