<?php
/**
 * Salient page header helpers
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Page header output.
 *
 * @since 2.0
 */
if ( !function_exists( 'nectar_page_header' ) ) {

	function nectar_page_header($postid) {

		global $nectar_options;
		global $post;
		global $nectar_theme_skin;
		global $woocommerce;

		$bg                 = apply_filters('nectar_page_header_bg_val', get_post_meta($postid, '_nectar_header_bg', true));
		$bg_color           = apply_filters('nectar_page_header_bg_color_val', get_post_meta($postid, '_nectar_header_bg_color', true));
		$bg_type            = apply_filters('nectar_page_header_bg_type_val', get_post_meta($postid, '_nectar_slider_bg_type', true));
		$height             = apply_filters('nectar_page_header_height_val', get_post_meta($postid, '_nectar_header_bg_height', true));
		$font_color         = apply_filters('nectar_page_header_font_color_val', get_post_meta($postid, '_nectar_header_font_color', true));
		$title              = get_post_meta($postid, '_nectar_header_title', true);
		$subtitle           = get_post_meta($postid, '_nectar_header_subtitle', true);
		$bg_overlay_color   = get_post_meta($postid, '_nectar_header_bg_overlay_color', true);
		$header_auto_title  = (!empty($nectar_options['header-auto-title']) && $nectar_options['header-auto-title'] === '1') ? true : false;
		$bg_position        = get_post_meta($postid, '_nectar_page_header_bg_alignment', true);

		$blog_post_type_list = array('post');
		if( has_filter('nectar_metabox_post_types_post_header') ) {
 		  $blog_post_type_list = apply_filters('nectar_metabox_post_types_post_header', $blog_post_type_list);
 	  	}
		$is_blog_header_post_type = (isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single()) ? true : false;

		if( empty($bg_position) ) {
			$bg_position = 'top';
		}
		
		if( empty($bg_type) ) {
			$bg_type = 'image_bg';
		}

		// Theme option: Automatically Add Page Title to Page Header.
    $pages_to_skip = apply_filters('nectar_auto_page_header_bypass',array());

    if( is_array($pages_to_skip) ) {
      foreach ($pages_to_skip as $page ) {
        if( is_page($page) ) {
          $header_auto_title = false;
        }
      }
    }

		if( $header_auto_title && is_page() && empty($title) ) {

			$title = esc_html( get_the_title() );
			$auto_header_bg_color        = ( isset($nectar_options['header-auto-title-bg-color']) && !empty($nectar_options['header-auto-title-bg-color'])) ? esc_html($nectar_options['header-auto-title-bg-color']) : false;
			$auto_header_featured_img    = ( isset($nectar_options['header-auto-title-use-featured-img']) && '1' === $nectar_options['header-auto-title-use-featured-img'] ) ? true : false;
			$auto_header_overlay_color   = ( isset($nectar_options['header-auto-title-overlay-color']) && !empty($nectar_options['header-auto-title-overlay-color'])) ? esc_html($nectar_options['header-auto-title-overlay-color']) : false;
			$auto_header_overlay_opacity = ( isset($nectar_options['header-auto-title-overlay-opacity']) && !empty($nectar_options['header-auto-title-overlay-opacity'])) ? esc_html($nectar_options['header-auto-title-overlay-opacity']) : false;
			
			// Auto page header bg color.
			if( empty($bg_color) ) {
				$bg_color = (!empty($nectar_options['overall-bg-color'])) ? $nectar_options['overall-bg-color'] : '#ffffff';
				
				if( $auto_header_bg_color ) {
					$bg_color = $auto_header_bg_color;
				}
				
			}
			
			// Auto page header overlay color default.
			if( empty($bg_overlay_color) ) {
				$bg_overlay_color = 'rgba(0,0,0,0.07)';
			}
			
			// Auto page header featured image.	
			if( $bg_type === 'image_bg' && empty($bg) && $auto_header_featured_img ) {
				
				if( has_post_thumbnail($post->ID) ) {
					$bg = wp_get_attachment_url( get_post_thumbnail_id() );
					$bg_position = 'center';
					
					if($auto_header_overlay_color) {
						$bg_overlay_color = $auto_header_overlay_color;
					}
					
				}
			}

			
			
			if( empty($height) ) {
				$height = '225';
			}

		} else {
			$title = get_post_meta($postid, '_nectar_header_title', true);
		}

		// Theme option: Single Post Header Inherits Featured Image
		$single_post_header_inherit_fi = (!empty($nectar_options['blog_post_header_inherit_featured_image'])) ? $nectar_options['blog_post_header_inherit_featured_image'] : '0';

		if( empty($bg) && $single_post_header_inherit_fi === '1' && $is_blog_header_post_type && $post->ID != 0 && is_single() ) {
			if( has_post_thumbnail($post->ID) ) {
				$bg = wp_get_attachment_url( get_post_thumbnail_id() );
				$bg_position = 'center';
			}
		}



		// Empty image bg.
		$early_exit = ( isset($post->post_type) && $post->post_type === 'page' && $bg_type === 'image_bg' && empty($bg_color) && empty($bg) && empty($height) && empty($title)) ? true : false;


		$headerRemoveStickiness     = (!empty($nectar_options['header-remove-fixed'])) ? $nectar_options['header-remove-fixed'] : '0';
		$header_format              = (!empty($nectar_options['header_format'])) ? $nectar_options['header_format'] : 'default';
		$condense_header_on_scroll  = (!empty($nectar_options['condense-header-on-scroll']) && $header_format === 'centered-menu-bottom-bar' && $headerRemoveStickiness != '1' && $nectar_options['condense-header-on-scroll'] === '1') ? 'true' : 'false';

		$parallax_bg     = get_post_meta($postid, '_nectar_header_parallax', true);
		$fullscreen_rows = get_post_meta($postid, '_nectar_full_screen_rows', true);
		$box_roll        = get_post_meta($postid, '_nectar_header_box_roll', true);
		$text_align      = get_post_meta($postid, '_nectar_page_header_alignment', true);
		$text_align_v    = get_post_meta($postid, '_nectar_page_header_alignment_v', true);


		if( $fullscreen_rows === 'on' || $early_exit ) {
			return;
		}

		$page_header_bg_attrs = '';
		$page_header_classes  = array();

		$product_archive_parallax_effect = ( isset($nectar_options['product_archive_header_parallax']) && '1' === $nectar_options['product_archive_header_parallax'] ) ? true : false;

		if( function_exists('woocommerce_page_title') && $woocommerce ) {

			$product_archive_header_size = ( isset($nectar_options['product_archive_header_size'] ) ) ? $nectar_options['product_archive_header_size'] : 'default';
			$product_archive_auto_height = ( isset($nectar_options['product_archive_header_auto_height'] ) ) ? $nectar_options['product_archive_header_auto_height'] : '0';

			// Woocommerce archives.
			if( is_product_category() || is_product_tag() || is_product_taxonomy() ) {

				$subtitle      = '';
				$title         = woocommerce_page_title(false);
				$cate          = get_queried_object();
				$t_id          = (property_exists($cate, 'term_id')) ? $cate->term_id : '';
				$product_terms = get_option( "taxonomy_$t_id" );
				$bg            = (!empty($product_terms['product_category_image'])) ? $product_terms['product_category_image'] : $bg;

				// Taxonomy custom alignment.
				$content_align 	= (isset($product_terms['product_category_header_content_align'])) ? $product_terms['product_category_header_content_align'] : '';
				if( !empty($content_align) && 'default' !== $content_align ) {
					$text_align = esc_attr($content_align);
					$text_align_v = 'middle';
				}

				if( 'contained' === $product_archive_header_size || true === $product_archive_parallax_effect ) {
					$parallax_bg  = '';
					$box_roll     = 'off';
				}
				if( '1' === $product_archive_auto_height ) {
					$text_align_v = 'middle';
					$text_align   = 'left';
				}
				if( true === $product_archive_parallax_effect ) {
					$page_header_classes[] = 'parallax-layer';
					$page_header_bg_attrs = ' data-n-parallax-bg="true" data-parallax-speed="fast" ';
				}

			}
			// Woocommerce main shop.
			else if( is_shop() ) {

				if( 'contained' === $product_archive_header_size || true === $product_archive_parallax_effect ) {
					$parallax_bg = '';
					$box_roll    = 'off';
				}
				if( '1' === $product_archive_auto_height ) {
					$text_align_v = 'middle';
					$text_align   = 'left';
				}
				if( true === $product_archive_parallax_effect ) {
					$page_header_classes[] = 'parallax-layer';
					$page_header_bg_attrs = ' data-n-parallax-bg="true" data-parallax-speed="fast" ';
				}

			}

		}

		$page_template      = get_post_meta($postid, '_wp_page_template', true);
		$display_sortable   = get_post_meta($postid, 'nectar-metabox-portfolio-display-sortable', true);
		$inline_filters     = (!empty($nectar_options['portfolio_inline_filters']) && $nectar_options['portfolio_inline_filters'] === '1') ? '1' : '0';
		$filters_id         = (!empty($nectar_options['portfolio_inline_filters']) && $nectar_options['portfolio_inline_filters'] === '1') ? 'portfolio-filters-inline' : 'portfolio-filters';

		$fullscreen_header  = (!empty($nectar_options['blog_header_type']) && $nectar_options['blog_header_type'] === 'fullscreen' && is_singular('post')) ? true : false;
		if( $is_blog_header_post_type && isset($nectar_options['blog_header_type']) && $nectar_options['blog_header_type'] === 'fullscreen' ) {
			$fullscreen_header = true;
		}
		

		$post_header_style  = (!empty($nectar_options['blog_header_type'])) ? $nectar_options['blog_header_type'] : 'default';
		$bottom_shadow      = get_post_meta($postid, '_nectar_header_bottom_shadow', true);
		$bg_overlay         = get_post_meta($postid, '_nectar_header_overlay', true);
		$text_effect        = get_post_meta($postid, '_nectar_page_header_text-effect', true);
		$blog_header_sizing = (isset($nectar_options['blog_header_sizing']) && !empty($nectar_options['blog_header_sizing'])) ? $nectar_options['blog_header_sizing'] : 'default';
		$animate_in_effect  = (!empty($nectar_options['header-animate-in-effect'])) ? $nectar_options['header-animate-in-effect'] : 'none';
		$on_blog_archive    = ((is_category() || is_author() || is_tag() || is_date()) && 'post' === get_post_type()) ? true : false;
		(!empty($display_sortable) && $display_sortable === 'on') ? $display_sortable = '1' : $display_sortable = '0';

		// Filter subtitle.
		$subtitle = apply_filters('nectar_page_header_subtitle', $subtitle);

		// If no title is entered for portfolio, still show the filters.
		if( $page_template === 'template-portfolio.php' && empty($title) ) {
			$title = get_the_title($post->ID);
		}

		if( (!empty($bg) || !empty($bg_color) || $bg_type === 'video_bg' || $bg_type === 'particle_bg') && false === $on_blog_archive ) {

			$social_img_src = (empty($bg)) ? 'none' : $bg;
			$bg = (empty($bg)) ? 'none' : $bg;

			if( $bg_type === 'image_bg' || $bg_type === 'particle_bg' ) {
				// Do not set #000 for default minimal page header
				if( empty($bg_color) && is_singular('post') && 'default_minimal' === $post_header_style ) {
					$bg_color = false;
				} else {
					(empty($bg_color)) ? $bg_color = '#000' : $bg_color;
				}

			}
			else {
				$bg = 'none'; // remove stnd bg image for video BG type
			}

			$bg_color_string = (!empty($bg_color)) ? 'background-color: '.esc_attr($bg_color).'; ' : null;

			if( $bg_type === 'particle_bg' ) {
				$rotate_timing 			= get_post_meta($postid, '_nectar_particle_rotation_timing', true);
				$disable_explosion 	= get_post_meta($postid, '_nectar_particle_disable_explosion', true);
				$shapes 						= get_post_meta($postid, '_nectar_canvas_shapes', true);
				if( empty($shapes) ) {
					$bg_type = 'image_bg';
				}
			}

			if( $bg_type === 'video_bg' ) {
				$video_webm  = get_post_meta($postid, '_nectar_media_upload_webm', true);
				$video_mp4   = get_post_meta($postid, '_nectar_media_upload_mp4', true);
				$video_ogv   = get_post_meta($postid, '_nectar_media_upload_ogv', true);
				$video_image = get_post_meta($postid, '_nectar_slider_preview_image', true);
				if( empty($video_webm) && empty($video_mp4) && empty($video_ogv) && empty($title)) {
					return;
				}
			}


			if(!empty($nectar_options['boxed_layout']) && $nectar_options['boxed_layout'] === '1' || $condense_header_on_scroll == 'true') {
				$box_roll = 'off';
			}

			if( $post_header_style === 'default_minimal' && (isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single())) {
				$height = (!empty($height)) ? preg_replace('/\s+/', '', $height) : 550;
			} else {
				$height = (!empty($height)) ? preg_replace('/\s+/', '', $height) : 350;
			}

			// Mobile padding calc.
			if( intval($height) < 350 ) {
				$mobile_padding_influence = 'low';
			} else if(intval($height) < 600) {
				$mobile_padding_influence = 'normal';
			} else {
				$mobile_padding_influence = 'high';
			}

			// Blog header effect.
			$post_header_parallax = ( isset($nectar_options['blog_header_scroll_effect']) && !empty($nectar_options['blog_header_scroll_effect'])) ? $nectar_options['blog_header_scroll_effect'] : 'default';
			if( is_singular('post') && 'parallax' === $post_header_parallax ) {
				$parallax_bg = 'on';
			}

			// Blog responsive sizing.
			$responsive_sizing = '';
			$responsive_sizing_bool = false;

			if( 'responsive' === $blog_header_sizing && is_singular('post') && true !== $fullscreen_header ) {
				$height = '75vh';
				$responsive_sizing = ' data-responsive="true"';
				$responsive_sizing_bool = true;
			}

			$not_loaded_class       = ($nectar_theme_skin !== 'ascend') ? "not-loaded " : null;
			$page_fullscreen_header = get_post_meta($postid, '_nectar_header_fullscreen', true);
			$fullscreen_class       = ($fullscreen_header == true || $page_fullscreen_header === 'on') ? "fullscreen-header" : '';
			$bottom_shadow_class    = ($bottom_shadow === 'on') ? " bottom-shadow": null;
			$bg_overlay_class       = ($bg_overlay === 'on') ? " bg-overlay": null;
			$ajax_page_loading      = (!empty($nectar_options['ajax-page-loading']) && $nectar_options['ajax-page-loading'] === '1') ? true : false;

			$hentry_post_class = ( isset($post->post_type) && $post->post_type === 'post' && is_single() ) ? apply_filters('nectar_post_header_hentry_class', ' hentry') : '';

			if($animate_in_effect === 'slide-down' && true !== $responsive_sizing_bool ) {
				$wrapper_height_style = null;
			} elseif( strpos($height,'vh') !== false ) {
				$wrapper_height_style = 'style="height: '.$height.';"';
			} else {
				$wrapper_height_style = 'style="height: '.$height.'px;"';
			}

			// Disable slide down for fullscreen headers.
			if($fullscreen_header == true && $is_blog_header_post_type || 
			   $page_fullscreen_header === 'on') {
				$wrapper_height_style = null;
			}

			// Force transparent coloring.
			$force_transparent_header_color = nectar_get_forced_transparent_header_color();
			if(empty($force_transparent_header_color)) {
				$force_transparent_header_color = 'light';
			}

			$midnight_non_parallax = (!empty($parallax_bg) && $parallax_bg === 'on') ? null : 'data-midnight="light"';
			$regular_page_header_midnight_override = 'data-midnight="'.$force_transparent_header_color.'"';

			$page_header_wrap_classes = array();

			if( !empty($fullscreen_class) ) {
				$page_header_wrap_classes[] = $fullscreen_class;
			}

			$page_header_wrap_classes = apply_filters('nectar_page_header_wrap_class_name', $page_header_wrap_classes);
			$page_header_wrap_classes = implode( ' ', $page_header_wrap_classes );
			$page_header_classes      = implode( ' ', $page_header_classes );

			// Begin output.
			if( $box_roll !== 'on' ) {
				echo '<div id="page-header-wrap" data-animate-in-effect="'. esc_attr($animate_in_effect) .'"'.$responsive_sizing.' data-midnight="'.esc_attr($force_transparent_header_color).'" class="'.esc_attr($page_header_wrap_classes).'" '.$wrapper_height_style.'>';
			}

			// Box roll effect.
			if( !empty($box_roll) && $box_roll === 'on' ) {
				wp_enqueue_style('box-roll');
				echo '<div class="nectar-box-roll">';
			}

			// Starting fullscreen height.
			if( $page_fullscreen_header === 'on' || $fullscreen_header == true ) {
				$starting_height = ' ';
			} elseif( strpos($height,'vh') !== false ) {
				$starting_height = 'height:' . esc_attr($height) . ';';
			} else {
				$starting_height = 'height:' . esc_attr($height) . 'px;';
			}

			// Inner page header data-attrs
			$nectar_page_header_attrs = '';
			if( isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single() ) {
				$nectar_page_header_attrs .= 'data-post-hs="'. esc_attr( $post_header_style ) .'" ';
			}
			$nectar_page_header_attrs .= 'data-padding-amt="'.esc_attr( $mobile_padding_influence ).'" ';
			$nectar_page_header_attrs .= 'data-animate-in-effect="'.esc_attr( $animate_in_effect ).'" ';
			$nectar_page_header_attrs .= 'data-midnight="'.esc_attr($force_transparent_header_color).'" ';
			$nectar_page_header_attrs .= 'data-text-effect="'.esc_attr( $text_effect ).'" ';
			$nectar_page_header_attrs .= 'data-bg-pos="'. esc_attr( $bg_position ). '" ';
			$nectar_page_header_attrs .= (!empty($text_align)) ? 'data-alignment="'.esc_attr($text_align).'" ': 'data-alignment="left" ';
			$nectar_page_header_attrs .= (!empty($text_align_v)) ? 'data-alignment-v="'.esc_attr($text_align_v).'" ' : 'data-alignment-v="middle" ';
			$nectar_page_header_attrs .= (!empty($parallax_bg) && $parallax_bg == 'on') ? 'data-parallax="1" ': 'data-parallax="0" ';
			$nectar_page_header_attrs .= (!empty($height)) ? 'data-height="'.esc_attr($height).'" ': 'data-height="350" ';

			// Inner page header classes
			$nectar_page_header_classes = esc_attr($not_loaded_class) . esc_attr($fullscreen_class) . esc_attr($bottom_shadow_class) . esc_attr($hentry_post_class) . esc_attr($bg_overlay_class);

			// Begin inner page header output:
      if( function_exists('nectar_responsive_page_header_css') ) {
        echo nectar_responsive_page_header_css( $bg, '#page-header-bg .page-header-bg-image' );
      }
      
			echo '<div id="page-header-bg" class="'.$nectar_page_header_classes.'" '.$nectar_page_header_attrs.' style="'. $bg_color_string . $starting_height .'">';

				// BG markup.
				if( !empty($bg) && $bg !== 'none' ) { ?>
					<div class="page-header-bg-image-wrap" id="nectar-page-header-p-wrap"<?php if(!empty($page_header_bg_attrs) ) { echo ' ' . $page_header_bg_attrs; } ?> data-parallax-speed="fast">
						<div class="page-header-bg-image<?php if(!empty($page_header_classes)) { echo ' ' . esc_attr($page_header_classes); } ?>" style="background-image: url(<?php echo esc_attr( nectar_options_img($bg) ); ?>);"></div>
					</div> <?php }

				// Overlay Markup.
				if( !empty($bg_overlay_color) ) {
					$overlay_opacity = get_post_meta($postid, '_nectar_header_bg_overlay_opacity', true);
					$overlay_opacity_amount = empty($overlay_opacity) ? 'default' : $overlay_opacity;
					?>
					<div class="page-header-overlay-color" data-overlay-opacity="<?php echo esc_attr($overlay_opacity_amount); ?>" style="background-color: <?php echo esc_attr( $bg_overlay_color ); ?>;"></div>
				<?php } ?>

				<?php if( $bg_type !== 'particle_bg' ) { echo '<div class="container">'; }


				// Portfolio Single Header.
				if( $post->ID != 0 && $post->post_type && $post->post_type === 'portfolio' ) { ?>

					<div class="row project-title">
						<div class="container">
							<div class="col span_6 section-title <?php if(empty($nectar_options['portfolio_social']) || $nectar_options['portfolio_social'] === '0' || empty($nectar_options['portfolio_date']) || $nectar_options['portfolio_date'] === '0' ) { echo 'no-date'; } ?>">
								<div class="inner-wrap">
									<h1><?php the_title(); ?></h1>
									<?php if(!empty($subtitle) || has_filter('salient_portfolio_single_subtitle') ) { ?> <span class="subheader"><?php echo wp_kses_post( apply_filters('salient_portfolio_single_subtitle', $subtitle) ); ?></span> <?php }

									global $nectar_options;
									$single_nav_pos = (!empty($nectar_options['portfolio_single_nav'])) ? $nectar_options['portfolio_single_nav'] : 'in_header';

									if( $single_nav_pos === 'in_header' && function_exists('nectar_project_single_controls') ) {
										nectar_project_single_controls();
									} ?>
								</div>
							</div>
						</div>
					</div><!--/row-->


				<?php }

				// Blog Single header.
				elseif( $post->ID != 0 && in_array($post->post_type, $blog_post_type_list) && is_single() ) {


					if( $social_img_src !== 'none' ) {
						echo '<img loading="lazy" class="hidden-social-img" src="'.esc_url($social_img_src).'" alt="'.get_the_title().'" style="display: none;" />';
					}

					$remove_single_post_date           = (!empty($nectar_options['blog_remove_single_date'])) ? $nectar_options['blog_remove_single_date'] : '0';
					$remove_single_post_author         = (!empty($nectar_options['blog_remove_single_author'])) ? $nectar_options['blog_remove_single_author'] : '0';
					$remove_single_post_comment_number = (!empty($nectar_options['blog_remove_single_comment_number'])) ? $nectar_options['blog_remove_single_comment_number'] : '0';
					$remove_single_post_nectar_love    = (!empty($nectar_options['blog_remove_single_nectar_love'])) ? $nectar_options['blog_remove_single_nectar_love'] : '0';
					$category_display                  = ( isset($nectar_options['blog_header_category_display'])) ? $nectar_options['blog_header_category_display'] : 'default';
					?>

					<div class="row">
						<div class="col span_6 section-title blog-title" data-remove-post-date="<?php echo esc_attr( $remove_single_post_date ); ?>" data-remove-post-author="<?php echo esc_attr( $remove_single_post_author ); ?>" data-remove-post-comment-number="<?php echo esc_attr( $remove_single_post_comment_number ); ?>">
							<div class="inner-wrap">

								<?php
								global $nectar_options;
								$theme_skin = (!empty($nectar_options['theme-skin'])) ? $nectar_options['theme-skin'] : 'default';
								
								do_action('nectar_single_post_header_before_title');

								if( (in_array($post->post_type, $blog_post_type_list) && is_single()) && $post_header_style === 'default_minimal' ||
								(in_array($post->post_type, $blog_post_type_list) && is_single()) && $fullscreen_header == true && $theme_skin === 'material') {

									$categories = get_the_category();
									if ( ! empty( $categories ) ) {
										$output = nectar_get_category_list();
										echo apply_filters('nectar_blog_page_header_categories',trim( $output ));
									}
								} ?>

								<h1 class="entry-title"><?php the_title(); ?></h1>

								<?php if( (in_array($post->post_type, $blog_post_type_list) && is_single()) && $fullscreen_header == true ) { ?>
									<div class="author-section">
										<span class="meta-author">
											<?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), 100 ); }?>
										</span>
										<div class="avatar-post-info vcard author">
											<span class="fn"><?php the_author_posts_link(); ?></span>

											<?php
											$date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';
											if( '1' !== $remove_single_post_date ) {
												if( 'last_editied_date' === $date_functionality ) {
													echo '<span class="meta-date date updated"><i>'.get_the_modified_date().'</i></span>';
												} else {
													$nectar_u_time 					= get_the_time('U');
													$nectar_u_modified_time = get_the_modified_time('U');
													if( $nectar_u_modified_time >= $nectar_u_time + 86400 ) { ?>
														<span class="meta-date date published"><i><?php echo get_the_date(); ?></i></span>
														<span class="meta-date date updated rich-snippet-hidden"><i><?php echo get_the_modified_time(__( 'F jS, Y' , 'salient' )); ?></i></span>
													<?php } else { ?>
														<span class="meta-date date updated"><i><?php echo get_the_date(); ?></i></span>
													<?php }
												} 
											} ?>

										</div>
									</div>
								<?php } ?>


								<?php if( $fullscreen_header != true ) {

									$blog_social_style = ( get_option( 'salient_social_button_style' ) ) ? get_option( 'salient_social_button_style' ) : 'fixed';
									$using_fixed_salient_social = 'false';
									if( function_exists('nectar_social_sharing_output') && 'default' === $blog_social_style ) {
										$using_fixed_salient_social = 'true';
									}
									?>
									<div id="single-below-header" data-hide-on-mobile="<?php echo esc_attr($using_fixed_salient_social); ?>">
										<?php echo '<span class="meta-author vcard author"><span class="fn"><span class="author-leading">' . esc_html__('By', 'salient') . '</span> ' . get_the_author_posts_link() . '</span></span>';
										$date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';
										
										if( '1' !== $remove_single_post_date ) {
											if( 'last_editied_date' === $date_functionality ) {
												echo '<span class="meta-date date updated"><i>'.get_the_modified_date().'</i></span>';
											} else {
												$nectar_u_time 					= get_the_time('U');
												$nectar_u_modified_time = get_the_modified_time('U');
												if( $nectar_u_modified_time >= $nectar_u_time + 86400 ) {
													echo '<span class="meta-date date published">' . get_the_date() . '</span>';
													echo '<span class="meta-date date updated rich-snippet-hidden">' . get_the_modified_time(__( 'F jS, Y' , 'salient' )) . '</span>';
												} else {
													echo '<span class="meta-date date updated">' . get_the_date() . '</span>';
												}
											}
										}

										if($post_header_style != 'default_minimal') {
											echo '<span class="meta-category">'.apply_filters('nectar_blog_page_header_categories',nectar_get_category_list(',')) .'</span>';
										} else {
											echo '<span class="meta-comment-count"><a href="'.get_comments_link().'">'. get_comments_number_text( esc_html__('No Comments', 'salient'), esc_html__('One Comment', 'salient'), esc_html__('% Comments', 'salient') ) . '</a></span>';
										}
                    $remove_reading_dur = ( isset( $nectar_options['blog_remove_single_reading_dur'] ) ) ? $nectar_options['blog_remove_single_reading_dur'] : '0';
                      if( '1' !== $remove_reading_dur ) {
                        echo '<span class="meta-reading-time">'.nectar_estimated_reading_time(get_the_content()). ' '. esc_html__('min read','salient').'</span>';
                      }
										?>
									</div><!--/single-below-header-->
						<?php } ?>

						<?php if( $fullscreen_header != true && $post_header_style !== 'default_minimal' ) { ?>

							<div id="single-meta">

								<div class="meta-comment-count">
									<a href="<?php comments_link(); ?>"><i class="icon-default-style steadysets-icon-chat-3"></i> <?php comments_number( esc_html__('No Comments', 'salient'), esc_html__('One Comment', 'salient'), esc_html__('% Comments', 'salient') ); ?></a>
								</div>

								<?php
								$blog_social_style = ( get_option( 'salient_social_button_style' ) ) ? get_option( 'salient_social_button_style' ) : 'fixed';

								if( $blog_social_style !== 'fixed') {

									if( function_exists('nectar_social_sharing_output') ) {
										nectar_social_sharing_output('hover','right');
									}

								}
								?>

							</div><!--/single-meta-->

						<?php } //end if theme skin default
						
						do_action('nectar_single_post_header_after_title');
						
						?>
					</div>

				</div><!--/section-title-->
				<?php do_action('nectar_single_post_header_after_content'); ?>
			</div><!--/row-->

			<?php

		}

		// Pages.
		else if( $bg_type !== 'particle_bg' ) {

			if( !empty($box_roll) && $box_roll === 'on' ) {
				$alignment 		= (!empty($text_align)) ? $text_align : 'left';
				$v_alignment 	= (!empty($text_align_v)) ? $text_align_v : 'middle';
				echo '<div class="overlaid-content" data-text-effect="'.esc_attr($text_effect).'" data-alignment="'.esc_attr($alignment).'" data-alignment-v="'.esc_attr($v_alignment).'"><div class="container">';
			}

			// CPT title when using featured image bg
			if( empty($title) && is_single() && !is_page() )  {
				$title = esc_html( get_the_title($post->ID) );
			}

			$empty_title_class = (empty($title) && empty($subtitle)) ? 'empty-title' : '';

			?>

			<div class="row">
				<div class="col span_6 <?php echo esc_attr( $empty_title_class ); ?>">
					<div class="inner-wrap">
						<?php if(!empty($title)) { ?><h1><?php echo wp_kses_post( $title ); ?></h1> <?php } ?>
						<span class="subheader"><?php echo wp_kses_post( $subtitle ); ?></span>
					</div>

					<?php // portfolio filters
					if( $page_template === 'template-portfolio.php' && $display_sortable === '1' && $inline_filters === '0' ) { ?>
						<div class="<?php echo esc_attr( $filters_id );?>" instance="0">
							<a href="#" data-sortable-label="<?php echo (!empty($nectar_options['portfolio-sortable-text'])) ? wp_kses_post( $nectar_options['portfolio-sortable-text'] ) :'Sort Portfolio'; ?>" id="sort-portfolio"><span><?php echo (!empty($nectar_options['portfolio-sortable-text'])) ? wp_kses_post( $nectar_options['portfolio-sortable-text'] ) : esc_html__('Sort Portfolio','salient'); ?></span> <i class="icon-angle-down"></i></a>
							<ul>
								<li><a href="#" data-filter="*"><?php echo esc_html__('All', 'salient'); ?></a></li>
								<?php wp_list_categories(array(
									'title_li' => '',
									'taxonomy' => 'project-type',
									'show_option_none'   => '',
									'walker' => new Walker_Portfolio_Filter())); ?>
								</ul>
							</div>
						<?php } ?>
					</div>
				</div>

				<?php if( !empty($box_roll) && $box_roll === 'on' ) {
					echo '</div></div><!--/overlaid-content-->';
				}

			} ?>



			<?php if( $bg_type !== 'particle_bg' ) { echo '</div>'; } // closing container


			// "Scroll down" icon link markup.
			if( ($post->ID != 0 && in_array($post->post_type, $blog_post_type_list) && is_single()) && $fullscreen_header == true || $page_fullscreen_header === 'on' ) {

				$rotate_in_class 			 	 = ( $text_effect === 'rotate_in') ? 'hidden' : null;
				$button_styling 				 = (!empty($nectar_options['button-styling'])) ? $nectar_options['button-styling'] : 'default';
				$header_down_arrow_style = (!empty($nectar_options['header-down-arrow-style'])) ? $nectar_options['header-down-arrow-style'] : 'default';

				if( $header_down_arrow_style === 'animated-arrow' ) {
					echo '<div class="scroll-down-wrap minimal-arrow nectar-next-section-wrap"><a href="#" class="minimal-arrow">
			      <svg class="next-arrow" width="40px" height="68px" viewBox="0 0 40 50" xml:space="preserve">
			      <path stroke="#ffffff" stroke-width="2" fill="none" d="M 20 0 L 20 51"></path>
			      <polyline stroke="#ffffff" stroke-width="2" fill="none" points="12, 44 20, 52 28, 44"></polyline>
			      </svg>
			    </a></div>';
				}
				else if( $header_down_arrow_style === 'scroll-animation' || $button_styling === 'slightly_rounded' || $button_styling === 'slightly_rounded_shadow' ) {
					echo '<div class="scroll-down-wrap no-border"><a href="#" class="section-down-arrow '.$rotate_in_class.'"><svg class="nectar-scroll-icon" viewBox="0 0 30 45" enable-background="new 0 0 30 45">
					<path class="nectar-scroll-icon-path" fill="none" stroke="#ffffff" stroke-width="2" stroke-miterlimit="10" d="M15,1.118c12.352,0,13.967,12.88,13.967,12.88v18.76  c0,0-1.514,11.204-13.967,11.204S0.931,32.966,0.931,32.966V14.05C0.931,14.05,2.648,1.118,15,1.118z"></path>
					</svg></a></div>';
				}
				else {

					if( $button_styling === 'default' ) {
						echo '<div class="scroll-down-wrap"><a href="#" class="section-down-arrow '.$rotate_in_class.'"><i class="icon-salient-down-arrow icon-default-style"> </i></a></div>';
					} else {
						echo '<div class="scroll-down-wrap '.$rotate_in_class.'"><a href="#" class="section-down-arrow"><i class="fa fa-angle-down top"></i><i class="fa fa-angle-down"></i></a></div>';
					}
				}

			}


			// Video Background.
			if( $bg_type === 'video_bg' ) {

				// parse video image.
				if( strpos($video_image, "http://") !== false || strpos($video_image, "https://") !== false ){
					$video_image_src = nectar_options_img( $video_image );
				} else {
					$video_image_src = wp_get_attachment_image_src($video_image, 'full');
					$video_image_src = isset($video_image_src[0]) ? $video_image_src[0] : '';
				}


				echo '<div class="video-color-overlay" data-color="'. esc_attr($bg_color) .'"></div>';
				echo '<div class="mobile-video-image" style="background-image: url('. esc_url($video_image_src) .')"></div>';

				echo '<div class="nectar-video-wrap" data-bg-alignment="'. esc_attr( $bg_position ).'">';

				echo '<video class="nectar-video-bg" width="1800" height="700" preload="auto" loop autoplay muted playsinline>';
				if(!empty($video_webm)) { echo '<source type="video/webm" src="'. esc_url( nectar_video_src_from_wp_attachment( $video_webm ) ).'">'; }
				if(!empty($video_mp4)) { echo '<source type="video/mp4" src="'. esc_url( nectar_video_src_from_wp_attachment( $video_mp4 ) ).'">'; }
				if(!empty($video_ogv)) { echo '<source type="video/ogg" src="'. esc_url( nectar_video_src_from_wp_attachment( $video_ogv ) ).'">'; }
				echo '</video></div>';

			}

			// HTML5 Canvas BG.
			if( $bg_type === 'particle_bg' ) {

				wp_enqueue_script('nectar-particles');

				echo '<div class=" nectar-particles" data-disable-explosion="'.esc_attr($disable_explosion).'" data-rotation-timing="'.esc_attr($rotate_timing).'"><div class="canvas-bg"><canvas id="canvas" data-active-index="0"></canvas></div>';

				$images = explode( ',', $shapes);
				$i = 0;

				if( !empty($shapes) ) {

					if( !empty($box_roll) && $box_roll === 'on' ) {
						$alignment 		= (!empty($text_align)) ? $text_align : 'left';
						$v_alignment 	= (!empty($text_align_v)) ? $text_align_v : 'middle';
						echo '<div class="overlaid-content" data-text-effect="'.esc_attr($text_effect).'" data-alignment="'.esc_attr($alignment).'" data-alignment-v="'.esc_attr($v_alignment).'">';
					}

					echo '<div class="container"><div class="row"><div class="col span_6" >';

					foreach ( $images as $attach_id ) {

						$i++;

						$img        = wp_get_attachment_image_src(  $attach_id, 'full' );
						$attachment = get_post( $attach_id );
						$shape_meta = array(
							'caption'           => $attachment->post_excerpt,
							'title'             => $attachment->post_title,
							'bg_color'          => get_post_meta( $attachment->ID, 'nectar_particle_shape_bg_color', true ),
							'color'             => get_post_meta( $attachment->ID, 'nectar_particle_shape_color', true ),
							'color_mapping'     => get_post_meta( $attachment->ID, 'nectar_particle_shape_color_mapping', true ),
							'alpha'             => get_post_meta( $attachment->ID, 'nectar_particle_shape_color_alpha', true ),
							'density'           => get_post_meta( $attachment->ID, 'nectar_particle_shape_density', true ),
							'max_particle_size' => get_post_meta( $attachment->ID, 'nectar_particle_max_particle_size', true )
						);

						if( !empty($shape_meta['density']) ) {
							switch($shape_meta['density']) {
								case 'very_low':
									$shape_meta['density'] = '19';
								break;
								case 'low':
									$shape_meta['density'] = '16';
								break;
								case 'medium':
									$shape_meta['density'] = '13';
								break;
								case 'high':
									$shape_meta['density'] = '10';
								break;
								case 'very_high':
									$shape_meta['density'] = '8';
								break;
							}
						}

						if( !empty($shape_meta['color']) && $shape_meta['color'] === '#fff' || !empty($shape_meta['color']) && $shape_meta['color'] === '#ffffff' ) {
							$shape_meta['color'] = '#fefefe';
						}

						// Data for particle shape.
						echo '<div class="shape" data-src="'. nectar_ssl_check($img[0]) .'" data-max-size="'.esc_attr($shape_meta['max_particle_size']).'" data-alpha="'.esc_attr($shape_meta['alpha']).'" data-density="'.esc_attr($shape_meta['density']).'" data-color-mapping="'.esc_attr($shape_meta['color_mapping']).'" data-color="'.esc_attr($shape_meta['color']).'" data-bg-color="'.esc_attr($shape_meta['bg_color']).'"></div>';

						// Overlaid content markup.
						echo '<div class="inner-wrap shape-'.$i.'">';
						echo '<h1>'.$shape_meta["title"].'</h1> <span class="subheader">'.$shape_meta["caption"].'</span>';
						echo '</div>';

					} ?>

				</div>
			</div>
		</div>

		<div class="pagination-navigation">
			<div class="pagination-current"></div>
			<div class="pagination-dots">
				<?php foreach ( $images as $attach_id ) {
					echo '<button class="pagination-dot"></button>';
				} ?>
			</div>
		</div>
		<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="690">
			<defs>
				<filter id="goo">
					<feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
					<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 69 -16" result="goo"></feColorMatrix>
					<feComposite in="SourceGraphic" in2="goo" operator="atop"></feComposite>
				</filter>
			</defs>
		</svg>

		<?php if(!empty($box_roll) && $box_roll === 'on') echo '</div><!--/overlaid-content-->'; ?>

	</div> <!--/nectar particles-->

<?php }
} //particle bg ?>

