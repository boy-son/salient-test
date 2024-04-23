<?php
/**
 * Menu Item Custom Fields
 * @author Dzikri Aziz <kvcrvt@gmail.com>
 *
 * License: GPLv2
 */
if ( ! class_exists( 'Menu_Item_Custom_Fields' ) ) :
	/**
	* Menu Item Custom Fields Loader
	*/
	class Menu_Item_Custom_Fields {
		/**
		* Add filter
		*
		* @wp_hook action wp_loaded
		*/
		public static function load() {
			add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, '_filter_walker' ), 99 );
		}
		
		public static function _filter_walker( $walker ) {
			$walker = 'Menu_Item_Custom_Fields_Walker';
			if ( ! class_exists( $walker ) ) {
				require_once get_parent_theme_file_path( '/nectar/assets/functions/wp-menu-custom-items/walker-nav-menu-edit.php' );
			}
			return $walker;
		}
		
	}
	
	global $wp_version;
	 if (version_compare(preg_replace("/[^0-9\.]/","",$wp_version), '5.4', '<') ) {
		add_action( 'wp_loaded', array( 'Menu_Item_Custom_Fields', 'load' ), 9 );
	}
	
endif; // class_exists( 'Menu_Item_Custom_Fields' )