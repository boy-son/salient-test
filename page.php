<?php
/**
* The template for displaying pages.
*
* @package Salient WordPress Theme
* @version 15.5
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
nectar_page_header( $post->ID );
$nectar_fp_options = nectar_get_full_page_options();

?>
<div class="container-wrap">
	<div class="<?php if ( $nectar_fp_options['page_full_screen_rows'] !== 'on' ) { echo 'container'; } ?> main-content" role="main">
		<div class="<?php echo apply_filters('nectar_main_container_row_class_name', 'row'); ?>">
			<?php

			nectar_hook_before_content();

			if ( have_posts() ) :
				while ( have_posts() ) :

					the_post();
					the_content();

				endwhile;
			endif;

			nectar_hook_after_content();

			?>
		</div>
	</div>
	<?php nectar_hook_before_container_wrap_close(); ?>
</div>
<?php get_footer(); ?>
