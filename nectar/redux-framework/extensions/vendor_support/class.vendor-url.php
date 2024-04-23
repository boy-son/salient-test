<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Redux_VendorURL' ) ) {
    class Redux_VendorURL {
        static public $url;
        static public $dir;

        public static function get_url( $handle ) {
            $min    = Redux_Functions::isMin();
            $ace    = self::$dir . 'vendor/ace_editor/ace.js';
            $s2js   = self::$dir . 'vendor/select2/select2' . $min . '.js';
            $s2css  = self::$dir . 'vendor/select2/select2.css';

            if ( $handle == 'ace-editor-js' && file_exists( $ace ) ) {
                return self::$url . 'vendor/ace_editor/ace.js';
            } elseif ( $handle == 'select2-js' && file_exists( $s2js ) ) {
                return self::$url . 'vendor/select2/select2.js';
            } elseif ( $handle == 'select2-css' && file_exists( $s2css ) ) {
                return self::$url . 'vendor/select2/select2.css';
            }
        }
    }
}

/* nectar addition for v4 plugin compatibility  */
if ( ! class_exists( 'Redux_Vendor_URL' ) ) {

	/**
	 * Redux_Vendor_URL class.
	 */
	class Redux_Vendor_URL {

      
		public static $url;
		public static $dir;

		public static function get_url( string $handle ) {
            
            // Manually setting these up since thy won't be passed from ReduxFramework_extension_vendor_support construct
            $current_directory = get_parent_theme_file_path('/nectar/redux-framework/extensions/vendor_support');
            self::$dir = trailingslashit( str_replace( '\\', '/', $current_directory ) );
            self::$url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', self::$dir ) );

			$ace = self::$dir . 'vendor/ace_editor/ace.js';

			if ( 'ace-editor' === $handle && file_exists( $ace ) ) {
				return self::$url . 'vendor/ace_editor/ace.js';
			} else {
                // don't return null to prevent errors
               return '';
            }
		}
	}
}