<?php
/**
 * Outputs the theme option color related styles.
 *
 * The styles generated from here will either be contained in salient/css/salient-dynamic-styles.css
 * or output directly in the head, depending on if the server writing permission is set for the css directory.
 *
 * @version 15.0.3
 */


$nectar_options = get_nectar_theme_options();

NectarThemeManager::setup();

$theme_skin             = NectarThemeManager::$skin;

$off_canvas_style       = ( isset($nectar_options['header-slide-out-widget-area-style']) ) ? $nectar_options['header-slide-out-widget-area-style'] : 'slide-out-from-right';
$header_hover_animation = ( isset($nectar_options['header-hover-effect']) ) ? $nectar_options['header-hover-effect'] : 'animated_underline';


global $woocommerce;


// Main Accent Color: color property.
echo '

.nectar-color-accent-color,
body a,
label span,
body [class^="icon-"].icon-default-style,
.blog-recent[data-style*="classic_enhanced"] .post-meta a:hover i,
.masonry.classic_enhanced .post .post-meta a:hover i,
.comment-list .comment-meta a:hover,
.comment-list .comment-meta a:focus,
.comment-author a:hover,
.comment-author a:focus,
.post .post-header h2 a,
.post .post-header a:hover,
.post .post-header a:focus,
#single-below-header a:hover,
#single-below-header a:focus,
.comment-list .pingback .comment-body > a:hover,
[data-style="list_featured_first_row"] .meta-category a,
[data-style="list_featured_first_row"] .meta-category a,
.nectar-fancy-box[data-style="color_box_hover"][data-color="accent-color"] .icon-default-style,
div[data-style="minimal"] .toggle:hover > .toggle-title a,
div[data-style="minimal"] .toggle.open > .toggle-title a,
#footer-outer #copyright li a i:hover,
.ascend .comment-list .reply a,
body.material .widget:not(.nectar_popular_posts_widget):not(.recent_posts_extra_widget) li a:hover,
body.material #sidebar .widget:not(.nectar_popular_posts_widget):not(.recent_posts_extra_widget) li a:hover,
body.material #footer-outer .widget:not(.nectar_popular_posts_widget):not(.recent_posts_extra_widget) li a:hover,
#top nav .sf-menu .current_page_item > a .sf-sub-indicator i,
#top nav .sf-menu .current_page_ancestor > a .sf-sub-indicator i,
.sf-menu > .current_page_ancestor > a > .sf-sub-indicator i,
.material .widget .tagcloud a,
#single-below-header a:hover [class^="icon-"],
.wpcf7-form .wpcf7-not-valid-tip,
#header-outer .nectar-menu-label
{
	color: '. esc_attr($nectar_options["accent-color"]) .';
}';

// Header link hover: color property.
if( 'default' === $header_hover_animation ) {
	echo '#header-outer[data-lhe="default"] #top nav > ul > li > a:hover,
	#header-outer[data-lhe="default"] #top nav .sf-menu > .sfHover:not(#social-in-menu) > a,
	#header-outer[data-lhe="default"] #top nav .sf-menu > .current-menu-item > a,
	#header-outer[data-lhe="default"] #top nav .sf-menu > .current_page_ancestor > a,
	#header-outer[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a,
	#header-outer[data-lhe="default"] #top nav .sf-menu > .current_page_item > a,
	#header-outer[data-lhe="default"] #top nav > ul > .button_bordered > a:hover,
	#header-outer[data-lhe="default"] #top nav > .sf-menu > .button_bordered.sfHover > a,
	#header-outer:not(.transparent) .nectar-mobile-only.mobile-header a:hover,
	#header-outer:not(.transparent) .nectar-mobile-only.mobile-header li[class*="current"] a {
	   color:'. esc_attr($nectar_options["accent-color"]) .'!important;
	}';
} 

else if( 'animated_underline' === $header_hover_animation) {
	echo '#header-outer:not(.transparent) .nectar-mobile-only.mobile-header .menu-title-text:after {
	   border-color:'. esc_attr($nectar_options["accent-color"]) .'!important;
	}';
}


echo '
#header-outer #top nav > ul > .button_bordered > a:hover,
#header-outer:not(.transparent) #social-in-menu a i:after,
.sf-menu > li > a:hover > .sf-sub-indicator i,
.sf-menu > li > a:active > .sf-sub-indicator i,
.sf-menu > .sfHover > a > .sf-sub-indicator i,
.sf-menu .megamenu > ul > li:hover > a,
#header-outer nav > ul > .megamenu > ul > li > a:hover,
#header-outer nav > ul > .megamenu > ul > .sfHover > a,
#header-outer nav > ul > .megamenu > ul > li > a:focus,
#top nav ul #nectar-user-account a:hover span,
#top nav ul #search-btn a:hover span,
#top nav ul .slide-out-widget-area-toggle a:hover span,
body.material:not([data-header-color="custom"]) #header-outer:not([data-format="left-header"]) #top ul.cart_list a:hover,
body.material #header-outer:not(.transparent) .cart-outer:hover .cart-menu-wrap .icon-salient-cart,
#header-outer:not([data-format="left-header"]) nav > ul > .megamenu ul ul .current-menu-item.has-ul > a,
#header-outer:not([data-format="left-header"]) nav > ul > .megamenu ul ul .current-menu-ancestor.has-ul > a,
body #header-secondary-outer #social a:hover i,
body #header-secondary-outer #social a:focus i,
#footer-outer a:focus,
#footer-outer a:hover,
.recent-posts .post-header a:hover,
.result a:hover,
.post-area.standard-minimal .post .post-meta .date a,
.post-area.standard-minimal .post .post-header h2 a:hover,
.post-area.standard-minimal .post .more-link:hover span,
.post-area.standard-minimal .post .more-link span:after,
.post-area.standard-minimal .post .minimal-post-meta a:hover,
.single .post .post-meta a:hover,
.single .post .post-meta a:focus,
.single #single-meta div a:hover i,
.single #single-meta div:hover > a,
.single #single-meta div:focus > a,
.result .title a,
.circle-border,
.home .blog-recent:not([data-style="list_featured_first_row"]) .col .post-header a:hover,
.home .blog-recent .col .post-header h3 a,
.project-attrs li i,
.nectar-milestone .number.accent-color,
body #portfolio-nav a:hover i,
span.accent-color,
.portfolio-items .nectar-love:hover i,
.portfolio-items .nectar-love.loved i,
body .hovered .nectar-love i,
body:not(.material) #search-outer #search #close a span:hover,
.carousel-wrap[data-full-width="true"] .carousel-heading a:hover i,
#search-outer .ui-widget-content li:hover *,
#search-outer .ui-widget-content .ui-state-focus *,
.portfolio-filters-inline .container ul li .active,
.svg-icon-holder[data-color="accent-color"],
.team-member .accent-color:hover,
.blog-recent[data-style="minimal"] .col > span,
.blog-recent[data-style="title_only"] .col:hover .post-header .title,
body #pagination .page-numbers.prev:hover,
body #pagination .page-numbers.next:hover,
body #pagination a.page-numbers:hover,
body #pagination a.page-numbers:focus,
body[data-form-submit="see-through"] input[type=submit],
body[data-form-submit="see-through"] button[type=submit],
.nectar_icon_wrap[data-color="accent-color"] i,
.nectar_team_member_close .inner:before,
body:not([data-header-format="left-header"]) nav > ul > .megamenu > ul > li > ul > .has-ul > a:hover,
body:not([data-header-format="left-header"]) nav > ul > .megamenu > ul > li > ul > .has-ul > a:focus,
.masonry.material .masonry-blog-item .meta-category a,
body .wpb_row .span_12 .portfolio-filters-inline[data-color-scheme="accent-color-underline"].full-width-section .active,
body .wpb_row .span_12 .portfolio-filters-inline[data-color-scheme="accent-color-underline"].full-width-section a:hover,
.material .comment-list .reply a:hover,
.material .comment-list .reply a:focus,
.material .widget li:not(.has-img) a:hover .post-title,
.material #sidebar .widget li:not(.has-img) a:hover .post-title,
.material #sidebar .widget ul[data-style="featured-image-left"] li a:hover .post-title,
.material #sidebar .widget .tagcloud a,
.single.material .post-area .content-inner > .post-tags a,
.post-area.featured_img_left .meta-category a,
.post-meta .icon-salient-heart-2.loved,
body.material .nectar-button.see-through.accent-color[data-color-override="false"],
div[data-style="minimal_small"] .toggle.accent-color > .toggle-title a:hover,
div[data-style="minimal_small"] .toggle.accent-color.open > .toggle-title a,
.testimonial_slider[data-rating-color="accent-color"] .star-rating .filled:before,
.nectar_single_testimonial[data-color="accent-color"] p .open-quote,
.nectar-quick-view-box .star-rating,
.widget_search .search-form button[type=submit] .icon-salient-search,
body.search-no-results .search-form button[type=submit] .icon-salient-search {
	color:'. esc_attr($nectar_options["accent-color"]) .'!important;
}';

if( 'simple' === $off_canvas_style ) {
	echo '#header-outer #mobile-menu ul li[class*="current"] > a,
	#header-outer #mobile-menu ul li a:hover,
	#header-outer #mobile-menu ul li a:focus,
	#header-outer #mobile-menu ul li a:hover .sf-sub-indicator i,
	#header-outer #mobile-menu ul li a:focus .sf-sub-indicator i
	{
		color: '. esc_attr($nectar_options["accent-color"]) .';
	}';
}


// Main Accent Color: background-color property.
echo '
.nectar-bg-accent-color,
.nectar-bg-hover-accent-color:hover,
#ajax-content-wrap .nectar-bg-pseudo-accent-color:before,
[data-style="list_featured_first_row"] .meta-category a:before,
.tabbed > ul li .active-tab,
.tabbed > ul li .active-tab:hover,
.wpb_row .nectar-post-grid-filters[data-active-color="accent-color"] a:after,
.testimonial_slider[data-style="multiple_visible"][data-color*="accent-color"] .flickity-page-dots .dot.is-selected:before,
.testimonial_slider[data-style="multiple_visible"][data-color*="accent-color"] blockquote.is-selected p,
.nectar_video_lightbox.nectar-button[data-color="default-accent-color"],
.nectar_video_lightbox.nectar-button[data-color="transparent-accent-color"]:hover,
.nectar-cta[data-color="accent-color"]:not([data-style="material"]) .link_wrap,
.flex-direction-nav a,
.carousel-prev:hover,
.carousel-next:hover,
.nectar-flickity[data-controls*="arrows_overlaid"][data-control-color="accent-color"] .flickity-prev-next-button:hover:before,
.nectar-flickity[data-controls="default"][data-control-color="accent-color"] .flickity-page-dots .dot:before,
.nectar-flickity[data-controls="touch_total"][data-control-color="accent-color"] .visualized-total span,
[class*=" icon-"],
.toggle.open > .toggle-title a,
div[data-style="minimal"] .toggle.open > .toggle-title i:after,
div[data-style="minimal"] .toggle:hover > .toggle-title i:after,
div[data-style="minimal"] .toggle.open > .toggle-title i:before,
div[data-style="minimal"] .toggle:hover > .toggle-title i:before,
div[data-style="minimal_small"] .toggle.accent-color > .toggle-title:after,
.main-content .widget_calendar caption,
#footer-outer .widget_calendar caption,
.post .more-link span:hover,
.post.format-quote .post-content .quote-inner,
.post.format-link .post-content .link-inner,
.nectar-post-grid-wrap[data-load-more-color="accent-color"] .load-more:hover,
.format-status .post-content .status-inner,
.nectar-post-grid-item.nectar-new-item .inner:before,
input[type=submit]:hover,
input[type="button"]:hover,
body[data-form-submit="regular"] input[type=submit],
body[data-form-submit="regular"] button[type=submit],
body[data-form-submit="regular"] .container-wrap .span_12.light input[type=submit]:hover,
body[data-form-submit="regular"] .container-wrap .span_12.light button[type=submit]:hover,
#slide-out-widget-area,
#slide-out-widget-area-bg.fullscreen,
#slide-out-widget-area-bg.fullscreen-split,
#slide-out-widget-area-bg.fullscreen-alt .bg-inner,
body.material #slide-out-widget-area-bg.slide-out-from-right,
.widget .material .widget .tagcloud a:before,
.nectar-hor-list-item[data-hover-effect="full_border"][data-color="accent-color"] .nectar-list-item-btn:hover,
#header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:after,
.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .nectar-notice,
.woocommerce #review_form #respond .form-submit #submit,
#header-outer .nectar-menu-label:before {
	background-color:'.esc_attr($nectar_options["accent-color"]).';
}


