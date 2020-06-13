<?php /* Smarty version 2.6.31, created on 2020-06-09 19:34:59
         compiled from CRM/common/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/footer.tpl', 1, false),array('block', 'ts', 'CRM/common/footer.tpl', 18, false),array('function', 'crmVersion', 'CRM/common/footer.tpl', 17, false),array('function', 'crmURL', 'CRM/common/footer.tpl', 21, false),array('function', 'docURL', 'CRM/common/footer.tpl', 27, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if (call_user_func ( array ( 'CRM_Core_Permission' , 'check' ) , 'access CiviCRM' )): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/accesskeys.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php if (! empty ( $this->_tpl_vars['contactId'] )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/contactFooter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>

  <div class="crm-footer" id="civicrm-footer">
    <?php echo smarty_function_crmVersion(array('assign' => 'version'), $this);?>

    <?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Powered by CiviCRM<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <a href="https://download.civicrm.org/about/<?php echo $this->_tpl_vars['version']; ?>
"><?php echo $this->_tpl_vars['version']; ?>
</a>.
    <?php if (! empty ( $this->_tpl_vars['footer_status_severity'] )): ?>
      <span class="status<?php if ($this->_tpl_vars['footer_status_severity'] > 3): ?> crm-error<?php elseif ($this->_tpl_vars['footer_status_severity'] > 2): ?> crm-warning<?php else: ?> crm-ok<?php endif; ?>">
      <a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/a/#/status'), $this);?>
"><?php echo $this->_tpl_vars['footer_status_message']; ?>
</a>
    </span>
    <?php endif; ?>
    <?php $this->_tag_stack[] = array('ts', array('1' => 'http://www.gnu.org/licenses/agpl-3.0.html')); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>CiviCRM is openly available under the <a href='%1'>GNU AGPL License</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><br/>
    <a href="https://civicrm.org/download"><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Download CiviCRM.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> &nbsp; &nbsp;
    <a href="https://lab.civicrm.org/groups/dev/-/issues"><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>View issues and report bugs.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> &nbsp; &nbsp;
    <?php echo smarty_function_docURL(array('page' => "",'text' => "Online documentation."), $this);?>

  </div>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/notifications.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>