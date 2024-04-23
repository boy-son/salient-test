<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $nectar_options;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'woocommerce' ) ) );

?>

<?php if ( $heading ) : ?>
	<h2 class="hidden-tab-heading"><?php echo esc_html( $heading ); ?></h2>
<?php endif; ?>

<?php the_content(); ?>
