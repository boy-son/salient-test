<?php

/**
 * Outputs the theme option typography font styles.
 *
 * The styles generated from here will either be contained in salient/css/salient-dynamic-styles.css
 * or output directly in the head, depending on if the server writing permission is set for the css directory.
 *
 *
 * @version 13.0
 */

$nectar_options  = get_nectar_theme_options();
$legacy_options  = get_option('salient');
$current_options = get_option('salient_redux');

// Load custom fonts.
if( !empty($current_options) ) {

	$font_fields = array(
		'logo_font_family',
		'navigation_font_family',
		'navigation_dropdown_font_family',
		'portfolio_filters_font_family',
		'portfolio_caption_font_family',
		'blog_single_post_content_font_family',
		'page_heading_font_family',
		'page_heading_subtitle_font_family',
		'off_canvas_nav_font_family',
		'off_canvas_nav_subtext_font_family',
		'body_font_family',
		'h1_font_family',
		'h2_font_family',
		'h3_font_family',
		'h4_font_family',
		'h5_font_family',
		'h6_font_family',
		'i_font_family',
		'label_font_family',
		'nectar_slider_heading_font_family',
		'home_slider_caption_font_family',
		'testimonial_font_family',
		'sidebar_footer_h_font_family',
		'team_member_h_font_family',
		'nectar_dropcap_font_family',
		'nectar_sidebar_footer_headers_font_family',
		'nectar_woo_shop_product_title_font_family',
		'nectar_woo_shop_product_secondary_font_family');

	// Legacy formatting.
	foreach($font_fields as $k => $v) {
		$nectar_options[str_replace('_family', '', $v)] = (empty($nectar_options[$v]['font-family'])) ? '-' : $nectar_options[$v]['font-family'];
		$nectar_options[str_replace('_family', '', $v) . '_size'] = (empty($nectar_options[$v]['font-size'])) ? '-' : $nectar_options[$v]['font-size'];
		$nectar_options[str_replace('_family', '', $v) . '_line_height'] = (empty($nectar_options[$v]['line-height'])) ? '-' : $nectar_options[$v]['line-height'];
		$nectar_options[str_replace('_family', '', $v) . '_spacing'] = (empty($nectar_options[$v]['letter-spacing'])) ? '-' : $nectar_options[$v]['letter-spacing'];
		$nectar_options[str_replace('_family', '', $v) . '_weight'] = (empty($nectar_options[$v]['font-weight'])) ? '-' : $nectar_options[$v]['font-weight'];
		$nectar_options[str_replace('_family', '', $v) . '_transform'] = (empty($nectar_options[$v]['text-transform'])) ? '-' : $nectar_options[$v]['text-transform'];
		$nectar_options[str_replace('_family', '', $v) . '_style'] = (empty($nectar_options[$v]['font-weight'])) ? '-' : $nectar_options[$v]['font-weight'];

		$nectar_options[str_replace('_family', '', $v) . '_spacing_units'] = (isset($nectar_options[$v]['letter-spacing-units']) && !empty($nectar_options[$v]['letter-spacing-units'])) ? $nectar_options[$v]['letter-spacing-units'] : 'px';

		if( isset($nectar_options[$v]) && is_array($nectar_options[$v]) ) {
			$nectar_options[$v]['attrs_in_use'] = false;
		}
		
		if(!empty( $nectar_options[str_replace('_family', '', $v)] ) && $nectar_options[str_replace('_family', '', $v)] != '-' ||
		   !empty( $nectar_options[str_replace('_family', '', $v) . '_size'] ) && $nectar_options[str_replace('_family', '', $v) . '_size'] != '-' ||
		   !empty( $nectar_options[str_replace('_family', '', $v) . '_line_height'] ) && $nectar_options[str_replace('_family', '', $v) . '_line_height'] != '-' ||
		   !empty( $nectar_options[str_replace('_family', '', $v) . '_spacing'] ) && $nectar_options[str_replace('_family', '', $v) . '_spacing'] != '-' ||
		   !empty( $nectar_options[str_replace('_family', '', $v) . '_weight'] ) && $nectar_options[str_replace('_family', '', $v) . '_weight'] != '-' ||
		   !empty( $nectar_options[str_replace('_family', '', $v) . '_transform'] ) && $nectar_options[str_replace('_family', '', $v) . '_transform'] != '-' ||
		   !empty( $nectar_options[str_replace('_family', '', $v) . '_style'] ) && $nectar_options[str_replace('_family', '', $v) . '_style'] != '-') {
			if( is_array($nectar_options[$v]) ) { 
				$nectar_options[$v]['attrs_in_use'] = true;
			}
		}

		if(!empty($nectar_options[$v]['font-weight']) && !empty($nectar_options[$v]['font-style'])) {
			$nectar_options[str_replace('_family', '', $v) . '_style'] = $nectar_options[$v]['font-weight'] . $nectar_options[$v]['font-style'];
		}
	}

}



// Responsive heading values.
$using_custom_responsive_sizing = ( !empty($nectar_options['use-responsive-heading-typography']) && $nectar_options['use-responsive-heading-typography'] == '1') ? true : false;

