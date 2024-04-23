<?php
/**
* Default Post Format Template 
*
* Used when "Featured Image Left" standard style is selected.
*
* @version 11.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

$excerpt_length = ( ! empty( $nectar_options['blog_excerpt_length'] ) ) ? intval( $nectar_options['blog_excerpt_length'] ) : 15;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>  
  <div class="inner-wrap animated">
    <div class="post-content">
      <div class="article-content-wrap">
        <div class="post-featured-img-wrap">
          <?php
          // Featured image.
          get_template_part( 'includes/partials/blog/styles/standard-featured-img-left/post-image' );
          ?>
          
        </div>
        <div class="post-content-wrap">
          <a class="entire-meta-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"></a>
          <?php 
          
          // Output categories.
          get_template_part( 'includes/partials/blog/styles/standard-featured-img-left/post-categories' );
          
          ?>
          
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
          get_template_part( 'includes/partials/blog/styles/standard-featured-img-left/post-bottom-meta' );
          
          ?>
        </div>
      </div>
    </div>
  </div>
</article>