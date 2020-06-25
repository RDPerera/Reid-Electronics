<?php
	/*
	* GMapFP Component Google Map for Joomla! 3.x
	* Version J3_52F
	* Creation date: Janvier 2018
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	* 782
	*/

defined('_JEXEC') or die;

	if (!defined('COM_GMAPFP_IMAGES_HTTP_RELATIVE')) define('COM_GMAPFP_IMAGES_HTTP_RELATIVE', '/images/gmapfp');

	$https = "";
	if ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) $https = "s";	

    $mainframe 	= JFactory::getApplication();
    $language 	= JFactory::getLanguage();
    $language->load('com_gmapfp');
    //$config 	=& JComponentHelper::getParams('com_gmapfp');//parametre du composant
    $config 	= $mainframe->getParams('com_gmapfp');//parametre de l'item
    $perso 		= $this->getPersonnalisation();
	$doc		= JFactory::getDocument();
	
    $itineraire = $mainframe->input->get('itineraire', 0, 'int');
    $affichage  = $mainframe->input->get('affichage', '0', 'string');
    $itemid 	= $mainframe->input->get('Itemid', 	0, 'int');
    $_layout 	= $mainframe->input->get('layout', '', 'string');
    $flag       = $mainframe->input->get('flag',($_layout=="article"), 'int');

	$ControlOption 	= '';
	$event_map 		= '';
	$standard_function	= '';
    $carte			= '';
	$MapVariables	= '';
	
    if (empty($num)) { $num = ''; };
	
/*********************************************
* traitement des données de zoom et centrage *
*********************************************/
    $zoom = "";
	$centrage_auto = $config->get('gmapfp_auto', 1);
    if (($_layout == "item_carte") or ($flag)) {
        if ((empty($this->_id))or($config->get('gmapfp_zoom_lightbox_carte', 100)!=100)) {
            $zoom = $config->get('gmapfp_zoom_lightbox_carte');
			$centrage_auto = $config->get('gmapfp_auto_other', 1);
        }else{
            $zoom = $rows[0]->gzoom;
        };
    };
    if ($_layout == "print_article") {
        if ((empty($this->_id)) or ($config->get('gmapfp_zoom_lightbox_imprimer', 100)!=100)) {
            $zoom = $config->get('gmapfp_zoom_lightbox_imprimer');
			$centrage_auto = $config->get('gmapfp_auto_other', 1);
        }else{
            $zoom = $rows[0]->gzoom;
        };
    };
    if (($zoom == "") or ($zoom == 0)) {
        $zoom = $config->get('gmapfp_zoom', 10);
    };
    if (!($Zmap)) {
        $Zmap = $zoom;
    };
	
	//centrage et zoom manuel par défaut
	$centrageauto='';
	$Zauto='';
    if ($plugin) {
        //centrage de la carte et choix du zoom de départ avec plugin
        if (!(($glat_plugin)and($glng_plugin))) {
            if ($Zmap) {
            //centreage auto et zoom manuel
				$centrageauto	= 1;
            }else{
             //centrage et zoom automatique
				$centrageauto	= 1;
				$Zauto			= 1;
            }
        };
    }else{
		//centrage de la carte et choix du zoom de départ sans plugin
        if ((($centrage_auto==1) or ($Zmap==0))) {
             if ($Zmap) {
            //centrage auto et zoom manuel
				$centrageauto	= 1;
            }else{
            //centrage et zoom automatique
				$centrageauto	= 1;
				$Zauto			= 1;
			};
        };
    };
	if ($centrageauto == 1) {
		$centrageauto='carteGMapFP'.$num.'.setCenter(bounds_GMapFP'.$num.'.getCenter());'."\n";
	}
	if ($Zauto == 1) {
		$Zauto='carteGMapFP'.$num.'.fitBounds(bounds_GMapFP'.$num.');'."\n";
		//limitation de la valeur du zoom automatique
		$event_map.='	google.maps.event.addListenerOnce(carteGMapFP'.$num.', \'zoom_changed\', function(){UpdateZoomMax();})'."\n";
		$standard_function .='function UpdateZoomMax(){'."\n";
		$standard_function .='	var mapZoom = carteGMapFP'.$num.'.getZoom();'."\n";
		$standard_function .='	if (mapZoom > '.$config->get('gmapfp_auto_zoom_maxi',18).')'."\n";
		$standard_function .='	{carteGMapFP'.$num.'.setZoom('.$config->get('gmapfp_auto_zoom_maxi',18).');}'."\n";
		$standard_function .='}'."\n";		
	}
/*************************************************
* fin traitement des données de zoom et centrage *
*************************************************/

