<?php

class EasySocialShareButtons_SocialShareOptimization {
	private static $instance = null;
	
	public $default_image = "";
	public $apply_the_content = false;
	
	// Facebook additional
	public $ogtags_active = false;
	public $fbadmins = "";
	public $fbpage = "";
	public $fbapp = "";
	
	// Twitter Cards
	public $twitter_cards_active = false;
	public $card_type = "";
	public $twitter_user = "";
		
	// Google Schema.org
	public $google_authorship = false;
	public $google_markup = false;
	public $google_publisher = "";
	public $google_author = "";
	
	// Post defaults
	private $post_title = "";
	private $post_description = "";
	private $post_image = "";
	private $post_url = "";
	private $site_name = "";
	
	private $fb_title = "";
	private $fb_description = "";
	private $fb_image = "";
	
	private $twitter_title = "";
	private $twitter_description = "";
	private $twitter_image = "";
	
	private $google_title = "";
	private $google_description = "";
	private $google_image = "";
	
	private $sso_active = false;
	
	private $options;
	
	private $meta = array();
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	function __construct() {
		$essb_options = EasySocialShareButtons_Options::get_instance();
		$this->options = $essb_options->options;
		
		$this->loadDefaultFromOptions();
		
		if ($this->sso_active) {
			add_filter ( 'language_attributes', array ($this, 'insertLanguageAttributes' ) );
			add_action ( 'wp_head', array ($this, 'outputMeta' ) );
		}
	}
	
	private function loadDefaultFromOptions() {
		$opengraph_tags = isset ( $this->options ['opengraph_tags'] ) ? $this->options ['opengraph_tags'] : 'false';
		$this->default_image = isset ( $this->options ['sso_default_image'] ) ? $this->options ['sso_default_image'] : '';
		
		$apply_the_content = isset($this->options['sso_apply_the_content']) ? $this->options['sso_apply_the_content'] : 'false';
		
		if ($apply_the_content == 'true') {
			$this->apply_the_content = true;
		}
		
		if ($opengraph_tags == 'true') {
			$this->ogtags_active = true;
			$this->fbadmins = isset ( $this->options ['opengraph_tags_fbadmins'] ) ? $this->options ['opengraph_tags_fbadmins'] : '';
			$this->fbpage = isset ( $this->options ['opengraph_tags_fbpage'] ) ? $this->options ['opengraph_tags_fbpage'] : '';
			$this->fbapp = isset ( $this->options ['opengraph_tags_fbapp'] ) ? $this->options ['opengraph_tags_fbapp'] : '';
		}
		
		$twitter_card_active = isset ( $this->options ['twitter_card'] ) ? $this->options ['twitter_card'] : 'false';
		
		if ($twitter_card_active == "true") {
			$this->twitter_cards_active = true;
			$this->card_type = isset ( $this->options ['twitter_card_type'] ) ? $this->options ['twitter_card_type'] : '';
			$this->twitter_user = isset ( $this->options ['twitter_card_user'] ) ? $this->options ['twitter_card_user'] : '';
		}
		
		$google_authorship = isset ( $this->options ['sso_google_author'] ) ? $this->options ['sso_google_author'] : 'false';
		if ($google_authorship == 'true') {
			$this->google_authorship = true;
			$this->google_author = isset ( $this->options ['ss_google_author_profile'] ) ? $this->options ['ss_google_author_profile'] : '';
			$this->google_publisher = isset ( $this->options ['ss_google_author_publisher'] ) ? $this->options ['ss_google_author_publisher'] : '';
		}
		
		$google_markup = isset ( $this->options ['sso_google_markup'] ) ? $this->options ['sso_google_markup'] : 'false'; 
		if ($google_markup == "true") {
			$this->google_markup = true;
		}
		
		if ($this->ogtags_active || $this->twitter_cards_active || $this->google_authorship || $this->google_markup) {
			$this->sso_active = true;
		}
	}
	
