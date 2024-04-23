<?php
/**
 * Third party SEO integrations.
 *
 *
 * @package Salient WordPress Theme
 * @version 13.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// General page builder.
if( !function_exists('salient_wpbakery_general_sitemap_images') ) {
  function salient_wpbakery_general_sitemap_images( $images, $id ) {
    
      $post = get_post( $id );

      if ( $post && strpos( $post->post_content, '[vc_row' ) !== false ) {
        preg_match_all( '/(?:image_url|image_1_url|image_2_url)\=\"([^\"]+)\"/', $post->post_content, $matches );
        foreach ( $matches[1] as $m ) {
          $ids = explode( ',', $m );
          foreach ( $ids as $id ) {
            if ( (int) $id ) {
              $images[] = array(
                'src' => wp_get_attachment_url( $id ),
                'title' => get_the_title( $id ),
              );
            }
          }
        }
      }
    

    return $images;
  }
}

/* portfolio single extra content images */
if( !function_exists('salient_wpbakery_image_gallery_sitemap_images') ) {
  function salient_wpbakery_image_gallery_sitemap_images( $images, $id ) {
    
      $post = get_post( $id );

      if($post) {

        $portfolio_extra_content = get_post_meta( $post->ID, '_nectar_portfolio_extra_content', true );

        if ( $post && strpos( $portfolio_extra_content, '[vc_row' ) !== false ) {
          preg_match_all( '/(?:images|image_url|image_1_url|image_2_url)\=\"([^\"]+)\"/', $portfolio_extra_content, $matches );
          foreach ( $matches[1] as $m ) {
            $ids = explode( ',', $m );
            foreach ( $ids as $id ) {
              if ( (int) $id ) {
                $images[] = array(
                  'src' => wp_get_attachment_url( $id ),
                  'title' => get_the_title( $id ),
                );
              }
            }
          }
        }

      }
    
    return $images;
  }
}

// Query loop images.
if( !function_exists('salient_wpbakery_query_sitemap_images') ) {
  function salient_wpbakery_query_sitemap_images( $images, $id ) {

    $post = get_post( $id );
    
    if( !$post ) {
      return $images;
    }

    $elements_arr = array('nectar_blog','recent_posts','nectar_post_grid','nectar_portfolio');

    foreach( $elements_arr as $element ) {

      if ( preg_match_all( '/\['.$element.'(\s.*?)?\]/s', $post->post_content, $matches, PREG_SET_ORDER ) )  {

        if (!empty($matches)) {

          foreach ($matches as $shortcode) {
              
            if( $shortcode && isset($shortcode[1]) ) {

              $atts = shortcode_parse_atts($shortcode[1]);
            
              if( !empty($atts) ) {

                $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '-1';
                $post_offset    = isset($atts['offset']) ? $atts['offset'] : 0;
                $category       = isset($atts['category']) ? $atts['category'] : null;
                $post_type      = 'post';

                if( $element === 'nectar_post_grid' ) {
                  $post_offset    = isset($atts['post_offset']) ? $atts['post_offset'] : 0;
                }

                $nectar_blog_arr = array(
                  'post_type'      => $post_type,
                  'posts_per_page' => $posts_per_page,
                  'offset'         => $post_offset
                );


                if( $element === 'nectar_post_grid' ) { 

                    $post_grid_post_type = isset($atts['post_type'] ) ? $atts['post_type'] : 'post';

                    if( $post_grid_post_type === 'post' ) {
                        $category = (isset($atts['blog_category']) && 'all' !== $atts['blog_category']) ? $atts['blog_category'] : null;
                        $nectar_blog_arr['category_name'] = $category;
                    } 
                    else if( $post_grid_post_type === 'portfolio' ) {
                        $category = (isset($atts['portfolio_category']) && 'all' !== $atts['portfolio_category']) ? $atts['portfolio_category'] : null;
                        $nectar_blog_arr['project-type'] = $category;
                        $nectar_blog_arr['post_type'] = 'portfolio';
                    }
                    else {
                      continue;
                    }
                } 

                else if( $element === 'nectar_portfolio' ) { 
                  $project_offset = isset($atts['project_offset']) ? $atts['project_offset'] : 0;
                  $projects_per_page = isset($atts['projects_per_page']) ? $atts['projects_per_page'] : -1;
                  if( 'all' === $category ) {
                    $category = null;
                  }

                  $nectar_blog_arr['post_type'] = 'portfolio';
                  $nectar_blog_arr['project-type'] = $category;
                  $nectar_blog_arr['offset'] = $project_offset;
                  $nectar_blog_arr['posts_per_page'] = $projects_per_page;
                  
                }

                else {
                  if( 'all' === $category ) {
                    $category = null;
                  }
                  $nectar_blog_arr['category_name'] = $category;
                }

                $nectar_blog_el_query = new WP_Query( $nectar_blog_arr );

                if( $nectar_blog_el_query->have_posts() ) : while( $nectar_blog_el_query->have_posts() ) : $nectar_blog_el_query->the_post(); 
                
                // Gather alt and image.
                $alt_text = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
                if( !$alt_text ) {
                  $alt_text = get_the_title();
                } 
                $featured_image = get_the_post_thumbnail_url(); 

                // Items to filter out.
                $allowed_image = true;

                //// Rwcent Posts.
                if( false !== strpos($shortcode[0],'[recent_posts ') ) {
                  $style = isset($atts['style']) ? $atts['style'] : 'default';
                  
                  $blacklisted_styles = array(
                    'minimal',
                    'title_only',
                    'slider',
                    'slider_multiple_visible'
                  );
                  if( in_array($style, $blacklisted_styles) ) {
                    $allowed_image = false;
                  }
                }

                //// Portfolio.
                if( false !== strpos($shortcode[0],'[nectar_post_grid ') || 
                    false !== strpos($shortcode[0],'[nectar_portfolio ')) {
                    $custom_thumbnail = get_post_meta(get_the_ID(), '_nectar_portfolio_custom_thumbnail', true);
                   
                    if( !empty($custom_thumbnail) ) {
                      $featured_image = $custom_thumbnail;
                    }
                }

                // Add image to array.
                if( $featured_image && $allowed_image ) {
                  $images[] = array(
                    'src' => $featured_image,
                    'title' => $alt_text
                  );
                }

                endwhile; endif; 
                wp_reset_postdata();
                              
              } // end found $atts
            } // end $shortcode
          } // end foreach $matches
        } // end found $matches
      } // end preg_match_all
    } // end foreach $elements_arr

    return $images;
  }
}


