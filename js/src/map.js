/**
 * Salient Google Maps script file.
 *
 * @package Salient
 * @author ThemeNectar
 */
/* global google */
/* global nectarLove */

Object.keys = Object.keys || function(o) { 
    var result = []; 
    for(var name in o) { 
        if (o.hasOwnProperty(name)) 
          result.push(name); 
    } 
    return result; 
};

(function($) { 
  
  "use strict";
  
  jQuery(document).ready(function($){

    var enableAnimation, 
    extraColor, 
    greyscale, 
    enableZoom, 
    enableZoomConnect, 
    markerImg, 
    centerlng, 
    centerlat, 
    zoomLevel, 
    latLng;
    
    var map     = [],
    styles      = [],
    infoWindows = [];
    
    window.mapAPI_Loaded = function() {
      
      for(var i = 0; i < $('.nectar-google-map').length; i++) {
        infoWindows[i] = [];
      }
      
      $('.nectar-google-map').each(function(i){
        
        //map margin if page header
        if( $('#page-header-bg:not("[data-parallax=1]")').length > 0 && $('#contact-map').length > 0 ) { 
          $('#contact-map').css('margin-top', 0);  
          $('.container-wrap').css('padding-top', 0);
        } 
        if( $('#page-header-bg[data-parallax=1]').length > 0 ) {
          $('#contact-map').css('margin-top', '-30px');
        }
        
        zoomLevel  = parseFloat($(this).attr('data-zoom-level'));
        centerlat  = parseFloat($(this).attr('data-center-lat'));
        centerlng  = parseFloat($(this).attr('data-center-lng'));
        markerImg  = $(this).attr('data-marker-img');
        enableZoom = $(this).attr('data-enable-zoom');
        enableZoomConnect = (enableZoom == '1') ? false : true;
        greyscale  = $(this).attr('data-greyscale');
        extraColor = $(this).attr('data-extra-color');
        
        var ultraFlat = $(this).attr('data-ultra-flat');
        var darkColorScheme = $(this).attr('data-dark-color-scheme');
        var $flatObj = [];
        var $darkColorObj = [];
        enableAnimation = $(this).attr('data-enable-animation');
        
        if( isNaN(zoomLevel) ) { 
          zoomLevel = 12; 
        }
        if( isNaN(centerlat) ) { 
          centerlat = 51.47; 
        }
        if( isNaN(centerlng) ) { 
          centerlng = -0.268199; 
        }
        
        if( typeof enableAnimation != 'undefined' && enableAnimation == 1 && $(window).width() > 690) { 
          enableAnimation = google.maps.Animation.BOUNCE;
        } else { 
          enableAnimation = null; 
        }
        
        latLng = new google.maps.LatLng(centerlat,centerlng);
        
        //color
        
        if(ultraFlat == '1') {
          $flatObj = [{
            "featureType": "transit",
            "elementType": "geometry",
            "stylers": [
              { "visibility": "off" }
            ]
          },
          {
            "elementType": "labels",
            "stylers": [
              { "visibility": "off" }
            ]
          },
          {
            "featureType": "administrative",
            "stylers": [
              { "visibility": "off" }
            ]
          }];
        } else {
          $flatObj[0] = {};
          $flatObj[1] = {};
          $flatObj[2] = {};
        }
        
        
        if(darkColorScheme == '1') {
          $darkColorObj = [{
            "featureType": "all",
            "elementType": "labels.text.fill",
            "stylers": [
              {
                "saturation": 36
              },
              {
                "color": "#000000"
              },
              {
                "lightness": 40
              }
            ]
          },
          {
            "featureType": "all",
            "elementType": "labels.text.stroke",
            "stylers": [
              {
                "visibility": "on"
              },
              {
                "color": "#000000"
              },
              {
                "lightness": 16
              }
            ]
          },
          {
            "featureType": "all",
            "elementType": "labels.icon",
            "stylers": [
              {
                "visibility": "off"
              }
            ]
          },
          {
            "featureType": "administrative",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 20
              }
            ]
          },
          {
            "featureType": "administrative",
            "elementType": "geometry.stroke",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 17
              },
              {
                "weight": 1.2
              }
            ]
          },
          {
            "featureType": "landscape",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 20
              }
            ]
          },
          {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 21
              }
            ]
          },
          {
            "featureType": "road.highway",
            "elementType": "geometry.fill",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 17
              }
            ]
          },
          {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 29
              },
              {
                "weight": 0.2
              }
            ]
          },
          {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 18
              }
            ]
          },
          {
            "featureType": "road.local",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 16
              }
            ]
          },
          {
            "featureType": "transit",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 19
              }
            ]
          },
          {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
              {
                "color": "#000000"
              },
              {
                "lightness": 17
              }
            ]
          }];
        } else {
          $darkColorObj[0] = {};
          $darkColorObj[1] = {};
          $darkColorObj[2] = {};
          $darkColorObj[3] = {};
          $darkColorObj[4] = {};
          $darkColorObj[5] = {};
          $darkColorObj[6] = {};
          $darkColorObj[7] = {};
          $darkColorObj[8] = {};
          $darkColorObj[9] = {};
          $darkColorObj[10] = {};
          $darkColorObj[11] = {};
          $darkColorObj[12] = {};
        }
        
        if(greyscale == '1' && extraColor.length > 0) {
          styles = [
            
            {
              featureType: "poi",
              elementType: "labels",
              stylers: [{
                visibility: "off"
              }]
            }, 
            { 
              featureType: "road.local", 
              elementType: "labels.icon", 
              stylers: [{ 
                "visibility": "off" 
              }] 
            },
            { 
              featureType: "road.arterial", 
              elementType: "labels.icon", 
              stylers: [{ 
                "visibility": "off" 
              }] 
            },
            {
              featureType: "road",
              elementType: "geometry.stroke",
              stylers: [{
                visibility: "off"
              }]
            }, 
            { 
              featureType: "transit", 
              elementType: "geometry.fill", 
              stylers: [
                { hue: extraColor },
                { visibility: "on" }, 
                { lightness: 1 }, 
                { saturation: 7 }
              ]
            },
            {
              elementType: "labels",
              stylers: [{
                saturation: -100,
              }]
            }, 
            {
              featureType: "poi",
              elementType: "geometry.fill",
              stylers: [
                { hue: extraColor },
                { visibility: "on" }, 
                { lightness: 20 }, 
                { saturation: 7 }
              ]
            },
            {
              featureType: "landscape",
              stylers: [
                { hue: extraColor },
                { visibility: "on" }, 
                { lightness: 20 }, 
                { saturation: 20 }
              ]
              
            }, 
            {
              featureType: "road",
              elementType: "geometry.fill",
              stylers: [
                { hue: extraColor },
                { visibility: "on" }, 
                { lightness: 1 }, 
                { saturation: 7 }
              ]
            }, 
            {
              featureType: "water",
              elementType: "geometry",
              stylers: [
                { hue: extraColor },
                { visibility: "on" }, 
                { lightness: 1 }, 
                { saturation: 7 }
              ]
            },
            $darkColorObj[0],
            $darkColorObj[1],
            $darkColorObj[2],
            $darkColorObj[3],
            $darkColorObj[4],
            $darkColorObj[5],
            $darkColorObj[6],
            $darkColorObj[7],
            $darkColorObj[8],
            $darkColorObj[9],
            $darkColorObj[10],
            $darkColorObj[11],
            $darkColorObj[12],
            $flatObj[0],
            $flatObj[1],
            $flatObj[2]
          ];
          
        } 
        
        
        
        else if(greyscale == '1'){
          
          styles = [
            
            {
              featureType: "poi",
              elementType: "labels",
              stylers: [{
                visibility: "off"
              }]
            }, 
            { 
              featureType: "road.local", 
              elementType: "labels.icon", 
              stylers: [{ 
                "visibility": "off" 
              }] 
            },
            { 
              featureType: "road.arterial", 
              elementType: "labels.icon", 
              stylers: [{ 
                "visibility": "off" 
              }] 
            },
            {
              featureType: "road",
              elementType: "geometry.stroke",
              stylers: [{
                visibility: "off"
              }]
            }, 
            {
              elementType: "geometry",
              stylers: [{
                saturation: -100
              }]
            },
            {
              elementType: "labels",
              stylers: [{
                saturation: -100
              }]
            }, 
            {
              featureType: "poi",
              elementType: "geometry.fill",
              stylers: [{
                color: "#ffffff"
              }]
            },
            {
              featureType: "landscape",
              stylers: [{
                color: "#ffffff"
              }]
            }, 
            {
              featureType: "road",
              elementType: "geometry.fill",
              stylers: [ {
                color: "#f1f1f1"
              }]
            }, 
            {
              featureType: "water",
              elementType: "geometry",
              stylers: [{
                color: "#b9e7f4"
              }]
            },
            $darkColorObj[0],
            $darkColorObj[1],
            $darkColorObj[2],
            $darkColorObj[3],
            $darkColorObj[4],
            $darkColorObj[5],
            $darkColorObj[6],
            $darkColorObj[7],
            $darkColorObj[8],
            $darkColorObj[9],
            $darkColorObj[10],
            $darkColorObj[11],
            $darkColorObj[12],
            $flatObj[0],
            $flatObj[1],
            $flatObj[2]
          ];
          
          
        }
        
        
        else {
          styles = [];
        } 
        
        
        var styledMap = new google.maps.StyledMapType(styles,
          {name: "Styled Map"});
          
          
          //options
          var mapOptions = {
            mapId: $(this).attr('id'),
            center: latLng,
            zoom: zoomLevel,
            mapTypeControlOptions: {
              mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
            },
            scrollwheel: false,
            panControl: false,
            zoomControl: enableZoom,
            disableDoubleClickZoom: enableZoomConnect,	  
            zoomControlOptions: {
              style: google.maps.ZoomControlStyle.LARGE,
              position: google.maps.ControlPosition.LEFT_CENTER
            },
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false
            
          };
          
          map[i] = new google.maps.Map(document.getElementById($(this).attr('id')), mapOptions);
          
          //Associate the styled map with the MapTypeId and set it to display.
          map[i].mapTypes.set('map_style', styledMap);
          map[i].setMapTypeId('map_style');
          
          var $count = i;
          
          google.maps.event.addListenerOnce(map[i], 'tilesloaded', function() {
            
            var map_id = $(map[i].getDiv()).attr('id');
            
            //don't start the animation until the marker image is loaded if there is one
            if(markerImg.length > 0) {
              var markerImgLoad = new Image();
              markerImgLoad.src = markerImg;
              
              $(markerImgLoad).load(function(){
                setMarkers(map[i], map_id, $count);
              });
              
            }
            else {
              setMarkers(map[i], map_id, $count);
            }
          });
          
        });
        
        
        //watcher to resize gmap inside grow-in animatino col
        var $gMapsAnimatedSelector = $('.col.has-animation[data-animation="grow-in"] .nectar-google-map');
        var gMapsInterval = [];
        $gMapsAnimatedSelector.each(function(i){
          
          var $that = $(this);
          
          //watcher
          gMapsInterval[i] = setInterval(function(){
            
            if($that.parents('.col.has-animation[data-animation="grow-in"]').hasClass('animated-in')) {
              
              for(var k=0; k < map.length; k++ ) {
                google.maps.event.trigger(map[k], 'resize');
              }
              
              //clear watcher
              setTimeout(function(){
                clearInterval(gMapsInterval[i]);
              },1000); 
              
            }
            
          },500);
          
        });
        
        
      }; //api loaded
      
      
      
      if(typeof google === 'object' && typeof google.maps === 'object') {
        //skip processing.
      } else {
        
        if(nectarLove.mapApiKey.length > 0) {
          $.getScript('https://maps.googleapis.com/maps/api/js?sensor=false&key='+nectarLove.mapApiKey+'&libraries=places,marker,drawing,geometry&loading=async&callback=mapAPI_Loaded');
        } else {
          $.getScript('https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places,marker,drawing,geometry&loading=async&callback=mapAPI_Loaded');
        }
        
      }
      
    
    
      
      function setMarkers(map,map_id,count) {
        
        $('.map-marker-list.'+map_id).each(function(){
          
          var enableAnimation = $('#'+map_id).attr('data-enable-animation');
          
          $(this).find('.map-marker').each(function(i){

            var lat = $(this).attr('data-lat');
            // make sure a valid lat is found in data attr
            if ( lat.length === 0 ) {
              return;
            }
            
            let markerGlyph = new google.maps.marker.PinElement().element;

            //nectar marker 
            if($('#'+map_id).is('[data-marker-style="nectar"]')) {
              markerGlyph = $('' +
              '<div><div class="animated-dot">' +
              '<div class="middle-dot"></div>' +
              '<div class="signal"></div>' +
              '<div class="signal2"></div>' +
              '</div></div>' +
              '')[0];
            }

            // Custom image marker.
            if ($('#'+map_id).is('[data-marker-img]') && $('#'+map_id).attr('data-marker-img') !== '') {

              markerGlyph = document.createElement("img");
              // TODO: This works, but need to add a field to set a custom width.
              // var markerWidth = $(this).attr('data-marker-image-width');
              // if ( parseInt(markerWidth) < 30 ) {
              //   markerWidth = 30;
              // }
              // markerGlyph.style.width = parseInt(markerWidth) + 'px';
              markerGlyph.className = 'nectar-google-map__marker-img';
              markerGlyph.src = $('#'+map_id).attr('data-marker-img');
            }

            const mapIndex = count;
            const infoWindowIndex = i;

            var AdvancedMarkerElement = new google.maps.marker.AdvancedMarkerElement({
              position: new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng')),
              map: map,
              content: markerGlyph,
            }); 
            
            
            //info windows 
            if($(this).attr('data-mapinfo') != '' && $(this).attr('data-mapinfo') != '<br />' && $(this).attr('data-mapinfo') != '<br/>') {
              var infowindow = new google.maps.InfoWindow({
                content: $(this).attr('data-mapinfo'),
                maxWidth: 300
              });
              
              infoWindows[count].push(infowindow);
              
              AdvancedMarkerElement.addListener('click', (function( domEvent, latLng ) {     
                infoWindows[mapIndex][infoWindowIndex].open(map, this);
              }));
            }
            
            
          });
          
        });
        
        
      }//setMarker
      
  });
    
})(jQuery);  