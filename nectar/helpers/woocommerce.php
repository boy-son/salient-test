<?php
/**
 * Salient WooCommerce Integration.
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 13.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Admin notice for left over unneeded template files.
if ( class_exists( 'WooCommerce' ) && is_admin() && file_exists( NECTAR_THEME_DIRECTORY . '/woocommerce/cart/cart.php' ) ) {
	include NECTAR_THEME_DIRECTORY . '/nectar/woo/admin-notices.php';
}

// Declare theme support.
add_theme_support( 'woocommerce' );


$nectar_quick_view_in_use = 'false';

if ( class_exists( 'WooCommerce' ) ) {

	// Load product quickview class.
	$nectar_quick_view = ( ! empty( $nectar_options['product_quick_view'] ) && $nectar_options['product_quick_view'] === '1' ) ? true : false;

	if ( $nectar_quick_view ) {
		$nectar_quick_view_in_use = 'true';
		require_once NECTAR_THEME_DIRECTORY . '/nectar/woo/quick-view.php';
	}

	// Load cart class.
	require_once NECTAR_THEME_DIRECTORY . '/nectar/woo/cart.php';


}

$main_shop_layout      = ( ! empty( $nectar_options['main_shop_layout'] ) ) ? $nectar_options['main_shop_layout'] : 'no-sidebar';
$single_product_layout = ( ! empty( $nectar_options['single_product_layout'] ) ) ? $nectar_options['single_product_layout'] : 'no-sidebar';

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if( !function_exists('nectar_is_woo_archive') ) {
	function nectar_is_woo_archive() {
		if( class_exists( 'WooCommerce' ) ) {

			if( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
				return true;
			}

		}
		return false;
	}
}

// Needed to let WooCommerce know Salient has theme options for columns
if ( function_exists( 'is_customize_preview' ) ) {
	if ( $woocommerce && is_customize_preview() ) {
		add_filter( 'loop_shop_columns', 'nectar_shop_loop_columns' );
	}
}


// Layout/Structure modifications.
if ( !function_exists( 'nectar_shop_header_output' ) ) {
	function nectar_shop_header_output() {
		echo '<div class="nectar-shop-header">';
		do_action( 'nectar_shop_header_markup' );
		echo '</div>';
	}
}

if ( !function_exists( 'nectar_shop_wrapper_start' ) ) {
	function nectar_shop_wrapper_start() {
		echo '<div class="container-wrap" data-midnight="dark">';
		do_action('nectar_shop_after_container_wrap_open');
		echo '<div class="container main-content"><div class="row">';
		do_action('nectar_shop_above_loop');
	}
}

if ( !function_exists( 'nectar_shop_wrapper_end' ) ) {
	function nectar_shop_wrapper_end() {
		echo '</div></div>';
			nectar_hook_before_container_wrap_close();
		echo '</div>';
		do_action( 'nectar_shop_fixed_social' );
	}
}


if ( !function_exists( 'nectar_shop_wrapper_start_sidebar_left' ) ) {
	function nectar_shop_wrapper_start_sidebar_left() {

		echo '<div class="container-wrap" data-midnight="dark">';
			do_action('nectar_shop_after_container_wrap_open');
			echo '<div class="container main-content">';
				do_action('nectar_shop_above_loop');
				echo '<div class="row"><div id="sidebar" class="col span_3 col">';
				do_action('nectar_shop_sidebar_top');
				do_action('nectar_hook_sidebar_top');
				echo '<div class="inner">';
				if ( function_exists( 'dynamic_sidebar' ) ) {
					dynamic_sidebar( 'woocommerce-sidebar' );
				}
				do_action('nectar_hook_sidebar_bottom');
		echo '</div></div><div class="post-area col span_9 col_last">';
	}
}

if ( !function_exists( 'nectar_shop_wrapper_end_sidebar_left' ) ) {
	function nectar_shop_wrapper_end_sidebar_left() {
		echo '</div></div></div>';
		nectar_hook_before_container_wrap_close();
		echo '</div>';
		do_action( 'nectar_shop_fixed_social' );
	}
}

if ( !function_exists( 'nectar_shop_wrapper_start_sidebar_right' ) ) {
	function nectar_shop_wrapper_start_sidebar_right() {
		echo '<div class="container-wrap" data-midnight="dark">';
		do_action('nectar_shop_after_container_wrap_open');
		echo '<div class="container main-content">';
		do_action('nectar_shop_above_loop');
		echo '<div class="row"><div class="post-area col span_9">';
	}
}

if ( !function_exists( 'nectar_shop_wrapper_end_sidebar_right' ) ) {
	function nectar_shop_wrapper_end_sidebar_right() {
		echo '</div><div id="sidebar" class="col span_3 col_last">';
		do_action('nectar_shop_sidebar_top');
		echo '<div class="inner">';
		if ( function_exists( 'dynamic_sidebar' ) ) {
			dynamic_sidebar( 'woocommerce-sidebar' );
		}
		echo '</div></div></div></div>';
		nectar_hook_before_container_wrap_close();
		echo '</div>';
		do_action( 'nectar_shop_fixed_social' );
	}
}

if ( !function_exists( 'nectar_shop_wrapper_start_fullwidth' ) ) {
	function nectar_shop_wrapper_start_fullwidth() {

		echo '<div class="container-wrap" data-midnight="dark">';
		do_action('nectar_shop_after_container_wrap_open');
		echo '<div class="container main-content"><div class="row"><div class="full-width-content nectar-shop-outer">';
		do_action('nectar_shop_above_loop');
	}
}

if ( !function_exists( 'nectar_shop_wrapper_end_fullwidth' ) ) {
	function nectar_shop_wrapper_end_fullwidth() {
		echo '</div></div></div>';
			nectar_hook_before_container_wrap_close();
		echo '</div>';
	}
}


if ( ! function_exists( 'nectar_shop_loop_columns' ) ) {
	function nectar_shop_loop_columns() {
		return 3; // 3 products per row
	}
}

if ( ! function_exists( 'nectar_shop_loop_columns_std' ) ) {
	function nectar_shop_loop_columns_std() {
		return 4; // 4 products per row
	}
}


// Change header.
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_filter( 'woocommerce_show_page_title', '__return_false' );

// Modify Breadcrumbs.
add_filter( 'woocommerce_breadcrumb_defaults', 'nectar_change_breadcrumbs' );

if ( !function_exists( 'nectar_change_breadcrumbs' ) ) {
	function nectar_change_breadcrumbs( $defaults ) {

		return array(
          'delimiter'   => ' <i class="fa fa-angle-right"></i> ',
          'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
          'wrap_after'  => '</nav>',
          'before'      => '<span>',
          'after'       => '</span>',
          'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
      );

	}
}


if ( $woocommerce ) {

	if( !is_admin() ) {
		add_action( 'wp', 'nectar_woo_shop_markup' );
	}

	// alter gallery thumbnail width
	add_action( 'after_setup_theme', 'nectar_custom_gallery_thumb_woocommerce_theme_support' );

	if ( !function_exists( 'nectar_custom_gallery_thumb_woocommerce_theme_support' ) ) {
		function nectar_custom_gallery_thumb_woocommerce_theme_support() {
			add_theme_support(
				'woocommerce',
				array(
					'gallery_thumbnail_image_width' => 150,
				)
			);
		}
	}

}


/**
 * Alters the WooCommerce shop markup with
 * the Salient specific structure
 *
 * @since 5.0
 */