</div>

<?php

echo '</div>';

}

// Archives.
else if( !empty($title) && !is_archive() ) { ?>

	<div class="row page-header-no-bg" data-alignment="<?php echo (!empty($text_align)) ? esc_attr($text_align) : 'left' ; ?>">
		<div class="container">
			<div class="col span_12 section-title">
				<h1><?php echo wp_kses_post( $title ); ?><?php if(!empty($subtitle)) { echo '<span>' . wp_kses_post( $subtitle ) . '</span>'; } ?></h1>

				<?php // portfolio filters
				if( $page_template === 'template-portfolio.php' && $display_sortable === '1' && $inline_filters === '0') { ?>
					<div class="<?php echo esc_attr( $filters_id ) ;?>" instance="0">

						<a href="#" data-sortable-label="<?php echo (!empty($nectar_options['portfolio-sortable-text'])) ? wp_kses_post( $nectar_options['portfolio-sortable-text'] ) :'Sort Portfolio'; ?>" id="sort-portfolio"><span><?php echo (!empty($nectar_options['portfolio-sortable-text'])) ? wp_kses_post( $nectar_options['portfolio-sortable-text'] ) : esc_html__('Sort Portfolio','salient'); ?></span> <i class="icon-angle-down"></i></a>

						<ul>
							<li><a href="#" data-filter="*"><?php echo esc_html__('All', 'salient'); ?></a></li>
							<?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'project-type', 'show_option_none'   => '', 'walker' => new Walker_Portfolio_Filter())); ?>
						</ul>
					</div>
				<?php } ?>

			</div>
		</div>

	</div>

