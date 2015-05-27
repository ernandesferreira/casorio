<?php

class EasySocialShareButtons {
	
	protected $version = ESSB_VERSION;
	protected $plugin_name = "Easy Social Share Buttons for WordPress";
	protected $plugin_slug = "easy-social-share-buttons";
		
	protected $print_links_position = "top";
	
	public static $plugin_settings_name = "easy-social-share-buttons";
	
	public static $instance = null;
	
	public $stats = null;
	public $love = null;
	
	protected $pinjs_registered = false;
	protected $mailjs_registered = false;
	protected $css_minifier = false;
	protected $js_minifier = false;
	protected $counter_included = false;
	protected $skinned_social = false;
	protected $skinned_social_selected_skin = "";
	protected $twitter_api_added = false;
	protected $shortcode_like_css_included = false;		
	protected $avoid_next_page = false;
	
	protected $essb_css_builder;
	
	// @since 1.3.9.8
	protected  $essb_js_builder;
	
	protected $options_by_pt_active = false;
	protected $options_by_bp_active = false;
	protected $fb_api_loaded = false;
	protected $gplus_loaded = false;
	protected $load_js_async = false;
	//protected $async_js_list = array();
	
	protected $using_self_counters = false;
	
	protected $more_button_code_inserted = false;
	
	protected $loaded_template_slug = 'default';
	
	protected $options;
	
	protected $mobile_detect;
	protected $custom_priority = 0;		
	protected $custom_priority_active = false;
	protected $ga_tracking = false;	
	
	protected $mobile_options_active = false;
	
	public function __construct() {
		global $essb_stats;
		
		$this->stats = $essb_stats;
		
		$essb_options = EasySocialShareButtons_Options::get_instance();
		$this->options = $essb_options->options;

		// @since 1.3.9.8 - new Social Share Optimization Engine
		EasySocialShareButtons_SocialShareOptimization::get_instance();
		
		$this->essb_css_builder = new ESSB_CSS_Builder();
		$this->essb_js_builder = ESSB_JS_Buider::get_instance();						
		
		$option = $this->options;
		
		$remove_ver_resource = isset($option['remove_ver_resource']) ? $option['remove_ver_resource'] : 'false';
		if ($remove_ver_resource == 'true') {
			$this->version = '';
		}
		
		$new_priority = isset($option['priority_of_buttons']) ? $option['priority_of_buttons'] : '';
		if (trim($new_priority) != '') {
			$new_priority = trim($new_priority);
			$new_priority = intval($new_priority);
			
			if ($new_priority != 0) {
				$this->custom_priority = $new_priority;
				$this->custom_priority_active = true;
			}
		}
		
		// @since 1.3.9.8 - GA tracking mode
		$activate_ga_tracking = isset($option['activate_ga_tracking']) ? $option['activate_ga_tracking'] : '';
		if ($activate_ga_tracking == 'true') {
			$this->ga_tracking = true;
			$ga_tracking_mode = isset($option['ga_tracking_mode']) ? $option['ga_tracking_mode'] : '';
			
			$this->essb_js_builder->include_ga_tracking_code($ga_tracking_mode);
		}
		
		$activate_opt_by_mp = isset($option['activate_opt_by_mp']) ? $option['activate_opt_by_mp'] : 'false';
		if ($activate_opt_by_mp == 'true') {
			$this->mobile_options_active = true;
		}
		
		$module_off_lv = isset($option['module_off_lv']) ? $option['module_off_lv'] : 'false';
		if ($module_off_lv != 'true') {
			$this->love = ESSB_LoveYou::get_instance();
		}
				
		$avoid_nextpage = isset($option['avoid_nextpage']) ? $option['avoid_nextpage'] : 'false';
		if ($avoid_nextpage == "true") { $this->avoid_next_page = true; }
		
		// @since 1.3.9.5 - we can have specific post type options
		$activate_opt_by_pt = isset($option['activate_opt_by_pt']) ? $option['activate_opt_by_pt'] : 'false';
		if ($activate_opt_by_pt == "true") {
			$this->options_by_pt_active = true;
		}
		
		// @since 1.3.9.5 - we can have specific post type options
		$activate_opt_by_bp = isset($option['activate_opt_by_bp']) ? $option['activate_opt_by_bp'] : 'false';
		if ($activate_opt_by_bp == "true") {
			$this->options_by_bp_active = true;
		}
		
		$load_js_async = isset($option['load_js_async']) ? $option['load_js_async'] : 'false';
		$this->load_js_async = ($load_js_async == "true") ? true: false;
		
		
		add_action ( 'wp_enqueue_scripts', array ($this, 'register_front_assets' ), 1 );
		
		$skin_native = isset($option['skin_native']) ? $option['skin_native'] : 'false';
		$skin_native_skin = isset($option['skin_native_skin']) ? $option['skin_native_skin'] : '';
		if ($skin_native == 'true') {
			$this->skinned_social = true;
			$this->skinned_social_selected_skin = $skin_native_skin;			
		}
		
		if ($this->custom_priority_active) {
			add_action ( 'the_content', array ($this, 'print_share_links' ), $this->custom_priority, 1 );
		}
		else {
 			add_action ( 'the_content', array ($this, 'print_share_links' ), 10, 1 );
		}
		$is_excerpt_active = isset($option['display_excerpt']) ? $option['display_excerpt'] : 'false';
			
		$apply_clean_buttons = isset($option['apply_clean_buttons']) ? $option['apply_clean_buttons'] : 'false';
		
		if ($apply_clean_buttons == "true") {
			add_filter( 'get_the_excerpt', array( $this, 'remove_buttons_excerpts'));
			//add_filter( 'the_excerpt', array( $this, 'remove_buttons_excerpts'), -999);
		}
			
		// @since 1.1.9
		if ($is_excerpt_active == "true") {
			add_action ( 'the_excerpt', array ($this, 'print_share_links_excerpt' ), 10, 1 );
		}
				
		add_shortcode ( 'essb', array ($this, 'handle_essb_shortcode' ) );
		add_shortcode ( 'easy-share', array ($this, 'handle_essb_shortcode' ) );
		add_shortcode ( 'easy-social-share-buttons', array ($this, 'handle_essb_shortcode' ) );
		
		// @since 1.3.9.2
		add_shortcode ('easy-social-like', array($this, 'handle_essb_like_buttons_shortcode'));
		add_shortcode ('easy-social-like-simple', array($this, 'handle_essb_like_buttons_shortcode'));
		add_shortcode ( 'easy-social-share', array ($this, 'handle_essb_shortcode_vk' ) );
		
		// @since 1.3.9.3
		add_shortcode ('easy-total-shares', array($this, 'handle_essb_total_shortcode'));
						
		$included_fb_api = isset($option['facebook_like_button_api']) ? $option['facebook_like_button_api'] : '';
		$facebook_advanced_sharing = isset($option['facebookadvanced']) ? $option['facebookadvanced'] : 'false';
		$facebook_like_button = isset($option['facebook_like_button']) ? $option['facebook_like_button'] : 'false';
		
		if ($included_fb_api != 'true' && $facebook_like_button == 'true') {

			$this->essb_js_builder->include_fb_script();
			$this->fb_api_loaded = true;
		}
		
		$plusbutton = isset ( $option ['googleplus'] ) ? $option ['googleplus'] : 'false';
		
		if ($plusbutton == 'true') {
			//add_action ( 'wp_footer', array ($this, 'init_gplus_script' ) );
			$this->essb_js_builder->include_gplus_script();
			$this->gplus_loaded = true;
		}
		
		// @since 1.0.4
		$include_vk_api = isset ( $option ['vklike'] ) ? $option ['vklike'] : '';
		
		if ($include_vk_api == 'true') {
			//add_action ( 'wp_footer', array ($this, 'init_vk_script' ) );
			$this->essb_js_builder->include_vk_script();
		}
		
		// @since 1.0.7 fix for mobile devices don't to pop network names
		$hidden_network_names = (isset ( $option ['hide_social_name'] ) && $option ['hide_social_name'] == 1) ? true : false;
		
		// @since 1.3.9.2
		$always_hide_names_mobile = (isset ( $option ['always_hide_names_mobile'] )) ? $option ['always_hide_names_mobile'] : 'false';
		if ($always_hide_names_mobile == 'true' && $this->isMobile ()) {
			$hidden_network_names = true;
		}
		
		if ($hidden_network_names && $this->isMobile ()) {
			$this->essb_css_builder->fix_css_mobile_hidden_network_names ();
		}
		
		if ($hidden_network_names && ! $this->isMobile ()) {
			$force_hide = isset ( $option ['force_hide_social_name'] ) ? $option ['force_hide_social_name'] : 'false';
			
			if ($force_hide == 'true') {
				$this->essb_css_builder->fix_css_mobile_hidden_network_names ();
			}
		}
		
		$hidden_buttons_on_lowres_mobile = isset ( $option ['force_hide_buttons_on_mobile'] ) ? $option ['force_hide_buttons_on_mobile'] : 'false';
		if ($hidden_buttons_on_lowres_mobile == 'true' && $this->isMobile ()) {
			$this->essb_css_builder->fix_css_mobile_hidden_buttons ();
		}
		
		// @since 1.3.9.3
		$force_hide_buttons_on_all_mobile = isset ( $option ['force_hide_buttons_on_all_mobile'] ) ? $option ['force_hide_buttons_on_all_mobile'] : 'false';
		if ($force_hide_buttons_on_all_mobile == "true") {
			$this->deactivate ();
		}
				
		// @since 1.1
		//add_action ( 'wp_ajax_nopriv_essb_action', array ($this, 'send_email' ) );
		//add_action ( 'wp_ajax_essb_action', array ($this, 'send_email' ) );
		
		// @since 1.3.1
		add_action ( 'wp_ajax_nopriv_essb_counts', array ($this, 'get_share_counts' ) );
		add_action ( 'wp_ajax_essb_counts', array ($this, 'get_share_counts' ) );
		
		add_action ( 'wp_ajax_nopriv_essb_self_postcount', array ($this, 'essb_self_updatepost_count' ) );
		add_action ( 'wp_ajax_essb_self_postcount', array ($this, 'essb_self_updatepost_count' ) );
		
		if (ESSB_SELF_ENABLED) {
			//add_action ( 'wp_ajax_nopriv_essb_self_counts', array ($this, 'get_self_share_counts' ) );
			//add_action ( 'wp_ajax_essb_self_counts', array ($this, 'get_self_share_counts' ) );
			
			//add_action ( 'wp_ajax_nopriv_essb_self_docount', array ($this, 'essb_self_docount' ) );
			//add_action ( 'wp_ajax_essb_self_docount', array ($this, 'essb_self_docount' ) );
			
			//$activate_self_hosted_counters = isset ( $option ['activate_self_hosted_counters'] ) ? $option ['activate_self_hosted_counters'] : 'false';
			//if ($activate_self_hosted_counters == 'true') {
			//	add_action ( 'wp_footer', array ($this, 'essb_self_docount_code' ) );
				$this->using_self_counters = true;
			//}
		
		}
		
		$sidebar_draw_in_footer = isset ( $option ['sidebar_draw_in_footer'] ) ? $option ['sidebar_draw_in_footer'] : 'false';
		if ($sidebar_draw_in_footer == 'true') {			
			add_action ( 'wp_footer', array ($this, 'display_sidebar_in_footer' ) );
		}
		
		$woocommerce_share = isset ( $option ['woocommece_share'] ) ? $option ['woocommece_share'] : 'false';
		
		if ($woocommerce_share == "true") {
			add_action ( 'woocommerce_share', array ($this, 'handle_woocommerce_share' ) );
		}
		
		$wpec_before_desc = isset ( $option ['wpec_before_desc'] ) ? $option ['wpec_before_desc'] : 'false';
		$wpec_after_desc = isset ( $option ['wpec_after_desc'] ) ? $option ['wpec_after_desc'] : 'false';
		$wpec_theme_footer = isset ( $option ['wpec_theme_footer'] ) ? $option ['wpec_theme_footer'] : 'false';
		
		if ($wpec_before_desc == "true") {
			add_action ( 'wpsc_product_before_description', array ($this, 'handle_wpecommerce_share' ) );
		}
		if ($wpec_after_desc == "true") {
			add_action ( 'wpsc_product_addons', array ($this, 'handle_wpecommerce_share' ) );
		}
		
		if ($wpec_theme_footer == "true") {
			add_action ( 'wpsc_theme_footer', array ($this, 'handle_wpecommerce_share' ) );
		}
		
		// @since 1.3.9.8 JigoShop
		$jigoshop_top = isset($option['jigoshop_top']) ? $option['jigoshop_top'] : 'false';
		$jigoshop_bottom = isset($option['jigoshop_bottom']) ? $option['jigoshop_bottom'] : 'false';
		if ($jigoshop_top == 'true') {
			add_action ( 'jigoshop_before_single_product_summary', array ($this, 'handle_jigoshop_share' ) );
		}
		if ($jigoshop_bottom == 'true') {
			add_action ( 'jigoshop_after_main_content', array ($this, 'handle_jigoshop_share' ) );
		}
		
		
		// @since 1.2.6
		$bbpress_forum = isset ( $option ['bbpress_forum'] ) ? $option ['bbpress_forum'] : 'false';
		$bbpress_topic = isset ( $option ['bbpress_topic'] ) ? $option ['bbpress_topic'] : 'false';
		$buddypress_group = isset ( $option ['buddypress_group'] ) ? $option ['buddypress_group'] : 'false';
		$buddypress_activity = isset ( $option ['buddypress_activity'] ) ? $option ['buddypress_activity'] : 'false';
		$display_where = isset ( $option ['display_where'] ) ? $option ['display_where'] : '';
		
		if ($bbpress_topic == 'true') {
			if ('top' == $display_where || 'both' == $display_where || 'float' == $display_where || 'sidebar' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where) {
				add_action ( 'bbp_template_before_replies_loop', array ($this, 'bbp_show_before_replies' ) );
			}
			
			if ('bottom' == $display_where || 'both' == $display_where || 'popup' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where) {
				add_action ( 'bbp_template_after_replies_loop', array ($this, 'bbp_show_after_replies' ) );
			}
		}
		
		if ($bbpress_forum == "true") {
			if ('top' == $display_where || 'both' == $display_where || 'float' == $display_where || 'sidebar' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where) {
				add_action ( 'bbp_template_before_topics_loop', array ($this, 'bbp_show_before_topics' ) );
			}
			if ('bottom' == $display_where || 'both' == $display_where || 'popup' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where) {
				add_action ( 'bbp_template_after_topics_loop', array ($this, 'bbp_show_after_topics' ) );
			}
		}
		
		if ($buddypress_group == 'true') {
			add_action ( 'bp_before_group_home_content', array ($this, 'buddy_social_button_group_filter' ) );
		}
		if ($buddypress_activity == 'true') {
			add_action ( 'bp_activity_entry_meta', array ($this, 'buddy_social_button_activity_filter' ), 999 );
		}
		
		// @since 1.2.7
		/*$opengraph_tags = isset ( $option ['opengraph_tags'] ) ? $option ['opengraph_tags'] : 'false';
		
		if ($opengraph_tags == 'true') {
			// @since 1.3.9.8
			$opengraph = ESSB_OpenGraph::get_instance();
			// @since 1.3.7.3
			$opengraph->fbadmins = isset ( $option ['opengraph_tags_fbadmins'] ) ? $option ['opengraph_tags_fbadmins'] : '';
			$opengraph->fbpage = isset ( $option ['opengraph_tags_fbpage'] ) ? $option ['opengraph_tags_fbpage'] : '';
			$opengraph->fbapp = isset ( $option ['opengraph_tags_fbapp'] ) ? $option ['opengraph_tags_fbapp'] : '';
			$opengraph->default_image = isset ( $option ['sso_default_image'] ) ? $option ['sso_default_image'] : '';
			
			$opengraph->activate_opengraph_metatags ();
		}
		
		// @since 1.3.6
		$twitter_card_active = isset ( $option ['twitter_card'] ) ? $option ['twitter_card'] : 'false';
		
		if ($twitter_card_active == "true") {
			// @since 1.3.9.8 moved to singleton pattern
			$twitter_cards = ESSB_TwitterCards::get_instance();
			$twitter_cards->card_type = isset ( $option ['twitter_card_type'] ) ? $option ['twitter_card_type'] : '';
			$twitter_cards->twitter_user = isset ( $option ['twitter_card_user'] ) ? $option ['twitter_card_user'] : '';
			$twitter_cards->default_image = isset ( $option ['sso_default_image'] ) ? $option ['sso_default_image'] : '';
			
			$twitter_cards->activate_twittercards_metatags ();
		}
		*/
		// @since 1.3.9.5
		$this->essb_css_builder->insert_total_shares_text ();
		$this->essb_css_builder->handle_postfloat_custom_css ();
		$this->essb_css_builder->customizer_compile_css ( $this->skinned_social );
		
		
		$encode_url_nonlatin = isset ( $option ['encode_url_nonlatin'] ) ? $option ['encode_url_nonlatin'] : 'false';
		if ($encode_url_nonlatin == "true") {
			add_action ( 'template_redirect', array ($this, 'essb_sharing_process_requests' ), 9 );
		}		
	}
		
	function essb_sharing_process_requests() {
		if (isset( $_GET['easy-share'])) {
			$share_redirect_address = $_GET['easy-share'];
			
			//print "get = ".$share_redirect_address;
			
			$share_redirect_address = essb_base64url_decode($share_redirect_address);
			
			$split_url = explode('?', $share_redirect_address);
			$url_params = $split_url[1];
			
			$url_params_obj = explode('&', $url_params);
			$new_params = array();
			
			foreach ($url_params_obj as $singleParam) {
				$single = explode('=', $singleParam);
				
				$param = $single[0];
				$value = $single[1];
				
				if (false !== strpos($value, 'http://')) {
					$value = rawurlencode($value);
					$value = strtolower($value);
					//$value = str_replace('%', '%25', $value);
					$value = str_replace('%252f', '%2F', $value);
					$value = str_replace('%253a', '%3A', $value);
					$value = str_replace('%252F', '%2F', $value);
					$value = str_replace('%253A', '%3A', $value);
				}				
				else {
					//$value = urlencode_deep($value);
				}
				
				$new_params[] = $param.'='.$value;
			}
			
			$new_query_string = implode('&', $new_params);
			
			$share_redirect_address_encoded = $split_url[0].'?'.$new_query_string;
			
			//print $share_redirect_address_encoded;
			
			wp_redirect( $share_redirect_address_encoded );
			exit;
		}
	}
		
	function check_for_deactivation($post_object) {
		$is_deactivated = $this->is_plugin_deactivated_on_post();
		if ($is_deactivated) {
			remove_shortcode ( 'essb' );
			remove_shortcode ( 'easy-share' );
			remove_shortcode ( 'easy-social-share-buttons' );
			
			// @since 1.3.9.2
			remove_shortcode ('easy-social-like');
			remove_shortcode ('easy-social-like-simple');
			remove_shortcode ( 'easy-social-share' );
				
			remove_shortcode ('easy-total-shares');			
			
			remove_action ( 'the_content', array ($this, 'print_share_links' ), 10, 1 );
			remove_action ( 'the_excerpt', array ($this, 'print_share_links_excerpt' ), 10, 1 );
			
			remove_action ( 'woocommerce_share', array ($this, 'handle_woocommerce_share' ) );
			//remove_action ( 'wp_footer', array ($this, 'init_gplus_script' ) );

						//remove_action ( 'wp_footer', array ($this, 'init_vk_script' ) );
			//remove_action ( 'wp_footer', array ($this, 'display_sidebar_in_footer' ), 10 );

			//remove_action ( 'wp_ajax_nopriv_essb_action', array ($this, 'send_email' ) );
			//remove_action ( 'wp_ajax_essb_action', array ($this, 'send_email' ) );
			
			// @since 1.3.1
			remove_action ( 'wp_ajax_nopriv_essb_counts', array ($this, 'get_share_counts' ) );
			remove_action ( 'wp_ajax_essb_counts', array ($this, 'get_share_counts' ) );
			
			remove_action ( 'wp_ajax_nopriv_essb_self_postcount', array ($this, 'essb_self_updatepost_count' ) );
			remove_action ( 'wp_ajax_essb_self_postcount', array ($this, 'essb_self_updatepost_count' ) );
			
			if (ESSB_SELF_ENABLED) {
				//remove_action ( 'wp_ajax_nopriv_essb_self_counts', array ($this, 'get_self_share_counts' ) );
				//remove_action ( 'wp_ajax_essb_self_counts', array ($this, 'get_self_share_counts' ) );
					
				//remove_action ( 'wp_ajax_nopriv_essb_self_docount', array ($this, 'essb_self_docount' ) );
				//remove_action ( 'wp_ajax_essb_self_docount', array ($this, 'essb_self_docount' ) );
					
				//remove_action ( 'wp_footer', array ($this, 'essb_self_docount_code' ) );			
			}
		}
	}
	
	function is_plugin_deactivated_on_post() {
		global $post;
		$is_deactivated = false;

		if (isset($post)) {
			$current_query_id = $post->ID;
			$options = $this->options;
			$excule_from = isset($options['display_deactivate_on']) ? $options['display_deactivate_on'] : '';
								
			if ($excule_from != '') {
				$excule_from = explode(',', $excule_from);
			
				$excule_from = array_map('trim', $excule_from);
			
				if (in_array($current_query_id, $excule_from, false)) {
					$is_deactivated = true;
				}
			}
					
		}		
		
		return $is_deactivated;
	}
	
	
	function remove_buttons_excerpts($text) {
		
				//print "parsing text = ". $text;
		$option = $this->options;		
		if (is_array($option)) {
			$support_networks = $option['networks'];
			//print "current text =".$text . '|';

				foreach ($support_networks as $k => $v) {
					$network_name = isset($v[1]) ? $v[1] : $k;
					//print " replace ".$network_name;
					$text = str_replace($network_name, '', $text);
				}
			
			$message_above_share = isset($option['message_share_buttons']) ? $option['message_share_buttons'] : '';
			$text = str_replace($message_above_share, '', $text);
			$text = trim($text);
		}
		
		return $text;
	}
	
