<?php
/**
 * Fallback Post Template
 *
 * This file is here only in case a legacy child theme calls it.
 * It simply gets the post template needed from includes/partials/blog/styles,
 * which is the new location of all post templates. If your theme is calling this
 * from index.php or single.php, please update your files to use the new templates.
 *
 * @package Salient WordPress Theme
 * @subpackage Post Templates
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$nectar_options        = get_nectar_theme_options();
$nectar_post_format    = get_post_format();
$masonry_class         = null;
$masonry_style_parsed  = null;
$standard_style_parsed = null;
$blog_standard_type    = ( ! empty( $nectar_options['blog_standard_type'] ) ) ? $nectar_options['blog_standard_type'] : 'classic';
$blog_type             = $nectar_options['blog_type'];

if ( null === $blog_type ) {
	$blog_type = 'std-blog-sidebar';
}

// Using masonry.
if ( $blog_type === 'masonry-blog-sidebar' || 
	$blog_type === 'masonry-blog-fullwidth' || 
	$blog_type === 'masonry-blog-full-screen-width' ) {
	$masonry_class = 'masonry';
}

// Store styles
if ( null !== $masonry_class ) {
	$masonry_style        = ( ! empty( $nectar_options['blog_masonry_type'] ) ) ? $nectar_options['blog_masonry_type'] : 'classic';
	$masonry_style_parsed = str_replace('_', '-', $masonry_style);
} else {
	$standard_style_parsed = str_replace('_', '-', $blog_standard_type);
}

// Only allow Salient post formats.
if( get_post_format() === 'image' || 
get_post_format() === 'aside' || 
get_post_format() === 'status' ) {
	$nectar_post_format = false;
}

if( ! is_single() ) {
	
	// Masonry layouts.
	if( null !== $masonry_class ) {
		get_template_part( 'includes/partials/blog/styles/masonry-'.$masonry_style_parsed.'/entry', $nectar_post_format );
	}
	// Standard layouts.
	else {
		get_template_part( 'includes/partials/blog/styles/standard-'.$standard_style_parsed.'/entry', $nectar_post_format );
	}
	
} else {
	get_template_part( 'includes/partials/single-post/post-content' );
}