<?php }

// Blog Archives.
else if( is_category() || is_tag() || is_date() || is_author() ) {

	$archive_bg_img = (isset($nectar_options['blog_archive_bg_image'])) ? nectar_options_img($nectar_options['blog_archive_bg_image']) : null;
	$t_id 					=  get_cat_ID( single_cat_title( '', false ) ) ;
	$terms 					=  get_option( "taxonomy_$t_id" );
	$heading 				= '';
	$subtitle			 	= '';

	if( is_author() ) {
		$heading =  get_the_author();
		$subtitle = esc_html__('All Posts By', 'salient' );

	}
	else if( is_category() ) {
		$heading =  single_cat_title( '', false );
		$subtitle = esc_html__('Category', 'salient' );

	}
	else if( is_tag() ) {
		$heading = single_tag_title( '', false );
		$subtitle = esc_html__('Tag', 'salient' );

	}
	else if( is_date() ){

		if ( is_day() ) {
			$heading  = get_the_date();
			$subtitle = esc_html__('Daily Archives', 'salient' );
		}
		elseif ( is_month() ) {
			$heading  = get_the_date( _x( 'F Y', 'monthly archives date format', 'salient' ) );
			$subtitle = esc_html__('Monthly Archives', 'salient' );
		}
		elseif ( is_year() ) {
			$heading  =  get_the_date( _x( 'Y', 'yearly archives date format', 'salient' ) );
			$subtitle = esc_html__('Yearly Archives', 'salient' );
		}
		else {
			$heading = esc_html__( 'Archives', 'salient' );
		}

	}
	else {
		$heading = wp_title("",false);
	}

	$heading  = apply_filters('nectar_archive_header_heading_text', $heading);
	$subtitle = apply_filters('nectar_archive_header_sub_text', $subtitle);

	// Category archive text align.
	$blog_type = $nectar_options['blog_type'];

	if( $blog_type == null ) {
		$blog_type = 'std-blog-sidebar';
	}

	$blog_standard_type        = ( !empty($nectar_options['blog_standard_type'])) ? $nectar_options['blog_standard_type'] : 'classic';
	$archive_header_text_align = ( $blog_type !== 'masonry-blog-sidebar' && $blog_type !== 'masonry-blog-fullwidth' && $blog_type !== 'masonry-blog-full-screen-width' && $blog_standard_type === 'minimal') ? 'center' : 'left';
	$archive_header_bg_func    = ( !empty($nectar_options['blog_archive_bg_functionality']) ) ? $nectar_options['blog_archive_bg_functionality'] : 'image';
	
	$skip_subtitle = false;
	if( isset(NectarThemeManager::$options['blog_archive_format']) &&
		NectarThemeManager::$options['blog_archive_format'] === 'minimal' ) {
			$skip_subtitle = true;
	}
	if( (!empty($terms['category_image']) || !empty($archive_bg_img)) && $archive_header_bg_func !== 'color') {

		$bg_img = $archive_bg_img;
		if(!empty($terms['category_image'])) {
			$bg_img = $terms['category_image'];
		}

		?>

		<div id="page-header-wrap" data-animate-in-effect="<?php echo esc_attr( $animate_in_effect ); ?>" data-midnight="light" class="blog-archive-header">
			<div id="page-header-bg" data-animate-in-effect="<?php echo esc_attr( $animate_in_effect ); ?>" data-text-effect="" data-bg-pos="center" data-alignment="<?php echo esc_attr( $archive_header_text_align ); ?>" data-alignment-v="middle" data-parallax="0" data-height="400">
				<div class="page-header-bg-image" style="background-image: url(<?php echo esc_url( $bg_img ); ?>);"></div>
				<div class="container">
					<div class="row">
						<div class="col span_6">
							<div class="inner-wrap">
								<?php do_action('nectar_archive_header_before_title'); ?>
								<?php if( !$skip_subtitle ) { ?>
									<span class="subheader"><?php echo wp_kses_post( $subtitle ); ?></span>
								<?php } ?>
								<h1><?php echo wp_kses_post( $heading ); do_action('nectar_archive_header_in_title'); ?></h1>
								<?php if( is_category() ) {
									echo category_description();
								} else if( is_tag() ) {
									echo tag_description();
								} else if ( is_author() ) {
									echo '<p>' . get_the_author_meta('description') . '</p>';
								} ?>
								<?php do_action('nectar_archive_header_after_title'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }

	else { ?>
		<?php 
			$page_header_class_names = 'row page-header-no-bg blog-archive-header';
		?>
		<div class="<?php echo apply_filters('nectar_archive_header_classes', $page_header_class_names); ?>" <?php do_action('nectar_archive_header_attrs'); ?> data-alignment="<?php echo (!empty($text_align)) ? $text_align : 'left' ; ?>">
			<div class="container">
				<div class="col span_12 section-title">
					<?php do_action('nectar_archive_header_before_title'); ?>
					<?php if( !$skip_subtitle ) { ?>
						<span class="subheader"><?php echo wp_kses_post( $subtitle ); ?></span>
					<?php } ?>
					<h1><?php echo wp_kses_post( $heading ); do_action('nectar_archive_header_in_title'); ?></h1>
					<?php if( is_category() ) {
						echo category_description();
					} else if( is_tag() ) {
						echo tag_description();
					} else if ( is_author() ) {
						echo '<p>' . get_the_author_meta('description') . '</p>';
					} ?>
					<?php do_action('nectar_archive_header_after_title'); ?>
				</div>
			</div>
		</div>

	<?php
			}
		}
	}
}



