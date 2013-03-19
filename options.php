<?php

// default width and height for markers
$def_options = array(
    
   "def_w" => 32,
   "def_h" => 37,
    
);

// update options
foreach ( $def_options as $k => $v ) {
    
   if ( !get_option( $k) )
      update_option( $k, $v );
   
}

/**
 * Geo Mashup Custom settings
 * 
 * @param   array $o
 * @return  mixed
 */
function add_custom_options($o) {
    
    $o['custom'] = array();
    $keys = explode(',', get_option( 'my_settings_list' ) );

    foreach ($keys as $key) {

         $v = get_option( $key );
         if ( is_numeric( $v ) ) $v = intval( $v );
         $o['custom'][$key] = $v;

    }
    return $o;
   
}

if ( is_admin() ){
    
    /**
     * Geo Mashup Custom plugin options page
     * 
     * Add a settings page to admin
     * 
     * @internal
     */
    function gmc_admin_menu() {
        add_options_page( 'Geo Mashup Custom', 'Geo Mashup Custom', 'administrator', 'gmc', 'gmc_html_page' );    
    }
    add_action( 'admin_menu', 'gmc_admin_menu' );
    
}

/**
 * Marker Icon input 
 * 
 * Generates an input for marker URL and size, used in settings iterations 
 * 
 * @param string $name The name of each input 
 */
function echo_icon( $name ) {
    
    global $d;
    $d[] = $name; 
    $d[] = 'size_x_' . $name;
    $d[] = 'size_y_' . $name;
   
    ?>

            <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
            value="<?php echo htmlspecialchars(get_option($name)); ?>" style="width: 300px;" />
            (
                <?php _e( 'width:', 'GeoMashup' ); ?> 
                    <input name="<?php echo 'size_x_' . $name; ?>" type="text" id="<?php echo 'size_x_' . $name; ?>"
                    value="<?php echo htmlspecialchars(get_option( 'size_x_' . $name)); ?>" style="width: 25px;" /> px, 
                <?php _e ( 'height:', 'GeoMashup' ); ?>
                    <input name="<?php echo 'size_y_' . $name; ?>" type="text" id="<?php echo 'size_y_' . $name; ?>"
                    value="<?php echo htmlspecialchars(get_option( 'size_y_' . $name )); ?>" style="width: 25px;" /> px
            )

    <?php

}

/**
 * Output Geo Mashup Custom options page
 * 
 * HTML for the admin settings page
 *  
 */
