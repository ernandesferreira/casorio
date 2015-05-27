<?php
/*
Plugin Name: NEX-Forms
Plugin URI:
Plugin Prefix: wap_ 
Module Ready: Yes
Plugin TinyMCE: popup
Description: Drag and Drop WordPress form builder
Author: Basix
Version: 2.1
Author URI: http://codecanyon.net/user/Basix/portfolio?ref=Basix
License: GPL
*/
//ini_set('error_reporting',0);
error_reporting(1);
wp_enqueue_script('jquery');
require( dirname(__FILE__) . '/includes/Core/includes.php');
require( dirname(__FILE__) . '/includes/class.admin.php');
define('SESSION_ID',rand(0,99999999999));
/***************************************/
/**********  Configuration  ************/
/***************************************/
class NEXForms_Config{
	/*************  General  ***************/
	/************  DONT EDIT  **************/
	public $plugin_version;
	/* The displayed name of your plugin */
	public $plugin_name;
	/* The alias of the plugin used by external entities */
	public $plugin_alias;
	/* Enable or disable external modules */
	public $enable_modules;
	/* Plugin Prefix */
	public $plugin_prefix;
	/* Plugin table */
	public $plugin_table, $component_table;
	/* Admin Menu */
	public $plugin_menu;
	/* Add TinyMCE */
	public $add_tinymce;
	
	
	/************* Database ****************/
	/* Sets the primary key for table created above */
	public $plugin_db_primary_key = 'Id';
	/* Database table fields array */
	public $plugin_db_table_fields = array
			(
			'title'								=>	'text',
			'description'						=>	'text',
			'mail_to'							=>  'text',
			'confirmation_mail_body'			=>  'longtext',
			'confirmation_mail_subject'			=>	'text',
			'from_address'						=>  'text',
			'from_name'							=>  'text',
			'on_screen_confirmation_message'	=>  'longtext',
			'confirmation_page'					=>  'text',
			'form_fields'						=>	'longtext',
			'visual_settings'					=>	'text',
			'google_analytics_conversion_code'  =>  'text',
			'colour_scheme'  					=>  'text',
			'send_user_mail'					=>  'text',
			'user_email_field'					=>  'text',
			'on_form_submission'				=>  'text',
			'date_sent'							=>  'datetime',
			'is_form'							=>  'text',
			'is_template'						=>  'text'
			);
			
	public $addtional_table_fields = array
			(
			'nex_forms_Id'			=>	'text',
			'meta_key'				=>	'text',
			'meta_value'			=>  'text',
			'time_added'			=>	'text',
			'u_id'					=>	'text'
			);
	/************* Admin Menu **************/
	public function build_plugin_menu(){
	
		$plugin_alias  = $this->plugin_alias;
		$plugin_name  = $this->plugin_name;
				
		$this->plugin_menu = array
			(
			$this->plugin_name => array
				(
				'menu_page'	=>	array
					(
					'page_title' 	=> $this->plugin_name,
					'menu_title' 	=> $this->plugin_name,
					'capability' 	=> 'administrator',
					'menu_slug' 	=> ''.$plugin_alias.'-main',
					'function' 		=> 'NEXForms_main_page',
					'icon_url' 		=> WP_PLUGIN_URL.'/nex-forms/images/menu_icon.png',
					'position '		=> ''
					),)		
			);
		}
	
	public function __construct()
		{ 
		$header_info = IZC_Functions::get_file_headers(dirname(__FILE__).DIRECTORY_SEPARATOR.'main.php');
		
		$this->plugin_version 	= $header_info['Version'];
		$this->plugin_name 		= $header_info['Plugin Name'];
		$this->enable_modules 	= ($header_info['Module Ready']='Yes') ? true : false ;
		$this->plugin_alias		= IZC_Functions::format_name($this->plugin_name);
		$this->plugin_prefix	= $header_info['Plugin Prefix'];
		$this->plugin_table		= $this->plugin_prefix.$this->plugin_alias;
		$this->component_table	= $this->plugin_table;
		$this->add_tinymce		= $header_info['Plugin TinyMCE'];
		$this->build_plugin_menu(); 
		}
}

