<?php
/**
 * The template for displaying the footer.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * This file used to call almost all other PHP scripts and libraries needed.
 * The file contains some of the primary functions to set main theme settings.
 * All bundled plugins are also called from here using TGMPA class.
 *
 * @package    SEOWP WordPress Theme
 * @author     Vlad Mitkovsky <info@lumbermandesigns.com>
 * @copyright  2014 Lumberman Designs
 * @license    http://themeforest.net/licenses
 * @link       http://themeforest.net/user/lumbermandesigns
 *
 * -------------------------------------------------------------------
 *
 * Send your ideas on code improvement or new hook requests using
 * contact form on http://themeforest.net/user/lumbermandesigns
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * -----------------------------------------------------------------------------
 * Theme PHP scripts and libraries includes
 */

$theme_dir = get_template_directory();
$plugins_integration_dir = $theme_dir . '/inc/plugins-integration';

require_once ( ABSPATH . 'wp-admin/includes/plugin.php' );           // 1
require_once ( $theme_dir . '/design/functions-themedefaults.php'); // 2
require_once ( $theme_dir . '/inc/functions-extras.php' );         // 3

// Plugin integrations
require_once ( $plugins_integration_dir . '/class-tgmpa.php');        // 4
require_once ( $plugins_integration_dir . '/metaboxes.php' );        // 6
require_once ( $plugins_integration_dir . '/livecomposer.php' );    // 7
require_once ( $plugins_integration_dir . '/rotatingtweets.php' ); // 8
require_once ( $plugins_integration_dir . '/megamainmenu.php' );  // 9
require_once ( $plugins_integration_dir . '/masterslider.php' ); // 10
require_once ( $plugins_integration_dir . '/rankie.php' ); 		 // 10.1
require_once ( $plugins_integration_dir . '/nex-forms.php' );  // 10.2

require_once ( $theme_dir . '/inc/customizer/headerpresets.class.php');      // 11
require_once ( $theme_dir . '/inc/customizer/customized-css.php');          // 12
require_once ( $theme_dir . '/inc/functions-themeinstall.php' );           // 13
require_once ( $theme_dir . '/inc/functions-ini.php' );                   // 14
require_once ( $theme_dir . '/all-icons-page.php');                      // 15
require_once ( $theme_dir . '/inc/customizer/customizer.php' );         // 16
require_once ( $theme_dir . '/inc/importer/widgets-importer.php' );    // 17
require_once ( $theme_dir . '/inc/functions-nopluginsinstalled.php' );// 18
require_once ( $theme_dir . '/inc/wp-updates-theme.php' );           // 19

/**
 *  1. Need this to have is_plugin_active()
 *  2. Import theme default settings ( make sure theme defaults are the first
 *     among other files to include !!! )
 *  3. Some extra functions that can be used by any of theme template files
 *
 *  4. TGMP class for plugin install and updates (modified http://goo.gl/frBZcL)
 *  5. ---
 *  6. Framework used to create custom meta boxes
 *  7. LiveComposer plugin integration
 *  8. Rotating Tweets plugin integration
 *  9. Mega Main Menu plugin integration
 *  10. Master Slider plugin integration
 *  10.1. WordPress Rankie plugin integration
 *  10.2. NEX-Forms plugin integration
 *
 *  11. Header design presets class (used in Theme Customizer)
 *  12. Custom CSS generator (based on Theme Customizer options)
 *  13. On theme activation installation functions
 *  14. Functions called on theme initialization
 *  15. Creates admin page used by modal window with all custom icons listed
 *  16. All Theme Customizer magic
 *
 *  17. Widget Importer Functions (based on Widget_Importer_Exporter plugin)
 *  18. Functions to be used when not all required plugins installed
 *  19. WPUpdates Theme Updater Class for automatic theme updates
 */

