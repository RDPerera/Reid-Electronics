<?php /* Smarty version 2.6.31, created on 2020-06-09 19:34:59
         compiled from CRM/common/dashboard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', 'CRM/common/dashboard.tpl', 1, false),array('modifier', 'json_encode', 'CRM/common/dashboard.tpl', 17, false),array('function', 'crmURL', 'CRM/common/dashboard.tpl', 27, false),array('function', 'crmKey', 'CRM/common/dashboard.tpl', 30, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo '
<script type="text/javascript">

CRM.$(function($) {
  // The set of options we can use to initialize jQuery.dashboard().
  var options = {

    widgetsByColumn: '; ?>
<?php echo json_encode($this->_tpl_vars['contactDashlets']); ?>
<?php echo ',

    // These define the urls and data objects used for all of the ajax requests to the server.
    ajaxCallbacks: {

      // jQuery.dashboard() POSTs the widget-to-column settings here.
      // The \'columns\' property of data is reserved for the widget-to-columns settings:
      //    An array (keyed by zero-indexed column ID), of arrays (keyed by widget ID)
      //    of ints; 1 if the widget is minimized.  0 if not.
      saveColumns: {
        url: '; ?>
'<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/ajax/dashboard','h' => 0), $this);?>
'<?php echo ',
        data: {
          // columns: array(0 => array(widgetId => isMinimized, ...), ...),
          op: \'save_columns\', key: '; ?>
"<?php echo smarty_function_crmKey(array('name' => 'civicrm/ajax/dashboard'), $this);?>
"<?php echo '
        }
      },

      // jQuery.dashboard() GETs a widget\'s settings object and POST\'s a users submitted
      // settings back to the server.  The return, in both cases, is an associative
      // array with the new settings markup and other info:
      //
      // Required properties:
      //  * markup: HTML string.  The inner HTML of the settings form.  jQuery.dashboard()
      //    provides the Save and Cancel buttons and wrapping <form> element.  Can include
      //    <input>s of any standard type and <select>s, nested in <div>s etc.
      //
      // Server-side executable script callbacks (See documentation for
      // ajaxCallbacks.getWidgets):
      //  * initScript:  Called when widget settings are initialising.
      //  * script:  Called when switching into settings mode.  Executed every time
      //    the widget goes into settings-edit mode.
      //
      // The \'id\' property of data is reserved for the widget ID.
      // The \'settings\' property of data is reserved for the user-submitted settings.
      //    An array (keyed by the name="" attributes of <input>s), of <input> values.
      widgetSettings: {
        url: '; ?>
'<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/ajax/dashboard','h' => 0), $this);?>
'<?php echo ',
        data: {
          // id: widgetId,
          // settings: array(name => value, ...),
          op: \'widget_settings\', key: '; ?>
"<?php echo smarty_function_crmKey(array('name' => 'civicrm/ajax/dashboard'), $this);?>
"<?php echo '
        }
      }
    }

  };

  var dashboard = $(\'#civicrm-dashboard\')
    .on(\'mouseover\', \'.widget-header\', function() {
      $(this).closest(\'.widget-wrapper\').addClass(\'db-hover-handle\');
    })
    .on(\'mouseout\', \'.widget-header\', function() {
      $(this).closest(\'.widget-wrapper\').removeClass(\'db-hover-handle\');
    })
    .dashboard(options);


  $(\'.crm-hover-button.show-refresh\').click(function(e) {
    e.preventDefault();
    $.each(dashboard.widgets, function(id, widget) {
      widget.reloadContent();
    });
  });

});

</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>