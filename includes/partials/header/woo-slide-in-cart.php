<?php
/**
 * WooCommerce slide in cart
 *
 * @package    Salient WordPress Theme
 * @subpackage Partials
 * @version 13.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $woocommerce;

$nectar_options = get_nectar_theme_options();
$nav_cart_style = ( ! empty( $nectar_options['ajax-cart-style'] ) ) ? $nectar_options['ajax-cart-style'] : 'default';

if ( $woocommerce && $nav_cart_style === 'slide_in' ) {

	echo '<div class="nectar-slide-in-cart style_'.esc_attr($nav_cart_style).'">';

	if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0' ) >= 0 ) {
		$instance_params = ( defined('ICL_SITEPRESS_VERSION') ) ? array('wpml_language' => 'all', 'title' => '') : array('title' => '');
		the_widget( 'WC_Widget_Cart', $instance_params );
	} else {
		the_widget( 'WooCommerce_Widget_Cart', 'title= ' );
	}

	echo '</div>';

}

else if ( $woocommerce && $nav_cart_style === 'slide_in_click' ) {

	$theme_skin = NectarThemeManager::$skin;

	if( 'material' === $theme_skin ) {
		$close_markup = '<span class="close-wrap"><span class="close-line close-line1"></span><span class="close-line close-line2"></span></span>';
	} else {
		$close_markup = '<span class="icon-salient-m-close"></span>';
	}

	echo '<div class="nectar-slide-in-cart style_'.esc_attr($nav_cart_style).'">';

	echo '<div class="inner"><div class="header"><span data-inherit-heading-family="h4">'. esc_html__('Cart', 'salient') .'</span><a href="#" class="close-cart"><span class="screen-reader-text">'.esc_html__('Close Cart','salient').'</span>'.$close_markup.'</a></div>';

	if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0' ) >= 0 ) {

		$instance_params = ( defined('ICL_SITEPRESS_VERSION') ) ? array('wpml_language' => 'all', 'title' => '') : array('title' => '');
		the_widget( 'WC_Widget_Cart', $instance_params );

	} else {
		the_widget( 'WooCommerce_Widget_Cart', 'title= ' );
	}

	echo '</div></div><div class="nectar-slide-in-cart-bg"></div>';


}
