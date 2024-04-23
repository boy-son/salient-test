<?php
/**
 * Header search template
 *
 * @package    Salient WordPress Theme
 * @subpackage Includes
 * @version 13.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nectar_options = get_nectar_theme_options();

if ( ! empty( $nectar_options['header-disable-ajax-search'] ) && '1' === $nectar_options['header-disable-ajax-search'] ) {
	$ajax_search = 'no';
} else {
	$ajax_search = 'yes';
} 

$bottom_helper_text = true;
if( isset($nectar_options['header-search-remove-bt']) && '1' === $nectar_options['header-search-remove-bt'] ) {
	$bottom_helper_text = false;
}

?>

<div id="search-outer" class="nectar">
	<div id="search">
		<div class="container">
			 <div id="search-box">
				 <div class="inner-wrap">
					 <div class="col span_12">
						  <form role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="GET">
							<?php
							$theme_skin = NectarThemeManager::$skin;
							
							$placeholder_text = ( 'material' === $theme_skin ) ? esc_attr__( 'Search', 'salient' ) : esc_attr__( 'Start Typing...', 'salient' );
							
							if( isset($nectar_options['header-search-ph-text']) && strlen($nectar_options['header-search-ph-text']) > 2 ) {
								$placeholder_text = $nectar_options['header-search-ph-text'];
							}
							
							if ( 'material' === $theme_skin ) {
							?>
							 <input type="text" name="s" <?php if ( 'yes' === $ajax_search ) { echo 'id="s"'; } ?> value="" aria-label="<?php echo esc_attr__( 'Search', 'salient' ); ?>" placeholder="<?php echo esc_attr($placeholder_text); ?>" />
							 <?php
							} else {
								?>
								<input type="text" name="s" <?php if ( 'yes' === $ajax_search ) { echo 'id="s"'; } ?> value="<?php echo esc_attr($placeholder_text); ?>" aria-label="<?php echo esc_attr__( 'Search', 'salient' ); ?>" data-placeholder="<?php echo esc_attr($placeholder_text); ?>" />
							<?php } ?>

						<?php
						if ( 'ascend' === $theme_skin && 'no' === $ajax_search && false !== $bottom_helper_text ) {
							echo '<span><i>' . __( 'Press enter to begin your search', 'salient' ) . '</i></span>'; }
						if ( 'material' === $theme_skin && false !== $bottom_helper_text ) {
							echo '<span>' . esc_html__( 'Hit enter to search or ESC to close', 'salient' ) . '</span>'; }
						?>

						<?php
						// Limit post type
						$post_types_list = array('post','product','portfolio');

						if( isset($nectar_options['header-search-limit']) && in_array($nectar_options['header-search-limit'],$post_types_list) ) {
							echo '<input type="hidden" name="post_type" value="'.esc_attr($nectar_options['header-search-limit']).'">';
						}
						?>
						</form>
					</div><!--/span_12-->
				</div><!--/inner-wrap-->
			 </div><!--/search-box-->
			 <div id="close"><a href="#"><span class="screen-reader-text"><?php echo esc_html__('Close Search','salient'); ?></span>
				<?php
				if ( 'material' === $theme_skin ) {
					echo '<span class="close-wrap"> <span class="close-line close-line1"></span> <span class="close-line close-line2"></span> </span>';
				} else {
					echo '<span class="icon-salient-x" aria-hidden="true"></span>';
				}
				?>
				 </a></div>
		 </div><!--/container-->
	</div><!--/search-->
</div><!--/search-outer-->
