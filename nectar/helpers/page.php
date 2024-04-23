<?php
/**
 * Page related helpers
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 9.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * @deprecated since 10.5
 */
if ( ! function_exists( 'nectar_current_page_url' ) ) {
	function nectar_current_page_url() {
		return '';
	}
}


$nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

/**
 * Grab all fullscreen page row related options.
 *
 * @since 6.0
 */
function nectar_get_full_page_options() {

	global $post;

	$page_full_screen_rows                  = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows', true ) : '';
	$page_full_screen_rows_animation        = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_animation', true ) : '';
	$page_full_screen_rows_animation_speed  = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_animation_speed', true ) : '';
	$page_full_screen_rows_anchors          = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_anchors', true ) : '';
	$page_full_screen_rows_dot_navigation   = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_dot_navigation', true ) : '';
	$page_full_screen_rows_footer           = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_footer', true ) : '';
	$page_full_screen_rows_content_overflow = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_content_overflow', true ) : '';
	$page_full_screen_rows_bg_img_animation = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_row_bg_animation', true ) : '';
	$page_full_screen_rows_mobile_disable   = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows_mobile_disable', true ) : '';

	global $nectar_using_VC_front_end_editor;
	// On front end editor certain values are forced.
	if($nectar_using_VC_front_end_editor) {
		$page_full_screen_rows_animation = 'none';
		$page_full_screen_rows_dot_navigation = 'tooltip_alt';
		$page_full_screen_rows_footer = 'none';
	}

	$nectar_full_page_options = array(
		'page_full_screen_rows'                  => $page_full_screen_rows,
		'page_full_screen_rows_animation'        => $page_full_screen_rows_animation,
		'page_full_screen_rows_animation_speed'  => $page_full_screen_rows_animation_speed,
		'page_full_screen_rows_anchors'          => $page_full_screen_rows_anchors,
		'page_full_screen_rows_dot_navigation'   => $page_full_screen_rows_dot_navigation,
		'page_full_screen_rows_footer'           => $page_full_screen_rows_footer,
		'page_full_screen_rows_content_overflow' => $page_full_screen_rows_content_overflow,
		'page_full_screen_rows_bg_img_animation' => $page_full_screen_rows_bg_img_animation,
		'page_full_screen_rows_mobile_disable'   => $page_full_screen_rows_mobile_disable,
	);

	return $nectar_full_page_options;
}



/**
 * Adds a body class when using the page fullscreen rows option.
 *
 * @since 7.0
 */
function nectar_add_pfsr_bodyclass(){

		$post_id = (int) vc_get_param( 'vc_post_id' );

		$page_full_screen_rows = (isset($post_id)) ? get_post_meta($post_id, '_nectar_full_screen_rows', true) : '';
		if($page_full_screen_rows === 'on') {
			add_filter( 'body_class','nectar_using_pfsr_editor_class' );
		}
}

/**
 * Adds a body class when using the page fullscreen rows option on the front end editor.
 *
 * @since 10.0
 */
function nectar_using_pfsr_editor_class( $classes ) {

	 	$classes[] = 'nectar_using_pfsr';
		$classes[] = 'nectar_pfsr_compose_mode';
		$classes[] = 'nectar-no-flex-height';
    return $classes;
}

/**
 * Adds a body class when using the page fullscreen rows option.
 *
 * @since 10.0
 */
function nectar_using_pfsr_class( $classes ) {

		global $post;

		if( !$post ) {
			return $classes;
		}

		$page_full_screen_rows = (isset($post->ID)) ? get_post_meta($post->ID, '_nectar_full_screen_rows', true) : '';
		if( $page_full_screen_rows === 'on' ) {
				$classes[] = 'nectar_using_pfsr';
				$classes[] = 'nectar-no-flex-height';
		}

		$nectar_box_roll = (isset($post->ID)) ? get_post_meta($post->ID, '_nectar_header_box_roll', true) : '';
		if( $nectar_box_roll === 'on' ) {
				$classes[] = 'nectar_box_roll';
		}

    return $classes;
}


if( $nectar_using_VC_front_end_editor ) {
	nectar_add_pfsr_bodyclass();
} else {
	add_filter( 'body_class','nectar_using_pfsr_class' );
}
