<?php
/**
 * Post categories
 *
 * @package Salient WordPress Theme
 * @subpackage Partials
 * @version 16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action('nectar_single_post_header_before_categories');

?>

<span class="meta-category nectar-inherit-label">

<?php
$categories = get_the_category();
if ( ! empty( $categories ) ) {
  $output = nectar_get_category_list(false, 'nectar-inherit-border-radius nectar-bg-hover-accent-color');
  echo apply_filters('nectar_blog_page_header_categories', trim( $output )); // WPCS: XSS ok.
}
?>
</span>