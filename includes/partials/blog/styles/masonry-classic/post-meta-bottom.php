<?php
/**
* Post meta bottom partial
*
* Used when "Classic" masonry style is selected.
*
* @version 12.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $post;
global $nectar_options;

?>

<div class="post-meta">
  
  <div class="date">
    <?php 
    $date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';
    
    if( 'last_editied_date' === $date_functionality ) {
      $date = get_the_modified_date();
    } else {
      $date = get_the_date();
    }
    echo esc_html($date); ?>
  </div>
  
  <div class="nectar-love-wrap">
    <?php
    
    $use_nectar_love    = true;
    $remove_nectar_love = get_option( 'salient_social_remove_love', '0' );
    
    if( function_exists('nectar_social_sharing_output') && '1' === $remove_nectar_love ) {
      $use_nectar_love = false;
    }
    
    if ( function_exists( 'nectar_love' ) && false !== $use_nectar_love ) {
      nectar_love();
    }
    ?>
  </div>
  
</div><!--/post-meta-->