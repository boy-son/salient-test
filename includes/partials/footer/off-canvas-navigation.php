<?php
/**
* Off canvas navigation
*
* @package Salient WordPress Theme
* @subpackage Partials
* @version 13.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

$header_format  = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
$theme_skin     = NectarThemeManager::$skin;

$mobile_fixed                = ( ! empty( $nectar_options['header-mobile-fixed'] ) ) ? $nectar_options['header-mobile-fixed'] : 'false';
$has_main_menu               = ( has_nav_menu( 'top_nav' ) || $header_format === 'centered-logo-between-menu-alt' ) ? 'true' : 'false';
$full_width_header           = ( ! empty( $nectar_options['header-fullwidth'] ) && $nectar_options['header-fullwidth'] === '1' ) ? true : false;
$side_widget_class           = NectarThemeManager::$ocm_style;
$side_widget_area            = ( ! empty( $nectar_options['header-slide-out-widget-area'] ) ) ? $nectar_options['header-slide-out-widget-area'] : 'off';
$side_widget_overlay_opacity = ( ! empty( $nectar_options['header-slide-out-widget-area-overlay-opacity'] ) ) ? $nectar_options['header-slide-out-widget-area-overlay-opacity'] : 'dark';
$prepend_top_nav_mobile      = ( ! empty( $nectar_options['header-slide-out-widget-area-top-nav-in-mobile'] ) ) ? $nectar_options['header-slide-out-widget-area-top-nav-in-mobile'] : 'false';
$dropdown_func               = ( ! empty( $nectar_options['header-slide-out-widget-area-dropdown-behavior'] ) ) ? $nectar_options['header-slide-out-widget-area-dropdown-behavior'] : 'default';
$user_set_side_widget_area   = $side_widget_area;
$separate_mobile_ocm         = ( isset( $nectar_options['header-slide-out-widget-area-separate-mobile'] ) && $side_widget_area != '1' ) ? $nectar_options['header-slide-out-widget-area-separate-mobile'] : '0'; 

if ( $has_main_menu === 'true' ) {
	$side_widget_area = '1';
}

if ( $header_format === 'centered-menu-under-logo' ) {
	if ( $side_widget_class === 'slide-out-from-right-hover' && $user_set_side_widget_area === '1' ) {
		$side_widget_class = 'slide-out-from-right';
	}
}

if ( $theme_skin === 'material' ) {
	$prepend_top_nav_mobile = apply_filters('nectar_material_prepend_top_mobile', '1');
}

if ( $side_widget_class === 'fullscreen' || 
     $side_widget_class === 'fullscreen-alt' || 
     $side_widget_class === 'fullscreen-inline-images' ) {
	$dropdown_func = 'default';
}

// Legacy double mobile menu
$legacy_double_menu = nectar_legacy_mobile_double_menu();
if( true === $legacy_double_menu ) {
	$prepend_top_nav_mobile = '0';
}

// Check if ocm is enabled and the simple style is not selected.
if ( $side_widget_area === '1' && $side_widget_class !== 'simple' || true === $legacy_double_menu ) {

	$ocm_class = ( in_array($side_widget_class, array('fullscreen-split','fullscreen-inline-images' )) ) ? $side_widget_class. ' hidden' : $side_widget_class;

	?>

	<div id="slide-out-widget-area-bg" class="<?php echo esc_attr( $ocm_class ) . ' ' . esc_attr( $side_widget_overlay_opacity ); ?>">
		<?php

  
    nectar_ocm_menu_images();
    
		if ( $side_widget_class === 'fullscreen-alt' ) {
			echo '<div class="bg-inner"></div>';}
			?>
		</div>

		<div id="slide-out-widget-area" class="<?php echo esc_attr( $ocm_class ); ?>" data-dropdown-func="<?php echo esc_attr( $dropdown_func ); ?>" data-back-txt="<?php echo esc_attr__( 'Back', 'salient' ); ?>">

			<?php
			if ( $side_widget_class === 'fullscreen' ||
			$side_widget_class === 'fullscreen-alt' ||
			$side_widget_class === 'fullscreen-split' ||
			$side_widget_class === 'fullscreen-inline-images' ||
			( $theme_skin === 'material' && $side_widget_class === 'slide-out-from-right' ) ||
			( $theme_skin === 'material' && $side_widget_class === 'slide-out-from-right-hover' ) ) {

				echo '<div class="inner-wrap">';
			}


			$prepend_mobile_menu = ( $prepend_top_nav_mobile === '1' && $has_main_menu === 'true' && $user_set_side_widget_area !== 'off' ) ? 'true' : 'false'; ?>

			<div class="inner" data-prepend-menu-mobile="<?php echo esc_attr( $prepend_mobile_menu ); ?>">

				<a class="slide_out_area_close" href="#"><span class="screen-reader-text"><?php echo esc_html__('Close Menu','salient'); ?></span>
					<?php
					if ( $theme_skin !== 'material' ) {
						echo '<span class="icon-salient-x icon-default-style"></span>';
					} else {
						echo '<span class="close-wrap"> <span class="close-line close-line1"></span> <span class="close-line close-line2"></span> </span>';
					}
					?>
				</a>


				<?php

				nectar_hook_ocm_before_menu();

				if ( $user_set_side_widget_area === 'off' && true !== $legacy_double_menu && $separate_mobile_ocm != '1' || 
					$prepend_top_nav_mobile === '1' && $has_main_menu === 'true' && $separate_mobile_ocm != '1' ) {
					?>
					<div class="off-canvas-menu-container mobile-only" role="navigation">

						<?php
						$header_mobile_func = ( ! empty( $nectar_options['secondary-header-mobile-display'] ) ) ? $nectar_options['secondary-header-mobile-display'] : 'default';
						$using_secondary    = ( ! empty( $nectar_options['header_layout'] ) && $header_format != 'left-header' ) ? $nectar_options['header_layout'] : ' ';

						if ( ! empty( $nectar_options['secondary-header-text'] ) && $using_secondary === 'header_with_secondary' && $header_mobile_func !== 'display_full' ) {
							$nectar_secondary_link = ( ! empty( $nectar_options['secondary-header-link'] ) ) ? $nectar_options['secondary-header-link'] : '';
							echo '<div class="secondary-header-text">';
							if ( ! empty( $nectar_secondary_link ) ) {
								echo '<a href="' . esc_url( $nectar_secondary_link ) . '">';
							}
							echo wp_kses_post( $nectar_options['secondary-header-text'] );
							if ( ! empty( $nectar_secondary_link ) ) {
								echo '</a>';
							}
							echo '</div>';
						}
						?>

						<ul class="menu">
							<?php

							// use default top nav menu if ocm is not activated
							// but is needed for mobile when the mobile fixed nav is on
							if( has_nav_menu( 'top_nav' ) ) {
								wp_nav_menu(
									array(
										'walker'         => new Nectar_OCM_Icon_Walker(),
										'theme_location' => 'top_nav',
										'container'      => '',
										'items_wrap'     => '%3$s',
									)
								);
							}

							if ( $header_format === 'centered-menu' ||
								$header_format === 'menu-left-aligned' ||
								$header_format === 'centered-logo-between-menu' ) {

								if ( has_nav_menu( 'top_nav_pull_right' ) ) {
									wp_nav_menu(
										array(
											'walker'         => new Nectar_OCM_Icon_Walker(),
											'theme_location' => 'top_nav_pull_right',
											'container'  => '',
											'items_wrap' => '%3$s',
										)
									);
								}
							}

							if ( $header_format === 'centered-menu-bottom-bar' || 
							     $header_format === 'centered-logo-between-menu-alt'  ) {
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

								if ( has_nav_menu( 'top_nav_pull_right' ) ) {
									wp_nav_menu(
										array(
											'walker'         => new Nectar_OCM_Icon_Walker(),
											'theme_location' => 'top_nav_pull_right',
											'container'      => '',
											'items_wrap'     => '%3$s',
										)
									);
								}
							}

							?>

						</ul>

						<ul class="menu secondary-header-items">
							<?php

							// Material secondary nav in menu.
							$using_secondary = ( ! empty( $nectar_options['header_layout'] ) && $header_format != 'left-header' ) ? $nectar_options['header_layout'] : ' ';

							if ( $using_secondary === 'header_with_secondary' && has_nav_menu( 'secondary_nav' ) && $header_mobile_func !== 'display_full' ) {
								wp_nav_menu(
									array(
										'walker'         => new Nectar_Arrow_Walker_Nav_Menu(),
										'theme_location' => 'secondary_nav',
										'container'      => '',
										'items_wrap'     => '%3$s',
									)
								);
							}
							?>
						</ul>
					</div>
					<?php
				}

				if ( has_nav_menu( 'off_canvas_nav' ) && $user_set_side_widget_area != 'off' ||
					has_nav_menu( 'off_canvas_nav' ) && $separate_mobile_ocm == '1' ) {
					?>
					<div class="off-canvas-menu-container" role="navigation">
						<ul class="menu">
							<?php
							wp_nav_menu(
								array(
									'walker'         => new Nectar_OCM_Icon_Walker(),
									'theme_location' => 'off_canvas_nav',
									'container'      => '',
									'items_wrap'     => '%3$s',
								)
							);

							?>

						</ul>
					</div>

					<?php
				}

				nectar_hook_ocm_after_menu();

				nectar_hook_ocm_before_secondary_items();

				// Widget area.
				if ( $side_widget_class != 'slide-out-from-right-hover' ) {
					if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Off Canvas Menu' ) ) :
						elseif ( ! has_nav_menu( 'off_canvas_nav' ) && $user_set_side_widget_area != 'off' ) :
							?>

							<div class="widget">

							</div>
							<?php
						endif;

					}

					// Bottom meta.
					if( $side_widget_class === 'fullscreen-split' ) {
						get_template_part( 'includes/partials/footer/off-canvas-navigation-bottom-meta' );
					}

					nectar_hook_ocm_after_secondary_items();

					?>

				</div>

				<?php

					// Bottom meta.
					if( $side_widget_class !== 'fullscreen-split' ) {
						get_template_part( 'includes/partials/footer/off-canvas-navigation-bottom-meta' );
					}

					if ( $side_widget_class === 'fullscreen' ||
					$side_widget_class === 'fullscreen-alt' ||
					$side_widget_class === 'fullscreen-split' ||
          $side_widget_class === 'fullscreen-inline-images' ||
					( $theme_skin === 'material' && $side_widget_class === 'slide-out-from-right' ) ||
					( $theme_skin === 'material' && $side_widget_class === 'slide-out-from-right-hover' ) ) {
						echo '</div> <!--/inner-wrap-->';
					}
					?>

				</div>
		<?php }
