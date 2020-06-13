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

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class CreativeimagesliderModelCreativeimage extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'CreativeImage', $prefix = 'CreativeImageTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	//get max id
	public function getMax_id()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query = 'SELECT COUNT(id) AS count_id FROM #__cis_images';
		$db->setQuery($query);
		$max_id = $db->loadResult();
		return $max_id;
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
		$form = $this->loadForm('com_creativeimageslider.creativeimage', 'creativeimage', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_creativeimageslider.edit.creativeimage.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	
	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}
	
	
	/**
	 * Method to save answer
	 */
	function saveImage()
	{
		$cis_error = 'no';
		
		$date = new JDate();
		$id = JRequest::getInt('id',0);
		
		$req = new JObject();
		$req->name =  str_replace('\\','', htmlspecialchars($_REQUEST['name'], ENT_QUOTES) );
		$req->img_name = $_REQUEST['jform']['img_name'];
		$req->img_url =  $_REQUEST['img_url'];
		$req->caption =  $_REQUEST['caption'];
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
		$req->overlayusedefault = (int)$_REQUEST['overlayusedefault'];
		$req->buttonusedefault = (int)$_REQUEST['buttonusedefault'];
		$req->textshadowsize = (int)$_REQUEST['textshadowsize'];
		$req->readmorealign = (int)$_REQUEST['readmorealign'];
		$req->captionalign = (int)$_REQUEST['captionalign'];
		$req->readmoremargin = $_REQUEST['readmoremargin'];
		$req->captionmargin = $_REQUEST['captionmargin'];
		$req->redirect_url = $_REQUEST['redirect_url'];
		$req->redirect_itemid =  (int)$_REQUEST['jform']['redirect_itemid'];
		$req->redirect_target =  (int)$_REQUEST['redirect_target'];

		$req->popup_img_name = $_REQUEST['jform']['popup_img_name'];
		$req->popup_img_url =  $_REQUEST['popup_img_url'];
		$req->popup_open_event =  (int) $_REQUEST['popup_open_event'];
		
		if($_REQUEST['jform']['img_name'] != '') {
			
			$id_slider = (int)$_REQUEST['id_slider'];
			$db = JFactory::getDBO();
			$query = "SELECT `height` FROM `#__cis_sliders` WHERE id = '".$id_slider."'";
			$db->setQuery($query);
			$item_height = $db->loadResult();
			
			$img_width = 0;
			$img_height = $item_height;
			$img_crop = false;
			//resize image
			$this->resize_image($_REQUEST['jform']['img_name'],$img_width,$img_height,$img_crop);
		}
	
		$req->id_slider = (int)$_REQUEST['id_slider'];
		$req->published = (int)$_REQUEST['published'];
	
		if($req->id_slider == 0 || $req->name == "") {
			$cis_error = 'COM_CREATIVEIMAGESLIDER_ERROR_SPECIFY_REQUIRED_FIELDS';
			return $cis_error;
		}
		elseif($_REQUEST['jform']['img_name'] == '' && $_REQUEST['img_url'] == '') {
			$cis_error = 'COM_CREATIVEIMAGESLIDER_ERROR_IMAGE_EMPTY';
			return $cis_error;
		}
		elseif($id == 0) {//if id ==0, we add the record
			$req->id = NULL;
			if(JV == 'j2')
				$req->created = $date->toMySQL();
			else
				$req->created = $date->toSql();
	
			if (!$this->_db->insertObject( '#__cis_images', $req, 'id' )) {
				$cis_error = "COM_CREATIVEIMAGESLIDER_ERROR_INSERT_DB";
				return $cis_error;
			}
		}
		else { //else update the record
			$req->id = $id;
			var_export($req);
			if (!$this->_db->updateObject( '#__cis_images', $req, 'id' )) {
				$cis_error = "COM_CREATIVEIMAGESLIDER_ERROR_UPDATE_DB";
				return $cis_error;
			}
		}
	
		return $cis_error;
	}

	/**
	 * Method to copy field
	 */
	function copyImage()
	{
		$id = JRequest::getInt('id',0);
		
		$req = new JObject();

		$req->name =  str_replace('\\','', htmlspecialchars($_REQUEST['name'], ENT_QUOTES) ) . ' (copy)';
		$req->img_name = $_REQUEST['jform']['img_name'];
		$req->img_url =  $_REQUEST['img_url'];
		$req->caption =  $_REQUEST['caption'];
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
		$req->overlayusedefault = (int)$_REQUEST['overlayusedefault'];
		$req->buttonusedefault = (int)$_REQUEST['buttonusedefault'];
		$req->textshadowsize = (int)$_REQUEST['textshadowsize'];
		$req->readmorealign = (int)$_REQUEST['readmorealign'];
		$req->captionalign = (int)$_REQUEST['captionalign'];
		$req->readmoremargin = $_REQUEST['readmoremargin'];
		$req->captionmargin = $_REQUEST['captionmargin'];
		$req->redirect_url = $_REQUEST['redirect_url'];
		$req->redirect_itemid =  (int)$_REQUEST['jform']['redirect_itemid'];
		$req->redirect_target =  (int)$_REQUEST['redirect_target'];

		$req->popup_img_name = $_REQUEST['jform']['popup_img_name'];
		$req->popup_img_url =  $_REQUEST['popup_img_url'];
		$req->popup_open_event =  (int) $_REQUEST['popup_open_event'];

		$req->id_slider = (int)$_REQUEST['id_slider'];
		$req->published = (int)$_REQUEST['published'];

		$req->id = NULL;

		$response = array(0=>"no","1"=>0);

		//get max ordering
		$query = "SELECT MAX(`ordering`) FROM `#__cis_images` WHERE `id_slider` = '".$req->id_slider."'";
		$this->_db->setQuery($query);
		$max_order = $this->_db->loadResult();
		$max_order ++;

		$req->ordering = $max_order;

		if (!$this->_db->insertObject( '#__cis_images', $req, 'id' )) {
			$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FIELD_COPIED";
			
			$response[0] = $cis_error;
			return $response;
		}
		$new_insert_id = $this->_db->insertid();
		$response[1] = $new_insert_id;


		return $response;
	}
	
	
	function resize_image($image,$width = 0,$height = 0, $crop = false)
	{
		$cache_dir = __DIR__ . '/../../../../cache/com_creativeimageslider/';
		if (!file_exists($cache_dir))
			@mkdir($cache_dir, 0755);
		
		// Make sure we can read and write the cache directory
		if (!is_readable($cache_dir))
		{
			//header('HTTP/1.1 500 Internal Server Error');
			$error = 'Error: the cache directory is not readable';
			return false;
		}
		else if (!is_writable($cache_dir))
		{
			$error = 'Error: the cache directory is not writable';
			return false;
		}
		
		//strip path
		$img_parts = explode('/',$image);
		$filename = $img_parts[sizeof($img_parts) - 1];
		preg_match('/^(.*)\.([a-z]{3,4}$)/i',$filename,$matches);
		$resized = $matches[1] . '-tmb-h' . $height . '.' . $matches[2];
		
		//get resized image
		$resized = $cache_dir . $resized;
		
		//unlink the image
		if(file_exists($resized))
			unlink($resized);
		
		//get image path
		$image = __DIR__ . '/../../../../' . $image;
		
		// Images must be local files, so for convenience we strip the domain if it's there
		$image			= preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', $image);
		
		// If the image doesn't exist, or we haven't been told what it is, there's nothing
		// that we can do
		if (!file_exists($image))
		{
			$error = 'There is no image';
			return false;
		}
	
		// Strip the possible trailing slash off the document root
		//$docRoot	= preg_replace('/\/$/', '', $_SERVER['DOCUMENT_ROOT']);
		$docRoot = '';
	
		$size	= GetImageSize($image);
		$mime	= $size['mime'];
	
		if (substr($mime, 0, 6) != 'image/')
		{
			$error = 'Wrong filetype';
			return false;
		}
		$maxWidth		= $width;
		$maxHeight		= $height;
	
		$width			= $size[0];
		$height			= $size[1];
	
		if (!$maxWidth && $maxHeight)
		{
			$maxWidth	= 99999999999999;
		}
		elseif ($maxWidth && !$maxHeight)
		{
			$maxHeight	= 99999999999999;
		}
		if ((!$maxWidth && !$maxHeight) || ($maxWidth >= $width && $maxHeight >= $height))
		{
			copy($image,$resized);
			return false;
		}
		
		// Ratio cropping
		$offsetX	= 0;
		$offsetY	= 0;
		
		if ($crop)
		{
			if ($width != 0 && $height != 0)
			{
				$ratioComputed		= $width / $height;
				$cropRatioComputed	= $maxWidth / $maxHeight;
		
				if ($ratioComputed < $cropRatioComputed)
				{ // Image is too tall so we will crop the top and bottom
					$origHeight	= $height;
					$height		= $width / $cropRatioComputed;
					$offsetY	= ($origHeight - $height) / 2;
				}
				else if ($ratioComputed >= $cropRatioComputed)
				{ // Image is too wide so we will crop off the left and right sides
					$origWidth	= $width;
					$width		= $height * $cropRatioComputed;
					$offsetX	= ($origWidth - $width) / 2;
				}
			}
		}
		
		$xRatio		= $maxWidth / $width;
		$yRatio		= $maxHeight / $height;
	
		if ($xRatio * $height < $maxHeight)
		{ // Resize the image based on width
			$tnHeight	= ceil($xRatio * $height);
			$tnWidth	= $maxWidth;
		}
		else // Resize the image based on height
		{
			$tnWidth	= ceil($yRatio * $width);
			$tnHeight	= $maxHeight;
		}
	
		$quality = 100;
	
		// Set up a blank canvas for our resized image (destination)
		$dst	= imagecreatetruecolor($tnWidth, $tnHeight);
	
		switch ($size['mime'])
		{
			case 'image/gif':
				// We will be converting GIFs to PNGs to avoid transparency issues when resizing GIFs
				// This is maybe not the ideal solution, but IE6 can suck it
				$creationFunction	= 'ImageCreateFromGif';
				$outputFunction		= 'ImagePng';
				$mime				= 'image/png'; // We need to convert GIFs to PNGs
				$doSharpen			= FALSE;
				$quality			= round(10 - ($quality / 10)); // We are converting the GIF to a PNG and PNG needs a compression level of 0 (no compression) through 9
				break;
	
			case 'image/x-png':
			case 'image/png':
				$creationFunction	= 'ImageCreateFromPng';
				$outputFunction		= 'ImagePng';
				$doSharpen			= FALSE;
				$quality			= round(10 - ($quality / 10)); // PNG needs a compression level of 0 (no compression) through 9
				break;
	
			default:
				$creationFunction	= 'ImageCreateFromJpeg';
				$outputFunction	 	= 'ImageJpeg';
				$doSharpen			= TRUE;
				break;
		}
		// Read in the original image
		$src	= $creationFunction($docRoot . $image);
	
		if (in_array($size['mime'], array('image/gif', 'image/png')))
		{
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
		}
	
		// Resample the original image into the resized canvas we set up earlier
		ImageCopyResampled($dst, $src, 0, 0, $offsetX, $offsetY, $tnWidth, $tnHeight, $width, $height);
	
		if ($doSharpen)
		{
			// Sharpen the image based on two things:
			//	(1) the difference between the original size and the final size
			//	(2) the final size
			$sharpness	= $this->findSharp($width, $tnWidth);
	
			$sharpenMatrix	= array(
					array(-1, -2, -1),
					array(-2, $sharpness + 12, -2),
					array(-1, -2, -1)
			);
			$divisor		= $sharpness;
			$offset			= 0;
			imageconvolution($dst, $sharpenMatrix, $divisor, $offset);
		}
		// Write the resized image to the cache
		$outputFunction($dst, $docRoot.$resized, $quality);
	
		ImageDestroy($src);
		ImageDestroy($dst);
	}
	
	function findSharp($orig, $final)
	{
		$final	= $final * (750.0 / $orig);
		$a		= 52;
		$b		= -0.27810650887573124;
		$c		= .00047337278106508946;
	
		$result = $a + $b * $final + $c * $final * $final;
	
		return max(round($result), 0);
	}
	
}