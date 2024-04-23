<?php
/**
* Post single bottom meta bar - used only with the Ascend theme skin when the fullscreen header layout is in use.
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

$fullscreen_header                 = ( ! empty( $nectar_options['blog_header_type'] ) && $nectar_options['blog_header_type'] === 'fullscreen' && is_singular( 'post' ) ) ? true : false;
$fullscreen_class                  = ( true === $fullscreen_header ) ? 'fullscreen-header full-width-content' : null;
$remove_single_post_comment_number = ( ! empty( $nectar_options['blog_remove_single_comment_number'] ) ) ? $nectar_options['blog_remove_single_comment_number'] : '0';
$blog_social_style                 = ( get_option( 'salient_social_button_style' ) ) ? get_option( 'salient_social_button_style' ) : 'fixed';

?>

<div id="single-below-header" data-remove-post-comment-number="<?php echo esc_attr( $remove_single_post_comment_number ); ?>">
	<?php 
	if ( $blog_social_style !== 'fixed' ) { 
		if( function_exists('nectar_social_sharing_output') ) {
			nectar_social_sharing_output('hover');
		}
	} 
	$categories = get_the_category();
	$output     = null;
	
	if ( ! empty( $categories ) ) {
		foreach( $categories as $category ) {
			$output .= '<a class="'.$category->slug.'" href="' . esc_url( get_category_link( $category->term_id ) ) . '" > <i class="icon-default-style steadysets-icon-book2"></i> ' . esc_html( $category->name ) . '</a>';
		}
	}
	?>
	<span class="meta-category"><?php echo trim( $output); ?></span>
	<span class="meta-comment-count"><a class="comments-link" href="<?php comments_link(); ?>"><i class="icon-default-style steadysets-icon-chat-3"></i> <?php comments_number( esc_html__( 'No Comments', 'salient' ), esc_html__( 'One Comment ', 'salient' ), esc_html__( '% Comments', 'salient' ) ); ?></a></span>
</div><!--/single-below-header-->
