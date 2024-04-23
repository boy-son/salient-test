<?php
/**
 * Post single default minimal header style bottom social sharing bar.
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

?>

<div class="bottom-meta">	
	<?php

	$using_post_pag      = ( ! empty( $nectar_options['blog_next_post_link'] ) && $nectar_options['blog_next_post_link'] === '1' ) ? true : false;
	$using_related_posts = ( ! empty( $nectar_options['blog_related_posts'] ) && ! empty( $nectar_options['blog_related_posts'] ) === '1' ) ? true : false;
	$extra_bottom_space  = ( $using_related_posts && ! $using_post_pag ) ? 'false' : 'true';

	echo '<div class="sharing-default-minimal" data-bottom-space="' . $extra_bottom_space . '">'; // WPCS: XSS ok.
		if( function_exists('nectar_social_sharing_output') ) {
		  nectar_social_sharing_output('default');
		}
	echo '</div>';
	?>
</div>
