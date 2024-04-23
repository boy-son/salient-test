<?php
/**
* Link Post Format Template 
*
* Used when "Minimal" standard style is selected.
*
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

$use_excerpt = ( ! empty( $nectar_options['blog_auto_excerpt'] ) && $nectar_options['blog_auto_excerpt'] === '1' ) ? 'true' : 'false';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('link'); ?>>  
  <div class="inner-wrap animated">
    <div class="post-content">
      <?php get_template_part( 'includes/partials/blog/styles/standard-minimal/post-author' ); ?>
      <div class="content-inner">
        <div class="article-content-wrap">
          <?php
          
          // Output Link.
          get_template_part( 'includes/partials/blog/media/link' );

          ?>
        </div>
      </div>
    </div>
  </div>
</article>