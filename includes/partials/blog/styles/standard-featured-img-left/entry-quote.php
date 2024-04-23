<?php
/**
* Quote Post Format Template 
*
* Used when "Featured Image Left" standard style is selected.
*
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

$excerpt_length = ( ! empty( $nectar_options['blog_excerpt_length'] ) ) ? intval( $nectar_options['blog_excerpt_length'] ) : 15;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'quote' ); ?>>  
  <div class="inner-wrap animated">
    <div class="post-content">
      <div class="content-inner">
        <?php
        
        // Output Quote.
        get_template_part( 'includes/partials/blog/media/quote' );
        
        ?>
      </div>
    </div>
  </div>
</article>