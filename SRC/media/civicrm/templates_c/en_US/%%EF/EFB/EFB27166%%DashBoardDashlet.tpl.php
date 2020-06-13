<?php /* Smarty version 2.6.31, created on 2020-06-09 19:34:59
         compiled from CRM/Contact/Page/DashBoardDashlet.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Contact/Page/DashBoardDashlet.tpl', 1, false),array('block', 'ts', 'CRM/Contact/Page/DashBoardDashlet.tpl', 16, false),array('function', 'help', 'CRM/Contact/Page/DashBoardDashlet.tpl', 31, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/dashboard.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/chart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo $this->_tpl_vars['communityMessages']; ?>

<div class="crm-submit-buttons crm-dashboard-controls">
<a href="#" id="crm-dashboard-configure" class="crm-hover-button show-add">
  <i class="crm-i fa-wrench"></i> <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Configure Your Dashboard<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</a>

<a style="float:right;" href="#" class="crm-hover-button show-refresh" style="margin-left: 6px;">
  <i class="crm-i fa-refresh"></i> <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Refresh Dashboard Data<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</a>

</div>
<div class="clear"></div>
<div class="crm-block crm-content-block">
<div id="empty-message" class='hiddenElement'>
    <div class="status">
        <div class="font-size12pt bold"><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Welcome to your Home Dashboard<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
        <div class="display-block">
            <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Your dashboard provides a one-screen view of the data that's most important to you. Graphical or tabular data is pulled from the reports you select, and is displayed in 'dashlets' (sections of the dashboard).<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo smarty_function_help(array('id' => "id-dash_welcome",'file' => "CRM/Contact/Page/Dashboard.hlp"), $this);?>

        </div>
    </div>
</div>

<div id="configure-dashlet" class='hiddenElement' style="min-height: 20em;"></div>
<div id="civicrm-dashboard">
    <noscript><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Javascript must be enabled in your browser in order to use the dashboard features.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></noscript>
</div>
<div class="clear"></div>
<?php echo '
<script type="text/javascript">
  CRM.$(function($) {
    $(\'#crm-dashboard-configure\').click(function(e) {
      e.preventDefault();
      $(this).hide();
      if ($("#empty-message").is(\':visible\')) {
        $("#empty-message").fadeOut(400);
      }
      $("#civicrm-dashboard").fadeOut(400, function() {
        $(".crm-dashboard-controls").hide();
        $("#configure-dashlet").fadeIn(400);
      });
      CRM.loadPage(CRM.url(\'civicrm/dashlet\', \'reset=1\'), {target: $("#configure-dashlet")});
    });
  });
</script>
'; ?>

</div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>