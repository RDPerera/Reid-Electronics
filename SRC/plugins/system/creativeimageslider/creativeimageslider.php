<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: default.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.event.plugin');

class plgSystemCreativeimageslider extends JPlugin {
    function __construct( &$subject ) {
      parent::__construct( $subject );
      // load plugin parameters and language file
      $this->_plugin = JPluginHelper::getPlugin( 'system', 'creativeimageslider' );
      $this->_params = json_decode( $this->_plugin->params );
      JPlugin::loadLanguage('plg_system_creativeimageslider', JPATH_ADMINISTRATOR);
    }
    
    function cis_make_slider($m) {
    	$id_slider = (int) $m[2];
    	
    	//include helper class
    	require_once JPATH_SITE.'/components/com_creativeimageslider/helpers/helper.php';
    	
    	$cis_class = new CreativeimagesliderHelper;
    	$cis_class->slider_id = $id_slider;
    	$cis_class->type = 'plugin';
    	$cis_class->class_suffix = 'cis_plg';
    	$cis_class->module_id = $this->plg_order;
    	$this->plg_order ++;
    	return  $cis_class->render_html();
    }

     function render_styles_scripts() {
         $document = JFactory::getDocument();
    	$content = JResponse::getBody();
    	
    	//check if the scripts did not included
    	if (strpos($content,'components/com_creativeimageslider/assets/css/main.css') !== false) {
    		return $content;
    	}
    	
    	$version = '3.1.1';
    	
    	$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/main.css?version='.$version;
    	$scripts = '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";
    	
    	$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/creative_buttons.css';
    	$scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";
    	
    	$cssFile = JURI::base(true).'/components/com_creativeimageslider/assets/css/creativecss-ui.css';
    	$scripts .= '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />'."\n";
    	
    	$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativelib.js';
    	$scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";
    	
    	$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativelib-ui.js';
    	$scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

      $jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/jquery.mobile.custom.js';
      $scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";
    	
    	$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/mousewheel.js';
    	$scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";
    	
    	$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativeimagesliderlightbox.js?version='.$version;
    	$scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

    	$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/creativeimageslider.js?version='.$version;
    	$scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";

    	$jsFile = JURI::base(true).'/components/com_creativeimageslider/assets/js/main.js?version='.$version;
    	$scripts .= '<script src="'.$jsFile.'" type="text/javascript"></script>'."\n";
    	
    	$content = str_replace('</head>', $scripts . '</head>', $content);
    	return $content;
    }
    
    function onAfterRender() {
      $mainframe = JFactory::getApplication();
      if($mainframe->isAdmin())
        return;

      $plugin = JPluginHelper::getPlugin('system', 'creativeimageslider');
      $pluginParams = json_decode( $plugin->params );

      $content = JResponse::getBody();
      
      //add scripts
      if(preg_match('/(\[creativeimageslider id="([0-9]+)"\])/s',$content))
      	$content = $this->render_styles_scripts();
      else
      	return;
      
      $this->plg_order = 10000;
      //plugin 
      $c = preg_replace_callback('/(\[creativeimageslider id="([0-9]+)"\])/s',array($this, 'cis_make_slider'),$content);
      
      JResponse::setBody($c);
    }

}