<?php

global $wpdb;
define ('ESSB_TABLE_STATS', $wpdb->prefix . "essb_click_stats");

class EasySocialShareButtons_Stats {
	
	public $plugin_settings_name = "easy-social-share-buttons";
	public $code_is_added = false;
	//$option = get_option ( self::$plugin_settings_name );
	
	private static $instance = null;
	
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	public function __construct() {
		$options = get_option(  EasySocialShareButtons::$plugin_settings_name );		
		$stats_active = isset($options['stats_active']) ? $options['stats_active'] : 'false';
		
		
		//if ($stats_active ==  'true') {
			add_action ( 'wp_ajax_nopriv_essb_stat_action_new', array ($this, 'log_click' ) );
			add_action ( 'wp_ajax_essb_stat_action_new', array ($this, 'log_click' ) );
		//}
		
	}
	
	public function generate_log_js_code_jsbuilder() {
		global $post;
		
		if ($this->code_is_added) {
			return "";
		}
		
		$query_object_id = get_queried_object_id();
		
		$this->code_is_added = true;
		$result = "
		var essb_stat_data = {
		'ajax_url': '" . admin_url ('admin-ajax.php') . "'
		};
		jQuery(document).bind('essb_button_action', function (e, service, post_id) {
		jQuery.post(essb_stat_data.ajax_url, {
		'action': 'essb_stat_action_new',
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
		function essb_handle_stats(service, post_id) {
		jQuery(document).trigger('essb_button_action',[service, post_id]);
		};
		";
		
		return $result;
	}
	
	public function generate_log_js_code() {
		
		global $post;		
		
		if ($this->code_is_added) { return ""; }
		
		$query_object_id = get_queried_object_id();
		
		$this->code_is_added = true;
		$result = "
		<script type=\"text/javascript\" language=\"javascript\">
var essb_stat_data = {
    'ajax_url': '" . admin_url ('admin-ajax.php') . "'
};
jQuery(document).bind('essb_button_action', function (e, service) {

    jQuery.post(essb_stat_data.ajax_url, {
            'action': 'essb_stat_action',
            'post_id': " . (isset($post) ? $query_object_id : 0) . ",
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
function essb_handle_stats(service) {
	jQuery(document).trigger('essb_button_action',[service]);
}
</script>
";		

		print $result;
	}
		
	public function log_click() {
		global $wpdb, $blog_id;
		
		$post_id = isset($_POST["post_id"]) ? $_POST["post_id"] : '';
		$service_id = isset($_POST["service"]) ? $_POST["service"] : '';
		
		$rows_affected = $wpdb->insert ( ESSB_TABLE_STATS, array ('essb_blog_id' => $blog_id, 'essb_post_id' => $_POST ["post_id"], 'essb_service' => $_POST ["service"] ) );
		sleep ( 1 );
		echo json_encode ( array ("success" => 'Log handled' ) );
		die ( json_encode ( array ("success" => 'Log handled' ) ) );
	}	
}

class EasySocialShareButtons_Stats_Admin {

	public $plugin_settings_name = "easy-social-share-buttons";
	public $code_is_added = false;
	//$option = get_option ( self::$plugin_settings_name );

	private static $instance = null;

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	} // end get_instance;


	/**
	 * @since 1.2.6
	 */
	public static function install() {
		global $wpdb;

		$sql = "";

		$table_name = $wpdb->prefix . "essb_click_stats";

		$sql .= "CREATE TABLE $table_name (
		essb_id mediumint(9) NOT NULL AUTO_INCREMENT,
		essb_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		essb_blog_id VARCHAR(10) NOT NULL,
		essb_post_id VARCHAR(10) NOT NULL,
		essb_service VARCHAR(20) NOT NULL,
		UNIQUE KEY essb_id (essb_id)
		); ";



		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta ( $sql );
	}

	public function essb_stat_admin_by_networks() {
		global $wpdb;

		$options = get_option(  EasySocialShareButtons::$plugin_settings_name );

		$essb_networks = $options['networks'];

		$query = "";

		//foreach($essb_networks as $k => $v) {
		$query .= "SELECT COUNT( essb_post_id ) AS cnt";

		foreach($essb_networks as $k => $v) {
			$query .= ",SUM( IF( essb_service =  '".$k."', 1, 0 ) ) AS ".$k;
		}

		$query .= " FROM  ".ESSB_TABLE_STATS . "
		ORDER BY cnt DESC ";


		$network_stats = $wpdb->get_row ( $query );
		$graph_data = "";
		if (isset($network_stats)) {

			foreach($essb_networks as $k => $v) {
				if ($graph_data != '') {
					$graph_data .= ",";
				}

				$graph_data .= "{ label:'".$v[1]."', value: ".$network_stats->{$k}."}";
			}
		}


		print '<h3>Activity by Social Networks</h3>';

		print '<div class="col1_2"><div id="essb-network-usage" style="height: 300px;"></div>
		<script type="text/javascript">
		Morris.Donut({
		element: \'essb-network-usage\',
		data: [
		'.$graph_data.'
		]

	});

	</script>

	</div></div>
	<div class="col1_2">';

		print '<table border="0" cellpadding="10" cellspacing="0" width="99%">
		<tr>
		<td class="sub2">Network</td>
		<td class="sub2">Clicks</td>
		</tr>
		';

		if (isset($network_stats)) {
			$cnt = 0;
			foreach($essb_networks as $k => $v) {

				$cnt++;
					
				$class= "";
					
				if ($cnt % 2 == 0) {
					$class = "odd table-border-bottom";
				} else { $class= "even table-border-bottom";
				}
					
				print "<tr class=\"".$class."\">";
					

				print "<td>".$v[1]."</td>";
				print "<td  align=\"right\">".$network_stats->{$k}."</td>";

				print "</tr>";
			}
		}


		print '</table></div>';
	}

	public function essb_stat_admin_detail_by_post($month = '') {
		global $wpdb;

		$options = get_option(  EasySocialShareButtons::$plugin_settings_name );

		$essb_networks = $options['networks'];

		$query = "";

		//foreach($essb_networks as $k => $v) {
		$query .= "SELECT essb_post_id, COUNT( essb_post_id ) AS cnt";

		foreach($essb_networks as $k => $v) {
			$query .= ",SUM( IF( essb_service =  '".$k."', 1, 0 ) ) AS ".$k;
		}

		if ($month == '') {
			$query .= " FROM  ".ESSB_TABLE_STATS . "
			GROUP BY essb_post_id
			ORDER BY cnt DESC ";
		}
		else {
			$query .= " FROM  ".ESSB_TABLE_STATS . "
			WHERE DATE_FORMAT( essb_date,  \"%Y-%m\" ) = '".$month."'
			GROUP BY essb_post_id
			ORDER BY cnt DESC ";
		}

		//print $query;
		$post_stats = $wpdb->get_results ( $query );

		print '<table border="0" cellpadding="5" cellspacing="0" width="100%">';

		print "<tr>";

		print "<td class=\"sub2\">Post/Page</td>";
		print "<td class=\"sub2\">Total</td>";

		foreach($essb_networks as $k => $v) {
			print "<td class=\"sub2\">".$v[1]."</td>";
		}

		print "</tr>";

		if (isset($post_stats)) {
			$cnt = 0;
			foreach ( $post_stats as $rec ) {
					
				$cnt++;
					
				$class= "";
					
				if ($cnt % 2 == 0) {
					$class = "odd table-border-bottom";
				} else { $class= "even table-border-bottom";
				}
					
				print "<tr class=\"".$class."\">";
					
				print "<td><a href=\"".get_permalink($rec->essb_post_id)."\">".get_the_title($rec->essb_post_id).'</a></td>';
				print "<td align=\"right\" class=\"bold\">".$rec->cnt.'</td>';

				foreach($essb_networks as $k => $v) {
					print "<td align=\"right\">".$rec->{$k}.'</td>';
				}

				print "</tr>";
			}
		}

		print "</table>";
	}

	public function generate_months_dropdown_values($month = '') {
		global $wpdb;

		$sql = "SELECT DATE_FORMAT( essb_date,  \"%Y-%m\" ) AS month FROM ".ESSB_TABLE_STATS." GROUP BY MONTH ORDER BY month DESC";

		$result = $wpdb->get_results ( $sql );

		if (isset($result)) {
			foreach ( $result as $rec ) {
				print '<option value="'.$rec->month.'" '.($rec->month == $month ? ' selected="selected"': '').'>'.$rec->month.'</option>';
					
			}
		}
	}

	public function generate_bar_graph_month($month) {
		global $wpdb;

		//cal_days_in_month(CAL_GREGORIAN, 8, 2003);
		$month_arr = explode("-", $month);
		$days_in_mon = cal_days_in_month(CAL_GREGORIAN, intval($month_arr[1]), intval($month_arr[0]));

		$query = "";

		$query_date_stats = "SELECT DATE_FORMAT(essb_date, \"%Y-%m-%d\") AS essb_date, COUNT( essb_post_id ) AS cnt FROM ".ESSB_TABLE_STATS." GROUP BY DATE_FORMAT(essb_date, \"%Y-%m-%d\") ORDER BY essb_date DESC";
		$date_stats = $wpdb->get_results ( $query_date_stats );

		$graph_data = "";

		if (isset($date_stats)) {
			foreach ( $date_stats as $rec ) {
				$date = $rec->essb_date;
				$result_array[$date] = $rec;
			}
		}


		for ($i=1;$i<=intval($days_in_mon);$i++) {

			if ($graph_data != "") {
				$graph_data .= ",";
			}

			$day = strval($i);
			if ($i < 10) {
				$day = "0".strval($i);
			}
				
			$today = $month . "-" . $day;
			if (isset($result_array[$today])) {
				//print "exist " . $today;
				$rec = $result_array[$today];
				$graph_data .= "{ y: '".$today."', a:".intval($rec->cnt)."}";
			}
			else {
				$graph_data .= "{ y: '".$today."', a:".intval(0)."}";
			}

		}

		print '
		<div id="bar-by-dates"></div>

		<script type="text/javascript">
		Morris.Bar({
		element: \'bar-by-dates\',
		data: [
		'.$graph_data.'
		],
		xkey: \'y\',
		ykeys: [\'a\'],
		labels: [\'Total\']
	});

	</script>
	';
	}
}


?>