.orbit-wrapper .slider-nav .right,
.orbit-wrapper .slider-nav .left,
.progress li span, .nectar-progress-bar span,
#footer-outer #footer-widgets .col .tagcloud a:hover,
#sidebar .widget .tagcloud a:hover,
#fp-nav.tooltip ul li .fp-tooltip .tooltip-inner,
#pagination .next a:hover,
#pagination .prev a:hover,
.comment-list .reply a:hover,
.comment-list .reply a:focus,
.icon-normal,
.bar_graph li span,
.nectar-button[data-color-override="false"].regular-button,
.nectar-button.tilt.accent-color,
body .swiper-slide .button.transparent_2 .primary-color:hover,
#footer-outer #footer-widgets .col input[type="submit"],
.blog-recent .more-link span:hover,
.post-tags a:hover,
#to-top:hover,
#to-top.dark:hover,
body[data-button-style*="rounded"] #to-top:after,
#pagination a.page-numbers:hover,
#pagination span.page-numbers.current,
.portfolio-items .col[data-default-color="true"] .work-item:not(.style-3) .work-info-bg,
.portfolio-items .col[data-default-color="true"] .bottom-meta,
.portfolio-items .col.nectar-new-item .inner-wrap:before,
.portfolio-filters-inline[data-color-scheme="accent-color-underline"] a:after,
.portfolio-filters a,
.portfolio-filters #sort-portfolio,
.project-attrs li span,
.portfolio-filters,
.portfolio-filters-inline[data-color-scheme="accent-color"],
.bottom_controls #portfolio-nav .controls li a i:after,
.bottom_controls #portfolio-nav ul:first-child li#all-items a:hover i,
.single-portfolio .facebook-share a:hover,
.single-portfolio .twitter-share a:hover,
.single-portfolio .pinterest-share a:hover,
.single-post .facebook-share a:hover,
.single-post .twitter-share a:hover,
.single-post .pinterest-share a:hover,
.mejs-controls .mejs-time-rail .mejs-time-current,
.mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current,
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
.post.quote .content-inner .quote-inner .whole-link,
.masonry.classic_enhanced .post.quote.wide_tall .post-content a:hover .quote-inner,
.masonry.classic_enhanced .post.link.wide_tall .post-content a:hover .link-inner,
.iosSlider .prev_slide:hover,
.iosSlider .next_slide:hover,
#header-outer .widget_shopping_cart a.button,
#header-outer a.cart-contents .cart-wrap span,
#header-outer #mobile-cart-link .cart-wrap span,
#top nav ul .slide-out-widget-area-toggle a:hover .lines,
#top nav ul .slide-out-widget-area-toggle a:hover .lines:after,
#top nav ul .slide-out-widget-area-toggle a:hover .lines:before,
#top nav ul .slide-out-widget-area-toggle a:hover .lines-button:after,
#header-outer .widget_shopping_cart a.button,
body[data-header-format="left-header"] #header-outer[data-lhe="animated_underline"] #top nav ul li:not([class*="button_"]) > a span:after,
#buddypress a.button:focus,
.swiper-slide .button.solid_color a,
.swiper-slide .button.solid_color_2 a,
.select2-container .select2-choice:hover,
.select2-dropdown-open .select2-choice,
body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single:hover,
body[data-fancy-form-rcs="1"] .select2-container--default.select2-container--open .select2-selection--single,
#top nav > ul > .button_solid_color > a:before,
#header-outer.transparent #top nav > ul > .button_solid_color > a:before,
.twentytwenty-handle,
.twentytwenty-horizontal .twentytwenty-handle:before,
.twentytwenty-horizontal .twentytwenty-handle:after,
.twentytwenty-vertical .twentytwenty-handle:before, .twentytwenty-vertical
.twentytwenty-handle:after,
.masonry.classic_enhanced .posts-container article .meta-category a:hover,
.blog-recent[data-style*="classic_enhanced"] .meta-category a:hover,
.masonry.classic_enhanced .posts-container article .video-play-button,
.masonry.material .masonry-blog-item .meta-category a:before,
.material.masonry .masonry-blog-item .video-play-button,
.masonry.material .quote-inner:before,
.masonry.material .link-inner:before,
.nectar-recent-posts-slider .container .strong span:before,
#page-header-bg[data-post-hs="default_minimal"] .inner-wrap > a:hover,
#page-header-bg[data-post-hs="default_minimal"] .inner-wrap > a:focus,
.single .heading-title[data-header-style="default_minimal"] .meta-category a:hover,
.single .heading-title[data-header-style="default_minimal"] .meta-category a:focus,
.nectar-fancy-box:after,
.divider-small-border[data-color="accent-color"],
.divider-border[data-color="accent-color"],
.nectar-animated-title[data-color="accent-color"] .nectar-animated-title-inner:after,
#fp-nav:not(.light-controls).tooltip_alt ul li a span:after,
#fp-nav.tooltip_alt ul li a span:after,
.nectar-video-box[data-color="default-accent-color"] .nectar_video_lightbox,
body .nectar-video-box[data-color="default-accent-color"][data-hover="zoom_button"] .nectar_video_lightbox:after,
.nectar_video_lightbox.play_button_with_text[data-color="default-accent-color"]:not([data-style="small"]) .play > .inner-wrap:before,
.span_12.dark .owl-theme .owl-dots .owl-dot.active span,
.span_12.dark .owl-theme .owl-dots .owl-dot:hover span,
.nectar-recent-posts-single_featured .strong a,
.post-area.standard-minimal .post .more-link span:before,
.nectar-slide-in-cart .widget_shopping_cart a.button,
.related-posts[data-style="material"] .meta-category a:before,
.post-area.featured_img_left .meta-category a:before,
body.material #page-header-bg.fullscreen-header .inner-wrap >a,
.nectar-hor-list-item[data-color="accent-color"]:before,
.material #sidebar .widget .tagcloud a:before,
.single .post-area .content-inner > .post-tags a:before,
.auto_meta_overlaid_spaced .post.quote .n-post-bg:after,
.auto_meta_overlaid_spaced .post.link .n-post-bg:after,
.post-area.featured_img_left .posts-container .article-content-wrap .video-play-button,
.post-area.featured_img_left .post .quote-inner:before,
.post-area.featured_img_left .link-inner:before,
.nectar-recent-posts-single_featured.multiple_featured .controls li:after,
.nectar-recent-posts-single_featured.multiple_featured .controls .active:before,
.nectar-fancy-box[data-color="accent-color"]:not([data-style="default"]) .box-bg:after,
body.material[data-button-style^="rounded"] .nectar-button.see-through.accent-color[data-color-override="false"] i,
body.material .nectar-video-box[data-color="default-accent-color"] .nectar_video_lightbox:before,
.nectar_team_member_overlay .team_member_details .bio-inner .mobile-close:before,
.nectar_team_member_overlay .team_member_details .bio-inner .mobile-close:after,
.fancybox-navigation button:hover:before,
button[type=submit]:hover,
button[type=submit]:focus,
body[data-form-submit="see-through"] input[type=submit]:hover,
body[data-form-submit="see-through"].woocommerce #respond input#submit:hover,
html body[data-form-submit="see-through"] button[type=submit]:hover,
body[data-form-submit="see-through"] .container-wrap .span_12.light input[type=submit]:hover,
body[data-form-submit="see-through"] .container-wrap .span_12.light button[type=submit]:hover,
body.original .bypostauthor .comment-body:before,
.widget_layered_nav ul.yith-wcan-label li a:hover,
.widget_layered_nav ul.yith-wcan-label .chosen a,
.nectar-next-section-wrap.bounce a:before,
body .nectar-button.see-through-2[data-hover-color-override="false"]:hover
{
	background-color:'.esc_attr($nectar_options["accent-color"]).'!important;
}';


// Dropdown Hover Coloring.
$using_underline_dropdown_effect = false;
if(isset($nectar_options['header-dropdown-hover-effect']) &&
	!empty($nectar_options['header-dropdown-hover-effect']) &&
	'animated_underline' === $nectar_options['header-dropdown-hover-effect']) {
	$using_underline_dropdown_effect = true;
} else {
	echo '#header-outer #top nav > ul > li:not(.megamenu) ul a:hover,
	#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) .sfHover > a,
	#header-outer #top nav > ul > li:not(.megamenu) .sfHover > a,
	#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul a:hover,
	#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-item > a,
	#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-ancestor > a,
	#header-outer nav > ul > .megamenu > ul ul li a:hover,
	#header-outer nav > ul > .megamenu > ul ul li a:focus,
	#header-outer nav > ul > .megamenu > ul ul .sfHover > a,
	#header-secondary-outer ul > li:not(.megamenu) .sfHover > a,
	#header-secondary-outer ul > li:not(.megamenu) ul a:hover,
	#header-secondary-outer ul > li:not(.megamenu) ul a:focus,
	body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul ul .current-menu-item > a {
		background-color:'.esc_attr($nectar_options["accent-color"]).'!important;
	}
	#header-outer[data-format="left-header"] #top nav > ul > li:not(.megamenu) ul a:hover {
		color:'.esc_attr($nectar_options["accent-color"]).';
	}
	#header-outer[data-format="left-header"] .sf-menu .sub-menu .current-menu-item > a,
	.sf-menu ul .open-submenu > a {
		color:'.esc_attr($nectar_options["accent-color"]).'!important;
	}
	';
}



// Main Accent Color: border-color property.
$form_style = ( isset($nectar_options['form-style']) ) ? $nectar_options['form-style'] : 'default';

if( 'minimal' === $form_style ) {
	echo 'body[data-form-style="minimal"] input[type=text]:focus,
	body[data-form-style="minimal"].woocommerce-cart table.cart .actions .coupon .input-text:focus,
	body[data-form-style="minimal"] textarea:focus,
	body[data-form-style="minimal"] input[type=email]:focus,
	body[data-form-style="minimal"] input[type=search]:focus,
	body[data-form-style="minimal"] input[type=password]:focus,
	body[data-form-style="minimal"] input[type=tel]:focus,
	body[data-form-style="minimal"] input[type=url]:focus,
	body[data-form-style="minimal"] input[type=date]:focus,
	body[data-form-style="minimal"] input[type=number]:focus,
	body[data-form-style="minimal"] select:focus {
		border-color:'.esc_attr($nectar_options["accent-color"]).';
	}';
}

echo '
.tabbed > ul li .active-tab,
body.material input[type=text]:focus,
body.material textarea:focus,
body.material input[type=email]:focus,
body.material input[type=search]:focus,
body.material input[type=password]:focus,
body.material input[type=tel]:focus,
body.material input[type=url]:focus,
body.material input[type=date]:focus,
body.material input[type=number]:focus,
body.material select:focus,
.row .col .wp-caption .wp-caption-text,
.material.woocommerce-page input#coupon_code:focus,
.material #search-outer #search input[type="text"],
#header-outer[data-lhe="animated_underline"] #top nav > ul > li > a .menu-title-text:after,
div[data-style="minimal"] .toggle.default.open i,
div[data-style="minimal"] .toggle.default:hover i,
div[data-style="minimal"] .toggle.accent-color.open i,
div[data-style="minimal"] .toggle.accent-color:hover i,
.single #single-meta div a:hover,
.single #single-meta div a:focus,
.single .fullscreen-blog-header #single-below-header > span a:hover,
.blog-title #single-meta .nectar-social.hover > div a:hover,
.nectar-hor-list-item[data-hover-effect="full_border"][data-color="accent-color"]:hover,
.material.woocommerce-page[data-form-style="default"] div input#coupon_code:focus {
	border-color:'.esc_attr($nectar_options["accent-color"]).';
}


body[data-form-style="minimal"] label:after,
body .recent_projects_widget a:hover img,
.recent_projects_widget a:hover img,
#sidebar #flickr a:hover img,
body .nectar-button.see-through-2[data-hover-color-override="false"]:hover,
#footer-outer #flickr a:hover img,
#featured article .post-title a:hover,
body #featured article .post-title a:hover,
div.wpcf7-validation-errors,
.select2-container .select2-choice:hover,
.select2-dropdown-open .select2-choice,
body:not(.original) .bypostauthor img.avatar,
.material blockquote::before,
blockquote.wp-block-quote:before,
#header-outer:not(.transparent) #top nav > ul > .button_bordered > a:hover:before,
.single #project-meta ul li:not(.meta-share-count):hover a,
body[data-button-style="rounded"] #pagination > a:hover,
body[data-form-submit="see-through"] input[type=submit],
body[data-form-submit="see-through"] button[type=submit],
.span_12.dark .nectar_video_lightbox.play_button_with_text[data-color="default-accent-color"] .play:before,
.span_12.dark .nectar_video_lightbox.play_button_with_text[data-color="default-accent-color"] .play:after,
#header-secondary-outer[data-lhe="animated_underline"] nav > .sf-menu >li >a .menu-title-text:after,
body.material .nectar-button.see-through.accent-color[data-color-override="false"],
.woocommerce-page.material .widget_price_filter .ui-slider .ui-slider-handle,
body[data-form-submit="see-through"] button[type=submit]:not(.search-widget-btn),
.woocommerce-account[data-form-submit="see-through"] .woocommerce-form-login button.button,
.woocommerce-account[data-form-submit="see-through"] .woocommerce-form-register button.button,
body[data-form-submit="see-through"] .woocommerce #order_review #payment #place_order,
body[data-fancy-form-rcs="1"] .select2-container--default .select2-selection--single:hover,
body[data-fancy-form-rcs="1"] .select2-container--default.select2-container--open .select2-selection--single,
.gallery a:hover img {
	border-color:'.esc_attr($nectar_options["accent-color"]).'!important;
}';





