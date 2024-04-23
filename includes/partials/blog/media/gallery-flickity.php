<?php
/**
 * Gallery flickity blog partial.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$masonry_size_pm       = get_post_meta( $post->ID, '_post_item_masonry_sizing', true );
$masonry_item_sizing   = ( ! empty( $masonry_size_pm ) ) ? $masonry_size_pm : 'regular';
$img_size              = ( ! empty( $masonry_item_sizing ) && 'regular' === $masonry_item_sizing ) ? 'portfolio-thumb' : 'full';
$enable_gallery_slider = get_post_meta( get_the_ID(), '_nectar_gallery_slider', true );
$image_attrs           = array(
  'class' => 'attachment-full wp-post-image',
);

// Check if the gallery slider is enabled.
if ( ! empty( $enable_gallery_slider ) && 'on' === $enable_gallery_slider ) {
  
  wp_enqueue_script( 'flickity' );
  wp_enqueue_style( 'nectar-flickity' );
  
  // Alter image size when on single post.
  if( is_single() ) {
    $img_size = 'large';
    $flickity_masonry_class  = '';
    $flickity_controls_style = 'material_pagination';
  } else {
    $flickity_masonry_class  = 'masonry';
    $flickity_controls_style = '';
  }
  
  $gallery_ids = nectar_grab_ids_from_gallery();


  echo '<div class="nectar-flickity ' . $flickity_masonry_class . ' not-initialized" data-controls="'. esc_attr($flickity_controls_style) .'"><div class="flickity-viewport"> <div class="flickity-slider">';
  
  foreach ( $gallery_ids as $image_id ) {
    
    if ( 'large_featured' === $masonry_item_sizing && ! is_single() ) {
      echo '<div class="cell"><a href="' . esc_url( get_permalink() ) . '">' . wp_get_attachment_image( $image_id, $img_size, false, $image_attrs ) . '</a></div>';
    } else {
      echo '<div class="cell">' . wp_get_attachment_image( $image_id, $img_size, false, $image_attrs ) . '</div>';
    }
  }
  
  echo '</div></div></div>';

} 

// Default to featured image.
else {
  
  $image_attrs = array(
    'title' => '',
    'sizes' => '(min-width: 1600px) 20vw, (min-width: 1300px) 25vw, (min-width: 1000px) 33.3vw, (min-width: 690px) 50vw, 100vw',
  );
  if( ! is_single() ) {
    echo '<a href="' . esc_url( get_permalink() ) . '" class="img-link">';
  }
  echo '<span class="post-featured-img">' . get_the_post_thumbnail( $post->ID, 'large', $image_attrs ) . '</span>';
  if( ! is_single() ) {
    echo '</a>';
  }
  
}