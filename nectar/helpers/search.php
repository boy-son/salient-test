<?php
/**
 * Salient search related functions
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
 * AJAX search.
 *
 * @since 4.0
 */
if ( ! function_exists( 'nectar_add_ajax_to_search' ) ) {
	function nectar_add_ajax_to_search() {

		global $nectar_options;
		$nectar_theme_skin = NectarThemeManager::$skin;

		$ajax_search  = ( ! empty( $nectar_options['header-disable-ajax-search'] ) && $nectar_options['header-disable-ajax-search'] === '1' ) ? 'no' : 'yes';
		$headerSearch = ( ! empty( $nectar_options['header-disable-search'] ) && $nectar_options['header-disable-search'] === '1' ) ? 'false' : 'true';

		// Legacy
		if ( $ajax_search === 'yes' && $headerSearch !== 'false' && $nectar_theme_skin !== 'material' ) {
			get_template_part( 'nectar/assets/functions/ajax-search/wp-search-suggest' );
		}
		// Modern
		else if( $ajax_search === 'yes' && $headerSearch !== 'false' && $nectar_theme_skin === 'material' ) {
			include NECTAR_THEME_DIRECTORY . '/includes/class-nectar-quick-search.php';
		}

	}
}
nectar_add_ajax_to_search();
