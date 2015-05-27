<?php

// parsing update action
$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : '';

if ($cmd == "update") {
	$options = $_POST ['general_options'];
	$nn = $_POST ["general_options_nn"];
	
	$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
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
	
	foreach ( $nn as $k => $v ) {
		$current_options ['networks'] [$k] [1] = $v;
	}
	
	if (! isset ( $options ['display_in_types'] )) {
		$options ['display_in_types'] = array ();
	}
	$current_options ['display_in_types'] = $options ['display_in_types'];
	
	$current_options['style'] = get_options_field_value($options, 'style', '0');
	$current_options['buttons_pos'] = get_options_field_value($options, 'buttons_pos', '');
	$current_options['twitter_shareshort'] = get_options_field_value($options, 'twitter_shareshort', 'false');
	$current_options['twitteruser'] = get_options_field_value($options, 'twitteruser', '');
	$current_options['twitterhashtags'] = get_options_field_value($options, 'twitterhashtags', '');
	$current_options['url_short_bitly_user'] = get_options_field_value($options, 'url_short_bitly_user', '');
	$current_options['url_short_bitly_api'] = get_options_field_value($options, 'url_short_bitly_api', '');
	$current_options['display_where'] = get_options_field_value($options, 'display_where', 'bottom');
	$current_options['display_position_mobile'] = get_options_field_value($options, 'display_position_mobile', '');
	$current_options['force_hide_buttons_on_mobile'] = get_options_field_value($options, 'force_hide_buttons_on_mobile', 'false');
	$current_options['another_display_sidebar'] = get_options_field_value($options, 'another_display_sidebar', 'false');
	$current_options['another_display_popup'] = get_options_field_value($options, 'another_display_popup', 'false');
	$current_options['hide_social_name'] = get_options_field_value($options, 'hide_social_name', 0);
	$current_options['force_hide_social_name'] = get_options_field_value($options, 'force_hide_social_name', 'false');
	$current_options['show_counter'] = get_options_field_value($options, 'show_counter', 0);
	$current_options['counter_pos'] = get_options_field_value($options, 'counter_pos', '');
	$current_options['total_counter_pos'] = get_options_field_value($options, 'total_counter_pos', '');
	$current_options['force_counters_admin'] = get_options_field_value($options, 'force_counters_admin', 'false');
	$current_options['force_hide_total_count'] = get_options_field_value($options, 'force_hide_total_count', 'false');
	$current_options['facebooktotal'] = get_options_field_value($options, 'facebooktotal', 'false');
	$current_options['more_button_func'] = get_options_field_value($options, 'more_button_func', '');
	$current_options['twitter_shareshort_service'] = get_options_field_value($options, 'twitter_shareshort_service', '');
	$current_options['display_excerpt_pos'] = get_options_field_value($options, 'display_excerpt_pos', '');
	$current_options['display_excerpt'] = get_options_field_value($options, 'display_excerpt', 'false');
	$current_options['display_position_mobile_sidebar'] = get_options_field_value($options, 'display_position_mobile_sidebar', '');
	$current_options['force_hide_buttons_on_all_mobile'] = get_options_field_value($options, 'force_hide_buttons_on_all_mobile', 'false');
	$current_options['always_hide_names_mobile'] = get_options_field_value($options, 'always_hide_names_mobile', 'false');
	$current_options['admin_ajax_cache'] = get_options_field_value($options, 'admin_ajax_cache', '');
	$current_options['activate_total_counter_text'] = get_options_field_value($options, 'activate_total_counter_text', 'false');
	$current_options['total_counter_hidden_till'] = get_options_field_value($options, 'total_counter_hidden_till', '');
	//$current_options['use_minified_css'] = get_options_field_value($options, 'use_minified_css', 'true');
	//$current_options['use_minified_js'] = get_options_field_value($options, 'use_minified_js', 'true');
	$current_options['using_yoast_ga'] = get_options_field_value($options, 'using_yoast_ga', 'true');
	//$current_options['apply_clean_buttons'] = get_options_field_value($options, 'apply_clean_buttons', 'true');
	
	update_option ( EasySocialShareButtons::$plugin_settings_name, $current_options );
	
	echo "<script type='text/javascript'>window.location='".admin_url()."admin.php?page=essb_settings&tab=general#wizard';</script>";
}

function get_options_field_value($options, $key, $default) {
	$value = '';
	if (!isset($options[$key])) { 
		$value = $default;
	}
	else {
		$value = $options[$key];
	}
	
	return $value;
}

