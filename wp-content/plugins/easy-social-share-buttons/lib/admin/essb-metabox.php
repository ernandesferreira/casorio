<?php

function essb_register_settings_metabox() {
	global $post;
	
	$essb_off = "false";
	
	if (isset ( $_GET ['action'] )) {
		$custom = get_post_custom ( $post->ID );
		
		// print_r($custom);
		
		$essb_off = isset ( $custom ["essb_off"] ) ? $custom ["essb_off"] [0] : "false";
	
	}
	
	wp_nonce_field ( 'essb_metabox_handler', 'essb_nonce' );
	
	?>

<div class="essb-meta">

	<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<col width="60%" />
		<col width="40%" />
		<tr class="even">
			<td class="bold">Turn off for current post:</td>
			<td><select class="input-element stretched" id="essb_off"
				name="essb_off">
					<option value="true"
						<?php echo (($essb_off == "true") ? " selected=\"selected\"": ""); ?>>Yes</option>
					<option value="false"
						<?php echo (($essb_off == "false") ? " selected=\"selected\"": ""); ?>>No</option>
			</select></td>
		</tr>
		<tr>
			<td colspan="2"><a href="#" id="essb-goto-advanced" class="button">Advanced Options</a></td>
		</tr>
				
	</table>
	<?php 
	
	if (ESSB_ESML_ACTIVE == 'true') {
		
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%" style="font-size: 11px;">';
		echo '<col width="60%"/>';
		echo '<col width="40%"/>';
		 
		$parse_list = array("facebook" => "Facebook", "twitter" => "Twitter", "googleplus" => "Google+", "pinterest" => "Pinterest", "linkedin" => "LinkedIn", "stumbleupon" => "StumbleUpon");
		
		$item = array();
		$item['esml_socialcount_total'] = (get_post_meta($post->ID, "esml_socialcount_TOTAL", true)) ? get_post_meta($post->ID, "esml_socialcount_TOTAL", true) : 0;
		$item['esml_socialcount_LAST_UPDATED'] = get_post_meta($post->ID, "esml_socialcount_LAST_UPDATED", true);
		foreach ($parse_list as $singleValueCode => $singleValue) {
			$item['esml_socialcount_'.$singleValueCode] = get_post_meta($post->ID, "esml_socialcount_$singleValueCode", true);
		}
		
		$total = $item['esml_socialcount_total'];
		
		echo '<tr style="background-color: #f4f4f4; padding: 4px;">';
		echo '<td colspan="2" style="padding: 10px 5px;"><strong>Easy Social Metrics Lite Report</strong></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td colspan="2"><strong>Data last updated '.EasySocialMetricsLite::timeago($item['esml_socialcount_LAST_UPDATED']).'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><strong>Total Social Shares:</strong></td>';
		echo '<td align="right"><strong>'.number_format($total).'</strong></td>';
		echo '</tr>';
		
		$cnt = 1;
		foreach ($parse_list as $singleValueCode => $singleValue) {
			$single_value = $item['esml_socialcount_'.$singleValueCode];
		
			$cnt = $cnt + 1;
			
			$style = "";
			
			if ($cnt % 2 == 0) {
				$style = " style=\"background-color: #f4f4f4;\"";
			}
 			
			if ($total != 0) {
				$display_percent = number_format($single_value * 100 / $total, 2);
				$percent = number_format($single_value * 100 / $total);
			}
			else {
				$display_percent = "0.00";
				$percent = "0";
			}
		
			if (intval($percent) == 0 && intval($single_value) != 0) {
				$percent = 1;
			}
		
			echo '<tr '.$style.'>';
			echo '<td>'.$singleValue.' <span style="background-color: #2980b9; padding: 2px 5px; color: #fff; font-size: 10px; border-radius: 3px;">'.$display_percent.' %</span></td>';
			echo '<td align="right"><strong>'.number_format(intval($single_value)).'</strong></td>';
			echo '</tr>';
			echo '<tr '.$style.'>';
			echo '<td colspan="2"><div style="background-color: #2980b9; display: block; height: 11px; width:'.$percent.'%;">&nbsp;</div></td>';
			echo '</tr>';
		}
		
		if (ESSB_SELF_ENABLED) {
			essb_self_hosted_counter_values();
		}
		 
		echo '</table>';
		
		
	}
	
	?>
</div>


<?php
}

