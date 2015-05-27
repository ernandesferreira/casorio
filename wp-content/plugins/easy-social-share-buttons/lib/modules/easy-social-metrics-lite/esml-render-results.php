<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}



class EasySocialMetricsLiteResultTable extends WP_List_Table {
   
	public $total_results = array();
	public $top_content = array();
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'post',     //singular name of the listed records
            'plural'    => 'posts',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
        $this->services = array(
        		'facebook'   => 'Facebook',
        		'twitter'    => 'Twitter',
        		'googleplus' => 'Google Plus',
        		'linkedin'   => 'LinkedIn',
        		'pinterest'  => 'Pinterest',
        		'diggs'      => 'Digg.com',
        		'delicious'	 => 'Delicious',
        		'facebook_comments'	 => 'Facebook Comments',
        		'stumbleupon'=> 'Stumble Upon'
        );
        
    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'total':
            	return number_format(intval($item['esml_socialcount_total']));
            case 'comment_count':
                return number_format(intval($item[$column_name]));
            case 'facebook':
            case 'twitter':
            case 'googleplus':
            case 'pinterest':
            case 'linkedin':
            case 'stumbleupon':
            case 'facebook_comments':
            	return number_format(intval($item['esml_socialcount_'.$column_name]));
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }


    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_post_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="post.php?post=%s&action=edit">Edit Post</a>',$item['ID'],'edit',$item['ID']),
            'update'    => '<a href="'.add_query_arg( 'esml_sync_now', $item['ID']).'">Update Stats</a>',
        		'info' => sprintf('Updated %s',EasySocialMetricsLite::timeago($item['esml_socialcount_LAST_UPDATED']))
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['post_title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }



    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'post_title'     => 'Title',
            'total'    => 'Total',
        	'facebook' => 'Facebook',
        	'twitter' => 'Twitter',
        		'googleplus' => 'Google+',
        		'linkedin' => 'LinkedIn',
        		'pinterest' => 'Pinterest',
            'stumbleupon'  => 'StumbleUpon',
            'comment_count'  => 'Post Comments',
        	'facebook_comments'  => 'Facebook Comments'
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'post_title'     => array('post_title',false),     //true means it's already sorted
            'total'    => array('total',false),
            'facebook'  => array('facebook',false),
        		'twitter'  => array('twitter',false),
        		'googleplus'  => array('googleplus',false),
        		'linkedin'  => array('linkedin',false),
        		'pinterest'  => array('pinterest',false),
        		'stumbleupon'  => array('stumbleupon',false),
        		'comment_count'  => array('comment_count',false),
        		'facebook_comments'  => array('facebook_comments',false)
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            //'delete'    => 'Delete'
        );
        return $actions;
    }


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }


    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 20;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        $data = $this->generate_data();
        //print_r($data);
                
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'total'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            
            if ($orderby == 'total') { $orderby = 'esml_socialcount_total'; }
            
            switch ($orderby) {
            case 'facebook':
            case 'twitter':
            case 'googleplus':
            case 'pinterest':
            case 'linkedin':
            case 'stumbleupon':
            case 'facebook_comments':
            	$orderby = 'esml_socialcount_'.$orderby;
            }
            
            if ($orderby == "post_title") {
            	$result = strcmp($a[$orderby], $b[$orderby]);
            } //Determine sort order
            else {
            	if (intval($a[$orderby]) < intval($b[$orderby])) {
            		$result = -1;
            	}
            	else if (intval($a[$orderby]) > intval($b[$orderby])) {
            		$result = 1;
            	}
            	else {
            		$result = 0;
            	}
            }
            
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }

    
    function generate_data() {
    	global $wpdb; //This is used only if making any database queries
    	
    	$per_page = 10;
    	
    	
    	$this->process_bulk_action();
    	
    	// Get custom post types to display in our report.
    	$post_types = $this->get_post_types();
    	//print "post types = ";
    	$limit = 30;
    	
    	add_filter( 'posts_where', array($this, 'date_range_filter') );
    	 
    	$querydata = new WP_Query(array(
    			'posts_per_page'=> -1,
    			'post_status'	=> 'publish',
    			'post_type'		=> $post_types
    	));
    	
    	remove_filter( 'posts_where', array($this, 'date_range_filter') );
    	 
    	$data=array();
    	
    	
    	// foreach ($querydata as $querydatum ) {
    	if ( $querydata->have_posts() ) : while ( $querydata->have_posts() ) : $querydata->the_post();
    	global $post;
    	
    	$item['ID'] = $post->ID;
    	$item['post_title'] = $post->post_title;
    	$item['post_date'] = $post->post_date;
    	$item['comment_count'] = $post->comment_count;
    	$item['esml_socialcount_total'] = (get_post_meta($post->ID, "esml_socialcount_TOTAL", true)) ? get_post_meta($post->ID, "esml_socialcount_TOTAL", true) : 0;
    	$item['esml_socialcount_LAST_UPDATED'] = get_post_meta($post->ID, "esml_socialcount_LAST_UPDATED", true);
    	$item['permalink'] = get_permalink($post->ID);
    	
    	if (!isset($this->total_results['esml_socialcount_total'])) { $this->total_results['esml_socialcount_total'] = 0; }
    	$this->total_results['esml_socialcount_total'] = $this->total_results['esml_socialcount_total'] + $item['esml_socialcount_total'];
    	
    	foreach ($this->services as $slug => $name) {
    		$item['esml_socialcount_'.$slug] = get_post_meta($post->ID, "esml_socialcount_$slug", true);
    		
    		if (!isset($this->total_results['esml_socialcount_'.$slug])) {
    			$this->total_results['esml_socialcount_'.$slug] = 0;
    		}
    		$this->total_results['esml_socialcount_'.$slug] = $this->total_results['esml_socialcount_'.$slug] + $item['esml_socialcount_'.$slug];
    		
    		if (!isset($this->top_content['esml_socialcount_'.$slug ])) {
    			$blank = array("title" => "", "permalink" => "", "value" => "0");
    			$this->top_content['esml_socialcount_'.$slug ] = $blank;
    		}
    		
    		if ($item['esml_socialcount_'.$slug] > $this->top_content['esml_socialcount_'.$slug ]["value"]) {
    			$this->top_content['esml_socialcount_'.$slug ]["value"] = $item['esml_socialcount_'.$slug];
    			$this->top_content['esml_socialcount_'.$slug ]["title"] = $item['post_title'] = $post->post_title;
    			$this->top_content['esml_socialcount_'.$slug ]["permalink"] = $item['permalink'];
    		}
    	}
    	    	
    	array_push($data, $item);
    	endwhile;
    	endif;
    	
    	return $data;
    	 
    }

    public function get_post_types() {
    
    	$types_to_track = array();
    
    	$pts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => true ) );
    	$cpts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => false ) );
    	$options = $this->options;
    
    	if (is_array($options)) {
    		if (!isset($options['esml_monitor_types'])) {
    			$options['esml_monitor_types'] = array();
    		}
    	}
    
    	if (is_array ( $options ) && isset ( $options ['esml_monitor_types'] ) && is_array ( $options ['esml_monitor_types'] )) {
    
    		global $wp_post_types;
    		// classical post type listing
    		foreach ( $pts as $pt ) {
    
    			$selected = in_array ( $pt, $options ['esml_monitor_types'] ) ? '1' : '0';
    
    			if ($selected == '1') {
    				$types_to_track[] = $pt;
    			}
    
    		}
    
    		// custom post types listing
    		if (is_array ( $cpts ) && ! empty ( $cpts )) {
    			foreach ( $cpts as $cpt ) {
    
    				$selected = in_array ( $cpt, $options ['esml_monitor_types'] ) ? '1' : '0';
    					
    				if ($selected == '1') {
    					$types_to_track[] = $cpt;
    				}
    
    				$selected = in_array ( $cpt, $options ['esml_monitor_types'] ) ? 'checked="checked"' : '';
    			}
    		}
    	}
    
    	return $types_to_track;
    
    }
    
    function extra_tablenav( $which ) {
    	if ( $which == "top" ){
    		//The code that goes before the table is here
    		$range = (isset($_GET['range'])) ? $_GET['range'] : 0;
    		?>
    			<label for="range">Show only:</label>
    					<select name="range">
    						<option value="1"<?php if ($range == 1) echo 'selected="selected"'; ?>>Items published within 1 Month</option>
    						<option value="3"<?php if ($range == 3) echo 'selected="selected"'; ?>>Items published within 3 Months</option>
    						<option value="6"<?php if ($range == 6) echo 'selected="selected"'; ?>>Items published within 6 Months</option>
    						<option value="12"<?php if ($range == 12) echo 'selected="selected"'; ?>>Items published within 12 Months</option>
    						<option value="0"<?php if ($range == 0) echo 'selected="selected"'; ?>>Items published anytime</option>
    					</select>
    					
    					<?php do_action( 'esml_dashboard_query_options' ); // Allows developers to add additional sort options ?>
    
    					<input type="submit" name="filter" id="submit_filter" class="button" value="Filter">
    					<a href="<?php echo admin_url('admin.php?page=easy-social-metrics-lite&esml_sync_all=true'); ?>" class="button">Update all posts</a>
    			<?php
    
    		}
    		if ( $which == "bottom" ){
    			//The code that goes after the table is there
    		}
    	}    
    	
    	function date_range_filter( $where = '' ) {
    	
    		$range = (isset($_GET['range'])) ? $_GET['range'] : '0';
    	
    		if ($range <= 0) return $where;
    	
    		$range_bottom = " AND post_date >= '".date("Y-m-d", strtotime('-'.$range.' month') );
    		$range_top = "' AND post_date <= '".date("Y-m-d")."'";
    	
    		$where .= $range_bottom . $range_top;
    		return $where;
    	}
    	

    public function output_total_results() {
    	
    	echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">';
    	echo '<col width="30%"/>';
    	echo '<col width="30%"/>';
    	echo '<col width="40%"/>';
    	
    	echo '<tr>';
    	echo '<td><strong>Total Social Shares:</strong></td>';
    	echo '<td align="right"><strong>'.number_format($this->total_results['esml_socialcount_total']).'</strong></td>';
    	echo '<td>&nbsp;</td>';
    	echo '</tr>';
    	
    	$total = $this->total_results['esml_socialcount_total'];
    	$parse_list = array("facebook" => "Facebook", "twitter" => "Twitter", "googleplus" => "Google+", "pinterest" => "Pinterest", "linkedin" => "LinkedIn", "stumbleupon" => "StumbleUpon");
    	    	
    	foreach ($parse_list as $singleValueCode => $singleValue) {
    		$single_value = $this->total_results['esml_socialcount_'.$singleValueCode];
    		
    		if ($total != 0) {
    		$display_percent = number_format($single_value * 100 / $total, 2);
    		$percent = number_format($single_value * 100 / $total);
    		}
    		else {
    			$display_percent = "0.00";
    			$percent = "0";
    		}
    		
    		if (intval($percent) == 0 && intval($single_value) != 0) { $percent = 1; }
    		
    		echo '<tr>';
    		echo '<td>'.$singleValue.' <span style="background-color: #2980b9; padding: 2px 5px; color: #fff; font-size: 10px; border-radius: 3px;">'.$display_percent.' %</span></td>';
    		echo '<td align="right"><strong>'.number_format($single_value).'</strong></td>';
    		echo '<td><div style="background-color: #2980b9; display: block; height: 24px; width:'.$percent.'%;">&nbsp;</div></td>';
    		echo '</tr>';
    	}
    	
    	echo '</table>';
    }
    
    public function output_total_content() {
    	 
    	echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">';
    	echo '<col width="20%"/>';
    	echo '<col width="20%"/>';
    	echo '<col width="60%"/>';
    	     	 
    	$parse_list = array("facebook" => "Facebook", "twitter" => "Twitter", "googleplus" => "Google+", "pinterest" => "Pinterest", "linkedin" => "LinkedIn", "stumbleupon" => "StumbleUpon");
    
    	foreach ($parse_list as $singleValueCode => $singleValue) {
    		$single_value = $this->top_content['esml_socialcount_'.$singleValueCode]['value'];
    		$title = $this->top_content['esml_socialcount_'.$singleValueCode]['title'];
    		$permalink = $this->top_content['esml_socialcount_'.$singleValueCode]['permalink'];
    		
    
    		echo '<tr>';
    		echo '<td>'.$singleValue.'</td>';
    		echo '<td align="right"><strong>'.number_format($single_value).'</strong></td>';
    		echo '<td><a href="'.$permalink.'" target="_blank">'.$title.'</a></td>';
    		echo '</tr>';
    	}
    	 
    	echo '</table>';
    }    
}





