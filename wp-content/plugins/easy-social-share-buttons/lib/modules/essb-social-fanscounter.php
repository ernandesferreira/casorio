<?php

class EasySocialFansCounter {
	
	public $version = "";
	public $module_version = "1.1.0";
	
	public $transient_text = "essb-fans-counter";
	public $options_text = "essb-fans-options";
	
	public $essb_supported_items = array ('facebook', 'twitter', 'google', 'youtube', 'vimeo', 'dribbble', 'github', 'envato', 'soundcloud', 'behance', 'delicious', 'instagram', 'pinterest', 'love', 'vk', 'rss', 'posts', 'comments', 'users', 'mailchimp', 'linkedin', 'tumblr' );
	
	public $options;
	public $transient;
	public $data;
	public $updating_data;
	
	public $expired = false;
	
	private static $instance = null;
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	
	function __construct() {
		add_shortcode ( 'essb-fans', array ($this, 'handle_essb_fancounts_shortcode' ) );
		// initialize settings if not exist
		$this->initialize_default_data ();
		
		$this->load ();
	
	}
	
	function register_social_network() {
	
	}
	
	function load() {
		$this->transient = get_transient ( $this->transient_text );
		$this->options = get_option ( $this->options_text );
		if (empty ( $this->options )) {
			$this->options = array ();
		}
		if (empty ( $this->transient ) || (false === $this->transient)) {
			
			$this->transient = array ();
			//$this->options ['data'] = array();
			$this->expired = true;
		}
		
		$this->data = array();
		$this->updating_data = array();
	}
	
