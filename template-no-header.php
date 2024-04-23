<?php 
/*template name: No Header */
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
	
	if(!empty($nectar_options['boxed_layout']) && $nectar_options['boxed_layout'] === '1') { echo '<div id="boxed">'; }
	
	?>
	
	<div id="header-outer" <?php nectar_header_nav_attributes(); ?>> 
		<header id="top"> <div class="span_3"></div><div class="span_9"></div> </header> 
	</div>
	
	<div id="ajax-content-wrap">
		
		<?php
		
		nectar_hook_after_outer_wrap_open();
		
		nectar_page_header($post->ID);
		
		$nectar_fp_options = nectar_get_full_page_options();
		
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
			<?php nectar_hook_before_container_wrap_close(); ?>
		</div><!--/container-wrap-->
		
		<?php get_footer(); ?>