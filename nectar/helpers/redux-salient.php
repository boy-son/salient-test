<?php
/**
 * Redux theme options Salient helpers
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Load redux and options.
$using_nectar_redux_framework = false;

if ( ! class_exists( 'ReduxFramework' ) && file_exists( NECTAR_THEME_DIRECTORY . '/nectar/redux-framework/ReduxCore/framework.php' ) ) {
	require_once NECTAR_THEME_DIRECTORY . '/nectar/redux-framework/ReduxCore/framework.php';
	$using_nectar_redux_framework = true;
}
if ( ! isset( $redux_demo ) && file_exists( NECTAR_THEME_DIRECTORY . '/nectar/redux-framework/options-config.php' ) ) {
	require_once NECTAR_THEME_DIRECTORY . '/nectar/redux-framework/options-config.php';
}



/**
 * Add nectar redux styling/custom deps.
 *
 * @since 5.0
 */
function nectar_redux_deps( $hook_suffix ) {
	
	global $using_nectar_redux_framework;
	if ( strstr( $hook_suffix, 'Salient' ) || strstr( $hook_suffix, 'salient' ) ) {

		wp_enqueue_style( 'nectar_redux_admin_style', get_template_directory_uri() . '/nectar/redux-framework/ReduxCore/assets/css/salient-redux-styling.css', array(), '16.0', 'all' );

		if ( $using_nectar_redux_framework === false ) {
			wp_enqueue_style( 'nectar_redux_select_2', get_template_directory_uri() . '/nectar/redux-framework/extensions/vendor_support/vendor/select2/select2.css', array(), time(), 'all' );
			wp_enqueue_script( 'nectar_redux_ace', get_template_directory_uri() . '/nectar/redux-framework/extensions/vendor_support/vendor/ace_editor/ace.js', array(), time(), 'all' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'nectar_redux_deps' );



/**
 * Removes the redux demo.
 *
 * @since 5.0
 */
function nectar_removeDemoModeLink() {
	if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
	}
}



if ( is_admin() ) {

	add_action( 'init', 'nectar_removeDemoModeLink' );
	add_action( 'admin_menu', 'nectar_remove_redux_menu', 12 );
	
	function nectar_remove_redux_menu() {
		remove_submenu_page( 'tools.php', 'redux-about' );
	}
	
	/**
	 * Adds lovelo font to admin for live typography preview.
	 *
	 * @since 3.0
	 */
	if ( ! function_exists( 'nectar_admin_lovelo_font' ) ) {
		function nectar_admin_lovelo_font() {
			
			if( isset($_GET['page']) && $_GET['page'] === 'Salient' || isset($_GET['page']) && $_GET['page'] === 'salient' ) {
				echo "
				<!-- A font fabric font - http://fontfabric.com/lovelo-font/ -->
				<style> @font-face { font-family: 'Lovelo'; src: url('" . get_template_directory_uri() . "/css/fonts/Lovelo_Black.eot'); src: url('" . get_template_directory_uri() . "/css/fonts/Lovelo_Black.eot?#iefix') format('embedded-opentype'), url('" . get_template_directory_uri() . "/css/fonts/Lovelo_Black.woff') format('woff'),  url('" . get_template_directory_uri() . "/css/fonts/Lovelo_Black.ttf') format('truetype'), url('" . get_template_directory_uri() . "/css/fonts/Lovelo_Black.svg#loveloblack') format('svg'); font-weight: normal; font-style: normal; } </style>";
			}
			
		}
	}
	add_action( 'admin_head', 'nectar_admin_lovelo_font' );

}

