<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_52F
	* Creation date: Janvier 2018
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

defined('_JEXEC') or die;

class GMapFPsModelGMapFP extends JModelLegacy
{
	private $_catid = null;
	private $_result = null;
	private $_result2 = null;
	
	function __construct() 
    { 
        parent::__construct(); 
		$app = JFactory::getApplication('site');
		if ($app->getName()=='site') {
			$params = $app->getParams();
		} else {
			$params = JComponentHelper::getParams('com_gmapfp'); 
		}

        $this->_layout = JRequest::getVar('layout', '', '', 'str'); 
        $this->_id = JRequest::getVar('id', 0, '', 'int'); 

        $this->_catid = $params->get('catid', 0);
        $this->_total   = null; 
        $this->_result  = null; 
        $this->_result2 = null; 
        $this->_result_personnalisation = null; 
		$this->_num_marqueurs = 0;
		
		$doc		= JFactory::getDocument();
		JHTML::_('behavior.modal');
		$largeur = $params->get('gmapfp_largeur_lightbox', '700');
		$hauteur = $params->get('gmapfp_hauteur_lightbox', '400');
		$script = '
		<script type="text/javascript">
				var gmapfp_largeur_lightbox = '.(int)$largeur.';
				var gmapfp_hauteur_lightbox = '.(int)$hauteur.';
		</script>
		';
		$doc->addCustomTag($script);

   }

	public function set_ids($value)
	{
		$this->_ids = $value;
		return true;
	}
	
	function define_gmapfp() 
	{
		$mainframe	= JFactory::getApplication(); 
        $params		= clone($mainframe->getParams('com_gmapfp'));
		$doc		= JFactory::getDocument();
        $lang		= JFactory::getLanguage(); 
        $tag_lang=$lang->getTag();
		if (($tag_lang!='en-AU') AND ($tag_lang!='en-GB') AND ($tag_lang!='pt-BR') AND ($tag_lang!='pt-PT') AND ($tag_lang!='zh-CN') AND ($tag_lang!='zh-TW'))
			{$tag_lang=(substr($lang->getTag(),0,2)); };
        
		// Définition de JUri::base() sans tenir compte du paramètre $config->get('live_site') de la config principale
		$uri = JUri::getInstance();
		$base['prefix'] = $uri->toString(array('scheme', 'host', 'port'));
		if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI']))
			$script_name = $_SERVER['PHP_SELF'];
		else $script_name = $_SERVER['SCRIPT_NAME'];
		$base['path'] = rtrim(dirname($script_name), '/\\');
		$Base_URL = $base['prefix'] . $base['path'] . '/';
		if (!defined( '_JOS_GMAPFP_BASE_URL' ))
			define('_JOS_GMAPFP_BASE_URL', $Base_URL);
		$https = "";
		if ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) $https = "s";
		
        //Insertion des entêtes GMapFP si non déjà fait.
        if (!defined( '_JOS_GMAPFP_CSS' ))
        {
            /** verifi que la fonction n'est défini qu'une faois */
            define( '_JOS_GMAPFP_CSS', 1 );
    
            $doc->addCustomTag( '<link rel="stylesheet" href="'.$Base_URL.'components/com_gmapfp/views/gmapfp/gmapfp2.css" type="text/css" />'); 
            $doc->addCustomTag( '<link rel="stylesheet" href="'.$Base_URL.'components/com_gmapfp/views/gmapfp/gmapfp.css" type="text/css" />'); 
        }
        
