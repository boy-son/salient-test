<?php
/**
 * Back to top button
 *
 * @package Salient WordPress Theme
 * @subpackage Partials
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

if ( ! empty( $nectar_options['back-to-top'] ) && $nectar_options['back-to-top'] === '1' ) { ?>
	<a id="to-top" aria-label="<?php echo esc_html__('Back to top','salient'); ?>" href="#" class="<?php
	if ( ! empty( $nectar_options['back-to-top-mobile'] ) && $nectar_options['back-to-top-mobile'] === '1' ) {
		echo 'mobile-enabled';} else { echo 'mobile-disabled'; }
	?>"><i role="presentation" class="fa fa-angle-up"></i></a>
	<?php
}
