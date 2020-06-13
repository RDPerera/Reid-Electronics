<?php /* Smarty version 2.6.31, created on 2020-06-09 19:34:45
         compiled from CRM/Block/LangSwitch.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/Block/LangSwitch.tpl', 1, false),array('modifier', 'count', 'CRM/Block/LangSwitch.tpl', 10, false),array('function', 'crmURL', 'CRM/Block/LangSwitch.tpl', 12, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php if (isset ( $this->_tpl_vars['langSwitch'] ) && count($this->_tpl_vars['langSwitch']) > 1): ?>
  <form action="#">
    <select name="lcMessages" onchange="window.location='<?php echo CRM_Utils_System::crmURL(array('q' => ($this->_tpl_vars['queryString'])), $this);?>
'+this.value">
      <?php $_from = $this->_tpl_vars['langSwitch']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['locale'] => $this->_tpl_vars['language']):
?>
        <option value="<?php echo $this->_tpl_vars['locale']; ?>
" <?php if ($this->_tpl_vars['locale'] == $this->_tpl_vars['tsLocale']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['language']; ?>
</option>
      <?php endforeach; endif; unset($_from); ?>
    </select>
  </form>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>