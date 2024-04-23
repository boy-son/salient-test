<?php
/*template name: Home - Recent Posts */
get_header();

$nectar_options = get_nectar_theme_options();

wp_enqueue_script('flexslider');

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
		?>
	
		<?php
		while ( $slides->have_posts() ) :
			$slides->the_post();

			$alignment = get_post_meta( $post->ID, '_nectar_slide_alignment', true );

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

								?>
								
								 <?php
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
		
			<?php
				$posts_page_id    = get_option( 'page_for_posts' );
				$posts_page       = get_page( $posts_page_id );
				$posts_page_title = $posts_page->post_title;
				$posts_page_link  = get_page_uri( $posts_page_id );

				$recent_posts_title_text = ( ! empty( $nectar_options['recent-posts-title'] ) ) ? $nectar_options['recent-posts-title'] : 'Recent Posts';
				$recent_posts_link_text  = ( ! empty( $nectar_options['recent-posts-link'] ) ) ? $nectar_options['recent-posts-link'] : 'View All Posts';
			?>
			
			<h2 class="uppercase recent-posts-title"><?php echo wp_kses_post( $recent_posts_title_text ); ?><a href="<?php echo esc_url( $posts_page_link ); ?>" class="button"> / <?php echo wp_kses_post( $recent_posts_link_text ); ?> </a></h2>
			
			<div class="row blog-recent">
				
				<?php
				$recentBlogPosts = array(
					'showposts'           => 4,
					'ignore_sticky_posts' => 1,
					'tax_query'           => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-link', 'post-format-quote' ),
							'operator' => 'NOT IN',
						),
					),
				);
				query_posts( $recentBlogPosts );
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>
				
				<div class="col span_3">
					
						<?php

						$wp_version = floatval( get_bloginfo( 'version' ) );

						if ( get_post_format() === 'video' ) {

							if ( $wp_version < '3.6' ) {
								$video_embed = get_post_meta( $post->ID, '_nectar_video_embed', true );

								if ( ! empty( $video_embed ) ) {
									echo '<div class="video-wrap">' . stripslashes( wp_specialchars_decode( $video_embed ) ) . '</div>';
								} 
							} else {

								$video_embed  = get_post_meta( $post->ID, '_nectar_video_embed', true );
								$video_m4v    = get_post_meta( $post->ID, '_nectar_video_m4v', true );
								$video_ogv    = get_post_meta( $post->ID, '_nectar_video_ogv', true );
								$video_poster = get_post_meta( $post->ID, '_nectar_video_poster', true );

								if ( ! empty( $video_embed ) || ! empty( $video_m4v ) ) {

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
								} // endif for if there's a video
							} // endif for 3.6
						} //endif for post format video

						elseif ( get_post_format() === 'audio' ) {
							?>
							<div class="audio-wrap">		
								<?php
								if ( $wp_version < '3.6' ) {
			
								} else {
									$audio_mp3 = get_post_meta( $post->ID, '_nectar_audio_mp3', true );
									$audio_ogg = get_post_meta( $post->ID, '_nectar_audio_ogg', true );

									if ( ! empty( $audio_ogg ) || ! empty( $audio_mp3 ) ) {

										$audio_output = '[audio ';

										if ( ! empty( $audio_mp3 ) ) {
											$audio_output .= 'mp3="' . esc_url( $audio_mp3 ) . '" '; }
										if ( ! empty( $audio_ogg ) ) {
											$audio_output .= 'ogg="' . esc_url( $audio_ogg ) . '"'; }

										$audio_output .= ']';

										echo do_shortcode( $audio_output );
									}
								}
								?>
							</div><!--/audio-wrap-->
							<?php
						} elseif ( get_post_format() === 'gallery' ) {

							if ( $wp_version < '3.6' ) {

								if ( has_post_thumbnail() ) {
									echo get_the_post_thumbnail( $post->ID, 'full', array( 'title' => '' ) ); }
							} else {

								$gallery_ids = nectar_grab_ids_from_gallery();
								?>
					
								<div class="flex-gallery"> 
										 <ul class="slides">
											<?php
											foreach ( $gallery_ids as $image_id ) {
												 echo '<li>' . wp_get_attachment_image( $image_id, '', false ) . '</li>';
											}
											?>
										</ul>
									</div><!--/gallery-->

								<?php
							}
						} else {
							if ( has_post_thumbnail() ) {
								echo '<a href="' . esc_url( get_permalink() ) . '">' . get_the_post_thumbnail( $post->ID, 'blog', array( 'title' => '' ) ) . '</a>'; }
						}

						?>
	
					<div class="post-header">
						<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>	
						<?php the_author_posts_link(); ?> | <?php the_category( ', ' ); ?> | <a href="<?php comments_link(); ?>">
						<?php comments_number( __( 'No Comments', 'salient' ), __( 'One Comment', 'salient' ), '% ' . __( 'Comments', 'salient' ) ); ?></a>
					</div><!--/post-header-->
					
						<?php the_excerpt(); ?>
					
				</div><!--/span_3-->
				
				<?php
				endwhile;
				endif;
				?>
		
			</div><!--/blog-recent-->
	
	
	</div><!--/container-->
	<?php nectar_hook_before_container_wrap_close(); ?>
</div><!--/home-wrap-->

<?php get_footer(); ?>