<?php
/**
 * Salient Theme Hooks.
 *
 * @package Salient WordPress Theme
 * @subpackage hooks
 * @version 12.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * After body open tag.
 */
function nectar_hook_after_body_open() {
	do_action( 'nectar_hook_after_body_open' );
}

/**
 * Before Header Navigation.
 */
function nectar_hook_before_header_nav() {
	do_action( 'nectar_hook_before_header_nav' );
}

/**
 * After outer wrap open (#ajax-content-wrap).
 */
function nectar_hook_after_outer_wrap_open() {
	do_action( 'nectar_hook_after_outer_wrap_open' );
}

function nectar_hook_global_section_after_header_navigation() {
	do_action( 'nectar_hook_global_section_after_header_navigation' );
}

/**
 * Before page/post content begins.
 */
function nectar_hook_before_content() {
  do_action( 'nectar_hook_before_content' );
}
function nectar_hook_before_content_global_section() {
	do_action( 'nectar_hook_before_content_global_section' );
}

/**
 * After page/post content ends.
 */
function nectar_hook_after_content() {
  do_action( 'nectar_hook_after_content' );
}

function nectar_hook_global_section_after_content() {
	do_action( 'nectar_hook_global_section_after_content' );
}

/**
 * Pull right menu items.
 */
function nectar_hook_pull_right_menu_items() {
	do_action( 'nectar_hook_pull_right_menu_items' );
}

/**
 * Pull left menu items.
 */
function nectar_hook_pull_left_menu_items() {
	do_action( 'nectar_hook_pull_left_menu_items' );
}

/**
 * Before button menu items.
 */
function nectar_hook_before_button_menu_items() {
	do_action( 'nectar_hook_before_button_menu_items' );
}

/**
 * Mobile menu items.
 */
function nectar_hook_mobile_header_menu_items() {
	do_action( 'nectar_hook_mobile_header_menu_items' );
}
function nectar_hook_mobile_header_before_logo() {
	do_action( 'nectar_hook_mobile_header_before_logo' );
}

/**
 * Secondary header layout menu items.
 */
function nectar_hook_before_secondary_header() {
	do_action( 'nectar_hook_before_secondary_header' );
}

function nectar_hook_secondary_header_menu_items() {
	do_action( 'nectar_hook_secondary_header_menu_items' );
}

function nectar_hook_secondary_header_after_nav_open() {
	do_action( 'nectar_hook_secondary_header_after_nav_open' );
}

function nectar_hook_secondary_header_before_nav_close() {
	do_action( 'nectar_hook_secondary_header_before_nav_close' );
}


/**
 * Off canvas menu before menu.
 */
function nectar_hook_ocm_before_menu() {
	do_action( 'nectar_hook_ocm_before_menu' );
}

/**
 * Off canvas menu after menu.
 */
function nectar_hook_ocm_after_menu() {
	do_action( 'nectar_hook_ocm_after_menu' );
}

/**
 * Off canvas menu before secondary items.
 */
 
function nectar_hook_ocm_before_secondary_items() {
	do_action( 'nectar_hook_ocm_before_secondary_items' );
}

/**
 * Off canvas menu after secondary items.
 */
function nectar_hook_ocm_after_secondary_items() {
	do_action( 'nectar_hook_ocm_after_secondary_items' );
}

/**
 * Off canvas menu bottom meta.
 */
function nectar_hook_ocm_bottom_meta() {
	do_action( 'nectar_hook_ocm_bottom_meta' );
}

/**
 *Before sidebar items..
 */
function nectar_hook_sidebar_top() {
	do_action( 'nectar_hook_sidebar_top' );
}

/**
 *Before sidebar items..
 */
function nectar_hook_sidebar_bottom() {
	do_action( 'nectar_hook_sidebar_bottom' );
}

/**
 *Before footer open.
 */
function nectar_hook_before_container_wrap_close() {
	do_action( 'nectar_hook_before_container_wrap_close' );
}

/**
 *Before footer open.
 */
function nectar_hook_before_footer_open() {
	do_action( 'nectar_hook_before_footer_open' );
}

/**
 *After footer open.
 */
function nectar_hook_after_footer_open() {
	do_action( 'nectar_hook_after_footer_open' );
}

/**
 * Before footer widgets.
 */
function nectar_hook_before_footer_widget_area() {
	do_action( 'nectar_hook_before_footer_widget_area' );
}

/**
 * After footer widgets.
 */
function nectar_hook_after_footer_widget_area() {
	do_action( 'nectar_hook_after_footer_widget_area' );
}


function nectar_hook_404_content() {
	do_action( 'nectar_hook_404_content' );
}

/**
 * Before outer wrap close (#ajax-content-wrap).
 */
function nectar_hook_before_outer_wrap_close() {
	do_action( 'nectar_hook_before_outer_wrap_close' );
}
function nectar_hook_global_section_footer() {
	do_action( 'nectar_hook_global_section_footer' );
}
function nectar_hook_global_section_parallax_footer() {
	do_action( 'nectar_hook_global_section_parallax_footer' );
}
function nectar_hook_global_section_after_footer() {
	do_action( 'nectar_hook_global_section_after_footer' );
}

/**
 * After WP footer.
 */
function nectar_hook_after_wp_footer() {
	do_action( 'nectar_hook_after_wp_footer' );
}

/**
 * Before body close tag.
 */
function nectar_hook_before_body_close() {
	do_action( 'nectar_hook_before_body_close' );
}


/* WooCommerce */
function nectar_woocommerce_before_shop_loop() {
	do_action( 'nectar_woocommerce_before_shop_loop' );
}
function nectar_woocommerce_after_shop_loop() {
	do_action( 'nectar_woocommerce_after_shop_loop' );
}
function nectar_woocommerce_before_single_product_summary() {
	do_action( 'nectar_woocommerce_before_single_product_summary' );
}
function nectar_woocommerce_before_add_to_cart_form() {
	do_action( 'nectar_woocommerce_before_add_to_cart_form' );
}
function nectar_woocommerce_after_add_to_cart_form() {
	do_action( 'nectar_woocommerce_after_add_to_cart_form' );
}
function nectar_woocommerce_after_single_product_summary() {
	if (has_action( 'nectar_woocommerce_after_single_product_summary' )) {
		echo '<div class="clear"></div>';
	}
	do_action( 'nectar_woocommerce_after_single_product_summary' );
}
function nectar_woocommerce_before_checkout_billing_form() {
	do_action( 'nectar_woocommerce_before_checkout_billing_form' );
}
function nectar_woocommerce_after_checkout_billing_form() {
	do_action( 'nectar_woocommerce_after_checkout_billing_form' );
}
function nectar_woocommerce_before_checkout_shipping_form() {
	do_action( 'nectar_woocommerce_before_checkout_shipping_form' );
}

function nectar_woocommerce_before_order_notes() {
	do_action( 'nectar_woocommerce_before_order_notes' );
}
function nectar_woocommerce_after_order_notes() {
	do_action( 'nectar_woocommerce_after_order_notes' );
}
function nectar_woocommerce_checkout_before_order_review() {
	do_action( 'nectar_woocommerce_checkout_before_order_review' );
}
function nectar_woocommerce_review_order_before_payment() {
	do_action( 'nectar_woocommerce_review_order_before_payment' );
}
function nectar_woocommerce_review_order_after_payment() {
	do_action( 'nectar_woocommerce_review_order_after_payment' );
}

function nectar_woocommerce_cart_coupon() {
	do_action( 'nectar_woocommerce_cart_coupon' );
}
function nectar_woocommerce_before_cart_totals() {
	do_action( 'nectar_woocommerce_before_cart_totals' );
}
function nectar_woocommerce_cart_totals_before_shipping() {
	do_action( 'nectar_woocommerce_cart_totals_before_shipping' );
}

function nectar_woocommerce_before_shipping_calculator() {
	do_action( 'nectar_woocommerce_before_shipping_calculator' );
}
function nectar_woocommerce_after_shipping_calculator() {
	do_action( 'nectar_woocommerce_after_shipping_calculator' );
}
function nectar_woocommerce_proceed_to_checkout() {
	do_action( 'nectar_woocommerce_proceed_to_checkout' );
}









?>