<?php
/**
 * Enqueue styles
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 12.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register/Enqueue frontend CSS.
 *
 * @since 1.0
 */
function nectar_main_styles() {

		global $nectar_get_template_directory_uri;
		global $nectar_options;

		 $nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
		 $nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

		 $nectar_theme_version = nectar_get_theme_version();

		$nectar_dev_mode = apply_filters('nectar_dev_mode', false);
		$src_dir = ( $nectar_dev_mode == true ) ? 'src' : 'build';

		 // Core.
		 wp_register_style( 'main-styles', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/style.css', '', $nectar_theme_version );
		 wp_register_style( 'main-styles-non-critical', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/style-non-critical.css', '', $nectar_theme_version );
		 wp_register_style( 'salient-grid-system-legacy', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/grid-system-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'salient-grid-system', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/grid-system.css', '', $nectar_theme_version );

		 // WooCommerce
		 wp_register_style( 'nectar-product-style-minimal', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/woocommerce/product-style-minimal.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-product-style-classic', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/woocommerce/product-style-classic.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-product-style-text-on-hover', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/woocommerce/product-style-text-on-hover.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-product-style-material', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/woocommerce/product-style-material.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-woocommerce-non-critical', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/woocommerce/woocommerce-non-critical.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-woocommerce-single', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/woocommerce/product-single.css', '', $nectar_theme_version );
		 wp_register_style( 'woocommerce', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/woocommerce.css', '', $nectar_theme_version );
     
		// Icons
		if( isset($nectar_options['rm-legacy-icon-css']) &&
			!empty($nectar_options['rm-legacy-icon-css']) &&
			'1' === $nectar_options['rm-legacy-icon-css'] ) {
			wp_register_style( 'font-awesome', $nectar_get_template_directory_uri . '/css/font-awesome.min.css', '', '4.7.1' );
		} else {
			wp_register_style( 'font-awesome', $nectar_get_template_directory_uri . '/css/font-awesome-legacy.min.css', '', '4.7.1' );
		}
		 wp_register_style( 'iconsmind', $nectar_get_template_directory_uri . '/css/iconsmind.css', '', '12.5' );
		 wp_register_style( 'iconsmind-core', $nectar_get_template_directory_uri . '/css/iconsmind-core.css', '', $nectar_theme_version );
		 wp_register_style( 'linea', $nectar_get_template_directory_uri . '/css/fonts/svg/font/arrows_styles.css' );
		 wp_register_style( 'nectar-steadysets', $nectar_get_template_directory_uri . '/css/steadysets.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-brands', $nectar_get_template_directory_uri . '/css/nectar-brands.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-linecon', $nectar_get_template_directory_uri . '/css/linecon.css', '', $nectar_theme_version );


		 // Header Formats.
		 wp_register_style( 'nectar-header-layout-left', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-left.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-left-aligned', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-menu-left-aligned.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-bottom-bar', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-centered-bottom-bar.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-menu', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-centered-menu.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-menu-under-logo', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-centered-menu-under-logo.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-logo-between-menu', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-centered-logo-between-menu.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-layout-centered-logo-between-menu-alt', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-layout-centered-logo-between-menu-alt.css', '', $nectar_theme_version );

		 wp_register_style( 'nectar-header-secondary-nav', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-secondary-nav.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-header-perma-transparent', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/header/header-perma-transparent.css', '', $nectar_theme_version );

		 wp_register_style( 'nectar-boxed', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/boxed.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-single-styles', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/single.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-widget-posts', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/widget-nectar-posts.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-search-results', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/search.css', '', $nectar_theme_version );

		 // Elements.
		 wp_register_style( 'nectar-image-with-hotspots', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-image-with-hotspots.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-recent-posts', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-recent-posts.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-testimonial', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-testimonial.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-flip-box', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-flip-box.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-fancy-box', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-fancy-box.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-post-grid', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-post-grid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-category-grid', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-category-grid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-icon-list', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-icon-list.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-tabbed-section', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-tabbed-section.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-team-member', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-team-member.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-pricing-table', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-pricing-table.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-wpb-column-border-legacy', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-wpb-column-border-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-wpb-column-border', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-wpb-column-border.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-horizontal-list-item', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-horizontal-list-item.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-video-lightbox', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-video-lightbox.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-product-carousel', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-product-carousel.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-animated-title', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-animated-title.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-cascading-images', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-cascading-images.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-highlighted-text', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-highlighted-text.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-toggle-panels', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-toggles.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-scrolling-text', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-scrolling-text.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-interactive-map', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-interactive-map.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-clients', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-clients.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-fancy-unordered-list', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-fancy-unordered-list.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-milestone', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-milestone.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-morphing-outline', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-morphing-outline.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-page-submenu', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-page-submenu.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-parallax-image-grid', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-parallax-gallery-grid.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-food-item', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-food-item.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-rotating-words-title', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-rotating-words-title.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-button-legacy', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-button-legacy.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-icon-with-text', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-icon-with-text.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-vc-icon', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-vc-icon-element.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-element-vc-separator', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/element-vc-separator.css', '', $nectar_theme_version );

		 wp_register_style( 'nectar-element-asset-reveal-animation', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/elements/asset-reveal-animation.css', '', $nectar_theme_version );

		 // Blog.
		wp_register_style( 'nectar-blog-masonry-core', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/blog/masonry-core.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-blog-masonry-classic-enhanced', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/blog/masonry-classic-enhanced.css', array('nectar-blog-masonry-core'), $nectar_theme_version );
		wp_register_style( 'nectar-blog-masonry-meta-overlaid', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/blog/masonry-meta-overlaid.css', array('nectar-blog-masonry-core'), $nectar_theme_version );
		wp_register_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/blog/auto-masonry-meta-overlaid-spaced.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-blog-standard-minimal', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/blog/standard-minimal.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-blog-standard-featured-left', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/blog/standard-featured-left.css', '', $nectar_theme_version );

		 // Off canvas menu styles.
		wp_register_style( 'nectar-ocm-core', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/off-canvas/core.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-ocm-slide-out-right-hover', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/off-canvas/slide-out-right-hover.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-ocm-fullscreen-legacy', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/off-canvas/fullscreen-legacy.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-ocm-fullscreen-split', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/off-canvas/fullscreen-split.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-ocm-simple', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/off-canvas/simple-dropdown.css', '', $nectar_theme_version );
		wp_register_style( 'nectar-ocm-slide-out-right-material', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/off-canvas/slide-out-right-material.css', '', $nectar_theme_version );

		 // Third Party.
		 wp_register_style( 'fullpage', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/fullpage.css', '', $nectar_theme_version );
		 wp_register_style( 'twentytwenty', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/twentytwenty.css' );
		 wp_register_style( 'magnific', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/magnific.css', '', '8.6.0' );
		 wp_register_style( 'fancyBox', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/jquery.fancybox.css', '', '3.3.1' );
		 wp_register_style( 'box-roll', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/box-roll.css', '', $nectar_theme_version);
		 wp_register_style( 'leaflet', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/leaflet.css', '1.3.1' );
		 wp_register_style( 'nectar-flickity', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/flickity.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-caroufredsel', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/caroufredsel.css', '', $nectar_theme_version );
		 wp_register_style( 'nectar-owl-carousel', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/owl-carousel.css', '', $nectar_theme_version );
		 wp_register_style( 'responsive', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/responsive.css', '', $nectar_theme_version );
		 wp_register_style( 'select2', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/plugins/select2.css', '', '4.0.1' );
		 wp_register_style( 'non-responsive', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/non-responsive.css' );
		 wp_register_style( 'skin-original', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/skin-original.css', '', $nectar_theme_version );
		 wp_register_style( 'skin-ascend', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/ascend.css', '', $nectar_theme_version );
		 wp_register_style( 'skin-material', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/skin-material.css', '', $nectar_theme_version );

		 global $post;

		 if ( ! is_object( $post ) ) {
 			$post = (object) array(
 				'post_content' => ' ',
 				'ID'           => ' ',
 			);
 		}


		// Boxed style.
	 	$nectar_using_boxed = ( ! empty( $nectar_options['boxed_layout'] ) ) ? $nectar_options['boxed_layout'] : 'off';
	 	if( $nectar_using_boxed === '1') {
	 		wp_enqueue_style( 'nectar-boxed' );
	 	}
    
    // Font awesome.
    $use_font_awesome = ( isset($nectar_options['rm-font-awesome']) && '1' === $nectar_options['rm-font-awesome'] ) ? false : true;
    if( $use_font_awesome ) {
	    wp_enqueue_style( 'font-awesome' );
    }

	 // Grid system.
	 if( function_exists('nectar_use_flexbox_grid') && true === nectar_use_flexbox_grid() ) {
		 /* Salient provides a modern flexbox grid system as of v10.6 as long
		 as the Salient core and Salient page builder plugins are up to date. */
		 $nectar_modern_grid_compat = true;
	 } else {
		 $nectar_modern_grid_compat = false;
	 }

	 if( true === $nectar_modern_grid_compat ) {
		 wp_enqueue_style( 'salient-grid-system' );
	 } else {
		 wp_enqueue_style( 'salient-grid-system-legacy' );
	 }

	 // Main Salient styles.
	 wp_enqueue_style( 'main-styles' );

	 // Header layouts.
	 $header_format = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';

	 if( $header_format === 'left-header' ) {
		 wp_enqueue_style( 'nectar-header-layout-left' );
	 }
	else if( $header_format === 'menu-left-aligned' ) {
		wp_enqueue_style( 'nectar-header-layout-left-aligned' );
	}
	 else if( $header_format === 'centered-menu-bottom-bar' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-bottom-bar' );
	 }
	 else if ( $header_format === 'centered-menu-under-logo' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-menu-under-logo' );
	 }
	 else if ( $header_format === 'centered-menu' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-menu' );
	 }
	 else if( $header_format === 'centered-logo-between-menu' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-logo-between-menu' );
	 }
	 else if( $header_format === 'centered-logo-between-menu-alt' ) {
		 wp_enqueue_style( 'nectar-header-layout-centered-logo-between-menu-alt' );
	 }

	// Secondary navigation bar.
	$header_secondary_format = ( ! empty( $nectar_options['header_layout'] ) ) ? $nectar_options['header_layout'] : 'standard';
	if( $header_secondary_format === 'header_with_secondary') {
		wp_enqueue_style( 'nectar-header-secondary-nav' );
	}

	 // Permanent transparent navigation option.
	 $header_trans = ( ! empty( $nectar_options['transparent-header'] ) ) ? $nectar_options['transparent-header'] : '0';
	 $header_perma_trans = ( ! empty( $nectar_options['header-permanent-transparent'] ) ) ? $nectar_options['header-permanent-transparent'] : '0';

	 if( $header_trans === '1' && $header_perma_trans === '1' ) {
		 wp_enqueue_style( 'nectar-header-perma-transparent' );
	 }


	 // Single posts.
	 if( is_single() && !is_singular( 'product' ) ) {
		 wp_enqueue_style( 'nectar-single-styles' );
	 }

	 // Testimonials.
	 if ( NectarElAssets::locate(array('[testimonial_slider','[nectar_single_testimonial')) ) {
		 wp_enqueue_style( 'nectar-element-testimonial' );
	 }

	 // Image with hotspots.
	 if ( NectarElAssets::locate(array('[nectar_image_with_hotspots')) ) {
		 wp_enqueue_style( 'nectar-image-with-hotspots' );
	 }

	 // Fancy box.
	 if ( NectarElAssets::locate(array('[fancy_box')) ) {
		 wp_enqueue_style( 'nectar-element-fancy-box' );
	 }

	 // Flip box.
	 if ( NectarElAssets::locate(array('[nectar_flip_box')) ) {
		 wp_enqueue_style( 'nectar-element-flip-box' );
	 }

	 // Scrolling Text.
	 if ( NectarElAssets::locate(array('[nectar_scrolling_text')) ) {
		 wp_enqueue_style( 'nectar-element-scrolling-text' );
	 }

	 // Animated Title.
	 if ( NectarElAssets::locate(array('[nectar_animated_title')) ) {
		 wp_enqueue_style( 'nectar-element-animated-title' );
	 }

	 // Highlighted Text.
	 if ( NectarElAssets::locate(array('[nectar_highlighted_text')) ) {
		 wp_enqueue_style( 'nectar-element-highlighted-text' );
	 }

	 // Post grid.
	 if ( NectarElAssets::locate(array('[nectar_post_grid')) ) {
		 wp_enqueue_style( 'nectar-element-post-grid' );
	 }

	 // Category grid.
	 if ( NectarElAssets::locate(array('[nectar_category_grid')) ) {
		 wp_enqueue_style( 'nectar-element-category-grid' );
	 }

	 // Icon list.
	 if ( NectarElAssets::locate(array('[nectar_icon_list')) ) {
		 wp_enqueue_style( 'nectar-element-icon-list' );
	 }

	 // Tabbed section.
	 if ( NectarElAssets::locate(array('[tabbed_section')) ) {
		 wp_enqueue_style( 'nectar-element-tabbed-section' );
	 }

	 // Team member.
	 if ( NectarElAssets::locate(array('[team_member')) ) {
		 wp_enqueue_style( 'nectar-element-team-member' );
	 }

	 // Pricing table.
	 if ( NectarElAssets::locate(array('[pricing_table')) ) {
		 wp_enqueue_style( 'nectar-element-pricing-table' );
	 }

	 // Horizontal List Item.
	 if ( NectarElAssets::locate(array('[nectar_horizontal_list_item')) ) {
		 wp_enqueue_style( 'nectar-element-horizontal-list-item' );
	 }

	 // Cascading Images.
	 if( NectarElAssets::locate(array('[nectar_cascading_images')) ) {
		 wp_enqueue_style( 'nectar-element-cascading-images' );
	 }

	 // Toggle Panels.
	 if( NectarElAssets::locate(array('[toggle')) ) {
		 wp_enqueue_style( 'nectar-element-toggle-panels' );
	 }

	 // Video Lightbox.
	 if ( NectarElAssets::locate(array('[nectar_video_lightbox')) ) {
		 wp_enqueue_style( 'nectar-element-video-lightbox' );
	 }

	 // Interactive Map.
	 if( NectarElAssets::locate(array('[nectar_gmap')) || is_page_template( 'template-contact.php' ) ) {
		 wp_enqueue_style( 'nectar-element-interactive-map' );
	 }

	 // Clients.
	 if( NectarElAssets::locate(array('[clients')) ) {
		 wp_enqueue_style( 'nectar-element-clients' );
	 }

	 // Fancy UL.
	 if( NectarElAssets::locate(array('[fancy-ul')) ) {
		 wp_enqueue_style( 'nectar-element-fancy-unordered-list' );
	 }

	 // Food Item.
	 if( NectarElAssets::locate(array('[nectar_food_menu')) ) {
		 wp_enqueue_style( 'nectar-element-food-item' );
	 }

	 // Milestone.
	 if( NectarElAssets::locate(array('[milestone')) ) {
		 wp_enqueue_style( 'nectar-element-milestone' );
	 }

	 // Morphing Outline.
	 if( NectarElAssets::locate(array('[morphing_outline')) ) {
		 wp_enqueue_style( 'nectar-element-morphing-outline' );
	 }

	 // Page Submenu.
	 if( NectarElAssets::locate(array('[nectar_rotating_words_title')) ) {
		wp_enqueue_style( 'nectar-element-rotating-words-title' );
	}

	 // Page Submenu.
	if( NectarElAssets::locate(array('[page_submenu')) ) {
		 wp_enqueue_style( 'nectar-element-page-submenu' );
	 }

	 // Owl Carousel.
	 if( NectarElAssets::locate(array('owl_carousel')) ) {
		 wp_enqueue_style( 'nectar-owl-carousel' );
	 }

	 // VC Separator.
	 if( NectarElAssets::locate(array('[vc_text_separator', '[vc_zigzag')) ) {
		 wp_enqueue_style( 'nectar-element-vc-separator' );
	 }
	 // VC Icon.
	 if( NectarElAssets::locate(array('[vc_text_separator','[vc_text_icon','[vc_btn','[vc_icon_element')) ) {
		 wp_enqueue_style( 'nectar-element-vc-icon' );
	 }

	 // Text with icon.
	 if( NectarElAssets::locate(array('[text-with-icon')) ) {
		 wp_enqueue_style( 'nectar-element-icon-with-text' );
	 }

	 // Caroufredsel.
	 if( NectarElAssets::locate(array('[recent_projects', '[carousel easing', '[carousel auto', 'carouFredSel', 'carousel="true"')) || is_page_template( 'template-home-1.php' )  ) {
		 wp_enqueue_style( 'nectar-caroufredsel' );
	 }

	 // Image grid - parallax style.
	 if( NectarElAssets::locate(array('[vc_gallery')) && NectarElAssets::locate(array('parallax_image_grid')) ) {
		 wp_enqueue_style( 'nectar-element-parallax-image-grid' );
	 }



	 // Product Carousel.
	 if ( NectarElAssets::locate(array('[nectar_woo_products')) ) {
		 if( NectarElAssets::locate(array('carousel="1"')) || NectarElAssets::locate(array("carousel='1'")) ) {
			 wp_enqueue_style( 'nectar-element-product-carousel' );
		 }
	 }

	 // Reveal Animation.
	 if( NectarElAssets::locate(array('ro-reveal-from')) ) {
		  wp_enqueue_style( 'nectar-element-asset-reveal-animation' );
	 }

	 // Legacy Button.
	 $theme_skin = NectarThemeManager::$skin;

	 if( 'material' === $theme_skin && NectarElAssets::locate(array('[button')) ) {
		 wp_enqueue_style( 'nectar-element-button-legacy' );
	 }


	 // Column border.
	 if ( NectarElAssets::locate(array('column_border_color="#','column_border_color="rgb')) ) {

		 if( true === $nectar_modern_grid_compat ) {
			 wp_enqueue_style( 'nectar-element-wpb-column-border' );
		 } else {
			 wp_enqueue_style( 'nectar-element-wpb-column-border-legacy' );
		 }

	 }

	 // Recent posts.
	 if ( NectarElAssets::locate(array('[recent_posts')) ||
		 is_page_template( 'template-home-2.php' ) ||
		 is_page_template( 'template-home-3.php' ) ) {
		 wp_enqueue_style( 'nectar-element-recent-posts' );
	 }


	 // Single post using related posts.
	 $nectar_using_related_posts = ( ! empty( $nectar_options['blog_related_posts'] ) ) ? $nectar_options['blog_related_posts'] : 'off';
	 if( is_single() && $nectar_using_related_posts === '1') {
		 wp_enqueue_style( 'nectar-element-recent-posts' );
	 }


	 if( defined('WPCF7_VERSION') ) {
		wp_enqueue_style( 'nectar-cf7', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/cf7.css', '', $nectar_theme_version );
	}
	if( defined('WPFORMS_VERSION') ) {
		wp_enqueue_style( 'nectar-wpforms', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/wpforms.css', '', $nectar_theme_version );
	}
	if( class_exists( 'bbPress' ) ) {
		wp_enqueue_style( 'nectar-basic-bbpress', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/bbpress.css', '', $nectar_theme_version );
	}
	if( class_exists( 'BuddyPress' ) ) {
		wp_enqueue_style( 'nectar-basic-buddypress', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/buddypress.css', '', $nectar_theme_version );
	}
	if( class_exists( 'Tribe__Main' ) ) {
		wp_enqueue_style( 'nectar-basic-events-calendar', $nectar_get_template_directory_uri . '/css/'.$src_dir.'/third-party/events-calendar.css', '', $nectar_theme_version );
	}

	// Icons
	if ( NectarElAssets::locate(array('steadysets-')) ) {
		wp_enqueue_style('nectar-steadysets');
	}
	if( NectarElAssets::locate(array('linecon')) ) {
		wp_enqueue_style('nectar-linecon');
	}
	if( NectarElAssets::locate(array('nectar-brands')) ) {
		wp_enqueue_style('nectar-brands');
	}



	// Default Salient font (Open Sans).
	$nectar_default_font = ( ! empty( $nectar_options['default-theme-font'] ) ) ? $nectar_options['default-theme-font'] : 'from_google';

	if( 'from_google' === $nectar_default_font ) {
		// Load from Google.

		$display_swap_str = '';

		if( isset($nectar_options['typography_font_swap']) && !empty($nectar_options['typography_font_swap']) && '1' === $nectar_options['typography_font_swap'] ) {
			$display_swap_str = '&display=swap';
		}

		wp_enqueue_style( 'nectar_default_font_open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans%3A300%2C400%2C600%2C700&subset=latin%2Clatin-ext'.$display_swap_str, false, null, 'all' );
	} else if( 'from_theme' === $nectar_default_font ) {
		// Load locally.
		
		$display_swap_prop = '';
		if( isset($nectar_options['typography_font_swap']) && !empty($nectar_options['typography_font_swap']) && '1' === $nectar_options['typography_font_swap'] ) {
			$display_swap_prop = 'font-display: swap;';
		}
	
		$nectar_default_font_css = "
		@font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-Light.woff') format('woff');
		     font-weight:300;
		     font-style:normal; ".$display_swap_prop."
		}
		 @font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-Regular.woff') format('woff');
		     font-weight:400;
		     font-style:normal; ".$display_swap_prop."
		}
		 @font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-SemiBold.woff') format('woff');
		     font-weight:600;
		     font-style:normal; ".$display_swap_prop."
		}
		 @font-face{
		     font-family:'Open Sans';
		     src:url('". get_template_directory_uri() ."/css/fonts/OpenSans-Bold.woff') format('woff');
		     font-weight:700;
		     font-style:normal; ".$display_swap_prop."
		}";

		wp_add_inline_style( 'main-styles', $nectar_default_font_css );
	}


	// Front end editor needs all.
	if ( $nectar_using_VC_front_end_editor ) {
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_style( 'iconsmind' );
		wp_enqueue_style( 'nectar-brands' );
		wp_enqueue_style( 'fullpage' );
		wp_enqueue_style( 'nectar-slider' );
		wp_enqueue_style( 'nectar-portfolio' );
		wp_enqueue_style( 'nectar-flickity' );
		wp_enqueue_style( 'nectar-owl-carousel' );
		wp_enqueue_style( 'nectar-caroufredsel' );
		wp_enqueue_style( 'twentytwenty' );
		wp_enqueue_style( 'linea' );
		wp_enqueue_style( 'leaflet' );
		wp_enqueue_style( 'nectar-element-recent-posts' );
		wp_enqueue_style( 'nectar-header-layout-left' );
		wp_enqueue_style( 'nectar-single-styles' );
		wp_enqueue_style( 'nectar-element-testimonial' );
		wp_enqueue_style( 'nectar-image-with-hotspots' );
		wp_enqueue_style( 'nectar-element-fancy-box' );
		wp_enqueue_style( 'nectar-element-flip-box' );
		wp_enqueue_style( 'nectar-element-category-grid' );
		wp_enqueue_style( 'nectar-element-post-grid' );
		wp_enqueue_style( 'nectar-element-icon-list' );
		wp_enqueue_style( 'nectar-element-tabbed-section' );
		wp_enqueue_style( 'nectar-element-team-member' );
		wp_enqueue_style( 'nectar-element-pricing-table' );
		wp_enqueue_style( 'nectar-element-wpb-column-border' );
		wp_enqueue_style( 'nectar-element-horizontal-list-item' );
		wp_enqueue_style( 'nectar-element-video-lightbox' );
		wp_enqueue_style( 'nectar-element-product-carousel' );
		wp_enqueue_style( 'nectar-element-animated-title' );
		wp_enqueue_style( 'nectar-element-cascading-images' );
		wp_enqueue_style( 'nectar-element-highlighted-text' );
		wp_enqueue_style( 'nectar-element-toggle-panels' );
		wp_enqueue_style( 'nectar-element-scrolling-text' );
		wp_enqueue_style( 'nectar-element-interactive-map' );
		wp_enqueue_style( 'nectar-element-clients' );
		wp_enqueue_style( 'nectar-element-fancy-unordered-list' );
		wp_enqueue_style( 'nectar-element-milestone' );
		wp_enqueue_style( 'nectar-element-morphing-outline' );
		wp_enqueue_style( 'nectar-element-page-submenu' );
		wp_enqueue_style( 'nectar-element-parallax-image-grid' );
		wp_enqueue_style( 'nectar-element-food-item' );
		wp_enqueue_style( 'nectar-element-rotating-words-title' );
		wp_enqueue_style( 'nectar-element-vc-separator' );
		wp_enqueue_style( 'nectar-element-vc-icon' );
		wp_enqueue_style( 'nectar-element-asset-reveal-animation' );
		wp_enqueue_style( 'nectar-element-icon-with-text' );
		wp_enqueue_style( 'nectar-blog-masonry-core' );
		wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
		wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
		wp_enqueue_style( 'nectar-blog-standard-minimal' );
		wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
		wp_enqueue_style( 'nectar-blog-standard-featured-left' );

	}


	// Remove WP CSS.
	if( isset($nectar_options['rm-block-editor-css']) &&
			!empty($nectar_options['rm-block-editor-css']) &&
			'1' === $nectar_options['rm-block-editor-css'] ) {
		
		$post_content_length = ( $post && isset($post->post_content) ) ? strlen( $post->post_content ) : 0;
		
		if ( !NectarElAssets::locate(array('<!-- wp:')) || $post_content_length < 100 ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wc-block-style' );
			wp_dequeue_style( 'wc-blocks-style' );
		}
	
	}


}

add_action( 'wp_enqueue_scripts', 'nectar_main_styles' );



/**
 * Page specific frontend CSS.
 *
 * @since 1.0
 */
function nectar_page_sepcific_styles() {

	global $post;
	global $nectar_options;

	if ( ! is_object( $post ) ) {
		$post = (object) array(
			'post_content' => ' ',
			'ID'           => ' ',
		);
	}

	// Home templates.
	if ( is_page_template( 'template-home-1.php' ) ||
	is_page_template( 'template-home-2.php' ) ||
	is_page_template( 'template-home-3.php' ) ||
	is_page_template( 'template-home-4.php' ) ) {
		wp_enqueue_style( 'orbit' );
		
		$nectar_orbit_css = '.home-wrap {
			padding-top:3em;
			margin-bottom:0;
			padding-bottom:0;
			position:relative;
			z-index:100;
			background-color:#f8f8f8
		}
		html:not(.js) .home-wrap{
			padding-top:0
		}
		.home-wrap .full-width-section.first-section{
			margin-top:-37px
		}';
		
		wp_add_inline_style( 'orbit', $nectar_orbit_css );
		
	}

	// Full page option.
	$page_full_screen_rows = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows', true ) : '';
	if ( $page_full_screen_rows === 'on' ) {
		wp_enqueue_style( 'fullpage' );
	}

	// Nectar slider.
	if ( NectarElAssets::locate(array('[nectar_slider','type="nectarslider_style"')) ) {
		wp_enqueue_style( 'nectar-slider' );
	}

	// Portfolio.
	if ( NectarElAssets::locate(array('nectar_portfolio','recent_projects', 'type="image_grid"')) ||
	   is_page_template( 'template-portfolio.php' ) ||
		 is_post_type_archive( 'portfolio' ) ||
		 is_singular( 'portfolio' ) ||
		 is_tax( 'project-attributes' ) ||
		 is_tax( 'project-type' ) ) {
			wp_enqueue_style( 'nectar-portfolio' );
	}

	// Blog std style containing image gallery grid - non archive.
	if ( NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('layout="std-blog-')) && NectarElAssets::locate(array('blog_standard_style="classic')) ||
		NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('layout="std-blog-')) && NectarElAssets::locate(array('blog_standard_style="minimal')) ) {
		wp_enqueue_style( 'nectar-portfolio' );
	}

	// Blog styles - page builder element.
	$nectar_using_related_posts = ( ! empty( $nectar_options['blog_related_posts'] ) ) ? $nectar_options['blog_related_posts'] : 'off';
	$nectar_related_posts_style = ( isset( $nectar_options['blog_related_posts_style'] ) && ! empty( $nectar_options['blog_related_posts_style'] ) ) ? $nectar_options['blog_related_posts_style'] : 'material';

	$posttype                     = get_post_type( $post );
	$nectar_on_blog_archive_check = ( is_archive() || is_author() || is_category() || is_home() || is_tag() );
	$nectar_blog_type             = ( ! empty( $nectar_options['blog_type'] ) ) ? $nectar_options['blog_type'] : 'masonry-blog-fullwidth';
	$nectar_blog_std_style        = ( ! empty( $nectar_options['blog_standard_type'] ) ) ? $nectar_options['blog_standard_type'] : 'featured_img_left';
	$nectar_blog_masonry_style    = ( ! empty( $nectar_options['blog_masonry_type'] ) ) ? $nectar_options['blog_masonry_type'] : 'auto_meta_overlaid_spaced';


	// Page builder elements.
	if( NectarElAssets::locate(array('[nectar_blog')) ||
      	NectarElAssets::locate(array('[recent_posts')) ) {

        //// Core.
        if( NectarElAssets::locate(array('blog_masonry_style="material"')) || 
            NectarElAssets::locate(array('blog_masonry_style="classic"')) ) {
          wp_enqueue_style( 'nectar-blog-masonry-core' );
        }
        
				//// Classic enhanced.
				if( NectarElAssets::locate(array('classic_enhanced')) ) {
					wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
				}

				//// Meta Overlaid.
				if( NectarElAssets::locate(array('blog_masonry_style="meta_overlaid"')) ) {
					wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
				}

				//// Auto Masonry Meta Overlaid.
				if( NectarElAssets::locate(array('auto_meta_overlaid_spaced')) ) {
					wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
				}

				if( NectarElAssets::locate(array('standard_style="minimal"')) ) {
					wp_enqueue_style( 'nectar-blog-standard-minimal' );
				}

				if( NectarElAssets::locate(array('featured_img_left')) ) {
					wp_enqueue_style( 'nectar-blog-standard-featured-left' );
				}

				// Inherit from theme options.
				if( NectarElAssets::locate(array('blog_standard_style="inherit"')) || NectarElAssets::locate(array('blog_masonry_style="inherit"')) ) {

          if( 'material' === $nectar_blog_masonry_style || 
              'classic' === $nectar_blog_masonry_style ) {
            wp_enqueue_style( 'nectar-blog-masonry-core' );
          }

					if( 'classic_enhanced' === $nectar_blog_masonry_style ) {
						wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
					}
					if( 'meta_overlaid' === $nectar_blog_masonry_style ) {
						wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
					}
					if( 'auto_meta_overlaid_spaced' === $nectar_blog_masonry_style ) {
						wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );
					}

					if( 'minimal' === $nectar_blog_std_style ) {
						wp_enqueue_style( 'nectar-blog-standard-minimal' );
					}
					if( 'featured_img_left' === $nectar_blog_std_style ) {
						wp_enqueue_style( 'nectar-blog-standard-featured-left' );
					}

				}

	} // End page builder elements.

	// Archives.
	$using_post_grid_archive = false;
	if ( NectarThemeManager::is_special_location_active('nectar_special_location__blog_loop') ) {
		$using_post_grid_archive = true;
	}

	if( $nectar_on_blog_archive_check && !$using_post_grid_archive ) {

		//// Masonry Styles.
		if( $nectar_blog_type === 'masonry-blog-sidebar' ||
		    $nectar_blog_type === 'masonry-blog-fullwidth' ||
	      $nectar_blog_type === 'masonry-blog-full-screen-width' ) {
        
        //// Core.
        if( 'classic' === $nectar_blog_masonry_style ||
            'material' === $nectar_blog_masonry_style ) {
          wp_enqueue_style( 'nectar-blog-masonry-core' );
        }

				//// Classic enhanced.
				if( 'classic_enhanced' === $nectar_blog_masonry_style ) {
					wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
				}

				//// Meta Overlaid.
				if( 'meta_overlaid' === $nectar_blog_masonry_style ) {
					wp_enqueue_style( 'nectar-blog-masonry-meta-overlaid' );
				}

				//// Auto Masonry Meta Overlaid.
				if( 'auto_meta_overlaid_spaced' === $nectar_blog_masonry_style ) {
					wp_enqueue_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced' );

          // remove container padding.
          if( 'post' === $posttype && $nectar_blog_type === 'masonry-blog-full-screen-width' ) {
            $n_auto_masonry_meta_overlaid_spaced_css = '#ajax-content-wrap .container-wrap { padding-top: 0px!important; }';
            wp_add_inline_style( 'nectar-blog-auto-masonry-meta-overlaid-spaced', $n_auto_masonry_meta_overlaid_spaced_css );
          }
				}

		}
		//// Standard Styles.
		else {

			if( 'minimal' === $nectar_blog_std_style ) {
				wp_enqueue_style( 'nectar-blog-standard-minimal' );
			}
			if( 'featured_img_left' === $nectar_blog_std_style ) {
				wp_enqueue_style( 'nectar-blog-standard-featured-left' );
			}

		}

	} // End archive check.

	//// Related Posts
	if( '1' === $nectar_using_related_posts && is_single() ) {

		if( 'classic_enhanced' === $nectar_related_posts_style ) {
			wp_enqueue_style( 'nectar-blog-masonry-classic-enhanced' );
		}

	}

	// Blog std style containing image gallery grid - archive.
	if ( $nectar_on_blog_archive_check ) {

		if ( $nectar_blog_type === 'std-blog-sidebar' || $nectar_blog_type === 'std-blog-fullwidth' ) {
			//// Standard styles that could contain gallery sliders.
			if ( $nectar_blog_std_style === 'classic' || $nectar_blog_std_style === 'minimal' ) {
				 wp_enqueue_style( 'nectar-flickity' );
				 wp_enqueue_style( 'nectar-portfolio' );
			}
		}
	}


	// Responsive.
	if ( ! empty( $nectar_options['responsive'] ) && $nectar_options['responsive'] == 1 ) {
		wp_enqueue_style( 'responsive' );
	} else {
		wp_enqueue_style( 'non-responsive' );

		add_filter( 'body_class', 'salient_non_responsive' );
		function salient_non_responsive( $classes ) {

				$classes[] = 'salient_non_responsive';

				return $classes;
		}
	}


	// WooCommerce.
	if ( function_exists( 'is_woocommerce' ) ) {

		// Product styles
		if( isset($nectar_options['product_style']) && 'classic' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-classic' );
		}
		else if( isset($nectar_options['product_style']) && 'text_on_hover' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-text-on-hover' );
		}
		else if( isset($nectar_options['product_style']) && 'material' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-material' );
		}
		else if( isset($nectar_options['product_style']) && 'minimal' === $nectar_options['product_style'] ) {
			wp_enqueue_style( 'nectar-product-style-minimal' );
		}
		else {
			wp_enqueue_style( 'nectar-product-style-classic' );
		}

		wp_enqueue_style( 'woocommerce' );

		if( is_product() ) {
			wp_enqueue_style( 'nectar-woocommerce-single' );
			if( isset( $nectar_options['single_product_related_upsell_carousel'] ) && '1' === $nectar_options['single_product_related_upsell_carousel'] ) {
				wp_enqueue_style( 'nectar-element-product-carousel' );
				wp_enqueue_style( 'nectar-flickity' );
			}
		}

		/* Compatibility fix for when plugins enqueue selectWoo 
		https://github.com/woocommerce/selectWoo/issues/41 */
        if ( wp_script_is('selectWoo', 'enqueued')) {
			$select_woo_css = '.woocommerce div.product form.variations_form .fancy-select-wrap {
				position: relative;
			 }
			 .woocommerce div.product form.variations_form .select2-container--open:not(.select2) {
				top: 105%!important;
				min-width: 150px;
			 }';
			wp_add_inline_style( 'main-styles', $select_woo_css );
        }

		if( is_account_page() ) {
			wp_enqueue_style( 'font-awesome' );
		}

	}

	// Gradient linea icons.
	if ( NectarElAssets::locate(array('.svg')) && NectarElAssets::locate(array('Extra-Color-Gradient')) ) {
		wp_enqueue_style( 'linea' );
	}


	// Flickity.
	$nectar_flickity_els = array(
		'[vc_gallery type="flickity"',
		'[vc_gallery type="flickity_style"',
		'[vc_gallery type="flickity_static_height_style"',
		'style="multiple_visible"',
		'style="slider_multiple_visible"',
		'script="flickity"',
		'script="simple_slider"',
		'style="multiple_visible_minimal"',
		'style="slider"',
		'display_type="carousel"'
	);

	if ( NectarElAssets::locate($nectar_flickity_els) ) {
		wp_enqueue_style( 'nectar-flickity' );
	}


	$fancy_rcs = ( ! empty( $nectar_options['form-fancy-select'] ) ) ? $nectar_options['form-fancy-select'] : 'default';
	if ( $fancy_rcs === '1' ) {
		wp_enqueue_style( 'select2' );
	}


	// Portfolio template inline styles.
	if( is_page_template( 'template-portfolio.php' ) ) {

		$nectar_portfolio_archive_layout = ( !empty($nectar_options['main_portfolio_layout']) ) ? $nectar_options['main_portfolio_layout'] : '3';
		$nectar_inline_filters   				 = ( ! empty( $nectar_options['portfolio_inline_filters'] ) && $nectar_options['portfolio_inline_filters'] === '1' ) ? '1' : '0';
		$nectar_portfolio_archive_bg     = get_post_meta( $post->ID, '_nectar_header_bg', true );

		$nectar_portfolio_css = '.page-template-template-portfolio-php .row .col.section-title h1{
		  margin-bottom:0
		}';

		if( $nectar_portfolio_archive_layout === 'fullwidth' ) {
			$nectar_portfolio_css .= '.container-wrap { padding-bottom: 0px!important; } #call-to-action .triangle { display: none; }';
		}

		if( $nectar_portfolio_archive_layout === 'fullwidth' && !empty($nectar_portfolio_archive_bg) ) {
			$nectar_portfolio_css .= '.container-wrap { padding-top: 0px!important; }';
		}

		if( $nectar_inline_filters === '1' && empty($nectar_portfolio_archive_bg) ) {
			$nectar_portfolio_css .= '.page-header-no-bg { display: none; }
			.container-wrap { padding-top: 0px!important; }
			body #portfolio-filters-inline { margin-top: -50px!important; padding-top: 5.8em!important; }';
		}

		if( $nectar_inline_filters === '1' && empty($nectar_portfolio_archive_bg) && $nectar_portfolio_archive_layout != 'fullwidth') {
			$nectar_portfolio_css .= '#portfolio-filters-inline.non-fw { margin-top: -37px!important; padding-top: 6.5em!important;}';
		}

		if( $nectar_inline_filters === '1' && !empty($nectar_portfolio_archive_bg) && $nectar_portfolio_archive_layout != 'fullwidth') {
			$nectar_portfolio_css .= '.container-wrap { padding-top: 3px!important; }';
		}

		wp_add_inline_style( 'main-styles', $nectar_portfolio_css );

	}

	// Search template inline styles.
	if( is_search() ) {

		wp_enqueue_style( 'nectar-search-results' );

		$search_results_header_bg_color   = ( ! empty( $nectar_options['search-results-header-bg-color'] ) ) ? $nectar_options['search-results-header-bg-color'] : '#f4f4f4';
		$search_results_header_font_color = ( ! empty( $nectar_options['search-results-header-font-color'] ) ) ? $nectar_options['search-results-header-font-color'] : '#000000';

		$nectar_search_css = '
		body:not(.archive) #page-header-bg {
			background-color: '.$search_results_header_bg_color.';
		}
		body:not(.archive) #page-header-bg h1, #page-header-bg .result-num {
			color: '.$search_results_header_font_color.';
		}
		';

		if( nectar_is_contained_header() ) {
			$nectar_search_css .= '.search-no-results #search-results  {
				padding-top: 8%;
			}';
		}

		wp_add_inline_style( 'main-styles', $nectar_search_css );
	}

	// 404 template inline styles.
	if( is_404() ) {

		$page_404_bg_color               = ( ! empty( $nectar_options['page-404-bg-color'] ) ) ? $nectar_options['page-404-bg-color'] : '';
		$page_404_font_color             = ( ! empty( $nectar_options['page-404-font-color'] ) ) ? $nectar_options['page-404-font-color'] : '';
		$page_404_bg_image_overlay_color = ( ! empty( $nectar_options['page-404-bg-image-overlay-color'] ) ) ? $nectar_options['page-404-bg-image-overlay-color'] : '';

		$nectar_404_css = '
		#error-404{
		  text-align:center;
		  padding: 10% 0;
		  position: relative;
		  z-index: 10;
		}
		body.error {
		  padding: 0;
		}
		body #error-404[data-cc="true"] h1,
		body #error-404[data-cc="true"] h2,
		body #error-404[data-cc="true"] p {
		  color: inherit;
		}
		body.error404 .error-404-bg-img,
		body.error404 .error-404-bg-img-overlay {
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  background-size: cover;
		  background-position: 50%;
		  z-index: 1;
		}
		body.error404 .error-404-bg-img-overlay {
		  opacity: 0.8;
		}
		body #error-404 h1,
		body #error-404 h2 {
		  font-family: "Open Sans";
		  font-weight:700
		}
		body #ajax-content-wrap #error-404 h1 {
		  font-size:250px;
		  line-height:250px;
		}
		body #ajax-content-wrap #error-404 h2 {
		  font-size:54px;
		}
		body #error-404 .nectar-button {
		  margin-top: 50px;
		}

		body.error404 .main-content > .row > .col.span_12 {
			padding-bottom: 0;
		}

		@media only screen and (max-width : 690px) {

			body .row #error-404 h1,
			body #ajax-content-wrap #error-404 h1 {
				font-size: 150px;
				line-height: 150px;
			}

			body #ajax-content-wrap #error-404 h2 {
				font-size: 32px;
			}

			body .row #error-404 {
				margin-bottom: 0;
			}
		}
		';

		if ( ! empty( $page_404_bg_color ) ) {
			$nectar_404_css .= 'html .error404 .container-wrap {
				background-color: '.$page_404_bg_color.';
			}';
		}

		if ( ! empty( $page_404_bg_image_overlay_color ) ) {
			$nectar_404_css .= '.error404 .error-404-bg-img-overlay {
				background-color: '. $page_404_bg_image_overlay_color .';
			}';
		}
		if ( ! empty( $page_404_font_color ) ) {
			$nectar_404_css .= '.error404 #error-404,
			.error404 #error-404 h1,
			.error404 #error-404 h2 {
				color: '. $page_404_font_color .';
			}';
		}

		wp_add_inline_style( 'main-styles', $nectar_404_css );
	}

  $nectar_media_el_css = 'body .mejs-container .mejs-controls >.mejs-horizontal-volume-slider{
    height:26px;
    width:56px;
    position:relative;
    display:block;
    float:left;
  }
  .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total{
    background:rgba(255,255,255,.33)
  }
  .mejs-controls .mejs-button button:focus{
    outline:none
  }
  
  body[data-button-style*="_rounded"] .mejs-button>button,
  body[data-button-style^="rounded"] .mejs-controls .mejs-pause button,
  body .mejs-controls .mejs-pause {
    border-radius: 0!important;
  }
  
  video, audio{
    visibility:hidden
  }
  .mejs-controls .mejs-time-rail .mejs-time-loaded{
    background-color:rgba(255,255,255,0.3)!important
  }
  .mejs-video .mejs-controls .mejs-time-rail{
    padding-top:12px
  }
  .mejs-audio .mejs-controls .mejs-time-rail{
    padding-top:11px
  }
  .mejs-video .mejs-controls .mejs-time-rail .mejs-time-current,
  .mejs-video .mejs-controls .mejs-time-rail span,
  .mejs-video .mejs-controls .mejs-time-rail a,
  .mejs-video .mejs-controls .mejs-time-rail .mejs-time-loaded{
    height:8px
  }
  .mejs-audio .mejs-controls .mejs-time-rail .mejs-time-current,
  .mejs-audio .mejs-controls .mejs-time-rail span,
  .mejs-audio .mejs-controls .mejs-time-rail a,
  .mejs-audio .mejs-controls .mejs-time-rail .mejs-time-loaded{
    height:8px
  }
  #ajax-content-wrap .mejs-container{
    background-color:transparent;
    background-image:none!important
  }
  .wp-video{
    margin-bottom:20px;
  }
  .wp-video,
  .mejs-container .mejs-poster img{
    max-width:none!important;
    width:100%!important
  }
  .wp-video-shortcode.mejs-container .mejs-poster img{
    visibility:hidden;
    display: block;
    margin-bottom: 0;
  }
  .mejs-container-fullscreen .mejs-poster img{
    height:100%!important
  }
  body .mejs-poster{
    background-size:cover
  }
  body .mejs-container .mejs-controls .mejs-time{
    opacity:0.8;
  }
  body .mejs-controls button{
    transition:opacity 0.15s ease
  }
  body .mejs-controls button:hover,
  .mejs-controls .mejs-fullscreen-button:hover button{
    opacity:0.8
  }
  
  #ajax-content-wrap .mejs-controls .mejs-time-rail .mejs-time-total{
    background-color:rgba(255,255,255,0.25)
  }
  .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current{
    background:transparent!important
  }
  body .mejs-controls .mejs-button button{
    font-size:18px;
    color:#fff;
    width:auto;
    position:relative;
    display:inline-block;
  }
  body .mejs-controls .mejs-button:not(.mejs-replay) button {
     background-image:none
  }
  body .mejs-controls .mejs-button.mejs-replay button {
    width: 20px;
  }
  body .mejs-controls button:before{
    text-decoration:inherit;
    display:inline-block;
    speak:none
  }
  body .mejs-controls .mejs-play button:before{
    content:"\e052"
  }
  body .mejs-controls .mejs-mute button:before{
    content:"\e098"
  }
  body .mejs-controls .mejs-unmute button:before{
    content:"\e099"
  }
  body .mejs-controls .mejs-fullscreen-button button:before{
    content:"\e110";
    font-size:17px
  }
  body .mejs-controls .mejs-fullscreen-button.mejs-unfullscreen button:before{
    content:"\e111"
  }
  body .mejs-button.mejs-volume-button{
    margin-left:5px
  }
  body .mejs-controls .mejs-pause{
    top:2px;
    left:2px;
    position:relative;
  }
  
  body .mejs-controls .mejs-pause button{
    border-left:3px solid #fff;
    border-right:3px solid #fff;
    width:9px;
    height:12px;
    right:3px;
    top:2px
  }
  #ajax-content-wrap .mejs-container.mejs-audio{
    height:44px!important;
    background-color:rgba(0,0,0,1)
  }
  #ajax-content-wrap .mejs-container.mejs-audio .mejs-playpause-button {
    margin-left: 0;
  }
  
  body .mejs-container.mejs-audio .mejs-controls {
    height:42px
  }
  
  body .mejs-container:not(.mejs-audio) .mejs-controls:before{
    background:linear-gradient(rgba(255,255,255,0) -2%,rgba(0,0,0,0) 35%,rgba(0,0,0,0.04) 44%,rgba(0,0,0,0.8) 100%,rgba(0,0,0,0.6) 100%);
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    height:400%;
    z-index:11;
    border-radius:4px;
    content:" "
  }
  body .mfp-wrap .mfp-content .mejs-container:not(.mejs-audio) .mejs-controls:before {
    border-radius: 0;
  }
  body .mejs-container .mejs-controls >*{
    z-index:100;
    position:relative
  }
  body .mejs-container .mejs-controls{
    background:none!important;
    height:50px
  }
  #ajax-content-wrap .mejs-playpause-button{
    margin-left:20px
  }
  #ajax-content-wrap .mejs-fullscreen-button{
    margin-right:20px
  }
  body .mejs-video .mejs-controls .mejs-time-rail .mejs-time-float{
    color:#fff;
    border:none;
    background-color:transparent
  }
  body .mejs-controls .mejs-time-rail .mejs-time-float-corner{
    border-color:transparent transparent
  }
  body .mejs-audio .mejs-controls .mejs-time-rail .mejs-time-float{
    border:none;
    background-color:#fff;
    height:15px;
    box-shadow:0 2px 12px rgba(0,0,0,0.25)
  }';
  
  $nectar_media_el_css = nectar_quick_minify($nectar_media_el_css);
  
  wp_add_inline_style( 'wp-mediaelement', $nectar_media_el_css );

	// Sidebar templates
	if( is_page_template( 'page-sidebar.php' ) || is_page_template( 'page-left-sidebar.php' ) ) {

		$sidebar_template_css = 'body.page-template-page-sidebar-php .main-content >.row >.post-area,
		body.page-template-page-sidebar-php .main-content >.row >#sidebar,
		body.page-template-page-left-sidebar-php .main-content >.row >.post-area,
		body.page-template-page-left-sidebar-php .main-content >.row >#sidebar{
		  margin-top:30px
		}';

		wp_add_inline_style( 'main-styles', $sidebar_template_css );
	}


	// Legacy Dual Mobile Menu.
	$legacy_double_menu = nectar_legacy_mobile_double_menu();
	if( true === $legacy_double_menu ) {

		$nectar_dual_mobile_menu = '@media only screen and (max-width: 999px) and (min-width: 1px) {
			#header-outer[data-has-menu="true"] #top .span_3 .nectar-ocm-trigger-open {
				display: inline-block;
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
			}
			#header-outer[data-has-menu="true"] #top .span_3 {
				text-align: center!important;
			}
		}';

		wp_add_inline_style( 'main-styles', $nectar_dual_mobile_menu );
	}


}

