<?php
/**
 * Quote Post Format Template 
 *
 * Used when "Auto Masonry: Meta Overlaid Spaced" masonry style is selected.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $nectar_options;

$nectar_post_class_additions = ' masonry-blog-item';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $nectar_post_class_additions . ' quote' ); ?>>  
    
  <div class="inner-wrap animated">
    
    <div class="post-content">
      
      <div class="content-inner">
        
        <?php
        
        // Output Quote.
        get_template_part( 'includes/partials/blog/media/quote' );
        
        ?>
        
      </div><!--/content-inner-->

    </div><!--/post-content-->
      
  </div><!--/inner-wrap-->
    
</article>