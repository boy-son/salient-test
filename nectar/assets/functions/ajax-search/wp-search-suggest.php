<?php
/**
 * AJAX search logic
 *
 * @package Salient WordPress Theme
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'nectar_autocomplete_init' );
function nectar_autocomplete_init() {
	// Register our jQuery UI style and our custom javascript file.
	wp_register_script( 'my_acsearch', get_template_directory_uri() . '/nectar/assets/functions/ajax-search/wpss-search-suggest.js', array( 'jquery', 'jquery-ui-autocomplete' ), null, true );
	wp_localize_script( 'my_acsearch', 'MyAcSearch', array( 'url' => esc_url( admin_url( 'admin-ajax.php' ) ) ) );

	// Function to fire whenever search form is displayed.
	if ( ! is_admin() ) {
		nectar_autocomplete_search_form();
	}

	// Functions to deal with the AJAX request - one for logged in users, the other for non-logged in users.
	add_action( 'wp_ajax_myprefix_autocompletesearch', 'nectar_autocomplete_suggestions' );
	add_action( 'wp_ajax_nopriv_myprefix_autocompletesearch', 'nectar_autocomplete_suggestions' );
}

function nectar_autocomplete_search_form() {
	wp_enqueue_script( 'my_acsearch' );
}


function nectar_autocomplete_suggestions() {

	$search_term = sanitize_text_field( $_REQUEST['term'] );
	$search_term = apply_filters( 'get_search_query', $search_term );

	$nectar_options  = get_nectar_theme_options();
	$show_postsnum   = ( ! empty( $nectar_options['theme-skin'] ) && $nectar_options['theme-skin'] == 'ascend' ) ? 3 : 6;

	$post_types_list = array('post','product','portfolio');
	$post_type       = 'any';
	if( isset($nectar_options['header-search-limit']) && in_array($nectar_options['header-search-limit'], $post_types_list) ) {
		$post_type = esc_attr($nectar_options['header-search-limit']);
	}

	$search_array = array(
		's'                => $search_term,
		'showposts'        => $show_postsnum,
		'post_type'        => $post_type,
		'post_status'      => 'publish',
		'post_password'    => '',
		'suppress_filters' => true,
	);

	$query = http_build_query( $search_array );

	$posts = get_posts( $query );

	// Initialize suggestions array.
	$suggestions = array();

	global $post;
	foreach ( $posts as $post ) :
		setup_postdata( $post );

		$suggestion           = array();
		$suggestion['label']  = esc_html( $post->post_title );
		$suggestion['link']   = esc_url( get_permalink() );
		$suggestion['image']  = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'title' => '' ) ) : '<i class="icon-salient-pencil"></i>';
		$suggestion['target'] = '_self';

		if ( get_post_type( $post->ID ) == 'post' ) {

			if( get_post_format() === 'link' ) {

				$post_link_url  = get_post_meta( $post->ID, '_nectar_link', true );
				$post_link_text = get_the_content();

				if ( empty($post_link_text) && !empty($post_link_url) ) {
					$post_perma = esc_url($post_link_url);

					$suggestion['link'] = esc_url( $post_perma );
					$suggestion['target'] = '_blank';
				}

			}

			$suggestion['post_type'] = esc_html__( 'Blog Post', 'salient' );

		} elseif ( get_post_type( $post->ID ) == 'page' ) {

			$suggestion['post_type'] = esc_html__( 'Page', 'salient' );

		} elseif ( get_post_type( $post->ID ) == 'portfolio' ) {

			$suggestion['post_type'] = esc_html__( 'Portfolio Item', 'salient' );

			// Show custom thumbnail if in use.
			$custom_thumbnail = get_post_meta( $post->ID, '_nectar_portfolio_custom_thumbnail', true );
			if ( ! empty( $custom_thumbnail ) ) {
				$attachment_id       = pn_get_attachment_id_from_url( $custom_thumbnail );
				$suggestion['image'] = wp_get_attachment_image( $attachment_id, 'portfolio-widget' );
			}

			// Ext project URL.
			$custom_project_link = get_post_meta($post->ID, '_nectar_external_project_url', true);
			if( !empty($custom_project_link) ) {
				$suggestion['link'] = esc_url( $custom_project_link );
			}

		} elseif ( get_post_type( $post->ID ) == 'product' ) {

			$suggestion['post_type'] = esc_html__( 'Product', 'salient' );
		}

		// Add suggestion to suggestions array.
		$suggestions[] = $suggestion;
		endforeach;

	// JSON encode and echo.
	echo htmlentities( sanitize_text_field($_GET['callback']), ENT_QUOTES, 'UTF-8' ) . '(' . wp_json_encode( $suggestions ) . ')';

	exit;
}
