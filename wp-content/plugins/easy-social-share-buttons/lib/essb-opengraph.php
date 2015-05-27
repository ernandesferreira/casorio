<?php

class ESSB_OpenGraph {
	
	public $fbadmins = "";
	public $fbpage = "";
	public $fbapp = "";
	public $default_image = "";
	
	private static $instance = null;
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	private function __construct() {
	
	}
	
	public function activate_opengraph_metatags() {
		add_filter ( 'language_attributes', array ($this, 'add_og_xml_ns' ) );
		// add_filter('language_attributes', array($this, 'add_fb_xml_ns'));
		add_action ( 'wp_head', array ($this, 'ogmeta_header' ) );
	}
	
	function add_og_xml_ns($content) {
		// return ' xmlns:og="http://ogp.me/ns#" ' . $content;
		if ($this->fbadmins != '' || $this->fbapp != '') {
			return $content . ' prefix="og: http://ogp.me/ns#  fb: http://ogp.me/ns/fb#"';
		} else {
			return $content . ' prefix="og: http://ogp.me/ns#"';
		}
	}
	
	function add_fb_xml_ns($content) {
		if ($this->fbadmins != '') {
		
		} else {
			return ' xmlns:fb="https://www.facebook.com/2008/fbml" ' . $content;
		}
	}
	