// content generation functions
function essb_template_select_radio() {
	
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$n1 = $n2 = $n3 = $n4 = $n5 = $n6 = $n7 = $n8 = $n9 = $n10 = $n11 = $n12 = "";
		${
			'n' . $options ['style']} = " checked='checked'";
		
		echo '
			<tr><td>
			<input id="essb_style_1" value="1" name="general_options[style]" type="radio" ' . $n1 . ' />&nbsp;&nbsp;' . __ ( 'Default', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-default.png"/>
			</td><td>
			<input id="essb_style_2" value="2" name="general_options[style]" type="radio" ' . $n2 . ' />&nbsp;&nbsp;' . __ ( 'Metro', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-metro.png"/>
			</td></tr><tr><td>
			<input id="essb_style_3" value="3" name="general_options[style]" type="radio" ' . $n3 . ' />&nbsp;&nbsp;' . __ ( 'Modern', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-modern.png"/>
			</td><td>
			<input id="essb_style_4" value="4" name="general_options[style]" type="radio" ' . $n4 . ' />&nbsp;&nbsp;' . __ ( 'Round', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-round.png"/><br/><span class="small">Round style works correct only with Hide Social Network Names: <strong>Yes</strong>. If this option is not set to Yes please change its value or template will not render correct.</span>
			</td></tr><tr><td>
			<input id="essb_style_5" value="5" name="general_options[style]" type="radio" ' . $n5 . ' />&nbsp;&nbsp;' . __ ( 'Big', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-big.png"/>
			</td><td>
			<input id="essb_style_6" value="6" name="general_options[style]" type="radio" ' . $n6 . ' />&nbsp;&nbsp;' . __ ( 'Metro (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-metro2.png"/>
			</td></tr><tr><td>
			<input id="essb_style_7" value="7" name="general_options[style]" type="radio" ' . $n7 . ' />&nbsp;&nbsp;' . __ ( 'Big (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-big-retina.png"/>
			</td><td>
			<input id="essb_style_8" value="8" name="general_options[style]" type="radio" ' . $n8 . ' />&nbsp;&nbsp;' . __ ( 'Light (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-light-retina.png"/>
			</td></tr><tr><td>
			<input id="essb_style_9" value="9" name="general_options[style]" type="radio" ' . $n9 . ' />&nbsp;&nbsp;' . __ ( 'Flat (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-flat.png"/>
			</td><td>
			<input id="essb_style_10" value="10" name="general_options[style]" type="radio" ' . $n10 . ' />&nbsp;&nbsp;' . __ ( 'Tiny (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-tiny.png"/>
			</td></tr><tr><td>
			<input id="essb_style_11" value="11" name="general_options[style]" type="radio" ' . $n11 . ' />&nbsp;&nbsp;' . __ ( 'Round (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-round-retina.png"/>
			</td><td>
			<input id="essb_style_12" value="12" name="general_options[style]" type="radio" ' . $n12 . ' />&nbsp;&nbsp;' . __ ( 'Modern (Retina)', ESSB_TEXT_DOMAIN ) . '<br /><img src="' . ESSB_PLUGIN_URL . '/assets/images/demo-style-modern-retina.png"/>
			</td></tr>';
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
				// $more_options = '<span class="label"
			// style="margin-top:-15px;"><a href="#'.$k.'"> Additional
			// Options</a></span>';
			}
			
			if ($k == "more") {
				$more_options = " onclick=\"essb_option_show_hide('#essb-more-button-options', '#network_selection_more');\"";
			}
			if ($k == "twitter") {
				$more_options = " onclick=\"essb_option_show_hide('#essb-twitter1,#essb-twitter2,#essb-twitter3,#essb-twitter4,#essb-twitter5,#essb-twitter6,#essb-twitter7', '#network_selection_twitter');\"";				
			}
			
			$is_checked = ($v [0] == 1) ? ' checked="checked"' : '';
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
			
			echo '<li><p style="margin: .2em 5% .2em 0;">
			<input id="network_selection_' . $k . '" value="' . $k . '" name="general_options[networks][]" type="checkbox"
			' . $is_checked . ' ' . $more_options . ' /><input name="general_options[sort][]" value="' . $k . '" type="checkbox" checked="checked" style="display: none; " />
			<label for="network_selection_' . $k . '"><span class="essb_icon essb_icon_' . $k . '"></span>' . $network_name . '</label> ' . '' . '
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

function essb_twitter_share_short() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitter_shareshort'] ) ? $options ['twitter_shareshort'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitter_shareshort" type="checkbox" name="general_options[twitter_shareshort]" value="true" ' . $is_checked . ' onclick="essb_option_show_hide(\'#essb-twitter3,#essb-twitter6,#essb-twitter7\', \'#twitter_shareshort\');" /></p>';

	}

}

function essb_twitter_username_append() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitteruser'] ) ? $options ['twitteruser'] : '';

		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitteruser" type="text" name="general_options[twitteruser]" value="' . $exist . '" class="input-element" /></p>';

	}

}

function essb_twitter_hashtags_append() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['twitterhashtags'] ) ? $options ['twitterhashtags'] : '';

		echo '<p style="margin: .2em 5% .2em 0;"><input id="twitterhashtags" type="text" name="general_options[twitterhashtags]" value="' . $exist . '" class="input-element" /></p>';

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

function essb_select_content_type() {
	$pts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => true ) );
	$cpts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => false ) );
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	$woocommerce_selected = isset ( $options ['woocommece_share'] ) ? $options ['woocommece_share'] : 'false';
	$is_woocommerce_selected = ($woocommerce_selected == 'true') ? ' checked="checked"' : '';

	$excerpt_selected = isset ( $options ['display_excerpt'] ) ? $options ['display_excerpt'] : 'false';
	$is_excerpt_selected = ($excerpt_selected == 'true') ? ' checked="checked"' : '';

	$all_lists_selected = '';
	if (is_array ( $options ['display_in_types'] )) {
		$all_lists_selected = in_array ( 'all_lists', $options ['display_in_types'] ) ? 'checked="checked"' : '';
	}



	if (is_array ( $options ) && isset ( $options ['display_in_types'] ) && is_array ( $options ['display_in_types'] )) {

		global $wp_post_types;
		// classical post type listing
		foreach ( $pts as $pt ) {
				
			$selected = in_array ( $pt, $options ['display_in_types'] ) ? 'checked="checked"' : '';
				
			$icon = "";
			echo '<input type="checkbox" name="general_options[display_in_types][]" id="' . $pt . '" value="' . $pt . '" ' . $selected . '> <label for="' . $pt . '">' . $icon . ' ' . $wp_post_types [$pt]->label . '</label><br />';
		}

		// custom post types listing
		if (is_array ( $cpts ) && ! empty ( $cpts )) {
			foreach ( $cpts as $cpt ) {

				$selected = in_array ( $cpt, $options ['display_in_types'] ) ? 'checked="checked"' : '';

				$icon = "";
				echo '<input type="checkbox" name="general_options[display_in_types][]" id="' . $cpt . '" value="' . $cpt . '" ' . $selected . '> <label for="' . $cpt . '">' . $icon . ' ' . $wp_post_types [$cpt]->label . '</label><br />';
			}
		}
	}
	echo '<input type="checkbox" name="general_options[display_in_types][]" id="all_lists" value="all_lists" ' . $all_lists_selected . '> <label for="all_lists">' . "" . ' ' . sprintf ( __ ( 'Lists of articles <br />%s<span class="label">(blog, archives, search results, etc.)</span>%s', ESSB_TEXT_DOMAIN ), '<em>', '</em>' ) . '</label><br/><br/>';
	echo '<input type="checkbox" name="general_options[woocommece_share]" id="woocommece_share" value="true" ' . $is_woocommerce_selected . '> <label for="woocommece_share">' . "" . ' ' . sprintf ( __ ( 'WooCommence Products <br />%s<span class="label">(Activate share buttons for WooCommerce Products)</span>%s', ESSB_TEXT_DOMAIN ), '<em>', '</em>' ) . '</label><br/><br/>';
}