function essb_register_advanced_metabox() {
	global $post;
	
	$essb_position = "";
	$essb_names = "";
	$essb_counter = "";
	$essb_theme = "";
	$essb_hidefb = "no";
	$essb_hideplusone = "no";
	$essb_hidevk = "no";
	$essb_hidetwitter = "no";
	$essb_counter_pos = "";
	$essb_sidebar_pos = "";
	$essb_total_counter_pos = "";
	
	$essb_post_share_message = "";
	$essb_post_share_url = "";
	$essb_post_share_image = "";
	$essb_post_share_text = "";
	$essb_post_fb_url = "";
	$essb_post_plusone_url = "";
	
	$essb_post_og_desc = "";
	$essb_post_og_title = "";
	$essb_post_og_image = "";
	
	$essb_post_twitter_desc = "";
	$essb_post_twitter_title = "";
	$essb_post_twitter_image = "";
	
	$essb_post_google_desc = "";
	$essb_post_google_title = "";
	$essb_post_google_image = "";
	
	
	$essb_post_twitter_hashtags = "";
	$essb_post_twitter_username = "";
	$essb_post_twitter_tweet = "";
	
	$essb_another_display_sidebar = "";
	$essb_another_display_popup = "";
	$essb_another_display_postfloat = "";
	
	$essb_activate_customizer = "";
	$essb_activate_fullwidth = "";
	$essb_post_og_video = "";
	$essb_hideyoutube = 'no';
	$essb_hidepinfollow = 'no';
	
	$essb_activate_nativeskinned = "";
	
	$essb_opt_by_bp = "";
	
	if (isset ( $_GET ['action'] )) {
		$custom = get_post_custom ( $post->ID );
		
		// print_r($custom);
		$essb_position = isset ( $custom ["essb_position"] ) ? $custom ["essb_position"] [0] : "";
		$essb_theme = isset ( $custom ["essb_theme"] ) ? $custom ["essb_theme"] [0] : "";
		$essb_names = isset ( $custom ["essb_names"] ) ? $custom ["essb_names"] [0] : "";
		$essb_counter = isset ( $custom ["essb_counter"] ) ? $custom ["essb_counter"] [0] : "";
		$essb_hidefb = isset ( $custom ["essb_hidefb"] ) ? $custom ["essb_hidefb"] [0] : "no";
		$essb_hideplusone = isset ( $custom ["essb_hideplusone"] ) ? $custom ["essb_hideplusone"] [0] : "no";
		$essb_hidevk = isset ( $custom ["essb_hidevk"] ) ? $custom ["essb_hidevk"] [0] : "no";
		$essb_hidetwitter = isset ( $custom ["essb_hidetwitter"] ) ? $custom ["essb_hidetwitter"] [0] : "no";
		
		$essb_sidebar_pos = isset ( $custom ["essb_sidebar_pos"] ) ? $custom ["essb_sidebar_pos"] [0] : "";
		$essb_counter_pos = isset ( $custom ["essb_counter_pos"] ) ? $custom ["essb_counter_pos"] [0] : "";
		$essb_total_counter_pos = isset ( $custom ["essb_total_counter_pos"] ) ? $custom ["essb_total_counter_pos"] [0] : "";
		
		$essb_hideyoutube = isset ( $custom ["essb_hideyoutube"] ) ? $custom ["essb_hideyoutube"] [0] : "no";
		$essb_hidepinfollow = isset ( $custom ["essb_hidepinfollow"] ) ? $custom ["essb_hidepinfollow"] [0] : "no";
		
		$essb_post_share_message = isset ( $custom ["essb_post_share_message"] ) ? $custom ["essb_post_share_message"] [0] : "";
		$essb_post_share_url = isset ( $custom ["essb_post_share_url"] ) ? $custom ["essb_post_share_url"] [0] : "";
		$essb_post_share_image = isset ( $custom ["essb_post_share_image"] ) ? $custom ["essb_post_share_image"] [0] : "";
		$essb_post_share_text = isset ( $custom ["essb_post_share_text"] ) ? $custom ["essb_post_share_text"] [0] : "";
		$essb_post_fb_url = isset ( $custom ["essb_post_fb_url"] ) ? $custom ["essb_post_fb_url"] [0] : "";
		$essb_post_plusone_url = isset ( $custom ["essb_post_plusone_url"] ) ? $custom ["essb_post_plusone_url"] [0] : "";
		
		$essb_post_share_message = stripslashes ( $essb_post_share_message );
		$essb_post_share_text = stripslashes ( $essb_post_share_text );
		
		$essb_post_og_desc = isset ( $custom ["essb_post_og_desc"] ) ? $custom ["essb_post_og_desc"] [0] : "";
		$essb_post_og_title = isset ( $custom ["essb_post_og_title"] ) ? $custom ["essb_post_og_title"] [0] : "";
		$essb_post_og_image = isset ( $custom ["essb_post_og_image"] ) ? $custom ["essb_post_og_image"] [0] : "";
		$essb_post_og_desc = stripslashes ( $essb_post_og_desc );
		$essb_post_og_title = stripslashes ( $essb_post_og_title );
		$essb_post_og_video = isset ( $custom ["essb_post_og_video"] ) ? $custom ["essb_post_og_video"] [0] : "";
		
		$essb_post_twitter_desc = isset ( $custom ["essb_post_twitter_desc"] ) ? $custom ["essb_post_twitter_desc"] [0] : "";
		$essb_post_twitter_title = isset ( $custom ["essb_post_twitter_title"] ) ? $custom ["essb_post_twitter_title"] [0] : "";
		$essb_post_twitter_image = isset ( $custom ["essb_post_twitter_image"] ) ? $custom ["essb_post_twitter_image"] [0] : "";
		$essb_post_twitter_desc = stripslashes ( $essb_post_twitter_desc );
		$essb_post_twitter_title = stripslashes ( $essb_post_twitter_title );

		$essb_post_google_desc = isset ( $custom ["essb_post_google_desc"] ) ? $custom ["essb_post_google_desc"] [0] : "";
		$essb_post_google_title = isset ( $custom ["essb_post_google_title"] ) ? $custom ["essb_post_google_title"] [0] : "";
		$essb_post_google_image = isset ( $custom ["essb_post_google_image"] ) ? $custom ["essb_post_google_image"] [0] : "";
		$essb_post_google_desc = stripslashes ( $essb_post_google_desc );
		$essb_post_google_title = stripslashes ( $essb_post_google_title );
		
		
		$essb_post_twitter_hashtags = isset ( $custom ['essb_post_twitter_hashtags'] ) ? $custom ['essb_post_twitter_hashtags'] [0] : "";
		$essb_post_twitter_username = isset ( $custom ['essb_post_twitter_username'] ) ? $custom ['essb_post_twitter_username'] [0] : "";
		$essb_post_twitter_tweet = isset ( $custom ['essb_post_twitter_tweet'] ) ? $custom ['essb_post_twitter_tweet'] [0] : "";
		
		$essb_another_display_sidebar = isset ( $custom ['essb_another_display_sidebar'] ) ? $custom ['essb_another_display_sidebar'] [0] : "";
		$essb_another_display_popup = isset ( $custom ['essb_another_display_popup'] ) ? $custom ['essb_another_display_popup'] [0] : "";
		$essb_another_display_postfloat = isset ( $custom ['essb_another_display_postfloat'] ) ? $custom ['essb_another_display_postfloat'] [0] : "";
		
		$essb_activate_customizer = isset ( $custom ['essb_activate_customizer'] ) ? $custom ['essb_activate_customizer'] [0] : "";
		$essb_activate_fullwidth = isset ( $custom ['essb_activate_fullwidth'] ) ? $custom ['essb_activate_fullwidth'] [0] : "";
		//essb_activate_nativeskinned	
		$essb_activate_nativeskinned = isset ( $custom ['essb_activate_nativeskinned'] ) ? $custom ['essb_activate_nativeskinned'] [0] : "";
		
		$essb_opt_by_bp = isset ( $custom ['essb_opt_by_bp'] ) ? $custom ['essb_opt_by_bp'] [0] : "";
	}
	
	?>

<div class="essb-options">
	<div class="essb-options-sidebar">
		<ul class="essb-options-group-menu">
			<li id="essb-menu-1" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('1'); return false;">Visual Settings</a></li>
			<li id="essb-menu-2" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('2'); return false;">Hide Native
					Social Like and Subscribe Buttons</a></li>
			<li id="essb-menu-5" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('5'); return false;">Social Sharing Optimization</a></li>
			<li id="essb-menu-6" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('6'); return false;">Twitter Tweet Options</a></li>
			<li id="essb-menu-3" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('3'); return false;">Custom Share
					Message</a></li>
			<li id="essb-menu-4" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('4'); return false;">Facebook Like and
					Google +1</a></li>
			<li id="essb-menu-7" class="essb-menu-item"><a href="#"
				onclick="essb_option_activate('7'); return false;">Advanced Custom Share</a></li>					
		</ul>
	</div>
	<div class="essb-options-container" style="min-height: 400px;">
		<div id="essb-container-1" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Visual Settings</td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Template:</td>
					<td><select class="input-element stretched" id="essb_theme"
						name="essb_theme">
							<option value="">From Settings</option>
							<option value="1"
								<?php echo (($essb_theme == "1") ? " selected=\"selected\"": ""); ?>>Default</option>
							<option value="2"
								<?php echo (($essb_theme == "2") ? " selected=\"selected\"": ""); ?>>Metro</option>
							<option value="3"
								<?php echo (($essb_theme == "3") ? " selected=\"selected\"": ""); ?>>Modern</option>
							<option value="4"
								<?php echo (($essb_theme == "4") ? " selected=\"selected\"": ""); ?>>Round</option>
							<option value="5"
								<?php echo (($essb_theme == "5") ? " selected=\"selected\"": ""); ?>>Big</option>
							<option value="6"
								<?php echo (($essb_theme == "6") ? " selected=\"selected\"": ""); ?>>Metro
								(Retina)</option>
							<option value="7"
								<?php echo (($essb_theme == "7") ? " selected=\"selected\"": ""); ?>>Big
								(Retina)</option>
							<option value="8"
								<?php echo (($essb_theme == "8") ? " selected=\"selected\"": ""); ?>>Light
								(Retina)</option>
							<option value="9"
								<?php echo (($essb_theme == "9") ? " selected=\"selected\"": ""); ?>>Flat
								(Retina)</option>
							<option value="10"
								<?php echo (($essb_theme == "10") ? " selected=\"selected\"": ""); ?>>Tiny
								(Retina)</option>
							<option value="11"
								<?php echo (($essb_theme == "11") ? " selected=\"selected\"": ""); ?>>Round
								(Retina)</option>								
							<option value="12"
								<?php echo (($essb_theme == "12") ? " selected=\"selected\"": ""); ?>>Modern
								(Retina)</option>								
								
					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Hide Network Names:</td>
					<td><select class="input-element stretched" id="essb_names"
						name="essb_names">
							<option value="">From Settings</option>
							<option value="1"
								<?php echo (($essb_names == "1") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="0"
								<?php echo (($essb_names == "0") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Position of buttons:</td>
					<td><select class="input-element stretched" id="essb_position"
						name="essb_position">
							<option value="">From Settings</option>
							</option>



							<option value="bottom"
								<?php echo (($essb_position == "bottom") ? " selected=\"selected\"": ""); ?>>Bottom</option>

							<option value="top"
								<?php echo (($essb_position == "top") ? " selected=\"selected\"": ""); ?>>Top</option>
							<option value="both"
								<?php echo (($essb_position == "both") ? " selected=\"selected\"": ""); ?>>Top and Bottom</option>
							<option value="float"
								<?php echo (($essb_position == "float") ? " selected=\"selected\"": ""); ?>>Float</option>
							<option value="sidebar"
								<?php echo (($essb_position == "sidebar") ? " selected=\"selected\"": ""); ?>>Sidebar</option>
							<option value="popup"
								<?php echo (($essb_position == "popup") ? " selected=\"selected\"": ""); ?>>Popup</option>
							<option value="likeshare"
								<?php echo (($essb_position == "likeshare") ? " selected=\"selected\"": ""); ?>>Like/Share</option>
							<option value="sharelike"
								<?php echo (($essb_position == "sharelike") ? " selected=\"selected\"": ""); ?>>Share/Like</option>
							<option value="postfloat" <?php echo (($essb_position == "postfloat") ? " selected=\"selected\"": ""); ?>>Post Vertical Float</option>
							<option value="inline" <?php echo (($essb_position == "inline") ? " selected=\"selected\"": ""); ?>>Content Inline</option>
							</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Display Counters:</td>
					<td><select class="input-element stretched" id="essb_counter"
						name="essb_counter">
							<option value="">From Settings</option>
							<option value="1"
								<?php echo (($essb_counter == "1") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="0"
								<?php echo (($essb_counter == "0") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Counters Position:</td>
					<td><select class="input-element stretched" id="essb_counter_pos"
						name="essb_counter_pos">
							<option value="">From Settings</option>
							<option value="left"
								<?php echo (($essb_counter_pos == "left") ? " selected=\"selected\"": ""); ?>>Left</option>
							<option value="right"
								<?php echo (($essb_counter_pos == "right") ? " selected=\"selected\"": ""); ?>>Right</option>
							<option value="inside"
								<?php echo (($essb_counter_pos == "inside") ? " selected=\"selected\"": ""); ?>>Inside</option>
							<option value="insidename"
								<?php echo (($essb_counter_pos == "insidename") ? " selected=\"selected\"": ""); ?>>Inside with Network Names</option>
							<option value="hidden"
								<?php echo (($essb_counter_pos == "hidden") ? " selected=\"selected\"": ""); ?>>Hidden</option>
							<option value="leftm"
								<?php echo (($essb_counter_pos == "leftm") ? " selected=\"selected\"": ""); ?>>Left Modern</option>
							<option value="rightm"
								<?php echo (($essb_counter_pos == "rightm") ? " selected=\"selected\"": ""); ?>>Right Modern</option>
							<option value="top"
								<?php echo (($essb_counter_pos == "top") ? " selected=\"selected\"": ""); ?>>Top Modern</option>
							<option value="topm"
								<?php echo (($essb_counter_pos == "topm") ? " selected=\"selected\"": ""); ?>>Top Mini</option>
								
					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Total Counter Position:</td>
					<td><select class="input-element stretched"
						id="essb_total_counter_pos" name="essb_total_counter_pos">
							<option value="">From Settings</option>
							<option value="left"
								<?php echo (($essb_total_counter_pos == "left") ? " selected=\"selected\"": ""); ?>>Left</option>
							<option value="right"
								<?php echo (($essb_total_counter_pos == "right") ? " selected=\"selected\"": ""); ?>>Right</option>
							<option value="leftbig"
								<?php echo (($essb_total_counter_pos == "leftbig") ? " selected=\"selected\"": ""); ?>>Left
								Big</option>
							<option value="rightbig"
								<?php echo (($essb_total_counter_pos == "rightbig") ? " selected=\"selected\"": ""); ?>>Right
								Big</option>
							<option value="hidden"
								<?php echo (($essb_total_counter_pos == "hidden") ? " selected=\"selected\"": ""); ?>>Hidden</option>

					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Sidebar Position:</td>
					<td><select class="input-element stretched" id="essb_sidebar_pos"
						name="essb_sidebar_pos">
							<option value="">From Settings</option>
							<option value="left"
								<?php echo (($essb_sidebar_pos == "left") ? " selected=\"selected\"": ""); ?>>Left</option>
							<option value="right"
								<?php echo (($essb_sidebar_pos == "right") ? " selected=\"selected\"": ""); ?>>Right</option>
							<option value="bottom"
								<?php echo (($essb_sidebar_pos == "bottom") ? " selected=\"selected\"": ""); ?>>Bottom</option>
							<option value="top"
								<?php echo (($essb_sidebar_pos == "top") ? " selected=\"selected\"": ""); ?>>Top</option>
								
					</select></td>
				</tr>
				
				<tr class="odd table-border-bottom">
					<td>Activate Sidebar as another display:</td>
					<td><select class="input-element stretched" id="essb_another_display_sidebar"
						name="essb_another_display_sidebar">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_another_display_sidebar == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_another_display_sidebar == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Activate Popup as another display:</td>
					<td><select class="input-element stretched" id="essb_another_display_popup"
						name="essb_another_display_popup">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_another_display_popup == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_another_display_popup == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Activate Post Vertical Float as another display:</td>
					<td><select class="input-element stretched" id="essb_another_display_postfloat"
						name="essb_another_display_postfloat">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_another_display_postfloat == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_another_display_postfloat == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>				
				<tr class="even table-border-bottom">
					<td>Activate color customizer:</td>
					<td><select class="input-element stretched" id="essb_activate_customizer"
						name="essb_activate_customizer">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_activate_customizer == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_activate_customizer == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Activate full width share buttons:</td>
					<td><select class="input-element stretched" id="essb_activate_fullwidth"
						name="essb_activate_fullwidth">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_activate_fullwidth == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_activate_fullwidth == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Activate native buttons skin:</td>
					<td><select class="input-element stretched" id="essb_activate_nativeskinned"
						name="essb_activate_nativeskinned">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_activate_nativeskinned == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_activate_nativeskinned == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Activate custom settings by button position:</td>
					<td><select class="input-element stretched" id="essb_opt_by_bp"
						name="essb_opt_by_bp">
							<option value="">From Settings</option>
							<option value="no"
								<?php echo (($essb_opt_by_bp == "no") ? " selected=\"selected\"": ""); ?>>No</option>
							<option value="yes"
								<?php echo (($essb_opt_by_bp == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
					</select></td>
				</tr>				
			</table>
		</div>
		<div id="essb-container-2" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Hide Native Social Like and Subscribe
						Buttons</td>
				</tr>
				<tr class="even table-border-bottom">
					<td>Facebook Like Button:</td>
					<td><select class="input-element stretched" id="essb_hidefb"
						name="essb_hidefb">
							<option value="yes"
								<?php echo (($essb_hidefb == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="no"
								<?php echo (($essb_hidefb == "no") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Google Plus One Button:</td>
					<td><select class="input-element stretched" id="essb_hideplusone"
						name="essb_hideplusone">
							<option value="yes"
								<?php echo (($essb_hideplusone == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="no"
								<?php echo (($essb_hideplusone == "no") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>VKontakte Like Button:</td>
					<td><select class="input-element stretched" id="essb_hidevk"
						name="essb_hidevk">
							<option value="yes"
								<?php echo (($essb_hidevk == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="no"
								<?php echo (($essb_hidevk == "no") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Twitter Tweet/Follow Button:</td>
					<td><select class="input-element stretched" id="essb_hidetwitter"
						name="essb_hidetwitter">
							<option value="yes"
								<?php echo (($essb_hidetwitter == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="no"
								<?php echo (($essb_hidetwitter == "no") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="even table-border-bottom">
					<td>YouTube Subscribe:</td>
					<td><select class="input-element stretched" id="essb_hideyoutube"
						name="essb_hideyoutube">
							<option value="yes"
								<?php echo (($essb_hideyoutube == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="no"
								<?php echo (($essb_hideyoutube == "no") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td>Pinterest Pin/Follow Button:</td>
					<td><select class="input-element stretched" id="essb_hidepinfollow"
						name="essb_hidepinfollow">
							<option value="yes"
								<?php echo (($essb_hidepinfollow == "yes") ? " selected=\"selected\"": ""); ?>>Yes</option>
							<option value="no"
								<?php echo (($essb_hidepinfollow == "no") ? " selected=\"selected\"": ""); ?>>No</option>

					</select></td>
				</tr>
			</table>
		</div>
		<div id="essb-container-3" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Custom Share Message</td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Custom Share Message:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_share_message"
						name="essb_post_share_message"
						value="<?php echo $essb_post_share_message; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Custom Share URL:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_share_url"
						name="essb_post_share_url"
						value="<?php echo $essb_post_share_url; ?>" /></td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Custom Share Image URL (Facebook,
						Pinterest only):</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_share_image"
						name="essb_post_share_image"
						value="<?php echo $essb_post_share_image; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Custom Share Description (Facebook,
						Pinterest only):</td>
					<td class="essb_general_options"><textarea
							class="input-element stretched" id="essb_post_share_text"
							name="essb_post_share_text" rows="5"><?php echo $essb_post_share_text; ?></textarea></td>
				</tr>
			</table>
		</div>
		<div id="essb-container-4" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Facebook Like and Google +1</td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Address for Facebook Like Button:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_fb_url"
						name="essb_post_fb_url" value="<?php echo $essb_post_fb_url; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Address for Google +1 Button:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_plusone_url"
						name="essb_post_plusone_url"
						value="<?php echo $essb_post_plusone_url ?>" /></td>
				</tr>
			</table>
		</div>
		<div id="essb-container-5" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Social Sharing Optimization</td>
				</tr>
				<tr class="table-border-bottom">
					<td colspan="2" class="sub2">Open Graph Meta Tags</td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Title:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_og_title"
						name="essb_post_og_title"
						value="<?php echo $essb_post_og_title; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Image:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_og_image"
						name="essb_post_og_image"
						value="<?php echo $essb_post_og_image; ?>" /></td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Description:</td>
					<td class="essb_general_options"><textarea
							class="input-element stretched" id="essb_post_og_desc"
							name="essb_post_og_desc" rows="5"><?php echo $essb_post_og_desc; ?></textarea></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Video:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_og_video"
						name="essb_post_og_video"
						value="<?php echo $essb_post_og_video; ?>" /></td>
				</tr>
				<tr class="table-border-bottom">
					<td colspan="2" class="sub2">Twitter Cards Meta Tags</td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Title:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_twitter_title"
						name="essb_post_twitter_title"
						value="<?php echo $essb_post_twitter_title; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Image:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_twitter_image"
						name="essb_post_twitter_image"
						value="<?php echo $essb_post_twitter_image; ?>" /></td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Description:</td>
					<td class="essb_general_options"><textarea
							class="input-element stretched" id="essb_post_twitter_desc"
							name="essb_post_twitter_desc" rows="5"><?php echo $essb_post_twitter_desc; ?></textarea></td>
				</tr>				
				<tr class="table-border-bottom">
					<td colspan="2" class="sub2">Google Meta Tags</td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Title:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_google_title"
						name="essb_post_google_title"
						value="<?php echo $essb_post_google_title; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Image:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_google_image"
						name="essb_post_google_image"
						value="<?php echo $essb_post_google_image; ?>" /></td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Description:</td>
					<td class="essb_general_options"><textarea
							class="input-element stretched" id="essb_post_google_desc"
							name="essb_post_google_desc" rows="5"><?php echo $essb_post_google_desc; ?></textarea></td>
				</tr>				
				</table>
		</div>
		<div id="essb-container-6" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Twitter Tweet Options</td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Twitter username to be mentioned:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_twitter_username"
						name="essb_post_twitter_username"
						value="<?php echo $essb_post_twitter_username; ?>" /></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td valign="top" class="bold">Twitter hashtags to be added:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_twitter_hashtags"
						name="essb_post_twitter_hashtags"
						value="<?php echo $essb_post_twitter_hashtags; ?>" /></td>
				</tr>
				<tr class="even table-border-bottom">
					<td valign="top" class="bold">Twitter customized Tweet:</td>
					<td class="essb_general_options"><input type="text"
						class="input-element stretched" id="essb_post_twitter_tweet"
						name="essb_post_twitter_tweet"
						value="<?php echo $essb_post_twitter_tweet; ?>" /></td>
				</tr>				
			</table>
		</div>
		<div id="essb-container-7" class="essb-data-container">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<col width="30%" />
				<col width="70%" />
				<tr>
					<td class="sub" colspan="2">Advanced Custom Share</td>
				</tr>
				<?php essb_metabox_advanced_network_share(); ?>
				</table>
				</div>
	</div>
	<script type="text/javascript">

jQuery(document).ready(function(){
    //jQuery('#networks-sortable').sortable();
    essb_option_activate('1');
    jQuery("#essb-goto-advanced").click(function() {
        jQuery('html, body').animate({
            scrollTop: jQuery("#essb_advanced").offset().top
        }, 500);
    });
});

</script>
</div>
	<?php
}


function essb_metabox_advanced_network_share() {
	global $post;
	
	$essb_as = '';
	if (isset ( $_GET ['action'] )) {		
		$essb_as = get_post_meta($post->ID, 'essb_as', true);
	}
	
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
				
			if (isset ( $essb_as ) && $essb_as != '') {
				$settings = $essb_as;

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
			echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $url_pass . '" name="essb_as[' . $k . '_u]" type="text" class="input-element stretched" /></td>';
			echo '</tr>';
				
			if ($k == "facebook" || $k == "twitter" || $k == "pinterest" || $k == "tumblr" || $k == "digg" || $k == "linkedin" || $k == "reddit" || $k == "del" || $k == "buffer") {
				echo '<tr class="odd table-border-bottom">';
				echo '<td class="bold">Message:</td>';
				echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $message_pass . '" name="essb_as[' . $k . '_t]" type="text" class="input-element stretched" /></td>';
				echo '</tr>';
					
			}
			if ($k == "facebook" || $k == "pinterest") {
				echo '<tr class="even table-border-bottom">';
				echo '<td class="bold">Image:</td>';
				echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $image_pass . '" name="essb_as[' . $k . '_i]" type="text" class="input-element stretched" /></td>';
				echo '</tr>';
					
			}
				
			if ($k == "facebook" || $k == "pinterest") {
				echo '<tr class="odd table-border-bottom">';
				echo '<td class="bold">Description:</td>';
				echo '<td class="essb_options_general"><input id="network_selection_' . $k . '" value="' . $desc_pass . '" name="essb_as[' . $k . '_d]" type="text" class="input-element stretched" /></td>';
				echo '</tr>';
					
			}

		}
	}

}

function essb_self_hosted_counter_values() {
	global $post;
	
	$is_saved_post = false;
	if (isset ( $_GET ['action'] )) {
		$is_saved_post = true;
	}
	
	if (!$is_saved_post) { return; }
	
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	echo '<table border="0" cellpadding="3" cellspacing="0" width="100%" style="font-size: 11px;">';
	echo '<col width="60%"/>';
	echo '<col width="40%"/>';
	echo '<tr style="background-color: #f4f4f4; padding: 4px;">';
	echo '<td colspan="2" style="padding: 10px 5px;"><strong>Self Hosted Counters</strong></td>';
	echo '</tr>';
	if (is_array ( $options )) {
		foreach ( $options ['networks'] as $k => $v ) {
	
			if ($k == "mail" || $k == "print" || $k == "love") {
				continue;
			}
	
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
	
			$counter_value =  get_post_meta($post->ID, 'essb_self_'.$k, true);
		
	
	
			echo '<tr class="even table-border-bottom">';
			echo '<td class="bold">'.$network_name.':</td>';
			echo '<td class="essb_options_general"><input id="essb_self_' . $k . '" value="' . $counter_value . '" name="essb_self_'.$k.'" type="text" class="input-element stretched" /></td>';
			echo '</tr>';
	
	
		}
	}
	echo '</table>';
}

?>