/**
 * Swaps the background image as needed for each device viewport
 *
 * @since 13.1
 * @param string $image_id The image id.
 */

if( !function_exists('nectar_responsive_page_header_css') ) {

  function nectar_responsive_page_header_css( $image_id, $selector ) {

    global $nectar_options;

    // Page header responsive images.
    if( isset($nectar_options['page_header_responsive_images']) && 
        '1' === $nectar_options['page_header_responsive_images'] && 
        function_exists('attachment_url_to_postid') ) {

        $located_id = $image_id;
        
        // URL passed
        if( !preg_match('/^\d+$/', $image_id) ) {

          $located_id = attachment_url_to_postid($image_id);
          if( !$located_id ) {
            return;
          }

        }

        $tablet_image_src = wp_get_attachment_image_url( $located_id , 'large' ); 
        $mobile_image_src = wp_get_attachment_image_url( $located_id , 'medium_large' ); 

        if( !$tablet_image_src || !$mobile_image_src ) {
          return;
        }

        $css = '@media only screen and (max-width: 999px) and (min-width: 691px) {
          '.$selector.' {
             background-image: url("'.esc_url($tablet_image_src).'")!important;
          }
        }
        @media only screen and (max-width: 690px) {
          '.$selector.' {
            background-image: url("'.esc_url($mobile_image_src).'")!important;
         }
        }';

        return '<style type="text/css">' . $css . '</style>';
    } // end using page header responsive theme option.
    
  }

}


