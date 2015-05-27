<?php

define('ESSB_ESML_LOG', 'easy_social_metrics_lite_log');
define('ESSB_ESML_DEBUG', false);
$wp_rewrite = new WP_Rewrite();
require_once(ESSB_PLUGIN_ROOT .'lib/modules/easy-social-metrics-lite/esml-metricsupdater-class.php');
require_once(ESSB_PLUGIN_ROOT .'lib/modules/easy-social-metrics-lite/data-sources/sharedcount.com.php');
//include_once('SocialMetricsTrackerWidget.class.php');

class EasySocialMetricsLite {

	private $version = '1.0'; // for db upgrade comparison
	private $updater;
	private $options;

	public function __construct() {

		$this->options = get_option(EasySocialShareButtons::$plugin_settings_name);
		
		if (is_admin()) {
			$this->activate();
			add_action('admin_menu', array($this,'adminMenuSetup'));
			add_action('admin_enqueue_scripts', array($this, 'adminHeaderScripts'));
			add_action('plugins_loaded', array($this, 'version_check'));
		}

		if (is_array($this->options)) {
			$this->updater = new EasySocialMetricsUpdater($this->options);
		}

		// Manual data update for a post
		if (is_admin() && $this->updater && isset($_REQUEST['esml_sync_now'])) {
			$this->updater->updatePostStats($_REQUEST['esml_sync_now']);
			header("Location: ".remove_query_arg('esml_sync_now'));
		}
		
		if (is_admin() && isset($_REQUEST['esml_sync_cancel'])) {
			EasySocialMetricsUpdater::removeAllQueuedUpdates();
			header("Location: ".remove_query_arg('esml_sync_cancel'));
		}
		
		if (is_admin() && isset($_REQUEST['esml_sync_all'])) {
			EasySocialMetricsUpdater::scheduleFullDataSync();
			header("Location: ".remove_query_arg('esml_sync_all'));
		}

	} // end constructor


	public function adminHeaderScripts() {
	} // end adminHeaderScripts()

	public function adminMenuSetup() {

		// Add Social Metrics Tracker menu
		$visibility = 'manage_options';
		add_menu_page( 'Easy Social Metrics Lite', 'Easy Social Metrics Lite', $visibility, 'easy-social-metrics-lite', array($this, 'render_view'), 'dashicons-chart-area', '113.597831' );

		//new SocialMetricsTrackerWidget();

	} // end adminMenuSetup()

	public function render_view() {
		require(ESSB_PLUGIN_ROOT .'lib/modules/easy-social-metrics-lite/esml-render-results.php');
		esml_render_dashboard_view($this->options);
	} 

	public static function timeago($time) {
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

			$difference     = $now - $time;
			$tense         = "ago";

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "s";
		}

		return "$difference $periods[$j] ago";
	}

	/***************************************************
	* Check the version of the plugin and perform upgrade tasks if necessary 
	***************************************************/
	public function version_check() {
		$installed_version = get_option( "esml_version" );

		if( $installed_version != $this->version ) {
			update_option( "esml_version", $this->version );

			// 
			// Do upgrade tasks
			//$this->db_setup();
			EasySocialMetricsUpdater::scheduleFullDataSync();
		}
	}

	public function activate() {		
		//if (defined('WP_ENV') && strtolower(WP_ENV) != 'production' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
			// Do not schedule update
		//} else {
			// Sync all data
		//}
		
		
		$this->version_check();

	}

	public function deactivate() {

		// Remove Queued Updates
		EasySocialMetricsUpdater::removeAllQueuedUpdates();

	}

} // END SocialMetricsTracker

// Run plugin
if (ESSB_ESML_ACTIVE == 'true') {
	$SocialMetricsTracker = new EasySocialMetricsLite();
}
