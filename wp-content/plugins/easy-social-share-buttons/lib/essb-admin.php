<?php
/**
 * Admin functions class handler
 * @author appscreo
 * @since 1.3.9.8
 *
 */
class EasySocialShareButtons_Admin {
	public static $plugin_settings_name = "easy-social-share-buttons";
	protected $version = ESSB_VERSION;
	
	private static $instance = null;
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	function __construct() {
		// register admin page
		add_action ( 'admin_menu', array ($this, 'init_menu' ) );
		add_action ( 'admin_enqueue_scripts', array ($this, 'register_admin_assets' ), 1 );
		add_action ( 'admin_init', array ($this, 'register_vk' ), 1 );
		add_action('add_meta_boxes', array ($this, 'handle_essb_metabox' ) );
		add_action('save_post',  array ($this, 'handle_essb_save_metabox'));
		
		$option = get_option ( self::$plugin_settings_name );
		
		// @since 1.3.9.4
		if (is_admin ()) {
			if (! function_exists ( 'is_plugin_active' )) {
				include_once (ABSPATH . 'wp-admin/includes/plugin.php');
			}
			if (is_plugin_active ( 'google-analytics-for-wordpress/googleanalytics.php' )) {
		
				$using_yoast_ga = isset ( $option ['using_yoast_ga'] ) ? $option ['using_yoast_ga'] : 'false';
				if ($using_yoast_ga == 'false') {
					add_action ( 'admin_notices', array ($this, 'addNoticeGoogleAnalytics' ) );
				}
			}
			
			$native_counters = (isset($option['native_social_counters'])) ? $option['native_social_counters'] : 'false';
			$native_counters_fb = (isset($option['native_social_counters_fb'])) ? $option['native_social_counters_fb'] : 'false';
			$native_fb_width = (isset($option['facebook_like_button_width'])) ? $option['facebook_like_button_width'] : '';
			$native_counters_g = (isset($option['native_social_counters_g'])) ? $option['native_social_counters_g'] : 'false';
			$native_counters_t = (isset($option['native_social_counters_t'])) ? $option['native_social_counters_t'] : 'false';
			$native_counters_big = (isset($option['native_social_counters_boxes'])) ? $option['native_social_counters_boxes'] : 'false';
			$native_counters_youtube = (isset($option['native_social_counters_youtube'])) ? $option['native_social_counters_youtube'] : 'false';
				
			// @since 1.3.9.8 - moved to general counter option
			$allnative_counters = isset($option['allnative_counters']) ? $option['allnative_counters'] : 'false';
			
			if ($native_counters == 'true' || $native_counters_big == 'true' || $native_counters_fb == 'true' || $native_counters_g == 'true' || $native_counters_g == 'true' || $native_counters_t == 'true' || $native_counters_youtube == 'true') {
				if ($allnative_counters == 'false') {
					add_action ( 'admin_notices', array ($this, 'addNoticeNativeCounters' ) );
				}
			}
		}
		
	}
	
	public function addNoticeGoogleAnalytics() {
		?>
	
	<div class="updated fade">
		<p>
			<strong><a
				href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo"
				target="_blank">Easy Social Share Buttons for WordPress</a></strong>
			found that you are using Google Analytics for WordPress plugin. Please
			go to Easy Social Share Buttons for WordPress Main Settings and in
			Admistrative options activate <strong>I am using Google Analytics for
				WordPress by Yoast:</strong>. <a href="<?php echo admin_url('admin.php?page=essb_settings&tab=general&autoactivate=ga');?>" class="button">Click here to fix</a>
		</p>
	
	</div>
	
	<?php 
		}
		
	public function addNoticeNativeCounters() {
			?>
			
			<div class="updated fade">
				<p>
					<strong><a
						href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo"
						target="_blank">Easy Social Share Buttons for WordPress!</a></strong><br/>
					A change in settings is made starting from version 1.3.9.8. All native counters are activated with single option moved under Main Settings -> Social Like, Follow and Subscribe Buttons. This option is currently off and you will not see counters for native buttons unless you activate it. <br/><a href="<?php echo admin_url('admin.php?page=essb_settings&tab=general&autoactivate=native-counters');?>" class="button">Click here to activate counters</a> <a href="<?php echo admin_url('admin.php?page=essb_settings&tab=general&autoactivate=native-counters-dismiss');?>" class="button">Dismiss this message</a>
				</p>
			
			</div>
			
			<?php 
				}
	