	function buddy_social_button_activity_filter() {
		// buddypress activity
		$activity_type = bp_get_activity_type();
		$activity_link = bp_get_activity_thread_permalink();
		$activity_title = bp_get_activity_feed_item_title();
		
		echo '<div style="clear: both;\"></div>';
		$options = $this->options;
		
		$essb_networks = $options['networks'];
		$buttons = "";
		foreach($essb_networks as $k => $v) {
			if( $v[0] == 1 ) {
				if ($buttons != '') { $buttons .= ","; }
				$buttons .= $k;
			}
			
		}
		$activity_title = str_replace ('[&#8230;]', '', $activity_title);
		$need_counters = $options['show_counter'] ? 1 : 0;
		$links = do_shortcode('[easy-share buttons="'.$buttons.'" counters=0 native="no" url="'.urlencode($activity_link).'" text="'.htmlspecialchars($activity_title).'" nostats="yes" hide_names="yes"]');
		
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	function buddy_social_button_group_filter() {
		// buddypress activity	
		$activity_link = bp_get_group_permalink();
		$activity_title =  bp_get_group_name();
		$options = $this->options;
		
		$essb_networks = $options['networks'];
		$buttons = "";
		foreach($essb_networks as $k => $v) {
			if( $v[0] == 1 ) {
				if ($buttons != '') {
					$buttons .= ",";
				}
				$buttons .= $k;
			}
			
		}
		$hidden_name_class = (isset($options['hide_social_name']) && $options['hide_social_name']==1) ? ' hide_names="yes" ' : '';
		$hidden_name_class = 'hide_names="yes"';
		$need_counters = $options['show_counter'] ? 1 : 0;
		//$need_counters = 0;
		$links = do_shortcode('[easy-share buttons="'.$buttons.'" counters='.$need_counters.' native="no"  nostats="yes"  url="'.$activity_link.'" text="'.$activity_title.'" '.$hidden_name_class.']');
		
		echo $links .'<div style="clear: both;\"></div>';
	}		
	
	function bbp_show_before_replies() {
		$topic_id = bbp_get_topic_id();

		$this->print_links_position = "top";
		
		$options = $this->options;
		$need_counters = $options['show_counter'] ? 1 : 0;
		$links = $this->generate_share_snippet(array(), $need_counters);
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	function bbp_show_after_replies() {
		$topic_id = bbp_get_topic_id();
		
		$this->print_links_position = "bottom";
		$options = $this->options;
		$need_counters = $options['show_counter'] ? 1 : 0;
		$links = $this->generate_share_snippet(array(), $need_counters);
	
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	function bbp_show_before_topics() {
		$this->print_links_position = "top";
		$options = $this->options;
		$need_counters = $options['show_counter'] ? 1 : 0;
		$links = $this->generate_share_snippet(array(), $need_counters);
	
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	function bbp_show_after_topics() {
		$this->print_links_position = "bottom";
		$options = $this->options;
		$need_counters = $options['show_counter'] ? 1 : 0;
		$links = $this->generate_share_snippet(array(), $need_counters);
	
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	public static function get_instance() {
		
		// If the single instance hasn't been set, set it now.
		if (null == self::$instance)
			self::$instance = new self ();
		
		return self::$instance;
	
	}
	
	/**
	 * Activate plugin
	 */
	public static function activate() {
		$option = get_option ( self::$plugin_settings_name );
		if (! $option || empty ( $option )) {
			update_option ( self::$plugin_settings_name, self::default_options () );
			update_option ('essb-first-time', 'true');
		}
		
		delete_option('essb-welcome-deactivated');
	}
	
	public static function deactivate() {
		//delete_option ( self::$plugin_settings_name );
		// remove schedule update check
		delete_option('essb-first-time');
		wp_clear_scheduled_hook('essb_update');
		
	}
	
	public static function default_options() {
		return array ('style' => 1, 'networks' => array ("facebook" => array (1, "Facebook" ), "twitter" => array (1, "Twitter" ), "google" => array (0, "Google+" ), "pinterest" => array (0, "Pinterest" ), "linkedin" => array (0, "LinkedIn" ), "digg" => array (0, "Digg" ), "stumbleupon" => array (0, "StumbleUpon" ), "vk" => array (0, "VKontakte" ), "tumblr" => array(0, "Tumblr"), "print" => array(0, "Print"), "mail" => array (1, "E-mail" ) ), 'show_counter' => 0, 'hide_social_name' => 0, 'target_link' => 1, 'twitter_user' => '', 'display_in_types' => array ('post' ), 'display_where' => 'bottom', 'mail_subject' => __ ( 'Visit this site %%siteurl%%', ESSB_TEXT_DOMAIN ), 'mail_body' => __ ( 'Hi, this may be intersting you: "%%title%%"! This is the link: %%permalink%%', ESSB_TEXT_DOMAIN ), 'colors' => array ("bg_color" => '', "txt_color" => '', 'facebook_like_button' => 'false' ) );
	}
		
	public function register_front_assets() {
		global $post;	

		// @since 1.3.9.6
		$is_deactivated_here = $this->is_plugin_deactivated_on_post();
		if ($is_deactivated_here) {
			$this->essb_js_builder->remove_hook(); 
			return; 
		}
		
		//$this->essb_css_builder->fix_css_float_from_top ();
		
		$options = $this->options;
		if (is_array ( $options )) {
			
			$essb_networks = $options['networks'];
			$mail_active = false;
			$love_active = false;
			
			$self_counters = false;
			$activate_self_hosted_counters = isset($options['activate_self_hosted_counters']) ? $options['activate_self_hosted_counters'] : 'false';
			if (ESSB_SELF_ENABLED && $activate_self_hosted_counters == 'true') {
				$self_counters = true;
				$this->using_self_counters = true;
			}				
			
			$use_minified_css = isset($options['use_minified_css']) ? $options['use_minified_css'] : 'false';
			$minifier = ($use_minified_css == "true") ? true : false;
			$this->css_minifier = $minifier;
			
			$use_minified_js = isset($options['use_minified_js']) ? $options['use_minified_js'] : 'false';
			$minifier_js = ($use_minified_js == "true") ? true : false;
			$this->js_minifier = $minifier_js;
			
			$scripts_in_head = isset($options['scripts_in_head']) ? $options['scripts_in_head'] : 'false';
			
			$load_footer = ($scripts_in_head == 'true') ? false : true;
			
			// @since 1.3.9.5
			if (isset($post) && $this->options_by_pt_active) {
				$pt_settings = EasySocialShareButtons_Advanced_Display::get_options_by_pt();
			}
			
			$post_native_skinned = '';
			
			if (isset($post)) {
				$post_native_skinned =  get_post_meta($post->ID,'essb_activate_nativeskinned',true);
			}
			
			foreach($essb_networks as $k => $v) {
				if( $v[0] == 1 && $k == 'mail') {
					$mail_active = true;
				}
				
				if( $v[0] == 1 && $k == 'love') {
					$love_active = true;
				}
			}
			
			if (is_numeric ( $options ['style'] )) {
				
				// @since 1.3.9.5
				if (isset($post) && $this->options_by_pt_active) {
					$pt_template = isset($pt_settings['template']) ? $pt_settings['template'] : '';
					
					if ($pt_template != '') {
						$options['style'] = intval($pt_template);
					}
				}
				
				$post_theme = '';
				if (isset($post)) {
					$post_theme =  get_post_meta($post->ID,'essb_theme',true);
				}
				
				if ($post_theme != "" && is_numeric($post_theme)) {
					$options['style'] = intval($post_theme);
				}							
				
				$folder = "default";
				
				if ($options ['style'] == 1) { $folder = "default"; }
				if ($options ['style'] == 2) { $folder = "metro"; }
				if ($options ['style'] == 3) { $folder = "modern"; }
				if ($options ['style'] == 4) {
					$folder = "round";
				}
				if ($options ['style'] == 5) {
					$folder = "big";
				}
				if ($options ['style'] == 6) {
					$folder = "metro-retina";
				}
				if ($options ['style'] == 7) {
					$folder = "big-retina";
				}
				if ($options ['style'] == 8) {
					$folder = "light-retina";
				}
				if ($options ['style'] == 9) {
					$folder = "flat-retina";
				}
				if ($options ['style'] == 10) {
					$folder = "tiny-retina";
				}
				if ($options ['style'] == 11) {
					$folder = "round-retina";
				}
				if ($options ['style'] == 12) {
					$folder = "modern-retina";
				}
				
				if ($minifier) {
					wp_enqueue_style ( 'easy-social-share-buttons', ESSB_PLUGIN_URL . '/assets/css/' . $folder . '/' . 'easy-social-share-buttons.min.css', false, $this->version, 'all' );
				}
				else {
					wp_enqueue_style ( 'easy-social-share-buttons', ESSB_PLUGIN_URL . '/assets/css/' . $folder . '/' . 'easy-social-share-buttons.css', false, $this->version, 'all' );
				}
				
				$this->loaded_template_slug = $folder;
			}
			
			$pt_counters = "";
			if (isset($post) && $this->options_by_pt_active) {
				$pt_counters = isset($pt_settings['counters']) ? $pt_settings['counters'] : '';
				if ($pt_counters != '') {
					$options ['show_counter'] = intval($pt_counters);
				}
			}
			
			$post_counters = '';
			if (isset($post)) {
				$post_counters =  get_post_meta($post->ID,'essb_counter',true);
			}
			
			if ($post_counters != '') {
				$options ['show_counter'] = intval($post_counters);
			}
			
			if (is_numeric ( $options ['show_counter'] ) && $options ['show_counter'] == 1) {
				$this->counter_included = true;
				if ($minifier_js) {
					if (!$this->load_js_async) {
						if ($this->using_self_counters) {
							wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js', array ( 'jquery' ), $this->version, $load_footer );
						}
						else {
							wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js', array ( 'jquery' ), $this->version, $load_footer );
						}
					}	
					else {
						if ($this->using_self_counters) {
							$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js');
							//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js';
						}
						else {
							$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js');								
							//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js';
						}
					}					
				}
				else {
					if (!$this->load_js_async) {
						if ($this->using_self_counters) {
							wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js', array ( 'jquery' ), $this->version, $load_footer );
						}
						else {
							wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js', array ( 'jquery' ), $this->version, $load_footer );
						}
					}
					else {
						if ($this->using_self_counters) {
							$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js');
							//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js';
						}
						else {
							$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js');
							//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js';
						}
					}
				}
			}
			
			$display_where = isset($options['display_where']) ? $options['display_where'] : '';		
			
			$display_float_js = isset($options['float_js']) ? $options['float_js'] : '';
			
			$pt_display_where = "";
			if (isset($post) && $this->options_by_pt_active) {
				$pt_display_where = isset($pt_settings['position']) ? $pt_settings['position'] : '';
				if ($pt_display_where != '') {
					$display_where = $pt_display_where;
				}
			}
			
			$post_display_where = '';
			if (isset($post)) {
				$post_display_where = get_post_meta($post->ID,'essb_position',true);
			}
			if ($post_display_where != "") { $display_where = $post_display_where; }			
			
			// @since 1.3.8.2 - mobile display render in alternative way
			if ($this->isMobile()){
				$display_position_mobile = isset($options['display_position_mobile']) ? $options['display_position_mobile'] : '';
					
				if ($display_position_mobile != '') {
					$display_where = $display_position_mobile;
				}
			}
				
			
			if ($display_where == "float") {
				if ($display_float_js == "true") {
					if ($minifier_js) {
						wp_enqueue_script ( 'essb-float-script', ESSB_PLUGIN_URL . '/assets/js/essb-float-js.min.js', array ('jquery' ), $this->version, $load_footer );
					}
					else {
						wp_enqueue_script ( 'essb-float-script', ESSB_PLUGIN_URL . '/assets/js/essb-float-js.js', array ('jquery' ), $this->version, $load_footer );
					}
				}
				else {
					if ($minifier_js) {
						if (!$this->load_js_async) {
							wp_enqueue_script ( 'essb-float-script', ESSB_PLUGIN_URL . '/assets/js/essb-float.min.js', array ('jquery' ), $this->version, $load_footer );
						}
						else {
							$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/essb-float.min.js');
							//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/essb-float.min.js';
						}
					}
					else {
						if (!$this->load_js_async) {
							wp_enqueue_script ( 'essb-float-script', ESSB_PLUGIN_URL . '/assets/js/essb-float.js', array ('jquery' ), $this->version, $load_footer );
						}
						else {
							$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/essb-float.js');
							//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/essb-float.js';
						}
					}
				}
			}
			
			if ($display_where == "sidebar" || $display_where == "postfloat") {
				if ($minifier) {
					wp_enqueue_style ( 'easy-social-share-buttons-sidebar', ESSB_PLUGIN_URL . '/assets/css/essb-sidebar.min.css', false, $this->version, 'all' );
				}
				else {
					wp_enqueue_style ( 'easy-social-share-buttons-sidebar', ESSB_PLUGIN_URL . '/assets/css/essb-sidebar.css', false, $this->version, 'all' );
				}
			}

			if ($display_where == "popup") {
				if ($minifier) {
					wp_enqueue_style ( 'easy-social-share-buttons-popup', ESSB_PLUGIN_URL . '/assets/css/essb-popup.min.css', false, $this->version, 'all' );
				}
				else {
					wp_enqueue_style ( 'easy-social-share-buttons-popup', ESSB_PLUGIN_URL . '/assets/css/essb-popup.css', false, $this->version, 'all' );
				}
				
				if ($minifier_js) {
					if (!$this->load_js_async) {
						wp_enqueue_script ( 'essb-popup-script', ESSB_PLUGIN_URL . '/assets/js/essb-popup.min.js', array ('jquery' ), $this->version, $load_footer );
					}
					else {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/essb-popup.min.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/essb-popup.min.js';
					}
				}
				else {
					if (!$this->load_js_async) {
						wp_enqueue_script ( 'essb-popup-script', ESSB_PLUGIN_URL . '/assets/js/essb-popup.js', array ('jquery' ), $this->version, $load_footer );
					}
					else {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/essb-popup.js');
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/essb-popup.js';
					}
				}
			}
				
			$youtube_button = isset($options['youtubesub']) ? $options['youtubesub'] : 'false';
			if ($youtube_button == 'true') {
				//https://apis.google.com/js/platform.js
				//wp_enqueue_script ( 'essb-youtube-subscribe', 'https://apis.google.com/js/platform.js', array ('jquery' ), $this->version, $load_footer );
				$this->essb_js_builder->add_js_lazyload('https://apis.google.com/js/platform.js');
			}	
			
			$pinfollow = isset($options['pinterestfollow']) ? $options['pinterestfollow'] : 'false';
			if ($pinfollow == "true") {
				//wp_enqueue_script ( 'essb-pinterest-follow', '//assets.pinterest.com/js/pinit.js', array ('jquery' ), $this->version, $load_footer );
				$this->essb_js_builder->add_js_lazyload('//assets.pinterest.com/js/pinit.js');
				$this->pinjs_registered = true;
			}
			
			
			if ($mail_active) {
				// @since 1.1 mail contact form
				// @thanks nextpulse - performace move
				if ($minifier) {
					wp_enqueue_style ( 'easy-social-share-buttons-mailform', ESSB_PLUGIN_URL . '/assets/css/essb-mailform.min.css', false, $this->version, 'all' );
				}
				else {
					wp_enqueue_style ( 'easy-social-share-buttons-mailform', ESSB_PLUGIN_URL . '/assets/css/essb-mailform.css', false, $this->version, 'all' );
				}
				wp_enqueue_script ( 'easy-social-share-buttons-mailform', ESSB_PLUGIN_URL . '/assets/js/essb-mailform.js', array ('jquery' ), $this->version, $load_footer );
				$this->mailjs_registered = true;
			}		

			$include_twitter = isset($options['twitterfollow']) ? $options['twitterfollow'] : 'false';
			if ($include_twitter == 'true') {
				//wp_enqueue_script ( 'twitter-essb', 'http://platform.twitter.com/widgets.js', array ('jquery' ) );
			}

			
			
			// @since 1.2.7 stats inject js is moved to footer
			$stats_active = isset($options['stats_active']) ? $options['stats_active'] : 'false';
			if ($stats_active ==  'true') {
				//$this->stats = EasySocialShareButtons_Stats::get_instance();//$stats_instance;
				//$this->stats = new EasySocialShareButtons_Stats();
				//add_action ( 'wp_footer', array ($this->stats, 'generate_log_js_code' ) );
				$this->essb_js_builder->add_js_code($this->stats->generate_log_js_code_jsbuilder());				
			}
			
			// @since 1.3.9.1 only if love you button is active to insert code
			if ($love_active) {
				//add_action('wp_footer', array ($this->love, 'generate_loveyou_js_code' ) );
				$this->essb_js_builder->add_js_code($this->love->generate_loveyou_js_code_jsbuilder());
			}
			
			$sidebar_sticky = isset($options['sidebar_sticky']) ? $options['sidebar_sticky'] : 'false';
			if ($sidebar_sticky == 'true') {
				if ($minifier_js) {
					wp_enqueue_script ( 'essb-sticky-sidebar', ESSB_PLUGIN_URL . '/assets/js/essb-sticky-sidebar.min.js', array ('jquery' ), $this->version, $load_footer );
				}
				else {
					wp_enqueue_script ( 'essb-sticky-sidebar', ESSB_PLUGIN_URL . '/assets/js/essb-sticky-sidebar.js', array ('jquery' ), $this->version, $load_footer );
				}
			}
			
			/// @test
			$skin_native = isset($options['skin_native']) ? $options['skin_native'] : 'false';
			
			if ($post_native_skinned != '') {
				if ($post_native_skinned == "yes") { 
					$skin_native = "true";
				}

				if ($post_native_skinned == "no") {
					$skin_native = "false";
				}
			}
			
			if ($skin_native == 'true') { 
				$this->skinned_social = true; 
				ESSB_Skinned_Native_Button::registerCSS();
			}
			else {
				$this->skinned_social = false;
			}
		}
		
		//if ($this->load_js_async) {
		//	add_action ( 'wp_footer', array ($this, 'init_async_js_scripts' ) );
		//}
		
		//add_action ( 'wp_footer', array ($this, 'essb_self_updatepost_count_code' ) );
		$this->essb_js_builder->add_js_code($this->essb_self_updatepost_count_code());
	}
	
	public function get_current_url($mode = 'base') {
		
		$url = 'http' . (is_ssl () ? 's' : '') . '://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		
		switch ($mode) {
			case 'raw' :
				return $url;
				break;
			case 'base' :
				return reset ( explode ( '?', $url ) );
				break;
			case 'uri' :
				$exp = explode ( '?', $url );
				return trim ( str_replace ( home_url (), '', reset ( $exp ) ), '/' );
				break;
			default :
				return false;
		}
	}
	
	public function generate_more_button_js($type = '1') {
		//$this->more_button_code_inserted = true;
		if ($type == '2' || $type == '3') {
			return $this->generate_more_button_js_popup();
		}
		else {
			return $this->generate_more_button_js_inline();
		}
	}
	
	public function generate_more_button_js_popup() {
		$output = '';
		
		$output .= 'function essb_toggle_more(unique_id) {
	jQuery.fn.extend({
        center: function () {
            return this.each(function() {
                var top = (jQuery(window).height() - jQuery(this).outerHeight()) / 2;
                var left = (jQuery(window).width() - jQuery(this).outerWidth()) / 2;
                jQuery(this).css({position:\'fixed\', margin:0, top: (top > 0 ? top : 0)+\'px\', left: (left > 0 ? left : 0)+\'px\'});
            });
        }
    }); 
	
	var win_width = jQuery( window ).width();
	var doc_height = jQuery(\'document\').height();
	
	var base_width = 370;
	
	if (win_width < base_width) { base_width = win_width - 60; }
	var content = jQuery(\'.essb_displayed_more_popup\').html();
	content = \'<div class="essb_more_popup"><div class="essb_more_popup_content"><div class="essb_more_popup_button_close"><a href="#" onclick="essb_toggle_less(); return false;">X</a></div>\'+content+\'</div></div><div class="essb_more_popup_shadow"></div>\';
	jQuery("body").append(content);
	
	jQuery(".essb_more_popup").css( { width: base_width+\'px\'});
	jQuery(".essb_more_popup").center();
	jQuery(".essb_more_popup_shadow").css( { display: \'block\'});

}

function essb_toggle_less() {
	jQuery(".essb_more_popup").css( { display: \'none\'});		
	jQuery(".essb_more_popup_shadow").css( { display: \'none\'});
};';
		
		return $output;
	}
	
	public function generate_more_button_js_inline() {
		$output = "";
		
		$output .= 'jQuery(document).ready(function($){
			jQuery.fn.essb_toggle_more = function(){
				return this.each(function(){
					$single = $(this);
						
					$single.removeClass(\'essb_after_more\');
					$single.addClass(\'essb_before_less\');
				});
			};
			jQuery.fn.essb_toggle_less = function(){
				return this.each(function(){
					$single = $(this);
						
					$single.addClass(\'essb_after_more\');
					$single.removeClass(\'essb_before_less\');
				});
			};
		});
		function essb_toggle_more(unique_id) {
			jQuery(\'.essb_\'+unique_id+\' .essb_after_more\').essb_toggle_more();
			$more_button = jQuery(\'.essb_\'+unique_id).find(\'.essb_link_more\');
			if (typeof($more_button) != "undefined") {
				$more_button.hide();
				$more_button.addClass(\'essb_hide_more_sidebar\');
			}
		};
		
		function essb_toggle_less(unique_id) {
			jQuery(\'.essb_\'+unique_id+\' .essb_before_less\').essb_toggle_less();
			$more_button = jQuery(\'.essb_\'+unique_id).find(\'.essb_link_more\');
			if (typeof($more_button) != "undefined") {
				$more_button.show();
				$more_button.removeClass(\'essb_hide_more_sidebar\');
			};
		};';
		
		return $output;
	}
	
	public function generate_share_snippet($networks = array(), $counters = 0, $is_current_page_url = 0, $is_shortcode = 0, $custom_share_text = '', $custom_share_address = '', 
			$shortcode_native = 'yes', $shortcode_sidebar = 'no', $shortcode_messages = 'no', $shortcode_popup = 'no', $shortcode_popafter = '', 
			$shortcode_custom_shareimage = '', $shortcode_custom_sharemessage = '', $shortcode_custom_fblike_url = '', $shortcode_custom_pluson_url = '',
			$shortcode_native_show_fblike = 'no', $shortcode_native_show_twitter = 'no', $shortcode_native_show_plusone = 'no', $shortcode_native_show_vk = 'no',
			$shortcode_hide_network_names = 'no', $shortcode_counter_pos = '', $shortcode_sidebar_pos = '', $shortcode_native_show_youtube = 'no', $shortcode_native_show_pinfollow = 'no', $shortcode_force_nostats = 'no',
			$shortcode_hide_total_count = 'no', $shortcode_total_count_pos = '', $shortcode_full_width = 'no', $shortcode_fullwidth_fix = '', $shortcode_native_show_wpmanaged = 'no', $shortcode_custom_button_texts = array(), 
			$shortcode_float = 'no', $shortcode_fixed_width = 'no', $shortcode_fixed_width_value = '', $shortcode_postfloat = 'no', $shortcode_morebutton = '', $shortcode_force_current = 'no',
			$shortcode_custom_messages = array(), $shortcode_video_share = 'no', $shortcode_template = '') {
	
		global $post;
		// get the plugin options
		$options = $this->options;
		$force_wp_query_postid = isset($options['force_wp_query_postid']) ? $options['force_wp_query_postid'] : 'false';
		
		if ($is_shortcode && $shortcode_force_current == 'yes') {
			$force_wp_query_postid = 'true';
		}
		
		if ($force_wp_query_postid == "true") {
			$current_query_id = get_queried_object_id();
			$post = get_post($current_query_id);
		}
		
		$self_count_post_id = isset($post) ? $post->ID : 0;
		
		//force_wp_query_postid
		$essb_off = get_post_meta($post->ID,'essb_off',true);
		
		if ($essb_off == "true") { $show_me = false; } else {$show_me = true;}
		//		$show_me =  (get_post_meta($post->ID,'essb_off',true)== 1) ? false : true;			
		$show_me = 	$is_shortcode ? true : $show_me;
		//print $show_me;
		$post_display_where = get_post_meta($post->ID,'essb_position',true);
		$post_hide_network_names = get_post_meta($post->ID,'essb_names',true);
			
		
		$bp_set_post_hide_network_names = ($post_hide_network_names != '') ? true : false;
		
		$force_network_hide_from_shortcode = false;
		if ($is_shortcode && $shortcode_hide_network_names == "force") { $force_network_hide_from_shortcode = true; }
		
		$pt_settings = array();
		if ($this->options_by_pt_active && isset($post)) {
			$pt_settings = EasySocialShareButtons_Advanced_Display::get_options_by_pt();
			
			$pt_display_where = isset($pt_settings['position']) ? $pt_settings['position'] : '';
			$pt_hide_network_names = isset($pt_settings['hidenames']) ? $pt_settings['hidenames'] : '';
			
			if ($pt_display_where != '' && $post_display_where == '') { $post_display_where = $pt_display_where; }
			if ($pt_hide_network_names != '' && $post_hide_network_names == '') { $post_hide_network_names = $pt_hide_network_names; }				
		}
		
		$post_hide_fb = get_post_meta($post->ID,'essb_hidefb',true);
		$post_hide_plusone = get_post_meta($post->ID,'essb_hideplusone',true);
		$post_hide_vk = get_post_meta($post->ID,'essb_hidevk',true);
		$post_hide_twitter = get_post_meta($post->ID, 'essb_hidetwitter', true);
		
		// @since 1.2.3
		$post_hide_youtube = get_post_meta($post->ID, 'essb_hideyoutube', true);
		$post_hide_pinfollow = get_post_meta($post->ID, 'essb_hidepinfollow', true);
		
		$post_hide_wpmanaged = "";
		
		// @since 1.2.1
		$shortcode_force_fblike = false;
		$shortcode_force_twitter = false;
		$shortcode_force_vk = false;
		$shortcode_force_plusone = false;
		$shortcode_force_youtube = false;
		$shortcode_force_pinfollow = false;
		$shortcode_force_wpamanged = false;
		if ($is_shortcode) {
			if ($shortcode_native_show_fblike == "yes") { $post_hide_fb = "no"; $shortcode_force_fblike = true; }
			if ($shortcode_native_show_plusone == "yes") { $post_hide_plusone = "no"; $shortcode_force_plusone = true; }
			if ($shortcode_native_show_twitter == "yes") { $post_hide_twitter = "no"; $shortcode_force_twitter = true; }
			if ($shortcode_native_show_vk == "yes") { $post_hide_vk = "no"; $shortcode_force_vk = true; }
			
			// @since 1.2.3
			if ($shortcode_native_show_youtube == "yes") { $post_hide_youtube = "no"; $shortcode_force_youtube = true; }
			if ($shortcode_native_show_pinfollow == "yes") { $post_hide_pinfollow = "no";  $shortcode_force_pinfollow = true; }
			
			if ($shortcode_native_show_wpmanaged == "yes") { $post_hide_wpmanaged = "no"; $shortcode_force_wpamanged = true; }
			
			if ($shortcode_hide_network_names == "yes") { $post_hide_network_names = '1'; }		
		}
		
		$post_sidebar_pos = get_post_meta($post->ID, 'essb_sidebar_pos', true);
		$post_counter_pos = get_post_meta($post->ID, 'essb_counter_pos', true);
		$post_total_counter_pos = get_post_meta($post->ID, 'essb_total_counter_pos', true);
		
		$bp_set_post_counter_pos = ($post_counter_pos != '') ? true : false;
		$bp_set_post_total_counter_pos = ($post_total_counter_pos != '') ? true : false;
		
		if ($this->options_by_pt_active && isset($post)) {
			$pt_sidebar_pos = isset($pt_settings['sidebar_pos']) ? $pt_settings['sidebar_pos'] : '';
			$pt_counter_pos = isset($pt_settings['counters_pos']) ? $pt_settings['counters_pos'] : '';
			$pt_total_counter_pos = isset($pt_settings['total_counters_pos']) ? $pt_settings['total_counters_pos'] : '';
			
			if ($pt_sidebar_pos != '' && $post_sidebar_pos == '') { $post_sidebar_pos = $pt_sidebar_pos; }
			if ($pt_counter_pos != '' && $post_counter_pos == '') { $post_counter_pos = $pt_counter_pos; }
			if ($pt_total_counter_pos != '' && $post_total_counter_pos == '') { $post_total_counter_pos = $pt_total_counter_pos; }
		}
		
		if ($is_shortcode && $shortcode_total_count_pos != '') {
			$post_total_counter_pos = $shortcode_total_count_pos;
		}
		
		// @since 1.2.1
		if ($is_shortcode && $shortcode_counter_pos != '' ) {
			$post_counter_pos = $shortcode_counter_pos;
		}
		
		if ($is_shortcode && $shortcode_sidebar_pos != '') {
			$post_sidebar_pos = $shortcode_sidebar_pos;
		}
		
		$cookie_loved_page = isset($_COOKIE['essb_love_'. $post->ID]) ? true : false;
		
		// custom_share_message_address		
		$post_essb_post_share_message = get_post_meta($post->ID, 'essb_post_share_message', true);
		$post_essb_post_share_url = get_post_meta($post->ID, 'essb_post_share_url', true);
		$post_essb_post_share_image = get_post_meta($post->ID, 'essb_post_share_image', true);
		$post_essb_post_share_text = get_post_meta($post->ID, 'essb_post_share_text', true);
		$post_essb_post_fb_url = get_post_meta($post->ID, 'essb_post_fb_url', true);
		$post_essb_post_plusone_url = get_post_meta($post->ID, 'essb_sidebar_pos', true);
		
		$post_essb_twitter_username = get_post_meta($post->ID, 'essb_post_twitter_username', true);
		$post_essb_twitter_hastags = get_post_meta($post->ID, 'essb_post_twitter_hashtags', true);
		$post_essb_twitter_tweet = get_post_meta($post->ID, 'essb_post_twitter_tweet', true);
		
		$post_essb_as = get_post_meta($post->ID, 'essb_as', true);
		
		$salt = mt_rand ();
		
		// show buttons only if post meta don't ask to hide it, and if it's not a shortcode.
		if ( $show_me ) {
	
			// @since 1.3.9.6 - check if it is shortcode then options by bp will not be executed otherwise we correct state with possible values from settings
			if ($is_shortcode) {
				$this->options_by_bp_active = false;
			}
			else {
				$this->set_options_by_bp_activate_state();
			}
			
			// texts, URL and image to share
			$text = esc_attr(urlencode($post->post_title));
			$url = $post ? get_permalink() : $this->get_current_url( 'raw' );
			if ($this->avoid_next_page) {
				$url = $post ? get_permalink($post->ID) : $this->get_current_url( 'raw' );
			}
			//$url = urlencode(get_permalink());
			if ( $is_current_page_url ) {
				$url = $this->get_current_url( 'raw' );
			}
			$url = apply_filters('essb_the_shared_permalink', $url);
			$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : '';
	
			$pinterest_image = ($image != '') ? $image[0] : '';
			$pinterest_desc = $post->post_excerpt;
			$post_image = ($image != '') ? $image[0] : '';
			$post_desc = $post->post_excerpt;
			
			// some markup filters
			$hide_intro_phrase 			= apply_filters('eesb_network_name', true);
			$share_the_post_sentence 	= apply_filters('eesb_intro_phrase_text', __('Share the post',ESSB_TEXT_DOMAIN) );
			$before_the_sps_content 	= apply_filters('eesb_before_the_snippet', '');
			$after_the_sps_content 		= apply_filters('eesb_after_the_snippet', '');
			$before_the_list 			= apply_filters('eesb_before_the_list', '');
			$after_the_list 			= apply_filters('eesb_after_the_list', '');
			$before_first_i 			= apply_filters('eesb_before_first_item', '');
			$after_last_i 				= apply_filters('eesb_after_last_item', '');
			$container_classes 			= apply_filters('eesb_container_classes', '');
			$rel_nofollow 				= apply_filters('eesb_links_nofollow', 'rel="nofollow"');
	
			// markup filters
			$div 	= apply_filters('eesb_container_tag', 'div');
			$p 		= apply_filters('eesb_phrase_tag', 'p');
			$ul 	= apply_filters('eesb_list_container_tag', 'ul');
			$li 	= apply_filters('eesb_list_of_item_tag', 'li');
		
			// @since 1.2.6
			$stats_active = isset($options['stats_active']) ? $options['stats_active'] : 'false';
			
			if ($is_shortcode && $shortcode_force_nostats == 'yes') { $stats_active = 'false'; }
			
			// classes and attributes options
			$target_link = (isset($options['target_link']) && $options['target_link']==1) ? ' target="_blank"' : '';
			$hidden_name_class = (isset($options['hide_social_name']) && $options['hide_social_name']==1) ? ' essb_hide_name' : '';
			if ($is_shortcode && $shortcode_hide_network_names == "show") {
				$hidden_name_class = "";
			}
			
			//@since 1.3.9.2
			$always_hide_names_mobile = (isset($options['always_hide_names_mobile'])) ? $options['always_hide_names_mobile'] : 'false';
			if ($always_hide_names_mobile == 'true' && $this->isMobile()) {
				$hidden_name_class = ' essb_hide_name';
			}
			
			$container_classes .= (intval($counters)==1) ? ' essb_counters' : '';
			$counter_pos = isset($options['counter_pos']) ? $options['counter_pos'] : '';
			$total_counter_pos = isset($options['total_counter_pos']) ? $options['total_counter_pos'] : '';
			// @since 1.3.9.6
			$total_counter_hidden_till = isset($options['total_counter_hidden_till']) ? $options['total_counter_hidden_till'] : '';
			$total_counter_hidden_till = trim($total_counter_hidden_till);
			
			$css_hide_total_counter = "";
			if ($total_counter_hidden_till != '') {
				$css_hide_total_counter = ' style="display: none;" essb-hide-till="'.$total_counter_hidden_till.'"';
			}
			
			if ($post_counter_pos != '') { $counter_pos = $post_counter_pos; }
			if ($post_total_counter_pos != '') { $total_counter_pos = $post_total_counter_pos; }
			
			$stumble_noshortlink = isset($options['stumble_noshortlink']) ? $options['stumble_noshortlink'] : 'false';
			
			$url_short_native = isset($options['url_short_native']) ? $options['url_short_native'] : 'false';
			$url_short_google = isset($options['url_short_google']) ? $options['url_short_google'] : 'false';
			
			$url_short_bitly =  isset($options['url_short_bitly']) ? $options['url_short_bitly'] : 'false';
			$url_short_bitly_user =  isset($options['url_short_bitly_user']) ? $options['url_short_bitly_user'] : '';
			$url_short_bitly_api =  isset($options['url_short_bitly_api']) ? $options['url_short_bitly_api'] : '';
				
			$facebook_simplesharing = 'true';//isset($options['facebooksimple']) ? $options['facebooksimple'] : 'false';
			$facebook_totalcount = isset($options['facebooktotal']) ? $options['facebooktotal'] : 'false';
			
			// @ from 1.2.9
			$facebook_advanced_sharing = isset($options['facebookadvanced']) ? $options['facebookadvanced'] : 'false';
			$facebook_advanced_sharing_appid = isset($options['facebookadvancedappid']) ? $options['facebookadvancedappid'] : '';
				
			$custom_like_url_active = (isset($options['custom_url_like'])) ? $options['custom_url_like'] : 'false'; 
			$custom_like_url = (isset($options['custom_url_like_address'])) ? $options['custom_url_like_address'] : '';
			$custom_plusone_address = (isset($options['custom_url_plusone_address'])) ? $options['custom_url_plusone_address'] : '';//custom_url_plusone_address

			$native_counters = (isset($options['native_social_counters'])) ? $options['native_social_counters'] : 'false'; 
			$native_counters_fb = (isset($options['native_social_counters_fb'])) ? $options['native_social_counters_fb'] : 'false';
			$native_fb_width = (isset($options['facebook_like_button_width'])) ? $options['facebook_like_button_width'] : '';
			$native_counters_g = (isset($options['native_social_counters_g'])) ? $options['native_social_counters_g'] : 'false';
			$native_counters_t = (isset($options['native_social_counters_t'])) ? $options['native_social_counters_t'] : 'false';
			$native_counters_big = (isset($options['native_social_counters_boxes'])) ? $options['native_social_counters_boxes'] : 'false';
			$native_counters_youtube = (isset($options['native_social_counters_youtube'])) ? $options['native_social_counters_youtube'] : 'false';		
			
			// @since 1.3.9.8 - moved to general counter option
			$allnative_counters = isset($options['allnative_counters']) ? $options['allnative_counters'] : 'false';
			$native_counters_fb = $allnative_counters;
			$native_counters_g = $allnative_counters;
			$native_counters_t = $allnative_counters;
			$native_counters_youtube = $allnative_counters;
			
			$more_button_func = (isset($options['more_button_func'])) ? $options['more_button_func'] : '';

			if ($is_shortcode && $shortcode_morebutton != '') {
				$more_button_func = $shortcode_morebutton;
			}
				
			$force_hide_total_count = isset($options['force_hide_total_count']) ? $options['force_hide_total_count'] : 'false';
			// @since 1.3.1
			$force_counter_adminajax = isset($options['force_counters_admin']) ? $options['force_counters_admin'] : 'false';
			
			if ($post_total_counter_pos == "hidden") { $force_hide_total_count = "true";  }
			if ($is_shortcode && $shortcode_hide_total_count == 'yes') { $force_hide_total_count = 'true'; }
			
			$native_lang = isset($options['native_social_language']) ? $options['native_social_language'] : "en";
				
			// @since 1.1.5 popup
			$popup_window_title = (isset($options['popup_window_title'])) ? $options['popup_window_title'] : '';
			$popup_window_close = (isset($options['popup_window_close_after'])) ? $options['popup_window_close_after'] : '';
			
			// @since 1.1.7
			$custom_sidebar_pos = (isset($options['sidebar_pos'])) ? $options['sidebar_pos'] : '';
			if ($post_sidebar_pos != '') { $custom_sidebar_pos = $post_sidebar_pos; }
			if ($custom_sidebar_pos == "left") { $custom_sidebar_pos = ""; }
			
			// @since 1.3.9.6
			$sidebar_bottom_align = isset($options['sidebar_bottom_align']) ? $options['sidebar_bottom_align'] : '';
			
			// @since 1.1.6 popafter
			$popup_popafter = (isset($options['popup_window_popafter'])) ? $options['popup_window_popafter'] : '';
 			if ($is_shortcode && $shortcode_popafter != '') {
				$popup_popafter = $shortcode_popafter;
			}

			// @since 1.2.1
			
			if ($post_essb_post_fb_url != '' || $post_essb_post_plusone_url != '') { 
				$custom_like_url_active = "true";
				if ($post_essb_post_fb_url != '') { $custom_like_url = $post_essb_post_fb_url; }
				if ($post_essb_post_plusone_url != '' ) { $custom_plusone_address = $post_essb_post_plusone_url; }
			}
			
			if ($is_shortcode && ($shortcode_custom_fblike_url != '' || $shortcode_custom_pluson_url != '')) { $custom_like_url_active = "true"; }
			if ($is_shortcode && $shortcode_custom_fblike_url != '') { $custom_like_url = $shortcode_custom_fblike_url; }
			if ($is_shortcode && $shortcode_custom_pluson_url != '') {
				$custom_plusone_address = $shortcode_custom_pluson_url;
			}
			
			if ($custom_like_url_active == 'false') { $custom_like_url = ""; $custom_plusone_address = ""; }
			
			$stumble_fullurl = $url;
			$twitter_fullurl = $url;
			$is_active_shorturl = false;
			$is_set_twitter_counturl = false;
			
			if ($url_short_native == 'true') {
				$short_url = wp_get_shortlink();
				if ($short_url != '') { $url = $short_url; $is_active_shorturl = true;}
			}
			
			if ($url_short_google == 'true') { 
				$url = EasySocialShareButtons_ShortUrl::google_shorten($url);
				$is_active_shorturl = true;
			}
			
			if ($url_short_bitly == "true") {
				$url = EasySocialShareButtons_ShortUrl::bitly_shorten($url, $url_short_bitly_user, $url_short_bitly_api);
				$is_active_shorturl = true;
			}
	
			// custom share message
			$active_custom_message =isset($options['customshare']) ? $options['customshare'] : 'false';
			$is_from_customshare = false;
			
			if ($facebook_advanced_sharing == 'true')  { $is_from_customshare = true; } 
			
			$custom_share_imageurl = isset($options['customshare_imageurl']) ? $options['customshare_imageurl'] : '';
			$custom_share_description = isset($options['customshare_description']) ? $options['customshare_description'] : '';
				
			// @since 1.2.1
			if ($post_essb_post_share_image != '' || $post_essb_post_share_message != '' || $post_essb_post_share_text != '' || $post_essb_post_share_url != '') {
				$active_custom_message = "true";
				
				if ($post_essb_post_share_image != '') { $custom_share_imageurl = $post_essb_post_share_image; }
				if ($post_essb_post_share_message != '') { $custom_share_text = $post_essb_post_share_message; }
				if ($post_essb_post_share_text != '') { $custom_share_description = $post_essb_post_share_text; }
				if ($post_essb_post_share_url != '') { $custom_share_address = $post_essb_post_share_url; }
			}
			
			
			if ($is_shortcode && $shortcode_custom_sharemessage != '') {
				$custom_share_description = $shortcode_custom_sharemessage;
			}
			if ($is_shortcode && $shortcode_custom_shareimage != '') {
				$custom_share_imageurl = $shortcode_custom_shareimage;
			}
			
			if ($is_shortcode && $shortcode_custom_sharemessage != '') {
			
			}
			
			$pinterest_sniff_disable = isset($options['pinterest_sniff_disable']) ? $options['pinterest_sniff_disable'] : 'false';
			
			$include_twitter = isset($options['twitterfollow']) ? $options['twitterfollow'] : 'false';
			$include_twitter_user = isset($options['twitterfollowuser']) ? $options['twitterfollowuser'] : '';
			
			$include_twitter_type = isset($options['twitter_tweet']) ? $options['twitter_tweet'] : '';
			
			// @since 1.3.9.8
			$include_facebook_type = isset($options['facebook_like_type']) ? $options['facebook_like_type'] : '';
			$include_facebook_follow_profile = isset($options['facebook_follow_profile']) ? $options['facebook_follow_profile'] : '';
			$include_google_type = isset($options['google_like_type']) ? $options['google_like_type'] : '';
			$include_google_follow_profile = isset($options['google_follow_profile']) ? $options['google_follow_profile'] : '';
			
			// @since 1.2.3
			$include_youtube = isset($options['youtubesub']) ? $options['youtubesub'] : 'false';
			$include_youtube_channel = isset($options['youtubechannel']) ? $options['youtubechannel'] : '';
			
			$include_pinfollow = isset($options['pinterestfollow']) ? $options['pinterestfollow'] : 'false';
			$include_pinfollow_disp = isset($options['pinterestfollow_disp']) ? $options['pinterestfollow_disp'] : '';
			$include_pinfollow_url = isset($options['pinterestfollow_url']) ? $options['pinterestfollow_url'] : '';
			$include_pintype = isset($options['pinterest_native_type']) ? $options['pinterest_native_type'] : '';
			
			// @since 1.3.9.8
			$linkedin_follow = isset($options['linkedin_follow']) ? $options['linkedin_follow'] : 'false';
			$linkedin_follow_id = isset($options['linkedin_follow_id']) ? $options['linkedin_follow_id'] : '';
			
			$native_deactivate_mobile = isset($options['native_deactivate_mobile']) ? $options['native_deactivate_mobile'] : 'false';
			
			$include_managedwp = isset($options['managedwp_button']) ? $options['managedwp_button'] : 'false';
			
			$append_twitter_user_to_message = isset($options['twitteruser']) ? $options['twitteruser'] : '';
			$append_twitter_hashtags = isset($options['twitterhashtags']) ? $options['twitterhashtags'] : '';
			$twitter_nojspop = isset($options['twitter_nojspop']) ? $options['twitter_nojspop'] : 'false';
			$using_yoast_ga = isset($options['using_yoast_ga']) ? $options['using_yoast_ga'] : 'false';
			
			// @since 1.3.9.5
			$encode_url_nonlatin = isset($options['encode_url_nonlatin']) ? $options['encode_url_nonlatin'] : 'false';
			
			$mail_funcion = isset($options['mail_funcion']) ? $options['mail_funcion'] : '';
			
			if ($post_essb_twitter_username != '') {
				$append_twitter_user_to_message = $post_essb_twitter_username;
			}
			if ($post_essb_twitter_hastags != '') {
				$append_twitter_hashtags = $post_essb_twitter_hastags;
			} 
			
			$twitter_shareshort = isset($options['twitter_shareshort']) ? $options['twitter_shareshort'] : 'false';
			$twitter_shareshort_service = isset($options['twitter_shareshort_service']) ? $options['twitter_shareshort_service'] : '';
				
			$twitter_always_count_full = isset($options['twitter_always_count_full']) ? $options['twitter_always_count_full'] : 'false';
			
			//$append_facebook_hashtags = isset($options['facebookhashtags']) ? $options['facebookhashtags'] : '';
			$append_facebook_hashtags = "";
			// @since 1.1.1
			$otherbuttons_sameline = isset($options['otherbuttons_sameline']) ? $options['otherbuttons_sameline'] : 'false';
			
			if ($custom_share_text == '' && $active_custom_message == 'true') {
				$custom_share_text = isset($options['customshare_text']) ? $options['customshare_text'] : '';
				
			}
			if ($custom_share_text != '') {
				$text = $custom_share_text;
				$is_from_customshare = true;
			}
				
			if ($custom_share_address == '' && $active_custom_message == 'true') {
				$custom_share_address = isset($options['customshare_url']) ? $options['customshare_url'] : '';
				
			}
			if ($custom_share_address != '') {
				$url = $custom_share_address;
				$stumble_fullurl = $url;
			}
			
			if ($custom_share_description != '' && $active_custom_message == 'true') {
				$pinterest_desc = $custom_share_description;
			}
				
			if ($custom_share_imageurl != '' && $active_custom_message == 'true') {
				$pinterest_image = $custom_share_imageurl;
			}
			
			// other options
			$display_where = isset($options['display_where']) ? $options['display_where'] : '';
			
			if ($post_display_where != '') { $display_where = $post_display_where; }
			
			// @since 1.3.8.2 - mobile display render in alternative way
			if ($this->isMobile()){
				$display_position_mobile = isset($options['display_position_mobile']) ? $options['display_position_mobile'] : '';
					
				if ($display_position_mobile != '') {
					$display_where = $display_position_mobile;
					
					// @since 1.3.9.6
					if ($display_position_mobile == 'sidebar') {
						$display_position_mobile_sidebar = isset($options['display_position_mobile_sidebar']) ? $options['display_position_mobile_sidebar'] : '';
						
						if ($display_position_mobile_sidebar != '') {
							$custom_sidebar_pos = $display_position_mobile_sidebar;
						}
					}
				}
			}
				
			
			if ($post_hide_network_names == '1') {
				$hidden_name_class = ' essb_hide_name';
			}
			
			// @since 1.1.3
			if ($is_shortcode) { $display_where = "shortcode"; }
			if ($is_shortcode && $shortcode_sidebar == 'yes') { $display_where = "sidebar"; }
			if ($is_shortcode && $shortcode_popup == 'yes') { $display_where = "popup"; }
			
			if ($is_shortcode && $shortcode_postfloat == "yes") { $display_where = "postfloat"; }
			
			// @since 1.3.9.3
			if ($is_shortcode && $shortcode_float == "yes") {
				$display_where = "float";
			}
			
			if ($display_where == "popup") {
				$container_classes = "essb_popup_counters";
			}
			
			if ($display_where != "sidebar") {
				$custom_sidebar_pos = "";
				$sidebar_bottom_align = "";
			}
			else {
				if ($custom_sidebar_pos != '') {
					$custom_sidebar_pos = "_".$custom_sidebar_pos;
				}
				
				if ($sidebar_bottom_align != '') {
					switch ($sidebar_bottom_align) {
						case "left":
							$sidebar_bottom_align = " sbal";
							break;
						case "right":
							$sidebar_bottom_align = " sbar";
							break;
						case "center":
							$sidebar_bottom_align = "";
							break;
						default:
							$sidebar_bottom_align = "";
							break;							
					} 
				}
			}
			
			//print "display where = " . $display_where;
			//print $native_counters_g;
			
			$force_pinterest_snif = 1;
			if ($pinterest_sniff_disable == 'true') { $force_pinterest_snif = 0; }

			if ($custom_like_url == "") { $custom_like_url = $url; }
			if ($custom_plusone_address == "") { $custom_plusone_address = $url; }
			
			$user_network_messages = isset($options['network_message']) ? $options['network_message'] : '';
			$user_advanced_share = isset($options['advanced_share']) ? $options['advanced_share'] : '';
			
			if ($post_essb_as != '') {
				$user_advanced_share = $post_essb_as;
			}
				
			$message_above_share = isset($options['message_share_buttons']) ? $options['message_share_buttons'] : '';
			$message_above_like = isset($options['message_like_buttons']) ? $options['message_like_buttons'] : '';
			
			$message_above_share = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#'), array(get_the_title(), get_site_url(), get_permalink()), $message_above_share);
			$message_above_like = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#'), array(get_the_title(), get_site_url(), get_permalink()), $message_above_like);
				
			// @since 1.2.2 where are available likeshare and sharelike display methods
			$is_forced_hidden_networks = false;
			$like_share_display_position = "";
			if (!$is_shortcode) {
				//print $display_where;
				if ($display_where == "likeshare" && $this->print_links_position == "top") {
					$networks = array("no"); $is_forced_hidden_networks = true;
					$like_share_display_position = " essb-like-buttons";
				}
				if ($display_where == "likeshare" && $this->print_links_position == "bottom") {
					$shortcode_native = "no";
					$like_share_display_position = " essb-share-buttons";
						
				}
				if ($display_where == "sharelike" && $this->print_links_position == "bottom") {
					$networks = array("no"); $is_forced_hidden_networks = true;
					$like_share_display_position = " essb-like-buttons";
				}
				if ($display_where == "sharelike" && $this->print_links_position == "top") {
					$shortcode_native = "no";
					$like_share_display_position = " essb-share-buttons";
				}
			}
				
			
			if ($message_above_share != "" && !$is_shortcode && !$is_forced_hidden_networks) { $before_the_list .= '<div class="essb_message_above_share">'.stripslashes($message_above_share)."</div>";}
			if ($message_above_share != "" && $is_shortcode && $shortcode_messages == "yes") {  $before_the_list .= '<div class="essb_message_above_share">'.stripslashes($message_above_share)."</div>"; }
			
			// @developer fix to attach class for template
			$loaded_template_id = isset($options ['style']) ? $options ['style']  : '';
				
			$post_theme =  get_post_meta($post->ID,'essb_theme',true);
			if ($post_theme != "" && is_numeric($post_theme)) {
				$loaded_template_id = intval($post_theme);
			}
				
			$loaded_template_id = intval($loaded_template_id);
			$loaded_template = "default";
				
			if ($loaded_template_id == 1) {
				$loaded_template = "default";
			}
			if ($loaded_template_id == 2) {
				$loaded_template = "metro";
			}
			if ($loaded_template_id == 3) {
				$loaded_template = "modern";
			}
			if ($loaded_template_id == 4) {
				$loaded_template = "round";
			}
			if ($loaded_template_id == 5) {
				$loaded_template = "big";
			}
			if ($loaded_template_id == 6) {
				$loaded_template = "metro-retina";
			}
			if ($loaded_template_id == 7) {
				$loaded_template = "big-retina";
			}
			if ($loaded_template_id == 8) {
				$loaded_template = "light-retina";
			}
			if ($loaded_template_id == 9) {
				$loaded_template = "flat-retina";
			}
			if ($loaded_template_id == 10) {
				$loaded_template = "tiny-retina";
			}	
			if ($loaded_template_id == 11) {
				$loaded_template = "round-retina";
			}
			if ($loaded_template_id == 12) {
				$loaded_template = "modern-retina";
			}
			
			if ($is_shortcode && $shortcode_template != '') {
				$loaded_template = $shortcode_template;
			}
			
			if ($loaded_template != $this->loaded_template_slug) {
				//print $loaded_template .  "| ".$this->loaded_template_slug;
				if ($this->css_minifier) {
					wp_enqueue_style ( 'easy-social-share-buttons-'.$loaded_template, ESSB_PLUGIN_URL . '/assets/css/' . $loaded_template . '/' . 'easy-social-share-buttons.min.css', false, $this->version, 'all' );
				}
				else {
					wp_enqueue_style ( 'easy-social-share-buttons-'.$loaded_template, ESSB_PLUGIN_URL . '/assets/css/' . $loaded_template . '/' . 'easy-social-share-buttons.css', false, $this->version, 'all' );
				}
				
			}
			// networks to display
			// 2 differents results by :
			// -- using hook (options from admin panel)
			// -- using shortcode/template-function (the array $networks in parameter of this function)
			$essb_networks = array();
			
			if ( count($networks) > 0 ) {
				$essb_networks = array();
				/*foreach($options['networks'] as $k => $v) {
					if(in_array($k, $networks)) {
						$essb_networks[$k]=$v;
						$essb_networks[$k][0]=1; //set its visible value to 1 (visible)
					}
				}*/
				
				$current_networks = $options['networks'];
				
				foreach ($networks as $single) {
					
					if ($single != 'no') {					
						$essb_networks[$single]= $current_networks[$single];
						$essb_networks[$single][0]=1;
					}
				}
			
			}
			else {
				$essb_networks = $options['networks'];
			}
			
			
			// full width fix starts here;
			$is_fullwidth_mode = false;
			$css_fullwidth_container = "";
			$css_fullwidth_list = "";
			$css_fullwidth_item = "";
			$css_fullwidth_item_link = "";
			if ($is_shortcode && $shortcode_full_width == "yes") {
				$is_fullwidth_mode = true;
				$css_fullwidth_container = ' style="width: 100% !important;"';
				$css_fullwidth_list = ' style="width: 100% !important;"';
				
				$cnt = 0;
				foreach($essb_networks as $k => $v) {
					if( $v[0] == 1 ) {
						$cnt++;
					}
				}
				
				// @since 1.3.9.3
				if ($cnt > 0) {
					$item_width = 100 / $cnt;
				}
				else {
					$item_width = 100;
				}
				
				if ($shortcode_fullwidth_fix == '') { $shortcode_fullwidth_fix = "80"; }
				
				$css_fullwidth_item = ' style="width: '.intval($item_width).'% !important;"';
				$css_fullwidth_item_link = ' style="width: '.$shortcode_fullwidth_fix.'% !important; text-align: center;"';
			}
			
			// full width from settings
			$fullwidth_share_buttons = isset($options['fullwidth_share_buttons']) ? $options['fullwidth_share_buttons'] : '';
			$fullwidth_share_buttons_correction = isset($options['fullwidth_share_buttons_correction']) ? $options['fullwidth_share_buttons_correction'] : '';
			$fullwidth_share_buttons_correction_mobile = isset($options['fullwidth_share_buttons_correction_mobile']) ? $options['fullwidth_share_buttons_correction_mobile'] : '';
			
			// @since 1.3.9.5 get custom settings by button position
			$bp_settings = array();
			//if (!$is_shortcode) {
			$bp_display_where = $display_where;
			
			if ($display_where == "both") {
				if ($this->print_links_position == "top") {
					$bp_display_where = "top";
				}
				
				if ($this->print_links_position == "bottom") {
					$bp_display_where = "bottom";
				}
			}
			
			if ($this->mobile_options_active && $this->isMobile() && !$is_shortcode) {
				$this->options_by_bp_active = true;
			}
			
			if ($this->options_by_bp_active) {
				$bp_settings = EasySocialShareButtons_Advanced_Display::get_options_by_bp($bp_display_where);

				// @since 1.3.9.8 - separate mobile options included
				if ($this->mobile_options_active && $this->isMobile() && !$is_shortcode) {
					$bp_settings = EasySocialShareButtons_Advanced_Display::get_options_by_mp('mobile');
				}
				
				if ($bp_settings['active']) {
					// @since 1.3.9.8 - loading different template based on button position
					if (isset($bp_settings['template'])) {
						$bp_template = $bp_settings['template'];
						
						if ($bp_template != '' && $bp_template != $this->loaded_template_slug) {
							if ($this->css_minifier) {
								wp_enqueue_style ( 'easy-social-share-buttons-'.$bp_template, ESSB_PLUGIN_URL . '/assets/css/' . $bp_template . '/' . 'easy-social-share-buttons.min.css', false, $this->version, 'all' );
							}
							else {
								wp_enqueue_style ( 'easy-social-share-buttons-'.$bp_template, ESSB_PLUGIN_URL . '/assets/css/' . $bp_template . '/' . 'easy-social-share-buttons.css', false, $this->version, 'all' );
							}
							
							$loaded_template = $bp_template;
						}
					}
					
					if (isset($bp_settings['fullwidth'])) {
						$bp_fullwidth = isset($bp_settings['fullwidth']) ? $bp_settings['fullwidth'] : false;
						$bp_fullwidth_value = isset($bp_settings['fullwidth_value']) ? $bp_settings['fullwidth_value'] : '';
						
						if ($bp_fullwidth) {
							$fullwidth_share_buttons = "true";
						}
						else {
							$fullwidth_share_buttons = "false";
						}
						
						if ($bp_fullwidth_value != '') {
							$fullwidth_share_buttons_correction = $bp_fullwidth_value;
						}
 					}
 					
 					$bp_hidenames = isset($bp_settings['hidenames']) ? $bp_settings['hidenames'] : '';
 					$bp_counters_pos = isset($bp_settings['counters_pos']) ? $bp_settings['counters_pos'] : '';
 					$bp_total_counters_pos = isset($bp_settings['total_counters_pos']) ? $bp_settings['total_counters_pos'] : '';
 					 					
 					if (!$is_shortcode) {
 						if ($bp_hidenames != '' && !$bp_set_post_hide_network_names) {
 							$hidden_name_class = ($bp_hidenames == '1') ? ' essb_hide_name' : '';
 						}
 						if ($bp_counters_pos != '' && !$bp_set_post_counter_pos) {
 							$counter_pos = $bp_counters_pos;
 						}
 						if ($bp_total_counters_pos != '' && !$bp_set_post_total_counter_pos) {
 							$total_counter_pos = $bp_total_counters_pos;
 						}
 					}
				}
			}
			
			$bp_networks = array();
			$bp_networks_names = array();
				//if (!$is_shortcode) {
			if ($this->options_by_bp_active) {
				if ($bp_settings['active']) {
						
					if (!$is_shortcode) {
						$bp_networks = isset($bp_settings['networks']) ? $bp_settings['networks'] : array();
					}
					$bp_networks_names = isset($bp_settings['names']) ? $bp_settings['names'] : array();
					
					if (count($bp_networks_names) > 0) {
						$user_network_messages = $bp_networks_names;
					}
				}
			}
						
			$post_fullwidth_share_buttons = get_post_meta($post->ID, 'essb_activate_fullwidth', true);
			if ($post_fullwidth_share_buttons != '') {
				if ($post_fullwidth_share_buttons == "yes") {
					$fullwidth_share_buttons = "true";
				}
				else {
					$fullwidth_share_buttons = "false";
				}
			}
			
			if (!$is_shortcode && $fullwidth_share_buttons == "true" && $display_where != "sidebar" && $display_where != "popup") {
				$is_fullwidth_mode = true;
				$css_fullwidth_container = ' style="width: 100% !important;"';
				$css_fullwidth_list = ' style="width: 100% !important;"';
				
				$cnt = 0;
				foreach($essb_networks as $k => $v) {
					if (!$is_shortcode) {
						if ($this->options_by_bp_active && count($bp_networks) > 0) {
							$v[0] = (in_array($k, $bp_networks, true)) ? 1 : 0;
						}
					}
								
					if( $v[0] == 1 ) {
						$cnt++;
					}
				}
				
				if ($cnt > 0) {
					$item_width = 100 / $cnt;
				}
				else {
					$item_width = 100;
				}
				
				if ($fullwidth_share_buttons_correction == '') {
					$fullwidth_share_buttons_correction = "80";
				}
				
				if ($this->isMobile() && $fullwidth_share_buttons_correction_mobile != '') {
					$fullwidth_share_buttons_correction = $fullwidth_share_buttons_correction_mobile;
				}
				
				$css_fullwidth_item = ' style="width: '.intval($item_width).'% !important;"';
				$css_fullwidth_item_link = ' style="width: '.$fullwidth_share_buttons_correction.'% !important; text-align: center;"';
			}
			
			// @since 1.3.9.3
			$fixed_width_active = isset($options['fixed_width_active']) ? $options['fixed_width_active'] : '';
			$fixed_width_value = isset($options['fixed_width_value']) ? $options['fixed_width_value'] : '';
			
			if ($is_shortcode && $shortcode_fixed_width == 'yes') {
				$width_value = ($shortcode_fixed_width_value != '' ) ? $shortcode_fixed_width_value : '80';
				$css_fullwidth_item_link = ' style="width: '.$width_value.'px !important; text-align: center;"';
				$css_fullwidth_item_link = ' style="width: '.$width_value.'px !important; text-align: center;"';
			}
			if (!$is_shortcode && $fixed_width_active == 'true') {
				$width_value = ($fixed_width_value != '' ) ? $fixed_width_value : '80';
				$css_fullwidth_item_link = ' style="width: '.$width_value.'px !important; text-align: center;"';
				$css_fullwidth_item_link = ' style="width: '.$width_value.'px !important; text-align: center;"';
			}				
			
			$essb_css_modern_counter_class = "";
			if ($counter_pos == 'leftm') {
				$counter_pos = 'left';
				$essb_css_modern_counter_class = ' essb_counter_modern_left';
			}

			if ($counter_pos == 'rightm') {
				$counter_pos = 'right';
				$essb_css_modern_counter_class = ' essb_counter_modern_right';
			}

			if ($counter_pos == 'top') {
				$counter_pos = 'left';
				$essb_css_modern_counter_class = ' essb_counter_modern_top';
			}
				
			if ($counter_pos == 'topm') {
				$counter_pos = 'left';
				$essb_css_modern_counter_class = ' essb_counter_modern_top_mini';
			}
				
			// beginning markup
						
			$is_hidden_content = ($display_where == "popup") ? ' display: none;"' : '';
			if ($css_fullwidth_container != '' && $is_hidden_content != '') {
				$css_fullwidth_container = str_replace(';', ';display:none;', $css_fullwidth_container);
				$is_hidden_content = "";
			}
			
			if ($force_network_hide_from_shortcode) { $hidden_name_class = " essb_force_hide"; }
			
			$block_content = $before_the_sps_content;
			$block_content .= "\n".'<'.$div.' class="essb_links '.$container_classes.$essb_css_modern_counter_class.' essb_displayed_'.$display_where.$custom_sidebar_pos.$like_share_display_position.$sidebar_bottom_align.' essb_template_'.$loaded_template.' essb_'.$salt.'" id="essb_displayed_'.$display_where.'" '.$css_fullwidth_container.$is_hidden_content.'>';
			$block_content .= $hide_intro_phrase ? '' : "\n".'<'.$p.' class="screen-reader-text essb_maybe_hidden_text">'.$share_the_post_sentence.' "'.get_the_title().'"</'.$p.'>'."\n";
			$block_content .= $before_the_list;
			$block_content .= "\n\t".'<'.$ul.' class="essb_links_list'.$hidden_name_class.'" '.$css_fullwidth_list.'>';
			$block_content .= $before_first_i;
	
			// @since 1.3.0
			$general_counters = (isset($options['show_counter']) && $options['show_counter']==1) ? 1 : 0;
			if ($is_forced_hidden_networks) {
				$general_counters = 0; $counters = 0;
			}
				
			if (($general_counters==1 && intval($counters)==1) || ($general_counters==0 && intval($counters)==1)) {
				if ($total_counter_pos == 'left' || $total_counter_pos == "leftbig") {
					if ($total_counter_pos == "leftbig") {
						$block_content .= '<li class="essb_item essb_totalcount_item" '.($force_hide_total_count == 'true' ? 'style="display: none !important;"' : '').$css_hide_total_counter.' data-counter-pos="'.$counter_pos.'"><span class="essb_totalcount essb_t_l_big" title="" title_after=""><span class="essb_t_nb"></span></span></li>';
					}
					else {
						$block_content .= '<li class="essb_item essb_totalcount_item" '.($force_hide_total_count == 'true' ? 'style="display: none !important;"' : '').$css_hide_total_counter.' data-counter-pos="'.$counter_pos.'"><span class="essb_totalcount essb_t_l" title_after="" title="'.__('Total: ', ESSB_TEXT_DOMAIN).'"><span class="essb_t_nb"></span></span></li>';
					}
				}
					
			}
				
	
			$active_fb = false;		
			$active_pinsniff = false;		
			$active_mail = false;	
			$message_body = "";
			$message_subject = "";

			$used_twitter_url = "";

			if ($this->options_by_pt_active && isset($post)) {
				$pt_networks = isset($pt_settings['networks']) ? $pt_settings['networks'] : array();
			}
				
			$is_set_external_print = false;
			$is_active_more_button = false;
			
			// each links (come from options or manual array)
			foreach($essb_networks as $k => $v) {
				
				if (!$is_shortcode) {
					if ($this->options_by_pt_active && isset($post)) {
						if (count($pt_networks) > 0) {
							$v[0] = (in_array($k, $pt_networks, true)) ? 1 : 0;
						}
					}
				}
				
				if (!$is_shortcode) {
					if ($this->options_by_bp_active && count($bp_networks) > 0) {
						$v[0] = (in_array($k, $bp_networks, true)) ? 1 : 0;
					}
				}
				
				if( $v[0] == 1 ) {
					$api_link = $api_text = '';
					$url = apply_filters('essb_the_shared_permalink_for_'.$k, $url);
	
					$twitter_user = '';

					if ($append_twitter_user_to_message != '' ) { $twitter_user .= '&amp;related='.$append_twitter_user_to_message.'&amp;via='.$append_twitter_user_to_message; }
					//$twitter_user .= '&amp;hashtags=demo,demo1,demo2';
					if ($append_twitter_hashtags != '') {
						$twitter_user .= '&amp;hashtags='.$append_twitter_hashtags;
					}
					
					
					switch ($k) {
						case "twitter" :
							//$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$url.'&amp;text='.$text.'&amp;url='.$url.$twitter_user;
							// @since 1.3.9.3 to allow usage of # in message
							$twitter_message = $text;
							
							// @since 1.3.9.8 on post Twitter Tweet customization
							if ($post_essb_twitter_tweet != '') {
								$twitter_message = $post_essb_twitter_tweet;
							}
							
							$twitter_message = str_replace('#', '%23', $twitter_message);
							
							if ($is_shortcode && is_array($shortcode_custom_messages)) {
								$custom_network_message = isset($shortcode_custom_messages[$k]) ? $shortcode_custom_messages[$k] : '';
								if ($custom_network_message != '') {
									$twitter_message = $custom_network_message;
								}
							}
								
							
							$twitter_url = $url;
							$used_twitter_url = $url;
							// @since 1.3.9.5
							//if ($encode_url_nonlatin == "true") {
								//$url = rawurlencode($url);
							//	$twitter_url = urlencode_deep($twitter_url);
							//}
								
							// @since 1.3.9.6
							//$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$twitter_url.'&amp;text='.$twitter_message.'&amp;url='.$twitter_url.$twitter_user;
							$api_link = 'https://twitter.com/intent/tweet?text='.$twitter_message.'&amp;url='.$twitter_url.$twitter_user;
							
							if ($is_active_shorturl && $twitter_always_count_full == 'true') {
								$api_link = 'https://twitter.com/intent/tweet?text='.$twitter_message.'&amp;url='.$twitter_url.'&amp;counturl='.$twitter_fullurl.$twitter_user;
								$is_set_twitter_counturl = true;
							}
							
							if ($twitter_shareshort == 'true' && !$is_from_customshare) {
								
								$short_twitter = wp_get_shortlink();
								
								if ($twitter_shareshort_service == 'goo.gl') {
									$short_twitter = EasySocialShareButtons_ShortUrl::google_shorten($twitter_url);
								}
								if ($twitter_shareshort_service == "bit.ly") {
									$short_twitter = EasySocialShareButtons_ShortUrl::bitly_shorten($twitter_url, $url_short_bitly_user, $url_short_bitly_api);
								}
								
								$used_twitter_url = $short_twitter;
								//$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$url.'&amp;text='.$text.'&amp;url='.$short_twitter.'&amp;counturl='.$url.$twitter_user;
								$api_link = 'https://twitter.com/intent/tweet?text='.$twitter_message.'&amp;url='.$short_twitter.'&amp;counturl='.$url.$twitter_user;
								$is_set_twitter_counturl = false;
							}
							
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Twitter',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") { $api_text = $custom_text; }
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_image_pass = isset ( $user_advanced_share [$k . '_i'] ) ? $user_advanced_share [$k . '_i'] : '';
								$user_advanced_share_desc_pass = isset ( $user_advanced_share [$k . '_d'] ) ? $user_advanced_share [$k . '_d'] : '';
								
								if ($user_advanced_share_message_pass != '' || $user_advanced_share_url_pass != '') {
									
									if ($user_advanced_share_url_pass == '') { $user_advanced_share_url_pass = $url; }
									if ($user_advanced_share_message_pass == '') { $user_advanced_share_message_pass = $text; }
									
									// @since 1.3.9.5
									//if ($encode_url_nonlatin == "true") {
									//	$user_advanced_share_url_pass = urlencode_deep($user_advanced_share_url_pass);
									//}
									
									$used_twitter_url = $user_advanced_share_url_pass;
									
									//$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$user_advanced_share_url_pass.'&amp;text='.$user_advanced_share_message_pass.'&amp;url='.$user_advanced_share_url_pass.$twitter_user;
									$api_link = 'https://twitter.com/intent/tweet?text='.$user_advanced_share_message_pass.'&amp;url='.$user_advanced_share_url_pass.$twitter_user;
									$is_set_twitter_counturl = false;
								}
							}
							
							break;
	
						case "facebook" :
							//https://www.facebook.com/dialog/feed?app_id=145634995501895&display=popup&caption=An%20example%20caption&link=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fdialogs%2F&redirect_uri=https://developers.facebook.com/tools/explorer&description=
							
							$api_link = 'https://www.facebook.com/dialog/feed?app_id='.$facebook_advanced_sharing_appid.'&display=popup&name='.($text).'&link='.urlencode($url).'&redirect_uri=https://www.facebook.com';//'https://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$url.'&p&#91;title]='.$text.$append_facebook_hashtags;
							if ($post_image != '') {
								$api_link .= '&picture='.$post_image;
							}
							if (!$post_desc != '') {
								$api_link .= '&description='.urlencode($post_desc);
							}
							
							if ($facebook_simplesharing == 'true') {
								$api_link = 'http://www.facebook.com/sharer/sharer.php?u='.$url;
							}
							
							if ($is_from_customshare) {
								//$api_link = 'https://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$url.'&p&#91;title]='.$text.$append_facebook_hashtags;
								$api_link = 'https://www.facebook.com/dialog/feed?app_id='.$facebook_advanced_sharing_appid.'&display=popup&name='.($text).'&link='.urlencode($url).'&redirect_uri=https://www.facebook.com';//'https://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$url.'&p&#91;title]='.$text.$append_facebook_hashtags;
								
								if ($custom_share_description != '') {
									//$api_link .= '&p&#91;summary]='.$custom_share_description;
									$api_link .= '&description='.urlencode($custom_share_description);
								}
								// @ fix in 1.0.8
								if ($custom_share_imageurl != '') {
									//$api_link .= '&p&#91;images][0]='.$custom_share_imageurl;
									$api_link .= '&picture='.$custom_share_imageurl;
								}	

								$api_link = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#'), array(get_the_title(), get_site_url(), get_permalink()), $api_link);
								
								// if there is no applicatio when cannot use custom share
								if ($facebook_advanced_sharing_appid == "") {
									$api_link = 'http://www.facebook.com/sharer/sharer.php?u='.$url;
								}
							}
							
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Facebook',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_image_pass = isset ( $user_advanced_share [$k . '_i'] ) ? $user_advanced_share [$k . '_i'] : '';
								$user_advanced_share_desc_pass = isset ( $user_advanced_share [$k . '_d'] ) ? $user_advanced_share [$k . '_d'] : '';
							
								if ($user_advanced_share_message_pass != '' || $user_advanced_share_url_pass != '') {
										
									if ($user_advanced_share_url_pass == '') {
										$user_advanced_share_url_pass = $url;
									}
									if ($user_advanced_share_message_pass == '') {
										$user_advanced_share_message_pass = $text;
									}
									
									if ($user_advanced_share_image_pass == '') {
										$user_advanced_share_image_pass = $custom_share_imageurl;	
									}
									
									if ($user_advanced_share_desc_pass == '') {
										$user_advanced_share_desc_pass = $custom_share_description;
									}									
									
									
										
									if ($facebook_simplesharing == 'true') {
										$api_link = 'http://www.facebook.com/sharer/sharer.php?u='.$user_advanced_share_url_pass;
									}
									else {
										$api_link = 'https://www.facebook.com/dialog/feed?app_id='.$facebook_advanced_sharing_appid.'&display=popup&name='.urlencode($user_advanced_share_message_pass).'&link='.urlencode($user_advanced_share_url_pass).'&redirect_uri=https://www.facebook.com';//'https://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$url.'&p&#91;title]='.$text.$append_facebook_hashtags;
								
										if ($user_advanced_share_desc_pass != '') {
									
											$api_link .= '&description='.urlencode($user_advanced_share_desc_pass);
										}
										if ($user_advanced_share_image_pass != '') {
											//$api_link .= '&p&#91;images][0]='.$custom_share_imageurl;
											$api_link .= '&picture='.$user_advanced_share_image_pass;
										}	

										$api_link = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#'), array(get_the_title(), get_site_url(), get_permalink()), $api_link);
									}
								}
							}
							
							break;
	
						case "google" :
							$google_url = $url;
							
							if ($encode_url_nonlatin == "true") {
								//$google_url = rawurlencode($google_url);
							}
							
							$api_link = 'https://plus.google.com/share?url='.$google_url;
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Google+',ESSB_TEXT_DOMAIN));
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
															
								
								if ($user_advanced_share_url_pass != '') {
									$api_link = 'https://plus.google.com/share?url='.$user_advanced_share_url_pass;
								}
							}
							
							break;
	
						case "pinterest" :
							if ( $pinterest_image != '' && $force_pinterest_snif==0 ) {
								$api_link = 'http://pinterest.com/pin/create/bookmarklet/?media='.$pinterest_image.'&amp;url='.$url.'&amp;title='.$text.'&amp;description='.$pinterest_desc;
								$api_link = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#'), array(get_the_title(), get_site_url(), get_permalink()), $api_link);								
							}
							else {
								//$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
								$api_link = "javascript:void(0);";
								$target_link = "";
								$active_pinsniff = true;
							}
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share an image of this article on Pinterest',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_image_pass = isset ( $user_advanced_share [$k . '_i'] ) ? $user_advanced_share [$k . '_i'] : '';
								$user_advanced_share_desc_pass = isset ( $user_advanced_share [$k . '_d'] ) ? $user_advanced_share [$k . '_d'] : '';
									
								if ($user_advanced_share_message_pass != '' || $user_advanced_share_url_pass != '' || $user_advanced_share_image_pass != '') {
							
									if ($user_advanced_share_url_pass == '') {
										$user_advanced_share_url_pass = $url;
									}
									if ($user_advanced_share_message_pass == '') {
										$user_advanced_share_message_pass = $text;
									}
										
									if ($user_advanced_share_image_pass == '') {
										$user_advanced_share_image_pass = $pinterest_image;
									}
										
									if ($user_advanced_share_desc_pass == '') {
										$user_advanced_share_desc_pass = $pinterest_desc;
									}
									
							
									$api_link = 'http://pinterest.com/pin/create/bookmarklet/?media='.$user_advanced_share_image_pass.'&amp;url='.$user_advanced_share_url_pass.'&amp;title='.$user_advanced_share_message_pass.'&amp;description='.$user_advanced_share_desc_pass;
								}
							}
						break;
	
	
						case 'linkedin':
							$api_link = "http://www.linkedin.com/shareArticle?mini=true&amp;ro=true&amp;trk=EasySocialShareButtons&amp;title=".$text."&amp;url=".$url;
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on LinkedIn',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
								
								if ($user_advanced_share_message_pass == '') {
									$user_advanced_share_message_pass = $text;
								}
								

								if ($user_advanced_share_url_pass != '') {
									$api_link = "http://www.linkedin.com/shareArticle?mini=true&amp;ro=true&amp;trk=EasySocialShareButtons&amp;title=".$user_advanced_share_message_pass."&amp;url=".$user_advanced_share_url_pass;
								}
							}
								
							break;
	
						case 'digg':
							$api_link = "http://digg.com/submit?phase=2%20&amp;url=".$url."&amp;title=".$text;
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Digg',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
								
								if ($user_advanced_share_message_pass == '') {
									$user_advanced_share_message_pass = $text;
								}	
								
								
								if ($user_advanced_share_url_pass != '') {
									$api_link = "http://digg.com/submit?phase=2%20&amp;url=".$user_advanced_share_url_pass."&amp;title=".$user_advanced_share_message_pass;
								}
							}
							break;

							case 'reddit':
								$api_link = "http://reddit.com/submit?url=".$url."&amp;title=".$text;
								$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Reddit',ESSB_TEXT_DOMAIN));
									
								if ($user_network_messages != '') {
									$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
									if ($custom_text != "") {
										$api_text = $custom_text;
									}
								}
									
								// @since 1.3.2
								if ($user_advanced_share != '') {
									$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
									$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
							
									if ($user_advanced_share_message_pass == '') {
										$user_advanced_share_message_pass = $text;
									}
									

																		
									if ($user_advanced_share_url_pass != '') {
										$api_link = "http://reddit.com/submit?url=".$user_advanced_share_url_pass."&amp;title=".$user_advanced_share_message_pass;
									}
								}
								break;
								
								case 'del':
									$api_link = "https://delicious.com/save?v=5&noui&jump=close&url=".$url."&title=".$text;
									$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Delicious',ESSB_TEXT_DOMAIN));
										
									if ($user_network_messages != '') {
										$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
										if ($custom_text != "") {
											$api_text = $custom_text;
										}
									}
										
									// @since 1.3.2
									if ($user_advanced_share != '') {
										$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
										$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
											
										if ($user_advanced_share_message_pass == '') {
											$user_advanced_share_message_pass = $text;
										}
										

										if ($user_advanced_share_url_pass != '') {
											$api_link = "https://delicious.com/save?v=5&noui&jump=close&url=".$user_advanced_share_url_pass."&amp;title=".$user_advanced_share_message_pass;
										}
									}
									break;		
									case 'buffer':
										$api_link = "https://bufferapp.com/add?url=".$url."&text=".$text."&via=&picture=&count=horizontal&source=button";
										$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Buffer',ESSB_TEXT_DOMAIN));
									
										if ($user_network_messages != '') {
											$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
											if ($custom_text != "") {
												$api_text = $custom_text;
											}
										}
									
										// @since 1.3.2
										if ($user_advanced_share != '') {
											$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
											$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
												
											if ($user_advanced_share_message_pass == '') {
												$user_advanced_share_message_pass = $text;
											}
											

																						if ($user_advanced_share_url_pass != '') {
												$api_link = "https://bufferapp.com/add?url=".$user_advanced_share_url_pass."&text=".$user_advanced_share_message_pass."&via=&picture=&count=horizontal&source=button";
												
											}
										}
										break;
										case 'love':
											$api_link = "javascript:void(0);";
											$api_text = apply_filters('essb_share_text_for_'.$k, __('Love This!',ESSB_TEXT_DOMAIN));
																							
											break;										
						case 'stumbleupon':
							
							$share_stumble_url = $url;
							
							if ($stumble_noshortlink == 'true') {
								$share_stumble_url = $stumble_fullurl;
							}
							
							$api_link = "http://www.stumbleupon.com/badge/?url=".$share_stumble_url;
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on StumbleUpon',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
									
								if ($user_advanced_share_url_pass != '') {
										$api_link = "http://www.stumbleupon.com/badge/?url=".$user_advanced_share_url_pass;
								}
							}
							break;
	
						case 'tumblr':
							$api_link = "http://tumblr.com/share?s=&v=3&t=".$text."&u=".urlencode($url);
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Tumblr',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
							
								if ($user_advanced_share_message_pass == '') {
									$user_advanced_share_message_pass = $text;
								}
																
								if ($user_advanced_share_url_pass != '') {
									$api_link = "http://tumblr.com/share?s=&v=3&t=".$user_advanced_share_message_pass."&u=".urlencode($user_advanced_share_url_pass);
								}
							}
							break;
									
	
						case 'vk':
							$api_link = "http://vkontakte.ru/share.php?url=".$url;
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on VKontakte',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
									
								if ($user_advanced_share_message_pass == '') {
									$user_advanced_share_message_pass = $text;
								}
																
								if ($user_advanced_share_url_pass != '') {
									$api_link = "http://vkontakte.ru/share.php?url=".$user_advanced_share_url_pass;
								}
							}
							break;
						case 'ok':
							$api_link = "http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=".$url;
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Odnoklassniki',ESSB_TEXT_DOMAIN));
							
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							
							// @since 1.3.2
							if ($user_advanced_share != '') {
								$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
								$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
									
								if ($user_advanced_share_message_pass == '') {
									$user_advanced_share_message_pass = $text;
								}
																
								if ($user_advanced_share_url_pass != '') {
									$api_link = "http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=".$user_advanced_share_url_pass;
								}
							}
							break;
							case 'weibo':
								$api_link = "http://service.weibo.com/share/share.php?url=".$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Weibo',ESSB_TEXT_DOMAIN));
								
								if ($user_network_messages != '') {
									$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
									if ($custom_text != "") {
										$api_text = $custom_text;
									}
								}
									
								// @since 1.3.2
								if ($user_advanced_share != '') {
									$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
									$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
										
									if ($user_advanced_share_message_pass == '') {
										$user_advanced_share_message_pass = $text;
									}
																		
									if ($user_advanced_share_url_pass != '') {
										$api_link = "http://service.weibo.com/share/share.php?url=".$user_advanced_share_url_pass;
									}
								}								
							break;	
							case 'xing':
								$api_link = "https://www.xing.com/social_plugins/share?h=1;url=".$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Xing',ESSB_TEXT_DOMAIN));
							
								if ($user_network_messages != '') {
									$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
									if ($custom_text != "") {
										$api_text = $custom_text;
									}
								}
									
								// @since 1.3.2
								if ($user_advanced_share != '') {
									$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
									$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
							
									if ($user_advanced_share_message_pass == '') {
										$user_advanced_share_message_pass = $text;
									}
									
																		
									if ($user_advanced_share_url_pass != '') {
										$api_link = "https://www.xing.com/social_plugins/share?h=1;url=".$user_advanced_share_url_pass;
									}
								}
								break;
									
							case 'pocket':
								$api_link = "https://getpocket.com/save?title=".$text."&url=".urlencode($url);
								$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on Pocket',ESSB_TEXT_DOMAIN));
									
								if ($user_network_messages != '') {
									$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
									if ($custom_text != "") {
										$api_text = $custom_text;
									}
								}
									
									
								// @since 1.3.2
								if ($user_advanced_share != '') {
									$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
									$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
										
									if ($user_advanced_share_message_pass == '') {
										$user_advanced_share_message_pass = $text;
									}
																		
									if ($user_advanced_share_url_pass != '') {
										$api_link = "https://getpocket.com/save?title=".$user_advanced_share_message_pass."&url=".urlencode($user_advanced_share_url_pass);
									}
								}
								break;
									
								case 'mwp':
									$api_link = "http://managewp.org/share/form?url=".urlencode($url);
									$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article on ManageWP.org',ESSB_TEXT_DOMAIN));
										
									if ($user_network_messages != '') {
										$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
										if ($custom_text != "") {
											$api_text = $custom_text;
										}
									}
										
										
									// @since 1.3.2
									if ($user_advanced_share != '') {
										$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
										$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
								
										if ($user_advanced_share_message_pass == '') {
											$user_advanced_share_message_pass = $text;
										}
								
										if ($user_advanced_share_url_pass != '') {
											$api_link = "http://managewp.org/share/form?url=".urlencode($user_advanced_share_url_pass);
										}
									}
									break;
									case 'whatsapp':
										$api_link = "whatsapp://send?text=".urlencode($text.' '.$url);
										$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article via WhatsApp',ESSB_TEXT_DOMAIN));
									
										if ($user_network_messages != '') {
											$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
											if ($custom_text != "") {
												$api_text = $custom_text;
											}
										}
									
									
										// @since 1.3.2
										if ($user_advanced_share != '') {
											$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
											$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
									
											if ($user_advanced_share_message_pass == '') {
												$user_advanced_share_message_pass = $text;
											}
									
											if ($user_advanced_share_url_pass != '') {
												$api_link = "whatsapp://send?text=".urlencode($user_advanced_share_message_pass.' '.$user_advanced_share_url_pass);
											}
										}
										break;		
										case 'meneame':
											$api_link = "http://www.meneame.net/submit.php?url=".urlencode($url);
											$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article via Meneame',ESSB_TEXT_DOMAIN));
												
											if ($user_network_messages != '') {
												$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
												if ($custom_text != "") {
													$api_text = $custom_text;
												}
											}
												
												
											// @since 1.3.2
											if ($user_advanced_share != '') {
												$user_advanced_share_url_pass = isset ( $user_advanced_share [$k . '_u'] ) ? $user_advanced_share [$k . '_u'] : '';
												$user_advanced_share_message_pass = isset ( $user_advanced_share [$k . '_t'] ) ? $user_advanced_share [$k . '_t'] : '';
													
												if ($user_advanced_share_message_pass == '') {
													$user_advanced_share_message_pass = $text;
												}
													
												if ($user_advanced_share_url_pass != '') {
													$api_link = "http://www.meneame.net/submit.php?url=".urlencode($user_advanced_share_url_pass);
												}
											}
											break;																
						case 'print':
							$api_link = "window.print(); essb_self_postcount(&#39;print&#39;, &#39;".$self_count_post_id."&#39;);";
							
							if ($stats_active == 'true') {
								$api_link .= " essb_handle_stats(&#39;print&#39;);";
							}
							$api_link .= ' return false;';
							$print_use_printfriendly = isset($options['print_use_printfriendly']) ? $options['print_use_printfriendly'] : 'false';
							if ($print_use_printfriendly == 'true') {
								$api_link = 'http://www.printfriendly.com/print/?url='.$url;
								$is_set_external_print = true;
							}
							
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Print this article',ESSB_TEXT_DOMAIN));
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							break;	
						case 'flattr' :
							// currently Flattr does not support cusomize of share options
							$api_link = ESSB_Extension_Flattr::getStaticFlattrUrl();
							break;
							
						case 'mail' :
							if (strpos($options['mail_body'], '%%') || strpos($options['mail_subject'], '%%') ) {
								$api_link = esc_attr('mailto:?subject='.$options['mail_subject'].'&amp;body='.$options['mail_body']);
								$api_link = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#', '#%%image%%#'), array(get_the_title(), get_site_url(), get_permalink(), $post_image), $api_link);
								
							}
							else {
								$api_link = 'mailto:?subject='.$options['mail_subject'].'&amp;body='.$options['mail_body']." : ".$url;
							}
							$message_subject = $options['mail_subject'];
							$message_body = $options['mail_body'];
							$message_subject = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#', '#%%image%%#', '#%%shorturl%%#'), array(get_the_title(), get_site_url(), get_permalink(), $post_image, $url), $message_subject);
							$message_body = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#', '#%%image%%#', '#%%shorturl%%#'), array(get_the_title(), get_site_url(), get_permalink(), $post_image, $url), $message_body);
							
							
							if ($mail_funcion != 'link') {
								$api_link = "javascript:void(0);";
							}
							
							$api_text = apply_filters('essb_share_text_for_'.$k, __('Share this article with a friend (email)',ESSB_TEXT_DOMAIN));
							if ($user_network_messages != '') {
								$custom_text = isset($user_network_messages[$k]) ? $user_network_messages[$k] : '';
								if ($custom_text != "") {
									$api_text = $custom_text;
								}
							}
							$active_mail = true;
							break;
						case "more":
							$api_link = "essb_toggle_more(&#39;".$salt."&#39;); return false;";
							$network_name = "";
							$is_active_more_button = true;
							break;
					}
	
					$network_name = isset($v[1]) ? $v[1] : $k;
										
					
					if ($is_shortcode && is_array($shortcode_custom_button_texts)) {
						$custom_shortcode_netowrk_name = isset($shortcode_custom_button_texts[$k]) ? $shortcode_custom_button_texts[$k] : '';
						if ($custom_shortcode_netowrk_name != '') {
							$network_name = $custom_shortcode_netowrk_name;
						}
					}
					if (!$is_shortcode && $this->options_by_bp_active) {
						if (count($bp_networks_names) > 0) {
							$custom_name = isset($bp_networks_names[$k]) ? $bp_networks_names[$k] : '';
							if ($custom_name != '') {
								$network_name = $custom_name;
							}
						}
					}
					
					$network_name = trim($network_name);
					$network_name = esc_attr(stripslashes($network_name));
					// @since 1.3.9.2 - fix problem with ' in share text
					$api_link= str_replace("'", "\'", $api_link);
					
					if ($encode_url_nonlatin == "true") {
						$api_link = essb_base64url_encode($api_link);
					}
					
					if (intval($counters) == 1) {
						if ($counter_pos == "inside") { $network_name = ""; }
					}
					
					$force_hide_social_name = isset($options['force_hide_social_name']) ? $options['force_hide_social_name'] : 'false';
					if ($force_hide_social_name == 'true') {
						$network_name = '';
					}	
					
					// @ since 1.3.9.7 - added for usage with Easy Social Video Share Addon
					if ($is_shortcode && $shortcode_video_share == "yes") {
						$api_text = "";
					}
					
					if ($force_network_hide_from_shortcode) { $network_name = "";}
					
					if ($k == "more") {
						$network_name = "";
					}
					
					if ($k == 'more') {
						$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox" '.$css_fullwidth_item.'><a href="#" onclick="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';					
					}
					else if ($k != 'mail' && $k != 'pinterest') {
						if ($k == "print" && !$is_set_external_print) {
							$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="#" onclick="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';								
						}
						else if ($k == "love") {
							// @since 1.3.7 - love button handle stats
							if ($stats_active == "true") {
								// essb_handle_stats('love');								
								$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.$api_link.'" onclick="essb_handle_loveyou(\''.$cookie_loved_page.'\', &#39;'.$self_count_post_id.'&#39;, this); essb_handle_stats(\'love\'); return false;" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' '.$css_fullwidth_item_link.' '.($cookie_loved_page ? 'disabled="disabled"': '').'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
							}
							else {
								$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.$api_link.'" onclick="essb_handle_loveyou(\''.$cookie_loved_page.'\', &#39;'.$self_count_post_id.'&#39;, this); return false;" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' '.$css_fullwidth_item_link.' '.($cookie_loved_page ? 'disabled="disabled"': '').'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
							}							
						}
						else {
							
							if ($k == "twitter") {
								if ($twitter_nojspop == 'true') {
									$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
								}
								else {
									if ($using_yoast_ga == "true") {
										$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.'#'.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' onclick="essb_window'.$salt.'(&#39;'.$api_link.'&#39;, &#39;'.$k.'&#39;); return false;" '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
									}
									else {
										$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.'#'.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' onclick="essb_window'.$salt.'(\''.$api_link.'\', \''.$k.'\'); return false;" '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
									}
								}
							}
							else {
								if ($using_yoast_ga == "true") {
									$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' onclick="essb_window'.$salt.'(&#39;'.$api_link.'&#39;, &#39;'.$k.'&#39;); return false;" '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
								}
								else {
									$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' onclick="essb_window'.$salt.'(\''.$api_link.'\', \''.$k.'\'); return false;" '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
								}
							}
						}						
					}
					else {
						if ($k == 'pinterest') {
							if (! $active_pinsniff) {
								if ($using_yoast_ga == "true") {
									$block_content .= '<' . $li . ' class="essb_item essb_link_' . $k . ' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="' . $api_link . '" ' . $rel_nofollow . ' title="' . $api_text . '"' . $target_link . ' onclick="essb_window'.$salt.'(&#39;' . $api_link . '&#39;, &#39;'.$k.'&#39;); return false;" '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">' . $network_name . '</span></a></' . $li . '>';
								}
								else {
									$block_content .= '<' . $li . ' class="essb_item essb_link_' . $k . ' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="' . $api_link . '" ' . $rel_nofollow . ' title="' . $api_text . '"' . $target_link . ' onclick="essb_window'.$salt.'(\'' . $api_link . '\', \''.$k.'\'); return false;" '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">' . $network_name . '</span></a></' . $li . '>';
								}
							} else {
								$block_content .= '<' . $li . ' class="essb_item essb_link_' . $k . ' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="' . $api_link . '" ' . $rel_nofollow . ' title="' . $api_text . '"' . $target_link . ' '.$css_fullwidth_item_link.' onclick="essb_pinterenst'.$salt.'(); return false;"><span class="essb_icon"></span><span class="essb_network_name">' . $network_name . '</span></a></' . $li . '>';
							}
						} else {
							$block_content .= '<' . $li . ' class="essb_item essb_link_' . $k . ' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a id="essb-mailform'.$salt.'" href="' . $api_link . '" ' . $rel_nofollow . ' title="' . $api_text . '" '.$css_fullwidth_item_link.' class="essb-mail-link"><span class="essb_icon"></span><span class="essb_network_name">' . $network_name . '</span></a></' . $li . '>';
						}
					}
	
				}
			}
			
			if ($is_active_more_button) {
				$k = 'less';
				$api_link = "essb_toggle_less(&#39;".$salt."&#39;); return false;";
				$network_name = "";
				$block_content .= '<'.$li.' class="essb_item essb_link_'.$k.' nolightbox'.($is_active_more_button ? ' essb_after_more':  '').'" '.$css_fullwidth_item.'><a href="#" onclick="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$target_link.' '.$css_fullwidth_item_link.'><span class="essb_icon"></span><span class="essb_network_name">'.$network_name.'</span></a></'.$li.'>';
				
			}
			
			$post_counters =  get_post_meta($post->ID,'essb_counter',true);
				
			if ($post_counters != '') {
				$options ['show_counter'] = $post_counters;
			}			
			
			$include_plusone_button = isset($options['googleplus']) ? $options['googleplus'] : 'false';
			$include_fb_likebutton = isset($options['facebook_like_button']) ? $options['facebook_like_button'] : '';
			$include_vklike = isset($options['vklike']) ? $options['vklike'] : '';
		
			// @since 1.2.1
			if ($shortcode_force_fblike) { $include_fb_likebutton = "true"; }
			if ($shortcode_force_plusone) { $include_plusone_button = "true"; }
			if ($shortcode_force_twitter) { $include_twitter = "true"; }
			if ($shortcode_force_vk) { $include_vklike = "true"; }
			
			if ($shortcode_force_youtube) { $include_youtube = "true"; }
			if ($shortcode_force_pinfollow) { $include_pinfollow = "true"; }
			if ($shortcode_force_wpamanged) { $include_managedwp = "true"; }
			
			if ($post_hide_fb == 'yes') {
				$include_fb_likebutton = 'false';
			}
			if ($post_hide_plusone == 'yes') {
				$include_plusone_button = 'false';
			}
			if ($post_hide_vk == 'yes') {
				$include_vklike = 'false';
			}
			
			if ($post_hide_twitter == 'yes') { $include_twitter = 'false'; $include_twitter_user = ''; }
			if ($post_hide_youtube == "yes") { $include_youtube = 'false';  $include_youtube_channel = ''; }
			
			if ($post_hide_pinfollow == "yes") { $include_pinfollow = 'false'; $include_pinfollow_disp = ""; $include_pinfollow_url = ""; }
				
			if ($post_hide_wpmanaged == "yes") { $include_managedwp = "false";}
			
			if ($shortcode_native == 'no') {
				
				$include_fb_likebutton = 'false';
				$include_plusone_button = 'false';
				$include_vklike = 'false';
				$include_twitter = 'false'; $include_twitter_user = '';
				$include_youtube = 'false';  $include_youtube_channel = '';
				$include_pinfollow = 'false'; $include_pinfollow_disp = ""; $include_pinfollow_url = "";
				$include_managedwp = 'false';	
				$linkedin_follow = 'false'; $linkedin_follow_id = '';			
			}
			
			if (!$is_shortcode && $this->isMobile() && $native_deactivate_mobile == 'true') {
				$include_fb_likebutton = 'false';
				$include_plusone_button = 'false';
				$include_vklike = 'false';
				$include_twitter = 'false'; $include_twitter_user = '';
				$include_youtube = 'false';  $include_youtube_channel = '';
				$include_pinfollow = 'false'; $include_pinfollow_disp = ""; $include_pinfollow_url = "";
				$include_managedwp = 'false';
				$linkedin_follow = 'false'; $linkedin_follow_id = '';
			}
			
			if ($shortcode_native == "selected") {
				if (!$shortcode_force_fblike) { $include_fb_likebutton = 'false'; }
				if (!$shortcode_force_plusone) { $include_plusone_button = 'false'; }
				if (!$shortcode_force_vk) { $include_vklike = 'false'; }
				if (!$shortcode_force_twitter) { $include_twitter = 'false'; $include_twitter_user = ''; }
				if (!$shortcode_force_youtube) { $include_youtube = 'false';  $include_youtube_channel = ''; }
				if (!$shortcode_force_pinfollow) { $include_pinfollow = 'false'; $include_pinfollow_disp = ""; $include_pinfollow_url = ""; }
				if (!$shortcode_force_wpamanged) { $include_managedwp = "false"; }
			}
			
			$twitter_count_url = $url;
			if (!$is_shortcode) {
				if ($is_set_twitter_counturl) { $twitter_count_url = $twitter_fullurl; }
			}
			
			$general_counters = (isset($options['show_counter']) && $options['show_counter']==1) ? 1 : 0;
			if ($is_forced_hidden_networks) { $general_counters = 0; $counters = 0; }
			if ($this->using_self_counters) {
				$hidden_info = '<input type="hidden" class="essb_info_plugin_url" value="'.ESSB_PLUGIN_URL.'" /><input type="hidden" class="essb_info_permalink" value="'.$post->ID.'" />';
			}
			else {
				$hidden_info = '<input type="hidden" class="essb_info_plugin_url" value="'.ESSB_PLUGIN_URL.'" /><input type="hidden" class="essb_info_permalink" value="'.$url.'" post-id="'.$self_count_post_id.'" /><input type="hidden" class="essb_info_permalink_twitter" value="'.$twitter_count_url.'" />';
			}
			$hidden_info .= '<input type="hidden" class="essb_fb_total_count" value="'.$facebook_totalcount.'" />';
			$hidden_info .= '<input type="hidden" class="essb_counter_ajax" value="'.$force_counter_adminajax.'"/>';
			// counter_pos
			if (($general_counters==1 && intval($counters)==1) || ($general_counters==0 && intval($counters)==1)) {
				$hidden_info .= '<input type="hidden" class="essb_info_counter_pos" value="'.$counter_pos.'" />';
				
				//$hidden_info .= '<div class="essb_hidden_counter" style="display:none;visibility:hidden;" data-counter-pos="'.$counter_pos.'" data-counter-ajax="'.$force_counter_adminajax.'" data-fb-total="'.$facebook_totalcount.'"></div>';
			}
			
			$block_content .= $after_last_i;
			if (($general_counters==1 && intval($counters)==1) || ($general_counters==0 && intval($counters)==1)) {
				if ($total_counter_pos == 'right' || $total_counter_pos == "rightbig" || $total_counter_pos == "") {
					if ($total_counter_pos == "rightbig") {
						$block_content .= '<li class="essb_item essb_totalcount_item" '.($force_hide_total_count == 'true' ? 'style="display: none !important;"' : '').$css_hide_total_counter.' data-counter-pos="'.$counter_pos.'"><span class="essb_totalcount essb_t_r_big" title="" title_after=""><span class="essb_t_nb"></span></span></li>';
					}
					else {
						$block_content .= '<li class="essb_item essb_totalcount_item" '.($force_hide_total_count == 'true' ? 'style="display: none !important;"' : '').$css_hide_total_counter.' data-counter-pos="'.$counter_pos.'"><span class="essb_totalcount" title_after="" title="'.__('Total: ', ESSB_TEXT_DOMAIN).'"><span class="essb_t_nb"></span></span></li>';
					}
				}
			
			}
			
			// @since 1.3.9.6
			if ($used_twitter_url == '') {
				$used_twitter_url = $url;
				if ($twitter_shareshort == 'true' && !$is_from_customshare) {
				
					$short_twitter = wp_get_shortlink();
				
					if ($twitter_shareshort_service == 'goo.gl') {
						$short_twitter = EasySocialShareButtons_ShortUrl::google_shorten($twitter_url);
					}
					if ($twitter_shareshort_service == "bit.ly") {
						$short_twitter = EasySocialShareButtons_ShortUrl::bitly_shorten($twitter_url, $url_short_bitly_user, $url_short_bitly_api);
					}
				
					$used_twitter_url = $short_twitter;
				}
			}
			
			// @since 1.1.1
			if ($otherbuttons_sameline == 'true') {

				if ($include_plusone_button == 'true') {
					if ($include_google_type == "follow") {
						$block_content .= '<li class="essb_item essb_native_item essb_native_plusone_item"><div>'.$this->print_plusfollow_button($include_google_follow_profile, $native_counters_g, $native_lang).'</div></li>';
					}
					else {
						$block_content .= '<li class="essb_item essb_native_item essb_native_plusone_item"><div>'.$this->print_plusone_button($custom_plusone_address, $native_counters_g, $native_lang).'</div></li>';
					}
				}
				
				if ($include_twitter == 'true') {
					if ($include_twitter_type == 'tweet') {
						$block_content .= '<li class="essb_item essb_native_item essb_native_twitter_item"><div>'.$this->print_twitter_tweet_button($include_twitter_user, $native_counters_t, $native_lang, '', '', '', $used_twitter_url).'</div></li>';
					}
					else {
						$block_content .= '<li class="essb_item essb_native_item essb_native_twitter_item"><div>'.$this->print_twitter_follow_button($include_twitter_user, $native_counters_t, $native_lang).'</div></li>';
					}
				}
												
				if ($include_fb_likebutton == 'true') {
					if ($include_facebook_type == 'follow') {
						$block_content .= '<li class="essb_item essb_native_item essb_native_facebook_item"><div>'.$this->print_fb_followbutton($include_facebook_follow_profile, $native_counters_fb, $native_fb_width).'</div></li>';
					}
					else {
						$block_content .= '<li class="essb_item essb_native_item essb_native_facebook_item"><div>'.$this->print_fb_likebutton($custom_like_url, $native_counters_fb, $native_fb_width).'</div></li>';
					}
				}
				
				if ($linkedin_follow == 'true') {
					$block_content .= '<li class="essb_item essb_native_item essb_native_linkedin_item"><div>'.$this->print_linkedin_button($linkedin_follow_id, $allnative_counters).'</div></li>';						
				}

				if ($include_youtube == 'true') {
					$block_content .= '<li class="essb_item essb_native_item essb_native_youtube_item"><div>'.$this->print_youtube_button($include_youtube_channel, $native_counters_youtube).'</div></li>';
				}
				
				if ($include_pinfollow == 'true') {
					$block_content .= '<li class="essb_item essb_native_item essb_native_pinfollow_item"><div>'.$this->print_pinterest_follow($include_pinfollow_disp, $include_pinfollow_url, $include_pintype).'</div></li>';
				}
				if ($include_managedwp == "true") {
					$block_content .= '<li class="essb_item essb_native_item essb_native_managedwp_item"><div>'.$this->print_managedwp_button($url, $text).'</div></li>';						
				}

				if ($include_vklike == 'true') {
					$block_content .= '<li class="essb_item essb_native_item essb_native_vk_item"><div>'.$this->print_vklike_button($custom_like_url, $native_counters).'</div></li>';
				}
				
			}
			
			$block_content .= '</'.$ul.'>'."\n\t";
			$block_content .= $after_the_list;
			$block_content .= ( ($general_counters==1 && intval($counters)==1) || ($general_counters==0 && intval($counters)==1))  ? $hidden_info : '';
							
			if ($otherbuttons_sameline != 'true') {
				if ($include_fb_likebutton == 'true' || $include_plusone_button == 'true' || $include_vklike == 'true' || $include_twitter == 'true' || $linkedin_follow == 'true') {
					
					if ($message_above_like != "" && !$is_shortcode) {
						$block_content .= '<div class="essb_message_above_like">'.stripslashes($message_above_like)."</div>";
					}
					
					if ($message_above_like != "" && $is_shortcode && $shortcode_messages == "yes") {
						$block_content .= '<div class="essb_message_above_share">'.stripslashes($message_above_like)."</div>";
					}
						
						
					if ($this->skinned_social) {
						$block_content .= '<div style="display: inline-block; width: 100%; padding-top: 3px !important;" class="essb_native_skinned">';						
					}
					else {
						$block_content .= '<div style="display: inline-block; width: 100%; padding-top: 3px !important; overflow: hidden; padding-right: 10px;" class="essb_native">';
					}				
				}
				
				if ($include_plusone_button == 'true') {
					//$block_content .= '<'.$div.' class="" style="position: relative; float: left;">'.$this->print_plusone_button($url).'</'.$div.'>';
					if ($include_google_type == "follow") {
						$block_content .= $this->print_plusfollow_button($include_google_follow_profile, $native_counters_g, $native_lang);
					}	
					else {	
						$block_content .= $this->print_plusone_button($custom_plusone_address, $native_counters_g, $native_lang);
					}		
				}

				if ($include_twitter == 'true') {
					if ($include_twitter_type == 'tweet') {
						$block_content .= $this->print_twitter_tweet_button($include_twitter_user, $native_counters_t, $native_lang, '', '', '', $used_twitter_url);
					}
					else {
						$block_content .= $this->print_twitter_follow_button($include_twitter_user, $native_counters_t, $native_lang);						
					}
				}
				
			
				if ($include_fb_likebutton == 'true') {
					//$block_content .= '<'.$div.' class="" style="postion: relative; float: left; padding-top:3px !important;">'.$this->print_fb_likebutton($url).'</'.$div.'>';
					if ($include_facebook_type == 'follow') {
						$block_content .= $this->print_fb_followbutton($include_facebook_follow_profile, $native_counters_fb, $native_fb_width);
					}
					else {
						$block_content .= $this->print_fb_likebutton($custom_like_url, $native_counters_fb, $native_fb_width);
					}
				}
				
				if ($linkedin_follow == 'true' ){ 
					$block_content .= $this->print_linkedin_button($linkedin_follow_id, $allnative_counters);				
				}
				
				if ($include_youtube == 'true' ){ 
					$block_content .= $this->print_youtube_button($include_youtube_channel, $native_counters_youtube);
				
				}
				
				if ($include_pinfollow == 'true') {
					$block_content .= $this->print_pinterest_follow($include_pinfollow_disp, $include_pinfollow_url, $include_pintype);
				}
				if ($include_managedwp == "true") {
					$block_content .= ''.$this->print_managedwp_button($url, $text).'';
				}
				
				if ($include_vklike == 'true') {
					$block_content .= $this->print_vklike_button($custom_like_url, $native_counters);
				}
				
				// @since 1.1.1 added vklike
				if ($include_fb_likebutton == 'true' || $include_plusone_button == 'true' || $include_vklike == 'true' || $include_twitter == 'true' || $include_youtube == 'true' || $include_pinfollow == 'true' || $include_managedwp == "true" || $linkedin_follow == 'true') {
					$block_content .= '</div>';
				}
			}
				
			$block_content .= '</'.$div.'>'."\n\n";
			$block_content .= $after_the_sps_content;
	
			$js_encode_url = "";
			if ($encode_url_nonlatin == "true") {
				$post_pase_permalink = get_permalink();
				$js_encode_url = ' oUrl = "'.$post_pase_permalink.'?easy-share="+(oUrl); ';
			}
			
			// @since 1.3.9.8 main window code is moved to js_builder class
			$this->essb_js_builder->include_share_window_script();
			
			$js_gatracking_callback = "";
			$js_gatracking_pinterest = "";
			if ($this->ga_tracking) {
				$js_gatracking_callback = 'essb_ga_tracking(oService, "'.$display_where.$custom_sidebar_pos.'");';
				$js_gatracking_pinterest = 'essb_ga_tracking("pinterest", "'.$display_where.$custom_sidebar_pos.'");';
			}
			
			$block_content .= '<script type="text/javascript">';
			if ($stats_active == 'true') {
				$block_content .= 'function essb_window'.$salt.'(oUrl, oService) { '.$js_encode_url.' essb_window_stat(oUrl, oService, '.$self_count_post_id.'); '.$js_gatracking_callback.' }; ';
				$block_content .= "function essb_pinterenst".$salt."() {".$js_gatracking_pinterest."  essb_pinterenst_stat(".$self_count_post_id."); };";
			}
			else {
				//$block_content .= 'function essb_window'.$salt.'(oUrl, oService) { '.$js_encode_url.' if (oService == "twitter") { window.open( oUrl, "essb_share_window", "height=300,width=500,resizable=1,scrollbars=yes" ); }  else { window.open( oUrl, "essb_share_window", "height=500,width=800,resizable=1,scrollbars=yes" ); }  }; ';
				$block_content .= 'function essb_window'.$salt.'(oUrl, oService) { '.$js_encode_url.' essb_window(oUrl, oService, '.$self_count_post_id.'); '.$js_gatracking_callback.' }; ';				
				$block_content .= "function essb_pinterenst".$salt."() {".$js_gatracking_pinterest." essb_pinterenst(); }";
			}
			
			if ($is_active_more_button && !$this->more_button_code_inserted) {
				//$block_content .= $this->generate_more_button_js($more_button_func);
				//$this->footer_scripts[] = '<script type="text/javascript">'.$this->generate_more_button_js($more_button_func).'</script>';
				$this->essb_js_builder->add_js_code($this->generate_more_button_js($more_button_func), true);
				
			}
			
			$sidebar_sticky = isset($options['sidebar_sticky']) ? $options['sidebar_sticky'] : 'false';
			if ($display_where == "sidebar" && $sidebar_sticky == 'true') {
				$this->essb_js_builder->add_js_code('jQuery(document).ready(function() {
				jQuery(\'#essb_displayed_sidebar\').stickySidebar({				
				footerThreshold: 100
			});});');
			}
			
			// @since 1.3.1 - moved to option for admin-ajax call			
			$block_content .= '</script>';
				
			if ($active_mail) {
				//$block_content .= $this->print_popup_mailform($message_subject, $message_body, $salt, $stats_active, $self_count_post_id);
				if ($mail_funcion != 'link') {
					$short_url_mailer = '';
					$mail_shorturl = isset($options['mail_shorturl']) ? $options['mail_shorturl'] : 'false';
					if ($mail_shorturl == 'true') {
						$short_url_mailer = $url;
					}
					//$this->footer_scripts[] = $this->print_popup_mailform($message_subject, $message_body, $salt, $stats_active, $self_count_post_id);
					// original code
					//$this->essb_js_builder->add_js_code( $this->print_popup_mailform($message_subject, $message_body, $salt, $stats_active, $self_count_post_id, $display_where.$custom_sidebar_pos), true);
					$this->essb_js_builder->generate_popup_mailform();
					$this->essb_js_builder->add_js_code( $this->print_mailer_code($message_subject, $message_body, $salt, $stats_active, $self_count_post_id, $display_where.$custom_sidebar_pos, $short_url_mailer), true);
				}
			}
			
			// @since 1.3.9.1 - popup information is rendered only when popup method is really active
			if ($display_where == "popup") {
				$popup_user_message = isset($options['popup_user_message']) ? $options['popup_user_message'] : '';
				$popup_user_width = isset($options['popup_user_width']) ? $options['popup_user_width'] : '';
				$popup_user_close = isset($options['popup_user_close']) ? $options['popup_user_close'] : '';
				$popup_user_autoclose = isset($options['popup_user_autoclose']) ? $options['popup_user_autoclose'] : '';
				$popup_user_notshow_onclose = isset($options['popup_user_notshow_onclose']) ? $options['popup_user_notshow_onclose'] : 'false';
				$popup_user_manual_show = isset($options['popup_user_manual_show']) ? $options['popup_user_manual_show'] : 'false';
				$popup_user_percent = isset($options['popup_user_percent']) ? $options['popup_user_percent'] : '';
				$popup_user_percent = str_replace('%', '', $popup_user_percent);
				
				$block_content .= '<input type="hidden" name="essb_settings_popup_title" id="essb_settings_popup_title" value="'.stripslashes($popup_window_title).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_message" id="essb_settings_popup_message" value="'.stripslashes($popup_user_message).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_width" id="essb_settings_popup_width" value="'.stripslashes($popup_user_width).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_close" id="essb_settings_popup_close" value="'.$popup_window_close.'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_template" id="essb_settings_popup_template" value="'.$loaded_template.'"/>';

				$block_content .= '<input type="hidden" name="essb_settings_popup_user_close" id="essb_settings_popup_user_close" value="'.stripslashes($popup_user_close).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_user_autoclose" id="essb_settings_popup_user_autoclose" value="'.stripslashes($popup_user_autoclose).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_user_notshow_onclose" id="essb_settings_popup_user_notshow_onclose" value="'.stripslashes($popup_user_notshow_onclose).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_user_manual_show" id="essb_settings_popup_user_manual_show" value="'.stripslashes($popup_user_manual_show).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_user_notshow_onclose_id" id="essb_settings_popup_user_notshow_onclose_id" value="'.($post->ID).'"/>';
				$block_content .= '<input type="hidden" name="essb_settings_popup_user_percent" id="essb_settings_popup_user_percent" value="'.($popup_user_percent).'"/>';
				
				if ($popup_popafter != "") {
					$block_content .= '<input type="hidden" name="essb_settings_popup_popafter" id="essb_settings_popup_popafter" value="'.$popup_popafter.'"/>';
					$block_content .= '<div style="display: none;" id="essb_settings_popafter_counter"></div>';
				}
			
				if ((intval($counters)==1) && $display_where == "popup") {
					$block_content .= '<input type="hidden" name="essb_settings_popup_counters" id="essb_settings_popup_counters" value="yes"/>';
				}
			}
			
			if ($is_active_more_button && !$this->more_button_code_inserted && ($more_button_func == "2" || $more_button_func == '3')) {
				$essb_networks = $options['networks'];
				$buttons = "";
				foreach($essb_networks as $k => $v) {
					if( $k != 'more' ) {
						
						if ($more_button_func == "3") {
							if (!$v[0] == 1) { continue; }
						}
						
						if ($buttons != '') {
							$buttons .= ",";
						}
						$buttons .= $k;
					}
				
				}
				$links = do_shortcode('[easy-share buttons="'.$buttons.'" counters=0 native="no" fixedwidth="yes" fixedwidth_px="140" hide_names="show"]');
				$block_content .= '<div class="essb_displayed_more_popup" style="display:none;">'.$links.'</div>';
			}

			
			if ($is_active_more_button && !$this->more_button_code_inserted) {
				$this->more_button_code_inserted = true;
			}
			//@since 1.2.7 this is moved to wp_footer
			//if ($stats_active == 'true') {
			//	$block_content .= $this->stats->generate_log_js_code();
			//}
			
			//$block_content .= '<div id="essb_fb_commands" style="display: block;">'.$this->print_fb_sharebutton($url).$this->print_fb_likebutton($url). '</div>';
	
			// final markup
	
			return $block_content;
	
		} // end of if post meta hide sharing buttons
	
	} 
	
	public function print_managedwp_button($url, $text) {
		$output = '<div class="essb_managedwp" style="display: inline-block; overflow: hidden;"><script src="http://managewp.org/share.js" data-type="small" data-title="'.$text.'" data-url="'.$url.'"></script></div>';
		
		return $output;
	}
	
	public function print_pinterest_follow($disp, $url, $type, $skinned_text = '', $skinned_width = '') {
		if ($this->skinned_social) {
			if ($type == 'pin') {
				$code = '<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>';
			}
			else {
				$code = '<a data-pin-do="buttonFollow" href="'.$url.'">'.$disp.'</a>';
			}
			$output = ESSB_Skinned_Native_Button::generateButton('pinterest', $code, "follow", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
			if ($type == 'pin') {
				$output = '<div class="essb_pinterest_follow"  style="display: inline-block; overflow: hidden; vertical-align: top;margin-right: 5px;"><a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a></div>';
			}
			else {
 				$output = '<div class="essb_pinterest_follow"  style="display: inline-block; overflow: hidden; vertical-align: top;margin-right: 5px;"><a data-pin-do="buttonFollow" href="'.$url.'">'.$disp.'</a></div>';
			}
		}
		return $output;
	}
	
	public function print_linkedin_button($channel, $native_counters, $skinned_text = '', $skinned_width = '') {
		$output = "";
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				$code = '<script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/FollowCompany" data-id="'.$channel.'" data-counter="none"></script>';
			}
			else {
				$code = '<script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/FollowCompany" data-id="'.$channel.'" data-counter="right"></script>';
							}
			$output = ESSB_Skinned_Native_Button::generateButton('linkedin', $code, "follow", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
	
			//@Emiel add float left for correct display on Safari
			if ($native_counters == "false") {
				$output = '<div style="display: inline-block; overflow: hidden; vertical-align:top;margin-right: 5px; margin-left: 5px;" class="essb_linkedin_follow"><script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/FollowCompany" data-id="'.$channel.'" data-counter="none"></script></div>';
			}
			else {
				$output = '<div style="display: inline-block; overflow: hidden; vertical-align: top;margin-right: 5px; margin-left: 5px;" class="essb_linkedin_follow"><script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/FollowCompany" data-id="'.$channel.'" data-counter="right"></script></div>';
			}
		}
	
		return $output;
	}
	
	public function print_youtube_button($channel, $native_counters, $skinned_text = '', $skinned_width = '') {
		$output = "";
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				$code = '<div class="g-ytsubscribe" data-channel="'.$channel.'" data-layout="default" data-count="hidden"></div>';
			}
			else {
				$code = '<div class="g-ytsubscribe" data-channel="'.$channel.'" data-layout="default" data-count="default"></div>';
			}
			$output = ESSB_Skinned_Native_Button::generateButton('youtube', $code, "subscribe", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
		
		//@Emiel add float left for correct display on Safari
		if ($native_counters == "false") {
			$output = '<div style="display: inline-block; overflow: hidden; vertical-align:top;margin-right: 5px; margin-left: 5px;" class="essb_youtube_subscribe"><div class="g-ytsubscribe" data-channel="'.$channel.'" data-layout="default" data-count="hidden"></div></div>';
		}
		else {
			$output = '<div style="display: inline-block; overflow: hidden; vertical-align: top;margin-right: 5px; margin-left: 5px;" class="essb_youtube_subscribe"><div class="g-ytsubscribe" data-channel="'.$channel.'" data-layout="default" data-count="default"></div></div>';
		}
		}
		
		return $output;
	}
	
	public function print_vklike_button($address, $native_counters, $skinned_text = '', $skinned_width = '') {
		if ($this->skinned_social) {
			$code = '<div id="vk_like" style="float: left; poistion: relative;"></div>';
			$output = ESSB_Skinned_Native_Button::generateButton('vk', $code, "like", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
		
		
		$output = '<div class="essb-vk" style="display: inline-block;vertical-align: top;overflow: hidden;height: 20px; margin-right: 5px;"><div id="vk_like" style="float: left; poistion: relative;"></div></div>';
		}
		return $output;
	}
	
	function print_plusfollow_button($address, $native_counters, $native_lang, $skinned_text = '', $skinned_width = '') {
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				$code = '<div class="g-follow" data-size="medium" data-href="' . $address . '" data-annotation="none" data-rel="publisher"></div>';
			}
			else {
				$code = '<div class="g-follow" data-size="medium" data-href="' . $address . '" data-rel="publisher"></div>';
			}
			$output = ESSB_Skinned_Native_Button::generateButton('google', $code, "follow", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
			if ($native_counters == "false") {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-left: 5px; margin-right: 5px;  vertical-align: top;" class="essb_google_plusone"><div class="g-follow" data-size="medium" data-href="' . $address . '" data-annotation="none" data-rel="publisher"></div></div>';
			} else {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-left: 5px; margin-right: 5px; vertical-align: top;" class="essb_google_plusone"><div class="g-follow" data-size="medium" data-href="' . $address . '" data-rel="publisher"></div></div>';
			}
		}
	
		if (!$this->gplus_loaded) {
			$output .= $this->essb_js_builder->generate_gplus_script();
			$this->gplus_loaded = true;
		}
	
		return $output;
	}
	
	function print_plusone_button($address, $native_counters, $native_lang, $skinned_text = '', $skinned_width = '') {
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				$code = '<div class="g-plusone" data-size="medium" data-href="' . $address . '" data-annotation="none"></div>';
			}
			else {
				$code = '<div class="g-plusone" data-size="medium" data-href="' . $address . '"></div>';
			}
			$output = ESSB_Skinned_Native_Button::generateButton('google', $code, "+1", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		} 
		else {
			if ($native_counters == "false") {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-left: 5px; margin-right: 5px;  vertical-align: top;" class="essb_google_plusone"><div class="g-plusone" data-size="medium" data-href="' . $address . '" data-annotation="none"></div></div>';
			} else {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-left: 5px; margin-right: 5px; vertical-align: top;" class="essb_google_plusone"><div class="g-plusone" data-size="medium" data-href="' . $address . '"></div></div>';
			}
		}
		
		if (!$this->gplus_loaded) {
			$output .= $this->essb_js_builder->generate_gplus_script();
			$this->gplus_loaded = true;
		}
		
		return $output;
	}
	
	function print_fb_likebutton_css_fixer ($css, $height, $margin_top) {
		$css_object = explode(';', $css);
		$output = "";
		
		$injected_margintop = false;
		$injected_height = false;
		$injected_maxheight = false;
		
		foreach ($css_object as $singleRule) {
			
			$pos_height = strpos($singleRule, 'height');
			$pos_maxheight = strpos($singleRule, 'max-height');
			$pos_margintop = strpos($singleRule, 'margin-top');
			
			if (($pos_height === false) && ($pos_maxheight === false) && ($pos_margintop === false)) {
				$output .= $singleRule.';';
			}
			else {
				$newAppendValue = "";
				if ($pos_margintop !== false) {
					if ($margin_top != '') {
						$injected_margintop = true;
						$newAppendValue = "margin-top:".$margin_top.'px !important';
					}
					else {
						$newAppendValue = $singleRule;
					}
				}
				
				if ($pos_height !== false) {
					if ($height != '') {
						$injected_height = true;
						$newAppendValue = "height:".$height. 'px !important';
					}
					else {
						$newAppendValue = $singleRule;
					}
				}

				if ($pos_height !== false) {
					if ($height != '') {
						$injected_maxheight = true;
						$newAppendValue = "max-height:".$height. 'px !important';
					}
					else {
						$newAppendValue = $singleRule;
					}
				}
				
				$output .= $newAppendValue . ';';
			}
		}
		
		if ($margin_top != '' && !$injected_margintop) {
			$output .= 'margin-top:'.$margin_top.'px !important;';
		}
		if ($height != '' && !$injected_height) {
			$output .= 'height:'.$height.'px !important;';
		}
		if ($height != '' && !$injected_maxheight) {
			$output .= 'max-height:'.$height.'px !important;';
		}
		
		return $output;
	}
	
	function print_fb_likebutton($address, $native_counters, $native_fb_width, $skinned_text = '', $skinned_width = '') {
		
		$options = $this->options;
		$facebook_like_button_margin_top = isset($options['facebook_like_button_margin_top']) ? $options['facebook_like_button_margin_top'] : '';
		$facebook_like_button_height = isset($options['facebook_like_button_height']) ? $options['facebook_like_button_height'] : '';
		
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				if (trim($native_fb_width) == "") {
					$native_fb_width = "30";
				}
				$code = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; padding-right: 20px; width: '.$native_fb_width.'px !important; vertical-align: top;', $facebook_like_button_height, $facebook_like_button_margin_top).'"><div class="fb-like" data-href="'.$address.'" data-layout="button" data-action="like" data-show-faces="false" data-share="false" data-width="292"></div></div>';				
			}
			else {
				if (trim($native_fb_width) != '') {
					$code = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; padding-right: 20px; width: '.$native_fb_width.'px !important; vertical-align: top;', $facebook_like_button_height, $facebook_like_button_margin_top).'"><div class="fb-like" data-href="'.$address.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" data-width="292"></div></div>';
				}
				else {
					$code = '<div class="fb-like" data-href="'.$address.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" data-width="292"></div>';						
				}
			}
			$output = ESSB_Skinned_Native_Button::generateButton('facebook', $code, "like", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
		
			if ($native_counters == "false") {
				if (trim($native_fb_width) == "") { $native_fb_width = "30"; }
				$output = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; padding-right: 20px; width: '.$native_fb_width.'px !important; vertical-align: top; margin-right: 5px;', $facebook_like_button_height, $facebook_like_button_margin_top).'" class="essb_fb_like"><div class="fb-like" data-href="'.$address.'" data-layout="button" data-action="like" data-show-faces="false" data-share="false" data-width="292"></div></div>';				
			}
			else {
				$fix_native_width = "";
				if (trim($native_fb_width) != '') {
					$fix_native_width = 'width:'.$native_fb_width.'px !important;';
				}
				$output = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-right: 5px; vertical-align: top;'.$fix_native_width.'', $facebook_like_button_height, $facebook_like_button_margin_top).'" class="essb_fb_like"><div class="fb-like" data-href="'.$address.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" data-width="292"></div></div>';
			}
		}
		
		if (!$this->fb_api_loaded) {
			$output .= $this->essb_js_builder->generate_fb_script_inline();
			$this->fb_api_loaded = true;
		}
		
		return $output;
	}
	
	function print_fb_followbutton($address, $native_counters, $native_fb_width, $skinned_text = '', $skinned_width = '') {
	
		$options = $this->options;
		$facebook_like_button_margin_top = isset($options['facebook_like_button_margin_top']) ? $options['facebook_like_button_margin_top'] : '';
		$facebook_like_button_height = isset($options['facebook_like_button_height']) ? $options['facebook_like_button_height'] : '';
	
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				if (trim($native_fb_width) == "") {
					$native_fb_width = "30";
				}
				$code = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; padding-right: 20px; width: '.$native_fb_width.'px !important; vertical-align: top;', $facebook_like_button_height, $facebook_like_button_margin_top).'"><div class="fb-follow" data-href="'.$address.'" data-layout="button" data-show-faces="false"></div></div>';
			}
			else {
				if (trim($native_fb_width) != '') {
					$code = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; padding-right: 20px; width: '.$native_fb_width.'px !important; vertical-align: top;', $facebook_like_button_height, $facebook_like_button_margin_top).'"><div class="fb-follow" data-href="'.$address.'" data-layout="button_count" data-show-faces="false"></div></div>';
				}
				else {
					$code = '<div class="fb-like" data-href="'.$address.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" data-width="292"></div>';
				}
			}
			$output = ESSB_Skinned_Native_Button::generateButton('facebook', $code, "follow", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
	
			if ($native_counters == "false") {
				if (trim($native_fb_width) == "") {
					$native_fb_width = "30";
				}
				$output = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; padding-right: 20px; width: '.$native_fb_width.'px !important; vertical-align: top; margin-right: 5px;', $facebook_like_button_height, $facebook_like_button_margin_top).'" class="essb_fb_like"><div class="fb-follow" data-href="'.$address.'" data-layout="button" data-show-faces="false"></div></div>';
			}
			else {
				$fix_native_width = "";
				if (trim($native_fb_width) != '') {
					$fix_native_width = 'width:'.$native_fb_width.'px !important;';
				}
				$output = '<div style="'.$this->print_fb_likebutton_css_fixer('display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-right: 5px; vertical-align: top;'.$fix_native_width.'', $facebook_like_button_height, $facebook_like_button_margin_top).'" class="essb_fb_like"><div class="fb-follow" data-href="'.$address.'" data-layout="button_count" data-show-faces="false"></div></div>';
			}
		}
	
		if (!$this->fb_api_loaded) {
			$output .= $this->essb_js_builder->generate_fb_script_inline();
			$this->fb_api_loaded = true;
		}
	
		return $output;
	}
	
	function print_twitter_follow_button($user, $native_counters, $native_lang, $skinned_text = '', $skinned_width = '') {
		//$output = '<a href="https://twitter.com/'.$user.'" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false" data-size="small">Follow @'.$user.'</a>';
		// data counter false = 65
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				$code = '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name='.$user.'&show_count=false&show_screen_name=false&lang='.$native_lang.'" style="width:65px; height:20px;"></iframe>';
			}
			else {
				$code = '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name='.$user.'&show_count=true&show_screen_name=false&lang='.$native_lang.'" style="width:155px; height:20px;"></iframe>';
			}
			$output = ESSB_Skinned_Native_Button::generateButton('twitter', $code, "follow", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
			if ($native_counters == "false") {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-right: 5px; vertical-align: top;" class="essb_twitter_follow"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name='.$user.'&show_count=false&show_screen_name=false&lang='.$native_lang.'" style="width:65px; height:20px;"></iframe></div>';
			}
			else {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-right: 5px; vertical-align: top;" class="essb_twitter_follow"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name='.$user.'&show_count=true&show_screen_name=false&lang='.$native_lang.'" style="width:155px; height:20px;"></iframe></div>';				
			}
		}
	
		return $output;
	}
	
	function print_twitter_tweet_button($user, $native_counters, $native_lang, $custom_message = '', $skinned_text = '', $skinned_width = '', $twitter_short = '', $twitter_full = '') {
		//$output = '<a href="https://twitter.com/'.$user.'" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false" data-size="small">Follow @'.$user.'</a>';
		// data counter false = 65
		if ($this->skinned_social) {
			if ($native_counters == "false") {
				$code = '<a href="https://twitter.com/share" class="twitter-share-button" data-via="'.$user.'" data-lang="'.$native_lang.'" data-count="none" '.($custom_message != '' ? 'data-text="'.$custom_message.'"' : '').' '.($twitter_short != '' ? 'data-url="'.$twitter_short.'"' : '').'>Tweeter</a>';
			}
			else {
				$code = '<a href="https://twitter.com/share" class="twitter-share-button" data-via="'.$user.'" data-lang="'.$native_lang.'" '.($custom_message != '' ? 'data-text="'.$custom_message.'"' : '').' '.($twitter_short != '' ? 'data-url="'.$twitter_short.'"' : '').'>Tweeter</a>';
			}
			$output = ESSB_Skinned_Native_Button::generateButton('twitter', $code, "tweet", $skinned_text, $skinned_width, $this->skinned_social_selected_skin);
			//$output = ESSB_Skinned_Native_Button::generateCircleButton("google", $code);
		}
		else {
			if ($native_counters == "false") {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-right: 5px; vertical-align: top;" class="essb_twitter_follow"><a href="https://twitter.com/share" class="twitter-share-button" data-via="'.$user.'" data-lang="'.$native_lang.'" data-count="none" '.($custom_message != '' ? 'data-text="'.$custom_message.'"' : '').' '.($twitter_short != '' ? 'data-url="'.$twitter_short.'"' : '').'>Tweeter</a></div>';
			}
			else {
				$output = '<div style="display: inline-block; overflow: hidden; height: 24px; max-height: 24px; margin-right: 5px; vertical-align: top;" class="essb_twitter_follow"><a href="https://twitter.com/share" class="twitter-share-button" data-via="'.$user.'" data-lang="'.$native_lang.'" '.($custom_message != '' ? 'data-text="'.$custom_message.'"' : '').' '.($twitter_short != '' ? 'data-url="'.$twitter_short.'"' : '').'>Tweeter</a></div>';
			}
		}
		
		if (!$this->twitter_api_added) {
			$this->twitter_api_added = true;
			$output .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
		}
			
		return $output;
	}
	
	function print_fb_sharebutton($address) {
		$output = '<div class="fb-share-button" data-href="'.$address.'" data-type="button"></div>';
		
		return $output;
	}
	
	public function print_popup_mailform($title, $text, $salt_parent, $stats_ative, $post_id, $position) {
		global $post;
		
		$salt = mt_rand ();
		$mailform_id = 'essb_mail_from_'.$salt;
		$stats_callback = "";
		
		if ($stats_ative == 'true') {
			$stats_callback = "essb_handle_stats('mail');";
		}
		
		$stats_callback .= " essb_self_postcount('mail', ".$post_id.");";
		
		if ($this->ga_tracking) {
			$stats_callback .= 'essb_ga_tracking("mail", "'.$position.'");';
		}
		
		$options = $this->options;
		$translate_mail_title = isset($options['translate_mail_title']) ? $options['translate_mail_title'] : '';
		$translate_mail_email = isset($options['translate_mail_email']) ? $options['translate_mail_email'] : '';
		$translate_mail_recipient = isset($options['translate_mail_recipient']) ? $options['translate_mail_recipient'] : '';
		$translate_mail_subject = isset($options['translate_mail_subject']) ? $options['translate_mail_subject'] : '';
		$translate_mail_message = isset($options['translate_mail_message']) ? $options['translate_mail_message'] : '';
		$translate_mail_cancel = isset($options['translate_mail_cancel']) ? $options['translate_mail_cancel'] : '';
		$translate_mail_send = isset($options['translate_mail_send']) ? $options['translate_mail_send'] : '';

		$mail_captcha = isset($options['mail_captcha']) ? $options['mail_captcha'] : '';
		$mail_captcha_answer = isset($options['mail_captcha_answer']) ? $options['mail_captcha_answer'] : '';

		$captcha_html = '';
		if ($mail_captcha != '' && $mail_captcha_answer != '') {
			$captcha_html = '\'<div class="vex-custom-field-wrapper"><strong>'.$mail_captcha.'</strong></div><input name="captchacode" type="text" placeholder="Captcha Code" />\'+';
		}
		
		$text =nl2br($text);
		$text = str_replace("\r", "", $text);
		$text = str_replace("\n", "", $text);
		
		$text = str_replace("'", "&apos;", $text);
		$title = str_replace("'", "&apos;", $title);
		
		$siteurl = ESSB_PLUGIN_URL. '/';
		//$open = 'javascript:PopupContact_OpenForm("PopupContact_BoxContainer","PopupContact_BoxContainerBody","PopupContact_BoxContainerFooter");';
		$site_title = get_the_title();
		$url = get_site_url();
		$permalink = get_permalink();
		
		$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : '';
		$post_image = ($image != '') ? $image[0] : '';
		//$html = '<script type="text/javascript">jQuery(function() {
		
		$html = 'jQuery(function() {
        vex.defaultOptions.className = \'vex-theme-os\';
	    jQuery(\'#essb-mailform'.$salt_parent.'\').click(function(){
	    '.$stats_callback.'
        vex.dialog.open({
            message: \''.($translate_mail_title != '' ? $translate_mail_title : 'Share this with a friend').'\',
            input: \'\' +
                \'<div class="vex-custom-field-wrapper"><strong>'. ($translate_mail_email != '' ? $translate_mail_email : 'Your Email').'</strong></div>\'+
                \'<input name="emailfrom" type="text" placeholder="'. ($translate_mail_email != '' ? $translate_mail_email : 'Your Email').'" required />\' +
                \'<div class="vex-custom-field-wrapper"><strong>'.($translate_mail_recipient != '' ? $translate_mail_recipient : 'Recipient Email'). '</strong></div>\'+
                \'<input name="emailto" type="text" placeholder="'.($translate_mail_recipient != '' ? $translate_mail_recipient : 'Recipient Email'). '" required />\' +
                \'<div class="vex-custom-field-wrapper" style="border-bottom: 1px solid #aaa !important; margin-top: 10px;"><h3></h3></div>\'+
                \'<div class="vex-custom-field-wrapper" style="margin-top: 10px;"><strong>'.($translate_mail_subject != '' ? $translate_mail_subject : 'Subject').'</strong></div>\'+
                \'<input name="emailsubject" type="text" placeholder="Subject" required value="'.$title.'" />\' +
                \'<div class="vex-custom-field-wrapper" style="margin-top: 10px;"><strong>'.($translate_mail_message != '' ? $translate_mail_message : 'Message').'</strong></div>\'+
                \'<textarea name="emailmessage" placeholder="Message" required" rows="6">'.$text.' </textarea>\' +
                '.$captcha_html. '
            \'\',
            buttons: [
                jQuery.extend({}, vex.dialog.buttons.YES, { text: \''.($translate_mail_send != '' ? $translate_mail_send : 'Send').'\' }),
                jQuery.extend({}, vex.dialog.buttons.NO, { text: \''.($translate_mail_cancel != '' ? $translate_mail_cancel : 'Cancel').'\' })
            ],
            callback: function (data) {
				if (data.emailfrom && typeof(data.emailfrom) != "undefined") {
					var c = typeof(data.captchacode) != "undefined" ? data.captchacode : "";
					essb_sendmail_ajax'.$salt.'(data.emailfrom, data.emailto, data.emailsubject, data.emailmessage, c);
				}
	}
        });
    });
});
		function essb_sendmail_ajax'.$salt.'(emailfrom, emailto, emailsub, emailmessage, c) {
			
			var get_address = "' . ESSB_PLUGIN_URL . '/public/essb-mail.php?from="+emailfrom+"&to="+emailto+"&sub="+emailsub+"&message="+emailmessage+"&t='.urlencode ($site_title).'&u='.urlencode ($url).'&img='.urlencode($post_image).'&p='.urlencode ($permalink).'&c="+c;
			jQuery.getJSON(get_address)
					.done(function(data){
						alert(data.message);
					});
		};
		';
		
		return $html;
	}

	public function print_mailer_code($title, $text, $salt_parent, $stats_ative, $post_id, $position, $shorturl = '') {
		global $post;
	
		$salt = mt_rand ();
		$mailform_id = 'essb_mail_from_'.$salt;
		$stats_callback = "";
	
		if ($stats_ative == 'true') {
			$stats_callback = "essb_handle_stats('mail');";
		}
	
		$stats_callback .= " essb_self_postcount('mail', ".$post_id.");";
	
		if ($this->ga_tracking) {
			$stats_callback .= 'essb_ga_tracking("mail", "'.$position.'");';
		}
	
		//if ($shorturl != '') {
		//	$permalink = $shorturl;
		//}
	
		$text =nl2br($text);
		$text = str_replace("\r", "", $text);
		$text = str_replace("\n", "", $text);
	
		$text = str_replace("'", "&apos;", $text);
		$title = str_replace("'", "&apos;", $title);
	
		$siteurl = ESSB_PLUGIN_URL. '/';
		//$open = 'javascript:PopupContact_OpenForm("PopupContact_BoxContainer","PopupContact_BoxContainerBody","PopupContact_BoxContainerFooter");';
		$site_title = get_the_title();
		$url = get_site_url();
		$permalink = get_permalink();
	
		$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : '';
		$post_image = ($image != '') ? $image[0] : '';
		//$html = '<script type="text/javascript">jQuery(function() {
	
		$html = 'jQuery(function() {
		jQuery(\'#essb-mailform'.$salt_parent.'\').click(function(){
		'.$stats_callback.'
		essb_mailer(\''.$title.'\', \''.$text.'\', \''.$site_title.'\', \''.$url.'\', \''.$post_image.'\', \''.$permalink.'\', \''.$shorturl.'\');
		});
		});;
	';
	
		return $html;
	}
	
	
	
	function display_sidebar_in_footer() {
		global $post;
		$start = microtime(true);
		$execution_trance = "";

		$options = $this->options;
		
		if( isset($options['display_in_types']) ) {
		
			// write buttons only if administrator checked this type
			$is_all_lists = in_array('all_lists', $options['display_in_types']);
			$singular_options = $options['display_in_types'];
				
			$is_set_list = count($singular_options) > 0 ?  true: false;
				
			unset($singular_options['all_lists']);
				
			$is_lists_authorized = (is_archive() || is_front_page() || is_search() || is_tag() || is_post_type_archive() || is_home()) && $is_all_lists ? true : false;
			$is_singular = is_singular($singular_options);
		
			if ($is_singular && !$is_set_list) {
				$is_singular = false;
			}
				
			$excule_from = isset($options['display_exclude_from']) ? $options['display_exclude_from'] : '';
				
			// @since 1.3.8.2
			if ($excule_from != '') {
				$excule_from = explode(',', $excule_from);
		
				$excule_from = array_map('trim', $excule_from);
		
				if (in_array($post->ID, $excule_from, false)) {
					$is_singular = false;
					$is_lists_authorized = false;
				}
			}
				
			if ( $is_singular || $is_lists_authorized ) {
				$pt_settings = array();
		
				// @ since 1.3.9.6 - correct state before additioanl display method generation
				$this->set_options_by_bp_activate_state();
		
				if ($this->options_by_pt_active) {
					$pt_settings = EasySocialShareButtons_Advanced_Display::get_options_by_pt();
				}
		
				if ($this->options_by_pt_active && isset($post)) {
					$pt_counters = isset($pt_settings['counters']) ? $pt_settings['counters'] : '';
					if ($pt_counters != '') {
						$options ['show_counter'] = intval($pt_counters);
					}
				}
		
				$post_counters =  get_post_meta($post->ID,'essb_counter',true);
					
				if ($post_counters != '') {
					$options ['show_counter'] = $post_counters;
				}
		
				$need_counters = $options['show_counter'] ? 1 : 0;
					
				$display_where = isset($options['display_where']) ? $options['display_where'] : '';
		
				if ($this->options_by_pt_active && isset($post)) {
					$pt_position = isset($pt_settings['position']) ? $pt_settings['position'] : '';
					if ($pt_position != '') {
						$display_where = $pt_position;
					}
				}
		
				$post_position =  get_post_meta($post->ID,'essb_position',true);
				if ($post_position != '' ) {
					$display_where = $post_position;
				}
				
		
				// @since 1.3.8.2 - mobile display render in alternative way
				if ($this->isMobile()){
					$display_position_mobile = isset($options['display_position_mobile']) ? $options['display_position_mobile'] : '';
						
					if ($display_position_mobile != '') {
						$display_where = $display_position_mobile;
					}
				}
				
				$sidebar_draw_in_footer = isset($options['sidebar_draw_in_footer']) ? $options['sidebar_draw_in_footer'] : 'false';
						
				if ('sidebar' == $display_where && $sidebar_draw_in_footer == 'true') {

						$links = $this->generate_share_snippet(array(), $need_counters);
						$js_code = '';
						
						$sidebar_bottom_percent = isset($options['sidebar_bottom_percent']) ? $options['sidebar_bottom_percent'] : '';
						$sidebar_top_percent = isset($options['sidebar_top_percent']) ? $options['sidebar_top_percent'] : '';
						if ($sidebar_bottom_percent != '' || $sidebar_top_percent != '') { $js_code = $this->display_sidebar_in_footer_percent_code($sidebar_bottom_percent, $sidebar_top_percent); }
						
						echo $links.$js_code;
				}

				
				// @since 1.3.5
				$another_display_sidebar = isset($options['another_display_sidebar']) ? $options['another_display_sidebar'] : 'false';
				$another_display_sidebar_counter = isset($options['another_display_sidebar_counter']) ? $options['another_display_sidebar_counter'] : 'false';
				
				// @since 1.3.9.5 post type options
				if ($this->options_by_pt_active && isset($post)) {
					$pt_another_display_sidebar = isset($pt_settings['another_display_sidebar']) ? $pt_settings['another_display_sidebar'] : '';
					if ($pt_another_display_sidebar != '') {
						$another_display_sidebar = (intval($pt_another_display_sidebar) == 1) ? 'true' : 'false';
					}
				
				}
				
				//print ' $another_display_sidebar_counter = '.$another_display_sidebar_counter;
				//$debug_lab = microtime(true);
				//$execution_trance .= ' break3 '. ($debug_lab - $start) . ' | ';
				$post_another_display_sidebar =  get_post_meta($post->ID,'essb_another_display_sidebar',true);
				$another_display_deactivate_mobile = isset($options['another_display_deactivate_mobile']) ? $options['another_display_deactivate_mobile'] : 'false';
				
				if ($post_another_display_sidebar != '') {
					if ($post_another_display_sidebar == "yes") {
						$another_display_sidebar = "true";
					}
					else { $another_display_sidebar = "false";
					}
				}
				if ($this->isMobile() && $another_display_deactivate_mobile == "true") {
					$another_display_popup = "false";
					$another_display_sidebar = "false";
				}
				
				if ($another_display_sidebar == "true" && $sidebar_draw_in_footer == 'true') {
					echo $this->render_sidebar_code($another_display_sidebar_counter);
					$js_code = '';
					
					$sidebar_bottom_percent = isset($options['sidebar_bottom_percent']) ? $options['sidebar_bottom_percent'] : '';
					$sidebar_top_percent = isset($options['sidebar_top_percent']) ? $options['sidebar_top_percent'] : '';
					if ($sidebar_bottom_percent != '' || $sidebar_top_percent != '') {
						$js_code = $this->display_sidebar_in_footer_percent_code($sidebar_bottom_percent, $sidebar_top_percent);
						echo $js_code;
					}
				}
				
			}
			
		}
		
	}
	
	function display_sidebar_in_footer_percent_code($value, $value_top) {
		$value = str_replace('%', '' , $value);
		
		$js_code = "<script type='text/javascript'>jQuery(document).ready(function($){
		
			$(window).scroll(essb_sidebar_onscroll);
		
			function essb_sidebar_onscroll() {
				var current_pos = $(window).scrollTop();
				var height = $(document).height()-$(window).height();
				var percentage = current_pos/height*100;

				var value_bottom = '".$value."';
				var value_top = '".$value_top."';
				
				
				var display_state = (percentage > parseInt('".$value."')) ? true : false;
				var display_state_top = (percentage > parseInt('".$value_top."')) ? true : false;
				
				if ($('.essb_displayed_sidebar_bottom').length && value_bottom != '') {
					if (display_state) {
						$('.essb_displayed_sidebar_bottom').show('fast');
					}
					else {
						$('.essb_displayed_sidebar_bottom').hide('fast');
					}
				}
				if ($('.essb_displayed_sidebar_top').length && value_top != '') {
					if (display_state_top) {
						$('.essb_displayed_sidebar_top').show('fast');
					}
					else {
						$('.essb_displayed_sidebar_top').hide('fast');
					}
				}
			}
		});</script>
		";;
		
		return $js_code;
	}
	
	function print_share_links($content) {
		global $post;

		$start = microtime(true);
		$execution_trance = "";
		
		
		$options = $this->options;
	
		if( isset($options['display_in_types']) ) {
	
			// write buttons only if administrator checked this type
			$is_all_lists = in_array('all_lists', $options['display_in_types']);
			$singular_options = $options['display_in_types'];
			
			$is_set_list = count($singular_options) > 0 ?  true: false;	
			
			unset($singular_options['all_lists']);
			
			$is_lists_authorized = (is_archive() || is_front_page() || is_search() || is_tag() || is_post_type_archive() || is_home()) && $is_all_lists ? true : false;
			$is_singular = is_singular($singular_options);

			if ($is_singular && !$is_set_list) { $is_singular = false; }
			
			$serialized_test = serialize($singular_options);
			
			$excule_from = isset($options['display_exclude_from']) ? $options['display_exclude_from'] : '';
			
			// @since 1.3.8.2
			if ($excule_from != '') {
				$excule_from = explode(',', $excule_from);
				
				$excule_from = array_map('trim', $excule_from);
				
				if (in_array($post->ID, $excule_from, false)) {
					$is_singular = false;
					$is_lists_authorized = false;
				}
			}
			
			// AI1EC fix of exporet
			$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
			$exist_ai1ec_export = strpos($request_uri, 'ai1ec_exporter_controller');
			if ($exist_ai1ec_export !== false) { $is_singular = false; $is_lists_authorized = false; }
			
			//$content .= " is_singular = ".$is_singular. " | is_list = ".$is_lists_authorized . ' | types = '.$serialized_test . ' | '.$post->post_type. ' | '.$_SERVER['REQUEST_URI'];
			if ( $is_singular || $is_lists_authorized ) {
					
				
				$pt_settings = array();
				
				// @ since 1.3.9.6 - correct state before additioanl display method generation
				$this->set_options_by_bp_activate_state();
				
				if ($this->options_by_pt_active) {
					$pt_settings = EasySocialShareButtons_Advanced_Display::get_options_by_pt();					
				}
				
				if ($this->options_by_pt_active && isset($post)) {
					$pt_counters = isset($pt_settings['counters']) ? $pt_settings['counters'] : '';
					if ($pt_counters != '') {
						$options ['show_counter'] = intval($pt_counters);
					}
				}
				
				$post_counters =  get_post_meta($post->ID,'essb_counter',true);
					
				if ($post_counters != '') {
					$options ['show_counter'] = $post_counters;
				}
				
				$need_counters = $options['show_counter'] ? 1 : 0;	
							
				$this->print_links_position = "top";
				$links = $this->generate_share_snippet(array(), $need_counters);
	
				$display_where = isset($options['display_where']) ? $options['display_where'] : '';
				
				if ($this->options_by_pt_active && isset($post)) {
					$pt_position = isset($pt_settings['position']) ? $pt_settings['position'] : '';
					if ($pt_position != '') {
						$display_where = $pt_position;
					}
				}
				
				$post_position =  get_post_meta($post->ID,'essb_position',true);
				if ($post_position != '' ) { $display_where = $post_position; }
				
				// @since 1.3.8.2 - mobile display render in alternative way
				if ($this->isMobile()){
					$display_position_mobile = isset($options['display_position_mobile']) ? $options['display_position_mobile'] : '';
					
					if ($display_position_mobile != '') { $display_where = $display_position_mobile; }
				}
				
				//$debug_lab = microtime(true);
				//$execution_trance .= ' break1 '. ($debug_lab - $start) . ' | ';
				
				// @since 1.3.1 sidebar is moved to bottom render to avoid pop in excerpts
				$this->print_links_position = "top";
				$sidebar_draw_in_footer = isset($options['sidebar_draw_in_footer']) ? $options['sidebar_draw_in_footer'] : 'false';
				if ($sidebar_draw_in_footer == 'true' && $display_where == 'sidebar') {
					$display_where = 'moved-footer';
				}
				
				if ('sidebar' == $display_where) {
					$content = '<div class="essb_sidebar_start_scroll"></div>'.$content;
				}
				
				if( 'top' == $display_where || 'both' == $display_where || 'float' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where || 'postfloat' == $display_where)
					$content = $links.$content;
				if( 'bottom' == $display_where || 'both' == $display_where || 'popup' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where || 'sidebar' == $display_where) {
					$this->print_links_position = "bottom";
					if ('both' == $display_where || 'likeshare' == $display_where || 'sharelike' == $display_where) {
						$links = $this->generate_share_snippet(array(), $need_counters);
					}
					$content = $content.$links;
				}
				if ('inline' == $display_where) {
					$content = str_replace('<!--easy-share-->', $links, $content);
				}
 				
				//$debug_lab = microtime(true);
				//$execution_trance .= ' break2 '. ($debug_lab - $start) . ' | ';
				
				// @since 1.3.9.1
				if ('sidebar' == $display_where) {
					$content .= '<div class="essb_sidebar_break_scroll"></div>';
				}
				
				
				// @since 1.3.5
				$another_display_sidebar = isset($options['another_display_sidebar']) ? $options['another_display_sidebar'] : 'false';
				$another_display_sidebar_counter = isset($options['another_display_sidebar_counter']) ? $options['another_display_sidebar_counter'] : 'false';
				$another_display_popup = isset($options['another_display_popup']) ? $options['another_display_popup'] : 'false';

				$another_display_postfloat = isset($options['another_display_postfloat']) ? $options['another_display_postfloat'] : 'false';
				$another_display_postfloat_counter = isset($options['another_display_postfloat_counter']) ? $options['another_display_postfloat_counter'] : 'false';
				
				// @since 1.3.9.5 post type options
				if ($this->options_by_pt_active && isset($post)) {
					$pt_another_display_sidebar = isset($pt_settings['another_display_sidebar']) ? $pt_settings['another_display_sidebar'] : '';
					if ($pt_another_display_sidebar != '') {
						$another_display_sidebar = (intval($pt_another_display_sidebar) == 1) ? 'true' : 'false';
					}

					$pt_another_display_popup = isset($pt_settings['another_display_popup']) ? $pt_settings['another_display_popup'] : '';
					if ($pt_another_display_popup != '') {
						$aanother_display_popup = (intval($pt_another_display_popup) == 1) ? 'true' : 'false';
					}
					
					$pt_another_display_postfloat = isset($pt_settings['another_display_postfloat']) ? $pt_settings['another_display_postfloat'] : '';
					if ($pt_another_display_postfloat != '') {
						$another_display_postfloat = (intval($pt_another_display_postfloat) == 1) ? 'true' : 'false';
					}						
				}
				
				//print ' $another_display_sidebar_counter = '.$another_display_sidebar_counter;
				//$debug_lab = microtime(true);
				//$execution_trance .= ' break3 '. ($debug_lab - $start) . ' | ';
				$post_another_display_sidebar =  get_post_meta($post->ID,'essb_another_display_sidebar',true);
				$post_another_display_popup =  get_post_meta($post->ID,'essb_another_display_popup',true);
				$post_another_display_postfloat =  get_post_meta($post->ID,'essb_another_display_postfloat',true);
				//print "!$post_another_display_postfloat = " . $post_another_display_postfloat;
				$another_display_deactivate_mobile = isset($options['another_display_deactivate_mobile']) ? $options['another_display_deactivate_mobile'] : 'false';
				
				if ($post_another_display_sidebar != '') {
					if ($post_another_display_sidebar == "yes") { $another_display_sidebar = "true"; }
					else { $another_display_sidebar = "false"; }
				}
				if ($post_another_display_popup != '') {
					if ($post_another_display_popup == "yes") {
						$another_display_popup = "true";
					}
					else { $another_display_popup = "false";
					}
				}
				
				if ($post_another_display_postfloat != '') {
					if ($post_another_display_postfloat == "yes") {
						$another_display_postfloat = "true";
					}
					else {
						$another_display_postfloat = "false";
					}
				}
				
				if ($sidebar_draw_in_footer == "true") { $another_display_sidebar = "false"; }
				
// 				/$debug_lab = microtime(true);
				//$execution_trance .= ' break4 '. ($debug_lab - $start) . ' | ';
				if ($this->isMobile() && $another_display_deactivate_mobile == "true") {
					$another_display_popup = "false";
					$another_display_sidebar = "false";
				}
				
				if ($another_display_sidebar == "true") { $content = $content.$this->render_sidebar_code($another_display_sidebar_counter); }
				//$debug_lab = microtime(true);
				//$execution_trance .= ' break5 '. ($debug_lab - $start) . ' | ';
				if ($another_display_popup == "true") {
					$content = $content.$this->render_popup_code();
				}
				//$debug_lab = microtime(true);
				//$execution_trance .= ' break6 '. ($debug_lab - $start) . ' | ';
				if ($another_display_postfloat == "true") {
					//print "activate display as post float";
					$content = $this->render_postfloat_code($another_display_postfloat_counter).$content;
				}
				
				$end = microtime(true);
				
				return $content;
				
				//return "Execution: ".($end-$start). ' secs |'. $execution_trance.$content;
			}
			else
				return $content;
		}
		else
			return $content;
	
	} // end function
	
	function print_share_links_excerpt($content) {
		global $post;
	
		$start = microtime(true);
		$execution_trance = "";
	
		$options = $this->options;
	
		if( isset($options['display_in_types']) ) {
			// write buttons only if administrator checked this type
			$is_all_lists = in_array('all_lists', $options['display_in_types']);
			$singular_options = $options['display_in_types'];
				
			$is_set_list = count($singular_options) > 0 ?  true: false;
				
			unset($singular_options['all_lists']);
				
			$is_lists_authorized = (is_archive() || is_front_page() || is_search() || is_tag() || is_post_type_archive() || is_home()) && $is_all_lists ? true : false;
			$is_singular = is_singular($singular_options);
	
			if ($is_singular && !$is_set_list) {
				$is_singular = false;
			}
				
			$excule_from = isset($options['display_exclude_from']) ? $options['display_exclude_from'] : '';
				
			// @since 1.3.8.2
			if ($excule_from != '') {
				$excule_from = explode(',', $excule_from);
	
				$excule_from = array_map('trim', $excule_from);
	
				if (in_array($post->ID, $excule_from, false)) {
					$is_singular = false;
					$is_lists_authorized = false;
				}
			}
			if ( $is_singular || $is_lists_authorized ) {
				$pt_settings = array();
	
				// @ since 1.3.9.6 - correct state before additioanl display method generation
				$this->set_options_by_bp_activate_state();
	
				if ($this->options_by_pt_active) {
					$pt_settings = EasySocialShareButtons_Advanced_Display::get_options_by_pt();
				}
	
				if ($this->options_by_pt_active && isset($post)) {
					$pt_counters = isset($pt_settings['counters']) ? $pt_settings['counters'] : '';
					if ($pt_counters != '') {
						$options ['show_counter'] = intval($pt_counters);
					}
				}
	
				$post_counters =  get_post_meta($post->ID,'essb_counter',true);
					
				if ($post_counters != '') {
					$options ['show_counter'] = $post_counters;
				}
	
				$need_counters = $options['show_counter'] ? 1 : 0;
				
				$display_where = isset($options['display_excerpt_pos']) ? $options['display_excerpt_pos'] : 'top';
				$this->print_links_position = "top";
				$links = $this->generate_share_snippet(array(), $need_counters);
	
				if ($display_where == "top") {
					$content = $links.$content;
				}			
				else if ($display_where == "bottom") {
					$content = $content.$links;
				}
	
	
				return $content;
			}
			else
				return $content;
		}
		else
			return $content;
	
	} // end function
	
	
	function handle_essb_total_shortcode ($atts) {
		global $post;
		
		$atts = shortcode_atts(array(
				'message' => '',
				'align' => '',
				'url' => '',
				'share_text' => '',
				'fullnumber' => 'no',
				'networks' => ''
		), $atts);
		
		$align = isset($atts['align']) ? $atts['align'] : '';
		$message = isset($atts['message']) ? $atts['message'] : '';
		$url = isset($atts['url']) ? $atts['url'] : '';
		$share_text = isset($atts['share_text']) ? $atts['share_text'] : '';
		$fullnumber = isset($atts['fullnumber']) ? $atts['fullnumber'] : 'no';
		$networks = isset($atts['networks']) ? $atts['networks'] : 'no';
		
		$data_full_number = "false";
		if ($fullnumber == 'yes') { $data_full_number = "true"; } 
		
		// init global options
		$options = $this->options;

		$essb_networks = $options['networks'];
		$buttons = "";
		foreach($essb_networks as $k => $v) {
			if ($buttons != '') {
				$buttons .= ",";
			}
			$buttons .= $k;
				
		}
		
		if ($networks != '') { $buttons = $networks; }
		
		$facebook_totalcount = isset($options['facebooktotal']) ? $options['facebooktotal'] : 'false';
		$force_counter_adminajax = isset($options['force_counters_admin']) ? $options['force_counters_admin'] : 'false';
		
		
		
		$css_class_align = "";

		$data_url = $post ? get_permalink() : $this->get_current_url( 'raw' );
		if ($this->avoid_next_page) {
			$data_url = $post ? get_permalink($post->ID) : $this->get_current_url( 'raw' );				
		}
		if ($url != '' ) { $data_url = $url; }
		
		
		$data_post_id = "";
		if (isset($post)) {
			$data_post_id = $post->ID;
		}
		
		if ($align == "right" || $align == "center") {
			$css_class_align = $align;
		}
		
		
		if (!$this->shortcode_like_css_included) {
			$this->shortcode_like_css_included = true;
			wp_enqueue_style ( 'essb-social-like', ESSB_PLUGIN_URL . '/assets/css/essb-social-like-buttons.css', false, $this->version, 'all' );
		
		}
		
		//if( $this->$counter_included ) {
			if ($this->js_minifier) {
				if (!$this->load_js_async) {
					if ($this->using_self_counters) {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js', array ('jquery' ), $this->version, true );
					}
					else {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js', array ('jquery' ), $this->version, true );
					}
				}	
				else {
					if ($this->using_self_counters) {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js');
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js';
					}
					else {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js';
					}
				}			
			}
			else {
				if (!$this->load_js_async) {
					if ($this->using_self_counters) {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js', array ('jquery' ), $this->version, true );
					}
					else {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js', array ('jquery' ), $this->version, true );
					}
				}
				else {
					if ($this->using_self_counters) {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js';
					}
					else {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js';
					}
				}
			}
		//}
		
		$ajax_count_url = admin_url ('admin-ajax.php');
		
		$output = "";
		$output .= '<div class="essb-total '.$css_class_align.'" data-network-list="'.$buttons.'" data-url="'.$data_url.'" data-fb-total="'.$facebook_totalcount.'" data-counter-url="'.ESSB_PLUGIN_URL.'" data-ajax-url="'.$ajax_count_url.'" data-force-ajax="'.$force_counter_adminajax.'" data-full-number="'.$data_full_number.'" data-post="'.$data_post_id.'">';
		
		if ($message != '') {
			$output .= '<div class="essb-message essb-block">'.$message.'</div>';
		}

		$output .= '<div class="essb-total-value essb-block">0</div>';
		if ($share_text != '') {
			$output .= '<div class="essb-total-text essb-block">'.$share_text.'</div>';
		}
				
		$output .= '</div>';
		
		
		return $output;
		
	}
	
	function handle_essb_like_buttons_shortcode($atts) {
		global $post;
		
		$atts = shortcode_atts(array(
				'facebook' => 'false',
				'facebook_url'	=> '',
				'facebook_width' => '',
				'facebook_skinned_text' => '',
				'facebook_skinned_width' => '',
				'facebook_follow' => 'false',
				'facebook_follow_url'	=> '',
				'facebook_follow_width' => '',
				'facebook_follow_skinned_text' => '',
				'facebook_follow_skinned_width' => '',
				'twitter_follow'	=> 'false',
				'twitter_follow_user' => '',
				'twitter_follow_skinned_text' => '',
				'twitter_follow_skinned_width' => '',
				'twitter_tweet' => 'false',
				'twitter_tweet_message' => '',
				'twitter_tweet_skinned_text' => '',
				'twitter_tweet_skinned_width' => '',
				'google' => 'false',
				'google_url'=> '',
				'google_skinned_text' => '',
				'google_skinned_width' => '',
				'google_follow' => 'false',
				'google_follow_url'=> '',
				'google_follow_skinned_text' => '',
				'google_follow_skinned_width' => '',
				'vk' => 'false',
				'vk_skinned_text' => '',
				'vk_skinned_width' => '',
				'youtube' => 'false',
				'youtube_chanel' => '',
				'youtube_skinned_text' => '',
				'youtube_skinned_width' => '',
				'linkedin' => 'false',
				'linkedin_company' => '',
				'linkedin_skinned_text' => '',
				'linkedin_skinned_width' => '',
				'pinterest_pin' => 'false',
				'pinterest_pin_skinned_text' => '',
				'pinterest_pin_skinned_width' => '',
				'pinterest_follow' => 'false',
				'pinterest_follow_display' => '',
				'pinterest_follow_url' => '',				
				'pinterest_follow_skinned_text' => '',
				'pinterest_follow_skinned_width' => '',
				'skinned' => 'false',
				'skin' => 'flat',
				'message' => '',
				'align' => '',
				'counters' => 'false'
		), $atts);
		
		$align = isset($atts['align']) ? $atts['align'] : '';
		$facebook = isset($atts['facebook']) ? $atts['facebook'] : '';
		$facebook_url = isset($atts['facebook_url']) ? $atts['facebook_url'] : '';
		$facebook_width = isset($atts['facebook_width']) ? $atts['facebook_width'] : '';
		$facebook_skinned_text = isset($atts['facebook_skinned_text']) ? $atts['facebook_skinned_text'] : '';
		$facebook_skinned_width = isset($atts['facebook_skinned_width']) ? $atts['facebook_skinned_width'] : '';

		$facebook_follow = isset($atts['facebook_follow']) ? $atts['facebook_follow'] : '';
		$facebook_follow_url = isset($atts['facebook_follow_url']) ? $atts['facebook_follow_url'] : '';
		$facebook_follow_width = isset($atts['facebook_follow_width']) ? $atts['facebook_follow_width'] : '';
		$facebook_follow_skinned_text = isset($atts['facebook_follow_skinned_text']) ? $atts['facebook_follow_skinned_text'] : '';
		$facebook_follow_skinned_width = isset($atts['facebook_follow_skinned_width']) ? $atts['facebook_follow_skinned_width'] : '';		
		
		$twitter_follow = isset($atts['twitter_follow']) ? $atts['twitter_follow'] : '';
		$twitter_follow_user = isset($atts['twitter_follow_user']) ? $atts['twitter_follow_user'] : '';
		$twitter_follow_skinned_text = isset($atts['twitter_follow_skinned_text']) ? $atts['twitter_follow_skinned_text'] : '';
		$twitter_follow_skinned_width = isset($atts['twitter_follow_skinned_width']) ? $atts['twitter_follow_skinned_width'] : '';
		
		$twitter_tweet = isset($atts['twitter_tweet']) ? $atts['twitter_tweet'] : '';
		$twitter_tweet_message = isset($atts['twitter_tweet_message']) ? $atts['twitter_tweet_message'] : '';
		$twitter_tweet_skinned_text = isset($atts['twitter_tweet_skinned_text']) ? $atts['twitter_tweet_skinned_text'] : '';
		$twitter_tweet_skinned_width = isset($atts['twitter_tweet_skinned_width']) ? $atts['twitter_tweet_skinned_width'] : '';
		
		$google = isset($atts['google']) ? $atts['google'] : '';
		$google_url = isset($atts['google_url']) ? $atts['google_url'] : '';
		$google_skinned_text = isset($atts['google_skinned_text']) ? $atts['google_skinned_text'] : '';
		$google_skinned_width = isset($atts['google_skinned_width']) ? $atts['google_skinned_width'] : '';

		$google_follow = isset($atts['google_follow']) ? $atts['google_follow'] : '';
		$google_follow_url = isset($atts['google_follow_url']) ? $atts['google_follow_url'] : '';
		$google_follow_skinned_text = isset($atts['google_follow_skinned_text']) ? $atts['google_follow_skinned_text'] : '';
		$google_follow_skinned_width = isset($atts['google_follow_skinned_width']) ? $atts['google_follow_skinned_width'] : '';
		
		$vk = isset($atts['vk']) ? $atts['vk'] : '';
		$vk_skinned_text = isset($atts['vk_skinned_text']) ? $atts['vk_skinned_text'] : '';
		$vk_skinned_width = isset($atts['vk_skinned_width']) ? $atts['vk_skinned_width'] : '';
		
		$youtube = isset($atts['youtube']) ? $atts['youtube'] : '';
		$youtube_chanel = isset($atts['youtube_chanel']) ? $atts['youtube_chanel'] : '';
		$youtube_skinned_text = isset($atts['youtube_skinned_text']) ? $atts['youtube_skinned_text'] : '';
		$youtube_skinned_width = isset($atts['youtube_skinned_width']) ? $atts['youtube_skinned_width'] : '';

		$linkedin = isset($atts['linkedin']) ? $atts['linkedin'] : '';
		$linkedin_comapny = isset($atts['linkedin_company']) ? $atts['linkedin_company'] : '';
		$linkedin_skinned_text = isset($atts['linkedin_skinned_text']) ? $atts['linkedin_skinned_text'] : '';
		$linkedin_skinned_width = isset($atts['linkedin_skinned_width']) ? $atts['linkedin_skinned_width'] : '';
		
		$pinterest_pin = isset($atts['pinterest_pin']) ? $atts['pinterest_pin'] : '';
		$pinterest_pin_skinned_text = isset($atts['pinterest_pin_skinned_text']) ? $atts['pinterest_pin_skinned_text'] : '';
		$pinterest_pin_skinned_width = isset($atts['pinterest_pin_skinned_width']) ? $atts['pinterest_pin_skinned_width'] : '';
		
		$pinterest_follow = isset($atts['pinterest_follow']) ? $atts['pinterest_follow'] : '';
		$pinterest_follow_display = isset($atts['pinterest_follow_display']) ? $atts['pinterest_follow_display'] : '';
		$pinterest_follow_url = isset($atts['pinterest_follow_url']) ? $atts['pinterest_follow_url'] : '';
		$pinterest_follow_skinned_text = isset($atts['pinterest_follow_skinned_text']) ? $atts['pinterest_follow_skinned_text'] : '';
		$pinterest_follow_skinned_width = isset($atts['pinterest_follow_skinned_width']) ? $atts['pinterest_follow_skinned_width'] : '';
		
		$skinned = isset($atts['skinned']) ? $atts['skinned'] : 'false';
		$skin = isset($atts['skin']) ? $atts['skin'] : '';
		$message = isset($atts['message']) ? $atts['message'] : '';
		$counters = isset($atts['counters']) ? $atts['counters'] : 'false';
		
		// init global options
		$options = $this->options;
		$native_lang = isset($options['native_social_language']) ? $options['native_social_language'] : "en";
		
		$css_class_align = "";
		$css_class_noskin = ($skinned != 'true') ? ' essb-noskin' : '';
		
		$current_state_skinned = $this->skinned_social;
		$current_state_selected_skin = $this->skinned_social_selected_skin;
		
		if ($skinned != 'true') { $this->skinned_social = false; } else {$this->skinned_social = true; $this->skinned_social_selected_skin = $skin; }
		
		if (!$current_state_skinned && $skinned == 'true') {
			ESSB_Skinned_Native_Button::registerCSS();
		}
		
		$text = esc_attr(urlencode($post->post_title));
		$url = $post ? get_permalink() : $this->get_current_url( 'raw' );
		if ($this->avoid_next_page) {
			$url = $post ? get_permalink($post->ID) : $this->get_current_url( 'raw' );
		}
  		
		if ($align == "right" || $align == "center") {
			$css_class_align = $align;
		}
		
		if (!$this->shortcode_like_css_included) {
			$this->shortcode_like_css_included = true;
			wp_enqueue_style ( 'essb-social-like', ESSB_PLUGIN_URL . '/assets/css/essb-social-like-buttons.css', false, $this->version, 'all' );
				
		}
				
		$output = "";
		$output .= '<div class="essb-like '.$css_class_align.'">';
		
		if ($message != '') {
			$output .= '<div class="essb-message">'.$message.'</div>';
		}
		
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($facebook) == 'true') {
			if ($facebook_url == "") { $facebook_url = $url; }
			
			$output .= '<div class="essb-like-facebook essb-block'.$css_class_noskin.'">';
			$output .= $this->print_fb_likebutton($facebook_url, $counters, $facebook_width, $facebook_skinned_text, $facebook_skinned_width);
			$output .= '</div>'; 
		}

		if (EasySocialShareButtons_ShortcodeHelper::unified_true($facebook_follow) == 'true') {
			$output .= '<div class="essb-like-facebook essb-block'.$css_class_noskin.'">';
			$output .= $this->print_fb_followbutton($facebook_follow_url, $counters, $facebook_follow_width, $facebook_follow_skinned_text, $facebook_follow_skinned_width);
			$output .= '</div>';
		}
		
		
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($twitter_tweet) == 'true') {
			$output .= '<div class="essb-like-twitter essb-block'.$css_class_noskin.'">';
			$output .= $this->print_twitter_tweet_button($twitter_follow_user, $counters, $native_lang, $twitter_tweet_message, $twitter_tweet_skinned_text, $twitter_tweet_skinned_width);
			$output .= '</div>';
		}
		
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($twitter_follow) == 'true') {
			$output .= '<div class="essb-like-twitter-follow essb-block'.$css_class_noskin.'">';
			$output .= $this->print_twitter_follow_button($twitter_follow_user, $counters, $native_lang, $twitter_follow_skinned_text, $twitter_follow_skinned_width);
			$output .= '</div>';
		}
		
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($google) == 'true') {
			if ($google_url == "") { $google_url =  $url; }
			$output .= '<div class="essb-like-google essb-block'.$css_class_noskin.'">';
			$output .= $this->print_plusone_button($google_url, $counters, $native_lang, $google_skinned_text, $google_skinned_width);
			$output .= '</div>';
		}

		if (EasySocialShareButtons_ShortcodeHelper::unified_true($google_follow) == 'true') {
			$output .= '<div class="essb-like-google essb-block'.$css_class_noskin.'">';
			$output .= $this->print_plusfollow_button($google_follow_url, $counters, $native_lang, $google_follow_skinned_text, $google_follow_skinned_width);
			$output .= '</div>';
		}
		
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($vk) == 'true') {
			$output .= '<div class="essb-like-vk essb-block'.$css_class_noskin.'">';
			$output .= $this->print_vklike_button($url, $counters, $vk_skinned_text, $vk_skinned_width);
			$output .= '</div>';
		}
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($youtube) == 'true') {
			$output .= '<div class="essb-like-youtube essb-block'.$css_class_noskin.'">';
			$output .= $this->print_youtube_button($youtube_chanel, $counters, $youtube_skinned_text, $youtube_skinned_width);
			$output .= '</div>';				
		}
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($pinterest_pin) == 'true') {
			$output .= '<div class="essb-like-pin essb-block'.$css_class_noskin.'">';
			$output .= $this->print_pinterest_follow($pinterest_follow_display, $pinterest_follow_url, 'pin', $pinterest_pin_skinned_text, $pinterest_pin_skinned_width);
			$output .= '</div>';				
		}
		if (EasySocialShareButtons_ShortcodeHelper::unified_true($pinterest_follow) == 'true') {
			$output .= '<div class="essb-like-pin-follow essb-block'.$css_class_noskin.'">';
			$output .= $this->print_pinterest_follow($pinterest_follow_display, $pinterest_follow_url, 'follow', $pinterest_follow_skinned_text, $pinterest_follow_skinned_width);
			$output .= '</div>';
		}
		
		$output .= '</div>';
		
		$this->skinned_social = $current_state_skinned;
		$this->skinned_social_selected_skin = $current_state_selected_skin;
		
		return $output;
	}
	
	
	function handle_essb_shortcode_vk($atts) {
		$atts['native'] = "no";
		
		$total_counter_pos = isset($atts['total_counter_pos']) ? $atts['total_counter_pos'] : '';		
		if ($total_counter_pos == "none") {
			$atts['hide_total'] = "yes";
		}

		$counter_pos = isset($atts['counter_pos']) ? $atts['counter_pos'] : '';		
		if ($counter_pos == "none") {
			$atts['counter_pos'] = "hidden";
		}
		
		return $this->handle_essb_shortcode($atts);
	}
	
	function handle_essb_shortcode($atts) {
		
		// start prepare custom display texts
		$options = $this->options;
		$shortcode_custom_display_texts = array();
		
		if (is_array ( $options )) {
			foreach ( $options ['networks'] as $k => $v ) {
				$key = $k.'_text';

				$value = isset($atts[$key]) ? $atts[$key] : '';
				
				if ($value != '') {
					$shortcode_custom_display_texts[$k] = $value;
				}		
			}
		}
		
		$shortcode_custom_messages = array();
		
		if (is_array ( $options )) {
			foreach ( $options ['networks'] as $k => $v ) {
				$key = $k.'_message';
		
				$value = isset($atts[$key]) ? $atts[$key] : '';
		
				if ($value != '') {
					$shortcode_custom_messages[$k] = $value;
				}
			}
		}
		
		$atts = shortcode_atts(array(
				//'buttons' 	=> 'facebook,twitter,mail,google,stumbleupon,linkedin,pinterest,digg,vk',
			    'buttons' => '',
				'counters'	=> 0,
				'current'	=> 1,
				'text' => '',
				'url' => '',
				'native' => 'yes',
				'sidebar' => 'no',
				'popup'=> 'no',
				'popafter' => '',
				'message' => 'no',
				'description' => '',
				'image' => '',
				'fblike' => '',
				'plusone' => '',
				'show_fblike' => 'no',
				'show_twitter' => 'no',
				'show_plusone' => 'no',
				'show_vk' => 'no',
				'hide_names' => 'no',
				'counters_pos' => '',
				'counter_pos' => '',
				'sidebar_pos' => '',
				'show_youtube' => 'no',
				'show_pinfollow' => 'no',
				'nostats' => 'no',
				'hide_total' => 'no',
				'total_counter_pos' => '',
				'fullwidth' =>  'no',
				'fullwidth_fix' => '',
				'fixedwidth' => 'no',
				'fixedwidth_px' => '',
				'show_managedwp' => 'no',
				'float' => 'no',
				'postfloat' => 'no',
				'morebutton' => '',
				'forceurl' => 'no',
				'videoshare' => 'no',
				'template' => ''
		), $atts); 
			

		$exist_mail = strpos($atts['buttons'], 'mail');
		
		//print "shortcode handle";
		// buttons become array ("digg,mail", "digg ,mail", "digg, mail", "digg , mail", are right syntaxes)
		if ( $atts['buttons'] == '') {
			$networks = array();
		}
		else {
			$networks = preg_split('#[\s+,\s+]#', $atts['buttons']);
		}
		$counters = intval($atts['counters']);
		$current_page = intval($atts['current']);
		
		$text = isset($atts['text']) ? $atts['text'] : '';
		$url = isset($atts['url']) ? $atts['url'] : '';
		$native = isset($atts['native']) ? $atts['native'] : 'no';		
		$native = EasySocialShareButtons_ShortcodeHelper::unified_yes($native);
		$sidebar = isset($atts['sidebar']) ? $atts['sidebar'] : 'no'; 
		$sidebar = EasySocialShareButtons_ShortcodeHelper::unified_yes($sidebar);
		$popup = isset($atts['popup']) ? $atts['popup'] : 'no';
		$popup = EasySocialShareButtons_ShortcodeHelper::unified_yes($popup);
		$message = isset($atts['message']) ? $atts['message'] : 'no';
		$message = EasySocialShareButtons_ShortcodeHelper::unified_yes($message);
		$popafter = isset($atts['popafter']) ? $atts['popafter'] : '';
		$description = isset($atts['description']) ? $atts['description'] : '';
		$image = isset($atts['image']) ? $atts['image'] : '';
		$fblike = isset($atts['fblike']) ? $atts['fblike'] : '';
		$plusone = isset($atts['plusone']) ? $atts['plusone'] : '';

		$show_fblike = isset($atts['show_fblike']) ? $atts['show_fblike'] : 'no';
		$show_fblike = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_fblike);
		$show_twitter = isset($atts['show_twitter']) ? $atts['show_twitter'] : 'no';
		$show_twitter = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_twitter);
		$show_plusone = isset($atts['show_plusone']) ? $atts['show_plusone'] : 'no';
		$show_plusone = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_plusone);
		$show_vk = isset($atts['show_vk']) ? $atts['show_vk'] : 'no';
		$show_vk = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_vk);
		$hide_names = isset($atts['hide_names']) ? $atts['hide_names'] : 'no';
		$hide_names = EasySocialShareButtons_ShortcodeHelper::unified_yes($hide_names);
		$counters_pos = isset($atts['counters_pos']) ? $atts['counters_pos'] : '';
		$counter_pos = isset($atts['counter_pos']) ? $atts['counter_pos'] : '';
		$sidebar_pos = isset($atts['sidebar_pos']) ? $atts['sidebar_pos'] : '';
		
		$show_youtube = isset($atts['show_youtube']) ? $atts['show_youtube'] : 'no';
		$show_youtube = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_youtube);
		$show_pinfollow = isset($atts['show_pinfollow']) ? $atts['show_pinfollow'] : 'no';
		$show_pinfollow = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_pinfollow);
		$total_counter_pos = isset($atts['total_counter_pos'])  ? $atts['total_counter_pos'] : '';
		$fullwidth = isset($atts['fullwidth']) ? $atts['fullwidth'] : 'no';
		$fullwidth = EasySocialShareButtons_ShortcodeHelper::unified_yes($fullwidth);
		$fullwidth_fix = isset($atts['fullwidth_fix']) ? $atts['fullwidth_fix'] : '';

		$fixedwidth = isset($atts['fixedwidth']) ? $atts['fixedwidth'] : 'no';
		$fixedwidth = EasySocialShareButtons_ShortcodeHelper::unified_yes($fixedwidth);
		$fixedwidth_px = isset($atts['fixedwidth_px']) ? $atts['fixedwidth_px'] : '';
		
		$float = isset($atts['float']) ? $atts['float'] : 'no';
		$float = EasySocialShareButtons_ShortcodeHelper::unified_yes($float);

		$postfloat = isset($atts['postfloat']) ? $atts['postfloat'] : 'no';
		$postfloat = EasySocialShareButtons_ShortcodeHelper::unified_yes($postfloat);
		$morebutton = isset($atts['morebutton']) ? $atts['morebutton'] : '';
		$forceurl = isset($atts['forceurl']) ? $atts['forceurl'] : 'no';
		$forceurl = EasySocialShareButtons_ShortcodeHelper::unified_yes($forceurl);
		
		$show_managedwp = isset($atts['show_managedwp']) ? $atts['show_managedwp'] : 'no';
		$show_managedwp = EasySocialShareButtons_ShortcodeHelper::unified_yes($show_managedwp);
		$videoshare = isset($atts['videoshare']) ? $atts['videoshare'] : 'no';
		$videoshare = EasySocialShareButtons_ShortcodeHelper::unified_yes($videoshare);
		
		if ($show_pinfollow == "yes" && !$this->pinjs_registered) {
			//wp_enqueue_script ( 'essb-pinterest-follow', '//assets.pinterest.com/js/pinit.js', array ('jquery' ), $this->version, true );
			$this->essb_js_builder->add_js_lazyload('//assets.pinterest.com/js/pinit.js');				
			$this->pinjs_registered = true;				
		}
		
		if ($exist_mail != false && !$this->mailjs_registered) {
			if ($this->css_minifier) {
				wp_enqueue_style ( 'easy-social-share-buttons-mailform', ESSB_PLUGIN_URL . '/assets/css/essb-mailform.min.css', false, $this->version, 'all' );
			}
			else {
				wp_enqueue_style ( 'easy-social-share-buttons-mailform', ESSB_PLUGIN_URL . '/assets/css/essb-mailform.css', false, $this->version, 'all' );
			}
			wp_enqueue_script ( 'easy-social-share-buttons-mailform', ESSB_PLUGIN_URL . '/assets/js/essb-mailform.js', array ('jquery' ), $this->version, true );
			$this->mailjs_registered = true;
		}
		
		$nostats = isset($atts['nostats']) ? $atts['nostats'] : 'no';
		$nostats = EasySocialShareButtons_ShortcodeHelper::unified_yes($nostats);
		$hide_total = isset($atts['hide_total']) ? $atts['hide_total'] : 'no';
		$hide_total = EasySocialShareButtons_ShortcodeHelper::unified_yes($hide_total);
		
		$template = isset($atts['template']) ? $atts['template'] : '';
		
		if( $counters == 1 ) {
			if ($this->js_minifier) {
				if (!$this->load_js_async) {
					if ($this->using_self_counters) {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js', array ('jquery' ), $this->version, true );
					}
					else {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js', array ('jquery' ), $this->version, true );
					}
				}
				else {
					if ($this->using_self_counters) {
						$this->essb_js_builder->add_js_lazyload( ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.min.js';
					}
					else {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.min.js';
					}
				}
			}
			else {
				if (!$this->load_js_async) {
					if ($this->using_self_counters) {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js', array ('jquery' ), $this->version, true );
					}
					else {
						wp_enqueue_script ( 'essb-counter-script', ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js', array ('jquery' ), $this->version, true );
					}
				}
				else {
					if ($this->using_self_counters) {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons-self.js';
					}
					else {
						$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js');						
						//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/easy-social-share-buttons.js';
					}
				}
			}
		}
			
		if ($sidebar == "yes" || $postfloat == "yes") {
			if ($this->css_minifier) {
				wp_enqueue_style ( 'easy-social-share-buttons-sidebar', ESSB_PLUGIN_URL . '/assets/css/essb-sidebar.min.css', false, $this->version, 'all' );
			}				
			else {
				wp_enqueue_style ( 'easy-social-share-buttons-sidebar', ESSB_PLUGIN_URL . '/assets/css/essb-sidebar.css', false, $this->version, 'all' );
			}
		}
		
		if ($popup == "yes") {
			if ($this->css_minifier) {
				wp_enqueue_style ( 'easy-social-share-buttons-popup', ESSB_PLUGIN_URL . '/assets/css/essb-popup.min.css', false, $this->version, 'all' );
			}
			else {
				wp_enqueue_style ( 'easy-social-share-buttons-popup', ESSB_PLUGIN_URL . '/assets/css/essb-popup.css', false, $this->version, 'all' );
			}
			
			if ($this->js_minifier) {
				if (!$this->load_js_async) {
					wp_enqueue_script ( 'essb-popup-script', ESSB_PLUGIN_URL . '/assets/js/essb-popup.min.js', array ('jquery' ), $this->version, true );
				}
				else {
					$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/essb-popup.min.js');
						
					//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/essb-popup.min.js';
				}
			}
			else {
				if (!$this->load_js_async) {
					wp_enqueue_script ( 'essb-popup-script', ESSB_PLUGIN_URL . '/assets/js/essb-popup.js', array ('jquery' ), $this->version, true );
				}
				else {
					$this->essb_js_builder->add_js_lazyload(ESSB_PLUGIN_URL . '/assets/js/essb-popup.js');						
					//$this->async_js_list[] = ESSB_PLUGIN_URL . '/assets/js/essb-popup.js';
				}
			}				
		}
		if ($counters_pos == "" && $counter_pos != '') { $counters_pos = $counter_pos; }
		
		//ob_start();
		$output = $this->generate_share_snippet($networks, $counters, $current_page, 1, $text, $url, $native, $sidebar, $message, $popup, $popafter, $image, $description, 
				$fblike, $plusone, $show_fblike, $show_twitter, $show_plusone, $show_vk, $hide_names, $counters_pos, $sidebar_pos, $show_youtube, $show_pinfollow, $nostats, $hide_total,
				$total_counter_pos, $fullwidth, $fullwidth_fix, $show_managedwp, $shortcode_custom_display_texts, $float, $fixedwidth, $fixedwidth_px, $postfloat, $morebutton, $forceurl,
				$shortcode_custom_messages, $videoshare, $template); //do an echo
		//$output = ob_get_contents();
		//ob_end_clean();
			
		
		
		return $output;
	}
	
	public function handle_jigoshop_share($product_id) {
		global $post;
		$options = $this->options;
		
		if (isset($post)) {
			$post_counters =  get_post_meta($post->ID,'essb_counter',true);
				
			if ($post_counters != '') {
				$options ['show_counter'] = $post_counters;
			}
	
		}
		$need_counters = $options['show_counter'] ? 1 : 0;
	
		$need_counters = $options['show_counter'] ? 1 : 0;
			
	
		$links = $this->generate_share_snippet(array(), $need_counters);
	
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	public function handle_wpecommerce_share($product_id) {
		global $post;
		$options = $this->options;
		
		if (isset($post)) {
			$post_counters =  get_post_meta($post->ID,'essb_counter',true);
			
			if ($post_counters != '') {
				$options ['show_counter'] = $post_counters;
			}
		
		}
		$need_counters = $options['show_counter'] ? 1 : 0;
		
		$need_counters = $options['show_counter'] ? 1 : 0;
			
		
		$links = $this->generate_share_snippet(array(), $need_counters);
		
		echo $links .'<div style="clear: both;\"></div>';
	}
	
	public function handle_woocommerce_share() {
		$options = $this->options;
		
		
		
				$need_counters = $options['show_counter'] ? 1 : 0;
					
		
				$links = $this->generate_share_snippet(array(), $need_counters);
		
		echo $links .'<div style="clear: both;\"></div>';		
	}
	
	// @since 1.0.7 - disable network name popup on mobile devices
	// @since 1.3.9.8 - implemeneted MobileDetect Class for better results
	public function isMobile() {

		$exclude_tablet = isset($this->options['mobile_exclude_tablet']) ? $this->options['mobile_exclude_tablet'] : 'false';
		
		if (!isset($this->mobile_detect)) {
			$this->mobile_detect = new ESSB_Mobile_Detect();
		}
		
		//print "mobile = ".$this->mobile_detect->isMobile();;
		$isMobile = $this->mobile_detect->isMobile();
		
		if ($exclude_tablet == 'true' && $this->mobile_detect->isTablet()) {
			$isMobile = false;
		}
		return $isMobile;		
	}					
		
	public function temporary_deactivate_content_filter() {
		if ($this->custom_priority_active) {
			remove_action ( 'the_content', array ($this, 'print_share_links' ), $this->custom_priority, 1 );
		}
		else {
			remove_action ( 'the_content', array ($this, 'print_share_links' ), 10, 1 );
		}
		remove_action ( 'the_excerpt', array ($this, 'print_share_links_excerpt' ), 10, 1 );
	}
	
	public function reactivate_content_filter() {
		if ($this->custom_priority_active) {	
			add_action ( 'the_content', array ($this, 'print_share_links' ), $this->custom_priority, 1 );
		}
		else {
			add_action ( 'the_content', array ($this, 'print_share_links' ), 10, 1 );
		}
	}
	
	/*public function essb_self_docount() {
		global $wpdb, $blog_id;
		
		$post_id = isset($_POST["post_id"]) ? $_POST["post_id"] : '';
		$service_id = isset($_POST["service"]) ? $_POST["service"] : '';
		$post_id = intval($post_id);
		
		//$rows_affected = $wpdb->insert ( ESSB_TABLE_STATS, array ('essb_blog_id' => $blog_id, 'essb_post_id' => $_POST ["post_id"], 'essb_service' => $_POST ["service"] ) );
		
		$current_value = get_post_meta($post_id, 'essb_self_'.$service_id, true);
		$current_value = intval($current_value) + 1;
		update_post_meta ( $post_id, 'essb_self_'.$service_id, $current_value );
		
		sleep ( 1 );
		
		die ( json_encode ( array ("success" => 'Log handled' ) ) );
	}
	
	public function essb_self_docount_code() {
		
		global $post;
				
		$query_object_id = get_queried_object_id();
		
		$result = "
		<script type=\"text/javascript\" language=\"javascript\">
		var essb_docount_data = {
		'ajax_url': '" . admin_url ('admin-ajax.php') . "'
		};
		jQuery(document).bind('essb_selfcount_action', function (e, service) {
		
		jQuery.post(essb_docount_data.ajax_url, {
		'action': 'essb_self_docount',
		'post_id': " . (isset($post) ? $query_object_id : 0) . ",
		'service': service,
		'nonce': '" . wp_create_nonce ( "ajax-nonce" ) . "'
		}, function (data) {
		if (data && data.error) {
		alert(data.error);
		}
		},
		'json'
		);
		});
		function essb_self_docount(service) {
		jQuery(document).trigger('essb_selfcount_action',[service]);
		}
		</script>
		";
		
		print $result;
	}
	*/
	
	public function essb_self_updatepost_count() {
		global $wpdb, $blog_id;
	
		$post_id = isset($_POST["post_id"]) ? $_POST["post_id"] : '';
		$service_id = isset($_POST["service"]) ? $_POST["service"] : '';
		$post_id = intval($post_id);
	
		//$rows_affected = $wpdb->insert ( ESSB_TABLE_STATS, array ('essb_blog_id' => $blog_id, 'essb_post_id' => $_POST ["post_id"], 'essb_service' => $_POST ["service"] ) );
	
		$current_value = get_post_meta($post_id, 'essb_pc_'.$service_id, true);
		$current_value = intval($current_value) + 1;
		update_post_meta ( $post_id, 'essb_pc_'.$service_id, $current_value );
	
		sleep ( 1 );
	
		//die ( json_encode ( array ("success" => 'Log handled' ) ) );
		die(" post_id ".$post_id.", service = ".$service_id.", current_value = ".$current_value);
	}
	
	public function essb_self_updatepost_count_code() {
	
		global $post;
	
		$query_object_id = get_queried_object_id();
	
		$result = "
		var essb_postcount_data = {
		'ajax_url': '" . admin_url ('admin-ajax.php') . "',
		'post_id': '".(isset($post) ? $query_object_id : 0)."'
	};
	jQuery(document).bind('essb_selfpostcount_action', function (e, service, post_id) {		
		post_id = String(post_id);
	jQuery.post(essb_postcount_data.ajax_url, {
	'action': 'essb_self_postcount',
	'post_id': post_id,
	'service': service,
	'nonce': '" . wp_create_nonce ( "ajax-nonce" ) . "'
	}, function (data) { if (data) {
		//alert(data);
	}},'json');});
	function essb_self_postcount(service, post_id) {

	jQuery(document).trigger('essb_selfpostcount_action',[service, post_id]);
	};
	";
	
		//$result = str_replace("\n", "", $result);
		//$result = str_replace("\r", "", $result);
		
		return $result;
	}
		
	public function get_self_share_counts() {
		header('content-type: application/json');

		$json = array('url'=>'','count'=>0);
		$url = $_GET['url'];
		$json['url'] = $url;
		$network = $_GET['nw'];
		
		if (intval($url) > 0) {
			$counter_value =  get_post_meta(intval($url), 'essb_self_'.$network, true);
			$json['count'] = intval($counter_value);
		}
		echo str_replace('\\/','/',json_encode($json));
		
		die();
	}
	
	public function get_share_counts() {
		header('content-type: application/json');
		//admin_ajax_cache
		$options = $this->options;
		$admin_ajax_cache = isset($options['admin_ajax_cache']) ? $options['admin_ajax_cache'] : '';
		$admin_ajax_cache = trim($admin_ajax_cache);
		
		$is_cache_active = false;
		if (intval($admin_ajax_cache) > 0) { $is_cache_active = true; }
		
		$admin_ajax_cache_internal = isset($options['admin_ajax_cache_internal']) ? $options['admin_ajax_cache_internal'] : 'false';
		$admin_ajax_cache_internal_time = isset($options['admin_ajax_cache_internal_time']) ? $options['admin_ajax_cache_internal_time'] : '';
		
		$admin_ajax_cache_internal_time = trim($admin_ajax_cache_internal_time);
		if ($admin_ajax_cache_internal_time == '') { $admin_ajax_cache_internal_time = '600'; }
		$is_internal_cache_active = false;
		if ($admin_ajax_cache_internal == 'true' && intval($admin_ajax_cache_internal_time) > 0) {
			$is_internal_cache_active = true;
		}
		
		$json = array('url'=>'','count'=>0);
		$url = $_GET['url'];
		$json['url'] = $url;
		$network = $_GET['nw'];		
		
		if ( filter_var($url, FILTER_VALIDATE_URL) || ($network == "love" || $network == "print" || $network == "mail")) {
			$transient_key = 'essb_'.$network.'_'.$url;			
			//
			$exist_in_cache = false;
			if ( $network == 'google2' ) {
					
				// http://www.helmutgranda.com/2011/11/01/get-a-url-google-count-via-php/
				$content = $this->parse("https://plusone.google.com/u/0/_/+1/fastbutton?url=".$url."&count=true");
				$dom = new DOMDocument;
				$dom->preserveWhiteSpace = false;
				@$dom->loadHTML($content);
				$domxpath = new DOMXPath($dom);
				$newDom = new DOMDocument;
				$newDom->formatOutput = true;
		
				$filtered = $domxpath->query("//div[@id='aggregateCount']");
		
				if ( isset( $filtered->item(0)->nodeValue ) ) {
					$cars = array("u00c2", "u00a", 'Ã‚Â ', 'Ã‚Â', 'Ã', ',', 'Â', 'Â ');
					$count = str_replace($cars, '', $filtered->item(0)->nodeValue );
					$json['count'] = preg_replace( '#([0-9])#', '$1', $count );
				}
		
			}
		
			elseif ( $network == 'stumble' ) {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
					
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
				
				if (!$exist_in_cache) {
					$content = $this->parse("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=$url");
		
					$result = json_decode($content);
					if ( isset($result->result->views )) {
						$json['count'] = $result->result->views;
						
						if ($is_cache_active) {
							set_transient( $transient_key, $json['count'], $admin_ajax_cache );								
						}
					}					
				}
		
			}
		
			elseif ($network == "google") {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
						
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
				//print "exist in cache = ".$exist_in_cache;
				//print "is_cahc ".$is_cache_active;
				if (!$exist_in_cache) {
					$json['count'] = $this->getGplusShares($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
		
			}
			elseif ($network == "reddit") {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
				
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
				
				if (!$exist_in_cache) {
					$json['count'] = $this->getRedditScore($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
			
			}
			
			elseif ($network == "love") {
				if ($is_internal_cache_active) {
					$transient = get_transient($transient_key);
				
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
				
				if (!$exist_in_cache) {
					$json['count'] = $this->getLoveCount($url);
					
					if ($is_internal_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache_internal_time );
					}
				}
					
			}
			
			elseif ($network == "print" || $network == "mail") {
				if ($is_internal_cache_active) {
					$transient = get_transient($transient_key);
			
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
			
				if (!$exist_in_cache) {
					$json['count'] = $this->getSelfPostCount($url, $network);
						
					if ($is_internal_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache_internal_time );
					}
				}
					
			}			
						
			elseif ($network == "ok") {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
				
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
				
				if (!$exist_in_cache) {
					$json['count'] = $this->get_counter_number_odnoklassniki($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
				
			}
			elseif ($network == 'vk') {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
				
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
				
				if (!$exist_in_cache) {
					$json['count'] = $this->get_counter_number__vk($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
		
			}
			elseif ($network == 'mwp') {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
			
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
			
				if (!$exist_in_cache) {
					$json['count'] = $this->getManagedWPUpVote($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
			
			}
			elseif ($network == 'xing') {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
			
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
			
				if (!$exist_in_cache) {
					$json['count'] = $this->getXingCount($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
			
			}
			elseif ($network == 'pocket') {
				if ($is_cache_active) {
					$transient = get_transient($transient_key);
						
					if ($transient) {
						$json['count'] = $transient;
						$exist_in_cache = true;
					}
				}
					
				if (!$exist_in_cache) {
					$json['count'] = $this->getPocketCount($url);
					if ($is_cache_active) {
						set_transient( $transient_key, $json['count'], $admin_ajax_cache );
					}
				}
					
			}
				
		}
		echo str_replace('\\/','/',json_encode($json));
		
		die();
	}
	
	function getXingCount($url) {
		//- Get Xing Shares counter from this https://www.xing-share.com/app/share?op=get_share_button;url=https://blog.xing.com/2012/01/the-shiny-new-xing-share-button-how-to-implement-it-in-your-blog-or-website/;counter=top;lang=en;type=iframe;hovercard_position=2;shape=rectangle
		$buttonURL = sprintf('https://www.xing-share.com/app/share?op=get_share_button;url=%s;counter=top;lang=en;type=iframe;hovercard_position=2;shape=rectangle', urlencode($url));
		$data  = $this->parse($buttonURL);
		$shares = array();
	
		$count = 0;
		preg_match( '/<span class="xing-count top">(.*?)<\/span>/s', $data, $shares );
	
		if (count($shares) > 0) {
			$current_result = $shares[1];
	
			$count = $current_result;
		}
	
		return $count;
	}
	
	function getPocketCount($url) {
		//- Get Xing Shares counter from this https://www.xing-share.com/app/share?op=get_share_button;url=https://blog.xing.com/2012/01/the-shiny-new-xing-share-button-how-to-implement-it-in-your-blog-or-website/;counter=top;lang=en;type=iframe;hovercard_position=2;shape=rectangle
		$buttonURL = sprintf('https://widgets.getpocket.com/v1/button?align=center&count=vertical&label=pocket&url=%s', urlencode($url));
		$data  = $this->parse($buttonURL);
		$shares = array();
	
		$count = 0;
		preg_match( '/<em id="cnt">(.*?)<\/em>/s', $data, $shares );
	
		if (count($shares) > 0) {
			$current_result = $shares[1];
	
			$count = $current_result;
		}
	
		return $count;
	}
	
	
	function getLoveCount($postID) {
		if (!is_numeric($postID)) { return 0; }
		
		$love_count = get_post_meta($postID, '_essb_love', true);
		
		if( !$love_count ){
			$love_count = 0;
			add_post_meta($postID, '_essb_love', $love_count, true);
		}
		
		return $love_count;
	}
	
	function getSelfPostCount($postID, $service) {
		if (!is_numeric($postID)) {
			return -1;
		}
		
		$current_count = get_post_meta($postID, 'essb_pc_'.$service, true);
		
		$current_count = intval($current_count);
		
		return $current_count;
	}
	
	function getGplusShares($url)
	{
		$buttonUrl = sprintf('https://plusone.google.com/u/0/_/+1/fastbutton?url=%s', urlencode($url));
		//$htmlData  = file_get_contents($buttonUrl);
		$htmlData  = $this->parse($buttonUrl);
	
		@preg_match_all('#{c: (.*?),#si', $htmlData, $matches);
		$ret = isset($matches[1][0]) && strlen($matches[1][0]) > 0 ? trim($matches[1][0]) : 0;
		if(0 != $ret) {
			$ret = str_replace('.0', '', $ret);
		}
	
		return ($ret);
	}
	
	function get_counter_number_odnoklassniki( $url ) {
		$CHECK_URL_PREFIX = 'http://www.odnoklassniki.ru/dk?st.cmd=extLike&uid=odklcnt0&ref=';
	
		$check_url = $CHECK_URL_PREFIX . $url;
	
		$data   = parse( $check_url );
		$shares = array();
	
		preg_match( '/^ODKL\.updateCount\(\'odklcnt0\',\'(\d+)\'\);$/i', $data, $shares );
	
		return (int)$shares[ 1 ];
	}
	
	function get_counter_number__vk( $url ) {
		$CHECK_URL_PREFIX = 'http://vk.com/share.php?act=count&url=';
	
		$check_url = $CHECK_URL_PREFIX . $url;
	
		$data   = $this->parse( $check_url );
		$shares = array();
	
		preg_match( '/^VK\.Share\.count\(\d, (\d+)\);$/i', $data, $shares );
	
		return $shares[ 1 ];
	}
	
	function getManagedWPUpVote($url) {
		$buttonURL = sprintf('https://managewp.org/share/frame/small?url=%s', urlencode($url));
		$data  = $this->parse($buttonURL);
		$shares = array();
	
		$count = 0;
		preg_match( '/<form(.*?)<\/form>/s', $data, $shares );
	
		if (count($shares) > 0) {
			$current_result = $shares[1];
				
			$second_parse = array();
			preg_match( '/<div>(.*?)<\/div>/s', $current_result, $second_parse );
				
			$value = $second_parse[1];
			$value = str_replace("<span>", "", $value);
			$value = str_replace("</span>", "", $value);
				
			$count = $value;
		}
	
		return $count;
	}
	
	function getRedditScore($url) {
		$reddit_url = 'http://www.reddit.com/api/info.json?url='.$url;
		$format = "json";
		$score = $ups = $downs = 0; //initialize
	
		/* action */
		$content = $this->parse( $reddit_url );
		if($content) {
			if($format == 'json') {
				$json = json_decode($content,true);
				foreach($json['data']['children'] as $child) { // we want all children for this example
					$ups+= (int) $child['data']['ups'];
					$downs+= (int) $child['data']['downs'];
					//$score+= (int) $child['data']['score']; //if you just want to grab the score directly
				}
				$score = $ups - $downs;
			}
		}
	
		return $score;
	}
	

	
	function parse( $encUrl ) {
	
		$options = array(
				CURLOPT_RETURNTRANSFER	=> true, 	// return web page
				CURLOPT_HEADER 			=> false, 	// don't return headers
				//CURLOPT_FOLLOWLOCATION	=> true, 	// follow redirects
				CURLOPT_ENCODING	 	=> "", 		// handle all encodings
				CURLOPT_USERAGENT	 	=> 'essb', 	// who am i
				CURLOPT_AUTOREFERER 	=> true, 	// set referer on redirect
				CURLOPT_CONNECTTIMEOUT 	=> 5, 		// timeout on connect
				CURLOPT_TIMEOUT 		=> 10, 		// timeout on response
				CURLOPT_MAXREDIRS 		=> 3, 		// stop after 3 redirects
				CURLOPT_SSL_VERIFYHOST 	=> 0,
				CURLOPT_SSL_VERIFYPEER 	=> false,
		);
		$ch = curl_init();
	
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
			$options[CURLOPT_FOLLOWLOCATION] = true;
		}
				
		$options[CURLOPT_URL] = $encUrl;
		curl_setopt_array($ch, $options);
		// force ip v4 - uncomment this
		try {
			curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		catch (Exception $e) {
			
		}
		
			
		$content	= curl_exec( $ch );
		$err 		= curl_errno( $ch );
		$errmsg 	= curl_error( $ch );
	
		curl_close( $ch );
	
		if ($errmsg != '' || $err != '') {
			print_r($errmsg);
		}
		return $content;
	}
		
	
	function generate_sidebar_shortcode_from_settings($sidebar_counter) {	
		$options = $this->options;	
		$activate_opt_by_bp = isset($options['activate_opt_by_bp']) ? $options['activate_opt_by_bp'] : 'false';
		if ($activate_opt_by_bp == "true") {
			$this->options_by_bp_active = true;
		}
		
		$bp_template_shortcode = '';
		if ($this->options_by_bp_active) {
			$bp_settings = EasySocialShareButtons_Advanced_Display::get_options_by_bp('sidebar');
			$bp_networks = array();
			if ($bp_settings['active']) {
				$bp_networks = isset($bp_settings['networks']) ? $bp_settings['networks'] : array();
				
				// @since 1.3.9.8 - loading different template based on button position
				if (isset($bp_settings['template'])) {
					$bp_template = $bp_settings['template'];
				
					if ($bp_template != '' && $bp_template != $this->loaded_template_slug) {
						$bp_template_shortcode = ' template="'.$bp_template.'"';
							
					}
				}
			}			
		}
		
		$essb_networks = $options['networks'];
		$buttons = "";
		foreach($essb_networks as $k => $v) {
			
			if ($this->options_by_bp_active) {
				if ($bp_settings['active'] && count($bp_networks) > 0) {
					$v[0] = (in_array($k, $bp_networks, true)) ? 1 : 0;
				}
			}
			
			if( $v[0] == 1 ) {
				if ($buttons != '') {
					$buttons .= ",";
				}
				$buttons .= $k;
			}
			
		}
		$hidden_name_class = (isset($options['hide_social_name']) && $options['hide_social_name']==1) ? ' hide_names="yes" ' : '';
		$hidden_name_class = 'hide_names="yes"';
		//$need_counters = $options['show_counter'] ? 0 : 0;
		//print ' $sidebar_counter = ' . $sidebar_counter;
		$need_counters = ($sidebar_counter == 'true') ? 1: 0;
		$sidebar_pos = isset ( $options ['sidebar_pos'] ) ? $options ['sidebar_pos'] : 'left';
		
		//$need_counters = 0;
		$links = ('[easy-share buttons="'.$buttons.'" counters='.$need_counters.' native="no" '.$hidden_name_class.' sidebar="yes" sidebar_pos="'.$sidebar_pos.'" '.$bp_template_shortcode.']');
	
		
		return $links;
	}

	function generate_postfloat_shortcode_from_settings($sidebar_counter) {
		$options = $this->options;
		$activate_opt_by_bp = isset($options['activate_opt_by_bp']) ? $options['activate_opt_by_bp'] : 'false';
		if ($activate_opt_by_bp == "true") {
			$this->options_by_bp_active = true;
		}
		$bp_template_shortcode = '';
		if ($this->options_by_bp_active) {		
			$bp_settings = EasySocialShareButtons_Advanced_Display::get_options_by_bp('postfloat');
			$bp_networks = array();
			if ($bp_settings['active']) {
				$bp_networks = isset($bp_settings['networks']) ? $bp_settings['networks'] : array();
				
				// @since 1.3.9.8 - loading different template based on button position
				if (isset($bp_settings['template'])) {
					$bp_template = $bp_settings['template'];
				
					if ($bp_template != '' && $bp_template != $this->loaded_template_slug) {
						$bp_template_shortcode = ' template="'.$bp_template.'"';
							
					}
				}
			}
		}
		
		$essb_networks = $options['networks'];
		$buttons = "";
		foreach($essb_networks as $k => $v) {
			
			if ($this->options_by_bp_active) {					
				if ($bp_settings['active'] && count($bp_networks) > 0) {
					$v[0] = (in_array($k, $bp_networks, true)) ? 1 : 0;
				}
			}
				
			
			if($v[0] == 1 ) {
				if ($buttons != '') {
					$buttons .= ",";
				}
				$buttons .= $k;
			}
				
		}
		$hidden_name_class = (isset($options['hide_social_name']) && $options['hide_social_name']==1) ? ' hide_names="yes" ' : '';
		$hidden_name_class = 'hide_names="yes"';
		//$need_counters = $options['show_counter'] ? 0 : 0;
		//print ' $sidebar_counter = ' . $sidebar_counter;
		$need_counters = ($sidebar_counter == 'true') ? 1: 0;
	
		//$need_counters = 0;
		$links = ('[easy-share buttons="'.$buttons.'" counters='.$need_counters.' native="no" '.$hidden_name_class.' postfloat="yes" '.$bp_template_shortcode.']');
	
		return $links;
	}
	
	
	function render_sidebar_code($sidebar_counter) {
		$shortcode = $this->generate_sidebar_shortcode_from_settings($sidebar_counter);

		return do_shortcode($shortcode);
	}
	
	function render_postfloat_code($sidebar_counter) {
		$shortcode = $this->generate_postfloat_shortcode_from_settings($sidebar_counter);
	
		return do_shortcode($shortcode);
	}
	
	
	function generate_popup_shortcode_from_settings() {		
		$options = $this->options;
		$activate_opt_by_bp = isset($options['activate_opt_by_bp']) ? $options['activate_opt_by_bp'] : 'false';
		if ($activate_opt_by_bp == "true") {
			$this->options_by_bp_active = true;
		}
		$bp_template_shortcode = '';
		if ($this->options_by_bp_active) {		
			$bp_settings = EasySocialShareButtons_Advanced_Display::get_options_by_bp('popup');
			$bp_networks = array();
			if ($bp_settings['active']) {
				$bp_networks = isset($bp_settings['networks']) ? $bp_settings['networks'] : array();
				
				// @since 1.3.9.8 - loading different template based on button position
				if (isset($bp_settings['template'])) {
					$bp_template = $bp_settings['template'];
				
					if ($bp_template != '' && $bp_template != $this->loaded_template_slug) {
						$bp_template_shortcode = ' template="'.$bp_template.'"';
							
					}
				}
			}
		}
		$essb_networks = $options['networks'];
		$buttons = "";
		foreach($essb_networks as $k => $v) {
			if ($this->options_by_bp_active) {
					
				if ($bp_settings['active'] && count($bp_networks) > 0) {
					$v[0] = (in_array($k, $bp_networks, true)) ? 1 : 0;
				}
			}
			
			if( $v[0] == 1 ) {
				if ($buttons != '') {
					$buttons .= ",";
				}
				$buttons .= $k;
			}
			
		}
		$hidden_name_class = (isset($options['hide_social_name']) && $options['hide_social_name']==1) ? ' hide_names="yes" ' : '';

		$need_counters = $options['show_counter'] ? 0 : 0;
		$popafter = isset ( $options ['popup_window_popafter'] ) ? $options ['popup_window_popafter'] : '';
		
		if ($popafter != '') {
			$popafter = ' popafter="'.$popafter.'"';
		}
	
		//$need_counters = 0;
		$links = ('[easy-share buttons="'.$buttons.'" counters='.$need_counters.' '.$hidden_name_class.' popup="yes" '.$popafter.' '.$bp_template_shortcode.']');
	
		return $links;
	}
	
	function render_popup_code() {
		$shortcode = $this->generate_popup_shortcode_from_settings();
		//print $shortcode;
		return do_shortcode($shortcode);
	}
	
	
	function set_options_by_bp_activate_state() {		
		$this->options_by_bp_active = EasySocialShareButtons_Advanced_Display::get_options_by_bp_activate_state();
	}
	
	
}

class EasySocialShareButtons_AdminMenu {
	function EasySocialShareButtons_AdminMenu() {
		// @since 1.2.0
		add_action ( 'admin_bar_menu', array ($this, "attach_admin_barmenu" ), 89 );
	}
	
	public function attach_admin_barmenu() {
		$this->add_root_menu ( "Easy Social Share Buttons", "essb", get_admin_url () . 'index.php?page=essb_settings&tab=general' );
		$this->add_sub_menu ( "Main Settings", get_admin_url () . 'index.php?page=essb_settings&tab=general', "essb", "essb_p1" );
		$this->add_sub_menu ( "Display Settings", get_admin_url () . 'index.php?page=essb_settings&tab=display', "essb", "essb_p2" );
		$this->add_sub_menu ( "Style Settings", get_admin_url () . 'index.php?page=essb_settings&tab=customizer', "essb", "essb_p4" );
		$this->add_sub_menu ( "Shortcode Generator", get_admin_url () . 'index.php?page=essb_settings&tab=shortcode', "essb", "essb_p3" );
		$this->add_sub_menu ( "Need Help?", 'http://support.creoworx.com/', "essb", "essb_p6" );
	}
	
	function add_root_menu($name, $id, $href = FALSE) {
		global $wp_admin_bar;
		if (! is_super_admin () || ! is_admin_bar_showing ())
			return;
		
		$wp_admin_bar->add_menu ( array ('id' => $id, 'meta' => array (), 'title' => $name, 'href' => $href ) );
	}
	
	function add_sub_menu($name, $link, $root_menu, $id, $meta = FALSE) {
		global $wp_admin_bar;
		if (! is_super_admin () || ! is_admin_bar_showing ())
			return;
		
		$wp_admin_bar->add_menu ( array ('parent' => $root_menu, 'id' => $id, 'title' => $name, 'href' => $link, 'meta' => $meta ) );
	}

}


function essb_base64url_encode($data) {
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function essb_base64url_decode($data) {
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

?>