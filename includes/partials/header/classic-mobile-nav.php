<?php
/**
 * Simple mobile navigation
 *
 * Used when theme option "Off Canvas Menu Style" is set to "Simple dropdown".
 *
 * @package    Salient WordPress Theme
 * @subpackage Partials
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$nectar_options = get_nectar_theme_options();

$header_format      = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
$mobile_fixed       = ( ! empty( $nectar_options['header-mobile-fixed'] ) ) ? $nectar_options['header-mobile-fixed'] : 'false';
$has_main_menu      = ( has_nav_menu( 'top_nav' ) ) ? 'true' : 'false';
$header_mobile_func = ( ! empty( $nectar_options['secondary-header-mobile-display'] ) ) ? $nectar_options['secondary-header-mobile-display'] : 'default';

// Using pull right menu.
$using_pr_menu = 'false';
if ( $header_format === 'menu-left-aligned' || 
     $header_format === 'centered-menu' || 
		 $header_format === 'centered-logo-between-menu' ||
	   $header_format === 'centered-logo-between-menu-alt' ) {
			 
		if ( has_nav_menu( 'top_nav_pull_right' ) ) {
			$using_pr_menu = 'true';
		}
}


?>

<div id="mobile-menu" data-mobile-fixed="<?php echo esc_attr( $mobile_fixed ); ?>">

	<div class="inner">

		<?php
			$using_secondary = ( ! empty( $nectar_options['header_layout'] ) && $header_format != 'left-header' ) ? $nectar_options['header_layout'] : ' ';
			$has_secondary_text = 'false';

			if( isset($nectar_options['header-text-widget']) && !empty($nectar_options['header-text-widget']) ) {
				echo '<div class="nectar-header-text-content mobile-only"><div>'.wp_kses_post($nectar_options['header-text-widget']).'</div></div>';
			}

			if ( ! empty( $nectar_options['secondary-header-text'] ) && $using_secondary === 'header_with_secondary' && $header_mobile_func !== 'display_full' ) {
				$has_secondary_text = 'true';
				$nectar_secondary_link = ( ! empty( $nectar_options['secondary-header-link'] ) ) ? $nectar_options['secondary-header-link'] : '';
				echo '<div class="secondary-header-text"><p>';
				if ( ! empty( $nectar_secondary_link ) ) {
					echo '<a href="' . esc_url( $nectar_secondary_link ) . '">';
				}
				echo wp_kses_post( $nectar_options['secondary-header-text'] );
				if ( ! empty( $nectar_secondary_link ) ) {
					echo '</a>';
				}
				echo '</p></div>';
			}
		?>

		<div class="menu-items-wrap" data-has-secondary-text="<?php echo esc_attr($has_secondary_text); ?>">

			<ul>
				<?php

				if ( $has_main_menu === 'true' ) {

					wp_nav_menu(
						array(
							'walker'         => new Nectar_OCM_Icon_Walker(),
							'theme_location' => apply_filters('nectar_simple_dropdown_ocm_location','top_nav'),
							'container'      => '',
							'items_wrap'     => '%3$s',
						)
					);

				}

				if ( $header_format === 'centered-menu-bottom-bar' || 
				     $header_format === 'centered-logo-between-menu-alt' ) {
							 
						if ( has_nav_menu( 'top_nav_pull_left' ) ) {
							wp_nav_menu(
								array(
									'walker'         => new Nectar_OCM_Icon_Walker(),
									'theme_location' => 'top_nav_pull_left',
									'container'      => '',
									'items_wrap'     => '%3$s',
								)
							);
						}
				}

				if ( $header_format === 'centered-menu' && $using_pr_menu === 'true' ||
					$header_format === 'menu-left-aligned' && $using_pr_menu == 'true' ||
					$header_format === 'centered-logo-between-menu' && $using_pr_menu === 'true' ||
				  $header_format === 'centered-logo-between-menu-alt' && $using_pr_menu === 'true' ) {

					wp_nav_menu(
						array(
							'walker'         => new Nectar_OCM_Icon_Walker(),
							'theme_location' => 'top_nav_pull_right',
							'container'      => '',
							'items_wrap'     => '%3$s',
						)
					);
					nectar_hook_pull_right_menu_items();
				}
				?>


			</ul>

			<?php
			// Secondary nav menu items.
			$using_secondary = ( ! empty( $nectar_options['header_layout'] ) && $header_format != 'left-header' ) ? $nectar_options['header_layout'] : ' ';

			if ( $using_secondary === 'header_with_secondary' && has_nav_menu( 'secondary_nav' ) && $header_mobile_func !== 'display_full' ) { ?>

				<ul class="secondary-header-items">

					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'secondary_nav',
								'container'      => '',
								'items_wrap'     => '%3$s',
							)
						);

					?>

				</ul>

			<?php } ?>

		</div><!--/menu-items-wrap-->

		<div class="below-menu-items-wrap">
			<?php
			// Social Icons.
			if ( ! empty( $nectar_options['header-slide-out-widget-area-social'] ) &&
				$nectar_options['header-slide-out-widget-area-social'] === '1' ) {
				nectar_ocm_add_social();
			}

			// Bottom Text.
			if ( ! empty( $nectar_options['header-slide-out-widget-area-bottom-text'] ) ) {
				echo '<p class="bottom-text">' . wp_kses_post( $nectar_options['header-slide-out-widget-area-bottom-text'] ) . '</p>';
			}

			nectar_hook_ocm_bottom_meta();

			?>
		</div><!--/below-menu-items-wrap-->

	</div><!--/inner-->

</div><!--/mobile-menu-->
