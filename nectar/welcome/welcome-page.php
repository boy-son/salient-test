<?php
/**
 * Salient welcome page
 *
 * @package Salient WordPress Theme
 * @version 16.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action('after_switch_theme','nectar_salient_welcome_redirect');

/**
* Redirect to welcome page when Salient is activated.
*
* @since 10.5
*/
function nectar_salient_welcome_redirect() {
  
  global $pagenow;
  
  // Verify that the theme was activated.
  if ( is_admin() && 'themes.php' === $pagenow && isset( $_GET['activated'] ) ) {
    
    // Do not redirect if network activated.
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
      return;
    }
    
    // Redirect.
    wp_safe_redirect( add_query_arg( array( 'page' => 'salient-welcome-screen' ), admin_url( 'index.php' ) ) );
    
  }
  
}


/**
* Redirect to welcome page when Salient is upgraded.
*
* @since 13.0
*/
add_action('admin_init','nectar_upgrade_theme_welcome_redirect');

function nectar_upgrade_theme_welcome_redirect() {
	
	if( is_admin() && !wp_doing_ajax() && !is_customize_preview() ) {
    
    $on_welcome_page = ( isset($_GET['page']) && 'salient-welcome-screen' === $_GET['page'] ) ? true : false;

		$salient_current_version    = nectar_get_theme_version();
		$salient_upgrading_version  = get_option('salient_upgrading_version');
		
		// Do not redirect if network activated.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		
		if( $salient_current_version != $salient_upgrading_version && 
        false == $on_welcome_page &&
        current_user_can('switch_themes') ) {
			
			update_option('salient_upgrading_version', $salient_current_version);

			// Skip redirect for minor releases.
			if ( !empty($salient_upgrading_version) && intval($salient_current_version) - intval($salient_upgrading_version) < 1 ) {
				return;
			}
			
			// Redirect.
			wp_safe_redirect( add_query_arg( array( 'page' => 'salient-welcome-screen' ), admin_url( 'index.php' ) ) );
		}
		
	}

}


add_action('admin_menu', 'nectar_salient_welcome_page');

/**
* Create Salient welcome page.
*
* @since 10.5
*/
function nectar_salient_welcome_page() {
  add_dashboard_page(
    esc_html__( 'Welcome To Salient', 'salient' ),
    esc_html__( 'Welcome To Salient', 'salient' ),
    'edit_theme_options',
    'salient-welcome-screen',
    'nectar_salient_welcome_content'
  );
}