// WooCommerce
if ($woocommerce) {

	 // Color property.
	 echo '
	 .woocommerce div.product .woocommerce-variation-price span.price,
	 .woocommerce div.product .entry-summary .stock {
		 color:'.esc_attr($nectar_options["accent-color"]).';
	 }

	 #header-outer .widget_shopping_cart .cart_list a,
	 #header-outer .woocommerce.widget_shopping_cart .cart_list li a.remove,
	 .woocommerce .star-rating,
	 .woocommerce form .form-row .required,
	 .woocommerce-page form .form-row .required,
	 .woocommerce ul.products li.product .price,
	 .woocommerce-page ul.products li.product .price,
	 .woocommerce-pagination a.page-numbers:hover,
	 .woocommerce p.stars a:hover,
	 .woocommerce .material.product .product-wrap .product-add-to-cart a:hover,
	 .woocommerce .material.product .product-wrap .product-add-to-cart a:hover > span,
	 .woocommerce-MyAccount-navigation ul li.is-active a:before,
	 .woocommerce-MyAccount-navigation ul li:hover a:before,
	 .woocommerce.ascend .price_slider_amount button.button[type="submit"],
	 .ascend.woocommerce #sidebar div ul li a:hover,
	 .ascend.woocommerce #sidebar div ul .current-cat > a,
	 .woocommerce .widget_layered_nav ul li.chosen a:after,
	 .woocommerce-page .widget_layered_nav ul li.chosen a:after,
	 .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control.active,
	 .woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control:hover,
	 .woocommerce #review_form #respond p.comment-notes span.required,
	 .nectar-slide-in-cart:not(.style_slide_in_click) .widget_shopping_cart .cart_list a,
	 #sidebar .widget_shopping_cart .cart_list li a.remove:hover,
	 .text_on_hover.product .add_to_cart_button,
	 .text_on_hover.product > .button,
	 .minimal.product .product-wrap .normal.icon-salient-cart[class*=" icon-"],
	 .minimal.product .product-wrap i,
	 .minimal.product .product-wrap .normal.icon-salient-m-eye,
	 .products li.product.minimal .product-add-to-cart .loading:after,
	 .ascend #header-outer:not(.transparent) .cart-outer:hover .cart-menu-wrap:not(.has_products) .icon-salient-cart {
		 color:'.esc_attr($nectar_options["accent-color"]).'!important;
	 }';

	 // BG Color property.
	 echo '.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
	 .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,
	 .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
	 .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active {
		 background-color:'.esc_attr($nectar_options["accent-color"]).';
	 }';

	 	echo '.woocommerce ul.products li.product .onsale,
		.woocommerce-page ul.products li.product .onsale, .woocommerce span.onsale,
		.woocommerce-page span.onsale, .woocommerce .product-wrap .add_to_cart_button.added,
		.single-product .facebook-share a:hover, .single-product .twitter-share a:hover,
		.single-product .pinterest-share a:hover, .woocommerce-message, .woocommerce-error,
		.woocommerce-info, .woocommerce .chzn-container .chzn-results .highlighted,
		.woocommerce .chosen-container .chosen-results .highlighted, .woocommerce a.button:hover,
		.woocommerce-page a.button:hover, .woocommerce button.button:hover, .woocommerce-page button.button:hover,
		.woocommerce input.button:hover, .woocommerce-page input.button:hover,
		.woocommerce #respond input#submit:hover,
		.woocommerce-page #respond input#submit:hover,
		.woocommerce #content input.button:hover,
		.woocommerce-page #content input.button:hover,
		.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
		.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
		.ascend.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
		.ascend.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
		.woocommerce #sidebar div ul li a:hover ~ .count,
		.woocommerce #sidebar div ul li.chosen > a ~ .count,
		.woocommerce #sidebar div ul .current-cat > .count,
		.woocommerce #sidebar .wc-block-product-categories-list-item:hover > .wc-block-product-categories-list-item-count,
		.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
		.material.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
		.woocommerce-account .woocommerce-form-login button.button,
		.woocommerce-account .woocommerce-form-register button.button,
		.woocommerce.widget_price_filter .price_slider:not(.ui-slider):before,
		.woocommerce.widget_price_filter .price_slider:not(.ui-slider):after,
		.woocommerce.widget_price_filter .price_slider:not(.ui-slider),
		body .woocommerce.add_to_cart_inline a.button.add_to_cart_button,
		.woocommerce table.cart a.remove:hover,
		.woocommerce #content table.cart a.remove:hover,
		.woocommerce-page table.cart a.remove:hover,
		.woocommerce-page #content table.cart a.remove:hover,
		.woocommerce-page .woocommerce p.return-to-shop a.wc-backward,
		.woocommerce .yith-wcan-reset-navigation.button,
		ul.products li.minimal.product span.onsale,
		.span_12.dark .nectar-woo-flickity[data-controls="arrows-and-text"] .nectar-woo-carousel-top a:after,
		.woocommerce-page button.single_add_to_cart_button,
		.woocommerce div.product .woocommerce-tabs .full-width-content ul.tabs li a:after,
		.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
		.woocommerce #order_review #payment #place_order,
		.woocommerce .span_4 input[type="submit"].checkout-button,
		.woocommerce .material.product .add_to_cart_button,
		body nav.woocommerce-pagination span.page-numbers.current,
		.woocommerce span.onsale .nectar-quick-view-box .onsale,
		.nectar-quick-view-box .onsale,
		.woocommerce-page .nectar-quick-view-box .onsale,
		.cart .quantity input.plus:hover,
		.cart .quantity input.minus:hover,
		.woocommerce-mini-cart .quantity input.plus:hover,
		.woocommerce-mini-cart .quantity input.minus:hover,
		body .nectar-quick-view-box .single_add_to_cart_button,
		.woocommerce .classic .add_to_cart_button,
		.woocommerce .classic .product-add-to-cart a.button,
		body[data-form-submit="see-through"] .woocommerce #order_review #payment #place_order:hover,
		body .products-carousel .carousel-next:hover,
		body .products-carousel .carousel-prev:hover,
		.text_on_hover.product .nectar_quick_view,
		.text_on_hover.product a.added_to_cart {
			background-color:'.esc_attr($nectar_options["accent-color"]).'!important;
		}
		.single-product .product[data-gallery-style="left_thumb_sticky"] .product-thumbs .flickity-slider .thumb.is-nav-selected img,
		.single-product:not(.mobile) .product[data-gallery-style="left_thumb_sticky"] .product-thumbs .thumb a.active img {
			border-color:'.esc_attr($nectar_options["accent-color"]).'!important;
		}';

		echo '.woocommerce.material .widget_price_filter .ui-slider .ui-slider-handle:before,
		.material.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle:before {
			box-shadow: 0 0 0 10px '.esc_attr($nectar_options["accent-color"]).' inset;
		}
		.woocommerce.material .widget_price_filter .ui-slider .ui-slider-handle.ui-state-active:before,
		.material.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle.ui-state-active:before {
			box-shadow: 0 0 0 2px '.esc_attr($nectar_options["accent-color"]).' inset;
		}

		.woocommerce #sidebar .widget_layered_nav ul.yith-wcan-color li.chosen a {
			box-shadow: 0 0 0 2px '.esc_attr($nectar_options["accent-color"]).', inset 0 0 0 3px #fff;
		}
		.woocommerce #sidebar .widget_layered_nav ul.yith-wcan-color li a:hover {
			box-shadow: 0 0 0 2px '.esc_attr($nectar_options["accent-color"]).', 0px 8px 20px rgba(0,0,0,0.2), inset 0 0 0 3px #fff;
		}

		.woocommerce-account .woocommerce > #customer_login .nectar-form-controls .control {
		  background-image: linear-gradient(to right, '.esc_attr($nectar_options["accent-color"]).' 0%, '.esc_attr($nectar_options["accent-color"]).' 100%);
		}

		@media only screen and (max-width: 768px) {
			.woocommerce-page table.cart a.remove {
				background-color:'.esc_attr($nectar_options["accent-color"]).'!important;
			}
		}';


} // end WooCommerce.




// Main Accent Color: other misc properties.
if( isset($nectar_options["accent-color"]) && !empty($nectar_options["accent-color"]) ) {

	$accent_color = substr($nectar_options["accent-color"],1);
	$ac_r         = hexdec( substr( $accent_color, 0, 2 ) );
	$ac_g         = hexdec( substr( $accent_color, 2, 2 ) );
	$ac_b         = hexdec( substr( $accent_color, 4, 2 ) );
	$ac_rgba      = 'rgba('.esc_attr($ac_r).','.esc_attr($ac_g).','.esc_attr($ac_b).', 0.3)';

	echo '.nectar-highlighted-text[data-using-custom-color="false"]:not([data-style="text_outline"]) em{
	  background-image: linear-gradient(to right, '.$ac_rgba.' 0%, '.$ac_rgba.' 100%);
	}

	.nectar-highlighted-text[data-using-custom-color="false"][data-style="regular_underline"] a em,
	.nectar-highlighted-text[data-using-custom-color="false"][data-style="regular_underline"] em.has-link {
	  background-image: linear-gradient(to right, '.$ac_rgba.' 0%, '.$ac_rgba.' 100%),
		                  linear-gradient(to right, '.esc_attr($nectar_options["accent-color"]).' 0%, '.esc_attr($nectar_options["accent-color"]).' 100%);
	}';
}

echo '
.nectar_icon_wrap .svg-icon-holder[data-color="accent-color"] svg path {
	stroke:'. esc_attr($nectar_options["accent-color"]).'!important;
}

body.material[data-button-style^="rounded"] .nectar-button.see-through.accent-color[data-color-override="false"] i:after {
	box-shadow: '.esc_attr($nectar_options["accent-color"]).' 0px 8px 15px; opacity: 0.24;
}

.nectar-fancy-box[data-style="color_box_hover"][data-color="accent-color"]:before {
	box-shadow: 0 30px 90px '.esc_attr($nectar_options["accent-color"]).';
}

.nectar-fancy-box[data-style="hover_desc"][data-color="accent-color"] .box-bg:after {
  background: linear-gradient(to bottom, rgba(0,0,0,0), '.esc_attr($nectar_options["accent-color"]).' 100%);
}

#footer-outer[data-link-hover="underline"][data-custom-color="false"] #footer-widgets ul:not([class*="nectar_blog_posts"]):not(.cart_list) a:not(.tag-cloud-link):not(.nectar-button),
#footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button) {
  background-image: linear-gradient(to right, '.esc_attr($nectar_options["accent-color"]).' 0%, '.esc_attr($nectar_options["accent-color"]).' 100%);
}


#search-results .result .title a {
  background-image: linear-gradient(to right, '.esc_attr($nectar_options["accent-color"]).' 0%, '.esc_attr($nectar_options["accent-color"]).' 100%);
}

.container-wrap .bottom_controls #portfolio-nav ul:first-child  li#all-items a:hover i {
	box-shadow: -.6em 0 '.esc_attr($nectar_options["accent-color"]).',
  -.6em .6em '.esc_attr($nectar_options["accent-color"]).',
  .6em 0 '.esc_attr($nectar_options["accent-color"]).',
  .6em -.6em '.esc_attr($nectar_options["accent-color"]).',
  0 -.6em '.esc_attr($nectar_options["accent-color"]).',
  -.6em -.6em '.esc_attr($nectar_options["accent-color"]).',
  0 .6em '.esc_attr($nectar_options["accent-color"]).',
  .6em .6em '.esc_attr($nectar_options["accent-color"]).';
}


#fp-nav:not(.light-controls).tooltip_alt ul li a.active span,
#fp-nav.tooltip_alt ul li a.active span {
	box-shadow: inset 0 0 0 2px '.esc_attr($nectar_options["accent-color"]).';
	-webkit-box-shadow: inset 0 0 0 2px '.esc_attr($nectar_options["accent-color"]).';
}

.default-loading-icon:before {
	border-top-color:'.esc_attr($nectar_options["accent-color"]).'!important;
}

#header-outer a.cart-contents span:before,
#fp-nav.tooltip ul li .fp-tooltip .tooltip-inner:after {
	border-color: transparent '.esc_attr($nectar_options["accent-color"]).'!important;
}

body .testimonial_slider[data-style="multiple_visible"][data-color*="accent-color"] blockquote .bottom-arrow:after,
body .dark .testimonial_slider[data-style="multiple_visible"][data-color*="accent-color"] blockquote .bottom-arrow:after,
.portfolio-items[data-ps="6"] .bg-overlay,
.portfolio-items[data-ps="6"].no-masonry .bg-overlay,
.nectar_team_member_close .inner,
.nectar_team_member_overlay .team_member_details .bio-inner .mobile-close {
	border-color:'.esc_attr($nectar_options["accent-color"]).';
}

.widget .nectar_widget[class*="nectar_blog_posts_"] .arrow-circle svg circle,
.nectar-woo-flickity[data-controls="arrows-and-text"] .flickity-prev-next-button svg circle.time {
	stroke: '.esc_attr($nectar_options["accent-color"]).';
}

.im-icon-wrap[data-color="accent-color"] path {
		 fill: '.esc_attr($nectar_options["accent-color"]).';
}

';


if( !empty($nectar_options['responsive']) && $nectar_options['responsive'] === '1' ) {

	echo '@media only screen and (min-width : 1px) and (max-width : 1000px) {
		body #featured article .post-title > a { background-color:'. esc_attr($nectar_options["accent-color"]).'; }
		body #featured article .post-title > a { border-color:'. esc_attr($nectar_options["accent-color"]).'; }
	}';

}





// Extra accent colors.

//// Styles that use accent #1 only.
if( ! empty($nectar_options["extra-color-1"]) ) {

	echo '#header-outer .widget_shopping_cart .cart_list li a.remove,
		.original #header-outer .woocommerce.widget_shopping_cart .cart_list li a.remove,
		.stock.out-of-stock,
		#header-outer #top nav > ul > .button_bordered_2 > a:hover,
		#header-outer[data-lhe="default"] #top nav > ul > .button_bordered_2 > a:hover,
		#header-outer[data-lhe="default"] #top nav .sf-menu .button_bordered_2.current-menu-item > a {
				color: '. esc_attr($nectar_options["extra-color-1"]) .'!important;
		}

		#top nav > ul > .button_solid_color_2 > a:before,
		#header-outer.transparent #top nav > ul > .button_solid_color_2 > a:before,
		body[data-slide-out-widget-area-style="slide-out-from-right"]:not([data-header-color="custom"]).material .slide_out_area_close:before,
		#header-outer .widget_shopping_cart a.button,
		.woocommerce ul.products li.product .onsale,
		.woocommerce-page ul.products li.product .onsale,
		.woocommerce span.onsale,
		.woocommerce-page span.onsale {
				background-color: '. esc_attr($nectar_options["extra-color-1"]) .';
		}

		#header-outer .woocommerce.widget_shopping_cart .cart_list li a.remove,
		#header-outer .woocommerce.widget_shopping_cart .cart_list li a.remove,
		#header-outer:not(.transparent) #top nav > ul > .button_bordered_2 > a:hover:before {
			border-color: '. esc_attr($nectar_options["extra-color-1"]) .';
		}

	';
} ////End accent #1 only.


