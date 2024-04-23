<?php
/**
 * Media related and image size helper functions
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 9.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Add theme specific image sizes that are used
 *
 * @since 8.0
 */

if ( ! function_exists( 'nectar_add_image_sizes' ) ) {

	function nectar_add_image_sizes() {

		global $nectar_options;
		
		add_image_size( 'portfolio-thumb', 600, 403, true );
		add_image_size( 'nectar_small_square', 140, 140, true );


		if( isset($nectar_options['image-size-portfolio-thumb_large']) && 
			'1' === $nectar_options['image-size-portfolio-thumb_large'] ) {
			add_image_size( 'portfolio-thumb_large', 900, 604, true );
		}
		
		if( isset($nectar_options['image-size-portfolio-thumb_small']) && 
			'1' === $nectar_options['image-size-portfolio-thumb_small'] ) {
			add_image_size( 'portfolio-thumb_small', 400, 269, true );
		}

		if( isset($nectar_options['image-size-portfolio-widget']) && 
			'1' === $nectar_options['image-size-portfolio-widget'] ) {
			add_image_size( 'portfolio-widget', 100, 100, true );
		}


		$masonry_sizing_type = ( ! empty( $nectar_options['portfolio_masonry_grid_sizing'] ) && $nectar_options['portfolio_masonry_grid_sizing'] === 'photography' ) ? 'photography' : 'default';

		if ( $masonry_sizing_type !== 'photography' ) {
			
			if( isset($nectar_options['image-size-wide']) && 
				'1' === $nectar_options['image-size-wide'] ) {
				add_image_size( 'wide', 1000, 500, true );
				add_image_size( 'wide_photography', 900, 600, true );
			}

			if( isset($nectar_options['image-size-wide_small']) && 
				'1' === $nectar_options['image-size-wide_small'] ) {
				add_image_size( 'wide_small', 670, 335, true );
			}

			if( isset($nectar_options['image-size-regular']) && 
				'1' === $nectar_options['image-size-regular'] ) {
				add_image_size( 'regular', 500, 500, true );
			}

			if( isset($nectar_options['image-size-regular_small']) && 
				'1' === $nectar_options['image-size-regular_small'] ) {
				add_image_size( 'regular_small', 350, 350, true );
			}

			if( isset($nectar_options['image-size-tall']) && 
				'1' === $nectar_options['image-size-tall'] ) {
				add_image_size( 'tall', 500, 1000, true );
			}
			
			if( isset($nectar_options['image-size-wide_tall']) && 
				'1' === $nectar_options['image-size-wide_tall'] ) {
				add_image_size( 'wide_tall', 1000, 1000, true );
			}
			

		} else {
			
			// These three are still needed for meta overlaid masonry blog.
			if( isset($nectar_options['image-size-regular']) && 
				'1' === $nectar_options['image-size-regular'] ) {
				add_image_size( 'regular', 500, 500, true );
				add_image_size( 'regular_photography', 450, 600, true );
			}

			if( isset($nectar_options['image-size-regular_small']) && 
				'1' === $nectar_options['image-size-regular_small'] ) {
				add_image_size( 'regular_small', 350, 350, true );
				add_image_size( 'regular_photography_small', 350, 467, true );
			}

			if( isset($nectar_options['image-size-wide_tall']) && 
				'1' === $nectar_options['image-size-wide_tall'] ) {
				add_image_size( 'wide_tall', 1000, 1000, true );
				add_image_size( 'wide_tall_photography', 900, 1200, true );
			}

			if( isset($nectar_options['image-size-wide']) && 
				'1' === $nectar_options['image-size-wide'] ) {
				add_image_size( 'wide_photography', 900, 600, true );
			}

			if( isset($nectar_options['image-size-wide_small']) && 
				'1' === $nectar_options['image-size-wide_small'] ) {
				add_image_size( 'wide_photography_small', 675, 450, true );
			}

		}

		if( isset($nectar_options['image-size-large_featured']) && 
			'1' === $nectar_options['image-size-large_featured'] ) {
			add_image_size( 'large_featured', 1870, 770, true );
		}

		if( isset($nectar_options['image-size-medium_featured']) && 
			'1' === $nectar_options['image-size-medium_featured'] ) {
			add_image_size( 'medium_featured', 800, 800, true );
		}

	}
}

