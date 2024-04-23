<?php
/**
 * Default Post Format Template 
 *
 * Used when "Auto Masonry: Meta Overlaid Spaced" masonry style is selected.
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

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $nectar_post_class_additions ); ?>>  
    
  <div class="inner-wrap animated">
    
    <div class="post-content">

      <div class="content-inner">
        
        <a class="entire-meta-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"></a>
        
        <?php
        
          // Featured image.
          get_template_part( 'includes/partials/blog/styles/masonry-auto-meta-overlaid-spaced/post-image' );

        ?>
        
        <div class="article-content-wrap">
          
          <?php 
          
          // Output categories.
          get_template_part( 'includes/partials/blog/styles/masonry-auto-meta-overlaid-spaced/post-categories' );
          
          ?>
          
          <div class="post-header">
            <h3 class="title"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
            <?php do_action('nectar_after_archive_post_item_content'); ?>
          </div>
          
        </div><!--article-content-wrap-->
        
      </div><!--/content-inner-->
        
    </div><!--/post-content-->
      
  </div><!--/inner-wrap-->
    
</article>