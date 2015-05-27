<?php

class EasySocialShareButtons_Options {
	public static $plugin_settings_name = "easy-social-share-buttons";
	
	private static $instance = null;
	
	public $options = array();
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	function __construct() {
		$this->get_options();
	}
	
	function get_options() {
		$this->options = get_option ( self::$plugin_settings_name );
	}
}

?>