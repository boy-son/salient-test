<?php
/**
* Post bottom meta partial
*
* Used when "Featured Image Left" standard style is selected.
*
* @version 12.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

$date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';

if( 'last_editied_date' === $date_functionality ) {
  $date = get_the_modified_date();
} else {
  $date = get_the_date();
}

if ( function_exists( 'get_avatar' ) ) {
  echo '<div class="grav-wrap"><a href="' . get_author_posts_url( $post->post_author ) . '">' . get_avatar( get_the_author_meta( 'email' ), 70, null, get_the_author() ) . '</a>';
  echo '<div class="text"><a href="' . get_author_posts_url( $post->post_author ) . '" rel="author">' . get_the_author() . '</a>';
  echo '<span>' . esc_html($date) . '</span></div></div>'; 
}