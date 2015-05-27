<?php
/*
Plugin Name: WPFW - Menus Management
Plugin URI: http://www.wpfw.net/wordpress-duplicate-menu
Description: Duplicate, Export and Import WordPress menus.
Version: 0.0.1
Author: Catalin Nita
Author URI: http://www.wpfw.net
*/
include('settings.php');
include('functions.php');

add_meta_box( 'wpfw-duplicate-menu', 'Duplicate Menu', 'wpfw_duplicate_menu', 'nav-menus', 'side', 'high' );

function wpfw_duplicate_menu() {
	global $wpdb;
	
	if ($_POST['duplicate'] == 1) {
		
		$old_taxonomy = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_taxonomy WHERE term_id = ".$_POST['wpfw_menu_id']);
		
		// insert menu
		$name = $_POST['wpfw_menu_name'];
		$slug = strtolower(str_replace(" ","-", $name));
		
		$count = $old_taxonomy[0]->count;

		// terms
		$wpdb->query("INSERT INTO ".$wpdb->prefix."terms (name, slug) 
										VALUES ('".$name."', '".$slug."')");		
		$new_menu_id = $wpdb->insert_id;										
		
		// terms taxonomy				
		$wpdb->query("INSERT INTO ".$wpdb->prefix."term_taxonomy (term_id, taxonomy, count)
										VALUES ('".$wpdb->insert_id."', 'nav_menu', ".$count.")");				
		$tti = $wpdb->insert_id;

		// get posts
		$posts = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE term_taxonomy_id = ".$old_taxonomy[0]->term_taxonomy_id);
		
		$prev_post_id = array();
		foreach($posts as $post) {
			// duplicate posts
			$op = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$post->object_id);
			$opm = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$post->object_id);

			
			$wpdb->query("INSERT INTO ".$wpdb->prefix."posts 
					(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, menu_order, post_type, 	post_mime_type, comment_count) 
					VALUES 
					(".$op[0]->post_author.", '".$op[0]->post_date."', '".$op[0]->post_date_gmt."', '".$op[0]->post_content."', '".$op[0]->post_title."', 
					'".$op[0]->post_excerpt."', '".$op[0]->post_status."', '".$op[0]->comment_status."', '".$op[0]->ping_status."', '".$op[0]->post_password."', '".$op[0]->to_ping."', '".$op[0]->pinged."', '".$op[0]->post_modified."', '".$op[0]->post_modified_gmt."', '".$op[0]->post_content_filtered."', ".$op[0]->post_parent.", ".$op[0]->menu_order.", '".$op[0]->post_type."', '".$op[0]->post_mime_type."', ".$op[0]->comment_count.")");
						
			$new_post_id = $wpdb->insert_id;
			$prev_post_id[$post->object_id] = $new_post_id;
			
			$wpdb->query("UPDATE ".$wpdb->prefix."posts SET
											post_name = '".$new_post_id."',
											guid = '".str_replace("?p=".$op[0]->ID, "?p=".$new_post_id, $op[0]->guid)."'
												WHERE ID = ".$new_post_id);

			foreach($opm as $m) {
				
						$wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta 
												(post_id, meta_key, meta_value)
												 VALUES 
												 (".$new_post_id.", '".$m->meta_key."', '".$m->meta_value."')");												 
						
			}
			
			// duplicate term relationship
			
			$wpdb->query("INSERT INTO ".$wpdb->prefix."term_relationships
											(object_id, term_taxonomy_id)
											VALUES 
											(".$new_post_id.", ".$tti.")");
		
		}
		
		foreach($posts as $post) {
			
			$opm = $wpdb->get_results("SELECT meta_value FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$prev_post_id[$post->object_id]." AND meta_key = '_menu_item_menu_item_parent'");
		
			$wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET
											meta_value = '".$prev_post_id[$opm[0]->meta_value]."' 
												WHERE post_id = ".$prev_post_id[$post->object_id]."
													AND meta_key = '_menu_item_menu_item_parent'");
													
							
		
		}									
		wp_redirect(get_bloginfo("wpurl").'/wp-admin/nav-menus.php?action=edit&menu='.$new_menu_id);
	}
	
	
	
	$menus = $wpdb->get_results("SELECT ".$wpdb->prefix."terms.* 
																	FROM ".$wpdb->prefix."terms, ".$wpdb->prefix."term_taxonomy 
																			WHERE ".$wpdb->prefix."term_taxonomy.taxonomy = 'nav_menu'
																				AND ".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_taxonomy.term_id");
	
	echo '<p class="howto">Use this box for duplicating any of your existing menus.</p>';
	echo '<form name="duplicate_menu" action="nav-menus.php" method="post">';
	echo '<input type="hidden" name="duplicate" value="1">';
	echo '<p>';
	echo '<label class="howto" for="wpfw_menu_id">';
	echo '<span>Menu you want to duplicate</span>';
	echo '<select id="wpfw_menu_id" name="wpfw_menu_id" style="width: 100%;">';
	foreach($menus as $menu) {
		echo '<option value="'.$menu->term_id.'">'.$menu->name.'</option>';
	}
	echo '</select>';
	echo '</label>';	
	echo '</p>';
	
	echo '<p>';
	echo '<label class="howto" for="wpfw_menu_name">';
	echo '<span>New menu name</span>';	
	echo '<input type="text" value="" id="wpfw_menu_name" name="wpfw_menu_name" style="width: 100%; float: none;" />';	
	echo '</label>';	
	echo '</p>';
	
	echo '<p class="button-controls">';
	echo '<input type="submit" value="Duplicate" class="button button-primary right" />';	
	echo '</p>';
	echo '</form>';
	
}

add_meta_box( 'wpfw-export-menu', 'Export Menu', 'wpfw_export_menu', 'nav-menus', 'side', 'high' );

if ( isset($_POST['export']) && $_POST['export'] == 1 ) {
		
		
		$old_taxonomy = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_taxonomy WHERE term_id = ".$_POST['wpfw_menu_id']);
		$name = $wpdb->get_results("SELECT name FROM ".$wpdb->prefix."terms WHERE term_id = ".$_POST['wpfw_menu_id']);
		
		// insert menu
		$slug = strtolower(str_replace(" ","-", $name[0]->name));
		$count = $old_taxonomy[0]->count;

		$export_string = '
		// FILE-INFO-START
		// menu-id: '.$slug.';
		// data-type: sql;
		// FILE-INFO-END
		
		// INSERT MENU
		$wpdb->query("INSERT INTO ".$wpdb->prefix."terms (name, slug) 
										VALUES (\''.$name[0]->name.'\', \''.$slug.'\')");		
		$new_menu_id = $wpdb->insert_id;				

		$wpdb->query("INSERT INTO ".$wpdb->prefix."term_taxonomy (term_id, taxonomy, count)
										VALUES (".$new_menu_id.", \'nav_menu\', '.$count.')");				
		$tti = $wpdb->insert_id;
		';
		
		// get all posts attached to the menu
		$posts = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE term_taxonomy_id = ".$old_taxonomy[0]->term_taxonomy_id);
		
		$export_string .= '
			$prev_post_id = array();
		';
		foreach($posts as $post) {
			// duplicate posts
			$op = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$post->object_id);
			$type = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$post->object_id." AND meta_key = '_menu_item_type'");
			$opm = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$post->object_id);
			
			
			
			
			$export_string .= '
			$wpdb->query("INSERT INTO ".$wpdb->prefix."posts 
					(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, menu_order, post_type, 	post_mime_type, comment_count) 
					VALUES 
					('.$op[0]->post_author.', \''.$op[0]->post_date.'\', \''.$op[0]->post_date_gmt.'\', \''.$op[0]->post_content.'\', \''.$op[0]->post_title.'\', \''.$op[0]->post_excerpt.'\', \''.$op[0]->post_status.'\', \''.$op[0]->comment_status.'\', \''.$op[0]->ping_status.'\', \''.$op[0]->post_password.'\', \''.$op[0]->post_name.'\', \''.$op[0]->to_ping.'\', \''.$op[0]->pinged.'\', \''.$op[0]->post_modified.'\', \''.$op[0]->post_modified_gmt.'\', \''.$op[0]->post_content_filtered.'\', '.$op[0]->post_parent.', '.$op[0]->menu_order.', \''.$op[0]->post_type.'\', \''.$op[0]->post_mime_type.'\', '.$op[0]->comment_count.')");
						
			$new_post_id = $wpdb->insert_id;
			
			$prev_post_id['.$post->object_id.'] = $new_post_id;
			$wpdb->query("UPDATE ".$wpdb->prefix."posts SET
											guid = \'".str_replace("?p='.$op[0]->ID.'", "?p=".$new_post_id, "'.$op[0]->guid.'")."\'
												WHERE ID = ".$new_post_id);
			';


			
			foreach($opm as $m) {
							
						$export_string .= '
						$wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta 
												(post_id, meta_key, meta_value)
												 VALUES 
												 (".$new_post_id.", \''.$m->meta_key.'\', \''.str_replace('"', '\"', $m->meta_value).'\')");	
												 
						
						';
						if($m->meta_key == '_menu_item_object_id') {
							$export_string .= '$post_meta_id = $wpdb->insert_id;
							';
						}
						
						
						if ($m->meta_key == '_menu_item_object_id' && $m->meta_value != $post->object_id) {
							
							if ($type[0]->meta_value == 'custom' || $type[0]->meta_value == 'post_type') {
								
								$p = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$m->meta_value);
								$pm = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$m->meta_value);
								
								$export_string .= '
								$check = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_name = \''.$p[0]->post_name.'\' AND post_type = \''.$p[0]->post_type.'\'");
								
								if (!$check[0]->ID) {
								$wpdb->query("INSERT INTO ".$wpdb->prefix."posts 
										(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, menu_order, post_type, 	post_mime_type, comment_count) 
										VALUES 
										('.$p[0]->post_author.', \''.$p[0]->post_date.'\', \''.$p[0]->post_date_gmt.'\', \''.str_replace("'", "\'", str_replace('"', '\"', $p[0]->post_content)).'\', \''.$p[0]->post_title.'\', \''.$p[0]->post_excerpt.'\', \''.$p[0]->post_status.'\', \''.$p[0]->comment_status.'\', \''.$p[0]->ping_status.'\', \''.$p[0]->post_password.'\', \''.$p[0]->post_name.'\', \''.$p[0]->to_ping.'\', \''.$p[0]->pinged.'\', \''.$p[0]->post_modified.'\', \''.$p[0]->post_modified_gmt.'\', \''.$p[0]->post_content_filtered.'\', '.$p[0]->post_parent.', '.$p[0]->menu_order.', \''.$p[0]->post_type.'\', \''.$p[0]->post_mime_type.'\', '.$op[0]->comment_count.')");
											
								$new_post_id_p = $wpdb->insert_id;
								$prev_post_id['.$m->meta_value.'] = $new_post_id_p;
								}
								else {
									$new_post_id_p = $check[0]->ID;
									$prev_post_id['.$m->meta_value.'] = $new_post_id_p;
								}
								
								$wpdb->query("UPDATE ".$wpdb->prefix."posts SET
																guid = \'".str_replace("?p='.$p[0]->ID.'", "?p=".$new_post_id_p, "'.$p[0]->guid.'")."\'
																	WHERE ID = ".$new_post_id_p);
																	
								$wpdb->query("UPDATE ".$wpdb->prefix."posts SET
																post_name = \'".$prev_post_id['.$post->object_id.']."\'
																	WHERE post_name = \''.$op[0]->post_name.'\'");																
								';
								
								foreach($pm as $pme) {
									
									$export_string .= '
									if (!$check[0]->ID) {
									$cpme = $wpdb->get_results("SELECT meta_id FROM ".$wpdb->prefix."postmeta
																								WHERE post_id = ".$new_post_id_p."
																									AND meta_key = \''.$pme->meta_key.'\'");
									if(!$cpme[0]->meta_id) {
										
										$wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta (post_id, meta_key, meta_value)
																				VALUES (".$new_post_id_p.", \''.$pme->meta_key.'\', \''.$pme->meta_value.'\')");	
																				
									}
									}
									';
									
								}
						}
						
						if ($type[0]->meta_value == 'taxonomy') {
			
							$ts = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."terms WHERE term_id = ".$m->meta_value);
							$tt = $wpdb->get_results("SELECT taxonomy FROM ".$wpdb->prefix."term_taxonomy WHERE term_id = ".$m->meta_value);
							
							$export_string .= '
								$check = $wpdb->get_results("SELECT ".$wpdb->prefix."terms.term_id FROM ".$wpdb->prefix."terms, ".$wpdb->prefix."term_taxonomy 
																								WHERE ".$wpdb->prefix."terms.slug = \''.$ts[0]->slug.'\' 
																									AND ".$wpdb->prefix."term_taxonomy.taxonomy = \''.$tt[0]->taxonomy.'\'
																										AND ".$wpdb->prefix."term_taxonomy.term_id = ".$wpdb->prefix."terms.term_id");			
																										
								if (!$check[0]->term_id) {
									$wpdb->query("INSERT INTO ".$wpdb->prefix."terms (name, slug) VALUES (\''.$ts[0]->name.'\', \''.$ts[0]->slug.'\')");
									$new_term_id = $wpdb->insert_id;							 
									$wpdb->query("INSERT INTO ".$wpdb->prefix."term_taxonomy (term_id, taxonomy) VALUES (".$new_term_id.", \''.$tt[0]->taxonomy.'\')");
									
									$wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET
												meta_value = \'".$new_term_id."\' 
													WHERE meta_id = ".$post_meta_id." 
														AND meta_key = \'_menu_item_object_id\'");
														
																							
								}
								else {
									$wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET
												meta_value = \'".$check[0]->term_id."\' 
													WHERE meta_id = ".$post_meta_id." 
														AND meta_key = \'_menu_item_object_id\'");
													
								}
																																					
							';
							
						}
					}
						
			}
			
			// duplicate term relationship
			
			$export_string .= '
			if($new_post_id) {
			$wpdb->query("INSERT INTO ".$wpdb->prefix."term_relationships
											(object_id, term_taxonomy_id)
											VALUES 
											(".$new_post_id.", ".$tti.")");
			}											
			';
			
			
			$attachments = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_parent = ".$post->object_id." AND post_type = 'attachment'");
			
			foreach($attachments as $att) {
				
				$export_string .= '
					$wpdb->query("INSERT INTO ".$wpdb->prefix."posts
					(post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, 	post_mime_type, comment_count) 
						VALUES 
							('.$att->post_author.', \''.$att->post_date.'\', \''.$att->post_date_gmt.'\', \''.$att->post_content.'\', \''.$att->post_title.'\', \''.$att->post_excerpt.'\', \''.$att->post_status.'\', \''.$att->comment_status.'\', \''.$att->ping_status.'\', \''.$att->post_password.'\', \''.$att->post_name.'\', \''.$att->to_ping.'\', \''.$att->pinged.'\', \''.$att->post_modified.'\', \''.$att->post_modified_gmt.'\', \''.$att->post_content_filtered.'\', ".$new_post_id.", \''.$att->guid.'\', '.$att->menu_order.', \''.$att->post_type.'\', \''.$att->post_mime_type.'\', '.$att->comment_count.')");										
							$new_att_id = $wpdb->insert_id;
							
					$wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET
												meta_value = \'".$new_att_id."\' 
													WHERE post_id = ".$new_post_id." 
														AND meta_key = \'_thumbnail_id\'");						
				';
				
				$attachmentmeta  = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$att->ID);
				foreach($attachmentmeta as $attm) {
					$export_string .= '
						$wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta 
												(post_id, meta_key, meta_value)
												 VALUES 
												 (".$new_att_id.", \''.$attm->meta_key.'\', \''.str_replace('"', '\"', $attm->meta_value).'\')");						
						';
				}
				
				
			}
		
		}
		
		
		foreach($posts as $post) {
			$type = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$post->object_id." AND meta_key = '_menu_item_type'");
			
			if($type[0]->meta_value != 'taxonomy') {
			$opm = $wpdb->get_results("SELECT meta_value FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$post->object_id." AND meta_key = '_menu_item_menu_item_parent'");
		
			if ($opm[0]->meta_value) {
		
				$export_string .= '
				$wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET
												meta_value = \'".$prev_post_id['.$opm[0]->meta_value.']."\' 
													WHERE post_id = ".$prev_post_id['.$post->object_id.']." 
														AND meta_key = \'_menu_item_menu_item_parent\'");
														
				';
			}
			
				$opm1 = $wpdb->get_results("SELECT meta_value FROM ".$wpdb->prefix."postmeta WHERE post_id = ".$post->object_id." AND meta_key = '_menu_item_object_id'");													
			if ($opm1[0]->meta_value) {	
				$export_string .= '
				$wpdb->query("UPDATE ".$wpdb->prefix."postmeta SET
												meta_value = \'".$prev_post_id['.$opm1[0]->meta_value.']."\' 
													WHERE post_id = ".$prev_post_id['.$post->object_id.']." 
														AND meta_key = \'_menu_item_object_id\'");
				';			
													
			}
			}
		}	
		
		

		
		// taxonomies
		
		$export_string .= '
			wp_redirect(get_bloginfo("wpurl")."/wp-admin/nav-menus.php?action=edit&menu=".$tti);								
		';
		
		$export_string = str_replace(get_bloginfo("wpurl"), "#wpurl#", $export_string);
		
		// required for IE, otherwise Content-Disposition may be ignored
    if(ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');
    
    header('Content-Type: application/force-download');
    //header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="'.get_bloginfo('name').'-'.$name[0]->name.'.txt"');
    header("Content-Transfer-Encoding: binary");
    header('Accept-Ranges: bytes');
		
		print($export_string);
		
		exit;
	}

function wpfw_export_menu() {
	global $wpdb;
	
	
	
	
	$menus = $wpdb->get_results("SELECT ".$wpdb->prefix."terms.* 
																	FROM ".$wpdb->prefix."terms, ".$wpdb->prefix."term_taxonomy 
																			WHERE ".$wpdb->prefix."term_taxonomy.taxonomy = 'nav_menu'
																				AND ".$wpdb->prefix."terms.term_id = ".$wpdb->prefix."term_taxonomy.term_id");
	
	echo '<p class="howto">Use this box for getting the export file for a menu.</p>';
	echo '<form name="duplicate_menu" action="nav-menus.php" method="post">';
	echo '<input type="hidden" name="export" value="1">';
	echo '<p>';
	echo '<label class="howto" for="wpfw_menu_id">';
	echo '<span>Menu you want to export</span>';
	echo '<select id="wpfw_menu_id" name="wpfw_menu_id" style="width: 100%;">';
	foreach($menus as $menu) {
		echo '<option value="'.$menu->term_id.'">'.$menu->name.'</option>';
	}
	echo '</select>';
	echo '</label>';	
	echo '</p>';
	
	echo '<p class="button-controls">';
	echo '<input type="submit" value="Export" class="button button-primary right" />';	
	echo '</p>';
	echo '</form>';
	
}

add_meta_box( 'wpfw-import-menu', 'Import Menu', 'wpfw_import_menu', 'nav-menus', 'side', 'high' );

function wpfw_get_inside_content($startTag='', $endTag='', $out, $delimiter='#') {

	$regex = $delimiter . preg_quote($startTag, $delimiter) 
	                    . '(.*?)' 
	                    . preg_quote($endTag, $delimiter) 
	                    . $delimiter 
	                    . 's';
	preg_match($regex,$out,$matches);
	return $matches[1];
	
}

function wpfw_menu_file_info($file) {
	
		$info = wpfw_get_inside_content('// FILE-INFO-START', '// FILE-INFO-END', file_get_contents($file));
		$info = str_replace("// ", "", $info);
		$info = str_replace("//", "", $info);
		$info = str_replace("\n", "", $info);
		$info = str_replace("\r", "", $info);
		$info = str_replace("\t", "", $info);
		$info = str_replace(" ", "", $info);
		$info = explode(";", $info);
		
		$ret = array();
		foreach($info as $i) {
			$var = explode(":", $i);
			if($var[0] && $var[1]) {
				$ret[$var[0]] = $var[1];
			}
		}
		return $ret;
	
}

function wpfw_import_menu($file='', $menu_location='') {
	global $wpdb;
	
	if ($_POST['import'] == 1 && !isset($file)) {
		if ($_FILES['wpfw_menu_file']['error'] == UPLOAD_ERR_OK               
		      && is_uploaded_file($_FILES['wpfw_menu_file']['tmp_name'])) { 

		  ini_set('max_execution_time', '240');
		  set_time_limit(240);
		  eval(str_replace("#wpurl#", get_bloginfo("wpurl"), file_get_contents($_FILES['wpfw_menu_file']['tmp_name']))); 
		}		
	}
	if(isset($file)) {
		
		ini_set('max_execution_time', '240');
		set_time_limit(240);
		
		$fileinfo = wpfw_menu_file_info($file);		
		eval(str_replace("wp_redirect", "//wp_redirect", str_replace("#wpurl#", get_bloginfo("wpurl"), file_get_contents($file))));
		if(isset($menu_location)) {
			wpfw_set_menu_to_location($menu_location, $fileinfo['menu-id']);
		}
		
	}
	if(!isset($file)) {
		echo '<p class="howto">Please upload just files exported with this plugin.</p>';
		echo '<form name="duplicate_menu" action="nav-menus.php" method="post" enctype="multipart/form-data">';
		echo '<input type="hidden" name="import" value="1">';
		echo '<p>';
		echo '<label class="howto" for="wpfw_menu_id">';
		echo '<span>Upload file</span>';
		echo '<input type="file" id="wpfw_menu_file" name="wpfw_menu_file" style="width: 100%;">';
		echo '</label>';	
		echo '</p>';
		
		echo '<p class="button-controls">';
		echo '<input type="submit" value="Import" class="button button-primary right" />';	
		echo '</p>';
		echo '</form>';
	}
	
}
function wpfw_set_menu_to_location($location, $menuslug) {
	$menu = get_term_by('slug', $menuslug, 'nav_menu');
	$locations = get_theme_mod('nav_menu_locations');
	$locations[$location] = $menu->term_id;
	set_theme_mod( 'nav_menu_locations', $locations );	
	
}

if( isset($_GET['u']) && $_GET['u'] == 1 ) {
	wpfw_import_menu(get_template_directory()."/dummy_content/WPFW Plugins - Test menu.txt", 'primary');	
}

?>