add_action( 'after_setup_theme', 'nectar_add_image_sizes' );



/**
 * List the available image sizes
 *
 * @since 8.0
 */
 function nectar_list_thumbnail_sizes() {
	 global $_wp_additional_image_sizes;
	 $sizes = array();
	 foreach ( get_intermediate_image_sizes() as $s ) {
		 $sizes[ $s ] = array( 0, 0 );
		 if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
			 $sizes[ $s ][0] = get_option( $s . '_size_w' );
			 $sizes[ $s ][1] = get_option( $s . '_size_h' );
		 } else {
			 if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
				 $sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'] );
			 }
		 }
	 }
	 
	 foreach ( $sizes as $size => $atts ) {
		 echo esc_html( $size ) . ' ' . implode( 'x', $atts ) . "\n";
	 }
 }





/**
 * Auto lightbox image links theme option.
 *
 * @since 5.0
 */
if ( ! function_exists( 'nectar_auto_gallery_lightbox' ) ) {
	function nectar_auto_gallery_lightbox( $content ) {

		preg_match_all( '/<a(.*?)href=(?:\'|")([^<]*?).(bmp|gif|jpeg|jpg|png)(?:\'|")(.*?)>/i', $content, $links );
		if ( isset( $links[0] ) ) {
			$rel_hash = '[gallery-' . wp_generate_password( 4, false, false ) . ']';

			foreach ( $links[0] as $id => $link ) {

				if ( preg_match( '/<a.*?rel=(?:\'|")(.*?)(?:\'|").*?>/', $link, $result ) === 1 ) {
					$content = str_replace( $link, preg_replace( '/rel=(?:\'|")(.*?)(?:\'|")/', 'rel="prettyPhoto' . $rel_hash . '"', $link ), $content );
				} else {
					$content = str_replace( $link, '<a' . $links[1][ $id ] . 'href="' . $links[2][ $id ] . '.' . $links[3][ $id ] . '"' . $links[4][ $id ] . ' rel="prettyPhoto' . $rel_hash . '">', $content );
				}
			}
		}

		return $content;

	}
}

 global $nectar_options;

if ( ! empty( $nectar_options['default-lightbox'] ) && $nectar_options['default-lightbox'] === '1' ) {
	add_filter( 'the_content', 'nectar_auto_gallery_lightbox' );

	add_filter( 'body_class', 'nectar_auto_gallery_lightbox_class' );
	function nectar_auto_gallery_lightbox_class( $classes ) {
		// add 'class-name' to the $classes array
		$classes[] = 'nectar-auto-lightbox';
		// return the $classes array
		return $classes;
	}
}





/**
 * Add URL option into attachment details for wpbakery image gallery element
 *
 * @since 5.0
 */
