<?php
/**
 * Link Post Format Template 
 *
 * Used when "Classic Enhanced" masonry style is selected.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $nectar_options;

$masonry_size_pm             = get_post_meta( $post->ID, '_post_item_masonry_sizing', true );
$masonry_item_sizing         = ( ! empty( $masonry_size_pm ) ) ? $masonry_size_pm : 'regular';
$nectar_post_class_additions = $masonry_item_sizing . ' masonry-blog-item';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $nectar_post_class_additions . ' link' ); ?>>  
    
  <div class="inner-wrap animated">
    
    <div class="post-content">
      
      <div class="content-inner">
        
        <?php
        
        // Output Quote.
        get_template_part( 'includes/partials/blog/media/link' );
        
        ?>
        
      </div><!--/content-inner-->

    </div><!--/post-content-->
      
  </div><!--/inner-wrap-->
    
</article>