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

// no direct access
defined('_JEXEC') or die('Restircted access');

class com_creativeimagesliderInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // installing module
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'module'))
            echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_MODULE_INSTALL_FAILED').'</p>';
        
        // installing plugin
        $plugin_installer = new JInstaller;
        if($plugin_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin'))
        	 echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
        	echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_PLUGIN_INSTALL_FAILED').'</p>';
        
        // enabling plugin
        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET enabled = 1 WHERE element = "creativeimageslider" AND folder = "system"');
        $db->query();
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';

        $db = JFactory::getDBO();
        
        $sql = 'SELECT `extension_id` AS id, `name`, `element`, `folder` FROM #__extensions WHERE `type` = "module" AND ( (`element` = "mod_creativeimageslider") ) ';
        $db->setQuery($sql);
        $cis_module = $db->loadObject();
        $module_uninstaller = new JInstaller;
        if($module_uninstaller->uninstall('module', $cis_module->id))
        	 echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_MODULE_UNINSTALL_SUCCESS').'</p>';
        else
        	echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_MODULE_UNINSTALL_FAILED').'</p>';
        
        // uninstalling creative image slider plugin
        $db->setQuery("select extension_id from #__extensions where name = 'Creative Image Slider' and type = 'plugin' and element = 'creativeimageslider'");
        $cis_plugin = $db->loadObject();
        $plugin_uninstaller = new JInstaller;
        if($plugin_uninstaller->uninstall('plugin', $cis_plugin->extension_id))
        	echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_PLUGIN_UNINSTALL_SUCCESS').'</p>';
        else
        	echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_PLUGIN_UNINSTALL_FAILED').'</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'module'))
            echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_MODULE_INSTALL_FAILED').'</p>';
        
        $plugin_uninstaller = new JInstaller;
        if(@$plugin_uninstaller->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin'))
            echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVEIMAGESLIDER_PLUGIN_INSTALL_FAILED').'</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
	function postflight($type, $parent) {
	   //1.0.5 -> 1.0.6 update/////////////////////////////////////
    	$db = JFactory::getDBO();
		$query = "SELECT * FROM `#__cis_sliders` LIMIT 1";
		$db->setQuery($query);
		$columns_data = $db->LoadAssoc();
		
		if(is_array($columns_data)) {
			$columns_titles = array_keys($columns_data);
			if(!in_array('arrow_template',$columns_titles)) {
				//add required columns
				$query_update = "
									ALTER TABLE  `#__cis_sliders`  
										ADD `arrow_template` SMALLINT UNSIGNED NOT NULL DEFAULT  '37',
										ADD `arrow_width` SMALLINT UNSIGNED NOT NULL DEFAULT  '32',
										ADD `arrow_left_offset` SMALLINT UNSIGNED NOT NULL DEFAULT  '10',
										ADD `arrow_center_offset` SMALLINT NOT NULL DEFAULT  '0',
										ADD `arrow_passive_opacity` SMALLINT UNSIGNED NOT NULL DEFAULT  '70',
										ADD `move_step` INT UNSIGNED NOT NULL DEFAULT  '600',
										ADD `move_time` INT UNSIGNED NOT NULL DEFAULT  '600',
										ADD `move_ease` INT UNSIGNED NOT NULL DEFAULT  '60',
										ADD `autoplay` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
										ADD `autoplay_start_timeout` INT UNSIGNED NOT NULL DEFAULT  '3000',
										ADD `autoplay_hover_timeout` INT UNSIGNED NOT NULL DEFAULT  '800',
										ADD `autoplay_step_timeout` INT UNSIGNED NOT NULL DEFAULT  '5000',
										ADD `autoplay_evenly_speed` INT UNSIGNED NOT NULL DEFAULT  '25'
								";
				$db->setQuery($query_update);
				$db->query();
			}
		}
         //1.0.6 -> 2.0.0 update/////////////////////////////////////
        $query = "SELECT * FROM `#__cis_sliders` LIMIT 1";
        $db->setQuery($query);
        $columns_data = $db->LoadAssoc();
        
        if(is_array($columns_data)) {
            $columns_titles = array_keys($columns_data);
            if(!in_array('popup_max_size',$columns_titles)) {
                //add required columns
                $query_update = "
                                    ALTER TABLE  `#__cis_sliders`  
                                        ADD  `overlayanimationtype` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `popup_max_size` TINYINT UNSIGNED NOT NULL DEFAULT  '90',
                                        ADD  `popup_item_min_width` SMALLINT UNSIGNED NOT NULL DEFAULT  '300',
                                        ADD  `popup_use_back_img` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `popup_arrow_passive_opacity` TINYINT UNSIGNED NOT NULL DEFAULT  '70',
                                        ADD  `popup_arrow_left_offset` TINYINT UNSIGNED NOT NULL DEFAULT  '12',
                                        ADD  `popup_arrow_min_height` TINYINT UNSIGNED NOT NULL DEFAULT  '30',
                                        ADD  `popup_arrow_max_height` TINYINT UNSIGNED NOT NULL DEFAULT  '50',
                                        ADD  `popup_showarrows` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `popup_image_order_opacity` TINYINT UNSIGNED NOT NULL DEFAULT  '70',
                                        ADD  `popup_image_order_top_offset` TINYINT UNSIGNED NOT NULL DEFAULT  '12',
                                        ADD  `popup_show_orderdata` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `popup_icons_opacity` TINYINT UNSIGNED NOT NULL DEFAULT  '50',
                                        ADD  `popup_show_icons` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `popup_autoplay_default` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `popup_closeonend` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `popup_autoplay_time` INT UNSIGNED NOT NULL DEFAULT  '5000',
                                        ADD  `popup_open_event` TINYINT UNSIGNED NOT NULL DEFAULT  '0'
                                ";


                $db->setQuery($query_update);
                $db->query(); 
                $query_update = "
                                    ALTER TABLE  `#__cis_images`  
                                        ADD  `popup_img_name` TEXT NOT NULL,
                                        ADD  `popup_img_url` TEXT NOT NULL,
                                        ADD  `popup_open_event` TINYINT UNSIGNED NOT NULL DEFAULT  '4'
                                ";


                $db->setQuery($query_update);
                $db->query();
            }
        } 
        //2.0.0 -> 3.0.0 update/////////////////////////////////////
        $query = "SELECT * FROM `#__cis_sliders` LIMIT 1";
        $db->setQuery($query);
        $columns_data = $db->LoadAssoc();
        
        if(is_array($columns_data)) {
            $columns_titles = array_keys($columns_data);
            if(!in_array('link_open_event',$columns_titles)) {
                //add required columns
                $query_update = "
                                    ALTER TABLE  `#__cis_sliders`  
                                        ADD  `link_open_event` TINYINT UNSIGNED NOT NULL DEFAULT  '3',
                                        ADD  `cis_touch_enabled` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_inf_scroll_enabled` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_mouse_scroll_enabled` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_item_correction_enabled` TINYINT UNSIGNED NOT NULL DEFAULT  '1',

                                        
                                        ADD  `cis_animation_type` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_item_hover_effect` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `cis_items_appearance_effect` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_overlay_type` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_touch_type` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `cis_font_family` TEXT NOT NULL,
                                        ADD  `cis_font_effect` TEXT NOT NULL,

                                        ADD  `icons_size` TINYINT UNSIGNED NOT NULL DEFAULT  '40',
                                        ADD  `icons_margin` TINYINT UNSIGNED NOT NULL DEFAULT  '10',
                                        ADD  `icons_offset` TINYINT UNSIGNED NOT NULL DEFAULT  '5',
                                        ADD  `icons_animation` TINYINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD  `icons_color` TINYINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD  `icons_valign` TINYINT UNSIGNED NOT NULL DEFAULT  '0',

                                        ADD  `ov_items_offset` TINYINT UNSIGNED NOT NULL DEFAULT  '15',
                                        ADD  `ov_items_m_offset` SMALLINT NOT NULL DEFAULT  '0',
                                        ADD  `cis_button_font_family` TEXT NOT NULL


                                ";
                $db->setQuery($query_update);
                $db->query();

            }
            // 3.0 beta to 3.0 updates
            if(!in_array('custom_css',$columns_titles)) {
                //add required columns
                $query_update = "
                                    ALTER TABLE  `#__cis_sliders`  
                                        ADD  `custom_css` TEXT NOT NULL,
                                        ADD  `custom_js` TEXT NOT NULL
                                ";
                $db->setQuery($query_update);
                $db->query();
            }
            // to 3.1.0 update
            if(!in_array('slider_full_size',$columns_titles)) {
                //add required columns
                $query_update = "
                                ALTER TABLE  `#__cis_sliders`  
                                    ADD  `slider_full_size` TINYINT UNSIGNED NOT NULL DEFAULT  '0'
                              ";
                $db->setQuery($query_update);
                $db->query();

            }
        }
    }
}