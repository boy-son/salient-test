<?php
/**
 * Fallback project sidebar Template
 *
 * This file is here only in case a legacy child theme calls it.
 * If your child theme is calling this from salient-child/single-portfolio.php,
 * please update your child theme to contain the actual file
 * (includes/partials/single-portfolio/sidebar.php). The portfolio post 
 * type is now contained in a plugin (Salient Portfolio) and not apart of the theme.
 *
 * @package Salient WordPress Theme
 * @version 10.5
 */
 

 $options = get_nectar_theme_options(); 
 
 $project_social_style = ( get_option( 'salient_social_button_style' ) ) ? get_option( 'salient_social_button_style' ) : 'fixed';
 
 if( !function_exists('nectar_social_sharing_output') ) {
 	$project_social_style = 'fixed';
 }
 
 global $post;
 
 ?>
 
 <div id="sidebar" class="col span_3 col_last" data-follow-on-scroll="<?php echo ( ! empty( $options['portfolio_sidebar_follow'] ) && $options['portfolio_sidebar_follow'] == 1 ) ? 1 : 0; ?>">
 		
   <div id="sidebar-inner">
 	
 	<div id="project-meta">
 
 		<ul class="project-sharing" data-sharing-style="<?php echo esc_attr( $project_social_style ); ?>"> 
 
 		<?php
 		// portfolio social sharting icons
 		if ( $project_social_style !== 'fixed' && defined( 'NECTAR_THEME_NAME' ) ) {
 
 			if( function_exists('nectar_social_sharing_output') ) {
 				nectar_social_sharing_output('hover');
 			}
 
 		}
 		
 		
 		if ( ! empty( $options['portfolio_date'] ) && $options['portfolio_date'] == 1 ) {
 			if ( $project_social_style === 'fixed' ) {
 				?>
 
 			<li class="project-date">
 				<?php the_time( 'F d, Y' ); ?>
 			</li>
 				<?php
 			}
 		}
 		?>
 	  </ul><!--sharing-->
 
 	  <div class="clear"></div>
 	</div><!--project-meta-->
 	
 	<?php
 	
 	$nectar_using_VC_front_end_editor = ( isset($_GET['vc_editable']) ) ? sanitize_text_field($_GET['vc_editable']) : '';
 	$nectar_using_VC_front_end_editor = ($nectar_using_VC_front_end_editor == 'true') ? true : false;
 	
 	if( $nectar_using_VC_front_end_editor ) {
 		
 		$fe_editor_sidebar_content = $post->post_content;
 		
 		if( function_exists( 'wptexturize' ) ) {
 			$fe_editor_sidebar_content = wptexturize($fe_editor_sidebar_content);
 		}
 		if( function_exists( 'convert_smilies' ) ) {
 			$fe_editor_sidebar_content = convert_smilies($fe_editor_sidebar_content);
 		}
 		if( function_exists( 'convert_chars' ) ) {
 			$fe_editor_sidebar_content = convert_chars($fe_editor_sidebar_content);
 		}
 		if( function_exists( 'wpautop' ) ) {
 			$fe_editor_sidebar_content = wpautop($fe_editor_sidebar_content);
 		}
 		if( function_exists( 'shortcode_unautop' ) ) {
 			$fe_editor_sidebar_content = shortcode_unautop($fe_editor_sidebar_content);
 		}
 		
 		echo wp_kses_post( $fe_editor_sidebar_content );
 		
 	} else {
 		the_content();
 	}
 
 	
 	
 	$project_attrs = get_the_terms( $post->ID, 'project-attributes' );
 	if ( ! empty( $project_attrs ) ) {
 		?>
 	  <ul class="project-attrs checks">
 		<?php
 		foreach ( $project_attrs as $attr ) {
 			echo '<li>' . wp_kses_post( $attr->name ) . '</li>';
 		}
 		?>
 	  </ul>
 	<?php } ?>
   
 
   </div>
   
 </div><!--/sidebar-->