function essb_setting_radio_where() {
	
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	// print_r($options);
	
	$w_bottom = $w_top = $w_both = $w_nowhere = $w_float = $w_sidebar = $w_popup = $w_likeshare = $w_sharelike = $w_postfloat = $w_inline = "" ;
	if (is_array ( $options ) && isset ( $options ['display_where'] ))
		${'w_' . $options ['display_where']} = " checked='checked'";
	
	echo '<table border="0" cellpadding="2" cellspacing="0" style="background-color: #f3f3f3;">';
	echo '<col width="33%"/>';
	echo '<col width="33%"/>';
	echo '<col width="33%"/>';
	echo '<tr>';
	echo '<td align="center" valign="top" style="background-color: #fafafa !important;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-02.png" style="margin-bottom: 5px;" /><br/><input id="" value="bottom" name="general_options[display_where]" type="radio" ' . $w_bottom . ' />
	<br/><label for=""><strong>' . __ ( 'Content bottom', ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons after content</label></label></td>';
	echo '<td align="center" valign="top" style="background-color: #ffffff !important;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-01.png"  style="margin-bottom: 5px;" /><br/><input id="" value="top" name="general_options[display_where]" type="radio" ' . $w_top . ' />
	<br/><label for=""><strong>' . __ ( 'Content top', ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons before content</label></label></td>';
	echo '<td align="center"  valign="top" style="background-color: #fafafa !important;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-03.png"  style="margin-bottom: 5px;" /><br/><input id="" value="both" name="general_options[display_where]" type="radio" ' . $w_both . ' />
	<br/><label for=""><strong>' . __ ( 'Both (content bottom and content top)', ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons before and after content</label></label></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td align="center" valign="top" style="background-color: #fafafa !important;border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-04.png" style="margin-bottom: 5px;" /><br/><input id="" value="float" name="general_options[display_where]" type="radio" ' . $w_float . ' />
	<br/><label for=""><strong>' . __ ( "Float from content top", ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons before content and stick on top while scroll down</label></label><br/><a href="#" class="button" onclick="essb_option_activate(\'3\'); ">Display options</a></td>';
	echo '<td align="center" valign="top" style="background-color: #ffffff !important; border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-06.png"  style="margin-bottom: 5px;" /><br/><input id="" value="sidebar" name="general_options[display_where]" type="radio" ' . $w_sidebar . ' />
	<br/><label for=""><strong>' . __ ( "Sidebar", ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons as left, right, top or bottom window sidebar</label></label><br/><a href="#" class="button" onclick="essb_option_activate(\'4\'); ">Display options</a></td>';
	echo '<td align="center"  valign="top"  style="background-color: #fafafa !important;border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-09.png"  style="margin-bottom: 5px;" /><br/><input id="" value="nowhere" name="general_options[display_where]" type="radio" ' . $w_nowhere . ' />
	<br/><label for=""><strong>' . __ ( "Via shortcode only", ESSB_TEXT_DOMAIN ) . '</label><br /><label class="small">This will display buttons only when shortcode call is included: <strong>[easy-share]</strong>, <strong>[easy-social-share]</strong>, <strong>[easy-social-like]</strong></label></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td align="center" valign="top" style="background-color: #fafafa !important;border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-07.png" style="margin-bottom: 5px;" /><br/><input id="" value="popup" name="general_options[display_where]" type="radio" ' . $w_popup . ' />
	<br/><label for=""><strong>' . __ ( "Pop up", ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons as pop up over content</label></label><br/><a href="#" class="button" onclick="essb_option_activate(\'5\'); ">Display options</a></td>';
	echo '<td align="center" valign="top" style="background-color: #ffffff !important; border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-10.png"  style="margin-bottom: 5px;" /><br/><input id="" value="likeshare" name="general_options[display_where]" type="radio" ' . $w_likeshare . ' />
	<br/><label for=""><strong>' . __ ( 'Native top/Share bottom', ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display native buttons before content and share buttons after content</label></label></td>';
	echo '<td align="center"  valign="top"  style="background-color: #fafafa !important;border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-11.png"  style="margin-bottom: 5px;" /><br/><input id="" value="sharelike" name="general_options[display_where]" type="radio" ' . $w_sharelike . ' />
	<br/><label for=""><strong>' . __ ( 'Share top/Native bottom', ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display share buttons before content and native buttons after content</label></label></td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td align="center" valign="top" style="background-color: #fafafa !important;border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-05.png" style="margin-bottom: 5px;" /><br/><input id="" value="postfloat" name="general_options[display_where]" type="radio" ' . $w_postfloat . ' />
	<br/><label for=""><strong>' . __ ( "Post Vertical Float Sidebar", ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons as vertical float bar on the left side of post and stay on screen while scroll down</label></label><br/><a href="#" class="button" onclick="essb_option_activate(\'10\'); ">Display options</a></td>';
	echo '<td align="center" valign="top" style="background-color: #ffffff !important;border-top: 1px solid #dadada;"><img src="' . ESSB_PLUGIN_URL . '/assets/images/button-position-08.png" style="margin-bottom: 5px;" /><br/><input id="" value="inline" name="general_options[display_where]" type="radio" ' . $w_inline . ' />
	<br/><label for=""><strong>' . __ ( "Content Inline (&lt;!--easy-share--&gt;)", ESSB_TEXT_DOMAIN ) . '</strong><br/><label class="small">This will display buttons on every position where text &lt;!--easy-share--&gt; is included.</label></label></td>';
		echo '<td align="center"  valign="top"  style="border-top: 1px solid #dadada;"></td>';
	echo '</tr>';
	
	echo '</table>';
}