	function register_vk() {
		require_once ESSB_PLUGIN_ROOT.'/lib/extensions/essb-vk.php';
	}
	
	public function init_menu() {
		/*if (@$_POST && isset ( $_POST ['essb_option_page'] )) {
		 	
		include (ESSB_PLUGIN_ROOT . 'lib/admin/essb-settings-updater.php');
			
		$changed = false;
		if ('essb_settings_general' == essb_getval ( $_POST, 'essb_option_page' )) {
		essb_update_general_settings();
		$changed = true;
		}
	
		if ($changed) {
		$goback = add_query_arg ( 'settings-updated', 'true', wp_get_referer () );
		wp_redirect ( $goback );
		die ();
		}
		}*/
	
		$option = get_option ( self::$plugin_settings_name );
		$menu_pos = isset($option['register_menu_under_settings']) ? $option['register_menu_under_settings'] : 'false';
		if ($menu_pos == "true") {
			add_options_page ( "Easy Social Share Buttons", "Easy Social Share Buttons", 'edit_pages', "essb_settings", array ($this, 'essb_settings_load' ), ESSB_PLUGIN_URL . '/assets/images/essb_16.png', 113 );
		}
		else {
			add_menu_page ( "Easy Social Share Buttons", "Easy Social Share Buttons", 'edit_pages', "essb_settings", array ($this, 'essb_settings_load' ), ESSB_PLUGIN_URL . '/assets/images/essb_16.png', 113 );
		}
	}
	
	public function essb_settings_load() {
		/*if (isset($_POST)) {
		 $cmd = isset($_POST['cmd']) ? $_POST['cmd'] : '';
		if ($cmd == "update" || $cmd == "generate") {
		$secCheck =  wp_verify_nonce($_POST['_wpnonce'], 'essb');
	
		if (!$secCheck) { die( "check = " .$secCheck); }
		}
		//$secCheck =  wp_verify_nonce($_POST['_wpnonce'], 'essb');
		//if (!check_admin_referer('essb')) { die(); }
		}*/
	
		include (ESSB_PLUGIN_ROOT . 'lib/admin/essb-settings.php');
	}
	
	
	public function register_admin_assets($hook) {
		wp_register_style ( 'essb-admin', ESSB_PLUGIN_URL . '/assets/css/essb-admin.css', array (), $this->version );
		wp_enqueue_style ( 'essb-admin' );
		wp_enqueue_script ( 'essb-admin', ESSB_PLUGIN_URL . '/assets/js/essb-admin.js', array ('jquery' ), $this->version, true );
	
		wp_register_style ( 'essb-fontawsome', ESSB_PLUGIN_URL . '/assets/css/font-awesome.min.css', array (), $this->version );
		wp_enqueue_style ( 'essb-fontawsome' );
	
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
	
		wp_enqueue_style ( 'essb-morris-styles', ESSB_PLUGIN_URL.'/assets/css/morris.min.css',array (), $this->version );
	
		wp_enqueue_script ( 'essb-morris', ESSB_PLUGIN_URL . '/assets/js/morris.min.js', array ('jquery' ), $this->version );
		wp_enqueue_script ( 'essb-raphael', ESSB_PLUGIN_URL . '/assets/js/raphael-min.js', array ('jquery' ), $this->version );
		wp_enqueue_style ( 'essb-wizard-styles', ESSB_PLUGIN_URL.'/assets/css/essb-jquery.steps.css', array (), $this->version );
		wp_enqueue_script ( 'essb-wizard', ESSB_PLUGIN_URL . '/assets/js/jquery.steps.js', array ('jquery' ), $this->version );
		
		//if ($hook == "toplevel_page_essb_settings") {
		//	wp_enqueue_script( 'ace_code_highlighter_js', ESSB_PLUGIN_URL . '/assets/js/ace/ace.js', '', '1.0.0', true );
		//	wp_enqueue_script( 'ace_mode_js', ESSB_PLUGIN_URL . '/assets/js/ace/mode-css.js', array( 'ace_code_highlighter_js' ), '1.0.0', true );
		//	wp_enqueue_script( 'custom_css_js', ESSB_PLUGIN_URL . '/assets/js/worker-css.js', array( 'jquery', 'ace_code_highlighter_js' ), '1.0.0', true );			
		//}
	}
	
