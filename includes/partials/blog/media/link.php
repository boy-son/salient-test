<?php
/**
 * Link blog partial.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
global $nectar_options;

$link            = get_post_meta( $post->ID, '_nectar_link', true );
$link_text       = get_the_content();

?>

<div class="link-inner">
  
  <a target="_blank" class="full-post-link" href="<?php echo esc_url( $link ); ?>"></a>
  
  <?php if( has_post_thumbnail() ) {
    
    $link_bg_img_src = wp_get_attachment_url( get_post_thumbnail_id() );
    
    // Lazy load.
    if( !empty($nectar_options['blog_lazy_load']) && '1' === $nectar_options['blog_lazy_load'] ) {
      echo '<div class="n-post-bg" data-nectar-img-src="' . esc_url( $link_bg_img_src ) . '"></div>';
    } else {
      echo '<div class="n-post-bg" style=" background-image: url(' . esc_url( $link_bg_img_src ) . '); "></div>';
    }
    
  } else {
    echo '<div class="n-post-bg"></div>';
  } ?>
  
  <span class="link-wrap">
    
    <h3 class="title"><?php
      if ( empty( $link_text ) ) {
        echo get_the_title();
      } else {
        echo wp_kses_post( $link_text ); }
      ?></h3>
    
    <span class="destination"><?php echo wp_kses_post( $link ); ?></span>

  </span>
  
  <span class="icon"></span>
  
</div><!--/link-inner-->
