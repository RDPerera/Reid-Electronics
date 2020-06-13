	/*
	* GMapFP Component Google Map for Joomla! 3.0.x
	* Version J3_52F
	* Creation date: Janvier 2018
	* Author: Fabrice4821 - www.gmapfp.org
	* Author email: webmaster@gmapfp.org
	* License GNU/GPL
	*/

var map_name = '';
var traffic_name = '';
/*************************************************************/
/* Charge les photos Paroramio contenu dans la carte visible */
/* voir http://www.panoramio.com/api/                        */
/*************************************************************/
function inverse_Traffic(my_map, my_traffic){
	map_name = my_map;
	traffic_name = my_traffic;

	if (traffic_name.enable) {
		traffic_name.enable = false;
		traffic_name.setMap(null);
	}else{
		traffic_name.enable = true;
		traffic_name.setMap(map_name);
	}
}

/**************************************************************/
/* Charge les données Wikipédia contenu dans la carte visible */
/**************************************************************/
//var geoXml;
/*
function loadDataWikipedia() {
      geoXml = new geoXML3.parser({
        zoom: true,
        processStyles: true,
        markerOptions: {map: carteGMapFP, shadow: null},
        infoWindowOptions: {pixelOffset: new google.maps.Size(0, 12)},
        singleInfoWindow: true,
        createMarker: addMarker,
        afterParse: parsed,
        failedParse: failed
      });
alert('tutu');
	var bounds = carteGMapFP.getBounds();
	var url = 'data/wikipedia_bounds.kml.php?maxRows=10&west=' + 
	mapBounds.getSouthWest().lng().toFixed(6) + '&north=' + 
	mapBounds.getNorthEast().lat().toFixed(6) + '&east=' + 
	mapBounds.getNorthEast().lng().toFixed(6) + '&south=' +  
	mapBounds.getSouthWest().lat().toFixed(6);

	// Load the KML - new markers will be added when it returns
	alert(geoXml.parse(url));
}
function addMarker(placemark) {
  var coordinates = new google.maps.LatLng(placemark.point.lat, placemark.point.lng);
  for (var m = markers.length - 1; m >= 0; m--) {
	if (markers[m].get_position().equals(coordinates)) {
	  return;
	}
  }

  var marker = geoXml.createMarker(placemark);
  markers.push(marker);
};
*/

/*********************************************************/
/* Calcul l'itinéraire                                   */
/*********************************************************/
function onchange_travel_mode(modeselected, mode, num) {
	switch (modeselected) {
	case "bike": 
		mode = google.maps.DirectionsTravelMode.BICYCLING;
		document.getElementById("map_bike_button"+num).className = "map-bike-button-selected"
		document.getElementById("map_walk_button"+num).className = "map-walk-button"
		document.getElementById("map_car_button"+num).className = "map-car-button"
		break;
	case "walk": 
		mode = google.maps.DirectionsTravelMode.WALKING;
		document.getElementById("map_bike_button"+num).className = "map-bike-button"
		document.getElementById("map_walk_button"+num).className = "map-walk-button-selected"
		document.getElementById("map_car_button"+num).className = "map-car-button"
		break;
	default: 
		mode = google.maps.DirectionsTravelMode.DRIVING;
		document.getElementById("map_bike_button"+num).className = "map-bike-button"
		document.getElementById("map_walk_button"+num).className = "map-walk-button"
		document.getElementById("map_car_button"+num).className = "map-car-button-selected"
	}
	return mode;
}

function HideShow_OptionsRoute(num) {
	style = document.getElementById("map-options-routes_choose"+num).style.display;
	if (style == "none") {
		document.getElementById("map-options-routes_choose"+num).style.display = "block";
	} else {
		document.getElementById("map-options-routes_choose"+num).style.display = "none";
	}
}