/**
 * Check if a page header or other applicable element/option is active
 * to trigger the transparent header navigation.
 *
 * @since 2.0
 */
if ( !function_exists( 'nectar_using_page_header' ) ) {

	function nectar_using_page_header($post_id) {

		 global $post;
		 global $woocommerce;
		 global $nectar_options;

		 $force_effect = null;

		 if( $woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag() ) {

		 	if( version_compare( $woocommerce->version, "3.0", ">=" ) ) {

				$header_title    = get_post_meta(wc_get_page_id('shop'), '_nectar_header_title', true);
				$header_bg       = get_post_meta(wc_get_page_id('shop'), '_nectar_header_bg', true);
				$header_bg_color = get_post_meta(wc_get_page_id('shop'), '_nectar_header_bg_color', true);
				$bg_type         = get_post_meta(wc_get_page_id('shop'), '_nectar_slider_bg_type', true);
				if(empty($bg_type)) {
					$bg_type = 'image_bg';
				}
				$disable_effect = get_post_meta(wc_get_page_id('shop'), '_disable_transparent_header', true);
				$force_effect = null;

				// product category images.
				$product_archive_header_size = ( isset($nectar_options['product_archive_header_size'] ) ) ? $nectar_options['product_archive_header_size'] : 'default';
				if( is_product_category() && $product_archive_header_size !== 'contained') {
					$cate          = get_queried_object();
					$t_id          = ( property_exists( $cate, 'term_id' ) ) ? $cate->term_id : '';
					$product_terms = get_option( "taxonomy_$t_id" );
					if( ! empty( $product_terms['product_category_image'] ) ) {
						$header_bg = $product_terms['product_category_image'];
					}
				}

			} 

		 }
		 else if( is_home() ){

		 	$header_title    = get_post_meta(get_option('page_for_posts'), '_nectar_header_title', true);
			$header_bg       = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg', true);
			$header_bg_color = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg_color', true);
			$bg_type         = get_post_meta(get_option('page_for_posts'), '_nectar_slider_bg_type', true);
			if(empty($bg_type)) {
				$bg_type = 'image_bg';
			}
			$disable_effect = get_post_meta(get_option('page_for_posts'), '_disable_transparent_header', true);
			$force_effect   = null;
		 }

		 else if( !is_category() && !is_tag() && !is_date() & !is_author() ) {

			$header_title    = get_post_meta($post->ID, '_nectar_header_title', true);
			$header_bg       = apply_filters('nectar_page_header_bg_val', get_post_meta($post->ID, '_nectar_header_bg', true));
			$header_bg_color = apply_filters('nectar_page_header_bg_color_val', get_post_meta($post->ID, '_nectar_header_bg_color', true));
			$bg_type         = get_post_meta($post->ID, '_nectar_slider_bg_type', true);

			if( empty($bg_type) ) {
				$bg_type = 'image_bg';
			}
			$disable_effect = get_post_meta($post->ID, '_disable_transparent_header', true);
			$force_effect   = get_post_meta($post->ID, '_force_transparent_header', true);
			
		 }

		// Blog archives.
		if( is_category() || is_tag() || is_date() || is_author() ){

			$bg_type        = null;
			$disable_effect = null;
			$archive_bg_img = ( isset($nectar_options['blog_archive_bg_image']['id']) && !empty($nectar_options['blog_archive_bg_image']['id']) ) ? nectar_options_img($nectar_options['blog_archive_bg_image']) : null;
			$t_id           =  get_cat_ID( single_cat_title( '', false ) ) ;
			$terms          =  get_option( "taxonomy_$t_id" );

			if(!empty($archive_bg_img) || !empty($terms['category_image'])) {
			     $force_effect = 'on';
			     $bg_type      = 'image_bg';
			 }

			// color BG forces page header.
			if ( nectar_is_contained_header() && 
				isset($nectar_options['blog_archive_bg_functionality']) &&
				$nectar_options['blog_archive_bg_functionality'] === 'color' ) {
				$force_effect = 'on';
			}
		}

		$pattern = get_shortcode_regex();

		$using_applicable_shortcode = 0;

	  if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )  && array_key_exists( 0, $matches ))  {

			if($matches[0][0]){
				if( strpos($matches[0][0],'nectar_slider') !== false && strpos($matches[0][0],'full_width="true"') !== false) {

					if(empty($header_title)) {
						$using_applicable_shortcode = 1;
					}

				} else {
					$using_applicable_shortcode = 0;
				}
			}

	  }


		// Project single remove header.
		if( is_singular( 'portfolio' ) &&
		    isset($nectar_options['portfolio_remove_single_header']) &&
		    !empty($nectar_options['portfolio_remove_single_header']) &&
			  '1' === $nectar_options['portfolio_remove_single_header'] ) {
					$header_bg       = 0;
					$header_bg_color = 0;
		}

		$blog_post_type_list = array('post');
		if( has_filter('nectar_metabox_post_types_post_header') ) {
 		  $blog_post_type_list = apply_filters('nectar_metabox_post_types_post_header', $blog_post_type_list);
 	  	}
		$is_blog_header_post_type = (isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single()) ? true : false;


		// Single blog post auto page header from featured image.
		$single_post_header_inherit_fi = ( !empty($nectar_options['blog_post_header_inherit_featured_image']) ) ? $nectar_options['blog_post_header_inherit_featured_image'] : '0';
		if( $single_post_header_inherit_fi === '1' && $is_blog_header_post_type && $post->ID != 0  && is_single() && has_post_thumbnail($post->ID) ) {
			$using_applicable_shortcode = 1;
		}

		// Stop effect from WooCommerce single pages.
		global $woocommerce;

		if( $woocommerce && is_product() ) {
			$using_applicable_shortcode = 0;
			$header_bg                  = 0;
			$header_bg_color            = 0;
		}

		// Alternate header style.
		global $nectar_options;
		if( !empty($nectar_options['blog_header_type']) && $nectar_options['blog_header_type'] === 'fullscreen' && $is_blog_header_post_type ) {
			$using_applicable_shortcode = 1;
		} else if( !empty($nectar_options['blog_header_type']) && $nectar_options['blog_header_type'] === 'image_under' && $is_blog_header_post_type ) {
			$using_applicable_shortcode = 0;
			$header_bg = 0;
			$header_bg_color = 0;
		}

		// Search / tax / removing effect.
		if( is_search() || $disable_effect === 'on' ) {
			$using_applicable_shortcode = 0;
			$header_bg                  = 0;
			$header_bg_color            = 0;
			$bg_type                    = 'image_bg';
		}

		// Page full screen rows.
		$page_full_screen_rows = (isset($post->ID)) ? get_post_meta($post->ID, '_nectar_full_screen_rows', true) : '';

		if($page_full_screen_rows === 'on' && $disable_effect !== 'on' && (!is_search() && !is_tax()) ) {
			$using_applicable_shortcode = 1;
		}

		// Forcing effect.
		if( $force_effect === 'on' && (!is_search() && !is_tax()) ) {
			$using_applicable_shortcode = 1;
		}

		$the_verdict = (!empty($header_bg_color) || !empty($header_bg) || $bg_type === 'video_bg' || $bg_type === 'particle_bg' || $using_applicable_shortcode) ? true : false;
		
		// Verify its not a portfolio archive.
		if( is_tax('project-type') || is_tax('project-attributes') || is_404() ) {
			$the_verdict = false;
		}

		// Frontend editor when using fullscreen page rows.
		$nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
		$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;

		if( $nectar_using_VC_front_end_editor && is_page() && (!is_search() && !is_tax() ) ) {
			$the_verdict = false;
		}


		// Filter verdict.
		if( has_filter('nectar_activate_transparent_header') ) {

			$filtered_verdict = apply_filters('nectar_activate_transparent_header', $the_verdict);
			
			// Ensure a bool is being used.
			if( $filtered_verdict === false || $filtered_verdict === true ) {
				return $filtered_verdict;
			}

		}

		return $the_verdict;

	}
}