if ( !function_exists( 'nectar_woo_shop_markup' ) ) {

	function nectar_woo_shop_markup() {

		global $single_product_layout;
		global $main_shop_layout;
		global $woocommerce;
		global $nectar_options;

		if ( $woocommerce && ! is_product() ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		}

		// Account page markup wrap
		if( function_exists('is_account_page') && is_account_page() ) {
			add_action('nectar_hook_before_content','woocommerce_output_content_wrapper');
			add_action('nectar_hook_after_content','woocommerce_output_content_wrapper_end');
		}

		// Shop Page Header.
		if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {

			add_action( 'nectar_shop_above_loop', 'nectar_shop_header_output', 10 );

			$product_archive_header_size = ( isset($nectar_options['product_archive_header_size'] ) ) ? $nectar_options['product_archive_header_size'] : 'default';
			if( 'contained' === $product_archive_header_size ) {
				add_action( 'nectar_shop_after_container_wrap_open', 'salient_shop_header', 10 );
			}
			else {
				add_action( 'woocommerce_before_main_content', 'salient_shop_header', 10 );
			}


			$product_archive_description = ( isset($nectar_options['product_archive_category_description'] ) ) ? $nectar_options['product_archive_category_description'] : 'default';

			if ( ! function_exists( 'salient_shop_header' ) ) {
				function salient_shop_header() {
					global $woocommerce;
					// page header for main shop page
					if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
						nectar_page_header( wc_get_page_id( 'shop' ) );
					} else {
						nectar_page_header( woocommerce_get_page_id( 'shop' ) );
					}
				}
			}

			if ( ! function_exists( 'salient_woo_shop_title' ) ) {
				function salient_woo_shop_title() {
					echo '<h1 class="page-title">';
					woocommerce_page_title();
					echo '</h1>';
				}
			}

			if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
				$header_title    = get_post_meta( wc_get_page_id( 'shop' ), '_nectar_header_title', true );
				$header_bg_color = get_post_meta( wc_get_page_id( 'shop' ), '_nectar_header_bg_color', true );
				$header_bg_image = get_post_meta( wc_get_page_id( 'shop' ), '_nectar_header_bg', true );
			} else {
				$header_title    = get_post_meta( woocommerce_get_page_id( 'shop' ), '_nectar_header_title', true );
				$header_bg_color = get_post_meta( woocommerce_get_page_id( 'shop' ), '_nectar_header_bg_color', true );
				$header_bg_image = get_post_meta( woocommerce_get_page_id( 'shop' ), '_nectar_header_bg', true );
			}

			$using_cat_bg = false;

			if ( is_shop() ) {
				if ( empty( $header_bg_color ) && empty( $header_bg_image ) ) {
					add_action( 'nectar_shop_header_markup', 'salient_woo_shop_title', 10 );
				}
			}
			elseif ( is_product_category() ) {

				$cate          = get_queried_object();
				$t_id          = ( property_exists( $cate, 'term_id' ) ) ? $cate->term_id : '';
				$product_terms = get_option( "taxonomy_$t_id" );

				$using_cat_bg = ( ! empty( $product_terms['product_category_image'] ) ) ? true : false;

				if ( empty( $header_bg_color ) && empty( $header_bg_image ) && ! $using_cat_bg ) {
					add_action( 'nectar_shop_header_markup', 'salient_woo_shop_title', 10 );
				}

			}
			elseif ( is_product_tag() || is_product_taxonomy() ) {

				if ( empty( $header_bg_color ) && empty( $header_bg_image ) ) {
					add_action( 'nectar_shop_header_markup', 'salient_woo_shop_title', 10 );
				}

			}

			// Product Category Description Location.
			if( 'in_header' === $product_archive_description  ) {

				if( !empty( $header_bg_color ) || !empty( $header_bg_image ) || true === $using_cat_bg ) {
					remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description');
					remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description');

					if(!is_shop()) {
						add_filter('nectar_page_header_subtitle', 'nectar_shop_header_description_mod');
					}
				}

			}

			// Filter sidebar toggle
			$product_filter_trigger = NectarThemeManager::$woo_product_filters;

			if( false === $product_filter_trigger ) {
				add_action( 'nectar_shop_header_markup', 'woocommerce_catalog_ordering', 10 );
				add_action( 'nectar_shop_header_markup', 'woocommerce_result_count', 10 );
			}
			add_action( 'nectar_shop_header_markup', 'woocommerce_breadcrumb', 10 );

			if( true === $product_filter_trigger ) {

				add_action( 'nectar_shop_header_markup', 'nectar_shop_header_bottom_markup_output', 10 );

				add_action( 'nectar_shop_header_bottom_markup', 'nectar_product_filter_area_trigger', 10 );
				add_action( 'nectar_shop_header_bottom_secondary_markup', 'woocommerce_result_count', 10 );
				add_action( 'nectar_shop_header_bottom_secondary_markup', 'woocommerce_catalog_ordering', 10 );

				add_action( 'nectar_shop_sidebar_top', 'nectar_shop_sidebar_filter_area_meta' );

			}

			// Active filters.
			if( isset($nectar_options['product_show_filters']) &&
			    '1' === $nectar_options['product_show_filters']) {
				add_action( 'nectar_woocommerce_after_filter_trigger', 'nectar_product_active_filters', 10 );
			}


		} // end shop page header.


		// No sidebar: Product single.
		if ( is_product() && $single_product_layout != 'right-sidebar' && is_product() && $single_product_layout != 'left-sidebar' ) {
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
			add_action( 'woocommerce_before_main_content', 'nectar_shop_wrapper_start', 10 );
			add_action( 'woocommerce_after_main_content', 'nectar_shop_wrapper_end', 10 );

			add_filter( 'loop_shop_columns', 'nectar_shop_loop_columns_std' );
		}

		// No sidebar: Product archives.
		if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
			if ( $main_shop_layout != 'right-sidebar' && $main_shop_layout != 'left-sidebar' && $main_shop_layout != 'fullwidth' ) {
				remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
				add_action( 'woocommerce_before_main_content', 'nectar_shop_wrapper_start', 10 );
				add_action( 'woocommerce_after_main_content', 'nectar_shop_wrapper_end', 10 );

				add_filter( 'loop_shop_columns', 'nectar_shop_loop_columns_std' );
			}

			if ( $main_shop_layout === 'fullwidth' ) {
				add_filter( 'loop_shop_columns', 'nectar_shop_loop_columns_std' );
			}
		}


		if ( is_shop() || is_product_category() || is_product_tag() || is_product() || is_product_taxonomy() ) {

			$nectar_shop_layout = ( is_product() ) ? $single_product_layout : $main_shop_layout;

			// Right Sidebar.
			if ( $nectar_shop_layout === 'right-sidebar' ) {

				remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

				add_action( 'woocommerce_before_main_content', 'nectar_shop_wrapper_start_sidebar_right', 10 );
				add_action( 'woocommerce_after_main_content', 'nectar_shop_wrapper_end_sidebar_right', 10 );

				add_filter( 'loop_shop_columns', 'nectar_shop_loop_columns' );

			}

			// Left Sidebar.
			elseif ( $nectar_shop_layout === 'left-sidebar' ) {

				remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

				add_action( 'woocommerce_before_main_content', 'nectar_shop_wrapper_start_sidebar_left', 10 );
				add_action( 'woocommerce_after_main_content', 'nectar_shop_wrapper_end_sidebar_left', 10 );

				add_filter( 'loop_shop_columns', 'nectar_shop_loop_columns' );
			}
			// Fullwidth.
			elseif ( $nectar_shop_layout === 'fullwidth' ) {

				remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

				add_action( 'woocommerce_before_main_content', 'nectar_shop_wrapper_start_fullwidth', 10 );
				add_action( 'woocommerce_after_main_content', 'nectar_shop_wrapper_end_fullwidth', 10 );

			}

		} // end conditional to check if on a WooCommerce page.

	} // end nectar_woo_shop_markup;

}


