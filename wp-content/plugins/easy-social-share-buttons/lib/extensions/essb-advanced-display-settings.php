<?php

/**
 * Advnaced display options class parser
 * @author appscreo
 * @since 1.3.9.8
 *
 */
class EasySocialShareButtons_Advanced_Display {
	
	public static function get_options_by_bp_activate_state() {
		global $post;
		
		$self_result = false;
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		
		$activate_opt_by_bp = isset ( $options ['activate_opt_by_bp'] ) ? $options ['activate_opt_by_bp'] : 'false';
		if ($activate_opt_by_bp == "true") {
			$self_result = true;
		}
		
		$post_active_opt_by_bp = get_post_meta ( $post->ID, 'essb_opt_by_bp', true );
		if ($post_active_opt_by_bp == "yes") {
			$self_result = true;
		}
		if ($post_active_opt_by_bp == "no") {
			$self_result = false;
		}
		return $self_result;
	}
	
	public static function get_options_by_bp($selectedPt) {
		global $post;
		
		$result = array ();
		$result ['active'] = false;
		
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		
		if (isset ( $post )) {
			$activate_opt_by_bp = isset ( $options ['activate_opt_by_bp'] ) ? $options ['activate_opt_by_bp'] : 'false';
			if ($activate_opt_by_bp == "true") {
				$result ['active'] = true;
			}
			
			$post_active_opt_by_bp = get_post_meta ( $post->ID, 'essb_opt_by_bp', true );
			
			$options_by_bp = array ();
			if (is_array ( $options )) {
				if (isset ( $options ['opt_by_bp'] )) {
					$options_by_bp = $options ['opt_by_bp'];
				}
			}
			
			if ($post_active_opt_by_bp != '') {
				if ($post_active_opt_by_bp == 'yes') {
					$result ['active'] = true;
				} else {
					$result ['active'] = false;
				}
			}
			
			$pt_networks = isset ( $options_by_bp [$selectedPt . '_networks'] ) ? $options_by_bp [$selectedPt . '_networks'] : array ();
			if (count ( $pt_networks ) > 0) {
				$result ['networks'] = $pt_networks;
			}
			
			$pt_names = isset ( $options_by_bp [$selectedPt . '_names'] ) ? $options_by_bp [$selectedPt . '_names'] : array ();
			if (count ( $pt_names ) > 0) {
				$result ['names'] = $pt_names;
			}
			
			$pt_fullwidth = isset ( $options_by_bp [$selectedPt . '_fullwidth'] ) ? $options_by_bp [$selectedPt . '_fullwidth'] : '';
			if ($pt_fullwidth != '') {
				if ($pt_fullwidth == "yes") {
					$result ['fullwidth'] = true;
				} else {
					$result ['fullwidth'] = false;
				}
				
				$pt_fullwidth_value = isset ( $options_by_bp [$selectedPt . '_fullwidth_value'] ) ? $options_by_bp [$selectedPt . '_fullwidth_value'] : '';
				if ($pt_fullwidth_value != '') {
					$result ['fullwidth_value'] = $pt_fullwidth_value;
				}
			}
			
			$pt_hidenames = isset ( $options_by_bp [$selectedPt . '_hidenames'] ) ? $options_by_bp [$selectedPt . '_hidenames'] : '';
			if ($pt_hidenames != '') {
				if ($pt_hidenames == "yes") {
					$result ['hidenames'] = '1';
				} else {
					$result ['hidenames'] = '0';
				}
			}
			
			$pt_counters = isset ( $options_by_bp [$selectedPt . '_counters'] ) ? $options_by_bp [$selectedPt . '_counters'] : '';
			if ($pt_counters != '') {
				if ($pt_counters == "yes") {
					$result ['counters'] = '1';
				} else {
					$result ['counters'] = '0';
				}
			}
			
			$pt_counters_pos = isset ( $options_by_bp [$selectedPt . '_counters_pos'] ) ? $options_by_bp [$selectedPt . '_counters_pos'] : '';
			if ($pt_counters_pos != '') {
				$result ['counters_pos'] = $pt_counters_pos;
			}
			
			$pt_total_counters_pos = isset ( $options_by_bp [$selectedPt . '_total_counters_pos'] ) ? $options_by_bp [$selectedPt . '_total_counters_pos'] : '';
			if ($pt_total_counters_pos != '') {
				$result ['total_counters_pos'] = $pt_total_counters_pos;
			}
			
			$pt_template = isset ( $options_by_bp [$selectedPt . '_template'] ) ? $options_by_bp [$selectedPt . '_template'] : '';
			if ($pt_template != '') {
				$result ['template'] = EasySocialShareButtons_Advanced_Display::get_template_slug ( $pt_template );
			}
		
		}
		
		return $result;
	}
	