/**************************/
/* Création des marqueurs */
/**************************/
    //creation de l'infowindow

	// Sélection de la cible
	switch ($config->get('target'))
	{
		case 1:
			// open in parent avec navigation
			$cible = 'parent.location=place[5]';
			break;
		case 2:
			// open in nouvelle fenêtre avec barre de navigation
			$cible = "window.open(place[5], \"info+place[3]\", 'toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=".$largeur_lightbox.",height=".$hauteur_lightbox."'); ";
			break;
		case 3:
			// open in a popup window
			$cible = "window.open(place[5], \"info+place[3]\", 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=".$largeur_lightbox.",height=".$hauteur_lightbox."');";
			break;
		default:
			// open in lightbox
			$cible ='SqueezeBox.setOptions({size: {x: gmapfp_largeur_lightbox, y: gmapfp_hauteur_lightbox}});SqueezeBox.setContent(\'iframe\',place[5]);';
			break;
	}

	$check_eventcontrol = 10 * (int) $click_over + (int) $config->get('gmapfp_eventcontrol');
	$ajout_nom = false;
	// choix avec / sans possibilité de clik : More information
	if (($config->get('gmapfp_plus_detail')==0)||($flag==1)) {
		$MoreInfoTexte = '';
		$plus_detail = '';
	} else {
		$MoreInfoTexte = '
			google.maps.event.addListener(marker, \'mousedown\', function(e) {
				'.$cible.'
			});
		';
		$plus_detail = '<h4>'.JTEXT::_("GMAPFP_CLIQUER_POUR_ARTICLE").'</h4>';
	}
	
    $MapVariables .= '
	var infowindow'.$num.' = new Array();
	var num_open = 0;
	';
	
	switch ($check_eventcontrol) {
		case 2 :
			//MouseOver + Fermeture manuel + Click
			$create_infowindow = '
			function attachinfowindow(marker, place, i){
				'.$MoreInfoTexte.'
				infowindow'.$num.'[i] = new google.maps.InfoWindow({
					content: place[4],
					maxWidth : '.(int)$config->get('gmapfp_width_bulle_GMapFP', 400).',
					disableAutoPan : '.$config->get('gmapfp_AutoPan', 0).'
				});
				google.maps.event.addListener(marker, \'mouseover\', function(e) {
					infowindow'.$num.'[num_open].close(carteGMapFP'.$num.',marker);
					infowindow'.$num.'[i].setZIndex(++infowindowLevel);
					infowindow'.$num.'[i].open(carteGMapFP'.$num.',marker);
					num_open = i;
				});
			};';
			break;
		case 01 :
		case 10 :
		case 11 :
		case 12 :
		   //MouseOver + Fermeture auto + Click
			$create_infowindow = '
			function attachinfowindow(marker, place, i){
				'.$MoreInfoTexte.'
				infowindow'.$num.'[i] = new google.maps.InfoWindow({
					content: place[4],
					maxWidth : '.(int)$config->get('gmapfp_width_bulle_GMapFP', 400).',
					disableAutoPan : '.$config->get('gmapfp_AutoPan', 0).'
				});
				google.maps.event.addListener(marker, \'mouseover\', function(e) {
					infowindow'.$num.'[i].setZIndex(++infowindowLevel);
					infowindow'.$num.'[i].open(carteGMapFP'.$num.',marker);
				});
				google.maps.event.addListener(marker, \'mouseout\', function(e) {
					infowindow'.$num.'[i].setZIndex(++infowindowLevel);
					infowindow'.$num.'[i].close(carteGMapFP'.$num.',marker);
				});
			}';
			break;
		default : //case 0
			//Seulement Click
			if (($config->get('gmapfp_plus_detail')==0)||($flag==1)) {
				$MoreInfoTexte = '
					infowindow'.$num.'[i] = new google.maps.InfoWindow({
						content: place[4],
						maxWidth : '.(int)$config->get('gmapfp_width_bulle_GMapFP', 400).',
						disableAutoPan : '.$config->get('gmapfp_AutoPan', 0).'
					});
					
					google.maps.event.addListener(marker, \'mousedown\', function(e) {
						infowindow'.$num.'[marker_precedent'.$num.'].close();
						infowindow'.$num.'[i].setZIndex(++infowindowLevel);
						infowindow'.$num.'[i].open(carteGMapFP'.$num.',marker);
						marker_precedent'.$num.' = i;
					});
				';
			}
			
			$create_infowindow='
			function attachinfowindow(marker, place, i){
					'.$MoreInfoTexte.'
				}';
	}
	unset ($cible);
	unset ($MoreInfoTexte);

	$map_without_clustering = 'map: carteGMapFP'.$num.',';

    $create_marker = 'create_carteGMapFP'.$num.'.prototype.addMarker = function     (donnees){
		'
        .$create_infowindow
        .'
    for (var i = 0; i < donnees.length; i++) {
		var place = donnees[i];
        infos_marqueur = place[9];
		image_marker = new google.maps.MarkerImage(
			infos_marqueur[0],
            new google.maps.Size(markerImage'.$num.'[infos_marqueur[1]].width, markerImage'.$num.'[infos_marqueur[1]].height),
            new google.maps.Point(0,0),
            new google.maps.Point(markerImage'.$num.'[infos_marqueur[1]].width/2, markerImage'.$num.'[infos_marqueur[1]].height)
		);
	    var shadow_marker = new google.maps.MarkerImage("http'.$https.'://www.google.com/mapfiles/shadow50.png",
			new google.maps.Size(34, 37),
			new google.maps.Point(0,0),
			new google.maps.Point(10, 34));
		var shape_marker = {
			coord: [0, 0, markerImage'.$num.'[infos_marqueur[1]].width, markerImage'.$num.'[infos_marqueur[1]].height],
			type: \'rect\'
		};

        var maLatLng = new google.maps.LatLng(place[1], place[2]);
		bounds_GMapFP'.$num.'.extend(maLatLng);
        marker'.$num.'[i] = new google.maps.Marker({
            '.$map_without_clustering.'
            position: maLatLng,
            title: place[7],
            icon: image_marker,
			shadow: shadow_marker,
			shape: shape_marker,
            zIndex: place[3]
        });
		marker'.$num.'[i].mycategory  = place[8];
        attachinfowindow(marker'.$num.'[i], place, i);
    };
    '
    .$centrageauto
    .$Zauto
    .'};'."\n\n";

    $VariableDirection='';
    $DeclareDirection='';
    if ((($config->get('gmapfp_itineraire') == 1) and ($Itin == 0)) or ($Itin == 1) or $itineraire) {
        $VariableDirection = 'var directionDisplay'.$num.';
            var directionsService'.$num.' = new google.maps.DirectionsService();';
        $Option_Direction = 'draggable: true';
		$DeclareDirection = '
			var renderOptions = {
			'.$Option_Direction.'
			};
            directionsDisplay'.$num.' = new google.maps.DirectionsRenderer(renderOptions);
            directionsDisplay'.$num.'.setMap(carteGMapFP'.$num.');
            directionsDisplay'.$num.'.setPanel(document.getElementById("gmapfp_directions'.$num.'"));';
    }
    $MapVariables .=
            'var places'.$num.'  = [];
            var infowindowLevel = 0;
			var bounds_GMapFP'.$num.' = new google.maps.LatLngBounds();
			var marker'.$num.' = new Array();
           '.$VariableDirection
			.'
    ';