// Archive Header Options.
if( !function_exists('nectar_woo_archive_mods') ) {
	function nectar_woo_archive_mods() {

		global $nectar_options;

		// Product Category Header Sizing.
		$product_archive_header_size = ( isset($nectar_options['product_archive_header_size'] ) ) ? $nectar_options['product_archive_header_size'] : 'default';

		if( 'contained' === $product_archive_header_size && true === nectar_is_woo_archive() ) {
			add_filter('nectar_page_header_wrap_class_name', 'nectar_shop_header_contained_class_mod');
			add_filter('nectar_activate_transparent_header', 'nectar_shop_header_contained_transparency_mod', 10);
		}

		add_filter('nectar_page_header_wrap_class_name', 'nectar_shop_header_alignment_class_mod');

	}
}

add_action('wp', 'nectar_woo_archive_mods', 10);


// Altering page header classes.
if( !function_exists('nectar_shop_header_contained_class_mod') ) {
	function nectar_shop_header_contained_class_mod( $classes ) {
		$classes[] = 'container';
		$classes[] = 'woo-archive-header';
		return $classes;
	}
}

if( !function_exists('nectar_shop_header_alignment_class_mod') ) {
	function nectar_shop_header_alignment_class_mod( $classes ) {

		global $nectar_options;

		$cate 					= get_queried_object();
		$t_id 					= (property_exists($cate, 'term_id')) ? $cate->term_id : '';
		$product_terms 	= get_option( "taxonomy_$t_id" );

		$product_archive_auto_height = ( isset($nectar_options['product_archive_header_auto_height'] ) ) ? $nectar_options['product_archive_header_auto_height'] : '0';

		if( '1' === $product_archive_auto_height ) {
			$content_align 	= (isset($product_terms['product_category_header_content_align'])) ? $product_terms['product_category_header_content_align'] : '';
			// Content align.
			if( !empty($content_align) ) {
				$classes[] = 'align-content-'.esc_attr($content_align);
			}

			$text_align 	= (isset($product_terms['product_category_header_text_align'])) ? $product_terms['product_category_header_text_align'] : '';
			// Text align.
			if( !empty($text_align) ) {
				$classes[] = 'align-text-'.esc_attr($text_align);
			}
		}

		return $classes;
	}
}


// Alters the page header transparency.
if( !function_exists('nectar_shop_header_contained_transparency_mod') ) {
	function nectar_shop_header_contained_transparency_mod( $bool ) {
		return false;
	}
}


// Alters the page header description.
if( !function_exists('nectar_shop_header_description_mod') ) {
	function nectar_shop_header_description_mod($text) {

		ob_start();
		woocommerce_taxonomy_archive_description();
		$content = ob_get_clean();

		return $content;
	}
}


// Bottom Header Markup.
if( !function_exists('nectar_shop_header_bottom_markup_output') ) {
	function nectar_shop_header_bottom_markup_output() {

		echo '<div class="nectar-shop-header-bottom"><div class="left-side">';
		do_action( 'nectar_shop_header_bottom_markup' );
		echo '</div><div class="right-side">';
		do_action( 'nectar_shop_header_bottom_secondary_markup' );
		echo '</div></div>';

	}
}

// Filter area.
if( !function_exists('nectar_product_filter_area_trigger') ) {
	function nectar_product_filter_area_trigger() {
		echo '<div class="nectar-shop-filters">
					<a href="#" class="nectar-shop-filter-trigger">
						<span class="toggle-icon">
							<span>
								<span class="top-line"></span>
								<span class="bottom-line"></span>
							</span>
						</span>
						<span class="text-wrap">
							<span class="dynamic">
								<span class="show">'.esc_html__('Show','salient').'</span>
								<span class="hide">'.esc_html__('Hide','salient').'</span>
							</span> '.esc_html__('Filters','salient').'</span>
					</a>';
					do_action('nectar_woocommerce_after_filter_trigger');
		echo '</div>';
	}
}

if( !function_exists('nectar_shop_sidebar_filter_area_meta') ) {
	function nectar_shop_sidebar_filter_area_meta() {
		echo '<div class="header">
			<h4>'.esc_html__('Filters','salient').'</h4>
			<div class="nectar-close-btn-wrap">';
			if( NectarThemeManager::$skin === 'material' ) {
				echo '<a href="#" class="nectar-close-btn small">
					<span class="screen-reader-text">'.esc_html__('Close Filters','salient').'</span>
					<span class="close-wrap">
						<span class="close-line close-line1"></span>
						<span class="close-line close-line2"></span>
					</span>
				</a>';
			} else {
				echo '<a href="#" class="nectar-close-btn small">
					<span class="screen-reader-text">'.esc_html__('Close Filters','salient').'</span>
					<span class="icon-salient-m-close"></span>
				</a>';
			}
			echo '</div>
		</div>';
	}
}

