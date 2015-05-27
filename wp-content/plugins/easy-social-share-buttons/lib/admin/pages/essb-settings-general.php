<?php

$autoactivate = isset($_REQUEST['autoactivate']) ? $_REQUEST['autoactivate'] : '';

if ($autoactivate != '') {
	switch ($autoactivate) {
		case "ga" :
			$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
			$current_options['using_yoast_ga'] = 'true';
			update_option(EasySocialShareButtons::$plugin_settings_name, $current_options);
			break;
		case "native-counters-dismiss":
			$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
			$current_options['native_social_counters'] = 'false';
			$current_options['native_social_counters_fb'] = 'false';
			$current_options['native_social_counters_g'] = 'false';
			$current_options['native_social_counters_t'] = 'false';
			$current_options['native_social_counters_boxes'] = 'false';
			$current_options['native_social_counters_youtube'] = 'false';
			update_option(EasySocialShareButtons::$plugin_settings_name, $current_options);
			break;
		case  "native-counters":
			$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
			$current_options['allnative_counters'] = 'true';
			update_option(EasySocialShareButtons::$plugin_settings_name, $current_options);
			break;		 
	}
	
	echo '<script type="text/javascript">window.location="admin.php?page=essb_settings&tab=general";</script>';
}

$is_easy_mode = get_option ( 'essb-easy-mode' );
$active_easy_mode = ($is_easy_mode == "true") ? true : false;

$mode_option = isset ( $_GET ["easy-mode"] ) ? $_GET ["easy-mode"] : "";
if ($mode_option != '') {
	update_option('essb-easy-mode', $mode_option);

	if ($mode_option == 'true') {
		$active_easy_mode = true;
	}
	else {
		$active_easy_mode = false;
	}
}

$easy_mode_address = add_query_arg ( 'easy-mode', 'true', 'admin.php?page=essb_settings&tab=general' );
$easy_mode_text = "Turn on Easy Mode";

if ($active_easy_mode) {
	$easy_mode_address = add_query_arg ( 'easy-mode', 'false', 'admin.php?page=essb_settings&tab=general' );
	$easy_mode_text = "Turn off Easy Mode";
}

$msg = "";

// reset settings
$reset_settings = isset ( $_GET ["reset"] ) ? $_GET ["reset"] : "";
if ($reset_settings == "true") {
	delete_option ( EasySocialShareButtons::$plugin_settings_name );
	update_option ( EasySocialShareButtons::$plugin_settings_name, EasySocialShareButtons::default_options () );
	delete_option('essb-welcome-deactivated');	

	inject_new_network_to_options_set("twitter", "Twitter");
	inject_new_network_to_options_set("facebook", "Facebook");
	inject_new_network_to_options_set("google", "Google+");
	inject_new_network_to_options_set("pinterest", "Pinterest");
	inject_new_network_to_options_set("linkedin", "LinkedIn");
	inject_new_network_to_options_set("digg", "Digg");
	inject_new_network_to_options_set("del", "Del");
	inject_new_network_to_options_set("tumblr", "Tumblr");
	inject_new_network_to_options_set("vk", "Vkontakte");
	inject_new_network_to_options_set("print", "Print");
	inject_new_network_to_options_set("mail", "Email");
	
	if (! inject_new_network_to_options_set ( "flattr", "Flattr" )) {
		$msg = "Injected new network to options: Flattr.";
	}
	if (! inject_new_network_to_options_set ( "reddit", "Reddit" )) {
		$msg = "Injected new network to options";
	}
	if (! inject_new_network_to_options_set ( "del", "Delicious" )) {
		$msg = "Injected new network to options";
	}
	if (! inject_new_network_to_options_set ( "buffer", "Buffer" )) {
		$msg = "Injected new network to options";
	}
	if (! inject_new_network_to_options_set ( "love", "Love This" )) {
		$msg = "Injected new network to options";
	}
	
	if (! inject_new_network_to_options_set ( "weibo", "Weibo" )) {
		$msg = "Injected new network to options: Weibo";
	}
	
	if (! inject_new_network_to_options_set ( "pocket", "Pocket" )) {
		$msg .= "Injected new network to options: Pocket";
	}
	
	if (! inject_new_network_to_options_set ( "xing", "Xing" )) {
		$msg .= "Injected new network to options: Xing";
	}
	
	if (! inject_new_network_to_options_set ( "ok", "Odnoklassniki" )) {
		$msg .= "Injected new network to options: Odnoklassniki";
	}
	
	if (! inject_new_network_to_options_set ( "mwp", "ManageWP.org" )) {
		$msg = "Injected new network to options: ManageWP.org";
	}
	if (! inject_new_network_to_options_set ( "more", "More Button" )) {
		$msg = "Injected new network to options: More Button ";
	}
	if (! inject_new_network_to_options_set ( "whatsapp", "WhatsApp" )) {
		$msg = "Injected new network to options: WhatsApp ";
	}
	if (! inject_new_network_to_options_set ( "meneame", "Meneame" )) {
		$msg = "Injected new network to options: Meneame ";
	}
	
}

global $essb_fans;

function inject_new_network_to_options_set($key, $name) {
	
	$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	$exist = false;
	
	foreach ( $current_options ['networks'] as $nw => $v ) {
		
		if ($nw == $key) {
			$exist = true;
		}
	}
	
	if (! $exist) {
		$current_options ['networks'] [$key] = array (0, $name );
		update_option ( EasySocialShareButtons::$plugin_settings_name, $current_options );
	}
	
	return $exist;
}

$cmd = isset ( $_POST ['cmd'] ) ? $_POST ['cmd'] : '';

// inject_new_network_to_options_set("twitter", "Twitter");
// inject_new_network_to_options_set("facebook", "Facebook");
// inject_new_network_to_options_set("google", "Google+");
// inject_new_network_to_options_set("pinterest", "Pinterest");
// inject_new_network_to_options_set("linkedin", "LinkedIn");
// inject_new_network_to_options_set("digg", "Digg");
// inject_new_network_to_options_set("del", "Del");
// inject_new_network_to_options_set("tumblr", "Tumblr");
// inject_new_network_to_options_set("vk", "Vkontakte");
// inject_new_network_to_options_set("print", "Print");
// inject_new_network_to_options_set("mail", "Email");

if (! inject_new_network_to_options_set ( "flattr", "Flattr" )) {
	$msg = "Injected new network to options: Flattr.";
}
if (! inject_new_network_to_options_set ( "reddit", "Reddit" )) {
	$msg = "Injected new network to options";
}
if (! inject_new_network_to_options_set ( "del", "Delicious" )) {
	$msg = "Injected new network to options";
}
if (! inject_new_network_to_options_set ( "buffer", "Buffer" )) {
	$msg = "Injected new network to options";
}
if (! inject_new_network_to_options_set ( "love", "Love This" )) {
	$msg = "Injected new network to options";
}

if (! inject_new_network_to_options_set ( "weibo", "Weibo" )) {
	$msg = "Injected new network to options: Weibo";
}

if (! inject_new_network_to_options_set ( "pocket", "Pocket" )) {
	$msg .= "Injected new network to options: Pocket";
}

if (! inject_new_network_to_options_set ( "xing", "Xing" )) {
	$msg .= "Injected new network to options: Xing";
}

if (! inject_new_network_to_options_set ( "ok", "Odnoklassniki" )) {
	$msg .= "Injected new network to options: Odnoklassniki";
}

if (! inject_new_network_to_options_set ( "mwp", "ManageWP.org" )) {
	$msg = "Injected new network to options: ManageWP.org ";
}

if (! inject_new_network_to_options_set ( "more", "More Button" )) {
	$msg = "Injected new network to options: More Button ";
}
if (! inject_new_network_to_options_set ( "whatsapp", "WhatsApp" )) {
	$msg = "Injected new network to options: WhatsApp ";
}
if (! inject_new_network_to_options_set ( "meneame", "Meneame" )) {
	$msg = "Injected new network to options: Meneame ";
}

// check
$option = get_option ( EasySocialShareButtons::$plugin_settings_name );
if (! $option || empty ( $option )) {
	update_option ( EasySocialShareButtons::$plugin_settings_name, EasySocialShareButtons::default_options () );
}