//affichage de google earth 45° quand disponible    
	$control ="";
    if ($config->get('gmapfp_Tilt45', 1)){
		$control .= '
		  carteGMapFP'.$num.'.setTilt(45);';
	}

//insertion du traffic
    $traffic='';
    if ($config->get('gmapfp_enable_traffic', 0) or (($config->get('gmapfp_plus_info', 1) and ($More == 0)) or ($More == 1))) {
        $traffic= '
            trafficLayer'.$num.' = new google.maps.TrafficLayer();';
		if ($config->get('gmapfp_enable_traffic', 0)) {
			$traffic.= '
			trafficLayer'.$num.'.setMap(carteGMapFP'.$num.');
			trafficLayer'.$num.'.enable = true;';
			$enable_traffic = 1;
		} else {
			$traffic.= '
			trafficLayer'.$num.'.enable = false;';
			$enable_traffic = 0;
		}
	};

//affichage de plus de détail : Panoramino, trafic, ...
    if (($config->get('gmapfp_plus_info', 1) and ($More == 0)) or ($More == 1)){
		$MapVariables .='
		var moreControlText = [ "'.JText::_( 'GMAPFP_MORE' ).'", "'.JText::_( 'GMAPFP_CLICK_TO_OPEN' ).'", "'.JText::_( 'GMAPFP_CLICK_TO_SET' ).'", "'.JText::_( 'GMAPFP_PANORAMINO' ).'", "'.JText::_( 'GMAPFP_TRAFFIC' ).'"];';
		$control .= '
		  var moreControlDiv = document.createElement(\'DIV\');
		  var moreControl = new MoreControl("carteGMapFP'.$num.'", moreControlDiv, "", "", "trafficLayer'.$num.'", '.$enable_traffic.');
		  moreControlDiv.index = 1;
		  carteGMapFP'.$num.'.controls[google.maps.ControlPosition.TOP_RIGHT].push(moreControlDiv);
		';
	}
	
//choix des options d'affichage de la carte
//bar de zoom 3D (ANDROID,SMALL,ZOOM_PAN)
    if ((($config->get('gmapfp_mapcontrol')==1) and ($bar_z_nav == 0)) or ($bar_z_nav == 1) )
        {$ControlOption.='zoomControl: true, panControl: true,
                  navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},';}
    else
        {$ControlOption.='zoomControl: false, panControl: false,';};
//Affichage de l'échelle
    if ((($config->get('gmapfp_scalecontrol')==1) and ($Ech == 0)) or ($Ech == 1) )
        {$ControlOption.='
              scaleControl: true,';}
    else
        {$ControlOption.='
              scaleControl: false,';};
//activation du zoom par molette de la sourie
    if ((($config->get('gmapfp_mousewheel')==1) and ($MZoom == 0)) or ($MZoom == 1) )
        {$ControlOption.='
              scrollwheel: true,';}
    else
        {$ControlOption.='
              scrollwheel: false,';};
//activation de la zone de zoom en bas à droite
    if ((($config->get('gmapfp_zzoom')==1) and ($ZZoom == 0)) or ($ZZoom == 1) )
        {$ControlOption.='
              overviewMapControl: true,';}
    else
        {$ControlOption.='
              overviewMapControl: false,';};


//affichage streetView
    $HeightPano=(int)$config->get('gmapfp_height_sv', 300);
    $streeview='';
    $ControlOption.='
              streetViewControl: false,';

//insertion du fichier kml
    $xml_files = array();
	if ($config->get('gmapfp_geoXML') != "") $xml_files = explode(";",$config->get('gmapfp_geoXML'));
	if ($kml_file != '0')
		$xml_files[] = $kml_file;
    $geo_xml = '';
	$var_xml = '';
	$cn = 1;
    foreach ($xml_files as $xml_file) {
        $geo_xml .= '
			var optionkml = {
				preserveViewport: true
			}
            ctaLayer'.$cn.' = new google.maps.KmlLayer(\''.$xml_file.'\', optionkml);
                ctaLayer'.$cn.'.setMap(carteGMapFP'.$num.');';
        $var_xml .= 'var ctaLayer'.$cn.';'."\n";
		$cn++;
		};

//auto complete les champs de saisie destination/provenance
	$auto_complete= '';
				
$mapTypeId=array();
//affichage bouton carte hybride
    if (($config->get('gmapfp_hybrid') and ($map_hyb == 0)) or ($map_hyb == 1))
        { $mapTypeId[]='google.maps.MapTypeId.HYBRID';}
//affichage bouton carte normale
    if (($config->get('gmapfp_normal') and ($map_nor == 0)) or ($map_nor == 1))
        { $mapTypeId[]='google.maps.MapTypeId.ROADMAP';}
//affichage bouton carte relief
    if (($config->get('gmapfp_physic') and ($map_phy == 0)) or ($map_phy == 1))
        { $mapTypeId[]='google.maps.MapTypeId.TERRAIN';}
//affichage bouton carte satellite
    if (($config->get('gmapfp_satellite') and ($map_sat == 0)) or ($map_sat == 1))
        { $mapTypeId[]='google.maps.MapTypeId.SATELLITE';}
    $mapTypeIds=implode( ",", $mapTypeId );
    $mapTypeIds='var types = ['.$mapTypeIds.'];';
//selection du type d'affichage des types de carte
    $MapTypeControlStyle='';
    if ($config->get('gmapfp_vertical'))
        { $MapTypeControlStyle='style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,';}

	$points = "places".$num." = [\n";
    $cesure = $config->get('gmapfp_taille_bulle_cesure');
    $cnt2 = 0;
	$premier = true;
    foreach($rows as $row){
        $cnt2++;
        if (($row->glat<>"")&&($row->glng<>"")) {
            if (!$premier) {$points.=',';
			} else $premier = false;
            $points.='[\''.''.'\'';

            $image ='';
            if (@$row->img) {
                $image = JURI::base(true).COM_GMAPFP_IMAGES_HTTP_RELATIVE.'/'.$row->img;
                $image = JPath::clean($image, '/');
                $image = "<img style='height: 80px;' src='".$image."'>";
            }
            $points.= ','.$row->glat.','.$row->glng.',';
            if (@$row->intro) {
                $intro=trim($row->intro);
                $intro = str_replace(chr(10), '',$intro);
                $intro = str_replace(chr(13), '<BR />',$intro);
            }else{$intro='';};
            if (@$row->message) {
                $message=trim($row->message);
                $message = str_replace(chr(10), '',$message);
                $message = str_replace(chr(13), '<BR />',$message);
            }else{$message='';};

            if ($config->get('gmapfp_html_bubble')){
                $text = addslashes($intro.$message);
            }else{
                $text = strip_tags(addslashes($intro.$message),"<BR>");
            }

            $nom  = addslashes(trim($row->nom));

            $link4=substr(trim($row->link),0,4);
            $link5=substr(trim($row->link),0,5);
            $link9=substr(trim($row->link),0,9);
            $linkmap=trim($row->link);

            if ($link4=="www.") {$linkmap="http://".$linkmap;};

            if ((empty($row->link))||($row->link='')) {
                if(!class_exists('GMapFPHelperRoute')) require_once (JPATH_SITE . '/components/com_gmapfp/helpers/route.php');  
                if ($config->get('target')==0) {
                    // $map_link=JRoute::_('index.php?option=com_gmapfp&view=gmapfp&Itemid='.$itemid.'&layout=article&tmpl=component&id='.$row->id, false);
                    $map_link=JRoute::_(GMapFPHelperRoute::getArticleRoute($row->slug).'&tmpl=component', false);
                }else{
                    // $map_link=JRoute::_('index.php?option=com_gmapfp&view=gmapfp&Itemid='.$itemid.'&layout=article&id='.$row->slug, false);
                    $map_link=JRoute::_(GMapFPHelperRoute::getArticleRoute($row->slug), false);
                };
                $choix=1;
            } else {
                if (($link5=="http:")||($link4=="www.")||($link9=="index.php")) {
                    $map_link=$linkmap;
                    if (($link5=="http:")||($link4=="www.")) {$choix=0;}else{$choix=1;};
                } else {
                    if(!class_exists('ContentHelperRoute')) require_once (JPATH_SITE . '/components/com_content/helpers/route.php');  
                    if ($config->get('target')==0) {
                        $map_link=JRoute::_(ContentHelperRoute::getArticleRoute($row->article_slug, $row->article_id.':'.$row->article_alias, 0));
                        $map_link .= '?&tmpl=component';
                    }else{
                         $map_link=JRoute::_(ContentHelperRoute::getArticleRoute($row->article_slug, $row->article_id.':'.$row->article_alias, 0));
                    };
                    $choix=1;
                };
            };
            if (@$row->icon) {
                if($config->get('target')==0) {
                    $map_link=JRoute::_('index.php?option=com_gmapfp&view=gmapfp&Itemid='.$itemid.'&layout=article&tmpl=component&id='.$row->id, false);
                }else{
                    $map_link=JRoute::_('index.php?option=com_gmapfp&view=gmapfp&Itemid='.$itemid.'&layout=article&id='.$row->slug, false);
                };
            };

            if (empty($row->m_url)) $row->m_url = 'http'.$https.'://www.google.com/mapfiles/marker.png';


			if ($flag and ($config->get('gmapfp_auto_ouvrir_titre', 1) > 0)) {
				if ($config->get('gmapfp_auto_ouvrir_titre', 1) == 2) {
					$bubble = include 'components/com_gmapfp/bubble/bubbleadresse.php';
				} else {
					$bubble = include 'components/com_gmapfp/bubble/bubblenom.php';
				}
			} else {
	            switch ($config->get('affichage')) {
					case 0: //Complet
						$bubble = include 'components/com_gmapfp/bubble/bubble0.php';
						break;
					case 1: //Nom, photo, adresse
						$bubble = include 'components/com_gmapfp/bubble/bubble1.php';
						break;
					case 2: //Nom, photo, message
						$bubble = include 'components/com_gmapfp/bubble/bubble2.php';
						break;
					default: //Nom
						$bubble = include 'components/com_gmapfp/bubble/bubbledefault.php';
				}
			};
			$points.= $bubble;
			$points.= $map_link."\",".$choix;
            $points.=", \"".$nom."\"";
			
            //$points.=",\"".$row->catalias."\"";
            $points.=",\"\"";

			//dimensionnel marqueur et ombre
			if ($row->marqueur)
				$points.=',["'.$row->marqueur.'",'.$cnt2.']';
			else
				$points.=',["http'.$https.'://www.google.com/mapfiles/marker.png",'.$cnt2.']';

            $points.="]\n ";
        }
    }
    $points.='];';

	$carte_choix = 'ROADMAP';
    if ((($config->get('gmapfp_choix_affichage_carte')==2) and ($map_choix == 0)) or ($map_choix == 2)) { $carte_choix = 'SATELLITE';};
    if ((($config->get('gmapfp_choix_affichage_carte')==3) and ($map_choix == 0)) or ($map_choix == 3)) { $carte_choix = 'HYBRID';};
    if ((($config->get('gmapfp_choix_affichage_carte')==4) and ($map_choix == 0)) or ($map_choix == 4)) { $carte_choix = 'TERRAIN';};

	if ($plugin) {
		if ($glat_plugin) {
			$lat=$glat_plugin;
		}else{
			$lat='0';};
		if ($glng_plugin) {
			$lng=$glng_plugin;
		}else{
			$lng='0';};
	} else {
			if ($config->get('gmapfp_centre_lat')) {
			$lat=$config->get('gmapfp_centre_lat');
		}else{
			$lat='0';};
		if ($config->get('gmapfp_centre_lng')) {
			$lng=$config->get('gmapfp_centre_lng');
		}else{
			$lng='0';};
	}

//Ajout du logo GMapFP en bas à gauche de la carte
	if ($config->get('gmapfp_logo', 1)) {
		$active_logo = 'setTimeout("Hide_Google_link'.$num.'()",1000);';
		$logo_img = 'data:image/png;base64,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';
		$code_logo = '		function Hide_Google_link'.$num.'() {
				google_link = jQuery("#map_canvas'.$num.' div:has(a img[src*=\'api-3/images/google\'])");
				gmapfp_link =  "<a style=\"position: static;overflow: visible;float: none;display: inline;\" title=\"GMapFP: Map Component for Joomla\" target=\"_blank\" href=\"http'.$https.'://gmapfp.org\"><div class=\"gmnoprint\" style=\"width: 60px; height: 24px; cursor: pointer; \"><img style=\"position: absolute; left: 0px; top: 24px; -webkit-user-select: none; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-style: initial; border-color: initial; border-image: initial; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; width: 60px; height: 24px; \" src=\"'.$logo_img.'\"></div></a>";
				google_link.prepend(gmapfp_link);
		};';
	} else {
		$active_logo = '';
		$code_logo = '';
	}

//autoouvrir le marqueur si 1lieu, carte, imprimer, ...
	$auto_ouvrir = '';
	if ($flag and $config->get('gmapfp_auto_ouvrir_titre', 1)) {
		$auto_ouvrir = "\n".'infowindow'.$num.'[1]=new google.maps.InfoWindow({
					content: places[0][4],
					maxWidth : '.(int)$config->get('gmapfp_width_bulle_GMapFP', 400).',
					disableAutoPan : '.$config->get('gmapfp_AutoPan', 0).'
				});';
		$auto_ouvrir .= "\n".'infowindow[1].open(carteGMapFP,marker[0]);';
	};
	
    $doc->addCustomTag( '<script type="text/javascript">
        '.
        $MapVariables.
		$var_xml.
        '
        var carteGMapFP'.$num.';
        var panoramioLayer'.$num.';
        var trafficLayer'.$num.';
		var travel_mode'.$num.' = google.maps.DirectionsTravelMode.DRIVING;
		var marker_precedent'.$num.' = 0;
		var markerCluster'.$num.' = null;

        function create_carteGMapFP'.$num.'() {
            '.$mapTypeIds.'
			var mycentre = new google.maps.LatLng('.$lat.', '.$lng.');
            var myOptions = {
              zoom: '.$Zmap.',
              center: mycentre,
              '.$ControlOption.'
              mapTypeControlOptions: {
                '.$MapTypeControlStyle.'
                mapTypeIds: types
              },
              mapTypeId: google.maps.MapTypeId.'.$carte_choix.'
            };
            carteGMapFP'.$num.' = new google.maps.Map(document.getElementById("map_canvas'.$num.'"),myOptions);'."\n"
			//.'var mc = new MarkerClusterer(carteGMapFP'.$num.');'
            .$geo_xml
            ."\n\n"
			.$event_map
			.$traffic
			.$auto_complete
            .$streeview
            .$DeclareDirection
            .$control
			."\n};\n\n"
        .$standard_function
		.$create_marker
        //.$points."\n"
		.'	function initialise_gmapfp'.$num.'() {
				var maCarteGMapFP'.$num.'= new create_carteGMapFP'.$num.';
				maCarteGMapFP'.$num.'.addMarker(places'.$num.');'
		.$active_logo
		.$auto_ouvrir
		.'
			};
		'
		.$code_logo
		."\n</script>"."\n"

        .'<script type="text/javascript">'."\n"
        .$points."\n"
		."\n</script>"
    );

    if (empty($Hmap)) {$Hmap=$config->get('gmapfp_height');};
    if (empty($Lmap)) {
        if ((strpos($config->get('gmapfp_width'),'%'))or(strpos($config->get('gmapfp_width'),'x'))){
            $Lmap=$config->get('gmapfp_width');
        }else{
            $Lmap=(int)$config->get('gmapfp_width').'px';
        };
    };
    if ($_layout == "article") {$Lmap='100%';}

    if (!empty($perso)) {
        //fonction pour execution des plugins dans la personnalisation
		$article = new stdclass;
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('content');
        if (!empty($perso->intro_carte)) {
            $article->text=$perso->intro_carte;
			$results = $dispatcher->trigger('onContentPrepare', array ('com_gmapfp', & $article, & $this->params, 0)); 
            $carte.=$article->text;
        };
    };

    $carte.=
    '<div id="gmapfp" style="width:'.$Lmap.'; height:auto; overflow:hidden;">';

    $carte.=
        '<div id="map_canvas'.$num.'" style="width:'.$Lmap.'; height:'.$Hmap.'px; background-image:url(components/com_gmapfp/images/loader_black.gif); background-repeat:no-repeat; background-position:center;"></div>';

	if (!empty($perso->conclusion_carte)) {
		$article->text=$perso->conclusion_carte;
		$results = $dispatcher->trigger('onContentPrepare', array ('com_gmapfp', & $article, & $this->params, 0)); 
		$carte.=$article->text;
	};

	if ((($config->get('gmapfp_itineraire')==1) and ($Itin==0)) or ($Itin==1) or $itineraire) {

		$java_direction = '
			var MessageRoute = [ "OK", "'.JText::_( 'GMAPFP_NOT_FOUND' ).'", "'.JText::_( 'GMAPFP_ZERO_RESULTS' ).'", "'.JText::_( 'GMAPFP_MAX_WAYPOINTS_EXCEEDED' ).'", "'.JText::_( 'GMAPFP_INVALID_REQUEST' ).'", "'.JText::_( 'GMAPFP_OVER_QUERY_LIMIT' ).'", "'.JText::_( 'GMAPFP_REQUEST_DENIED' ).'", "'.JText::_( 'GMAPFP_UNKNOWN_ERROR' ).'"];
			';
		$doc->addCustomTag( '<script language="javascript" type="text/javascript">
			'.$java_direction.'
			</script>');

		//Ajoute mise a jour du lien impression et affichage de l'icon d'impression de l'itinéraire
		$debutlink ='index.php?option=com_gmapfp&view=gmapfp&tmpl=component&layout=print_article&flag=1&id=';
		$finlink ='&Itemid='.$mainframe->input->get('Itemid', '', 'int');
		$printer=JURI::base().'components/com_gmapfp/images/printer.png';
		$print_layout = 'print_';
		if ($_layout != "print_article") {
			$print_layout = '';
			$doc->addCustomTag( '<script language="javascript" type="text/javascript">
				function Show_bp_print(num_map) {
					var ok = false;
					if (document.getElementById("select_from"+num_map).selectedIndex!=0 && (document.getElementById("select_to"+num_map).selectedIndex!=0 || document.getElementById("text_to"+num_map).value!="")) {
						var i = document.getElementById("select_from"+num_map).value.lastIndexOf(",");
						var link_print = "'.JURI::base().'" + "'.$debutlink.'" + document.getElementById("select_from"+num_map).value.substring(i+1) + "&plug_num="+num_map+"'.$finlink.'";
						ok = true;
					} else {
						if (document.getElementById("select_to"+num_map).selectedIndex!=0 && document.getElementById("text_from"+num_map).value!="") {
							var i = document.getElementById("select_to"+num_map).value.lastIndexOf(",");
							var link_print = "'.JURI::base().'" + "'.$debutlink.'" + document.getElementById("select_to"+num_map).value.substring(i+1) + "&plug_num="+num_map+"'.$finlink.'";
							ok = true;
						}
					}
					if (ok) {
						document.getElementById("frame_print_itin"+num_map).src = link_print;
						document.getElementById("bp_print_itin"+num_map).style.display = "block";
					} else {
						document.getElementById("bp_print_itin"+num_map).style.display = "none";
					}
				}
				function OnSubmit_Itin'.$num.'() {
					CalculRoute(\''.$num.'\', directionsDisplay'.$num.', directionsService'.$num.', travel_mode'.$num.', \'\'); 
					Show_bp_print(\''.$num.'\');
				}
			</script>');
		} else {
			$doc->addCustomTag( '<script language="javascript" type="text/javascript">
				function OnSubmit_Itin'.$num.'() {
					CalculRoute(\''.$num.'\', directionsDisplay'.$num.', directionsService'.$num.', travel_mode'.$num.', \''.$print_layout.'\'); 
				}
				</script>');
		}

		$bike_option = 'style = "display:none;"';
		$travel_option = 'style = "display:none;"';
		$carte.='
			<p style="text-align:center;margin:2px 0;">&nbsp;</p>
			<form action="#" onsubmit="OnSubmit_Itin'.$num.'(); return false;" method="post" name="'.$print_layout.'direction_form'.$num.'">
				<div class="gmnoprint">
					<legend >'.JText::_( 'GMAPFP_DIRECTION' ).'</legend>
						<div '.$travel_option.'>
							<span id="map_car_button'.$num.'" class="map-car-button-selected" title="'.JText::_( 'GMAPFP_BY_CAR' ).'" onclick="travel_mode'.$num.' = onchange_travel_mode(\'car\', travel_mode'.$num.', \''.$num.'\')">&nbsp;</span>
							<span id="map_walk_button'.$num.'" class="map-walk-button" title="'.JText::_( 'GMAPFP_WALKING' ).'" onclick="travel_mode'.$num.' = onchange_travel_mode(\'walk\', travel_mode'.$num.', \''.$num.'\')">&nbsp;</span>
							<span '.$bike_option.' id="map_bike_button'.$num.'" class="map-bike-button" title="'.JText::_( 'GMAPFP_BICYCLING' ).'" onclick="travel_mode'.$num.' = onchange_travel_mode(\'bike\', travel_mode'.$num.', \''.$num.'\')">&nbsp;</span>
							<span class="map-options-routes" style="float:left;">
							<a href="javascript:void(0); HideShow_OptionsRoute(\''.$num.'\');">'.JText::_( 'GMAPFP_SHOW_OPTIONS' ).'</a>
							<div class="map-options-routes_choose" id="map-options-routes_choose'.$num.'" style="display:none;">
								<input type="checkbox" id="checkbox_autoroute'.$num.'" ><label for="checkbox_autoroute'.$num.'">'.JText::_( 'GMAPFP_AUTOROUTE' ).'</label></input><br />
								<input type="checkbox" id="checkbox_peage'.$num.'" ><label for="checkbox_peage'.$num.'">'.JText::_( 'GMAPFP_PEAGE' ).'</label></input><br />
								<input type="radio" id="checkbox_unite'.$num.'" name="unite" value="K" checked="checked"/><label for="checkbox_unite'.$num.'">'.JText::_( 'GMAPFP_KM' ).' </label>
								<input type="radio" id="checkbox_unite_miles'.$num.'" name="unite" value="M"/><label for="checkbox_unite_miles'.$num.'">'.JText::_( 'GMAPFP_MILES' ).'</label>
							</div>
						</span>
						</div>
						<div class="clearboth"></div>
					<p>
						'.JText::_( 'GMAPFP_DE' ).' :
						<select name="select_from'.$num.'" id="'.$print_layout.'select_from'.$num.'">
							<option value="">'.JText::_( 'GMAPFP_CHOIX_DE' ).'</option>';
							foreach($rows_orderA as $row) {
								$selected = '';
								if (isset($row->glat) && isset($row->glng) && !empty($row->glat) && !empty($row->glng)) {
									$value = $row->glat.','.$row->glng;
								} else {
									$value = @$row->country.' '.@$row->postcode.' '.@$row->suburb.' '.@$row->address;
								}
								//$carte .= '<option value="'.$value.'" '.$selected.'>'.$row->nom.'</option>';
								$carte .= '<option value="'.$value.','.$row->id.'" '.$selected.'>'.$row->nom.'</option>'."\n";
							}
							$carte.='
						</select>
						'.JText::_( 'GMAPFP_OU' ).'<input type="text" name="text_from'.$num.'" id="'.$print_layout.'text_from'.$num.'" />
					</p>
					'.JText::_( 'GMAPFP_VERS' ).' :
					<select name="select_to'.$num.'" id="'.$print_layout.'select_to'.$num.'">
						<option value="">'.JText::_( 'GMAPFP_CHOIX_VERS' ).'</option>';
						foreach($rows_orderA as $row) {
							$selected = '';
							if (isset($row->glat) && isset($row->glng) && !empty($row->glat) && !empty($row->glng)) {
								$value = $row->glat.','.$row->glng;
							} else {
								$value = @$row->country.' '.@$row->postcode.' '.@$row->suburb.' '.@$row->address;
							}
							$carte .= '<option value="'.$value.','.$row->id.'" '.$selected.'>'.$row->nom.'</option>'."\n";
						}
						
						
						$carte.='
					</select>
					'.JText::_( 'GMAPFP_OU' ).'<input type="text" name="text_to'.$num.'" id="'.$print_layout.'text_to'.$num.'" />
					<input type="submit" class="button" value='.JText::_( 'GMAPFP_GO' ).' />

					<!-- iframe pour préparation de l impression -->
					<iframe src="'.JURI::base().'components/com_gmapfp/index.html" style="visibility: hidden; height: 0; width: 100%;" name="frame_print_itin'.$num.'" id="frame_print_itin'.$num.'"></iframe>
					';
					
					if ($_layout == "print_article") {
						$plugin_num = $mainframe->input->get('plug_num', '', 'string');
					$carte.='
					
					<!-- récupère les infos de la page parent si ils existent -->
					<script language="javascript">
						var select_from_index = parent.direction_form'.$plugin_num.$num.'.getElementById("select_from'.$plugin_num.$num.'").selectedIndex;
						if (select_from_index) var select_from_value = parent.direction_form'.$plugin_num.$num.'.getElementById("select_from'.$plugin_num.$num.'").options[parent.direction_form'.$plugin_num.$num.'.getElementById("select_from'.$plugin_num.$num.'").selectedIndex].value;
						
						var select_to_index   = parent.direction_form'.$plugin_num.$num.'.getElementById("select_to'.$plugin_num.$num.'").selectedIndex;
						if (select_to_index) var select_to_value = parent.direction_form'.$plugin_num.$num.'.getElementById("select_to'.$plugin_num.$num.'").options[parent.direction_form'.$plugin_num.$num.'.getElementById("select_to'.$plugin_num.$num.'").selectedIndex].value;
						
						var text_from_value   = parent.direction_form'.$plugin_num.$num.'.getElementById("text_from'.$plugin_num.$num.'").value;
						var text_to_value     = parent.direction_form'.$plugin_num.$num.'.getElementById("text_to'.$plugin_num.$num.'").value;
						
						if (select_from_value || select_to_value) {
						
							if (select_from_value == document.getElementById("print_select_from'.$num.'").options[1].value) {
								document.getElementById("print_select_from'.$num.'").selectedIndex = 1;
							} else {
								if (select_from_index != 0) {
									document.getElementById("print_select_from'.$num.'").options[2] = new Option(parent.direction_form'.$plugin_num.$num.'.getElementById("select_from'.$plugin_num.$num.'").options[parent.direction_form'.$plugin_num.$num.'.getElementById("select_from'.$plugin_num.$num.'").selectedIndex].text, select_from_value);
									document.getElementById("print_select_from'.$num.'").selectedIndex = 2;
								}
							}
							if (text_from_value) document.getElementById("print_text_from'.$num.'").value = text_from_value;
							
							if (select_to_value == document.getElementById("print_select_to'.$num.'").options[1].value) {
								document.getElementById("print_select_to'.$num.'").selectedIndex = 1;
							} else {
								if(select_to_index != 0) {
									document.getElementById("print_select_to'.$num.'").options[2] = new Option(parent.direction_form'.$plugin_num.$num.'.getElementById("select_to'.$plugin_num.$num.'").options[parent.direction_form'.$plugin_num.$num.'.getElementById("select_to'.$plugin_num.$num.'").selectedIndex].text, select_to_value);
									document.getElementById("print_select_to'.$num.'").selectedIndex = 2;
								}
							}
							if (text_to_value) document.getElementById("print_text_to'.$num.'").value = text_to_value;
	
							if ( (select_from_value!=0 || text_from_value!="") && (select_to_value!=0 || text_to_value!="")) {
								setTimeout("CalculRoute(\''.$num.'\', directionsDisplay'.$num.', directionsService'.$num.', travel_mode'.$num.', \''.$print_layout.'\')",1000); 
							}
						}
					</script>
					';
					}
			if($_layout == "map") {
				$print_fct = 'window.frame_print_itin'.$num.'.print();';
			} else {
				$print_fct = 'window.print();';
			}
			$carte.='
				</div>
			<div name="gmapfp_directions'.$num.'" id="gmapfp_directions'.$num.'"></div>
			<div id="bp_print_itin'.$num.'" style="float: right; display: none;">		
				<img title="'.JText::_('GMAPFP_IMPRIMER').'" onclick="'.$print_fct.'" src="'.$printer.'" />
				<br/>
				<br/>
			</div>
			<br/>
			<input type="hidden" name="direction" value="1" />
			</form>
		';
	};
    $carte.='</div>';

    // Charge la procédure d'init de la carte
	// première si pas besoin de rachraichissement
	// deuxieme si besoin de rafraichissement : pane
	if (false) {
    $doc->addCustomTag( '<script language="javascript" type="text/javascript">
    function LoadMarqueur'.$num.'() {
		setTimeout(initialise_gmapfp'.$num.' , 500);
 	}
    google.maps.event.addDomListener(window, "load", LoadMarqueur'.$num.');
    </script>');
	
	}else{
    $carte.= '<script language="javascript" type="text/javascript">

		google.maps.event.addDomListener(window, "load", initialise_map_gmapfp'.$num.');

		var tstGMapFP'.$num.' = document.getElementById("map_canvas'.$num.'");
		var tstIntGMapFP'.$num.';
			
		function CheckGMapFP'.$num.'() {
			if (tstGMapFP'.$num.') {
				if (tstGMapFP'.$num.'.offsetWidth != tstGMapFP'.$num.'.getAttribute("oldValue")) {
					tstGMapFP'.$num.'.setAttribute("oldValue",tstGMapFP'.$num.'.offsetWidth);
					initialise_gmapfp'.$num.'();
				}
			}
		}

		function initialise_map_gmapfp'.$num.'() {
		   tstGMapFP'.$num.'.setAttribute("oldValue",0);
		   tstIntGMapFP'.$num.' = setInterval("CheckGMapFP'.$num.'()",500);
		}
   </script>';
   }


?>