<?php
/**
* Link Post Format Template 
*
* Used when "Classic" standard style is selected.
*
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( ' link' ); ?>>  
  
  <div class="inner-wrap animated">
    
    <div class="post-content classic">
      
      <?php get_template_part( 'includes/partials/blog/styles/standard-classic/post-meta' ); ?>
      
      <div class="content-inner">
        
        <?php
        
        // Output Link.
        get_template_part( 'includes/partials/blog/media/link' );
        
        ?>
        
      </div><!--content-inner-->
      
    </div><!--/post-content-->
    
  </div><!--/inner-wrap-->
  
</article>