function essb_display_for_mobile() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['display_position_mobile'] ) ? $options ['display_position_mobile'] : '';
		$exist = stripslashes ( $exist );

		echo '<p style="margin: .2em 5% .2em 0;"><select id="display_position_mobile" type="text" name="general_options[display_position_mobile]" class="input-element">
		<option value="" ' . ($exist == '' ? ' selected="selected"' : '') . '>No</option>
		<option value="bottom" ' . ($exist == 'bottom' ? ' selected="selected"' : '') . '>Content bottom</option>
		<option value="top" ' . ($exist == 'top' ? ' selected="selected"' : '') . '>Content top</option>
		<option value="both" ' . ($exist == 'both' ? ' selected="selected"' : '') . '>Content top and bottom</option>
		<option value="float" ' . ($exist == 'float' ? ' selected="selected"' : '') . '>Float from content top</option>
		<option value="sidebar" ' . ($exist == 'sidebar' ? ' selected="selected"' : '') . '>Top or bottom Sidebar</option>
		</select>
		</p>';
	}
}

function essb_force_hide_buttons_on_mobile() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['force_hide_buttons_on_mobile'] ) ? $options ['force_hide_buttons_on_mobile'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="force_hide_buttons_on_mobile" type="checkbox" name="general_options[force_hide_buttons_on_mobile]" value="true" ' . $is_checked . ' /><label for="force_hide_buttons_on_mobile"></label></p>';

	}

}

function essb_another_display_sidebar() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['another_display_sidebar'] ) ? $options ['another_display_sidebar'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="another_display_sidebar" type="checkbox" name="general_options[another_display_sidebar]" value="true" ' . $is_checked . ' /><label for="another_display_sideba"></label></p>';

	}

}

function essb_another_display_popup() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['another_display_popup'] ) ? $options ['another_display_popup'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="another_display_popup" type="checkbox" name="general_options[another_display_popup]" value="true" ' . $is_checked . ' /><label for="another_display_popup"></label></p>';

	}

}

function essb_radio_hide_social_name() {
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	if (is_array ( $options ))
		(isset ( $options ['hide_social_name'] ) and $options ['hide_social_name'] == 1) ? $y = " checked='checked'" : $n = " checked='checked'";

	echo '<input id="hide_name_yes" value="1" name="general_options[hide_social_name]" type="radio" ' . $y . ' />
	<label for="hide_name_yes">' . __ ( 'Yes', ESSB_TEXT_DOMAIN ) . '</label>

	<input id="hide_name_no" value="0" name="general_options[hide_social_name]" type="radio" ' . $n . ' />
	<label for="hide_name_no">' . __ ( 'No', ESSB_TEXT_DOMAIN ) . '</label>';
}