// Active filters.
if( !function_exists('nectar_product_active_filters') ) {

	function nectar_product_active_filters() {

		if( class_exists('WC_Widget_Layered_Nav_Filters') ) {

			echo '<div class="nectar-active-product-filters">';
			the_widget( 'WC_Widget_Layered_Nav_Filters' );
			echo '</div>';
		}
	}

}



// Custom gallery thumb size.
if ( $woocommerce ) {
	add_filter( 'woocommerce_gallery_thumbnail_size', 'nectar_woocommerce_gallery_thumbnail_size' );
}

if ( !function_exists( 'nectar_woocommerce_gallery_thumbnail_size' ) ) {
	function nectar_woocommerce_gallery_thumbnail_size() {
		return 'nectar_small_square';
	}
}


if ( $woocommerce && function_exists('nectar_remove_categories_count') ) {
	add_filter( 'woocommerce_layered_nav_count', 'nectar_remove_categories_count' );
}

add_filter( 'woocommerce_pagination_args', 'nectar_override_pagination_args' );
if ( !function_exists( 'nectar_override_pagination_args' ) ) {
	function nectar_override_pagination_args( $args ) {
		$args['prev_text'] = __( 'Previous', 'salient' );
		$args['next_text'] = __( 'Next', 'salient' );
		return $args;
	}
}

if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'nectar_add_to_cart_fragment' );
} else {
	add_filter( 'add_to_cart_fragments', 'nectar_add_to_cart_fragment' );
}


// Update the cart with ajax.
if ( !function_exists( 'nectar_add_to_cart_fragment' ) ) {
	function nectar_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		$fragments['a.cart-parent'] = ob_get_clean();
		return $fragments;
	}
}

// Change summary html markup to fit responsive.
if ( empty( $nectar_options['product_tab_position'] ) || $nectar_options['product_tab_position'] === 'in_sidebar' ) {
	add_action( 'woocommerce_before_single_product_summary', 'nectar_woocommerce_summary_div', 35 );
	add_action( 'woocommerce_after_single_product_summary', 'nectar_woocommerce_close_div', 4 );
}

if ( !function_exists( 'nectar_woocommerce_summary_div' ) ) {
	function nectar_woocommerce_summary_div() {
		echo "<div class='span_7 col col_last single-product-summary'>";
	}
}

if ( !function_exists( 'nectar_woocommerce_close_div' ) ) {
	function nectar_woocommerce_close_div() {
		echo '</div>';
	}
}

// Change tab position to be inside summary.
if ( empty( $nectar_options['product_tab_position'] ) || $nectar_options['product_tab_position'] === 'in_sidebar' ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 1 );
}

// Wrap single product image in an extra div.
add_action( 'woocommerce_before_single_product_summary', 'nectar_woocommerce_images_div', 8 );
add_action( 'woocommerce_before_single_product_summary', 'nectar_woocommerce_close_div', 29 );

if ( !function_exists( 'nectar_woocommerce_images_div' ) ) {
	function nectar_woocommerce_images_div() {
		echo "<div class='span_5 col single-product-main-image'>";
	}
}


// Display upsells and related products within dedicated div with different column and number of products.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

if ( !function_exists( 'woocommerce_output_related_products' ) ) {
	function woocommerce_output_related_products() {
		$output = '';

		global $nectar_options;
		$related_carousel = ( isset($nectar_options['single_product_related_upsell_carousel'] ) ) ? $nectar_options['single_product_related_upsell_carousel'] : '0';
		$products_per_page = ( '1' === $related_carousel && isset($nectar_options['single_product_related_upsell_carousel_number']) && !empty($nectar_options['single_product_related_upsell_carousel_number']) ) ? intval($nectar_options['single_product_related_upsell_carousel_number']) : 4;

		ob_start();
		woocommerce_related_products(
			array(
				'columns'        => 4,
				'posts_per_page' => $products_per_page,
			)
		);
		$content = ob_get_clean();
		if ( $content ) {
			$output .= $content; }

		if( $content ) {

			if( '1' === $related_carousel ) {
				echo '<div class="clear"></div>';
				echo '<div class="span_12 dark"><div class="woocommerce columns-4"><div class="nectar-woo-flickity related-upsell-carousel" data-autorotate="" data-controls="arrows-overlaid">';
				echo '<div class="nectar-woo-carousel-top"></div>' . $output;
				echo '</div></div></div>';
			}
			else {
				echo '<div class="clear"></div>' . $output;
			}

		}

	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 21 );

if ( !function_exists( 'woocommerce_output_upsells' ) ) {
	function woocommerce_output_upsells() {

		global $nectar_options;

		$upsell_carousel = ( isset($nectar_options['single_product_related_upsell_carousel'] ) ) ? $nectar_options['single_product_related_upsell_carousel'] : '0';

		$products_per_page = ( '1' === $upsell_carousel && isset($nectar_options['single_product_related_upsell_carousel_number']) && !empty($nectar_options['single_product_related_upsell_carousel_number']) ) ? intval($nectar_options['single_product_related_upsell_carousel_number']) : 4;

		if( '1' === $upsell_carousel ) {

			ob_start();
			woocommerce_upsell_display( $products_per_page, 4 );
			$content = ob_get_clean();

			if( !empty($content) ) {
				echo '<div class="span_12 dark"><div class="woocommerce columns-4"><div class="nectar-woo-flickity related-upsell-carousel" data-autorotate="" data-controls="arrows-overlaid">';
				echo '<div class="nectar-woo-carousel-top"></div>'. $content;
				echo '</div></div></div>';
			}

		}
		else {
			woocommerce_upsell_display( $products_per_page, 4 );
		}

	}
}


if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'nectar_woocommerce_header_add_to_cart_fragment' );
	add_filter( 'woocommerce_add_to_cart_fragments', 'nectar_mobile_woocommerce_header_add_to_cart_fragment' );

} else {
	add_filter( 'add_to_cart_fragments', 'nectar_woocommerce_header_add_to_cart_fragment' );
}

if ( !function_exists( 'nectar_woocommerce_header_add_to_cart_fragment' ) ) {
	function nectar_woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start(); ?>
		<a class="cart-contents" aria-label="<?php echo esc_html__('Cart', 'salient'); ?>" href="<?php echo wc_get_cart_url(); ?>"><div class="cart-icon-wrap"><i class="icon-salient-cart"></i> <div class="cart-wrap"><span><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?> </span></div> </div></a>
		<?php

		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

if ( !function_exists( 'nectar_mobile_woocommerce_header_add_to_cart_fragment' ) ) {
	function nectar_mobile_woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		global $nectar_options;

		ob_start();
		$nav_cart_style = ( isset( $nectar_options['ajax-cart-style'] ) ) ? $nectar_options['ajax-cart-style'] : 'default';
		$first_load_class = ( intval($woocommerce->cart->cart_contents_count) > 0 ) ? ' class="first-load"' : '';
		?>
		<a id="mobile-cart-link" <?php echo ''.$first_load_class; ?> aria-label="<?php echo esc_attr__('Cart','salient'); ?>" data-cart-style="<?php echo esc_attr($nav_cart_style); ?>" href="<?php echo wc_get_cart_url(); ?>"><i class="icon-salient-cart"></i><div class="cart-wrap"><span><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?> </span></div></a>
		<?php

		$fragments['a#mobile-cart-link'] = ob_get_clean();

		return $fragments;
	}
}