        if (!defined( '_JOS_GMAPFP_APIV3' ))
        {
            /** verifi que la fonction n'est défini qu'une fois */
            define( '_JOS_GMAPFP_APIV3', 1 );
			//&region=GB //pour ameliorer la zone de recherche d'une adresse
			$librarie = array();
			if ($params->get('gmapfp_auto_complete', 1)) 	$librarie[] = 'places';
			if ($params->get('gmapfp_plus_info', 1) or $params->get('gmapfp_enable_pano_photos', 1)) 		$librarie[] = 'panoramio';
			$libraries= "";
			if (count($librarie) > 0) {
				$libraries = '&libraries=';
				$libraries .= implode(',', $librarie);
			}
			$key = $params->get('gmapfp_google_key');
			
            $doc->setMetaData( "viewport", "initial-scale=1.0, user-scalable=no");
            $doc->addScript( 'http'.$https.'://maps.googleapis.com/maps/api/js?language='.$tag_lang.$libraries.'&key='.$key);
			$doc->addScriptDeclaration( "var base_url_gmapfp = '".$Base_URL."';"."\n");
            $doc->addScript( $Base_URL.'components/com_gmapfp/libraries/map.js');

        }
    }

	function enfants_catid($id)
	{
		$app = JFactory::getApplication();
		$params = $app->getParams();

		$ids[] = $id;
		if ($params->get('recursive', 1)) {
			//renvoie tous les enfants de $id trié par parent et ordre alphabétique
			$count_ids = count($ids);
			
			for ($i = 0; $i < $count_ids; $i++) {
				if ($this->_enfant($ids[$i])) {
					$ids = array_merge(array_slice($ids, 0, $i+1), $this->_enfant($ids[$i]), array_slice($ids, $i+1));
					$count_ids = count($ids);
				}
			}
		}

		return $ids;
	}

	function _getQuery()
    {
        $db     = JFactory::getDBO();
		$query = $db->getQuery(true);
        
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 
        $params	   = clone($mainframe->getParams('com_gmapfp'));
        $tri       = $params->get('orderby_pri');

 		$user_where = $params->get('gmapfp_filtre_sql');
		if (!empty($user_where))
			$wheres[] = @$params->get('gmapfp_filtre_sql');

       switch ($tri) {
        case 'alpha' :
            $order = "a.nom";
            break;
        case 'ralpha' :
            $order = "a.nom DESC";
            break;
        case 'ville' :
            $order = array("a.ville", "a.nom");
            break;
        case 'rville' :
            $order = array("a.ville DESC", "a.nom DESC");
            break;
        case 'pays' :
            $order = array("a.pay", "a.departement", "a.ville", "a.nom");
            break;
        case 'rpays' :
            $order = array("a.pay DESC", "a.departement DESC", "a.ville DESC", "a.nom DESC");
            break;
        case 'paysvilles' :
            $order = array("a.pay", "a.ville", "a.nom");
            break;
        case 'rpaysvilles' :
            $order = array("a.pay DESC", "a.ville DESC", "a.nom DESC");
            break;
        default :
            $order = "a.ordering";
            break;
        }

        $select = 'a.*, b.title, b.description as cat_description, c.catid as article_id, '.
				' (SELECT d.alias FROM #__categories AS d WHERE d.id=c.catid) as article_alias , '.
				' (SELECT d.id FROM #__categories AS d WHERE d.id=c.catid) as article_id ';

        $wheres[] = 'a.published = 1';
		
		// Filter by access level.
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$wheres[] ='(b.access IN ('.$groups.') or (b.access = ""))';

		if ($this->_catid) {
        	$_catids = $this->enfants_catid($this->_catid);
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( "\n OR ", $wheresOr).')';
        };

        if (!empty($this->_id)) {
            $wheres[] = 'a.id = '.$this->_id.'';
        };
		
		if ($params->get('gmapfp_filtre')) {
			$filtreville = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 'string' );
			$filtredepartement = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 'string' );
			$filtrepays = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrepays', 'filtrepays', '-- '.JText::_( 'GMAPFP_PAYS_FILTRE' ).' --', 'string' );
			$filtrecategorie = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrecategorie', 'filtrecategorie', '-- '.JText::_( 'GMAPFP_CATEGORIE_FILTRE' ).' --', 'string' );
			$search	= $mainframe->getUserStateFromRequest( $option.$Itemid.'search_gmapfp', 'search_gmapfp', '', 'string' );
			$search	= JString::strtolower( $search );

			if ($search) {
				$wheres[] = 'LOWER(a.nom) LIKE '.$db->Quote('%'.$search.'%');
			}

			if ($filtreville{0}<>'-') {
				$wheres[] = 'ville = '.$db->Quote($this->_getVilleByID($filtreville));
			}
			if ($filtredepartement{0}<>'-') {
				$wheres[] = 'departement = '.$db->Quote($this->_getDepartementByID($filtredepartement));
			}
			if ($filtrepays{0}<>'-') {
				$wheres[] = 'pay = '.$db->Quote($this->_getPaysByID($filtrepays));
			}	
			if ($filtrecategorie{0}<>'-') {
				$wheres[] = 'b.id = '.$filtrecategorie;
			}
		};

		$query->select($select);
		$query->select(' CASE WHEN '.$query->charLength('a.alias').' THEN '.$query->concatenate(array('a.id', 'a.alias'), ':').' ELSE a.id END as slug ');
		$query->select(' CASE WHEN '.$query->charLength('b.alias').' THEN '.$query->concatenate(array('b.id', 'b.alias'), ':').' ELSE b.id END as catslug ');
		$query->select(' CASE WHEN '.$query->charLength('c.alias').' THEN '.$query->concatenate(array('c.id', 'c.alias'), ':').' ELSE c.id END as article_slug ');
		$query->from('#__gmapfp AS a');
		$query->join('INNER', '#__categories AS b on a.catid = b.id');
		$query->join('LEFT OUTER', '#__content AS c on a.article_id = c.id');
		$query->where(implode( "\n  AND ", $wheres ));
		$query->order($order);
		$db->setQuery($query);

