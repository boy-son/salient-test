<?php
/**
 * Post single author bio - used on all theme skins except Ascend.
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

$theme_skin = NectarThemeManager::$skin;

$grav_size     = 80;
$fw_class      = null;
$has_tags      = 'false';

if ( ! empty( $nectar_options['display_tags'] ) &&
	$nectar_options['display_tags'] === '1' && has_tag() ) {
	$has_tags = 'true';
}

?>

<div id="author-bio" class="<?php echo esc_attr( $fw_class ); // WPCS: XSS ok. ?>" data-has-tags="<?php echo esc_attr( $has_tags );  // WPCS: XSS ok. ?>">

	<div class="span_12">

	<?php
	if ( function_exists( 'get_avatar' ) ) {
		echo get_avatar( get_the_author_meta( 'email' ), $grav_size, null, get_the_author() ); }
	?>
	<div id="author-info">

	  <h3 class="nectar-link-underline-effect <?php echo apply_filters( 'nectar_author_info_class', 'nectar-author-info-title' ); ?>"><span><?php if ( $theme_skin === 'ascend' ) { _e( 'Author', 'salient' ); } elseif ( $theme_skin !== 'material' ) { _e( 'About', 'salient' ); } ?></span>

		<?php
		if ( $theme_skin === 'material' ) {
			echo '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">';
		}
		echo get_the_author();
		if ( $theme_skin === 'material' ) {
			echo '</a>';
		}
		?>
		</h3>
	  <p><?php the_author_meta( 'description' ); ?></p>

	</div>

	<?php
	if ( $theme_skin === 'ascend' ) {
		echo '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" data-hover-text-color-override="#fff" data-hover-color-override="false" data-color-override="#000000" class="nectar-button see-through-2 large"> ' . esc_html__( 'More posts by', 'salient' ) . ' ' . get_the_author() . ' </a>'; }
	?>

	<div class="clear"></div>

	</div><!--/span_12-->

</div><!--/author-bio-->
