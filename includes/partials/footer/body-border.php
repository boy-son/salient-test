<?php
/**
 * Body border (Passepartout)
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
$body_border    = ( ! empty( $nectar_options['body-border'] ) ) ? $nectar_options['body-border'] : 'off';

if ( '1' === $body_border ) {
	echo '<div class="body-border-top"></div>
		<div class="body-border-right"></div>
		<div class="body-border-bottom"></div>
		<div class="body-border-left"></div>';
}
