<?php

/**
 * The template for displaying the Blog index.
 *
 * @package Salient WordPress Theme
 * @version 15.1
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

get_header();

nectar_page_header(get_option('page_for_posts')); ?>

<div class="container-wrap">

	<div class="container main-content">

		<?php do_action('nectar_before_blog_loop_row'); 
		
		$row_class = apply_filters('nectar_blog_row_class', 'row');

		echo '<div class="'.$row_class.'">';
		
			$nectar_options = get_nectar_theme_options();
			
			$blog_type = apply_filters('nectar_blog_type', $nectar_options['blog_type']);
			if ($blog_type === null) {
				$blog_type = 'std-blog-sidebar';
			}

			$masonry_class         = null;
			$masonry_style         = null;
			$masonry_style_parsed  = null;
			$standard_style_parsed = null;
			$infinite_scroll_class = null;
			$load_in_animation     = (!empty($nectar_options['blog_loading_animation'])) ? $nectar_options['blog_loading_animation'] : 'none';
			$blog_standard_type    = (!empty($nectar_options['blog_standard_type'])) ? $nectar_options['blog_standard_type'] : 'classic';
			$enable_ss             = (!empty($nectar_options['blog_enable_ss'])) ? $nectar_options['blog_enable_ss'] : 'false';
			$auto_masonry_spacing  = (!empty($nectar_options['blog_auto_masonry_spacing'])) ? $nectar_options['blog_auto_masonry_spacing'] : '4px';

			$remove_post_date           = (!empty($nectar_options['blog_remove_post_date'])) ? $nectar_options['blog_remove_post_date'] : '0';
			$remove_post_author         = (!empty($nectar_options['blog_remove_post_author'])) ? $nectar_options['blog_remove_post_author'] : '0';
			$remove_post_comment_number = (!empty($nectar_options['blog_remove_post_comment_number'])) ? $nectar_options['blog_remove_post_comment_number'] : '0';
			$remove_post_nectar_love    = (!empty($nectar_options['blog_remove_post_nectar_love'])) ? $nectar_options['blog_remove_post_nectar_love'] : '0';

			// Enqueue masonry script if selected.
			if (
				$blog_type === 'masonry-blog-sidebar' ||
				$blog_type === 'masonry-blog-fullwidth' ||
				$blog_type === 'masonry-blog-full-screen-width'
			) {
				$masonry_class = 'masonry';
			}

			$blog_masonry_style = (!empty($nectar_options['blog_masonry_type'])) ? $nectar_options['blog_masonry_type'] : 'classic';


			if (
				!empty($nectar_options['blog_pagination_type']) &&
				$nectar_options['blog_pagination_type'] === 'infinite_scroll' && 
				!has_action('nectar_blog_loop_post_item')
			) {
				$infinite_scroll_class = ' infinite_scroll';
			}

			// Store masonry style.
			if ($masonry_class !== null) {
				$masonry_style        = (!empty($nectar_options['blog_masonry_type'])) ? $nectar_options['blog_masonry_type'] : 'classic';
				$masonry_style_parsed = str_replace('_', '-', $masonry_style);
			} else {
				$standard_style_parsed = str_replace('_', '-', $blog_standard_type);
			}


			$std_minimal_class = '';
			if ($blog_standard_type == 'minimal' && $blog_type === 'std-blog-fullwidth') {
				$std_minimal_class = 'standard-minimal full-width-content';
			} elseif ($blog_standard_type == 'minimal' && $blog_type === 'std-blog-sidebar') {
				$std_minimal_class = 'standard-minimal';
			}

			if ($masonry_style === null && $blog_standard_type === 'featured_img_left') {
				$std_minimal_class = 'featured_img_left';
			}

			if( has_action('nectar_blog_loop_post_item') ) {
				$masonry_class = $masonry_style = $std_minimal_class = $auto_masonry_spacing = $load_in_animation = '';
			}

			if ($blog_type === 'std-blog-sidebar' || $blog_type === 'masonry-blog-sidebar') {
				echo '<div class="post-area col ' . $std_minimal_class . ' span_9 ' . esc_attr($masonry_class) . ' ' . esc_attr($masonry_style) . ' ' . $infinite_scroll_class . '" role="main" data-ams="' . esc_attr($auto_masonry_spacing) . '" data-remove-post-date="' . esc_attr($remove_post_date) . '" data-remove-post-author="' . esc_attr($remove_post_author) . '" data-remove-post-comment-number="' . esc_attr($remove_post_comment_number) . '" data-remove-post-nectar-love="' . esc_attr($remove_post_nectar_love) . '"> <div class="posts-container"  data-load-animation="' . esc_attr($load_in_animation) . '">'; // WPCS: XSS ok.
			} else {

				if (
					$blog_type === 'masonry-blog-full-screen-width' && $blog_masonry_style === 'auto_meta_overlaid_spaced' ||
					$blog_type === 'masonry-blog-full-screen-width' && $blog_masonry_style === 'meta_overlaid'
				) {
					echo '<div class="full-width-content blog-fullwidth-wrap meta-overlaid">';
				} elseif ($blog_type === 'masonry-blog-full-screen-width') {
					echo '<div class="full-width-content blog-fullwidth-wrap">';
				}

				echo '<div class="post-area col ' . $std_minimal_class . ' span_12 col_last ' . esc_attr($masonry_class) . ' ' . esc_attr($masonry_style) . ' ' . $infinite_scroll_class . '" role="main" data-ams="' . esc_attr($auto_masonry_spacing) . '" data-remove-post-date="' . esc_attr($remove_post_date) . '" data-remove-post-author="' . esc_attr($remove_post_author) . '" data-remove-post-comment-number="' . esc_attr($remove_post_comment_number) . '" data-remove-post-nectar-love="' . esc_attr($remove_post_nectar_love) . '"> <div class="posts-container" data-load-animation="' . esc_attr($load_in_animation) . '">'; // WPCS: XSS ok.
			}
			

			add_filter('wp_get_attachment_image_attributes', 'nectar_remove_lazy_load_functionality');

			do_action('nectar_before_blog_loop_start');

			do_action('nectar_before_blog_loop_content');

			// Main post loop.
			if (have_posts()) :
				while (have_posts()) :
					the_post();

					$nectar_post_format = get_post_format();

					if (
						get_post_format() === 'image' ||
						get_post_format() === 'aside' ||
						get_post_format() === 'status'
					) {
						$nectar_post_format = false;
					}

					if( !has_action('nectar_blog_loop_post_item') ) {
						// Masonry layouts.
						if (null !== $masonry_class) {
							get_template_part('includes/partials/blog/styles/masonry-' . $masonry_style_parsed . '/entry', $nectar_post_format);
						}
						// Standard layouts.
						else {
							get_template_part('includes/partials/blog/styles/standard-' . $standard_style_parsed . '/entry', $nectar_post_format);
						}
					} else {
						do_action('nectar_blog_loop_post_item');
					}


				endwhile;
			endif;

			do_action('nectar_after_blog_loop_content');

			do_action('nectar_before_blog_loop_end');

		echo '</div>'; // closing posts-container.
			
		nectar_pagination(); ?>

		</div>

		<?php
		if ($blog_type === 'masonry-blog-full-screen-width') {
			echo '</div>';
		}
		
		if ( strpos($blog_type, 'sidebar') !== false ) { ?>
			<div id="sidebar" data-nectar-ss="<?php echo esc_attr($enable_ss); ?>" class="col span_3 col_last">
				<?php 
					nectar_hook_sidebar_top();
					get_sidebar(); 
					nectar_hook_sidebar_bottom();
				?>
			</div>
		<?php } ?>

		</div>
	</div>
<?php nectar_hook_before_container_wrap_close(); ?>
</div>
<?php get_footer(); ?>