/**
* ----------------------------------------------------------------------
* Setup some of the theme settings
*
* http://codex.wordpress.org/Plugin_API/Action_Reference
*
* Generally used to initialize theme settings/options.
* This is the first action hook available to themes,
* triggered immediately after the active theme's functions.php
* file is loaded. add_theme_support() should be called here,
* since the init action hook is too late to add some features.
* At this stage, the current user is not yet authenticated.
*/

add_action( 'after_setup_theme', 'lbmn_setup' ); // Bind theme setup callback

if ( ! function_exists( 'lbmn_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 */
	function lbmn_setup() {
		load_theme_textdomain( 'lbmn', get_template_directory() . '/languages' );
		// Make theme available for translation

		// add_theme_support( 'post-formats', array( 'aside', 'quote', 'link' ) );
		// Enable support for Post Formats

		add_theme_support( 'post-thumbnails' );
		// Enable support for Post Thumbnails on posts and pages

		add_theme_support( 'automatic-feed-links' );
		// Add default posts and comments RSS feed links to head

		// This theme uses wp_nav_menu()
		// Here we define menu locations available
		register_nav_menus( array(
			'topbar'	=> __( 'Top Bar', 'lbmn' ),
			'header-menu'	=> __( 'Header', 'lbmn' ),
			// Please note: Mobile off-canvas menu is widget area no menu locaiton
		) );
	}

endif; // lbmn_setup


/**
 * ------------------------------------------------------------------------------
 * Register the required plugins for this theme.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */

define( 'THEME_LC_VER', '1.0.6' );
// Latest compatible Live Composer version
// It overrides the LC dashboard updates feature to make sure LC do not
// update itself until we test and permit it

add_action( 'tgmpa_register', 'lbmn_register_required_plugins' );
function lbmn_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	// there is a bug in tgmpa that prevents me from using 'default_path' in config
	$default_path = LBMN_INSTALLER . LBMN_PLUGINS;
	//get_template_directory_uri() . '/inc/plugins-integration/plugin-installables/';
	$plugins = array(

		// Include amazing 'Live Composer' plugin pre-packaged with a theme
		// http://codecanyon.net/item/live-composer-frontend-wordpress-page-builder/6594028?ref=lumbermandesigns
		array(
			'name'     				=> 'Live Composer',
			'slug'     				=> 'ds-live-composer',
			'source'   				=> $default_path . 'ds-live-composer.1.0.6.zip',
			'required' 				=> true,
			'version' 				=> '1.0.6', // make sure to update THEME_LC_VER constant too
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Mega Main Menu' plugin pre-packaged with a theme
		// http://codecanyon.net/item/mega-main-menu-wordpress-menu-plugin/6135125?ref=lumbermandesigns
		array(
			'name'     				=> 'Mega Main Menu',
			'slug'     				=> 'mega_main_menu',
			'source'   				=> $default_path . 'mega_main_menu.2.0.3.zip',
			'required' 				=> true,
			'version' 				=> '2.0.3',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Meta Box' plugin pre-packaged with a theme
		// http://wordpress.org/plugins/meta-box/
		array(
			'name'     				=> 'Meta Box',
			'slug'     				=> 'meta-box',
			'required' 				=> true,
			'version' 				=> '', //'4.3.8',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'WPFW - Menus Management' plugin pre-packaged with a theme
		// http://codecanyon.net/item/wordpress-menus-management/7814552?ref=lumbermandesigns
		array(
			'name'     				=> 'WPFW - Menus Management',
			'slug'     				=> 'wpfw_menus_management',
			'source'   				=> $default_path . 'wpfw_menus_management.0.0.1.zip',
			'required' 				=> true,
			'version' 				=> '0.0.1',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'MasterSlider' plugin pre-packaged with a theme
		// http://codecanyon.net/item/master-slider-wordpress-responsive-touch-slider/7467925?ref=lumbermandesigns
		array(
			'name'     				=> 'MasterSlider',
			'slug'     				=> 'masterslider',
			'source'   				=> $default_path . 'masterslider.2.7.2.zip',
			'required' 				=> false,
			'version' 				=> '2.7.2',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Easy Social Share Buttons for WordPress' plugin pre-packaged with a theme
		// http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=lumbermandesigns
		array(
			'name'     				=> 'Easy Social Share Buttons for WordPress',
			'slug'     				=> 'easy-social-share-buttons',
			'source'   				=> $default_path . 'easy-social-share-buttons.1.3.9.8.2.zip',
			'required' 				=> false,
			'version' 				=> '1.3.9.8.2',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'NEX-Forms' plugin pre-packaged with a theme
		// http://codecanyon.net/item/nexforms-the-ultimate-wordpress-form-builder/7103891?ref=lumbermandesigns
		array(
			'name'     				=> 'NEX-Forms',
			'slug'     				=> 'nex-forms/main.php',
			'source'   				=> $default_path . 'nex-forms.2.1.zip',
			'required' 				=> false,
			'version' 				=> '2.1',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Simplified Google Maps' plugin pre-packaged with a theme
		// http://cetabo.com
		// array(
		// 	'name'     				=> 'Simplified Google Maps', // Cetabo_GoogleMaps
		// 	'slug'     				=> 'cetabo-googlemaps',
		// 	'source'   				=> $default_path . 'cetabo-googlemaps.2.0.2.zip',
		// 	'required' 				=> false,
		// 	'version' 				=> '2.0.2',
		// 	'force_activation' 	=> false,
		// 	'force_deactivation' => false,
		// 	'external_url' 		=> '',
		// ),
		//
		// Replaced with another (better) alternative
		//
		// http://www.sbthemes.com/plugins/responsive-google-map-plugin/
		array(
			'name'     				=> 'SB Multilingual Responsive Google Map with Styles',
			'slug'     				=> 'sb-google-map',
			'source'   				=> $default_path . 'sb-google-map.1.2.zip',
			'required' 				=> false,
			'version' 				=> '1.2',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Rotating Tweets' plugin pre-packaged with a theme
		// http://wordpress.org/plugins/rotatingtweets/
		//
		// Removed due to performance issues
		//
		// array(
		// 	'name'     				=> 'Rotating Tweets (Twitter widget and shortcode)',
		// 	'slug'     				=> 'rotatingtweets',
		// 	'required' 				=> false,
		// 	'version' 				=> '', //'4.3.8',
		// 	'force_activation' 	=> false,
		// 	'force_deactivation' => false,
		// 	'external_url' 		=> '',
		// ),

		// Include 'Newsletter Sign-Up' plugin pre-packaged with a theme
		// http://wordpress.org/plugins/rotatingtweets/?ref=lumbermandesigns
		array(
			'name'     				=> 'Newsletter Sign-Up',
			'slug'     				=> 'newsletter-sign-up',
			'required' 				=> false,
			'version' 				=> '', //'4.3.8',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Fresh Favicon' premium plugin pre-packaged with a theme
		// http://codecanyon.net/item/fresh-favicon/8111680?ref=lumbermandesigns
		array(
			'name'     				=> 'Fresh Framework',
			'slug'     				=> 'fresh-framework',
			'source'   				=> $default_path . 'fresh-framework.1.4.6.zip',
			'required' 				=> false,
			'version' 				=> '1.4.6',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),
		array(
			'name'     				=> 'Fresh Favicon',
			'slug'     				=> 'fresh-favicon',
			'source'   				=> $default_path . 'fresh-favicon.1.1.1.0.zip',
			'required' 				=> false,
			'version' 				=> '1.1.1',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),

		// Include 'Rankie' premium plugin pre-packaged with our theme
		// http://codecanyon.net/item/rankie-wordpress-rank-tracker-plugin/7605032?ref=lumbermandesigns
		array(
			'name'     				=> 'Wordpress Rankie',
			'slug'     				=> 'wp-rankie',
			'source'   				=> $default_path . 'wp-rankie.1.2.3.zip',
			'required' 				=> false,
			'version' 				=> '1.2.3',
			'force_activation' 	=> false,
			'force_deactivation' => false,
			'external_url' 		=> '',
		),
	);

	/**
	 * Array of configuration settings.
	 */
	$config = array(
		'domain'       		=> 'lbmn',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '', // Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'lbmn' ),
			'menu_title'                       			=> __( 'Install Plugins', 'lbmn' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'lbmn' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'lbmn' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin (the plugin is already included, you need only to install/activate it): %1$s.', 'This theme requires the following plugins (the plugins are already included, you need only to install/activate them): %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin (the plugin is already included, you need only to install/activate it): %1$s.', 'This theme recommends the following plugins (the plugins are already included, you need only to install/activate them): %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is installed but inactive: %1$s.', 'The following required plugins are installed but inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is installed but inactive: %1$s.', 'The following recommended plugins are installed but inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'lbmn' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'lbmn' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'lbmn' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}

/**
 * ----------------------------------------------------------------------
 * WPUpdates Theme Updater Class Initialization
 * Automatic theme updates
 */
new WPUpdatesThemeUpdater_880( 'http://wp-updates.com/api/2/theme', basename(get_template_directory()) );

/**
 * ----------------------------------------------------------------------
 * Save updating versions history
 */


add_action( 'current_screen', 'lbmn_theme_version_control' );
function lbmn_theme_version_control( $current_screen ) {
	if ( 'themes' == $current_screen->base ) {
		// Proceed only if 'themes' admin screen loaded
		$lbmn_theme = wp_get_theme();
		$curent_theme_ver = $lbmn_theme->get( 'Version' );
		$theme_ver_log = get_option( 'lbmn_theme_updates_log');

		if ( ! $theme_ver_log ) {
			$theme_ver_log = array();
		}

		// This code marks the very first theme installation
		// when no theme version control were available
		if ( get_option( LBMN_THEME_NAME . '_basic_setup_done') && ! in_array('1.0.1', $theme_ver_log) ) {
			array_unshift($theme_ver_log, '1.0.1');
			update_option( 'lbmn_theme_updates_log', $theme_ver_log);
		}

		if ( get_option( LBMN_THEME_NAME . '_basic_setup_done') ) {
			update_option( LBMN_THEME_NAME . '_basic_config_done', true);
		}

		if ( ! in_array($curent_theme_ver, $theme_ver_log) ) {
			array_unshift($theme_ver_log, $curent_theme_ver);
			update_option( 'lbmn_theme_updates_log', $theme_ver_log);
		}

		if ( get_option( LBMN_THEME_NAME . '_basic_config_done') ) {
			define ('LBMN_THEME_CONFUGRATED', true);
		}
	}
}
/*
delete_option( LBMN_THEME_NAME . '_basic_setup_done');
delete_option( 'lbmn_theme_updates_log' );
*/
/**
 * ----------------------------------------------------------------------
 * Output theme debug information in HTML including template file name.
 * Must be 'false' if you finished website development.
 */

define('LBMN_THEME_DEBUG', false);

/**
 * ----------------------------------------------------------------------
 * Alternative to define( 'SCRIPT_DEBUG', true ) in wp-config.php
 */

define('LBMN_SCRIPT_DEBUG', false);

// true – original theme JavaScripts outputted
// false - minimized theme JavaScripts outputted

/**
* ----------------------------------------------------------------------
* Theme debuging helpers
*/

function lbmn_debug($var) {
	echo '<!-- debug start --><pre>';
	print_r($var);
	echo '</pre> <!-- debug end -->';
}

/**
 * Send debug code to the Javascript console
 */
function lbmn_debug_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.info('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.info('PHP: ".$data."');</script>");
	}
}

if ( !defined('LBMN_THEME_CONFUGRATED') ) {
	if( get_option( LBMN_THEME_NAME . '_basic_config_done') ) {
		define ('LBMN_THEME_CONFUGRATED', true);
	} else {
		define ('LBMN_THEME_CONFUGRATED', false);
	}
}
