<?php
/**
 * Post featured image
 *
 * Used when "Classic Enhanced" masonry style is selected.
 *
 * @version 11.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $nectar_options;


$masonry_size_pm     = get_post_meta( $post->ID, '_post_item_masonry_sizing', true );
$masonry_item_sizing = ( ! empty( $masonry_size_pm ) ) ? $masonry_size_pm : 'regular';
$img_size            = ( ! empty( $masonry_item_sizing ) && 'regular' === $masonry_item_sizing ) ? 'portfolio-thumb' : 'full';

$image_attrs = array(
  'title' => '',
  'sizes' => '(min-width: 1600px) 20vw, (min-width: 1300px) 25vw, (min-width: 1000px) 33.3vw, (min-width: 690px) 50vw, 100vw',
);

$nectar_post_format = get_post_format();

if( !empty($nectar_options['blog_lazy_load']) && '1' === $nectar_options['blog_lazy_load'] && 'portfolio-thumb' === $img_size && NectarLazyImages::activate_lazy() ) {
  
  // src.
  $img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), $img_size );
  
  // srcset.
  $img_srcset = '';
  if (function_exists('wp_get_attachment_image_srcset')) {
    $img_srcset = wp_get_attachment_image_srcset(get_post_thumbnail_id(), $img_size);
  }
  
  // alt.
  $alt_tag = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
  
  // dimensions.
  $width  = '600';
  $height = '403';
  
  echo '<a class="img-link" href="' . esc_url( get_permalink() ) . '"><span class="post-featured-img">';
  if( 'video' === $nectar_post_format || 'audio' === $nectar_post_format ) {
    if( 'wide_tall' !== $masonry_item_sizing ) {
      get_template_part( 'includes/partials/blog/media/play-button' );
    }
  }
  if( has_post_thumbnail() ) { 
    echo '<img class="nectar-lazy skip-lazy wp-post-image" alt="'.esc_attr($alt_tag).'" height="'.esc_attr($height).'" width="'.esc_attr($width).'" data-nectar-img-src="'.esc_attr($img_src[0]).'" data-nectar-img-srcset="'.esc_attr($img_srcset).'" sizes="'.esc_attr($image_attrs['sizes']).'" />';
  }
  echo '</span></a>';
  
} else {
  echo '<a href="' . esc_url( get_permalink() ) . '" class="img-link"><span class="post-featured-img">';
  if( 'video' === $nectar_post_format || 'audio' === $nectar_post_format ) {
    if( 'wide_tall' !== $masonry_item_sizing ) {
      get_template_part( 'includes/partials/blog/media/play-button' );
    }
  }
  echo get_the_post_thumbnail( $post->ID, $img_size, $image_attrs ) . '</span></a>';
  
}