	/**
	 * get_options_by_pt
	 * ---
	 * Generates specific options by post type
	 *
	 * @since 1.3.9.5
	 *       
	 * @return array of settings
	 */
	public static function get_options_by_pt() {
		global $post;
		
		$result = array ();
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		$options_by_pt_active = false;
		// @since 1.3.9.5 - we can have specific post type options
		$activate_opt_by_pt = isset($options['activate_opt_by_pt']) ? $options['activate_opt_by_pt'] : 'false';
		if ($activate_opt_by_pt == "true") {
			$options_by_pt_active = true;
		}
		
		if (isset ( $post ) && $options_by_pt_active) {

			$selectedPt = $post->post_type;
			
			$options_by_pt = "";
			if (is_array ( $options )) {
				if (isset ( $options ['opt_by_pt'] )) {
					$options_by_pt = $options ['opt_by_pt'];
				}
			}
			
			// get custom options by pt;
			$pt_position = isset ( $options_by_pt [$selectedPt . '_position'] ) ? $options_by_pt [$selectedPt . '_position'] : '';
			if ($pt_position != '') {
				$result ['position'] = $pt_position;
			}
			
			$pt_template = isset ( $options_by_pt [$selectedPt . '_template'] ) ? $options_by_pt [$selectedPt . '_template'] : '';
			if ($pt_template != '') {
				$result ['template'] = $pt_template;
			}
			
			$pt_hidenames = isset ( $options_by_pt [$selectedPt . '_hidenames'] ) ? $options_by_pt [$selectedPt . '_hidenames'] : '';
			if ($pt_hidenames != '') {
				if ($pt_hidenames == "yes") {
					$result ['hidenames'] = '1';
				} else {
					$result ['hidenames'] = '0';
				}
			}
			
			$pt_counters = isset ( $options_by_pt [$selectedPt . '_counters'] ) ? $options_by_pt [$selectedPt . '_counters'] : '';
			if ($pt_counters != '') {
				if ($pt_counters == "yes") {
					$result ['counters'] = '1';
				} else {
					$result ['counters'] = '0';
				}
			}
			
			$pt_counters_pos = isset ( $options_by_pt [$selectedPt . '_counters_pos'] ) ? $options_by_pt [$selectedPt . '_counters_pos'] : '';
			if ($pt_counters_pos != '') {
				$result ['counters_pos'] = $pt_counters_pos;
			}
			
			$pt_total_counters_pos = isset ( $options_by_pt [$selectedPt . '_total_counters_pos'] ) ? $options_by_pt [$selectedPt . '_total_counters_pos'] : '';
			if ($pt_total_counters_pos != '') {
				$result ['total_counters_pos'] = $pt_total_counters_pos;
			}
			
			$pt_sidebar_pos = isset ( $options_by_pt [$selectedPt . '_sidebar_pos'] ) ? $options_by_pt [$selectedPt . '_sidebar_pos'] : '';
			if ($pt_sidebar_pos != '') {
				$result ['sidebar_pos'] = $pt_sidebar_pos;
			}
			
			$pt_another_display_sidebar = isset ( $options_by_pt [$selectedPt . '_another_display_sidebar'] ) ? $options_by_pt [$selectedPt . '_another_display_sidebar'] : '';
			if ($pt_another_display_sidebar != '') {
				if ($pt_another_display_sidebar == 'yes') {
					$result ['another_display_sidebar'] = '1';
				} else {
					$result ['another_display_sidebar'] = '0';
				}
			}
			
			$pt_another_display_popup = isset ( $options_by_pt [$selectedPt . '_another_display_popup'] ) ? $options_by_pt [$selectedPt . '_another_display_popup'] : '';
			if ($pt_another_display_popup != '') {
				if ($pt_another_display_popup == 'yes') {
					$result ['another_display_popup'] = '1';
				} else {
					$result ['another_display_popup'] = '0';
				}
			}
			
			$pt_another_display_postfloat = isset ( $options_by_pt [$selectedPt . '_another_display_postfloat'] ) ? $options_by_pt [$selectedPt . '_another_display_postfloat'] : '';
			if ($pt_another_display_postfloat != '') {
				if ($pt_another_display_postfloat == 'yes') {
					$result ['another_display_postfloat'] = '1';
				} else {
					$result ['another_display_postfloat'] = '0';
				}
			}
			
			$pt_networks = isset ( $options_by_pt [$selectedPt . '_networks'] ) ? $options_by_pt [$selectedPt . '_networks'] : array ();
			if (count ( $pt_networks ) > 0) {
				$result ['networks'] = $pt_networks;
			}
		}
		
		return $result;
	}
	
