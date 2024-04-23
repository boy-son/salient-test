<?php
/**
 * Salient WooCommerce Cart
 *
 * @package Salient WordPress Theme
 * @version 13.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists('Nectar_Woo_Cart') ) {
	
	class Nectar_Woo_Cart {

	  private static $instance;

	  private function __construct() {

	      $this->hooks();

	  }

	  /**
		 * Initiator.
		 */
	  public static function get_instance() {
			if ( !self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

	  /**
		 * Adds actions/filters.
		 */
	  public function hooks() {

	    global $nectar_options;

	    // Empty Mini Cart Content.
	    if( isset($nectar_options['ajax-cart-style']) &&
	        'slide_in_click' === $nectar_options['ajax-cart-style'] ) {
	      add_action( 'woocommerce_after_mini_cart', array($this, 'empty_minicart_buttons'), 10);
	    }

		// Mini Cart QTY AJAX.
	    add_action( 'wp_ajax_nectar_minicart_update_quantity', array($this, 'update_cart_quantity' ) );
	    add_action( 'wp_ajax_nopriv_nectar_minicart_update_quantity', array($this, 'update_cart_quantity' ) );


		// Single Product AJAX Add to Cart
		if( isset($nectar_options['ajax-add-to-cart']) && 
			'1' === $nectar_options['ajax-add-to-cart']) {
			
			$editing_subscription = isset($_GET['switch-subscription']) ? true : false;
			if ( !$editing_subscription ) { 
				add_action( 'wp_ajax_nectar_ajax_add_to_cart', array($this, 'add_to_cart' ) );
				add_action( 'wp_ajax_nopriv_nectar_ajax_add_to_cart', array($this, 'add_to_cart' ) );
			}

		}

	  }


	  /**
		 * Adds in the WooCommerce minicart buttons
	   * even when the cart is empty.
		 */
	  public static function empty_minicart_buttons() {

			if( WC()->cart->is_empty() ) {

				echo '<div class="nectar-inactive"><p class="woocommerce-mini-cart__total total">';
					do_action( 'woocommerce_widget_shopping_cart_total' );
				echo '</p>';

				do_action('woocommerce_widget_shopping_cart_buttons');
				echo '</div>';
			}

		}

		/**
		 * Compatibility for WooCommerce Min/Max Quantities plugin.
		 */
		public static function get_minmax_quantities($item_key) {

			$minimum_quantity = 0;
			$maximum_quantity = 0;
			
			// Check for WooCommerce min/max variation quantity.
			if( class_exists('WC_Min_Max_Quantities') ) {
				$product = WC()->cart->get_cart_item($item_key);
				if( $product && isset( $product['variation_id'] ) ) {
					$variation_id = $product['variation_id'];
					$product_id = $product['product_id'];
					$min_max_rules = get_post_meta( $variation_id, 'min_max_rules', true );
					
					if ( 'yes' === $min_max_rules ) {
						
						$maximum_quantity = absint( get_post_meta( $variation_id, 'variation_maximum_allowed_quantity', true ) );
						$minimum_quantity = absint( get_post_meta( $variation_id, 'variation_minimum_allowed_quantity', true ) );

						// If the Minimum Quantity is not set on variation level, fall back to the parent's.
						if ( 0 === $maximum_quantity ) {
							$maximum_quantity = absint( get_post_meta( $product_id, 'maximum_allowed_quantity', true ) );
						}

						// If the Maximum Quantity is not set on variation level, fall back to the parent's.
						if ( 0 === $minimum_quantity ) {
							$minimum_quantity = absint( get_post_meta( $product_id, 'minimum_allowed_quantity', true ) );
						}

					} else {
						// default to product min/max
						$maximum_quantity  = absint( get_post_meta( $product_id, 'maximum_allowed_quantity', true ) );
						$minimum_quantity  = absint( get_post_meta( $product_id, 'minimum_allowed_quantity', true ) );
					}


				} // endif $product has variation_id
			} // endif WC_Min_Max_Quantities is active

			return [
				'min' => $minimum_quantity,
				'max' => $maximum_quantity
			];
		}

		/**
		 * AJAX callback to update the minicart quantity.
		 */
		public static function update_cart_quantity() {

			if( !isset($_POST['quantity']) || !isset($_POST['item_key']) || !function_exists('WC') ) {
				wp_die();
			}

			$quantity = absint( $_POST['quantity'] );
			$item_key = sanitize_text_field( $_POST['item_key'] );

			// Check for WooCommerce min/max variation quantity.
			$min_max_quantities = self::get_minmax_quantities($item_key);
			$maximum_quantity = $min_max_quantities['max'];
			$minimum_quantity = $min_max_quantities['min'];
			if ( $maximum_quantity && $quantity > $maximum_quantity ) {
				$quantity = $maximum_quantity;
			}
			if ( $minimum_quantity && $quantity < $minimum_quantity ) {
				$quantity = $minimum_quantity;
			}

			// Check to make sure the item exists.
			if ( !WC()->cart->get_cart_item( $item_key ) ) {
				wp_die();
			}
			
			WC()->cart->set_quantity( $item_key, $quantity, true );
			WC()->cart->calculate_totals();

			$cart_item = WC()->cart->get_cart_item($item_key);
			$item_price = WC()->cart->get_product_price( $cart_item['data'] );
			
			wp_send_json( array(
				'item' => $cart_item,
				'item_price' => apply_filters( 'woocommerce_cart_item_price', $item_price, $cart_item, $item_key ), 
				'subtotal' => WC()->cart->get_cart_subtotal(),
				'item_count' => WC()->cart->get_cart_contents_count()
			) );

			wp_die();

		}

		/**
		 * AJAX callback for add to cart.
		 */
		 public static function add_to_cart() {

			 if( !isset($_POST['add-to-cart']) || !function_exists('WC') ) {
				 wp_die();
			 }

			 // Triggers WooCommerce to add to the cart.
			 $product_id = absint( $_POST['add-to-cart'] );

			 // Check for errors to output.
			 $error_notices = wc_get_notices('error');
			 wc_clear_notices();

			 if( empty($error_notices) ) {
				 do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			 }

			 // Get updated fragments.
			 ob_start();
			 woocommerce_mini_cart();
			 $mini_cart = ob_get_clean();

			 $data = array(
				 'fragments' => apply_filters(
					 'woocommerce_add_to_cart_fragments',
					 array(
						 'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
					 )
				 ),
				 'cart_hash' => WC()->cart->get_cart_hash(),
				 'notices' => $error_notices
			 );

			 // Send the data.
			 wp_send_json( $data );

			 wp_die();

		 }


	}
	
}

/**
 * Initialize the Nectar_Woo_Cart class
 */
Nectar_Woo_Cart::get_instance();
