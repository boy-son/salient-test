<?php
/**
 * Nectar Element Assets
 *
 * Verifies the existence of specific elements
 * on a given page/post for asset loading.
 *
 * @package Salient WordPress Theme
 * @version 10.5.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Nectar Element Assets.
 */
class NectarElAssets {

  private static $instance;

  public static $using_woocommerce                = false;
  public static $post_content                     = '';
  public static $portfolio_content                = '';
  public static $woo_shop_content                 = '';
  public static $woo_taxonmy_content              = '';
  public static $woo_short_desc_content           = '';
  public static $global_section_locations_content = array();
  public static $templatera_content               = array();

	/**
	 * Constructor.
	 */
  public function __construct() {
		if( !is_admin() ) {
	    	add_action( 'wp', array( $this, 'get_page_content' ), 10 );
		}
  }

	/**
	 * Initiator.
	 */
  public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	/**
	 * Stores page/post content for searching.
	 */
  public static function get_page_content() {

    global $post;

    if ( ! is_object( $post ) ) {
		return;
    }

    // Check if using WooCommerce.
    if( class_exists( 'WooCommerce' ) && function_exists('is_shop') && function_exists('is_product_category') ) {
      self::$using_woocommerce = true;
    }

    // Portfolio.
    self::$portfolio_content = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_portfolio_extra_content', true ) : '';

    // Page/Post.
    self::$post_content = ( isset( $post->post_content ) ) ? $post->post_content : '';

	// WooCommerce.
    if( self::$using_woocommerce ) {

      // WooCommerce Shop Page.
      if( is_shop() ) {
        $woo_shop_id   = get_option( 'woocommerce_shop_page_id' );
        $woo_shop_page = ( $woo_shop_id && !empty($woo_shop_id) ) ? get_post( $woo_shop_id ) : '';
        self::$woo_shop_content = ( !empty($woo_shop_page) && isset( $woo_shop_page->post_content ) ) ? $woo_shop_page->post_content : '';
      }

      // WooCommerce Category or Tag.
      if( is_product_category() || is_product_tag() ) {
        $current_query = get_queried_object();
        self::$woo_taxonmy_content = ( !empty($current_query) && !empty($current_query->description) && isset( $current_query->description ) ) ? $current_query->description : '';
      }

	  if( is_product() ) {
		self::$woo_short_desc_content = ( isset( $post->post_excerpt ) && !empty($post->post_excerpt) ) ? $post->post_excerpt : '';
	  }

    }


	// Global template shortcodes.
	preg_match_all( '/\[templatera(\s.*?)?\]/s', self::$post_content, $templatera_shortcode_match, PREG_SET_ORDER  );
	
	preg_match_all( '/\[nectar_global_section(\s.*?)?\]/s', self::$post_content, $nectar_global_section_match, PREG_SET_ORDER  );
	preg_match_all( '/\[nectar_global_section(\s.*?)?\]/s', self::$portfolio_content, $nectar_global_section_match_portfolio, PREG_SET_ORDER  );
	
	$global_template_shortcode_match = array_merge($templatera_shortcode_match, $nectar_global_section_match, $nectar_global_section_match_portfolio);

    if( !empty($global_template_shortcode_match) ) {

      foreach( $global_template_shortcode_match as $shortcode ) {

        if( strpos($shortcode[0],'[') !== false && strpos($shortcode[0],']') !== false ) {
          $shortcode_inner = substr($shortcode[0], 1, -1);
        } else {
          $shortcode_inner = $shortcode[0];
        }

        $atts = shortcode_parse_atts( $shortcode_inner );

        if( isset($atts['id']) ) {

          $template_ID = (int) $atts['id'];
					if( 0 !== $template_ID ) {
	          $templatera_content_query = get_post($template_ID);

	          if( isset($templatera_content_query->post_content) && !empty($templatera_content_query->post_content) ) {
	            self::$templatera_content[] = $templatera_content_query->post_content;
	          }
					}

        }

      } // End global template Loop.

    } // End found global template shortcode.
		

	// Global template theme options
	$theme_template_locations = NectarThemeManager::$global_seciton_options;
	
	$nectar_options = NectarThemeManager::$options;
	
	foreach ($theme_template_locations as $key => $location) {
		
		if( isset($nectar_options[$location]) &&
			!empty($nectar_options[$location]) ) {
			
				$template_ID = intval($nectar_options[$location]);
				$global_section_content_query = get_post($template_ID);
				
				if( isset($global_section_content_query->post_content) && 
					!empty($global_section_content_query->post_content) ) {
							
							self::$templatera_content[] = $global_section_content_query->post_content;
				}
			
		}
		
	}


	// Global Section Locations.
	$global_sections_query_args = array(
		'post_type'    => 'salient_g_sections',
		'post_status'  => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'  => true
	);
	
	$global_sections_query = new WP_Query( $global_sections_query_args );

	if( $global_sections_query->have_posts() ) : while( $global_sections_query->have_posts() ) : $global_sections_query->the_post();
			
		$global_section_id = get_the_ID();

		// Locations.
		$locations = get_post_meta($global_section_id, 'nectar_g_section_locations', true);
		
		if( empty( $locations ) || !is_array($locations) ) {
			continue;
		}

		self::$global_section_locations_content[] = get_the_content();

	endwhile; endif;  
	
	wp_reset_query();

	set_transient('salient_global_sections_asset_refresh', 'false', 0);



  }


	/**
	 * Checks the page/post content for the given string.
	 *
	 * @return bool True if found. False otherwise.
	 */
  public static function locate($search_arr = array()) {

    foreach( $search_arr as $string ) {

  		if( strpos( self::$post_content, $string ) !== false ||
  			strpos( self::$portfolio_content, $string ) !== false ||
  			strpos( self::$woo_shop_content, $string ) !== false ||
  			strpos( self::$woo_taxonmy_content, $string ) !== false ) {
  			return true;
  		}

		// Templatera.
		foreach( self::$templatera_content as $template_content ) {

			if( strpos( $template_content, $string ) !== false ) {
				return true;
			}

		}

		// Global Section Locations.
		foreach( self::$global_section_locations_content as $global_section_content ) {
			if( strpos( $global_section_content, $string ) !== false ) {
				return true;
			}
		}
  	}

  	return false;

  }


}

/**
 * Initialize the NectarElAssets class
 */
NectarElAssets::get_instance();




/**
 * Verify elements are in use.
 *
 * @deprecated since 10.5.1
 */
if( !function_exists('nectar_using_content') ) {
  function nectar_using_content() {
    return true;
  }
}
