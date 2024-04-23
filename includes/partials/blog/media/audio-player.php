<?php
/**
 * Audio blog partial.
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

$audio_mp3 = get_post_meta( $post->ID, '_nectar_audio_mp3', true );
$audio_ogg = get_post_meta( $post->ID, '_nectar_audio_ogg', true );

if ( ! empty( $audio_ogg ) || ! empty( $audio_mp3 ) ) {
  
  echo '<div class="audio-wrap">';
  
  $audio_output = '[audio ';

  if ( ! empty( $audio_mp3 ) ) {
    $audio_output .= 'mp3="' . esc_url( $audio_mp3 ) . '" '; 
  }
  if ( ! empty( $audio_ogg ) ) {
    $audio_output .= 'ogg="' . esc_url( $audio_ogg ) . '"'; 
  }

  $audio_output .= ']';

  echo do_shortcode( $audio_output );
  
  echo '</div><!--/audio-wrap-->';
  
}

?>