function CalculRoute(num, display_name, service_name, options_route , tmpl) {
	if (document.getElementById(tmpl+"select_from"+num).selectedIndex != 0) { 
		i = document.getElementById(tmpl+"select_from"+num).value.lastIndexOf(",");
		fromAddress = document.getElementById(tmpl+"select_from"+num).value.substring(0, i); 
	} else {
		fromAddress = document.getElementById(tmpl+"text_from"+num).value; 
	}; 
	if (document.getElementById(tmpl+"select_to"+num).selectedIndex != 0) { 
		i = document.getElementById(tmpl+"select_to"+num).value.lastIndexOf(",");
		toAddress = document.getElementById(tmpl+"select_to"+num).value.substring(0, i); 
	} else { 
		toAddress = document.getElementById(tmpl+"text_to"+num).value; 
	}; 
	HighWays = document.getElementById("checkbox_autoroute"+num).checked;
	Tolls = document.getElementById("checkbox_peage"+num).checked;
	if (document.getElementById("checkbox_unite"+num).checked) {
		Unit = google.maps.DirectionsUnitSystem.METRIC;
	} else {
		Unit = google.maps.DirectionsUnitSystem.IMPERIAL;
	}
	var request = {
		origin: fromAddress, 
		destination: toAddress,
		travelMode: options_route,
		avoidHighways: HighWays,
		avoidTolls: Tolls,
		unitSystem: Unit,
		provideRouteAlternatives: true
	};

	/*  region: 'en-GB'
		avoid (optional) indicates that the calculated route(s) should avoid the indicated features. Currently, this parameter supports the following two arguments:
			tolls indicates that the calculated route should avoid toll roads/bridges.
			highways indicates that the calculated route should avoid highways.
	*/
	service_name.route(request, function(response, status) {
	  if (status == google.maps.DirectionsStatus.OK) {
		display_name.setDirections(response);
	  } else {
		if (! tmpl) {
			switch(status) {
				case "NOT_FOUND": message = MessageRoute[1]; break;
				case "ZERO_RESULTS": message = MessageRoute[2]; break;
				case "MAX_WAYPOINTS_EXCEEDED": message = MessageRoute[3]; break;
				case "INVALID_REQUEST": message = MessageRoute[4]; break;
				case "OVER_QUERY_LIMIT": message = MessageRoute[5]; break;
				case "REQUEST_DENIED": message = MessageRoute[6]; break;
				case "UNKNOWN_ERROR": message = MessageRoute[7]; break;
			}
			alert(message);
		}
	  }
	});
}

/*********************************************************/
/* Affiche le menu more options                          */
/*********************************************************/

// Define a property to hold the More state
MoreControl.prototype.home_ = null;

// Define setters and getters for this property
MoreControl.prototype.getMore = function() {
    control.setMorePano.style.display = 'block';
	alert('fc_getmore');
  //return this.home_;
}

MoreControl.prototype.setMore = function(home) {
  alert('fc_setMore');
  //this.home_ = home;
}

function MoreControl(my_map, div, my_photo, enable_photos, my_traffic, enable_traffic) {
	var visible = false;

  // Get the control DIV. We'll attach our control UI to this DIV.
  var controlDiv = div;

  // We set up a variable for the 'this' keyword since we're adding event
  // listeners later and 'this' will be out of scope.
  var control = this;

  // Set CSS styles for the DIV containing the control. Setting padding to
  // 5 px will offset the control from the edge of the map
  controlDiv.style.padding = '5px';

  // Set CSS for the control border
  var MoreUI = document.createElement('DIV');
  MoreUI.style.backgroundColor = 'white';
  MoreUI.style.borderStyle = 'solid';
  MoreUI.style.borderWidth = '2px';
  MoreUI.style.cursor = 'pointer';
  MoreUI.style.textAlign = 'center';
  MoreUI.style.width = '120px';
  MoreUI.className = 'gmnoprint';
  MoreUI.title = moreControlText[1];
  controlDiv.appendChild(MoreUI);

  // Set CSS for the control interior
  var MoreText = document.createElement('DIV');
  MoreText.style.fontFamily = 'Arial,sans-serif';
  MoreText.style.fontSize = '12px';
  MoreText.style.paddingLeft = '4px';
  MoreText.style.paddingRight = '4px';
  MoreText.style.color = 'black';
  MoreText.innerHTML = '<b>'+moreControlText[0]+'</b><img style="position: absolute; right: 9px; top: 9px; display: block; " src="' + base_url_gmapfp + 'components/com_gmapfp/images/down-arrow.gif">';
  MoreUI.appendChild(MoreText);
  
  // Set CSS for the setMore control border
  var MoreDisplayUI = document.createElement('DIV');
  MoreDisplayUI.style.backgroundColor = 'white';
  MoreDisplayUI.style.borderStyle = 'solid';
  MoreDisplayUI.style.borderWidth = '1px';
  MoreDisplayUI.style.cursor = 'pointer';
  MoreDisplayUI.style.textAlign = 'center';
  MoreDisplayUI.style.fontFamily = 'Arial,sans-serif';
  MoreDisplayUI.style.fontSize = '12px';
  MoreDisplayUI.style.paddingLeft = '4px';
  MoreDisplayUI.style.paddingRight = '4px';
  MoreDisplayUI.style.color = 'black';
  MoreDisplayUI.style.display = 'none';
  MoreDisplayUI.title = moreControlText[2];
  controlDiv.appendChild(MoreDisplayUI);

  // Set CSS for the control interior

  var Traffic = document.createElement('DIV');
  var Vchecked = "";
  if (enable_traffic) Vchecked = "checked";
  Traffic.innerHTML = '<input id="Traffic_check" type="checkbox" '+Vchecked+' onclick="inverse_Traffic('+my_map+', '+my_traffic+')" /> <label for="Traffic_check">'+moreControlText[4]+'<label><br />';
  MoreDisplayUI.appendChild(Traffic);

  // Setup the click event listener for More:
  google.maps.event.addDomListener(MoreUI, 'click', function() {
	if (visible) {
		MoreDisplayUI.style.display = 'none';
		visible = false;
	}else{
		MoreDisplayUI.style.display = 'block';
		visible = true;
	}
  });
}
