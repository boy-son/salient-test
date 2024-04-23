<?php
/**
 * Audio Post Format Template 
 *
 * Used when "Material" masonry style is selected.
 *
 * @version 11.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $nectar_options;

$nectar_post_class_additions = ' masonry-blog-item';
$use_excerpt                 = ( ! empty( $nectar_options['blog_auto_excerpt'] ) && $nectar_options['blog_auto_excerpt'] === '1' ) ? 'true' : 'false';
$excerpt_length              = ( ! empty( $nectar_options['blog_excerpt_length'] ) ) ? intval( $nectar_options['blog_excerpt_length'] ) : 15;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $nectar_post_class_additions ); ?>>  
    
  <div class="inner-wrap animated">
    
    <div class="post-content">
      
      <div class="content-inner">
        
        <a class="entire-meta-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"></a>
        
        <?php
        
          // Featured image.
          get_template_part( 'includes/partials/blog/styles/masonry-material/post-image' );

          // Output categories.
          get_template_part( 'includes/partials/blog/styles/masonry-material/post-categories' );
        
        ?>
        
        <div class="article-content-wrap">
          
          <div class="post-header">
            <h3 class="title"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
          </div>
          
          <?php 

            // Excerpt.
            echo '<div class="excerpt">';
              echo nectar_excerpt( $excerpt_length );
            echo '</div>';

            do_action('nectar_after_archive_post_item_content');
            
            // Bottom author link & date.
            get_template_part( 'includes/partials/blog/styles/masonry-material/post-bottom-meta' );

          ?>

        </div><!--article-content-wrap-->
        
      </div><!--/content-inner-->
        
    </div><!--/post-content-->
      
  </div><!--/inner-wrap-->
    
</article>