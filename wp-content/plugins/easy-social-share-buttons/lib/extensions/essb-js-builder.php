<?php

class ESSB_JS_Buider {
	private static $instance = null;
	
	private $js_builder;
	private $js_lazyload;
	private $js_socialscripts;	
	
	private $included_window_script = false;
	private $included_ga_script = false;
	private $included_mail_script = false;
	
	private $options;
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	function __construct() {
		$this->js_builder = array();
		$this->js_lazyload = array();
		$this->js_socialscripts = array();
		
		$essb_options = EasySocialShareButtons_Options::get_instance();
		$this->options = $essb_options->options;
		
		
		add_action('wp_footer', array($this, 'generate_custom_js'));
	}
	
	public function remove_hook() {
		remove_action('wp_footer', array($this, 'generate_custom_js'));
	}
	
	/**
	 * Custom Javascript injection function into page footer
	 * @since 1.3.9.8
	 */
	public function generate_custom_js() {
		if (count($this->js_lazyload) > 0) {
		
			echo '<!-- easy-async-scripts-ver-'.ESSB_VERSION. '-->';
			echo '<script type="text/javascript">';
		
			$list = array_unique($this->js_lazyload);
		
			foreach ($list as $script) {
				echo '
				(function() {
				var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
				po.src = \''.$script.'\';
				var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
			})();';
			}
		
			echo '</script>';
		}
		
		if (count($this->js_builder) > 0) {
			echo '<!-- easy-inline-scripts-ver-'.ESSB_VERSION. '-->';
			echo '<script type="text/javascript">';
			
			foreach ($this->js_builder as $singleCode) {
				//$singleCode = trim(preg_replace('/\s+/', ' ', $singleCode));
				echo $singleCode;
			}
			
			echo '</script>';
		}
		
		// social scripts are loaded at the end
		if (count($this->js_socialscripts) > 0) {
			echo '<!-- easy-social-scripts-ver-'.ESSB_VERSION. '-->';
			
			foreach ($this->js_socialscripts as $key => $code) {
				echo $code;
			}
		}		
	}
	
	public function add_js_lazyload ($file) {
		$this->js_lazyload[] = $file;
	}
	
	public function add_js_code($js, $clean_new_lines = false) {
		if ($clean_new_lines) {
			$js = trim(preg_replace('/\s+/', ' ', $js));
		}
		$this->js_builder[] = $js;
		
	}
	
