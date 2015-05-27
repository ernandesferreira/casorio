<?php

class ESSB_Settings_Helper {
	
	public static $color_fields = array();
	
	public static function drawInputField($field, $fullwidth = false) {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "";
			
			if ($fullwidth) {
				echo '<input id="customshare_text" type="text" name="general_options['.$field.']" value="' . $value . '" class="input-element stretched" />';
			}
			else {
				echo '<input id="customshare_text" type="text" name="general_options['.$field.']" value="' . $value . '" class="input-element" />';				
			}
		}
	}

	public static function drawCustomInputField($field, $fullwidth = false, $group = 'general_options') {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "";
				
			if ($fullwidth) {
				echo '<input id="customshare_text" type="text" name="'.$group.'['.$field.']" value="' . $value . '" class="input-element stretched" />';
			}
			else {
				echo '<input id="customshare_text" type="text" name="'.$group.'['.$field.']" value="' . $value . '" class="input-element" />';
			}
		}
	}
	
	public static function drawCheckboxField($field) {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "false";
				
			
			$is_checked = ($value == 'true') ? ' checked="checked"' : '';
			echo '<p style="margin: .2em 5% .2em 0;"><input id="'.$field.'" type="checkbox" name="general_options['.$field.']" value="true" ' . $is_checked . ' /></p>';
		}
	}
	
	public static function drawExtendedCheckboxField($field, $content) {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "false";
	
				
			$is_checked = ($value == 'true') ? ' checked="checked"' : '';
			echo '<p style="margin: .2em 5% .2em 0;"><input id="'.$field.'" type="checkbox" name="general_options['.$field.']" value="true" ' . $is_checked . ' /><label for="'.$field.'">'.$content.'</label></p>';
		}
	}
	
	public static function drawCustomCheckboxField($field, $group = 'general_options') {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "false";
	
				
			$is_checked = ($value == 'true') ? ' checked="checked"' : '';
			echo '<p style="margin: .2em 5% .2em 0;"><input id="'.$field.'" type="checkbox" name="'.$group.'['.$field.']" value="true" ' . $is_checked . ' /></p>';
		}
	}
	
	public static function drawSelectField($field, $listOfValues, $simpleList = false) {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "";
				
			echo '<select name="general_options['.$field.']" class="input-element">';
			
			if ($simpleList) {
				foreach ($listOfValues as $singleValue) {
					printf('<option value="%1$s" %2$s>%1$s</option>',
							$singleValue,
							($singleValue == $value ? 'selected' : '')
					);
				}
			}
			else {
				foreach ($listOfValues as $singleValueCode => $singleValue)
				{
					printf('<option value="%s" %s>%s</option>',
							$singleValueCode,
							($singleValueCode == $value ? 'selected' : ''),
							$singleValue
					);
				}
			}
			
			echo '</select>';
		}
	}
	
	public static function drawCustomSelectField($field, $listOfValues, $simpleList = false, $group = 'general_options') {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
		if (is_array ( $options )) {
			$value = isset($options[$field]) ? $options[$field] : "";
	
			echo '<select name="'.$group.'['.$field.']" class="input-element">';
				
			if ($simpleList) {
				foreach ($listOfValues as $singleValue) {
					printf('<option value="%1$s" %2$s>%1$s</option>',
							$singleValue,
							($singleValue == $value ? 'selected' : '')
					);
				}
			}
			else {
				foreach ($listOfValues as $singleValueCode => $singleValue)
				{
					printf('<option value="%s" %s>%s</option>',
							$singleValueCode,
							($singleValueCode == $value ? 'selected' : ''),
							$singleValue
					);
				}
			}
				
			echo '</select>';
		}
	}
	
	public static function generateCustomSelectField($field, $listOfValues, $simpleList = false, $group = 'general_options', $user_value = '') {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
			$value = isset($options[$field]) ? $options[$field] : "";
			
			if ($user_value != '') {
				$value = $user_value;
			}			
			
			$output = '';
			
			$output .= '<select name="'.$group.'['.$field.']" class="input-element '.$field.'">';
	
			if ($simpleList) {
				foreach ($listOfValues as $singleValue) {
					$output.= sprintf('<option value="%1$s" %2$s>%1$s</option>',
							$singleValue,
							($singleValue == $value ? 'selected' : '')
					);
				}
			}
			else {
				foreach ($listOfValues as $singleValueCode => $singleValue)
				{
					$output .= sprintf('<option value="%s" %s>%s</option>',
							$singleValueCode,
							($singleValueCode == $value ? 'selected' : ''),
							$singleValue
					);
				}
			}
	
			$output .= '</select>';
			
			return $output;
	}
	
	public static function generateCustomInputField($field, $fullwidth = false, $group = 'general_options', $user_value = '') {
		$value = '';
		if ($user_value != '') {
			$value = $user_value;
		}
			
		$output = '';
		
			if ($fullwidth) {
				$output .= '<input id="customshare_text" type="text" name="'.$group.'['.$field.']" value="' . $value . '" class="input-element stretched" />';
			}
			else {
				$output .= '<input id="customshare_text" type="text" name="'.$group.'['.$field.']" value="' . $value . '" class="input-element" />';
			}
			
			return $output;
	}
	
	
	public static function drawColorField($field) {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		if (is_array ( $options )) {
			$exist = isset ( $options [$field] ) ? $options [$field] : '';
			$exist = stripslashes ( $exist );
		
			echo '<input id="'.$field.'" type="text" name="general_options['.$field.']" value="' . $exist . '" class="input-element stretched" data-default-color="' . $exist . '" />';
		
		}
		
		array_push(self::$color_fields, $field);
	}
	
	public static function drawCustomColorField($field, $group = 'general_options') {
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		if (is_array ( $options )) {
			$exist = isset ( $options [$field] ) ? $options [$field] : '';
			$exist = stripslashes ( $exist );
	
			echo '<input id="'.$field.'" type="text" name="'.$group.'['.$field.']" value="' . $exist . '" class="input-element stretched" data-default-color="' . $exist . '" />';
	
		}
	
		array_push(self::$color_fields, $field);
	}
	
	public static function registerColorSelector() {
		?>
		<div id="colorpicker"></div>
		
		
		<script type="text/javascript">		
		
		
		jQuery(document).ready(function($){
			<?php
		
			foreach (self::$color_fields as $single) {
				print "$('#".$single."').wpColorPicker();";
			}
		
			?>
		});
		
		</script>
		<?php 
	}
	
}

?>