/**
 * Forces transparent header state when using contained header.
 *
 * @since 15.1
 */
if( !function_exists('nectar_transparent_header_filter') ) {
	function nectar_transparent_header_filter($enabled) {
		
		if( nectar_is_contained_header() ) {
			if( nectar_using_full_width_top_level_row() ||
				has_action('nectar_hook_404_content') && is_404() ||
				is_search() ||
				nectar_using_before_content_global_section() ) {
				return true;
			}
		}
		return $enabled;

	}
}
add_filter('nectar_activate_transparent_header', 'nectar_transparent_header_filter', 10, 1);


/**
 * Forces transparent header state at a higher priority 
 * when using contained header to ensure it overrides other options.
 *
 * @since 15.5
 */
if( !function_exists('nectar_transparent_contained_header_filter') ) {
	function nectar_transparent_contained_header_filter($enabled) {
		
		if( nectar_is_contained_header() ) {
			if( has_action('nectar_hook_global_section_after_header_navigation') ) {
				return true;
			}
		}
		return $enabled;

	}
}
add_filter('nectar_activate_transparent_header', 'nectar_transparent_contained_header_filter', 60, 1);



/**
 * Check if a Nectar Slider is the first element on the page.
 *
 * @since 3.0
 */

if ( !function_exists( 'nectar_using_full_width_top_level_row' ) ) {
	function nectar_using_full_width_top_level_row() {
		global $post;

		$pattern = get_shortcode_regex();

		if($post && isset($post->post_content) && (!is_single() && !is_archive() && !is_home() && !nectar_using_before_content_global_section()) ) {

			if ( preg_match( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 0, $matches ))  {

				if($matches[0]){
						
					if( strpos($matches[0],'vc_row type="full_width_background"') !== false || 
						strpos($matches[0],'vc_row type="full_width_content"') !== false ||
						strpos($matches[0],'[nectar_global_section') !== false ) {
								
						return true;
						
					} // First shortcode is fullwidth.

				}

			}

		} // Verify not on single or archive.

		return false;
	}
}

