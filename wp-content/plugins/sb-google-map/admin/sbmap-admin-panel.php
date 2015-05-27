<?php
/*	 Plugin setting panel
 *   Version: 1.0
 *   Author: SB Themes
 *   Profile: http://codecanyon.net/user/sbthemes?ref=sbthemes
 */

/**
 * Adding jquery ui dialog scripts
 */
function sb_admin_enqueue_scripts($hook) {
	//Prevent adding scripts if page is not sb map settings page
	$add_scripts_pages = array('toplevel_page_sb-google-map', 'sb-google-maps_page_sb-google-map-form');
	if( !in_array($hook ,$add_scripts_pages) ) {
        return;
	}
	
	global $wpdb;
	$table = $wpdb->prefix.'sb_google_map';
	
	$map_language = 'en';
	if(isset($_GET['id'])) {
		$map_id = $_GET['id'];
		$get_map_language = $wpdb->get_row("select miscellaneous from $table where map_id = '".$map_id."'");
		if($get_map_language) {
			$map_language = @unserialize($get_map_language->miscellaneous);
			$map_language = $map_language['language'];
		}
	}
	
	wp_register_script( 'googlemapapi', (is_ssl() ? 'https://' :'http://').'maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,weather,panoramio&language='.$map_language, array(), '', true );
	wp_enqueue_script('googlemapapi');
	
	wp_enqueue_style('wp-jquery-ui-dialog');																							//Adding modal dialog style
	wp_register_style('sbmap-admin-style', plugins_url('sb-google-map/assets/css/sb-admin-map.css'), array(), SB_PLUGIN_VERSION);		//Registering admin setting screen style
	wp_enqueue_style('sbmap-admin-style');																								//Adding admin setting screen style
	
	wp_enqueue_script('jquery-ui-dialog');																								//Adding modal dialog script
	wp_register_script('sbmap-admin', plugins_url('sb-google-map/assets/js/sb-admin-map.js'), array(), SB_PLUGIN_VERSION, true);		//Registering admin setting screen script
	wp_localize_script('sbmap-admin', 'sbjsobj', array('sb_preview_dir_url'	=>	SB_PREVIEW_DIR_URL, 'homeurl' => home_url()));
	wp_enqueue_script('sbmap-admin');
	
	wp_register_script( 'sbmap', plugins_url('sb-google-map/assets/js/sb-map.js'), array('googlemapapi'), SB_PLUGIN_VERSION, true );
	wp_enqueue_script('sbmap');
	
}
add_action( 'admin_enqueue_scripts', 'sb_admin_enqueue_scripts');

/**
 * Generate map form
 */
