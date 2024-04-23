<?php
/**
 * Post meta fields
 *
 * @package Salient WordPress Theme
 * @subpackage Partials
 * @version 15.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $nectar_options;

$date_functionality    = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';
$remove_post_date      = ( isset( $nectar_options['blog_remove_single_date'] ) ) ? $nectar_options['blog_remove_single_date'] : '0';
$remove_post_author    = ( isset( $nectar_options['blog_remove_single_author'] ) ) ? $nectar_options['blog_remove_single_author'] : '0';
$remove_comment_number = ( isset( $nectar_options['blog_remove_single_comment_number'] ) ) ? $nectar_options['blog_remove_single_comment_number'] : '0';
$remove_reading_dur    = ( isset( $nectar_options['blog_remove_single_reading_dur'] ) ) ? $nectar_options['blog_remove_single_reading_dur'] : '0';

$author_style          = ( isset( $nectar_options['blog_header_image_under_author_style'] ) ) ? $nectar_options['blog_header_image_under_author_style'] : 'default'; 

$date_markup = '';
$est_time_markup = '';

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();

    // date.
    if( '1' !== $remove_post_date ) {
 
      if( 'last_editied_date' === $date_functionality ) {
        $date_markup = '<span class="meta-date date updated">'.get_the_modified_date().'</span>';
      } else {
        $nectar_u_time 					= get_the_time('U');
        $nectar_u_modified_time = get_the_modified_time('U');
        if( $nectar_u_modified_time >= $nectar_u_time + 86400 ) {
          $date_markup = '<span class="meta-date date published">' . get_the_date() . '</span>';
          $date_markup .= '<span class="meta-date date updated rich-snippet-hidden">' . get_the_modified_time(__( 'F jS, Y' , 'salient' )) . '</span>';
        } else {
          $date_markup = '<span class="meta-date date updated">' . get_the_date() . '</span>';
        }
      }
    }

    // reading duration.
    if( '1' !== $remove_reading_dur ) {
      $est_time_markup = '<span class="meta-reading-time">'.nectar_estimated_reading_time(get_the_content()). ' '. esc_html__('min read','salient').'</span>';
    }


    // author.
    if( '1' !== $remove_post_author ) {
      echo '<span class="meta-author vcard author">';
      if ( function_exists( 'get_avatar' ) ) {
        echo get_avatar( get_the_author_meta( 'email' ), 40, null, get_the_author() ); 
      }
      echo '<span>';
      $label_class = ('large' === $author_style) ? ' nectar-inherit-label': '';
      echo '<span class="fn'.$label_class.'">' . get_the_author_posts_link() . '</span>';
      if( 'large' === $author_style ) {
        echo ' ' . $date_markup . $est_time_markup;
      }
      echo '</span></span>';
    }

    echo ( 'large' !== $author_style ) ? $date_markup . $est_time_markup : '';

    

endwhile;
endif;