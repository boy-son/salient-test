<?php
/**
 * Enqueue scripts
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 13.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Register/Enqueue frontend JS.
 *
 * @since 1.0
 */
function nectar_register_js() {

	global $nectar_options;
	global $post;
	global $nectar_get_template_directory_uri;

	$nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
	$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

	$nectar_theme_version = nectar_get_theme_version();

	if ( ! is_admin() ) {

    $nectar_dev_mode = apply_filters('nectar_dev_mode', false);
    $src_dir = ( $nectar_dev_mode == true ) ? 'src' : 'build';

		// Priority scripts.
		wp_register_script( 'jquery-easing', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/jquery.easing.min.js', array( 'jquery' ), '1.3', true );
		wp_register_script( 'jquery-mousewheel', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/jquery.mousewheel.min.js', array( 'jquery' ), '3.1.13', true );
		wp_register_script( 'nectar_priority', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/priority.js', array( 'jquery', 'jquery-easing', 'jquery-mousewheel' ), $nectar_theme_version, true );
		wp_register_script( 'nectar_slider_priority', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/nectar-slider-priority.js', array( 'jquery', 'jquery-easing', 'jquery-mousewheel' ), $nectar_theme_version, true );

		// Third party scripts.
		wp_register_script( 'modernizer', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/modernizr.min.js', array( 'jquery' ), '2.6.2', true );
		wp_register_script( 'imagesLoaded', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/imagesLoaded.min.js', array( 'jquery' ), '4.1.4', true );
		wp_register_script( 'superfish', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/superfish.js', array( 'jquery' ), '1.5.8', true );
		wp_register_script( 'hoverintent', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/hoverintent.min.js', array( 'jquery' ), '1.9', true );
		wp_register_script( 'touchswipe', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/touchswipe.min.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'flexslider', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/flexslider.min.js', array( 'jquery', 'touchswipe' ), '2.1', true );
		wp_register_script( 'flickity', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/flickity.js', array( 'jquery' ), '2.3.1', true );
		wp_register_script( 'flickity-fade', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/flickity-fade.js', array( 'jquery', 'flickity' ), '2.2.1', true );
		wp_register_script( 'magnific', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/magnific.js', array( 'jquery' ), '7.0.1', true );
		wp_register_script( 'fancyBox', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/jquery.fancybox.js', array( 'jquery' ), '3.3.9', true );
		wp_register_script( 'isotope', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/isotope.min.js', array( 'jquery' ), '7.6', true );
		wp_register_script( 'select2', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/select2.min.js', array( 'jquery' ), '4.0.1', true );
		wp_register_script( 'nectar-parallax', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/parallax.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'nectar-transit', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/transit.min.js', array( 'jquery' ), '0.9.9', true );
		wp_register_script( 'fullpage', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/jquery.fullPage.min.js', array( 'jquery' ), $nectar_theme_version, true );
		wp_register_script( 'vivus', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/vivus.min.js', array( 'jquery' ), '6.0.1', true );
		wp_register_script( 'caroufredsel', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/caroufredsel.min.js', array( 'jquery', 'touchswipe' ), '7.0.1', true );
		wp_register_script( 'owl-carousel', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );
		wp_register_script( 'leaflet', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/leaflet.min.js', array( 'jquery' ), '1.3.1', true );
		wp_register_script( 'twentytwenty', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/jquery.twentytwenty.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'infinite-scroll', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/infinitescroll.js', array( 'jquery' ), '1.1', true );
		wp_register_script( 'stickykit', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/stickkit.js', array( 'jquery' ), '1.0', true );
		wp_register_script( 'pixi', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/pixi.min.js', array( 'jquery' ), '4.5.1', true );
		wp_deregister_script( 'anime' );
		wp_register_script( 'anime', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/anime.min.js', array( 'jquery' ), '4.5.1', true );
		wp_register_script( 'lottie-player', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/lottie-player.min.js', array( 'jquery' ), '0.4.0', true );
		wp_register_script( 'nectar-waypoints', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/third-party/waypoints.js', array( 'jquery' ), '4.0.2', true );


		// Page option conditional scripts.
		wp_register_script( 'nectar-single-product', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/nectar-single-product.js', array( 'jquery' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-single-product-reviews', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/nectar-single-product-reviews.js', array( 'jquery' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-product-filters-display', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/nectar-product-filters-display.js', array(), $nectar_theme_version );
		wp_register_script( 'nectar-fullpage', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-full-page-rows.js', array( 'jquery', 'jquery-mousewheel' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-box-roll', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/nectar-box-roll.js', array( 'jquery', 'jquery-mousewheel', 'touchswipe' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-particles', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/nectar-particles.js', array( 'jquery', 'jquery-mousewheel' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-animated-gradient', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-animated-gradient.js', array(), $nectar_theme_version, true );

		// Register Salient element scripts.
		wp_register_script( 'nectar-leaflet-map', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-leaflet-map.js', array( 'jquery' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-masonry-blog', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-blog.js', array( 'jquery' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-liquid-bgs', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-liquid.js', array( 'jquery' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-testimonial-sliders', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-testimonial-slider.js', array( 'jquery', 'touchswipe' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-text-inline-images', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-text-inline-images.js', array( 'jquery', 'nectar-waypoints' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-sticky-media-sections', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-sticky-media-sections.js', array( 'jquery', 'nectar-waypoints', 'nectar-frontend' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-fit-text', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-fit-text.js', array( 'jquery', 'nectar-frontend' ), $nectar_theme_version, true );
		wp_register_script( 'nectar-lottie', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/elements/nectar-lottie.js', array( 'jquery', 'lottie-player', 'nectar-frontend' ), $nectar_theme_version, true );
		
		// Main Salient script.
		wp_register_script( 'nectar-frontend', $nectar_get_template_directory_uri . '/js/'.$src_dir.'/init.js', array( 'jquery', 'superfish', 'nectar-waypoints', 'nectar-transit' ), $nectar_theme_version, true );

		// Dequeue.
		$lightbox_script = ( ! empty( $nectar_options['lightbox_script'] ) ) ? $nectar_options['lightbox_script'] : 'magnific';
		if ( $lightbox_script === 'pretty_photo' ) {
			$lightbox_script = 'magnific';
		}

		// Enqueue.
		wp_enqueue_script( 'nectar_priority' );
		if( class_exists('Salient_Nectar_Slider') && NectarElAssets::locate(array('nectar_slider')) ) {
			wp_enqueue_script( 'nectar_slider_priority' );
		}


		wp_enqueue_script( 'nectar-transit' );
		wp_enqueue_script( 'nectar-waypoints' );

		$salient_modernizr = false;
		if( has_filter('salient_IE_compat_mode') ) {
			$salient_modernizr = apply_filters('salient_IE_compat_mode', $salient_modernizr);

			if( true === $salient_modernizr ) {
				wp_enqueue_script( 'modernizer' );
			}

		}

		wp_enqueue_script( 'imagesLoaded' );
		wp_enqueue_script( 'hoverintent' );


		$post_content           = ( isset( $post->post_content ) ) ? $post->post_content : '';
		$nectar_box_roll 				= ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_header_box_roll', true ) : '';
		$page_full_screen_rows 	= ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows', true ) : '';

		if ( ! empty( $nectar_options['portfolio_sidebar_follow'] ) && $nectar_options['portfolio_sidebar_follow'] === '1' && is_singular( 'portfolio' ) ) {
			wp_enqueue_script( 'stickykit' );
		}

		// Lightbox.
		if ( $lightbox_script === 'magnific' ) {
			wp_enqueue_script( 'magnific' );
		} elseif ( $lightbox_script === 'fancybox' ) {
			wp_enqueue_script( 'fancyBox' );
		}

		if( NectarElAssets::locate( array('nectar_portfolio', 'vc_gallery type="image_grid"', 'type="image_grid"') ) ||
		is_page_template( 'template-portfolio.php' ) || is_search() ) {
			 wp_enqueue_script( 'isotope' );
		}
		// Portfolio.
		if ( NectarElAssets::locate(array('nectar_portfolio','recent_projects', 'type="image_grid"')) ) {
			wp_enqueue_script( 'salient-portfolio-js' );
		}

		// Nectar Page Settings.
		if( $nectar_box_roll === 'on' ) {
			wp_enqueue_script( 'nectar-box-roll' );
		}

		if ( $page_full_screen_rows === 'on' ) {
			wp_enqueue_script( 'fullpage' );
			wp_enqueue_script( 'nectar-fullpage' );
		}

		// Carousels.
		if( NectarElAssets::locate(array('[recent_projects','[carousel easing','[carousel auto', 'carouFredSel', 'carousel="true"')) || is_page_template( 'template-home-1.php' ) ) {
			wp_enqueue_script( 'caroufredsel' );
		}
		if ( NectarElAssets::locate( array('script="owl_carousel"')) ) {
			wp_enqueue_script( 'owl-carousel' );
		}

		// Testimonial Sliders.
		if ( NectarElAssets::locate(array('testimonial_slider', 'style="multiple_visible"')) ) {
			wp_enqueue_script( 'nectar-testimonial-sliders' );
		}

		// Row/Column BG animation deps.
		if ( NectarElAssets::locate(array('bg_image_animation="displace-filter')) ) {
			wp_enqueue_script( 'pixi' );
			wp_enqueue_script( 'nectar-liquid-bgs' );
		}
		
    	wp_dequeue_script( 'anime' );
		wp_enqueue_script( 'anime' );
		
		// Testimonial Sliders.
		if ( NectarElAssets::locate(array('mouse_based_parallax_bg="true"')) ) {
			wp_enqueue_script( 'nectar-parallax' );
		}

		// Mouse Parallax
		if ( NectarElAssets::locate(array('[nectar_image_comparison')) ) {
			wp_enqueue_script( 'twentytwenty' );
		}

		// Twenty Twenty
		if ( NectarElAssets::locate(array('[nectar_image_comparison')) ) {
			wp_enqueue_script( 'twentytwenty' );
		}

		// Text with inline images.
        if (NectarElAssets::locate(array('[nectar_text_inline_images'))) {
            wp_enqueue_script('nectar-text-inline-images');
		}
		
		// Sticky Media Sections.
        if (NectarElAssets::locate(array('[nectar_sticky_media_sections'))) {
            wp_enqueue_script('nectar-sticky-media-sections');
        }

		// Sticky Media Sections.
        if (NectarElAssets::locate(array('fit_text_to_container'))) {
            wp_enqueue_script('nectar-fit-text');
        }

		// Animated Gradients.
		if (NectarElAssets::locate(array('animated_gradient_bg="true"'))) {
            wp_enqueue_script('nectar-animated-gradient');
        }
		

		

		// Flickity.
		$nectar_flickity_els = array(
			'[vc_gallery type="flickity"',
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

			wp_enqueue_script( 'flickity' );

			if ( NectarElAssets::locate(array('simple_slider_transition_type="fade"')) ) {
				wp_enqueue_script( 'flickity-fade' );
			}
		}

		// Sticky sidebar.
		if ( NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('enable_ss="true"')) ) {
			wp_enqueue_script( 'stickykit' );
		}
		if(  NectarElAssets::locate(array('sticky_content="true"')) ) {
			wp_enqueue_script( 'stickykit' );
		}

		// svg icon animation
		if ( NectarElAssets::locate(array('.svg')) || 
			NectarElAssets::locate(array('"linea"')) && NectarElAssets::locate(array('enable_animation="true"')) ) {
			wp_enqueue_script( 'vivus' );
		}



		// Infinite scroll.
		if ( NectarElAssets::locate(array('pagination_type="infinite_scroll"')) ) {
			wp_enqueue_script( 'infinite-scroll' );
		}

		// Gallery slider scripts.
		if ( NectarElAssets::locate(array('[nectar_blog')) ) {
				wp_enqueue_script( 'flexslider' );
		}

		// Isotope.
		if ( NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('layout="masonry')) ||
			NectarElAssets::locate(array('[nectar_blog')) && NectarElAssets::locate(array('layout="std-blog-')) && NectarElAssets::locate(array('blog_standard_style="classic')) ) {
			wp_enqueue_script( 'isotope' );
			wp_enqueue_script( 'nectar-masonry-blog' );
		}

		/*********for archive pages based on theme options*/
		$posttype =  isset($post) ? get_post_type( $post ) : '';
		$nectar_on_blog_archive_check      = ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && ( ! is_singular() );
		$nectar_on_portfolio_archive_check = ( is_archive() || is_category() || is_home() || is_tag() ) && ( 'portfolio' === $posttype && ! is_singular() );

		// Infinite scroll.
		if ( ( ! empty( $nectar_options['portfolio_pagination_type'] ) && $nectar_options['portfolio_pagination_type'] === 'infinite_scroll' ) && $nectar_on_portfolio_archive_check ||
				( ! empty( $nectar_options['portfolio_pagination_type'] ) && $nectar_options['portfolio_pagination_type'] === 'infinite_scroll' ) && is_page_template( 'template-portfolio.php' ) ||
				( ! empty( $nectar_options['blog_pagination_type'] ) && $nectar_options['blog_pagination_type'] === 'infinite_scroll' ) && $nectar_on_blog_archive_check ) {
				wp_enqueue_script( 'infinite-scroll' );

			if ( class_exists( 'WPBakeryVisualComposerAbstract' ) && defined( 'SALIENT_VC_ACTIVE' ) ) {
				wp_register_script( 'progressCircle', vc_asset_url( 'lib/bower/progress-circle/ProgressCircle.min.js' ) );
				wp_register_script( 'vc_pie', vc_asset_url( 'lib/vc_chart/jquery.vc_chart.min.js' ), array( 'jquery', 'progressCircle' ) );
			}
		}

		// Sticky sidebar.
		if ( ! empty( $nectar_options['blog_enable_ss'] ) && $nectar_options['blog_enable_ss'] === '1' && $nectar_on_blog_archive_check ) {
			wp_enqueue_script( 'stickykit' );
		}

		// Isotope.
		$nectar_blog_type          = ( ! empty( $nectar_options['blog_type'] ) ) ? $nectar_options['blog_type'] : 'masonry-blog-fullwidth';
		$nectar_blog_std_style     = ( ! empty( $nectar_options['blog_standard_type'] ) ) ? $nectar_options['blog_standard_type'] : 'featured_img_left';
		$nectar_blog_masonry_style = ( ! empty( $nectar_options['blog_masonry_type'] ) ) ? $nectar_options['blog_masonry_type'] : 'auto_meta_overlaid_spaced';

		if ( $nectar_blog_type != 'std-blog-sidebar' && $nectar_blog_type !== 'std-blog-fullwidth' ) {
			if ( $nectar_blog_masonry_style != 'auto_meta_overlaid_spaced' && $nectar_on_blog_archive_check ) {
				wp_enqueue_script( 'isotope' );
				wp_enqueue_script( 'nectar-masonry-blog' );
			}
		}

		if ( $nectar_on_portfolio_archive_check ) {
			wp_enqueue_script( 'isotope' );
			wp_enqueue_script( 'salient-portfolio-js' );
		}

		// Gallery slider scripts.
		if ( $nectar_on_blog_archive_check ) {

			if ( $nectar_blog_type === 'std-blog-sidebar' || $nectar_blog_type === 'std-blog-fullwidth' ) {

				// Standard styles that could contain gallery sliders.
				if ( $nectar_blog_std_style === 'classic' || $nectar_blog_std_style === 'minimal' ) {
					wp_enqueue_script( 'flexslider' );
					wp_enqueue_script( 'isotope' );
					wp_enqueue_script( 'flickity' );
					wp_enqueue_script( 'nectar-testimonial-sliders' );
				}
			} else {
				// Masonry styles that could contain gallery sliders.
				if ( $nectar_blog_masonry_style !== 'auto_meta_overlaid_spaced' ) {
					wp_enqueue_script( 'flexslider' );
				}
			}
		}

		// Single post sticky sidebar.
		$enable_ss = ( ! empty( $nectar_options['blog_enable_ss'] ) ) ? $nectar_options['blog_enable_ss'] : 'false';

		if ( ( $enable_ss == '1' && is_single() && $posttype === 'post' ) ||
					NectarElAssets::locate(array('[vc_widget_sidebar')) ||
					NectarElAssets::locate( array('style="vertical_scrolling"')) ) {
			wp_enqueue_script( 'stickykit' );
		}



		// Main Salient Script.
		wp_enqueue_script( 'nectar-frontend' );


		// Load all when using WPBakery front end editor.
		if( $nectar_using_VC_front_end_editor ) {
			wp_enqueue_script('nectar-slider');
			wp_enqueue_script('nectar-waypoints');
			wp_enqueue_script('nectar-parallax');
			wp_enqueue_script('isotope');
			wp_enqueue_script('salient-portfolio-js');
			wp_enqueue_script('caroufredsel');
			wp_enqueue_script('vivus');
			wp_enqueue_script('touchswipe');
			wp_enqueue_script('flickity');
			wp_enqueue_script('flickity-fade');
			wp_enqueue_script('flexslider');
			wp_enqueue_script('stickykit');
			wp_enqueue_script('vivus');
			wp_enqueue_script('twentytwenty');
			wp_enqueue_script('owl-carousel');
			wp_enqueue_script('leaflet');
	    	wp_enqueue_script('nectar-leaflet-map');
			wp_enqueue_script('nectar-testimonial-sliders');
			wp_enqueue_script('nectar-masonry-blog');
			wp_enqueue_script('nectar-text-inline-images');
			wp_enqueue_script('nectar-sticky-media-sections');
			wp_enqueue_script('nectar-fit-text');
		}

	}


	// Disqus plugin.
	$disqus_comments = ( function_exists( 'dsq_is_installed' ) ) ? 'true' : 'false';

	wp_localize_script(
		'nectar-frontend',
		'nectarLove',
		array(
			'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
			'postID'         => isset($post->ID) ? $post->ID : '',
			'rooturl'        => esc_url( home_url() ),
			'disqusComments' => $disqus_comments,
			'loveNonce'      => wp_create_nonce( 'nectar-love-nonce' ),
			'mapApiKey'      => ( ! empty( $nectar_options['google-maps-api-key'] ) ) ? $nectar_options['google-maps-api-key'] : '',
		)
	);

	$woo_toggle_sidebar = true;
	if( has_filter('salient_woocommerce_sidebar_toggles') ) {
		$woo_toggle_sidebar = apply_filters('salient_woocommerce_sidebar_toggles', $woo_toggle_sidebar);
	}

	$ajax_search       = ( ! empty( $nectar_options['header-disable-ajax-search'] ) && $nectar_options['header-disable-ajax-search'] === '1' ) ? 'no' : 'yes';
	$header_search     = ( ! empty( $nectar_options['header-disable-search'] ) && $nectar_options['header-disable-search'] === '1' ) ? 'false' : 'true';
	$nectar_theme_skin = NectarThemeManager::$skin;
	$header_format     = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
	$header_entrance   = 'false';

	if( isset($post->ID) ) {

		$entrance_animation = get_post_meta($post->ID, '_header_nav_entrance_animation', true);

		if( in_array($entrance_animation, array('fade-in','fade-in-from-top')) ) {
			$header_entrance = 'true';
		}

	}

	// Track if delayJs is enabled
	$delay_js = 'false';
	if (  isset( $nectar_options['delay-js-execution'] ) && $nectar_options['delay-js-execution'] === '1' ) {
		$delay_js_devices = ( isset( $nectar_options['delay-js-execution-devices'] ) ) ? $nectar_options['delay-js-execution-devices'] : 'mobile';
	
		if( 'all' === $delay_js_devices || wp_is_mobile() ) {
			$delay_js = '1';
		}
	}

	$ajax_add_to_cart = ( isset( $nectar_options['ajax-add-to-cart'] ) ) ? esc_html($nectar_options['ajax-add-to-cart']) : '0';
	$editing_subscription = isset($_GET['switch-subscription']) ? true : false;
	if ( $editing_subscription ) { 
		$ajax_add_to_cart = '0';
	}

	$woo_using_cart_addons = 'false';
	if( class_exists('WC_Dynamic_Pricing') ) {
		$woo_using_cart_addons = 'true';
	}

	wp_localize_script(
		'nectar-frontend',
		'nectarOptions',
		array(
			'delay_js'                    => $delay_js,
			'quick_search'                => ( $ajax_search === 'yes' && $header_search !== 'false' && $nectar_theme_skin === 'material' ) ? 'true' : 'false',
			'react_compat'                => apply_filters('salient_react_compatibility', 'disabled'), // deprecated -- removed from use in 16.0
			'header_entrance'             =>  $header_entrance,
			'dropdown_hover_intent'       => ( isset( $nectar_options['header-dropdown-delay'] ) ) ? esc_html($nectar_options['header-dropdown-delay']) : 'default',
			'simplify_ocm_mobile'         => ( isset( $nectar_options['header-slide-out-from-right-simplify-mobile'] ) ) ? esc_html($nectar_options['header-slide-out-from-right-simplify-mobile']) : 'false',
			'mobile_header_format'        => ( isset( $nectar_options['mobile-menu-layout'] ) ) ? esc_html($nectar_options['mobile-menu-layout']) : 'default',
			'ocm_btn_position'            => ( isset( $nectar_options['ocm_btn_position'] ) ) ? esc_html($nectar_options['ocm_btn_position']) : 'default',
			'left_header_dropdown_func'   => ( isset( $nectar_options['left-header-dropdown-func'] ) ) ? esc_html($nectar_options['left-header-dropdown-func']) : 'default',
			'ajax_add_to_cart'            => $ajax_add_to_cart,
			'ocm_remove_ext_menu_items'   => ( isset( $nectar_options['header-slide-out-widget-area-image-display'] ) ) ? esc_html($nectar_options['header-slide-out-widget-area-image-display']) : 'default',
			'woo_product_filter_toggle'   => ( isset( $nectar_options['product_filter_area'] ) ) ? esc_html($nectar_options['product_filter_area']) : '0',
			'woo_sidebar_toggles'         => ( false === $woo_toggle_sidebar ) ? 'false' : 'true',
			'woo_sticky_sidebar'          => ( isset( $nectar_options['main_shop_layout_sticky_sidebar'] ) ) ? esc_html($nectar_options['main_shop_layout_sticky_sidebar']) : '0',
			'woo_minimal_product_hover'   => ( isset( $nectar_options['product_minimal_hover_layout'] ) ) ? esc_html($nectar_options['product_minimal_hover_layout']) : 'default',
			'woo_minimal_product_effect'  => ( isset( $nectar_options['product_minimal_hover_effect'] ) ) ? esc_html($nectar_options['product_minimal_hover_effect']) : 'default',
			'woo_related_upsell_carousel' => ( isset( $nectar_options['single_product_related_upsell_carousel'] ) && '1' === $nectar_options['single_product_related_upsell_carousel'] ) ? 'true' : 'false',
			'woo_product_variable_select' => ( isset( $nectar_options['product_variable_select_style'] ) ) ? esc_html($nectar_options['product_variable_select_style']) : 'default',
			'woo_using_cart_addons'       => $woo_using_cart_addons
		)
	);

	wp_localize_script(
		'nectar-frontend',
		'nectar_front_i18n',
		array(
			'menu'     => esc_html__('Menu', 'salient'),
			'next'     => esc_html__('Next', 'salient'),
			'previous' => esc_html__('Previous', 'salient'),
			'close' => esc_html__('Close', 'salient'),
		)
	);

}

add_action( 'wp_enqueue_scripts', 'nectar_register_js' );



/**
 * Enqueue page specific JS.
 *
 * @since 1.0
 */
function nectar_page_specific_js() {

	global $post;
	global $nectar_options;
	global $nectar_get_template_directory_uri;

	if ( ! is_object( $post ) ) {
		$post = (object) array(
			'post_content' => ' ',
			'ID'           => ' ',
		);
	}
	$template_name = get_post_meta( $post->ID, '_wp_page_template', true );

	// Home.
	if ( is_page_template( 'template-home-1.php' ) || $template_name === 'salient/template-home-1.php' ||
		 is_page_template( 'template-home-2.php' ) || $template_name === 'salient/template-home-2.php' ||
		 is_page_template( 'template-home-3.php' ) || $template_name === 'salient/template-home-3.php' ||
		 is_page_template( 'template-home-4.php' ) || $template_name === 'salient/template-home-4.php' ) {
		wp_enqueue_script( 'orbit' );
		wp_enqueue_script( 'touchswipe' );
	}

	if( class_exists( 'WooCommerce' ) ) {

		// Archives.
		if( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {

			if( true === NectarThemeManager::$woo_product_filters ) {
				wp_enqueue_script( 'nectar-product-filters-display' );
				wp_localize_script(
					'nectar-product-filters-display',
					'nectarProductFilterOptions',
					array(
						'startingState' => ( isset( $nectar_options['product_filter_area_starting_state'] ) && 'closed' !== $nectar_options['product_filter_area_starting_state'] ) ? 'open' : 'closed'
					)
				);
			}

			if( isset( $nectar_options['main_shop_layout_sticky_sidebar'] ) && '1' === $nectar_options['main_shop_layout_sticky_sidebar'] ) {
				wp_enqueue_script( 'stickykit' );
			}

		}

		// Single Product.
		if( is_product() ) {

			$product_gallery_style = (isset($nectar_options['single_product_gallery_type'])) ? $nectar_options['single_product_gallery_type'] : 'default';

			if( isset( $nectar_options['single_product_related_upsell_carousel'] ) && 
				'1' === $nectar_options['single_product_related_upsell_carousel'] ) {
				wp_enqueue_script( 'flickity' );
			}
			if( in_array($product_gallery_style, array('ios_slider', 'left_thumb_sticky')) ) {
				wp_enqueue_script( 'flickity' );
			}
			if( in_array($product_gallery_style, array( 'left_thumb_sticky', 'two_column_images' )) ) {
				wp_enqueue_script( 'stickykit' );
			}

			wp_enqueue_script('nectar-single-product');
			
			if( isset( $nectar_options['product_reviews_style'] ) && 
			'off_canvas' === $nectar_options['product_reviews_style'] ) {
				wp_enqueue_script('nectar-single-product-reviews');
			}
      
			
		}

	}


	// Nectar slider.
	if ( NectarElAssets::locate(array('[nectar_slider')) || NectarElAssets::locate(array('type="nectarslider_style"')) ) {
		wp_enqueue_script( 'nectar-slider' );
	}

	// Touch swipe.
	wp_enqueue_script( 'touchswipe' );


	// Fancy select.
	$fancy_rcs = ( ! empty( $nectar_options['form-fancy-select'] ) ) ? $nectar_options['form-fancy-select'] : 'default';
	if ( $fancy_rcs === '1' ) {
		wp_enqueue_script( 'select2' );
	}


	// comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

add_action( 'wp_enqueue_scripts', 'nectar_page_specific_js' );


if( !function_exists('nectar_defer_parsing_of_jquery') ) {
	function nectar_defer_parsing_of_jquery( $wp_scripts ) {
		
		$wp_scripts->add_data( 'jquery', 'group', 1 );
		$wp_scripts->add_data( 'jquery-core', 'group', 1 );
		$wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
		$wp_scripts->add_data( 'jquery-blockui', 'group', 1 );
	}
}

global $nectar_options;

if( isset($nectar_options['defer-javascript']) &&
		!empty($nectar_options['defer-javascript']) &&
		'1' === $nectar_options['defer-javascript'] ) {

    $nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
  	$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

    if( false === $nectar_using_VC_front_end_editor && !is_admin() ) {
      add_action( 'wp_default_scripts', 'nectar_defer_parsing_of_jquery', 20 );
    }
}

add_action( 'admin_enqueue_scripts', 'salient_script_overrides', 90 );
function salient_script_overrides() {
	
	$use_ace_cdn = apply_filters('nectar_use_ace_editor_cdn', false);

	if ( $use_ace_cdn && wp_script_is( 'ace-editor-js' ) ) {
		wp_deregister_script( 'ace-editor-js' );

		wp_enqueue_script(
			'ace-editor-js',
			'https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.0/ace.min.js',
			array( 'jquery' ),
			'1.1.9',
			true
		);
	}
}