// Change how many products are displayed per page.
global $nectar_options;

$product_hover_alt_image      = ( ! empty( $nectar_options['product_hover_alt_image'] ) ) ? $nectar_options['product_hover_alt_image'] : 'off';
$nectar_woo_products_per_page = ( ! empty( $nectar_options['woo-products-per-page'] ) ) ? $nectar_options['woo-products-per-page'] : '12';

add_filter(
	'loop_shop_per_page',
	function( $cols ) {
		global $nectar_woo_products_per_page;
		return $nectar_woo_products_per_page;
	},
	20
);

// Change the position of add to cart.
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );


// Product Add to cart styles.
$product_add_to_cart_style = ( isset($nectar_options['product_add_to_cart_style']) ) ? $nectar_options['product_add_to_cart_style'] : 'default';
if( 'fullwidth_qty' === $product_add_to_cart_style ) {
	add_action('woocommerce_before_add_to_cart_button', 'nectar_add_to_cart_single_before', 40);
}

if( !function_exists('nectar_add_to_cart_single_before') ) {
	function nectar_add_to_cart_single_before() {
		echo '<span class="flex-break"></span>';
	}
}


// Product styles.
$product_style = ( ! empty( $nectar_options['product_style'] ) ) ? $nectar_options['product_style'] : 'classic';

