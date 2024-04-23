<?php
/**
* Post featured image
*
* Used when "Minimal" standard style is selected.
*
* @version 11.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

// Featured image.
$image_attrs = array(
  'title' => ''
);

if( has_post_thumbnail() ) {
  // Lazy load.
  if( !empty($nectar_options['blog_lazy_load']) && '1' === $nectar_options['blog_lazy_load'] && NectarLazyImages::activate_lazy() ) {

    // src.
    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
    
    // srcset.
    $img_srcset = '';
    if (function_exists('wp_get_attachment_image_srcset')) {
      $img_srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id(), 'full');
    }
    
    // alt.
    $alt_tag = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
    
    // dimensions.
    $img_meta = wp_get_attachment_metadata(get_post_thumbnail_id());

    $width  = ( !empty($img_meta['width']) ) ? $img_meta['width'] : '100';
    $height = ( !empty($img_meta['height']) ) ? $img_meta['height'] : '100';
    
    $image_attrs = array(
      'title' => '',
      'sizes' => '(max-width: '.$width.'px) 100vw, '.$width.'px'
    );
    
    echo '<a href="' . esc_url( get_permalink() ) . '" aria-label="'.get_the_title().'"><span class="post-featured-img">';
    echo '<img class="nectar-lazy skip-lazy wp-post-image" alt="'.esc_attr($alt_tag).'" height="'.esc_attr($height).'" width="'.esc_attr($width).'" data-nectar-img-src="'.esc_attr($img_src[0]).'" data-nectar-img-srcset="'.esc_attr($img_srcset).'" sizes="'.esc_attr($image_attrs['sizes']).'" />';
    echo '</span></a>';
    
  } else {
    echo '<a href="' . esc_url( get_permalink() ) . '" aria-label="'.get_the_title().'"><span class="post-featured-img">' . get_the_post_thumbnail( $post->ID, 'full', $image_attrs ) . '</span></a>';
  }
}