<?php
/**
 * Quote blog partial.
 *
 * @version 10.5
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $nectar_options;

$quote        = get_post_meta( $post->ID, '_nectar_quote', true );
$quote_author = get_post_meta( $post->ID, '_nectar_quote_author', true );

?>

<div class="quote-inner"> 
  <?php 
    if( has_post_thumbnail() ) {
      $quote_bg_img_src = wp_get_attachment_url( get_post_thumbnail_id() );
      
      // Lazy load.
      if( !empty($nectar_options['blog_lazy_load']) && '1' === $nectar_options['blog_lazy_load'] ) {
        echo '<div class="n-post-bg" data-nectar-img-src="' . esc_url( $quote_bg_img_src ) . '"></div>';
      } else {
        echo '<div class="n-post-bg" style=" background-image: url(' . esc_url( $quote_bg_img_src ) . '); "></div>';
      }

    } else {
      echo '<div class="n-post-bg"></div>';
    }
  
    if( ! is_single() ) {
      echo '<a class="full-post-link" href="' . esc_url( get_permalink() ) . '"></a>'; 
    }
    ?>
    
    <span class="quote-wrap">
        <h3 class="title"><?php echo wp_kses_post( $quote ); ?></h3>
        <span class="author"><?php
          if ( ! empty( $quote_author ) ) {
            echo wp_kses_post( $quote_author );
          } else {
            the_title();
          }
          ?></span> 
    </span>
    
    <span class="icon"></span>
    
</div><!--/quote-inner-->