if ( $product_style === 'classic' ) {

	add_action( 'woocommerce_before_shop_loop_item_title', 'nectar_product_thumbnail_with_cart', 10 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

} elseif ( $product_style === 'text_on_hover' ) {

	add_action( 'woocommerce_before_shop_loop_item_title', 'nectar_product_thumbnail_with_cart_alt', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 5 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 10 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
}
elseif ( $product_style === 'material' ) {

	add_action( 'woocommerce_before_shop_loop_item_title', 'nectar_product_thumbnail_material', 10 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
}
else {

	add_action( 'woocommerce_before_shop_loop_item_title', 'nectar_product_thumbnail_minimal', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	add_action( 'nectar_woo_minimal_price', 'woocommerce_template_loop_price', 5 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
}


// Add 3.0 gallery support when using default lightbox option in theme.
$nectar_product_gal_type = ( ! empty( $nectar_options['single_product_gallery_type'] ) ) ? $nectar_options['single_product_gallery_type'] : 'default';


add_theme_support( 'wc-product-gallery-lightbox' );

if( $nectar_product_gal_type !== 'two_column_images' ) {
	add_theme_support( 'wc-product-gallery-zoom' );
}
if ( $nectar_product_gal_type === 'default' ) {
	add_theme_support( 'wc-product-gallery-slider' );
}

// Gallery Styles.
$product_gallery_style = (isset($nectar_options['single_product_gallery_type'])) ? $nectar_options['single_product_gallery_type'] : 'default';

if( 'left_thumb_slider' === $product_gallery_style ||
    'left_thumb_sticky_fullwidth' === $product_gallery_style ) {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 2 );

}

// Add additional wrapping div.
add_action( 'woocommerce_before_single_product_summary', 'nectar_left_thumb_slider_wrap_start', 2 );
add_action( 'woocommerce_after_single_product_summary', 'nectar_woocommerce_close_div', 2 );

function nectar_left_thumb_slider_wrap_start() {
	echo '<div class="nectar-prod-wrap">';
}



if ( ! function_exists( 'nectar_product_thumbnail_with_cart' ) ) {

	function nectar_product_thumbnail_with_cart() {
		global $product;
		global $woocommerce;
		global $product_hover_alt_image;
		global $nectar_quick_view_in_use;
		?>
	   <div class="product-wrap">
			<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr($product->get_name()); ?>"><?php

							$product_second_image = null;
							if ( $product_hover_alt_image == '1' ) {

								if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
									$product_attach_ids = $product->get_gallery_image_ids();
								} else {
									$product_attach_ids = $product->get_gallery_attachment_ids();
								}

								if ( isset( $product_attach_ids[0] ) ) {
									$product_second_image = wp_get_attachment_image( $product_attach_ids[0], 'shop_catalog', false, array( 'class' => 'hover-gallery-image' ) );
								}
							}

							echo woocommerce_get_product_thumbnail() . $product_second_image;
							?></a>
			<?php
					echo '<div class="product-add-to-cart" data-nectar-quickview="' . esc_attr($nectar_quick_view_in_use) . '">';
				  	do_action( 'nectar_woocommerce_before_shop_loop_item_add_to_cart' );
						woocommerce_template_loop_add_to_cart();
						do_action( 'nectar_woocommerce_before_add_to_cart' );
					 echo '</div>';
			?>
		   </div>
		<?php
	}
}



if ( ! function_exists( 'nectar_product_thumbnail_material' ) ) {

	function nectar_product_thumbnail_material() {

			global $product;
			global $woocommerce;
			global $product_hover_alt_image;
		 	global $nectar_quick_view_in_use;
		?>

	   <div class="product-wrap">
			<?php

			$product_second_image = null;
			if ( $product_hover_alt_image == '1' ) {

				if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
					$product_attach_ids = $product->get_gallery_image_ids();
				} else {
					$product_attach_ids = $product->get_gallery_attachment_ids();
				}

				if ( isset( $product_attach_ids[0] ) ) {
					$product_second_image = wp_get_attachment_image( $product_attach_ids[0], 'shop_catalog', false, array( 'class' => 'hover-gallery-image' ) );
				}
			}

			echo '<a href="' . esc_url( get_permalink() ) . '" aria-label="'.$product->get_name().'">';
			echo woocommerce_get_product_thumbnail() . $product_second_image;
			echo '</a>';
			echo '<div class="product-meta">';
			echo '<a href="' . esc_url( get_permalink() ) . '">';
			do_action( 'nectar_woocommerce_before_shop_loop_item_title' );
			do_action( 'woocommerce_shop_loop_item_title' );
			echo '</a>';
			do_action( 'woocommerce_after_shop_loop_item_title' );

			echo '<div class="product-add-to-cart" data-nectar-quickview="' . esc_attr($nectar_quick_view_in_use) . '">';
			  do_action( 'nectar_woocommerce_before_shop_loop_item_add_to_cart' );
			  woocommerce_template_loop_add_to_cart();
				do_action( 'nectar_woocommerce_before_add_to_cart' );
			echo '</div></div>';
			?>
		   </div>
		<?php
	}
}



if ( ! function_exists( 'nectar_product_thumbnail_minimal' ) ) {

	function nectar_product_thumbnail_minimal() {

		global $product;
		global $woocommerce;
		global $product_hover_alt_image;
		global $nectar_quick_view_in_use;
		global $nectar_options;

		$product_minimal_hover_layout = ( isset( $nectar_options['product_minimal_hover_layout'] ) ) ? esc_html($nectar_options['product_minimal_hover_layout']) : 'default';
		?>
		 <div class="background-color-expand"></div>
	   <div class="product-wrap">
			<?php

			$product_second_image = null;
			$product_second_image_class = '';

			if ( $product_hover_alt_image == '1' ) {

				if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
					$product_attach_ids = $product->get_gallery_image_ids();
				} else {
					$product_attach_ids = $product->get_gallery_attachment_ids();
				}

				if ( isset( $product_attach_ids[0] ) ) {
					$product_second_image = wp_get_attachment_image( $product_attach_ids[0], 'shop_catalog', false, array( 'class' => 'hover-gallery-image' ) );
					if($product_second_image) {
						$product_second_image_class = ' has-hover-image';
					}

				}
			}

			// Flex Buttons.
			if( 'price_visible_flex_buttons' === $product_minimal_hover_layout ) {

				echo '
				<div class="product-image-wrap'.$product_second_image_class.'">
					<a href="' . esc_url( get_permalink() ) . '" aria-label="'.$product->get_name().'">';
					echo woocommerce_get_product_thumbnail() . $product_second_image;
				echo '</a>
					<div class="product-add-to-cart" data-nectar-quickview="' . esc_attr($nectar_quick_view_in_use) . '">';
					do_action( 'nectar_woocommerce_before_shop_loop_item_add_to_cart' );
					woocommerce_template_loop_add_to_cart();
					do_action( 'nectar_woocommerce_before_add_to_cart' );
				echo '</div></div>';

				echo '<div class="product-meta">';
					echo '<div class="product-main-meta">
						<a href="' . esc_url( get_permalink() ) . '">';
						do_action( 'nectar_woocommerce_before_shop_loop_item_title' );
						do_action( 'woocommerce_shop_loop_item_title' );
					echo '</a>';
						do_action( 'nectar_woo_minimal_price' );
					echo '</div>';
					do_action( 'woocommerce_after_shop_loop_item_title' );
				echo '</div>';

			}
			// Default Buttons.
			else {

				echo '<a href="' . esc_url( get_permalink() ) . '" aria-label="'.$product->get_name().'">';
					echo woocommerce_get_product_thumbnail() . $product_second_image;
				echo '</a>';

				echo '<div class="product-meta">';
				echo '<a href="' . esc_url( get_permalink() ) . '">';
					do_action( 'nectar_woocommerce_before_shop_loop_item_title' );
					do_action( 'woocommerce_shop_loop_item_title' );
				echo '</a>';

				do_action( 'woocommerce_after_shop_loop_item_title' );
				echo '<div class="price-hover-wrap">';
				do_action( 'nectar_woo_minimal_price' );

				echo '<div class="product-add-to-cart" data-nectar-quickview="' . esc_attr($nectar_quick_view_in_use) . '">';
				  do_action( 'nectar_woocommerce_before_shop_loop_item_add_to_cart' );
				  woocommerce_template_loop_add_to_cart();
					do_action( 'nectar_woocommerce_before_add_to_cart' );
				echo '</div>
				</div>
			</div>';

			}

			echo '</div>'; // end product-wrap
	}
}



if ( ! function_exists( 'nectar_product_thumbnail_with_cart_alt' ) ) {

	function nectar_product_thumbnail_with_cart_alt() {
		?>

	   <div class="product-wrap">
			<?php
			global $product;
			global $woocommerce;
			global $product_hover_alt_image;
			global $nectar_quick_view_in_use;

			$product_second_image = null;
			if ( $product_hover_alt_image == '1' ) {

				if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
					$product_attach_ids = $product->get_gallery_image_ids();
				} else {
					$product_attach_ids = $product->get_gallery_attachment_ids();
				}

				if ( isset( $product_attach_ids[0] ) ) {
					$product_second_image = wp_get_attachment_image( $product_attach_ids[0], 'shop_catalog', false, array( 'class' => 'hover-gallery-image' ) );
				}
			}

			echo woocommerce_get_product_thumbnail() . $product_second_image;
			?>

			   <div class="bg-overlay"></div>
			   <a href="<?php the_permalink(); ?>" class="link-overlay" aria-label="<?php echo esc_attr($product->get_name()); ?>"></a>
			   <div class="text-on-hover-wrap">
				<?php do_action( 'nectar_woocommerce_before_shop_loop_item_title' ); ?>
				<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
				<?php

				if ( $woocommerce && version_compare( $woocommerce->version, '3.0', '>=' ) ) {
					echo '<div class="categories">' . wc_get_product_category_list( $product->get_id() ) . '</div>';
				} else {
					echo '<div class="categories">' . $product->get_categories() . '</div>';
				}

				?>
			</div>

			<?php do_action( 'nectar_woocommerce_before_add_to_cart' ); ?>


		   </div>
		   <a href="<?php the_permalink(); ?>"><?php do_action( 'woocommerce_shop_loop_item_title' ); ?></a>
		<?php
		do_action( 'nectar_woocommerce_before_shop_loop_item_add_to_cart' );
		woocommerce_template_loop_add_to_cart();
	}
}


if ( !function_exists( 'nectar_header_cart_output' ) ) {

	function nectar_header_cart_output() {
		global $woocommerce;
		global $nectar_options;

		$header_format         = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
		$userSetSideWidgetArea = ( ! empty( $nectar_options['header-slide-out-widget-area'] ) && $header_format !== 'left-header' ) ? $nectar_options['header-slide-out-widget-area'] : 'off';

		ob_start();

		if ( $woocommerce ) {

				$nav_cart_style = ( ! empty( $nectar_options['ajax-cart-style'] ) ) ? $nectar_options['ajax-cart-style'] : 'default';
			?>

			<div class="cart-outer" data-user-set-ocm="<?php echo esc_attr( $userSetSideWidgetArea ); ?>" data-cart-style="<?php echo esc_attr( $nav_cart_style ); ?>">
				<div class="cart-menu-wrap">
					<div class="cart-menu">
						<a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>"><div class="cart-icon-wrap"><i class="icon-salient-cart" aria-hidden="true"></i> <div class="cart-wrap"><span><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?> </span></div> </div></a>
					</div>
				</div>

				<?php if( 'slide_in_click' !== $nav_cart_style ) { ?>
					<div class="cart-notification">
						<span class="item-name"></span> <?php echo esc_html__( 'was successfully added to your cart.', 'salient' ); ?>
					</div>
				<?php } ?>

				<?php
				if ( $nav_cart_style !== 'slide_in' && $nav_cart_style !== 'slide_in_click' ) {
					$instance_params = ( defined('ICL_SITEPRESS_VERSION') ) ? array('wpml_language' => 'all', 'title' => '') : array('title' => '');
					the_widget( 'WC_Widget_Cart', $instance_params );
				}
				?>

			</div>

			<?php
		}

		$captured_cart_content = ob_get_clean();
		return $captured_cart_content;

	}
}


