<?php
if(!class_exists('WP_List_Table')){
    require_once( SB_DIR_PATH.'admin/class-wp-comments-list-table.php' );
}
class SB_Goole_Map_List_Table extends WP_List_Table {


    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'map',     //singular name of the listed records
            'plural'    => 'maps',    //plural name of the listed records
            'ajax'      => false      //does this table support ajax?
        ) );
        
    }


    /* 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     */
    function column_default($item, $column_name){
        switch($column_name){
			case 'map_title':
				return $this->column_title($item);
			case 'map_styles':
                return $item[$column_name];
			case 'shortcode':
                return '<code>[SBMAP ID="'.$item['map_id'].'"]</code>';
			case 'width_height':
                $width_height = @unserialize($item[$column_name]);
				if(is_array($width_height)) {
					return 'Width: '.$width_height['width'].$width_height['widthtype'].'<br>Height: '.$width_height['height'].$width_height['heighttype'];
				}
            default:
                return '';
        }
    }


    /*
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td>
     */
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=sb-google-map-form&id=%s">Edit</a>',$item['map_id']),
			'mappreview'   => sprintf('<a href="?page=sb-google-map-form&id=%s&preview=true">Preview</a>',$item['map_id']),
            'delete'    => sprintf('<a href="?page=%s&_wpnonce='.wp_create_nonce('bulk-maps').'&action=%s&map=%s&paged=%s">Delete</a>',$_REQUEST['page'],'delete',$item['map_id'],$this->get_pagenum()),
        );
        
        //Return the title contents
        return sprintf('<strong>%1$s<strong> %2$s',
            /*$1%s*/ $item['map_title'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }


    /*
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td>
     */
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label
            /*$2%s*/ $item['map_id']                //The value of the checkbox should be the record's id
        );
    }


    /* 
	 * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     */
    function get_columns(){
        $columns = array(
            'cb'      			=> '<input type="checkbox" />', //Render a checkbox instead of text
			'map_title'    	 	=> 'Title',
			'width_height' 	 	=> 'Width & Height',
            'map_styles'	   	=> 'Style',
			'shortcode'  		=> 'Shortcode'
        );
        return $columns;
    }


    /*
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'map_id'     	=> array('map_id',false),     //true means it's already sorted
			'map_title'     => array('map_title',false)
        );
        return $sortable_columns;
    }


    /*
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     */
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }


    /*
     * @see $this->prepare_items()
     */
    function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete' === $this->current_action() ) {
            $nonce = $_REQUEST['_wpnonce'];
			
			if(isset($_REQUEST['map'])) {
				$maps = $_REQUEST['map'];
				
				if(!wp_verify_nonce($nonce, 'bulk-maps')) {
					wp_die('Invalid nonce...');
				} else {
					$deleted_records = $this->delete_map($maps);
					wp_redirect('?page=sb-google-map&paged='.$this->get_pagenum().'&deleted='.$deleted_records);
					exit();
				}
			}
        }
    }
	
	function delete_map($maps) {
		global $wpdb;
		$map_ids = array();
		if(!is_array($maps)) {
			$map_ids[] = $maps;
		} else {
			$map_ids = $maps;
		}
		foreach($map_ids as $map_id) {
			$wpdb->delete( $wpdb->prefix.'sb_google_map', array( 'map_id' => $map_id ), array( '%d' ) );
		}
		return count($map_ids);
	}


    /*
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     */
    function prepare_items() {
        global $wpdb;
        $per_page = 20;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
		
		$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'map_id';
		$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
		
		$data = $wpdb->get_results("select * from ".$wpdb->prefix."sb_google_map order by ".$orderby." ".$order, ARRAY_A);
        
		//Pagination
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}