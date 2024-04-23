<?php
/**
 * Salient admin enqueue
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 12.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Enqueue admin core media scripts
 *
 * @since 1.0
 */
if ( ! function_exists( 'nectar_enqueue_media' ) ) {

	function nectar_enqueue_media() {

		// enqueue the correct media scripts for the media library
		if ( floatval( get_bloginfo( 'version' ) ) < '3.5' ) {
			wp_enqueue_script(
				'redux-opts-field-upload-js',
				get_template_directory_uri() . '/nectar/redux-framework/ReduxCore/inc/fields/upload/field_upload_3_4.js',
				array( 'jquery', 'thickbox', 'media-upload' ),
				'8.5.4',
				true
			);
			wp_enqueue_style( 'thickbox' );
		}

	}
}


/**
 * Enqueue admin css
 *
 * @since 1.0
 */
function nectar_metabox_styles() {
	
	$nectar_theme_version = nectar_get_theme_version();
	
	wp_enqueue_style( 'nectar_meta_css', NECTAR_FRAMEWORK_DIRECTORY . 'assets/css/nectar_meta.css', '', $nectar_theme_version );
}


/**
 * Enqueue admin scripts
 *
 * @since 1.0
 */
function nectar_metabox_scripts() {
	
	global $nectar_options;

	$nectar_theme_version = nectar_get_theme_version();
	
	wp_register_script( 'nectar-upload', NECTAR_FRAMEWORK_DIRECTORY . 'assets/js/nectar-meta.js', array( 'jquery' ), $nectar_theme_version );
	wp_enqueue_script( 'nectar-upload' );
	wp_localize_script( 'redux-opts-field-upload-js', 'redux_upload', array( 'url' => get_template_directory_uri() . '/nectar/redux-framework/ReduxCore/inc/fields/upload/blank.png' ) );


		wp_enqueue_style( 'wp-color-picker' );


		wp_enqueue_script(
			'nectar-add-media',
			NECTAR_FRAMEWORK_DIRECTORY . 'assets/js/add-media.js',
			array( 'jquery' ),
			'10.1',
			true
		);

		wp_enqueue_script(
			'nectar-colorpicker-js',
			NECTAR_FRAMEWORK_DIRECTORY . 'assets/js/colorpicker.js',
			array( 'jquery','wp-color-picker' ),
			'15.1',
			true
		);

		$available_colors = NectarThemeManager::$available_theme_colors;
		$formatted_available_colors = array();
		$index = 0;
		foreach( $available_colors as $color_key => $label) {

			if($index > 4) {
				return;
			}

			if( isset($nectar_options[$color_key]) && !empty($nectar_options[$color_key]) ) {
				$formatted_available_colors[] = array(
					'key' => $color_key,
					'value' => $nectar_options[$color_key]
				);
				$index++;
			}

		}

		wp_localize_script(
			'nectar-colorpicker-js',
			'nectar_theme_colors', 
			$formatted_available_colors
		);	

		 wp_enqueue_media();


}

add_action( 'admin_enqueue_scripts', 'nectar_metabox_scripts' );
add_action( 'admin_print_styles', 'nectar_metabox_styles' );
add_action( 'admin_print_styles', 'nectar_enqueue_media' );