// Math Rannk images.
add_filter( 'rank_math/sitemap/urlimages', 'salient_wpbakery_general_sitemap_images', 10, 2 );
add_filter( 'rank_math/sitemap/urlimages', 'salient_wpbakery_query_sitemap_images', 10, 2 );
add_filter( 'rank_math/sitemap/urlimages', 'salient_wpbakery_image_gallery_sitemap_images', 10, 2 );


//Yoast images.
add_filter( 'wpseo_sitemap_urlimages', 'salient_wpbakery_general_sitemap_images', 10, 2 );
add_filter( 'wpseo_sitemap_urlimages', 'salient_wpbakery_query_sitemap_images', 10, 2 );
add_filter( 'wpseo_sitemap_urlimages', 'salient_wpbakery_image_gallery_sitemap_images', 10, 2 );

// Yoast Breadhcrums transparent header override.
if( !function_exists('salient_is_yoast_breadcrumb_active') ) {
  function salient_is_yoast_breadcrumb_active() {
    if ( function_exists('yoast_breadcrumb') && class_exists('WPSEO_Options') ) {
      
    if ( !method_exists('WPSEO_Options', 'get') ) {
      return false;
    }

    $breadcrumbs_enabled = WPSEO_Options::get( 'breadcrumbs-enable', false );
      if ( $breadcrumbs_enabled && is_page() && !is_front_page() ) {
        return true;
      }
    }
    return false;
  } 
}

if( !function_exists('salient_yoast_breadcrumbs_transparent_header_mod') ) {
  function salient_yoast_breadcrumbs_transparent_header_mod($bool) {
    global $post;
    if ( salient_is_yoast_breadcrumb_active() ) {
      
        $force_effect = get_post_meta($post->ID, '_force_transparent_header', true);
      
        if ( !nectar_header_section_check($post->ID) && 'on' === $force_effect ) {
          return false;
        }
    }
    return $bool;
  }
}

add_filter('nectar_activate_transparent_header', 'salient_yoast_breadcrumbs_transparent_header_mod', 11, 1);