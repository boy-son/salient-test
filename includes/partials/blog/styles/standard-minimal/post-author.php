<?php
/**
* Post author partial
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

?>

<div class="post-author">
  <?php
  if ( function_exists( 'get_avatar' ) ) {
    echo '<div class="grav-wrap"><a href="' . get_author_posts_url( $post->post_author ) . '">' . get_avatar( get_the_author_meta( 'email' ), 90, null, get_the_author() ) . '</a></div>'; 
  }
  ?>
  <span class="meta-author"> <?php the_author_posts_link(); ?></span>
  
  <?php
  echo '<span class="meta-category">';

  $categories = get_the_category();
  if ( ! empty( $categories ) ) {

    echo '<span class="in">' . esc_html__( 'In', 'salient' ) . ' </span>';

    $output    = null;
    $cat_count = 0;
    foreach ( $categories as $category ) {
      $output .= '<a class="' . $category->slug . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
      if ( count( $categories ) > 1 && ( $cat_count + 1 ) < count( $categories ) ) {
        $output .= ', ';
      }
      $cat_count++;
    }
    echo trim( $output );
    
  }
  echo '</span>';
  ?>
</div>