/**
 * Check if a Nectar Slider is the first element on the page.
 *
 * @since 3.0
 */
if ( !function_exists( 'using_nectar_slider' ) ) {

	function using_nectar_slider(){

		global $post;
		global $woocommerce;

		if($woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag()) {

			if( version_compare( $woocommerce->version, "3.0", ">=" ) ) {
				$header_title    = get_post_meta(wc_get_page_id('shop'), '_nectar_header_title', true);
				$header_bg       = get_post_meta(wc_get_page_id('shop'), '_nectar_header_bg', true);
				$header_bg_color = get_post_meta(wc_get_page_id('shop'), '_nectar_header_bg_color', true);
			} else {
				$header_title    = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_title', true);
				$header_bg       = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_bg', true);
				$header_bg_color = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_bg_color', true);
			}
		 }
		 else if(is_home() || is_archive()){
		 	$header_title    = get_post_meta(get_option('page_for_posts'), '_nectar_header_title', true);
			$header_bg       = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg', true);
			$header_bg_color = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg_color', true);
		 }  else {
			$header_title    = get_post_meta($post->ID, '_nectar_header_title', true);
			$header_bg       = get_post_meta($post->ID, '_nectar_header_bg', true);
			$header_bg_color = get_post_meta($post->ID, '_nectar_header_bg_color', true);
		 }

		$pattern = get_shortcode_regex();
		$using_fullwidth_slider = 0;

		if ( preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )  && array_key_exists( 0, $matches ))  {

			if($matches[0][0]){

				if( strpos($matches[0][0],'nectar_slider') !== false && strpos($matches[0][0],'full_width="true"') !== false
				|| strpos($matches[0][0],' type="full_width_content"') !== false && strpos($matches[0][0],'nectar_slider') !== false && strpos($matches[0][0],'[vc_column width="1/1"') !== false ) {

					$using_fullwidth_slider = 1;

				} else {

					$using_fullwidth_slider = 0;

				}
			}

	    }

		// Incase of search.
		if(is_search() || is_tax()) {
			$using_fullwidth_slider = 0;
		}

		// Stop effect from WooCommerce single pages.
		global $woocommerce;
		if($woocommerce && is_product()) {
			$using_fullwidth_slider = 0;
		}

		$the_verdict = (empty($header_title) && empty($header_bg) && empty($header_bg_color) && $using_fullwidth_slider) ? true : false;

		return $the_verdict;
	}

}