	function ogmeta_header() {
		global $post;
		
		if (is_single () || is_page ()) {
			
			easy_share_deactivate ();
			$essb_post_og_desc = get_post_meta ( $post->ID, 'essb_post_og_desc', true );
			$essb_post_og_title = get_post_meta ( $post->ID, 'essb_post_og_title', true );
			$essb_post_og_image = get_post_meta ( $post->ID, 'essb_post_og_image', true );
			$essb_post_og_video = get_post_meta ( $post->ID, 'essb_post_og_video', true );
			
			// sso_apply_the_content
			$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
			$apply_the_content = isset($options['sso_apply_the_content']) ? $options['sso_apply_the_content'] : 'false';
			
			if ($essb_post_og_desc == '') {
				// $essb_post_og_desc = strip_tags( get_the_excerpt($post->ID));
				$content_post = get_post ( $post->ID );
				$essb_post_og_desc = $content_post->post_content;
				
				if ($apply_the_content == 'true') {
					$essb_post_og_desc = apply_filters('the_content', $essb_post_og_desc);
				}
				//$essb_post_og_desc = strip_shortcodes($essb_post_og_desc);
				$post_shortdesc = $content_post->post_excerpt;
				if ($post_shortdesc != '') {
					$essb_post_og_desc = $post_shortdesc;
				}
				
				$essb_post_og_desc = strip_tags ( $essb_post_og_desc );
				$essb_post_og_desc = preg_replace( '/\s+/', ' ', $essb_post_og_desc );
				$essb_post_og_desc = trim ( $essb_post_og_desc );				
				$essb_post_og_desc = substr ( $essb_post_og_desc, 0, 155 );

			}
			if ($essb_post_og_title == '') {
				$essb_post_og_title = get_the_title ();
			}
			if ($essb_post_og_image == '') {
				$fb_image = wp_get_attachment_image_src ( get_post_thumbnail_id ( get_the_ID () ), 'full' );
				if ($fb_image) {
					$essb_post_og_image = $fb_image [0];
				}
			}
			
			if ($essb_post_og_image == "") { $essb_post_og_image = $this->default_image;}
			
			$essb_post_og_desc = str_replace ( '"', "", $essb_post_og_desc );
			$essb_post_og_desc = str_replace ( '&quot;', "", $essb_post_og_desc );
			$essb_post_og_desc = str_replace ( '\'', "&apos;", $essb_post_og_desc );
				
			$essb_og_locate = $this->get_og_locate ();
			if ($this->fbapp != '') {
				print '<meta property="fb:app_id" content="' . $this->fbapp . '"/>';
			}
			if ($this->fbadmins != '') {
				print '<meta property="fb:admins" content="' . $this->fbadmins . '"/>';
			}
			
			?>
<meta property="og:title" content="<?php echo addslashes(strip_tags($essb_post_og_title)); ?>" />
<meta property="og:description" content="<?php echo addslashes(strip_tags($essb_post_og_desc)); ?>" />
<meta property="og:url" content="<?php the_permalink(); ?>" />
<?php if ($essb_post_og_image != '') : ?>
<meta property="og:image" content="<?php echo $essb_post_og_image;?>" />
<?php endif; ?>
<?php if ($essb_post_og_video != '') : ?>
<meta property="og:video" content="<?php echo $essb_post_og_video;?>" />
<?php endif; ?>
<meta property="og:type" content="<?php if (is_single () || is_page ()) {
				echo "article";
			} else {
				echo "website";
			}
			?>" />
<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
<?php

			if (is_singular ()) {
				$this->og_tags();
				$this->og_category();
				$this->og_publish_date();
				if ($this->fbpage != '') {
					print '<meta property="article:publisher" content="' . $this->fbpage . '"/>';
				}
				
			}
			easy_share_reactivate ();
		}
	}
	
	function og_tags() {
		if (! is_singular ()) {
			return;
		}
		
		$tags = get_the_tags ();
		if (! is_wp_error ( $tags ) && (is_array ( $tags ) && $tags !== array ())) {
			foreach ( $tags as $tag ) {
				print '<meta property="article:tag" content="' . $tag->name . '"/>';
			}
		}
	}
	
	public function og_category() {
		if ( ! is_singular() ) {
			return;
		}
	
		$terms = get_the_category();
		if ( ! is_wp_error( $terms ) && ( is_array( $terms ) && $terms !== array() ) ) {
			foreach ( $terms as $term ) {
				print '<meta property="article:section" content="' . $term->name . '"/>';
			}
		}
	}
	
	public function og_publish_date() {
		if ( ! is_singular() ) {
			return;
		}
	
		$pub = get_the_date( 'c' );
		print '<meta property="article:published_time" content="' .$pub . '"/>';
	
		$mod = get_the_modified_date( 'c' );
		if ( $mod != $pub ) {
			print '<meta property="article:modified_time" content="' . $mod . '"/>';
			print '<meta property="og:updated_time" content="' .$mod . '"/>';
		}
	}
	
	function get_og_locate() {
		$locale = get_locale ();
		
		// catch some weird locales served out by WP that are not easily doubled
		// up.
		$fix_locales = array ('ca' => 'ca_ES', 'en' => 'en_US', 'el' => 'el_GR', 'et' => 'et_EE', 'ja' => 'ja_JP', 'sq' => 'sq_AL', 'uk' => 'uk_UA', 'vi' => 'vi_VN', 'zh' => 'zh_CN' );
		
		if (isset ( $fix_locales [$locale] )) {
			$locale = $fix_locales [$locale];
		}
		
		// convert locales like "es" to "es_ES", in case that works for the
		// given locale (sometimes it does)
		if (strlen ( $locale ) == 2) {
			$locale = strtolower ( $locale ) . '_' . strtoupper ( $locale );
		}
		
		// These are the locales FB supports
		$fb_valid_fb_locales = array ('ca_ES', 'cs_CZ', 'cy_GB', 'da_DK', 'de_DE', 'eu_ES', 'en_PI', 'en_UD', 'ck_US', 'en_US', 'es_LA', 'es_CL', 'es_CO', 'es_ES', 'es_MX', 'es_VE', 'fb_FI', 'fi_FI', 'fr_FR', 'gl_ES', 'hu_HU', 'it_IT', 'ja_JP', 'ko_KR', 'nb_NO', 'nn_NO', 'nl_NL', 'pl_PL', 'pt_BR', 'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sv_SE', 'th_TH', 'tr_TR', 'ku_TR', 'zh_CN', 'zh_HK', 'zh_TW', 'fb_LT', 'af_ZA', 'sq_AL', 'hy_AM', 'az_AZ', 'be_BY', 'bn_IN', 'bs_BA', 'bg_BG', 'hr_HR', 'nl_BE', 'en_GB', 'eo_EO', 'et_EE', 'fo_FO', 'fr_CA', 'ka_GE', 'el_GR', 'gu_IN', 'hi_IN', 'is_IS', 'id_ID', 'ga_IE', 'jv_ID', 'kn_IN', 'kk_KZ', 'la_VA', 'lv_LV', 'li_NL', 'lt_LT', 'mk_MK', 'mg_MG', 'ms_MY', 'mt_MT', 'mr_IN', 'mn_MN', 'ne_NP', 'pa_IN', 'rm_CH', 'sa_IN', 'sr_RS', 'so_SO', 'sw_KE', 'tl_PH', 'ta_IN', 'tt_RU', 'te_IN', 'ml_IN', 'uk_UA', 'uz_UZ', 'vi_VN', 'xh_ZA', 'zu_ZA', 'km_KH', 'tg_TJ', 'ar_AR', 'he_IL', 'ur_PK', 'fa_IR', 'sy_SY', 'yi_DE', 'gn_PY', 'qu_PE', 'ay_BO', 'se_NO', 'ps_AF', 'tl_ST', 'fy_NL' );
		
		// check to see if the locale is a valid FB one, if not, use en_US as a
		// fallback
		if (! in_array ( $locale, $fb_valid_fb_locales )) {
			$locale = 'en_US';
		}
		
		return $locale;
	}
}

?>