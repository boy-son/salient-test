<?php 
/*template name: Contact*/
get_header(); 

nectar_page_header($post->ID);  

$nectar_options = get_nectar_theme_options(); 

wp_enqueue_script('nectarMap', get_template_directory_uri() . '/js/build/map.js', array('jquery'), '1.0', TRUE);

?>

<div class="container-wrap">

	<div id="contact-map" class="nectar-google-map" data-dark-color-scheme="<?php if(!empty($nectar_options['map-dark-color-scheme'])) echo esc_attr( $nectar_options['map-dark-color-scheme'] ); ?>" data-ultra-flat="<?php if(!empty($nectar_options['map-ultra-flat'])) echo esc_attr( $nectar_options['map-ultra-flat'] ); ?>" data-greyscale="<?php if(!empty($nectar_options['map-greyscale'])) echo esc_attr( $nectar_options['map-greyscale'] ); ?>" data-extra-color="<?php if(!empty($nectar_options['map-color'])) echo esc_attr( $nectar_options['map-color'] ); ?>" data-enable-animation="<?php if(!empty($nectar_options['enable-map-animation'])) echo esc_attr( $nectar_options['enable-map-animation'] ); ?>" data-enable-zoom="<?php if(!empty($nectar_options['enable-map-zoom'])) echo esc_attr( $nectar_options['enable-map-zoom'] ); ?>" data-zoom-level="<?php if(!empty($nectar_options['zoom-level'])) echo esc_attr( $nectar_options['zoom-level'] ); ?>" data-center-lat="<?php if(!empty($nectar_options['center-lat'])) echo esc_attr( $nectar_options['center-lat'] ); ?>" data-center-lng="<?php if(!empty($nectar_options['center-lng'])) echo esc_attr( $nectar_options['center-lng'] ); ?>" data-marker-img="<?php if(!empty($nectar_options['marker-img'])) echo nectar_options_img( $nectar_options['marker-img'] ); ?>"></div>
	
	<div class="map-marker-list contact-map">
		<?php
			$count = 0;

			for($i = 1; $i < 9; $i++){
				if(!empty($nectar_options['map-point-'.$i]) && $nectar_options['map-point-'.$i] != 0 ) {
					$count++;

					echo '<div class="map-marker" data-lat="'. esc_attr( $nectar_options['latitude'.$i] ) .'" data-lng="'. esc_attr( $nectar_options['longitude'.$i] ) .'" data-mapinfo="'. esc_attr( $nectar_options['map-info'.$i] ) .'"></div>';
				}	
			}
		?>
		
	</div>
	
	<div class="container main-content">
		
		<div class="row">
	
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				
				<?php the_content(); ?>
	
			<?php endwhile; endif; ?>
				
	
		</div><!--/row-->
		
	</div><!--/container-->

</div>
<?php get_footer(); ?>