// Main extra color loop.
$available_colors = NectarThemeManager::$available_theme_colors;
array_shift($available_colors); // remove accent-color.

$nectar_extra_accent_colors = array();
foreach( $available_colors as $color_key => $label) {
	$nectar_extra_accent_colors[$color_key] = ( isset($nectar_options[$color_key]) && !empty($nectar_options[$color_key]) ) ? $nectar_options[$color_key] : false;
}

foreach( $nectar_extra_accent_colors as $selector => $color ){

	if( $color !== false ) {

		echo '
		.nectar-color-'.$selector.',
		.testimonial_slider[data-rating-color="'.$selector.'"] .star-rating .filled:before,
		div[data-style="minimal"] .toggle.'.$selector.':hover > .toggle-title a,
		div[data-style="minimal"] .toggle.'.$selector.'.open > .toggle-title a,
		div[data-style="minimal_small"] .toggle.'.$selector.' > .toggle-title a:hover,
		div[data-style="minimal_small"] .toggle.'.$selector.'.open > .toggle-title a {
			color: '. esc_attr($color) .';
		}


		.nectar-milestone .number.'.$selector.', span.'.$selector.',
		.team-member .social.'.$selector.' li a,
		body [class^="icon-"].icon-default-style.'.$selector.',
		body [class^="icon-"].icon-default-style[data-color="'.$selector.'"],
		.team-member .'.$selector.':hover,
		.svg-icon-holder[data-color="'.$selector.'"],
		 .nectar_icon_wrap[data-color="'.$selector.'"] i,
		 body .wpb_row .span_12 .portfolio-filters-inline[data-color-scheme="'.$selector.'-underline"].full-width-section .active,
		 body .wpb_row .span_12 .portfolio-filters-inline[data-color-scheme="'.$selector.'-underline"].full-width-section a:hover,
		 body.material .nectar-button.see-through.'.$selector.'[data-color-override="false"],
		 .nectar_single_testimonial[data-color="'.$selector.'"] p .open-quote,
		 .no-highlight.'.$selector.' h3 {
			color: '. esc_attr($color) .'!important;
		}

		.nectar-bg-'.$selector.',
    #ajax-content-wrap .nectar-bg-pseudo-'.$selector.':before,
    .wpb_row .nectar-post-grid-filters[data-active-color="'.$selector.'"] a:after,
		.testimonial_slider[data-style="multiple_visible"][data-color*="'.$selector.'"] .flickity-page-dots .dot.is-selected:before,
		.testimonial_slider[data-style="multiple_visible"][data-color*="'.$selector.'"] blockquote.is-selected p,
		.nectar-button.nectar_video_lightbox[data-color="default-'.$selector.'"],
		.nectar_video_lightbox.nectar-button[data-color="transparent-'.$selector.'"]:hover,
    .nectar-cta[data-color="'.$selector.'"]:not([data-style="material"]) .link_wrap,
    .nectar-flickity[data-controls*="arrows_overlaid"][data-control-color="'.$selector.'"] .flickity-prev-next-button:hover:before,
    .nectar-flickity[data-controls="default"][data-control-color="'.$selector.'"] .flickity-page-dots .dot:before,
    .nectar-flickity[data-controls="touch_total"][data-control-color="'.$selector.'"] .visualized-total span,
    .nectar-post-grid-wrap[data-load-more-color="'.$selector.'"] .load-more:hover,
		[class*=" icon-"].'.$selector.'.icon-normal,
		div[data-style="minimal"] .toggle.'.$selector.'.open i:after,
		div[data-style="minimal"] .toggle.'.$selector.':hover i:after,
		div[data-style="minimal"] .toggle.open.'.$selector.' i:before,
		div[data-style="minimal"] .toggle.'.$selector.':hover i:before,
		div[data-style="minimal_small"] .toggle.'.$selector.' > .toggle-title:after,
		.toggle.open.'.$selector.' > .toggle-title a,
    .nectar-hor-list-item[data-hover-effect="full_border"][data-color="'.$selector.'"] .nectar-list-item-btn:hover {
			background-color: '. esc_attr($color) .';
		}

		.nectar-button.regular-button.'.$selector.',
		.nectar-button.tilt.'.$selector.',
		body .swiper-slide .button.transparent_2 .'.$selector.':hover,
		#sidebar .widget:hover [class^="icon-"].icon-3x.'.$selector.':not(.alt-style),
		.portfolio-filters-inline[data-color-scheme="'.$selector.'"],
    .portfolio-filters[data-color-scheme="'.$selector.'"] #sort-portfolio,
    .portfolio-filters[data-color-scheme="'.$selector.'"] a,
		.nectar-fancy-box[data-color="'.$selector.'"]:after,
		.divider-small-border[data-color="'.$selector.'"],
		.divider-border[data-color="'.$selector.'"],
		.nectar-animated-title[data-color="'.$selector.'"] .nectar-animated-title-inner:after,
		.portfolio-filters-inline[data-color-scheme="'.$selector.'-underline"] a:after,
		.nectar-video-box[data-color="'.$selector.'"] .nectar_video_lightbox,
		body .nectar-video-box[data-color="'.$selector.'"][data-hover="zoom_button"] .nectar_video_lightbox:after,
		.nectar_video_lightbox.play_button_with_text[data-color="'.$selector.'"]:not([data-style="small"]) .play > .inner-wrap:before,
		body.material .nectar-video-box[data-color="'.$selector.'"] .nectar_video_lightbox:before,
		.nectar-hor-list-item[data-color="'.$selector.'"]:before,
		.nectar-fancy-box[data-color="'.$selector.'"]:not([data-style="default"]) .box-bg:after,
		body.material[data-button-style^="rounded"] .nectar-button.see-through.'.$selector.'[data-color-override="false"] i,
		.nectar-recent-posts-single_featured.multiple_featured .controls[data-color="'.$selector.'"] li:after,
		.'.$selector.'.icon-normal,
		.bar_graph li .'.$selector.',
		.nectar-progress-bar .'.$selector.',
		.swiper-slide .button.solid_color .'.$selector.',
		.swiper-slide .button.solid_color_2 .'.$selector.'
		{
			background-color: '. esc_attr($color).'!important;
		}


		.nectar_icon_wrap .svg-icon-holder[data-color="'.$selector.'"] svg path {
			stroke:'. esc_attr($color) .'!important;
		}

		body.material[data-button-style^="rounded"] .nectar-button.see-through.'.$selector.'[data-color-override="false"] i:after {
			box-shadow: '. esc_attr($color) .' 0px 8px 15px; opacity: 0.24;
		}

		.nectar-fancy-box[data-style="color_box_hover"][data-color="'.$selector.'"]:before {
			box-shadow: 0 30px 90px '. esc_attr($color) .';
		}
    .nectar-fancy-box[data-style="hover_desc"][data-color="'.$selector.'"] .box-bg:after {
      background: linear-gradient(to bottom, rgba(0,0,0,0), '.esc_attr($color).' 100%);
    }


		body .testimonial_slider[data-style="multiple_visible"][data-color*="'.$selector.'"] blockquote .bottom-arrow:after,
		body .dark .testimonial_slider[data-style="multiple_visible"][data-color*="'.$selector.'"] blockquote .bottom-arrow:after,
		div[data-style="minimal"] .toggle.open.'.$selector.' i, div[data-style="minimal"] .toggle.'.$selector.':hover i,
		.span_12.dark .nectar_video_lightbox.play_button_with_text[data-color="'.$selector.'"] .play:before,
		.span_12.dark .nectar_video_lightbox.play_button_with_text[data-color="'.$selector.'"] .play:after,
    .nectar-hor-list-item[data-hover-effect="full_border"][data-color="'.$selector.'"]:hover {
			border-color:'. esc_attr($color) .';
		}

		body.material .nectar-button.see-through.'.$selector.'[data-color-override="false"] {
			border-color:'. esc_attr($color) .'!important;
		}


		.im-icon-wrap[data-color="'.$selector.'"] path {
				 fill: '. esc_attr($color) .';
		}

		';


	} // End color is set.
} // End main extra accent color loop.




//Gradient colors.

////Styles that use gradient #1 only.
if( isset($nectar_options["extra-color-gradient"]) && $nectar_options["extra-color-gradient"]['to'] && $nectar_options["extra-color-gradient"]['from'] ) {
	echo '.widget .nectar_widget[class*="nectar_blog_posts_"][data-style="hover-featured-image-gradient-and-counter"] > li a .popular-featured-img:after {
		background: '.esc_attr($nectar_options["extra-color-gradient"]['from']).';
		background: linear-gradient(to right, '.esc_attr($nectar_options["extra-color-gradient"]['from']).', '.esc_attr($nectar_options["extra-color-gradient"]['to']).');
	}';
} ////End gradient #1 only.


////Main Gradient loop.
$nectar_gradient_colors = array(
	'extra-color-gradient-1' => ( isset($nectar_options["extra-color-gradient"]) && $nectar_options["extra-color-gradient"]['to'] && $nectar_options["extra-color-gradient"]['from'] ) ? array($nectar_options["extra-color-gradient"]['to'],$nectar_options["extra-color-gradient"]['from']) : false,
	'extra-color-gradient-2' => ( isset($nectar_options["extra-color-gradient-2"]) && $nectar_options["extra-color-gradient-2"]['to'] && $nectar_options["extra-color-gradient-2"]['from'] ) ? array($nectar_options["extra-color-gradient-2"]['to'],$nectar_options["extra-color-gradient-2"]['from']) : false
);

foreach( $nectar_gradient_colors as $selector => $color_grad ){

	if( $color_grad !== false ) {

		$accent_gradient_from = $color_grad[0];
		$accent_gradient_to 	= $color_grad[1];

		echo '.nectar-bg-'.$selector.',
    #ajax-content-wrap .nectar-bg-pseudo-'.$selector.':before,
		.divider-small-border[data-color="'.$selector.'"],
		.divider-border[data-color="'.$selector.'"],
		.nectar-progress-bar .'.$selector.',
    .wpb_row .nectar-post-grid-filters[data-active-color="'.$selector.'"] a:after,
		.nectar-recent-posts-single_featured.multiple_featured .controls[data-color="'.$selector.'"] li:after,
		.nectar-fancy-box[data-style="default"][data-color="'.$selector.'"]:after {
			background: '.esc_attr($accent_gradient_from).';
		  background: linear-gradient(to right, '.esc_attr($accent_gradient_from).', '.esc_attr($accent_gradient_to).');
		}

		.icon-normal.'.$selector.',
		body [class^="icon-"].icon-3x.alt-style.'.$selector.',
		.nectar-button.'.$selector.':after,
    .nectar-cta[data-color="'.$selector.'"]:not([data-style="material"]) .link_wrap,
		.nectar-button.see-through-'.$selector.':after,
		.nectar-fancy-box[data-style="color_box_hover"][data-color="'.$selector.'"] .box-bg:after,
    .nectar-post-grid-wrap[data-load-more-color="'.$selector.'"] .load-more:before {
			 background: '.esc_attr($accent_gradient_from).';
		   background: linear-gradient(to bottom right, '.esc_attr($accent_gradient_from).', '.esc_attr($accent_gradient_to).');
		}

		body.material .nectar-button.regular.m-'.$selector.',
		body.material .nectar-button.see-through.m-'.$selector.':before,
		.swiper-slide .button.solid_color .'.$selector.',
		.swiper-slide .button.transparent_2 .'.$selector.':before {
			 background: '.esc_attr($accent_gradient_from).';
		   background: linear-gradient(125deg, '.esc_attr($accent_gradient_from).', '.esc_attr($accent_gradient_to).');
		}
		body.material .nectar-button.regular.m-'.$selector.':before {
			 background: '.esc_attr($accent_gradient_to).';
		}


		.nectar-fancy-box[data-style="color_box_hover"][data-color="'.$selector.'"]:before {
			box-shadow: 0px 30px 90px '.esc_attr($accent_gradient_to).';
		}

		.nectar-color-'.$selector.',
		.testimonial_slider[data-rating-color="'.$selector.'"] .star-rating .filled:before {
			 color: '.esc_attr($accent_gradient_from).';
			 background: linear-gradient(to right, '.esc_attr($accent_gradient_from).', '.esc_attr($accent_gradient_to).');
			 -webkit-background-clip: text;
			 -webkit-text-fill-color: transparent;
			 background-clip: text;
		}

		.nectar-button.'.$selector.',
		.nectar-button.see-through-'.$selector.' {
			 border-width: 3px;
			 border-style: solid;
		   -moz-border-image: -moz-linear-gradient(top right, '.esc_attr($accent_gradient_from).' 0%, '.esc_attr($accent_gradient_to).' 100%);
		   -webkit-border-image: -webkit-linear-gradient(top right, '.esc_attr($accent_gradient_from).' 0%,'.esc_attr($accent_gradient_to).' 100%);
		   border-image: linear-gradient(to bottom right, '.esc_attr($accent_gradient_from).' 0%, '.esc_attr($accent_gradient_to).' 100%);
		   border-image-slice: 1;
		}

		[class^="icon-"][data-color="'.$selector.'"]:before,
		[class*=" icon-"][data-color="'.$selector.'"]:before,
		[class^="icon-"].'.$selector.':not(.icon-normal):before,
		[class*=" icon-"].'.$selector.':not(.icon-normal):before,
		.nectar_icon_wrap[data-color="'.$selector.'"]:not([data-style="shadow-bg"]) i {
			  color: '.esc_attr($accent_gradient_from).';
			  background: linear-gradient(to bottom right, '.esc_attr($accent_gradient_from).', '.esc_attr($accent_gradient_to).');
			  -webkit-background-clip: text;
			  -webkit-text-fill-color: transparent;
			  background-clip: text;
			  display: initial;
		}
		.nectar-button.'.$selector.' .hover,
		.nectar-button.see-through-'.$selector.' .start {
			  background: '.esc_attr($accent_gradient_from).';
			  background: linear-gradient(to bottom right, '.esc_attr($accent_gradient_from).', '.esc_attr($accent_gradient_to).');
			  -webkit-background-clip: text;
			  -webkit-text-fill-color: transparent;
			  background-clip: text;
			  display: initial;
		}

		.nectar-button.'.$selector.'.no-text-grad .hover,
		.nectar-button.see-through-'.$selector.'.no-text-grad .start {
			 background: transparent!important;
			 color: '.esc_attr($accent_gradient_from).'!important;
		}';



	} // End color gradient is set.
} // End main gradient color loop.