function nectar_add_attachment_field_credit( $form_fields, $post ) {

	$form_fields['image-url'] = array(
		'label' => 'Image URL',
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'nectar_image_gal_url', true ),
		'helps' => '',
	);

	 $form_fields['shape-bg-color'] = array(
		 'label' => 'BG Color',
		 'input' => 'text',
		 'value' => esc_attr( get_post_meta( $post->ID, 'nectar_particle_shape_bg_color', true ) ),
		 'helps' => 'Enter your color in hex format e.g. "#1ed760',
	 );

	$image_gal_masonry_sizing_mapping         = null;
	$image_gal_masonry_sizing_mapping_options = array(
		'regular'   => 'Regular',
		'wide'      => 'Wide',
		'tall'      => 'Tall',
		'wide_tall' => 'Wide & Tall',
	);
	$meta                                     = get_post_meta( $post->ID, 'nectar_image_gal_masonry_sizing', true );
	foreach ( $image_gal_masonry_sizing_mapping_options as $key => $option ) {
		$image_gal_masonry_sizing_mapping .= '<option value="' . $key . '"';
		if ( $meta ) {
			if ( $meta == $key ) {
				$image_gal_masonry_sizing_mapping .= ' selected="selected"';
			}
		}
		$image_gal_masonry_sizing_mapping .= '>' . $option . '</option>';
	}

	$color_mapping         = null;
	$color_mapping_options = array(
		'original' => 'Original',
		'solid'    => 'Solid Color',
		'random'   => 'Random',
	);
	$meta                  = get_post_meta( $post->ID, 'nectar_particle_shape_color_mapping', true );
	foreach ( $color_mapping_options as $key => $option ) {
		$color_mapping .= '<option value="' . $key . '"';
		if ( $meta ) {
			if ( $meta == $key ) {
				$color_mapping .= ' selected="selected"';
			}
		}
		$color_mapping .= '>' . $option . '</option>';
	}

	$density         = null;
	$density_options = array(
		'very_low'  => 'Very Low',
		'low'       => 'Low',
		'medium'    => 'Medium',
		'high'      => 'High',
		'very_high' => 'Very High',
	);
	$meta            = get_post_meta( $post->ID, 'nectar_particle_shape_density', true );
	foreach ( $density_options as $key => $option ) {
		$density .= '<option value="' . $key . '"';
		if ( $meta ) {
			if ( $meta == $key ) {
				$density .= ' selected="selected"';
			}
		}
		$density .= '>' . $option . '</option>';
	}

	$alpha         = null;
	$alpha_options = array(
		'original' => 'Original',
		'random'   => 'Random',
	);
	$meta          = get_post_meta( $post->ID, 'nectar_particle_shape_color_alpha', true );
	foreach ( $alpha_options as $key => $option ) {
		$alpha .= '<option value="' . $key . '"';
		if ( $meta ) {
			if ( $meta == $key ) {
				$alpha .= ' selected="selected"';
			}
		}
		$alpha .= '>' . $option . '</option>';
	}

	$form_fields['masonry-image-sizing'] = array(
		'label' => 'Masonry Sizing',
		'input' => 'html',
		'html'  => "<select name='attachments[{$post->ID}][masonry-image-sizing]' id='attachments[{$post->ID}][masonry-image-sizing]'>" . $image_gal_masonry_sizing_mapping . '</select>',
		'helps' => '',
		'value' => get_post_meta( $post->ID, 'nectar_image_gal_masonry_sizing', true ),
	);

	$form_fields['shape-color-mapping'] = array(
		'label' => 'Color Mapping',
		'input' => 'html',
		'html'  => "<select name='attachments[{$post->ID}][shape-color-mapping]' id='attachments[{$post->ID}][shape-color-mapping]'>" . $color_mapping . '</select>',
		'helps' => '',
		'value' => get_post_meta( $post->ID, 'nectar_particle_shape_color_mapping', true ),
	);

	$form_fields['shape-color-alpha'] = array(
		'label' => 'Color Alpha',
		'input' => 'html',
		'html'  => "<select name='attachments[{$post->ID}][shape-color-alpha]' id='attachments[{$post->ID}][shape-color-alpha]'>" . $alpha . '</select>',
		'helps' => '',
		'value' => get_post_meta( $post->ID, 'nectar_particle_shape_color_alpha', true ),
	);

	$form_fields['shape-particle-color'] = array(
		'label' => 'Particle Color',
		'input' => 'text',
		'value' => esc_attr( get_post_meta( $post->ID, 'nectar_particle_shape_color', true ) ),
		'helps' => 'Will only be used if Color Mapping is set to "Solid Color". Enter your color in hex format e.g. "#1ed760',
	);

	$form_fields['shape-density'] = array(
		'label' => 'Particle Density',
		'input' => 'html',
		'html'  => "<select name='attachments[{$post->ID}][shape-density]' id='attachments[{$post->ID}][shape-density]'>" . $density . '</select>',
		'helps' => 'The lower the density, the higher the performance',
		'value' => get_post_meta( $post->ID, 'nectar_particle_shape_density', true ),
	);

	$form_fields['shape-max-particle-size'] = array(
		'label' => 'Max Particle Size',
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'nectar_particle_max_particle_size', true ),
		'helps' => 'The default is 3',
	);

	return $form_fields;
}
 add_filter( 'attachment_fields_to_edit', 'nectar_add_attachment_field_credit', 10, 2 );