/***************************************/
/*************  Hooks   ****************/
/***************************************/
add_action('wp_ajax_NEXForms_tinymce_window', 'NEXForms_tinymce_window');
/* On plugin activation */
register_activation_hook(__FILE__, 'NEXForms_run_instalation' );
/* On plugin deactivation */
//register_deactivation_hook(__FILE__, 'NEXForms_deactivate');
/* Called from page */
add_shortcode( 'NEXForms', 'NEXForms_ui_output' );
/* Build admin menu */
add_action('admin_menu', 'NEXForms_main_menu');
/* Add action button to TinyMCE Editor */
add_action('init', 'NEXForms_add_mce_button');

/***************************************/
/*********  Hook functions   ***********/
/***************************************/
/* Convert menu to WP Admin Menu */
function NEXForms_main_menu(){
	$config = new NEXForms_Config();
	IZC_Admin_menu::build_menu($config->plugin_name);
}
/* Called on plugin activation */
function NEXForms_run_instalation(){
	$config = new NEXForms_Config();
	
	update_option('nex-forms-version',$config->plugin_version);
	if(!get_option('basix-client-id'))
		add_option('basix-client-id',rand(10000000,99999999));
		
	$instalation = new IZC_Instalation();
	$instalation->component_name 			=  $config->plugin_name;
	$instalation->component_prefix 			=  $config->plugin_prefix;
	$instalation->component_alias			=  'nex_forms';
	$instalation->component_default_fields	=  $config->default_fields;
	$instalation->component_menu 			=  $config->plugin_menu;	
	$instalation->db_table_fields			=  $config->plugin_db_table_fields;
	$instalation->db_table_primary_key		=  $config->plugin_db_primary_key;
	$instalation->run_instalation('full');
	
	/************************************************/
	/************  Additional Table   ***************/
	/************************************************/
	$extra_instalation = new IZC_Instalation();
	$extra_instalation->component_prefix 		=  $config->plugin_prefix;
	$extra_instalation->component_alias			=  'nex_forms_meta';
	$extra_instalation->db_table_fields			=  $config->addtional_table_fields;
	$extra_instalation->db_table_primary_key	=  $config->plugin_db_primary_key;
	$extra_instalation->install_component_table();	
}

/* Add action button to TinyMCE Editor */
function NEXForms_add_mce_button() {
	add_filter("mce_external_plugins", "NEXForms_tinymce_plugin");
 	add_filter('mce_buttons', 'NEXForms_register_button');
}
/* register button to be called from JS */
function NEXForms_register_button($buttons) {
   array_push($buttons, "separator", "nexcalendar");
   return $buttons;
}

/* Send request to JS */
function NEXForms_tinymce_plugin($plugin_array) {
   $plugin_array['nexcalendar'] = WP_PLUGIN_URL.'/nex-forms/tinyMCE/plugin.js';
   return $plugin_array;
}
function NEXForms_tinymce_window(){
	include_once( dirname(__FILE__).'/includes/window.php');
    die();
}
	
/***************************************/
/*********   Admin Pages   *************/
/***************************************/
//Landing page
function NEXForms_main_page(){

	$config 	= new NEXForms_Config();
	$template 	= new IZC_Template();
	$custom		= new NEXForms_admin();
	
	$custom->plugin_name  = $config->plugin_name;
	$custom->plugin_alias = $config->plugin_alias;
	$custom->plugin_table = $config->plugin_table;
		
	$template -> build_header( $config->plugin_name,'' , $template->build_menu($modules_menu),'',$config->plugin_alias);
	
	$body .= '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
	$body .= $custom->NEXForms_admin();	

	$template -> build_body($body);
	$template -> build_footer('');	
	$template -> print_template();
}
/***************************************/
/*********   User Interface   **********/
/***************************************/