// CSS Color vars.
echo ':root { --nectar-accent-color: '. esc_attr($nectar_options["accent-color"]) .';';

foreach( $nectar_extra_accent_colors as $selector => $color ) {
	if( false !== $color ) {
		echo '--nectar-'.esc_attr($selector).': '. esc_attr($color) .'; ';
	}
}

echo '}';




///////// Custom bg/font colors.
if( !empty($nectar_options['overall-bg-color']) ) {

	echo ':root { --nectar-bg-color: '. esc_attr($nectar_options['overall-bg-color']) .'; }';

	echo '
	body,
	.container-wrap,
	.material .ocm-effect-wrap,
	.project-title,
	.ascend .container-wrap,
	.ascend .project-title,
	body .vc_text_separator div,
	.carousel-wrap[data-full-width="true"] .carousel-heading,
	.carousel-wrap .left-border,
	.carousel-wrap .right-border,
	.single-post.ascend #page-header-bg.fullscreen-header,
	.single-post #single-below-header.fullscreen-header,
	#page-header-wrap,
	.page-header-no-bg,
	#full_width_portfolio .project-title.parallax-effect,
	.portfolio-items .col,
	.page-template-template-portfolio-php .portfolio-items .col.span_3,
	.page-template-template-portfolio-php .portfolio-items .col.span_4,
	body .nectar-quick-view-box div.product .product div.summary,
	.nectar-global-section.before-footer,
	.nectar-global-section.after-nav,
	body.box-rolling,
	body[data-footer-reveal="1"].ascend.box-rolling,
	body[data-footer-reveal="1"].box-rolling,
  .wpml-ls-statics-footer {
		background-color: '.esc_attr($nectar_options['overall-bg-color']).';
	}';
}

if( !empty($nectar_options['overall-font-color']) ) {

	echo 'body,
	body h1,
	body h2,
	body h3,
	body h4,
	body h5,
	body h6,
	.woocommerce div.product .woocommerce-tabs .full-width-content ul.tabs li a,
	.woocommerce .woocommerce-breadcrumb a,
	.woocommerce .woocommerce-breadcrumb i,
	body:not(.original) .comment-list .comment-author,
	body:not(.original) .comment-list .pingback .comment-body > a,
	.post-area.standard-minimal .post .more-link span,
	#sidebar .widget .nectar_widget[class*="nectar_blog_posts_"] > li .post-date {
		color: '.esc_attr($nectar_options['overall-font-color']).';
	}';

	if( 'ascend' === $theme_skin ) {
		echo '.ascend #author-bio .nectar-button:not(:hover) {
			color: '.esc_attr($nectar_options['overall-font-color']).'!important;
			border-color: '.esc_attr($nectar_options['overall-font-color']).'!important;
		}';
	}
	if( in_array($theme_skin , array('material','ascend')) ) {
		echo '.comment-list .comment-meta a:not(:hover),
		      .material .comment-list .reply a:not(:hover) {
			color: '.esc_attr($nectar_options['overall-font-color']).';
		}';
	}
	if ($woocommerce) {
		echo '
		.woocommerce-tabs .full-width-content[data-tab-style="fullwidth"] ul.tabs li a,
		.woocommerce .woocommerce-breadcrumb a,
		.nectar-shop-header > .woocommerce-ordering .select2-container--default .select2-selection__rendered,
		.woocommerce div.product .woocommerce-review-link,
		.woocommerce.single-product div.product_meta a {
			background-image: linear-gradient(to right, '.esc_attr($nectar_options['overall-font-color']).' 0%, '.esc_attr($nectar_options['overall-font-color']).' 100%);
		}
		#sidebar .price_slider_amount .price_label,
		#sidebar .price_slider_amount button.button[type="submit"]:not(:hover),
		#sidebar .price_slider_amount button.button:not(:hover) {
			color: '.esc_attr($nectar_options['overall-font-color']).';
		}';
	}
  echo '#sidebar h4,
	.ascend.woocommerce #sidebar h4,
  body .row .col.section-title span {
   color: '.esc_attr($nectar_options['overall-font-color']).';
  }
	#ajax-content-wrap ul.products li.product.minimal .price {
		color: '.esc_attr($nectar_options['overall-font-color']).'!important;
	}
  .single .heading-title[data-header-style="default_minimal"] .meta-category a {
    color: '.esc_attr($nectar_options['overall-font-color']).';
    border-color: '.esc_attr($nectar_options['overall-font-color']).';
  }';

	if( $nectar_options['overall-font-color'] !== '#000000' && $nectar_options['overall-font-color'] !== '#0a0a0a' &&
	    $nectar_options['overall-font-color'] !== '#111111' && $nectar_options['overall-font-color'] !== '#222222' &&
			$nectar_options['overall-font-color'] !== '#333333') {

		echo '.full-width-section > .col.span_12.dark, .full-width-content > .col.span_12.dark {
			color: '.esc_attr($nectar_options['overall-font-color']).';
		}';

		echo '
		.full-width-section  > .col.span_12.dark .portfolio-items .col h3,
		.full-width-section  > .col.span_12.dark .portfolio-items[data-ps="6"] .work-meta h4 { color: #fff; } ';

	}
}