if ($cmd == "update") {
	$options = $_POST ['general_options'];
	
	$as = $_POST ['general_options_as'];
	
	$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	// to resort
	// print_r($current_options ['networks']);
	
	foreach ( $current_options ['networks'] as $nw => $v ) {
		// print_r($current_options ['networks'] [$nw] );
		$current_options ['networks'] [$nw] [0] = 0;
	}
	
	$new_networks = array ();
	
	foreach ( $options ['sort'] as $nw ) {
		$new_networks [$nw] = $current_options ['networks'] [$nw];
	}
	
	$current_options ['networks'] = $new_networks;
	
	foreach ( $options ['networks'] as $nw ) {
		$current_options ['networks'] [$nw] [0] = 1;
	}
	
	if (! isset ( $options ['facebook_like_button'] )) {
		$options ['facebook_like_button'] = 'false';
	}
	if (! isset ( $options ['facebook_like_button_api'] )) {
		$options ['facebook_like_button_api'] = 'false';
	}
	
	if (! isset ( $options ['googleplus'] )) {
		$options ['googleplus'] = 'false';
	}
	
	if (! isset ( $options ['vklike'] )) {
		$options ['vklike'] = 'false';
	}
	if (! isset ( $options ['vklikeappid'] )) {
		$options ['vklikeappid'] = '';
	}
	
	// @since 1.0.5
	if (! isset ( $options ['customshare'] )) {
		$options ['customshare'] = 'false';
	}
	if (! isset ( $options ['customshare_text'] )) {
		$options ['customshare_text'] = '';
	}
	if (! isset ( $options ['customshare_url'] )) {
		$options ['customshare_url'] = '';
	}
	
	if (! isset ( $options ['customshare_imageurl'] )) {
		$options ['customshare_imageurl'] = '';
	}
	
	if (! isset ( $options ['customshare_description'] )) {
		$options ['customshare_description'] = '';
	}
	
	if (! isset ( $options ['pinterest_sniff_disable'] )) {
		$options ['pinterest_sniff_disable'] = 'false';
	}
	
	if (! isset ( $options ['mail_copyaddress'] )) {
		$options ['mail_copyaddress'] = '';
	}
	
	if (! isset ( $options ['otherbuttons_sameline'] )) {
		$options ['otherbuttons_sameline'] = 'false;';
	}
	
	if (! isset ( $options ['twitterfollow'] )) {
		$options ['twitterfollow'] = 'false';
	}
	if (! isset ( $options ['twitterfollowuser'] )) {
		$options ['twitterfollowuser'] = '';
	}
	
	if (! isset ( $options ['url_short_native'] )) {
		$options ['url_short_native'] = 'false';
	}
	
	if (! isset ( $options ['url_short_google'] )) {
		$options ['url_short_google'] = 'false';
	}
	
	if (! isset ( $options ['twitteruser'] )) {
		$options ['twitteruser'] = '';
	}
	
	if (! isset ( $options ['twitterhashtags'] )) {
		$options ['twitterhashtags'] = '';
	}
	
	if (! isset ( $options ['twitter_nojspop'] )) {
		$options ['twitter_nojspop'] = 'false';
	}
	
	if (! isset ( $options ['facebooksimple'] )) {
		$options ['facebooksimple'] = 'false';
	}
	
	if (! isset ( $options ['facebooktotal'] )) {
		$options ['facebooktotal'] = 'false';
	}
	
	if (! isset ( $options ['facebookhashtags'] )) {
		$options ['facebookhashtags'] = "";
	}
	
	if (! isset ( $options ['stats_active'] )) {
		$options ['stats_active'] = 'false';
	}
	
	if (! isset ( $options ['opengraph_tags'] )) {
		$options ['opengraph_tags'] = 'false';
	}
	
	if (! isset ( $options ['disable_adminbar_menu'] )) {
		$options ['disable_adminbar_menu'] = 'false';
	}
	if (! isset ( $options ['register_menu_under_settings'] )) {
		$options ['register_menu_under_settings'] = 'false';
	}
	
	if (! isset ( $options ['twitter_shareshort'] )) {
		$options ['twitter_shareshort'] = 'false';
	}
	
	// @since 1.1.4
	if (! isset ( $options ['custom_url_like'] )) {
		$options ['custom_url_like'] = 'false';
	}
	if (! isset ( $options ['custom_url_like_address'] )) {
		$options ['custom_url_like_address'] = '';
	}
	if (! isset ( $options ['custom_url_plusone_address'] )) {
		$options ['custom_url_plusone_address'] = '';
	}
	
	// @since 1.2.3
	if (! isset ( $options ['youtubechannel'] )) {
		$options ['youtubechannel'] = '';
	}
	if (! isset ( $options ['youtubesub'] )) {
		$options ['youtubesub'] = 'false';
	}
	
	if (! isset ( $options ['pinterestfollow'] )) {
		$options ['pinterestfollow'] = "false";
	}
	if (! isset ( $options ['pinterestfollow_disp'] )) {
		$options ['pinterestfollow_disp'] = "";
	}
	if (! isset ( $options ['pinterestfollow_url'] )) {
		$options ['pinterestfollow_url'] = "";
	}
	
	if (! isset ( $options ['facebookadvanced'] )) {
		$options ['facebookadvanced'] = 'false';
	}
	if (! isset ( $options ['facebookadvancedappid'] )) {
		$options ['facebookadvancedappid'] = '';
	}
	
	if (! isset ( $options ['buttons_pos'] )) {
		$option ['buttons_pos'] = '';
	}
	
	if (! isset ( $options ['using_yoast_ga'] )) {
		$options ['using_yoast_ga'] = "false";
	}
	
	if (! isset ( $options ['url_short_bitly'] )) {
		$options ['url_short_bitly'] = 'false';
	}
	if (! isset ( $options ['url_short_bitly_user'] )) {
		$options ['url_short_bitly_user'] = "";
	}
	if (! isset ( $options ['url_short_bitly_api'] )) {
		$options ['url_short_bitly_api'] = "";
	}
	if (! isset ( $options ['twitter_card'] )) {
		$options ['twitter_card'] = 'false';
	}
	if (! isset ( $options ['twitter_card_user'] )) {
		$options ['twitter_card_user'] = '';
	}
	if (! isset ( $options ['twitter_card_type'] )) {
		$options ['twitter_card_type'] = '';
	}
	
	if (! isset ( $options ['fullwidth_share_buttons'] )) {
		$options ['fullwidth_share_buttons'] = 'false';
	}
	
	if (! isset ( $options ['fullwidth_share_buttons_correction'] )) {
		$options ['fullwidth_share_buttons_correction'] = "";
	}
	
	if (! isset ( $options ['opengraph_tags_fbpage'] )) {
		$options ['opengraph_tags_fbpage'] = "";
	}
	
	if (! isset ( $options ['opengraph_tags_fbadmins'] )) {
		$options ['opengraph_tags_fbadmins'] = "";
	}
	if (! isset ( $options ['opengraph_tags_fbapp'] )) {
		$options ['opengraph_tags_fbapp'] = "";
	}
	if (! isset ( $options ['sso_default_image'] )) {
		$options ['sso_default_image'] = "";
	}
	
	if (! isset ( $options ['translate_mail_title'] )) {
		$options ['translate_mail_title'] = "";
	}
	if (! isset ( $options ['translate_mail_email'] )) {
		$options ['translate_mail_email'] = "";
	}
	if (! isset ( $options ['translate_mail_recipient'] )) {
		$options ['translate_mail_recipient'] = "";
	}
	if (! isset ( $options ['translate_mail_subject'] )) {
		$options ['translate_mail_subject'] = "";
	}
	if (! isset ( $options ['translate_mail_message'] )) {
		$options ['translate_mail_message'] = "";
	}
	if (! isset ( $options ['translate_mail_cancel'] )) {
		$options ['translate_mail_cancel'] = "";
	}
	if (! isset ( $options ['translate_mail_send'] )) {
		$options ['translate_mail_send'] = "";
	}
	if (! isset ( $options ['facebook_like_button_width'] )) {
		$options ['facebook_like_button_width'] = "";
	}
	if (! isset ( $options ['use_minified_css'] )) {
		$options ['use_minified_css'] = 'false';
	}
	if (! isset ( $options ['use_minified_js'] )) {
		$options ['use_minified_js'] = 'false';
	}
	if (!isset($options['mail_captcha_answer'])) {
		$options['mail_captcha_answer'] = '';
	}
	
	if (!isset($options['mail_captcha'])) {
		$options['mail_captcha'] = '';
	}
	
	if (!isset($options['flattr_username'])) {
		$options['flattr_username'] = "";
	}
	if (!isset($options['flattr_tags'])) {
		$options['flattr_tags'] = "";
	}
	if (!isset($options['flattr_cat'])) {
		$options['flattr_cat'] = "";
	}
	if (!isset($options['flattr_lang'])) {
		$options['flattr_lang'] = "";
	}
	if (!isset($options['managedwp_button'])) {
		$options['managedwp_button'] = 'false';
	}
	if (!isset($options['skin_native'])) {
		$options['skin_native'] = 'false';
	}
	if (!isset($options['skin_native_skin'])) {
		$options['skin_native_skin'] = '';
	}
	
	if (!isset($options['skinned_fb_color'])) {
		$options['skinned_fb_color'] = '';
	}
	if (!isset($options['skinned_fb_width'])) {
		$options['skinned_fb_width'] = '';
	}
	if (!isset($options['skinned_fb_text'])) {
		$options['skinned_fb_text'] = '';
	}
	if (!isset($options['skinned_vk_color'])) {
		$options['skinned_vk_color'] = '';
	}
	if (!isset($options['skinned_vk_width'])) {
		$options['skinned_vk_width'] = '';
	}
	if (!isset($options['skinned_vk_text'])) {
		$options['skinned_vk_text'] = '';
	}
	if (!isset($options['skinned_google_color'])) {
		$options['skinned_google_color'] = '';
	}
	if (!isset($options['skinned_google_width'])) {
		$options['skinned_google_width'] = '';
	}
	if (!isset($options['skinned_google_text'])) {
		$options['skinned_google_text'] = '';
	}
	if (!isset($options['skinned_twitter_color'])) {
		$options['skinned_twitter_color'] = '';
	}
	if (!isset($options['skinned_twitter_width'])) {
		$options['skinned_twitter_width'] = '';
	}
	if (!isset($options['skinned_twitter_text'])) {
		$options['skinned_twitter_text'] = '';
	}
	if (!isset($options['skinned_pinterest_color'])) {
		$options['skinned_pinterest_color'] = '';
	}
	if (!isset($options['skinned_pinterest_width'])) {
		$options['skinned_pinterest_width'] = '';
	}
	if (!isset($options['skinned_pinterest_text'])) {
		$options['skinned_pinterest_text'] = '';
	}
	if (!isset($options['skinned_youtube_color'])) {
		$options['skinned_youtube_color'] = '';
	}
	if (!isset($options['skinned_youtube_width'])) {
		$options['skinned_youtube_width'] = '';
	}
	if (!isset($options['skinned_youtube_text'])) {
		$options['skinned_youtube_text'] = '';
	}
	
	if (!isset($options['skinned_fb_hovercolor'])) {
		$options['skinned_fb_hovercolor'] = '';
	}
	if (!isset($options['skinned_fb_textcolor'])) {
		$options['skinned_fb_textcolor'] = '';
	}
	if (!isset($options['skinned_vk_hovercolor'])) {
		$options['skinned_vk_hovercolor'] = '';
	}
	if (!isset($options['skinned_fb_textcolor'])) {
		$options['skinned_fb_textcolor'] = '';
	}
	if (!isset($options['skinned_google_hovercolor'])) {
		$options['skinned_fb_hovercolor'] = '';
	}
	if (!isset($options['skinned_google_textcolor'])) {
		$options['skinned_fb_textcolor'] = '';
	}
	if (!isset($options['skinned_twitter_hovercolor'])) {
		$options['skinned_fb_hovercolor'] = '';
	}
	if (!isset($options['skinned_twitter_textcolor'])) {
		$options['skinned_fb_textcolor'] = '';
	}
	if (!isset($options['skinned_pinterest_hovercolor'])) {
		$options['skinned_fb_hovercolor'] = '';
	}
	if (!isset($options['skinned_pinterest_textcolor'])) {
		$options['skinned_fb_textcolor'] = '';
	}
	if (!isset($options['skinned_youtube_hovercolor'])) {
		$options['skinned_fb_hovercolor'] = '';
	}
	if (!isset($options['skinned_youtube_textcolor'])) {
		$options['skinned_fb_textcolor'] = '';
	}
	
	if (!isset($options['twitter_tweet'])) {
		$options['twitter_tweet'] = '';
	}
	if (!isset($options['pinterest_native_type'])) {
		$options['pinterest_native_type'] = '';
	}
	if (!isset($options['use_wpmandrill'])) {
		$options['use_wpmandrill'] = 'false';
	}
	if (!isset($options['scripts_in_head'])) {
		$options['scripts_in_head'] = 'false';
	}
	if (!isset($options['twitter_shareshort_service'])) {
		$options['twitter_shareshort_service'] = '';
	}
	
	if (!isset($options['translate_mail_message_sent'])) {
		$options['translate_mail_message_sent'] = '';
	}
	if (!isset($options['translate_mail_message_invalid_captcha'])) {
		$options['translate_mail_message_invalid_captcha'] = '';
	}
	if (!isset($options['translate_mail_message_error_send'])) {
		$options['translate_mail_message_error_send'] = '';
	}
	
	if (!isset($options['fixed_width_active'])){
		$options['fixed_width_active'] = 'false';
	}
	if (!isset($options['fixed_width_value'])) {
		$options['fixed_width_value'] = '';
	}
	if (!isset($options['sso_apply_the_content'])) {
		$options['sso_apply_the_content'] = 'false';
	}
	if (!isset($options['facebook_like_button_height'])) {
		$options['facebook_like_button_height'] = '';
	}
	if (!isset($options['facebook_like_button_margin_top'])) {
		$options['facebook_like_button_margin_top'] = '';
	}
	
	if (!isset($options['module_off_sfc'])) {
		$options['module_off_sfc'] = 'false';
	}
	if (!isset($options['module_off_lv'])) {
		$options['module_off_lv'] = 'false';
	}
	
	if (!isset($options['load_js_async'])) {
		$options['load_js_async'] = 'false';
	}
	
	if (!isset($options['encode_url_nonlatin'])) {
		$options['encode_url_nonlatin'] = 'false';
	}
	if (!isset($options['stumble_noshortlink'])) {
		$options['stumble_noshortlink'] = 'false';
	}
	if (!isset($options['turnoff_essb_advanced_box'])) {
		$options['turnoff_essb_advanced_box'] = 'false';
	}
	
	if (!isset($options['esml_ttl'])) {
		$options['esml_ttl'] = '1';
	}
	if (!isset($options['esml_active'])) {
		$options['esml_active'] = 'false';		
	}
	if (!isset($options['esml_monitor_types'])) {
		$options['esml_monitor_types'] = array();
	}
	if (!isset($options['avoid_nextpage'])) {
		$options['avoid_nextpage'] = 'false';
	}
	if (!isset($options['apply_clean_buttons'])) {
		$options['apply_clean_buttons'] = 'false';
	}
	if (!isset($options['force_wp_query_postid'])) {
		$options['force_wp_query_postid'] = 'false';
	}
	
	if (!isset($options['print_use_printfriendly'])) {
		$options['print_use_printfriendly'] = 'false';
	}
	if (!isset($options['twitter_always_count_full'])) {
		$options['twitter_always_count_full'] = 'false';
	}
	if (!isset($options['more_button_func'])) {
		$options['more_button_func'] = '';
	}
	if (!isset($options['fullwidth_share_buttons_correction_mobile'])) {
		$options['fullwidth_share_buttons_correction_mobile'] = '';
	}
	if (!isset($options['mail_funcion'])) {
		$options['mail_funcion'] = '';
	}
	
	if (!isset($options['priority_of_buttons'])) {
		$options['priority_of_buttons'] = '';
	}
	if (!isset($options['activate_ga_tracking'])) {
		$options['activate_ga_tracking'] = 'false';
	}
	if (!isset($options['ga_tracking_mode'])) {
		$options['ga_tracking_mode'] = '';
	}
	if (!isset($options['facebook_like_type'])) {
		$options['facebook_like_type'] = '';
	}
	if (!isset($options['facebook_follow_profile'])) {
		$options['facebook_follow_profile'] = '';
	}
	if (!isset($options['google_like_type'])) {
		$options['google_like_type'] = '';
	}
	if (!isset($options['google_follow_profile'])) {
		$options['google_follow_profile'] = '';
	}
	if (!isset($options['allnative_counters'])) {
		$options['allnative_counters'] = 'false';
	}
	if (!isset($options['linkedin_follow'])) {
		$options['linkedin_follow'] = 'false';
	}
	if (!isset($options['linkedin_follow_id'])) {
		$options['linkedin_follow_id'] = '';
	}
	if (!isset($options['skinned_linkedin_color'])) {
		$options['skinned_linkedin_color'] = '';		
	}
	if (!isset($options['skinned_linkedin_hovercolor'])) {
		$options['skinned_linkedin_hovercolor'] = '';
	}
	if (!isset($options['skinned_linkedin_textcolor'])) { 
		$options['skinned_linkedin_textcolor'] = '';
	}
	if (!isset($options['skinned_linkedin_width'])) {
		$options['skinned_linkedin_width'] = '';
	}
	if (!isset($options['skinned_linkedin_text'])) {
		$options['skinned_linkedin_text'] = '';
	}
	if (!isset($options['sso_google_author'])) {
		$options['sso_google_author'] = 'false';
	}
	if (!isset($options['ss_google_author_profile'])) {
		$options['ss_google_author_profile'] = '';
	}
	if (!isset($options['ss_google_author_publisher'])) {
		$options['ss_google_author_publisher'] = '';		
	}
	if (!isset($options['sso_google_markup'])) {
		$options['sso_google_markup'] = 'false';
	}
	if (!isset($options['facebook_like_button_api_async'])) {
		$options['facebook_like_button_api_async'] = 'false';
	}
	if (!isset($options['remove_ver_resource'])) {
		$options['remove_ver_resource'] = 'false';
	}
	
	if (!isset($options['mail_shorturl'])) {
		$options['mail_shorturl'] = 'false';
	}
	
	$current_options ['style'] = $options ['style'];
	$current_options ['mail_subject'] = sanitize_text_field ( $options ['mail_subject'] );
	$current_options ['mail_body'] = ($options ['mail_body']);
	
	$current_options ['facebook_like_button'] = $options ['facebook_like_button'];
	$current_options ['facebook_like_button_api'] = $options ['facebook_like_button_api'];
	
	$current_options ['googleplus'] = $options ['googleplus'];
	
	$current_options ['vklike'] = $options ['vklike'];
	$current_options ['vklikeappid'] = $options ['vklikeappid'];
	
	$current_options ['customshare'] = $options ['customshare'];
	$current_options ['customshare_url'] = $options ['customshare_url'];
	$current_options ['customshare_text'] = $options ['customshare_text'];
	
	$current_options ['customshare_imageurl'] = $options ['customshare_imageurl'];
	$current_options ['customshare_description'] = $options ['customshare_description'];
	
	$current_options ['pinterest_sniff_disable'] = $options ['pinterest_sniff_disable'];
	
	// @since 1.1
	$current_options ['mail_copyaddress'] = $options ['mail_copyaddress'];
	
	// @since 1.1.1
	$current_options ['otherbuttons_sameline'] = $options ['otherbuttons_sameline'];
	$current_options ['twitterfollow'] = $options ['twitterfollow'];
	$current_options ['twitterfollowuser'] = $options ['twitterfollowuser'];
	
	$current_options ['url_short_native'] = $options ['url_short_native'];
	$current_options ['url_short_google'] = $options ['url_short_google'];
	
	// @since 1.1.4
	$current_options ['custom_url_like'] = $options ['custom_url_like'];
	$current_options ['custom_url_like_address'] = $options ['custom_url_like_address'];
	
	$current_options ['twitteruser'] = $options ['twitteruser'];
	$current_options ['twitterhashtags'] = $options ['twitterhashtags'];
	$current_options ['twitter_nojspop'] = $options ['twitter_nojspop'];
	$current_options ['custom_url_plusone_address'] = $options ['custom_url_plusone_address'];
	
	// @since 1.2.3
	$current_options ['youtubesub'] = $options ['youtubesub'];
	$current_options ['youtubechannel'] = $options ['youtubechannel'];
	
	$current_options ['pinterestfollow_url'] = $options ['pinterestfollow_url'];
	$current_options ['pinterestfollow_disp'] = $options ['pinterestfollow_disp'];
	$current_options ['pinterestfollow'] = $options ['pinterestfollow'];
	
	$current_options ['facebooksimple'] = $options ['facebooksimple'];
	$current_options ['facebooktotal'] = $options ['facebooktotal'];
	$current_options ['facebookhashtags'] = $options ['facebookhashtags'];
	$current_options ['stats_active'] = $options ['stats_active'];
	$current_options ['opengraph_tags'] = $options ['opengraph_tags'];
	$current_options ['facebookadvanced'] = $options ['facebookadvanced'];
	$current_options ['facebookadvancedappid'] = $options ['facebookadvancedappid'];
	$current_options ['buttons_pos'] = $options ['buttons_pos'];
	$current_options ['disable_adminbar_menu'] = $options ['disable_adminbar_menu'];
	$current_options ['register_menu_under_settings'] = $options ['register_menu_under_settings'];
	$current_options ['twitter_shareshort'] = $options ['twitter_shareshort'];
	$current_options ['using_yoast_ga'] = $options ['using_yoast_ga'];
	
	$current_options ['url_short_bitly'] = $options ['url_short_bitly'];
	$current_options ['url_short_bitly_user'] = $options ['url_short_bitly_user'];
	$current_options ['url_short_bitly_api'] = $options ['url_short_bitly_api'];
	
	$current_options ['twitter_card'] = $options ['twitter_card'];
	$current_options ['twitter_card_user'] = $options ['twitter_card_user'];
	$current_options ['twitter_card_type'] = $options ['twitter_card_type'];
	
	$current_options ['fullwidth_share_buttons'] = $options ['fullwidth_share_buttons'];
	$current_options ['fullwidth_share_buttons_correction'] = $options ['fullwidth_share_buttons_correction'];
	
	$current_options ['opengraph_tags_fbpage'] = $options ['opengraph_tags_fbpage'];
	$current_options ['opengraph_tags_fbadmins'] = $options ['opengraph_tags_fbadmins'];
	$current_options ['opengraph_tags_fbapp'] = $options ['opengraph_tags_fbapp'];
	$current_options ['sso_default_image'] = $options ['sso_default_image'];
	
	$current_options ['translate_mail_title'] = $options ['translate_mail_title'];
	$current_options ['translate_mail_email'] = $options ['translate_mail_email'];
	$current_options ['translate_mail_recipient'] = $options ['translate_mail_recipient'];
	$current_options ['translate_mail_subject'] = $options ['translate_mail_subject'];
	$current_options ['translate_mail_message'] = $options ['translate_mail_message'];
	$current_options ['translate_mail_cancel'] = $options ['translate_mail_cancel'];
	$current_options ['translate_mail_send'] = $options ['translate_mail_send'];
	$current_options ['facebook_like_button_width'] = $options ['facebook_like_button_width'];
	$current_options ['use_minified_css'] = $options ['use_minified_css'];
	$current_options ['use_minified_js'] = $options ['use_minified_js'];
	
	$current_options['mail_captcha_answer'] = $options['mail_captcha_answer'];
	$current_options['mail_captcha'] = $options['mail_captcha'];
	
	$current_options['flattr_username'] = $options['flattr_username'];
	$current_options['flattr_tags'] = $options['flattr_tags'];
	$current_options['flattr_cat'] = $options['flattr_cat'];
	$current_options['flattr_lang'] = $options['flattr_lang'];
	
	$current_options['managedwp_button'] = $options['managedwp_button'];
	$current_options['skin_native'] = $options['skin_native'];
	
	$current_options['skinned_fb_color'] = $options['skinned_fb_color'];
	$current_options['skinned_fb_width'] = $options['skinned_fb_width'];
	$current_options['skinned_fb_text'] = $options['skinned_fb_text'];
	$current_options['skinned_fb_hovercolor'] = $options['skinned_fb_hovercolor'];
	$current_options['skinned_fb_textcolor'] = $options['skinned_fb_textcolor'];

	$current_options['skinned_vk_color'] = $options['skinned_vk_color'];
	$current_options['skinned_vk_width'] = $options['skinned_vk_width'];
	$current_options['skinned_vk_text'] = $options['skinned_vk_text'];
	$current_options['skinned_vk_hovercolor'] = $options['skinned_vk_hovercolor'];
	$current_options['skinned_vk_textcolor'] = $options['skinned_vk_textcolor'];
	
	$current_options['skinned_google_color'] = $options['skinned_google_color'];
	$current_options['skinned_google_width'] = $options['skinned_google_width'];
	$current_options['skinned_google_text'] = $options['skinned_google_text'];
	$current_options['skinned_google_hovercolor'] = $options['skinned_google_hovercolor'];
	$current_options['skinned_google_textcolor'] = $options['skinned_google_textcolor'];
	
	$current_options['skinned_twitter_color'] = $options['skinned_twitter_color'];
	$current_options['skinned_twitter_width'] = $options['skinned_twitter_width'];
	$current_options['skinned_twitter_text'] = $options['skinned_twitter_text'];
	$current_options['skinned_twitter_hovercolor'] = $options['skinned_twitter_hovercolor'];
	$current_options['skinned_twitter_textcolor'] = $options['skinned_twitter_textcolor'];
	
	$current_options['skinned_pinterest_color'] = $options['skinned_pinterest_color'];
	$current_options['skinned_pinterest_width'] = $options['skinned_pinterest_width'];
	$current_options['skinned_pinterest_text'] = $options['skinned_pinterest_text'];
	$current_options['skinned_pinterest_hovercolor'] = $options['skinned_pinterest_hovercolor'];
	$current_options['skinned_pinterest_textcolor'] = $options['skinned_pinterest_textcolor'];
	
	$current_options['skinned_youtube_color'] = $options['skinned_youtube_color'];
	$current_options['skinned_youtube_width'] = $options['skinned_youtube_width'];
	$current_options['skinned_youtube_text'] = $options['skinned_youtube_text'];
	$current_options['skinned_youtube_hovercolor'] = $options['skinned_youtube_hovercolor'];
	$current_options['skinned_youtube_textcolor'] = $options['skinned_youtube_textcolor'];
	
	$current_options['twitter_tweet'] = $options['twitter_tweet'];
	$current_options['pinterest_native_type'] = $options['pinterest_native_type'];
	
	$current_options['skin_native_skin'] = $options['skin_native_skin'];
	$current_options['use_wpmandrill'] = $options['use_wpmandrill'];
	$current_options['scripts_in_head'] = $options['scripts_in_head'];
	$current_options['twitter_shareshort_service'] = $options['twitter_shareshort_service'];
	
	$current_options['translate_mail_message_error_send'] = $options['translate_mail_message_error_send'];
	$current_options['translate_mail_message_invalid_captcha'] = $options['translate_mail_message_invalid_captcha'];
	$current_options['translate_mail_message_sent'] = $options['translate_mail_message_sent'];
	
	$current_options['fixed_width_value'] = $options['fixed_width_value'];
	$current_options['fixed_width_active'] = $options['fixed_width_active'];
	$current_options['sso_apply_the_content'] = $options['sso_apply_the_content'];
	
	$current_options['facebook_like_button_height'] = $options['facebook_like_button_height'];
	$current_options['facebook_like_button_margin_top'] = $options['facebook_like_button_margin_top'];
	
	$current_options['module_off_lv'] = $options['module_off_lv'];
	$current_options['module_off_sfc'] = $options['module_off_sfc'];
	$current_options['load_js_async'] = $options['load_js_async'];
	
	$current_options['encode_url_nonlatin'] = $options['encode_url_nonlatin'];
	$current_options['stumble_noshortlink'] = $options['stumble_noshortlink'];
	$current_options['turnoff_essb_advanced_box'] = $options['turnoff_essb_advanced_box'];
	
	$current_options['esml_monitor_types'] = $options['esml_monitor_types'];
	$current_options['esml_active'] = $options['esml_active'];
	$current_options['esml_ttl'] = $options['esml_ttl'];
	$current_options['avoid_nextpage'] = $options['avoid_nextpage'];
	$current_options['apply_clean_buttons'] = $options['apply_clean_buttons'];
	$current_options['force_wp_query_postid'] = $options['force_wp_query_postid'];
	$current_options['print_use_printfriendly'] = $options['print_use_printfriendly'];
	
	$current_options['twitter_always_count_full'] = $options['twitter_always_count_full'];
	$current_options['more_button_func'] = $options['more_button_func'];
	$current_options['fullwidth_share_buttons_correction_mobile'] = $options['fullwidth_share_buttons_correction_mobile'];
	$current_options['mail_funcion'] = $options['mail_funcion'];
	
	$current_options['priority_of_buttons'] = $options['priority_of_buttons'];
	$current_options['activate_ga_tracking'] = $options['activate_ga_tracking'];
	$current_options['ga_tracking_mode'] = $options['ga_tracking_mode'];
	$current_options['facebook_like_type'] = $options['facebook_like_type'];
	$current_options['facebook_follow_profile'] = $options['facebook_follow_profile'];
	$current_options['google_follow_profile'] = $options['google_follow_profile'];
	$current_options['google_like_type'] = $options['google_like_type'];
	
	$current_options['allnative_counters'] = $options['allnative_counters'];
	$current_options['linkedin_follow'] = $options['linkedin_follow'];
	$current_options['linkedin_follow_id'] = $options['linkedin_follow_id'];
	$current_options['skinned_linkedin_color'] = $options['skinned_linkedin_color'];
	$current_options['skinned_linkedin_hovercolor'] = $options['skinned_linkedin_hovercolor'];
	$current_options['skinned_linkedin_textcolor'] = $options['skinned_linkedin_textcolor'];
	$current_options['skinned_linkedin_width'] = $options['skinned_linkedin_width'];
	$current_options['skinned_linkedin_text'] = $options['skinned_linkedin_text'];
	
	$current_options['sso_google_author'] = $options['sso_google_author'];
	$current_options['ss_google_author_profile'] = $options['ss_google_author_profile'];
	$current_options['ss_google_author_publisher'] = $options['ss_google_author_publisher'];
	$current_options['sso_google_markup'] = $options['sso_google_markup'];
	
	$current_options['facebook_like_button_api_async'] = $options['facebook_like_button_api_async'];
	$current_options['remove_ver_resource'] = $options['remove_ver_resource'];
	
	$current_options['mail_shorturl'] = $options['mail_shorturl'];
	
	$current_options ['advanced_share'] = $as;
	
	update_option ( EasySocialShareButtons::$plugin_settings_name, $current_options );
	
	$msg = __ ( "Settings are saved", ESSB_TEXT_DOMAIN );
	
	if ($current_options ['stats_active'] == 'true') {
		EasySocialShareButtons_Stats_Admin::install ();
	}
	
	// update social fans counter
	if (isset($essb_fans)) {
		$essb_fans->options['social'] = $_POST['social'];
		$essb_fans->options['sort'] = $_POST['sort'];
		$essb_fans->options['cache'] = (int) $_POST['cache'];
		//$essb_fans->options['data'] = '';
	
		update_option($essb_fans->options_text ,$essb_fans->options);
		delete_transient($essb_fans->transient_text);
	}
}

