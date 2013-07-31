<?php

/**
 * INFO WINDOW
 *
 * this balloon 'popup' window template can be override
 * if a template with identical name is found in active theme
 *
 */

$file = get_stylesheet_directory() . '/info-window.php';

if ( file_exists( $file ) && !defined( '_FROM_PLUGIN_' ) ) :
    
       define( '_FROM_PLUGIN_', true );
       include $file;
       exit;
   
endif;

?>

<div class="locationinfo post-location-info">
    
    <?php if ( have_posts() ) : ?>
    
        <?php while ( have_posts() ) : the_post(); ?>
    
                <header>
                    <div>
                          <h2 class="locationinfo-title"><a href="<?php the_permalink() ?>" target="_blank" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                    </div>
                </header>
                <article class="locationinfo-content">
                    <?php the_excerpt(); ?>
                </article>
                <footer class="locationinfo-footer">
                </footer>
    
        <?php endwhile; ?>
    
    <?php else : ?>
    
        <article>
            
            Post Not Found
            
        </article>
    
    <?php endif; ?>
    
</div>
<div id="custom_info_window_b"></div>
