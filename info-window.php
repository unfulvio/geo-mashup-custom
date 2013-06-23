<?php

//the ballon window can be overriden if a template with identical name is found in theme
$file = get_stylesheet_directory() . '/info-window.php';
if ( file_exists( $file ) && !defined( '_FROM_PLUGIN_' ) ) {
    
   define( '_FROM_PLUGIN_', true );
   include $file;
   exit;
   
}

?>


<div class="locationinfo post-location-info">
    
    <?php if (have_posts()) : ?>
    
        <?php while (have_posts()) : the_post(); ?>
    
                <header class="locationinfo-header">
                    
                    <?php 
                            if ( is_post_type( 'accommodation' ) ) {
                        
                                $terms = get_the_terms( $post_id, 'sg_taxon_accommodation_type' );
                                if ( !empty( $terms ) ) {
                                    
                                    echo'<ul class="locationinfo-cats">';
                                    $out = array();
                                    foreach ( $terms as $term ) {
                                        
                                        $out[] = sprintf( '<li class="locationinfo-cat"><a title="'.$term->description.'" href="'.get_term_link($term->slug, 'sg_taxon_accommodation_type').'">'.$term->name.'</a></li>');
                                
                                    } 
                                    echo join( ' - ', $out ).'</ul>';
                                    
                                } 
                                
                            } 
                            
                            elseif ( is_post_type( 'activity' ) ) {
                        
                                $terms = get_the_terms( $post_id, 'sg_taxon_shop_cat' );
                                if ( !empty( $terms ) ) {
                                    
                                    echo'<ul class="locationinfo-cats">';
                                    $out = array();
                                    foreach ( $terms as $term ) {
                                        
                                        $out[] = sprintf( '<li class="locationinfo-cat"><a title="'.$term->description.'" href="'.get_term_link($term->slug, 'sg_taxon_shop_cat').'">'.$term->name.'</a></li>');
                                    
                                    } 
                                    echo join( ' - ', $out ).'</ul>';
                                    
                                }   
                                
                            } 
                  
                            elseif ( is_post_type( 'dining' ) ) {   
                                
                                $terms = get_the_terms( $post_id, 'sg_taxon_dining_place' );
                                if ( !empty( $terms ) ) {
                                    
                                    echo'<ul class="locationinfo-cats">';
                                    $out = array();
                                    foreach ( $terms as $term ) {
                                        
                                        $out[] = sprintf( '<li class="locationinfo-cat"><a title="'.$term->description.'" href="'.get_term_link($term->slug, 'sg_taxon_dining_place').'">'.$term->name.'</a></li>');
                            
                                    } 
                                    echo join( ' - ', $out ).'</ul>';
                                    
                                }
                                
                            } 
                            
                            elseif ( is_post_type( 'heritage' ) ) {   
                                
                                $terms = get_the_terms( $post_id, 'sg_taxon_heritage_type' );
                                if ( !empty( $terms ) ) {
                                    
                                    echo'<ul class="locationinfo-cats">';
                                    $out = array();
                                    foreach ( $terms as $term ) {
                                        
                                        $out[] = sprintf( '<li class="locationinfo-cat"><a title="'.$term->description.'" href="'.get_term_link($term->slug, 'sg_taxon_heritage_type').'">'.$term->name.'</a></li>');
                                        
                                    } 
                                    echo join( ' - ', $out ).'</ul>';
                                }                              
                            }     
                            
                            elseif ( is_post_type( 'hiking' ) ) { 
                                
                                $terms = get_the_terms( $post_id, 'sg_taxon_hiking_type' );
                                if ( !empty( $terms ) ) {
                                    
                                    echo'<ul class="locationinfo-cats">';
                                    $out = array();
                                    foreach ( $terms as $term ) {
                                        
                                        $out[] = sprintf( '<li class="locationinfo-cat"><a title="'.$term->description.'" href="'.get_term_link($term->slug, 'sg_taxon_hiking_type').'">'.$term->name.'</a></li>');
                                            
                                    } 
                                    echo join( ' - ', $out ).'</ul>';
                                }
                            }
                            
                            elseif ( is_post_type( 'nightlife' ) ) { 
                                
                                $terms = get_the_terms( $post_id, 'sg_taxon_nightlife_type' );
                        if ( !empty( $terms ) ) {
                            
                            echo'<ul class="locationinfo-cats">';
                            $out = array();
                            foreach ( $terms as $term ) {
                                
                                $out[] = sprintf( '<li class="locationinfo-cat"><a title="'.$term->description.'" href="'.get_term_link($term->slug, 'sg_taxon_nightlife_type').'">'.$term->name.'</a></li>');
                            
                            } 
                            echo join( ' - ', $out ).'</ul>';
                            
                            }
                    } ?>
                    
                    <div class="locationinfo-heading">
                        
                        <div class="locationinfo-thumb">
                            <span><?php echo get_the_post_thumbnail($post->ID, '32');?></span>
                        </div>
                        <hgroup>
                            <h2 class="locationinfo-title"><a href="<?php the_permalink() ?>" target="_blank" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                            <h5 class="locationinfo-title"><?php $title_subtitle = get_field('title_subtitle'); if($title_subtitle) { echo $title_subtitle; } ?></h5>
                        </hgroup>
                        
                    </div>
                    
                </header>
    
                <article class="locationinfo-content">
                    
                    <?php the_field('location_address'); ?>
                    
                </article>
    
                <footer class="locationinfo-footer">
                    <a href="<?php the_permalink() ?>" target="_blank" title="<?php _e('Clicca per saperne di pi&ugrave', 'salgari'); ?>"></a>
                </footer>
    
        <?php endwhile; ?>
    
    <?php else : ?>
    
        <article>
            
            <h5><?php _e( 'Elemento non trovato', 'salgari' ); ?></h5>
            <p><?php _e( 'Ci scusiamo dell\'inconveniente', 'salgari' ); ?>.</p>
            <p><?php _e( 'Si prega di contattare l\'amministrazione', 'salgari' ); ?>.<br /><?php _e( 'Grazie', 'salgari'); ?>.</p>
            
        </article>
    
    <?php endif; ?>
    
</div>
<div id="custom_info_window_b"></div>
