<?php
/**
* Post bottom meta partial
*
* Used when "Classic Enhanced" masonry style is selected.
*
* @version 12.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;

$post_img_class = ( ! has_post_thumbnail() ) ? 'no-img' : '';

?>

<div class="post-meta <?php echo esc_attr( $post_img_class ); ?>">
  
  <span class="meta-author"> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <i class="icon-default-style icon-salient-m-user"></i> <?php the_author(); ?></a> </span> 
  
  <?php if ( comments_open() ) { ?>
    <span class="meta-comment-count">  <a href="<?php comments_link(); ?>">
      <i class="icon-default-style steadysets-icon-chat-3"></i> <?php comments_number( '0', '1', '%' ); ?></a>
    </span>
  <?php } ?>
  
  <div class="nectar-love-wrap">
    <?php
    
    $use_nectar_love    = true;
    $remove_nectar_love = get_option( 'salient_social_remove_love', '0' );
    
    if( function_exists('nectar_social_sharing_output') && '1' === $remove_nectar_love ) {
      $use_nectar_love = false;
    }
    
    if ( function_exists( 'nectar_love' ) && false !== $use_nectar_love ) {
      nectar_love();}
    ?>
  </div>
    
</div><!--/post-meta-->