/**
 * Check if the header is in use.
 *
 * @since 9.0
 */
 if( !function_exists('nectar_header_section_check') ) {

	function nectar_header_section_check($post_id){

		 global $post;
		 global $woocommerce;
		 global $nectar_options;

		 if( $woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag() ) {
		 	return false;
		 }

		 $header_bg             = '';
		 $header_bg_color       = '';
		 $bg_type               = '';
		 $page_full_screen_rows = (isset($post->ID)) ? get_post_meta($post->ID, '_nectar_full_screen_rows', true) : '';


		 if( !is_category() && !is_tag() && !is_date() & !is_author() ) {

			$header_bg       = get_post_meta($post->ID, '_nectar_header_bg', true);
			$header_bg_color = get_post_meta($post->ID, '_nectar_header_bg_color', true);
			$bg_type         = get_post_meta($post->ID, '_nectar_slider_bg_type', true);
			if(empty($bg_type)) {
				$bg_type = 'image_bg';
			}

		 }

		$header_auto_title = (!empty($nectar_options['header-auto-title']) && $nectar_options['header-auto-title'] === '1') ? true : false;

		$the_verdict = (!empty($header_bg_color) || !empty($header_bg) || $bg_type === 'video_bg' || $bg_type === 'particle_bg' || $page_full_screen_rows === 'on' || ($header_auto_title && is_page()) ) ? true : false;

		// Verify its not a portfolio or other non applicable archive.
		if( is_tax('project-type') || is_tax('project-attributes') || is_404() || is_search()) {
			$the_verdict = false;
		}

		return apply_filters('nectar_using_header_section', $the_verdict);

	}

}
