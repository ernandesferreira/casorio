<?php

$essb_options = EasySocialShareButtons_Options::get_instance();

if ( function_exists('vc_map')) {
	
		function essb_vk_checkbox_network_selection() {
			global $essb_options;
			$y = $n = '';
			$options = $essb_options->options;
			$result = array();
			if (is_array ( $options )) {
				foreach ( $options ['networks'] as $k => $v ) {
	
					$is_checked = "";
					$network_name = isset ( $v [1] ) ? $v [1] : $k;
	
					$result[$network_name.'&nbsp;&nbsp;'] = $k;
				}
	
				return $result;
			}
		}
		
		
	
		$param_obj = array(
								array(
										"type" => 'checkbox',
										"heading" => __("Social Networks", ESSB_TEXT_DOMAIN),
										"param_name" => "buttons",
										"value" => essb_vk_checkbox_network_selection(),
											
								),
								array(
										"type" => "dropdown",
										"heading" => __("Counters", ESSB_TEXT_DOMAIN),
										"param_name" => "counters",
										"value" => array(
												__("Yes", ESSB_TEXT_DOMAIN) => "1",
												__("No", ESSB_TEXT_DOMAIN) => "0"
										)
								),
								array(
										"type" => "dropdown",
										"heading" => __("Counter Position", ESSB_TEXT_DOMAIN),
										"param_name" => "counter_pos",
										"value" => array(
												__("Left", ESSB_TEXT_DOMAIN) => "left",
												__("Right", ESSB_TEXT_DOMAIN) => "right",
												__("Hidden", ESSB_TEXT_DOMAIN) => "none",
												__("Inside", ESSB_TEXT_DOMAIN) => "inside",
												__("Inside With Network Names", ESSB_TEXT_DOMAIN) => "insidename",
												__("Left Modern", ESSB_TEXT_DOMAIN) => "leftm",
												__("Right Modern", ESSB_TEXT_DOMAIN) => "rightm",
												__("Top", ESSB_TEXT_DOMAIN) => "top",
												__("Top Mini", ESSB_TEXT_DOMAIN) => "topm",
												
										)
								),
								array(
										"type" => "dropdown",
										"heading" => __("Total Counter Position", ESSB_TEXT_DOMAIN),
										"param_name" => "total_counter_pos",
										"value" => array(
												__("Left", ESSB_TEXT_DOMAIN) => "left",
												__("Right", ESSB_TEXT_DOMAIN) => "right",
												__("Right Big Numbers", ESSB_TEXT_DOMAIN) => "rightbig",
												__("Left Big Numbers", ESSB_TEXT_DOMAIN) => "leftbig",
												__("Hidden", ESSB_TEXT_DOMAIN) => "none",
										)
								),
								array(
										"type" => 'dropdown',
										"heading" => __("Hide Network Names", ESSB_TEXT_DOMAIN),
										"param_name" => "hide_names",
										"value" => array(
												__("No", ESSB_TEXT_DOMAIN) => "no",
												__("Yes and display them on hover", ESSB_TEXT_DOMAIN) => "yes",
												__("Yes and do not display them on hover (always hidden)", ESSB_TEXT_DOMAIN) => "force",)
								),
									
								array(
										"type" => 'textfield',
										"heading" => __("Custom Share URL", ESSB_TEXT_DOMAIN),
										"param_name" => "url",
								),
								array(
										"type" => 'textfield',
										"heading" => __("Custom Share Message", ESSB_TEXT_DOMAIN),
										"param_name" => "text",
								),
								array(
										"type" => 'textfield',
										"heading" => __("Custom Share Image", ESSB_TEXT_DOMAIN),
										"param_name" => "image",
								),
								array(
										"type" => 'textfield',
										"heading" => __("Custom Share Description", ESSB_TEXT_DOMAIN),
										"param_name" => "description",
								),
									
								array(
										"type" => 'checkbox',
										"heading" => __("Full width buttons", ESSB_TEXT_DOMAIN),
										"param_name" => "fullwidth",
										"value" => array(
												__("Yes", ESSB_TEXT_DOMAIN) => "yes"
										)
								),									
								array(
										"type" => 'textfield',
										"heading" => __("Full width share buttons width (number)", ESSB_TEXT_DOMAIN),
										"param_name" => "fullwidth_fix",
								),
	
								array(
										"type" => 'checkbox',
										"heading" => __("Fixed width buttons", ESSB_TEXT_DOMAIN),
										"param_name" => "fixedwidth",
										"value" => array(
												__("Yes", ESSB_TEXT_DOMAIN) => "yes"
										)
								),									
								array(
										"type" => 'textfield',
										"heading" => __("Fixed width share buttons width (number in px without px in value)", ESSB_TEXT_DOMAIN),
										"param_name" => "fixedwidth_px",
								),
								
								array(
										"type" => 'checkbox',
										"heading" => __("Display as sidebar", ESSB_TEXT_DOMAIN),
										"param_name" => "sidebar",
										"value" => array(
												__("Yes", ESSB_TEXT_DOMAIN) => "yes"
										)
								),
								array(
										"type" => "dropdown",
										"heading" => __("Sidebar position", ESSB_TEXT_DOMAIN),
										"param_name" => "sidebar_pos",
										"value" => array(
												__("Left", ESSB_TEXT_DOMAIN) => "left",
												__("Right", ESSB_TEXT_DOMAIN) => "right",
												__("Bottom", ESSB_TEXT_DOMAIN) => "bottom",
												__("Top", ESSB_TEXT_DOMAIN) => "top",
										)
								),
								array(
										"type" => 'checkbox',
										"heading" => __("Display as popup", ESSB_TEXT_DOMAIN),
										"param_name" => "popup",
										"value" => array(
												__("Yes", ESSB_TEXT_DOMAIN) => "yes"
										)
								),
									
								array(
										"type" => 'textfield',
										"heading" => __("Popup display after (sec)", ESSB_TEXT_DOMAIN),
										"param_name" => "popafter",
								),
				
				array(
						"type" => 'checkbox',
						"heading" => __("Display as float bar", ESSB_TEXT_DOMAIN),
						"param_name" => "float",
						"value" => array(
								__("Yes", ESSB_TEXT_DOMAIN) => "yes"
						)
				),
				array(
						"type" => "dropdown",
						"heading" => __("Template", ESSB_TEXT_DOMAIN),
						"param_name" => "template",
						"value" => array(
								__("Default from settings", ESSB_TEXT_DOMAIN) => "",
								__("Default", ESSB_TEXT_DOMAIN) => "default",
								__("Metro", ESSB_TEXT_DOMAIN) => "metro",
								__("Modern", ESSB_TEXT_DOMAIN) => "modern",
								__("Round", ESSB_TEXT_DOMAIN) => "round",
								__("Big", ESSB_TEXT_DOMAIN) => "big",
								__("Big (Retina)", ESSB_TEXT_DOMAIN) => "big-retina",
								__("Metro (Retina)", ESSB_TEXT_DOMAIN) => "metro-retina",
								__("Light (Retina)", ESSB_TEXT_DOMAIN) => "light-retina",
								__("Flat (Retina)", ESSB_TEXT_DOMAIN) => "flat-retina",
								__("Tiny (Retina)", ESSB_TEXT_DOMAIN) => "tiny-retina",
								__("Round (Retina)", ESSB_TEXT_DOMAIN) => "round-retina",
								__("Modern (Retina)", ESSB_TEXT_DOMAIN) => "modern-retina",
						)
				),
				
						);
		
		$options =  $essb_options->options;
		if (is_array ( $options )) {
			foreach ( $options ['networks'] as $k => $v ) {
		
				$network_name = isset ( $v [1] ) ? $v [1] : $k;

				$single_option = array(
							"type" => "textfield",
							"heading" => ($network_name. " custom button text"),
							"param_name" => $k."_text"
				);
				
				$param_obj[] = $single_option;
			}
		
		}
		
		vc_map(
				array(
						"name" => __("Easy Social Share Buttons", ESSB_TEXT_DOMAIN),
						"base" => "easy-social-share",
						"icon" => 'easy-social-share',
						"category" => __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN),
						"description" => __("Display Social Share Buttons", ESSB_TEXT_DOMAIN),
						"params" => $param_obj
				)
		);
	
}

