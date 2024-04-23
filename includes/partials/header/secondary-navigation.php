<?php
/**
* Secondary navigation bar
*
* @package    Salient WordPress Theme
* @subpackage Partials
* @version 14.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$nectar_options = get_nectar_theme_options();

$using_page_header         = nectar_using_page_header( $post->ID );
$header_format             = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
$header_link_hover_effect  = ( ! empty( $nectar_options['header-hover-effect'] ) ) ? $nectar_options['header-hover-effect'] : 'default';
$mobile_fixed              = ( ! empty( $nectar_options['header-mobile-fixed'] ) ) ? $nectar_options['header-mobile-fixed'] : 'false';
$bg_header                 = ( ! empty( $post->ID ) && $post->ID != 0 ) ? $using_page_header : 0;
$bg_header                 = ( $bg_header == 1 ) ? 'true' : 'false';
$trans_header              = ( ! empty( $nectar_options['transparent-header'] ) && $nectar_options['transparent-header'] === '1' ) ? $nectar_options['transparent-header'] : 'false';
$perm_trans                = ( ! empty( $nectar_options['header-permanent-transparent'] ) && $trans_header != 'false' && $bg_header === 'true' && $header_format != 'centered-menu-bottom-bar' ) ? $nectar_options['header-permanent-transparent'] : 'false';
$header_remove_stickiness  = ( ! empty( $nectar_options['header-remove-fixed'] ) ) ? $nectar_options['header-remove-fixed'] : '0';
$header_mobile_func        = ( ! empty( $nectar_options['secondary-header-mobile-display'] ) && $perm_trans !== '1' ) ? $nectar_options['secondary-header-mobile-display'] : 'default';

if ( $header_format === 'left-header' ) {
	$header_remove_stickiness = '0'; 
}

$using_secondary      	 	= ( ! empty( $nectar_options['header_layout'] ) && $header_format !== 'left-header' ) ? $nectar_options['header_layout'] : ' ';
$secondary_header_text 		= ( ! empty( $nectar_options['secondary-header-text'] ) ) ? 'true' : 'false';

nectar_hook_before_secondary_header();

if ( $using_secondary === 'header_with_secondary' ) { ?>
	
	<div id="header-secondary-outer" class="<?php echo esc_attr( $header_format ); ?>" data-mobile="<?php echo esc_attr($header_mobile_func); ?>" data-remove-fixed="<?php echo esc_attr( $header_remove_stickiness ); ?>" data-lhe="<?php echo esc_attr( $header_link_hover_effect ); ?>" data-secondary-text="<?php echo esc_attr( $secondary_header_text ); ?>" data-full-width="<?php echo ( ! empty( $nectar_options['header-fullwidth'] ) && $nectar_options['header-fullwidth'] === '1' ) ? 'true' : 'false'; ?>" data-mobile-fixed="<?php echo esc_attr( $mobile_fixed ); ?>" data-permanent-transparent="<?php echo esc_attr( $perm_trans ); ?>" >
		<div class="container">
			<nav>
				<?php

				nectar_hook_secondary_header_after_nav_open();

				if ( ! empty( $nectar_options['enable_social_in_header'] ) && 
					$nectar_options['enable_social_in_header'] === '1' && 
					$header_format !== 'centered-menu-bottom-bar' ) {
					nectar_header_social_icons( 'secondary-nav' );
				}
				
				if ( ! empty( $nectar_options['secondary-header-text'] ) ) {
					$nectar_secondary_link = ( ! empty( $nectar_options['secondary-header-link'] ) ) ? $nectar_options['secondary-header-link'] : '';
					echo '<div class="nectar-center-text">';
					if ( ! empty( $nectar_secondary_link ) ) {
						echo '<a href="' . esc_url( $nectar_secondary_link ) . '">';
					}
					echo do_shortcode( wp_kses_post( $nectar_options['secondary-header-text'] ) );
					if ( ! empty( $nectar_secondary_link ) ) {
						echo '</a>';
					}
					echo '</div>';
				}
				
				if ( has_nav_menu( 'secondary_nav' ) ) { ?>
					<ul class="sf-menu">
						<?php
						wp_nav_menu(
							array(
								'walker'         => new Nectar_Arrow_Walker_Nav_Menu(),
								'theme_location' => 'secondary_nav',
								'container'      => '',
								'items_wrap'     => '%3$s',
							)
						);
						nectar_hook_secondary_header_menu_items();
						
						?>
					</ul>
					<?php
				}

				nectar_hook_secondary_header_before_nav_close();
				
				?>
				
			</nav>
		</div>
	</div>
	
<?php }