	private function loadPostSettings() {
		global $post;
		
		if (is_single () || is_page ()) {
			easy_share_deactivate();
			
			// loading post default data
			$this->post_title = get_the_title ();
			$this->site_name = get_bloginfo('name');
			$this->post_url = get_permalink($post->ID);
			
			$content_post = get_post ( $post->ID );
			$working_post_content = $content_post->post_content;
			
			if ($this->apply_the_content) {
				$working_post_content = apply_filters('the_content', $working_post_content);
			}
			//$essb_post_og_desc = strip_shortcodes($essb_post_og_desc);
			$post_shortdesc = $content_post->post_excerpt;
			if ($post_shortdesc != '') {
				$working_post_content = $post_shortdesc;
			}
			
			$working_post_content = strip_tags ( $working_post_content );
			$working_post_content = preg_replace( '/\s+/', ' ', $working_post_content );
			$working_post_content = trim ( $working_post_content );
			$working_post_content = substr ( $working_post_content, 0, 155 );
			
			$this->post_description = $working_post_content;
			
			$fb_image = wp_get_attachment_image_src ( get_post_thumbnail_id ( get_the_ID () ), 'full' );
			if ($fb_image) {
				$this->post_image = $fb_image [0];
			}
				
			if ($this->post_image == "") {
				$this->post_image = $this->default_image;
			}
			
			// end loading post defaults
			
			if ($this->ogtags_active) {
				$this->fb_description = get_post_meta ( $post->ID, 'essb_post_og_desc', true );
				$this->fb_title = get_post_meta ( $post->ID, 'essb_post_og_title', true );
				$this->fb_image = get_post_meta ( $post->ID, 'essb_post_og_image', true );
			
				if ($this->fb_description == "") {
					$this->fb_description = $this->post_description;
				}				
				if ($this->fb_title == "") {
					$this->fb_title = $this->post_title;
				}
				if ($this->fb_image == "") {
					$this->fb_image = $this->post_image;
				}
			}
			
			if ($this->twitter_cards_active) {
				$this->twitter_description =  get_post_meta($post->ID,'essb_post_twitter_desc',true);
				$this->twitter_title =  get_post_meta($post->ID,'essb_post_twitter_title',true);
				$this->twitter_image =  get_post_meta($post->ID,'essb_post_twitter_image',true);
				
				if ($this->twitter_description == "") {
					$this->twitter_description = $this->post_description;
				}
				if ($this->twitter_title == "") {
					$this->twitter_title = $this->post_title;
				}
				if ($this->twitter_image == "") {
					$this->twitter_image = $this->post_image;
				}
			}
			
			if ($this->google_markup) {
				$this->google_description =  get_post_meta($post->ID,'essb_post_google_desc',true);
				$this->google_title =  get_post_meta($post->ID,'essb_post_google_title',true);
				$this->google_image =  get_post_meta($post->ID,'essb_post_google_image',true);
			
				if ($this->google_description == "") {
					$this->google_description = $this->post_description;
				}
				if ($this->google_title == "") {
					$this->google_title = $this->post_title;
				}
				if ($this->google_image == "") {
					$this->google_image = $this->post_image;
				}
			}
			
			easy_share_reactivate();
		
		}
		
	}
	
	public function insertLanguageAttributes($content) {
		if ($this->ogtags_active) {
			if ($this->fbadmins != '' || $this->fbapp != '') {
				$content .= ' prefix="og: http://ogp.me/ns#  fb: http://ogp.me/ns/fb#"';
			} else {
				$content .= ' prefix="og: http://ogp.me/ns#"';
			}
		}
		
		if ($this->google_markup && is_singular ()) {
			$content .= ' itemscope itemtype="http://schema.org/Article"';
		}

		return $content;
	}
	
	public function outputMeta() {
		$this->loadPostSettings();
		
		if ($this->ogtags_active) {
			$this->buildFacebookMeta();
		}
		if ($this->twitter_cards_active) {
			$this->buildTwitterMeta();
		}
		
		if ($this->google_authorship || $this->google_markup) {
			$this->buildGoogleMeta();
		}
		
		echo "\r\n";
		foreach ($this->meta as $single) {
			echo $single."\r\n";
		}
		echo "\r\n";
	}
	
	// Google
	
	private function buildGoogleMeta() {
		if ($this->google_authorship) {
			if (is_singular () && !is_front_page()) {
				$this->googleAuthorshipBuilder('author', $this->google_author);
			}
			$this->googleAuthorshipBuilder('publisher', $this->google_publisher);
		}
		
		if ($this->google_markup) {
			$this->googleMetaBuilder('name', $this->google_title, true);
			$this->googleMetaBuilder('description', $this->google_description, true);
			$this->googleMetaBuilder('image', $this->google_image);
		}
	}
	
