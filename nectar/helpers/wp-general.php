<?php
/**
 * General setup functions
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add theme support.
 *
 * @since 2.0
 */
function nectar_add_theme_support() {

	add_theme_support( 'post-formats', array( 'quote', 'video', 'audio', 'gallery', 'link' ) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );

	// Add custom editor style.
	add_editor_style( 'css/style-editor.css' );

}

add_action( 'after_setup_theme', 'nectar_add_theme_support' );


// Default WP video size.
global $content_width;
if ( ! isset( $content_width ) ) {
	$content_width = 1080;
}


/**
 * Site title.
 *
 * @since 7.0
 */
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function nectar_theme_slug_render_title() { ?>
			<title><?php wp_title( '|', true, 'right' ); ?></title>
			<?php
	}
		add_action( 'wp_head', 'nectar_theme_slug_render_title' );
}




/**
 * Add iFrame to allowed wp_kses_post tags
 *
 * @param string $tags Allowed tags, attributes, and/or entities.
 * @param string $context Context to judge allowed tags by. Allowed values are 'post',
 *
 * @return mixed
 */
function nectar_custom_wpkses_post_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
	}
	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'nectar_custom_wpkses_post_tags', 10, 2 );




/**
 * Remove the lazy load class use in Jetpack.
 * only called for specific elements which need the image
 * present to calculate correctly - masonry, isotope etc.
 *
 * @since 8.0
 */
if ( ! function_exists( 'nectar_remove_lazy_load_functionality' ) ) {
	function nectar_remove_lazy_load_functionality( $attr ) {
		$attr['class'] .= ' skip-lazy';
		return $attr;
	}
}



/**
 * Check for HTTPS
 *
 * @since 4.0
 */
$nectar_is_ssl = is_ssl();

if ( ! function_exists( 'nectar_ssl_check' ) ) {
	function nectar_ssl_check( $src ) {

		global $nectar_is_ssl;

		if ( strpos( $src, 'http://' ) !== false && $nectar_is_ssl == true ) {
			$converted_start = str_replace( 'http://', 'https://', $src );
			return $converted_start;
		} else {
			return $src;
		}
	}
}


/**
 * Verify theme option isset and is not empty
 *
 * @since 12.2.0
 */

if ( ! function_exists( 'nectar_option_isset' ) ) {
	function nectar_option_isset( $option ) {

		if( isset($option) && !empty($option) ) {
			return true;
		}

		return false;

	}
}




/**
* Helper to strip paragraph tags.
*
* @param string $content text to remove p tags from.
* @since 10.5
*/
function nectar_remove_p_tags( $content ) {

	$content = preg_replace('/<p[^>]*>[\s|&nbsp;]*<\/p>/', '', $content);
	return $content;
}


/**
* Determine the Salient grid system.
*
* @since 10.6
*/
function nectar_use_flexbox_grid() {

	if( class_exists( 'WPBakeryVisualComposerAbstract' ) &&
		defined( 'SALIENT_VC_ACTIVE' ) &&
		version_compare( WPB_VC_VERSION, '6.0.5', '>=' ) &&
		defined( 'SALIENT_CORE_VERSION' ) &&
		version_compare( SALIENT_CORE_VERSION, '1.2', '>=' ) ) {
		/* Salient provides a modern flexbox grid system as of v10.6 as long
		as the Salient core and Salient page builder plugins are up to date. */
		$nectar_modern_grid_compat = true;
	} else {
		$nectar_modern_grid_compat = false;
	}

	return $nectar_modern_grid_compat;

}


/**
 * Allow users to enable the old double 
 * mobile menu functionality via a child
 *
 * @since 13.0
 */
 if ( ! function_exists( 'nectar_legacy_mobile_double_menu' ) ) {
 	function nectar_legacy_mobile_double_menu() {
		return false;
	}
}
