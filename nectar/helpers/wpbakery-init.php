<?php
/**
 * Salient WPBakery page builder initialization
 *
 * @package Salient WordPress Theme
 * @subpackage helpers
 * @version 10.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $nectar_options;

// WPBakery AI functionality.
if ( isset($nectar_options['wpbakery-ai']) && '1' === $nectar_options['wpbakery-ai'] ) {
	add_filter('nectar_wpbakery_ai_enabled', '__return_true');
}

// Salient WPBakery is active, but the Salient core plugin is not.
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) && defined( 'SALIENT_VC_ACTIVE' ) && ! class_exists( 'Salient_Core' ) ) {


	if( ! function_exists('nectar_vc_library_cat_list') ) {
	  function nectar_vc_library_cat_list() {
	    
	  } 
	}

	add_action('vc_before_init', 'nectar_wpbakery_default_maps');

	function nectar_wpbakery_default_maps() {
	  
	  vc_map( array(
	  	'name' => esc_html__( 'Inner Column', 'salient' ),
	  	'base' => 'vc_column_inner',
	  	'icon' => 'icon-wpb-row',
	  	'class' => '',
	  	'wrapper_class' => '',
	  	'controls' => 'full',
	  	'allowed_container_element' => false,
	  	'content_element' => false,
	  	'is_container' => true,
	  	'description' => esc_html__( 'Place content elements inside the inner column', 'salient' ),
	  	'params' => array(
	  		array(
	  			'type' => 'el_id',
	  			'heading' => esc_html__( 'Element ID', 'salient' ),
	  			'param_name' => 'el_id',
	  			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'salient' ), '//www.w3schools.com/tags/att_global_id.asp' ),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Extra class name', 'salient' ),
	  			'param_name' => 'el_class',
	  			'value' => '',
	  			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'css_editor',
	  			'heading' => esc_html__( 'CSS box', 'salient' ),
	  			'param_name' => 'css',
	  			'group' => esc_html__( 'Design Options', 'salient' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Width', 'salient' ),
	  			'param_name' => 'width',
	  			'value' => array(
	  				esc_html__( '1 column - 1/12', 'salient' ) => '1/12',
	  				esc_html__( '2 columns - 1/6', 'salient' ) => '1/6',
	  				esc_html__( '3 columns - 1/4', 'salient' ) => '1/4',
	  				esc_html__( '4 columns - 1/3', 'salient' ) => '1/3',
	  				esc_html__( '5 columns - 5/12', 'salient' ) => '5/12',
	  				esc_html__( '6 columns - 1/2', 'salient' ) => '1/2',
	  				esc_html__( '7 columns - 7/12', 'salient' ) => '7/12',
	  				esc_html__( '8 columns - 2/3', 'salient' ) => '2/3',
	  				esc_html__( '9 columns - 3/4', 'salient' ) => '3/4',
	  				esc_html__( '10 columns - 5/6', 'salient' ) => '5/6',
	  				esc_html__( '11 columns - 11/12', 'salient' ) => '11/12',
	  				esc_html__( '12 columns - 1/1', 'salient' ) => '1/1',
	  				esc_html__( '20% - 1/5', 'salient' ) => '1/5',
	  				esc_html__( '40% - 2/5', 'salient' ) => '2/5',
	  				esc_html__( '60% - 3/5', 'salient' ) => '3/5',
	  				esc_html__( '80% - 4/5', 'salient' ) => '4/5',
	  			),
	  			'group' => esc_html__( 'Responsive Options', 'salient' ),
	  			'description' => esc_html__( 'Select column width.', 'salient' ),
	  			'std' => '1/1',
	  		),
	  		array(
	  			'type' => 'column_offset',
	  			'heading' => esc_html__( 'Responsiveness', 'salient' ),
	  			'param_name' => 'offset',
	  			'group' => esc_html__( 'Responsive Options', 'salient' ),
	  			'description' => esc_html__( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'salient' ),
	  		),
	  	),
	  	'js_view' => 'VcColumnView',
	  ));


	  vc_map( array(
	  	'name' => esc_html__( 'Inner Row', 'salient' ),
	  	//Inner Row
	    'base' => 'vc_row_inner',
	  	'content_element' => false,
	  	'is_container' => true,
	  	'icon' => 'icon-wpb-row',
	  	'weight' => 1000,
	  	'show_settings_on_create' => false,
	  	'description' => esc_html__( 'Place content elements inside the inner row', 'salient' ),
	  	'params' => array(
	  		array(
	  			'type' => 'el_id',
	  			'heading' => esc_html__( 'Row ID', 'salient' ),
	  			'param_name' => 'el_id',
	  			'description' => sprintf( esc_html__( 'Enter optional row ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)', 'salient' ), '<a target="_blank" href="//www.w3schools.com/tags/att_global_id.asp">' . esc_html__( 'link', 'salient' ) . '</a>' ),
	  		),
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Equal height', 'salient' ),
	  			'param_name' => 'equal_height',
	  			'description' => esc_html__( 'If checked columns will be set to equal height.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Content position', 'salient' ),
	  			'param_name' => 'content_placement',
	  			'value' => array(
	  				esc_html__( 'Default', 'salient' ) => '',
	  				esc_html__( 'Top', 'salient' ) => 'top',
	  				esc_html__( 'Middle', 'salient' ) => 'middle',
	  				esc_html__( 'Bottom', 'salient' ) => 'bottom',
	  			),
	  			'description' => esc_html__( 'Select content position within columns.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Columns gap', 'salient' ),
	  			'param_name' => 'gap',
	  			'value' => array(
	  				'0px' => '0',
	  				'1px' => '1',
	  				'2px' => '2',
	  				'3px' => '3',
	  				'4px' => '4',
	  				'5px' => '5',
	  				'10px' => '10',
	  				'15px' => '15',
	  				'20px' => '20',
	  				'25px' => '25',
	  				'30px' => '30',
	  				'35px' => '35',
	  			),
	  			'std' => '0',
	  			'description' => esc_html__( 'Select gap between columns in row.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Disable row', 'salient' ),
	  			'param_name' => 'disable_element',
	  			// Inner param name.
	  			'description' => esc_html__( 'If checked the row won\'t be visible on the public side of your website. You can switch it back any time.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Extra class name', 'salient' ),
	  			'param_name' => 'el_class',
	  			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'css_editor',
	  			'heading' => esc_html__( 'CSS box', 'salient' ),
	  			'param_name' => 'css',
	  			'group' => esc_html__( 'Design Options', 'salient' ),
	  		),
	  	),
	  	'js_view' => 'VcRowView',
	  ));


	  vc_map( array(
	  	'name' => esc_html__( 'Column', 'salient' ),
	  	'icon' => 'icon-wpb-row',
	    'base' => 'vc_column',
	  	'is_container' => true,
	  	'content_element' => false,
	  	'description' => esc_html__( 'Place content elements inside the column', 'salient' ),
	  	'params' => array(
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Use video background?', 'salient' ),
	  			'param_name' => 'video_bg',
	  			'description' => esc_html__( 'If checked, video will be used as row background.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'YouTube link', 'salient' ),
	  			'param_name' => 'video_bg_url',
	  			'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k',
	  			// default video url
	  			'description' => esc_html__( 'Add YouTube link.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Parallax', 'salient' ),
	  			'param_name' => 'video_bg_parallax',
	  			'value' => array(
	  				esc_html__( 'None', 'salient' ) => '',
	  				esc_html__( 'Simple', 'salient' ) => 'content-moving',
	  				esc_html__( 'With fade', 'salient' ) => 'content-moving-fade',
	  			),
	  			'description' => esc_html__( 'Add parallax type background for row.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Parallax', 'salient' ),
	  			'param_name' => 'parallax',
	  			'value' => array(
	  				esc_html__( 'None', 'salient' ) => '',
	  				esc_html__( 'Simple', 'salient' ) => 'content-moving',
	  				esc_html__( 'With fade', 'salient' ) => 'content-moving-fade',
	  			),
	  			'description' => esc_html__( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg',
	  				'is_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'attach_image',
	  			'heading' => esc_html__( 'Image', 'salient' ),
	  			'param_name' => 'parallax_image',
	  			'value' => '',
	  			'description' => esc_html__( 'Select image from media library.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'parallax',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Parallax speed', 'salient' ),
	  			'param_name' => 'parallax_speed_video',
	  			'value' => '1.5',
	  			'description' => esc_html__( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg_parallax',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Parallax speed', 'salient' ),
	  			'param_name' => 'parallax_speed_bg',
	  			'value' => '1.5',
	  			'description' => esc_html__( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'parallax',
	  				'not_empty' => true,
	  			),
	  		),
	  		vc_map_add_css_animation( false ),
	  		array(
	  			'type' => 'el_id',
	  			'heading' => esc_html__( 'Element ID', 'salient' ),
	  			'param_name' => 'el_id',
	  			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'salient' ), '//www.w3schools.com/tags/att_global_id.asp' ),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Extra class name', 'salient' ),
	  			'param_name' => 'el_class',
	  			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'css_editor',
	  			'heading' => esc_html__( 'CSS box', 'salient' ),
	  			'param_name' => 'css',
	  			'group' => esc_html__( 'Design Options', 'salient' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Width', 'salient' ),
	  			'param_name' => 'width',
	  			'value' => array(
	  				esc_html__( '1 column - 1/12', 'salient' ) => '1/12',
	  				esc_html__( '2 columns - 1/6', 'salient' ) => '1/6',
	  				esc_html__( '3 columns - 1/4', 'salient' ) => '1/4',
	  				esc_html__( '4 columns - 1/3', 'salient' ) => '1/3',
	  				esc_html__( '5 columns - 5/12', 'salient' ) => '5/12',
	  				esc_html__( '6 columns - 1/2', 'salient' ) => '1/2',
	  				esc_html__( '7 columns - 7/12', 'salient' ) => '7/12',
	  				esc_html__( '8 columns - 2/3', 'salient' ) => '2/3',
	  				esc_html__( '9 columns - 3/4', 'salient' ) => '3/4',
	  				esc_html__( '10 columns - 5/6', 'salient' ) => '5/6',
	  				esc_html__( '11 columns - 11/12', 'salient' ) => '11/12',
	  				esc_html__( '12 columns - 1/1', 'salient' ) => '1/1',
	  				esc_html__( '20% - 1/5', 'salient' ) => '1/5',
	  				esc_html__( '40% - 2/5', 'salient' ) => '2/5',
	  				esc_html__( '60% - 3/5', 'salient' ) => '3/5',
	  				esc_html__( '80% - 4/5', 'salient' ) => '4/5',
	  			),
	  			'group' => esc_html__( 'Responsive Options', 'salient' ),
	  			'description' => esc_html__( 'Select column width.', 'salient' ),
	  			'std' => '1/1',
	  		),
	  		array(
	  			'type' => 'column_offset',
	  			'heading' => esc_html__( 'Responsiveness', 'salient' ),
	  			'param_name' => 'offset',
	  			'group' => esc_html__( 'Responsive Options', 'salient' ),
	  			'description' => esc_html__( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'salient' ),
	  		),
	  	),
	  	'js_view' => 'VcColumnView',
	  ));

	  vc_map( array(
	  	'name' => esc_html__( 'Row', 'salient' ),
	    'base' => 'vc_row',
	  	'is_container' => true,
	  	'icon' => 'icon-wpb-row',
	  	'show_settings_on_create' => false,
	  	'category' => esc_html__( 'Content', 'salient' ),
	  	'class' => 'vc_main-sortable-element',
	  	'description' => esc_html__( 'Place content elements inside the row', 'salient' ),
	  	'params' => array(
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Row stretch', 'salient' ),
	  			'param_name' => 'full_width',
	  			'value' => array(
	  				esc_html__( 'Default', 'salient' ) => '',
	  				esc_html__( 'Stretch row', 'salient' ) => 'stretch_row',
	  				esc_html__( 'Stretch row and content', 'salient' ) => 'stretch_row_content',
	  				esc_html__( 'Stretch row and content (no paddings)', 'salient' ) => 'stretch_row_content_no_spaces',
	  			),
	  			'description' => esc_html__( 'Select stretching options for row and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).', 'salient' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Columns gap', 'salient' ),
	  			'param_name' => 'gap',
	  			'value' => array(
	  				'0px' => '0',
	  				'1px' => '1',
	  				'2px' => '2',
	  				'3px' => '3',
	  				'4px' => '4',
	  				'5px' => '5',
	  				'10px' => '10',
	  				'15px' => '15',
	  				'20px' => '20',
	  				'25px' => '25',
	  				'30px' => '30',
	  				'35px' => '35',
	  			),
	  			'std' => '0',
	  			'description' => esc_html__( 'Select gap between columns in row.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Full height row?', 'salient' ),
	  			'param_name' => 'full_height',
	  			'description' => esc_html__( 'If checked row will be set to full height.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Columns position', 'salient' ),
	  			'param_name' => 'columns_placement',
	  			'value' => array(
	  				esc_html__( 'Middle', 'salient' ) => 'middle',
	  				esc_html__( 'Top', 'salient' ) => 'top',
	  				esc_html__( 'Bottom', 'salient' ) => 'bottom',
	  				esc_html__( 'Stretch', 'salient' ) => 'stretch',
	  			),
	  			'description' => esc_html__( 'Select columns position within row.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'full_height',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Equal height', 'salient' ),
	  			'param_name' => 'equal_height',
	  			'description' => esc_html__( 'If checked columns will be set to equal height.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Content position', 'salient' ),
	  			'param_name' => 'content_placement',
	  			'value' => array(
	  				esc_html__( 'Default', 'salient' ) => '',
	  				esc_html__( 'Top', 'salient' ) => 'top',
	  				esc_html__( 'Middle', 'salient' ) => 'middle',
	  				esc_html__( 'Bottom', 'salient' ) => 'bottom',
	  			),
	  			'description' => esc_html__( 'Select content position within columns.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Use video background?', 'salient' ),
	  			'param_name' => 'video_bg',
	  			'description' => esc_html__( 'If checked, video will be used as row background.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'YouTube link', 'salient' ),
	  			'param_name' => 'video_bg_url',
	  			'value' => 'https://www.youtube.com/watch?v=lMJXxhRFO1k',
	  			// default video url
	  			'description' => esc_html__( 'Add YouTube link.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Parallax', 'salient' ),
	  			'param_name' => 'video_bg_parallax',
	  			'value' => array(
	  				esc_html__( 'None', 'salient' ) => '',
	  				esc_html__( 'Simple', 'salient' ) => 'content-moving',
	  				esc_html__( 'With fade', 'salient' ) => 'content-moving-fade',
	  			),
	  			'description' => esc_html__( 'Add parallax type background for row.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'dropdown',
	  			'heading' => esc_html__( 'Parallax', 'salient' ),
	  			'param_name' => 'parallax',
	  			'value' => array(
	  				esc_html__( 'None', 'salient' ) => '',
	  				esc_html__( 'Simple', 'salient' ) => 'content-moving',
	  				esc_html__( 'With fade', 'salient' ) => 'content-moving-fade',
	  			),
	  			'description' => esc_html__( 'Add parallax type background for row (Note: If no image is specified, parallax will use background image from Design Options).', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg',
	  				'is_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'attach_image',
	  			'heading' => esc_html__( 'Image', 'salient' ),
	  			'param_name' => 'parallax_image',
	  			'value' => '',
	  			'description' => esc_html__( 'Select image from media library.', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'parallax',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Parallax speed', 'salient' ),
	  			'param_name' => 'parallax_speed_video',
	  			'value' => '1.5',
	  			'description' => esc_html__( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'video_bg_parallax',
	  				'not_empty' => true,
	  			),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Parallax speed', 'salient' ),
	  			'param_name' => 'parallax_speed_bg',
	  			'value' => '1.5',
	  			'description' => esc_html__( 'Enter parallax speed ratio (Note: Default value is 1.5, min value is 1)', 'salient' ),
	  			'dependency' => array(
	  				'element' => 'parallax',
	  				'not_empty' => true,
	  			),
	  		),
	  		vc_map_add_css_animation( false ),
	  		array(
	  			'type' => 'el_id',
	  			'heading' => esc_html__( 'Row ID', 'salient' ),
	  			'param_name' => 'el_id',
	  			'description' => sprintf( esc_html__( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'salient' ), '//www.w3schools.com/tags/att_global_id.asp' ),
	  		),
	  		array(
	  			'type' => 'checkbox',
	  			'heading' => esc_html__( 'Disable row', 'salient' ),
	  			'param_name' => 'disable_element',
	  			// Inner param name.
	  			'description' => esc_html__( 'If checked the row won\'t be visible on the public side of your website. You can switch it back any time.', 'salient' ),
	  			'value' => array( esc_html__( 'Yes', 'salient' ) => 'yes' ),
	  		),
	  		array(
	  			'type' => 'textfield',
	  			'heading' => esc_html__( 'Extra class name', 'salient' ),
	  			'param_name' => 'el_class',
	  			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'salient' ),
	  		),
	  		array(
	  			'type' => 'css_editor',
	  			'heading' => esc_html__( 'CSS box', 'salient' ),
	  			'param_name' => 'css',
	  			'group' => esc_html__( 'Design Options', 'salient' ),
	  		),
	  	),
	  	'js_view' => 'VcRowView',
	  ));

	}
	


}

elseif ( class_exists( 'WPBakeryVisualComposerAbstract' ) && ! class_exists( 'Salient_Core' ) ) {

	function nectar_font_awesome() {
		global $nectar_get_template_directory_uri;
		wp_enqueue_style( 'font-awesome', $nectar_get_template_directory_uri . '/css/font-awesome.min.css' );
	}

	if ( ! is_admin() ) {
		add_action( 'init', 'nectar_font_awesome', 99 );
	}
}
