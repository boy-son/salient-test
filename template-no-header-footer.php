<?php 
/*template name: No Header & Footer */
?>

<!DOCTYPE html>

<html <?php language_attributes(); ?> class="no-js">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<?php
	$nectar_options = get_nectar_theme_options();
	
	if ( ! empty( $nectar_options['responsive'] ) && $nectar_options['responsive'] === '1' ) { ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
		
	<?php } else { ?>
		<meta name="viewport" content="width=1200" />
	<?php } ?>	
	
	<!--Shortcut icon-->
	<?php if ( ! empty( $nectar_options['favicon'] ) && ! empty( $nectar_options['favicon']['url'] ) ) { ?>
		<link rel="shortcut icon" href="<?php echo nectar_options_img( $nectar_options['favicon'] ); ?>" />
	<?php } 
	
	wp_head();
	
	?>
	
</head>

<?php

global $post;
global $woocommerce;
$nectar_header_options = nectar_get_header_variables();

?>

<body <?php body_class(); ?> <?php nectar_body_attributes(); ?>>
	
	<?php 
	
	nectar_hook_after_body_open();
	
	nectar_hook_before_header_nav();
	
	if(!empty($nectar_options['boxed_layout']) && 
	$nectar_options['boxed_layout'] == '1') {
		 echo '<div id="boxed">'; 
	 }
	
	?>
	
	<div id="header-outer" <?php nectar_header_nav_attributes(); ?>> 
		<header id="top"> <div class="span_3"></div><div class="span_9"></div> </header> 
	</div>
	
	<div id="ajax-content-wrap">
		
		<?php
		
		nectar_hook_after_outer_wrap_open();
		
		nectar_page_header($post->ID);
		
		$nectar_fp_options = nectar_get_full_page_options();
		$nectar_options    = get_nectar_theme_options();
		$header_format     = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';
		$theme_skin        = ( ! empty( $nectar_options['theme-skin'] ) ) ? $nectar_options['theme-skin'] : 'original';
		if ( 'centered-menu-bottom-bar' == $header_format ) {
			$theme_skin = 'material';
		}
		
		?>
		
		<div class="container-wrap">
			<div class="<?php if ( $nectar_fp_options['page_full_screen_rows'] !== 'on' ) { echo 'container';} ?> main-content">
				<div class="row">
					
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
					
				</div><!--/row-->
			</div><!--/container-->
		</div><!--/container-wrap-->
		
		
		<?php
		
		nectar_hook_before_outer_wrap_close();
		
		get_template_part( 'includes/partials/footer/off-canvas-navigation' );
		
		?>
		
	</div> <!--/ajax-content-wrap-->
	
	
	<?php
	if ( ! empty( $nectar_options['boxed_layout'] ) && 
		$nectar_options['boxed_layout'] === '1' && 
		$header_format !== 'left-header' ) {
		echo '</div><!--/boxed closing div-->'; }
		
		
		get_template_part( 'includes/partials/footer/back-to-top' );
		
		get_template_part( 'includes/partials/footer/body-border' );
		

		nectar_hook_after_wp_footer();
		nectar_hook_before_body_close();
		
		wp_footer();
	?>
</body>
</html>