function nectar_add_attachment_field_credit_save( $post, $attachment ) {
	if ( isset( $attachment['image-url'] ) ) {
		$image_url_sanitized = sanitize_text_field( $attachment['image-url'] );
		update_post_meta( $post['ID'], 'nectar_image_gal_url', $image_url_sanitized );
	}

	if ( isset( $attachment['masonry-image-sizing'] ) ) {
		$masonry_image_sizing_sanitized = sanitize_text_field( $attachment['masonry-image-sizing'] );
		update_post_meta( $post['ID'], 'nectar_image_gal_masonry_sizing', $masonry_image_sizing_sanitized );
	}

	if ( isset( $attachment['shape-bg-color'] ) ) {
		$shape_bg_color_sanitized = sanitize_text_field( $attachment['shape-bg-color'] );
		update_post_meta( $post['ID'], 'nectar_particle_shape_bg_color', $shape_bg_color_sanitized );
	}

	if ( isset( $attachment['shape-particle-color'] ) ) {
		$shape_particle_color_sanitized = sanitize_text_field( $attachment['shape-particle-color'] );
		update_post_meta( $post['ID'], 'nectar_particle_shape_color', $shape_particle_color_sanitized );
	}
	if ( isset( $attachment['shape-color-mapping'] ) ) {
		$shape_color_mapping_sanitized = sanitize_text_field( $attachment['shape-color-mapping'] );
		update_post_meta( $post['ID'], 'nectar_particle_shape_color_mapping', $shape_color_mapping_sanitized );
	}
	if ( isset( $attachment['shape-color-alpha'] ) ) {
		$shape_color_alpha_sanitized = sanitize_text_field( $attachment['shape-color-alpha'] );
		update_post_meta( $post['ID'], 'nectar_particle_shape_color_alpha', $shape_color_alpha_sanitized );
	}
	if ( isset( $attachment['shape-density'] ) ) {
		$shape_density_sanitized = sanitize_text_field( $attachment['shape-density'] );
		update_post_meta( $post['ID'], 'nectar_particle_shape_density', $shape_density_sanitized );
	}
	if ( isset( $attachment['shape-max-particle-size'] ) ) {
		$shape_max_particle_size_sanitized = sanitize_text_field( $attachment['shape-max-particle-size'] );
		update_post_meta( $post['ID'], 'nectar_particle_max_particle_size', $shape_max_particle_size_sanitized );
	}
	return $post;
}
 add_filter( 'attachment_fields_to_save', 'nectar_add_attachment_field_credit_save', 10, 2 );




 /**
  * Get attachment ID from a given image URL.
  *
  * @since 5.0
  */
if ( ! function_exists( 'fjarrett_get_attachment_id_from_url' ) ) {
	function fjarrett_get_attachment_id_from_url( $url ) {

		// Split the $url into two parts with the wp-content directory as the separator.
		$parse_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

		// Get the host of the current site and the host of the $url, ignoring www.
		$this_host = str_ireplace( 'www.', '', parse_url( esc_url( home_url() ), PHP_URL_HOST ) );
		$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

		// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
		if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
			return;
		}

		// Now we're going to quickly search the DB for any attachment GUID with a partial path match.
		global $wpdb;

		$prefix     = $wpdb->prefix;
		$attachment = $wpdb->get_col( $wpdb->prepare( 'SELECT ID FROM ' . $prefix . 'posts WHERE guid RLIKE %s;', $parse_url[1] ) );

		return ( ! empty( $attachment ) ) ? $attachment[0] : null;
	}
}



/**
 * Returns a lightbox ready URL from youtube/vimeo embed
 *
 * @since 12.2
 */