function essb_setting_checkbox_network_selection() {
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	if (is_array ( $options )) {
		
		if (! is_array ( $options ['networks'] )) {
			$default_networks = EasySocialShareButtons::default_options ();
			$options ['networks'] = $default_networks ['networks'];
		}
		
		foreach ( $options ['networks'] as $k => $v ) {

			$more_options = "";
			
			if ($k == "twitter" || $k == "facebook" || $k == "pinterest" || $k == "flatter" || $k == "stumbleuppon" || $k == "print") {
				//$more_options = '<span class="label" style="margin-top:-15px;"><a href="#'.$k.'"> Additional Options</a></span>';
			}
			
			$is_checked = ($v [0] == 1) ? ' checked="checked"' : '';
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
			
			echo '<li><p style="margin: .2em 5% .2em 0;">
			<input id="network_selection_' . $k . '" value="' . $k . '" name="general_options[networks][]" type="checkbox"
			' . $is_checked . ' /><input name="general_options[sort][]" value="' . $k . '" type="checkbox" checked="checked" style="display: none; " />
			<label for="network_selection_' . $k . '"><span class="essb_icon essb_icon_' . $k . '"></span>' . $network_name . '</label> '.$more_options.'
			</p></li>';
		}
	
	}
}

function essb_custom_buttons_pos() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['buttons_pos'] ) ? $options ['buttons_pos'] : '';
		$exist = stripslashes ( $exist );
		
		echo '<p style="margin: .2em 5% .2em 0;"><select id="buttons_pos" type="text" name="general_options[buttons_pos]" class="input-element">
		<option value="" ' . ($exist == '' ? ' selected="selected"' : '') . '>Left</option>
		<option value="right" ' . ($exist == 'right' ? ' selected="selected"' : '') . '>Right</option>
		<option value="center" ' . ($exist == 'center' ? ' selected="selected"' : '') . '>Center</option>
		</select>
		</p>';
	
	}
}

function essb_facebook_likebutton() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['facebook_like_button'] ) ? $options ['facebook_like_button'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="fb_like" type="checkbox" name="general_options[facebook_like_button]" value="true" ' . $is_checked . ' /><label for="fb_like">Include Facebook Like/Follow Button</label></p>';
		
		$exist = isset ( $options ['facebook_like_button_api'] ) ? $options ['facebook_like_button_api'] : 'false';
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="fb_like" type="checkbox" name="general_options[facebook_like_button_api]" value="true" ' . $is_checked . ' /><label for="fb_like">My site already uses Facebook Api</label></p>';
		
		$exist = isset ( $options ['facebook_like_button_api_async'] ) ? $options ['facebook_like_button_api_async'] : 'false';
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="fb_like" type="checkbox" name="general_options[facebook_like_button_api_async]" value="true" ' . $is_checked . ' /><label for="fb_like">Load Facebook API asynchronous</label></p>';
		
		$exist = isset ( $options ['facebook_like_button_width'] ) ? $options ['facebook_like_button_width'] : '';
		$is_checked = $exist;
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebook_like_button_width" type="text" name="general_options[facebook_like_button_width]" value="' . $is_checked . '" /><br/><label for="facebook_like_button_width" class="small">Set custom width of Facebook like button to fix problem with not rendering correct. Value must be number without px in it.</label></p>';

			$exist = isset ( $options ['facebook_like_button_height'] ) ? $options ['facebook_like_button_height'] : '';
		$is_checked = $exist;
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebook_like_button_height" type="text" name="general_options[facebook_like_button_height]" value="' . $is_checked . '" /><br/><label for="facebook_like_button_height" class="small">Set custom height of Facebook like button to fix problem with not rendering correct. Value must be number without px in it.</label></p>';
	
			$exist = isset ( $options ['facebook_like_button_margin_top'] ) ? $options ['facebook_like_button_margin_top'] : '';
		$is_checked = $exist;
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebook_like_button_margin_top" type="text" name="general_options[facebook_like_button_margin_top]" value="' . $is_checked . '" /><br/><label for="facebook_like_button_width" class="small">Set custom margin-top (to move up use negative value) of Facebook like button to fix problem with not rendering correct. Value must be number without px in it.</label></p>';
	}
}

function essb_plusone_button() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['googleplus'] ) ? $options ['googleplus'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="plusone" type="checkbox" name="general_options[googleplus]" value="true" ' . $is_checked . ' /><label for="plusone">Include Default Google+ Button</label></p>';
	
	}
}

function essb_vklike_button() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['vklike'] ) ? $options ['vklike'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="vklike" type="checkbox" name="general_options[vklike]" value="true" ' . $is_checked . ' /><label for="vklike">Include Default VKontakte (vk.com) Like Button</label></p>';
	
	}

}
function essb_vklike_button_appid() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['vklikeappid'] ) ? $options ['vklikeappid'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="vklikeappid" type="text" name="general_options[vklikeappid]" value="' . $exist . '" class="input-element" /></p><span for="vklikeappid" class="small">If you don\'t have application id for your site you need to generate one on VKontakte (vk.com) Dev Site. To do this visit this page <a href="http://vk.com/dev.php?method=Like" target="_blank">http://vk.com/dev.php?method=Like</a> and follow instrunctions on page</span>';
	
	}

}

function essb_customshare_message() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['customshare'] ) ? $options ['customshare'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<input id="customshare" type="checkbox" name="general_options[customshare]" value="true" ' . $is_checked . ' />';
	
	}

}

function essb_customshare_message_text() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['customshare_text'] ) ? $options ['customshare_text'] : '';
		
		echo '<input id="customshare_text" type="text" name="general_options[customshare_text]" value="' . $exist . '" class="input-element stretched" />';
	
	}

}

function essb_customshare_message_url() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['customshare_url'] ) ? $options ['customshare_url'] : '';
		
		echo '<input id="customshare_url" type="text" name="general_options[customshare_url]" value="' . $exist . '" class="input-element stretched" />';
	
	}

}

function essb_customshare_message_imageurl() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['customshare_imageurl'] ) ? $options ['customshare_imageurl'] : '';
		
		echo '<input id="customshare_imageurl" type="text" name="general_options[customshare_imageurl]" value="' . $exist . '" class="input-element stretched" />';
	
	}

}

function essb_customshare_message_description() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['customshare_description'] ) ? $options ['customshare_description'] : '';
		
		echo '<textarea id="customshare_description" type="text" name="general_options[customshare_description]" class="input-element stretched" rows="5">' . $exist . "</textarea>";
	
	}

}

function essb_pinterest_sniff_disable() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['pinterest_sniff_disable'] ) ? $options ['pinterest_sniff_disable'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="pinterest_sniff_disable" type="checkbox" name="general_options[pinterest_sniff_disable]" value="true" ' . $is_checked . ' /></p>';
	}
}