///////// Custom header colors.
if( !empty($nectar_options['header-color']) && $nectar_options['header-color'] === 'custom' ) {

	if( !empty($nectar_options['header-background-color']) ) {
		echo 'body #header-outer,
		body #search-outer,
		body.ascend #search-outer,
    body[data-header-format="left-header"].ascend #search-outer,
		.material #header-space,
		#header-space,
		.material #header-outer .bg-color-stripe,
		.material #search-outer .bg-color-stripe,
		.material #header-outer #search-outer:before,
		.material #search-outer .nectar-ajax-search-results,
    body[data-header-format="left-header"].material #search-outer,
		body.material[data-header-format="centered-menu-bottom-bar"] #page-header-wrap.fullscreen-header,
		body #header-outer #mobile-menu:before,
		.nectar-slide-in-cart.style_slide_in_click {
			background-color:'.esc_attr($nectar_options['header-background-color']).';
		}
		body .nectar-slide-in-cart:not(.style_slide_in_click) .blockUI.blockOverlay {
			background-color:'.esc_attr($nectar_options['header-background-color']).'!important;
		}';
	}

	 // Custom header bg opacity.
	 if( !empty($nectar_options['header-bg-opacity']) ) {

	 		 $navBGColor = esc_attr($nectar_options['header-background-color']);
	 		 $navBGColor = substr($navBGColor,1);
			 $colorR = hexdec( substr( $navBGColor, 0, 2 ) );
			 $colorG = hexdec( substr( $navBGColor, 2, 2 ) );
			 $colorB = hexdec( substr( $navBGColor, 4, 2 ) );
			 $colorA = ($nectar_options['header-bg-opacity'] != '100') ? '0.'.esc_attr($nectar_options['header-bg-opacity']) : esc_attr($nectar_options['header-bg-opacity']);

			 echo 'body #header-outer, body[data-header-color="dark"] #header-outer {
				 background-color: rgba('.$colorR.','.$colorG.','.$colorB.','.$colorA.');
			 }';

			 // Material search.
			 echo '.material #header-outer:not(.transparent) .bg-color-stripe {
				 display: none;
			 }';
	}

	if( !empty($nectar_options['header-font-color']) ) {
		echo '
		#header-outer #top nav > ul > li > a,
    #header-outer .slide-out-widget-area-toggle a i.label,
		#header-outer:not(.transparent) #top #logo,
		#header-outer:not(.transparent) #top .logo-clone,
		#header-outer #top .span_9 > .slide-out-widget-area-toggle i,
		#header-outer #top .sf-sub-indicator i,
		body[data-header-color="custom"].ascend #boxed #header-outer .cart-menu .cart-icon-wrap i,
		#header-outer #top nav ul #nectar-user-account a span,
		#header-outer #top #toggle-nav i,
    .material #header-outer:not([data-permanent-transparent="1"]) .mobile-search .icon-salient-search,
		#header-outer:not([data-permanent-transparent="1"]) .mobile-user-account .icon-salient-m-user,
		#header-outer:not([data-permanent-transparent="1"]) .mobile-search .icon-salient-search,
		#header-outer #top #mobile-cart-link i,
		#header-outer .cart-menu .cart-icon-wrap .icon-salient-cart,
		body[data-header-format="left-header"] #header-outer #social-in-menu a,
		#header-outer #top nav ul #search-btn a span,
		#search-outer #search input[type="text"],
		#search-outer #search #close a span,
		body.ascend #search-outer #search #close a span,
		body.ascend #search-outer #search input[type="text"],
		.material #search-outer #search .span_12 span,
		.style_slide_in_click .total,
		.style_slide_in_click .total strong,
		.nectar-slide-in-cart.style_slide_in_click h4,
		.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart,
		.nectar-slide-in-cart.style_slide_in_click .widget_shopping_cart .cart_list.woocommerce-mini-cart .mini_cart_item a,
		.style_slide_in_click .woocommerce-mini-cart__empty-message h3 {
			color:'.esc_attr($nectar_options['header-font-color']).'!important;
		}
    body #header-outer .nectar-header-text-content,
		.nectar-ajax-search-results .search-post-item,
		.nectar-ajax-search-results ul.products li.product,
		#header-outer:not(.transparent) .nectar-mobile-only.mobile-header {
      color:'.esc_attr($nectar_options['header-font-color']).';
    }

    .material #header-outer #search-outer input::-webkit-input-placeholder,
    body[data-header-format="left-header"].material #search-outer input::-webkit-input-placeholder {
      color:'.esc_attr($nectar_options['header-font-color']).'!important;
    }';

		if( 'simple' === $off_canvas_style ) {
			echo '#header-outer #mobile-menu ul li a,
			#header-outer #mobile-menu ul li a .item_desc,
			#header-outer #mobile-menu .below-menu-items-wrap p {
				color:'.esc_attr($nectar_options['header-font-color']).'!important;
			}';
		}


		echo '
		#header-outer #top .slide-out-widget-area-toggle a .lines:after,
		#header-outer #top .slide-out-widget-area-toggle a .lines:before,
		#header-outer #top .slide-out-widget-area-toggle a .lines-button:after,
		body.material.mobile #header-outer.transparent:not([data-permanent-transparent="1"]) header .slide-out-widget-area-toggle a .close-line,
		body.material.mobile #header-outer:not([data-permanent-transparent="1"]) header .slide-out-widget-area-toggle a .close-line,
		#search-outer .close-wrap .close-line,
		#header-outer:not(.transparent) #top .slide-out-widget-area-toggle .close-line,
		.nectar-slide-in-cart.style_slide_in_click .close-cart .close-line,
		.nectar-ajax-search-results h4 a:before {
			background-color:'.esc_attr($nectar_options['header-font-color']).';
		}';

		echo '#top nav > ul > .button_bordered > a:before,
		#header-outer:not(.transparent) #top .slide-out-widget-area-toggle .close-line {
			border-color:'.esc_attr($nectar_options['header-font-color']).';
		}';
		if( nectar_is_contained_header() ) {
			echo '#header-outer.transparent #top .slide-out-widget-area-toggle .close-line {
				background-color:'.esc_attr($nectar_options['header-font-color']).';
			}';
		}
	}


	if( 'default' === $header_hover_animation && !empty($nectar_options['header-font-hover-color']) ) {
		echo '#header-outer[data-lhe="default"] #top nav > ul > li > a:hover,
		#header-outer[data-lhe="default"] #top nav .sf-menu > .sfHover:not(#social-in-menu) > a,
		body #header-outer[data-lhe="default"] #top nav > ul > li > a:hover,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .sfHover:not(#social-in-menu) > a,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current-menu-item > a,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current_page_item > a .sf-sub-indicator i,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current_page_ancestor > a,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a i,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current_page_item > a,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current_page_parent > a,
		body #header-outer[data-lhe="default"] #top nav .sf-menu > .current-menu-ancestor > a,
		#header-outer:not(.transparent) .nectar-mobile-only.mobile-header a:hover,
		#header-outer:not(.transparent) .nectar-mobile-only.mobile-header li[class*="current"] a {
			color:'.esc_attr($nectar_options['header-font-hover-color']).'!important;
		}';

		echo '#header-outer:not(.transparent) .nectar-mobile-only.mobile-header li[class*="current-"] a,
		#header-outer:not(.transparent) .nectar-mobile-only.mobile-header li a:active {
		  color:'.esc_attr($nectar_options['header-font-hover-color']).';
		}';
	} 
	
	else if( 'animated_underline' === $header_hover_animation && !empty($nectar_options['header-font-hover-color']) ) {
		echo '#header-outer:not(.transparent) .nectar-mobile-only.mobile-header .menu-title-text:after {
			border-color:'. esc_attr($nectar_options['header-font-hover-color']) .'!important;
		 }';
	}

	if( !empty($nectar_options['header-font-hover-color']) ) {
		echo '
    #header-outer .slide-out-widget-area-toggle a:hover i.label,
		body #header-outer:not(.transparent) #social-in-menu a i:after,
		.ascend #header-outer:not(.transparent) .cart-outer:hover .cart-menu-wrap:not(.has_products) .icon-salient-cart,
		body.material #header-outer:not(.transparent) .cart-outer:hover .cart-menu-wrap .icon-salient-cart,
		body #top nav .sf-menu > .current_page_ancestor > a .sf-sub-indicator i,
		body #top nav .sf-menu > .current_page_item > a .sf-sub-indicator i,
		#header-outer #top .sf-menu > .sfHover > a .sf-sub-indicator i,
		#header-outer #top .sf-menu > li > a:hover .sf-sub-indicator i,
		#header-outer #top nav ul #search-btn a:hover span,
		#header-outer #top nav ul #nectar-user-account a:hover span,
		#header-outer #top nav ul .slide-out-widget-area-toggle a:hover span,
		body:not(.material) #search-outer #search #close a span:hover {
			color:'.esc_attr($nectar_options['header-font-hover-color']).'!important;
		}
    #top .sf-menu > li.nectar-regular-menu-item > a:hover > .nectar-menu-icon,
    #top .sf-menu > li.nectar-regular-menu-item.sfHover > a > .nectar-menu-icon,
    #top .sf-menu > li.nectar-regular-menu-item[class*="current-"] > a > .nectar-menu-icon,
    #header-outer[data-lhe="default"]:not(.transparent) .nectar-header-text-content a:hover {
      color:'.esc_attr($nectar_options['header-font-hover-color']).';
    }
		.nectar-ajax-search-results .search-post-item h5 {
		  background-image: linear-gradient(to right, '.esc_attr($nectar_options['header-font-hover-color']).' 0%, '.esc_attr($nectar_options['header-font-hover-color']).' 100%);
		}
    ';


		if( 'simple' === $off_canvas_style ) {
			echo '#header-outer #mobile-menu ul li a:hover,
			#header-outer #mobile-menu ul li a:hover .sf-sub-indicator i,
			#header-outer #mobile-menu ul li a:focus,
			#header-outer #mobile-menu ul li a:focus .sf-sub-indicator i,
			#header-outer #mobile-menu ul li[class*="current"] > a,
			#header-outer #mobile-menu ul li[class*="current"] > a i {
				color:'.esc_attr($nectar_options['header-font-hover-color']).'!important;
			}';
		}

		echo '
		#header-outer:not(.transparent) #top nav ul .slide-out-widget-area-toggle a:hover .lines:after,
		#header-outer:not(.transparent) #top nav ul .slide-out-widget-area-toggle a:hover .lines:before,
		#header-outer:not(.transparent) #top nav ul .slide-out-widget-area-toggle a:hover .lines-button:after,
		body[data-header-format="left-header"] #header-outer[data-lhe="animated_underline"] #top nav > ul > li:not([class*="button_"]) > a > span:after,
    #header-outer[data-lhe="animated_underline"] .nectar-header-text-content a:after {
			background-color:'.esc_attr($nectar_options['header-font-hover-color']).'!important;
		}';

		echo '
		#header-outer[data-lhe="animated_underline"] #top nav > ul > li > a .menu-title-text:after,
		body.material #header-outer #search-outer #search input[type="text"],
    body[data-header-format="left-header"].material #search-outer #search input[type="text"] {
			border-color:'.esc_attr($nectar_options['header-font-hover-color']).';
		}';

	} // End custom header font color.

	// Button BG hover effect
	if( 'button_bg' === $header_hover_animation ) {
		$header_btn_bg = (isset($nectar_options['header-font-button-bg']) && !empty($nectar_options['header-font-button-bg'])) ? $nectar_options['header-font-button-bg'] : false ;
		$header_btn_bg_active = (isset($nectar_options['header-font-button-bg-active']) && !empty($nectar_options['header-font-button-bg-active'])) ? $nectar_options['header-font-button-bg-active'] : false ;
		$header_btn_text_active = (isset($nectar_options['header-font-button-text-active']) && !empty($nectar_options['header-font-button-text-active'])) ? $nectar_options['header-font-button-text-active'] : false ;

		if( $header_btn_bg ) {
			echo '#top .sf-menu > li:not([class*="menu-item-btn"]) > a .menu-title-text:before {
				background-color: '.$header_btn_bg.';	
			}';
		}

		if( $header_btn_bg_active ) {
			echo '#top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a .menu-title-text:before {
				background-color: '.$header_btn_bg_active.';	
			}';
		}
		if( $header_btn_text_active ) {
			echo '#top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a .menu-title-text {
				color: '.$header_btn_text_active.';	
			}
			#header-outer #top .sf-menu > li[class*="current"]:not([class*="menu-item-btn"]) > a > .sf-sub-indicator i {
				color: '.$header_btn_text_active.'!important;	
			}';
		}

		if( !empty($nectar_options['header-font-hover-color']) ) {
			echo '
			#header-outer #top nav .sf-menu > .sfHover:not([class*="current"]):not(#social-in-menu) > a .menu-title-text,
			#header-outer #top nav > ul > li:not([class*="current"]) > a:hover .menu-title-text {
				color: '.$nectar_options['header-font-hover-color'].';
			}';
		}
		
	}


	if( !empty($nectar_options['header-icon-color']) ) {
    echo '#top .sf-menu > li.nectar-regular-menu-item > a > .nectar-menu-icon {
      color:'.esc_attr($nectar_options['header-icon-color']).';
    }';
  }


	if( !empty($nectar_options['header-dropdown-background-color']) ) {
		echo '
		#search-outer .ui-widget-content,
		body:not([data-header-format="left-header"]) #top .sf-menu li ul,
		#header-outer nav > ul > .megamenu > .sub-menu,
		body #header-outer nav > ul > .megamenu > .sub-menu > li > a,
		#header-outer .widget_shopping_cart .cart_list a,
		#header-outer .widget_shopping_cart .cart_list li,
		#header-outer .widget_shopping_cart_content,
		.woocommerce .cart-notification,
		#header-secondary-outer ul ul li a,
		#header-secondary-outer .sf-menu li ul {
			background-color:'.esc_attr($nectar_options['header-dropdown-background-color']).';
		}';

		echo 'body[data-header-format="left-header"] #header-outer .cart-outer .cart-notification:after {
			border-color: transparent transparent '.esc_attr($nectar_options['header-dropdown-background-color']).' transparent;
		} ';
	}

	if( !empty($nectar_options['header-dropdown-background-hover-color']) ) {

		if( true !== $using_underline_dropdown_effect ) {
			echo '
			#top .sf-menu li ul li a:hover,
			body #top nav .sf-menu ul .sfHover > a,
			#top .sf-menu li ul .current-menu-item > a,
			#top .sf-menu li ul .current-menu-ancestor > a,
			#header-outer nav > ul > .megamenu > ul ul li a:hover,
			#header-outer nav > ul > .megamenu > ul ul li a:focus,
			#header-outer nav > ul > .megamenu > ul ul .current-menu-item > a,
			#header-secondary-outer ul ul li a:hover,
			#header-secondary-outer ul ul li a:focus,
			#header-secondary-outer ul > li:not(.megamenu) ul a:hover,
			body #header-secondary-outer .sf-menu ul .sfHover > a,
			#search-outer .ui-widget-content li:hover,
			#search-outer .ui-state-hover,
			#search-outer .ui-widget-content .ui-state-hover,
			#search-outer .ui-widget-header .ui-state-hover,
			#search-outer .ui-state-focus,
			#search-outer .ui-widget-content .ui-state-focus,
			#search-outer .ui-widget-header .ui-state-focus,
			#header-outer #top nav > ul > li:not(.megamenu) ul a:hover,
			#header-outer #top nav > ul > li:not(.megamenu) .sfHover > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) .sfHover > a,
			#header-outer nav > ul > .megamenu > ul ul .sfHover > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul a:hover,
			body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul ul .current-menu-item > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-item > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-ancestor > a {
				background-color:'.esc_attr($nectar_options['header-dropdown-background-hover-color']).'!important;
			}';

		} else {
			echo '
			#search-outer .ui-widget-content li:hover,
			#search-outer .ui-widget-content .ui-state-hover,
			#search-outer .ui-widget-header .ui-state-hover,
			#search-outer .ui-widget-content .ui-state-focus,
			#search-outer .ui-widget-header .ui-state-focus {
				background-color:'.esc_attr($nectar_options['header-dropdown-background-hover-color']).'!important;
			}';
		}

	}

	if( !empty($nectar_options['header-dropdown-font-color']) ) {
		echo '
		#search-outer .ui-widget-content li a,
		#search-outer .ui-widget-content i,
		#top .sf-menu li ul li a,
		body #header-outer .widget_shopping_cart .cart_list a,
		#header-secondary-outer ul ul li a,
		.woocommerce .cart-notification .item-name,
		.cart-outer .cart-notification,
		#header-outer #top .sf-menu li ul .sf-sub-indicator i,
		#header-outer .widget_shopping_cart .quantity,
		#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul a,
		#header-outer .cart-notification .item-name,
		#header-outer #top nav > ul > .nectar-woo-cart .cart-outer .widget ul a:hover,
		#header-outer .cart-outer .total strong,
		#header-outer .cart-outer .total,
		#header-outer ul.product_list_widget li dl dd,
		#header-outer ul.product_list_widget li dl dt {
			color:'.esc_attr($nectar_options['header-dropdown-font-color']).'!important;
		}';

    echo '
    .sf-menu .widget-area-active .widget *,
    .sf-menu .widget-area-active:hover .widget * {
      color:'.esc_attr($nectar_options['header-dropdown-font-color']).';
    }
    ';
	}

  if( !empty($nectar_options['header-dropdown-icon-color']) ) {
    echo '#top .sf-menu > li li > a > .nectar-menu-icon {
      color:'.esc_attr($nectar_options['header-dropdown-icon-color']).';
    }';
  }

	if( !empty($nectar_options['header-dropdown-font-hover-color']) ) {

		if( true === $using_underline_dropdown_effect ) {

					echo '#search-outer .ui-widget-content li:hover *,
					#search-outer .ui-widget-content .ui-state-focus *,
					body nav .sf-menu ul .sfHover > a .sf-sub-indicator i,
					body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li:hover > a {
						color:'.esc_attr($nectar_options['header-dropdown-font-hover-color']).'!important;
					}';

		} else {

			echo '
			#search-outer .ui-widget-content li:hover *,
			#search-outer .ui-widget-content .ui-state-focus *,
			body #top nav .sf-menu ul .sfHover > a,
			#header-secondary-outer ul ul li:hover > a,
			#header-secondary-outer ul ul li:hover > a i,
			#header-secondary-outer ul .sfHover > a,
	    body[data-dropdown-style="minimal"] #header-secondary-outer ul > li:not(.megamenu) .sfHover > a,
			body #top nav .sf-menu ul .sfHover > a .sf-sub-indicator i,
			body #top nav .sf-menu ul li:hover > a .sf-sub-indicator i,
			body #top nav .sf-menu ul li:hover > a,
			body #top nav .sf-menu ul .current-menu-item > a,
			body #top nav .sf-menu ul .current_page_item > a .sf-sub-indicator i,
			body #top nav .sf-menu ul .current_page_ancestor > a .sf-sub-indicator i,
			body #top nav .sf-menu ul .sfHover > a,
			body #top nav .sf-menu ul .current_page_ancestor > a,
			body #top nav .sf-menu ul .current-menu-ancestor > a,
			body #top nav .sf-menu ul .current_page_item > a,
			body .sf-menu ul li ul .sfHover > a .sf-sub-indicator i,
			body .sf-menu > li > a:active > .sf-sub-indicator i,
			body .sf-menu > .sfHover > a > .sf-sub-indicator i,
			body .sf-menu li ul .sfHover > a,
			#header-outer nav > ul > .megamenu > ul ul .current-menu-item > a,
			#header-outer nav > ul > .megamenu > ul > li > a:hover,
			#header-outer nav > ul > .megamenu > ul > .sfHover > a,
			body #header-outer nav > ul > .megamenu ul li:hover > a,
			#header-outer #top nav ul li .sfHover > a .sf-sub-indicator i,
			#header-outer #top nav > ul > .megamenu > ul ul li a:hover,
	    #header-outer #top nav > ul > .megamenu > ul ul li a:focus,
			#header-outer #top nav > ul > .megamenu > ul ul .sfHover > a,
			#header-outer #header-secondary-outer nav > ul > .megamenu > ul ul li a:hover,
	    #header-outer #header-secondary-outer nav > ul > .megamenu > ul ul li a:focus,
			#header-outer #header-secondary-outer nav > ul > .megamenu > ul ul .sfHover > a,
			#header-outer #top nav ul li li:hover > a .sf-sub-indicator i,
	    #header-outer[data-format="left-header"] .sf-menu .sub-menu .current-menu-item > a,
			body:not([data-header-format="left-header"]) #header-outer #top nav > ul > .megamenu > ul ul .current-menu-item > a,
			body:not([data-header-format="left-header"]) #header-outer #header-secondary-outer nav > ul > .megamenu > ul ul .current-menu-item > a,
			#header-outer #top nav > ul > li:not(.megamenu) ul a:hover,
	    body[data-dropdown-style="minimal"] #header-secondary-outer ul >li:not(.megamenu) ul a:hover,
			#header-outer #top nav > ul > li:not(.megamenu) .sfHover > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) .sfHover > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul a:hover,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) .current-menu-item > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-item > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-ancestor > a,
			#header-outer:not([data-format="left-header"]) #top nav > ul > li:not(.megamenu) ul .current-menu-ancestor > a .sf-sub-indicator i,
			#header-outer:not([data-format="left-header"]) #top nav > ul > .megamenu ul ul .current-menu-item > a,
			#header-outer:not([data-format="left-header"]) #header-secondary-outer nav > ul > .megamenu ul ul .current-menu-item > a,
			body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul > a:hover,
	    body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul > a:focus,
			body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li:hover > a,
			body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul:hover > a,
			#header-outer:not([data-format="left-header"]) nav > ul > .megamenu ul ul .current-menu-item.has-ul > a,
		  #header-outer:not([data-format="left-header"]) nav > ul > .megamenu ul ul .current-menu-ancestor.has-ul > a {
				color:'.esc_attr($nectar_options['header-dropdown-font-hover-color']).'!important;
			}
	    #top .sf-menu > li li > a:hover > .nectar-menu-icon,
	    #top .sf-menu > li li.sfHover > a > .nectar-menu-icon,
	    #top .sf-menu > li li.nectar-regular-menu-item[class*="current-"] > a > .nectar-menu-icon {
	      color:'.esc_attr($nectar_options['header-dropdown-font-hover-color']).';
	    }
	    ';
		} // End not using dropdown animated underline.

	}

	if( isset($nectar_options['header-dropdown-desc-font-color']) &&
  !empty($nectar_options['header-dropdown-desc-font-color']) ) {
    echo '
    body #header-outer #top nav .sf-menu ul li > a .item_desc {
			color:'.esc_attr($nectar_options['header-dropdown-desc-font-color']).'!important;
		}';

  }

  if( isset($nectar_options['header-dropdown-desc-font-hover-color']) &&
  !empty($nectar_options['header-dropdown-desc-font-hover-color']) ) {
    echo '
    body #header-outer #top nav .sf-menu ul .sfHover > a .item_desc,
    body #header-outer #top nav .sf-menu ul li:hover > a .item_desc,
    body #header-outer #top nav .sf-menu ul .current-menu-item > a .item_desc,
		body #header-outer #top nav .sf-menu ul .current_page_item > a .item_desc,
		body #header-outer #top nav .sf-menu ul .current_page_ancestor > a .item_desc,
    body #header-outer nav > ul > .megamenu > ul ul li a:focus .item_desc {
			color:'.esc_attr($nectar_options['header-dropdown-desc-font-hover-color']).'!important;
		}';

  }

	if( !empty($nectar_options['header-dropdown-heading-font-color']) ) {
		echo '
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > a,
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul > a,
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > a,
		#header-outer[data-lhe="default"] nav .sf-menu .megamenu ul .current_page_ancestor > a,
		#header-outer[data-lhe="default"] nav .sf-menu .megamenu ul .current-menu-ancestor > a,
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul > a {
			color:'.esc_attr($nectar_options['header-dropdown-heading-font-color']).'!important;
		}';
	}

	if( !empty($nectar_options['header-dropdown-heading-font-hover-color']) ) {
		echo '
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li:hover > a,
		body:not([data-header-format="left-header"]) #header-outer #top nav > ul > .megamenu > ul > li:hover > a,
		body:not([data-header-format="left-header"]) #header-outer #header-secondary-outer nav > ul > .megamenu > ul > li:hover > a,
	  #header-outer:not([data-format="left-header"]) nav > ul > .megamenu > ul > .current-menu-ancestor.menu-item-has-children > a,
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > .current-menu-item > a,
		body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul:hover > a,
    body:not([data-header-format="left-header"]) #header-outer nav > ul > .megamenu > ul > li > ul > .has-ul > a:focus,
	  #header-outer:not([data-format="left-header"]) nav > ul > .megamenu ul ul .current-menu-item.has-ul > a,
		#header-outer:not([data-format="left-header"]) nav > ul > .megamenu ul ul .current-menu-ancestor.has-ul > a  {
			color:'.esc_attr($nectar_options['header-dropdown-heading-font-hover-color']).'!important;
		}';
	}

	if( !empty($nectar_options['header-separator-color']) ) {
		
		$ascend_header_sep_selector = '';
		
		if( 'ascend' === $theme_skin ) {
			$ascend_header_sep_selector = '.ascend #header-outer[data-transparent-header="true"][data-full-width="true"][data-remove-border="true"] #top nav ul #search-btn a:after,
				.ascend #header-outer[data-transparent-header="true"][data-full-width="true"][data-remove-border="true"] #top nav ul #nectar-user-account a:after,
				.ascend #header-outer[data-transparent-header="true"][data-full-width="true"][data-remove-border="true"] #top nav ul .slide-out-widget-area-toggle a:after,
				.ascend #header-outer[data-transparent-header="true"][data-full-width="true"][data-remove-border="true"] .cart-contents:after, ';
		}
		
		echo ' ' . $ascend_header_sep_selector . '
		body #header-outer[data-transparent-header="true"] #top nav ul #nectar-user-account > div,
		body[data-header-color="custom"] #top nav ul #nectar-user-account > div,
		#header-outer:not(.transparent) .sf-menu > li ul {
			border-color:'.esc_attr($nectar_options['header-separator-color']).';
		}
		#header-outer:not(.transparent) .sf-menu > li ul {
				border-top-width: 1px; border-top-style: solid;
		}';
	}


	$header_format = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
	$using_secondary = ( ! empty( $nectar_options['header_layout'] ) && $header_format !== 'left-header' ) ? $nectar_options['header_layout'] : ' ';
	if( 'header_with_secondary' === $using_secondary ) {

		if( !empty($nectar_options['secondary-header-background-color']) ) {
			echo '#header-secondary-outer,
			#header-outer #header-secondary-outer,
			body #header-outer #mobile-menu .secondary-header-text {
				background-color:'.esc_attr($nectar_options['secondary-header-background-color']).';
			}';
		}

		if( !empty($nectar_options['secondary-header-font-color']) ) {
			echo '#header-secondary-outer nav > ul > li > a,
			#header-secondary-outer .nectar-center-text,
			#header-secondary-outer .nectar-center-text a,
			body #header-secondary-outer nav > ul > li > a .sf-sub-indicator i,
			#header-secondary-outer #social li a i,
			#header-secondary-outer[data-lhe="animated_underline"] nav > .sf-menu >li:hover >a,
	    #header-outer #mobile-menu .secondary-header-text p {
				color:'.esc_attr($nectar_options['secondary-header-font-color']).';
			}';
		}

		if( !empty($nectar_options['secondary-header-font-hover-color']) ) {
			echo '
			#header-secondary-outer #social li a:hover i,
			#header-secondary-outer .nectar-center-text a:hover,
			#header-secondary-outer nav > ul > li:hover > a,
			#header-secondary-outer nav > ul > .current-menu-item > a,
			#header-secondary-outer nav > ul > .sfHover > a,
			#header-secondary-outer nav > ul > .sfHover > a .sf-sub-indicator i,
			#header-secondary-outer nav > ul > .current-menu-item > a .sf-sub-indicator i,
			#header-secondary-outer nav > ul > .current-menu-ancestor > a,
			#header-secondary-outer nav > ul > .current-menu-ancestor > a .sf-sub-indicator i,
			#header-secondary-outer nav > ul > li:hover > a .sf-sub-indicator i {
				color:'.esc_attr($nectar_options['secondary-header-font-hover-color']).'!important;
			}
			#header-secondary-outer[data-lhe="animated_underline"] nav > .sf-menu >li >a .menu-title-text:after {
				border-color:'.esc_attr($nectar_options['secondary-header-font-hover-color']).'!important;
			}';
		}

	} // using secondary


	if( !empty($nectar_options['header-dropdown-opacity']) ) {

	 		 $dropdownBGColor = esc_attr($nectar_options['header-dropdown-background-color']);
	 		 $dropdownBGColor = substr($dropdownBGColor,1);
			 $colorR = hexdec( substr( $dropdownBGColor, 0, 2 ) );
			 $colorG = hexdec( substr( $dropdownBGColor, 2, 2 ) );
			 $colorB = hexdec( substr( $dropdownBGColor, 4, 2 ) );
			 $colorA = ($nectar_options['header-dropdown-opacity'] != '100') ? '0.'.esc_attr($nectar_options['header-dropdown-opacity']) : esc_attr($nectar_options['header-dropdown-opacity']);

			 echo '
			 #search-outer .ui-widget-content,
			 body:not([data-header-format="left-header"]) #header-outer .sf-menu li ul,
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


	// Custom off canvas menu colors.
	if( !empty($nectar_options['header-slide-out-widget-area-background-color']) ) {
		echo '
		#slide-out-widget-area:not(.fullscreen-alt):not(.fullscreen),
		#slide-out-widget-area-bg.fullscreen,
		#slide-out-widget-area-bg.fullscreen-split,
		#slide-out-widget-area-bg.fullscreen-inline-images .nectar-ocm-image-wrap-outer,
		#slide-out-widget-area-bg.fullscreen-alt .bg-inner,
		body.material #slide-out-widget-area-bg.slide-out-from-right {
			background-color:'.esc_attr($nectar_options['header-slide-out-widget-area-background-color']).';
		}';

		// Gradients.
		if( !empty($nectar_options['header-slide-out-widget-area-background-color-2']) ) {
			echo '
			body:not(.material) #slide-out-widget-area.slide-out-from-right,
			#slide-out-widget-area.slide-out-from-right-hover,
			#slide-out-widget-area-bg.fullscreen,
      #slide-out-widget-area-bg.fullscreen-split,
			#slide-out-widget-area-bg.fullscreen-alt .bg-inner,
			body.material #slide-out-widget-area-bg.slide-out-from-right {
				background: linear-gradient(145deg, '.esc_attr($nectar_options['header-slide-out-widget-area-background-color']).', '.esc_attr($nectar_options['header-slide-out-widget-area-background-color-2']).');
			}';

      if( $off_canvas_style === 'fullscreen-inline-images' ) {
        echo 'body.material #slide-out-widget-area-bg.fullscreen-inline-images .nectar-ocm-image-wrap-outer {
          background: linear-gradient(145deg, '.esc_attr($nectar_options['header-slide-out-widget-area-background-color']).', '.esc_attr($nectar_options['header-slide-out-widget-area-background-color-2']).');
        }';
      }
		}
	}



	if( !empty($nectar_options['header-slide-out-widget-area-color']) ) {
		echo '
		body #slide-out-widget-area,
		body.material #slide-out-widget-area.slide-out-from-right .off-canvas-social-links a:hover i:before,
		body #slide-out-widget-area a,
		body #slide-out-widget-area.fullscreen-alt .inner .widget.widget_nav_menu li a,
		body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a,
		#slide-out-widget-area.fullscreen-split .inner .widget.widget_nav_menu li a,
		#slide-out-widget-area.fullscreen-split .inner .off-canvas-menu-container li a,
		#slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a,
		body #slide-out-widget-area.fullscreen .menuwrapper li a,
		body #slide-out-widget-area.slide-out-from-right-hover .inner .off-canvas-menu-container li a,
		body #slide-out-widget-area .slide_out_area_close .icon-default-style[class^="icon-"],
		body #slide-out-widget-area .nectar-menu-label {
			color:'.esc_attr($nectar_options['header-slide-out-widget-area-color']).';
		}
		body #slide-out-widget-area .nectar-menu-label:before {
			background-color:'.esc_attr($nectar_options['header-slide-out-widget-area-color']).';
		}';

    if( $off_canvas_style === 'fullscreen-inline-images' ) {
      echo 'body #slide-out-widget-area.fullscreen-inline-images .bottom-text,
      .material #slide-out-widget-area.fullscreen-inline-images .wp-block-search input[type=search] {
        color:'.esc_attr($nectar_options['header-slide-out-widget-area-color']).';
      }';
    }

		echo '
		#slide-out-widget-area .tagcloud a,
		body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container li a:after,
    #slide-out-widget-area.fullscreen-split .inner .off-canvas-menu-container li a:after {
			border-color: '.esc_attr($nectar_options['header-slide-out-widget-area-color']).';
		}';

		if( 'slide-out-from-right-hover' === $off_canvas_style ) {
			echo '
			body .slide-out-hover-icon-effect.slide-out-widget-area-toggle .lines:before,
			body .slide-out-hover-icon-effect.slide-out-widget-area-toggle .lines:after,
			body .slide-out-hover-icon-effect.slide-out-widget-area-toggle .lines-button:after,
			body .slide-out-hover-icon-effect.slide-out-widget-area-toggle .unhidden-line .lines:before,
			body .slide-out-hover-icon-effect.slide-out-widget-area-toggle .unhidden-line .lines:after,
			body .slide-out-hover-icon-effect.slide-out-widget-area-toggle .unhidden-line.lines-button:after {
				background-color:'.esc_attr($nectar_options['header-slide-out-widget-area-color']).';
			}';
		}
	}

	if( !empty($nectar_options['header-slide-out-widget-area-header-color']) ) {
		echo 'body #slide-out-widget-area h1,
		body #slide-out-widget-area h2,
		body #slide-out-widget-area h3,
		body #slide-out-widget-area h4,
		body #slide-out-widget-area h5,
		body #slide-out-widget-area h6 {
			color:'.esc_attr($nectar_options['header-slide-out-widget-area-header-color']).';
		}';
	}


	if( !empty($nectar_options['header-slide-out-widget-area-hover-color']) ) {

    if( $off_canvas_style === 'fullscreen-inline-images' ) {
      echo 'body #slide-out-widget-area.fullscreen-inline-images a:hover {
        color:'.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).'!important;
      }
      #slide-out-widget-area.fullscreen-inline-images .inner .off-canvas-menu-container li a span:after {
			  border-color: '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).';
	  	}';
    }
    
		echo '
		body #slide-out-widget-area[class*="fullscreen"] .current-menu-item > a,
		body #slide-out-widget-area.fullscreen a:hover,
    body #slide-out-widget-area.fullscreen-split a:hover,
    body #slide-out-widget-area.fullscreen-split .off-canvas-menu-container .current-menu-item > a,
		#slide-out-widget-area.slide-out-from-right-hover a:hover,
		body.material #slide-out-widget-area.slide-out-from-right .off-canvas-social-links a i:after,
		body #slide-out-widget-area.slide-out-from-right a:hover,
		body #slide-out-widget-area.fullscreen-alt .inner .off-canvas-menu-container li a:hover,
		#slide-out-widget-area.slide-out-from-right-hover .inner .off-canvas-menu-container li a:hover,
		#slide-out-widget-area.slide-out-from-right-hover .inner .off-canvas-menu-container li.current-menu-item > a,
		#slide-out-widget-area.slide-out-from-right-hover.no-text-effect .inner .off-canvas-menu-container li a:hover,
		body #slide-out-widget-area .slide_out_area_close:hover .icon-default-style[class^="icon-"],
		body.material #slide-out-widget-area.slide-out-from-right .off-canvas-menu-container .current-menu-item > a,
    #slide-out-widget-area .widget .nectar_widget[class*="nectar_blog_posts_"] li:not(.has-img) a:hover .post-title {
			color:'.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).'!important;
		}
		body.material #slide-out-widget-area[class*="slide-out-from-right"] .off-canvas-menu-container li a:after,
    #slide-out-widget-area.fullscreen-split .inner .off-canvas-menu-container li a:after,
		#slide-out-widget-area .tagcloud a:hover {
			border-color: '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).';
		}
    #slide-out-widget-area.fullscreen-split .widget ul:not([class*="nectar_blog_posts"]) li > a:not(.tag-cloud-link):not(.nectar-button),
    #slide-out-widget-area.fullscreen-split .textwidget a:not(.nectar-button) {
      background-image: linear-gradient(to right, '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).' 0%, '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).' 100%);
    }';

		if( isset($nectar_options['header-slide-out-widget-area-image-display']) &&
		    'remove_images' === $nectar_options['header-slide-out-widget-area-image-display'] ) {

				echo '#slide-out-widget-area ul .menu-item .nectar-ext-menu-item .menu-title-text {
					background-image: linear-gradient(to right, '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).' 0%, '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).' 100%);
				}
				#mobile-menu ul .menu-item .nectar-ext-menu-item .menu-title-text {
					background-image: none;
				}';

		} else {
			
			$dropdown_hover_effect = (isset($nectar_options['header-dropdown-hover-effect'])) ? esc_attr($nectar_options['header-dropdown-hover-effect']) : 'default';
			
			if( 'animated_underline' === $dropdown_hover_effect ) {
				echo '#slide-out-widget-area ul .menu-item .nectar-ext-menu-item .menu-title-text {
					background-image: linear-gradient(to right, '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).' 0%, '.esc_attr($nectar_options['header-slide-out-widget-area-hover-color']).' 100%);
				}';
			} 
		
		}

	}

	if( !empty($nectar_options['header-slide-out-widget-area-close-bg-color']) ) {
		echo 'body[data-slide-out-widget-area-style="slide-out-from-right"].material .slide_out_area_close:before {
			background-color: '.esc_attr($nectar_options['header-slide-out-widget-area-close-bg-color']).';
		} ';
	}

	if( !empty($nectar_options['header-slide-out-widget-area-close-icon-color']) ) {
		echo '@media only screen and (min-width: 1000px) {
			body[data-slide-out-widget-area-style="slide-out-from-right"].material .slide_out_area_close .close-line {
				background-color: '.esc_attr($nectar_options['header-slide-out-widget-area-close-icon-color']).';
			}
		} ';
	}


}