	public function include_ga_tracking_code($ga_type) {
		
		if($this->included_ga_script) { return; }
		
		$js_code = '
			function essb_ga_tracking(oService, oPosition) {
				var essb_ga_type = "'.$ga_type.'";
				
				if ( \'ga\' in window && window.ga !== undefined && typeof window.ga === \'function\' ) {
					if (essb_ga_type == "extended") {
						ga(\'send\', \'social\', oService, \'share click on \' + oPosition);
					}
					else {
						ga(\'send\', \'social\', oService, \'share\');
					}
				}
			}
		';

		$this->add_js_code($js_code);
		$this->included_ga_script = true;
	}
	
	public function include_share_window_script() {
		
		if ($this->included_window_script) { return; }
		
		$this->add_js_code('function essb_window_stat(oUrl, oService, oCountID) { var w = 800 ; var h = 500;  if (oService == "twitter") { w = 500; h= 300; } var left = (screen.width/2)-(w/2); var top = (screen.height/2)-(h/2); if (oService == "twitter") { window.open( oUrl, "essb_share_window", "height=300,width=500,resizable=1,scrollbars=yes,top="+top+",left="+left ); }  else { window.open( oUrl, "essb_share_window", "height=500,width=800,resizable=1,scrollbars=yes,top="+top+",left="+left ); } essb_handle_stats(oService, oCountID); essb_self_postcount(oService, oCountID);  }; ');
		$this->add_js_code("function essb_pinterenst_stat(oCountID) { essb_handle_stats('pinterest', oCountID); var e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)};");
		$this->add_js_code('function essb_window(oUrl, oService, oCountID) { var w = 800 ; var h = 500;  if (oService == "twitter") { w = 500; h= 300; } var left = (screen.width/2)-(w/2); var top = (screen.height/2)-(h/2);  if (oService == "twitter") { window.open( oUrl, "essb_share_window", "height=300,width=500,resizable=1,scrollbars=yes,top="+top+",left="+left ); }  else { window.open( oUrl, "essb_share_window", "height=500,width=800,resizable=1,scrollbars=yes,top="+top+",left="+left ); } essb_self_postcount(oService, oCountID); }; ');
		$this->add_js_code("function essb_pinterenst() {var e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)};");
		$this->add_js_code("var essb_count_data = {
				'ajax_url': '" . admin_url ('admin-ajax.php') . "'
		};");
		
		$this->included_window_script = true;
	}
	
	/* social media scripts */
	
	public function include_fb_script() {
		
		$option = get_option ( EasySocialShareButtons::$plugin_settings_name );
		$lang = isset($option['native_social_language']) ? $option['native_social_language'] : "en";
		
		$fb_appid = isset($option['facebookadvancedappid']) ? $option['facebookadvancedappid'] : "";
		
		$async_load = isset($option['facebook_like_button_api_async']) ? $option['facebook_like_button_api_async'] : 'false';
		
		if ($lang == "") {
			$lang = "en";
		}
		
		$code = $lang ."_" . strtoupper($lang);
		if ($lang == "en") {
			$code = "en_US";
		}
		
		$this->js_socialscripts['fb'] = $this->generate_fb_script($code, $fb_appid, $async_load);
	}
	
	public function generate_fb_script_inline() {
		$option = get_option ( EasySocialShareButtons::$plugin_settings_name );
		$lang = isset($option['native_social_language']) ? $option['native_social_language'] : "en";
		
		$fb_appid = isset($option['facebookadvancedappid']) ? $option['facebookadvancedappid'] : "";
		$async_load = isset($option['facebook_like_button_api_async']) ? $option['facebook_like_button_api_async'] : 'false';
		
		if ($lang == "") {
			$lang = "en";
		}
		
		$code = $lang ."_" . strtoupper($lang);
		if ($lang == "en") {
			$code = "en_US";
		}
		
		return $this->generate_fb_script($lang, $fb_appid, $async_load);
	}
	
	public function generate_fb_script($lang = 'en_US', $app_id = '', $async_load = 'false') {
		if ($app_id != '') {
			$app_id = "&appId=".$app_id;
		}
		
		$js_async = "";
		if ($async_load == 'true') {
			$js_async = " js.async = true;";
		}
		
		$result = '<div id="fb-root"></div>
		<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id; '.$js_async.'
		js.src = "//connect.facebook.net/'.$lang.'/sdk.js#version=v2.0&xfbml=1'.$app_id.'"
		fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));</script>';
		
		return $result;
	}
	
	public function generate_gplus_script() {
	
		$script = '	
		<script type="text/javascript">
		(function() {
		var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
		po.src = \'https://apis.google.com/js/platform.js\';
		var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
	})();
	</script>';
	
		return $script;
	}
	
	public function include_gplus_script() {
		$this->js_socialscripts['gplus'] = $this->generate_gplus_script();	
	}
	
	public function include_vk_script () {
		$option = get_option ( EasySocialShareButtons::$plugin_settings_name );
		
		$vkapp_id = isset($option['vklikeappid']) ? $option['vklikeappid'] : '';
		$this->js_socialscripts['vk'] = $this->generate_vk_script($vkapp_id);
	}
	
	public function generate_vk_script($appid = '') {
		$script = '<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
<script type="text/javascript">
window.onload = function () {
  VK.init({apiId: '.$appid.', onlyWidgets: true});
  VK.Widgets.Like("vk_like", {type: "button", height: 20});
}
</script>';

		return $script;
	
	}
	
