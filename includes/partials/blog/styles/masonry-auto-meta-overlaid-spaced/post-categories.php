<?php
/**
* Post categories partial
*
* Used when "Auto Masonry: Meta Overlaid Spaced" masonry style is selected.
*
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;

echo '<span class="meta-category">';

$categories = get_the_category();

if ( ! empty( $categories ) ) {
  $output = null;
  foreach ( $categories as $category ) {
    $output .= '<a class="' . esc_attr( $category->slug ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
  }
  echo trim( $output ); // WPCS: XSS ok.
}

echo '</span>'; 