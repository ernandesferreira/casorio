<?php
function essb_http_remote_get( $url , $json = true) {
	$get_request = wp_remote_get( $url , array( 'timeout' => 18 , 'sslverify' => false ) );
	$request = wp_remote_retrieve_body( $get_request );
	if( $json ) $request = @json_decode( $request , true );
	return $request;
}

function essb_linkedin_getAuthorizationCode( $api_key ) {
	$params = array('response_type' => 'code',
			'client_id' => $api_key,
			'scope' => 'r_fullprofile rw_groups',
			'state' => uniqid('', true), // unique long string
			'redirect_uri' => admin_url().'admin.php?page=essb_settings&tab=fans&service=linkedin',
	);

	$url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);
		
	set_transient( 'linkedin_state', $params['state'] , 60*60 );

	//header("Location: $url");
	echo "<script type='text/javascript'>window.location='".$url."';</script>";
	exit;
}

function essb_linkedin_getAccessToken($api_key , $api_secret) {
	$params = array('grant_type' => 'authorization_code',
			'client_id' => $api_key,
			'client_secret' => $api_secret,
			'code' => $_GET['code'],
			'redirect_uri' => admin_url().'admin.php?page=essb_settings&tab=fans&service=linkedin',
	);
		
	$url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
	$token = essb_http_remote_get( $url, true );
	
	print_r($token);

	// Store access token and expiration time
	set_transient( 'essb_linkedin_expires_in', $token['expires_in'] , 60*60 );
	set_transient( 'essb_linkedin_expires_at',  time() + $token['expires_in'] , 60*60 );
	update_option( 'essb_linkedin_access_token' , $token['access_token']);
	//echo "<script type='text/javascript'>window.location='".admin_url()."admin.php?page=essb_settings&tab=general#linkedin';</script>";
	exit;
}

$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : '';
$code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

if ($cmd == "linkedin") {
	$api_key = isset($_REQUEST['api_key']) ? $_REQUEST['api_key'] : '';
	$api_secret = isset($_REQUEST['api_secret']) ? $_REQUEST['api_secret'] : '';
	set_transient( 'essb_linkedin_api_key', $_REQUEST['api_key'] , 60*60 );
	set_transient( 'essb_linkedin_api_secret', $_REQUEST['api_secret'] , 60*60 );
	
	//essb_linkedin_getAccessToken($api_key, $api_secret);
	essb_linkedin_getAuthorizationCode($api_key);
}

if ($code != '') {
	$api_key	= get_transient( 'essb_linkedin_api_key' );
	$api_secret = get_transient( 'essb_linkedin_api_secret' );
	essb_linkedin_getAccessToken($api_key, $api_secret);
}

?>

<div class="wrap">	
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e( 'LinkedIn App info' , ESSB_TEXT_DOMAIN ) ?></h2>
	<br />
	<form method="post">
		<input name="cmd" value="linkedin" type="hidden"/>
		<div id="poststuff">
			<div id="post-body" class="columns-2">
				<div id="post-body-content" class="arq-content">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'LinkedIn App info' , ESSB_TEXT_DOMAIN ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="api_key"><?php _e( 'API Key' , ESSB_TEXT_DOMAIN ) ?></label></th>
										<td><input type="text" name="api_key" class="code" id="api_key" value=""></td>
									</tr>
									<tr>
										<th scope="row"><label for="api_secret"><?php _e( 'Secret Key' , ESSB_TEXT_DOMAIN ) ?></label></th>
										<td><input type="text" name="api_secret" class="code" id="api_secret" value=""></td>
									</tr>
								</tbody>
							</table>
							<div>
								<strong><?php _e( 'Need Help?' , ESSB_TEXT_DOMAIN ) ?></strong><p><em><?php _e( 'Enter Your APP API Key and Secret Key ,' , ESSB_TEXT_DOMAIN ) ?></p>
								
								<p>
								<ul style="list-style-type: disk; margin: 5px; padding-left: 10px; list-style: inherit;"><li>
								Go To <a href="https://www.linkedin.com/secure/developer">API Keys</a> page.</li>
<li>Click on Add New Application link.</li>
<li>Fill out the form: <ul style="list-style-type: disk; margin: 5px; padding-left: 10px; list-style: inherit;">
<li>Company: Choose your company or add add a new company .</li>
<li>Application Name: enter any name for the Application .</li>
<li>Description: enter a description for the Application .</li>
<li>Website URL: enter your site URL .</li>
<li>Application Use: Select "Other" .</li>
<li>Live Status: Select "Live" .</li>
<li>Developer Contact Email: Enter Your Email.</li>
<li>Phone: Enter Your Phone Number.</li>
<li>Default Scope: Choose "r_fullprofile" and "rw_groups".</li>
<li>Terms of Service: Check the "Agree" checkbox .</li></ul></li>
<li>Click on Add Application Button .</li>
<li>Copy the API Key and Secret Key</li>
<li>Back to the "Plugin Settings" page and Click on Get Access Token link in the end of the Linkedin Box.</li>
</ul>
Enter the API Key and Secret Key in the LinkedIn App info box and click Submit .
It will redirect you to Linkedin Allow Access page. enter your Email and password and click on Allow Access button .
It will redirect you back to the Arqam Settings page .. now the Access Token Key containing a code .</p>
								
								</div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->
				</div> <!-- Post Body COntent -->

							
				<div id="publishing-action">								
					<input type="hidden" name="action" value="linkedin" />
					<input name="save" type="submit" class="button-large button-primary" id="publish" value="<?php _e( 'Submit' , 'arq' ) ?>">
				</div>
				<div class="clear"></div>
				
			</div><!-- post-body /-->
				
		</div><!-- poststuff /-->
	</form>
</div>