if ( function_exists('vc_map')) {
	vc_map(
			array(
					"name" => __("Advanced Native Like and Subscribe Buttons", ESSB_TEXT_DOMAIN),
					"base" => "easy-social-like",
					"icon" => 'easy-social-like',
					"category" => __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN),
					"description" => __("Activate render of Facebook Like, Twitter Follow or Tweet, Google +1, Pinterest Pin or Follow, YouTube Subscribe, vk.com Like", ESSB_TEXT_DOMAIN),
					"params" => array(
							array(
									"type" => 'checkbox',
									"heading" => __("Facebook Like Button", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_width",
									"description" => __("Provide custom width of button if it did not render full in your language.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Like URL Address", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_url",
									"description" => __("Provide custom URL for like. If blank page/post were button is displayed will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Facebook Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Follow Width", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow_width",
									"description" => __("Provide custom width of button if it did not render full in your language.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Follow User", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow_url",
									"description" => __("Provide profile URL to follow.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
														array(
									"type" => 'textfield',
									"heading" => __("Facebook Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Twitter Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Follow User", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_follow_user",
									"description" => __("Provide user to be followed. If blank general setting will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Follow Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_follow_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Follow Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_follow_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Twitter Tweet Button", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_tweet",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Tweet Message", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_tweet_message",
									"description" => __("Provide custom tweet to be shared. If blank post/page title will be used.", ESSB_TEXT_DOMAIN),
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Tweet Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_tweet_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Tweet Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_tweet_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),		
							array(
									"type" => 'checkbox',
									"heading" => __("Google +1 Button", ESSB_TEXT_DOMAIN),
									"param_name" => "google",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google+ Custom URL", ESSB_TEXT_DOMAIN),
									"param_name" => "google_url",
									"description" => __("Provide custom url to be shared. If blank current post/page address will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google+ Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "google_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google+ Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "google_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Google Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "google_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google Follow ProfileURL", ESSB_TEXT_DOMAIN),
									"param_name" => "google_follow_url",
									"description" => __("Provide profile url to be followed.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "google_follow_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "google_follow_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("vk.com Button", ESSB_TEXT_DOMAIN),
									"param_name" => "vk",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("VK Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "vk_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("VK Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "vk_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),			
							array(
									"type" => 'checkbox',
									"heading" => __("YouTube Subscribe Button", ESSB_TEXT_DOMAIN),
									"param_name" => "youtube",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("YouTube Channel Name", ESSB_TEXT_DOMAIN),
									"param_name" => "youtube_chanel",
									"description" => __("Provide channel name. If blank general option will be used.", ESSB_TEXT_DOMAIN),
							),
							array(
									"type" => 'textfield',
									"heading" => __("YouTube Subscribe Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "youtube_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("YouTube Subscribe Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "youtube_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("LinkedIn Company Follow", ESSB_TEXT_DOMAIN),
									"param_name" => "linkedin",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("LinkedIn Company ID", ESSB_TEXT_DOMAIN),
									"param_name" => "linkedin_company",
									"description" => __("Provide company ID. If blank general option will be used.", ESSB_TEXT_DOMAIN),
							),
							array(
									"type" => 'textfield',
									"heading" => __("LinkedIn Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "linkedin_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("LinkedIn Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "linkedin_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Pinterst Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Follow Display Name", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow_display",
									"description" => __("If blank general option will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Follow URL", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow_url",
									"description" => __("If blank general option will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Follow Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Follow Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Pinterst Pin Button", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_pin",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),							
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Pin Skinned Button Text Overlay", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_pin_skinned_text",
									"description" => __("Provide custom text to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Pin Skinned Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_pin_skinned_width",
									"description" => __("Provide custom width to be used for initial display when you select skinned buttons to be active.", ESSB_TEXT_DOMAIN)
							),															
							array(
									"type" => "dropdown",
									"heading" => __("Align", ESSB_TEXT_DOMAIN),
									"param_name" => "align",
									"value" => array(
											__("Center", ESSB_TEXT_DOMAIN) => "center",
											__("Left", ESSB_TEXT_DOMAIN) => "left",
											__("Right", ESSB_TEXT_DOMAIN) => "right"
									)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Skin Native Buttons", ESSB_TEXT_DOMAIN),
									"param_name" => "skinned",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => "dropdown",
									"heading" => __("Skin", ESSB_TEXT_DOMAIN),
									"param_name" => "skin",
									"value" => array(
											__("Flat", ESSB_TEXT_DOMAIN) => "flat",
											__("Metro", ESSB_TEXT_DOMAIN) => "metro"
									)
							),							
							array(
									"type" => 'checkbox',
									"heading" => __("Display counters", ESSB_TEXT_DOMAIN),
									"param_name" => "counters",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),							
							array(
									"type" => 'textfield',
									"heading" => __("Message before buttons", ESSB_TEXT_DOMAIN),
									"param_name" => "message",
							),
					),
			)
	);
}

if ( function_exists('vc_map')) {
	
	vc_map(
			array(
					"name" => __("Simple Native Like and Subscribe Buttons", ESSB_TEXT_DOMAIN),
					"base" => "easy-social-like-simple",
					"icon" => 'easy-social-like-simple',
					"category" => __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN),
					"description" => __("Activate render of Facebook Like, Twitter Follow or Tweet, Google +1, Pinterest Pin or Follow, YouTube Subscribe, vk.com Like", ESSB_TEXT_DOMAIN),
					"params" => array(
							array(
									"type" => 'checkbox',
									"heading" => __("Facebook Like Button", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_width",
									"description" => __("Provide custom width of button if it did not render full in your language.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Like URL Address", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_url",
									"description" => __("Provide custom URL for like. If blank page/post were button is displayed will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Facebook Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Follow Button Width", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow_width",
									"description" => __("Provide custom width of button if it did not render full in your language.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Facebook Follow Profile URL Address", ESSB_TEXT_DOMAIN),
									"param_name" => "facebook_follow_url",
									"description" => __("Provide profile URL to follow. If blank page/post were button is displayed will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Twitter Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Follow User", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_follow_user",
									"description" => __("Provide user to be followed. If blank general setting will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Twitter Tweet Button", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_tweet",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Twitter Tweet Message", ESSB_TEXT_DOMAIN),
									"param_name" => "twitter_tweet_message",
									"description" => __("Provide custom tweet to be shared. If blank post/page title will be used.", ESSB_TEXT_DOMAIN),
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Google +1 Button", ESSB_TEXT_DOMAIN),
									"param_name" => "google",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google+ Custom URL", ESSB_TEXT_DOMAIN),
									"param_name" => "google_url",
									"description" => __("Provide custom url to be shared. If blank current post/page address will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Google Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "google_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Google Follow Profile URL", ESSB_TEXT_DOMAIN),
									"param_name" => "google_follow_url",
									"description" => __("Provide profile url to be followed. If blank current post/page address will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("vk.com Button", ESSB_TEXT_DOMAIN),
									"param_name" => "vk",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("YouTube Subscribe Button", ESSB_TEXT_DOMAIN),
									"param_name" => "youtube",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("YouTube Channel Name", ESSB_TEXT_DOMAIN),
									"param_name" => "youtube_chanel",
									"description" => __("Provide channel name. If blank general option will be used.", ESSB_TEXT_DOMAIN),
							),
							array(
									"type" => 'checkbox',
									"heading" => __("LinkedIn Company Follow", ESSB_TEXT_DOMAIN),
									"param_name" => "linkedin",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("LinkedIn Company ID", ESSB_TEXT_DOMAIN),
									"param_name" => "linkedin_company",
									"description" => __("Provide company ID. If blank general option will be used.", ESSB_TEXT_DOMAIN),
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Pinterst Follow Button", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Follow Display Name", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow_display",
									"description" => __("If blank general option will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Pinterest Follow URL", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_follow_url",
									"description" => __("If blank general option will be used.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Pinterst Pin Button", ESSB_TEXT_DOMAIN),
									"param_name" => "pinterest_pin",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => "dropdown",
									"heading" => __("Align", ESSB_TEXT_DOMAIN),
									"param_name" => "align",
									"value" => array(
											__("Center", ESSB_TEXT_DOMAIN) => "center",
											__("Left", ESSB_TEXT_DOMAIN) => "left",
											__("Right", ESSB_TEXT_DOMAIN) => "right"
									)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Display counters", ESSB_TEXT_DOMAIN),
									"param_name" => "counters",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "true"
									)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Message before buttons", ESSB_TEXT_DOMAIN),
									"param_name" => "message",
							),
					),
			)
	);
	
}

if ( function_exists('vc_map')) {

	vc_map(
			array(
					"name" => __("Social Fans Counter", ESSB_TEXT_DOMAIN),
					"base" => "essb-fans",
					"icon" => 'essb-fans',
					"category" => __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN),
					"description" => __("Activate render of social fans counter", ESSB_TEXT_DOMAIN),
					"params" => array(
							array(
								"type" => "dropdown",
								"heading" => __("Style", ESSB_TEXT_DOMAIN),
								"param_name" => "style",
								"value" => array(
									__("Grey Icons", ESSB_TEXT_DOMAIN) => "mutted",
									__("Colored Icons", ESSB_TEXT_DOMAIN) => "colored",
									__("Metro", ESSB_TEXT_DOMAIN) => "metro",
									__("Flat", ESSB_TEXT_DOMAIN) => "flat",							
									__("Tiny", ESSB_TEXT_DOMAIN) => "tiny"							
								),
							),
							array(
									"type" => "dropdown",
									"heading" => __("Columns", ESSB_TEXT_DOMAIN),
									"param_name" => "cols",
									"value" => array(
											__("", ESSB_TEXT_DOMAIN) => "",
											__("1 Col", ESSB_TEXT_DOMAIN) => "1",
											__("2 Cols", ESSB_TEXT_DOMAIN) => "2",
											__("3 Cols", ESSB_TEXT_DOMAIN) => "3",
											__("4 Cols", ESSB_TEXT_DOMAIN) => "4"
									),	
),						
							
							array(
									"type" => 'textfield',
									"heading" => __("Fixed Width", ESSB_TEXT_DOMAIN),
									"param_name" => "width",
									"description" => __("Optional provide fixed width of panels. Width can be number value in pixels (without px) or percentage (with % mark).", ESSB_TEXT_DOMAIN)
							),
								
					),
			)
	);

}

if ( function_exists('vc_map')) {

	vc_map(
			array(
					"name" => __("Total Share Counter", ESSB_TEXT_DOMAIN),
					"base" => "easy-total-shares",
					"icon" => 'essb-total-shares',
					"category" => __('Easy Social Share Buttons', ESSB_TEXT_DOMAIN),
					"description" => __("Activate display of total share counter", ESSB_TEXT_DOMAIN),
					"params" => array(
							array(
									"type" => "dropdown",
									"heading" => __("Align", ESSB_TEXT_DOMAIN),
									"param_name" => "align",
									"value" => array(
											__("Center", ESSB_TEXT_DOMAIN) => "center",
											__("Left", ESSB_TEXT_DOMAIN) => "left",
											__("Right", ESSB_TEXT_DOMAIN) => "right"
									)
							),
								
							array(
									"type" => 'textfield',
									"heading" => __("Message before counter:", ESSB_TEXT_DOMAIN),
									"param_name" => "message",
									"description" => __("Optional provide message that will be displayed before share counter.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("Message after counter:", ESSB_TEXT_DOMAIN),
									"param_name" => "share_text",
									"description" => __("Optional provide message that will be displayed after share counter.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'textfield',
									"heading" => __("URL:", ESSB_TEXT_DOMAIN),
									"param_name" => "url",
									"description" => __("Optional provide different url to extract share counts for.", ESSB_TEXT_DOMAIN)
							),
							array(
									"type" => 'checkbox',
									"heading" => __("Display full counter value", ESSB_TEXT_DOMAIN),
									"param_name" => "fullnumber",
									"value" => array(
											__("Yes", ESSB_TEXT_DOMAIN) => "yes"
									)
							),
					),
			)
	);

}

?>