function gmc_html_page() {

    // update settings
    if ( $_POST['action'] == 'update' ) {
        
            update_option( 'my_settings_list', $_POST['page_options'] );
            $keys = explode( ',', $_POST['page_options'] );
            foreach ( $keys as $key ) {

                    $v = $_POST[$key];
                    if ( is_array( $v ) )
                        $v = implode( ',', $v );
                        update_option( $key, $v );

            }
            
            ?>
            <div id="setting-error-settings_updated" class="updated settings-error"> 
                <p><strong><?php _e( 'Settings saved', 'GeoMashup' ); ?></strong></p>
            </div>
            <?php
        
    }

    global $d;
    $d = array();
    
    ?>
    <div id="geo-mashup-custom-settings" class="wrap">
        <h2><?php _e( 'Geo Mashup Custom options', 'GeoMashup' ); ?></h2>
        <hr />
        <br />
        
        <form method="post">

            <?php wp_nonce_field('update-options'); ?>

            <table class="form-table">

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( 'Taxonomies to pick markers from:', 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                    </th>
                
                    <td>
                        <table border="1" cellspacing="0" cellpadding="3">
                            <?php
                            
                                $post_types = get_post_types( '', 'objects' ); 
                                $_taxes     = get_taxonomies( null, 'objects' );

                                foreach ( $post_types as $post_type ) {

                                        $taxes = $_taxes;
                                        foreach ($taxes as $k => $v)

                                             if ( !in_array( $post_type->name, $v->object_type ) )
                                                 unset( $taxes[$k] );

                                             if ( !count($taxes) )  
                                                 continue;

                                        $name   = 'my_post_types';
                                        $d[]    = $name;

                                        ?>

                                        <tr class="custom_posts">
                                            <td align="left" valign="top">
                                                <label>
                                                    <input type="checkbox" name="<?php echo $name; ?>[]" 
                                                           value="<?php echo $post_type->name; ?>" <?php echo ( in_array( $post_type->name, explode( ',', get_option( $name ) ) ) ? ' checked="checked"' : '' ); ?>/>
                                                    <?php echo $post_type->label; ?>(<i><?php echo $post_type->name; ?></i>)
                                                </label>
                                            </td>
                                            <td>
                                                <?php _e( 'Marker:', 'GeoMashup' ); ?>
                                                <?php echo_icon( 'default_icon_' . $post_type->name ); ?>

                                                <br/>

                                                <?php _e ( 'Taxonomy used to store markers:', 'GeoMashup' ); ?>

                                                <?php 

                                                foreach ( $taxes as $tax ) {
                                                        
                                                        echo '<br/>';
                                                        $name = 'my_taxonomies';
                                                        $d[] = $name;
                                                        
                                                        ?>
                                                        <label>
                                                            <input type="checkbox" name="<?php echo $name; ?>[]" value="<?php echo $tax->name ?>" <?php echo ( in_array( $tax->name, explode( ',', get_option( $name ) ) ) ? ' checked="checked"' : ''); ?>/> 
                                                            <?php echo $tax->label; ?> (<i><?php echo $tax->name; ?></i>)
                                                        </label>
                                                        <?php 

                                                }

                                                ?>
                                                <br />
                                            </td>
                                        </tr>
                                        <?php 
                                            
                                    } 
                                    
                            ?>
                        </table>
                    </td>
                </tr>

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( 'Term meta to look for markers:', 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                    </th>
                    <td>
                        <?php $name = 'acf_icon'; $d[] = $name; ?>
                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                        value="<?php echo htmlspecialchars(get_option( $name )); ?>" style="width: 500px;" />
                        <p class="description">
                            <?php _e( 'The term meta GeoMashup will look for an URL pointing to a marker image to use for posts associated with the term.', 'GeoMashup' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( "Marker for posts sharing the same coordinates:", 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                    </th>
                    <td>
                        <?php $name = 'multiple_icon'; echo_icon( $name ); $d[] = $name; ?>
                        <p class="description">
                            <?php _e( 'When two or more posts share the same coordinates a unique marker will be used. You can override GeoMashup default by pointing an URL with a marker image.', 'GeoMashup' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( "Marker for currently viewed post:", 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                    </th>
                    <td>
                        <?php $name = 'currloc_icon'; echo_icon( $name ); $d[]=$name; ?>
                        <p class="description">
                            <?php _e( 'You can set a marker image to associate to the current post coordinates when viewing an individual post.', 'GeoMashup' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( 'Default marker size:', 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                    </th>
                    <td>
                        
                        <?php _e( 'width:', 'GeoMashup' ); ?>
                        <?php $name="def_w"; $d[]=$name; ?>
                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                        value="<?php echo htmlspecialchars(get_option($name)); ?>" style="width: 25px;" />px, 
                        
                        <?php _e( 'height:', 'GeoMashup' ); ?>
                        <?php $name="def_h"; $d[]=$name; ?>
                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                        value="<?php echo htmlspecialchars(get_option($name)); ?>" style="width: 25px;" />px 
                        
                        <p class="description">
                            <?php _e( 'You can set a default marker size for the images being used.', 'GeoMashup' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( 'Popup shift:', 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                    </th>
                    <td>
                        x: <?php $name = "shift_x"; $d[] = $name; ?>
                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                        value="<?php echo htmlspecialchars(get_option($name)); ?>" style="width: 25px;" /> px,
                        y: <?php $name = "shift_y"; $d[] = $name; ?>
                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                        value="<?php echo htmlspecialchars(get_option( $name )); ?>" style="width: 25px;" /> px
                        
                        <p class="description">
                            <?php _e( 'You can trim the position of the markers comparatively to the coordinates point on the map.', 'GeoMashup' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="middle">
                    <th scope="row" align="left" valign="top">
                        <?php _e( 'Clusters:', 'GeoMashup' ); ?>&nbsp;&nbsp;&nbsp;
                        <br />
                        <p class="description">
                            <?php _e( 'You can override default Google Maps cluster icons and labels.', 'GeoMashup' ); ?>
                        </p>
                    </th>
                    <td>
                        <?php
                                for ( $i=1; $i<=3; $i++ ) {
                                    
                                        echo '<b>' . __( 'Cluster icon', 'GeoMashup' ) . ' #' . $i . '</b>: <br/>';
                                        $_name = 'cluster_icon_' . $i; 
                                        echo_icon( $_name );

                                        ?>
                        
                                        <br/>
                                        <?php _e( 'Anchor:', 'GeoMashup' ); ?>
                                        
                                        <?php $name = 'anchor_x_' . $_name; $d[] = $name; ?>
                                        x = <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                                            value="<?php echo htmlspecialchars(get_option( $name )); ?>" style="width: 25px;" /> px,
                                        <?php $name = 'anchor_y_' . $_name; $d[] = $name; ?>
                                        y = <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                                            value="<?php echo htmlspecialchars(get_option( $name )); ?>" style="width: 25px;" /> px
                                        
                                        <br/>
                                        <?php _e( 'Color:', 'GeoMashup' ); ?>
                                        
                                        <?php $name = 'color_' . $_name; $d[] = $name; ?>
                                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                                        value="<?php echo htmlspecialchars(get_option( $name )); ?>" style="width: 50px;" />
                                        
                                        <br/>
                                        <?php _e( 'Font size:', 'GeoMashup' ); ?>
                                        <?php $name = 'size_' . $_name; $d[] = $name; ?>
                                        <input name="<?php echo $name; ?>" type="text" id="<?php echo $name; ?>"
                                        value="<?php echo htmlspecialchars(get_option( $name )); ?>" style="width: 50px;" /> px<br />
                                        
                                        <?php
                                        if ( $i != 3 ) 
                                            echo '<br/>';
                                        
                                }
                        ?>
                    </td>
                </tr>

            </table>

            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="<?php echo htmlspecialchars(implode( ',', array_unique( $d ) )); ?>" />

            <p class="submit">
                <input type="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'GeoMashup' ) ?>" />
            </p>

        </form>
    </div>

               
    <script>
        jQuery(function($){
            
            $(".custom_posts").each(function () {
               
                $(this).find("td:eq(0) input[type=checkbox]")
                    .click(function () {
                        var e = $(this).parents("tr").find("td:eq(1) input[type=checkbox]");
                        return;
                        if ( $(this).attr("checked") )
                            e.eq(0).attr("checked", "checked");
                        else
                            e.removeAttr("checked");
                        
                    });
                 
            });
           
        });
    </script>
    
    <?php
    
}
