<?php
/**
 * Creates various style/sizing calculations dynamically from theme options.
 *
 * The styles generated from here will either be contained in salient/css/salient-dynamic-styles.css
 * or output directly in the head, depending on if the server writing permission is set for the css directory.
 *
 * @version 13.0
 */

 /*---------------------------------------------------------------------------

 [Table Of Contents]

 1. Header Navigation
    1.1. Header Navigation Sizing
    1.2. Header Navigation Dropdown
    1.3. Mobile Logo Height
    1.4. Custom Mobile Breakpoint
    1.5. Megamenu Removes Transparent
    1.6. Dark Color Scheme
    1.7. Social Icons
    1.8. AJAX Search
    1.9. Button Styling
    1.10. Text Content
    1.11. Mobile Layout
    1.12. Search Core
    1.13. Ext Search
    1.14. Search Typography
    1.15. Shadows
    1.16. Animations
    1.17. OCM Alignment
    1.18. Background Blur
    1.19. Header Size
    1.20. Header Border
2. Link Hover Effects
    2.1 Skip to content focus
    2.2. Header Navigation Hover Effects
    2.3. Global Hover Effects
    2.4. General links
3. Nectar Slider Font Sizing
4. Header Navigation Transparent Coloring
5. Extended Responsive Theme Option
6. Form styling
   6.1. Fancy Selects
   6.2. Input Coloring
   6.3. Input Padding
   6.4. Submit Button Sizing
   6.5  Minimal Style
   6.6. Input Sizing
   6.7. Input Styling Output
7. Blog
    7.1. Blog Single Width
    7.2. Blog Archives
    7.3. Blog Comments
8. Page Transitions
9. Page Header
10. Button Roundness
11. Call To Action
12. Body Border
13. Mobile Animations
14. Footer
  14.1 Core Footer
  14.2. Reveal Effect
  14.3. Footer Layouts
  14.4. To Top Button
  14.5  Global Section
15. Column Spacing
16. Off Canvas Menu
  16.1 Font sizing
17. Animations
18. Third Party
  18.1. WooCommerce Theme Skin
  18.2. WooCommerce AJAX Cart
  18.3. WooCommerce Quantity Style
  18.4. WooCommerce Sidebar Toggles
  18.5. WooCommerce Single Gallery Variants
  18.6. WooCommerce Single Gallery Width
  18.7. WooCommerce Add to Cart Style
  18.8. WooCommerce Filters
  18.9. WooCommerce Archive header
  18.10. WooCommerce Product Styles
  18.11. WooCommerce Product Style Mods
  18.12. WooCommerce Related/Upsell Carousel
  18.13. WooCommerce lightbox Gallery Background
  18.14. WooCommerce Review Style
  18.15. WooCommerce Variation Dropdown Style
  18.16. MISC Third Party

---------------------------------------------------------------------------*/

/*-------------------------------------------------------------------------*/
/* 1. Header Navigation
/*-------------------------------------------------------------------------*/
  NectarThemeManager::setup();

  $nectar_options    = get_nectar_theme_options();
  $headerFormat      = (!empty($nectar_options['header_format'])) ? $nectar_options['header_format'] : 'default';
	$theme_skin        = NectarThemeManager::$skin;

  $global_font_color = (isset($nectar_options['overall-font-color']) && !empty($nectar_options['overall-font-color']) ) ? $nectar_options['overall-font-color'] : '#000000';
  $global_bg_color   = (isset($nectar_options['overall-bg-color']) && !empty($nectar_options['overall-bg-color']) ) ? $nectar_options['overall-bg-color'] : '#ffffff';
  $boxed_layout      = (isset($nectar_options['boxed_layout'])) ? $nectar_options['boxed_layout'] : false;

	// Using image based logo.
	if( ! empty( $nectar_options['use-logo'] ) ) {
			$logo_height = ( !empty($nectar_options['logo-height']) ) ? intval($nectar_options['logo-height']) : 30;
	}
	// Using text logo.
	else {
			// Custom size from typography logo line height option.
			if( !empty($nectar_options['logo_font_family']['line-height']) ) {
				$logo_height = intval(substr($nectar_options['logo_font_family']['line-height'],0,-2));
			}
			// Custom size from typography logo font size option.
			else if( !empty($nectar_options['logo_font_family']['font-size']) ) {
				$logo_height = intval(substr($nectar_options['logo_font_family']['font-size'],0,-2));
			}
			// Default size.
			else {
				$logo_height = 22;
			}
	}

	$mobile_logo_height	 		= (!empty($nectar_options['use-logo']) && !empty($nectar_options['mobile-logo-height'])) ? intval($nectar_options['mobile-logo-height']) : 24;
  $mobile_header_layout   = (isset($nectar_options['mobile-menu-layout']) && !empty($nectar_options['mobile-menu-layout'])) ? $nectar_options['mobile-menu-layout'] : 'default';
	$header_padding 				= (!empty($nectar_options['header-padding'])) ? intval($nectar_options['header-padding']) : 28;
	$nav_font_size 					= (!empty($nectar_options['navigation_font_family']['font-size']) && $nectar_options['navigation_font_family']['font-size'] != '-') ? intval(substr($nectar_options['navigation_font_family']['font-size'],0,-2) *1.4 ) : 20;
  $nav_font_line_height   = (!empty($nectar_options['navigation_font_family']['line-height']) && $nectar_options['navigation_font_family']['line-height'] != '-') ? intval( substr($nectar_options['navigation_font_family']['line-height'],0,-2) ) : $nav_font_size;
	$dd_indicator_height 		= (!empty($nectar_options['use-custom-fonts']) && $nectar_options['use-custom-fonts'] == 1 && !empty($nectar_options['navigation_font_size']) && $nectar_options['navigation_font_size'] != '-') ? intval(substr($nectar_options['navigation_font_size'],0,-2)) -1 : 20;
	$headerFormat 					= (!empty($nectar_options['header_format'])) ? $nectar_options['header_format'] : 'default';
	$shrinkNum 							= (!empty($nectar_options['header-resize-on-scroll-shrink-num'])) ? intval($nectar_options['header-resize-on-scroll-shrink-num']) : 6;
	$perm_trans 						= (!empty($nectar_options['header-permanent-transparent'])) ? $nectar_options['header-permanent-transparent'] : 'false';
  $headerResize 					= (!empty($nectar_options['header-resize-on-scroll']) && $perm_trans != '1' && $headerFormat != 'centered-menu-bottom-bar') ? $nectar_options['header-resize-on-scroll'] : '0';
	$hideHeaderUntilNeeded 	= (!empty($nectar_options['header-hide-until-needed']) && $headerFormat != 'centered-menu-bottom-bar') ? $nectar_options['header-hide-until-needed'] : '0';
	$body_border 						= (!empty($nectar_options['body-border'])) ? $nectar_options['body-border'] : 'off';
	$headerRemoveStickiness = (!empty($nectar_options['header-remove-fixed'])) ? $nectar_options['header-remove-fixed'] : '0';
	$using_secondary 				= (!empty($nectar_options['header_layout'])) ? $nectar_options['header_layout'] : ' ';
	$menu_item_spacing 			= (!empty($nectar_options['header-menu-item-spacing'])) ? esc_attr($nectar_options['header-menu-item-spacing']) : '10';
  $side_widget_class      = (!empty($nectar_options['header-slide-out-widget-area-style'] ) ) ? $nectar_options['header-slide-out-widget-area-style'] : 'slide-out-from-right';
  $side_widget_area       = (!empty($nectar_options['header-slide-out-widget-area'] ) && $headerFormat != 'left-header') ? $nectar_options['header-slide-out-widget-area'] : 'off';
  $centered_menu_bb_sep   = (isset($nectar_options['centered-menu-bottom-bar-separator']) && !empty($nectar_options['centered-menu-bottom-bar-separator'])) ? $nectar_options['centered-menu-bottom-bar-separator'] : '0';
  $centered_menu_align    = (isset($nectar_options['centered-menu-bottom-bar-alignment']) && !empty($nectar_options['centered-menu-bottom-bar-alignment'])) ? $nectar_options['centered-menu-bottom-bar-alignment'] : 'center';
  $header_fullwidth       = (!empty($nectar_options['header-fullwidth'])) ? $nectar_options['header-fullwidth'] : '0';
	$header_fullwidth_pad   = (!empty($nectar_options['header-fullwidth-padding'])) ? $nectar_options['header-fullwidth-padding'] : 28;
  $header_hover_effect    = (isset($nectar_options['header-hover-effect']) && !empty($nectar_options['header-hover-effect'])) ? $nectar_options['header-hover-effect'] : 'default';
  
  $button_width = 1; // in em
  $button_sizing = isset($nectar_options['header-hover-effect-button-bg-size']) ? $nectar_options['header-hover-effect-button-bg-size'] : 'medium';
  if( 'small' === $button_sizing ) {
    $button_width = 0.8; 
  } else if( 'large' === $button_sizing ) {
    $button_width = 1.4; 
  }
  // Button bg modifies the link line height calculation.
  $nav_item_extra_height = 0;
  if( 'button_bg' === $header_hover_effect && 'left-header' !== $headerFormat ) {
    $nav_item_extra_height = ( ($button_width/1.5)*$nav_font_size );
  }

  if( class_exists('NectarElDynamicStyles') ) {
    $header_fullwidth_pad = NectarElDynamicStyles::percent_unit_type($header_fullwidth_pad, false);
  } else {
    $header_fullwidth_pad = intval($header_fullwidth_pad) . 'px';
  }

  $user_set_side_widget_area = $side_widget_area;

  if( isset($nectar_options['header-resize-on-scroll-shrink-num']) && '0' === $nectar_options['header-resize-on-scroll-shrink-num'] ) {
		$shrinkNum = 0;
	}

	// Options that disable the header resize effect.
	if( $hideHeaderUntilNeeded === '1' || $body_border === '1' || $headerFormat === 'left-header' || $headerRemoveStickiness === '1') {
		$headerResize = '0';
	}

	// Larger secondary header with material theme skin.
	if( $theme_skin === 'material' ) {
		$extra_secondary_height = ($using_secondary === 'header_with_secondary') ? 42 : 0;
	} else {
		$extra_secondary_height = ($using_secondary === 'header_with_secondary') ? 34 : 0;
	}

	if( $headerFormat === 'centered-menu-bottom-bar') {
    $sep_height = ($headerFormat === 'centered-menu-bottom-bar' && '1' === $centered_menu_bb_sep ) ? $header_padding : 0;
	 	$header_space = $logo_height + ($header_padding*3) + $nav_font_line_height + $extra_secondary_height + $sep_height + $nav_item_extra_height;
	}
	else if( $headerFormat === 'centered-menu-under-logo') {
	 	$header_space = $logo_height + ($header_padding*2) + 20 + $nav_font_line_height + $extra_secondary_height + $nav_item_extra_height;
	}
	else {
		$header_space = $logo_height + ($header_padding*2) + $extra_secondary_height;
	}

	$page_transition_bg 	= (!empty($nectar_options['transition-bg-color'])) ? $nectar_options['transition-bg-color'] : '#ffffff';
	$page_transition_bg_2 = (!empty($nectar_options['transition-bg-color-2'])) ? $nectar_options['transition-bg-color-2'] : $page_transition_bg;


	$headerFormat = (!empty($nectar_options['header_format'])) ? $nectar_options['header_format'] : 'default';
	$small_matieral_header_space = (($header_padding/1.8)*2) + $logo_height - $shrinkNum;

  $menu_label = false;
  if( ! empty( $nectar_options['header-menu-label'] ) && $nectar_options['header-menu-label'] === '1' ) {
    $menu_label = true;
  }

  // Ext search.
  $ajax_search       = ( ! empty( $nectar_options['header-disable-ajax-search'] ) && $nectar_options['header-disable-ajax-search'] === '1' ) ? 'no' : 'yes';
  $header_search     = ( ! empty( $nectar_options['header-disable-search'] ) && $nectar_options['header-disable-search'] === '1' ) ? 'false' : 'true';
  $ext_search_active = false;

  if( 'material' === $theme_skin && 'yes' === $ajax_search && 'true' === $header_search ) {
    $ext_search_active = true;
  }


  /*-------------------------------------------------------------------------*/
  /* 1.1. Header Navigation Sizing
  /*-------------------------------------------------------------------------*/

    $material_header_space = $logo_height + ($header_padding*2);

	  if( $headerFormat !== 'left-header' ) {

			// Desktop header navigation sizing.
		  echo '
		  @media only screen and (min-width: 1000px) {

        #header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 #logo,
				#header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 .logo-clone {
					margin-top: -'. $header_padding/2 .'px;
				}

				#header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 nav >ul >li:not(#social-in-menu):not(#nectar-user-account):not(#search-btn):not(.slide-out-widget-area-toggle) > a {
					margin-bottom: '.$header_padding.'px;
				}

				#header-outer #logo,
        #header-outer .logo-clone,
				#header-outer .logo-spacing {
					margin-top: '.$header_padding.'px;
					margin-bottom: '.$header_padding.'px;
					position: relative;
				}

				 #header-outer.small-nav #logo,
         #header-outer.small-nav .logo-clone,
				 #header-outer.small-nav .logo-spacing {
						margin-top: '. ($header_padding/1.8) .'px;
						margin-bottom: '. ($header_padding/1.8) .'px;

				}

        #header-outer.small-nav .logo-clone img,
				#header-outer.small-nav #logo img,
				#header-outer.small-nav .logo-spacing img {
						height: '. ($logo_height - $shrinkNum) .'px;
				}

		  }';

      if( false === $ext_search_active ) {
        echo '@media only screen and (min-width: 1000px) {
          .material #header-outer:not(.transparent) .bg-color-stripe {
            top: '.$material_header_space.'px;
            height: calc(35vh - '.$material_header_space.'px);
          }

          .material #header-outer:not(.transparent).small-nav .bg-color-stripe {
            top: '.$small_matieral_header_space.'px;
            height: calc(35vh - '.$small_matieral_header_space.'px);
          }
        }

        @media only screen and (max-width: 999px) {
          .material #header-outer:not([data-permanent-transparent="1"]):not(.transparent) .bg-color-stripe,
          .material #header-outer:not([data-permanent-transparent="1"]).transparent .bg-color-stripe {
            top: '. ($mobile_logo_height+24) .'px;
            height: calc(30vh - '. ($mobile_logo_height+24) .'px);
          }
        }';
      }

		  echo '#header-outer #logo img,
      #header-outer .logo-clone img,
			#header-outer .logo-spacing img {
				height: ' . $logo_height .'px;
			}';


    // Centered menu bottom bar
    if( $headerFormat === 'centered-menu-bottom-bar' ) {

      // Bottom bar separator
      if( '1' === $centered_menu_bb_sep ) {
        echo '@media only screen and (min-width: 1000px) {
         #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3 {
           margin-bottom: '. ($header_padding) .'px;
         }
         #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3:before {
           margin-left: -50vw;
           left: 50%;
           bottom: 0;
           width: 100vw;
           height: 1px;
           content: "";
           z-index: 100;
           background-color: rgba(0,0,0,0.08);
           position: absolute;
         }
         #header-outer[data-format="centered-menu-bottom-bar"].transparent.dark-slide #top .span_3:before {
           background-color: rgba(0,0,0,0.1);
         }
         body.material #header-outer[data-box-shadow="large-line"].transparent:not(.scrolled-down):not(.fixed-menu) {
           box-shadow: 0 1px 0 rgba(255,255,255,0.25), 0 18px 40px rgba(0,0,0,0.0);
         }
         body.material #header-outer[data-box-shadow="large-line"].transparent.dark-slide:not(.scrolled-down):not(.fixed-menu) {
           box-shadow: 0 1px 0 rgba(0,0,0,0.1), 0 18px 40px rgba(0,0,0,0.0);
         }

         #header-outer[data-format="centered-menu-bottom-bar"].transparent:not(.dark-slide) #top .span_3:before,
         body[data-header-color="dark"] #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3:before {
           background-color: rgba(255,255,255,0.25);
         }
        }';
        if( !empty($nectar_options['header-color']) &&
            $nectar_options['header-color'] === 'custom' &&
            !empty($nectar_options['header-separator-color']) ) {

          echo '#header-outer[data-format="centered-menu-bottom-bar"] #top .span_3:before {
            background-color: '.esc_attr($nectar_options['header-separator-color']).';
          }';

        }
      }

      // Left alignment specific.
      if( 'left' === $centered_menu_align ) {
        echo '#header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"]  #top .span_9 ul.buttons > li {
          margin-top: -'. ($header_padding) .'px;
          height: calc(100% + '.$header_padding.'px);
        }';
      }
    } // centered menu bottom bar end


    // Full width header left/right custom padding
    if( 'left-header' !== $headerFormat && '1' === $header_fullwidth && !empty($header_fullwidth_pad) && '28px' !== $header_fullwidth_pad ) {
     echo '@media only screen and (min-width: 1000px) {
       #header-outer[data-full-width="true"] header > .container {
         padding: 0 '. esc_attr($header_fullwidth_pad) . ';
       }
     }';
    }

    if( isset($nectar_options['header-text-widget']) && !empty($nectar_options['header-text-widget']) ) {
        echo 'body[data-header-search="false"][data-full-width-header="false"] #header-outer[data-lhe="animated_underline"][data-format="default"][data-cart="false"] #top nav >ul >li:last-child > a,
        #header-outer[data-menu-bottom-bar-align="left"][data-format="centered-menu-bottom-bar"][data-lhe="animated_underline"] #top .span_3 nav.right-side ul > li:last-child > a,
        #header-outer[data-menu-bottom-bar-align="left_t_center_b"][data-format="centered-menu-bottom-bar"][data-lhe="animated_underline"] #top .span_3 nav.right-side ul > li:last-child > a {
          margin-right: '.$menu_item_spacing.'px;
        }
        #header-outer[data-menu-bottom-bar-align="left_t_center_b"][data-format="centered-menu-bottom-bar"][data-lhe="default"] #top .span_3 nav.right-side ul > li:not([class*="button_"]):last-child > a,
        #header-outer[data-menu-bottom-bar-align="left"][data-format="centered-menu-bottom-bar"][data-lhe="default"] #top .span_3 nav.right-side ul > li:not([class*="button_"]):last-child > a {
          padding-right: '.$menu_item_spacing.'px;
        }';
    }

		 echo'
		 #header-outer[data-lhe="animated_underline"] #top nav > ul > li > a,
		 #top nav > ul > li[class*="button_solid_color"] > a,
		 body #header-outer[data-lhe="default"] #top nav .sf-menu > li[class*="button_solid_color"] > a:hover,
		 #header-outer[data-lhe="animated_underline"] #top nav > .sf-menu > li[class*="button_bordered"] > a,
		 #top nav > ul > li[class*="button_bordered"] > a,
		 body #header-outer.transparent #top nav > ul > li[class*="button_bordered"] > a,
		 body #header-outer[data-lhe="default"] #top nav .sf-menu > li[class*="button_bordered"] > a:hover,
		 body #header-outer.transparent #top nav > ul > li[class*="button_solid_color"] > a,
		 #header-outer[data-lhe="animated_underline"] #top nav > ul > li[class*="button_solid_color"] > a {
			 margin-left: '.$menu_item_spacing.'px;
			 margin-right: '.$menu_item_spacing.'px;
		 }

		 #header-outer[data-lhe="default"] #top nav > ul > li > a,
     #header-outer .nectar-header-text-content,
     body[data-header-search="false"][data-full-width-header="false"] #header-outer[data-lhe="animated_underline"][data-format="default"][data-cart="false"] .nectar-header-text-content {
			 padding-left: '.$menu_item_spacing.'px;
			 padding-right: '.$menu_item_spacing.'px;
		 }';

     if( 'left' !== $centered_menu_align ) {
  		 echo '#header-outer[data-lhe="animated_underline"][data-condense="true"][data-format="centered-menu-bottom-bar"].fixed-menu #top nav > ul > li > a {
  			 margin-left: '. floor($menu_item_spacing/1.3) .'px;
  			 margin-right: '. floor($menu_item_spacing/1.3) .'px;
  		 }

  		 #header-outer[data-lhe="default"][data-condense="true"][data-format="centered-menu-bottom-bar"].fixed-menu #top nav > ul > li > a {
  			 padding-left: '. floor($menu_item_spacing/1.3) .'px;
  			 padding-right: '. floor($menu_item_spacing/1.3) .'px;
  		 }';
     }

     // No margin on last li for full width header layout
     if( 'default' === $headerFormat && '1' === $header_fullwidth ) {
      echo '@media only screen and (min-width: 1000px) {
        body.material #header-outer #top .span_9 nav > ul.sf-menu > li:last-child > a {
          margin-right: 0;
        }
      }';
     }


     /*-------------------------------------------------------------------------*/
     /* 1.2. Header Navigation Dropdown
     /*-------------------------------------------------------------------------*/

     // Arrows.
		 $dropdown_arrows = (!empty($nectar_options['header-dropdown-arrows']) && $headerFormat !== 'left-header' ) ? $nectar_options['header-dropdown-arrows'] : 'inherit';

		 if( $dropdown_arrows === 'show' && intval($menu_item_spacing) > 7 || $theme_skin === 'original' && $dropdown_arrows === 'inherit') {

			 echo '#header-outer #top .sf-menu > .sf-with-ul > a {
					 padding-right: '. intval(intval($menu_item_spacing) + 10) .'px!important;
				 }
	 			 #header-outer[data-lhe="animated_underline"] #top .sf-menu > .sf-with-ul:not([class*="button"]) > a {
					 padding-right: 10px!important;
				 }
         #header-outer[data-lhe="animated_underline"] #top .sf-menu > .sf-with-ul[class*="button"] > a {
					 padding-right: 26px!important;
				 }
				 #header-outer[data-lhe="default"][data-condense="true"][data-format="centered-menu-bottom-bar"]:not([data-menu-bottom-bar-align="left"]).fixed-menu #top nav .sf-menu > .sf-with-ul > a {
					 padding-right: '. intval( floor($menu_item_spacing/1.3) + 10) .'px!important;
				 }';

		 }

     // Dropdown Positioning.
     if( isset( $nectar_options['header-dropdown-position'] ) &&
     !empty( $nectar_options['header-dropdown-position'] ) &&
     'bottom-of-menu-item' === $nectar_options['header-dropdown-position'] ) {

       echo '#header-outer #top .sf-menu > li > ul,
       body #header-outer #top .nectar-woo-cart .widget_shopping_cart,
       body #header-outer #top .cart-notification {
         top: 50%;
         margin-top: '.( $nav_font_line_height + 12) .'px;
         transition: margin 0.2s ease;
       }
       #header-outer.small-nav #top .sf-menu > li > ul,
       body #header-outer.small-nav #top .nectar-woo-cart .widget_shopping_cart,
       body #header-outer.small-nav #top .cart-notification {
         top: 50%;
         margin-top: '.( $nav_font_line_height + 12 - ($header_padding/3.5) ) .'px;
       }';


       if( 'button_bg' === $header_hover_effect ) {
         
        $button_extra_spacing = 28;
        $button_bg_sizing = isset($nectar_options['header-hover-effect-button-bg-size']) ? $nectar_options['header-hover-effect-button-bg-size'] : 'medium';
        if( $button_bg_sizing == 'large' || $button_bg_sizing == 'medium' ) {
          $button_extra_spacing = 35;
        }
          echo '#header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 .sf-menu > li > ul,
          #header-outer[data-format="centered-menu-under-logo"] #top .sf-menu > li > ul {
            top: 0%;
            margin-top: '.( $nav_font_line_height + $button_extra_spacing) .'px;
          }';

       } else {
          echo '#header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 .sf-menu > li > ul,
          #header-outer[data-format="centered-menu-under-logo"] #top .sf-menu > li > ul {
            top: 0%;
            margin-top: '.( $nav_font_line_height + 16) .'px;
          }';
       }

    
       echo '#header-outer #top .widget_shopping_cart .widget_shopping_cart_content ul,
       #header-outer .cart-notification {
         border-top: none;
         border-bottom: none;
       }
       #header-outer #top .sf-menu > li ul {
         border-top: none;
       }';
     }

     // Dropdown Animations.
     if( isset($nectar_options['header-dropdown-animation']) ) {

       if( 'default' === $nectar_options['header-dropdown-animation'] ) {
         echo '#top nav >ul >li >ul >li,
         #header-outer[data-format="centered-menu"] #top nav >ul >li >ul >li,
         #header-secondary-outer .sf-menu > li > ul > li,
         #header-outer .widget_shopping_cart .cart_list,
         #header-outer .widget_shopping_cart .total,
         #header-outer .widget_shopping_cart .buttons {
           -webkit-transform:translate3d(0,13px,0);
           transform:translate3d(0,13px,0);
         }';
       }
       else if( 'fade-in-up' === $nectar_options['header-dropdown-animation'] ) {
         echo '
         body .sf-menu >li:not(.nectar-woo-cart):not(.slide-out-widget-area-toggle) {
           overflow: hidden;
         }
         body .sf-menu >li:not(.nectar-woo-cart).sfHover {
           overflow: visible;
         }

         body .sf-menu >li > ul {
           -webkit-transform:translate3d(0,15px,0);
           transform:translate3d(0,15px,0);
           opacity: 0;
         }
         body .sf-menu >li.sfHover > ul{
           -webkit-transform:translate3d(0,0,0);
           transform:translate3d(0,0,0);
           opacity: 1;
         }

         body #header-outer #top .nectar-woo-cart .widget_shopping_cart {
           -webkit-transform:translate3d(0,15px,0);
           transform:translate3d(0,15px,0);
           transition: transform 0.55s cubic-bezier(0.2,.8,.25,1);
         }
         body #header-outer #top .nectar-woo-cart .widget_shopping_cart.open {
           -webkit-transform:translate3d(0,0,0);
           transform:translate3d(0,0,0);
         }

         body .sf-menu >li.sfHover > ul,
         #header-outer #top .sf-menu > li.sfHover > ul {
           transition: transform 0.55s cubic-bezier(0.2,.8,.25,1),opacity 0.55s cubic-bezier(0.2,.8,.25,1);
         }

         body #header-outer #top .nectar-woo-cart .widget_shopping_cart,
         body #header-outer #top .cart-notification {
           transition: transform 0.55s cubic-bezier(0.2,.8,.25,1);
         }

         #header-outer #top .sf-menu.menu-open > li > ul {
          transition: none;
          -webkit-transition: none;
       }

         @media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
           body .sf-menu >li:not(.sfHover) > ul,
           body #header-outer #top .nectar-woo-cart .widget_shopping_cart:not(.open) {
             transform:translate3d(0,-9999px,0);
           }
           body .sf-menu >li.sfHover > ul,
           #header-outer #top .sf-menu > li.sfHover > ul,
            body #header-outer #top .nectar-woo-cart .widget_shopping_cart {
             transition: opacity 0.55s cubic-bezier(0.2,.8,.25,1);
           }


         }';
       }
       else if( 'fade-in' === $nectar_options['header-dropdown-animation']  ) {
        echo '#top nav >ul >li >ul >li,
         #header-outer[data-format="centered-menu"] #top nav >ul >li >ul >li,
         #header-secondary-outer .sf-menu > li > ul > li,
         #header-outer .widget_shopping_cart .cart_list,
         #header-outer .widget_shopping_cart .total,
         #header-outer .widget_shopping_cart .buttons {
          transition: opacity 0.4s ease;
          -webkit-transition: opacity 0.4s ease;
         }';
       }

     }

     // Dropdown Box Shadow.
     if( isset( $nectar_options['header-dropdown-box-shadow'] ) &&
     !empty( $nectar_options['header-dropdown-box-shadow'] ) ) {

       if( 'small' === $nectar_options['header-dropdown-box-shadow'] ) {
         echo '#header-outer #top .sf-menu > li > ul,
         #header-outer #header-secondary-outer .sf-menu > li > ul,
         #header-outer #top .sf-menu > li:not(.megamenu) ul:not(.woocommerce-mini-cart),
         #header-outer .widget_shopping_cart,
         #header-outer .cart-notification,
         body[data-fancy-form-rcs="1"] .nectar-shop-header .woocommerce-ordering .select2-dropdown,
         body[data-fancy-form-rcs="1"] .variations_form .select2-dropdown {
           box-shadow: rgba(0, 0, 0, 0.04) 0px 1px 0px, rgba(0, 0, 0, 0.05) 0px 2px 7px, rgba(0, 0, 0, 0.06) 0px 12px 22px;
         }';
       } else if( 'large-alt' === $nectar_options['header-dropdown-box-shadow']  ) {
         echo '#header-outer #top .sf-menu > li > ul,
         #header-outer #header-secondary-outer .sf-menu > li > ul,
         #header-outer #top .sf-menu > li:not(.megamenu) ul:not(.woocommerce-mini-cart),
         #header-outer .widget_shopping_cart,
         #header-outer .cart-notification,
         body[data-fancy-form-rcs="1"] .nectar-shop-header .woocommerce-ordering .select2-dropdown,
         body[data-fancy-form-rcs="1"] .variations_form .select2-dropdown {
           box-shadow: 
                rgba(0, 0, 0, 0.02) 0px 1px 0px, 
                rgba(0, 0, 0, 0.06) 0px 38px 38px;
         }';
       }
       else if( 'none' === $nectar_options['header-dropdown-box-shadow'] ) {
         echo '#header-outer #top .sf-menu > li ul {
           box-shadow: none;
         }';
       }

     }

     // Dropdown Border Radius.
     if( isset($nectar_options['header-dropdown-border-radius']) &&
     !empty($nectar_options['header-dropdown-border-radius']) &&
     '0' !== $nectar_options['header-dropdown-border-radius'] ) {
       echo '#header-outer #top .sf-menu > li ul,
       #header-outer #header-secondary-outer .sf-menu > li ul,
       #header-outer .widget_shopping_cart .widget_shopping_cart_content,
       #header-outer .cart-notification,
       body #header-outer #top .nectar-woo-cart .widget_shopping_cart,
       body[data-fancy-form-rcs="1"] .nectar-shop-header .woocommerce-ordering .select2-dropdown {
         border-radius: '.esc_attr($nectar_options['header-dropdown-border-radius']).'px;
       }';
     }

		 if( intval($menu_item_spacing) > 15 ) {
			 echo 'body.material[data-header-format="default"] #header-outer[data-has-buttons="yes"]:not([data-format="left-header"]) #top nav >.buttons {
				 margin-left: '. intval($menu_item_spacing)*2 .'px;
			 }';
		 }

		 if( intval($menu_item_spacing) > 30 ) {
		 	  echo 'body.material[data-header-format="default"] #header-outer[data-format="default"] #social-in-menu {
					margin-left: '. intval($menu_item_spacing) .'px;
				} ';
	   }


	} // End not using left header.
	else {
		echo '#header-outer #logo img {
			height: ' . $logo_height .'px;
		}
		body[data-header-format="left-header"] #header-outer .row .col.span_9 {
			top: '. intval($logo_height+40) .'px;
		}';
	}


  // Dropdown Hover Effect.
  $dropdown_hover_effect = (isset($nectar_options['header-dropdown-hover-effect'])) ? esc_attr($nectar_options['header-dropdown-hover-effect']) : 'default';
  $dropdown_underline_color = $nectar_options["accent-color"];

  if( !empty($nectar_options['header-color']) && $nectar_options['header-color'] === 'custom' ) {
    if( !empty($nectar_options['header-dropdown-font-hover-color']) ) {
      $dropdown_underline_color = $nectar_options['header-dropdown-font-hover-color'];
    }
  }

  if( 'animated_underline' === $dropdown_hover_effect ) {

    echo '
    .sf-menu li ul li a .menu-title-text {
      position: relative;
    }
    .sf-menu li ul li a .menu-title-text:after {
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 2px;
      display: block;
      content: "";
      transition: transform 0.35s cubic-bezier(0.52, 0.01, 0.16, 1);
      transform: scaleX(0);
      transform-origin: 0 0;
      background-color: '.esc_attr($dropdown_underline_color).';
    }
    nav > ul > .megamenu > ul > li > a > .menu-title-text:after,
    nav > ul > .megamenu > ul > li > ul > .has-ul > a > .menu-title-text:after {
      display: none;
    }
    .sf-menu li ul li a:focus .menu-title-text:after,
    .sf-menu li ul li a:hover .menu-title-text:after,
    .sf-menu li ul li.sfHover > a .menu-title-text:after,
    .sf-menu li ul li[class*="current-"] > a .menu-title-text:after,
    .sf-menu ul .open-submenu > a .menu-title-text:after {
      transform: scaleX(1);
    }

    .nectar-ext-menu-item .menu-title-text {
      background-repeat: no-repeat;
      background-size: 0% 2px;
      background-image: linear-gradient(to right, '.esc_attr($dropdown_underline_color).' 0%, '.esc_attr($dropdown_underline_color).' 100%);
      -webkit-transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
      transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
      background-position: left bottom;
    }
    a:hover > .nectar-ext-menu-item .menu-title-text,
    a:focus > .nectar-ext-menu-item .menu-title-text,
    li[class*="current"] > a > .nectar-ext-menu-item .menu-title-text {
      background-size: 100% 2px;
    }
    ';
  } else if($headerFormat !== 'left-header') {
    // Default dropdown coloring.
    echo '
    #header-outer nav ul li li:hover >a .sf-sub-indicator i,
    #header-outer nav ul li .sfHover >a .sf-sub-indicator i,
    #header-outer:not([data-format="left-header"]) #top nav >ul >li:not(.megamenu) ul .current-menu-ancestor >a .sf-sub-indicator i,
    #header-outer:not([data-format="left-header"]) nav >ul >.megamenu ul ul .current-menu-item >a,
    #header-outer:not([data-format="left-header"]) nav >ul >.megamenu ul ul .current-menu-ancestor >a,
    #header-outer nav > ul >.megamenu > ul ul .sfHover >a,
    #header-outer nav > ul >.megamenu > ul ul li a:hover,
    #header-outer nav > ul >.megamenu > ul ul li a:focus,
    body:not([data-header-format="left-header"]) #header-outer nav >ul >.megamenu >ul ul .current-menu-item > a,
    #header-outer:not([data-format="left-header"]) #top nav >ul >li:not(.megamenu) ul a:hover,
    #header-outer:not([data-format="left-header"]) #top nav >ul >li:not(.megamenu) .sfHover >a,
    #header-outer:not([data-format="left-header"]) #top nav >ul >li:not(.megamenu) ul .current-menu-item >a,
    #header-outer:not([data-format="left-header"]) #top nav >ul >li:not(.megamenu) ul .current-menu-ancestor >a,
    body[data-dropdown-style="minimal"] #header-secondary-outer ul >li:not(.megamenu) .sfHover >a,
    body[data-dropdown-style="minimal"] #header-secondary-outer ul >li:not(.megamenu) ul a:hover {
      color:#fff
    }
    body:not([data-header-format="left-header"]) #header-outer nav >ul >.megamenu >ul ul li a:hover,
    body:not([data-header-format="left-header"]) #header-outer nav >ul >.megamenu >ul ul .current-menu-item > a{
      color:#fff;
      background-color:#000
    }';
  }

  // When ext menu items have images removed, the underline effect is still needed.
  if( isset($nectar_options['header-slide-out-widget-area-image-display']) &&
      'remove_images' === $nectar_options['header-slide-out-widget-area-image-display'] ) {

        $animated_underline_thickness = (isset($nectar_options['animated-underline-thickness']) && !empty($nectar_options['animated-underline-thickness'])) ? $nectar_options['animated-underline-thickness'] : '2';

        if( (in_array($theme_skin, array('original','ascend')) && 'fullscreen-split' !== $side_widget_class) ||
            'material' === $theme_skin && in_array($side_widget_class, array('fullscreen', 'fullscreen-alt')) ) {

          echo '#slide-out-widget-area .nectar-ext-menu-item .menu-title-text {
            background-image: none!important;
          }';

        }
        else if( 'animated_underline' !== $dropdown_hover_effect ) {

          echo '
          #slide-out-widget-area .nectar-ext-menu-item .menu-title-text {
            background-repeat: no-repeat;
            background-size: 0% '.esc_attr($animated_underline_thickness).'px;
            background-image: linear-gradient(to right, '.esc_attr($dropdown_underline_color).' 0%, '.esc_attr($dropdown_underline_color).' 100%);
            -webkit-transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
            transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
            background-position: left bottom;
          }
          #slide-out-widget-area a:hover > .nectar-ext-menu-item .menu-title-text,
          #slide-out-widget-area li[class*="current"] > a > .nectar-ext-menu-item .menu-title-text {
            background-size: 100% '.esc_attr($animated_underline_thickness).'px;
          }';

        }

    }







	global $post;
	if( !empty($nectar_options['transparent-header']) && $nectar_options['transparent-header'] === '1' && isset($post->ID) ) {
		$activate_transparency = nectar_using_page_header($post->ID);
	} else {
		$activate_transparency = false;
	}

	 echo '#header-space {
		 height: '. $header_space .'px;
	 }
	 @media only screen and (max-width: 999px) {
		 #header-space {
			 height: '. (intval($mobile_logo_height) + 24) .'px;
		 }
	 }';

	 $header_extra_space_to_remove = $extra_secondary_height;

	 if($headerFormat === 'centered-menu-under-logo' || $headerFormat === 'centered-menu-bottom-bar') {
		 $header_extra_space_to_remove += 20;
	 } else {
		 $header_extra_space_to_remove += intval($header_padding);
	 }


  

  
  if( isset($nectar_options['header-dropdown-display-desc']) &&
      !empty($nectar_options['header-dropdown-display-desc']) &&
      '1' === $nectar_options['header-dropdown-display-desc']) {
      echo '#slide-out-widget-area .inner .off-canvas-menu-container li a .item_desc {
        display: block;
      }';
  }


	 // Permanent transparent theme option.
	 $perm_trans = (!empty($nectar_options['header-permanent-transparent'])) ? $nectar_options['header-permanent-transparent'] : 'false';

   /*-------------------------------------------------------------------------*/
   /* 1.3. Mobile Logo Height
   /*-------------------------------------------------------------------------*/
	 echo '
	 #header-outer #logo .mobile-only-logo,
   #header-outer[data-format="centered-menu-bottom-bar"][data-condense="true"] .span_9 .logo-clone img {
		 height: '.esc_attr($mobile_logo_height).'px;
	 }

	 @media only screen and (max-width: 999px) {
		 	body #top #logo img,
			#header-outer[data-permanent-transparent="false"] #logo .dark-version {
		 		height: '.esc_attr($mobile_logo_height).'px!important;
		 	}

	 }';


   /*-------------------------------------------------------------------------*/
   /* 1.4. Custom Mobile Breakpoint
   /*-------------------------------------------------------------------------*/
	 $mobile_breakpoint = (!empty($nectar_options['header-menu-mobile-breakpoint'])) ? $nectar_options['header-menu-mobile-breakpoint'] : 1000;
	 $has_main_menu     = (has_nav_menu('top_nav')) ? 'true' : 'false';

   if( $headerFormat === 'centered-logo-between-menu-alt' ) {
     $has_main_menu = 'true';
   }

	 if( !empty($mobile_breakpoint) && $mobile_breakpoint != 1000 &&
      $has_main_menu === 'true' &&
      'centered-menu' !== $mobile_header_layout ) {

	 	$mobileMenuTopPadding      = ceil(($logo_height/2)) - 10;
	 	$mobileMenuTopPaddingSmall = ceil( ($logo_height-$shrinkNum) / 2  ) - 10;

    $starting_opacity  = (isset($nectar_options['header-starting-opacity']) && !empty($nectar_options['header-starting-opacity'])) ? $nectar_options['header-starting-opacity'] : '0.75';
	 	$starting_color    = (empty($nectar_options['header-starting-color'])) ? '#ffffff' : $nectar_options['header-starting-color'];
	 	$mobile_menu_hover = $nectar_options["accent-color"];

	 	if( !empty($nectar_options['header-color']) && $nectar_options['header-color'] === 'custom' && !empty($nectar_options['header-font-hover-color'])) {
	 		$mobile_menu_hover = $nectar_options['header-font-hover-color'];
	 	}

    // Left Header format
    if ( $headerFormat === 'left-header' ) {
      echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {

        html body[data-header-format="left-header"] #header-space {
          display: block;
        }
        html body[data-header-format="left-header"] #ajax-content-wrap {
          margin-left: 0;
        }
        #header-outer[data-format="left-header"] #top .sf-menu .nectar-woo-cart {
            display: none;
        }
        html body #header-outer[data-format="left-header"] {
          width: 100%;
          height: auto;
          border-right: none;
        } 
        html body[data-header-format="left-header"] #top {
          height: auto;
        } 
        html body[data-header-format="left-header"] #top #logo {
          margin: '.$header_padding.'px 0;
          display: block;
        }
        #header-outer[data-format="left-header"] #top .span_9, 
        #header-outer[data-format="left-header"] #top .span_3 {
          width: auto;
        } 
        #header-outer[data-format="left-header"] #top .span_9 {
          margin-left: auto;
          position: relative;
          bottom: auto;
          overflow: visible;
          display: flex;
          width: auto;
          top: 0;
        }
        body[data-header-format="left-header"] #header-outer .button_social_group {
          display: none;
        }
        #header-outer[data-format="left-header"] #top .buttons.sf-menu,
        body[data-header-format="left-header"] #header-outer .span_3 {
          margin-top: 0;
        }
        body[data-header-format="left-header"] #header-outer nav {
            padding: 0;
            display: block;
        }
        #header-outer[data-format="left-header"] #top > .container > .row nav >ul {
          width: auto;
          gap: 20px;
          margin:0 20px;
        }
        body[data-header-format="left-header"][data-header-search="true"] #header-outer nav #nectar-user-account {
          margin-left: 0;
        }
        .ascend #header-outer[data-full-width="false"] #top nav ul #nectar-user-account >div {
          padding-left: 0;
        }
        #header-outer[data-format="left-header"] #top > .container > .row, 
        #header-outer[data-format="left-header"] #top > .container > .row nav, 
        #header-outer[data-format="left-header"] #top > .container > .row nav >ul,
        body[data-header-format="left-header"] #header-outer .nav-outer {
          display: flex;
      }
      #header-outer[data-format="left-header"] .buttons.sf-menu li {
        display: flex;
        align-content: center;
        align-items: center;
      }

      html body[data-header-format="left-header"].material #search-outer {
        left: 0;
        width: 100%;
        position: absolute;
        z-index: 99999!important;
      }

      body[data-header-format="left-header"] .portfolio-filters-inline.full-width-section:not(.non-fw),
      body[data-header-format="left-header"] .full-width-content.blog-fullwidth-wrap,
      body[data-header-format="left-header"] .wpb_row.full-width-content,
      body[data-header-format="left-header"] .wpb_row.full-width-content.has-global-section .wpb_row.full-width-content,
      body[data-header-format="left-header"] .full-width-content.nectar-shop-outer,
      body[data-header-format="left-header"] .page-submenu > .full-width-section,
      body[data-header-format="left-header"] .page-submenu .full-width-content,
      body[data-header-format="left-header"] .full-width-section .row-bg-wrap,
      body[data-header-format="left-header"] .full-width-section .nectar-parallax-scene,
      body[data-header-format="left-header"] .full-width-section > .nectar-shape-divider-wrap,
      body[data-header-format="left-header"] .full-width-section > .video-color-overlay,
      body[data-header-format="left-header"][data-aie="zoom-out"] .first-section .row-bg-wrap,
      body[data-header-format="left-header"][data-aie="long-zoom-out"] .first-section .row-bg-wrap,
      body[data-header-format="left-header"][data-aie="zoom-out"] .top-level.full-width-section .row-bg-wrap,
      body[data-header-format="left-header"][data-aie="long-zoom-out"] .top-level.full-width-section .row-bg-wrap,
      body[data-header-format="left-header"] .full-width-section.parallax_section .row-bg-wrap,
      body[data-header-format="left-header"] .nectar-slider-wrap[data-full-width="true"],
      body[data-header-format="left-header"] .wpb_row.full-width-section .templatera_shortcode > .wpb_row.full-width-section > .row-bg-wrap,
      .single-product[data-header-format="left-header"] .product[data-gallery-style="left_thumb_sticky"][data-gallery-variant="fullwidth"] .single-product-wrap {
        margin-left: -50vw;
        margin-left: calc(-50vw + var(--scroll-bar-w)/2);
        left: 50%;
        width: 100vw;
        width: calc(100vw - var(--scroll-bar-w));
      }
      body[data-header-format="left-header"] .full-width-section > .nectar-video-wrap {
        margin-left: calc(-50vw + var(--scroll-bar-w)/2);
        width: calc(100vw - var(--scroll-bar-w))!important;
      }

      }';
    }

    // Regular formats
	 	echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {

      #top .span_9 > .nectar-mobile-only {
        display: flex;
        align-items: center;
        order: 1;
      }

      #header-outer[data-has-buttons="no"] #top .span_9 > .nectar-mobile-only {
        margin-right: 20px;
      }

      #header-outer:not([data-format="centered-menu-bottom-bar"]) #top .span_9 {
        flex-direction: row-reverse;
      }

      body #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3:before {
        display: none;
      }

      body #header-outer[data-format="centered-menu-bottom-bar"][data-condense="true"].fixed-menu .span_3 .sf-menu  {
        visibility: visible;
      }

      #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3 {
        margin-bottom: 0;
      }


      body.material #header-outer[data-format="centered-menu-bottom-bar"][data-condense="true"].fixed-menu #search-outer {
        top: 0;
      }

      body[data-slide-out-widget-area-style="simple"] #header-outer #mobile-menu {
          top: 100%;
      }

      body[data-slide-out-widget-area-style="simple"][data-ext-responsive="true"] #header-outer[data-full-width="false"] #mobile-menu {
        padding: 0 90px;
      }

	 		#header-outer #top .span_9 nav .sf-menu:not(.buttons) > li,
			#top .span_9 nav .buttons .menu-item,
      #top .right-aligned-menu-items .buttons .menu-item {
				visibility: hidden;
				pointer-events: none;
				margin: 0;
			}

	 		#header-outer #top .span_9 nav .sf-menu:not(.buttons) > li,
			#top .span_9 nav .buttons .menu-item,
      #top .right-aligned-menu-items .buttons .menu-item {
				position: absolute;
			}

      #header-outer[data-format="centered-menu"] #top nav >.buttons {
        position: relative;
      }

	 		#header-outer #top nav .sf-menu > #social-in-menu {
				position: relative;
				visibility: visible;
				pointer-events: auto;
			}

			body.material[data-header-search="true"][data-user-set-ocm="off"] #header-outer:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons,
			body.material[data-cart="true"][data-user-set-ocm="off"] #header-outer:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons,
			body.material[data-user-account-button="true"][data-user-set-ocm="off"] #header-outer:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons {
				margin-right: 28px;
			}

      body.ascend[data-header-search="true"] #header-outer[data-full-width="false"]:not([data-format="left-header"]) #top nav > .buttons,
      body.ascend[data-cart="true"] #header-outer[data-full-width="false"]:not([data-format="left-header"]) #top nav > .buttons {
        margin-right: 19px;
      }

			body[data-header-search="true"] #header-outer:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons,
			body[data-cart="true"] #header-outer:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons,
			body[data-user-account-button="true"] #header-outer:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons {
				margin-right: 19px;
			}

      body #header-outer[data-full-width="false"][data-has-buttons="no"]:not([data-format="left-header"]) #top nav #social-in-menu,
      body.material #header-outer[data-has-buttons="no"]:not([data-format="left-header"]) #top nav #social-in-menu {
        margin-right: 20px;
      }

      #header-outer[data-format="menu-left-aligned"] #top > .container .span_9 > .slide-out-widget-area-toggle.mobile-icon {
        top: 50%;
        right: 0;
        position: absolute;
        transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
      }
      body #header-outer[data-format="menu-left-aligned"]:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons {
        margin-right: 55px!important;
      }
      ';
			if( true === $menu_label ) {
        echo 'body #header-outer[data-format="menu-left-aligned"]:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons {
          margin-right: 110px!important;
        }
        #header-outer[data-format="centered-menu-bottom-bar"] .right-side > .slide-out-widget-area-toggle a.using-label > span {
          display: inline-block;
          vertical-align: middle;
        }
        #header-outer[data-format="centered-menu-bottom-bar"] .right-side > .slide-out-widget-area-toggle a.using-label .label {
          margin-right: 15px;
        }';
      }

      //// Centered Menu.
      if( $headerFormat === 'centered-menu' ) {
        echo '#header-outer #top .span_9 nav .sf-menu:not(.buttons) {
          display: none!important; 
        }
        #header-outer[data-format=centered-menu] #top .span_9, #header-outer[data-format=centered-menu] #top .span_9 nav {
          flex-grow: 0;
        }
        #header-outer #top .logo-spacing {
          width: 1px; 
        }';
      }

      // Centered Logo Between Menu Alt.
      if( $headerFormat === 'centered-logo-between-menu-alt' ) {
        echo '
          #header-outer #top > .container > .row {
            justify-content: flex-start;
          }
          #header-outer #top > .container > .row > .span_9,
          #header-outer #top > .container > .row > .right-aligned-menu-items {
            position: relative;
            width: auto;
            pointer-events: all;
          }
          #header-outer #top > .container > .row > .right-aligned-menu-items {
            order: 1;
            margin-left: auto;
          }
          #header-outer #top > .container > .row > .span_9 {
            order: 2;
          }
          #header-outer #top > .container > .row > .span_9 nav {
            display: none;
          }
          body.material #header-outer:not([data-format="left-header"]) #top nav >.buttons {
            margin-left: 0;
          }
          #header-outer #top>.container>.row>.right-aligned-menu-items nav >.buttons  {
            display: none!important;
          }

          #header-outer #top .span_9 > .mobile-search,
          #header-outer #top .span_9 > .slide-out-widget-area-toggle,
          #header-outer #top .span_9 > #mobile-cart-link,
          #header-outer #top .mobile-user-account {
            display: flex;
            position: relative;
            align-items: center;
            padding: 0 12px;
          }
          
          #header-outer #top .span_9 > .nectar-mobile-only.mobile-header {
            order: 11;
          }
          
          #header-outer #top .span_9 > .mobile-search {
            order: 10;
          }
          #header-outer #top .span_9 > .mobile-user-account {
            order: 9;
          }
          #header-outer #top .span_9 > #mobile-cart-link{
            order: 8;
          }
          #header-outer #top .span_9 > .slide-out-widget-area-toggle {
            order: 7;
          }

        ';
      }

      // Centered Menu Bottom Bar.
			else if( $headerFormat === 'centered-menu-bottom-bar' ) {

        // Centered menu bottom bar is no longer two rows at this point.
        $centerd_header_space = $logo_height + ($header_padding*2) + $extra_secondary_height;
    	  echo 'body #header-space {
    		  height: '. esc_attr($centerd_header_space) .'px;
    	  }';

        
				echo '
        #header-outer[data-menu-bottom-bar-align="left"] #top .span_9 > .nectar-mobile-only.mobile-header {
          display: none; 
        }

        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] .row {
          flex-direction: row;
        }
        body.material #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #top .span_9 {
          width: auto;
          margin-left: 20px;
        }
        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #top .span_9 nav {
          display: none;
        }
        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #top .span_9 > .mobile-search,
        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #top .span_9 > .slide-out-widget-area-toggle,
        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #top .span_9 > #mobile-cart-link {
          display: flex;
          position: relative;
          align-items: center;
          padding: 0 12px;
        }
        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #top #mobile-cart-link i {
          font-size: 22px;
          line-height: 22px;
        }
        #header-outer[data-format="centered-menu-bottom-bar"][data-menu-bottom-bar-align="left"] #mobile-cart-link .cart-wrap {
          right: 3px;
        }

				#header-outer[data-format="centered-menu-bottom-bar"]:not([data-menu-bottom-bar-align="left"]) #top .span_3 .slide-out-widget-area-toggle.mobile-icon {
					display: flex;
					display: -webkit-flex;
					margin-left: 13px;
					align-items: center;
					-webkit-align-items: center;
				}

				body[data-user-set-ocm="off"] #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3 .slide-out-widget-area-toggle.mobile-icon {
					margin-left: 23px;
				}

				#header-outer[data-format="centered-menu-bottom-bar"] #top .span_3 .slide-out-widget-area-toggle.mobile-icon > div {
				 display: flex; display: -webkit-flex;
	       display: -ms-flexbox;
				 -webkit-align-items: center;
				 -ms-align-items: center;
				 -ms-flex-align: center;
				 align-items: center;
				 height: 100%;
			 }

				body.material #header-outer[data-using-secondary="1"][data-format="centered-menu-bottom-bar"][data-condense="true"]:not([data-format="left-header"]) {
					margin-top: 0;
				}

				#header-outer[data-format="centered-menu-bottom-bar"] #top .span_3 .right-side ul .slide-out-widget-area-toggle.mobile-icon {
					display: block!important;
				}
				body #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"] #top .span_3 {
					text-align: center;
				}

				body #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"] {
					position: fixed!important;
				}

        body:not(.admin-bar) #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"] {
          top: 0px!important;
        }
				body.admin-bar #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"] {
					top: 32px!important;
				}

				#header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"].fixed-menu #top nav >.buttons,
				#header-outer[data-has-menu="true"]:not([data-format="centered-menu-bottom-bar"]) #top .span_3 #logo img {
					transform: none!important;
				}

				body #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"]:not([data-menu-bottom-bar-align="left"]) #top .span_9 {
					display: none;
				}';

			} // end conditional for centered menu bottom bar

			echo '
			body.ascend #header-outer[data-full-width="false"] .cart-menu {
				border-left: none;
			}

			#top nav ul .slide-out-widget-area-toggle {
				display: none!important;
			}


	 		#header-outer[data-format="centered-menu"] #top .span_9 nav .sf-menu,
	 		#header-outer[data-format="centered-logo-between-menu"] #top .span_9 nav .sf-menu,
      #header-outer[data-format="centered-logo-between-menu"] #top .span_9 nav .sf-menu:not(.buttons),
	 		#header-outer[data-format="centered-menu-under-logo"] #top .span_9 nav {
	 			-webkit-justify-content: flex-end;
			    -moz-justify-content: flex-end;
			    -ms-flex-pack: flex-end;
			    -ms-justify-content: flex-end;
			    justify-content: flex-end;
	 		}

			#header-outer[data-format="centered-logo-between-menu"] #top nav > .buttons {
				position: relative;
			}

			body #header-outer[data-format="centered-logo-between-menu"] #top #logo,
      body[data-slide-out-widget-area-style="slide-out-from-right"]:not(.material) #header-outer[data-format="centered-logo-between-menu"] #top #logo {
				transform: none!important;
			}

 	    #header-outer:not([data-format="centered-menu-bottom-bar"]) #top .span_9 > .slide-out-widget-area-toggle,
			#slide-out-widget-area .mobile-only:not(.nectar-header-text-content) {
        display: -webkit-flex;
				display: flex!important;
        -webkit-align-items: center;
        align-items: center;
				transition: padding 0.2s ease;
			}

      #slide-out-widget-area.fullscreen .mobile-only,
      #slide-out-widget-area.fullscreen-alt .mobile-only {
        justify-content: center;
      }
      #slide-out-widget-area.fullscreen .mobile-only:not(.nectar-header-text-content),
      #slide-out-widget-area.fullscreen-alt .mobile-only:not(.nectar-header-text-content) {
        display: block!important;
      }

	 		#header-outer[data-has-menu="true"] #top .span_3,
			body #header-outer[data-format="centered-menu-under-logo"] .span_3 {
			    text-align: left;
			    left: 0;
			    width: auto;
			    float: left;
			}

			#header-outer[data-format="centered-menu-under-logo"] #top .span_9 ul #social-in-menu a {
				margin-bottom: 0;
			}

			#header-outer[data-format="centered-menu-under-logo"] #top .span_9 nav >.buttons {
				padding-bottom: 0;
			}

			body.material #header-outer[data-format="centered-menu-under-logo"] #top .span_9 {
			    margin-left: auto;
			}

			body.material #header-outer[data-format="centered-menu-under-logo"] #top .span_9 ul #social-in-menu a,
		  body.material #header-outer[data-format="centered-menu-under-logo"] #top .span_9 nav >.buttons {
				margin-bottom: 0;
				padding-bottom: 0;
			}

			body.material #header-outer[data-format="centered-menu-under-logo"] #top .row .span_9,
			body.material #header-outer[data-format="centered-menu-under-logo"] #top .row .span_3,
			body.ascend #header-outer[data-format="centered-menu-under-logo"] #top .row .span_9,
			body.ascend #header-outer[data-format="centered-menu-under-logo"] #top .row .span_3,
			body.original #header-outer[data-format="centered-menu-under-logo"] #top .row .span_9,
			body.original #header-outer[data-format="centered-menu-under-logo"] #top .row .span_3 {
				    display: -webkit-flex;
				    display: -ms-flexbox;
				    display: flex;
			}

			body #header-outer[data-format="centered-menu-under-logo"] .row {
				-webkit-flex-direction: row;
			    -ms-flex-direction: row;
			    -moz-flex-direction: row;
			    flex-direction: row;
			}

			#header-outer[data-format="centered-menu-under-logo"] #top #logo{
			  display: -webkit-flex;
			  display: -ms-flexbox;
			  display: flex;
			  -webkit-align-items: center;
			  align-items: center;
			}

			body #header-outer[data-format="centered-menu-under-logo"] #top #logo .starting-logo {
				left: 0;
				-webkit-transform: none;
				transform: none;
			}

			body #header-outer[data-format="centered-menu-under-logo"] #top #logo img {
				margin: 0
			}

			#header-outer[data-format="centered-menu-under-logo"] #top #logo {
				text-align: left;
			}

			.cart-outer, body #header-outer[data-full-width="false"] .cart-outer {
			    display: block;
			}

			#header-outer[data-format="centered-logo-between-menu"] #top nav .sf-menu > li {
				float: left!important;
			}


			body[data-full-width-header="false"] #slide-out-widget-area.slide-out-from-right-hover .slide_out_area_close {
				display: none;
			}

			body[data-slide-out-widget-area-style="slide-out-from-right-hover"][data-slide-out-widget-area="true"][data-user-set-ocm="off"] #header-outer[data-full-width="false"][data-cart="false"] header > .container {
			    max-width: 100%!important;
			    padding: 0 28px !important;
			}

			body[data-full-width-header="false"][data-cart="true"] .slide-out-hover-icon-effect.small {
				right: 28px!important;
			}

			body[data-full-width-header="false"][data-cart="true"] .slide-out-widget-area-toggle .lines-button.x2.no-delay .lines:before,
			body[data-full-width-header="false"][data-cart="true"] .slide-out-widget-area-toggle .lines-button.x2.no-delay .lines:after,
			body[data-full-width-header="false"][data-cart="true"] .slide-out-hover-icon-effect.slide-out-widget-area-toggle .no-delay.lines-button.no-delay:after {
				-webkit-transition: none!important;
				transition: none!important;
			}

			body:not(.mobile) #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
			body:not(.mobile) #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
			body:not(.mobile) #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle .lines:after {
				background-color: '.esc_attr($starting_color).'!important;
				opacity: '.esc_attr($starting_opacity).';
			}
			body:not(.mobile) #header-outer.transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
			body:not(.mobile) #header-outer.transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
			body:not(.mobile) #header-outer.transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle .lines:after {
				background-color: #000!important;
				opacity: '.esc_attr($starting_opacity).';
			}
			body:not(.mobile) #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle:hover .lines-button:after,
			body:not(.mobile) #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle:hover .lines:before,
			body:not(.mobile) #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle:hover .lines:after {
				opacity: 1;
			}

			body #top .span_9 > .slide-out-widget-area-toggle.mobile-icon a:hover .lines:after,
			body #top .span_9 > .slide-out-widget-area-toggle.mobile-icon a:hover .lines-button:after,
			body #top .span_9 > .slide-out-widget-area-toggle.mobile-icon a:hover .lines:before {
				background-color: '.esc_attr($mobile_menu_hover).'!important;
			}

			body:not(.mobile) #header-outer.light-text > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
			body:not(.mobile) #header-outer.light-text > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
			body:not(.mobile) #header-outer.light-text > #top .span_9 > .slide-out-widget-area-toggle .lines:after {
				background-color: #fff!important;
			}

      body[data-user-set-ocm="off"] #slide-out-widget-area.fullscreen-split,
      body[data-user-set-ocm="off"] #slide-out-widget-area-bg.fullscreen-split {
        display: block;
      }

	 	}';
	 }

	 // Material boxed desktop off canvas menu.
	 if(!empty($nectar_options['boxed_layout']) && $nectar_options['boxed_layout'] === '1')  {
	 	$boxed_max_width = 1200;

	 	if(!empty($nectar_options['responsive']) && $nectar_options['responsive'] === '1' && !empty($nectar_options['ext_responsive']) && $nectar_options['ext_responsive'] === '1') {
	 		$boxed_max_width = 1400;

	 		if ( !empty($nectar_options['max_container_width']) ) {
				$boxed_max_width = $nectar_options['max_container_width'];
		 	}
	 	}

	 	echo '@media only screen and (min-width: '.esc_attr($boxed_max_width).'px) {
	 		.material .ocm-effect-wrap.material-ocm-open #boxed {
	 			margin-left: auto;
	 			margin-right: 80px;
	 		}
	 	}';
	 }

	 // Custom header bg opacity for light/dark.
	 if( !empty($nectar_options['header-bg-opacity']) && !empty($nectar_options['header-color']) ) {

	 	if( $nectar_options['header-color'] === 'light' || $nectar_options['header-color'] === 'dark' ) {

	 		 if( $headerFormat !== 'left-header' ) {

				 $navBGColor = ($nectar_options['header-color'] === 'light') ? 'ffffff' : '000000';
				 $colorR = hexdec( substr( $navBGColor, 0, 2 ) );
				 $colorG = hexdec( substr( $navBGColor, 2, 2 ) );
				 $colorB = hexdec( substr( $navBGColor, 4, 2 ) );
				 $colorA = ($nectar_options['header-bg-opacity'] != '100') ? '0.'.esc_attr($nectar_options['header-bg-opacity']) : esc_attr($nectar_options['header-bg-opacity']);

				 echo 'body #header-outer,
				 body[data-header-color="dark"] #header-outer {
					 background-color: rgba('.$colorR.','.$colorG.','.$colorB.','.$colorA.');
				 }';

				 // Material search.
				 echo '.material #header-outer:not(.transparent) .bg-color-stripe {
					 display: none;
				 }';
			}
		}
	}

	if(!empty($nectar_options['header-dropdown-opacity']) && $nectar_options['header-dropdown-opacity'] !== '100' && !empty($nectar_options['header-color'])) {

		if($nectar_options['header-color'] === 'light' || $nectar_options['header-color'] === 'dark') {

			 $dropdownBGColor = '1c1c1c';

	 		 if( $nectar_options['header-color'] === 'light' ) {
				 $dropdownBGColor = 'ffffff';
			 }

			 $colorR = hexdec( substr( $dropdownBGColor, 0, 2 ) );
			 $colorG = hexdec( substr( $dropdownBGColor, 2, 2 ) );
			 $colorB = hexdec( substr( $dropdownBGColor, 4, 2 ) );
			 $colorA = ($nectar_options['header-dropdown-opacity'] != '100') ? '0.'.esc_attr($nectar_options['header-dropdown-opacity']) : esc_attr($nectar_options['header-dropdown-opacity']);

			 echo '
			 #search-outer .ui-widget-content,
			 body:not([data-header-format="left-header"]) #top .sf-menu li ul,
			 #header-outer nav > ul > .megamenu > .sub-menu,
			 body #header-outer nav > ul > .megamenu > .sub-menu > li > a,
			 #header-outer .widget_shopping_cart .cart_list a,
			 #header-secondary-outer ul ul li a,
			 #header-outer .widget_shopping_cart .cart_list li,
			 .woocommerce .cart-notification,
			 #header-outer .widget_shopping_cart_content {
					background-color: rgba('.$colorR.','.$colorG.','.$colorB.','.$colorA.')!important;
				}';
		}
	}



  /*-------------------------------------------------------------------------*/
  /* 1.5. Megamenu Removes Transparent
  /*-------------------------------------------------------------------------*/
  if(isset($nectar_options['header-megamenu-remove-transparent']) &&
    !empty($nectar_options['header-megamenu-remove-transparent']) &&
    '1' === $nectar_options['header-megamenu-remove-transparent'] &&
    isset($nectar_options['transparent-header']) && '1' === $nectar_options['transparent-header'] ) {

    echo '
    #header-outer.no-transition,
    #header-outer.no-transition a:not(#logo),
    #header-outer.no-transition img,
    #header-outer.no-transition a.no-image,
    #header-outer.no-transition .icon-salient-search,
    #header-outer.no-transition .icon-salient-m-user,
    #header-outer.no-transition .icon-salient-cart,
    .ascend #header-outer.no-transition .has_products .cart-menu .cart-icon-wrap .icon-salient-cart,
    #header-outer.no-transition #top nav ul .slide-out-widget-area-toggle a .lines,
    #header-outer.no-transition #top nav ul .slide-out-widget-area-toggle a .lines:before,
    #header-outer.no-transition #top nav ul .slide-out-widget-area-toggle a .lines:after,
    .material #header-outer.no-transition #top .slide-out-widget-area-toggle a .lines-button:after,
    #header-outer.no-transition .lines-button:after,
    #header-outer.no-transition #top nav ul li a > .sf-sub-indicator i,
    #header-outer.no-transition #top nav ul #search-btn a:after,
    #header-outer.no-transition #top nav ul #nectar-user-account a:after,
    #header-outer.no-transition #top nav ul .slide-out-widget-area-toggle a:after,
    #header-outer.no-transition .cart-menu:after,
    #header-outer.no-transition #top nav >ul >li[class*="button_bordered"] >a:not(:hover):before,
    #header-outer.no-transition.transparent #top nav >ul >li[class*="button_bordered"] >a:not(:hover):before,
    #header-outer.no-transition .nectar-header-text-content {
      -webkit-transition:none!important;
      transition:none!important
    }
    .material #header-outer:not([data-transparent-header="true"]):not([data-format="left-header"]).no-transition #logo img,
    .material #header-outer:not([data-transparent-header="true"]):not([data-format="left-header"]).no-transition .logo-spacing img {
      -webkit-transition: height 0.15s ease!important;
      transition: height 0.15s ease!important;
    }
    .material #header-outer:not([data-format="left-header"]).no-transition #logo img,
    .material #header-outer:not([data-format="left-header"]).no-transition .logo-spacing img {
      -webkit-transition: height 0.32s ease!important;
      transition: height 0.32s ease!important;
    }
    #header-outer.no-transition li[class*="button_"] a:hover{
      -webkit-transition:opacity 0.2s ease,color 0.2s ease!important;
      transition:opacity 0.2s ease,color 0.2s ease!important
    }
    #header-outer[data-lhe="animated_underline"].no-transition #top nav >ul >li:not([class*="button_"]) >a .menu-title-text:after{
      -webkit-transition:-webkit-transform .3s ease-out;
      transition:transform .3s ease-out
    }
    ';
  }

  /*-------------------------------------------------------------------------*/
  /* 1.6. Dark Color Scheme
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['header-color']) &&
      !empty($nectar_options['header-color']) &&
      'dark' === $nectar_options['header-color'] ) {
    echo '
     #header-space {
      background-color: #000;
    }
     #header-outer,
     #search-outer{
      background-color:#000;
      -webkit-box-shadow:none;
    	box-shadow:none;
    }

     #top nav ul li a,
     #search-outer input,
     #top #logo,
     #top nav ul #nectar-user-account a span,
     #top nav ul #search-btn a span,
     #header-outer .cart-menu .cart-icon-wrap .icon-salient-cart,
     .nectar-mobile-only.mobile-header {
      color:#A0A0A0
    }

     #top nav ul .slide-out-widget-area-toggle a .lines,
     #top nav ul .slide-out-widget-area-toggle a .lines:after,
     #top nav ul .slide-out-widget-area-toggle a .lines:before,
     #top nav ul .slide-out-widget-area-toggle .lines-button:after{
      background-color:#A0A0A0
    }
    body:not([data-header-format="left-header"]) #top nav >ul >.megamenu >ul >li >a,
    body:not([data-header-format="left-header"]) #top nav >ul >.megamenu >ul >li >ul >.has-ul >a {
    	color: #fff;
    }
     #header-secondary-outer .sf-menu li ul,
    body:not([data-header-format="left-header"]) #header-outer .sf-menu li ul,
     #header-outer nav >ul >.megamenu >.sub-menu,
     #header-outer .widget_shopping_cart .cart_list li,
     #header-outer .cart-notification,
     #header-outer .widget_shopping_cart_content {
      background-color:#000
    }
    ';
  }

  /*-------------------------------------------------------------------------*/
  /* 1.7. Social Icons
  /*-------------------------------------------------------------------------*/
  $social_media_list = nectar_get_social_media_list();
  foreach ($social_media_list as $network_name => $icon_arr) {

    if ( isset( $nectar_options[ 'use-' . $network_name . '-icon-header' ] ) &&
         $nectar_options[ 'use-' . $network_name . '-icon-header' ] === '1' ||
         isset( $nectar_options[ $network_name . '-url' ] ) &&
         !empty($nectar_options[ $network_name . '-url' ]) ) {

           echo '#header-outer #social-in-menu .'.esc_attr($icon_arr['icon_class']).':after,
           .material #slide-out-widget-area.slide-out-from-right .off-canvas-social-links .'.esc_attr($icon_arr['icon_class']).':after{
             content:"'.esc_attr($icon_arr['icon_code']).'"
           }';
    }
  }

  /*-------------------------------------------------------------------------*/
  /* 1.8. AJAX Search
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['header-disable-search']) &&
      '1' !== $nectar_options['header-disable-search'] ||
      isset($nectar_options['header-disable-search']) &&
      empty($nectar_options['header-disable-search']) ) {

      if( isset($nectar_options['header-disable-ajax-search']) &&
         '1' !== $nectar_options['header-disable-ajax-search'] ||
         isset($nectar_options['header-disable-ajax-search']) &&
         empty($nectar_options['header-disable-ajax-search'])) {

           echo '#search-outer .ui-widget-content{
             background-color:#1F1F1F;
             border:none;
             border-radius:0;
             -webkit-border-radius:0;
             background-image:none
           }
           #search-outer .ui-widget-content li:hover,
           .ui-state-hover,
           .ui-widget-content .ui-state-hover,
           .ui-widget-header .ui-state-hover,
           .ui-state-focus,
           .ui-widget-content .ui-state-focus,
           .ui-widget-header .ui-state-focus {
             background-color:#272727;
             cursor:pointer
           }
           #search-outer .ui-widget-content li:hover a{
             background-image:none
           }
           #search-outer .ui-widget-content li:last-child a{
             border:none
           }
           #search-outer .ui-widget-content li a{
             border-bottom:1px solid rgba(255,255,255,0.1)
           }
           #search-outer .ui-widget-content img,
           #search-outer .ui-widget-content i{
             width:40px;
             height:auto;
             float:left;
             margin-right:15px
           }
           #search-outer .ui-widget-content i{
             line-height:40px;
             font-size:22px;
             background-color:rgba(255,255,255,0.1);
             border-radius:0
           }
           .ui-widget{
             font-family:Verdana,Arial,sans-serif;
             font-size:1.1em
           }
           .ui-menu{
             display:block;
             float:left;
             list-style:none outside none;
             margin:0;
             padding:2px
           }
           .ui-autocomplete{
             cursor:default;
             position:absolute
           }
           .ui-menu .ui-menu-item a{
             display:block;
             line-height:1.5;
             padding:0.2em 0.4em;
             text-decoration:none
           }
           #search-outer .ui-widget-content{
             width:100%!important;
             left:0!important
           }

           #search-outer .ui-widget-content li{
             line-height:14px;
             clear:left;
             width:100%;
             display:block;
             float:left;
             margin:0
           }
           #search-outer .ui-widget-content li .desc{
             position:relative;
             line-height:14px;
             font-size:11px
           }
           #search-outer .ui-widget-content li a{
             color:#CCC;
             line-height:1.4em;
             transition:none;
             -webkit-transition:none;
             padding:15px 15px
           }';

      }


  }

  /*-------------------------------------------------------------------------*/
  /* 1.9. Button Styling
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['header-button-styling']) ) {

      if( 'shadow_hover_scale' === $nectar_options['header-button-styling'] ||
          'hover_scale' === $nectar_options['header-button-styling'] ) {

        echo '#header-outer[data-header-button_style*="hover_scale"] #top nav >ul >li[class*="button_solid_color"] >a:before,
        #header-outer[data-header-button_style*="hover_scale"] .slide-out-widget-area-toggle[data-custom-color="true"] a:before {
        	-webkit-transition: transform 0.3s ease;
        	transition: transform 0.3s ease;
        	transform-origin: top;
        }

        #header-outer li[class*="menu-item-btn-style"] > a:hover:before {
          transform: scale(1.065) translateY(-50%)!important;
        }

        #header-outer li[class*="menu-item-btn-style"] > a:hover:after {
          transform: scale(1.07) translateY(-50%)!important;
        }

        #header-outer[data-header-button_style*="hover_scale"]:not([data-format="centered-menu-under-logo"]):not([data-format="centered-menu-bottom-bar"]) #top nav li[class*="button_solid_color"] >a:hover:before,
        #header-outer[data-header-button_style*="hover_scale"]:not([data-format="centered-menu-under-logo"]):not([data-format="centered-menu-bottom-bar"]) #top nav ul .slide-out-widget-area-toggle[data-custom-color="true"] a:hover:before  {
        	-webkit-transform: scale(1.07) translateY(-50%);
          transform: scale(1.07) translateY(-50%);
        }
        #header-outer[data-header-button_style*="hover_scale"][data-format="centered-menu-bottom-bar"] #top nav >ul >li[class*="button_"] >a:hover:before,
        #header-outer[data-header-button_style*="hover_scale"][data-format="centered-menu-bottom-bar"] #top nav ul .slide-out-widget-area-toggle[data-custom-color="true"] a:hover:before{
        	-webkit-transform: scale(1.07) translateY(-9px);
          transform: scale(1.07) translateY(-9px);
        }';
      }
      if( 'shadow_hover_scale' === $nectar_options['header-button-styling'] ) {
        echo '#header-outer[data-header-button_style="shadow_hover_scale"] #top nav >ul >li[class*="button_solid_color"] >a:before,
        #header-outer li[class*="menu-item-btn-style"] > a:hover:after,
        #header-outer[data-header-button_style="shadow_hover_scale"] .slide-out-widget-area-toggle[data-custom-color="true"] a:before {
        	box-shadow: 0px 10px 25px rgba(0,0,0,0.13);
        }';
      }
  }


  // Header styles based on icons active
  $woo_cart = ( function_exists( 'is_woocommerce' ) && isset( $nectar_options['enable-cart'] ) && $nectar_options['enable-cart'] === '1') ? 'true' : 'false';  
  if( in_array(	NectarThemeManager::$header_format, array('centered-logo-between-menu-alt') ) ) {
		$has_main_menu = 'true';
	}

  $ocm_menu_btn_bg_color = 'false';
  $full_width_header     = ( isset( $nectar_options['header-fullwidth'] ) && $nectar_options['header-fullwidth'] === '1' ) ? 'true' : 'false';

  if( isset($nectar_options['header-slide-out-widget-area-menu-btn-bg-color']) &&
    !empty( $nectar_options['header-slide-out-widget-area-menu-btn-bg-color'] ) ) {

      //// Ascend full width does not support custom OCM coloring.
      $ocm_menu_btn_color_non_compatible = ( 'ascend' === $theme_skin && 'true' === $full_width_header ) ? true : false;

      if( false === $ocm_menu_btn_color_non_compatible ) {
        $ocm_menu_btn_bg_color = 'true';
      }

  }

  if( 'false' === $header_search && 'false' === $has_main_menu && 'false' === $woo_cart ) {
    
    // 2000 of style.css
    echo '
    body[data-header-search="false"]:not(.mobile) #header-outer[data-has-menu="false"][data-cart="false"] #social-in-menu i{
      font-size:20px;
      width:38px;
      line-height:26px;
      height:26px;
      margin-bottom:-3px
    }

    body[data-header-search="false"]:not(.mobile) #header-outer[data-has-menu="false"][data-cart="false"] .lines-button {
      line-height:0;
      font-size:0
    }
    
    body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .lines-button.close{
      -webkit-transform:none;
      transform:none;
    }

    body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle.mobile-icon .lines:before{
      top:6px
    }
    ';


    if( 'false' === $ocm_menu_btn_bg_color ) {

      echo '
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines,
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:before,
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:after{
        height:4px;
        width:2.1rem;
        border-radius:1px;
      }
      
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:before{
        top:9px
      }
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:after{
        top:-9px
      }

      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top nav ul .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) {
        width:34px
      }
      ';
    

      //7909 of style.css
      echo '
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) > span {
        height: auto;
      }
      body[data-header-search="false"].material #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) > span {
        height: 22px;
      }
      
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines-button:after{
        height:3px;
        top:0;
        width:2rem;
        border-radius:2px
      }
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines,
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:before,
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:after{
        height:3px;
        width:2rem;
        border-radius:2px
      }
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines:before{
        top:9px
      }
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .close .lines:before{
        top:10px
      }
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines-button.close .lines:before{
        transform:translateY(-9px) rotateZ(-45deg)
      }
      body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]):not(.mobile-icon) a:not(.using-label) .lines-button.close .lines:after{
        transform:translateY(10px) rotateZ(45deg)
      }
      ';

      // in material
      if( 'material' === $theme_skin ) {
        echo '
        @media only screen and (min-width: 1000px) {
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) span:not(.close-line) {
            width: 30px;
            overflow: hidden;
          }
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) span .lines-button.hover-effect {
            left: -40px;
            margin-top: -3px;
          }
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines:after {
            top: -8px;
          }
        
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines:before {
            top: 8px;
          }

          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines-button:after,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines:before,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines:after {
            border-radius: 0!important;
            height: 2px;
          }

          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label):hover .lines-button:after,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label):hover .lines:before,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label):hover .lines:after,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a.effect-shown:not(.using-label) .lines-button:after,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a.effect-shown:not(.using-label) .lines:before,
          body[data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .slide-out-widget-area-toggle:not([data-custom-color="true"]) a.effect-shown:not(.using-label) .lines:after {
            transform: translateX(40px);
          }

          body.material[data-header-search="false"]:not(.mobile) #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines:before,
          body.material[data-header-search="false"]:not(.mobile) #header-outer[data-has-menu="false"][data-cart="false"] .slide-out-widget-area-toggle:not([data-custom-color="true"]) a:not(.using-label) .lines:before {
            width: 1.4rem;
          }

          body[data-slide-out-widget-area-style*="fullscreen"][data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .menu-push-out .lines-button:after,
          body[data-slide-out-widget-area-style*="fullscreen"][data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .menu-push-out .lines:before,
          body[data-slide-out-widget-area-style*="fullscreen"][data-header-search="false"] #header-outer[data-has-menu="false"][data-cart="false"] #top .menu-push-out .lines:after {
            transform: translateX(75px)!important;
          }

        }
        ';
      }

    }
  }
  
  
  /*-------------------------------------------------------------------------*/
  /* 1.10. Text Content
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['header-text-widget']) && !empty($nectar_options['header-text-widget']) ) {
    echo '
    #header-outer[data-format="left-header"] .buttons.sf-menu .nectar-header-text-content-wrap {
        float: none;
        display: block;
    }
    #header-outer .nectar-header-text-content {
    	line-height: 1.3em;
    	padding-top: 15px;
    	padding-bottom: 15px;
    	color: #999;
    	align-self: center;
    }

    #header-outer[data-format="default"] .nectar-header-text-content,
    #header-outer[data-format="centered-logo-between-menu"] .nectar-header-text-content {
     padding-left: 0;
     text-align: center;
    }
    #header-outer[data-format="menu-left-aligned"] .nectar-header-text-content {
    	text-align: right;
    }
    #header-outer .buttons > .nectar-header-text-content:last-child {
    	 padding-right: 0;
     }

    #header-outer .nectar-header-text-content h2,
    #header-outer .nectar-header-text-content h3,
    #header-outer .nectar-header-text-content h4,
    #header-outer .nectar-header-text-content h5,
    #header-outer .nectar-header-text-content h6 {
    	margin-bottom: 0;
    }

    #header-outer .nectar-header-text-content {
    	transition: color 0.2s ease, opacity 0.2s ease;
    }
    #header-outer .nectar-header-text-content a {
    	transition: none;
    	position: relative;
    	display: inline-block;
    }
    #header-outer[data-lhe="default"]:not(.transparent) .nectar-header-text-content a:hover {
    	transition: color 0.2s ease;
    }
    #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:after {
    	transition: transform .3s ease-out, border-color .3s ease-out;
      position: absolute;
      display: block;
      bottom: -3px;
    	height: 2px;
      left: 0;
      width: 100%;
      transform: scaleX(0);
      background-color: #000;
      content: "";
    }
    body[data-header-color="dark"] #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:after {
    	background-color: #fff;
    }
    #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:hover:after {
    	transform: scaleX(1);
    }
    #header-outer .nectar-header-text-content * {
    	color: inherit;
    }
    #header-outer #mobile-menu .nectar-header-text-content div {
    	color: #000;
    }';
  }

  
 
  // Hide header menu links when original OCM is open
  if( in_array($theme_skin, array('original','ascend')) && 
      'slide-out-from-right' === $side_widget_class && 
      $user_set_side_widget_area === '1' ) {
    echo '
    #header-outer.side-widget-open.hidden-menu #top nav >.sf-menu li,
    #header-outer.hidden-menu-items #top nav >.sf-menu li:not(#social-in-menu){
      transition:opacity 0.75s ease
    }
    body:not(.material) #header-outer[data-format="centered-menu"].side-widget-open #top nav >.sf-menu:not(.buttons) li,
    body:not(.material) #header-outer.side-widget-open.hidden-menu #top nav >.sf-menu:not(.buttons) li,
    body:not(.material) #header-outer.side-widget-open.hidden-menu-items #top nav >.sf-menu:not(.buttons) li:not(#social-in-menu){
      opacity:0
    }';
  }

  
  /*-------------------------------------------------------------------------*/
  /* 1.11. Mobile Layout
  /*-------------------------------------------------------------------------*/
  
  if( 'centered-menu' === $mobile_header_layout ) {

    $header_breakpoint = ( 'left-header' !== $headerFormat && $mobile_breakpoint != 1000 ) ? $mobile_breakpoint : '999';
    if( $has_main_menu !== 'true' ) {
      $header_breakpoint = '999';
    }

    echo '@media only screen and (max-width: '.esc_attr($header_breakpoint).'px) {

      body #header-outer.transparent #top .span_3 #logo[data-supplied-ml-starting="true"] img.mobile-only-logo.starting-logo {
        transform: translateX(-50%);
        left: 50%;
      }
      #header-outer[data-has-menu][data-format] #top .row {
        display: flex;
        justify-content: center;
      }
      #header-outer[data-format="centered-menu-under-logo"] .row,
      #header-outer[data-format="centered-menu-bottom-bar"] .row {
        flex-direction: row;
      }
      #header-outer[data-has-menu][data-format] #top .row .span_3,
      body[data-header-format] #header-outer[data-format="centered-menu-under-logo"] #top .row .span_3 {
        margin: 0;
        float: none;
        z-index: 30000;
        width: auto!important;
        position: relative;
        left: 0;
      }
      #header-outer[data-has-menu][data-format].material-search-open #top .row .span_3 #logo {
        pointer-events: none;
      }
      #header-outer #top .row .col.span_9,
      body[data-header-format] #header-outer[data-format="centered-menu-bottom-bar"] #top .row .span_9,
      body[data-header-format] #header-outer[data-format="centered-menu-under-logo"] #top .row .span_9 {
        width: 100%!important;
        display: flex!important;
        flex-direction: row;
        align-items: center;
      }
      #header-outer #top .row .col.span_9 .slide-out-widget-area-toggle {
        order: 1;
        padding: 0 10px 0 0;
      }
      #header-outer #top .col.span_9 .mobile-search {
        order: 2;
      }
      #header-outer #top .col.span_9 .nectar-mobile-only.mobile-header {
        order: 5;
        margin-left: 8px;
      }
      #header-outer #top .col.span_9 .mobile-user-account {
        order: 3;
        margin-left: auto;
      }
      #header-outer #top .col.span_9 #mobile-cart-link {
        order: 4;
        padding-right: 0;
      }
      #header-outer #mobile-cart-link .cart-wrap {
        right: -9px;
      }
      body[data-cart="false"] #header-outer #top .col.span_9 .mobile-user-account {
        padding-right: 0;
      }

      body:not([data-user-account-button="true"])[data-cart="false"] #header-outer #top .col.span_9 .mobile-search,
      body[data-cart="false"][data-user-account-button="false"][data-header-search="false"] #header-outer #top .col.span_9 .nectar-mobile-only.mobile-header {
        padding-right: 0;
        margin-left: auto;
      }
      
      #header-outer #top .col.span_9 .nectar-mobile-only.mobile-header ul > li:last-child {
        margin-right: 0;
      }

      body:not([data-user-account-button="true"]) #header-outer #top .col.span_9 #mobile-cart-link {
        margin-left: auto;
      }
      #header-outer .logo-spacing {
        display: none;
      }';


  echo '}'; // End media query.


  /* Custom mobile breakpoint specific */
  if( !empty($mobile_breakpoint) && $mobile_breakpoint != 1000 && $headerFormat !== 'left-header' && $has_main_menu === 'true' ) {

    $starting_opacity  = (isset($nectar_options['header-starting-opacity']) && !empty($nectar_options['header-starting-opacity'])) ? $nectar_options['header-starting-opacity'] : '0.75';
    $starting_color    = (empty($nectar_options['header-starting-color'])) ? '#ffffff' : $nectar_options['header-starting-color'];
	 	$mobile_menu_hover = $nectar_options["accent-color"];

	 	if( !empty($nectar_options['header-color']) && $nectar_options['header-color'] === 'custom' && !empty($nectar_options['header-font-hover-color'])) {
	 		$mobile_menu_hover = $nectar_options['header-font-hover-color'];
	 	}

    echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {';

      echo '#top .span_9 > .nectar-mobile-only {
        display: flex;
        align-items: center;
      }';

      // Centered Logo Between Menu Alt.
      if( $headerFormat === 'centered-logo-between-menu-alt' ) {
    	  echo '#header-outer #top > .container > .row > .right-aligned-menu-items {
    		  display: none;
    	  }
        #header-outer #top > .container > .row > .span_9 {
          pointer-events: all;
        }';
      }

      // Centered menu bottom bar is no longer two rows at this point.
      if( 'centered-menu-bottom-bar' === $headerFormat ) {
        $centerd_header_space = $logo_height + ($header_padding*2) + $extra_secondary_height;
    	  echo 'body #header-space {
    		  height: '. esc_attr($centerd_header_space) .'px;
    	  }';
      }

      // Coloring.
      if ( !nectar_is_contained_header() ) {
        echo '
        #header-outer > #top .span_9 > a > span,
        #header-outer > #top .span_9 > a > i {
          transition: color 0.25s ease;
        }
        #header-outer > #top .span_9 > a {
          transition: opacity 0.25s ease;
        }
        #header-outer.transparent > #top .span_9 > a:not(:hover) {
          opacity: '.esc_attr($starting_opacity).';
        }

        #header-outer:not(.transparent) > #top .span_9 > a:hover > span,
        #header-outer:not(.transparent) > #top .span_9 > a:hover > i {
          color: '.esc_attr($mobile_menu_hover).';
        }

        #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
        #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
        #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle .lines:after {
          background-color: '.esc_attr($starting_color).'!important;
          opacity: '.esc_attr($starting_opacity).';
        }
        #header-outer.transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
        #header-outer.transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
        #header-outer.transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle .lines:after {
          background-color: #000!important;
          opacity: '.esc_attr($starting_opacity).';
        }
        #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle:hover .lines-button:after,
        #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle:hover .lines:before,
        #header-outer.transparent > #top .span_9 > .slide-out-widget-area-toggle:hover .lines:after {
          opacity: 1;
        }

        body #top .span_9 > .slide-out-widget-area-toggle.mobile-icon a:hover .lines:after,
        body #top .span_9 > .slide-out-widget-area-toggle.mobile-icon a:hover .lines-button:after,
        body #top .span_9 > .slide-out-widget-area-toggle.mobile-icon a:hover .lines:before {
          background-color: '.esc_attr($mobile_menu_hover).'!important;
        }

        #header-outer.light-text > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
        #header-outer.light-text > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
        #header-outer.light-text > #top .span_9 > .slide-out-widget-area-toggle .lines:after {
          background-color: #fff!important;
        }';
      }

      // Alignment.
      echo '
      body #header-outer[data-format="centered-logo-between-menu"] #top #logo,
      body[data-slide-out-widget-area-style="slide-out-from-right"]:not(.material) #header-outer[data-format="centered-logo-between-menu"] #top #logo {
				transform: none!important;
			}

      body #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"] {
        position: fixed!important;
      }
      #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3:before {
        display: none;
      }
      body:not(.admin-bar) #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"] {
        top: 0px!important;
      }
      body.admin-bar #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"][data-condense="true"] {
        top: 32px!important;
      }
      body.material #header-outer[data-format="centered-menu-bottom-bar"][data-condense="true"].fixed-menu #search-outer {
        top: 0;
      }

      #top .col.span_9,
      #header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 {
        min-height: 0;
        width: auto!important;
        position: absolute!important;
        right: 0;
        top: 0;
        z-index: 2000;
        height: 100%;
        padding-left: 0!important;
        padding-right: 0!important;
    }

    #header-outer #top nav,
    #header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 > #logo,
    #header-outer[data-format="menu-left-aligned"] .row .right-aligned-menu-items {
        display: none!important;
    }
    body #header-outer[data-has-menu="true"][data-format="centered-menu-bottom-bar"]:not([data-menu-bottom-bar-align="left"]) #top .span_9 {
      display: flex;
    }
      #top #mobile-cart-link,
      #top .span_9 > .slide-out-widget-area-toggle,
      #top .mobile-search,
      #header-outer #top .mobile-user-account {
        display: flex;
        align-items: center;
        position: relative;
        width: auto;
        padding: 0 14px;
        top: auto;
        right: auto;
        margin-bottom: 0;
        margin-top: 0;
        line-height: 0;
        height: 100%;
        -webkit-transform: none;
        transform: none;
      }
      #header-outer #top .row .col.span_9 .slide-out-widget-area-toggle {
        padding-right: 14px;
      }

      .material #top .mobile-search .icon-salient-search {
        font-size: 20px;
        line-height: 20px;
        height: 20px;
      }
      body.material #header-outer #top .mobile-user-account .icon-salient-m-user{
        font-size: 20px;
        line-height: 22px;
        height: 20px;
      }
      body.material #header-outer #top #mobile-cart-link .icon-salient-cart {
        font-size: 20px;
        line-height: 22px;
        height: 21px;
      }
      body.material #header-outer #mobile-cart-link .cart-wrap {
          margin-top: -15px;
          right: -10px;
      }';

      // Display mobile only menu items.
      echo '
      #slide-out-widget-area .mobile-only:not(.nectar-header-text-content),
      #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container.mobile-only {
				display: block;
			}';

      // Simple dropdown.
      if( 'simple' === $side_widget_class ) {
        echo 'body[data-slide-out-widget-area-style="simple"] #header-outer #mobile-menu {
              top: 100%;
          }

          body[data-slide-out-widget-area-style="simple"][data-ext-responsive="true"] #header-outer[data-full-width="false"] #mobile-menu {
            padding: 0 90px;
          }';
      }

      // OCM.
      if( 'slide-out-from-right-hover' === $side_widget_class ) {
        echo '#slide-out-widget-area.slide-out-from-right-hover[data-dropdown-func="separate-dropdown-parent-link"] .off-canvas-menu-container.mobile-only {
          display: block!important;
        }
        body[data-full-width-header="false"] #slide-out-widget-area .slide_out_area_close {
          display: none!important;
        }';
      }

      if( 'fullscreen-split' === $side_widget_class ) {
        echo 'body[data-user-set-ocm="off"] #slide-out-widget-area.fullscreen-split,
              body[data-user-set-ocm="off"] #slide-out-widget-area-bg.fullscreen-split {
          display: block;
        }';
      }

    echo '}'; // Ending media query.

  } // End custom mobile breakpoint specific.

  } // End centered mobile layout.


  // Mobile Icons
  $ocm_menu_icon_display = (isset($nectar_options['header-slide-out-widget-area-icons-display'])) ? esc_html($nectar_options['header-slide-out-widget-area-icons-display']) : 'none';

  if( 'none' === $ocm_menu_icon_display ) {
    echo '.off-canvas-menu-container .nectar-menu-icon,
    .off-canvas-menu-container .nectar-menu-icon-img,
    #header-outer #mobile-menu .nectar-menu-icon,
    #header-outer #mobile-menu .nectar-menu-icon-img  {
      display: none;
    }';
  }
  else if( 'font_icons_only' === $ocm_menu_icon_display ) {
    echo '.off-canvas-menu-container .nectar-menu-icon-img,
    #header-outer #mobile-menu .nectar-menu-icon-img  {
      display: none;
    }';
  }
  else if( 'image_icons_only' === $ocm_menu_icon_display ) {
    echo '.off-canvas-menu-container .nectar-menu-icon,
    #header-outer #mobile-menu .nectar-menu-icon  {
      display: none;
    }';
  }


  /*-------------------------------------------------------------------------*/
  /* 1.12. Search Core
  /*-------------------------------------------------------------------------*/
  if( 'true' == $header_search ) {

    echo '
    body.original #header-outer[data-full-width="true"][data-remove-border="true"] #top nav ul #search-btn{
      margin-left:22px
    }
  
    @media only screen and (max-width: 999px) {
      #search-outer #search #close a:before,
      body[data-header-format="left-header"] #search-outer #search #close a:before,
      body.material #header-outer #search-outer #search #close a:before {
        height: 28px;
        width: 28px;
        margin: -14px 0 0 -14px;
      }
      .material #search-outer #search #close a span {
        font-size: 13px;
        height: 13px;
        line-height: 13px;
        top: 6px;
      }
      #search-outer #search #close a span {
        font-size: 16px;
        height: 16px;
        line-height: 16px;
      }
      .ascend #search-outer #search #close a span {
        top: 0;
      }
      .ascend #search-outer #search #close a {
        height: 20px;
      }
      #search-outer #search #close a {
        height: 14px;
      }
      #search-outer #search #close,
      body.material #header-outer #search-outer #search #close {
        top: 0;
        right: -5px;
      }
      #search-outer #search #close a,
      body.material #header-outer #search-outer #search #close a {
        right: 8px;
        top: 9px;
      }
      body.original #search-outer #search #close a {
        top: 50%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
      }
    }
    
    body[data-bg-header="true"][data-header-search="true"].ascend #header-outer.transparent[data-has-menu="false"] #top nav ul #search-btn a:after,
    body[data-bg-header="true"][data-header-search="true"].ascend #header-outer[data-has-menu="false"] #top nav ul #search-btn a:after,
    body[data-bg-header="true"][data-header-search="true"] #header-outer[data-has-menu="false"] #top nav ul #search-btn a:after{
      border:none;
      display:none
    }

    #search-outer{
      top:0;
      left:0;
      width:100%;
      height: 100%;
      position:absolute;
      z-index:10000;
      overflow:visible;
      display:none;
      background-color:#fff
    }
    
    #search-box{
      position:relative
    }
    #search-outer .container{
      overflow:visible;
      width: 100%;
    }
    #search-outer #search input[type=text]{
      width:100%;
      color:#888;
      font-size:43px;
      line-height:43px;
      position:relative;
      padding:0;
      background-color:transparent;
      border:0;
      -webkit-transition:none;
      transition:none;
      box-shadow:none;
      font-family:"Open Sans";
      font-weight:700;
      text-transform:uppercase;
      letter-spacing:1px
    }
    
    #search-outer >#search form{
      width:92%;
      float:left
    }
    #search-outer #search #close{
      list-style:none
    }
    #search-outer #search #close a{
      position:absolute;
      right:0;
      top:24px;
      display:block;
      width:24px;
      height:17px;
      line-height:22px;
      z-index:10
    }
    #search-outer #search #close a span:not(.close-line){
      color:#a0a0a0;
      font-size:18px;
      height:20px;
      line-height:19px;
      background-color:transparent;
      transition:color 0.2s ease
    }
    #search-outer >#search #close a:hover span{
      color:#000
    }
    #header-outer #search{
      position:relative
    }

    body #search-outer #search #close a span{
      font-size:20px
    }

    ';

    // responsive
    echo '
    @media only screen and (min-width : 1px) and (max-width : 999px) {

      .original #search-outer #search input[type="text"],
      body[data-header-format="left-header"]:not(.material) #search-outer #search input[type="text"] {
        font-size: 24px;
        border-bottom-width: 2px;
      }
    
      .original #search-outer .container {
         width: 100%;
      }
    
      body.material #search-outer #search form input[type="text"][name] {
        font-size: 16px;
        line-height: 40px;
        border-bottom-width: 2px;
        padding-right: 50px;
      }

      body.material #search-outer,
      .material #search-outer .bg-color-stripe {
        height: 30vh;
      }
    
      body.material #search-outer {
        min-height: 200px;
      }
    
      body.material #search-outer .col {
        margin-bottom: 0;
      }
    }

    @media only screen and (max-device-width: 2600px) {

      .ascend.using-mobile-browser #search {
        height: 100%;
      }
    
      .ascend.using-mobile-browser #search #search-box {
        top: 20px;
      }
    }';

    
    if( 'material' === $theme_skin ) {

      echo '@media screen and (max-width: 999px) {
        .material #header-outer.transparent .bg-color-stripe,
        .material #header-outer:not([data-permanent-transparent="1"]).transparent .bg-color-stripe,
        .material #header-outer[data-transparent-header].transparent .bg-color-stripe,
        .material #header-outer[data-transparent-header]:not([data-permanent-transparent="1"]).transparent .bg-color-stripe {
          height: 200px;
          top: 0;
        }
        body.material #search-outer,
        body.material #header-outer #search-outer {
          height: 200px;
          transform: translate3d(0,-200px,0);
          -webkit-transform: translate3d(0,-200px,0);
        }
        body.material #header-outer[data-using-secondary="1"] #search-outer {
          top: auto;
          margin-top: -13px;
        }
        html.material #search-outer .bg-color-stripe {
          height: 200px;
          top: 0;
        }
        #search-outer #search-box,
        #search-outer #search #close,
        #header-outer #search-outer #search-box,
        #header-outer #search-outer #search #close {
          -webkit-transform: translate3d(0,200px,0);
          transform: translate3d(0,200px,0);
        }
      }

      body.material #search-outer {
        background-color: transparent;
        height: 35vh;
        position: absolute;
        transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1), opacity 0.8s cubic-bezier(0.2, 1, 0.3, 1);
        transform: translate3d(0,-35vh,0);
        -webkit-transform: translate3d(0,-35vh,0);
        z-index: 2000!important;
        padding:0;
        top: -1px;
      }

      body.material[data-header-search="false"] #search-outer {
        visibility: hidden;
      }

      body.material #search-outer.perma-trans {
        position: fixed;
      }
      body.material.admin-bar #search-outer.perma-trans {
        top: 32px;
      }

      body.material #search-outer #search .container {
        height:auto!important;
        float:none;
        width:100%;
      }

      .material #header-outer.transparent .bg-color-stripe,
      .material #search-outer .bg-color-stripe {
        height: 35vh;
        top: 0;
      }
      body[data-header-color="dark"] #header-outer .bg-color-stripe,
      body[data-header-color="dark"].material #header-outer #search-outer:before {
        background-color: #000;
      }
      #header-outer .bg-color-stripe, 
      #search-outer .bg-color-stripe,
      #header-outer #search-outer:before {
        transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        display: block;
        background-color: #fff;
        -webkit-transform: scaleY(0);
        transform: scaleY(0);
        -webkit-transform-origin: top;
        transform-origin: top;
      }

      .material #header-outer #search-outer:before {
        content: "";
        -webkit-transform: none;
        transform: none;
        backface-visibility: hidden;
      }
      body.material[data-header-inherit-rc="true"] #header-outer #search-outer:before {
        display: none;
      }
      body.material[data-header-inherit-rc="true"] #search-outer {
        background-color: inherit;
      }
      #header-outer.material-search-open .bg-color-stripe, 
      #search-outer.material-open .bg-color-stripe {
        -webkit-transform: scaleY(1);
        transform: scaleY(1);
      }

      .material #search-outer {
        display: block;
        overflow: hidden;
      }
      #search-outer #search {
        max-width: 1200px;
        position: relative;
        z-index: 10;
        margin: 0 auto;
        height: 100%;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-align-items: center;
        -ms-align-items: center;
        -ms-flex-align: center;
        align-items: center;
      }
      .material #search-outer #search,
      .material #header-outer #search-outer #search {
        padding: 0;
      }
      #search-outer #search-box,
      #search-outer #search #close {
        transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1), opacity 0.8s cubic-bezier(0.2, 1, 0.3, 1);
        transform: translate3d(0,35vh,0);
        opacity: 0;
      }
      
      body.material #search-outer.material-open,
      body.material #header-outer #search-outer.material-open {
        transform: translate3d(0,0vh,0);
        -webkit-transform: translate3d(0,0vh,0);
      }
      #search-outer.material-open #search-box,
      #search-outer.material-open #search #close,
      #header-outer #search-outer.material-open #search-box,
      #header-outer #search-outer.material-open #search #close {
        transform: translate3d(0,0vh,0);
        opacity: 1;
      }
      body #search-outer #search input[type="text"] {
        height:auto!important;
        text-transform: none;
        color: #000;
        border-radius: 0;
        border-bottom: 2px solid #3452ff;
      }
      body #search-outer #search input[type="text"],
      body.material #search-outer #search form input[type="text"] {
        font-size:60px;
      }
      
      body.material #search-outer #search input[type="text"] {
        line-height: 60px;
      }
      
      @media only screen and (min-width: 1000px) {
        body #search-outer #search input[type="text"] {
          height: 90px!important;
        }
        body.material #search-outer #search #search-box input[type="text"] {
          line-height: 90px;
        }
      }

      body[data-header-color="custom"] #search-outer #search .span_12 span {
        opacity: 0.7;
      }
      
      body[data-header-color="light"] #header-outer #search-outer input::-webkit-input-placeholder {
        color: #000;
      }
      body[data-header-color="dark"] #header-outer #search-outer input::-webkit-input-placeholder {
        color: #fff;
      }
      
      #header-outer #search-outer input::-webkit-input-placeholder,
      #header-outer #search-outer input::-moz-placeholder {
        color: #888;
      }
      body.material #search-outer > #search form {
        width:100%;
        float:none
      }
      #header-outer.light-text #search-outer input[type="text"],
      body[data-header-color="dark"] #header-outer #search-outer input[type="text"] {
        border-color: #fff;
      }
      #header-outer.light-text #search-outer #search .span_12 span,
      body[data-header-color="dark"] #header-outer #search-outer #search .span_12 span {
        color: rgba(255,255,255,0.6);
      }
      #header-outer.light-text #search-outer #search #close a span,
      body[data-header-color="dark"] #header-outer #search-outer #search #close a span,
      body[data-header-color="dark"] .nectar-ajax-search-results .search-post-item,
      body[data-header-color="dark"] .nectar-ajax-search-results ul.products li.product {
        color: #fff;
      }
      #header-outer.light-text #search-outer input::-webkit-input-placeholder,
      body[data-header-color="dark"] #header-outer #search-outer input::-webkit-input-placeholder {
        color: rgba(255,255,255,0.5);
      }
      #header-outer.light-text #search-outer input::-moz-placeholder,
      body[data-header-color="dark"] #header-outer #search-outer input::-moz-placeholder {
        color: rgba(255,255,255,0.5);
      }
  
      #search-outer #search #close {
        position:absolute;
        right:40px
      }
      
      body[data-ext-responsive="false"].material #search-outer #search #close {
        right: 0;
      }
      body[data-ext-responsive="false"].material #search-outer #search #close a {
        right: 15px;
      }
      
      body.material #search-outer #search input[type="text"]{
        padding-right: 70px;
      }
      body.material #search-outer #search #close a {
        right:64px;
        top:16px;
      }

      [data-header-color="dark"] #search-outer #search #close a:before {
        background-color: rgba(255,255,255,.1);
      }
      #search-outer #search .span_12 span {
        display:block;
        color:rgba(0,0,0,0.4);
        margin-top:15px
      }
      @media only screen and (max-width: 999px) {
        #search-outer #search .span_12 span {
          display: none;
        }
      }
      
      body.material #search-outer #search #close a span {
        color:#000;
      }
      body #search-outer .ui-widget-content {
        top:90px
      }';

    }


    if( 'original' === $theme_skin ) {
      echo '
      .original #search-outer #search {
        height: 100%;
        display:-webkit-flex;
        display:flex;
        align-items: center;
      }
      @media only screen and (max-width: 999px) {
        #search-outer {
          height: '. (intval($mobile_logo_height) + 24) .'px;
          bottom: 0;
          top: auto;
        }
      }
      ';
    }

    if( 'ascend' === $theme_skin ) {
      echo '
      .ascend #search-outer {
        background-color:rgba(255,255,255,0.97);
        height: 100vh;
        position: fixed;
        -webkit-transform: scale(1,0);
        transform: scale(1,0);
        padding:0;
        z-index:10100;
        top: 0;
      }
      
      body.ascend #search-outer #search-box {
          transition: all 0.15s ease;
      }
      body.ascend #search-outer.small-nav #search-box {
          transition: all 0.32s ease;
      }
      
      @media only screen and (min-width: 691px) {
        body.ascend.admin-bar #search-outer {
          top: 32px;
        }
      }
      
      #search-box {
        top: 50%;
        left: 0;
        width: 100%;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        position: absolute;
      }
      body[data-header-color="dark"].ascend #search-outer {
        background-color:rgba(0,0,0,0.94);
      }
      body[data-header-color="dark"].ascend #search-outer #search input[type="text"],
      body[data-header-color="dark"].ascend #search-outer #search #close a span {
        color: #fff;
      }
      
      body.ascend #search-outer .container {
          height:auto!important;
          float:none;
          width:100%;
          padding:0 40px;
          position:static
      }
      #header-outer #search {
          position:static!important
      }
      body.ascend #search-outer #search input[type="text"] {
          color:#000;
          height:auto;
          font-size:80px;
          text-align:center
      }
      body.ascend #search-outer > #search form {
          width:100%;
          float:none
      }
      #search-outer > #search form,
      #search-outer #search .span_12 span {
          opacity:0;
          position:relative
      }
      #search-outer #search #close {
          position:absolute;
          top: 30px;
          right: 30px
      }
      .ascend #search-outer #search #close a span:not(.close-line){
          font-size:22px;
      }
      body.ascend #search-outer #search #close a {
          right:0;
          top:0;
          transition: all .47s cubic-bezier(0.3,1,0.3,0.95);
          -webkit-transition: all .47s cubic-bezier(0.3,1,0.3,0.95)
      }
      #search-outer #search #close a:hover {
          transform:rotate(90deg) translateZ(0);
          -webkit-transform:rotate(90deg) translateZ(0)
      }
      #search-outer #search .span_12 span {
          text-align:center;
          display:block;
          color:rgba(0,0,0,0.4);
          margin-top:15px
      }
      
      body.ascend #boxed #search-outer {
          width:auto;
          min-width:1200px;
          left:auto
      }
      
      @media only screen and (max-width: 999px) {
        body.ascend #boxed #search-outer {
            min-width:680px;
        }
      }
      
      @media only screen and (max-width: 690px) {
        body.ascend #boxed #search-outer {
            min-width:100%;
        }
      }
      body.ascend #search-outer #search #close a span {
          color:#000
      }
      body.ascend #search-outer .ui-widget-content {
          top:90px!important
      }

      @media only screen and (max-width: 999px) {
        #search-box {
          -webkit-transform: none;
          transform: none;
          top: 20%;
        }
        body.ascend #search-outer #search input[type="text"] {
          font-size: 40px;
        }
      }
      @media only screen and (max-width: 690px) {
        body.ascend #search-outer #search input[type="text"] {
          font-size: 28px;
        }
      }
      ';
    }
  } else {
    echo '#search-outer, #header-outer .bg-color-stripe {
      display: none;
    }';
  }

  /*-------------------------------------------------------------------------*/
  /* 1.13. Ext Search
  /*-------------------------------------------------------------------------*/

  if( true === $ext_search_active || nectar_is_contained_header() && 'material' === $theme_skin && 'true' === $header_search ) {

    if( $headerFormat !== 'left-header' ) {

      if( $headerFormat === 'centered-menu-bottom-bar' ) {
        echo '
        @media only screen and (min-width: '.esc_attr($mobile_breakpoint).'px) {

          .material #header-outer.transparent .bg-color-stripe {
            top: 0px;
            height: 225px;
          }
          .material #header-outer:not(.transparent) .bg-color-stripe {
            top: '.$header_space.'px;
            height: calc(225px - '.$header_space.'px);
          }
          .material #header-outer:not(.transparent).fixed-menu .bg-color-stripe {
            top: '.$header_space.'px;
            height: calc(225px - '. ($header_space - $material_header_space) .'px);
          }
          body.material [data-format="centered-menu-bottom-bar"][data-condense="true"].fixed-menu #search-outer {
            top: '.$material_header_space.'px;
          }
        }';

        if( $mobile_breakpoint != 1000 && $has_main_menu === 'true' ) {
          echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {
            .material #header-outer:not(.transparent) .bg-color-stripe {
              top: '.$material_header_space.'px;
              height: calc(225px - '.$material_header_space.'px);
            }
            .material #header-outer.transparent .bg-color-stripe {
              height: 225px;
            }
          }';
        }

    	}
      else {
        echo '
        @media only screen and (min-width: 1000px) {
          .material #header-outer.transparent .bg-color-stripe {
            top: 0px;
            height: 225px;
          }
          .material #header-outer:not(.transparent) .bg-color-stripe {
            top: '.$material_header_space.'px;
            height: calc(225px - '.$material_header_space.'px);
          }

          .material #header-outer:not(.transparent).small-nav .bg-color-stripe {
            top: '.$small_matieral_header_space.'px;
            height: calc(225px - '.$small_matieral_header_space.'px);
          }
        }';
      }
    }


    echo '@media only screen and (max-width: 999px) {
      .material #header-outer.transparent .bg-color-stripe,
      .material #header-outer[data-transparent-header].transparent .bg-color-stripe,
      .material #header-outer[data-transparent-header]:not([data-permanent-transparent="1"]).transparent .bg-color-stripe {
        top: 0px;
        height: 100px;
      }
      .material #header-outer:not([data-permanent-transparent="1"]):not(.transparent) .bg-color-stripe {
        top: '. ($mobile_logo_height+24) .'px;
        height: calc(100px - '. ($mobile_logo_height+24) .'px);
      }
    }';


    echo '
    body.material[data-header-inherit-rc="true"] #search-outer {
      background-color: transparent;
    }
    .material:not([data-header-format="left-header"]) #header-outer:not(.transparent) .bg-color-stripe {
      display: block;
    }
    .material #header-outer #search-outer:not(.material-open):before {
      transform: scaleY(0);
    }
    .material #header-outer #search-outer:before {
      transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
    }
    #search-outer:not(.material-open) {
      pointer-events: none;
    }

    body.material #search-outer {
      min-height: 225px;
      height: auto;
    }

    body.material #header-outer:not([data-format="left-header"]) #search-outer {
      transform: none;
      -webkit-transform: none;
    }

    body[data-header-format="left-header"].material #search-outer,
    body.material #header-outer #search-outer {
      height: auto;
    }

    @media only screen and (min-width: 1000px) {
      body[data-header-format="left-header"] #search-outer:not(.material-open) #search {
        transform: translate3d(0,225px,0);
        -webkit-transform: translate3d(0,225px,0);
      }
    }

    #search-outer #search {
      overflow: hidden;
      -webkit-transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
      transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
      transform: translate3d(0,-225px,0);
      -webkit-transform: translate3d(0,-225px,0);
    }
    #search-outer #search-box,
    #search-outer #search #close {
      transform: translate3d(0,225px,0);
      -webkit-transform: translate3d(0,225px,0);
    }
    #search-outer.material-open #search {
      transform: translate3d(0,0,0);
      -webkit-transform: translate3d(0,0,0);
    }
    #search-outer #search {
      height: 225px;
    }

    #search-outer .nectar-ajax-search-results {
      background-color: #fff;
      max-height: 0;
    }
    #search-outer.material-open .nectar-ajax-search-results {
      transition: transform 0.8s cubic-bezier(0.2, 0.6, 0.4, 1), max-height 0.8s cubic-bezier(0.2, 0.6, 0.4, 1);
    }

    #header-outer.material-search-open .bg-color-stripe {
      transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
    }
    [data-header-inherit-rc="true"] #header-outer.material-search-open .bg-color-stripe {
      transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1), background-color 0.30s;
    }

    .results-shown #search-outer #search,
    .results-shown #search-outer #search-box,
    .results-shown #search-outer #search #close {
      -webkit-transition: transform 0.4s cubic-bezier(0.2, 0.6, 0.4, 1) 0s, opacity 0.4s cubic-bezier(0.2, 0.6, 0.4, 1) 0.2s;
      transition: transform 0.4s cubic-bezier(0.2, 0.6, 0.4, 1) 0.2s, opacity 0.4s cubic-bezier(0.2, 0.6, 0.4, 1) 0.2s;
    }

    #header-outer:not([data-format="left-header"]).results-shown header {
          transition: all 0.2s ease 0.3s;
    }

    .results-shown #search-outer .nectar-ajax-search-results {
      transition: transform 0.3s cubic-bezier(0.68, 0.01, 1, 1), max-height 0.3s cubic-bezier(0.68, 0.01, 1, 1);
    }
    #header-outer.results-shown .bg-color-stripe {
      transition: transform 0.4s cubic-bezier(0.16, 0.46, 0.3, 1) 0.3s;
    }



    #search-outer .nectar-ajax-search-results .inner {
      max-height: calc(100vh - 225px);
      padding-bottom: 30px;
      overflow-y: auto;
      overflow-x: hidden;
      position: relative;
    }

    .admin-bar #search-outer .nectar-ajax-search-results .inner {
      max-height: calc(100vh - 282px);
    }



    @media screen and (max-width: 999px) {

      #header-outer[data-format="left-header"].material-search-open header {
        opacity: 0;
      }
      body[data-header-format="left-header"].admin-bar.material #search-outer {
        top: 32px;
      }
      body[data-header-format="left-header"].material #search-outer,
      body[data-header-format="left-header"].material[data-header-inherit-rc="true"] #search-outer {
        background-color: transparent;
      }

      body[data-header-format="left-header"] #search-outer,
      body.material #header-outer #search-outer {
        min-height: 100px;
        height: auto;
        transform: none;
        -webkit-transform: none;
      }
      #header-outer #search-outer #search-box,
      #header-outer #search-outer #search #close {
        transform: translate3d(0,100px,0);
        -webkit-transform: translate3d(0,100px,0);
      }

      #search-outer #search {
        height: 100px;
        transform: translate3d(0,-100px,0);
        -webkit-transform: translate3d(0,-100px,0);
      }

      #search-outer .nectar-ajax-search-results .inner {
        max-height: calc(100vh - 100px);
      }
      .admin-bar #search-outer .nectar-ajax-search-results .inner {
        max-height: calc(100vh - 152px);
      }

    }


    #search-outer .nectar-ajax-search-results .inner::-webkit-scrollbar {
        width: 10px;
    }
    #search-outer .nectar-ajax-search-results .inner::-webkit-scrollbar {
    	background-color: rgba(0, 0, 0, 0.07);
    }
    #search-outer .nectar-ajax-search-results .inner::-webkit-scrollbar:hover {
      background-color: rgba(0, 0, 0, 0.09);
    }
    #search-outer .nectar-ajax-search-results .inner::-webkit-scrollbar:hover {
    	background-color: rgba(0, 0, 0, 0.11);
    }
    #search-outer .nectar-ajax-search-results .inner::-webkit-scrollbar-thumb {
    	background: rgba(0,0,0,0.25);
      background-clip: padding-box;
      min-height: 10px;
    }

    ';

    $limit_search      = ( isset($nectar_options['header-search-limit']) ) ? $nectar_options['header-search-limit'] : 'all';
    $ajax_search_style = ( isset($nectar_options['header-ajax-search-style']) ) ? $nectar_options['header-ajax-search-style'] : 'default';

    // Limit search styling.
    if( 'product' === $limit_search && 'extended' === $ajax_search_style ) {

      $product_style = ( ! empty( $nectar_options['product_style'] ) ) ? $nectar_options['product_style'] : 'classic';

      if( 'material' === $product_style ) {
        echo '
        #search-outer .woocommerce .material.product:hover .product-wrap .product-meta > .price {
          opacity: 1;
        }
        #search-outer .woocommerce .material.product .product-wrap .product-add-to-cart,
        #search-outer .woocommerce .material.product:hover:before {
          display: none;
        }
        #search-outer .products li.product.material {
            border-radius: 6px;
            transition: box-shadow 0.25s ease, opacity 0.6s cubic-bezier(0.2, 0.6, 0.4, 1), transform 0.6s cubic-bezier(0.2, 0.6, 0.4, 1);
            box-shadow: rgba(0, 0, 0, 0.06) 0 0 0 1px,
                        rgba(0, 0, 0, 0.03) 0 2px 7px;
        }
        #search-outer .products li.product.material:hover {
            box-shadow: rgba(0, 0, 0, 0.1) 0 0 0 1px,
                        rgba(0, 0, 0, 0.07) 0 4px 8px;
        }
        #search-outer .woocommerce .material.product:hover h3,
        #search-outer .woocommerce .material.product:hover .product-meta > .price,
        #search-outer .woocommerce .material.product:hover h2 {
          transform: none;
        }';
      }

      else if( 'classic' === $product_style ) {
        echo '
        #search-outer .woocommerce .classic .product-wrap a.button,
        #search-outer .woocommerce .classic .product-wrap .product-add-to-cart[data-nectar-quickview="true"] {
          display: none;
        }';
      }
      else if( 'minimal' === $product_style ) {

        $product_minimal_hover_effect = ( isset( $nectar_options['product_minimal_hover_effect'] ) ) ? esc_html($nectar_options['product_minimal_hover_effect']) : 'default';

        if( 'default' === $product_minimal_hover_effect ) {
          echo '#search-outer .products li.product.minimal:hover {
              box-shadow: rgba(0, 0, 0, 0.08) 0 0 0 1px,
                          rgba(0, 0, 0, 0.05) 0 3px 8px;
          }';
        }

        echo '
        #search-outer .background-color-expand {
          display: none;
        }
        #search-outer .products li.product.minimal {
            border-radius: 6px;
            transition: box-shadow 0.25s ease, opacity 0.6s cubic-bezier(0.2, 0.6, 0.4, 1), transform 0.6s cubic-bezier(0.2, 0.6, 0.4, 1);
        }

        #search-outer ul.products li.minimal.product .product-wrap {
          overflow: hidden;
        }
        #search-outer .products li.product.minimal:hover .product-meta .price {
            opacity: 1;
         }
        #search-outer .products li.product.minimal.hover-bound:hover .product-meta {
           transform: none;
         }
         #search-outer .products li.product.minimal:hover .product-add-to-cart a {
            display: none;
          }
        ';
      }
      else if( 'text_on_hover' === $product_style ) {
        echo '#search-outer ul.products .text_on_hover.product > .button {
           display: none;
         }';
      }

      echo '
      #search-outer .woocommerce ul.products .text_on_hover.product .add_to_cart_button,
      #search-outer .products li.product .nectar_quick_view {
        display: none;
      }
      
      .nectar-ajax-search-results li.product .woocommerce-loop-product__title {
        color: inherit;
      }
      
      @media only screen and (min-width: 1000px) {
        #search-outer ul.products {
          width: 101.5%;
          padding: 3px;
        }
        #search-outer ul.products > li:nth-child(6) {
          display: none;
        }
        #search-outer ul.products li.product {
          width: 18.4%;
          margin: 0 1.5% 1.5% 0%;
        }

      }
      ';
    } // end product limit.

    // Extended list ajax styling - non product.
    else if( 'extended' === $ajax_search_style ) {

      echo '
      .nectar-ajax-search-results .header {
        flex: 1;
        line-height: 1.3;
        padding: 20px;
      }
      .nectar-ajax-search-results .nectar-search-results {
        display: -webkit-flex;
        display: flex;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
      }
      .nectar-ajax-search-results .post-featured-img {
        width: 100%;
        padding-bottom: 65%;
        background-size: cover;
        background-position: center;
        display: block;
      }
      .nectar-ajax-search-results a {
        color: inherit;
      }
      .nectar-ajax-search-results .meta {
        display: block;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 3px;
      }
      .nectar-ajax-search-results .meta-type {
        margin: 3px 0 0 0;
      }
      .nectar-ajax-search-results .search-post-item h5 {
        margin-bottom: 0;
        line-height: 1.3;
      }
      .nectar-ajax-search-results .search-post-item {
        border-radius: 6px;
        overflow: hidden;
        transition: box-shadow 0.25s ease, opacity 0.6s cubic-bezier(0.2, 0.6, 0.4, 1), transform 0.6s cubic-bezier(0.2, 0.6, 0.4, 1);
        box-shadow: rgba(0, 0, 0, 0.06) 0 0 0 1px,
                    rgba(0, 0, 0, 0.03) 0 2px 7px;
      }
      .nectar-ajax-search-results .search-post-item:hover {
          box-shadow: rgba(0, 0, 0, 0.1) 0 0 0 1px,
                      rgba(0, 0, 0, 0.07) 0 4px 8px;
      }

      @media only screen and (max-width: 999px) {
        .nectar-ajax-search-results .nectar-search-results > div:nth-child(2n+2) {
          margin-right: 0;
        }
        .nectar-ajax-search-results .search-post-item {
          width: 49.25%;
          margin: 0 1.5% 1.5% 0%;
        }
      }

      @media only screen and (min-width: 1000px) {
        .nectar-ajax-search-results .nectar-search-results {
          width: 101.5%;
          padding: 3px;
        }
        .nectar-ajax-search-results .search-post-item {
          width: 18.4%;
          margin: 0 1.5% 1.5% 0%;
        }
        .nectar-ajax-search-results .nectar-search-results > div:nth-child(6) {
          display: none;
        }

      }
      @media only screen and (max-width: 690px) {
        .nectar-ajax-search-results .search-post-item h5 {
          font-size: 14px;
        }
        .nectar-ajax-search-results .meta {
          font-size: 13px;
        }
      }';
    }
    // Simple ajax style
    else {
      echo '
      .nectar-ajax-search-results .header {
        flex: 1;
      }
      .nectar-ajax-search-results .post-featured-img {
        border-radius: 8px;
        height: 60px;
        width: 60px;
        background-position: center;
        background-size: cover;
        margin-right: 15px;
      }
      .nectar-ajax-search-results a {
        color: inherit;
      }
      .nectar-ajax-search-results .meta {
        display: block;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 3px;
      }
      .nectar-ajax-search-results .search-post-item h5 {
        margin-bottom: 0;
        line-height: 1.3;
      }
      .nectar-ajax-search-results .search-post-item a {
        display: flex;
        align-items: center;
        min-height: 60px;
        margin: 0 0 25px 0;
      }
      @media only screen and (max-width: 690px) {
        .nectar-ajax-search-results .search-post-item h5 {
          font-size: 14px;
        }
        .nectar-ajax-search-results .meta {
          font-size: 13px;
        }
      }';
    }

    echo '.nectar-ajax-search-results h5 {
      color: inherit;
      display: inline;
    }
    .nectar-ajax-search-results h5 a {
      position: relative;
    }';

  } // ext search enabled

  /*-------------------------------------------------------------------------*/
  /*  1.14. Search Typography
  /*-------------------------------------------------------------------------*/
  $header_search_font_size = ( isset($nectar_options['header-search-type-size']) ) ? $nectar_options['header-search-type-size'] : false;

  if( false !== $header_search_font_size ) {
    echo '
    @media only screen and (min-width: 1000px) {
        body.'.esc_attr($theme_skin).' #search-outer #search #search-box input[type="text"] {
        font-size: '.intval($header_search_font_size).'px;
        line-height: 1;';
        if( 'material' === $theme_skin ) {
          echo 'height: '. ( intval($header_search_font_size) + 28 ) .'px!important;';
        }
      echo '}';

      if( 'material' === $theme_skin ) {
        echo '
        #search-outer #search .span_12 form > span {
          position: absolute;
          bottom: -40px;
          left: 0;
        }
        #search-outer #search #close {
          top: 50%;
          margin-top: -28px;
        }';
        if ( is_rtl() ) {
          echo '
          #search-outer #search .span_12 form > span {
            right: 0;
          }';
        }
      }
      else if( 'original' === $theme_skin ) {
        echo '#search-outer #search #close a {
          top: 50%;
          margin-top: -7px;
        }';
      }

      echo '}'; // end media query.
  }

  /*-------------------------------------------------------------------------*/
  /*  1.15. Shadows
  /*-------------------------------------------------------------------------*/
  $header_box_shadow = ( isset($nectar_options['header-box-shadow']) ) ? $nectar_options['header-box-shadow'] : false;
  
  if( 'small' === $header_box_shadow ) {
    echo '#header-outer[data-box-shadow="small"],
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="small"][data-remove-fixed="0"]:not(.transparent):not(.invisible),
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="small"][data-remove-fixed="1"]:not(.transparent){
      box-shadow:0 0 3px 0 rgba(0,0,0,0.22);
    }';
  }
  if( 'large' === $header_box_shadow ) {
    echo '#header-outer[data-box-shadow="large"],
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="large"][data-remove-fixed="0"]:not(.transparent):not(.invisible),
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="large"][data-remove-fixed="1"]:not(.transparent) {
      box-shadow:0 3px 45px rgba(0,0,0,0.15);
    }';
  }
  if( 'large-line' === $header_box_shadow ) {
    echo '#header-outer[data-box-shadow="large-line"],
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="large-line"][data-remove-fixed="0"]:not(.transparent):not(.invisible),
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="large-line"][data-remove-fixed="0"]:not(.transparent):not(.fixed-menu).scrolling,
    body.material[data-hhun="1"] #header-outer[data-header-resize="0"][data-box-shadow="large-line"][data-remove-fixed="1"]:not(.transparent) {
      box-shadow: 0 0 2px rgba(0,0,0,0.14), 0 18px 40px rgba(0,0,0,0.045);
    }';
  }

  /*-------------------------------------------------------------------------*/
  /*  1.16. Animations
  /*-------------------------------------------------------------------------*/

  /* Header Transparent logo is same as regular logo */
  if( isset($nectar_options['header-starting-logo']) && isset($nectar_options['header-starting-logo']['url']) &&
      isset($nectar_options['logo']) && isset($nectar_options['logo']['url'])) {

        if( $nectar_options['header-starting-logo']['url'] == $nectar_options['logo']['url'] ) {
          echo '#header-outer[data-transparent-header="true"] #logo img {
            transition: height 0.32s ease; 
          }';
        }
        
  }
  if( isset($nectar_options['header-starting-logo-dark']) && isset($nectar_options['header-starting-logo-dark']['url']) &&
      isset($nectar_options['logo']) && isset($nectar_options['logo']['url'])) {

        if( $nectar_options['header-starting-logo-dark']['url'] == $nectar_options['logo']['url'] ) {
          echo '#header-outer[data-permanent-transparent="false"][data-transparent-header="true"].dark-slide #logo img {
            transition: height 0.32s ease; 
          }';
        }
        
  }

  /* Header resize */
  if( '1' === $headerResize && 'left-header' !== $headerFormat ) {
    
    echo '#header-outer[data-header-resize="1"] #logo,
    #header-outer[data-header-resize="1"] .logo-spacing {
      transition: margin 0.32s ease, color 0.32s ease;
    }
    #header-outer[data-header-resize="1"] #logo img,
    #header-outer[data-header-resize="1"] .logo-spacing img {
      transition: height 0.32s ease, opacity 0.2s ease;
    }
    #header-outer[data-header-resize="1"]:not([data-transparent-header="true"]) #logo,
    #header-outer[data-header-resize="1"]:not([data-transparent-header="true"]) .logo-spacing {
      transition: margin 0.15s ease;
    }
    #header-outer[data-header-resize="1"]:not([data-transparent-header="true"]) #logo img,
    #header-outer[data-header-resize="1"]:not([data-transparent-header="true"]) .logo-spacing img {
      transition: height 0.15s ease, opacity 0.2s ease;
    }

    #header-outer[data-header-resize="1"].small-nav:not([data-transparent-header="true"]) #logo,
    #header-outer[data-header-resize="1"].small-nav:not([data-transparent-header="true"]) .logo-spacing {
      transition: margin 0.3s ease;
    }
    #header-outer[data-header-resize="1"].small-nav:not([data-transparent-header="true"]) #logo img,
    #header-outer[data-header-resize="1"].small-nav:not([data-transparent-header="true"]) .logo-spacing img {
      transition: height 0.3s ease, opacity 0.2s ease;
    }

    #header-outer.small-nav {
      transition: box-shadow 0.42s ease, opacity 0.3s ease;
    }
    #header-outer.small-nav #logo,
    #header-outer.small-nav .logo-spacing {
      transition: margin 0.32s ease;
    }
    #header-outer.small-nav #logo img,
    #header-outer.small-nav .logo-spacing img {
      transition: height 0.32s ease;
    }
    #header-outer[data-using-secondary="1"].small-nav {
      transition: box-shadow 0.42s ease, opacity 0.3s ease, transform 0.32s ease;
    }';
  }


  /*-------------------------------------------------------------------------*/
  /*  1.17. OCM Alignment
  /*-------------------------------------------------------------------------*/
  $side_widget_area_pos = ( isset( $nectar_options['ocm_btn_position'] ) ) ? esc_html($nectar_options['ocm_btn_position']) : 'default';
	
  if( 'left' === $side_widget_area_pos && $side_widget_class !== 'simple' ) {

    $bottom_bar_align = isset($nectar_options['centered-menu-bottom-bar-alignment']) ? $nectar_options['centered-menu-bottom-bar-alignment'] : 'center';
    
    //Custom Mobile Breakpoint 
    if(!empty($mobile_breakpoint) && $mobile_breakpoint != 1000 &&
    $headerFormat !== 'left-header' &&
    $has_main_menu === 'true' ) {

      echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {';

        if( 'centered-menu' !== $mobile_header_layout ) {

          //// Bottom Bar.
          if( $headerFormat === 'centered-menu-bottom-bar' ) {
            
            echo '#top .span_3 .left-aligned-ocm .slide-out-widget-area-toggle {
              display: flex!important;
            }
            
            #header-outer[data-format="centered-menu-bottom-bar"] #top .slide-out-widget-area-toggle.mobile-icon,
            #header-outer[data-format="centered-menu-bottom-bar"]:not([data-menu-bottom-bar-align="left"]) #top .span_3 .slide-out-widget-area-toggle.mobile-icon {
              display: none!important; 
            }';

            
          }

          echo 'body #header-outer[data-format="'.esc_attr($headerFormat).'"] header#top  .left-aligned-ocm[data-user-set="off"] {
            display: flex!important;
          }';

          // centered-logo-between-menu-alt
          if( $headerFormat === 'centered-logo-between-menu-alt' ) {
            echo ' #header-outer[data-format="centered-logo-between-menu-alt"] #top .slide-out-widget-area-toggle.mobile-icon {
              display: none!important; 
            }
            #header-outer[data-format="centered-logo-between-menu-alt"] #top .span_3 .left-aligned-ocm,
            body #header-outer[data-format="centered-logo-between-menu-alt"] header#top .span_3 .left-aligned-ocm[data-user-set="off"] {
              display: flex!important;
            }';
            
          }

          //// General.
          if( in_array(NectarThemeManager::$header_format, array('default','centered-menu','menu-left-aligned')) ) {
            echo '
            body #header-outer #top .left-aligned-ocm {
              display: flex;
            }
            #header-outer:not([data-format="centered-menu-bottom-bar"]) #top .span_9 > .slide-out-widget-area-toggle {
              display: none!important; 
            }';
          }

        }

        //// centered-menu
        else {
          echo ' #header-outer #top .left-aligned-ocm[data-user-set="1"] {
             display: none!important; 
          }
          #top .span_9 > .slide-out-widget-area-toggle a.using-label {
            display: flex!important;
            align-items: center;
            flex-direction: row-reverse;
          }
          #top .span_9 > .slide-out-widget-area-toggle a.using-label .label {
            margin: 0 0 0 15px;
          }';
        }

        echo '}'; // end media query.

    }

    echo '
    @media only screen and (min-width: 1000px) {
      #header-outer #top .left-aligned-ocm:not([data-user-set="1"]) {
        display: none!important;
      }
      #header-outer[data-format="centered-logo-between-menu-alt"] #top .left-aligned-ocm:not([data-user-set="1"]) {
        display: none!important;
      }
    }
   ';

    if( 'centered-menu' !== $mobile_header_layout ) {
      echo '
      @media only screen and (max-width: 999px) {';

        //// Bottom Bar.
        if( $headerFormat === 'centered-menu-bottom-bar' ) {

          if( $bottom_bar_align === 'center' ) {
            echo ' body #header-outer[data-format=centered-menu-bottom-bar] .span_3 {
              display: flex!important;
              justify-content: flex-end;
              flex-direction: row-reverse; 
              align-items: center;
            }';
          } else {
            echo ' body #header-outer[data-format=centered-menu-bottom-bar] .span_3 {
              display: flex!important;
              align-items: center;
            }';
          }

          echo '
          #header-outer[data-format=centered-menu-bottom-bar] #top .span_3 nav.left-side {
            display: inline-block!important;
            position: relative;
          }
          #header-outer #top .span_9 > .slide-out-widget-area-toggle,
          #header-outer[data-format=centered-menu-bottom-bar] #top .span_3 nav.left-side > *:not(.left-aligned-ocm)  {
            display: none!important; 
          }
          #header-outer header#top .left-side ul {
            padding: 0;
          }
         ';
        }
        
        if( $headerFormat === 'centered-logo-between-menu-alt' ) {
          echo '#header-outer #top .span_3 .left-aligned-ocm {
          display: flex!important;
          }';
        }

        echo '#header-outer:not([data-format="centered-menu-bottom-bar"]) #top .span_9 > .slide-out-widget-area-toggle {
          display: none!important; 
        }
        #header-outer #top .left-aligned-ocm {
          display: flex;
        }
 
        #header-outer #top .col.span_3 {
          display: flex;
        }
        #header-outer[data-transparent-header="true"].transparent #logo {
          position: relative;
        }
        body #header-outer #top .left-aligned-ocm {
          margin-right: 18px;
        }';

      echo '}';
    }
    // Centered logo mobile layout
    else {
      echo '
      @media only screen and (max-width: 999px) {
        #header-outer .span_3 .left-aligned-ocm,
        #header-outer #top .left-aligned-ocm[data-user-set="1"] {
           display: none!important;
        }
        #top .span_9 > .slide-out-widget-area-toggle a.using-label {
          display: flex!important;
          align-items: center;
          flex-direction: row-reverse;
        }
        #top .span_9 > .slide-out-widget-area-toggle a.using-label .label {
          margin: 0 0 0 15px;
        }
      }';

    }


    if( $headerFormat === 'centered-logo-between-menu-alt' ) {
      echo '#header-outer #top .span_3 .left-aligned-ocm[data-user-set="1"] {
        display: none;
      }';
    }

    echo '#header-outer #top .left-aligned-ocm[data-user-set="1"] {
      display: flex;
    }

    #header-outer #top .left-aligned-ocm a {
      display: flex!important;
      align-items: center;
      flex-direction: row-reverse;
    }

    .ascend #header-outer[data-full-width="true"] #top .left-aligned-ocm .slide-out-widget-area-toggle a {
      border-left: none;
    }

    #header-outer .left-aligned-ocm {
      align-items: center;
      margin: 0 28px 0 0; 
    }
    
    #header-outer #top .left-aligned-ocm .label {
      margin: 0 0 0 15px;
    }';
  }


  // Left Aligned OCM
  if( 'centered-menu' === $mobile_header_layout || 
      'left' === $side_widget_area_pos ) {
    
    $header_nav_breakpoint = ( 'left-header' !== $headerFormat && $mobile_breakpoint != 1000 ) ? $mobile_breakpoint : '999';

    if( $has_main_menu !== 'true' ) {
      $header_nav_breakpoint = '999';
    }


      echo '
      @media only screen and (max-width: '.esc_attr($header_nav_breakpoint).'px) {
        body[data-slide-out-widget-area-style="slide-out-from-right"].material .slide_out_area_close {
          right: auto;
          left: 340px;
        }

        html body[data-slide-out-widget-area-style="slide-out-from-right"].material .ocm-effect-wrap {
          -webkit-transform-origin: left;
          transform-origin: left;
        }
        body[data-slide-out-widget-area-style="slide-out-from-right"].material .ocm-effect-wrap.material-ocm-open {
          -webkit-transform: scale(0.835) translateX(465px) translateZ(0)!important;
          transform: scale(0.835) translateX(465px) translateZ(0)!important;
        }
      }';

      if( 'left' === $side_widget_area_pos ) {

        if( 'slide-out-from-right' === $side_widget_class && 'material' === $theme_skin ) {
          echo 'body #slide-out-widget-area.slide-out-from-right:not(.material-open) .bottom-meta-wrap, 
          body #slide-out-widget-area.slide-out-from-right:not(.material-open) .inner > div {
            transform: translateX(-100px);
          }';
        }

        echo '@media only screen and (min-width: 1000px) {
          body[data-slide-out-widget-area-style="slide-out-from-right"].material .slide_out_area_close {
            right: auto!important;
            left: 0;
            -webkit-transform: translateY(-50%) translateX(35.55vw)!important;
            transform: translateY(-50%) translateX(35.55vw)!important;
          }

          body[data-slide-out-widget-area-style="slide-out-from-right"].material .ocm-effect-wrap.material-ocm-open {
            -webkit-transform: scale(0.8) translateX(32vw) translateZ(0)!important;
            transform: scale(0.8) translateX(32vw) translateZ(0)!important;
          }
        }
        ';
      }

      //// Move OCM to left on all viewports 
      if( 'left' === $side_widget_area_pos ) {
        $header_nav_breakpoint = '9999';
      }
    


    echo '@media only screen and (max-width: '.esc_attr($header_nav_breakpoint).'px) {';

    if( 'slide-out-from-right' === $side_widget_class && 'material' === $theme_skin ) {
      echo '
        body #slide-out-widget-area.slide-out-from-right {
          right: auto;
          left: 0;
        }';
    }
    if( 'slide-out-from-right-hover' === $side_widget_class || 
        'slide-out-from-right' === $side_widget_class && 'material' === $theme_skin ) {
      echo 'body #slide-out-widget-area.slide-out-from-right-hover {
          transform: translate3d(-101%,0,0);
            -webkit-transform: translate3d(-101%,0,0);
            -ms-transform: translate3d(-101%,0,0);
            right: auto;
            left: 0;
            opacity: 0;
            transition: transform .7s cubic-bezier(0.25,1,.2,1), opacity .2s ease 0.3s;
      }
      body #slide-out-widget-area.slide-out-from-right-hover.open {
            transition: transform .7s cubic-bezier(0.25,1,.2,1), opacity .1s ease 0s;
      }
      body .slide-out-hover-icon-effect.small {
        left: 27px;
        right: auto;
      }';

      if( 'left' === $side_widget_area_pos ) {
        echo '
        body #slide-out-widget-area.slide-out-from-right-hover .inner,
        body #slide-out-widget-area.slide-out-from-right-hover .bottom-meta-wrap {
          transform: translateX(-50px);
        }
        .admin-bar .slide-out-hover-icon-effect {
          top: 62px!important;
        }
        .slide-out-hover-icon-effect {
          top: 30px!important;
        }';
      }

    }
    else if( 'slide-out-from-right' === $side_widget_class ) {
      echo '#slide-out-widget-area.slide-out-from-right {
        left: 0;
        right: auto;
      }
      body[data-header-format]:not(.material) #slide-out-widget-area.slide-out-from-right:not(.open) {
        -webkit-transform: translate(-301px,0px)!important;
        transform: translate(-301px,0px)!important;
      }';
    }
    
    echo '}'; // end media query

    //// Within custom breakpoint.
    if( !empty($mobile_breakpoint) && $mobile_breakpoint != 1000 && $headerFormat !== 'left-header' && $has_main_menu === 'true' ) {

      echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {';
        if( 'slide-out-from-right' === $side_widget_class && 'material' === $theme_skin ) {

          echo '
          body[data-slide-out-widget-area-style="slide-out-from-right"] .follow-body.slide_out_area_close {
            right: auto!important;
            left: 0!important;
          }
          body[data-slide-out-widget-area-style="slide-out-from-right"] .follow-body.slide_out_area_close {
            -webkit-transform: translateY(-50%) translateX(35.55vw)!important;
            transform: translateY(-50%) translateX(35.55vw)!important;
          }
          body[data-slide-out-widget-area-style="slide-out-from-right"].material .ocm-effect-wrap.material-ocm-open {
            -webkit-transform: scale(0.8) translateX(32vw) translateZ(0)!important;
            transform: scale(0.8) translateX(32vw) translateZ(0)!important;
            -webkit-transform-origin: center;
            transform-origin: center;
          }';

        }
      echo '}';

    }

    if( 'slide-out-from-right' === $side_widget_class && 'material' === $theme_skin ) {
      echo '@media only screen and (max-width: 450px) {
        body[data-slide-out-widget-area-style="slide-out-from-right"].material .slide_out_area_close {
          display: none;
        }
        body[data-slide-out-widget-area-style="slide-out-from-right"].material .ocm-effect-wrap.material-ocm-open {
          -webkit-transform: scale(0.835) translateX(93vw) translateZ(0)!important;
          transform: scale(0.835) translateX(93vw) translateZ(0)!important;
        }
      }';
    }
  
    echo 'body:not(.material) #slide-out-widget-area.slide-out-from-right:not(.open) {
      -webkit-transform: translate(301px,0px)!important;
      transform: translate(301px,0px)!important;
    }
  
  
    body #slide-out-widget-area.slide-out-from-right-hover {
          opacity: 0;
          transition: transform .7s cubic-bezier(0.25,1,.2,1), opacity .2s ease 0.3s;
    }
    body #slide-out-widget-area.slide-out-from-right-hover.open {
          opacity: 1;
          transition: transform .7s cubic-bezier(0.25,1,.2,1), opacity .1s ease 0s;
    }';
    
  }

  /*-------------------------------------------------------------------------*/
  /*  1.18. Background Blur
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['header-blur-bg']) && $nectar_options['header-blur-bg'] === '1' ) {

    $header_blur_func = (isset($nectar_options['header-blur-bg-func'])) ? $nectar_options['header-blur-bg-func'] : 'active_non_transparent';

    if( $header_blur_func === 'active_non_transparent' ) {
      echo '#header-outer:not(.transparent) {
        -webkit-backdrop-filter: blur(12px); 
        backdrop-filter: blur(12px); 
     }';
    } 
    else {
      echo '#header-outer {
        -webkit-backdrop-filter: blur(12px); 
        backdrop-filter: blur(12px); 
     }';
    }

    
  }

  /*-------------------------------------------------------------------------*/
  /*  1.19. Header Size
  /*-------------------------------------------------------------------------*/
  
  if ( nectar_is_contained_header() ) {
    echo '
    body #header-space {
      background-color: transparent!important;
    }
    body #header-outer {
      transition: box-shadow 0.35s ease;
    }

    #header-outer[data-box-shadow="small"] {
      box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    }

    body #header-outer, body #header-space {
      width: calc(100% - var(--container-padding)*2);
      max-width: calc(var(--container-width) - var(--container-padding)*2);
      left: 0;
      right: 0;
      margin-left: auto;
      margin-right: auto;
      margin-top: max(calc(var(--container-padding)/3), 25px);
    }

    body #header-outer .container {
      padding-left: max(calc(var(--container-padding)/3), 25px);
      padding-right: max(calc(var(--container-padding)/3), 25px);
    }
    .nectar_hook_before_secondary_header {
      padding-top: max(calc(var(--container-padding)/3), 25px);
    }

    body[data-bg-header="true"].single-product .nectar_hook_global_section_after_header_navigation {
      margin-bottom: 40px;
    }

    @media only screen and (max-width: 999px) {
      body #header-outer, body #header-space {
          max-width: var(--mobile-container-width);
          width: 100%;
      }
      body #header-outer .container {
        max-width: 100%;
      }
    }

    @media only screen and (max-width: 690px) {

      body #header-outer .container {
        padding-left: min(max(calc(var(--container-padding)/3),25px), 22px);
        padding-right: min(max(calc(var(--container-padding)/3),25px), 22px);
      }
    }

    @media only screen and (min-width : 1px) and (max-width : 999px) {
      body #header-outer #top .span_3 #logo[data-supplied-ml="true"] img:not(.mobile-only-logo) {
        display: none!important;
      }
      body #header-outer #top .span_3 #logo[data-supplied-ml="true"] .mobile-only-logo {
        display: block!important;
      }
    }

    ';
    
    // hhun
    echo '
    body[data-hhun="1"] #header-outer.invisible:not(.side-widget-open), 
    body[data-hhun="1"] #header-outer.no-trans-hidden:not(.side-widget-open) {
      transform: translateY(calc((100% + max(calc(var(--container-padding)/3), 25px)) * -1))!important;
    }
    body[data-hhun="1"] #header-outer.invisible:not(.side-widget-open) {
      transition: transform .3s ease, box-shadow 0.3s ease;
    }
    ';

   
    // simple ocm
    echo '
    body #header-outer #mobile-menu:before {
      width: 100%;
      left: 0;
      margin-left: 0;
    }
    #mobile-menu .menu-items-wrap,
    #header-outer .nectar-global-section.nectar_hook_ocm_bottom_meta > .container {
      padding: 0 min(max(calc(var(--container-padding)/3),25px),22px);
    }
    ';


    if ( 'material' === $theme_skin ) {

      $search_height = '160px';
      $header_search_font_size = ( isset($nectar_options['header-search-type-size']) ) ? $nectar_options['header-search-type-size'] : false;
      if ( intval($header_search_font_size) < 40 ) {
        $search_height = '148px';
      }

      echo '
      body[data-header-color="light"] #top .slide-out-widget-area-toggle .close-line {
        background-color: #999;
      }
      .nectar-ajax-search-results {
        overflow: hidden;
      }
      body.material #search-outer #search,
      html body.material #search-outer {
        height: '.$search_height.';
        min-height: 0;
      }

      .material #header-outer:not(.transparent) #top + .bg-color-stripe,
      .material #header-outer.transparent #top + .bg-color-stripe {
        top: 50%!important;
        height: '.$search_height.';
      }

      @media only screen and (max-width: 999px) {
        .material #header-outer:not(.transparent) #top + .bg-color-stripe,
        .material #header-outer.transparent #top + .bg-color-stripe,
         body.material #search-outer #search, 
         html body.material #search-outer {
          height: 80px;
        }
      }

      body.material #search-outer #search {
        align-items: flex-start;
      }
      .material #header-outer #search-outer {
        overflow: hidden;
        top: 50%;
      }
      @media only screen and (min-width: 1000px) {
        html body.material #search-outer #search #close {
            right: max(calc(var(--container-padding)/3), 25px);
        }
      }
      @media only screen and (max-width: 999px) {
        html body.material #header-outer #search-outer #search #close {
          right: 20px;
        }
      }';
    } else {
      echo '
      @media only screen and (max-width: 999px) {
        .original #header-outer #search-outer {
           height: 100%;
        }
        .original #search-outer #search #close a {
          right: 20px;
        }
      }';
    }

    if ( isset($nectar_options['header-border-radius']) && $nectar_options['header-border-radius'] !== '0' ) {
      echo '
      body #header-outer, #header-outer #top {
        border-radius: '.esc_attr($nectar_options['header-border-radius']).'px;
      }
      .material #header-outer #search-outer,
      .original #header-outer #search-outer {
        border-radius: '.esc_attr($nectar_options['header-border-radius']).'px;
      }
      body.material #header-outer #search-outer:before,
      .material #header-outer .bg-color-stripe {
        border-radius: 0 0 '.esc_attr($nectar_options['header-border-radius']).'px '.esc_attr($nectar_options['header-border-radius']).'px;
      }
      .material #header-outer.results-shown .bg-color-stripe {
        border-radius: 0;
      }
      #header-outer.simple-ocm-open {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }
      #header-outer #mobile-menu:before {
        border-bottom-right-radius: '.esc_attr($nectar_options['header-border-radius']).'px;
        border-bottom-left-radius: '.esc_attr($nectar_options['header-border-radius']).'px;
      }
      ';
    }


  }



  /*-------------------------------------------------------------------------*/
  /*  1.20. Header Border
  /*-------------------------------------------------------------------------*/
  if ( isset( $nectar_options['header-enable-border'] ) && '1' === $nectar_options['header-enable-border'] ) {

    $header_bottom_border_color = ( isset( $nectar_options['header-border-color'] ) ) ? $nectar_options['header-border-color'] : '#000000';
    $remove_border = ( isset( $nectar_options['header-remove-border'] ) && $nectar_options['header-remove-border'] === '1' || $theme_skin === 'material' ) ? true : false;
    
    // contained header.
    if ( nectar_is_contained_header() ) {
      echo '
      @media only screen and (min-width: 1000px) {
        #header-outer #top {
          border: 1px solid '.esc_attr($header_bottom_border_color).';
        }
      }';
    }

    // mobile
    echo '
    @media only screen and (max-width: 999px) {';

      if ( !$remove_border ) {
        echo '
        html body #header-outer[data-has-menu][data-lhe] {
          border-bottom: 1px solid '.esc_attr($header_bottom_border_color).'!important;
        }
        html body #header-outer[data-has-menu][data-lhe].transparent {
          border-color: var(--nectar-starting-header-color)!important;
        }
        html body #header-outer[data-has-menu][data-lhe].transparent.dark-slide,
        html body #header-outer[data-has-menu][data-lhe].dark-text {
          border-color: var(--nectar-starting-dark-header-color)!important;
        }';
      } else {
        echo 'html body #header-outer[data-has-menu][data-lhe]:not(.transparent) {
          border-bottom: 1px solid '.esc_attr($header_bottom_border_color).'!important;
        }';
      }

    echo '}';
 
    // regular desktop
    echo '
    @media only screen and (min-width: 1000px) {
      #header-outer:not(.transparent) #top {
        transition: border-color 0.2s ease;
        border-bottom: 1px solid '.esc_attr($header_bottom_border_color).';
      }';

      if ( !$remove_border ) {
        echo '
        #header-outer #top {
          transition: border-color 0.2s ease;
          border-bottom: 1px solid '.esc_attr($header_bottom_border_color).';
        }
        #header-outer.transparent #top {
          border-color: var(--nectar-starting-header-color);
        }
        #header-outer.transparent.dark-slide #top,
        #header-outer.dark-text #top {
          border-color: var(--nectar-starting-dark-header-color);
        }';
      } else {
        echo '#header-outer:not(.transparent) #top {
          transition: border-color 0.2s ease;
          border-bottom: 1px solid '.esc_attr($header_bottom_border_color).';
        }';
      }
      
    echo '}';

    // left header
    if ( $headerFormat === 'left-header'  ) {
      echo '
      @media only screen and (min-width: 1000px) {
        body #header-outer[data-format="left-header"] {
          border-right: 0;
        }
        body #header-outer[data-format="left-header"] #top {
          border-right: 1px solid '.esc_attr($header_bottom_border_color).';
        }
      }
      ';
    }

    // bottom bar
    if( $headerFormat === 'centered-menu-bottom-bar' ) {
      if( '1' === $centered_menu_bb_sep ) {
        echo '
        @media only screen and (min-width: 1000px) {
          #header-outer[data-format="centered-menu-bottom-bar"] #top .span_3:before {
            background-color: '.esc_attr($header_bottom_border_color).';
          }
          body #header-outer[data-format="centered-menu-bottom-bar"].transparent:not(.dark-slide) #top .span_3:before {
            background-color: var(--nectar-starting-header-color);
          }
          body #header-outer[data-format="centered-menu-bottom-bar"].transparent.dark-slide #top .span_3:before,
          body #header-outer[data-format="centered-menu-bottom-bar"].transparent.dark-text #top .span_3:before {
            background-color: var(--nectar-starting-dark-header-color);
          }
        }';
      }
    }

  }


  /*-------------------------------------------------------------------------*/
  /* 2. Link Hover Effects
  /*-------------------------------------------------------------------------*/

  /*-------------------------------------------------------------------------*/
  /* 2.1. Header Navigation Hover Effects
  /*-------------------------------------------------------------------------*/
    echo '
    .nectar-skip-to-content:focus {
      position: fixed;
      left: 6px;
      top: 7px;
      height: auto;
      width: auto;
      display: block;
      font-size: 14px;
      font-weight: 600;
      padding: 15px 23px 14px;
      background: #f1f1f1;
      color: #000;
      z-index: 100000;
      line-height: normal;
      text-decoration: none;
      box-shadow: 0 0 2px 2px rgba(0,0,0,.6);
  }';

  /*-------------------------------------------------------------------------*/
  /* 2.2. Header Navigation Hover Effects
  /*-------------------------------------------------------------------------*/

 if( 'animated_underline' === $header_hover_effect && 'left-header' !== $headerFormat ) {
   echo '
   #header-outer[data-lhe="animated_underline"] li > a .menu-title-text {
     position: relative;
   }
   #header-outer .mobile-header li:not([class*="button_"]) > a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] nav > ul >li:not([class*="button_"]) > a .menu-title-text:after,
   #header-secondary-outer[data-lhe="animated_underline"] nav >.sf-menu >li >a .menu-title-text:after {
     -webkit-transition:-webkit-transform .3s ease-out, border-color .3s ease-out;
     transition:transform .3s ease-out,border-color .3s ease-out;
     position:absolute;
     display:block;
     bottom:-6px;
     left:0;
     width:100%;
     -ms-transform:scaleX(0);
     -webkit-transform:scaleX(0);
     transform:scaleX(0);
     border-top:2px solid #000;
     content: "";
     padding-bottom:inherit
   }
   #header-outer .mobile-header li:not([class*="button_"]) > a:hover .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] #top nav >ul >li >a:hover .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] #top nav >ul >.sfHover >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .sf-menu .current_page_ancestor >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .sf-menu .current-menu-ancestor >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .sf-menu .current-menu-item >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .sf-menu .current_page_item >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .sf-menu .current_page_parent >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .buttons .current-menu-item >a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"] .buttons .current-menu-ancestor >a .menu-title-text:after,
   #header-secondary-outer[data-lhe="animated_underline"] nav >.sf-menu >li >a .menu-title-text:hover:after,
   #header-secondary-outer[data-lhe="animated_underline"] nav >.sf-menu >li >a:focus .menu-title-text:after {
     backface-visibility: hidden;
     -webkit-transform:scaleX(1);
     transform:scaleX(1)
   }
   body[data-header-inherit-rc="true"] #header-outer[data-lhe="animated_underline"].light-text #top nav >ul >li:not([class*="button_"]) >a .menu-title-text:after {
   	border-color: #fff;
   }
   #header-outer[data-lhe="animated_underline"].transparent.dark-slide #top nav >ul >li >a .menu-title-text:after {
     border-color:#000!important;
   }';
 } 

 // Button BG style
 else if( 'button_bg' === $header_hover_effect && 'left-header' !== $headerFormat ) {

  $header_color_scheme = (isset($nectar_options['header-color'])) ? $nectar_options['header-color'] : 'light';
  
  // core styling.
  echo '
  #top .sf-menu > li:not([class*="menu-item-btn"]) > a .menu-title-text {
    transition: color .45s cubic-bezier(0.25,1,0.33,1);
  }
  #top .sf-menu > li:not([class*="menu-item-btn"]) > a .menu-title-text:before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    content: "";
    display: block;
    z-index: -1;
    transition: opacity .45s cubic-bezier(0.25,1,0.33,1),
                transform .45s cubic-bezier(0.25,1,0.33,1),
                background-color .45s cubic-bezier(0.25,1,0.33,1);
  }';

  // dropdown arrows.
  echo '#top .sf-menu > li > a > .sf-sub-indicator {
    left: 0;
    margin-left: 5px;
  }';

  // default coloring.
  if( in_array($header_color_scheme, array('light','dark')) ) {
    $default_header_button_color = (isset($nectar_options["accent-color"]) && !empty($nectar_options["accent-color"])) ? $nectar_options["accent-color"] : '#000';
    echo '#top .sf-menu > li:not([class*="menu-item-btn"]) > a .menu-title-text:before {
      background-color: '.esc_attr($default_header_button_color).';
      filter: opacity(0.15);
    }
    #top .sf-menu > li:not([class*="current"]):not([class*="menu-item-btn"]) > a .menu-title-text:before {
      background-color: currentColor;
    }
    #top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a .menu-title-text {
      color: #fff;
    }
    #top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a .menu-title-text:before {
      filter: opacity(1);
    }'; 
  }

  // sizing.
  echo '
  #top .sf-menu > li,
  body.material #header-outer:not([data-format=left-header]) #top nav>.buttons.sf-menu>li.menu-item  {
    margin-left: '.(intval($menu_item_spacing)/2) .'px;
    margin-right: '.(intval($menu_item_spacing)/2) .'px;
  }
  #top .sf-menu > li[class*="menu-item-btn"] > a {
    padding: 0px;
  }
  #top .sf-menu > li:not([class*="menu-item-btn"]) > a {
    padding: '.($button_width/1.8).'em '.$button_width.'em;
  }
  #top .sf-menu > #social-in-menu > a {
    padding: 0;
  }
  #header-outer[data-format="centered-menu-under-logo"] #top .sf-menu > li:not([class*="menu-item-btn"]) > a {
    padding: '.($button_width/1.8).'em '.$button_width.'em!important;
  }';

  if ( 'ascend' === $theme_skin && $header_fullwidth ) {
    echo '
    #top .sf-menu > #search-btn,
    #top .sf-menu > .nectar-woo-cart,
    #top .sf-menu > .slide-out-widget-area-toggle,
    #top .sf-menu > #nectar-user-account {
      margin: 0;
    }
    ';
  }

  // styling.
  $menu_button_style = isset($nectar_options['header-hover-effect-button-bg-style']) ? $nectar_options['header-hover-effect-button-bg-style'] : 'fade-in';
  if( 'fade-in' === $menu_button_style ) {
    echo '#top .sf-menu > li:not([class*="menu-item-btn"]) > a .menu-title-text:before {
      opacity: 0;
    }
    #top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a .menu-title-text:before,
    #top .sf-menu > li:not([class*="menu-item-btn"]) > a:hover .menu-title-text:before,
    #top .sf-menu > li.sfHover:not([class*="menu-item-btn"]) > a .menu-title-text:before {
      opacity: 1; 
    }
   ';
  } 
  else if ( 'grow-in' === $menu_button_style ) {
    echo '#top .sf-menu > li:not([class*="menu-item-btn"]) > a .menu-title-text:before {
      transform: scale(0.5);
      opacity: 0;
    }

    #top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a .menu-title-text:before,
    #top .sf-menu > li:not([class*="menu-item-btn"]) > a:hover .menu-title-text:before,
    #top .sf-menu > li.sfHover:not([class*="menu-item-btn"]) > a .menu-title-text:before {
      transform: scale(1) translateZ(0);
      opacity: 1;
    }';
  }

  // roundness.
  if( 'slightly_rounded' === $nectar_options['button-styling'] ||
  'slightly_rounded_shadow' === $nectar_options['button-styling'] ) {
    $button_roundness = ( isset($nectar_options['button-styling-roundness']) && !empty($nectar_options['button-styling-roundness']) ) ? intval( $nectar_options['button-styling-roundness'] ) : 4;
    echo '#top .sf-menu .menu-title-text:before {
      border-radius: '.$button_roundness.'px;
    }';
  }

  if( 'rounded' === $nectar_options['button-styling'] || 
       'rounded_shadow' === $nectar_options['button-styling'] ) {
      echo '#top .sf-menu .menu-title-text:before {
        border-radius: 200px;
      }';
  }

 }

 /*-------------------------------------------------------------------------*/
 /* 2.3. Global Hover Effects
 /*-------------------------------------------------------------------------*/
 $animated_underline_thickness = (isset($nectar_options['animated-underline-thickness']) && !empty($nectar_options['animated-underline-thickness'])) ? $nectar_options['animated-underline-thickness'] : '2';

  // Animated Underline Animation Variants.
 if( isset( $nectar_options['animated-underline-type'] ) && !empty( $nectar_options['animated-underline-type'] ) ) {

   // Left To Right Fancy Style
   if( 'ltr-fancy' === $nectar_options['animated-underline-type'] ) {

     $starting_origin = 'right';
     $ending_origin = 'left';

     if( is_rtl() ) {
       $starting_origin = 'left';
       $ending_origin = 'right';
     }

     // Transform
     echo '#header-outer[data-lhe="animated_underline"]:not([data-format="left-header"]) #top nav > ul > li:not([class*="button_"]) > a .menu-title-text:after,
     body #header-outer .mobile-header li:not([class*="button_"]) > a .menu-title-text:after,
     body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container li a:after,
     #header-secondary-outer[data-lhe="animated_underline"] nav >.sf-menu >li >a .menu-title-text:after,
     #slide-out-widget-area.fullscreen-split .inner .off-canvas-menu-container li a:after,
     #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a span:after,
     body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container .nectar-menu-item-with-icon .menu-title-text:after,
     body #slide-out-widget-area.fullscreen-split .off-canvas-menu-container .nectar-menu-item-with-icon .menu-title-text:after,
     .masonry.material .masonry-blog-item .meta-category a:before,
     .post-area.featured_img_left .meta-category a:before,
     .related-posts[data-style="material"] .meta-category a:before,
     .nectar-recent-posts-single_featured .grav-wrap .text a:before,
     .auto_meta_overlaid_spaced .masonry-blog-item .meta-category a:before,
     [data-style="list_featured_first_row"] .meta-category a:before,
     body #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:after,
     .sf-menu li ul li a .menu-title-text:after,
     #ajax-content-wrap .portfolio-filters-inline[data-color-scheme*="-underline"] ul li a:after,
     .nectar-post-grid-filters a:after,
     .nectar-post-grid .meta-category a:before {
       transform-origin: '. esc_attr($starting_origin) .';
       transition: transform 0.3s cubic-bezier(0.25, 0, 0.4, 1), border-color 0.35s cubic-bezier(0.52, 0.01, 0.16, 1);
     }

     body #header-outer .mobile-header li:not([class*="button_"]) > a:hover .menu-title-text:after,
     #header-outer[data-lhe="animated_underline"]:not([data-format="left-header"]) #top nav > ul > li:not([class*="button_"]) > a:hover .menu-title-text:after,
     #header-outer[data-lhe="animated_underline"]:not([data-format="left-header"]) #top nav > ul > li.sfHover:not([class*="button_"]) > a .menu-title-text:after,
     body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container li a:hover:after,
     #header-secondary-outer[data-lhe="animated_underline"] nav >.sf-menu >li >a:hover .menu-title-text:after,
     #slide-out-widget-area.fullscreen-split .inner .off-canvas-menu-container li a:hover:after,
     #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a:hover span:after,
     body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container .nectar-menu-item-with-icon:hover .menu-title-text:after,
     body #slide-out-widget-area.fullscreen-split .off-canvas-menu-container .nectar-menu-item-with-icon:hover .menu-title-text:after,
     .masonry.material .masonry-blog-item .meta-category a:hover:before,
     .related-posts[data-style="material"] .meta-category a:hover:before,
     .post-area.featured_img_left .meta-category a:hover:before,
     .nectar-recent-posts-single_featured .grav-wrap .text a:hover:before,
     .auto_meta_overlaid_spaced .masonry-blog-item .meta-category a:hover:before,
     [data-style="list_featured_first_row"] .meta-category a:hover:before,
     .masonry.material .masonry-blog-item .meta-category a:focus:before,
     .related-posts[data-style="material"] .meta-category a:focus:before,
     .post-area.featured_img_left .meta-category a:focus:before,
     .nectar-recent-posts-single_featured .grav-wrap .text a:focus:before,
     .auto_meta_overlaid_spaced .masonry-blog-item .meta-category a:focus:before,
     [data-style="list_featured_first_row"] .meta-category a:focus:before,
     body #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:hover:after,
     .sf-menu li ul li a:hover .menu-title-text:after,
     .sf-menu li ul li.sfHover > a .menu-title-text:after,
     #ajax-content-wrap .portfolio-filters-inline[data-color-scheme*="-underline"] ul li a:hover:after,
     .nectar-post-grid-filters a:hover:after,
     .nectar-post-grid-filters a.active:after,
     .nectar-post-grid .meta-category a:hover:before  {
       transform-origin: '. esc_attr($ending_origin) .';
     }';

     // BG size
     echo '#footer-outer[data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
     #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button),
     #slide-out-widget-area.fullscreen-split .widget ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
     #slide-out-widget-area.fullscreen-split .textwidget a:not(.nectar-button),
     .nectar-quick-view-box .single_add_to_cart_button_wrap a span,
     .products li.product.minimal .product-add-to-cart a span,
     .products li.product.minimal .product-add-to-cart .added_to_cart,
     .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control,
     .woocommerce-tabs .full-width-content[data-tab-style="fullwidth"] ul.tabs li a,
     .woocommerce .woocommerce-info a,
     .woocommerce .woocommerce-error a,
     .woocommerce-message a,
     .woocommerce-MyAccount-content .woocommerce-message a.button,
     #search-results .result .title a,
     a > .nectar-ext-menu-item .menu-title-text,
     .nectar-slide-in-cart.style_slide_in_click .cart_list .product-meta a:not(.remove),
     body .woocommerce-checkout-review-order-table .product-info h4 a,
     body.woocommerce-cart .product-name a,
     .woocommerce .woocommerce-breadcrumb a,
     .nectar-ajax-search-results .search-post-item h5,
     .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .cat-heading,
     .nectar-underline h1,
     .nectar-underline h2,
     .nectar-underline h3,
     .nectar-underline h4,
     .nectar-underline h5,
     .nectar-link-underline a span,
     .nectar-shop-header > .woocommerce-ordering .select2-container--default:hover .select2-selection__rendered,
     .nectar-shop-header > .woocommerce-ordering .select2-container--default.select2-container--open .select2-selection__rendered,
     .variations_form .variations .select2-container--default:hover .select2-selection__rendered,
     .variations_form .variations .select2-container--default.select2-container--open .select2-selection__rendered,
     body .variations_form .variations select:hover,
     .woocommerce div.product .woocommerce-review-link,
     .woocommerce.single-product div.product_meta a {
          background-position: '. esc_attr($starting_origin) .' bottom;
     }';

     echo '#footer-outer[data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button):hover,
     #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button):hover,
     #slide-out-widget-area.fullscreen-split .widget ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button):hover,
     #slide-out-widget-area.fullscreen-split .textwidget a:not(.nectar-button):hover,
     .nectar-quick-view-box .single_add_to_cart_button_wrap a:hover span,
     .products li.product.minimal .product-add-to-cart a:hover span,
     .products li.product.minimal .product-add-to-cart .added_to_cart:hover,
     .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control.active,
     .woocommerce-tabs .full-width-content[data-tab-style="fullwidth"] ul.tabs li.active a,
     .woocommerce .woocommerce-info a:hover,
     .woocommerce .woocommerce-error a:hover,
     .woocommerce-message a:hover,
     .woocommerce-MyAccount-content .woocommerce-message a.button:hover,
     #search-results .result .title a:hover,
     a:hover > .nectar-ext-menu-item .menu-title-text,
     a:focus > .nectar-ext-menu-item .menu-title-text,
     li[class*="current"] > a > .nectar-ext-menu-item .menu-title-text,
     .nectar-slide-in-cart.style_slide_in_click .cart_list .product-meta a:hover:not(.remove),
     body .woocommerce-checkout-review-order-table .product-info h4 a:hover,
     body.woocommerce-cart .product-name a:hover,
     .woocommerce .woocommerce-breadcrumb a:hover,
     .nectar-ajax-search-results .search-post-item:hover h5,
     .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item:hover .cat-heading,
     .nectar-underline:hover h1,
     .nectar-underline:hover h2,
     .nectar-underline:hover h3,
     .nectar-underline:hover h4,
     .nectar-underline:hover h5,
     .nectar-link-underline a:hover span,
     .active-tab .nectar-link-underline a span,
     .nectar-link-underline a[class*="active"] span,
     .nectar-post-grid-item:hover .nectar-link-underline span,
     .nectar-shop-header > .woocommerce-ordering .select2-container--default .select2-selection__rendered,
     .woocommerce-ordering .select2-container--default .select2-selection__rendered,
     .variations_form .variations .select2-container--default .select2-selection__rendered,
     body .variations_form .variations select,
     .woocommerce div.product .woocommerce-review-link:hover,
     .woocommerce.single-product div.product_meta a:hover
      {
          background-position: '. esc_attr($ending_origin) .' bottom;
     }';
   }

   // Left To Right Style
   else if( 'ltr' === $nectar_options['animated-underline-type'] ) {
     echo '
     body #header-outer .mobile-header li:not([class*="button_"]) > a .menu-title-text:after,
     #header-outer[data-lhe="animated_underline"]:not([data-format="left-header"]) #top nav > ul > li:not([class*="button_"]) > a .menu-title-text:after,
     .sf-menu li ul li a .menu-title-text:after,
     .nectar-post-grid-filters a:after {
       transform-origin: left;
       transition: transform 0.3s cubic-bezier(0.25, 0, 0.4, 1), border-color 0.35s cubic-bezier(0.52, 0.01, 0.16, 1);
     }';
   }

 } // end underline type

 if( '2' !== $animated_underline_thickness ) {

   // Border
   echo '
   body #header-outer .mobile-header li:not([class*="button_"]) > a .menu-title-text:after,
   #header-outer[data-lhe="animated_underline"]:not([data-format="left-header"]) nav > ul > li:not([class*="button_"]) > a .menu-title-text:after,
   body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container li a:after,
   body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container .nectar-menu-item-with-icon .menu-title-text:after,
   body #slide-out-widget-area.fullscreen-split .off-canvas-menu-container .nectar-menu-item-with-icon .menu-title-text:after,
   #header-secondary-outer[data-lhe="animated_underline"] nav >.sf-menu >li >a .menu-title-text:after,
   #slide-out-widget-area.fullscreen-split .inner .off-canvas-menu-container li a:after,
   #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a span:after {
     border-top-width: '.esc_attr($nectar_options['animated-underline-thickness']) .'px;
   }
   .nectar-cta[data-style="underline"] .link_wrap .link_text:after {
      border-bottom-width: '.esc_attr($nectar_options['animated-underline-thickness']) .'px;
   }
   :root {
      --nectar-border-thickness: '.esc_attr($nectar_options['animated-underline-thickness']) .'px;
   }';

   // BG
   echo '.masonry.material .masonry-blog-item .meta-category a:before,
   .post-area.featured_img_left .meta-category a:before,
  .related-posts[data-style="material"] .meta-category a:before,
  .nectar-recent-posts-single_featured .grav-wrap .text a:before,
  .auto_meta_overlaid_spaced .masonry-blog-item .meta-category a:before,
  [data-style="list_featured_first_row"] .meta-category a:before,
  #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:after,
  .sf-menu li ul li a .menu-title-text:after,
  #ajax-content-wrap .portfolio-filters-inline[data-color-scheme*="-underline"] ul li a:after,
  .nectar-post-grid-filters a:after,
  .nectar-post-grid .meta-category a:before {
      height: '.esc_attr($nectar_options['animated-underline-thickness']).'px;
  }';

   // BG Size
   echo '#footer-outer[data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
   #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button),
   #slide-out-widget-area.fullscreen-split .widget ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
   #slide-out-widget-area.fullscreen-split .textwidget a:not(.nectar-button),
   .nectar-quick-view-box .single_add_to_cart_button_wrap a span,
   .products li.product.minimal .product-add-to-cart a span,
   .products li.product.minimal .product-add-to-cart .added_to_cart,
   .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control,
   .woocommerce-tabs .full-width-content[data-tab-style="fullwidth"] ul.tabs li a,
   .woocommerce .woocommerce-info a,
   .woocommerce .woocommerce-error a,
   .woocommerce-message a,
   .woocommerce-MyAccount-content .woocommerce-message a.button,
   #search-results .result .title a,
   a > .nectar-ext-menu-item .menu-title-text,
   .nectar-slide-in-cart.style_slide_in_click .cart_list .product-meta a:not(.remove),
   body .woocommerce-checkout-review-order-table .product-info h4 a,
   body.woocommerce-cart .product-name a,
   .woocommerce .woocommerce-breadcrumb a,
   .nectar-ajax-search-results .search-post-item h5,
   .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item .cat-heading,
   .nectar-underline h1,
   .nectar-underline h2,
   .nectar-underline h3,
   .nectar-underline h4,
   .nectar-underline h5,
   .nectar-shop-header > .woocommerce-ordering .select2-container--default:hover .select2-selection__rendered,
   .nectar-shop-header > .woocommerce-ordering .select2-container--default.select2-container--open .select2-selection__rendered,
   .variations_form .variations .select2-container--default:hover .select2-selection__rendered,
   .variations_form .variations .select2-container--default.select2-container--open .select2-selection__rendered,
   body .variations_form .variations select:hover,
   .woocommerce div.product .woocommerce-review-link,
   .woocommerce.single-product div.product_meta a,
   .nectar-link-underline a span
   {
        background-size: 0% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
   }
   #footer-outer[data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button):hover,
   #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button):hover,
   #slide-out-widget-area.fullscreen-split .widget ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button):hover,
   #slide-out-widget-area.fullscreen-split .textwidget a:not(.nectar-button):hover,
   .nectar-quick-view-box .single_add_to_cart_button_wrap a:hover span,
   .products li.product.minimal .product-add-to-cart a:hover span,
   .products li.product.minimal .product-add-to-cart .added_to_cart:hover,
   .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control.active,
   .woocommerce-tabs .full-width-content[data-tab-style="fullwidth"] ul.tabs li.active a,
   .woocommerce .woocommerce-info a:hover,
   .woocommerce .woocommerce-error a:hover,
   .woocommerce-message a:hover,
   .woocommerce-MyAccount-content .woocommerce-message a.button:hover,
   #search-results .result .title a:hover,
   a:hover > .nectar-ext-menu-item .menu-title-text,
   a:focus > .nectar-ext-menu-item .menu-title-text,
   li[class*="current"] > a > .nectar-ext-menu-item .menu-title-text,
   .nectar-slide-in-cart.style_slide_in_click .cart_list .product-meta a:hover:not(.remove),
   body .woocommerce-checkout-review-order-table .product-info h4 a:hover,
   body.woocommerce-cart .product-name a:hover,
   .woocommerce .woocommerce-breadcrumb a:hover,
   .nectar-ajax-search-results .search-post-item:hover h5,
   .nectar-category-grid[data-style="mouse_follow_image"] .nectar-category-grid-item:hover .cat-heading,
   .nectar-underline:hover h1,
   .nectar-underline:hover h2,
   .nectar-underline:hover h3,
   .nectar-underline:hover h4,
   .nectar-underline:hover h5,
   .active-tab .nectar-link-underline a span,
   .nectar-shop-header > .woocommerce-ordering .select2-container--default .select2-selection__rendered,
   .woocommerce-ordering .select2-container--default .select2-selection__rendered,
   .variations_form .variations .select2-container--default .select2-selection__rendered,
   body .variations_form .variations select,
   .woocommerce div.product .woocommerce-review-link:hover,
   .woocommerce.single-product div.product_meta a:hover,
   .nectar-post-grid-item:hover .nectar-link-underline span,
   .nectar-link-underline a:hover span,
   .nectar-link-underline a[class*="active"] span {
        background-size: 100% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
   }

   .nectar-link-underline-effect a {
    background-size: 100% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
   }
   @keyframes nectar_ltr_line_animation_start {
    0% {
      background-size: 0% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
    }
    100% {
      background-size: 100% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
    }
  }
   @keyframes nectar_ltr_line_animation {
    0% {
      background-size: 100% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
    }
    100% {
      background-size: 0% '.esc_attr($nectar_options['animated-underline-thickness']).'px;
    }
  }';

   if( '1' === $nectar_options['animated-underline-thickness'] ) {
     echo '#header-outer[data-lhe="animated_underline"]:not([data-format="left-header"]) nav > ul > li:not([class*="button_"]) > a .menu-title-text:after {
       bottom: -4px;
     }';
   }

 } // end underline thickness
 else {
    echo ':root {
      --nectar-border-thickness: 2px;
    }';
 }


 /*-------------------------------------------------------------------------*/
 /* 2.4. General Links
 /*-------------------------------------------------------------------------*/
 if( isset($nectar_options['general-link-style']) && 
     'basic-underline' === $nectar_options['general-link-style'] ) {
       echo '.wpb_text_column a, 
       p a,
       .nectar-fancy-box .inner a, 
       .nectar-fancy-ul a,
       .nectar_team_member_overlay .team-desc a,
       .main-content > .row > h1 a,
       .main-content > .row > h2 a,
       .main-content > .row > h3 a,
       .main-content > .row > h4 a,
       .main-content > .row > h5 a, 
       .main-content > .row > h6 a,
       .wp-block-quote a {
         color: inherit;
         text-decoration: underline;
       }
       .nectar-cta p a,
       .nectar_team_member_overlay .team-desc .bottom_meta a {
        text-decoration: none;
       }
       ';

 } 
 else {

  echo '.span_12.light .wpb_text_column a {
    transition: 0.3s ease opacity, 0.3s ease color;
  }
  .span_12.light .wpb_text_column a:not(:hover) {
    opacity: 0.7;
  }';

 }

 /*-------------------------------------------------------------------------*/
 /* 3. Nectar Slider Font Sizing
 /*-------------------------------------------------------------------------*/
	 $heading_size = (!empty($nectar_options['use-custom-fonts']) && $nectar_options['use-custom-fonts'] == 1 && $nectar_options['nectar_slider_heading_font_size'] != '-') ? intval($nectar_options['nectar_slider_heading_font_size']) : 60;
	 $caption_size = (!empty($nectar_options['use-custom-fonts']) && $nectar_options['use-custom-fonts'] == 1 && $nectar_options['home_slider_caption_font_size'] != '-') ? intval($nectar_options['home_slider_caption_font_size']) : 24;

	 echo '@media only screen and (min-width: 1000px) and (max-width: 1300px) {
	    .nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2,
	    .nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
	    .full-width-content .vc_span12 .swiper-slide .content h2 {
			font-size: ' .$heading_size*0.75 . 'px!important;
			line-height: '.$heading_size*0.85 .'px!important;
		}

		.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p,
		.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p,
	    .full-width-content .vc_span12 .swiper-slide .content p {
			font-size: ' .$caption_size *0.75 . 'px!important;
			line-height: '.$caption_size *1.3 .'px!important;
		}
	}

	@media only screen and (min-width : 691px) and (max-width : 999px) {
		.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2,
		.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
	    .full-width-content .vc_span12 .swiper-slide .content h2 {
			font-size: ' . (($heading_size*0.55 > 20) ? $heading_size*0.55 : 20) . 'px!important;
			line-height: '. (($heading_size*0.55 > 20) ? $heading_size*0.65 : 27) .'px!important;
		}

		.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p,
		.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p,
	    .full-width-content .vc_span12 .swiper-slide .content p {
			font-size: ' . (($caption_size *0.55 > 12) ? $caption_size *0.55 : 12). 'px!important;
			line-height: '.$caption_size *1 .'px!important;
		}
	}

	@media only screen and (max-width : 690px) {
		.nectar-slider-wrap[data-full-width="true"][data-fullscreen="false"] .swiper-slide .content h2,
    .full-width-content .vc_col-sm-12 .nectar-slider-wrap[data-fullscreen="false"] .swiper-slide .content h2,
		.nectar-slider-wrap[data-full-width="boxed-full-width"][data-fullscreen="false"] .swiper-slide .content h2,
	    .full-width-content .vc_span12 .nectar-slider-wrap[data-fullscreen="false"] .swiper-slide .content h2 {
			font-size: ' .(($heading_size*0.25 > 14) ? $heading_size*0.25 : 14) . 'px!important;
			line-height: '.(($heading_size*0.25 > 14) ? $heading_size*0.35 : 20) .'px!important;
		}

		.nectar-slider-wrap[data-full-width="true"][data-fullscreen="false"] .swiper-slide .content p,
		.nectar-slider-wrap[data-full-width="boxed-full-width"][data-fullscreen="false"]  .swiper-slide .content p,
	    .full-width-content .vc_span12 .nectar-slider-wrap[data-fullscreen="false"] .swiper-slide .content p {
			font-size: ' .(($caption_size *0.32 > 10) ? $caption_size *0.32 : 10) . 'px!important;
			line-height: '.(($caption_size *0.73 > 10) ? $caption_size *0.73 : 18) .'px!important;
		}
	}
	';


  /*-------------------------------------------------------------------------*/
  /* 4. Header Navigation Transparent Coloring
  /*-------------------------------------------------------------------------*/
	if( (!empty($nectar_options['transparent-header']) && 
      $nectar_options['transparent-header'] === '1' && 
      !nectar_is_contained_header() ) ||
      'fullscreen-inline-images' === $side_widget_class && !nectar_is_contained_header() ) {

    // Dynamic coloring.
    $starting_color   = (empty($nectar_options['header-starting-color'])) ? '#ffffff' : $nectar_options['header-starting-color'];
    $starting_opacity = (isset($nectar_options['header-starting-opacity']) && !empty($nectar_options['header-starting-opacity'])) ? $nectar_options['header-starting-opacity'] : '0.75';
      
    echo ':root {
      --nectar-starting-header-color: ' . esc_attr($starting_color) . ';  
    }';

    // Core.
    echo 'body #header-outer[data-transparent-header="true"],
    body #header-outer[data-transparent-header="true"] .cart-menu{
      transition:background-color 0.30s ease,box-shadow 0.30s ease,margin 0.25s ease, backdrop-filter 0.25s ease;
      -webkit-transition:background-color 0.30s ease,box-shadow 0.30s ease,margin 0.25s ease, backdrop-filter 0.25s ease;
    }
    body #header-outer[data-transparent-header="true"].transparent,
    body #header-outer[data-transparent-header="true"].transparent .cart-menu {
      transition:border-color 0.30s ease;
      -webkit-transition:border-color 0.30s ease
    }

    body.original #header-outer[data-transparent-header="true"].transparent,
    body.ascend #header-outer[data-transparent-header="true"].transparent,
    body.material #header-outer[data-transparent-header="true"].transparent {
      box-shadow:none;
    }
    body #header-outer[data-transparent-header="true"].transparent {
      background-color:transparent!important;
      -webkit-box-shadow:none;
    	box-shadow:none;
      border-bottom:1px solid rgba(255,255,255,0.25)
    }


    #header-outer[data-transparent-header="true"][data-transparent-shadow-helper="true"].transparent:not(.dark-slide):before {
      background: linear-gradient(to top,rgba(255,255,255,0) 0%,rgba(0,0,0,0) 1%,rgba(0,0,0,0.04) 16%,rgba(0,0,0,0.23) 75%,rgba(0,0,0,0.33) 100%);
      position: absolute;
      pointer-events: none;
      height: 120%;
      top: 0;
      left: 0;
      width: 100%;
      content: "";
      display: block;
    }

    body.material #header-outer[data-transparent-header="true"].transparent {
      border-bottom: 0;
    }

    body #header-outer[data-transparent-header="true"].transparent nav >ul >li >a{
      margin-bottom:-1px
    }
    body #header-outer[data-transparent-header="true"][data-format="centered-menu"].transparent.side-widget-open.small-nav nav >ul >li >a{
      margin-bottom:0
    }

    #header-outer[data-transparent-header="true"].transparent #logo img,
    #header-outer[data-transparent-header="true"] #logo .starting-logo,
    #header-outer[data-transparent-header="true"].light-text #logo img{
      opacity:0;
      -ms-filter:"alpha(opacity=0)"
    }
    #header-outer[data-transparent-header="true"].transparent #logo .starting-logo,
    #header-outer[data-transparent-header="true"].light-text #logo .starting-logo {
      opacity:1;
      -ms-filter:"alpha(opacity=100)"
    }

    #header-outer[data-transparent-header="true"].transparent:not(.dark-text):not(.dark-slide) #logo picture.starting-logo:not(.dark-version) img {
      opacity: 1!important;
    }
    #header-outer[data-transparent-header="true"].light-text:not(.dark-text) #logo picture.starting-logo:not(.dark-version) img {
      opacity: 1!important;
    }
    #header-outer[data-transparent-header="true"].transparent.dark-slide #logo picture.starting-logo.dark-version img,
    #header-outer[data-transparent-header="true"].dark-text #logo picture.starting-logo.dark-version img,
    #header-outer[data-transparent-header="true"].dark-text #logo picture.starting-logo.dark-version {
      opacity: 1!important;
    }
 
    body #header-outer[data-transparent-header="true"][data-remove-border="true"],
    #header-outer[data-transparent-header="true"][data-full-width="true"][data-remove-border="true"] .cart-menu,
    #header-outer[data-transparent-header="true"][data-full-width="false"][data-remove-border="true"].transparent .cart-menu,
    .ascend #header-outer.transparent[data-transparent-header="true"][data-full-width="true"][data-remove-border="true"] #top .nectar-woo-cart .cart-contents {
      border:none!important
    }
    body #header-outer.transparent[data-transparent-header="true"][data-remove-border="true"]{
      transition:background-color 0.3s ease 0s,box-shadow 0.3s ease 0s,margin 0.25s ease, backdrop-filter 0.25s ease;
      -webkit-transition:background-color 0.3s ease 0s,box-shadow 0.3s ease 0s,margin 0.25s ease, backdrop-filter 0.25s ease;
    }
    body:not(.ascend) #header-outer[data-transparent-header="true"][data-remove-border="true"]:not(.transparent) .cart-menu:after{
      border-left:1px solid rgba(0,0,0,0)
    }
    body #header-outer[data-transparent-header="true"][data-remove-border="true"].transparent.pseudo-data-transparent {
      border-color:transparent!important
    }


    #header-outer.light-text #top nav >ul >li >a,
    #header-outer.light-text #top nav ul #search-btn a span,
    #header-outer.light-text #top nav ul #nectar-user-account a span,
    #header-outer.light-text #top .container nav >ul >li >a >.sf-sub-indicator i,
    #header-outer.light-text .cart-menu .cart-icon-wrap .icon-salient-cart,
    #header-outer.light-text .nectar-header-text-content,
    .light-text .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a,
    .ascend #boxed #header-outer.light-text .cart-menu .cart-icon-wrap .icon-salient-cart,
    #header-outer[data-lhe="default"].light-text #top nav .sf-menu .current-menu-item >a,
    body[data-header-inherit-rc="true"] #header-outer[data-lhe="default"].light-text #top nav .sf-menu > .sfHover:not(#social-in-menu) > a,
    #header-outer[data-lhe="default"].light-text #top nav >ul >li >a:hover,
    #header-outer[data-lhe="default"].light-text #top nav .sf-menu >.sfHover >a{
      color:#fff!important;
      opacity:'.esc_attr($starting_opacity).';
    }
    #header-outer.light-text #logo,
    #header-outer.light-text .sf-menu > li.nectar-regular-menu-item > a > .nectar-menu-icon {
    	color: #fff;
    }

    body #header-outer.light-text #top .container nav ul .slide-out-widget-area-toggle a .lines,
    body #header-outer.light-text #top .container nav ul .slide-out-widget-area-toggle a .lines:before,
    body #header-outer.light-text #top .container nav ul .slide-out-widget-area-toggle a .lines:after,
    body #header-outer.light-text #top .container nav ul .slide-out-widget-area-toggle .lines-button:after {
      background-color:#fff !important
    }
    #header-outer.dark-text #top nav >ul >li >a,
    #header-outer.dark-text #top nav ul #search-btn a span,
    #header-outer.dark-text #top nav ul #nectar-user-account a span,
    #header-outer.dark-text nav >ul >li >a >.sf-sub-indicator i,
    .dark-text .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a,
    #header-outer.dark-text .cart-menu .cart-icon-wrap .icon-salient-cart,
    .ascend #boxed #header-outer.dark-text .cart-menu .cart-icon-wrap .icon-salient-cart,
    #header-outer[data-lhe="default"].dark-text #top nav .sf-menu .current-menu-item >a{
      color:#444!important;
      opacity:'.esc_attr($starting_opacity).';
    }
    #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a .lines,
    #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a .lines:before,
    #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a .lines:after{
      background-color:#444 !important
    }
    #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a .lines,
    #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a .lines,
    #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a .lines-button:after{
      opacity: '.esc_attr($starting_opacity).';
    }';

    if( '1.0' !== $starting_opacity ) {
      echo '#header-outer.light-text #top nav >ul >li >a:hover,
      #header-outer.light-text #top nav .sf-menu >.sfHover >a,
      #header-outer.light-text #top nav .sf-menu >.current_page_ancestor >a,
      #header-outer.light-text #top nav .sf-menu >.current-menu-item >a,
      #header-outer.light-text #top nav .sf-menu >.current-menu-ancestor >a,
      #header-outer.light-text #top nav .sf-menu >.current_page_item >a,
      #header-outer.light-text #top nav >ul >li >a:hover >.sf-sub-indicator >i,
      #header-outer.light-text #top nav >ul >.sfHover >a >span >i,
      #header-outer.light-text #top nav ul #search-btn a:hover span,
      #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a:hover span,
      #header-outer.light-text #top nav .sf-menu >.current-menu-item >a i,
      #header-outer.light-text #top nav .sf-menu >.current-menu-ancestor >a i,
      #header-outer.light-text .cart-outer:hover .icon-salient-cart,
      .light-text .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a:hover,
      .light-text .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"])[class*="current"] a,
      .dark-text .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a:hover,
      .dark-text .nectar-mobile-only.mobile-header li[class*="current"] a,
      .ascend #boxed #header-outer.light-text .cart-outer:hover .cart-menu .cart-icon-wrap .icon-salient-cart,
      .ascend #boxed #header-outer.dark-text .cart-outer:hover .cart-menu .cart-icon-wrap .icon-salient-cart,
      #header-outer.dark-text #top nav >ul >li >a:hover,
      #header-outer.dark-text #top nav .sf-menu >.sfHover >a,
      #header-outer.dark-text #top nav .sf-menu >.current_page_ancestor >a,
      #header-outer.dark-text #top nav .sf-menu >.current-menu-item >a,
      #header-outer.dark-text #top nav .sf-menu >.current-menu-ancestor >a,
      #header-outer.dark-text #top nav .sf-menu >.current_page_item >a,
      #header-outer.dark-text #top nav >ul >li >a:hover >.sf-sub-indicator >i,
      #header-outer.dark-text #top nav >ul >.sfHover >a >span >i,
      #header-outer.dark-text #top nav ul #search-btn a:hover span,
      #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a:hover span,
      #header-outer.dark-text #top nav .sf-menu >.current-menu-item >a i,
      #header-outer.dark-text #top nav .sf-menu >.current-menu-ancestor >a i,
      #header-outer.dark-text .cart-outer:hover .icon-salient-cart,
      #header-outer.light-text.side-widget-open #top nav ul .slide-out-widget-area-toggle a .lines,
      #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a:hover .lines,
      #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a:hover .lines-button:after,
      #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a:hover .lines:before,
      #header-outer.light-text #top nav ul .slide-out-widget-area-toggle a:hover .lines:after,
      #header-outer.dark-text.side-widget-open #top nav ul .slide-out-widget-area-toggle a .lines,
      #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a:hover .lines,
      #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a:hover .lines:before,
      #header-outer.dark-text #top nav ul .slide-out-widget-area-toggle a:hover .lines:after {
        opacity:1!important
      }';
    }

    echo '#header-outer.light-text #top nav >ul >li >a,
    #header-outer.light-text #top nav >ul >li *,
    body.ascend #header-outer.light-text .cart-menu,
    #header-outer.dark-text #top nav >ul >li >a,
    #header-outer.dark-text #top nav >ul >li *,
    .ascend #header-outer[data-full-width="true"].dark-text #top nav ul #search-btn a,
    .ascend #header-outer[data-full-width="true"].dark-text #top nav ul .slide-out-widget-area-toggle a,
    .ascend #header-outer[data-full-width="true"].light-text #top nav ul #search-btn a,
    .ascend #header-outer[data-full-width="true"].light-text #top nav ul .slide-out-widget-area-toggle a,
    body.ascend #header-outer.dark-text .cart-menu{
      border-color:rgba(0,0,0,0.1)
    }
    body #header-outer.transparent[data-transparent-header="true"].dark-slide,
    body #header-outer.transparent[data-transparent-header="true"].dark-slide .cart-menu,
    body #header-outer.transparent[data-transparent-header="true"].dark-slide >#top nav ul #nectar-user-account >div,
    body #header-outer.transparent[data-transparent-header="true"].dark-slide >#top nav ul .slide-out-widget-area-toggle >div,
    #boxed #header-outer.transparent[data-transparent-header="true"].dark-slide,
    body #header-outer.transparent[data-transparent-header="true"][data-remove-border="true"].dark-slide .cart-menu:after{
      border-color:rgba(0,0,0,0.08) !important
    }

    #header-outer.transparent.dark-slide #top nav >ul >li[class*="button_bordered"] >a:before,
    .dark-slide.transparent #top nav >ul >li[class*="button_bordered"] >a:before {
      border-color:#000!important
    }
    #header-outer[data-transparent-header="true"].no-pointer-events {
      pointer-events:none
    }
    #header-outer[data-transparent-header="true"].no-pointer-events.side-widget-open.style-slide-out-from-right,
    #header-outer[data-transparent-header="true"].no-pointer-events.side-widget-open.style-slide-out-from-right-alt{
      pointer-events:auto
    }
    #header-outer[data-transparent-header="true"].transparent >header #logo img.dark-version,
    #header-outer[data-transparent-header="true"].light-text >header #logo img.dark-version,
    #header-outer[data-transparent-header="true"].transparent.dark-slide >header #logo img,
    #header-outer[data-transparent-header="true"].dark-text >header #logo img,
    #header-outer[data-transparent-header="true"].dark-text.side-widget-open >header #logo img.dark-version{
      opacity:0!important
    }
    #header-outer[data-transparent-header="true"].transparent.dark-slide >header #logo img.dark-version,
    #header-outer[data-transparent-header="true"].dark-text >header #logo img.dark-version,
    #header-outer[data-transparent-header="true"].dark-text.side-widget-open >header #logo img.starting-logo{
      opacity:1!important
    }
    ';

		echo '
				#header-outer.transparent #top #logo,
        #header-outer.transparent #top .logo-clone,
				#header-outer.transparent #top #logo:hover {
				 	color: '.esc_attr($starting_color).';
				 }
         #header-outer:not(.transparent).light-text #top #logo {
           color: '.esc_attr($starting_color).'!important;
         }

				 #header-outer.transparent[data-permanent-transparent="false"] #top .slide-out-widget-area-toggle.mobile-icon i:before,
				 #header-outer.transparent[data-permanent-transparent="false"] #top .slide-out-widget-area-toggle.mobile-icon i:after,
				 body.material.mobile #header-outer.transparent:not([data-permanent-transparent="1"]) header .slide-out-widget-area-toggle a .close-line,
				 body #header-outer[data-permanent-transparent="1"].transparent:not(.dark-slide) > #top .span_9 > .slide-out-widget-area-toggle .lines-button:after,
				 body #header-outer[data-permanent-transparent="1"].transparent:not(.dark-slide) > #top .span_9 > .slide-out-widget-area-toggle .lines:before,
				 body #header-outer[data-permanent-transparent="1"].transparent:not(.dark-slide) > #top .span_9 > .slide-out-widget-area-toggle .lines:after,
         #header-outer[data-lhe="animated_underline"].transparent .nectar-header-text-content a:after {
					 background-color: '.esc_attr($starting_color).'!important;
				 }

				 #header-outer.transparent #top nav > ul > li > a,
				 #header-outer.transparent #top nav > .sf-menu > li > a,
         #header-outer.transparent .slide-out-widget-area-toggle a i.label,
         #header-outer.transparent #top .span_9 > .slide-out-widget-area-toggle a.using-label .label,
				 #header-outer.transparent #top nav ul #search-btn a .icon-salient-search,
				 #header-outer.transparent #top nav ul #nectar-user-account a span,
				 #header-outer.transparent #top nav > ul > li > a > .sf-sub-indicator i,
				 #header-outer.transparent .cart-menu .cart-icon-wrap .icon-salient-cart,
				 .ascend #boxed #header-outer.transparent .cart-menu .cart-icon-wrap .icon-salient-cart,
         #header-outer.transparent #top .sf-menu > li.nectar-regular-menu-item > a > .nectar-menu-icon,
         #header-outer.transparent .nectar-header-text-content,
         #header-outer.transparent .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a
				  {
				 	color: '.esc_attr($starting_color).'!important;
				 	opacity: '.esc_attr($starting_opacity).';
          will-change: opacity, color;
					transition: opacity 0.2s ease, color 0.2s ease;
				 }
				#header-outer.transparent[data-lhe="default"] #top nav > ul > li > a:hover,
				#header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .sfHover:not(#social-in-menu) > a,
				#header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .current_page_ancestor > a,
				#header-outer.transparent #top nav .sf-menu > .current-menu-item > a,
				#header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a,
				#header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .current-menu-item > a,
				#header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .current_page_item > a,
				#header-outer.transparent #top nav > ul > li > a:hover > .sf-sub-indicator > i,
        #header-outer.transparent #top .sf-menu > .sfHover > a .sf-sub-indicator i,
				#header-outer.transparent #top nav > ul > .sfHover > a > span > i,
				#header-outer.transparent #top nav ul #search-btn a:hover span,
				#header-outer.transparent #top nav ul #nectar-user-account a:hover span,
				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:hover span,
				#header-outer.transparent #top nav .sf-menu > .current-menu-item > a i,
				body #header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .current_page_item > a .sf-sub-indicator i,
				#header-outer.transparent #top nav .sf-menu > .current-menu-ancestor > a i,
				body #header-outer.transparent[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a i,
				#header-outer.transparent .cart-outer:hover .icon-salient-cart,
				.ascend #boxed #header-outer.transparent .cart-outer:hover .cart-menu .cart-icon-wrap .icon-salient-cart,
				#header-outer.transparent[data-permanent-transparent="false"]:not(.dark-slide) #top .span_9 > a[class*="mobile-"] > *,
				#header-outer.transparent[data-permanent-transparent="false"]:not(.dark-slide) #top #mobile-cart-link i,
				#header-outer[data-permanent-transparent="1"].transparent:not(.dark-slide) #top .span_9 > a[class*="mobile-"] > *,
				#header-outer[data-permanent-transparent="1"].transparent:not(.dark-slide) #top #mobile-cart-link i,
        #header-outer.transparent #top .sf-menu > li.nectar-regular-menu-item > a:hover > .nectar-menu-icon,
        #header-outer.transparent #top .sf-menu > li.nectar-regular-menu-item.sfHover > a:hover > .nectar-menu-icon,
        #header-outer.transparent #top .sf-menu > li.nectar-regular-menu-item[class*="current-"] > a:hover > .nectar-menu-icon,
        #header-outer.transparent .nectar-header-text-content:hover,
        #header-outer.transparent:not(.dark-slide) .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a:hover,
        .transparent:not(.dark-slide) .nectar-mobile-only.mobile-header li[class*="menu-item-btn-style-button-border"]:not(:hover) > a
				{
					opacity: 1;
					color: '.esc_attr($starting_color).'!important;
				}';

        if( '1.0' !== $starting_opacity ) {
  				echo '#header-outer.transparent[data-lhe="animated_underline"] #top nav > ul > li > a:hover,
          #header-outer.transparent[data-lhe="animated_underline"] #top nav > ul > li > a:focus,
  				#header-outer.transparent[data-lhe="animated_underline"] #top nav .sf-menu > .sfHover > a,
  				#header-outer.transparent[data-lhe="animated_underline"] #top nav .sf-menu > .current-menu-ancestor > a,
  				#header-outer.transparent[data-lhe="animated_underline"] #top nav .sf-menu > .current_page_item > a,
          #header-outer.transparent[data-lhe="default"] #top nav > ul > li > a:focus,
          #header-outer.transparent .slide-out-widget-area-toggle a:hover i.label,
          #header-outer.transparent #top nav ul #search-btn a:focus span,
  				#header-outer.transparent #top nav ul #nectar-user-account a:focus span,
  				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:focus span,
          #header-outer.transparent .nectar-woo-cart .cart-contents:focus .icon-salient-cart {
  					opacity: 1;
  				}';
        }

				echo '#header-outer[data-lhe="animated_underline"].transparent #top nav > ul > li > a .menu-title-text:after,
        #header-outer.transparent #top nav>ul>li[class*="button_bordered"]>a:before,
        #header-outer.transparent .nectar-mobile-only.mobile-header li a .menu-title-text:after {
					border-color: '.esc_attr($starting_color).'!important;
				}

        .transparent .left-aligned-ocm .lines-button i:before,
        .transparent .left-aligned-ocm .lines-button i:after,
        .transparent .left-aligned-ocm .lines-button:after,
				#header-outer.transparent > #top nav ul .slide-out-widget-area-toggle a .lines,
				#header-outer.transparent > #top nav ul .slide-out-widget-area-toggle a .lines:before,
				#header-outer.transparent > #top nav ul .slide-out-widget-area-toggle a .lines:after,
				body.material #header-outer.transparent .slide-out-widget-area-toggle a .close-line,
				#header-outer.transparent > #top nav ul .slide-out-widget-area-toggle .lines-button:after {
					background-color: '.esc_attr($starting_color).'!important;
				}

				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a .lines,
				body.material:not(.mobile) #header-outer.transparent .slide-out-widget-area-toggle a .close-line,
				#header-outer.transparent:not(.side-widget-open) #top nav ul .slide-out-widget-area-toggle a .lines-button:after {
					opacity: '.esc_attr($starting_opacity).';
				}';


        if( '1.0' !== $starting_opacity ) {
  				echo '#header-outer.transparent.side-widget-open #top nav ul .slide-out-widget-area-toggle a .lines,
  				body.material #header-outer.transparent .slide-out-widget-area-toggle a:hover .close-line,
  				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:hover .lines,
          #header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:hover .lines-button:after,
  				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:hover .lines:before,
  				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:hover .lines:after,
          #header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:focus .lines-button:after,
  				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:focus .lines:before,
  				#header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:focus .lines:after,
          #header-outer.transparent #top nav ul .slide-out-widget-area-toggle a:focus .lines {
  					opacity: 1;
  				}';
        }

		$dark_header_color = (!empty($nectar_options['header-transparent-dark-color'])) ? $nectar_options['header-transparent-dark-color'] : '#000000';

    echo ':root {
      --nectar-starting-dark-header-color: ' . esc_attr($dark_header_color) . ';  
    }';

		echo '
		#header-outer.transparent[data-permanent-transparent="false"].dark-slide #top .slide-out-widget-area-toggle.mobile-icon i:before,
		#header-outer.transparent[data-permanent-transparent="false"].dark-slide #top .slide-out-widget-area-toggle.mobile-icon i:after {
			background-color: '.esc_attr($dark_header_color).'!important;
		}';

		echo '#header-outer.transparent.dark-slide > #top nav > ul > li > a,
		#header-outer.transparent.dark-row > #top nav > ul > li > a,
    #header-outer.transparent.dark-row .slide-out-widget-area-toggle a i.label,
    #header-outer.transparent.dark-slide .slide-out-widget-area-toggle a i.label,
    #header-outer.transparent.dark-slide #top .span_9 > .slide-out-widget-area-toggle a.using-label .label,
    #header-outer.transparent.dark-row #top .span_9 > .slide-out-widget-area-toggle a.using-label .label,
		#header-outer.transparent.dark-slide > #top nav ul #search-btn a span,
		#header-outer.transparent.dark-row > #top nav ul #search-btn a span,
		#header-outer.transparent.dark-slide > #top nav ul #nectar-user-account a span,
		#header-outer.transparent.dark-row > #top nav ul #nectar-user-account a span,
		#header-outer.transparent.dark-slide > #top nav > ul > li > a > .sf-sub-indicator [class^="icon-"],
		#header-outer.transparent.dark-slide > #top nav > ul > li > a > .sf-sub-indicator [class*=" icon-"],
		#header-outer.transparent.dark-row > #top nav > ul > li > a > .sf-sub-indicator [class*=" icon-"],
		#header-outer.transparent.dark-slide .cart-menu .cart-icon-wrap .icon-salient-cart,
		#header-outer.transparent.dark-row .cart-menu .cart-icon-wrap .icon-salient-cart,
		body.ascend[data-header-color="custom"] #boxed #header-outer.transparent.dark-slide > #top .cart-outer .cart-menu .cart-icon-wrap i,
		body.ascend #boxed #header-outer.transparent.dark-slide > #top .cart-outer .cart-menu .cart-icon-wrap i,
		#header-outer[data-permanent-transparent="1"].transparent.dark-slide .mobile-search .icon-salient-search,
		#header-outer[data-permanent-transparent="1"].transparent.dark-slide .mobile-user-account .icon-salient-m-user,
	  #header-outer[data-permanent-transparent="1"].transparent.dark-slide #top #mobile-cart-link i,
		#header-outer.transparent[data-permanent-transparent="false"].dark-slide #top .span_9 > a[class*="mobile-"] > *,
 		#header-outer.transparent[data-permanent-transparent="false"].dark-slide #top #mobile-cart-link i,
    #header-outer.transparent.dark-slide #top .sf-menu > li.nectar-regular-menu-item > a > .nectar-menu-icon,
    #header-outer.transparent.dark-slide .nectar-header-text-content,
    #header-outer.dark-slide .nectar-mobile-only.mobile-header li:not([class*="menu-item-btn-style"]) a  {
		 	color: '.esc_attr($dark_header_color).'!important;
		 }

		#header-outer.transparent.dark-slide > #top nav ul .slide-out-widget-area-toggle a .lines-button i:after,
		#header-outer.transparent.dark-slide > #top nav ul .slide-out-widget-area-toggle a .lines-button i:before,
		#header-outer.transparent.dark-slide > #top nav ul .slide-out-widget-area-toggle .lines-button:after,
    .transparent.dark-slide .left-aligned-ocm .lines-button i:before,
    .transparent.dark-slide .left-aligned-ocm .lines-button i:after,
    .transparent.dark-slide .left-aligned-ocm .lines-button:after,
		body.marterial #header-outer.transparent.dark-slide > #top nav .slide-out-widget-area-toggle a .close-line,
		body #header-outer[data-permanent-transparent="1"].transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle.mobile-icon .lines-button:after,
		body #header-outer[data-permanent-transparent="1"].transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle.mobile-icon .lines:before,
		body #header-outer[data-permanent-transparent="1"].transparent.dark-slide > #top .span_9 > .slide-out-widget-area-toggle.mobile-icon .lines:after,
     #header-outer[data-lhe="animated_underline"].transparent.dark-slide .nectar-header-text-content a:after {
			background-color: '.esc_attr($dark_header_color).'!important;
		}

		#header-outer.transparent.dark-slide > #top nav > ul > li > a:hover,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .sfHover > a,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .current_page_ancestor > a,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .current-menu-item > a,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .current-menu-ancestor > a,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .current_page_item > a,
		#header-outer.transparent.dark-slide > #top nav > ul > li > a:hover > .sf-sub-indicator > i,
		#header-outer.transparent.dark-slide > #top nav > ul > .sfHover > a > span > i,
		#header-outer.transparent.dark-slide > #top nav ul #search-btn a:hover span,
		#header-outer.transparent.dark-slide > #top nav ul #nectar-user-account a:hover span,
		body #header-outer.dark-slide.transparent[data-lhe="default"] #top nav .sf-menu > .current_page_item > a .sf-sub-indicator i,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .current-menu-item > a i,
		#header-outer.transparent.dark-slide > #top nav .sf-menu > .current-menu-ancestor > a i,
		body #header-outer.dark-slide.transparent[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a i,
		#header-outer.transparent.dark-slide  > #top .cart-outer:hover .icon-salient-cart,
		body.ascend[data-header-color="custom"] #boxed #header-outer.transparent.dark-slide > #top .cart-outer:hover .cart-menu .cart-icon-wrap i,
		#header-outer.transparent.dark-slide > #top #logo,
    #header-outer.transparent.dark-slide > #top .logo-clone,
		#header-outer.transparent[data-lhe="default"].dark-slide #top nav .sf-menu > .current_page_item > a,
		#header-outer.transparent[data-lhe="default"].dark-slide #top nav .sf-menu > .current-menu-ancestor > a,
		#header-outer.transparent[data-lhe="default"].dark-slide #top nav > ul > li > a:hover,
		#header-outer.transparent[data-lhe="default"].dark-slide #top nav .sf-menu > .sfHover:not(#social-in-menu) > a,
		#header-outer.transparent.dark-slide #top nav > ul > .sfHover > a > span > i,
		body.ascend[data-header-color="custom"] #boxed #header-outer.transparent.dark-slide > #top .cart-outer:hover .cart-menu .cart-icon-wrap i,
		.swiper-wrapper .swiper-slide[data-color-scheme="dark"] .slider-down-arrow i.icon-default-style[class^="icon-"],
		.slider-prev.dark-cs i,
		.slider-next.dark-cs i,
		.swiper-container .dark-cs.slider-prev .slide-count span,
		.swiper-container .dark-cs.slider-next .slide-count span,
    #header-outer.transparent.dark-slide #top .sf-menu > li.nectar-regular-menu-item > a:hover > .nectar-menu-icon,
    #header-outer.transparent.dark-slide #top .sf-menu > li.nectar-regular-menu-item.sfHover > a:hover > .nectar-menu-icon,
    #header-outer.transparent.dark-slide #top .sf-menu > li.nectar-regular-menu-item[class*="current-"] > a:hover > .nectar-menu-icon,
    #header-outer.transparent.dark-slide .nectar-header-text-content:hover,
    .transparent.dark-slide .nectar-mobile-only.mobile-header li[class*="menu-item-btn-style-button-border"]:not(:hover) > a {
			color: '.esc_attr($dark_header_color).'!important;
		}

		#header-outer[data-lhe="animated_underline"].transparent.dark-slide #top nav > ul > li > a .menu-title-text:after,
    #header-outer.dark-slide.transparent:not(.side-widget-open) #top nav>ul>li[class*="button_bordered"]>a:before,
    #header-outer.dark-slide .nectar-mobile-only.mobile-header li a .menu-title-text:after {
			border-color: '.esc_attr($dark_header_color).'!important;
		}

		.swiper-container[data-bullet_style="scale"] .slider-pagination.dark-cs .swiper-pagination-switch.swiper-active-switch i,
		.swiper-container[data-bullet_style="scale"] .slider-pagination.dark-cs .swiper-pagination-switch:hover i {
			background-color: '.esc_attr($dark_header_color).';
		}

		.slider-pagination.dark-cs .swiper-pagination-switch {
			 border: 1px solid '.esc_attr($dark_header_color).';
			 background-color: transparent;
		}
		.slider-pagination.dark-cs .swiper-pagination-switch:hover {
			background: none repeat scroll 0 0 '.esc_attr($dark_header_color).';
		}

		.slider-pagination.dark-cs .swiper-active-switch {
			 background: none repeat scroll 0 0 '.esc_attr($dark_header_color).';
		}
		';

	   $dark_header_color = str_replace("#", "", $dark_header_color);
		 $darkcolorR = hexdec( substr( $dark_header_color, 0, 2 ) );
		 $darkcolorG = hexdec( substr( $dark_header_color, 2, 2 ) );
		 $darkcolorB = hexdec( substr( $dark_header_color, 4, 2 ) );
		 echo '
		 #fp-nav:not(.light-controls) ul li a span:after {
			 background-color: #'.esc_attr($dark_header_color).';
		 }
		 #fp-nav:not(.light-controls) ul li a span {
			 box-shadow: inset 0 0 0 8px rgba('.$darkcolorR.','.$darkcolorG.','.$darkcolorB.',0.3);
			 -webkit-box-shadow: inset 0 0 0 8px rgba('.$darkcolorR.','.$darkcolorG.','.$darkcolorB.',0.3);
		 }
		 body #fp-nav ul li a.active span  {
			 box-shadow: inset 0 0 0 2px rgba('.$darkcolorR.','.$darkcolorG.','.$darkcolorB.',0.8);
			 -webkit-box-shadow: inset 0 0 0 2px rgba('.$darkcolorR.','.$darkcolorG.','.$darkcolorB.',0.8);
		 }';

	 } // Using transparent theme option

	// Custom off canvas navigation menu button coloring.
  $ocm_menu_btn_bg_color = false;
  $ocm_menu_btn_color    = false;
  $full_width_header     = (!empty($nectar_options['header-fullwidth']) && $nectar_options['header-fullwidth'] === '1') ? true : false;

  if ( $headerFormat === 'centered-menu-under-logo' ) {
    if ( $side_widget_class === 'slide-out-from-right-hover' && $user_set_side_widget_area === '1' ) {
      $side_widget_class = 'slide-out-from-right';
    }
    $full_width_header = false;
  }
  if ( $side_widget_class === 'slide-out-from-right-hover' && $user_set_side_widget_area === '1' ) {
    $full_width_header = true;
  }

  $ocm_menu_btn_color_non_compatible = ( 'ascend' === $theme_skin && true === $full_width_header ) ? true : false;

  if( true !== $ocm_menu_btn_color_non_compatible &&
  isset($nectar_options['header-slide-out-widget-area-menu-btn-color']) &&
  !empty( $nectar_options['header-slide-out-widget-area-menu-btn-color'] ) ) {

    $ocm_menu_btn_color = $nectar_options['header-slide-out-widget-area-menu-btn-color'];

    echo 'body #header-outer[data-has-menu][data-format][data-padding] #top .slide-out-widget-area-toggle[data-custom-color="true"] a i.label,
    body #header-outer.transparent #top .slide-out-widget-area-toggle[data-custom-color="true"] a i.label {
      color: '.esc_attr($ocm_menu_btn_color).'!important;
    }
    body #header-outer[data-has-menu][data-format][data-padding][data-using-logo] > #top .slide-out-widget-area-toggle[data-custom-color="true"] .lines-button:after,
    body #header-outer[data-has-menu][data-format][data-padding][data-using-logo] > #top .slide-out-widget-area-toggle[data-custom-color="true"] a .lines-button i:before,
    body #header-outer[data-has-menu][data-format][data-padding][data-using-logo] > #top .slide-out-widget-area-toggle[data-custom-color="true"] a .lines-button i.lines:after,
    body.material #header-outer .slide-out-widget-area-toggle[data-custom-color="true"] a .close-line,
    body.material #header-outer[data-using-logo].transparent .slide-out-widget-area-toggle[data-custom-color="true"] a .close-line,
    body.material:not(.mobile) #header-outer.transparent .slide-out-widget-area-toggle[data-custom-color="true"] a .close-line {
      background-color: '.esc_attr($ocm_menu_btn_color).'!important;
      opacity: 1;
    }
    #header-outer.transparent #top nav ul .slide-out-widget-area-toggle[data-custom-color="true"] a .lines {
      opacity: 1;
    }';

  }

  if( true !== $ocm_menu_btn_color_non_compatible &&
  isset($nectar_options['header-slide-out-widget-area-menu-btn-bg-color']) &&
  !empty( $nectar_options['header-slide-out-widget-area-menu-btn-bg-color'] ) ) {

    $ocm_menu_btn_bg_color = $nectar_options['header-slide-out-widget-area-menu-btn-bg-color'];
    $mobile_padding_mod    = ( $mobile_logo_height < 38 ) ? 10 : 0;

    echo 'body #header-outer #top .slide-out-widget-area-toggle[data-custom-color="true"] a:before {
      background-color: '.esc_attr($ocm_menu_btn_bg_color ).';
    }

    @media only screen and (max-width: 999px) {
      body #header-outer #logo {
       position: relative;
       margin: '.esc_attr($mobile_padding_mod).'px 0;
      }

      #header-space {
 			 height: '. (intval($mobile_logo_height) + 24 + ($mobile_padding_mod*2)) .'px;
 		 }
   
    #top #mobile-cart-link, #top .mobile-search, #header-outer #top .mobile-user-account {
      padding: 0 10px;
    }

    }';

    if( true === $menu_label &&
    !empty($mobile_breakpoint) &&
    $mobile_breakpoint != 1000 &&
    $headerFormat !== 'left-header' &&
    $has_main_menu === 'true' ) {
      echo '@media only screen and (min-width: 1000px) and (max-width: '.esc_attr($mobile_breakpoint).'px) {
        body #header-outer[data-format="menu-left-aligned"]:not([data-format="left-header"]):not([data-format="centered-menu-bottom-bar"]) #top nav > .buttons {
          margin-right: 140px;
        }
      }';
    }

  }

  // Circular ocm icon.
  if( false === $menu_label &&
  false !== $ocm_menu_btn_bg_color &&
  isset($nectar_options['header-slide-out-widget-area-icon-style']) &&
  !empty( $nectar_options['header-slide-out-widget-area-icon-style'] ) &&
  'circular' === $nectar_options['header-slide-out-widget-area-icon-style']) {
    echo 'body #header-outer #top .slide-out-widget-area-toggle[data-custom-color] a:before {
      height: 46px;
      padding-bottom: 0;
    }
    body #header-outer #top .slide-out-widget-area-toggle[data-custom-color] a {
      padding: 0 12px;
    }
    body[data-button-style] #header-outer .slide-out-widget-area-toggle[data-custom-color="true"] a:before {
      border-radius: 100px!important;
    }
    body[data-slide-out-widget-area-style*="fullscreen"] #top .slide-out-widget-area-toggle:not(.small) a .close-wrap {
      height: 22px;
    }
    body[data-slide-out-widget-area-style*="fullscreen"] #top .slide-out-widget-area-toggle .close-line {
      left: 10px;
    }
    #header-outer[data-format="centered-menu-bottom-bar"] #top .slide-out-widget-area-toggle[data-custom-color="true"] a:before,
    #header-outer[data-format="centered-menu-under-logo"] #top .slide-out-widget-area-toggle[data-custom-color="true"] a:before {
      transform: translateY(-14px);
    }
    #header-outer[data-format="centered-menu-bottom-bar"][data-header-button_style*="scale"] #top nav ul .slide-out-widget-area-toggle[data-custom-color="true"] a:hover:before,
    #header-outer[data-format="centered-menu-under-logo"][data-header-button_style*="scale"] #top nav ul .slide-out-widget-area-toggle[data-custom-color="true"] a:hover:before {
      transform: scale(1.1) translateY(-14px);
    }

    @media only screen and (min-width: 1000px) {
      #header-outer li[class*="menu-item-btn-style"] > a:before, 
      #header-outer li[class*="menu-item-btn-style"] > a:after {
        height: calc(100% + 28px);
      }
    }
    @media only screen and (max-width: 999px) {
      body #header-outer #top .slide-out-widget-area-toggle[data-custom-color] a:before {
        height: 40px;
      }
      body #header-outer #top .slide-out-widget-area-toggle[data-custom-color] a {
        padding: 0 9px;
      }
      #top .slide-out-widget-area-toggle[data-custom-color] a > span {
        transform: scale(0.8);
      }
    }

    @media only screen and (max-width: 690px) {
      body #header-outer[data-full-width="true"] header > .container {
        padding: 0 25px;
      }
    }
    ';
  }



  /*-------------------------------------------------------------------------*/
  /* 5. Extended Responsive Theme Option
  /*-------------------------------------------------------------------------*/
	global $woocommerce;

  $ext_padding = '90';

	if( !empty($nectar_options['responsive']) &&
		$nectar_options['responsive'] === '1' &&
		!empty($nectar_options['ext_responsive']) &&
		$nectar_options['ext_responsive'] === '1') {

    if( isset( $nectar_options['ext_responsive_padding'] ) &&
    !empty( $nectar_options['ext_responsive_padding'] ) &&
    '90' !== $nectar_options['ext_responsive_padding'] ) {
  		$ext_padding = $nectar_options['ext_responsive_padding'];
  	}

    echo ':root {
      --wp--style--root--padding-left: '.esc_attr($ext_padding).'px;
      --wp--style--root--padding-right: '.esc_attr($ext_padding).'px;
      --container-padding: '.esc_attr($ext_padding).'px;
      --nectar-resp-container-padding: '.esc_attr($ext_padding).'px;
   }';

		echo '@media only screen and (min-width: 1000px) {
			    .container,
					body[data-header-format="left-header"] .container,
					.woocommerce-tabs .full-width-content .tab-container,
					.nectar-recent-posts-slider .flickity-page-dots,
					.post-area.standard-minimal.full-width-content .post .inner-wrap,
					.material #search-outer #search  {
			      max-width: 1425px;
					  width: 100%;
					  margin: 0 auto;
					  padding: 0px '.esc_attr($ext_padding).'px;
			    }';

          if( 'left-header' === $headerFormat ) {
            echo 'body[data-header-format="left-header"] .container,
            body[data-header-format="left-header"] .woocommerce-tabs .full-width-content .tab-container,
  					body[data-header-format="left-header"] .nectar-recent-posts-slider .flickity-page-dots,
  			    body[data-header-format="left-header"] .post-area.standard-minimal.full-width-content .post .inner-wrap {
  			    	padding: 0 60px;
  			    }';
          }


			    echo 'body .container .page-submenu.stuck .container:not(.tab-container):not(.normal-container),
					.nectar-recent-posts-slider .flickity-page-dots,
			    #nectar_fullscreen_rows[data-footer="default"] #footer-widgets .container,
					#nectar_fullscreen_rows[data-footer="default"] #copyright .container {
			    	  padding: 0px '.esc_attr($ext_padding).'px!important;
			    }

  				.swiper-slide .content {
  				  padding: 0px '.esc_attr($ext_padding).'px;
  				}';

          if( 'left-header' === $headerFormat ) {
    				echo 'body[data-header-format="left-header"] .container .page-submenu.stuck .container:not(.tab-container),
    				body[data-header-format="left-header"] .nectar-recent-posts-slider .flickity-page-dots {
    			    	  padding: 0px 60px!important;
    			    }

    				body[data-header-format="left-header"] .swiper-slide .content {
    				  padding: 0px 60px;
    				}';
          }

				echo 'body .container .container:not(.tab-container):not(.recent-post-container):not(.normal-container) {
					width: 100%!important;
					padding: 0!important;
				}

				body .carousel-heading .container .carousel-next {
					right: 10px;
				}
				body .carousel-heading .container .carousel-prev {
					right: 35px;
				}
				.carousel-wrap[data-full-width="true"] .carousel-heading .portfolio-page-link {
					left: '.esc_attr($ext_padding).'px;
				}
				.carousel-wrap[data-full-width="true"] .carousel-heading {
					margin-left: -20px;
					margin-right: -20px;
				}
				#ajax-content-wrap .carousel-wrap[data-full-width="true"] .carousel-next {
					right: '.esc_attr($ext_padding).'px;
				}
		   	#ajax-content-wrap .carousel-wrap[data-full-width="true"] .carousel-prev {
					right: ' . (intval($ext_padding) + 25) . 'px;
				}
				.carousel-wrap[data-full-width="true"] {
					padding: 0;
				}
				.carousel-wrap[data-full-width="true"] .caroufredsel_wrapper {
					padding: 20px;
				}

				#search-outer #search #close a {
					right: '.esc_attr($ext_padding).'px;
				}

        body.material #search-outer #search #close {
					right: '.esc_attr($ext_padding).'px;
				}
        body.material #search-outer #search #close a {
          right: 12px;
        }';

        if( !empty($nectar_options['boxed_layout']) && $nectar_options['boxed_layout'] === '1' ) {
  				echo '#boxed,
  				#boxed #header-outer,
  				#boxed #slide-out-widget-area-bg.fullscreen,
  				#boxed #featured,
  				body[data-footer-reveal="1"] #boxed #footer-outer,
  				#boxed .orbit > div,
  				#boxed #featured article,
  				body.ascend #boxed #search-outer {
  				   max-width: 1400px!important;
  				   width: 90%!important;
  				   min-width: 980px;
  				}

  				body[data-hhun="1"] #boxed #header-outer[data-remove-fixed="1"],
  				body[data-hhun="1"] #boxed #header-secondary-outer,
  				#boxed #header-outer[data-format="centered-menu-bottom-bar"][data-condense="true"]:not(.fixed-menu),
  				#boxed #header-secondary-outer.centered-menu-bottom-bar {
  					width: 100%!important;
  				}

  				#boxed #search-outer #search #close a {
  					right: 0!important;
  				}

  				#boxed .container {
  				  width: 92%;
  				  padding: 0;
  			   }

  				#boxed #footer-outer #footer-widgets, #boxed #footer-outer #copyright {
  					padding-left: 0;
  					padding-right: 0;
  				}

  				#boxed .carousel-wrap[data-full-width="true"] .carousel-heading .portfolio-page-link {
  					left: 35px;
  				}

  				#boxed .carousel-wrap[data-full-width="true"] .carousel-next {
  					right: 35px!important;
  				}
  				#boxed .carousel-wrap[data-full-width="true"] .carousel-prev {
  					right: 60px!important;
  				}';
        }


			 echo '}';

		  // Custom max width theme option.
		  if(!empty($nectar_options['max_container_width'])) {
		  	   echo '@media only screen and (min-width: 1000px) {
						 .container,
						 body[data-header-format="left-header"] .container,
						 .woocommerce-tabs .full-width-content .tab-container,
						 .nectar-recent-posts-slider .flickity-page-dots,
						 .post-area.standard-minimal.full-width-content .post .inner-wrap,
						 .material #search-outer #search {
							 max-width: '.esc_attr($nectar_options['max_container_width']).'px;
						 }
					 }
           
           :root {
              --container-width: '.esc_attr($nectar_options['max_container_width']).'px;
           }
           html body {
            --wp--style--global--content-size: '.esc_attr($nectar_options['max_container_width']).'px;
            --wp--style--global--wide-size: '.intval($nectar_options['max_container_width'] + 300).'px;
           }';
		  }
      else {
        echo '@media only screen and (min-width: 1025px) {
          :root {
            --container-width: 1425px;
         }
         html body {
          --wp--style--global--content-size: 1425px;
         }
        }';
      }

    } else {
      echo ':root {
        --container-padding: 0.01px;
     }
  
     :root {
      --container-width: 880px;
    }
    @media only screen and (min-width: 1300px) {
      :root {
        --container-width:1100px;
      }
     }
     @media only screen and (max-width: 690px) {
      :root {
        --container-width: 420px;
      }
    }
     
      @media only screen and (max-width: 479px) {
        :root {
          --container-width: 320px;
        }
      }';
  
    }

    /*-------------------------------------------------------------------------*/
    /* 6. Form Sizing
    /*-------------------------------------------------------------------------*/

    /*-------------------------------------------------------------------------*/
    /* 6.1. Fancy Selects
    /*-------------------------------------------------------------------------*/
    if( isset( $nectar_options['form-fancy-select'] ) &&
        !empty($nectar_options['form-fancy-select']) &&
        '1' === $nectar_options['form-fancy-select'] ) {
      echo '
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #aaa;
        padding: 4px;
      }
      body[data-fancy-form-rcs="1"] .variations select {
        padding: 8px;
      }

      body[data-fancy-form-rcs="1"] .select2-container .select2-choice,
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single {
        height: auto;
        background-color:transparent;
        border-color:#e0e0e0;
        padding-top:5px;
        padding-bottom:5px;
        -webkit-transition:background-color 0.15s cubic-bezier(.39,.71,.56,.98),color 0.15s cubic-bezier(.39,.71,.56,.98);
        transition:background-color 0.15s cubic-bezier(.39,.71,.56,.98),color 0.15s cubic-bezier(.39,.71,.56,.98)
      }
      body[data-fancy-form-rcs="1"].woocommerce-checkout .select2-container--default .select2-selection--single {
        color: #555;
      }
      body[data-fancy-form-rcs="1"] .select2-container .select2-choice:hover .select2-arrow b:after{
        -webkit-transition:border-color 0.15s cubic-bezier(.39,.71,.56,.98);
        transition:border-color 0.15s cubic-bezier(.39,.71,.56,.98)
      }
      body[data-fancy-form-rcs="1"] .select2-drop,
      body[data-fancy-form-rcs="1"] .select2-dropdown{
        border:none;
        background-color:#fff;
        box-shadow:0 0 6px rgba(0,0,0,0.2)
      }
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-dropdown {
        box-shadow: 0 6px 28px rgba(0,0,0,0.08);
      }
      body[data-fancy-form-rcs="1"] .select2-container,
      body[data-fancy-form-rcs="1"] .select2-drop,
      body[data-fancy-form-rcs="1"] .select2-search,
      .select2-search input{
        font-size:14px
      }
      body[data-fancy-form-rcs="1"] .select2-container:not(.select2-dropdown-open) .select2-choice:hover .select2-arrow b:after,
      body[data-fancy-form-rcs="1"] .select2-container--default:hover .select2-selection--single .select2-selection__arrow b,
      body[data-fancy-form-rcs="1"] .select2-container--open .select2-selection__arrow b {
        border-top-color:#fff
      }
      body[data-fancy-form-rcs="1"] .select2-dropdown-open .select2-choice .select2-arrow b:after,
      body[data-fancy-form-rcs="1"] .select2-container--default:hover .select2-selection--single .select2-selection__arrow b,
      body[data-fancy-form-rcs="1"] .select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-bottom-color:#fff
      }
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        width: 30px;
        top: 0;
      }
      body[data-fancy-form-rcs="1"] .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 10px;
        padding-right: 30px;
      }
      body[data-fancy-form-rcs="1"] .select2-container .select2-dropdown {
        color: #000;
      }
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
        padding-right: 20px;
        line-height: 1.9;
      }
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default .select2-selection--single .select2-selection__arrow {
        width: 12px;
      }
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-results__option[aria-selected=true],
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f0f0f0!important;
      }
      body[data-fancy-form-rcs="1"] .select2-drop.select2-drop-above .select2-search input,
      body[data-fancy-form-rcs="1"] .select2-drop.select2-drop-below .select2-search input,
      body[data-fancy-form-rcs="1"] .select2-drop .select2-search input[type="text"]{
        padding:0 4px!important;
        margin-top:7px!important
      }
      body[data-fancy-form-rcs="1"] .select2-container .select2-choice:hover,
      body[data-fancy-form-rcs="1"] .select2-container .select2-choice:hover >.select2-chosen,
      body[data-fancy-form-rcs="1"] .select2-dropdown-open .select2-choice,
      body[data-fancy-form-rcs="1"] .select2-dropdown-open .select2-choice >.select2-chosen{
        color:#fff!important;
        box-shadow:none;
        -webkit-box-shadow:none
      }
      body[data-fancy-form-rcs="1"].admin-bar .select2-drop.select2-drop-above.select2-drop-active{
        margin-top:-33px
      }
      body[data-fancy-form-rcs="1"] .fancy-select-wrap{
        padding-top:12px
      }
      body[data-fancy-form-rcs="1"] .fancy-select-wrap label{
        padding-bottom:0;
        font-size:12px;
        display:inline-block;
        color:#acacac!important
      }
      body[data-fancy-form-rcs="1"] .woocommerce-ordering select,
      body[data-fancy-form-rcs="1"] select {
        color: inherit;
      }
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single .select2-selection__rendered,
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-results__option--highlighted[aria-selected],
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default:hover .select2-selection--single .select2-selection__rendered,
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default.select2-container--open .select2-selection--single .select2-selection__rendered {
        color: inherit!important;
      }

      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default .select2-selection--single .select2-selection__arrow b,
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default:hover .select2-selection--single .select2-selection__arrow b {
        border-top-color: inherit;
        transition: transform 0.3s ease;
      }

      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: inherit transparent transparent transparent;
        border-width: 5px 4px 0 4px;
        transform: rotate(180deg);
      }

      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-bottom-color: inherit;
      }
      body[data-fancy-form-rcs="1"] .select2-container--default:hover .select2-selection--single .select2-selection__rendered,
      body[data-fancy-form-rcs="1"] .select2-container--default.select2-container--open .select2-selection--single .select2-selection__rendered {
        color: #fff!important;
      }
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single:hover .select2-selection__placeholder {
        color: #fff;
      }';
    }


    /*-------------------------------------------------------------------------*/
    /* 6.2. Input Coloring
    /*-------------------------------------------------------------------------*/
    $woo_qty_style             = ( isset( $nectar_options['qty_button_style'] ) && !empty( $nectar_options['qty_button_style'] ) ) ? $nectar_options['qty_button_style'] : 'default';
    $form_input_bg_color       = ( isset( $nectar_options['form-input-bg-color'] ) && !empty($nectar_options['form-input-bg-color']) ) ? $nectar_options['form-input-bg-color'] : false;
    $form_input_text_color     = ( isset( $nectar_options['form-input-text-color'] ) && !empty($nectar_options['form-input-text-color']) ) ? $nectar_options['form-input-text-color'] : false;
    $form_input_border_color   = ( isset( $nectar_options['form-input-border-color'] ) && !empty($nectar_options['form-input-border-color']) ) ? $nectar_options['form-input-border-color'] : false;
    $form_input_border_color_h = ( isset( $nectar_options['form-input-border-color-hover'] ) && !empty($nectar_options['form-input-border-color-hover']) ) ? $nectar_options['form-input-border-color-hover'] : false;
    $minimal_form_style        = ( isset( $nectar_options['form-style'] ) && !empty($nectar_options['form-style']) && 'minimal' === $nectar_options['form-style'] ) ? true : false;

    $form_input_props = '';
    $form_input_props_hover = '';

    // Input BG
    if( $form_input_bg_color ) {
      $form_input_props .= 'background-color: '.esc_attr($form_input_bg_color).';';
    }

    // Input Border
    if( $form_input_border_color ) {

      if( 'default' !== $woo_qty_style ) {
        echo 'body .cart div.quantity, body .woocommerce-mini-cart div.quantity {
          border-color: '.esc_attr($form_input_border_color).';
        }';
      }

      $form_input_props .= 'border-color: '.esc_attr($form_input_border_color).';';
    }

    // Input Border Hover
    if( $form_input_border_color_h ) {

      if( 'default' !== $woo_qty_style ) {
        echo 'body .cart div.quantity:hover, body .woocommerce-mini-cart div.quantity:hover {
          border-color: '.esc_attr($form_input_border_color_h).';
        }';
      }

      $form_input_props_hover .= 'border-color: '.esc_attr($form_input_border_color_h).';';
    }

    // Input Text Coloring
    if( $form_input_text_color ) {
      $form_input_props .= 'color: '.esc_attr($form_input_text_color).';';
      echo '.widget_search .search-form input[type=text]::placeholder {
        color: '.esc_attr($form_input_text_color).';
        opacity: 0.7;
      }';
    }


    /*-------------------------------------------------------------------------*/
    /* 6.3. Input Padding
    /*-------------------------------------------------------------------------*/

    // Form input button padding.
    if( isset( $nectar_options['form-input-spacing'] ) && !empty($nectar_options['form-input-spacing']) ) {

      // Top.
      $form_input_padding_top = false;
      if( isset( $nectar_options['form-input-spacing']['padding-top'] ) &&
          !empty($nectar_options['form-input-spacing']['padding-top']) ) {
        $form_input_padding_top = $nectar_options['form-input-spacing']['padding-top'];
      }

      // Right.
      $form_input_padding_right = false;
      if( isset( $nectar_options['form-input-spacing']['padding-right'] ) &&
          !empty($nectar_options['form-input-spacing']['padding-right']) ) {
        $form_input_padding_right = $nectar_options['form-input-spacing']['padding-right'];
      }

      // Verify a custom val was set for atleast one prop before creating rule.
      if( false !== $form_input_padding_top ||
      false !== $form_input_padding_right ) {

        $skin_selector = ( 'ascend' === $theme_skin || true === $minimal_form_style ) ? 'body .container-wrap ' : '';

        echo esc_attr($skin_selector).'input[type=text],
        '.esc_attr($skin_selector).'input[type=email],
        '.esc_attr($skin_selector).'input[type=password],
        '.esc_attr($skin_selector).'input[type=tel],
        '.esc_attr($skin_selector).'input[type=url],
        '.esc_attr($skin_selector).'input[type=search],
        '.esc_attr($skin_selector).'input[type=date],
        '.esc_attr($skin_selector).'input[type=number],
        '.esc_attr($skin_selector).'textarea,
        .woocommerce input#coupon_code {';
          if( false !== $form_input_padding_top ) {
            echo 'padding-top: '.esc_attr($form_input_padding_top) .'; padding-bottom: '.esc_attr($form_input_padding_top) .';';
          }
          if( false !== $form_input_padding_right ) {
            echo 'padding-right: '.esc_attr($form_input_padding_right) .'; padding-left: '.esc_attr($form_input_padding_right) .';';
          }

          echo 'line-height: 1em;';

        echo '}';

        echo 'body[data-fancy-form-rcs="1"] .variations select,
        body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single {';
          if( false !== $form_input_padding_top ) {
            echo 'padding-top: '.esc_attr($form_input_padding_top) .'; padding-bottom: '.esc_attr($form_input_padding_top) .';';
          }
        echo '}';

        echo '.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.2em; }';

      }

    } // End form input padding.


    /*-------------------------------------------------------------------------*/
    /* 6.4. Submit Button Sizing
    /*-------------------------------------------------------------------------*/

    // See through submit
    if( isset( $nectar_options['form-submit-btn-style'] ) && 'see-through' === $nectar_options['form-submit-btn-style'] ) {
      echo '
      body[data-form-submit="see-through"] input[type=submit]:hover,
      body[data-form-submit="see-through"] button[type=submit]:not(.search-widget-btn):hover {
        color: #fff!important;
      }
      body[data-form-submit="see-through"] .container-wrap input[type=submit],
      body[data-form-submit="see-through"] .container-wrap button[type=submit]:not(.search-widget-btn) {
        padding:15px 22px!important
      }
 
      body[data-form-submit="see-through"] input[type=submit],
      body[data-form-submit="see-through"].woocommerce #respond input#submit,
      body[data-form-submit="see-through"] button[type=submit]:not(.search-widget-btn),
      [data-form-submit="see-through"] .woocommerce #order_review #payment #place_order {
        background-color:transparent!important;
        border:2px solid #000;
      }
    ';
    }


    // Form submit button padding.
    if( isset( $nectar_options['form-submit-spacing'] ) && !empty($nectar_options['form-submit-spacing']) ) {

      // Top.
      $form_submit_padding_top = false;
      if( isset( $nectar_options['form-submit-spacing']['padding-top'] ) &&
          !empty($nectar_options['form-submit-spacing']['padding-top']) ) {
        $form_submit_padding_top = $nectar_options['form-submit-spacing']['padding-top'];
      }

      // Right.
      $form_submit_padding_right = false;
      if( isset( $nectar_options['form-submit-spacing']['padding-right'] ) &&
          !empty($nectar_options['form-submit-spacing']['padding-right']) ) {
        $form_submit_padding_right = $nectar_options['form-submit-spacing']['padding-right'];
      }


      // Verify a custom val was set for atleast one prop before creating rule.
      if( false !== $form_submit_padding_top ||
      false !== $form_submit_padding_right) {

        echo 'body[data-form-submit="default"] .container-wrap input[type=submit],
        body[data-form-submit="regular"] .container-wrap input[type=submit],
        body[data-form-submit="regular"] .container-wrap button[type=submit]:not(.search-widget-btn),
        body[data-form-submit="see-through"] .container-wrap input[type=submit],
        body[data-form-submit="see-through"] .container-wrap button[type=submit]:not(.search-widget-btn),
        body[data-button-style="rounded"].ascend .container-wrap input[type="submit"],
        body[data-button-style="rounded"].ascend .container-wrap button[type="submit"]:not(.search-widget-btn),
        .wc-proceed-to-checkout .button.checkout-button,
        .woocommerce #order_review #payment #place_order,
        body.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
        .woocommerce-page button[type="submit"].single_add_to_cart_button,
        body[data-form-submit="regular"].woocommerce-page .container-wrap button[type=submit].single_add_to_cart_button,
        .nectar-post-grid-wrap .load-more,
        .row .wpforms-form button[type="submit"] {';

          if( false !== $form_submit_padding_top ) {
            echo 'padding-top: '.esc_attr($form_submit_padding_top) .'!important; padding-bottom: '.esc_attr($form_submit_padding_top) .'!important;';
          }
          if( false !== $form_submit_padding_right ) {
            echo 'padding-right: '.esc_attr($form_submit_padding_right) .'!important; padding-left: '.esc_attr($form_submit_padding_right) .'!important;';
          }

          echo 'line-height: 1.2em;';

        echo '}';

      }

    } // End form submit button padding.

    /*-------------------------------------------------------------------------*/
    /* 6.5. Minimal Styling
    /*-------------------------------------------------------------------------*/
    if( isset( $nectar_options['form-style'] ) && !empty($nectar_options['form-style']) && 'minimal' === $nectar_options['form-style'] ) {
      echo 'body[data-form-style="minimal"] input[type="text"],
      body[data-form-style="minimal"] textarea,
      body[data-form-style="minimal"] input[type="email"],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type="email"],
      body[data-form-style="minimal"] input[type=password],
      body[data-form-style="minimal"] input[type=tel],
      body[data-form-style="minimal"] input[type=url],
      body[data-form-style="minimal"] input[type=search],
      body[data-form-style="minimal"] input[type=date],
      body[data-form-style="minimal"] input[type=number],
      body[data-form-style="minimal"] select {
        background-color: rgba(0,0,0,0.035);
        box-shadow:none;
        -webkit-box-shadow:none;
        border:none;
        position:relative;
        margin:0;
        font-size:14px;
        border-bottom: 2px solid #e0e0e0;
        -webkit-transition: border-color 0.2s ease;
        transition: border-color 0.2s ease;
        border-radius: 0;
      }
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type="text"],
      body[data-form-style="minimal"] .container-wrap .span_12.light textarea,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type="email"],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=password],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=tel],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=url],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=search],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=date],
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=number],
      body[data-form-style="minimal"] .container-wrap .span_12.light select {
        color:#fff;
        border-top: 0;
        border-left: 0;
        border-right: 0;
        background-color: rgba(255,255,255,0.04);
      	box-shadow: none;
        border-bottom: 2px solid transparent;
      }
      body[data-form-style="minimal"].material .span_12.light .select2-container--default .select2-selection--single,
      body[data-form-style="minimal"][data-fancy-form-rcs="1"].material .span_12.light .select2-container--default .select2-selection--single {
        background-color: rgba(255,255,255,0.04);
      }

      body[data-form-style="minimal"] .container-wrap .span_12.light input[type="text"]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light textarea:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type="email"]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=password]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=tel]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=url]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=search]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=date]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type=number]:focus,
      body[data-form-style="minimal"] .container-wrap .span_12.light select:focus {
      	border-top: 0;
        border-left: 0;
        border-right: 0;
      	border-color: #fff;
      }

      body[data-form-style="minimal"] textarea,
      body[data-form-style="minimal"].woocommerce #review_form #respond textarea{
        padding: 20px;
      }
      body[data-form-style="minimal"] .widget_search .search-form .search-submit{
        top:30px
      }';
    } // End minimal styling.


    /*-------------------------------------------------------------------------*/
    /* 6.6. Input Sizing
    /*-------------------------------------------------------------------------*/
    if( isset( $nectar_options['form-input-font-size'] ) && !empty($nectar_options['form-input-font-size']) ) {

      echo 'span.wpcf7-not-valid-tip,
      .woocommerce input#coupon_code,
      body[data-fancy-form-rcs="1"] .select2-container,
      body[data-fancy-form-rcs="1"] .select2-drop,
      body[data-fancy-form-rcs="1"] .select2-search,
      .select2-search input,
      body[data-form-style="minimal"] .container-wrap .span_12.light input[type="email"] {
        font-size: '.esc_attr($nectar_options['form-input-font-size']).'px;
      }';

      $form_input_props .= 'font-size: '.esc_attr($nectar_options['form-input-font-size']).'px;';

    }


    /*-------------------------------------------------------------------------*/
    /* 6.8. Input Border Width
    /*-------------------------------------------------------------------------*/
    if( isset( $nectar_options['form-input-border-width'] ) &&
        !empty($nectar_options['form-input-border-width']) &&
        'default' !== $nectar_options['form-input-border-width'] ) {

        if( 'default' !== $woo_qty_style ) {
          echo 'body .cart div.quantity, body .woocommerce-mini-cart div.quantity {
            border-width: '.esc_attr($nectar_options['form-input-border-width']).';
          }';
        }

        if( 'original' === $theme_skin && false === $minimal_form_style ) {
          $form_input_props .= 'border-style: solid;';
        }

        $form_input_props .= 'border-width: '.esc_attr($nectar_options['form-input-border-width']).';';

    }

    /*-------------------------------------------------------------------------*/
    /* 6.9. Input Styling Output
    /*-------------------------------------------------------------------------*/

    if( !empty($form_input_props) ) {

      echo '.container-wrap input[type=text],
      .container-wrap input[type=email],
      .container-wrap input[type=password],
      .container-wrap input[type=tel],
      .container-wrap input[type=url],
      .container-wrap input[type=search],
      .container-wrap input[type=date],
      .container-wrap input[type=number],
      .container-wrap textarea,
      .container-wrap select,
      body > #review_form_wrapper.modal input[type=text],
      body > #review_form_wrapper.modal textarea,
      body > #review_form_wrapper.modal select,
      body > #review_form_wrapper.modal input[type=email],
      .select2-container--default .select2-selection--single,
      body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single,
      .woocommerce input#coupon_code,
      .material.woocommerce-page[data-form-style="default"] input#coupon_code,
      body[data-form-style="minimal"] input[type="text"],
      body[data-form-style="minimal"] textarea,
      body[data-form-style="minimal"] input[type="email"],
      body[data-form-style="minimal"] input[type=password],
      body[data-form-style="minimal"] input[type=tel],
      body[data-form-style="minimal"] input[type=url],
      body[data-form-style="minimal"] input[type=search],
      body[data-form-style="minimal"] input[type=date],
      body[data-form-style="minimal"] input[type=number],
      body[data-form-style="minimal"] select { '.$form_input_props.' }';

    }

    if( !empty($form_input_props_hover) ) {

      echo '.container-wrap input[type=text]:hover,
      .container-wrap input[type=email]:hover,
      .container-wrap input[type=password]:hover,
      .container-wrap input[type=tel]:hover,
      .container-wrap input[type=url]:hover,
      .container-wrap input[type=search]:hover,
      .container-wrap input[type=date]:hover,
      .container-wrap input[type=number]:hover,
      .container-wrap textarea:hover,
      .container-wrap select:hover,
      body > #review_form_wrapper.modal input[type=text]:hover,
      body > #review_form_wrapper.modal textarea:hover,
      body > #review_form_wrapper.modal select:hover,
      body > #review_form_wrapper.modal input[type=email]:hover,
      .select2-container--default .select2-selection--single:hover,
      .woocommerce input#coupon_code:hover,
      .material.woocommerce-page[data-form-style="default"] input#coupon_code:hover,
      body[data-form-style="minimal"] input[type="text"]:hover,
      body[data-form-style="minimal"] textarea:hover,
      body[data-form-style="minimal"] input[type="email"]:hover,
      body[data-form-style="minimal"] input[type=password]:hover,
      body[data-form-style="minimal"] input[type=tel]:hover,
      body[data-form-style="minimal"] input[type=url]:hover,
      body[data-form-style="minimal"] input[type=search]:hover,
      body[data-form-style="minimal"] input[type=date]:hover,
      body[data-form-style="minimal"] input[type=number]:hover,
      body[data-form-style="minimal"] select:hover { '.$form_input_props_hover.' }';

    }



    /*-------------------------------------------------------------------------*/
    /* 7. Blog
    /*-------------------------------------------------------------------------*/

    /*-------------------------------------------------------------------------*/
    /* 7.1. Blog Single Width
    /*-------------------------------------------------------------------------*/
    $blog_hide_sidebar = ( isset( $nectar_options['blog_hide_sidebar'] ) && !empty($nectar_options['blog_hide_sidebar']) ) ? $nectar_options['blog_hide_sidebar'] : false;

    if( '1' === $blog_hide_sidebar && isset( $nectar_options['blog_width'] ) && !empty($nectar_options['blog_width']) ) {
      if( 'default' !== $nectar_options['blog_width'] ) {

        $blog_full_width_row_func = ( isset( $nectar_options['blog_full_width_row_func'] ) && 'limit' === $nectar_options['blog_full_width_row_func'] ) ? true : false;
        
        echo '
        @media only screen and (min-width: 1000px) {
          body.single-post #ajax-content-wrap .container-wrap.no-sidebar .post-area,
          body.single-post #ajax-content-wrap .container-wrap.no-sidebar .comment-list >li,
          body.single-post #ajax-content-wrap .container-wrap.no-sidebar .comment-wrap h3#comments,
          body.single-post #ajax-content-wrap .comment-wrap #respond,
          body.single-post #ajax-content-wrap #page-header-bg.fullscreen-header h1,
          body.single-post #ajax-content-wrap #page-header-bg[data-post-hs="default_minimal"] h1,
          body.single-post #ajax-content-wrap .heading-title[data-header-style="default_minimal"] .entry-title,
          .single-post .featured-media-under-header__content,
          [data-style="parallax_next_only"].blog_next_prev_buttons .inner {
            max-width: '.esc_attr($nectar_options['blog_width']).';
            margin-left: auto;
            margin-right: auto;
          }';
          
          if( $blog_full_width_row_func && $boxed_layout != '1' ) {

            $ext_padding_doubled = intval($ext_padding)*2;
            $max_container_width = ( isset($nectar_options['max_container_width'])) ? intval($nectar_options['max_container_width']) : 1425;
            $max_container_width -= $ext_padding_doubled;
            $max_container_width_half = $max_container_width/2;

            if( $headerFormat != 'left-header' ) {
              echo 'body.single-post .container-wrap.no-sidebar .post-area .wpb_row.full-width-content:not(.blog_next_prev_buttons),
            body.single-post .container-wrap.no-sidebar .post-area .full-width-section .row-bg-wrap, 
            body.single-post .container-wrap.no-sidebar .post-area .full-width-section > .nectar-video-wrap,
            body.single-post .container-wrap.no-sidebar .post-area .full-width-section .video-color-overlay {
              margin-left: max(calc((50vw - '.esc_attr($ext_padding).'px) * -1),-'.esc_attr($max_container_width_half).'px)!important;
              left: 50%!important;
              width: min(calc(100vw - '.esc_attr($ext_padding_doubled).'px),'.esc_attr($max_container_width).'px)!important;
            }';
            } 
            else {

              $ext_padding_mod = 60;
              $ext_padding_doubled = 120;
              $max_container_width = ( isset($nectar_options['max_container_width'])) ? intval($nectar_options['max_container_width']) : 1425;
              $max_container_width -= $ext_padding_doubled;
              $max_container_width_half = $max_container_width/2;

              echo 'body.single-post .container-wrap.no-sidebar .post-area .wpb_row.full-width-content:not(.blog_next_prev_buttons),
              body.single-post .container-wrap.no-sidebar .post-area .full-width-section .row-bg-wrap, 
              body.single-post .container-wrap.no-sidebar .post-area .full-width-section > .nectar-video-wrap,
              body.single-post .container-wrap.no-sidebar .post-area .full-width-section .video-color-overlay {
                margin-left: max(calc((50vw - 135px - '.esc_attr($ext_padding_mod).'px) * -1),-'.esc_attr($max_container_width_half).'px)!important;
                left: 50%!important;
                width: min(calc(100vw - 275px - '.esc_attr($ext_padding_doubled).'px),'.esc_attr($max_container_width).'px)!important;
              }';
            }
            

          } else {

            echo ' body.single-post .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons) {
              margin-left: -50vw!important;
              left: 50%!important;
              width: 100vw!important;
            }

          body.single-post[data-header-format="left-header"] .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons) {
              margin-left: calc(-50vw + 135px)!important;
              width: calc(100vw - 272px)!important;
              left: 50%!important;
          }';
          }

          if( !empty($nectar_options['boxed_layout']) && $nectar_options['boxed_layout'] === '1' ) {

            echo '@media only screen and (max-width: 1600px) {
              body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
              body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
                margin-left: -45vw!important;
                width: 90vw!important;
                left: 50%!important;
              }
            }';

            if( '1000px' === $nectar_options['blog_width'] ) {
              echo '@media only screen and (min-width: 1601px) {
                body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
                body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
                  margin-left: -20%!important;
                  width: 140%!important;
                  left: 0%!important;
                }
              }';
            } else if( '900px' === $nectar_options['blog_width'] ) {
              echo '@media only screen and (min-width: 1601px) {
                body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
                body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
                  margin-left: -28%!important;
                  width: 156%!important;
                  left: 0%!important;
                }
              }';

            } else if( '800px' === $nectar_options['blog_width'] ) {
              echo '@media only screen and (min-width: 1601px) {
                body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
                body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
                  margin-left: -38%!important;
                  width: 176%!important;
                  left: 0%!important;
                }
              }';
            } else if( '700px' === $nectar_options['blog_width'] ) {
              echo '@media only screen and (min-width: 1601px) {
                body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
                body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
                  margin-left: -50%!important;
                  width: 200%!important;
                  left: 0%!important;
                }
              }';
            }
          }

        echo '}';
      }
      else if( 'default' === $nectar_options['blog_width'] &&
               !empty($nectar_options['boxed_layout']) &&
               $nectar_options['boxed_layout'] === '1' ) {
        echo '@media only screen and (min-width: 1000px) {

          @media only screen and (max-width: 1600px) {
            body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
            body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
              margin-left: -45vw!important;
              width: 90vw!important;
              left: 50%!important;
            }
          }
          @media only screen and (min-width: 1601px) {
            body.single-post #boxed .container-wrap.no-sidebar .wpb_row.full-width-content:not(.blog_next_prev_buttons),
            body.single-post #boxed .container-wrap.no-sidebar .full-width-section .row-bg-wrap {
              margin-left: -20%!important;
              width: 140%!important;
              left: 0%!important;
            }
          }

        }';
      }
    } // custom width set.


    /*-------------------------------------------------------------------------*/
    /* 7.2. Blog Archives
    /*-------------------------------------------------------------------------*/
  	if( (is_archive() || is_author() || is_category() || is_home() || is_tag()) && 'post' == get_post_type() ) {

  		$blog_type = (isset($nectar_options['blog_type']) && !empty($nectar_options['blog_type'])) ? $nectar_options['blog_type'] : 'masonry-blog-fullwidth';

  		if( $blog_type === 'masonry-blog-full-screen-width' ) {

  			$blog_masonry_type = (!empty($nectar_options['blog_masonry_type'])) ? $nectar_options['blog_masonry_type'] : 'auto_meta_overlaid_spaced';

  			if( $theme_skin === 'material' ) {
  				echo 'body[data-header-resize] .container-wrap {
  					padding-top: 0;
  				}
  				body[data-bg-header="false"].archive .container-wrap {
  					padding-top: 40px;
  				}
  				body[data-bg-header="true"].archive .container-wrap {
  					padding-top: 40px!important;
  				}';

  				if( $blog_masonry_type === 'auto_meta_overlaid_spaced' || $blog_masonry_type === 'meta_overlaid' ) {
  					echo 'body[data-bg-header="true"].archive .container-wrap {
  						padding-top: 0!important;
  					}';
  				}

  			} else {

  				echo 'body[data-bg-header="false"].archive .container-wrap {
  					padding-top: 40px;
  					margin-top: 0;
  				}
  				body[data-bg-header="true"].archive .container-wrap {
  						padding-top: 40px!important;
  				}';

  				if( $blog_masonry_type === 'auto_meta_overlaid_spaced' || $blog_masonry_type === 'meta_overlaid' ) {
  					echo 'body[data-bg-header="true"].archive .container-wrap {
  						padding-top: 0!important;
  					}';
  				}

  			}

  		} // using full width masonry blog.

  	} // if archive.

    $blog_archive_text_align = (isset($nectar_options['blog_archive_text_alignment'])) ? $nectar_options['blog_archive_text_alignment'] : 'default';

    if( 'center' === $blog_archive_text_align ) {
      echo '.blog-archive-header .col.section-title,
      .blog-archive-header .inner-wrap {
        text-align: center; 
      }
      @media only screen and (min-width: 1000px) {
        .blog-archive-header .col.section-title p,
        .blog-archive-header .inner-wrap p{
          padding: 0 22%;
        }
      }
      @media only screen and (max-width: 999px) and (min-width: 691px) {
        .blog-archive-header .col.section-title p,
        .blog-archive-header .inner-wrap p{
          padding: 0 15%;
        }
      }';
    }
    else if( 'right' === $blog_archive_text_align ) {
      echo '.blog-archive-header .col.section-title,
      .blog-archive-header .inner-wrap {
        text-align: right; 
      }';
    }


    /*-------------------------------------------------------------------------*/
    /* 7.3. Blog Comments
    /*-------------------------------------------------------------------------*/
    if( isset($nectar_options['blog_comment_author_style']) &&
        $nectar_options['blog_comment_author_style'] === 'author_badge' ) {
          
          echo '
          .comment-list .bypostauthor .comment-author cite.fn {
            --nectar-author-text: "' . esc_html__('Author','salient') . '";
          }
          .comment-list .bypostauthor .comment-author cite.fn:after {
            display: inline-block;
            padding: 4px 8px;
            margin-left: 5px;
            line-height: 1;
            font-size: 11px;
            color: #fff;
            border-radius: 15px;
            content: var(--nectar-author-text);
            background-color: var(--nectar-accent-color);
          }
          .comment-list .bypostauthor .comment-author cite.fn,
          .comment-list .bypostauthor .comment-author cite.fn a {
            display: flex;
            align-items: center;
          }

          .comment-list .says {
            display: none;
          }
          body.original .bypostauthor .comment-body:before {
            display: none;
          }
          body .comment-list .comment-meta {
            line-height: 20px; 
            font-size: 12px;
          }

          #ajax-content-wrap .bypostauthor > .comment-body > .comment-author > img.avatar {
            border: none;
          }
          ';

    }


    /*-------------------------------------------------------------------------*/
    /* 8. Page Transitions
    /*-------------------------------------------------------------------------*/

    if( isset($nectar_options['ajax-page-loading']) &&
       !empty( $nectar_options['ajax-page-loading'] ) &&
       $nectar_options['ajax-page-loading'] === '1' ) {

    echo '
    #ajax-loading-screen{
      background-color:#fff;
      width:100%;
      height:100%;
      position:fixed;
      top:0;
      left:0;
      display:none;
      z-index:1000000000
    }
    #ajax-loading-screen .reveal-1,
    #ajax-loading-screen .reveal-2{
      position:absolute;
      left:100%;
      top:0;
      width:100%;
      height:100%
    }
    #ajax-loading-screen[data-effect*="horizontal_swipe"]{
      background-color:transparent!important;
      left:-100%
    }
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-method="standard"][data-effect*="horizontal_swipe"]{
      display:block
    }
    body[data-ajax-transitions="true"][data-apte="horizontal_swipe_basic"] #ajax-loading-screen .reveal-2 {
      display: none;
    }
    #ajax-loading-screen.in-from-right{
      left:0;
    }
    .no-cssanimations #ajax-loading-screen.loaded .reveal-1,
    .no-cssanimations #ajax-loading-screen.loaded .reveal-2{
      display:none
    }
    #ajax-loading-screen.loaded .reveal-1{
      backface-visibility: hidden;
      -webkit-animation:nectar-anim-effect-2-2 1.85s cubic-bezier(0.67,0,0.3,1) forwards;
      animation:nectar-anim-effect-2-2 1.85s cubic-bezier(0.67,0,0.3,1) forwards
    }
    #ajax-loading-screen.loaded .reveal-2{
      backface-visibility: hidden;
      -webkit-animation:nectar-anim-effect-2-1 1.85s cubic-bezier(0.67,0,0.3,1) forwards;
      animation:nectar-anim-effect-2-1 1.85s cubic-bezier(0.67,0,0.3,1) forwards
    }
    #ajax-loading-screen.loaded.in-from-right .reveal-1{
      -webkit-animation:nectar-anim-effect-2-1 1.85s cubic-bezier(0.67,0,0.3,1) forwards;
      animation:nectar-anim-effect-2-1 1.85s cubic-bezier(0.67,0,0.3,1) forwards
    }
    body[data-apte="horizontal_swipe_basic"] #ajax-loading-screen.loaded.in-from-right .reveal-1{
      -webkit-animation:nectar-anim-effect-2-1 1.1s cubic-bezier(0.215, 0.61, 0.355, 1) forwards;
      animation:nectar-anim-effect-2-1 1.1s cubic-bezier(0.215, 0.61, 0.355, 1) forwards
    }
    #ajax-loading-screen.loaded.in-from-right .reveal-2{
      -webkit-animation:nectar-anim-effect-2-2 1.85s cubic-bezier(0.67,0,0.3,1) forwards;
      animation:nectar-anim-effect-2-2 1.85s cubic-bezier(0.67,0,0.3,1) forwards
    }
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-effect*="horizontal_swipe"].hidden{
      display:none
    }
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-effect*="horizontal_swipe"].hidden.loaded.in-from-right {
      display: block;
    }
    @-webkit-keyframes nectar-anim-effect-2-1{
      0%{
        -ms-transform:translateX(0);
        -webkit-transform:translate3d(0,0,0);
        transform:translate3d(0,0,0)
      }
      30%, 100%{
        -ms-transform:translateX(-100%);
        -webkit-transform:translate3d(-100%,0,0);
        transform:translate3d(-100%,0,0);
        -webkit-animation-timing-function:cubic-bezier(0.67,0,0.3,1);
        animation-timing-function:cubic-bezier(0.67,0,0.3,1)
      }
    }
    @keyframes nectar-anim-effect-2-1{
      0%{
        -ms-transform:translateX(0);
        -webkit-transform:translate3d(0,0,0);
        transform:translate3d(0,0,0)
      }
      30%, 100%{
        -ms-transform:translateX(-100%);
        -webkit-transform:translate3d(-100%,0,0);
        transform:translate3d(-100%,0,0);
        -webkit-animation-timing-function:cubic-bezier(0.67,0,0.3,1);
        animation-timing-function:cubic-bezier(0.67,0,0.3,1)
      }
    }
    @-webkit-keyframes nectar-anim-effect-2-2{
      0%,14.5%{
        -ms-transform:translateX(0);
        -webkit-transform:translate3d(0,0,0);
        transform:translate3d(0,0,0)
      }
      34.5%, 100%{
        -ms-transform:translateX(-100%);
        -webkit-transform:translate3d(-100%,0,0);
        transform:translate3d(-100%,0,0);
        -webkit-animation-timing-function:cubic-bezier(0.67,0,0.3,1);
        animation-timing-function:cubic-bezier(0.67,0,0.3,1)
      }
    }
    @keyframes nectar-anim-effect-2-2{
      0%,14.5%{
        -ms-transform:translate3d(0,0,0);
        -webkit-transform:translate3d(0,0,0);
        transform:translate3d(0,0,0)
      }
      34.5%, 100%{
        -ms-transform:translate3d(-100%,0,0);
        -webkit-transform:translate3d(-100%,0,0);
        transform:translate3d(-100%,0,0);
        -webkit-animation-timing-function:cubic-bezier(0.67,0,0.3,1);
        animation-timing-function:cubic-bezier(0.67,0,0.3,1)
      }
    }
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-method="standard"],
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-effect*="horizontal_swipe"][data-method="ajax"],
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-method="standard"] .loading-icon{
      display:block;
      opacity:1
    }

    #ajax-loading-screen .material-icon {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translateX(-50%) translateY(-50%);
    }
    .nectar-material-spinner circle {
      stroke-dasharray: 187;
      stroke-dashoffset: 0;
      transform-origin: center;
      animation: nectar_material_loader_dash 1.4s ease-in-out infinite, nectar_material_loader_colors 1.8s ease-in-out infinite;
    }

    .nectar-material-spinner {
        animation: nectar_material_loader_rotate 1.4s linear infinite;
    }

    @keyframes nectar_material_loader_rotate {
      0% {
          transform: rotate(0deg);
      }
      100% {
          transform: rotate(270deg);
      }
    }

    @keyframes nectar_material_loader_dash {
      0% {
        stroke-dashoffset: 187;
      }
      50% {
        stroke-dashoffset: 46.75;
        -webkit-transform: rotate(135deg);
                transform: rotate(135deg);
      }
      100% {
        stroke-dashoffset: 187;
        -webkit-transform: rotate(450deg);
                transform: rotate(450deg);
      }
    }
    
    

    
    body #ajax-loading-screen[data-effect="center_mask_reveal"]{
      background-color:transparent
    }
    body[data-ajax-transitions="true"] #ajax-loading-screen[data-effect="center_mask_reveal"].hidden{
      display:none
    }
    #ajax-loading-screen[data-effect="center_mask_reveal"] span{
      position:absolute;
      background:#fff;
      z-index:100;
      -webkit-transition:0.8s cubic-bezier(0.12,0.75,0.4,1);
      transition:0.8s cubic-bezier(0.12,0.75,0.4,1)
    }
    #ajax-loading-screen .mask-top{
      top:0;
      left:0;
      height:50%;
      width:100%
    }
    #ajax-loading-screen .mask-right{
      top:0;
      right:0;
      height:100%;
      width:50%
    }
    #ajax-loading-screen .mask-bottom{
      bottom:0;
      right:0;
      height:50%;
      width:100%
    }
    #ajax-loading-screen .mask-left{
      top:0;
      left:0;
      height:100%;
      width:50%
    }
    #ajax-loading-screen.loaded .mask-top{
      -webkit-transform:translateY(-100%) translateZ(0);
      -ms-transform:translateY(-100%) translateZ(0);
      transform:translateY(-100%) translateZ(0)
    }
    #ajax-loading-screen.loaded .mask-right{
      -webkit-transform:translateX(100%) translateZ(0);
      -ms-transform:translateX(100%) translateZ(0);
      transform:translateX(100%) translateZ(0)
    }
    #ajax-loading-screen.loaded .mask-bottom{
      -webkit-transform:translateY(100%) translateZ(0);
      -ms-transform:translateY(100%) translateZ(0);
      transform:translateY(100%) translateZ(0)
    }
    #ajax-loading-screen.loaded .mask-left{
      -webkit-transform:translateX(-100%) translateZ(0);
      -ms-transform:translateX(-100%) translateZ(0);
      transform:translateX(-100%) translateZ(0)
    }
    #ajax-loading-screen[data-effect="center_mask_reveal"].set-to-fade span,
    #ajax-loading-screen[data-effect="center_mask_reveal"].set-to-fade.loaded span {
      width:100%;
      height:100%;
      top:0;
      left:0;
      -webkit-transform:none;
    	transform:none;
    }';
  }

    // Custom loading icon.
    if( isset($nectar_options['loading-image']['id']) && !empty($nectar_options['loading-image']['id']) ){
      echo ' .portfolio-loading, #ajax-loading-screen .loading-icon, .loading-icon, .pp_loaderIcon {
        background-image: url("'.nectar_options_img( $nectar_options["loading-image"] ).'");
      }';
    }

    // Page transitions coloring.
    if( $page_transition_bg !== '#ffffff' ) {
      echo '#ajax-loading-screen,
      #ajax-loading-screen[data-effect="center_mask_reveal"] span {
        background-color: '.esc_attr($page_transition_bg).'
      }
      .default-loading-icon {
        border-color: rgba(255,255,255,0.2);
      } ';
    }

    echo '#ajax-loading-screen .reveal-1 { background-color: '.esc_attr($page_transition_bg).'; }';
    echo '#ajax-loading-screen .reveal-2 { background-color: '.esc_attr($page_transition_bg_2).'; }';


    // Material loader color.
    $loading_icon = (isset($nectar_options['loading-icon'])) ? $nectar_options['loading-icon'] : 'default';

    if( $loading_icon === 'material' ) {

      $icon_colors = (isset($nectar_options['loading-icon-colors'])) ? $nectar_options['loading-icon-colors'] : array('from' => '#444444', 'to' => '#444444');

      echo '
      @keyframes nectar_material_loader_colors {
        0% {
          stroke: '.esc_attr($icon_colors['from']).';
        }
        50% {
          stroke: '.esc_attr($icon_colors['to']).';
        }
        100% {
          stroke: '.esc_attr($icon_colors['from']).';
        }
      }';

      
    }

    /*-------------------------------------------------------------------------*/
    /* 9. Page Header
    /*-------------------------------------------------------------------------*/

    // Blog header overlay
    
    $blog_header_type = ( isset($nectar_options['blog_header_type']) ) ? esc_attr($nectar_options['blog_header_type']) : '';

    if( isset($nectar_options['std_blog_header_overlay_color']) && 
       !empty($nectar_options['std_blog_header_overlay_color']) &&
        isset($nectar_options['std_blog_header_overlay_opacity']) && in_array($blog_header_type, array('default', 'fullscreen')) ) {

          $std_blog_header_overlay_color = esc_attr($nectar_options['std_blog_header_overlay_color']);
          $std_blog_header_overlay_opacity = esc_attr($nectar_options['std_blog_header_overlay_opacity']);

          echo '.single-post .page-header-bg-image-wrap .page-header-bg-image:after {
            display: block;
            content: " ";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background-color: '.$std_blog_header_overlay_color.';
            opacity: '.$std_blog_header_overlay_opacity.';
          }';
    }

    // Animated in effect.
    if( isset($nectar_options['header-animate-in-effect']) ) {

      $animate_in_effect = $nectar_options['header-animate-in-effect'];

      if( 'slide-down' === $animate_in_effect ) {
        echo '#page-header-wrap[data-animate-in-effect="slide-down"],
        #page-header-wrap[data-animate-in-effect="slide-down"] #page-header-bg{
          transition:height 0.85s cubic-bezier(0.725,0,0,0.995);
          -webkit-transition:height 0.85s cubic-bezier(0.725,0,0,0.995);
        }
        body[data-ajax-transitions="true"] #page-header-wrap[data-animate-in-effect="slide-down"] {
          transition:height 0.85s 0.2s cubic-bezier(0.725,0,0,0.995);
          -webkit-transition:height 0.85s 0.2s cubic-bezier(0.725,0,0,0.995);
        }';
      }
      else if( 'zoom-out' === $animate_in_effect ) {

        echo '
        @media only screen and (min-width: 1000px) {
          #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"] .page-header-bg-image-wrap,
          #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"] .nectar-video-wrap,
          .top-level .nectar-slider-wrap[data-animate-in-effect="zoom-out"]:not([data-bg-animation="ken_burns"]) .slide-bg-wrap,
          .nectar-recent-posts-slider[data-animate-in-effect="zoom-out"] .nectar-recent-posts-slider-inner:not(.loaded) .nectar-recent-post-bg,
          body[data-aie="zoom-out"] .nectar-recent-posts-single_featured .nectar-recent-post-bg,
          body[data-aie="zoom-out"] .parallax_section .nectar-recent-post-slide .nectar-recent-post-bg {
            -webkit-transform:scale(1.11) translateZ(0);
            transform:scale(1.11) translateZ(0);
            -webkit-transition:0.95s 0s cubic-bezier(0.3,0.58,0.42,0.9);
            transition:0.95s 0s cubic-bezier(0.3,0.58,0.42,0.9)
          }
        }

        @media only screen and (min-width: 1000px) {
          body[data-ajax-transitions="true"][data-apte="center_mask_reveal"]:not(.using-mobile-browser) #page-header-bg[data-animate-in-effect="zoom-out"] .nectar-video-wrap video{
            opacity:1
          }
        }

        body[data-apte*="horizontal_swipe"] #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"] .page-header-bg-image-wrap,
        body[data-apte*="horizontal_swipe"] #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"] .nectar-video-wrap,
        body[data-apte*="horizontal_swipe"][data-aie="zoom-out"] .nectar-recent-posts-single_featured .nectar-recent-post-bg {
          -webkit-transition:1.25s 0s cubic-bezier(0.3,0.58,0.42,0.9);
          transition:1.25s 0s cubic-bezier(0.3,0.58,0.42,0.9)
        }
        body[data-ajax-transitions="true"] #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"] .nectar-video-wrap{
          -webkit-transition:transform 0.95s 0s cubic-bezier(0.3,0.58,0.42,0.9);
          transition:transform 0.95s 0s cubic-bezier(0.3,0.58,0.42,0.9)
        }
        #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"].loaded .page-header-bg-image-wrap,
        #page-header-wrap #page-header-bg[data-animate-in-effect="zoom-out"].loaded .nectar-video-wrap,
        .top-level .nectar-slider-wrap[data-animate-in-effect="zoom-out"]:not([data-bg-animation="ken_burns"]).loaded .slide-bg-wrap,
        .nectar-recent-posts-slider[data-animate-in-effect="zoom-out"].loaded .nectar-recent-post-bg,
        .js_active body[data-aie="zoom-out"] .nectar-recent-posts-single_featured .nectar-recent-post-bg {
          -webkit-transform:scale(1) translateZ(0);
          transform:scale(1) translateZ(0)
        }
        @media only screen and (min-width: 1000px) {
          body[data-aie="zoom-out"] .first-section .row-bg-wrap .inner-wrap,
          body[data-aie="zoom-out"] .top-level .row-bg-wrap .inner-wrap,
          body[data-aie="zoom-out"] .first-section .project-slide .bg-inner-wrap{
            -webkit-transform:scale(1.11) translateZ(0);
            transform:scale(1.11) translateZ(0)
          }
        }
        body[data-aie="zoom-out"] .first-section.loaded .row-bg-wrap .inner-wrap,
        body[data-aie="zoom-out"] .top-level.loaded .row-bg-wrap .inner-wrap,
        body[data-aie="zoom-out"] .first-section.loaded .project-slide .bg-inner-wrap{
          -webkit-transform:scale(1) translateZ(0);
          transform:scale(1) translateZ(0);
          -webkit-transition:transform 0.95s 0s cubic-bezier(0.3,0.58,0.42,0.9);
          transition:transform 0.95s 0s cubic-bezier(0.3,0.58,0.42,0.9)
        }
        body[data-aie="zoom-out"] #nectar_fullscreen_rows[data-row-bg-animation="ken_burns"] .first-section .row-bg-wrap .inner-wrap {
          -webkit-transform:none;
          transform:none;
        }
        ';

      }
      else if( 'fade-in' === $animate_in_effect ) {
        echo '
        #page-header-bg[data-animate-in-effect="fade-in"] .scroll-down-wrap {
        	opacity: 0;
        	transition: opacity 1s ease 0.6s;
        }

        #page-header-bg[data-animate-in-effect="fade-in"].loaded .scroll-down-wrap {
        	opacity: 1;
        	transition: opacity 1s ease 0.4s;
        }

        #page-header-bg[data-animate-in-effect="fade-in"] .page-header-bg-image-wrap,
        #page-header-wrap #page-header-bg[data-animate-in-effect="fade-in"] .nectar-video-wrap {
        	opacity: 0;
        	-webkit-animation: pageHeaderFadeIn 1.5s ease forwards;
        	animation: pageHeaderFadeIn 1.5s ease forwards;
        	animation-delay: 0.5s;
        }

        #page-header-bg[data-animate-in-effect="fade-in"] .container {
        	opacity: 0;
        	-webkit-animation: pageHeaderFadeInText 1.5s ease forwards;
        	animation: pageHeaderFadeInText 1.5s ease forwards;
        	animation-delay: 0.1s;
        }


        @keyframes pageHeaderFadeIn {
          0%{
            opacity: 0;
          }
          100%{
            opacity: 1;
          }
        }

        @keyframes pageHeaderFadeInText {
          0%{
        		-webkit-transform:translateY(40px);
        		transform:translateY(40px);
            opacity: 0;
          }
          100%{
            -webkit-transform:translateY(0);
        		transform:translateY(0);
            opacity: 1;
          }
        }';
      }

    }


    /*-------------------------------------------------------------------------*/
    /* 10. Button Roundness
    /*-------------------------------------------------------------------------*/
    if( isset($nectar_options['button-styling']) ) {

      if ( 'default' === $nectar_options['button-styling'] ) {
        echo ':root {
          --nectar-border-radius: 0px;
        }';
      }

      if( 'slightly_rounded' === $nectar_options['button-styling'] ||
          'slightly_rounded_shadow' === $nectar_options['button-styling'] ) {

          $button_roundness = ( isset($nectar_options['button-styling-roundness']) && !empty($nectar_options['button-styling-roundness']) ) ? intval( $nectar_options['button-styling-roundness'] ) : 4;

          $ascend_button_selector = '';

          if( 'ascend' === $theme_skin ) {
            $ascend_button_selector = '.ascend[data-button-style="slightly_rounded"] .container-wrap input[type="submit"],
            .ascend[data-button-style*="slightly_rounded"] .container-wrap button[type="submit"],
            body[data-button-style*="slightly_rounded"].ascend .container-wrap input[type="submit"],
            body[data-button-style*="slightly_rounded"].ascend .container-wrap button[type="submit"],
            body[data-button-style*="slightly_rounded"].ascend .nectar-button.see-through,
            body[data-button-style*="slightly_rounded"].ascend .nectar-button.see-through-2,
            body[data-button-style*="slightly_rounded"].ascend .nectar-button.see-through-3,';
          }

      echo ' ' . $ascend_button_selector . '
      .nectar-inherit-border-radius,
      body[data-button-style*="slightly_rounded"] .nectar-cta:not([data-style="material"]) .link_wrap,
      body[data-button-style*="slightly_rounded"] .nectar-button.see-through,
      body[data-button-style*="slightly_rounded"] .nectar-button.see-through-2,
      body[data-button-style*="slightly_rounded"] .nectar-button.see-through-3,
      body[data-button-style*="slightly_rounded"] .portfolio-filters-inline .container ul li a,
      body[data-button-style*="slightly_rounded"] .slide-out-widget-area-toggle[data-custom-color="true"] a:before,
      body[data-button-style*="slightly_rounded"] #infscr-loading,
      body[data-button-style*="slightly_rounded"] .flex-direction-nav a,
      body[data-button-style*="slightly_rounded"] #pagination span,
      body[data-button-style*="slightly_rounded"] #pagination a,
      body[data-button-style*="slightly_rounded"] #pagination .next.inactive,
      body[data-button-style*="slightly_rounded"] #pagination .prev.inactive,
      body[data-button-style*="slightly_rounded"].woocommerce nav.woocommerce-pagination ul li a,
      body[data-button-style*="slightly_rounded"].woocommerce .container-wrap nav.woocommerce-pagination ul li span,
      body[data-button-style*="slightly_rounded"] .container-wrap nav.woocommerce-pagination ul li span,
      body[data-button-style*="slightly_rounded"].woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
      body[data-button-style*="slightly_rounded"] input[type=submit],
      body[data-button-style*="slightly_rounded"] button[type=submit],
      body[data-button-style*="slightly_rounded"] input[type="button"],
      body[data-button-style*="slightly_rounded"] button,
      body[data-button-style*="slightly_rounded"] .nectar-button,
      body[data-button-style*="slightly_rounded"] .swiper-slide .button a,
      body[data-button-style*="slightly_rounded"] #top nav > ul > li[class*="button_solid_color"] > a:before,
      body[data-button-style*="slightly_rounded"] #top nav > ul > li[class*="button_bordered"] > a:before,
      body[data-button-style*="slightly_rounded"] #header-outer .widget_shopping_cart a.button,
      body[data-button-style*="slightly_rounded"] .comment-list .reply a,
      body[data-button-style*="slightly_rounded"].material #page-header-bg.fullscreen-header .inner-wrap >a,
      body[data-button-style*="slightly_rounded"] .sharing-default-minimal .nectar-social-inner >a,
      body[data-button-style*="slightly_rounded"] .sharing-default-minimal .nectar-love,
      body[data-button-style*="slightly_rounded"].single .heading-title[data-header-style="default_minimal"] .meta-category a,
      body[data-button-style*="slightly_rounded"] #page-header-bg[data-post-hs="default_minimal"] .inner-wrap > a,
      body[data-button-style*="slightly_rounded"] .masonry.classic_enhanced .posts-container article .meta-category a,
      body[data-button-style*="slightly_rounded"] .blog-recent[data-style*="classic_enhanced"] .meta-category a,
      body[data-button-style*="slightly_rounded"] .woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
      .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart a.button,
      li[class*="menu-item-btn-style"] > a:before,
      li[class*="menu-item-btn-style"] > a:after {
        border-radius: '.intval($button_roundness).'px!important;
        -webkit-border-radius: '.intval($button_roundness).'px!important;
        box-shadow: none;
        -webkit-transition: opacity .45s cubic-bezier(0.25, 1, 0.33, 1), transform .45s cubic-bezier(0.25, 1, 0.33, 1), border-color .45s cubic-bezier(0.25, 1, 0.33, 1), color .45s cubic-bezier(0.25, 1, 0.33, 1), background-color .45s cubic-bezier(0.25, 1, 0.33, 1), box-shadow .45s cubic-bezier(0.25, 1, 0.33, 1);
        transition: opacity .45s cubic-bezier(0.25, 1, 0.33, 1), transform .45s cubic-bezier(0.25, 1, 0.33, 1), border-color .45s cubic-bezier(0.25, 1, 0.33, 1), color .45s cubic-bezier(0.25, 1, 0.33, 1), background-color .45s cubic-bezier(0.25, 1, 0.33, 1), box-shadow .45s cubic-bezier(0.25, 1, 0.33, 1);
      }
      .nectar-shop-filters .nectar-shop-filter-trigger,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-selection--single,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select,
      body[data-button-style*="slightly_rounded"] .widget_layered_nav_filters ul li a,
      .nectar-menu-label:before,
     .nectar-ext-menu-item__button,
     .nectar-post-grid .meta-category .style-button {
        border-radius: '.intval($button_roundness).'px;
      }';


      if( 'material' === $theme_skin ) {
        echo '.material[data-button-style*="slightly_rounded"] .widget .tagcloud a:before,
        .material[data-button-style*="slightly_rounded"] #sidebar .widget .tagcloud a:before,
        .single[data-button-style*="slightly_rounded"] .post-area .content-inner > .post-tags a:before,
        .material[data-button-style*="slightly_rounded"] .widget .tagcloud a,
        .material[data-button-style*="slightly_rounded"] #sidebar .widget .tagcloud a,
        .single[data-button-style*="slightly_rounded"] .post-area .content-inner > .post-tags a,
        #slide-out-widget-area.fullscreen-inline-images .menuwrapper li.back >a {
          border-radius: '.intval($button_roundness).'px!important;
        }';
      }

      echo ':root {
        --nectar-border-radius: '.intval($button_roundness).'px;
      }';

    } // slightly rounded end.

    if( 'rounded' === $nectar_options['button-styling'] || 'rounded_shadow' === $nectar_options['button-styling'] ) {

      $ascend_button_selector = '';

      if( 'ascend' === $theme_skin ) {
        $ascend_button_selector = 'body[data-button-style^="rounded"].ascend .nectar-button.see-through,
        body[data-button-style^="rounded"].ascend .nectar-button.see-through-2,
        body[data-button-style^="rounded"].ascend .nectar-button.see-through-3,
        body[data-button-style^="rounded"].ascend .container-wrap input[type="submit"],
        body[data-button-style^="rounded"].ascend .container-wrap button[type="submit"],';
      }

      echo ' ' . $ascend_button_selector . '
      .nectar-inherit-border-radius,
      body[data-button-style^="rounded"] .nectar-cta:not([data-style="material"]) .link_wrap,
      body[data-button-style^="rounded"] .nectar-button.see-through,
      body[data-button-style^="rounded"] .nectar-button.see-through-2,
      body[data-button-style^="rounded"] .nectar-button.see-through-3,
      body[data-button-style^="rounded"] .portfolio-filters-inline .container ul li a,
      body[data-button-style^="rounded"] .slide-out-widget-area-toggle[data-custom-color="true"] a:before,
      body[data-button-style^="rounded"] #to-top,
      body[data-button-style^="rounded"] .flex-direction-nav a,
      body[data-button-style^="rounded"] #pagination span,
      body[data-button-style^="rounded"] #pagination a,
      body[data-button-style^="rounded"] #pagination .next.inactive,
      body[data-button-style^="rounded"] #pagination .prev.inactive,
      body[data-button-style^="rounded"].woocommerce nav.woocommerce-pagination ul li a,
      body[data-button-style^="rounded"].woocommerce .container-wrap nav.woocommerce-pagination ul li span,
      body[data-button-style^="rounded"] .container-wrap nav.woocommerce-pagination ul li span,
      body[data-button-style^="rounded"].woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
      body[data-button-style^="rounded"] #infscr-loading,
      body[data-button-style^="rounded"] input[type=submit],
      body[data-button-style^="rounded"] button[type=submit],
      body[data-button-style^="rounded"] input[type="button"],
      body[data-button-style^="rounded"] button,
      body[data-button-style^="rounded"] .nectar-button,
      body[data-button-style^="rounded"] .swiper-slide .button a,
      body[data-button-style^="rounded"] #top nav > ul > li[class*="button_solid_color"] > a:before,
      body[data-button-style^="rounded"] #top nav > ul > li[class*="button_bordered"] > a:before,
      body[data-button-style^="rounded"] .woocommerce.add_to_cart_inline a.button.add_to_cart_button,
      body[data-button-style^="rounded"] #header-outer .widget_shopping_cart a.button,
      .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart a.button,
      li[class*="menu-item-btn-style"] > a:before,
      li[class*="menu-item-btn-style"] > a:after {
        border-radius: 200px!important;
        -webkit-border-radius: 200px!important;
        box-shadow: none;
        -ms-transition: opacity .45s cubic-bezier(0.25, 1, 0.33, 1), transform .45s cubic-bezier(0.25, 1, 0.33, 1), border-color .45s cubic-bezier(0.25, 1, 0.33, 1), color .45s cubic-bezier(0.25, 1, 0.33, 1), background-color .45s cubic-bezier(0.25, 1, 0.33, 1), box-shadow .45s cubic-bezier(0.25, 1, 0.33, 1);
        -webkit-transition: opacity .45s cubic-bezier(0.25, 1, 0.33, 1), transform .45s cubic-bezier(0.25, 1, 0.33, 1), border-color .45s cubic-bezier(0.25, 1, 0.33, 1), color .45s cubic-bezier(0.25, 1, 0.33, 1), background-color .45s cubic-bezier(0.25, 1, 0.33, 1), box-shadow .45s cubic-bezier(0.25, 1, 0.33, 1);
        transition: opacity .45s cubic-bezier(0.25, 1, 0.33, 1), transform .45s cubic-bezier(0.25, 1, 0.33, 1), border-color .45s cubic-bezier(0.25, 1, 0.33, 1), color .45s cubic-bezier(0.25, 1, 0.33, 1), background-color .45s cubic-bezier(0.25, 1, 0.33, 1), box-shadow .45s cubic-bezier(0.25, 1, 0.33, 1);
      }

      .nectar-shop-filters .nectar-shop-filter-trigger,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-selection--single,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select,
      .nectar-menu-label:before,
      #slide-out-widget-area.fullscreen-inline-images .menuwrapper li.back >a,
      .nectar-ext-menu-item__button,
      .nectar-post-grid .meta-category .style-button {
        border-radius: 200px;
      }';

      echo ':root {
        --nectar-border-radius: 200px;
      }';

    } // rounded end.
    


    if( 'rounded_shadow' === $nectar_options['button-styling'] ) {
      echo 'body[data-button-style*="rounded_shadow"] .wp-block-button > .wp-block-button__link:hover,
      body[data-button-style*="rounded_shadow"] .nectar-button:hover,
      body[data-button-style*="rounded_shadow"] .nectar-button:focus,
      body[data-button-style*="rounded_shadow"].ascend .nectar-button.see-through:hover,
      body[data-button-style*="rounded_shadow"] input[type="submit"]:hover,
      body[data-button-style*="rounded_shadow"] input[type="submit"]:focus,
      body[data-button-style*="rounded_shadow"].woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
      body[data-button-style*="rounded_shadow"] .swiper-slide .button a:hover,
      body[data-button-style="rounded_shadow"].ascend .nectar-button.see-through-2:hover,
      body[data-button-style*="rounded_shadow"].ascend .nectar-button:hover,
      body[data-button-style*="rounded_shadow"] .sharing-default-minimal .nectar-love:hover,
      body[data-button-style="rounded_shadow"] .sharing-default-minimal .nectar-social-inner > a:hover,
      body[data-button-style*="rounded_shadow"] .woocommerce.add_to_cart_inline a.button.add_to_cart_button:hover,
      body[data-button-style*="rounded_shadow"] .container-wrap input[type="submit"]:hover,
      body[data-button-style="rounded_shadow"] .container-wrap button[type="submit"]:hover,
      body[data-button-style="rounded_shadow"] .woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover {
        box-shadow: 0 20px 38px rgba(0, 0, 0, 0.16);
        -ms-transform: translateY(-3px);
        transform: translateY(-3px);
        -webkit-transform: translateY(-3px);
      }';
    } // rounded shadow hover end.

    if( 'slightly_rounded_shadow' === $nectar_options['button-styling'] ) {
      echo 'body[data-button-style="slightly_rounded_shadow"] .wp-block-button > .wp-block-button__link:hover,
      body[data-button-style="slightly_rounded_shadow"] .nectar-button:hover,
      body[data-button-style="slightly_rounded_shadow"] .nectar-button:focus,
      body[data-button-style="slightly_rounded_shadow"].ascend .nectar-button.see-through:hover,
      body[data-button-style="slightly_rounded_shadow"] input[type="submit"]:hover,
      body[data-button-style="slightly_rounded_shadow"] input[type="submit"]:focus,
      body[data-button-style*="slightly_rounded"].single .heading-title[data-header-style="default_minimal"] .meta-category a:hover,
      body[data-button-style*="slightly_rounded"] #page-header-bg[data-post-hs="default_minimal"] .inner-wrap > a:hover,
      body[data-button-style="slightly_rounded_shadow"].woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
      body[data-button-style="slightly_rounded_shadow"] .swiper-slide .button a:hover,
      body[data-button-style="slightly_rounded_shadow"].ascend .nectar-button.see-through-2:hover,
      body[data-button-style="slightly_rounded_shadow"].ascend .nectar-button:hover,
      body[data-button-style="slightly_rounded_shadow"].woocommerce-page .woocommerce p.return-to-shop a.wc-backward:hover,
      body[data-button-style="slightly_rounded_shadow"] .sharing-default-minimal .nectar-love:hover,
      body[data-button-style="slightly_rounded_shadow"] .sharing-default-minimal .nectar-social-inner > a:hover,
      body[data-button-style="slightly_rounded_shadow"] .woocommerce.add_to_cart_inline a.button.add_to_cart_button:hover,
      body[data-button-style="slightly_rounded_shadow"] .container-wrap input[type="submit"]:hover,
      body[data-button-style="slightly_rounded_shadow"] .container-wrap button[type="submit"]:hover,
      body[data-button-style="slightly_rounded_shadow"] .woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover {
        box-shadow: 0 20px 38px rgba(0, 0, 0, 0.16)!important;
        -ms-transform: translateY(-3px);
        transform: translateY(-3px);
        -webkit-transform: translateY(-3px);
      }';
    } // slightly rounded shadow hover end.

    if( 'default' === $nectar_options['button-styling'] ) { 
      echo '.nectar-cta .link_wrap {
        transition: border-color .45s cubic-bezier(0.25, 1, 0.33, 1), color .45s cubic-bezier(0.25, 1, 0.33, 1), background-color .45s cubic-bezier(0.25, 1, 0.33, 1);
      }';
    }

  }

  /*-------------------------------------------------------------------------*/
  /* 11. Call to action
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['cta-text']) && !empty($nectar_options['cta-text']) ) {
    echo '#call-to-action{
      font-weight:300;
      position:relative;
      z-index:10;
      background-color:#eeedec;
      text-align:center;
      vertical-align:bottom;
      font-size:22px
    }
    #boxed #call-to-action .container,
    #call-to-action .container,
    #footer-outer[data-full-width="1"] #call-to-action .container{
      padding:42px 0 36px 0
    }
    #call-to-action .container span{
      display:inline-block
    }
    #call-to-action .container a{
      background:none repeat scroll 0 0 #000;
      position:relative;
      top:-3px;
      color:#FFF;
      margin-bottom:0;
      font-size:12px;
      box-shadow:0 -3px rgba(0,0,0,0.1) inset;
      -webkit-box-shadow:0 -3px rgba(0,0,0,0.1) inset;
      text-transform:uppercase;
      font-weight:700;
      letter-spacing:2px;
      margin-left:20px;
      line-height:24px;
      display:inline-block;
      border-radius:5px 5px 5px 5px;
      padding:16px 21px
    }
    #call-to-action .triangle{
      border-left:10px solid transparent;
      border-right:10px solid transparent;
      border-top:10px solid #f8f8f8;
      height:10px;
      width:10px;
      left:440px;
      margin:-42px auto 33px auto
    }
    #call-to-action a{
      color:#45484a;
      opacity:1;
      transition:opacity 0.3s linear;
      -webkit-transition:opacity 0.3s linear;
    }
    #call-to-action a:hover{
      opacity:0.75
    }
    #call-to-action span{
      color:#4b4f52;
      font-weight:600
    }';
  }


  /*-------------------------------------------------------------------------*/
  /* 12. Body Border
  /*-------------------------------------------------------------------------*/

  if( isset($nectar_options['body-border']) && !empty($nectar_options['body-border']) && '1' === $nectar_options['body-border'] ) {
    echo '
    .body-border-bottom{
      height:20px;
      width:100%;
      bottom:0;
      left:0;
      position:fixed;
      z-index:10000;
      background-color:#fff;
    }

    .body-border-top{
      height:20px;
      width:100%;
      top:0;
      left:0;
      z-index:10000;
      position:fixed;
      background-color:#fff;
    }

    .admin-bar .body-border-top{
      top:32px
    }

    .body-border-right{
      height:100%;
      width:20px;
      top:0;
      right:0;
      z-index:10000;
      position:fixed;
      background-color:#fff;
    }

    .body-border-left{
      height:100%;
      width:20px;
      top:0;
      left:0;
      z-index:10000;
      position:fixed;
      background-color:#fff;
    }
    .pum-open [data-body-border="1"] #header-outer[data-transparent-header="true"][data-transparent-shadow-helper="true"].transparent:not(.dark-slide):before {
      opacity: 0;
    }
    @media only screen and (max-width: 999px) {
      .body-border-right,
      .body-border-left,
      .body-border-top,
      .body-border-bottom {
        display: none;
      }
    }
    ';
  }


  /*-------------------------------------------------------------------------*/
  /* 13. Mobile Animations
  /*-------------------------------------------------------------------------*/
  $mobile_animations = false;
  if( isset($nectar_options['column_animation_mobile']) &&
      !empty($nectar_options['column_animation_mobile']) &&
      'enable' === $nectar_options['column_animation_mobile']) {
    $mobile_animations = true;
  }

  if( false === $mobile_animations ) {
    echo '
    @media only screen and (min-width : 1px) and (max-width : 999px) {

      body:not([data-m-animate="1"]) .wpb_animate_when_almost_visible,
      body:not([data-m-animate="1"]) .wpb_animate_when_almost_visible.wpb_start_animation {
        opacity: 1;
        -webkit-animation: none;
        -o-animation: none;
        animation: none;
      }

      body:not([data-m-animate="1"]) .clients.no-carousel.fade-in-animation > div {
        opacity: 1;
      }
      
      .divider-border[data-animate="yes"],
      .divider-small-border[data-animate="yes"] {
        visibility: visible!important;
      }

      img.img-with-animation[data-animation="fade-in-from-left"],
      .col.has-animation[data-animation="fade-in-from-left"],
      .wpb_column.has-animation[data-animation="fade-in-from-left"],
      .nectar-fancy-box.has-animation[data-animation="fade-in-from-left"],
      img.img-with-animation[data-animation="fade-in-from-right"],
      .col.has-animation[data-animation="fade-in-from-right"],
      .wpb_column.has-animation[data-animation="fade-in-from-right"],
      .nectar-fancy-box.has-animation[data-animation="fade-in-from-right"],
      .divider-border[data-animate="yes"], .divider-small-border[data-animate="yes"],
      .col.has-animation[data-animation="fade-in-from-bottom"],
      .wpb_column.has-animation[data-animation="fade-in-from-bottom"],
      .wpb_column.has-animation[data-animation="slight-fade-in-from-bottom"],
      .nectar-fancy-box.has-animation[data-animation="fade-in-from-bottom"],
      img.img-with-animation[data-animation="grow-in"],
      .col.has-animation[data-animation="grow-in"],
      .wpb_column.has-animation[data-animation="grow-in"],
      .nectar-fancy-box.has-animation[data-animation="grow-in"],
      img.img-with-animation[data-animation="flip-in"],
      .col.has-animation[data-animation="flip-in"],
      .wpb_column.has-animation[data-animation="flip-in"],
      .nectar-fancy-box.has-animation[data-animation="flip-in"],
      img.img-with-animation[data-animation="flip-in-vertical"],
      .col.has-animation[data-animation="flip-in-vertical"],
      .wpb_column.has-animation[data-animation="flip-in-vertical"],
      .wpb_column.has-animation[data-animation="slight-twist"],
      .nectar-fancy-box.has-animation[data-animation="flip-in-vertical"],
      .img-with-aniamtion-wrap[data-animation="flip-in-vertical"] .hover-wrap,
      .img-with-aniamtion-wrap[data-animation="flip-in"] .hover-wrap,
      .img-with-aniamtion-wrap[data-animation="fade-in-from-bottom"] .hover-wrap,
      .img-with-aniamtion-wrap[data-animation="fade-in-from-right"] .hover-wrap,
      .img-with-aniamtion-wrap[data-animation="fade-in-from-left"] .hover-wrap,
      .img-with-aniamtion-wrap[data-animation="grow-in"] .hover-wrap,
      .nectar-split-heading .heading-line > div,
      .nectar-split-heading span > .inner,
      .nectar-split-heading[data-animation-type="twist-in"],
      .nectar_food_menu_item .item_description,
    	.nectar_food_menu_item .inner,
      .col.has-animation[data-animation="zoom-out"],
      .col.has-animation[data-animation="zoom-out-high"],
      .nectar_image_with_hotspots[data-animation="true"] .nectar_hotspot_wrap,
      .column-bg-overlay-wrap[data-bg-animation="zoom-out-reveal"],
      .column-image-bg-wrap[data-bg-animation="zoom-out-reveal"],
      .column-image-bg-wrap[data-bg-animation="zoom-out-reveal"] .inner-wrap,
      .column-image-bg-wrap[data-bg-animation*="reveal-from-"],
      .column-image-bg-wrap[data-bg-animation*="reveal-from-"] .inner-wrap,
      .column-image-bg-wrap[data-bg-animation*="reveal-from-"] .inner-wrap .column-image-bg,
      .column-bg-overlay-wrap[data-bg-animation*="reveal-from-"],
      .column-bg-overlay-wrap[data-bg-animation*="reveal-from-"] > div,
      .img-with-aniamtion-wrap[data-animation*="reveal-from-"] img.img-with-animation,
      .img-with-aniamtion-wrap[data-animation*="reveal-from-"] .inner,
      .img-with-aniamtion-wrap[data-animation*="reveal-from-"],
      .nectar-rotating-words-title.element_stagger_words .text-wrap > span,
      .nectar-waypoint-el {
        transform: none!important;
        -webkit-transform: none!important;
      }


      .clients.fade-in-animation > div,
      img.img-with-animation,
      .img-with-aniamtion-wrap .hover-wrap,
      .col.has-animation,
      .wpb_column.has-animation,
      .nectar-fancy-box.has-animation,
      img.img-with-animation[data-animation="flip-in"],
      .col.has-animation[data-animation="flip-in"],
      .wpb_column.has-animation[data-animation="flip-in"],
      .nectar-fancy-box.has-animation[data-animation="flip-in"],
      img.img-with-animation[data-animation="flip-in-vertical"],
      .col.has-animation[data-animation="flip-in-vertical"],
      .wpb_column.has-animation[data-animation="flip-in-vertical"],
      .nectar-fancy-box.has-animation[data-animation="flip-in-vertical"],
      .nectar_food_menu_item .item_description,
    	.nectar_food_menu_item .inner,
      .nectar_image_with_hotspots[data-animation="true"] .nectar_hotspot_wrap,
      .nectar-fancy-ul[data-animation="true"] ul li,
      .nectar-split-heading[data-animation-type="line-reveal-by-space"]:not(.markup-generated),
      .nectar-split-heading[data-animation-type="twist-in"],
      .nectar-split-heading span > .inner,
      .column-bg-overlay-wrap[data-bg-animation="zoom-out-reveal"],
      .column-image-bg-wrap[data-bg-animation="zoom-out-reveal"],
      .column-image-bg-wrap[data-bg-animation*="reveal-from-"] .inner-wrap,
      .column-bg-overlay-wrap[data-bg-animation*="reveal-from-"],
      .column-bg-overlay-wrap[data-bg-animation*="reveal-from-"] > div,
      .img-with-aniamtion-wrap[data-animation*="reveal-from-"] .inner,
      .nectar-waypoint-el {
        opacity: 1!important;
      }

      body:not([data-m-animate="1"]) .span_12.flip-in-vertical-wrap {
        -webkit-perspective: none;
        perspective: none;
      }

      .nectar_cascading_images .cascading-image .inner-wrap,
      .nectar-icon-list[data-animate="true"] .content,
      .nectar-icon-list[data-animate="true"] .nectar-icon-list-item .list-icon-holder,
      .nectar-icon-list[data-animate="true"]:after,
      .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner .wrap,
      .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner .wrap *,
      .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner:after,
      .nectar-animated-title[data-style="hinge-drop"] .nectar-animated-title-inner,
      .nectar-woo-flickity[data-animation*="fade-in"] ul.products .flickity-cell > .product {
        transform: none!important;
        -webkit-transform: none!important;
        opacity: 1!important;
        animation: none!important;
      }

      .child_column[class*="nectar-mask-reveal"] .vc_column-inner {
        clip-path: none!important;
      }
      

    }

    @media only screen and (min-device-width: 481px) and (max-device-width: 1025px) and (orientation:landscape) {

      .col.has-animation[data-animation="fade-in-from-left"],
      .wpb_column.has-animation[data-animation="fade-in-from-left"],
      .img-with-animation[data-animation="fade-in-from-right"],
      .img-with-animation[data-animation="fade-in-from-left"],
      .divider-border[data-animate="yes"],
      .divider-small-border[data-animate="yes"],
      .img-with-animation[data-animation="grow-in"],
      .col.has-animation[data-animation="grow-in"],
      .wpb_column.has-animation[data-animation="grow-in"],
      .img-with-animation[data-animation="flip-in"],
      .col.has-animation[data-animation="flip-in"],
      .wpb_column.has-animation[data-animation="flip-in"],
      .img-with-animation[data-animation="flip-in-vertical"],
      .col.has-animation[data-animation="flip-in-vertical"],
      .wpb_column.has-animation[data-animation="flip-in-vertical"],
      .wpb_column.has-animation[data-animation="slight-twist"],
      .col.has-animation[data-animation="fade-in-from-bottom"],
      .col.has-animation[data-animation="slight-fade-in-from-bottom"],
      .wpb_column.has-animation[data-animation="fade-in-from-bottom"] {
        transform: none!important;
        -webkit-transform: none!important;
      }

      .clients.fade-in-animation > div,
      .img-with-animation,
      .col.has-animation,
      .wpb_column.has-animation,
      .img-with-animation[data-animation="flip-in"],
      .col.has-animation[data-animation="flip-in"],
      .wpb_column.has-animation[data-animation="flip-in"],
      .img-with-animation[data-animation="flip-in-vertical"],
      .col.has-animation[data-animation="flip-in-vertical"],
      .wpb_column.has-animation[data-animation="flip-in-vertical"] {
        opacity: 1!important;
      }

      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-bottom"] .column-inner-wrap,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-top"] .column-inner-wrap,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-left"] .column-inner-wrap,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-right"] .column-inner-wrap,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-bottom"] .column-inner,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-top"] .column-inner,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-left"] .column-inner,
      body:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-right"] .column-inner {
        transform: none;
        -webkit-transform: none;
      }
      
      .divider-border[data-animate="yes"],
      .divider-small-border[data-animate="yes"] {
        visibility: visible;
      }

    }


    @media only screen and (max-device-width: 2600px) {

      body.using-mobile-browser .col.has-animation[data-animation="fade-in-from-left"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="fade-in-from-left"],
      body.using-mobile-browser .img-with-animation[data-animation="fade-in-from-right"],
      body.using-mobile-browser .img-with-animation[data-animation="fade-in-from-left"],
      body.using-mobile-browser .col.has-animation[data-animation="fade-in-from-bottom"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="fade-in-from-bottom"],
      body.using-mobile-browser .img-with-animation[data-animation="grow-in"],
      body.using-mobile-browser .col.has-animation[data-animation="grow-in"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="grow-in"],
      body.using-mobile-browser .divider-border[data-animate="yes"],
      body.using-mobile-browser .divider-small-border[data-animate="yes"],
      body.using-mobile-browser .img-with-aniamtion-wrap .hover-wrap,
      body.using-mobile-browser .img-with-animation[data-animation="flip-in"],
      body.using-mobile-browser .col.has-animation[data-animation="flip-in"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="flip-in"],
      body.using-mobile-browser .img-with-animation[data-animation="flip-in-vertical"],
      body.using-mobile-browser .col.has-animation[data-animation="flip-in-vertical"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="flip-in-vertical"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation*="reveal-from"] > .vc_column-inner,
      body.using-mobile-browser .nectar_image_with_hotspots[data-animation="true"] .nectar_hotspot_wrap,
      body.using-mobile-browser .nectar_cascading_images .cascading-image .inner-wrap,
      body.using-mobile-browser .nectar-split-heading[data-animation-type="twist-in"],
      body.using-mobile-browser .nectar-split-heading span > .inner,
      body.using-mobile-browser .nectar-icon-list[data-animate="true"] .content,
      body.using-mobile-browser .nectar-icon-list[data-animate="true"] .nectar-icon-list-item .list-icon-holder,
      body.using-mobile-browser .nectar-icon-list[data-animate="true"]:after,
    	body.using-mobile-browser .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner .wrap,
      body.using-mobile-browser .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner .wrap *,
      body.using-mobile-browser .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner:after,
      body.using-mobile-browser .nectar-animated-title[data-style="hinge-drop"] .nectar-animated-title-inner,
      body.using-mobile-browser .nectar-fancy-box.has-animation,
      body.using-mobile-browser .img-with-aniamtion-wrap[data-animation*="reveal-from-"] img.img-with-animation,
      body.using-mobile-browser .img-with-aniamtion-wrap[data-animation*="reveal-from-"] .inner,
      body.using-mobile-browser .img-with-aniamtion-wrap[data-animation*="reveal-from-"],
      body.using-mobile-browser [data-animation="zoom-out-reveal"] .nectar-link-underline, 
      body.using-mobile-browser [data-animation="zoom-out-reveal"] .item-main:before, 
      body.using-mobile-browser [data-animation="zoom-out-reveal"] .nectar-post-grid-item__meta-wrap,
      body.using-mobile-browser .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-category,
      body.using-mobile-browser .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-author,
      body.using-mobile-browser .nectar-post-grid:not(.nectar-flickity):not([data-animation="none"]) .nectar-post-grid-item,
      body.using-mobile-browser .nectar-post-grid.nectar-flickity:not([data-animation="none"]) .nectar-post-grid-item div.inner,
      body.using-mobile-browser .nectar-post-grid:not([data-animation="none"]) .nectar-post-grid-item .post-heading span,
      body.using-mobile-browser .nectar-post-grid:not([data-animation="none"]) .nectar-post-grid-item .meta-date,
      body.using-mobile-browser .nectar-woo-flickity[data-animation*="fade-in"] ul.products .flickity-cell > .product,
      body.using-mobile-browser .nectar-rotating-words-title.element_stagger_words .text-wrap > span,
      body.using-mobile-browser .nectar-waypoint-el {
        transform: none!important;
        -webkit-transform: none!important;
      }

      body.using-mobile-browser .clients.fade-in-animation > div,
      body.using-mobile-browser .img-with-animation,
      body.using-mobile-browser .img-with-aniamtion-wrap .hover-wrap,
      body.using-mobile-browser .col.has-animation,
      body.using-mobile-browser .wpb_column.has-animation,
      body.using-mobile-browser .nectar_image_with_hotspots[data-animation="true"] .nectar_hotspot_wrap,
      body.using-mobile-browser .img-with-animation[data-animation="flip-in"],
      body.using-mobile-browser .col.has-animation[data-animation="flip-in"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="flip-in"],
      body.using-mobile-browser .img-with-animation[data-animation="flip-in-vertical"],
      body.using-mobile-browser .col.has-animation[data-animation="flip-in-vertical"],
      body.using-mobile-browser .wpb_column.has-animation[data-animation="flip-in-vertical"],
      body.using-mobile-browser .nectar-fancy-box.has-animation,
      body.using-mobile-browser .nectar-split-heading[data-animation-type="twist-in"],
      body.using-mobile-browser .nectar-split-heading span > .inner,
      body.using-mobile-browser .img-with-aniamtion-wrap[data-animation*="reveal-from-"] .inner,
      body.using-mobile-browser [data-animation="zoom-out-reveal"] .nectar-link-underline, 
      body.using-mobile-browser [data-animation="zoom-out-reveal"] .item-main:before, 
      body.using-mobile-browser [data-animation="zoom-out-reveal"] .nectar-post-grid-item__meta-wrap,
      body.using-mobile-browser .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-category,
      body.using-mobile-browser .nectar-post-grid[data-animation="zoom-out-reveal"] .meta-author,
      body.using-mobile-browser .nectar-post-grid:not([data-animation="none"]) .nectar-post-grid-item,
      body.using-mobile-browser .nectar-post-grid:not([data-animation="none"]) .nectar-post-grid-item .post-heading span,
      body.using-mobile-browser .nectar-post-grid:not([data-animation="none"]) .nectar-post-grid-item  .meta-date,
      body.using-mobile-browser .nectar-woo-flickity[data-animation*="fade-in"] ul.products .flickity-cell > .product,
      body.using-mobile-browser .nectar-waypoint-el {
        opacity: 1!important;
      }


      body.using-mobile-browser .divider-border[data-animate="yes"],
      body.using-mobile-browser .divider-small-border[data-animate="yes"] {
        visibility: visible!important;
      }

      body.using-mobile-browser .nectar_cascading_images .cascading-image .inner-wrap,
      body.using-mobile-browser .nectar-icon-list[data-animate="true"] .content,
      body.using-mobile-browser .nectar-icon-list[data-animate="true"] .nectar-icon-list-item .list-icon-holder,
      body.using-mobile-browser .nectar-icon-list[data-animate="true"]:after,
    	body.using-mobile-browser .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner .wrap,
      body.using-mobile-browser .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner .wrap *,
      body.using-mobile-browser .nectar-animated-title[data-style="color-strip-reveal"] .nectar-animated-title-inner:after,
      body.using-mobile-browser .nectar-animated-title[data-style="hinge-drop"] .nectar-animated-title-inner {
        opacity: 1!important;
        animation: none!important;
      }


      body.using-mobile-browser:not([data-m-animate="1"]) .wpb_animate_when_almost_visible,
      body.using-mobile-browser:not([data-m-animate="1"]) .wpb_animate_when_almost_visible.wpb_start_animation {
        opacity: 1;
        -webkit-animation: none;
        -o-animation: none;
        animation: none;
      }

      body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-bottom"] .column-inner-wrap,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-top"] .column-inner-wrap,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-left"] .column-inner-wrap,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-right"] .column-inner-wrap,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-bottom"] .column-inner,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-top"] .column-inner,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-left"] .column-inner,
    	body.using-mobile-browser:not([data-m-animate="1"]) .wpb_column.has-animation[data-animation="reveal-from-right"] .column-inner {
        transform: none;
        -webkit-transform: none;
      }
      body.using-mobile-browser:not([data-m-animate="1"]) .nectar-split-heading .heading-line > div {
        transform: none;
        -webkit-transform: none;
      }

      body.using-mobile-browser:not([data-m-animate="1"]) .nectar-milestone:not(.animated-in) {
        opacity: 1;
      }
      
      body.using-mobile-browser:not([data-m-animate="1"]) .child_column[class*="nectar-mask-reveal"] .vc_column-inner {
        clip-path: none!important;
      }

    }';

  } // end mobile animations.

  $mobile_remove_parallax = false;
  if( isset($nectar_options['disable-mobile-parallax']) &&
      !empty($nectar_options['disable-mobile-parallax']) &&
      '1' === $nectar_options['disable-mobile-parallax']) {
    $mobile_remove_parallax = true;
  }

  if( true === $mobile_remove_parallax ) {
    echo '@media only screen and (max-device-width: 2600px) {

      body[data-remove-m-parallax="1"].using-mobile-browser .full-width-section.parallax_section,
      body[data-remove-m-parallax="1"].using-mobile-browser .full-width-content.parallax_section {
        background-attachment: scroll!important;
        background-position: center!important;
      }

      body[data-remove-m-parallax="1"].using-mobile-browser .wpb_row.parallax_section .row-bg,
      body[data-remove-m-parallax="1"].using-mobile-browser .full-width-section.parallax_section .row-bg,
      body[data-remove-m-parallax="1"].using-mobile-browser .nectar-recent-posts-single_featured .nectar-recent-post-slide .row-bg,
      body[data-remove-m-parallax="1"].using-mobile-browser #page-header-bg[data-parallax="1"] .page-header-bg-image {
        margin-top: 0!important;
        height: 100%!important;
        transform: none!important;
        -webkit-transform: none!important;
        background-attachment: scroll!important;
        background-position: 50%!important;
        opacity: 1;
      }

      body[data-remove-m-parallax="1"].using-mobile-browser .nectar-recent-posts-single_featured.parallax_section .nectar-recent-post-slide .nectar-recent-post-bg,
      body[data-remove-m-parallax="1"].using-mobile-browser #ajax-content-wrap .parallax-layer.column-image-bg {
        opacity: 1;
      }
      body[data-remove-m-parallax="1"].using-mobile-browser .top-level .nectar-recent-posts-single_featured .nectar-recent-post-slide .row-bg {
        height: 100%;
      }
    }';
  }



  /*-------------------------------------------------------------------------*/
  /* 14.1. Core Footer
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['enable-main-footer-area']) &&
    !empty($nectar_options['enable-main-footer-area']) &&
    '1' === $nectar_options['enable-main-footer-area'] ) {

      echo '
      #footer-outer .widget.widget_media_image img {
        margin-bottom: 0;
      }
      #footer-outer #footer-widgets .col .tagcloud a:hover,
      #footer-outer .nectar-button:hover {
        color:#fff!important
      }
      #footer-outer,
      #nectar_fullscreen_rows > #footer-outer.wpb_row .full-page-inner-wrap{
        color:#ccc;
        position:relative;
        z-index:10;
        background-color:#252525
      }
      #footer-outer .row{
        padding:55px 0;
        margin-bottom:0
      }
      #footer-outer #footer-widgets[data-has-widgets="false"] .row {
        padding: 0;
      }
      #footer-outer .widget h4 {
        color:#777;
        font-size:14px;
        font-weight:600;
        margin-bottom:20px
      }
      #footer-outer .widget h3,
      #footer-outer .widget h5,
      #footer-outer .widget h6 {
        color: inherit;
      }
      #footer-outer .widget{
        margin-bottom:30px
      }
      #footer-outer .widget.widget_categories ul ul,
      #footer-outer .widget.widget_pages ul ul,
      #footer-outer .widget.widget_nav_menu ul ul{
        margin:0!important;
        padding:0 0 0 20px
      }

      #footer-outer #footer-widgets .widget.widget_pages li,
      #footer-outer #footer-widgets .widget.widget_nav_menu li{
        border-bottom:0;
        padding:0!important
      }

      #footer-outer .widget.widget_pages li a,
      #footer-outer .widget.widget_nav_menu li a{
        padding:8px 0;
        display:block;
      }
      #footer-outer .widget_pages li a,
      #footer-outer .widget_nav_menu li a {
        border-bottom:1px solid #444
      }
      #ajax-content-wrap #footer-outer #footer-widgets .widget.widget_categories li a,
      #ajax-content-wrap #footer-outer #footer-widgets .widget.widget_archive li a {
        display: inline-block;
        border-bottom: 0;
      }

      #footer-outer .widget.widget_categories >ul >li:first-child >a,
      #footer-outer .widget.widget_pages >ul >li:first-child >a,
      #footer-outer .widget.widget_nav_menu >ul >li:first-child >a {
        padding-top:0
      }
      #footer-outer .span_3 .widget:last-child{
        margin-bottom:0
      }
      #footer-outer a{
        color:#ccc
      }
      #footer-outer a:hover{
        color:#000
      }

      #footer-outer .widget ul li{
        margin-bottom:7px
      }
      
      #footer-outer[data-full-width="1"] .container{
        width:100%;
        padding:0 28px;
        max-width:none
      }
      
      #footer-outer .col {
        z-index: 10;
        min-height: 1px;
      }

      #footer-outer .col .widget_recent_entries span,
      #footer-outer .col .recent_posts_extra_widget .post-widget-text span{
        display:block;
        line-height:17px;
        color:#999;
        font-size:11px;
        margin-bottom:6px
      }

      #footer-outer #footer-widgets .col ul li{
        padding:8px 0;
        list-style:none;
        margin-bottom:0;
        border-bottom:1px solid #444
      }
      #footer-outer #footer-widgets .col ul li:last-child{
        margin-bottom:0
      }
      #footer-outer .widget.widget_nav_menu li a,
      #footer-outer #footer-widgets .col ul ul li:last-child{
        border-bottom:0!important
      }

      #footer-outer #footer-widgets .col p{
        padding-bottom:20px
      }
      #footer-outer #footer-widgets .col p:last-child{
        padding-bottom:0
      }

      #footer-outer #footer-widgets .col .widget_calendar table th{
        text-align:center
      }
      #footer-outer #footer-widgets .col .widget_calendar table tbody td{
        border:0;
        color:#666;
        padding:8px;
        font-size:14px
      }
      #footer-outer #footer-widgets .col .widget_calendar table{
        border-collapse:collapse
      }
      #footer-outer #footer-widgets .col .widget_calendar table tbody tr td:first-child{
        border-left:0
      }
      #footer-outer #footer-widgets .col .widget_calendar table tbody tr:nth-child(2n+1){
        background-color:rgba(0,0,0,0.1)
      }
      #footer-outer #footer-widgets .col .widget_calendar table th{
        border-bottom:0;
        padding-bottom:10px;
        font-weight:700;
        padding: 10px;
        color:#666
      }
      #footer-outer #footer-widgets .col .widget_calendar table tfoot tr{
        margin-top:20px
      }

      #footer-outer .widget_search .search-form input[type=submit],
      #footer-outer .newsletter-widget form input[type=submit]{
        padding:10px 11px 11px 10px
      }
      #footer-outer #footer-widgets .col .tagcloud a{
        background-color: rgba(0,0,0,0.1);
        color:#A0A0A0;
        cursor:pointer;
        display:inline-block;
        float:left;
        margin:3px 3px 0 0;
        padding:5px 7px;
        position:relative;
        font-size:8pt;
        text-transform:capitalize;
        transition:all 0.2s linear;
        border-radius:2px;
        line-height:22px;
      }
      #footer-outer #footer-widgets .col .widget_tag_cloud:after{
        display:block;
        height:0;
        clear:both;
        content:"";
        visibility:hidden
      }

      #footer-outer #footer-widgets .col .tagcloud a:hover {
        background-color:#000;
      }

      #footer-outer #footer-widgets .col .widget_recent_comments ul li {
        background:none repeat scroll 0 0 rgba(0,0,0,0.15);
        border:medium none;
        display:block;
        margin-bottom:18px;
        padding:15px;
        position:relative
      }
      
      #footer-outer #footer-widgets .col .widget.widget_recent_comments ul li {
        padding:15px!important;
      }
      #footer-outer #footer-widgets .col .widget_recent_comments ul li:last-child {
        margin-bottom:0
      }
      #footer-outer #footer-widgets .col input[type=text],
      #footer-outer #footer-widgets .col input[type=email]{
        padding:10px;
        width:100%
      }


      body[data-form-style="minimal"] #footer-outer #footer-widgets .col input[type=text]{
        color:#fff
      }
      body:not([data-form-style="minimal"]) #footer-outer #footer-widgets .col input[type=text]:focus,
      body:not([data-form-style="minimal"]) #footer-outer #footer-widgets .col input[type=email]:focus{
        background-color:#fff
      }
      #footer-outer #footer-widgets .col input[type=submit]{
        background-color:#000;
        opacity:0.9;
        transition:opacity 0.2s linear 0s;
        -webkit-transition:opacity 0.2s linear 0s;
      }
      #footer-outer #footer-widgets .col input[type=submit]:hover{
        opacity:1
      }
      #footer-outer #footer-widgets .col .search-form form,
      #footer-outer #footer-widgets .col .search-form label,
      #footer-outer #footer-widgets .col .newsletter-widget form,
      #footer-outer #footer-widgets .col .search-form{
        line-height:12px
      }
      #footer-outer .recent_projects_widget img{
        background-color:#444;
        border-color:#4d4d4d
      }
      #footer-outer .recent_projects_widget a:hover img{
        border-color:#616161;
        background-color:#616161
      }

      #footer-outer #footer-widgets .col ul li:first-child >a,
      #footer-outer #footer-widgets .col ul li:first-child {
        padding-top:0!important
      }

      .original #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li:first-child >a {
        padding-top: 8px!important;
      }

      #footer-outer #footer-widgets .rsswidget img{
        margin-bottom:-2px;
        margin-right:2px
      }
      #footer-outer .recent_projects_widget img {
        margin-bottom:0;
      }';

      echo '@media only screen and (min-width : 691px) and (max-width : 999px) {
        #footer-outer .one-fourths.span_3,
        #footer-outer .one-fourths.vc_span3,
        #footer-outer .one-fourths.vc_col-sm-3:not([class*="vc_col-xs-"]) {
          width: 48%!important;
          margin-bottom: 2%;
          margin-right: 15px;
          margin-left: 0!important;
          padding: 15px;
          float: left;
        }
  
        #footer-widgets .container .col {
          margin-left: 15px;
          width: 48%;
        }
      
        #footer-widgets .one-fourths .span_3:nth-child(2n+1) {
          margin-left: 0;
        }

        #footer-widgets .container .col.span_6,
        #footer-widgets .container .col.span_4 {
          margin-left: 0;
          margin-right: 15px;
          padding: 15px;
          margin-bottom: 0;
        }

        #footer-widgets .container .col.span_4 {
          margin-bottom: 40px;
        }

        #footer-widgets .container .row > div:last-child,
        #footer-widgets .container .row > div.col_last {
          margin-right: 0;
        }

      }

      @media only screen and (max-width : 690px) {

        #ajax-content-wrap #footer-widgets .container .col:nth-child(3) {
          margin-bottom: 40px;
        }
      
        #footer-outer #flickr img, #sidebar #flickr img {
          width: 95px;
        }

      }

      @media only screen and (min-width : 1px) and (max-width : 999px) {

        #footer-widgets .container .col {
          margin-bottom: 40px;
        }
      
        #footer-widgets .container .col:nth-child(3),
        #footer-widgets .container .col:nth-child(4) {
          margin-bottom: 0;
        }
      
      }';


      if( isset($nectar_options['footer-link-hover']) && 'underline' === $nectar_options['footer-link-hover'] ) {
        echo '
        [data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
        [data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button) {
          background-repeat: no-repeat;
          background-size: 0% 2px;
          background-position: left bottom;
          opacity: 1;
          background-image: linear-gradient(to right, #000000 0%, #000000 100%);
          transition: background-size 0.55s cubic-bezier(.2,.75,.5,1), color 0.5s ease!important;
          text-decoration: none;
        }
        #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button) {
          transition: background-size 0.55s cubic-bezier(.2,.75,.5,1), color 0.5s ease;
        }
        
        #ajax-content-wrap #footer-outer[data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
        #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button) {
          display: inline;
          opacity: 1;
        }
        
        [data-link-hover="underline"] #footer-widgets ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button):hover {
          background-size: 100% 2px;
          opacity: 1;
        }
        #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button):hover {
          opacity: 1; 
        }';
      }

    }


    $disable_footer_copyright = 'false';

    if ( isset( $nectar_options['disable-copyright-footer-area'] ) &&
        !empty( $nectar_options['disable-copyright-footer-area'] ) &&
        $nectar_options['disable-copyright-footer-area'] === '1' ) {
            $disable_footer_copyright = 'true';
    }

    if( 'false' == $disable_footer_copyright ) {

      /* Copyright */
      echo '#footer-outer #copyright{
        padding:20px 0;
        font-size:12px;
        background-color:#1c1c1c;
        color:#777
      }
      #footer-outer #copyright li{
        float:left;
        margin-left:20px
      }

      #footer-outer #copyright .container div:last-child{
        margin-bottom:0
      }
      #footer-outer #copyright li a {
        display:block;
        line-height:22px;
        height:24px;
        position:relative;
        transition:all 0.2s linear;
        -webkit-transition:all 0.2s linear;
        background-position:center top
      }
      #footer-outer #copyright li a i {
        color:#777;
        transition:all 0.2s linear;
        top: 0;
      }

      
      #footer-outer .fa-vine{
        font-size:16px
      }
      #footer-outer #copyright li a i:hover{
        color:#000
      }
      #footer-outer #copyright li .vimeo,
      #footer-outer #copyright li .behance {
        background-color:#666
      }
      #footer-outer #copyright li .vimeo:hover,
      #footer-outer #copyright li .behance:hover {
        background-color:#000
      }

      #footer-outer #copyright p{
        line-height:22px;
        margin-top:3px
      }
      #footer-outer #copyright .col ul{
        float:right
      }
      #footer-outer #copyright li .facebook{
        width:12px
      }
      #footer-outer #copyright li .twitter{
        width:20px
      }
      #footer-outer #copyright li .dribbble{
        width:24px
      }
      #footer-outer #copyright li .google-plus{
        width:20px
      }
      #footer-outer #copyright li .pinterest{
        width:17px
      }
      #footer-outer #copyright li .rss{
        width:18px
      }
      #footer-outer #copyright li .vimeo{
        width:20px;
        text-indent:-9999px
      }
      #footer-outer #copyright li .tumblr{
        width:21px
      }
      #footer-outer #copyright li .youtube{
        width:21px
      }
      #footer-outer #copyright li .linkedin{
        width:19px
      }
      #footer-outer #copyright li .behance{
        width:27px;
        text-indent:-9999px
      }
      #footer-outer #copyright li .instagram{
        width:20px
      }
      #footer-outer #copyright #social .icon-soundcloud{
        font-size:26px
      }

      body #footer-outer i{
        font-size:20px;
        width:auto;
        background-color:transparent
      }
      #footer-outer #copyright i.icon-be{
        font-size:24px
      }
      @media only screen and (min-width: 1000px) {
        #footer-outer[data-full-width="1"]:not([data-cols="1"]) #copyright:not([data-layout="centered"]) .col ul {
          padding-right: 35px;
        }
      }
      #footer-outer[data-cols="1"] #copyright {
        padding: 45px 0;
      }


      #footer-outer #copyright .widget_products img {
        display: none;
      }

      #footer-outer #copyright .widget .nectar_widget[class*="nectar_blog_posts_"][data-style="featured-image-left"] > li {
        margin: 20px 0;
      }';

      echo '@media only screen and (min-width : 691px) and (max-width : 999px) {
  
        #footer-outer #copyright .col {
          width: 49%;
          margin-bottom: 0;
        }
      }

      @media only screen and (max-width : 690px) {
        body #footer-outer #copyright .col ul {
          float: left;
        }

        body #footer-outer #copyright .col ul li:first-child {
          margin-left: 0;
        }
     
        #footer-outer #social li {
          margin-right: 10px;
          margin-left: 0;
        }
      }';

    }


      



  /*-------------------------------------------------------------------------*/
  /* 14.2. Reveal Effect
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['footer-reveal']) &&
      !empty($nectar_options['footer-reveal']) &&
      '1' === $nectar_options['footer-reveal'] ) {
        echo '
        body[data-footer-reveal="1"] #footer-outer {
          position:fixed;
          bottom:0;
          width:100%;
          z-index:1;
        }
        body[data-footer-reveal="1"]:not(.nectar_using_pfsr) .container-wrap{
          margin-bottom:280px
        }
        body[data-footer-reveal="1"][data-footer-reveal-shadow="small"] .container-wrap{
          box-shadow:0 5px 8px -3px rgba(0,0,0,0.2);
        }
        body[data-footer-reveal="1"][data-footer-reveal-shadow="large"] .container-wrap{
          box-shadow:0 27px 25px -2px rgba(0,0,0,0.3);
        }
        body[data-footer-reveal="1"][data-footer-reveal-shadow="large_2"] .container-wrap{
          box-shadow: 0 70px 110px -30px rgba(0,0,0,1);
        }
        body[data-footer-reveal="1"][data-footer-reveal-shadow="large_2"] #footer-outer .row{
          padding:80px 0
        }
        body[data-footer-reveal="1"][data-footer-reveal-shadow] .container-wrap.no-shadow{
          box-shadow:none
        }
        body[data-footer-reveal="1"] #call-to-action .triangle{
          display:none
        }

        @media only screen and (min-width : 1px) and (max-width : 999px) {

          body[data-footer-reveal="1"] #footer-outer {
            position: relative;
          }
          body[data-footer-reveal="1"] #ajax-content-wrap,
          body[data-footer-reveal="1"] #ajax-content-wrap > .blurred-wrap {
            overflow: visible;
          }

          body[data-footer-reveal="1"][data-footer-reveal-shadow="large"] .container-wrap,
          body[data-footer-reveal="1"][data-footer-reveal-shadow="large_2"] .container-wrap {
            box-shadow: none;
            -webkit-box-shadow: none;
          }

          body[data-footer-reveal="1"] .container-wrap,
          body:not(.single-post) #page-header-bg:not(.fullscreen-header) .span_6 {
            margin-bottom: 0!important;
          }
        }

        ';
  }


  /*-------------------------------------------------------------------------*/
  /* 14.3. Footer Layouts
  /*-------------------------------------------------------------------------*/

    $footer_columns = ( isset($nectar_options['footer_columns']) && !empty( $nectar_options['footer_columns'] ) ) ? $nectar_options['footer_columns'] : '4';

    if( '5' === $footer_columns ) {
      echo '
      @media only screen and (min-width:1000px) {
        #footer-widgets[data-cols="5"] .container .row >div{
          width:19.5%
        }
        #footer-widgets[data-cols="5"] .container .row >div:first-child{
          width:35%
        }
      }';
    } else if( '1' == $footer_columns ) {

      echo '#footer-outer[data-cols="1"] #copyright .social li a {
        height: 50px;
      }
      #footer-outer[data-cols="1"] .col {
        text-align: center;
      }
      #footer-outer[data-cols="1"] #footer-widgets .container .col {
        width: 100%;
      }
      #footer-outer[data-cols="1"] #footer-widgets .span_12 > div:last-child {
        margin-bottom: 0;
      }
      #footer-outer[data-cols="1"] #copyright .col,
      #footer-outer[data-cols="1"] #copyright .col ul{
        width: 100%;
        float: none;
      }
      #footer-outer[data-cols="1"] #copyright #social li a {
        display: block;
        height: 50px;
        width: 50px;
      }
      #footer-outer[data-cols="1"] #copyright li a i {
        -webkit-transition: all .45s cubic-bezier(0.25, 1, 0.33, 1);
        transition: all .45s cubic-bezier(0.25, 1, 0.33, 1);
        font-size: 20px;
        height: 50px;
        width: 50px;
        line-height: 48px;
      }
      #footer-outer[data-cols="1"]:not([data-custom-color="true"]) #copyright li a:hover i {
        border-color: #fff;
        color: #fff;
      }
      #footer-outer[data-cols="1"] #copyright .col ul li {
        margin-bottom: 25px;
      }
      .ascend #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li:first-child,
      .material #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li:first-child {
        padding-top: 4px!important;
      }
      #footer-outer[data-cols="1"] #copyright li,
      #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li {
        float: none;
        display: inline-block;
        margin: 0 10px;
        width: auto;
      }
      #footer-outer[data-cols="1"] #copyright .widget {
        margin-bottom: 0;
      }
      #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li,
      #footer-outer[data-cols="1"] #copyright .widget_nav_menu li,
      #footer-outer[data-cols="1"] #copyright .widget_pages li {
        vertical-align: top;
        text-align: left;
        margin: 0 15px;
      }
      #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li ul,
      #footer-outer[data-cols="1"] #copyright .widget_nav_menu li ul,
      #footer-outer[data-cols="1"] #copyright .widget_pages li ul {
        padding-left: 0;
        margin-left: 0;
      }
      #footer-outer[data-cols="1"] #footer-widgets .widget.widget_nav_menu li ul li,
      #footer-outer[data-cols="1"] #copyright .widget_nav_menu li ul li,
      #footer-outer[data-cols="1"] #copyright .widget_pages li ul li {
        display: block;
        margin-left: 0;
      }

      #footer-outer[data-cols="1"] #copyright .widget [data-style="minimal-counter"] > li::before,
      #footer-outer[data-cols="1"] #copyright .arrow-circle {
        display: none;
      }
      #footer-outer[data-cols="1"] #copyright .widget_search {
        margin: 20px 0;
      }
      #footer-outer[data-cols="1"] #copyright li a i:after {
        position: absolute;
        -webkit-transition: all .45s cubic-bezier(0.25, 1, 0.33, 1);
        transition: all .45s cubic-bezier(0.25, 1, 0.33, 1);
        pointer-events: none;
        display: block;
        content: "";
        top: 0;
        left: 0;
        opacity: 0.2;
        border-radius: 50%;
        height: 46px;
        width: 46px;
      }
      #footer-outer[data-cols="1"] #copyright a i:after {
        border: 2px solid #fff;
      }
      #footer-outer[data-cols="1"] #copyright li a:hover i:after {
        opacity: 1;
      }
      body #footer-outer[data-cols="1"][data-disable-copyright="false"] .row {
        padding-top: 70px;
        padding-bottom: 40px;
      }
      #footer-outer[data-cols="1"][data-disable-copyright="false"] #copyright {
        padding-bottom: 70px;
      }
      body #footer-outer[data-cols="1"][data-disable-copyright="false"][data-using-widget-area="false"][data-copyright-line="false"][data-matching-section-color="true"] #copyright,
      body #footer-outer[data-cols="1"][data-disable-copyright="false"][data-using-widget-area="false"] #copyright {
        padding-top: 70px;
      }
      body #footer-outer[data-cols="1"][data-disable-copyright="false"][data-copyright-line="false"][data-matching-section-color="true"] .row {
        padding-bottom: 0;
      }
      body #footer-outer[data-cols="1"][data-disable-copyright="false"][data-copyright-line="false"][data-matching-section-color="true"] #copyright {
        padding-top: 30px;
      }
      #footer-outer[data-cols="1"] #copyright[data-layout="centered"] .col .social li {
        margin-top: 0;
      }';

    }


  // Footer BG
  if( isset($nectar_options['footer-background-image']) &&
      isset( $nectar_options['footer-background-image']['url'] ) &&
     !empty( $nectar_options['footer-background-image']['url'] ) ) {

       echo '#footer-outer[data-using-bg-img="true"] {
         background-size: cover;
         background-position: center;
       }
       #footer-outer[data-using-bg-img="true"]:after {
         position: absolute;
         width: 100%;
         height: 100%;
         content:"";
         left:0;
         top:0;
         background-color: inherit;
         opacity: 0.9;
       }';

       $footer_overlay_opacity = (isset($nectar_options['footer-background-image-overlay']) && !empty($nectar_options['footer-background-image-overlay'])) ? $nectar_options['footer-background-image-overlay'] : false;

       if( $footer_overlay_opacity ) {
         echo '#footer-outer[data-using-bg-img="true"][data-bg-img-overlay="'.esc_attr($footer_overlay_opacity).'"]:after {
           opacity: '.esc_attr($footer_overlay_opacity).';
         }';
       }
  }

  // Centered Copyright.
  $disable_footer_copyright = 'false';

  if ( isset( $nectar_options['disable-copyright-footer-area'] ) &&
       !empty( $nectar_options['disable-copyright-footer-area'] ) &&
       $nectar_options['disable-copyright-footer-area'] === '1' ) {
           $disable_footer_copyright = 'true';
  }

  if( 'false' === $disable_footer_copyright ) {

    $copyright_footer_layout = ( isset($nectar_options['footer-copyright-layout']) && ! empty( $nectar_options['footer-copyright-layout'] ) ) ? $nectar_options['footer-copyright-layout'] : 'default';
    if( 'centered' === $copyright_footer_layout ) {
      echo '
      #footer-outer #copyright[data-layout="centered"] .col,
      #footer-outer #copyright[data-layout="centered"] .col ul {
        width: 100%;
        float: none;
      }
      #footer-outer #copyright[data-layout="centered"] .col .social li {
        margin-top: 25px;
      }

      #footer-outer:not([data-cols="1"]) #copyright[data-layout="centered"] .social li a {
        height: 30px;
        width: 30px;
        line-height: 30px;
      }

      #footer-outer #copyright[data-layout="centered"] {
        padding: 45px 0;
      }

      #footer-outer #copyright[data-layout="centered"] li {
        float: none;
        display: inline-block;
        margin: 0 10px;
        width: auto;
      }
      @media only screen and (min-width: 1000px) {
        #footer-outer #copyright[data-layout="centered"] .col.span_5 {
          max-width: 70%;
          margin: 0 auto;
        }
      }
      @media only screen and (max-width: 999px) {
        #footer-outer #copyright[data-layout="centered"] .col.span_5 {
          margin-bottom: 0;
        }
      }
      #footer-outer #copyright[data-layout="centered"] .widget  {
        margin-bottom: 0;
      }

      #footer-outer #copyright[data-layout="centered"] .widget_nav_menu li,
      #footer-outer #copyright[data-layout="centered"] .widget_pages li {
        vertical-align: top;
        text-align: left;
        margin: 0 15px;
      }

      #footer-outer #copyright[data-layout="centered"] .widget_nav_menu li ul,
      #footer-outer #copyright[data-layout="centered"] .widget_pages li ul {
        padding-left: 0;
        margin-left: 0;
      }

      #footer-outer #copyright[data-layout="centered"] .widget_nav_menu li ul li,
      #footer-outer #copyright[data-layout="centered"] .widget_pages li ul li {
        display: block;
        margin-left: 0;
      }
      #footer-outer #copyright[data-layout="centered"] .widget [data-style="minimal-counter"] > li::before,
      #footer-outer #copyright[data-layout="centered"] .widget .arrow-circle {
        display: none;
      }
      #footer-outer #copyright[data-layout="centered"] .widget_search {
        margin: 20px 0;
      }
      #footer-outer #copyright[data-layout="centered"] .col {
        text-align: center;
      }';

    }

  }

  /*-------------------------------------------------------------------------*/
  /* 14.4. To Top Button
  /*-------------------------------------------------------------------------*/
  if( isset($nectar_options['back-to-top']) &&
      !empty($nectar_options['back-to-top']) &&
      '1' === $nectar_options['back-to-top'] ) {
        echo '
        #to-top{
          display:block;
          position:fixed;
          text-align:center;
          line-height:12px;
          right:17px;
          bottom:0px;
        	transform: translateY(105%);
          color:#fff;
          cursor:pointer;
          border-radius:2px;
          -webkit-border-radius:2px;
          z-index:9994;
          height:29px;
          width:29px;
          background-color:rgba(0,0,0,0.25);
          background-repeat:no-repeat;
          background-position:center;
          transition:background-color 0.1s linear;
          -webkit-transition:background-color 0.1s linear;
        }
        body[data-button-style*="rounded"] #to-top{
          transition:box-shadow 0.3s cubic-bezier(.55,0,.1,1),background-color 0.1s linear;
          -webkit-transition:-webkit-box-shadow 0.3s cubic-bezier(.55,0,.1,1),background-color 0.1s linear;
          background-color:rgba(0,0,0,0.25)
        }
        body[data-button-style*="rounded"] #to-top:hover,
        body[data-button-style*="rounded"] #to-top.dark:hover{
          transition:box-shadow 0.3s cubic-bezier(.55,0,.1,1),background-color 0.05s linear 0.25s;
          -webkit-transition:-webkit-box-shadow 0.3s cubic-bezier(.55,0,.1,1),background-color 0.05s linear 0.25s;
          box-shadow:1px 2px 3px rgba(0,0,0,0.16);
          background-color:transparent!important
        }
        body[data-button-style*="rounded"] #to-top:after,
        body[data-button-style*="rounded"] #to-top:before{
          display:block;
          content:" ";
          height:100%;
          width:100%;
          position:absolute;
          top:0;
          left:0;
          z-index:1;
          background-color:#000;
          transform:scale(0);
          -webkit-transform:scale(0);
          transition:all 0.3s cubic-bezier(.55,0,.1,1);
          -webkit-transition:all 0.3s cubic-bezier(.55,0,.1,1);
          border-radius:100px;
          -webkit-border-radius:100px
        }
        body[data-button-style*="rounded"] #to-top:before{
          background-color:rgba(255,255,255,0.25);
          transform:scale(1);
          -webkit-transform:scale(1);
          transition:all 0.5s cubic-bezier(0.165,0.84,0.44,1);
          -webkit-transition:all 0.5s cubic-bezier(0.165,0.84,0.44,1);
          opacity:0;
          z-index:2
        }
        body[data-button-style*="rounded"] #to-top:hover:after{
          transform:scale(1);
          -webkit-transform:scale(1);
        }
        body[data-button-style*="rounded"] #to-top{
          overflow:hidden
        }
        body[data-button-style*="rounded"] #to-top i.fa-angle-up.top-icon,
        body[data-button-style*="rounded"] #to-top i.fa-angle-up{
          -webkit-transform:translate(0,0px);
          transform:translate(0,0px);
          transition:transform 0.2s ease;
          -webkit-transition:transform 0.2s ease;
        }
        body[data-button-style*="rounded"] #to-top:hover i.fa-angle-up.top-icon,
        body[data-button-style*="rounded"] #to-top:hover i.fa-angle-up,
        body[data-button-style*="rounded"] #to-top.hovered i.fa-angle-up.top-icon,
        body[data-button-style*="rounded"] #to-top.hovered i.fa-angle-up{
          -webkit-transform:translate(0,-29px);
          transform:translate(0,-29px)
        }
        body[data-button-style*="rounded"] #to-top:active:before{
          opacity:1
        }
        #to-top i{
          line-height:29px;
          width:29px;
          height:29px;
          font-size:14px;
          top:0;
          left:0;
          text-align:center;
          position:relative;
          z-index:10;
          background-color:transparent
        }
        #to-top:hover,
        #to-top.dark:hover{
          background-color:#000
        }
        #to-top.dark{
          background-color:rgba(0,0,0,0.87)
        }
        body[data-button-style*="slightly_rounded"] #to-top {
          border-radius: 200px!important;
          -webkit-border-radius: 200px!important;
        }
        ';
  }

  /*-------------------------------------------------------------------------*/
  /* 14.5. Global Section
  /*-------------------------------------------------------------------------*/
  if( isset( $nectar_options['global-section-above-footer'] ) &&
      !empty( $nectar_options['global-section-above-footer']) ) {
        echo '
        #ajax-content-wrap .container-wrap {
        	padding-bottom: 0;
        }
        .woocommerce-checkout .nectar-global-section.before-footer,
        .woocommerce-account .nectar-global-section.before-footer,
        .woocommerce-cart .nectar-global-section.before-footer,
        body:not(.page):not(.single-post):not(.single-portfolio) .nectar-global-section.before-footer,
        .single-portfolio #regular_portfolio [data-nav-pos="in_header"] .nectar-global-section.before-footer {
          padding-top: 40px;
        }

        .container-wrap .row >.wpb_row:not(.full-width-section):not(.full-width-content):last-child {
          margin-bottom: 1.5em;
        }';
  }

  /*-------------------------------------------------------------------------*/
  /* 15. Column Spacing
  /*-------------------------------------------------------------------------*/
  if( isset( $nectar_options['column-spacing'] ) &&
  !empty( $nectar_options['column-spacing']) && 
  'default' !== $nectar_options['column-spacing']) {
    
    $column_spacing = intval($nectar_options['column-spacing']);

    if( in_array( $column_spacing, array(30,40,50,60,70)) ) {

      if( in_array( $column_spacing, array(30,40,50)) ) {
        echo '
        body[data-col-gap="'.$column_spacing.'px"] .wpb_row:not(.full-width-section):not(.full-width-content) {
          margin-bottom: '.$column_spacing.'px;
        }';
      }
      
      echo '
      body[data-col-gap="'.$column_spacing.'px"] .vc_row-fluid .span_12 {
        margin-left: -'.($column_spacing/2).'px;
        margin-right: -'.($column_spacing/2).'px;
      }
      
      body[data-col-gap="'.$column_spacing.'px"] .vc_row-fluid .wpb_column {
        padding-left: '.($column_spacing/2).'px;
        padding-right: '.($column_spacing/2).'px;
      }
      ';

      /* One fourths */
      echo '
      @media only screen and (max-width: 999px) and (min-width: 691px) {

        body[data-col-gap="'.$column_spacing.'px"] .vc_row-fluid:not(.inner_row):not(.full-width-content) > .span_12 > .one-fourths:not([class*="vc_col-xs-"]),
        body[data-col-gap="'.$column_spacing.'px"] .vc_row-fluid:not(.full-width-content) .vc_row-fluid.inner_row > .span_12 > .one-fourths:not([class*="vc_col-xs-"]) {
          margin-bottom: '.$column_spacing.'px;
        }

      }';

    }

  }

  /*-------------------------------------------------------------------------*/
  /* 16. Off Canvas Menu
  /*-------------------------------------------------------------------------*/
  if( 'fullscreen-inline-images' === $side_widget_class ) {
    echo '

    .fullscreen-inline-images .nectar-ocm-image-wrap-outer {
      background-color: #000;
    }
    @media only screen and (min-width : 1px) and (max-width : 999px) {
      #header-outer.lighten-logo.transparent #logo[data-supplied-ml="true"] img:not(.mobile-only-logo) {
        display: none;
      }
      #header-outer.lighten-logo.transparent #logo[data-supplied-ml="true"] img.mobile-only-logo {
        filter: brightness(0) invert(1);
        opacity: 1!important;
        display: block;
      }

      #header-outer.lighten-logo.transparent #top #logo:not([data-supplied-ml="true"]) img.stnd {
        filter: brightness(0) invert(1);
        opacity: 1!important;
      }
      #header-outer.lighten-logo.transparent #top #logo:not([data-supplied-ml="true"]) img:not(.stnd) {
        opacity: 0!important;
      }
    }


    @media only screen and (min-width : 1000px) {
      #header-outer.lighten-logo.transparent #top #logo img:not(.stnd) {
        opacity: 0!important;
      }
      #header-outer.lighten-logo.transparent #top #logo img.stnd {
        filter: brightness(0) invert(1);
        opacity: 1!important;
      }
    }


    #header-outer.side-widget-open.transparent #header-secondary-outer {
      position: absolute!important;
      visibility: hidden;
    }
    

    #slide-out-widget-area.fullscreen-inline-images{
      position:fixed;
      width:100%;
      height:100%;
      right:auto;
      left:0;
      top:0;
      z-index:9997;
      background-color:transparent!important;
      text-align:center;
      display: block;
      overflow-y:scroll;
      overflow-x:hidden;
      box-sizing:content-box;
      -ms-overflow-style: none; 
      scrollbar-width: none;  
      transform: none!important;
  }


    #slide-out-widget-area-bg.hidden,
    #slide-out-widget-area.hidden {
      z-index: -999;
      pointer-events: none;
      visibility: hidden;
    }

    #slide-out-widget-area-bg.hidden {
      top: 150%;
    }

    @media only screen and (min-width: 1000px) {
      #slide-out-widget-area-bg.hidden .nectar-ocm-image.current,
      #slide-out-widget-area-bg.hidden .nectar-ocm-image.default {
       position: fixed;
       top: 0; 
      }
    }
    @media only screen and (max-width: 999px) {
      #slide-out-widget-area-bg.hidden {
        top: 250%;
      }
    }

    
    #slide-out-widget-area-bg {
      width: 100%;
      height: 100%;
      -webkit-transition: opacity 0.35s ease;
      transition: opacity 0.35s ease;
      opacity: 0;
      background-color: transparent!important;
    }

    #slide-out-widget-area-bg .nectar-ocm-image {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
      background-position: center;
      background-size: cover;
      transform: scale(1.06);
      transition: transform 0.3s ease 0.35s, opacity 0.35s cubic-bezier(0.25,0,0.4,1);
    }

    #slide-out-widget-area-bg .nectar-ocm-image.active {
      opacity: 0.15;
      transform: scale(1);
      z-index: 100;
      transition: transform 4s cubic-bezier(0.07, 0.37, 0.23, 0.99) 0s, opacity 0.35s cubic-bezier(0.25,0,0.4,1);
    }

    #slide-out-widget-area-bg svg,
    #slide-out-widget-area-bg .nectar-ocm-image-wrap-outer,
    #slide-out-widget-area-bg .nectar-ocm-image-wrap {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    #slide-out-widget-area-bg .nectar-ocm-image-wrap-outer  {
      overflow: hidden;
    }
    #slide-out-widget-area-bg.hidden .nectar-ocm-image-wrap-outer  {
      transform: translateY(-100%);
    }
    #slide-out-widget-area-bg.hidden .nectar-ocm-image-wrap {
      transform: translateY(100%);
    
  } 
   #slide-out-widget-area-bg.open .nectar-ocm-image-wrap-outer,
    #slide-out-widget-area-bg.open .nectar-ocm-image-wrap {
        transform: translateY(0);
        transition: transform 1s cubic-bezier(0.76, 0, 0.3, 1);
    }
    
    #slide-out-widget-area-bg .nectar-ocm-image.current {
      transform: scale(1.12);
    }
    #slide-out-widget-area-bg.open .nectar-ocm-image.current {
      opacity: 0.15;
      transform: scale(1);
      transition: transform 1.7s cubic-bezier(0.4, 0, 0.3, 1) 0s, opacity 0.35s cubic-bezier(0.25,0,0.4,1);
      z-index: 10;
    }

    #slide-out-widget-area-bg.open.medium .nectar-ocm-image.current,
    #slide-out-widget-area-bg.medium .nectar-ocm-image.active {
      opacity: 0.4;
    }
    #slide-out-widget-area-bg.open.light .nectar-ocm-image.current,
    #slide-out-widget-area-bg.light .nectar-ocm-image.active {
      opacity: 0.65;
    }
    #slide-out-widget-area-bg.open.solid .nectar-ocm-image.current,
    #slide-out-widget-area-bg.solid .nectar-ocm-image.active {
      opacity: 0;
    }

    #slide-out-widget-area-bg .nectar-ocm-image.current.hidden {
      opacity: 0!important;
    }

    #slide-out-widget-area-bg.open{ 
      opacity: 1; 
    }

    
    #slide-out-widget-area.fullscreen-inline-images::-webkit-scrollbar {
      display: none;
    }
    
    #slide-out-widget-area .inner-wrap {
      min-height: 100%;
      width: 100%;
    }

    #slide-out-widget-area.fullscreen-inline-images .inner {
        width:100%;
        position:relative;
        top: 0;
        left:0;
        height: auto;
        margin: auto;
        padding: 30px 0;
    }


    #slide-out-widget-area.fullscreen-inline-images .inner-wrap {
      display: flex;
      flex-direction: column;
  }

    @media only screen and (min-width: 691px) {
     
      #slide-out-widget-area .off-canvas-menu-container .menu, #slide-out-widget-area .off-canvas-menu-container .menu ul {
        flex-wrap: wrap;
        display: flex;
        justify-content: center;
      }
      .menuwrapper >.sub-menu.dl-animate-in-4,
      #slide-out-widget-area .menuwrapper >.sub-menu,
      body #slide-out-widget-area .menu.subview .subview, 
      body #slide-out-widget-area .menu.subview .subview .sub-menu, 
      body #slide-out-widget-area .menu.subview .subviewopen, 
      body #slide-out-widget-area .menu.subview .subviewopen>.sub-menu {
        display: flex!important;
        flex-wrap: wrap;
        justify-content: center;
      }
    }

    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(1) > a .wrap {transition-delay: 0.5s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(2) > a .wrap {transition-delay: 0.54s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(3) > a .wrap {transition-delay: 0.58s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(4) > a .wrap {transition-delay: 0.62s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(5) > a .wrap {transition-delay: 0.66s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(6) > a .wrap {transition-delay: 0.7s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(7) > a .wrap {transition-delay: 0.74s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(8) > a .wrap {transition-delay: 0.78s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(9) > a .wrap {transition-delay: 0.82s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(10) > a .wrap {transition-delay: 0.86s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li:nth-child(11) > a .wrap {transition-delay: .9s; }
    #slide-out-widget-area.open .off-canvas-menu-container .menu > li > a .wrap {transition-delay: .9s; }

    .fullscreen-inline-images.hidden .inner .off-canvas-menu-container li[class*="current"] a span:after {
      opacity: 0;
    }
    #slide-out-widget-area.fullscreen-inline-images.open .inner .off-canvas-menu-container li[class*="current"] a span:after {
      opacity: 1;
      transition: opacity 0.7s ease, transform 0.4s cubic-bezier(0.52,0.01,0.16,1);
      transition-delay: 1.05s;
    }
    
    #slide-out-widget-area .off-canvas-menu-container .menu li a,
    .menuwrapper >.sub-menu.dl-animate-in-4 > li >a,
    #slide-out-widget-area .menuwrapper >.sub-menu > li > a {
      margin: 0.35em;
      padding: 0.1em;
      display: block;
      overflow: hidden;
      transition: color 0.4s cubic-bezier(0.52, 0.01, 0.16, 1);
    }

    #slide-out-widget-area .menuwrapper >.sub-menu > li.back,
    .menuwrapper >.sub-menu.dl-animate-in-4 .back,
    body #slide-out-widget-area .menu.subview .subviewopen >.sub-menu >li.back {
        width: 100%;
        margin-bottom: 0!important;
    }

    #slide-out-widget-area .off-canvas-menu-container .menu > li > a .wrap {
      transition: transform 0.2s ease 0.35s, opacity 0.25s ease;
      line-height: 1.1;
      opacity: 0;
      transform: translateY(103%);
    }

    #slide-out-widget-area:not(.open) .off-canvas-menu-container .sub-menu {
      opacity: 0;
      transition: opacity 0.25s ease;
    }

    #slide-out-widget-area.open .off-canvas-menu-container .menu > li > a .wrap {
      transition: transform 1.1s cubic-bezier(0.25, 1, 0.5, 1);
    }
    #slide-out-widget-area .off-canvas-menu-container .menu li a .wrap {
      display: block;
      position: relative;
    }
   
    #slide-out-widget-area .off-canvas-menu-container .menu > li > a .wrap .nav_desc,
    #slide-out-widget-area .off-canvas-menu-container .menu li a .wrap .item_desc {
      max-width: 200px;
      margin: 0 auto;
      text-align: center;
    }

    #slide-out-widget-area.open .off-canvas-menu-container .menu > li > a .wrap {
      transform: translateY(0);
      opacity: 1;
    }

    #slide-out-widget-area .off-canvas-menu-container {
      padding: 0 15%;
    }


  .fullscreen-inline-images .inner .widget.widget_nav_menu li a,
  .fullscreen-inline-images .inner .off-canvas-menu-container li a{
      font-size:48px;
      line-height:48px;
  }

    @media only screen and (max-width: 690px) {

        #slide-out-widget-area.fullscreen-inline-images .inner .widget.widget_nav_menu li a,
        #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a{
            font-size:34px;
            line-height:34px;
            margin: 0.1em;
        }
        
    }

    #slide-out-widget-area.fullscreen-inline-images .inner .widget.widget_nav_menu li a,
    #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a {
        display:inline-block;
        position:relative;
        opacity:1;
    }
    .fullscreen-inline-images .inner .widget.widget_nav_menu li a,
    .fullscreen-inline-images .inner .off-canvas-menu-container li a {
        color:rgba(255,255,255,1);
    }

    #slide-out-widget-area.fullscreen-inline-images .inner .widget.widget_nav_menu li.no-pointer-events,
    #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li.no-pointer-events {
        pointer-events:none
    }
    

    #slide-out-widget-area .off-canvas-menu-container li a .wrap:after {
      position:absolute;
      display:block;
      left:0;
      width:100%;
      -webkit-transform:scaleX(0);
      transform:scaleX(0);
      border-top:2px solid #000;
      content:"";
      transform-origin: left;
      pointer-events: none;
      bottom: -2px;
      transition: none;
      border-color: #fff;
      transition: transform 0.4s cubic-bezier(0.52, 0.01, 0.16, 1);
    }

    @media only screen and (max-width: 999px) {
      #slide-out-widget-area .off-canvas-menu-container li a{
       transition: none!important;
      }
      #slide-out-widget-area.open .off-canvas-menu-container li a span:after {
        transition: opacity 0.5s ease 1.1s!important;
       }
      #slide-out-widget-area .bottom-meta-wrap {
        margin-bottom: 25px;
      }
    }

    #slide-out-widget-area .off-canvas-menu-container li a:hover span:after,
    #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li[class*="current"] a span:after {
       transform: scaleX(1);
    }

    #slide-out-widget-area .off-canvas-menu-container li.back a span:after {
      display: none;
    }
 
    #slide-out-widget-area.fullscreen-inline-images .inner .widget{
        max-width: 800px;
        width:100%;
        padding: 0 50px;
        margin:20px auto 20px auto;
    }

    #slide-out-widget-area.fullscreen-inline-images .inner .widget p:last-child {
      padding-bottom: 0;
    }

   
  
    #slide-out-widget-area.fullscreen-inline-images .widget_recent_comments ul li{
        background:transparent;
        margin-bottom:0px;
        padding:0px
    }

  
    #slide-out-widget-area.fullscreen-inline-images .tagcloud a{
        float:none;
        display:inline-block
    }

    #slide-out-widget-area.fullscreen-inline-images .widget_calendar table tbody td{
        padding:20px
    }

   
    body[data-slide-out-widget-area-style="fullscreen-inline-images"] #header-outer{
        border-bottom-color:transparent
    }
 
  
    #slide-out-widget-area.fullscreen-inline-images .nectar-header-text-content,
    #slide-out-widget-area.fullscreen-inline-images .bottom-meta-wrap,
    #slide-out-widget-area.fullscreen-inline-images .widget,
    #slide-out-widget-area.fullscreen-inline-images .nectar-global-section {
      opacity: 0;
      -webkit-transition: opacity 0.5s ease;
      transition: opacity 0.5s ease;
    }
    #slide-out-widget-area.fullscreen-inline-images.open .widget,
    #slide-out-widget-area.fullscreen-inline-images.open .nectar-global-section {
      transition-delay: 0.6s;
    }
    #slide-out-widget-area-bg.open + #slide-out-widget-area.fullscreen-inline-images .nectar-header-text-content,
    #slide-out-widget-area.fullscreen-inline-images.open .bottom-meta-wrap,
    #slide-out-widget-area.fullscreen-inline-images.open .widget, 
    #slide-out-widget-area.fullscreen-inline-images.open .nectar-global-section {
      opacity: 1;
    }
    .nectar-global-section.nectar_hook_ocm_after_menu {
      margin-top: 25px;
    }
    #slide-out-widget-area.fullscreen-inline-images.open .bottom-meta-wrap {
      transition-delay: 0.8s;
    }
    #slide-out-widget-area-bg.fullscreen-inline-images.padding-removed{
        padding:0!important
    }
    .admin-bar #slide-out-widget-area-bg.fullscreen-inline-images.padding-removed{
        padding-top:32px!important
    }
    .admin-bar #slide-out-widget-area-bg.fullscreen-inline-images{
        padding-top:52px
    }

    .admin-bar #slide-out-widget-area {
      padding-top: 0;
    }


    @media only screen and (min-width: 1000px) {
      #slide-out-widget-area.fullscreen-inline-images .off-canvas-social-links{
          position:fixed;
          bottom:18px;
          right:18px
      }
      #slide-out-widget-area.fullscreen-inline-images .bottom-text{
        position:fixed;
        bottom:28px;
        left:28px;
        padding-bottom:0
    }
    }
    
    
    #slide-out-widget-area.fullscreen-inline-images .bottom-text {
        color:#fff
    }
    @media only screen and (min-width:1000px){
       
        #slide-out-widget-area.fullscreen-inline-images .bottom-text[data-has-desktop-social="false"]{
            left:50%;
            transform:translateX(-50%);
            -webkit-transform:translateX(-50%)
        }
    }
    
  


    body #slide-out-widget-area .inner >div:first-of-type {
      margin-top: 3%;
    }
    #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container {
      margin-bottom: 0;
    }
   
    body #slide-out-widget-area.fullscreen-inline-images .slide_out_area_close{
        display:none!important
    }
  
    #slide-out-widget-area.fullscreen-inline-images .menuwrapper li a{
        display:block;
        position:relative;
        color:#fff;

    }
    #slide-out-widget-area.fullscreen-inline-images .menuwrapper li a{
        overflow:hidden
    }
    #slide-out-widget-area.fullscreen-inline-images .menuwrapper li small {
        display:block
    }
    #slide-out-widget-area.fullscreen-inline-images .menuwrapper li.back >a{
        transform:scale(0.5);
        -webkit-transform:scale(0.5);
        background-color:transparent!important;
        margin-left: .35em!important;
        border: 2px solid;
        transform-origin: center bottom;
        padding: 0.25em 0.5em;
    }
 
    
    #slide-out-widget-area.fullscreen-inline-images .widget_shopping_cart {
      max-width: 450px;
    }
    #slide-out-widget-area.fullscreen-inline-images .wp-block-search .wp-block-search__inside-wrapper {
      display: flex;
    }
    

    ';
  }

  /*-------------------------------------------------------------------------*/
  /* 16.1 Font Sizing
  /*-------------------------------------------------------------------------*/
  $ocm_custom_font_size_desktop = (isset($nectar_options['header-slide-out-widget-area-custom-font-size'])) ? $nectar_options['header-slide-out-widget-area-custom-font-size'] : false;
  $ocm_custom_font_size_mobile = (isset($nectar_options['header-slide-out-widget-area-custom-font-size-mobile'])) ? $nectar_options['header-slide-out-widget-area-custom-font-size-mobile'] : false;
  
  if( $ocm_custom_font_size_desktop && !empty($ocm_custom_font_size_desktop) && class_exists('NectarElDynamicStyles') ) {

    $ocm_font_size_selector = 'body #slide-out-widget-area .inner .off-canvas-menu-container li a';

    if( 'fullscreen-inline-images' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area .inner .off-canvas-menu-container li a';
    } 
    else if( 'fullscreen' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area.fullscreen .inner .off-canvas-menu-container li a';
    }
    else if( 'fullscreen-alt' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a';
    }
    else if( 'slide-out-from-right-hover' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area.slide-out-from-right-hover .inner .off-canvas-menu-container li a';
    } 

    echo '@media only screen and (min-width: 1000px) {
      '.$ocm_font_size_selector.' {
      font-size: '.esc_attr(NectarElDynamicStyles::font_sizing_format($ocm_custom_font_size_desktop)).'!important;
      line-height: 1!important;
     }
    }';
  }
  
  if( $ocm_custom_font_size_mobile && !empty($ocm_custom_font_size_mobile) && class_exists('NectarElDynamicStyles') ) {

    $ocm_font_size_selector = 'body #slide-out-widget-area .inner .off-canvas-menu-container li a';

    if( 'fullscreen-inline-images' === $side_widget_class ) {
      $ocm_font_size_selector .= ', body #slide-out-widget-area.fullscreen-inline-images .inner .widget.widget_nav_menu li a, 
      body #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a';
    } 
    else if( 'fullscreen' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area.fullscreen .inner .off-canvas-menu-container li a';
    }
    else if( 'fullscreen-alt' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a';
    }
    else if( 'slide-out-from-right-hover' === $side_widget_class ) {
      $ocm_font_size_selector = ' body #slide-out-widget-area.slide-out-from-right-hover .inner .off-canvas-menu-container li a';
    } 
    else if( 'simple' === $side_widget_class ) {
      $ocm_font_size_selector = ' #header-outer #mobile-menu ul li a';
    } 

    echo '
    @media only screen and (max-width: 999px) {
      '.$ocm_font_size_selector.' {
        font-size: '.esc_attr(NectarElDynamicStyles::font_sizing_format($ocm_custom_font_size_mobile)).'!important;
        line-height: 1!important;
      }
    }';
  }

  /*-------------------------------------------------------------------------*/
  /* 17. Animations
  /*-------------------------------------------------------------------------*/
  $cubic_beziers = nectar_cubic_bezier_easings();
  $animation_easing = isset($nectar_options['column_animation_easing']) && !empty($nectar_options['column_animation_easing']) ? $nectar_options['column_animation_easing'] : 'easeOutCubic';
  $animation_duration = isset($nectar_options['column_animation_timing']) && !empty($nectar_options['column_animation_timing']) ? $nectar_options['column_animation_timing'] : '800';

  if( isset($cubic_beziers[$animation_easing]) ) {
    echo '.nectar-waypoint-el {
      transition: transform '.$animation_duration.'ms cubic-bezier('.$cubic_beziers[$animation_easing].'), opacity 450ms ease; 
    }';
  }



  /*-------------------------------------------------------------------------*/
  /* 18. Third Party
  /*-------------------------------------------------------------------------*/
  /*-------------------------------------------------------------------------*/
  /* 18.1. WooCommerce Theme Skin
  /*-------------------------------------------------------------------------*/

  if( function_exists( 'is_woocommerce' ) && 'material' === $theme_skin ) {
    echo '
    .woocommerce #sidebar .widget_layered_nav ul li a {
      padding-left: 25px!important;
    }
    
    body:not(.ascend).woocommerce #sidebar .widget_layered_nav ul li a:before,
    body:not(.ascend).woocommerce-page #sidebar .widget_layered_nav ul li a:before,
    body:not(.ascend).woocommerce #sidebar .widget_layered_nav ul li:first-child a:before {
      top: 14px;
    }

    body.material #header-outer[data-transparent-header="true"] .cart-outer .cart-notification,
    body.material #header-outer[data-transparent-header="true"] .cart-outer .widget_shopping_cart {
      margin-top: 0;
    }

    #header-outer .nectar-woo-cart .widget_shopping_cart,
    #header-outer .nectar-woo-cart .cart-notification{
      top: 100%;
    }

    #header-outer .nectar-woo-cart .cart-menu-wrap {
      position: relative;
      width: auto;
      overflow: visible;
      right: auto!important;
    }

    .woocommerce #sidebar div ul li,
    .material.woocommerce #sidebar div ul li {
      padding:3px 0
    }


    body:not(.ascend).woocommerce.material #sidebar .widget_layered_nav ul li:first-child a:before,
    body:not(.ascend).woocommerce.material #sidebar .widget_layered_nav ul li a:before,
    body:not(.ascend).woocommerce-page.material #sidebar .widget_layered_nav ul li a:before,
    body:not(.ascend).woocommerce.material #sidebar .widget_layered_nav ul li a:after,
    body:not(.ascend).woocommerce-page.material #sidebar .widget_layered_nav ul li a:after {
      top: 50%;
      margin-top: -1px;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
    }

    body:not(.ascend).material #sidebar .widget.woocommerce li:first-child > .count {
      top: 6px;
    }

    body:not(.ascend) #sidebar .widget.woocommerce li .count {
      top: 6px;
    }

    body.woocommerce-page.material .product-categories .children {
      margin-top: 3px;
    }


    #header-outer:not([data-format="left-header"]) .cart-menu .cart-icon-wrap {
      height: 22px;
      line-height: 22px;
    }
    
    body.material #header-outer:not([data-format="left-header"]) .cart-menu {
      position: relative;
      right: auto;
      padding: 0;
      background-color: transparent;
      top: auto;
    }
    
    
    body[data-header-format="left-header"] #header-outer .cart-outer {
      -webkit-transition: opacity .35s cubic-bezier(0.12,0.75,0.4,1);
      transition: opacity .35s cubic-bezier(0.12,0.75,0.4,1);
    }
    body[data-header-format="left-header"] #header-outer.material-search-open .cart-outer {
      opacity: 0;
      pointer-events: none;
    }

    body.material #header-outer[data-format="left-header"] .cart-menu > a,
    body[data-header-format="left-header"].material #header-outer nav ul li .cart-menu > a {
      padding: 0;
    }
    body.material #header-outer:not([data-format="left-header"]) .cart-wrap {
      margin-right: 0;
      top: -5px;
      right: -9px;
      color: #fff;
    }
    @media only screen and (max-width: 999px) {
      body.material #header-outer[data-format="left-header"] .cart-wrap {
        margin-right: 0;
        top: -5px;
        right: -9px;
        color: #fff;
      }
    }

    #header-outer .cart-menu .cart-icon-wrap .icon-salient-cart {
      left:0;
      transition:all .2s ease;
      -webkit-transition:all .2s ease
    }
    body.material #header-outer .cart-menu .cart-icon-wrap .icon-salient-cart {
      font-size:20px
    }
    .icon-salient-cart:before, body.material .icon-salient-cart:before {
      content:"\e910"
    }
    .material #header-outer a.cart-contents .cart-wrap span:before {
      display:none
    }
    #header-outer .cart-wrap {
      font-size:11px
    }

    #header-outer a.cart-contents .cart-wrap span {
      visibility:hidden;
    }
    body.material #header-outer a.cart-contents .cart-wrap span {
      border-radius:99px;
      font:bold 11px/16px Arial;
      line-height:18px;
      width:18px;
      padding:0 1px;
      box-shadow: 0 5px 12px rgba(0,0,0,0.2);
    }

    #header-outer .static a.cart-contents span {
      visibility:visible;
    }
    #header-outer .has_products .cart-menu .cart-icon-wrap .icon-salient-cart {
      transition:all .2s ease;
      -webkit-transition:all .2s ease
    }
    body.material #header-outer .cart-menu {
      border-left:1px solid rgba(0,0,0,0.07);
      background-color:transparent
    }

    @media only screen and (min-width: 1000px) {
      .woocommerce.archive #sidebar.span_3, 
      .woocommerce.single #sidebar.span_3 {
        width: 21%;
      }
      .woocommerce.archive #sidebar.span_3.col_last {
        width: 20%;
      }
      .woocommerce.archive .post-area.span_9, 
      .woocommerce.single .post-area.span_9 {
        width: 75.5%;
      }
      .woocommerce.single .post-area.span_9:not(.col_last) {
        margin-right: 0;
        padding-right: 20px;
      }
      .woocommerce.single #sidebar.span_3.col_last {
        margin-left: 2%;
      }
    }

    .woocommerce nav.woocommerce-pagination ul li a,
    .woocommerce .container-wrap nav.woocommerce-pagination ul li span {
      border-radius:0!important
    }


    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
      top: -7px!important;
    }
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
    .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
    .woocommerce-page .widget_price_filter .price_slider_wrapper .ui-widget-content {
      height: 4px;
    }
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle:before,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle:before {
      position: absolute;
      content: "";
      display: block;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-radius: 100px;
      box-shadow: 0 0 0 10px #000 inset;
      transition: box-shadow 0.2s ease;
    }
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle.ui-state-active,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle.ui-state-active {
      transform: scale(1.2);
      box-shadow:0px 5px 12px rgba(0,0,0,0.2)!important;
    }
    .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
    .woocommerce-page .widget_price_filter .price_slider_wrapper .ui-widget-content,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
      box-shadow: none!important;
      border: 0!important;
    }

    .woocommerce .actions .button {
      height:auto!important;
      padding:14px!important
    }
    ';
  }



  /*-------------------------------------------------------------------------*/
  /* 18. WooCommerce AJAX Cart
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) &&
      isset( $nectar_options['enable-cart'] ) &&
      !empty( $nectar_options['enable-cart']) &&
      '1' === $nectar_options['enable-cart'] ) {

    $nav_cart_style = ( isset( $nectar_options['ajax-cart-style'] ) ) ? $nectar_options['ajax-cart-style'] : 'default';

    if( 'slide_in' === $nav_cart_style || 'slide_in_click' === $nav_cart_style ) {

      $cart_underline_color = $nectar_options["accent-color"];

      if( !empty($nectar_options['header-color']) && $nectar_options['header-color'] === 'custom' ) {
        if( !empty($nectar_options['header-font-hover-color']) ) {
          $cart_underline_color = $nectar_options['header-font-hover-color'];
        }
      }

      echo '
      .nectar-slide-in-cart .widget_shopping_cart .cart_list li {
      	padding: 20px 20px;
      }
      .nectar-slide-in-cart .widget_shopping_cart .cart_list li span.quantity {
      	line-height: 12px;
      }
      .nectar-slide-in-cart:not(.style_slide_in_click) .widget_shopping_cart .cart_list li span.quantity {
        display: block;
      }
      .nectar-slide-in-cart {
      	position: fixed;
      	height: 100%;
      	right: 0;
      	top: 0;
      	z-index: 100000;
      	-ms-transform: translateX(107%);
      	transform: translateX(107%) translateZ(0);
      	-webkit-transform: translateX(107%) translateZ(0);
      }

      .nectar-slide-in-cart,
      .nectar-slide-in-cart.style_slide_in_click > .inner {
        -webkit-transition: all .8s cubic-bezier(0.2,1,.3,1);
        transition: all .8s cubic-bezier(0.2,1,.3,1);
      }

      .nectar-slide-in-cart.open {
          -ms-transform: translateX(0%);
          transform: translateX(0%) translateZ(0);
          -webkit-transform: translateX(0%) translateZ(0);
      }

      .nectar-slide-in-cart .widget_shopping_cart {
      	position: relative;
      	height: 100%;
      	left: 0;
      	top: 0;
      	display: block!important;
      	opacity: 1!important;
      }

      .nectar-slide-in-cart .widget_shopping_cart .cart_list > li:first-child {
      	padding-top: 0;
      	min-height: 98px;
      }

      .nectar-slide-in-cart .widgettitle {
           display: none;
      }

      .nectar-slide-in-cart .total,
      .nectar-slide-in-cart .woocommerce.widget_shopping_cart .total {
           padding: 20px 0 20px;
      }

      .nectar-slide-in-cart.style_slide_in_click .total .tax_label {
        margin-left: 7px;
      }

      body .nectar-slide-in-cart ul.product_list_widget li dl dd {
           color: inherit;
      }

      body .nectar-slide-in-cart ul.product_list_widget li dl {
           width: 220px;
      }

      body .nectar-slide-in-cart .total,
      body .nectar-slide-in-cart .total strong {
           color: #000;
      }

      .nectar-slide-in-cart {
           background-color: #fff;
      }
      .nectar-slide-in-cart:not(.style_slide_in_click) {
           box-shadow: 0 3px 20px rgba(0,0,0,0.09);
      }

      .nectar-slide-in-cart:not(.style_slide_in_click) .widget_shopping_cart_content {
           height: 100%;
           padding: 35px;
      }

       .nectar-slide-in-cart .widget_shopping_cart .cart_list .mini_cart_item > a {
           font-size: 18px;
           line-height: 24px;
      }

       .nectar-slide-in-cart .widget_shopping_cart .buttons a {
           display: block;
           padding: 20px;
           font-size: 16px;
           margin-top: 8px;
           margin-left: 0;
      }
      body .nectar-slide-in-cart:not(.style_slide_in_click) .woocommerce.widget_shopping_cart .cart_list li a.remove {
           position: absolute;
           right: 0;
           height: 23px;
           width: 23px;
           padding: 3px!important;
           line-height: 14px;
           margin: 0;
           font-size: 24px;
           transition: all 0.47s cubic-bezier(0.3, 1, 0.3, 0.95) 0s;
           -webkit-transition: all 0.47s cubic-bezier(0.3, 1, 0.3, 0.95) 0s;
      }
       .nectar-slide-in-cart:not(.style_slide_in_click) .woocommerce.widget_shopping_cart .cart_list li a.remove:hover {
           transform: rotate(90deg) translateZ(0);
           -webkit-transform: rotate(90deg) translateZ(0);
      }
      body  .nectar-slide-in-cart .widget_shopping_cart .cart_list a img {
          width: 75px;
           height: auto;
      }
       .nectar-slide-in-cart .widget_shopping_cart .cart_list {
           display: block!important;
           max-height: 65%;
           overflow-y: auto;
           overflow-x: hidden;
           width: 300px;
      }

     .nectar-slide-in-cart:not(.style_slide_in_click) .widget_shopping_cart .cart_list a img,
     .nectar-slide-in-cart:not(.style_slide_in_click) .widget_shopping_cart .cart_list .no-permalink img {
         position: absolute;
         left: 0;
         float: none;
      }
       .nectar-slide-in-cart:not(.style_slide_in_click) .widget_shopping_cart .cart_list li {
           padding-left: 100px;
           min-height: 112px;
           border-bottom: 1px solid #eee;
      }
       body .nectar-slide-in-cart ul.product_list_widget li dl {
           clear: none;
           float: none;
           margin-bottom: 10px;
      }
       body.admin-bar .nectar-slide-in-cart .widget_shopping_cart {
           top: 32px;
      }

      .nectar-slide-in-cart:not(.style_slide_in_click) .woocommerce.widget_shopping_cart .total {
         border: 0;
      }

      .nectar-slide-in-cart .widget_shopping_cart .cart_list {
        width: 100%;
      }
      .nectar-slide-in-cart.style_slide_in .widget_shopping_cart .cart_list {
        max-width: 300px;
      }';

      if( 'slide_in_click' === $nav_cart_style ) {

        echo '
        @media only screen and (max-width: 850px) and (-webkit-min-device-pixel-ratio: 2) {
           body .nectar-slide-in-cart {
            transform: translateX(50%);
            opacity: 0;
            pointer-events: none;
          }
          body .nectar-slide-in-cart.open {
            opacity: 1;
            pointer-events: all;
            transform: translateX(0%);
          }
          body .nectar-slide-in-cart.style_slide_in_click > .inner {
            -webkit-transform: none;
            transform: none;
          }
        }

        .nectar-slide-in-cart .widget_shopping_cart_content {
           height: 100%;
           padding: 40px;
        }
        .nectar-slide-in-cart.style_slide_in_click .close-cart {
          transition: opacity 0.2s ease;
        }
        body:not(.material) .nectar-slide-in-cart.style_slide_in_click .close-cart:hover {
          opacity: 0.55;
        }
        .nectar-slide-in-cart.style_slide_in_click .woocommerce.widget_shopping_cart .total {
           border-top: 1px solid rgba(0,0,0,0.1);
        }
        body .nectar-slide-in-cart .woocommerce.widget_shopping_cart .cart_list li a.remove {
          float: none;
          margin-left: 0;
          margin-bottom: 0;
          border: none;
          font-size: inherit;
          height: auto;
          line-height: 17px;
          width: auto;
          transition: opacity 0.2s ease;
        }
        body .nectar-slide-in-cart .woocommerce.widget_shopping_cart .cart_list li a.remove:hover {
          opacity: 0.55;
        }
        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list {
         max-height: none;
        }
        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list li {
        	padding: 20px 0;
          display: flex;
          align-items: center;
          border-top: 1px solid rgba(0,0,0,0.1);
        }
        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list li:first-child {
          border-top: none;
        }
        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart_content {
          width: 600px;
        }
        body .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list a img {
          float: left;
        }
        .nectar-slide-in-cart.style_slide_in_click {
          overflow: hidden;
        }
        .nectar-slide-in-cart.style_slide_in_click .cart_list .product-meta .product-details > a:not(.remove) {
          display: inline;
        }
        .nectar-slide-in-cart.style_slide_in_click .buttons a {
          margin-right: 0;
        }
        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart_content {
          display: flex;
          flex-direction: column;
        }

        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list {
          flex: 1;
        }
        .nectar-slide-in-cart.style_slide_in_click .woocommerce-mini-cart__total,
        .nectar-slide-in-cart.style_slide_in_click .nectar-inactive {
          margin-top: auto;
        }
        .nectar-slide-in-cart.style_slide_in_click .nectar-inactive {
          cursor: not-allowed;
        }
        .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart_content .nectar-inactive a {
          opacity: 0.3;
          pointer-events: none;
          filter: grayscale(1);
        }
        .nectar-slide-in-cart.style_slide_in_click > .inner {
          -webkit-transform: translateX(-107%);
          transform: translateX(-107%);
          height: 100%;
        }

        .style_slide_in_click .cart_list .product-meta a:not(.remove) {
          background-repeat: no-repeat;
          background-size: 0% 2px;
          background-image: linear-gradient(to right, '.esc_attr($cart_underline_color).' 0%, '.esc_attr($cart_underline_color).' 100%);
          -webkit-transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
          transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
          background-position: left bottom;
        }
        .style_slide_in_click .cart_list .product-meta a:hover:not(.remove) {
          background-size: 100% 2px;
        }
        .nectar-slide-in-cart.style_slide_in_click.open > .inner {
          -webkit-transform: translateX(0);
          transform: translateX(0);
        }

        .style_slide_in_click .product-meta {
          display: flex;
          position: relative;
          flex: 1;
        }
        .style_slide_in_click .product-meta > .product-details {
          width: 50%;
          padding-right: 30px;
          line-height: 1.5;
          align-self: center;
        }

        .style_slide_in_click .product-meta > .quantity {
          flex: 1;
          display: flex;
          align-items: center;
        }
        .style_slide_in_click .product-meta .modify > .quantity {
          margin: 0;
        }
        .style_slide_in_click .product-meta > .quantity > * {
          display: inline-block;
        }
        .style_slide_in_click .product-meta > .quantity .product-price {
          margin-left: auto;
        }
        .style_slide_in_click .product-meta > .quantity .product-price > * {
          display: block;
          line-height: 1!important;
        }
        .style_slide_in_click .product-meta > .quantity .amount bdi {
          font-size: 16px;
        }
        .style_slide_in_click .product-meta > .quantity .modify {
          min-width: 100px;
        }
        body .nectar-slide-in-cart ul.product_list_widget li dl {
         width: auto;
         line-height: 1.5;
         margin: 5px 0 0 0;
         padding-left: 0;
         border-left: none;
      }

      .nectar-slide-in-cart.nectar-slide-in-cart .widget_shopping_cart {
        height: calc(100% - 45px);
        top: 38px;
      }
      body.admin-bar .nectar-slide-in-cart.nectar-slide-in-cart .widget_shopping_cart {
        top: 70px;
        height: calc(100% - 75px);
       }
      #slide-out-widget-area .widget_shopping_cart .cart_list li .product-meta .product-details {
          line-height: 1.4;
        }
       body #slide-out-widget-area .widget_shopping_cart .cart_list li .product-meta .product-details > a {
        max-width: 100%;
        margin-bottom: 0;
        display: inline;
       }
       body #slide-out-widget-area .widget_shopping_cart .cart_list li {
        padding-bottom: 35px;
      }
      body #slide-out-widget-area ul.product_list_widget li img {
        width: 75px;
      }
      #slide-out-widget-area .widget_shopping_cart .qty {
        color: inherit;
      }
      body #slide-out-widget-area .widget_shopping_cart .woocommerce-Price-amount {
     	 display: block;
      }
      #slide-out-widget-area .woocommerce.widget_shopping_cart .cart_list li a.remove.with_text {
         margin-bottom: 10px;
      }


       @media only screen and (max-width:690px) {
         .nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart_content {
           width: 90vw;
         }
         .style_slide_in_click .product-meta > .product-details {
           width: 57%;
         }
         .nectar-slide-in-cart .widget_shopping_cart_content {
            padding: 30px;
        }
        .nectar-slide-in-cart.style_slide_in_click .inner > .header {
            left: 30px;
            width: calc(100% - 60px);
        }
         .style_slide_in_click .product-meta > .quantity {
            align-items: flex-end;
            flex-direction: column-reverse;
        }
        .style_slide_in_click .product-meta > .quantity .modify {
            margin-top: 25px;
        }
       }';

      }

    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.3. WooCommerce Quantity Style
  /*-------------------------------------------------------------------------*/
  $qty_style = 'default';

  if( function_exists( 'is_woocommerce' ) ) {

    $qty_style = ( isset( $nectar_options['qty_button_style'] ) && !empty( $nectar_options['qty_button_style'] ) ) ? $nectar_options['qty_button_style'] : 'default';

    if( 'default' === $qty_style ) {

      echo '
      .ascend .cart .quantity input.qty {
          height:46px;
          width:46px
      }

      .woocommerce .quantity,
      .woocommerce-page .quantity,
      .woocommerce #content .quantity,
      .woocommerce-page #content .quantity {
      	width: auto!important;
      }
      .woocommerce div.product form.cart div.quantity {
        width: auto;
      }

      .cart .quantity input.plus,
      .cart .quantity input.minus,
      .woocommerce-mini-cart .quantity input.plus,
      .woocommerce-mini-cart .quantity input.minus {
      	color: #666;
      	width: 35px;
      	height: 35px;
      	text-shadow: none;
      	padding: 0;
      	margin: 0;
      	background-color: transparent;
      	display: inline-block;
      	vertical-align: middle;
      	border: none;
      	position: relative;
      	box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      	transition: all 0.25s ease;
      	border-radius: 50px!important;
      	line-height: 24px!important;
      	font-size: 18px;
      	background-color: #fff;
        -webkit-appearance: none;
        font-family: "Open Sans";
      	font-weight: 400;
      }

      #slide-out-widget-area .woocommerce-mini-cart .quantity input.plus,
      #slide-out-widget-area .woocommerce-mini-cart .quantity input.minus {
      	width: 30px;
      	height: 30px;
      }

      .cart .quantity input.plus:hover,
      .cart .quantity input.minus:hover,
      .woocommerce-mini-cart .quantity input.plus:hover,
      .woocommerce-mini-cart .quantity input.minus:hover {
      	box-shadow: 0 2px 12px rgba(0,0,0,0.25);
      }

      .cart .quantity input.plus:hover,
      .cart .quantity input.minus:hover,
      .woocommerce-mini-cart .quantity input.plus:hover,
      .woocommerce-mini-cart .quantity input.minus:hover {
      	color: #fff!important;
      }

      .cart .quantity input.qty,
      .woocommerce-mini-cart .quantity input.qty {
          border: none;
          margin: 0 10px;
          display: inline-block;
          height: 35px;
          line-height: 35px;
          margin: 0;
      		font-size: 20px;
      		font-family: "Open Sans";
      		font-weight: 700;
          padding: 0 5px;
          text-align: center;
          vertical-align: middle;
      		background-color: transparent;
      		background-image: none;
      		box-shadow: none;
          width: 46px;
          position: relative;
      }
      .cart .quantity input.qty,
      body[data-form-style="minimal"] .woocommerce-mini-cart .quantity input.qty {
        color: inherit;
      }

      .entry-summary .cart .quantity input.qty {
        color: '.esc_attr( $global_font_color ).';
      }

      @media only screen and ( max-width: 690px ) {
        .style_slide_in_click .woocommerce-mini-cart .quantity input.plus,
        .style_slide_in_click .woocommerce-mini-cart .quantity input.minus {
          height: 22px;
          width: 22px;
        }
      }

      @media only screen and (max-width: 770px) {
        .woocommerce .cart .quantity { width: auto!important; }
      }';

    }

    else if( 'grouped_together' === $qty_style ) {
      echo 'body[data-header-format] .cart .quantity input.plus,
      body[data-header-format] .cart .quantity input.minus,
      body[data-header-format] .woocommerce-mini-cart .quantity input.plus,
      body[data-header-format] .woocommerce-mini-cart .quantity input.minus {
        background-color: transparent!important;
        border-radius: 0!important;
        font-size: 18px!important;
        font-family: "Open Sans";
        color: inherit;
        font-weight: 400!important;
        line-height: 1!important;
        width: 33.3%;
      }

      body[data-header-format] .cart .quantity input.minus,
      body[data-header-format] .woocommerce-mini-cart .quantity input.minus {
        padding-left: 11%;
        padding-right: 0;
      }

      body[data-header-format] .cart .quantity input.plus,
      body[data-header-format] .woocommerce-mini-cart .quantity input.plus {
        padding-right: 11%;
        padding-left: 0;
      }

      .cart div.quantity,
      .woocommerce-mini-cart div.quantity {
        border: 1px solid rgba(0,0,0,0.4);
        border-radius: 5px;
        overflow: hidden;
        display: flex;
        flex-wrap: nowrap;
        width: 110px;
        transition: border-color 0.2s ease;
      }
      .cart div.quantity:hover,
      .woocommerce-mini-cart div.quantity:hover {
        border-color: rgba(0,0,0,1);
      }
      #slide-out-widget-area .woocommerce-mini-cart div.quantity {
        border: 1px solid rgba(255,255,255,0.4);
      }
      #slide-out-widget-area .woocommerce-mini-cart div.quantity:hover {
        border: 1px solid rgba(255,255,255,1);
      }
      .woocommerce div.product form.cart div.quantity.hidden {
        visibility: hidden;
      }
      .style_slide_in_click .woocommerce-mini-cart div.quantity {
        width: 100px;
      }
      body[data-button-style^="rounded"] .cart div.quantity,
      body[data-button-style^="rounded"] .woocommerce-mini-cart div.quantity {
        border-radius: 200px;
      }
      body[data-header-format] .cart div.quantity .qty,
      body .woocommerce-mini-cart div.quantity .qty {
        border: none;
        font-weight: 600;
        font-size: 16px!important;
        width: 33.3%;
        background-color: transparent;
        box-sizing: content-box;
        color: inherit;
        line-height: 1!important;
        color: inherit;
        padding: 1px 2px;
        box-shadow: none;
        text-align: center;
      }
      body[data-header-format] .woocommerce-mini-cart div.quantity .qty {
        background-color: transparent;
      }
      body #slide-out-widget-area .widget_shopping_cart div.quantity {
        display: flex;
      }
      @media only screen and (max-width: 768px) {
        .cart div.quantity,
        .woocommerce-mini-cart div.quantity {
          margin-left: auto;
        }
      }';

    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.4. WooCommerce Sidebar Toggles
  /*-------------------------------------------------------------------------*/
  $salient_woo_sidebar_toggles = true;

  if( has_filter('salient_woocommerce_sidebar_toggles') ) {
    $salient_woo_sidebar_toggles = apply_filters('salient_woocommerce_sidebar_toggles', $salient_woo_sidebar_toggles);
  }

  if( function_exists( 'is_woocommerce' ) && true === $salient_woo_sidebar_toggles) {

    echo '
    @media only screen and (min-width: 1000px) {
    	.woocommerce #sidebar .widget.woocommerce > ul,
    	.woocommerce #sidebar .widget.widget_product_tag_cloud > div,
      .woocommerce #sidebar .widget.woocommerce-widget-layered-nav > .woocommerce-widget-layered-nav-dropdown {
    		display: block!important;
    	}
    }

    @media only screen and (max-width: 999px) {
    	.woocommerce #sidebar > div,
    	.woocommerce #sidebar > div.widget,
      .woocommerce #sidebar .inner > div.widget {
    		margin-bottom: 0;
    	}
    	.woocommerce  #sidebar .widget.woocommerce {
    		margin-top: 8px;
    		position: relative;
    	}
    	.woocommerce  #sidebar .widget.woocommerce:not(.widget_price_filter) h4 {
    		margin-bottom: 0;
    	}
    	.woocommerce  #sidebar .widget.woocommerce:not(.widget_price_filter) h4 {
    		cursor: pointer;
    		line-height: 34px;
    		padding-left: 35px;
    		font-size: 14px;
    	}

    	.woocommerce  #sidebar .widget.woocommerce:not(.widget_price_filter) h4:before {
    	    content: " ";
    	    top: 10px;
    	    left: 14px;
    	    width: 2px;
    	    margin-left: -2px;
    	    height: 14px;
    	    position: absolute;
    	    background-color: #888;
    	    -ms-transition: transform 0.45s cubic-bezier(.3,.4,.2,1), background-color 0.15s ease;
    	    transition: transform 0.45s cubic-bezier(.3,.4,.2,1), background-color 0.15s ease;
    	    -webkit-transition: -webkit-transform 0.45s cubic-bezier(.3,.4,.2,1), background-color 0.15s ease;
    	}
    	.woocommerce #sidebar .widget.woocommerce:not(.widget_price_filter) h4:after {
    	    content: " ";
    	    top: 18px;
    	    left: 6px;
    	    margin-top: -2px;
    	    width: 14px;
    	    height: 2px;
    	    position: absolute;
    	    background-color: #888;
    	    -ms-transition: background-color 0.15s ease;
    	    transition: background-color 0.15s ease;
    	    -webkit-transition: background-color 0.15s ease;
    	}

    	.woocommerce #sidebar .widget.woocommerce:not(.widget_price_filter).open-filter h4:before {
    		transform: scaleY(0);
    		-webkit-transform: scaleY(0);
    	}

    	.woocommerce #sidebar .widget.woocommerce:not(.no-widget-title) > ul,
    	.woocommerce #sidebar .widget.widget_product_tag_cloud > div,
      .woocommerce #sidebar .widget.woocommerce-widget-layered-nav > .woocommerce-widget-layered-nav-dropdown {
    		display: none;
    		padding-left: 35px;
    	}

    }';


  }

  /*-------------------------------------------------------------------------*/
  /* 18.5. WooCommerce Single Gallery Variants
  /*-------------------------------------------------------------------------*/

  $product_gallery_style = (!empty($nectar_options['single_product_gallery_type'])) ? $nectar_options['single_product_gallery_type'] : 'default';

  if( function_exists( 'is_woocommerce' ) ) {

    if( 'left_thumb_sticky' === $product_gallery_style ) {
      // Match right side product info padding based on container ext left/right padding
      if( intval($ext_padding) < 70 ) {
        echo '@media only screen and (min-width: 1000px) {
          .single-product .product[data-gallery-style="left_thumb_sticky"] .summary.entry-summary {
            padding-right: '. (70 - intval($ext_padding)) .'px;
          }
        }';
      }

    }

    if( 'left_thumb_sticky_fullwidth' === $product_gallery_style ) {

      echo 'body[data-header-resize].single-product .container-wrap {
        padding-top: 0;
      }
      .product-thumbs-wrap {
        display: flex;
        max-height: 60vh;
        height: 60vh;
        align-items: center;
      }
      .single-product .product-thumbs {
        margin: 0;
        width: 100%;
      }
      .single-product .single-product-wrap {
        position: relative;
      }

      .single-product .product .summary.entry-summary {
        padding: 6% 6% 0 6%;
      }
      .single-product .product[data-gallery-style="left_thumb_sticky"] .single-product-main-image {
        padding: 6% 0 0 30px;
      }

      .single-product .product[data-gallery-style="left_thumb_sticky"] .images .slider > div:last-child {
        margin-bottom: 0;
      }
      @media only screen and (min-width: 1000px) {
        .single-product .product[data-gallery-style="left_thumb_sticky"] div.images {
          padding-right: 30px;
        }
        .single-product .row > .product[data-gallery-style="left_thumb_sticky"] .single-product-main-image {
            width: 60%;
        }
        .single-product .row > .product[data-gallery-style="left_thumb_sticky"][data-tab-pos*="fullwidth"] .summary.entry-summary {
          width: 40%;
        }
      }
      ';

    }

    /*left aligned thumbs fullwidth */
    if( 'left_thumb_slider' === $product_gallery_style ) {

      echo '

     .single-product .product-thumbs .thumb-inner {
       line-height: 0;
     }

      .single-product .images .product-slider {
        position: relative;
      }

      .single-product-main-image {
        flex: 1;
        display: flex;
        flex-direction: row-reverse;
      }

      .woocommerce div.product .product-slider.woocommerce-product-gallery > .woocommerce-product-gallery__trigger {
        display: none;
      }

      .woocommerce div.product div.images .zoomImg {
        pointer-events: none;
      }

      .woocommerce div.product div.images .swiper-slide .woocommerce-product-gallery__trigger {
        position: relative;
        top: 0;
        right: 0;
        width: auto;
        height: auto;
        background: transparent;
        text-indent: 0;
        border-radius: 0;
        display: block;
        box-shadow: none;
        transition: none;
        z-index: auto;
      }
      .woocommerce div.product div.images .is-moving .slide .woocommerce-product-gallery__trigger {
        pointer-events: none;
      }
      .woocommerce div.product div.images .swiper-slide .woocommerce-product-gallery__trigger:before,
      .woocommerce div.product div.images .swiper-slide .woocommerce-product-gallery__trigger:after {
        display: none;
      }

      .swiper-container.product-slider:hover .button-next,
      .swiper-container.product-slider:hover .button-prev {
        opacity: 1;
      }

      .swiper-container.product-slider .button-next,
      .swiper-container.product-slider .button-prev {
        position: absolute;
        top: 50%;
        z-index: 100;
        width: 20px;
        text-align: center;
        height: 20px;
        opacity: 0;
        transition: opacity 0.2s ease;
      }

      .swiper-container.product-slider .button-next {
        right: 20px;
      }

      .swiper-container.product-slider .button-prev {
        left: 20px;
      }




      @media only screen and (min-width: 1000px) {

        .product[data-tab-pos="fullwidth"] .summary.entry-summary {
          width: 40%;
          padding-left: 5%;
        }

        .woocommerce.single-product .single-product-wrap {
          display: flex;
          position: relative;
          align-items: center;
          min-height: calc( 100vh - '.esc_attr($header_space).'px - 80px)!important;
        }

        .admin-bar.woocommerce.single-product .single-product-wrap {
          min-height: calc( 100vh - '.esc_attr($header_space).'px - 110px)!important;
        }

        .woocommerce div.product .single-product-wrap div.images:not([data-has-gallery-imgs="false"]) {
          width: calc(100% - 130px)!important;
        }

        /*
        .single-product .product-thumbs .swiper-wrapper > div {
          height: 0;
          opacity: 0;
          margin-bottom: 0!important;
          padding-bottom: 0px;
          transition: opacity 0.2s ease;
        }
        .single-product .product-thumbs .swiper-wrapper > div:nth-child(1),
        .single-product .product-thumbs .swiper-wrapper > div:nth-child(2),
        .single-product .product-thumbs .swiper-wrapper > div:nth-child(3) {
          opacity: 1;
          height: auto;
          padding-bottom: 10px;
        } */

        .single-product .product-thumbs .thumb,
        .single-product .product-thumbs {
          width: 100px;
        }

        .single-product .product-thumbs {
          margin-left: 0;
          justify-content: center;
          display:flex;
          height: auto;
          max-height: 440px;
        }
        .single-product .product-thumbs .swiper-wrapper{
            height: auto;
            margin: auto 0;
        }
        .single-product .product-thumbs .thumb {
          height: auto;
          margin: 0 0 10px;
        }

        .single-product .product-thumbs .thumb {
          border: 1px solid rgba(0,0,0,0);
          padding: 2px;
          transition: border-color 0.2s ease;
        }
        .single-product .product-thumbs .thumb.swiper-slide-thumb-active,
        .single-product .product-thumbs .thumb:hover {
          border: 1px solid rgba(0,0,0,1);
        }

        .single-product .product-thumbs-wrap {
          margin-right: 30px;
          align-items: center;
          justify-content: center;
          flex-direction: column;
          display:flex;
        }
        .single-product .product-thumbs-wrap .button-next,
        .single-product .product-thumbs-wrap .button-prev {
          text-align: center;
          height: 20px;
          pointer-events: none;
          opacity: 0;
          line-height: 20px;
        }
        .single-product .product-thumbs-wrap.visible .button-next,
        .single-product .product-thumbs-wrap.visible .button-prev {
          pointer-events: all;
          opacity: 1;
        }
        .single-product .product-thumbs-wrap .button-next {
          margin-bottom: auto;
        }
        .single-product .product-thumbs-wrap .button-prev {
          margin-top: auto;
        }


      }

      @media only screen and (max-width: 999px) {
        .single-product-main-image {
            flex-direction: column;
            margin-right: 0;
        }
        .single-product .product-thumbs .thumb {
          width: calc(20% - 10px);
          margin: 0 5px;
        }
        .single-product .product-thumbs {
          width: calc(100% + 10px);
          margin-left: -5px;
        }
      }

      ';

    }


  }


  /*-------------------------------------------------------------------------*/
  /* 18.6. WooCommerce Single Gallery Width
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {

    $use_custom_gallery_width = ( isset($nectar_options['single_product_gallery_custom_width']) && '1' === $nectar_options['single_product_gallery_custom_width'] ) ? true : false;
    $single_gallery_width     = ( isset($nectar_options['single_product_gallery_width']) ) ? $nectar_options['single_product_gallery_width'] : false;
    $product_style            = ( ! empty( $nectar_options['single_product_gallery_type'] ) ) ? $nectar_options['single_product_gallery_type'] : 'ios_slider';

    // Modern styles get a default.
    if( in_array($product_style, array('two_column_images')) && false === $use_custom_gallery_width) {
      $use_custom_gallery_width = true;
      $single_gallery_width = '65';
    }

    if( $use_custom_gallery_width && $single_gallery_width ) {

      echo '@media only screen and (min-width: 1000px) {';

      echo '.single-product .nectar-prod-wrap {
        display: flex;
      }';

      // Left Thumb Sticky Style.
      if( 'left_thumb_sticky' === $product_gallery_style ) {
        echo '
        .single-product .row > .product[data-gallery-style="left_thumb_sticky"][data-tab-pos="in_sidebar"] .single-product-summary,
        .single-product .row > .product[data-gallery-style="left_thumb_sticky"][data-tab-pos*="fullwidth"] .summary.entry-summary,
        .single-product .product[data-gallery-style="left_thumb_sticky"][data-tab-pos*="fullwidth"] .summary.entry-summary {
          flex: 1;
          min-width: 340px;
          width: auto;
        }
        .single-product .row > .product[data-gallery-style="left_thumb_sticky"] .single-product-main-image,
        .single-product .product[data-gallery-style="left_thumb_sticky"] .single-product-main-image {
          width: '.intval( $single_gallery_width ).'%;
        }';
        if( is_rtl() ) {
          echo '.single-product .nectar-prod-wrap {
            flex-direction: row-reverse;
          }';
        }
      }
      else {
        echo '
        .single-product .row > .product[data-gallery-style][data-tab-pos] .single-product-summary,
        .single-product .row > .product[data-gallery-style][data-tab-pos] .summary.entry-summary,
        .single-product .product[data-tab-pos] .summary.entry-summary {
          flex: 1;
          min-width: 340px;
          width: auto;
        }
        .single-product .row > .product[data-gallery-style] .single-product-main-image,
        .single-product .product[data-gallery-style] .single-product-main-image {
          width: '.intval( $single_gallery_width ).'%;
          margin-right: 5%;
        }
        .woocommerce-tabs[data-tab-style="fullwidth"] #reviews #comments {
          margin-right: 5%;
        }
        .woocommerce-tabs[data-tab-style="fullwidth"] #reviews #comments,
        .woocommerce-tabs[data-tab-style="fullwidth"] #reviews #review_form_wrapper {
          width: 47.5%;
        }
        ';

        if( is_rtl() ) {
            echo '.single-product .row > .product[data-gallery-style] .single-product-main-image,
            .single-product .product[data-gallery-style] .single-product-main-image {
                margin-left: 5%;
                margin-right: 0;
            }';
        }

        if( intval( $single_gallery_width ) > 45 && intval( $single_gallery_width ) < 60 ) {
          echo '.woocommerce div.product div.images .flex-control-thumbs li {
            width: 20%;
            clear: none!important;
          }';
        } else if( intval( $single_gallery_width ) >= 60 ) {
          echo '.woocommerce div.product div.images .flex-control-thumbs li,
          .single-product .product-thumbs .flickity-slider .thumb,
          .single-product [data-gallery-style="ios_slider"] .slider > .thumb {
            width: 16.66%;
            clear: none!important;
          }';
        }

      }

      echo '}'; // End media query.

    } // Using custom width.

  }

  /*-------------------------------------------------------------------------*/
  /* 18.7. WooCommerce Add to Cart Style
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {

    $product_add_to_cart_style = ( isset($nectar_options['product_add_to_cart_style']) ) ? $nectar_options['product_add_to_cart_style'] : 'default';

    if( 'fullwidth' === $product_add_to_cart_style ) {

      echo '.nectar-prod-wrap .single_add_to_cart_button {
        width: 100%;
      }';

    }
    else if( 'fullwidth_qty' === $product_add_to_cart_style ) {

      if( 'default' === $qty_style ) {
          echo '.woocommerce div.product .nectar-prod-wrap form.cart div.quantity {
            display: flex;
            margin-right: 30px;
            align-items: center;
          }';
      }

      echo '
      .nectar-prod-wrap .cart .flex-break {
        flex-basis: 100%;
        height: 0;
      }

      .woocommerce-page .nectar-prod-wrap button.single_add_to_cart_button {
        margin-top: 0;
        flex: 1;
      }
      .nectar-prod-wrap .woocommerce-variation-add-to-cart,
      .woocommerce div.product .nectar-prod-wrap form.cart:not(.variations_form):not(.grouped_form),
      .woocommerce div.product .nectar-prod-wrap form.cart.cart_group.bundle_form .cart.bundle_data .bundle_button {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
      }
      .nectar-prod-wrap form.cart.grouped_form .single_add_to_cart_button,
      .woocommerce div.product .nectar-prod-wrap form.cart.cart_group.bundle_form .cart.bundle_data {
        width: 100%;
      }
      .woocommerce-page button[type="submit"].single_add_to_cart_button,
      body[data-form-submit="regular"].woocommerce-page .container-wrap button[type=submit].single_add_to_cart_button {
        padding-left: 10px!important;
        padding-right: 10px!important;
      }
      .single-product[data-header-format="left-header"] div.product .cart div.quantity,
      .single-product[data-header-format="left-header"] .container-wrap button[type=submit].single_add_to_cart_button {
        margin-top: 10px;
      }
      .woocommerce div.product .nectar-prod-wrap .cart div.quantity.hidden {
        width: 0px;
        margin: 0;
      }';

    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.8. WooCommerce Filters
  /*-------------------------------------------------------------------------*/

  if( function_exists( 'is_woocommerce' ) ) {

    // Filter Sidebar Toggle
    if( true === NectarThemeManager::$woo_product_filters ) {
      echo '
      .nectar-shop-filters .nectar-shop-filter-trigger {
        background-color: rgba(0,0,0,0.045);
        transition: background-color 0.2s ease;
        padding: 10px 15px;
        display: flex;
        justify-content: center;
        color: inherit;
        line-height: 1.2;
        margin-right: 10px;
        min-width: 155px;
      }
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection--single,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select {
        background-color: rgba(0,0,0,0.045)!important;
        transition: background-color 0.2s ease;
        padding: 10px 35px 10px 20px;
        border: none;
      }
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection--single,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container {
          font-size: inherit;
      }
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select {
        line-height: 1.2;
        font-size: inherit!important;
        width: 160px;
        opacity: 1;
        -webkit-appearance: none;
        appearance: none;
        position: relative;
      }

      .nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection--single .select2-selection__arrow {
        right: 17px;
      }
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection__rendered {
        line-height: 1.2;
        font-size: inherit!important;
        padding: 0;
      }
      .nectar-shop-filters .nectar-shop-filter-trigger:hover {
        background-color: rgba(0,0,0,0.065);
      }
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container:hover .select2-selection--single,
      body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container--open .select2-selection--single {
        background-color: rgba(0,0,0,0.065)!important;
      }
      body #ajax-content-wrap .nectar-shop-header-bottom .widget_layered_nav_filters ul li a {
        line-height: 1.2;
        font-size: inherit!important;
      }
      .nectar-shop-filters .nectar-shop-filter-trigger .text-wrap {
      	display: flex;
        line-height: 20px;
      }

      .nectar-shop-filters .nectar-shop-filter-trigger .text-wrap .dynamic {
        margin-right: 5px;
        height: 20px;
        line-height: 20px;
      }
      ';

      // Icon.
      echo '
      .nectar-shop-filter-trigger .toggle-icon {
        display: block;
        border-top: 2px solid '.esc_html($global_font_color).';
        width: 20px;
        height: 9px;
        margin-right: 10px;
        border-bottom: 2px solid '.esc_html($global_font_color).';
        position: relative;
      }
      .nectar-shop-filter-trigger .toggle-icon .top-line,
      .nectar-shop-filter-trigger .toggle-icon .bottom-line {
        border: 2px solid '.esc_html($global_font_color).';
        border-radius: 50px;
        background-color: #fff;
        position: absolute;
        height: 6px;
        width: 6px;
        transition: transform 0.3s ease;
        display: block;
        content: "";
      }
      .nectar-shop-filter-trigger .toggle-icon .top-line {
        top: -4px;
        left: 3px;
      }
      .nectar-shop-filter-trigger .toggle-icon .bottom-line {
        bottom: -4px;
        right: 3px;
      }
      ';

      // Structure.
      echo '
      .nectar-shop-header h1.page-title {
        margin-bottom: 0;
      }

      .nectar-shop-header .nectar-shop-header-bottom {
        display: flex;
        align-items: center;
        margin-bottom: 40px;
      }
      .full-width-content .nectar-shop-header .nectar-shop-header-bottom {
        padding: 0 2%;
      }

      .nectar-shop-header-bottom .left-side {
        max-width: 65%;
      }
      .nectar-shop-header .nectar-shop-header-bottom .right-side {
        margin-left: auto;
        display: flex;
        align-items: center;
      }

      body .nectar-shop-header .nectar-shop-header-bottom .woocommerce-result-count {
        margin-right: 20px;
      }
      body .full-width-content .nectar-shop-header .nectar-shop-header-bottom .woocommerce-result-count  {
        padding-left: 0;
      }


      body.woocommerce .nectar-shop-header .nectar-shop-header-bottom .woocommerce-ordering {
        margin-left: 0;
      }
       body.woocommerce .nectar-shop-header .nectar-shop-header-bottom .woocommerce-ordering,
       body.woocommerce .nectar-shop-header .nectar-shop-header-bottom .woocommerce-result-count {
         position: relative;
         bottom: 0;
         right: 0;
         margin-bottom: 0;
         margin-top: 0;
         padding-bottom: 0;
         float: none;
       }

      @media only screen and (min-width: 1000px) {
         .archive.woocommerce .container-wrap > .main-content > .row {
           display: -webkit-flex;
           display: flex;
         }

         .archive.woocommerce .container-wrap > .main-content #sidebar .inner {
           transition: transform 0.4s ease;
         }

         body.woocommerce #sidebar .inner > .nectar-active-product-filters {
           display: none;
         }

         .woocommerce .woocommerce-breadcrumb {
           padding: 0;
         }
         .archive.woocommerce .container-wrap > .main-content #sidebar {
           overflow: hidden;
           transition: margin 0.4s ease, transform 0.4s ease, opacity 0.4s ease;
           margin: 0;
           padding-right: 4%;
           width: 25%;
         }
         .archive.woocommerce .container-wrap > .main-content #sidebar:not(.col_last) {
           transition: margin 0.4s ease, transform 0.4s ease, opacity 0.2s ease;
         }
         .archive.woocommerce .container-wrap > .main-content #sidebar.col_last {
           padding: 0 0 0 4%;
           z-index: 1;
         }
         .archive.woocommerce .container-wrap > .main-content > .row .post-area.span_9 {
          z-index: 10;
         }
         
         .archive.woocommerce .container-wrap > .main-content > .row .post-area.span_9 {
            flex: 1;
            margin-right: 0;
            padding-left: 0;
            padding-right: 0;
         }
         .archive.woocommerce .container-wrap > .main-content #sidebar > .header,
         .archive.woocommerce .container-wrap > .main-content #sidebar .theiaStickySidebar > .header {
           display: none;
         }

      }

      @media only screen and (max-width: 999px) {

        .nectar-shop-filters .nectar-shop-filter-trigger .dynamic .hide {
          display: none!important;
        }
        .nectar-shop-filters .nectar-shop-filter-trigger .dynamic .show,
        body.woocommerce #sidebar .inner > .nectar-active-product-filters ul {
          display: block!important;
        }
        body.woocommerce #sidebar .nectar-active-product-filters ul {
          padding-left: 0!important;
        }
        .nectar-shop-filters .nectar-shop-filter-trigger {
            min-width: 120px;
            width: 100%;
        }
        .nectar-shop-filters {
          flex-direction: column;
        }

        .archive.woocommerce .container-wrap > .main-content #sidebar {
          position: fixed!important;
          display: flex;
          justify-content: center;
          align-items: flex-start;
          top: 0;
          width: 100%;
          z-index: 20000;
          background-color: '. esc_html($global_bg_color).';
          padding: 100px 40px 60px 40px;
          overflow-y: auto!important;
          height: 100vh;
          max-height: 100vh;
          flex-wrap: wrap;
          transform: none;
          opacity: 0!important;
          pointer-events: none;
          left: -9999px;
          margin-right: 0!important;
          transition: opacity 0.5s ease;
        }
        .admin-bar.archive.woocommerce .container-wrap > .main-content #sidebar {
          padding-top: 130px;
        }
        .archive.woocommerce .main-content #sidebar .widget.woocommerce {
          margin-bottom: 15px;
        }

        .archive.woocommerce .container-wrap > .main-content #sidebar .inner,
        .archive.woocommerce .container-wrap > .main-content #sidebar .theiaStickySidebar {
          min-width: 100%;
          transform: none!important;
        }

        .archive.woocommerce .container-wrap > .main-content #sidebar.open {
          opacity: 1!important;
          left: 0;
          pointer-events: all;
        }

        .archive.woocommerce .container-wrap > .main-content #sidebar .nectar-close-btn .close-line {
          background-color: '. esc_html($global_font_color).';
        }

        .archive.woocommerce .container-wrap > .main-content #sidebar > .header h4,
        .archive.woocommerce .container-wrap > .main-content #sidebar .theiaStickySidebar > .header h4 {
          margin-bottom: 0;
        }
        .archive.woocommerce .container-wrap > .main-content #sidebar > .header,
        .archive.woocommerce .container-wrap > .main-content #sidebar .theiaStickySidebar > .header {
          align-items: center;
          width: 100%;
          display: flex;
          position: fixed;
          z-index: 100;
          top: 0;
          left: 0;
          margin-bottom: 0;
          padding: 20px 40px;
          background-color: '. esc_html($global_bg_color).';
          border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .admin-bar.archive.woocommerce .container-wrap > .main-content #sidebar > .header,
        .admin-bar.archive.woocommerce .container-wrap > .main-content #sidebar .theiaStickySidebar > .header {
          top: 32px;
        }

        .archive.woocommerce #sidebar .nectar-close-btn-wrap {
          margin-left: auto;
        }

        .nectar-shop-header .nectar-shop-header-bottom {
          align-items: flex-start;
          font-size: 14px;
          margin-bottom: 60px;
        }
        .nectar-shop-header .woocommerce-breadcrumb {
          font-size: 14px;
          padding: 0;
          margin-bottom: 25px;
        }
        .nectar-shop-header .nectar-active-product-filters {
          display: none;
        }
        body.woocommerce #sidebar .nectar-active-product-filters {
          margin-bottom: 40px;
        }
        body.woocommerce .nectar-shop-header .nectar-shop-header-bottom .woocommerce-result-count {
          padding-top: 10px;
          line-height: 1.2;
          margin-right: 0;
          position: absolute;
          left: 0;
          bottom: -30px;
        }
        body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select,
        body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection__rendered {
          line-height: 1.4;
        }
        .nectar-shop-header .nectar-shop-header-bottom .left-side,
        .nectar-shop-header .nectar-shop-header-bottom .right-side {
          width: 50%;
        }

        body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering,
        body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select,
        body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering .select2-container {
            width: 100%!important;
            text-align: center;
            text-align-last:center;
        }

        .woocommerce.archive .select2-dropdown {
          z-index: 10051;
        }

      }

      @media only screen and (max-width: 690px) {
        .nectar-shop-filters .nectar-shop-filter-trigger .dynamic .show {
          display: none!important;
        }
        .nectar-shop-header .nectar-shop-header-bottom .left-side {
            width: 40%;
        }
        .nectar-shop-header .nectar-shop-header-bottom .right-side {
            width: 60%;
        }
        .archive.woocommerce .container-wrap > .main-content #sidebar {
          padding-right: 20px;
          padding-left: 20px;
        }
      }



       ';
    }
    // Filter area not active.
    else {
      echo 'body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default .select2-selection--single,
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default .select2-selection--single:hover,
      body[data-fancy-form-rcs="1"] .woocommerce-ordering .select2-container--default.select2-container--open .select2-selection--single,
      body[data-fancy-form-rcs="1"] .woocommerce-ordering select {
      	background-color: transparent!important;
      	border: none!important;
      }';
    }


    // Display Active Filters Next To Toggle
    if( isset($nectar_options['product_show_filters']) && '1' === $nectar_options['product_show_filters']) {
      echo '.nectar-active-product-filters {
        line-height: 1;
      }
      .woocommerce .nectar-shop-filters .nectar-active-product-filters ul {
        margin-top: -10px;
      }
      .nectar-active-product-filters h2 {
        display: none;
      }
      .nectar-active-product-filters .widget_layered_nav_filters ul li {
        margin-top: 10px;
      }';
    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.9. WooCommerce Archive Header
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {

    $product_archive_header_size    = ( isset($nectar_options['product_archive_header_size'] ) ) ? $nectar_options['product_archive_header_size'] : 'default';
    $product_archive_border_radius  = ( isset($nectar_options['product_archive_header_br'] ) ) ? $nectar_options['product_archive_header_br'] : '0';
    $product_archive_auto_height    = ( isset($nectar_options['product_archive_header_auto_height'] ) ) ? $nectar_options['product_archive_header_auto_height'] : '0';
    $product_archive_max_text_width = ( isset($nectar_options['product_archive_header_text_width'] ) ) ? intval($nectar_options['product_archive_header_text_width']) : 900;

    echo '
    .woocommerce.archive #page-header-wrap + .container-wrap {
      padding-top: 20px;
    }
    .woocommerce.archive #page-header-wrap + .container-wrap .woocommerce-breadcrumb {
      margin-bottom: 20px;
    }';

    // Contained Header
    if( 'contained' === $product_archive_header_size ) {

      echo '
      .woocommerce.archive #ajax-content-wrap .container-wrap {
        padding-top: ' . ((intval($ext_padding) < 70) ? intval($ext_padding) . 'px' : '50px' ) . ';
      }
      .woocommerce.archive .container-wrap .nectar-shop-header .woocommerce-breadcrumb {
        margin-bottom: 20px;
      }
      body #page-header-wrap.woo-archive-header.container {
        width: 100%;
        margin-bottom: 20px;
      }
      body #page-header-wrap.woo-archive-header.container .span_6 {
        padding: 0 6%;
      }
      body #page-header-wrap.woo-archive-header.container.align-text-center .span_6 {
        padding: 0 8%;
      }';

      if( '1' === $boxed_layout ) {
        echo '@media only screen and (min-width: 1000px) {
          body #page-header-wrap.woo-archive-header.container {
            width: 92%;
          }
      }';
      }

    }

    // Max Content Width
    echo '.woocommerce.archive #page-header-wrap .inner-wrap {
      max-width: '.intval($product_archive_max_text_width).'px;
    }
    .woocommerce.archive #page-header-bg[data-alignment="center"] .inner-wrap {
      margin: 0 auto;
    }
    .woocommerce.archive #page-header-bg[data-alignment="right"] .span_6 {
      display: flex;
      justify-content: flex-end;
    }';

    // Auto Height
    if( '1' === $product_archive_auto_height ) {
      echo '.woocommerce.archive #page-header-wrap, .woocommerce.archive #page-header-bg {
        height: auto!important;
      }

      .woocommerce.archive #page-header-bg[data-bg-pos="top"] .page-header-bg-image {
        background-position: center;
      }

      .woocommerce.archive #page-header-bg {
        padding: 8% 0;
      }
      .woocommerce.archive #page-header-bg .span_6 {
        -webkit-transform: none;
        transform: none;
        top: 0;
      }

      #page-header-wrap.align-content-right .span_6 {
        display: flex;
        justify-content: flex-end;
      }
      #page-header-wrap.align-content-center .span_6 {
        display: flex;
        justify-content: center;
      }
      #page-header-wrap.align-content-left .span_6 {
        display: flex;
        justify-content: flex-start;
      }


      #page-header-wrap.align-text-right .span_6 {
        text-align: right;
      }
      #page-header-wrap.align-text-center .span_6 {
        text-align: center;
      }
      #page-header-wrap.align-text-left .span_6 {
        text-align: left;
      }
      ';

    }

    // Border Radius
    if( '0' !== $product_archive_border_radius ) {
      echo '#page-header-wrap.woo-archive-header.container #page-header-bg {
        border-radius: '.intval($product_archive_border_radius).'px;
      }';
    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.10. WooCommerce Product Styles
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {
    $product_style = ( ! empty( $nectar_options['single_product_gallery_type'] ) ) ? $nectar_options['single_product_gallery_type'] : 'ios_slider';

    if( 'two_column_images' === $product_style ) {
      echo '
      .single-product-main-image.col {
        float: none;
      }
      .woocommerce.single-product div.product div.summary {
        margin-bottom: 0;
        float: none;
      }
      .woocommerce.single-product [data-tab-pos*="full"] div.product_meta:last-child {
        margin-bottom: 3px;
      }
      .woocommerce div.product .single-product-main-image div.images .woocommerce-product-gallery__wrapper {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -3px;
      }
      .woocommerce.single-product div.product div.images .woocommerce-product-gallery__image img {
        margin-bottom: 0;
      }
      .woocommerce.single-product div.product .single-product-main-image div.images .woocommerce-product-gallery__image a {
        display: block;
        padding: 3px;
        cursor: zoom-in;
      }
      .woocommerce.single-product div.product .single-product-main-image div.images .woocommerce-product-gallery__image {
        width: 50%;
      }
      .woocommerce.single-product div.product div.woocommerce-product-gallery-with-single-image.images .woocommerce-product-gallery__image {
        width: 100%;
      }

      @media only screen and (max-width: 999px) {
        .woocommerce div.product .single-product-main-image div.images .woocommerce-product-gallery__wrapper {
          overflow-x: auto;
          -webkit-overflow-scrolling: touch;
          flex-wrap: nowrap;
        }
        .woocommerce div.product .single-product-main-image div.images .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image {
          flex: 0 0 auto;
          width: 66.6%;
        }
        .woocommerce div.product .single-product-main-image .images.woocommerce-product-gallery-with-single-image  .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image {
          width: 100%;
        }
        .single-product-main-image.col {
          margin-bottom: 35px;
          margin-left: -50vw;
          margin-left: calc(-50vw + var(--scroll-bar-w)/2);
          left: 50%;
        	width: 100vw;
          width: calc(100vw - var(--scroll-bar-w));
        }
      }

      @media only screen and (max-width: 999px) and (min-width: 691px) {
        #boxed .single-product-main-image.col {
          margin-left: -7%;
          width: 114%;
          left: 0;
        }
      }

      @media only screen and (max-width: 690px) {
        .woocommerce div.product .single-product-main-image div.images .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image {
          width: 75%;
        }
      }';
    }

  }


  /*-------------------------------------------------------------------------*/
  /* 18.11. WooCommerce Product Style Mods
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {

    $product_border_radius = ( isset( $nectar_options['product_border_radius'] ) ) ? esc_html($nectar_options['product_border_radius']) : false;
    $product_style         = ( ! empty( $nectar_options['product_style'] ) ) ? $nectar_options['product_style'] : 'classic';

    // Minimal
    if( 'minimal' === $product_style ) {

      // Border Radius
      if( $product_border_radius && $product_border_radius !== 'default' ) {
        echo '.products li.product.minimal .background-color-expand,
              .products li.product.minimal .background-color-expand:before,
              .products li.product.minimal .product-wrap,
              #search-outer .products li.product.minimal,
              .products li.product.minimal .product-image-wrap,
              body .widget_shopping_cart ul.product_list_widget li img,
              body.material .widget_shopping_cart ul.product_list_widget li img,
              body .nectar-quick-view-box .inner-wrap {
           border-radius: '.$product_border_radius.';
        }
        li.product.minimal .product-wrap img,
        li.product.minimal:hover .product-wrap img,
        body.woocommerce #ajax-content-wrap ul.products li.minimal.product span.onsale,
        #ajax-content-wrap ul.products li.minimal.product span.onsale,
        .nectar-woo-flickity ul.products li.minimal.product span.onsale,
        .nectar-ajax-search-results ul.products li.minimal.product span.onsale {
          border-radius: '.$product_border_radius.';
        }
        body .products li.product.minimal .product-wrap .product-image-wrap .product-add-to-cart a:first-child {
          border-top-left-radius: '.$product_border_radius.';
          border-bottom-left-radius: '.$product_border_radius.';
        }
        body .products li.product.minimal .product-wrap .product-image-wrap .product-add-to-cart a:last-child {
          border-top-right-radius: '.$product_border_radius.';
          border-bottom-right-radius: '.$product_border_radius.';
        }
        ';
      }
      $product_minimal_border = ( isset( $nectar_options['product_minimal_border'] ) ) ? esc_html($nectar_options['product_minimal_border']) : '0';
      // Bordered Design.
      if( '1' === $product_minimal_border ) {
        echo '.products[data-product-style="minimal"] li.product.minimal {
          border: 1px solid transparent;
        }
        .woocommerce ul.products li.product.minimal .product-wrap {
          background-color: transparent!important;
        }
        .products[data-product-style="minimal"] li.product.minimal .background-color-expand {
          box-shadow: 0px 0px 0px 1px inset rgba(0,0,0,0.08);
          backface-visibility: hidden;
          -webkit-backface-visibility: hidden;
          transition: box-shadow 0.55s cubic-bezier(.2,.75,.5,1), transform 0.55s cubic-bezier(.2,.75,.5,1);
        }
        .products[data-product-style="minimal"] li.product.minimal:hover .background-color-expand {
          box-shadow: 0px 0px 0px 1px inset rgba(0,0,0,0);
        }
        .products[data-product-style="minimal"] li.product.minimal:hover .background-color-expand:before {
          top: 1px;
          left: 1px;
          width: calc(100% - 2px);
          height: calc(100% - 2px);
        }
        .woocommerce ul.products li.product:hover img.hover-gallery-image {
          opacity: 1;
        }

        ';
      }

      $product_minimal_hover_effect   = ( isset( $nectar_options['product_minimal_hover_effect'] ) ) ? esc_html($nectar_options['product_minimal_hover_effect']) : 'default';
      $product_minimal_text_alignment = ( isset( $nectar_options['product_minimal_text_alignment'] ) ) ? esc_html($nectar_options['product_minimal_text_alignment']) : 'default';

      if( 'right' === $product_minimal_text_alignment ) {
        echo '.products li.product.minimal .product-meta {
            text-align: right;
        }
        .products li.product.minimal .price-hover-wrap {
          width: 100%;
        }';
      }
      if( 'center' === $product_minimal_text_alignment ) {
        echo '
        .products li.product.minimal .product-meta h2 {
          display: inline-block;
        }
        .products li.product.minimal .product-meta {
            text-align: center;
        }
        .products li.product.minimal .price-hover-wrap {
          width: 100%;
        }';
      }

      // Minimal Product Hover Effect.

      //// Default shadow.
      if( 'default' === $product_minimal_hover_effect ) {

        if( 'right' === $product_minimal_text_alignment ) {
          echo '
          .products li.product.minimal.hover-bound:hover .product-meta {
            transform: translateY(6px) translateX(6px);
          }';
        }
        if( 'center' === $product_minimal_text_alignment ) {
          echo '
          .products li.product.minimal.hover-bound:hover .product-meta {
              transform: translateY(6px);
          }';
        }
        if( 'left' === $product_minimal_text_alignment ||
            'default' === $product_minimal_text_alignment ) {
              echo '
              .products li.product.minimal.hover-bound:hover .product-meta {
                transform: translateY(6px) translateX(-6px);
              }';
        }

      }

      //// Image Zoom.
      else if( 'image_zoom' === $product_minimal_hover_effect ) {
        echo 'body .products li.product.minimal.hover-bound:hover .product-meta {
          transform: none;
        }
        .products li.product.minimal .background-color-expand {
          display: none;
        }
        .single-product .product[data-n-lazy="1"] .minimal img.nectar-lazy,
        ul.products li.product.minimal img {
          transition: border-color 0.1s ease, opacity 0.5s ease, transform 0.4s cubic-bezier(.2,.75,.5,1);
        }
        .woocommerce ul.products li.product.minimal .product-wrap img.hover-gallery-image {
          transition: transform 0.4s cubic-bezier(.2,.75,.5,1), opacity 0.4s cubic-bezier(.2,.75,.5,1);
        }

        .products li.product.minimal .product-image-wrap {
          overflow: hidden;
        }
        ul.products li.product.minimal.product-image-wrap:not(.has-hover-image) img {
          transform: translateZ(0);
        }
        ul.products li.product.minimal:hover .product-image-wrap:not(.has-hover-image) img {
          transform: scale(1.04) translateZ(0);
        }
        ul.products li.product.minimal .product-image-wrap.has-hover-image .hover-gallery-image {
          transform: scale(1) translateZ(0);
        }
        ul.products li.product.minimal:hover .product-image-wrap.has-hover-image .hover-gallery-image {
          transform: scale(1.04) translateZ(0);
        }';
      }

      $product_minimal_bg_color   = isset($nectar_options['product_minimal_bg_color']) ? $nectar_options['product_minimal_bg_color'] : '';
      $product_minimal_shop_color = isset($nectar_options['product_archive_bg_color']) ? $nectar_options['product_archive_bg_color'] : '';

      if( empty($product_minimal_shop_color) ) {
        $product_minimal_shop_color = $global_bg_color;
      }


      // Minimal Product Hover Layout.
      $product_minimal_hover_layout = ( isset( $nectar_options['product_minimal_hover_layout'] ) ) ? esc_html($nectar_options['product_minimal_hover_layout']) : 'default';
      if( 'price_visible_flex_buttons' === $product_minimal_hover_layout ) {

        echo '
        body .woocommerce .nectar-woo-flickity .flickity-slider .flickity-cell {
          padding: 10px;
        }
        body .nectar-woo-flickity > ul.products[data-product-style] > li.product,
        body .full-width-content .nectar-woo-flickity > ul.products[data-product-style] > li.product {
          margin: 0 10px!important;
        }
        .wpb_row:not(.full-width-content) .woocommerce .nectar-woo-flickity,
        .related-upsell-carousel.nectar-woo-flickity {
          width: calc(100% + 20px);
          margin-left: -10px;
          padding: 10px 0;
        }
        .related-upsell-carousel.nectar-woo-flickity h2 {
        	margin-left: 10px;
          padding-right: 10px;
        }
        @media only screen and (max-width: 1600px) and (min-width: 1000px) {
          .nectar-woo-flickity > ul.products[data-product-style].columns-dynamic > li.product {
              width: calc(25% - 20px)!important;
          }
        }
        @media only screen and (min-width: 1600px) {
          .nectar-woo-flickity > ul.products[data-product-style].columns-dynamic > li.product {
              width: calc(20% - 20px)!important;
          }
        }
        @media only screen and (max-width: 999px) and (min-width: 691px) {
          .nectar-woo-flickity > ul.products[data-product-style].columns-dynamic > li.product {
              width: calc(33% - 20px)!important;
          }
        }
        @media only screen and (min-width: 1000px) {
          .nectar-woo-flickity > ul.products[data-product-style].columns-4 > li.product {
              width: calc(25% - 20px)!important;
          }
          .nectar-woo-flickity > ul.products[data-product-style].columns-3 > li.product {
              width: calc(33.3% - 20px)!important;
          }
        }
        @media only screen and (min-width: 691px) and (max-width: 999px){
          .nectar-woo-flickity > ul.products[data-product-style].columns-3 > li.product {
              width: calc(50% - 20px)!important;
          }
        }
        @media only screen and (min-width: 691px) {
          .nectar-woo-flickity > ul.products[data-product-style].columns-2 > li.product {
              width: calc(50% - 20px)!important;
          }
        }
        body .woocommerce .nectar-woo-flickity[data-controls="arrows-overlaid"]:not(.related-upsell-carousel) .nectar-woo-carousel-top {
        	top: calc(50% - 50px);
        }

        .products li.product.minimal:hover .product-meta .price {
            opacity: 1;
            width: 100%;
        }

        .products li.product.minimal .background-color-expand,
        .products li.product.minimal .product-meta > a h2,
        .products li.product.minimal .product-meta,
        .products li.product.minimal .product-meta .price,
        .products li.product.minimal .background-color-expand:before {
          transition: opacity 0.4s cubic-bezier(.2,.75,.5,1), transform 0.4s cubic-bezier(.2,.75,.5,1);
        }

        .nectar-quick-view-box.visible:before {
          transition: opacity 0.8s ease 0.65s;
        }

        .products li.product.minimal .background-color-expand:before {
          box-shadow: 0px 5px 75px -10px rgba(0,0,0,0.12)
        }
        .products li.product.minimal.no-trans .background-color-expand:before {
          box-shadow: none;
        }

        .products li.product.minimal .product-image-wrap .product-add-to-cart a {
          transition: opacity 0.4s cubic-bezier(.2,.75,.5,1), background-color 0.4s cubic-bezier(.2,.75,.5,1), background-size 0.55s cubic-bezier(.2,.75,.5,1);
        }

        .woocommerce ul.products li.product .product-wrap img.hover-gallery-image,
        .woocommerce ul.products li.product .product-wrap picture.hover-gallery-image {
          transition: opacity 0.4s cubic-bezier(.2,.75,.5,1);
        }

        .products li.product.minimal .product-image-wrap {
          position: relative;
        }
        ul.products li.minimal.product .product-wrap {
          height: 100%;
        }
        .products li.product.minimal .product-image-wrap .product-add-to-cart {
          display: flex;
          -webkit-transform: none;
          transform: none;
          position: absolute;
          bottom: 0;
          left: 0;
          z-index: 10;
          top: auto;
          -webkit-transform: none;
          transform: none;
          padding: 10px;
        }
        .products li.product.minimal .product-wrap .product-image-wrap .product-add-to-cart a {
          width: 50%;
          flex: 1;
          background-color: rgba(255,255,255,0.93)!important;
          padding: 10px 5px!important;
          color: #000;
          border-radius: 0;
          text-align: center;
          line-height: 20px;
        }
        .products li.product.minimal .product-wrap .product-image-wrap .product-add-to-cart a:hover {
          background-color: rgba(255,255,255,1)!important;
        }

        .products li.product.minimal [data-nectar-quickview="true"] .nectar_quick_view,
        body .products li.product.minimal .product-add-to-cart a {
          font-size: 14px;
        }

        .products li.product.minimal .product-add-to-cart .loading:after {
            display: none;
        }

        .products li.product.minimal .product-add-to-cart .loading:before  {
          font-family: WooCommerce;
          content: "\e01c";
          vertical-align: top;
          font-weight: 400;
          position: absolute;
          top: .618em;
          right: 1em;
          -webkit-animation: spin 2s linear infinite;
          animation: spin 2s linear infinite;
          margin-right: 7px;
          color: inherit;
          font-size: 11px;
          height: 18px;
          position: relative;
          display: inline-block;
          right: 0;
          top: 1px;
        }
        .products li.product.minimal .product-add-to-cart a.added span {
          display: inline;
        }
        .products li.product.minimal .product-wrap .product-image-wrap .product-add-to-cart .added_to_cart,
        .products li.product.minimal .product-add-to-cart .loading i {
          display: none;
        }
        .products li.product.minimal .product-add-to-cart .button i,
        .products li.product.minimal .product-add-to-cart .nectar_quick_view i {
          display: none;
        }

        .products li.product.minimal .product-wrap .product-image-wrap [data-nectar-quickview="true"] a:first-child,
        .products li.product.minimal .product-wrap .product-image-wrap .product-add-to-cart .added_to_cart {
          margin-right: 0;
        }
        .products li.product.minimal .product-wrap .product-image-wrap [data-nectar-quickview="true"] .nectar_quick_view {
          border-left: 1px solid rgba(0,0,0,0.1);
        }
        .woocommerce ul.products .minimal.product span.onsale,
        .woocommerce-page ul.products .minimal.product span.onsale {
          left: 10px;
          top: 10px;
        }
        .products li.product.minimal .product-image-wrap .product-add-to-cart a i.normal {
          top: 0;
          font-size: 14px;
          color: #000!important;
        }

        ';
      }
      // Non flex buttons
      else {

        if( 'image_zoom' === $product_minimal_hover_effect ) {
          echo '.products li.product.minimal .product-wrap > a {
            overflow: hidden;
            position: relative;
            display: block;
          }
          ul.products li.product.minimal:hover .product-wrap > a img {
            transform: scale(1.04) translateZ(0);
          }';

          if( $product_border_radius && $product_border_radius !== 'default' ) {
            echo '.products li.product.minimal .product-wrap > a {
              border-radius: '.$product_border_radius.';
            }';
          }

        }

      } // End default minimal button layout.

    } // End minimal product style.

    else if( 'material' === $product_style ) {
      // Border Radius
      if( $product_border_radius && 'default' !== $product_border_radius ) {
        echo '.woocommerce .material.product .product-wrap {
          border-radius: '.$product_border_radius.' '.$product_border_radius.' 0 0;
        }
        .woocommerce .material.product,
        .woocommerce .material.product:before,
        .widget_shopping_cart ul.product_list_widget li img {
            border-radius: '.$product_border_radius.';
        }';
      }
    }

    else if( 'text_on_hover' === $product_style || 'classic' === $product_style  ) {
      // Border Radius
      if( $product_border_radius && 'default' !== $product_border_radius ) {
        echo '.product .product-wrap, .widget_shopping_cart ul.product_list_widget li img {
          border-radius: '.$product_border_radius.';
        }';
      }
    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.12. WooCommerce Related/Upsell Carousel
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {
    $related_carousel = ( isset($nectar_options['single_product_related_upsell_carousel'] ) ) ? $nectar_options['single_product_related_upsell_carousel'] : '0';
    if( '1' === $related_carousel ) {
      echo '
      .related-upsell-carousel.nectar-woo-flickity section > h2 {
        display: flex;
        align-items: center;
      }
      .woocommerce .related-upsell-carousel.nectar-woo-flickity section > h2 .woo-flickity-count {
        margin-left: auto;
        padding-left: 20px;
        font-size: 16px!important;
      }

      @media only screen and (min-width: 1000px) {
        .related-upsell-carousel.nectar-woo-flickity.desktop-controls-hidden h2 .woo-flickity-count {
          display: none;
        }
      }';
    } else {
      echo '.woocommerce .products.related .product,
      .woocommerce .products.upsells .product {
          margin-bottom: 1.3%;
          margin-right: 1.3% !important;
      }

      @media only screen and (min-width: 1000px) {
        .woocommerce .products.related .product,
        .woocommerce .products.upsells .product {
          width: 24% !important;
        }
      }


      .woocommerce .span_9 .products.related .products li:nth-child(4),
      .woocommerce .span_9 .products.upsells .products li:nth-child(4) {
      	display: none;
      }

      .woocommerce .span_9 .products.related .products li:nth-child(3),
      .woocommerce .span_9 .products.upsells .products li:nth-child(3),
      .woocommerce .products.related ul.products li.product.last,
      .woocommerce-page .products.related ul.products li.product.last,
      .woocommerce .products.upsells ul.products li.product.last  {
      	margin-right: 0!important;
      }';
    }
  }

  /*-------------------------------------------------------------------------*/
  /* 18.13. WooCommerce lightbox Gallery Background
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {
    $woo_gallery_bg_color = ( isset($nectar_options['product_gallery_bg_color']) && !empty($nectar_options['product_gallery_bg_color']) ) ? $nectar_options['product_gallery_bg_color'] : false;

    if( false !== $woo_gallery_bg_color ) {

        echo '.single-product .pswp__bg {
          background-color: '.esc_attr($woo_gallery_bg_color).';
        }
        .single-product .pswp__caption__center {
          color: #fff;
        }
        .single-product .pswp__top-bar,
        .single-product .pswp__caption {
          background-color: transparent;
        }
        .single-product .pswp__button,
        .single-product .pswp__caption,
        .single-product .pswp__top-bar {
            mix-blend-mode: difference;
        }';

    }

  }


  /*-------------------------------------------------------------------------*/
  /* 18.14. WooCommerce Review Style
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {

    $woo_review_style = ( isset($nectar_options['product_reviews_style']) && !empty($nectar_options['product_reviews_style']) ) ? $nectar_options['product_reviews_style'] : 'default';

    if( 'off_canvas' === $woo_review_style ) {

        echo '
        .woocommerce-tabs #review_form_wrapper {
          display: none;
        }

        .woocommerce-tabs[data-tab-style] #tab-reviews #comments {
          display: flex;
          flex-wrap: wrap;
        }
        .woocommerce-tabs[data-tab-style="in_sidebar"] #tab-reviews #comments {
          flex-direction: column;
        }

        body > #review_form_wrapper.modal,
        body > #review_form_wrapper.modal #review_form {
            -webkit-transition: all .8s cubic-bezier(0.2,1,.3,1);
            transition: all .8s cubic-bezier(0.2,1,.3,1);
        }
        body > #review_form_wrapper.modal {
            position: fixed;
            height: 100%;
            right: 0;
            top: 0;
            z-index: 100000;
            -ms-transform: translateX(107%);
            transform: translateX(107%);
            -webkit-transform: translateX(107%);
            position: fixed;
            top: 0;
            overflow: hidden;
            background-color: '.esc_attr($global_bg_color).';
            max-width: 80%;
        }

        body > #review_form_wrapper.modal #review_form {
            -webkit-transform: translateX(-107%);
            transform: translateX(-107%);
            height: 100%;
            padding: 40px;
            min-height: calc(100vh - 80px);
            overflow-y: auto;
        }
        body > #review_form_wrapper.modal #review_form::-webkit-scrollbar {
          display: none;
        }

        body.admin-bar > #review_form_wrapper.modal #review_form {
          padding-top: 70px;
        }

        body > #review_form_wrapper.modal.open,
        body > #review_form_wrapper.modal.open #review_form  {
            -webkit-transform: translateX(0);
            transform: translateX(0);
        }

        body > #review_form_wrapper.modal .comment-form {
          display: flex;
          flex-wrap: wrap;
        }

        body > #review_form_wrapper.modal #respond .comment-form > * {
          width: 100%;
          padding: 0 0 10px 0;
          margin: 0!important;
        }
        body > #review_form_wrapper.modal #respond .comment-form > * p {
          padding-bottom: 0;
          margin-bottom: 0;
        }
        body > #review_form_wrapper.modal #respond #reply-title {
          display: block;
          padding-right: 60px;
        }
        body.material > #review_form_wrapper.modal #respond input#submit {
          padding-left: 35px;
          padding-right: 35px;
        }
        body > #review_form_wrapper.modal .nectar-close-btn-wrap {
          position: absolute;
          right: 40px;
          top: 48px;
        }
        body.admin-bar > #review_form_wrapper.modal .nectar-close-btn-wrap {
          top: 78px;
        }
        @media only screen and (min-width: 691px) {
          body > #review_form_wrapper.modal #respond .comment-form > .comment-form-author {
            padding-right: 20px;
            width: 50%;
          }
          body > #review_form_wrapper.modal #respond .comment-form > .comment-form-email {
            padding-left: 20px;
            width: 50%;
          }
        }
        @media only screen and (min-width: 1000px) {
          body > #review_form_wrapper.modal {
            max-width: 50%;
          }
          body > #review_form_wrapper.modal textarea {
            height: 150px;
          }

        }


        @media only screen and (min-width: 1000px) {
          .woocommerce-tabs[data-tab-style] #reviews #comments ol.commentlist {
            padding-left: 7.5%;
            flex: 1;
          }
          .woocommerce-tabs:not([data-tab-style="in_sidebar"]) #reviews .woocommerce-Reviews-title {
            padding-right: 7.5%;
            width: auto;
            border-right: 1px solid rgba(0,0,0,0.1);
          }
        }
        .woocommerce [data-tab-pos="fullwidth_stacked"] .commentlist > li .comment_container {
          margin-top: 7.5%;
        }

        @media only screen and (min-width: 1350px) {

          .woocommerce-tabs[data-tab-style] #reviews #comments ol.commentlist {
            padding-left: 5.5%;
          }
          .woocommerce-tabs[data-tab-style] #reviews .woocommerce-Reviews-title {
            padding-right: 5.5%;
          }
          .woocommerce [data-tab-pos="fullwidth_stacked"] .commentlist > li .comment_container {
            margin-top: 5.5%;
          }

          .woocommerce-tabs[data-tab-style] #tab-reviews .woocommerce-pagination {
            padding-left: calc(160px + 5.5%);
            margin-top: 5.5%;
          }

        }

        .woocommerce-tabs[data-tab-style="fullwidth"] #tab-reviews > #reviews #comments {
          width: 100%;
          flex-direction: row;
        }
        .woocommerce-tabs[data-tab-style="fullwidth"] #reviews p.woocommerce-noreviews {
          border: none;
          align-items: center;
          padding: 0;
          justify-content: flex-start;
        }

        .woocommerce .woocommerce-tabs[data-tab-style] .commentlist li .comment_container {
          border: none;
        }

        .woocommerce-tabs[data-tab-style] #reviews .commentlist li  .star-rating {
          float: none;
        }

        .woocommerce-tabs[data-tab-style] #reviews #comments ol.commentlist li img.avatar {
          left: 0;
          top: 0;
        }

        .woocommerce-tabs[data-tab-style] #reviews #comments ol.commentlist li .comment-text .description p:last-child {
          padding-bottom: 0;
          margin-bottom: 0;
        }


        .woocommerce-tabs[data-tab-style] #reviews #comments ol.commentlist li .comment-text {
          margin-left: 90px;
          padding: 0;
          border-radius: 0;
          display: flex;
          flex-direction: column;
        }

        .woocommerce-tabs[data-tab-style] #comments li .comment-text .meta {
          padding-top: 10px;
        }

        .woocommerce-tabs[data-tab-style] .woocommerce-noreviews,
        .woocommerce-tabs[data-tab-style] .nectar-no-reviews {
          display: block;
          margin: 0 0 20px;
          min-width: 100%;
          padding-bottom: 0;
        }


        @media only screen and (min-width: 1000px) {
          .woocommerce-tabs[data-tab-style] #tab-reviews .woocommerce-pagination {
            padding-left: calc(160px + 7.5%);
            margin-top: 7.5%;
          }
        }


        .woocommerce-tabs[data-tab-style] #tab-reviews .woocommerce-pagination {
          width: 100%;
        }



        .woocommerce-tabs #reviews .woocommerce-Reviews-title .nectar-average {
          line-height: 1.1;
        }
        .woocommerce-tabs #reviews .woocommerce-Reviews-title .star-rating {
          width: 133px;
          font-size: 22px;
          float: none;
        }

        .woocommerce-tabs #reviews .woocommerce-Reviews-title .nectar-average-count {
          font-size: 50px;
          line-height: 1;
          margin-bottom: 30px;
          display: block;
        }
        .woocommerce-tabs[data-tab-style="fullwidth_stacked"] #reviews .star-rating {
          margin-bottom: 0;
        }

        .woocommerce-tabs #reviews .woocommerce-Reviews-title .nectar-average-count-wrap .total-num {
          font-size: 14px;
        }
        .woocommerce-tabs #reviews .woocommerce-Reviews-title .nectar-button {
          margin-top: 40px;
        }

        .woocommerce-verification-required {
          margin-top: 50px;
          padding: 15px;
          border: 1px solid rgba(0,0,0,0.1);
        }

        @media only screen and (max-width: 999px) {

          
          .woocommerce-tabs[data-tab-style] #reviews .woocommerce-Reviews-title  {
            margin: 0 auto 40px auto;
            text-align: center;
          }
          .woocommerce-tabs[data-tab-style] #reviews .woocommerce-Reviews-title .star-rating {
            margin: 0 auto;
          }
          .woocommerce-tabs #reviews .woocommerce-Reviews-title .nectar-average-count {
            margin-bottom: 15px;
          }
          .woocommerce-tabs #reviews .woocommerce-Reviews-title .nectar-button {
            margin-top: 25px;
          }
          .woocommerce .woocommerce-tabs[data-tab-style] .commentlist > li .comment_container {
            margin-top: 50px;
          }

        }


        ';

    } else {
      echo '.woocommerce-verification-required {
        margin-top: 15px; 
      }';
    }

  }


  /*-------------------------------------------------------------------------*/
  /* 18.15. WooCommerce Variation Dropdown Style
  /*-------------------------------------------------------------------------*/
  if( function_exists( 'is_woocommerce' ) ) {

    $woo_variation_dropdown_style = ( isset($nectar_options['product_variable_select_style']) && !empty($nectar_options['product_variable_select_style']) ) ? $nectar_options['product_variable_select_style'] : 'default';

    if( 'underline' === $woo_variation_dropdown_style ) {

      echo '
      .variations_form .select2-container--default .select2-selection__rendered,
      .variations_form .variations select {
      	background-repeat: no-repeat;
      	background-size: 100% 2px;
      	background-position: left bottom;
      	background-image: linear-gradient(to right, '.esc_html($global_font_color).' 0%, '.esc_html($global_font_color).' 100%);
      	transition: background-size 0.55s cubic-bezier(.2,.75,.5,1);
      }
      .variations_form .variations .select2-container .select2-selection--single .select2-selection__rendered {
        padding: 0 25px 0 0;
      }
      body .variations_form .variations select {
        padding: 5px 0;
      }
      .variations_form .select2-container--default:hover .select2-selection__rendered,
      .variations_form .select2-container--default.select2-container--open .select2-selection__rendered,
      .variations_form .variations select:hover {
      	background-size: 0% 2px;
        background-position: right bottom;
      }

      .variations_form .variations .select2-container--default .select2-selection--single,
      .variations_form .variations .select2-container--default .select2-selection--single:hover,
      .variations_form .variations .select2-container--default.select2-container--open .select2-selection--single,
      .variations_form .variations select {
        background-color: transparent!important;
        border: none!important;
      }
      .variations_form .variations select {
        box-shadow: none;
      }
      .variations_form .variations .select2-container--default:hover .select2-selection--single .select2-selection__rendered,
      .variations_form .variations .select2-container--default.select2-container--open .select2-selection--single .select2-selection__rendered {
        color: inherit!important;
      }
      .variations_form .variations .select2-container--default .select2-selection--single .select2-selection__arrow {
        width: 12px;
      }
      .variations_form .variations .select2-container--default:hover .select2-selection--single .select2-selection__arrow b {
        border-top-color: inherit;
      }
      .variations_form .variations .select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-bottom-color: inherit!important;
      }
      body[data-fancy-form-rcs="1"] .variations_form.cart .variations .select2-container,
      body[data-fancy-form-rcs="1"].woocommerce div.product form.cart .variations select {
      	min-width: 0;
        margin-right: 0;
      }
      .woocommerce div.product .variations_form.cart .variations td.label {
        padding-bottom: 0;
      }
      .variations_form .variations .select2-results__option {
          line-height: 1.3;
      }

      body .variations_form .select2-dropdown {
        box-shadow: 0 6px 28px rgba(0,0,0,0.08);
      }
      ';

    }

  }

  /*-------------------------------------------------------------------------*/
  /* 18.16. MISC Third Party
  /*-------------------------------------------------------------------------*/


?>