if ( ! function_exists( 'nectar_extract_video_lightbox_link' ) ) {

 function nectar_extract_video_lightbox_link( $post, $video_embed, $video_mp4 ) {

	 global $nectar_options;

	 $project_video_src  = null;
	 $project_video_link = null;
	 $using_fancybox     = ( isset($nectar_options['lightbox_script']) && $nectar_options['lightbox_script'] === 'fancybox') ? true : false;

	 if ( $video_embed ) {

		 $project_video_src = $video_embed;

		 if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $project_video_src, $video_match ) ) {

			 // youtube
			 // handle query params.
			 $query_args = '';

			 // iframe src.
			 if(strpos($project_video_src, '<iframe') !== false && $using_fancybox === true ) {
				 preg_match('/src="([^"]+)"/', $project_video_src, $iframe_src_match);
				 $iframe_src = $iframe_src_match[1];

				 $parsed_iframe_src = parse_url($iframe_src);
				 
				 if( isset($parsed_iframe_src['query']) && $parsed_iframe_src['query'] !== null ) {
					 $query_args = '&' . $parsed_iframe_src['query'];
				 }
			 }

			 $project_video_link = 'https://www.youtube.com/watch?v=' . $video_match[1] . $query_args;

		 } elseif ( preg_match( '/player\.vimeo\.com\/video\/([0-9]*)/', $project_video_src, $video_match ) ) {

			 // vimeo iframe
			 $project_video_link = 'https://vimeo.com/' . $video_match[1] . '?iframe=true';

		 } elseif ( preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/', $project_video_src, $video_match ) ) {

			 // reg vimeo
			 $project_video_link = 'https://vimeo.com/' . $video_match[5] . '?iframe=true';

		 }
	 } elseif ( $video_mp4 ) {

		 $project_video_link = $video_mp4;

	 }


	 return esc_url($project_video_link);
	 
 }
 
}



/**
 * Get attachment src from a given image URL.
 *
 * @since 4.0
 */
if ( ! function_exists( 'nectar_options_img' ) ) {

	function nectar_options_img( $image_arr_or_str ) {

		// dummy data import from external
		if ( isset( $image_arr_or_str['thumbnail'] ) && strpos( $image_arr_or_str['thumbnail'], '://themenectar.com' ) !== false && strpos( get_site_url(), 'themenectar.com' ) === false ) {
			return $image_arr_or_str['thumbnail'];
		}
		if ( isset( $image_arr_or_str['thumbnail'] ) && strpos( $image_arr_or_str['thumbnail'], 'https://source.unsplash.com' ) !== false ) {
			return $image_arr_or_str['thumbnail'];
		}

		// check if URL or ID is passed
		if ( isset( $image_arr_or_str['id'] ) ) {
			
			$image_id = apply_filters('wpml_object_id', $image_arr_or_str['id'], 'attachment', TRUE);
			$image = wp_get_attachment_image_src( $image_id, 'full' );
			
			if( isset($image[0]) ) {
				return $image[0];
			} else {
				return '';
			}
			
		} 
		elseif ( isset( $image_arr_or_str['url'] ) ) {
			return $image_arr_or_str['url'];
		} 
		else {

			$image_id = fjarrett_get_attachment_id_from_url( $image_arr_or_str );

			if ( ! is_null( $image_id ) && ! empty( $image_id ) ) {

				$image_id = apply_filters('wpml_object_id', $image_id, 'attachment', TRUE);

				$image = wp_get_attachment_image_src( $image_id, 'full' );
				return $image[0];
			} else {
				return $image_arr_or_str;
			}
		}
	}
}



/**
 * Attempts to locate video ID based on URL and grab the video source
 * through wp_get_attachment_url to allow CDNs to swap the source.
 *
 * @since 12.2.0
 */
 if( !function_exists('nectar_video_src_from_wp_attachment') ) {
	 
	 function nectar_video_src_from_wp_attachment( $url ) {
		 
		 
		 if( function_exists('attachment_url_to_postid') && !empty($url) ) {
			 
			 $video_id = attachment_url_to_postid($url);
			 
			 // The ID has been found.
			 if( 0 !== $video_id ) {
				 
				 $video_source = wp_get_attachment_url($video_id);
			
				 // An Attachment URL has been found.
				 if( $video_source ) {
					 return $video_source;
				 }
				 
			 }
			 
		 }
		 
		 // Default.
		 return $url;
		 
	 }
	 
 }
 


