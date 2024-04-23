<?php
/*template name: Sidebar*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

nectar_page_header( $post->ID );

?>

<div class="container-wrap">
	
	<div class="container main-content">
		
		<div class="row">
			
			<div class="post-area col span_9">
				
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
				
			</div><!--/span_9-->
			
			<div id="sidebar" class="col span_3 col_last">
				<?php 
				nectar_hook_sidebar_top();
				get_sidebar(); 
				nectar_hook_sidebar_bottom();
				?>
			</div><!--/span_9-->
			
		</div><!--/row-->
		
	</div><!--/container-->
	<?php nectar_hook_before_container_wrap_close(); ?>
</div><!--/container-wrap-->

<?php get_footer(); ?>