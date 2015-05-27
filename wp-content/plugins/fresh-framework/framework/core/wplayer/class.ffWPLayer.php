<?php

class ffWPLayer extends ffBasicObject {
	/**
	 * 
	 * @var ffHookManager
	 */
	private $_hookManager = null;
	
	private $_WPMLBridge = null;
	
	private $_frameworkUrl = null;
	
	private $_cached = array();
	
	public function get_absolute_path() {
		return ABSPATH;
	}
	
	public function current_user_can( $capability ) {
		return current_user_can($capability);
	}

	public function getGlobal($name){
		switch( $name ){
			// case 'some-variable':
			// 	global $some_other_variable;
			// 	return $some_other_variable;
			default:
				global $$name;
				return $$name;
		}
	}

	public function setGlobal($name, $value){
		switch( $name ){
			// case 'some-variable':
			// 	global $some_other_variable;
			// 	return $some_other_variable = $value;
			default:
				global $$name;
				return $$name = $value;
		}
	}

	public function sanitize_title( $title, $fallback_title = '', $context = 'save' ) {
		return sanitize_title($title, $fallback_title, $context);
	}

	public function wp_is_mobile() {
		return wp_is_mobile();
	}

	public function get_is_iphone (){ global $is_iphone ; return $is_iphone ; }
	public function get_is_chrome (){ global $is_chrome ; return $is_chrome ; }
	public function get_is_safari (){ global $is_safari ; return $is_safari ; }
	public function get_is_NS4    (){ global $is_NS4    ; return $is_NS4    ; }
	public function get_is_opera  (){ global $is_opera  ; return $is_opera  ; }
	public function get_is_macIE  (){ global $is_macIE  ; return $is_macIE  ; }
	public function get_is_winIE  (){ global $is_winIE  ; return $is_winIE  ; }
	public function get_is_gecko  (){ global $is_gecko  ; return $is_gecko  ; }
	public function get_is_lynx   (){ global $is_lynx   ; return $is_lynx   ; }
	public function get_is_IE     (){ global $is_IE     ; return $is_IE     ; }


	public function is_ajax() {
		return defined('DOING_AJAX') && DOING_AJAX;
	}

	public function is_admin_screen( $name ){
		if( ! $this->is_admin() ){
			return false;
		}
		return basename( $_SERVER['SCRIPT_NAME'] ) == $name;
	}

	public function plugin_basename( $file ) {
		return plugin_basename( $file );
	}
	
	public function plugins_url($path = '', $plugin = '') {
		return plugins_url($path, $plugin);
	}
	
	public function getFrameworkUrl() {
		return $this->_frameworkUrl;
	}
	
	public function getFrameworkDir() {
		return FF_FRAMEWORK_DIR;
	}
	
	public function remove_menu_page( $menu_slug ) {
		return remove_menu_page( $menu_slug );
	}
	
	public function __construct( $frameworkUrl ) {
		$this->_frameworkUrl = $frameworkUrl;
	}
	
	public function wp_mkdir_p($path) {
		return wp_mkdir_p($path);
	}
	
	public function get_plugins( $plugin_folder = '' ) {
		return get_plugins( $plugin_folder );
	}
	
	public function wp_get_theme() {
		return wp_get_theme();
	}

	public function wp_get_themes( $args = array() ) {
		return wp_get_themes( $args );
	}
	
	public function setHookManager( ffHookManager $hookManager ) {
		$this->_hookManager = $hookManager;
	}
	
	public function setWPMLBridge( ffWPMLBridge $wpmlBridge ) {
		$this->_WPMLBridge = $wpmlBridge;
	}
	
	public function getWPMLBridge() {
		return $this->_WPMLBridge;
	}
	
	public function wp_remote_get( $url, $arguments = array() ) {
		return wp_remote_get($url, $arguments);
	}
	
	public function copy_dir( $from, $to, $skip_list = array() ) {
		return copy_dir( $from, $to, $skip_list );
	}
	
	public function download_url( $url, $timeout = 300 ) {
		return download_url($url, $timeout);
	}
	
	public function get_plugin_data( $plugin_file, $markup = true, $translate = true ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		return get_plugin_data( $plugin_file, $markup, $translate );
	}
	
	public function get_file_data( $file, $default_headers, $context = '' ) {
		if( file_exists( $file ) ) {
			return get_file_data( $file, $default_headers, $context);
		} else {
			return null;
		}
	}
	
	public function deactivate_plugins( $plugins, $silent = false, $network_wide = null ) {
		return deactivate_plugins( $plugins, $silent, $network_wide);
	}
	
	public function activate_plugin( $plugin, $redirect = '', $network_wide = false, $silent = false ) {
		return activate_plugin( $plugin, $redirect, $network_wide, $silent);
	}
	
	public function get_wp_scripts() {
		//global $WP_Scripts;
		global $wp_scripts;
		return $wp_scripts;
	}
	
