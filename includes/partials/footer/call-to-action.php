<?php
/**
 * Footer bottom content
 *
 * @package Salient WordPress Theme
 * @subpackage Partials
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
global $post;
global $wp;

$nectar_options = get_nectar_theme_options();
$exclude_pages  = ( ! empty( $nectar_options['exclude_cta_pages'] ) ) ? $nectar_options['exclude_cta_pages'] : array();
$cta_link       = ( ! empty( $nectar_options['cta-btn-link'] ) ) ? $nectar_options['cta-btn-link'] : '#';
$cta_btn_color  = ( ! empty( $nectar_options['cta-btn-color'] ) ) ? $nectar_options['cta-btn-color'] : 'accent-color';

if ( ! empty( $nectar_options['cta-text'] ) && ! in_array( $post->ID, $exclude_pages ) ) {

?>

<div id="call-to-action">
	<div class="container">
		  <div class="triangle"></div>
		  <span> <?php echo wp_kses_post( $nectar_options['cta-text'] ); ?> </span>
		  <a class="nectar-button 
		  <?php
			if ( $cta_btn_color !== 'see-through' ) {
				echo 'regular-button ';}

		  echo esc_html( $cta_btn_color ); ?>" data-color-override="false" href="<?php echo esc_attr( $cta_link ); ?>">
		  <?php
			if ( ! empty( $nectar_options['cta-btn'] ) ) {
				echo wp_kses_post( $nectar_options['cta-btn'] );}
			?>
		</a>
	</div>
</div>

	<?php
}