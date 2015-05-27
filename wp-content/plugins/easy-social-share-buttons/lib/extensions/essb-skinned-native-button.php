<?php

class ESSB_Skinned_Native_Button {
	
	const extension_version = "1.0.0";

	public static $text_replace = array();
	
	public static function generateStyleCustomizerCSS ($options) {
		$network_list = array("fb", "vk", "google", "twitter", "pinterest", "youtube", "linkedin");
		
		$css = "";
		
		foreach ($network_list as $net) {
			$color = isset($options['skinned_'.$net.'_color']) ? $options['skinned_'.$net.'_color'] : '';
			$hovercolor = isset($options['skinned_'.$net.'_hovercolor']) ? $options['skinned_'.$net.'_hovercolor'] : '';
			$textcolor = isset($options['skinned_'.$net.'_textcolor']) ? $options['skinned_'.$net.'_textcolor'] : '';
			$width = isset($options['skinned_'.$net.'_width']) ? $options['skinned_'.$net.'_width'] : '';
			
			$selector = $net;
			if ($net == "fb") { $selector = "facebook"; }

			if ($color != '') {
				$css .= '.essb-native-'.$selector.' .essb-native-text { background-color: '.$color.'!important;}';
			}
			if ($hovercolor != '') {
				$css .= '.essb-native-'.$selector.' { background-color: '.$hovercolor.'!important;}';
			}
			if ($textcolor != '') {
				$css .= '.essb-native-'.$selector.' .essb-native-text { color: '.$textcolor.'!important;}';
			}
			if ($width != '') {
				$css .= '.essb-native-'.$selector.' { width: '.$width.'px!important;}';
			}
			
			$text = isset($options['skinned_'.$net.'_text']) ? $options['skinned_'.$net.'_text'] : '';
			
			if ($text != '') {
				self::$text_replace[$selector] = $text;
			}
		}
		
		return $css;
	}
	
	public static function generateButton($type, $code, $text = '', $force_text = '', $width = '', $user_skin) {
		
		$text_replace = isset(self::$text_replace[$type]) ? self::$text_replace[$type] : '';
		
		if ($text_replace != '') { $text = $text_replace; }
		
		if ($force_text != '') { $text = $force_text; }
		
		if ($user_skin != '') { $user_skin = ' '.$user_skin; }
		
		$output = "";
		
		$css_width = "";
		if ($width != '') { $css_width = ' style="width:'.$width.'px!important;"'; }
		
		$output = '<div class="essb-native-skinned-button'.$user_skin.'">';
		$output .= '<div class="essb-native-outsite'.$user_skin.' essb-native-' . $type . '"'.$css_width.'>';
		
		$output_text = "";
		
		if ($text != '') {
			$output_text = '<span class="essb-native-text-inner">' . $text . '</span>';
		}
		
		$output .= '<div class="essb-native-text'.$user_skin.'"><span class="fa ' . self::getIcon ( $type ) . '"></span>' . $output_text . '</div>';
		$output .= '<div class="essb-native-click">' . $code . '</div>';
		
		$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
	
	public static function generateCircleButton($type, $code) {
		$output = "";
		
		$output .= '<div class="essb-native-circle-outsite essb-native-circle-' . $type . '">';
		
		$output .= '<div class="essb-native-circle-text"><span class="fa ' . self::getIcon ( $type ) . '"></span></div>';
		$output .= '<div class="essb-native-circle-click">' . $code . '</div>';
		
		$output .= '</div>';
		
		return $output;
	}
	
	public static function getIcon($type) {
		$icon = "";
		
		switch ($type) {
			case "google" :
				$icon = "fa-google-plus";
				break;
			
			case "facebook" :
				$icon = "fa-facebook";
				break;
			
			case "twitter" :
				$icon = "fa-twitter";
				break;
			
			case "pinterest" :
				$icon = "fa-pinterest";
				break;
			
			case "youtube" :
				$icon = "fa-youtube-play";
				break;
			case "vk" :
				$icon = "fa-vk";
				break;		
			case "linkedin" :
				$icon = "fa-linkedin";
				break;		
		}
		
		return $icon;
	}
	
	public static function registerCSS() {
		wp_enqueue_style ( 'essb-native-skinned', ESSB_PLUGIN_URL . '/assets/css/essb-native-skinned.min.css', array (), self::extension_version );
		wp_register_style ( 'essb-fontawsome', ESSB_PLUGIN_URL . '/assets/css/font-awesome.min.css', array (), self::extension_version );
		wp_enqueue_style ( 'essb-fontawsome' );
	}

}

?>