<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' );

$nectar_options  = get_nectar_theme_options();
$mini_cart_style = ( isset($nectar_options['ajax-cart-style']) && 'slide_in_click' === $nectar_options['ajax-cart-style'] ) ? 'slide_in_click' : 'default';

?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php
          if( 'slide_in_click' !== $mini_cart_style ) {
	  				$default_remove = apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	  						'woocommerce_cart_item_remove_link',
	  						sprintf(
	  							'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
	  							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
	  							/* translators: %s is the product name */
								esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
	  							esc_attr( $product_id ),
	  							esc_attr( $cart_item_key ),
	  							esc_attr( $_product->get_sku() )
	  						),
	  						$cart_item_key
	  					);
          } else {
						$default_remove = '';
					}
					?>

					<?php if ( empty( $product_permalink ) ) : ?>
						<?php echo '<span class="no-permalink">' . $thumbnail . '</span><div class="product-meta"><div class="product-details">' . $default_remove . wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php else : ?>
						<?php echo '<a href="'. esc_url( $product_permalink ) .'">' . $thumbnail . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo '<div class="product-meta"><div class="product-details">'.$default_remove .'<a href="'.esc_url( $product_permalink ). '">'. wp_kses_post( $product_name ) .'</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>

					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          <?php echo '</div>'; // end product-details ?>
          <?php
          // Slide in ext style.
          if( 'slide_in_click' === $mini_cart_style ) {

           // Create quantity markup.
		    $max_quantity = $_product->get_max_purchase_quantity();
			$min_quantity = $_product->get_min_purchase_quantity();

			// min/max from plugin.
		   	$min_max_quantities = Nectar_Woo_Cart::get_minmax_quantities($cart_item_key);
			$constrained_maximum_quantity = $min_max_quantities['max'];
			$constrained_minimum_quantity = $min_max_quantities['min'];

			if ( $constrained_maximum_quantity ) {
				$max_quantity = $constrained_maximum_quantity;
			}
			if ( $constrained_minimum_quantity ) {
				$min_quantity = $constrained_minimum_quantity;
			}

			// output quantity.
            $quantity_markup = woocommerce_quantity_input( array(
            'min_value' => apply_filters( 'woocommerce_quantity_input_min', $min_quantity, $_product ),
            'max_value' => apply_filters( 'woocommerce_quantity_input_max', $max_quantity, $_product ),
            'input_value' => $cart_item['quantity'],
            'input_name' => 'cart['.$cart_item_key.'][qty]'), $_product, false );

            // Remove link.
            $remove_link = sprintf(
                '<a href="%s" class="remove remove_from_cart_button with_text" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                /* translators: %s is the product name */
				esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                esc_attr( $product_id ),
                esc_attr( $cart_item_key ),
                esc_attr( $_product->get_sku() ),
                esc_attr__( 'Remove', 'woocommerce' )
              );

            if( false === $_product->get_sold_individually() ) {
              echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity"><span class="modify">' . $quantity_markup . '</span><span class="product-price">' . $product_price . $remove_link . '</span></span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
              echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity"><span class="modify"></span><span class="product-price">'. $product_price . $remove_link . '</span></span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

          }
          // Regular ajax cart styles.
          else {
            echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
          }

          echo '</div>'; // end product-meta
          ?>

				</li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<p class="woocommerce-mini-cart__total total">
		<?php
		/**
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

<?php else : ?>
	
	<?php if( 'slide_in_click' !== $mini_cart_style ) { ?>
		<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>
		<?php do_action( 'nectar_woocommerce_mini_cart_empty' ); ?>
	<?php } else { ?>
		<div class="woocommerce-mini-cart__empty-message">
			<span data-inherit-heading-family="h3"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></span>
			<a class="button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php esc_html_e( 'Go to shop', 'salient' ); ?>
			</a>
			<?php do_action( 'nectar_woocommerce_mini_cart_empty' ); ?>
		</div>
	<?php } ?>
<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