// no custom OCM colors are set, ext menu items which have been converted to basic items still need a color for the underline
else if( isset($nectar_options['header-slide-out-widget-area-image-display']) &&
		'remove_images' === $nectar_options['header-slide-out-widget-area-image-display'] ) {

		echo '#slide-out-widget-area ul .menu-item .nectar-ext-menu-item .menu-title-text {
			background-image: linear-gradient(to right, #fff 0%, #fff 100%);
		}';

}


// Custom footer colors.
if( !empty($nectar_options['footer-custom-color']) && $nectar_options['footer-custom-color'] === '1' ) {

	if( !empty($nectar_options['footer-background-color']) ) {
		echo '
		#footer-outer,
		#nectar_fullscreen_rows > #footer-outer.wpb_row .full-page-inner-wrap {
			background-color:'.esc_attr($nectar_options['footer-background-color']).'!important;
		}
		#footer-outer #footer-widgets {
			border-bottom: none;
		}
		body.original #footer-outer #footer-widgets .col ul li {
			border-bottom: 1px solid rgba(0,0,0,0.1);
		}
		.original #footer-outer #footer-widgets .col .widget_recent_comments ul li,
		#footer-outer #footer-widgets .col .widget_recent_comments ul li {
			background-color: rgba(0, 0, 0, 0.07);
			border-bottom: 0px;
		} ';
    if( '#000000' === $nectar_options['footer-background-color'] ) {
      echo '#ajax-content-wrap #footer-outer #footer-widgets .col .widget_recent_comments ul li { background-color: rgba(255,255,255,0.05); }';
    }
	}

	if( !empty($nectar_options['footer-font-color']) ) {
		echo '
		#footer-outer,
		#footer-outer a:not(.nectar-button) {
			color:'.esc_attr($nectar_options['footer-font-color']).'!important;
		}
    #footer-outer[data-link-hover="underline"][data-custom-color="true"] #footer-widgets ul:not([class*="nectar_blog_posts"]) a:not(.tag-cloud-link):not(.nectar-button),
    #footer-outer[data-link-hover="underline"] #footer-widgets .textwidget a:not(.nectar-button) {
      background-image: linear-gradient(to right, '.esc_attr($nectar_options["footer-font-color"]).' 0%, '.esc_attr($nectar_options["footer-font-color"]).' 100%);
    }';
	}

	if( !empty($nectar_options['footer-secondary-font-color']) ) {
		echo '
		#footer-outer #footer-widgets .widget h4,
		#footer-outer .col .widget_recent_entries span,
		#footer-outer .col .recent_posts_extra_widget .post-widget-text span {
			color:'.esc_attr($nectar_options['footer-secondary-font-color']).'!important;
		}';
	}

	if( !empty($nectar_options['footer-copyright-background-color']) ) {
		echo '
		body #footer-outer #copyright,
		.ascend #footer-outer #copyright {
			border: none;
			background-color:'.esc_attr($nectar_options['footer-copyright-background-color']).';
		}';
	}

	if( !empty($nectar_options['footer-copyright-font-color']) ) {
		echo '
		body #footer-outer #copyright .widget h4,
		body #footer-outer #copyright li a i,
		body #footer-outer #copyright p {
			color:'.esc_attr($nectar_options['footer-copyright-font-color']).';
		}
		#footer-outer #copyright a:not(.nectar-button) {
			color:'.esc_attr($nectar_options['footer-copyright-font-color']).'!important;
		}
		body #footer-outer[data-cols="1"] #copyright li a i:after {
			border-color:'.esc_attr($nectar_options['footer-copyright-font-color']).';
		}';
	}

	if( !empty($nectar_options['footer-copyright-icon-hover-color']) ) {
		echo '
		body #footer-outer #copyright li a:hover i,
		body #footer-outer[data-cols="1"] #copyright li a:hover i,
		body #footer-outer[data-cols="1"] #copyright li a:hover i:after {
			border-color: '.esc_attr($nectar_options['footer-copyright-icon-hover-color']).';
			color:'.esc_attr($nectar_options['footer-copyright-icon-hover-color']).';
		}
		#footer-outer #copyright a:hover:not(.nectar-button) {
			color:'.esc_attr($nectar_options['footer-copyright-icon-hover-color']).'!important;
		}';
	}

	// Copyright border line.
	if( !empty($nectar_options['footer-copyright-line']) && $nectar_options['footer-copyright-line'] === '1' ) {

    if( isset($nectar_options['footer-copyright-border-color']) && !empty($nectar_options['footer-copyright-border-color']) ) {
      echo '
  		#ajax-content-wrap #footer-outer #copyright {
  			border-top: 1px solid '.esc_attr($nectar_options['footer-copyright-border-color']).';
  		}';
    } else {
      echo '
  		#ajax-content-wrap #footer-outer #copyright {
  			border-top: 1px solid rgba(255,255,255,0.18);
  		}';
    }

	}
}

