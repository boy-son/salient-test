<?php
/**
* Single Post Content
*
* @version 13.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $nectar_options;

$nectar_post_format            = get_post_format();
$hide_featrued_image           = ( ! empty( $nectar_options['blog_hide_featured_image'] ) ) ? $nectar_options['blog_hide_featured_image'] : '0';
$blog_post_type_list = array('post');
if( has_filter('nectar_metabox_post_types_post_header') ) {
	$blog_post_type_list = apply_filters('nectar_metabox_post_types_post_header', $blog_post_type_list);
}
$is_blog_header_post_type      = ( isset($post->post_type) && in_array($post->post_type, $blog_post_type_list) && is_single()) ? true : false;
$single_post_header_inherit_fi = ( ! empty( $nectar_options['blog_post_header_inherit_featured_image'] ) && $is_blog_header_post_type ) ? $nectar_options['blog_post_header_inherit_featured_image'] : '0';
$blog_header_type              = ( ! empty( $nectar_options['blog_header_type'] ) ) ? $nectar_options['blog_header_type'] : 'default';
$blog_social_style             = ( get_option( 'salient_social_button_style' ) ) ? get_option( 'salient_social_button_style' ) : 'fixed';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  
  <div class="inner-wrap">

		<div class="post-content" data-hide-featured-media="<?php echo esc_attr( $hide_featrued_image ); ?>">
      
        <?php
  
        if( function_exists('nectar_social_sharing_output') && 'default' == $blog_social_style && 'image_under' === $blog_header_type) {
          nectar_social_sharing_output('vertical');
        }
        
        $gallery_attr = null;
                
        if( '1' !== $hide_featrued_image && 'image_under' !== $blog_header_type ) {
          
          // Featured Image.
          if( null === $nectar_post_format || false === $nectar_post_format || 'image' === $nectar_post_format) {
            if ( has_post_thumbnail() && '1' !== $single_post_header_inherit_fi ) {
              echo '<span class="post-featured-img">' . get_the_post_thumbnail( $post->ID, 'full', array( 'title' => '' ) ) . '</span>';
            }
          } 
          
          // Video.
          else if( 'video' === $nectar_post_format ) {
            get_template_part( 'includes/partials/blog/media/video-player' );
          }
          // Audio.
          else if( 'audio' === $nectar_post_format ) {
            get_template_part( 'includes/partials/blog/media/audio-player' );
          }
          
        }
        
        // Quote.
        if( 'quote' === $nectar_post_format ) {
          get_template_part( 'includes/partials/blog/media/quote' );
        }
        
        // Link.
        else if( 'link' === $nectar_post_format ) {
          get_template_part( 'includes/partials/blog/media/link' );
        }
        
        // Gallery.
        else if( 'gallery' === $nectar_post_format && '1' !== $hide_featrued_image ) {
          
          $enable_gallery_slider = get_post_meta( get_the_ID(), '_nectar_gallery_slider', true );
          if ( ! empty( $enable_gallery_slider ) && 'on' === $enable_gallery_slider ) {
            
            $gallery_script = 'flickity';
            $blog_type      = ( isset($nectar_options['blog_type']) ) ? $nectar_options['blog_type'] : '';
            
            // Blog Type/Style will determine what gallery script is used.
            if( strpos($blog_type, 'masonry') !== false ) {
              
              // Masonry style.
              $blog_masonry_style = ( ! empty( $nectar_options['blog_masonry_type'] ) ) ? $nectar_options['blog_masonry_type'] : 'classic';
              
              if( 'classic' === $blog_masonry_style ) {
                $gallery_script = 'flexslider'; 
              } 
              
            } else {
              // Standard style.
              $blog_standard_style = ( ! empty( $nectar_options['blog_standard_type'] ) ) ? $nectar_options['blog_standard_type'] : 'classic';
              
              if( 'classic' === $blog_standard_style ) {
                $gallery_script = 'flexslider'; 
              } 
            }
            
            if( 'flickity' === $gallery_script ) {
              echo '<div class="top-featured-media full-width-content wpb_row vc_row-fluid standard_section">';
              get_template_part( 'includes/partials/blog/media/gallery-flickity' );
              echo '</div>';
            } else {
              get_template_part( 'includes/partials/blog/media/gallery-flexslider' );
            }
            
            $gallery_attr = ' data-has-gallery';
          }
        }
          
        
        echo '<div class="content-inner"'. esc_html($gallery_attr).'>';
          
          // Post content.
          if( 'link' !== $nectar_post_format ) {
            the_content( '<span class="continue-reading">' . esc_html__( 'Read More', 'salient' ) . '</span>' );
          }
          
          // Tags.
          if ( '1' === $nectar_options['display_tags'] && has_tag() ) {
            echo '<div class="post-tags"><h4>' . esc_html__( 'Tags:', 'salient' ) . '</h4>';
            the_tags( '', '', '' );
            echo '<div class="clear"></div></div> ';
          }

        echo '</div>';
          

        
        ?>
        
      </div><!--/post-content-->
      
    </div><!--/inner-wrap-->
    
</article>