<?php
/**
 * Post single author bio - used only with the Ascend theme skin.
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

$theme_skin = NectarThemeManager::$skin;

$fullscreen_header = ( ! empty( $nectar_options['blog_header_type'] ) && $nectar_options['blog_header_type'] === 'fullscreen' && is_singular( 'post' ) ) ? true : false;
$grav_size         = 80;
$fw_class          = 'full-width-section ';
$next_post         = get_previous_post();
$next_post_button  = ( ! empty( $nectar_options['blog_next_post_link'] ) && $nectar_options['blog_next_post_link'] === '1' ) ? 'on' : 'off';

?>

<div id="author-bio" data-midnight="dark" class="<?php echo esc_attr( $fw_class ); // WPCS: XSS ok. ?> <?php if ( empty( $next_post ) || $next_post_button === 'off' || $fullscreen_header === false && $next_post_button === 'off' ) { echo 'no-pagination';} ?> ">

	<div class="span_12">
	<?php
	if ( function_exists( 'get_avatar' ) ) {
		echo get_avatar( get_the_author_meta( 'email' ), $grav_size, null, get_the_author() ); }
	?>
	<div id="author-info">
	  <h3 class="<?php echo apply_filters( 'nectar_author_info_class', 'nectar-author-info-title' ); ?>"><span><?php if ( $theme_skin === 'ascend' ) { echo '<i>' . esc_html__( 'Author', 'salient' ) . '</i>'; } else { _e( 'About', 'salient' ); } ?></span> <?php the_author(); ?></h3>
	  <p><?php the_author_meta( 'description' ); ?></p>
	</div>
	<?php
	if ( $theme_skin === 'ascend' ) {
		echo '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" data-hover-text-color-override="#fff" data-hover-color-override="false" data-color-override="#000000" class="nectar-button see-through-2 large">' . esc_html__( 'More posts by', 'salient' ) . ' ' . get_the_author() . ' </a>';
	}
	?>
	<div class="clear"></div>

  </div><!--/span_12-->

</div><!--/author-bio-->
