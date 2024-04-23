<?php
/**
 * Post single no header BG image supplied - fullscreen template
 *
 * @package Salient WordPress Theme
 * @subpackage Partials
 * @version 15.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$nectar_options = get_nectar_theme_options();

$bg                                = apply_filters('nectar_page_header_bg_val', get_post_meta( $post->ID, '_nectar_header_bg', true ));
$bg_color                          = apply_filters('nectar_page_header_bg_color_val', get_post_meta( $post->ID, '_nectar_header_bg_color', true ));
$blog_post_type_list = array('post');
if( has_filter('nectar_metabox_post_types_post_header') ) {
	$blog_post_type_list = apply_filters('nectar_metabox_post_types_post_header', $blog_post_type_list);
}
$is_blog_header_post_type      = ( isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single()) ? true : false;
$single_post_header_inherit_fi = ( ! empty( $nectar_options['blog_post_header_inherit_featured_image'] ) && $is_blog_header_post_type ) ? $nectar_options['blog_post_header_inherit_featured_image'] : '0';

$theme_skin = NectarThemeManager::$skin;

$fullscreen_header                 = ( ! empty( $nectar_options['blog_header_type'] ) && $nectar_options['blog_header_type'] === 'fullscreen' && is_singular( 'post' ) ) ? true : false;
$fullscreen_class                  = ( $fullscreen_header === true ) ? 'fullscreen-header full-width-content' : null;
$remove_single_post_date           = ( ! empty( $nectar_options['blog_remove_single_date'] ) ) ? $nectar_options['blog_remove_single_date'] : '0';
$remove_single_post_author         = ( ! empty( $nectar_options['blog_remove_single_author'] ) ) ? $nectar_options['blog_remove_single_author'] : '0';
$remove_single_post_comment_number = ( ! empty( $nectar_options['blog_remove_single_comment_number'] ) ) ? $nectar_options['blog_remove_single_comment_number'] : '0';
$remove_single_post_nectar_love    = ( ! empty( $nectar_options['blog_remove_single_nectar_love'] ) ) ? $nectar_options['blog_remove_single_nectar_love'] : '0';

// Determine whether theme option to inherit featured image is in effect.
$inherit_and_has_featured_img = false;
if( $single_post_header_inherit_fi === '1' && isset( $post->ID ) && has_post_thumbnail( $post->ID ) ) {
	$inherit_and_has_featured_img = true;
}

$hentry_class = apply_filters('nectar_post_header_hentry_class', ' hentry');

if ( empty( $bg ) && empty( $bg_color ) && $inherit_and_has_featured_img !== true ) { ?>
  <div id="page-header-wrap" data-animate-in-effect="none" data-midnight="light" class="fullscreen-header">
  <div class="default-blog-title fullscreen-header<?php echo esc_attr($hentry_class); ?>" id="page-header-bg" data-midnight="light" data-alignment-v="middle" data-alignment="center" data-parallax="0" data-height="450" data-remove-post-date="<?php echo esc_attr( $remove_single_post_date ); ?>" data-remove-post-author="<?php echo esc_attr( $remove_single_post_author ); ?>" data-remove-post-comment-number="<?php echo esc_attr( $remove_single_post_comment_number ); ?>">
		<div class="container">
			<div class="row">
				<div class="col span_6 section-title blog-title">
					<div class="inner-wrap">
						<?php
						if ( ( $post->post_type === 'post' && is_single() ) && $theme_skin === 'material' ) {
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								$output = null;
								foreach ( $categories as $category ) {
									$output .= '<a class="' . esc_attr( $category->slug ) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'salient' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>';
								}
								echo apply_filters('nectar_blog_page_header_categories',trim( $output ));
							}
						}
						?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="author-section">
							<span class="meta-author">
								<?php
								if ( function_exists( 'get_avatar' ) ) {
									echo get_avatar( get_the_author_meta( 'email' ), 100 ); }
									?>
								</span>
								<div class="avatar-post-info vcard author">
									<span class="fn"><?php the_author_posts_link(); ?></span>
									<?php
									$date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';
									
									if( '1' !== $remove_single_post_date ) {
										if( 'last_editied_date' === $date_functionality ) {
											echo '<span class="meta-date date updated"><i>'.get_the_modified_time(__( 'F jS, Y' , 'salient' )).'</i></span>';
										} else {
											$nectar_u_time          = get_the_time( 'U' );
											$nectar_u_modified_time = get_the_modified_time( 'U' );
											if( $nectar_u_modified_time >= $nectar_u_time + 86400 ) {
												?>
												<span class="meta-date date published"><i><?php echo get_the_date(); ?></i></span>
												<span class="meta-date date updated rich-snippet-hidden"><?php echo get_the_modified_time( __( 'F jS, Y' , 'salient' ) ); ?></span>
											<?php } else { ?>
												<span class="meta-date date updated"><i><?php echo get_the_date(); ?></i></span>
											<?php }
										} 
									} ?>
								</div>
							</div><!--/author-section-->
						</div><!--/inner-wrap-->
					</div><!--/blog-title-->
				</div><!--/row-->
			</div><!--/container-->
	<?php
	$button_styling = ( ! empty( $nectar_options['button-styling'] ) ) ? $nectar_options['button-styling'] : 'default';
	$header_down_arrow_style = (!empty($nectar_options['header-down-arrow-style'])) ? $nectar_options['header-down-arrow-style'] : 'default';

	if( $header_down_arrow_style === 'animated-arrow' ) {
		echo '<div class="scroll-down-wrap minimal-arrow nectar-next-section-wrap"><a href="#" class="minimal-arrow">
			<svg class="next-arrow" width="40px" height="68px" viewBox="0 0 40 50" xml:space="preserve">
			<path stroke="#ffffff" stroke-width="2" fill="none" d="M 20 0 L 20 51"></path>
			<polyline stroke="#ffffff" stroke-width="2" fill="none" points="12, 44 20, 52 28, 44"></polyline>
			</svg>
		</a></div>';
	}
	elseif ( $button_styling === 'default' ) {
		echo '<div class="scroll-down-wrap"><a href="#" class="section-down-arrow"><i class="icon-salient-down-arrow icon-default-style"> </i></a></div>';
	} elseif ( $button_styling === 'slightly_rounded' || $button_styling === 'slightly_rounded_shadow' ) {
		echo '<div class="scroll-down-wrap no-border"><a href="#" class="section-down-arrow"><svg class="nectar-scroll-icon" viewBox="0 0 30 45" enable-background="new 0 0 30 45">
                    <path class="nectar-scroll-icon-path" fill="none" stroke="#ffffff" stroke-width="2" stroke-miterlimit="10" d="M15,1.118c12.352,0,13.967,12.88,13.967,12.88v18.76  c0,0-1.514,11.204-13.967,11.204S0.931,32.966,0.931,32.966V14.05C0.931,14.05,2.648,1.118,15,1.118z"></path>
                  </svg></a></div>';
	} else {
		echo '<div class="scroll-down-wrap"><a href="#" class="section-down-arrow"><i class="fa fa-angle-down top"></i><i class="fa fa-angle-down"></i></a></div>';
	}
	?>
  </div>
  </div>
	<?php
}