/************* Panels **************/
add_action( 'wp_ajax_submit_nex_form', 'submit_nex_form');
add_action( 'wp_ajax_nopriv_submit_nex_form', 'submit_nex_form');
function submit_nex_form(){
		global $wpdb;
//ANTI SPAM
if($_POST['company_url']!='')
	die();
	
/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/

	$form_attr = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = '.$_REQUEST['nex_forms_Id']);
	if ( ! function_exists( 'wp_handle_upload' ) ) 
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	
	if(!function_exists('wp_get_current_user')) {
		include(ABSPATH . "wp-includes/pluggable.php"); 
	}
	$time = md5(time());
	$boundary = "==Multipart_Boundary_x{$time}x";
		foreach($_FILES as $key=>$file)
			{
			$uploadedfile = $_FILES[$key];
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile ) {
				//echo "File is valid, and was successfully uploaded.\n";
					if($movefile['file'])
						{
						$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
						$_POST[$key] = get_option('siteurl').'/'.$set_file_name;
						$files[] = $movefile['file'];
						$filenames[] = $file['name'];
						}
			} else {
				//echo "Possible file upload attack!\n";
				//$_POST[$key] = 'Error on uplaod: '.$movefile['error'];
			}
		}


	$user_fields = '<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>'.$form_attr->title.'</title>
</head>
<body>';

	foreach($_POST as $key=>$val)
		{
		if(
		$key!='set_file_ext' &&
		$key!='format_date' &&
		$key!='action' &&
		$key!='set_radio_items' &&
		$key!='change_button_layout' &&
		$key!='set_check_items' &&
		$key!='set_autocomplete_items' &&
		$key!='required' &&
		$key!='xform_submit' &&
		$key!='current_page' &&
		$key!='ajaxurl' &&
		$key!='page_id' &&
		$key!='nex_forms_Id' &&
		$key!='company_url' &&
		$key!='submit'
		)
			{
			$admin_val = '';
			if(is_array($val))
				{
				foreach($val as $thekey=>$value)
					{
					$val .='<span class="fa fa-check"></span> '. $value.' ';
					$admin_val .='- '. $value.' ';
						
					}
				$val = str_replace('Array','',$val);
				}
			else
				{
				$val =$val;
				$admin_val = $val;
				}
				$user_fields .= ''.IZC_Functions::unformat_name($key).' : ';
				$user_fields .= ''.$admin_val.'<br>
';
		
		$user_fields .= '</body></html>';
				
			$insert = $wpdb->insert($wpdb->prefix.'wap_nex_forms_meta',
					array(
						'nex_forms_Id'=>$_REQUEST['nex_forms_Id'],
						'meta_key'=>$key,
						'meta_value'=>$val,
						'time_added' => mktime()
						)
				 );
			}
		}
	
	$from_address 							= ($form_attr->from_address) 						? $form_attr->from_address 												: $default_values['from_address'];
	$from_name 								= ($form_attr->from_name) 							? $form_attr->from_name 												: $default_values['from_name'];
	$mail_to 								= ($form_attr->mail_to) 							? $form_attr->mail_to 													: $default_values['mail_to'];
	$subject 								= ($form_attr->confirmation_mail_subject) 			? str_replace('\\','',$form_attr->confirmation_mail_subject) 			:  str_replace('\\','',$default_values['confirmation_mail_subject']);
	$body 									= ($form_attr->confirmation_mail_body) 				? str_replace('\\','',$form_attr->confirmation_mail_body) 				:  str_replace('\\','',$default_values['confirmation_mail_body']);
	$onscreen 								= ($form_attr->on_screen_confirmation_message) 		? str_replace('\\','',$form_attr->on_screen_confirmation_message) 		:  str_replace('\\','',$default_values['on_screen_confirmation_message']);
	$google_analytics_conversion_code 		= ($form_attr->google_analytics_conversion_code) 	? str_replace('\\','',$form_attr->google_analytics_conversion_code) 	:  str_replace('\\','',$default_values['google_analytics_conversion_code']);


	$pattern = '({{+([A-Za-z 0-9_])+}})';
			
	preg_match_all($pattern, $body, $matches);
		foreach($matches[0] as $match)
			{
			$the_val = '';
			
			if(is_array($_REQUEST[IZC_Functions::format_name($match)]))
				{
				foreach($_REQUEST[IZC_Functions::format_name($match)] as $thekey=>$value)
					{
					$the_val .='<span class="fa fa-check"></span> '. $value.' ';	
					}
				$the_val = str_replace('Array','',$the_val);
				$body = str_replace($match,$the_val,$body);
				$subject = str_replace($match,$the_val,$subject);
				}
			else
				{
				$body = str_replace($match,$_REQUEST[IZC_Functions::format_name($match)],$body);
				$subject = str_replace($match,$_REQUEST[IZC_Functions::format_name($match)],$subject);	
				}
			}

