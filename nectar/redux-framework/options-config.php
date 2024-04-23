<?php
    /**
     * Salient theme options configuration.
     *
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    $opt_name = "salient_redux";
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );
    $theme    = wp_get_theme();

    $theme_menu_icon = null;
    if( floatval(get_bloginfo('version')) >= "3.8" ) {
        $current_color = get_user_option( 'admin_color' );
        if( $current_color == 'light' ) {
            $theme_menu_icon = NECTAR_FRAMEWORK_DIRECTORY . 'assets/img/icons/salient-grey.svg';
        } else {
            $theme_menu_icon = NECTAR_FRAMEWORK_DIRECTORY . 'assets/img/icons/salient.svg';
        }
    }


    // Allow child theme to disable AJAX saving.
    if( !function_exists('salient_set_theme_options_ajax') ) {
      function salient_set_theme_options_ajax() {
        return true;
      }
    }

    $salient_redux_ajax_saving      = salient_set_theme_options_ajax();
    $salient_redux_ajax_saving_bool = ( false === $salient_redux_ajax_saving ) ? false : true;


    $args = array(
        'opt_name'             => $opt_name,
        'disable_tracking' => true,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Salient', 'salient' ),
        'page_title'           => esc_html__( 'Salient Options', 'salient' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        'ajax_save'            => $salient_redux_ajax_saving_bool,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => false,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => 54,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => $theme_menu_icon,
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => '',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        'footer_credit'     => ' ',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => false,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );


    // SOCIAL ICONS
    $args['share_icons'][] = array(
        'url'   => '//www.facebook.com/ThemeNectar-488077244574702/?fref=ts',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = '';
    } else {
         $args['intro_text'] = '';
    }

    // Add content after the form.
    $args['footer_text'] = '';

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /* Extension Loader */
    if(!function_exists('redux_register_custom_extension_loader')) :

    function redux_register_custom_extension_loader($ReduxFramework) {

        $path = get_parent_theme_file_path('/nectar/redux-framework/extensions/');
        $folders = scandir( $path, 1 );

        foreach($folders as $folder) {
            if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
                continue;
            }
            $extension_class = 'ReduxFramework_Extension_' . $folder;
            if( !class_exists( $extension_class ) && 'wbc_importer' !== $folder ) {
                // In case you wanted override your override, hah.
                $class_file = $path . $folder . '/extension_' . $folder . '.php';
                $class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
                if( $class_file ) {
                    require_once( $class_file );
                    $extension = new $extension_class( $ReduxFramework );
                }
            }
        }
    }


    add_action("redux/extensions/".$opt_name ."/before", 'redux_register_custom_extension_loader', 0);
    endif;


    // Write dynamic css when saving.
    add_action('redux/options/salient_redux/saved', 'nectar_generate_options_css');
    



    // Allow child theme to disable sections from loading outside theme options
    if( !function_exists('salient_load_theme_options_globally') ) {
      function salient_load_theme_options_globally() {
        return true;
      }
    }

    $salient_globally_load_redux        = salient_load_theme_options_globally();
    $salient_globally_load_redux_bool   = ( false === $salient_globally_load_redux ) ? false : true;
    $salient_on_theme_options_page_bool = ( is_admin() && isset($_GET['page']) && $_GET['page'] === sanitize_html_class($theme->get( 'Name' )) ) ? true : false;

    if ( false === $salient_globally_load_redux_bool && is_admin() ) {

      if( true === $salient_on_theme_options_page_bool ) {

      } else {
        return;
      }

    }
    

    if( !function_exists('salient_redux_options_js') ) {
      function salient_redux_options_js() {
        wp_enqueue_script('salient-redux-options', get_template_directory_uri() . '/nectar/redux-framework/salient-redux.js', array( 'jquery' ), '15.1', true);
      }
    }
 
    if( $salient_on_theme_options_page_bool ) {

      // Enqueue admin js.
      add_action('admin_enqueue_scripts','salient_redux_options_js');

      // Write dynamic css when importing.
      add_action('redux/options/salient_redux/import', 'nectar_generate_options_css');
    }


    /*
     *
     * START SECTIONS
     *
     */


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'General Settings', 'salient' ),
       'id'               => 'general-settings',
       'customizer_width' => '450px',
       'desc'             => esc_html__('Welcome to the Salient options panel! You can switch between option groups by using the left-hand tabs.', 'salient'),
       'fields'           => array(

       )
     ) );

     $border_border_sizes = array();
     for( $i = 1; $i<100; $i++ ) {
       $border_border_sizes[$i] = $i;
     }

     $legacy_wp_favicon = array(
       'id' => 'favicon',
       'type' => 'media',
       'title' => esc_html__('Favicon Upload', 'salient'),
       'subtitle' => esc_html__('Upload a 16px x 16px .png or .gif image that will be your favicon.', 'salient'),
       'desc' => ''
     );

     $options              = get_nectar_theme_options();
     $using_legacy_favicon = (!empty($options['favicon']) && !empty($options['favicon']['url'])) ? true : false;

     if( floatval(get_bloginfo('version')) >= "4.3" && !$using_legacy_favicon ) {
       $legacy_wp_favicon = array(
         'id'    => 'info_success',
         'type'  => 'info',
         'style' => 'success',
         'title' => esc_html__('Favicon', 'salient'),
         'icon'  => 'el-icon-info-sign',
         'desc'  => esc_html__( 'As of WP 4.3, the favicon setting is now available in the default WordPress customizer (Appearance > Customize).', 'salient')
       );
     }

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Styling', 'salient' ),
       'id'               => 'general-settings-styling',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'theme-skin',
           'type' => 'select',
           'title' => esc_html__('Theme Skin', 'salient'),
           'subtitle' => esc_html__('This will alter the overall styling of various theme elements', 'salient'),
           'options' => array(
             "original" => esc_html__('Original', 'salient'),
             "ascend" => esc_html__('Ascend', 'salient'),
             "material" => esc_html__('Material', 'salient')
           ),
           'default' => 'material'
         ),

         array(
           'id' => 'button-styling',
           'type' => 'select',
           'title' => esc_html__('Button Styling', 'salient'),
           'subtitle' => esc_html__('This will effect the overall styling of buttons', 'salient'),
           'options' => array(
             "default" => esc_html__("Default", "salient"),
             "slightly_rounded" => esc_html__("Slightly Rounded", "salient"),
             "slightly_rounded_shadow" => esc_html__("Slightly Rounded W/ Shadow", "salient"),
             "rounded" => esc_html__("Rounded", "salient"),
             "rounded_shadow" => esc_html__("Rounded W/ Shadow", "salient")
           ),
           'default' => 'slightly_rounded_shadow'
         ),
         array(
           'id' => 'button-styling-roundness',
           'type'      => 'slider',
           'title'     => esc_html__('Button Roundness', 'salient'),
           'desc'      => '',
           "default"   => 4,
           "min"       => 1,
           "step"      => 1,
           "max"       => 20,
           'subtitle' => esc_html__('Fine-tune the rounded edges of your buttons.', 'salient'),
          'required' => array( array( 'button-styling', '!=', 'default' ), array( 'button-styling', '!=', 'rounded') , array( 'button-styling', '!=', 'rounded_shadow') ),
           'display_value' => 'label',
         ),
         array(
           'id' => 'column-spacing',
           'type' => 'select',
           'title' => esc_html__('Column Spacing', 'salient'),
           'subtitle' => esc_html__('Choose the global spacing between page builder columns.', 'salient'),
           'options' => array(
             "default" => esc_html__('Default', 'salient'),
             "30px" => esc_html__('30px', 'salient'),
             "40px" => esc_html__('40px', 'salient'),
             "50px" => esc_html__('50px', 'salient'),
             "60px" => esc_html__('60px', 'salient'),
             "70px" => esc_html__('70px', 'salient'),
           ),
           'default' => 'default'
         ),

         array(
           'id' => 'overall-bg-color',
           'type' => 'color',
           'title' => esc_html__('Overall Background Color', 'salient'),
           'subtitle' => esc_html__('Default is #ffffff', 'salient'),
           'transparent' => false,
           'desc' => '',
           'default' => '#ffffff'
         ),

         array(
           'id' => 'overall-font-color',
           'type' => 'color',
           'title' => esc_html__('Overall Font Color', 'salient'),
           'subtitle' => esc_html__('Default is #676767', 'salient'),
           'transparent' => false,
           'desc' => '',
           'default' => ''
         ),
         array(
           'id' => 'animated-underline-type',
           'type' => 'select',
           'desc' => '',
           'title' => esc_html__('Animated Underline Type', 'salient'),
           'subtitle' => esc_html__('Various elements in Salient display an animated underline when hovering over. This option allows you to globally fine-tune the styling of that line.', 'salient'),
           'options' => array(
             'default' => esc_html__('Default', 'salient'),
             'ltr' => esc_html__('Left to Right Simple', 'salient'),
             'ltr-fancy' => esc_html__('Left to Right Fancy', 'salient'),
           ),
           'default' => 'default'
         ),
         array(
           'id' => 'animated-underline-thickness',
           'type'      => 'slider',
           'title'     => esc_html__('Animated Underline Thickness', 'salient'),
           'desc'      => '',
           "default"   => 2,
           "min"       => 1,
           "step"      => 1,
           "max"       => 4,
           'display_value' => 'label',
         ),
         array(
          'id' => 'general-link-style',
          'type' => 'select',
          'desc' => '',
          'title' => esc_html__('General Link Style', 'salient'),
          'subtitle' => esc_html__('This controls the styling of standard anchor links.', 'salient'),
          'options' => array(
            'default' => esc_html__('Inherit Accent Color', 'salient'),
            'basic-underline' => esc_html__('Basic Underline', 'salient')
          ),
          'default' => 'default'
        ),
         array(
           'id' => 'body-border',
           'type' => 'switch',
           'title' => esc_html__('Body Border (Passepartout)', 'salient'),
           'subtitle' => esc_html__('This will add a border around the edges of the screen', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'body-border-color',
           'type' => 'color',
           'required' => array( 'body-border', '=', '1' ),
           'title' => esc_html__('Body Border Color', 'salient'),
           'subtitle' => esc_html__('Default is #ffffff', 'salient'),
           'transparent' => false,
           'desc' => '',
           'default' => '#ffffff'
         ),
         array(
           'id' => 'body-border-size',
           'type' => 'select',
           'required' => array( 'body-border', '=', '1' ),
           'title' => esc_html__('Body Border Size', 'salient'),
           'subtitle' => esc_html__('Please choose your desired size in px here. Default is 20px.', 'salient'),
           'options' => $border_border_sizes,
           'default' => '20px'
         ),
         $legacy_wp_favicon
       )
     ) );

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Functionality', 'salient' ),
       'id'               => 'general-settings-functionality',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'one-page-scrolling',
           'type' => 'switch',
           'title' => esc_html__('One Page Scroll Support (Animated Anchor Links)', 'salient'),
           'subtitle' => esc_html__('Toggle whether or not to enable one page scroll support', 'salient'),
           'desc' => '',
           'default' => '1'
         ),
         array(
           'id' => 'responsive',
           'type' => 'switch',
           'title' => esc_html__('Enable Responsive Design', 'salient'),
           'subtitle' => esc_html__('This adjusts the layout of your website depending on the screen size/device.', 'salient'),
           'desc' => '',
           'next_to_hide' => '1',
           'default' => '1'
         ),
         array(
           'id' => 'ext_responsive',
           'type' => 'switch',
           'required' => array( 'responsive', '=', '1' ),
           'title' => esc_html__('Extended Responsive Design', 'salient'),
           'subtitle' => esc_html__('This will enhance the way the theme responds when viewing on screens larger than 1000px & increase the max width.', 'salient'),
           'desc' => '',
           'default' => '1'
         ),
         array(
          'id' => 'meta_viewport',
          'type' => 'select',
          'title' => esc_html__('Meta Viewport Functionality', 'salient'),
          'subtitle' => esc_html__('Determines whether browser zooming/scaling is enabled or disabled. Enabling this will improve accessibility for users with low vision.', 'salient'),
          'options' => array(
            "not_scalable" => "Not Scalable",
            "scalable" => "Scalable",
          ),
          'default' => 'not_scalable'
        ),
         array(
           'id'        => 'max_container_width',
           'type'      => 'slider',
           'required' => array( 'ext_responsive', '=', '1' ),
           'title'     => esc_html__('Max Website Container Width', 'salient'),
           'subtitle'  => esc_html__('When using the extended responsive design, your container will scale to a maximum width of 1425px, use this option if you\'d like to increase that value.', 'salient'),
           'desc'      => '',
           "default"   => 1425,
           "min"       => 1425,
           "step"      => 1,
           "max"       => 2400,
           'display_value' => 'text'
         ),
         array(
           'id'        => 'ext_responsive_padding',
           'type'      => 'slider',
           'required' => array( 'ext_responsive', '=', '1' ),
           'title'     => esc_html__('Container Left/Right Padding', 'salient'),
           'subtitle'  => esc_html__('When using the extended responsive design, the main content container will have 90px of padding set on left and right, use this option if you\'d like to modify that.', 'salient'),
           'desc'      => '',
           "default"   => 90,
           "min"       => 20,
           "step"      => 5,
           "max"       => 120,
           'display_value' => 'text'
         ),
         array(
           'id' => 'lightbox_script',
           'type' => 'select',
           'title' => esc_html__('Theme Lightbox', 'salient'),
           'subtitle' => esc_html__('Please choose your desired lightbox script here', 'salient'),
           'options' => array(
             "magnific" => "Magnific",
             "fancybox" => "fancyBox3",
             "none" => "None"
           ),
           'default' => 'fancybox'
         ),
         array(
           'id' => 'default-lightbox',
           'type' => 'switch',
           'title' => esc_html__('Auto Lightbox Image Links', 'salient'),
           'subtitle' => esc_html__('This will allow all image links to open in a lightbox - including the images links within standard WordPress galleries.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'column_animation_mobile',
           'type' => 'select',
           'title' => esc_html__('Page Builder Element Animations On Mobile Devices', 'salient'),
           'subtitle' => esc_html__('Determine whether or not to run page builder element animations on mobile devices.', 'salient'),
           'options' => array(
             'disable' => 'Disable',
             'enable' => 'Enable',
           ),
           'default' => 'disable'
         ),
         array(
           'id' => 'column_animation_easing',
           'type' => 'select',
           'title' => esc_html__('Column/Image Animation Easing', 'salient'),
           'subtitle' => esc_html__('This is the easing that will be used on all animated column/images you set', 'salient'),
           'options' => array(
             'linear'=>'linear',
             'swing'=>'swing',
             'easeInQuad'=>'easeInQuad',
             'easeOutQuad' => 'easeOutQuad',
             'easeInOutQuad'=>'easeInOutQuad',
             'easeInCubic'=>'easeInCubic',
             'easeOutCubic'=>'easeOutCubic',
             'easeInOutCubic'=>'easeInOutCubic',
             'easeInQuart'=>'easeInQuart',
             'easeOutQuart'=>'easeOutQuart',
             'easeInOutQuart'=>'easeInOutQuart',
             'easeInQuint'=>'easeInQuint',
             'easeOutQuint'=>'easeOutQuint',
             'easeInOutQuint'=>'easeInOutQuint',
             'easeInExpo'=>'easeInExpo',
             'easeOutExpo'=>'easeOutExpo',
             'easeInOutExpo'=>'easeInOutExpo',
             'easeInSine'=>'easeInSine',
             'easeOutSine'=>'easeOutSine',
             'easeInOutSine'=>'easeInOutSine',
             'easeInCirc'=>'easeInCirc',
             'easeOutCirc'=>'easeOutCirc',
             'easeInOutCirc'=>'easeInOutCirc',
             'easeInElastic'=>'easeInElastic',
             'easeOutElastic'=>'easeOutElastic',
             'easeInOutElastic'=>'easeInOutElastic',
             'easeInBack'=>'easeInBack',
             'easeOutBack'=>'easeOutBack',
             'easeInOutBack'=>'easeInOutBack',
             'easeInBounce'=>'easeInBounce',
             'easeOutBounce'=>'easeOutBounce',
             'easeInOutBounce'=>'easeInOutBounce'
           ),
           'default' => 'easeOutCubic'
         ),
         array(
           'id' => 'column_animation_timing',
           'type' => 'text',
           'title' => esc_html__('Column/Image Animation Timing', 'salient'),
           'subtitle' => esc_html__('Enter the time in milliseconds e.g. "400" - default is "650"', 'salient'),
           'desc' => '',
           'default' => '750'
         ),
         array(
           'id' => 'disable-mobile-parallax',
           'type' => 'switch',
           'title' => esc_html__('Disable Parallax Backgrounds On Mobile Devices', 'salient'),
           'subtitle' => esc_html__('This will remove the parallax scrolling effect from your row backgrounds/page headers that use the option.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'disable-mobile-video-bgs',
           'type' => 'switch',
           'title' => esc_html__('Disable Video Backgrounds On Mobile Devices', 'salient'),
           'subtitle' => esc_html__('This will remove all self hosted video backgrounds from your rows/page headers that use them on mobile devices and cause the supplied preview image to be shown instead.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
          'id' => 'back-to-top',
          'type' => 'switch',
          'title' => esc_html__('Back To Top Button', 'salient'),
          'subtitle' => esc_html__('Toggle whether or not to enable a back to top button on your pages.', 'salient'),
          'desc' => '',
          'default' => '1'
        ),
        array(
          'id' => 'back-to-top-mobile',
          'type' => 'switch',
          'title' => esc_html__('Keep Back To Top Button On Mobile', 'salient'),
          'subtitle' => esc_html__('Toggle whether or not to show or hide the back to top button when viewing on a mobile device.', 'salient'),
          'desc' => '',
          'required' => array( 'back-to-top', '=', '1' ),
          'default' => '0'
        ),
        array(
          'id' => 'wpbakery-ai',
          'type' => 'switch',
          'title' => esc_html__('Enable WPBakery AI Functionality', 'salient'),
          'subtitle' => esc_html__('Enables various text fields in the Salient WPBakery page builder to utilize AI for generation. Requires a separate, active support subscription to the core WPBakery page builder plugin.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
       )
     ) );

     $css_js_permission_cap = ( is_multisite() && apply_filters('nectar_multisite_options_allow_admin_edit_code', true) ) ? 'manage_sites' : 'manage_options';

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'CSS/Script Related', 'salient' ),
       'id'               => 'general-settings-extra',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'force-dynamic-css-inline',
           'type' => 'switch',
           'title' => esc_html__('Force Dynamic CSS to Inline In Head', 'salient'),
           'subtitle' => esc_html__('This prevents the theme dynamic css from being written/enqueued in a stylesheet and instead will cause it to output directly inline within the HTML head. This option is useful for preventing caching of the styles if you\'re still developing and using minification/caching plugins.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'google-maps-api-key',
           'type' => 'text',
           'title' => esc_html__('Google Maps API Key', 'salient'),
           'subtitle' => esc_html__('In order to use Google maps, you need to generate an API key and enter it here. Please see the', 'salient') . ' <a rel="nofollow" href="//developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key">' . esc_html__('official documentation', 'salient') . '</a> ' . esc_html__('for more information', 'salient'),
           'desc' => '',
           'default' => ''
         ),
         array(
           'id'=>'custom-css',
           'type' => 'ace_editor',
           'title' => esc_html__('Custom CSS Code', 'salient'),
           'subtitle' => esc_html__('If you have any custom CSS you would like added to the site, please enter it here.', 'salient'),
           'mode' => 'css',
           'theme' => 'monokai',
           'hint' => array('content' => esc_html__('Note - if you\'ve pasted CSS in here from an external source, ensure no accidental', 'salient'). ' <b>pre</b> ' . esc_html__('tags pasted in with the snippet. If unintentional tags like that are present, it will prevent the css from parsing correctly.', 'salient'), 'title' => ''),
           'desc' => '',
           'permissions' => $css_js_permission_cap,
           'options' => array('minLines' => 20)
         ),
         array(
           'id' => 'google-analytics',
           'type' => 'textarea',
           'title' => esc_html__('Custom JS Code (Head)', 'salient'),
           'subtitle' => esc_html__('Please enter in any custom javascript you wish to add to the head of your pages. Requires opening and closing script tags.', 'salient'),
           'permissions' => $css_js_permission_cap,
           'desc' => ''
         ),
         array(
          'id' => 'custom-js-after-body',
          'type' => 'textarea',
          'title' => esc_html__('Custom JS Code (After Body Open)', 'salient'),
          'subtitle' => esc_html__('Useful for third party integrations which require code to be placed after the opening <body> tag, such as Google Tag Manager. Requires opening and closing script tags.', 'salient'),
          'permissions' => $css_js_permission_cap,
          'desc' => ''
        )
       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Performance', 'salient' ),
       'id'               => 'general-settings-performance',
       'desc' => esc_html__('Performance options related to asset reduction/loading optimization. Options are ordered from the largest to smallest impact on overall performance.', 'salient'),
       'subsection'       => true,
       'fields'           => array(
        array(
          'id' => 'delay-js-execution',
          'type' => 'switch',
          'class' => 'salient-new-badge',
          'title' => esc_html__('Delay Javascript Execution', 'salient'),
          'subtitle' => esc_html__('Prevents theme javascript from running until the user makes an interaction such as scrolling, tapping etc.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
        array(
          'id' => 'delay-js-execution-devices',
          'type' => 'select',
          'title' => esc_html__('Devices to Activate "Delay Javascript" Logic', 'salient'),
          'subtitle' => esc_html__('If you are using a performance plugin that caches your pages, this will need to be set to "All Devices" in order to take effect.', 'salient'),
          'required' => array( 'delay-js-execution', '=', '1' ),
          'options' => array(
            "all" => esc_html__("All Devices", "salient"),
            "mobile" => esc_html__("Mobile", "salient"),
          ),
          'default' => 'all'
        ),
         array(
           'id' => 'global_lazy_load_images',
           'type' => 'switch',
           'title' => esc_html__('Lazy Load Page Builder Element Images', 'salient'),
           'subtitle' => esc_html__('Enabling this will globally activate lazy loading for theme elements which support it.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
          'id' => 'defer-javascript',
          'type' => 'switch',
          'title' => esc_html__('Move jQuery to Footer', 'salient'),
          'subtitle' => esc_html__('Attempts to move jQuery to the footer to make it non render-blocking. Note that this can break third party plugins/scripts which require JavaScript to be loaded in the head. Additionally certain WordPress plugins will still force jQuery to load in the head, such as WooCommerce.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
         
         array(
           'id' => 'typography_font_swap',
           'type' => 'switch',
           'title' => esc_html__('Font Display Swap', 'salient'),
           'subtitle' => esc_html__('This is a font performance option which will your allow text to display in a default font before Google fonts have loaded. Enabling this will correct the page speed recommendation "Ensure text remains visible during webfont load".', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
        
         array(
           'id' => 'rm-block-editor-css',
           'type' => 'switch',
           'title' => esc_html__('Remove Block Editor (Gutenberg) CSS', 'salient'),
           'subtitle' => esc_html__('Removes the block editor element css.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
          'id' => 'rm-font-awesome',
          'type' => 'switch',
          'title' => esc_html__('Remove Global Font Awesome', 'salient'),
          'subtitle' => esc_html__('By default, the Font Awesome icon library is enqueued everywhere. Enabling this will remove that and instead late enqueue the icon library if any Font Awesome icons are found on the current page.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
        array(
          'id' => 'rm-legacy-icon-css',
          'type' => 'switch',
          'title' => esc_html__('Remove Legacy Icon CSS', 'salient'),
          'subtitle' => esc_html__('Removes extra icon CSS for legacy users.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
         array(
          'id' => 'rm-wp-emojis',
          'type' => 'switch',
          'title' => esc_html__('Remove WordPress Emoji Script/CSS', 'salient'),
          'subtitle' => esc_html__('Removes the WordPress Emoji assets which automatically convert emoticons to WP specific emojis.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
         array(
          'id' => 'page_header_responsive_images',
          'type' => 'switch',
          'title' => esc_html__('Responsive Page/Post Header Image Sizing', 'salient'),
          'subtitle' => esc_html__('This will swap the background image of all page/post headers to use smaller sizes on mobile devices. Enabling this will decrease the Google lighthouse largest contentful paint metric wherever page headers are used.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
       )
     ) );



     Redux::setSection( $opt_name, array(
      'title'            => esc_html__( 'Image Sizes', 'salient' ),
      'id'               => 'general-settings-image-sizes',
      'desc' => esc_html__('Control which sizes that Salient registers. Disabling sizes will reduce number of variants that WordPress cuts when uploading new images. Removing unused sizes will reduce the amount of server space taken up by images and speed up upload time.', 'salient'),
      'subsection'       => true,
      'fields'           => array(
       array(
         'id' => 'image-size-portfolio-widget',
         'type' => 'switch',
         'title' => esc_html__('portfolio-widget', 'salient'),
         'subtitle' => '100x100',
         'desc' => '',
         'default' => '1'
       ),
       array(
        'id' => 'image-size-portfolio-thumb_small',
        'type' => 'switch',
        'title' => esc_html__('portfolio-thumb_small', 'salient'),
        'subtitle' => '400x270',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-portfolio-thumb_large',
        'type' => 'switch',
        'title' => esc_html__('portfolio-thumb_large', 'salient'),
        'subtitle' => '900x600',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-regular_small',
        'type' => 'switch',
        'title' => esc_html__('regular_small', 'salient'),
        'subtitle' => '350x350',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-regular',
        'type' => 'switch',
        'title' => esc_html__('regular', 'salient'),
        'subtitle' => '500x500',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-wide_small',
        'type' => 'switch',
        'title' => esc_html__('wide_small', 'salient'),
        'subtitle' => '670x335',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-wide',
        'type' => 'switch',
        'title' => esc_html__('wide', 'salient'),
        'subtitle' => '1000x500',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-tall',
        'type' => 'switch',
        'title' => esc_html__('tall', 'salient'),
        'subtitle' => '500x1000',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-wide_tall',
        'type' => 'switch',
        'title' => esc_html__('wide_tall', 'salient'),
        'subtitle' => '1000x1000',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-medium_featured',
        'type' => 'switch',
        'title' => esc_html__('medium_featured', 'salient'),
        'subtitle' => '800x800',
        'desc' => '',
        'default' => '1'
      ),
      array(
        'id' => 'image-size-large_featured',
        'type' => 'switch',
        'title' => esc_html__('large_featured', 'salient'),
        'subtitle' => '1870x770',
        'desc' => '',
        'default' => '1'
      ),

      )
    ) );

    
     // Theme coloring.
    $available_colors = NectarThemeManager::$available_theme_colors;

    $available_colors_option_data = array(
      'accent-color' => array(
        'title' => esc_html__('Accent Color', 'salient'),
        'subtitle' => esc_html__('Change this color to alter the accent color globally for your site.', 'salient'),
        'default' => '#3452ff'
      ),
      'extra-color-1' => array(
        'title' => esc_html__('Extra Color #1', 'salient'),
        'subtitle' => esc_html__('Applicable theme elements will have the option to choose this as a color (i.e. buttons, icons etc..)', 'salient'),
        'default' => '#ff1053'
      ),
      'extra-color-2' => array(
        'title' => esc_html__('Extra Color #2', 'salient'),
        'subtitle' => esc_html__('Applicable theme elements will have the option to choose this as a color (i.e. buttons, icons etc..)', 'salient'),
        'default' => '#2AC4EA'
      ),
      'extra-color-3' => array(
        'title' => esc_html__('Extra Color #3', 'salient'),
        'subtitle' => esc_html__('Applicable theme elements will have the option to choose this as a color (i.e. buttons, icons etc..)', 'salient'),
        'default' => '#333333'
      ),
    );

    // Main theme coloring.
    $salient_main_theme_colors = array();
    $color_index = 1;
    foreach( $available_colors as $color_key => $label) {

      $color_option_arr = array(
        'id' => $color_key,
        'type' => 'color',
        'transparent' => false,
        'desc' => '',
      );
      
      if( isset($available_colors_option_data[$color_key]) ) {
        $color_option_arr['title'] = $available_colors_option_data[$color_key]['title'];
        $color_option_arr['subtitle'] = $available_colors_option_data[$color_key]['subtitle'];
        $color_option_arr['default'] = $available_colors_option_data[$color_key]['default'];
      } else {
        $color_option_arr['title'] = esc_html($label);
        $color_option_arr['subtitle'] = '';
        $color_option_arr['default'] = '#000000';
      }

      $salient_main_theme_colors[] = $color_option_arr;

      $color_index++;
    }

    // Gradient coloring.
    $salient_gradient_theme_colors = array(
      array(
        'id' => 'extra-color-gradient',
        'type' => 'color_gradient',
        'transparent' => false,
        'title' => esc_html__('Extra Color Gradient', 'salient'),
        'subtitle' => esc_html__('Applicable theme elements will have the option to choose this as a color (i.e. buttons, icons etc..)', 'salient'),
        'desc' => '',
        'default'  => array(
          'from' => '#3452ff',
          'to'   => '#ff1053'
        ),
      ),

      array(
        'id' => 'extra-color-gradient-2',
        'type' => 'color_gradient',
        'transparent' => false,
        'title' => esc_html__('Extra Color Gradient #2', 'salient'),
        'subtitle' => esc_html__('Applicable theme elements will have the option to choose this as a color (i.e. buttons, icons etc..)', 'salient'),
        'desc' => '',
        'default'  => array(
          'from' => '#2AC4EA',
          'to'   => '#32d6ff'
        ),
      )
    );

    
    // Register theme colors
     Redux::setSection( $opt_name, array(
       'id'               => 'accent-color',
       'customizer_width' => '450px',
       'icon' => 'el el-brush',
       'title' => esc_html__('Accent Colors', 'salient'),
       'desc' => esc_html__('All accent color related options are listed here.', 'salient'),
       'fields' => array_merge($salient_main_theme_colors, $salient_gradient_theme_colors)
     ) );



     Redux::setSection( $opt_name, array(
       'id'               => 'boxed-layout',
       'customizer_width' => '450px',
       'icon' => 'el el-website',
       'title' => esc_html__('Boxed Layout', 'salient'),
       'desc' => esc_html__('All boxed layout related options are listed here.', 'salient'),
       'fields'           => array(
         array(
           'id' => 'boxed_layout',
           'type' => 'switch',
           'title' => esc_html__('Enable Boxed Layout', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'next_to_hide' => '6',
           'default' => '0'
         ),
         array(
           'id' => 'background-color',
           'type' => 'color',
           'title' => esc_html__('Background Color', 'salient'),
           'subtitle' => esc_html__('If you would rather simply use a solid color for your background, select one here.', 'salient'),
           'desc' => '',
           'transparent' => false,
           'required' => array( 'boxed_layout', '=', '1' ),
           'default' => '#f1f1f1'
         ),
         array(
           'id' => 'background_image',
           'type' => 'media',
           'title' => esc_html__('Background Image', 'salient'),
           'subtitle' => esc_html__('Upload your background here', 'salient'),
           'required' => array( 'boxed_layout', '=', '1' ),
           'desc' => ''
         ),
         array(
           'id' => 'background-repeat',
           'type' => 'select',
           'title' => esc_html__('Background Repeat', 'salient'),
           'subtitle' => esc_html__('Do you want your background to repeat? (Turn on when using patterns)', 'salient'),
           'required' => array( 'boxed_layout', '=', '1' ),
           'options' => array(
             "no-repeat" => esc_html__('No-Repeat', 'salient'),
             "repeat" => esc_html__('Repeat', 'salient'),
           )
         ),
         array(
           'id' => 'background-position',
           'type' => 'select',
           'title' => esc_html__('Background Position', 'salient'),
           'subtitle' => esc_html__('How would you like your background image to be aligned?', 'salient'),
           'required' => array( 'boxed_layout', '=', '1' ),
           'options' => array(
             "left top" => esc_html__("Left Top", "salient"),
             "left center" => esc_html__("Left Center", "salient"),
             "left bottom" => esc_html__("Left Bottom", "salient"),
             "center top" => esc_html__("Center Top", "salient"),
             "center center" => esc_html__("Center Center", "salient"),
             "center bottom" => esc_html__("Center Bottom", "salient"),
             "right top" => esc_html__("Right Top", "salient"),
             "right center" => esc_html__("Right Center", "salient"),
             "right bottom" => esc_html__("Right Bottom", "salient")
           )
         ),
         array(
           'id' => 'background-attachment',
           'type' => 'select',
           'title' => esc_html__('Background Attachment', 'salient'),
           'subtitle' => esc_html__('Would you prefer your background to scroll with your site or be fixed and not move', 'salient'),
           'required' => array( 'boxed_layout', '=', '1' ),
           'options' => array(
             "scroll" => esc_html__("Scroll", "salient"),
             "fixed" => esc_html__("Fixed", "salient")
           )
         ),
         array(
           'id' => 'background-cover',
           'type' => 'switch',
           'title' => esc_html__('Auto resize background image to fit window', 'salient'),
           'subtitle' => esc_html__('This will ensure your background image always fits no matter what size screen the user has. (Don\'t use with patterns)', 'salient'),
           'required' => array( 'boxed_layout', '=', '1' ),
           'desc' => '',
           'default' => '0'
         ),

       )
     ) );


     // -> START Typography
     Redux::setSection( $opt_name, array(
       'title'  => esc_html__( 'Typography', 'salient' ),
       'id'     => 'typography',
       'desc'   => esc_html__( 'All typography related options are listed here', 'salient' ),
       'icon'   => 'el el-font',
       'fields' => array(

       )
     ) );


     $nectar_std_fonts = array(
       'Arial, sans-serif'                                    => 'Arial',
       'Cambria, Georgia, serif'                              => 'Cambria',
       'Copse, sans-serif'                                    => 'Copse',
       "Courier, monospace"                                   => "Courier, monospace",
       "Garamond, serif"                                      => "Garamond",
       "Georgia, serif"                                       => "Georgia",
       "Impact, Charcoal, sans-serif"                         => "Impact, Charcoal, sans-serif",
       'Helvetica, sans-serif'                                => 'Sans Serif',
       "Lucida Console, Monaco, monospace"                    => "Lucida Console, Monaco, monospace",
       "Lucida Sans Unicode, Lucida Grande, sans-serif"       => "Lucida Sans Unicode, Lucida Grande, sans-serif",
       "MS Sans Serif, Geneva, sans-serif"                    => "MS Sans Serif, Geneva, sans-serif",
       "MS Serif, New York, sans-serif"                       => "MS Serif, New York, sans-serif",
       "Palatino Linotype, Book Antiqua, Palatino, serif"     => "Palatino Linotype, Book Antiqua, Palatino, serif",
       "Tahoma,Geneva, sans-serif"                            => "Tahoma",
       "Times New Roman, Times, serif"                        => "Times New Roman, Times, serif",
       "Verdana, Geneva, sans-serif"                          => "Verdana, Geneva, sans-serif",
       'Lovelo, sans-serif' => 'Lovelo'
     );

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Navigation & Page Header', 'salient' ),
       'id'               => 'typography-slider',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'default-theme-font',
           'type' => 'select',
           'title' => esc_html__('Default Theme Font Functionality', 'salient'),
           'subtitle' => esc_html__('This will determine where the default Salient font loads from (Open Sans).', 'salient'),
           'options' => array(
             "from_google" => esc_html__("Load from Google", "salient"),
             "from_theme" => esc_html__("Load from Theme", "salient"),
             "remove" => esc_html__("Do not load default font", "salient"),
           ),
           'default' => 'from_google'
         ),

         array(
           'id'       => 'logo_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Logo Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Logo font properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),
         array(
           'id'       => 'navigation_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Navigation Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Navigation font properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),
         array(
           'id'       => 'navigation_dropdown_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Navigation Dropdown Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Navigation Dropdown font properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'compiler' => true,
           'default'  => array()
         ),


         array(
           'id'       => 'page_heading_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Page Heading Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Page Heading font properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'page_heading_subtitle_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Page Heading Subtitle Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Page Heading Subtitle font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'off_canvas_nav_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Off Canvas Navigation', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Off Canvas Navigation properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'off_canvas_nav_subtext_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Off Canvas Navigation/Dropdown Description Text', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Off Canvas Navigation/Dropdown Description Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),
       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'General HTML elements', 'salient' ),
       'id'               => 'typography-general',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id'       => 'body_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Body Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Body font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()

         ),
         array(
           'id'       => 'h1_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Heading 1', 'salient' ),
           'subtitle' => esc_html__( 'Specify the H1 Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'h2_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Heading 2', 'salient' ),
           'subtitle' => esc_html__( 'Specify the H2 Text properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'h3_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Heading 3', 'salient' ),
           'subtitle' => esc_html__( 'Specify the H3 Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'h4_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Heading 4', 'salient' ),
           'subtitle' => esc_html__( 'Specify the H4 Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'h5_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Heading 5', 'salient' ),
           'subtitle' => esc_html__( 'Specify the H5 Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'h6_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Heading 6', 'salient' ),
           'subtitle' => esc_html__( 'Specify the H6 Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'i_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Italic', 'salient' ),
           'subtitle' => esc_html__( 'Specify the italic Text properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'bold_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Bold', 'salient' ),
           'subtitle' => esc_html__( 'Specify the bold text font & weight. (Other properties will inherit from the body font)', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'font-size' => false,
           'subsets' => false,
           'line-height' => false,
           'text-transform' => false,
           'letter-spacing' => false,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'label_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Form and Category Labels', 'salient' ),
           'subtitle' => esc_html__( 'Specify html Label properties. When using the "Material" theme skin, sidebar links will inherit this as well.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

       )
     ) );

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Salient Elements/Areas', 'salient' ),
       'id'               => 'typography-nectar',
       'subsection'       => true,
       'fields'           => array(

         array(
           'id'       => 'sidebar_footer_h_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Nectar Button', 'salient' ),
           'subtitle' => esc_html__( 'Specify the font properties for Nectar Buttons.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'nectar_sidebar_footer_headers_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Sidebar/Footer Headers', 'salient' ),
           'subtitle' => esc_html__( 'Specify the font properties for headers used in sidebars & the footer.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'nectar_slider_heading_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Nectar/Home Slider Heading Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Nectar Slider Heading font properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
           'id'       => 'home_slider_caption_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Nectar/Home Slider Caption Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Nectar Slider Caption font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'testimonial_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Testimonial Slider/Blockquote Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Testimonial Slider/Blockquote font properties.', 'salient' ),
           'google'   => true,
           'all_styles'  => false,
           'fonts' =>  $nectar_std_fonts,
           'default'  => array()
         ),

         array(
          'id'       => 'blog_single_post_content_font_family',
          'type'     => 'typography',
          'title'    => esc_html__( 'Blog Single Post Content', 'salient' ),
          'subtitle' => esc_html__( 'Specify the font properties for the blog single post content area.', 'salient' ),
          'google'   => true,
          'fonts' =>  $nectar_std_fonts,
          'all_styles'  => false,
          'default'  => array()
        ),

         array(
           'id'       => 'portfolio_filters_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Portfolio/Post Grid Filters', 'salient' ),
           'subtitle' => esc_html__( 'Specify the filter font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'portfolio_caption_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Portfolio Caption/Excerpt', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Portfolio project caption/excerpt font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'team_member_h_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Sub-headers & Team Member Names Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the Sub-headers & Team Member Name properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'nectar_dropcap_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'Dropcap', 'salient' ),
           'subtitle' => esc_html__( 'Specify the dropcap font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'nectar_woo_shop_product_title_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'WooCommerce Product Title Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the WooCommerce Product Title font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),

         array(
           'id'       => 'nectar_woo_shop_product_secondary_font_family',
           'type'     => 'typography',
           'title'    => esc_html__( 'WooCommerce Product Secondary Font', 'salient' ),
           'subtitle' => esc_html__( 'Specify the WooCommerce Product Secondary font properties.', 'salient' ),
           'google'   => true,
           'fonts' =>  $nectar_std_fonts,
           'all_styles'  => false,
           'default'  => array()
         ),


       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Responsive Settings', 'salient' ),
       'id'               => 'typography-responsive',
       'subsection'       => true,
       'fields'           => array(

         array(
           'id' => 'use-responsive-heading-typography',
           'type' => 'switch',
           'title' => esc_html__('Custom Responsive Headings', 'salient'),
           'subtitle' => esc_html__('If left off, Salient will calculate the responsive typography settings for your h1-h6 tags & body automatically.', 'salient'),
           'desc' => ''
         ),

         array(
           'id'    => 'info-use-responsive-heading-typography',
           'type'  => 'info',
           'style' => 'success',
           'title' => esc_html__('How These Settings Work',  'salient'),
           'icon'  => 'el el-info-circle',
           'required' => array( 'use-responsive-heading-typography', '=', '1' ),
           'desc'  => esc_html__( 'Set the amount (in %) you would like each heading tag to decrease by for every viewport. For example, a value of "100" would mean the font stays at 100% of the font size defined and a value of "50" would mean the font shrinks to "50%" of the font size defined. Note: these will apply to all heading tags defined by you throughout your site, but some Nectar Elements will override the sizing within themselves.',  'salient')
         ),

         array(
           'id'        => 'h1-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H1 Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 75,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h1-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H1 Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 70,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h1-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H1 Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 65,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'   =>'responsive-heading-typography-divider-1',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),



         array(
           'id'        => 'h2-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H2 Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 85,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h2-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H2 Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 80,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h2-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H2 Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 70,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'   =>'responsive-heading-typography-divider-2',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),


         array(
           'id'        => 'h3-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H3 Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 85,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h3-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H3 Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 80,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h3-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H3 Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 70,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'   =>'responsive-heading-typography-divider-3',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),


         array(
           'id'        => 'h4-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H4 Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h4-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H4 Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 90,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h4-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H4 Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 90,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'   =>'responsive-heading-typography-divider-4',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),



         array(
           'id'        => 'h5-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H5 Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h5-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H5 Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h5-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H5 Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'   =>'responsive-heading-typography-divider-5',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),



         array(
           'id'        => 'h6-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H6 Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h6-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H6 Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'h6-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('H6 Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'   =>'responsive-heading-typography-divider-6',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'body-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('Body Font Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'body-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('Body Font Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'body-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('Body Font Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),


         array(
           'id'   =>'responsive-heading-typography-divider-6',
           'desc' => '',
           'type' => 'divide',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'blockquote-small-desktop-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('Testimonial/Blockquote Font Small Desktop', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'blockquote-tablet-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('Testimonial/Blockquote Font Tablet', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),

         array(
           'id'        => 'blockquote-phone-font-size',
           'type'      => 'slider',
           'title'     => esc_html__('Testimonial/Blockquote Font Phone', 'salient'),
           'subtitle'  => '',
           'desc'      => '',
           "default"   => 100,
           "min"       => 10,
           "step"      => 5,
           "max"       => 100,
           'display_value' => 'text',
           'required' => array( 'use-responsive-heading-typography', '=', '1' )
         ),


       )
     ) );




     Redux::setSection( $opt_name, array(
       'title'  => esc_html__( 'Header Navigation', 'salient' ),
       'id'     => 'header-nav',
       'desc'   => esc_html__( 'All header navigation related options are listed here.', 'salient' ),
       'icon'   => 'el el-lines',
       'fields' => array(

       )
     ) );




     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Logo & General Styling', 'salient' ),
       'id'               => 'header-nav-general',
       'subsection'       => true,
       'fields'           => array(

         array(
           'id' => 'use-logo',
           'type' => 'switch',
           'title' => esc_html__('Use Image for Logo', 'salient'),
           'subtitle' => esc_html__('If left unchecked, plain text will be used instead (generated from site name).', 'salient'),
           'desc' => ''
         ),
         array(
           'id' => 'logo',
           'type' => 'media',
           'title' => esc_html__('Logo Upload', 'salient'),
           'subtitle' => esc_html__('Upload your logo here and enter the height of it below.','salient'),
           'required' => array( 'use-logo', '=', '1' ),
           'desc' => ''
         ),
         array(
           'id' => 'retina-logo',
           'type' => 'media',
           'title' => esc_html__('Retina Logo Upload', 'salient'),
           'subtitle' => esc_html__('Upload at exactly 2x the size of your standard logo to keep the display crisp on screens with a higher pixel density.', 'salient'),
           'desc' => '' ,
           'required' => array( 'use-logo', '=', '1' )
         ),

         array(
          'id'    => 'info-use-responsive-heading-typography',
          'type'  => 'info',
          'style' => 'success',
          'title' => esc_html__('Logo not changing after saving?',  'salient'),
          'icon'  => 'el el-info-circle',
          'required' => array( 'use-logo', '=', '1' ),
          'desc'  => esc_html__( 'If you\'re using a transparent header, the logos displayed while that effect is active are specified in the',  'salient') . ' <strong>' . esc_html__( 'Transparent Header Effect',  'salient') . '</strong> tab.'
        ),

         array(
           'id' => 'logo-height',
           'type' => 'text',
           'title' => esc_html__('Logo Height', 'salient'),
           'subtitle' => esc_html__('Don\'t include "px" in the string. e.g. 30', 'salient'),
           'desc' => '',
           'validate' => 'numeric',
           'required' => array( 'use-logo', '=', '1' ),
         ),
         array(
           'id' => 'mobile-logo-height',
           'type' => 'text',
           'title' => esc_html__('Mobile Logo Height', 'salient'),
           'subtitle' => esc_html__('Don\'t include "px" in the string. e.g. 24', 'salient'),
           'desc' => '',
           'required' => array( 'use-logo', '=', '1' ),
           'validate' => 'numeric'
         ),

         array(
           'id' => 'mobile-logo',
           'type' => 'media',
           'title' => esc_html__('Mobile Only Logo Upload', 'salient'),
           'subtitle' => esc_html__('An optional field that allows you to display a separate logo that will be shown on mobile devices only.', 'salient'),
           'required' => array( 'use-logo', '=', '1' ),
           'desc' => ''
         ),

         array(
           'id' => 'header-padding',
           'type' => 'text',
           'title' => esc_html__('Header Padding', 'salient'),
           'subtitle' => esc_html__('Don\'t include "px" in the string. e.g. 28', 'salient'),
           'desc' => '',
           'validate' => 'numeric'
         ),

         array(
           'id' => 'header-remove-fixed',
           'type' => 'switch',
           'title' => esc_html__('Header Remove Desktop Stickiness', 'salient'),
           'subtitle' => esc_html__('By default your header will always remain at the top of the screen even when scrolling down the page. Enabling this will remove that functionality and cause it to stay at the top of the page.', 'salient'),
           'desc' => '',
           'switch' => true,
           'default' => '0'
         ),

         array(
           'id' => 'header-box-shadow',
           'type' => 'select',
           'title' => esc_html__('Header Box Shadow', 'salient'),
           'subtitle' => esc_html__('Please select your header box shadow here.', 'salient'),
           'desc' => '',
           'options' => array(
             'small' => esc_html__('Small', 'salient'),
             'large' => esc_html__('Large', 'salient'),
             'large-line' => esc_html__('Large With Bottom Line', 'salient'),
             'none' => esc_html__('None', 'salient')
           ),
           'default' => 'large'
         ),
         array(
           'id'        => 'header-menu-item-spacing',
           'type'      => 'slider',
           'title'     => esc_html__('Menu Item Spacing', 'salient'),
           'subtitle'  => esc_html__('Set the padding that will display on each side of your header menu items - space will be set in pixels.', 'salient'),
           'desc'      => '',
           "default"   => 10,
           "min"       => 8,
           "step"      => 1,
           "max"       => 50,
           'display_value' => 'label'
         ),
         array(
           'id' => 'header-bg-opacity',
           'type'      => 'slider',
           'title'     => esc_html__('Header BG Opacity', 'salient'),
           'subtitle'  => esc_html__('Please set your header BG opacity here.', 'salient'),
           'desc'      => '',
           "default"   => 100,
           "min"       => 1,
           "step"      => 1,
           "max"       => 100,
           'hint' => array('content' => esc_html__('If you are trying to have your header navigation completely see through before scrolling, setting this very low is not how to achieve it. The fully transparent style as shown on many of the demos is the option titled','salient') .'<b> '. esc_html__('Use Transparent Header When Applicable','salient').'</b> '. esc_html__('which is available in the Header Navigation ~ Transparent Header Effect tab.','salient'), 'title' => ''),
           'display_value' => 'label'
         ),

         array(
          'id' => 'header-blur-bg',
          'type' => 'switch',
          'title' => esc_html__('Header Blur Background', 'salient'),
          'subtitle' => esc_html__('This will cause your header to blur the area behind it, giving a frosted glass effect.', 'salient'),
          'desc' => '',
          'switch' => true,
          'default' => '0'
        ),

        array(
          'id' => 'header-blur-bg-func',
          'type' => 'select',
          'title' => esc_html__('Header Blur Background Functionality', 'salient'),
          'subtitle' => '',
          'desc' => '',
          'required' => array( array( 'header-blur-bg', '=', '1' ) ),
          'options' => array(
            'active_non_transparent' => esc_html__('Active when "Transparent header effect" is not', 'salient'),
            'active_all' => esc_html__('Active in all header states', 'salient')
          ),
          'default' => 'active_non_transparent'
        ),

         array(
           'id' => 'header-button-styling',
           'type' => 'select',
           'title' => esc_html__('Header Button Link Style', 'salient'),
           'subtitle' => esc_html__('This effects any header links which are set to use','salient') . ' <a target="_blank" href="http://themenectar.com/docs/salient/header-button-links/">' . esc_html('button styling.', 'salient') .'</a>',
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Default', 'salient'),
             'hover_scale' => esc_html__('Scale on Hover', 'salient'),
             'shadow_hover_scale' => esc_html__('Button Shadow and Scale on Hover', 'salient')
           ),
           'default' => 'default'
         ),

         array(
          'id' => 'header-enable-border',
          'type' => 'switch',
          'title' => esc_html__('Header Enable Border', 'salient'),
          'subtitle' => esc_html__('This will add a border onto your header navigation.', 'salient'),
          'desc' => '',
          'switch' => true,
          'default' => '0'
        ),
        array(
          'id' => 'header-border-color',
          'type' => 'color',
          'title' => esc_html__('Header Border Color', 'salient'),
          'subtitle' => '',
          'transparent' => false,
          'required' => array( array( 'header-enable-border', '=', '1' ) ),
          'desc' => '',
          'default' => '#000000'
        ),

         array(
          'id'    => 'header-cs-new-loc',
          'type'  => 'info',
          'style' => 'success',
          'title' => esc_html__('Header Color Scheme', 'salient'),
          'icon'  => 'el-icon-info-sign',
          'desc'  => esc_html__( 'Looking for the the Header color scheme options? They now have their own section tab on the left.', 'salient')
        )

       )
     ) );





     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Layout & Content Related', 'salient' ),
       'id'               => 'header-nav-layout',
       'subsection'       => true,
       'fields'           => array(


         array(
           'id' => 'header_format',
           'type' => 'image_select',
           'title' => esc_html__('Header Layout', 'salient'),
           'subtitle' => esc_html__('Please select the layout you desire.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => array('title' => esc_html__('Default Layout','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/default-header.png'),
             'centered-menu' => array('title' => esc_html__('Centered Menu','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/centered-menu.png'),
             'centered-menu-under-logo' => array('title' => esc_html__('Centered Menu Alt','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/centered-menu-under-logo.png'),
             'centered-menu-bottom-bar' => array('title' => esc_html__('Menu Bottom Bar','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/centered-menu-bottom-bar.png', 'tooltip' => 'Relies on the Material theme skin and will enable that skin, even if it is not selected. <br/><br/> Top left: Social Icon Area <br/> Top right: Header Buttons <br/> Bottom: Navigation Links'),
             'centered-logo-between-menu' => array('title' => esc_html__('Centered Logo Menu','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/centered-logo-menu.png'),
             'centered-logo-between-menu-alt' => array('title' => esc_html__('Centered Logo Menu Alt','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/centered-logo-menu-alt.png'),
             'menu-left-aligned' => array('title' => esc_html__('Menu Left Aligned','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/menu-left-aligned.png'),
             'left-header' => array('title' => esc_html__('Left Header','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/fixed-left.png', 'tooltip' => 'Does not allow 	&quot;Transparency&quot; options, and some options in	&quot;Animation Effects&quot;')
           ),
           'default' => 'default'
         ),
         
         array(
           'id' => 'left-header-dropdown-func',
           'type' => 'select',
           'title' => esc_html__('Left Header Dropdown Functionality', 'salient'),
           'subtitle' => esc_html__('Please select the functionality for how dropdowns will behave in the left header navigation.', 'salient'),
           'desc' => '',
          'required' => array( array( 'header_format', '=', 'left-header' ) ),
           'options' => array(
             'default' => esc_html__('Dropdown Parent Link Toggles Submenu', 'salient'),
             'separate-dropdown-parent-link' => esc_html__('Separate Dropdown Parent Link From Dropdown Toggle', 'salient')
           ),
           'default' => 'default'
         ),
         
         array(
           'id' => 'centered-menu-bottom-bar-separator',
           'type' => 'switch',
           'title' => esc_html__('Menu Bottom Bar Separator', 'salient'),
           'subtitle' => esc_html__('Add a line to separate the top/bottom of your header.', 'salient'),
           'desc' => '',
           'switch' => true,
           'required' => array( 'header_format', '=', 'centered-menu-bottom-bar' ),
           'default' => '0'
         ),
         array(
           'id' => 'centered-menu-bottom-bar-alignment',
           'type' => 'select',
           'required' => array( 'header_format', '=', 'centered-menu-bottom-bar' ),
           'title' => esc_html__('Menu Bottom Bar Alignment', 'salient'),
           'subtitle' => esc_html__('Please select how you would like your header content to align.', 'salient'),
           'desc' => '',
           'options' => array(
             'center' => esc_html__('Center', 'salient'),
             'left' => esc_html__('Left', 'salient'),
             'left_t_center_b' => esc_html__('Left Top Center Bottom', 'salient'),
           ),
           'default' => 'center'
         ),

         array(
           'id' => 'header-fullwidth',
           'type' => 'switch',
           'title' => esc_html__('Full Width Header Content', 'salient'),
           'subtitle' => esc_html__('Do you want the header content to span the full width of the page?', 'salient'),
           'desc' => '',
           'switch' => true,
           'required' => array( 'header-size', '=', 'default' ),
           'default' => '0'
         ),
         array(
           'id' => 'header-fullwidth-padding',
           'type' => 'text',
           'title' => esc_html__('Full Width Left/Right Padding', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'header-fullwidth', '=', '1' )
         ),

         array(
          'id' => 'header-size',
          'type' => 'select',
          'required' => array( 
            array( 'header_format', '!=', 'left-header' ),
            array( 'header_format', '!=', 'centered-menu-bottom-bar' ),
            array( 'header_layout', '!=', 'header_with_secondary' ),
            array( 'boxed_layout', '!=', '1' ),
            
          ),
          'title' => esc_html__('Header Size', 'salient'),
          'subtitle' => esc_html__('Please select your header size here.', 'salient'),
          'desc' => '',
          'options' => array(
            'default' => esc_html__('Default (Expanded to screen edges)', 'salient'),
            'contained' => esc_html__('Contained', 'salient'),
          ),
          'default' => 'default'
        ),
        array(
          'id' => 'header-border-radius',
          'type'      => 'slider',
          'required' => array( 
              array( 'header_format', '!=', 'left-header' ),
              array( 'header_format', '!=', 'centered-menu-bottom-bar' ),
              array( 'header_layout', '!=', 'header_with_secondary' ),
              array( 'boxed_layout', '!=', '1' ),
              array( 'header-size', '=', 'contained' ),
          ),
          'title'     => esc_html__('Header Roundness', 'salient'),
          'subtitle'  => esc_html__('This allows you to round the corners of your header. (Amount to round in px)', 'salient'),
          'desc'      => '',
          "default"   => 0,
          "min"       => 0,
          "step"      => 1,
          "max"       => 50,
          'display_value' => 'label'
        ),

         array(
           'id' => 'header-account-button',
           'type' => 'switch',
           'title' => esc_html__('Add User Account Button', 'salient'),
           'subtitle' => esc_html__('This will add a user account icon button within the button area of your header navigation', 'salient'),
           'desc' => '',
           'switch' => true,
           'default' => '0'
         ),
         array(
           'id' => 'header-account-button-url',
           'type' => 'text',
           'title' => esc_html__('User Account Button URL', 'salient'),
           'required' => array( 'header-account-button', '=', '1' ),
           'subtitle' => esc_html__('Enter the URL of your user account button', 'salient'),
           'desc' => '',
           'default' => ''
         ),
         array(
           'id' => 'header-text-widget',
           'type' => 'editor',
           'title' => esc_html__('Text To Display In Header', 'salient'),
           'subtitle' => esc_html__('Enter a small amount of text to display in your header navigation. e.g. a phone number, store address etc. The positioning of this content will be determined by the header layout that you are using.', 'salient'),
           'default' => '',
           'args' => array(
             'teeny' => true,
             'media_buttons' => false,
             'textarea_rows' => 5
           )
         ),
         array(
           'id' => 'enable_social_in_header',
           'type' => 'switch',
           'title' => esc_html__('Enable Social Icons', 'salient'),
           'subtitle' => esc_html__('Do you want the your nav to display social icons? If using the secondary header navigation option, the icons will be displayed in that top bar instead of the main navigation.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'use-facebook-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Facebook Icon', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'enable_social_in_header', '=', '1' ),
         ),
         array(
           'id' => 'use-twitter-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Twitter Icon', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'enable_social_in_header', '=', '1' ),
         ),
         array(
          'id' => 'use-x-twitter-icon-header',
          'type' => 'checkbox',
          'title' => esc_html__('Use X-Twitter Icon', 'salient'),
          'subtitle' => '',
          'desc' => '',
          'required' => array( 'enable_social_in_header', '=', '1' ),
        ),
         array(
           'id' => 'use-google-plus-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Google Icon', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'enable_social_in_header', '=', '1' ),
         ),
         array(
           'id' => 'use-vimeo-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Vimeo Icon', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'enable_social_in_header', '=', '1' ),
         ),
         array(
           'id' => 'use-dribbble-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Dribbble Icon', 'salient'),
           'subtitle' => '',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'desc' => ''
         ),
         array(
           'id' => 'use-pinterest-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Pinterest Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-youtube-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Youtube Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-tumblr-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Tumblr Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-linkedin-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use LinkedIn Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-rss-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use RSS Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-behance-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Behance Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-instagram-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Instagram Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-flickr-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Flickr Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-spotify-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use Spotify Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-github-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use GitHub Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-stackexchange-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use StackExchange Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-soundcloud-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use SoundCloud Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-vk-icon-header',
           'type' => 'checkbox',
           'title' => esc_html__('Use VK Icon', 'salient'),
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-vine-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Vine Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-houzz-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Houzz Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-yelp-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Yelp Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-mixcloud-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Mixcloud Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-snapchat-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Snapchat Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-bandcamp-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Bandcamp Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-tripadvisor-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Tripadvisor Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-telegram-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Telegram Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-slack-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Slack Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-medium-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Medium Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-artstation-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Artstation Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-discord-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Discord Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-whatsapp-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use WhatsApp Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-messenger-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Messenger Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-tiktok-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use TikTok Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-twitch-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Twitch Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-applemusic-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Apple Music Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
          'id' => 'use-patreon-icon-header',
          'type' => 'checkbox',
          'required' => array( 'enable_social_in_header', '=', '1' ),
          'title' => esc_html__('Use Patreon Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
         array(
          'id' => 'use-xing-icon-header',
          'type' => 'checkbox',
          'required' => array( 'enable_social_in_header', '=', '1' ),
          'title' => esc_html__('Use Xing Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
        array(
          'id' => 'use-mastodon-icon-header',
          'type' => 'checkbox',
          'required' => array( 'enable_social_in_header', '=', '1' ),
          'title' => esc_html__('Use Mastodon Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
        array(
          'id' => 'use-threads-icon-header',
          'type' => 'checkbox',
          'required' => array( 'enable_social_in_header', '=', '1' ),
          'title' => esc_html__('Use Threads Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
        array(
          'id' => 'use-trustpilot-icon-header',
          'type' => 'checkbox',
          'required' => array( 'enable_social_in_header', '=', '1' ),
          'title' => esc_html__('Use Trustpilot Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
         array(
           'id' => 'use-email-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Email Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-phone-icon-header',
           'type' => 'checkbox',
           'required' => array( 'enable_social_in_header', '=', '1' ),
           'title' => esc_html__('Use Phone Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         )



       )
     ) );





     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Secondary Header Bar', 'salient' ),
       'id'               => 'header-secondary-bar',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'header_layout',
           'type' => 'select',
           'title' => esc_html__('Secondary Header Bar', 'salient'),
           'subtitle' => esc_html__('Please select if you would like an additional header bar above the main navigation.', 'salient'),
           'desc' => '',
           'options' => array(
             'standard' => esc_html__('Standard Header', 'salient'),
             'header_with_secondary' => esc_html__('Header With Secondary Navigation Bar', 'salient'),
           ),
           'default' => 'standard'
         ),
         array(
           'id' => 'secondary-header-text',
           'type' => 'text',
           'title' => esc_html__('Secondary Header Text', 'salient'),
           'required' => array( 'header_layout', '=', 'header_with_secondary' ),
           'subtitle' => esc_html__('Add the text that you would like to appear in the secondary header.', 'salient'),
           'desc' => ''
         ),
         array(
           'id' => 'secondary-header-link',
           'type' => 'text',
           'title' => esc_html__('Secondary Header Link URL', 'salient'),
           'required' => array( 'header_layout', '=', 'header_with_secondary' ),
           'subtitle' => esc_html__('Please enter an optional URL for the secondary header text here.', 'salient'),
           'desc' => ''
         ),
         array(
           'id' => 'secondary-header-mobile-display',
           'type' => 'select',
           'required' => array( 'header_layout', '=', 'header_with_secondary' ),
           'title' => esc_html__('Secondary Header Mobile Functionality', 'salient'),
           'subtitle' => esc_html__('Please select how you would like the secondary header bar to display on mobile devices.', 'salient') . '<br/><br/><i>' . esc_html__('The option to "Display Items Above Mobile Header" will be skipped when using the "Header Permanent Transparent" option in the Header Navigation > Transparent Effect tab.', 'salient') .'</i>',
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Add Items Into Mobile Navigation Menu', 'salient'),
             'display_full' => esc_html__('Display Items Above Mobile Header', 'salient'),
           ),
           'default' => 'default'
         ),

       )
     ) );



     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Transparent Header Effect', 'salient' ),
       'id'               => 'header-nav-transparency',
       'class'            => 'salient-header-nav-transparency',
       'subsection'       => true,
       'desc'             => __('Inactive while using "Contained" for the "Header Size" option.', 'salient'),
       'fields'           => array(


         array(
           'id' => 'transparent-header',
           'type' => 'switch',
           'title' => esc_html__('Use Transparent Header When Applicable', 'salient'),
           'subtitle' => esc_html__('If activated this will cause your header to be completely transparent before the user scrolls. Valid instances where this will get used include using a Page Header or using a Full width/screen Nectar Slider at the top of a page.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'header-starting-logo',
           'type' => 'media',
           'title' => esc_html__('Header Starting Logo Upload', 'salient'),
           'subtitle' => esc_html__('This will be used when the header is transparent before the user scrolls. (Will be swapped for the regular logo upon scrolling)', 'salient'),
           'desc' => '' ,
           'required' => array( 'transparent-header', '=', '1' ),

         ),
         array(
           'id' => 'header-starting-retina-logo',
           'type' => 'media',
           'title' => esc_html__('Header Starting Retina Logo Upload', 'salient'),
           'subtitle' => esc_html__('Retina version of the header starting logo.', 'salient'),
           'required' => array( 'transparent-header', '=', '1' ),
           'desc' => ''
         ),
         array(
           'id' => 'header-starting-mobile-only-logo',
           'type' => 'media',
           'title' => esc_html__('Header Starting Mobile Only Logo Upload', 'salient'),
           'subtitle' => esc_html__('A separate header starting logo that will be shown on mobile devices only.', 'salient'),
           'required' => array( 'transparent-header', '=', '1' ),
           'desc' => ''
         ),

         array(
           'id' => 'header-starting-logo-dark',
           'type' => 'media',
           'title' => esc_html__('Header Starting Dark Logo Upload', 'salient'),
           'subtitle' => esc_html__('This will be used when the header transparent effect is active and the dark color is selected. (If nothing is uploaded, the default logo will be used)', 'salient'),
           'desc' => '' ,
           'required' => array( 'transparent-header', '=', '1' ),
         ),
         array(
           'id' => 'header-starting-retina-logo-dark',
           'type' => 'media',
           'title' => esc_html__('Header Starting Dark Retina Logo Upload', 'salient'),
           'subtitle' => esc_html__('Retina version of the header starting dark logo.  (If nothing is uploaded, the default logo will be used)', 'salient'),
           'desc' => '',
           'required' => array( 'transparent-header', '=', '1' ),
         ),
         array(
           'id' => 'header-starting-mobile-only-logo-dark',
           'type' => 'media',
           'title' => esc_html__('Header Starting Dark Mobile Only Logo Upload', 'salient'),
           'subtitle' => esc_html__('A separate header starting dark logo that will be shown on mobile devices only.', 'salient'),
           'required' => array( 'transparent-header', '=', '1' ),
           'desc' => ''
         ),
         array(
           'id' => 'header-starting-color',
           'type' => 'color',
           'title' => esc_html__('Header Starting Text Color', 'salient'),
           'subtitle' => esc_html__('Please select the color you desire for your header text before the user scrolls', 'salient'),
           'desc' => '',
           'transparent' => false,
           'required' => array( 'transparent-header', '=', '1' ),
           'default' => '#ffffff'
         ),
         array(
           'id' => 'header-transparent-dark-color',
           'type' => 'color',
           'title' => esc_html__('Header Dark Text Color', 'salient'),
           'subtitle' => esc_html__('Please select the color you desire for your header navigation links when the dark header is triggered. This occurs on dark Nectar Slides, dark rows when using permanent transparent etc.', 'salient'),
           'desc' => '',
           'transparent' => false,
           'required' => array( 'transparent-header', '=', '1' ),
           'default' => '#000000'
         ),
         array(
           'id' => 'header-starting-opacity',
           'type' => 'select',
           'required' => array( 'transparent-header', '=', '1' ),
           'title' => esc_html__('Header Starting Text Opacity', 'salient'),
           'subtitle' => esc_html__('Please select the opacity you desire for your header text before the user scrolls', 'salient'),
           'desc' => '',
           'options' => array(
             '0.75' => esc_html__('Default (Dimmed)', 'salient'),
             '1.0' => esc_html__('Full Opacity', 'salient'),
           ),
           'default' => '0.75'
         ),
         array(
           'id' => 'header-permanent-transparent',
           'type' => 'switch',
           'title' => esc_html__('Header Permanent Transparent', 'salient'),
           'subtitle' => esc_html__('Turning this on will allow your header to remain transparent even after scroll down', 'salient'),
           'required' => array( array( 'transparent-header', '=', '1' ), array( 'header_format', '!=', 'centered-menu-bottom-bar' ) ),
           'desc' => '',
           'hint' => array('content' => esc_html__('Your navigation will alternate between dark and light color schemes based on the intersecting row. When editing your pages, every row in the page builder has a field for', 'salient') . ' <b>' . esc_html__('Text Color','salient') .'</b> ' . esc_html__('to set this.','salient'), 'title' => ''),
           'default' => '0'
         ),
         array(
           'id' => 'header-inherit-row-color',
           'type' => 'switch',
           'title' => esc_html__('Header Inherit Row Color', 'salient'),
           'subtitle' => esc_html__('Turning this on will allow your header to take on the background color of the row that it passes.', 'salient'),
           'desc' => '',
           'hint' => array('content' => esc_html__('Hint: The navigation logo and links will alternative between dark and light based on what the intersecting row has set. When editing your pages, every row in the page builder has a field for', 'salient') . ' <b>' . esc_html__('Text Color','salient') .'</b> ' . esc_html__('to set this.','salient'), 'title' => ''),
           'switch' => true,
           'required' => array( array( 'transparent-header', '=', '1' ), array( 'header_format', '!=', 'centered-menu-bottom-bar' ) ),
           'default' => '0'
         ),
         array(
           'id' => 'header-remove-border',
           'type' => 'switch',
           'title' => esc_html__('Remove Border On Transparent Header', 'salient'),
           'subtitle' => esc_html__('Turning this on will remove the border that normally appears with the transparent header', 'salient'),
           'desc' => '',
           'required' => array( array( 'transparent-header', '=', '1' ), array( 'theme-skin', '!=', 'material') ),
           'default' => '0'
         ),
         array(
           'id' => 'transparent-header-shadow-helper',
           'type' => 'switch',
           'title' => esc_html__('Add Shadow Behind Transparent Header', 'salient'),
           'subtitle' => esc_html__('If activated this will add a subtle shadow behind your transparent header to help with the visibility of your navigation items.', 'salient'),
           'desc' => '',
           'required' => array( 'transparent-header', '=', '1' ),
           'default' => '0'
         ),


       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Animation Effects', 'salient' ),
       'id'               => 'header-nav-animation-effects',
       'subsection'       => true,
       'fields'           => array(

         array(
           'id' => 'header-hover-effect',
           'type' => 'select',
           'title' => esc_html__('Header Link Hover/Active Effect', 'salient'),
           'subtitle' => esc_html__('Please select your header link hover/active effect here.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Color Change', 'salient'),
             'animated_underline' => esc_html__('Animated Underline', 'salient'),
             'button_bg' => esc_html__('Button Background', 'salient')
           ),
           'default' => 'animated_underline'
         ),

         array(
          'id' => 'header-hover-effect-button-bg-size',
          'type' => 'select',
          'title' => esc_html__('Button Background Size', 'salient'),
          'subtitle' => esc_html__('Alters the button background size relative to link text.', 'salient'),
          'desc' => '',
          'options' => array(
            'small' => esc_html__('Small', 'salient'),
            'medium' => esc_html__('Medium', 'salient'),
            'large' => esc_html__('Large', 'salient'),
          ),
          'required' => array( 'header-hover-effect', '=', 'button_bg' ),
          'default' => 'medium'
        ),

         array(
          'id' => 'header-hover-effect-button-bg-style',
          'type' => 'select',
          'title' => esc_html__('Button Background Animation', 'salient'),
          'subtitle' => esc_html__('Alters the button background entrance animation.', 'salient'),
          'desc' => '',
          'options' => array(
            'fade-in' => esc_html__('Fade In', 'salient'),
            'grow-in' => esc_html__('Grow In', 'salient'),
          ),
          'required' => array( 'header-hover-effect', '=', 'button_bg' ),
          'default' => 'fade-in'
        ),

         array(
           'id' => 'header-hide-until-needed',
           'type' => 'switch',
           'title' => esc_html__('Header Hide Until Needed', 'salient'),
           'subtitle' => esc_html__('Do you want the header to be hidden after scrolling until needed? i.e. the user scrolls back up towards the top', 'salient'),
           'desc' => '',
           'required' => array( 'header_format', '!=', 'centered-menu-bottom-bar' ),
           'default' => ''
         ),

         array(
           'id' => 'header-resize-on-scroll',
           'type' => 'switch',
           'title' => esc_html__('Header Resize On Scroll', 'salient'),
           'subtitle' => esc_html__('Do you want the header to shrink a little when you scroll?', 'salient'),
           'desc' => '',
           'required' => array( 'header_format', '!=', 'centered-menu-bottom-bar' ),
           'default' => '1' ,
           'hint' => array('content' => esc_html__('This will only be active when the','salient') .'<b> ' . esc_html__('Header Hide Until Needed','salient') . '</b> ' . esc_html__('effect is turned off', 'salient'), 'title' => ''),
         ),
         array(
           'id' => 'header-resize-on-scroll-shrink-num',
           'type' => 'text',
           'title' => esc_html__('Header Logo Shrink Number (in px)', 'salient'),
           'subtitle' => esc_html__('Don\'t include "px" in the string. e.g. 6', 'salient'),
           'desc' => '',
           'required' => array( 'header-resize-on-scroll', '=', '1' ),
           'validate' => 'numeric'
         ),

         array(
           'id' => 'condense-header-on-scroll',
           'type' => 'switch',
           'title' => esc_html__('Condense Header On Scroll', 'salient'),
           'subtitle' => esc_html__('This option is specific to "Menu Bottom Bar" Header Format.','salient') . '<br /><br /> <strong>' . esc_html__('When Menu Is Center Aligned','salient') . '</strong><br />' . esc_html__('Adds the logo/header buttons into the bottom nav bar when scrolling. Uses the "Mobile Only Logo" if supplied.', 'salient') . '<br /><br /> <strong>' . esc_html__('When Menu Is left Aligned','salient') . '</strong><br />' . esc_html__('Keeps bottom bar sticky when scrolling.', 'salient'),
           'desc' => '',
           'required' => array( 'header_format', '=', 'centered-menu-bottom-bar' ),
           'default' => ''
         ),



       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Dropdown/Megamenu', 'salient' ),
       'id'               => 'header-nav-dropdowns',
       'subsection'       => true,
       'fields'           => array(


         array(
           'id' => 'header-dropdown-opacity',
           'type'      => 'slider',
           'title'     => esc_html__('Header Dropdown Opacity', 'salient'),
           'subtitle'  => esc_html__('Please select your dropdown opacity here', 'salient'),
           'desc'      => '',
           "default"   => 100,
           "min"       => 1,
           "step"      => 1,
           "max"       => 100,
           'display_value' => 'label'
         ),

         array(
           'id' => 'header-dropdown-hover-effect',
           'type' => 'select',
           'title' => esc_html__('Header Dropdown Link Hover/Active Effect', 'salient'),
           'subtitle' => esc_html__('Please select your header dropdown link hover/active effect here.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Background Color Change', 'salient'),
             'animated_underline' => esc_html__('Animated Underline', 'salient'),
           ),
           'default' => 'default'
         ),

         array(
           'id' => 'header-dropdown-arrows',
           'type' => 'select',
           'title' => esc_html__('Header Dropdown Arrows', 'salient'),
           'subtitle' => esc_html__('Please choose whether you would like your dropdowns to show a down arrow.', 'salient'),
           'desc' => '',
          'required' => array( array( 'header_format', '!=', 'left-header' ) ),
           'options' => array(
             'inherit' => esc_html__('Inherit From Theme Skin', 'salient'),
             'show' => esc_html__('Show Arrow', 'salient'),
             'dont_show' => esc_html__('Don\'t Show Arrow', 'salient')
           ),
           'default' => 'inherit'
         ),

         array(
           'id' => 'header-dropdown-display-desc',
           'type' => 'switch',
           'title' => esc_html__('Header Dropdown Display Descriptions', 'salient'),
           'subtitle' => esc_html__('This will display the "Description" field specified for dropdown menu items in Appearance > Menus.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'header-dropdown-position',
           'type' => 'select',
           'title' => esc_html__('Header Dropdown Positioning', 'salient'),
           'subtitle' => esc_html__('Please select how you would like your dropdowns to align to the parent menu item.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Bottom of Header Navigation Bar', 'salient'),
             'bottom-of-menu-item' => esc_html__('Bottom of Menu Item Label', 'salient')
           ),
           'default' => 'default'
         ),
         array(
          'id' => 'header-dropdown-delay',
          'type' => 'select',
          'title' => esc_html__('Header Dropdown Delay', 'salient'),
          'subtitle' => esc_html__('Determine if dropdowns should appear after a small delay to prevent accidental user interaction.', 'salient'),
          'desc' => '',
          'options' => array(
            'default' => esc_html__('Default', 'salient'),
            'add' => esc_html__('Add Delay', 'salient')
          ),
          'default' => 'default'
        ),
         array(
           'id' => 'header-dropdown-animation',
           'type' => 'select',
           'title' => esc_html__('Header Dropdown Animation', 'salient'),
           'subtitle' => esc_html__('Select the animation to use when dropdowns are revealed.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Default', 'salient'),
             'fade-in-up' => esc_html__('Fade In Up', 'salient'),
             'fade-in' => esc_html__('Fade In', 'salient')
           ),
           'default' => 'default'
         ),
         array(
           'id' => 'header-dropdown-border-radius',
           'type'      => 'slider',
           'title'     => esc_html__('Header Dropdown Roundness', 'salient'),
           'subtitle'  => esc_html__('This allows you to round the corners of your dropdowns. (Amount to round in px)', 'salient'),
           'desc'      => '',
           "default"   => 0,
           "min"       => 0,
           "step"      => 1,
           "max"       => 20,
           'display_value' => 'label'
         ),
         array(
           'id' => 'header-dropdown-box-shadow',
           'type' => 'select',
           'title' => esc_html__('Header Dropdown Box Shadow', 'salient'),
           'subtitle' => esc_html__('Please select your dropdown box shadow here.', 'salient'),
           'desc' => '',
           'options' => array(
             'small' => esc_html__('Small', 'salient'),
             'large' => esc_html__('Large', 'salient'),
             'large-alt' => esc_html__('Large Alt', 'salient'),
             'none' => esc_html__('None', 'salient')
           ),
           'default' => 'large'
         ),
         array(
           'id' => 'header-megamenu-width',
           'type' => 'select',
           'title' => esc_html__('Header Mega Menu Width', 'salient'),
           'subtitle' => esc_html__('Please choose whether you would like your megamenu to be constrained to the same width of the header container or if you would prefer to be the full width of the page.', 'salient'),
           'desc' => '',
           'options' => array(
             'contained' => esc_html__('Contained To Header Item Width', 'salient'),
             'full-width' => esc_html__('Full Screen Width', 'salient')
           ),
           'default' => 'contained'
         ),

         array(
           'id' => 'header-megamenu-remove-transparent',
           'type' => 'switch',
           'title' => esc_html__('Megamenu Removes Transparent Header', 'salient'),
           'subtitle' => esc_html__('This will cause your header navigation to temporarily disable the transparent effect when your megamenu is open', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

       )
     ) );

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Header Search', 'salient' ),
       'id'               => 'header-nav-search',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'header-disable-search',
           'type' => 'checkbox',
           'title' => esc_html__('Remove Header search', 'salient'),
           'subtitle' => esc_html__('Active to remove the search functionality from your header', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'header-disable-ajax-search',
           'type' => 'checkbox',
           'title' => esc_html__('Disable AJAX from search', 'salient'),
           'subtitle' => esc_html__('This will turn off the autocomplete suggestions from appearing when typing in the search box.', 'salient'),
           'desc' => '',
           'default' => '1'
         ),
         array(
           'id' => 'header-ajax-search-style',
           'type' => 'select',
           'title' => esc_html__('AJAX search styling', 'salient'),
           'subtitle' => esc_html__('Select how to display the search results as the user types.', 'salient') .'<br/>'.esc_html__('the "Extended List" option relies on the "Material" theme skin and will enable that skin, even if it is not selected.','salient'),
           'desc' => '',
           'required' => array(  array('header-disable-ajax-search', '!=', '1') ),
           'options' => array(
             'default' => esc_html__('Simple List', 'salient'),
             'extended' => esc_html__('Extended List', 'salient'),
           ),
           'default' => 'default'
         ),
         array(
           'id' => 'header-search-limit',
           'type' => 'select',
           'title' => esc_html__('Limit Search To Post Type', 'salient'),
           'subtitle' => esc_html__('If you would like the search to only show results of a specific post type, select the post type here.', 'salient'),
           'desc' => '',
           'options' => array(
             'any' => esc_html__('All', 'salient'),
             'product' => esc_html__('Products', 'salient'),
             'post' => esc_html__('Posts', 'salient'),
             'portfolio' => esc_html__('Portfolio', 'salient')
           ),
           'default' => 'all'
         ),
         array(
           'id' => 'header-search-type',
           'type' => 'select',
           'title' => esc_html__('Header Search Typography', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Default', 'salient'),
             'header_nav' => esc_html__('Inherit Navigation Typography', 'salient')
           ),
           'default' => 'default'
         ),
         array(
           'id'        => 'header-search-type-size',
           'type'      => 'slider',
           'title'     => esc_html__('Search Font Size', 'salient'),
           'subtitle'  => esc_html__('Set a custom font size (in px) for the header search navigation.', 'salient'),
           'desc'      => '',
           "default"   => 48,
           "min"       => 20,
           "step"      => 1,
           "max"       => 80,
           'display_value' => 'text'
         ),
         array(
           'id' => 'header-search-remove-bt',
           'type' => 'switch',
           'title' => esc_html__('Remove Search Bottom Helper Text', 'salient'),
           'subtitle' => esc_html__('On some theme skins, helper text is shown under the main search bar. Enable this to remove that.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'header-search-ph-text',
           'type' => 'text',
           'title' => esc_html__('Search Placeholder Text', 'salient'),
           'subtitle' => esc_html__('Define custom text to show to the user when opening the search, before they start typing. Leave blank to use theme skin default.', 'salient'),
           'desc' => '',
           'default' => ''
         ),
       )
     ) );

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Off Canvas Menu', 'salient' ),
       'id'               => 'header-nav-off-canvas-navigation',
       'subsection'       => true,
       'fields'           => array(

         array(
           'id' => 'header-slide-out-widget-area',
           'type' => 'switch',
           'title' => esc_html__('Off Canvas Menu', 'salient'),
           'subtitle' => esc_html__('This will add an off canvas menu button on all viewports to your header navigation. When this is disabled, the off canvas menu will only be visible on mobile devices.', 'salient'),
           'desc' => '',
           'required' => array(  array('header-slide-out-widget-area-style', '!=', 'simple') ),
           'default' => '1'
         ),

         array(
           'id' => 'header-slide-out-widget-area-style',
           'type' => 'select',
           'title' => esc_html__('Off Canvas Menu Style', 'salient'),
           'subtitle' => esc_html__('Please select your off canvas menu style here. The "Slide Out From Right Hover Triggered" style will force the "Full Width Header" option regardless of your selection.', 'salient'),
           'desc' => '',
           'options' => array(
             'slide-out-from-right' => esc_html__('Slide Out From Side', 'salient'),
             'slide-out-from-right-hover' => esc_html__('Slide Out From Side Hover Triggered', 'salient'),
             'fullscreen' => esc_html__('Fullscreen Cover Slide + Blur BG', 'salient'),
             'fullscreen-alt' => esc_html__('Fullscreen Cover Fade', 'salient'),
             'fullscreen-inline-images' => esc_html__('Fullscreen Inline with Dynamic BG', 'salient'),
             'fullscreen-split' => esc_html__('Fullscreen Cover Split', 'salient'),
             'simple' => esc_html__('Simple Dropdown', 'salient')
           ),
           'default' => 'slide-out-from-right',
         ),

         array(
          'id' => 'header-slide-out-widget-area-separate-mobile',
          'type' => 'switch',
          'title' => esc_html__('Off Canvas Menu Separate Mobile Menu', 'salient'),
          'subtitle' => esc_html__('This will cause your off canvas to only display navigation menu items assigned to the "Off Canvas Navigation Menu" location when viewing on a mobile device.', 'salient'),
          'desc' => '',
          'required' => array(  array('header-slide-out-widget-area', '!=', '1') ),
          'default' => '0'
        ),

         array(
          'id' => 'fullscreen-inline-images-default',
          'type' => 'media',
          'required' => array(  array('header-slide-out-widget-area-style', '=', 'fullscreen-inline-images') ),
          'title' => esc_html__('Default Off Canvas Background Image', 'salient'),
          'subtitle' => esc_html__('Choose the default image to be shown in your Off Canvas Menu. You can also supply a unique item for each menu item in','salient') . ' <a href="'.esc_url( admin_url('nav-menus.php') ). '">'. esc_html__('Appearance > Menus.', 'salient') . '</a>',
          'desc' => ''
        ),

         array(
           'id' => 'header-slide-out-widget-area-dropdown-behavior',
           'type' => 'select',
           'title' => esc_html__('Off Canvas Menu Dropdown Behavior', 'salient'),
           'subtitle' => esc_html__('Please select the functionality for how dropdowns will behave in your off canvas menu.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Dropdown Parent Link Toggles Submenu', 'salient'),
             'separate-dropdown-parent-link' => esc_html__('Separate Dropdown Parent Link From Dropdown Toggle', 'salient')
           ),
           'default' => 'separate-dropdown-parent-link',
           'required' => array(  array('header-slide-out-widget-area-style', '!=', 'fullscreen'), array('header-slide-out-widget-area-style', '!=', 'fullscreen-inline-images' ), array('header-slide-out-widget-area-style', '!=', 'fullscreen-alt' ), array('header-slide-out-widget-area-style', '!=', 'simple' ) ),
         ),

         array(
          'id' => 'header-slide-out-from-right-simplify-mobile',
          'type' => 'switch',
          'title' => esc_html__('Simplify Animation/Style of OCM on Mobile', 'salient'),
          'subtitle' => esc_html__('The "Slide Out From Side" animation can be difficult for some mobile devices to render correctly. You can use this to offer a simplified version when accessed from a mobile device.', 'salient'),
          'desc' => '',
          'required' => array(  array('header-slide-out-widget-area-style', '=', 'slide-out-from-right'), array( 'theme-skin', '=', 'material') ),
          'default' => '0'
        ),
         array(
           'id' => 'header-menu-label',
           'type' => 'switch',
           'title' => esc_html__('Off Canvas Menu Add Menu Label', 'salient'),
           'subtitle' => esc_html__('This will add a "Menu" label next to your off canvas link.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
          'id' => 'ocm_btn_position',
          'type' => 'select',
          'title' => esc_html__('Off Canvas Menu Button Position', 'salient'),
          'subtitle' => esc_html__('Please select the positioning for your off canvas menu.', 'salient'),
          'desc' => '',
          'required' => array(  array('header_format', '!=', 'centered-logo-between-menu'), array('header_format', '!=', 'centered-menu-under-logo'), array('header-slide-out-widget-area-style', '!=', 'simple') ),
          'options' => array(
            'default' => esc_html__('Default', 'salient'),
            'left' => esc_html__('Left', 'salient'),
          ),
          'default' => 'default',
        ),

         array(
           'id' => 'header-slide-out-widget-area-social',
           'type' => 'switch',
           'title' => esc_html__('Off Canvas Menu Add Social', 'salient'),
           'subtitle' => esc_html__('This will add the social links you have links set for in the "Social Media" tab to your off canvas menu.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'header-slide-out-widget-area-bottom-text',
           'type' => 'text',
           'title' => esc_html__('Off Canvas Menu Bottom Text', 'salient'),
           'subtitle' => esc_html__('This will add some text fixed at the bottom of your off canvas menu - useful for copyright or quick contact info etc.', 'salient'),
           'desc' => '',
           'default' => ''
         ),
         array(
           'id' => 'header-slide-out-widget-area-overlay-opacity',
           'type' => 'select',
           'title' => esc_html__('Off Canvas Menu Overlay Strength', 'salient'),
           'subtitle' => esc_html__('Please select your off canvas menu overlay strength here.', 'salient'),
           'desc' => '',
           'options' => array(
             'solid' => esc_html__('Solid', 'salient'),
             'dark' => esc_html__('Dark', 'salient'),
             'medium' => esc_html__('Medium', 'salient'),
             'light' => esc_html__('Light', 'salient')
           ),
           'default' => 'dark',
           'required' => array(  array('header-slide-out-widget-area-style', '!=', 'simple') )
         ),
         array(
           'id' => 'header-slide-out-widget-area-top-nav-in-mobile',
           'type' => 'switch',
           'title' => esc_html__('Off Canvas Menu Mobile Nav Menu items', 'salient'),
           'subtitle' => esc_html__('This will cause your off canvas menu to inherit any navigation items assigned in your "Top Navigation" menu location when viewing on a mobile device.', 'salient') . '<br/><br/>' . esc_html__('Enabled by default in the Material theme skin.','salient'),
           'desc' => '',
           'required' => array(  array('header-slide-out-widget-area-style', '!=', 'simple'), array('header-slide-out-widget-area', '!=', '0') ),
           'default' => '0'
         ),
         array(
           'id' => 'header-slide-out-widget-area-icons-display',
           'type' => 'select',
           'title' => esc_html__('Off Canvas Menu Item Icons', 'salient'),
           'subtitle' => esc_html__('This will control what type of icons (if any) to display in your off canvas menu. Icons are defined by you on an individual menu item basis in Appearance > Menus.', 'salient'),
           'desc' => '',
           'options' => array(
             'none' => esc_html__('Display No Icons', 'salient'),
             'font_icons_only' => esc_html__('Display Font/Emoji Icons Only', 'salient'),
             'image_icons_only' => esc_html__('Display Image Icons Only', 'salient'),
             'all' => esc_html__('Display All Icons', 'salient'),
           ),
           'default' => 'none'
         ),
         array(
           'id' => 'header-slide-out-widget-area-image-display',
           'type' => 'select',
           'title' => esc_html__('Off Canvas Menu Item Images', 'salient'),
           'subtitle' => esc_html__('This will control how to display menu items which have an image set. Removing images will simply your menu items to their default mobile state. Menu Item images are defined by you on an individual menu item basis in Appearance > Menus.', 'salient'),
           'desc' => '',
           'options' => array(
             'remove_images' => esc_html__('Remove Images', 'salient'),
             'default' => esc_html__('Display Images', 'salient'),
           ),
           'default' => 'remove_images'
         ),
         array(
           'id' => 'header-slide-out-widget-area-icon-style',
           'type' => 'select',
           'title' => esc_html__('Off Canvas Icon Style', 'salient'),
           'subtitle' => esc_html__('Please select your off canvas header icon style.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Default', 'salient'),
             'circular' => esc_html__('Circular', 'salient')
           ),
           'default' => 'default',
           'required' => array(  array('header-menu-label', '!=', '1') )
         ),
         array(
           'id' => 'header-slide-out-widget-area-menu-btn-bg-color',
           'type' => 'color',
           'title' => esc_html__('Off Canvas Navigation Menu Button BG Color', 'salient'),
           'desc' => '',
           'transparent' => false,
           'subtitle' => esc_html__('Optionally define a background color for your off canvas navigation button within the header. Useful in combination with the "Permanent Transparent" navigation option.', 'salient'),
           'default' => ''
         ),
         array(
           'id' => 'header-slide-out-widget-area-menu-btn-color',
           'type' => 'color',
           'title' => esc_html__('Off Canvas Navigation Menu Button Color', 'salient'),
           'desc' => '',
           'transparent' => false,
           'default' => ''
         ),

         array(
          'id' => 'header-slide-out-widget-area-custom-font-size',
          'type' => 'text',
          'title' => esc_html__('Off Canvas Menu Custom Font Size (Desktop)', 'salient'),
          'subtitle' => esc_html__('Optionally specify a custom font size to use for your off canvas navigation menu items when viewed on desktop displays. All unit types are accepted.', 'salient'),
          'desc' => '',
          'default' => ''
        ),
        array(
          'id' => 'header-slide-out-widget-area-custom-font-size-mobile',
          'type' => 'text',
          'title' => esc_html__('Off Canvas Menu Custom Font Size (Mobile)', 'salient'),
          'subtitle' => esc_html__('Optionally specify a custom font size to use for your off canvas navigation menu items when viewed on mobile displays. All unit types are accepted.', 'salient'),
          'desc' => '',
          'default' => ''
        ),

       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Mobile Header', 'salient' ),
       'id'               => 'header-nav-mobile-menu',
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'mobile-menu-layout',
           'type' => 'image_select',
           'title' => esc_html__('Mobile Header Layout', 'salient'),
           'subtitle' => esc_html__('Please select the layout you desire.', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => array('title' => esc_html__('Default Layout','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/mobile-default.jpg'),
             'centered-menu' => array('title' => esc_html__('Centered Menu','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/mobile-centered.jpg'),
           ),
           'default' => 'default'
         ),
         array(
           'id' => 'header-mobile-fixed',
           'type' => 'switch',
           'title' => esc_html__('Header Sticky On Mobile', 'salient'),
           'subtitle' => esc_html__('Do you want the header to be sticky on mobile devices?', 'salient'),
           'desc' => '',
           'switch' => true,
           'default' => '1'
         ),

         array(
           'id'        => 'header-menu-mobile-breakpoint',
           'type'      => 'slider',
           'title'     => esc_html__('Header Mobile Breakpoint', 'salient'),
           'subtitle'  => esc_html__('Define at what window size (in px) the header navigation menu will collapse into the mobile menu style - larger values are useful when you have a navigation with many items which wouldn\'t fit on one line when viewed on small desktops/laptops.', 'salient'),
           'desc'      => '',
           "default"   => 1000,
           "min"       => 1000,
           "step"      => 2,
           "max"       => 6000,
           'display_value' => 'text'
         ),

       )
     ) );

     Redux::setSection( $opt_name, array(
      'title'            => esc_html__( 'Color Scheme', 'salient' ),
      'id'               => 'header-nav-color-scheme',
      'subsection'       => true,
      'fields'           => array(

        array(
          'id' => 'header-color',
          'type' => 'select',
          'title' => esc_html__('Header Color Scheme', 'salient'),
          'subtitle' => esc_html__('Please select your header color scheme here. Color pickers below will only be used when using "Custom" for the color scheme.', 'salient'),
          'desc' => '',
          'options' => array(
            'light' => esc_html__('Light', 'salient'),
            'dark' => esc_html__('Dark', 'salient'),
            'custom' => esc_html__('Custom', 'salient')
          ),
          'hint' => array('content' => esc_html__('Salient will use the accent color with the light/dark schemes. To create your own color scheme and use the color pickers below, ensure that you choose','salient') . ' <strong>'. esc_html__('Custom','salient').'</strong>.', 'title' => ''),
          'default' => 'light'
        ),

        array(
          'id' => 'header-navigation-main-start',
          'type' => 'section',
          'class' => 'salient-color-grid',
          'title' => __('Main Navigation', 'salient'),
          'indent' => true,
          'subtitle' => __('Inactive - Select "Custom" for your header color scheme above to activate custom coloring.', 'salient'),
        ),
        array(
          'id' => 'header-background-color',
          'type' => 'color',
          'title' => esc_html__('Header Background', 'salient'),
          'subtitle' => '',
          'desc' => '',
          'transparent' => false,
          'default' => '#ffffff'
        ),
        array(
          'id' => 'header-font-color',
          'type' => 'color',
          'title' => esc_html__('Header Font', 'salient'),
          'subtitle' => '',
          'class' => 'no-border',
          'transparent' => false,
          'desc' => esc_html__('Default State', 'salient'),
          'default' => '#888888'
        ),
        array(
          'id' => 'header-font-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'transparent' => false,
          'desc' => esc_html__('Hover State', 'salient'),
          'class' => 'hover-state',
          'default' => '#3452ff'
        ),

        array(
          'id' => 'header-font-button-bg',
          'type' => 'color',
          'title' => esc_html__('Header Button Effect', 'salient'),
          'subtitle' => '',
          'transparent' => false,
          'class' => 'no-border',
          'desc' => esc_html__('BG Default State', 'salient'),
          'default' => '#eeeeee',
          'required' => array( 'header-hover-effect', '=', 'button_bg' ),
        ),
        array(
          'id' => 'header-font-button-bg-active',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'transparent' => false,
          'class' => 'hover-state no-border-middle',
          'desc' => esc_html__('BG Active State', 'salient'),
          'default' => '#000000',
          'required' => array( 'header-hover-effect', '=', 'button_bg' ),
        ),
        array(
          'id' => 'header-font-button-text-active',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'transparent' => false,
          'class' => 'hover-state',
          'desc' => esc_html__('Text Active State', 'salient'),
          'default' => '#ffffff',
          'required' => array( 'header-hover-effect', '=', 'button_bg' ),
        ),

        array(
          'id' => 'header-icon-color',
          'type' => 'color',
          'title' => esc_html__('Header Menu Item Icons', 'salient'),
          'subtitle' => '',
          'transparent' => false,
          'desc' => '',
          'default' => '#888888'
        ),
        array(
          'id'     => 'header-navigation-main-end',
          'type'   => 'section',
          'indent' => false,
         ),


        array(
          'id' => 'header-secondary-start',
          'type' => 'section',
          'class' => 'salient-color-grid',
          'title' => __('Secondary Navigation', 'salient'),
          'indent' => true,
          'subtitle' => __('Inactive - Select "Custom" for your header color scheme above to activate custom coloring.', 'salient'),
        ),
        array(
          'id' => 'secondary-header-background-color',
          'type' => 'color',
          'title' => esc_html__('Secondary Header Background', 'salient'),
          'subtitle' => '',
          'desc' => '',
          'transparent' => false,
          'default' => '#F8F8F8'
        ),
        array(
          'id' => 'secondary-header-font-color',
          'type' => 'color',
          'title' => esc_html__('Secondary Header Font', 'salient'),
          'subtitle' => '',
          'class' => 'no-border',
          'transparent' => false,
          'desc' => esc_html__('Default State', 'salient'),
          'default' => '#666666'
        ),
        array(
          'id' => 'secondary-header-font-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'transparent' => false,
          'desc' => esc_html__('Hover State', 'salient'),
          'class' => 'hover-state',
          'default' => '#222222'
        ),
        array(
          'id'     => 'header-secondary-end',
          'type'   => 'section',
          'indent' => false,
         ),



         array(
          'id' => 'header-navigation-dropdown-start',
          'type' => 'section',
          'indent' => true, 
          'class' => 'salient-color-grid',
          'title' => __('Dropdown & Megamenu', 'salient'),
          'subtitle' => __('Inactive - Select "Custom" for your header color scheme above to activate custom coloring.', 'salient'),
        ),
        array(
          'id' => 'header-dropdown-background-color',
          'type' => 'color',
          'title' => esc_html__('Dropdown Background', 'salient'),
          'class' => 'no-border',
          'transparent' => false,
          'subtitle' => '',
          'desc' => esc_html__('Default State', 'salient'),
          'default' => '#1F1F1F'
        ),
        array(
          'id' => 'header-dropdown-background-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'transparent' => false,
          'desc' => esc_html__('Hover State', 'salient'),
          'class' => 'hover-state',
          'default' => '#313233'
        ),
        array(
          'id' => 'header-dropdown-font-color',
          'type' => 'color',
          'title' => esc_html__('Dropdown Font', 'salient'),
          'subtitle' => '',
          'class' => 'no-border',
          'transparent' => false,
          'desc' => esc_html__('Default State', 'salient'),
          'default' => '#CCCCCC'
        ),
        array(
          'id' => 'header-dropdown-font-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'desc' => esc_html__('Hover State', 'salient'),
          'class' => 'hover-state',
          'transparent' => false,
          'default' => '#3452ff'
        ),
        array(
          'id' => 'header-dropdown-icon-color',
          'type' => 'color',
          'title' => esc_html__('Dropdown Menu Item Icons', 'salient'),
          'subtitle' => '',
          'transparent' => false,
          'desc' => '',
          'default' => '#3452ff'
        ),
        array(
          'id' => 'header-dropdown-desc-font-color',
          'type' => 'color',
          'title' => esc_html__('Dropdown Description Font', 'salient'),
          'subtitle' => '',
          'desc' => esc_html__('Default State', 'salient'),
          'class' => 'no-border',
          'transparent' => false,
          'default' => '#CCCCCC'
        ),
        array(
          'id' => 'header-dropdown-desc-font-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'class' => 'hover-state',
          'desc' => esc_html__('Hover State', 'salient'),
          'transparent' => false,
          'default' => '#ffffff'
        ),
        array(
          'id' => 'header-dropdown-heading-font-color',
          'type' => 'color',
          'title' => esc_html__('Mega Menu Heading Font', 'salient'),
          'subtitle' => '',
          'class' => 'no-border',
          'transparent' => false,
          'desc' => esc_html__('Default State', 'salient'),
          'default' => '#ffffff'
        ),
        array(
          'id' => 'header-dropdown-heading-font-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'transparent' => false,
          'class' => 'hover-state',
          'desc' => esc_html__('Hover State', 'salient'),
          'default' => '#ffffff'
        ),
        array(
          'id' => 'header-separator-color',
          'type' => 'color',
          'title' => esc_html__('Header Separators', 'salient'),
          'subtitle' => '',
          'transparent' => false,
          'desc' => '',
          'default' => '#eeeeee'
        ),
        array(
          'id'     => 'header-navigation-dropdown-end',
          'type'   => 'section',
          'indent' => false,
         ),



        array(
          'id' => 'header-off-canvas-section-start',
          'type' => 'section',
          'title' => __('Off Canvas Menu', 'salient'),
          'indent' => true,
          'class' => 'salient-color-grid',
          'subtitle' => __('Inactive - Select "Custom" for your header color scheme above to activate custom coloring.', 'salient'),
        ),
        array(
          'id' => 'header-slide-out-widget-area-background-color',
          'type' => 'color',
          'title' => esc_html__('Off Canvas Navigation Background', 'salient'),
          'subtitle' => '',
          'desc' => '',
          'class' => 'no-border',
          'transparent' => false,
          'default' => '#3452ff'
        ),
        array(
          'id' => 'header-slide-out-widget-area-background-color-2',
          'type' => 'color',
          'title' => esc_html__('Off Canvas Navigation Background 2', 'salient'),
          'subtitle' => esc_html__('Used for gradient', 'salient'),
          'desc' => '',
          'transparent' => false,
          'default' => ''
        ),
        array(
          'id' => 'header-slide-out-widget-area-header-color',
          'type' => 'color',
          'title' => esc_html__('Off Canvas Navigation Headers', 'salient'),
          'subtitle' => '',
          'transparent' => false,
          'desc' => '',
          'default' => '#ffffff'
        ),
        array(
          'id' => 'header-slide-out-widget-area-color',
          'type' => 'color',
          'title' => esc_html__('Off Canvas Navigation Text', 'salient'),
          'subtitle' => '',
          'class' => 'no-border',
          'transparent' => false,
          'desc' => esc_html__('Default State', 'salient'),
          'default' => '#eefbfa'
        ),
        array(
          'id' => 'header-slide-out-widget-area-hover-color',
          'type' => 'color',
          'title' => '',
          'subtitle' => '',
          'class' => 'hover-state',
          'transparent' => false,
          'desc' => esc_html__('Hover State', 'salient'),
          'default' => '#ffffff'
        ),
        array(
          'id' => 'header-slide-out-widget-area-close-bg-color',
          'type' => 'color',
          'title' => esc_html__('Off Canvas Navigation Close Button Background', 'salient'),
          'subtitle' => '',
          'required' => array( 'theme-skin', '=', 'material') ,
          'desc' => '',
          'transparent' => false,
          'default' => '#ff1053'
        ),
        array(
          'id' => 'header-slide-out-widget-area-close-icon-color',
          'type' => 'color',
          'title' => esc_html__('Off Canvas Navigation Close Button Icon', 'salient'),
          'subtitle' => '',
          'required' => array( 'theme-skin', '=', 'material') ,
          'desc' => '',
          'transparent' => false,
          'default' => '#ffffff'
        ),
        array(
          'id'     => 'header-off-canvas-section-end',
          'type'   => 'section',
          'indent' => false,
         ),

      )
    ) );


     $global_sections_arr = array();

     if( true === $salient_on_theme_options_page_bool ) {
       $global_sections_query = get_posts(
         array(
           'posts_per_page' => -1,
           'post_status'    => 'publish',
           'ignore_sticky_posts' => true,
           'no_found_rows'  => true,
           'post_type'      => 'salient_g_sections'
         )
       );

       foreach( $global_sections_query as $section ) {
         if( property_exists( $section, 'post_title') && property_exists( $section, 'ID') ) {
           $global_sections_arr[$section->ID] = $section->post_title;
         }
       }

     }

     Redux::setSection( $opt_name, array(
       'title'  => esc_html__( 'Global Sections', 'salient' ),
       'id'     => 'global-sections',
       'desc'   => esc_html__( 'Use this area to assign templates from your ','salient') . '<a href="'.esc_url( admin_url('edit.php?post_type=salient_g_sections') ).'">' . esc_html__('Global Sections','salient') . '</a> ' . esc_html__('to various locations within Salient.','salient'),
       'icon'   => 'el el-website',
       'fields' => array(
        array(
          'id'    => 'info_success',
          'type'  => 'info',
          'class' => 'nectar-announcement',
          'style' => 'success',
          'title' => esc_html__('New Functionality Available', 'salient'),
          'icon'  => 'el-icon-info-sign',
          'desc'  => esc_html__( 'As of v16, Global Sections can now be assigned to locations from within each section settings with greater control over the display.', 'salient') . ' <a target="_blank" rel="noopener noreferrer" href="https://themenectar.com/docs/salient/page-builder-global-sections/#locations">' .esc_html__('See documentation for more information.','salient') . '</a>'
        ),
         array(
           'id' => 'global-section-after-header-navigation',
           'type' => 'select',
           'title' => esc_html__('After Header Navigation', 'salient'),
           'subtitle' => esc_html__('The area below your header navigation bar, before all page content. Note that assigning a template to this area will disable the Transparent Header Effect.', 'salient'),
           'options' => $global_sections_arr
         ),
         array(
           'id' => 'global-section-above-footer',
           'type' => 'select',
           'title' => esc_html__('After Page Content', 'salient'),
           'subtitle' => esc_html__('The area above your footer, after all page content.', 'salient'),
           'options' => $global_sections_arr
         ),

       )
     ) );


     Redux::setSection( $opt_name, array(
       'title'  => esc_html__( 'Footer', 'salient' ),
       'id'     => 'footer',
       'desc'   => esc_html__( 'All footer related options are listed here.', 'salient' ),
       'icon'   => 'el el-file',
       'fields' => array(
         array(
           'id' => 'enable-main-footer-area',
           'type' => 'switch',
           'title' => esc_html__('Main Footer Area', 'salient'),
           'subtitle' => esc_html__('Do you want use the main footer that contains all the widgets areas?', 'salient'),
           'desc' => '',
           'default' => '1'
         ),

         array(
           'id' => 'footer_columns',
           'type' => 'image_select',
           'required' => array( 'enable-main-footer-area', '=', '1' ),
           'title' => esc_html__('Footer Columns', 'salient'),
           'subtitle' => esc_html__('Please select the number of columns you would like for your footer. Note: using the 1 Column layout will also center the copyright area.', 'salient'),
           'desc' => '',
           'options' => array(
             '1' => array('title' => esc_html__('1 Column Centered','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/1colg.png'),
             '2' => array('title' => esc_html__('2 Columns','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/2col.png'),
             '3' => array('title' => esc_html__('3 Columns','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/3col.png'),
             '4' => array('title' => esc_html__('4 Columns','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/4col.png'),
             '5' => array('title' => esc_html__('4 Columns Alt','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/4colalt.png')
           ),
           'default' => '4'
         ),

         array(
           'id' => 'footer-custom-color',
           'type' => 'switch',
           'title' => esc_html__('Custom Footer Color Scheme', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'footer-background-color',
           'type' => 'color',
           'title' => '',
           'subtitle' => esc_html__('Footer Background Color', 'salient'),
           'desc' => '',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'class' => 'five-columns always-visible',
           'default' => '#313233',
           'transparent' => false
         ),

         array(
           'id' => 'footer-font-color',
           'type' => 'color',
           'title' => '',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'subtitle' => esc_html__('Footer Font Color', 'salient'),
           'class' => 'five-columns always-visible',
           'desc' => '',
           'default' => '#CCCCCC',
           'transparent' => false
         ),

         array(
           'id' => 'footer-secondary-font-color',
           'type' => 'color',
           'title' => '',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'subtitle' => esc_html__('2nd Footer Font Color', 'salient'),
           'class' => 'five-columns always-visible',
           'desc' => '',
           'default' => '#777777',
           'transparent' => false
         ),

         array(
           'id' => 'footer-copyright-background-color',
           'type' => 'color',
           'title' => '',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'class' => 'five-columns always-visible',
           'subtitle' => esc_html__('Copyright Background Color', 'salient'),
           'desc' => '',
           'default' => '#1F1F1F',
           'transparent' => false
         ),

         array(
           'id' => 'footer-copyright-font-color',
           'type' => 'color',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'title' => '',
           'class' => 'five-columns always-visible',
           'subtitle' => esc_html__('Footer Copyright Font Color', 'salient'),
           'desc' => '',
           'default' => '#777777',
           'transparent' => false
         ),
         array(
           'id' => 'footer-copyright-icon-hover-color',
           'type' => 'color',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'title' => '',
           'class' => 'five-columns always-visible',
           'subtitle' => esc_html__('Footer Copyright Icon Hover Color', 'salient'),
           'desc' => '',
           'default' => '#ffffff',
           'transparent' => false
         ),
         array(
           'id' => 'footer-copyright-border-color',
           'type' => 'color',
           'required' => array( 'footer-custom-color', '=', '1' ),
           'title' => '',
           'subtitle' => esc_html__('Footer Copyright Top Border Color', 'salient'),
           'desc' => '',
           'default' => '',
           'transparent' => false
         ),
         array(
           'id' => 'footer-copyright-line',
           'type' => 'switch',
           'title' => esc_html__('Footer Add Line Above Copyright', 'salient'),
           'subtitle' => esc_html__('This will add a thin line to separate your footer widget area from the copyright section', 'salient'),
           'default' => ''
         ),

         array(
           'id' => 'footer-full-width',
           'type' => 'switch',
           'title' => esc_html__('Footer Full Width', 'salient'),
           'subtitle' => esc_html__('This to cause your footer content to display full width.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),


         array(
           'id' => 'footer-reveal',
           'type' => 'switch',
           'title' => esc_html__('Footer Reveal Effect', 'salient'),
           'subtitle' => esc_html__('This to cause the footer to appear as though it\'s being reveal by the main content area when scrolling down to it', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'footer-reveal-shadow',
           'type' => 'select',
           'required' => array( 'footer-reveal', '=', '1' ),
           'title' => esc_html__('Footer Reveal Shadow', 'salient'),
           'subtitle' => esc_html__('Please select the type of shadow you would like to appear on your footer', 'salient'),
           'options' => array(
             "none" => esc_html__("None", "salient"),
             "small" => esc_html__("Small", "salient"),
             "large" => esc_html__("Large", "salient"),
             "large_2" => esc_html__("Large & same color as footer BG", "salient"),
           ),
           'default' => 'none'
         ),

         array(
           'id' => 'footer-link-hover',
           'type' => 'select',
           'title' => esc_html__('Footer Link Hover Effect', 'salient'),
           'subtitle' => esc_html__('Please select the hover effect you would like for links in your footer copyright area.', 'salient'),
           'options' => array(
             "default" => esc_html__("Opacity/Color Change", "salient"),
             "underline" => esc_html__("Animated Underline", "salient")
           ),
           'default' => 'default'
         ),
         array(
           'id' => 'footer-copyright-layout',
           'type' => 'select',
           'title' => esc_html__('Footer Copyright Layout', 'salient'),
           'subtitle' => esc_html__('Please select the layout you would like for your footer copyright area.', 'salient'),
           'options' => array(
             "default" => esc_html__("Determined by Footer Columns", "salient"),
             "centered" => esc_html__("Centered", "salient")
           ),
           'default' => 'default'
         ),

         array(
           'id' => 'disable-copyright-footer-area',
           'type' => 'switch',
           'title' => esc_html__('Disable Footer Copyright Area', 'salient'),
           'subtitle' => esc_html__('This will hide the copyright bar in your footer', 'salient'),
           'desc' => '',
           'default' => ''
         ),

         array(
           'id' => 'footer-copyright-text',
           'type' => 'text',
           'title' => esc_html__('Footer Copyright Section Text', 'salient'),
           'subtitle' => esc_html__('Please enter the copyright section text. e.g. All Rights Reserved, Salient Inc.', 'salient'),
           'desc' => ''
         ),

         array(
           'id' => 'disable-auto-copyright',
           'type' => 'switch',
           'title' => esc_html__('Disable Automatic Copyright', 'salient'),
           'subtitle' => esc_html__('By default, your copyright section will say "© {YEAR} {SITENAME}" before the additional text you add above in the Footer Copyright Section Text input - This option allows you to remove that.', 'salient'),
           'desc' => ''
         ),

         array(
           'id' => 'footer-background-image',
           'type' => 'media',
           'title' => esc_html__('Footer Background Image', 'salient'),
           'subtitle' => esc_html__('Upload an image that will be used as the background image on your footer. ', 'salient'),
           'desc' => ''
         ),

         array(
           'id'        => 'footer-background-image-overlay',
           'type'      => 'slider',
           'title'     => esc_html__('Footer Background Overlay', 'salient'),
           'subtitle'  => esc_html__('Adjust the overlay opacity here - the overlay colors pulls from your defined footer background color.', 'salient'),
           'desc'      => '',
           "default"   => 0.8,
           "min"       => 0,
           "step"      => 0.1,
           "max"       => 1,
           "resolution" => 0.1,
           'display_value' => 'text'
         ),

         array(
           'id' => 'use-facebook-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Facebook Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-twitter-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Twitter Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
          'id' => 'use-x-twitter-icon',
          'type' => 'checkbox',
          'title' => esc_html__('Use X-Twitter Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
         array(
           'id' => 'use-google-plus-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Google Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-vimeo-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Vimeo Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-dribbble-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Dribbble Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-pinterest-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Pinterest Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-youtube-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Youtube Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-tumblr-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Tumblr Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-linkedin-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use LinkedIn Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-rss-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use RSS Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-behance-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Behance Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-instagram-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Instagram Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-flickr-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Flickr Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-spotify-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Spotify Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-github-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use GitHub Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-stackexchange-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use StackExchange Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-soundcloud-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use SoundCloud Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-vk-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use VK Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-vine-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Vine Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-houzz-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Houzz Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-yelp-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Yelp Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-snapchat-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Snapchat Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-mixcloud-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Mixcloud Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-bandcamp-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Bandcamp Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-tripadvisor-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Tripadvisor Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-telegram-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Telegram Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-slack-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Slack Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-medium-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Medium Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-artstation-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Artstation Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-discord-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Discord Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-whatsapp-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use WhatsApp Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-messenger-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Messenger Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-tiktok-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use TikTok Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-twitch-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Twitch Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-applemusic-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Apple Music Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
          'id' => 'use-patreon-icon',
          'type' => 'checkbox',
          'title' => esc_html__('Use Patreon Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
        array(
          'id' => 'use-trustpilot-icon',
          'type' => 'checkbox',
          'title' => esc_html__('Use Trustpilot Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
        array(
          'id' => 'use-mastodon-icon',
          'type' => 'checkbox',
          'title' => esc_html__('Use Mastodon Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
        array(
          'id' => 'use-threads-icon',
          'type' => 'checkbox',
          'title' => esc_html__('Use Threads Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
         array(
          'id' => 'use-xing-icon',
          'type' => 'checkbox',
          'title' => esc_html__('Use Xing Icon', 'salient'),
          'subtitle' => '',
          'desc' => ''
        ),
         array(
           'id' => 'use-email-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Email Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         ),
         array(
           'id' => 'use-phone-icon',
           'type' => 'checkbox',
           'title' => esc_html__('Use Phone Icon', 'salient'),
           'subtitle' => '',
           'desc' => ''
         )
       )
     ) );





     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Page Transitions', 'salient' ),
       'id'               => 'page_transitions',
       'desc'             => esc_html__( 'All page transition options are listed here.', 'salient' ),
       'customizer_width' => '400px',
       'icon'   => 'el el-refresh',
       'fields' => array(

         array(
           'id' => 'ajax-page-loading',
           'type' => 'switch',
           'title' => esc_html__('Animated Page Transitions', 'salient'),
           'subtitle' => esc_html__('This will enable an animation between loading your pages.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'disable-transition-fade-on-click',
           'type' => 'switch',
           'title' => esc_html__('Disable Fade Out On Click', 'salient'),
           'subtitle' => esc_html__('This will disable the default functionality of your page fading out when clicking a link with the Standard transition method. Is useful if your page transitions are conflicting with third party plugins that take over certain anchors such as lighboxes.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'disable-transition-on-mobile',
           'type' => 'switch',
           'title' => esc_html__('Disable Page Transitions On Mobile', 'salient'),
           'subtitle' => esc_html__('This will remove page transitions when viewed on a mobile device (produces faster loading)', 'salient'),
           'desc' => '',
           'required' => array( 'ajax-page-loading', '=', '1' ),
           'default' => '1'
         ),

         array(
           'id' => 'transition-effect',
           'type' => 'select',
           'title' => esc_html__('Transition Effect', 'salient'),
           'subtitle' => esc_html__('Please select your transition effect here', 'salient'),
           'options' => array(
             "standard" => esc_html__('Fade with loading icon', 'salient'),
             "center_mask_reveal" => esc_html__('Center mask reveal', 'salient'),
             "horizontal_swipe_basic" => esc_html__('Horizontal basic swipe', 'salient'),
             "horizontal_swipe" => esc_html__('Horizontal multi layer swipe', 'salient')
           ),
           'default' => 'standard'
         ),

         array(
           'id' => 'loading-icon',
           'type' => 'select',
           'required' => array( 'transition-effect', '=', 'standard' ),
           'title' => esc_html__('Loading Icon Style', 'salient'),
           'subtitle' => esc_html__('Select your loading icon style here', 'salient'),
           'options' => array(
             "default" => esc_html__('Default', 'salient'),
             "material" => esc_html__('Material Design', 'salient')
           ),
           'default' => 'material'
         ),
         array(
           'id' => 'loading-icon-colors',
           'type' => 'color_gradient',
           'transparent' => false,
           'title' => esc_html__('Loading Icon Coloring', 'salient'),
           'subtitle' => esc_html__('The icon will animate between the two colors - or just use the first if a second is not supplied.', 'salient'),
           'desc' => '',
           'required' => array( 'loading-icon', '=', 'material' ),
           'default'  => array(
             'from' => '#3452ff',
             'to'   => '#3452ff'
           ),
         ),
         array(
           'id' => 'loading-image',
           'type' => 'media',
           'required' => array( 'transition-effect', '=', 'standard' ),
           'title' => esc_html__('Custom Loading Image', 'salient'),
           'subtitle' => esc_html__('Upload a .png or .gif image that will be used in all applicable areas on your site as the loading image. ', 'salient'),
           'desc' => ''
         ),
         array(
           'id' => 'loading-image-animation',
           'type' => 'select',
           'required' => array( 'transition-effect', '=', 'standard' ),
           'title' => esc_html__('Loading Image CSS Animation', 'salient'),
           'subtitle' => esc_html__('This will add a css based animation onto your defined image', 'salient'),
           'options' => array(
             "none" => esc_html__('Default', 'salient'),
             "spin" => esc_html__('Smooth Spin', 'salient')
           ),
           'default' => 'none'
         ),
         array(
           'id' => 'transition-bg-color',
           'type' => 'color',
           'title' => esc_html__('Page Transition BG Color', 'salient'),
           'subtitle' =>  esc_html__('Use this to define the color of your page transition background.', 'salient'),
           'desc' => '',
           'default' => '',
           'transparent' => false
         ),
         array(
           'id' => 'transition-bg-color-2',
           'type' => 'color',
           'title' => esc_html__('Page Transition BG Color 2', 'salient'),
           'subtitle' =>  esc_html__('Use this to define the second color of your page transition background.', 'salient'),
           'desc' => '',
           'default' => '',
           'required' => array( 'transition-effect', '=', 'horizontal_swipe' ),
           'transparent' => false
         )


       )
     ) );




     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Page Header', 'salient' ),
       'id'               => 'page_header',
       'desc'             => esc_html__( 'All global page header options are listed here. (there are also many options located in your page header metabox available on every edit page screen which are configured on a per-page basis', 'salient' ),
       'customizer_width' => '400px',
       'icon'   => 'el el-file',
       'fields' => array(

         array(
           'id' => 'header-auto-title',
           'type' => 'switch',
           'title' => esc_html__('Automatically Add Page Title to Page Header', 'salient'),
           'subtitle' => esc_html__('Convenient if you are transitioning an existing WP site to Salient to avoid having to manually add in page titles into the Page Header Settings metabox.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
         array(
           'id' => 'header-auto-title-bg-color',
           'type' => 'color',
           'title' => esc_html__('Auto Page Header: Background Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'default' => '',
           'required' => array( 'header-auto-title', '=', '1' ),
           'transparent' => false
         ),
         array(
           'id' => 'header-auto-title-text-color',
           'type' => 'color',
           'title' => esc_html__('Auto Page Header: Text Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'default' => '',
           'required' => array( 'header-auto-title', '=', '1' ),
           'transparent' => false
         ),
         array(
           'id' => 'header-auto-title-use-featured-img',
           'type' => 'switch',
           'title' => esc_html__('Auto Page Header: Use Featured Images', 'salient'),
           'subtitle' => esc_html__('Adds featured images to the page header background.','salient'),
           'desc' => '',
           'required' => array( 'header-auto-title', '=', '1' ),
           'default' => '0'
         ),
         array(
           'id' => 'header-auto-title-overlay-color',
           'type' => 'color',
           'title' => esc_html__('Auto Page Header: Overlay Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'default' => '',
           'required' => array( 'header-auto-title-use-featured-img', '=', '1' ),
           'transparent' => false
         ),

         array(
           'id' => 'header-animate-in-effect',
           'type' => 'select',
           'title' => esc_html__('Load In Animation', 'salient'),
           'subtitle' => esc_html__('Page headers refer to any page header set in the page header meta box.', 'salient') . '<br/> <br/> <strong>' . esc_html__('None:','salient') . '</strong> ' . esc_html__('No animation will occur (default).', 'salient') .' <br/> <strong>'. esc_html__('Slide down:', 'salient'). '</strong> '. esc_html__('Will apply for all non full screen page headers.','salient') . '<br/> <strong>'.esc_html__('Slight zoom out:','salient') .'</strong>'. esc_html__('Will apply to all page headers that have an image/video set (bg color only won\'t show the effect).', 'salient'),
           'options' => array(
             "none" => esc_html__('None', 'salient'),
             "slide-down" => esc_html__('Slide Down', 'salient'),
             "zoom-out" => esc_html__('Slight Zoom Out', 'salient'),
             "fade-in" => esc_html__('Fade In', 'salient')
           ),
           'default' => 'none'
         ),

         array(
           'id' => 'header-down-arrow-style',
           'type' => 'select',
           'title' => esc_html__('Down Arrow Style', 'salient'),
           'subtitle' => esc_html__('Page headers that are set to fullscreen will show an arrow at the bottom so the user knows there is more content below - select the style for that here.', 'salient'),
           'options' => array(
             "default" => esc_html__("Default", 'salient'),
             "scroll-animation" => esc_html__("Scroll Animation", 'salient'),
             "animated-arrow" => esc_html__("Animated Arrow", 'salient')
           ),
           'default' => 'default'
         ),


       )
     ) );




     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Form Styling', 'salient' ),
       'id'               => 'form_styling',
       'desc'             => esc_html__( 'All form styling options are listed here.', 'salient' ),
       'customizer_width' => '400px',
       'icon'             => 'el el-edit',
       'fields' => array(

         array(
           'id' => 'form-style',
           'type' => 'select',
           'title' => esc_html__('Overall Form Style', 'salient'),
           'subtitle' => esc_html__('Sets the style of all form elements used.', 'salient'),
           'hint' => array('content' => esc_html__('If you\'re trying to get third party forms to display without any styling from Salient, simply select the', 'salient') . ' <b>' . esc_html__('Inherit', 'salient') .'</b> ' . esc_html__('option.', 'salient'), 'title' => ''),
           'options' => array(
             "default" => esc_html__("Inherit", 'salient'),
             "minimal" => esc_html__("Minimal", 'salient')
           ),
           'default' => 'default'
         ),

         array(
           'id' => 'form-fancy-select',
           'type' => 'switch',
           'title' => esc_html__('Enable Fancy Select Styling', 'salient'),
           'subtitle' => esc_html__('This will add additional styling and functionality to your select (dropdown) elements.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),

         array(
           'id' => 'form-submit-btn-style',
           'type' => 'select',
           'title' => esc_html__('Form Submit Button Style', 'salient'),
           'subtitle' => esc_html__('Select your desired style which will be used for submit buttons throughout your site', 'salient'),
           'desc' => '',
           'options' => array(
             'default' => esc_html__('Default', 'salient'),
             'regular' => esc_html__('Nectar Btn', 'salient'),
             'see-through' => esc_html__('Nectar Btn See Through', 'salient')
           ),
           'default' => 'regular'
         ),
         array(
            'id'             => 'form-submit-spacing',
            'type'           => 'spacing',
            'mode'           => 'padding',
            'units'          => array('em', 'px'),
            'left'           => false,
            'bottom'         => false,
            'units_extended' => 'false',
            'title'          => esc_html__('Form Submit Button Padding', 'salient'),
            'subtitle'       => esc_html__('Fine-tune form submit button padding. If left blank, defaults to 15px for top/bottom and 20px for left/right.', 'salient'),
            'desc'           => '',
            'default'            => array(
                'padding-top'     => '',
                'padding-right'   => '',
                'units'          => 'px',
            )
        ),
        array(
          'id'        => 'form-input-font-size',
          'type'      => 'slider',
          'title'     => esc_html__('Form Input Field Text Size', 'salient'),
          'subtitle'  => esc_html__('Alters the font size for form input field/textarea elements.', 'salient'),
          'desc'      => '',
          "default"   => 14,
          "min"       => 14,
          "step"      => 1,
          "max"       => 30,
          'display_value' => 'text'
        ),
        array(
           'id'             => 'form-input-spacing',
           'type'           => 'spacing',
           'mode'           => 'padding',
           'left'           => false,
           'bottom'         => false,
           'units'          => array('em', 'px'),
           'units_extended' => 'false',
           'title'          => esc_html__('Form Input Field Padding', 'salient'),
           'subtitle'       => esc_html__('Fine-tune form input fields/textarea element padding. If left blank, defaults to 10px for all sides.', 'salient'),
           'desc'           => '',
           'default'            => array(
               'padding-top'     => '',
               'padding-right'   => '',
               'units'          => 'px',
           )
       ),
       array(
         'id' => 'form-input-border-width',
         'type' => 'select',
         'title' => esc_html__('Form Input Border Width', 'salient'),
         'subtitle' => esc_html__('Fine-tune the border width of form inputs.', 'salient'),
         'options' => array(
           "default" => esc_html__("Default", 'salient'),
           "1px" => esc_html__("1px", 'salient'),
           "2px" => esc_html__("2px", 'salient'),
           "3px" => esc_html__("3px", 'salient'),
         ),
         'default' => 'default'
       ),

       array(
         'id' => 'form-input-bg-color',
         'type' => 'color',
         'title' => esc_html__('Form Input BG Color', 'salient'),
         'subtitle' =>  esc_html__('Use this to define the background color of form inputs', 'salient'),
         'desc' => '',
         'default' => '',
         'transparent' => false
       ),
       array(
         'id' => 'form-input-text-color',
         'type' => 'color',
         'title' => esc_html__('Form Input Text Color', 'salient'),
         'subtitle' =>  esc_html__('Use this to define the text color of form inputs', 'salient'),
         'desc' => '',
         'default' => '',
         'transparent' => false
       ),
       array(
         'id' => 'form-input-border-color',
         'type' => 'color',
         'title' => esc_html__('Form Input Border Color', 'salient'),
         'subtitle' =>  esc_html__('Use this to define the border color of form inputs', 'salient'),
         'desc' => '',
         'default' => '',
         'transparent' => false
       ),
       array(
         'id' => 'form-input-border-color-hover',
         'type' => 'color',
         'title' => esc_html__('Form Input Border Hover Color', 'salient'),
         'subtitle' =>  esc_html__('Use this to define the border color on hover of form inputs', 'salient'),
         'desc' => '',
         'default' => '',
         'transparent' => false
       ),


       )
     ) );



     $using_cta_option = ( isset($options['cta-text']) && !empty($options['cta-text']) ) ? true : false;

     if( true === $using_cta_option && true === $salient_on_theme_options_page_bool ) {
       $exclude_cta_option = array(
         'id' => 'exclude_cta_pages',
         'title' => esc_html__('Pages to Exclude the Call to Action Section', 'salient'),
         'subtitle' => esc_html__('Select any pages you wish to exclude the Call to Action section from. You can select multiple pages.', 'salient'),
         'args' => array(
           'sort_order' => 'ASC'
         ),
         'desc' => '',
         'type'     => 'select',
         'data'     => 'pages',
         'multi'    => true,
       );
     } else {
       $exclude_cta_option = array(
         'id' => 'exclude_cta_pages',
         'title' => esc_html__('Pages to Exclude the Call to Action Section', 'salient'),
         'subtitle' => esc_html__('Select any pages you wish to exclude the Call to Action section from. You can select multiple pages.', 'salient'),
         'args' => array(
           'sort_order' => 'ASC'
         ),
         'desc' => '',
         'type'     => 'select',
         'options'  => array(
           '0' => 'None'
         ),
         'multi' => true,
       );
     }

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Call To Action', 'salient' ),
       'id'               => 'cta',
       'desc'             => esc_html__( 'All call to action options are listed here.', 'salient' ),
       'customizer_width' => '400px',
       'icon'             => 'el el-bell',
       'fields' => array(

         array(
           'id' => 'cta-text',
           'type' => 'text',
           'title' => esc_html__('Call to Action Text', 'salient'),
           'subtitle' => esc_html__('Add the text that you would like to appear in the global call to action section.', 'salient'),
           'desc' => ''
         ),
         array(
           'id' => 'cta-btn',
           'type' => 'text',
           'title' => esc_html__('Call to Action Button Text', 'salient'),
           'subtitle' => esc_html__('If you would like a button to be the link in the global call to action section, please enter the text for it here.', 'salient'),
           'desc' => ''
         ),
         array(
           'id' => 'cta-btn-link',
           'type' => 'text',
           'title' => esc_html__('Call to Action Button Link URL', 'salient'),
           'subtitle' => esc_html__('Please enter the URL for the call to action section here.', 'salient'),
           'desc' => ''
         ),
         $exclude_cta_option,
         array(
           'id' => 'cta-background-color',
           'type' => 'color',
           'title' => esc_html__('Call to Action Background Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'default' => '#ECEBE9',
           'transparent' => false
         ),

         array(
           'id' => 'cta-text-color',
           'type' => 'color',
           'title' => esc_html__('Call to Action Font Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'default' => '#4B4F52',
           'transparent' => false
         ),

         array(
           'id' => 'cta-btn-color',
           'type' => 'select',
           'title' => esc_html__('Call to Action Button Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'options' => array(
             'accent-color' => esc_html__('Accent Color', 'salient'),
             'extra-color-1' => esc_html__('Extra Color 1', 'salient'),
             'extra-color-2' => esc_html__('Extra Color 2', 'salient'),
             'extra-color-3' => esc_html__('Extra Color 3', 'salient'),
             'see-through' => esc_html__('See Through', 'salient')
           ),
           'default' => 'accent-color'
         )


       )
     ) );




     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Portfolio', 'salient' ),
       'id'               => 'portfolio',
       'desc'             => '',
       'customizer_width' => '400px',
       'icon'   => 'el el-th',
       'fields' => array(

       )
     ) );

     $nectar_salient_portfolio_active_str = '';

     if( !class_exists('Salient_Portfolio') ) {
       $nectar_salient_portfolio_active_str = '<span class="salient-plugin-notice"><b>'. __('"Salient Portfolio" Plugin Not Active.', 'salient') . '</b> ' . '<a href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ). '">' . esc_html__('Click here to go to the plugin install page.', 'salient') . '</a></span>';
     }

     Redux::setSection( $opt_name, array(
       'title'            => esc_html__( 'Archive', 'salient' ),
       'id'               => 'portfolio-style',
       'desc'             => esc_html__( 'Options that affect the styling of the portfolio archive pages (portfolio grid).', 'salient' ) . ' ' . wp_kses_post($nectar_salient_portfolio_active_str),
       'subsection'       => true,
       'fields'           => array(
         array(
           'id' => 'main_portfolio_layout',
           'type' => 'image_select',
           'title' => esc_html__('Main Layout', 'salient'),
           'subtitle' => esc_html__('Please select the number of columns you would like for your portfolio.', 'salient'),
           'desc' => '',
           'options' => array(
             '2' => array('title' => '2 Columns', 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/2col.png'),
             '3' => array('title' => '3 Columns', 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/3col.png'),
             '4' => array('title' => '4 Columns', 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/4col.png'),
             'fullwidth' => array('title' => 'Full Width', 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/fullwidth.png')
           ),
           'default' => '3'
         ),
         array(
           'id' => 'main_portfolio_project_style',
           'type' => 'radio',
           'title' => esc_html__('Project Style', 'salient'),
           'subtitle' => esc_html__('Please select the style you would like your projects to display in on your portfolio pages.', 'salient'),
           'desc' => '',
           'options' => array(
             '1' => esc_html__('Meta below thumb w/ links on hover', 'salient'),
             '2' => esc_html__('Meta on hover + entire thumb link', 'salient'),
             '7' => esc_html__('Meta on hover w/ zoom + entire thumb link', 'salient'),
             '8' => esc_html__('Meta overlaid - bottom left aligned', 'salient'),
             '3' => esc_html__("Title overlaid w/ zoom effect on hover", 'salient'),
             '5' => esc_html__("Title overlaid w/ zoom effect on hover alt", 'salient'),
             '4' => esc_html__("Meta from bottom on hover + entire thumb link", 'salient'),
             '6' => esc_html__("Meta + 3D Parallax on hover", 'salient') ,
             '9' => esc_html__('Meta below thumb w/ shadow hover', 'salient')
           ),
           'default' => '1'
         ),
         array(
           'id' => 'portfolio_date',
           'type' => 'checkbox',
           'title' => esc_html__('Display Dates on Projects', 'salient'),
           'subtitle' => esc_html__('Toggle whether or not to show the date on your projects within your portfolio grids - note that if a custom excerpt is supplied for a specific project, it will be shown instead of the date.', 'salient'),
           'desc' => '',
           'switch' => true,
           'default' => '1'
         ),
         array(
           'id' => 'main_portfolio_item_spacing',
           'type' => 'select',
           'title' => esc_html__('Project Item Spacing', 'salient'),
           'subtitle' => esc_html__('Please select the spacing you would like between your items', 'salient'),
           'desc' => '',
           'options' => array(
             "default" => "Default",
             "1px" => "1px",
             "2px" => "2px",
             "3px" => "3px",
             "4px" => "4px",
             "5px" => "5px",
             "6px" => "6px",
             "7px" => "7px",
             "8px" => "8px",
             "9px" => "9px",
             "10px" => "10px",
             "15px" => "15px",
             "20px" => "20px"
           ),
           'default' => 'default'
         ),

         array(
           'id' => 'portfolio_use_masonry',
           'type' => 'switch',
           'title' => esc_html__('Masonry Style', 'salient'),
           'subtitle' => esc_html__('This will allow your portfolio items to display in a masonry layout as opposed to a fixed grid. You can define your masonry sizes in each project. If using the full width layout, will only be active with the alternative project style.', 'salient'),
           'desc' => '',
           'switch' => true,
           'default' => '0'
         ),
         array(
           'id' => 'portfolio_masonry_grid_sizing',
           'type' => 'select',
           'title' => esc_html__('Masonry Grid Sizing', 'salient'),
           'subtitle' => esc_html__('Please select the grid layout for your masonry portfolio. This will change the dimensions of the "Masonry Item Sizing" field you choose for your projects in the project configuration metabox. After changing this, you will need to run the','salient') . ' <a target="_blank" rel="nofollow" href="//wordpress.org/plugins/regenerate-thumbnails/">'. esc_html__('regenerate thumbnails','salient') . '</a> ' . esc_html__('plugin to recrop any featured images that are already uploaded. You must upload your images at a minimum of these dimensions or larger - uploading smaller than the size chosen will result in an incorrect layout.','salient') . '<br/> <strong class="top-margin">'. esc_html__('Square Based Grid','salient') . '</strong><br/><table class="masonry_table"><tr><th>' . esc_html__('Masonry Size','salient') . '</th><th>'. esc_html__('Dimensions','salient') . '</th></tr><tr><td>' . esc_html__('Regular','salient') . '</td><td>500x500</td></tr><tr><td>' . esc_html__('Wide','salient'). '</td><td>1000x500</td></tr><tr><td>' . esc_html__('Tall','salient') . '</td><td>1000x500</td></tr><tr><td>' . esc_html__('Wide & Tall','salient') . '</td><td>1000x1000</td></tr></table>    <strong>'. esc_html__('Photography Based','salient') . '</strong><br/><table class="masonry_table"><tr><th>' . esc_html__('Masonry Size','salient') . '</th><th>' . esc_html__('Dimensions','salient') . '</th></tr><tr><td>'. esc_html__('Regular','salient') . '</td><td>450x600</td></tr><tr><td>' . esc_html__('Wide','salient'). '</td><td>900x600</td></tr><tr><td>' . esc_html__('Wide & Tall','salient') . '</td><td>900x1200</td></tr></table>',
             'desc' => '',
             'options' => array(
               "default" => esc_html__("Square Grid Based (Default)", 'salient'),
               "photography" => esc_html__("Photography Based", 'salient')
             ),
             'default' => 'default'
           ),
           array(
             'id' => 'portfolio_inline_filters',
             'type' => 'switch',
             'title' => esc_html__('Display Filters Horizontally', 'salient'),
             'subtitle' => esc_html__('This will allow your filters to display horizontally instead of in a dropdown.', 'salient'),
             'desc' => '',
             'switch' => true,
             'default' => '0'
           ),
           array(
             'id' => 'portfolio_loading_animation',
             'type' => 'select',
             'title' => esc_html__('Load In Animation', 'salient'),
             'subtitle' => esc_html__('Please select the loading animation you would like', 'salient'),
             'desc' => '',
             'options' => array(
               "none" => esc_html__("None", 'salient'),
               "fade_in" => esc_html__("Fade In", 'salient'),
               "fade_in_from_bottom" => esc_html__("Fade In From Bottom", 'salient'),
               "perspective" => esc_html__("Perspective Fade In", 'salient')
             ),
             'default' => 'fade_in_from_bottom'
           ),
         )
       ) );

       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Single Project', 'salient' ),
         'id'               => 'portfolio-single-project',
         'subsection'       => true,
         'desc'             => esc_html__( 'Options for the single project template.', 'salient' ) . ' ' . wp_kses_post($nectar_salient_portfolio_active_str),
         'fields'           => array(
           array(
             'id' => 'single_portfolio_project_layout',
             'type' => 'image_select',
             'title' => esc_html__('Single Project Layout', 'salient'),
             'subtitle' => esc_html__('Please select the layout to use for your single project template.', 'salient') . '<br/><br/>' . esc_html__('By default, Salient will use a basic predetermined layout for the single project template. If you wish to have more design flexibility, select the','salient') . ' <strong>'. esc_html__('Full Width Page Builder','salient').'</strong> ' . esc_html__('option and your single projects will behave like regular pages.','salient'),
             'desc' => '',
             'options' => array(
               'basic' => array('title' => esc_html__('Basic With Sidebar','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'redux-framework/ReduxCore/assets/img/project-default.jpg'),
               'page_builder' => array('title' => esc_html__('Full Width Page Builder','salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'redux-framework/ReduxCore/assets/img/project-page-builder.jpg'),
             ),
             'default' => 'basic'
           ),
           array(
             'id' => 'portfolio_single_nav',
             'type' => 'radio',
             'title' => esc_html__('Single Project Page Navigation', 'salient'),
             'subtitle' => esc_html__('Please select the navigation you would like your projects to use.', 'salient'),
             'desc' => '',
             'options' => array(
               'in_header' => esc_html__('In Project Header', 'salient'),
               'after_project' => esc_html__('At Bottom Of Project', 'salient'),
               'after_project_2' => esc_html__('At Bottom W/ Featured Image', 'salient'),
               'after_project_next_only' => esc_html__('At Bottom W/ Featured Image Next Only', 'salient')
             ),
             'default' => 'after_project'
           ),

           array(
            'id' => 'portfolio_single_nav_order',
            'type' => 'select',
            'title' => esc_html__('Project Navigation Ordering', 'salient'),
            'subtitle' => esc_html__('Please select how you would like your next/previous post links to function.', 'salient'),
            'desc' => '',
            'hint' => array('content' => '<strong>'. esc_html__('Default:','salient') . '</strong> '. esc_html__('the next project link will be the next','salient') . ' <i>'. esc_html__('oldest','salient') .'</i> ' . esc_html__('project.','salient') . '<br/> <strong>' . esc_html__('Reverse Order:','salient') .'</strong> ' . esc_html__('the next project link will be the next', 'salient') . ' <i>'. esc_html__('newest', 'salient') . '</i> ' . esc_html__('project.','salient'), 'title' => ''),
            'options' => array(
              "default" => esc_html__("Default", 'salient'),
              "reverse" => esc_html__("Reverse Order", 'salient')
            ),
            'default' => 'default'
          ),

          array(
            'id' => 'after_project_next_only_last_link',
            'type' => 'select',
            'title' => esc_html__('Project Navigation Last Project Link', 'salient'),
            'subtitle' => esc_html__('When viewing the final project in your portfolio, choose what to link to in the bottom Project Navigation.', 'salient'),
            'desc' => '',
            'required' => array( array('portfolio_single_nav', '=', 'after_project_next_only'), array('portfolio_same_category_single_nav','!=','1') ),
            'options' => array(
              "default" => esc_html__("Previous Project", 'salient'),
              "first_project" => esc_html__("First Project (Circular Loop)", 'salient')
            ),
            'default' => 'default'
          ),
           
           array(
             'id' => 'portfolio_remove_single_header',
             'type' => 'switch',
             'title' => esc_html__('Single Project Remove Default Header', 'salient'),
             'subtitle' => esc_html__('This will deactivate the default portfolio header from displaying on your single project pages.', 'salient'),
             'desc' => '',
             'switch' => true,
             'default' => '0'
           ),
           array(
             'id' => 'portfolio_sidebar_follow',
             'type' => 'switch',
             'title' => esc_html__('Portfolio Sidebar Follow on Scroll', 'salient'),
             'subtitle' => esc_html__('When supplying extra content, a sidebar enabled page can get quite tall and feel empty on the right side. Enable this option to have your sidebar follow you down the page.', 'salient'),
             'desc' => '',
             'required' => array( 'single_portfolio_project_layout', '!=', 'page_builder' ),
             'switch' => true,
             'default' => '0'
           ),
           array(
             'id' => 'portfolio_remove_comments',
             'type' => 'switch',
             'title' => esc_html__('Remove Comment Functionality On Projects', 'salient'),
             'subtitle' => esc_html__('Enable this to globally disable commenting on your single project layout', 'salient'),
             'desc' => '',
             'default' => '0',
           ),
           array(
            'id' => 'portfolio_same_category_single_nav',
            'type' => 'switch',
            'title' => esc_html__('Single Project Nav Arrows Limited To Same Category', 'salient'),
            'subtitle' => esc_html__('This will cause your single project page next/prev arrows to lead only to projects that exist in the same category as the current.', 'salient'),
            'desc' => '',
            'default' => '0'
          ),
          

         )

       ) );



       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Functionality', 'salient' ),
         'id'               => 'portfolio-functionality',
         'subsection'       => true,
         'fields'           => array(

           array(
             'id'    => 'portfolio_social',
             'type'  => 'info',
             'style' => 'success',
             'title' => esc_html__('Portfolio Social Sharing Options', 'salient'),
             'icon'  => 'el-icon-info-sign',
             'desc'  => esc_html__( 'As of Salient v10.1 the Portfolio social settings have been moved into WordPress customizer (Appearance > Customize). Ensure that you have the "Salient Social" plugin installed and activated to use them.', 'salient')
           ),

           array(
             'id' => 'portfolio_pagination',
             'type' => 'switch',
             'title' => esc_html__('Portfolio Pagination', 'salient'),
             'subtitle' => esc_html__('Would you like your portfolio items to be paginated?', 'salient'),
             'desc' => '',
             'default' => '0',
           ),
           array(
             'id' => 'portfolio_pagination_type',
             'type' => 'select',
             'title' => esc_html__('Pagination Type', 'salient'),
             'subtitle' => esc_html__('Please select your pagination type here.', 'salient'),
             'desc' => '',
             'required' => array( 'portfolio_pagination', '=', '1' ),
             'options' => array(
               'default' => esc_html__('Default', 'salient'),
               'infinite_scroll' => esc_html__('Infinite Scroll', 'salient')
             ),
             'default' => 'default'
           ),
           array(
             'id' => 'portfolio_extra_pagination',
             'type' => 'switch',
             'required' => array( 'portfolio_pagination', '=', '1' ),
             'title' => esc_html__('Display Pagination Numbers', 'salient'),
             'subtitle' => esc_html__('Do you want the page numbers to be visible in your portfolio pagination?', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'portfolio_pagination_number',
             'type' => 'text',
             'required' => array( 'portfolio_pagination', '=', '1' ),
             'title' => esc_html__('Items Per page', 'salient'),
             'subtitle' => esc_html__('How many of your portfolio items would you like to display per page?', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),

           array(
             'id' => 'portfolio_rewrite_slug',
             'type' => 'text',
             'title' => esc_html__('Custom Slug', 'salient'),
             'subtitle' => esc_html__('If you want your portfolio post type to have a custom slug in the url, please enter it here. You will still have to refresh your permalinks after saving this! This is done by going to Settings > Permalinks and clicking save.', 'salient'),
             'desc' => ''
           ),

           array(
             'id' => 'portfolio-sortable-text',
             'type' => 'text',
             'title' => esc_html__('Custom Portfolio Page Sortable Text', 'salient'),
             'subtitle' => esc_html__('e.g. Sort Portfolio', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'main-portfolio-link',
             'type' => 'text',
             'title' => esc_html__('Main Portfolio Page URL', 'salient'),
             'subtitle' => esc_html__('This will be used to link back to your main portfolio from the more details page and for the recent projects link. i.e. The portfolio page that you are displaying all project categories on.', 'salient'),
             'desc' => ''
           ),
           
           array(
             'id' => 'carousel-title',
             'type' => 'text',
             'title' => esc_html__('Custom Recent Projects Title', 'salient'),
             'subtitle' => esc_html__('This is be used anywhere you place the recent work shortcode and on the "Recent Work" home layout. e.g. Recent Work', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'carousel-link',
             'type' => 'text',
             'title' => esc_html__('Custom Recent Projects Link Text', 'salient'),
             'subtitle' => esc_html__('This is be used anywhere you place the recent work shortcode and on the "Recent Work" home layout. e.g. View All Work', 'salient'),
             'desc' => ''
           ),

         )
       ) );



       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Blog', 'salient' ),
         'id'               => 'blog',
         'desc'             => esc_html__( 'All blog options are listed here.', 'salient' ),
         'customizer_width' => '400px',
         'icon'             => 'el el-list',
         'fields' => array(




         )
       ) );


      $using_post_grid_archive = false;
      $post_grid_special_id = '';
      if ( NectarThemeManager::is_special_location_active('nectar_special_location__blog_loop') ) {
        $using_post_grid_archive = true;
        $post_grid_special_id = NectarThemeManager::is_special_location_active('nectar_special_location__blog_loop');
      }

      $blog_layout_options = array(
        array(
          'id' => 'blog_type_post_grid',
          'class' => $using_post_grid_archive ? '' : 'hidden-theme-option',
          'type' => 'select',
          'title' => esc_html__('Blog Format', 'salient'),
          'subtitle' => esc_html__('Please select your blog format here.', 'salient') . '<br/><br/><strong>' . esc_html__('Note: Your blog archive design is currently being handled through a', 'salient') .' <a target="_blank" href="'.esc_url( admin_url( 'post.php?post='.$post_grid_special_id.'&action=edit' ) ).'">'.esc_html__('global section.', 'salient').'</a></strong>',
          'desc' => '',
          'options' => array(
            'contained' => esc_html__('Contained', 'salient'),
            'contained-sidebar' => esc_html__('Contained + sidebar', 'salient'),
            'fullwidth' => esc_html__('Fullwidth', 'salient')
          ),
          'default' => 'contained'
        ),

        array(
          'id' => 'blog_type',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'type' => 'select',
          'title' => esc_html__('Blog Type', 'salient'),
          'subtitle' => esc_html__('Please select your blog format here.', 'salient'),
          'desc' => '',
          'options' => array(
            'std-blog-sidebar' => esc_html__('Standard Blog W/ Sidebar', 'salient'),
            'std-blog-fullwidth' => esc_html__('Standard Blog No Sidebar', 'salient'),
            'masonry-blog-sidebar' => esc_html__('Masonry Blog W/ Sidebar', 'salient'),
            'masonry-blog-fullwidth' => esc_html__('Masonry Blog No Sidebar', 'salient'),
            'masonry-blog-full-screen-width' => esc_html__('Masonry Blog Fullwidth', 'salient')
          ),
          'default' => 'masonry-blog-sidebar'
        ),
        array(
          'id' => 'blog_standard_type',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'type' => 'radio',
          'title' => esc_html__('Standard Blog Style', 'salient'),
          'subtitle' => esc_html__('Please select the style you would like your posts to use when the standard layout is displayed', 'salient'),
          'desc' => '',
          'options' => array(
            'classic' => esc_html__('Classic', 'salient'),
            'minimal' => esc_html__('Minimal', 'salient'),
            'featured_img_left' => esc_html__('Featured Image Left', 'salient')
          ),
          'default' => 'featured_img_left',
          'required' => array( 'blog_type', 'contains', 'std-blog' )
        ),
        array(
          'id' => 'blog_masonry_type',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'type' => 'radio',
          'title' => esc_html__('Masonry Style', 'salient'),
          'subtitle' => esc_html__('Please select the style you would like your posts to use when the masonry layout is displayed', 'salient'),
          'desc' => '',
          'hint' => array('content' => esc_html__('Hint: Auto Masonry based layouts load the fastest. This is because the layouts are calculated with pure CSS and do not rely on any scripting.', 'salient'), 'title' => ''),
          'options' => array(
            'classic' => esc_html__('Classic', 'salient'),
            'classic_enhanced' => esc_html__('Classic Enhanced', 'salient'),
            'material' =>  esc_html__('Material', 'salient'),
            'meta_overlaid' => esc_html__('Meta Overlaid', 'salient'),
            'auto_meta_overlaid_spaced' => esc_html__('Auto Masonry: Meta Overlaid Spaced', 'salient')
          ),
          'default' => 'auto_meta_overlaid_spaced'
        ),

        array(
          'id' => 'blog_auto_masonry_spacing',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'type' => 'select',
          'title' => esc_html__('Auto Masonry Spacing', 'salient'),
          'subtitle' => esc_html__('Please select the amount of spacing you would like for your auto masonry layout', 'salient'),
          'desc' => '',
          'options' => array(
            '4px' => '4px',
            '8px' => '8px',
            '12px' => '12px',
            '16px' => '16px',
            '20px' => '20px',
          ),
          'default' => '8px',
          'required' => array( 'blog_masonry_type', '=', 'auto_meta_overlaid_spaced' )
        )
      );
       

       $blog_styling_options = array(
        array(
          'id' => 'blog_loading_animation',
          'type' => 'select',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'title' => esc_html__('Archive Load In Animation', 'salient'),
          'subtitle' => esc_html__('Please select the loading animation you would like for your posts.', 'salient'),
          'desc' => '',
          'options' => array(
            "none" => esc_html__("None", 'salient'),
            "fade_in" => esc_html__("Fade In", 'salient'),
            "fade_in_from_bottom" => esc_html__("Fade In From Bottom", 'salient'),
            "perspective" => esc_html__("Perspective Fade In", 'salient')
          ),
          'default' => 'fade_in_from_bottom'
        ),

        
        array(
          'id' => 'blog_enable_ss',
          'type' => 'switch',
          'title' => esc_html__('Enable Sticky Sidebar', 'salient'),
          'subtitle' => esc_html__('Would you like to have your sidebar follow down as your scroll in a sticky manner?', 'salient'),
          'desc' => '',
          'default' => '0',
        ),

        array(
          'id' => 'blog_auto_excerpt',
          'type' => 'switch',
          'title' => esc_html__('Automatic Post Excerpts', 'salient'),
          'subtitle' => esc_html__('Using this will create automatic excerpts for your posts.', 'salient'),
          'desc' => '',
          'default' => '1'
        ),
        
        array(
          'id' => 'blog_excerpt_length',
          'type' => 'text',
          'required' => array( 'blog_auto_excerpt', '=', '1' ),
          'title' => esc_html__('Excerpt Length', 'salient'),
          'subtitle' => esc_html__('How many words would you like to display for your post excerpts? The default is 30.', 'salient'),
          'desc' => ''
        ),
        
       );


       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'General Settings', 'salient' ),
         'id'               => 'Blog-style',
         'subsection'       => true,
         'fields'           => array_merge($blog_layout_options, $blog_styling_options)
       ) );

       Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Single Post', 'salient' ),
        'id'               => 'blog-single-post',
        'subsection'       => true,
        'fields'           => array(
          

          array(
            'id' => 'blog_hide_sidebar',
            'type' => 'switch',
            'title' => esc_html__('Hide Sidebar on Single Post', 'salient'),
            'subtitle' => esc_html__('Using this will remove the sidebar from appearing on your single post page.', 'salient'),
            'desc' => '',
            'default' => '1'
          ),
          array(
            'id' => 'blog_width',
            'type' => 'select',
            'title' => esc_html__('Blog Content Width', 'salient'),
            'options' => array(
              'default' => esc_html__('Default', 'salient'),
              '1000px' => esc_html__('1000px', 'salient'),
              '900px' => esc_html__('900px', 'salient'),
              '800px' => esc_html__('800px', 'salient'),
              '700px' => esc_html__('700px', 'salient')
            ),
            'required' => array( 'blog_hide_sidebar', '=', '1' ),
            'default' => 'default'
          ),
          array(
           'id' => 'blog_full_width_row_func',
           'type' => 'select',
           'title' => esc_html__('Blog Full Width Row Functionality', 'salient'),
           'options' => array(
             'default' => esc_html__('Expand To Edges Of Screen', 'salient'),
             'limit' => esc_html__('Limited To Max Container Width', 'salient'),
           ),
           'subtitle' => esc_html__('Allows you to limit the overall max width of page builder rows set to Full Width Content/Full Width Background on non-mobile viewports. When setting to limit, the width will match what you have defined in General Settings > Functionality for the "Max Website Container Width"
           ', 'salient'),
           'required' => array( array('blog_width', '!=', 'default') ),
           'default' => 'default'
         ),
   
        array(
          'id' => 'author_bio',
          'type' => 'switch',
          'title' => esc_html__('Author\'s Bio', 'salient'),
          'subtitle' => esc_html__('Display the author\'s bio at the bottom of posts?', 'salient'),
          'desc' => '',
          'default' => '1'
        ),

        array(
          'id' => 'blog_next_post_link',
          'type' => 'switch',
          'title' => esc_html__('Post Navigation Links On Single Post Page', 'salient'),
          'subtitle' => esc_html__('Using this will add navigation link(s) at the bottom of every post page.', 'salient'),
          'desc' => '',
          'type' => 'switch',
          'default' => '1'
        ),
        array(
          'id' => 'blog_next_post_link_style',
          'type' => 'select',
          'title' => esc_html__('Post Navigation Style', 'salient'),
          'subtitle' => esc_html__('Please select the style you would like your post navigation to display in.', 'salient'),
          'desc' => '',
          'required' => array( 'blog_next_post_link', '=', '1' ),
          'options' => array(
            "fullwidth_next_only" => esc_html__("Fullwidth Next Link Only", 'salient'),
            "fullwidth_next_prev" => esc_html__("Fullwidth Next & Prev Links", 'salient'),
            "contained_next_prev" => esc_html__("Contained Next & Prev Links", 'salient'),
            "parallax_next_only" => esc_html__("Parallax Contained Next Link Only", 'salient'),
          ),
          'default' => 'fullwidth_next_prev'
        ),
        array(
         'id' => 'blog_next_post_limit_cat',
         'type' => 'switch',
         'title' => esc_html__('Limit Post Navigation To Same Category', 'salient'),
         'subtitle' => esc_html__('This will ensure that the next/prev links will only show posts that are set in the same catgory of the post being viewed.', 'salient'),
         'desc' => '',
         'type' => 'switch',
         'required' => array( 'blog_next_post_link', '=', '1' ),
         'default' => '0'
       ),

        array(
          'id' => 'blog_next_post_link_order',
          'type' => 'select',
          'title' => esc_html__('Post Navigation Ordering', 'salient'),
          'subtitle' => esc_html__('Please select how you would like your next/previous post links to function.', 'salient'),
          'desc' => '',
          'hint' => array('content' => '<strong>'. esc_html__('Default:','salient') . '</strong> '. esc_html__('the next post link will be the next','salient') . ' <i>'. esc_html__('oldest','salient') .'</i> ' . esc_html__('post.','salient') . '<br/> <strong>' . esc_html__('Reverse Order:','salient') .'</strong> ' . esc_html__('the next post link will be the next', 'salient') . ' <i>'. esc_html__('newest', 'salient') . '</i> ' . esc_html__('post.','salient'), 'title' => ''),
          'required' => array( 'blog_next_post_link', '=', '1' ),
          'options' => array(
            "default" => esc_html__("Default", 'salient'),
            "reverse" => esc_html__("Reverse Order", 'salient')
          ),
          'default' => 'default'
        ),

        array(
          'id' => 'blog_related_posts',
          'type' => 'switch',
          'title' => esc_html__('Related Posts On Single Post Page', 'salient'),
          'subtitle' => esc_html__('Using this will add related post links at the bottom of every post page.', 'salient'),
          'desc' => '',
          'type' => 'switch',
          'default' => '0'
        ),

        array(
          'id' => 'blog_related_posts_functionality',
          'type' => 'select',
          'title' => esc_html__('Related Posts Functionality', 'salient'),
          'subtitle' => esc_html__('Determines how the related posts are picked for each post."', 'salient'),
          'desc' => '',
          'required' => array( 'blog_related_posts', '=', '1' ),
          'options' => array(
            "default" => esc_html__("Recent in same category", 'salient'),
            "random_same_cat" => esc_html__("Random in same category", 'salient'),
            "random" => esc_html__("Random in any category", 'salient'),
       
          ),
          'default' => 'default'
        ),
        
        array(
          'id' => 'blog_related_posts_style',
          'type' => 'select',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'title' => esc_html__('Related Posts Style', 'salient'),
          'subtitle' => esc_html__('Please select the style you would like for the related posts."', 'salient'),
          'desc' => '',
          'required' => array( 'blog_related_posts', '=', '1' ),
          'options' => array(
            "material" => esc_html__("Material", 'salient'),
            "classic_enhanced" => esc_html__("Classic Enhanced", 'salient'),
          ),
          'default' => 'material'
        ),

        array(
          'id' => 'blog_related_posts_excerpt',
          'type' => 'switch',
          'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
          'title' => esc_html__('Display Excerpt In Related Posts', 'salient'),
          'subtitle' => '',
          'desc' => '',
          'required' => array( 'blog_related_posts', '=', '1' ),
          'type' => 'switch',
          'default' => '0'
        ),

        array(
          'id' => 'blog_related_posts_title_text',
          'type' => 'select',
          'title' => esc_html__('Related Posts Title Text', 'salient'),
          'subtitle' => esc_html__('Please select the header text you would like above the related posts.', 'salient'),
          'desc' => '',
          'required' => array( 'blog_related_posts', '=', '1' ),
          'options' => array(
            "related_posts" => esc_html__("Related Posts", 'salient'),
            "similar_posts" => esc_html__("Similar Posts", 'salient'),
            "you_may_also_like" => esc_html__("You May Also Like", 'salient'),
            "recommended_for_you" => esc_html__("Recommended For You", 'salient'),
            "hidden" => esc_html__("None (Hidden)", 'salient')
          ),
          'default' => 'related_posts'
        ),



        array(
          'id' => 'blog_section_title',
          'type' => 'select',
          'title' => esc_html__('Blog Section Title Typography', 'salient'),
          'subtitle' => esc_html__('Select which typography settings the single post section title headings should inherit styling from.', 'salient'),
          'desc' => '',
          'options' => array(
            "default" => "Default",
            "h2" => "Heading 2",
            "h3" => "Heading 3",
            "h4" => "Heading 4",
            "h5" => "Heading 5",
          ),
          'default' => 'default',
        ),


        array(
          'id' => 'blog_comment_author_style',
          'type' => 'select',
          'title' => esc_html__('Author Style in Comments', 'salient'),
          'subtitle' => esc_html__('Choose the styling that will be unique to the post author in the comments section.', 'salient'),
          'desc' => '',
          'options' => array(
            "default" => esc_html__("Default (Inherit from theme skin)", 'salient'),
            "author_badge" => esc_html__("\"Author\" Badge Next to Name", 'salient'),
          ),
          'default' => 'default'
        ),
        array(
          'id' => 'display_tags',
          'type' => 'switch',
          'title' => esc_html__('Display Tags', 'salient'),
          'subtitle' => esc_html__('Display tags at the bottom of posts?', 'salient'),
          'desc' => '',
          'switch' => true,
          'default' => '0'
        ),

        )
      ) );


      Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Single Post Header', 'salient' ),
        'id'               => 'blog-single-post-header',
        'subsection'       => true,
        'fields'           => array(
          array(
            'id' => 'blog_header_type',
            'type' => 'select',
            'title' => esc_html__('Blog Header Type', 'salient'),
            'subtitle' => esc_html__('Please select your blog header format here.', 'salient'),
            'desc' => '',
            'options' => array(
              'default' => esc_html__('Variable Height & Meta Overlaid', 'salient'),
              'default_minimal' => esc_html__('Variable Height Minimal', 'salient'),
              'fullscreen' => esc_html__('Fullscreen with Meta Under', 'salient'),
              'image_under' => esc_html__('Featured Media Under Heading', 'salient')
            ),
            'default' => 'default_minimal'
          ),
  
          array(
           'id' => 'blog_header_aspect_ratio',
           'type' => 'select',
           'title' => esc_html__('Blog Header Image Sizing', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'blog_header_type', '=', 'image_under'  ),
           'options' => array(
             '40' => esc_html__('Slim (2.5:1)', 'salient'),
             '50' => esc_html__('Narrow (2:1)', 'salient'),
             '56.25' => esc_html__('Regular (16:9)', 'salient'),
             '66.66' => esc_html__('Tall (3:2)', 'salient'),
             '100' => esc_html__('Square (1:1)', 'salient'),
           ),
           'default' => '40'
         ),
  
         array(
           'id' => 'blog_header_image_under_align',
           'type' => 'select',
           'title' => esc_html__('Blog Header Alignment', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'blog_header_type', '=', 'image_under'  ),
           'options' => array(
             'default' => esc_html__('Default (Left)', 'salient'),
             'center' => esc_html__('Center', 'salient'),
           ),
           'default' => 'default'
         ),
  
         array(
           'id' => 'blog_header_image_under_author_style',
           'type' => 'select',
           'title' => esc_html__('Blog Header Author Style', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'required' => array( 'blog_header_type', '=', 'image_under'  ),
           'options' => array(
             'default' => esc_html__('Small Image Inline', 'salient'),
             'large' => esc_html__('Large Image Multiline ', 'salient'),
           ),
           'default' => 'default'
         ),
  
         array(
           'id' => 'blog_header_image_under_excerpt',
           'type' => 'switch',
           'required' => array( 'blog_header_type', '=', 'image_under'  ),
           'title' => esc_html__('Blog Header Display Excerpt', 'salient'),
           'subtitle' => esc_html__('Enabling this will add the excerpt field under your blog title.', 'salient'),
           'desc' => '',
           'default' => '0'
         ),
  
          array(
           'id' => 'std_blog_header_overlay_color',
           'type' => 'color',
           'title' => esc_html__('Blog Header Overlay Color', 'salient'),
           'subtitle' => '',
           'desc' => '',
           'default' => '',
           'required' => array( array( 'blog_header_type', '!=', 'default_minimal' ), array( 'blog_header_type', '!=', 'image_under' ) ),
           'transparent' => false
         ),
         array(
           'id'        => 'std_blog_header_overlay_opacity',
           'type'      => 'slider',
           'required' => array( array( 'blog_header_type', '!=', 'default_minimal' ), array( 'blog_header_type', '!=', 'image_under' ) ),
           'title'     => esc_html__('Blog Header Overlay Opacity', 'salient'),
           'desc'      => '',
           "default"   => 0.0,
           "min"       => 0,
           "step"      => 0.1,
           "max"       => 1,
           'resolution' => 0.1,
           'display_value' => 'text'
         ),
  
          array(
            'id' => 'default_minimal_overlay_color',
            'type' => 'color',
            'title' => esc_html__('Blog Header Overlay Color', 'salient'),
            'subtitle' => '',
            'desc' => '',
            'default' => '#2d2d2d',
            'required' => array( 'blog_header_type', '=', 'default_minimal' ),
            'transparent' => false
          ),
          array(
            'id'        => 'default_minimal_overlay_opacity',
            'type'      => 'slider',
            'required' => array( 'blog_header_type', '=', 'default_minimal' ),
            'title'     => esc_html__('Blog Header Overlay Opacity', 'salient'),
            'desc'      => '',
            "default"   => 0.4,
            "min"       => 0,
            "step"      => 0.1,
            "max"       => 1,
            'resolution' => 0.1,
            'display_value' => 'text'
          ),
          array(
            'id' => 'default_minimal_text_color',
            'type' => 'color',
            'title' => esc_html__('Blog Header Text Color', 'salient'),
            'subtitle' => '',
            'desc' => '',
            'default' => '#ffffff',
            'required' => array( 'blog_header_type', '=', 'default_minimal' ),
            'transparent' => false
          ),
  
          array(
            'id' => 'blog_header_sizing',
            'type' => 'select',
            'title' => esc_html__('Blog Header Sizing', 'salient'),
            'desc' => esc_html__('Using a responsive sizing option will override the post height set on individual posts.', 'salient'),
            'required' => array( array( 'blog_header_type', '!=', 'fullscreen' ), array( 'blog_header_type', '!=', 'image_under' ) ),
            'options' => array(
              'default' => esc_html__('Height Set Per Post', 'salient'),
              'responsive' => esc_html__('Responsive Sizing', 'salient')
            ),
            'default' => 'default'
          ),
          array(
            'id' => 'blog_header_scroll_effect',
            'type' => 'select',
            'title' => esc_html__('Blog Header Scroll Effect', 'salient'),
            'desc' => esc_html__('Globally define a scroll effect for your blog header.', 'salient'),
            'options' => array(
              'default' => esc_html__('None', 'salient'),
              'parallax' => esc_html__('Parallax', 'salient')
            ),
            'default' => 'default'
          ),
          array(
           'id' => 'blog_header_load_in_animation',
           'type' => 'select',
           'title' => esc_html__('Blog Header Load In Animation', 'salient'),
           'subtitle' => esc_html__('Please select the loading animation you would like for blog header on the single post template.', 'salient'),
           'desc' => '',
           'required' => array( 'blog_header_type', '=', 'image_under'  ),
           'options' => array(
             "none" => esc_html__("None", 'salient'),
             "fade_in" => esc_html__("Fade In Staggered", 'salient'),
           ),
           'default' => 'none'
         ),

         array(
          'id' => 'blog_hide_featured_image',
          'type' => 'switch',
          'title' => esc_html__('Hide Featured Media on Single Post', 'salient'),
          'subtitle' => esc_html__('Using this will remove the featured media (determined by the selected post format) from appearing in the top of your single blog posts.', 'salient'),
          'desc' => '',
          'default' => '0'
        ),
       
        array(
          'id' => 'blog_post_header_inherit_featured_image',
          'type' => 'switch',
          'title' => esc_html__('Single Post Header Inherits Featured Image', 'salient'),
          'subtitle' => esc_html__('Using this will cause the default background of your post header to use your featured image when no other post header image is supplied.', 'salient'),
          'desc' => '',
          'required' => array( array( 'blog_header_type', '!=', 'image_under' ) ),
          'default' => '1'
        ),

        array(
          'id' => 'blog_header_category_display',
          'type' => 'select',
          'title' => esc_html__('Blog Header Category Display', 'salient'),
          'subtitle' => esc_html__('Please select how you would like your post categories displayed in the header.', 'salient'),
          'desc' => '',
          'options' => array(
            "default" => esc_html__("Display All", 'salient'),
            "parent_only" => esc_html__("Parent Categories Only", 'salient'),
          ),
          'default' => 'default'
        ),

        )
      ) );


       Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Archive Header', 'salient' ),
        'id'               => 'blog-archive-header',
        'subsection'       => true,
        'fields'           => array(

          array(
            'id' => 'blog_archive_bg_functionality',
            'type' => 'select',
            'title' => esc_html__('Blog Archive Header Type', 'salient'),
            'options' => array(
              'image' => esc_html__('Image Background', 'salient'),
              'color' => esc_html__('Color Background', 'salient'),
            ),
            'default' => 'image'
          ),

          array(
            'id' => 'blog_archive_bg_color',
            'type' => 'color',
            'title' => esc_html__('Blog Archive Header Background Color', 'salient'),
            'desc' => '',
            'default' => '#b6c1ff',
            'subtitle' => esc_html__('Select a background color that will be used as the default on all blog archive pages.', 'salient'),
            'required' => array( 'blog_archive_bg_functionality', '=', 'color' ),
            'transparent' => false
          ),
          array(
            'id' => 'blog_archive_bg_text_color',
            'type' => 'color',
            'title' => esc_html__('Blog Archive Header Text Color', 'salient'),
            'desc' => '',
            'default' => '#000000',
            'subtitle' => esc_html__('Select a text color that will be used as the default on all blog archive pages.', 'salient'),
            'required' => array( 'blog_archive_bg_functionality', '=', 'color' ),
            'transparent' => false
          ),

          array(
            'id' => 'blog_archive_bg_image',
            'type' => 'media',
            'title' => esc_html__('Blog Archive Header Background Image', 'salient'),
            'subtitle' => esc_html__('Upload an optional background that will be used as the default on all blog archive pages.', 'salient'),
            'desc' => '',
            'required' => array( 'blog_archive_bg_functionality', '=', 'image' ),
          ),

          array(
            'id' => 'blog_archive_bg_color_layout',
            'type' => 'select',
            'title' => esc_html__('Blog Archive Background Color Layout', 'salient'),
            'required' => array( 'blog_archive_bg_functionality', '=', 'color' ),
            'options' => array(
              'default' => esc_html__('Default (Solid)', 'salient'),
              'gradient' => esc_html__('Gradient', 'salient'),
            ),
            'default' => 'default'
          ),
          
          array(
            'id' => 'blog_archive_text_alignment',
            'type' => 'select',
            'title' => esc_html__('Blog Archive Text Alignment', 'salient'),
            'options' => array(
              'default' => esc_html__('Default', 'salient'),
              'left' => esc_html__('Left', 'salient'),
              'center' => esc_html__('Center', 'salient'),
              'right' => esc_html__('Right', 'salient'),
            ),
            'default' => 'default'
          ),

          array(
            'id' => 'blog_archive_format',
            'type' => 'select',
            'title' => esc_html__('Blog Archive Format', 'salient'),
            'options' => array(
              'default' => esc_html__('Default', 'salient'),
              'minimal' => esc_html__('Minimal', 'salient'),
            ),
            'default' => 'default'
          ),

          array(
            'id' => 'blog_archive_author_gravatar',
            'type' => 'switch',
            'title' => esc_html__('Blog Archive Author Gravatar', 'salient'),
            'subtitle' => esc_html__('Display the author gravatar on author archives.', 'salient'),
            'desc' => '',
            'default' => ''
          ),

        )
      ) );


       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Functionality', 'salient' ),
         'id'               => 'blog-functionality',
         'subsection'       => true,
         'fields'           => array(

          array(
            'id' => 'blog_lazy_load',
            'type' => 'switch',
            'title' => esc_html__('Lazy Load Blog Images', 'salient'),
            'subtitle' => esc_html__('Enabling this will load all featured images within the blog element via a lazy load method for higher performance.', 'salient'),
            'desc' => '',
            'default' => '0'
          ),

           array(
             'id' => 'display_full_date',
             'type' => 'switch',
             'title' => esc_html__('Display Full Date', 'salient'),
             'subtitle' => esc_html__('This will add the year to the date post meta on all blog pages.', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'post_date_functionality',
             'type' => 'select',
             'title' => esc_html__('Post Date Functionality', 'salient'),
             'desc' => '',
             'options' => array(
               "published_date" => esc_html__("Show Published Date", 'salient'),
               "last_editied_date" => esc_html__("Show Last Edited Date", 'salient'),
             ),
             'default' => 'published_date'
           ),
           array(
             'id' => 'blog_pagination_type',
             'type' => 'select',
             'class' => $using_post_grid_archive ? 'hidden-theme-option' : '',
             'title' => esc_html__('Pagination Type', 'salient'),
             'subtitle' => esc_html__('Please select your pagination type here.', 'salient'),
             'desc' => '',
             'options' => array(
               'default' => esc_html__('Default', 'salient'),
               'infinite_scroll' => esc_html__('Infinite Scroll', 'salient')
             ),
             'default' => 'default'
           ),

           array(
            'id'    => 'blog_social',
            'type'  => 'info',
            'style' => 'success',
            'title' => esc_html__('Blog Social Sharing Options', 'salient'),
            'icon'  => 'el-icon-info-sign',
            'desc'  => esc_html__( 'As of Salient v10.1 the blog social settings have been moved into WordPress customizer (Appearance > Customize). Ensure that you have the "Salient Social" plugin installed and activated to use them.', 'salient')
          ),
           array(
             'id' => 'recent-posts-title',
             'type' => 'text',
             'title' => esc_html__('Custom Recent Posts Title', 'salient'),
             'subtitle' => esc_html__('This is be used anywhere you place the recent posts shortcode and on the "Recent Posts" home layout. e.g. Recent Posts', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'recent-posts-link',
             'type' => 'text',
             'title' => esc_html__('Custom Recent Posts Link Text', 'salient'),
             'subtitle' => esc_html__('This is be used anywhere you place the recent posts shortcode and on the "Recent Posts" home layout. e.g. View All Posts', 'salient'),
             'desc' => ''
           ),

         )
       ) );


       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Post Meta', 'salient' ),
         'id'               => 'blog-meta',
         'subsection'       => true,
         'fields'           => array(
           array(
             'id'    => 'blog_single_meta_info',
             'type'  => 'info',
             'style' => 'success',
             'title' => esc_html__('Single Post Template', 'salient'),
             'icon'  => 'el-icon-info-sign',
             'desc'  => esc_html__( 'Use the following options to control what meta information will be shown on your single post template.', 'salient')
           ),
           array(
             'id' => 'blog_remove_single_date',
             'type' => 'switch',
             'title' => esc_html__('Remove Single Post Date', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the date from displaying on your single post template', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'blog_remove_single_author',
             'type' => 'switch',
             'title' => esc_html__('Remove Single Post Author', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the author name from displaying on your single post template', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'blog_remove_single_comment_number',
             'type' => 'switch',
             'title' => esc_html__('Remove Single Post Comment Number', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the comment count from displaying on your single post template', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
            'id' => 'blog_remove_single_reading_dur',
            'type' => 'switch',
            'title' => esc_html__('Remove Single Estimated Reading Time', 'salient'),
            'subtitle' => esc_html__('Enable this to remove the estimated reading time from displaying on your single post template', 'salient'),
            'desc' => '',
            'default' => '1'
          ),
           array(
             'id' => 'blog_remove_single_nectar_love',
             'type' => 'switch',
             'title' => esc_html__('Remove Single Post Nectar Love Button', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the nectar love button from displaying on your single post template', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id'    => 'blog_archive_meta_info',
             'type'  => 'info',
             'style' => 'success',
             'title' => esc_html__('Blog Archive (Post Grid/List) Template', 'salient'),
             'icon'  => 'el-icon-info-sign',
             'desc'  => esc_html__( 'Use the following options to control what meta information will be shown on your posts in the main post query.', 'salient')
           ),
           array(
             'id' => 'blog_remove_post_date',
             'type' => 'switch',
             'title' => esc_html__('Remove Post Date', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the date from displaying on your blog archive layout', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'blog_remove_post_author',
             'type' => 'switch',
             'title' => esc_html__('Remove Post Author', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the author name from displaying on your blog archive layout', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'blog_remove_post_comment_number',
             'type' => 'switch',
             'title' => esc_html__('Remove Comment Number', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the comment count from displaying on your blog archive layout', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'blog_remove_post_nectar_love',
             'type' => 'switch',
             'title' => esc_html__('Remove Nectar Love Button', 'salient'),
             'subtitle' => esc_html__('Enable this to remove the nectar love button from displaying on your blog archive layout in post styles that use it', 'salient'),
             'desc' => '',
             'default' => ''
           ),

         )
       ) );


       global $woocommerce;
       if ($woocommerce) {

         Redux::setSection( $opt_name, array(
           'title'            => esc_html__( 'WooCommerce', 'salient' ),
           'id'               => 'woocommerce',
           'desc'             => esc_html__( 'All WooCommerce options are listed here.', 'salient' ),
           'customizer_width' => '400px',
           'icon'             => 'el el-shopping-cart',
           'fields' => array(

           )
         ) );


         Redux::setSection( $opt_name, array(
           'title'            => esc_html__( 'General', 'salient' ),
           'id'               => 'woocommerce-general',
           'subsection'       => true,
           'desc'             => esc_html__( 'All general WooCommerce related options are listed here', 'salient' ),
           'customizer_width' => '400px',
           'fields' => array(

             array(
               'id' => 'enable-cart',
               'type' => 'switch',
               'title' => esc_html__('Enable WooCommerce Cart In Nav', 'salient'),
               'sub_desc' => esc_html__('This will add a cart item to your main navigation.', 'salient'),
               'desc' => '',
               'default' => '1'
             ),
             array(
               'id' => 'ajax-cart-style',
               'type' => 'select',
               'title' => esc_html__('Cart In Nav Style', 'salient'),
               'subtitle' => esc_html__('Please select the style you would like for your AJAX cart.', 'salient') . '<br/><br/><strong>'. esc_html__('Note:','salient') . '</strong> '. esc_html__('Because WooCommerce caches the cart widget markup, when changing this option you will need to add a product to the cart to see the full change. Alternatively, you can also use the "Clear sessions" tool within WooCommerce > Status > Tools for this.','salient'),
               'desc' => '',
               'options' => array(
                 "dropdown" => esc_html__("Hover Dropdown", "salient"),
                 "slide_in" => esc_html__("Hover Off Canvas Basic", "salient"),
                 "slide_in_click" => esc_html__("Click Extended Functionality", "salient")
               ),
               'default' => 'dropdown',
               'required' => array( 'enable-cart', '=', '1' ),
             ),
             array(
               'id' => 'ajax-add-to-cart',
               'type' => 'switch',
               'title' => esc_html__('AJAX Product Template Add to Cart', 'salient'),
               'subtitle' => esc_html__('Enabling this will allow products to be added to the cart without causing a page refresh on the single product template and in the quickview modal.', 'salient') .'<br /><br /><strong>'. esc_html__('Note:','salient') . '</strong>'. esc_html__('This option may not work with some third-party addons which extend the product data set. If you encounter any issues adding items to your cart, try disabling this option.','salient'),
               'default' => '0',
               'required' => array( 'enable-cart', '=', '1' ),
             ),
             array(
               'id' => 'product_lazy_load_images',
               'type' => 'switch',
               'title' => esc_html__('Lazy Load Shop Images', 'salient'),
               'subtitle' => esc_html__('Enabling this will load shop product images via a lazy load method for higher performance.', 'salient'),
               'desc' => '',
               'default' => '0'
             ),

             array(
               'id' => 'main_shop_layout',
               'type' => 'image_select',
               'title' => esc_html__('Main Shop Layout', 'salient'),
               'sub_desc' => esc_html__('Please select layout you would like to use on your main shop page.', 'salient'),
               'desc' => '',
               'options' => array(
                 'fullwidth' => array('title' => esc_html__('Fullwidth', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/no-sidebar.png'),
                 'no-sidebar' => array('title' => esc_html__('No Sidebar', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/no-sidebar.png'),
                 'right-sidebar' => array('title' => esc_html__('Right Sidebar', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/right-sidebar.png'),
                 'left-sidebar' => array('title' => esc_html__('Left Sidebar', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/left-sidebar.png')
               ),
               'default' => 'no-sidebar'
             ),
             array(
               'id' => 'product_filter_area',
               'type' => 'switch',
               'required' => array( array('main_shop_layout', '!=', 'no-sidebar'), array('main_shop_layout', '!=', 'fullwidth' ) ),
               'title' => esc_html__('Add "Filter" Sidebar Toggle', 'salient'),
               'subtitle' => esc_html__('Enabling this will allow your sidebar widget area to be toggled from a button above your products on all product archives.', 'salient'),
               'desc' => '',
               'default' => '0'
             ),
             array(
               'id' => 'product_show_filters',
               'type' => 'switch',
               'required' => array( array('product_filter_area', '=', '1') ),
               'title' => esc_html__('Display Active Filters Next To Toggle', 'salient'),
               'subtitle' => esc_html__('Displays currently active filters next to the filter toggle button. Not compatible with third party plugins which update WooCommerce filters without reloading the page.', 'salient'),
               'desc' => '',
               'default' => '0'
             ),
             array(
               'id' => 'product_filter_area_starting_state',
               'type' => 'select',
               'required' => array( array('product_filter_area', '=', '1') ),
               'title' => esc_html__('"Filter" Sidebar Toggle Starting State', 'salient'),
               'options' => array(
                 'open' => esc_html__('Open', 'salient'),
                 'closed' => esc_html__('Closed', 'salient'),
               ),
               'default' => 'open'
             ),
             array(
               'id' => 'main_shop_layout_sticky_sidebar',
               'type' => 'switch',
               'required' => array( array('main_shop_layout', '!=', 'no-sidebar'), array('main_shop_layout', '!=', 'fullwidth' ) ),
               'title' => esc_html__('Enable Sticky Sidebar', 'salient'),
               'subtitle' => esc_html__('Would you like to have your sidebar follow down as your scroll in a sticky manner?', 'salient'),
               'desc' => '',
               'default' => '0'
             ),

             array(
               'id' => 'product_style',
               'type' => 'radio',
               'title' => esc_html__('Product Style', 'salient'),
               'sub_desc' => esc_html__('Please select the style you would like your products to display in (single product page styling will also vary slightly with each)', 'salient'),
               'desc' => '',
               'options' => array(
                 'classic' => esc_html__('Classic', 'salient'),
                 'text_on_hover' => esc_html__('Price/Star Ratings on Hover', 'salient'),
                 'material' => esc_html__('Material Design', 'salient'),
                 'minimal' => esc_html__('Minimal Design', 'salient')
               ),
               'default' => 'classic'
             ),

             array(
               'id' => 'product_minimal_hover_layout',
               'type' => 'select',
               'required' => array( array('product_style', '=', 'minimal') ),
               'title' => esc_html__('Minimal Product Hover Layout', 'salient'),
               'subtitle' => esc_html__('Determines the layout your minimal product will use when hovering.', 'salient'),
               'options' => array(
                 'default' => esc_html__('Price Hidden, Minimal Buttons', 'salient'),
                 'price_visible_flex_buttons' => esc_html__('Price Visible, Flex Buttons', 'salient'),
               ),
               'default' => 'default'
             ),
             array(
               'id' => 'product_minimal_hover_effect',
               'type' => 'select',
               'required' => array( array('product_style', '=', 'minimal') ),
               'title' => esc_html__('Minimal Product Hover Effect', 'salient'),
               'subtitle' => esc_html__('Determines the animation effect your minimal product will use when hovering.', 'salient'),
               'options' => array(
                 'default' => esc_html__('Background Grow W/ Shadow', 'salient'),
                 'image_zoom' => esc_html__('Image Zoom', 'salient'),
               ),
               'default' => 'default'
             ),
             array(
               'id' => 'product_minimal_text_alignment',
               'type' => 'select',
               'required' => array( array('product_style', '=', 'minimal') ),
               'title' => esc_html__('Minimal Product Text Alignment', 'salient'),
               'options' => array(
                 'default' => esc_html__('Default', 'salient'),
                 'left' => esc_html__('Left', 'salient'),
                 'center' => esc_html__('Center', 'salient'),
                 'right' => esc_html__('Right', 'salient'),
               ),
               'default' => 'default'
             ),
             array(
               'id' => 'product_border_radius',
               'type' => 'select',
               'title' => esc_html__('Product Border Radius', 'salient'),
               'options' => array(
                 'default' => esc_html__('Default', 'salient'),
                 '0px' => esc_html__('0px', 'salient'),
                 '1px' => esc_html__('1px', 'salient'),
                 '2px' => esc_html__('2px', 'salient'),
                 '3px' => esc_html__('3px', 'salient'),
                 '4px' => esc_html__('4px', 'salient'),
                 '5px' => esc_html__('5px', 'salient'),
                 '6px' => esc_html__('6px', 'salient'),
                 '7px' => esc_html__('7px', 'salient'),
                 '8px' => esc_html__('8px', 'salient'),
                 '9px' => esc_html__('9px', 'salient'),
                 '10px' => esc_html__('10px', 'salient'),
                 '15px' => esc_html__('15px', 'salient'),
                 '20px' => esc_html__('20px', 'salient'),
               ),
               'default' => 'default'
             ),
             array(
               'id' => 'product_quick_view',
               'type' => 'switch',
               'title' => esc_html__('Enable WooCommerce Product Quick View', 'salient'),
               'subtitle' => esc_html__('This will add a "quick view" button to your products which will load key single product page info without having to navigate to the page itself.', 'salient'),
               'desc' => '',
               'default' => ''
             ),
             array(
               'id' => 'product_hover_alt_image',
               'type' => 'switch',
               'title' => esc_html__('Show first gallery image on Product hover', 'salient'),
               'sub_desc' => '',
               'desc' => esc_html__("Using this will cause your products to show the first gallery image (if supplied) on hover", 'salient'),
               'default' => '0'
             ),
             array(
               'id' => 'product_mobile_deactivate_hover',
               'type' => 'switch',
               'title' => esc_html__('Disable Product Hover Effect On Mobile', 'salient'),
               'sub_desc' => '',
               'default' => '0'
             ),

             array(
               'id' => 'product_desktop_cols',
               'type' => 'select',
               'title' => esc_html__('Archive Page Columns (Desktop)', 'salient'),
               'subtitle' => esc_html__('The column number to be displayed on product archive pages when viewed on a desktop monitor ( > 1300px)', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "6" => "6",
                 "5" => "5",
                 "4" => "4",
                 "3" => "3",
                 "2" => "2"
               ),
               'default' => 'default',
             ),
             array(
               'id' => 'product_desktop_small_cols',
               'type' => 'select',
               'title' => esc_html__('Archive Page Columns (Small Desktop)', 'salient'),
               'subtitle' => esc_html__('The column number to be displayed on product archive pages when viewed on a small desktop monitor (1000px - 1300px)', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "6" => "6",
                 "5" => "5",
                 "4" => "4",
                 "3" => "3",
                 "2" => "2"
               ),
               'default' => 'default',
             ),

             array(
               'id' => 'product_tablet_cols',
               'type' => 'select',
               'title' => esc_html__('Archive Page Columns (Tablet)', 'salient'),
               'subtitle' => esc_html__('The column number to be displayed on product archive pages when viewed on a tablet (690px - 1000px)', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "4" => "4",
                 "3" => "3",
                 "2" => "2"
               ),
               'default' => 'default',
             ),

             array(
               'id' => 'product_phone_cols',
               'type' => 'select',
               'title' => esc_html__('Archive Page Columns (Phone)', 'salient'),
               'subtitle' => esc_html__('The column number to be displayed on product archive pages when viewed on a phone ( < 690px)', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "4" => "4",
                 "3" => "3",
                 "2" => "2",
                 "1" => "1"
               ),
               'default' => 'default',
             ),

             array(
               'id' => 'qty_button_style',
               'type' => 'select',
               'title' => esc_html__('Quantity Button Style', 'salient'),
               'subtitle' => esc_html__('Please select style for your quantity buttons.', 'salient'),
               'desc' => '',
               'options' => array(
                 'default' => esc_html__('Rounded Circles With Shadow', 'salient'),
                 'grouped_together' => esc_html__('All Grouped Together', 'salient'),
               ),
               'default' => 'default'
             ),

             array(
               'id' => 'product_bg_color',
               'type' => 'color',
               'transparent' => false,
               'title' => esc_html__('Material Design Product Item BG Color', 'salient'),
               'subtitle' => esc_html__('Set this to match the BG color of your product images.', 'salient'),
               'desc' => '',
               'required' => array( 'product_style', '=', 'material' ),
               'default' => '#ffffff'
             ),
             array(
               'id' => 'product_minimal_bg_color',
               'type' => 'color',
               'transparent' => false,
               'title' => esc_html__('Minimal Design Product Item BG Color', 'salient'),
               'subtitle' => esc_html__('Set this to match the BG color of your product images.', 'salient'),
               'desc' => '',
               'required' => array( 'product_style', '=', 'minimal' ),
               'default' => '#ffffff'
             ),
             array(
               'id' => 'product_archive_bg_color',
               'type' => 'color',
               'transparent' => false,
               'title' => esc_html__('Product Archive Page BG Color', 'salient'),
               'subtitle' => esc_html__('Allows to you set the BG color for all product archive pages', 'salient'),
               'desc' => '',
               'default' => '#f6f6f6'
             ),

             array(
               'id' => 'woo-products-per-page',
               'type' => 'text',
               'title' => esc_html__('Products Per Page', 'salient'),
               'subtitle' => esc_html__('Please enter your desired your products per page (default is 12)', 'salient'),
               'desc' => '',
               'validate' => 'numeric'
             ),
             array(
               'id'    => 'woo_social',
               'type'  => 'info',
               'style' => 'success',
               'title' => esc_html__('WooCommerce Social Sharing Options', 'salient'),
               'icon'  => 'el-icon-info-sign',
               'desc'  => esc_html__( 'As of Salient v10.1 the WooCommerce social settings have been moved into WordPress customizer (Appearance > Customize). Ensure that you have the "Salient Social" plugin installed and activated to use them.', 'salient')
             ),

           )
         ) );

         Redux::setSection( $opt_name, array(
           'title'            => esc_html__( 'Single Product', 'salient' ),
           'id'               => 'woocommerce-single',
           'subsection'       => true,
           'fields'           => array(
             array(
               'id' => 'single_product_layout',
               'type' => 'image_select',
               'title' => esc_html__('Single Product Layout', 'salient'),
               'sub_desc' => esc_html__('Please select layout you would like to use on your single product page.', 'salient'),
               'desc' => '',
               'options' => array(
                 'no-sidebar' => array('title' => esc_html__('No Sidebar', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/no-sidebar.png'),
                 'right-sidebar' => array('title' => esc_html__('Right Sidebar', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/right-sidebar.png'),
                 'left-sidebar' => array('title' => esc_html__('Left Sidebar', 'salient'), 'img' => NECTAR_FRAMEWORK_DIRECTORY.'options/img/left-sidebar.png')
               ),
               'default' => 'no-sidebar'
             ),
             array(
               'id' => 'single_product_gallery_type',
               'type' => 'radio',
               'title' => esc_html__('Single Product Gallery Type', 'salient'),
               'sub_desc' => esc_html__('Please select what gallery type you would like on your single product page', 'salient'),
               'desc' => '',
               'options' => array(
                 'default' => esc_html__('Default WooCommerce Gallery', 'salient'),
                 'ios_slider' => esc_html__('Bottom Thumbnails Slider', 'salient'),
                 'left_thumb_sticky' => esc_html__('Left Thumbnails + Sticky Product Info', 'salient'),
                 'two_column_images' => esc_html__('2 Column Images + Sticky Product Info', 'salient'),
               ),
               'default' => 'ios_slider'
             ),
             array(
               'id' => 'single_product_gallery_custom_width',
               'type' => 'switch',
               'title' => esc_html__('Custom Product Gallery Width', 'salient'),
               'sub_desc' => '',
               'desc' => '',
               'default' => '0'
             ),
             array(
               'id'        => 'single_product_gallery_width',
               'type'      => 'slider',
               'required' => array( 'single_product_gallery_custom_width', '=', '1' ),
               'title'     => esc_html__('Product Gallery Width Percent', 'salient'),
               'subtitle'  => esc_html__('Specify a custom width for your product gallery to be used on desktop views.', 'salient'),
               'desc'      => '',
               "default"   => 60,
               "min"       => 30,
               "step"      => 1,
               "max"       => 75,
               'display_value' => 'text'
             ),
             array(
               'id' => 'single_product_custom_image_dimensions',
               'type' => 'switch',
               'title' => esc_html__('Custom Product Gallery Image Dimensions', 'salient'),
               'sub_desc' => '',
               'desc' => '',
               'default' => '0'
             ),
             array(
                'id'       => 'single_product_gallery_image_dimensions',
                'type'     => 'dimensions',
                'required' => array( 'single_product_custom_image_dimensions', '=', '1' ),
                'units'    => array('px'),
                'title'    => esc_html__('Single Product Image Dimensions', 'salient'),
                'subtitle' => esc_html__('This will allow you to define exact dimensions to crop your single product images to.', 'salient') . '<br/><br/>' . esc_html__('After adding/changing dimensions, you must run the','salient') . ' <strong>' . esc_html__('regenerate thumbnails function','salient') . '</strong>' . ' ' . esc_html__('in the', 'salient') . ' <a href="'. esc_url( admin_url( 'admin.php?page=wc-status&tab=tools' ) ). '">' . esc_html__('WooCommerce tools','salient') . '</a>',
                'default'  => array(
                    'Width'   => '600',
                    'Height'  => '800'
                ),
            ),
             array(
               'id' => 'product_tab_position',
               'type' => 'radio',
               'title' => esc_html__('Product Tab Position', 'salient'),
               'sub_desc' => esc_html__('Please select what area you would like your tabs to display in on the single product page', 'salient'),
               'desc' => '',
               'options' => array(
                 'in_sidebar' => esc_html__('In Side Area', 'salient'),
                 'fullwidth' => esc_html__('Fullwidth Under Images', 'salient'),
                 'fullwidth_stacked' => esc_html__('Fullwidth Under Images Stacked (No Tabs)', 'salient')
               ),
               'default' => 'fullwidth'
             ),
             array(
               'id' => 'product_reviews_style',
               'type' => 'select',
               'title' => esc_html__('Product Reviews Style', 'salient'),
               'subtitle' => esc_html__('Determines how product reviews will be styled.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "off_canvas" => "Submission Form Off Canvas",
               ),
               'default' => 'default',
             ),
             array(
               'id' => 'single_product_related_upsell_carousel',
               'type' => 'switch',
               'title' => esc_html__('Related/Upsell Products Carousel', 'salient'),
               'subtitle' => esc_html__('Increases the max number of related/upsell products to 8 and will enable a carousel display.', 'salient'),
               'desc' => '',
               'default' => '0'
             ),
             array(
               'id' => 'single_product_related_upsell_carousel_number',
               'type'      => 'slider',
               'title'     => esc_html__('Related/Upsell Products Carousel Max Products', 'salient'),
               'desc'      => '',
               "default"   => 8,
               "min"       => 4,
               "step"      => 1,
               "max"       => 20,
               'subtitle' => esc_html__('Choose the maximum number of related/upsell products to display per carousel.', 'salient'),
               'required' => array( 'single_product_related_upsell_carousel', '=', '1' ),
               'display_value' => 'label',
             ),
             array(
               'id' => 'product_add_to_cart_style',
               'type' => 'select',
               'title' => esc_html__('Add to Cart Style', 'salient'),
               'subtitle' => esc_html__('Determines how the add to cart button will be styled.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "fullwidth" => "Full Width",
                 "fullwidth_qty" => "Full Width with Quantity",

               ),
               'default' => 'default',
             ),
             array(
               'id' => 'product_title_typography',
               'type' => 'select',
               'title' => esc_html__('Product Title Typography', 'salient'),
               'subtitle' => esc_html__('Select which typography settings the single product title headings should inherit styling from.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "h2" => "Heading 2",
                 "h3" => "Heading 3",
                 "h4" => "Heading 4",
                 "h5" => "Heading 5",
               ),
               'default' => 'default',
             ),
             array(
               'id' => 'product_price_typography',
               'type' => 'select',
               'title' => esc_html__('Product Price Typography', 'salient'),
               'subtitle' => esc_html__('Select which typography settings the single product price should inherit styling from.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "h2" => "Heading 2",
                 "h3" => "Heading 3",
                 "h4" => "Heading 4",
                 "h5" => "Heading 5",
                 "h6" => "Heading 6",
               ),
               'default' => 'default',
             ),
             array(
               'id' => 'product_tab_heading_typography',
               'type' => 'select',
               'title' => esc_html__('Product Tab Heading Typography', 'salient'),
               'subtitle' => esc_html__('Select which typography settings the single product tab headings should inherit styling from. e.g. "Reviews for ___", "Related products" etc.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "h2" => "Heading 2",
                 "h3" => "Heading 3",
                 "h4" => "Heading 4",
                 "h5" => "Heading 5",
               ),
               'default' => 'default',
             ),
             array(
               'id' => 'product_variable_select_style',
               'type' => 'select',
               'title' => esc_html__('Product Variable Product Dropdown Style', 'salient'),
               'subtitle' => esc_html__('Determines how product variation dropdowns will be styled.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Default",
                 "underline" => "Animated Underline",
               ),
               'default' => 'default',
             ),

             array(
               'id' => 'product_gallery_bg_color',
               'type' => 'color',
               'title' => esc_html__('Product Gallery BG Color', 'salient'),
               'subtitle' => esc_html__('Controls the coloring of the WooCommerce single product lightbox gallery background.', 'salient'),
               'transparent' => false,
               'desc' => '',
               'default' => ''
             ),

             array(
               'id' => 'woo_hide_product_sku',
               'type' => 'switch',
               'title' => esc_html__('Remove SKU From Product Page', 'salient'),
               'sub_desc' => '',
               'desc' => '',
               'default' => '0'
             ),

             array(
               'id' => 'woo_hide_product_additional_info_tab',
               'type' => 'switch',
               'title' => esc_html__('Remove "Additional Information" Tab From Product Page', 'salient'),
               'sub_desc' => '',
               'desc' => '',
               'default' => '0'
             ),

           )
         ) );


         Redux::setSection( $opt_name, array(
           'title'            => esc_html__( 'Archive Header', 'salient' ),
           'id'               => 'woocommerce-archive-header',
           'subsection'       => true,
           'fields'           => array(

             array(
               'id' => 'product_archive_header_size',
               'type' => 'select',
               'title' => esc_html__('Product Category Header Sizing', 'salient'),
               'subtitle' => esc_html__('When a product category header image is supplied, this option will control the sizing of the header area.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Fullwidth",
                 "contained" => "Contained"
               ),
               'default' => 'default',
             ),

             array(
               'id' => 'product_archive_category_description',
               'type' => 'select',
               'title' => esc_html__('Product Category Description', 'salient'),
               'subtitle' => esc_html__('Determines the display of category descriptions.', 'salient'),
               'desc' => '',
               'options' => array(
                 "default" => "Under Header",
                 "in_header" => "Inside of Header"
               ),
               'default' => 'default',
             ),
             array(
               'id' => 'product_archive_header_br',
               'type'      => 'slider',
               'title'     => esc_html__('Product Category Header Roundness', 'salient'),
               'subtitle'  => esc_html__('This allows you to round the corners of your category headers. (Amount to round in px)', 'salient'),
               'desc'      => '',
               "default"   => 0,
               "min"       => 0,
               "step"      => 1,
               "max"       => 20,
               'display_value' => 'label'
             ),
             array(
               'id' => 'product_archive_header_parallax',
               'type' => 'switch',
               'title' => esc_html__('Product Category Header Parallax Scrolling', 'salient'),
               'sub_desc' => '',
               'desc' => '',
               'default' => '0'
             ),
             array(
               'id' => 'product_archive_header_auto_height',
               'type' => 'switch',
               'title' => esc_html__('Product Category Header Auto Height', 'salient'),
               'subtitle' => esc_html__('Will automatically adjust the height of your header depending on the content.', 'salient'),
               'desc' => '',
               'default' => '0'
             ),
             array(
               'id' => 'product_archive_header_text_width',
               'type'      => 'slider',
               'title'     => esc_html__('Product Category Header Max Content Width', 'salient'),
               'subtitle'  => esc_html__('Optionally set the max limit that text will display at before breaking to a new line. (Amount in px)', 'salient'),
               'desc'      => '',
               "default"   => 1000,
               "min"       => 300,
               "step"      => 10,
               "max"       => 2000,
               'display_value' => 'label'
             ),

           )
         ) );

       }









       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'General WordPress Pages', 'salient' ),
         'id'               => 'general-wordpress-pages',
         'customizer_width' => '450px',
         'desc'             => esc_html__('Here you can find options related to general WordPress templates such as the search results template, 404 template etc.', 'salient'),
         'fields'           => array(

         )
       ) );


       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Search Results Template', 'salient' ),
         'id'               => 'general-wordpress-pages-search-results',
         'subsection'       => true,
         'fields'           => array(
           array(
             'id' => 'search-results-layout',
             'type' => 'select',
             'title' => esc_html__('Layout', 'salient'),
             'subtitle' => esc_html__('The layout for your search results.', 'salient'),
             'options' => array(
               "default" => esc_html__("Masonry Grid & Sidebar", 'salient'),
               "masonry-no-sidebar" => esc_html__("Masonry Grid No Sidebar", 'salient'),
               "list-with-sidebar" => esc_html__("List & Sidebar", 'salient'),
               "list-no-sidebar" => esc_html__("List No Sidebar", 'salient')
             ),
             'default' => 'masonry-no-sidebar'
           ),
           array(
             'id' => 'search-results-header-bg-color',
             'type' => 'color',
             'title' => esc_html__('Header Background Color', 'salient'),
             'subtitle' => esc_html__('Default is #f4f4f4', 'salient'),
             'transparent' => false,
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'search-results-header-font-color',
             'type' => 'color',
             'title' => esc_html__('Header Font Color', 'salient'),
             'subtitle' => esc_html__('Default is #000000', 'salient'),
             'transparent' => false,
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'search-results-header-bg-image',
             'type' => 'media',
             'title' => esc_html__('Header Background Image', 'salient'),
             'subtitle' => esc_html__('Upload an optional background that will be used on your search results page', 'salient'),
             'desc' => ''
           )
         )
       ) );

       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( '404 Not Found Template', 'salient' ),
         'id'               => 'general-wordpress-pages-404',
         'subsection'       => true,
         'fields'           => array(

           array(
             'id' => 'page-404-bg-color',
             'type' => 'color',
             'title' => esc_html__('Background Color', 'salient'),
             'subtitle' => '',
             'transparent' => false,
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'page-404-font-color',
             'type' => 'color',
             'title' => esc_html__('Font Color', 'salient'),
             'subtitle' => '',
             'transparent' => false,
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'page-404-bg-image',
             'type' => 'media',
             'title' => esc_html__('Background Image', 'salient'),
             'subtitle' => esc_html__('Upload an optional background that will be used on the 404 page', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'page-404-bg-image-overlay-color',
             'type' => 'color',
             'title' => esc_html__('Background Overlay Color', 'salient'),
             'subtitle' => esc_html__('If you would like a color to overlay your background image, select it here.', 'salient'),
             'transparent' => false,
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'page-404-home-button',
             'type' => 'switch',
             'title' => esc_html__('Add Button To Direct User Home', 'salient'),
             'sub_desc' => esc_html__('This will add a button onto your 404 template which links back to your home page.', 'salient'),
             'desc' => '',
             'default' => '1'
           )
         )
       ));








       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Social Media', 'salient' ),
         'id'               => 'social_media',
         'desc'             => esc_html__( 'Enter in your social media locations here and then activate which ones you would like to display in your footer options & header options tabs. Remember to include the "http://" in all URLs!', 'salient' ),
         'customizer_width' => '400px',
         'icon'             => 'el el-share',
         'fields' => array(
           array(
             'id' => 'facebook-url',
             'type' => 'text',
             'title' => esc_html__('Facebook URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Facebook URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'twitter-url',
             'type' => 'text',
             'title' => esc_html__('Twitter URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Twitter URL.', 'salient'),
             'desc' => ''
           ),
           array(
            'id' => 'x-twitter-url',
            'type' => 'text',
            'title' => esc_html__('X-Twitter URL', 'salient'),
            'subtitle' => esc_html__('Please enter in your X-Twitter URL.', 'salient'),
            'desc' => ''
          ),
           array(
             'id' => 'google-plus-url',
             'type' => 'text',
             'title' => esc_html__('Google URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Google URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'vimeo-url',
             'type' => 'text',
             'title' => esc_html__('Vimeo URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Vimeo URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'dribbble-url',
             'type' => 'text',
             'title' => esc_html__('Dribbble URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Dribbble URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'pinterest-url',
             'type' => 'text',
             'title' => esc_html__('Pinterest URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Pinterest URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'youtube-url',
             'type' => 'text',
             'title' => esc_html__('Youtube URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Youtube URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'tumblr-url',
             'type' => 'text',
             'title' => esc_html__('Tumblr URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Tumblr URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'linkedin-url',
             'type' => 'text',
             'title' => esc_html__('LinkedIn URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your LinkedIn URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'rss-url',
             'type' => 'text',
             'title' => esc_html__('RSS URL', 'salient'),
             'subtitle' => esc_html__('If you have an external RSS feed such as Feedburner, please enter it here. Will use built in Wordpress feed if left blank.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'behance-url',
             'type' => 'text',
             'title' => esc_html__('Behance URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Behance URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'flickr-url',
             'type' => 'text',
             'title' => esc_html__('Flickr URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Flickr URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'spotify-url',
             'type' => 'text',
             'title' => esc_html__('Spotify URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Spotify URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'instagram-url',
             'type' => 'text',
             'title' => esc_html__('Instagram URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Instagram URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'github-url',
             'type' => 'text',
             'title' => esc_html__('GitHub URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your GitHub URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'stackexchange-url',
             'type' => 'text',
             'title' => esc_html__('StackExchange URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your StackExchange URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'soundcloud-url',
             'type' => 'text',
             'title' => esc_html__('SoundCloud URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your SoundCloud URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'vk-url',
             'type' => 'text',
             'title' => esc_html__('VK URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your VK URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'vine-url',
             'type' => 'text',
             'title' => esc_html__('Vine URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Vine URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'vine-url',
             'type' => 'text',
             'title' => esc_html__('Vine URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Vine URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'houzz-url',
             'type' => 'text',
             'title' => esc_html__('Houzz URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Houzz URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'yelp-url',
             'type' => 'text',
             'title' => esc_html__('Yelp URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Yelp URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'snapchat-url',
             'type' => 'text',
             'title' => esc_html__('Snapchat URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Snapchat URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'mixcloud-url',
             'type' => 'text',
             'title' => esc_html__('Mixcloud URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Mixcloud URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'bandcamp-url',
             'type' => 'text',
             'title' => esc_html__('Bandcamp URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Mixcloud URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'tripadvisor-url',
             'type' => 'text',
             'title' => esc_html__('Tripadvisor URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Tripadvisor URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'telegram-url',
             'type' => 'text',
             'title' => esc_html__('Telegram URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Telegram URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'slack-url',
             'type' => 'text',
             'title' => esc_html__('Slack URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Slack URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'medium-url',
             'type' => 'text',
             'title' => esc_html__('Medium URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Medium URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'artstation-url',
             'type' => 'text',
             'title' => esc_html__('Artstation URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Artstation URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'discord-url',
             'type' => 'text',
             'title' => esc_html__('Discord URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Discord URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'whatsapp-url',
             'type' => 'text',
             'title' => esc_html__('WhatsApp URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your WhatsApp URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'messenger-url',
             'type' => 'text',
             'title' => esc_html__('Messenger URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Messenger URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'tiktok-url',
             'type' => 'text',
             'title' => esc_html__('TikTok URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your TikTok URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'twitch-url',
             'type' => 'text',
             'title' => esc_html__('Twitch URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Twitch URL.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'applemusic-url',
             'type' => 'text',
             'title' => esc_html__('Apple Music URL', 'salient'),
             'subtitle' => esc_html__('Please enter in your Apple Music URL.', 'salient'),
             'desc' => ''
           ),
           array(
            'id' => 'patreon-url',
            'type' => 'text',
            'title' => esc_html__('Patreon URL', 'salient'),
            'subtitle' => esc_html__('Please enter in your Patreon URL.', 'salient'),
            'desc' => ''
          ),
           array(
            'id' => 'xing-url',
            'type' => 'text',
            'title' => esc_html__('Xing URL', 'salient'),
            'subtitle' => esc_html__('Please enter in your Xing URL.', 'salient'),
            'desc' => ''
          ),
          array(
            'id' => 'trustpilot-url',
            'type' => 'text',
            'title' => esc_html__('Trustpilot URL', 'salient'),
            'subtitle' => esc_html__('Please enter in your Trustpilot URL.', 'salient'),
            'desc' => ''
          ),
          array(
            'id' => 'mastodon-url',
            'type' => 'text',
            'title' => esc_html__('Mastodon URL', 'salient'),
            'subtitle' => esc_html__('Please enter in your Mastodon URL.', 'salient'),
            'desc' => ''
          ),
          array(
            'id' => 'threads-url',
            'type' => 'text',
            'title' => esc_html__('Threads URL', 'salient'),
            'subtitle' => esc_html__('Please enter in your Threads URL.', 'salient'),
            'desc' => ''
          ),
           array(
             'id' => 'email-url',
             'type' => 'text',
             'title' => esc_html__('Email link', 'salient'),
             'subtitle' => esc_html__('Please enter in your URL link.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'phone-url',
             'type' => 'text',
             'title' => esc_html__('Phone Link', 'salient'),
             'subtitle' => esc_html__('Please enter in your Phone link.', 'salient'),
             'desc' => ''
           )


         )
       ) );




       $salient_using_contact_template = array();

       if( function_exists('get_pages') && true === $salient_on_theme_options_page_bool ) {
         $salient_using_contact_template = get_pages(array(
           'meta_key' => '_wp_page_template',
           'meta_value' => 'template-contact.php'
         ));
       }

       // If user has contact template assigned, show the options.
       if( !empty($salient_using_contact_template) ) {

       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Contact Map', 'salient' ),
         'id'               => 'contact',
         'desc'             => esc_html__( 'The settings on this page will configure the Google map which gets placed at the top of "Contact" page template. These are legacy options which you can still use, however, with the introduction of the interactive map page builder element there is a better and more flexible way to add a map to pages.','salient') . ' <br/><h3>' . esc_html__('We reccomend using the more powerful "Interactive Map" page builder element instead of this.','salient') .  '</h3> ',
         'customizer_width' => '400px',
         'icon'             => 'el el-phone',
         'fields' => array(


           array(
             'id' => 'zoom-level',
             'type' => 'text',
             'title' => esc_html__('Default Map Zoom Level', 'salient'),
             'subtitle' => esc_html__('Value should be between 1-18, 1 being the entire earth and 18 being right at street level.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'enable-map-zoom',
             'type' => 'checkbox',
             'title' => esc_html__('Enable Map Zoom In/Out', 'salient'),
             'subtitle' => esc_html__('Do you want users to be able to zoom in/out on the map?', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'center-lat',
             'type' => 'text',
             'title' => esc_html__('Map Center Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for the maps center point.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'center-lng',
             'type' => 'text',
             'title' => esc_html__('Map Center Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for the maps center point.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'use-marker-img',
             'type' => 'switch',
             'title' => esc_html__('Use Image for Markers', 'salient'),
             'subtitle' => esc_html__('Do you want a custom image to be used for the map markers?', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'marker-img',
             'type' => 'media',
             'required' => array( 'use-marker-img', '=', '1' ),
             'title' => esc_html__('Marker Icon Upload', 'salient'),
             'subtitle' => esc_html__('Please upload an image that will be used for all the markers on your map.', 'salient'),
             'desc' => ''
           ),
           array(
             'id' => 'enable-map-animation',
             'type' => 'checkbox',
             'title' => esc_html__('Enable Marker Animation', 'salient'),
             'subtitle' => esc_html__('This will cause your markers to do a quick bounce as they load in.', 'salient'),
             'desc' => '',
             'default' => '1'
           ),
           array(
             'id' => 'map-point-1',
             'type' => 'switch',
             'title' => esc_html__('Location #1', 'salient'),
             'subtitle' => esc_html__('Toggle location #1', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude1',
             'type' => 'text',
             'required' => array( 'map-point-1', '=', '1' ),
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your first location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude1',
             'type' => 'text',
             'required' => array( 'map-point-1', '=', '1' ),
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your first location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info1',
             'type' => 'textarea',
             'required' => array( 'map-point-1', '=', '1' ),
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your first location, please enter it here.', 'salient'),
             'desc' => ''
           ),


           array(
             'id' => 'map-point-2',
             'type' => 'switch',
             'title' => esc_html__('Location #2', 'salient'),
             'subtitle' => esc_html__('Toggle location #2', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude2',
             'type' => 'text',
             'required' => array( 'map-point-2', '=', '1' ),
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your second location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude2',
             'required' => array( 'map-point-2', '=', '1' ),
             'type' => 'text',
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your second location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info2',
             'type' => 'textarea',
             'required' => array( 'map-point-2', '=', '1' ),
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your second location, please enter it here.', 'salient'),
             'desc' => ''
           ),


           array(
             'id' => 'map-point-3',
             'type' => 'switch',
             'title' => esc_html__('Location #3', 'salient'),
             'subtitle' => esc_html__('Toggle location #3', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude3',
             'type' => 'text',
             'required' => array( 'map-point-3', '=', '1' ),
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your third location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude3',
             'required' => array( 'map-point-3', '=', '1' ),
             'type' => 'text',
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your third location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info3',
             'type' => 'textarea',
             'required' => array( 'map-point-3', '=', '1' ),
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your third location, please enter it here.', 'salient'),
             'desc' => ''
           ),


           array(
             'id' => 'map-point-4',
             'type' => 'switch',
             'title' => esc_html__('Location #4', 'salient'),
             'subtitle' => esc_html__('Toggle location #4', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude4',
             'type' => 'text',
             'required' => array( 'map-point-4', '=', '1' ),
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your fourth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude4',
             'type' => 'text',
             'required' => array( 'map-point-4', '=', '1' ),
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your fourth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info4',
             'required' => array( 'map-point-4', '=', '1' ),
             'type' => 'textarea',
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your fourth location, please enter it here.', 'salient'),
             'desc' => ''
           ),



           array(
             'id' => 'map-point-5',
             'type' => 'switch',
             'title' => esc_html__('Location #5', 'salient'),
             'subtitle' => esc_html__('Toggle location #5', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude5',
             'type' => 'text',
             'required' => array( 'map-point-5', '=', '1' ),
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your fifth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude5',
             'type' => 'text',
             'required' => array( 'map-point-5', '=', '1' ),
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your fifth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info5',
             'required' => array( 'map-point-5', '=', '1' ),
             'type' => 'textarea',
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your fifth location, please enter it here.', 'salient'),
             'desc' => ''
           ),


           array(
             'id' => 'map-point-6',
             'type' => 'switch',
             'title' => esc_html__('Location #6', 'salient'),
             'subtitle' => esc_html__('Toggle location #6', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude6',
             'type' => 'text',
             'required' => array( 'map-point-6', '=', '1' ),
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your sixth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude6',
             'required' => array( 'map-point-6', '=', '1' ),
             'type' => 'text',
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your sixth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info6',
             'required' => array( 'map-point-6', '=', '1' ),
             'type' => 'textarea',
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your sixth location, please enter it here.', 'salient'),
             'desc' => ''
           ),



           array(
             'id' => 'map-point-7',
             'type' => 'switch',
             'title' => esc_html__('Location #7', 'salient'),
             'subtitle' => esc_html__('Toggle location #7', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'latitude7',
             'required' => array( 'map-point-7', '=', '1' ),
             'type' => 'text',
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your seventh location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude7',
             'type' => 'text',
             'required' => array( 'map-point-7', '=', '1' ),
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your seventh location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info7',
             'type' => 'textarea',
             'required' => array( 'map-point-7', '=', '1' ),
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your seventh location, please enter it here.', 'salient'),
             'desc' => ''
           ),



           array(
             'id' => 'map-point-8',
             'type' => 'switch',
             'title' => esc_html__('Location #8', 'salient'),
             'subtitle' => esc_html__('Toggle location #8', 'salient'),
             'desc' => '',
             'next_to_hide' => '3',
             'switch' => true,
             'default' => '0'
           ),
           array(
             'id' => 'latitude8',
             'required' => array( 'map-point-8', '=', '1' ),
             'type' => 'text',
             'title' => esc_html__('Latitude', 'salient'),
             'subtitle' => esc_html__('Please enter the latitude for your eighth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'longitude8',
             'type' => 'text',
             'required' => array( 'map-point-8', '=', '1' ),
             'title' => esc_html__('Longitude', 'salient'),
             'subtitle' => esc_html__('Please enter the longitude for your eighth location.', 'salient'),
             'desc' => '',
             'validate' => 'numeric'
           ),
           array(
             'id' => 'map-info8',
             'type' => 'textarea',
             'required' => array( 'map-point-8', '=', '1' ),
             'title' => esc_html__('Map Infowindow Text', 'salient'),
             'subtitle' => esc_html__('If you would like to display any text in an info window for your eighth location, please enter it here.', 'salient'),
             'desc' => ''
           ),



           array(
             'id' => 'add-remove-locations',
             'type' => 'add_remove',
             'title' => esc_html__('Show More or Less Locations', 'salient'),
             'desc' => '',
             'grouping' => 'map-point'
           ),

           array(
             'id' => 'map-greyscale',
             'type' => 'switch',
             'title' => esc_html__('Greyscale Color', 'salient'),
             'subtitle' => esc_html__('Toggle a greyscale color scheme (will also unlock a custom color option)', 'salient'),
             'desc' => '',
             'default' => '0'
           ),
           array(
             'id' => 'map-color',
             'type' => 'color',
             'required' => array( 'map-greyscale', '=', '1' ),
             'transparent' => false,
             'title' => esc_html__('Map Extra Color', 'salient'),
             'subtitle' =>  esc_html__('Use this to define a main color that will be used in combination with the greyscale option for your map', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'map-ultra-flat',
             'type' => 'checkbox',
             'required' => array( 'map-greyscale', '=', '1' ),
             'title' => esc_html__('Ultra Flat Map', 'salient'),
             'subtitle' =>  esc_html__('This removes street/landmark text & some extra details for a clean look', 'salient'),
             'desc' => '',
             'default' => ''
           ),
           array(
             'id' => 'map-dark-color-scheme',
             'type' => 'checkbox',
             'required' => array( 'map-greyscale', '=', '1' ),
             'title' => esc_html__('Dark Color Scheme', 'salient'),
             'subtitle' =>  esc_html__('Enable this option for a dark colored map (This will override the extra color choice) ', 'salient'),
             'desc' => '',
             'default' => ''
           )


         )
       ) );

     } // Using contact template conditional.


       $nectar_home_slider_active_str = '';

       if( !class_exists('Salient_Home_Slider') ) {
         $nectar_home_slider_active_str = '<span class="salient-plugin-notice"><b>'. esc_html__('"Salient Home Slider" Plugin Not Active.', 'salient') . '</b> ' . '<a href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ). '">' . esc_html__('Click here to go to the plugin install page.', 'salient') . '</a></span>';
       }

       Redux::setSection( $opt_name, array(
         'title'            => esc_html__( 'Home Slider', 'salient' ),
         'id'               => 'home_slider',
         'desc'             => esc_html__( 'All Home Slider plugin related options are listed here.', 'salient' ) . ' ' . wp_kses_post($nectar_home_slider_active_str),
         'customizer_width' => '400px',
         'icon'             => 'el el-home',
         'fields' => array(


           array(
             'id' => 'slider-caption-animation',
             'type' => 'switch',
             'title' => esc_html__('Slider Caption Animations', 'salient'),
             'subtitle' => esc_html__('This will add transition animations to your captions.', 'salient'),
             'desc' => '',
             'default' => '1'
           ),
           array(
             'id' => 'slider-background-cover',
             'type' => 'switch',
             'title' => esc_html__('Slider Image Resize', 'salient'),
             'subtitle' => esc_html__('This will automatically resize your slide images to fit the users screen size by using the background-size cover css property.', 'salient'),
             'desc' => '',
             'switch' => true,
             'default' => '1'
           ),
           array(
             'id' => 'slider-autoplay',
             'type' => 'switch',
             'title' => esc_html__('Autoplay Slider', 'salient'),
             'subtitle' => esc_html__('This will cause the automatic advance of slides until the user begins interaction.', 'salient'),
             'desc' => '',
             'switch' => true,
             'default' => '1'
           ),
           array(
             'id' => 'slider-advance-speed',
             'type' => 'text',
             'title' => esc_html__('Slider Advance Speed', 'salient'),
             'subtitle' => esc_html__('This is how long it takes before automatically switching to the next slide.', 'salient'),
             'desc' => esc_html__('enter in milliseconds (default is 5500)', 'salient'),
             'validate' => 'numeric'
           ),
           array(
             'id' => 'slider-height',
             'type' => 'text',
             'title' => esc_html__('Slider Height', 'salient'),
             'subtitle' => esc_html__('Please enter your desired height for the home slider. The safe minimum height is 400. The theme demo uses 650.', 'salient'),
             'desc' => esc_html__('Don\'t include "px" in the string. e.g. 650', 'salient'),
             'validate' => 'numeric'
           ),
           array(
             'id' => 'slider-bg-color',
             'type' => 'color',
             'title' => esc_html__('Slider Background Color', 'salient'),
             'subtitle' => esc_html__('This color will only be seen if your slides aren\'t wide enough to accommodate large resolutions. ', 'salient'),
             'desc' => '',
             'transparent' => false,
             'default' => '#000000'
           ),


         )
       ) );





    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
    //add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $return['error'] = $field;
                $field['msg']    = 'your custom error message';
            }

            if ( $warning == true ) {
                $return['warning'] = $field;
                $field['msg']      = 'your custom warning message';
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => esc_html__( 'Section via hook', 'salient' ),
                'desc'   => esc_html__( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'salient' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }
