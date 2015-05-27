<?php
/*
	Plugin Name: SB Multilingual Responsive Google Map with Styles
	Description: This is a plugin to generate multilingual responsive google maps with different styles and layers. 100+ stunning styles available and counting. 50 pins available. Nearest Places API. Widget Ready
	Plugin URI: http://www.sbthemes.com/plugins/responsive-google-map-plugin/
	Version: 1.2
	Author: SB Themes
	Author URI: http://codecanyon.net/user/sbthemes/portfolio?ref=sbthemes
*/

/**
 * Defines Constant Variables
 */
// ob_start();
@define(SB_PLUGIN_VERSION, '1.2');																//Plugin Version
@define(SB_MAP_DB_VERSION, '1.0');																//Database Version
@define(SB_DIR_URL, plugins_url('/sb-google-map'));												//Return plugin uri
@define(SB_DIR_PATH, plugin_dir_path(__FILE__));												//Return plugin path
@define(SB_IMG_DIR_URL, plugins_url('/sb-google-map/assets/img'));								//Return images dir uri
@define(SB_ICONS_DIR_URL, SB_IMG_DIR_URL.'/icons');												//Return icons dir uri
@define(SB_ICONS_DIR_PATH, plugin_dir_path(__FILE__).'assets/img/icons');						//Return icons dir path
@define(SB_PREVIEW_DIR_URL, SB_IMG_DIR_URL.'/previews');										//Return preview dir uri
@define(SB_PREVIEW_DIR_PATH, plugin_dir_path(__FILE__).'assets/img/previews');					//Return preview dir path
@define(SB_LIST_PAGE_URL, admin_url('admin.php?page=sb-google-map'));							//Add/Edit Screen URL
@define(SB_EDIT_PAGE_URL, admin_url('admin.php?page=sb-google-map-form'));						//Add/Edit Screen URL



/**
 * Plugin Activation Hook
 */
function sb_map_active_plugin() {
	global $wpdb;
	$table = $wpdb->prefix . 'sb_google_map';
	
	$charset_collate = '';
	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	}
	
	$sql = "CREATE TABLE IF NOT EXISTS $table (
	  `map_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `map_title` varchar(100) DEFAULT NULL,
	  `markers` longtext,
	  `width_height` varchar(500) DEFAULT NULL,
	  `map_styles` varchar(20) DEFAULT NULL,
	  `zoom_settings` longtext,
	  `map_controls` longtext,
	  `nearest_places` longtext,
	  `map_layers` longtext,
	  `miscellaneous` longtext,
	  PRIMARY KEY (`map_id`)
	) ENGINE=MyISAM  $charset_collate;";
	
	//Adding google map table to database
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	//Adding DB Version to database
	update_option('sb_map_db_version', SB_MAP_DB_VERSION);
}
register_activation_hook( __FILE__, 'sb_map_active_plugin' );


/**
 * Set-up Action and Filter Hooks To Add Menu Items (Admin Screen)
 */
function  sb_map_add_menu_page() {
	add_menu_page('SB Google Maps', 'SB Google Maps', 'manage_options', 'sb-google-map', 'sb_map_render_list', SB_IMG_DIR_URL.'/menu-icon.png');
	add_submenu_page('sb-google-map', 'SB Google Maps', 'Add New Map', 'manage_options', 'sb-google-map-form', 'sb_map_render_form');
}
add_action('admin_menu', 'sb_map_add_menu_page');


/**
 * Add settings link in plugins page
 */
function sb_map_plugin_action_links($links, $file) {
    if ($file == plugin_basename( __FILE__ )) {
        $rsmaps_links = '<a href="'.get_admin_url().'admin.php?page=sb-google-map">'.__('Settings').'</a>';
        // Make the 'Settings' link appear first
        array_unshift( $links, $rsmaps_links );
    }
    return $links;
}
add_filter('plugin_action_links', 'sb_map_plugin_action_links', 10, 2);

/**
 * Adding map scripts and styles
 */
function sb_map_scripts() {
	wp_register_script( 'sbmap', SB_DIR_URL.'/assets/js/sb-map.js', array('googlemapapi'), SB_PLUGIN_VERSION, true );		//Register sbmap script
	wp_register_style( 'sbmap-style', SB_DIR_URL.'/assets/css/sb-map.css', array(), SB_PLUGIN_VERSION);						//register sbmap style
}
add_action('wp_enqueue_scripts', 'sb_map_scripts');


/**
 * Including admin settings page
 */
require('admin/sbmap-admin-panel.php');

/**
 * Including tinymce settings
 */
require('admin/sbmap-tinymce.php');

/**
 * Including widget class
 */
require('admin/sbmap-widget.php');

/**
 * Including PHP functions file
 */
require('functions.php');

/**
 * Including Shortcodes function
 */
require('shortcodes.php');
// ob_flush();