$nectar_h1_small_desktop = ( !empty($nectar_options['h1-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h1-small-desktop-font-size'])/100 : 0.75;
$nectar_h1_tablet        = ( !empty($nectar_options['h1-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h1-tablet-font-size'])/100 : 0.7;
$nectar_h1_phone         = ( !empty($nectar_options['h1-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h1-phone-font-size'])/100 : 0.65;
$nectar_h1_default_size  = 54;

$nectar_h2_small_desktop = ( !empty($nectar_options['h2-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h2-small-desktop-font-size'])/100 : 0.85;
$nectar_h2_tablet        = ( !empty($nectar_options['h2-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h2-tablet-font-size'])/100 : 0.8;
$nectar_h2_phone         = ( !empty($nectar_options['h2-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h2-phone-font-size'])/100 : 0.75;
$nectar_h2_default_size  = 34;

$nectar_h3_small_desktop = ( !empty($nectar_options['h3-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h3-small-desktop-font-size'])/100 : 0.85;
$nectar_h3_tablet        = ( !empty($nectar_options['h3-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h3-tablet-font-size'])/100 : 0.8;
$nectar_h3_phone         = ( !empty($nectar_options['h3-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h3-phone-font-size'])/100 : 0.8;
$nectar_h3_default_size  = 22;

$nectar_h4_small_desktop = ( !empty($nectar_options['h4-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h4-small-desktop-font-size'])/100 : 1;
$nectar_h4_tablet        = ( !empty($nectar_options['h4-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h4-tablet-font-size'])/100 : 1;
$nectar_h4_phone         = ( !empty($nectar_options['h4-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h4-phone-font-size'])/100 : 0.9;
$nectar_h4_default_size  = 18;

$nectar_h5_small_desktop = ( !empty($nectar_options['h5-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h5-small-desktop-font-size'])/100 : 1;
$nectar_h5_tablet        = ( !empty($nectar_options['h5-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h5-tablet-font-size'])/100 : 1;
$nectar_h5_phone         = ( !empty($nectar_options['h5-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h5-phone-font-size'])/100 : 1;
$nectar_h5_default_size  = 16;

$nectar_h6_small_desktop = ( !empty($nectar_options['h6-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h6-small-desktop-font-size'])/100 : 1;
$nectar_h6_tablet        = ( !empty($nectar_options['h6-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h6-tablet-font-size'])/100 : 1;
$nectar_h6_phone         = ( !empty($nectar_options['h6-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['h6-phone-font-size'])/100 : 1;
$nectar_h6_default_size  = 14;

$nectar_body_small_desktop = ( !empty($nectar_options['body-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['body-small-desktop-font-size'])/100 : 1;
$nectar_body_tablet        = ( !empty($nectar_options['body-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['body-tablet-font-size'])/100 : 1;
$nectar_body_phone         = ( !empty($nectar_options['body-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['body-phone-font-size'])/100 : 1;
$nectar_body_default_size  = 14;

$nectar_blockquote_small_desktop = ( !empty($nectar_options['blockquote-small-desktop-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['blockquote-small-desktop-font-size'])/100 : 1;
$nectar_blockquote_tablet        = ( !empty($nectar_options['blockquote-tablet-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['blockquote-tablet-font-size'])/100 : 1;
$nectar_blockquote_phone         = ( !empty($nectar_options['blockquote-phone-font-size']) && $using_custom_responsive_sizing) ? intval($nectar_options['blockquote-phone-font-size'])/100 : 1;
$nectar_blockquote_default_size  = 22;

// WooCommerce Tabbed Headings.
global $woocommerce;
$product_tab_heading_typography = false;

if( isset($nectar_options['product_tab_heading_typography']) &&
		'default' !== $nectar_options['product_tab_heading_typography'] ) {
	$product_tab_heading_typography = esc_html($nectar_options['product_tab_heading_typography']);
}

// Search Font.
$header_search_font = (isset($nectar_options['header-search-type'])) ? $nectar_options['header-search-type'] : 'default';

	/*-------------------------------------------------------------------------*/
	/*	Body Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['body_font_style']);

	( intval( substr($nectar_options['body_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['body_font_size'],0,-2)) * 1.8 .'px' : $line_height = null ;  ?>

	<?php

	if( is_array($nectar_options['body_font_family']) && $nectar_options['body_font_family']['attrs_in_use'] ) {

		echo 'body,
		.toggle h3 a,
		body .ui-widget,
		table,
		select,
		.bar_graph li span strong,
		#slide-out-widget-area .tagcloud a,
		body .container .woocommerce-message a.button,
		#search-results .result .title span,
		.woocommerce ul.products li.product h3,
		.woocommerce-page ul.products li.product h3,
		.row .col.section-title .nectar-love span,
		body .nectar-love span,
		body .nectar-social .nectar-love .nectar-love-count,
		body .carousel-heading h2,
		.sharing-default-minimal .nectar-social .social-text,
		body .sharing-default-minimal .nectar-love,
		.widget ul.nectar_widget[class*="nectar_blog_posts_"] > li .post-date,
		.single [data-post-hs="default_minimal"] #single-below-header span,
		.single .heading-title[data-header-style="default_minimal"] #single-below-header span,
		#header-outer .nectar-image-link-widget .image-link-content span,
		#slide-out-widget-area.fullscreen .nectar-image-link-widget .image-link-content span,
		#slide-out-widget-area.fullscreen-alt .nectar-image-link-widget .image-link-content span, .nectar-header-text-content,
		#slide-out-widget-area .nectar-ext-menu-item .menu-item-desc,
		.woocommerce-checkout-review-order-table .product-info .product-quantity,
		#ajax-content-wrap .nectar-shop-header-bottom .widget_layered_nav_filters ul li a,
		#ajax-content-wrap .nectar-sticky-tabs .wpb_tabs_nav li .menu-content > a {';

			// Output font properties.
			nectar_output_font_props('body_font', $line_height, $nectar_options);

  	echo '}';

		// Store calculated line height.
		$the_line_height = nectar_font_line_height('body_font', $line_height, $nectar_options);

	  if( $nectar_options['body_font'] != '-' ) {
       $font_family = (1 === preg_match('~[0-9]~', $nectar_options['body_font'])) ? '"'. $nectar_options['body_font'] .'"' : $nectar_options['body_font'];
		   echo '.bold, strong, b { font-family: ' . esc_attr($font_family) .'; font-weight: 600; } ';
		   echo '.single #single-below-header span { font-family: ' . esc_attr($font_family) .';  }';
		 }

		 echo '.nectar-fancy-ul ul li .icon-default-style[class^="icon-"] {';
			 if(!empty($the_line_height)) {
				 echo 'line-height:' . $the_line_height .';';
			 }
		 echo '}';


		 if( isset($nectar_options['body_font_family']['font-weight']) &&
	       !empty($nectar_options['body_font_family']['font-weight']) ) {

					 echo '#ajax-content-wrap .nectar-shop-header-bottom .widget_layered_nav_filters ul li a,
					 .nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection--single .select2-selection__rendered,
					 body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select {
						 font-weight: '.esc_attr($nectar_options['body_font_family']['font-weight']).'!important;
					 }';
	   }
		 if( isset($nectar_options['body_font_family']['font-family']) &&
	       !empty($nectar_options['body_font_family']['font-family']) ) {

					 echo '.nectar-shop-header-bottom .woocommerce-ordering .select2-container--default .select2-selection--single .select2-selection__rendered,
					 body[data-fancy-form-rcs="1"] .nectar-shop-header-bottom .woocommerce-ordering select {
						 font-family: '.esc_attr($nectar_options['body_font_family']['font-family']).'!important;
					 }';
	   }


		 $defined_font_size   = (!empty($nectar_options['body_font_size']) && $nectar_options['body_font_size'] != '-') ? intval($nectar_options['body_font_size']) : $nectar_body_default_size;
		 $defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : intval($nectar_body_default_size) * 1.8;

     if( $defined_font_size < 14 ) {
       echo '#footer-widgets[data-cols="5"] .container .row .widget {
         font-size: 14px;
         line-height: 24px
       }';
     }
     ?>

		 @media only screen and (max-width: 1300px) and (min-width: 1000px) {
			 body {
				 font-size: <?php echo esc_html( ceil($defined_font_size*$nectar_body_small_desktop) ) . 'px'; ?>;
				 line-height: <?php echo esc_html( ceil($defined_line_height*$nectar_body_small_desktop) ) . 'px'; ?>;
			 }
		 }
		 @media only screen and (max-width: 999px) and (min-width: 691px) {
			 body {
				 font-size: <?php echo esc_html( ceil($defined_font_size*$nectar_body_tablet) ) . 'px'; ?>;
				 line-height: <?php echo esc_html( ceil($defined_line_height*$nectar_body_tablet) ) . 'px'; ?>;
			 }

		 }
		 @media only screen and (max-width: 690px) {
			 body {
				 font-size: <?php echo esc_html( ceil($defined_font_size*$nectar_body_phone) ) . 'px'; ?>;
				 line-height: <?php echo esc_html( ceil($defined_line_height*$nectar_body_phone) ) . 'px'; ?>;
			 }

		 }


 <?php } //attrs in use




 /*-------------------------------------------------------------------------*/
 /* Logo
 /*-------------------------------------------------------------------------*/
 $styles = explode('-', $nectar_options['logo_font_style']);

 ( intval( substr($nectar_options['logo_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['logo_font_size'],0,-2)) .'px' : $line_height = null ;

 if( isset($nectar_options['logo_font_family']) &&
 	is_array($nectar_options['logo_font_family']) &&
	$nectar_options['logo_font_family']['attrs_in_use'] ) {

	 echo '#header-outer #logo.no-image,
	 #header-outer .logo-clone.no-image,
   #header-outer[data-format="centered-menu"] .logo-spacing[data-using-image="false"],
   #header-outer[data-format="centered-logo-between-menu"] .logo-spacing[data-using-image="false"] {';

		 // Output font properties.
		 nectar_output_font_props('logo_font', $line_height, $nectar_options);

	 echo ' }';

   // Shrink logo font size for mobile if user defined size is large.
   if( $nectar_options['logo_font_size'] !== '-' && intval( substr($nectar_options['logo_font_size'],0,-2) ) > 24 ) {
     echo '@media only screen and (max-width: 999px) {
       #header-outer #logo.no-image {
         font-size: 24px;
         line-height: 24px;
       }
     }';
   } else if($nectar_options['logo_font_size'] !== '-') {
		   $logo_font_size = intval( substr($nectar_options['logo_font_size'],0,-2) );
			 echo '
				 #header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 #logo.no-image,
				 #header-outer[data-format="centered-menu-bottom-bar"] #top .span_9 .logo-clone.no-image {
				 font-size: '.esc_attr( ceil($logo_font_size*0.8) ).'px;
			  }
				@media only screen and (max-width: 999px) {
					#header-outer[data-format="centered-menu-bottom-bar"] .logo-clone.no-image,
					#header-outer[data-format="centered-menu-bottom-bar"] #logo.no-image {
 				   font-size: '.esc_attr( ceil($logo_font_size*0.8) ).'px;
 			   }
				}
		 ';
	 }

	}//attrs in use


	/*-------------------------------------------------------------------------*/
	/*	Navigation Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['navigation_font_style']);

	( intval( substr($nectar_options['navigation_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['navigation_font_size'],0,-2)) *1.4 .'px' : $line_height = null;

	if( is_array($nectar_options['navigation_font_family']) && 
	    $nectar_options['navigation_font_family']['attrs_in_use'] ) {

		echo '#top nav > ul > li > a,
		.span_3 .pull-left-wrap > ul > li > a,
		body.material #search-outer #search input[type="text"],
		#top ul .slide-out-widget-area-toggle a i.label,
    #top .span_9 > .slide-out-widget-area-toggle a.using-label .label,
		#header-secondary-outer .nectar-center-text,
		#slide-out-widget-area .secondary-header-text,
    #header-outer #mobile-menu ul li a,
		#header-outer #mobile-menu .secondary-header-text,  
    .nectar-mobile-only.mobile-header a {';

			// Output font properties.
			nectar_output_font_props('navigation_font', $line_height, $nectar_options);

		echo ' }';

		// Header Search.
		if( 'header_nav' === $header_search_font ) {
			echo '#search-outer #search input[type=text] {
				text-transform: none;
        letter-spacing: 0px;';
				nectar_output_font_props('navigation_font', $line_height, $nectar_options);
			echo '}';
		}

		  if( $nectar_options['navigation_font_size'] != '-' ) {

		    	echo '#top nav > ul > li[class*="button_solid_color"] > a:before, #header-outer.transparent #top nav > ul > li[class*="button_solid_color"] > a:before, #header-outer #top .slide-out-widget-area-toggle[data-custom-color="true"] a:before {
		    		height: ' . floor((intval(substr($nectar_options['navigation_font_size'],0,-2)) *1.4)+ 5)  .'px;
		    	}';

		    	echo '#top nav > ul > li[class*="button_bordered"] > a:before, #header-outer.transparent #top nav > ul > li[class*="button_bordered"] > a:before {
		    		height: ' . floor((intval(substr($nectar_options['navigation_font_size'],0,-2)) *1.4)+ 15)  .'px;
		    	}';

          // Increase dropdown arrow size for larger fonts.
					if( intval(substr($nectar_options['navigation_font_size'],0,-2)) >= 16 ) {
						echo '.material .sf-menu > li > a > .sf-sub-indicator [class^="icon-"] { font-size: 18px; }';
					}

			}

      // Minimum line height for header nav.
      if( !empty($nectar_options['navigation_font_line_height']) && $nectar_options['navigation_font_line_height'] !== '-' ) {
        $nav_line_height = intval(substr($nectar_options['navigation_font_line_height'],0,-2));
        if( $nav_line_height < 10) {
          echo '#top nav > ul > li > a { line-height: 10px; }';
        }
      }

		}//attrs in use


	/*-------------------------------------------------------------------------*/
	/*	Navigation Dropdown Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['navigation_dropdown_font_style']);
	( intval( substr($nectar_options['navigation_dropdown_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['navigation_dropdown_font_size'],0,-2)) + 10 .'px' : $line_height = null;

	if( is_array($nectar_options['navigation_dropdown_font_family']) && 
		$nectar_options['navigation_dropdown_font_family']['attrs_in_use'] ) {

		echo '#top .sf-menu li ul li a,
		#header-secondary-outer nav > ul > li > a,
		#header-secondary-outer .sf-menu li ul li a,
		#header-secondary-outer ul ul li a,
		#header-outer .widget_shopping_cart .cart_list a,
		.nectar-slide-in-cart.style_slide_in_click .close-cart {';

			// Output font properties.
			nectar_output_font_props('navigation_dropdown_font', $line_height, $nectar_options);

		echo ' }'; ?>


		<?php 
   
	} // attrs in use


	/*-------------------------------------------------------------------------*/
	/*	Page Heading Font - h1
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['h1_font_style']);

	( intval( substr($nectar_options['h1_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['h1_font_size'],0,-2)) +6 .'px' : $line_height = null;

	if( is_array($nectar_options['h1_font_family']) && 
		$nectar_options['h1_font_family']['attrs_in_use'] ) {

			echo '#page-header-bg h1,
			body h1,
			body .row .col.section-title h1,
			.full-width-content .nectar-recent-posts-slider .recent-post-container .inner-wrap h2,
			body #error-404 h1,
			[data-inherit-heading-family="h1"] {';

			// Output font properties.
			nectar_output_font_props('h1_font', $line_height, $nectar_options);

			echo ' }';

			// Store calculated line height.
			$the_line_height = nectar_font_line_height('h1_font', $line_height, $nectar_options);

			$defined_font_size = (!empty($nectar_options['h1_font_size']) && $nectar_options['h1_font_size'] != '-') ? intval($nectar_options['h1_font_size']) : $nectar_h1_default_size;
			$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_h1_default_size + 6;

			?>

			@media only screen and (max-width: 1300px) and (min-width: 1000px) {
				body .row .col.section-title h1, body h1, .full-width-content .recent-post-container .inner-wrap h2 {
					font-size: <?php echo esc_html( $defined_font_size*$nectar_h1_small_desktop ) . 'px'; ?>;
					line-height: <?php echo esc_html( $defined_line_height*$nectar_h1_small_desktop ) . 'px'; ?>;
				}
			}
			@media only screen and (max-width: 999px) and (min-width: 691px) {
				body .row .col.section-title h1,
        body h1,
        html body .row .col.section-title.span_12 h1,
        #page-header-bg .span_6 h1,
        #page-header-bg.fullscreen-header .span_6 h1,
        body .featured-media-under-header h1,
        .full-width-content .nectar-recent-posts-slider .recent-post-container .inner-wrap h2 {
					font-size: <?php echo esc_html( $defined_font_size*$nectar_h1_tablet ) . 'px'; ?>;
					line-height: <?php echo esc_html( $defined_line_height*$nectar_h1_tablet ) . 'px'; ?>;
				}
				.full-width-content .recent-post-container .inner-wrap h2 {
					font-size: <?php echo esc_html( $defined_font_size*$nectar_h1_tablet ) . 'px'; ?>;
					line-height: <?php echo esc_html( $defined_line_height*$nectar_h1_tablet ) . 'px'; ?>;
				}

				.wpb_wrapper h1.vc_custom_heading {
					font-size: <?php echo esc_html( $defined_font_size*$nectar_h1_tablet ) . 'px!important'; ?>;
					line-height: <?php echo esc_html( $defined_line_height*$nectar_h1_tablet ) . 'px!important'; ?>;
				}

			}
			@media only screen and (max-width: 690px) {
				body .row .col.section-title h1,
        body h1,
        html body .row .col.section-title.span_12 h1,
        body.single.single-post .row .col.section-title.span_12 h1,
        #page-header-bg .span_6 h1,
        #page-header-bg.fullscreen-header .span_6 h1,
        body .featured-media-under-header h1,
        .full-width-content .nectar-recent-posts-slider .recent-post-container .inner-wrap h2 {
					font-size: <?php echo esc_html( $defined_font_size*$nectar_h1_phone ) . 'px'; ?>;
					line-height: <?php echo esc_html( $defined_line_height*$nectar_h1_phone ) . 'px'; ?>;
				}

				.wpb_wrapper h1.vc_custom_heading {
					font-size: <?php echo esc_html( $defined_font_size*$nectar_h1_phone ) . 'px!important'; ?>;
					line-height: <?php echo esc_html( $defined_line_height*$nectar_h1_phone ) . 'px!important'; ?>;
				}

			}

<?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Page Heading Font - h2
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['h2_font_style']);

	( intval( substr($nectar_options['h2_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['h2_font_size'],0,-2)) + intval(substr($nectar_options['h2_font_size'],0,-2))*0.65 .'px' : $line_height = null;


	if( is_array($nectar_options['h2_font_family']) && 
		$nectar_options['h2_font_family']['attrs_in_use'] ) {

		echo '#page-header-bg h2,
		body h2,
		article.post .post-header h2,
		article.post.quote .post-content h2,
		article.post.link .post-content h2,
		#call-to-action span,
		.woocommerce .full-width-tabs #reviews h3,
		.row .col.section-title h2,
		.nectar_single_testimonial[data-style="bold"] p,
		.woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control,
		body #error-404 h2, .woocommerce-page .woocommerce p.cart-empty,
		.nectar-ext-menu-item .inherit-h2 .menu-title-text,
		#slide-out-widget-area .nectar-ext-menu-item .inherit-h2,
		#mobile-menu .nectar-ext-menu-item .inherit-h2,
		#ajax-content-wrap .nectar-inherit-h2,
    #header-outer .nectar-inherit-h2,
		[data-inherit-heading-family="h2"],
		.nectar-quick-view-box div.product h1.product_title.nectar-inherit-h2 { ';

			// Output font properties.
			nectar_output_font_props('h2_font', $line_height, $nectar_options);

		echo ' }';


		// Store calculated line height.
		$the_line_height = nectar_font_line_height('h2_font', $line_height, $nectar_options);

		$defined_font_size = (!empty($nectar_options['h2_font_size']) && $nectar_options['h2_font_size'] != '-') ? intval($nectar_options['h2_font_size']) : $nectar_h2_default_size;
		$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_h2_default_size + 8;

		?>

		.single-product div.product h1.product_title, .nectar-shop-header .page-title {
			font-size: <?php echo intval($defined_font_size) . 'px'; ?>;
			line-height: <?php echo intval($defined_line_height) . 'px'; ?>;
		}

		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h2"] .content {
			font-size: <?php echo intval($defined_font_size) . 'px'; ?>;
		}


		@media only screen and (max-width: 1300px) and (min-width: 1000px) {
		 	body h2, .single-product div.product h1.product_title, .nectar-shop-header .page-title, #ajax-content-wrap .nectar-inherit-h2,
			.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h2"] .content {
		 		font-size: <?php echo esc_html( $defined_font_size*$nectar_h2_small_desktop ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h2_small_desktop ) . 'px'; ?>;
			}

		}

		@media only screen and (max-width: 999px) and (min-width: 691px) {
		.col h2, body h2, .single-product div.product h1.product_title, .nectar-shop-header .page-title, .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control,
    .nectar_single_testimonial[data-style="bold"] p, #slide-out-widget-area .nectar-ext-menu-item .inherit-h2, #mobile-menu .nectar-ext-menu-item .inherit-h2, #ajax-content-wrap .nectar-inherit-h2,
		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h2"] .content {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h2_tablet ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h2_tablet ) . 'px'; ?>;
			}
			.wpb_wrapper h2.vc_custom_heading {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h2_tablet ) . 'px!important'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h2_tablet ) . 'px!important'; ?>;
			}

		}

		@media only screen and (max-width: 690px) {
		.col h2, body h2, .single-product div.product h1.product_title, .nectar-shop-header .page-title, .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control,
    .nectar_single_testimonial[data-style="bold"] p, #slide-out-widget-area .nectar-ext-menu-item .inherit-h2, #ajax-content-wrap .nectar-inherit-h2,
		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h2"] .content {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h2_phone ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h2_phone ) . 'px'; ?>;
			}
			.wpb_wrapper h2.vc_custom_heading {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h2_phone ) . 'px!important'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h2_phone ) . 'px!important'; ?>;
			}
		}

	<?php


	if( $woocommerce && 'h2' === $product_tab_heading_typography ) {

		echo '
		#ajax-content-wrap .related.products > h2,
		#ajax-content-wrap .upsells.products > h2,
		#ajax-content-wrap #tab-reviews #reviews #comments h2,
		.woocommerce .woocommerce-tabs #tab-additional_information h2,
		#ajax-content-wrap #reviews #reply-title,
		.woocommerce-tabs #reviews .nectar-average-count,
		#review_form_wrapper.modal .comment-reply-title,
		.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews { ';

			// Output font properties.
			nectar_output_font_props('h2_font', $line_height, $nectar_options);

		echo ' }

		@media only screen and (max-width: 999px) and (min-width: 691px) {
			#ajax-content-wrap .related.products > h2,
			#ajax-content-wrap .upsells.products > h2,
			#ajax-content-wrap #tab-reviews #reviews #comments h2,
			.woocommerce .woocommerce-tabs #tab-additional_information h2,
			#ajax-content-wrap #reviews #reply-title,
			#review_form_wrapper.modal .comment-reply-title,
			.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
				font-size: '.esc_html( $defined_font_size*$nectar_h2_tablet ) . 'px;
				line-height: '.esc_html( $defined_line_height*$nectar_h2_tablet ) . 'px;
			}
		}
		@media only screen and (max-width: 690px) {
			#ajax-content-wrap .related.products > h2,
			#ajax-content-wrap .upsells.products > h2,
			#ajax-content-wrap #tab-reviews #reviews #comments h2,
			.woocommerce .woocommerce-tabs #tab-additional_information h2,
			#ajax-content-wrap #reviews #reply-title,
			#review_form_wrapper.modal .comment-reply-title,
			.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
				font-size: '.esc_html( $defined_font_size*$nectar_h2_phone ) . 'px;
				line-height: '.esc_html( $defined_line_height*$nectar_h2_phone ) . 'px;
			}
		}';

	 }

} // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Page Heading Font - h3
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['h3_font_style']);

	( intval( substr($nectar_options['h3_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['h3_font_size'],0,-2)) +8 .'px' : $line_height = null;

	if( is_array($nectar_options['h3_font_family']) && 
		$nectar_options['h3_font_family']['attrs_in_use'] ) {

	echo 'body h3,
	.row .col h3,
	.toggle > h3.toggle-title a,
	.ascend #respond h3,
	.ascend h3#comments,
	.woocommerce ul.products li.product.text_on_hover h3,
	.masonry.classic_enhanced .masonry-blog-item h3.title,
	.woocommerce ul.products li.product.material h3,
	.woocommerce-page ul.products li.product.material h3,
	.portfolio-items[data-ps="8"] .col h3,
	.nectar-hor-list-item[data-font-family="h3"],
	.woocommerce ul.products li.product h2, .nectar-quick-view-box h1,
	.nectar-ext-menu-item .inherit-h3 .menu-title-text,
	#slide-out-widget-area .nectar-ext-menu-item .inherit-h3,
	#mobile-menu .nectar-ext-menu-item .inherit-h3,
	#ajax-content-wrap .nectar-inherit-h3,
  #header-outer .nectar-inherit-h3,
	[data-inherit-heading-family="h3"],
	.nectar-quick-view-box div.product h1.product_title.nectar-inherit-h3,
	.nectar-quick-view-box div.product .summary p.price.nectar-inherit-h3,
	body.woocommerce div.product p.price.nectar-inherit-h3 ins { ';

		// Output font properties.
		nectar_output_font_props('h3_font', $line_height, $nectar_options);

	echo ' }';

	// Store calculated line height.
	$the_line_height = nectar_font_line_height('h3_font', $line_height, $nectar_options);

	?>

	@media only screen and (min-width: 1000px) {
		.ascend .comments-section .comment-wrap.full-width-section > h3#comments, .blog_next_prev_buttons[data-post-header-style="default_minimal"] .col h3 {
			font-size: <?php if(!empty($nectar_options['h3_font_size']) && $nectar_options['h3_font_size'] != '-') echo intval($nectar_options['h3_font_size'])*1.7 . 'px' ?>;
			line-height: <?php if(!empty($nectar_options['h3_font_size']) && $nectar_options['h3_font_size'] != '-') echo (intval($nectar_options['h3_font_size'])*1.7) +8 . 'px' ?>;
		}

		#ajax-content-wrap .masonry.classic_enhanced .masonry-blog-item.large_featured h3.title {
			font-size: <?php if(!empty($nectar_options['h3_font_size']) && $nectar_options['h3_font_size'] != '-') echo intval($nectar_options['h3_font_size'])*1.5 . 'px' ?>;
			line-height: <?php if(!empty($nectar_options['h3_font_size']) && $nectar_options['h3_font_size'] != '-') echo intval($the_line_height)*1.5 . 'px' ?>;
		}
	}

	@media only screen and (min-width: 1300px) and (max-width: 1500px){
		body .portfolio-items.constrain-max-cols.masonry-items .col.elastic-portfolio-item h3 {
			font-size: <?php if(!empty($nectar_options['h3_font_size']) && $nectar_options['h3_font_size'] != '-') echo intval($nectar_options['h3_font_size'])*0.85 . 'px' ?>;
			line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.85) . 'px' ?>;
		}
	}

	<?php
		$defined_font_size = (!empty($nectar_options['h3_font_size']) && $nectar_options['h3_font_size'] != '-') ? intval($nectar_options['h3_font_size']) : $nectar_h3_default_size;
		$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_h3_default_size + 10;
	?>

	.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h3"] .content {
		font-size: <?php echo intval($defined_font_size) . 'px'; ?>;
	}

	@media only screen and (max-width: 1300px) and (min-width: 1000px) {
		.row .span_2 h3, .row .span_3 h3, .row .span_4 h3, .row .vc_col-sm-2 h3, .row .vc_col-sm-3 h3, .row .vc_col-sm-4 h3, .row .col h3, body h3, #ajax-content-wrap .nectar-inherit-h3,
		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h3"] .content {
			font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_small_desktop ) . 'px'; ?>;
			line-height: <?php echo esc_html( $defined_line_height*$nectar_h3_small_desktop ) . 'px'; ?>;
		}
	}

	@media only screen and (max-width: 999px) and (min-width: 691px) {
		.row .span_2 h3, .row .span_3 h3, .row .span_4 h3, .row .vc_col-sm-2 h3, .row .vc_col-sm-3 h3, .row .vc_col-sm-4 h3, .row .col h3, body h3, #slide-out-widget-area .nectar-ext-menu-item .inherit-h3,
		#ajax-content-wrap .nectar-inherit-h3,
		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h3"] .content {
			font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_tablet ) . 'px'; ?>;
			line-height: <?php echo esc_html( $defined_line_height*$nectar_h3_tablet ) . 'px'; ?>;
		}
		.wpb_wrapper h3.vc_custom_heading {
			font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_tablet ) . 'px!important'; ?>;
			line-height: <?php echo esc_html( $defined_line_height*$nectar_h3_tablet ) . 'px!important'; ?>;
		}
	}

	@media only screen and (max-width: 690px) {
		.row .span_2 h3, .row .span_3 h3, .row .span_4 h3, .row .vc_col-sm-2 h3, .row .vc_col-sm-3 h3, .row .vc_col-sm-4 h3, .row .col h3, body h3, #slide-out-widget-area .nectar-ext-menu-item .inherit-h3, #mobile-menu .nectar-ext-menu-item .inherit-h3,
		#ajax-content-wrap .nectar-inherit-h3,
		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h3"] .content {
			font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_phone ) . 'px'; ?>;
			line-height: <?php echo esc_html( $defined_line_height*$nectar_h3_phone ) . 'px'; ?>;
		}
		.wpb_wrapper h3.vc_custom_heading {
			font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_phone ) . 'px!important'; ?>;
			line-height: <?php echo esc_html( $defined_line_height*$nectar_h3_phone ) . 'px!important'; ?>;
		}
	}


  @media only screen and (min-width: 1300px) {
    .nectar-post-grid[data-columns="2"][data-masonry="yes"] > div:nth-of-type(3n + 1) h3 {
      font-size: <?php echo esc_html( $defined_font_size*1.4 ) . 'px'; ?>;
    }
  }
  @media only screen and (max-width: 1300px) and (min-width: 1000px) {
    .nectar-post-grid[data-columns="2"][data-masonry="yes"] > div:nth-of-type(3n + 1) h3 {
      font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_small_desktop*1.4 ) . 'px'; ?>;
    }
  }
  @media only screen and (max-width: 999px) and (min-width: 691px) {
    .nectar-post-grid[data-columns="2"][data-masonry="yes"] > div:nth-of-type(3n + 1) h3 {
      font-size: <?php echo esc_html( $defined_font_size*$nectar_h3_tablet*1.4 ) . 'px'; ?>;
    }
  }

<?php

if( $woocommerce && 'h3' === $product_tab_heading_typography ) {

	echo '
	#ajax-content-wrap .related.products > h2,
	#ajax-content-wrap .upsells.products > h2,
	#ajax-content-wrap #tab-reviews #reviews #comments h2,
	.woocommerce .woocommerce-tabs #tab-additional_information h2,
	#ajax-content-wrap #reviews #reply-title,
	.woocommerce-tabs #reviews .nectar-average-count,
	#review_form_wrapper.modal .comment-reply-title,
	.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews { ';

		// Output font properties.
		nectar_output_font_props('h3_font', $line_height, $nectar_options);

	echo ' }

	@media only screen and (max-width: 999px) and (min-width: 691px) {
		#ajax-content-wrap .related.products > h2,
		#ajax-content-wrap .upsells.products > h2,
		#ajax-content-wrap #tab-reviews #reviews #comments h2,
		.woocommerce .woocommerce-tabs #tab-additional_information h2,
		#ajax-content-wrap #reviews #reply-title,
		#review_form_wrapper.modal .comment-reply-title,
		.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
			font-size: '.esc_html( $defined_font_size*$nectar_h3_tablet ) . 'px;
			line-height: '.esc_html( $defined_line_height*$nectar_h3_tablet ) . 'px;
		}
	}
	@media only screen and (max-width: 690px) {
		#ajax-content-wrap .related.products > h2,
		#ajax-content-wrap .upsells.products > h2,
		#ajax-content-wrap #tab-reviews #reviews #comments h2,
		.woocommerce .woocommerce-tabs #tab-additional_information h2,
		#ajax-content-wrap #reviews #reply-title,
		#review_form_wrapper.modal .comment-reply-title,
		.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
			font-size: '.esc_html( $defined_font_size*$nectar_h3_phone ) . 'px;
			line-height: '.esc_html( $defined_line_height*$nectar_h3_phone ) . 'px;
		}
	}';

 }

} // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Page Heading Font - h4
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['h4_font_style']);

	( intval( substr($nectar_options['h4_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['h4_font_size'],0,-2)) +10 .'px' : $line_height = null;


	if( is_array($nectar_options['h4_font_family']) && $nectar_options['h4_font_family']['attrs_in_use'] ) {

		echo 'body h4,
		.row .col h4,
		.portfolio-items .work-meta h4,
		.list-icon-holder[data-icon_type="numerical"] span,
		.portfolio-items .col.span_3 .work-meta h4,
		#respond h3,
		.blog-recent.related-posts h3.title, h3#comments,
		.portfolio-items[data-ps="6"] .work-meta h4,
		.nectar-hor-list-item[data-font-family="h4"],
		.toggles[data-style="minimal_small"] .toggle > h3 a,
		.woocommerce #reviews #reply-title,
		p.woocommerce.add_to_cart_inline > span.woocommerce-Price-amount,
		p.woocommerce.add_to_cart_inline ins > span.woocommerce-Price-amount,
		#header-outer .total, #header-outer .total strong,
		.nectar-ext-menu-item .inherit-h4 .menu-title-text,
		#slide-out-widget-area .nectar-ext-menu-item .inherit-h4,
		#mobile-menu .nectar-ext-menu-item .inherit-h4,
		.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list .product-meta a:not(.remove),
		.woocommerce-cart .product-name a,
		#ajax-content-wrap .nectar-inherit-h4,
    #header-outer .nectar-inherit-h4,
		.archive.woocommerce .container-wrap > .main-content #sidebar > .header h4,
		[data-inherit-heading-family="h4"],
		.nectar-quick-view-box div.product h1.product_title.nectar-inherit-h4,
		.nectar-quick-view-box div.product .summary p.price.nectar-inherit-h4,
		body.woocommerce div.product p.price.nectar-inherit-h4 ins { ';

			// Output font properties.
			nectar_output_font_props('h4_font', $line_height, $nectar_options);

		echo ' }';

		// Store calculated line height.
		$the_line_height = nectar_font_line_height('h4_font', $line_height, $nectar_options);

    $font_family = (1 === preg_match('~[0-9]~', $nectar_options['h4_font'])) ? '"'. $nectar_options['h4_font'] .'"' : $nectar_options['h4_font'];


		if(!empty($nectar_options['h4_font_size']) && $nectar_options['h4_font_size'] != '-') {
			?>

			@media only screen and (min-width: 691px) {
				.portfolio-items[data-ps="6"] .wide_tall .work-meta h4 {
					font-size: <?php if(!empty($nectar_options['h4_font_size']) && $nectar_options['h4_font_size'] != '-') echo intval($nectar_options['h4_font_size'])*1.7 . 'px!important' ?>;
					line-height: <?php if(!empty($nectar_options['h4_font_size']) && $nectar_options['h4_font_size'] != '-') echo (intval($nectar_options['h4_font_size'])*1.7) +8 . 'px!important' ?>;
				}

				.nectar-slide-in-cart .widget_shopping_cart .cart_list .mini_cart_item > a:not(.remove) {
					<?php if($nectar_options['h4_font'] != '-') echo 'font-family: ' . $font_family .'!important;';
					if(!empty($styles[0]) && $styles[0] == 'regular') $styles[0] = '400';
					if(!empty($styles[0]) && strpos($styles[0],'italic') === false) { echo 'font-weight:' .  $styles[0] .'!important;'; } ?>
				}

			}

		<?php
		}
			$defined_font_size   = (!empty($nectar_options['h4_font_size']) && $nectar_options['h4_font_size'] != '-') ? intval($nectar_options['h4_font_size']) : $nectar_h4_default_size;
			$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_h4_default_size + 10;
		?>

		.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h4"] .content {
			font-size: <?php echo intval($defined_font_size) . 'px'; ?>;
		}

		@media only screen and (max-width: 1300px) and (min-width: 1000px) {
			.row .col h4, body h4, .woocommerce-cart .product-name a, #ajax-content-wrap .nectar-inherit-h4,
			.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h4"] .content {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h4_small_desktop ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h4_small_desktop ) . 'px'; ?>;
			}
		}

		@media only screen and (max-width: 999px) and (min-width: 691px) {
			.row .col h4, body h4, #slide-out-widget-area .nectar-ext-menu-item .inherit-h4,
			.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list .product-meta a:not(.remove),
			.woocommerce-cart .product-name a,
			#ajax-content-wrap .nectar-inherit-h4,
			.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h4"] .content {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h4_tablet ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h4_tablet ) . 'px'; ?>;
			}
		}

		@media only screen and (max-width: 690px) {
			.row .col h4, body h4, #slide-out-widget-area .nectar-ext-menu-item .inherit-h4,
			.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list .product-meta a:not(.remove),
			.woocommerce-cart .product-name a,
			#ajax-content-wrap .nectar-inherit-h4,
			.archive.woocommerce .container-wrap > .main-content #sidebar > .header h4,
			.nectar-category-grid[data-style="mouse_follow_image"][data-h-tag="h4"] .content {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h4_phone ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h4_phone ) . 'px'; ?>;
			}
		}

	<?php
	if( $woocommerce && 'h4' === $product_tab_heading_typography ) {

		echo '
		#ajax-content-wrap .related.products > h2,
		#ajax-content-wrap .upsells.products > h2,
		#ajax-content-wrap #tab-reviews #reviews #comments h2,
		.woocommerce .woocommerce-tabs #tab-additional_information h2,
		#ajax-content-wrap #reviews #reply-title,
		.woocommerce-tabs #reviews .nectar-average-count,
		#review_form_wrapper.modal .comment-reply-title,
		.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews { ';

			// Output font properties.
			nectar_output_font_props('h4_font', $line_height, $nectar_options);

		echo ' }

		@media only screen and (max-width: 999px) and (min-width: 691px) {
			#ajax-content-wrap .related.products > h2,
			#ajax-content-wrap .upsells.products > h2,
			#ajax-content-wrap #tab-reviews #reviews #comments h2,
			.woocommerce .woocommerce-tabs #tab-additional_information h2,
			#ajax-content-wrap #reviews #reply-title,
			#review_form_wrapper.modal .comment-reply-title,
			.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
				font-size: '.esc_html( $defined_font_size*$nectar_h4_tablet ) . 'px;
				line-height: '.esc_html( $defined_line_height*$nectar_h4_tablet ) . 'px;
			}
		}
		@media only screen and (max-width: 690px) {
			#ajax-content-wrap .related.products > h2,
			#ajax-content-wrap .upsells.products > h2,
			#ajax-content-wrap #tab-reviews #reviews #comments h2,
			.woocommerce .woocommerce-tabs #tab-additional_information h2,
			#ajax-content-wrap #reviews #reply-title,
			#review_form_wrapper.modal .comment-reply-title,
			.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
				font-size: '.esc_html( $defined_font_size*$nectar_h4_phone ) . 'px;
				line-height: '.esc_html( $defined_line_height*$nectar_h4_phone ) . 'px;
			}
		}';

	 }

} // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Page Heading Font - h5
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['h5_font_style']);

	( intval( substr($nectar_options['h5_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['h5_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['h5_font_family']) && $nectar_options['h5_font_family']['attrs_in_use'] ) {

		echo 'body h5,
		.row .col h5,
		.portfolio-items .work-item.style-3-alt p,
		.nectar-hor-list-item[data-font-family="h5"],
		.nectar-ext-menu-item .inherit-h5 .menu-title-text,
		#slide-out-widget-area .nectar-ext-menu-item .inherit-h5,
		#ajax-content-wrap .nectar-inherit-h5,
    #header-outer .nectar-inherit-h5,
		[data-inherit-heading-family="h5"],
		.nectar-quick-view-box div.product h1.product_title.nectar-inherit-h5,
		.nectar-quick-view-box div.product .summary p.price.nectar-inherit-h5,
		body.woocommerce div.product p.price.nectar-inherit-h5 ins { ';

			// Output font properties.
			nectar_output_font_props('h5_font', $line_height, $nectar_options);

		echo ' }';

		// Store calculated line height.
		$the_line_height = nectar_font_line_height('h5_font', $line_height, $nectar_options);

		
		if( !empty($nectar_options['h5_font_size']) && $nectar_options['h5_font_size'] != '-')  {
			echo 'body .wpb_column > .wpb_wrapper > .morphing-outline .inner > h5 {
				font-size: '.ceil(intval($nectar_options['h5_font_size'])*1.35) . 'px'.'
			}';
		}

		
			$defined_font_size = (!empty($nectar_options['h5_font_size']) && $nectar_options['h5_font_size'] != '-') ? intval($nectar_options['h5_font_size']) : $nectar_h5_default_size;
			$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_h5_default_size + 10;
		?>

		@media only screen and (max-width: 1300px) and (min-width: 1000px) {
			.row .col h5, body h5, #ajax-content-wrap .nectar-inherit-h5 {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h5_small_desktop ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h5_small_desktop ) . 'px'; ?>;
			}
		}

		@media only screen and (max-width: 999px) and (min-width: 691px) {
			.row .col h5, body h5, #ajax-content-wrap .nectar-inherit-h5 {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h5_tablet ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h5_tablet ) . 'px'; ?>;
			}
		}

		@media only screen and (max-width: 690px) {
			.row .col h5, body h5, #slide-out-widget-area .nectar-ext-menu-item .inherit-h5, #ajax-content-wrap .nectar-inherit-h5 {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h5_phone ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h5_phone ) . 'px'; ?>;
			}
		}

	<?php
	if( $woocommerce && 'h5' === $product_tab_heading_typography ) {

		echo '
		#ajax-content-wrap .related.products > h2,
		#ajax-content-wrap .upsells.products > h2,
		.woocommerce .woocommerce-tabs #tab-additional_information h2,
		#ajax-content-wrap #tab-reviews #reviews #comments h2,
		#ajax-content-wrap #reviews #reply-title,
		.woocommerce-tabs #reviews .nectar-average-count,
		#review_form_wrapper.modal .comment-reply-title,
		.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews { ';

			// Output font properties.
			nectar_output_font_props('h5_font', $line_height, $nectar_options);

		echo ' }

		@media only screen and (max-width: 999px) and (min-width: 691px) {
			#ajax-content-wrap .related.products > h2,
			#ajax-content-wrap .upsells.products > h2,
			.woocommerce .woocommerce-tabs #tab-additional_information h2,
			#ajax-content-wrap #tab-reviews #reviews #comments h2,
			#ajax-content-wrap #reviews #reply-title,
			#review_form_wrapper.modal .comment-reply-title,
			.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
				font-size: '.esc_html( $defined_font_size*$nectar_h5_tablet ) . 'px;
				line-height: '.esc_html( $defined_line_height*$nectar_h5_tablet ) . 'px;
			}
		}
		@media only screen and (max-width: 690px) {
			#ajax-content-wrap .related.products > h2,
			#ajax-content-wrap .upsells.products > h2,
			.woocommerce .woocommerce-tabs #tab-additional_information h2,
			#ajax-content-wrap #tab-reviews #reviews #comments h2,
			#ajax-content-wrap #reviews #reply-title,
			#review_form_wrapper.modal .comment-reply-title,
			.woocommerce-tabs[data-tab-style="fullwidth_stacked"] p.woocommerce-noreviews {
				font-size: '.esc_html( $defined_font_size*$nectar_h5_phone ) . 'px;
				line-height: '.esc_html( $defined_line_height*$nectar_h5_phone ) . 'px;
			}
		}';

	 }

} // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Page Heading Font - h6
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['h6_font_style']);

	( intval( substr($nectar_options['h6_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['h6_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['h6_font_family']) && $nectar_options['h6_font_family']['attrs_in_use'] ) {

		echo 'body h6,
		.row .col h6,
		.nectar-hor-list-item[data-font-family="h6"],
		.nectar-ext-menu-item .inherit-h6 .menu-title-text,
		#slide-out-widget-area .nectar-ext-menu-item .inherit-h6,
		#ajax-content-wrap .nectar-inherit-h6,
    #header-outer .nectar-inherit-h6,
		[data-inherit-heading-family="h6"],
		.nectar-quick-view-box div.product .summary p.price.nectar-inherit-h6,
		body.woocommerce div.product p.price.nectar-inherit-h6 ins { ';

			// Output font properties.
			nectar_output_font_props('h6_font', $line_height, $nectar_options);

		echo ' }';

		// Store calculated line height.
		$the_line_height = nectar_font_line_height('h6_font', $line_height, $nectar_options);

		$defined_font_size   = (!empty($nectar_options['h6_font_size']) && $nectar_options['h6_font_size'] != '-') ? intval($nectar_options['h6_font_size']) : $nectar_h6_default_size;
		$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_h6_default_size + 10;

		?>

		@media only screen and (max-width: 1300px) and (min-width: 1000px) {
			.row .col h6, body h6, #ajax-content-wrap .nectar-inherit-h6 {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h6_small_desktop ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h6_small_desktop ) . 'px'; ?>;
			}
		}

		@media only screen and (max-width: 999px) and (min-width: 691px) {
			.row .col h6, body h6, #ajax-content-wrap .nectar-inherit-h6 {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h6_tablet ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h6_tablet ) . 'px'; ?>;
			}
		}

		@media only screen and (max-width: 690px) {
			.row .col h6, body h6, #ajax-content-wrap .nectar-inherit-h6 {
				font-size: <?php echo esc_html( $defined_font_size*$nectar_h6_phone ) . 'px'; ?>;
				line-height: <?php echo esc_html( $defined_line_height*$nectar_h6_phone ) . 'px'; ?>;
			}
		}

	<?php } // attrs in use ?>


	<?php
	/*-------------------------------------------------------------------------*/
	/*	Italic Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['i_font_style']);

	( intval( substr($nectar_options['i_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['i_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['i_font_family']) && $nectar_options['i_font_family']['attrs_in_use'] ) {

		echo 'body i, 
		body em, 
		.masonry.meta_overlaid article.post .post-header .meta-author > span, 
		.post-area.masonry.meta_overlaid article.post .post-meta .date,
		.post-area.masonry.meta_overlaid article.post.quote .quote-inner .author, 
		.post-area.masonry.meta_overlaid  article.post.link .post-content .destination,
		body .testimonial_slider[data-style="minimal"] blockquote span.title,
		#ajax-content-wrap .nectar-inherit-italic { ';

			// Output font properties.
			nectar_output_font_props('i_font', $line_height, $nectar_options);

		echo ' }'; ?>

		<?php } // attrs in use ?>


  <?php
	/*-------------------------------------------------------------------------*/
	/*	Bold Font
	/*-------------------------------------------------------------------------*/

  $nectar_bold_font_family = false;
  if( isset($nectar_options['bold_font_family']['font-family']) &&
      !empty($nectar_options['bold_font_family']['font-family']) ) {
    $nectar_bold_font_family = $nectar_options['bold_font_family']['font-family'];
  }
  $nectar_bold_font_weight = false;
  if( isset($nectar_options['bold_font_family']['font-weight']) &&
      !empty($nectar_options['bold_font_family']['font-weight']) ) {
    $nectar_bold_font_weight = $nectar_options['bold_font_family']['font-weight'];
  }

	if( $nectar_bold_font_weight || $nectar_bold_font_family ) {

		echo 'body b, body strong, body .bold {';
			if( $nectar_bold_font_family ) {
        echo 'font-family: '.esc_attr($nectar_bold_font_family).';';
      }
      if( $nectar_bold_font_weight ) {
        echo 'font-weight: '.intval($nectar_bold_font_weight).';';
      }
		echo '}';

		}  ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Form Label Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['label_font_style']);

	( intval( substr($nectar_options['label_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['label_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['label_font_family']) && $nectar_options['label_font_family']['attrs_in_use'] ) {

		echo 'form label,
		.woocommerce-checkout-review-order-table .product-info .amount,
		.nectar-progress-bar p,
		.nectar-progress-bar span strong i,
		.nectar-progress-bar span strong,
		body.material .nectar_single_testimonial[data-style="basic"] span.wrap,
		body.material .nectar_single_testimonial[data-style="basic_left_image"] span.wrap,
		.testimonial_slider:not([data-style="minimal"]) blockquote span,
		.woocommerce-ordering .select2-container--default .select2-selection--single .select2-selection__rendered,
		.woocommerce-ordering .select2-container .select2-choice>.select2-chosen,
		body[data-fancy-form-rcs="1"] .woocommerce-ordering select,
		.tabbed[data-style="minimal_alt"] > ul li a,
		.material .widget .nectar_widget[class*="nectar_blog_posts_"] > li .post-title,
		body.material .tagcloud a,
    	.material .main-content .widget li a,
    	.material #footer-outer .widget li a,
		.nectar-recent-posts-slider_multiple_visible
		.recent-post-container.container .strong a,
		.material .recentcomments .comment-author-link,
		.single .post-area .content-inner > .post-tags a,
		.masonry.material .masonry-blog-item .grav-wrap a,
		.masonry.material .masonry-blog-item .meta-category a,
		.post-area.featured_img_left article .meta-category a,
		.post-area.featured_img_left article .grav-wrap .text a,
		.related-posts[data-style="material"] .meta-category a,
		.masonry.auto_meta_overlaid_spaced article.post.quote .author,
		.masonry.material article.post.quote .author,
    	.nectar-post-grid-wrap[data-style="vertical_list"] .nectar-link-underline,
    	.nectar-post-grid.vert_list_counter .item-main:before,
		body.search-results #search-results[data-layout="list-no-sidebar"] .result .inner-wrap h2 span,
		.material .tabbed >ul li a,
		.post-area.featured_img_left article.post.quote .author,
		.single .post.format-quote .author,
		.related-posts[data-style="material"] .grav-wrap .text a,
		.auto_meta_overlaid_spaced .masonry-blog-item .meta-category a,
		[data-style="list_featured_first_row"] .meta-category a,
		.nectar-recent-posts-single_featured .strong a,
		.nectar-recent-posts-single_featured.multiple_featured .controls li .title,
		body .woocommerce .nectar-woo-flickity[data-controls="arrows-and-text"] .woo-flickity-count,
		body.woocommerce ul.products li.minimal.product span.onsale,
		.nectar-ajax-search-results ul.products li.minimal.product span.onsale,
		.nectar-woo-flickity ul.products li.minimal.product span.onsale,
		.nectar-quick-view-box span.onsale,
		.nectar-quick-view-box .nectar-full-product-link a,
		body .nectar-quick-view-box .single_add_to_cart_button,
		.nectar-quick-view-box .single_add_to_cart_button,
		body .cart .quantity input.qty,
	  	body .cart .quantity input.plus,
		body .cart .quantity input.minus,
		body .woocommerce-mini-cart .quantity input.qty,
	  	body .woocommerce-mini-cart .quantity input.plus,
		body .woocommerce-mini-cart .quantity input.minus,
		.style_slide_in_click .product-meta > .quantity .amount,
		.pum-theme-salient-page-builder-optimized .pum-container .pum-content+.pum-close,
		.woocommerce-account .woocommerce-form-login .lost_password,
		.woocommerce div.product .woocommerce-tabs .full-width-content[data-tab-style="fullwidth"] ul.tabs li a,
		.woocommerce div.product_meta,
		.woocommerce table.shop_table th,
		#header-outer .widget_shopping_cart .cart_list a,
		.woocommerce .yith-wcan-reset-navigation.button,
		.single-product .entry-summary p.stock.out-of-stock,
    	.nectar-post-grid .nectar-post-grid-item .content .meta-category a,
		.nectar-post-grid-item .item-main > .meta-author .meta-author-name,
		.nectar-post-grid-item .post-heading-wrap .meta-author-name,
		.nectar-slide-in-cart.style_slide_in_click ul.product_list_widget li dl dt,
		.woocommerce-tabs ol.commentlist li .comment-text p.meta strong,
		#ajax-content-wrap .nectar-inherit-label
		{ ';

			// Output font properties.
			nectar_output_font_props('label_font', $line_height, $nectar_options);

		echo ' }'; 
		
		// Label without sizing.
		echo '.material .comment-list .reply a,
			  .nectar-recent-posts-single_featured .grav-wrap a,
			  .wc-blocks-filter-wrapper .wc-block-components-checkbox,
			  .wc-block-product-categories-list-item-count,
			  .comment-list .comment-author, 
			  .comment-list .pingback .comment-body > a,
			  .netar-inherit-label-font--simple {';
			nectar_output_font_props('label_font', 'bypass', $nectar_options, 'bypass');
		echo '}';

		?>

	<?php } // attrs in use ?>

	<?php 
	/*-------------------------------------------------------------------------*/
	/*	Blog Single Content Area Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['blog_single_post_content_font_style']);

	( intval( substr($nectar_options['blog_single_post_content_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['blog_single_post_content_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( isset($nectar_options['blog_single_post_content_font_family']) && 
		is_array($nectar_options['blog_single_post_content_font_family']) && 
		$nectar_options['blog_single_post_content_font_family']['attrs_in_use'] ) {

		echo '.single-post .post-area .post-content { ';

			// Output font properties.
			nectar_output_font_props('blog_single_post_content_font', $line_height, $nectar_options);

		echo '}'; 
		
		if( !empty($nectar_options['blog_single_post_content_font_size']) && 
			$nectar_options['blog_single_post_content_font_size'] != '-' && 
			intval($nectar_options['blog_single_post_content_font_size']) >= 20 ) {
				echo '@media only screen and (max-width: 690px) {
					.single-post .post-area .post-content { 
						font-size: 18px;
						line-height: 1.55;
					}
				}';
		}
		
		?>

   <?php } // attrs in use ?>


	<?php
	/*-------------------------------------------------------------------------*/
	/*	Portfolio Filter Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['portfolio_filters_font_style']);

	( intval( substr($nectar_options['portfolio_filters_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['portfolio_filters_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( isset($nectar_options['portfolio_filters_font_family']) &&
		is_array($nectar_options['portfolio_filters_font_family']) &&
		$nectar_options['portfolio_filters_font_family']['attrs_in_use'] ) {

		echo '.portfolio-filters-inline .container > ul a,
    .portfolio-filters > ul a,
    .portfolio-filters > a span,
    .nectar-post-grid-filters a { ';

			// Output font properties.
			nectar_output_font_props('portfolio_filters_font', $line_height, $nectar_options);

		echo ' }';

		// Store calculated line height.
		$the_line_height = nectar_font_line_height('portfolio_filters_font', $line_height, $nectar_options);

		if( $the_line_height !== null ) { echo '.portfolio-filters-inline #current-category { line-height: '.esc_attr($the_line_height).'; }'; } ?>

	<?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Portfolio Captions/Excerpts Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['portfolio_caption_font_style']);

	( intval( substr($nectar_options['portfolio_caption_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['portfolio_caption_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( isset($nectar_options['portfolio_caption_font_family']) &&
		is_array($nectar_options['portfolio_caption_font_family']) &&
		$nectar_options['portfolio_caption_font_family']['attrs_in_use'] ) {

		echo '.portfolio-items .col p, .container-wrap[data-nav-pos="after_project_2"] .bottom_controls li span:not(.text) { ';

			// Output font properties.
			nectar_output_font_props('portfolio_caption_font', $line_height, $nectar_options);

		echo ' }'; ?>

   <?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Dropcap Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['nectar_dropcap_font_style']);

	( intval( substr($nectar_options['nectar_dropcap_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['nectar_dropcap_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['nectar_dropcap_font_family']) && $nectar_options['nectar_dropcap_font_family']['attrs_in_use'] ) {

		echo '.nectar-dropcap { ';

			// Output font properties.
			nectar_output_font_props('nectar_dropcap_font', $line_height, $nectar_options);

		echo ' }'; ?>

  <?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Sidebar/Footer Header Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['nectar_sidebar_footer_headers_font_style']);

	( intval( substr($nectar_options['nectar_sidebar_footer_headers_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['nectar_sidebar_footer_headers_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( isset($nectar_options['nectar_sidebar_footer_headers_font_family']) &&
		is_array($nectar_options['nectar_sidebar_footer_headers_font_family']) && 
	   $nectar_options['nectar_sidebar_footer_headers_font_family']['attrs_in_use'] ) {

		echo 'body #sidebar h4, body .widget h4, body #footer-outer .widget h4
		{ ';

			// Output font properties.
			nectar_output_font_props('nectar_sidebar_footer_headers_font', $line_height, $nectar_options);

		echo ' }'; ?>

	<?php } // attrs in use ?>

	<?php

	/*-------------------------------------------------------------------------*/
	/*	Page Header Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['page_heading_font_style']);

	( intval( substr($nectar_options['page_heading_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['page_heading_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['page_heading_font_family']) && 
		$nectar_options['page_heading_font_family']['attrs_in_use'] ) {

			echo 'body #page-header-bg h1, html body .row .col.section-title h1, .nectar-box-roll .overlaid-content h1, .featured-media-under-header h1 { ';

				// Output font properties.
				nectar_output_font_props('page_heading_font', $line_height, $nectar_options);

			echo ' }';

			// Store calculated line height.
			$the_line_height = nectar_font_line_height('page_heading_font', $line_height, $nectar_options);

			?>

			@media only screen and (min-width: 691px) and (max-width: 999px) {
				.overlaid-content h1 {
					font-size: <?php if(!empty($nectar_options['page_heading_font_size']) && $nectar_options['page_heading_font_size'] != '-') echo intval($nectar_options['page_heading_font_size'])*0.7 . 'px!important' ?>;
					line-height: <?php if(!empty($nectar_options['page_heading_font_size']) && $nectar_options['page_heading_font_size'] != '-') echo (intval($nectar_options['page_heading_font_size'])*0.7) + 4 . 'px!important' ?>;
				}
			}

			@media only screen and (min-width: 1000px) and (max-width: 1300px) {
				#page-header-bg .span_6 h1, .nectar-box-roll .overlaid-content h1, body .featured-media-under-header h1 {
					font-size: <?php if(!empty($nectar_options['page_heading_font_size']) && $nectar_options['page_heading_font_size'] != '-') echo intval($nectar_options['page_heading_font_size'])*0.85 . 'px' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.85) . 'px' ?>;
				}
			}

			@media only screen and (min-width: 1300px) and (max-width: 1500px) {
				#page-header-bg .span_6 h1, .nectar-box-roll .overlaid-content h1 {
					font-size: <?php if(!empty($nectar_options['page_heading_font_size']) && $nectar_options['page_heading_font_size'] != '-') echo intval($nectar_options['page_heading_font_size'])*0.9 . 'px' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.9) . 'px' ?>;
				}
			}

			@media only screen and (max-width: 690px) {
				.overlaid-content h1 {
					font-size: <?php if(!empty($nectar_options['page_heading_font_size']) && $nectar_options['page_heading_font_size'] != '-') echo intval($nectar_options['page_heading_font_size'])*0.45 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.45) . 'px!important' ?>;
				}
			}

	<?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Page Header Subtitle Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['page_heading_subtitle_font_style']);

	( intval( substr($nectar_options['page_heading_subtitle_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['page_heading_subtitle_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['page_heading_subtitle_font_family']) && 
		$nectar_options['page_heading_subtitle_font_family']['attrs_in_use'] ) {

			echo 'body #page-header-bg .span_6 span.subheader, 
			#page-header-bg span.result-num,
			body .row .col.section-title > span, 
			.page-header-no-bg .col.section-title h1 > span,
			.nectar-box-roll .overlaid-content .subheader { ';

				// Output font properties.
				nectar_output_font_props('page_heading_subtitle_font', $line_height, $nectar_options);

			echo ' }';

			// Store calculated line height.
			$the_line_height = nectar_font_line_height('page_heading_subtitle_font', $line_height, $nectar_options);

			?>

			@media only screen and (min-width: 1000px) and (max-width: 1300px) {
				body #page-header-bg:not(.fullscreen-header) .span_6 span.subheader, body .row .col.section-title > span {
					font-size: <?php if(!empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] != '-') echo intval($nectar_options['page_heading_subtitle_font_size'])*0.9 . 'px' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.9) . 'px' ?>;
				}
			}

			@media only screen and (min-width: 691px) and (max-width: 999px) {
				body #page-header-bg.fullscreen-header .span_6 span.subheader, .overlaid-content .subheader {
					font-size: <?php if(!empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] != '-') echo intval($nectar_options['page_heading_subtitle_font_size'])*0.8 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.8) . 'px!important' ?>;
				}

				<?php if(!empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] != '-' && $nectar_options['page_heading_subtitle_font_size'] > 20) { ?>
					#page-header-bg .span_6 span.subheader {
				  		font-size: 20px!important;
							line-height: 1.6!important;
				  }
			  	<?php } else if( empty($nectar_options['page_heading_subtitle_font_size']) || !empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] == '-' ) { ?>
				  	#page-header-bg .span_6 span.subheader {
				  		font-size: 20px!important;
							line-height: 1.6!important;
				  	}
				 <?php } ?>
			}

			@media only screen and (max-width: 690px) {
				body #page-header-bg.fullscreen-header .span_6 span.subheader, .overlaid-content .subheader {
					font-size: <?php if(!empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] != '-') echo intval($nectar_options['page_heading_subtitle_font_size'])*0.7 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.7) . 'px!important' ?>;
				}

				<?php if(!empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] != '-' && $nectar_options['page_heading_subtitle_font_size'] > 16) { ?>
					#page-header-bg .span_6 span.subheader {
				  		font-size: 16px!important;
							line-height: 1.6!important;
				  }
			  	<?php } else if( empty($nectar_options['page_heading_subtitle_font_size']) || !empty($nectar_options['page_heading_subtitle_font_size']) && $nectar_options['page_heading_subtitle_font_size'] == '-' ) { ?>
			  		#page-header-bg .span_6 span.subheader {
				  		font-size: 16px!important;
							line-height: 1.6!important;
				  	}
				 <?php } ?>
			}

  <?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Off Canvas Navigation Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['off_canvas_nav_font_style']);

	( intval( substr($nectar_options['off_canvas_nav_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['off_canvas_nav_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['off_canvas_nav_font_family']) && 
		$nectar_options['off_canvas_nav_font_family']['attrs_in_use'] ) {

			echo 'body #slide-out-widget-area .inner .off-canvas-menu-container li a,
			body #slide-out-widget-area.fullscreen .inner .off-canvas-menu-container li a,
			body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a,
			body #slide-out-widget-area.slide-out-from-right-hover .inner .off-canvas-menu-container li a,
			body #nectar-ocm-ht-line-check { ';

				// Output font properties.
				nectar_output_font_props('off_canvas_nav_font', $line_height, $nectar_options);

			echo ' }';

			// Store calculated line height.
			$the_line_height = nectar_font_line_height('off_canvas_nav_font', $line_height, $nectar_options);

			?>

			@media only screen and (min-width: 691px) and (max-width: 999px) {
				body #slide-out-widget-area.fullscreen .inner .off-canvas-menu-container li a,
        #slide-out-widget-area.fullscreen-split .off-canvas-menu-container > ul > li > a,
				body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a  {
					font-size: <?php if(!empty($nectar_options['off_canvas_nav_font_size']) && $nectar_options['off_canvas_nav_font_size'] != '-') echo intval($nectar_options['off_canvas_nav_font_size'])*0.9 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.9) . 'px!important' ?>;
				}
			}

			@media only screen and (max-width: 690px) {
				body #slide-out-widget-area.fullscreen .inner .off-canvas-menu-container li a,
        #slide-out-widget-area.fullscreen-split .off-canvas-menu-container > ul > li > a,
				body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a {
					font-size: <?php if(!empty($nectar_options['off_canvas_nav_font_size']) && $nectar_options['off_canvas_nav_font_size'] != '-') echo intval($nectar_options['off_canvas_nav_font_size'])*0.7 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.7) . 'px!important' ?>;
				}
			}

			<?php
				if(!empty($nectar_options['off_canvas_nav_font_size']) && $nectar_options['off_canvas_nav_font_size'] != '-' && intval($nectar_options['off_canvas_nav_font_size']) < 25) {
					echo 'body.material #slide-out-widget-area.slide-out-from-right  .off-canvas-menu-container li li a,
					#slide-out-widget-area[data-dropdown-func="separate-dropdown-parent-link"]  .off-canvas-menu-container li li a { font-size: '. intval($nectar_options['off_canvas_nav_font_size'])*0.7 .'px; line-height: '. intval($nectar_options['off_canvas_nav_font_size'])*0.7 .'px; }';
				}
			?>

  <?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Off Canvas Navigation Font Subtext
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['off_canvas_nav_subtext_font_style']);

	( intval( substr($nectar_options['off_canvas_nav_subtext_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['off_canvas_nav_subtext_font_size'],0,-2)) +10 .'px' : $line_height = null;

	if( is_array($nectar_options['off_canvas_nav_subtext_font_family']) && 
		$nectar_options['off_canvas_nav_subtext_font_family']['attrs_in_use'] ) {

			echo 'body #slide-out-widget-area .menuwrapper li small,
      #header-outer .sf-menu li ul li a .item_desc,
      #slide-out-widget-area.fullscreen-split .off-canvas-menu-container li small,
			#slide-out-widget-area .off-canvas-menu-container .nectar-ext-menu-item .item_desc,
      .material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container .menu li small,
			#header-outer #mobile-menu ul ul > li > a .item_desc,
			.nectar-ext-menu-item .menu-item-desc,
			#slide-out-widget-area .inner .off-canvas-menu-container li a .item_desc { ';

				// Output font properties.
				nectar_output_font_props('off_canvas_nav_subtext_font', $line_height, $nectar_options);

			echo ' }';

			// Store calculated line height.
			$the_line_height = nectar_font_line_height('off_canvas_nav_subtext_font', $line_height, $nectar_options);

			?>

			@media only screen and (min-width: 691px) and (max-width: 999px) {
				#slide-out-widget-area .menuwrapper li small {
					font-size: <?php if(!empty($nectar_options['off_canvas_nav_subtext_font_size']) && $nectar_options['off_canvas_nav_subtext_font_size'] != '-') echo intval($nectar_options['off_canvas_nav_subtext_font_size'])*0.9 . 'px' ?>;
					line-height: <?php if($the_line_height) echo intval($the_line_height)*0.9 . 'px' ?>;
				}
			}

			@media only screen and (max-width: 690px) {
				#slide-out-widget-area .menuwrapper li small {
					font-size: <?php if(!empty($nectar_options['off_canvas_nav_subtext_font_size']) && $nectar_options['off_canvas_nav_subtext_font_size'] != '-') echo intval($nectar_options['off_canvas_nav_subtext_font_size'])*0.7 . 'px' ?>;
					line-height: <?php if($the_line_height) echo intval($the_line_height)*0.7 . 'px' ?>;
				}
			}

	<?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Nectar Slider Heading Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['nectar_slider_heading_font_style']);
	( intval( substr($nectar_options['nectar_slider_heading_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['nectar_slider_heading_font_size'],0,-2)) + 19 .'px!important' : $line_height = null;

	if( is_array($nectar_options['nectar_slider_heading_font_family']) && 
		$nectar_options['nectar_slider_heading_font_family']['attrs_in_use'] ) {

			echo '.swiper-slide .content h2 { ';

				// Output font properties.
				nectar_output_font_props('nectar_slider_heading_font', $line_height, $nectar_options);

			echo ' }';

			// Store calculated line height.
			$the_line_height = nectar_font_line_height('nectar_slider_heading_font', $line_height, $nectar_options);

			?>

			@media only screen and (min-width: 1000px) and (max-width: 1300px) {
				body .nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2,
        body .full-width-content .vc_col-sm-12 .nectar-slider-wrap .swiper-slide .content h2,
				body .nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
				body .full-width-content .vc_span12 .swiper-slide .content h2 {
					font-size: <?php if(!empty($nectar_options['nectar_slider_heading_font_size']) && $nectar_options['nectar_slider_heading_font_size'] != '-') echo intval($nectar_options['nectar_slider_heading_font_size'])*0.8 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.8) . 'px!important' ?>;
				}
			}

			@media only screen and (min-width: 691px) and (max-width: 999px) {
				body .nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2,
        body .full-width-content .vc_col-sm-12 .nectar-slider-wrap .swiper-slide .content h2,
				body .nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
				body .full-width-content .vc_span12 .swiper-slide .content h2 {
					font-size: <?php if(!empty($nectar_options['nectar_slider_heading_font_size']) && $nectar_options['nectar_slider_heading_font_size'] != '-') echo intval($nectar_options['nectar_slider_heading_font_size'])*0.6 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.6) . 'px!important' ?>;
				}
			}

			@media only screen and (max-width: 690px) {
				body .nectar-slider-wrap[data-full-width="true"] .swiper-slide .content h2,
        body .full-width-content .vc_col-sm-12 .nectar-slider-wrap .swiper-slide .content h2,
				body .nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content h2,
				body .full-width-content .vc_span12 .swiper-slide .content h2 {
					font-size: <?php if(!empty($nectar_options['nectar_slider_heading_font_size']) && $nectar_options['nectar_slider_heading_font_size'] != '-') echo intval($nectar_options['nectar_slider_heading_font_size'])*0.5 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo (intval($the_line_height)*0.5) . 'px!important' ?>;
				}
			}

	<?php } // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Nectar/Home Slider Caption
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['home_slider_caption_font_style']);
	( intval( substr($nectar_options['home_slider_caption_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['home_slider_caption_font_size'],0,-2)) + 19 .'px!important' : $line_height = null;

	if( is_array($nectar_options['home_slider_caption_font_family']) &&
		$nectar_options['home_slider_caption_font_family']['attrs_in_use'] ) {

			echo '#featured article .post-title h2 span, .swiper-slide .content p, body .vc_text_separator div { ';

				// Output font properties.
				nectar_output_font_props('home_slider_caption_font', $line_height, $nectar_options);

			echo ' }'; ?>

			<?php

			  echo '#portfolio-filters-inline ul { line-height: '.esc_attr($line_height).'; }';
			  echo '.swiper-slide .content p.transparent-bg span { '; $nectar_slider_line_height_2 = intval(substr($nectar_options["home_slider_caption_font_size"],0,-2)) + 25;

			 if( !empty($line_height) ) {
				 echo 'line-height:' . esc_attr($nectar_slider_line_height_2) .'px;';
			 }

			 // Store calculated line height.
	 		$the_line_height = nectar_font_line_height('home_slider_caption_font', $line_height, $nectar_options);

			echo '}'; ?>

			@media only screen and (min-width: 1000px) and (max-width: 1300px) {
				.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p,
				.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p,
				.full-width-content .vc_span12 .swiper-slide .content p {
					font-size: <?php if(!empty($nectar_options['home_slider_caption_font_size']) && $nectar_options['home_slider_caption_font_size'] != '-') echo intval($nectar_options['home_slider_caption_font_size'])*0.8 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo intval($the_line_height)*0.8 . 'px!important' ?>;
				}
			}

			@media only screen and (min-width: 691px) and (max-width: 999px) {
				.nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p,
				.nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p,
				.full-width-content .vc_span12 .swiper-slide .content p {
					font-size: <?php if(!empty($nectar_options['home_slider_caption_font_size']) && $nectar_options['home_slider_caption_font_size'] != '-') echo intval($nectar_options['home_slider_caption_font_size'])*0.7 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo intval($the_line_height)*0.7 . 'px!important' ?>;
				}
			}

			@media only screen and (max-width: 690px) {
				body .nectar-slider-wrap[data-full-width="true"] .swiper-slide .content p,
				body .nectar-slider-wrap[data-full-width="boxed-full-width"] .swiper-slide .content p,
				body .full-width-content .vc_span12 .swiper-slide .content p {
					font-size: <?php if(!empty($nectar_options['home_slider_caption_font_size']) && $nectar_options['home_slider_caption_font_size'] != '-') echo intval($nectar_options['home_slider_caption_font_size'])*0.7 . 'px!important' ?>;
					line-height: <?php if($the_line_height) echo intval($the_line_height)*0.7 . 'px!important' ?>;
				}
			}

	<?php } // attrs in use ?>


	<?php
	/*-------------------------------------------------------------------------*/
	/*	Testimonial Font
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['testimonial_font_style']);
	( intval( substr($nectar_options['testimonial_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['testimonial_font_size'],0,-2)) + 19 .'px!important' : $line_height = null;

	if( is_array($nectar_options['testimonial_font_family']) && 
		$nectar_options['testimonial_font_family']['attrs_in_use'] ) {

			echo 'blockquote,
			.testimonial_slider blockquote,
			.testimonial_slider blockquote span,
			.testimonial_slider[data-style="minimal"] blockquote span:not(.title),
			.testimonial_slider[data-style="minimal"] blockquote,
			.testimonial_slider[data-style="minimal"] .controls { ';

				// Output font properties.
				nectar_output_font_props('testimonial_font', $line_height, $nectar_options);

			echo ' }';

      if( true === $using_custom_responsive_sizing ) {

        // Store calculated line height.
    		$the_line_height = nectar_font_line_height('testimonial_font', $line_height, $nectar_options);

        $defined_font_size   = (!empty($nectar_options['testimonial_font_size']) && $nectar_options['testimonial_font_size'] != '-') ? intval($nectar_options['testimonial_font_size']) : $nectar_blockquote_default_size;
    		$defined_line_height = (!empty($the_line_height)) ? intval($the_line_height) : $nectar_blockquote_default_size + 10;

    		?>

    		@media only screen and (max-width: 1300px) and (min-width: 1000px) {
    			blockquote,
          .testimonial_slider blockquote,
          .testimonial_slider blockquote span,
          .testimonial_slider[data-style="minimal"] blockquote,
          .testimonial_slider[data-style="minimal"] blockquote span:not(.title) {
    				font-size: <?php echo esc_html( $defined_font_size*$nectar_blockquote_small_desktop ) . 'px'; ?>;
    				line-height: <?php echo esc_html( $defined_line_height*$nectar_blockquote_small_desktop ) . 'px'; ?>;
    			}
    		}

    		@media only screen and (max-width: 999px) and (min-width: 691px) {
          blockquote,
          .testimonial_slider blockquote,
          .testimonial_slider blockquote span,
          .testimonial_slider[data-style="minimal"] blockquote,
          .testimonial_slider[data-style="minimal"] blockquote span:not(.title) {
    				font-size: <?php echo esc_html( $defined_font_size*$nectar_blockquote_tablet ) . 'px'; ?>;
    				line-height: <?php echo esc_html( $defined_line_height*$nectar_blockquote_tablet ) . 'px'; ?>;
    			}
    		}

    		@media only screen and (max-width: 690px) {
          blockquote,
          .testimonial_slider blockquote,
          .testimonial_slider blockquote span,
          .testimonial_slider[data-style="minimal"] blockquote,
          .testimonial_slider[data-style="minimal"] blockquote span:not(.title) {
    				font-size: <?php echo esc_html( $defined_font_size*$nectar_blockquote_phone ) . 'px'; ?>;
    				line-height: <?php echo esc_html( $defined_line_height*$nectar_blockquote_phone ) . 'px'; ?>;
    			}
    		}

	<?php

    } // custom responsive sizing.

} // attrs in use ?>

	<?php
	/*-------------------------------------------------------------------------*/
	/*	Woo Product Title
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['nectar_woo_shop_product_title_font_style']);
	( intval( substr($nectar_options['nectar_woo_shop_product_title_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['nectar_woo_shop_product_title_font_size'],0,-2)) + 10 .'px!important' : $line_height = null;

	if( isset($nectar_options['nectar_woo_shop_product_title_font_family']) &&
		is_array($nectar_options['nectar_woo_shop_product_title_font_family']) && 
		$nectar_options['nectar_woo_shop_product_title_font_family']['attrs_in_use'] ) {

			echo '.woocommerce ul.products li.product .woocommerce-loop-product__title,
			.woocommerce ul.products li.product h3, .woocommerce ul.products li.product h2,
			.woocommerce ul.products li.product h2, .woocommerce-page ul.products li.product h2 { ';

				// Output font properties.
				nectar_output_font_props('nectar_woo_shop_product_title_font', $line_height, $nectar_options);

			echo ' }'; ?>

	<?php } // attrs in use ?>


	<?php
	/*-------------------------------------------------------------------------*/
	/*	Woo Product Secondary
	/*-------------------------------------------------------------------------*/
	$styles = explode('-', $nectar_options['nectar_woo_shop_product_secondary_font_style']);
	( intval( substr($nectar_options['nectar_woo_shop_product_secondary_font_size'],0,-2) ) > 8 ) ? $line_height = intval(substr($nectar_options['nectar_woo_shop_product_secondary_font_size'],0,-2)) + 10 .'px!important' : $line_height = null;

	if( isset($nectar_options['nectar_woo_shop_product_secondary_font_family']) &&
		is_array($nectar_options['nectar_woo_shop_product_secondary_font_family']) && 
		$nectar_options['nectar_woo_shop_product_secondary_font_family']['attrs_in_use'] ) {

			echo '.woocommerce .material.product .product-wrap .product-add-to-cart .price .amount,
			.woocommerce .material.product .product-wrap .product-add-to-cart a,
			.woocommerce .material.product .product-wrap .product-add-to-cart a > span,
			.woocommerce .material.product .product-wrap .product-add-to-cart a.added_to_cart,
			html .woocommerce ul.products li.product.material .price,
			.woocommerce ul.products li.product.material .price ins,
			.woocommerce ul.products li.product.material .price ins .amount,
			.woocommerce-page ul.products li.product.material .price ins span,
			.material.product .product-wrap .product-add-to-cart a span,
			html .woocommerce ul.products .text_on_hover.product .add_to_cart_button,
			.woocommerce ul.products li.product .price,
			.woocommerce ul.products li.product .price ins,
			.woocommerce ul.products li.product .price ins .amount,
			html .woocommerce .material.product .product-wrap .product-add-to-cart a.added_to_cart,
			body .material.product .product-wrap .product-add-to-cart[data-nectar-quickview="true"] a span,
			.woocommerce .material.product .product-wrap .product-add-to-cart a.added_to_cart,
			.text_on_hover.product a.added_to_cart,
			.products li.product.minimal .product-meta .price,
			.products li.product.minimal .product-meta .amount { ';

				// Output font properties.
				nectar_output_font_props('nectar_woo_shop_product_secondary_font', $line_height, $nectar_options);

			echo ' }'; ?>

		<?php } // attrs in use ?>


	<?php
	/*-------------------------------------------------------------------------*/
	/*	Sidebar, Carousel & Nectar Button Header Font
	/*-------------------------------------------------------------------------*/
	$styles      = explode('-', $nectar_options['sidebar_footer_h_font_style']);
	$line_height = intval(substr($nectar_options['sidebar_footer_h_font_size'],0,-2));

	if( is_array($nectar_options['sidebar_footer_h_font_family']) && 
		$nectar_options['sidebar_footer_h_font_family']['attrs_in_use'] ) {

			// WooCommerce specific.
			global $woocommerce;
			if( $woocommerce ) {
				echo '.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
				.woocommerce-page .single_add_to_cart_button,
	      .woocommerce-page #respond input#submit,
	      .woocommerce nav.woocommerce-pagination ul li a,
	      html body nav.woocommerce-pagination ul li a,
	      html body nav.woocommerce-pagination ul li span,
				.woocommerce-account .woocommerce-form-login button.button,
	      .woocommerce-account .woocommerce-form-register button.button,
				.text_on_hover.product .add_to_cart_button,
				.text_on_hover.product > .button,
	      .nectar-slide-in-cart .widget_shopping_cart .buttons a,
				.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart_content .nectar-inactive a,
	      .material.product .product-wrap .product-add-to-cart a .price .amount,
	      .material.product .product-wrap .product-add-to-cart a span,
	      ul.products li.material.product span.onsale,
	      .woocommerce .material.product .product-wrap .product-add-to-cart a.added_to_cart,
	      .woocommerce-page ul.products li.product.material .price,
	      .woocommerce-page ul.products li.product.material .price ins span,
	      body .woocommerce .nectar-woo-flickity[data-controls="arrows-and-text"] .nectar-woo-carousel-top,
	      .products li.product.minimal .product-add-to-cart a,
	      .woocommerce div.product form.cart .button,
	      .nectar-quick-view-box .nectar-full-product-link,
	      .woocommerce-page .nectar-quick-view-box button[type="submit"].single_add_to_cart_button,
	      #header-outer .widget_shopping_cart a.button,
	      .woocommerce .classic .product-wrap .product-add-to-cart .add_to_cart_button,
				.woocommerce .classic .product-wrap .product-add-to-cart .button,
	      .text_on_hover.product .nectar_quick_view,
	      .woocommerce .classic .product-wrap .product-add-to-cart .button.product_type_variable,
	      .woocommerce.add_to_cart_inline a.button.add_to_cart_button,
	      .woocommerce .classic .product-wrap .product-add-to-cart .button.product_type_grouped,
	      .woocommerce-page .woocommerce p.return-to-shop a.wc-backward,
				.nectar-slide-in-cart.style_slide_in_click .woocommerce-mini-cart__empty-message a.button { ';

					// Output font properties.
					nectar_output_font_props('sidebar_footer_h_font', 'bypass', $nectar_options);

				echo ' }';

			}

			echo '#footer-outer .widget h4,
      #sidebar h4,
      #call-to-action .container a,
      .uppercase,
      .nectar-post-grid-wrap .load-more,
      .nectar-button,
      .nectar-button.medium,
      .nectar-button.small,
      .nectar-view-indicator span,
      .nectar-3d-transparent-button,
      .swiper-slide .button a,
      .play_button_with_text span[data-font*="btn"],
      body .widget_calendar table th,
      body #footer-outer #footer-widgets .col .widget_calendar table th,
      body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > a,
      .carousel-heading h2, body .gform_wrapper .top_label .gfield_label,
      body .vc_pie_chart .wpb_pie_chart_heading,
      #infscr-loading div, #page-header-bg .author-section a,
      .ascend input[type="submit"], .ascend button[type="submit"],
      .material input[type="submit"],
      .material button[type="submit"],
			.original .checkout_coupon button[type="submit"],
			.original.woocommerce-cart .actions button[type="submit"],
			.ascend .checkout_coupon button[type="submit"],
			.ascend.woocommerce-cart .actions button[type="submit"],
      body.material #page-header-bg.fullscreen-header .inner-wrap >a,
      body #page-header-bg[data-post-hs="default_minimal"] .inner-wrap > a,
      .widget h4, .text-on-hover-wrap .categories a,
      .meta_overlaid article.post .post-header h3,
      .meta_overlaid article.post.quote .post-content h3,
      .meta_overlaid article.post.link .post-content h3,
      .meta_overlaid article .meta-author a,
      .pricing-column.highlight h3 .highlight-reason,
      .blog-recent[data-style="minimal"] .col > span,
      body .masonry.classic_enhanced .posts-container article .meta-category a,
      body .masonry.classic_enhanced .posts-container article.wide_tall .meta-category a,
      .blog-recent[data-style*="classic_enhanced"] .meta-category a,
      .nectar-recent-posts-slider .container .strong,
      .single .heading-title[data-header-style="default_minimal"] .meta-category a,
      .nectar-fancy-box .link-text,
      .post-area.standard-minimal article.post .post-meta .date a,
      .post-area.standard-minimal article.post .more-link span,
      body[data-button-style="rounded"] #pagination > a,
      html body #pagination > span,
      body[data-form-submit="see-through-2"] input[type=submit],
      body[data-form-submit="see-through-2"] button[type=submit],
      body[data-form-submit="see-through"] input[type=submit],
      body[data-form-submit="see-through"] button[type=submit],
      body[data-form-submit="regular"] input[type=submit], 
	  body[data-form-submit="regular"] button[type=submit],
      .nectar_team_member_overlay .team_member_details .title,
      body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > li.has-ul > a,
      .nectar_fullscreen_zoom_recent_projects .project-slide .project-info .normal-container > a,
      .nectar-hor-list-item .nectar-list-item-btn,
      .nectar-category-grid-item .content span.subtext,
      .yikes-easy-mc-form .yikes-easy-mc-submit-button,
      .nectar-cta .nectar-button-type,
	  .nectar-post-grid-wrap .nectar-post-grid-filters h4,
	  .nectar-inherit-btn-type { ';

				// Output font properties.
				nectar_output_font_props('sidebar_footer_h_font', 'bypass', $nectar_options);

			echo ' }'; 
      
      $defined_button_font_size = (isset($nectar_options['sidebar_footer_h_font_size']) && $nectar_options['sidebar_footer_h_font_size'] != '-') ? intval($nectar_options['sidebar_footer_h_font_size']) : 16;

      if( $defined_button_font_size < 16 && $defined_button_font_size > 10 ) {
        echo '.nectar-view-indicator span { font-size: '.esc_attr($defined_button_font_size).'px!important; }';
      }

      ?>

	<?php } // attrs in use ?>


	<?php
	/*-------------------------------------------------------------------------*/
	/*	Team member names & heading subtitles
	/*-------------------------------------------------------------------------*/
	$styles      = explode('-', $nectar_options['team_member_h_font_style']);
	$line_height = intval(substr($nectar_options['team_member_h_font_size'],0,-2));

	if( is_array($nectar_options['team_member_h_font_family']) && 
		$nectar_options['team_member_h_font_family']['attrs_in_use'] ) {

			echo '.team-member h4, .row .col.section-title p,
			.row .col.section-title span, #page-header-bg .subheader,
			.nectar-milestone .subject, .testimonial_slider blockquote span { ';

				// Output font properties.
				nectar_output_font_props('team_member_h_font', 'bypass', $nectar_options);

			echo '}';

			echo 'article.post .post-meta .month { line-height:'. ($line_height + -6) . 'px; }';

	 } // attrs in use


?>
