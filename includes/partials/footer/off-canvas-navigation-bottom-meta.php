<?php
/**
* Off canvas navigation bottom meta area
*
* @package Salient WordPress Theme
* @subpackage Partials
* @version 11.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

$header_format             = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
$side_widget_class         = ( ! empty( $nectar_options['header-slide-out-widget-area-style'] ) ) ? $nectar_options['header-slide-out-widget-area-style'] : 'slide-out-from-right';
$side_widget_area          = ( ! empty( $nectar_options['header-slide-out-widget-area'] ) ) ? $nectar_options['header-slide-out-widget-area'] : 'off';
$user_set_side_widget_area = $side_widget_area;


if ( $header_format === 'centered-menu-under-logo' ) {
	if ( $side_widget_class === 'slide-out-from-right-hover' && $user_set_side_widget_area === '1' ) {
		$side_widget_class = 'slide-out-from-right';
	}
}


echo '<div class="bottom-meta-wrap">';

nectar_hook_ocm_bottom_meta();

if ( $side_widget_class === 'slide-out-from-right-hover' ) {
  if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Off Canvas Menu' ) ) :
    elseif ( ! has_nav_menu( 'off_canvas_nav' ) && $user_set_side_widget_area != 'off' ) :
      ?>
      
      <div class="widget">			
        
      </div>
      <?php
    endif;
    
  }
  
  global $using_secondary;
  // Social icons.
  if ( ! empty( $nectar_options['header-slide-out-widget-area-social'] ) && $nectar_options['header-slide-out-widget-area-social'] === '1' ) {
    nectar_ocm_add_social();
  } 
  elseif ( ! empty( $nectar_options['enable_social_in_header'] ) && 
  $nectar_options['enable_social_in_header'] === '1' && 
  $using_secondary != 'header_with_secondary' ) {
    
    echo '<ul class="off-canvas-social-links mobile-only">';
    nectar_header_social_icons( 'off-canvas' );
    echo '</ul>';
  }
  
  // Bottom text.
  if ( ! empty( $nectar_options['header-slide-out-widget-area-bottom-text'] ) ) {
    $desktop_social = ( ! empty( $nectar_options['enable_social_in_header'] ) && $nectar_options['enable_social_in_header'] === '1' ) ? 'false' : 'true';
    echo '<p class="bottom-text" data-has-desktop-social="' . esc_attr( $desktop_social ) . '">' . wp_kses_post( $nectar_options['header-slide-out-widget-area-bottom-text'] ) . '</p>';
  }
  
  echo '</div><!--/bottom-meta-wrap-->';