<?php

function essb_update_general_settings() {
	global $_POST;
	
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
	
	$current_options ['advanced_share'] = $as;
	
	update_option ( EasySocialShareButtons::$plugin_settings_name, $current_options );
	
	$msg = __ ( "Settings are saved", ESSB_TEXT_DOMAIN );
	
	if ($current_options ['stats_active'] == 'true') {
		EasySocialShareButtons_Stats::install ();
	}
	
	// update social fans counter
	if (isset($essb_fans)) {
		$essb_fans->options['social'] = $_POST['social'];
		$essb_fans->options['sort'] = $_POST['sort'];
		$essb_fans->options['cache'] = (int) $_POST['cache'];
		$essb_fans->options['data'] = '';
	
		update_option($essb_fans->options_text ,$essb_fans->options);
		delete_transient($essb_fans->transient_text);
	}
	
}

?>