	public function get_wp_styles() {
		global $wp_styles;
		return $wp_styles;
	}
	
	public function get_wp_admin_css_colors() {
		global $_wp_admin_css_colors;
		return $_wp_admin_css_colors;
	}
	
	public function set_wp_scripts($wp_scripts_new) {
		global $wp_scripts;
		$wp_scripts = $wp_scripts_new;
	}
	
	public function set_wp_styles( $wp_styles_new ) {
		global $wp_styles;
		$wp_styles = $wp_styles_new;
	}
	
	public function get_site_url( $blog_id = null, $path = '', $scheme = null ) {
		return get_site_url($blog_id, $path, $scheme );
	}
	
	/**
	 * 
	 * @return ffHookManager
	 */
	public function getHookManager() {
		return $this->_hookManager;
	}
	
	public function is_login_page() {
		return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
	}
	
	public function get_wp_query() {
		global $wp_query;
		return $wp_query;
	}
	
	public function add_shortcode($tag, $func) {
		return add_shortcode($tag, $func);
	}
	
	public function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		return add_action( $tag, $function_to_add, $priority, $accepted_args );
	}
	
	public function remove_meta_box($id, $screen, $context) {
		return remove_meta_box($id, $screen, $context);
	}
	
	public function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		return add_filter($tag, $function_to_add, $priority, $accepted_args);
	}
	
	public function get_terms( $taxonomies, $args ) {
		return get_terms( $taxonomies, $args );
	}
	
	public function get_plugin_base_dir() {
		return WP_PLUGIN_DIR;
	}
	
	public function get_WP_filesystem() {
		global $wp_filesystem;
		return $wp_filesystem;
	}
	
	public function wp_upload_dir() {
		return wp_upload_dir();
	}
	
	public function get_home_url() {
		if( !isset( $this->_cached['get_home_url'] ) ) {
			$this->_cached['get_home_url'] = get_home_url(); 
		}
		return $this->_cached['get_home_url'];
	}
	
	public function get_home_path() {
		if( !isset( $this->_cached['get_home_path'] ) ) {
			$this->_cached['get_home_path'] = get_home_path();
		}
		return $this->_cached['get_home_path'];
	}
	
	public function WP_Filesystem() {
		require_once(ABSPATH .'/wp-admin/includes/file.php');
		return WP_Filesystem();
	}
	
	public function is_admin() {
		return is_admin();
	}
	
	public function action_enqueue_scripts_name() {
		if( $this->is_admin() ) {
			return 'admin_enqueue_scripts';
		} else {
			return 'wp_enqueue_scripts';
		}
	}
	
	public function add_action_enque_scripts( $callback, $priority = 10 ) {
		if( $this->is_admin() ) {
			$this->add_action('admin_enqueue_scripts', $callback, $priority);
		} else {
			$this->add_action('wp_enqueue_scripts', $callback, $priority);
		}
	}
	
	public function wp_enqueue_script( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
		wp_enqueue_script( $handle, $src, $deps, false, $in_footer);
	}
	
	private $_stylesToEnqeueueInFooter = array();
	
	public function wp_enqueue_style( $handle, $src = false, $deps = array(), $media = 'all', $in_footer = false )  {
		if( $in_footer && ( $this->action_been_executed('wp_enqueue_scripts') || $this->action_been_executed('admin_enqueue_scripts') ) ) {
		
			if( empty( $this->_stylesToEnqeueueInFooter) ) {
				$this->add_action('wp_footer', array( $this, 'enqueue_footer_styles'), 1);
			}
			
			$style = array();
			$style['handle'] = $handle;
			$style['src'] = $src;
			$style['deps'] = $deps;
			$style['media'] = $media;
			
			
			
			$this->_stylesToEnqeueueInFooter[] = $style;
		} else {
			wp_enqueue_style( $handle, $src, $deps, false, $media);
		}
	}
	
	public function enqueue_footer_styles() {
		foreach( $this->_stylesToEnqeueueInFooter as $oneStyle ) {
			wp_enqueue_style( $oneStyle['handle'], $oneStyle['src'], $oneStyle['deps'], false, $oneStyle['media']);
		}
	}
	
	
	public function wp_enqueue_style_add_param( $handle, $key, $param ) {
		global $wp_styles;
		$wp_styles->add_data($handle, $key, $param);
	
	}
	
	public function add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null ) {
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
	}
	
	public function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
		add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
	}
	
	public function do_action($tag, $arg='') {
		do_action($tag, $arg);
	}
	

	public function action_been_executed( $actionName ) {
		global $merged_filters;
	
		if( isset( $merged_filters[ $actionName ] ) ) {
			return true;
		} else {
			return false;
		}
	}
	

	
	public function wp_enqueue_media( $args = array() ) {
		return wp_enqueue_media( $args );
	}
	
	public function get_queried_object_id() {
		return get_queried_object_id();
	}
	
	public function get_queried_object() {
		return get_queried_object();
	}
	
	public function update_option($name, $value ) {
		return update_option( $name, $value );
	}
	
	public function delete_option( $name ) {
		return delete_option( $name );
	}
	
	public function get_option( $name, $default = null ) {
		return get_option( $name, $default );
	}
	
	public function get_wp_plugin_dir() {
		return WP_PLUGIN_DIR;
	}
	
	public function get_wp_post_types() {
		global $wp_post_types;
		return $wp_post_types;
	}
	
	public function get_taxonomies(  $args = array(), $output = 'names', $operator = 'and' ) {
		return get_taxonomies( $args, $output, $operator );
	}
	
	public function get_taxonomy( $taxonomy ) {
		return get_taxonomy($taxonomy );
	}
	
	public function get_posts( $args ) {
		return get_posts( $args );
	}

	public function get_post( $id, $output = OBJECT, $filter="raw" ){
		return get_post( $id, $output, $filter );
	}

	public function get_post_format( $post_id ){
		return get_post_format( $post_id );
	}

	public function is_singular( $post_types = '' ) {
		return is_singular( $post_types );
	}

	public function is_page( $page = '' ){
		return is_page( $page );
	}

	public function is_home(){       return is_home(); }
	public function is_front_page(){ return is_front_page(); }
	public function is_404(){        return is_404(); }
	public function is_archive(){    return is_archive(); }
	public function is_author( $author = '' ){     return is_author( $author ); }
	public function is_search(){     return is_search(); }
	public function is_date(){       return is_date(); }
	public function is_day(){        return is_day(); }
	public function is_month(){      return is_month(); }
	public function is_year(){       return is_year(); }

	public function is_category( $category = '' ) {
		return is_category( $category );
	}

	public function is_tag( $tag = '' ) {
		return is_tag( $tag  );
	}

	public function is_tax( $taxonomy = '', $term = '' ) {
		return is_tax( $taxonomy, $term );
	}

	public function is_taxonomy() {
		return ( is_category() || is_tag() || is_tax() );
	}

	public function wp_get_object_terms($object_ids, $taxonomies, $args = array()){
		return wp_get_object_terms($object_ids, $taxonomies, $args);
	}

	/**
	 * @return string template of actual page
	 */
	public function get_page_template(){
		return get_page_template();
	}

	/**
	 * @return array array of possible theme page templates
	 */
	public function get_page_templates(){
		return get_page_templates();
	}

	public function wp_delete_post( $postid, $force_delete = false ){
		wp_delete_post( $postid, $force_delete );
	}

	public function wp_insert_post( $post, $wp_error = false ){
		return wp_insert_post( $post, $wp_error );
	}

	public function wp_update_post( $postarr = array(), $wp_error = false ) {
		return wp_update_post( $postarr, $wp_error);
	}

	public function get_post_meta( $post_id, $key = '', $single = false ){
		return get_post_meta( $post_id, $key, $single );
	}

	public function update_post_meta($post_id, $meta_key, $meta_value, $prev_value = ''){
		return update_post_meta($post_id, $meta_key, $meta_value, $prev_value);
	}

	public function delete_post_meta($post_id, $meta_key, $meta_value = ''){
		delete_post_meta($post_id, $meta_key, $meta_value);
	}

	public function wp_get_attachment_url( $att_ID ){
		return wp_get_attachment_url( $att_ID );
	}
	
	public function register_post_type( $post_type, $args ){
		return register_post_type( $post_type, $args );
	}

	public function register_taxonomy( $taxonomy, $object_type, $args ){
		return register_taxonomy( $taxonomy, $object_type, $args );
	}

	public function get_wp_registered_sidebars() {
		global $wp_registered_sidebars;
		return $wp_registered_sidebars;
	}
	
	public function set_wp_registered_sidebars( $registeredSidebars ) {
		global $wp_registered_sidebars;
		$wp_registered_sidebars = $registeredSidebars;
	}

	public function register_sidebar($args = array()){
		return register_sidebar($args);
	}
	
	public function createWpDependency(  $handle = null, $src = null, $deps = null, $ver = null, $args = null ) {
		return new _WP_Dependency( $handle, $src, $deps, $ver, $args );
	}
	
	public function get_theme_support( $feature ) {
		return get_theme_support($feature);
	}
	
	public function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null ) {
		return add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
	}

	public function get_current_user_id(){
		return get_current_user_id();
	}
	
	public function get_current_screen() {
		return get_current_screen();
	}

	public function get_the_author_meta( $field, $userID ){
		return get_the_author_meta( $field, $userID );
	}
	
	public function sanitize_only_letters_and_numbers( $string ) {
		$res = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
		$res = str_replace(' ', '-', $res);
		
		return $res;
	}
}