/**
* Add welcome page content.
*
* @since 10.5
*/
function nectar_salient_welcome_content() {
  
  $salient_theme_version = nectar_get_theme_version();
  update_option('salient_upgrading_version', $salient_theme_version);
  
  ?>
  <div class="salient-welcome-wrap">
		<div class="salient-version-box">
				<div class="content">
				  <h1><?php echo esc_html__('Salient Version','salient') . ' ' . nectar_get_theme_version(); ?></h1>
					<p class="subtitle"><?php echo esc_html__('To explore what\'s new in version 16 and view a full list of changes, check out the','salient'); ?> <a href="https://themenectar.com/changelogs/salient.html" target="_blank"><?php echo esc_html__('changelog','salient'); ?>.</a></p>
			  </div>
        <div class="cache-notice"><h3><?php echo esc_html__('A note for upgrading users','salient'); ?></h3><p><?php echo esc_html__('This is a major release. If you\'re using a performance plugin to cache or minify your assets, you must clear the cache, or else layout issues are likely to occur.','salient'); ?></p></div>
        <div class="salient-version-box__img-wrap"><img src="<?php echo get_template_directory_uri(). '/nectar/welcome/img/version-16.jpg'; ?>" alt="Salient version 16" width="" height="" /></div>
		</div>
		<?php 
		if( ! class_exists('Salient_Portfolio') ||
			! class_exists('Salient_Nectar_Slider') ||
			! class_exists('Salient_Home_Slider') ||
			! class_exists('Salient_Shortcodes') ||
			! class_exists('Salient_Demo_Importer') ||
			! class_exists('Salient_Core') ||
			! class_exists('Salient_Widgets') ||
			! class_exists('Salient_Social') ) { ?>
	    <div class="notice-box">
	      <div class="inner">
	        <h3><?php echo esc_html__('For Upgrading Users Coming From Version 10.5 Or Below','salient'); ?></h3>
	        <p><?php echo esc_html__('Salient has separated all of the custom post types and plugin territory functionality into individual plugins. Please install and activate the required plugins and any of the desired optional plugins which you wish to use on your site:','salient'); ?></p>
						<ul>
							<?php 
							if( ! defined( 'SALIENT_VC_ACTIVE' ) ) {
								echo '<li><span class="core">'.esc_html__('Required','salient').'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '"> '. esc_html__('Salient WPBakery Page Builder', 'salient') . '</a></li>'; 
							}
							if( ! class_exists('Salient_Core') ) {
								echo '<li><span class="core">'.esc_html__('Required','salient').'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '"> '. esc_html__('Salient Core', 'salient') . '</a></li>'; 
							}
							if( ! class_exists('Salient_Demo_Importer') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '"> '. esc_html__('Salient Demo Importer', 'salient') . '</a></li>'; 
							}
							if( ! class_exists('Salient_Social') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '"> '. esc_html__('Salient Social', 'salient') . '</a></li>'; 
							}
							if( ! class_exists('Salient_Widgets') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '"> '. esc_html__('Salient Widgets', 'salient') . '</a></li>'; 
							}
							if( ! class_exists('Salient_Portfolio') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '">'. esc_html__('Salient Portfolio', 'salient') . '</a></li>';
							} 
							if( ! class_exists('Salient_Nectar_Slider') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '">'. esc_html__('Salient Nectar Slider', 'salient') . '</a></li>';
							}
							if( ! class_exists('Salient_Home_Slider') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '">'. esc_html__('Salient Home Slider', 'salient') . '</a></li>'; 
							}
							if( ! class_exists('Salient_Shortcodes') ) {
								echo '<li><span>'. esc_html__('Optional','salient') .'</span><a target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '">'. esc_html__('Salient Shortcodes', 'salient') . '</a></li>'; 
							}
							?>
						</ul>
						<?php echo '<div class="cta-wrap"><a class="begin-installing" target="_blank" href="'. esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '"><strong>' . esc_html__('Begin Installing Plugins','salient') .'</strong></a></div>'; ?>
				</div>
	    </div>
		<?php } ?>
  </div>
  <div class="welcome-container">
    <div class="row">
      <div class="col col-3">
        <h3><?php echo esc_html__('Documentation','salient'); ?></h3>
        <p><?php echo esc_html__('View our online user guide and documentation to get started.','salient'); ?></p>
        <a class="button button-primary" href="//themenectar.com/docs/salient" target="_blank"><?php echo esc_html__('View Documentation','salient'); ?> </a>
      </div>
      <div class="col col-3">
        <h3><?php echo esc_html__('What\'s New','salient'); ?></h3></p>
        <p><?php echo esc_html__('Salient has been going strong for over 10 years and is constantly evolving. ','salient'); ?></p>
        <a class="button button-primary" href="//themenectar.com/changelogs/salient.html" target="_blank"><?php echo esc_html__('View Changelog','salient'); ?> </a>
      </div>
      <div class="col col-3">
        <h3><?php echo esc_html__('Get Support','salient'); ?></h3>
        <p><?php echo esc_html__('Have a Question? Head over to the forum and open a ticket so that we may assist you.','salient'); ?></p>
        <a class="button button-primary" href="//themenectar.ticksy.com/" target="_blank"><?php echo esc_html__('Open Support Forum','salient'); ?> </a>
      </div>
    </div>
  </div>
  <?php 
}


/**
* Hide the welcome page from appearing in the menu.
*
* @since 10.5
*/
add_action( 'admin_head', 'nectar_salient_welcome_remove_menu' );

function nectar_salient_welcome_remove_menu() {
    remove_submenu_page( 'index.php', 'salient-welcome-screen' );
}



add_action( 'admin_enqueue_scripts', 'nectar_salient_welcome_assets' );

/**
* Enqueue welcome assets.
*
* @since 10.5
*/
function nectar_salient_welcome_assets($hook) {
  
  if( $hook !== 'dashboard_page_salient-welcome-screen' ) {
    return;
  }
  
  wp_register_style( 'nectar_salient_welcome_css', get_template_directory_uri() . '/nectar/welcome/css/style.css', false, '15.0' );
  wp_enqueue_style( 'nectar_salient_welcome_css' );
}
