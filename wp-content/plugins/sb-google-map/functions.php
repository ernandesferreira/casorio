<?php
/*	 Plugin functions file
 *   Version: 1.0
 *   Author: SB Themes
 *   Profile: http://codecanyon.net/user/sbthemes?ref=sbthemes
 */

/**
 * Getting latitude and longitude from address using geocode api
 */
function sb_get_lat_lng_from_address($address) {
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(trim($address))."&sensor=false";
    return @file_get_contents($url);
}

/**
 * Conver string to Lowercase with no start/trail space
 */
function sb_lower_trim($string) {
	return strtolower(trim($string));
}

/**
 * Conver string to Uppercase with no start/trail space
 */
function sb_upper_trim($string) {
	return strtoupper(trim($string));
}

/**
 * Get Predefined icon
 */
function sb_get_icon($icon) {
	$icon = sb_lower_trim($icon);
	$preicons = array();
	for($icon_counter = 1; $icon_counter <= 10; $icon_counter++) {
		$preicons[] = 'blue-'.$icon_counter;
		$preicons[] = 'green-'.$icon_counter;
		$preicons[] = 'magenta-'.$icon_counter;
		$preicons[] = 'red-'.$icon_counter;
		$preicons[] = 'yellow-'.$icon_counter;
	}
	
	if ($icon == 'none') {
		$icon = SB_IMG_DIR_URL.'/lazyload.png';
	} else if (in_array($icon,$preicons)) {
		$icon = SB_ICONS_DIR_URL.'/'.$icon.'.png';
	} else {
		$icon = $icon;
	}
	return trim($icon);
	
}

/**
 * Display DB Field
 */
function display_field($string) {
	return htmlentities(stripslashes($string));
}

/**
 * Display Current Class if strings matched
 */
function toggle_radio_button_class($str1, $str2) {
	if(trim($str1) == trim($str2))
		echo 'radio-checked';
}