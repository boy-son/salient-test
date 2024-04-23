<?php
/**
* Post meta partial
*
* Used when "Classic" standard style is selected.
*
* @version 12.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $nectar_options;

$use_nectar_love    = 'true';
$remove_nectar_love = get_option( 'salient_social_remove_love', '0' );

if( function_exists('nectar_social_sharing_output') && '1' === $remove_nectar_love ) {
  $use_nectar_love = 'false';
}

?>

<div class="post-meta" data-love="<?php echo esc_attr($use_nectar_love); ?>">
  <?php 
    $date_functionality = (isset($nectar_options['post_date_functionality']) && !empty($nectar_options['post_date_functionality'])) ? $nectar_options['post_date_functionality'] : 'published_date';
    if( 'last_editied_date' === $date_functionality ) {
      $month = get_the_modified_time('M');
      $day = get_the_modified_time('d');
      $year = get_the_modified_time('Y');
    } else {
      $month = get_the_time( 'M' );
      $day = get_the_time( 'd' );
      $year = get_the_time( 'Y' );
    }
  ?>
  <div class="date">
    <span class="month"><?php echo esc_html($month); ?></span>
    <span class="day"><?php echo esc_html($day); ?></span>
    <?php
    if ( ! empty( $nectar_options['display_full_date'] ) && $nectar_options['display_full_date'] === '1' ) {
      echo '<span class="year">' . esc_html($year) . '</span>';
    } 
    ?>
  </div>
  
  <div class="nectar-love-wrap">
    <?php
    if ( function_exists( 'nectar_love' ) && 'false' !== $use_nectar_love ) {
      nectar_love();
    }
    ?>
  </div>
  
</div><!--post-meta-->