	private function googleMetaBuilder($property = '', $value = '', $apply_filters = false) {
		if ($apply_filters) {
			$value = addslashes(strip_tags($value));
		}
		
		if ($property != '' && $value != '') {
			$this->meta[] = '<meta itemprop="'.$property.'" content="'.$value.'" />';
		}
	}
	
	private function googleAuthorshipBuilder($property = '', $value = '') {
		if ($property != '' && $value != '') {
			$this->meta[] = '<link rel="'.$property.'" href="'.$value.'"/>';
		}
	}
	
	// Twitter
	
	private function buildTwitterMeta() {
		if ($this->card_type == "") {
			$this->card_type = "summary";
		}
		
		$this->twitterMetaTagBuilder('card', $this->card_type);
		if ($this->twitter_user != '') { 
			$this->twitterMetaTagBuilder('site', '@'.$this->twitter_user);
		}
		$this->twitterMetaTagBuilder('title', $this->twitter_title, true);
		$this->twitterMetaTagBuilder('description', $this->twitter_description);
		$this->twitterMetaTagBuilder('site', $this->card_type);
		$this->twitterMetaTagBuilder('url', $this->post_url);
		
		if ($this->card_type == "summary_large_image" && $this->twitter_image != '') {
			$this->twitterMetaTagBuilder('image:src', $this->twitter_image);
		}
		$this->twitterMetaTagBuilder('domain', $this->site_name, true);
	}
	
	private function twitterMetaTagBuilder($property = '', $value = '', $apply_filters = false, $prefix = 'twitter') {
		if ($apply_filters) {
			$value = addslashes(strip_tags($value));
		}
		
		if ($property != '' && $value != '') {
			$this->meta[] = '<meta property="'.$prefix.':'.$property.'" content="'.$value.'" />';
		}
	}
	
	// Facebook
	
	private function buildFacebookMeta() {
		$this->openGraphMetaTagBuilder('app_id', $this->fbapp, false, 'fb');
		$this->openGraphMetaTagBuilder('admins', $this->fbadmins, false, 'fb');
		
		$this->openGraphMetaTagBuilder('title', $this->fb_title, true);
		$this->openGraphMetaTagBuilder('description', $this->fb_description, true);
		$this->openGraphMetaTagBuilder('url', $this->post_url);
		$this->openGraphMetaTagBuilder('image', $this->fb_image);
		
		$content_type = (is_single () || is_page ()) ? "article" : "website";
		$this->openGraphMetaTagBuilder('type', $content_type);
		$this->openGraphMetaTagBuilder('site_name', $this->site_name, true);
		
		// only for posts
		if (is_singular () && !is_front_page() ) {
			$this->og_tags();
			$this->og_category();
			$this->og_publish_date();
			if ($this->fbpage != '') {
				$this->openGraphMetaTagBuilder('publisher', $this->fbpage, false, 'article');
			}		
		}
	}
	
	private function openGraphMetaTagBuilder($property = '', $value = '', $apply_filters = false, $prefix = 'og') {
		if ($apply_filters) {
			$value = addslashes(strip_tags($value));
		}
		
		if ($property != '' && $value != '') {
			$this->meta[] = '<meta property="'.$prefix.':'.$property.'" content="'.$value.'" />';
		}
		
	}
	
	function og_tags() {
		if (! is_singular ()) {
			return;
		}
	
		$tags = get_the_tags ();
		if (! is_wp_error ( $tags ) && (is_array ( $tags ) && $tags !== array ())) {
			foreach ( $tags as $tag ) {
				$this->openGraphMetaTagBuilder('tag', $tag->name, false, 'article');
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
				$this->openGraphMetaTagBuilder('section', $term->name, false, 'article');
			}
		}
	}
	
	public function og_publish_date() {
		if ( ! is_singular() ) {
			return;
		}
	
		$pub = get_the_date( 'c' );
		$this->openGraphMetaTagBuilder('published_time', $pub, false, 'article');
	
		$mod = get_the_modified_date( 'c' );
		if ( $mod != $pub ) {
			$this->openGraphMetaTagBuilder('modified_time', $mod, false, 'article');
			$this->openGraphMetaTagBuilder('updated_time', $mod);
		}
	}
}

?>