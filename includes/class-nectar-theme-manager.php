<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}


/**
* Nectar Theme Manager.
*/

if( !class_exists('NectarThemeManager') ) {

  class NectarThemeManager {

    private static $instance;
    
    public static $options                = '';
    public static $skin                   = '';
    public static $ocm_style              = '';
    public static $woo_product_filters    = false;
    public static $colors                 = array();
    public static $available_theme_colors = array();
    public static $header_format          = '';
    public static $column_gap             = '';
    public static $global_seciton_options = array(
      'global-section-after-header-navigation',
      'global-section-above-footer' 
    );
    
    private function __construct() {

      self::setup();

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
     * Determines all theme settings
     * which are conditionally forced.
     */
    public static function setup() {
      
      self::$options = get_nectar_theme_options();

      // Theme Skin.
      $theme_skin        = ( isset(self::$options['theme-skin']) && !empty(self::$options['theme-skin']) ) ? self::$options['theme-skin'] : 'material';
      $header_format     = ( isset(self::$options['header_format']) ) ? self::$options['header_format'] : 'default';
      $search_enabled    = ( isset(self::$options['header-disable-search']) && '1' === self::$options['header-disable-search'] ) ? false : true;
      $ajax_search       = ( isset(self::$options['header-disable-ajax-search']) && '1' === self::$options['header-disable-ajax-search'] ) ? false : true;
      $ajax_search_style = ( isset(self::$options['header-ajax-search-style']) ) ? self::$options['header-ajax-search-style'] : 'default';
      
      self::$header_format = $header_format;

    	if( 'centered-menu-bottom-bar' === $header_format ) {
    		$theme_skin = 'material';
    	}
      if( true === $ajax_search && 'extended' === $ajax_search_style && true === $search_enabled ) {
    		$theme_skin = 'material';
    	}

      self::$skin = esc_html($theme_skin);
      
      
      // OCM style.
      $theme_ocm_style    = ( isset( self::$options['header-slide-out-widget-area-style'] ) && !empty( self::$options['header-slide-out-widget-area-style'] ) ) ? self::$options['header-slide-out-widget-area-style'] : 'slide-out-from-right';
      $legacy_double_menu = ( function_exists('nectar_legacy_mobile_double_menu') ) ? nectar_legacy_mobile_double_menu() : false;
      
      if( true === $legacy_double_menu && in_array($theme_ocm_style, array('slide-out-from-right-hover', 'simple')) ) {
         $theme_ocm_style = 'slide-out-from-right';
      }
      
      self::$ocm_style = esc_html($theme_ocm_style);
      
      
      // Woo filter area.
      $product_filter_trigger = ( isset( self::$options['product_filter_area']) && '1' === self::$options['product_filter_area'] ) ? true : false;
			$main_shop_layout       = ( isset( self::$options['main_shop_layout'] ) ) ? self::$options['main_shop_layout'] : 'no-sidebar';
			
			if( $main_shop_layout != 'right-sidebar' && $main_shop_layout != 'left-sidebar' ) {
				$product_filter_trigger = false;
			}
      
      self::$woo_product_filters = $product_filter_trigger;


      // Column Gap.
      self::$column_gap = ( isset( self::$options['column-spacing']) ) ? self::$options['column-spacing'] : 'default';
      

      // Theme Colors.
      self::$available_theme_colors = array(
        'accent-color' => 'Salient Accent Color',
        'extra-color-1' => 'Salient Extra Color #1',
        'extra-color-2' => 'Salient Extra Color #2',
        'extra-color-3' => 'Salient Extra Color #3'
      );

      $custom_colors = apply_filters('nectar_additional_theme_colors', array());
      if( $custom_colors && !empty($custom_colors) ) {
        $custom_colors = array_flip($custom_colors);
      }

      self::$available_theme_colors = array_merge(self::$available_theme_colors, $custom_colors);
      

      foreach( self::$available_theme_colors as $color => $display_name ) {
        
          self::$colors[$color] = array(
            'display_name' => $display_name,
            'value' => ''
          );

          if( isset( self::$options[$color]) && !empty( self::$options[$color]) ) {
            self::$colors[$color]['value'] = self::$options[$color];
          }
        
      }

      // Overall Colors.
      $overall_font_color = ( isset(self::$options['overall-font-color']) ) ? self::$options['overall-font-color'] : false;
      if( $overall_font_color ) {
        self::$colors['overall_font_color'] = $overall_font_color;
      }


    }

    public static function get_active_special_locations() {
      $special_locations = get_option( 'salient_global_section_special_locations', array() );
      return $special_locations;
    }

   /**
    * Determines if a special location is active.
    * @param string $location
    * @return mixed
    */
    public static function is_special_location_active($location) {
        $special_locations = self::get_active_special_locations();
        return isset($special_locations[$location]) ? $special_locations[$location] : false;
    }


  }
  

  /**
	 * Initialize the NectarThemeManager class
	 */
	NectarThemeManager::get_instance();
}