$from = ($_REQUEST[$form_attr->user_email_field]) ? $_REQUEST[$form_attr->user_email_field] : $from_address;  
$subject = $subject;  
$message = $user_fields;  
   
  
// include the from email in the headers  
$headers = "From: $from";  
  
// boundary  
$time = md5(time());  
$boundary = "==Multipart_Boundary_x{$time}x";  
  
// headers used for send attachment with email  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$boundary}\"";  
  
// multipart boundary  
$message = "--{$boundary}\n" . "Content-type: text/html; charset=UTF-8\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";  
$message .= "--{$boundary}\n";  
  
// attach the attachments to the message  
for($x = 0; $x < count($files); $x++){  
    $file = fopen($files[$x],"r");  
    $content = fread($file,filesize($files[$x]));  
    fclose($file);  
    $content = chunk_split(base64_encode($content));  
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" . "Content-Disposition: attachment;\n" . " filename=\"$filenames[$x]\"\n" . "Content-Transfer-Encoding: base64\n\n" . $content . "\n\n";  
    $message .= "--{$boundary}\n";  
}  


		
$mail_to = explode(',',$mail_to);

foreach($mail_to as $email)
	mail($email,$subject,$message,$headers);


$headers2  = 'MIME-Version: 1.0' . "\r\n";
$headers2 .= 'Content-Type: text/html; charset=UTF-8\n\n'. "\r\n";
$headers2 .= 'From: '.$from_name.' <'.$from_address.'>' . "\r\n";
if($_REQUEST[$form_attr->user_email_field])
	mail($_REQUEST[$form_attr->user_email_field],$subject,$body,$headers2);
		
		die();	
	}