function essb_force_hide_name() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['force_hide_social_name'] ) ? $options ['force_hide_social_name'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="force_hide_social_name" type="checkbox" name="general_options[force_hide_social_name]" value="true" ' . $is_checked . ' /><label for="force_hide_social_name"></label></p>';

	}

}


function essb_setting_rename_network_selection() {
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	if (is_array ( $options )) {
		foreach ( $options ['networks'] as $k => $v ) {
				
			$is_checked = ($v [0] == 1) ? ' checked="checked"' : '';
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
				
			echo '<li><p style="margin: .2em 5% .2em 0;">
			<input id="network_selection_' . $k . '" value="' . esc_attr(stripslashes($network_name)) . '" name="general_options_nn[' . $k . ']" type="text"
			class="input-element" />
			<label for="network_selection_' . $k . '"><span class="essb_icon essb_icon_' . $k . '"></span>' . esc_attr(stripslashes($network_name)) . '</label>
			</p></li>';
		}

	}
}

function essb_setting_radio_counter() {

	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	if (is_array ( $options ))
		(isset ( $options ['show_counter'] ) and $options ['show_counter'] == 1) ? $y = " checked='checked'" : $n = " checked='checked'";

	echo '	<input id="show_counter_yes" value="1" name="general_options[show_counter]" type="radio" ' . $y . ' onclick="essb_option_show_hide(\'#essb-counter1,#essb-counter2,#essb-counter3,#essb-counter4,#essb-counter5,#essb-counter6,#essb-counter7\', \'#show_counter_yes\');" />
	<label for="">' . __ ( 'Yes', ESSB_TEXT_DOMAIN ) . '</label>

	<input id="show_counter_no" value="0" name="general_options[show_counter]" type="radio" ' . $n . '  onclick="essb_option_show_hide(\'#essb-counter1,#essb-counter2,#essb-counter3,#essb-counter4,#essb-counter5,#essb-counter6,#essb-counter7\', \'#show_counter_yes\');" />
	<label for="">' . __ ( 'No', ESSB_TEXT_DOMAIN ) . '</label>';
}

function essb_custom_counter_pos() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['counter_pos'] ) ? $options ['counter_pos'] : '';
		$exist = stripslashes ( $exist );

		echo '<p style="margin: .2em 5% .2em 0;"><select id="counter_pos" type="text" name="general_options[counter_pos]" class="input-element">
		<option value="" ' . ($exist == '' ? ' selected="selected"' : '') . '>Left</option>
		<option value="right" ' . ($exist == 'right' ? ' selected="selected"' : '') . '>Right</option>
		<option value="inside" ' . ($exist == 'inside' ? ' selected="selected"' : '') . '>Inside Buttons</option>
		<option value="insidename" ' . ($exist == 'insidename' ? ' selected="selected"' : '') . '>Inside Buttons with Network Names</option>
		<option value="hidden" ' . ($exist == 'hidden' ? ' selected="selected"' : '') . '>Hidden</option>
		<option value="leftm" ' . ($exist == 'leftm' ? ' selected="selected"' : '') . '>Left Modern</option>
		<option value="rightm" ' . ($exist == 'rightm' ? ' selected="selected"' : '') . '>Right Modern</option>
		<option value="top" ' . ($exist == 'top' ? ' selected="selected"' : '') . '>Top Modern</option>
		<option value="topm" ' . ($exist == 'topm' ? ' selected="selected"' : '') . '>Top Mini</option>
		</select>
		</p>';

	}
}

function essb_custom_total_counter_pos() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['total_counter_pos'] ) ? $options ['total_counter_pos'] : '';
		$exist = stripslashes ( $exist );

		echo '<p style="margin: .2em 5% .2em 0;"><select id="total_counter_pos" type="text" name="general_options[total_counter_pos]" class="input-element">
		<option value="" ' . ($exist == '' ? ' selected="selected"' : '') . '>Right</option>
		<option value="left" ' . ($exist == 'left' ? ' selected="selected"' : '') . '>Left</option>
		<option value="rightbig" ' . ($exist == 'rightbig' ? ' selected="selected"' : '') . '>Right Big Numbers Only</option>
		<option value="leftbig" ' . ($exist == 'leftbig' ? ' selected="selected"' : '') . '>Left Big Numbers Only</option>
		</select>
		</p>';

	}
}


function essb_loadcounters_from_admin() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['force_counters_admin'] ) ? $options ['force_counters_admin'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="force_counters_admin" type="checkbox" name="general_options[force_counters_admin]" value="true" ' . $is_checked . ' /><label for="force_counters_admin"></label></p>';

	}

}