// Single product price typography.
add_filter( 'woocommerce_product_price_class', 'nectar_single_product_price_class' );
if( !function_exists('nectar_single_product_price_class') ) {
	function nectar_single_product_price_class($class) {
		global $nectar_options;

		$inherit_typography = 'default';
		if( isset($nectar_options['product_price_typography'] ) &&
		    'default' !== $nectar_options['product_price_typography'] ) {
			$inherit_typography = $nectar_options['product_price_typography'];
		}

		return $class . ' nectar-inherit-'.esc_attr($inherit_typography);
	}
}

// Single Product Additional Information Tab.
if( isset($nectar_options['woo_hide_product_additional_info_tab']) && '1' === $nectar_options['woo_hide_product_additional_info_tab'] ) {
	add_filter( 'woocommerce_product_tabs', 'nectar_remove_additional_info_product_tab', 10 );
}

if( !function_exists('nectar_remove_additional_info_product_tab') ) {
	function nectar_remove_additional_info_product_tab( $tabs ) {
	    unset( $tabs['additional_information'] );
	    return $tabs;
	}
}


// Single Product Reviews Title.
add_filter('woocommerce_reviews_title','nectar_single_product_review_with_average', 9);

if( !function_exists('nectar_single_product_review_with_average') ) {
	function nectar_single_product_review_with_average($title) {

		global $product;
		global $nectar_options;

		$woo_review_style = ( isset($nectar_options['product_reviews_style']) && !empty($nectar_options['product_reviews_style']) ) ? $nectar_options['product_reviews_style'] : 'default';

		$count = $product->get_review_count();

		if( $count && wc_review_ratings_enabled() && 'off_canvas' === $woo_review_style && function_exists('wc_get_rating_html') ) {

			$average       = $product->get_average_rating();
			$reviews_title = sprintf( esc_html( _n( 'Based on %1$s review', 'Based on %1$s reviews', $count, 'salient' ) ), esc_html( $count ) );

			$nectar_title = '<div class="nectar-average-count-wrap">';
			$nectar_title .= '<span class="nectar-average-count">' . esc_html($average ) . '</span>';
			$nectar_title .= '<div>' . wc_get_rating_html( $average ) . '</div>';
			$nectar_title .= '<span class="total-num">' . $reviews_title .'</span>';
			$nectar_title .= '</div>';

      if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
			  $nectar_title .= '<a class="nectar-button large regular accent-color regular-button nectar-product-reviews-trigger" href="#" data-color-override="false" data-hover-color-override="false"><span>'.esc_html__('Write a Review','salient').'</span></a>';
      }
      
			return $nectar_title;

		}

		return $title;
	}
}

add_filter('nectar_woocommerce_no_reviews_title','nectar_single_product_review_empty', 10);

if( !function_exists('nectar_single_product_review_empty') ) {
	function nectar_single_product_review_empty($title) {

		global $product;
		global $nectar_options;

		$woo_review_style = ( isset($nectar_options['product_reviews_style']) && !empty($nectar_options['product_reviews_style']) ) ? $nectar_options['product_reviews_style'] : 'default';

		if( wc_review_ratings_enabled() && 'off_canvas' === $woo_review_style ) {

			if( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {

				$nectar_title = '<div class="nectar-no-reviews">';
				$nectar_title .= '<a class="nectar-button large regular accent-color regular-button nectar-product-reviews-trigger" href="#" data-color-override="false" data-hover-color-override="false"><span>'.esc_html__('Write a Review','salient').'</span></a></div>';

				return $title . $nectar_title;
			}
			

		}

		return $title;
	}
}



// Quantity buttons
add_action( 'woocommerce_before_quantity_input_field', 'nectar_quantity_markup_mod_before', 10 );
add_action( 'woocommerce_after_quantity_input_field', 'nectar_quantity_markup_mod_after', 10 );


if( !function_exists('nectar_quantity_markup_mod_before') ) {
	function nectar_quantity_markup_mod_before() {
		echo '<input type="button" value="-" class="minus" />';
	}
}

if( !function_exists('nectar_quantity_markup_mod_after') ) {
	function nectar_quantity_markup_mod_after() {
		echo '<input type="button" value="+" class="plus" />';
	}
}



if( !is_admin() ) {
	add_action( 'wp', 'nectar_woo_social_add' );
}

if ( !function_exists( 'nectar_woo_social_add' ) ) {
	function nectar_woo_social_add() {

		global $nectar_options;
		global $woocommerce;

		$social_style = get_option( 'salient_social_button_style','fixed' );

		if ( empty( $nectar_options['product_tab_position'] ) || $nectar_options['product_tab_position'] === 'in_sidebar' ) {

				if( $woocommerce && $social_style === 'fixed' && is_product() ) {
					add_action( 'nectar_shop_fixed_social', 'nectar_review_quickview', 10 );
				} else {
					add_action( 'woocommerce_after_add_to_cart_form', 'nectar_review_quickview', 10 );
				}

		} else {

			if( $woocommerce && $social_style === 'fixed' && is_product() ) {
				add_action( 'nectar_shop_fixed_social', 'nectar_review_quickview', 10 );
			} else {
				add_action( 'woocommerce_single_product_summary', 'nectar_review_quickview', 100 );
			}

			add_action( 'woocommerce_after_single_product_summary', 'nectar_woo_clearfix', 7 );

		}

	}

}


if ( !function_exists( 'nectar_woo_clearfix' ) ) {
	function nectar_woo_clearfix() {
		echo '<div class="after-product-summary-clear"></div>';
	}
}


if ( !function_exists( 'nectar_review_quickview' ) ) {

	function nectar_review_quickview() {
		global $product, $nectar_options, $post;

			// Social sharting icons
			if( function_exists('nectar_social_sharing_output') ) {
				$social_style = get_option( 'salient_social_button_style','fixed' );
				if( $social_style === 'fixed' ) {
					nectar_social_sharing_output('fixed');
				} else {
					nectar_social_sharing_output('hover');
				}
			}

	}
}

// Image sizes
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow === 'themes.php' ) {
	add_action( 'init', 'nectar_woocommerce_image_dimensions', 1 ); }