function essb_template_select_radio() {
	
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$n1 = $n2 = $n3 = $n4 = $n5 = $n6 = $n7 = $n8 = $n9 = $n10 = $n11 = $n12 = "";
		${'n' . $options ['style']} = " checked='checked'";
		
		echo '
			<input id="essb_style_1" value="1" name="general_options[style]" type="radio" ' . $n1 . ' />&nbsp;&nbsp;' . __ ( 'Default', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-default.png"/>
			<br/><br/>
			<input id="essb_style_2" value="2" name="general_options[style]" type="radio" ' . $n2 . ' />&nbsp;&nbsp;' . __ ( 'Metro', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-metro.png"/>
			<br/><br/>
			<input id="essb_style_3" value="3" name="general_options[style]" type="radio" ' . $n3 . ' />&nbsp;&nbsp;' . __ ( 'Modern', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-modern.png"/>
			<br/><br/>
			<input id="essb_style_4" value="4" name="general_options[style]" type="radio" ' . $n4 . ' />&nbsp;&nbsp;' . __ ( 'Round', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-round.png"/><br/><span class="small">Round style works correct only with Hide Social Network Names: <strong>Yes</strong>. If this option is not set to Yes please change its value or template will not render correct.</span>
			<br/><br/>
			<input id="essb_style_5" value="5" name="general_options[style]" type="radio" ' . $n5 . ' />&nbsp;&nbsp;' . __ ( 'Big', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-big.png"/>
			<br/><br/>
			<input id="essb_style_6" value="6" name="general_options[style]" type="radio" ' . $n6 . ' />&nbsp;&nbsp;' . __ ( 'Metro (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-metro2.png"/>
			<br/><br/>
			<input id="essb_style_7" value="7" name="general_options[style]" type="radio" ' . $n7 . ' />&nbsp;&nbsp;' . __ ( 'Big (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-big-retina.png"/>
			<br/><br/>
			<input id="essb_style_8" value="8" name="general_options[style]" type="radio" ' . $n8 . ' />&nbsp;&nbsp;' . __ ( 'Light (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-light-retina.png"/>
			<br/><br/>
			<input id="essb_style_9" value="9" name="general_options[style]" type="radio" ' . $n9 . ' />&nbsp;&nbsp;' . __ ( 'Flat (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-flat.png"/>
			<br/><br/>
			<input id="essb_style_10" value="10" name="general_options[style]" type="radio" ' . $n10 . ' />&nbsp;&nbsp;' . __ ( 'Tiny (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-tiny.png"/>
			<br/><br/>
			<input id="essb_style_11" value="11" name="general_options[style]" type="radio" ' . $n11 . ' />&nbsp;&nbsp;' . __ ( 'Round (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-round-retina.png"/>
			<br/><br/>
			<input id="essb_style_12" value="12" name="general_options[style]" type="radio" ' . $n12 . ' />&nbsp;&nbsp;' . __ ( 'Modern (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-modern-retina.png"/>
			';
	}
}

function essb_setting_input_mail_subject() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (isset ( $options ['mail_subject'] ))
		echo '<input id="mail_subject" value="' . esc_attr ( $options ['mail_subject'] ) . '" name="general_options[mail_subject]" type="text"  class="input-element stretched"/>';
}
function essb_setting_textarea_mail_body() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (isset ( $options ['mail_body'] ))
		echo '<textarea id="mail_body" name="general_options[mail_body]" class="input-element stretched" rows="5">' . esc_textarea ( stripslashes ( $options ['mail_body'] ) ) . '</textarea>';
}

function essb_setting_textarea_mail_copyaddress() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$value = isset ( $options ['mail_copyaddress'] ) ? $options ['mail_copyaddress'] : '';
		
		echo '<input id="mail_copyaddress" value="' . esc_attr ( $value ) . '" name="general_options[mail_copyaddress]" type="text"  class="input-element stretched"/>';
	}
}

function essb_setting_input_mail_captcha() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	if (is_array ( $options )) {
		$exist = isset ( $options ['mail_captcha'] ) ? $options ['mail_captcha'] : '';
	
		echo '<input id="mail_captcha" value="' . esc_attr ( $exist ) . '" name="general_options[mail_captcha]" type="text"  class="input-element stretched"/>';
	}
	
}

function essb_setting_input_mail_captcha_answer() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	if (is_array ( $options )) {
		$exist = isset ( $options ['mail_captcha_answer'] ) ? $options ['mail_captcha_answer'] : '';
	
		echo '<input id="mail_captcha_answer" value="' . esc_attr ( $exist ) . '" name="general_options[mail_captcha_answer]" type="text"  class="input-element stretched"/>';
	}	
}

function essb_display_other_onsame() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['otherbuttons_sameline'] ) ? $options ['otherbuttons_sameline'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="otherbuttons_sameline" type="checkbox" name="general_options[otherbuttons_sameline]" value="true" ' . $is_checked . ' /></p>';
	}
}

function essb_twitter_follow_button() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitterfollow'] ) ? $options ['twitterfollow'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitterfollow" type="checkbox" name="general_options[twitterfollow]" value="true" ' . $is_checked . ' /><label for="twitterfollow"></label></p>';
	
	}

}

function essb_custom_like_address() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['custom_url_like'] ) ? $options ['custom_url_like'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<input id="custom_url_like" type="checkbox" name="general_options[custom_url_like]" value="true" ' . $is_checked . ' />';
	
	}

}

function essb_custom_like_address_url() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['custom_url_like_address'] ) ? $options ['custom_url_like_address'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="custom_url_like_address" type="text" name="general_options[custom_url_like_address]" value="' . $exist . '" class="input-element stretched" /></p>';
	
	}

}

function essb_custom_plusone_address_url() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['custom_url_plusone_address'] ) ? $options ['custom_url_plusone_address'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="custom_url_plusone_address" type="text" name="general_options[custom_url_plusone_address]" value="' . $exist . '" class="input-element stretched" /></p>';
	
	}

}

function essb_twitter_follow_button_user() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitterfollowuser'] ) ? $options ['twitterfollowuser'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitterfollowuser" type="text" name="general_options[twitterfollowuser]" value="' . $exist . '" class="input-element" /></p>';
	
	}

}

function essb_youtube_subscribe_channel() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['youtubechannel'] ) ? $options ['youtubechannel'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="youtubechannel" type="text" name="general_options[youtubechannel]" value="' . $exist . '" class="input-element" style="width: 350px;" /></p>';
	
	}
}

function essb_youtube_subscribe() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['youtubesub'] ) ? $options ['youtubesub'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="youtubesub" type="checkbox" name="general_options[youtubesub]" value="true" ' . $is_checked . ' /><label for="youtubesub"></label></p>';
	
	}

}

function essb_url_short_native() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['url_short_native'] ) ? $options ['url_short_native'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="url_short_native" type="checkbox" name="general_options[url_short_native]" value="true" ' . $is_checked . ' /><label for="url_short_native"></label></p>';
	
	}

}

function essb_url_short_google() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['url_short_google'] ) ? $options ['url_short_google'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="url_short_google" type="checkbox" name="general_options[url_short_google]" value="true" ' . $is_checked . ' /><label for="url_short_native"></label></p>';
	
	}

}

function essb_url_short_bitly() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['url_short_bitly'] ) ? $options ['url_short_bitly'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="url_short_bitly" type="checkbox" name="general_options[url_short_bitly]" value="true" ' . $is_checked . ' /><label for="url_short_bitly"></label></p>';
	
	}

}

function essb_url_short_bitly_user() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['url_short_bitly_user'] ) ? $options ['url_short_bitly_user'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="url_short_bitly_user" type="text" name="general_options[url_short_bitly_user]" value="' . $exist . '" class="input-element" style="width: 350px;" /></p>';
	
	}
}

function essb_url_short_bitly_api() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['url_short_bitly_api'] ) ? $options ['url_short_bitly_api'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="url_short_bitly_api" type="text" name="general_options[url_short_bitly_api]" value="' . $exist . '" class="input-element" style="width: 350px;" /></p>';
	
	}
}

function essb_twitter_username_append() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitteruser'] ) ? $options ['twitteruser'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitteruser" type="text" name="general_options[twitteruser]" value="' . $exist . '" class="input-element" /></p><span for="twitteruser" class="small">If you wish a twitter username to be mentioned in tweet write it here. Enter your username without @ - example <span class="bold">twittername</span>. This text will be appended to tweet message at the end. Please note that if you activate custom share address option this will be added to custom share message.</span>';
	
	}

}

function essb_twitter_hashtags_append() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitterhashtags'] ) ? $options ['twitterhashtags'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitterhashtags" type="text" name="general_options[twitterhashtags]" value="' . $exist . '" class="input-element" /></p><span for="twitterhashtags" class="small">If you wish hashtags to be added to message write theme here. You can set one or more (if more then one separate them with comma (,)) Example: demotag1,demotag2.</span>';
	
	}

}

function essb_twitter_dont_popup() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitter_nojspop'] ) ? $options ['twitter_nojspop'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitter_nojspop" type="checkbox" name="general_options[twitter_nojspop]" value="true" ' . $is_checked . ' /></p><span for="twitter_nojspop" class="small">If you have issue with Twitter share button opening same window but not share a tweet window activate this option to fix it.</span>';
	
	}

}

function essb_twitter_share_short() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitter_shareshort'] ) ? $options ['twitter_shareshort'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitter_shareshort" type="checkbox" name="general_options[twitter_shareshort]" value="true" ' . $is_checked . ' /></p>';
	
	}

}

function essb_pinterest_follow_display() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['pinterestfollow_disp'] ) ? $options ['pinterestfollow_disp'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="pinterestfollow_disp" type="text" name="general_options[pinterestfollow_disp]" value="' . $exist . '" class="input-element" style="width: 350px;" /></p>';
	
	}
}

function essb_pinterest_follow_url() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['pinterestfollow_url'] ) ? $options ['pinterestfollow_url'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="pinterestfollow_url" type="text" name="general_options[pinterestfollow_url]" value="' . $exist . '" class="input-element" style="width: 350px;" /></p>';
	
	}
}

function essb_pinterest_follow() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['pinterestfollow'] ) ? $options ['pinterestfollow'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="pinterestfollow" type="checkbox" name="general_options[pinterestfollow]" value="true" ' . $is_checked . ' /><label for="pinterestfollow"></label></p>';
	
	}

}

function essb_facebook_simple_sharing() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['facebooksimple'] ) ? $options ['facebooksimple'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebooksimple" type="checkbox" name="general_options[facebooksimple]" value="true" ' . $is_checked . ' /><label for="facebooksimple"></label></p>';
	
	}

}

function essb_facebook_advanced_sharing() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['facebookadvanced'] ) ? $options ['facebookadvanced'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebookadvanced" type="checkbox" name="general_options[facebookadvanced]" value="true" ' . $is_checked . ' /><label for="facebookadvanced"></label></p>';
	
	}

}

function essb_facebook_advanced_application_appid() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['facebookadvancedappid'] ) ? $options ['facebookadvancedappid'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebookadvancedappid" type="text" name="general_options[facebookadvancedappid]" value="' . $exist . '" class="input-element" style="width: 300px;" /></p>';
	
	}

}

function essb_facebook_total_count() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['facebooktotal'] ) ? $options ['facebooktotal'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebooktotal" type="checkbox" name="general_options[facebooktotal]" value="true" ' . $is_checked . ' /><label for="facebooktotal"></label></p>';
	
	}

}

function essb_facebook_hashtags_append() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['facebookhashtags'] ) ? $options ['facebookhashtags'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="facebookhashtags" type="text" name="general_options[facebookhashtags]" value="' . $exist . '" class="input-element" /></p><span for="facebookhashtags" class="small">If you wish hashtags to be added to message write theme here. You can set one or more (if more then one separate them with comma (,)) Example: #demotag1, #demotag2. Hashtags must be added with hash tag symbol (#).</span>';
	
	}

}

function essb_click_stats() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['stats_active'] ) ? $options ['stats_active'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="stats_active" type="checkbox" name="general_options[stats_active]" value="true" ' . $is_checked . ' /><label for="stats_active"></label></p>';
	
	}

}

function essb_opengraph_tags() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['opengraph_tags'] ) ? $options ['opengraph_tags'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="opengraph_tags" type="checkbox" name="general_options[opengraph_tags]" value="true" ' . $is_checked . ' /><label for="stats_active"></label></p>';
	
	}

}

function essb_sso_default_share_image() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['sso_default_image'] ) ? $options ['sso_default_image'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="sso_default_image" type="text" name="general_options[sso_default_image]" value="' . $exist . '" class="input-element stretched" /></p><span for="sso_default_image" class="small"></span>';
	
	}
}

function essb_sso_facebook_page() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['opengraph_tags_fbpage'] ) ? $options ['opengraph_tags_fbpage'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="opengraph_tags_fbpage" type="text" name="general_options[opengraph_tags_fbpage]" value="' . $exist . '" class="input-element stretched" /></p><span for="opengraph_tags_fbpage" class="small"></span>';
	
	}
}

function essb_sso_facebook_admins() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['opengraph_tags_fbadmins'] ) ? $options ['opengraph_tags_fbadmins'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="opengraph_tags_fbadmins" type="text" name="general_options[opengraph_tags_fbadmins]" value="' . $exist . '" class="input-element" /></p><span for="opengraph_tags_fbadmins" class="small"></span>';
	
	}
}

function essb_sso_facebook_appid() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['opengraph_tags_fbapp'] ) ? $options ['opengraph_tags_fbapp'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="opengraph_tags_fbapp" type="text" name="general_options[opengraph_tags_fbapp]" value="' . $exist . '" class="input-element" /></p><span for="opengraph_tags_fbadmins" class="small"></span>';
	
	}
}

// twitter card
function essb_sso_twitter_card() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		
		$exist = isset ( $options ['twitter_card'] ) ? $options ['twitter_card'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitter_card" type="checkbox" name="general_options[twitter_card]" value="true" ' . $is_checked . ' /><label for="twitter_card"></label></p>';
	
	}

}

function essb_sso_twitter_card_user() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitter_card_user'] ) ? $options ['twitter_card_user'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitter_card_user" type="text" name="general_options[twitter_card_user]" value="' . $exist . '" class="input-element" /></p><span for="twitter_card_user" class="small"></span>';
	
	}
}

function essb_sso_twitter_card_type() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitter_card_type'] ) ? $options ['twitter_card_type'] : '';
		$exist = stripslashes ( $exist );
		
		echo '<p style="margin: .2em 5% .2em 0;"><select id="twitter_card_type" type="text" name="general_options[twitter_card_type]" class="input-element">
		<option value="" ' . ($exist == '' ? ' selected="selected"' : '') . '>Summary</option>
		<option value="summaryimage" ' . ($exist == 'summaryimage' ? ' selected="selected"' : '') . '>Summary with image</option>
		</select>
		</p>';
	
	}
}

function essb_disable_adminbar_menu() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['disable_adminbar_menu'] ) ? $options ['disable_adminbar_menu'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="disable_adminbar_menu" type="checkbox" name="general_options[disable_adminbar_menu]" value="true" ' . $is_checked . ' /></p>';
	}
}

function essb_register_pluginsettings_under_settings() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['register_menu_under_settings'] ) ? $options ['register_menu_under_settings'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="register_menu_under_settings" type="checkbox" name="general_options[register_menu_under_settings]" value="true" ' . $is_checked . ' /></p>';
	}
}

function essb_fix_using_yoast_ga() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['using_yoast_ga'] ) ? $options ['using_yoast_ga'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="using_yoast_ga" type="checkbox" name="general_options[using_yoast_ga]" value="true" ' . $is_checked . ' /></p>';
	}
}

function essb_setting_advanced_network_share() {
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	if (is_array ( $options )) {
		foreach ( $options ['networks'] as $k => $v ) {
			
			if ($k == "mail" || $k == "print" || $k == "love") {
				continue;
			}
			
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
			
			$message_pass = "";
			$url_pass = "";
			$image_pass = "";
			$desc_pass = "";
			
			if (isset ( $options ['advanced_share'] )) {
				$settings = $options ['advanced_share'];
				
				$message_pass = isset ( $settings [$k . '_t'] ) ? $settings [$k . '_t'] : '';
				$url_pass = isset ( $settings [$k . '_u'] ) ? $settings [$k . '_u'] : '';
				$image_pass = isset ( $settings [$k . '_i'] ) ? $settings [$k . '_i'] : '';
				$desc_pass = isset ( $settings [$k . '_d'] ) ? $settings [$k . '_d'] : '';
			}
			
			echo '<tr class="table-border-bottom">';
			echo '<td colspan="2" class="sub2">' . $network_name . '</td>';
			echo '</tr>';
			
			echo '<tr class="even table-border-bottom">';
			echo '<td class="bold">URL:</td>';
			echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $url_pass . '" name="general_options_as[' . $k . '_u] type="text" class="input-element stretched" /></td>';
			echo '</tr>';
			
			if ($k == "facebook" || $k == "twitter" || $k == "pinterest" || $k == "tumblr" || $k == "digg" || $k == "linkedin" || $k == "reddit" || $k == "del" || $k == "buffer") {
				echo '<tr class="odd table-border-bottom">';
				echo '<td class="bold">Message:</td>';
				echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $message_pass . '" name="general_options_as[' . $k . '_t] type="text" class="input-element stretched" /></td>';
				echo '</tr>';
			
			}
			if ($k == "facebook" || $k == "pinterest") {
				echo '<tr class="even table-border-bottom">';
				echo '<td class="bold">Image:</td>';
				echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $image_pass . '" name="general_options_as[' . $k . '_i] type="text" class="input-element stretched" /></td>';
				echo '</tr>';
			
			}
			
			if ($k == "facebook" || $k == "pinterest") {
				echo '<tr class="odd table-border-bottom">';
				echo '<td class="bold">Description:</td>';
				echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $desc_pass . '" name="general_options_as[' . $k . '_d] type="text" class="input-element stretched" /></td>';
				echo '</tr>';
			
			}
		
		}
	}

}

function essb_fullwidth_share_buttons() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['fullwidth_share_buttons'] ) ? $options ['fullwidth_share_buttons'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="fullwidth_share_buttons" type="checkbox" name="general_options[fullwidth_share_buttons]" value="true" ' . $is_checked . ' /><label for="fullwidth_share_buttons"></label></p>';
	
	}

}

