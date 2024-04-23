<?php
/**
 * Footer copyright bar
 *
 * @package Salient WordPress Theme
 * @subpackage Partials
 * @version 13.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

$disable_footer_copyright = ( ! empty( $nectar_options['disable-copyright-footer-area'] ) && $nectar_options['disable-copyright-footer-area'] === '1' ) ? 'true' : 'false';
$copyright_footer_layout  = ( ! empty( $nectar_options['footer-copyright-layout'] ) ) ? $nectar_options['footer-copyright-layout'] : 'default';
$footer_columns           = ( ! empty( $nectar_options['footer_columns'] ) ) ? $nectar_options['footer_columns'] : '4';


if ( 'false' === $disable_footer_copyright ) {
	?>

  <div class="row" id="copyright" data-layout="<?php echo esc_attr( $copyright_footer_layout ); ?>">
	
	<div class="container">
	   
		<?php if ( '1' !== $footer_columns ) { ?>
		<div class="col span_5">
		   
			<?php
			if ( $copyright_footer_layout === 'centered' ) {
				if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Footer Copyright' ) ) :
          else :
	           ?>
	
  				<div class="widget">			
  				</div>		   
  			<?php
			endif;
			}
      
      nectar_footer_copyright_text();
      
			?>

		</div><!--/span_5-->
		<?php } ?>
	   
	  <div class="col span_7 col_last">
      <ul class="social">
        <?php 
        $social_networks = nectar_get_social_media_list();
        
        foreach ( $social_networks as $network_name => $icon_arr ) {
          
          $leading_fa = ('font-awesome' === $icon_arr['icon_type']) ? 'fa ': '';
          
          if ( 'rss' === $network_name ) {
            
            if ( ! empty( $nectar_options[ 'use-' . $network_name . '-icon' ] ) && $nectar_options[ 'use-' . $network_name . '-icon' ] === '1' ) {
              
              $nectar_rss_url_link = ( ! empty( $nectar_options['rss-url'] ) ) ? $nectar_options['rss-url'] : get_bloginfo( 'rss_url' );
              echo '<li><a target="_blank" rel="noopener" href="' . esc_url( $nectar_rss_url_link ) . '"><span class="screen-reader-text">RSS</span><i class="' . esc_attr($leading_fa) . esc_attr($icon_arr['icon_class']) . '" aria-hidden="true"></i></a></li>';
              
            }
            
          } else {
            
            $target_attr = ( 'email' !== $network_name && 'phone' !== $network_name ) ? 'target="_blank" rel="noopener"' : '';
            
            if ( ! empty( $nectar_options[ 'use-' . $network_name . '-icon' ] ) && $nectar_options[ 'use-' . $network_name . '-icon' ] === '1' ) {
              
              if( isset($nectar_options[ $network_name . '-url' ]) ) {
                echo '<li><a '.$target_attr.' href="' . esc_url( $nectar_options[ $network_name . '-url' ] ) . '"><span class="screen-reader-text">'.esc_attr($network_name).'</span><i class="' . esc_attr($leading_fa) . esc_attr($icon_arr['icon_class']) . '" aria-hidden="true"></i></a></li>';
              } else {
                echo '<li><a '.$target_attr.' href="#"><span class="screen-reader-text">'.esc_attr($network_name).'</span><i class="' . esc_attr($leading_fa) . esc_attr($icon_arr['icon_class']) . '" aria-hidden="true"></i></a></li>';
              }
              
            }
            
          }
          
        } // End social network loop.
        
        ?>
      </ul>
	  </div><!--/span_7-->
    
	  <?php if ( '1' === $footer_columns ) { ?>
		<div class="col span_5">
			<?php
			if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Footer Copyright' ) ) :
      else :
      ?>
			<div class="widget"></div>		   
		<?php endif; 
    nectar_footer_copyright_text();
    ?>
		</div><!--/span_5-->
		<?php } ?>
	
	</div><!--/container-->
  </div><!--/row-->
	<?php }