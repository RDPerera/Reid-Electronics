<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3.48F
	* Creation date: Janvier 2017
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;
 
class com_GMapFPInstallerScript
{
	/**
	 * method to install the component
	 * @return void
	 */
	function install($parent) 
	{
		$path = JPATH_SITE;
		@mkdir(JPATH_ROOT."/images/gmapfp/");
	
		//Installation du fichier CSS
		$filesource = $path .'/components/com_gmapfp/views/gmapfp/gmapfp3.css';
		$filedest = $path .'/components/com_gmapfp/views/gmapfp/gmapfp.css';
		JFile::copy($filesource, $filedest,null);
		
		$db = JFactory::getDBO();
		
		try {
			$db->transactionStart();
			
			/*mise à jour des données marqueurs*/
			$values = array();
			$values[] = array($db->quote(''), $db->quote('marqueur'), $db->quote('http://www.google.com/mapfiles/marker.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueur home'), $db->quote('http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|home|FFFF00|FF0000'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueur flag'), $db->quote('http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|flag|FFFF00|FF0000'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueur info'), $db->quote('http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|info|FFFF00|FF0000'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueur bar'), $db->quote('http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|bar|FFFF00|FF0000'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueur cafe'), $db->quote('http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|cafe|FFFF00|FF0000'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueur perso'), $db->quote('http://chart.apis.google.com/chart?chst=d_map_spin&chld=1.2|0|FF0000|10|_|foo|bar'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurA'), $db->quote('http://www.google.com/mapfiles/markerA.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurB'), $db->quote('http://www.google.com/mapfiles/markerB.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurC'), $db->quote('http://www.google.com/mapfiles/markerC.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurD'), $db->quote('http://www.google.com/mapfiles/markerD.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurE'), $db->quote('http://www.google.com/mapfiles/markerE.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurF'), $db->quote('http://www.google.com/mapfiles/markerF.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurG'), $db->quote('http://www.google.com/mapfiles/markerG.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurH'), $db->quote('http://www.google.com/mapfiles/markerH.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurI'), $db->quote('http://www.google.com/mapfiles/markerI.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurJ'), $db->quote('http://www.google.com/mapfiles/markerJ.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurK'), $db->quote('http://www.google.com/mapfiles/markerK.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurL'), $db->quote('http://www.google.com/mapfiles/markerL.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurM'), $db->quote('http://www.google.com/mapfiles/markerM.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurN'), $db->quote('http://www.google.com/mapfiles/markerN.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurO'), $db->quote('http://www.google.com/mapfiles/markerO.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurP'), $db->quote('http://www.google.com/mapfiles/markerP.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurQ'), $db->quote('http://www.google.com/mapfiles/markerQ.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurR'), $db->quote('http://www.google.com/mapfiles/markerR.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurS'), $db->quote('http://www.google.com/mapfiles/markerS.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurT'), $db->quote('http://www.google.com/mapfiles/markerT.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurU'), $db->quote('http://www.google.com/mapfiles/markerU.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurV'), $db->quote('http://www.google.com/mapfiles/markerV.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurW'), $db->quote('http://www.google.com/mapfiles/markerW.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurX'), $db->quote('http://www.google.com/mapfiles/markerX.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurY'), $db->quote('http://www.google.com/mapfiles/markerY.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurZ'), $db->quote('http://www.google.com/mapfiles/markerZ.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurBleu'), $db->quote('http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/blue-dot.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurVert'), $db->quote('http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/green-dot.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurOrange'), $db->quote('http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/orange-dot.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurJaune'), $db->quote('http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/yellow-dot.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurViolet'), $db->quote('http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/purple-dot.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('marqueurRose'), $db->quote('http://maps.gstatic.com/intl/fr_ALL/mapfiles/ms/micons/pink-dot.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('purple'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_purple.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('yellow'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_yellow.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('blue'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_blue.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('white'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_white.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('green'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_green.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('red'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_red.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('black'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_black.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('orange'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_orange.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('gray'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_gray.png'), $db->quote('1'));
			$values[] = array($db->quote(''), $db->quote('brown'), $db->quote('http://labs.google.com/ridefinder/images/mm_20_brown.png'), $db->quote('1'));


			$query = $db->getQuery(true);
			$query->insert($db->quoteName('#__gmapfp_marqueurs'));
			$query->columns($db->quoteName(array('id', 'nom', 'url', 'published')));
			foreach ($values as $value) {
				$query->values(implode(',' ,$value));
			}
			$db->setQuery($query);
			$db->execute();
			
			$db->transactionCommit();
		} catch (Execption $e) {
			$db->transactionRoolback();
			JErrorPage::render($e);
		}
		$this->update_plg(true);
	}

	/**
	 * method to uninstall the component
	 * @return void
	 */
	function uninstall($parent) 
	{
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->delete('#__menu');
		$query->where("menutype = 'menu'");
		$query->where('path LIKE '.$db->quote('gmapfp%'));
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);
		$query->delete('#__extensions');
		$query->where("name = 'plg_system_gmapfp'");
		$db->setQuery($query);
		$db->execute();

		$path_folder = JPATH_SITE.'/plugins/system/cp';
		JFolder::delete($path_folder);
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		$this->update_plg(false);
	}

	function update_plg ($install) {

		$path_source = JPATH_ADMINISTRATOR.'/components/com_gmapfp/models/fields/modal';
		if(file_exists($path_source.'/cp.xml')) {
			$path_dest = JPATH_SITE.'/plugins/system/cp';
			@mkdir($path_dest);
			$file = '/cp.xml';
			JFile::copy($path_source.$file, $path_dest.$file,null);
			$file = '/cp.php';
			JFile::copy($path_source.$file, $path_dest.$file,null);
			$file = '/index.html';
			JFile::copy($path_source.$file, $path_dest.$file,null);
		}


		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$db->setQuery("SELECT extension_id FROM  `#__extensions` WHERE  `name` =  \"plg_system_cp\"");
		$old_name = $db->loadResult();
		
		$query = $db->getQuery(true);
		$db->setQuery("SELECT extension_id FROM  `#__extensions` WHERE  `name` =  \"plg_system_gmapfp\"");
		$new_name = $db->loadResult();
		
		//supprime l'ancienne extension
		if ($old_name) {
			$query = $db->getQuery(true);
			$db->setQuery("DELETE FROM  `#__extensions` WHERE  `extension_id` = ".(int)$old_name);
			$db->query();
		}
		
		if (!@$new_name) {
			$query = $db->getQuery(true);
			$db->setQuery("SELECT MAX(  `extension_id` ) as id FROM  `#__extensions` WHERE  `type` =  \"plugin\" AND (`extension_id` > 8000 AND `extension_id` < 10000)");
			$id = (int)$db->loadResult();
			if (empty($id) or $id=='') $id = 9998;
			$id = $id + 1;
			if ($id == 10000) $id = 0;
			// Enable mod_
			$query = $db->getQuery(true);
			$db->setQuery("INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data)
			VALUES (".((int)$id).", 'plg_system_gmapfp', 'plugin', 'cp', 'system', 0, 1, 1, 1, '{\"name\":\"plg_system_gmapfp\",\"type\":\"plugin\",\"creationDate\":\"December 2006\",\"author\":\"Fabrice4821\",\"copyright\":\"Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.\",\"authorEmail\":\"webmaster@gmapfp.org\",\"authorUrl\":\"www.gmapfp.org\",\"version\":\"3.0.0\",\"description\":\"PLG_GMAPFP_COPYRIGHT_DESCRIPTION\",\"group\":\"\"}', '{}', '', '')");
			$db->query();
		}
	}

	function affiche_bienvenue($install) {
		$lang		= JFactory::getLanguage();
		$langue		= substr((@$lang->getTag()),0,2);
		if ($langue!='fr') $langue = 'en';

		if ($langue=='fr') {
			if ($install == 1) {
				echo "<h1>GMapFP Installation</h1>";
			}else{
				echo "<h1>GMapFP Mise &agrave; jour</h1>";
			};
			?>
			<a href="http://gmapfp.org/fr/" target="_blank"><img src="../administrator/components/com_gmapfp/images/gmapfp_logo.png" title="Visit&eacute; le site : GMapFP.org" alt="Visit&eacute; le site : GMapFP.org" style="float:left; margin: 2px 25px 2px 0px;"/></a>
			<p>Bienvenue sur GMapFP v<?php echo $this->release?> !</p>
			<p>Avant de commencer, je vous invite, si ce n'est pas d&eacute;j&agrave; fait, &agrave; d&eacute;couvrir toutes les possibilit&eacute;s de se composant et de son ou ses plugins sur son <a target="_blank" href="http://gmapfp.org/fr">Site officiel</a>.<br />
			Vous pourrez y <a target="_blank" href="http://gmapfp.org/fr/telechargement">t&eacute;l&eacute;charger</a> les mise &agrave; jours et consulter le <a target="_blank" href="http://gmapfp.org/fr/forum"> forum</a>.</p>
			<p>Bonne continuation avec GMapFP</p>
			<?php
		} else {
			if ($install == 1) {
				echo "<h1>GMapFP Installation</h1>";
			}else{
				echo "<h1>GMapFP Upgrade</h1>";
			};
			?>
			<a href="http://gmapfp.org/en/" target="_blank"><img src="../administrator/components/com_gmapfp/images/gmapfp_logo.png" title="Visited the site : GMapFP.org" alt="Visited the site : GMapFP.org" style="float:left; margin: 2px 25px 2px 0px;"/></a>
			<p>Welcome on v<?php echo $this->release?> GMapFP !</p>
			<p>Before starting, I invite you, if this isn't already made, to discovery all the possibilities of this component and thisd plugin on its <a target="_blank" href="http://www.gmapfp.org/en">Official Site</a>.<br />
			You will be able there to <a target="_blank" href="http://gmapfp.org/en/download">download</a> the update and consult the <a target="_blank" href="http://gmapfp.org/en/forum"> forum</a>.</p>
			<p>Good continuation with GMapFP</p>
			<?php
		}
	}
	
	function createcat($nom) 
	{
		// Initialize a new category
		/** @type  JTableCategory  $category  */
		$category = JTable::getInstance('Category');

		// Check if the Uncategorised category exists before adding it
		if (!$category->load(array('extension' => 'com_gmapfp', 'title' => $nom)))
		{
			$category->extension = 'com_gmapfp';
			$category->title = $nom;
			$category->description = '';
			$category->published = 1;
			$category->access = 1;
			$category->params = '{"category_layout":"","image":""}';
			$category->metadata = '{"author":"","robots":""}';
			$category->metadesc = '';
			$category->metakey = '';
			$category->language = '*';
			$category->checked_out_time = JFactory::getDbo()->getNullDate();
			$category->version = 1;
			$category->hits = 0;
			$category->modified_user_id = 0;
			$category->checked_out = 0;

			// Set the location in the tree
			$category->setLocation(1, 'last-child');

			// Check to make sure our data is valid
			if (!$category->check())
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_GMAPFP_ERROR_INSTALL_CATEGORY', $category->getError()));

				return;
			}

			// Now store the category
			if (!$category->store(true))
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_GMAPFP_ERROR_INSTALL_CATEGORY', $category->getError()));

				return;
			}

			// Build the path for our category
			$category->rebuildPath($category->id);
		}
		
	}
	public function getAssoc()
	{
		return false;
	}
	public function getTable($type = 'Category', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getState()
	{
		return 0;
	}
	public function setState()
	{
		return;
	}
	public function getName()
	{
		return 0;
	}
	public function setName()
	{
		return;
	}
	public function cleanCache()
	{
		return ;
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		if ($type == 'install') {

			$db = JFactory::getDBO();
			
			try {
				$db->transactionStart();
				
				/*mise à jour des données marqueurs*/
				$value = 'params = '.$db->quote('{"gmapfp_height":"500", "gmapfp_width":"100%", "gmapfp_auto":"1", "gmapfp_centre_lng":"2.1391367912", "gmapfp_centre_lat":"47.927644470", "gmapfp_auto_zoom":"0", "gmapfp_zoom":"2", "gmapfp_zoom":"10", "gmapfp_itineraire":"1", "gmapfp_filtre":"1", "gmapfp_msg":"1", "gmapfp_typecontrol":"1", "gmapfp_normal":"1", "gmapfp_satellite":"1", "gmapfp_hybrid":"1", "gmapfp_physic":"1", "gmapfp_choix_affichage_carte":"1", "gmapfp_mapcontrol":"1", "gmapfp_scalecontrol":"1", "gmapfp_mousewheel":"1", "gmapfp_zzoom":"1", "gmapfp_eventcontrol":"1", "gmapfp_plus_detail":"1", "target":"0", "gmapfp_hauteur_lightbox":"400", "gmapfp_largeur_lightbox":"700", "gmapfp_plus_info":"1", "gmapfp_url_wiki":"org.wikipedia.fr", "gmapfp_afficher_horaires_prix":"1", "gmapfp_afficher_intro_italique":"1", "gmapfp_chemin_img":"\/images\/gmapfp\/", "gmapfp_hauteur_img":"100", "gmapfp_width_bulle_GMapFP":"400", "gmapfp_taille_bulle_cesure":"200", "gmapfp_geoXML":"", "gmapfp_news":"1", "gmapfp_licence":"1"}');

				$query = $db->getQuery(true);
				$query->update($db->quoteName('#__extensions'));
				$query->set($value);
				$query->where($db->quoteName('name').'= '.$db->quote('com_gmapfp'));
				$db->setQuery($query);
				$db->execute();
				
				$db->transactionCommit();
			} catch (Execption $e) {
				$db->transactionRoolback();
				JErrorPage::render($e);
			}
			
			/*mise à jour des paramètres par défaut*/
			$db->setQuery($query);
			$db->query();
			if ($db->getErrorNum()) {
				exit($db->stderr());
			}

			//Insertion des catégories exemples
			$this->createcat('GMapFP cat exemple');

			//Insertion des exemples
			$query = $db->getQuery(true);
			$query = "SELECT id FROM `#__categories` WHERE `extension`='com_gmapfp';";
			$db->setQuery($query);
			$cat = $db->loadColumn();
			if ($db->getErrorNum()) {
				exit($db->stderr());
			}

			if (!empty($cat[0])) {
				$query = $db->getQuery(true);
				$query = "INSERT INTO `#__gmapfp` (`id`, `nom`, `alias`, `adresse`, `adresse2`, `ville`, `departement`, `codepostal`, `pay`, `tel`, `tel2`, `fax`, `email`, `web`, `img`, `album`, `intro`, `message`, `horaires_prix`, `link`, `article_id`, `icon`, `icon_label`, `affichage`, `marqueur`, `glng`, `glat`, `gzoom`, `catid`, `userid`, `published`, `checked_out`, `metadesc`, `metakey`, `ordering`) VALUES
					(1, 'GMapFP d&#233;veloppement', 'gmapfp-developpement', '', '', 'Fay-aux-Loges', 'Loiret', '45450', 'France', '', '', '', '', 'http://creation-web.pro/', 'gmapfp_logo.png', 0, '<p>GmapFP D&#233;veloppement :</p>\r\n<ul>\r\n<li>Cr&#233;ation de site web dans la r&#233;gion d''Orl&#233;ans Est.</li>\r\n<li>Cr&#233;ation d''extensions Joomla.</li>\r\n</ul>', NULL, '', '', 0, '', '', 0, 'http://chart.apis.google.com/chart?chst=d_map_xpin_icon&chld=pin_star|info|FFFF00|FF0000', '2.1462339161', '47.914774458', '11', ".$cat[0].", 531, 1, 0, 'GmapFP Développement : Création de site web dans la région d''Orléans Est. Création d''extensions Joomla.', '', 1);";
				$db->setQuery($query);
				$db->query();
				if ($db->getErrorNum()) {
					exit($db->stderr());
				}
			}
			
			$this->affiche_bienvenue(1);

		}else
			$this->affiche_bienvenue(0);

	}
	
	function preflight( $type, $parent ) {
        // Installing component manifest file version
        $this->release = $parent->get( "manifest" )->version;
	}

}