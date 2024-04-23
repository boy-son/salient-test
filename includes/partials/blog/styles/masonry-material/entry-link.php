<?php
/**
 * Link Post Format Template 
 *
 * Used when "Material" masonry style is selected.
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

<article id="post-<?php the_ID(); ?>" <?php post_class( $nectar_post_class_additions . ' link' ); ?>>  
    
  <div class="inner-wrap animated">
    
    <div class="post-content">
      
      <div class="content-inner">
        
        <?php
        
        // Output Link.
        get_template_part( 'includes/partials/blog/media/link' );
        
        ?>
        
      </div><!--/content-inner-->

    </div><!--/post-content-->
      
  </div><!--/inner-wrap-->
    
</article>