	public static function get_template_slug($loaded_template_id) {
		$loaded_template_id = intval ( $loaded_template_id );
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
		
		return $loaded_template;
	}
	
	public static function get_options_by_mp($selectedPt) {
		global $post;
		
		$result = array ();
		$result ['active'] = true;
		
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		
		$options_by_bp = array ();
		if (is_array ( $options )) {
			if (isset ( $options ['opt_by_mp'] )) {
				$options_by_bp = $options ['opt_by_mp'];
			}
		}

		
		$pt_networks = isset ( $options_by_bp [$selectedPt . '_networks'] ) ? $options_by_bp [$selectedPt . '_networks'] : array ();
		if (count ( $pt_networks ) > 0) {
			$result ['networks'] = $pt_networks;
		}
		
		$pt_names = isset ( $options_by_bp [$selectedPt . '_names'] ) ? $options_by_bp [$selectedPt . '_names'] : array ();
		if (count ( $pt_names ) > 0) {
			$result ['names'] = $pt_names;
		}
		
		$pt_fullwidth = isset ( $options_by_bp [$selectedPt . '_fullwidth'] ) ? $options_by_bp [$selectedPt . '_fullwidth'] : '';
		if ($pt_fullwidth != '') {
			if ($pt_fullwidth == "yes") {
				$result ['fullwidth'] = true;
			} else {
				$result ['fullwidth'] = false;
			}
			
			$pt_fullwidth_value = isset ( $options_by_bp [$selectedPt . '_fullwidth_value'] ) ? $options_by_bp [$selectedPt . '_fullwidth_value'] : '';
			if ($pt_fullwidth_value != '') {
				$result ['fullwidth_value'] = $pt_fullwidth_value;
			}
		}
		
		$pt_hidenames = isset ( $options_by_bp [$selectedPt . '_hidenames'] ) ? $options_by_bp [$selectedPt . '_hidenames'] : '';
		if ($pt_hidenames != '') {
			if ($pt_hidenames == "yes") {
				$result ['hidenames'] = '1';
			} else {
				$result ['hidenames'] = '0';
			}
		}
		
		$pt_counters = isset ( $options_by_bp [$selectedPt . '_counters'] ) ? $options_by_bp [$selectedPt . '_counters'] : '';
		if ($pt_counters != '') {
			if ($pt_counters == "yes") {
				$result ['counters'] = '1';
			} else {
				$result ['counters'] = '0';
			}
		}
		
		$pt_counters_pos = isset ( $options_by_bp [$selectedPt . '_counters_pos'] ) ? $options_by_bp [$selectedPt . '_counters_pos'] : '';
		if ($pt_counters_pos != '') {
			$result ['counters_pos'] = $pt_counters_pos;
		}
		
		$pt_total_counters_pos = isset ( $options_by_bp [$selectedPt . '_total_counters_pos'] ) ? $options_by_bp [$selectedPt . '_total_counters_pos'] : '';
		if ($pt_total_counters_pos != '') {
			$result ['total_counters_pos'] = $pt_total_counters_pos;
		}
		
		$pt_template = isset ( $options_by_bp [$selectedPt . '_template'] ) ? $options_by_bp [$selectedPt . '_template'] : '';
		if ($pt_template != '') {
			$result ['template'] = EasySocialShareButtons_Advanced_Display::get_template_slug ( $pt_template );
		}
		
		return $result;
	}

}

?>