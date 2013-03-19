<?php

function geo_mashup_custom_locations_json_filter( $json_properties, $queried_object ) {
    
    $key = 'gmtmp' . $queried_object->object_id;
    // uncomment the next line to enable caching
    if ( $ret = get_transient( $key ) ) 
            return $ret;
    
    $post_id = $queried_object->object_id;          // Get current Post ID
    $post_type = get_post_type($post_id);           // Determine Post Type of current Post by ID
    $obj = get_taxonomies( array( 'object_type' => array( $post_type ) ), 'objects' );
    $obj = array_keys( $obj );
    $taxonomy = $obj[0];
    
    // Markers can be overriden by placing them in the markers folder
    $default_markers_dir = WP_PLUGIN_URL . '/geo-mashup-custom/markers/';
    $default_markers_dir = preg_replace('/^http\:\/\/[^\/]+/', '', $default_markers_dir);
   
    $post_term = $post_type;                                                // ID will be used as fallback
    
    if ( $def_image_link == get_option( 'default_icon_' . $post_type ) ) {
        $post_legend_image = $def_image_link;                               // Fallback for a png named after the Post Type slug
    } else {
        $post_legend_image = $default_markers_dir.$post_type . '.png';      // Fallback for a png named after the Post Type slug
    }
    
    $post_marker_image  = $post_legend_image;       // This is used for the legend (optional)
    $is_current_post    = false;                    // This variable is used later to replace the marker for the current post, default is false
    $term_ids           = array();

    $term_objects = wp_get_post_terms( $post_id, $taxonomy,  array( "fields" => "all" ) );
    
    if ( !empty( $term_objects ) && function_exists( 'get_field' ) ) {
                    
            $post_terms = array();
            $icon = '';
            foreach ( $term_objects as $term_object ) {

                    $post_terms[]   = $term_object->slug;
                    $term_ids[]     = $term_object->term_id;
                    
                    if ( !$icon ) $icon = get_field( get_option( 'acf_icon' ), $taxonomy . '_' . $term_object->term_id );
                    
            }
            
            $post_term = strtolower( join( ',', $post_terms ) );

            if ( !$icon ) {
                
                $icon = $post_terms[0];
                $icon = $default_markers_dir.$icon.'.png';
            }
         
            $icon = preg_replace( '/^http\:\/\/[^\/]+/', '', $icon );
            $tmp_marker_image = $icon;

            if ( $icon ) {
                
                if ( file_exists( './' . $tmp_marker_image ) && is_file( './' . $tmp_marker_image ) ) {
                    
                        $post_marker_image = $tmp_marker_image;
                        $size = @getimagesize( $tmp_marker_image );
                        if ( $size ) {
                            
                            if ( $size[0] > 0 ) {
                            
                                $json_properties['icon_w'] = intval($size[0]);
                                $json_properties['icon_h'] = intval($size[1]);
                            
                            }
                        }
                }
                
            }
            
    }
        
	
    if ( get_the_ID() == $post_id ) {            
		
            $post_marker_image = get_option('currloc_icon');
            $json_properties['icon_w'] = intval(get_option('size_x_currloc_icon'));
            $json_properties['icon_h'] = intval(get_option('size_y_currloc_icon'));
            $is_current_post = true;		
                
    }
            
    
    $post_type_object   = get_post_type_object( $post_type );
    $post_type_name     = $post_type_object->labels->singular_name;

    $json_properties['post_type_name']      = $post_type_name;
    $json_properties['post_type']           = $post_type;
    $json_properties['post_term']           = $post_term;
    $json_properties['post_marker_image']   = $post_marker_image;
    $json_properties['post_legend_image']   = $post_legend_image;
    $json_properties['is_current_post']     = $is_current_post;
	
    if ( $post_marker_image == $def_image_link ) {
        
            $json_properties['icon_w'] = intval(get_option('size_x_default_icon_'.$post_type));
            $json_properties['icon_h'] = intval(get_option('size_y_default_icon_'.$post_type));
            
    }

   set_transient($key, $json_properties, 60*5);
   
   return $json_properties;        
}

add_filter( 'geo_mashup_locations_json_object', 'geo_mashup_custom_locations_json_filter', 10, 2 );