	function initialize_default_data() {
		if (! get_option ( $this->options_text )) {
			
			$default_data = array ('social' => array ('facebook' => array ('id' => '', 'text' => __ ( 'Fans', ESSB_TEXT_DOMAIN ) ), 'twitter' => array ('id' => '', 'text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'google' => array ('id' => '', 'text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'youtube' => array ('id' => '', 'text' => __ ( 'Subscribers', ESSB_TEXT_DOMAIN ), 'type' => 'User' ), 'vimeo' => array ('text' => __ ( 'Subscribers', ESSB_TEXT_DOMAIN ) ), 'dribbble' => array ('id' => '', 'text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'envato' => array ('id' => '', 'text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ), 'site' => 'themeforest' ), 'github' => array ('text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'soundcloud' => array ('text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'behance' => array ('text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'instagram' => array ('text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'delicious' => array ('text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'pinterest' => array ('text' => __ ( 'Followers', ESSB_TEXT_DOMAIN ) ), 'love' => array ('text' => __ ( 'Loves', ESSB_TEXT_DOMAIN ) ), 'vk' => array ('text' => __ ( 'Members', ESSB_TEXT_DOMAIN ) ), 'rss' => array ('text' => __ ( 'Subscribers', ESSB_TEXT_DOMAIN )), 'mailchimp' => array ('text' => __ ( 'Subscribers', ESSB_TEXT_DOMAIN )), 'users' => array ('text' => __ ( 'Users', ESSB_TEXT_DOMAIN )), 'posts' => array ('text' => __ ( 'Posts', ESSB_TEXT_DOMAIN )), 'comments' => array ('text' => __ ( 'Comments', ESSB_TEXT_DOMAIN ) ) ), 'cache' => 2 );
			
			update_option ( $this->options_text, $default_data );
		}
	}
	
	function update_all_counts() {
		//print "expired state = " .$this->expired;
		if (!$this->expired) { return; }
		
		$this->updating_data = array();
		$this->data = array();
		foreach ( $this->essb_supported_items as $network ) {
			switch ($network) {
				case "facebook" :
					if (! empty ( $this->options ['social'] ['facebook'] ['id'] )) {
						$this->update_facebook_count ();
					}
					break;
				case "twitter" :
					if (! empty ( $this->options ['social'] ['twitter'] ['id'] )) {
						$this->update_twitter_count ();
					}
					
					break;
				case "google" :
					if (! empty ( $this->options ['social'] ['google'] ['id'] )) {
						$this->update_google_count ();
					}
					
					break;
				
				case "youtube" :
					if (! empty ( $this->options ['social'] ['youtube'] ['id'] )) {
						$this->update_youtube_count ();
					}
					
					break;
				
				case "vimeo" :
					if (! empty ( $this->options ['social'] ['vimeo'] ['id'] )) {
						$this->update_vimeo_count ();
					}
					
					break;
				
				case "github" :
					if (! empty ( $this->options ['social'] ['github'] ['id'] )) {
						$this->update_github_count ();
					}
					
					break;
				
				case "dribbble" :
					if (! empty ( $this->options ['social'] ['dribbble'] ['id'] )) {
						$this->update_dribbble_count ();
					}
					
					break;
				
				case "envato" :
					if (! empty ( $this->options ['social'] ['envato'] ['id'] )) {
						$this->update_envato_count ();
					}
					
					break;
				
				case "soundcloud" :
					if (! empty ( $this->options ['social'] ['soundcloud'] ['id'] )) {
						$this->update_soundcloud_count ();
					}
					
					break;
				
				case "behance" :
					if (! empty ( $this->options ['social'] ['behance'] ['id'] )) {
						$this->update_behance_count ();
					}
					
					break;
				
				case "delicious" :
					if (! empty ( $this->options ['social'] ['delicious'] ['id'] )) {
						$this->update_delicious_count ();
					}
					
					break;
				
				case "instagram" :
					if (! empty ( $this->options ['social'] ['instagram'] ['id'] )) {
						$this->update_instagram_count ();
					}
					
					break;
				
				case "pinterest" :
					if (! empty ( $this->options ['social'] ['pinterest'] ['id'] )) {
						$this->update_pinterest_count ();
					}
					
					break;
			
				case "love" :
					if (! empty ( $this->options ['social'] ['love'] ['id'] )) {
						if ($this->options ['social'] ['love'] ['id'] == "Yes") {
							$this->update_love_count ();
						}
					}

				case "vk":
					if (! empty ( $this->options ['social'] ['vk'] ['id'] )) {
						$this->update_vk_count ();
					}
					break;
				case "rss":
					if (! empty ( $this->options ['social'] ['rss'] ['id'] )) {
						$this->update_rss_count ();
					}
					break;
				case "mailchimp":
					if (! empty ( $this->options ['social'] ['mailchimp'] ['id'] )) {
						$this->update_mailchimp_count();
					}
					break;		
				case "linkedin":
					if (! empty ( $this->options ['social'] ['linkedin'] ['id'] )) {
						$this->update_linkedin_count();
					}
					break;									
				case "tumblr":
					if (! empty ( $this->options ['social'] ['tumblr'] ['id'] )) {
						$this->update_tumblr_count();
					}
					break;									
			}
		}
		
		foreach ( $this->essb_supported_items as $network ) {
			if (isset($this->updating_data[$network])) {
				$this->data[$network] = $this->updating_data[$network];
			}
		}
		
		if (! empty ( $this->data )) {
			$this->update_counts ( $this->data );
		}
	}
	
	function update_tumblr_count() {
		$result = 0;
		if( !empty($this->transient['tumblr']) ){
			$result = $this->transient['tumblr'];
		}
		elseif( empty($this->transient['tumblr']) && !empty( $this->data) && !empty( $this->options['data']['tumblr'] )  ){
			$result = $arq_options['data']['tumblr'];
		}
		else{
			if (!class_exists('LinkedIn')) {
				require_once ESSB_PLUGIN_ROOT.'lib/external/tumblr/tumblroauth.php';
			}
			if ( !class_exists( 'OAuthException' ) ) {
				require_once ESSB_PLUGIN_ROOT . 'lib/external/OAuth/OAuth.php';
			}
			$base_hostname = str_replace( array( 'http://','https://' ) , '', $this->options['social']['tumblr']['id'] );
			$result = 0;
			try {
				$consumer_key		= $this->options['social']['tumblr']['key'];
				$consumer_secret	= $this->options['social']['tumblr']['secret'];
				$oauth_token		= $this->options['social']['tumblr']['token'];
				$oauth_token_secret	= $this->options['social']['tumblr']['tokensecret'];
				$tumblr_api_URI= 'http://api.tumblr.com/v2/blog/'.$base_hostname.'/followers';
				
				$tum_oauth = new TumblrOAuthTie($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
				$tumblr_api = $tum_oauth->post($tumblr_api_URI, '');
				
				if( $tumblr_api->meta->status == 200 && !empty($tumblr_api->response->total_users) )
					$result = (int) $tumblr_api->response->total_users ;
					
			} catch (Exception $e) {
				$result = 0;
			}
		
			if( !empty( $result ) ) //To update the stored data
				 $this->data['tumblr'] = $result;
		
			if( empty( $result ) && !empty( $this->options['data']['tumblr'] ) ) //Get the stored data
				$result = $this->options['data']['tumblr'];
		}
		return $result;		
		
	
		
	}
	
	function update_linkedin_count() {
		if (! empty ( $this->transient ['linkedin'] )) {
			$result = $this->transient ['linkedin'];
		} elseif (empty ( $this->transient ['linkedin'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['linkedin'] )) {
			$result = $this->options ['data'] ['linkedin'];
				
		} else {
			if (!class_exists('LinkedIn')) {
				require_once ESSB_PLUGIN_ROOT.'lib/external/linkedin/linkedin.php';
			}
			if ( !class_exists( 'OAuthServer' ) ) {
				require_once ESSB_PLUGIN_ROOT . 'lib/external/OAuth/OAuth.php';
			}
			$app_key = $this->options['social']['linkedin']['api'];
			$app_secret = $this->options['social']['linkedin']['apps'];
			$company_id = $this->options['social']['linkedin']['id'];
	
			$result = 0;
			
			$opt = array (
					'appKey' => $app_key ,
					'appSecret' => $app_secret ,
					'callbackUrl' => '' ,
			);
			
			$api = new LinkedIn( $opt );
			$response = $api->company( trim( 'universal-name=' . $company_id . ':(num-followers)' ) );
			//print "parsing";
			if ( false !== $response['success'] ) {
				//print "get company";
				$company = new SimpleXMLElement( $response['linkedin'] );
			
				if ( isset( $company->{'num-followers'} ) ) {
			
					$result = current( $company->{'num-followers'} );
				}
			}				
	
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['linkedin'] = $result;
				
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['linkedin'] )) // Get
				// the
				// stored
				// data
				$result = $this->options ['data'] ['linkedin'];
		}
		return $result;
	}
	
	
	function update_mailchimp_count() {
		if (! empty ( $this->transient ['mailchimp'] )) {
			$result = $this->transient ['mailchimp'];
		} elseif (empty ( $this->transient ['mailchimp'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['mailchimp'] )) {
			$result = $this->options ['data'] ['mailchimp'];
			
		} else {
			if (!class_exists('MCAPI')) require_once ESSB_PLUGIN_ROOT.'lib/external/mailchimp/MCAPI.class.php';
	
			$apikey = $this->options['social']['mailchimp']['api'];
			$listId = $this->options['social']['mailchimp']['id'];
	
			$api = new MCAPI($apikey);
			$retval = $api->lists();
			$result = 0;
	
			foreach ($retval['data'] as $list){
				if($list['id'] == $listId){
					$result = $list['stats']['member_count'];
					break;
				}
			}
				
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['mailchimp'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['mailchimp'] )) // Get
			                                                                           // the
			                                                                           // stored
			                                                                           // data
				$result = $this->options ['data'] ['mailchimp'];
		}
		return $result;
	}
	
	function get_posts_count() {
		$count_posts = wp_count_posts();
		$result = $count_posts->publish ;
		return $result;
	}
	
	function get_comments_count() {
		$comments_count  = wp_count_comments() ;
		$result = $comments_count->approved ;
		return $result;
	}
	
	function get_members_count() {
		$members_count  = count_users() ;
		$result = $members_count['total_users'] ;
		return $result;
	}
	
	function update_counts($data) {
		if (empty ( $this->options ['cache'] ) || ! is_int ( $this->options ['cache'] )) {
			$cache = 2;
		} 

		else {
			$cache = $this->options ['cache'];
		}
				
		$cache = intval($cache);
		if ($this->expired) {
			if (is_array ( $data )) {
				foreach ( $data as $item => $value ) {
					$this->transient [$item] = $value;
					$this->options ['data'] [$item] = $value;
				}
			}				
			
			set_transient ( $this->transient_text, $this->transient, $cache * 60 * 60);
			//set_transient ( $this->transient_text, $this->transient, $cache *60);
			update_option ( $this->options_text, $this->options );
		}
	}
	
	function parse($url, $json = true) {
		$request = wp_remote_retrieve_body ( wp_remote_get ( $url, array ('timeout' => 18, 'sslverify' => false ) ) );
		if ($json) {
			$request = @json_decode ( $request, true );
		}
		return $request;
	}
	
	function prettyPrintNumber($number) {
		if (! is_numeric ( $number )) {
			return $number;
		}
		
		if ($number >= 1000000) {
			return round ( ($number / 1000) / 1000, 1 ) . "M";
		} 

		elseif ($number >= 100000) {
			return round ( $number / 1000, 0 ) . "k";
		} 

		else {
			return @number_format ( $number );
		}
	}
	
	// ---------------------- update social counters ---------------------- //
	function update_twitter_count() {
	
		if (! empty ( $this->transient ['twitter'] )) {
			$result = $this->transient ['twitter'];
		} elseif (empty ( $this->transient ['twitter'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['twitter'] )) {
			$result = $this->options ['data'] ['twitter'];
			
		} else {
			//print "update twitter count";
			$twitter_type = $this->options ['social'] ['twitter'] ['type'];
			
			$count = 0;
			if ($twitter_type == "Without API keys") {
				$count = $this->update_twitter_without_api();
			}			
			else{
				$count = $this->update_twitter_with_api();
			}
			
			if (!empty($count)) {
				$this->updating_data ['twitter'] = $count;
			}
			
			if (empty ( $count ) &&  empty ( $this->options ['data'] ['twitter'] )) {
				$this->updating_data ['twitter'] = 0;
			}
					
			if (!$this->updating_data['twitter']) { $this->updating_data['twitter'] = 0; }
			
			if (empty ( $count ) && ! empty ( $this->options ['data'] ['twitter'] )) // Get
				// the
				// stored
				// data
				$count = $this->options ['data'] ['twitter'];
				
			$result = $count;
		}
		return $result;
	}
	
	function update_twitter_with_api() {
		$count = 0;
		$id = $this->options ['social'] ['twitter'] ['id'];
		
		$consumerKey = $this->options ['social'] ['twitter'] ['key'];
		$consumerSecret = $this->options ['social'] ['twitter'] ['secret'];
		
		$consumerToken = $this->options ['social'] ['twitter'] ['token'];
		$consumerTokenSecret = $this->options ['social'] ['twitter'] ['tokensecret'];
		
			
		$count = 0;
		$settings = array(
				'oauth_access_token' => $consumerToken,
				'oauth_access_token_secret' => $consumerTokenSecret,
				'consumer_key' => $consumerKey,
				'consumer_secret' => $consumerSecret
		);
		$apiURL = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name='.$id;
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$data = $twitter->setGetfield($getfield)
		->buildOauth($apiURL, $requestMethod)
		->performRequest();
			
		if(!empty($data)){
			$jsonData = json_decode($data, true);
			if(!empty($jsonData[0]['user']['followers_count'])){
				$count = $jsonData[0]['user']['followers_count'];
			}
		}
			
		return $count;
	}
	
	function update_twitter_without_api() {
		$count = 0;
		$screen_name = $this->options ['social'] ['twitter'] ['id'];
		$data = $this->doCurl("https://cdn.api.twitter.com/1/users/lookup.json?screen_name=" . $screen_name);		
		$data = json_decode($data, true);
		$twitter_data = $data[0];
		$count = $twitter_data['followers_count'];
		return $count;
	}
	
	function update_facebook_count() {
		
		if (! empty ( $this->transient ['facebook'] )) {
			$result = $this->transient ['facebook'];
		} elseif (empty ( $this->transient ['facebook'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['facebook'] )) {
			$result = $this->options ['data'] ['facebook'];
		} else {
			//print 'fb update';
			$id = $this->options ['social'] ['facebook'] ['id'];
			try {
				$data = @$this->parse ( "http://graph.facebook.com/$id" );
				$result = ( int ) $data ['likes'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['facebook'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['facebook'] )) // Get
			                                                                           // the
			                                                                           // stored
			                                                                           // data
				$result = $this->options ['data'] ['facebook'];
		}
		return $result;
	}
	
	function update_vk_count() {
		if (! empty ( $this->transient ['vk'] )) {
			$result = $this->transient ['vk'];
		} elseif (empty ( $this->transient ['vk'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['vk'] )) {
			$result = $this->options ['data'] ['vk'];
		} else {
			//print 'fb update';
			$id = $this->options ['social'] ['vk'] ['id'];
			try {
				$data = @$this->parse ( "http://api.vk.com/method/groups.getById?gid=$id&fields=members_count" );
				$result = (int) $data['response'][0]['members_count'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['vk'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['vk'] )) // Get
			                                                                           // the
			                                                                           // stored
			                                                                           // data
				$result = $this->options ['data'] ['vk'];
		}
		return $result;
	}
	
	function update_rss_count() {
		if (! empty ( $this->transient ['rss'] )) {
			$result = $this->transient ['rss'];
		} elseif (empty ( $this->transient ['rss'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['rss'] )) {
			$result = $this->options ['data'] ['rss'];
		} else {
			//print 'fb update';
			if( ( $this->options ['social']['rss']['type'] == 'feedpress.it' ) && !empty($this->options ['social']['rss']['id']) ){
				try {
					$feedpress_url = esc_url($this->options ['social']['rss']['id']);
					$data =  @$this->parse( $feedpress_url );
					$result =  (int) $data[ 'subscribers' ];	
				} catch (Exception $e) {
					$result = 0;
				}
			}
			elseif( ( $this->options ['social']['rss']['type'] == 'manual' ) && !empty($this->options ['social']['rss']['manual']) ){
				
				$result = $this->options ['social']['rss']['manual'] ;
			}
			else{
				$result = 0;
			}
				
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['rss'] = $result;
				
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['rss'] )) // Get
				// the
				// stored
				// data
				$result = $this->options ['data'] ['rss'];
		}
		return $result;
	}	
	
	function update_google_count() {
		$id = $this->options ['social'] ['google'] ['id'];		
		//$id = str_replace ( '+', '%2B', $id );
		
		$type = $this->options['social']['google']['type'];
		$api = isset($this->options['social']['google']['api']) ? $this->options['social']['google']['api'] : '';
		$count_type = isset($this->options['social']['google']['counter_type']) ? $this->options['social']['google']['counter_type'] : '';
		$result  = 0;
		
		if (! empty ( $this->transient ['google'] )) {
			$result = $this->transient ['google'];
		} elseif (empty ( $this->transient ['google'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['google'] )) {
			$result = $this->options ['data'] ['google'];
		} else {
		
		/*try {
			
			$data = @$this->parse ( "https://apis.google.com/u/0/_/widget/render/page?usegapi=1&bsv=o&width=180&href=https%3A%2F%2Fplus.google.com%2F$id&showcoverphoto=0&showtagline=0&hl=en-US", false );
			
			if (empty ( $data ))
				// $data = file_get_contents(
				// "https://apis.google.com/u/0/_/pages/badge?bsv&hl=en-US&width=200&url=https%3A%2F%2Fplus.google.com%2F$id%3Fprsrc%3D1"
				// );
				$data = file_get_contents ( "https://apis.google.com/u/0/_/widget/render/page?usegapi=1&bsv=o&width=180&href=https%3A%2F%2Fplus.google.com%2F$id&showcoverphoto=0&showtagline=0&hl=en-US" );
			
			preg_match ( '/<div class="gge mgd Oae" style="font-size:11px;">(.*?)<\/div>/s', $data, $result );
			
			if (isset ( $result ) && ! empty ( $result )) {
				$count = $result [1];
				$result = preg_replace ( '/[^0-9_]/', '', $count );
				$result = ( int ) $result;
			}
		} catch ( Exception $e ) {
			$result = 0;
		}*/
		
		if ($api != '') {
			$result = $this->get_google_count_page_api($id, $api, $count_type);			
		}
		else {
			if ($type == "Page") {
				$result = $this->get_google_count_page($id);
				//print "page = " .$result;
			}
			else {
				$result = $this->get_google_count_user($id);
				//print "user = " .$result;
			}
		}
		
		if (! empty ( $result )) // To update the stored data
			$this->updating_data ['google'] = $result;
		
		if (empty ( $result ) && ! empty ( $this->options ['data'] ['google'] )) // Get
		                                                                         // the
		                                                                         // stored
		                                                                         // data
			$result = $this->options ['data'] ['google'];
 		 }
		return $result;
	}
	
	public function get_google_count_page_api($id, $api, $type)
	{
		
		$count = array();
		$url = "https://www.googleapis.com/plus/v1/people/". $id ."?key=". $api;
		$data = self::doCurl($url);
		$circleCount = 0;
		$plusOneCount = 0;
		if(!empty($data)){
			$jsonData = json_decode($data, true);
			if(!empty($jsonData['plusOneCount'])){
				$count['plusOneCount'] = $jsonData['plusOneCount'];
				$plusOneCount = intval($jsonData['plusOneCount']);
			}
			if(!empty($jsonData['circledByCount'])){
				$count['circledByCount'] = $jsonData['circledByCount'];
				$circleCount = intval($jsonData['circledByCount']);
			}
			
		}
		
		if ($type == "plusOneCount") {
			return $plusOneCount;
		}
		else if ($type == "circledByCount") {
			return $circleCount;
		}
		else {
			return ($circleCount + $plusOneCount);
		}
	}
	
	function get_google_count_page($id) {
		$request = @wp_remote_get( 'https://apis.google.com/u/0/_/+1/fastbutton?hl=en&annotation=inline&url=https%3A%2F%2Fplus.google.com%2F' . urlencode( $id ) );
		
		if ( false == $request ) {
			return 0;
		}
		
		@preg_match( '/<span class="A8 RZa">\+([0-9]+)( &nbsp;&nbsp;Recommend this on Google)?<\/span>/s' , @wp_remote_retrieve_body( $request ) , $matches );
		
		if ( count( $matches > 0 ) && isset( $matches[1] ) ) {
			return $matches[1];
		}
		
	}

	function get_google_count_user($id) {
		//$request = @wp_remote_get( 'https://plus.google.com/' . urlencode( $id ) . '/about?hl=en' );
		$request = @wp_remote_get( 'https://plus.google.com/' . ( $id ) . '/about?hl=en' );
		//print 'https://plus.google.com/' . urlencode( $id ) . '/about/?hl=en';
		if ( false == $request ) {
			return 0;
		}
		
		//@preg_match( '/<div class="bkb">([a-zA-Z0-9., ]+) have (him|her) in circles<\/div>/s' , @wp_remote_retrieve_body( $request ) , $matches );
		@preg_match( '/<span class="BOfSxb">([a-zA-Z0-9., ]+)<\/span>/s' , @wp_remote_retrieve_body( $request ) , $matches );
		
		if ( count( $matches > 0 ) && isset( $matches[1] ) ) {
			return str_replace( ',' , '' , $matches[1] );
		}
		
	}
	function update_youtube_count() {
		
		if (! empty ( $this->transient ['youtube'] )) {
			$result = $this->transient ['youtube'];
		} elseif (empty ( $this->transient ['youtube'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['youtube'] )) {
			$result = $this->options ['data'] ['youtube'];
		} else {
			//print "youtube update";
			$id = $this->options ['social'] ['youtube'] ['id'];
			try {
				$data = @$this->parse ( "http://gdata.youtube.com/feeds/api/users/$id?alt=json" );
				$result = ( int ) $data ['entry'] ['yt$statistics'] ['subscriberCount'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['youtube'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['youtube'] )) // Get
			                                                                          // the
			                                                                          // stored
			                                                                          // data
				$result = $this->options ['data'] ['youtube'];
		}
		return $result;
	}
	
	function update_vimeo_count() {
		
		if (! empty ( $this->transient ['vimeo'] )) {
			$result = $this->transient ['vimeo'];
		} elseif (empty ( $this->transient ['vimeo'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['vimeo'] )) {
			$result = $this->options ['data'] ['vimeo'];
		} else {
			$id = $this->options ['social'] ['vimeo'] ['id'];
			try {
				@$data = $this->parse ( "http://vimeo.com/api/v2/channel/$id/info.json" );
				$result = ( int ) $data ['total_subscribers'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['vimeo'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['vimeo'] )) // Get
			                                                                        // the
			                                                                        // stored
			                                                                        // data
				$result = $this->options ['data'] ['vimeo'];
		}
		return $result;
	}
	
	function update_dribbble_count() {
		
		if (! empty ( $this->transient ['dribbble'] )) {
			$result = $this->transient ['dribbble'];
		} elseif (empty ( $this->transient ['dribbble'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['dribbble'] )) {
			$result = $this->options ['data'] ['dribbble'];
		} else {
			$id = $this->options ['social'] ['dribbble'] ['id'];
			try {
				$data = @$this->parse ( "http://api.dribbble.com/$id" );
				$result = ( int ) $data ['followers_count'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['dribbble'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['dribbble'] )) // Get
			                                                                           // the
			                                                                           // stored
			                                                                           // data
				$result = $this->options ['data'] ['dribbble'];
		}
		return $result;
	}
	
	function update_github_count() {
		if (! empty ( $this->transient ['github'] )) {
			$result = $this->transient ['github'];
		} elseif (empty ( $this->transient ['github'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['github'] )) {
			$result = $this->options ['data'] ['github'];
		} else {
			$id = $this->options ['social'] ['github'] ['id'];
			try {
				$data = @$this->parse ( "https://api.github.com/users/$id" );
				$result = ( int ) $data ['followers'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['github'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['github'] )) // Get
			                                                                         // the
			                                                                         // stored
			                                                                         // data
				$result = $this->options ['data'] ['github'];
		}
		return $result;
	}
	
	function update_envato_count() {
		
		if (! empty ( $this->transient ['envato'] )) {
			$result = $this->transient ['envato'];
		} elseif (empty ( $this->transient ['envato'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['envato'] )) {
			$result = $this->options ['data'] ['envato'];
		} else {
			$id = $this->options ['social'] ['envato'] ['id'];
			try {
				$data = @$this->parse ( "http://marketplace.envato.com/api/edge/user:$id.json" );
				$result = ( int ) $data ['user'] ['followers'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['envato'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['envato'] )) // Get
			                                                                         // the
			                                                                         // stored
			                                                                         // data
				$result = $this->options ['data'] ['envato'];
		}
		return $result;
	}
	
	function update_soundcloud_count() {
		
		if (! empty ( $this->transient ['soundcloud'] )) {
			$result = $this->transient ['soundcloud'];
		} elseif (empty ( $this->transient ['soundcloud'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['soundcloud'] )) {
			$result = $this->options ['data'] ['soundcloud'];
		} else {
			$id = $this->options ['social'] ['soundcloud'] ['id'];
			$api = $this->options ['social'] ['soundcloud'] ['api'];
			try {
				$data = @$this->parse ( "http://api.soundcloud.com/users/$id.json?consumer_key=$api" );
				$result = ( int ) $data ['followers_count'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['soundcloud'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['soundcloud'] )) // Get
			                                                                             // the
			                                                                             // stored
			                                                                             // data
				$result = $this->options ['data'] ['soundcloud'];
		}
		return $result;
	}
	
	function update_behance_count() {
		
		if (! empty ( $this->transient ['behance'] )) {
			$result = $this->transient ['behance'];
		} elseif (empty ( $this->transient ['behance'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['behance'] )) {
			$result = $this->options ['data'] ['behance'];
		} else {
			$id = $this->options ['social'] ['behance'] ['id'];
			$api = $this->options ['social'] ['behance'] ['api'];
			try {
				$data = @$this->parse ( "http://www.behance.net/v2/users/$id?api_key=$api" );
				$result = ( int ) $data ['user'] ['stats'] ['followers'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['behance'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['behance'] )) // Get
			                                                                          // the
			                                                                          // stored
			                                                                          // data
				$result = $this->options ['data'] ['behance'];
		}
		return $result;
	}
	
	function update_delicious_count() {
		
		if (! empty ( $this->transient ['delicious'] )) {
			$result = $this->transient ['delicious'];
		} elseif (empty ( $this->transient ['delicious'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['delicious'] )) {
			$result = $this->options ['data'] ['delicious'];
		} else {
			$id = $this->options ['social'] ['delicious'] ['id'];
			try {
				$data = @$this->parse ( "http://feeds.delicious.com/v2/json/userinfo/$id" );
				$result = ( int ) $data [2] ['n'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['delicious'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['delicious'] )) // Get
			                                                                            // the
			                                                                            // stored
			                                                                            // data
				$result = $this->options ['data'] ['delicious'];
		}
		return $result;
	}
	
	function update_instagram_count() {
		
		if (! empty ( $this->transient ['instagram'] )) {
			$result = $this->transient ['instagram'];
		} elseif (empty ( $this->transient ['instagram'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['instagram'] )) {
			$result = $this->options ['data'] ['instagram'];
		} else {
			$api = $this->options ['social'] ['instagram'] ['api'];
			$id = $this->options ['social'] ['instagram']['id'];
			$id = explode ( ".", $id );
			try {
				$data = @$this->parse ( "https://api.instagram.com/v1/users/$id[0]/?access_token=$api" );
				$result = ( int ) $data ['data'] ['counts'] ['followed_by'];
			} catch ( Exception $e ) {
				$result = 0;
			}
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['instagram'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['instagram'] )) // Get
			                                                                            // the
			                                                                            // stored
			                                                                            // data
				$result = $this->options ['data'] ['instagram'];
			
			if (empty ( $result ) && empty ( $this->options ['data'] ['instagram'] )) {
				$this->updating_data ['instagram'] = $result;
			}
		}
		return $result;
	}
	
	function update_pinterest_count() {
		
		//print "start get pin!";
		
		if (! empty ( $this->transient ['pinterest'] )) {
			//print "pin from transitient!";
			$result = $this->transient ['pinterest'];
		} elseif (empty ( $this->transient ['pinterest'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['pinterest'] )) {
			//print_r($this->data);
			//print "data from cache";
			$result = $this->options ['data'] ['pinterest'];
		} else {
			
			$result = '0';
			$id = $this->options ['social'] ['pinterest'] ['id'];

			
			$request = @wp_remote_get( 'http://www.pinterest.com/' . $id );
			
			if ( false == $request ) {
				$result = 0;
			}
			
			@preg_match( ' <meta property="pinterestapp:followers" name="pinterestapp:followers" content="(\d+)" data-app>' , @wp_remote_retrieve_body( $request ) , $matches );
			
			if ( count( $matches > 0 ) && isset( $matches[1] ) ) {
				$result =  $matches[1];
			}
				
			//print " Pinterest update result returns = ".$result;
			
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['pinterest'] = $result;
			
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['pinterest'] )) // Get
			                                                                             // the
			                                                                             // stored
			                                                                             // data
				$result = $this->options ['data'] ['pinterest'];
			
			if (empty ($this->data ['pinterest'] ) ) {
				$this->data ['pinterest'] = 0;
			}
		}
		return $result;
	}
	
	function update_love_count() {
		if (! empty ( $this->transient ['love'] )) {
			$result = $this->transient ['love'];
		} elseif (empty ( $this->transient ['love'] ) && ! empty ( $this->data ) && ! empty ( $this->options ['data'] ['love'] )) {
			$result = $this->options ['data'] ['love'];
		} else {
			$id = $this->options ['social'] ['love'] ['id'];
			$result = 0;
			try {
				$args = array('posts_per_page' => -1, 'post_type' => 'any');
				$posts = get_posts( $args );
				if ($posts) {
					foreach ($posts as $post) {
						$love_count = get_post_meta($post->ID, '_essb_love', true);
						
						
						$love_count = intval($love_count);
						$result = $result + $love_count;
					}
				}
				
			} catch ( Exception $e ) {
				$result = 0;
				
			}
			
				
			if (! empty ( $result )) // To update the stored data
				$this->updating_data ['love'] = $result;
				
			if (empty ( $result ) && ! empty ( $this->options ['data'] ['love'] )) // Get
				// the
				// stored
				// data
				$result = $this->options ['data'] ['love'];
		}
		return $result;
	}	
	
	function handle_essb_fancounts_shortcode($atts) {
		$atts = shortcode_atts ( array ('style' => '', 'cols' => '', 'width' => '', 'border' => 'no', 'small' => 'no' ), $atts );
	
		$style = isset ( $atts ['style'] ) ? $atts ['style'] : 'flat';
		$cols = isset ( $atts ['cols'] ) ? $atts ['cols'] : '2';
		$width = isset ( $atts ['width'] ) ? $atts ['width'] : '';
		$border = isset ( $atts ['border'] ) ? $atts ['border'] : 'no';
		$small = isset ( $atts ['small'] ) ? $atts ['small'] : 'no';
		
		$widget = $this->generate_essb_fans_count($style, $cols, $width, $border, $small);
		return $widget;
	}
	
	function generate_essb_fans_count($style = 'flat', $cols = '2', $width = '', $border = '', $small = '') {
		
		$layout = "";
		if ($style == "") {
			$style = "flat";
		}
		
		if ($style == "mutted") {
			if ($cols == "1") {
				$layout = " outer onecol";
			}
			if ($cols == "2") {
				$layout = " outer twocols";
			}
			if ($cols == "3") {
				$layout = " outer threecols";
			}
			if ($cols == "4") {
				$layout = " outer fourcols";
			}
			
			if ($cols == "") {
				$layout = " outer";
			}
		}
		
		if ($style == "colored") {
			if ($cols == "1") {
				$layout = " outer colored onecol";
			}
			if ($cols == "2") {
				$layout = " outer colored twocols";
			}
			if ($cols == "3") {
				$layout = " outer colored threecols";
			}
			if ($cols == "4") {
				$layout = " outer colored fourcols";
			}
			
			if ($cols == "") {
				$layout = " outer colored";
			}
		}
		
		if ($style == "flat") {
			if ($cols == "1") {
				$layout = " flat onecol";
			}
			if ($cols == "2") {
				$layout = " flat twocols";
			}
			if ($cols == "3") {
				$layout = " flat threecols";
			}
			if ($cols == "4") {
				$layout = " flat fourcols";
			}
			
			if ($cols == "") {
				$layout = " flat";
			}
		}
		
		if ($style == "metro") {
			if ($cols == "1") {
				$layout = " metro onecol";
			}
			if ($cols == "2") {
				$layout = " metro twocols";
			}
			if ($cols == "3") {
				$layout = " metro threecols";
			}
			if ($cols == "4") {
				$layout = " metro fourcols";
			}
				
			if ($cols == "") {
				$layout = " metro";
			}
		}
		
		if ($style == "tiny") {
			$layout .= ' tiny onecol';
		}
		
		if ($border == "yes" && $style != 'flat' && $style != 'metro' && $style != 'tiny') {
			$layout .= " bordered";
		}
		
		if ($small == "yes") { $layout .= " tiny2"; }
		
		wp_register_style ( 'essb-fans-count-style', ESSB_PLUGIN_URL . '/assets/css/essb-fanscount.min.css', array (), $this->version . "" . $this->module_version );
		wp_enqueue_style ( 'essb-fans-count-style' );
		
		$this->update_all_counts ();
		
		if (empty($width) && $style == 'tiny') {
			$width = "70";
		}
		
		if (! empty ( $width )) {
			$pos_percent = strpos($width, '%');
			
			if ($pos_percent !== false) {
				$width = ' style="width:' . $width . '%;"';
			}
			else {
				$width = ' style="width:' . $width . 'px;"';
			}
		}
		
		if (! empty ( $this->options ['sort'] )) {
			$network_sort_items = $this->options ['sort'];
		}
		
		if (empty ( $this->options ['sort'] ) || ! is_array ( $network_sort_items ) || $this->essb_supported_items != array_intersect ( $this->essb_supported_items, $network_sort_items )) {
			$network_sort_items = $this->essb_supported_items;
		}
		
		$widget = "";
		
		
		$widget .= '<div class="essb-fans' . $layout . '">';
		
		$widget .= '<ul>';
		
		foreach ( $network_sort_items as $network ) {
			
			switch ($network) {
				case 'facebook' :
					if (! empty ( $this->options ['social'] ['facebook'] ['id'] )) {
						$text = __ ( 'Fans', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['facebook'] ['text'] ))
							$text = $this->options ['social'] ['facebook'] ['text'];
						
						$widget .= '<li class="essb-fans-facebook"' . $width . '>
						<a href="http://www.facebook.com/' . $this->options ['social'] ['facebook'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-facebook"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['facebook'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'twitter' :
					if (! empty ( $this->options ['social'] ['twitter'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['twitter'] ['text'] ))
							$text = $this->options ['social'] ['twitter'] ['text'];
						
						$widget .= '<li class="essb-fans-twitter"' . $width . '>
						<a href="http://twitter.com/' . $this->options ['social'] ['twitter'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-twitter"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['twitter'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'google' :
					if (! empty ( $this->options ['social'] ['google'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['google'] ['text'] ))
							$text = $this->options ['social'] ['google'] ['text'];
						
						$widget .= '<li class="essb-fans-google"' . $width . '>
						<a href="http://plus.google.com/' . $this->options ['social'] ['google'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-gplus"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['google'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'youtube' :
					if (! empty ( $this->options ['social'] ['youtube'] ['id'] )) {
						$text = __ ( 'Subscribers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['youtube'] ['text'] ))
							$text = $this->options ['social'] ['youtube'] ['text'];
						
						$type = 'user';
						if (! empty ( $this->options ['social'] ['youtube'] ['type'] ) && $this->options ['social'] ['youtube'] ['type'] == 'Channel')
							$type = 'channel';
						
						$widget .= '<li class="essb-fans-youtube"' . $width . '>
						<a href="http://youtube.com/' . $type . '/' . $this->options ['social'] ['youtube'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-youtube"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['youtube'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'vimeo' :
					if (! empty ( $this->options ['social'] ['vimeo'] ['id'] )) {
						$text = __ ( 'Subscribers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['vimeo'] ['text'] ))
							$text = $this->options ['social'] ['vimeo'] ['text'];
						
						$widget .= '<li class="essb-fans-vimeo"' . $width . '>
						<a href="https://vimeo.com/channels/' . $this->options ['social'] ['vimeo'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-vimeo"></i> 
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['vimeo'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'github' :
					if (! empty ( $this->options ['social'] ['github'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['github'] ['text'] ))
							$text = $this->options ['social'] ['github'] ['text'];
						
						$widget .= '<li class="essb-fans-github"' . $width . '>
						<a href="https://github.com/' . $this->options ['social'] ['github'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-github-circled"></i> 
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['github'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'dribbble' :
					if (! empty ( $this->options ['social'] ['dribbble'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['dribbble'] ['text'] ))
							$text = $this->options ['social'] ['dribbble'] ['text'];
						
						$widget .= '<li class="essb-fans-dribbble"' . $width . '>
						<a href="http://dribbble.com/' . $this->options ['social'] ['dribbble'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-dribbble"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['dribbble'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'envato' :
					if (! empty ( $this->options ['social'] ['envato'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['envato'] ['text'] ))
							$text = $this->options ['social'] ['envato'] ['text'];
						
						$widget .= '<li class="essb-fans-envato"' . $width . '>
						<a href="http://' . $this->options ['social'] ['envato'] ['site'] . '.net/user/' . $this->options ['social'] ['envato'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-envato"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['envato'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'soundcloud' :
					if (! empty ( $this->options ['social'] ['soundcloud'] ['id'] ) && ! empty ( $this->options ['social'] ['soundcloud'] ['api'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['soundcloud'] ['text'] ))
							$text = $this->options ['social'] ['soundcloud'] ['text'];
						$widget .= '
					<li class="essb-fans-soundcloud"' . $width . '>
						<a href="http://soundcloud.com/' . $this->options ['social'] ['soundcloud'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-soundcloud"></i> 
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['soundcloud'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'behance' :
					if (! empty ( $this->options ['social'] ['behance'] ['id'] ) && ! empty ( $this->options ['social'] ['behance'] ['api'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['behance'] ['text'] ))
							$text = $this->options ['social'] ['behance'] ['text'];
						
						$widget .= '<li class="essb-fans-behance"' . $width . '>
						<a href="http://www.behance.net/' . $this->options ['social'] ['behance'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-behance"></i> 
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['behance'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'delicious' :
					if (! empty ( $this->options ['social'] ['delicious'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['delicious'] ['text'] ))
							$text = $this->options ['social'] ['delicious'] ['text'];
						
						$widget .= '<li class="essb-fans-delicious"' . $width . '>
						<a href="http://delicious.com/' . $this->options ['social'] ['delicious'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon-delicious"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['delicious'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'instagram' :
					if (! empty ( $this->options ['social'] ['instagram'] ['id'] )) {
						$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['instagram'] ['text'] ))
							$text = $this->options ['social'] ['instagram'] ['text'];
						
						$id = $this->options ['social'] ['instagram'] ['id'];
						$id = explode ( ".", $id );
						
						$widget .= '<li class="essb-fans-instagram"' . $width . '>
						<a href="http://instagram.com/' . $id[1] . '" target="_blank">
							<i class="essb-fans-icon-instagram-filled"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['instagram'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				
				case 'pinterest' :
					if (! empty ( $this->options ['social'] ['pinterest'] ['id'] )) {
						$text = __ ( 'Friends', ESSB_TEXT_DOMAIN );
						if (! empty ( $this->options ['social'] ['pinterest'] ['text'] ))
							$text = $this->options ['social'] ['pinterest'] ['text'];
						
						$widget .= '<li class="essb-fans-pinterest"' . $width . '>
						<a href="http://pinterest.com/' . $this->options ['social'] ['pinterest'] ['id'] . '" target="_blank">
							<i class="essb-fans-icon2-pinterest"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['pinterest'] ) . '</span>
							<small>' . $text . '</small>
						</a>
					</li>';
					
					}
					break;
				case 'love' :
						if (! empty ( $this->options ['social'] ['love'] ['id'] )) {
							
							if ($this->options ['social'] ['love'] ['id'] == "Yes") {
							$text = __ ( 'Loves', ESSB_TEXT_DOMAIN );
							if (! empty ( $this->options ['social'] ['love'] ['text'] ))
								$text = $this->options ['social'] ['love'] ['text'];
					
							$widget .= '<li class="essb-fans-love"' . $width . '>
							<a href="#">
							<i class="essb-fans-icon2-love"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['love'] ) . '</span>
							<small>' . $text . '</small>
							</a>
							</li>';
							}
								
						}
						break;					

				case 'vk' :
						if (! empty ( $this->options ['social'] ['vk'] ['id'] )) {
							
							
							$text = __ ( 'Members', ESSB_TEXT_DOMAIN );
							if (! empty ( $this->options ['social'] ['vk'] ['text'] ))
								$text = $this->options ['social'] ['vk'] ['text'];
					
							$widget .= '<li class="essb-fans-vk"' . $width . '>
					<a href="http://vk.com/'. $this->options ['social'] ['vk'] ['id'].'" target="_blank">
							<i class="essb-fans-icon2-vk"></i>
							<span>' . $this->prettyPrintNumber ( $this->options ['data'] ['vk'] ) . '</span>
							<small>' . $text . '</small>
							</a>
							</li>';
							
								
						}
						break;					
				case 'rss' :
						if (! empty ( $this->options ['social'] ['rss'] ['id'] )) {
							
							$rss_cnt =  isset($this->options ['data'] ['rss']) ? $this->options ['data'] ['rss'] : 0 ;
							
							if (intval($rss_cnt) == 0) {
								$rss_cnt = "";
							}
							else {
								$rss_cnt =  $this->prettyPrintNumber ($rss_cnt);
							}
							
							$text = __ ( 'Subscribers', ESSB_TEXT_DOMAIN );
							if (! empty ( $this->options ['social'] ['rss'] ['text'] ))
								$text = $this->options ['social'] ['rss'] ['text'];
					
							$widget .= '<li class="essb-fans-rss"' . $width . '>
						<a href="' . $this->options ['social'] ['rss'] ['id'] . '" target="_blank">
							
							<i class="essb-fans-icon2-rss"></i>
							<span>' . $rss_cnt . '</span>
							<small>' . $text . '</small>
							</a>
							</li>';
							
								
						}
						break;					
					case 'users' :
						if (! empty ( $this->options ['social'] ['users'] ['id'] )) {
							
							$member_address = isset($this->options ['social'] ['users'] ['url']) ? $this->options ['social'] ['users'] ['url'] : '#'; 
							
							if ($this->options ['social'] ['users'] ['id'] == "Yes") {
							$text = __ ( 'Users', ESSB_TEXT_DOMAIN );
							if (! empty ( $this->options ['social'] ['users'] ['text'] ))
								$text = $this->options ['social'] ['users'] ['text'];
					
							$widget .= '<li class="essb-fans-users"' . $width . '>
							<a href="'.$member_address.'">
							<i class="essb-fans-icon2-users"></i>
							<span>' . $this->prettyPrintNumber ( $this->get_members_count() ) . '</span>
							<small>' . $text . '</small>
							</a>
							</li>';
							}
								
						}
						break;							
					case 'posts' :
						if (! empty ( $this->options ['social'] ['posts'] ['id'] )) {
							
							if ($this->options ['social'] ['posts'] ['id'] == "Yes") {
							$text = __ ( 'Posts', ESSB_TEXT_DOMAIN );
							if (! empty ( $this->options ['social'] ['posts'] ['text'] ))
								$text = $this->options ['social'] ['posts'] ['text'];
					
							$widget .= '<li class="essb-fans-posts"' . $width . '>
							<a href="#">
							<i class="essb-fans-icon2-posts"></i>
							<span>' . $this->prettyPrintNumber ( $this->get_posts_count() ) . '</span>
							<small>' . $text . '</small>
							</a>
							</li>';
							}
								
						}
						break;		
					case 'comments' :
							if (! empty ( $this->options ['social'] ['comments'] ['id'] )) {
									
								if ($this->options ['social'] ['comments'] ['id'] == "Yes") {
									$text = __ ( 'Comments', ESSB_TEXT_DOMAIN );
									if (! empty ( $this->options ['social'] ['comments'] ['text'] ))
										$text = $this->options ['social'] ['comments'] ['text'];
										
									$widget .= '<li class="essb-fans-comments"' . $width . '>
									<a href="#">
									<i class="essb-fans-icon2-comments"></i>
									<span>' . $this->prettyPrintNumber ( $this->get_comments_count() ) . '</span>
									<small>' . $text . '</small>
									</a>
									</li>';
								}
						
							}
							break;
							case 'mailchimp' :
								if (! empty ( $this->options ['social'] ['mailchimp'] ['id'] )) {
										
										
									$text = __ ( 'Subscribers', ESSB_TEXT_DOMAIN );
									if (! empty ( $this->options ['social'] ['mailchimp'] ['text'] ))
										$text = $this->options ['social'] ['mailchimp'] ['text'];
										
									$widget .= '<li class="essb-fans-mailchimp"' . $width . '>
						<a href="' . $this->options ['social'] ['mailchimp'] ['url'] . '" target="_blank">
									<i class="essb-fans-icon-mail-alt"></i>
									<span>' . $this->prettyPrintNumber ( isset($this->options ['data'] ['mailchimp']) ? $this->options ['data'] ['mailchimp'] : 0 ) . '</span>
									<small>' . $text . '</small>
									</a>
									</li>';
										
							
								}
								break;
							case 'linkedin' :
								if (! empty ( $this->options ['social'] ['linkedin'] ['id'] )) {
										
										
									$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
									if (! empty ( $this->options ['social'] ['linkedin'] ['text'] ))
										$text = $this->options ['social'] ['linkedin'] ['text'];
										
									$widget .= '<li class="essb-fans-linkedin"' . $width . '>
						<a href="http://linkedin.com/company/'. $this->options ['social'] ['linkedin'] ['id'].'" target="_blank">
									<i class="essb-fans-icon2-linkedin"></i>
									<span>' . $this->prettyPrintNumber ( isset($this->options ['data'] ['linkedin']) ? $this->options ['data'] ['linkedin'] : 0 ) . '</span>
									<small>' . $text . '</small>
									</a>
									</li>';
										
							
								}
								break;
								case 'tumblr' :
									if (! empty ( $this->options ['social'] ['tumblr'] ['id'] )) {
								
								
										$text = __ ( 'Followers', ESSB_TEXT_DOMAIN );
										if (! empty ( $this->options ['social'] ['tumblr'] ['text'] ))
											$text = $this->options ['social'] ['tumblr'] ['text'];
								
										$widget .= '<li class="essb-fans-tumblr"' . $width . '>
										<a href="'.esc_url( $this->options ['social'] ['tumblr'] ['id'] ).'" target="_blank">
										<i class="essb-fans-icon2-tumblr"></i>
										<span>' . $this->prettyPrintNumber ( isset($this->options ['data'] ['tumblr']) ? $this->options ['data'] ['tumblr'] : 0 ) . '</span>
										<small>' . $text . '</small>
										</a>
										</li>';
								
											
									}
									break;
								
			}
		
		}
		
		$widget .= '</ul>';
		
		$widget .= '</div>';
		
		$widget .= $this->color_customizer_compiler();
		
		return $widget;
	}
	
	function doCurl($url)
	{
		if(!extension_loaded('curl'))
			return;
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_VERBOSE, true );
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function color_customizer_compiler() {
		$essb_options = EasySocialShareButtons_Options::get_instance();
		$options = $essb_options->options;
		//activate_fanscounter_customizer
		
		$activate_fanscounter_customizer = isset($options['activate_fanscounter_customizer']) ? $options['activate_fanscounter_customizer'] : 'false';
		if ($activate_fanscounter_customizer != 'true') { return ""; }
		$output = "";
		if ($activate_fanscounter_customizer == 'true') {
			$css_builder = array();
			
			foreach ($this->essb_supported_items as $network) {
				$color = isset($options['fanscustomizer_'.$network.'_color']) ? $options['fanscustomizer_'.$network.'_color'] : '';
				
				if ($color != '') {
					$css_builder[] = '.essb-fans.colored li a i.essb-fans-icon2-'.$network.',.essb-fans.colored li a i.essb-fans-icon-'.$network.',.essb-fans.metro li.essb-fans-'.$network.' a,.essb-fans.flat li.essb-fans-'.$network.' a,.essb-fans.tiny li.essb-fans-'.$network.' a{background-color: '.$color.' !important;}';
				}
			}
			
			if (count($css_builder) > 0) {
				wp_deregister_style('essb-fans-count-style');
				//wp_register_style ( 'essb-fans-count-style', ESSB_PLUGIN_URL . '/assets/css/essb-fanscount.min.css', array (), $this->version . "" . $this->module_version );
				$output .= '<link rel=\'stylesheet\' id=\'essb-fans-count-style-css\'  href=\''.ESSB_PLUGIN_URL . '/assets/css/essb-fanscount.min.css\' type=\'text/css\' media=\'all\' />';
				$output .= '<style type="text/css">';
				foreach ($css_builder as $css) {
					$output .=  $css;
				}
				$output .= '</style>';
			} 
			
		}
		return $output;
	}
}

/*** Widget Classs ***/

add_action( 'widgets_init', 'essb_fans_counter_widget_box' );
function essb_fans_counter_widget_box() {
	register_widget( 'EasySocialFansCounter_Widget' );
}
class EasySocialFansCounter_Widget extends WP_Widget {

	function EasySocialFansCounter_Widget() {
		$widget_ops = array( 'classname' => 'easy-social-share-fans-counter', 'description' => ''  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'easy-social-share-fans-counter' );
		$this->WP_Widget( 'easy-social-share-fans-counter', 'Easy Social Fans Counter Widget', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $essb_fans;
		extract( $args );

		$title = $instance['title'] ;
		$style = $instance['style'] ;
		$cols = $instance['cols'] ;
		$width = $instance['width'] ;
		$box_only = $instance['box_only'] ;
		$tiny = $instance['tiny'] ;

		if (!empty($tiny)) { $tiny = ' small="yes"'; }
		
		if( empty($box_only) )	echo $before_widget . $before_title . $title . $after_title;
		//$shortcode = '[essb-fans style="'.$style.'" cols="'.$cols.'" width="'.$width.'" '.$tiny.']';
		//echo do_shortcode($shortcode);
		
		$generated = $essb_fans->generate_essb_fans_count($style, $cols, $width);
		echo $generated;
		
		if( empty($box_only) )	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['style'] = $new_instance['style'] ;
		$instance['title'] =  $new_instance['title'] ;
		$instance['cols'] =  $new_instance['cols'] ;
		$instance['width'] =  $new_instance['width'] ;
		$instance['box_only'] =  $new_instance['box_only'] ;
		$instance['tiny'] =  $new_instance['tiny'] ;

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __( 'Social Fans' , ESSB_TEXT_DOMAIN )  , 'style' => 'flat' , 'cols' => '3', 'box_only' => false, 'tiny' => false );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title :' , ESSB_TEXT_DOMAIN ) ?> </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style :' , ESSB_TEXT_DOMAIN ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
					<option value="mutted" <?php if( $instance['style'] == 'mutted' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Grey Icons' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="colored" <?php if( $instance['style'] == 'colored' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Colored Icons' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="metro" <?php if( $instance['style'] == 'metro' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Metro' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="flat" <?php if( $instance['style'] == 'flat' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Flat' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="tiny" <?php if( $instance['style'] == 'tiny' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Tiny' , ESSB_TEXT_DOMAIN ) ?></option>
					</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cols' ); ?>"><?php _e( 'Columns :' , ESSB_TEXT_DOMAIN ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'cols' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>" >
					<option value="" <?php if( $instance['cols'] == '' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( '' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="1" <?php if( $instance['cols'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( '1 Col' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="2" <?php if( $instance['cols'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( '2 Cols' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="3" <?php if( $instance['cols'] == '3' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( '3 Cols' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="4" <?php if( $instance['cols'] == '4' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( '4 Cols' , ESSB_TEXT_DOMAIN ) ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'box_only' ); ?>"><?php _e( 'Show the Social Box only :' , ESSB_TEXT_DOMAIN ) ?></label>
			<input id="<?php echo $this->get_field_id( 'box_only' ); ?>" name="<?php echo $this->get_field_name( 'box_only' ); ?>" value="true" <?php if( $instance['box_only'] ) echo 'checked="checked"'; ?> type="checkbox" />
			<br /><small><?php _e( 'Will avoid the theme widget design and hide the widget title .' , ESSB_TEXT_DOMAIN ) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Forced Items Width :' , ESSB_TEXT_DOMAIN ) ?></label>
			<input id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php if(isset( $instance['width'] )) echo $instance['width']; ?>" style="width:40px;" type="text" /> <?php _e( 'px' , ESSB_TEXT_DOMAIN ) ?>
		</p>
		
		
	<?php
	}
}

?>