add_action( 'wp_enqueue_scripts', 'nectar_page_sepcific_styles' );


if( !function_exists('nectar_preload_key_requests') ) {
	function nectar_preload_key_requests() {

		global $nectar_options;
		global $nectar_get_template_directory_uri;

		if( isset($nectar_options['typography_font_swap']) && '1' === $nectar_options['typography_font_swap'] ) {

			// Icomoon.
			echo '<link rel="preload" href="'.esc_attr($nectar_get_template_directory_uri) . '/css/fonts/icomoon.woff?v=1.6" as="font" type="font/woff" crossorigin="anonymous">';
		}
	}

}
add_action( 'wp_head', 'nectar_preload_key_requests', 5 );

/**
 * Non rendering blocking CSS.
 *
 * @since 1.0
 */
 if( !function_exists('nectar_deferred_styles') ) {
	function nectar_deferred_styles() {

		global $nectar_options;
		
		wp_enqueue_style('main-styles-non-critical');

		$use_font_awesome = ( isset($nectar_options['rm-font-awesome']) && '1' === $nectar_options['rm-font-awesome'] ) ? false : true;

		if( false === $use_font_awesome ) {
		
			$social_media_list = nectar_get_social_media_list();

			$included_icons = array('twitter', 'facebook', 'pinterest', 'linkedin');
			foreach($included_icons as $key) {
				unset( $social_media_list[$key] );
			}

			foreach ($social_media_list as $network_name => $icon_arr) {
			
				if ( (isset( $nectar_options[ 'use-' . $network_name . '-icon-header' ] ) && $nectar_options[ 'use-' . $network_name . '-icon-header' ] === '1' ) ||
					isset( $nectar_options[ $network_name . '-url' ] ) && !empty($nectar_options[ $network_name . '-url' ]) ) {
			
					wp_enqueue_style( 'font-awesome' );
					break;

				}
			}
		}

		 // WooCommerce.
		 if ( function_exists( 'is_woocommerce' ) ) {
			 wp_enqueue_style( 'nectar-woocommerce-non-critical');
		 }

		 // Lightbox.
		 $lightbox_script = ( ! empty( $nectar_options['lightbox_script'] ) ) ? $nectar_options['lightbox_script'] : 'magnific';
		 if ( $lightbox_script === 'pretty_photo' ) {
			 $lightbox_script = 'magnific';
		 }
		 if ( $lightbox_script === 'magnific' ) {
			 wp_enqueue_style( 'magnific' );
		 } elseif ( $lightbox_script === 'fancybox' ) {
			 wp_enqueue_style( 'fancyBox' );
		 }

		 // Off canvas menu.
		 wp_enqueue_style( 'nectar-ocm-core' );

	 	 $header_off_canvas_style = NectarThemeManager::$ocm_style;
		 $legacy_double_menu = nectar_legacy_mobile_double_menu();

	 	 if( $header_off_canvas_style === 'slide-out-from-right-hover' ) {
	 		wp_enqueue_style( 'nectar-ocm-slide-out-right-hover' );
	 	 }
	 	 else if( $header_off_canvas_style === 'fullscreen' ||
	 	          $header_off_canvas_style === 'fullscreen-alt' ) {
	 	 	wp_enqueue_style( 'nectar-ocm-fullscreen-legacy' );
	 	 }
	 	 else if( $header_off_canvas_style === 'fullscreen-split' ) {
	 		 wp_enqueue_style( 'nectar-ocm-fullscreen-split' );
	 	 }
		 else if( $header_off_canvas_style === 'slide-out-from-right' ) {
			 $theme_skin = NectarThemeManager::$skin;

			 if( 'material' === $theme_skin ) {
				 wp_enqueue_style('nectar-ocm-slide-out-right-material');
				 wp_enqueue_style( 'nectar-ocm-slide-out-right-hover' );
			 }

		 }

		 if( $header_off_canvas_style === 'simple' || true === $legacy_double_menu ) {
	 		 wp_enqueue_style( 'nectar-ocm-simple' );
	 	 }


	}
}
add_action( 'wp_footer', 'nectar_deferred_styles' );



