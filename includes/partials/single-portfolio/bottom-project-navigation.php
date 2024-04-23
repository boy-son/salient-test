<?php
/**
 * Fallback bottom project navigation template
 *
 * This file is here only in case a legacy child theme calls it.
 * If your child theme is calling this from salient-child/single-portfolio.php,
 * please update your child theme to contain the actual file
 * (includes/partials/single-portfolio/bottom-project-navigation.php). The portfolio post 
 * type is now contained in a plugin (Salient Portfolio) and not apart of the theme.
 *
 * @package Salient WordPress Theme
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<div class="bottom_controls"> <div class="container">';
	if( function_exists('nectar_project_single_controls') ) {
		nectar_project_single_controls();
	}
echo '</div></div>';
