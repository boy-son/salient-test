<?php
/**
 * Post single no header BG image supplied - regular template
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
$is_blog_header_post_type          = ( isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single()) ? true : false;
$single_post_header_inherit_fi     = ( ! empty( $nectar_options['blog_post_header_inherit_featured_image'] ) && $is_blog_header_post_type ) ? $nectar_options['blog_post_header_inherit_featured_image'] : '0';
$theme_skin                        = NectarThemeManager::$skin;
$fullscreen_header                 = ( ! empty( $nectar_options['blog_header_type'] ) && $nectar_options['blog_header_type'] === 'fullscreen' && is_singular( 'post' ) ) ? true : false;
$blog_header_type                  = ( ! empty( $nectar_options['blog_header_type'] ) ) ? $nectar_options['blog_header_type'] : 'default';
$fullscreen_class                  = ( $fullscreen_header === true ) ? 'fullscreen-header full-width-content' : null;
$blog_social_style                 = ( get_option( 'salient_social_button_style' ) ) ? get_option( 'salient_social_button_style' ) : 'fixed';
$remove_single_post_date           = ( ! empty( $nectar_options['blog_remove_single_date'] ) ) ? $nectar_options['blog_remove_single_date'] : '0';
$remove_single_post_author         = ( ! empty( $nectar_options['blog_remove_single_author'] ) ) ? $nectar_options['blog_remove_single_author'] : '0';
$remove_single_post_comment_number = ( ! empty( $nectar_options['blog_remove_single_comment_number'] ) ) ? $nectar_options['blog_remove_single_comment_number'] : '0';
$remove_single_post_nectar_love    = ( ! empty( $nectar_options['blog_remove_single_nectar_love'] ) ) ? $nectar_options['blog_remove_single_nectar_love'] : '0';


if ( get_post_format() !== 'status' && get_post_format() !== 'aside' && 'image_under' !== $blog_header_type ) {

	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			// Determine whether theme option to inherit featured image is in effect.
			$inherit_and_has_featured_img = false;
			if( $single_post_header_inherit_fi === '1' && has_post_thumbnail() ) {
				$inherit_and_has_featured_img = true;
			}
			
			$hentry_class = apply_filters('nectar_post_header_hentry_class', ' hentry');

			if ( ( empty( $bg ) && empty( $bg_color ) ) && $fullscreen_header != true && $inherit_and_has_featured_img !== true ) { ?>

	  <div class="row heading-title<?php echo esc_attr($hentry_class); ?>" data-header-style="<?php echo esc_attr( $blog_header_type ); ?>">
		<div class="col span_12 section-title blog-title">
				<?php do_action('nectar_before_post_title'); ?>
				<?php if ( $blog_header_type === 'default_minimal' && 'post' === get_post_type() ) { ?>
		  <span class="meta-category">

					<?php
					$categories = get_the_category();
					if ( ! empty( $categories ) ) {
						$output = nectar_get_category_list();
						echo apply_filters('nectar_blog_page_header_categories',trim( $output )); // WPCS: XSS ok.
					}
					?>
			  </span>

		  <?php } ?>
		  <h1 class="entry-title"><?php the_title(); ?></h1>

			<?php if ( 'post' === get_post_type() ) {

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
						echo '<span class="meta-date date updated"><i>'.get_the_modified_time(__( 'F jS, Y' , 'salient' )).'</i></span>';
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

				if( $blog_header_type != 'default_minimal') {
					echo '<span class="meta-category">'.apply_filters('nectar_blog_page_header_categories',nectar_get_category_list(',')).'</span>';
				} else {
					echo '<span class="meta-comment-count"><a href="'.get_comments_link().'">'. get_comments_number_text( esc_html__('No Comments', 'salient'), esc_html__('One Comment', 'salient'), esc_html__('% Comments', 'salient') ) . '</a></span>';
				}
        $remove_reading_dur = ( isset( $nectar_options['blog_remove_single_reading_dur'] ) ) ? $nectar_options['blog_remove_single_reading_dur'] : '0';
        if( '1' !== $remove_reading_dur ) {
          echo '<span class="meta-reading-time">'.nectar_estimated_reading_time(get_the_content()). ' '. esc_html__('min read','salient').'</span>';
        }
				?>
			</div><!--/single-below-header-->
		<?php }

			if ( $blog_header_type !== 'default_minimal' && 'post' === get_post_type() ) { ?>
			<div id="single-meta">

				<div class="meta-comment-count">
				  <a href="<?php comments_link(); ?>"><i class="icon-default-style steadysets-icon-chat-3"></i> <?php comments_number( esc_html__( 'No Comments', 'salient' ), esc_html__( 'One Comment ', 'salient' ), esc_html__( '% Comments', 'salient' ) ); ?></a>
				</div>

					<?php

					if ( $blog_social_style != 'fixed' ) {

						if( function_exists('nectar_social_sharing_output') ) {
							nectar_social_sharing_output('hover','right');
						}

					}
					?>

			</div><!--/single-meta-->

			<?php } ?>
		</div><!--/section-title-->
	  </div><!--/row-->

	<?php }

endwhile;
endif;

} ?>