function essb_fullwidth_share_buttons_correction() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['fullwidth_share_buttons_correction'] ) ? $options ['fullwidth_share_buttons_correction'] : '';
		
		echo '<p style="margin: .2em 5% .2em 0;"><input id="fullwidth_share_buttons_correction" type="text" name="general_options[fullwidth_share_buttons_correction]" value="' . $exist . '" class="input-element" /></p>';
	
	}

}

function essb_localize_mail_form() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		echo "<ul style='margin: 0; padding:0;'>";
		
		$k = 'translate_mail_title';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Share this with a friend</label>
		</p></li>';
		
		$k = 'translate_mail_email';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Your Email</label>
		</p></li>';
		
		$k = 'translate_mail_recipient';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Recipient Email</label>
		</p></li>';
		
		$k = 'translate_mail_subject';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Subject</label>
		</p></li>';
		
		$k = 'translate_mail_message';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Message</label>
		</p></li>';
		
		$k = 'translate_mail_cancel';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Cancel</label>
		</p></li>';
		
		$k = 'translate_mail_send';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Send</label>
		</p></li>';

		$k = 'translate_mail_message_sent';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Message sent!</label>
		</p></li>';

		$k = 'translate_mail_message_invalid_captcha';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Invalid Captcha code!</label>
		</p></li>';
		
		$k = 'translate_mail_message_error_send';
		$value = isset ( $options [$k] ) ? $options [$k] : '';
		
		echo '<li><p style="margin: .2em 5% .2em 0;">
		<input value="' . $value . '" name="general_options[' . $k . ']" type="text"
		class="input-element" />
		<label for="network_selection_' . $k . '">Error sending message!</label>
		</p></li>';
		
		echo "</ul>";
	}
}

function essb_use_minified_css_files() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['use_minified_css'] ) ? $options ['use_minified_css'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="use_minified_css" type="checkbox" name="general_options[use_minified_css]" value="true" ' . $is_checked . ' /></p>';
	
	}

}

function essb_use_minified_js_files() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['use_minified_js'] ) ? $options ['use_minified_js'] : 'false';
		
		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="use_minified_js" type="checkbox" name="general_options[use_minified_js]" value="true" ' . $is_checked . ' /></p>';
	
	}

}

function essb_esml_select_content_type() {
	$pts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => true ) );
	$cpts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => false ) );
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	if (is_array($options)) {
		if (!isset($options['esml_monitor_types'])) { $options['esml_monitor_types'] = array(); }
	}
	
	if (is_array ( $options ) && isset ( $options ['esml_monitor_types'] ) && is_array ( $options ['esml_monitor_types'] )) {

		global $wp_post_types;
		// classical post type listing
		foreach ( $pts as $pt ) {
				
			$selected = in_array ( $pt, $options ['esml_monitor_types'] ) ? 'checked="checked"' : '';
				
			$icon = "";
			echo '<input type="checkbox" name="general_options[esml_monitor_types][]" id="' . $pt . '" value="' . $pt . '" ' . $selected . '> <label for="' . $pt . '">' . $icon . ' ' . $wp_post_types [$pt]->label . '</label><br />';
		}

		// custom post types listing
		if (is_array ( $cpts ) && ! empty ( $cpts )) {
			foreach ( $cpts as $cpt ) {

				$selected = in_array ( $cpt, $options ['esml_monitor_types'] ) ? 'checked="checked"' : '';

				$icon = "";
				echo '<input type="checkbox" name="general_options[esml_monitor_types][]" id="' . $cpt . '" value="' . $cpt . '" ' . $selected . '> <label for="' . $cpt . '">' . $icon . ' ' . $wp_post_types [$cpt]->label . '</label><br />';
			}
		}
	}
}

?>
<div class="wrap">
	<?php
	
	if ($msg != "") {
		echo '<div class="updated" style="padding: 10px;">' . $msg . '</div>';
	}
	
	?>