if( !function_exists('nectar_deferred_style_list') ) {
	function nectar_deferred_style_list() {
		return array(
			'main-styles-non-critical',
			'nectar-woocommerce-non-critical',
			'nectar-ocm-simple',
			'nectar-ocm-fullscreen-split',
			'nectar-ocm-slide-out-right-material',
			'nectar-ocm-fullscreen-legacy',
			'nectar-ocm-slide-out-right-hover',
			'nectar-ocm-core',
			'fancyBox',
			'magnific',
		);

	}
}

if( !function_exists('nectar_deferred_mod_style_attrs') ) {

	function nectar_deferred_mod_style_attrs($tag, $handle) {

		$deferred_styles = nectar_deferred_style_list();

		if ( in_array($handle, $deferred_styles) ) {
			
			$modded_stylesheet = str_replace( '<link', '<link data-pagespeed-no-defer data-nowprocket data-wpacu-skip data-no-optimize data-noptimize', $tag );
		   
			return $modded_stylesheet; 
		}

		return $tag;    
	}
}

// Modify deferred stylesheets to exclude from performance plugins
add_filter( 'style_loader_tag', 'nectar_deferred_mod_style_attrs', 10 ,2 );

if( !function_exists('nectar_deferred_styles_slice') ) {

	function nectar_deferred_styles_slice($stylesheets) {

		$deferred_styles = nectar_deferred_style_list();

		foreach($stylesheets as $key => $style) {

			foreach( $deferred_styles as $salient_stylesheet ) {
				if(  strpos($style, $salient_stylesheet) !== false ) {
					unset($stylesheets[$key]);
				}
			}
			
		}

		return $stylesheets;
	}

}

