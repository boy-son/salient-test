<?php
/**
 * Video blog partial.
 *
 * Outputs actual video player.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$video_embed  = get_post_meta( $post->ID, '_nectar_video_embed', true );
$video_m4v    = get_post_meta( $post->ID, '_nectar_video_m4v', true );
$video_ogv    = get_post_meta( $post->ID, '_nectar_video_ogv', true );
$video_poster = get_post_meta( $post->ID, '_nectar_video_poster', true );

if ( ! empty( $video_embed ) || ! empty( $video_m4v ) ) {

  // Video embed
  if ( ! empty( $video_embed ) ) {
    echo '<div class="video">' . do_shortcode( $video_embed ) . '</div>';
  }
  // Self hosted video
  else  {

    if ( ! empty( $video_m4v ) || ! empty( $video_ogv ) ) {

      $video_output = '[video ';

      if ( ! empty( $video_m4v ) ) {
        $video_output .= 'preload="auto" mp4="' . esc_url( $video_m4v ) . '" '; 
      }
      if ( ! empty( $video_ogv ) ) {
        $video_output .= 'ogv="' . esc_url( $video_ogv ) . '"'; 
      }

      if ( $video_poster ) {
        $video_output .= ' poster="' . esc_url( $video_poster ) . '" ';
      }

      $video_output .= ']';

      echo '<div class="video">' . do_shortcode( $video_output ) . '</div>';
      
    }
  }
}