function NEXForms_ui_output( $atts , $echo=''){
	
	$config 	= new NEXForms_Config();
	global $wpdb;
	
	//echo '<pre>';
	//print_r($atts);
	if(is_array($atts))
		{
		$defaults = array(
			'id' => '0',
			'open_trigger' => '',
			'type' => 'button',
			'text' => 'open');
		extract( shortcode_atts( $defaults, $atts ) );
		wp_parse_args($atts, $defaults);
		}
	else
		$id=$atts;
		$output .= '<link href="'.WP_PLUGIN_URL . '/nex-forms/css/font-awesome.min.css" rel="stylesheet">';
		$form_attr = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = '.$id);
		
		if($open_trigger=="popup")
			{
			if($type == 'button')
				$output .= '<button class="btn btn-primary open_nex_forms_popup">'.$text.'</button>';
			else
				$output .= '<a href="#" class="open_nex_forms_popup">'.$text.'</a>';
			
			$output .= '<div class="modal fade nex_forms_modal" id="nexForms_popup_'.$form_attr->id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header alert alert-info">
								<h4 class="modal-title" id="myModalLabel">'.$form_attr->title.'</h4>
							  </div>
							  <div class="modal-body">';	
			}
		
		$output .= '<div id="the_plugin_url" style="display:none;">'.WP_PLUGIN_URL.'/nex-forms</div>';
		$output .= '<div id="nex-forms" class="nex-forms">';
			$output .= '<div id="confirmation_page" style="display:none;">'.$form_attr->confirmation_page.'</div>';
			$output .= '<div id="on_form_submmision" style="display:none;">'.$form_attr->on_form_submission.'</div>';
			$output .= '<div class="ui-nex-forms-container" id="ui-nex-forms-container"  >';
			$output .= '<div class="panel-body alert alert-success nex_success_message" style="display:none;">'.str_replace('\\','',$form_attr->on_screen_confirmation_message).'</div>';
				$output .= 	'<form id="" class="submit-nex-form" name="nex_form" action="'.get_option('siteurl').'/wp-admin/admin-ajax.php" method="post" enctype="multipart/form-data">';
					$output .= '<input type="hidden" name="nex_forms_Id" value="'.$id.'">';
					$output .= '<input type="text" name="company_url" value="" placeholder="enter company url" class="form-control req">';			
					$output .=  str_replace('\\','',$form_attr->form_fields);
					$output .= '<div style="clear:both;"></div>';
				$output .= 	'</form>';
			$output .= '</div>';
		$output .= '</div>';
	if($open_trigger=="popup")
			{	
	$output .= '</div>
			</div>
		  </div>
		</div>';
			}
		
	/* UI JS AND STYLE INCLUDES */		
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-form');
	wp_enqueue_style('jquery-ui');
	/* JS */
	//BOOTSTRAP
	wp_enqueue_script('nex-forms-bootstrap.min',  WP_PLUGIN_URL . '/nex-forms/js/bootstrap.min.js');
	//ONLOAD
	wp_enqueue_script('nex-forms-onload', WP_PLUGIN_URL . '/nex-forms/js/nexf-onload-ui.js');
	wp_enqueue_script('nex-forms-fields', WP_PLUGIN_URL . '/nex-forms/js/fields.js');
	//VALIDATIION
	wp_enqueue_script('nex-forms-form-validation', WP_PLUGIN_URL . '/nex-forms/js/nexf-form-validation.js');
	//BOOTSTRAP
	wp_enqueue_style('nex-forms-bootstrap-ui', WP_PLUGIN_URL . '/nex-forms/css/ui-bootstrap.css');
	//NEX-FORMS CUSTOM UI
	wp_enqueue_style('nex-forms-ui', WP_PLUGIN_URL . '/nex-forms/css/ui.css');
	//JQUERY UI
	wp_enqueue_style('nex-forms-jQuery-UI',WP_PLUGIN_URL . '/nex-forms/css/jquery-ui.min.css');
	//COLOR PICKER
	wp_enqueue_style('nex-forms-fields', WP_PLUGIN_URL . '/nex-forms/css/fields.css');
	//LOAD SCRIPTS AND STYLES
	wp_print_scripts();
	wp_print_styles();
	
	if($echo)
		echo $output;
	else
		return $output;	
}

function NEXForms_dashboard_setup() {
	
	wp_add_dashboard_widget('NEXForms_dashboard_widget', 'NEX Forms', 'NEXForms_dashboard_widget');
	
	global $wp_meta_boxes;
	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$wa_form_builder_widget_backup = array('NEXForms_dashboard_widget' => $normal_dashboard['NEXForms_dashboard_widget']);
	unset($normal_dashboard['NEXForms_dashboard_widget']);
	$sorted_dashboard = array_merge($wa_form_builder_widget_backup, $normal_dashboard);
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;	
} 

//add_action('wp_dashboard_setup', 'NEXForms_dashboard_setup' );
add_action('widgets_init', 'NEXForms_widget::register_this_widget');

