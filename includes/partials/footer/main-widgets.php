<?php
/**
* Footer widget area
*
* @package Salient WordPress Theme
* @subpackage Partials
* @version 10.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

$using_footer_widget_area = ( ! empty( $nectar_options['enable-main-footer-area'] ) && $nectar_options['enable-main-footer-area'] === '1' ) ? 'true' : 'false';
$footer_columns           = ( ! empty( $nectar_options['footer_columns'] ) ) ? $nectar_options['footer_columns'] : '4';
$footer_has_widgets       = ( is_active_sidebar( 'Footer Area 1' ) || is_active_sidebar( 'Footer Area 2' ) || is_active_sidebar( 'Footer Area 3' ) || is_active_sidebar( 'Footer Area 4' ) ) ? 'true' : 'false';

if ( $using_footer_widget_area === 'true' ) { ?>
	
	<div id="footer-widgets" data-has-widgets="<?php echo esc_attr( $footer_has_widgets ); ?>" data-cols="<?php echo esc_attr( $footer_columns ); ?>">
		
		<div class="container">
			
			<?php nectar_hook_before_footer_widget_area(); ?>
			
			<div class="row">
				
				<?php
				
				if ( $footer_columns === '1' ) {
					$footer_column_class = 'span_12';
				} elseif ( $footer_columns === '2' ) {
					$footer_column_class = 'span_6';
				} elseif ( $footer_columns === '3' ) {
					$footer_column_class = 'span_4';
				} else {
					$footer_column_class = 'span_3';
				}
				?>
				
				<div class="col <?php echo esc_attr( $footer_column_class ); // WPCS: XSS ok. ?>">
					<?php
					if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Footer Area 1' ) ) :
						else :
							?>
							<div class="widget">			
							</div>
						<?php endif; ?>
					</div>
					
					<?php 
					if ( $footer_columns === '2' || 
					$footer_columns === '3' || 
					$footer_columns === '4' || 
					$footer_columns === '5' ) { ?>
						
						<div class="col <?php echo esc_attr( $footer_column_class ); // WPCS: XSS ok. ?>">
							<?php
							if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Footer Area 2' ) ) :
								else :
									?>
									<div class="widget">			
									</div>
								<?php endif; ?>
								
							</div>
							
						<?php } ?>
						
						
						<?php 
						if ( $footer_columns === '3' || 
						$footer_columns === '4' || 
						$footer_columns === '5' ) { ?>
							<div class="col <?php echo esc_attr( $footer_column_class ); // WPCS: XSS ok. ?>">
								<?php
								if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Footer Area 3' ) ) :
									else :
										?>
										<div class="widget">			
										</div>		   
									<?php endif; ?>
									
								</div>
							<?php } ?>
							
							<?php if ( $footer_columns === '4' || $footer_columns === '5' ) { ?>
								<div class="col <?php echo esc_attr( $footer_column_class ); // WPCS: XSS ok. ?>">
									<?php
									if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'Footer Area 4' ) ) :
										else :
											?>
											<div class="widget">		
											</div>
										<?php endif; ?>
										
									</div>
								<?php } ?>
								
							</div>
							<?php nectar_hook_after_footer_widget_area(); ?>
						</div><!--/container-->
					</div><!--/footer-widgets-->
					
					<?php
				} //endif for enable main footer area