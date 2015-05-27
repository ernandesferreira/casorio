<?php

// if (isset($_POST)) {
// if (!check_admin_referer('essb')) { die(); }
// }
$current_admin_url = 'http' . (is_ssl () ? 's' : '') . '://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
$action = str_replace ( '%7E', '~', $_SERVER ['REQUEST_URI'] );
$welcome_action = isset($_REQUEST['welcome']) ? $_REQUEST['welcome'] : '';
if ($welcome_action == '0') {
	update_option('essb-welcome-deactivated', 'true');
}

$current_tab = (empty ( $_GET ['tab'] )) ? 'general' : sanitize_text_field ( urldecode ( $_GET ['tab'] ) );

$tabs = array ('general' => __ ( 'Main Settings', ESSB_TEXT_DOMAIN ), 'display' => __ ( 'Display Settings', ESSB_TEXT_DOMAIN ), 'customizer' => __ ( 'Style Settings', ESSB_TEXT_DOMAIN ), 'shortcode' => __ ( 'Shortcode Generator', ESSB_TEXT_DOMAIN ), "stats" => "Click Statistics", "backup" => "Import/Export Settings", "update" => "Automatic Updates" );

$first_time_option = get_option ( 'essb-first-time' );
if (isset ( $first_time_option )) {
	if ($first_time_option == 'true') {
		//$current_tab = "wizard";
	}
	
	delete_option ( 'essb-first-time' );
}

$welcome_active = true;
if ($current_tab == "wizard") { $welcome_active = false; }

$welcome_option = get_option ( 'essb-welcome-deactivated' );
if (isset($welcome_option)) {
	if ($welcome_option == "true") {
		$welcome_active = false;
	}
}

if ($current_tab != 'wizard') {
	
	?>

<div class="wrap">
	<div class="icon32">
		<img
			src="<?php echo ESSB_PLUGIN_URL . '/assets/images/essb_32.png';?>" />
	</div>
	<h2 class="nav-tab-wrapper">
<?php
	foreach ( $tabs as $name => $label ) {
		echo '<a href="' . admin_url ( 'admin.php?page=essb_settings&tab=' . $name ) . '" class="nav-tab ';
		if ($current_tab == $name)
			echo 'nav-tab-active';
		echo '">' . $label . '</a>';
	}
}
?>
</h2>
<?php if ($welcome_active) { ?>
	<div class="wrap" id="essb-welcome">
		<div class="welcome-panel" id="welcome-panel">
			<a class="welcome-panel-close"
				href="<?php echo $current_admin_url;?>&welcome=0">Dismiss</a>

			<div class="welcome-panel-content">
				<h3>Welcome to Easy Social Share Buttons for WordPress!</h3>
				<p >You are running Easy Social Share Buttons for WordPress version <strong><?php echo ESSB_VERSION;?></strong>. <strong><a href="http://fb.creoworx.com/essb/change-log/" target="_blank">See what's new in this version.</a></strong></p>
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<h4>Get Started</h4>
						<a
							class="button button-primary button-hero load-customize hide-if-no-customize"
							href="<?php echo admin_url ( 'admin.php?page=essb_settings&tab=wizard' ); ?>">Customize
							Display Using Configuration Wizard</a> 
						<p class="hide-if-no-customize">
							or, <a href="<?php echo admin_url ( 'admin.php?page=essb_settings&tab=backup&section=2' ); ?>">import ready made configurations (Upworthy style, Mashable Style, Default Plugin Demo, Your own backup of settings)</a>
						</p>
					</div>
					<div class="welcome-panel-column">
						<h4>More Actions</h4>
						<ul class="essb-welcome-list">
							<li><a
								href="<?php echo $current_admin_url.'&easy-mode=true'; ?>"
								><i class="fa fa-check-square-o fa-essb-welcome"></i>Turn on Easy Mode Options</a></li>
							<li><a
								href="<?php echo admin_url ( 'admin.php?page=essb_settings&tab=update' ); ?>"
								><i class="fa fa-refresh fa-essb-welcome"></i>Activate automatic update</a></li>
								
								<li><a
								href="http://codecanyon.net/downloads" target="_blank"
								><i class="fa fa-star fa-essb-welcome"></i>Rate Easy Social Share Buttons for WordPress</a></li>		
														</ul>
					</div>
					<div class="welcome-panel-column welcome-panel-last">
						<h4>Support</h4>
						<ul class="essb-welcome-list">
						<li><a
								href="http://support.creoworx.com/section/easy-social-share-buttons-for-wordpress/how-to-work-with-easy-social-share-buttons/" target="_blank"
								><i class="fa fa-external-link-square fa-essb-welcome"></i>Read plugin documentation</a></li>
						<li><a
								href="http://support.creoworx.com/section/easy-social-share-buttons-for-wordpress/shortcodes/" target="_blank"
								><i class="fa fa-code fa-essb-welcome"></i>Read shortcodes documentation</a></li>
			<li><a
								href="http://support.creoworx.com/forums/forum/wordpress-plugins/easy-social-share-buttons/" target="_blank"
								><i class="fa fa-question-circle fa-essb-welcome"></i>Need Help? Visit our support site</a></li>	
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>
<?php

switch ($current_tab) :
	case "general" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-general.php');
		
		break;
	case "display" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-display.php');
		
		break;
	case "shortcode" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-shortcode.php');
		
		break;
	case "backup" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-backup.php');
		
		break;
	case "stats" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-stats.php');
		
		break;
	case "update" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-autoupdate.php');
		
		break;
	case "customizer" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-customize.php');
		
		break;
	case "fans" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-fans.php');
		break;
	case "wizard" :
		include (ESSB_PLUGIN_ROOT . '/lib/admin/pages/essb-settings-wizard.php');
		break;
endswitch
;

?>