if(!function_exists('Basix_dashboard_widget'))
	{
	function Basix_dashboard_widget(){
		wp_enqueue_style ('basix-dashboard',WP_PLUGIN_URL . '/nex-forms/css/basix-dashboard.css');
		wp_enqueue_script('basix-dashboard-js',WP_PLUGIN_URL . '/nex-forms/js/basix-dashboard.js');
		global $wpdb;
		$output .= '<div class="dashboard_wrapper">';
			$output .= '<div class="item_logo "><a href="http://codecanyon.net/item/nexforms-the-ultimate-wordpress-form-builder/7103891?ref=Basix"><img width="80" height="80" border="0" title="" src="https://0.s3.envato.com/files/98749807/nex_logo.jpg" data-preview-width="" data-preview-url="https://0.s3.envato.com/files/98749808/cover_image.jpg" data-preview-height="" data-item-name="NEX-Forms - The Ultimate WordPress Form Builder" data-item-cost="33" data-item-category="WordPress / Forms" data-item-author="Basix" class="landscape-image-magnifier preload no_preview" alt="NEX-Forms - The Ultimate WordPress Form Builder - CodeCanyon Item for Sale" data-tooltip="NEX-Forms - The Ultimate WordPress Form Builder"></a><div class="cover_image"><img src="https://0.s3.envato.com/files/98749808/cover_image.jpg" itemprop="image" alt="NEX-Forms - The Ultimate WordPress Form Builder - CodeCanyon Item for Sale"></div></div>';
			$output .= '<div class="item_logo "><a href="http://codecanyon.net/item/nexevents-drag-drop-wordpress-events-calendar/8762860?ref=Basix"><img width="80" height="80" border="0" title="" src="https://0.s3.envato.com/files/103488334/logo.jpg" data-preview-width="" data-preview-url="https://0.s3.envato.com/files/103488337/cover_image.jpg" data-preview-height="" data-item-name="NEX-Events - Drag &amp; Drop WordPress Events Calendar" data-item-cost="32" data-item-category="WordPress / Calendars" data-item-author="Basix" class="landscape-image-magnifier preload no_preview" alt="NEX-Events - Drag &amp;amp; Drop WordPress Events Calendar - CodeCanyon Item for Sale" data-tooltip="NEX-Events - Drag &amp; Drop WordPress Events Calendar"></a><div class="cover_image"><img src="https://0.s3.envato.com/files/103488337/cover_image.jpg" itemprop="image" alt="NEX-Events - Drag &amp; Drop WordPress Events Calendar - CodeCanyon Item for Sale"></div></div>';
			$output .= '<div class="item_logo "><a href="http://codecanyon.net/item/neximages-drag-and-drop-image-mapper/8796428?ref=Basix"><img width="80" height="80" border="0" title="" src="https://0.s3.envato.com/files/103631138/logo.jpg" data-preview-width="" data-preview-url="https://0.s3.envato.com/files/103631139/cover_image.jpg" data-preview-height="" data-item-name="NEX-Images - Drag and Drop Image Mapper" data-item-cost="19" data-item-category="WordPress / Galleries" data-item-author="Basix" class="landscape-image-magnifier preload no_preview" alt="NEX-Images - Drag and Drop Image Mapper - CodeCanyon Item for Sale" data-tooltip="NEX-Images - Drag and Drop Image Mapper"></a><div class="cover_image"><img src="https://0.s3.envato.com/files/103631139/cover_image.jpg" itemprop="image" alt="NEX-Images - Drag and Drop Image Mapper - CodeCanyon Item for Sale"></div></div>';
			$output .= '<div class="item_logo "><div class="item_wrapper"></div></div>';
		$output .= '<div style="clear:both;"></div>';	
		$output .= '</div>';
		
		echo $output;
	}
	
	function Basix_dashboard_setup() {
		
		wp_add_dashboard_widget('basix_dashboard_widget', 'NEX-Range by Basix', 'Basix_dashboard_widget');
		
		global $wp_meta_boxes;
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$wa_form_builder_widget_backup = array('basix_dashboard_widget' => $normal_dashboard['basix_dashboard_widget']);
		unset($normal_dashboard['basix_dashboard_widget']);
		$sorted_dashboard = array_merge($wa_form_builder_widget_backup, $normal_dashboard);
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;	
	} 

	add_action('wp_dashboard_setup', 'Basix_dashboard_setup' );
	}
?>