<form name="general_form" method="post" action="admin.php?page=essb_settings&tab=general">
		<input type="hidden" id="cmd" name="cmd" value="update" />
		<input type="hidden" id="action" name="action" value="update" />
		<input type="hidden" id="section" name="section" value="" />
		<input type='hidden' name='essb_option_page' value='essb_settings_general' />
		<div class="essb-options">
			<div class="essb-options-header" id="essb-options-header">
				<div class="essb-options-title">
					Main Settings<br /> <span class="label" style="font-weight: 400;"><a
						href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo"
						target="_blank" style="text-decoration: none;">Easy Social Share Buttons for WordPress version <?php echo ESSB_VERSION; ?></a></span>
				</div>		
								<?php echo '<a href="'.$easy_mode_address.'" text="' . __ ( $easy_mode_text, ESSB_TEXT_DOMAIN ) . '" class="button button-essb">' . __ ( $easy_mode_text, ESSB_TEXT_DOMAIN ) . '</a>'; ?>
				
								<?php echo '<a href="'.add_query_arg ( 'easy-mode', 'true', 'admin.php?page=essb_settings&tab=wizard' ).'" text="' . __ ( 'Configuration Wizard', ESSB_TEXT_DOMAIN ) . '" class="button button-essb">' . __ ( 'Configuration Wizard', ESSB_TEXT_DOMAIN ) . '</a>'; ?>
								<?php echo '<a href="http://support.creoworx.com" target="_blank" text="' . __ ( 'Need Help? Click here to visit our support center', ESSB_TEXT_DOMAIN ) . '" class="button">' . __ ( 'Support Center', ESSB_TEXT_DOMAIN ) . '</a>'; ?>
				
				<?php echo '<input type="Submit" name="Submit" value="' . __ ( 'Update Settings', ESSB_TEXT_DOMAIN ) . '" class="button-primary" />'; ?>

	</div>
			<div class="essb-options-sidebar">
				<ul class="essb-options-group-menu" id="sticky-navigation">
					<li id="essb-menu-1" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('1'); return false;">Template</a></li>
					<li id="essb-menu-2" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('2'); return false;">Social Share
							Buttons</a></li>
					<li id="essb-menu-2-5" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-5'); return false;">Template</a></li>
					<li id="essb-menu-2-1" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-1'); return false;">Advanced Options</a></li>
					<li id="essb-menu-2-2" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-2'); return false;">Twitter Additional Options</a></li>
					<li id="essb-menu-2-3" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-3'); return false;">Pinterest Additional Options</a></li>
					<li id="essb-menu-2-4" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-4'); return false;">Facebook Additional Options</a></li>
					<li id="essb-menu-2-6" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-7'); return false;">StumbleUppon Additional Options</a></li>
					<li id="essb-menu-2-7" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-6'); return false;" <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>>Flattr Additional Options</a></li>
					<li id="essb-menu-2-8" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('2-8'); return false;">Print Additional Options</a></li>
					<li id="essb-menu-12" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('12'); return false;">Social Share Analytics</a></li>						
					<li id="essb-menu-9" class="essb-menu-item"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>><a href="#"
						onclick="essb_option_activate('9'); return false;">Social Sharing
							Optimization</a></li>
					<li id="essb-menu-9-1" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('9-1'); return false;">Facebook Open Graph</a></li>
					<li id="essb-menu-9-2" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('9-2'); return false;">Twitter Cards</a></li>
					<li id="essb-menu-9-3" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('9-3'); return false;">Google</a></li>
						<li id="essb-menu-11" class="essb-menu-item"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>><a href="#"
						onclick="essb_option_activate('11'); return false;">Easy Social Metrics Lite</a></li>
					<li id="essb-menu-10" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('10'); return false;">Social Fans
							Counter</a></li>
					<li id="essb-menu-10-1" class="essb-submenu-item"><a href="#" class="not-sub"><span href="#"
						onclick="essb_submenu_execute('10-1'); return false;">Facebook</span>, <span href="#"
						onclick="essb_submenu_execute('10-2'); return false;">Twitter</span>, <span href="#"
						onclick="essb_submenu_execute('10-3'); return false;">Google+</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-4'); return false;">YouTube</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-5'); return false;">Vimeo</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-6'); return false;">Dribbble</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-7'); return false;">Github</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-8'); return false;">Envato</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-9'); return false;">SoundCloud</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-11'); return false;">Behance</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-12'); return false;">Delicious</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-13'); return false;">Instagram</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-14'); return false;">Pinterest</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-15'); return false;">Love This</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-16'); return false;">VK.com</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-17'); return false;">RSS</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-18'); return false;">MailChimp</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-19'); return false;">LinkedIn</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-20'); return false;">Posts, Comments, Users</span>,
					<span href="#"
						onclick="essb_submenu_execute('10-21'); return false;">Tumblr</span>	</a></li>
					<li id="essb-menu-3" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('3'); return false;">Social Like, Follow and
							Subscribe Buttons</a></li>
					<li id="essb-menu-3-1" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-1'); return false;">Facebook Like/Follow</a></li>
					<li id="essb-menu-3-2" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-2'); return false;">Google +1/Follow Button</a></li>
					<li id="essb-menu-3-3" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-3'); return false;">VKontankte (vk.com) Like</a></li>
					<li id="essb-menu-3-4" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-4'); return false;">Twitter Tweet/Follow</a></li>
					<li id="essb-menu-3-5" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-5'); return false;">YouTube Subscribe</a></li>
					<li id="essb-menu-3-6" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-6'); return false;">Pinterest Follow/Pin</a></li>
					<li id="essb-menu-3-7" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-7'); return false;">LikendIn Company Follow</a></li>
					<li id="essb-menu-3-8" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-8'); return false;">ManagedWP.org Upvote Button</a></li>
					<li id="essb-menu-3-9" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('3-9'); return false;">Activate Custom Like Address</a></li>
						
					<li id="essb-menu-4" class="essb-menu-item" <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>><a href="#"
						onclick="essb_option_activate('4'); return false;">URL Shortener</a>
					</li>
					<li id="essb-menu-5" class="essb-menu-item"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>><a href="#"
						onclick="essb_option_activate('5'); return false;">Custom Share
							Message</a></li>
					<li id="essb-menu-8" class="essb-menu-item"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>><a href="#"
						onclick="essb_option_activate('8'); return false;">Advanced Custom
							Share</a></li>
					<li id="essb-menu-6" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('6'); return false;">Customize
							E-mail Message</a></li>
					<li id="essb-menu-7" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('7'); return false;">Administrative and Optimization
							Options</a></li>
					<li id="essb-menu-7-1" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('7-1'); return false;">Administrative Options</a></li>
					<li id="essb-menu-7-2" class="essb-submenu-item"><a href="#"
						onclick="essb_submenu_execute('7-2'); return false;">Optimization Options</a></li>							

				</ul>
			</div>
			<div class="essb-options-container" style="min-height: 550px;">
				<div id="essb-container-1" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Template', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/581/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>

						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Template:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">This will be your
									default theme for site. You are able to select different theme
									for each post/page.</span></td>
							<td class="essb_options_general bold"><?php essb_template_select_radio(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Use minified CSS files:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Minified CSS files
									will improve speed of load. Activate this option to use them.</span></td>
							<td class="essb_options_general bold"><?php essb_use_minified_css_files(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Use minified JS files:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Minified
									javascript files will improve speed of load. Activate this
									option to use them.</span></td>
							<td class="essb_options_general bold"><?php essb_use_minified_js_files(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Load scripts in head element:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">If you are using caching plugin like W3 Total Cache you need to activate this option if counters, send mail form or float does not work.</span></td>
							<td class="essb_options_general bold"><?php ESSB_Settings_Helper::drawCheckboxField('scripts_in_head'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('I am using URL addresses with non latin chars (beta):', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Activate this option to encode URL addresses with non latin chars if you have issues with share.</span></td>
							<td class="essb_options_general bold"><?php ESSB_Settings_Helper::drawCheckboxField('encode_url_nonlatin'); ?></td>
						</tr>
						</table>
				</div>
				<div id="essb-container-2" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Social Share Buttons', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/social-share-buttons/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-submenu-2-5">
							<td valign="top" class="bold"><?php _e('Social Networks:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Select networks
									that you wish to appear in your list. With drag and drop you
									can rearrange them.</span></td>
							<td class="essb_general_options"><ul id="networks-sortable"><?php essb_setting_checkbox_network_selection(); ?></ul></td>
						</tr>
						<tr class="even table-border-bottom"    id="essb-submenu-2-1">
						 <td valign="top" class="bold">More button function:<br />
								<span class="label" style="font-weight: 400;">Choose how more button will function.</span></td>
						 <td class="essb_options_general">
						 	<?php 
						 	
						 	$more_options = array("1"=> "Display all active networks after more button", "2" => "Display all social networks as popup", "3" => "Display only active social networks as popup");
						 	ESSB_Settings_Helper::drawSelectField('more_button_func', $more_options);
						 	
						 	?>
						 
						 </td>
						</tr>
						<tr class="odd table-border-bottom" >
							<td valign="top" class="bold"><?php _e('Buttons Align:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Choose how buttons
									to be aligned. Default position is left but you can also select
									Right or Center</span></td>
							<td><?php essb_custom_buttons_pos(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Full width share buttons:', ESSB_TEXT_DOMAIN); ?><br />
							</td>
							<td><?php essb_fullwidth_share_buttons(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Full width share buttons width correction (number):', ESSB_TEXT_DOMAIN); ?><br />
							</td>
							<td><?php essb_fullwidth_share_buttons_correction(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Full width share buttons width correction mobile (number):', ESSB_TEXT_DOMAIN); ?><br />
							</td>
							<td><?php ESSB_Settings_Helper::drawInputField('fullwidth_share_buttons_correction_mobile'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Fixed width share buttons:', ESSB_TEXT_DOMAIN); ?><br />
							</td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('fixed_width_active'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Fixed width share buttons width (in px without providing px to value):', ESSB_TEXT_DOMAIN); ?><br />
							</td>
							<td><?php ESSB_Settings_Helper::drawInputField('fixed_width_value'); ?></td>
						</tr>
						
						<tr class="table-border-bottom" id="essb-submenu-2-2">
							<td colspan="2" class="sub2">Twitter Additional Options</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Twitter share short url:<br/><div class="essb-recommended"><i class="fa fa-check"></i><span></span></div><span class="label" style="font-weight: normal;">Activate this option to share short url with Twitter.</span></td>
							<td class="essb_general_options"><?php essb_twitter_share_short(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Short URL service:</td>
							<td class="essb_general_options">
							<?php 
							$list_of_url = array('wp_get_shortlink', 'goo.gl', 'bit.ly');
							ESSB_Settings_Helper::drawSelectField('twitter_shareshort_service', $list_of_url, true);
							?>
							</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Twitter username to be mentioned:</td>
							<td class="essb_general_options"><?php essb_twitter_username_append(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Twitter hashtags to be added:</td>
							<td class="essb_general_options"><?php essb_twitter_hashtags_append(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Don't use popup window for Tweeter
								Share:</td>
							<td class="essb_general_options"><?php essb_twitter_dont_popup(); ?></td>
						</tr>
						<tr class="odd table-border-bottom"  >
							<td class="bold" valign="top">Make Twitter always count full post/page address when using shorurl:</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('twitter_always_count_full'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-2-3">
							<td colspan="2" class="sub2">Pinterest Additional Options</td>
						</tr>
						<tr class="odd">
							<td class="bold" valign="top">Disable Pinterest sniff for images:</td>
							<td><?php essb_pinterest_sniff_disable(); ?></td>
						</tr>
						<tr class="odd table-border-bottom"  >
							<td>&nbsp;</td>
							<td class="small">If you disable Pinterest sniff for images
								plugin will use for share post featured image or custom share
								image you provide.</td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-2-4">
							<td colspan="2" class="sub2">Facebook Additional Options</td>
						</tr>
						<tr class="even table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>>
							<td class="bold" valign="top">Use Facebook Advanced Sharing:<br />
								<span class="label" style="font-size: 400;">Enable this option
									if you wish to share custom share message. This option require
									to be set Facebook Application and you need to provide Facebook
									Application ID.</span></td>
							<td class="essb_general_options"><?php essb_facebook_advanced_sharing(); ?></td>
						</tr>
						<tr class="odd table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>>
							<td class="bold" valign="top">Facebook Application ID:<br /> <span
								class="label" style="font-size: 400;">For proper work of
									advanced Facebook sharing you need to provide application id.
									If you don't have you need to create one. To create Facebook
									Application use this link: <a
									href="http://developers.facebook.com/apps/" target="_blank">http://developers.facebook.com/apps/</a>
							</span></td>
							<td class="essb_general_options"><?php essb_facebook_advanced_application_appid(); ?></td>
						</tr>
						<tr class="even table-border-bottom"  >
							<td class="bold" valign="top">Display Facebook Total Count:<br />
							<div class="essb-recommended"><i class="fa fa-check"></i><span></span></div>
								<span class="label" style="font-size: 400;">Enable this option
									if you wish to display total count not only share count.</span></td>
							<td class="essb_general_options"><?php essb_facebook_total_count(); ?></td>
						</tr>
						<tr class="even table-border-bottom" style="display: none;">
							<td class="bold" valign="top">Facebook HastTags:</td>
							<td class="essb_general_options"><?php essb_facebook_hashtags_append(); ?></td>
						</tr>
						<tr class="table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?> id="essb-submenu-2-6" >
							<td colspan="2" class="sub2">Flattr Additional Options</td>
						</tr>
						<tr class="odd table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>>
							<td class="bold" valign="top">Flattr Username:<br /> <span
								class="label" style="font-size: 400;">The Flattr account to which the buttons will be assigned.
							</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawInputField('flattr_username'); ?></td>
						</tr>
						<tr class="even table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>>
							<td class="bold" valign="top">Additional Flattr tags for your posts:<br /> <span
								class="label" style="font-size: 400;">Comma separated list of additional tags to use in Flattr buttons
							</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawInputField('flattr_tags'); ?></td>
						</tr>
						<tr class="odd table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?>>
							<td class="bold" valign="top">Default category for your posts:<br /></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawSelectField('flattr_cat', ESSB_Extension_Flattr::getCategories(), true); ?></td>
						</tr>
						<tr class="even table-border-bottom"  <?php if ($active_easy_mode) { echo ' style="display:none;"';} ?> >
							<td class="bold" valign="top">Default language for your posts:<br /></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawSelectField('flattr_lang', ESSB_Extension_Flattr::getLanguages()); ?></td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">StumpleUpon Additional Options</td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-submenu-2-7">
							<td class="bold" valign="top">Do not generate shortlinks:<br /></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('stumble_noshortlink'); ?></td>
						</tr>
						<tr class="table-border-bottom" >
							<td colspan="2" class="sub2">Print additional options</td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-submenu-2-8">
							<td class="bold" valign="top">User prindfriendly.com service instead of default print function:<br /></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('print_use_printfriendly'); ?></td>
						</tr>
						</table>
				</div>
				<div id="essb-container-12" class="essb-data-container">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
								<col width="25%" />
						<col width="75%" />
						<tr class="table-border-bottom">
							<td colspan="2" class="sub">Social Share Analytics<div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/social-share-optimization-sso/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">Click Log and Statistics</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Activate Statistics:<br /> <span
								class="label" style="font-size: 400;">Click statistics hanlde
									click on share buttons and you are able to see detailed view of
									user activity. Please note that plugin log clicks of buttons.</span></td>
							<td class="essb_general_options"><?php essb_click_stats(); ?></td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">Google Analytics</td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Activate Google Analytics Tracking:<br /> <span
								class="label" style="font-size: 400;">Activate tracking of social share buttons click using Google Analytics (requires Google Analytics to be active on this site).</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('activate_ga_tracking'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Google Analytics Tracking Method:<br /> <span
								class="label" style="font-size: 400;">Choose your tracking method: Simple - track clicks by social networks, Extended - track clicks on separate social networks by button display position.</span></td>
							<td class="essb_general_options"><?php 
							$listOfOptions = array("simple" => "Simple", "extended" => "Extended");
							ESSB_Settings_Helper::drawSelectField('ga_tracking_mode', $listOfOptions); ?></td>
						</tr>
						</table>
				</div>
				<div id="essb-container-9" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr class="table-border-bottom">
							<td colspan="2" class="sub">Social Share Optimization (SSO)<div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/social-share-optimization-sso/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold">Default share image:<br /> <span class="label"
								style="font-size: 400;">Default share image will be used when
									page or post doesn't have featured image or custom setting for
									share image. </span></td>
							<td><?php essb_sso_default_share_image(); ?>
						
						
						</tr>
												<tr class="odd table-border-bottom">
							<td class="bold">Extract full content when generating description:<br /> <span class="label"
								style="font-size: 400;">If you see shortcodes in your description activate this option to extract as full rendered content. </span></td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('sso_apply_the_content'); ?>
						
						
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-9-1">
							<td colspan="2" class="sub2">Facebook Open Graph</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Automatically generate and insert
								open graph meta tags for post/pages:<br /> <span class="label"
								style="font-size: 400;">Open Graph meta tags are used to
									optimize social sharing. This option will include following
									tags <b>og:title, og:description, og:url, og:image, og:type,
										og:site_name</b>.
							</span>
							</td>
							<td class="essb_general_options"><?php essb_opengraph_tags(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Facebook Page URL:<br /> <span
								class="label" style="font-size: 400;"> </span>
							</td>
							<td class="essb_general_options"><?php essb_sso_facebook_page(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Facebook Admins:<br /> <span
								class="label" style="font-size: 400;">Enter IDs of Facebook
									Users that are admins of current page. </span>
							</td>
							<td class="essb_general_options"><?php essb_sso_facebook_admins(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Facebook Application ID:<br /> <span
								class="label" style="font-size: 400;">Enter ID of Facebook
									Application to be able to use Facebook Insights </span>
							</td>
							<td class="essb_general_options"><?php essb_sso_facebook_appid(); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-9-2">
							<td colspan="2" class="sub2">Twitter Cards</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Automatically generate and insert
								Twitter Cards meta tags for post/pages:<br /> <span
								class="label" style="font-size: 400;"> </span>
							</td>
							<td class="essb_general_options"><?php essb_sso_twitter_card(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Twitter Site Username:<br /> <span
								class="label" style="font-size: 400;"> </span>
							</td>
							<td class="essb_general_options"><?php essb_sso_twitter_card_user(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Default Twitter Card type:<br /> <span
								class="label" style="font-size: 400;"> </span>
							</td>
							<td class="essb_general_options"><?php essb_sso_twitter_card_type(); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-9-3">
							<td colspan="2" class="sub2">Google Schema.org</td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Activate Google Authorship and Publisher Markup:<br/> <span
								class="label" style="font-size: 400;">When active Google Authorship will appear only on posts from your blog - usage of authorship require you to sign up to Google Authoship program at this address: <a href="https://plus.google.com/authorship">https://plus.google.com/authorship</a>. Publisher markup will be included on all pages and posts where it is activated.</span></td>
							<td class="essb_general_options">
							<?php ESSB_Settings_Helper::drawCheckboxField('sso_google_author');?>
							</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Google+ Author Page:<br /> <span
								class="label" style="font-size: 400;">Put link to your Goolge+ Profile (example: https://plus.google.com/[Google+_Profile]/posts) </span>
							</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawInputField('ss_google_author_profile'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Google+ Publisher Page:<br /> <span
								class="label" style="font-size: 400;">Put link to your Google+ Page (example: https://plus.google.com/[Google+_Page_Profile]) </span>
							</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawInputField('ss_google_author_publisher'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Include Google Schema.org base markup:<br/> <span
								class="label" style="font-size: 400;">This will include minimal needed markup for Google schema.org (name, descriptio and image) </span></td>
							<td class="essb_general_options">
							<?php ESSB_Settings_Helper::drawCheckboxField('sso_google_markup');?>
							</td>
						</tr>
						</table>
				</div>
				<div id="essb-container-3" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Social Like, Follow and Subscribe Buttons', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/social-like-and-subscribe-buttons/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="even">
							<td class="bold" valign="top">Display on same line:<br/><span class="label" style="font-weight: 400;">Activating this option will display native
								social network buttons on same line with share buttons.</span></td>
							<td><?php essb_display_other_onsame(); ?></td>
						</tr>						
						<tr class="odd">
							<td valign="top" class="bold">Apply native buttons skin:<br/><span class="label" style="font-weight: 400;">This option will hide native buttons inside nice flat style boxes and show them on hover.</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('skin_native'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Native buttons skin:</td>
							<td class="essb_general_options"><?php 
							
							$skin_list = array("flat" => "Flat", "metro" => "Metro");
							ESSB_Settings_Helper::drawSelectField('skin_native_skin', $skin_list);
							
							?></td>
						</tr>
						<tr class="odd">
							<td valign="top" class="bold">Display counters for native buttons:</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('allnative_counters'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-1">
							<td colspan="2" class="sub2">Facebook Like/Follow Button</td>
						</tr>
						<tr class="even">
							<td valign="top" class="bold">Facebook Like/Follow Button</td>
							<td class="essb_general_options"><?php essb_facebook_likebutton(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td></td>
							<td class="small">According to Facebook Policy Like button must
								not be modified! Turning this options will include default Like
								button from Facebook Social Plugins.
						
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Facebook Like/Follow button type:</td>
							<td><?php 
							$listOfOptions = array("like", "follow");
							ESSB_Settings_Helper::drawSelectField('facebook_like_type', $listOfOptions, true); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Facebook Follow Profile Page URL:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('facebook_follow_profile', true); ?></td>
						</tr>
						
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_fb_color'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_fb_hovercolor'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_fb_textcolor'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_fb_width'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_fb_text'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-2">
							<td colspan="2" class="sub2">Google +1/Follow Button</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Google Plus/Follow Button</td>
							<td class="essb_general_options"><?php essb_plusone_button(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Google Plus/Follow button type:</td>
							<td><?php 
							$listOfOptions = array("plus", "follow");
							ESSB_Settings_Helper::drawSelectField('google_like_type', $listOfOptions, true); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Google+ Profile Page URL:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('google_follow_profile', true); ?></td>
						</tr>
						
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_google_color'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_google_hovercolor'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_google_textcolor'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_google_width'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_google_text'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-3">
							<td colspan="2" class="sub2">VKontankte (vk.com) Like</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">VKontakte (vk.com) Like Button:</td>
							<td class="essb_general_options"><?php essb_vklike_button(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">VKontakte (vk.com) Application ID:</td>
							<td class="essb_general_options"><?php essb_vklike_button_appid(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_vk_color'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_vk_hovercolor'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_vk_textcolor'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_vk_width'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_vk_text'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-4">
							<td colspan="2" class="sub2">Twitter Tweet/Follow</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Twitter Tweet/Follow Button:</td>
							<td class="essb_general_options"><?php essb_twitter_follow_button(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Twitter Button Type:<br /><span class="label" style="font-size: 400;">Choose which button you wish to display Tweet or Follow.</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawSelectField('twitter_tweet', array('follow', 'tweet'), true); ?></td>
						</tr>
						
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Twitter Follow User:</td>
							<td class="essb_general_options"><?php essb_twitter_follow_button_user(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_twitter_color'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_twitter_hovercolor'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_twitter_textcolor'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_twitter_width'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_twitter_text'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-5">
							<td colspan="2" class="sub2">YouTube Subscribe</td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">YouTube Subscribe:</td>
							<td class="essb_general_options"><?php essb_youtube_subscribe(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Channel Name:</td>
							<td class="essb_general_options"><?php essb_youtube_subscribe_channel(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_youtube_color'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_youtube_hovercolor'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_youtube_textcolor'); ?></td>
						</tr>						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_youtube_width'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_youtube_text'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-6">
							<td colspan="2" class="sub2">Pinterest Follow/Pin</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Pinterest Follow/Pin Buttons:</td>
							<td class="essb_general_options"><?php essb_pinterest_follow(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Pinterest Button Type:<br /><span class="label" style="font-size: 400;">Choose which button you wish to display Pin or Follow.</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawSelectField('pinterest_native_type', array('follow', 'pin'), true); ?></td>
						</tr>
						
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Display name on button:</td>
							<td class="essb_general_options"><?php essb_pinterest_follow_display(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Pinterest User URL:</td>
							<td class="essb_general_options"><?php essb_pinterest_follow_url(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_pinterest_color'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_pinterest_hovercolor'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_pinterest_textcolor'); ?></td>
						</tr>						
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_pinterest_width'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_pinterest_text'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-7">
							<td colspan="2" class="sub2">LikendIn Company Follow</td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">LinkedIn Follow:</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('linkedin_follow'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">LinkedIn CompanyID:</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawInputField('linkedin_follow_id'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_linkedin_color'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button hover color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_linkedin_hovercolor'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text color replace:</td>
							<td><?php ESSB_Settings_Helper::drawColorField('skinned_linkedin_textcolor'); ?></td>
						</tr>						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Skinned button width replace: <br/><span style="font-style: normal" class="label"> (number value in pixels - without px)</span></td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_linkedin_width'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Skinned button text replace:</td>
							<td><?php ESSB_Settings_Helper::drawInputField('skinned_linkedin_text'); ?></td>
						</tr>
						
						<tr class="table-border-bottom" id="essb-submenu-3-8">
							<td colspan="2" class="sub2">ManagedWP.org Upvote Button</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Activate ManagedWP.org upvote button:</td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('managedwp_button'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-3-9">
							<td colspan="2" class="sub2"><?php essb_custom_like_address(); ?>Activate Custom Like Address</td>
						</tr>

						<tr class="even table-border-bottom">
							<td colspan="2" class="label"><i class="fa fa-info-circle fa-lg"></i><span
								class="label">&nbsp;This option allows you to send different
									address for native social network buttons to like. If you have
									activated custom share message this will overwrite address you
									send from custom share message options but only for social
									network native buttons.</span></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="middle">Address for Facebook Like
								Button:<br /> <span class="label" style="font-weight: 400;">This
									can be your Facebook Fan Page url or other url address you wish
									people to like.</span>
							</td>
							<td><?php essb_custom_like_address_url(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="middle">Address for Google +1 Button:<br />
								<span class="label" style="font-weight: 400;">This can be your
									Google +1 Page url or other url address you wish people to
									like.</span></td>
							<td><?php essb_custom_plusone_address_url(); ?></td>
						</tr>
					</table>
				</div>
				<div id="essb-container-4" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('URL Shortener', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/url-shortener/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="label" colspan="2"><i class="fa fa-info-circle fa-lg"></i><span
								class="label">&nbsp;Using shortlinks will generate unique
									shortlinks for pages/posts. If you have shared till now full
									address of you current post/page using shortlink will make
									counters of sharing to start from 0.</span></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Use wp_get_shortlink():<br /> <span
								class="label" style="font-weight: 400;">If you wish to share
									shortlink for your post or pages using build in WordPress
									shortlink function activate this option.</span></td>
							<td class="essb_general_options"><?php essb_url_short_native(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Use goo.gl to generate short URL:<br />
								<span class="label" style="font-weight: 400;">If you wish to use
									goo.gl service to generate shortlinks for your pages or posts
									activate this option.</span></td>
							<td class="essb_general_options"><?php essb_url_short_google(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Use bit.ly to generate short URL:<br />
								<span class="label" style="font-weight: 400;">If you wish to use
									bit.ly service to generate shortlinks for your pages or posts
									activate this option. </span></td>
							<td class="essb_general_options"><?php essb_url_short_bitly(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">bit.ly user:<br />
							</td>
							<td class="essb_general_options"><?php essb_url_short_bitly_user(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">bit.ly api key:<br />
							</td>
							<td class="essb_general_options"><?php essb_url_short_bitly_api(); ?></td>
						</tr>
					</table>
				</div>
				<div id="essb-container-5" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php essb_customshare_message(); _e('Custom Share Message', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/custom-share-message/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Custom Share Message:<br /> <span
								class="label" style="font-weight: 400;">This option allows you
									to pass custom message to share (not all networks support
									this).</span></td>
							<td class="essb_general_options"><?php essb_customshare_message_text(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Custom Share URL:<br /> <span
								class="label" style="font-weight: 400;">This option allows you
									to pass custom url to share (all networks support this).</span></td>
							<td class="essb_general_options"><?php essb_customshare_message_url(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Custom Share Image URL:<br /> <span
								class="label" style="font-weight: 400;">This option allows you
									to pass custom image to your share message (only Facebok and
									Pinterest support this).</span></td>
							<td class="essb_general_options"><?php essb_customshare_message_imageurl(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Custom Share Description:<br /> <span
								class="label" style="font-weight: 400;">This option allows you
									to pass custom extended description to your share message (only
									Facebok and Pinterest support this).</span></td>
							<td class="essb_general_options"><?php essb_customshare_message_description(); ?></td>
						</tr>
					</table>
				</div>
				<div id="essb-container-6" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Customize E-mail Message', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/customize-e-mail-message/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr>
							<td class="label" colspan="2"><i class="fa fa-info-circle fa-lg"></i><span
								class="label">&nbsp;<?php _e('You can customize texts to display when visitors share your content by mail button. To perform customization, you can use <span class="bold">%%title%%</span>, <span class="bold">%%siteurl%%</span>, <span class="bold">%%permalink%%</span> or <span class="bold">%%image%%</span> variables.', ESSB_TEXT_DOMAIN); ?></span></td>
						</tr>

						<tr class="odd table-border-bottom" style="display: none;">
							<td class="bold" valign="top">Use short url in mail message when short url option is active:<br/><span class="label">This will provide in mail message short url instead of full permalink when one of global short url options is active.</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('mail_shorturl');?></td>
							
						</tr>
						
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Send to mail button function:', ESSB_TEXT_DOMAIN); ?><br/><span class="label" style="font-weight: normal;">Choose how you wish mail button to operate. By default it uses the build in popup window with sendmail option but you can change this to link option to force use of client mail program.</span></td>
							<td><?php 

							$listOfOptions = array("form" => "Send mail using popup form", "link" => "Send mail using mailto link and user mail client");
							ESSB_Settings_Helper::drawSelectField('mail_funcion', $listOfOptions);
							
							?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Message subject:', ESSB_TEXT_DOMAIN); ?></td>
							<td><?php essb_setting_input_mail_subject(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Message body:', ESSB_TEXT_DOMAIN); ?></td>
							<td><?php essb_setting_textarea_mail_body(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Send copy of all messages to:</td>
							<td><?php essb_setting_textarea_mail_copyaddress(); ?></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2"><?php _e('Localization', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Localize Mail Form:</td>
							<td class="essb_options_general"><?php essb_localize_mail_form();?></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2"><?php _e('Activate AntiSpam captcha verification', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Captcha Message:<br/><span class="label" style="font-weight: normal;">Enter captcha question you wish to ask users to validate that they are human.</span></td>
							<td><?php essb_setting_input_mail_captcha(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Captcha Answer:<Br/><span class="label" style="font-weight: normal;">Enter answer you wish users to put to verify them.</span></td>
							<td><?php essb_setting_input_mail_captcha_answer(); ?></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2"><?php _e('Send mail settings', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Use wpMandrill for send mail:<br/><span class="label" style="font-style: normal;">To be able to send messages with <a href="http://wordpress.org/plugins/wpmandrill/" target="_blank">wpMandrill</a> you need to have plugin installed.</span></td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('use_wpmandrill'); ?></td>
						</tr>
						
					</table>
				</div>
				<div id="essb-container-7" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr class="table-border-bottom" id="essb-submenu-7-1">
							<td colspan="2" class="sub"><?php _e('Administrative Options', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/administrative-options/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Disable menu in WordPress admin
								bar:</td>
							<td><?php essb_disable_adminbar_menu(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Move plugin options under settings
								menu:</td>
							<td><?php essb_register_pluginsettings_under_settings(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Turn off Easy Social Share Buttons Advanced settings:</td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('turnoff_essb_advanced_box'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">I am using Google Analytics for WordPress by Yoast:<br/><div class="essb-recommended"><i class="fa fa-check"></i><span></span></div></td>
							<td><?php essb_fix_using_yoast_ga(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Avoid &lt;!--nextpage--&gt; and always share main post address:</td>
							<td><?php  ESSB_Settings_Helper::drawCheckboxField('avoid_nextpage'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Clean buttons from excerpts:<br/><span class="label" style="font-weight: 400;">Activate this option to avoid buttons included in excerpts as text FacebookTwiiter and so.</span></td>
							<td><?php  ESSB_Settings_Helper::drawCheckboxField('apply_clean_buttons'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Force get of current post/page:<br/><span class="label" style="font-weight: 400;">Activate this option if share doest not get correct page.</span></td>
							<td><?php  ESSB_Settings_Helper::drawCheckboxField('force_wp_query_postid'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Change default priority of buttons:<br/><span class="label" style="font-weight: 400;">Provide custom value of priority when buttons will be included in content (default is 10). This will make code of plugin to execute before or after another plugin. Attention! Providing incorrect value may cause buttons not to display.</span></td>
							<td><?php  ESSB_Settings_Helper::drawInputField('priority_of_buttons'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Remove version number from script and css files:<br/><span class="label" style="font-weight: 400;">Activating this option will remove added to resources version number ?ver= which will allow this files to be cached.</span></td>
							<td><?php  ESSB_Settings_Helper::drawCheckboxField('remove_ver_resource'); ?></td>
						</tr>
						<tr class="table-border-bottom" id="essb-submenu-7-2">
							<td colspan="2" class="sub" style="padding-top: 10px;"><?php _e('Optimization Options', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Load plugin javascript files asynchronous (beta):<br/>
							<span class="label" style="font-weight: normal;">This is option is in beta and if you found any problems using it please report at our <a href="http://support.creoworx.com" target="_blank">support portal</a>.</span></td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('load_js_async'); ?></td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2"><?php _e('Turn off build in modules', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"></td>
							<td class="label">You can turn off build in modules that you do not use. This will make plugin to work faster. You can turn back on modules at any time.</td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Turn off Social Fans Counter:</td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('module_off_sfc'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Turn off Love This:</td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('module_off_lv'); ?></td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2"><?php _e('Reset configuration to default values', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td></td>
							<td class="essb_options_general"><a href="admin.php?page=essb_settings&tab=general&reset=true" class="button">I want to reset configuration to default plugin values</a><br/><br/>
							<span class="label">Warning! Pressing this button will restore initial plugin configuration values and all setttings that you apply after plugin activation will be removed.</span>
							
						</tr>
						</table>
				</div>
				<div id="essb-container-8" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr class="table-border-bottom">
							<td colspan="2" class="sub"><?php _e('Advanced Custom Share', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/advanced-custom-share/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr>
							<td class="label" colspan="2"><i class="fa fa-info-circle fa-lg"></i><span
								class="label">&nbsp; Advanced Custom Share is option which
									allows you set different share address for each social network.
									Please note that not all networks support full customization of
									messages. Setting parameters for social netwrok will have
									highest priority for custom sharing and will overwrite settings
									from custom share message option.</span></td>
						</tr>
					<?php essb_setting_advanced_network_share(); ?>
					
				</table>


				</div>

				<div id="essb-container-10" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr class="table-border-bottom">
							<td colspan="2" class="sub"><?php _e('Social Fans Counter', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/social-fans-counter/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr>
						<td class="label" colspan="2"><i class="fa fa-info-circle fa-lg"></i><span
								class="label">&nbsp; If you need help how to configure social fans counter module of Easy Social Share Buttons for WordPress <a href="http://fb.creoworx.com/essb/easy-social-fans-counter-configuration/" target="_blank">open this tutorial</a>.</span>
								<br/><br/><br/>
								Social Fans Counter can be insert as Widget (Easy Social Fans Counter Widget) or with shortcode <strong>[essb-fans]</strong> where you wish to display it. Shortcode can be used with following options:
								<ul>
								<li><strong>style</strong> - this is template of buttons. You can choose between: mutted, colored, flat, metro</li>
								<li><strong>cols</strong> - choose how many columns to be used in redner. You can choose between 1,2,3,4</li>
								<li><strong>width</strong> - instead of cols you can set exact width of buttons. For example you may enter 100 for 100px</li>
								</ul>
								<strong>[essb-fans style="flat" cols="4"]</strong>
								</td>
						</tr>
						
						</tr>
						<tr>
							<td colspan="2" class="sub2"><?php _e('General Option', ESSB_TEXT_DOMAIN); ?></td>

						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><?php _e( 'Drag and Drop To Sort The Items' , ESSB_TEXT_DOMAIN ) ?></td>
														<td>
							<ul id="essb-fans-sortables" style="cursor: pointer;">
								<?php
									if (isset($essb_fans)) {
									if( !empty( $essb_fans->options['sort'] ) )
										$network_sort = $essb_fans->options['sort'];
										
									if( empty( $essb_fans->options['sort'] ) || !is_array($network_sort) || $essb_fans->essb_supported_items != array_intersect($essb_fans->essb_supported_items , $network_sort ) ){
										$network_sort = $essb_fans->essb_supported_items ;
									}
									foreach ( $network_sort as $network ){ ?>
										<li style="padding: 5px; border-bottom: 1px dotted #999;"><strong><?php echo $network; ?></strong><input type="hidden" name="sort[]" class="code" id="social[]" value="<?php echo $network; ?>"></li>
								<?php }
									} ?>
							</ul>
							
							</td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><?php _e( 'Cache Time' , ESSB_TEXT_DOMAIN ) ?></td>
							<td class="essb_general_options"><input type="text" class="input-element" name="cache" id="cache" value="<?php if (!empty($essb_fans->options['cache'])) { print $essb_fans->options['cache']; } ?>" /></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-1"><?php _e('Facebook', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[facebook][id]"><?php _e( 'Page ID/Name' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[facebook][id]" class="code"
								id="social[facebook][id]"
								value="<?php if( !empty($essb_fans->options['social']['facebook']['id']) ) echo $essb_fans->options['social']['facebook']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[facebook][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[facebook][text]" class="code"
								id="social[facebook][text]"
								value="<?php if( !empty($essb_fans->options['social']['facebook']['text']) ) echo $essb_fans->options['social']['facebook']['text'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-2"><?php _e('Twitter', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[twitter][id]" class="code"
								id="social[twitter][id]"
								value="<?php if( !empty($essb_fans->options['social']['twitter']['id']) ) echo $essb_fans->options['social']['twitter']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[twitter][text]" class="code"
								id="social[twitter][text]"
								value="<?php if( !empty($essb_fans->options['social']['twitter']['text']) ) echo $essb_fans->options['social']['twitter']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][type]"><?php _e( 'Extract data:' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[twitter][type]" class="code"
								id="social[twitter][type]">
								<?php 
																			$google_type = array ('With API Keys','Without API keys' );
											foreach ( $google_type as $type ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['twitter']['type']) && $essb_fans->options['social']['twitter']['type'] == $type ) echo'selected="selected"'?>
										value="<?php echo $type ?>"><?php echo $type ?></option>
											<?php } ?>
								
								</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][key]"><?php _e( 'Application/Consumer key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[twitter][key]" class="code"
								id="social[twitter][key]"
								value="<?php if( !empty($essb_fans->options['social']['twitter']['key']) ) echo $essb_fans->options['social']['twitter']['key'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][secret]"><?php _e( 'Application/Consumer secret' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[twitter][secret]"
								class="code" id="social[twitter][secret]"
								value="<?php if( !empty($essb_fans->options['social']['twitter']['secret']) ) echo $essb_fans->options['social']['twitter']['secret'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][key]"><?php _e( 'Access token' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[twitter][token]" class="code"
								id="social[twitter][token]"
								value="<?php if( !empty($essb_fans->options['social']['twitter']['token']) ) echo $essb_fans->options['social']['twitter']['token'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[twitter][secret]"><?php _e( 'Access token secret' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[twitter][tokensecret]"
								class="code" id="social[twitter][tokensecret]"
								value="<?php if( !empty($essb_fans->options['social']['twitter']['tokensecret']) ) echo $essb_fans->options['social']['twitter']['tokensecret'] ?>"></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-3"><?php _e('Google+', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[google][id]"><?php _e( 'Page ID/Name' , ESSB_TEXT_DOMAIN ) ?></label><br/><span class="label" style="font-weight: normal;">You can extract Google+ counter with our without using API key. Method with API key is more stable but requires providing a valid API access key. If you use access with API key you always need to provide page id or user id.</span></td>
							<td><input type="text" class="input-element stretched" name="social[google][id]" class="code"
								id="social[google][id]"
								value="<?php if( !empty($essb_fans->options['social']['google']['id']) ) echo $essb_fans->options['social']['google']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[google][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[google][text]" class="code"
								id="social[google][text]"
								value="<?php if( !empty($essb_fans->options['social']['google']['text']) ) echo $essb_fans->options['social']['google']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[google][id]"><?php _e( 'Account Type:' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[google][type]" class="code"
								id="social[google][type]">
								<?php 
																			$google_type = array ('Page', 'User' );
											foreach ( $google_type as $type ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['google']['type']) && $essb_fans->options['social']['google']['type'] == $type ) echo'selected="selected"'?>
										value="<?php echo $type ?>"><?php echo $type ?></option>
											<?php } ?>
								
								</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[google][api]"><?php _e( 'Get counts using my Google+ API key:' , ESSB_TEXT_DOMAIN ) ?></label><br/><span class="label" style="font-weight: normal;">You can extract counters without using API key but method with API key is stable and always returns correct value. See <a href="http://fb.creoworx.com/essb/easy-social-fans-counter-configuration/" target="_blank">settings tutorial</a> on how to generate Google+ API access key.</span></td>
							<td><input type="text" class="input-element stretched" name="social[google][api]" class="code"
								id="social[google][text]"
								value="<?php if( !empty($essb_fans->options['social']['google']['api']) ) echo $essb_fans->options['social']['google']['api'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[google][counter_type]"><?php _e( 'Select Google+ Display value only when API key is provided:' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[google][counter_type]" class="code"
								id="social[google][type]">
								<?php 
																			$google_counter_type = array ('circledByCount+plusOneCount', 'plusOneCount', 'circledByCount' );
											foreach ( $google_counter_type as $type ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['google']['counter_type']) && $essb_fans->options['social']['google']['counter_type'] == $type ) echo'selected="selected"'?>
										value="<?php echo $type ?>"><?php echo $type ?></option>
											<?php } ?>
								
								</select></td>
						</tr>
						
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-4"><?php _e('YouTube', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[youtube][id]"><?php _e( 'Username or Channel ID' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[youtube][id]" class="code"
								id="social[youtube][id]"
								value="<?php if( !empty($essb_fans->options['social']['youtube']['id']) ) echo $essb_fans->options['social']['youtube']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[youtube][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[youtube][text]" class="code"
								id="social[youtube][text]"
								value="<?php if( !empty($essb_fans->options['social']['youtube']['text']) ) echo $essb_fans->options['social']['youtube']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[youtube][type]"><?php _e( 'Type' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[youtube][type]"
								id="social[youtube][type]">
											<?php
											$youtube_type = array ('User', 'Channel' );
											foreach ( $youtube_type as $type ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['youtube']['type']) && $essb_fans->options['social']['youtube']['type'] == $type ) echo'selected="selected"'?>
										value="<?php echo $type ?>"><?php echo $type ?></option>
											<?php } ?>
											</select></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-5"><?php _e('Vimeo', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[vimeo][id]"><?php _e( 'Channel Name' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[vimeo][id]" class="code"
								id="social[vimeo][id]"
								value="<?php if( !empty($essb_fans->options['social']['vimeo']['id']) ) echo $essb_fans->options['social']['vimeo']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[vimeo][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[vimeo][text]" class="code"
								id="social[vimeo][text]"
								value="<?php if( !empty($essb_fans->options['social']['vimeo']['text']) ) echo $essb_fans->options['social']['vimeo']['text'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-6"><?php _e('Dribbble', ESSB_TEXT_DOMAIN); ?></td>
						</tr>

						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[dribbble][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[dribbble][id]" class="code"
								id="social[dribbble][id]"
								value="<?php if( !empty($essb_fans->options['social']['dribbble']['id']) ) echo $essb_fans->options['social']['dribbble']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[dribbble][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[dribbble][text]" class="code"
								id="social[dribbble][text]"
								value="<?php if( !empty($essb_fans->options['social']['dribbble']['text']) ) echo $essb_fans->options['social']['dribbble']['text'] ?>"></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-7"><?php _e('Github', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[github][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[github][id]" class="code"
								id="social[github][id]"
								value="<?php if( !empty($essb_fans->options['social']['github']['id']) ) echo $essb_fans->options['social']['github']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[github][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[github][text]" class="code"
								id="social[github][text]"
								value="<?php if( !empty($essb_fans->options['social']['github']['text']) ) echo $essb_fans->options['social']['github']['text'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-8"><?php _e('Envato', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[envato][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[envato][id]" class="code"
								id="social[envato][id]"
								value="<?php if( !empty($essb_fans->options['social']['envato']['id']) ) echo $essb_fans->options['social']['envato']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[envato][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[envato][text]" class="code"
								id="social[envato][text]"
								value="<?php if( !empty($essb_fans->options['social']['envato']['text']) ) echo $essb_fans->options['social']['envato']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[envato][site]"><?php _e( 'Marketplace' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[envato][site]" id="social[envato][site]">
											<?php
											$envato_markets = array ('3docean', 'activeden', 'audiojungle', 'codecanyon', 'graphicriver', 'photodune', 'themeforest', 'videohive' );
											foreach ( $envato_markets as $market ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['envato']['site']) && $essb_fans->options['social']['envato']['site'] == $market ) echo'selected="selected"'?>
										value="<?php echo $market ?>"><?php echo $market ?></option>
											<?php } ?>
											</select></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-9"><?php _e('SoundCloud', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[soundcloud][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[soundcloud][id]" class="code"
								id="social[soundcloud][id]"
								value="<?php if( !empty($essb_fans->options['social']['soundcloud']['id']) ) echo $essb_fans->options['social']['soundcloud']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[soundcloud][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[soundcloud][text]"
								class="code" id="social[soundcloud][text]"
								value="<?php if( !empty($essb_fans->options['social']['soundcloud']['text']) ) echo $essb_fans->options['social']['soundcloud']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[soundcloud][api]"><?php _e( 'API Key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[soundcloud][api]"
								class="code" id="social[soundcloud][api]"
								value="<?php if( !empty($essb_fans->options['social']['soundcloud']['api']) ) echo $essb_fans->options['social']['soundcloud']['api'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-11"><?php _e('Behance', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[behance][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[behance][id]" class="code"
								id="social[behance][id]"
								value="<?php if( !empty($essb_fans->options['social']['behance']['id']) ) echo $essb_fans->options['social']['behance']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[behance][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[behance][text]" class="code"
								id="social[behance][text]"
								value="<?php if( !empty($essb_fans->options['social']['behance']['text']) ) echo $essb_fans->options['social']['behance']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[behance][api]"><?php _e( 'API Key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[behance][api]" class="code"
								id="social[behance][api]"
								value="<?php if( !empty($essb_fans->options['social']['behance']['api']) ) echo $essb_fans->options['social']['behance']['api'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-12"><?php _e('Delicious', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[delicious][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[delicious][id]" class="code"
								id="social[delicious][id]"
								value="<?php if( !empty($essb_fans->options['social']['delicious']['id']) ) echo $essb_fans->options['social']['delicious']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[delicious][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[delicious][text]"
								class="code" id="social[delicious][text]"
								value="<?php if( !empty($essb_fans->options['social']['delicious']['text']) ) echo $essb_fans->options['social']['delicious']['text'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-13"><?php _e('Instagram', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[instagram][id]"><?php _e( 'UserID.UserName:' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[instagram][id]" class="code"
								id="social[instagram][id]"
								value="<?php if( !empty($essb_fans->options['social']['instagram']['id']) ) echo $essb_fans->options['social']['instagram']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[instagram][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[instagram][text]"
								class="code" id="social[instagram][text]"
								value="<?php if( !empty($essb_fans->options['social']['instagram']['text']) ) echo $essb_fans->options['social']['instagram']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[instagram][api]"><?php _e( 'Access Token Key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[instagram][api]" class="code"
								id="social[instagram][api]"
								value="<?php if( !empty($essb_fans->options['social']['instagram']['api']) ) echo $essb_fans->options['social']['instagram']['api'] ?>"></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-14"><?php _e('Pinterest', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[pinterest][id]"><?php _e( 'UserName' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[pinterest][id]" class="code"
								id="social[pinterest][id]"
								value="<?php if( !empty($essb_fans->options['social']['pinterest']['id']) ) echo $essb_fans->options['social']['pinterest']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[pinterest][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[pinterest][text]"
								class="code" id="social[pinterest][text]"
								value="<?php if( !empty($essb_fans->options['social']['pinterest']['text']) ) echo $essb_fans->options['social']['pinterest']['text'] ?>"></td>
						</tr>
						
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-15"><?php _e('Love This', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[love][id]"><?php _e( 'Activate' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[love][id]" id="social[love][id]">
											<?php
											$love_state = array ('No', 'Yes');
											foreach ( $love_state as $market ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['love']['id']) && $essb_fans->options['social']['love']['id'] == $market ) echo'selected="selected"'?>
										value="<?php echo $market ?>"><?php echo $market ?></option>
											<?php } ?>
											</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[love][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[love][text]"
								class="code" id="social[love][text]"
								value="<?php if( !empty($essb_fans->options['social']['love']['text']) ) echo $essb_fans->options['social']['love']['text'] ?>"></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-16"><?php _e('VK.com', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[vk][id]"><?php _e( 'Community ID/Name' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[vk][id]" class="code"
								id="social[vk][id]"
								value="<?php if( !empty($essb_fans->options['social']['vk']['id']) ) echo $essb_fans->options['social']['vk']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[vk][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[vk][text]"
								class="code" id="social[vk][text]"
								value="<?php if( !empty($essb_fans->options['social']['vk']['text']) ) echo $essb_fans->options['social']['vk']['text'] ?>"></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-17"><?php _e('RSS', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[rss][id]"><?php _e( 'Feed URL' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[rss][id]" class="code"
								id="social[rss][id]"
								value="<?php if( !empty($essb_fans->options['social']['rss']['id']) ) echo $essb_fans->options['social']['rss']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[rss][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[rss][text]"
								class="code" id="social[rss][text]"
								value="<?php if( !empty($essb_fans->options['social']['rss']['text']) ) echo $essb_fans->options['social']['rss']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[rss][type]"><?php _e( 'Type' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[rss][type]" id="social[rss][type]">
											<?php
											$love_state = array ('feedpress.it', 'manual');
											foreach ( $love_state as $market ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['rss']['type']) && $essb_fans->options['social']['rss']['type'] == $market ) echo'selected="selected"'?>
										value="<?php echo $market ?>"><?php echo $market ?></option>
											<?php } ?>
											</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[rss][manual]"><?php _e( 'Manual Value' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[rss][manual]"
								class="code" id="social[rss][manual]"
								value="<?php if( !empty($essb_fans->options['social']['rss']['manual']) ) echo $essb_fans->options['social']['rss']['manual'] ?>"></td>
						</tr>

						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-18"><?php _e('MailChimp', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[mailchimp][id]"><?php _e( 'List ID' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[mailchimp][id]" class="code"
								id="social[mailchimp][id]"
								value="<?php if( !empty($essb_fans->options['social']['mailchimp']['id']) ) echo $essb_fans->options['social']['mailchimp']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[mailchimp][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[mailchimp][text]"
								class="code" id="social[mailchimp][text]"
								value="<?php if( !empty($essb_fans->options['social']['mailchimp']['text']) ) echo $essb_fans->options['social']['mailchimp']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[mailchimp][url]"><?php _e( 'List URL' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[mailchimp][url]"
								class="code" id="social[mailchimp][url]"
								value="<?php if( !empty($essb_fans->options['social']['mailchimp']['url']) ) echo $essb_fans->options['social']['mailchimp']['url'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[mailchimp][api]"><?php _e( 'API Key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[mailchimp][api]"
								class="code" id="social[mailchimp][api]"
								value="<?php if( !empty($essb_fans->options['social']['mailchimp']['api']) ) echo $essb_fans->options['social']['mailchimp']['api'] ?>"></td>
						</tr>
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-19"><?php _e('LinkedIn', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[linkedin][id]"><?php _e( 'Comapny ID' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[linkedin][id]" class="code"
								id="social[linkedin][id]"
								value="<?php if( !empty($essb_fans->options['social']['linkedin']['id']) ) echo $essb_fans->options['social']['linkedin']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[linkedin][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[linkedin][text]"
								class="code" id="social[linkedin][text]"
								value="<?php if( !empty($essb_fans->options['social']['linkedin']['text']) ) echo $essb_fans->options['social']['linkedin']['text'] ?>"></td>
						</tr>
						
						<tr class="even table-border-bottom" >
							<td class="bold" valign="top"><label for="social[linkedin][api]"><?php _e( 'APP Key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[linkedin][api]"
								class="code" id="social[linkedin][api]"
								value="<?php if( !empty($essb_fans->options['social']['linkedin']['api']) ) echo $essb_fans->options['social']['linkedin']['api'] ?>"/>
							</td>
						</tr>						
						<tr class="odd table-border-bottom" >
							<td class="bold" valign="top"><label for="social[linkedin][apps]"><?php _e( 'APP Secret' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[linkedin][apps]"
								class="code" id="social[linkedin][apps]"
								value="<?php if( !empty($essb_fans->options['social']['linkedin']['apps']) ) echo $essb_fans->options['social']['linkedin']['apps'] ?>"/>								
							</td>
						</tr>		
						<tr>
							<td colspan="2" class="sub2" id="essb-submenu-10-21"><?php _e('Tumblr', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[tumblr][id]"><?php _e( 'Blog Host Name' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[tumblr][id]" class="code"
								id="social[tumblr][id]"
								value="<?php if( !empty($essb_fans->options['social']['tumblr']['id']) ) echo $essb_fans->options['social']['tumblr']['id'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[tumblr][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[tumblr][text]" class="code"
								id="social[tumblr][text]"
								value="<?php if( !empty($essb_fans->options['social']['tumblr']['text']) ) echo $essb_fans->options['social']['tumblr']['text'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[tumblr][key]"><?php _e( 'Consumer key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[tumblr][key]" class="code"
								id="social[tumblr][key]"
								value="<?php if( !empty($essb_fans->options['social']['tumblr']['key']) ) echo $essb_fans->options['social']['tumblr']['key'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[tumblr][secret]"><?php _e( 'Secret key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[tumblr][secret]"
								class="code" id="social[tumblr][secret]"
								value="<?php if( !empty($essb_fans->options['social']['tumblr']['secret']) ) echo $essb_fans->options['social']['tumblr']['secret'] ?>"></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[tumblr][key]"><?php _e( 'Access token key' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[tumblr][token]" class="code"
								id="social[tumblr][token]"
								value="<?php if( !empty($essb_fans->options['social']['tumblr']['token']) ) echo $essb_fans->options['social']['tumblr']['token'] ?>"></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[tumblr][secret]"><?php _e( 'Access token secret' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[tumblr][tokensecret]"
								class="code" id="social[tumblr][tokensecret]"
								value="<?php if( !empty($essb_fans->options['social']['tumblr']['tokensecret']) ) echo $essb_fans->options['social']['tumblr']['tokensecret'] ?>"></td>
						</tr>
						
						<tr class="even table-border-bottom" >
							<td class="bold" valign="top"></td>
							<td><a href="https://www.tumblr.com/oauth/apps" class="button" target="_blank">Go to developer center to get your application key</a>&nbsp;<a href="http://fb.creoworx.com/tumblr/" class="button" target="_blank">Go here to get your access token key and secret.</a>
							</td>
						</tr>						
						<tr id="essb-submenu-10-20">
							<td colspan="2" class="sub2"><?php _e('Posts Counter', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[posts][id]"><?php _e( 'Activate' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[posts][id]" id="social[posts][id]">
											<?php
											$love_state = array ('No', 'Yes');
											foreach ( $love_state as $market ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['posts']['id']) && $essb_fans->options['social']['posts']['id'] == $market ) echo'selected="selected"'?>
										value="<?php echo $market ?>"><?php echo $market ?></option>
											<?php } ?>
											</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[posts][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[posts][text]"
								class="code" id="social[posts][text]"
								value="<?php if( !empty($essb_fans->options['social']['posts']['text']) ) echo $essb_fans->options['social']['posts']['text'] ?>"></td>
						</tr>
						
						<tr>
							<td colspan="2" class="sub2"><?php _e('Comments Counter', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[comments][id]"><?php _e( 'Activate' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[comments][id]" id="social[comments][id]">
											<?php
											$love_state = array ('No', 'Yes');
											foreach ( $love_state as $market ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['comments']['id']) && $essb_fans->options['social']['comments']['id'] == $market ) echo'selected="selected"'?>
										value="<?php echo $market ?>"><?php echo $market ?></option>
											<?php } ?>
											</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[comments][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[comments][text]"
								class="code" id="social[comments][text]"
								value="<?php if( !empty($essb_fans->options['social']['comments']['text']) ) echo $essb_fans->options['social']['comments']['text'] ?>"></td>
						</tr>
						
						<tr>	
							<td colspan="2" class="sub2"><?php _e('Users Counter', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[users][id]"><?php _e( 'Activate' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><select class="input-element" name="social[users][id]" id="social[users][id]">
											<?php
											$love_state = array ('No', 'Yes');
											foreach ( $love_state as $market ) {
												?>
												<option
										<?php if( !empty($essb_fans->options['social']['users']['id']) && $essb_fans->options['social']['users']['id'] == $market ) echo'selected="selected"'?>
										value="<?php echo $market ?>"><?php echo $market ?></option>
											<?php } ?>
											</select></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top"><label for="social[users][text]"><?php _e( 'Text Below The Number' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[users][text]"
								class="code" id="social[users][text]"
								value="<?php if( !empty($essb_fans->options['social']['users']['text']) ) echo $essb_fans->options['social']['users']['text'] ?>"></td>
						</tr>						
						<tr class="even table-border-bottom">
							<td class="bold" valign="top"><label for="social[users][url]"><?php _e( 'Member List URL Address' , ESSB_TEXT_DOMAIN ) ?></label></td>
							<td><input type="text" class="input-element stretched" name="social[users][url]"
								class="code" id="social[users][url]"
								value="<?php if( !empty($essb_fans->options['social']['users']['url']) ) echo $essb_fans->options['social']['users']['url'] ?>"></td>
						</tr>						
						</table>


				</div>
			
				<div id="essb-container-11" class="essb-data-container">

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr class="table-border-bottom">
							<td colspan="2" class="sub"><?php _e('Easy Social Metrics Lite', ESSB_TEXT_DOMAIN); ?><div class="essb-help"><a href="http://support.creoworx.com/knowledgebase/easy-social-metrics-lite/" target="_blank" class="button essb-popup-help">Need help?</a></div></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Activate Easy Social Metrics Lite:<br /><span class="label" style="font-weight: 400;">Activate Easy Social Metrics Lite to start collect information for social shares.</span></td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('esml_active'); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Monitor following post types:<br /><span class="label" style="font-weight: 400;">Choose for which post types you want to collect information.</span></td>
							<td class="essb_general_options"><?php essb_esml_select_content_type(); ?>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Data refresh time:<br /><span class="label" style="font-weight: 400;">Length of time to store the statistics locally before downloading new data. A lower value will use more server resources. High values are recommended for blogs with over 500 posts.</span></td>
							<td><?php 
							$data_refresh = array();
							$data_refresh['1'] = '1 hour';
							$data_refresh['2'] = '2 hours';
							$data_refresh['4'] = '4 hours';
							$data_refresh['8'] = '8 hours';
							$data_refresh['12'] = '12 hours';
							$data_refresh['24'] = '24 hours';
							$data_refresh['36'] = '36 hours';
							$data_refresh['48'] = '2 days';
							$data_refresh['72'] = '3 days';
							$data_refresh['96'] = '4 days';
							$data_refresh['120'] = '5 days';
							$data_refresh['168'] = '7 days';
								
							ESSB_Settings_Helper::drawSelectField('esml_ttl', $data_refresh, true); ?></td>
						</tr>
					</table>


				</div>			
			
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#networks-sortable').sortable();
    jQuery('#essb-fans-sortables').sortable();
    <?php $section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '1'; if ($section == '') { $section = '1'; } ?>
    essb_option_activate('<?php echo $section; ?>');    

    //jQuery.lockfixed("#sticky-navigation",{offset: {top: 40, bottom: 100}});
});

</script>
    
<?php 
ESSB_Settings_Helper::registerColorSelector();
?>