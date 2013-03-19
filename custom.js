
var geomashupCustomMarkerCache = new Array();
var cluster = false;

GeoMashup.addAction( 'loadedMap', function( properties, object ) {
   //console.log(object);
   
   GeoMashup.map.maps.googlev3.setCenter(new google.maps.LatLng( GeoMashup.opts.near_lat, GeoMashup.opts.near_lng ));
   
   var mkrs = new Array();
   for (var i in object.markers)
   {
      mkrs.push( object.markers[i].proprietary_marker );
      //console.log(object.markers[i].proprietary_marker);
      var a = object.markers[i].proprietary_marker.getIcon();
      if (a) a.anchor = new google.maps.Point(16, 34);
   }
   // display clusters only when there are no polylines because close objects may overlap
   if ( (mkrs.length > 0) && ( !GeoMashup.opts.connecting_posts ) )
   {
      var o = {
         gridSize: 50,
         maxZoom: GeoMashup.opts.cluster_max_zoom
      };
      if (
         GeoMashup.opts.custom.cluster_icon_1
         && GeoMashup.opts.custom.cluster_icon_2
         && GeoMashup.opts.custom.cluster_icon_3
       )
       {
         o.styles = new Array();
         var i;
         for (i=1; i<=3; i++)
         {
            var k = "cluster_icon_"+i;
            o.styles.push({
              url: GeoMashup.opts.custom[k],
              height: GeoMashup.opts.custom["size_x_"+k],
              width: GeoMashup.opts.custom["size_y_"+k],
              anchor: [ GeoMashup.opts.custom["anchor_x_"+k], GeoMashup.opts.custom["anchor_y_"+k] ],
              textColor: GeoMashup.opts.custom["color_"+k],
              textSize: GeoMashup.opts.custom["size_"+k]
            });
         }
         //console.log(o);
       }
      cluster = new MarkerClusterer(GeoMashup.map.maps.googlev3, mkrs, o);
   }
   
   if ( GeoMashup.opts.connecting_posts ) {
      var path = new Array();
      var bounds = new google.maps.LatLngBounds();
      for (var i in GeoMashup.opts.connecting_posts)
      {
         var p = GeoMashup.opts.connecting_posts[i];
         var point = new google.maps.LatLng( p.lat, p.lng );
         path.push( point );
         bounds.extend( point );
      }
      var line = new google.maps.Polyline({
         path: path,
         strokeColor: '#ff0000',
         strokeOpacity: 1.0,
         strokeWeight: 3
      });
      GeoMashup.map.maps.googlev3.fitBounds(bounds);
      line.setMap( GeoMashup.map.maps.googlev3 );
   }
   
   google.maps.event.addListener(GeoMashup.map.maps.googlev3, 'zoom_changed', function() {

   });
   
   //if(0)
   google.maps.event.addListener(GeoMashup.map.maps.googlev3, "rightclick", function (e) {
   
      var menu_event = document.createEvent("MouseEvents");

       menu_event.initMouseEvent("contextmenu", true, true,
         e.view, 1, 0, 0, 0, 0, false,
         false, false, false, 2, null);

        e.originalTarget.dispatchEvent(menu_event);
   });
   
   
   
});

GeoMashup.addAction( 'objectIcon', function( properties, object ) {
   
	var image_prefix = '';//properties.url_path + '-custom/markers/';
	object.icon.image = image_prefix + object.post_marker_image;
	// Set icon size as preferred
        //console.log(GeoMashup.opts.custom.def_w);
	if (!object.icon_w) object.icon_w = GeoMashup.opts.custom.def_w;
	if (!object.icon_h) object.icon_h = GeoMashup.opts.custom.def_h;
	object.icon.iconSize = new Array( object.icon_w , object.icon_h );
	//console.log(object.icon.iconSize);
	// Hide the shadow
	//object.icon.shadow = null;
	// Using post type name
	//console.log(object);
	geomashupCustomMarkerCache[object.object_id] = object.post_type_name;
        
});


/*
 *  When a marker is selected, do nothing if it is the current post.
 *  Otherwise, use extinfowindow library to display its corresponding info window.
 *  In both cases though, GeoMashup still centers the map which is a good thing. 
 */ 
GeoMashup.addAction( 'multiObjectMarker', function( properties, marker ) {
    marker.setIcon( GeoMashup.opts.custom.multiple_icon, new Array( GeoMashup.opts.custom.size_x_multiple_icon, GeoMashup.opts.custom.size_y_multiple_icon ) );
});
 
// fix center
var theInt = setInterval(function () {
    
   if ( GeoMashup.map ) {
       
        if ((jQuery(GeoMashup.map.maps.googlev3.getDiv()).width() > 0) && (jQuery(GeoMashup.map.maps.googlev3.getDiv()).height() > 0)){
            
            setTimeout(function () {
                        GeoMashup.map.maps.googlev3.setCenter(new google.maps.LatLng( GeoMashup.opts.near_lat, GeoMashup.opts.near_lng ));
                        }, 100);
            clearInterval(theInt);
            
      }
      
   }
   
}, 10);
 
var infobox = false;
//var infoWindow = new google.maps.InfoWindow();
GeoMashup.addAction( 'selectedMarker', function( properties, marker, map) {
    
	var objects, ids, i, currect_post_detected;
	//console.log(marker);
	objects = GeoMashup.getObjectsAtLocation( marker.location.lat +", "+ marker.location.lon );
	ids = [];
	currect_post_detected = false;
        
	for( i = 0; i < objects.length; i += 1 ) {
		ids.push( objects[i].object_id );
		if (objects[i].is_current_post == true) {
			currect_post_detected = true;
		}
	}
	
	if (currect_post_detected == true) {return;}


        /*
        *  Image folder for info window, ajax loader and others
        */
	var image_prefix = properties.url_path + '-custom/images/';
	

	if (infobox) infobox.close();   
       
	GeoMashup.showLoadingIcon();
	
	jQuery.get(
        
		GeoMashup.geo_query_url,
		{ object_name: properties.object_name, object_ids: ids.join(','), template: 'info-window' },
                
		function( content ) {
                    
                    infobox = new InfoBox({
                        
                            content: content,
                            disableAutoPan: true,
                            maxWidth: 220,
                            pixelOffset: new google.maps.Size(-110-10+parseInt(GeoMashup.opts.custom.shift_x+0), -160-14+parseInt(GeoMashup.opts.custom.shift_y+0)),
                            zIndex: null,
                            pane: "floatPane",
                            contextmenu: true,
                            enableEventPropagation: true,
                            boxStyle: { },
                                        closeBoxMargin: "-10px 0px 0px 0px",
                                        closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                                        infoBoxClearance: new google.maps.Size(1, 1)
                            }
                    );
                        
                    infobox.open(GeoMashup.map.maps.googlev3, marker.proprietary_marker);
                    google.maps.event.removeListener(infobox.contextListener_);

                    GeoMashup.hideLoadingIcon();
       
                        
		}
	);
            
});