// Custom CTA colors.
if( !empty($nectar_options['cta-background-color']) ) {
	echo 'body #call-to-action {
		background-color:'.esc_attr($nectar_options['cta-background-color']).';
	}';
}

if( !empty($nectar_options['cta-text-color']) ) {
	echo 'body #call-to-action span {
		color:'.esc_attr($nectar_options['cta-text-color']).';
	}';
}

// Off canvas menu overlay color.
$slide_out_widget_overlay = (!empty($nectar_options['header-slide-out-widget-area-overlay-opacity'])) ? $nectar_options['header-slide-out-widget-area-overlay-opacity'] : 'dark';

if( $slide_out_widget_overlay === 'dark' ) {
	echo 'body #slide-out-widget-area-bg {
		background-color: rgba(0,0,0,0.8);
	}';
} else if( $slide_out_widget_overlay === 'medium' ) {
	echo 'body #slide-out-widget-area-bg {
		background-color: rgba(0,0,0,0.6);
	}';
} else {
	echo 'body #slide-out-widget-area-bg {
		background-color: rgba(0,0,0,0.4);
	}';
}

// Blog header.
$blog_header_type = (!empty($nectar_options['blog_header_type'])) ? $nectar_options['blog_header_type'] : 'default_minimal';

if( 'default_minimal' === $blog_header_type ) {

  $blog_header_color   = (!empty($nectar_options['default_minimal_overlay_color'])) ? $nectar_options['default_minimal_overlay_color'] : '#2d2d2d';
  $blog_header_overlay = (!empty($nectar_options['default_minimal_overlay_opacity'])) ? $nectar_options['default_minimal_overlay_opacity'] : '0.4';

   echo '.single-post #page-header-bg[data-post-hs="default_minimal"] .page-header-bg-image:after {
     background-color: '.esc_attr($blog_header_color).';
     opacity: '.esc_attr($blog_header_overlay).';
   }
   .single-post #page-header-bg[data-post-hs="default_minimal"] { background-color: '.esc_attr($blog_header_color).'; }';

}


// Blog categories.
$headerFormat = (!empty($nectar_options['header_format'])) ? $nectar_options['header_format'] : 'default';
$masonry_type = (!empty($nectar_options['blog_masonry_type'])) ? $nectar_options['blog_masonry_type'] : 'classic';

if( 'classic_enhanced' === $masonry_type || 'material' === $theme_skin ) {

	$categories = get_categories();

	if(!empty($categories)){

		foreach($categories as $k => $v) {

			$t_id  = esc_attr($v->term_id);
			$terms = get_option( "taxonomy_$t_id" );

			if( !empty($terms['category_color']) ) {

				$button_text_color = (isset($terms['category_text_color']) && !empty($terms['category_text_color']) ) ? esc_attr($terms['category_text_color']) : '#fff';

				echo '
        .featured-media-under-header__cat-wrap .meta-category .'.esc_attr($v->slug) . ':hover,
				.single .heading-title[data-header-style="default_minimal"] .meta-category .'.esc_attr($v->slug) . ':hover,
				.single .heading-title[data-header-style="default_minimal"] .meta-category .'.esc_attr($v->slug) . ':focus,
				body.material #page-header-bg.fullscreen-header .inner-wrap >a.'.esc_attr($v->slug) . ',
				.blog-recent.related-posts[data-style="classic_enhanced"] .meta-category .'.esc_attr($v->slug) . ':hover,
				.masonry.classic_enhanced .posts-container article .meta-category .'.esc_attr($v->slug) . ':hover,
				.blog-recent.related-posts[data-style="classic_enhanced"] .meta-category .'.esc_attr($v->slug) . ':focus,
				.masonry.classic_enhanced .posts-container article .meta-category .'.esc_attr($v->slug) . ':focus,
				#page-header-bg[data-post-hs="default_minimal"] .inner-wrap > .'.esc_attr($v->slug) . ':hover,
				#page-header-bg[data-post-hs="default_minimal"] .inner-wrap > .'.esc_attr($v->slug) . ':focus,
				.nectar-recent-posts-slider .container .strong .'.esc_attr($v->slug).':before,
				.masonry.material .masonry-blog-item .meta-category .'.esc_attr($v->slug) . ':before,
				[data-style="list_featured_first_row"] .meta-category .'.esc_attr($v->slug) . ':before,
				.nectar-recent-posts-single_featured .strong .'.esc_attr($v->slug) . ',
				.related-posts[data-style="material"] .meta-category .'.esc_attr($v->slug) . ':before,
				.post-area.featured_img_left .meta-category .'.esc_attr($v->slug) . ':before,
				.post-area.featured_img_left .quote.category-'.esc_attr($v->slug) . ' .quote-inner:before,
				.material.masonry .masonry-blog-item.category-'.esc_attr($v->slug) . ' .quote-inner:before,
				.material.masonry .masonry-blog-item.category-'.esc_attr($v->slug) . ' .video-play-button,
				.material.masonry .masonry-blog-item.category-'.esc_attr($v->slug) . ' .link-inner:before {
					background-color: '.esc_attr($terms['category_color']).'!important;
					color: '.esc_attr($button_text_color).';
				}

				[data-style="list_featured_first_row"] .meta-category .'.esc_attr($v->slug) . ',
				.masonry.material .masonry-blog-item .meta-category .'.esc_attr($v->slug) . ',
				.post-area.featured_img_left .meta-category .'.esc_attr($v->slug) . ',
				.related-posts[data-style="material"] .meta-category .'.esc_attr($v->slug) . ' {
					color: '.esc_attr($terms['category_color']).'!important;
				}';
			}

		}
	}
}



?>