function essb_force_hide_total_count() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['force_hide_total_count'] ) ? $options ['force_hide_total_count'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="force_hide_total_count" type="checkbox" name="general_options[force_hide_total_count]" value="true" ' . $is_checked . ' /><label for="force_hide_total_count"></label></p>';

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

?>

<div class="wrap">
		<form name="general_form" method="post"
		action="admin.php?page=essb_settings&tab=wizard"
		enctype="multipart/form-data" id="wizard-form" >
		<input type="hidden" id="cmd" name="cmd" value="update" />

	<div id="poststuff">
		<div class="postbox">
			<h3 class="hndle">
				<span>Easy Social Share Buttons for WordPress Configuration Wizard</span>
			</h3>
			<div class="inside">

				<div id="essb-easy-wizard">
					<h3>Template</h3>
					<section>

						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<col width="30%" />
							<col width="70%" />
							<tr>
								<td colspan="2" class="sub"><?php _e('Template', ESSB_TEXT_DOMAIN); ?></td>
							</tr>
						<?php essb_template_select_radio();?>
						</table>

					</section>

					<h3>Social Share Buttons</h3>
					<section>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<col width="50%" />
							<col width="50%" />
							<tr>
								<td colspan="2" class="sub"><?php _e('Social Share Buttons', ESSB_TEXT_DOMAIN); ?></td>
							</tr>
							<tr>
								<td colspan="2">
									<!-- div class="essb-info-box">
									Hi I am info box</div > -->
								</td>
							</tr>
							<tr class="even table-border-bottom">

								<td colspan="2"><ul id="networks-sortable"><?php essb_setting_checkbox_network_selection(); ?></ul></td>
							</tr>
							
						</table>
					</section>
					<h3>Additional Social Share Buttons Settings</h3>
					<section>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<col width="30%" />
							<col width="70%" />
							<tr>
								<td colspan="2" class="sub"><?php _e('Additional Social Share Buttons Settings', ESSB_TEXT_DOMAIN); ?></td>
							</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Buttons Align:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Choose how buttons
									to be aligned. Default position is left but you can also select
									Right or Center</span></td>
							<td><?php essb_custom_buttons_pos(); ?></td>
						</tr>
							<tr class="even table-border-bottom"
								id="essb-more-button-options" style="display: none;">
								<td valign="top" class="bold">More button function:<br /> <span
									class="label" style="font-weight: 400;">Choose how more button
										will function.</span></td>
								<td class="essb_options_general">
						 	<?php
								
								$more_options = array ("1" => "Display all active networks after more button", "2" => "Display all social networks as popup" , "3" => "Display only active social networks as popup");
								ESSB_Settings_Helper::drawSelectField ( 'more_button_func', $more_options );
								
								?>
						 
						 </td>
							</tr>
							<tr class="table-border-bottom" id="essb-twitter1">
							<td colspan="2" class="sub2">Twitter Additional Options</td>
						</tr>
						<tr class="even table-border-bottom" id="essb-twitter4">
							<td class="bold" valign="top">Twitter username to be mentioned:<br/><span for="twitteruser" class="label">If you wish a twitter username to be mentioned in tweet write it here. Enter your username without @ - example <span class="bold">twittername</span>. This text will be appended to tweet message at the end. Please note that if you activate custom share address option this will be added to custom share message.</span></td>
							<td class="essb_general_options"><?php essb_twitter_username_append(); ?></td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-twitter5">
							<td class="bold" valign="top">Twitter hashtags to be added:<br/><span for="twitterhashtags" class="label">If you wish hashtags to be added to message write theme here. You can set one or more (if more then one separate them with comma (,)) Example: demotag1,demotag2.</span></td>
							<td class="essb_general_options"><?php essb_twitter_hashtags_append(); ?></td>
						</tr>
						<tr class="even table-border-bottom" id="essb-twitter2">
							<td class="bold" valign="top">Twitter share short url:<br/><div class="essb-recommended"><i class="fa fa-check"></i><span></span></div><span for="twitter_shareshort" class="label">Activate this option to share short url with Twitter.</span></td>
							<td class="essb_general_options"><?php essb_twitter_share_short(); ?></td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-twitter3">
							<td class="bold" valign="top">Short URL service:<br/><span class="label">Provide short URL service you wish to use to generate short URL's used with Twitter. wp_get_shortlink is build in WordPress option that will provide shorturl like this http://www.site.com/?p=123. Goo.gl is short url serives provided from Google which does not require registration or setup. Returned address will look like this: http://goo.gl/abcdsd. Bit.ly is advanced short URL services that requires to work valid username and API key. Return address will be in format http://bit.ly/abvcsd or using your short domain that you setup in bit.ly.</span></td>
							<td class="essb_general_options">
							<?php 
							$list_of_url = array('wp_get_shortlink', 'goo.gl', 'bit.ly');
							ESSB_Settings_Helper::drawSelectField('twitter_shareshort_service', $list_of_url, true);
							?>
							</td>
						</tr>
						<tr class="even table-border-bottom" id="essb-twitter6">
							<td valign="top" class="bold">bit.ly user:<br /><span class="label">Provide valid bit.ly username if you select short url services options to be active and choose bit.ly</span>
							</td>
							<td class="essb_general_options"><?php essb_url_short_bitly_user(); ?></td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-twitter7">
							<td valign="top" class="bold">bit.ly api key:<br /><span class="label">Provide valid bit.ly api key if you select short url services options to be active and choose bit.ly</span>
							</td>
							<td class="essb_general_options"><?php essb_url_short_bitly_api(); ?></td>
						</tr>
						
						</table>
					</section>
					<h3>Display buttons on</h3>
					<section>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Display buttons on', ESSB_TEXT_DOMAIN); ?></td>
						</tr>

						<tr class="even">
							<td valign="top" class="bold"><?php _e('Where to display buttons:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Choose post types
									where you wish buttons to appear. If you are running <span
									class="bold">WooCommerce</span> store you can choose between
									post type <span class="bold">Products</span> which will display
									share buttons into product description or option to display
									buttons below price.
							</span></td>
							<td class="essb_general_options"><?php essb_select_content_type(); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold" valign="top">Display in post excerpt:<br/><span class="label">(Activate this option if your theme is using excerpts and you wish to display share buttons in excerpts)</span></td>
							<td class="essb_general_options"><?php 
							$excerpt_pos = array("top", "bottom"); $html = ESSB_Settings_Helper::generateCustomSelectField('display_excerpt_pos', $excerpt_pos, true);
							ESSB_Settings_Helper::drawExtendedCheckboxField('display_excerpt', $html.''); ?></td>
						</tr>
						</table>
					</section>
					<h3>Position of buttons</h3>
					<section>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Position of buttons', ESSB_TEXT_DOMAIN); ?></td>
						</tr>

						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Position of buttons:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Choose default
									method that will be used to render buttons. You are able to
									provide custom options for each post/page.</span></td>
							<td class="essb_general_options"><?php essb_setting_radio_where(); ?></td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">Mobile device options</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e("Activate another display method for mobile:", ESSB_TEXT_DOMAIN);?>
							<br/><span class="label" style="font-weight: 400;">This option allow you to set another display method of buttons which will be applied when page/post is viewed from mobile device.</span></td>
							<td class="essb_general_options"><?php essb_display_for_mobile(); ?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e("Mobile sidebar position:", ESSB_TEXT_DOMAIN);?>
							<br/><span class="label" style="font-weight: 400;">Choose sidebar position when activate another display method for mobile is set to sidebar.</span></td>
							<td class="essb_general_options"><?php $mobile_pos = array("bottom", "top"); ESSB_Settings_Helper::drawSelectField('display_position_mobile_sidebar', $mobile_pos, true) ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Hide buttons for low resolution mobile devices:<br/><span class="label" style="font-weight: 400;">This option will hide buttons when viewed from low resolution mobile device.</span></td></td>
							<td class="essb_general_options"><?php essb_force_hide_buttons_on_mobile();?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Hide buttons for all mobile devices:<br/><span class="label" style="font-weight: 400;">This option will always hide buttons when viewed from any mobile device.</span></td></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('force_hide_buttons_on_all_mobile');?></td>
						</tr>
						</table>
					</section>
					
					
					<h3>Social Network Names</h3>
					<section>
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub">Social Network Names</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Hide Social Network Names:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;"><?php _e('This will display only social network icon by default and network name will apear when you hover button.', ESSB_TEXT_DOMAIN); ?></span></td>
							<td class="essb_general_options"><?php essb_radio_hide_social_name(); ?>
					<br /> <img
								src="<?php echo ESSB_PLUGIN_URL; ?>/assets/images/admin_help_images-01.png" /></td>
						</tr>
									<tr class="odd table-border-bottom">
							<td valign="top" class="bold">Always hide network names on mobile:<br/><span class="label" style="font-weight: 400;">This option will hide network names when mobile device is detected and only social icons will be displayed.</span></td>
							<td><?php ESSB_Settings_Helper::drawCheckboxField('always_hide_names_mobile'); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold">Don't pop network names:<br/><span class="label" style="font-weight: 400;">This option will disable network name pop out
								when you activate Hide Social Network Names. This option is
								activated by default on mobile devices and can't be turned off.</span></td>
							<td><?php essb_force_hide_name(); ?><br /> <img
								src="<?php echo ESSB_PLUGIN_URL; ?>/assets/images/admin_help_images-02.png" /></td>
						</tr>
						
						<tr class="odd table-border-bottom">
							<td class="bold" valign="top">Social Network Names:<br/><span class="label" style="font-weight:400;">Set different texts that will appear instead of social network names inside buttons.</span></td>
							<td class="essb_general_options"><ul id="networks-sortable"><?php essb_setting_rename_network_selection(); ?></ul></td>
						</tr>
					</table>
										</section>

					<h3>Social Share Counters</h3>
					<section>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Social Share Counters', ESSB_TEXT_DOMAIN); ?></td>
						</tr>						
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Display counter of sharing', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;"><?php _e('Activate display of share counters.', ESSB_TEXT_DOMAIN); ?></span></td>
							<td class="essb_general_options"><?php essb_setting_radio_counter(); ?><br />
								<img
								src="<?php echo ESSB_PLUGIN_URL; ?>/assets/images/admin_help_images-03.png" />
								</td>
						</tr>
						
						<tr class="even table-border-bottom" id="essb-counter1">
							<td class="bold" valign="top">Position of counters:</td>
							<td class="essb_general_options"><?php essb_custom_counter_pos(); ?><br />
								<img
								src="<?php echo ESSB_PLUGIN_URL; ?>/assets/images/admin_help_images-04.png" /></td>
						</tr>
						<tr class="odd table-border-bottom"  id="essb-counter7">
							<td class="bold" valign="top">Display Facebook Total Count:<br /><div class="essb-recommended"><i class="fa fa-check"></i><span></span></div>
								<span class="label" style="font-size: 400;">Enable this option
									if you wish to display total count not only share count which is displayed by default.</span></td>
							<td class="essb_general_options"><?php essb_facebook_total_count(); ?></td>
						</tr>						
						<tr class="even table-border-bottom" id="essb-counter6">
							<td class="bold" valign="top">Hide total counter when display
								counter of sharing is active:<br/><span class="label" style="font-weight: 400;">This option will make total counter always to be hidden when counters are active.</span></td>
							<td class="essb_general_options"><?php essb_force_hide_total_count(); ?></td>
						</tr>
						
						<tr class="odd table-border-bottom" id="essb-counter2">
							<td class="bold" valign="top">Load counters with build-in WordPress AJAX functions (using admin-ajax):<br/><span class="label" style="font-weight: 400;">This method is more secure and required by some hosting companies but may slow down page load.</span></td>
							<td class="essb_general_options"><?php essb_loadcounters_from_admin(); ?>cache counters loaded with this call for amount of seconds<br/>
							<?php ESSB_Settings_Helper::drawInputField('admin_ajax_cache') ?> <br/><span class="small">(this will reduce external calls to APIs and will speed up load)</span></td>
						</tr>
						<tr class="even table-border-bottom" id="essb-counter3">
							<td class="bold" valign="top">Position of total counter:</td>
							<td class="essb_general_options"><?php essb_custom_total_counter_pos(); ?><br />
								<img
								src="<?php echo ESSB_PLUGIN_URL; ?>/assets/images/admin_help_images-05.png" /></td>
						</tr>
						<tr class="odd table-border-bottom" id="essb-counter4">
							<td class="bold" valign="top">Append text to total counter when big number styles are active:<br/><span class="label" style="font-size:400;">This option allows you to add custom text below counter when big number styles are active. For example you can add text shares.</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawCheckboxField('activate_total_counter_text'); ?><?php ESSB_Settings_Helper::drawCustomInputField('activate_total_counter_text_value'); ?><br/> <span class="label">ex: shares, likes</span><br />
								<img
								src="<?php echo ESSB_PLUGIN_URL; ?>/assets/images/admin_help_images-06.png" /></td>
						</tr>
						<tr class="even table-border-bottom" id="essb-counter5">
							<td class="bold" valign="top">Display total counter after this value of shares is reached:<br/><span class="label" style="font-weight:400">You can hide your total counter until amount of shares is reached. This option is active only when you enter value in this field - if blank total counter is always displayed.</span></td>
							<td class="essb_general_options"><?php ESSB_Settings_Helper::drawInputField('total_counter_hidden_till'); ?></td>
						</tr>
					</table>
					</section>
				</div>


			</div>
		</div>
	</div>
	</form>
</div>
<script type="text/javascript">
jQuery("#essb-easy-wizard").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slide",
    stepsOrientation: "vertical",
    cssClass: 'essb-wizard',
    onFinished: function (event, currentIndex) { jQuery("#wizard-form").submit(); },
	onStepChanged: function (event, currentIndex, priorIndex) {
		if (currentIndex == 2) {
			essb_option_show_hide('#essb-more-button-options', '#network_selection_more');
			essb_option_show_hide('#essb-twitter1,#essb-twitter2,#essb-twitter3,#essb-twitter4,#essb-twitter5,#essb-twitter6,#essb-twitter7', '#network_selection_twitter');
			essb_option_show_hide('#essb-twitter3,#essb-twitter6,#essb-twitter7', '#twitter_shareshort');

		}
		if (currentIndex == 6) {
			essb_option_show_hide('#essb-counter1,#essb-counter2,#essb-counter3,#essb-counter4,#essb-counter5,#essb-counter6,#essb-counter7', '#show_counter_yes');
		}

		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
	}
});