	public function handle_essb_metabox() {
		$options = get_option(  EasySocialShareButtons::$plugin_settings_name );
		$pts	 = get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => true) );
		$cpts	 = get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => false) );
	
		$turnoff_essb_advanced_box = isset($options['turnoff_essb_advanced_box']) ? $options['turnoff_essb_advanced_box'] : 'false';
		foreach ( $pts as $pt ) {
			if (in_array($pt, $options['display_in_types'])) {
				add_meta_box('essb_metabox', __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN), 'essb_register_settings_metabox', $pt, 'side', 'high');
	
				if ($turnoff_essb_advanced_box != 'true') {
					add_meta_box ( "essb_advanced", "Easy Social Share Buttons Advanced", "essb_register_advanced_metabox", $pt, "normal", "high" );
				}
	
			}
		}
		foreach ( $cpts as $cpt ) {
			if (in_array($cpt, $options['display_in_types'])) {
				add_meta_box('essb_metabox', __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN), 'essb_register_settings_metabox', $cpt, 'side', 'high');
	
				if ($turnoff_essb_advanced_box != 'true') {
					add_meta_box ( "essb_advanced", "Easy Social Share Buttons Advanced", "essb_register_advanced_metabox", $cpt, "normal", "high" );
				}
			}
			else if ($cpt == "product") {
				$woocommerce_share = isset ( $options ['woocommece_share'] ) ? $options ['woocommece_share'] : 'false';
				if ($woocommerce_share == "true") {
					add_meta_box('essb_metabox', __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN), 'essb_register_settings_metabox', $cpt, 'side', 'high');
						
					if ($turnoff_essb_advanced_box != 'true') {
						add_meta_box ( "essb_advanced", "Easy Social Share Buttons Advanced", "essb_register_advanced_metabox", $cpt, "normal", "high" );
					}
				}
			}
		}
	
	}
	
	public function handle_essb_save_metabox() {
		global $post, $post_id;
	
		if (! $post) {
			return $post_id;
		}
	
		if (! $post_id)
			$post_id = $post->ID;
			
		// if (! wp_verify_nonce ( @$_POST ['essb_nonce'],
		// 'essb_metabox_handler' ))
		// return $post_id;
		// if (defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
		// return $post_id;
			
		// "essb_off"
		if (isset ( $_POST ['essb_off'] )) {
			if ($_POST ['essb_off'] != '')
				update_post_meta ( $post_id, 'essb_off', $_POST ['essb_off'] );
			else
				delete_post_meta ( $post_id, 'essb_off' );
		}
	
		if (isset ( $_POST ['essb_position'] )) {
			if ($_POST ['essb_position'] != '')
				update_post_meta ( $post_id, 'essb_position', $_POST ['essb_position'] );
			else
				delete_post_meta ( $post_id, 'essb_position' );
		}
	
		if (isset ( $_POST ['essb_theme'] )) {
			if ($_POST ['essb_theme'] != '')
				update_post_meta ( $post_id, 'essb_theme', $_POST ['essb_theme'] );
			else
				delete_post_meta ( $post_id, 'essb_theme' );
		}
	
		if (isset ( $_POST ['essb_names'] )) {
			if ($_POST ['essb_names'] != '')
				update_post_meta ( $post_id, 'essb_names', $_POST ['essb_names'] );
			else
				delete_post_meta ( $post_id, 'essb_names' );
		}
		if (isset ( $_POST ['essb_counter'] )) {
			if ($_POST ['essb_counter'] != '')
				update_post_meta ( $post_id, 'essb_counter', $_POST ['essb_counter'] );
			else
				delete_post_meta ( $post_id, 'essb_counter' );
		}
	
		if (isset ( $_POST ['essb_hidefb'] )) {
			if ($_POST ['essb_hidefb'] != '')
				update_post_meta ( $post_id, 'essb_hidefb', $_POST ['essb_hidefb'] );
			else
				delete_post_meta ( $post_id, 'essb_hidefb' );
		}
	
		if (isset ( $_POST ['essb_hideplusone'] )) {
			if ($_POST ['essb_hideplusone'] != '')
				update_post_meta ( $post_id, 'essb_hideplusone', $_POST ['essb_hideplusone'] );
			else
				delete_post_meta ( $post_id, 'essb_hideplusone' );
		}
	
		if (isset ( $_POST ['essb_hidevk'] )) {
			if ($_POST ['essb_hidevk'] != '')
				update_post_meta ( $post_id, 'essb_hidevk', $_POST ['essb_hidevk'] );
			else
				delete_post_meta ( $post_id, 'essb_hidevk' );
		}
	
		if (isset ( $_POST ['essb_hidetwitter'] )) {
			if ($_POST ['essb_hidetwitter'] != '')
				update_post_meta ( $post_id, 'essb_hidetwitter', $_POST ['essb_hidetwitter'] );
			else
				delete_post_meta ( $post_id, 'essb_hidetwitter' );
		}
	
		if (isset ( $_POST ['essb_counter_pos'] )) {
			if ($_POST ['essb_counter_pos'] != '')
				update_post_meta ( $post_id, 'essb_counter_pos', $_POST ['essb_counter_pos'] );
			else
				delete_post_meta ( $post_id, 'essb_counter_pos' );
		}
	
		if (isset ( $_POST ['essb_sidebar_pos'] )) {
			if ($_POST ['essb_sidebar_pos'] != '')
				update_post_meta ( $post_id, 'essb_sidebar_pos', $_POST ['essb_sidebar_pos'] );
			else
				delete_post_meta ( $post_id, 'essb_sidebar_pos' );
		}
	
		if (isset ( $_POST ['essb_post_share_message'] )) {
			if ($_POST ['essb_post_share_message'] != '')
				update_post_meta ( $post_id, 'essb_post_share_message', $_POST ['essb_post_share_message'] );
			else
				delete_post_meta ( $post_id, 'essb_post_share_message' );
		}
	
		if (isset ( $_POST ['essb_post_share_url'] )) {
			if ($_POST ['essb_post_share_url'] != '')
				update_post_meta ( $post_id, 'essb_post_share_url', $_POST ['essb_post_share_url'] );
			else
				delete_post_meta ( $post_id, 'essb_post_share_url' );
		}
	
		if (isset ( $_POST ['essb_post_share_image'] )) {
			if ($_POST ['essb_post_share_image'] != '')
				update_post_meta ( $post_id, 'essb_post_share_image', $_POST ['essb_post_share_image'] );
			else
				delete_post_meta ( $post_id, 'essb_post_share_image' );
		}
	
		if (isset ( $_POST ['essb_post_share_text'] )) {
			if ($_POST ['essb_post_share_text'] != '')
				update_post_meta ( $post_id, 'essb_post_share_text', $_POST ['essb_post_share_text'] );
			else
				delete_post_meta ( $post_id, 'essb_post_share_text' );
		}
	
		if (isset ( $_POST ['essb_post_fb_url'] )) {
			if ($_POST ['essb_post_fb_url'] != '')
				update_post_meta ( $post_id, 'essb_post_fb_url', $_POST ['essb_post_fb_url'] );
			else
				delete_post_meta ( $post_id, 'essb_post_fb_url' );
		}
	
		if (isset ( $_POST ['essb_post_plusone_url'] )) {
			if ($_POST ['essb_post_plusone_url'] != '')
				update_post_meta ( $post_id, 'essb_post_plusone_url', $_POST ['essb_post_plusone_url'] );
			else
				delete_post_meta ( $post_id, 'essb_post_plusone_url' );
	
		}
		if (isset ( $_POST ['essb_hideyoutube'] )) {
			if ($_POST ['essb_hideyoutube'] != '')
				update_post_meta ( $post_id, 'essb_hideyoutube', $_POST ['essb_hideyoutube'] );
			else
				delete_post_meta ( $post_id, 'essb_hideyoutube' );
		}
		if (isset ( $_POST ['essb_hidepinfollow'] )) {
			if ($_POST ['essb_hidepinfollow'] != '')
				update_post_meta ( $post_id, 'essb_hidepinfollow', $_POST ['essb_hidepinfollow'] );
			else
				delete_post_meta ( $post_id, 'essb_hidepinfollow' );
		}
	
		if (isset ( $_POST ['essb_post_og_desc'] )) {
			if ($_POST ['essb_post_og_desc'] != '')
				update_post_meta ( $post_id, 'essb_post_og_desc', $_POST ['essb_post_og_desc'] );
			else
				delete_post_meta ( $post_id, 'essb_post_og_desc' );
		}
	
		if (isset ( $_POST ['essb_post_og_title'] )) {
			if ($_POST ['essb_post_og_title'] != '')
				update_post_meta ( $post_id, 'essb_post_og_title', $_POST ['essb_post_og_title'] );
			else
				delete_post_meta ( $post_id, 'essb_post_og_title' );
		}
	
		if (isset ( $_POST ['essb_post_og_image'] )) {
			if ($_POST ['essb_post_og_image'] != '')
				update_post_meta ( $post_id, 'essb_post_og_image', $_POST ['essb_post_og_image'] );
			else
				delete_post_meta ( $post_id, 'essb_post_og_image' );
		}
		if (isset ( $_POST ['essb_post_og_video'] )) {
			if ($_POST ['essb_post_og_video'] != '')
				update_post_meta ( $post_id, 'essb_post_og_video', $_POST ['essb_post_og_video'] );
			else
				delete_post_meta ( $post_id, 'essb_post_og_video' );
		}
	
		if (isset ( $_POST ['essb_total_counter_pos'] )) {
			if ($_POST ['essb_total_counter_pos'] != '')
				update_post_meta ( $post_id, 'essb_total_counter_pos', $_POST ['essb_total_counter_pos'] );
			else
				delete_post_meta ( $post_id, 'essb_total_counter_pos' );
		}
	
		if (isset ( $_POST ['essb_post_twitter_hashtags'] )) {
			if ($_POST ['essb_post_twitter_hashtags'] != '')
				update_post_meta ( $post_id, 'essb_post_twitter_hashtags', $_POST ['essb_post_twitter_hashtags'] );
			else
				delete_post_meta ( $post_id, 'essb_post_twitter_hashtags' );
		}
	
		if (isset ( $_POST ['essb_post_twitter_username'] )) {
			if ($_POST ['essb_post_twitter_username'] != '')
				update_post_meta ( $post_id, 'essb_post_twitter_username', $_POST ['essb_post_twitter_username'] );
			else
				delete_post_meta ( $post_id, 'essb_post_twitter_username' );
		}
	
		if (isset ( $_POST ['essb_as'] )) {
			$value =  $_POST ['essb_as'];
			$value = array_filter($value);
			if (count($value) != 0)
				update_post_meta ( $post_id, 'essb_as', $value );
			else
				delete_post_meta ( $post_id, 'essb_as' );
		}
			
		if (isset ( $_POST ['essb_post_twitter_desc'] )) {
			if ($_POST ['essb_post_twitter_desc'] != '')
				update_post_meta ( $post_id, 'essb_post_twitter_desc', $_POST ['essb_post_twitter_desc'] );
			else
				delete_post_meta ( $post_id, 'essb_post_twitter_desc' );
		}
			
		if (isset ( $_POST ['essb_post_twitter_title'] )) {
			if ($_POST ['essb_post_twitter_title'] != '')
				update_post_meta ( $post_id, 'essb_post_twitter_title', $_POST ['essb_post_twitter_title'] );
			else
				delete_post_meta ( $post_id, 'essb_post_twitter_title' );
		}
			
		if (isset ( $_POST ['essb_post_twitter_image'] )) {
			if ($_POST ['essb_post_twitter_image'] != '')
				update_post_meta ( $post_id, 'essb_post_twitter_image', $_POST ['essb_post_twitter_image'] );
			else
				delete_post_meta ( $post_id, 'essb_post_twitter_image' );
		}
	

		if (isset ( $_POST ['essb_post_google_desc'] )) {
			if ($_POST ['essb_post_google_desc'] != '')
				update_post_meta ( $post_id, 'essb_post_google_desc', $_POST ['essb_post_google_desc'] );
			else
				delete_post_meta ( $post_id, 'essb_post_google_desc' );
		}
			
		if (isset ( $_POST ['essb_post_google_title'] )) {
			if ($_POST ['essb_post_google_title'] != '')
				update_post_meta ( $post_id, 'essb_post_google_title', $_POST ['essb_post_google_title'] );
			else
				delete_post_meta ( $post_id, 'essb_post_google_title' );
		}
			
		if (isset ( $_POST ['essb_post_google_image'] )) {
			if ($_POST ['essb_post_google_image'] != '')
				update_post_meta ( $post_id, 'essb_post_google_image', $_POST ['essb_post_google_image'] );
			else
				delete_post_meta ( $post_id, 'essb_post_google_image' );
		}
		
		if (isset ( $_POST ['essb_post_twitter_tweet'] )) {
			if ($_POST ['essb_post_twitter_tweet'] != '')
				update_post_meta ( $post_id, 'essb_post_twitter_tweet', $_POST ['essb_post_twitter_tweet'] );
			else
				delete_post_meta ( $post_id, 'essb_post_twitter_tweet' );
		}		
		
		if (isset ( $_POST ['essb_another_display_popup'] )) {
			if ($_POST ['essb_another_display_popup'] != '')
				update_post_meta ( $post_id, 'essb_another_display_popup', $_POST ['essb_another_display_popup'] );
			else
				delete_post_meta ( $post_id, 'essb_another_display_popup' );
		}
	
		if (isset ( $_POST ['essb_another_display_sidebar'] )) {
			if ($_POST ['essb_another_display_sidebar'] != '')
				update_post_meta ( $post_id, 'essb_another_display_sidebar', $_POST ['essb_another_display_sidebar'] );
			else
				delete_post_meta ( $post_id, 'essb_another_display_sidebar' );
		}
	
		if (isset ( $_POST ['essb_another_display_postfloat'] )) {
			if ($_POST ['essb_another_display_postfloat'] != '')
				update_post_meta ( $post_id, 'essb_another_display_postfloat', $_POST ['essb_another_display_postfloat'] );
			else
				delete_post_meta ( $post_id, 'essb_another_display_postfloat' );
		}
	
			
		if (isset ( $_POST ['essb_activate_customizer'] )) {
			if ($_POST ['essb_activate_customizer'] != '')
				update_post_meta ( $post_id, 'essb_activate_customizer', $_POST ['essb_activate_customizer'] );
			else
				delete_post_meta ( $post_id, 'essb_activate_customizer' );
		}
	
			
		if (isset ( $_POST ['essb_activate_fullwidth'] )) {
			if ($_POST ['essb_activate_fullwidth'] != '')
				update_post_meta ( $post_id, 'essb_activate_fullwidth', $_POST ['essb_activate_fullwidth'] );
			else
				delete_post_meta ( $post_id, 'essb_activate_fullwidth' );
		}
	
		if (isset ( $_POST ['essb_activate_nativeskinned'] )) {
			if ($_POST ['essb_activate_nativeskinned'] != '')
				update_post_meta ( $post_id, 'essb_activate_nativeskinned', $_POST ['essb_activate_nativeskinned'] );
			else
				delete_post_meta ( $post_id, 'essb_activate_nativeskinned' );
		}
	
		if (isset ( $_POST ['essb_opt_by_bp'] )) {
			if ($_POST ['essb_opt_by_bp'] != '')
				update_post_meta ( $post_id, 'essb_opt_by_bp', $_POST ['essb_opt_by_bp'] );
			else
				delete_post_meta ( $post_id, 'essb_opt_by_bp' );
		}
	
		// @ since 1.3.9.6 - self hosted values
		if (ESSB_SELF_ENABLED) {
			$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
				
			if (is_array ( $options )) {
				foreach ( $options ['networks'] as $k => $v ) {
						
					if (isset ( $_POST ['essb_self_'.$k] )) {
						if ($_POST ['essb_self_'.$k] != '')
							update_post_meta ( $post_id, 'essb_self_'.$k, $_POST ['essb_self_'.$k] );
						else
							delete_post_meta ( $post_id, 'essb_self_'.$k );
					}
				}
			}
		}
	
	}
	
	
}
?>