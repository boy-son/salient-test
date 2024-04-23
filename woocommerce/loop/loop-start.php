<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

$nectar_options                = get_nectar_theme_options(); 
$product_style                 = (!empty($nectar_options['product_style'])) ? $nectar_options['product_style'] : 'classic';
$nectar_main_shop_layout       = (!empty($nectar_options['main_shop_layout'])) ? $nectar_options['main_shop_layout'] : 'no-sidebar';
$nectar_woo_desktop_cols       = (!empty($nectar_options['product_desktop_cols'])) ? $nectar_options['product_desktop_cols'] : 'default';
$nectar_woo_desktop_small_cols = (!empty($nectar_options['product_desktop_small_cols'])) ? $nectar_options['product_desktop_small_cols'] : 'default';
$nectar_woo_tablet_cols        = (!empty($nectar_options['product_tablet_cols'])) ? $nectar_options['product_tablet_cols'] : 'default';
$nectar_woo_phone_cols         = (!empty($nectar_options['product_phone_cols'])) ? $nectar_options['product_phone_cols'] : 'default';
$nectar_woo_lazy_load          = (isset( $nectar_options['product_lazy_load_images'] ) && !empty( $nectar_options['product_lazy_load_images'] )) ? $nectar_options['product_lazy_load_images'] : 'off';
$nectar_woo_rm_mobile_hover    = (isset( $nectar_options['product_mobile_deactivate_hover'] ) && !empty( $nectar_options['product_mobile_deactivate_hover'] )) ? $nectar_options['product_mobile_deactivate_hover'] : 'off';

// Default to modern flex columns.
if( 'classic' === $product_style || 'text_on_hover' === $product_style ) {
  
  if( 'no-sidebar' === $nectar_main_shop_layout ) {
    if( 'default' === $nectar_woo_desktop_cols ) {
      $nectar_woo_desktop_cols = '4';
    }
    if( 'default' === $nectar_woo_desktop_small_cols ) {
      $nectar_woo_desktop_small_cols = '3';
    }
  }

  if( 'right-sidebar' === $nectar_main_shop_layout || 
      'left-sidebar' === $nectar_main_shop_layout ) {  
    if( 'default' === $nectar_woo_desktop_cols ) {
      $nectar_woo_desktop_cols = '3';
    }
    if( 'default' === $nectar_woo_desktop_small_cols ) {
      $nectar_woo_desktop_small_cols = '3';
    }
  }
  
  if( 'fullwidth' === $nectar_main_shop_layout ) {
    if( 'default' === $nectar_woo_desktop_cols ) {
      $nectar_woo_desktop_cols = '5';
    }
  }
  
}

?>

<?php if(function_exists('wc_get_loop_prop')) { ?>
  <ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) );?>" data-n-lazy="<?php echo esc_attr($nectar_woo_lazy_load); ?>" data-rm-m-hover="<?php echo esc_attr($nectar_woo_rm_mobile_hover); ?>" data-n-desktop-columns="<?php echo esc_attr( $nectar_woo_desktop_cols ); ?>" data-n-desktop-small-columns="<?php echo esc_attr( $nectar_woo_desktop_small_cols ); ?>" data-n-tablet-columns="<?php echo esc_attr( $nectar_woo_tablet_cols ); ?>" data-n-phone-columns="<?php echo esc_attr( $nectar_woo_phone_cols ); ?>" data-product-style="<?php echo esc_attr( $product_style ); ?>">
<?php } else { ?>
  <ul class="products" data-product-style="<?php echo esc_attr( $product_style ); ?>">
<?php } ?>


