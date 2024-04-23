<?php
/**
 * Gallery flexslider blog partial.
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
  
  wp_enqueue_script( 'flexslider' );
  
  $gallery_ids = nectar_grab_ids_from_gallery();
  
  ?>

    <div class="flex-gallery"> 
         <ul class="slides">
          <?php
            foreach ( $gallery_ids as $image_id ) {
               echo '<li>' . wp_get_attachment_image( $image_id, '', false, $image_attrs ) . '</li>';
            }
          ?>
        </ul>
      </div>

  <?php
} 

// Default to featured image.
else {
  
  $image_attrs = array(
    'title' => '',
    'sizes' => '(min-width: 1600px) 20vw, (min-width: 1300px) 25vw, (min-width: 1000px) 33.3vw, (min-width: 690px) 50vw, 100vw',
  );
  if( ! is_single() ) {
    echo '<a href="' . esc_url( get_permalink() ) . '">';
  }
  echo '<span class="post-featured-img">' . get_the_post_thumbnail( $post->ID, 'large', $image_attrs ) . '</span>';
  if( ! is_single() ) {
    echo '</a>';
  }
  
}