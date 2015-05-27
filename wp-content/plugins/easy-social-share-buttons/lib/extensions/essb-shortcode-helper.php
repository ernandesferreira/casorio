<?php

class EasySocialShareButtons_ShortcodeHelper {
	public static function unified_true($value) {
		$out = $value;
		
		if ($value == "yes") {
			$value = "true";
		}
		if ($value == "no") {
			$value = "false";
		}
		
		return $out;
	}
	
	public static function unified_yes($value) {
		$out = $value;
		
		if ($value == "true") {
			$value = "yes";
		}
		if ($value == "false") {
			$value = "no";
		}
		
		return $out;			
	}
}

?>