function sb_map_render_form() {
	global $wpdb;
	$table = $wpdb->prefix.'sb_google_map';
	$map_id = $preview = false;
	
	if(isset($_GET['id'])) {
		$map_id = $_GET['id'];
	}
	
	if(isset($_GET['preview'])) {
		$preview = $_GET['preview'];
	}
	
	$success = $error = false;	//Set message variables to false
	if(isset($_GET['success'])) {
		$success = $_GET['success'];
	}
	if(isset($_GET['error'])) {
		$error = $_GET['error'];
	}
	
	//Save Map Nonce Key
	$nonce_key = 'sb-google-map-save-map';
	
	//Map Control Positions
	$sb_control_positions = array('TOP_CENTER', 'TOP_LEFT', 'TOP_RIGHT', 'LEFT_TOP', 'RIGHT_TOP', 'LEFT_CENTER', 'RIGHT_CENTER', 'LEFT_BOTTOM', 'RIGHT_BOTTOM', 'BOTTOM_CENTER', 'BOTTOM_LEFT', 'BOTTOM_RIGHT');
	
	//Default settings
	
	$map_title = '';
	
	$markers = array(
		array(
			'address'						=>	'Central Business District, Melbourne - Australia',
			'textfordirectionslink'			=>	'view directions',		//Any string. Leave blank to disable direction link
			'icon'							=>	'blue-1',
			'animation'						=>	'NONE',					//NONE | DROP, BOUNCE
			'infowindow'					=>	'no',
			'content'						=>	'Central Business District, Melbourne - Australia'
		)
	);
	
	$width_height = array(
		'width'							=>	'100', 					// Any value In px or %
		'widthtype'						=>	'%',					// Value symbol % or px
		'height'						=>	'400', 					// Any value In px or %
		'heighttype'					=>	'px',					// Value symbol % or px
	);
	
	$map_styles = 'style-1';				//style-1 to style-103
	
	$zoom_settings = array(
		'zoom' 							=> 	14,						// 1 to 21
		'zoomcontrol'					=>	'yes',					//Boolean
		'zoomcontrol_position'			=>	'TOP_LEFT',
		'zoomcontrolstyle'				=>	'DEFAULT',				//DEFAULT | SMALL | LARGE
		'draggable'						=>	'yes',					//Boolean
		'scrollwheel'					=>	'yes',					//Boolean
		'centerpoint'					=>	array(
			'address'	=>	''										//Center point of map (Any address or (latitude, longitude))
		)
	);
	
	$map_controls = array(
		'pancontrol'					=>	'yes',					//Boolean
		'pancontrol_position'			=>	'TOP_LEFT',
		'scalecontrol'					=>	'yes',					//Boolean
		'streetviewcontrol'				=>	'yes',					//Boolean
		'streetviewcontrol_position'	=>	'TOP_LEFT',
		'maptypecontrol'				=>	'yes',					//Boolean
		'maptypecontrol_position'		=>	'TOP_RIGHT',
		'maptype'						=>	'ROADMAP',				//ROADMAP | SATELLITE | HYBRID | TERRAIN
		'maptypecontrolstyle'			=>	'DEFAULT',				//DEFAULT, HORIZONTAL_BAR, DROPDOWN_MENU
		'overviewmapcontrolvisible'		=>	'no',					//Boolean
		'overviewmapcontrol'			=>	'yes'					//Boolean
	);
	
	$nearest_places = array(
		'searchtype'					=>	'disabled',				//Supported types: https://developers.google.com/places/documentation/supported_types
		'searchradius'					=>	500,					//Any integer value between 0 to 50000
		'searchiconanimation'			=>	'NONE',					//NONE | DROP, BOUNCE
		'searchdirectiontext'			=>	'view directions'		//Any string. Leave blank to disable direction link
	);
	
	$map_layers = array(
		'weather'						=>	'no',					//Boolean
		'traffic'						=>	'no',					//Boolean
		'transit'						=>	'no',					//Boolean
		'bicycle'						=>	'no',					//Boolean
		'panoramio'						=>	'no',					//Boolean
	);
	
	$miscellaneous = array(
		'reloadonresize'				=>	'no',					//Boolean //Centered map on window resize
		'language'						=>	'en',					//http://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
	);
	
	
	//Saving Map
	if(isset($_POST['save_google_map_details'])) {
		
		$nonce = $_POST['sb_google_map_save_map'];
		
		if(!wp_verify_nonce($nonce, $nonce_key)) {
			wp_die('Invalid nonce...');
		}
		
		$map_title = sanitize_text_field($_POST['map_title']);
		
		//Markers Parameters
		$address 			= $_POST['address'];
		$textfordirlink		= $_POST['textfordirectionslink'];
		$icon				= $_POST['icon'];
		$animation			= $_POST['animation'];
		$infowindow			= $_POST['infowindow'];
		$content			= $_POST['content'];
		
		$markers = array();
		
		$total_markers = count($address);
		for($i = 0; $i < $total_markers; $i++) {
			
			list($latitude,$longitude) = sscanf("$address[$i]", "%f,%f");
			if($latitude == '' || $longitude == '') {
				$location = json_decode(sb_get_lat_lng_from_address($address[$i]));		//Get latitude, longitude from geocode api
				if(strtoupper($location->status) == 'OK') {
					$latitude = $location->results[0]->geometry->location->lat;
					$longitude = $location->results[0]->geometry->location->lng;
				} else {
					$latitude = 0;
					$longitude = 0;
				}
			}
			
			$markers[] = array(
				'address'					=>	sanitize_text_field($address[$i]),
				'latitude'					=>	$latitude,
				'longitude'					=>	$longitude,
				'textfordirectionslink'		=>	sanitize_text_field($textfordirlink[$i]),
				'icon'						=>	sanitize_text_field($icon[$i]),
				'animation'					=>	sanitize_text_field($animation[$i]),
				'infowindow'				=>	sanitize_text_field($infowindow[$i]),
				'content'					=>	$content[$i]
			);
		}
		
		//Width & Height Parameters
		$width 			= sanitize_text_field($_POST['width']);
		$width_type 	= $_POST['width_type'];
		$height 		= sanitize_text_field($_POST['height']);
		$height_type 	= sanitize_text_field($_POST['height_type']);
		
		$width_height = array(
			'width'							=>	$width,
			'widthtype'						=>	$width_type,
			'height'						=>	$height,
			'heighttype'					=>	$height_type
		);
		
		//Map Style
		$map_styles = $_POST['mapstyles'];
		
		//Zoom Settings
		$zoom 					= $_POST['zoom'];
		$zoomcontrol			= $_POST['zoomcontrol'];
		$zoomcontrol_position	= $_POST['zoomcontrol_position'];
		$zoomcontrolstyle		= $_POST['zoomcontrolstyle'];
		$draggable				= $_POST['draggable'];
		$scrollwheel			= $_POST['scrollwheel'];
		$centerpoint			= $_POST['centerpoint'];
		
		list($centerpoint_latitude,$centerpoint_longitude) = sscanf("$centerpoint", "%f,%f");
		if($centerpoint_latitude == '' || $centerpoint_longitude == '') {
			$centerpoint_location = json_decode(sb_get_lat_lng_from_address($centerpoint));		//Get latitude, longitude from geocode api
			if(strtoupper($centerpoint_location->status) == 'OK') {
				$centerpoint_latitude = $centerpoint_location->results[0]->geometry->location->lat;
				$centerpoint_longitude = $centerpoint_location->results[0]->geometry->location->lng;
			} else {
				$centerpoint_latitude = 0;
				$centerpoint_longitude = 0;
			}
		}
		
		$zoom_settings = array(
			'zoom' 							=> 	$zoom,
			'zoomcontrol'					=>	$zoomcontrol,
			'zoomcontrol_position'			=>	$zoomcontrol_position,
			'zoomcontrolstyle'				=>	$zoomcontrolstyle,
			'draggable'						=>	$draggable,
			'scrollwheel'					=>	$scrollwheel,
			'centerpoint'					=>	array(
				'address'					=>	sanitize_text_field($centerpoint),
				'latitude'					=>	$centerpoint_latitude,
				'longitude'					=>	$centerpoint_longitude
			)
		);
		
		//Map Controls
		$pancontrol 					= $_POST['pancontrol'];
		$pancontrol_position			= $_POST['pancontrol_position'];
		$scalecontrol 					= $_POST['scalecontrol'];
		$streetviewcontrol 				= $_POST['streetviewcontrol'];
		$streetviewcontrol_position		= $_POST['streetviewcontrol_position'];
		$maptypecontrol 				= $_POST['maptypecontrol'];
		$maptypecontrol_position		= $_POST['maptypecontrol_position'];
		$maptype 						= $_POST['maptype'];
		$maptypecontrolstyle			= $_POST['maptypecontrolstyle'];
		$overviewmapcontrolvisible 		= $_POST['overviewmapcontrolvisible'];
		$overviewmapcontrol				= $_POST['overviewmapcontrol'];
		
		$map_controls = array(
			'pancontrol'					=>	$pancontrol,
			'pancontrol_position'			=>	$pancontrol_position,
			'scalecontrol'					=>	$scalecontrol,
			'streetviewcontrol'				=>	$streetviewcontrol,
			'streetviewcontrol_position'	=>	$streetviewcontrol_position,
			'maptypecontrol'				=>	$maptypecontrol,
			'maptypecontrol_position'		=>	$maptypecontrol_position,
			'maptype'						=>	$maptype,
			'maptypecontrolstyle'			=>	$maptypecontrolstyle,
			'overviewmapcontrolvisible'		=>	$overviewmapcontrolvisible,
			'overviewmapcontrol'			=>	$overviewmapcontrol
		);
		
		//Nearest Places
		$searchtype 				= $_POST['searchtype'];
		$searchradius 				= sanitize_text_field($_POST['searchradius']);
		$searchiconanimation		= $_POST['searchiconanimation'];
		$searchdirectiontext		= sanitize_text_field($_POST['searchdirectiontext']);
		
		$nearest_places = array(
			'searchtype'					=>	$searchtype,
			'searchradius'					=>	$searchradius,
			'searchiconanimation'			=>	$searchiconanimation,
			'searchdirectiontext'			=>	$searchdirectiontext
		);
		
		//Map Layers
		$weather = $_POST['weather'];
		$traffic = $_POST['traffic'];
		$transit = $_POST['transit'];
		$bicycle = $_POST['bicycle'];
		$panoramio = $_POST['panoramio'];
		
		$map_layers = array(
			'weather'			=>	$weather,
			'traffic'			=>	$traffic,
			'transit'			=>	$transit,
			'bicycle'			=>	$bicycle,
			'panoramio'			=>	$panoramio
		);
		
		//Miscellaneous Settings
		$reloadonresize = $_POST['reloadonresize'];
		$language 		= $_POST['language'];
		
		$miscellaneous = array(
			'reloadonresize'	=>	$reloadonresize,
			'language'			=>	$language
		);
		
		if($map_id) {
			$wpdb->update(
				$table,
				array(
					  'map_title'			=>	$map_title,
					  'markers'				=>	serialize($markers),
					  'width_height'		=>	serialize($width_height),
					  'map_styles'			=>	$map_styles,
					  'zoom_settings'		=>	serialize($zoom_settings),
					  'map_controls'		=>	serialize($map_controls),
					  'nearest_places'		=>	serialize($nearest_places),
					  'map_layers'			=>	serialize($map_layers),
					  'miscellaneous'		=>	serialize($miscellaneous)
				),
				array('map_id' => $map_id),
				array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
				array('%d')
			);
			$save_map = true;
		} else {
			$save_map = $wpdb->insert(
				$table,
				array(
					  'map_title'			=>	$map_title,
					  'markers'				=>	serialize($markers),
					  'width_height'		=>	serialize($width_height),
					  'map_styles'			=>	$map_styles,
					  'zoom_settings'		=>	serialize($zoom_settings),
					  'map_controls'		=>	serialize($map_controls),
					  'nearest_places'		=>	serialize($nearest_places),
					  'map_layers'			=>	serialize($map_layers),
					  'miscellaneous'		=>	serialize($miscellaneous)
				),
				array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
			);
			$map_id = $wpdb->insert_id;
		}
		if($save_map) {
			wp_redirect(SB_EDIT_PAGE_URL.'&id='.$map_id.'&success=1');
			exit();
		} else {
			$error = 1;
		}
	}
	
	//Check if action is edit
	if($map_id) {
		$get_map = $wpdb->get_row("select * from $table where map_id = '".$map_id."'");
		//Check if map id is invalid
		if(!$get_map) {
			wp_die('Invalid map...');
		} else {
			$map_title 		= $get_map->map_title;
			$markers 		= @unserialize($get_map->markers);
			$width_height 	= @unserialize($get_map->width_height);
			$map_styles 	= $get_map->map_styles;
			$zoom_settings 	= @unserialize($get_map->zoom_settings);
			$map_controls 	= @unserialize($get_map->map_controls);
			$nearest_places	= @unserialize($get_map->nearest_places);
			$map_layers 	= @unserialize($get_map->map_layers);
			$miscellaneous 	= @unserialize($get_map->miscellaneous);
		}
	}
	
	if(!$preview) {
		?>
            <div class="wrap" id="sb-google-maps-plugin">
                <h2>
                    <img class="title-icon" src="<?php echo SB_IMG_DIR_URL; ?>/logo-icon.png" alt="" />
                    <?php
                        if($map_id) {
                            echo 'Edit Map: '.$map_title.'<a class="add-new-h2" href="'.SB_EDIT_PAGE_URL.'">Add New</a>';
                        } else {
                            echo 'Add New Map';
                        }
                    ?>
                </h2>
                <div class="preview-buttons-group">
                	<?php if($map_id) { ?>
	                <a class="sb-button alignright preview-button" href="<?php echo SB_EDIT_PAGE_URL.'&id='.$map_id.'&preview=true'; ?>">View Map Preview</a>
                    <?php } ?>
    	            <a class="sb-button alignright preview-button" href="javascript:" onclick="jQuery('#btn_save_map').trigger('click');">Save Map</a>
                    <?php if($map_id) { ?>
	                <code class="alignright" style="margin-top:3px;">[SBMAP ID="<?php echo $map_id; ?>"]</code>
                    <?php } ?>
                </div>
                <br />
                
                <div class="sbclear"></div>
                <?php if($success) { ?>
                    <div class="updated below-h2"><p><strong>Map Saved.</strong></p></div>
                <?php } else if($error) { ?>
                    <div class="updated error below-h2"><p><strong>Something wrong. Please try again.</strong></p></div>
                <?php } ?>
                <form id="frm-google-map" action="" method="post">
                <div id="titlediv">
                    <div id="titlewrap">
                        <input value="<?php echo display_field($map_title); ?>" type="text" autocomplete="off" id="title" value="" size="30" name="map_title" placeholder="Title">
                    </div>
                </div>
                <div class="metabox-holder">
                        <div class="meta-box-sortables">
                            <div class="postbox sb-map-zoom">
                                <div title="Click to toggle" class="handlediv"><br></div>
                                <h3 class="hndle"><span>Map Markers</span></h3>
                                <div class="inside">
                                    <div class="main">
                                        <table id="map-markers" class="sbmap-table">
                                            <thead>
                                                <tr>
                                                    <th>Address or (Latitude, Longitude)</th>
                                                    <th style="display:none;">Latitude</th>
                                                    <th style="display:none;">Longitude</th>
                                                    <th>Text for Directions Link</th>
                                                    <th>Marker Icon</th>
                                                    <th>Icon Animation</th>
                                                    <th>Info Window</th>
                                                    <th>Marker Content</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if($markers) {
                                                        $marker_count = 1;
                                                        foreach($markers as $marker) {
                                                            ?>
                                                            <tr class="map-marker">
                                                                <td><input type="text" name="address[]" id="address-<?php echo $marker_count; ?>" class="address" value="<?php echo display_field($marker['address']); ?>"></td>
                                                                <td style="display:none;"><input type="text" name="latitude[]" class="latitude"></td>
                                                                <td style="display:none;"><input type="text" name="longitude[]" class="longitude"></td>
                                                                <td><input type="text" name="textfordirectionslink[]" class="textfordirectionslink" value="<?php echo display_field($marker['textfordirectionslink']); ?>"></td>
                                                                <td><input type="text" name="icon[]" class="icon" value="<?php echo display_field($marker['icon']); ?>" readonly="readonly"></td>
                                                                <td>
                                                                    <select name="animation[]" class="animation">
                                                                        <option <?php selected($marker['animation'],'NONE'); ?> value="NONE">NONE</option>
                                                                        <option <?php selected($marker['animation'],'DROP'); ?> value="DROP">DROP</option>
                                                                        <option <?php selected($marker['animation'],'BOUNCE'); ?> value="BOUNCE">BOUNCE</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="infowindow[]" class="infowindow">
                                                                        <option <?php selected($marker['infowindow'],'yes'); ?> value="yes">yes</option>
                                                                        <option <?php selected($marker['infowindow'],'no'); ?> value="no">no</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <textarea name="content[]" class="content" rows="1"><?php echo display_field($marker['content']); ?></textarea>
                                                                </td>
                                                                <td><?php if($marker_count != 1) { ?><button class="sb-button remove-marker" type="button">x</button><?php } ?></td>
                                                            </tr>
                                                            <?php
                                                            $marker_count++;
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        <button id="add-more-marker" type="button" class="sb-button">+ Add Marker</button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sbclear"></div>
                
                    <div class="metabox-holder sb-metabox-holder">
                        
                        <div class="meta-box-sortables">
                            <div class="postbox">
                                <div class="inside sb-inside">
                                    <div class="main">
                                        <label class="first">Width</label>
                                        <input id="width" name="width" type="number" value="<?php echo display_field($width_height['width']); ?>">
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($width_height['widthtype'],'px'); ?>">PX <input type="radio" <?php checked($width_height['widthtype'],'px'); ?> value="px" name="width_type" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($width_height['widthtype'],'%'); ?>">% <input type="radio" <?php checked($width_height['widthtype'],'%'); ?> value="%" name="width_type" /></label>
                                        </div>
                                        
                                        <label>Height</label>
                                        <input type="number" id="height" name="height" value="<?php echo display_field($width_height['height']); ?>">
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($width_height['heighttype'],'px'); ?>">PX <input type="radio" <?php checked($width_height['heighttype'],'px'); ?> value="px" name="height_type" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($width_height['heighttype'],'%'); ?>">% <input type="radio" <?php checked($width_height['heighttype'],'%'); ?> value="%" name="height_type" /></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="meta-box-sortables">
                            <div class="postbox sb-map-zoom closed">
                                <div title="Click to toggle" class="handlediv"><br></div>
                                <h3 class="hndle"><span>Zoom Settings</span></h3>
                                <div class="inside">
                                    <div class="main">
                                        
                                        <label class="first">Zoom Level</label>
                                        <select id="zoom" name="zoom">
                                            <?php for($zoom = 1; $zoom <=21; $zoom++) { ?>
                                                <option <?php selected($zoom_settings['zoom'],$zoom); ?> value="<?php echo $zoom; ?>"><?php echo $zoom; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Control zoom level of the map. For multiple markers it will automatically set to fit all markers in viewport.<br /><br />If you want to control zoom level for multiple markers, you must have to set <strong><em>Center of Map</em></strong> (last field in this section).</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Zoom Control</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($zoom_settings['zoomcontrol'],'yes'); ?>">YES <input type="radio" <?php checked($zoom_settings['zoomcontrol'],'yes'); ?> value="yes" name="zoomcontrol" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($zoom_settings['zoomcontrol'],'no'); ?>">NO <input type="radio" <?php checked($zoom_settings['zoomcontrol'],'no'); ?> value="no" name="zoomcontrol" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays a slider (for large maps) or small "+/-" buttons</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Zoom Position</label>
                                        <select id="zoomcontrol_position" name="zoomcontrol_position">
                                            <?php foreach($sb_control_positions as $sb_control_position) { ?>
                                                <option <?php selected($zoom_settings['zoomcontrol_position'],$sb_control_position); ?> value="<?php echo $sb_control_position; ?>"><?php echo str_replace('_',' ',$sb_control_position); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Zoom control position</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Zoom Style</label>
                                        <select id="zoomcontrolstyle" name="zoomcontrolstyle">
                                            <option <?php selected($zoom_settings['zoomcontrolstyle'],'DEFAULT'); ?> value="DEFAULT">DEFAULT</option>
                                            <option <?php selected($zoom_settings['zoomcontrolstyle'],'SMALL'); ?> value="SMALL">SMALL</option>
                                            <option <?php selected($zoom_settings['zoomcontrolstyle'],'LARGE'); ?> value="LARGE">LARGE</option>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Zoom control style</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Draggable</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($zoom_settings['draggable'],'yes'); ?>">YES <input type="radio" <?php checked($zoom_settings['draggable'],'yes'); ?> value="yes" name="draggable" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($zoom_settings['draggable'],'no'); ?>">NO <input type="radio" <?php checked($zoom_settings['draggable'],'no'); ?> value="no" name="draggable" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">If yes, map will be draggable by mouse</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Scroll Wheel</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($zoom_settings['scrollwheel'],'yes'); ?>">YES <input type="radio" <?php checked($zoom_settings['scrollwheel'],'yes'); ?> value="yes" name="scrollwheel" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($zoom_settings['scrollwheel'],'no'); ?>">NO <input type="radio" <?php checked($zoom_settings['scrollwheel'],'no'); ?> value="no" name="scrollwheel" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">If yes, zoom level will be changed by mouse scroll wheel</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Center of Map</label>
                                        <input style="width:250px;" type="text" name="centerpoint" id="centerpoint" value="<?php echo (isset($zoom_settings['centerpoint']['address']))?display_field($zoom_settings['centerpoint']['address']):''; ?>" />
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Address or (latitude, longitude).<br />Leave blank to auto center.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="meta-box-sortables">
                            <div class="postbox sb-map-zoom closed">
                                <div title="Click to toggle" class="handlediv"><br></div>
                                <h3 class="hndle"><span>Find Nearest Places</span></h3>
                                <div class="inside">
                                    <div class="main">
                                        <span class="sb-note"><strong>Note:</strong> If you want to use this feature with multiple markers map, you must have to set <strong>Center of Map</strong> field. Nearest places will be calculated from center of map.</span>
                                        <div class="sbclear"></div>
                                        <label class="first">Search Type</label>
                                        <?php
                                            //Supported Search Types: https://developers.google.com/places/documentation/supported_types
                                            $map_searchtypes = array (
                                                'accounting',
                                                'airport',
                                                'amusement_park',
                                                'aquarium',
                                                'art_gallery',
                                                'atm',
                                                'bakery',
                                                'bank',
                                                'bar',
                                                'beauty_salon',
                                                'bicycle_store',
                                                'book_store',
                                                'bowling_alley',
                                                'bus_station',
                                                'cafe',
                                                'campground',
                                                'car_dealer',
                                                'car_rental',
                                                'car_repair',
                                                'car_wash',
                                                'casino',
                                                'cemetery',
                                                'church',
                                                'city_hall',
                                                'clothing_store',
                                                'convenience_store',
                                                'courthouse',
                                                'dentist',
                                                'department_store',
                                                'doctor',
                                                'electrician',
                                                'electronics_store',
                                                'embassy',
                                                'establishment',
                                                'finance',
                                                'fire_station',
                                                'florist',
                                                'food',
                                                'funeral_home',
                                                'furniture_store',
                                                'gas_station',
                                                'general_contractor',
                                                'grocery_or_supermarket',
                                                'gym',
                                                'hair_care',
                                                'hardware_store',
                                                'health',
                                                'hindu_temple',
                                                'home_goods_store',
                                                'hospital',
                                                'insurance_agency',
                                                'jewelry_store',
                                                'laundry',
                                                'lawyer',
                                                'library',
                                                'liquor_store',
                                                'local_government_office',
                                                'locksmith',
                                                'lodging',
                                                'meal_delivery',
                                                'meal_takeaway',
                                                'mosque',
                                                'movie_rental',
                                                'movie_theater',
                                                'moving_company',
                                                'museum',
                                                'night_club',
                                                'painter',
                                                'park',
                                                'parking',
                                                'pet_store',
                                                'pharmacy',
                                                'physiotherapist',
                                                'place_of_worship',
                                                'plumber',
                                                'police',
                                                'post_office',
                                                'real_estate_agency',
                                                'restaurant',
                                                'roofing_contractor',
                                                'rv_park',
                                                'school',
                                                'shoe_store',
                                                'shopping_mall',
                                                'spa',
                                                'stadium',
                                                'storage',
                                                'store',
                                                'subway_station',
                                                'synagogue',
                                                'taxi_stand',
                                                'train_station',
                                                'travel_agency',
                                                'university',
                                                'veterinary_care',
                                                'zoo'
                                            );
                                            asort($map_searchtypes);
                                        ?>
                                        <select id="searchtype" name="searchtype">
                                            <option <?php selected($nearest_places['searchtype'],'disabled'); ?> value="disabled">Disabled</option>
                                            <?php foreach($map_searchtypes as $map_searchtype) { ?>
                                                <option <?php selected($nearest_places['searchtype'],$map_searchtype); ?> value="<?php echo $map_searchtype; ?>"><?php echo ucwords(str_replace('_',' ',trim($map_searchtype))); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Nearest search query. Select <span style="color:#000;font-weight:700;">Disabled</span> to turn off this feature. <a target="_blank" href="https://developers.google.com/places/documentation/supported_types">Click Here</a> to see supported search query.</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Search Radius</label>
                                        <input type="number" id="searchradius" name="searchradius" value="<?php echo (int)display_field($nearest_places['searchradius']); ?>" />
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Search area radius in <span style="color:#000;font-weight:700;">meters</span>. Radius calculates from center of map. Maximum allowed radius is <span style="color:#000;font-weight:700;">50000</span>.</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Icon Animation</label>
                                        <select id="searchiconanimation" name="searchiconanimation">
                                            <option <?php selected($nearest_places['searchiconanimation'],'NONE'); ?> value="NONE">NONE</option>
                                            <option <?php selected($nearest_places['searchiconanimation'],'DROP'); ?> value="DROP">DROP</option>
                                            <option <?php selected($nearest_places['searchiconanimation'],'BOUNCE'); ?> value="BOUNCE">BOUNCE</option>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Seach result icon animation.</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Text for Direction Link</label>
                                        <input type="text" id="searchdirectiontext" name="searchdirectiontext" value="<?php echo display_field($nearest_places['searchdirectiontext']); ?>" />
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Direction link text for search result marker. Leave blank to hide link.</div>
                                        </div>
                                        <div class="sbclear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="meta-box-sortables">
                            <div class="postbox sb-map-zoom closed">
                                <div title="Click to toggle" class="handlediv"><br></div>
                                <h3 class="hndle"><span>Miscellaneous</span></h3>
                                <div class="inside">
                                    <div class="main">
                                        
                                        <label class="first">Reload on resize</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($miscellaneous['reloadonresize'],'yes'); ?>">YES <input type="radio" <?php checked($miscellaneous['reloadonresize'],'yes'); ?> value="yes" name="reloadonresize" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($miscellaneous['reloadonresize'],'no'); ?>">NO <input type="radio" <?php checked($miscellaneous['reloadonresize'],'no'); ?> value="no" name="reloadonresize" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">If yes,map will be reload on screen resize</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Language</label>
                                        <?php
                                            //List of languages supported by google map(v3 api)
                                            //Refrence: http://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1
                                            $map_languages = array(
                                                'ar'	=>	'ARABIC',
                                                'eu'	=>	'BASQUE',
                                                'bg'	=>	'BULGARIAN',
                                                'bn'	=>	'BENGALI',
                                                'ca'	=>	'CATALAN',
                                                'cs'	=>	'CZECH',
                                                'da'	=>	'DANISH',
                                                'de'	=>	'GERMAN',
                                                'el'	=>	'GREEK',
                                                'en'	=>	'ENGLISH',
                                                'en-AU'	=>	'ENGLISH (AUSTRALIAN)',
                                                'en-GB'	=>	'ENGLISH (GREAT BRITAIN)',
                                                'es'	=>	'SPANISH',
                                                'eu'	=>	'BASQUE',
                                                'fa'	=>	'FARSI',
                                                'fi'	=>	'FINNISH',
                                                'fil'	=>	'FILIPINO',
                                                'fr'	=>	'FRENCH',
                                                'gl'	=>	'GALICIAN',
                                                'gu'	=>	'GUJARATI',
                                                'hi'	=>	'HINDI',
                                                'hr'	=>	'CROATIAN',
                                                'hu'	=>	'HUNGARIAN',
                                                'id'	=>	'INDONESIAN',
                                                'it'	=>	'ITALIAN',
                                                'iw'	=>	'HEBREW',
                                                'ja'	=>	'JAPANESE',
                                                'kn'	=>	'KANNADA',
                                                'ko'	=>	'KOREAN',
                                                'lt'	=>	'LITHUANIAN',
                                                'lv'	=>	'LATVIAN',
                                                'ml'	=>	'MALAYALAM',
                                                'mr'	=>	'MARATHI',
                                                'nl'	=>	'DUTCH',
                                                //'nn'	=>	'NORWEGIAN NYNORSK', 			//Not Supported by v3 API
                                                'no'	=>	'NORWEGIAN',
                                                //'or'	=>	'ORIYA',						//Not Supported by v3 API
                                                'pl'	=>	'POLISH',
                                                'pt'	=>	'PORTUGUESE',
                                                'pt-BR'	=>	'PORTUGUESE (BRAZIL)',
                                                'pt-PT'	=>	'PORTUGUESE (PORTUGAL)',
                                                //'rm'	=>	'ROMANSCH',						//Not Supported by v3 API
                                                'ro'	=>	'ROMANIAN',
                                                'ru'	=>	'RUSSIAN',
                                                'sk'	=>	'SLOVAK',
                                                'sl'	=>	'SLOVENIAN',
                                                'sr'	=>	'SERBIAN',
                                                'sv'	=>	'SWEDISH',
                                                'tl'	=>	'TAGALOG',
                                                'ta'	=>	'TAMIL',
                                                'te'	=>	'TELUGU',
                                                'th'	=>	'THAI',
                                                'tr'	=>	'TURKISH',
                                                'uk'	=>	'UKRAINIAN',
                                                'vi'	=>	'VIETNAMESE',
                                                'zh-CN'	=>	'CHINESE (SIMPLIFIED)',
                                                'zh-TW'	=>	'CHINESE (TRADITIONAL)'
                                            );
                                            
                                            asort($map_languages);
                                        ?>
                                        <select id="language" name="language">
                                            <?php
                                                foreach($map_languages as $lang_key => $map_language) { ?>
                                                    <option <?php selected($miscellaneous['language'],$lang_key); ?> value="<?php echo $lang_key; ?>"><?php echo $map_language; ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Localize your map. <a target="_blank" href="http://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1">Click Here</a> to see list of supported language (v3 api)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input id="btn_save_map" name="btn_save_map" class="sb-button" type="button" onclick="jQuery(this).closest('form').trigger('submit');" value="SAVE MAP">
                    </div>
                    
                    <div class="metabox-holder sb-metabox-holder sb-metabox-holder-right">
                    
                        <div class="meta-box-sortables">
                            <div class="postbox">
                                <div class="inside sb-inside">
                                    <div class="main">
                                        <label class="first"><strong>Map Styles</strong></label>
                                        <select id="mapstyles" name="mapstyles">
                                            <?php for($mapstylecount = 1; $mapstylecount <= 114; $mapstylecount++) { ?>
                                                <option <?php selected($map_styles, 'style-'.$mapstylecount); ?> value="<?php echo 'style-'.$mapstylecount; ?>"><?php echo 'style-'.$mapstylecount; ?></option>
                                            <?php } ?>
                                        </select>
                                        &nbsp; &nbsp; &nbsp;<a id="map-styles-preview-link" href="javascript:">Click here to view all map styles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="meta-box-sortables">
                            <div class="postbox sb-map-controls closed">
                                <div title="Click to toggle" class="handlediv"><br></div>
                                <h3 class="hndle"><span>Map Controls</span></h3>
                                <div class="inside">
                                    <div class="main">
                                        
                                        <label class="first">Pan Control</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_controls['pancontrol'],'yes'); ?>">YES <input type="radio" <?php checked($map_controls['pancontrol'],'yes'); ?> value="yes" name="pancontrol" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_controls['pancontrol'],'no'); ?>">NO <input type="radio" <?php checked($map_controls['pancontrol'],'no'); ?> value="no" name="pancontrol" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays buttons for panning the map</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Pan Control Position</label>
                                        <select id="pancontrol_position" name="pancontrol_position">
                                            <?php foreach($sb_control_positions as $sb_control_position) { ?>
                                                <option <?php selected($map_controls['pancontrol_position'],$sb_control_position); ?> value="<?php echo $sb_control_position; ?>"><?php echo str_replace('_',' ',$sb_control_position); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Pan control buttons position</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Scale Control</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_controls['scalecontrol'],'yes'); ?>">YES <input type="radio" <?php checked($map_controls['scalecontrol'],'yes'); ?> value="yes" name="scalecontrol" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_controls['scalecontrol'],'no'); ?>">NO <input type="radio" <?php checked($map_controls['scalecontrol'],'no'); ?> value="no" name="scalecontrol" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays a map scale element</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Street View Control</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_controls['streetviewcontrol'],'yes'); ?>">YES <input type="radio" <?php checked($map_controls['streetviewcontrol'],'yes'); ?> value="yes" name="streetviewcontrol" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_controls['streetviewcontrol'],'no'); ?>">NO <input type="radio" <?php checked($map_controls['streetviewcontrol'],'no'); ?> value="no" name="streetviewcontrol" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays a Pegman icon to enable street view</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Street View Control Position</label>
                                        <select id="streetviewcontrol_position" name="streetviewcontrol_position">
                                            <?php foreach($sb_control_positions as $sb_control_position) { ?>
                                                <option <?php selected($map_controls['streetviewcontrol_position'],$sb_control_position); ?> value="<?php echo $sb_control_position; ?>"><?php echo str_replace('_',' ',$sb_control_position); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Pegman icon (street view control) position</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Map Type Control</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_controls['maptypecontrol'],'yes'); ?>">YES <input type="radio" <?php checked($map_controls['maptypecontrol'],'yes'); ?> value="yes" name="maptypecontrol" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_controls['maptypecontrol'],'no'); ?>">NO <input type="radio" <?php checked($map_controls['maptypecontrol'],'no'); ?> value="no" name="maptypecontrol" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays a maptype control</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Map Type Control Position</label>
                                        <select id="maptypecontrol_position" name="maptypecontrol_position">
                                            <?php foreach($sb_control_positions as $sb_control_position) { ?>
                                                <option <?php selected($map_controls['maptypecontrol_position'],$sb_control_position); ?> value="<?php echo $sb_control_position; ?>"><?php echo str_replace('_',' ',$sb_control_position); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Map type control position</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Map Type</label>
                                        <select id="maptype" name="maptype">
                                           <option <?php selected($map_controls['maptype'],'ROADMAP'); ?> value="ROADMAP">ROADMAP</option>
                                           <option <?php selected($map_controls['maptype'],'SATELLITE'); ?> value="SATELLITE">SATELLITE</option>
                                           <option <?php selected($map_controls['maptype'],'HYBRID'); ?> value="HYBRID">HYBRID</option>
                                           <option <?php selected($map_controls['maptype'],'TERRAIN'); ?> value="TERRAIN">TERRAIN</option>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Toggle between map types</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Map Type Control Style</label>
                                        <select id="maptypecontrolstyle" name="maptypecontrolstyle">
                                            <option <?php selected($map_controls['maptypecontrolstyle'],'DEFAULT'); ?> value="DEFAULT">DEFAULT</option>
                                            <option <?php selected($map_controls['maptypecontrolstyle'],'HORIZONTAL_BAR'); ?> value="HORIZONTAL_BAR">HORIZONTAL_BAR</option>
                                            <option <?php selected($map_controls['maptypecontrolstyle'],'DROPDOWN_MENU'); ?> value="DROPDOWN_MENU">DROPDOWN_MENU</option>
                                        </select>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Choose map type control style</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Overview Map Control Visible</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_controls['overviewmapcontrolvisible'],'yes'); ?>">YES <input type="radio" <?php checked($map_controls['overviewmapcontrolvisible'],'yes'); ?> value="yes" name="overviewmapcontrolvisible" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_controls['overviewmapcontrolvisible'],'no'); ?>">NO <input type="radio" <?php checked($map_controls['overviewmapcontrolvisible'],'no'); ?> value="no" name="overviewmapcontrolvisible" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays a thumbnail overview map reflecting the current map viewport</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        <label class="first">Overview Map Control</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_controls['overviewmapcontrol'],'yes'); ?>">YES <input type="radio" <?php checked($map_controls['overviewmapcontrol'],'yes'); ?> value="yes" name="overviewmapcontrol" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_controls['overviewmapcontrol'],'no'); ?>">NO <input type="radio" <?php checked($map_controls['overviewmapcontrol'],'no'); ?> value="no" name="overviewmapcontrol" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Displays a toggle button to show / hide overview map control (bottom tight)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="meta-box-sortables">
                            <div class="postbox sb-map-zoom closed">
                                <div title="Click to toggle" class="handlediv"><br></div>
                                <h3 class="hndle"><span>Map Layers</span></h3>
                                <div class="inside">
                                    <div class="main">
                                        
                                        <label class="first">Weather</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_layers['weather'],'yes'); ?>">YES <input type="radio" <?php checked($map_layers['weather'],'yes'); ?> value="yes" name="weather" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_layers['weather'],'no'); ?>">NO <input type="radio" <?php checked($map_layers['weather'],'no'); ?> value="no" name="weather" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Weather layer add weather forecasts to map</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Traffic</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_layers['traffic'],'yes'); ?>">YES <input type="radio" <?php checked($map_layers['traffic'],'yes'); ?> value="yes" name="traffic" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_layers['traffic'],'no'); ?>">NO <input type="radio" <?php checked($map_layers['traffic'],'no'); ?> value="no" name="traffic" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Traffic layer add real-time traffic information to map</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Transit</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_layers['transit'],'yes'); ?>">YES <input type="radio" <?php checked($map_layers['transit'],'yes'); ?> value="yes" name="transit" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_layers['transit'],'no'); ?>">NO <input type="radio" <?php checked($map_layers['transit'],'no'); ?> value="no" name="transit" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Traffic layer add public transit network of a city to map</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Bicycle</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_layers['bicycle'],'yes'); ?>">YES <input type="radio" <?php checked($map_layers['bicycle'],'yes'); ?> value="yes" name="bicycle" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_layers['bicycle'],'no'); ?>">NO <input type="radio" <?php checked($map_layers['bicycle'],'no'); ?> value="no" name="bicycle" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Bicycle layer add bicycle information (bike routes) to map</div>
                                        </div>
                                        <div class="sbclear"></div>
                                        
                                        <label class="first">Panoramio</label>
                                        <div class="sb-radio-group">
	                                        <label class="radio-left <?php toggle_radio_button_class($map_layers['panoramio'],'yes'); ?>">YES <input type="radio" <?php checked($map_layers['panoramio'],'yes'); ?> value="yes" name="panoramio" /></label>
                                            <label class="radio-right <?php toggle_radio_button_class($map_layers['panoramio'],'no'); ?>">NO <input type="radio" <?php checked($map_layers['panoramio'],'no'); ?> value="no" name="panoramio" /></label>
                                        </div>
                                        <div class="sb-tooltip">
                                        	<span class="help-icon">?</span>
                                            <div class="tooltip-content">Panoramio layer add community contributed photos to map</div>
                                        </div>
                                                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php wp_nonce_field($nonce_key, 'sb_google_map_save_map'); ?>
                        <input type="hidden" name="save_google_map_details" value="1" />
                    </div>
                    
                    <div class="sbclear"></div>
                    
                    
                    
                    <!-- Icon popup HTML -->
                    <div id="icons-content">
                        <div class="markers-container">
                            <ul id="icons-list">
                                <?php
                                    $icons = scandir(SB_ICONS_DIR_PATH);
                                    natsort($icons);
                                    foreach($icons as $icon) {
                                        $icon = strtolower($icon);
                                        $icon_ext = pathinfo($icon, PATHINFO_EXTENSION);
                                        if($icon_ext != 'png') {
                                            continue;
                                        } ?>
                                        <li>
                                            <label for="icon-<?php echo basename($icon,'.png'); ?>">
                                                <img src="<?php echo SB_ICONS_DIR_URL.'/'.$icon; ?>" alt="<?php echo basename($icon,'.png'); ?>" />
                                            </label>
                                            <input type="radio" name="mapicon" id="icon-<?php echo basename($icon,'.png'); ?>" value="<?php echo basename($icon,'.png'); ?>" />
                                            
                                        </li>
                                        <?php
                                    }
                                ?>
                            </ul>
                            <input type="text" class="final-icon" id="final-icon" placeholder="Select icon or insert custom icon url here" />
                            <p class="description">To hide icon type <span style="font-weight:700;color:#000;text-decoration:underline;">none</span> in above field. Select icon or insert custom icon full url. Ex: http://exapmle.com/images/xyz.png</p>
                        </div>
                    </div>
                    
                    <!-- Preview popup HTML -->
                    <div id="preview-content">
                        <div class="markers-container">
                            <ul id="previews-list">
                                <?php
                                    $previews = scandir(SB_PREVIEW_DIR_PATH);
                                    natsort($previews);
                                    $preview_count = 1;
                                    foreach($previews as $preview) {
                                        $preview = strtolower($preview);
                                        $preview_ext = pathinfo($preview, PATHINFO_EXTENSION);
                                        if($preview_ext != 'jpg') {
                                            continue;
                                        } ?>
                                        
                                        
                                        <li>
                                            <span class="preview-title"><?php echo str_replace('-',' ',basename($preview,'.jpg')); ?></span>
                                            <label for="preview-<?php echo basename($preview,'.jpg'); ?>">
                                                <?php
                                                    if($preview_count > 8) {
                                                        $preview_src = SB_IMG_DIR_URL.'/lazyload.png';
                                                        $display_pre_img = 'none';
                                                    } else {
                                                        $preview_src = SB_PREVIEW_DIR_URL.'/'.$preview;
                                                        $display_pre_img = 'block';
                                                    }
                                                ?>
                                                <img class="lazyload" src="<?php echo $preview_src; ?>" data-src="<?php echo SB_PREVIEW_DIR_URL.'/'.$preview; ?>" alt="" style=" display:<?php echo $display_pre_img; ?>;" />
                                            </label>
                                            <input type="radio" name="mappreview" id="preview-<?php echo basename($preview,'.jpg'); ?>" value="<?php echo basename($preview,'.jpg'); ?>" />
                                        </li>
                                        
                                        <?php
                                        $preview_count++;
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </form>
            
            </div>
		<?php
	} else {
		?>
        	<div class="wrap" id="sb-google-maps-plugin">
                <h2>
                    <img class="title-icon" src="<?php echo SB_IMG_DIR_URL; ?>/logo-icon.png" alt="" />Map Preview: <?php echo $map_title; ?>
                    <a class="add-new-h2" href="<?php echo SB_EDIT_PAGE_URL; ?>">Add New</a>
                </h2>
        		<div class="preview-buttons-group">
					<?php if($map_id) { ?>
                    <a class="sb-button alignright preview-button" href="<?php echo SB_EDIT_PAGE_URL.'&id='.$map_id; ?>">Edit Map</a>
                    <?php } ?>
                </div>
            	<div class="sbclear"></div>
                <span>Width & Height are ignored in this preview.</span>
                <span class="alignright">Use this shortcode to display map&nbsp; : &nbsp; &nbsp; <code>[SBMAP ID="<?php echo $map_id; ?>"]</code></span>
                <div class="sbclear"></div>
            	<div id="sb-map-preview">
                	<?php echo do_shortcode('[sbmap id="'.$map_id.'"]'); ?>
                </div>
                
            </div>
        <?php
	}
}

function sb_map_render_list() {
	//Including Map table List Class
	require('class-map-list.php');
	$map_list_table = new SB_Goole_Map_List_Table();
	//Fetch, prepare, sort, and filter our data...
	$map_list_table->prepare_items();
	?>
    <div class="wrap" id="sb-google-maps-plugin">
    	<h2>
        	<img class="title-icon" src="<?php echo SB_IMG_DIR_URL; ?>/logo-icon.png" alt="" />
            SB Google Maps
            <a class="add-new-h2" href="<?php echo SB_EDIT_PAGE_URL; ?>">Add New</a>
        </h2>
        <?php if(isset($_GET['deleted'])) { ?>
            <div class="updated below-h2"><p><strong><?php echo $_GET['deleted']; ?> map(s) deleted.</strong></p></div>
        <?php } ?>
        <form id="frm-google-map-list" method="get">
        	<input type="hidden" name="page" value="sb-google-map" />
        	<?php $map_list_table->display(); ?>
        </form>
	</div>
    <?php
}