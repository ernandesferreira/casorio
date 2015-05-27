<?php

class ESSB_TwitterCards {
	
	public $card_type = "";
	public $twitter_user = "";
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
	
	public function activate_twittercards_metatags() {
		add_action('wp_head', array($this, 'twitter_header'));
	}
	
	
	function twitter_header() {
		global $post;
		
		if (is_single() || is_page()) {
			
			$essb_post_twitter_desc =  get_post_meta($post->ID,'essb_post_twitter_desc',true);
			$essb_post_twitter_title =  get_post_meta($post->ID,'essb_post_twiiter_title',true);
			$essb_post_twitter_image =  get_post_meta($post->ID,'essb_post_twiiter_image',true);
			$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
			$apply_the_content = isset($options['sso_apply_the_content']) ? $options['sso_apply_the_content'] : 'false';
			
			easy_share_deactivate();
			if ($essb_post_twitter_desc == '') { 				
				//$essb_post_og_desc = strip_tags( get_the_excerpt($post->ID));
				$content_post = get_post($post->ID);
				$essb_post_twitter_desc = $content_post->post_content;
				
				if ($apply_the_content == 'true') {
					$essb_post_twitter_desc = apply_filters('the_content', $essb_post_twitter_desc);
				}
				//$essb_post_og_desc = strip_shortcodes($essb_post_og_desc);
			
				$essb_post_twitter_desc = strip_tags ( $essb_post_twitter_desc );
				$essb_post_twitter_desc = preg_replace( '/\s+/', ' ', $essb_post_twitter_desc );
				$essb_post_twitter_desc = trim ( $essb_post_twitter_desc );
				$essb_post_twitter_desc = substr ( $essb_post_twitter_desc, 0, 155 );
				
			 }
			easy_share_reactivate();
			if ($essb_post_twitter_title == '' ) { $essb_post_twitter_title = get_the_title(); }
			if ($essb_post_twitter_image == '') {
				$fb_image = wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ), 'full');
				if ($fb_image) {
					$essb_post_twitter_image =  $fb_image[0];
				}
			}
			
			if ($essb_post_twitter_image == "") {
				$essb_post_twitter_image = $this->default_image;
			}
			
			$essb_post_twitter_desc = str_replace('"', "", $essb_post_twitter_desc);
			$essb_post_twitter_desc = str_replace('&quot;', "", $essb_post_twitter_desc);
			$essb_post_twitter_desc = str_replace ( '\'', "&apos;", $essb_post_twitter_desc );
				
			$essb_twitter_card = ($this->card_type == "") ? "summary" : "summary_large_image";			
			
			?><meta property="twitter:card" content="<?php echo addslashes($essb_twitter_card); ?>"/><?php echo "\n"; ?>			
<?php
			if ($this->twitter_user != '') {
?><meta property="twitter:site" content="@<?php echo addslashes($this->twitter_user); ?>"/><?php echo "\n"; ?><?php 
			}
?><meta property="twitter:title" content="<?php echo addslashes(strip_tags($essb_post_twitter_title)); ?>"/><?php echo "\n"; ?>
<meta property="twitter:description" content="<?php echo addslashes(strip_tags($essb_post_twitter_desc)); ?>" /><?php echo "\n"; ?>
<meta property="twitter:url" content="<?php the_permalink(); ?>"/><?php echo "\n"; ?>
<?php  ?>
<?php if ($essb_post_twitter_image != '' && $essb_twitter_card == "summary_large_image") : ?>
<meta property="twitter:image:src" content="<?php echo $essb_post_twitter_image;?>" /><?php echo "\n"; ?>
<?php endif; ?>
<meta property="twitter:domain" content="<?php bloginfo('name'); ?>"/><?php echo "\n"; ?>	
<?php
	  }
	}
}

?>