	public function generate_popup_mailform() {
	
		if ($this->included_mail_script) { return; }
		
		$salt = mt_rand ();
		$mailform_id = 'essb_mail_from_'.$salt;
	
		$options = $this->options;
		$translate_mail_title = isset($options['translate_mail_title']) ? $options['translate_mail_title'] : '';
		$translate_mail_email = isset($options['translate_mail_email']) ? $options['translate_mail_email'] : '';
		$translate_mail_recipient = isset($options['translate_mail_recipient']) ? $options['translate_mail_recipient'] : '';
		$translate_mail_subject = isset($options['translate_mail_subject']) ? $options['translate_mail_subject'] : '';
		$translate_mail_message = isset($options['translate_mail_message']) ? $options['translate_mail_message'] : '';
		$translate_mail_cancel = isset($options['translate_mail_cancel']) ? $options['translate_mail_cancel'] : '';
		$translate_mail_send = isset($options['translate_mail_send']) ? $options['translate_mail_send'] : '';
	
		$mail_captcha = isset($options['mail_captcha']) ? $options['mail_captcha'] : '';
		$mail_captcha_answer = isset($options['mail_captcha_answer']) ? $options['mail_captcha_answer'] : '';
	
		$captcha_html = '';
		if ($mail_captcha != '' && $mail_captcha_answer != '') {
			$captcha_html = '\'<div class="vex-custom-field-wrapper"><strong>'.$mail_captcha.'</strong></div><input name="captchacode" type="text" placeholder="Captcha Code" />\'+';
		}
	
	
		$siteurl = ESSB_PLUGIN_URL. '/';
		//$open = 'javascript:PopupContact_OpenForm("PopupContact_BoxContainer","PopupContact_BoxContainerBody","PopupContact_BoxContainerFooter");';
	
		$html = 'function essb_mailer(oTitle, oMessage, oSiteTitle, oUrl, oImage, oPermalink) {
		vex.defaultOptions.className = \'vex-theme-os\';
		vex.dialog.open({
		message: \''.($translate_mail_title != '' ? $translate_mail_title : 'Share this with a friend').'\',
		input: \'\' +
		\'<div class="vex-custom-field-wrapper"><strong>'. ($translate_mail_email != '' ? $translate_mail_email : 'Your Email').'</strong></div>\'+
		\'<input name="emailfrom" type="text" placeholder="'. ($translate_mail_email != '' ? $translate_mail_email : 'Your Email').'" required />\' +
		\'<div class="vex-custom-field-wrapper"><strong>'.($translate_mail_recipient != '' ? $translate_mail_recipient : 'Recipient Email'). '</strong></div>\'+
		\'<input name="emailto" type="text" placeholder="'.($translate_mail_recipient != '' ? $translate_mail_recipient : 'Recipient Email'). '" required />\' +
		\'<div class="vex-custom-field-wrapper" style="border-bottom: 1px solid #aaa !important; margin-top: 10px;"><h3></h3></div>\'+
		\'<div class="vex-custom-field-wrapper" style="margin-top: 10px;"><strong>'.($translate_mail_subject != '' ? $translate_mail_subject : 'Subject').'</strong></div>\'+
		\'<input name="emailsubject" type="text" placeholder="Subject" required value="\'+oTitle+\'" />\' +
		\'<div class="vex-custom-field-wrapper" style="margin-top: 10px;"><strong>'.($translate_mail_message != '' ? $translate_mail_message : 'Message').'</strong></div>\'+
		\'<textarea name="emailmessage" placeholder="Message" required" rows="6">\'+oMessage+\'</textarea>\' +
		'.$captcha_html. '
		\'\',
		buttons: [
		jQuery.extend({}, vex.dialog.buttons.YES, { text: \''.($translate_mail_send != '' ? $translate_mail_send : 'Send').'\' }),
		jQuery.extend({}, vex.dialog.buttons.NO, { text: \''.($translate_mail_cancel != '' ? $translate_mail_cancel : 'Cancel').'\' })
		],
		callback: function (data) {
		if (data.emailfrom && typeof(data.emailfrom) != "undefined") {
		var c = typeof(data.captchacode) != "undefined" ? data.captchacode : "";
		essb_sendmail_ajax'.$salt.'(data.emailfrom, data.emailto, data.emailsubject, data.emailmessage, c, oSiteTitle, oUrl, oImage, oPermalink);
	}
	}
	
	});
	};
	function essb_sendmail_ajax'.$salt.'(emailfrom, emailto, emailsub, emailmessage, c, oSiteTitle, oUrl, oImage, oPermalink) {
	
	var get_address = "' . ESSB_PLUGIN_URL . '/public/essb-mail.php?from="+emailfrom+"&to="+emailto+"&sub="+emailsub+"&message="+emailmessage+"&t="+oSiteTitle+"&u="+oUrl+"&img="+oImage+"&p="+oPermalink+"&c="+c;
	jQuery.getJSON(get_address)
	.done(function(data){
	alert(data.message);
	});
	};
	';
	
		$this->included_mail_script = true;
		$this->add_js_code($html, true);
	}
	
	
}

?>