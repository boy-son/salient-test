<?php
/**
 * Gallery flickity blog partial.
 *
 * Does not take masonry sizing into account.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$enable_gallery_slider = get_post_meta( get_the_ID(), '_nectar_gallery_slider', true );
$image_attrs           = array(
  'class' => 'attachment-full wp-post-image',
);

// Check if the gallery slider is enabled.
if ( ! empty( $enable_gallery_slider ) && 'on' === $enable_gallery_slider ) {
  
  wp_enqueue_script( 'flickity' );
  wp_enqueue_style( 'nectar-flickity' );

  $gallery_ids            = nectar_grab_ids_from_gallery();
  $img_size               = ( is_single() ) ? 'full' : 'portfolio-thumb';
  $flickity_masonry_class = ( is_single() ) ? '' : 'masonry';

  echo '<div class="nectar-flickity ' . $flickity_masonry_class . ' not-initialized" data-controls><div class="flickity-viewport"> <div class="flickity-slider">';
  
  foreach ( $gallery_ids as $image_id ) {
      echo '<div class="cell">' . wp_get_attachment_image( $image_id, $img_size, false, $image_attrs ) . '</div>';
  }
  
  echo '</div></div></div>';

} 

// Default to featured image.
else if( ! is_single() ) {
  
  $image_attrs = array(
    'title' => '',
    'sizes' => '(min-width: 1600px) 20vw, (min-width: 1300px) 25vw, (min-width: 1000px) 33.3vw, (min-width: 690px) 50vw, 100vw',
  );

  echo '<a href="' . esc_url( get_permalink() ) . '" class="img-link">';
  echo '<span class="post-featured-img">' . get_the_post_thumbnail( $post->ID, 'large', $image_attrs ) . '</span>';
  echo '</a>';
  
  
}