add_filter('woocommerce_review_gravatar_size','nectar_woo_review_grav_size');

// Gravatar
if( !function_exists('nectar_woo_review_grav_size') ) {
	function nectar_woo_review_grav_size($size) {
		return '80';
	}
}

// Define image sizes
if ( !function_exists( 'nectar_woocommerce_image_dimensions' ) ) {
	function nectar_woocommerce_image_dimensions() {
		$catalog = array(
			'width'  => '375',
			'height' => '400',
			'crop'   => 1,
		);

		$single = array(
			'width'  => '600',
			'height' => '630',
			'crop'   => 1,
		);

		$thumbnail = array(
			'width'  => '130',
			'height' => '130',
			'crop'   => 1,
		);

		update_option( 'shop_catalog_image_size', $catalog ); // Product category thumbs
		update_option( 'shop_single_image_size', $single ); // Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); // Image gallery thumbs
	}
}

if( isset( $nectar_options['single_product_custom_image_dimensions'] ) &&
    '1' === $nectar_options['single_product_custom_image_dimensions'] ) {

	add_filter( 'woocommerce_get_image_size_single', 'nectar_woocommerce_user_defined_single_image_size' );
	add_filter( 'woocommerce_get_image_size_shop_single', 'nectar_woocommerce_user_defined_single_image_size' );
	add_filter( 'woocommerce_get_image_size_woocommerce_single', 'nectar_woocommerce_user_defined_single_image_size' );
}

if( !function_exists('nectar_woocommerce_user_defined_single_image_size') ) {
	function nectar_woocommerce_user_defined_single_image_size() {

		global $nectar_options;

		$custom_gallery_width = 800;
		if( isset($nectar_options['single_product_gallery_image_dimensions']) &&
		    isset($nectar_options['single_product_gallery_image_dimensions']['width']) &&
				!empty($nectar_options['single_product_gallery_image_dimensions']['width']) ) {
			$custom_gallery_width = intval($nectar_options['single_product_gallery_image_dimensions']['width']);
		}

		$custom_gallery_height = 600;
		if( isset($nectar_options['single_product_gallery_image_dimensions']) &&
		    isset($nectar_options['single_product_gallery_image_dimensions']['height']) &&
				!empty($nectar_options['single_product_gallery_image_dimensions']['height']) ) {
			$custom_gallery_height = intval($nectar_options['single_product_gallery_image_dimensions']['height']);
		}

		$size = array(
				'width'  => $custom_gallery_width,
				'height' => $custom_gallery_height,
				'crop'   => 1
		);

		return $size;
	}
}



add_action('wp', 'nectar_check_product_lazy_loading', 10);

if( !function_exists('nectar_check_product_lazy_loading') ) {

	function nectar_check_product_lazy_loading() {

		global $nectar_options;

		if( !is_admin() && NectarLazyImages::activate_lazy() &&
		isset( $nectar_options['product_lazy_load_images'] ) &&
		!empty( $nectar_options['product_lazy_load_images'] ) &&
		'1' === $nectar_options['product_lazy_load_images'] ) {

			add_filter('wp_get_attachment_image_attributes','nectar_lazyload_woocommerce_imgs', 10, 5 );
			add_filter( 'wp_lazy_loading_enabled', '__return_false' );

		}

	}
}


if( !function_exists('nectar_lazyload_woocommerce_imgs') ) {

	function nectar_lazyload_woocommerce_imgs( $attr, $attachment, $size ) {

		global $post;

		if( class_exists( 'WooCommerce' ) &&
		isset($post->post_type) &&
		'product' === $post->post_type &&
		function_exists('in_the_loop') && in_the_loop() &&
		isset($attr['class']) ) {

			if( strpos($attr['class'],'woocommerce_thumbnail') ||
			strpos($attr['class'],'shop_single') ||
			strpos($attr['class'],'shop_thumbnail') ||
			strpos($attr['class'],'over-gallery-imag') ) {

				// Skip first on shop single.
				if( strpos($attr['class'],'shop_single') && 0 == NectarLazyImages::$woo_single_main_count ) {
					NectarLazyImages::$woo_single_main_count = 1;
					return $attr;
				}
				else if( strpos($attr['class'],'shop_thumbnail') && 0 == NectarLazyImages::$woo_single_thumb_count ) {
					NectarLazyImages::$woo_single_thumb_count = 1;
					return $attr;
				}

				// Alter srcset.
				if( isset($attr['srcset']) ) {
					$temp_srcset = $attr['srcset'];
					unset($attr['srcset']);
					$attr['data-nectar-img-srcset'] = $temp_srcset;
				}

				// Alter Src.
				if( isset($attr['src']) ) {
					$temp_src = $attr['src'];

					if( !strpos($attr['class'],'over-gallery-imag') ) {
						$dimensions = wp_get_attachment_image_src($attachment->ID, $size);
						if( $dimensions ) {
							$attr['src'] = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%20".$dimensions[1].'%20'.$dimensions[2]."'%2F%3E";
						}
					}

					$attr['data-nectar-img-src'] = $temp_src;
				}

				// Alter Class.
				if( isset($attr['class']) ) {
					$attr['class'] = $attr['class'] . ' nectar-lazy';
				}

			} // limit to shop only.

		} // Products only.

		return $attr;

	}
}



// Remove AJAX for products with a large amoutn of variations
if ( !function_exists( 'nectar_wc_ajax_variation_thresh' ) ) {
	function nectar_wc_ajax_variation_thresh( $qty, $product ) {
	    return 125;
	}
}
add_filter( 'woocommerce_ajax_variation_threshold', 'nectar_wc_ajax_variation_thresh', 10, 2 );


// Third Party.

// WPML.
add_filter( 'wcml_multi_currency_ajax_actions', 'add_action_to_multi_currency_ajax', 10, 1 );

function add_action_to_multi_currency_ajax( $ajax_actions ) {
    $ajax_actions[] = 'nectar_woo_get_product';
    $ajax_actions[] = 'nectar_ajax_add_to_cart';
    $ajax_actions[] = 'nectar_minicart_update_quantity';

    return $ajax_actions;
}


// YITH Ajax filters.
if( class_exists('YITH_WCAN_Frontend_Premium') ) {
	add_filter('salient_woocommerce_sidebar_toggles', 'salient_remove_woocommerce_sidebar_toggles');
}

if( !function_exists('salient_remove_woocommerce_sidebar_toggles') ) {
	function salient_remove_woocommerce_sidebar_toggles() {
		return false;
	}
}