jQuery(document).ready(function(){
    jQuery('#networks-sortable').sortable();

    jQuery("#twitter_shareshort_service").change(function() {
    	essb_option_show_hide_based_on_value('#essb-twitter6,#essb-twitter7', '#twitter_shareshort_service', 'bit.ly');
    });
});


function essb_option_show_hide_based_on_value(oSelector, oSender, oValue) {
	if (oSelector.indexOf(",") > -1) {
		var obj = oSelector.split(",");

		for (var i=0;i<obj.length;i++) {
			var sender = jQuery(oSender).val();
			if (sender == oValue) {
				jQuery(obj[i]).show();
			}
			else {
				jQuery(obj[i]).hide();
			}
		}
	}
	else {
		var sender = jQuery(oSender).val();
		if (sender == oValue) {
			jQuery(oSelector).show();
		}
		else {
			jQuery(oSelector).hide();
		}
	}
}


function essb_option_show_hide(oSelector, oSender) {
	if (oSelector.indexOf(",") > -1) {
		var obj = oSelector.split(",");

		for (var i=0;i<obj.length;i++) {
			var sender = jQuery(oSender);
			if (sender.is(':checked')) {
				jQuery(obj[i]).show();
			}
			else {
				jQuery(obj[i]).hide();
			}
		}
	}
	else {
		var sender = jQuery(oSender);
		if (sender.is(':checked')) {
			jQuery(oSelector).show();
		}
		else {
			jQuery(oSelector).hide();
		}
	}
}

</script>