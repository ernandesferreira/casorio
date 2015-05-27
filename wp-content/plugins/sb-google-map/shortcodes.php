<?php
/*	 Plugin shortcodes file
 *   Version: 1.0
 *   Author: SB Themes
 *   Profile: http://codecanyon.net/user/sbthemes?ref=sbthemes
 */


/**
 * Adding shortcode for map
 */
function sb_generate_google_map( $atts, $content = NULL ) {
	
	global $wpdb;
	$table = $wpdb->prefix.'sb_google_map';
	
	extract( shortcode_atts( array(
		'id'	=> ''
	), $atts ) );
	
	$map_detail = $wpdb->get_row($wpdb->prepare("select * from $table where map_id = %d",$id));
	if(!$map_detail) {
		return 'Invalid Map ID';
	}
	
	//Getting Map Details
	$markers 			= unserialize($map_detail->markers);
	$width_height 		= unserialize($map_detail->width_height);
	$map_styles 		= $map_detail->map_styles;
	$zoom_settings 		= unserialize($map_detail->zoom_settings);
	$map_controls 		= unserialize($map_detail->map_controls);
	$nearest_places		= unserialize($map_detail->nearest_places);
	$map_layers 		= unserialize($map_detail->map_layers);
	$miscellaneous 		= unserialize($map_detail->miscellaneous);
	
	//Preparing markers
	$marker_content = '';
	foreach($markers as $marker) {
		$infowindow 			= sb_lower_trim($marker['infowindow']);
		$textfordirectionslink 	= $marker['textfordirectionslink'];
		$latitude 				= $marker['latitude'];
		$longitude 				= $marker['longitude'];
		$icon 					= $marker['icon'];
		$animation 				= $marker['animation'];
		$content 				= stripslashes(trim($marker['content']));
		
		if($latitude == '' || $longitude == '') {
			continue;
		}
		
		$marker_content .= '<div class="sb-marker"';
		$marker_content .= ' data-lat="'.$latitude.'" data-lng="'.$longitude.'" infowindow="'.$infowindow.'"';
		if(trim($icon) != '') {
			$icon = sb_get_icon($icon);
			$marker_content .= ' icon="'.$icon.'"';
		}
		$marker_content .= ' animation="'.sb_upper_trim($animation).'">';
		$marker_content .= do_shortcode($content);
		if(trim($textfordirectionslink) != '') {
			$marker_content .= '<br /><a target="_blank" href="https://maps.google.com/?daddr='.$latitude.','.$longitude.'">'.$textfordirectionslink.'</a>';
		}
		$marker_content .= '</div>';
	}
	
	$width = $width_height['width'].$width_height['widthtype'];
	$widthpx = strpos($width,'px');
	$widthper = strpos($width,'%');
	if(!$widthpx && !$widthper) {
		$width = intval($width).'px';
	}
	
	$height = $width_height['height'].$width_height['heighttype'];
	$heightpx = strpos($height,'px');
	$heightper = strpos($height,'%');
	if(!$heightpx && !$heightper) {
		$height = intval($height).'px';
	}
	
	$mapstyle = sb_lower_trim($map_styles);
	
	$zoom = intval(abs(trim($zoom_settings['zoom'])));
	if($zoom == '' || $zoom == 0) {
		$zoom = 14;
	}
	
	$zoomcontrol 					= sb_lower_trim($zoom_settings['zoomcontrol']);
	$zoomcontrol_position			= sb_upper_trim($zoom_settings['zoomcontrol_position']);
	$zoomcontrolstyle				= sb_upper_trim($zoom_settings['zoomcontrolstyle']);
	$draggable 						= sb_lower_trim($zoom_settings['draggable']);
	$scrollwheel 					= sb_lower_trim($zoom_settings['scrollwheel']);
	$cplatitude 					= (isset($zoom_settings['centerpoint']['latitude']))?$zoom_settings['centerpoint']['latitude']:0;
	$cplongitude 					= (isset($zoom_settings['centerpoint']['longitude']))?$zoom_settings['centerpoint']['longitude']:0;
	
	
	$pancontrol 					= sb_lower_trim($map_controls['pancontrol']);
	$pancontrol_position			= sb_upper_trim($map_controls['pancontrol_position']);
	$scalecontrol 					= sb_lower_trim($map_controls['scalecontrol']);
	$streetviewcontrol 				= sb_lower_trim($map_controls['streetviewcontrol']);
	$streetviewcontrol_position		= sb_upper_trim($map_controls['streetviewcontrol_position']);
	$maptypecontrol 				= sb_lower_trim($map_controls['maptypecontrol']);
	$maptypecontrol_position		= sb_upper_trim($map_controls['maptypecontrol_position']);
	$maptype 						= sb_upper_trim($map_controls['maptype']);
	$maptypecontrolstyle	 		= sb_upper_trim($map_controls['maptypecontrolstyle']);
	$overviewmapcontrolvisible 		= sb_lower_trim($map_controls['overviewmapcontrolvisible']);
	$overviewmapcontrol 			= sb_lower_trim($map_controls['overviewmapcontrol']);
	
	$searchtype						= sb_lower_trim($nearest_places['searchtype']);
	$searchradius					= intval(abs(trim($nearest_places['searchradius'])));
	$searchiconanimation			= sb_upper_trim($nearest_places['searchiconanimation']);
	$searchdirectiontext			= trim($nearest_places['searchdirectiontext']);
	
	$weather 						= sb_lower_trim($map_layers['weather']);
	$traffic 						= sb_lower_trim($map_layers['traffic']);
	$transit 						= sb_lower_trim($map_layers['transit']);
	$bicycle 						= sb_lower_trim($map_layers['bicycle']);
	$panoramio 						= sb_lower_trim($map_layers['panoramio']);
	
	$reloadonresize 						= sb_lower_trim($miscellaneous['reloadonresize']);
	$language 						= trim($miscellaneous['language']);
	
	
	wp_enqueue_style('sbmap-style');
	wp_enqueue_script('jquery');
	//Register google map api script if shortcode is call...
	wp_register_script( 'googlemapapi', (is_ssl() ? 'https://' :'http://').'maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,weather,panoramio&language='.$language, array(), '', true );
	wp_enqueue_script('googlemapapi');
	wp_enqueue_script('sbmap');
	
	//Generate Google Map HTML With Marker
    $output = '<div id="sb-google-map-'.$id.'" map-id="'.$id.'" class="sb-map" zoom="'.$zoom.'" maptype="'.$maptype.'" pancontrol="'.$pancontrol.'" pancontrol_position="'.$pancontrol_position.'" zoomcontrol="'.$zoomcontrol.'" zoomcontrol_position="'.$zoomcontrol_position.'" zoomcontrolstyle="'.$zoomcontrolstyle.'" maptypecontrol="'.$maptypecontrol.'" maptypecontrol_position="'.$maptypecontrol_position.'" streetviewcontrol="'.$streetviewcontrol.'" streetviewcontrol_position="'.$streetviewcontrol_position.'" overviewmapcontrol="'.$overviewmapcontrol.'" overviewmapcontrolvisible="'.$overviewmapcontrolvisible.'" maptypecontrolstyle="'.$maptypecontrolstyle.'" mapstyle="'.$mapstyle.'" weather="'.$weather.'" traffic="'.$traffic.'" transit="'.$transit.'" bicycle="'.$bicycle.'" panoramio="'.$panoramio.'" draggable="'.$draggable.'" scrollwheel="'.$scrollwheel.'" cplatitude="'.$cplatitude.'" cplongitude="'.$cplongitude.'" scalecontrol="'.$scalecontrol.'" searchtype="'.$searchtype.'" searchradius="'.$searchradius.'" searchiconanimation="'.$searchiconanimation.'" searchdirectiontext="'.$searchdirectiontext.'" reloadonresize="'.$reloadonresize.'" style="width:'.$width.';height:'.$height.'">';
	$output .= $marker_content;
	$output .= '</div>';
	return $output;
}
add_shortcode( 'SBMAP', 'sb_generate_google_map' );
add_shortcode( 'sbmap', 'sb_generate_google_map' );

//Enable Shortcode For Widget
add_filter('widget_text', 'do_shortcode');