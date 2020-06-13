<?php /* Smarty version 2.6.31, created on 2020-06-09 19:35:06
         compiled from CRM%5CDashlet%5CPage%5CBlog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM\\Dashlet\\Page\\Blog.tpl', 1, false),array('block', 'ts', 'CRM\\Dashlet\\Page\\Blog.tpl', 55, false),array('modifier', 'escape', 'CRM\\Dashlet\\Page\\Blog.tpl', 39, false),array('modifier', 'strip_tags', 'CRM\\Dashlet\\Page\\Blog.tpl', 51, false),array('modifier', 'mb_substr', 'CRM\\Dashlet\\Page\\Blog.tpl', 51, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo ''; ?><?php echo '
<style type="text/css">
  #civicrm-news-feed {
    border: 0 none;
  }
  #civicrm-news-feed .crm-news-feed-unread .crm-news-feed-item-title {
    font-weight: bold;
  }
  #civicrm-news-feed .collapsed .crm-accordion-header {
    text-overflow: ellipsis;
    text-wrap: none;
    white-space: nowrap;
    overflow: hidden;
  }
  #civicrm-news-feed .crm-news-feed-item-preview {
    color: #8d8d8d;
    display: none;
  }
  #civicrm-news-feed .collapsed .crm-news-feed-item-preview {
    display: inline;
  }
  #civicrm-news-feed .crm-news-feed-item-link {
    margin-bottom: 0;
  }
</style>
'; ?><?php echo '<div id="civicrm-news-feed"><ul>'; ?><?php $_from = $this->_tpl_vars['feeds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['channel']):
?><?php echo '<li class="ui-corner-all crm-tab-button" title="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['channel']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?><?php echo '"><a href="#civicrm-news-feed-'; ?><?php echo $this->_tpl_vars['channel']['name']; ?><?php echo '">'; ?><?php echo $this->_tpl_vars['channel']['title']; ?><?php echo '</a></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul>'; ?><?php $_from = $this->_tpl_vars['feeds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['channel']):
?><?php echo '<div id="civicrm-news-feed-'; ?><?php echo $this->_tpl_vars['channel']['name']; ?><?php echo '">'; ?><?php $_from = $this->_tpl_vars['channel']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['article']):
?><?php echo '<div class="crm-accordion-wrapper collapsed"><div class="crm-accordion-header"><span class="crm-news-feed-item-title">'; ?><?php echo $this->_tpl_vars['article']['title']; ?><?php echo '</span><span class="crm-news-feed-item-preview"> - '; ?><?php if (function_exists ( 'mb_substr' )): ?><?php echo ''; ?><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['article']['description'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 150) : mb_substr($_tmp, 0, 150)); ?><?php echo ''; ?><?php else: ?><?php echo ''; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['article']['description'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?><?php echo ''; ?><?php endif; ?><?php echo '</span></div><div class="crm-accordion-body"><div>'; ?><?php echo $this->_tpl_vars['article']['description']; ?><?php echo '</div><p class="crm-news-feed-item-link"><a target="_blank" href="'; ?><?php echo $this->_tpl_vars['article']['link']; ?><?php echo '" title="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['article']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?><?php echo '"><i class="crm-i fa-external-link"></i> '; ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo 'read more'; ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '…</a></p></div></div>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</div>'; ?><?php endforeach; endif; unset($_from); ?><?php echo ''; ?><?php if (! $this->_tpl_vars['feeds']): ?><?php echo '<div class="messages status no-popup"><div class="icon inform-icon"></div>'; ?><?php $this->_tag_stack[] = array('ts', array()); $_block_repeat=true;smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo 'Sorry but we are not able to provide this at the moment.'; ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><?php echo '</div>'; ?><?php endif; ?><?php echo '</div></div>'; ?><?php echo '<script type="text/javascript">
  (function($, _) {
    $(function() {
      $(\'#civicrm-news-feed\').tabs();
      var opened = CRM.cache.get(\'newsFeed\', {});
      $(\'#civicrm-news-feed ul.ui-tabs-nav a\').each(function() {
        var
          $tab = $(this),
          href = $tab.attr(\'href\'),
          $content = $(href),
          $items = $(\'.crm-accordion-wrapper\', $content),
          key = href.substring(19),
          count = 0;
        if (!opened[key]) opened[key] = [];
        if ($items.length) {
          $items.each(function () {
            var itemKey = $(\'.crm-news-feed-item-link a\', this).attr(\'href\');
            if ($.inArray(itemKey, opened[key]) < 0) {
              $(this).addClass(\'crm-news-feed-unread\');
              ++count;
              $(this).one(\'crmAccordion:open\', function () {
                $(this).removeClass(\'crm-news-feed-unread\');
                $(\'em\', $tab).text(--count || \'\');
                opened[key].push(itemKey);
                CRM.cache.set(\'newsFeed\', opened);
              });
            }
          });
          if (count) {
            $tab.html($tab.text() + \' <em>\' + count + \'</em>\');
          }
          // Remove items from localstorage that are no longer in the current feed
          _.remove(opened[key], function(itemKey) {
            return !$(\'a[href="\' + itemKey + \'"]\', $content).length;
          });
        }
      });
    });
  })(CRM.$, CRM._);
</script>'; ?><?php echo ''; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>