function esml_render_dashboard_view($options){
    
    //Create an instance of our package class...
    $testListTable = new EasySocialMetricsLiteResultTable();
    $testListTable->options = $options;
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    
    ?>
    
    <style type="text/css">

    .column-post_title { width: 30%; }
    .column-total { font-weight: bold; }
    
    </style>
    
    <div class="wrap">
        
        <h2>Easy Social Metrics Lite Dashboard</h2>
        <div style="clear:both;"></div>
        <div class="welcome-panel">
        	<div class="welcome-panel-content">
        	
        		<div class="welcome-panel-column-container">
        			<div class="welcome-panel-column" style="width: 49%;">
        			
        				<h4>Social Networks Presentation</h4>
        				<?php $testListTable->output_total_results(); ?>
        			</div>
        			<div class="welcome-panel-column"  style="width: 49%;">
        			
        				<h4>Top Shared Content by Social Network</h4>        				
        				<?php $testListTable->output_total_content();?>
        			</div>

        		</div>	
        	</div>
        
        </div>

        <?php EasySocialMetricsUpdater::printQueueLength(); ?>       
        
        
		<form id="easy-social-metrics-lite" method="get" action="admin.php?page=easy-social-metrics-lite">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<input type="hidden" name="orderby" value="<?php echo (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'total'; ?>" />
			<input type="hidden" name="order" value="<?php echo (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'DESC'; ?>" />
                    <?php $testListTable->display() ?>
        </form>
 		
    </div>
    <?php
}