if( !function_exists('nectar_deferred_styles_add') ) {

	function nectar_deferred_styles_add($stylesheets) {

		$deferred_styles = nectar_deferred_style_list();
		foreach( $deferred_styles as $style ) {
			$stylesheets[] = $style;
		}
		return $stylesheets;
	}

}

if( !function_exists('nectar_deferred_styles_add_string') ) {

	function nectar_deferred_styles_add_string() {

		$string = '';
		$stylesheets = array();
		$deferred_styles = nectar_deferred_style_list();

		foreach( $deferred_styles as $style ) {
			$stylesheets[] = $style;
		}
		return implode(',', $stylesheets);
	}

}

// W3 Total Cache  
add_filter( 'w3tc_minify_css_style_tags', 'nectar_deferred_styles_slice', 10, 2); 

// Siteground Optimizer 
add_filter('sgo_css_minify_exclude', 'nectar_deferred_styles_add', 10);
add_filter('sgo_css_combine_exclude', 'nectar_deferred_styles_add', 10);   

// Clearify 
add_filter('wmac_filter_css_exclude', 'nectar_deferred_styles_add_string', 10); 


/**
 * Assist in upgrading users with outdated child theme structure
 */
 if( !function_exists('salient_child_theme_enqueue_stylesheet') ) {

	function salient_child_theme_enqueue_stylesheet() {

		// Child stylesheet
		if( is_child_theme() ) {
			$nectar_theme_version = nectar_get_theme_version();
			wp_register_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
			wp_enqueue_style( 'salient-child-style' );
		}

	}
	add_action( 'wp_enqueue_scripts', 'salient_child_theme_enqueue_stylesheet', 90 );
}



/**
 * Allow users to disable default WP emojis
 *
 * @since 13.0
 */
 if ( ! function_exists( 'nectar_disable_emojis_dns_prefetch' ) ) {

 	function nectar_disable_emojis_dns_prefetch( $urls, $relation_type ) {

 		if ( 'dns-prefetch' === $relation_type ) {
 			foreach ( $urls as $key => $url ) {
 				if (  false !== strpos( $url, 'https://s.w.org/images/core/emoji' ) ) {
 					unset( $urls[$key] );
 				}
 			}
 		}

 		return $urls;
 	}

 }

if ( ! function_exists( 'nectar_disable_emojis' ) ) {
 function nectar_disable_emojis() {

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		add_filter( 'wp_resource_hints', 'nectar_disable_emojis_dns_prefetch', 10, 2 );

	}
}

global $nectar_options;
if( isset($nectar_options['rm-wp-emojis']) &&
		!empty($nectar_options['rm-wp-emojis']) &&
		'1' === $nectar_options['rm-wp-emojis'] ) {

		add_action( 'init', 'nectar_disable_emojis' );
}


