<?php

//the map info window can be overriden if a template with identical name is found in theme
$file = get_stylesheet_directory() . '/map-frame.php';
if ( file_exists( $file ) && !defined( '_FROM_PLUGIN_' ) ) {
    
   define( '_FROM_PLUGIN_', true );
   include $file;
   exit;
   
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--[if lte IE 6]>
<html xmlns:v="urn:schemas-microsoft-com:vml">
<![endif]-->

<head>    
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title></title>
    
    <?php 
        
        // Enqueue additional libraries
        global $geo_mashup_custom;
        wp_enqueue_script( 'jquery-spinner-js', $geo_mashup_custom->file_url( 'jquery.spinner.js' ) );
        wp_enqueue_script( 'markerclusterer-js', $geo_mashup_custom->file_url( 'markerclusterer_compiled.js' ) );
        wp_enqueue_script( 'infobox-js', $geo_mashup_custom->file_url( 'infobox_packed.js' ) );
        
        GeoMashupRenderMap::enqueue_script('jquery-spinner-js');
        GeoMashupRenderMap::enqueue_script('markerclusterer-js');
        GeoMashupRenderMap::enqueue_script('infobox-js');
        
        ob_start();
        GeoMashupRenderMap::head(); 
        $code = ob_get_clean();
        
        $code = str_replace('http://www.nellaterradisandokan.com/', 'http://'.$_SERVER['HTTP_HOST'].'/', $code);
        echo $code;
        
    ?>
    
    <style type="text/css">
        v\:* { 
            behavior:url(#default#VML); 
        }
        #geo-mashup {
            width: 100%;
            height: 100%;
            <?php if ( GeoMashupRenderMap::map_property( 'background_color' ) ) : ?>
            background-color: <?php echo GeoMashupRenderMap::map_property( 'background_color' ); ?>;
            <?php endif; ?>
        }
        img { 
            max-width: auto; <?php // fix for flexible or fluid layouts with max-width: 100% ?>
        }
    </style>
    
</head>

<body>
    
    <div id="geo-mashup">
        <noscript>
            <p><?php _e( 'This map requires JavaScript. You may have to enable it in your settings.', 'GeoMashup' ); ?></p>
        </noscript>
    </div>

    <?php echo GeoMashupRenderMap::map_script( 'geo-mashup' ); ?>
    
</body>
</html>
