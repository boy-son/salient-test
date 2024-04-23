<?php
/*template name: Home - Slider Only */

get_header();

$nectar_options = get_nectar_theme_options();

if ( class_exists( 'Salient_Home_Slider' ) ) { ?>

	<div id="featured" data-caption-animation="<?php echo (!empty($nectar_options['slider-caption-animation']) && $nectar_options['slider-caption-animation'] === '1') ? '1' : '0'; ?>" data-bg-color="<?php if(!empty($nectar_options['slider-bg-color'])) echo esc_attr( $nectar_options['slider-bg-color'] ); ?>" data-slider-height="<?php if(!empty($nectar_options['slider-height'])) echo esc_attr( $nectar_options['slider-height'] ); ?>" data-animation-speed="800" data-advance-speed="<?php if(!empty($nectar_options['slider-advance-speed'])) echo esc_attr( $nectar_options['slider-advance-speed'] ); ?>" data-autoplay="<?php echo esc_attr( $nectar_options['slider-autoplay'] );?>"> 
	
	 
	<?php
	$slides = new WP_Query(
		array(
			'post_type'      => 'home_slider',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		)
	);
	if ( $slides->have_posts() ) :

		while ( $slides->have_posts() ) :
			
			$slides->the_post();

			$alignment    = get_post_meta( $post->ID, '_nectar_slide_alignment', true );
			$video_embed  = get_post_meta( $post->ID, '_nectar_video_embed', true );
			$video_m4v    = get_post_meta( $post->ID, '_nectar_video_m4v', true );
			$video_ogv    = get_post_meta( $post->ID, '_nectar_video_ogv', true );
			$video_poster = get_post_meta( $post->ID, '_nectar_video_poster', true );

			?>
			
			<div class="slide orbit-slide <?php if ( ! empty( $video_embed ) || ! empty( $video_m4v ) || ! empty( $video_ogv ) ) { echo 'has-video'; } else { echo esc_attr( $alignment ); } ?> ">
				
				<?php 
				$image = get_post_meta( $post->ID, '_nectar_slider_image', true );
				if( $image ) {
					$image = nectar_options_img($image);
				}
				?>
				<article data-background-cover="<?php echo ( ! empty( $nectar_options['slider-background-cover'] ) && $nectar_options['slider-background-cover'] === '1' ) ? '1' : '0'; ?>" style="background-image: url('<?php echo esc_url( $image ); ?>')">
					<div class="container">
						<div class="col span_12">
							<div class="post-title">
								
								<?php
									 $wp_version = floatval( get_bloginfo( 'version' ) );

									// video embed
								if ( ! empty( $video_embed ) ) {

									 echo '<div class="video">' . do_shortcode( $video_embed ) . '</div>';

								}
								// self hosted video post 3-6
								elseif ( $wp_version >= '3.6' ) {

									if ( ! empty( $video_m4v ) || ! empty( $video_ogv ) ) {

										$video_output = '[video ';

										if ( ! empty( $video_m4v ) ) {
											$video_output .= 'mp4="' . esc_url( $video_m4v ) . '" '; }
										if ( ! empty( $video_ogv ) ) {
											$video_output .= 'ogv="' . esc_url( $video_ogv ) . '"'; }

										$video_output .= ' poster="' . esc_url( $video_poster ) . '"]';

										echo '<div class="video">' . do_shortcode( $video_output ) . '</div>';
									}
								}

									// mobile more info button for video
									if ( ! empty( $video_embed ) || ! empty( $video_m4v ) ) {
										echo '<div><a href="#" class="more-info"><span class="mi">' . esc_html__( 'More Info', 'salient' ) . '</span><span class="btv">' . esc_html__( 'Back to Video', 'salient' ) . '</span></a></div>'; }
									?>
								 
								 <?php $caption = get_post_meta( $post->ID, '_nectar_slider_caption', true ); ?>
								<h2 data-has-caption="<?php echo ( ! empty( $caption ) ) ? '1' : '0'; ?>"><span>
									<?php echo wp_kses_post( $caption ); ?>
								</span></h2>
								
								<?php
									$button     = get_post_meta( $post->ID, '_nectar_slider_button', true );
									$button_url = get_post_meta( $post->ID, '_nectar_slider_button_url', true );

								if ( ! empty( $button ) ) {
									?>
										<a href="<?php echo esc_url( $button_url ); ?>" class="uppercase"><?php echo wp_kses_post( $button ); ?></a>
									<?php } ?>
								 

							</div><!--/post-title-->
						</div>
					</div>
				</article>
			</div>
		<?php endwhile; ?>
		<?php else : ?>


	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>

<?php } ?>

<div class="home-wrap">

	<div class="container main-content">
		
		<div class="row">
	
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					
					the_post();
					the_content(); 
	
				endwhile;
			endif;
			?>
				
		</div><!--/row-->

	</div><!--/container-->
	<?php nectar_hook_before_container_wrap_close(); ?>
</div><!--/home-wrap-->
	
<?php get_footer(); ?>