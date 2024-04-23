<?php
/**
* Header menu items and logo
*
* @package Salient WordPress Theme
* @subpackage Partials
* @version 13.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;

$nectar_header_options = nectar_get_header_variables();
$nectar_options        = get_nectar_theme_options();

// Bottom menu config
$alt_button_location = false;

$nectar_header_format = $nectar_header_options['header_format'];

if( $nectar_header_format === 'centered-menu-bottom-bar' &&
    $nectar_header_options['centered_menu_bottom_bar_align'] !== 'left' ) {
	$alt_button_location = true;
}
?>

<header id="top">
	<div class="container">
		<div class="row">
			<div class="col span_3">
				<?php 
				
				do_action('nectar_hook_before_logo');

				if( has_action('nectar_hook_mobile_header_before_logo') ) { ?>
					<span class="nectar-mobile-only"><?php nectar_hook_mobile_header_before_logo(); ?></span>
				<?php } 
				$nectar_logo_url = apply_filters( 'nectar_logo_url', esc_url(home_url()) );
				?>
				<a id="logo" href="<?php echo esc_url( $nectar_logo_url ); ?>" data-supplied-ml-starting-dark="<?php echo esc_attr( $nectar_header_options['using_mobile_logo_starting_dark'] ); ?>" data-supplied-ml-starting="<?php echo esc_attr( $nectar_header_options['using_mobile_logo_starting'] ); ?>" data-supplied-ml="<?php echo esc_attr( $nectar_header_options['using_mobile_logo'] ); ?>" <?php echo wp_kses_post( $nectar_header_options['logo_class'] ); ?>>
					<?php nectar_logo_output( $nectar_header_options['activate_transparency'], $nectar_header_options['side_widget_class'], $nectar_header_options['using_mobile_logo'] ); ?>
				</a>
				<?php

				$menu_label = false;
				$menu_label_class = '';

				if( ! empty( $nectar_options['header-menu-label'] ) && $nectar_options['header-menu-label'] === '1' ) {
					$menu_label       = true;
					$menu_label_class = ' using-label';
				}

				if ( $nectar_header_format === 'centered-menu-bottom-bar' ) {

					$has_pull_left_menu = ( has_nav_menu( 'top_nav_pull_left' ) ) ? 'true' : 'false';
					?>
					<nav class="left-side" data-using-pull-menu="<?php echo esc_attr( $has_pull_left_menu ); ?>">
						<?php
						// Pull left menu.
            
						do_action('nectar_hook_before_pull_left_items');

						if ( has_nav_menu( 'top_nav_pull_left' ) ) {
							wp_nav_menu(
								array(
									'walker'          => new Nectar_Arrow_Walker_Nav_Menu(),
									'theme_location'  => 'top_nav_pull_left',
									'container'       => '',
									'container_class' => 'pull-left-wrap',
									'items_wrap'      => '<ul id="%1$s" class="sf-menu">%3$s</ul>',
								)
							);
						}
						nectar_hook_pull_left_menu_items();

						if( ! empty( $nectar_options['enable_social_in_header'] ) && $nectar_options['enable_social_in_header'] === '1' ) {
						?>
						<ul class="nectar-social"><li id="social-in-menu" class="button_social_group"><?php nectar_header_social_icons( 'main-nav' ); ?> </li></ul>
				 	 <?php } ?>
					</nav>
					<nav class="right-side">
						<?php
						// Pull right menu.
						if ( has_nav_menu( 'top_nav_pull_right' ) ) {
							wp_nav_menu(
								array(
									'walker'          => new Nectar_Arrow_Walker_Nav_Menu(),
									'theme_location'  => 'top_nav_pull_right',
									'container'       => '',
									'container_class' => 'pull-left-wrap',
									'items_wrap'      => '<ul id="%1$s" class="sf-menu">%3$s</ul>',
								)
							);
						}
						nectar_hook_pull_right_menu_items();
						if( true === $alt_button_location ) {
						?>
						<ul class="buttons" data-user-set-ocm="<?php echo esc_attr( $nectar_header_options['user_set_side_widget_area'] ); ?>"><?php nectar_header_button_items(); ?></ul>
						<?php } ?>
						<?php if ( $nectar_header_options['side_widget_area'] === '1' || $nectar_header_options['side_widget_class'] === 'simple' ) { ?>
							<div class="slide-out-widget-area-toggle mobile-icon <?php echo esc_attr( $nectar_header_options['side_widget_class'] ); ?>" data-custom-color="<?php echo esc_attr($nectar_header_options['ocm_menu_btn_color']); ?>" data-icon-animation="simple-transform">
								<div> <a href="#sidewidgetarea" role="button" aria-label="<?php echo esc_attr__('Navigation Menu', 'salient'); ?>" aria-expanded="false" class="<?php echo 'closed' . esc_attr($menu_label_class); ?>">
									<?php if( true === $menu_label ) {
										echo '<i class="label">' . esc_html__('Menu','salient') .'</i>';
									} else {
										echo '<span class="screen-reader-text">'.esc_html__('Menu','salient').'</span>';
									} ?><span aria-hidden="true"> <i class="lines-button x2"> <i class="lines"></i> </i> </span> </a> </div>
							</div>
						<?php } ?>
					</nav>
				<?php } ?>
			</div><!--/span_3-->

			<div class="col span_9 col_last">
				<?php

         if( has_action('nectar_hook_mobile_header_menu_items') ) { ?>
					<div class="nectar-mobile-only mobile-header"><div class="inner"><?php nectar_hook_mobile_header_menu_items(); ?></div></div>
				<?php } 

				// Mobile icons.
				if ( $nectar_header_options['header_search'] != 'false' ) {
					?>
					<a class="mobile-search" href="#searchbox"><span class="nectar-icon icon-salient-search" aria-hidden="true"></span><span class="screen-reader-text"><?php echo esc_html__('search','salient'); ?></span></a>
					<?php
				}

				if ( $nectar_header_options['user_account_btn'] != 'false' ) {
					?>
					<a class="mobile-user-account" href="<?php echo esc_url( $nectar_header_options['user_account_btn_url'] ); ?>"><span class="normal icon-salient-m-user" aria-hidden="true"></span><span class="screen-reader-text"><?php echo esc_html__('account','salient'); ?></span></a>
					<?php
				}

				if ( ! empty( $nectar_options['enable-cart'] ) && $nectar_options['enable-cart'] === '1' ) {

					if ( $woocommerce ) {
						$nav_cart_style = ( isset( $nectar_options['ajax-cart-style'] ) ) ? $nectar_options['ajax-cart-style'] : 'default';
						?>

						<a id="mobile-cart-link" aria-label="<?php echo esc_attr__('Cart','salient'); ?>" data-cart-style="<?php echo esc_attr($nav_cart_style); ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><i class="icon-salient-cart"></i><div class="cart-wrap"><span><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?> </span></div></a>
						<?php
					}
				}
				?>
				<?php
				if ( $nectar_header_options['side_widget_area'] === '1' || $nectar_header_options['side_widget_class'] === 'simple' ) {
					?>
					<div class="slide-out-widget-area-toggle mobile-icon <?php echo esc_attr( $nectar_header_options['side_widget_class'] ); ?>" data-custom-color="<?php echo esc_attr($nectar_header_options['ocm_menu_btn_color']); ?>" data-icon-animation="simple-transform">
						<div> <a href="#sidewidgetarea" role="button" aria-label="<?php echo esc_attr__('Navigation Menu', 'salient'); ?>" aria-expanded="false" class="<?php echo 'closed' . esc_attr($menu_label_class); ?>">
							<?php if( true === $menu_label ) {
								echo '<i class="label">' . esc_html__('Menu','salient') .'</i>';
							}
							else {
								echo '<span class="screen-reader-text">'.esc_html__('Menu','salient').'</span>';
							} ?><span aria-hidden="true"> <i class="lines-button x2"> <i class="lines"></i> </i> </span>
						</a></div>
					</div>
				<?php } ?>

				<?php
				if ( $nectar_header_format === 'left-header' ) {
					echo '<div class="nav-outer">';
				}
				?>
					<nav>
						<?php 
						// Centered Logo Between Menu Alt.
						if( in_array($nectar_header_format, array('centered-logo-between-menu-alt') ) ) { 

              do_action('nectar_hook_before_pull_left_items');

							if( has_nav_menu( 'top_nav_pull_left' ) ) {
								wp_nav_menu(
									array(
										'walker'          => new Nectar_Arrow_Walker_Nav_Menu(),
										'theme_location'  => 'top_nav_pull_left',
										'container'      => '',
										'menu_class' => 'sf-menu',      
									)
								);
							}
						}
						else {  
              
              do_action('nectar_hook_before_menu_items');
              
              ?>
							<ul class="sf-menu">
								<?php
								if ( $nectar_header_options['has_main_menu'] === 'true' ) {

									wp_nav_menu(
										array(
											'walker'         => new Nectar_Arrow_Walker_Nav_Menu(),
											'theme_location' => 'top_nav',
											'container'      => '',
											'items_wrap'     => '%3$s',
										)
									);
								} else {
									echo '<li class="no-menu-assigned"><a href="#"></a></li>';
								}

								if ( ! empty( $nectar_options['enable_social_in_header'] ) &&
								$nectar_options['enable_social_in_header'] === '1' &&
								$nectar_header_options['using_secondary'] !== 'header_with_secondary' &&
								$nectar_header_format !== 'menu-left-aligned' &&
								$nectar_header_format !== 'centered-menu' &&
								$nectar_header_format !== 'left-header' &&
								$nectar_header_format !== 'centered-menu-bottom-bar' ) {

									echo '<li id="social-in-menu" class="button_social_group">';
									nectar_header_social_icons( 'main-nav' );
									echo '</li>';
								}

								?>
							</ul>
						<?php
						}
						
						if ( false === in_array($nectar_header_format, array('menu-left-aligned','centered-logo-between-menu-alt') ) &&
						     false === $alt_button_location ) { ?>
							<ul class="buttons sf-menu" data-user-set-ocm="<?php echo esc_attr( $nectar_header_options['user_set_side_widget_area'] ); ?>">

								<?php

								if ( ! empty( $nectar_options['enable_social_in_header'] ) &&
								$nectar_options['enable_social_in_header'] === '1' &&
								$nectar_header_options['using_secondary'] !== 'header_with_secondary' &&
								$nectar_header_format === 'centered-menu' ) {

									echo '<li id="social-in-menu" class="button_social_group">';
									nectar_header_social_icons( 'main-nav' );
									echo '</li>';
								}

								// Pull right.
								if ( $nectar_header_format === 'centered-menu' &&
								$nectar_header_options['using_pr_menu'] === 'true' ||
								$nectar_header_format === 'centered-logo-between-menu' &&
								$nectar_header_options['using_pr_menu'] === 'true' ) {
									wp_nav_menu(
										array(
											'walker'         => new Nectar_Arrow_Walker_Nav_Menu(),
											'theme_location' => 'top_nav_pull_right',
											'container'      => '',
											'items_wrap'     => '%3$s',
										)
									);
									nectar_hook_pull_right_menu_items();
								}

								nectar_hook_before_button_menu_items();

								nectar_header_button_items();
								?>

							</ul>
						<?php } ?>

					</nav>

					<?php
					if ( $nectar_header_format === 'left-header' ) {
						echo '</div>';
					}

					if ( $nectar_header_format === 'centered-menu' ||
					$nectar_header_format === 'centered-logo-between-menu' ) {
						nectar_logo_spacing();
					}
					?>

				</div><!--/span_9-->

				<?php if ( in_array($nectar_header_format, array('menu-left-aligned','centered-logo-between-menu-alt') ) ) { ?>
					<div class="right-aligned-menu-items">
						<nav>
							<ul class="buttons sf-menu" data-user-set-ocm="<?php echo esc_attr( $nectar_header_options['user_set_side_widget_area'] ); ?>">

								<?php
								// Pull right.
								if ( $nectar_header_options['using_pr_menu'] === 'true' ) {
									wp_nav_menu(
										array(
											'walker'         => new Nectar_Arrow_Walker_Nav_Menu(),
											'theme_location' => 'top_nav_pull_right',
											'container'      => '',
											'items_wrap'     => '%3$s',
										)
									);
									nectar_hook_pull_right_menu_items();
								}

								nectar_hook_before_button_menu_items();

								nectar_header_button_items();
								?>

							</ul>

							<?php
							if ( ! empty( $nectar_options['enable_social_in_header'] ) &&
							$nectar_options['enable_social_in_header'] === '1' &&
							$nectar_header_options['using_secondary'] !== 'header_with_secondary' ) {

								echo '<ul><li id="social-in-menu" class="button_social_group">';
								nectar_header_social_icons( 'main-nav' );
								echo '</li></ul>';
							}
							?>
						</nav>
					</div><!--/right-aligned-menu-items-->

					<?php
				} 
				elseif ( $nectar_header_format === 'left-header' ) {

					if ( ! empty( $nectar_options['enable_social_in_header'] ) &&
					$nectar_options['enable_social_in_header'] === '1' &&
					$nectar_header_options['using_secondary'] !== 'header_with_secondary' ) {
						echo '<div class="button_social_group"><ul><li id="social-in-menu">';
						nectar_header_social_icons( 'main-nav' );
						echo '</li></ul></div>';
					}
				}
				?>

			</div><!--/row-->
			<?php
			
			$legacy_double_menu = nectar_legacy_mobile_double_menu();
			
			if( $nectar_header_options['side_widget_class'] === 'simple' || true === $legacy_double_menu ) {
				get_template_part( 'includes/partials/header/classic-mobile-nav' );
			}
			?>
		</div><!--/container-->
	</header>