//die(print_r($query));
        return $db;
    }

	function _getQuery_orderA()
    {
        $db     = JFactory::getDBO();
        
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 
        $params = clone($mainframe->getParams('com_gmapfp'));

        $order = "\n ORDER BY a.nom";

        $select = 'a.*, b.title, b.description as cat_description';
        $from   = '#__gmapfp AS a, #__categories AS b';

		$user_where = $params->get('gmapfp_filtre_sql');
		if (!empty($user_where))
			$wheres[] = @$params->get('gmapfp_filtre_sql');

        $wheres[] = 'a.published = 1';
        $wheres[] = 'a.catid = b.id';
		
		if ($this->_catid) {
        	$_catids = $this->enfants_catid($this->_catid);
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( "\n OR ", $wheresOr).')';
        };

        if (!empty($this->_id)) {
            $wheres[] = 'a.id = '.$this->_id.'';
        };

		if ($params->get('gmapfp_filtre')==1) {
			$filtreville = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 'string' );
			$filtredepartement = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 'string' );
			$filtrepays = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrepays', 'filtrepays', '-- '.JText::_( 'GMAPFP_PAYS_FILTRE' ).' --', 'string' );
			$filtrecategorie = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrecategorie', 'filtrecategorie', '-- '.JText::_( 'GMAPFP_CATEGORIE_FILTRE' ).' --', 'string' );
	
			if ($filtreville{0}<>'-') {
				$wheres[] = 'ville = '.$db->Quote($this->_getVilleByID($filtreville));
			}
			if ($filtredepartement{0}<>'-') {
				$wheres[] = 'departement = '.$db->Quote($this->_getDepartementByID($filtredepartement));
			}
			if ($filtrepays{0}<>'-') {
				$wheres[] = 'pay = '.$db->Quote($this->_getPaysByID($filtrepays));
			}	
			if ($filtrecategorie{0}<>'-') {
				$wheres[] = 'b.id = '.$filtrecategorie;
			}
		};

        $query = "SELECT " . $select .
                "\n FROM " . $from .
                "\n WHERE " . implode( "\n  AND ", $wheres ).
                $order;
        return $query;
    }

	function verif_catid($id)
	{
		/*jimport( 'joomla.application.categories' );
		$categories = JCategories::getInstance('Gmapfp', '');
		$item_categories = $categories->get($id);*/
		
		//renvoie tous les enfants de $id trié par parent et ordre alphabétique
		$ids[] = $id;
		$count_ids = count($ids);
		
		for ($i = 0; $i < $count_ids; $i++) {
			if ($this->_enfant($ids[$i]))
				$ids = array_merge(array_slice($ids, 0, $i+1), $this->_enfant($ids[$i]), array_slice($ids, $i+1));
			$count_ids = count($ids);
		}

		return $ids;
	}

	function _enfant($id)
	{
		$db	= JFactory::getDBO();
		$query = "SELECT id" .
				"\n FROM #__categories" .
				"\n WHERE parent_id = ".$id .
				"\n AND published = 1" .
				"\n ORDER BY title";
    	$db->setQuery( $query );
		$result = $db->loadColumn();
		return $result;
	}
	
	function getPersonnalisation()
	{
		$mainframe = JFactory::getApplication(); 
		$db	= JFactory::getDBO();
        $params = clone($mainframe->getParams('com_gmapfp'));
		$perso  = $params->get('id_perso', 0);

		$query = "SELECT *" .
				"\n FROM #__gmapfp_personnalisation" .
				"\n WHERE id = ".$perso;

		if (empty($this->_result_personnalisation))
		{
			$db->setQuery( $query );
			$this->_result_personnalisation = $db->loadObject();
		}
		return @$this->_result_personnalisation;
	}
	
	function getTotal()
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 

		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$db = $this->_getQuery();
			$this->_total = count($db->loadObjectList());
		}
		return $this->_total;
	}
	
	function _getQueryPlugin( $options )
	{
		$db			= JFactory::getDBO();
		$query = $db->getQuery(true);

		foreach ($options as $option)
		{
			$wheresOr[]= 'a.id = \''.$option.'\'';
		}
		$wheres[]="(".implode( "\n OR ", $wheresOr ).")";

		$select = 'a.*, b.title, c.catid as article_id,'.
				' (SELECT d.alias FROM #__categories AS d WHERE d.id=c.catid) as article_alias , '.
				' (SELECT d.id FROM #__categories AS d WHERE d.id=c.catid) as article_id ';

		$wheres[] = 'a.published = 1';

		$user = JFactory::getUser();
		$aid = $user->get('aid', 0);
		//$wheres[] = 'b.access <= '.(int) $aid;

		$query->select($select);
		$query->select(' CASE WHEN '.$query->charLength('a.alias').' THEN '.$query->concatenate(array('a.id', 'a.alias'), ':').' ELSE a.id END as slug ');
		$query->select(' CASE WHEN '.$query->charLength('b.alias').' THEN '.$query->concatenate(array('b.id', 'b.alias'), ':').' ELSE b.id END as catslug ');
		$query->select(' CASE WHEN '.$query->charLength('c.alias').' THEN '.$query->concatenate(array('c.id', 'c.alias'), ':').' ELSE c.id END as article_slug ');
		$query->from('#__gmapfp AS a');
		$query->join('INNER', '#__categories AS b on a.catid = b.id');
		$query->join('LEFT OUTER', '#__content AS c on a.article_id = c.id');
		$query->where(implode( "\n  AND ", $wheres ));
		$db->setQuery($query);

		return $db;
	}
	
	function _getQueryPluginCatid( $options )
	{
		$db			= JFactory::getDBO();
		$query = $db->getQuery(true);

		foreach ($options as $option)
		{
			$wheresOr[]= 'a.catid = \''.$option.'\'';
		}
		$wheres[]="(".implode( "\n OR ", $wheresOr ).")";

		$select = 'a.*, b.title, c.catid as article_id,'.
				' (SELECT d.alias FROM #__categories AS d WHERE d.id=c.catid) as article_alias , '.
				' (SELECT d.id FROM #__categories AS d WHERE d.id=c.catid) as article_id ';
				
		$wheres[] = 'a.published = 1';

		$user = JFactory::getUser();
		$aid = $user->get('aid', 0);
		//$wheres[] = 'b.access <= '.(int) $aid;

		$query->select($select);
		$query->select(' CASE WHEN '.$query->charLength('a.alias').' THEN '.$query->concatenate(array('a.id', 'a.alias'), ':').' ELSE a.id END as slug ');
		$query->select(' CASE WHEN '.$query->charLength('b.alias').' THEN '.$query->concatenate(array('b.id', 'b.alias'), ':').' ELSE b.id END as catslug ');
		$query->select(' CASE WHEN '.$query->charLength('c.alias').' THEN '.$query->concatenate(array('c.id', 'c.alias'), ':').' ELSE c.id END as article_slug ');
		$query->from('#__gmapfp AS a');
		$query->join('INNER', '#__categories AS b on a.catid = b.id');
		$query->join('LEFT OUTER', '#__content AS c on a.article_id = c.id');
		$query->where(implode( "\n  AND ", $wheres ));
		$db->setQuery($query);

		return $db;
	}
	
	function getGMapFPList( $options=array() )
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 

		if (empty($this->_result2))
		{
			$db	= $this->_getQuery( $options );
			$this->_result2 = $db->loadObjectList();
		}
		return @$this->_result2;
	}
	
	function getView()
	{
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 

		if (empty($this->_result)) {
			$db 	= $this->_getQuery();
			$this->_result = $db->loadObjectList();
			
			$query 	= $this->_getQuery_orderA();
			$this->_result_orderA = $this->_getList( $query );
		};
		if ($this->_result) {
			$map	= $this->getCarte($this->_result, $this->_result_orderA, 0, 0, 0);
		}else{
			$map	= JText::_('GMAPFP_AUCUNE_INFO')."</br></br>";
		};
		return $map;
	}

	//Integration des données du plugin
	function getViewPlugin($ids, $num, $Hmap='0', $Lmap='0', $Zmap='0', $Itin='0', $bar_PSM='0', $bar_z_nav='0', $Ech='0', $click_over='0', $MZoom='0', $ZZoom='0', $map_phy='0', $map_nor='0', $map_sat='0', $map_hyb='0', $map_choix='0', $kml_file='0', $map_earth='0', $map_centre_lng='0', $map_centre_lat='0', $plugcatids, $More='0', $map_centre_id='0')
	{
		//rechercher du centrage d'un id
		if ($map_centre_id!=0) {
			$map_centre_ids[]=$map_centre_id;
			$db 	= $this->_getQueryPlugin($map_centre_ids);
			$result = $db->loadObjectList();
			if ($result) {
				$map_centre_lng = $result[0]->glng;
				$map_centre_lat = $result[0]->glat;
				if ($Zmap==0) {
					$Zmap = $result[0]->gzoom;
				};
			}
		}

		//rechercher des lieux par id
		if ($ids!=0) {
			$db 	= $this->_getQueryPlugin($ids);
			$result = $db->loadObjectList();
			foreach($result as $lieu) {
				$rows[] = $lieu;
			};
		}

		//recherche des catégories des groupes de catégories
        if ($plugcatids!=0) {
			$catids = array();
			foreach ($plugcatids as $plugcatid) {
				$catids = array_merge($catids, $this->verif_catid($plugcatid));
			};
        }else{
			$catids=0;
		};

		//recherche des lieux par catégorie
		if ($catids!=0) {
			$db 	= $this->_getQueryPluginCatid($catids);
			$result = $db->loadObjectList();
			foreach($result as $lieu) {
				$rows[] = $lieu;
			};
		}
		
		//Isolation des id des lieux du plugin
		if (!(@$rows)) {
			$language = JFactory::getLanguage();
			$language->load('com_gmapfp');
			JError::raiseWarning(0, JText::_('GMAPFP_MAUVAIS_ID_PLUGIN'));
			return(JText::_('GMAPFP_MAUVAIS_ID_PLUGIN'));
		}else{
			//tri par ordre alphabétique pour la liste itinèraire
			$rows_orderA = $rows;
			foreach ($rows_orderA as $row) {
				$rows_orderA_id[]=$row->id;
				$rows_orderA_nom[]=$row->nom;
			}
			$array_lowercase = array_map('strtolower', $rows_orderA_nom);
			array_multisort($array_lowercase, SORT_ASC,  $rows_orderA_id, SORT_ASC, $rows_orderA);
			//fin de la procédure de tri

		//appel de la carte
		$map	= $this->getCarte($rows, $rows_orderA, $map_centre_lat, $map_centre_lng, 1, $num, $Hmap, $Lmap, $Zmap, $Itin, $bar_PSM, $bar_z_nav, $Ech, $click_over, $MZoom, $ZZoom, $map_phy, $map_nor, $map_sat, $map_hyb, $map_choix, $kml_file, $map_earth, $More);
	return $map;
		};
	}
	
	function getCarte ($rows, $rows_orderA, $glat_plugin, $glng_plugin, $plugin, $num = '', $Hmap = '', $Lmap = '', $Zmap = '',  $Itin='0', $bar_PSM='0', $bar_z_nav='0', $Ech='0', $click_over='0', $MZoom='0', $ZZoom='0', $map_phy='0', $map_nor='0', $map_sat='0', $map_hyb='0', $map_choix='0', $kml_file='0', $map_earth='0', $More='0')
	{
		$doc		= JFactory::getDocument();

		$loadmarqueur = '';
		$cnt = 1;
		foreach($rows as $row) {
			$loadmarqueur .=" markerImage".$num."[".$cnt."] = new Image(); ";
			if ($row->marqueur) {
				if (substr($row->marqueur, 0, 2) == '..') $row->marqueur = substr($row->marqueur, 2);
				if (substr($row->marqueur, 0, 1) == '\\') $row->marqueur = substr($row->marqueur, 1);
				if (substr($row->marqueur, 0, 1) == '/') $row->marqueur = substr($row->marqueur, 1);
				if (substr($row->marqueur, 0, 4) != 'www.' and substr($row->marqueur, 0, 4) != 'http') $row->marqueur = JURI::base(false).$row->marqueur;
				$loadmarqueur .=" markerImage".$num."[".$cnt."].src = \"".$row->marqueur."\";\n ";
			} else
				$loadmarqueur .=" markerImage".$num."[".$cnt."].src = \"http://www.google.com/mapfiles/marker.png\";\n ";
			$cnt++;
		}	
		
		$CustomHeadTag1=( '
		<script type="text/javascript"> 
			var markerImage'.$num.' = new Array();
			'.$loadmarqueur.'
		</script>
		');

		$doc->addCustomTag($CustomHeadTag1);
		
		$this->define_gmapfp();

		include 'components/com_gmapfp/libraries/map_v3.php';
		return $carte;
	}

    function getlistville()
    {
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 
        $db			= JFactory::getDBO();
		
		$filtredepartement = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 'string' );
		$filtrepays = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrepays', 'filtrepays', '-- '.JText::_( 'GMAPFP_PAYS_FILTRE' ).' --', 'string' );
		$filtrecategorie = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrecategorie', 'filtrecategorie', '-- '.JText::_( 'GMAPFP_CATEGORIE_FILTRE' ).' --', 'string' );
		if ($filtredepartement{0}<>'-') {
			$wheres[] = 'departement = '.$db->Quote($this->_getDepartementByID($filtredepartement));
		}
		if ($filtrepays{0}<>'-') {
			$wheres[] = 'pay = '.$db->Quote($this->_getPaysByID($filtrepays));
		}	
		if ($filtrecategorie{0}<>'-') {
			$wheres[] = 'b.id = '.$filtrecategorie;
		}
		
        $params = clone($mainframe->getParams('com_gmapfp'));
		$user_where = $params->get('gmapfp_filtre_sql');
		if (!empty($user_where))
			$wheres[] = @$params->get('gmapfp_filtre_sql');

        $wheres[] = 'a.published = 1';
        $wheres[] = 'a.catid = b.id';

		if ($this->_catid) {
        	$_catids = $this->enfants_catid($this->_catid);
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $query = 'SELECT a.ville, a.id' .
                ' FROM #__gmapfp AS a, #__categories AS b' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY a.ville';
        $results = $this->_getList( $query );
		$tries=array();
		foreach ($results as $result) {
			$trie=array();
			$trie['id'] = $result->id;
			$trie['ville'] = $result->ville;
			$tries[]=$trie;
		};
        return $tries;
    }

    function getlistdepartement()
    {
		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 
        $db			= JFactory::getDBO();
		
		$filtreville = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 'string' );
		$filtrepays = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrepays', 'filtrepays', '-- '.JText::_( 'GMAPFP_PAYS_FILTRE' ).' --', 'string' );
		$filtrecategorie = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrecategorie', 'filtrecategorie', '-- '.JText::_( 'GMAPFP_CATEGORIE_FILTRE' ).' --', 'string' );
		if ($filtreville{0}<>'-') {
			$wheres[] = 'ville = '.$db->Quote($this->_getVilleByID($filtreville));
		}
		if ($filtrepays{0}<>'-') {
			$wheres[] = 'pay = '.$db->Quote($this->_getPaysByID($filtrepays));
		}	
		if ($filtrecategorie{0}<>'-') {
			$wheres[] = 'b.id = '.$filtrecategorie;
		}

        $params = clone($mainframe->getParams('com_gmapfp'));
		$user_where = $params->get('gmapfp_filtre_sql');
		if (!empty($user_where))
			$wheres[] = @$params->get('gmapfp_filtre_sql');

        $wheres[] = 'a.published = 1';
        $wheres[] = 'a.catid = b.id';

		if ($this->_catid) {
        	$_catids = $this->enfants_catid($this->_catid);
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $query = 'SELECT a.departement, a.id' .
                ' FROM #__gmapfp AS a, #__categories AS b' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY a.departement';
        $results = $this->_getList( $query );
		$tries=array();
		foreach ($results as $result) {
			$trie=array();
			$trie['id'] = $result->id;
			$trie['departement'] = $result->departement;
			$tries[]=$trie;
		};
        return $tries;
    }

    function getlistpays()
    {
 		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 
        $db			= JFactory::getDBO();
		
		$filtreville = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 'string' );
		$filtredepartement = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 'string' );
		$filtrecategorie = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrecategorie', 'filtrecategorie', '-- '.JText::_( 'GMAPFP_CATEGORIE_FILTRE' ).' --', 'string' );
		if ($filtreville{0}<>'-') {
			$wheres[] = 'ville = '.$db->Quote($this->_getVilleByID($filtreville));
		}
		if ($filtredepartement{0}<>'-') {
			$wheres[] = 'departement = '.$db->Quote($this->_getDepartementByID($filtredepartement));
		}
		if ($filtrecategorie{0}<>'-') {
			$wheres[] = 'b.id = '.$filtrecategorie;
		}

        $params = clone($mainframe->getParams('com_gmapfp'));
		$user_where = $params->get('gmapfp_filtre_sql');
		if (!empty($user_where))
			$wheres[] = @$params->get('gmapfp_filtre_sql');

        $wheres[] = 'a.published = 1';
        $wheres[] = 'a.catid = b.id';

		if ($this->_catid) {
        	$_catids = $this->enfants_catid($this->_catid);
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

        $query = 'SELECT a.pay, a.id' .
                ' FROM #__gmapfp AS a, #__categories AS b' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY a.pay';
        $results = $this->_getList( $query );
		$tries=array();
		foreach ($results as $result) {
			$trie=array();
			$trie['id'] = $result->id;
			$trie['pay'] = $result->pay;
			$tries[]=$trie;
		};
        return $tries;
    }

    function getlistcategorie()
    {
 		$mainframe = JFactory::getApplication(); 
		$option    = JRequest::getCMD('option'); 
		$Itemid    = JRequest::getInt('Itemid'); 
        $db			= JFactory::getDBO();
		
		$filtreville = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtreville', 'filtreville', '-- '.JText::_( 'GMAPFP_VILLE_FILTRE' ).' --', 'string' );
		$filtredepartement = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtredepartement', 'filtredepartement', '-- '.JText::_( 'GMAPFP_DEPARTEMENT_FILTRE' ).' --', 'string' );
		$filtrepays = $mainframe->getUserStateFromRequest( $option.$Itemid.'filtrepays', 'filtrepays', '-- '.JText::_( 'GMAPFP_PAYS_FILTRE' ).' --', 'string' );
		if ($filtreville{0}<>'-') {
			$wheres[] = 'ville = '.$db->Quote($this->_getVilleByID($filtreville));
		}
		if ($filtredepartement{0}<>'-') {
			$wheres[] = 'departement = '.$db->Quote($this->_getDepartementByID($filtredepartement));
		}
		if ($filtrepays{0}<>'-') {
			$wheres[] = 'pay = '.$db->Quote($this->_getPaysByID($filtrepays));
		}	

        $params = clone($mainframe->getParams('com_gmapfp'));
		$user_where = $params->get('gmapfp_filtre_sql');
		if (!empty($user_where))
			$wheres[] = @$params->get('gmapfp_filtre_sql');

        $wheres[] = 'a.published = 1';
        $wheres[] = 'a.catid = b.id';

		if ($this->_catid) {
        	$_catids = $this->enfants_catid($this->_catid);
            foreach ($_catids as $_catid)
            {
                $wheresOr[] = 'a.catid = '.$_catid.'';
            }
            $wheres[] = '('.implode( " OR ", $wheresOr).')';
        };

		$user = JFactory::getUser();
		$aid = $user->get('aid', 0);
		//$wheres[] = 'b.access <= '.(int) $aid;

        $query = 'SELECT DISTINCT b.id, b.title' .
                ' FROM #__gmapfp AS a, #__categories AS b' .
                ' WHERE ' . implode( '  AND ', $wheres ).
                ' ORDER BY b.title';
        return $this->_getList( $query );
    }

    function _getGoogleAPIKey()
    {
		$GoogleAPIKey = "";
		$config = JComponentHelper::getParams('com_gmapfp');
		if ($config->get('gmapfp_key')) {
			$GoogleAPIKey = $config->get('gmapfp_key');
		}else{
			$homepages = explode(";",$config->get('gmapfp_multi_url'));
			$keys = explode(";",$config->get('gmapfp_multi_key'));
            if ($config->get('gmapfp_URI_type', 0)) {
                $mypage = str_replace("www.","",JURI::base());
            }else{
                $mypage = str_replace("www.","",$_SERVER["SERVER_NAME"]);
            }
			$mypage = str_replace("http://","",$mypage);
			$mypage = str_replace("http:\\","",$mypage);
			if (strpos($mypage, "/")!== false) { $mypage = substr($mypage, 0, strpos($mypage, "/")); };
			if (strpos($mypage, "\\")!== false) { $mypage = substr($mypage, 0, strpos($mypage, "\\")); };

/*            $mainframe->addCustomHeadTag( '<meta name="domaine_JURI::base"              content="'.JURI::base().'" />');
            $mainframe->addCustomHeadTag( '<meta name="domaine_JURI::current"           content="'.JURI::current().'" />');
            $mainframe->addCustomHeadTag( '<meta name="domaine_$_SERVER["REQUEST_URI"]" content="'.$_SERVER["REQUEST_URI"].'" />');
            $mainframe->addCustomHeadTag( '<meta name="domaine_$_SERVER["SERVER_NAME"]" content="'.$_SERVER["SERVER_NAME"].'" />');
*/
			$i = 0;
			foreach ($homepages as $homepage){ 
				$uri = trim($homepage);
				$u = JURI::getInstance( $uri );
				$page = str_replace("www.","",$u->getHost());
				$page = str_replace("http://","",$page);
				$page = str_replace("http:\\","",$page);
				if (strpos($page, "/")!== false) { $page = substr($page, 0, strpos($page, "/")); };
				if (strpos($page, "\\")!== false) { $page = substr($page, 0, strpos($page, "\\")); };
				
				if ($page==$mypage) {
					$GoogleAPIKey = $keys[$i];
					break;
				}
				$i++;
			}
		}
		return $GoogleAPIKey;
	}

//recherche de la ville correspondant à l'id (pour filtre suite Joom!Fish)
	function _getVilleByID($id)
	{
   	$db = JFactory::getDBO();
        $query = 'SELECT a.ville' .
                ' FROM #__gmapfp AS a' .
                ' WHERE id=' . $id;
    	$db->setQuery( $query );
		$result = $db->loadObject();
		return $result->ville;
	}

//recherche de la ville correspondant à l'id (pour filtre suite Joom!Fish)
	function _getDepartementByID($id)
	{
    	$db = JFactory::getDBO();
        $query = 'SELECT a.departement' .
                ' FROM #__gmapfp AS a' .
                ' WHERE id=' . $id;
    	$db->setQuery( $query );
		$result = $db->loadObject();
		return $result->departement;
	}

//recherche de la ville correspondant à l'id (pour filtre suite Joom!Fish)
	function _getPaysByID($id)
	{
    	$db = JFactory::getDBO();
        $query = 'SELECT a.pay' .
                ' FROM #__gmapfp AS a' .
                ' WHERE id=' . $id;
    	$db->setQuery( $query );
		$result = $db->loadObject();
		return $result->pay;
	}

}
?>
