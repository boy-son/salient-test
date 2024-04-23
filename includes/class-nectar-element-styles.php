<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}


/**
* Nectar Dynamic Element Styles.
*
* @since 11.1
*/

class NectarElDynamicStyles {

  private static $instance;

  public static $element_css = array();
  public static $elements_arr = array(
    'vc_row',
    'vc_row_inner',
    'vc_column',
    'vc_column_inner',
    'icon',
    'nectar_icon',
    'nectar_post_grid',
    'nectar_category_grid',
    'image_with_animation',
    'nectar_highlighted_text',
    'nectar_scrolling_text',
    'nectar_rotating_words_title',
    'fancy_box',
    'fancy-ul',
    'split_line_heading',
    'nectar_flip_box',
    'divider',
    'nectar_gmap',
    'nectar_cta',
    'nectar_btn',
    'nectar_cascading_images',
    'nectar_horizontal_list_item',
    'nectar_icon_list',
    'nectar_icon_list_item',
    'nectar_gradient_text',
    'nectar_star_rating',
    'nectar_circle_images',
    'nectar_image_with_hotspots',
    'nectar_video_player_self_hosted',
    'nectar_text_inline_images',
    'nectar_badge',
    'nectar_lottie',
    'nectar_price_typography',
    'nectar_responsive_text',
    'nectar_animated_shape',
    'recent_posts',
    'testimonial_slider',
    'pricing_column',
    'vc_gallery',
    'carousel',
    'item',
    'nectar_sticky_media_sections',
    'nectar_sticky_media_section',
    'tabbed_section',
    'toggles',
    'tab',
    'bar',
    'vc_pie',
    'button',
  );

  public static $devices = array(
    'desktop' => ', print',
    'tablet' => 'and (max-width: 999px)',
    'phone' => 'and (max-width: 690px)'
  );

  public static $using_fullscreen_rows = false;
  public static $using_front_end_editor = false;
  public static $theme_colors = array();

  public function __construct() {

    $nectar_using_VC_front_end_editor = (isset($_GET['vc_editable'])) ? sanitize_text_field($_GET['vc_editable']) : '';
    
    self::$using_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;
    
    add_action( 'wp_ajax_nectar_frontend_builder_generate_styles', array( $this, 'nectar_frontend_builder_generate_styles' ) );
  }


  /**
  * Used to regenerate styles when using the frontend editor.
  *
  */
  public static function nectar_frontend_builder_generate_styles() {

    if( !wp_verify_nonce( $_POST['_vcnonce'], 'vc-nonce-vc-admin-nonce' ) || true !== current_user_can('edit_pages') ) {
      exit();
    }

    self::$using_front_end_editor = true;

    if ( isset( $_POST['nectar_page_content'] ) ) {

      self::$element_css = array();

      $content = htmlspecialchars( stripslashes( $_POST['nectar_page_content'] ),  ENT_NOQUOTES );

      self::generate_el_styles($content);

      echo self::remove_duplicate_rules(self::$element_css);

    }

    exit();

  }


  /**
  * Removes duplicate css rules.
  *
  */
  public static function remove_duplicate_rules($rules) {

    if( is_array($rules) ) {

      $rules = array_unique($rules);
  
      usort($rules, array( 'NectarElDynamicStyles', 'order_media_queries' ));
 
      $css_rules = '';

      foreach($rules as $rule) {
        $css_rules .= $rule;
      }

      return $css_rules;

    }

    return '';

  }
  
  /**
  * Callback for usort to ensure that mobile media 
  * queries stay at the bottom.
  *
  */
  public static function order_media_queries($a, $b) {

    $media690 = '@media only screen and (max-width: 690px)';
    $media999 = '@media only screen and (max-width: 999px)';

    if (strpos( substr($a,0,41), $media690) !== false) {
        return 1; // Put $a after $b since $a starts with "@media only screen and (max-width: 690px)"
    } elseif (strpos( substr($b,0,41), $media690) !== false) {
        return -1; // Put $a before $b since $b starts with "@media only screen and (max-width: 690px)"
    } elseif (strpos( substr($a,0,41), $media999) !== false) {
        return 1; // Put $a after $b since $a starts with "@media only screen and (max-width: 999px)"
    } elseif (strpos( substr($b,0,41), $media999) !== false) {
        return -1; // Put $a before $b since $b starts with "@media only screen and (max-width: 999px)"
    } else {
        return 0; // Preserve original order for all other cases
    }

  }


  /**
  * Calls all functions to handle styles for
  * elements on page and obscured in templates
  *
  * @see nectar/helpers/dynamic-styles.php location where called.
  */
  public static function generate_styles($post_content) {

    global $post;

    $page_full_screen_rows = ( isset( $post->ID ) ) ? get_post_meta( $post->ID, '_nectar_full_screen_rows', true ) : '';
    if( 'on' === $page_full_screen_rows ) {
      self::$using_fullscreen_rows = true;
    }

    // Generate All.
    self::generate_el_styles($post_content);
    self::generate_templatera_el_styles($post_content);

    // Output if styles exist.
    if( count(self::$element_css) > 0 ) {

      $rules = self::remove_duplicate_rules(self::$element_css);
      return $rules;
    }

    

    return '';

  }



  /**
  * Generates dynamic CSS of elements on current page.
  */
  public static function generate_el_styles($post_content) {
    
    // Regular Pages.
    if(!empty($post_content) ) {

      self::get_theme_colors();

      foreach( self::$elements_arr as $element ) {

        if ( preg_match_all( '/\['.$element.'(\s.*?)?\]/s', $post_content, $matches, PREG_SET_ORDER ) )  {

          if (!empty($matches)) {

            foreach ($matches as $shortcode) {

              // Output CSS.
              self::element_css($shortcode);

            } // End Single Element Item Loop.

          } // End Not Empty Matches.

        } // End Preg Match.

      } // End Element Loop.

    }

  }


  /**
  * Locates the content of a templatera template and generates dynamic CSS.
  */
  public static function generate_templatera_el_styles($post_content) {

    // Global shortcodes.
    preg_match_all( '/\[templatera(\s.*?)?\]/s', $post_content, $templatera_shortcode_match, PREG_SET_ORDER  );
    preg_match_all( '/\[nectar_global_section(\s.*?)?\]/s', $post_content, $nectar_global_section_match, PREG_SET_ORDER  );

    $global_template_shortcode_match = array_merge($templatera_shortcode_match, $nectar_global_section_match);

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
              self::generate_styles($templatera_content_query->post_content);
            }
          }

        }

      } // End global section shortcode Loop.

    } // End found Templatera.


  }

  /**
  * Stores all theme colors.
  */
  public static function get_theme_colors() {

    $nectar_options = get_nectar_theme_options();

    $colors = array(
      array(
        'type' => 'regular',
        'stored_name' => 'accent-color',
        'display_name' => 'accent-color',
        'color_code' => ''
      ),
      array(
        'type' => 'regular',
        'stored_name' => 'extra-color-1',
        'display_name' => 'extra-color-1',
        'color_code' => ''
      ),
      array(
        'type' => 'regular',
        'stored_name' => 'extra-color-2',
        'display_name' => 'extra-color-2',
        'color_code' => ''
      ),
      array(
        'type' => 'regular',
        'stored_name' => 'extra-color-3',
        'display_name' => 'extra-color-3',
        'color_code' => ''
      ),
      array(
        'type' => 'gradient',
        'stored_name' => 'extra-color-gradient',
        'display_name' => 'extra-color-gradient-1',
        'color_code' => array(
          'from' => '',
          'to' => ''
        )
      ),
      array(
        'type' => 'gradient',
        'stored_name' => 'extra-color-gradient-2',
        'display_name' => 'extra-color-gradient-2',
        'color_code' => array(
          'from' => '',
          'to' => ''
        )
      )
    );

    // custom user colors.
    $custom_colors = apply_filters('nectar_additional_theme_colors', array());
    if( $custom_colors && !empty($custom_colors) ) {
      $custom_colors = array_flip($custom_colors);
    }
    foreach( $custom_colors as $color_key => $label) {

      if( isset($nectar_options[$color_key]) && !empty($nectar_options[$color_key]) ) {

        $colors[] = array(
          'type' => 'regular',
          'stored_name' => $color_key,
          'display_name' => $color_key,
          'color_code' => ''
        );
      }
      
    }

    $theme_colors_stored = array();

    foreach( $colors as $color ) {

      if( isset($nectar_options[$color['stored_name']]) ) {

        $c = $nectar_options[$color['stored_name']];

        // Gradient.
        if( 'gradient' === $color['type'] ) {

          $theme_colors_stored[$color['display_name']] = array(
            'gradient' => array(
              'from' => isset($c['from']) ? $c['from'] : '',
              'to' => isset($c['to']) ? $c['to'] : ''
            ),
            'name' => $color['display_name']
          );

        }
        // Regular
        else {
          $theme_colors_stored[$color['display_name']] = array(
            'color' => $c,
            'name' => $color['display_name']
          );
        }

      } // Color is set in theme options.

    } // End color loop.

    self::$theme_colors = $theme_colors_stored;

  }


  /**
  * Locates and returns a single theme color
  */
  public static function locate_color($color_name) {

    if( isset( self::$theme_colors[$color_name] ) ) {

      if( isset( self::$theme_colors[$color_name]['color'] ) &&
          !empty( self::$theme_colors[$color_name]['color'] ) ) {

        return self::$theme_colors[$color_name];

      }
      else if( isset( self::$theme_colors[$color_name]['gradient'] ) &&
               isset( self::$theme_colors[$color_name]['gradient']['to'] ) &&
               !empty( self::$theme_colors[$color_name]['gradient']['to'] ) ) {

        return self::$theme_colors[$color_name];

      }

    }

    return false;

  }


  /**
  * Outputs the dynamic CSS for a specific element passed to it.
  */
  public static function element_css($shortcode) {

    if( !isset($shortcode[0]) ) {
      return;
    }

    if( strpos($shortcode[0],'[') !== false && strpos($shortcode[0],']') !== false ) {
      $shortcode_inner = substr($shortcode[0], 1, -1);
    } else {
      $shortcode_inner = $shortcode[0];
    }

    $override = '!important';
    $devices  = array(
      'tablet' => '999px',
      'phone' => '690px'
    );


    // Row Element.
    if ( false !== strpos($shortcode[0],'[vc_row ') || false !== strpos($shortcode[0],'[vc_row_inner ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

      $row_selector = ( false !== strpos($shortcode[0],'[vc_row ') ) ? '.vc_row' : '.vc_row.inner_row';
      $row_span12_selector = ( false !== strpos($shortcode[0],'[vc_row ') ) ? '.row_col_wrap_12' : '.row_col_wrap_12_inner';
      $fullscreen_rows_bypass = ( false !== strpos($shortcode[0],'[vc_row ') && true === self::$using_fullscreen_rows ) ? true : false;

      $row_column_margin = ( isset($atts['column_margin']) ) ? esc_attr($atts['column_margin']) : 'default';

      // Column margin.
      //// None.
      if( $row_column_margin === 'none' ) {

        self::$element_css[] = '
        body .container-wrap .wpb_row[data-column-margin="none"]:not(.full-width-section):not(.full-width-content) {
          margin-bottom: 0;
        }
        
        body .container-wrap .vc_row-fluid[data-column-margin="none"] > .span_12,
        body .container-wrap .vc_row-fluid[data-column-margin="none"] .full-page-inner > .container > .span_12,
        body .container-wrap .vc_row-fluid[data-column-margin="none"] .full-page-inner > .span_12 {
          margin-left: 0;
          margin-right: 0;
        }
        
        body .container-wrap .vc_row-fluid[data-column-margin="none"] .wpb_column:not(.child_column),
        body .container-wrap .inner_row[data-column-margin="none"] .child_column {
          padding-left: 0;
          padding-right: 0;
        }';

      }

      /// PX amount.
      if( in_array( $row_column_margin, array('5px','10px','20px','30px','40px','50px','60px','70px','80px','90px','100px')) ) {

        $column_margin_amt = intval($row_column_margin);

        if( in_array($column_margin_amt,array(5,10,20,30,40)) ) {
          self::$element_css[] = '
          body .container-wrap .wpb_row[data-column-margin="'.$column_margin_amt.'px"]:not(.full-width-section):not(.full-width-content) {
            margin-bottom: '.$column_margin_amt.'px;
          }';
        }

        self::$element_css[] = '
        body .container-wrap .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"] > .span_12,
        body .container-wrap .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"] .full-page-inner > .container > .span_12,
        body .container-wrap .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"] .full-page-inner > .span_12 {
          margin-left: -'.($column_margin_amt/2).'px;
          margin-right: -'.($column_margin_amt/2).'px;
        }

        body .container-wrap .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"] .wpb_column:not(.child_column),
        body .container-wrap .inner_row[data-column-margin="'.$column_margin_amt.'px"] .child_column {
          padding-left: '.($column_margin_amt/2).'px;
          padding-right: '.($column_margin_amt/2).'px;
        }
        .container-wrap .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"].full-width-content > .span_12,
        .container-wrap .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"].full-width-content .full-page-inner > .span_12 {
          margin-left: 0;
          margin-right: 0;
          padding-left: '.($column_margin_amt/2).'px;
          padding-right: '.($column_margin_amt/2).'px;
        }
        .single-portfolio #full_width_portfolio .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"].full-width-content > .span_12 {
          padding-right: '.($column_margin_amt/2).'px;
        }
        
        @media only screen and (max-width: 999px) and (min-width: 691px) {
          .vc_row-fluid[data-column-margin="'.$column_margin_amt.'px"] > .span_12 > .one-fourths:not([class*="vc_col-xs-"]),
          .vc_row-fluid .vc_row-fluid.inner_row[data-column-margin="'.$column_margin_amt.'px"] > .span_12 > .one-fourths:not([class*="vc_col-xs-"]) {
            margin-bottom: '.$column_margin_amt.'px;
          }
        }';

      }

      // Custom column gap
      if( isset($atts['column_margin_custom']) && !empty($atts['column_margin_custom']) ) {

        $custom_column_gap = self::percent_unit_type($atts['column_margin_custom']);
        
        self::$element_css[] = '@media only screen and (min-width: 1000px) { 
            #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).'.wpb_row > .span_12,
            #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).'.wpb_row .full-page-inner > .container > .span_12,
            #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).'.wpb_row .full-page-inner > .span_12 {
              margin-left: calc('.$custom_column_gap.'/-2);
              margin-right: calc('.$custom_column_gap.'/-2);
            }
            #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).' .wpb_column:not(.child_column),
            #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).'.inner_row .child_column {
              padding-left: calc('.$custom_column_gap.'/2);
              padding-right: calc('.$custom_column_gap.'/2);
            }
          }
        ';
        self::$element_css[] = '@media only screen and (max-width: 999px) { 
          #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).'.wpb_row > .span_12:has([class*="vc_col-xs"]:not(.vc_col-xs-12)) {
            margin-left: calc('.$custom_column_gap.'/-2);
            margin-right: calc('.$custom_column_gap.'/-2);
          }
          #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).' .wpb_column[class*="vc_col-xs"]:not(.vc_col-xs-12):not(.child_column),
          #ajax-content-wrap .column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).'.inner_row .child_column[class*="vc_col-xs"]:not(.vc_col-xs-12) {
            padding-left: calc('.$custom_column_gap.'/2);
            padding-right: calc('.$custom_column_gap.'/2);
          }
        }';

      }

      // Reverse Columns.
      if( isset($atts['column_direction']) && 'reverse' === $atts['column_direction'] ) {
        self::$element_css[] = '@media only screen and (min-width : 1000px) {
          .wpb_row.reverse_columns_desktop .row_col_wrap_12,
          .wpb_row.inner_row.reverse_columns_desktop .row_col_wrap_12_inner {
            flex-direction: row-reverse;
          }
        }';
      }

      // z-index special case.
      if ( isset($atts['zindex']) && $atts['zindex'] === 'auto' ) {
        self::$element_css[] = $row_selector.'.z-index-auto > .row_col_wrap_12 {
          z-index: auto;
        }';
    
      }
      
      // Inner Row specific.
      if ( false !== strpos($shortcode[0],'[vc_row_inner ') ) {

        //// DESKTOP ONLY.

        if( isset($atts['pointer_events']) && 'none' === $atts['pointer_events'] ) {
          self::$element_css[] = $row_selector.'.no-pointer-events {
            pointer-events: none;
          }';
         }

        // min width.
        if( isset($atts['min_width_desktop']) && strlen($atts['min_width_desktop']) > 0 ) {
          self::$element_css[] = $row_selector.'.min_width_desktop_'. esc_attr( self::percent_unit_type_class($atts['min_width_desktop']) ) .' {
            min-width: '.esc_attr( self::percent_unit_type($atts['min_width_desktop']) ).';
          }';
        }
        // max width.
        if( isset($atts['max_width_desktop']) && strlen($atts['max_width_desktop']) > 0 ) {
          self::$element_css[] = $row_selector.'.max_width_desktop_'. esc_attr( self::percent_unit_type_class($atts['max_width_desktop']) ) .' {
            max-width: '.esc_attr( self::percent_unit_type($atts['max_width_desktop']) ).';
          }';
        }
        

        // positioning.
        if( isset($atts['row_position']) && 
            strlen($atts['row_position']) > 0 && 
            in_array($atts['row_position'], array('relative', 'absolute','fixed')) ) {
            self::$element_css[] = $row_selector.'.row_position_'. esc_attr( $atts['row_position'] ) .' {
              position: '.esc_attr( $atts['row_position'] ).';
            }';

            if( isset($atts['top_position_desktop']) && strlen($atts['top_position_desktop']) > 0 ) {
              self::$element_css[] = $row_selector.'.top_position_desktop_'. esc_attr( self::percent_unit_type_class($atts['top_position_desktop']) ) .' {
                top: '.esc_attr( self::percent_unit_type($atts['top_position_desktop']) ).';
              }';
            }
            if( isset($atts['bottom_position_desktop']) && strlen($atts['bottom_position_desktop']) > 0 ) {
             self::$element_css[] = $row_selector.'.bottom_position_desktop_'. esc_attr( self::percent_unit_type_class($atts['bottom_position_desktop']) ) .' {
              bottom: '.esc_attr( self::percent_unit_type($atts['bottom_position_desktop']) ).';
             }';
            }
            if( isset($atts['left_position_desktop']) && strlen($atts['left_position_desktop']) > 0 ) {
             self::$element_css[] = $row_selector.'.left_position_desktop_'. esc_attr( self::percent_unit_type_class($atts['left_position_desktop']) ) .' {
                left: '.esc_attr( self::percent_unit_type($atts['left_position_desktop']) ).';
             }';
            }
            if( isset($atts['right_position_desktop']) && strlen($atts['right_position_desktop']) > 0 ) {
             self::$element_css[] = $row_selector.'.right_position_desktop_'. esc_attr( self::percent_unit_type_class($atts['right_position_desktop']) ) .' {
                right: '.esc_attr( self::percent_unit_type($atts['right_position_desktop']) ).';
             }';
            }
            
        }

        //// DEVICES.
        foreach( $devices as $device => $media_query ) {

          // Min Width.
          if( isset($atts['min_width_'.$device]) && strlen($atts['min_width_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              body '.$row_selector.'.min_width_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_width_'.$device]) ) .' {
              min-width: '.esc_attr( self::percent_unit_type($atts['min_width_'.$device]) ).';
            } }';

          }

          // Max Width.
          if( isset($atts['max_width_'.$device]) && strlen($atts['max_width_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              body '.$row_selector.'.max_width_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['max_width_'.$device]) ) .' {
              max-width: '.esc_attr( self::percent_unit_type($atts['max_width_'.$device]) ).';
            } }';

          }


          // Positioning.

          // top.
          if( isset($atts['row_position']) && 
            strlen($atts['row_position']) > 0 && 
            in_array($atts['row_position'], array('relative', 'absolute','fixed')) ) {

            if( isset($atts['top_position_'.$device]) && strlen($atts['top_position_'.$device]) > 0 ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
                body '.$row_selector.'.top_position_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['top_position_'.$device]) ) .' {
                top: '.esc_attr( self::percent_unit_type($atts['top_position_'.$device]) ).';
              } }';
            }

            // bottom.
            if( isset($atts['bottom_position_'.$device]) && strlen($atts['bottom_position_'.$device]) > 0 ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
                body '.$row_selector.'.bottom_position_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['bottom_position_'.$device]) ) .' {
                  bottom: '.esc_attr( self::percent_unit_type($atts['bottom_position_'.$device]) ).';
              } }';
            }

            // left.
            if( isset($atts['left_position_'.$device]) && strlen($atts['left_position_'.$device]) > 0 ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
                body '.$row_selector.'.left_position_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['left_position_'.$device]) ) .' {
                  left: '.esc_attr( self::percent_unit_type($atts['left_position_'.$device]) ).';
              } }';
            }

            // right.
            if( isset($atts['right_position_'.$device]) && strlen($atts['right_position_'.$device]) > 0 ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
                body '.$row_selector.'.right_position_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['right_position_'.$device]) ) .' {
                  right: '.esc_attr( self::percent_unit_type($atts['right_position_'.$device]) ).';
              } }';
            }
          } // End Positioning.

          if( isset($atts['row_position_'.$device]) && 
             strlen($atts['row_position_'.$device]) > 0 &&
             in_array($atts['row_position_'.$device], array('absolute','relative','fixed')) ) {
               
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
                body '.$row_selector.'.row_position_'.$device.'_'. esc_attr( $atts['row_position_'.$device] )  .' {
                  position: '.esc_attr( $atts['row_position_'.$device] ).';
              } }';
          }
     

        } // End loop.

      } // End Inner Row specific.

      // Parent Row specific.
      if( false !== strpos($shortcode[0],'[vc_row ') ) {

        //// Sticky.
        if( isset($atts['sticky_row']) && 'true' === $atts['sticky_row'] ) {

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            html body {
              overflow: visible;
            }
            .nectar-sticky-row-wrap {
              position: sticky;
              z-index: 100;
            }
            #header-space {
              z-index: 100;
            }
            .wpb_row .row-bg-wrap .row-bg {
              transform: translateZ(0) scale(1.006);
            }
          }';

          if (isset($atts['sticky_row_alignment']) && !empty($atts['sticky_row_alignment']) ) {
            
            if ('top' === $atts['sticky_row_alignment']) {
              self::$element_css[] = '
              .nectar-sticky-row-wrap--top {
                top: 0;
              }';
            } 
            else if ('bottom' === $atts['sticky_row_alignment']) {
              self::$element_css[] = '
              .nectar-sticky-row-wrap--bottom {
                bottom: 0;
              }';
            }
            else if( 'top_after_nav' === $atts['sticky_row_alignment'] ) {
              self::$element_css[] = '
              .nectar-sticky-row-wrap--top_after_nav {
                top: calc(var(--nectar-sticky-top-distance) - 50px);
              }';
            }

          }

        }

        // Animated gradient.
        if( isset($atts['animated_gradient_bg']) && 'true' == $atts['animated_gradient_bg'] ) {
          self::$element_css[] = '.nectar-animated-gradient {
            opacity: 0;
            z-index: 1;
            transform: translateZ(0);
            transition: opacity 0.8s ease; 
          }
          .nectar-animated-gradient.loaded {
            opacity: 1;
          }
          .row-bg-wrap.has-animated-gradient {
            overflow: hidden; 
          }';
        }

        // Clip path bg animaiton.
        $clip_path_type = isset($atts['clip_path_animation_type']) ? $atts['clip_path_animation_type'] : '';
        if( 'scroll' !== $clip_path_type ) {

          if( isset($atts['clip_path_animation_applies']) && 'row' === $atts['clip_path_animation_applies'] ) {
            $clip_path_styles = self::clip_path_group_styles('.clip-path-entire-row', $atts);
          } else {
            $clip_path_styles = self::clip_path_group_styles(' .row-bg-layer', $atts);
          }

          foreach($clip_path_styles as $style) {
            self::$element_css[] = $style;
          }
        } else {
          
          // scroll based.
          $clip_params = array(
            'clip_path_start_top',
            'clip_path_start_right',
            'clip_path_start_bottom',
            'clip_path_start_left',
            'clip_path_end_top',
            'clip_path_end_right',
            'clip_path_end_bottom',
            'clip_path_end_left'
          );
          foreach( $clip_params as $param ) {
            if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {
              self::$element_css[] = '[data-nectar-animate-settings] .nectar-shape-divider-wrap { z-index: 0; } [data-nectar-animate-settings] .nectar-shape-divider-wrap .nectar-shape-divider { bottom: 0; }';
            }
          }
          
        }

        $clip_path_animation_addon = (isset($atts['clip_path_animation_addon'])) ? esc_attr($atts['clip_path_animation_addon']) : 'none';

        if( 'fade' === $clip_path_animation_addon ) {

           self::$element_css[] = '
           @keyframes nectar_fade_in {
              0% {
                opacity: 0;
              }
              100% {
                opacity: 1;
              }
           }

           .clip-path-animation-addon-fade .nectar-video-wrap .nectar-video-inner, 
           .clip-path-animation-addon-fade .row-bg,
           .clip-path-animation-addon-fade .nectar-parallax-scene,
           .clip-path-animation-addon-fade .nectar-animated-gradient {
              opacity: 0;
              animation: nectar_fade_in forwards 1.3s cubic-bezier(0.25, 0.1, 0.18, 1);
           }';
        } 

        else if( 'zoom' === $clip_path_animation_addon) {
          self::$element_css[] = '
           @keyframes nectar_zoom_fade_in {
              0% {
                opacity: 0;
                transform: scale(1.2);
              }
              100% {
                opacity: 1;
                transform: scale(1);
              }
           }

           .clip-path-animation-addon-zoom .nectar-video-wrap .nectar-video-inner, 
           .clip-path-animation-addon-zoom .row-bg,
           .clip-path-animation-addon-zoom .nectar-parallax-scene,
           .clip-path-animation-addon-zoom .nectar-animated-gradient {
             opacity: 0;
             animation: nectar_zoom_fade_in forwards 3s ease-out;
           }';

        }
        if( 'fade' === $clip_path_animation_addon || 'zoom' === $clip_path_animation_addon ) {
          if( isset( $atts['bg_image_animation_delay'] ) && !empty($atts['bg_image_animation_delay']) ) {  
            self::$element_css[] = '
              [data-bg-animation-delay="'.esc_attr($atts['bg_image_animation_delay']).'"] .nectar-video-wrap .nectar-video-inner, 
              [data-bg-animation-delay="'.esc_attr($atts['bg_image_animation_delay']).'"] .row-bg,
              [data-bg-animation-delay="'.esc_attr($atts['bg_image_animation_delay']).'"] .nectar-parallax-scene,
              [data-bg-animation-delay="'.esc_attr($atts['bg_image_animation_delay']).'"] .nectar-animated-gradient {
                animation-delay: '.esc_attr($atts['bg_image_animation_delay']).'ms;
              }';
            }
        }

        //// Border Radius.
        if( isset($atts['row_border_radius']) && !empty($atts['row_border_radius']) && 'none' !== $atts['row_border_radius'] ) {

          $br_applies_to = ( isset($atts['row_border_radius_applies']) && !empty($atts['row_border_radius_applies']) ) ? $atts['row_border_radius_applies'] : 'bg';
          $row_border_radius = esc_attr($atts['row_border_radius']);

          // Applies to Row.
          if( 'inner' === $br_applies_to || 'both' === $br_applies_to ) {
            if ( $row_border_radius !== 'custom' ) {
              self::$element_css[] = '.wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="'.esc_attr($br_applies_to).'"] .row_col_wrap_12 {
                border-radius: '.esc_attr($atts['row_border_radius']).';
              }';
            } else {
              self::$element_css[] = self::custom_border_radius_styles($atts, '[data-br-applies="'.esc_attr($br_applies_to).'"] .row_col_wrap_12'); 
            }
            
          }

          // Applies to BG.
          if( 'bg' === $br_applies_to || 'both' === $br_applies_to ) {
            if ( $row_border_radius !== 'custom' ) {
              self::$element_css[] = '.wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="'.esc_attr($br_applies_to).'"] > .row-bg-wrap,
              .wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="'.esc_attr($br_applies_to).'"] > .nectar-video-wrap,
              .wpb_row[data-br="'.esc_attr($atts['row_border_radius']).'"][data-br-applies="'.esc_attr($br_applies_to).'"] > .nectar-parallax-scene {
                border-radius: '.esc_attr($atts['row_border_radius']).';
              }';
            } else {
              
              self::$element_css[] = self::custom_border_radius_styles($atts, '[data-br-applies="'.esc_attr($br_applies_to).'"] > .row-bg-wrap'); 
              self::$element_css[] = self::custom_border_radius_styles($atts, '[data-br-applies="'.esc_attr($br_applies_to).'"] > .nectar-video-wrap'); 
              self::$element_css[] = self::custom_border_radius_styles($atts, '[data-br-applies="'.esc_attr($br_applies_to).'"] > .nectar-parallax-scene'); 

            }
          }

        } // End Border Radius.


        //// Shape Divider Height.
        $shape_divider_tablet = (isset($atts['shape_divider_height_tablet']) && strlen($atts['shape_divider_height_tablet']) > 0) ? $atts['shape_divider_height_tablet'] : false;
        $shape_divider_phone = (isset($atts['shape_divider_height_phone']) && strlen($atts['shape_divider_height_phone']) > 0) ? $atts['shape_divider_height_phone'] : false;

        if( $shape_divider_tablet ) {
          self::$element_css[] = '@media only screen and (max-width: 999px) {
            .wpb_row.shape_divider_tablet_'.esc_attr( self::percent_unit_type_class($shape_divider_tablet) ).' .nectar-shape-divider-wrap {
              height: '.esc_attr( self::percent_unit_type($shape_divider_tablet) ).'!important;
            }
          }';
        }
        if( $shape_divider_phone ) {
          self::$element_css[] = '@media only screen and (max-width: 690px) {
            .wpb_row.shape_divider_phone_'.esc_attr( self::percent_unit_type_class($shape_divider_phone) ).' .nectar-shape-divider-wrap {
              height: '.esc_attr( self::percent_unit_type($shape_divider_phone) ).'!important;
            }
          }';
        }

        // Parallax mouse scene.
        if( isset($atts['mouse_based_parallax_bg']) && 'true' === $atts['mouse_based_parallax_bg'] ) {

          self::$element_css[] = '.wpb_row .nectar-parallax-scene{
            position:absolute;
            top:0;
            left:0;
            margin-bottom:0;
            padding-bottom:0;
          	margin-left: 0;
            overflow:hidden;
            width:100%;
            height:100%;
            z-index:2;
            -webkit-backface-visibility:hidden;
            backface-visibility:hidden;
            -webkit-transform:translate3d(0px,0px,0px);
            transform:translate3d(0px,0px,0px);
            -webkit-transform-style:preserve-3d;
            transform-style:preserve-3d
          }
          .wpb_row.full-width-content .nectar-parallax-scene{
            margin-left: 0;
          }
          .wpb_row .nectar-parallax-scene li:first-child {
            position: relative;
          }
          .wpb_row .nectar-parallax-scene li{
            height:100%;
            width:100%;
            position: absolute;
            top: 0;
            left: 0;
            display: block;
          }
          .wpb_row .nectar-parallax-scene div{
            margin-left:-10%;
            top:-10%;
            min-height:100%;
            width:120%;
            height:120%;
            background-size:cover;
            margin-bottom:0;
            max-width:none;
            position:relative;
            -webkit-backface-visibility:hidden;
            backface-visibility:hidden;
            -webkit-transform:translate3d(0px,0px,0px);
            transform:translate3d(0px,0px,0px);
            -webkit-transform-style:preserve-3d;
            transform-style:preserve-3d
          }';

          if( isset($atts['scene_position']) && 'center' === $atts['scene_position'] ) {
            self::$element_css[] = '.wpb_row .nectar-parallax-scene[data-scene-position="center"] div{
                background-position:center
              }';
          }
          if( isset($atts['scene_position']) && 'top' === $atts['scene_position'] ) {
            self::$element_css[] = '
              .wpb_row .nectar-parallax-scene[data-scene-position="top"] div{
                background-position:center top
              }';
          }
          if( isset($atts['scene_position']) && 'bottom' === $atts['scene_position'] ) {
            self::$element_css[] = '.wpb_row .nectar-parallax-scene[data-scene-position="bottom"] div{
                background-position:center bottom
              }';
          }

        }


        //// Margin
        if( true !== $fullscreen_rows_bypass ) {

          if( isset($atts['left_margin']) && strlen($atts['left_margin']) > 0 ) {
            self::$element_css[] = '#ajax-content-wrap '. $row_selector.'.left_margin_'. esc_attr( self::percent_unit_type_class($atts['left_margin']) ) . ' {
              margin-left: '.esc_attr( self::percent_unit_type($atts['left_margin']) ).';
            } ';
          }
          if( isset($atts['top_margin']) && strlen($atts['top_margin']) > 0 ) {
            self::$element_css[] = '#ajax-content-wrap '. $row_selector.'.top_margin_'. esc_attr( self::percent_unit_type_class($atts['top_margin']) ) . ' {
              margin-top: '.esc_attr( self::percent_unit_type($atts['top_margin']) ).';
            } ';
          }
          if( isset($atts['right_margin']) && strlen($atts['right_margin']) > 0 ) {
            self::$element_css[] = '#ajax-content-wrap '. $row_selector.'.right_margin_'. esc_attr( self::percent_unit_type_class($atts['right_margin']) ) . ' {
              margin-right: '.esc_attr( self::percent_unit_type($atts['right_margin']) ).';
            } ';
          }
          if( isset($atts['bottom_margin']) && strlen($atts['bottom_margin']) > 0 ) {
            self::$element_css[] = '#ajax-content-wrap '. $row_selector.'.bottom_margin_'. esc_attr( self::percent_unit_type_class($atts['bottom_margin']) ) . ' {
              margin-bottom: '.esc_attr( self::percent_unit_type($atts['bottom_margin']) ).';
            } ';
          }

        }

         // Disable entrance animation.
        if( isset($atts['mobile_disable_bg_image_animation']) && 'true' == $atts['mobile_disable_bg_image_animation'] ) {
            self::$element_css[] = 
          '@media only screen and (max-width: 999px) {
            .mobile-disable-bg-image-animation .row-bg-wrap .inner-wrap,
            .mobile-disable-bg-image-animation .nectar-video-wrap .nectar-video-inner, 
            .mobile-disable-bg-image-animation .row-bg {
              animation: none!important;
            }
            .mobile-disable-bg-image-animation .row-bg-wrap .row-bg {
              opacity: 1!important;
            }
              .mobile-disable-bg-image-animation .row-bg-wrap .inner-wrap {
                transform: none!important;
                opacity: 1!important;
            }
            .mobile-disable-bg-image-animation .row-bg-layer {
              clip-path: none!important;
            }
          }';
        }
        

      } // End Parent Row specific.


      // Custom text color.
      if( isset($atts['text_color']) && 'custom' === $atts['text_color'] ) {
        self::$element_css[] = '.wpb_row[data-using-ctc="true"] h1,
        .wpb_row[data-using-ctc="true"] h2,
        .wpb_row[data-using-ctc="true"] h3,
        .wpb_row[data-using-ctc="true"] h4,
        .wpb_row[data-using-ctc="true"] h5,
        .wpb_row[data-using-ctc="true"] h6{
          color:inherit
        }';
      }
      
      // Shape Divider.
      if( isset($atts['enable_shape_divider']) && 'true' === $atts['enable_shape_divider'] ) {

        self::$element_css[] = '
        .nectar-shape-divider-wrap {
          position: absolute;
          top: auto;
          bottom: 0;
          left: 0;
          right: 0;
          width: 100%;
          height: 150px;
          z-index: 3;
          transform: translateZ(0);
        }
        .post-area.span_9 .nectar-shape-divider-wrap {
          overflow: hidden;
        }
        .nectar-shape-divider-wrap[data-front="true"] {
          z-index: 50;
        }
        .nectar-shape-divider-wrap[data-style="waves_opacity"] svg path:first-child {
          opacity: 0.6;
        }

        .nectar-shape-divider-wrap[data-style="curve_opacity"] svg path:nth-child(1),
        .nectar-shape-divider-wrap[data-style="waves_opacity_alt"] svg path:nth-child(1) {
          opacity: 0.15;
        }
        .nectar-shape-divider-wrap[data-style="curve_opacity"] svg path:nth-child(2),
        .nectar-shape-divider-wrap[data-style="waves_opacity_alt"] svg path:nth-child(2) {
          opacity: 0.3;
        }
        .nectar-shape-divider {
          width: 100%;
          left: 0;
          bottom: -1px;
          height: 100%;
          position: absolute;
        }
        .nectar-shape-divider-wrap.no-color .nectar-shape-divider {
          fill: #fff;
        }
        @media only screen and (max-width: 999px) {
          .nectar-shape-divider-wrap:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 75%;
          }
          .nectar-shape-divider-wrap[data-style="clouds"]:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 55%;
          }
        }
        @media only screen and (max-width: 690px) {
          .nectar-shape-divider-wrap:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 33%;
          }
          .nectar-shape-divider-wrap[data-style="clouds"]:not([data-using-percent-val="true"]) .nectar-shape-divider {
            height: 33%;
          }
        }

        #ajax-content-wrap .nectar-shape-divider-wrap[data-height="1"] .nectar-shape-divider,
        #ajax-content-wrap .nectar-shape-divider-wrap[data-height="1px"] .nectar-shape-divider {
        	height: 1px;
        }';

        // Shape Divider Position.
        if( isset($atts['shape_divider_position']) && 'top' === $atts['shape_divider_position'] ||
            isset($atts['shape_divider_position']) && 'both' === $atts['shape_divider_position'] ) {
          self::$element_css[] = '.nectar-shape-divider-wrap[data-position="top"] {
            top: -1px;
            bottom: auto;
          }
          .nectar-shape-divider-wrap[data-position="top"] {
            transform: rotate(180deg)
          }';
        }

        // Shape Divider Cloud Style.
        if( isset($atts['shape_type']) && 'clouds' === $atts['shape_type'] ) {
          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-shape-divider-wrap[data-style="clouds"] .nectar-shape-divider {
              min-width: 1700px;
            }
          }
          @media only screen and (max-width: 999px) {
            .nectar-shape-divider-wrap[data-style="clouds"] .nectar-shape-divider {
              min-width: 800px;
            }
          }
          @media only screen and (max-width: 690px) {
            .nectar-shape-divider-wrap[data-style="clouds"] .nectar-shape-divider {
              min-width: 690px;
            }
          }';
        }

        // Shape Divider Fan Style.
        if( isset($atts['shape_type']) && 'fan' === $atts['shape_type'] ) {

          self::$element_css[] = '.nectar-shape-divider-wrap[data-style="fan"] svg {
            width: 102%;
            left: -1%;
          }
          .nectar-shape-divider-wrap[data-style="fan"] svg polygon:nth-child(2) {
            opacity: 0.15;
          }
          .nectar-shape-divider-wrap[data-style="fan"] svg rect {
            opacity: 0.3;
          }';

        }

        // Shape Divider Mountain Style.
        if( isset($atts['shape_type']) && 'mountains' === $atts['shape_type'] ) {
            self::$element_css[] = '.nectar-shape-divider-wrap[data-style="mountains"] svg path:first-child {
            opacity: 0.1;
          }
          .nectar-shape-divider-wrap[data-style="mountains"] svg path:nth-child(2) {
            opacity: 0.12;
          }
          .nectar-shape-divider-wrap[data-style="mountains"] svg path:nth-child(3) {
            opacity: 0.18;
          }
          .nectar-shape-divider-wrap[data-style="mountains"] svg path:nth-child(4) {
            opacity: 0.33;
          }';

        }


      } // End shape divider.

      // DESKTOP SPECIFIC
      //// Left Padding.
      if( true !== $fullscreen_rows_bypass ) {

        if( isset($atts['left_padding_desktop']) && strlen($atts['left_padding_desktop']) > 0 ) {
          self::$element_css[] = '#ajax-content-wrap ' . $row_selector.'.left_padding_'. esc_attr( self::percent_unit_type_class($atts['left_padding_desktop']) ) . ' ' . $row_span12_selector .',
            .nectar-global-section ' . $row_selector.'.left_padding_'. esc_attr( self::percent_unit_type_class($atts['left_padding_desktop']) ) . ' ' . $row_span12_selector .' {
            padding-left: '.esc_attr( self::percent_unit_type($atts['left_padding_desktop']) ).';
          } ';
        }
        //// Right Padding.
        if( isset($atts['right_padding_desktop']) && strlen($atts['right_padding_desktop']) > 0 ) {
          self::$element_css[] = '#ajax-content-wrap ' . $row_selector.'.right_padding_'. esc_attr( self::percent_unit_type_class($atts['right_padding_desktop']) ) . ' ' . $row_span12_selector .',
            .nectar-global-section ' . $row_selector.'.right_padding_'. esc_attr( self::percent_unit_type_class($atts['right_padding_desktop']) ) . ' ' . $row_span12_selector .' {
            padding-right: '.esc_attr( self::percent_unit_type($atts['right_padding_desktop']) ).';
          } ';
        }

      }

      // Device Loop.
      foreach( $devices as $device => $media_query ) {

        // Padding.
        if( true !== $fullscreen_rows_bypass ) {

          //// Top.
          if( isset($atts['top_padding_'.$device]) && strlen($atts['top_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$row_selector.'.top_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['top_padding_'.$device]) ) .' {
              padding-top: '.esc_attr( self::percent_unit_type($atts['top_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }

          //// Bottom.
          if( isset($atts['bottom_padding_'.$device]) && strlen($atts['bottom_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$row_selector.'.bottom_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['bottom_padding_'.$device]) ) .' {
              padding-bottom: '.esc_attr( self::percent_unit_type($atts['bottom_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }


          //// Left.
          if( isset($atts['left_padding_'.$device]) && strlen($atts['left_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { #ajax-content-wrap '.$row_selector.'.left_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['left_padding_'.$device]) ) . ' ' . $row_span12_selector .' {
              padding-left: '.esc_attr( self::percent_unit_type($atts['left_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }
          //// Right.
          if( isset($atts['right_padding_'.$device]) && strlen($atts['right_padding_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { #ajax-content-wrap '.$row_selector.'.right_padding_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['right_padding_'.$device]) ) . ' ' . $row_span12_selector .' {
              padding-right: '.esc_attr( self::percent_unit_type($atts['right_padding_'.$device]) ). esc_attr( $override ).';
            } }';

          }

          //// Margin.
          if( isset($atts['left_margin_'.$device]) && strlen($atts['left_margin_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body #ajax-content-wrap '. $row_selector.'.left_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['left_margin_'.$device]) ) . ' {
              margin-left: '.esc_attr( self::percent_unit_type($atts['left_margin_'.$device]) ).';
            } }';
          }
          if( isset($atts['top_margin_'.$device]) && strlen($atts['top_margin_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body #ajax-content-wrap '. $row_selector.'.top_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['top_margin_'.$device]) ) . ' {
              margin-top: '.esc_attr( self::percent_unit_type($atts['top_margin_'.$device]) ).';
            } }';
          }
          if( isset($atts['right_margin_'.$device]) && strlen($atts['right_margin_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body #ajax-content-wrap '. $row_selector.'.right_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['right_margin_'.$device]) ) . ' {
              margin-right: '.esc_attr( self::percent_unit_type($atts['right_margin_'.$device]) ).';
            } }';
          }
          if( isset($atts['bottom_margin_'.$device]) && strlen($atts['bottom_margin_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body #ajax-content-wrap '. $row_selector.'.bottom_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['bottom_margin_'.$device]) ) . ' {
              margin-bottom: '.esc_attr( self::percent_unit_type($atts['bottom_margin_'.$device]) ).';
            } }';
          }

        } // end no fullscreen rows conditional

        // Transform.
        $transform_styles = self::transform_styles($row_selector, $atts, true);
        foreach( $transform_styles as $transform_style ) {
          self::$element_css[] = $transform_style;
        }

        // Reverse Columns.
        if( isset($atts['column_direction_'.$device]) && 
            'default' !== $atts['column_direction_'.$device] ) {

          self::$element_css[] ='@media only screen and (max-width: '.$media_query.') {
          .wpb_row.reverse_columns_row_'.$device.' .row_col_wrap_12,
          .wpb_row.inner_row.reverse_columns_row_'.$device.' .row_col_wrap_12_inner {
            flex-direction: row-reverse;
          }
          .wpb_row.reverse_columns_column_'.$device.' .row_col_wrap_12,
          .wpb_row.inner_row.reverse_columns_column_'.$device.' .row_col_wrap_12_inner {
            flex-direction: column-reverse;
          }
          .wpb_row.reverse_columns_column_'.$device.':not([data-column-margin="none"]):not(.full-width-content) > .row_col_wrap_12 > .wpb_column:last-of-type,
          .wpb_row:not(.full-width-content) .wpb_row.inner_row:not([data-column-margin="none"]).reverse_columns_column_'.$device.' .row_col_wrap_12_inner > .wpb_column:last-of-type {
              margin-bottom: 25px;
          }
          .wpb_row.reverse_columns_column_'.$device.':not([data-column-margin="none"]):not(.full-width-content) > .row_col_wrap_12 > .wpb_column:first-of-type,
          .wpb_row:not(.full-width-content) .wpb_row.inner_row:not([data-column-margin="none"]).reverse_columns_column_'.$device.' .row_col_wrap_12_inner > .wpb_column:first-of-type {
            margin-bottom: 0;
          }
        }';

        }



      } // End foreach device loop.


    } // End Row Element.



    // Column Element.
    else if( false !== strpos($shortcode[0],'[vc_column ') || false !== strpos($shortcode[0],'[vc_column_inner ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

      $col_selector = ( false !== strpos($shortcode[0],'[vc_column ') ) ? '.wpb_column' : '.wpb_column.child_column';

      $padding_type = isset($atts['column_padding_type']) ? $atts['column_padding_type'] : 'default';

      // Column Padding Core.
      if( isset($atts['column_padding']) && 
         'no-extra-padding' !== $atts['column_padding'] && 
         'advanced' !== $padding_type ) {

        $column_padding      = $atts['column_padding'];
        $column_padding      = str_replace('padding-','',$column_padding);
        $column_padding      = str_replace('-percent','',$column_padding);
        $column_padding      = intval($column_padding);
        
        $column_padding_half = $column_padding/2;
        
        $column_padding_half = strval($column_padding_half);
        $column_padding_half = str_replace('.','',$column_padding_half);

        $column_padding_special_cap = $column_padding;
        $leading_zero = '0';

        if( in_array($column_padding, array(2)) ) {
          $column_padding_special_cap = 3;
        } 
        else if( in_array($column_padding, array(4,5)) ) {
          $column_padding_special_cap = 6;
        }

        if( $column_padding_special_cap > 9 ) {
          $leading_zero = '';
        }
        

       

        self::$element_css[] = '

        .col.padding-'.$column_padding.'-percent > .vc_column-inner,
        .col.padding-'.$column_padding.'-percent > .n-sticky > .vc_column-inner { 
          padding: calc(600px * 0.'. $leading_zero.$column_padding_special_cap.'); 
        }
       
        @media only screen and (max-width: 690px) {
          .col.padding-'.$column_padding.'-percent > .vc_column-inner,
          .col.padding-'.$column_padding.'-percent > .n-sticky > .vc_column-inner { 
            padding: calc(100vw * 0.'.$leading_zero.$column_padding_special_cap.'); 
          }
        }

        @media only screen and (min-width: 1000px) {
          .col.padding-'.$column_padding.'-percent > .vc_column-inner,
          .col.padding-'.$column_padding.'-percent > .n-sticky > .vc_column-inner { 
            padding: calc((100vw - 180px) * 0.'.$leading_zero.$column_padding.'); 
          }
          .column_container:not(.vc_col-sm-12) .col.padding-'.$column_padding.'-percent > .vc_column-inner { 
            padding: calc((100vw - 180px) * 0.0'.$column_padding_half.'); 
          }
        }

        @media only screen and (min-width: 1425px) {
          .col.padding-'.$column_padding.'-percent > .vc_column-inner { 
            padding: calc(1245px * 0.'.$leading_zero.$column_padding.'); 
          }
          .column_container:not(.vc_col-sm-12) .col.padding-'.$column_padding.'-percent > .vc_column-inner { 
            padding: calc(1245px * 0.0'.$column_padding_half.'); 
          }
        }
        
        
        .full-width-content .col.padding-'.$column_padding.'-percent > .vc_column-inner { 
          padding: calc(100vw * 0.'.$leading_zero.$column_padding.'); 
        }

        @media only screen and (max-width: 999px) {
          .full-width-content .col.padding-'.$column_padding.'-percent > .vc_column-inner { 
            padding: calc(100vw * 0.'.$leading_zero.$column_padding_special_cap.'); 
          }
        }';

         // Edge case
        if( '20' == $column_padding ) {
          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .full-width-content .column_container:not(.vc_col-sm-12) .col.padding-20-percent > .vc_column-inner { 
              padding: calc(100vw * 0.1); 
            }
          }';
        }
        else {
          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .full-width-content .column_container:not(.vc_col-sm-12) .col.padding-'.$column_padding.'-percent > .vc_column-inner { 
              padding: calc(100vw * 0.0'.$column_padding_half.'); 
            }
          }';
        }
          
      }
    

      // Padding position.
      if( isset($atts['column_padding_position']) && 
         'all' !== $atts['column_padding_position'] && 
          'advanced' !== $padding_type ) {

        $padding_pos = $atts['column_padding_position'];

        if( 'top' === $padding_pos ) {
          self::$element_css[] = '#ajax-content-wrap .col[data-padding-pos="top"] > .vc_column-inner,
          #ajax-content-wrap .col[data-padding-pos="top"] > .n-sticky > .vc_column-inner {
            padding-right:0;
            padding-bottom:0;
            padding-left:0
          }';
        } 
        else if ( 'right' === $padding_pos ) {
          self::$element_css[] = '
          body #ajax-content-wrap .col[data-padding-pos="right"] > .vc_column-inner,
          #ajax-content-wrap .col[data-padding-pos="right"] > .n-sticky > .vc_column-inner {
            padding-left:0;
            padding-top:0;
            padding-bottom:0
          }';
        }
        else if ( 'left' === $padding_pos ) {
          self::$element_css[] = '
          body #ajax-content-wrap .col[data-padding-pos="left"] > .vc_column-inner,
          #ajax-content-wrap .col[data-padding-pos="left"] > .n-sticky > .vc_column-inner {
            padding-right:0;
            padding-top:0;
            padding-bottom:0
          }';
        }
        else if ( 'bottom' === $padding_pos ) {
          self::$element_css[] = '
          body #ajax-content-wrap .col[data-padding-pos="bottom"] > .vc_column-inner,
          #ajax-content-wrap .col[data-padding-pos="bottom"] > .n-sticky > .vc_column-inner {
            padding-right:0;
            padding-top:0;
            padding-left:0
          }';
        }
        else if ( 'top-right' === $padding_pos ) {
          self::$element_css[] = '
          #ajax-content-wrap .col[data-padding-pos="top-right"] > .vc_column-inner {
            padding-bottom:0;
            padding-left:0
          }';
        }
        else if ( 'top-left' === $padding_pos ) {
          self::$element_css[] = '
          #ajax-content-wrap .col[data-padding-pos="top-left"] > .vc_column-inner {
            padding-bottom:0;
            padding-right:0
          }';
        }
        else if ( 'top-bottom' === $padding_pos ) {
          self::$element_css[] = '
          #ajax-content-wrap .col[data-padding-pos="top-bottom"]> .vc_column-inner,
          #ajax-content-wrap .col[data-padding-pos="top-bottom"] > .n-sticky > .vc_column-inner {
            padding-left:0;
            padding-right:0
          }';
        }
        else if ( 'bottom-right' === $padding_pos ) {
          self::$element_css[] = '
          #ajax-content-wrap .col[data-padding-pos="bottom-right"] > .vc_column-inner {
            padding-left:0;
            padding-top:0
          }';
        }
        else if ( 'bottom-left' === $padding_pos ) {
          self::$element_css[] = '
          #ajax-content-wrap .col[data-padding-pos="bottom-left"] > .vc_column-inner {
            padding-right:0;
            padding-top:0
          }';
        }
        else if ( 'left-right' === $padding_pos ) {
          self::$element_css[] = '
          #ajax-content-wrap .col[data-padding-pos="left-right"] > .vc_column-inner,
          #ajax-content-wrap .col[data-padding-pos="left-right"] > .n-sticky > .vc_column-inner {
            padding-top:0;
            padding-bottom:0
          }';
        }

      }

      // Advanced padding;
      if( 'advanced' === $padding_type ) {
        self::$element_css[] = self::spacing_group_styles('padding','wpb_column', ' > .vc_column-inner', $atts);
        self::$element_css[] = self::spacing_group_styles('padding','wpb_column', ' > .n-sticky > .vc_column-inner', $atts);
      }

       // Backdrop filter.
       if( isset($atts['column_backdrop_filter']) && 'blur' === $atts['column_backdrop_filter'] ) {
       
        if( isset($atts['column_backdrop_filter_blur']) && !empty($atts['column_backdrop_filter_blur']) ) {

          if( isset($atts['animation_type']) && in_array($atts['animation_type'], array('parallax', 'entrance_and_parallax')) ) {
            self::$element_css[] = '[data-scroll-animation="true"].backdrop_filter_blur.backdrop_blur_'.intval($atts['column_backdrop_filter_blur']) . ' > .vc_column-inner{
              -webkit-backdrop-filter: blur('.intval($atts['column_backdrop_filter_blur']).'px);
              backdrop-filter: blur('.intval($atts['column_backdrop_filter_blur']).'px);
           }';
          } else {
            self::$element_css[] = '.backdrop_filter_blur.backdrop_blur_'.intval($atts['column_backdrop_filter_blur']) . ' {
              -webkit-backdrop-filter: blur('.intval($atts['column_backdrop_filter_blur']).'px);
              backdrop-filter: blur('.intval($atts['column_backdrop_filter_blur']).'px);
           }';
          }
          

           $column_gap = ( class_exists('NectarThemeManager') && 'default' !== NectarThemeManager::$column_gap ) ? NectarThemeManager::$column_gap : '2%';
           self::$element_css[] = 'body[data-col-gap] #ajax-content-wrap .vc_row-fluid.row-col-gap .wpb_column {
              padding: 0; 
           }
           #ajax-content-wrap .row-col-gap > .span_12:after {
            display: none;
           }
           #ajax-content-wrap .row-col-gap > .span_12 {
            margin-left: 0;
            margin-right: 0;
          }
           @media only screen and (min-width: 1000px) {
            #ajax-content-wrap .row-col-gap > .span_12 {
              column-gap: '.esc_attr($column_gap).';
              flex-wrap: nowrap;
            }
            }
            @media only screen and (max-width: 999px) { 
              #ajax-content-wrap .row-col-gap > .span_12 {
                column-gap: 0!important;
              }
            }';
         }
       }
       

      
      // Mask.
      $shadow = (isset($atts['column_shadow'])) ? esc_attr($atts['column_shadow']) : false;

      self::$element_css[] = self::mask_param_group_styles('wpb_column', ' > .vc_column-inner > .column-bg-layer', $shadow, $atts);
      self::$element_css[] = self::mask_param_group_styles('wpb_column', ' > .n-sticky > .vc_column-inner > .column-bg-layer', $shadow, $atts);
      
      // BG Image Stacking order.
      if( isset($atts['background_image_stacking']) && 'front' === $atts['background_image_stacking'] ) {
        self::$element_css[] = '
        .bg_img_front > .vc_column-inner > .column-bg-overlay-wrap {
          z-index: -2; 
        }';
      }

      //// DESKTOP ONLY.
      if( isset($atts['right_margin']) && strlen($atts['right_margin']) > 0 ) {
        self::$element_css[] = $col_selector.'.right_margin_'. esc_attr( self::percent_unit_type_class($atts['right_margin']) ) .' {
          margin-right: '.esc_attr( self::percent_unit_type($atts['right_margin']) ). esc_attr( $override ).';
        }';
      }
      if( isset($atts['left_margin']) && strlen($atts['left_margin']) > 0 ) {
        self::$element_css[] = $col_selector.'.left_margin_'. esc_attr( self::percent_unit_type_class($atts['left_margin']) ) .' {
          margin-left: '.esc_attr( self::percent_unit_type($atts['left_margin']) ). esc_attr( $override ).';
        }';
      }
      if( isset($atts['column_element_spacing']) && 'default' !== $atts['column_element_spacing'] ) {
        self::$element_css[] = $col_selector.'.el_spacing_'. esc_attr( $atts['column_element_spacing']) .' > .vc_column-inner > .wpb_wrapper > div:not(:last-child){
          margin-bottom: '.esc_attr( $atts['column_element_spacing'] ).';
        }';
      }

      // Mask Reveal animations.

      //// BG.
      $bg_animation = isset($atts['bg_image_animation']) ? esc_attr($atts['bg_image_animation']) : 'none';
      if( $bg_animation === 'mask-reveal') {
        
        $mask_reveal_styles = self::mask_reveal_styles(
          $col_selector.'.nectar-mask-reveal-bg-%s-dir.nectar-mask-reveal-bg-%s-shape > .vc_column-inner > .column-bg-layer', 
          '',
          'bg_image_animation_mask_direction',
          'bg_image_animation_mask_shape',
          'animation_easing',
          $atts
        );

        foreach( $mask_reveal_styles as $mask_reveal_style ) {
          self::$element_css[] = $mask_reveal_style;
        }
      }

      //// Enitre column.
      $col_animation = isset($atts['animation']) ? esc_attr($atts['animation']) : 'none';
      if( $col_animation === 'mask-reveal') {
        
        $mask_reveal_styles = self::mask_reveal_styles(
          $col_selector.'.nectar-mask-reveal-%s-dir.nectar-mask-reveal-%s-shape', 
          '.vc_column-inner',
          'animation_mask_direction',
          'animation_mask_shape',
          'animation_easing',
          $atts
        );

        foreach( $mask_reveal_styles as $mask_reveal_style ) {
          self::$element_css[] = $mask_reveal_style;
        }
      }


      // Column Border.
      $column_border_params = array(
        'border_left' => 'border-left-width',
        'border_top' => 'border-top-width',
        'border_right' => 'border-right-width',
        'border_bottom' => 'border-bottom-width'
      );
    

      // Advanced Border Styling.
      if( isset($atts['border_type']) && 'advanced' === $atts['border_type'] ) {

        // Border width.
        foreach( $column_border_params as $param => $css_prop) {

          if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {

            self::$element_css[] = $col_selector . '.' . $param . '_desktop_' . esc_attr( self::percent_unit_type_class($atts[$param.'_desktop']) ) . ' > .vc_column-inner, 
            '.$col_selector . '.' . $param . '_desktop_' . esc_attr( self::percent_unit_type_class($atts[$param.'_desktop']) ) . ' > .n-sticky > .vc_column-inner {
              '. $css_prop .': '.esc_attr( self::percent_unit_type($atts[$param.'_desktop']) ).';
            }';
          }

        } // end column border param loop.

        // Border color.
        if( isset($atts['column_border_color']) && strlen($atts['column_border_color']) > 0 ) {
          $border_color = ltrim($atts['column_border_color'],'#');
          self::$element_css[] = $col_selector . '.border_color_' . esc_attr( $border_color )  . ' > .vc_column-inner, 
          '. $col_selector . '.border_color_' . esc_attr( $border_color )  . ' > .n-sticky > .vc_column-inner  {
            border-color: #'.esc_attr($border_color).';
          }';
        }

        // Border style.
        if( !isset($atts['column_border_style']) ) {
          $atts['column_border_style'] = 'solid';
        }
        if( isset($atts['column_border_style']) && strlen($atts['column_border_style']) > 0 ) {
          self::$element_css[] = $col_selector . '.border_style_' . esc_attr( $atts['column_border_style'] )  . ' > .vc_column-inner,
          '.$col_selector . '.border_style_' . esc_attr( $atts['column_border_style'] )  . ' > .n-sticky > .vc_column-inner {
            border-style: '.esc_attr($atts['column_border_style']).';
          }';
        }

      } 
      // Basic Border style.
      else if( isset($atts['column_border_width']) && 'none' !== $atts['column_border_width'] ) {
        self::$element_css[] = '.wpb_column > .vc_column-inner > .border-wrap{
          position:static;
          pointer-events:none
        }
        .wpb_column > .vc_column-inner > .border-wrap >span{
          position:absolute;
          z-index: 100;
        }
        .wpb_column[data-border-style="solid"] > .vc_column-inner > .border-wrap >span{
          border-style:solid
        }
        .wpb_column[data-border-style="dotted"] > .vc_column-inner > .border-wrap >span{
          border-style:dotted
        }
        .wpb_column[data-border-style="dashed"] > .vc_column-inner > .border-wrap >span{
          border-style:dashed
        }
        .wpb_column > .vc_column-inner > .border-wrap >.border-top,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-top:after{
          width:100%;
          top:0;
          left:0;
          border-color:inherit;
        }
        .wpb_column > .vc_column-inner > .border-wrap >.border-bottom,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-bottom:after{
          width:100%;
          bottom:0;
          left:0;
          border-color:inherit;
        }
        .wpb_column > .vc_column-inner > .border-wrap >.border-left,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-left:after{
          height:100%;
          top:0;
          left:0;
          border-color:inherit;
        }
        .wpb_column > .vc_column-inner > .border-wrap >.border-right,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-right:after{
          height:100%;
          top:0;
          right:0;
          border-color:inherit;
        }';
        
        self::$element_css[] = '.wpb_column > .vc_column-inner > .border-wrap >.border-right,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-right:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-left,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-left:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-bottom,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-bottom:after {
          border-top:none!important
        }
        
        .wpb_column > .vc_column-inner > .border-wrap >.border-left,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-left:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-bottom,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-bottom:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-top,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-top:after {
          border-right:none!important
        }
        
        .wpb_column > .vc_column-inner > .border-wrap >.border-right,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-right:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-left,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-left:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-top,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-top:after {
          border-bottom:none!important
        }
        
        .wpb_column > .vc_column-inner > .border-wrap >.border-right,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-right:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-bottom,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-bottom:after,
        .wpb_column > .vc_column-inner > .border-wrap >.border-top,
        .wpb_column[data-border-style*="double"] > .vc_column-inner > .border-wrap >.border-top:after {
          border-left:none!important
        }';
      }

      // Custom Border Radius.
      if( isset($atts['column_border_radius']) && 'custom' === $atts['column_border_radius'] ) {

        if( isset($atts['top_left_border_radius']) && strlen($atts['top_left_border_radius']) > 0 ) {
          self::$element_css[] = $col_selector.'.tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) .' > .vc_column-inner > div[class*="-wrap"],
          '.$col_selector.'.tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) .' > .vc_column-inner {
            border-top-left-radius: '.self::percent_unit_type(esc_attr( $atts['top_left_border_radius'] )).';
            overflow: hidden;
          }';
          
        }
        if( isset($atts['top_right_border_radius']) && strlen($atts['top_right_border_radius']) > 0 ) {
          self::$element_css[] = $col_selector.'.tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) .' > .vc_column-inner > div[class*="-wrap"],
          '.$col_selector.'.tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) .' > .vc_column-inner  {
            border-top-right-radius: '.self::percent_unit_type(esc_attr( $atts['top_right_border_radius'] )).';
            overflow: hidden;
          }';
        }
        if( isset($atts['bottom_left_border_radius']) && strlen($atts['bottom_left_border_radius']) > 0 ) {
          self::$element_css[] = $col_selector.'.bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) .' > .vc_column-inner > div[class*="-wrap"],
          '.$col_selector.'.bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) .' > .vc_column-inner  {
            border-bottom-left-radius: '.self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'] )).';
            overflow: hidden;
          }';
        }
        if( isset($atts['bottom_right_border_radius']) && strlen($atts['bottom_right_border_radius']) > 0 ) {
          self::$element_css[] = $col_selector.'.br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) .' > .vc_column-inner > div[class*="-wrap"],
          '.$col_selector.'.br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) .' > .vc_column-inner  {
            border-bottom-right-radius: '.self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'] )).';
            overflow: hidden;
          }';
        }
       }

       // Disable entrance animation.
      if( isset($atts['mobile_disable_entrance_animation']) && 'true' == $atts['mobile_disable_entrance_animation'] ) {
        self::$element_css[] = 
       '@media only screen and (max-width: 999px) {
          '.$col_selector.'.mobile-disable-entrance-animation, '.$col_selector.'.mobile-disable-entrance-animation:not([data-scroll-animation-mobile="true"]) > .vc_column-inner {
            transform: none!important;
            opacity: 1!important;
        }
        .nectar-mask-reveal.mobile-disable-entrance-animation,
        [data-animation="mask-reveal"].mobile-disable-entrance-animation > .vc_column-inner {
          clip-path: none!important;
        }
      }';
      }

      //// DEVICES.
      foreach( $devices as $device => $media_query ) {

        // Transform.
        $transform_vals = '';
        $transform_x    = false;
        $transform_y    = false;

        //// Translate X.
        if( isset($atts['translate_x_'.$device]) && strlen($atts['translate_x_'.$device]) > 0 ) {

          $transform_vals .= 'translateX('. esc_attr( self::percent_unit_type($atts['translate_x_'.$device]) ).') ';
          $transform_x = true;

        }
        //// Translate Y.
        if( isset($atts['translate_y_'.$device]) && strlen($atts['translate_y_'.$device]) > 0 ) {

          $transform_vals .= 'translateY('. esc_attr( self::percent_unit_type($atts['translate_y_'.$device]) ).')';
          $transform_y = true;

        }

        if( !empty($transform_vals) ) {

          // X only.
          if( false === $transform_y && false !== $transform_x ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              '.$col_selector.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .' > .vc_column-inner,
              '.$col_selector.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .' > .n-sticky > .vc_column-inner {
              -webkit-transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
              transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
            } }';

          }
          // Y only.
          else if ( false !== $transform_y && false === $transform_x ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              '.$col_selector.'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' > .vc_column-inner,
              '.$col_selector.'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' > .n-sticky > .vc_column-inner {
              -webkit-transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
              transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
            } }';

          }
          // X and Y.
          else if( false !== $transform_y && false !== $transform_x ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              '.$col_selector.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' > .vc_column-inner,
              '.$col_selector.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' > .n-sticky > .vc_column-inner {
              -webkit-transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
              transform: '.esc_attr( $transform_vals ). esc_attr( $override ).';
            } }';

          }

        } // endif not empty transform vals.


        // Margin.
        //// Top.
        if( isset($atts['top_margin_'.$device]) && strlen($atts['top_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.top_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['top_margin_'.$device]) ) .' {
            margin-top: '.esc_attr( self::percent_unit_type($atts['top_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }

        //// Right.
        if( isset($atts['right_margin_'.$device]) && strlen($atts['right_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.right_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['right_margin_'.$device]) ) .' {
            margin-right: '.esc_attr( self::percent_unit_type($atts['right_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }

        //// Left.
        if( isset($atts['left_margin_'.$device]) && strlen($atts['left_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.left_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['left_margin_'.$device]) ) .' {
            margin-left: '.esc_attr( self::percent_unit_type($atts['left_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }

        //// Bottom.
        if( isset($atts['bottom_margin_'.$device]) && strlen($atts['bottom_margin_'.$device]) > 0 ) {

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { '.$col_selector.'.bottom_margin_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['bottom_margin_'.$device]) ) .' {
            margin-bottom: '.esc_attr( self::percent_unit_type($atts['bottom_margin_'.$device]) ). esc_attr( $override ).';
          } }';

        }


        // Padding.
        if( isset($atts['column_padding_'.$device]) && strlen($atts['column_padding_'.$device]) > 0
        && $atts['column_padding_'.$device] !== 'inherit' && $atts['column_padding_'.$device] !== 'no-extra-padding' ) {

          $padding_number = preg_replace("/[^0-9\.]/", '', $atts['column_padding_'.$device]);
          $leading_zero   = ( $padding_number < 10) ? '0' : '';

          self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { 
            body .wpb_row '.$col_selector.'.padding-'.esc_attr($padding_number).'-percent_'.$device.' > .vc_column-inner, body .wpb_row '.$col_selector.'.padding-'.esc_attr($padding_number).'-percent_'.$device.' > .n-sticky > .vc_column-inner {
                padding: calc('.$media_query.' * 0.'.$leading_zero . esc_attr($padding_number).');
              } 
           }';

        }

        // Border width
        if( isset($atts['border_type']) && 'advanced' === $atts['border_type'] ) {
          foreach( $column_border_params as $param => $css_prop) {

            if( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0 ) {

              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .wpb_row '.$col_selector . '.' . $param . '_'.$device.'_' . esc_attr( self::percent_unit_type_class($atts[$param.'_'.$device]) ) . ' > .vc_column-inner ' . ' {
                '. $css_prop .': '.esc_attr( self::percent_unit_type($atts[$param.'_'.$device]) ).';
              }}';
            }

          } // End column border param loop.

        } // End using advanced border style.

        
      } // END DEVICE LOOP.


       // FULL DEVICE LOOP INCLUDING DESKTOP.
      foreach( self::$devices as $device => $media_query_size) {
        
        // Text align.
        if( isset($atts[$device.'_text_alignment']) && 'default' !== $atts[$device.'_text_alignment'] ) {

          $body_selector = ($device !== 'desktop') ? 'body ' : '';
          $html_selector = ($device === 'phone') ? 'html ' : '';

          self::$element_css[] = '@media only screen '.$media_query_size.' { 
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-left,
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-left .col {
              text-align: left!important;
            }
          
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-right,
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-right .col {
              text-align: right!important;
            }
          
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-center,
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-center .col,
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-center .vc_custom_heading,
            '. $html_selector . $body_selector.'.wpb_column.force-'.$device.'-text-align-center .nectar-cta {
              text-align: center!important;
            }
          
            .wpb_column.force-'.$device.'-text-align-center .img-with-aniamtion-wrap img {
              display: inline-block;
            }
          }';
          
        }
      } // END FULL DEVICE LOOP.



      // Border Radius.
      if( isset($atts['column_border_radius']) && !empty($atts['column_border_radius']) && 
      'none' !== $atts['column_border_radius'] && 
      'custom' !== $atts['column_border_radius'] ) {
        self::$element_css[] = '
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"],
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-link,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-bg-overlay-wrap,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-image-bg-wrap[data-bg-animation="zoom-out-reveal"],
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-image-bg-wrap .column-image-bg,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .vc_column-inner > .column-image-bg-wrap[data-n-parallax-bg="true"],
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .n-sticky > .vc_column-inner,
        .wpb_column[data-border-radius="'.esc_attr($atts['column_border_radius']).'"] > .n-sticky > .vc_column-inner > .column-bg-overlay-wrap {
          border-radius: '.esc_attr($atts['column_border_radius']).';
        }';
      }

      // Column BG Position
      if( isset($atts['background_image_position']) && !empty($atts['background_image_position']) ) {
        self::$element_css[] = '.column-image-bg-wrap[data-bg-pos="'.esc_attr($atts['background_image_position']).'"] .column-image-bg,
        .container-wrap .main-content .column-image-bg-wrap[data-bg-pos="'.esc_attr($atts['background_image_position']).'"] .column-image-bg {
          background-position: '.esc_attr($atts['background_image_position']).';
        }';
      }

       // Custom text color.
       if( isset($atts['font_color']) && !empty($atts['font_color']) ) {
        self::$element_css[] = '.wpb_column[data-cfc="true"] h1,
        .wpb_column[data-cfc="true"] h2,
        .wpb_column[data-cfc="true"] h3,
        .wpb_column[data-cfc="true"] h4,
        .wpb_column[data-cfc="true"] h5,
        .wpb_column[data-cfc="true"] h6,
        .wpb_column[data-cfc="true"] p{
          color:inherit
        }';
      }

      // One sixth specific.
      if( isset($atts['width']) && $atts['width'] === '1/6' ) {

        self::$element_css[] = '@media only screen and (max-width: 999px) {
          body .vc_row-fluid:not(.full-width-content) > .span_12 .vc_col-sm-2:not(:last-child):not([class*="vc_col-xs-"]) {
            margin-bottom: 25px;
          }
        }

        @media only screen and (min-width : 691px) and (max-width : 999px) {
          body .vc_col-sm-2 {
            width: 31.2%;
            margin-left: 3.1%;
          }
        
          body .full-width-content .vc_col-sm-2 {
            width: 33.3%;
            margin-left: 0%;
          }

          .vc_row-fluid .vc_col-sm-2[class*="vc_col-sm-"]:first-child:not([class*="offset"]),
          .vc_row-fluid .vc_col-sm-2[class*="vc_col-sm-"]:nth-child(3n+4):not([class*="offset"]) {
            margin-left: 0;
          }

        }
        @media only screen and (max-width : 690px) {

          body .vc_row-fluid .vc_col-sm-2:not([class*="vc_col-xs"]),
          body .vc_row-fluid.full-width-content .vc_col-sm-2:not([class*="vc_col-xs"]) {
            width: 50%;
          }

          .vc_row-fluid .vc_col-sm-2[class*="vc_col-sm-"]:first-child:not([class*="offset"]),
          .vc_row-fluid .vc_col-sm-2[class*="vc_col-sm-"]:nth-child(2n+3):not([class*="offset"]) {
            margin-left: 0;
          }
        }
        ';
        
      }
      
      // PARENT COLUMN SPECIFIC.
      if ( false !== strpos($shortcode[0],'[vc_column ') ) {


         // positioning.
         if( isset($atts['column_position']) && 
         strlen($atts['column_position']) > 0 && 
         in_array($atts['column_position'], array('relative', 'static')) ) {
             self::$element_css[] = $col_selector.'.column_position_'. esc_attr($atts['column_position']) . ', ' . $col_selector.'.column_position_'. esc_attr($atts['column_position']) .' > .vc_column-inner {
           position: '.esc_attr($atts['column_position']).';
         }';
         }
        //// Sticky.
        if( isset($atts['sticky_content']) && 'true' === $atts['sticky_content'] &&
            isset($atts['sticky_content_functionality']) && 'css' === $atts['sticky_content_functionality'] ) {
              self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            html body {
              overflow: visible;
            }
            
            .vc_row:not(.vc_row-o-equal-height) .nectar-sticky-column-css.vc_column_container > .n-sticky {
              height: 100%;
            }

            .nectar-sticky-column-css.vc_column_container > .n-sticky {
              width: 100%;
            }

            .nectar-sticky-column-css.vc_column_container > .n-sticky > .vc_column-inner {
              position: sticky;
              top: var(--nectar-sticky-top-distance);
            }
          }';
          
          self::$element_css[] = '@media only screen and (max-width: 999px) {
            .nectar-sticky-column-css.vc_column_container > .n-sticky > .vc_column-inner {
              position: relative;
            }
          }';

        }
        //// Max width.

        ////// Desktop.
        if( isset($atts['max_width_desktop']) && strlen($atts['max_width_desktop']) > 0 ) {
          self::$element_css[] = '.wpb_column.max_width_desktop_'. esc_attr( self::percent_unit_type_class($atts['max_width_desktop']) ) .' {
            max-width: '.esc_attr( self::percent_unit_type($atts['max_width_desktop']) ).';
          }';
        }

        ////// Devices.
        foreach( $devices as $device => $media_query ) {

          if( isset($atts['max_width_'.$device]) && strlen($atts['max_width_'.$device]) > 0 ) {

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body .wpb_column.max_width_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['max_width_'.$device]) ) .' {
              max-width: '.esc_attr( self::percent_unit_type($atts['max_width_'.$device]) ).';
            } }';

          }

        }

      } // END PARENT COLUMN SPECIFIC.


        // Column element direction
        $gap_css = '';
        if( isset($atts['column_element_spacing']) && 'default' !== $atts['column_element_spacing'] ) {
          $gap_css = '.column_element_direction_desktop_horizontal.el_spacing_'. esc_attr( $atts['column_element_spacing']) .' > .vc_column-inner > .wpb_wrapper{
              gap: '.esc_attr( $atts['column_element_spacing'] ).';
            }'; 
        }

        //// Desktop
        if( isset($atts['column_element_direction_desktop']) && $atts['column_element_direction_desktop'] === 'horizontal' ) {

          if( self::$using_front_end_editor ) {
            self::$element_css[] = '@media only screen and (min-width: 1000px) {
              #ajax-content-wrap .column_element_direction_desktop_horizontal > .vc_column-inner > .wpb_wrapper > .vc_element > * {
                margin-bottom: 0;
              }
            }';
          } 

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .column_element_direction_desktop_horizontal > .vc_column-inner > .wpb_wrapper {
              display: flex; 
              align-items: center;
            }
            #ajax-content-wrap .column_element_direction_desktop_horizontal > .vc_column-inner > .wpb_wrapper > * {
              margin-bottom: 0;
            }
          }
          .column_element_direction_desktop_horizontal.force-desktop-text-align-right > .vc_column-inner > .wpb_wrapper {
            justify-content: flex-end;
          }
          .column_element_direction_desktop_horizontal.force-desktop-text-align-center > .vc_column-inner > .wpb_wrapper {
            justify-content: center;
          }

          @media only screen and (max-width: 999px) {
            .column_element_direction_desktop_horizontal.force-tablet-text-align-right > .vc_column-inner > .wpb_wrapper {
              justify-content: flex-end;
            }
            .column_element_direction_desktop_horizontal.force-tablet-text-align-center > .vc_column-inner > .wpb_wrapper {
              justify-content: center;
            }
          }

          @media only screen and (max-width: 690px) {
            .column_element_direction_desktop_horizontal.force-phone-text-align-right > .vc_column-inner > .wpb_wrapper {
              justify-content: flex-end;
            }
            .column_element_direction_desktop_horizontal.force-phone-text-align-center > .vc_column-inner > .wpb_wrapper {
              justify-content: center;
            }
          }
          ';

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            '.$gap_css.'
          }';
          
        }

        //// Tablet
        if( isset($atts['column_element_direction_tablet']) && $atts['column_element_direction_tablet'] === 'horizontal' ) {
          self::$element_css[] = '@media only screen and (min-width: 691px) and (max-width: 999px) {
            .column_element_direction_tablet_horizontal > .vc_column-inner > .wpb_wrapper {
              display: flex; 
              align-items: center;
            }
            #ajax-content-wrap .column_element_direction_tablet_horizontal > .vc_column-inner > .wpb_wrapper > * {
              margin-bottom: 0;
            }
          }';

          self::$element_css[] = '@media only screen and (min-width: 691px) and (max-width: 999px) {
            '.$gap_css.'
          }';

        }

        //// Phone
        if( isset($atts['column_element_direction_phone']) && $atts['column_element_direction_phone'] === 'horizontal' ) {

          self::$element_css[] = '@media only screen and (max-width: 690px) {
            .column_element_direction_phone_horizontal > .vc_column-inner > .wpb_wrapper {
              display: flex; 
              align-items: center;
            }
            #ajax-content-wrap .column_element_direction_phone_horizontal > .vc_column-inner > .wpb_wrapper > * {
              margin-bottom: 0;
            }
          }';

          self::$element_css[] = '@media only screen and (max-width: 690px) {
            '.$gap_css.'
          }';

        }

        if( isset($atts['column_element_alignment']) && $atts['column_element_alignment'] !== 'center' ) {
          self::$element_css[] = '#ajax-content-wrap .column_el_align_'.esc_attr($atts['column_element_alignment']).' > .vc_column-inner > .wpb_wrapper {
            align-items: '.esc_attr($atts['column_element_alignment']).';
            }
          ';
        }
    

    } // End Column Element.

    // Badge Element.
    else if ( false !== strpos($shortcode[0],'[nectar_badge ') ) {
      $atts = shortcode_parse_atts($shortcode_inner);

      $badge_style = isset($atts['badge_style']) ? $atts['badge_style'] : 'default';

      // Core.
      $badge_padding_arr = array(
        'none' => '0',
        'small' => '0.5em 1em',
        'medium' => '0.65em 1.25em',
        'large' => '0.8em 1.6em',
      );
      $badge_padding = ( isset($atts['padding']) && in_array($atts['padding'], array('small','medium','large','none')) ) ? esc_attr($atts['padding']) : 'small';

      $badge_border_radius = ( isset($atts['border_radius']) && !empty($atts['border_radius']) ) ? esc_attr($atts['border_radius']) : '0px';

      self::$element_css[] = '.nectar-badge__inner {
        display: inline-block;
        line-height: 1;
        color: #fff;
        border-radius: '.esc_attr($badge_border_radius).';
      }
      
      .nectar-badge.position_desktop_absolute {
        white-space: nowrap;
      }';

      self::$element_css[] = '.padding-amount-'.$badge_padding.' .nectar-badge__inner {
        padding: '.esc_attr($badge_padding_arr[$badge_padding]).';
      }';

      if( 'line' === $badge_style ) {
        self::$element_css[] = '.badge-style-line .nectar-badge__inner {
            display: flex;
            padding: 0;
            align-items: center;
        }
        .badge-style-line .nectar-badge__inner:before {
          content: "";
          display: block;
          width: 20px;
          margin-right: 10px;
          height: 1px;
          background-color: currentColor;
        }';

        if( isset($atts['line_width']) && !empty($atts['line_width']) ) {
          self::$element_css[] = '.badge-style-line.line-width-'.self::percent_unit_type_class(esc_attr($atts['line_width'])).' .nectar-badge__inner:before {
            width: '.self::percent_unit_type($atts['line_width']).';
          }';
        }

      }

      // Custom coloring.
      if( true === self::custom_color_bool('bg_color', $atts) ) {
       
        $bg_color = $atts['bg_color_custom'];
        self::$element_css[] = '.nectar-badge[data-bg-color-custom="'.esc_attr($bg_color).'"] .nectar-badge__inner {
          background-color: '.esc_attr($bg_color).';
        }';

      }

      if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
        $badge_text_color = ltrim($atts['text_color'],'#');
        self::$element_css[] = '.nectar-badge.text-color-'.esc_attr($badge_text_color).' .nectar-badge__inner {
          color: #'.esc_attr($badge_text_color).';
        }';
      }

      // Positioning.
      $positions = self::positioning_group_styles('nectar-badge',$atts);
      foreach( $positions as $position ) {
        self::$element_css[] = $position;
      }


    }

    else if( false !== strpos($shortcode[0],'[nectar_price_typography ') ) {
      $atts = shortcode_parse_atts($shortcode_inner);

      // Core.
      self::$element_css[] = '#ajax-content-wrap .nectar-price-typography {
        line-height: 1; 
      }
      .nectar-price-typography .before-text {
       vertical-align: top; 
       margin-right: 0.2em;
      }
      .nectar-price-typography .after-text {
        letter-spacing: 0;
        margin-left: 0.2em;
       }';

      // Custom font size.
      self::$element_css[] = self::font_size_group_styles('nectar-price-typography', false, false, '', $atts);

    }


    else if( false !== strpos($shortcode[0],'[nectar_responsive_text') ) {
      $atts = shortcode_parse_atts($shortcode_inner);
      // Core.
      self::$element_css[] = '
      #ajax-content-wrap .nectar-responsive-text * {
        margin-bottom: 0;
        color: inherit;
      }
      #ajax-content-wrap .nectar-responsive-text[class*="font_size"] * {
        font-size: inherit; 
        line-height: inherit;
      }
      .nectar-responsive-text.nectar-link-underline-effect a {
        text-decoration: none;
      }
      ';

      // Text Direction.
      $text_direction = self::text_direction_styles($atts, '.nectar-responsive-text');
      if ( !empty($text_direction) ) {
        self::$element_css[] = $text_direction;
      }

      // Custom font size.
      self::$element_css[] = self::font_size_group_styles('nectar-responsive-text', false, false, '', $atts);

    }

    else if( false !== strpos($shortcode[0],'[nectar_animated_shape ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

      // Core.
      self::$element_css[] = '.nectar-animated-shape {
        line-height: 0;
      }';

       // Positioning.
       $positions = self::positioning_group_styles('nectar-animated-shape',$atts);
       foreach( $positions as $position ) {
         self::$element_css[] = $position;
       }
 
 
       // width and height
       self::$element_css[] = self::width_height_group_styles('nectar-animated-shape', '', $atts);

       // Animations.
       $animation = isset($atts['animation']) ? $atts['animation'] : 'none';

      // easing.
      $cubic_bezier = '0.25, 1, 0.33, 1';


      // grab from global settings.
      global $nectar_options;
      $cubic_beziers = nectar_cubic_bezier_easings();
      $animation_easing = isset($nectar_options['column_animation_easing']) && !empty($nectar_options['column_animation_easing']) ? $nectar_options['column_animation_easing'] : 'easeOutCubic';

      // custom easing.
      if( isset($atts['animation_easing']) && !empty($atts['animation_easing']) && 'default' !== $atts['animation_easing'] ) {
        $animation_easing = $atts['animation_easing'];
      } 

      if( isset($cubic_beziers[$animation_easing]) ) {
        $cubic_bezier = $cubic_beziers[$animation_easing];
      }


      if( 'none' !== $animation ) { 

        // grow in
        if( 'grow-in' === $animation ) {
          self::$element_css[] = '.nectar-animated-shape__inner_wrap[data-animation="'.$animation.'"].nectar-waypoint-el {
            transform: scale(0);
          }
          .nectar-animated-shape__inner_wrap[data-animation="'.$animation.'"].nectar-waypoint-el.animated-in {
            transform: scale(1);
          }';
        }
       
        // easing
        self::$element_css[] = '.nectar-animated-shape__inner_wrap[data-animation="'.$animation.'"][data-easing="'.$animation_easing.'"] {
          transition: transform 1.3s cubic-bezier('.$cubic_bezier.');
        }';

      }
      
      
      
    }

    
    else if( false !== strpos($shortcode[0],'[fancy-ul ') ) {
      
      $atts = shortcode_parse_atts($shortcode_inner);

      // Custom font size.
      $custom_font_sizing = self::font_size_group_styles('nectar-fancy-ul', false, false, '', $atts);

      if ( !empty($custom_font_sizing) ) {
        self::$element_css[] = $custom_font_sizing;
        self::$element_css[] = '.nectar-fancy-ul {
          line-height: 1.3;  
        }
        #ajax-content-wrap .nectar-fancy-ul ul li {
          line-height: inherit;
        }
        .nectar-fancy-ul ul li .icon-default-style[class^="icon-"] {
          font-size: 1.1em;
        }';
      }

      // Link style.
      $link_style = isset($atts['link_style']) ? $atts['link_style'] : 'default';

      if ( $link_style === 'underline' ) {
        self::$element_css[] = '
        .nectar-fancy-ul.link_style_'. esc_attr($atts['link_style']) .' a {
          text-decoration: none;
          color: inherit;
          transition: opacity 0.25s ease;
        }';
      }

      if ( $link_style === 'opacity' ) {
        self::$element_css[] = '
        .nectar-fancy-ul.link_style_'. esc_attr($atts['link_style']) .' a {
          opacity: 0.6;
          text-decoration: none;
          color: inherit;
          transition: opacity 0.25s ease;
        }
        .nectar-fancy-ul.link_style_'. esc_attr($atts['link_style']) .' a:hover {
          opacity: 1;
        }
        ';
      }
     

    }


    else if( false !== strpos($shortcode[0],'[nectar_lottie ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);
      
      // core.
      self::$element_css[] = '.nectar-lottie-wrap {
        line-height: 0; 
      }
      .nectar-lottie-wrap .nectar-lottie {
        width: 100%;
        height: 100%;
      }
      .wpb_wrapper.tabbed {
        position: relative; 
      }';

      // Positioning.
      $positions = self::positioning_group_styles('nectar-lottie-wrap',$atts);
      foreach( $positions as $position ) {
        self::$element_css[] = $position;
      }


      // width and height
      self::$element_css[] = self::width_height_group_styles('nectar-lottie-wrap', '', $atts);


      // alignment.
      $alignment = (isset($atts['alignment']) && !empty($atts['alignment'])) ? esc_attr($atts['alignment']) : 'center';
      
      if( 'center' === $alignment ) {
        self::$element_css[] = '.nectar-lottie-wrap.alignment_center {
          display: flex;
          margin: 0 auto;
          justify-content: center;
        }';
      } else if( 'left' === $alignment ) {
        self::$element_css[] = '.nectar-lottie-wrap.alignment_left {
          display: flex;
          justify-content: flex-start;
        }';
      } else if( 'right' === $alignment ) {
        self::$element_css[] = '.nectar-lottie-wrap.alignment_right {
          display: flex;
          justify-content: flex-end;
          margin-left: auto;
        }';
      }

      // Mobile func.
      if (isset($atts['mobile_func']) && 'default' !== $atts['mobile_func'] ) {
        self::$element_css[] = '@media only screen and (max-width: 999px) {
          .nectar-lottie-wrap.mobile_remove {
            display: none;
          }
        }';
      }

    }

    // Icon Element.
    else if ( false !== strpos($shortcode[0],'[nectar_icon ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);
      $icon_style = isset($atts['icon_style']) ? esc_attr($atts['icon_style']) : 'default';

      // Core.
      //self::$element_css[] = '';

      //// default.
      if( 'default' === $icon_style ) {
        self::$element_css[] = '
          .nectar_icon_wrap[data-style*="default"][data-color*="extra-color-gradient"] .nectar_icon i {
            border-radius: 0!important;
            text-align: center;
          }
          .nectar_icon_wrap[data-style*="default"][data-color*="extra-color-gradient"] .nectar_icon i:before {
            vertical-align: top;
          }
          .nectar_icon_wrap[data-style*="default"][data-color*="extra-color-gradient"] .nectar_icon i[class*="fa-"],
          .nectar_icon_wrap[data-style*="default"][data-color*="extra-color-gradient"] .nectar_icon i[class^="icon-"] {
            vertical-align: baseline;
          }
        ';
      }

      //// border animation
      if( 'border-animation' === $icon_style ) {
        self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"] .nectar_icon:not(.no-grad):hover i {
            color:#fff!important;
        }
        .span_12.light .nectar_icon_wrap[data-style="border-animation"] .nectar_icon{
          border-color:rgba(255,255,255,0.15)
        }
        .nectar_icon_wrap[data-style="border-animation"] .nectar_icon {
          line-height:0;
          border:2px solid rgba(0,0,0,0.065);
          text-align: center;
          border-radius:150px;
          position:relative;
          transition:background-color .45s cubic-bezier(0.25,1,0.33,1),border-color .45s cubic-bezier(0.25,1,0.33,1)
        }
        .nectar_icon_wrap[data-style*="border"] .nectar_icon i {
          display: inline-block;
          vertical-align: middle;
          max-width: none;
          top: 0;
        }
        .nectar_icon_wrap[data-style="border-animation"] .nectar_icon i{
          transition:color .45s cubic-bezier(0.25,1,0.33,1)
        }
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"]:hover .nectar_icon{
          border-color:transparent
        }
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"]:hover:before,
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"]:hover .nectar_icon:before{
          opacity:1
        }
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"]:before,
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"] .nectar_icon:before{
          position:absolute;
          z-index:-1;
          content:" ";
          display:block;
          top:0;
          left:0;
          width:100%;
          height:100%;
          opacity:0;
          border-radius:100px;
          transition:opacity .45s cubic-bezier(0.25,1,0.33,1)
        }
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"] .nectar_icon:before{
          opacity:1
        }
        .nectar_icon_wrap[data-style="border-animation"][data-color*="extra-color-gradient"] .nectar_icon:before{
          background-color:#f6f6f6
        }';
      }

      //// border basic
      if( 'border-basic' === $icon_style ) {
        self::$element_css[] = '.span_12.light .nectar_icon_wrap[data-style="border-basic"] .nectar_icon{
          border-color:rgba(255,255,255,0.15)
        }
        .nectar_icon_wrap[data-style="border-basic"] .nectar_icon {
          line-height:0;
          border:2px solid rgba(0,0,0,0.065);
          text-align: center;
          border-radius:150px;
          position:relative;
          transition:background-color .45s cubic-bezier(0.25,1,0.33,1),border-color .45s cubic-bezier(0.25,1,0.33,1)
        }
        .nectar_icon_wrap[data-style*="border"] .nectar_icon i {
          display: inline-block;
          vertical-align: middle;
          max-width: none;
          top: 0;
        }
        .nectar_icon_wrap[data-style="border-basic"] .nectar_icon i{
          text-align:center
        }';
      }
      //// soft bg
      if( 'soft-bg' === $icon_style ) {

      
        self::$element_css[] = '
        .nectar_icon_wrap[data-style="soft-bg"][data-color="black"] .nectar_icon:before,
        .nectar_icon_wrap[data-style="soft-bg"][data-color="grey"] .nectar_icon:before {
          background-color: #888;
        }
          .nectar_icon_wrap[data-style="soft-bg"] .nectar_icon{
            line-height:0;
            border:2px solid rgba(0,0,0,0.065);
            text-align: center;
            border-radius:150px;
            position:relative;
            transition:background-color .45s cubic-bezier(0.25,1,0.33,1),border-color .45s cubic-bezier(0.25,1,0.33,1)
          }
          .nectar_icon_wrap[data-style="soft-bg"] .nectar_icon {
            border: 0;
          }
          .nectar_icon_wrap[data-style="soft-bg"] .nectar_icon:before {
          height: 100%;
          width: 100%;
          top: 0;
          left: 0;
          content: "";
          position: absolute;
          display: block;
          border-radius:100px;
          z-index: -1;
          opacity: 0.11;
        }
 
        .nectar_icon_wrap[data-style="soft-bg"] .nectar_icon i {
          display: inline-block;
          vertical-align: middle;
          max-width: none;
          top: 0;
        }
        ';
      }

      //// shadow bg
      if( 'shadow-bg' === $icon_style ) {
        self::$element_css[] = '
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon {
          line-height:0;
          border:2px solid rgba(0,0,0,0.065);
          text-align: center;
          border-radius:150px;
          position:relative;
          transition:background-color .45s cubic-bezier(0.25,1,0.33,1),border-color .45s cubic-bezier(0.25,1,0.33,1)
        }
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon {
          border: 0;
        }
       
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon:before,
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon:after {
          height: 100%;
          width: 100%;
          top: 0;
          left: 0;
          content: "";
          position: absolute;
          display: block;
          border-radius:100px;
          z-index: -1;
          opacity: 1;
        }
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon i {
          display: inline-block;
          vertical-align: middle;
          max-width: none;
          top: 0;
        }
        .nectar_icon_wrap[data-style="shadow-bg"][data-color="white"] .nectar_icon i {
          color: #000!important;
        }
        
        .nectar_icon_wrap[data-style="shadow-bg"][data-color="white"] .nectar_icon svg path {
          fill: #000!important;
        }
        
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon:before {
          box-shadow: 0 15px 28px #000;
          opacity: 0.1;
        }
        
        .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon:after {
          background-color: #fff;
        }
        .nectar_icon_wrap[data-style="shadow-bg"]:not([data-color="white"]) .nectar_icon i {
          color: #fff!important;
        }
        
        ';
      }


       // Positioning.
       $positions = self::positioning_group_styles('nectar_icon_wrap',$atts);
       foreach( $positions as $position ) {
        self::$element_css[] = $position;
      }


      // Custom coloring.
      if( true === self::custom_color_bool('icon_color', $atts) ) {

        if( isset($atts['icon_style']) && !empty($atts['icon_style']) ) {

          $icon_color = ltrim($atts['icon_color_custom'],'#');

            // Default style.
            if( 'default' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="default"] .icon_color_custom_'.esc_attr($icon_color).' i {
                color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';

              self::$element_css[] = '.nectar_icon_wrap[data-style="default"] .icon_color_custom_'.esc_attr($icon_color).' .im-icon-wrap path {
                fill: #'.esc_attr($icon_color).';
              }';
            }

            // Border Basic style.
            if( 'border-basic' === $atts['icon_style'] || 'border-animation' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap .icon_color_custom_'.esc_attr($icon_color).' i {
                color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="border-basic"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).',
              .nectar_icon_wrap[data-style="border-animation"]:not([data-draw="true"]) .nectar_icon.icon_color_custom_'.esc_attr($icon_color).',
              .nectar_icon_wrap[data-style="border-animation"][data-draw="true"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':hover {
                border-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';

              self::$element_css[] = '.nectar_icon_wrap[data-style*="border"] .icon_color_custom_'.esc_attr($icon_color).' .im-icon-wrap path {
                fill: #'.esc_attr($icon_color).';
              }';
            }

            // Border animation style.
            if( 'border-animation' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"]:not([data-draw="true"]) .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':hover {
                background-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';

              self::$element_css[] = '.nectar_icon_wrap[data-style=border-animation]:not([data-color*="extra-color-gradient"]) .nectar_icon:not(.no-grad):hover svg path {
                fill: #fff;
              }';

              
            }

            // Shadow BG style.
            if( 'shadow-bg' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).',
              .nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':after {
                background-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':before {
                box-shadow: 0px 15px 28px #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).' .svg-icon-holder svg path {
                stroke: #fff!important;
              }';

              self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"]:not([data-color=white]) .nectar_icon .im-icon-wrap path {
                fill: #fff;
              }';
            }

            // Soft BG style.
            if( 'soft-bg' === $atts['icon_style'] ) {
              self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).' i {
                color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';
              self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"] .nectar_icon.icon_color_custom_'.esc_attr($icon_color).':before {
                background-color: #'.esc_attr($icon_color). esc_attr( $override ).';
              }';

              self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"] .icon_color_custom_'.esc_attr($icon_color).' .im-icon-wrap path {
                fill: #'.esc_attr($icon_color).';
              }';
            }

            // SVG.
            self::$element_css[] = '.nectar_icon_wrap:not([data-style="soft-bg"]):not([data-style="shadow-bg"]) .nectar_icon.icon_color_custom_'.esc_attr($icon_color).' .svg-icon-holder[data-color] svg path {
              stroke: #'.esc_attr($icon_color). esc_attr( $override ).';
            }';


          }

        } // Endif using custom coloring.

        // Color scheme coloring.
        else {

          $color = self::locate_color(strtolower($atts['icon_color']));

          if( isset($atts['icon_style']) && !empty($atts['icon_style']) ) {

            // Gradient Coloring.
            if( $color && isset($color['gradient']) ) {

              // Core gradient.
                self::$element_css[] = '
                
              .nectar_icon_wrap[data-color="extra-color-gradient-1"] .nectar_icon i,
              .nectar_icon_wrap[data-color="extra-color-gradient-2"] .nectar_icon i {
                display: inline-block;
              }
                .nectar_icon_wrap[data-color="extra-color-gradient-1"] .nectar_icon.no-grad i,
              .nectar_icon_wrap[data-color="extra-color-gradient-2"] .nectar_icon.no-grad i{
                background-color:transparent!important;
                background:none!important
              }
              .nectar_icon_wrap[data-color="extra-color-gradient-1"] .nectar_icon.no-grad i,
              .nectar_icon_wrap[data-color="extra-color-gradient-2"] .nectar_icon.no-grad i{
                -webkit-text-fill-color:initial
              }';

              // Shadow bg
              if( 'shadow-bg' === $atts['icon_style'] ) {

                self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:after {
                  background: '.esc_attr($color['gradient']['to']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }
                .nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  box-shadow: 0px 15px 28px '. esc_attr($color['gradient']['from']) .'; opacity: 0.3;
                }';
                self::$element_css[] = '.nectar_icon_wrap[data-style=shadow-bg]:not([data-color=white]) .nectar_icon .im-icon-wrap path {
                  fill: #fff;
                }';
              }
              // Soft bg
              else if( 'soft-bg' === $atts['icon_style'] ) {

                self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  background: '.esc_attr($color['gradient']['to']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
              }
              // Border Animation
              else if( 'border-animation' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"]:before {
                  background: '.esc_attr($color['gradient']['to']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
              }

            } // end gradient coloring.

            // Regular Coloring.
            else if( $color && isset($color['color']) ) {

              // Shadow Bg.
              if( 'shadow-bg' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:after {
                  background-color: '.esc_attr($color['color']).';
                }
                .nectar_icon_wrap[data-style="shadow-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  box-shadow: 0px 15px 28px '. esc_attr($color['color']) .'; opacity: 0.3;
                }';
                self::$element_css[] = '.nectar_icon_wrap[data-style=shadow-bg]:not([data-color=white]) .nectar_icon .im-icon-wrap path {
                  fill: #fff;
                }';
              }
              // Soft Bg.
              else if( 'soft-bg' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="soft-bg"][data-color="'.esc_attr($color['name']).'"] .nectar_icon:before {
                  background-color: '.esc_attr($color['color']).';
                }';
              }
              // Border Animation.
              else if( 'border-animation' === $atts['icon_style'] ) {
                self::$element_css[] = '.nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"]:not([data-draw="true"]) .nectar_icon:hover {
                  background-color: '.esc_attr($color['color']).';
                }
                .nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"]:not([data-draw="true"]) .nectar_icon,
                .nectar_icon_wrap[data-style="border-animation"][data-color="'.esc_attr($color['name']).'"][data-draw="true"]:hover .nectar_icon {
                  border-color: '.esc_attr($color['color']).';
                }';
                self::$element_css[] = '.nectar_icon_wrap[data-style=border-animation]:not([data-color*="extra-color-gradient"]) .nectar_icon:not(.no-grad):hover svg path {
                  fill: #fff;
                }';
              }
              // Border Basic.
              else if( 'border-basic' === $atts['icon_style'] ) {

                self::$element_css[] = '.nectar_icon_wrap[data-style="border-basic"][data-color="'.esc_attr($color['name']).'"] .nectar_icon {
                   border-color: '.esc_attr($color['color']).';
                }';

              }

            } // end regular coloring.

          } // icon style is set.

        }

        if( isset($atts['pointer_events']) && 'none' === $atts['pointer_events'] ) {
          self::$element_css[] = '.no-pointer-events {
            pointer-events: none;
          }';
         }

        // Icon Padding.
        if( isset($atts['icon_padding']) && '0px' !== $atts['icon_padding'] ) {
          self::$element_css[] = '.nectar_icon_wrap[data-padding="'.esc_attr($atts['icon_padding']).'"] .nectar_icon {
            padding: '.esc_attr($atts['icon_padding']).';
          }';
        }

        // Border Thickness.
        if( isset($atts['icon_border_thickness']) && !empty($atts['icon_border_thickness']) ) {
          self::$element_css[] = '.nectar_icon_wrap[data-border-thickness="'.esc_attr($atts['icon_border_thickness']).'"] .nectar_icon {
            border-width: '.esc_attr($atts['icon_border_thickness']).';
          }';
        }

      } // End Icon Element.


      // Nectar CTA Element.
      else if ( false !== strpos($shortcode[0],'[nectar_cta ') ) {

          $atts = shortcode_parse_atts($shortcode_inner);

          $button_style = isset($atts['btn_style']) ? $atts['btn_style'] : 'default';

          // Material style.
          if( 'material' === $button_style ) {
            self::$element_css[] = '
            .nectar-cta[data-style="material"] .link_wrap .circle {
              background-color: #000;
            }
            .nectar-cta[data-style="material"] .link_wrap .circle {
              border: none;
              z-index: -1;
            }
            .nectar-cta[data-style="material"] .link_wrap .link_text:before {
              box-shadow: 0 9px 20px;
              opacity: 0.29;
              border: none;
              z-index: -1;
            }
            .nectar-cta[data-style="material"] .link_wrap .link_text:after {
              background-color: inherit;
              border: none;
            }
            .nectar-cta[data-style="material"] .link_wrap .link_text {
              padding-right: 38px;
            }
            .nectar-cta[data-style="material"] .link_wrap .arrow {
              overflow: hidden;
              display: inline-block;
              position: absolute;
              right: 0;
              top: 50%;
              margin-top: -14px;
              height: 25px;
              line-height: 28px;
              width: 24px;
              border-radius: 50px;
              transition:all 0.33s cubic-bezier(0.12,0.75,0.4,1);
            }
            .nectar-cta[data-style="material"] .link_wrap .arrow:before,
            .nectar-cta[data-style="material"] .link_wrap .arrow:after {
              margin-top: -14px;
              height: 28px;
              line-height: 28px;
              width: 25px;
              padding-left: 1px;
              box-sizing: border-box;
            }
            .nectar-cta[data-style="material"] .link_wrap .arrow:after {
              transform: translateX(-100%);
            }
            .nectar-cta[data-style="material"] .link_wrap .link_text:before,
            .nectar-cta[data-style="material"] .link_wrap .circle {
              height: 27px;
              width: 27px;
              right: -1px;
              margin-top: -14px;
            }
            .nectar-cta[data-style="material"] .link_wrap .circle {
              transform: scale(0.85);
            }
            .nectar-cta[data-style="material"] .link_wrap .link_text:before {
              transform: scale(0.84);
            }
            .nectar-cta[data-style="material"] .link_wrap:hover .circle,
            .nectar-cta[data-style="material"] .link_wrap:hover .link_text:before {
              transform: scale(1);
            }
            .nectar-cta[data-style="material"] .arrow,
            .nectar-cta[data-style="material"] .link_wrap .arrow:before,
            .nectar-cta[data-style="material"] .link_wrap .arrow:after {
              color: #fff;
            }';
          }

          // Next section
          if( 'next-section' === $button_style ) {
            self::$element_css[] = '
            .nectar-next-section-wrap.mouse-wheel .nectar-next-section,
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section,
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section i {
              color:#fff!important;
            }

            .nectar-next-section-wrap.mouse-wheel .nectar-next-section,
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section {
              display:inline-block;
              width:49px;
              height:49px;
              border:2px solid #fff;
              text-align:center;
              line-height:50px;
              border-radius:100px;
              font-size:25px;
              transition:opacity 0.4s ease;
              overflow:hidden;
              margin-left:0;
              left:0;
              bottom:16px;
              opacity:0.6
            }
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section i{
              transition:transform 0.2s ease;
              display:block;
              width:49px;
              height:48px;
              line-height:48px;
              font-size:20px
            }
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section:hover {
              opacity:1
            }
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section i {
              -webkit-transform:translate(0,-48px);
              transform:translate(0,-48px)
            }
            .nectar-next-section-wrap.down-arrow-bordered .nectar-next-section:hover i {
              transform:translate(0,0px)!important
            }

            .nectar-next-section-wrap.bounce a:before {
              border-radius: 100px;
              background-color: #000;
              position: absolute;
              top: -10%;
              left: -10%;
              width: 120%;
              height: 120%;
              display: block;
              content: " ";
              transition: all 0.45s cubic-bezier(.15,0.2,.1,1);
              transform: scale(0.8);
            }
            .nectar-next-section-wrap.bounce a:hover:before {
              transform: scale(1);
            }
            .nectar-next-section-wrap.bounce a {
              position: relative;
              height: 50px;
              width: 50px;
              line-height: 50px;
              text-align: center;
              vertical-align: middle;
              display: inline-block;
            }
            .nectar-next-section-wrap.bounce:not([data-animation="none"]) a {
              animation: down_arrow_bounce 2.3s infinite;
            }
            .nectar-next-section-wrap.bounce i {
              font-size: 24px;
              width: 24px;
              height: 24px;
              line-height: 24px;
              color: #fff;
              top: 0;
              display: inline-block;
              background-color: transparent;
            }
            
            .nectar-next-section-wrap.bounce[data-shad="add_shadow"] a,
            .nectar-next-section-wrap.down-arrow-bordered[data-shad="add_shadow"] a {
               box-shadow: 0px 13px 35px rgba(0,0,0,0.15);
            }
            
            .nectar-next-section-wrap.bounce i.dark-arrow {
              color: #000;
            }
            
            .nectar-next-section-wrap.minimal-arrow a {
              width: 30px;
              height: 70px;
              text-align: center;
              display: block;
              line-height: 0;
              position: relative;
            }
            .centered-text .nectar-next-section-wrap.minimal-arrow a {
              display: inline-block;
            }
            
            .nectar-next-section-wrap[data-align="center"] {
              text-align: center;
            }
            .nectar-next-section-wrap[data-align="right"] {
              text-align: right;
            }
            
            .nectar-next-section-wrap[data-align="center"].minimal-arrow a,
            .nectar-next-section-wrap[data-align="right"].minimal-arrow a {
              display: inline-block;
            }

            .nectar-next-section-wrap.minimal-arrow svg {
              animation: ctaMinimaLArrowOuter 2.5s cubic-bezier(.55, 0, 0.45, 1) infinite;
            }
            
            .nectar-next-section-wrap.minimal-arrow a:hover svg path {
              animation: ctaMinimaLArrowLine 0.6s cubic-bezier(.25, 0, 0.45, 1) forwards;
            }
            
            .nectar-next-section-wrap.minimal-arrow a:hover svg polyline {
              stroke-dashoffset: 0px;
              stroke-dasharray: 45px;
              animation: ctaMinimaLArrow 0.6s cubic-bezier(.25, 0, 0.45, 1) forwards 0.1s;
            }
            
            .nectar-next-section-wrap.minimal-arrow svg {
              display: block;
              width: 40px;
              position: absolute;
              bottom: 0;
              left: 0;
            }
            
            .nectar-next-section-wrap[data-custom-color="true"].bounce a {
              border-radius: 100px;
            }
            
            .nectar-next-section-wrap[data-custom-color="true"].mouse-wheel .nectar-next-section:before {
              display: none;
            }
            
            .nectar-next-section-wrap[data-custom-color="true"].bounce a:before {
              background: inherit!important;
            }
            .nectar-next-section-wrap[data-custom-color="true"].down-arrow-bordered a,
            .nectar-next-section-wrap[data-custom-color="true"].mouse-wheel a:after {
              border-color: inherit;
            }
            .nectar-next-section-wrap[data-custom-color="true"].down-arrow-bordered a,
            .nectar-next-section-wrap[data-custom-color="true"].down-arrow-bordered a i {
              color: inherit!important;
            }
            .nectar-next-section-wrap[data-custom-color="true"].mouse-wheel a:after {
              opacity: 0.5;
            }

            .nectar-next-section-wrap.mouse-wheel .nectar-next-section {
              border-width: 0;
              overflow:visible;
              text-align:center;
              opacity:1;
              height:auto;
              bottom:13px;
              -webkit-animation:nudgeMouse 2.4s cubic-bezier(0.250,0.460,0.450,0.940) infinite;
              animation:nudgeMouse 2.4s cubic-bezier(0.250,0.460,0.450,0.940) infinite
            }
            ';
          }

          // Positioning.
          $positions = self::positioning_group_styles('nectar-cta',$atts);
          foreach( $positions as $position ) {
            self::$element_css[] = $position;
          }

          if( 'next-section' === $button_style ) {
            $next_section_positions = self::positioning_group_styles('nectar-next-section-wrap',$atts);
            foreach( $next_section_positions as $position ) {
              self::$element_css[] = $position;
            }
          }
          
          
          $position_desktop = ( isset($atts['position_desktop']) && !empty($atts['position_desktop']) ) ? $atts['position_desktop'] : 'relative';
          $position_tablet = ( isset($atts['position_tablet']) && !empty($atts['position_tablet']) ) ? $atts['position_tablet'] : 'relative';
          $position_phone = ( isset($atts['position_phone']) && !empty($atts['position_phone']) ) ? $atts['position_phone'] : 'relative';

          if( 'absolute' === $position_desktop ) {
            self::$element_css[] = '@media only screen and (min-width: 1000px) {
              .position_desktop_absolute.nectar-cta .link_wrap { display: flex!important; justify-content: center; align-items: center; }
            }';
            self::$element_css[] = '.position_desktop_absolute.nectar-cta:not(.position_tablet_relative):not(.position_phone_relative) .link_wrap { 
              display: flex!important; justify-content: center; align-items: center; 
            }';
            // when user expands position to match partent column. i.e. top, bottom, left, right = 0;
            self::$element_css[] = '
            .position_desktop_absolute.nectar-cta:not(.position_tablet_relative):not(.position_phone_relative) > *,
            .position_desktop_absolute.nectar-cta:not(.position_tablet_relative):not(.position_phone_relative) .link_wrap { 
              height: 100%;
            }';
          }
          if( 'absolute' === $position_tablet ) {
            self::$element_css[] = '@media only screen and (max-width: 999px) and (min-width: 691px) {
              .position_tablet_absolute.nectar-cta .link_wrap { display: flex!important; justify-content: center; align-items: center; }
            }';
          }
          if( 'absolute' === $position_phone ) {
            self::$element_css[] = '@media only screen and (max-width: 999px) and (min-width: 691px) {
              .position_phone_absolute.nectar-cta .link_wrap { display: flex!important; justify-content: center; align-items: center; }
            }';
          }

          if( 'next-section' !== $button_style ) {

              if( isset($atts['button_color_hover']) && !empty($atts['button_color_hover']) ) {

                $btn_color = ltrim($atts['button_color_hover'],'#');

                // Gradient colors will overlay color on pseudo for transition
                if( isset($atts['button_color']) && 'extra-color-gradient-1' === $atts['button_color'] ||
                    isset($atts['button_color']) && 'extra-color-gradient-2' === $atts['button_color'] ) {
                  self::$element_css[] = '.nectar-cta.hover_color_'.esc_attr($btn_color).' .link_wrap:before {
                    background-color: #'.esc_attr($btn_color).';
                  }';
                }
                // Regular colored btns
                else {
                  self::$element_css[] = '.nectar-cta.hover_color_'.esc_attr($btn_color).' .link_wrap:hover {
                    background-color: #'.esc_attr($btn_color). esc_attr( $override ).';
                  }';
                }

              }

              // Custom hover text color.
              if( isset($atts['text_color_hover']) && !empty($atts['text_color_hover']) ) {
                $text_hover_color = ltrim($atts['text_color_hover'],'#');

                self::$element_css[] = '
                .nectar-cta.text_hover_color_'.esc_attr($text_hover_color).' .link_wrap a {
                  transition: none;  
                }
                .nectar-cta.text_hover_color_'.esc_attr($text_hover_color).' .link_wrap:hover {
                  color: #'.esc_attr($text_hover_color).';
                }';

                if( 'arrow-animation' === $button_style ) {
                  self::$element_css[] = '.nectar-cta[data-style="arrow-animation"].text_hover_color_'.esc_attr($text_hover_color).' .link_wrap:hover  .line {
                    background-color: #'.esc_attr($text_hover_color).'!important;
                  }';
                }

                if( 'material' === $button_style ) {
                  self::$element_css[] = '.nectar-cta[data-style="material"].text_hover_color_'.esc_attr($text_hover_color).' .link_wrap {
                    transition: color 0.25s ease;
                  }
                  .nectar-cta[data-style="material"].text_hover_color_'.esc_attr($text_hover_color).' .link_wrap:hover {
                    color: #'.esc_attr($text_hover_color).'!important;
                  }
                  .nectar-cta[data-style="material"].text_hover_color_'.esc_attr($text_hover_color).' .link_wrap:hover  .circle {
                    background-color: #'.esc_attr($text_hover_color).'!important;
                  }';
                }
                
              }
              

              // Button border.
              if( isset($atts['button_border_color']) && !empty($atts['button_border_color']) ) {
                $border_color = ltrim($atts['button_border_color'],'#');

                self::$element_css[] = '.nectar-cta.border_color_'.esc_attr($border_color).' .link_wrap {
                  border-color: #'.esc_attr($border_color).';
                }';
              }
              if( isset($atts['button_border_color_hover']) && !empty($atts['button_border_color_hover']) ) {

                $border_color_hover = ltrim($atts['button_border_color_hover'],'#');
                self::$element_css[] = '.nectar-cta.hover_border_color_'.esc_attr($border_color_hover).' .link_wrap:hover {
                  border-color: #'.esc_attr($border_color_hover).';
                }';
              }

              if( isset($atts['button_border_thickness']) && strlen($atts['button_border_thickness']) > 0 && $atts['button_border_thickness'] !== '0px' ) {

                self::$element_css[] = '.nectar-cta.border_thickness_'.esc_attr($atts['button_border_thickness']).' .link_wrap {
                  border-width: '.esc_attr($atts['button_border_thickness']).';
                  border-style: solid;
                }';
                
              }
              

            }

            // Border radius.
            if( isset($atts['border_radius']) && strlen($atts['border_radius']) > 0 ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-cta.border_radius_'. self::percent_unit_type(esc_attr( $atts['border_radius'])) .' .link_wrap {
                border-radius: '.self::percent_unit_type(esc_attr( $atts['border_radius'] )).'!important;
              }';
            }

            // Custom Alignment.
            if( isset($atts['alignment_tablet']) && 'default' !== $atts['alignment_tablet'] ) {
                self::$element_css[] = '@media only screen and (max-width: 999px) {
                  body .nectar-cta.alignment_tablet_'. esc_attr( $atts['alignment_tablet'] ) .', 
                  body .nectar-next-section-wrap.alignment_tablet_'. esc_attr( $atts['alignment_tablet'] ) .' {
                  text-align: '. esc_attr( $atts['alignment_tablet'] ) .';
                }
              }';
            }
            if( isset($atts['alignment_phone']) && 'default' !== $atts['alignment_phone'] ) {
                self::$element_css[] = '@media only screen and (max-width: 690px) {
                  html body .nectar-cta.alignment_phone_'. esc_attr( $atts['alignment_phone'] ) .',
                  html body .nectar-next-section-wrap.alignment_phone_'. esc_attr( $atts['alignment_phone'] ) .' {
                  text-align: '. esc_attr( $atts['alignment_phone'] ) .';
                }
              }';
            }

            if( isset($atts['display_tablet']) && 'default' !== $atts['display_tablet'] ) {
              self::$element_css[] = '@media only screen and (max-width: 999px) {
                .nectar-cta.display_tablet_'. esc_attr( $atts['display_tablet'] ) .' {
                display: '.esc_attr( $atts['display_tablet'] ).';
              }
            }';
          }
          
          if( isset($atts['display_phone']) && 'default' !== $atts['display_phone'] ) {
            self::$element_css[] = '@media only screen and (max-width: 690px) {
              .nectar-cta.display_phone_'. esc_attr( $atts['display_phone'] ) .' {
              display: '.esc_attr( $atts['display_phone'] ).';
            }
          }';
        }
          
        
        /* Icon */
        if( isset($atts['icon_family']) && $atts['icon_family'] !== 'none' ) {

          if( isset($atts['alignment']) && 'center' === $atts['alignment'] ) {
            self::$element_css[] = '.nectar-cta.has-icon[data-alignment="center"][data-style="underline"] .link_wrap, 
              .nectar-cta.has-icon[data-alignment="center"][data-style="text-reveal-wave"] .link_wrap,
              .nectar-cta.has-icon[data-alignment="center"][data-style="basic"] .link_wrap {
                justify-content: center;
            }';
          }

          if( 'underline' === $button_style ) {
            self::$element_css[] = '
            .nectar-cta.has-icon[data-style="underline"] .link_wrap .link_text {
              line-height: 1.5;
            }
            .nectar-cta.has-icon[data-style="underline"] .nectar-button-type {
              display: flex;
              align-items: center;
            }
            .nectar-cta.has-icon[data-style="underline"] .nectar-button-type .text {
              margin-right: 0.7em;
            }
            ';
          }
          
          self::$element_css[] = '.nectar-cta.has-icon .link_wrap {
            display: flex;
            align-items: center;
          }
          .nectar-cta.has-icon .link_wrap i {
            margin-right: 0.7em;
            line-height: 1;
            font-size: 1.3em;
          }
          .nectar-cta.has-icon .link_wrap .link_text {
            line-height: 1;
          }
          .nectar-cta.has-icon .link_wrap i svg {
            width: 1.3em;
            fill: currentColor;
          }
          .nectar-cta.has-icon .im-icon-wrap,
          .nectar-cta.has-icon .im-icon-wrap *{
            display: block;
          } 
          ';
        }
        
        
        /* Custom font size */
        if( isset($atts['font_size_desktop']) ) {
          self::$element_css[] = '.nectar-cta.font_size_desktop_'. self::decimal_unit_type_class($atts['font_size_desktop']) .',
          .nectar-cta.font_size_desktop_'. self::decimal_unit_type_class($atts['font_size_desktop']) .' * {
            font-size: '. esc_attr(self::font_size_output($atts['font_size_desktop'])) .';
            line-height: 1.1;
          }'; 

          if( 'underline' === $button_style ) {
            self::$element_css[] = '.nectar-cta.font_size_desktop_'. self::decimal_unit_type_class($atts['font_size_desktop']) .'[data-style="underline"],
            .nectar-cta.font_size_desktop_'. self::decimal_unit_type_class($atts['font_size_desktop']) .' * {
              line-height: 1.5;
            }'; 
          }

        }

        if( isset($atts['font_size_tablet']) ) {
          self::$element_css[] = '@media only screen and (max-width: 999px) { 
            body .nectar-cta.font_size_tablet_'. self::decimal_unit_type_class($atts['font_size_tablet']) .',
            body .nectar-cta.font_size_tablet_'. self::decimal_unit_type_class($atts['font_size_tablet']) .' *  {
              font-size: '. esc_attr(self::font_size_output($atts['font_size_tablet'])) .';
              line-height: 1.1;
          }
          }'; 
        }

        if( isset($atts['font_size_phone']) ) {
          self::$element_css[] = '@media only screen and (max-width: 690px) { 
            body .nectar-cta.font_size_phone_'. self::decimal_unit_type_class($atts['font_size_phone']) .',
            body .nectar-cta.font_size_phone_'. self::decimal_unit_type_class($atts['font_size_phone']) .' * {
              font-size: '. esc_attr(self::font_size_output($atts['font_size_phone'])) .';
              line-height: 1.1;
          }
          }'; 
        }
      
    


      } // End CTA Element.


      // Nectar Button Element.
      else if ( false !== strpos($shortcode[0],'[nectar_btn ') || false !== strpos($shortcode[0],'[button ') ) {

      $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['button_style']) && 'see-through-3d' === $atts['button_style'] ||
            isset($atts['color']) && 'see-through-3d' === $atts['color'] ) {

          self::$element_css[] = '.nectar-3d-transparent-button {
            font-weight:700;
            font-size:12px;
            line-height:20px;
            visibility:hidden
          }
          .nectar-3d-transparent-button{
            display:inline-block
          }
          .nectar-3d-transparent-button a{
            display:block
          }
          .nectar-3d-transparent-button .hidden-text{
            height:1em;
            line-height:1.5;
            overflow:hidden
          }
          .nectar-3d-transparent-button .hidden-text{
            display:block;
            height:0;
            position:absolute
          }
          body .nectar-3d-transparent-button{
            position:relative;
            margin-bottom:0
          }
          .nectar-3d-transparent-button .inner-wrap{
            -webkit-perspective:2000px;
            perspective:2000px;
            position:absolute;
            top:0;
            right:0;
            bottom:0;
            left:0;
            width:100%;
            height:100%;
            display:block
          }
          .nectar-3d-transparent-button .front-3d{
            position:absolute;
            top:0;
            right:0;
            bottom:0;
            left:0;
            width:100%;
            height:100%;
            display:block
          }
          .nectar-3d-transparent-button .back-3d{
            position:relative;
            top:0;
            right:0;
            bottom:0;
            left:0;
            width:100%;
            height:100%;
            display:block
          }
          .nectar-3d-transparent-button .back-3d{
            -webkit-transform-origin:50% 50% -2.3em;
            transform-origin:50% 50% -2.3em
          }
          .nectar-3d-transparent-button .front-3d{
            -webkit-transform-origin:50% 50% -2.3em;
            transform-origin:50% 50% -2.3em;
            -webkit-transform:rotateX(-90deg);
            transform:rotateX(-90deg)
          }
          .nectar-3d-transparent-button:hover .front-3d{
            -webkit-transform:rotateX(0deg);
            transform:rotateX(0deg)
          }
          .nectar-3d-transparent-button:hover .back-3d{
            -webkit-transform:rotateX(90deg);
            transform:rotateX(90deg)
          }
          .nectar-3d-transparent-button .back-3d,
          .nectar-3d-transparent-button .front-3d{
            transition:-webkit-transform .25s cubic-bezier(.2,.65,.4,1);
            transition:transform .25s cubic-bezier(.2,.65,.4,1);
            transition:transform .25s cubic-bezier(.2,.65,.4,1),-webkit-transform .25s cubic-bezier(.2,.65,.4,1)
          }
          .nectar-3d-transparent-button .back-3d,
          .nectar-3d-transparent-button .front-3d{
            -webkit-backface-visibility:hidden;
            backface-visibility:hidden
          }
          .nectar-3d-transparent-button .back-3d svg,
          .nectar-3d-transparent-button .front-3d svg{
            display:block
          }';

        }

        // Button Sizes.
        if( isset($atts['size']) && 'small' === $atts['size'] ) {
          self::$element_css[] = '.nectar-button.small{
            border-radius:2px 2px 2px 2px;
            font-size:12px;
            padding:8px 14px;
            color:#FFF;
            box-shadow:0 -1px rgba(0,0,0,0.1) inset;
          }
          .nectar-button.small.see-through,
          .nectar-button.small.see-through-2,
          .nectar-button.small.see-through-3{
            padding-top:6px;
            padding-bottom:6px
          }
          .nectar-button.small i{
            font-size:16px;
            line-height:16px;
            right:26px
          }
          .nectar-button.small i.icon-button-arrow{
            font-size:16px
          }
          .nectar-button.has-icon.small,
          .nectar-button.tilt.has-icon.small{
            padding-left:33px;
            padding-right:33px
          }
          .nectar-button.has-icon.small:hover span,
          .nectar-button.tilt.small.has-icon span,
          body.material .nectar-button.has-icon.small span {
            -webkit-transform:translateX(-14px);
            transform:translateX(-14px)
          }
          .nectar-button.small.has-icon:hover i,
          .nectar-button.small.tilt.has-icon i {
            -webkit-transform:translateX(10px);
            transform:translateX(10px);
          }

          body.material .nectar-button.small i {
            font-size: 14px;
          }
          body.material[data-button-style^="rounded"] .nectar-button.small i {
            font-size: 12px;
          }
          ';
        }

        if( isset($atts['size']) && 'medium' === $atts['size'] ) {
          self::$element_css[] = '
          .nectar-button.medium{
            border-radius:3px 3px 3px 3px;
            padding:10px 15px;
            font-size:12px;
            color:#FFF;
            box-shadow:0 -2px rgba(0,0,0,0.1) inset;
          }
          .nectar-button.medium.see-through,
          .nectar-button.medium.see-through-2,
          .nectar-button.medium.see-through-3{
            padding-top:9px;
            padding-bottom:9px
          }
          .nectar-button.medium i.icon-button-arrow {
            font-size:16px
          }
          body[data-button-style^="rounded"] .nectar-button.medium:not(.see-through):not(.see-through-2):not(.see-through-3).has-icon,
          body[data-button-style^="rounded"] .nectar-button.medium:not(.see-through):not(.see-through-2):not(.see-through-3).tilt.has-icon{
            padding-left:42px;
            padding-right:42px
          }
          body[data-button-style^="rounded"] .nectar-button.medium:not(.see-through):not(.see-through-2):not(.see-through-3) {
            padding: 12px 18px;
          }

          .nectar-button.medium.has-icon, .nectar-button.medium.tilt.has-icon{
            padding-left:42px;
            padding-right:42px
          }

          ';
        }

        if( isset($atts['size']) && 'extra_jumbo' === $atts['size'] ) {

          self::$element_css[] = '
          .nectar-button.extra_jumbo{
            font-size:60px;
            line-height:60px;
            padding:60px 90px;
            box-shadow:0 -3px rgba(0,0,0,0.1) inset;
          }
          body .nectar-button.extra_jumbo.see-through,
          body .nectar-button.extra_jumbo.see-through-2,
          body .nectar-button.extra_jumbo.see-through-3{
            border-width:10px
          }
          .nectar-button.extra_jumbo.has-icon,
          .nectar-button.tilt.extra_jumbo.has-icon{
            padding-left:80px;
            padding-right:80px
          }
          .nectar-button.extra_jumbo i,
          .nectar-button.tilt.extra_jumbo i,
          .nectar-button.extra_jumbo i[class*="fa-"],
          .nectar-button.tilt.extra_jumbo i[class*="fa-"] {
            right:75px
          }
          .nectar-button.has-icon.extra_jumbo:hover i,
          .nectar-button.tilt.extra_jumbo.has-icon i{
            -webkit-transform:translateX(13px);
            transform:translateX(13px);
          }
          .nectar-button.has-icon.extra_jumbo:hover span,
          .nectar-button.tilt.extra_jumbo.has-icon span {
            -webkit-transform:translateX(-30px);
            transform:translateX(-30px)
          }
          body .nectar-button.extra_jumbo i{
            font-size:40px;
            margin-top:-20px;
            line-height:40px
          }
          .nectar-button.extra_jumbo .im-icon-wrap svg {
            width: 40px;
            height: 40px;
          }


          @media only screen and (max-width: 999px) and (min-width: 691px) {

            body.material .nectar-button.extra_jumbo.has-icon {
              font-size: 30px;
              line-height: 60px;
              padding: 30px 100px 30px 60px;
            }

            body.material .nectar-button.has-icon.extra_jumbo i {
              height: 74px;
              width: 74px;
              line-height: 74px;
            }

          }

          @media only screen and (min-width : 691px) and (max-width : 999px) {
            .nectar-button.extra_jumbo {
              font-size: 32px;
              line-height: 60px;
              padding: 30px 50px;
            }

            .nectar-button.see-through-extra-color-gradient-1.extra_jumbo,
            .nectar-button.see-through-extra-color-gradient-2.extra_jumbo,
            .nectar-button.extra-color-gradient-1.extra_jumbo,
            .nectar-button.extra-color-gradient-2.extra_jumbo {
              border-width: 8px;
            }
          }

          @media only screen and (max-width : 690px) {
            .nectar-button.extra_jumbo {
              font-size: 24px;
              line-height: 24px;
              padding: 20px 30px;
            }

            .nectar-button.extra_jumbo.has-icon.extra-color-gradient-1,
            .nectar-button.extra_jumbo.has-icon.extra-color-gradient-2,
          	.nectar-button.extra_jumbo.has-icon.see-through-extra-color-gradient-1,
            .nectar-button.extra_jumbo.has-icon.see-through-extra-color-gradient-2 {
              font-size: 24px;
              line-height: 24px;
              padding: 20px 50px;
            }

            .nectar-button.extra-color-gradient-1.has-icon.extra_jumbo span,
            .nectar-button.extra-color-gradient-2.has-icon.extra_jumbo span,
            .nectar-button.see-through-extra-color-gradient-1.has-icon.extra_jumbo span,
            .nectar-button.see-through-extra-color-gradient-2.has-icon.extra_jumbo span {
              left: -28px;
            }

            .nectar-button.extra_jumbo i,
            .nectar-button.extra_jumbo.has-icon i {
              font-size: 26px;
            }

            body.material #ajax-content-wrap .nectar-button.extra_jumbo.has-icon {
              font-size: 22px;
              line-height: 22px;
              padding: 24px 65px 24px 55px;
            }

            body.material #ajax-content-wrap .nectar-button.has-icon.extra_jumbo i {
              height: 50px;
              width: 50px;
              line-height: 50px;
            }

            body.material .nectar-button.extra_jumbo .im-icon-wrap svg {
              width: 24px;
              height: 24px;
            }

            .nectar-button.see-through-extra-color-gradient-1.extra_jumbo,
            .nectar-button.see-through-extra-color-gradient-2.extra_jumbo,
            .nectar-button.extra-color-gradient-1.extra_jumbo,
            .nectar-button.extra-color-gradient-2.extra_jumbo {
              border-width: 6px;
            }

          }';
        }


      } // End Button Element.

      // Category Grid Element.
      else if( false !== strpos($shortcode[0],'[nectar_category_grid ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Style.
        if( isset($atts['grid_style']) && 'mouse_follow_image' === $atts['grid_style']) {

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content .cat-heading {
              max-width: 100%;
            }
          }

          .nectar-category-grid[data-style="mouse_follow_image"] {
            justify-content: center;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .inner {
           position: static;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-link {
            z-index: 100;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .inner {
            overflow: visible;
            width: auto;
            background-color: transparent;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .bg-overlay {
            display: none;
          }

          #ajax-content-wrap .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content {
            position: relative;
            width: auto;
            top: auto;
            left: auto;
            padding: .3em .5em;
            line-height: 1;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
            position: fixed;
            left: -150px;
            top: -150px;
            opacity: 0;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
            width: 300px;
            height: 300px;
            transition: opacity 0.15s ease;
          }
          .nectar-category-grid[data-style="mouse_follow_image"]:not(.active) .nectar-category-grid-item-bg[data-nectar-img-src] {
            display: none;
          }
          .nectar-category-grid[data-style="mouse_follow_image"].mouse-over .nectar-category-grid-item .nectar-category-grid-item-bg {
            transition: opacity 0.15s ease 0.15s;
          }
          .nectar-category-grid[data-style="mouse_follow_image"].mouse-over .nectar-category-grid-item:hover .nectar-category-grid-item-bg {
            opacity: 1;
            transition: opacity 0.15s ease;
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .content .cat-heading {
            line-height: 1em;
            transition: color 0.45s cubic-bezier(.15,.75,.5,1), background-size 0.45s cubic-bezier(.15,.75,.5,1);
            display: inline;
          }
          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content .subtext {
            transition: color 0.45s cubic-bezier(.15,.75,.5,1), opacity 0.45s cubic-bezier(.15,.75,.5,1);
          }

          .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
            will-change: transform, opacity;
          }

          .using-mobile-browser .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
            will-change: auto;
          }
          @media only screen and (max-width: 690px) {
            #ajax-content-wrap .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .content {
              padding: .33em .66em;
            }
          }

          ';

          if( isset($atts['image_aspect_ratio']) && '1-1' !== $atts['image_aspect_ratio'] ) {

            if( '4-5' === $atts['image_aspect_ratio'] ) {
              self::$element_css[] = '.nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
                  left: -150px;
                  top: -187.5px;
                  width: 300px;
                  height: 375px;
                }';
            }
            else if ( '16-9' === $atts['image_aspect_ratio'] ) {

              self::$element_css[] = '.nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
                  left: -175px;
                  top: -98px;
                  width: 350px;
                  height: 196px;
                }';
            }

            else if ( '4-3' === $atts['image_aspect_ratio'] ) {

              self::$element_css[] = '.nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item-bg {
                  left: -175px;
                  top: -131px;
                  width: 350px;
                  height: 262px;
                }';
            }


          }

          if( isset($atts['subtext']) && 'none' !== $atts['subtext'] ) {
            self::$element_css[] = '

            .nectar-category-grid[data-text-hover-color="dark"] .nectar-category-grid-item .content .subtext {
              color: #000;
            }
            .nectar-category-grid[data-text-hover-color="light"] .nectar-category-grid-item .content .subtext {
              color: #fff;
            }
            #ajax-content-wrap .nectar-category-grid .nectar-category-grid-item .subtext:after {
              display: none;
            }
            .nectar-category-grid[data-style="mouse_follow_image"] .content {
              text-align: center;
            }
            .nectar-category-grid[data-style="mouse_follow_image"] .subtext {
              display: block;
              opacity: 0.6;
              margin-top: 12px;
              line-height: 1;
            }';
          }

        } // End mouse follow image style.

      } // End Category Grid Element.

      // Post Grid Element
      else if ( false !== strpos($shortcode[0],'[nectar_post_grid ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $post_grid_masonry    = ( isset($atts['enable_masonry']) && 'yes' === $atts['enable_masonry'] ) ? true : false;
        $post_grid_style      = ( isset($atts['grid_style']) ) ? $atts['grid_style'] : 'content_overlaid';
        $post_grid_spacing    = ( isset($atts['grid_item_spacing']) ) ? $atts['grid_item_spacing'] : 'none';
        $post_grid_br         = ( isset($atts['border_radius']) ) ? $atts['border_radius'] : 'none';
        $post_grid_opacity    = ( isset($atts['color_overlay_opacity']) ) ? $atts['color_overlay_opacity'] : '0.3';
        $post_grid_opacity_h  = ( isset($atts['color_overlay_hover_opacity']) ) ? $atts['color_overlay_hover_opacity'] : '0.4';
        $post_grid_custom_ar  = ( isset($atts['custom_image_aspect_ratio']) && in_array($atts['custom_image_aspect_ratio'], array('1-1','16-9','3-2','4-3','4-5')) ) ? $atts['custom_image_aspect_ratio'] : false;
        $post_grid_featured_f = ( isset($atts['featured_top_item']) && 'yes' === $atts['featured_top_item'] ) ? true : false;

        // Height.
        if( isset($atts['grid_item_height']) && !empty($atts['grid_item_height']) ) {

          self::$element_css[] = '
            .nectar-post-grid[data-grid-item-height="'.esc_attr($atts['grid_item_height']).'"] .nectar-post-grid-item {
              min-height: '.esc_attr($atts['grid_item_height']).';
            }';

          if( $post_grid_masonry ) {
            self::$element_css[] = '
              .nectar-post-grid[data-grid-item-height="'.esc_attr($atts['grid_item_height']).'"][data-masonry="yes"] {
                grid-auto-rows: minmax('.esc_attr($atts['grid_item_height']).', auto);
              }
            ';
          }

          if( $post_grid_style === 'content_under_image' ) {
            self::$element_css[] = '
            .nectar-post-grid-wrap[data-style="content_under_image"] [data-grid-item-height="'.esc_attr($atts['grid_item_height']).'"] .nectar-post-grid-item-bg {
              height: '.esc_attr($atts['grid_item_height']).';
            }';

            if( $post_grid_custom_ar && isset($atts['aspect_ratio_image_size']) && 'yes' === $atts['aspect_ratio_image_size']) {
              self::$element_css[] = '
              .nectar-post-grid-wrap[data-style="content_under_image"] .custom-aspect-ratio-'.esc_attr($post_grid_custom_ar).' .nectar-post-grid-item-bg {
                padding-bottom: '.esc_attr(self::image_aspect_ratio($post_grid_custom_ar)).';
                height: auto!important;
              }';
            }

          }

        }

        // Border Radius.
        if( in_array($post_grid_br, array('3px','5px','10px','15px')) ) {

          self::$element_css[] = '
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"][data-text-layout="all_bottom_left_shadow"] .nectar-post-grid-item:before,
          .nectar-post-grid-wrap:not([data-style="content_under_image"]) .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item .inner,
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .bg-overlay,
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"][data-shadow-hover="yes"] .nectar-post-grid-item:after,
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg,
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap,
          [data-style="mouse_follow_image"] .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap-inner,
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"][data-text-layout="all_bottom_left_shadow"] .nectar-post-grid-item div.inner:before {
            border-radius: '.$post_grid_br.';
          }
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg,
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap {
            overflow: hidden; 
          }
          .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap {
            transform: translateZ(0);
          }';

          if( $post_grid_style === 'content_under_image' ) {
            self::$element_css[] = '
            [data-style="content_under_image"] .nectar-post-grid[data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item__has-secondary .nectar-post-grid-item-bg,
            [data-style="content_under_image"] .nectar-post-grid[data-border-radius="'.$post_grid_br.'"]:not([data-card="yes"]) .nectar-post-grid-item-bg-wrap,
            [data-style="content_under_image"] .nectar-post-grid[data-border-radius="'.$post_grid_br.'"][data-lock-aspect="yes"]:not([data-card="yes"]) .img-wrap,
            [data-style="content_under_image"] .nectar-post-grid[data-border-radius="'.$post_grid_br.'"][data-card="yes"] .nectar-post-grid-item,
            [data-style="content_under_image"] .nectar-post-grid[data-border-radius="'.$post_grid_br.'"][data-shadow-hover="yes"][data-card="yes"] .nectar-post-grid-item:after {
              border-radius: '.$post_grid_br.';
            }';
          }

        }

        if( $post_grid_br === 'none' ) {
          self::$element_css[] = '
          .nectar-post-grid[data-border-radius="none"][data-text-layout="all_bottom_left_shadow"] .nectar-post-grid-item:before,
          .nectar-post-grid[data-border-radius="none"] .nectar-post-grid-item .inner,
          .nectar-post-grid[data-border-radius="none"] .bg-overlay {
             border-radius: 0px;
          }
          ';
        }

        // Button style
        if( isset($atts['category_style']) && 'button' === $atts['category_style'] ) {
          self::$element_css[] = '
          .nectar-post-grid .meta-category .style-button {
            padding: 0.5em 1em;
            line-height: 1!important;
            background-color: var(--nectar-accent-color);
          }
          #ajax-content-wrap .nectar-post-grid .meta-category .style-button {
            color: #fff;
          }
          .nectar-post-grid .meta-category .style-button:before {
            display: none;
          }';
        }

        // Read more button.
        if( isset($atts['read_more_button']) && 'yes' === $atts['read_more_button'] ) {
          self::$element_css[] = '
          .nectar-post-grid .nectar-cta {
             display: block;
             margin-top: 10px;
          }
          @media only screen and (max-width: 690px) {
            .vc_col-xs-4 .nectar-post-grid .nectar-cta .link_text,
            .vc_col-xs-6 .nectar-post-grid .nectar-cta .link_text {
              font-size: 11px;
            }
            .vc_col-xs-4 .nectar-post-grid .nectar-cta svg,
            .vc_col-xs-6 .nectar-post-grid .nectar-cta svg {
              width: 18px;
            }
          }
          ';
        }

        // Excerpt.
        self::$element_css[] = '
        @media only screen and (max-width: 690px) {
          .vc_col-xs-4 .nectar-post-grid .nectar-post-grid-item .meta-excerpt,
          .vc_col-xs-6 .nectar-post-grid .nectar-post-grid-item .meta-excerpt {
            font-size: 12px;
          }
          .nectar-post-grid .nectar-post-grid-item .content .post-heading,
          .nectar-post-grid-wrap:not([data-style=content_overlaid]) .nectar-post-grid-item .content .meta-excerpt {
            max-width: 100%;
          }
        }
        ';
        if (isset($atts['excerpt_display']) && 'desktop_only' === $atts['excerpt_display']) {
          self::$element_css[] = '
          @media only screen and (max-width: 999px) {
            .nectar-post-grid.excerpt-desktop_only .meta-excerpt {
              display: none;
            }
          }
          ';
        }

        // Meta sizine.
        if( isset($atts['additional_meta_size']) && 'small' === $atts['additional_meta_size'] ) {
          self::$element_css[] = '.nectar-post-grid-wrap.additional-meta-size-small .nectar-post-grid-item__meta-wrap {
            font-size: 0.8em;
          }';
        }

        // Spacing.
        if( $post_grid_style !== 'vertical_list' ) {

          self::$element_css[] = '#ajax-content-wrap .nectar-post-grid[data-columns="1"] > .nectar-post-grid-item:nth-child(1) {
             margin-top: 0;
          }
          #ajax-content-wrap .nectar-post-grid[data-columns="1"] > .nectar-post-grid-item:last-child {
            margin-bottom: 0;
         }';

          if( $post_grid_spacing === 'none' ) {

            self::$element_css[] = '
              .nectar-post-grid[data-columns="4"][data-grid-spacing="none"] .nectar-post-grid-item {
                width: 25%;
              }
              .nectar-post-grid[data-columns="3"][data-grid-spacing="none"] .nectar-post-grid-item {
                width: 33.32%;
              }
              .nectar-post-grid[data-columns="2"][data-grid-spacing="none"] .nectar-post-grid-item {
                width: 50%;
              }
              
              @media only screen and (max-width: 999px) and (min-width: 691px) {
                .nectar-post-grid[data-columns="4"][data-grid-spacing="none"]:not([data-masonry="yes"]) .nectar-post-grid-item {
                  width: 50%;
                  padding-bottom: 50%;
                }
              }';
              
          }

          if( $post_grid_spacing === '5px' ) {

            self::$element_css[] = '
            .nectar-post-grid[data-grid-spacing="5px"] {
              margin-left: -5px;
              margin-right: -5px;
            }
            
            .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="5px"] {
              margin: 5px;
            }

            @media only screen and (min-width: 1000px) {
              body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="5px"]{
                margin: 5px -4px;
              }
            }

            .nectar-post-grid[data-grid-spacing="5px"] .nectar-post-grid-item {
              margin: 5px;
            }
            .nectar-post-grid[data-columns="4"][data-grid-spacing="5px"] .nectar-post-grid-item {
              width: calc(25% - 10px);
            }
            .nectar-post-grid[data-columns="3"][data-grid-spacing="5px"] .nectar-post-grid-item {
              width: calc(33.32% - 10px);
            }
            .nectar-post-grid[data-columns="2"][data-grid-spacing="5px"] .nectar-post-grid-item {
              width: calc(50% - 10px);
            }
            
            @media only screen and (max-width: 999px) and (min-width: 691px) {

              body .nectar-post-grid[data-columns][data-grid-spacing="5px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                width: calc(50% - 10px);
              }
            
            }';

          } 
          
          else if( $post_grid_spacing === '10px' ) {
            self::$element_css[] = '
            .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="10px"] {
              margin: 10px;
            }

            .nectar-post-grid[data-grid-spacing="10px"] {
              margin-left: -10px;
              margin-right: -10px;
            }

            @media only screen and (min-width: 1000px) {
              body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="10px"]{
                margin: 10px -9px;
              }
            }

            .nectar-post-grid[data-grid-spacing="10px"] .nectar-post-grid-item {
              margin: 10px;
            }

            .nectar-post-grid[data-columns="4"][data-grid-spacing="10px"] .nectar-post-grid-item {
              width: calc(25% - 20px);
            }
            .nectar-post-grid[data-columns="3"][data-grid-spacing="10px"] .nectar-post-grid-item {
              width: calc(33.32% - 20px);
            }
            .nectar-post-grid[data-columns="2"][data-grid-spacing="10px"] .nectar-post-grid-item {
              width: calc(50% - 20px);
            }

            @media only screen and (max-width: 999px) and (min-width: 691px) {
              body .nectar-post-grid[data-columns][data-grid-spacing="10px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                width: calc(50% - 20px);
              }
            
            }';

          }

          else if( $post_grid_spacing === '15px' ) {
            self::$element_css[] = '

            .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="15px"] {
              margin: 15px;
            }

            .nectar-post-grid[data-grid-spacing="15px"] {
              margin-left: -15px;
              margin-right: -15px;
            }

            .nectar-post-grid[data-grid-spacing="15px"] .nectar-post-grid-item {
            margin: 15px;
            }

            @media only screen and (min-width: 1000px) {
              body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="15px"]{
                margin: 15px -14px;
              }
            }

            .nectar-post-grid[data-columns="4"][data-grid-spacing="15px"] .nectar-post-grid-item {
              width: calc(25% - 30px);
            }
            .nectar-post-grid[data-columns="3"][data-grid-spacing="15px"] .nectar-post-grid-item {
              width: calc(33.32% - 30px);
            }
            .nectar-post-grid[data-columns="2"][data-grid-spacing="15px"] .nectar-post-grid-item {
              width: calc(50% - 30px);
            }

            @media only screen and (max-width: 999px) and (min-width: 691px) {
              body .nectar-post-grid[data-columns][data-grid-spacing="15px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                width: calc(50% - 30px);
              }
            }';

          }

          else if( $post_grid_spacing === '25px' ) {
            self::$element_css[] = '

            .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="25px"] {
              margin: 25px;
            }

            .nectar-post-grid[data-grid-spacing="25px"] {
              margin-left: -25px;
              margin-right: -25px;
            }

            .nectar-post-grid[data-grid-spacing="25px"] .nectar-post-grid-item {
            margin: 25px;
            }

            @media only screen and (min-width: 1000px) {
              body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="25px"]{
                margin: 25px -24px;
              }
            }

            .nectar-post-grid[data-columns="4"][data-grid-spacing="25px"] .nectar-post-grid-item {
              width: calc(25% - 50px);
            }
            .nectar-post-grid[data-columns="3"][data-grid-spacing="25px"] .nectar-post-grid-item {
              width: calc(33.32% - 50px);
            }
            .nectar-post-grid[data-columns="2"][data-grid-spacing="25px"] .nectar-post-grid-item {
              width: calc(50% - 50px);
            }

            .nectar-post-grid-wrap[data-style="content_under_image"] .nectar-post-grid:not([data-card="yes"]) .nectar-post-grid-item .content {
              padding-bottom: 0;
            }

            @media only screen and (max-width: 999px) and (min-width: 691px) {
              body .nectar-post-grid[data-columns][data-grid-spacing="25px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                width: calc(50% - 50px);
              }
            }';

          }

          else if( $post_grid_spacing === '35px' ) {
            self::$element_css[] = '

              .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="35px"] {
                margin: 35px;
              }
              @media only screen and (min-width: 1000px) {
                body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="35px"]{
                  margin: 35px -34px;
                }
              }

              .nectar-post-grid[data-grid-spacing="35px"]:not([data-columns="1"]) {
                margin-left: -35px;
                margin-right: -35px;
              }
              .nectar-post-grid[data-grid-spacing="35px"][data-columns="1"] .nectar-post-grid-item {
                margin-left: 0;
                margin-right: 0;
              }
              
              
              .nectar-post-grid[data-grid-spacing="35px"] .nectar-post-grid-item {
                margin: 35px;
              }
              
              
              @media only screen and (max-width: 690px) {
                .nectar-post-grid[data-grid-spacing="35px"] .nectar-post-grid-item,
                .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="35px"] {
                  margin: 20px;
                }
                .nectar-post-grid[data-grid-spacing="35px"]:not([data-columns="1"]) {
                  margin-left: -20px;
                  margin-right: -20px;
                }
              }
              
              .nectar-post-grid[data-columns="4"][data-grid-spacing="35px"] .nectar-post-grid-item {
                width: calc(25% - 70px);
              }
              .nectar-post-grid[data-columns="3"][data-grid-spacing="35px"] .nectar-post-grid-item {
                width: calc(33.32% - 70px);
              }
              .nectar-post-grid[data-columns="2"][data-grid-spacing="35px"] .nectar-post-grid-item {
                width: calc(50% - 70px);
              }

              .nectar-post-grid-wrap[data-style="content_under_image"] .nectar-post-grid:not([data-card="yes"]) .nectar-post-grid-item .content {
                padding-bottom: 0;
              }

              @media only screen and (max-width: 999px) and (min-width: 691px) {
                body .nectar-post-grid[data-columns][data-grid-spacing="35px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                  width: calc(50% - 70px);
                }
              }';

          }

          else if( $post_grid_spacing === '40px' ) {
            self::$element_css[] = '

              .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="40px"] {
                margin: 40px;
              }
              @media only screen and (min-width: 1000px) {
                body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="40px"]{
                  margin: 40px -39px;
                }
              }

              .nectar-post-grid[data-grid-spacing="40px"] {
                margin-left: -40px;
                margin-right: -40px;
              }
              
              .nectar-post-grid[data-grid-spacing="40px"] .nectar-post-grid-item {
                margin: 40px;
              }

              @media only screen and (max-width: 690px) {
                .nectar-post-grid[data-grid-spacing="40px"] .nectar-post-grid-item,
                .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="40px"] {
                  margin: 20px;
                }
              }
              
              .nectar-post-grid[data-columns="4"][data-grid-spacing="40px"] .nectar-post-grid-item {
                width: calc(25% - 80px);
              }
              .nectar-post-grid[data-columns="3"][data-grid-spacing="40px"] .nectar-post-grid-item {
                width: calc(33.32% - 80px);
              }
              .nectar-post-grid[data-columns="2"][data-grid-spacing="40px"] .nectar-post-grid-item {
                width: calc(50% - 80px);
              }

              .nectar-post-grid-wrap[data-style="content_under_image"] .nectar-post-grid:not([data-card="yes"]) .nectar-post-grid-item .content {
                padding-bottom: 0;
              }

              @media only screen and (max-width: 999px) and (min-width: 691px) {
                body .nectar-post-grid[data-columns][data-grid-spacing="40px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                  width: calc(50% - 80px);
                }
              }';

          }

          else if( $post_grid_spacing === '45px' ) {
            self::$element_css[] = '

              .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="45px"] {
                margin: 45px;
              }
              @media only screen and (min-width: 1000px) {
                body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="45px"]{
                  margin: 45px -44px;
                }
              }

              .nectar-post-grid[data-grid-spacing="45px"] {
                margin-left: -45px;
                margin-right: -45px;
              }

              .nectar-post-grid[data-grid-spacing="45px"] .nectar-post-grid-item {
                margin: 45px;
              }

              @media only screen and (max-width: 690px) {
                .nectar-post-grid[data-grid-spacing="45px"] .nectar-post-grid-item,
                .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="45px"] {
                  margin: 25px;
                }
              }
              
              .nectar-post-grid[data-columns="4"][data-grid-spacing="45px"] .nectar-post-grid-item {
                width: calc(25% - 90px);
              }
              .nectar-post-grid[data-columns="3"][data-grid-spacing="45px"] .nectar-post-grid-item {
                width: calc(33.32% - 90px);
              }
              .nectar-post-grid[data-columns="2"][data-grid-spacing="45px"] .nectar-post-grid-item {
                width: calc(50% - 90px);
              }

              .nectar-post-grid-wrap[data-style="content_under_image"] .nectar-post-grid:not([data-card="yes"]) .nectar-post-grid-item .content {
                padding-bottom: 0;
              }
              
              @media only screen and (max-width: 999px) and (min-width: 691px) {
                body .nectar-post-grid[data-columns][data-grid-spacing="45px"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                  width: calc(50% - 90px);
                }
              }';

          }

          else if( in_array($post_grid_spacing, array( '1vw', '2vw', '3vw', '4vw')) ) {
            self::$element_css[] = '

              .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] {
                margin: '.esc_attr($post_grid_spacing).';
              }
              @media only screen and (min-width: 1000px) {
                body[data-body-border="1"] .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"]{
                  margin: '.esc_attr($post_grid_spacing).' -'.esc_attr($post_grid_spacing).';
                }
              }

              .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] {
                margin-left: -'.esc_attr($post_grid_spacing).';
                margin-right: -'.esc_attr($post_grid_spacing).';
              }

              .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                margin: '.esc_attr($post_grid_spacing).';
              }
              @media only screen and (max-width: 690px) {
                .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  margin: '.(floatval($post_grid_spacing)*1.5).'vw;
                }
                .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] {
                  margin-left: -'.(floatval($post_grid_spacing)*1.5).'vw;
                  margin-right: -'.(floatval($post_grid_spacing)*1.5).'vw;
                }
                .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] {
                  margin: '.(floatval($post_grid_spacing)*1.5).'vw;
                }
              }

              .nectar-post-grid[data-columns="4"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                width: calc(25% - '.(floatval($post_grid_spacing)*2).'vw);
              }
              .nectar-post-grid[data-columns="3"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                width: calc(33.32% - '.(floatval($post_grid_spacing)*2).'vw);
              }
              .nectar-post-grid[data-columns="2"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                width: calc(50% - '.(floatval($post_grid_spacing)*2).'vw);
              }

              .nectar-post-grid-wrap[data-style="content_under_image"] .nectar-post-grid:not([data-card="yes"]) .nectar-post-grid-item .content {
                padding-bottom: 0;
              }
              
              @media only screen and (max-width: 999px) and (min-width: 691px) {
                body .nectar-post-grid[data-columns][data-grid-spacing="'.esc_attr($post_grid_spacing).'"]:not([data-columns="1"]):not([data-masonry="yes"]) .nectar-post-grid-item {
                  width: calc(50% - '.(floatval($post_grid_spacing)*2).'vw);
                }
              }';

          }

        }

        // Author.
        if ( isset($atts['display_author']) && 'yes' === $atts['display_author'] ) {
          self::$element_css[] = '.nectar-post-grid-item .meta-author,
          .nectar-post-grid-item .meta-author-inner,
          .nectar-post-grid-item .meta-author a {
            display: flex;
            align-items: center;
          }
          .nectar-post-grid-item .meta-author img {
            border-radius: 40px;
            width: 24px;
            height: 24px;
            margin-bottom: 0;
            margin-right: 8px;
          }
          .nectar-post-grid-item .post-heading-wrap .meta-author {
            margin-top: 10px;
          }
          .nectar-post-grid-item .post-heading-wrap .meta-author span {
            transition: none;
          }
          .nectar-post-grid-item .post-heading-wrap .meta-author .meta-author-name {
            transition: color 0.45s cubic-bezier(.15,.75,.5,1) 0s;
          }
          [data-style="vertical_list"] .nectar-post-grid-item__meta-wrap:empty {
            display: none;
          }
          ';

          $author_position = ( isset($atts['author_position']) ) ? $atts['author_position'] : 'default';
          if ( 'multiline' === $author_position ) {
            self::$element_css[] = '.nectar-post-grid-item .item-main > .meta-author {
              display: flex;
              align-items: center;
              margin-top: 15px;
              position: relative;
              z-index: 11;
              pointer-events: none;
            }
            .nectar-post-grid-item .item-main > .meta-author a {
              pointer-events: all;
            }
            .nectar-post-grid-item .item-main > .meta-author img {
              width: 42px;
              height: 42px;
              margin-right: 15px;
            }
            .nectar-post-grid-item__meta-wrap:empty {
              margin-top: 0;
            }
            .nectar-post-grid-item .item-main > .meta-author .meta-author-name {
              font-size: 1em!important;
              line-height: 1.6em;
            }
            .nectar-post-grid-item .item-main > .meta-author > .meta-author-inner {
              flex-direction: column;
              align-items: flex-start;
            }
            .nectar-post-grid-item .item-main > .meta-author > .meta-author-inner > span {
              font-size: 0.8em;
            }
            .nectar-post-grid-item .item-main > .meta-author .meta-date {
              margin-top: 0;
            }
            ';
          }

        }

        // Carousel 
       if ( isset($atts['display_type']) && 'carousel' === $atts['display_type'] ) {

        // carousel core.
        self::$element_css[] = '

        .nectar-flickity.nectar-post-grid {
          display: block;
          transition: none;
        }

        .nectar-flickity.nectar-post-grid.flickity-enabled.is-draggable .flickity-viewport.is-pointer-down a {
          cursor: -webkit-grabbing;
          cursor: grabbing;
        }

        body:not(.compose-mode) .nectar-flickity.nectar-post-grid .flickity-slider {
          display: flex;
          flex-wrap: nowrap;
        }

        
        .nectar-flickity.nectar-post-grid:not(.flickity-enabled) .flickity-slider {
          position: relative;
        }

        .nectar-flickity.nectar-post-grid:not(.flickity-enabled) .flickity-slider,
        .nectar-flickity.nectar-post-grid:not(.flickity-enabled) .flickity-viewport {
          height: auto;
        }

        #ajax-content-wrap .nectar-post-grid[data-wrap="no-wrap"]:not(.flickity-enabled) .flickity-slider .nectar-post-grid-item:first-child  {
          margin-left: 0;
        }

        .nectar-flickity.nectar-post-grid:not(.flickity-enabled) .nectar-post-grid-item {
          flex: 0 0 auto;
        }
      
        .nectar-post-grid .flickity-viewport {
          width: 100%;
        }

        #ajax-content-wrap .nectar-post-grid .flickity-viewport .nectar-post-grid-item {
          transition: none; 
        }

        .nectar-post-grid[data-wrap="no-wrap"] .flickity-viewport .flickity-slider > div:first-child {
          margin-left: 0;
        }
        .nectar-post-grid[data-wrap="no-wrap"] .flickity-viewport .flickity-slider > div:last-child {
          margin-right: 0;
        }

        #ajax-content-wrap [data-style="content_overlaid"] .nectar-post-grid .flickity-viewport .nectar-post-grid-item .content,
        .flickity-viewport .nectar-post-grid-item .bg-overlay,
        .nectar-post-grid[data-text-layout="all_bottom_left_shadow"] .flickity-viewport  .nectar-post-grid-item div.inner:before,
        [data-style="content_under_image"] .category-position-overlaid .flickity-viewport .nectar-post-grid-item .meta-category {
          transform: translateZ(0);
        }

        #ajax-content-wrap .nectar-post-grid .flickity-page-dots {
          bottom: 0;
        }
        #ajax-content-wrap .nectar-post-grid[data-controls] .flickity-viewport {
          margin-top: 0;
          margin-bottom: 0;
        }
        #ajax-content-wrap .nectar-post-grid[data-controls="default"] .flickity-viewport {
          margin-top: 0;
          margin-bottom: 50px;
        }
        #ajax-content-wrap .nectar-post-grid.nectar-flickity {
          margin: 0;
        }
       .nectar-post-grid[data-animation="fade-in-from-bottom"] .flickity-viewport .nectar-post-grid-item .inner {
           transform: translateY(50px);
           opacity: 0;
           transition: 
              transform 0.75s cubic-bezier(0.22, 0.61, 0.36, 1), 
              opacity 0.75s cubic-bezier(0.22, 0.61, 0.36, 1);
        }
        .nectar-post-grid[data-animation="fade-in-from-bottom"] .flickity-viewport .nectar-post-grid-item.animated-in .inner {
          transform: translateY(0px);
          opacity: 1;
       }
       
       .nectar-post-grid[data-animation="fade-in-from-right"] .flickity-viewport .nectar-post-grid-item .inner {
        transform: translateX(50px);
        opacity: 0;
        transition: 
           transform 0.75s cubic-bezier(0.22, 0.61, 0.36, 1), 
           opacity 0.75s cubic-bezier(0.22, 0.61, 0.36, 1);
     }
     .nectar-post-grid[data-animation="fade-in-from-right"] .flickity-viewport .nectar-post-grid-item.animated-in .inner {
       transform: translateY(0px);
       opacity: 1;
    }';

        // Spacing.
        if( isset($post_grid_spacing) && in_array($post_grid_spacing, array('5px','10px','15px','20px','25px','30px', '35px', '40px', '45px')) ) {

          $desktop_cols           = ( isset($atts['columns']) ) ? $atts['columns'] : '4';
          $small_desktop_cols     = ( isset($atts['desktop_small_cols_flickity']) ) ? $atts['desktop_small_cols_flickity'] : 'default';
          $tablet_cols            = ( isset($atts['tablet_cols_flickity']) ) ? $atts['tablet_cols_flickity'] : 'default';
          $phone_cols             = ( isset($atts['phone_cols_flickity']) ) ? $atts['phone_cols_flickity'] : 'default';
          $desktop_cols_mod       = intval($desktop_cols) - 1;
          $small_desktop_cols_mod = max(intval($small_desktop_cols) - 1, 1);
          $tablet_cols_mod        = max(intval($tablet_cols) - 1, 1);
          $phone_cols_mod         = max(intval($phone_cols) - 1, 1);
          
      

          // Basic calc for space between = ((colNum - 1) x spacing) x 2

          // Desktop.
          if( in_array($desktop_cols, array('2','3','4','5','6')) ) {
            self::$element_css[] = '
            @media only screen and (min-width:1300px) {
              .nectar-post-grid.nectar-flickity[data-columns="'.esc_attr($desktop_cols).'"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                width: calc((100% - '. ( intval($desktop_cols_mod) * intval($post_grid_spacing) * 2 ) .'px) / '.esc_attr($desktop_cols).');
              }
            }';

            if( $small_desktop_cols === 'default' ) {
              self::$element_css[] = '
              @media only screen and (min-width:1000px) {
                .nectar-post-grid.nectar-flickity[data-columns="'.esc_attr($desktop_cols).'"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: calc((100% - '. ( intval($desktop_cols_mod) * intval($post_grid_spacing) * 2 ) .'px) / '.esc_attr($desktop_cols).');
                }
              }';
            }
          }

          // Small desktop.
          if( in_array($small_desktop_cols, array('1','2','3','4','5','6')) ) {
            if( $small_desktop_cols == '1' ) {
              self::$element_css[] = '
              @media only screen and (max-width:1299px) and (min-width:1000px) {
                .nectar-post-grid.nectar-flickity.sm-desktop-cols-'.$small_desktop_cols.'[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: 100%;
                }
              }';
            } else {
              self::$element_css[] = '
              @media only screen and (max-width:1299px) and (min-width:1000px) {
                .nectar-post-grid.nectar-flickity.sm-desktop-cols-'.$small_desktop_cols.'[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: calc((100% - '. ( intval($small_desktop_cols_mod) * intval($post_grid_spacing) * 2 ) .'px) / '.esc_attr($small_desktop_cols).');
                }
              }';
            }
            
          }

          // Tablet.
          if( in_array($tablet_cols, array('1','2','3','4','5','6')) ) {
    
            if( $tablet_cols == '1' ) {
              self::$element_css[] = '
              @media only screen and (max-width:999px) {
                #ajax-content-wrap .nectar-post-grid.nectar-flickity.tablet-cols-'.$tablet_cols.'[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: 100%;
                }
              }';
            }
            else {
              self::$element_css[] = '
              @media only screen and (max-width:999px) {
                #ajax-content-wrap .nectar-post-grid.nectar-flickity.tablet-cols-'.$tablet_cols.'[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: calc((100% - '. ( intval($tablet_cols_mod) * intval($post_grid_spacing) * 2 ) .'px) / '.esc_attr($tablet_cols).');
                }
              }';
            }
            
            
          }


          // Phone.
          if( in_array($phone_cols, array('1','2','3','4','5','6')) ) {
            self::$element_css[] = '
            @media only screen and (max-width:690px) {
              body #ajax-content-wrap .nectar-post-grid.nectar-flickity.phone-cols-'.$phone_cols.'[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                width: calc((100% - '. ( intval($phone_cols_mod) * intval($post_grid_spacing) * 2 ) .'px) / '.esc_attr($phone_cols).');
              }
            }';
          }

       


        } // End carousel spacing.

        // Drag indicator.
        if (isset($atts['flickity_touch_total_style']) && 'tooltip_text' === $atts['flickity_touch_total_style']) {
              self::$element_css[] = self::get_drag_indicator_tooltip_css();
        }

        if (isset($atts['flickity_touch_total_style']) && 'solid_bg' === $atts['flickity_touch_total_style']) {
          self::$element_css[] = '
            .nectar-drag-indicator[data-type="solid"] {
              mix-blend-mode: normal;
              left: -47px;
              top: -47px;
              width: 94px;
              height: 94px;
            }
            .nectar-drag-indicator[data-type="solid"] .color-circle {
              background-color: #000;
              width: 100%;
              height: 100%;
              display: block;
              position: absolute;
              left: -2px;
              top: -2px;
              transform: scale(0.2);
              transition: transform 0.45s ease, opacity 0.3s ease;
              opacity: 0;
              border-radius: 50%;
            }

            .nectar-drag-indicator[data-type="solid"].visible .color-circle{
              transform: scale(1);
              opacity: 1;
            }
            .nectar-drag-indicator[data-type="solid"] i {
              font-size: 21px;
              top: -13px;
            }
            
            .nectar-drag-indicator[data-type="solid"]:before {
              display: none;
            }
            .nectar-drag-indicator[data-type="solid"].visible i {
              transition: transform 0.45s ease, opacity 0.3s ease, color 0.3s ease;
            }
            .nectar-drag-indicator[data-type="solid"] i.fa-angle-left {
              left: 23px;
            }
            .nectar-drag-indicator[data-type="solid"] i.fa-angle-right {
              right: 27px;
            }
            .nectar-drag-indicator.visible.pointer-down[data-type="solid"] .color-circle {
              transform: scale(0.15);
            }
            .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i {
              color: inherit!important;
            }
      
            .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i.fa-angle-left {
              transform: translateX(-10px);
            }
            .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i.fa-angle-right {
              transform: translateX(10px);
            }
          ';
          }
       } // end carousel.

       // Mobile columns -- non carousel.
       if ( isset($atts['columns_tablet']) && 'default' !== $atts['columns_tablet'] ) {
          $tablet_cols = $atts['columns_tablet'];
          if ( $tablet_cols === '1' ) {
            self::$element_css[] = '
            @media only screen and (max-width:999px) {
              body .nectar-post-grid.tablet-cols-1 .nectar-post-grid-item {
               width: 100%!important;
              }
            }
            ';
          }
        }
        if ( isset($atts['columns_phone']) && 'default' !== $atts['columns_phone'] ) {
          $phone_cols = $atts['columns_phone'];
          if ( $phone_cols === '2' ) {

            if( in_array($post_grid_spacing, array( '1vw', '2vw', '3vw', '4vw')) ) {
              self::$element_css[] = '
                @media only screen and (max-width:690px) {
                  body .nectar-post-grid.phone-cols-2[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: calc(50% - '.(floatval($post_grid_spacing)*2).'vw)!important;
                  margin: '.$post_grid_spacing.'!important;
                  }
                }';
            }
            else {
              self::$element_css[] = '
                @media only screen and (max-width:690px) {
                  body .nectar-post-grid.phone-cols-2[data-grid-spacing="'.esc_attr($post_grid_spacing).'"] .nectar-post-grid-item {
                  width: calc(50% - '.(floatval($post_grid_spacing)*2).'px)!important;
                  margin: '.$post_grid_spacing.'!important;
                  }
                }';
            }
            
          }
        }


       // Masonry Layout Variants.
       $additional_meta_dispay = ( isset($atts['additional_meta_display']) ) ? $atts['additional_meta_display'] : 'default';
       if( $post_grid_masonry ) {

        // 3 columns ==============================
          if( isset($atts['columns']) && '3' === $atts['columns'] ) {

            $masonry_variant = ( isset($atts['3_col_masonry_layout']) ) ? $atts['3_col_masonry_layout'] : 'default';
            // vert staggered ==============================
            if( 'vert_staggered' === $masonry_variant ) {

              // core.
              self::$element_css[] = '
              @media only screen and (min-width: 691px) {

                #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered {
                  grid-auto-flow: dense;
                }
               
                #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered > div {
                  grid-column-end: span 1;

                }
                #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) {
                  grid-column-end: span 2;
                  grid-row-end: span 2;
                }
              }
              @media only screen and (min-width: 1000px) {
                #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) {
                  grid-column-end: span 2;
                  grid-row-end: span 2;
                }
              }';

              // Limit meta display.
              if ( 'large_items_only' === $additional_meta_dispay ) {
                self::$element_css[] = '
                @media only screen and (min-width: 1000px) {
                  .nectar-post-grid[data-columns="3"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 5) [class*="meta-"]:not(.meta-category) {
                    display: flex;
                  }
                  .nectar-post-grid[data-columns="3"].layout-vert_staggered[data-text-align="center"] > div:nth-child(6n + 5) [class*="meta-"]:not(.meta-category) {
                    justify-content: center;
                    display: flex;
                  }
                }

                #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 1) .nectar-post-grid-item__read-more {
                  display: none;
                }

                .nectar-post-grid[data-columns="3"].layout-vert_staggered.meta-display-large_items_only > div [class*="meta-"]:not(.meta-category) {
                  display: none;
                }
                .nectar-post-grid[data-columns="3"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 1) [class*="meta-"]:not(.meta-category) {
                  display: flex;
                }
                .nectar-post-grid[data-columns="3"].layout-vert_staggered[data-text-align="center"] > div:nth-child(6n + 1) [class*="meta-"]:not(.meta-category) {
                  justify-content: center;
                  display: flex;
                }
                ';
              }

              
              if( $post_grid_style === 'content_under_image' || $post_grid_style === 'content_overlaid' ) {
                  self::$element_css[] = '

                  @media only screen and (min-width: 1000px) {


                    .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .nectar-post-grid-item-bg-wrap {
                      flex: 1;
                    }

                    .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .nectar-post-grid-item-bg {
                      height: 100%;
                    }

                    [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .post-heading a,
                    [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .post-heading a {
                      font-size: 2em;
                      line-height: 1.3em;
                    }

                    #ajax-content-wrap [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .meta-category,
                    #ajax-content-wrap [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .meta-category {
                      position: relative;
                      top: auto;
                      left: auto;
                    }

                    #ajax-content-wrap [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .meta-excerpt {
                      font-size: 1.3em;
                      line-height: 1.6em;
                      max-width: 75%;
                    }
  
  
                    [data-text-layout*="all_bottom_left"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content {
                      top: auto;
                      left: 0;
                      transform: none;
                      padding: 35px;
                      max-width: 85%;
                    }

                    [data-text-align="center"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content,
                    [data-text-layout="all_middle"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content {
                      top: 50%;
                      left: 50%;
                      transform: translate(-50%, -50%);
                    }
                    
                    [data-columns="3"].layout-vert_staggered:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(6n + 5) div.inner:before {
                      position: absolute;
                      top: 0;
                      left: 0;
                      content: "";
                      display: block;
                      width: 100%;
                      height: 100%;
                      z-index: 10;
                      pointer-events: none;
                      border-radius: var(--post-grid-border-radius);
                      background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                    }

                  }

                  @media only screen and (min-width: 1000px) {
                    .top-level .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-el-parallax-scroll .nectar-post-grid-item-bg-wrap-inner,
                    .top-level .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .nectar-el-parallax-scroll .nectar-post-grid-item-bg-wrap-inner {
                      transform: scale(1.09);
                    }
                  }

                  .nectar-post-grid[data-columns="3"].layout-vert_staggered div.inner,
                  .nectar-post-grid[data-columns="3"].layout-vert_staggered .nectar-post-grid-item-bg-wrap-inner{
                    height: 100%;
                  }

                  .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                    flex: 1;
                  }

                  .nectar-post-grid.layout-vert_staggered .nectar-post-grid-item .content .post-heading {
                    max-width: 100%;
                  }

                
                  @media only screen and (min-width: 691px) {
                    .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg {
                      height: 100%;
                    }
                  }

                  
                  @media only screen and (max-width: 999px) and (min-width: 691px) {
,
                    #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                      position: relative;
                      padding-bottom: 5%;
                    }

                    #ajax-content-wrap .nectar-post-grid[data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                      position: absolute;
                      top: 0;
                      left: 0;
                      width: 100%;
                      height: 100%;
                    }
                  }


                  #ajax-content-wrap [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .meta-excerpt {
                    font-size: 1.3em;
                    line-height: 1.6em;
                    max-width: 75%;
                  }


                  [data-text-layou*="all_bottom_left"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                    top: auto;
                    left: 0;
                    transform: none;
                    padding: 35px;
                    max-width: 85%;
                  }
                  

                  [data-text-align="center"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content,
                  [data-text-layout="all_middle"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                  }
                  
                  [data-columns="3"].layout-vert_staggered:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(6n + 1) div.inner:before {
                    position: absolute;
                    top: 0;
                    left: 0;
                    content: "";
                    display: block;
                    width: 100%;
                    height: 100%;
                    z-index: 10;
                    pointer-events: none;
                    border-radius: var(--post-grid-border-radius);
                    background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                  }

                  @media only screen and (max-width: 999px) {
                    [data-style="content_overlaid"] [data-text-layout*="left"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                      padding: 35px;
                    }
                  }

                  
                
                  ';
                  if( in_array($post_grid_br, array('3px','5px','10px','15px')) ) {
                    self::$element_css[] = '
                    [data-border-radius="'.$post_grid_br.'"][data-columns="3"].layout-vert_staggered > div div.inner:before {
                      border-radius: '.esc_attr($post_grid_br).';
                    }
                    ';
                  }
              }

              // specific to content overlaid.
              if( $post_grid_style === 'content_overlaid' ) {
                self::$element_css[] = '
                @media only screen and (min-width: 1000px) {
                  [data-style="content_overlaid"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content {
                    position: absolute;
                    top: 0;
                    left: 0;
                    padding: 0;
                    padding-right: 30%;
                    padding-top: min( max(35px, 3.5vw), 50px);
                    padding-left: min( max(35px, 3.5vw), 50px);
                  }
                  [data-style="content_overlaid"] [data-text-layout*="all_bottom_left"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content {
                    top: auto;
                    padding-bottom: min( max(35px, 3.5vw), 50px);
                    max-width: 100%;
                  }
                  [data-style="content_overlaid"] [data-text-layout="all_middle"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content {
                    top: 50%;
                    left: 50%;
                    padding-top: 0;
                    padding-left: 10%;
                    padding-right: 10%;
                  }
                }

                [data-style="content_overlaid"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  position: absolute;
                  top: 0;
                  left: 0;
                  padding: 0;
                  padding-right: 30%;
                  padding-top: min( max(35px, 3.5vw), 50px);
                  padding-left: min( max(35px, 3.5vw), 50px);
                }

                [data-style="content_overlaid"] [data-text-layout*="all_bottom_left"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  top: auto;
                  padding-bottom: min( max(35px, 3.5vw), 50px);
                }
                [data-style="content_overlaid"] [data-text-layout="all_middle"][data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  top: 50%;
                  left: 50%;
                  padding-top: 0;
                  padding-left: 10%;
                  padding-right: 10%;
                }';

              }
               // specific to content under featured image.
              if( $post_grid_style === 'content_under_image' ) {
                self::$element_css[] = '

                @media only screen and (min-width: 1000px) {

                  #ajax-content-wrap [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content * {
                    color: #fff;
                  }

                  [data-columns="3"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 5) .post-heading {
                    background-image: linear-gradient(to right,#fff 0,#fff 100%);
                  }

                  [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 5):hover .content .nectar-split-heading.effect-removed {
                    text-decoration: underline 1px rgba(255, 255, 255, 1)
                  }

                  .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .content {
                    padding: min( max(35px, 3.5vw), 50px);
                  }


                  [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .nectar-post-grid-item-bg-wrap {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                  }
                  [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 5) .nectar-post-grid-item-bg {
                    height: 100%!important;
                  }

                }


                #ajax-content-wrap [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content * {
                  color: #fff;
                }

                [data-columns="3"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 1) .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }

                [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 1):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }

                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }

                @media only screen and (min-width: 691px) {
                  .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                    max-width: 70%;
                  }
                }

                [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                } 
                [data-style="content_under_image"] [data-columns="3"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
                ';
              }
              

            } // end vert staggered variant.
            
          } // end 3 column variants.



          // 4 columns ==============================
          if( isset($atts['columns']) && '4' === $atts['columns'] ) {
            $masonry_variant = ( isset($atts['4_col_masonry_layout']) ) ? $atts['4_col_masonry_layout'] : 'default';
            
            // mixed style  ==============================
            if( 'mixed' === $masonry_variant ) {

              // Limit meta display.
              if ( 'large_items_only' === $additional_meta_dispay ) {
                self::$element_css[] = '
               

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed.meta-display-large_items_only > div:nth-child(10n + 1) .nectar-post-grid-item__read-more,
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed.meta-display-large_items_only > div:nth-child(10n + 8) .nectar-post-grid-item__read-more {
                  display: none;
                }

                .nectar-post-grid[data-columns="4"].layout-mixed.meta-display-large_items_only > div [class*="meta-"]:not(.meta-category) {
                  display: none;
                }
                .nectar-post-grid[data-columns="4"].layout-mixed.meta-display-large_items_only > div:nth-child(10n + 1) [class*="meta-"]:not(.meta-category),
                .nectar-post-grid[data-columns="4"].layout-mixed.meta-display-large_items_only > div:nth-child(10n + 8) [class*="meta-"]:not(.meta-category) {
                  display: flex;
                }
                .nectar-post-grid[data-columns="4"].layout-mixed[data-text-align="center"] > div:nth-child(10n + 1) [class*="meta-"]:not(.meta-category),
                .nectar-post-grid[data-columns="4"].layout-mixed[data-text-align="center"] > div:nth-child(10n + 8) [class*="meta-"]:not(.meta-category) {
                  justify-content: center;
                  display: flex;
                }
                ';
              }


              // core.
              self::$element_css[] = '
              @media only screen and (min-width: 691px) {

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed {
                  grid-auto-flow: dense;
                }
               
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed > div {
                  grid-column-end: span 1;

                }
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 1),
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 8) {
                  grid-column-end: span 2;
                  grid-row-end: span 2;
                }
              }


              @media only screen and (min-width: 1000px) {
                [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .post-heading a {
                  font-size: 2em;
                  line-height: 1.3em;
                }

                #ajax-content-wrap [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .meta-category {
                  position: relative;
                  top: auto;
                  left: auto;
                }

                #ajax-content-wrap [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .meta-excerpt {
                  font-size: 1.2em;
                  line-height: 1.6em;
                  max-width: 75%;
                }
               
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content,
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content  {
                  max-width: 80%;
                }
  
              }

              @media only screen and (max-width: 999px) {
                [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .post-heading a {
                  font-size: 1.4em;
                  line-height: 1.3em;
                }
              }

              @media only screen and (min-width: 1px) {

                .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }
                .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg {
                  height: 100%;
                }

  
                [data-text-layout*="all_bottom_left"][data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content {
                  top: auto;
                  left: 0;
                  transform: none;
                  padding: 35px;
                  padding-right: 15%;
                  max-width: 100%;
                }
  
                [data-text-align="center"][data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content {
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                }
                
                [data-columns="4"].layout-mixed:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(10n + 1) div.inner:before {
                  position: absolute;
                  top: 0;
                  left: 0;
                  content: "";
                  display: block;
                  width: 100%;
                  height: 100%;
                  z-index: 10;
                  pointer-events: none;
                  border-radius: var(--post-grid-border-radius);
                  background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                }
  
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content * {
                  color: #fff;
                }
  
                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div.nectar-underline:nth-child(10n + 1):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }

                [data-columns="4"].layout-mixed > div:nth-child(10n + 1).nectar-underline .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }
  
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                  min-height: 45vh;
                }
  
  
                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                }
                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
  
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content * {
                  color: #fff;
                }

                [data-columns="4"].layout-mixed > div:nth-child(10n + 1).nectar-underline .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }
                
                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div.nectar-underline:nth-child(10n + 1):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }
  
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }
  
          
  
                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                } 
                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
  
                .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

              }



              @media only screen and (min-width: 1000px) {
                
                .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

                .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg {
                  height: 100%;
                }


                [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .post-heading a {
                  font-size: 2em;
                  line-height: 1.3em;
                }

                #ajax-content-wrap [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .meta-category {
                  position: relative;
                  top: auto;
                  left: auto;
                }

                #ajax-content-wrap [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .meta-excerpt {
                  font-size: 1.2em;
                  line-height: 1.6em;
                  max-width: 75%;
                }


                [data-text-layout*="all_bottom_left"][data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content {
                  top: auto;
                  left: 0;
                  transform: none;
                  padding: 35px;
                  padding-right: 15%;
                  max-width: 100%;
                }


                [data-text-layout="all_middle"][data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content {
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                }
                
                [data-columns="4"].layout-mixed:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(10n + 8) div.inner:before {
                  position: absolute;
                  top: 0;
                  left: 0;
                  content: "";
                  display: block;
                  width: 100%;
                  height: 100%;
                  z-index: 10;
                  pointer-events: none;
                  border-radius: var(--post-grid-border-radius);
                  background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                }

                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content * {
                  color: #fff;
                }

                [data-columns="4"].layout-mixed > div:nth-child(10n + 8).nectar-underline .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }

                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div.nectar-underline:nth-child(10n + 8):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }


                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                  max-width: 70%;
                }
                

                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                }

                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg  {
                  height: 100%!important;
                }

              
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content * {
                  color: #fff;
                }
                

                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div.nectar-underline:nth-child(10n + 8):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }

                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }

              
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .content {
                  max-width: 80%;
                }
                


                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                } 

                [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }

                .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 8) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

              }


              @media only screen and (min-width: 1000px) {
                .top-level [data-style="content_under_image"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .nectar-el-parallax-scroll .nectar-post-grid-item-bg-wrap-inner {
                  transform: scale(1.09);
                }
              }


              @media only screen and (max-width: 999px) {
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-mixed > div:nth-child(10n + 8) {
                  grid-column-end: span 1;
                  grid-row-end: span 1;
                }

                [data-style="content_overlaid"] [data-columns="4"].layout-mixed > div:nth-child(10n + 1) .post-heading a {
                  font-size: 1em;
                }

              }


              .nectar-post-grid[data-columns="4"].layout-mixed div.inner,
              .nectar-post-grid[data-columns="4"].layout-mixed .nectar-post-grid-item-bg-wrap-inner{
                height: 100%;
              }
              
              .nectar-post-grid[data-columns="4"].layout-mixed .nectar-post-grid-item .content .post-heading {
                max-width: 100%;
              }
              
              ';

            } // end mixed variant




            // 4 col staggered variant ==============================================
            if( 'vert_staggered' === $masonry_variant ) {

              // Limit meta display.
              if ( 'large_items_only' === $additional_meta_dispay ) {
                self::$element_css[] = '
               

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 1) .nectar-post-grid-item__read-more,
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 6) .nectar-post-grid-item__read-more {
                  display: none;
                }

                .nectar-post-grid[data-columns="4"].layout-vert_staggered.meta-display-large_items_only > div [class*="meta-"]:not(.meta-category) {
                  display: none;
                }
                .nectar-post-grid[data-columns="4"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 1) [class*="meta-"]:not(.meta-category),
                .nectar-post-grid[data-columns="4"].layout-vert_staggered.meta-display-large_items_only > div:nth-child(6n + 6) [class*="meta-"]:not(.meta-category) {
                  display: flex;
                }
                .nectar-post-grid[data-columns="4"].layout-vert_staggered[data-text-align="center"] > div:nth-child(6n + 1) [class*="meta-"]:not(.meta-category),
                .nectar-post-grid[data-columns="4"].layout-vert_staggered[data-text-align="center"] > div:nth-child(6n + 6) [class*="meta-"]:not(.meta-category) {
                  justify-content: center;
                  display: flex;
                }
                ';
              }


              // core.
              self::$element_css[] = '
              @media only screen and (min-width: 691px) {

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered {
                  grid-auto-flow: dense;
                }
               
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered > div {
                  grid-column-end: span 1;

                }
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1),
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) {
                  grid-column-end: span 2;
                  grid-row-end: span 1;
                }
              }


              @media only screen and (min-width: 1000px) {
                [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .post-heading a {
                  font-size: 2em;
                  line-height: 1.3em;
                }

                #ajax-content-wrap [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .meta-category {
                  position: relative;
                  top: auto;
                  left: auto;
                }

                #ajax-content-wrap [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .meta-excerpt {
                  font-size: 1.2em;
                  line-height: 1.6em;
                  max-width: 75%;
                }
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  max-width: 80%;
                }
  
              }


              @media only screen and (min-width: 691px) {
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  max-width: 80%;
                }
              }


              @media only screen and (min-width: 1px) {

                .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }
                .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg {
                  height: 100%;
                  padding: 0;
                }

  
                [data-text-layout*="all_bottom_left"][data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  top: auto;
                  left: 0;
                  transform: none;
                  padding: 35px;
                  max-width: 100%;
                  padding-right: 15%;
                }
  
                [data-text-align="center"][data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                }
                
                [data-columns="4"].layout-vert_staggered:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(6n + 1) div.inner:before {
                  position: absolute;
                  top: 0;
                  left: 0;
                  content: "";
                  display: block;
                  width: 100%;
                  height: 100%;
                  z-index: 10;
                  pointer-events: none;
                  border-radius: var(--post-grid-border-radius);
                  background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                }
  
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content * {
                  color: #fff;
                }
                
                [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1).nectar-underline .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }
  
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 1):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }
  
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }
  
  
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                }
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
  
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content * {
                  color: #fff;
                }
                
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 1):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }
  
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }
  
               
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                } 
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
  
                .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

              }



              @media only screen and (min-width: 1000px) {
                
                .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

                .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg {
                  height: 100%;
                  padding: 0;
                }


                [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .post-heading a {
                  font-size: 2.5em;
                  line-height: 1.3em;
                }

                #ajax-content-wrap [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .meta-category {
                  position: relative;
                  top: auto;
                  left: auto;
                }

                #ajax-content-wrap [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .meta-excerpt {
                  font-size: 1.2em;
                  line-height: 1.6em;
                  max-width: 75%;
                }


                [data-text-layout*="all_bottom_left"][data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content {
                  top: auto;
                  left: 0;
                  transform: none;
                  padding: 35px;
                  max-width: 100%;
                  padding-right: 15%;
                }


                [data-text-layout="all_middle"][data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content {
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                }
                
                [data-columns="4"].layout-vert_staggered:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(6n + 6) div.inner:before {
                  position: absolute;
                  top: 0;
                  left: 0;
                  content: "";
                  display: block;
                  width: 100%;
                  height: 100%;
                  z-index: 10;
                  pointer-events: none;
                  border-radius: var(--post-grid-border-radius);
                  background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                }

                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content * {
                  color: #fff;
                }

                [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6).nectar-underline .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }


                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 6):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }


                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }
                

                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                }

                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg  {
                  height: 100%!important;
                }

              
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content * {
                  color: #fff;
                }
                

                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div.nectar-underline:nth-child(6n + 6):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }

                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }

              
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .content {
                  max-width: 80%;
                }
                


                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                } 

                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }

                .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

              }



              @media only screen and (max-width: 999px) {
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 6) {
                  grid-column-end: span 1;
                  grid-row-end: span 1;
                }

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  padding-bottom: 20vw;
                  max-width: 100%;
                }
                #ajax-content-wrap .nectar-post-grid[data-columns="4"][data-text-layout="all_middle"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  padding: 35px;
                }
                #ajax-content-wrap .nectar-post-grid[data-columns="4"][data-text-layout*="all_bottom_left"].layout-vert_staggered > div:nth-child(6n + 1) .content {
                  padding: 35px;
                  max-width: 100%;
                }
                
              }


            
              .nectar-post-grid[data-columns="4"].layout-vert_staggered div.inner,
              .nectar-post-grid[data-columns="4"].layout-vert_staggered .nectar-post-grid-item-bg-wrap-inner{
                height: 100%;
              }
              
              .nectar-post-grid[data-columns="4"].layout-vert_staggered .nectar-post-grid-item .content .post-heading {
                max-width: 100%;
              }
              
              ';

            } // end staggered variant



            // 4 col staggered variant ==============================================
            if( 'vert_staggered_middle' === $masonry_variant ) {

              // Limit meta display.
              if ( 'large_items_only' === $additional_meta_dispay ) {
                self::$element_css[] = '
               

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle.meta-display-large_items_only > div:nth-child(7n + 2) .nectar-post-grid-item__read-more {
                  display: none;
                }

                .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle.meta-display-large_items_only > div [class*="meta-"]:not(.meta-category) {
                  display: none;
                }
                .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle.meta-display-large_items_only > div:nth-child(7n + 2) [class*="meta-"]:not(.meta-category) {
                  display: flex;
                }
                .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle[data-text-align="center"] > div:nth-child(7n + 2) [class*="meta-"]:not(.meta-category) {
                  justify-content: center;
                  display: flex;
                }
                ';
              }


              // core.
              self::$element_css[] = '
              @media only screen and (min-width: 691px) {

                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle {
                  grid-auto-flow: dense;
                }
               
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle > div {
                  grid-column-end: span 1;

                }
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) {
                  grid-column-end: span 2;
                  grid-row-end: span 1;
                }
              }


              @media only screen and (min-width: 1000px) {
                [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .post-heading a {
                  font-size: 2em;
                  line-height: 1.3em;
                }

                #ajax-content-wrap [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .meta-category {
                  position: relative;
                  top: auto;
                  left: auto;
                }

                #ajax-content-wrap [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .meta-excerpt {
                  font-size: 1.2em;
                  line-height: 1.6em;
                  max-width: 75%;
                }
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  max-width: 80%;
                }
  
              }


              @media only screen and (min-width: 691px) {
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  max-width: 80%;
                }
              }


              @media only screen and (min-width: 1px) {

                .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }
                .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg {
                  height: 100%;
                  padding: 0;
                }

  
                [data-text-layout*="all_bottom_left"][data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  top: auto;
                  left: 0;
                  transform: none;
                  padding: 35px;
                  padding-right: 15%;
                  max-width: 100%;
                }
  
                [data-text-align="center"][data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                }
                
                [data-columns="4"].layout-vert_staggered_middle:not([data-text-layout="all_bottom_left_shadow"]) > div:nth-child(7n + 2) div.inner:before {
                  position: absolute;
                  top: 0;
                  left: 0;
                  content: "";
                  display: block;
                  width: 100%;
                  height: 100%;
                  z-index: 10;
                  pointer-events: none;
                  border-radius: var(--post-grid-border-radius);
                  background: radial-gradient(circle at 0 0, rgba(0,0,0,0.75) 0, transparent 70%);
                }
  
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content * {
                  color: #fff;
                }

                [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2).nectar-underline .post-heading {
                  background-image: linear-gradient(to right,#fff 0,#fff 100%);
                }
  
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div.nectar-underline:nth-child(7n + 2):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }
  
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }
  
  
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                }
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
  
                #ajax-content-wrap [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content * {
                  color: #fff;
                }
                
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div.nectar-underline:nth-child(7n + 2):hover .content .nectar-split-heading.effect-removed {
                  text-decoration: underline 1px rgba(255, 255, 255, 1)
                }
  
                .nectar-post-grid-wrap[data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  padding: min( max(35px, 3.5vw), 50px);
                }
  
               
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg-wrap {
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  top: 0;
                  left: 0;
                } 
                [data-style="content_under_image"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg {
                  height: 100%!important;
                }
  
                .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .nectar-post-grid-item-bg-wrap {
                  flex: 1;
                }

              }



              @media only screen and (max-width: 999px) {
  
                #ajax-content-wrap .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  padding-bottom: 20vw;
                  max-width: 100%;
                  min-height: 45vh;
                }
                [data-text-layout*="all_bottom_left"][data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content {
                  padding-right: 35px;
                }

                #ajax-content-wrap .nectar-post-grid[data-columns="4"][data-text-layout="all_middle"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content,
                #ajax-content-wrap .nectar-post-grid[data-columns="4"][data-text-layout*="bottom_left"].layout-vert_staggered_middle > div:nth-child(7n + 2) .content { 
                  padding-bottom: 35px;
                }

                [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .post-heading a {
                  font-size: 1.4em;
                  line-height: 1.3em;
                }
                [data-style="content_overlaid"] [data-columns="4"].layout-vert_staggered_middle > div:nth-child(7n + 2) .post-heading a {
                  font-size: 1em;
                }
                
              }


            
              .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle div.inner,
              .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle .nectar-post-grid-item-bg-wrap-inner{
                height: 100%;
              }
              
              .nectar-post-grid[data-columns="4"].layout-vert_staggered_middle .nectar-post-grid-item .content .post-heading {
                max-width: 100%;
              }
              
              ';

            } // end staggered alt variant



          }
          

        }


        // Post grid animation load in.
        if( isset($atts['animation']) && $atts['animation'] === 'zoom-out-reveal' ) {

          $cubic_beziers = nectar_cubic_bezier_easings();
          $cubic_bezier = '0.25,1,0.5,1';

          $animation_easing = 'default';
          if( isset($atts['animation_easing']) && !empty($atts['animation_easing']) && 'default' !== $atts['animation_easing'] ) {
            $animation_easing = $atts['animation_easing'];
          } 
          if( isset($cubic_beziers[$animation_easing]) ) {
            $cubic_bezier = $cubic_beziers[$animation_easing];
          }

          $post_grid_border_radius = '0px';
          if( in_array($post_grid_br, array('3px','5px','10px','15px')) ) {
            $post_grid_border_radius = $post_grid_br;
          }

          self::$element_css[] = '
          .nectar-post-grid-item .nectar-split-heading span .inner {
            position: relative;
            display: inline-block;
            transform: translateY(1.3em);
          }
          .nectar-post-grid-item .nectar-split-heading span {
            vertical-align: bottom;
          }
          .nectar-post-grid-item .nectar-split-heading > * > span {
              position: relative;
              display: inline-block;
              overflow: hidden;
              line-height: inherit;
          }

          .nectar-post-grid-item .nectar-split-heading:not(.markup-generated) {
            opacity: 0;
          }

          [data-style*="content"] .nectar-post-grid-item .nectar-split-heading .inner {
            background-color: transparent;
            overflow: visible;
            line-height: inherit;
          }
          #ajax-content-wrap [data-animation="zoom-out-reveal"] .nectar-underline .post-heading {
            background-image: none;
          }
          [data-style*="content"] [data-animation="zoom-out-reveal"] .nectar-post-grid-item.nectar-underline .nectar-split-heading {
              text-decoration: underline 1px rgba(255, 255, 255, 0);
              transition: text-decoration 0.2s ease, color 0.3s ease;
              text-underline-offset: 0.17em;
          }
        
          [data-style*="content"] [data-animation="zoom-out-reveal"] .nectar-post-grid-item.nectar-underline:hover .nectar-split-heading.effect-removed {
            text-decoration: underline 1px rgba(255, 255, 255, 1);
          }
          [data-style*="content"] [data-text-hover-color="dark"][data-animation="zoom-out-reveal"] .nectar-post-grid-item.nectar-underline:hover .nectar-split-heading.effect-removed {
            text-decoration: underline 1px rgba(0, 0, 0, 1);
          }

          [data-style*="content"] .nectar-post-grid-item .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-stagger="true"] span .inner {
            transition: transform 1.2s cubic-bezier(0.25,1,0.5,1) 0.6s,
                        opacity 1.2s cubic-bezier(0.25,1,0.5,1) 0.6s,
                        color 0.3s ease;
          }
          

            .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-category,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-cta,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .item-main > .meta-author,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-excerpt,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-post-grid-item__meta-wrap {
              opacity: 0;
              transform: translateY(20px);
              transition: transform 1.2s cubic-bezier(0.25,1,0.5,1) 0.7s, 
                          opacity 1.2s cubic-bezier(0.25,1,0.5,1) 0.7s;
            }
            [data-style*="content"] .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-post-grid-item-bg img {
              transform: scale(1.3);
              transition: transform 1.2s cubic-bezier('.$cubic_bezier.'), opacity 1.2s cubic-bezier('.$cubic_bezier.');
            }

            [data-style*="content"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap,
            [data-style*="content"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .inner:before {
              clip-path: inset(30px round '.$post_grid_border_radius.');
              opacity: 0;
              transition: opacity 1.2s cubic-bezier('.$cubic_bezier.'), transform 1.2s cubic-bezier('.$cubic_bezier.'), clip-path 1.2s cubic-bezier('.$cubic_bezier.');
            }
            

            [data-style*="content"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .animated-in .nectar-post-grid-item-bg-wrap,
            [data-style*="content"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .animated-in .inner:before {
              clip-path: inset(0 round '.$post_grid_border_radius.');
              opacity: 1;
            }';

            if( $post_grid_style === 'vertical_list' ) {
              self::$element_css[] = '
              [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-link-underline,
              [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .item-main:before,
              [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-post-grid-item__meta-wrap,
              [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .post-heading-wrap .meta-author {
                opacity: 0;
                transform: translateY(10px);
                transition: transform 1.2s cubic-bezier(0.25,1,0.5,1) 0.5s, 
                            opacity 1.2s cubic-bezier(0.25,1,0.5,1) 0.5s;
              }
              [data-style="vertical_list"] [data-animation="zoom-out-reveal"] .animated-in .nectar-link-underline,
              [data-style="vertical_list"] [data-animation="zoom-out-reveal"] .animated-in .item-main:before,
              [data-style="vertical_list"] [data-animation="zoom-out-reveal"] .animated-in .nectar-post-grid-item__meta-wrap,
              [data-style="vertical_list"] [data-animation="zoom-out-reveal"] .animated-in .post-heading-wrap .meta-author {
                opacity: 1;
                transform: translateY(0);
              }
              @media only screen and (max-width: 999px) {
                [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-category,
                [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-author,
                [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-link-underline,
                [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .item-main:before,
                [data-style="vertical_list"] .nectar-post-grid[data-animation="zoom-out-reveal"] .nectar-post-grid-item__meta-wrap {
                  transform: translateY(0)!important;
                }
              }';
            }

            if( $post_grid_style === 'content_overlaid' ) {
              self::$element_css[] = '

              [data-style="content_overlaid"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap-inner {
                height: 100%;
              }
              
              [data-style="content_overlaid"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] div.inner,
              [data-style="content_overlaid"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap-inner,
              [data-style="content_overlaid"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item:not(.animated-in) .bg-overlay {
                clip-path: inset(30px round '.$post_grid_border_radius.');
                opacity: 0;
                transition: opacity 1.2s cubic-bezier('.$cubic_bezier.'), transform 1.2s cubic-bezier('.$cubic_bezier.'), clip-path 1.2s cubic-bezier('.$cubic_bezier.');
              }

              [data-style="content_overlaid"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .animated-in div.inner,
              [data-style="content_overlaid"] .nectar-post-grid[data-animation="zoom-out-reveal"][data-border-radius="'.$post_grid_br.'"] .animated-in .nectar-post-grid-item-bg-wrap-inner {
                clip-path: inset(0 round '.$post_grid_border_radius.');
                opacity: 1;
              }';
            }
            
            self::$element_css[] = '
            body .nectar-post-grid[data-animation="zoom-out-reveal"] .animated-in .nectar-post-grid-item-bg img {
              transform: scale(1);
              opacity: 1;
            }
            .nectar-post-grid[data-animation="zoom-out-reveal"] .animated-in .meta-excerpt,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .animated-in .nectar-cta,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .animated-in .meta-category,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .animated-in .item-main > .meta-author,
            .nectar-post-grid[data-animation="zoom-out-reveal"] .animated-in .nectar-post-grid-item__meta-wrap {
              opacity: 1;
              transform: translateY(0);
            }';
        }



        // Font size.
        if( isset($atts['custom_font_size']) && !empty($atts['custom_font_size']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) { .nectar-post-grid.custom_font_size_'.esc_attr(self::decimal_unit_type_class($font_size)).' .post-heading {
            font-size: '.esc_attr($font_size).';
          } }';

        }

        $custom_font_sizing = self::font_size_group_styles('nectar-post-grid .nectar-post-grid-item .post-heading', 'custom_font_size', 'and (min-width: 1000px)', '', $atts);

        if ( !empty($custom_font_sizing) ) {
          self::$element_css[] = $custom_font_sizing;
        }


        // BG Opacity.
        self::$element_css[] = '
        .nectar-post-grid-item .bg-overlay[data-opacity="'.$post_grid_opacity.'"] { 
          opacity: '.$post_grid_opacity.'; 
        }
        
        .nectar-post-grid-item:hover .bg-overlay[data-hover-opacity="'.$post_grid_opacity_h.'"] { 
          opacity: '.$post_grid_opacity_h.'; 
        }';


        // BG Color Hover.
        if( isset($atts['card_bg_color_hover']) && !empty($atts['card_bg_color_hover']) ) {

          $card_hover_color = ltrim($atts['card_bg_color_hover'],'#');

          self::$element_css[] = '.nectar-post-grid[data-card="yes"].card_hover_color_'.esc_attr($card_hover_color).' .nectar-post-grid-item:hover {
            background-color: #'.esc_attr($card_hover_color) . esc_attr( $override ) .';
          }';

        }
        
        // Text Opacity.
        if( isset($atts['text_opacity']) && !empty($atts['text_opacity']) && '1' !== $atts['text_opacity'] ) {
          $text_opacity_selector = str_replace('.', '-', $atts['text_opacity']);
          self::$element_css[] = '.nectar-post-grid.text-opacity-'.esc_attr($text_opacity_selector).' .nectar-post-grid-item .content {
           opacity: '.esc_attr($atts['text_opacity']) .';
          }';
        }
        if( isset($atts['text_hover_opacity']) && !empty($atts['text_hover_opacity']) ) {
          $text_opacity_selector = str_replace('.', '-', $atts['text_hover_opacity']);
          self::$element_css[] = '.nectar-post-grid.text-opacity-hover-'.esc_attr($text_opacity_selector).' .nectar-post-grid-item:hover .content {
           opacity: '.esc_attr($atts['text_hover_opacity']) .';
          }';
        }

        // Secondary project image.
        if( isset($atts['overlay_secondary_project_image']) && 'yes' === $atts['overlay_secondary_project_image'] ) {

          $overlaid_image_align = ( isset($atts['overlay_secondary_project_image_align']) ) ? esc_attr($atts['overlay_secondary_project_image_align']) : 'left';
          $using_custom_aspect = ( isset($atts['custom_image_aspect_ratio']) && 'default' != $atts['custom_image_aspect_ratio'] ) ? true : false;
         
          // Core.
          self::$element_css[] = '
          .nectar-post-grid-wrap[data-style=content_under_image] .nectar-post-grid-item__has-secondary .nectar-post-grid-item-bg-wrap,
          .nectar-post-grid .nectar-post-grid-item__has-secondary .inner {
            overflow: visible;
          }
          .nectar-post-grid-item__has-secondary .nectar-post-grid-item-bg-wrap-inner {
            overflow: hidden;
          }
          .nectar-post-grid-item__overlaid-img {
            position: absolute;
            z-index: 100;
            top: 50%;
            transition: transform 0.4s ease;
            transform: translateY(-40%);
          }
          .nectar-post-grid-item__overlaid-img:not(.nectar-lazy) { 
            box-shadow: 0px 25px 40px rgba(0,0,0,0.24);
          }
          .nectar-post-grid-item__overlaid-img.nectar-lazy.loaded {
            box-shadow: 
              0px 13px 13px -10px rgba(0,0,0,0.06),
              0px 25px 40px rgba(0,0,0,0.24);
          }
          @media only screen and (min-width: 1000px) {
            .nectar-post-grid.featured-first-item > .nectar-post-grid-item:first-child .nectar-post-grid-item__overlaid-img.nectar-lazy.loaded,
            .nectar-post-grid.featured-first-item > .nectar-post-grid-item:first-child .nectar-post-grid-item__overlaid-img:not(.nectar-lazy) {
              box-shadow: 
                0px 25px 25px -20px rgba(0,0,0,0.06),
                0px 50px 80px rgba(0,0,0,0.24);
            }
          }
          .nectar-post-grid-wrap[data-style="content_under_image"] .nectar-post-grid[data-lock-aspect="yes"] .nectar-post-grid-item__has-secondary .img-wrap {
            margin-bottom: 0;
          }
          .nectar-post-grid[data-lock-aspect=yes][data-shadow-hover=yes]:not([data-card=yes]) .nectar-post-grid-item__has-secondary .img-wrap:before {
            display: none
          }';

          if( in_array($post_grid_br, array('3px','5px','10px','15px')) ) {
            self::$element_css[] = '.nectar-post-grid-item__overlaid-img {
              border-radius: '.$post_grid_br.';
            }';

            if('15px' === $post_grid_br) {
              self::$element_css[] = '@media only screen and (max-width: 999px) { 
                .nectar-post-grid-item__overlaid-img {
                  border-radius: 10px;
                }
              }';
            }

          }

          if( isset($atts['enable_masonry']) && 'yes' === $atts['enable_masonry'] && !$using_custom_aspect && !$post_grid_featured_f ) {
            self::$element_css[] = '.nectar-post-grid[data-masonry="yes"] .nectar-post-grid-item .nectar-post-grid-item__overlaid-img {
              height: 55%;
              width: auto;
            }';
          } else {
            self::$element_css[] = '.main-content .nectar-post-grid-item .nectar-post-grid-item__overlaid-img {
              width: 22%;
            }';
          }


          // Align.
          $post_card_style = ( isset($atts['card_design']) && 'yes' === $atts['card_design'] ) ? true : false;
          $post_text_align = ( isset($atts['content_under_image_text_align']) ) ? $atts['content_under_image_text_align'] : 'left'; 

          if( 'left' === $overlaid_image_align ) {

            if( $post_card_style ) {
              self::$element_css[] = '
              .overlay-secondary-project-image-left .nectar-post-grid-item__overlaid-img {
                left: 0;
              }
              .overlay-secondary-project-image-left .nectar-post-grid-item:hover .nectar-post-grid-item__overlaid-img { 
                transform: translateY(-50%);
              }';
            } 
            else {
              self::$element_css[] = '.overlay-secondary-project-image-left .nectar-post-grid-item__has-secondary .nectar-post-grid-item-bg-wrap-inner {
                padding-left: 11%;
              }
              .overlay-secondary-project-image-left .nectar-post-grid-item__overlaid-img {
                left: 0;
              }
              .overlay-secondary-project-image-left .nectar-post-grid-item:hover .nectar-post-grid-item__overlaid-img { 
                transform: translateY(-50%);
              }';
            }

            if( 'center' === $post_text_align ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-post-grid[data-text-align="center"] .nectar-post-grid-item__has-secondary .content {
               padding-left: 11%; 
              }';
            }
            
          } // end left aligned.
          
          else if( 'right' === $overlaid_image_align ) {

            if( $post_card_style ) {
              self::$element_css[] = '
              .overlay-secondary-project-image-right .nectar-post-grid-item__overlaid-img {
                right: 0;
              }
     
              .overlay-secondary-project-image-right .nectar-post-grid-item:hover .nectar-post-grid-item__overlaid-img { 
                transform: translateY(-50%);
              }';
            }
            else {
              self::$element_css[] = '.overlay-secondary-project-image-right .nectar-post-grid-item__has-secondary .nectar-post-grid-item-bg-wrap-inner {
                padding-right: 11%;
              }
              .overlay-secondary-project-image-right .nectar-post-grid-item__overlaid-img {
                right: 0;
              }
              .overlay-secondary-project-image-right .nectar-post-grid-item:hover .nectar-post-grid-item__overlaid-img { 
                transform: translateY(-50%);
              }';
            }

            if( 'center' === $post_text_align ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-post-grid[data-text-align="center"] .nectar-post-grid-item__has-secondary .content {
               padding-right: 11%; 
              }';
            }
            
          } // end right aligned.

          // Featured first alters alignment.
          if( $post_grid_featured_f ) {
            self::$element_css[] = '.nectar-post-grid.featured-first-item > .nectar-post-grid-item__has-secondary:first-child {
                text-align: center;
            }
            .nectar-post-grid.featured-first-item > .nectar-post-grid-item__has-secondary:first-child .content .meta-excerpt,
            .nectar-post-grid.featured-first-item > .nectar-post-grid-item__has-secondary:first-child .content .post-heading {
              max-width: none;
            }
            .nectar-post-grid.featured-first-item > .nectar-post-grid-item__has-secondary:first-child .item-main {
              max-width: 800px;
              margin: 0 auto;
          }';
          }


        } // End secondary overlaid image.

        
        // Overlaid category.
        if (  'content_under_image' === $post_grid_style ) {
              self::$element_css[] = '
              #ajax-content-wrap .nectar-post-grid-wrap .category-position-overlaid .nectar-post-grid-item .content {
                position: static;
              }
              .nectar-post-grid-wrap .category-position-overlaid .nectar-post-grid-item .meta-category {
                position: absolute;
                width: calc(100% - 40px);
                z-index: 100;
                top: 20px;
                left: 20px;
              }';

              if( isset($atts['category_style']) && 'underline' === $atts['category_style'] ) {
                self::$element_css[] = '#ajax-content-wrap .nectar-post-grid-wrap .category-position-overlaid .nectar-post-grid-item .meta-category a.style-underline {
                  color: #fff;
                }
                #ajax-content-wrap .nectar-post-grid-wrap .category-position-overlaid .nectar-post-grid-item .meta-category a.style-underline:before {
                  background-color: #fff;
                }';
              }
        }

        // Content overlaid/content under style.
        if ( 'content_next_to_image' === $post_grid_style ) {
          if( isset($atts['category_position']) && 'below_title' === $atts['category_position'] ) {
            self::$element_css[] = '.category-position-after-title .content {
              display: flex;
              flex-direction: column;
            }
            .category-position-after-title .meta-category  {
              order: 2;
              margin: 0
            }
            .category-position-after-title  .meta-category a {
              margin: 10px 10px 0 0;
            }
            .category-position-after-title .item-main  {
              order: 1;
            }
            ';
           }
           else if( isset($atts['category_position']) && 'default' === $atts['category_position'] ) {
            self::$element_css[] = '.category-position-before-title.nectar-post-grid .nectar-post-grid-item .item-meta-extra {
                margin-top: 0;
              }
              .category-position-before-title .item-main > .nectar-post-grid-item__meta-wrap:not(:empty) {
                margin-top: 10px;
              }';
            }
        }
        if( 'content_under_image' === $post_grid_style || 'content_overlaid' === $post_grid_style ) {

          // Category position.
          if( isset($atts['category_position']) && 'below_title' === $atts['category_position'] ) {
            self::$element_css[] = '.category-position-after-title .content {
              display: flex;
              flex-direction: column;
            }
            .category-position-after-title .meta-category  {
              order: 2;
              margin: 0
            }
            .category-position-after-title  .meta-category a {
              margin: 10px 10px 0 0;
            }
            .category-position-after-title .item-main  {
              order: 1;
            }';
           }
           if ( isset($atts['category_position']) && 'default' === $atts['category_position']  ) {
            // When category position is default, we'll move the additional meta below excerpt.
            self::$element_css[] = '.category-position-before-title.nectar-post-grid .nectar-post-grid-item .item-meta-extra {
              margin-top: 0;
            }
            .category-position-before-title .item-main > .nectar-post-grid-item__meta-wrap:not(:empty) {
              margin-top: 10px;
            }';
            
           }

           // Featured First Item
           if( isset($atts['featured_top_item']) && 'yes' === $atts['featured_top_item'] ) {
              self::$element_css[] = '.nectar-post-grid.featured-first-item > .nectar-post-grid-item:first-child {
                width: 100%!important;
              }';
           }

        }


        // Content Next to Image style.
        if ( 'content_next_to_image' === $post_grid_style ) {
          
          //// core.
          self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item .inner {
            display: flex; 
            align-items: flex-start;
            gap: 5%;
          }
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item {
            display: flex;
            align-items: center;
          }
    
          #ajax-content-wrap .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item .content {
            flex: 1;
            padding: 0;
          }
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item-bg,
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item-bg img {
            position: relative;
            margin-bottom: 0;
          }
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item-bg-wrap {
            width: 33.3%;
          }
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item-bg-wrap-inner {
            overflow: hidden;
          }

          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid .nectar-post-grid-item .content .post-heading {
            max-width: 100%;
          }
          [data-style="content_next_to_image"] .nectar-post-grid[data-columns="1"] > div .item-meta-extra {
            width: 100%;
          }

          @media only screen and (max-width: 690px) {
            .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid-item .content .meta-excerpt {
              max-width: 100%;
            }
          }
          
          ';

          // Locked to aspect ratio
          if( $post_grid_custom_ar && isset($atts['aspect_ratio_image_size']) && 'yes' === $atts['aspect_ratio_image_size']) {
            self::$element_css[] = '
            .nectar-post-grid-wrap[data-style="content_next_to_image"] .custom-aspect-ratio-'.esc_attr($post_grid_custom_ar).' .nectar-post-grid-item-bg {
              padding-bottom: '.esc_attr(self::image_aspect_ratio($post_grid_custom_ar)).';
              height: auto!important;
            }
            .nectar-post-grid-wrap[data-style="content_next_to_image"] .custom-aspect-ratio-'.esc_attr($post_grid_custom_ar).' .nectar-post-grid-item-bg img {
              position: absolute;
            }';
          }

          // Divider.
          if( isset($atts['content_next_to_image_divider']) && 
            $atts['content_next_to_image_divider'] === 'yes' && 
            isset($atts['content_next_to_image_divider_color']) && 
            isset($atts['columns']) && '1' === $atts['columns']  ) {
              
            $color = ltrim($atts['content_next_to_image_divider_color'],'#');
            
            if (in_array( $post_grid_spacing, array('10px','15px','25px','35px','40px','45px'))) {
              self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] .content_next_to_image_divider_'. esc_attr($color).'[data-columns="1"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"]  .nectar-post-grid-item:not(:last-child) {
                padding-bottom: '.(intval($post_grid_spacing)*2).'px;
              }';
            } else if (in_array( $post_grid_spacing, array('1vw','2vw','3vw','4vw'))) {
              self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] .content_next_to_image_divider_'. esc_attr($color).'[data-columns="1"][data-grid-spacing="'.esc_attr($post_grid_spacing).'"]  .nectar-post-grid-item:not(:last-child) {
                padding-bottom: '.(intval($post_grid_spacing)*2).'vw;
              }';
            }

            self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] .content_next_to_image_divider_'. esc_attr($color).'[data-columns="1"] .nectar-post-grid-item:not(:last-child)  {
              border-bottom: 1px solid #'.esc_attr($color).';
            }';

          }
          
          // Animation.

          //// Zoom out reveal is grouped with other style.

          //// Fade in from bottom
          if( isset($atts['animation']) && $atts['animation'] === 'fade-in-from-bottom' ) {
            self::$element_css[] = '
            .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid[data-animation="fade-in-from-bottom"] .nectar-post-grid-item {
              opacity: 0;
              -webkit-transform: translateY(30px);
              transform: translateY(30px);
            }
            .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid[data-animation="fade-in-from-bottom"] .nectar-post-grid-item.animated-in {
              opacity: 1;
              -webkit-transform: translateY(0px);
              transform: translateY(0px);
              transition: transform 0.75s cubic-bezier(0.22, 0.61, 0.36, 1), opacity 0.75s cubic-bezier(0.22, 0.61, 0.36, 1);
          }';
        }
        //// Fade in from right
        if( isset($atts['animation']) && $atts['animation'] === 'fade-in-from-right' ) {
          self::$element_css[] = '
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid[data-animation="fade-in-from-bottom"] .nectar-post-grid-item {
            opacity: 0;
            -webkit-transform: translateX(30px);
            transform: translateX(30px);
          }
          .nectar-post-grid-wrap[data-style="content_next_to_image"] .nectar-post-grid[data-animation="fade-in-from-bottom"] .nectar-post-grid-item.animated-in {
            opacity: 1;
            -webkit-transform: translateX(0px);
            transform: translateX(0px);
            transition: transform 0.75s cubic-bezier(0.22, 0.61, 0.36, 1), opacity 0.75s cubic-bezier(0.22, 0.61, 0.36, 1);
        }';
      }

          // Border radius
          if( in_array($post_grid_br, array('3px','5px','10px','15px')) ) {
            self::$element_css[] = '
            .nectar-post-grid-wrap[data-style="content_next_to_image"] [data-border-radius="'.$post_grid_br.'"] .nectar-post-grid-item-bg-wrap-inner {
              border-radius: '.esc_attr($post_grid_br).';
            }
            ';
          }

          // Image gap.
          if ( isset($atts['content_next_to_image_image_gap']) && in_array($atts['content_next_to_image_image_gap'], array('5%','7%','10%','10px', '15px', '20px', '25px'))) {
            self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] [data-image-gap="'.esc_attr($atts['content_next_to_image_image_gap']).'"] .nectar-post-grid-item .inner {
              gap: ' . $atts['content_next_to_image_image_gap'] . ';
            }';
          }

          // Image width.
          if ( isset($atts['content_next_to_image_image_width']) && in_array($atts['content_next_to_image_image_width'], array('25%','33.3%','50%'))) {
            self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] [data-image-width="'.esc_attr($atts['content_next_to_image_image_width']).'"] .nectar-post-grid-item-bg-wrap {
              width: ' . $atts['content_next_to_image_image_width'] . ';
            }';
          }

          // Image position.
          if ( isset($atts['content_next_to_image_image_position']) && 'right' === $atts['content_next_to_image_image_position'] ) {
            self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] [data-image-position="right"] .nectar-post-grid-item .inner {
              flex-direction: row-reverse;
            }';
          }

          // Vertical alignment.
          if ( isset($atts['content_next_to_image_vertical_align']) ) {
            if ( 'middle' === $atts['content_next_to_image_vertical_align'] ) {
              self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] [data-vertical-align="middle"] .nectar-post-grid-item .inner {
                align-items: center;
              }';
            }
            else if ( 'bottom' === $atts['content_next_to_image_vertical_align'] ) {
              self::$element_css[] = '.nectar-post-grid-wrap[data-style="content_next_to_image"] [data-vertical-align="bottom"] .nectar-post-grid-item .inner {
                align-items: flex-end;
              }';
            }
          }

        }


        // Content overlaid.
        if( 'content_overlaid' === $post_grid_style ) {

          // Custom padding
          foreach( self::$devices as $device => $media_query_size ) {

            $body_selector = ('desktop' !== $device) ? 'body ' : '';

            $device_padding = ( isset($atts['content_overlaid_padding_'.$device]) ) ? $atts['content_overlaid_padding_'.$device] : false;
            if( $device_padding ) {
              self::$element_css[] = '@media only screen '.$media_query_size.' {
                '.$body_selector.' [data-style="content_overlaid"] .nectar-post-grid.'.$device.'_padding_'.self::percent_unit_type_class($device_padding).' .content {
                  padding: '.self::percent_unit_type($device_padding).';
                }
              }';
            }
          }
        }
        

        // Vertical List style.
        if( 'vertical_list' === $post_grid_style ) {

          $vert_list_effect = ( isset($atts['vertical_list_hover_effect']) ) ? $atts['vertical_list_hover_effect'] : ''; 
          $vert_list_bg_color = ( isset($atts['vertical_list_bg_color_hover']) ) ? $atts['vertical_list_bg_color_hover'] : '#000';
          $vert_list_border_color = ( isset($atts['vertical_list_border_color']) ) ? $atts['vertical_list_border_color'] : 'rgba(0,0,0,0.15)';
          $vert_list_text_color = ( isset($atts['vertical_list_text_color_hover']) ) ? $atts['vertical_list_text_color_hover'] : '#fff';
          $vert_list_custom_text_color = ( isset($atts['vertical_list_custom_text_color']) ) ? $atts['vertical_list_custom_text_color'] : '';
          $vert_list_overlay_opacity = ( isset($atts['vertical_list_color_overlay_opacity']) ) ? $atts['vertical_list_color_overlay_opacity'] : '0.5';
          $vert_list_overlay_counter = ( isset($atts['vertical_list_counter']) ) ? $atts['vertical_list_counter'] : false;

          //// spacing.
          if( $post_grid_spacing == '5px' || $post_grid_spacing == 'none' ) {

            if( 'featured_image' === $vert_list_effect || 'bg_color_change' === $vert_list_effect ) {
              self::$element_css[] = '.nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid[data-grid-spacing="'.$post_grid_spacing.'"] .nectar-post-grid-item .content {
                padding-left: 25px;
                padding-right: 25px;
              }';
            }
           
          }

          if( in_array( $post_grid_spacing, array('10px','15px','25px','35px','40px','45px') ) ) {

            $lr_padding = '0';

            if( 'featured_image' === $vert_list_effect || 
                'bg_color_change' === $vert_list_effect ) {
                $lr_padding = $post_grid_spacing;
            } 
            
            self::$element_css[] = '
            .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid[data-grid-spacing="'.$post_grid_spacing.'"] .nectar-post-grid-item .content {
              padding: '.$post_grid_spacing.' '.$lr_padding.';
            }
            .nectar-post-grid.vert_list_counter .item-main:before {
              margin-right: '.$post_grid_spacing.';
            }
            ';
          }

          //// core.
          self::$element_css[] = '
          .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid > div:not(:last-child):after  {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 1px;
            content: "";
            display: block;
            transition: transform 1s ease;
            transform-origin: left;
            background-color: '.$vert_list_border_color.';
          }
          .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid-item .content .item-main {
            display: flex;
            flex-direction: column;
          }
          .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .post-heading-wrap {
            flex: 1;
          }
          #ajax-content-wrap .nectar-post-grid-wrap[data-style="vertical_list"] .inner {
            overflow: hidden;
          }
          .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid .nectar-post-grid-item .post-heading,
          .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid .nectar-post-grid-item .item-meta-extra,
          .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid .nectar-post-grid-item .meta-excerpt {
            max-width: 100%;
            width: 100%;
          } 

          .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .meta-date,
          .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .nectar-post-grid-item__meta-wrap {
            margin-top: 0;
          }

          @media only screen and (max-width: 999px) {
            .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid-item .item-main > * {
              margin: 5px 0;
            }
    
            #ajax-content-wrap .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid .nectar-post-grid-item .content {
              padding-left: 0;
              padding-right: 0;
            }
          }

          @media only screen and (min-width: 1000px) {
            .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid-item .content .item-main {
              flex-direction: row;
            }
            .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .meta-date,
            .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .nectar-post-grid-item__meta-wrap {
              padding-right: 8%;
              min-width: 22%;
            }

            .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .nectar-post-grid-item__meta-wrap .meta-date {
              padding-right: 0;
              min-width: none;
            }
        
            .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .nectar-link-underline {
              margin-left: auto;
              padding-left: 8%;
            }
            .nectar-post-grid-wrap[data-style="vertical_list"] .item-main .nectar-link-underline span {
              padding: 2px 0;
            }
          }';
     

        //// counter.
        if( 'yes' === $vert_list_overlay_counter ) {
          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid-item .content .item-main { 
              align-items: center;
            }
          }
          .nectar-post-grid.vert_list_counter .nectar-post-grid-item {
            counter-increment: step-counter; 
          }
          .nectar-post-grid.vert_list_counter .item-main:before {
            content: counter(step-counter);
            margin-right: 25px;
            border: 1px solid '.$vert_list_border_color.';
            display: block;
            position: relative;
            z-index: 10;
            height: 40px;
            width: 40px;
            line-height: 40px;
            font-size: 16px;
            pointer-events: none;
            text-align: center;
            border-radius: 50%;
            transition: color .45s cubic-bezier(.15,.75,.5,1) 0s, border-color .45s cubic-bezier(.15,.75,.5,1) 0s;
          }
          @media only screen and (max-width: 999px) {
            .nectar-post-grid.vert_list_counter .item-main:before {
              position: absolute;
              left: 0;
              top: 0;
            }
            .nectar-post-grid.vert_list_counter .item-main {
              padding-left: 65px;
            }
            .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid.vert_list_counter .item-main > *:first-child {
              margin-top: 0;
            }
          }
          ';

          if( 'bg_color_change' == $vert_list_effect || 'featured_image' == $vert_list_effect ) {
            self::$element_css[] = '.nectar-post-grid.vert_list_counter.vert_list_hover_effect_'.$vert_list_effect.' .nectar-post-grid-item:hover .item-main:before {
              border-color: '.$vert_list_text_color.';
            }';
          }
        }
        
        //// animated border.
        if( 'fade-in-from-bottom' == $atts['animation'] ) {
          self::$element_css[] = '.nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid[data-animation="fade-in-from-bottom"] > div:after {
            transform: scaleX(0);
          }
        .nectar-post-grid-wrap[data-style="vertical_list"] .nectar-post-grid[data-animation="fade-in-from-bottom"] > .animated-in:after {
              transform: scaleX(1);
          }'; 
        }


        //// Custom text color.
        if( !empty($vert_list_custom_text_color) ) {
          $custom_color = ltrim($vert_list_custom_text_color,'#');
          self::$element_css[] = '.nectar-post-grid.vert_list_custom_text_color_'.esc_attr($custom_color).' .nectar-post-grid-item .content *,
          .nectar-post-grid.vert_list_hover_effect_slight_move.vert_list_custom_text_color_'.esc_attr($custom_color).' .nectar-post-grid-item .content * {
            color: #'.esc_attr($custom_color).';
          }';
        }

        //// slight move.
        if( 'slight_move' == $vert_list_effect ) {
          $underline_color = ( isset($atts['text_color']) && 'light' === $atts['text_color'] ) ? '#fff' : '#000';
          
          if( !empty($vert_list_custom_text_color) ) {
            $underline_color = $vert_list_custom_text_color;
          }

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
          #ajax-content-wrap .vert_list_hover_effect_slight_move .item-main:before,
          .vert_list_hover_effect_slight_move .item-main .post-heading-wrap {
            transition: transform 0.35s ease, color .45s cubic-bezier(.15,.75,.5,1) 0s, border-color .45s cubic-bezier(.15,.75,.5,1) 0s;
          }
          .vert_list_hover_effect_slight_move .nectar-post-grid-item:hover .item-main:before,
          .vert_list_hover_effect_slight_move .nectar-post-grid-item:hover .item-main .post-heading-wrap {
            transform: translateX(25px);
          }
          .nectar-post-grid-item .nectar-link-underline span {
            background-image: linear-gradient(to right, '.$underline_color.' 0, '.$underline_color.' 100%);
          }
        }';
        }

        if( 'bg_color_change' == $vert_list_effect ) {
          self::$element_css[] = '.vert_list_hover_effect_bg_color_change .nectar-post-grid-item-bg:after {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            content: "";
            display: block;
            opacity: 1;
            background-color: '.$vert_list_bg_color.';
          }
          #ajax-content-wrap .vert_list_hover_effect_bg_color_change .nectar-post-grid-item-bg {
            transform: translateY(101%);
            transition: transform .6s cubic-bezier(.2,.75,.5,1), opacity .6s cubic-bezier(.2,.75,.5,1);
          }
          
          .vert_list_hover_effect_bg_color_change .nectar-post-grid-item .content .post-heading,
          .vert_list_hover_effect_bg_color_change .nectar-post-grid-item .content .post-heading a {
            transition: none;
          }
          #ajax-content-wrap .vert_list_hover_effect_bg_color_change .nectar-post-grid-item .item-main * {
            color: inherit;
          }

          @media only screen and (min-width: 1000px) {
            #ajax-content-wrap .vert_list_hover_effect_bg_color_change .nectar-post-grid-item:hover .nectar-post-grid-item-bg {
              transform: translateY(0);
            }
            .nectar-post-grid-item:hover .nectar-link-underline span {
              background-image: linear-gradient(to right, '.$vert_list_text_color.' 0, '.$vert_list_text_color.' 100%);
            }
            #ajax-content-wrap .vert_list_hover_effect_bg_color_change .nectar-post-grid-item:hover .item-main {
              color: '.$vert_list_text_color.';
            }
          }';
        }

        else if( 'featured_image' == $vert_list_effect ) {

          self::$element_css[] = '
          .vert_list_hover_effect_featured_image .nectar-post-grid-item-bg:after {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            content: "";
            display: block;
            opacity: '.$vert_list_overlay_opacity.';
            background-color: '.$vert_list_bg_color.';
          }

          #ajax-content-wrap .vert_list_hover_effect_featured_image .nectar-post-grid-item-bg {
            opacity: 0;
            transition: transform .6s cubic-bezier(.2,.75,.5,1), opacity .6s cubic-bezier(.2,.75,.5,1);
          }
        

          .vert_list_hover_effect_featured_image .nectar-post-grid-item .content .post-heading,
          .vert_list_hover_effect_featured_image .nectar-post-grid-item .content .post-heading a {
            transition: none;
          }

          #ajax-content-wrap .vert_list_hover_effect_featured_image .nectar-post-grid-item .item-main * {
            color: inherit;
          }

          @media only screen and (min-width: 1000px) {

            #ajax-content-wrap .vert_list_hover_effect_featured_image .nectar-post-grid-item:hover .nectar-post-grid-item-bg {
              transform: scale(1.05);
            }
            #ajax-content-wrap .vert_list_hover_effect_featured_image .nectar-post-grid-item:hover .nectar-post-grid-item-bg {
              opacity: 1;
            }

            .nectar-post-grid-item:hover .nectar-link-underline span {
              background-image: linear-gradient(to right, '.$vert_list_text_color.' 0, '.$vert_list_text_color.' 100%);
            }
            #ajax-content-wrap .vert_list_hover_effect_featured_image .nectar-post-grid-item:hover .item-main {
              color: '.$vert_list_text_color.';
            }
          }';
        }
      }
        

        // Sortable mobile spacing.
        $grid_spacing = ( isset($atts['grid_item_spacing']) ) ? $atts['grid_item_spacing'] : 'none';
      
        if( in_array($grid_spacing, array('15px', '25px','35px','40px','45px')) ) {

          if( in_array($grid_spacing, array('35px','40px','45px')) ) {
            self::$element_css[] = '
            .spacing-'.$grid_spacing.' .load-more-wrap {
              margin-top: 0;
            }';
          }

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            .spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-bottom: 0;
              padding-top: '.$grid_spacing.';
            }
            .full-width-content .span_12 .spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-top: '.(intval($grid_spacing) * 2 ).'px;
            }
          }
          @media only screen and (max-width: 999px) {
            .spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-bottom: 0;
            }
          }';
        }


        if( in_array($grid_spacing, array( '1vw', '2vw', '3vw', '4vw')) ) {
          
          if( in_array($grid_spacing, array('2vw', '3vw', '4vw')) ) {
            self::$element_css[] = '
            .spacing-'.$grid_spacing.' .load-more-wrap {
              margin-top: 0;
            }';
          }

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            .spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-bottom: 0;
              padding-top: '.$grid_spacing.';
            }
            .full-width-content .span_12 .spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-top: '.(floatval($grid_spacing) * 2 ).'vw;
            }
            .full-width-content .span_12 .spacing-'.$grid_spacing.' .nectar-post-grid-filters[data-align*="sidebar"] {
              padding-bottom: '.(floatval($grid_spacing) * 2 ).'vw;
            }
          }
          @media only screen and (max-width: 999px) {
            .spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-bottom: 0;
            }
          }';
        }

        

        // Sidebar filter alignment.
        $sortable_alignment = ( isset($atts['sortable_alignment']) ) ? $atts['sortable_alignment'] : 'default';
        $sortable_color = ( isset($atts['text_color']) ) ? $atts['text_color'] : 'light'; 
        
        if( 'sidebar_left' === $sortable_alignment || 'sidebar_right' === $sortable_alignment ) {

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
            html body {
              overflow: visible;
            }
            .nectar-post-grid-filters .n-sticky {
              position: sticky;
              top: var(--nectar-sticky-top-distance);
            }
          }';

          $left_or_right_layout = str_replace('sidebar_','',$sortable_alignment);

          if( $grid_spacing != 'none' ) {

            $spacing_unit = ( in_array($grid_spacing, array( '1vw', '2vw', '3vw', '4vw')) ) ? 'vw' : 'px';

            self::$element_css[] = '
            .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-'.$left_or_right_layout.': '.(floatval($grid_spacing) * 2).$spacing_unit.';
            }
            @media only screen and (max-width: 999px) {
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-'.$grid_spacing.' .nectar-post-grid-filters {
                padding-left: '.(floatval($grid_spacing) * 2).$spacing_unit.';
                padding-right: '.(floatval($grid_spacing) * 2).$spacing_unit.';
              }
            }
            @media only screen and (max-width: 690px) {
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-'.$grid_spacing.' .nectar-post-grid-filters {
                padding-left: min('.(floatval($grid_spacing) * 2).$spacing_unit.', 40px);
                padding-right: min('.(floatval($grid_spacing) * 2).$spacing_unit.', 40px);
              }
            }';

          } else {
            self::$element_css[] = '
            .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-'.$grid_spacing.' .nectar-post-grid-filters {
              padding-'.$left_or_right_layout.': 40px;
            }
            @media only screen and (min-width: 1000px) {
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-'.$grid_spacing.' .nectar-post-grid-filters {
                padding-top: 0;
              }
            }
            @media only screen and (max-width: 999px) {
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-'.$grid_spacing.' .nectar-post-grid-filters {
                padding-left: 40px;
                padding-right: 40px;
              }
            }';
          }

          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {
              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
              }
              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters {
                  width: 25%;
                  min-width: 215px;
                  max-width: 275px;
                  padding-right: 40px;
              }
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters {
                max-width: 315px;
              }
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-4vw .nectar-post-grid-filters,
              .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.spacing-3vw .nectar-post-grid-filters {
                max-width: 22vw;
                padding-right: 0;
                width: auto;
              }

              body .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters {
                padding-bottom: '.(intval($grid_spacing) * 2).'px;
                margin-bottom: 0!important;
              }

              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters h4 {
                display: block;
                padding-left: 0;
                text-align: left;
                cursor: auto;
              }
              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters h4:before,
              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters h4:after {
                  display: none;
              }

              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a {
                display: block; 
                margin: 1.1em 0;
             }
             .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a:last-child {
                margin-bottom: 0;
             }
            .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .load-more-wrap {
              padding-'.$left_or_right_layout.': min(275px, max(215px, 25%));
              margin-bottom: 0;
              position: absolute;
              bottom: 40px;
            }
            .full-width-content .span_12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .load-more-wrap {
              padding-'.$left_or_right_layout.': min(315px, max(215px, 25%));
            }
            
          
          }
          @media only screen and (max-width: 999px) {
            .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters {
              max-width: none;
            }
            .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a {
              margin: 0.7em;
            }
            .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .theiaStickySidebar {
              display: block;
              margin-top: 0;
            }
          }';

          // Pagination spacing
          if( isset($atts['pagination']) && 'load-more' === $atts['pagination']) {

            if( in_array($grid_spacing,array( '2vw', '3vw', '4vw' )) ) {

              self::$element_css[] = '@media only screen and (min-width: 1000px) {
                .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'[data-style="content_under_image"]:not(.all-loaded) .nectar-post-grid {
                  margin-bottom: 0;
                }

                body .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .load-more-wrap {
                  bottom: '.(floatval($grid_spacing) * 2).'vw;
                }
                body .nectar-post-grid-wrap--fl-'.$sortable_alignment.':not(.all-loaded) {
                  padding-bottom: '.(floatval($grid_spacing) * 4).'vw;
                }
              }';
            } 
            else if( in_array($grid_spacing,array('25px','35px','40px','45px')) ) {

              self::$element_css[] = '@media only screen and (min-width: 1000px) {
                .wpb_row.full-width-content .vc_col-sm-12 .nectar-post-grid-wrap--fl-'.$sortable_alignment.'[data-style="content_under_image"] .nectar-post-grid {
                  margin-bottom: 0;
                }

                body .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .load-more-wrap {
                  bottom: '.(intval($grid_spacing) * 2).'px;
                }
                body .nectar-post-grid-wrap--fl-'.$sortable_alignment.':not(.all-loaded) {
                  padding-bottom: '.(intval($grid_spacing) * 4).'px;
                }
              }';
            } else {
              self::$element_css[] = '@media only screen and (min-width: 1000px) {
                .nectar-post-grid-wrap--fl-'.$sortable_alignment.':not(.all-loaded) {
                  padding-bottom: 140px;
                }
              }';
            }

          }
          
          self::$element_css[] = '
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a {
              text-align: left;
              position: relative;
              padding-left: 30px;
              padding-top: 0;
              padding-bottom: 0;
          }

          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a:after,
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a:before {
            border-radius: 100px;
            position: absolute;
            display: block;
            content:"";
          }

          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a:after {
            left: 0;
            top: 0;
            height: 18px;
            width: 18px;
            border: 1px solid #000;
            opacity: 0.25;
            background: transparent!important;
            background-color: transparent!important;
            transform: none;
            transition: opacity 0.3s ease;
          }

          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a:before {
            height: 10px;
            width: 10px;
            left: 5px;
            top: 5px;
            transform: scale(0);
            background-color: #000;
            transition: transform 0.3s ease;
          }

          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a:hover:after,
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a.active:after {
            opacity: 1;
          }
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters a.active:before {
              transform: scale(1);
          }
          
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid {
            flex: 1;
          }
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .load-more-wrap {
            width: 100%; 
          }';

          
          self::$element_css[] = '
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.text-color-dark .nectar-post-grid-filters a:after {
            border: 1px solid #000;
          }
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.text-color-dark .nectar-post-grid-filters a:before {
            background-color: #000;
          }

          .light .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.text-color-light .nectar-post-grid-filters,
          .light .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.text-color-light .nectar-post-grid-filters h4 {
            color: #fff;
          }
          .light .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.text-color-light .nectar-post-grid-filters a:after {
            border: 1px solid #fff;
          }
          .light .nectar-post-grid-wrap--fl-'.$sortable_alignment.'.text-color-light .nectar-post-grid-filters a:before {
            background-color: #fff;
          }
          ';
         

          // Custom sortable active color.
          if( isset($atts['sortable_color']) && 'default' !== $atts['sortable_color'] ) {
            
            $color = self::locate_color(strtolower($atts['sortable_color']));

            if( $color && isset($color['gradient']) ) {

              self::$element_css[] = '.nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters[data-active-color="'.esc_attr($color['name']).'"] a:before {
                 background: '.esc_attr($color['gradient']['to']).';
                 background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';
            }

            else if( $color && isset($color['color']) ) {
    
              self::$element_css[] = '.nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters[data-active-color="'.esc_attr($color['name']).'"] a.active {
                color: '.esc_attr($color['color']).';
              }
              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters[data-active-color="'.esc_attr($color['name']).'"] a.active:after {
                border-color: '.esc_attr($color['color']).';
              }
              .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters[data-active-color="'.esc_attr($color['name']).'"] a:before {
                background-color: '.esc_attr($color['color']).';
              }';
              
            }

          }

        }

        if( 'sidebar_right' === $sortable_alignment ) {
          self::$element_css[] = '.nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid {
            order: 1;
          }
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .nectar-post-grid-filters {
            order: 2;
            padding-left: 40px;
            padding-right: 0;
          }
          .nectar-post-grid-wrap--fl-'.$sortable_alignment.' .load-more-wrap {
            order: 3;
          }';
        }


      } // End Post Grid Element.

      // Nectar Highlighted Text
      else if ( false !== strpos($shortcode[0],'[nectar_highlighted_text ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $style = ( isset($atts['style']) ) ? esc_attr($atts['style']) : 'full_text';

        if( isset($atts['custom_font_size']) && !empty($atts['custom_font_size']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h1,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h2,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h3,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h4,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h5,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' h6,
            .nectar-highlighted-text.font_size_'.esc_attr($font_size).' p {
            font-size: '.esc_attr($font_size).';
            line-height: 1.1em;
          }}
          .nectar-highlighted-text[data-style="regular_underline"].font_size_'.esc_attr($font_size).' em:before,
          .nectar-highlighted-text[data-style="half_text"].font_size_'.esc_attr($font_size).' em:before {
             bottom: 0.07em;
          }';

        }

         // Custom font size.
        $custom_font_sizing = self::font_size_group_styles('nectar-highlighted-text[data-style] > *', false, false, '', $atts);
        if ( !empty($custom_font_sizing) ) {
          self::$element_css[] = $custom_font_sizing;
        }

        // Text Direction.
        $text_direction = self::text_direction_styles($atts, '.nectar-highlighted-text');
        if ( !empty($text_direction) ) {
          self::$element_css[] = $text_direction;
        }
         

        // Coloring.
        if( isset($atts['highlight_color']) && !empty($atts['highlight_color']) ) {

          $color      = esc_attr($atts['highlight_color']);
          $grad_color = ( isset($atts['secondary_color']) && !empty($atts['secondary_color']) ) ? esc_attr($atts['secondary_color']) : false;

          if( 'text_outline' !== $style ) {

              // Regular
              if( false === $grad_color ) {
                self::$element_css[] = '.nectar-highlighted-text[data-color="'.esc_attr($color).'"]:not([data-style="text_outline"]) em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($color).' 100%);
                }
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"]:not([data-style="text_outline"]) em.has-link,
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"]:not([data-style="text_outline"]) a em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($color).' 100%),
                                    linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($color).' 100%);
                }';
              }
              else {

                // Grad
                self::$element_css[] = '.nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-color-gradient="'.esc_attr($grad_color).'"]:not([data-style="text_outline"]) em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($grad_color).' 100%);
                }
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-color-gradient="'.esc_attr($grad_color).'"]:not([data-style="text_outline"]) em.has-link,
                .nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-color-gradient="'.esc_attr($grad_color).'"]:not([data-style="text_outline"]) a em {
                  background-image: linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($grad_color).' 100%),
                                    linear-gradient(to right, '.esc_attr($color).' 0%, '.esc_attr($grad_color).' 100%);
                }';
              }

              // Default colo for none style.
              if( 'none' === $style  ) {
                self::$element_css[] = '.nectar-highlighted-text[data-color="'.esc_attr($color).'"][data-style="none"] em {
                  color: '.esc_attr($color).';
                }';
              }


          } // end non text outline.


        } // end color.

        // Sizing.
        $underline_thickness = ( isset($atts['underline_thickness']) ) ? esc_attr($atts['underline_thickness']) : 'default';
        $underline_px        = ( 'default' !== $underline_thickness ) ? $underline_thickness : '3px';
        $expansion           = ( isset($atts['highlight_expansion']) ) ? esc_attr($atts['highlight_expansion']) : 'default';

        $expansion_amt = '90%';
        $bg_hover_height = '80%';

        switch( $expansion ) {
          case 'closer':
            $expansion_amt = '84%';
            $bg_hover_height = '70%';
            break;
          case 'closest':
            $expansion_amt = '78%';
            $bg_hover_height = '60%';
            break;
        }

        if( 'regular_underline' === $style ) {

          self::$element_css[] = '.nectar-highlighted-text[data-style="regular_underline"][data-exp="'.esc_attr($expansion).'"] em {
            background-position: left '.esc_attr($expansion_amt).';
          }
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"] em {
          	background-size: 0% '.esc_attr($underline_px).';
          }
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] a em,
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] em.has-link {
          	background-size: 0% '.esc_attr($underline_px).', 0% '.esc_attr($bg_hover_height).';
            background-position: left '.esc_attr($expansion_amt).', left 50%;
          }';

          self::$element_css[] = '.nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"] em.animated {
          	background-size: 100% '.esc_attr($underline_px).';
          }
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] a em.animated,
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] em.animated.has-link {
          	background-size: 100% '.esc_attr($underline_px).', 0% '.esc_attr($bg_hover_height).';
          }

          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] a em.animated:hover,
          .nectar-highlighted-text[data-style="regular_underline"][data-underline-thickness="'.esc_attr($underline_thickness).'"][data-exp="'.esc_attr($expansion).'"] em.animated.has-link:hover  {
          	background-size: 100% '.esc_attr($underline_px).', 100% '.esc_attr($bg_hover_height).';
          }
          ';

        }

        if( 'none' === $style ) {
          self::$element_css[] = '.nectar-highlighted-text[data-style="none"] em{
            background-image: none!important;
          }';
        }

        // Hand drawn style.
        if( 'scribble' === $style ) {
          
          self::$element_css[] = '@keyframes nectarStrokeAnimation {
            0% {
              stroke-dashoffset: 1;
              opacity: 0;
            }
            1% {
              opacity: 1;
            }
            100% {
              stroke-dashoffset: 0;
            }
          }';

          self::$element_css[] = '.nectar-highlighted-text .nectar-scribble {
            position: absolute;
            left: 0;
            top: 0;
            z-index: -1;
          }
          .nectar-highlighted-text .nectar-scribble path {
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            opacity: 0;
          }
          .nectar-highlighted-text em.animated .nectar-scribble path {
            stroke-linecap: round;
            opacity: 1;
            animation: nectarStrokeAnimation 1.3s cubic-bezier(0.65,0,0.35,1) forwards;
          }
          .nectar-highlighted-text[data-style="scribble"] em {
            background-image: none!important;
          }';

          if( isset($atts['scribble_easing']) && 'ease_out' == $atts['scribble_easing'] ) {
            self::$element_css[] = '.nectar-highlighted-text.ease_out em.animated .nectar-scribble path {
              animation-timing-function: cubic-bezier(0.25,0.1,0.18,1);
            }';
          }

          $scribble_shape = ( isset($atts['scribble_shape']) ) ? esc_attr($atts['scribble_shape']) : 'circle';

          if( 'circle' === $scribble_shape ) {
            self::$element_css[] = 'body .nectar-scribble.circle {
              width: 130%;
              height: 140%;
              top: -20%;
              left: -15%;
            }';
          }
          if( 'sketch-underline' === $scribble_shape ) {
            self::$element_css[] = 'body .nectar-scribble.sketch-underline {
              width: 100%;
              height: 60%;
              top: auto;
              bottom: -15%;
            }';
          }
          if( 'basic-underline' === $scribble_shape ) {
            self::$element_css[] = 'body .nectar-scribble.basic-underline {
              width: 100%;
              height: 30%;
              top: auto;
              bottom: -20%;
            }';
          }
          if( 'squiggle-underline' === $scribble_shape ) {
            self::$element_css[] = 'body .nectar-scribble.squiggle-underline {
              width: 100%;
              height: 50%;
              top: auto;
              bottom: -30%;
            }';
          }
          if( 'squiggle-underline-2' === $scribble_shape ) {
            self::$element_css[] = 'body .nectar-scribble.squiggle-underline-2 {
              width: 100%;
              height: 50%;
              top: auto;
              bottom: -45%;
            }';
          }


          if( isset($atts['disable_mobile_animation']) && 'true' === $atts['disable_mobile_animation'] ) {
            self::$element_css[] = '
            @media only screen and (max-width: 999px) {
              .nectar-highlighted-text.nectar-disable-mobile-animation em {
                background-size: 100% 80%;
              }
              .nectar-highlighted-text[data-style="half_text"].nectar-disable-mobile-animation em {
                background-size: 100% 28%;
              }
              .nectar-highlighted-text.nectar-disable-mobile-animation:not([data-style="text_outline"]) em {
                transition: none;
              }
              .nectar-highlighted-text.nectar-disable-mobile-animation .nectar-scribble path  {
                stroke-dashoffset: 0;
                opacity: 1!important;
                animation: none!important;
              }
            }';
           }

        }

      } // End Nectar Highlighted Text Element.


      // Nectar Star Rating.
      else if( false !== strpos($shortcode[0],'[nectar_star_rating ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);
        $star_num = ( isset($atts['star_rating']) ) ? $atts['star_rating'] : '5';

        // Core.
        self::$element_css[] = '
        .nectar-star-rating {
          display: flex;
          flex-wrap: wrap;
        }

        .nectar-star-rating * {
          font-size: inherit;
          line-height: 1.3;
        }

        .nectar-star-rating__content {
          margin-left: 10px;
        }

        .nectar-star-rating__icon:before { 
          font-family: icomoon;
          font-weight: normal;
          font-style: normal;
          text-decoration: inherit;
          letter-spacing: 0.25em;
          -webkit-font-smoothing: antialiased;
        }';

        if( '5' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-5:before { content: "\e907 \e907 \e907 \e907 \e907"; }';
        } 
        else if( '4-5' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-4-5:before { content: "\e907 \e907 \e907 \e907 \e911"; }';
        }
        else if( '4' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-4:before { content: "\e907 \e907 \e907 \e907"; }';
        }
        else if( '3-5' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-3-5:before { content: "\e907 \e907 \e907 \e911"; }';
        }
        else if( '3' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-3:before { content: "\e907 \e907 \e907"; }';
        }
        else if( '2-5' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-2-5:before { content: "\e907 \e907 \e911"; }';
        }
        else if( '2' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-2:before { content: "\e907 \e907"; }';
        }
        else if( '1-5' === $star_num ) {
          self::$element_css[] = '.nectar-star-rating__icon.size-1-5:before { content: "\e907 \e911"; }';
        }
        else {
          self::$element_css[] = '.nectar-star-rating__icon.size-1:before { content: "\e907"; }';
        }
        

      }


      // Nectar Circle Images.
      else if( false !== strpos($shortcode[0],'[nectar_circle_images ')  ) {

       $atts = shortcode_parse_atts($shortcode_inner);
        
       $positioning = ( isset($atts['positioning']) && !empty($atts['positioning']) ) ? $atts['positioning'] : 'overlapping';
       $sizing = ( isset($atts['sizing']) && !empty($atts['sizing']) ) ? $atts['sizing'] : '50px';
       $border_color = ( isset($atts['border_color']) && !empty($atts['border_color']) ) ? ltrim($atts['border_color'],'#') : 'fff'; 
       $animation = ( isset($atts['animation']) && !empty($atts['animation']) && $atts['animation'] !== 'none' ) ? $atts['animation'] : false;
       $numerical_circle = ( isset($atts['numerical_circle']) && !empty($atts['numerical_circle']) ) ? $atts['numerical_circle'] : false;
       $text_content = ( isset($atts['text_content']) && !empty($atts['text_content']) ) ? $atts['text_content'] : false;
       
       // Core.
        self::$element_css[] = '
        .nectar-circle-images__inner {
          display: flex;
          flex-wrap: nowrap;
        }
        .nectar-circle-images__image:not(.nectar-circle-images--text):before {
          padding-bottom: 100%;
          display: block;
          content: " ";
        }
        .nectar-circle-images__image {
          background-size: cover;
          width: 50px;
          background-position: center;
          border-radius: 1000px;
        }
        ';

        // Numerical text item.
        if( $numerical_circle === 'true' ) {
          self::$element_css[] = '
          .nectar-circle-images--text {
            display: flex;
            align-items: center;
            justify-content: center;
          }
          #ajax-content-wrap .nectar-inherit-h5.nectar-circle-images--text {
            font-size: 16px;
          }
          ';
        }

        // Text content
        if( $text_content ) {
          self::$element_css[] = '
          .nectar-circle-images {
            display: flex;
            align-items: center;
            gap: 20px;
          }
          .nectar-circle-images__text {
            line-height: 1; 
          }
          .nectar-circle-images__text h3, 
          .nectar-circle-images__text h4, 
          .nectar-circle-images__text h5,
          .nectar-circle-images__text h6 {
            margin-bottom: 0; 
          }
          .nectar-circle-images__text {
            font-size: 0.85em; 
          }';
        }

        if( $animation ) {
          self::$element_css[] = '@keyframes nectarCircleImagesReveal {
            0% {
              opacity: 0;
              transform: scale(0.5);
            } 
            40% {
              opacity: 1;
            }
            100% {
              opacity: 1;
              transform: scale(1);
            } 
          }

          @keyframes nectarCircleImagesTextReveal {
            0% {
              opacity: 0;
              transform: translateX(20px);
            } 
            100% {
              opacity: 1;
              transform: translateX(0);
            } 
          }

          .nectar-circle-images.entrance_animation_'.$animation.' .nectar-circle-images__item {
            opacity: 0;
          }
          .nectar-circle-images.entrance_animation_'.$animation.' .nectar-circle-images__item.animated-in {
            animation: nectarCircleImagesReveal 1.3s cubic-bezier(0.25, 1, 0.5, 1) forwards;
          }
          .nectar-circle-images.entrance_animation_'.$animation.' .nectar-circle-images__text.animated-in {
            animation: nectarCircleImagesTextReveal 1s cubic-bezier(0.25, 1, 0.5, 1) forwards;
          }';
        }
        
        // Border Color.
        self::$element_css[] = '.nectar-circle-images.border_color_'.esc_attr($border_color).' .nectar-circle-images__image {
          border: 2px solid #'.esc_attr($border_color).';
        }';

        
        foreach( self::$devices as $device => $media_query_size ) {

          // Alignment
          if( isset($atts['alignment_'.$device]) && $atts['alignment_'.$device] != 'inherit' ) {

            $alignment = $atts['alignment_'.$device];
            $flex_align = 'flex-start';

            if( 'middle' === $alignment ) {
              $flex_align = 'center';
            } 
            else if('right' === $alignment) {
              $flex_align = 'flex-end';
            }

            self::$element_css[] = '@media only screen '.$media_query_size.' {
              .nectar-circle-images.alignment_'.$alignment.'_'.$device.', 
              .nectar-circle-images.alignment_'.$alignment.'_'.$device.' .nectar-circle-images__inner {
                  justify-content: '.$flex_align.';
                }
              }';
            
          } // end alignment set.

        } // end device loop.

       


        // Positioning.
        if( 'overlapping' === $positioning ) {
          self::$element_css[] = '.nectar-circle-images--position_'.$positioning.'.size_'.esc_attr( self::percent_unit_type_class($sizing) ).' .nectar-circle-images__image:not(:last-child) {
            margin-right: calc(('.self::percent_unit_type($sizing).' / 5) * -1);
          }';
        } 
        else {
          self::$element_css[] = '.nectar-circle-images--position_'.$positioning.'.size_'.esc_attr( self::percent_unit_type_class($sizing) ).' .nectar-circle-images__image:not(:last-child) {
            margin-right: calc('.self::percent_unit_type($sizing).' / 5);
          }';
        }

        // Sizing.
        self::$element_css[] = '.nectar-circle-images.size_'.esc_attr( self::percent_unit_type_class($sizing) ).' .nectar-circle-images__image{
          width: '.self::percent_unit_type($sizing).';
        }
        .nectar-circle-images.size_'.esc_attr( self::percent_unit_type_class($sizing) ).' .nectar-circle-images__image {
          border-width: min(9px, max(3px, calc('.self::percent_unit_type($sizing).' / 20)));
        }
        ';
        
      }


      // Nectar Text Inline Images.
      else if( false !== strpos($shortcode[0],'[nectar_text_inline_images ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Core.
        self::$element_css[] = '
        .nectar-text-inline-images {
          position: relative;
          opacity: 0;
          transition: opacity 0.2s ease;
        }
        @media only screen and (max-width: 999px) {
          .nectar-text-inline-images--rm-mobile-animation {
            transition: none;
          }
        }
        body .nectar-text-inline-images a {
          color: inherit;
        }
        .nectar-text-inline-images .nectar-text-inline-images__inner > *:last-child {
          margin-bottom: 0;
        }
        .nectar-text-inline-images__marker {
          display: inline-block;
          position: relative;
          min-width: 10px;
          clip-path: inset(6%);
        }
        body .row .nectar-text-inline-images__marker img {
          position: absolute;
          top: 0;
          left: 0;
          height: 100%;
          max-width: none;
          width: auto;
        }

        .nectar-text-inline-images__marker video {
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
        }

        .nectar-text-inline-images--calculated {
          opacity: 1;
        }
        
        
        ';

        // Text Direction.
        $text_direction = self::text_direction_styles($atts, '.nectar-text-inline-images');
        if ( !empty($text_direction) ) {
          self::$element_css[] = $text_direction;
        }

        // Image Animation.
        $nectar_text_inline_images_image_animation = (isset($atts['image_effect'])) ? $atts['image_effect'] : 'none';
 

        if( 'circle_reveal' == $nectar_text_inline_images_image_animation ) {

            self::$element_css[] = '
            @keyframes nectarClipReveal {
              0% {
                clip-path: circle(15%);
              } 
              100% {
                clip-path: circle(70%);
              } 
            }
    
            .nectar-text-inline-images--animation_circle_reveal img {
              clip-path: circle(15%);
            }
            .nectar-text-inline-images--animation_circle_reveal .nectar-text-inline-images__marker.animated-in img {
              animation: nectarClipReveal 2s cubic-bezier(0.13, 0.73, 0.29, 0.88) forwards;
            }
          ';

          if( isset($atts['image_effect_rm_mobile']) && 'yes' === $atts['image_effect_rm_mobile'] ) {
            self::$element_css[] = '@media only screen and (max-width: 999px) {
              .nectar-text-inline-images--animation_circle_reveal.nectar-text-inline-images--rm-mobile-animation img {
                clip-path: none;
              }
              .nectar-text-inline-images--animation_circle_reveal.nectar-text-inline-images--rm-mobile-animation .nectar-text-inline-images__marker.animated-in img {
                animation: none;
              }
            }';
          }

        } // end circle reveal animation

        if( 'circle_fade_in' == $nectar_text_inline_images_image_animation ) {
           self::$element_css[] = '
          @keyframes nectarClipFade {
            0% {
              opacity: 0;
              clip-path: circle(10%);
            } 
            50% {
              opacity: 1;
            }
            100% {
              opacity: 1;
              clip-path: circle(42%);
            } 
          }

     
            .nectar-text-inline-images--animation_circle_fade_in .nectar-text-inline-images__marker {
              clip-path: none;
            }
            .nectar-text-inline-images--animation_circle_fade_in img {
              clip-path: circle(15%);
              opacity: 0;
            }
            .nectar-text-inline-images--animation_circle_fade_in .nectar-text-inline-images__marker.animated-in img {
              animation: nectarClipFade 1.8s cubic-bezier(0.25,1,0.5,1) forwards;
            }
          ';
          if( isset($atts['image_effect_rm_mobile']) && 'yes' === $atts['image_effect_rm_mobile'] ) {
            self::$element_css[] = '@media only screen and (max-width: 999px) {
              .nectar-text-inline-images--animation_circle_fade_in.nectar-text-inline-images--rm-mobile-animation img {
                clip-path: none;
                opacity: 1;
              }
              .nectar-text-inline-images--animation_circle_fade_in.nectar-text-inline-images--rm-mobile-animation .nectar-text-inline-images__marker.animated-in img {
                animation: none;
              }
            }';
          }

        } // end circle fade animation

        // Color.
        if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
          $nectar_text_inline_images_text_color = ltrim($atts['text_color'],'#');
          self::$element_css[] = '.nectar-text-inline-images.text_color_'.esc_attr($nectar_text_inline_images_text_color).' * {
            color: #'.esc_attr($nectar_text_inline_images_text_color).';
          }';
        }

        // Font size.
        $nectar_text_inline_images_line_height = isset($atts['font_line_height']) ? $atts['font_line_height'] : '1.3';

        if( isset($atts['font_size_desktop']) ) {
            self::$element_css[] = '
            @media only screen and (min-width: 1000px) {
              body .row .nectar-text-inline-images.font_size_desktop_'. esc_attr(self::decimal_unit_type_class($atts['font_size_desktop'])) .' * {
                font-size: '. esc_attr($atts['font_size_desktop']) .';
                line-height: '. esc_attr($nectar_text_inline_images_line_height) .';
              }
          }';
        }

        foreach ($devices as $device => $media_query) {

          if (isset($atts['font_size_'.$device]) && !empty($atts['font_size_'.$device]) ) {

              $font_size = $atts['font_size_'.$device];

              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { 
              body .row .nectar-text-inline-images.font_size_'.$device.'_'. esc_attr(self::decimal_unit_type_class($font_size)) .' * {
                font-size: '. $font_size .';
                line-height: '. esc_attr($nectar_text_inline_images_line_height) .';
              } 
            
            }';

          }

        }

        // Custom Margin.
        self::$element_css[] = self::spacing_group_styles('margin','nectar-text-inline-images', ' .nectar-text-inline-images__marker', $atts);
        


      }
      // Split Line Heading.
      else if ( false !== strpos($shortcode[0],'[split_line_heading ') ) {
        $atts = shortcode_parse_atts($shortcode_inner);

        // Core.
        self::$element_css[] = '
        .nectar-split-heading .heading-line{
          display:block;
          overflow:hidden;
          position:relative
        }
        .nectar-split-heading .heading-line >div{
          display:block;
          transform:translateY(200%);
          -webkit-transform:translateY(200%)
        }
        
        .nectar-split-heading h1{
          margin-bottom:0
        }';

        // Custom font size.
        if( isset($atts['font_size']) && !empty($atts['font_size']) ) {
          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-split-heading[data-custom-font-size="true"] h1,
            .nectar-split-heading[data-custom-font-size="true"] h2,
            .row .nectar-split-heading[data-custom-font-size="true"] h3,
            .row .nectar-split-heading[data-custom-font-size="true"] h4,
            .row .nectar-split-heading[data-custom-font-size="true"] h5,
            .row .nectar-split-heading[data-custom-font-size="true"] h6,
            .row .nectar-split-heading[data-custom-font-size="true"] i {
              font-size: inherit;
              line-height: inherit;
            }
          }';
          
        }

        if( isset($atts['font_size_min']) && !empty($atts['font_size_min']) ) {
          self::$element_css[] = '@media only screen and (max-width: 999px) {
            .nectar-split-heading[class*="font_size_min"] > * {
              font-size: inherit;
              line-height: inherit;
            }
          }';
        }
        
        if( isset($atts['fit_text_to_container']) && 'true' === $atts['fit_text_to_container'] ) {

          if ( !self::$using_front_end_editor ) {
            self::$element_css[] = '.nectar-split-heading[data-has-fit-text="true"]:not(.fitty-fit) {
              opacity: 0!important;
            }';
          }
          
          self::$element_css[] = '.nectar-split-heading[data-has-fit-text="true"] {
            white-space: nowrap;
            display: inline-block;
          }
          .nectar-split-heading[data-has-fit-text="true"] * {
            font-size: inherit;
          }
          .nectar-split-heading[data-has-fit-text="true"][data-text-effect] * {
            line-height: 1; 
          }';
        }


        

        $split_heading_line_height = ( isset($atts['font_line_height']) && !empty($atts['font_line_height']) ) ? $atts['font_line_height'] : false;

        foreach( $devices as $device => $media_query ) {

          $body_selector = ($device !== 'desktop') ? 'body ' : '';
          $html_selector = ($device === 'phone') ? 'html ' : '';

          if( isset($atts['font_size_'.$device]) && strlen($atts['font_size_'.$device]) > 0 ) {

            $font_size = esc_attr( self::percent_unit_type_class($atts['font_size_'.$device]) );
            $line_height = esc_attr('1');

            if( strpos($font_size,'vw') !== false  ) {

              $line_height = esc_attr(intval($font_size)*1.1) .'vw';

            }  
            else if( strpos($font_size,'vh') !== false  ) {

              $line_height = esc_attr(intval($font_size)*1.1) .'vh';

            } 
            else {
              $multi = 1.2;
              if( isset($atts['font_style']) && 'p' === $atts['font_style'] ) {
                $multi = 1.5;
              } 

              $line_height = esc_attr(intval($font_size)*$multi) .'px';
            }

            //User has set unitless line height
            if( $split_heading_line_height && 
                false === strpos($split_heading_line_height, 'px') &&
                floatval($split_heading_line_height) > 0.3 && 
                floatval($split_heading_line_height) < 2.5 ) {
                  $line_height = $split_heading_line_height;
            }

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { 
              .nectar-split-heading.font_size_'.$device.'_'. $font_size .' * {
                font-size: inherit'. esc_attr( $override ).';
              }
            }';

            // auto line height.
            if ( !$split_heading_line_height ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { 
                '. $html_selector . $body_selector . '.nectar-split-heading.font_size_'.$device.'_'. $font_size .' {
                  line-height: '. $line_height .';
                } 
      
                '. $html_selector . $body_selector . '.nectar-split-heading.font_size_'.$device.'_'. $font_size .' * {
                  line-height: inherit;
                }
              }';
            }

          }

        }

        //Custom line height.
        if( $split_heading_line_height ) {
          self::$element_css[] = '.nectar-split-heading.font_line_height_'.esc_attr(self::decimal_unit_type_class($atts['font_line_height'])).' {
            line-height: '.esc_attr($atts['font_line_height']). esc_attr( $override ).'; 
          }
          .nectar-split-heading.font_line_height_'.esc_attr(self::decimal_unit_type_class($atts['font_line_height'])).' * {
            line-height: '.esc_attr($atts['font_line_height']). esc_attr( $override ).'; 
          }';
        }

        // Custom font size.
        $custom_font_sizing = self::font_size_group_styles('nectar-split-heading', 'font_size', false, '!important', $atts);

        if ( !empty($custom_font_sizing) ) {
          self::$element_css[] = $custom_font_sizing;
        }



        // Letter reveal by space.
        $text_effect = ( isset($atts['line_reveal_by_space_text_effect']) ) ? $atts['line_reveal_by_space_text_effect'] : 'default';

        if( isset($atts['animation_type']) && 'line-reveal-by-space' === $atts['animation_type'] ) {

          self::$element_css[] = '.centered-text .nectar-split-heading[data-animation-type="line-reveal-by-space"] h1,
          .centered-text .nectar-split-heading[data-animation-type="line-reveal-by-space"] h2,
          .centered-text .nectar-split-heading[data-animation-type="line-reveal-by-space"] h3,
          .centered-text .nectar-split-heading[data-animation-type="line-reveal-by-space"] h4 {
            margin: 0 auto;
          }
          .nectar-split-heading[data-animation-type="line-reveal-by-space"]:not(.markup-generated) {
            opacity: 0;
          }
          @media only screen and (max-width: 999px) {
            .nectar-split-heading[data-m-rm-animation="true"] {
              opacity: 1!important;
            }
          }
          
          .nectar-split-heading[data-animation-type="line-reveal-by-space"] > * > span  {
            position: relative;
            display: inline-block;
            overflow: hidden;
          }
          
          .nectar-split-heading[data-animation-type="line-reveal-by-space"] span {
             vertical-align: bottom;
          }
          
          .nectar-split-heading[data-animation-type="line-reveal-by-space"] span,
          .nectar-split-heading[data-animation-type="line-reveal-by-space"]:not(.markup-generated) > * {
            line-height: 1.2;
          }
          .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-stagger="true"]:not([data-text-effect*="letter-reveal"]) span .inner {
            transition: transform 1.2s cubic-bezier(0.25, 1, 0.5, 1), opacity 1.2s cubic-bezier(0.25, 1, 0.5, 1);
          }
          .nectar-split-heading[data-animation-type="line-reveal-by-space"] span .inner {
            position: relative;
            display: inline-block;
            -webkit-transform: translateY(1.3em);
            transform: translateY(1.3em);
          }
          .nectar-split-heading[data-animation-type="line-reveal-by-space"] span .inner.animated {
            -webkit-transform: none;
            transform: none;
            opacity: 1;
          }';


          // Scroll opacity reveal 
          if( 'scroll-opacity-reveal' ===  $text_effect ) {
            self::$element_css[] = '
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-text-effect="scroll-opacity-reveal"] span .inner {
              -webkit-transform: translateY(0);
              transform: translateY(0);
              opacity: 0.2;
              transition: none!important;
            }
            #nectar_fullscreen_rows .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-text-effect="scroll-opacity-reveal"] span .inner {
              transition: opacity 0.65s ease!important;
            }
            @media only screen and (max-width: 999px) {
              .nectar-split-heading[data-text-effect="scroll-opacity-reveal"][data-m-rm-animation="true"] .inner {
                opacity: 1!important;
              }
            }
            ';
          }
          // Letter reveal top
          if( 'letter-reveal-top' ===  $text_effect ) {
            self::$element_css[] = '
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-text-effect="letter-reveal-top"] span .inner,
            .nectar-split-heading[data-stored-text-effect="letter-reveal-top"] span .inner  {
              -webkit-transform: translateY(-1.3em);
              transform: translateY(-1.3em);
            }
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-text-effect="letter-reveal-top"] > * > span {
              padding: 0 0.05em;
              margin: 0 -0.05em;
            }';
          }

          if( 'letter-reveal-bottom' ===  $text_effect ) {
            self::$element_css[] = '
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-text-effect="letter-reveal-bottom"] > * > span {
              padding: 0 0.05em;
              margin: 0 -0.05em;
            }';
          }

          // Rotate from bottom
          if( 'twist-bottom' ===  $text_effect ) {
            self::$element_css[] = 'body .nectar-split-heading[data-text-effect="twist-bottom"] > * > span,
            .nectar-split-heading[data-stored-text-effect="twist-bottom"] > * > span {
              overflow: visible;
            }

            .nectar-split-heading[data-text-effect="twist-bottom"],
            .nectar-split-heading[data-stored-text-effect="twist-bottom"] {
              perspective: 700px;
            }
            @media not all and (min-resolution:.001dpcm) { @media {
              .nectar-split-heading[data-text-effect="twist-bottom"],
              .nectar-split-heading[data-stored-text-effect="twist-bottom"] {
                perspective: inherit;
              }
             }
            }
            body .nectar-split-heading[data-text-effect="twist-bottom"] > *,
            body .nectar-split-heading[data-text-effect="twist-bottom"] > * > span,
            .nectar-split-heading[data-stored-text-effect="twist-bottom"] > *,
            .nectar-split-heading[data-stored-text-effect="twist-bottom"] > * > span {
              transform-style: preserve-3d;
            }
            
            .nectar-split-heading[data-text-effect="twist-bottom"][data-animation-type="line-reveal-by-space"][data-stagger="true"] span .inner,
            .nectar-split-heading[data-stored-text-effect="twist-bottom"][data-stagger="true"] span .inner {
              transition: transform 1.2s cubic-bezier(0.25,1,0.5,1),opacity 0.5s cubic-bezier(0.25,1,0.5,1);
            }

            body .nectar-split-heading[data-text-effect="twist-bottom"] span .inner,
            .nectar-split-heading[data-stored-text-effect="twist-bottom"] span .inner {
              transform-origin: center center -2em;
              transform: rotateX(-70deg);
              opacity: 0;
            }';
          }

          if( 'twist-bottom-2' ===  $text_effect ) {
            self::$element_css[] = 'body .nectar-split-heading[data-text-effect="twist-bottom-2"] > * > span,
            .nectar-split-heading[data-stored-text-effect="twist-bottom-2"] > * > span {
              overflow: visible;
            }

            .nectar-split-heading[data-text-effect="twist-bottom-2"] > *,
            .nectar-split-heading[data-stored-text-effect="twist-bottom-2"] > * {
              perspective: 1000px;
            }

            body .nectar-split-heading[data-text-effect="twist-bottom-2"] > * > span,
            .nectar-split-heading[data-stored-text-effect="twist-bottom-2"] > * > span {
              transform-style: preserve-3d;
            }

            body .nectar-split-heading[data-text-effect="twist-bottom-2"] span .inner,
            .nectar-split-heading[data-stored-text-effect="twist-bottom-2"] span .inner  {
              transform-origin: center center -2em;
              transform: rotateX(-40deg) rotateY(13deg) rotateZ(13deg);
              opacity: 0;
            }';
          }

          if( 'none' === $text_effect ) {
            self::$element_css[] = '#ajax-content-wrap .nectar-split-heading[data-text-effect="none"] {
              opacity: 1;
            }';
          }
          
          if( isset($atts['mobile_disable_animation']) && 'true' === $atts['mobile_disable_animation']  ) {
            self::$element_css[] = '@media only screen and ( max-width: 999px ) {
              .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-m-rm-animation="true"] span .inner {
                -webkit-transform: none;
                transform: none!important;
                opacity: 1;
              }
            }';
          }

          self::$element_css[] = '.nectar-split-heading[data-animation-type="line-reveal-by-space"][data-align="left"] {
            display: flex;
            justify-content: flex-start;
          }
          .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-align="center"] {
            display: flex;
            justify-content: center;
          }
          .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-align="right"] {
            display: flex;
            justify-content: flex-end;
          }
          @media only screen and (max-width: 999px) {
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-m-align="left"] {
              display: flex;
              justify-content: flex-start;
            }
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-m-align="center"] {
              display: flex;
              justify-content: center;
            }
            .nectar-split-heading[data-animation-type="line-reveal-by-space"][data-m-align="right"] {
              display: flex;
              justify-content: flex-end;
            }
          }';

        }

        // Twist in
        else if( isset($atts['animation_type']) && 'twist-in' === $atts['animation_type'] ) {
          self::$element_css[] = '
          .nectar-split-heading[data-animation-type="twist-in"][data-align="center"] {
            display: flex;
            justify-content: center;
          }
          .nectar-split-heading[data-animation-type="twist-in"][data-align="right"] {
            display: flex;
            justify-content: flex-end;
          }
          @media only screen and (max-width: 999px) {
            .nectar-split-heading[data-animation-type="twist-in"][data-m-align="left"] {
              display: flex;
              justify-content: flex-start;
            }
            .nectar-split-heading[data-animation-type="twist-in"][data-m-align="center"] {
              display: flex;
              justify-content: center;
            }
            .nectar-split-heading[data-animation-type="twist-in"][data-m-align="right"] {
              display: flex;
              justify-content: flex-end;
            }
          }

          .nectar-split-heading[data-animation-type="twist-in"] {
            transform: rotateY(25deg) rotateZ(-4deg);
            opacity: 0;
            transition: opacity 1s cubic-bezier(.15,.75,.4,1), transform 1.2s cubic-bezier(.15,.75,.4,1);
          }
          .nectar-split-heading[data-animation-type="twist-in"].animated-in {
            transform: rotateY(0deg) rotateZ(0deg);
            opacity: 1;
          }';
        }
        

        // Text Direction.
        $text_direction = self::text_direction_styles($atts, '.nectar-split-heading');
        if ( !empty($text_direction) ) {
          self::$element_css[] = $text_direction;
        }
        

      }

      // Testimonial Slider
      else if ( false !== strpos($shortcode[0],'[testimonial_slider ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $testimonial_slider_style = ( isset($atts['style']) ) ? $atts['style'] : 'default';

        // Core Styles.
        if( 'minimal' === $testimonial_slider_style ) {
            self::$element_css[] = ' .testimonial_slider[data-style="minimal"] blockquote .title{
              font-size:12px;
              font-style:italic
            }
            .testimonial_slider[data-style="minimal"] blockquote span:not(.title){
                -webkit-transform:scale(0.8);
                transform:scale(0.8);
                margin-bottom:-4px
            }
            .testimonial_slider[data-style="minimal"] blockquote .star-rating:not(.title),
            .testimonial_slider[data-style="minimal"] blockquote .star-rating-wrap:not(.title) {
              -webkit-transform:none;
              transform:none;
            }

            .testimonial_slider:not([data-style="minimal"]) blockquote .star-rating {
                font-size: 16px!important;
            }
            @media only screen and (max-width: 690px) {
              .full-width-content .testimonial_slider[data-style="minimal"] .controls{
                bottom: -22px!important;
              }
            }
            
            .testimonial_slider[data-style="minimal"] .slides{
                max-width:70%;
                margin:0 auto
            }
            .testimonial_slider[data-style="minimal"] blockquote{
                padding:0 25px;
                transition:transform 0.5s,opacity 0.5s;
            }

            .testimonial_slider[data-style="minimal"] .control-wrap{
              width:20px;
              line-height:20px;
              overflow:hidden;
              display:inline-block;
              vertical-align:top
          }
          .testimonial_slider[data-style="minimal"] .controls .out-of, 
          .testimonial_slider[data-style="minimal"] .controls .total{
              display:inline-block;
              font-size:16px;
              line-height:20px;
              color: inherit;
              vertical-align:top
          }
          .testimonial_slider[data-style="minimal"] .control-wrap{
              font-size:16px
          }
          .testimonial_slider[data-style="minimal"] .controls .out-of, 
          .testimonial_slider[data-style="minimal"] .controls .total{
              width:20px;
              text-align:center
          }
          .testimonial_slider[data-style="minimal"] .controls .out-of{
              width:13px;
              top:-1px;
              position:relative
          }
          .testimonial_slider[data-style="minimal"] .control-wrap ul{
              width:auto;
              -ms-transition:transform 0.33s;
              -webkit-transition:transform 0.33s;
              transition:transform 0.33s
          }
          .testimonial_slider[data-style="minimal"] .control-wrap ul li{
              color:inherit;
              display:block;
              float:left;
              width:20px;
              font-size:16px;
              line-height:20px;
              cursor:auto
          }
          .testimonial_slider[data-style="minimal"] .controls{
              vertical-align:top
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev{
              position:absolute;
              top:50%;
              height:40px;
              width:40px;
              font-size:25px;
              -webkit-transform:translateY(-50%);
              transform:translateY(-50%);
              margin-top:-40px;
              left:7.5%;
              margin-left:-8px;
              color: inherit;
              text-align: center;
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next{
              position:absolute;
              top:50%;
              height:40px;
              margin-top:-40px;
              font-size:25px;
              width:40px;
              margin-right:-8px;
              color: inherit;
              -webkit-transform:translateY(-50%);
              transform:translateY(-50%);
              right:7.5%;
              text-align: center;
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:before, 
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:before{
              display:block;
              position:absolute;
              left:0;
              top:0
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:after, 
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:after{
              backface-visibility:hidden;
              display:block;
              content:" ";
              z-index:100;
              position:absolute;
              width:30px;
              height:2px;
              background-color:currentColor;
              top:48%;
              opacity:0;
              right: 3px;
              cursor:pointer;
              transform:translateY(-50%) scaleX(0) translateZ(0);
              transition:opacity .5s cubic-bezier(.2,1,.2,1),transform .5s cubic-bezier(.2,1,.2,1);
          }
          .span_12.light .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:after, 
          .span_12.light .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:after{
              background-color:#fff
          }
          .span_12.light .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:before, 
          .span_12.light .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:before, 
          .span_12.light .testimonial_slider[data-style="minimal"] .controls .out-of, 
          .span_12.light .testimonial_slider[data-style="minimal"] .controls .total,
          .span_12.light .testimonial_slider[data-style="minimal"] .controls .control-wrap ul li{
              color:#fff
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:after{
              right:8px
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:hover:after, 
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:hover:after{
              opacity:1;
              transform:translateY(-50%) scaleX(1) translateZ(0);
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:hover:before{
              transform: translateX(10px)
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:hover:before{
              transform:translateX(-10px)
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev svg{
              position:absolute;
              left:-2px;
              top:-2px
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:before{
              left:-1px;
              position:relative
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:before{
              right:-1px;
              position:relative
          }
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev:before, 
          .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next:before{
              line-height:38px;
              color:inherit;
              transition:transform .5s cubic-bezier(.2,1,.2,1);
          }

            @media only screen and (max-width : 690px) {
              .testimonial_slider[data-style="minimal"] .slides {
                max-width: none;
            }
            .testimonial_slider[data-style="minimal"] .testimonial-next-prev .prev, 
            .testimonial_slider[data-style="minimal"] .testimonial-next-prev .next {
                -ms-transform: none;
                -webkit-transform: none;
                transform: none;
                z-index: 500;
                top: auto;
                bottom: -11px;
                color: inherit;
            }
          }';

        } // end minimal style

     
        if( in_array($testimonial_slider_style, array('multiple_visible', 'multiple_visible_minimal')) ) {
          self::$element_css[] = 'body:not(.compose-mode) .testimonial_slider .flickity-slider {
              position: relative;
              display: flex;
              flex-wrap: nowrap;
          }
          
          body:not(.compose-mode) .testimonial_slider .flickity-slider blockquote {
            flex: 0 0 auto;
          }
          ';

          if( isset($atts['flickity_border_radius']) && 'default' !== $atts['flickity_border_radius'] ) {
            self::$element_css[] = '.testimonial_slider.border-radius-'.esc_attr($atts['flickity_border_radius']).' .flickity-slider blockquote p {
                border-radius: '.esc_attr($atts['flickity_border_radius']).';
            }';
          }

          // custom wdith
          if( isset($atts['custom_width_desktop']) && !empty($atts['custom_width_desktop']) ) {

            if( self::$using_front_end_editor ) {
              self::$element_css[] = '@media only screen and (min-width: 1300px) { 
                .testimonial_slider.desktop-width-'.self::percent_unit_type_class($atts['custom_width_desktop']).'[data-style*="multiple_visible"] .flickity-slider .vc_element.vc_testimonial {
                  width: '.self::font_size_output($atts['custom_width_desktop']).';
               }
              }';
            } else {
              self::$element_css[] = '@media only screen and (min-width: 1300px) { 
                .testimonial_slider.desktop-width-'.self::percent_unit_type_class($atts['custom_width_desktop']).'[data-style*="multiple_visible"] .flickity-slider blockquote {
                  width: '.self::font_size_output($atts['custom_width_desktop']).';
               }
              }';
            }
            
          }
        }

        // custom shadow.
        $enable_shadow = isset($atts['enable_shadow']) ? $atts['enable_shadow'] : '';

        if( 'multiple_visible' === $testimonial_slider_style && 'true' === $enable_shadow ) {
          self::$element_css[] = '.testimonial_slider[data-shadow] blockquote:not(.is-selected) p {
            box-shadow: none!important; 
          }
          .testimonial_slider[data-shadow] blockquote p {
            transition: box-shadow 0.2s ease, background-color 0.2s ease;
          }
        
          ';
        }

        // Next prev buttons
        if( isset($atts['slider_controls']) && 'next_prev_arrows' === $atts['slider_controls'] ) {
          self::$element_css[] = '

          .testimonial_slider[data-controls="next_prev_arrows"] .slides {
            text-align: center;
          }
            .testimonial_slider .flickity-prev-next-button {
              display: inline-block;
              margin: 12px;
              width: 50px;
              height: 50px;
              padding: 0;
            }
            @media only screen and (max-width: 690px) {
              .testimonial_slider .flickity-prev-next-button {
                transform: scale(0.8);
                margin: 6px;
              }
            }

            .testimonial_slider .flickity-prev-next-button svg {
              left: auto;
              top: 0;
              position: relative;
              width: 12px;
              height: 100%;
              transition: transform 0.45s cubic-bezier(.15,.75,.5,1);
          }

          .testimonial_slider .flickity-prev-next-button:after {
            height: 2px;
            width: 18px;
            background-color: #000;
            content: "";
            position: absolute;
            left: 7px;
            top: 50%;
            margin-top: -1px;
            display: block;
            transform-origin: right;
            transition: transform 0.45s cubic-bezier(.15,.75,.5,1);
          }

          .testimonial_slider .flickity-prev-next-button:before {
            position: absolute;
            display: block;
            content: "";
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            border-radius: 100px;
            transition: transform 0.45s cubic-bezier(.15,.75,.5,1),opacity 0.45s cubic-bezier(.15,.75,.5,1);
        }


          .testimonial_slider .flickity-prev-next-button.next svg {
            transform: translateX(5px);
          }
          .testimonial_slider .flickity-prev-next-button.next:after {
            transform: scaleX(0.9) translateX(8px);
          }
            

          .testimonial_slider .flickity-prev-next-button.previous:after {
              transform: scaleX(0.9) translateX(0px);
              left: 18px;
              transform-origin: left;
          }
        
          .testimonial_slider .flickity-prev-next-button.previous svg {
            transform: translateX(-4px);
          }
          

          .testimonial_slider .flickity-prev-next-button.next:hover svg {
            transform: translateX(7px);
        }
        .testimonial_slider .flickity-prev-next-button:hover:after {
          transform: scaleX(1.1) translateX(8px);
         }

          .testimonial_slider .flickity-prev-next-button:hover:before {
              transform: scale(1.15);
           }
           .testimonial_slider .flickity-prev-next-button.previous:hover:after {
            transform: scaleX(1.1) translateX(-1px);
          }
          .testimonial_slider .flickity-prev-next-button.previous:hover svg {
            transform: translateX(-6px);
          }
           

            .testimonial_slider .flickity-prev-next-button .arrow {
              fill: #000;
            }
           

          .testimonial_slider[data-controls="next_prev_arrows"] {
            padding-bottom: 0;
          }
          ';
        }
        
        // Font size.
        if( isset($atts['font_size_desktop']) && strlen($atts['font_size_desktop']) > 0 ) {
          self::$element_css[] = '@media only screen and (min-width: 1000px) { 
            body .testimonial_slider.font_size_desktop_'. self::decimal_unit_type_class($atts['font_size_desktop']) .' blockquote,
            body .testimonial_slider.font_size_desktop_'. self::decimal_unit_type_class($atts['font_size_desktop']) .' span[class*="-quote"] {
              font-size: '. esc_attr(self::font_size_output($atts['font_size_desktop'])) .';
              line-height: 1.4;
             } 
          }';
        }

        foreach( $devices as $device => $media_query ) {

          if( isset($atts['font_size_'.$device]) && strlen($atts['font_size_'.$device]) > 0 ) {

            $font_size = esc_attr( self::percent_unit_type_class($atts['font_size_'.$device]) );

            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { 
              body .testimonial_slider.font_size_'.$device.'_'. esc_attr(self::decimal_unit_type_class($atts['font_size_'.$device])) .' blockquote,
              body .testimonial_slider.font_size_'.$device.'_'. esc_attr(self::decimal_unit_type_class($atts['font_size_'.$device])) .'  span[class*="-quote"] {
              font-size: '. esc_attr(self::font_size_output($font_size)) .';
              line-height: 1.3;
             } 
            }';
          }

        }

         // Custom line height.
         if( isset($atts['font_line_height']) && !empty($atts['font_line_height']) ) {
          self::$element_css[] = '#ajax-content-wrap .testimonial_slider.font_line_height_'.esc_attr(self::decimal_unit_type_class($atts['font_line_height'])).' blockquote {
            line-height: '.esc_attr($atts['font_line_height']).'; 
          }';
        }

      }


      // Nectar Rotating Words Title
      else if ( false !== strpos($shortcode[0],'[nectar_rotating_words_title ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['font_size']) && !empty($atts['font_size']) ) {

          $font_size = self::font_sizing_format($atts['font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            body .nectar-rotating-words-title.font_size_'.esc_attr($font_size).' .heading {
            font-size: '.esc_attr($font_size).';
          } }';

        }

        if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
          $color = ltrim($atts['text_color'],'#');

          self::$element_css[] = '.nectar-rotating-words-title.color_'.esc_attr($color).' .heading {
            color: #'.esc_attr($color).';
          }';
        }

        // Text Direction.
        $text_direction = self::text_direction_styles($atts, '.nectar-rotating-words-title');
        if ( !empty($text_direction) ) {
          self::$element_css[] = $text_direction;
        }

      }

      // Nectar Scrolling Text
      else if ( false !== strpos($shortcode[0],'[nectar_scrolling_text ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Core.

         // Text outline.
         if( isset($atts['style']) && 'text_outline' === $atts['style'] ) {
          self::$element_css[] = '.nectar-scrolling-text[data-style="text_outline"] a {
            color: inherit;
          }
          .nectar-scrolling-text[data-style="text_outline"] a:hover em {
            -webkit-text-fill-color: initial;
            -webkit-text-stroke-width: 0;
          }
          
          .nectar-scrolling-text[data-style="text_outline"] em,
          .nectar-scrolling-text[data-style="text_outline"] i {
            font-style: normal;
            font-family: inherit;
          }
          
          .nectar-scrolling-text[data-style="text_outline"] em,
          .nectar-scrolling-text[data-style="text_outline"] i {
            -webkit-text-stroke-color: inherit;
            -webkit-text-fill-color: rgba(0,0,0,0);
          }';

          $outline_thickness = ( isset($atts['outline_thickness']) ) ? $atts['outline_thickness'] : 'thin';

          if( 'ultra-thin' === $outline_thickness ) {
            self::$element_css[] = '.nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="ultra-thin"] em,
            .nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="ultra-thin"] i {
              -webkit-text-stroke-width: 1px;
            }';
          }
          else if( 'thin' === $outline_thickness ) {
            self::$element_css[] = '.nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="thin"] em,
            .nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="thin"] i {
              -webkit-text-stroke-width: 0.015em;
            }';
          }
          else if( 'regular' === $outline_thickness ) {
            self::$element_css[] = '.nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="regular"] em,
            .nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="regular"] i {
              -webkit-text-stroke-width: 0.02em;
            }';
          }
          else if( 'thick' === $outline_thickness ) {
            self::$element_css[] = '.nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="thick"] em {
              -webkit-text-stroke-width: 0.03em;
            }';
          }
          else if( 'extra' === $outline_thickness ) {
            self::$element_css[] = '.nectar-scrolling-text[data-style="text_outline"][data-outline-thickness="extra_thick"] em {
              -webkit-text-stroke-width: 0.04em;
            }';
          }
        
        }


        // custom divider.
        if ( isset($atts['text_repeat_divider']) && $atts['text_repeat_divider'] === 'custom') {
          self::$element_css[] = '.nectar-scrolling-text.has-custom-divider .nectar-scrolling-text-inner__text-chunk > * {
            display: flex;
            align-items: center;
          }';
        }
        // BG Image.
        if( isset($atts['background_image_url']) && !empty($atts['background_image_url']) ) {
          
          self::$element_css[] = 'body .nectar-scrolling-text[data-using-bg="true"] .background-layer,
          body .full-width-section .nectar-scrolling-text[data-using-bg="true"] .background-layer.row-bg-wrap {
            overflow: hidden;
            z-index: 100;
            position: relative;
            width: 100%;
            margin-left: 0;
            left: 0;
            top: 0;
          }
          
          .nectar-scrolling-text[data-using-bg="true"] .background-image {
            background-size: cover;
            background-position: center;
          }
          
          .nectar-scrolling-text[data-sep-text="true"] {
            position: relative;
          }
          
          .nectar-scrolling-text[data-sep-text="true"],
          .nectar-scrolling-text[data-sep-text="true"] > .nectar-scrolling-text-inner {
            overflow: visible;
          }
          
          .nectar-scrolling-text[data-using-bg="true"][data-sep-text="true"] .nectar-scrolling-text-inner {
            position: absolute;
            top: 50%;
            left: 0;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            z-index: 10;
          }
          .wpb_row .nectar-scrolling-text .row-bg {
            position: relative;
            top: 0;
            left: 0;
          }
          .nectar-scrolling-text .background-layer:not([data-bg-animation="none"]) .nectar-scrolling-text-inner {
            opacity: 0;
            transition: opacity 0.6s ease;
          }
          
          .nectar-scrolling-text .background-layer:not([data-bg-animation="none"]) .inner {
            overflow: hidden;
            opacity: 0;
          }
          
          .nectar-scrolling-text .background-layer:not([data-bg-animation="none"]).animated-in .nectar-scrolling-text-inner, 
          .nectar-scrolling-text .background-layer:not([data-bg-animation="none"]).animated-in .inner {
            opacity: 1;
          }
          
          
          .nectar-scrolling-text .background-layer[data-bg-animation="fade-in-from-bottom"] .inner {
            transform:translateY(100px);
            -webkit-transform:translateY(100px);
          }
          
          .nectar-scrolling-text .background-layer[data-bg-animation="fade-in-from-bottom"] .inner {
            -webkit-transition: transform 1s cubic-bezier(0.2, 0.65, 0.3, 1), opacity 0.25s ease;
            transition: transform 1s cubic-bezier(0.2, 0.65, 0.3, 1), opacity 0.25s ease;
          }
          .nectar-scrolling-text .background-layer[data-bg-animation="fade-in-from-bottom"].animated-in .inner {
            transform: translateY(0);
          }';
        }

        // Text space.
        if( isset($atts['text_space_amount']) && 'medium' === $atts['text_space_amount'] ) {
          self::$element_css[] = '.nectar-scrolling-text.text_space_medium[data-spacing="true"] .nectar-scrolling-text-inner > * {
            padding-left: 1.1em;
          }';
        }
        if( isset($atts['text_space_amount']) && 'large' === $atts['text_space_amount'] ) {
          self::$element_css[] = '.nectar-scrolling-text.text_space_large[data-spacing="true"] .nectar-scrolling-text-inner > * {
            padding-left: 1.4em;
          }';
        }

        // Font size.
        if( isset($atts['custom_font_size']) && !empty($atts['custom_font_size']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size']);

          self::$element_css[] = '@media only screen and (min-width: 1000px) {
            .nectar-scrolling-text.font_size_'.esc_attr($font_size).' .nectar-scrolling-text-inner * {
            font-size: '.esc_attr($font_size).';
            line-height: 1em;
          } }';

        }

        if( isset($atts['custom_font_size_mobile']) && !empty($atts['custom_font_size_mobile']) ) {

          $font_size = self::font_sizing_format($atts['custom_font_size_mobile']);

          self::$element_css[] = '@media only screen and (max-width: 999px) {
            .nectar-scrolling-text.font_size_mobile_'.esc_attr($font_size).' .nectar-scrolling-text-inner * {
            font-size: '.esc_attr($font_size).';
            line-height: 1.1em;
          } }';

        }

        // Spin animation.
        if ( isset($atts['text_repeat_divider_custom_spin_animation']) && 
            'yes' === $atts['text_repeat_divider_custom_spin_animation'] ) {
              self::$element_css[] = '
              @keyframes nectar_looped_rotate {
                0% {
                  transform: rotate(0deg);
                }
  
                100% {
                  transform: rotate(360deg);
                }
              }
              .nectar-scrolling-text .nectar-scrolling-text-inner span.custom span {
                display: block;
              }
              .nectar-scrolling-text .nectar-scrolling-text-inner span.spin {
                animation: nectar_looped_rotate 10s linear infinite;
              }
              .nectar-scrolling-text[data-s-dir="ltr"] .nectar-scrolling-text-inner span.spin {
                animation: nectar_looped_rotate 10s linear infinite reverse;
              }';
        }

        // move on scroll animation
        if ( isset($atts['move_on_scroll_animation']) && 
        'yes' === $atts['move_on_scroll_animation'] ) {
          self::$element_css[] = '
          .nectar-scrolling-text .nectar-scrolling-text-inner {
            overflow: visible; 
          }';
        }

      } // End Nectar Scrolling Text.

      // Single Image Element.
      else if( false !== strpos($shortcode[0],'[image_with_animation ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

         // Custom Max Width.
         if( isset($atts['max_width']) && 'custom' === $atts['max_width'] ) {
          if( isset($atts['max_width_custom']) && !empty( $atts['max_width_custom'] ) ) {

            self::$element_css[] = '.img-with-aniamtion-wrap.custom-width-'. esc_attr( self::percent_unit_type_class($atts['max_width_custom']) ) .' .inner {
              max-width: '. self::percent_unit_type($atts['max_width_custom']) .';
            }';
          }
        }

        // Mask.
        $shadow = (isset($atts['box_shadow'])) ? esc_attr($atts['box_shadow']) : false;
        self::$element_css[] = self::mask_param_group_styles('img-with-aniamtion-wrap', '> .inner', $shadow, $atts);

        // Positioning.
        $positions = self::positioning_group_styles('img-with-aniamtion-wrap',$atts);
        foreach( $positions as $position ) {
          self::$element_css[] = $position;
        }

        if( isset($atts['overflow']) && 'hidden' === $atts['overflow'] ) {
          self::$element_css[] = '.img-with-aniamtion-wrap.nectar-overflow-hidden .inner {
            overflow: hidden; 
          }';
        }

        // Device loop atts.
        foreach( $devices as $device => $media_query ) {

          // Margin.
          //// Top.
          if( isset($atts['margin_top_'.$device]) && strlen($atts['margin_top_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_top_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_top_'.$device]) ) .' {
              margin-top: '.esc_attr( self::percent_unit_type($atts['margin_top_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          //// Right.
          if( isset($atts['margin_right_'.$device]) && strlen($atts['margin_right_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_right_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_right_'.$device]) ) .' {
              margin-right: '.esc_attr( self::percent_unit_type($atts['margin_right_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          //// Bottom.
          if( isset($atts['margin_bottom_'.$device]) && strlen($atts['margin_bottom_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_bottom_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_bottom_'.$device]) ) .' {
              margin-bottom: '.esc_attr( self::percent_unit_type($atts['margin_bottom_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          //// Left.
          if( isset($atts['margin_left_'.$device]) && strlen($atts['margin_left_'.$device]) > 0 ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .img-with-aniamtion-wrap.margin_left_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['margin_left_'.$device]) ) .' {
              margin-left: '.esc_attr( self::percent_unit_type($atts['margin_left_'.$device]) ). esc_attr( $override ).';
            } }';
          }

          // Custom Width.
          if( isset($atts['max_width_custom_'.$device]) && !empty( $atts['max_width_custom_'.$device] ) ) {
            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { body .img-with-aniamtion-wrap.custom-width-'. $device. '-' .esc_attr( self::percent_unit_type_class($atts['max_width_custom_'.$device]) ) .' .inner {
              max-width: '. self::percent_unit_type($atts['max_width_custom_'.$device]) .';
            } }';
          }
  

        } // End foreach device loop.

        // Border Radius.
        if( isset($atts['border_radius']) && 
           !empty($atts['border_radius']) && 
          'none' !== $atts['border_radius'] && 
          'custom' !== $atts['border_radius'] ) {
          self::$element_css[] = '
          .img-with-aniamtion-wrap[data-border-radius="'.esc_attr($atts['border_radius']).'"] .img-with-animation,
          .img-with-aniamtion-wrap[data-border-radius="'.esc_attr($atts['border_radius']).'"] .inner,
          .img-with-aniamtion-wrap[data-border-radius="'.esc_attr($atts['border_radius']).'"] .hover-wrap {
            border-radius: '.esc_attr($atts['border_radius']).';
          }';
        }

        // Custom Border Radius.
        if( isset($atts['border_radius']) && 'custom' === $atts['border_radius'] ) {

          if( isset($atts['top_left_border_radius']) && strlen($atts['top_left_border_radius']) > 0 ) {
            self::$element_css[] = '.img-with-aniamtion-wrap.tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) .' .img-with-animation,
            .img-with-aniamtion-wrap.tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) .' .inner,
            .img-with-aniamtion-wrap.tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) .' .hover-wrap {
              border-top-left-radius: '.self::percent_unit_type(esc_attr( $atts['top_left_border_radius'] )).';
            }';
            
          }
          if( isset($atts['top_right_border_radius']) && strlen($atts['top_right_border_radius']) > 0 ) {
            self::$element_css[] = '.img-with-aniamtion-wrap.tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) .' .img-with-animation,
            .img-with-aniamtion-wrap.tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) .' .inner,
            .img-with-aniamtion-wrap.tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) .' .hover-wrap {
              border-top-right-radius: '.self::percent_unit_type(esc_attr( $atts['top_right_border_radius'] )).';
            }';
          }
          if( isset($atts['bottom_left_border_radius']) && strlen($atts['bottom_left_border_radius']) > 0 ) {
            self::$element_css[] = '.img-with-aniamtion-wrap.bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) .' .img-with-animation,
            .img-with-aniamtion-wrap.bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) .' .inner,
            .img-with-aniamtion-wrap.bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) .' .hover-wrap {
              border-bottom-left-radius: '.self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'] )).';
            }';
          }
          if( isset($atts['bottom_right_border_radius']) && strlen($atts['bottom_right_border_radius']) > 0 ) {
            self::$element_css[] = '.img-with-aniamtion-wrap.br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) .' .img-with-animation,
            .img-with-aniamtion-wrap.br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) .' .inner,
            .img-with-aniamtion-wrap.br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) .' .hover-wrap {
              border-bottom-right-radius: '.self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'] )).';
            }';
          }
        }

      
        // Default Max Width.
        $image_alignment = ( isset($atts['alignment']) ) ? $atts['alignment'] : '';

        if( isset($atts['max_width']) && in_array($atts['max_width'], array('110%','125%','150%','165%','175%','200%','225%','250%') ) ) {

          $image_max_width = $atts['max_width'];
          $image_center_margin_rel = array(
              '110%' => '-5%',
              '125%' => '-12.5%',
              '150%' => '-25%',
              '165%' => '-32.5%',
              '175%' => '-37.5%',
              '200%' => '-50%',
              '225%' => '-62.5%',
              '250%' => '-75%',
          );
          $image_right_margin_rel = array(
              '110%' => '-10%',
              '125%' => '-25%',
              '150%' => '-50%',
              '165%' => '-65%',
              '175%' => '-75%',
              '200%' => '-100%',
              '225%' => '-125%',
              '250%' => '-150%',
          );

          // Width Def.
          self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] .inner {
            width: '.esc_attr($atts['max_width']).';
            display: block;
          }
          .img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] img {
            max-width: 100%;
            width: auto;
          }
          .img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"][data-shadow*="depth"] img {
            max-width: none;
          	width: 100%;
          }';

          // Right specific image alignment.
          if( 'right' === $image_alignment ) {
            self::$element_css[] = '.right.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] img {
              display: block;
            }
            .right.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] img:not([srcset]) {
              width: 100%;
            }';
            self::$element_css[] = '.img-with-aniamtion-wrap.right[data-max-width="'.esc_attr($atts['max_width']).'"] .inner{
              margin-left: '.esc_attr($image_right_margin_rel[$image_max_width]).';
            }';
          }

          // Center specific image alignment.
          if( 'center' === $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"].center .inner{
              margin-left: '.esc_attr($image_center_margin_rel[$image_max_width]).';
            }';
          }

          // Left and Center image alignment.
          if( 'right' !== $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"]:not(.right) img {
              backface-visibility: hidden;
            }';
          }

          // Responsive.
          self::$element_css[] = '@media only screen and (max-width : 999px) {
            .img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"] .inner {
              max-width: 100%;
            }
            .img-with-animation[data-max-width="'.esc_attr($atts['max_width']).'"] {
              max-width: 100%;
              margin-left: 0;
            }
          }';

        }

        if( isset($atts['max_width']) && in_array($atts['max_width'], array('50%','75%','custom')) ) {

          if( 'center' === $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"].center .inner {
              display: inline-block;
            }';
          }
          if( 'right' === $image_alignment ) {
            self::$element_css[] = '.img-with-aniamtion-wrap[data-max-width="'.esc_attr($atts['max_width']).'"].right .inner {
              display: inline-block;
            }';
          }

        }

        // Mobile Max Width.
        if( isset($atts['max_width_mobile']) && in_array($atts['max_width_mobile'], array('100%','110%','125%','150%','165%','175%','200%') ) ) {
          self::$element_css[] = '@media only screen and (max-width : 999px) {
              body .img-with-aniamtion-wrap[data-max-width-mobile="'.esc_attr($atts['max_width_mobile']).'"] .inner {
                width: '.esc_attr($atts['max_width_mobile']).';
              }
          }';
        }

        // Slide up animation.
        if( isset($atts['animation']) && 'slide-up' === $atts['animation'] ) {
          self::$element_css[] = '.img-with-aniamtion-wrap[data-animation="slide-up"] .hover-wrap {
            opacity: 1;
            transform: translateY(150px);
          }';
        }

        // Looped animation.
        if( isset($atts['animation_type']) && 'looped' === $atts['animation_type'] ) {

          $looped_animation = ( isset($atts['loop_animation']) ) ? $atts['loop_animation'] : 'none';

          if( 'rotate' == $looped_animation ) {

            self::$element_css[] = '.img-with-aniamtion-wrap.looped-animation-'. $looped_animation . ' .inner {
              animation: nectar_looped_rotate 12s forwards infinite linear;
            }
            @keyframes nectar_looped_rotate {
              0% {
                transform: rotate(0deg);
              }

              100% {
                transform: rotate(360deg);
              }
            }
            ';

          }
          
        }

      } // End Single Image.


      // Pie Chart Element.
      else if( false !== strpos($shortcode[0],'[vc_pie ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        self::$element_css[] = '
          body .vc_pie_chart .vc_pie_chart_value{
            font-size:42px;
            font-family:"Open Sans";
            font-weight:300
          }
          body .vc_pie_chart .wpb_pie_chart_heading{
            font-family:"Open Sans";
            font-weight:700;
            text-transform:uppercase;
            font-size:12px;
            margin-top:12px;
            margin-bottom:0;
            letter-spacing:2px
          }
          body .vc_pie_chart_back{
            border-width:7px;
            opacity:0
          }
          body .vc_pie_chart {
            opacity: 1;
          }
          @media only screen and (min-width: 1000px) and (max-width: 1300px) {
            body .vc_pie_chart .vc_pie_chart_value {
              font-size: 32px;
            }
          }';
      }

      
      // Progress Bar Element.
      else if( false !== strpos($shortcode[0],'[bar ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        self::$element_css[] = '.nectar-progress-bar,
        .wpb_wrapper > .nectar-progress-bar{
          margin-bottom:0
        }
        .nectar-progress-bar .bar-wrap{
          margin-bottom:20px;
          border-radius:300px;
          background-color:#ebebeb;
          box-shadow:0 1px 2px rgba(0,0,0,0.09) inset;
        }
        .nectar-progress-bar span{
          height:14px;
          width:0;
          display:block;
          background-color:#000;
          border-radius:300px;
        }
        .nectar-progress-bar p{
          padding-bottom: 15px;
          line-height: 1.4;
        }
        .nectar-progress-bar span{
          overflow:visible;
          position:relative
        }
        .nectar-progress-bar span strong{
          position:absolute;
          right:-0px;
          top:-23px;
          opacity:0;
          display:block;
          font-family:"Open Sans";
          font-weight:600;
          border-radius:2px;
          padding:5px 0;
        }
        .nectar-progress-bar span strong i{
          font-style:normal;
          font-family:"Open Sans";
          font-weight:600;
          letter-spacing:0;
          text-transform:none
        }
        body .nectar-progress-bar span strong,
        body .nectar-progress-bar .bar-wrap span strong i,
        body .nectar-progress-bar .bar-wrap span strong{
          font-size:11px!important;
          line-height:12px!important
        }
        .nectar-progress-bar span strong.full:after{
          left:15px
        }
        .nectar-progress-bar span strong.full{
          width:43px;
          text-align:right
        }';

        if( class_exists('NectarThemeManager') && 'original' !== NectarThemeManager::$skin ) {

          self::$element_css[] = '.nectar-progress-bar span strong {
              background-color:transparent;
              color:inherit
          }
          .nectar-progress-bar span strong:after {
              display:none
          }
          .nectar-progress-bar .bar-wrap {
              background-color:rgba(0,0,0,0.043)
          }
          .nectar-progress-bar .bar-wrap,
          .nectar-progress-bar span {
              box-shadow:none;
              -webkit-box-shadow:none;
              -o-box-shadow:none;
              border-radius:0;
              -webkit-border-radius:0;
              -o-border-radius:0
          }';
        }

      } // End Progress Bar.


      // Divider Element.
      else if( false !== strpos($shortcode[0],'[divider ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $style = ( isset($atts['line_type']) && !empty($atts['line_type']) ) ? $atts['line_type'] : 'No Line';

        foreach( $devices as $device => $media_query ) {

          // Height.
          if( isset($atts['custom_height_'.$device]) && strlen($atts['custom_height_'.$device]) > 0 ) {

            if( 'No Line' === $style ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .divider-wrap.height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['custom_height_'.$device]) ) .' > .divider {
                height: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
              } }';
            } 
            else if( 'Vertical Line' === $style ) {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .divider-wrap.height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['custom_height_'.$device]) ) .' > div {
                padding-top: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
                padding-bottom: 0!important;
              } }';
            }
            else {
              self::$element_css[] = '@media only screen and (max-width: '.$media_query.') { .divider-wrap.height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['custom_height_'.$device]) ) .' > div {
                margin-top: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
                margin-bottom: '.esc_attr( self::percent_unit_type($atts['custom_height_'.$device]) ). esc_attr( $override ).';
              } }';
            }


          }

        } // End foreach device loop.

      } // End Divider.


      // Fancy Box Element.
      else if( false !== strpos($shortcode[0],'[fancy_box ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Min Height.
        foreach( $devices as $device => $media_query ) {

          // Height.
          if( isset($atts['min_height_'.$device]) && strlen($atts['min_height_'.$device]) > 0 ) {


            self::$element_css[] = '@media only screen and (max-width: '.$media_query.') {
              .nectar-fancy-box:not([data-style="parallax_hover"]):not([data-style="hover_desc"]).min_height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_height_'.$device]) ) .' .inner,
              .nectar-fancy-box[data-style="parallax_hover"].min_height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_height_'.$device]) ) .' .meta-wrap,
              .nectar-fancy-box[data-style="hover_desc"].min_height_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['min_height_'.$device]) ) .' {
                 min-height: '.esc_attr( self::percent_unit_type($atts['min_height_'.$device]) ). esc_attr( $override ).';
              }
          }';


          }

        } // End foreach device loop.


        // Parallax hover styles.
        if( isset($atts['box_style']) && 'parallax_hover' === $atts['box_style'] ) {

          self::$element_css[] = '
          .vc_row.full-width-content .nectar-fancy-box[data-style="parallax_hover"] {
            margin-bottom: 0;
          }
          .nectar-fancy-box[data-style="parallax_hover"]:after {
              display: none;
         }
         .nectar-fancy-box[data-style="parallax_hover"] p {
          opacity: 0.8;
          }
          .nectar-fancy-box[data-style="parallax_hover"] i,
          .nectar-fancy-box[data-style="parallax_hover"] .im-icon-wrap {
                opacity: 0.5;
                transition: all 0.45s cubic-bezier(0.25, 1, 0.2, 1);
          }
          .nectar-fancy-box[data-style="parallax_hover"]:hover i,
          .nectar-fancy-box[data-style="parallax_hover"]:hover .im-icon-wrap {
                opacity: 1;
          }

          .nectar-fancy-box[data-style="parallax_hover"] {
            padding:0;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-wrap {
            transform: translateZ(0px);
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg {
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            outline: 1px solid transparent;
            transition: transform 0.23s ease-out;
            -webkit-transition: transform 0.23s ease-out;
            position: relative;
            z-index: 10;
          }
          .nectar-fancy-box[data-style="parallax_hover"] [class^="icon-"].icon-default-style {
            margin-bottom: 25px;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container {
            position: relative;
            width: 100.2%;
            height: 100%;
            outline: 1px solid transparent;
            will-change: transform;
            transform-style: preserve-3d;
            -webkit-transform-style: preserve-3d;
          }
          .nectar-fancy-box[data-style="parallax_hover"] img {
            width: 100%!important;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-layers {
            position: relative;
            width: 100%;
            height: 100%;
            z-index: 2;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            transform-style: preserve-3d;
            -webkit-transform-style: preserve-3d;
            outline: 1px solid transparent;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-rendered-layer,
          .nectar-fancy-box[data-style="parallax_hover"] .bg-img:after {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-repeat: no-repeat;
            background-position: center;
            background-color: transparent;
            background-size: cover;
            outline: 1px solid transparent;
            overflow: hidden;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .bg-img:after {
            display: block;
            content: "";
            background-color: rgba(40,40,40,1);
            transition: all 0.45s cubic-bezier(0.25, 1, 0.2, 1);
          }

          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-rendered-layer:last-child {
            position: relative;
          }
          .nectar-fancy-box[data-style="parallax_hover"]:hover .parallaxImg-container .parallaxImg-rendered-layer:nth-child(2) {
            transform: translateZ(65px)!important;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-rendered-layer > .bg-img {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-repeat: no-repeat;
            background-position: center;
            background-color: transparent;
            background-size: cover;
            outline: 1px solid transparent;
          }';

          self::$element_css[] = '
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container .parallaxImg-rendered-layer,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-rendered-layer {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container .parallaxImg-shadow,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-shadow {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
          }
          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container .parallaxImg-shadow,
          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-shadow,
          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container .parallaxImg-rendered-layer,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-rendered-layer {
            transition: transform 0.27s ease-out;
            -webkit-transition: transform 0.27s ease-out;
          }

          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.over .parallaxImg-container {
            will-change: transform;
          }

          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container:not(.over),
          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container:not(.over) .parallaxImg-rendered-layer,
          html.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg:not(.over) {
            transition: transform 0.25s ease-out;
            -webkit-transition: transform 0.25s ease-out;
          }

          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container:not(.over) .parallaxImg-shadow,
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-shadow {
            transition: all 0.27s ease-out;
            -webkit-transition: all 0.27s ease-out;
          }
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container .parallaxImg-shadow,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-shadow,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-container .parallaxImg-rendered-layer,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-rendered-layer,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg {
            transition: transform 0.1s ease-out;
            -webkit-transition: transform 0.1s ease-out;
          }
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-shadow,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition .parallaxImg-container .parallaxImg-rendered-layer,
          body.cssreflections .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg.transition {
            transition: transform 0.2s ease-out;
            -webkit-transition: transform 0.2s ease-out;
          }';

          self::$element_css[] = '
          .nectar-fancy-box[data-style="parallax_hover"] {
            overflow: visible;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .parallaxImg-shadow {
            position: absolute;
            top: 5%;
            left: 5%;
            width: 90%;
            height: 90%;
            transition: transform 0.27s ease-out, opacity 0.27s ease-out;
            z-index: 1;
            opacity: 0;
            box-shadow: 0 35px 100px rgba(0,0,0,0.4), 0 16px 40px rgba(0,0,0,0.4);
          }
          .nectar-fancy-box[data-style="parallax_hover"]:hover {
            z-index: 100;
          }
          .nectar-fancy-box[data-style="parallax_hover"]:hover .parallaxImg-shadow {
            opacity: 1;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .meta-wrap {
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-align-items: center;
            align-items: center;
            justify-content: center;
            -webkit-justify-content: center;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .inner {
            margin-bottom: 0;
            padding: 25% 0px;
            position: relative;
            width: 65%;
          }

          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.1"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.1"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.1;
          }
          .nectar-fancy-box[data-style="parallax_hover"]:hover .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.2"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.2"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.2;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.3"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.3"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.3;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.4"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.4"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.4;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.5"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.5"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.5;
          }
          .nectar-fancy-box[data-style="parallax_hover"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.6"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.6"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.6;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.7"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.7"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.7;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.8"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.8"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.8;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="0.9"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="0.9"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 0.9;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity="1"] .bg-img:after,
          .nectar-fancy-box[data-style="parallax_hover"][data-overlay-opacity-hover="1"]:hover .parallaxImg-wrap .bg-img:after {
          opacity: 1;
          }

          .nectar-fancy-box[data-style="parallax_hover"][data-align="top"] .meta-wrap {
          -webkit-align-items: flex-start;
          align-items: flex-start;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-align="top"] .meta-wrap .inner {
          padding-top: 12%;
          width: 73%;
          padding-right: 5%;
          }

          .nectar-fancy-box[data-style="parallax_hover"][data-align="bottom"] .meta-wrap {
          -webkit-align-items: flex-end;
          align-items: flex-end;
          }
          .nectar-fancy-box[data-style="parallax_hover"][data-align="bottom"] .meta-wrap .inner {
          padding-bottom: 12%;
          width: 73%;
          padding-right: 5%;
          }
          ';
        }

        if( isset($atts['parallax_hover_box_overlay']) && !empty($atts['parallax_hover_box_overlay']) ) {

          $color = ltrim($atts['parallax_hover_box_overlay'],'#');

          self::$element_css[] = '.nectar-fancy-box[data-style="parallax_hover"].overlay_'.esc_attr($color).' .bg-img:after {
            background-color: #'.esc_attr($color).';
          }';

        }

        // Description on hover styles.
        if( isset($atts['box_style']) && 'hover_desc' === $atts['box_style'] ) {

          $hover_desc_hover_o = ( isset($atts['hover_desc_hover_overlay_opacity']) && 'default' !== $atts['hover_desc_hover_overlay_opacity'] ) ? $atts['hover_desc_hover_overlay_opacity'] : '1';

           // Overlay Opacity.
           if (isset($atts['hover_desc_color_opacity']) && 'default' !== $atts['hover_desc_color_opacity']) {
            
              self::$element_css[] = '
              .nectar-fancy-box[data-style="hover_desc"].hovered.o_opacity_'.esc_attr(str_replace('.', '-', $atts['hover_desc_color_opacity'])).' .box-bg:before {
                opacity: 1;
              }
              .nectar-fancy-box[data-style="hover_desc"].o_opacity_'.esc_attr(str_replace('.', '-', $atts['hover_desc_color_opacity'])).' .box-bg:before {
                 background: linear-gradient(to bottom,rgba(15,15,15,0),rgba(15,15,15, '. esc_attr($atts['hover_desc_color_opacity']) .') 100%);
            }';
          }
          

          // Custom hover color.
          if( isset($atts['hover_color_custom']) && !empty($atts['hover_color_custom']) ) {

            $color = ltrim($atts['hover_color_custom'],'#');
  
            self::$element_css[] = '.nectar-fancy-box[data-style="hover_desc"][data-color].hover_color_'.esc_attr($color).' .box-bg:after {
              background: linear-gradient(to bottom, rgba(0,0,0,0), #'.esc_attr($color).' 100%);
            }

            .nectar-fancy-box[data-style="hover_desc"][data-color].hovered.hover_color_'.esc_attr($color).' .box-bg:after {
              opacity: '.esc_attr($hover_desc_hover_o).';
            }
            ';
  
          } 
          else {

            if (isset($atts['hover_desc_hover_overlay_opacity']) && 'default' !== $atts['hover_desc_hover_overlay_opacity']) {
                self::$element_css[] = '.nectar-fancy-box[data-style="hover_desc"].hovered.hover_o_opacity_'.esc_attr(str_replace('.', '-', $atts['hover_desc_hover_overlay_opacity'])).' .box-bg:after {
                  opacity: '.esc_attr($hover_desc_hover_o).';
              }';
            }

          }

          //// Icon Position.
          if( isset($atts['icon_position']) && 'top' === $atts['icon_position'] ) {
            self::$element_css[] = '.nectar-fancy-box[data-style="hover_desc"].icon_position_top .inner {
              align-self: stretch;
              display: flex;
              flex-direction: column;
            }
            .nectar-fancy-box[data-style="hover_desc"].icon_position_top .heading-wrap {
              margin-top: auto;
            }';
          }
          

        }

        

        // Default and Color box hover.

        if( isset($atts['box_style']) && 'color_box_basic' === $atts['box_style'] ) {
          
          self::$element_css[] = '
            .main-content .nectar-fancy-box[data-style="color_box_basic"] h1,
            .main-content .nectar-fancy-box[data-style="color_box_basic"] h2,
            .main-content .nectar-fancy-box[data-style="color_box_basic"] h3,
            .main-content .nectar-fancy-box[data-style="color_box_basic"] h4,
            .main-content .nectar-fancy-box[data-style="color_box_basic"] h5,
            .main-content .nectar-fancy-box[data-style="color_box_basic"] h6,
            .main-content .nectar-fancy-box[data-style="color_box_basic"] p {
                color: inherit;
            }
            .main-content .nectar-fancy-box[data-style="color_box_basic"] i {
                color: inherit!important;
            }
            .main-content .nectar-fancy-box[data-style="color_box_basic"] .im-icon-wrap path {
                fill: inherit;
            }
            .main-content .nectar-fancy-box[data-style="color_box_basic"] .inner {
                padding-bottom: 0;
                color: inherit;
            }
            .main-content .nectar-fancy-box[data-style="color_box_basic"][data-color*="#"] .box-bg:after {
                background-color: inherit!important;
            }
            .nectar-fancy-box[data-style="color_box_basic"]:hover .box-bg {
                transform: none;
            }
            .nectar-fancy-box[data-style="color_box_basic"] .box-bg:after {
                backface-visibility: hidden;
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.9"] .box-bg:after {
                opacity: 0.9
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.8"] .box-bg:after {
                opacity: 0.8
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.7"] .box-bg:after {
                opacity: 0.7
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.6"] .box-bg:after {
                opacity: 0.6
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.5"] .box-bg:after {
                opacity: 0.5
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.4"] .box-bg:after {
                opacity: 0.4
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.3"] .box-bg:after {
                opacity: 0.3
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.2"] .box-bg:after {
                opacity: 0.2
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0.1"] .box-bg:after {
                opacity: 0.1
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-box-color-opacity="0"] .box-bg:after {
                opacity: 0
            }
            .nectar-fancy-box[data-style="color_box_basic"] .box-bg:before {
                position: absolute;
                top: 0;
                left: 0;
                content: "";
                width: 100%;
                height: 100%;
                background-color: #fff;
                opacity: 0;
                z-index: 1;
                transition: opacity .4s cubic-bezier(0.25, 1, 0.33, 1)
            }
            .nectar-fancy-box[data-style="color_box_basic"]:hover .box-bg:before {
                opacity: 0.13;
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-alignment="center"] .inner,
            .nectar-fancy-box[data-style="color_box_basic"][data-alignment="center"] .inner > * {
                text-align: center
            }
            .nectar-fancy-box[data-style="color_box_basic"][data-alignment="right"] .inner,
            .nectar-fancy-box[data-style="color_box_basic"][data-alignment="right"] .inner > * {
                text-align: right
            }';
        }

        if( isset($atts['box_style']) && 'color_box_hover' === $atts['box_style'] ) {

          self::$element_css[] = '
          .nectar-fancy-box[data-style="color_box_hover"] .box-inner-wrap {
            transform: translateZ(0);
          }
          .nectar-fancy-box[data-style="color_box_hover"] .inner-wrap > i {
            margin-bottom: 25px;
          }
          .nectar-fancy-box[data-style="color_box_hover"][data-border="true"] .box-inner-wrap {
            border: 1px solid rgba(0,0,0,0.1);
            transition: all 0.45s cubic-bezier(0.25, 1, 0.2, 1);
          }
          .span_12.light .nectar-fancy-box[data-style="color_box_hover"][data-border="true"] .box-inner-wrap {
              border-color: rgba(255,255,255,0.14);
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-border="true"]:hover .box-inner-wrap {
                border: 1px solid rgba(0,0,0,0);
          }
            .nectar-fancy-box[data-style="color_box_hover"] .inner {
                display: -webkit-flex;
                display: flex;
                align-items: center;
                justify-content: center;
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"] .inner-wrap {
                position: relative;
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"] .inner i.hover-only {
                position: absolute;
                opacity: 0;
                top: -2px;
                left: 0;
                z-index: 1;
                transition: opacity 0.45s cubic-bezier(0.25, 1, 0.2, 1);
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"][data-alignment="right"] .inner i.hover-only {
                right: 0;
                left: auto;
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"][data-alignment="center"] .inner i.hover-only {
                left: 50%;
                transform: translateX(-50%);
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"]:hover .inner i.hover-only {
                opacity: 1;
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"].inner i:not(.hover-only) {
                transition: opacity 0.45s cubic-bezier(0.25, 1, 0.2, 1);
          }
            .nectar-fancy-box[data-style="color_box_hover"][data-color*="gradient"]:hover .inner i:not(.hover-only) {
                opacity: 0;
          }
            .nectar-fancy-box[data-style="color_box_hover"] .inner i {
                text-align: center;
          }
            .nectar-fancy-box[data-style="color_box_hover"] .inner p {
                opacity: 0.75;
          }';

          self::$element_css[] = '
          .vc_col-sm-3 .nectar-fancy-box[data-style="color_box_hover"] .inner p,
          .vc_col-sm-4 .nectar-fancy-box[data-style="color_box_hover"] .inner p,
          .nectar-flickity[data-desktop-columns="4"] .nectar-fancy-box[data-style="color_box_hover"] .inner p,
          .nectar-flickity[data-desktop-columns="3"] .nectar-fancy-box[data-style="color_box_hover"] .inner p {
            line-height: 1.7em;
          }
          .nectar-fancy-box[data-style="color_box_hover"] .inner {
              padding-bottom: 0;
              text-align: center;
              vertical-align: middle;
          }
          .nectar-fancy-box[data-style="color_box_hover"] .inner-wrap {
              text-align: center;
              vertical-align: middle;
          }
          .span_12.light .nectar-fancy-box[data-style="color_box_hover"] .inner p {
              opacity: 0.65;
          }
          .span_12.light .nectar-fancy-box[data-style="color_box_hover"]:hover .inner p {
              opacity: 0.8;
          }
          .span_12.light .nectar-fancy-box[data-style="color_box_hover"]:hover:before {
              display: none
          }
          .nectar-fancy-box[data-style="color_box_hover"][data-alignment="left"] .inner,
          .nectar-fancy-box[data-style="color_box_hover"][data-alignment="left"] .inner-wrap,
          .nectar-fancy-box[data-style="color_box_hover"][data-alignment="left"] .inner i {
              text-align: left;
          }

          .nectar-fancy-box[data-style="color_box_hover"][data-alignment="right"] .inner,
          .nectar-fancy-box[data-style="color_box_hover"][data-alignment="right"] .inner-wrap,
          .nectar-fancy-box[data-style="color_box_hover"][data-alignment="right"] .inner i {
              text-align: right;
         }
        .nectar-fancy-box[data-style="color_box_hover"]:before {
          display: block;
          position:absolute;
          left: 1%;
          top: 1%;
          height: 98%;
          width: 98%;
          opacity: 0;
          content: "";
        }
        .nectar-fancy-box[data-style="color_box_hover"]:hover:before {
        opacity: 0.33;
        }
        .nectar-fancy-box[data-style="color_box_hover"]:hover .box-bg {
          transform: scale(1.08);
          -webkit-transform: scale(1.08);
        }
        .nectar-fancy-box[data-style="color_box_hover"] {
          overflow: visible;
          padding: 0;
        }
        .nectar-fancy-box[data-style="color_box_hover"] .box-inner-wrap {
          padding: 10% 15%;
          position: relative;
          overflow: hidden;
        }

        .nectar-fancy-box[data-style="color_box_hover"]:hover .inner-wrap *,
        .nectar-fancy-box[data-style="color_box_hover"] .box-bg,
        .nectar-fancy-box[data-style="color_box_hover"] .box-bg:after {
          transition: all 0.45s cubic-bezier(0.25, 1, 0.2, 1);
        }
        .nectar-fancy-box[data-style="color_box_hover"]:before {
        transition: opacity 0.45s cubic-bezier(0.25, 1, 0.2, 1);
        }
        .nectar-fancy-box[data-style="color_box_hover"]:hover .inner-wrap .nectar-cta .link_wrap .link_text:after {
          border-color: rgba(255,255,255,0.4);
        }
        .nectar-fancy-box[data-style="color_box_hover"]:hover .inner-wrap .nectar-cta[data-style="see-through"] .link_wrap .arrow:after {
          border-color: #fff;
        }
        .nectar-fancy-box[data-style="color_box_hover"]:hover .inner-wrap .nectar-cta .arrow,
        .nectar-fancy-box[data-style="color_box_hover"]:hover .inner-wrap .nectar-cta .link_wrap .arrow:before {
          color: #fff;
        }
        .nectar-fancy-box[data-style="color_box_hover"] .inner-wrap *,
        .nectar-fancy-box[data-style="color_box_hover"] .inner-wrap {
          color: #444;
        }
        .span_12.light .nectar-fancy-box[data-style="color_box_hover"] .inner-wrap *,
        .span_12.light .nectar-fancy-box[data-style="color_box_hover"] .inner-wrap {
          color: #fff;
        }
        
        .nectar-fancy-box[data-style="color_box_hover"] .box-bg,
        .nectar-fancy-box[data-style="color_box_hover"]:after {
            opacity: 0
        }
        .nectar-fancy-box[data-style="color_box_hover"]:hover .box-bg {
            opacity: 1;
        }
        .nectar-fancy-box[data-style="color_box_hover"].using-img .box-bg:after {
            opacity: 0.85;
        }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.9"] .box-bg:after { opacity: 0.9; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.8"] .box-bg:after { opacity: 0.8; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.7"] .box-bg:after { opacity: 0.7; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.6"] .box-bg:after { opacity: 0.6; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.5"] .box-bg:after { opacity: 0.5; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.4"] .box-bg:after { opacity: 0.4; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.3"] .box-bg:after { opacity: 0.3; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.2"] .box-bg:after { opacity: 0.2; }
        .nectar-fancy-box[data-style="color_box_hover"][data-hover-o="0.1"] .box-bg:after { opacity: 0.1; }';

        }

        if( isset($atts['color_custom']) && !empty($atts['color_custom']) ) {

          $color = ltrim($atts['color_custom'],'#');

          self::$element_css[] = '.nectar-fancy-box[data-style="default"].box_color_'.esc_attr($color).':after {
            background-color: #'.esc_attr($color).esc_attr( $override ).';
          }';

          self::$element_css[] = '.nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).':hover:before {
             box-shadow: 0 30px 90px #'.esc_attr($color).';
          }
          .nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).':not(:hover) i.icon-default-style {
             color: #'.esc_attr($color).esc_attr( $override ).';
          }
          .nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).':not(:hover) svg path {
            fill: #'.esc_attr($color).esc_attr( $override ).';
         }
          .nectar-fancy-box[data-style="color_box_hover"][data-color].box_color_'.esc_attr($color).' .box-bg:after {
             background-color: #'.esc_attr($color).esc_attr( $override ).';
          }';

        }

        // Image Above Text Underline.
        if( isset($atts['box_style']) && 'image_above_text_underline' === $atts['box_style'] ) {

          // Image Ratio.
          $ratio = ( isset($atts['image_aspect_ratio']) ) ? esc_attr($atts['image_aspect_ratio']) : '1-1';

          self::$element_css[] = '.nectar-fancy-box[data-style="image_above_text_underline"].aspect-'.$ratio.' .box-bg {
            padding-bottom: '.esc_attr(self::image_aspect_ratio($ratio)).';
          }';

          // Content Color.
           if( isset($atts['content_color']) && !empty($atts['content_color']) ) {

             $color = ltrim($atts['content_color'],'#');

             self::$element_css[] = '.nectar-fancy-box[data-style="image_above_text_underline"].content-color-'.esc_attr($color).' * {
               color: #'.esc_attr($color).';
             }
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h2,
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h3,
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h4,
             .nectar-fancy-box[data-style*="text_underline"].content-color-'.esc_attr($color).' h5 {
               background-image: linear-gradient(to right, #'.esc_attr($color).' 0%,  #'.esc_attr($color).' 100%);
             }';

           }

        }



      } // End Fancy Box


      // Recent Posts.
      else if( false !== strpos($shortcode[0],'[recent_posts') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset( $atts['style'] ) && 'slider_multiple_visible' === $atts['style'] ) {

          self::$element_css[] = '.nectar-recent-posts-slider_multiple_visible .flickity-page-dots .dot.is-selected:before {
            background-color: transparent!important;
          }
          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-slide {
            width: 75%;
            border-radius: 5px;
            min-height: 400px;
            padding-top: 45%;
            margin: 0 15px;
          }
          
          
          @media only screen and (min-width: 691px) {
            .nectar-recent-posts-slider_multiple_visible[data-columns="2"] .nectar-recent-post-slide,
            .nectar-recent-posts-slider_multiple_visible[data-columns="3"] .nectar-recent-post-slide,
            .nectar-recent-posts-slider_multiple_visible[data-columns="4"] .nectar-recent-post-slide {
              width: 40%;
              margin: 0 20px;
              padding-top: 35%;
            }
          }

          @media only screen and (min-width: 1000px) {
            .nectar-recent-posts-slider_multiple_visible[data-columns="3"] .nectar-recent-post-slide,
            .nectar-recent-posts-slider_multiple_visible[data-columns="4"] .nectar-recent-post-slide {
              width: 25%;
              margin: 0 20px;
            }
          }

          @media only screen and (min-width: 1600px) {
            .nectar-recent-posts-slider_multiple_visible[data-columns="4"] .nectar-recent-post-slide {
              width: 20%;
              padding-top: 30%;
            }
          }

          .nectar-recent-posts-slider_multiple_visible {
            overflow: hidden;
          }

          .full-width-content .vc_col-sm-12 .nectar-recent-posts-slider_multiple_visible {
            overflow: visible;
          }

          .nectar-recent-posts-slider_multiple_visible .nectar-button {
            margin-bottom: 0;
          }

          .nectar-recent-posts-slider_multiple_visible .recent-post-container.container {
            position: absolute;
            bottom: 40px;
            left: 40px;
            max-width: 65%!important;
            z-index: 10;
            padding: 0;
          }';

          self::$element_css[] = '
          .nectar-recent-posts-slider_multiple_visible .flickity-viewport .nectar-recent-post-bg-wrap {
            border: 1px solid transparent;
            overflow: hidden;
            border-radius: 5px;
            transform: scale(1) translateZ(0);
          }

          .nectar-recent-posts-slider_multiple_visible .recent-post-container.container .nectar-button {
            opacity: 1;
            margin-top: 20px;
            transform: none!important;
          }

          .nectar-recent-posts-slider_multiple_visible {
            padding-bottom: 100px;
          }
          .wpb_row:not(.full-width-content) .nectar-recent-posts-slider_multiple_visible {
            padding-top: 15px;
          }

          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-bg,
          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-bg:after {
            border-radius: 5px;
          }

          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-slide {
            transition: box-shadow .28s ease;
          }

          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-slide .nectar-recent-post-bg:after {
            background-color: rgba(25,25,25,0.37);
            transition: background-color .28s ease;
          }

          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-slide:not(.no-bg-img):hover .nectar-recent-post-bg:after {
            background-color: rgba(25,25,25,0.24);
          }

          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-slide:not(.no-bg-img) .nectar-recent-post-bg,
          .nectar-recent-posts-slider_multiple_visible .nectar-recent-post-slide:not(.no-bg-img) .nectar-recent-post-bg-wrap {
            background-color: transparent;
            backface-visibility: hidden;
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport .nectar-recent-post-bg-wrap,
          .nectar-recent-posts-slider_multiple_visible .flickity-viewport .nectar-recent-post-bg {
            transition: transform .28s ease;
            z-index: 9;
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport .nectar-recent-post-bg-blur {
            transition: opacity .28s ease;
            filter: blur(35px);
            transform: translateY(38px) translateZ(0);
            opacity: 0;
            z-index: 1;
          }

          .nectar-recent-posts-slider_multiple_visible[data-shadow-hover-type="dark"] .flickity-viewport .nectar-recent-post-bg-blur {
            display: none;
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-page-dots {
            bottom: -80px;
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport:not(.no-hover) .nectar-recent-post-slide:hover {
            transition-delay: 0s !important;
            z-index: 3;
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport:not(.no-hover) .nectar-recent-post-slide:hover .nectar-recent-post-bg-blur {
            opacity: 0.7;
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport:not(.no-hover) .nectar-recent-post-slide:hover .nectar-recent-post-bg {
            transform: scale(1) translateZ(0);
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport .nectar-recent-post-slide .nectar-recent-post-bg {
            transform: scale(1.13) translateZ(0);
          }

          .nectar-recent-posts-slider_multiple_visible .flickity-viewport:not(.no-hover) .nectar-recent-post-slide:hover .nectar-recent-post-bg-wrap {
            transform: scale(1.08) translateZ(0);
          }

          .nectar-recent-posts-slider_multiple_visible[data-shadow-hover-type="dark"] .flickity-viewport .nectar-recent-post-bg-wrap {
            transition: transform .28s ease, box-shadow .28s ease;
          }

          .nectar-recent-posts-slider_multiple_visible[data-shadow-hover-type="dark"] .flickity-viewport:not(.no-hover) .nectar-recent-post-slide:hover .nectar-recent-post-bg-wrap {
            box-shadow: 0 40px 95px -15px rgba(0,0,0,0.15);
          }
          ';
        }

        if(isset( $atts['style'] ) && 'single_large_featured' === $atts['style'] ||
           isset( $atts['style'] ) && 'multiple_large_featured' === $atts['style'] ) {
            
          $load_in_animation = isset($atts['load_in_animation']) && !empty($atts['load_in_animation']) ? esc_attr($atts['load_in_animation']) : false;
          $content_align = isset($atts['single_large_featured_content_alignment']) ? $atts['single_large_featured_content_alignment'] : 'default';

          // Font size
          if( isset($atts['font_size_desktop']) && !empty($atts['font_size_desktop']) ) {
            self::$element_css[] = '.nectar-recent-posts-element[class*="font_size_desktop"] .large-featured-post-title {
                line-height: 1.3;
            }';
          }
          self::$element_css[] = self::font_size_group_styles('nectar-recent-posts-element .large-featured-post-title', false, 'and (min-width: 1000px)', '', $atts);
          
          self::$element_css[] = '
          .nectar-recent-posts-single_featured h2,
          .nectar-recent-posts-single_featured h2 * {
            line-height: 1.3;
          }';

          // Load in animations.
          if ( 'zoom_out_reveal' === $load_in_animation ) {
            self::$element_css[] = '.nectar-recent-posts-single_featured.zoom_out_reveal {
              clip-path: inset(5% 5% 5% 5% round 10px);
              transition: clip-path 1.2s cubic-bezier(0.65,0,0.35,1);
              opacity: 1;
            }
            .nectar-recent-posts-single_featured.zoom_out_reveal.animated-in {
              clip-path: inset(0% 0% 0% 0% round 0px);
            }
            
            .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > * {
              opacity: 0;
              transform: translateY(30px);
              transition: transform 1.2s cubic-bezier(0.25,1,0.5,1), opacity 1.2s cubic-bezier(0.25,1,0.5,1);
            }

            .nectar-recent-posts-single_featured.zoom_out_reveal.animated-in .inner-wrap > * {
              opacity: 1;
              transform: translateY(0);
            }
            ';

            if ( 'bottom' === $content_align ) {
              self::$element_css[] = '
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(1) { transition-delay: 0.75s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(2) { transition-delay: 0.85s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(3) { transition-delay: 0.95s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(4) { transition-delay: 1.05s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(5) { transition-delay: 1.15s; }
              ';
            } else {
              self::$element_css[] = '
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(1) { transition-delay: 0.5s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(2) { transition-delay: 0.6s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(3) { transition-delay: 0.7s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(4) { transition-delay: 0.8s; }
              .nectar-recent-posts-single_featured.zoom_out_reveal .inner-wrap > *:nth-child(5) { transition-delay: 0.9s; }
              ';
            }

          }

        }


        if( isset( $atts['style'] ) && 'single_large_featured' === $atts['style'] ) {
          
          $side_spacing = isset($atts['single_large_featured_content_side_spacing']) && $atts['single_large_featured_content_side_spacing'] !== 'default' ? $atts['single_large_featured_content_side_spacing'] : false;
          $content_align = isset($atts['single_large_featured_content_alignment']) ? $atts['single_large_featured_content_alignment'] : 'default';
          $vertical_padding = isset($atts['large_featured_padding']) ? $atts['large_featured_padding'] : false;

          // side spacing
          if( false !== $side_spacing ) {
            self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-side-spacing="'.$side_spacing.'"] .nectar-recent-post-slide .recent-post-container {
              padding-left: '.esc_attr($side_spacing).';
              padding-right: '.esc_attr($side_spacing).';
            }';

            if( 'bottom' === $content_align ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-side-spacing="'.$side_spacing.'"][data-align="bottom"] .nectar-recent-post-slide .recent-post-container {
                padding-bottom: '.esc_attr($side_spacing).';
              }';
            }
            if( 'top' === $content_align ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-side-spacing="'.$side_spacing.'"][data-align="top"] .nectar-recent-post-slide .recent-post-container {
                padding-top: '.esc_attr($side_spacing).';
              }';
            }
          }

          // content alignment
          ////bottom
          if ( 'bottom' === $content_align ) {
            
            if( $vertical_padding ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-align="'.$content_align.'"][data-padding="'.$vertical_padding.'"] .nectar-recent-post-slide .recent-post-container {
                padding-top: '.(intval($vertical_padding)*2).'%;
              }';
            }


            self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-align="'.$content_align.'"] .nectar-recent-post-slide .recent-post-container {
              padding-bottom: var(--container-padding);
            }';

            // altered shadow
            self::$element_css[] = '.nectar-recent-posts-single_featured[data-align="'.$content_align.'"][data-bg-overlay="diagonal_gradient"] .nectar-recent-post-slide:after {
              background: radial-gradient(circle at 0 100%,rgba(0,0,0,0.75) 0,transparent 70%);
            }';
            
          }

          //// top
          if ( 'top' === $content_align ) {

            if( $vertical_padding ) {
              self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-align="'.$content_align.'"][data-padding="'.$vertical_padding.'"] .nectar-recent-post-slide .recent-post-container {
                padding-bottom: '.(intval($vertical_padding)*2).'%;
              }';
            }

            self::$element_css[] = '#ajax-content-wrap .nectar-recent-posts-single_featured[data-align="'.$content_align.'"] .nectar-recent-post-slide .recent-post-container {
              padding-top: 50px;
            }';
          }

        }

      }


      // Cascading Images.
      else if( false !== strpos($shortcode[0],'[nectar_cascading_images') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Forced Aspect Sizing.
        $ratio = ( isset($atts['element_sizing_aspect']) ) ? esc_attr($atts['element_sizing_aspect']) : '1-1';

        if( isset( $atts['element_sizing'] ) && 'forced' === $atts['element_sizing'] ) {
          self::$element_css[] = '.nectar_cascading_images.forced-aspect.aspect-'.$ratio.' .cascading-image:first-child .bg-layer{
            padding-bottom: '.esc_attr(self::image_aspect_ratio($ratio)).';
          }';
        }

      } // End Cascading Images.


      // Icon List.
      else if( false !== strpos($shortcode[0],'[nectar_icon_list ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Coloring.
        if( isset($atts['color']) && !empty($atts['color']) ) {

          $color = self::locate_color(strtolower($atts['color']));

          // Gradient.
          if( $color && isset($color['gradient']) ) {

            self::$element_css[] = '.nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"] .list-icon-holder[data-icon_type="numerical"] span {
        			 color: '.esc_attr($color['gradient']['to']).';
        			 background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
        			 -webkit-background-clip: text;
        			 -webkit-text-fill-color: transparent;
        			 background-clip: text;
        			 display: inline-block;
        		}';

          }
          // Regular.
          else if( $color && isset($color['color']) ) {

            self::$element_css[] = '.nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"][data-icon-style="border"] .content h4,
            .nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"][data-icon-style="border"] .list-icon-holder[data-icon_type="numerical"] span,
            .nectar-icon-list[data-icon-color="'.esc_attr($color['name']).'"] .nectar-icon-list-item .list-icon-holder[data-icon_type="numerical"] {
              color: '.esc_attr($color['color']).';
            }';

          }

        }

      }

      // Gradient Text.
      else if( false !== strpos($shortcode[0],'[nectar_gradient_text ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Coloring.
        if( isset($atts['color']) && !empty($atts['color']) ) {

          $color = self::locate_color(strtolower($atts['color']));

          // Gradient.
          if( $color && isset($color['gradient']) ) {

            // Diagonal.
            if( isset( $atts['gradient_direction'] ) && 'diagonal' === $atts['gradient_direction'] ) {

              self::$element_css[] = '.nectar-gradient-text[data-color="'.esc_attr($color['name']).'"][data-direction="diagonal"] * {
                color: '.esc_attr($color['gradient']['from']).';
                background-image: linear-gradient(to right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                display: inline-block;
              }';

            }
            // Regular.
            else {

              self::$element_css[] = '.nectar-gradient-text[data-color="'.esc_attr($color['name']).'"][data-direction="horizontal"] * {
                color: '.esc_attr($color['gradient']['from']).';
                background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                display: inline-block;
              }';

            } // end direction.

          } // gradient exists.

        }

      }


      // Sticky Media Sections
      else if( false !== strpos($shortcode[0],'[nectar_sticky_media_sections ') ) {
        
        $atts = shortcode_parse_atts($shortcode_inner);
        $media_width = (isset($atts['media_width'])) ? esc_attr($atts['media_width']) : '50%'; 
        $media_height = (isset($atts['media_height'])) ? esc_attr($atts['media_height']) : '50%'; 
        $content_spacing = (isset($atts['content_spacing'])) ? esc_attr($atts['content_spacing']) : '20vh'; 
        $content_position =  (isset($atts['content_position'])) ? esc_attr($atts['content_position']) : 'right';
        $mobile_aspect_ratio = (isset($atts['mobile_aspect_ratio'])) ? esc_attr($atts['mobile_aspect_ratio']) : '16-9';
        $media_border_radius = (isset($atts['border_radius']) && 'none' !== $atts['border_radius']) ? esc_attr($atts['border_radius']) : false;

        //// Core.
        if( self::$using_front_end_editor ) {
          self::$element_css[] = '
          body #ajax-content-wrap {
             overflow: visible;
          }
          ';
        }
        self::$element_css[] = '
        @media only screen and (min-width: 1000px) {
          
          #boxed #ajax-content-wrap {
            overflow: visible!important;    
            contain: paint;
          }

            html body,
            html body.compensate-for-scrollbar {
              overflow: visible;
            }

            .nectar-sticky-media-sections {
              display: flex;
              gap: 6%;
              flex-wrap: nowrap;
            }

            .nectar-sticky-media-sections.content-position-left {
              flex-direction: row-reverse;
            }

            .nectar-sticky-media-section__featured-media {
              position: sticky;
              top: var(--nectar-sticky-media-sections-vert-y);
              width: 50%;
            }

            .nectar-sticky-media-content__media-wrap {
              display: none;
            }

        }';

        self::$element_css[] = '.nectar-sticky-media-sections.mobile-aspect-ratio-'.$mobile_aspect_ratio.' .nectar-sticky-media-content__media-wrap {
          padding-bottom: '.self::image_aspect_ratio($mobile_aspect_ratio).';
        }';

        self::$element_css[] = '@media only screen and (max-width: 999px) {
          .nectar-sticky-media-section__featured-media {
            display: none;
          }
          .nectar-sticky-media-content__media-wrap {
            display: block;
          }

          .nectar-sticky-media-content__media-wrap {
            position: relative;
            margin-bottom: 45px;
          }
          .nectar-sticky-media-sections .nectar-sticky-media-section__content-section:not(:last-child) {
            margin-bottom: 100px;
          }
          .nectar-sticky-media-sections .nectar-fancy-ul ul {
            margin-bottom: 0;
          }
        }

        
        .nectar-sticky-media-section__media-wrap {
          transition: opacity 0.2s ease 0.1s;
        }

        .nectar-sticky-media-section__media-wrap.active {
          transition: opacity 0.2s ease;
          z-index: 100;
        }

        .nectar-sticky-media-section__media-wrap:not(.active) {
          opacity: 0;
        }

        .nectar-sticky-media-section__media,
        .nectar-sticky-media-section__media-wrap {
          background-size: cover;
          background-position: center;
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
        }

        .nectar-sticky-media-section__media video {
          object-fit: cover;
          width: 100%;
          height: 100%;
        }

        .nectar-sticky-media-section__media video.nectar-lazy-video {
          transition: opacity 0.55s ease 0.25s;
          opacity: 0;
        }
        .nectar-sticky-media-section__media video.nectar-lazy-video.loaded {
          opacity: 1;
        }
        
        .nectar-sticky-media-section__media video.fit-contain {
          object-fit: contain;
        }

        .nectar-sticky-media-section__content {
          flex: 1;
        }
       
        ';

        //// Spacing.
        self::$element_css[] = '@media only screen and (min-width: 1000px) { 
           .nectar-sticky-media-sections.content-spacing-'.esc_attr($content_spacing).' .nectar-sticky-media-section__content-section:not(:last-child) {
          margin-bottom: '.esc_attr($content_spacing).';
        }
      }';

      //// Border Radius.
      if( $media_border_radius ) {
        self::$element_css[] = '.nectar-sticky-media-sections.media-boder-radius-'.$media_border_radius.' .nectar-sticky-media-section__media-wrap,
        .nectar-sticky-media-sections.media-boder-radius-'.$media_border_radius.' .nectar-sticky-media-content__media-wrap {
        border-radius: '.$media_border_radius.';
        overflow: hidden;
        }';
      }


        //// Width.
        self::$element_css[] = '.nectar-sticky-media-sections.media-width-'.self::percent_unit_type_class(esc_attr($media_width)).' .nectar-sticky-media-section__featured-media {
          width: calc('.esc_attr($media_width).' - 3%);
        }
        ';

        //// Height.
        self::$element_css[] = '.nectar-sticky-media-sections.media-height-'.self::percent_unit_type_class(esc_attr($media_height)).' .nectar-sticky-media-section__featured-media {
          height: '.esc_attr($media_height).';
        }
      
        ';

      }


      else if( false !== strpos($shortcode[0],'[nectar_sticky_media_section ') ) {
        $atts = shortcode_parse_atts($shortcode_inner);

        $acceptable_alignments = array('left-top','left-center','left-bottom','center-top','center-center','center-bottom','right-top','right-center','right-bottom');
        
        $video_alignment = ( isset($atts['video_alignment']) && in_array($atts['video_alignment'],$acceptable_alignments) ) ? $atts['video_alignment'] : false;
        
        if( $video_alignment ) {
          self::$element_css[] = '.nectar-sticky-media-section__featured-media video.align-'.$video_alignment.' {
            object-position: '.str_replace('-',' ',$video_alignment).';
          }';
        }

      }


      // Toggles.
      else if( false !== strpos($shortcode[0],'[toggles ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $toggle_style = (isset($atts['style'])) ? $atts['style'] : 'default';
        $toggle_border_radius = (isset($atts['border_radius'])) ? $atts['border_radius'] : false;

        if( 'default' === $toggle_style ) {
          self::$element_css[] = 'div[data-style="default"] .toggle > .toggle-title a,
          div[data-style="default"] .toggle > .toggle-title{
            font-size:14px;
            line-height:14px
          }
          .light .toggles[data-style="default"] .toggle > .toggle-title a {
            color: #fff;
          }
          .light .toggles[data-style="default"] .toggle >div{
            background-color: transparent;
          }
          ';

          if( $toggle_border_radius && 'none' != $toggle_border_radius ) {
            self::$element_css[] = 'div[data-style="default"] .toggle .toggle-title a {
              border-radius: '.esc_attr($toggle_border_radius).';
            }
            div[data-style="default"] .toggle.open .toggle-title a {
              border-radius: '.esc_attr($toggle_border_radius).' '. esc_attr($toggle_border_radius).' 0 0;
            }
            body .toggle[data-inner-wrap="true"] > div {
              border-radius: 0 0 '.esc_attr($toggle_border_radius).' '. esc_attr($toggle_border_radius).';
            }';
          }

        } 

        else if( 'minimal' === $toggle_style ) {
          self::$element_css[] = '
          div[data-style="minimal"] .toggle[data-inner-wrap="true"] > div .inner-toggle-wrap {
            padding: 0 0 30px 0;
          }
          div[data-style="minimal"] .toggle > .toggle-title a{
            padding:30px 70px 30px 0px;
            transition:color 0.15s ease;
          }
          div[data-style="minimal"] .toggle{
            border-bottom:2px solid rgba(0,0,0,0.08);
            transition:border-color 0.15s ease;
          }
          body div[data-style="minimal"] .toggle {
            margin-bottom:0
          }
          div[data-style="minimal"] .toggle > .toggle-title i:before{
            content:" ";
            top:14px;
            left:6px;
            margin-top:-2px;
            width:14px;
            height:2px;
            position:absolute;
            background-color:#888;
            transition:background-color 0.15s ease;
          }
          div[data-style="minimal"] .toggle > .toggle-title i:after{
            content: " ";
            top:6px;
            left:14px;
            width:2px;
            margin-left:-2px;
            height:14px;
            position:absolute;
            background-color:#888;
            transition:transform 0.45s cubic-bezier(.3,.4,.2,1),background-color 0.15s ease;
          }
          .light div[data-style="minimal"] .toggle {
            border-color:rgba(255,255,255,0.2)
          }
          @media only screen and (max-width : 690px) {
            div[data-style="minimal"] .toggle > .toggle-title a {
              font-size: 20px;
              line-height: 24px;
            }
          }
          
          ';
        }

        else if( 'minimal_shadow' === $toggle_style ) {
          
          self::$element_css[] = '
          .toggles--minimal-shadow .toggle > .toggle-title a {
            color: inherit;
          }

          .toggles--minimal-shadow .toggle.default > .toggle-title a:hover,
          .toggles--minimal-shadow .toggle.default.open > .toggle-title a {
            color: #000;
          }
          .span_12.light .toggles--minimal-shadow .toggle.default > .toggle-title a:hover,
          .span_12.light .toggles--minimal-shadow .toggle.default.open > .toggle-title a {
            color: #fff;
          }


          .toggles--minimal-shadow .toggle > .toggle-title i:before,
          .toggles--minimal-shadow .toggle > .toggle-title i:after{
            background-color:#888;
          }

          .toggles--minimal-shadow .toggle.default.open > .toggle-title i:after,
          .toggles--minimal-shadow .toggle.default.open > .toggle-title i:before,
          .toggles--minimal-shadow .toggle.default:hover > .toggle-title i:after,
          .toggles--minimal-shadow .toggle.default:hover > .toggle-title i:before {
            background-color: #000;
          }

          .toggles--minimal-shadow .toggle.default.open > .toggle-title i,
          .toggles--minimal-shadow .toggle.default:hover > .toggle-title i {
            border-color: #000;
          }

          .span_12.light .toggles--minimal-shadow .toggle.default.open > .toggle-title i:after,
          .span_12.light .toggles--minimal-shadow .toggle.default.open > .toggle-title i:before,
          .span_12.light .toggles--minimal-shadow .toggle.default:hover > .toggle-title i:after,
          .span_12.light .toggles--minimal-shadow .toggle.default:hover > .toggle-title i:before {
            background-color: #fff;
          }
          .span_12.light .toggles--minimal-shadow .toggle.default.open > .toggle-title i,
          .span_12.light .toggles--minimal-shadow .toggle.default:hover > .toggle-title i {
            border-color: #fff;
          }

          .toggles--minimal-shadow .toggle[data-inner-wrap="true"] > div .inner-toggle-wrap {
            padding: 0 0 30px 0;
          }

          .toggles--minimal-shadow .toggle > .toggle-title a{
            padding:30px 70px 30px 0px;
            transition:color 0.15s ease;
          }
          .toggles--minimal-shadow .toggle{
            border-bottom: 1px solid rgba(0,0,0,0.08);
          }
          body .toggles--minimal-shadow .toggle {
            margin-bottom:0;
            padding: 0 40px;
            position: relative;
            transition: border-color 0.15s ease;
          }
          div[data-style*="minimal"] .toggle.open {
            border-color: transparent;
          }
          .toggles--minimal-shadow .toggle:before {
            content: "";
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            pointer-events: none;
            width: 100%;
            height: 100%;
            box-shadow: 0 90px 70px 0 rgba(0, 0, 0, 0.04), 
                        0 40px 35px 0 rgba(0, 0, 0, 0.03), 
                        0 25px 15px 0 rgba(0, 0, 0, 0.03), 
                        0 11px 7px 0 rgba(0, 0, 0, 0.03), 
                        0 2px 5px 0 rgba(0, 0, 0, 0.03);
            transition: opacity 0.15s ease;
            opacity: 0;
          }
          
          div[data-style*="minimal"] .toggle.open:before {
            opacity: 1;
            transition: opacity 0.45s cubic-bezier(.3,.4,.2,1);
          }


          .toggles--minimal-shadow .toggle > .toggle-title i:before{
            content:" ";
            top:14px;
            left:6px;
            margin-top:-2px;
            width:14px;
            height:2px;
            position:absolute;
            transition:transform 0.45s cubic-bezier(.3,.4,.2,1),background-color 0.15s ease;
          }

          .toggles--minimal-shadow .toggle > .toggle-title i:after{
            content: " ";
            top:6px;
            left:14px;
            width:2px;
            margin-left:-2px;
            height:14px;
            position:absolute;
            transition:transform 0.45s cubic-bezier(.3,.4,.2,1),background-color 0.15s ease;
          }
          .light .toggles--minimal-shadow .toggle {
            border-color:rgba(255,255,255,0.2)
          }
          div[data-style*="minimal"].toggles--minimal-shadow .toggle i {
            transition:transform 0.45s cubic-bezier(.3,.4,.2,1),border-color 0.15s ease;
          }
          div[data-style*="minimal"] .toggle.open .toggle-title i {
            transform: rotate(90deg);
          }
          div[data-style*="minimal"] .toggle.open .toggle-title i:before {
            -ms-transform: scale(0,1);
            transform: scale(0,1);
            -webkit-transform: scale(0,1);
          }
          div[data-style*="minimal"] .toggle.open .toggle-title i:after {
            -ms-transform: scale(1,1);
            transform: scale(1,1);
            -webkit-transform: scale(1,1);
          }';

          if (class_exists('NectarThemeManager') && 
              isset(NectarThemeManager::$colors['overall_font_color']) ) {
                self::$element_css[] = '

                body .toggles--minimal-shadow .toggle > .toggle-title i:before,
                body .toggles--minimal-shadow .toggle > .toggle-title i:after {
                  background-color: '.esc_attr(NectarThemeManager::$colors['overall_font_color']).';
                }

                body .dark div[data-style*="minimal"].toggles--minimal-shadow .toggle:not(.open):not(:hover) > .toggle-title i {
                  border-color: '.esc_attr(NectarThemeManager::$colors['overall_font_color']).';
                }';
          }

          if( $toggle_border_radius && 'none' != $toggle_border_radius ) {
            self::$element_css[] = '.toggles--minimal-shadow .toggle:before {
              border-radius: '.esc_attr($toggle_border_radius).';
            }';
          }

        }

        else if( 'minimal_small' === $toggle_style ) {
          self::$element_css[] = '
          div[data-style="minimal_small"] .toggle[data-inner-wrap="true"] > div {
            padding: 0;
          }
          body div[data-style="minimal_small"] .toggle > div .inner-toggle-wrap {
            padding-top: 1.4em;
            padding-bottom: 0;
          }
          
          div[data-style="minimal_small"] .toggle > .toggle-title {
            display: inline-block;
            padding: 0 0 4px;
          }

          div[data-style="minimal_small"] .toggle > .toggle-title a {
            padding: 0
          }

          div[data-style="minimal_small"] .toggle.default > .toggle-title a:hover,
          div[data-style="minimal_small"] .toggle.default.open > .toggle-title a {
            color: #000;
          }

          div[data-style="minimal_small"] .toggle.default > .toggle-title:after {
            background-color: #000;
          }
          .span_12.light div[data-style="minimal_small"] .toggle.default > .toggle-title a:hover,
          .span_12.light div[data-style="minimal_small"] .toggle.default.open > .toggle-title a {
            color: #fff;
          }
          .span_12.light div[data-style="minimal_small"] .toggle > .toggle-title:before {
            background-color: rgba(255,255,255,0.2);
          }
          .span_12.light div[data-style="minimal_small"] .toggle.default:hover > .toggle-title:after,
          .span_12.light div[data-style="minimal_small"] .toggle.default.open > .toggle-title:after,
          .span_12.light div[data-style="minimal_small"] .toggle.default > .toggle-title:after {
            background-color: #fff;
          }
          div[data-style="minimal_small"] .toggle > .toggle-title:after,
          div[data-style="minimal_small"] .toggle > .toggle-title:before {
            display: block;
            content: "";
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(0,0,0,0.1);
            height: 2px;
            left: 0;
            pointer-events: none;
          }
          div[data-style="minimal_small"] .toggle > .toggle-title:after {
            -webkit-transform: scaleX(0);
            transform: scaleX(0);
            -webkit-transition: transform 0.5s cubic-bezier(0.3, 0.4, 0.1, 1);
            transition: transform 0.5s cubic-bezier(0.3, 0.4, 0.1, 1);
            transform-origin: left;
          }
          div[data-style="minimal_small"] .toggle.open > .toggle-title:after,
          div[data-style="minimal_small"] .toggle:hover > .toggle-title:after {
            -webkit-transform: scaleX(1);
            transform: scaleX(1);
          }
          div[data-style="minimal_small"] .toggle >div {
            padding-top: 1.3em;
            padding-bottom: 0;
          }
          div[data-style="minimal_small"] .toggle > .toggle-title i {
            display: none;
          }
          div[data-style="minimal_small"] .toggle {
            margin-bottom: 1.4em;
          }
          ';
        }

      }


      // Tabbed.
      else if( false !== strpos($shortcode[0],'[tabbed_section ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);
        
        // v13 update assist - for cached stylesheets, ensure first tab still displays
        self::$element_css[] = 'body .row .tabbed >div:first-of-type {
            display: block;
            opacity: 1;
            visibility: visible;
            position: relative;
            left: 0;
        }';
        
        // Coloring.
        $tab_color = (isset($atts['tab_color'])) ? $atts['tab_color'] : 'accent-color';

        if( $tab_color ) {

          $color = self::locate_color(strtolower($tab_color));

          // Gradient.
          if( $color && isset($color['gradient']) ) {

            // Default.
            if( isset($atts['style']) && 'default' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style*="default"][data-color-scheme="'.esc_attr($color['name']).'"] ul li a:before {
                background: '.esc_attr($color['gradient']['to']).';
         		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';

            }

            // Material.
            if( isset($atts['style']) && 'material' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li a:before {
                background: '.esc_attr($color['gradient']['from']).';
         		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul:after {
          			background-color: '.esc_attr($color['gradient']['from']).';
          		}

          		.tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li .active-tab:after {
          			box-shadow: 0px 18px 50px '.esc_attr($color['gradient']['from']).';
          		}';

            }
            // Minimal Alt.
            else if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal_alt"] .magic-line {
                background: '.esc_attr($color['gradient']['from']).';
          		  background: linear-gradient(to right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';
            }

            // Minimal Flexible.
            else if( isset($atts['style']) && 'minimal_flexible' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style="minimal_flexible"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav > li a:before {
                box-shadow: 0px 8px 22px '. esc_attr($color['gradient']['from']) .';
              }';
            }

            // Vertical Sticky.
            else if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] ) {

              self::$element_css[] = '.nectar-scrolling-tabs[data-color-scheme="'.esc_attr($color['name']).'"] .scrolling-tab-nav .line {
                background: '.esc_attr($color['gradient']['from']).';
                background: linear-gradient(to bottom, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
              }';

            }

            // All Vertical.
            if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] ||
                     isset($atts['style']) && 'vertical_modern' === $atts['style'] ||
                     isset($atts['style']) && 'vertical' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style*="vertical"][data-color-scheme="'.esc_attr($color['name']).'"] ul li a:before,
                .tabbed[data-style="vertical"][data-color-scheme="'.esc_attr($color['name']).'"] > ul li .active-tab {
                  background: '.esc_attr($color['gradient']['from']).';
           		    background: linear-gradient(to bottom right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }
                ';
            }

            // All Minimal.
            if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ||
                isset($atts['style']) && 'minimal_flexible' === $atts['style'] ||
                isset($atts['style']) && 'minimal' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style*="minimal"][data-color-scheme="'.esc_attr($color['name']).'"] > ul li a:after {
                  background: '.esc_attr($color['gradient']['from']).';
                  background: linear-gradient(to right, '.esc_attr($color['gradient']['to']).', '.esc_attr($color['gradient']['from']).');
                }';
            }

            // Toggle button
            if( isset($atts['style']) && 'toggle_button' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-style="toggle_button"][data-color-scheme="'.esc_attr($color['name']).'"] > ul .toggle-button-inner {
                background-clip: inherit;
              }';
            }

          }

          // Regular.
          else if( $color && isset($color['color']) ) {

            // Default.
            if( isset($atts['style']) && 'default' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="default"] li:not(.cta-button) .active-tab {
                background-color: '.esc_attr($color['color']).';
                border-color: '.esc_attr($color['color']).';
              }';
            }

            // Minimal.
            else if( isset($atts['style']) && 'minimal' === $atts['style'] ) {

              self::$element_css[] = 'body.material .tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal"]:not(.using-icons) >ul li:not(.cta-button) a:hover,
              body.material .tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal"]:not(.using-icons) >ul li:not(.cta-button) .active-tab {
                color: '.esc_attr($color['color']).';
              }';
            }

            // Minimal Alt.
            else if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ) {
              self::$element_css[] = '.tabbed[data-color-scheme="'.esc_attr($color['name']).'"][data-style="minimal_alt"] .magic-line {
                background-color: '.esc_attr($color['color']).';
              }';
            }

            // Minimal Flexible.
            else if( isset($atts['style']) && 'minimal_flexible' === $atts['style'] ) {

              self::$element_css[] = '.tabbed[data-style="minimal_flexible"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav > li a:before {
                box-shadow: 0px 8px 22px '. esc_attr($color['color']) .';
              }';
            }

            // Vertical Modern.
            else if( isset($atts['style']) && 'vertical_modern' === $atts['style'] || 
                    isset($atts['style']) && 'vertical' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style="vertical_modern"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav li .active-tab,
                .tabbed[data-style="vertical"][data-color-scheme="'.esc_attr($color['name']).'"] > ul li .active-tab {
                  background-color: '.esc_attr($color['color']).';
                }';
            }

            // Vertical Sticky.
            else if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] ) {

              self::$element_css[] = '#ajax-content-wrap .nectar-scrolling-tabs[data-color-scheme="'.esc_attr($color['name']).'"] .scrolling-tab-nav .line,
              #ajax-content-wrap [data-stored-style="vs"] .tabbed[data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav li a:before {
                background-color: '.esc_attr($color['color']).';
              }';

            }

            // Material.
            if( isset($atts['style']) && 'material' === $atts['style'] ) {

              self::$element_css[] = 'body .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] .wpb_tabs_nav li a:not(.active-tab):hover {
                color: '.esc_attr($color['color']).';
              }
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul:after,
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li .active-tab {
                background-color: '.esc_attr($color['color']).';
              }
              .tabbed[data-style*="material"][data-color-scheme="'.esc_attr($color['name']).'"] ul li .active-tab:after {
              	box-shadow: 0px 18px 50px  '.esc_attr($color['color']).';
              }';

            }


            // All Minimal.
            if( isset($atts['style']) && 'minimal_alt' === $atts['style'] ||
                isset($atts['style']) && 'minimal_flexible' === $atts['style'] ||
                isset($atts['style']) && 'minimal' === $atts['style'] ) {

                self::$element_css[] = '.tabbed[data-style*="minimal"][data-color-scheme="'.esc_attr($color['name']).'"] > ul li a:after {
                  background-color: '.esc_attr($color['color']).';
                }';
            }


          }

        } // Tab color isset.

        // Toggle button style.
        if( isset($atts['style']) && 'toggle_button' === $atts['style']) {
          self::$element_css[] = '.tabbed[data-style="toggle_button"] .wpb_tabs_nav {
            text-align: center;
          }


          .tabbed[data-style="toggle_button"] .wpb_tabs_nav li {
            float: none;
            display: inline-block; 
          }

          .tabbed[data-style="toggle_button"] .wpb_tabs_nav li a {
             background-color: transparent;
             color: inherit; 
             border: none;
          }

          .tabbed[data-style="toggle_button"] .wpb_tabs_nav li a:not(.active-tab) {
            opacity: 0.5;
          }

          .tabbed[data-style="toggle_button"] .wpb_tabs_nav {
              display: flex;
              justify-content: center;
              align-items: center;
              margin-bottom: 30px;
          }

          .span_12.light .tabbed[data-style="toggle_button"] .wpb_tabs_nav {
            color: #fff;
          }

          .tabbed[data-style="toggle_button"] .wpb_tabs_nav .toggle-button-inner {
            display: block;
            border-radius: 100px;
            width: 70px;
            height: 28px;
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 0 0 4px currentColor;
         }
         
         .tabbed[data-style="toggle_button"] .wpb_tabs_nav .toggle-button {
            padding: 0 10px;
         }

         .tabbed[data-style="toggle_button"] .wpb_tabs_nav .toggle-button .circle {
           display: block;
           border-radius: 100px;
           background-color: #fff;
           height: 28px;
           width: 70px;
           left: 0px;
           top: 0px;
           transition: transform 0.45s cubic-bezier(0.23, 0.46, 0.4, 1);
           transform: translateX(42px);
         }
         .tabbed[data-style="toggle_button"] .wpb_tabs_nav li.active-tab + .toggle-button .circle {
           transform: translateX(-42px);
         }
       ';

        }

        // Minimal alt Style
        if( isset($atts['style']) && 'minimal_alt' === $atts['style']) {
          self::$element_css[] = '
          .tabbed[data-style="minimal_alt"] >ul li:not(.cta-button) a svg,
          .tabbed[data-style="minimal_alt"] >ul li:not(.cta-button) .active-tab svg {
              fill: currentColor;
            }
            
            .tabbed[data-style="minimal_alt"] ul{
              position:relative
          }
          @media only screen and (max-width:999px){
            .tabbed[data-style="minimal_alt"] .magic-line{
              position: absolute;
            }
          }
          @media only screen and (min-width:1000px){
          
              body .tabbed[data-style="minimal_alt"] >ul li a:after{
                  display:none
              }
              .tabbed[data-style="minimal_alt"] .magic-line{
                  position:absolute;
                  bottom:8px;
                  left:0;
                  height:2px;
                  background-color:white;
                  width:1px;
                  transform:translateX(0);
                  transform-origin:left;
                  transition:transform 0.4s,visibility 0s;
                  -webkit-backface-visibility:hidden;
                  backface-visibility:hidden;
                  will-change:transform
              }
          }
          
          
          .tabbed[data-style="minimal_alt"] >ul li:not(.cta-button) a{
                padding:2px 0px!important
            }
            .tabbed[data-style="minimal_alt"] >ul li:not(.cta-button){
                margin:0 30px!important
            }
            @media only screen and (max-width : 690px) {
              .tabbed[data-style="minimal_alt"] > ul li:not(.cta-button) {
                  margin: 0 10px!important;
              }
            }';

          }

          // Vertical classic.
          if( isset($atts['style']) && 'vertical' === $atts['style']) {
            self::$element_css[] = '
            .tabbed[data-style="vertical"].clearfix:after {
              clear: both;
            }
            .tabbed[data-style="vertical"].clearfix:before,
            .tabbed[data-style="vertical"].clearfix:after {
              content: " ";
              display: table;
            }
            ';

          }

          // Vertical modern.
          if( isset($atts['style']) && 'vertical_modern' === $atts['style'] || self::$using_front_end_editor ) {
            self::$element_css[] = '
            .tabbed[data-style="vertical_modern"].clearfix:after {
              clear: both;
            }
            
            .tabbed[data-style="vertical_modern"].clearfix:before,
            .tabbed[data-style="vertical_modern"].clearfix:after {
              content: " ";
              display: table;
            }
            .tabbed[data-style="vertical_modern"] >div{
              padding-left:80px
            }
            .tabbed[data-style="vertical_modern"] > .wpb_tabs_nav li i {
              transition: none;
            }
            .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li i,
            .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li svg {
                  margin-right: 13px;
                  position: relative;
            }
              .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a {
                  background-color: rgba(0,0,0,0.04);
            }
              .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a:hover {
                  background-color: rgba(0,0,0,0.025);
            }
              .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a {
                  display: flex;
                  align-items: center;
            }
              .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li {
                  margin-bottom: 12px;
            }
              .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a {
                  border-radius: 6px;
                  padding: 25px 20px;
                  overflow: hidden;
            }
              @media only screen and (max-width: 999px) {
                  .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a {
                      padding: 15px 20px;
                }
            }
              .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li .active-tab {
                  box-shadow: 0px 15px 50px rgba(0,0,0,0.2);
            }';

          }

        // Minimal Flexible.
        if( isset($atts['style']) && 'minimal_flexible' === $atts['style']) {
          self::$element_css[] = '
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav {
              margin-bottom: 65px;
          }

          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav {
            display: flex;
            flex-wrap: nowrap;
          }

          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li {
            float: none;
            flex-grow: 1;
            display: block;
            border-bottom: 1px solid rgba(0,0,0,0.1);
          }
          .light .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li  {
            border-bottom: 1px solid rgba(255,255,255,0.1);
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a {
            background-color: transparent;
            text-align: center;
            color: inherit;
            border: none;
            font-size: 1.2em;
            padding: 25px 30px;
          }

          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a:not(.active-tab):hover {
            opacity: 0.6;
          }

          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a.active-tab:hover {
            background-color: transparent;
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a:not(.active-tab):after {
            background: none;
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a:after {
            transform: scaleY(0);
            transform-origin: bottom;
            height: 4px;
            bottom: 0;
            transition: transform .4s ease;
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a.active-tab:after {
            transform: scaleY(1);
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a.active-tab svg {
            fill: #000;
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a:before {
            position: absolute;
            display: block;
            content: "";
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 4px;
            opacity: 0;
            transition: opacity .4s ease;
            box-shadow: 0px 8px 24px rgba(0,0,0,0.15);
          }
          .tabbed[data-style="minimal_flexible"] .wpb_tabs_nav > li a.active-tab:before {
            opacity: 0.3;
          }
          
          ';
        }

        // Material
        if( isset($atts['style']) && in_array($atts['style'], array('material','vertical_material')) ) {

          self::$element_css[] = '
          .tabbed[data-style*="material"] .wpb_tabs_nav li i {
              transition: none;
         }
          .tabbed[data-style*="material"] .wpb_tabs_nav li i,
          .tabbed[data-style*="material"] .wpb_tabs_nav li svg {
              display: block;
              text-align: center;
              margin: 0 0 8px 0;
              font-size: 32px;
          }
          .tabbed[data-style*="material"] .wpb_tabs_nav {
            margin-bottom: 65px;
       }
        .tabbed[data-style*="material"] .wpb_tabs_nav li {
            float: none;
            display: inline-block;
       }
        .tabbed[data-style*="material"] .wpb_tabs_nav:after,
        .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-1"] .wpb_tabs_nav li a:before,
        .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-2"] .wpb_tabs_nav li a:before {
            background-color: #000;
            display: block;
            left: 0;
            width: 100%;
            position: absolute;
            content: "";
            height: 1px;
            opacity: 0.25;
            visibility: visible;
          }
            .tabbed[data-style*="material"] ul:after {
                opacity: 0.3;
          }
            .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-1"] .wpb_tabs_nav li a,
            .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-2"] .wpb_tabs_nav li a,
            .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-1"] .wpb_tabs_nav li .active-tab,
            .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-2"] .wpb_tabs_nav li .active-tab {
                background-color: transparent;
          }
          .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-1"] .wpb_tabs_nav li a:before,
          .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-2"] .wpb_tabs_nav li a:before {
              height: 100%;
              top: 0;
              opacity: 0;
              transition: all 0.3s cubic-bezier(0.12,0.75,0.4,1);
              z-index: -1;
              border-radius: 5px 5px 0 0px;
         }
         .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-1"] ul li .active-tab:before,
         .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-2"] ul li .active-tab:before {
             opacity: 1;
        }
        .tabbed[data-style*="material"][data-full-width-line="true"] .wpb_tabs_nav:after {
          left: 50%;
          right: 50%;
          margin-left: -50vw;
          margin-right: -50vw;
          width: 100vw;
     }
      .tabbed[data-style*="material"] .wpb_tabs_nav li i,
      .tabbed[data-style*="material"] .wpb_tabs_nav li svg {
          font-size: 22px;
          margin-top: 7px;
          line-height: 22px;
          height: auto;
          margin-bottom: 5px;
          width: auto;
     }
     .tabbed[data-style*="material"] .wpb_tabs_nav li a:not(.active-tab):hover,
      body .tabbed[data-style*="material"][data-color-scheme] .wpb_tabs_nav li a:not(.active-tab):hover {
          color: #000;
      }
      .tabbed[data-style*="material"] .wpb_tabs_nav li a:not(.active-tab):hover svg {
          fill: #000;
      }
      .tabbed[data-style*="material"] .wpb_tabs_nav li .active-tab:after {
          opacity: 0.3;
      }
      .tabbed[data-style*="material"] .wpb_tabs_nav li a:after {
          transition: all 0.3s cubic-bezier(0.12,0.75,0.4,1);
          opacity: 0.3;
          display: block;
          content: "";
          width: 92%;
          height: 92%;
          top: 4%;
          left: 4%;
          z-index: -2;
          position: absolute;
      }
      .tabbed[data-style*="material"] .wpb_tabs_nav li svg {
        margin: 0 auto
       }
        .span_12.light .tabbed[data-style*="material"] >ul li a:not(.active-tab) {
            color: rgba(255,255,255,0.7);
        }
        .span_12.light .tabbed[data-style*="material"] >ul li a:hover {
            color: rgba(255,255,255,1);
        }

        .tabbed[data-icon-size="30"][data-style*="material"] .wpb_tabs_nav li i,
        .tabbed[data-icon-size="32"][data-style*="material"] .wpb_tabs_nav li i,
        .tabbed[data-icon-size="34"][data-style*="material"] .wpb_tabs_nav li i,
        .tabbed[data-icon-size="36"][data-style*="material"] .wpb_tabs_nav li i,
        .tabbed[data-icon-size="30"][data-style*="material"] .wpb_tabs_nav li svg,
        .tabbed[data-icon-size="32"][data-style*="material"] .wpb_tabs_nav li svg,
        .tabbed[data-icon-size="34"][data-style*="material"] .wpb_tabs_nav li svg,
        .tabbed[data-icon-size="36"][data-style*="material"] .wpb_tabs_nav li svg {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .tabbed[data-style="material"] .wpb_tabs_nav li a {
            transition: all 0.3s cubic-bezier(0.12,0.75,0.4,1);
            padding-top: 13px;
            padding-bottom: 13px;
            border: none;
            border-radius: 5px 5px 0 0px;
        }
        .tabbed[data-style="material"] >ul li a:not(.active-tab) {
            background-color: transparent;
        }

    
        @media only screen and (max-width : 690px) {
          .tabbed[data-style*="material"] ul:after {
            display: none;
          }
            .tabbed[data-style="material"] .wpb_tabs_nav li a,
            .tabbed[data-style="material"] .wpb_tabs_nav li {
                display: block;
                text-align: center;
          }
            .tabbed[data-style="material"] ul li a,
            .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-1"] ul li a:before,
            .tabbed[data-style*="material"][data-color-scheme="extra-color-gradient-2"] ul li a:before {
                border-radius: 5px;
          }
         }
          
          ';

        }

        if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] || self::$using_front_end_editor ) {
          
          self::$element_css[] = '
          
            .nectar-scrolling-tabs {
              display: flex;
              flex-direction: row;
              flex-wrap: nowrap;
              align-items: flex-start;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav {
              width: 25%;
              min-width: 225px;
              margin-left: 0;
              margin-bottom: 0;
              position: relative;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul {
              margin-left: 0;
              margin-bottom: 0;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul li {
              list-style: none;
              float: none;
              display: block;
            }
            .nectar-scrolling-tabs[data-navigation-width="wide"] .scrolling-tab-nav {
              width: 33%;
              min-width: 275px;
            }

            .nectar-scrolling-tabs[data-navigation-width="narrow"] .scrolling-tab-nav {
              width: 20%;
              min-width: 175px;
            }
            .nectar-scrolling-tabs .scrolling-tab-content {
              flex: 1;
              padding-left: 7%;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav  a {
              color: inherit;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav h6,
            .nectar-scrolling-tabs .scrolling-tab-nav h5,
            .nectar-scrolling-tabs .scrolling-tab-nav h4,
            .nectar-scrolling-tabs .scrolling-tab-nav h3,
            .nectar-scrolling-tabs .scrolling-tab-nav h2 {
              margin-bottom: 0;
              line-height: 1.3em;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav ul li {
              line-height: 1em;
              opacity: 0.45;
              padding-left: 50px;
              padding-bottom: 35px;
              transition: opacity 0.25s ease;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav ul[data-spacing="15px"] li {
              padding-bottom: 15px;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul[data-spacing="20px"] li {
              padding-bottom: 20px;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul[data-spacing="25px"] li {
              padding-bottom: 25px;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul[data-spacing="30px"] li {
              padding-bottom: 30px;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul[data-spacing="40px"] li {
              padding-bottom: 40px;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav ul[data-spacing="45px"] li {
              padding-bottom: 45px;
            }


              @media only screen and (min-width: 1000px) {
               
                .nectar-scrolling-tabs .scrolling-tab-mobile-title {
                  margin-bottom: 60px;
                }
                .full-width-content .nectar-scrolling-tabs .scrolling-tab-mobile-title {
                  width: 50px;
                  margin-left: auto;
                  margin-right: auto;
                }
                .nectar-scrolling-tabs.initalized:not(.navigation_func_active_link_only) .scrolling-tab-content {
                  margin-top: -61px;
                }
              }

              @media only screen and (max-width: 999px) {
                .nectar-scrolling-tabs[data-tab-spacing="30%"] .scrolling-tab-content > div,
                .nectar-scrolling-tabs[data-tab-spacing="40%"] .scrolling-tab-content > div,
                .nectar-scrolling-tabs[data-tab-spacing="50%"] .scrolling-tab-content > div {
                  padding-top: 10%;
                  padding-bottom: 10%;
                }

                .nectar-scrolling-tabs {
                  display: block;
                }
              }


            .nectar-scrolling-tabs .scrolling-tab-content > div {
              position: relative;
            }

            .nectar-scrolling-tabs[data-tab-spacing] .scrolling-tab-content > div:first-child {
              padding-top: 0;
            }
            .nectar-scrolling-tabs[data-tab-spacing] .scrolling-tab-content > div:last-child {
              padding-bottom: 0;
              margin-bottom: 0;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav .theiaStickySidebar ul li:last-child {
              padding-bottom: 0;
            }


            .nectar-scrolling-tabs .scrolling-tab-nav .menu-item .sub-desc {
              padding-top: 10px;
              line-height: 1.6em;
              display: block;
              opacity: 0.7;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav .menu-item.has-icon {
              display: flex;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav .svg-icon-link svg {
              width: 24px;
            }

            .scrolling-tab-content .im-icon-wrap.tab-icon {
              display: none;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav .svg-icon-link {
              padding-top: 0;
            }
            .nectar-scrolling-tabs .scrolling-tab-nav i,
            .nectar-scrolling-tabs .scrolling-tab-nav .svg-icon-link {
              margin-right: 15px;
            }
            .nectar-scrolling-tabs[data-nav-tag="h2"] .scrolling-tab-nav i,
            .nectar-scrolling-tabs[data-nav-tag="h2"] .scrolling-tab-nav .svg-icon-link svg {
              font-size: 30px;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav a {
              padding-top: 5px;
            }

            .nectar-scrolling-tabs .scrolling-tab-mobile-title {
              min-height: 1px;
            }

            .nectar-scrolling-tabs .scrolling-tab-mobile-title .inner {
              display: none;
              margin-bottom: 25px;
            }

            .scrolling-tab-mobile-title i {
              font-size: 28px;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav .active {
              opacity: 1;
            }

            .nectar-scrolling-tabs .scrolling-tab-nav .theiaStickySidebar:before,
            .nectar-scrolling-tabs .scrolling-tab-nav .n-sticky > .line {
              display: block;
              position: absolute;
              left: 3px;
              top: 0;
              content: "";
              background-color: rgba(0,0,0,0.1);
              width: 1px;
              height: 100%;
            }

            .light .nectar-scrolling-tabs .scrolling-tab-nav .theiaStickySidebar:before,
            .light .nectar-scrolling-tabs .scrolling-tab-nav .n-sticky > .line {
              background-color: rgba(255,255,255,0.2);
            }

            .nectar-scrolling-tabs .scrolling-tab-nav.single-tab .skip-hash {
              pointer-events: none;
            }

            .scrolling-tab-nav .menu-item .sub-desc .nectar-line-break,
            .scrolling-tab-nav .menu-item .sub-desc br {
              display: block;
              content: "";
              margin-bottom: 1em;
            }

            @media only screen and (max-width: 999px ) {
              
              .nectar-scrolling-tabs .scrolling-tab-content {
                padding-left: 0;
              }
              .nectar-scrolling-tabs:not([data-m-display="hidden"]) .scrolling-tab-mobile-title .inner {
                display: block;
              }
              .nectar-scrolling-tabs .scrolling-tab-content > div {
                padding-bottom: 40px;
              }

            }';
          
        }
         
        // Frontened editor.
        if( self::$using_front_end_editor ) {
          self::$element_css[] = '[data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a {
            background-color: transparent!important;
            padding: 0 0 0 40px;
            margin-bottom: 35px;
          }
          [data-stored-style-aspect="active_link_only"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a {
            padding-left: 0;
          }
          
          [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li:last-child,
          [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li:last-child a {
            margin-bottom: 0;
          }
          
          [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a:before {
            display: none;
          }
          
          [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li .active-tab {
            box-shadow: none;
            color: inherit;
          }
          [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a:before {
            opacity: 0;
            background-color: #000;
            transition: opacity 0.3s ease;
          }
          [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a.active-tab:before {
            opacity: 1
          }
          
          [data-stored-style="vs"]:not([data-stored-style-aspect="active_link_only"]) .tabbed[data-style="vertical_modern"] .wpb_tabs_nav:before,
          #ajax-content-wrap [data-stored-style="vs"]:not([data-stored-style-aspect="active_link_only"]) .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a:before {
            display: block;
            position: absolute;
            left: 3px;
            top: 0;
            content: "";
            background-color: rgba(0,0,0,0.1);
            width: 1px;
            height: 100%;
          }

          .light [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav:before,
          .light #ajax-content-wrap [data-stored-style="vs"] .tabbed[data-style="vertical_modern"] .wpb_tabs_nav li a:before {
            background-color: rgba(255,255,255,0.2);
          }
          
          [data-stored-style-aspect="active_link_only"][data-navigation-width="wide"] .wpb_tabs_nav {
            width: 33%;
          }
          [data-stored-style-aspect="active_link_only"][data-navigation-width="wide"] .tabbed >div {
            width: 67%;
          }';  

        }



        $vs_sticky_aspect = (isset($atts['vs_sticky_aspect']) && 'content' === $atts['vs_sticky_aspect']) ? 'content' : 'default';
        $vs_nav_func      = (isset($atts['vs_navigation_func'])) ? $atts['vs_navigation_func'] : 'default';

        //Vertical Sticky Scrolling - default.
        if( isset($atts['style']) && 
        'vertical_scrolling' === $atts['style'] &&
        'content' !== $vs_sticky_aspect ) {


          // Tab spacing.
          if( isset($atts['vs_tab_spacing']) && in_array($atts['vs_tab_spacing'],array('5%','10%','15%','20%','25%','30%','35%','40%','45%','50%','10px','20px')) ) {

            $tab_spacing_unit = ( in_array($atts['vs_tab_spacing'],array('10px','20px')) ) ? 'px' : '%';

            self::$element_css[] = '.nectar-scrolling-tabs[data-tab-spacing="'.esc_attr($atts['vs_tab_spacing']).'"] .scrolling-tab-content > div {
              padding-top: '.(intval($atts['vs_tab_spacing'])/2).$tab_spacing_unit.';
              padding-bottom: '.(intval($atts['vs_tab_spacing'])/2).$tab_spacing_unit.';
            }
            
            .navigation_func_active_link_only[data-tab-spacing="'.esc_attr($atts['vs_tab_spacing']).'"] .scrolling-tab-content > div {
              padding: 0;
              margin-bottom: '. intval($atts['vs_tab_spacing']) .$tab_spacing_unit.';
            }';

          }

          // Font size.
          $vs_font_size = ( isset($atts['vs_font_size']) ) ? self::font_sizing_format($atts['vs_font_size']) : '';
          $vs_font_sub_desc_size = ( isset($atts['vs_sub_desc_font_size']) ) ? self::font_sizing_format($atts['vs_sub_desc_font_size']) : '';
          
          //// sub desc.
          if (!empty($vs_font_sub_desc_size)) {
           
            self::$element_css[] = '@media only screen and (min-width: 1000px) {
              .nectar-scrolling-tabs.sub_desc_font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_sub_desc_size)).' .wpb_tabs_nav .sub-desc {
                font-size: '.esc_attr($vs_font_sub_desc_size).';
                line-height: 1.5;
              } 
            }';
            
          }

          //// main tab heading.
          if( !empty($vs_font_size)) {
            self::$element_css[] = '@media only screen and (min-width: 1000px) {
              .nectar-scrolling-tabs.font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_size)).' .tab-nav-heading {
                font-size: '.esc_attr($vs_font_size).';
                line-height: 1;
              } 
            }';

            if( self::$using_front_end_editor ) {
              self::$element_css[] = '
              @media only screen and (min-width: 1000px) {
                [data-stored-style="vs"]:not([data-stored-style-aspect="content"]).font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_size)).' .wpb_tabs_nav li a {
                  font-size: '.esc_attr($vs_font_size).'!important;
                  line-height: 1;
                } 
              }';
            }

          }

        } // end font size for vs sticky defaults

        // Active link only visible.
        if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] &&
            'content' !== $vs_sticky_aspect && 
            'active_link_only' == $vs_nav_func ) {

              if( self::$using_front_end_editor ) {
                self::$element_css[] = '.navigation_func_active_link_only[data-stored-style-aspect="active_link_only"] .wpb_tabs_nav li a {
                  font-family: inherit;
                  font-weight: inherit!important;
                  letter-spacing: inherit;
                  text-transform: inherit;
                  overflow: visible;
                }';
              }

              self::$element_css[] = '

              .nectar-scrolling-tabs.navigation_func_active_link_only .scrolling-tab-nav > .line {
                display: none;
              }

              #ajax-content-wrap .navigation_func_active_link_only .scrolling-tab-nav ul li {
                padding-left: 0;
                padding-bottom: 0;
              } 

              .navigation_func_active_link_only .scrolling-tab-nav .link_text {
                padding-top: 0;
              }
              .navigation_func_active_link_only .scrolling-tab-nav .nectar-cta {
                margin-top: 40px;
              }

              .navigation_func_active_link_only .scrolling-tab-nav .menu-item .sub-desc {
                opacity: 1;
              }

              #ajax-content-wrap .navigation_func_active_link_only .scrolling-tab-content > div > div:last-child {
                margin-bottom: 0;
              }
   
              @media only screen and (min-width: 1000px) {

                #boxed #ajax-content-wrap {
                  overflow: visible!important;    
                  contain: paint;
                }

                html body,
                html body.compensate-for-scrollbar {
                  overflow: visible;
                }
                body .navigation_func_active_link_only .scrolling-tab-nav {
                  position: sticky;
                  top: var(--nectar-sticky-tabs-vert-y);
                }

                .nectar-scrolling-tabs .scrolling-tab-content {
                  padding-left: 10%;
                }

                .navigation_func_active_link_only .scrolling-tab-nav .menu-item .sub-desc {
                  padding-top: 1em;
                }
                
                .navigation_func_active_link_only .scrolling-tab-nav .scrolling-tab-nav-current-item {
                  display: none;
                }
                .navigation_func_active_link_only .scrolling-tab-nav ul li:not(.active) {
                  pointer-events: none;
                  position: absolute;
                  top: 0;
                  left: 0;
                  transition: none;
                }
                body .navigation_func_active_link_only .scrolling-tab-nav ul li {
                  opacity: 0;
                  transition: none;
                }

                @keyframes nectar_tab_out_up {
                  0% {
                    opacity: 1;
                    transform: translateY(0);
                  }
                  100% {
                    opacity: 0;
                    transform: translateY(-18px);
                  }
                }
                @keyframes nectar_tab_in_up {
                  0% {
                    opacity: 0;
                    transform: translateY(18px);
                  }
               
                  100% {
                    opacity: 1;
                    transform: translateY(0);
                  }
                }

                @keyframes nectar_tab_out_down {
                  0% {
                    opacity: 1;
                    transform: translateY(0);
                  }
                  100% {
                    opacity: 0;
                    transform: translateY(18px);
                  }
                }
                @keyframes nectar_tab_in_down {
                  0% {
                    opacity: 0;
                    transform: translateY(-18px);
                  }
            
                  100% {
                    opacity: 1;
                    transform: translateY(0);
                  }
                }

               
                .navigation_func_active_link_only .scrolling-tab-nav ul li.prev-active:not(.active) {
                  animation: nectar_tab_out_up 0.3s cubic-bezier(0.25,1,0.5,1) forwards;
                }
                .navigation_func_active_link_only .scrolling-tab-nav ul li.active {
                  animation: nectar_tab_in_up 0.6s cubic-bezier(0.25,1,0.5,1) forwards 0.1s;
                  opacity: 0;
                }
               
                .navigation_func_active_link_only.scrolling-up .scrolling-tab-nav ul li.prev-active:not(.active) {
                  animation: nectar_tab_out_down 0.3s cubic-bezier(0.25,1,0.5,1) forwards;
                }
                .navigation_func_active_link_only.scrolling-up .scrolling-tab-nav ul li.active {
                  animation: nectar_tab_in_down 0.6s cubic-bezier(0.25,1,0.5,1) forwards 0.1s;
                  opacity: 0;
                }

              
                .navigation_func_active_link_only .scrolling-tab-nav .tab-nav-heading {
                  cursor: text;
                }
                .navigation_func_active_link_only .scrolling-tab-nav .menu-item a {
                  cursor: text;
                  pointer-events: none;
                }

                .navigation_func_active_link_only .scrolling-tab-nav ul {
                  display: block!important;
                  position: relative;
                }
               
                .navigation_func_active_link_only .scrolling-tab-nav ul li {
                  padding-left: 0;
                } 

                .navigation_func_active_link_only .scrolling-tab-content > div {
                  transition: opacity 0.6s cubic-bezier(0.25,1,0.5,1);
                }

               
                .scrolling-tab-nav .scrolling-tab-nav-total {
                  margin-bottom: 25px;
                }
                .scrolling-tab-nav .scrolling-tab-nav-total > * {
                  vertical-align: middle;
                  line-height: 1.3;
                  display: inline-block;
                }
                .scrolling-tab-nav .scrolling-tab-nav-total .current span:not(:first-child) {
                  position: absolute;
                  top: 0;
                  left: 0;
                }
                .scrolling-tab-nav .scrolling-tab-nav-total .current {
                  overflow: hidden;
                  position: relative;
                  padding-right: 6px;
                }
                .scrolling-tab-nav .scrolling-tab-nav-total .current .inner {
                  display: block;
                  transition: transform 0.7s cubic-bezier(0.25,1,0.5,1);
                }

                .scrolling-tab-nav .scrolling-tab-nav-total .sep {
                  padding: 0 6px 0 3px;
                }
              }
                
          @media only screen and (max-width: 999px) {

            .nectar-scrolling-tabs.navigation_func_active_link_only:not(.initalized) .scrolling-tab-content > div:first-child {
              position: relative;
              pointer-events: all;
              opacity: 1;
            }

            .nectar-scrolling-tabs.navigation_func_active_link_only .scrolling-tab-content > div:not(.active) {
              position: absolute;
              pointer-events: none;
              opacity: 0;
              top: 0;
            }

            .navigation_func_active_link_only .scrolling-tab-content > div {
              padding: 0!important;
              margin: 0!important;
            }

            .navigation_func_active_link_only .scrolling-tab-nav {
              width: 100%!important;
            }

            .navigation_func_active_link_only .scrolling-tab-nav { 
              margin-bottom: 25px;
            }

            .navigation_func_active_link_only .scrolling-tab-nav a,
            .nectar-scrolling-tabs.navigation_func_active_link_only .scrolling-tab-nav .menu-item.has-icon {
              display: block;
            }
            
            .nectar-scrolling-tabs.navigation_func_active_link_only .scrolling-tab-nav i, 
            .nectar-scrolling-tabs.navigation_func_active_link_only .scrolling-tab-nav .svg-icon-link {
              margin-right: 0;
            }

            .scrolling-tab-nav .scrolling-tab-nav-total {
              display: none;
            }
            .navigation_func_active_link_only .scrolling-tab-nav ul {
              display: none;
              margin-top: 25px;
              padding-bottom: 10px;
              text-align: center;
            }
            .navigation_func_active_link_only .scrolling-tab-nav ul .tab-nav-heading {
              font-family: inherit;
              font-weight: inherit;
              text-transform: inherit;
              font-size: 22px;
              line-height: 1.3;
            }

            .nectar-scrolling-tabs.navigation_func_active_link_only .scrolling-tab-nav ul[data-spacing] li {
              padding-bottom: 0;
              opacity: 1;
            }
            .navigation_func_active_link_only .scrolling-tab-nav .active .tab-nav-heading {
              text-decoration: underline;
            }

            #ajax-content-wrap .navigation_func_active_link_only .scrolling-tab-nav ul .menu-content > a {
              font-size: 14px;
              max-width: 400px;
              line-height: 1.5;
              margin: 0 auto 20px auto;
              display: block;
            }
            .navigation_func_active_link_only .scrolling-tab-nav .scrolling-tab-nav-current-item a,
            .navigation_func_active_link_only .scrolling-tab-nav a {
              color: inherit;
              pointer-events: none;
            }
            .navigation_func_active_link_only .scrolling-tab-nav li {
              cursor: pointer;
            }
            .navigation_func_active_link_only .scrolling-tab-nav .scrolling-tab-nav-current-item {
              cursor: pointer;
              line-height: 1;
              display: flex;
              justify-content: center;
              align-items: center;
            }
    
            .navigation_func_active_link_only .scrolling-tab-nav .scrolling-tab-nav-current-item .tab-nav-heading {
              margin-bottom: 0;
            }

            .navigation_func_active_link_only .scrolling-tab-nav-current-item:after {
              content: "\e60a";
              font-family: "icomoon"!important;
              speak: none;
              margin-left: 10px;
              font-style: normal;
              font-weight: normal;
              font-variant: normal;
              text-transform: none;
              line-height: 1;
              display: inline;
              font-size: 20px;
              transition: transform .45s cubic-bezier(0.25,1,0.33,1);
              -webkit-font-smoothing: antialiased;
            }

            .navigation_func_active_link_only .scrolling-tab-nav-current-item.open:after {
              transform: rotate(180deg);
            }

            .navigation_func_active_link_only .scrolling-tab-content {
              position: relative!important;
              top: 0!important;
            }

            .navigation_func_active_link_only .scrolling-tab-nav-current-item + .wpb_tabs_nav + .nectar-cta {
              display: none;
              text-align: center;
              padding-bottom: 10px;
              padding-top: 10px;
            }

            .navigation_func_active_link_only .scrolling-tab-nav-current-item.open + .wpb_tabs_nav + .nectar-cta {
              display: block;
            }

          }
          
          @media only screen and (max-width: 690px) {
            .navigation_func_active_link_only .scrolling-tab-nav ul .tab-nav-heading {
              font-size: 20px;
            }
            #ajax-content-wrap .navigation_func_active_link_only .scrolling-tab-nav ul .menu-content > a {
              max-width: 300px; 
            }
          }';
        } 

        // All links visible.
        else if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] && 
                 'content' !== $vs_sticky_aspect &&
                 'active_link_only' !== $vs_nav_func) {
                  self::$element_css[] = '
                  .nectar-scrolling-tabs .scrolling-tab-nav .line {
                    max-height: 1px;
                    height: 100%;
                    background-color: #000;
                    -webkit-transition: transform 0.5s cubic-bezier(0,0,.34,.96), max-height 0.5s cubic-bezier(0,0,.34,.96);
                    transition: transform 0.5s cubic-bezier(0,0,.34,.96), max-height 0.5s cubic-bezier(0,0,.34,.96);
                    transform: translate3d(0px, 0px, 0px);
                  }
                  
              .nectar-scrolling-tabs:not(.navigation_func_active_link_only) .scrolling-tab-nav .menu-item:hover {
                opacity: 1;
              }
              .nectar-scrolling-tabs .scrolling-tab-nav .n-sticky > .line {
                width: 3px;
                left: 2px;
              }

              .nectar-scrolling-tabs .scrolling-tab-nav .link_text {
                padding-top: 0;
              }
              .nectar-scrolling-tabs .scrolling-tab-nav .nectar-cta {
                margin-top: 35px;
              }

              @media only screen and (min-width: 1000px ) {
                .nectar-scrolling-tabs .scrolling-tab-nav .nectar-cta {
                  padding-left: 50px;
                }
              }
              
              @media only screen and (max-width: 999px ) {
                .nectar-scrolling-tabs:not(.navigation_func_active_link_only) .scrolling-tab-nav {
                  display: none;
                }
              }';
        }

        //Vertical Sticky Scrolling - content.
        if( isset($atts['style']) && 'vertical_scrolling' === $atts['style'] &&
            'content' === $vs_sticky_aspect ) {
            
          $vs_content_animation = ( isset($atts['vs_content_animation']) ) ? $atts['vs_content_animation'] : 'fade';
          $vs_link_animation = ( isset($atts['vs_link_animation']) ) ? $atts['vs_link_animation'] : 'opacity';
          $vs_font_size = ( isset($atts['vs_font_size']) ) ? self::font_sizing_format($atts['vs_font_size']) : '';


          // Core styles.
          if( self::$using_front_end_editor ) {
            self::$element_css[] = '
            [data-stored-style-aspect="content"] > .tabbed > ul {
              float: left;
            }
            [data-stored-style-aspect="content"] > .tabbed > div {
              width: auto;
              float: right;
              margin: 0;
              padding: 0;
            }
            #ajax-content-wrap [data-stored-style-aspect="content"] > .tabbed > ul a {
              font-family: inherit!important;
              text-transform: inherit!important;
              letter-spacing: inherit!important;
              font-weight: inherit!important;
            }
            [data-stored-style-aspect="content"][data-tab-align="right"] > .tabbed > ul {
              float: right;
            }
            [data-stored-style-aspect="content"][data-tab-align="right"] > .tabbed > div {
              float: left;
            }
            [data-stored-style-aspect="content"][data-tab-align="right"] > .tabbed > ul {
              margin: 0 0 0 4%;
            }
            ';
          }

          self::$element_css[] = '

          .nectar-sticky-tabs .scrolling-tab-nav ul {
            margin: 0;
          }

          @media only screen and (min-width: 1000px) {

            #boxed #ajax-content-wrap {
              overflow: visible!important;    
              contain: paint;
            }

            html body,
            html body.compensate-for-scrollbar {
              overflow: visible;
            }

            .nectar-sticky-tabs  .wpb_tabs_nav li {
              margin-bottom: 0.35em;
            }
            
            .nectar-sticky-tabs {
              display: flex;
              flex-direction: row;
              flex-wrap: nowrap;
              align-items: flex-start;
            }

            .nectar-sticky-tabs .scrolling-tab-nav ul {
              display: block!important;
            } 

            .nectar-sticky-tabs .tab-text-content {
              margin-bottom: 20px;
              max-width: 400px;
            }

            .nectar-sticky-tabs .scrolling-tab-nav .nectar-cta {
              margin-top: 45px;
            }
            .nectar-sticky-tabs .scrolling-tab-nav .nectar-cta {
              display: inline-block;
            }
            .nectar-sticky-tabs .scrolling-tab-nav .nectar-cta .link_wrap {
              display: block;
            }

            .nectar-sticky-tabs .scrolling-tab-nav-current-item {
              display: none;
            }

            .nectar-sticky-tabs .wpb_tabs_nav li .sub-desc {
              margin-top: 0.5em;
              padding-right: 20%;
            }

         
            

            .nectar-sticky-tabs .scrolling-tab-nav {
              margin: 0 4% 0 0;
              min-width: 125px;
            }
            .nectar-sticky-tabs .scrolling-tab-content {
              margin: 0 0 0 4%;
              position: sticky;
              top: var(--nectar-sticky-top-distance);
            }
            .nectar-sticky-tabs[data-tab-align="right"] {
              flex-direction: row-reverse;
            }
            .nectar-sticky-tabs[data-tab-align="right"] .scrolling-tab-nav {
              margin: 0 0 0 4%;
            }
            .nectar-sticky-tabs[data-tab-align="right"] .scrolling-tab-content {
              margin: 0 4% 0 0;
              position: sticky;
            }
            
            .nectar-sticky-tabs .scrolling-tab-nav  .tab-nav-heading {
              position: relative;
              margin-bottom: 0;
            }
            .nectar-sticky-tabs .tab-nav-heading:before {
              border-radius: 100px;
              content: "";
              height: 8px;
              width: 8px;
              position: absolute;
              left: -25px;
              top: 50%;
              background-color: #000;
              display: inline-block;
              transition: transform 0.3s ease;
              transform: scale(0) translateY(-50%);
            }

            .nectar-sticky-tabs .active-tab .tab-nav-heading:before {
              transform: scale(1) translateY(-50%);
            }
            

          }
          

          .nectar-sticky-tabs .wpb_tabs_nav li {
            line-height: 1;
            list-style: none;
          }
          .nectar-sticky-tabs .wpb_tabs_nav li:last-child {
            margin-bottom: 0;
          }
    
          .nectar-sticky-tabs .scrolling-tab-content {
            flex: 1;
            overflow: hidden;
          }

          .nectar-sticky-tabs .wpb_tabs_nav li a {
            color: inherit;
            display: block;
            background-color: transparent;
            white-space: normal;
            padding: 0;
            border: none;
          }
        
          .nectar-sticky-tabs .wpb_tabs_nav li a span {
            line-height: 1.1;
          }
        
          .nectar-sticky-tabs .wpb_tabs_nav li a:hover,
          .nectar-sticky-tabs .wpb_tabs_nav li a.active-tab {
            background-color: transparent;
          }

          .nectar-sticky-tabs .scrolling-tab-content .wpb_tab > .inner-wrap > .wpb_row:last-child {
            margin-bottom: 0;
          }

          .nectar-sticky-tabs .scrolling-tab-content .wpb_tab {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            overflow: hidden;
            width: 100%;
            opacity: 0;
          }

          .nectar-sticky-tabs .scrolling-tab-content .wpb_tab > .inner-wrap {
            overflow: hidden;
          }


          .nectar-sticky-tabs .scrolling-tab-content .wpb_tab > .inner-wrap > .inner > .img-with-aniamtion-wrap:last-child .hover-wrap,
          .nectar-sticky-tabs .scrolling-tab-content .wpb_tab > .inner-wrap > .inner > .wpb_row:last-child {
            margin-bottom: 0;
          }
        
          .nectar-sticky-tabs:not(.loaded) .scrolling-tab-content > div:not(.n-sticky):first-child,
          .nectar-sticky-tabs:not(.loaded) .scrolling-tab-content .n-sticky > div:first-child {
            position: relative;
            pointer-events: all;
          }

          .nectar-sticky-tabs .scrolling-tab-content .wpb_tab.active-tab {
            position: relative;
            pointer-events: all;
            z-index: 100;
            opacity: 1;
          }

        

          @media only screen and (max-width: 999px) {
            .nectar-sticky-tabs .scrolling-tab-nav { 
              margin-bottom: 25px;
            }
            .nectar-sticky-tabs .scrolling-tab-nav ul {
              display: none;
              margin-top: 25px;
              text-align: center;
            }
            .nectar-sticky-tabs .scrolling-tab-nav ul .tab-nav-heading {
              font-family: inherit;
              font-weight: inherit;
              text-transform: inherit;
              font-size: 18px;
              line-height: 1.3;
            }
            #ajax-content-wrap .nectar-sticky-tabs .scrolling-tab-nav ul .menu-content > a {
              font-size: 14px;
              max-width: 400px;
              line-height: 1.5;
              margin: 0 auto 20px auto;
              display: block;
            }
            .nectar-sticky-tabs .scrolling-tab-nav .scrolling-tab-nav-current-item a {
              color: inherit;
            }
            .nectar-sticky-tabs .scrolling-tab-nav .scrolling-tab-nav-current-item {
              cursor: pointer;
              line-height: 1;
              display: flex;
              justify-content: center;
              align-items: center;
            }
            .tab-text-content {
              padding: 0 10%;
              text-align: center;
            }
            .nectar-sticky-tabs .scrolling-tab-nav .scrolling-tab-nav-current-item .tab-nav-heading {
              margin-bottom: 0;
            }
            .nectar-sticky-tabs .scrolling-tab-nav-current-item:after {
              content: "\e60a";
              font-family: "icomoon"!important;
              speak: none;
              margin-left: 10px;
              font-style: normal;
              font-weight: normal;
              font-variant: normal;
              text-transform: none;
              line-height: 1;
              display: inline;
              font-size: 20px;
              transition: transform .45s cubic-bezier(0.25,1,0.33,1);
              -webkit-font-smoothing: antialiased;
            }
            .nectar-sticky-tabs .scrolling-tab-nav-current-item.open:after {
              transform: rotate(180deg);
            }
            .nectar-sticky-tabs .scrolling-tab-content {
              position: relative!important;
              top: 0!important;
            }
            .nectar-sticky-tabs .nectar-cta {
              display: none;
              text-align: center;
              padding-bottom: 10px;
              padding-top: 10px;
            }
            .nectar-sticky-tabs .scrolling-tab-nav-current-item.open + .wpb_tabs_nav + .nectar-cta {
              display: block;
            }

          }

          
 
          ';
          
          // Tab spacing.
          if( isset($atts['vs_tab_link_spacing']) && strlen($atts['vs_tab_link_spacing']) > 0 ) {
            self::$element_css[] = ' @media only screen and (min-width: 1000px) {
              .nectar-sticky-tabs.tab_link_spacing_'. self::percent_unit_type_class($atts['vs_tab_link_spacing']) .' .wpb_tabs_nav li:not(:last-child) {
                margin-bottom: '. self::percent_unit_type($atts['vs_tab_link_spacing']) .';
              }
              .nectar-sticky-tabs.tab_link_spacing_'. self::percent_unit_type_class($atts['vs_tab_link_spacing']) .' .wpb_tabs_nav + .nectar-cta {
                margin-top: '. self::percent_unit_type($atts['vs_tab_link_spacing']) .';
              }
            }
            ';
          }
     
          // Tab content animations.
          if( 'slide_reveal_zoom' == $vs_content_animation ) {
            self::$element_css[] = '@keyframes nectar_tab_in_3 {
              0% {
                transform: scale(1.15);
              }
              100% {
                transform: scale(1);
              }
            }
            .content_animation_slide_reveal_zoom .scrolling-tab-content .wpb_tab.active-tab > .inner-wrap > .inner {
              animation: nectar_tab_in_3 0.8s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
            }';
          } 

          if ( 'slide_reveal' == $vs_content_animation || 'slide_reveal_zoom' == $vs_content_animation ) {
            self::$element_css[] = '
            @keyframes nectar_tab_in_1 {
              0% {
                transform: translateX(100%) translateZ(0);
              }
              100% {
                transform: translateX(0) translateZ(0);
              }
            }
  
            @keyframes nectar_tab_in_2 {
              0% {
                transform: translateX(-100%) translateZ(0);
              }
              100% {
                transform: translateX(0) translateZ(0);
              }
            }
  
  
            @keyframes nectar_tab_out_1 {
              0% {
                transform: translateX(0) translateZ(0);
              }
              85% {
                opacity: 1;
              }
              100% {
                opacity: 0;
                transform: translateX(100%) translateZ(0);
              }
            }
  
            @keyframes nectar_tab_out_2 {
              0% {
                transform: translateX(0) translateZ(0);
              }
              100% {
                transform: translateX(-100%) translateZ(0);
              }
            }

            div[class*="content_animation_slide_reveal"].loaded .scrolling-tab-content .wpb_tab.previously-active-tab {
              opacity: 1;
            }

            div[class*="content_animation_slide_reveal"] .scrolling-tab-content .wpb_tab.active-tab {
              animation: nectar_tab_in_2 0.8s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
            }
  
            div[class*="content_animation_slide_reveal"] .scrolling-tab-content .wpb_tab.active-tab > .inner-wrap {
              animation: nectar_tab_in_1 0.8s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
            }

            div[class*="content_animation_slide_reveal"].loaded .scrolling-tab-content .wpb_tab.previously-active-tab {
              animation: nectar_tab_out_1 0.8s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
              opacity: 1;
            }
  
            div[class*="content_animation_slide_reveal"].loaded .scrolling-tab-content .wpb_tab.previously-active-tab > .inner-wrap {
              animation: nectar_tab_out_2 0.8s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
            }

            
            ';
          } 

          else if ( 'fade' == $vs_content_animation ) {
            self::$element_css[] = ' @keyframes nectar_tab_in_fade {
              0% {
                transform: scale(0.93);
                opacity: 0;
              }
              100% {
                transform: scale(1);
                opacity: 1;
              }
            }
  
            @keyframes nectar_tab_out_fade {
              0% {
                transform: scale(1);
                opacity: 1;
              }
              100% {
                opacity: 0;
                transform: scale(0.93);
              }
            }


            .content_animation_fade.loaded .scrolling-tab-content .wpb_tab.previously-active-tab {
              opacity: 1;
            }

      
  
            .content_animation_fade .scrolling-tab-content .wpb_tab.active-tab > .inner-wrap {
              animation: nectar_tab_in_fade 0.45s ease forwards 0.08s;
              opacity: 0;
            }

            .content_animation_fade.loaded .scrolling-tab-content .wpb_tab.previously-active-tab {
              animation: nectar_tab_out_fade 0.25s ease forwards;
              opacity: 1;
            }';
          }



          // Tab link animations.
          if( 'opacity' === $vs_link_animation ) {
            self::$element_css[] = '
              .nectar-sticky-tabs .wpb_tabs_nav li {
                transition: opacity 0.25s ease;
              }

              .nectar-sticky-tabs .wpb_tabs_nav li:hover {
                opacity: 0.5;
              }
            ';
          }

          else if( 'underline' === $vs_link_animation ) {
            self::$element_css[] = '
            @media only screen and (min-width: 1000px) {
              .nectar-sticky-tabs.link_animation_underline .wpb_tabs_nav li:not(:last-child) {
               margin-bottom: 0.4em;
             }
             .nectar-sticky-tabs.link_animation_underline .wpb_tabs_nav li a span {
              line-height: 1;
              }
            }
            .scrolling-tab-nav-current-item .nectar-link-underline a span {
              background: none;
            }
            ';

            if( isset($atts['vs_link_underline_distance']) && 'closer' === $atts['vs_link_underline_distance'] ) {
              self::$element_css[] = '.link_animation_underline.underline_distance_closer .wpb_tabs_nav li a span {
                background-position-y: 91%;
              }';
            } 
            else if( isset($atts['vs_link_underline_distance']) && 'closest' === $atts['vs_link_underline_distance'] ) {
              self::$element_css[] = '.link_animation_underline.underline_distance_closest .wpb_tabs_nav li a span {
                background-position-y: 84%;
              }';
            }

          }

          else if( 'outline_fill' === $vs_link_animation ) {
            self::$element_css[] = '
            @media only screen and (min-width: 1000px) {
              .link_animation_outline_fill .wpb_tabs_nav li .tab-nav-heading a {
                transition: none;
                position: relative;
                display: block;
              } 
              .link_animation_outline_fill .wpb_tabs_nav li.active-tab .tab-nav-heading,
              .link_animation_outline_fill .wpb_tabs_nav li:hover .tab-nav-heading {
                color: inherit;
              }
              .link_animation_outline_fill .wpb_tabs_nav li .tab-nav-heading {
                -webkit-text-stroke: 1px;
                -webkit-text-stroke-color: black;
                -webkit-text-stroke-width: 0.01em;
                color: transparent;
                transition: color 0.25s ease;
              }
              .light .link_animation_outline_fill .wpb_tabs_nav li .tab-nav-heading {
                -webkit-text-stroke-color: #fff;
              }
              
             
          }';
          }
          
          //// sub desc.
          $vs_font_sub_desc_size = ( isset($atts['vs_sub_desc_font_size']) ) ? self::font_sizing_format($atts['vs_sub_desc_font_size']) : '';
        
          if (!empty($vs_font_sub_desc_size)) {
           
            self::$element_css[] = '@media only screen and (min-width: 1000px) {
              #ajax-content-wrap .nectar-sticky-tabs.sub_desc_font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_sub_desc_size)).' .wpb_tabs_nav .sub-desc {
                font-size: '.esc_attr($vs_font_sub_desc_size).';
                line-height: 1.5;
              } 
            }';
            
          }

          // main heading font size.
          if( !empty($vs_font_size)) {
            self::$element_css[] = '@media only screen and (min-width: 1000px) {
              .nectar-sticky-tabs.font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_size)).' .wpb_tabs_nav li {
                font-size: '.esc_attr($vs_font_size).';
                line-height: 0;
              } 
              .nectar-sticky-tabs.font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_size)).' .wpb_tabs_nav .tab-nav-heading {
                font-size: inherit;
                line-height: 1em;
              } 
            }';

            if( self::$using_front_end_editor ) {
              self::$element_css[] = '
              @media only screen and (min-width: 1000px) {
                [data-stored-style-aspect="content"].font_size_'.esc_attr(self::decimal_unit_type_class($vs_font_size)).' .wpb_tabs_nav li a {
                  font-size: '.esc_attr($vs_font_size).'!important;
                  line-height: 1;
                } 
              }';
            }

          }

          if( isset($atts['vs_navigation_width_2']) && in_array($atts['vs_navigation_width_2'], array('20%','25%','30%','35%','40%','45%','50%','55%','60%','65%')) ) {

            self::$element_css[] = '
            @media only screen and (min-width: 1000px) {
              .nectar-sticky-tabs[data-navigation-width="'.$atts['vs_navigation_width_2'].'"] .scrolling-tab-nav {
                width: '.$atts['vs_navigation_width_2'].';
              }
            }';

            if( self::$using_front_end_editor ) {
              self::$element_css[] = '
              @media only screen and (min-width: 1000px) {
                [data-stored-style-aspect="content"][data-navigation-width="'.$atts['vs_navigation_width_2'].'"] .ui-tabs-nav {
                  width: '.$atts['vs_navigation_width_2'].';
                }
                [data-stored-style-aspect="content"][data-navigation-width="'.$atts['vs_navigation_width_2'].'"] .tabbed > div {
                  width: '. (95 - intval($atts['vs_navigation_width_2'])).'%;
                }
              }';
            }
          }

        } // end hover based.

      }

      // Image With Hotspots.
      else if( false !== strpos($shortcode[0],'[nectar_image_with_hotspots ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['color_1']) && !empty($atts['color_1']) ) {

          $color = self::locate_color(strtolower($atts['color_1']));
          if( $color ) {

            self::$element_css[] = '.nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nectar_hotspot_wrap .nttip .tipclose {
              border-color: '.esc_attr($color['color']).';
            }
            .nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nectar_hotspot,
            .nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nttip .tipclose span:before,
            .nectar_image_with_hotspots[data-color="'.esc_attr($color['name']).'"] .nttip .tipclose span:after {
              background-color: '.esc_attr($color['color']).';
            }';

          }// color in use.

        }

      }


      // VC Gallery.
      else if ( false !== strpos($shortcode[0],'[vc_gallery ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        if ( isset($atts['type']) && in_array($atts['type'],array('flickity_style','flickity_static_height_style')) ) {

            // Spacing when using parallax option.
            // Changing marign from both to one side to prevent initial jump calc.
            if ( isset( $atts['flickity_image_parallax'] ) && 'true' === $atts['flickity_image_parallax'] &&
                 isset( $atts['flickity_spacing'] ) && 
                 in_array($atts['flickity_spacing'], array('5px','10px','15px','20px','30px')) ) {
                 self::$element_css[] = '.nectar-flickity[data-spacing="'.esc_attr($atts['flickity_spacing']).'"]:not(.masonry) .flickity-slider .cell {
                  margin-right: '.esc_attr(intval($atts['flickity_spacing'])*2).'px;
                  margin-left: 0;
                }';
            }


            // Drag indicator.
            if (isset($atts['flickity_touch_total_style']) && 'solid_bg' === $atts['flickity_touch_total_style']) {
                self::$element_css[] = '
              .nectar-drag-indicator[data-type="solid"] {
                mix-blend-mode: normal;
                left: -47px;
                top: -47px;
                width: 94px;
                height: 94px;
              }
              .nectar-drag-indicator[data-type="solid"] .color-circle {
                background-color: #000;
                width: 100%;
                height: 100%;
                display: block;
                position: absolute;
                left: -2px;
                top: -2px;
                transform: scale(0.2);
                transition: transform 0.45s ease, opacity 0.3s ease;
                opacity: 0;
                border-radius: 50%;
              }

              .nectar-drag-indicator[data-type="solid"].visible .color-circle{
                transform: scale(1);
                opacity: 1;
              }
              .nectar-drag-indicator[data-type="solid"] i {
                font-size: 21px;
                top: -13px;
              }
              
              .nectar-drag-indicator[data-type="solid"]:before {
                display: none;
              }
              .nectar-drag-indicator[data-type="solid"].visible i {
                transition: transform 0.45s ease, opacity 0.3s ease, color 0.3s ease;
              }
              .nectar-drag-indicator[data-type="solid"] i.fa-angle-left {
                left: 23px;
              }
              .nectar-drag-indicator[data-type="solid"] i.fa-angle-right {
                right: 27px;
              }
              .nectar-drag-indicator.visible.pointer-down[data-type="solid"] .color-circle {
                transform: scale(0.15);
              }
              .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i {
                color: inherit!important;
              }
        
              .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i.fa-angle-left {
                transform: translateX(-10px);
              }
              .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i.fa-angle-right {
                transform: translateX(10px);
              }
            ';
                }
            }

      if( isset( $atts['type'] ) && 'flickity_style' === $atts['type'] ) {

          if( isset( $atts['flickity_image_stagger_columns'] ) && 'true' === $atts['flickity_image_stagger_columns'] ) {

            $desktop_cols = ( isset( $atts['flickity_desktop_columns'] ) && !empty( $atts['flickity_desktop_columns'] ) ) ? esc_attr($atts['flickity_desktop_columns']) : '3';
            $tablet_cols = ( isset( $atts['flickity_tablet_columns'] ) && !empty( $atts['flickity_tablet_columns'] ) ) ? esc_attr($atts['flickity_tablet_columns']) : '2';
            $phone_cols = ( isset( $atts['flickity_phone_columns'] ) && !empty( $atts['flickity_phone_columns'] ) ) ? esc_attr($atts['flickity_phone_columns']) : '';

            //// desktop.
            if( in_array( $desktop_cols , array('2','3','4') ) ) {
              self::$element_css[] = '@media only screen and (min-width:1000px) {
                .nectar-flickity[data-stagger="true"][data-desktop-columns="'.$desktop_cols.'"] .flickity-slider > div:nth-child(2n + 2) {
                  padding-top: 4%;
                }
              }';

            } else if( in_array( $desktop_cols , array('5','6') ) ) {
              self::$element_css[] = '@media only screen and (min-width:1000px) {
                .nectar-flickity[data-stagger="true"][data-desktop-columns="'.$desktop_cols.'"] .flickity-slider > div:nth-child(3n + 2) {
                  padding-top: 2%;
                }
                .nectar-flickity[data-stagger="true"][data-desktop-columns="'.$desktop_cols.'"] .flickity-slider > div:nth-child(3n + 3) {
                  padding-top: 4%;
                }
              }';
            }

            //// tablet.
            if( in_array( $tablet_cols , array('2','3','4') ) ) {
              self::$element_css[] = '@media only screen and (min-width:690px) and (max-width:1000px) {
                .nectar-flickity[data-stagger="true"][data-tablet-columns="'.$tablet_cols.'"] .flickity-slider > div:nth-child(2n + 2) {
                  padding-top: 2.5%;
                }
              }';

            } else if( in_array( $tablet_cols , array('5','6') ) ) {
              self::$element_css[] = '@media only screen and (min-width:690px) and (max-width:1000px) {
                .nectar-flickity[data-stagger="true"][data-tablet-columns="'.$tablet_cols.'"] .flickity-slider > div:nth-child(3n + 2) {
                  padding-top: 2.5%;
                }
                .nectar-flickity[data-stagger="true"][data-tablet-columns="'.$tablet_cols.'"] .flickity-slider > div:nth-child(3n + 3) {
                  padding-top: 5%;
                }
              }';
            }


            //// phone.
            if( in_array( $phone_cols , array('2','3','4') ) ) {
              self::$element_css[] = '@media only screen and (max-width: 690px) {
                .nectar-flickity[data-stagger="true"][data-phone-columns="'.$phone_cols.'"] .flickity-slider > div:nth-child(2n + 2) {
                  padding-top: 2.5%;
                }
              }';

            } else if( in_array( $phone_cols , array('5') ) ) {
              self::$element_css[] = '@media only screen and (max-width: 690px) {
                .nectar-flickity[data-stagger="true"][data-phone-columns="'.$phone_cols.'"] .flickity-slider > div:nth-child(3n + 2) {
                  padding-top: 2.5%;
                }
                .nectar-flickity[data-stagger="true"][data-phone-columns="'.$phone_cols.'"] .flickity-slider > div:nth-child(3n + 3) {
                  padding-top: 5%;
                }
              }';
            }

          }

        }

      }
      
      // Carousel.
      else if ( false !== strpos($shortcode[0],'[carousel ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Flickity.
        // drag indicator.
        if( isset($atts['script']) && 'flickity' === $atts['script'] && 
            isset($atts['flickity_touch_total_style']) && 'tooltip_text' === $atts['flickity_touch_total_style'] ) {
              self::$element_css[] = self::get_drag_indicator_tooltip_css();
        }

          if( ( isset($atts['script']) && 'flickity' === $atts['script'] &&
             isset($atts['flickity_touch_total_style']) && 'solid_bg' === $atts['flickity_touch_total_style'] ) || 
             isset($atts['simple_slider_touch_indicator']) && 'true' === $atts['simple_slider_touch_indicator'] ) {
            self::$element_css[] = self::get_drag_indicator_solid_css();
          }
          
          if( isset($atts['script']) && 'flickity' === $atts['script'] ) {

            // pagination color.
            if( isset($atts['color']) && in_array( $atts['color'], array('accent-color','extra-color-1', 'extra-color-2', 'extra-color-3') ) ) {
              $pagination_color = self::locate_color(strtolower($atts['color']));
              if( $pagination_color ) {
                self::$element_css[] = '
                .nectar-flickity[data-controls="default"][data-control-color="'.esc_attr($atts['color']).'"] .flickity-page-dots .dot:before {
                  box-shadow: inset 0 0 0 5px '.$pagination_color['color'].';
                }
                .nectar-flickity[data-control-style="material_pagination"][data-control-color="'.esc_attr($atts['color']).'"] .flickity-page-dots .dot.is-selected:before {
                  box-shadow: inset 0 0 0 1px '.$pagination_color['color'].';
                }';
              }
            }
          
            // vertical alignment.
          if( isset($atts['flickity_column_vertical_alignment']) ) {
            
            if( 'bottom' === $atts['flickity_column_vertical_alignment'] ) {
              
              self::$element_css[] = '
              .nectar-flickity.nectar-carousel.vertical-alignment-bottom .flickity-slider .cell .inner-wrap-outer > .inner-wrap {
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
              }';

            } 

            else if( 'middle' === $atts['flickity_column_vertical_alignment'] ) {
              self::$element_css[] = '
              .nectar-flickity.nectar-carousel.vertical-alignment-middle .flickity-slider .cell .inner-wrap-outer > .inner-wrap {
                display: flex;
                flex-direction: column;
                justify-content: center;
              }';
            }
            
          }

          // Fixed text content fullwidth
          if( isset($atts['flickity_formatting']) && 'fixed_text_content_fullwidth' === $atts['flickity_formatting'] ) {
            self::$element_css[] = '
            .nectar-flickity.nectar-carousel[data-format="fixed_text_content_fullwidth"] .flickity-slider .cell .inner-wrap-outer {
              transform-style: preserve-3d;
            }
            
            .nectar-flickity.nectar-carousel[data-format="fixed_text_content_fullwidth"] .flickity-slider .cell {
              overflow: visible;
            }

            .nectar-flickity.nectar-carousel[data-format="fixed_text_content_fullwidth"] .flickity-slider .cell {
              margin-right: 30px;
              padding: 2px;
            }
            
            .nectar-flickity.nectar-carousel[data-format="fixed_text_content_fullwidth"] .flickity-slider {
              left: 1px!important;
            }
            
            
            .nectar-carousel-flickity-fixed-content .nectar-carousel-fixed-content p {
              opacity: 0.75;
            }

            .nectar-carousel-flickity-fixed-content .nectar-carousel-fixed-content {
              width: 33%;
              top: 50%;
              position: absolute;
              -webkit-transform: translateY(-50%);
              transform: translateY(-50%);
              padding-right: 65px;
              z-index: 50;
            }

            .nectar-carousel-flickity-fixed-content .nectar-flickity {
              margin-left: 34%;
              width: 100%;
            }

            @media only screen and (min-width: 1000px) {
              .nectar-carousel-flickity-fixed-content[data-alignment="right"] .nectar-flickity {
                margin-left: -34%;
              }
              .nectar-carousel-flickity-fixed-content[data-alignment="right"] .nectar-carousel-fixed-content {
                right: 0;
                left: auto;
                padding-right: 0;
                padding-left: 65px;
              }
              .nectar-carousel-flickity-fixed-content[data-alignment="right"] .nectar-carousel[data-format="fixed_text_content_fullwidth"] .flickity-page-dots {
                text-align: right;
              }
            }


            @media only screen and (min-width: 2000px) {
              .nectar-carousel-flickity-fixed-content[data-alignment="right"] .nectar-flickity {
                  margin-left: -50%;
              }
            }

            .nectar-carousel-flickity-fixed-content .nectar-flickity:not(.flickity-enabled) {
              opacity: 0;
            }


            @media only screen and (min-width: 2000px) {
              .nectar-carousel-flickity-fixed-content .nectar-flickity {
                width: 115%;
              }
            }

            .nectar-flickity.nectar-carousel[data-format="fixed_text_content_fullwidth"] .flickity-page-dots {
              text-align: left;
            }

            @media only screen and (min-width : 1px) and (max-width : 1000px) {
              body .nectar-carousel-flickity-fixed-content .nectar-carousel-fixed-content {
                position: relative;
                width: 100%;
                margin-right: 0;
                transform: none;
                top: 0;
              }
            
              body .nectar-carousel-flickity-fixed-content .nectar-flickity {
                margin-left: 0;
              }
            
            }
            
            @media only screen and (min-width:1300px) {

             
              .nectar-flickity.nectar-carousel[data-desktop-columns="6"][data-format="fixed_text_content_fullwidth"] .cell { width: 15%; }
              .nectar-flickity.nectar-carousel[data-desktop-columns="5"][data-format="fixed_text_content_fullwidth"] .cell { width: 15%; }
              .nectar-flickity.nectar-carousel[data-desktop-columns="4"][data-format="fixed_text_content_fullwidth"] .cell { width: 22.5%; }
              .nectar-flickity.nectar-carousel[data-desktop-columns="3"][data-format="fixed_text_content_fullwidth"] .cell { width: 31.9%; }
              .nectar-flickity.nectar-carousel[data-desktop-columns="2"][data-format="fixed_text_content_fullwidth"] .cell { width: 55%; }
              .nectar-flickity.nectar-carousel[data-desktop-columns="1"][data-format="fixed_text_content_fullwidth"] .cell { width: 85%; }
            
            }
            @media only screen and (min-width:1000px) and (max-width:1300px) {

              .nectar-flickity.nectar-carousel[data-small-desktop-columns="6"][data-format="fixed_text_content_fullwidth"] .cell { width: 15%; }
              .nectar-flickity.nectar-carousel[data-small-desktop-columns="5"][data-format="fixed_text_content_fullwidth"] .cell { width: 15%; }
              .nectar-flickity.nectar-carousel[data-small-desktop-columns="4"][data-format="fixed_text_content_fullwidth"] .cell { width: 22.5%; }
              .nectar-flickity.nectar-carousel[data-small-desktop-columns="3"][data-format="fixed_text_content_fullwidth"] .cell { width: 33%; }
              .nectar-flickity.nectar-carousel[data-small-desktop-columns="2"][data-format="fixed_text_content_fullwidth"] .cell { width: 55%; }
              .nectar-flickity.nectar-carousel[data-small-desktop-columns="1"][data-format="fixed_text_content_fullwidth"] .cell { width: 85%; }
            
            }
            @media only screen and (max-width:1000px) and (min-width:690px) {

              .nectar-flickity.nectar-carousel[data-tablet-columns="4"][data-format="fixed_text_content_fullwidth"] .cell { width: 22.5%; }
              .nectar-flickity.nectar-carousel[data-tablet-columns="3"][data-format="fixed_text_content_fullwidth"] .cell { width: 33%; }
              .nectar-flickity.nectar-carousel[data-tablet-columns="2"][data-format="fixed_text_content_fullwidth"] .cell { width: 55%; }
              .nectar-flickity.nectar-carousel[data-tablet-columns="1"][data-format="fixed_text_content_fullwidth"] .cell { width: 85%; }
            
            }
            ';
          }

          // Responsive columns.
          // Most are still contained in ext stylesheet.
          $flickity_desktop_cols = isset($atts['desktop_cols_flickity']) ? $atts['desktop_cols_flickity'] : '3';
          $flickity_sm_desktop_cols = isset($atts['desktop_small_cols_flickity']) ? $atts['desktop_small_cols_flickity'] : '3';
          $flickity_tablet_cols = isset($atts['tablet_cols_flickity']) ? $atts['tablet_cols_flickity'] : '2';
          $flickity_phone_cols = isset($atts['phone_cols_flickity']) ? $atts['phone_cols_flickity'] : '1';
          
    
          if( in_array( $flickity_desktop_cols, array('7','8', '9') ) ) {
            self::$element_css[] = '
            @media only screen and (min-width: 1300px) { 
              .nectar-flickity[data-desktop-columns="'.$flickity_desktop_cols.'"]:not(.masonry) .flickity-slider .cell {
                width: calc(100% / '.$flickity_desktop_cols.');
              }
            }';
          }
          if( in_array( $flickity_sm_desktop_cols, array('7','8',' 9') ) ) {
            self::$element_css[] = '
            @media only screen and (min-width: 1000px) and (max-width: 1300px) { 
              .nectar-flickity[data-small-desktop-columns="'.$flickity_sm_desktop_cols.'"]:not(.masonry) .flickity-slider .cell {
                width: calc(100% / '.$flickity_sm_desktop_cols.');
              }
            }';
          }
          if( in_array( $flickity_tablet_cols, array('4', '5', '6') ) ) {
            self::$element_css[] = '
            @media only screen and (min-width: 691px) and (max-width: 999px) { 
              .nectar-flickity[data-tablet-columns="'.$flickity_tablet_cols.'"]:not(.masonry) .flickity-slider .cell {
                width: calc(100% / '.$flickity_tablet_cols.');
              }
            }';

          }
          if( in_array( $flickity_phone_cols, array('2','3','4','5','6') ) ) {
            self::$element_css[] = '
            @media only screen and (max-width: 690px) { 
              .nectar-flickity[data-phone-columns="'.$flickity_phone_cols.'"]:not(.masonry) .flickity-slider .cell {
                width: calc(100% / '.$flickity_phone_cols.');
              }
            }';
          }

          // Top/bottom margin.
          if( isset($atts['flickity_element_spacing']) && 'default' !== $atts['flickity_element_spacing'] ) {
            self::$element_css[] = '.nectar-flickity.nectar-carousel.nectar-carousel:not(.masonry).tb-spacing-'.esc_attr($atts['flickity_element_spacing']).' .flickity-viewport {
              margin-top: '.esc_attr($atts['flickity_element_spacing']).';
              margin-bottom: '.esc_attr($atts['flickity_element_spacing']).';
            }';
            if( in_array( $atts['flickity_element_spacing'], array('0','10px','20px','30px','40px','50px') ) ) {
              self::$element_css[] = '@media only screen and (max-width: 999px) {
                .nectar-flickity.nectar-carousel:not(.masonry).tb-spacing-'.esc_attr($atts['flickity_element_spacing']).' .flickity-page-dots {
                  bottom: -50px;
                }
              }';
            }
          }

          // Custom padding.
          $custom_padding = (isset($atts['column_padding_custom']) && !empty($atts['column_padding_custom'])) ? $atts['column_padding_custom'] : false;

          if( isset($atts['column_padding']) && 'custom' === $atts['column_padding'] && $custom_padding ) {
            self::$element_css[] = '.nectar-flickity.nectar-carousel[data-format="default"].custom-column-padding-'. esc_attr(self::percent_unit_type_class($custom_padding)) . ' .flickity-slider .cell {
              padding: '.esc_attr(self::percent_unit_type($custom_padding)).';
            }';
          }

          // Spacing.
          if( isset($atts['flickity_spacing']) && in_array($atts['flickity_spacing'], array('5px','10px','15px','20px','30px')) ) {

            $item_spacing        = $atts['flickity_spacing'];

            $desktop_cols        = ( isset($atts['desktop_cols_flickity']) ) ? $atts['desktop_cols_flickity'] : '3';
            $desktop_cols_mod    = intval($desktop_cols) - 1;

            $sm_desktop_cols     = ( isset($atts['desktop_small_cols_flickity']) ) ? $atts['desktop_small_cols_flickity'] : '3';
            $sm_desktop_cols_mod = intval($sm_desktop_cols) - 1;

            $mobile_cols         = ( isset($atts['tablet_cols_flickity']) ) ? $atts['tablet_cols_flickity'] : '2';
            $mobile_cols_mod     = intval($mobile_cols) - 1;

            // Basic calc for space between = ((colNum - 1) x spacing) x 2

            // Desktop.
            if( in_array($desktop_cols, array('2','3','4','5','6')) ) {
              self::$element_css[] = '
              @media only screen and (min-width:1300px) {
                .nectar-flickity.nectar-carousel[data-desktop-columns="'.esc_attr($desktop_cols).'"][data-spacing="'.esc_attr($item_spacing).'"][data-format="default"] .cell {
                  width: calc((100% - '. ( intval($desktop_cols_mod) * intval($item_spacing) * 2 ) .'px) / '.esc_attr($desktop_cols).');
                }
              }';
            }

            // SM Desktop.
            if( in_array($sm_desktop_cols, array('2','3','4','5','6')) ) {
              self::$element_css[] = '
              @media only screen and (min-width:1000px) and (max-width:1299px) {
                .nectar-flickity.nectar-carousel[data-small-desktop-columns="'.esc_attr($sm_desktop_cols).'"][data-spacing="'.esc_attr($item_spacing).'"][data-format="default"] .cell {
                  width: calc((100% - '. ( intval($sm_desktop_cols_mod) * intval($item_spacing) * 2 ) .'px) / '.esc_attr($sm_desktop_cols).');
                }
              }';
            }

            // Mobile.
            if( in_array($mobile_cols, array('2','3')) ) {
              self::$element_css[] = '
              @media only screen and (max-width:999px) and (min-width:690px) {
                .nectar-flickity.nectar-carousel[data-tablet-columns="'.esc_attr($mobile_cols).'"][data-spacing="'.esc_attr($item_spacing).'"][data-format="default"] .cell {
                  width: calc((100% - '. ( intval($mobile_cols_mod) * intval($item_spacing) * 2 ) .'px) / '.esc_attr($mobile_cols).');
                }
              }';
            }

          }

        }

        // Simple Slider.
        if( isset($atts['script']) && 'simple_slider' === $atts['script']  ) {

          // Core
          self::$element_css[] = '
          .nectar-flickity.nectar-simple-slider {
            position: relative;
          }
          .nectar-flickity.nectar-simple-slider .flickity-slider .cell {
            width: 100%;
            padding: 0 8%;
            padding: 0 min(8%, 100px);
            position: relative;
            display: flex;
            align-items: center;
            height: 100%;
            margin-right: 0;
          }
          .nectar-flickity.nectar-simple-slider[data-parallax="true"] .flickity-slider .cell {
            width: 101%;
          }
          .nectar-flickity.nectar-simple-slider:not([data-arrows="true"]) .flickity-slider .cell {
            padding: 0 min(8%, 90px);
          }
          .nectar-simple-slider[data-arrows="true"]:not(.arrow-position-overlapping) .flickity-slider .cell {
            padding: 0 10%;
            padding: 0 max(10%, 100px);
          }
          @media only screen and (max-width: 1300px) and (min-width: 1000px){
            .nectar-simple-slider[data-arrows="true"]:not(.arrow-position-overlapping) .flickity-slider .cell {
              padding: 0 11%;
              padding: 0 max(10.5%, 105px);
            }
          }
          .nectar-flickity.nectar-simple-slider.sizing-aspect-ratio .flickity-viewport {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
          }';

          self::$element_css[] = '
          .nectar-flickity.nectar-simple-slider .flickity-slider .cell .inner > div:last-child {
            margin-bottom: 0;
          }
          .nectar-flickity.nectar-simple-slider .flickity-viewport {
            margin: 0;
          }
          .full-width-content .vc_col-sm-12 .nectar-flickity.nectar-simple-slider .flickity-viewport  {
            overflow: hidden;
          }
          .nectar-flickity.nectar-simple-slider .cell > .bg-layer-wrap .bg-layer {
            background-size: cover;
            background-position: center;
          }
          .nectar-simple-slider[data-parallax="true"] .cell > .bg-layer-wrap .bg-layer {
            will-change: transform;
          }
          .nectar-flickity.nectar-simple-slider[data-parallax="true"] .cell > .bg-layer-wrap {
            top: auto;
            bottom: 0;
          }
          
          .nectar-simple-slider .cell > .bg-layer-wrap,
          .nectar-simple-slider .cell > .bg-layer-wrap .bg-layer,
          .nectar-simple-slider .cell > .bg-layer-wrap .color-overlay {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
          }
          .nectar-simple-slider .cell > .bg-layer-wrap .color-overlay[data-strength="0.3"] { opacity: 0.3; }
          .nectar-simple-slider .cell > .bg-layer-wrap .color-overlay[data-strength="0.5"] { opacity: 0.5; }
          .nectar-simple-slider .cell > .bg-layer-wrap .color-overlay[data-strength="0.8"] { opacity: 0.8; }
          .nectar-simple-slider .cell > .bg-layer-wrap .color-overlay[data-strength="0.95"] { opacity: 0.95; }
          
          .nectar-simple-slider .cell > .inner {
            z-index: 10;
            position: relative;
            flex: 1;
          }
          .nectar-simple-slider .cell > .inner h1,
          .nectar-simple-slider .cell > .inner h2,
          .nectar-simple-slider .cell > .inner h3,
          .nectar-simple-slider .cell > .inner h4,
          .nectar-simple-slider .cell > .inner h5,
          .nectar-simple-slider .cell > .inner h6 {
            color: inherit;
          }
          
          .nectar-simple-slider .flickity-page-dots {
            position: absolute;
            bottom: 0;
            left: 0;
            padding: 0 3% 3%;
            padding: 0 max(3.45%, 27px) max(3.45%, 27px);
            width: 100%;
            pointer-events: none;
          }';

          self::$element_css[] = '
          .nectar-simple-slider[data-pagination-alignment="left"] .flickity-page-dots {
            text-align: left;
          }
          .nectar-simple-slider[data-pagination-alignment="right"] .flickity-page-dots {
            text-align: right;
          }
          .nectar-simple-slider .flickity-page-dots .dot {
            opacity: 1;
            width: 30px;
            height: 30px;
            padding: 5px;
            margin: 0;
            pointer-events: all;
            mix-blend-mode: difference;
          }

          .nectar-simple-slider .flickity-page-dots .dot:before {
            height: 6px;
            width: 6px;
            left: 12px;
            top: 12px;
            border-radius: 50px;
            background-color: #fff;
          }
          @media only screen and (min-width: 1000px) {
            .nectar-simple-slider .flickity-page-dots .dot {
              width: 36px;
              height: 36px;
              padding: 5px;
            }

            .nectar-simple-slider .flickity-page-dots .dot:before {
              height: 8px;
              width: 8px;
              left: 14px;
              top: 14px;
            }
            body .nectar-simple-slider .flickity-page-dots svg {
                width: 28px;
                height: 26px;
            }
          }';

          self::$element_css[] = '
          .nectar-simple-slider .flickity-page-dots svg circle.time {
            stroke-dashoffset: 180;
            stroke-dasharray: 179;
            stroke: #fff;
          }
          .nectar-simple-slider .flickity-page-dots svg {
              width: 22px;
              height: 21px;
              position: absolute;
              left: 5px;
              top: 4px;
              pointer-events: none;
          }
          .nectar-simple-slider .flickity-page-dots li:not(.is-selected) svg circle.time,
          .nectar-simple-slider .flickity-page-dots li.no-trans svg circle.time {
              transition-duration: 0s!important;
          }
          .nectar-simple-slider .flickity-page-dots li.no-trans svg circle.time {
            stroke-dashoffset: 180!important;
          }

          .nectar-simple-slider .flickity-page-dots .is-selected svg circle.time {
              stroke-dashoffset: 8;
              -webkit-transition: stroke-dashoffset .7s cubic-bezier(.25,.25,.1,1), stroke .2s ease;
              transition: stroke-dashoffset .7s cubic-bezier(.25,.25,.1,1), stroke .2s ease;
          }
          .nectar-simple-slider .flickity-page-dots .is-selected svg circle {
              -webkit-transition: stroke .3s ease;
              transition: stroke .3s ease;
              transform: rotate(-81deg);
              transform-origin: center;
          }

          .nectar-simple-slider .flickity-prev-next-button {
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            text-align: center;
            padding: 0;
          }
          .nectar-simple-slider.arrow-position-overlapping .flickity-prev-next-button {
            opacity: 1;
          }
          .nectar-simple-slider .flickity-prev-next-button:disabled {
              display: block;
              cursor: pointer;
          }
          .nectar-simple-slider.disabled-nav {
            cursor: pointer;
          }
          .nectar-simple-slider.disabled-nav > * {
            pointer-events: none;
          }
          .nectar-simple-slider .flickity-prev-next-button.previous {
            left: max(3.5%, 32px);
          }
          .nectar-simple-slider .flickity-prev-next-button.next {
            right: max(3.5%, 32px);
          }
          .nectar-simple-slider .flickity-prev-next-button:hover:before {
            transform: scale(1.15);
          }
          .nectar-simple-slider .flickity-prev-next-button:before {
            position: absolute;
            display: block;
            content: "";
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            border-radius: 100px;
            transition: transform 0.45s cubic-bezier(.15,.75,.5,1), opacity 0.45s cubic-bezier(.15,.75,.5,1);
          }';
          
          self::$element_css[] = '
          .nectar-simple-slider .flickity-prev-next-button svg {
              left: auto;
              top: 0;
              position: relative;
              width: 12px;
              height: 100%;
              transition: transform 0.45s cubic-bezier(.15,.75,.5,1);
          }

          .nectar-simple-slider .flickity-prev-next-button:after {
            height: 2px;
            width: 18px;
            background-color: #000;
            content: "";
            position: absolute;
            left: 7px;
            top: 50%;
            margin-top: -1px;
            display: block;
            transform-origin: right;
            transition: transform 0.45s cubic-bezier(.15,.75,.5,1);
          }

          .nectar-simple-slider .flickity-prev-next-button:after {
            transform: scaleX(0.9) translateX(4px);
          }
          .nectar-simple-slider .flickity-prev-next-button.previous:after {
            transform: scaleX(0.9) translateX(-4px);
          }
          .nectar-simple-slider .flickity-prev-next-button:hover:after {
            transform: scaleX(1.1) translateX(4px);
          }

          .nectar-simple-slider .flickity-prev-next-button.previous:hover:after {
            transform: scaleX(1.1) translateX(-4px);
          }
          .nectar-simple-slider .flickity-prev-next-button.previous:after {
            left: 17px;
            transform-origin: left;
          }

          .nectar-simple-slider .flickity-prev-next-button.previous svg {
            transform: translateX(-4px);
          }
          .nectar-simple-slider .flickity-prev-next-button.next svg {
            transform: translateX(4px);
          }

          .nectar-simple-slider .flickity-prev-next-button.previous:hover svg {
            transform: translateX(-6px);
          }
          .nectar-simple-slider .flickity-prev-next-button.next:hover svg {
            transform: translateX(6px);
          }

          .nectar-simple-slider .flickity-prev-next-button .arrow {
            fill: #000;
          }
          @media only screen and (max-width: 999px) {
            .nectar-simple-slider .flickity-prev-next-button.previous {
              left: 20px;
            }
            .nectar-simple-slider .flickity-prev-next-button.next {
              right: 20px;
            }
            .nectar-simple-slider .flickity-prev-next-button {
              transform: scale(0.75);
            }
            .nectar-flickity.nectar-simple-slider[data-arrows="true"] .flickity-slider .cell {
              padding: 0 100px;
            }
            .nectar-simple-slider .flickity-page-dots {
              padding: 0 20px 20px;
            }
            .nectar-flickity.nectar-simple-slider[data-arrows="true"].arrow-position-overlapping .flickity-slider .cell {
              padding: 0 60px;
            }
          }
          @media only screen and (max-width: 690px) {

            .nectar-simple-slider .flickity-prev-next-button {
              transform: scale(0.65);
            }
            .nectar-flickity.nectar-simple-slider[data-arrows="true"]:not(.arrow-position-overlapping) .flickity-slider .cell {
              padding: 0 80px;
            }
          }
          ';


          // Arrows
          if( isset($atts['simple_slider_arrow_positioning']) && 'overlapping' === $atts['simple_slider_arrow_positioning'] ) {
            self::$element_css[] = '
            .nectar-simple-slider.arrow-position-overlapping .flickity-prev-next-button.previous {
              left: -21px;
              width: 42px;
              height: 42px;
            }
            .nectar-simple-slider.arrow-position-overlapping .flickity-prev-next-button.next {
              right: -21px;
              width: 42px;
              height: 42px;
            }';

            if( isset($atts['simple_slider_arrow_button_border_color']) && !empty($atts['simple_slider_arrow_button_border_color']) ) {
              $color = ltrim($atts['simple_slider_arrow_button_border_color'],'#');
              self::$element_css[] = '.nectar-simple-slider.arrow-position-overlapping.arrow-btn-border-'.esc_attr($color).' .flickity-prev-next-button:before {
                box-shadow: 0px 0px 0px 6px #'.esc_attr($color).';
              }';
            }

          }

          //// Arrow Button Coloring.
          if( isset($atts['simple_slider_arrow_button_color']) && !empty($atts['simple_slider_arrow_button_color']) ) {
            $color = ltrim($atts['simple_slider_arrow_button_color'],'#');
            self::$element_css[] = '.nectar-simple-slider.arrow-btn-'.esc_attr($color).' .flickity-prev-next-button:before {
              background-color: #'.esc_attr($color).';
            }';
          }
          if( isset($atts['simple_slider_arrow_color']) && !empty($atts['simple_slider_arrow_color']) ) {
            $color = ltrim($atts['simple_slider_arrow_color'],'#');
            self::$element_css[] = '.nectar-simple-slider.arrow-'.esc_attr($color).' .flickity-prev-next-button .arrow {
              fill: #'.esc_attr($color).';
            }
            .nectar-simple-slider.arrow-'.esc_attr($color).' .flickity-prev-next-button:after {
              background-color: #'.esc_attr($color).';
            }';
          }

          // Pagination Coloring.
          if( isset($atts['simple_slider_pagination_coloring']) && 'default' !== $atts['simple_slider_pagination_coloring'] ) {

            if( 'light' === $atts['simple_slider_pagination_coloring'] ) {
              self::$element_css[] = '.nectar-simple-slider.pagination-color-light .flickity-page-dots .dot {
                mix-blend-mode: initial;
              }';
            }
            else {
              self::$element_css[] = '.nectar-simple-slider.pagination-color-dark .flickity-page-dots .dot {
                mix-blend-mode: initial;
              }
              .nectar-simple-slider.pagination-color-dark .flickity-page-dots .dot:before {
                background-color: #000;
              }
              .nectar-simple-slider.pagination-color-dark .flickity-page-dots svg circle.time {
                stroke: #000;
              }';
            }


     			}



          // Sizing.
          $sizing_type = 'aspect_ratio';

          if( isset($atts['simple_slider_sizing']) && 'percentage' === $atts['simple_slider_sizing'] ) {
            $sizing_type = 'percentage';
          }

          // Aspect Ratios.
          if( 'aspect_ratio' === $sizing_type ) {

            $ratio = ( isset($atts['simple_slider_aspect_ratio']) ) ? esc_attr($atts['simple_slider_aspect_ratio']) : '1-1';

            self::$element_css[] = '.nectar-simple-slider.sizing-aspect-ratio.aspect-'.$ratio.' {
              padding-bottom: '.self::image_aspect_ratio($ratio).';
            }';

          }

          // Percentage.
          else {

            $height_percent = ( isset($atts['simple_slider_height']) ) ? esc_attr($atts['simple_slider_height']) : '50vh';

            self::$element_css[] = '.nectar-simple-slider.sizing-percentage.height-'.$height_percent.' {
              height: '.$height_percent.';
            }';
          }

          // Min Height.
          if( isset($atts['simple_slider_min_height']) && !empty($atts['simple_slider_min_height']) ) {
            self::$element_css[] = '.nectar-simple-slider.min-height-'.esc_attr( self::percent_unit_type_class($atts['simple_slider_min_height']) ).' {
              min-height: '.esc_attr( self::percent_unit_type($atts['simple_slider_min_height']) ).';
            }';
          }


        }

      }
      

      // Caorusel Item - Simple Slider.
      else if ( false !== strpos($shortcode[0],'[item ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Font Color.
        if( isset($atts['simple_slider_font_color']) && !empty($atts['simple_slider_font_color']) ) {
          $color = ltrim($atts['simple_slider_font_color'],'#');
          self::$element_css[] = '.nectar-simple-slider .cell.text-color-'.esc_attr($color).' .inner {
             color: #'.esc_attr($color).';
          }';
        }

        // BG Pos.
        if( isset($atts['simple_slider_bg_image_position']) &&
            !empty($atts['simple_slider_bg_image_position']) &&
            'default' !== $atts['simple_slider_bg_image_position'] ) {

          self::$element_css[] = '#ajax-content-wrap .nectar-simple-slider .cell.bg-pos-'.esc_attr($atts['simple_slider_bg_image_position']).' > .bg-layer-wrap .bg-layer {
             background-position: '.esc_attr(str_replace('-',' ', $atts['simple_slider_bg_image_position'])).';
          }';
        }

        // BG Coloring.
        $simple_gradient = ( isset($atts['simple_slider_enable_gradient']) && 'true' === $atts['simple_slider_enable_gradient']) ? true : false;

        $simple_color_overlay_1       = ( isset($atts['simple_slider_color_overlay']) && !empty($atts['simple_slider_color_overlay']) ) ? $atts['simple_slider_color_overlay'] : 'transparent';
        $simple_color_overlay_1_class = str_replace('#','', $simple_color_overlay_1);
        $simple_color_overlay_1_class = str_replace('(', '\(', $simple_color_overlay_1_class);
        $simple_color_overlay_1_class = str_replace(')', '\)', $simple_color_overlay_1_class);
        $simple_color_overlay_1_class = str_replace('.', '\.', $simple_color_overlay_1_class);
        $simple_color_overlay_1_class = str_replace(',', '\,', $simple_color_overlay_1_class);

        $simple_color_overlay_2       = ( isset($atts['simple_slider_color_overlay_2']) && !empty($atts['simple_slider_color_overlay_2']) ) ? $atts['simple_slider_color_overlay_2'] : 'transparent';
        $simple_color_overlay_2_class = str_replace('#','',$simple_color_overlay_2);
        $simple_color_overlay_2_class = str_replace('(', '\(', $simple_color_overlay_2_class);
        $simple_color_overlay_2_class = str_replace(')', '\)', $simple_color_overlay_2_class);
        $simple_color_overlay_2_class = str_replace('.', '\.', $simple_color_overlay_2_class);
        $simple_color_overlay_2_class = str_replace(',', '\,', $simple_color_overlay_2_class);

        // Gradient overlays.
        if( true === $simple_gradient ) {

          $color_overlay_selectors = '';
          if( 'transparent' !== $simple_color_overlay_1_class ) {
            $color_overlay_selectors .= '.color-overlay-1-' . $simple_color_overlay_1_class;
          }
          if( 'transparent' !== $simple_color_overlay_2_class ) {
            $color_overlay_selectors .= '.color-overlay-2-' . $simple_color_overlay_2_class;
          }

          self::$element_css[] = '.nectar-simple-slider .cell.color-overlay-gradient'.esc_attr($color_overlay_selectors).' > .bg-layer-wrap > .color-overlay {
             background: linear-gradient(90deg, '.esc_attr($simple_color_overlay_1).', '.esc_attr($simple_color_overlay_2).');
          }';

        }
        // Regular color overlay.
        else {
          self::$element_css[] = '.nectar-simple-slider .cell.color-overlay-1-'.esc_attr($simple_color_overlay_1_class).' > .bg-layer-wrap > .color-overlay {
             background-color: '.esc_attr($simple_color_overlay_1).';
          }';
        }


        // Custom Width.
			  if( isset($atts['flickity_custom_item_width']) && !empty($atts['flickity_custom_item_width']) ) {
          self::$element_css[] = '@media only screen and (min-width: 1000px) { 
            .nectar-flickity .cell.custom-desktop-width-'. self::percent_unit_type_class(esc_attr($atts['flickity_custom_item_width'])) . ' {
              width: '.esc_attr(self::percent_unit_type($atts['flickity_custom_item_width'])).'!important;
           }
         }';
        }

      }

 
      // Interactive Map.
      else if( false !== strpos($shortcode[0],'[nectar_gmap ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // GMAP.
        if( isset($atts['map_type']) && 'google' === $atts['map_type'] || !isset($atts['map_type']) ) {

          // Coloring.
          if( isset($atts['nectar_marker_color']) && !empty($atts['nectar_marker_color']) ) {

            $color = self::locate_color(strtolower($atts['nectar_marker_color']));
            if( $color ) {

              // Animated Signal.
              if( isset($atts['marker_style']) && 'nectar' === $atts['marker_style'] ) {
                self::$element_css[] = '.nectar-google-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot .middle-dot,
                .nectar-google-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot div[class*="signal"] {
                  background-color: '.esc_attr($color['color']).';
                }';
              }

            } // color in use.

          }

        }
        // Leaflet.
        else if( isset($atts['map_type']) && 'leaflet' === $atts['map_type'] ) {

          // Coloring.
          if( isset($atts['color']) && !empty($atts['color']) ) {

            $color = self::locate_color(strtolower($atts['color']));
            if( $color ) {

              // Leaflet Pin.
              if( isset($atts['marker_style']) && 'default' === $atts['marker_style'] ) {
                self::$element_css[] = '.nectar-leaflet-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .nectar-leaflet-pin {
                  border: 10px solid '.esc_attr($color['color']).';
                }';
              }
              // Animated Signal.
              else if( isset($atts['marker_style']) && 'nectar' === $atts['marker_style'] ) {
                self::$element_css[] = '.nectar-leaflet-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot .middle-dot,
                .nectar-leaflet-map[data-nectar-marker-color="'.esc_attr($color['name']).'"] .animated-dot div[class*="signal"] {
                  background-color: '.esc_attr($color['color']).';
                }';

              }

            } // color in use.

          }

        }

      }


      // Self Hosted Video Player.
      else if( false !== strpos($shortcode[0],'[nectar_video_player_self_hosted ')  ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        // Core.
          self::$element_css[] = '.nectar_video_player_self_hosted .wpb_wrapper video,
          .nectar_video_player_self_hosted__overlay {
            width: 100%;
            height: 100%;
            display: block;
            position: absolute;
            margin: 0;
            top: 0;
            left: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            visibility: visible;
            object-fit: cover;
        }
        #ajax-content-wrap .nectar_video_player_self_hosted__overlay {
          object-fit: inherit;
          position: absolute;
          z-index: 1;
        }
        .nectar_video_player_self_hosted[data-border-radius*="px"] .wpb_video_wrapper {
          overflow: hidden;
        }
        .nectar_video_player_self_hosted video.no-video {
          opacity: 0;
        }
        ';
        
        // Mobile aspect ratio overrides.
        $video_aspect_ratio_arr = array(
          '169' => '56.25%',
          '43' => '75%',
          '235' => '42.55319149%',
          '916' => '177.77%',
          '11' => '100%',
        );

        if( isset($atts['el_aspect_tablet']) && in_array($atts['el_aspect_tablet'], array('169','43','235','916')) ) {
          
          $tablet_aspect_ratio = $atts['el_aspect_tablet'];

          self::$element_css[] = '@media only screen and (max-width: 999px) {
            .wpb_video_widget.nectar_video_player_self_hosted.tablet-aspect-'.esc_attr($atts['el_aspect_tablet']).' .wpb_video_wrapper {
               padding-top: '.esc_attr($video_aspect_ratio_arr[$tablet_aspect_ratio]).';
            }
           }';

         }

         if( isset($atts['el_aspect_phone']) && in_array($atts['el_aspect_phone'], array('169','43','235','916')) ) {
          
          $phone_aspect_ratio = $atts['el_aspect_phone'];

          self::$element_css[] = '@media only screen and (max-width: 690px) {
            .wpb_video_widget.nectar_video_player_self_hosted.phone-aspect-'.esc_attr($atts['el_aspect_phone']).' .wpb_video_wrapper {
               padding-top: '.esc_attr($video_aspect_ratio_arr[$phone_aspect_ratio]).';
            }
           }';

         }
  
        

        // Remove on mobile.
        if (isset($atts['rm_on_mobile']) && 'true' === $atts['rm_on_mobile'] ) {
          self::$element_css[] = '.nectar_video_player_self_hosted.rm-on-mobile .wpb_video_wrapper {
              background-size: cover;
              background-position: center;
            }';
        }
        
        // Border Radius.
        if( isset($atts['border_radius']) && !empty($atts['border_radius']) && 'none' !== $atts['border_radius'] ) {
          self::$element_css[] = '
          .nectar_video_player_self_hosted[data-border-radius="'.esc_attr($atts['border_radius']).'"] .wpb_video_wrapper,
          .nectar_video_player_self_hosted[data-border-radius="'.esc_attr($atts['border_radius']).'"] .wpb_video_wrapper video {
            border-radius: '.esc_attr($atts['border_radius']).';
          }';
        }
        
        // Lightbox style
        if(  isset($atts['player_functionality']) && 'lightbox' === $atts['player_functionality'] ) {

           // Custom color.
          $play_button_icon_color = '#fff';
          if( isset($atts['play_button_icon_color']) && !empty($atts['play_button_icon_color']) ) {
            $play_button_icon_color = $atts['play_button_icon_color'];
          }

          $play_button_color = '#000';
          if( isset($atts['play_button_color']) && !empty($atts['play_button_color']) ) {
            $play_button_color = $atts['play_button_color'];
          }


          self::$element_css[] = '
          .nectar_video_player_self_hosted .wpb_video_wrapper {
            overflow: hidden;
          }
          .nectar_video_player_self_hosted .play_button {
              z-index: 100;
              position: absolute;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
          }
          .nectar_video_player_self_hosted .play_button svg {
            height: 16px; 
            width: 16px; 
            position: relative;
            z-index: 100;
            margin-left: 4px;
          }
          .nectar_video_player_self_hosted .play_button .play {
            position: absolute;
            top: 0;
            left: 0;
            width: 8%;
            margin-top: -4%;
            margin-left: -4%;
            top: 50%;
            left: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
          }
          .nectar_video_player_self_hosted .play_button .play:after {
            content: "";
            display: block;
            padding-bottom: 100%;
          }
          @media only screen and (max-width: 999px) {
            .nectar_video_player_self_hosted .play_button .play {
              width: 54px;
              height: 54px;
              margin-top: -27px;
              margin-left: -27px;
            }
            .nectar_video_player_self_hosted .play_button svg { 
              height: 12px; 
              width: 12px; 
            }
          }
          .nectar_video_player_self_hosted .play_button.following.follow_mouse .play {
            opacity: 1;
          }
          .nectar_video_player_self_hosted .play_button.follow_mouse .play {
            pointer-events: none;
            transition: opacity 0.3s ease;
          }
         
          .nectar_video_player_self_hosted .play_button .play:before {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 100px;
            content: "";
            z-index: 0;
            background-color: #000;
            transition: transform 0.3s ease;
          }
          .nectar_video_player_self_hosted .play_button:not(.follow_mouse):hover .play:before {
            transform: scale(1.15);
          }
          .nectar_video_player_self_hosted .play_button svg path {
            fill: #fff; 
          }
         ';

         self::$element_css[] = '.nectar_video_player_self_hosted .play_button[data-play_button_color="'.esc_attr($play_button_color).'"] .play:before {
          background-color: '.esc_attr($play_button_color).';
        }
        .nectar_video_player_self_hosted .play_button[data-play_button_icon_color="'.esc_attr($play_button_icon_color).'"] svg path {
          fill: '.esc_attr($play_button_icon_color).'; 
        }';

        }

        if( isset($atts['play_button_hide']) && 'yes' == $atts['play_button_hide'] ) {
          self::$element_css[] = '
          @media only screen and (min-width: 1000px) {.nectar_video_player_self_hosted.play_button_hover .play {
              opacity: 0;
            }
            .nectar_video_player_self_hosted.play_button_hover:hover .play {
              opacity: 1;
            }
            .nectar_video_player_self_hosted.play_button_hover .play:before {
              transform: scale(0.3);
              transition: transform 0.45s ease-out;
            }
            .nectar_video_player_self_hosted.play_button_hover:hover .play:before {
              transform: scale(1);
            }
          }';
         }

        

      }


      // Pricing Column.
      else if( false !== strpos($shortcode[0],'[pricing_column ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);
        $pricing_el_color = (isset($atts['color'])) ? $atts['color'] : 'accent-color';

        // Coloring.
        if( !empty($pricing_el_color) ) {

          $color = self::locate_color(strtolower($pricing_el_color));
          if( $color ) {

            self::$element_css[] = '.pricing-table[data-style="flat-alternative"] .pricing-column.highlight.'.esc_attr($color['name']).' h3,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).' h4,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).' .interval {
              color: '.esc_attr($color['color']).';
            }
            .pricing-table[data-style="default"] .pricing-column.highlight.'.esc_attr($color['name']).' h3,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).'.highlight h3 .highlight-reason,
            .pricing-table[data-style="flat-alternative"] .pricing-column.'.esc_attr($color['name']).':before {
              background-color: '.esc_attr($color['color']).';
            }';

          } // color in use.

        }

      }

      // Legacy Shortcode Icon Element.
      else if ( false !== strpos($shortcode[0],'[icon ') ) {

        $atts = shortcode_parse_atts($shortcode_inner);

        $icon_el_color = (isset($atts['color'])) ? $atts['color'] : 'accent-color';

        if( !empty($icon_el_color) ) {

          $color = self::locate_color(strtolower($icon_el_color));

          if( $color && isset($color['color']) ) {

            if( isset($atts['size']) && !empty($atts['size']) ) {

              // 3x Both.
              if( 'large' === $atts['size'] ||
                  'large-2' === $atts['size'] ) {

                  self::$element_css[] = '.icon-3x[class^="icon-"].'.esc_attr($color['name']).',
                  .icon-3x[class*=" icon-"].'.esc_attr($color['name']).' {
                     color: '.esc_attr($color['color']).';
                  }
                  #sidebar .widget:hover [class^="icon-"].icon-3x.'.esc_attr($color['name']).' {
                    background-color: '.esc_attr($color['color']).';
                  }';

              }

              // 3x Alt style.
              if( 'large-2' === $atts['size'] ) {
                self::$element_css[] = '.col:not(.post-area):not(.span_12):not(#sidebar):hover [class^="icon-"].icon-3x.'.esc_attr($color['name']).'.alt-style.hovered,
                body .col:not(.post-area):not(.span_12):not(#sidebar):hover a [class*=" icon-"].icon-3x.'.esc_attr($color['name']).'.alt-style.hovered {
                  color: '.esc_attr($color['color']).'!important;
                }
                body [class^="icon-"].icon-3x.alt-style.'.esc_attr($color['name']).',
                body [class*=" icon-"].icon-3x.alt-style.'.esc_attr($color['name']).' {
                  background-color: '.esc_attr($color['color']).';
                }';
              }
              // 3x.
              else if( 'large' === $atts['size'] ) {

                self::$element_css[] = '
                .icon-3x[class^="icon-"].'.esc_attr($color['name']).':not(.alt-style),
            		.icon-3x[class*=" icon-"].'.esc_attr($color['name']).':not(.alt-style) {
                  color: '.esc_attr($color['color']).';
                }
                .col:hover > [class^="icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered,
                .col:hover > [class*=" icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered,
                .col:not(.post-area):not(.span_12):not(#sidebar):hover [class^="icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered,
                .col:not(.post-area):not(.span_12):not(#sidebar):hover a [class*=" icon-"].icon-3x:not(.alt-style).'.esc_attr($color['name']).'.hovered {
                  background-color: '.esc_attr($color['color']).';
                }
                .col:not(.post-area):not(.span_12):not(#sidebar):hover .'.esc_attr($color['name']).'.hovered .circle-border,
                body .col:not(.post-area):not(.span_12):not(#sidebar):hover [class*=" icon-"].icon-3x.hovered.'.esc_attr($color['name']).' .circle-border,
                body #sidebar .widget:hover [class*=" icon-"].icon-3x.'.esc_attr($color['name']).' .circle-border {
                  	border-color: '.esc_attr($color['color']).';
                }';

              }
              else if( 'tiny' === $atts['size']  ) {
                self::$element_css[] = '.icon-tiny[class^="icon-"].'.esc_attr($color['name']) . '{
                  color: '.esc_attr($color['color']).';
                }';
              }


            }

          } // color in use.

        }

      }


      // Link indicator Elements.
      if( false !== strpos($shortcode[0],'[split_line_heading ' ) ) {
        
        $atts = shortcode_parse_atts($shortcode_inner);

        if( isset($atts['link_indicator']) && 'true' === $atts['link_indicator'] ) {
          self::$element_css[] = '.nectar-view-indicator .nectar-cta {
            display: inline-block;
            top: -7px;
            position: relative;
            transform: rotate(-45deg) translateZ(0);
          }
          .nectar-view-indicator .nectar-cta svg {
            top: 0;
            left: 24px;
          }
          .nectar-view-indicator .nectar-cta .link_wrap {
            display: block;
            left:-2px;
          }
          .nectar-view-indicator .nectar-cta .link_wrap .line {
            position: relative; 
            animation: none;
            transform: scaleX(0);
          }
          .nectar-view-indicator .nectar-cta .link_wrap polyline {
            animation: none;
          
          }
          .visible.nectar-view-indicator .nectar-cta .link_wrap polyline {
            animation: ctaArrowStart 0.8s ease forwards 0.2s;
          }

          @keyframes ctaArrowLineReveal {
            0% {
              transform-origin: left;
              transform: scaleX(0) translateZ(0);
            }
            100% {
              transform: scaleX(1) translateZ(0);
            }
          }
          .visible.nectar-view-indicator .nectar-cta .link_wrap .line {
            animation: ctaArrowLineReveal 0.45s cubic-bezier(0.23, 0.46, 0.4, 1) forwards 0.1s;
          }';
        }
        
      }

      // Iconsmind Elements.
      if( false !== strpos($shortcode[0],'[nectar_flip_box ') ||
         false !== strpos($shortcode[0],'[nectar_btn ' ) ||
         false !== strpos($shortcode[0],'[fancy_box ') ||
         false !== strpos($shortcode[0],'[nectar_icon ') ||
         false !== strpos($shortcode[0],'[nectar_horizontal_list_item ') ||
         false !== strpos($shortcode[0],'[nectar_icon_list_item ') ||
         false !== strpos($shortcode[0],'[tab ') ) {

          $atts = shortcode_parse_atts($shortcode_inner);

          // Iconsmind CSS
          if( isset($atts['icon_family']) &&
             'iconsmind' === $atts['icon_family'] &&
             isset($atts['icon_iconsmind']) &&
             !empty($atts['icon_iconsmind']) &&
             function_exists('nectar_iconsmind_icon_list') ) {

            $iconsmind_icons = nectar_iconsmind_icon_list();
            $selected_iconsmind = $atts['icon_iconsmind'];

            if( isset($iconsmind_icons[$selected_iconsmind]) ) {
              self::$element_css[] = '.'.esc_attr($atts['icon_iconsmind']).':before{content:"'.esc_attr($iconsmind_icons[$selected_iconsmind]).'"}';
            }

          }

      }
      


  }



  public static function get_drag_indicator_solid_css() {
    return '.nectar-drag-indicator[data-type="solid"] {
      mix-blend-mode: normal;
      left: -47px;
      top: -47px;
      width: 94px;
      height: 94px;
    }
    .nectar-drag-indicator[data-type="solid"] .color-circle {
      background-color: #000;
      width: 100%;
      height: 100%;
      display: block;
      position: absolute;
      left: -2px;
      top: -2px;
      transform: scale(0.2);
      transition: transform 0.45s ease, opacity 0.3s ease;
      opacity: 0;
      border-radius: 50%;
    }

    .nectar-drag-indicator[data-type="solid"].visible .color-circle{
      transform: scale(1);
      opacity: 1;
    }
    .nectar-drag-indicator[data-type="solid"] i {
      font-size: 21px;
      top: -13px;
    }
    
    .nectar-drag-indicator[data-type="solid"]:before {
      display: none;
    }
    .nectar-drag-indicator[data-type="solid"].visible i {
      transition: transform 0.45s ease, opacity 0.3s ease, color 0.3s ease;
    }
    .nectar-drag-indicator[data-type="solid"] i.fa-angle-left {
      left: 23px;
    }
    .nectar-drag-indicator[data-type="solid"] i.fa-angle-right {
      right: 27px;
    }
    .nectar-drag-indicator.visible.pointer-down[data-type="solid"] .color-circle {
      transform: scale(0.15);
    }
    .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i {
      color: inherit!important;
    }

    .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i.fa-angle-left {
      transform: translateX(-10px);
    }
    .nectar-drag-indicator.visible.pointer-down[data-type="solid"] i.fa-angle-right {
      transform: translateX(10px);
    }';
  }

  public static function get_drag_indicator_tooltip_css() {
    return '.nectar-drag-indicator[data-type="tooltip_text"] {
      mix-blend-mode: normal;
      left: 12px;
      top: 12px;
      width: auto;
      height: auto;
      line-height: 1;
      padding: 7px 14px;
    }
    .nectar-drag-indicator[data-type="tooltip_text"] .fa-angle-left,
    .nectar-drag-indicator[data-type="tooltip_text"] .fa-angle-right {
      display: none;
    }
    .nectar-drag-indicator[data-type="tooltip_text"]:before {
      display: none;
    }

    .nectar-drag-indicator[data-type="tooltip_text"] .color-circle {
      background-color: #000;
      width: 100%;
      height: 100%;
      display: block;
      position: absolute;
      left: 0px;
      top: 0px;
      opacity: 0;
      border-radius: var(--nectar-border-radius);
      transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
      transform: scale(0.65);
    }
    .nectar-drag-indicator[data-type="tooltip_text"] span {
      opacity: 0; 
      position: relative;
      top: 0;
      left: 0;
      font-size: 14px;
      transition: opacity 0.2s ease-in-out;
      transform: none;
    }
    
    .nectar-drag-indicator[data-type="tooltip_text"].visible .color-circle{
      transform: scale(1);
    }
    .nectar-drag-indicator[data-type="tooltip_text"].visible span,
    .nectar-drag-indicator[data-type="tooltip_text"].visible .color-circle {
      opacity: 1;
    }';
  }


  /**
  * Prepares font sizing
  */
  public static function font_sizing_format($str) {

    if( strpos($str,'vw') !== false ||
      strpos($str,'vh') !== false ||
      strpos($str,'em') !== false ||
      strpos($str,'rem') !== false ) {
      return $str;
    } else {
      return intval($str) . 'px';
    }

  }


  /**
  * Verifies custom coloring is in use.
  */
  public static function custom_color_bool($param, $atts) {

    if(isset($atts[$param.'_type']) &&
			!empty($atts[$param.'_type']) &&
			'custom' === $atts[$param.'_type'] &&
			isset($atts[$param.'_custom']) &&
			!empty($atts[$param.'_custom']) ) {
			return true;
		}
		return false;

  }


  /**
  * Determines the unit type px or percent
  */
  public static function percent_unit_type($str, $forced_unit = false) {

    if( false !== $forced_unit ) {
      return floatval($str) . $forced_unit;
    }
    
    if( false !== strpos($str,'%') ) {
      return intval($str) . '%';
    } 
    else if( false !== strpos($str,'vh') ) {
      return intval($str) . 'vh';
    } 
    else if( false !== strpos($str,'vw') ) {
      return intval($str) . 'vw';
    } 
    else if( false !== strpos($str,'rem') ) {
      return intval($str) . 'rem';
    }
    else if( false !== strpos($str,'em') ) {
      return intval($str) . 'em';
    }
    else if( false !== strpos($str,'deg') ) {
      return intval($str) . 'deg';
    }
    else if( false !== strpos($str,'scale') ) {
      return intval($str)/100;
    }
    else if( 'auto' === $str ) {
			return 'auto';
		} 
    return intval($str) . 'px';

  }

  /**
  * Determines the unit type classname 
  */
  public static function percent_unit_type_class($str, $forced_unit = false) {

    if( $forced_unit !== false ) {
      return str_replace('.','-',floatval($str)) . $forced_unit;
    }

    if( false !== strpos($str,'%') ) {
      return str_replace('%','pct', $str);
    } else if( false !== strpos($str,'vh') ) {
      return intval($str) . 'vh';
    } else if( false !== strpos($str,'vw') ) {
      return intval($str) . 'vw';
    } else if( false !== strpos($str,'rem') ) {
      return intval($str) . 'rem';
    } else if( false !== strpos($str,'em') ) {
      return intval($str) . 'em';
    } else if( 'auto' === $str ) {
			return 'auto';
		}

    return intval($str) . 'px';
  }

  public static function font_size_output($str) {

    if( false !== strpos($str,'%') ) {
      return floatval($str) . '%';
    } else if( false !== strpos($str,'vh') ) {
      return floatval($str) . 'vh';
    } else if( false !== strpos($str,'vw') ) {
      return floatval($str) . 'vw';
    } else if( false !== strpos($str,'em') ) {
      return floatval($str) . 'em';
    } else if( 'inherit' === $str ) {
			return 'inherit';
		}

    return floatval($str) . 'px';
  }


  public static function line_height_output($str) {

    if( false !== strpos($str,'%') ) {
      return floatval($str) . '%';
    } else if( false !== strpos($str,'vh') ) {
      return floatval($str) . 'vh';
    } else if( false !== strpos($str,'vw') ) {
      return floatval($str) . 'vw';
    } else if( false !== strpos($str,'em') ) {
      return floatval($str) . 'em';
    } else if( false !== strpos($str,'px') ) {
      return floatval($str) . 'px';
    } else if( 'inherit' === $str ) {
			return 'inherit';
		}

    return floatval($str);
  }

  public static function decimal_unit_type_class($str) {
    
    if( false !== strpos($str,'.') ) {
			return str_replace('.','-', $str);
		} 
		else {
      return self::percent_unit_type_class($str);
    }

	}

  /**
  * Generates the padding for a specific aspect ratio.
  */
  public static function image_aspect_ratio($aspect) {

    if( '4-3' === $aspect )  {
      return 'calc((3 / 4) * 100%)';
    }
    else if( '3-2' === $aspect )  {
      return 'calc((2 / 3) * 100%)';
    }
    else if( '16-9' === $aspect )  {
      return 'calc((9 / 16) * 100%)';
    }
    else if( '2-1' === $aspect )  {
      return 'calc((1 / 2) * 100%)';
    }
    else if( '4-5' === $aspect )  {
      return 'calc((5 / 4) * 100%)';
    }
    else { //1-1 default
      return 'calc((1 / 1) * 100%)';
    }
  }

   /**
  * Generates text direction styles.
  */
  public static function text_direction_styles($atts, $selector) {
    if( isset($atts['text_direction']) && in_array($atts['text_direction'], array('rtl','ltr')) ) {
      return $selector.'.text_direction_'.esc_attr($atts['text_direction']).' { direction:'.esc_attr($atts['text_direction']).'; }';
    }
    return '';
  }


  /**
  * Generates the padding for a specific aspect ratio.
  */
  public static function shadow_output($shadow_size, $shadow_type) {

    $shadow = '';

    if( 'small_depth' === $shadow_size ) {

      $shadow = 'box-shadow: rgba(0, 0, 0, 0.04) 0px 1px 0px, 
      rgba(0, 0, 0, 0.05) 0px 2px 7px, 
      rgba(0, 0, 0, 0.06) 0px 12px 22px;';

      if( 'filter' === $shadow_type ) {
        $shadow = 'filter: drop-shadow(0px 1px 0px rgba(0, 0, 0, 0.04) )
        drop-shadow(0px 2px 7px rgba(0, 0, 0, 0.05) )
        drop-shadow(0px 12px 22px rgba(0, 0, 0, 0.06) );';
      } 
      
    } 
    else if( 'medium_depth' === $shadow_size ) {

      $shadow = '0 20px 30px rgba(0,0,0,0.20),
      0 20px 40px rgba(0,0,0,0.20)';

      if( 'filter' === $shadow_type ) {
        $shadow = 'filter: drop-shadow(0 25px 30px rgba(0,0,0,0.2))
        drop-shadow(0 20px 40px rgba(0,0,0,0.2));';
      }

    }
    else if( 'large_depth' === $shadow_size ) {

      $shadow = '0 40px 100px rgba(0,0,0,0.15),
      0 25px 80px rgba(0,0,0,0.1)';

      if( 'filter' === $shadow_type ) {
        $shadow = 'filter: drop-shadow(0 40px 100px rgba(0,0,0,0.15))
        drop-shadow(0 25px 80px rgba(0,0,0,0.1));';
      }

    }
    else if( 'x_large_depth' === $shadow_size ) {

      $shadow = '0 60px 135px rgba(0,0,0,0.14),
        0 15px 65px rgba(0,0,0,0.14)';

      if( 'filter' === $shadow_type ) {
        $shadow = 'filter: drop-shadow(0 60px 135px rgba(0,0,0,0.14)) 
                           drop-shadow(0 15px 65px rgba(0,0,0,0.14));';
      } 

    }
    else if( 'x_large_layered_depth' === $shadow_size ) {

      $shadow = '0 90px 70px 0 rgb(0, 0, 0, 0.06), 
       0 40px 35px 0 rgb(0, 0, 0, 0.05), 
       0 15px 15px 0 rgb(0, 0, 0, 0.05), 
       0 11px 7px 0 rgb(0, 0, 0, 0.05), 
       0 2px 5px 0 rgb(0, 0, 0, 0.04)';

       if( 'filter' === $shadow_type ) {
        $shadow = 'filter: drop-shadow(0 90px 70px 0 rgb(0, 0, 0, 0.06)) 
                           drop-shadow(0 40px 35px 0 rgb(0, 0, 0, 0.05))
                           drop-shadow(0 15px 15px 0 rgb(0, 0, 0, 0.05))
                           drop-shadow(0 11px 7px 0 rgb(0, 0, 0, 0.05))
                           drop-shadow(0 2px 5px 0 rgb(0, 0, 0, 0.04));';
      } 
       
   }
    return $shadow;
  }


   /**
  * Reusable param group styles
  */
  public static function mask_param_group_styles( $element_name, $inner_selector, $shadow, $atts ) {

    $css = '';
    
    if( isset($atts['mask_enable']) && $atts['mask_enable'] == 'true' && defined('SALIENT_CORE_PLUGIN_PATH') ) {
      
      $mask_shape = (isset($atts['mask_shape']) && !empty($atts['mask_shape'])) ? $atts['mask_shape'] : 'circle';
      $mask_scale = (isset($atts['mask_scale']) && !empty($atts['mask_scale'])) ? $atts['mask_scale'] : '100';
      $mask_size = (isset($atts['mask_size']) && !empty($atts['mask_size'])) ? $atts['mask_size'] : 'fit';
      
       // shape.
       if( 'custom' !== $mask_shape ) {
        $css .= '.'.$element_name.'.mask_shape_'.esc_attr($mask_shape).$inner_selector.'  {
          -webkit-mask-image: url('.esc_html(SALIENT_CORE_PLUGIN_PATH).'/includes/img/masks/'.esc_attr($mask_shape).'.svg);
        }';
      }

      $css .= '.'.$element_name.'.mask_shape_'.esc_attr($mask_shape).$inner_selector.' {
        -webkit-mask-repeat: no-repeat;
        -webkit-mask-position: center center;
      }';

      // shadow.
      if( $shadow && $shadow !== 'none' && $shadow !== 'custom') {
        $css .= '.'.$element_name.'.mask_shape_'.esc_attr($mask_shape).'[data-shadow="'.$shadow.'"] {
         '. self::shadow_output($shadow, 'filter') .'
        }';
      }
     
      // size.
      if( 'custom' === $mask_size ) {
        $css .= '.'.$element_name.'.mask_shape_'.esc_attr($mask_shape).'.mask_size_custom.mask_scale_'.esc_attr($mask_scale).$inner_selector.' {
          -webkit-mask-size: '.esc_attr($mask_scale).'%;
        }';
      } 
      else {
        $css .= '.'.$element_name.'.mask_shape_'.esc_attr($mask_shape).'.mask_size_'.esc_attr($mask_size).$inner_selector.' {
          -webkit-mask-size: '.esc_attr($mask_size).';
        }';
      }
      

      // device groups
      foreach( self::$devices as $device => $media_query_size) {

        $device_group_params = array(
          'mask_alignment'
        );

        foreach ($device_group_params as $param) {
          
          if( isset($atts[$param.'_'.$device]) && 
          strlen($atts[$param.'_'.$device]) > 0) {
           
            $css .= '@media only screen '.$media_query_size.' { 
              body .'.$element_name.'.'.$param.'_'.$device.'_'. esc_attr($atts[$param.'_'.$device]) .$inner_selector.' {
              -webkit-mask-position: '.esc_attr(str_replace('-',' ',$atts[$param.'_'.$device])).';
            }
          }';

          } // param isset

        } // param loop
        
      } // viewport loop

    } // mask is enabled

    return $css;

  }


  public static function mask_reveal_styles($selector, $inner_selector, $direction_param, $shape_param, $easing_param, $atts) {

    $styles = array();

    $mask_direction = (isset($atts[$direction_param]) && !empty($atts[$direction_param])) ? $atts[$direction_param] : 'top';
    $mask_shape = (isset($atts[$shape_param]) && !empty($atts[$shape_param])) ? $atts[$shape_param] : 'straight';

    $formatted_selector = sprintf($selector,$mask_direction,$mask_shape);

    $clip_path_mask = array(
      'straight' => array(
        'top'    => array(
          'start' => 'inset(0 0 100% 0)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'middle' => array(
          'start' => 'inset(50% 0 50% 0)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'bottom' => array(
          'start' => 'inset(100% 0 0 0)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'left'   => array(
          'start' => 'inset(0 100% 0 0)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'left_top'    => array(
          'start' => 'inset(0 100% 100% 0)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'left_bottom'    => array(
          'start' => 'inset(100% 100% 0 0)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'right'  => array(
          'start' => 'inset(0 0 0 100%)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'right_top'    => array(
          'start' => 'inset(0 0 100% 100%)',
          'end'   => 'inset(0 0 0 0)',
        ),
        'right_bottom'    => array(
          'start' => 'inset(100% 0 0 100%)',
          'end'   => 'inset(0 0 0 0)',
        ),
      ),
      'circle' => array(
        'top'    => array(
          'start' => 'circle(0% at 50% 0)',
          'end'   => 'circle(125% at 50% 0)',
        ),
        'middle' => array(
          'start' => 'circle(0% at 50%)',
          'end'   => 'circle(80% at 50%)',
        ),
        'bottom' => array(
          'start' => 'circle(0% at 50% 100%)',
          'end'   => 'circle(125% at 50% 100%)',
        ),
        'left'   => array(
          'start' => 'circle(0 at 0%)',
          'end'   => 'circle(125% at 0%)',
        ),
        'left_bottom'  => array(
          'start' => 'circle(0 at 0% 100%)',
          'end'   => 'circle(150% at 0% 100%)',
        ),
        'left_top'  => array(
          'start' => 'circle(0 at 0% 0%)',
          'end'   => 'circle(150% at 0% 0%)',
        ),
        'right'  => array(
          'start' => 'circle(0 at 100%)',
          'end'   => 'circle(125% at 100%)',
        ),
        'right_bottom'  => array(
          'start' => 'circle(0 at 100% 100%)',
          'end'   => 'circle(150% at 100% 100%)',
        ),
        'right_top'  => array(
          'start' => 'circle(0 at 100% 0%)',
          'end'   => 'circle(150% at 100% 0%)',
        ),
      ),
    );


    // easing.
    $cubic_bezier = '0.25, 1, 0.33, 1';


    // grab from global settings.
    global $nectar_options;
    $cubic_beziers = nectar_cubic_bezier_easings();
    $animation_easing = isset($nectar_options['column_animation_easing']) && !empty($nectar_options['column_animation_easing']) ? $nectar_options['column_animation_easing'] : 'easeOutCubic';

    // custom easing.
    if( isset($atts[$easing_param]) && !empty($atts[$easing_param]) && 'default' !== $atts[$easing_param] ) {
      $animation_easing = $atts[$easing_param];
    } 

    if( isset($cubic_beziers[$animation_easing]) ) {
      $cubic_bezier = $cubic_beziers[$animation_easing];
    }


    // rules.
    $styles[] = $formatted_selector.' '.$inner_selector.' {
      clip-path: '.esc_attr($clip_path_mask[$mask_shape][$mask_direction]['start']).';
      transition: clip-path 1.3s cubic-bezier('.$cubic_bezier.');
      opacity: 1;
    }';
    
    $styles[] = $formatted_selector.'.animated-in '.$inner_selector.' {
      clip-path: '.esc_attr($clip_path_mask[$mask_shape][$mask_direction]['end']).';
    }';

    return $styles;

  }

  public static function custom_border_radius_styles($atts, $selector) {
    $styles = '';
    if( isset($atts['top_left_border_radius']) && strlen($atts['top_left_border_radius']) > 0 ) {
      $styles .= '.tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) . $selector . ',
      .tl_br_'. self::percent_unit_type(esc_attr( $atts['top_left_border_radius'])) . $selector . ' {
        border-top-left-radius: '.self::percent_unit_type(esc_attr( $atts['top_left_border_radius'] )).';
        overflow: hidden;
      }';
      
    }
    if( isset($atts['top_right_border_radius']) && strlen($atts['top_right_border_radius']) > 0 ) {
      $styles .= '.tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) . $selector . ',
      .tr_br_'. self::percent_unit_type(esc_attr( $atts['top_right_border_radius'])) . $selector . ' {
        border-top-right-radius: '.self::percent_unit_type(esc_attr( $atts['top_right_border_radius'] )).';
        overflow: hidden;
      }';
    }
    if( isset($atts['bottom_left_border_radius']) && strlen($atts['bottom_left_border_radius']) > 0 ) {
      $styles .= '.bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) . $selector . ',
      .bl_br_'. self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'])) . $selector . ' {
        border-bottom-left-radius: '.self::percent_unit_type(esc_attr( $atts['bottom_left_border_radius'] )).';
        overflow: hidden;
      }';
    }
    if( isset($atts['bottom_right_border_radius']) && strlen($atts['bottom_right_border_radius']) > 0 ) {
      $styles .= '.br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) . $selector . ',
      .br_br_'. self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'])) . $selector . ' {
        border-bottom-right-radius: '.self::percent_unit_type(esc_attr( $atts['bottom_right_border_radius'] )).';
        overflow: hidden;
      }';
    }
    return $styles;
  }

  public static function width_height_group_styles( $element_name, $inner_selector, $atts ) {

    $css = '';

    // device groups
    foreach( self::$devices as $device => $media_query_size) {

      $device_group_params = array(
        'width',
        'height'
      );

      $body_selector = ($device !== 'desktop') ? 'body ' : '';
      $html_selector = ($device === 'phone') ? 'html ' : '';

      foreach ($device_group_params as $param) {
        
        if( isset($atts[$param.'_'.$device]) && 
        strlen($atts[$param.'_'.$device]) > 0) {
         
          $css .= '@media only screen '.$media_query_size.' { 
            '. $html_selector . $body_selector . ' .'.$element_name.'.'.$param.'_'.$device.'_'. self::percent_unit_type_class(esc_attr($atts[$param.'_'.$device])) .$inner_selector.' {
              '.$param.': '.esc_attr(self::percent_unit_type($atts[$param.'_'.$device])).';
          }
        }';

        } // param isset

      } // param loop
      
    } // viewport loop

    return $css;

  }


  public static function clip_path_group_styles($selector, $atts) {


      $css_arr = array();

      $clip_params_start = array();
      $clip_params_start['clip_start_t'] = 'clip_path_start_top';
      $clip_params_start['clip_start_r'] = 'clip_path_start_right';
      $clip_params_start['clip_start_b'] = 'clip_path_start_bottom';
      $clip_params_start['clip_start_l'] = 'clip_path_start_left';
      
      
      $clip_params_end = array();
      $clip_params_end['clip_end_t'] = 'clip_path_end_top';
      $clip_params_end['clip_end_r'] = 'clip_path_end_right';
      $clip_params_end['clip_end_b'] = 'clip_path_end_bottom';
      $clip_params_end['clip_end_l'] = 'clip_path_end_left';
      

      // loop.
      foreach( self::$devices as $device => $media_query_size) {

        //// Starting.
        $clip_consolidated_class = '';
        $clip_consolidated_vals = '';
        $start_roundness_val = '';
        $start_roundness = '0';

        $has_starting_vals = false;

        foreach( $clip_params_start as $classname => $param ) {    

          //// Class name
          $clip_consolidated_class .= ( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0  ) ? nectar_el_percent_unit_type_class($atts[$param.'_'.$device]) . '-' : '0-';   
          $start_roundness = ( isset($atts['clip_path_start_roundness']) && !empty($atts['clip_path_start_roundness']) ) ? nectar_el_percent_unit_type_class($atts['clip_path_start_roundness']) : '0';
          
          //// Values
          $clip_consolidated_vals .= ( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0 ) ? self::percent_unit_type($atts[$param.'_'.$device]) . ' ' : '0 ';   
          $start_roundness_val = ( isset($atts['clip_path_start_roundness']) && !empty($atts['clip_path_start_roundness']) ) ? self::percent_unit_type($atts['clip_path_start_roundness']) : '0';

          if( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0  ) {
            $has_starting_vals = true;
          }

        }

        //// Ending.
        $clip_consolidated_end_class = '';
        $clip_consolidated_end_vals = '';
        $end_roundness_val = '';
        $end_roundness = '0';
        
        $has_ending_vals = false;

        foreach( $clip_params_end as $classname => $param ) {    

          //// Class name
          $clip_consolidated_end_class .= ( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0  ) ? nectar_el_percent_unit_type_class($atts[$param.'_'.$device]) . '-' : '0-';   
          $end_roundness = ( isset($atts['clip_path_end_roundness']) && !empty($atts['clip_path_end_roundness']) ) ? nectar_el_percent_unit_type_class($atts['clip_path_end_roundness']) : '0';
          
          //// Values
          $clip_consolidated_end_vals .= ( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0 ) ? self::percent_unit_type($atts[$param.'_'.$device]) . ' ' : '0 ';   
          $end_roundness_val = ( isset($atts['clip_path_end_roundness']) && !empty($atts['clip_path_end_roundness']) ) ? self::percent_unit_type($atts['clip_path_end_roundness']) : '0';

          if( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0  ) {
            $has_ending_vals = true;
          }

        }

        
        //// Store rules
        $css_media_query = '';
        if( $has_starting_vals || $has_ending_vals ) {
            
            $css_arr[] = '.wpb_row[class*="clip-path-start-desktop"] .nectar-shape-divider-wrap { z-index: 0; } .wpb_row[class*="clip-path-start-desktop"] .nectar-shape-divider-wrap .nectar-shape-divider { bottom: 0; }';

            $css_media_query .= '@media only screen '.$media_query_size.' {';

              if( $has_starting_vals ) {
                $css_media_query .= '.clip-path-start-'.$device.'-'. $clip_consolidated_class . $start_roundness  . $selector .' {
                    clip-path: inset('.$clip_consolidated_vals.' round '.$start_roundness_val.');
                }
                .clip-path-start-'.$device.'-'. $clip_consolidated_class . $start_roundness . $selector .' {
                  transition: clip-path 2s cubic-bezier(0.6, 0.06, 0.18, 1)!important;
                }
                .top-level.clip-path-start-'.$device.'-'. $clip_consolidated_class . $start_roundness . $selector .' {
                  transition: clip-path 1.3s cubic-bezier(0.25, 0.1, 0.18, 1)!important;
                }';
              }

              if( $has_ending_vals ) {
                $css_media_query .= '.clip-path-end-'.$device.'-'. $clip_consolidated_end_class . $end_roundness . '.animated-in' . $selector . ' {
                  clip-path: inset('.$clip_consolidated_end_vals.' round '.$end_roundness_val.');
              }
              .clip-path-end-'.$device.'-'. $clip_consolidated_end_class . $end_roundness . '.animated-in' . $selector . ' {
                transition: clip-path 2s cubic-bezier(0.6, 0.06, 0.18, 1)!important;
            }
              .top-level.clip-path-end-'.$device.'-'. $clip_consolidated_end_class . $end_roundness . '.animated-in' . $selector . ' {
                transition: clip-path 1.3s cubic-bezier(0.25, 0.1, 0.18, 1)!important;
            }';
              }

            $css_media_query .='}';

            $css_arr[] = $css_media_query;
        }

        
      }
      

      return $css_arr;

  }

  public static function positioning_group_styles($element_name, $atts) {

    $css = array();

    foreach( self::$devices as $device => $media_query_size) {

      $body_selector = ($device !== 'desktop') ? 'body ' : '';
      $html_selector = ($device === 'phone') ? 'html ' : '';

      // Transforms.
      $transform_vals = '';
      $transform_x    = false;
      $transform_y    = false;

      //// Translate X.
      if( isset($atts['translate_x_'.$device]) && strlen($atts['translate_x_'.$device]) > 0 ) {

        $transform_vals .= 'translateX('. esc_attr( self::percent_unit_type($atts['translate_x_'.$device]) ).') ';
        $transform_x = true;

      }
      //// Translate Y.
      if( isset($atts['translate_y_'.$device]) && strlen($atts['translate_y_'.$device]) > 0 ) {

        $transform_vals .= 'translateY('. esc_attr( self::percent_unit_type($atts['translate_y_'.$device]) ).')';
        $transform_y = true;

      }

      if( !empty($transform_vals) ) {

        // X only.
        if( false === $transform_y && false !== $transform_x ) {

          $css[] = '@media only screen '.$media_query_size.'  {
            .'.$element_name.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .' {
            -webkit-transform: '.esc_attr( $transform_vals ).';
            transform: '.esc_attr( $transform_vals ).';
          } }';

        }
        // Y only.
        else if ( false !== $transform_y && false === $transform_x ) {

          $css[] = '@media only screen '.$media_query_size.'  {
            .'.$element_name.'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' {
            -webkit-transform: '.esc_attr( $transform_vals ).';
            transform: '.esc_attr( $transform_vals ).';
          } }';

        }
        // X and Y.
        else if( false !== $transform_y && false !== $transform_x ) {
          $css[] = '@media only screen '.$media_query_size.'  {
            .'.$element_name.'.translate_x_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_x_'.$device]) ) .'.translate_y_'.$device.'_'. esc_attr( self::percent_unit_type_class($atts['translate_y_'.$device]) ) .' {
            -webkit-transform: '.esc_attr( $transform_vals ).';
            transform: '.esc_attr( $transform_vals ).';
          } }';

        }

      } // endif not empty transform vals.


      // Positions.
      if( isset($atts['position_'.$device]) && 
        strlen($atts['position_'.$device]) > 0 && 
        in_array($atts['position_'.$device], array('relative', 'absolute', 'fixed')) ) {

          $css[] = '@media only screen '.$media_query_size.' { '. $html_selector . $body_selector.' .'.$element_name.'.position_'.$device.'_'. esc_attr($atts['position_'.$device]) .' {
            position: '.esc_attr($atts['position_'.$device]).';
          }
         }';

        }

         
        $positions = array('top','right','bottom','left');

        foreach ($positions as $position) {
            if (isset($atts[$position.'_position_'.$device]) && strlen($atts[$position.'_position_'.$device]) > 0) {
              $css[] = '@media only screen '.$media_query_size.' { '. $html_selector . $body_selector.' .'.$element_name.'.'.$position.'_position_'.$device.'_'. esc_attr(self::percent_unit_type_class($atts[$position.'_position_'.$device])) .' {
                '.$position.': '.esc_attr(self::percent_unit_type($atts[$position.'_position_'.$device])).';
              }
            }';
          }
        }

        
      } // end device foreach


       // Zindex.
       if(isset($atts['zindex']) && strlen($atts['zindex']) > 0 ) {
        $css[] = '.'.$element_name.'.z_index_'. esc_attr($atts['zindex']) . '{
          z-index: '.esc_attr($atts['zindex']).';
         }';
      }

      return $css;

  }

  public static function transform_styles($element_name, $atts, $legacy_desktop = true) {
    
    
    $rules = array();

    $all_transforms = array();
    $params = array(
      'translate_x',
      'translate_y',
      'scale',
      'rotate'
    );

    // Gather all active transforms into array
    foreach( self::$devices as $device => $media_query_size) {

      foreach($params as $transform) {

        $cur_device = '_' . $device; 

       
        if( in_array($transform, array('translate_x', 'translate_y')) && 'desktop' === $device && true === $legacy_desktop ) {
          $cur_device = '';
        }

        if( (isset($atts[$transform.$cur_device]) && strlen($atts[$transform.$cur_device]) > 0) ) {
          
          $forced_unit = false;
          
          if( 'rotate' === $transform) {
            $forced_unit = 'deg';
          }
          if( 'scale' === $transform) {
            $forced_unit = '';
          }

          $all_transforms[$device][$transform] = array(
            'in_use' => true,
            'param' => esc_attr($transform).$cur_device,
            'selector_value' => self::percent_unit_type_class($atts[$transform.$cur_device], $forced_unit),
            'value' => self::convert_transform_param($transform) . '('.self::percent_unit_type($atts[$transform.$cur_device], $forced_unit) .') '
          );

          continue;
        } 
        
       
        $all_transforms[$device][$transform] = array(
          'in_use' => false
        );
        

      }

    }
   
  
    // Combine all active transforms in CSS output.
    foreach( self::$devices as $device => $media_query_size ) {
      
      $selector_combined = '';
      $value_combined = '';

      foreach($all_transforms[$device] as $transform) {
        if( false !== $transform['in_use'] ) {

          $param = $transform['param'];
          $selector_value = $transform['selector_value'];
          $value = $transform['value'];

          $selector_combined .= '.' . $param . '_'. $selector_value;
          $value_combined .= $value;

        }
      }

      if( !empty($selector_combined) ) {
        $rules[] = '@media only screen '.$media_query_size.' { '.$element_name.$selector_combined.' {
            -webkit-transform: '.rtrim($value_combined).';
            transform: '.rtrim($value_combined).';
          } 
        }';
      }

    }


    return $rules;

  }


  public static function convert_transform_param($str) {
    $converted_str = str_replace('translate_x','translateX',$str);
    $converted_str = str_replace('translate_y','translateY',$converted_str );
    return esc_attr($converted_str);
  }


  public static function spacing_group_styles($type, $element_name, $inner_selector, $atts) {

    $css = '';

    $spacing_arr = array('top','right','bottom','left');

    foreach( self::$devices as $device => $media_query_size ) {

      // prevent empty media queries from being generated.
      $in_use = false;
      foreach($spacing_arr as $spacing) {
        if(isset($atts[$spacing.'_'.$type.'_'.$device])) {
          $in_use = true;
        }
      }

      if( !$in_use ) {
        continue;
      }

      $css .= '@media only screen '.$media_query_size.' {';

        foreach($spacing_arr as $spacing) {

          $html_selector = ( 'phone' === $device ) ? 'html ' : '';
          $body_selector = ('desktop' !== $device) ? 'body ' : '';

          if(isset($atts[$spacing.'_'.$type.'_'.$device])) {
            $css .= $html_selector . $body_selector.'.'.$element_name.'.'.$spacing.'_'.$type.'_'.$device.'_'.esc_attr(self::percent_unit_type_class($atts[$spacing.'_'.$type.'_'.$device])) . $inner_selector.' {
              '.$type.'-'.$spacing.': '.esc_attr(self::percent_unit_type($atts[$spacing.'_'.$type.'_'.$device])).';
            }';
          }

        } // end spacing loop.
       
      $css .='}';

    } // end device loop.

    return $css;
    
  }



  public static function font_size_group_styles($element_name, $desktop_param_name = false, $desktop_media_query_size = false, $important = '', $atts = array()) {
    
    $css = '';  

    $min = (isset($atts['font_size_min']) && strlen($atts['font_size_min']) > 0) ? $atts['font_size_min'] : '';
    $max = (isset($atts['font_size_max']) && strlen($atts['font_size_max']) > 0) ? $atts['font_size_max'] : '';

    foreach( self::$devices as $device => $media_query_size ) {

      $device_param = ( $device === 'desktop' && $desktop_param_name !== false ) ? $desktop_param_name : 'font_size_'.$device ;

      // Font sizes.
      if(isset($atts[$device_param])) {

        $computed_size = self::font_size_output($atts[$device_param]);

        $using_min_max = false;
        $minmax_classes = '';
        
        // Min/max font size.
        if( !empty($min) ) {
          $computed_size = 'max('.self::font_size_output($min).','.$computed_size.')';
          $using_min_max = true;
          $minmax_classes .= '.font_size_min_'.esc_attr(self::decimal_unit_type_class($min));
        }

        if( !empty($max) ) {
          $computed_size = 'min('.self::font_size_output($max).','.$computed_size.')';
          $using_min_max = true;
          $minmax_classes .= '.font_size_max_'.esc_attr(self::decimal_unit_type_class($max));
        }
        $body_selector = ($using_min_max) ? 'body ' : '';
        $html_selector = ($device === 'phone') ? 'html ' : '';

        $container_selector = ('desktop' !== $device) ? '.container-wrap ' : '';

        if( $device === 'desktop' && $desktop_media_query_size !== false ) {
          $media_query_size = $desktop_media_query_size;
        }
        
        // One version without .container-wrap for global sections.
        $css .= '@media only screen '.$media_query_size.' {
          '.$html_selector.$body_selector.'#ajax-content-wrap '.'.'.$device_param.'_'.esc_attr(self::decimal_unit_type_class($atts[$device_param])).$minmax_classes.'.'.$element_name .',
          '.$html_selector.$body_selector.'.'.$device_param.'_'.esc_attr(self::decimal_unit_type_class($atts[$device_param])).$minmax_classes.'.'.$element_name .' {
            font-size: '.$computed_size.$important.';
          }
        }';

        $css .= '@media only screen '.$media_query_size.' {
          '.$html_selector.$body_selector.'#ajax-content-wrap '.$container_selector.'.'.$device_param.'_'.esc_attr(self::decimal_unit_type_class($atts[$device_param])).$minmax_classes.'.'.$element_name .',
          '.$html_selector.$body_selector.$container_selector.'.'.$device_param.'_'.esc_attr(self::decimal_unit_type_class($atts[$device_param])).$minmax_classes.'.'.$element_name .' {
            font-size: '.$computed_size.$important.';
          }
        }';

        

      }

    }

    // Line height.
    if(isset($atts['font_line_height'])) {
      $css .= '#ajax-content-wrap .font_line_height_'.esc_attr(self::decimal_unit_type_class($atts['font_line_height'])).'.'.$element_name .' {
          line-height: '.self::line_height_output($atts['font_line_height']).';
      }';
    }
    
    return $css;

  }


}


$nectar_el_dynamic_styles = new NectarElDynamicStyles();







/**
* Determines the unit type classname px or percent
*
* @since 11.1
*/
if( !function_exists('nectar_el_custom_color_bool') ) {

	function nectar_el_custom_color_bool($param, $atts) {

		if(isset($atts[$param.'_type']) &&
			!empty($atts[$param.'_type']) &&
			'custom' === $atts[$param.'_type'] &&
			isset($atts[$param.'_custom']) &&
			!empty($atts[$param.'_custom']) ) {
			return true;
		}
		return false;

	}

}


/**
* Determines the unit type classname px or percent
*
* @since 11.1
*/
if( !function_exists('nectar_el_padding_unit_type_class') ) {

	function nectar_el_percent_unit_type_class($str, $forced_unit = false) {

    if( $forced_unit !== false ) {
      return str_replace('.','-',floatval($str)) . $forced_unit;
    }
    
		if( false !== strpos($str,'%') ) {
			return str_replace('%','pct', $str);
		} 
    else if( false !== strpos($str,'vw') ) {
			return intval($str) . 'vw';
		} 
    else if( false !== strpos($str,'vh') ) {
			return intval($str) . 'vh';
		} 
    else if( false !== strpos($str,'rem') ) {
			return intval($str) . 'rem';
		} 
    else if( false !== strpos($str,'em') ) {
			return intval($str) . 'em';
		} 
    else if( 'auto' === $str ) {
			return 'auto';
		}

		return intval($str) . 'px';
	}

 }


 /**
* Determines the unit type classname with decimal
*
* @since 13.1
*/
 if( !function_exists('nectar_el_decimal_unit_type_class') ) {

	function nectar_el_decimal_unit_type_class($str) {
    
    if( false !== strpos($str,'.') ) {
			return str_replace('.','-', $str);
		} 
		else {
      return nectar_el_percent_unit_type_class($str);
    }

	}

 }

/**
* Reusable param group classes
*
* @since 14.1
*/

if( !function_exists('nectar_position_param_group_classes') ) {

  function nectar_position_param_group_classes($atts) {
    
    $classnames = '';

    if( isset($atts['position_desktop']) && 
            strlen($atts['position_desktop']) > 0 &&
            in_array($atts['position_desktop'], array('absolute','relative','fixed')) ) {
            $classnames .= 'position_desktop_'. esc_attr($atts['position_desktop']) . ' ';
        }
        if( isset($atts['position_tablet']) && 
            strlen($atts['position_tablet']) > 0 &&
            in_array($atts['position_tablet'], array('absolute','relative','fixed')) ) {
            $classnames .= 'position_tablet_'. esc_attr($atts['position_tablet']) . ' ';
        }
        if( isset($atts['position_phone']) && 
            strlen($atts['position_phone']) > 0 &&
            in_array($atts['position_phone'], array('absolute','relative','fixed')) ) {
            $classnames .= 'position_phone_'. esc_attr($atts['position_phone']) . ' ';
        }

      $image_params = array();
      $image_params[] = 'top_position';
      $image_params[] = 'right_position';
      $image_params[] = 'left_position';
      $image_params[] = 'bottom_position';
      $image_params[] = 'translate_y';
      $image_params[] = 'translate_x';

      // loop.
      foreach( $image_params as $param ) {

        if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {
          $classnames .= $param.'_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_desktop'])) . ' ';
        }
        if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
          $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
        }
        if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
          $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
        }

      }

      // zindex.
      if(isset($atts['zindex']) && strlen($atts['zindex']) > 0 ) {
        $classnames .= 'z_index_'. esc_attr($atts['zindex']) . ' ';
      }

      return $classnames;


  }

}


/**
* Nectar clip path param group classes
*
* @param array $atts - the attributes
* @return string - the clip path param group classes
*
* @since 14.1
*/
if( !function_exists('nectar_clip_path_param_group_classes') ) {

  function nectar_clip_path_param_group_classes($atts) {
    
    $classnames = '';

      $clip_params_start = array();
      $clip_params_start['clip_start_t'] = 'clip_path_start_top';
      $clip_params_start['clip_start_r'] = 'clip_path_start_right';
      $clip_params_start['clip_start_b'] = 'clip_path_start_bottom';
      $clip_params_start['clip_start_l'] = 'clip_path_start_left';
      
      
      $clip_params_end = array();
      $clip_params_end['clip_end_t'] = 'clip_path_end_top';
      $clip_params_end['clip_end_r'] = 'clip_path_end_right';
      $clip_params_end['clip_end_b'] = 'clip_path_end_bottom';
      $clip_params_end['clip_end_l'] = 'clip_path_end_left';
      

      $devices = array('desktop','tablet','phone');

      // end early if none are set.
      $using_clip = false;
      $all_clips = array_merge($clip_params_start, $clip_params_end);
      foreach( $devices as $device ) {
        foreach( $all_clips as $classname => $param ) {    
          if( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0 ) {
            $using_clip = true;
          }
        } 
      }

      if( !$using_clip ) {
        return '';
      }

      // Main loop.
      foreach( $devices as $device ) {
        //// Starting.
        $clip_consolidated = '';
     
        foreach( $clip_params_start as $classname => $param ) {    
          $clip_consolidated .= ( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0  ) ? nectar_el_percent_unit_type_class($atts[$param.'_'.$device]) . '-' : '0-';    
        }
        $start_roundness = ( isset($atts['clip_path_start_roundness']) && !empty($atts['clip_path_start_roundness']) ) ? nectar_el_percent_unit_type_class($atts['clip_path_start_roundness']) : '0';
        $classnames .= 'clip-path-start-'.$device.'-'. $clip_consolidated . $start_roundness . ' ';
        
        //// Ending.
        $clip_consolidated = '';
     
        foreach( $clip_params_end as $classname => $param ) {    
          $clip_consolidated .= ( isset($atts[$param.'_'.$device]) && strlen($atts[$param.'_'.$device]) > 0  ) ? nectar_el_percent_unit_type_class($atts[$param.'_'.$device]) . '-' : '0-';    
        }
        $end_roundness = ( isset($atts['clip_path_end_roundness']) && !empty($atts['clip_path_end_roundness']) ) ? nectar_el_percent_unit_type_class($atts['clip_path_end_roundness']) : '0';
        $classnames .= 'clip-path-end-'.$device.'-'. $clip_consolidated . $end_roundness . ' ';

      }

      return $classnames;

  }

}


/**
* Nectar mask param group classes
*
* @param array $atts - the attributes
* @return string - the mask param group classes
*
* @since 14.1
*/
if( !function_exists('nectar_mask_param_group_classes') ) {

  function nectar_mask_param_group_classes($atts) {

    $classnames = '';

    if( isset($atts['mask_enable']) && $atts['mask_enable'] == 'true' ) {
      
      // Mask Shape
      $mask_shape = (isset($atts['mask_shape']) && !empty($atts['mask_shape'])) ? $atts['mask_shape'] : 'circle';
      $classnames .= 'mask_shape_'. esc_attr($mask_shape) . ' ';
      
      // Mask Size
      if( isset($atts['mask_size']) && !empty($atts['mask_size']) ) {
        $classnames .= 'mask_size_'. esc_attr($atts['mask_size']) . ' ';
      }

      // Mask Scale
      if( isset($atts['mask_scale']) && !empty($atts['mask_scale']) ) {
        $classnames .= 'mask_scale_'. esc_attr($atts['mask_scale']) . ' ';
      }

      $devices = array('desktop','tablet','phone');

      foreach( $devices as $device ) {
     
        if (isset($atts['mask_alignment_'.$device]) && !empty($atts['mask_alignment_'.$device])) {
         
          $classnames .= 'mask_alignment_'.$device.'_'. esc_attr($atts['mask_alignment_'.$device]) . ' ';
        }
      }

      
    }

    return $classnames;
  }
}




/**
* Nectar spacing param group classes
*
* @param string $type - the type of spacing param group
* @param array $atts - the attributes
*
* @return string - the spacing param group classes
*
* @since 14.1
*/
if ( !function_exists('nectar_spacing_param_group_classes') ) {

  function nectar_spacing_param_group_classes($type, $atts) {

    $classnames = '';

    $spacing_params = array(
      'left_'.$type,
      'top_'.$type,
      'right_'.$type,
      'bottom_'.$type
    );

    foreach( $spacing_params as $param ) {

      if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {
        $classnames .= $param.'_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_desktop'])) . ' ';
      }
      if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
        $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
      }
      if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
        $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
      }

    } 

    return $classnames;

  }

}



/**
* Nectar text direction param group classes
*
* @param array $atts - the attributes
* @return string - the text direction param group classes
*
* @since 14.1
*/
if ( !function_exists('nectar_text_direction_param_group_classes') ) {

  function nectar_text_direction_param_group_classes($atts) {
      
      $classnames = '';
  
      if( isset($atts['text_direction']) && in_array($atts['text_direction'], array('ltr','rtl')) ) {
        $classnames .= 'text_direction_'. esc_attr($atts['text_direction']) . ' ';
      }
  
      return $classnames;

  }

}


/**
* Nectar font size param group classes
*
* @param array $atts - the attributes
*
* @return string - the spacing param group classes
*
* @since 14.1
*/
if (!function_exists('nectar_font_param_group_classes')) {
  function nectar_font_param_group_classes($atts, $desktop_param = 'font_size_desktop') {
    
    $classnames = '';

    $devices = array('desktop','tablet','phone');

    // Min/max font size.
    if(isset($atts['font_size_min']) && !empty($atts['font_size_min']) ) {
      $classnames .= 'font_size_min_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size_min'])) . ' ';
    }
    if(isset($atts['font_size_max']) && !empty($atts['font_size_max']) ) {
      $classnames .= 'font_size_max_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size_max'])) . ' ';
    }

    // Font sizes.
    foreach( $devices as $device ) {
      if(isset($atts['font_size_'.$device]) && !empty($atts['font_size_'.$device]) ) {
        $classnames .= 'font_size_'.$device.'_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size_'.$device])) . ' ';
      }
    }

    // Line height.
    if(isset($atts['font_line_height']) && !empty($atts['font_line_height']) ) {
      $classnames .= 'font_line_height_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_line_height'])) . ' ';
    }
    
    return $classnames;

  }
}

/**
* Nectar Dynamic Class Names.
*
* Generates dynamic classnames for dynamic page builder element styles.
*
* @see NectarElDynamicStyles
* @since 11.1
*/
 if( !function_exists('nectar_el_dynamic_classnames') ) {

	 function nectar_el_dynamic_classnames( $el, $atts ) {

		 $classnames = '';

		 if( 'row' === $el || 'inner_row' === $el ) {

			 $row_params = array(
         'top_padding',
         'bottom_padding',
         'translate_x',
         'translate_y',
         'rotate',
         'scale',
         'right_padding',
         'left_padding'
        );

			 // inner row specifc.
			 if( 'inner_row' === $el ) {

         // Pointer events.
         if( isset($atts['pointer_events']) && 'none' === $atts['pointer_events'] ) {
          $classnames .= 'no-pointer-events ';
         }

         // min width.
				 if( isset($atts['min_width_desktop']) && strlen($atts['min_width_desktop']) > 0 ) {
				   $classnames .= 'min_width_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['min_width_desktop'])) . ' ';
			   }

         // max width,
         if( isset($atts['max_width_desktop']) && strlen($atts['max_width_desktop']) > 0 ) {
          $classnames .= 'max_width_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['max_width_desktop'])) . ' ';
        }

        // positioning.
         if( isset($atts['row_position']) && 
             strlen($atts['row_position']) > 0 &&
             in_array($atts['row_position'], array('absolute','relative','fixed')) ) {
            $classnames .= 'row_position_'. esc_attr($atts['row_position']) . ' ';

          if( isset($atts['top_position_desktop']) && strlen($atts['top_position_desktop']) > 0 ) {
            $classnames .= 'top_position_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_position_desktop'])) . ' ';
          }
          if( isset($atts['bottom_position_desktop']) && strlen($atts['bottom_position_desktop']) > 0 ) {
            $classnames .= 'bottom_position_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_position_desktop'])) . ' ';
          }
          if( isset($atts['left_position_desktop']) && strlen($atts['left_position_desktop']) > 0 ) {
            $classnames .= 'left_position_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['left_position_desktop'])) . ' ';
          }
          if( isset($atts['right_position_desktop']) && strlen($atts['right_position_desktop']) > 0 ) {
            $classnames .= 'right_position_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['right_position_desktop'])) . ' ';
          }

        }


        if( isset($atts['row_position_tablet']) && 
             strlen($atts['row_position_tablet']) > 0 &&
             in_array($atts['row_position_tablet'], array('absolute','relative','fixed')) ) {
            $classnames .= 'row_position_tablet_'. esc_attr($atts['row_position_tablet']) . ' ';
        }
        if( isset($atts['row_position_phone']) && 
             strlen($atts['row_position_phone']) > 0 &&
             in_array($atts['row_position_phone'], array('absolute','relative','fixed')) ) {
            $classnames .= 'row_position_phone_'. esc_attr($atts['row_position_phone']) . ' ';
        }

 
				 $row_params[] = 'min_width';
         $row_params[] = 'max_width';
         $row_params[] = 'top_position';
         $row_params[] = 'right_position';
         $row_params[] = 'left_position';
         $row_params[] = 'bottom_position';

		   }

       // row specific.
       if( 'row' === $el ) {

         if( isset($atts['shape_divider_height_tablet']) && strlen($atts['shape_divider_height_tablet']) > 0 ) {
				   $classnames .= 'shape_divider_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts['shape_divider_height_tablet'])) . ' ';
			   }
         if( isset($atts['shape_divider_height_phone']) && strlen($atts['shape_divider_height_phone']) > 0 ) {
				   $classnames .= 'shape_divider_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts['shape_divider_height_phone'])) . ' ';
			   }

          // Custom border radius.
        if( isset($atts['row_border_radius']) && 'custom' === $atts['row_border_radius'] ) {
          if( isset($atts['top_left_border_radius']) && strlen($atts['top_left_border_radius']) > 0 ) {
            $classnames .= 'tl_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_left_border_radius'])) . ' ';
          }
          if( isset($atts['top_right_border_radius']) && strlen($atts['top_right_border_radius']) > 0 ) {
            $classnames .= 'tr_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_right_border_radius'])) . ' ';
          }
          if( isset($atts['bottom_left_border_radius']) && strlen($atts['bottom_left_border_radius']) > 0 ) {
            $classnames .= 'bl_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_left_border_radius'])) . ' ';
          }
          if( isset($atts['bottom_right_border_radius']) && strlen($atts['bottom_right_border_radius']) > 0 ) {
            $classnames .= 'br_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_right_border_radius'])) . ' ';
          }
        }

         $row_params[] = 'top_margin';
         $row_params[] = 'right_margin';
         $row_params[] = 'bottom_margin';
         $row_params[] = 'left_margin';

         if( isset($atts['top_margin']) && strlen($atts['top_margin']) > 0 ) {
          $classnames .= 'top_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_margin'])) . ' ';
        }
        if( isset($atts['right_margin']) && strlen($atts['right_margin']) > 0 ) {
          $classnames .= 'right_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['right_margin'])) . ' ';
        }
        if( isset($atts['bottom_margin']) && strlen($atts['bottom_margin']) > 0 ) {
          $classnames .= 'bottom_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_margin'])) . ' ';
        }
        if( isset($atts['left_margin']) && strlen($atts['left_margin']) > 0 ) {
          $classnames .= 'left_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['left_margin'])) . ' ';
        }
        if( isset($atts['zindex']) && strlen($atts['zindex']) > 0 ) {
          $classnames .= 'zindex-set ';
        }

        if( isset($atts['mobile_disable_bg_image_animation']) && 'true' == $atts['mobile_disable_bg_image_animation'] ) {
          $classnames .= 'mobile-disable-bg-image-animation ';
        }

        $clip_path_type = isset($atts['clip_path_animation_type']) ? $atts['clip_path_animation_type'] : '';
        if( 'scroll' !== $clip_path_type ) {
          $classnames .= nectar_clip_path_param_group_classes($atts);
          if( isset($atts['clip_path_animation_applies']) && 'row' === $atts['clip_path_animation_applies'] ) {
            $classnames .= 'clip-path-entire-row ';
          }
        }

        if( isset($atts['clip_path_animation_addon']) && 'none' != $atts['clip_path_animation_addon'] ) {
          $classnames .= 'clip-path-animation-addon-' . esc_attr($atts['clip_path_animation_addon']) . ' ';
        }
        
      }

			 // desktop specific.
			if( isset($atts['right_padding_desktop']) && strlen($atts['right_padding_desktop']) > 0 ) {
				$classnames .= 'right_padding_'. nectar_el_percent_unit_type_class(esc_attr($atts['right_padding_desktop'])) . ' ';
			}
			if( isset($atts['left_padding_desktop']) && strlen($atts['left_padding_desktop']) > 0 ) {
				$classnames .= 'left_padding_'. nectar_el_percent_unit_type_class(esc_attr($atts['left_padding_desktop'])) . ' ';
			}

      if( isset($atts['scale_desktop']) && strlen($atts['scale_desktop']) > 0 ) {
				$classnames .= 'scale_desktop_'. esc_attr(nectar_el_percent_unit_type_class($atts['scale_desktop'], '')) . ' ';
			}
			if( isset($atts['rotate_desktop']) && strlen($atts['rotate_desktop']) > 0 ) {
				$classnames .= 'rotate_desktop_'. esc_attr(nectar_el_percent_unit_type_class($atts['rotate_desktop'], 'deg')) . ' ';
			}
      if( isset($atts['translate_y']) && strlen($atts['translate_y']) > 0 ) {
				$classnames .= 'translate_y_'. esc_attr(nectar_el_percent_unit_type_class($atts['translate_y'])) . ' ';
			}
			if( isset($atts['translate_x']) && strlen($atts['translate_x']) > 0 ) {
				$classnames .= 'translate_x_'. esc_attr(nectar_el_percent_unit_type_class($atts['translate_x'])) . ' ';
      }

			// column dir.
			if( isset($atts['column_direction']) && 'reverse' === $atts['column_direction'] ) {
				$classnames .= 'reverse_columns_desktop ';
			}
			if( isset($atts['column_direction_tablet']) && 'row_reverse' === $atts['column_direction_tablet'] ) {
				$classnames .= 'reverse_columns_row_tablet ';
			} else if( isset($atts['column_direction_tablet']) && 'column_reverse' === $atts['column_direction_tablet'] ) {
				$classnames .= 'reverse_columns_column_tablet ';
			}

			if( isset($atts['column_direction_phone']) && 'row_reverse' === $atts['column_direction_phone'] ) {
				$classnames .= 'reverse_columns_row_phone ';
			} else if( isset($atts['column_direction_phone']) && 'column_reverse' === $atts['column_direction_phone'] ) {
				$classnames .= 'reverse_columns_column_phone ';
      }

      //overflow
      if( isset($atts['overflow']) && 'hidden' === $atts['overflow'] ) {
        $classnames .= 'nectar-overflow-hidden ';
      }

      // custom column gap
      if( isset($atts['column_margin_custom']) && !empty($atts['column_margin_custom']) ) {
        $classnames .= 'column-margin-'.nectar_el_decimal_unit_type_class($atts['column_margin_custom']).' ';
      }

      // z-index
      if ( isset($atts['zindex']) && $atts['zindex'] === 'auto' ) {
        $classnames .= 'z-index-auto ';
      }
      

			 // loop.
			 foreach( $row_params as $param ) {

				 if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
           $forced_unit = false;
           if( 'rotate' === $param ) {
              $forced_unit = 'deg';
           }
           if( 'scale' === $param ) {
            $forced_unit = '';
          }
					 $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet']), $forced_unit) . ' ';
				 }
				 if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
            $forced_unit = false;
            if( 'rotate' === $param ) {
              $forced_unit = 'deg';
            }
            if( 'scale' === $param ) {
              $forced_unit = '';
            }
					 $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone']), $forced_unit) . ' ';
				 }

			 }


		 } 
     
     else if ( 'column' === $el || 'inner_column' === $el ) {

			 $column_params = array(
         'top_margin',
         'bottom_margin',
         'right_margin',
         'left_margin',
         'column_padding',
         'translate_x',
         'translate_y');

			 // parent specifc.
			 if( 'column' === $el ) {

				 if( isset($atts['max_width_desktop']) && strlen($atts['max_width_desktop']) > 0 ) {
				   $classnames .= 'max_width_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts['max_width_desktop'])) . ' ';
			   }
         $column_params[] = 'max_width';
         
         if( isset($atts['column_position']) && 
             strlen($atts['column_position']) > 0 &&
             in_array($atts['column_position'], array('relative','static')) ) {
            $classnames .= 'column_position_'. esc_attr($atts['column_position']) . ' ';
        }

		   }



      $devices = array('desktop','tablet','phone');

      foreach( $devices as $device ) {

        // direction.
        if( isset($atts['column_element_direction_'.$device]) && $atts['column_element_direction_'.$device] !== 'default' ) {
          $classnames .= 'column_element_direction_'. $device . '_' .$atts['column_element_direction_'.$device] . ' ';
        }

        // Text align.
        if( isset($atts[$device.'_text_alignment']) && $atts[$device.'_text_alignment'] !== 'default' ) {
          $classnames .= 'force-'. $device . '-text-align-' .$atts[$device.'_text_alignment'] . ' ';
        }

      }
      // direction alignment.
      if( isset($atts['column_element_alignment']) && $atts['column_element_alignment'] !== 'center' ) {
        $classnames .= 'column_el_align_'.$atts['column_element_alignment']. ' ';
      }


      


			 // desktop specific.
			 if( isset($atts['right_margin']) && strlen($atts['right_margin']) > 0 ) {
				 $classnames .= 'right_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['right_margin'])) . ' ';
			 }
			 if( isset($atts['left_margin']) && strlen($atts['left_margin']) > 0 ) {
				 $classnames .= 'left_margin_'. nectar_el_percent_unit_type_class(esc_attr($atts['left_margin'])) . ' ';
			 }
       if( isset($atts['column_element_spacing']) && 'default' !== $atts['column_element_spacing'] ) {
         $classnames .= 'el_spacing_'. esc_attr($atts['column_element_spacing']) . ' ';
       }


			 // loop.
			 foreach( $column_params as $param ) {

				 if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {

					 if('column_padding' === $param) {
						 $classnames .= esc_attr($atts[$param.'_tablet']) . '_tablet ';
					 } else {
						 $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
					 }

				 }
				 if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {

					 if('column_padding' === $param) {
						$classnames .= esc_attr($atts[$param.'_phone']) . '_phone ';
					 } else {
						 $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
					 }

				 }

			 } // end column general param loop.

       //overflow
       if( isset($atts['overflow']) && 'hidden' === $atts['overflow'] ) {
        $classnames .= 'col-overflow-hidden ';
      }


       $column_border_params = array('border_left','border_top','border_right','border_bottom');
       if( isset($atts['border_type']) && 'advanced' === $atts['border_type'] ) {

         // border width.
         foreach( $column_border_params as $param ) {

           if( isset($atts[$param.'_desktop']) && strlen($atts[$param.'_desktop']) > 0 ) {
             $classnames .= $param.'_desktop_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_desktop'])) . ' ';
           }
           if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
             $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
           }
           if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
             $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
           }
         } // end column border param loop.

         // border color.
         if( isset($atts['column_border_color']) && !empty($atts['column_border_color']) ) {
  				 $border_color = ltrim($atts['column_border_color'],'#');
  				 $classnames .= 'border_color_'. esc_attr($border_color) . ' ';
  			 }

         // border style.
         if( isset($atts['column_border_radius']) && 'none' !== $atts['column_border_radius'] ) {
           //$classnames .= 'border_style_solid ';
         }
         if( isset($atts['column_border_style']) && !empty($atts['column_border_style']) ) {
  				 $classnames .= 'border_style_'. esc_attr($atts['column_border_style']) . ' ';
  			 }

       } // end using advanced border style.

       // Disable animation on mobile.
       if( isset($atts['mobile_disable_entrance_animation']) && 'true' == $atts['mobile_disable_entrance_animation'] ) {
        $classnames .= 'mobile-disable-entrance-animation ';
      }

      // Background stacking order.
      if( isset($atts['background_image_stacking']) && 'front' === $atts['background_image_stacking'] ) {
        $classnames .= 'bg_img_front ';
      }

       // Custom border radius.
       if( isset($atts['column_border_radius']) && 'custom' === $atts['column_border_radius'] ) {

        if( isset($atts['top_left_border_radius']) && strlen($atts['top_left_border_radius']) > 0 ) {
          $classnames .= 'tl_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_left_border_radius'])) . ' ';
        }
        if( isset($atts['top_right_border_radius']) && strlen($atts['top_right_border_radius']) > 0 ) {
          $classnames .= 'tr_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_right_border_radius'])) . ' ';
        }
        if( isset($atts['bottom_left_border_radius']) && strlen($atts['bottom_left_border_radius']) > 0 ) {
          $classnames .= 'bl_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_left_border_radius'])) . ' ';
        }
        if( isset($atts['bottom_right_border_radius']) && strlen($atts['bottom_right_border_radius']) > 0 ) {
          $classnames .= 'br_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_right_border_radius'])) . ' ';
        }
       }

       // Backdrop filter.
       if( isset($atts['column_backdrop_filter']) && 'none' !== $atts['column_backdrop_filter'] ) {
        $classnames .= 'backdrop_filter_'. $atts['column_backdrop_filter'] . ' ';
       }
       if( isset($atts['column_backdrop_filter_blur']) && !empty($atts['column_backdrop_filter_blur']) ) {
        $classnames .= 'backdrop_blur_'.intval($atts['column_backdrop_filter_blur']) . ' ';
       }
 
       
       // Mask Reveal background layer
       if( isset($atts['bg_image_animation']) && 'mask-reveal' === $atts['bg_image_animation'] ) {

        $classnames .= 'nectar-mask-reveal-bg ';

        if( isset($atts['bg_image_animation_mask_direction']) && !empty($atts['bg_image_animation_mask_direction']) ) {
          $classnames .= 'nectar-mask-reveal-bg-'.$atts['bg_image_animation_mask_direction'].'-dir ';
        }

        if( isset($atts['bg_image_animation_mask_shape']) && !empty($atts['bg_image_animation_mask_shape']) ) {
          $classnames .= 'nectar-mask-reveal-bg-'.$atts['bg_image_animation_mask_shape'].'-shape ';
        }

      }

      // Mask reveal column.
      if( isset($atts['animation']) && 'mask-reveal' === $atts['animation'] ) {

        $classnames .= 'nectar-mask-reveal ';

        if( isset($atts['animation_mask_direction']) && !empty($atts['animation_mask_direction']) ) {
          $classnames .= 'nectar-mask-reveal-'.$atts['animation_mask_direction'].'-dir ';
        }

        if( isset($atts['animation_mask_shape']) && !empty($atts['animation_mask_shape']) ) {
          $classnames .= 'nectar-mask-reveal-'.$atts['animation_mask_shape'].'-shape ';
        }

      }
  

       // Mask BG param
       $classnames .= nectar_mask_param_group_classes($atts);

       // Advanced padding. 
       if( isset($atts['column_padding_type']) && 'advanced' === $atts['column_padding_type'] ) {
        $classnames .= nectar_spacing_param_group_classes('padding', $atts);
       }

		 }

		 else if( 'nectar_icon' === $el ) {

			 // Custom color.
			 if( isset($atts['icon_color_custom']) && true === nectar_el_custom_color_bool('icon_color', $atts) ) {
				 $color = ltrim($atts['icon_color_custom'],'#');
				 $classnames .= 'icon_color_custom_'. esc_attr($color) . ' ';
			 }

     }
     
     else if( 'nectar_badge' === $el ) {

      // Custom color.
      if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
        $color = ltrim($atts['text_color'],'#');
        $classnames .= 'text-color-'. esc_attr($color) . ' ';
      }

      // Padding.
      if( isset($atts['padding']) && !empty($atts['padding']) ) {
        $classnames .= 'padding-amount-'. esc_attr($atts['padding']) . ' ';
      }

      // Line Width.
      if( isset($atts['line_width']) && !empty($atts['line_width']) ) {
        $classnames .= 'line-width-'. nectar_el_percent_unit_type_class(esc_attr($atts['line_width'])) . ' ';
      }

      // Style.
      if( isset($atts['badge_style']) && !empty($atts['badge_style']) ) {
        $classnames .= 'badge-style-'. esc_attr($atts['badge_style']) . ' ';
      }

      // Positioning.
       $classnames .= nectar_position_param_group_classes($atts);


    }

		 else if( 'nectar_cta' === $el ) {

			 // Custom color.
			 if( isset($atts['button_color_hover']) && !empty($atts['button_color_hover']) ) {
				 $color = ltrim($atts['button_color_hover'],'#');
				 $classnames .= 'hover_color_'. esc_attr($color) . ' ';
       }
       
       // Custom text hover color.
       if( isset($atts['text_color_hover']) && !empty($atts['text_color_hover']) ) {
        $text_hover_color = ltrim($atts['text_color_hover'],'#');
        $classnames .= 'text_hover_color_'. esc_attr($text_hover_color) . ' ';
      }
      
       // Custom border.
			 if( isset($atts['button_border_color']) && !empty($atts['button_border_color']) ) {
        $border_color = ltrim($atts['button_border_color'],'#');
        $classnames .= 'border_color_'. esc_attr($border_color) . ' ';
      }
      if( isset($atts['button_border_color_hover']) && !empty($atts['button_border_color_hover']) ) {
        $border_color_hover = ltrim($atts['button_border_color_hover'],'#');
        $classnames .= 'hover_border_color_'. esc_attr($border_color_hover) . ' ';
      }
      if( isset($atts['button_border_thickness']) && strlen($atts['button_border_thickness']) > 0 && $atts['button_border_thickness'] !== '0px') {
        $classnames .= 'border_thickness_'. esc_attr($atts['button_border_thickness']) . ' ';
      }
      
      // Custom border radius.
      if( isset($atts['border_radius']) && strlen($atts['border_radius']) > 0 && $atts['border_radius'] !== '0px') {
        $classnames .= 'border_radius_'. nectar_el_percent_unit_type_class(esc_attr($atts['border_radius'])) . ' ';
      }

       // Custom Alignment.
			 if( isset($atts['alignment_tablet']) && strlen($atts['alignment_tablet']) > 0 ) {
				 $classnames .= 'alignment_tablet_'. esc_attr($atts['alignment_tablet']) . ' ';
			 }
       if( isset($atts['alignment_phone']) && strlen($atts['alignment_phone']) > 0 ) {
				 $classnames .= 'alignment_phone_'. esc_attr($atts['alignment_phone']) . ' ';
			 }

       // Custom Display.
       if( isset($atts['display_tablet']) && strlen($atts['display_tablet']) > 0 ) {
        $classnames .= 'display_tablet_'. esc_attr($atts['display_tablet']) . ' ';
      }
      if( isset($atts['display_phone']) && strlen($atts['display_phone']) > 0 ) {
        $classnames .= 'display_phone_'. esc_attr($atts['display_phone']) . ' ';
      }

      // Icon
      if( isset($atts['icon_family']) && $atts['icon_family'] !== 'none' ) {
        $classnames .= 'has-icon ';
      }

      // Custom font size.
      if( isset($atts['font_size_desktop']) ) {
        $classnames .= 'font_size_desktop_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size_desktop'])) . ' ';
      }
      if( isset($atts['font_size_tablet']) ) {
        $classnames .= 'font_size_tablet_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size_tablet'])) . ' ';
      }
      if( isset($atts['font_size_phone']) ) {
        $classnames .= 'font_size_phone_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size_phone'])) . ' ';
      }

       // Positioning.
       $classnames .= nectar_position_param_group_classes($atts);

		 }



		 else if( 'nectar_highlighted_text' === $el ) {

			 // Custom font size.
			 if( isset($atts['custom_font_size']) ) {
				 $classnames .= 'font_size_'. esc_attr($atts['custom_font_size']) . ' ';
			 }

       $classnames .= nectar_font_param_group_classes($atts);

			 if( isset($atts['custom_font_size_mobile']) ) {
				 $classnames .= 'font_size_mobile_'. esc_attr($atts['custom_font_size_mobile']) . ' ';
			 }

       // Remove mobile animation.
       if( isset($atts['disable_mobile_animation']) && 'true' === $atts['disable_mobile_animation'] ) {
        $classnames .= 'nectar-disable-mobile-animation ';
       }

       if( isset($atts['scribble_easing']) && in_array($atts['scribble_easing'], array('ease_out')) ) {
        $classnames .= $atts['scribble_easing'] . ' ';
       }

      // Text direction.
      $classnames .= nectar_text_direction_param_group_classes($atts);

		 }

     else if( 'nectar_scrolling_text' === $el ) {

      // Custom font size.
      if( isset($atts['custom_font_size']) ) {
        $classnames .= 'font_size_'. esc_attr($atts['custom_font_size']) . ' ';
      }
      if( isset($atts['custom_font_size_mobile']) ) {
        $classnames .= 'font_size_mobile_'. esc_attr($atts['custom_font_size_mobile']) . ' ';
      }

      // Custom color.
      if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
        $classnames .= 'custom_color ';
      }

      //Text Space.
      if( isset($atts['text_space_amount']) && !empty($atts['text_space_amount']) && 'default' !== $atts['text_space_amount'] ) {
        $classnames .= 'text_space_'. esc_attr($atts['text_space_amount']) . ' ';
      }

      // custom icon
      if( isset($atts['text_repeat_divider']) && $atts['text_repeat_divider'] === 'custom' ) {
        $classnames .= 'has-custom-divider ';
      }

    }


     else if( 'nectar_rotating_words_title' === $el ) {

      // Custom font size.
      if( isset($atts['font_size']) ) {
        $classnames .= 'font_size_'. esc_attr($atts['font_size']) . ' ';
      }
      // Custom color.
      if( isset($atts['text_color']) && !empty($atts['text_color']) ) {
        $color = ltrim($atts['text_color'],'#');
        $classnames .= 'color_'. esc_attr($color) . ' ';
      }
      // Animation.
      if( isset($atts['element_animation']) && 'none' !== $atts['element_animation'] ) {
        $classnames .= 'element_'. esc_attr($atts['element_animation']) . ' ';
      }

      // Text direction.
      $classnames .= nectar_text_direction_param_group_classes($atts);

    }

		 else if( 'divider' === $el ) {

			 // Custom Height.
			 if( isset($atts['custom_height_tablet']) && strlen($atts['custom_height_tablet']) > 0 ) {
				 $classnames .= 'height_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts['custom_height_tablet'])) . ' ';
			 }
			 if( isset($atts['custom_height_phone']) && strlen($atts['custom_height_phone']) > 0 ) {
				 $classnames .= 'height_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts['custom_height_phone'])) . ' ';
			 }

     } 

     // Sticky Media Sections.
     else if ( 'nectar_sticky_media_sections' === $el ) {
    
      if( isset($atts['media_width']) ) {
        $classnames .= 'media-width-'.nectar_el_percent_unit_type_class(esc_attr($atts['media_width'])). ' ';
      }

      if( isset($atts['media_height']) ) {
        $classnames .= 'media-height-'.nectar_el_percent_unit_type_class(esc_attr($atts['media_height'])). ' ';
      }

      if( isset($atts['content_spacing']) ) {
        $classnames .= 'content-spacing-'.nectar_el_percent_unit_type_class(esc_attr($atts['content_spacing'])). ' ';
      }

      if( isset($atts['content_position']) ) {
        $classnames .= 'content-position-'.esc_attr($atts['content_position']). ' ';
      }

      if( isset($atts['mobile_aspect_ratio']) ) {
        $classnames .= 'mobile-aspect-ratio-'.esc_attr($atts['mobile_aspect_ratio']). ' ';
      }

      if( isset($atts['border_radius']) && 'none' !== $atts['border_radius'] ) {
        $classnames .= 'media-boder-radius-'.$atts['border_radius'].' ';
      }

     }

     else if ( 'vs_tabbed' === $el ) {

      //Vertical Sticky Scrolling.
      if( isset($atts['vs_navigation_func']) && 'default' != $atts['vs_navigation_func'] ) {
        $classnames .= 'navigation_func_'.esc_attr($atts['vs_navigation_func']). ' ';
      }
      if( isset($atts['vs_font_size']) && strlen($atts['vs_font_size']) > 0 ) {
        $classnames .= 'font_size_'. nectar_el_decimal_unit_type_class(esc_attr($atts['vs_font_size'])) . ' ';
      }
      if( isset($atts['vs_sub_desc_font_size']) && strlen($atts['vs_sub_desc_font_size']) > 0 ) {
        $classnames .= 'sub_desc_font_size_'. nectar_el_decimal_unit_type_class(esc_attr($atts['vs_sub_desc_font_size'])) . ' ';
      }

     }

     else if ( 'tabbed_section' === $el ) {

      //Vertical Sticky Scrolling Alt.
      if( isset($atts['vs_content_animation']) ) {
        $classnames .= 'content_animation_'.$atts['vs_content_animation']. ' ';
      }
      if( isset($atts['vs_link_animation']) ) {
        $classnames .= 'link_animation_'.$atts['vs_link_animation']. ' ';

        if( 'underline' === $atts['vs_link_animation'] && isset($atts['vs_link_underline_distance']) && 
            'default' !== $atts['vs_link_underline_distance'] ) {
            $classnames .= 'underline_distance_'.esc_attr($atts['vs_link_underline_distance']). ' ';
        }
      }
      if( isset($atts['vs_font_size']) && strlen($atts['vs_font_size']) > 0 ) {
        $classnames .= 'font_size_'. nectar_el_decimal_unit_type_class(esc_attr($atts['vs_font_size'])) . ' ';
      }

      if( isset($atts['vs_sub_desc_font_size']) && strlen($atts['vs_sub_desc_font_size']) > 0 ) {
        $classnames .= 'sub_desc_font_size_'. nectar_el_decimal_unit_type_class(esc_attr($atts['vs_sub_desc_font_size'])) . ' ';
      }

      if( isset($atts['vs_tab_link_spacing']) && strlen($atts['vs_tab_link_spacing']) > 0 ) {
        $classnames .= 'tab_link_spacing_'. nectar_el_percent_unit_type_class(esc_attr($atts['vs_tab_link_spacing'])) . ' ';
      }

      
     }


     else if( 'nectar_circle_images' === $el ) {

      $devices = array('desktop','tablet','phone');

      if( isset($atts['positioning']) && !empty($atts['positioning']) ) {
        $classnames .= 'nectar-circle-images--position_'.esc_attr($atts['positioning']). ' ';
      }
      if( isset($atts['animation']) && !empty($atts['animation']) && $atts['animation'] !== 'none' ) {
        $classnames .= 'entrance_animation_'.esc_attr($atts['animation']) . ' ';
      }
      if( isset($atts['sizing']) && strlen($atts['sizing']) > 0 ) {
        $classnames .= 'size_'. nectar_el_percent_unit_type_class(esc_attr($atts['sizing'])) . ' ';
      } else {
        $classnames .= 'size_50px ';
      }

      // Alignment.
      foreach( $devices as $device ) {

        if( isset($atts['alignment_'.$device]) && $atts['alignment_'.$device] != 'inherit' ) {
          $classnames .= 'alignment_'. esc_attr($atts['alignment_'.$device]) . '_' . $device . ' ';
        }
      }
    

      if( isset($atts['border_color']) ) {
        $color = ltrim($atts['border_color'],'#');
        $classnames .= 'border_color_'. esc_attr($color) . ' ';
      }

     }


     else if( 'nectar_text_inline_images' === $el ) {

      // Custom Font Size.
      if( isset($atts['font_size_desktop']) && strlen($atts['font_size_desktop']) > 0 ) {
        $classnames .= 'font_size_desktop_'. nectar_el_decimal_unit_type_class(esc_attr($atts['font_size_desktop'])) . ' ';
      }
      if( isset($atts['font_size_tablet']) && strlen($atts['font_size_tablet']) > 0 ) {
        $classnames .= 'font_size_tablet_'. nectar_el_decimal_unit_type_class(esc_attr($atts['font_size_tablet'])) . ' ';
      }
      if( isset($atts['font_size_phone']) && strlen($atts['font_size_phone']) > 0 ) {
        $classnames .= 'font_size_phone_'. nectar_el_decimal_unit_type_class(esc_attr($atts['font_size_phone'])) . ' ';
      }

      // Animation.
      if( isset($atts['image_effect']) && !empty($atts['image_effect']) ) {
        $classnames .= 'nectar-text-inline-images--animation_'.esc_attr($atts['image_effect']). ' ';
      }
      if( isset($atts['image_effect_rm_mobile']) && 'yes' === $atts['image_effect_rm_mobile'] ) {
        $classnames .= 'nectar-text-inline-images--rm-mobile-animation ';
      }

      if( isset($atts['image_effect_stagger']) && 'yes' === $atts['image_effect_stagger'] ) {
        $classnames .= 'nectar-text-inline-images--stagger-animation ';
      }

      

      // Line height.
      if( isset($atts['font_line_height']) && !empty($atts['font_line_height']) ) {
        $classnames .= 'font_line_height_'.esc_attr(nectar_el_decimal_unit_type_class($atts['font_line_height'])). ' ';
      }

      // Text color.
			 if( isset($atts['text_color']) ) {
				 $color = ltrim($atts['text_color'],'#');
				 $classnames .= 'text_color_'. esc_attr($color) . ' ';
			 }

       // Margins.
       $classnames .= nectar_spacing_param_group_classes('margin', $atts);

       // Text direction.
      $classnames .= nectar_text_direction_param_group_classes($atts);
      
     }

     else if( 'split_line_heading' === $el ) {

      // Custom Font Size.
      $using_fit_text = isset($atts['fit_text_to_container']) && 'true' === $atts['fit_text_to_container'] ? true : false;

      if( !$using_fit_text ) {
        if( isset($atts['font_size']) ) {
          $classnames .= 'font_size_'. esc_attr(nectar_el_decimal_unit_type_class($atts['font_size'])). ' ';
        }
        $classnames .= nectar_font_param_group_classes($atts);
      }

      // Text direction.
      $classnames .= nectar_text_direction_param_group_classes($atts);

    }


		 else if( 'simple_slider' === $el ) {

       // Pagination Coloring.
       if( isset($atts['simple_slider_pagination_coloring']) && 'default' !== $atts['simple_slider_pagination_coloring'] ) {
				$classnames .= 'pagination-color-'.esc_attr($atts['simple_slider_pagination_coloring']).' ';
			 }

			 // Arrows.
			 if( isset($atts['simple_slider_arrow_positioning']) && 'overlapping' === $atts['simple_slider_arrow_positioning'] ) {
				$classnames .= 'arrow-position-overlapping ';
			 }
			 if( isset($atts['simple_slider_arrow_button_color']) && !empty($atts['simple_slider_arrow_button_color']) ) {
				 $color = ltrim($atts['simple_slider_arrow_button_color'],'#');
				 $classnames .= 'arrow-btn-'. esc_attr($color) . ' ';
			 }
			 if( isset($atts['simple_slider_arrow_button_border_color']) && !empty($atts['simple_slider_arrow_button_border_color']) ) {
				 $color = ltrim($atts['simple_slider_arrow_button_border_color'],'#');
				 $classnames .= 'arrow-btn-border-'. esc_attr($color) . ' ';
			 }
			 if( isset($atts['simple_slider_arrow_color']) && !empty($atts['simple_slider_arrow_color']) ) {
				 $color = ltrim($atts['simple_slider_arrow_color'],'#');
				 $classnames .= 'arrow-'. esc_attr($color) . ' ';
			 }

			 // Slider Sizing.
			 $sizing_type = 'aspect_ratio';

			 if( isset($atts['simple_slider_sizing']) && 'percentage' === $atts['simple_slider_sizing'] ) {
				$sizing_type = 'percentage';
			 }

			 if( 'aspect_ratio' === $sizing_type ) {
				 	$aspect = ( isset($atts['simple_slider_aspect_ratio']) ) ? esc_attr($atts['simple_slider_aspect_ratio']) : '1-1';

					$classnames .= 'sizing-aspect-ratio ';
					$classnames .= 'aspect-'. $aspect . ' ';
			 } else {

				 $height_percent = ( isset($atts['simple_slider_height']) ) ? esc_attr($atts['simple_slider_height']) : '50vh';

				 $classnames .= 'sizing-percentage ';
				 $classnames .= 'height-'. $height_percent . ' ';
			 }

       // Minimum Height.
       if( isset($atts['simple_slider_min_height']) && !empty($atts['simple_slider_min_height']) ) {
         $classnames .= 'min-height-'. nectar_el_percent_unit_type_class(esc_attr($atts['simple_slider_min_height'])) . ' ';
       }

		 }

     else if( 'carousel' === $el ) {

       // Flickity.
       if( isset($atts['script']) && 'flickity' === $atts['script'] ) {

         // Top/bottom margin.
         if( isset($atts['flickity_element_spacing']) && 'default' !== $atts['flickity_element_spacing'] ) {
           $classnames .= 'tb-spacing-'. esc_attr($atts['flickity_element_spacing']) . ' ';
         }

         // Column vertical alignment.
         if( isset($atts['flickity_column_vertical_alignment']) && 'default' !== $atts['flickity_column_vertical_alignment'] ) {
          $classnames .= 'vertical-alignment-'. esc_attr($atts['flickity_column_vertical_alignment']) . ' ';
        }

        // Custom padding.
        $custom_padding = (isset($atts['column_padding_custom']) && !empty($atts['column_padding_custom'])) ? $atts['column_padding_custom'] : false;

        if( isset($atts['column_padding']) && 'custom' === $atts['column_padding'] && $custom_padding ) {
          $classnames .= 'custom-column-padding-'. esc_attr(nectar_el_percent_unit_type_class($custom_padding)) . ' ';
        }

        // Autorotation type.
        if( isset($atts['autorotate_type']) && 'ticker' === $atts['autorotate_type'] ) {
          $classnames .= 'ticker-rotate ';
        }


			 }

     }

     else if( 'flickity_carousel_slide' === $el ) {
      // Custom Width
			 if( isset($atts['flickity_custom_item_width']) && !empty($atts['flickity_custom_item_width']) ) {
        $classnames .= 'custom-desktop-width-'. nectar_el_percent_unit_type_class(esc_attr($atts['flickity_custom_item_width'])) . ' ';
      }
       // Hide On Mobile
			 if( isset($atts['flickity_hide_on_mobile']) && !empty($atts['flickity_hide_on_mobile']) ) {
        $classnames .= 'vc_hidden-sm vc_hidden-xs ';
      }
     }

		 else if( 'simple_slider_slide' === $el ) {

			 // Color Overlay.
			 if( isset($atts['simple_slider_enable_gradient']) && 'true' === $atts['simple_slider_enable_gradient'] ) {
				 $classnames .= 'color-overlay-gradient ';
			 }
			 if( isset($atts['simple_slider_color_overlay']) && !empty($atts['simple_slider_color_overlay']) ) {
				 $color = ltrim($atts['simple_slider_color_overlay'],'#');
				 $classnames .= 'color-overlay-1-'. esc_attr($color) . ' ';
			 }
			 if( isset($atts['simple_slider_color_overlay_2']) && !empty($atts['simple_slider_color_overlay_2']) ) {
				 $color = ltrim($atts['simple_slider_color_overlay_2'],'#');
				 $classnames .= 'color-overlay-2-'. esc_attr($color) . ' ';
			 }

			 // Text Color.
			 if( isset($atts['simple_slider_font_color']) && !empty($atts['simple_slider_font_color']) ) {
				 $color = ltrim($atts['simple_slider_font_color'],'#');
				 $classnames .= 'text-color-'. esc_attr($color) . ' ';
			 }

			 // BG Image Pos.
			 if( isset($atts['simple_slider_bg_image_position']) && !empty($atts['simple_slider_bg_image_position']) ) {
				 $classnames .= 'bg-pos-'. esc_attr($atts['simple_slider_bg_image_position']) . ' ';
			 }

		 }


		 else if( 'nectar_post_grid' === $el ) {

      $post_grid_style = ( isset($atts['grid_style']) && !empty($atts['grid_style']) ) ? $atts['grid_style'] : '';

			 // Custom font size.
			 if( isset($atts['custom_font_size']) ) {
				 $classnames .= 'custom_font_size_'. esc_attr(nectar_el_decimal_unit_type_class($atts['custom_font_size'])). ' ';
			 }
       $classnames .= nectar_font_param_group_classes($atts);

			 // Hover color.
			 if( isset($atts['card_bg_color_hover']) ) {
				 $color = ltrim($atts['card_bg_color_hover'],'#');
				 $classnames .= 'card_hover_color_'. esc_attr($color) . ' ';
			 }
       // Text Opacity.
       if( isset($atts['text_opacity']) && !empty($atts['text_opacity']) ) {
         $text_opacity_selector = str_replace('.', '-', $atts['text_opacity']);
         $classnames .= 'text-opacity-'. esc_attr($text_opacity_selector) . ' ';
       }
       if( isset($atts['text_hover_opacity']) && !empty($atts['text_hover_opacity']) ) {
         $text_opacity_selector = str_replace('.', '-', $atts['text_hover_opacity']);
         $classnames .= 'text-opacity-hover-'. esc_attr($text_opacity_selector) . ' ';
       }

       // Custom Aspect Ratio.
       $post_grid_custom_ar = ( isset($atts['custom_image_aspect_ratio']) && in_array($atts['custom_image_aspect_ratio'], array('1-1','16-9','3-2','4-3','4-5')) ) ? $atts['custom_image_aspect_ratio'] : false;
       if( $post_grid_custom_ar ) {
        $classnames .= 'custom-aspect-ratio-'. esc_attr($post_grid_custom_ar) . ' ';
       }

       // Excerpt Display.
       if (isset($atts['excerpt_display']) && 'default' !== $atts['excerpt_display']) {
         $classnames .= 'excerpt-'. esc_attr($atts['excerpt_display']) . ' ';
       }

       // Masonry Layout Variants.
       if( isset($atts['enable_masonry']) && 'yes' === $atts['enable_masonry']) {
          if( isset($atts['columns']) && '4' === $atts['columns'] && 
              isset($atts['4_col_masonry_layout']) && 'default' !== $atts['4_col_masonry_layout']) {
              $classnames .= 'layout-'. esc_attr($atts['4_col_masonry_layout']) . ' ';
          }
          if( isset($atts['columns']) && '3' === $atts['columns'] && 
              isset($atts['3_col_masonry_layout']) && 'default' !== $atts['3_col_masonry_layout']) {
              $classnames .= 'layout-'. esc_attr($atts['3_col_masonry_layout']) . ' ';
          }
          if( isset($atts['columns']) && '2' === $atts['columns'] && 
              isset($atts['2_col_masonry_layout']) && 'default' !== $atts['2_col_masonry_layout']) {
              $classnames .= 'layout-'. esc_attr($atts['2_col_masonry_layout']) . ' ';
          }
        }


       if( isset($atts['featured_top_item']) && 'yes' === $atts['featured_top_item'] ) {
        $classnames .= 'featured-first-item ';
       }

    
      
       // Secondary project image.
       if( isset($atts['overlay_secondary_project_image']) && 'yes' === $atts['overlay_secondary_project_image'] ) {
         if( isset($atts['overlay_secondary_project_image_align']) && !empty($atts['overlay_secondary_project_image_align']) ) {
          $classnames .= 'overlay-secondary-project-image-'.esc_attr($atts['overlay_secondary_project_image_align']). ' ';
         }
        
       }

       
       // Category pos.
       if( isset($atts['category_position']) && 'below_title' === $atts['category_position'] ) {
        $classnames .= 'category-position-after-title ';
       } else if ( in_array($post_grid_style, array('content_under_image')) && 
                  isset($atts['category_position']) && 
                  'overlaid' === $atts['category_position'] ) {
                $classnames .= 'category-position-overlaid ';
       } else {
        $classnames .= 'category-position-before-title ';
       }

       // Additional meta.
       if( isset($atts['additional_meta_display']) && 'default' !== $atts['additional_meta_display'] ) {
        $classnames .= 'meta-display-'.esc_attr($atts['additional_meta_display']). ' ';
       }

       // Vertical list.
       if( isset($atts['vertical_list_custom_text_color']) ) {
        $color = ltrim($atts['vertical_list_custom_text_color'],'#');
        $classnames .= 'vert_list_custom_text_color_'. esc_attr($color) . ' ';
      }

       if( isset($atts['vertical_list_bg_color_hover']) ) {
         $color = ltrim($atts['vertical_list_bg_color_hover'],'#');
				 $classnames .= 'vert_list_bg_hover_'. esc_attr($color) . ' ';
       }
       if( isset($atts['vertical_list_text_color_hover']) ) {
        $color = ltrim($atts['vertical_list_text_color_hover'],'#');
        $classnames .= 'vert_list_text_hover_'. esc_attr($color) . ' ';
      }
      if( isset($atts['vertical_list_hover_effect']) && 'none' !== $atts['vertical_list_hover_effect'] ) {
        $classnames .= 'vert_list_hover_effect_'. esc_attr($atts['vertical_list_hover_effect']) . ' ';
      }
      if( isset($atts['vertical_list_color_overlay_opacity']) ) {
        $classnames .= 'vert_list_overlay_opacity_'. esc_attr($atts['vertical_list_color_overlay_opacity']) . ' ';
      }
      if( isset($atts['vertical_list_counter']) && 'yes' === $atts['vertical_list_counter'] ) {
        $classnames .= 'vert_list_counter ';
      }

      // Image next to content.
       if( isset($atts['content_next_to_image_divider']) && 
        $atts['content_next_to_image_divider'] === 'yes' && 
        isset($atts['content_next_to_image_divider_color']) ) {
        $color = ltrim($atts['content_next_to_image_divider_color'],'#');
        $classnames .= 'content_next_to_image_divider_'. esc_attr($color) . ' ';
      }

      // Custom padding
      $devices = array('desktop','tablet','phone');
      foreach( $devices as $device ) {
        if( isset($atts['content_overlaid_padding_'.$device]) && !empty($atts['content_overlaid_padding_'.$device]) ) {
          $classnames .= $device.'_padding_'.nectar_el_percent_unit_type_class($atts['content_overlaid_padding_'.$device]).' ';
        }
      }
      

      // Post grid flickity carousel
      if ( isset($atts['display_type']) && 'carousel' === $atts['display_type'] ) {
        
        if( isset($atts['desktop_small_cols_flickity']) && 'default' !== $atts['desktop_small_cols_flickity'] ) {
          $classnames .= 'sm-desktop-cols-'. esc_attr($atts['desktop_small_cols_flickity']) . ' ';
        }
        if( isset($atts['tablet_cols_flickity']) && 'default' !== $atts['tablet_cols_flickity'] ) {
          $classnames .= 'tablet-cols-'. esc_attr($atts['tablet_cols_flickity']) . ' ';
        }
        if( isset($atts['phone_cols_flickity']) && 'default' !== $atts['phone_cols_flickity'] ) {
          $classnames .= 'phone-cols-'. esc_attr($atts['phone_cols_flickity']) . ' ';
        }

      } else {

        // Mobile columns -- non carousel.
        if ( isset($atts['columns_tablet']) && 'default' !== $atts['columns_tablet'] ) {
          $classnames .= 'tablet-cols-'. esc_attr($atts['columns_tablet']) . ' ';
        }
        if ( isset($atts['columns_phone']) && 'default' !== $atts['columns_phone'] ) {
          $classnames .= 'phone-cols-'. esc_attr($atts['columns_phone']) . ' ';
        }
      }

		 }

		 else if( 'nectar_fancy_box' === $el ) {

			 // Min height.
			 if( isset($atts['min_height_tablet']) && strlen($atts['min_height_tablet']) > 0 ) {
				 $classnames .= 'min_height_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts['min_height_tablet'])) . ' ';
			 }
			 if( isset($atts['min_height_phone']) && strlen($atts['min_height_phone']) > 0 ) {
				 $classnames .= 'min_height_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts['min_height_phone'])) . ' ';
			 }

       // Image Above Text.
       if( isset($atts['box_style']) && 'image_above_text_underline' === $atts['box_style'] ) {

         // Text Color.
        if( isset($atts['content_color']) && !empty($atts['content_color']) ) {
          $color = ltrim($atts['content_color'],'#');
          $classnames .= 'content-color-'. esc_attr($color) . ' ';
        }

        // Aspect Ratio.
        $aspect = ( isset($atts['image_aspect_ratio']) ) ? esc_attr($atts['image_aspect_ratio']) : '1-1';
        $classnames .= 'aspect-'. $aspect . ' ';

       }

			 // Parallax hover.
			 if( isset($atts['parallax_hover_box_overlay']) && !empty($atts['parallax_hover_box_overlay']) ) {

				 $color = ltrim($atts['parallax_hover_box_overlay'],'#');

				 $classnames .= 'overlay_'. esc_attr($color) . ' ';
			 }

       // Hover description.
       if( isset($atts['hover_desc_hover_overlay_opacity']) && 'default' !== $atts['hover_desc_hover_overlay_opacity'] ) {
        $classnames .= 'hover_o_opacity_'. esc_attr(str_replace('.', '-', $atts['hover_desc_hover_overlay_opacity'])) . ' ';
       }
       if( isset($atts['hover_desc_color_opacity']) && 'default' !== $atts['hover_desc_color_opacity'] ) {
        $classnames .= 'o_opacity_'. esc_attr(str_replace('.', '-', $atts['hover_desc_color_opacity'])) . ' ';
       }

			 if( isset($atts['hover_color_custom']) && !empty($atts['hover_color_custom']) ) {

				 $color = ltrim($atts['hover_color_custom'],'#');

				 $classnames .= 'hover_color_'. esc_attr($color) . ' ';

         if( isset($atts['icon_position']) && 'bottom' !== $atts['icon_position'] ) {
          $classnames .= 'icon_position_'. esc_attr($atts['icon_position']) . ' ';
         }
			 }

       if( isset($atts['disable_hover_movement']) && 'true' === $atts['disable_hover_movement'] ) {
        $classnames .= 'disable-hover-movement ';
       }

			 // Hover description.
			 if( isset($atts['color_custom']) && !empty($atts['color_custom']) ) {

				 $color = ltrim($atts['color_custom'],'#');

				 $classnames .= 'box_color_'. esc_attr($color) . ' ';
			 }

		 }

		 else if ( 'image_with_animation' === $el ) {

			 $image_params = array('margin_top','margin_right','margin_bottom','margin_left');

			 foreach( $image_params as $param ) {

				 if( isset($atts[$param.'_tablet']) && strlen($atts[$param.'_tablet']) > 0 ) {
					 $classnames .= $param.'_tablet_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_tablet'])) . ' ';
				 }
				 if( isset($atts[$param.'_phone']) && strlen($atts[$param.'_phone']) > 0 ) {
					 $classnames .= $param.'_phone_'. nectar_el_percent_unit_type_class(esc_attr($atts[$param.'_phone'])) . ' ';
				 }

			 }

       // custom max width.
       if( isset($atts['max_width']) && 'custom' === $atts['max_width'] ) {

        if( isset($atts['max_width_custom']) && !empty( $atts['max_width_custom'] ) ) {
          $classnames .= 'custom-width-'. esc_attr( nectar_el_percent_unit_type_class($atts['max_width_custom']) ) . ' ';
        }

        if( isset($atts['max_width_custom_tablet']) && !empty( $atts['max_width_custom_tablet'] ) ) {
          $classnames .= 'custom-width-tablet-'. esc_attr( nectar_el_percent_unit_type_class($atts['max_width_custom_tablet']) ) . ' ';
        }

        if( isset($atts['max_width_custom_phone']) && !empty( $atts['max_width_custom_phone'] ) ) {
          $classnames .= 'custom-width-phone-'. esc_attr( nectar_el_percent_unit_type_class($atts['max_width_custom_phone']) ) . ' ';
        }
         
       }


      // Custom border radius.
      if( isset($atts['border_radius']) && 'custom' === $atts['border_radius'] ) {

        if( isset($atts['top_left_border_radius']) && strlen($atts['top_left_border_radius']) > 0 ) {
          $classnames .= 'tl_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_left_border_radius'])) . ' ';
        }
        if( isset($atts['top_right_border_radius']) && strlen($atts['top_right_border_radius']) > 0 ) {
          $classnames .= 'tr_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['top_right_border_radius'])) . ' ';
        }
        if( isset($atts['bottom_left_border_radius']) && strlen($atts['bottom_left_border_radius']) > 0 ) {
          $classnames .= 'bl_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_left_border_radius'])) . ' ';
        }
        if( isset($atts['bottom_right_border_radius']) && strlen($atts['bottom_right_border_radius']) > 0 ) {
          $classnames .= 'br_br_'. nectar_el_percent_unit_type_class(esc_attr($atts['bottom_right_border_radius'])) . ' ';
        }
        }
       
       // Remove mobile animation.
       if( isset($atts['disable_mobile_animation']) && 'true' === $atts['disable_mobile_animation'] ) {
        $classnames .= 'nectar-disable-mobile-animation ';
       }

       // Overflow.
       // Overflow.
       if( isset($atts['overflow']) && 'hidden' === $atts['overflow'] ) {
        $classnames .= 'nectar-overflow-hidden ';
        }

       // looped animation.
       if( isset($atts['animation_type']) && 'looped' === $atts['animation_type'] ) {
        $looped_animation = ( isset($atts['loop_animation']) ) ? $atts['loop_animation'] : '';
        if( !empty($looped_animation) && $looped_animation != 'none' ) {
          $classnames .= 'looped-animation-' . $looped_animation . ' ';
        }
       }


       // mask group.
       $classnames .= nectar_mask_param_group_classes($atts);

       // Image Positioning.
       $classnames .= nectar_position_param_group_classes($atts);


		 }

     else if( 'nectar_animated_shape' === $el ) {

      $devices = array('desktop','tablet','phone');

      // width and height
      foreach( $devices as $device ) {
        if (isset($atts['width_'.$device]) && !empty($atts['width_'.$device])) {
          $classnames .= 'width_'.$device.'_'. esc_attr(nectar_el_percent_unit_type_class($atts['width_'.$device])) . ' ';
        }
        if (isset($atts['height_'.$device]) && !empty($atts['height_'.$device])) {
          $classnames .= 'height_'.$device.'_'. esc_attr(nectar_el_percent_unit_type_class($atts['height_'.$device])) . ' ';
        }
      }
      
      // Positioning.
      $classnames .= nectar_position_param_group_classes($atts);

    

     }

     else if ( 'nectar_cascading_images' === $el ) {

       // Sizing.
       $aspect = ( isset($atts['element_sizing_aspect']) ) ? esc_attr($atts['element_sizing_aspect']) : '1-1';

       if( isset($atts['element_sizing']) && 'forced' === $atts['element_sizing'] ) {
         $classnames .= 'forced-aspect aspect-'. $aspect . ' ';
       }

       // Overflow.
       if( isset($atts['overflow']) && 'hidden' === $atts['overflow'] ) {
         $classnames .= 'overflow-hidden ';
       }

		 } 

     else if( 'nectar_video_player_self_hosted' === $el ) {

      if( isset($atts['el_aspect_tablet']) && 'inherit' != $atts['el_aspect_tablet'] ) {
        $classnames .= 'tablet-aspect-' . esc_attr($atts['el_aspect_tablet']) . ' ';
       }

       if( isset($atts['el_aspect_phone']) && 'inherit' != $atts['el_aspect_phone'] ) {
        $classnames .= 'phone-aspect-' . esc_attr($atts['el_aspect_phone']) . ' ';
       }

       if( isset($atts['play_button_hide']) && 'yes' == $atts['play_button_hide'] ) {
        $classnames .= 'play_button_hover ';
       }
      
     }

     else if( 'testimonial_slider' === $el ) {

       // Custom Font Size.
       if( isset($atts['font_size_desktop']) && strlen($atts['font_size_desktop']) > 0 ) {
          $classnames .= 'font_size_desktop_'. nectar_el_decimal_unit_type_class(esc_attr($atts['font_size_desktop'])) . ' ';
       }
       if( isset($atts['font_size_tablet']) && strlen($atts['font_size_tablet']) > 0 ) {
          $classnames .= 'font_size_tablet_'. nectar_el_decimal_unit_type_class(esc_attr($atts['font_size_tablet'])) . ' ';
        }
        if( isset($atts['font_size_phone']) && strlen($atts['font_size_phone']) > 0 ) {
          $classnames .= 'font_size_phone_'. nectar_el_decimal_unit_type_class(esc_attr($atts['font_size_phone'])) . ' ';
        }

        // Line height.
        if( isset($atts['font_line_height']) && !empty($atts['font_line_height']) ) {
          $classnames .= 'font_line_height_'.esc_attr(nectar_el_decimal_unit_type_class($atts['font_line_height'])). ' ';
        }

        // custom wdith
        if( isset($atts['custom_width_desktop']) && !empty($atts['custom_width_desktop']) ) {
          $classnames .= 'desktop-width-'.esc_attr(nectar_el_percent_unit_type_class($atts['custom_width_desktop'])) .' ';
        }

        // border-radius
        if( isset($atts['flickity_border_radius']) && 'default' !== $atts['flickity_border_radius'] ) {
          $classnames .= 'border-radius-'.esc_attr($atts['flickity_border_radius']).' ';
        }

     }


     else if( 'nectar_lottie' === $el ) {

      $devices = array('desktop','tablet','phone');
      
      // width and height
      foreach( $devices as $device ) {
        if (isset($atts['width_'.$device]) && !empty($atts['width_'.$device])) {
          $classnames .= 'width_'.$device.'_'. esc_attr(nectar_el_percent_unit_type_class($atts['width_'.$device])) . ' ';
        }
        if (isset($atts['height_'.$device]) && !empty($atts['height_'.$device])) {
          $classnames .= 'height_'.$device.'_'. esc_attr(nectar_el_percent_unit_type_class($atts['height_'.$device])) . ' ';
        }
      }

      // alignment.
      if (isset($atts['alignment']) && !empty($atts['alignment'])) {
        $classnames .= 'alignment_'. esc_attr($atts['alignment']) . ' ';
      }

      // Mobile func.
      if (isset($atts['mobile_func']) && 'default' !== $atts['mobile_func'] ) {
        $classnames .= 'mobile_'. esc_attr($atts['mobile_func']) . ' ';
      }

      // Positioning.
      $classnames .= nectar_position_param_group_classes($atts);

     }

     else if( 'nectar-fancy-ul' === $el ) {
      // Custom font size.
      $classnames .= nectar_font_param_group_classes($atts);

      // Link style.
      if (isset($atts['link_style']) && 'default' !== $atts['link_style'] ) {
        $classnames .= 'link_style_'. esc_attr($atts['link_style']) . ' ';

        if( $atts['link_style'] === 'underline' ) {
          $classnames .= 'nectar-link-underline-effect ';
        }


      }

     }

     else if ( 'recent_posts' === $el ) {

      // Custom font size.
      if (isset($atts['style']) && in_array($atts['style'], array('multiple_large_featured', 'single_large_featured')) ) {
        $classnames .= ' '. nectar_font_param_group_classes($atts);
      }

     }

     else if ( 'nectar_price_typography' === $el ) {

      // Custom font size.
      $classnames .= nectar_font_param_group_classes($atts);

     }


     else if ( 'nectar_responsive_text' === $el ) {

      // Custom font size.
      $classnames .= nectar_font_param_group_classes($atts);

      // Text direction.
      $classnames .= nectar_text_direction_param_group_classes($atts);

      // animated links.
      $classnames .= 'nectar-link-underline-effect ';
      

     }


		 if( !empty($classnames) ) {
			 return ' ' . $classnames;
		 }
		 return $classnames;

	 }

 }
