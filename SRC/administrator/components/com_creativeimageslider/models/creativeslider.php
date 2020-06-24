<?php
/**
 * Joomla! component creativeimageslider
 *
 * @version $Id: sexypoll.php 2012-04-05 14:30:25 svn $
 * @author Creative-Solutions.net
 * @package Creative Image Slider
 * @subpackage com_creativeimageslider
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class CreativeimagesliderModelCreativeslider extends JModelAdmin
{
	//get max id
	public function getMax_id()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query = 'SELECT COUNT(id) AS count_id FROM #__cis_sliders';
		$db->setQuery($query);
		$max_id = $db->loadResult();
		return $max_id;
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'CreativeSlider', $prefix = 'CreativeSliderTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_creativeimageslider.creativeslider', 'creativeslider', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_creativeimageslider.edit.creativeslider.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
			$data = $this->getItem();
		return $data;
	}
	
	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}
	
	/**
	 * Method to save block
	 */
	function saveSlider()
	{
		
		$date = new JDate();
		$id = JRequest::getInt('id',0);
	
		$req = new JObject();
	
		$req->name =  str_replace('\\','', htmlspecialchars($_REQUEST['jform']['name'], ENT_QUOTES) );
		$req->published = (int)$_REQUEST['published'];
		$req->id_template = (int)$_REQUEST['id_template'];
		$req->id_category = (int)$_REQUEST['id_category'];
		$req->width =  str_replace('\\','', htmlspecialchars($_REQUEST['width'], ENT_QUOTES) );
		$req->height = (int)$_REQUEST['height'];
		$req->itemsoffset = (int)$_REQUEST['itemsoffset'];
		$req->margintop = (int)$_REQUEST['margintop'];
		$req->marginbottom = (int)$_REQUEST['marginbottom'];
		$req->paddingtop = (int)$_REQUEST['paddingtop'];
		$req->paddingbottom = (int)$_REQUEST['paddingbottom'];
		$req->bgcolor =  str_replace('\\','', htmlspecialchars($_REQUEST['bgcolor'], ENT_QUOTES) );
		$req->showarrows = (int)$_REQUEST['showarrows'];
		$req->showreadmore = (int)$_REQUEST['showreadmore'];
		$req->readmoretext =  str_replace('\\','', htmlspecialchars($_REQUEST['readmoretext'], ENT_QUOTES) );
		$req->readmorestyle = $_REQUEST['readmorestyle'];
		$req->readmoreicon = $_REQUEST['readmoreicon'];
		$req->readmoresize = $_REQUEST['readmoresize'];
		$req->overlaycolor =  str_replace('\\','', htmlspecialchars($_REQUEST['overlaycolor'], ENT_QUOTES) );
		$req->overlayopacity = (int)$_REQUEST['overlayopacity'];
		$req->textcolor =  str_replace('\\','', htmlspecialchars($_REQUEST['textcolor'], ENT_QUOTES) );
		$req->overlayfontsize = (int)$_REQUEST['overlayfontsize'];
		$req->textshadowcolor =  str_replace('\\','', htmlspecialchars($_REQUEST['textshadowcolor'], ENT_QUOTES) );
		$req->textshadowsize = (int)$_REQUEST['textshadowsize'];
		$req->readmorealign = (int)$_REQUEST['readmorealign'];
		$req->captionalign = (int)$_REQUEST['captionalign'];
		$req->readmoremargin = $_REQUEST['readmoremargin'];
		$req->captionmargin = $_REQUEST['captionmargin'];
		$req->arrow_template = $_REQUEST['arrow_template'];
		$req->arrow_width = $_REQUEST['arrow_width'];
		$req->arrow_left_offset = $_REQUEST['arrow_left_offset'];
		$req->arrow_center_offset = $_REQUEST['arrow_center_offset'];
		$req->arrow_passive_opacity = $_REQUEST['arrow_passive_opacity'];
		$req->move_step = $_REQUEST['move_step'];
		$req->move_time = $_REQUEST['move_time'];
		$req->move_ease = $_REQUEST['move_ease'];
		$req->autoplay = $_REQUEST['autoplay'];
		$req->autoplay_start_timeout = $_REQUEST['autoplay_start_timeout'];
		$req->autoplay_hover_timeout = $_REQUEST['autoplay_hover_timeout'];
		$req->autoplay_step_timeout = $_REQUEST['autoplay_step_timeout'];
		$req->autoplay_evenly_speed = $_REQUEST['autoplay_evenly_speed'];

		$req->overlayanimationtype = (int) $_REQUEST['overlayanimationtype'];
		$req->popup_max_size = (int) $_REQUEST['popup_max_size'];
		$req->popup_item_min_width = (int) $_REQUEST['popup_item_min_width'];
		$req->popup_use_back_img = (int) $_REQUEST['popup_use_back_img'];
		$req->popup_arrow_passive_opacity = (int) $_REQUEST['popup_arrow_passive_opacity'];
		$req->popup_arrow_left_offset = (int) $_REQUEST['popup_arrow_left_offset'];
		$req->popup_arrow_min_height = (int) $_REQUEST['popup_arrow_min_height'];
		$req->popup_arrow_max_height = (int) $_REQUEST['popup_arrow_max_height'];
		$req->popup_showarrows = (int) $_REQUEST['popup_showarrows'];
		$req->popup_image_order_opacity = (int) $_REQUEST['popup_image_order_opacity'];
		$req->popup_image_order_top_offset = (int) $_REQUEST['popup_image_order_top_offset'];
		$req->popup_show_orderdata = (int) $_REQUEST['popup_show_orderdata'];
		$req->popup_icons_opacity = (int) $_REQUEST['popup_icons_opacity'];
		$req->popup_show_icons = (int) $_REQUEST['popup_show_icons'];
		$req->popup_autoplay_default = (int) $_REQUEST['popup_autoplay_default'];
		$req->popup_closeonend = (int) $_REQUEST['popup_closeonend'];
		$req->popup_autoplay_time = (int) $_REQUEST['popup_autoplay_time'];
		$req->popup_open_event = (int) $_REQUEST['popup_open_event'];
		$req->link_open_event = (int) $_REQUEST['link_open_event'];
		
		$req->cis_touch_enabled = (int) $_REQUEST['cis_touch_enabled'];
		$req->cis_inf_scroll_enabled = (int) $_REQUEST['cis_inf_scroll_enabled'];
		$req->cis_mouse_scroll_enabled = (int) $_REQUEST['cis_mouse_scroll_enabled'];
		$req->cis_item_correction_enabled = (int) $_REQUEST['cis_item_correction_enabled'];
		
		$req->cis_animation_type = (int) $_REQUEST['cis_animation_type'];
		$req->cis_item_hover_effect = (int) $_REQUEST['cis_item_hover_effect'];
		$req->cis_items_appearance_effect = (int) $_REQUEST['cis_items_appearance_effect'];
		$req->cis_overlay_type = (int) $_REQUEST['cis_overlay_type'];
		$req->cis_touch_type = (int) $_REQUEST['cis_touch_type'];
		$req->cis_font_family = $_REQUEST['cis_font_family'];
		$req->cis_font_effect = $_REQUEST['cis_font_effect'];

		$req->icons_size = (int) $_REQUEST['icons_size'];
		$req->icons_margin = (int) $_REQUEST['icons_margin'];
		$req->icons_offset = (int) $_REQUEST['icons_offset'];
		$req->icons_animation = (int) $_REQUEST['icons_animation'];
		$req->icons_color = (int) $_REQUEST['icons_color'];
		$req->icons_valign = (int) $_REQUEST['icons_valign'];

		$req->ov_items_offset = (int) $_REQUEST['ov_items_offset'];
		$req->ov_items_m_offset = (int) $_REQUEST['ov_items_m_offset'];
		$req->cis_button_font_family = $_REQUEST['cis_button_font_family'];

		$req->custom_css = $_REQUEST['custom_css'];
		$req->custom_js = $_REQUEST['custom_js'];

		$req->slider_full_size = $_REQUEST['slider_full_size'];

		if($req->name == "") {
			return false;
		}
		elseif($id == 0) {//if id ==0, we add the record
			$req->id = NULL;
	
			if (!$this->_db->insertObject( '#__cis_sliders', $req, 'id' )) {
				return false;
			}
		}
		else { //else update the record
			$req->id = $id;
				
			if (!$this->_db->updateObject( '#__cis_sliders', $req, 'id' )) {
				return false;
			}
		}
	
		return true;
	}

	/**
	 * Method to copy form
	 */
	function copySlider()
	{
		$id = JRequest::getInt('id',0);
		
		$req = new JObject();

		$req->name =  strip_tags($_REQUEST['jform']['name']) . ' (copy)';
		$req->published = (int)$_REQUEST['published'];
		$req->id_template = (int)$_REQUEST['id_template'];
		$req->id_category = (int)$_REQUEST['id_category'];
		$req->width =  str_replace('\\','', htmlspecialchars($_REQUEST['width'], ENT_QUOTES) );
		$req->height = (int)$_REQUEST['height'];
		$req->itemsoffset = (int)$_REQUEST['itemsoffset'];
		$req->margintop = (int)$_REQUEST['margintop'];
		$req->marginbottom = (int)$_REQUEST['marginbottom'];
		$req->paddingtop = (int)$_REQUEST['paddingtop'];
		$req->paddingbottom = (int)$_REQUEST['paddingbottom'];
		$req->bgcolor =  str_replace('\\','', htmlspecialchars($_REQUEST['bgcolor'], ENT_QUOTES) );
		$req->showarrows = (int)$_REQUEST['showarrows'];
		$req->showreadmore = (int)$_REQUEST['showreadmore'];
		$req->readmoretext =  str_replace('\\','', htmlspecialchars($_REQUEST['readmoretext'], ENT_QUOTES) );
		$req->readmorestyle = $_REQUEST['readmorestyle'];
		$req->readmoreicon = $_REQUEST['readmoreicon'];
		$req->readmoresize = $_REQUEST['readmoresize'];
		$req->overlaycolor =  str_replace('\\','', htmlspecialchars($_REQUEST['overlaycolor'], ENT_QUOTES) );
		$req->overlayopacity = (int)$_REQUEST['overlayopacity'];
		$req->textcolor =  str_replace('\\','', htmlspecialchars($_REQUEST['textcolor'], ENT_QUOTES) );
		$req->overlayfontsize = (int)$_REQUEST['overlayfontsize'];
		$req->textshadowcolor =  str_replace('\\','', htmlspecialchars($_REQUEST['textshadowcolor'], ENT_QUOTES) );
		$req->textshadowsize = (int)$_REQUEST['textshadowsize'];
		$req->readmorealign = (int)$_REQUEST['readmorealign'];
		$req->captionalign = (int)$_REQUEST['captionalign'];
		$req->readmoremargin = $_REQUEST['readmoremargin'];
		$req->captionmargin = $_REQUEST['captionmargin'];
		$req->arrow_template = $_REQUEST['arrow_template'];
		$req->arrow_width = $_REQUEST['arrow_width'];
		$req->arrow_left_offset = $_REQUEST['arrow_left_offset'];
		$req->arrow_center_offset = $_REQUEST['arrow_center_offset'];
		$req->arrow_passive_opacity = $_REQUEST['arrow_passive_opacity'];
		$req->move_step = $_REQUEST['move_step'];
		$req->move_time = $_REQUEST['move_time'];
		$req->move_ease = $_REQUEST['move_ease'];
		$req->autoplay = $_REQUEST['autoplay'];
		$req->autoplay_start_timeout = $_REQUEST['autoplay_start_timeout'];
		$req->autoplay_hover_timeout = $_REQUEST['autoplay_hover_timeout'];
		$req->autoplay_step_timeout = $_REQUEST['autoplay_step_timeout'];
		$req->autoplay_evenly_speed = $_REQUEST['autoplay_evenly_speed'];

		$req->overlayanimationtype = (int) $_REQUEST['overlayanimationtype'];
		$req->popup_max_size = (int) $_REQUEST['popup_max_size'];
		$req->popup_item_min_width = (int) $_REQUEST['popup_item_min_width'];
		$req->popup_use_back_img = (int) $_REQUEST['popup_use_back_img'];
		$req->popup_arrow_passive_opacity = (int) $_REQUEST['popup_arrow_passive_opacity'];
		$req->popup_arrow_left_offset = (int) $_REQUEST['popup_arrow_left_offset'];
		$req->popup_arrow_min_height = (int) $_REQUEST['popup_arrow_min_height'];
		$req->popup_arrow_max_height = (int) $_REQUEST['popup_arrow_max_height'];
		$req->popup_showarrows = (int) $_REQUEST['popup_showarrows'];
		$req->popup_image_order_opacity = (int) $_REQUEST['popup_image_order_opacity'];
		$req->popup_image_order_top_offset = (int) $_REQUEST['popup_image_order_top_offset'];
		$req->popup_show_orderdata = (int) $_REQUEST['popup_show_orderdata'];
		$req->popup_icons_opacity = (int) $_REQUEST['popup_icons_opacity'];
		$req->popup_show_icons = (int) $_REQUEST['popup_show_icons'];
		$req->popup_autoplay_default = (int) $_REQUEST['popup_autoplay_default'];
		$req->popup_closeonend = (int) $_REQUEST['popup_closeonend'];
		$req->popup_autoplay_time = (int) $_REQUEST['popup_autoplay_time'];
		$req->popup_open_event = (int) $_REQUEST['popup_open_event'];
		$req->link_open_event = (int) $_REQUEST['link_open_event'];
		
		$req->cis_touch_enabled = (int) $_REQUEST['cis_touch_enabled'];
		$req->cis_inf_scroll_enabled = (int) $_REQUEST['cis_inf_scroll_enabled'];
		$req->cis_mouse_scroll_enabled = (int) $_REQUEST['cis_mouse_scroll_enabled'];
		$req->cis_item_correction_enabled = (int) $_REQUEST['cis_item_correction_enabled'];
		
		$req->cis_animation_type = (int) $_REQUEST['cis_animation_type'];
		$req->cis_item_hover_effect = (int) $_REQUEST['cis_item_hover_effect'];
		$req->cis_items_appearance_effect = (int) $_REQUEST['cis_items_appearance_effect'];
		$req->cis_overlay_type = (int) $_REQUEST['cis_overlay_type'];
		$req->cis_touch_type = (int) $_REQUEST['cis_touch_type'];
		$req->cis_font_family = $_REQUEST['cis_font_family'];
		$req->cis_font_effect = $_REQUEST['cis_font_effect'];

		$req->icons_size = (int) $_REQUEST['icons_size'];
		$req->icons_margin = (int) $_REQUEST['icons_margin'];
		$req->icons_offset = (int) $_REQUEST['icons_offset'];
		$req->icons_animation = (int) $_REQUEST['icons_animation'];
		$req->icons_color = (int) $_REQUEST['icons_color'];
		$req->icons_valign = (int) $_REQUEST['icons_valign'];

		$req->ov_items_offset = (int) $_REQUEST['ov_items_offset'];
		$req->ov_items_m_offset = (int) $_REQUEST['ov_items_m_offset'];
		$req->cis_button_font_family = $_REQUEST['cis_button_font_family'];

		$req->custom_css = $_REQUEST['custom_css'];
		$req->custom_js = $_REQUEST['custom_js'];

		$req->slider_full_size = $_REQUEST['slider_full_size'];

		$response = array(0=>"no","1"=>0);

		//if id ==0, we add the record
		$req->id = NULL;

		//get max ordering
		$query = "SELECT MAX(`ordering`) FROM `#__cis_sliders`";
		$this->_db->setQuery($query);
		$max_order = $this->_db->loadResult();
		$max_order ++;

		$req->ordering = $max_order;

		if (!$this->_db->insertObject( '#__cis_sliders', $req, 'id' )) {
			$cis_error = "COM_CREATIVEIMAGESLIDER_ERROR_COPY_SLIDER";
			
			$response[0] = $cis_error;
			return $response;
		}
		$new_insert_id = $this->_db->insertid();
		$response[1] = $new_insert_id;

		//copy items
		$query = "SELECT * FROM `#__cis_images` WHERE `id_slider` = '".$id."'";
		$this->_db->setQuery($query);
		$fields = $this->_db->loadAssocList();

		// if(false) {
		if(is_array($fields)) {
			foreach($fields as $field) {

				$req = new JObject();

				$req->id_slider = $new_insert_id;
				$req->name =  $field['name'];
				$req->img_name = $field['img_name'];
				$req->img_url =  $field['img_url'];
				$req->caption =  $field['caption'];
				$req->showreadmore = $field['showreadmore'];
				$req->readmoretext =  $field['readmoretext'];
				$req->readmorestyle = $field['readmorestyle'];
				$req->readmoreicon = $field['readmoreicon'];
				$req->readmoresize = $field['readmoresize'];
				$req->overlaycolor =  $field['overlaycolor'];
				$req->overlayopacity = $field['overlayopacity'];
				$req->textcolor =  $field['textcolor'];
				$req->overlayfontsize = $field['overlayfontsize'];
				$req->textshadowcolor =  $field['textshadowcolor'];
				$req->textshadowsize = $field['textshadowsize'];
				$req->overlayusedefault = $field['overlayusedefault'];
				$req->buttonusedefault = $field['buttonusedefault'];
				$req->textshadowsize = $field['textshadowsize'];
				$req->readmorealign = $field['readmorealign'];
				$req->captionalign = $field['captionalign'];
				$req->readmoremargin = $field['readmoremargin'];
				$req->captionmargin = $field['captionmargin'];
				$req->redirect_url = $field['redirect_url'];
				$req->redirect_itemid =  $field['redirect_itemid'];
				$req->redirect_target =  $field['redirect_target'];

				$req->popup_img_name = $field['popup_img_name'];
				$req->popup_img_url =  $field['popup_img_url'];
				$req->popup_open_event =   $field['popup_open_event'];

				$req->published = $field['published'];


				$req->id = NULL;

				$this->_db->insertObject( '#__cis_images', $req, 'id' );

				$new_insert_id1 = $this->_db->insertid();

	
			}
		}
	
		return $response;
	}

	function deleteSlider($pks) {
		if(is_array($pks)) {
			foreach($pks as $f_id) {
				//delete form
				$query = "DELETE FROM `#__cis_sliders` WHERE `id` = '".$f_id."'";
				$this->_db->setQuery($query);
				$this->_db->query();

				//delete fields
				$query = "DELETE FROM `#__cis_images` WHERE `id_slider` = '".$f_id."'";
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
	}
}