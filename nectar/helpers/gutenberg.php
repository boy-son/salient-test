<?php
/**
 * Gutenberg helpers
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
 * Disable gutenberg editor on Salient CPTs
 *
 * @since 10.0
 */
function nectar_disable_gutenberg_on_cpts( $can_edit, $post_type ) {
	if ( $post_type === 'portfolio' || $post_type === 'nectar_slider' || $post_type === 'home_slider' ) {
		$can_edit = false;
	}
	return $can_edit;
}

add_filter( 'use_block_editor_for_post_type', 'nectar_disable_gutenberg_on_cpts', 10, 2 );
add_action( 'after_setup_theme', 'nectar_gutenberg_editor_fullwidth_support' );

add_action( 'enqueue_block_assets', 'nectar_block_editor_assets' );

function nectar_block_editor_assets() {

	if ( !is_admin() ) {
		return;
	}
	
	$nectar_options = get_nectar_theme_options();
	// Styles.
	$nectar_theme_version = nectar_get_theme_version();
	wp_enqueue_style( 'nectar-block-editor-styles', get_template_directory_uri() . '/css/build/style-editor.css', array(), $nectar_theme_version );
	
	// Container sizing.
	$ext_padding = isset($nectar_options['ext_responsive_padding']) ? $nectar_options['ext_responsive_padding'] : '60';
	$max_container_w = '900'; // fixed width for Salient
	// Nectarblocks context should utilize the theme's max container width.
	if ( defined('NECTAR_BLOCKS_VERSION') ) {
		$max_container_w = isset($nectar_options['max_container_width']) ? $nectar_options['max_container_width'] : '1400';
	}

	$editor_vars = 'html body, html body .editor-styles-wrapper {
		--wp--style--root--padding-left: '.esc_attr($ext_padding).'px;
      	--wp--style--root--padding-right: '.esc_attr($ext_padding).'px;
		--wp--style--global--content-size: '.esc_attr($max_container_w).'px;
		--wp--style--global--wide-size: '. ( intval($max_container_w) + 300 ).'px;
	}';

	// Colors.
	// $overall_bg_color = isset($nectar_options['overall-bg-color']) && !empty($nectar_options['overall-bg-color']) ? $nectar_options['overall-bg-color'] : '#ffffff';
	// $overall_font_color = isset($nectar_options['overall-font-color']) && !empty($nectar_options['overall-font-color']) ? $nectar_options['overall-font-color'] : '#000000';
	// $editor_vars .= 'html body, html body .editor-styles-wrapper {
	// 	--nectar-overall-bg-color: '.esc_attr($overall_bg_color).';
	// 	--nectar-overall-font-color: '.esc_attr($overall_font_color).';
	// }';

	wp_add_inline_style( 'nectar-block-editor-styles', $editor_vars );
}


/**
 * Declare Gutenberg support.
 *
 * @since 10.0
 */
function nectar_gutenberg_editor_fullwidth_support() {
	// Allows Salient to utilize theme.json as a hybrid theme correctly without FSE templates.
	remove_theme_support('block-templates');

	add_theme_support(
		'gutenberg',
		array( 'wide-images' => true )
	);
}
