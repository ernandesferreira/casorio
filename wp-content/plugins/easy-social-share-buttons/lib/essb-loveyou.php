<?php

class ESSB_LoveYou {
	public $plugin_settings_name = "easy-social-share-buttons";
	public $code_is_added = false;
	// $option = get_option ( self::$plugin_settings_name );
	
	private static $instance = null;
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	public function __construct() {
		add_action ( 'wp_ajax_nopriv_essb_love_action', array ($this, 'log_love_click' ) );
		add_action ( 'wp_ajax_essb_love_action', array ($this, 'log_love_click' ) );
	
		add_action ( 'wp_ajax_nopriv_essb_get_love_action', array ($this, 'get_love_click' ) );
		add_action ( 'wp_ajax_essb_get_love_action', array ($this, 'get_love_click' ) );
	}
	
	public function generate_loveyou_js_code_jsbuilder() {
		global $post;
		
		if ($this->code_is_added) {
			return "";
		}
		
		// localization of messages;
		$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		$message_loved = isset($current_options['translate_love_loved']) ? $current_options['translate_love_loved'] : 'Your already love this today.';
		$message_thanks = isset($current_options['translate_love_thanks'])? $current_options['translate_love_thanks'] : 'Thank you for loving this.';
		
		if ($message_loved == "") {
			$message_loved = "You already love this today.";
		}
		if ($message_thanks == "") {
			$message_thanks = "Thank you for loving this.";
		}
		
		$this->code_is_added = true;
		$result = "
		var essb_love_data = {
		'ajax_url': '" . admin_url ( 'admin-ajax.php' ) . "'
		};
		jQuery(document).bind('essb_love_action', function (e, service, post_id) {
		
		jQuery.post(essb_love_data.ajax_url, {
		'action': 'essb_love_action',
		'post_id': post_id,
		'service': service,
		'nonce': '" . wp_create_nonce ( "ajax-nonce" ) . "'
		}, function (data) {
		if (data && data.error) {
		alert(data.error);
		}
		},
		'json'
		);
		});
		var essb_love_you_clicked = false;
		var essb_love_you_message_thanks = '".$message_thanks."';
		var essb_love_you_message_loved = '".$message_loved."';
		function essb_handle_loveyou(service, post_id, sender) {
		if (service == '1') { alert(essb_love_you_message_loved); return; }
		if (essb_love_you_clicked) { alert(essb_love_you_message_loved); return; }
		 
		jQuery(document).trigger('essb_love_action',[service, post_id]);
		essb_love_you_clicked = true;
		if (typeof(sender) != 'undefined') {
		var parent = jQuery(sender).parent();
			
		if (parent.length) {
		var counter_r = jQuery(parent).find('.essb_counter_right');
		var counter_l = jQuery(parent).find('.essb_counter');
		var counter_i = jQuery(parent).find('.essb_counter_inside');
		if (counter_r.length) {
		var current = parseInt(counter_r.attr('cnt'));
		current++;
			
		counter_r.attr('cnt', current);
		counter_r.text(current);
		}
		if (counter_l.length) {
		var current = parseInt(counter_l.attr('cnt'));
		current++;
			
		counter_l.attr('cnt', current);
		counter_l.text(current);
		}
		if (counter_i.length) {
		var current = parseInt(counter_i.attr('cnt'));
		current++;
			
		counter_i.attr('cnt', current);
		counter_i.text(current);
		}
		}
		}
		
		alert(essb_love_you_message_thanks);
		}
		
		var essb_loveyou_post_id = ".(isset($post) ? $post->ID : 0). ";		
		";
		
		return $result;
	}
	
	public function generate_loveyou_js_code() {
		
		global $post;
		
		if ($this->code_is_added) {
			return "";
		}
		
		// localization of messages;
		$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		$message_loved = isset($current_options['translate_love_loved']) ? $current_options['translate_love_loved'] : 'Your already love this today.';
		$message_thanks = isset($current_options['translate_love_thanks'])? $current_options['translate_love_thanks'] : 'Thank you for loving this.';
		
		if ($message_loved == "") { $message_loved = "You already love this today."; }
		if ($message_thanks == "") { $message_thanks = "Thank you for loving this."; } 
		
		$this->code_is_added = true;
		$result = "
		<script type=\"text/javascript\" language=\"javascript\">
		var essb_love_data = {
		'ajax_url': '" . admin_url ( 'admin-ajax.php' ) . "'
	};
	jQuery(document).bind('essb_love_action', function (e, service, post_id) {
	
	jQuery.post(essb_love_data.ajax_url, {
	'action': 'essb_love_action',
	'post_id': post_id,
	'service': service,
	'nonce': '" . wp_create_nonce ( "ajax-nonce" ) . "'
	}, function (data) {
	if (data && data.error) {
	alert(data.error);
	}
	},
	'json'
	);
	});
	var essb_love_you_clicked = false;
	var essb_love_you_message_thanks = '".$message_thanks."';
	var essb_love_you_message_loved = '".$message_loved."';	
	function essb_handle_loveyou(service, post_id, sender) {
		if (service == '1') { alert(essb_love_you_message_loved); return; }
	    if (essb_love_you_clicked) { alert(essb_love_you_message_loved); return; }
	    
		jQuery(document).trigger('essb_love_action',[service, post_id]);
		essb_love_you_clicked = true;
		if (typeof(sender) != 'undefined') {
			var parent = jQuery(sender).parent();
			
			if (parent.length) {
				var counter_r = jQuery(parent).find('.essb_counter_right');
				var counter_l = jQuery(parent).find('.essb_counter');
				var counter_i = jQuery(parent).find('.essb_counter_inside');
				if (counter_r.length) {
					var current = parseInt(counter_r.attr('cnt'));
					current++;
					
					counter_r.attr('cnt', current);
					counter_r.text(current);
				}
				if (counter_l.length) {
					var current = parseInt(counter_l.attr('cnt'));
					current++;
					
					counter_l.attr('cnt', current);
					counter_l.text(current);
				}	
				if (counter_i.length) {
					var current = parseInt(counter_i.attr('cnt'));
					current++;
					
					counter_i.attr('cnt', current);
					counter_i.text(current);
				}	
			}
		}
		
		alert(essb_love_you_message_thanks);
	}

	var essb_loveyou_post_id = ".(isset($post) ? $post->ID : 0). ";
	
	</script>
	";
		
		print $result;
	}
	
	public function log_love_click() {
		global $wpdb, $blog_id;
		
		$post_id = isset ( $_POST ["post_id"] ) ? $_POST ["post_id"] : '';
		$service_id = isset ( $_POST ["service"] ) ? $_POST ["service"] : '';		
		
		$love_count = get_post_meta($post_id, '_essb_love', true);
		if( isset($_COOKIE['essb_love_'. $post_id]) ) die( $love_count);
		if (!isset($love_count)) { $love_count = 0;}
		
		$love_count++;
		update_post_meta($post_id, '_essb_love', $love_count);
		setcookie('essb_love_'. $post_id, $post_id . " - ". $love_count, time()*20, '/');
		
		die ( json_encode ( array ("success" => 'Log handled' ) ) );
	}

}

?>