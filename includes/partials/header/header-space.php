<?php
/**
* Header nav space
*
* @package    Salient WordPress Theme
* @subpackage Partials
* @version    10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$nectar_header_options      = nectar_get_header_variables();
$nectar_options             = get_nectar_theme_options();
$header_format              = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
$using_secondary            = ( ! empty( $nectar_options['header_layout'] ) && $header_format !== 'left-header' ) ? $nectar_options['header_layout'] : ' ';
$header_secondary_m_display = ( ! empty( $nectar_options['secondary-header-mobile-display'] ) ) ? $nectar_options['secondary-header-mobile-display'] : 'default';
$header_secondary_m_attr    = ( $using_secondary === 'header_with_secondary' && $header_secondary_m_display === 'display_full' ) ? true : false;


if ( $nectar_header_options['perm_trans'] !== '1' || 
$nectar_header_options['perm_trans'] === '1' && $nectar_header_options['bg_header'] == 'false' || 
$nectar_header_options['page_full_screen_rows'] === 'on' ) { ?>
	
	<div id="header-space" <?php echo (esc_html($header_secondary_m_attr)) ? 'data-secondary-header-display="full"' : ''; ?> data-header-mobile-fixed='<?php echo esc_attr( $nectar_header_options['mobile_fixed'] ); ?>'></div> 
	
	<?php
	
}