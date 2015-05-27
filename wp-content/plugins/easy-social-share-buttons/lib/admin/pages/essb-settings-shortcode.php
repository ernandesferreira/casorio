<?php
$msg = "";

$cmd = isset($_POST["cmd"]) ? $_POST["cmd"] : '';
$shortcode = "";

if ($cmd == "generate") {
	
	$options = $_POST ['general_options'];
	
	//print_r($options ['sort']);
	$buttons = "";
	if (isset($options["networks"])) {
	foreach ( $options ['networks'] as $nw ) {
		if ($buttons != '') { $buttons .= ","; }
		$buttons .= $nw;
	}
	}
	
	if ($buttons == "") { $buttons = "no"; }
	
	$counters = isset($_POST["essb_counters"]) ? $_POST["essb_counters"] : "";
	$hide_names = isset($_POST["essb_hide_names"]) ? $_POST["essb_hide_names"] : "";
	$message = isset($_POST["essb_message"]) ? $_POST["essb_message"] : "";
	$counter_pos = isset($_POST["essb_counter_pos"]) ? $_POST["essb_counter_pos"] : "";
	$native = isset($_POST["essb_native"]) ? $_POST["essb_native"] : "";
	$show_fblike = isset($_POST["essb_show_fblike"]) ? $_POST["essb_show_fblike"] : "";
	$show_plusone = isset($_POST["essb_show_plusone"]) ? $_POST["essb_show_plusone"] : "";
	$show_twitter = isset($_POST["essb_show_twitter"]) ? $_POST["essb_show_twitter"] : "";
	$show_managedwp = isset($_POST['essb_show_managedwp']) ? $_POST['essb_show_managedwp'] : "";
	$show_vk = isset($_POST["essb_show_vk"]) ? $_POST["essb_show_vk"] : "";

	$show_youtube = isset($_POST["essb_show_youtube"]) ? $_POST["essb_show_youtube"] : "";
	$show_pinfollow = isset($_POST["essb_show_pinfollow"]) ? $_POST["essb_show_pinfollow"] : "";
	
	$sidebar = isset($_POST["essb_sidebar"]) ? $_POST["essb_sidebar"] : "";
	$sidebar_pos = isset($_POST["essb_sidebar_pos"]) ? $_POST["essb_sidebar_pos"] : "";
	$popup = isset($_POST["essb_popup"]) ? $_POST["essb_popup"] : "";
	$popafter = isset($_POST["essb_popafter"]) ? $_POST["essb_popafter"] : "";
	$url = isset($_POST["essb_url"]) ? $_POST["essb_url"] : "";
	$text = isset($_POST["essb_text"]) ? $_POST["essb_text"] : "";
	$image = isset($_POST["essb_image"]) ? $_POST["essb_image"] : "";
	$description = isset($_POST["essb_description"]) ? $_POST["essb_description"] : "";
	$fblike = isset($_POST["essb_fblike"]) ? $_POST["essb_fblike"] : "";
	$plusone = isset($_POST["essb_plusone"]) ? $_POST["essb_plusone"] : "";
	
	$essb_total_counter_pos = isset($_POST['essb_total_counter_pos']) ? $_POST['essb_total_counter_pos'] : '';
	$fulwidth = isset($_POST['essb_fullwidth']) ? $_POST['essb_fullwidth'] : '';
	$fulwidth_fix = isset($_POST['essb_fullwidth_fix']) ? $_POST['essb_fullwidth_fix'] : '';

	$fixedwidth = isset($_POST['essb_fixedwidth']) ? $_POST['essb_fixedwidth'] : '';
	$fixedwidth_px = isset($_POST['essb_fixedwidth_px']) ? $_POST['essb_fixedwidth_px'] : '';
	
	$essb_hide_name_forces = isset($_POST['essb_hide_name_forces']) ? $_POST['essb_hide_name_forces'] : '';
	$essb_template = isset($_POST['essb_template']) ? $_POST['essb_template'] : '';
	
	$shortcode = "[easy-share";
	
	$shortcode .= ' buttons="'.$buttons.'"';
	if ($counters == "on") { $shortcode .= ' counters=1'; } else {$shortcode .= ' counters=0'; }
	if ($hide_names == "on" || $essb_hide_name_forces == "on") {

		if ($essb_hide_name_forces == "on") {
			$shortcode .= ' hide_names="force"';
		}
		else {
			$shortcode .= ' hide_names="yes"';
		} 
	}
	if ($message == "on") { $shortcode .=  ' message="yes"'; }
	if ($counter_pos != "left") { $shortcode .= ' counter_pos="'.$counter_pos.'"'; }
	if ($native == "on") { $shortcode .= ' native="yes"'; } 
	else {
		if (($show_fblike == "on") ||($show_plusone == "on")  || ($show_twitter == "on") || ($show_pinfollow == "on") || ($show_youtube == "on") || ($show_managedwp == "on")) {
			$shortcode .= ' native="selected"';
		} 
		else {
			$shortcode .= ' native="no"';
		}

	}
	if ($show_fblike == "on") { $shortcode .= ' show_fblike="yes"'; }
	if ($show_plusone == "on") { $shortcode .= ' show_plusone="yes"'; }
	if ($show_twitter == "on") { $shortcode .= ' show_twitter="yes"'; }
	if ($show_vk == "on") { $shortcode .= ' show_vk="yes"'; }
	if ($show_pinfollow == "on") {
		$shortcode .= ' show_pinfollow="yes"';
	}
	if ($show_youtube == "on") { $shortcode .= ' show_youtube="yes"'; }
	if ($show_managedwp == "on") { $shortcode .= ' show_managedwp="yes"'; }
	
	
	if ($sidebar == "on") {
		$shortcode .= ' sidebar="yes"';
		$shortcode .=  ' sidebar_pos="'.$sidebar_pos.'"';		
	}
	
	if ($popup == "on") {
		$shortcode .= ' popup="yes"';
		if ($popafter != '') { $shortcode .= ' popafter="'.$popafter.'"'; }
	}
	
	if ($url != '') { $shortcode .= ' url="'.$url.'"'; }
	if ($text != '' ) { $shortcode .= ' text="'.$text.'"'; }
	if ($image != '') { $shortcode .= ' image="'.$image.'"'; }
	if ($description != '') { $shortcode .= ' description="'.$description.'"'; }
	if ($fblike != '') { $shortcode .= ' fblike="'.$fblike.'"'; }
	if ($plusone != '') { $shortcode .= ' plusone="'.$plusone. '"'; }
	
	if ($essb_total_counter_pos != '') {
		if ($essb_total_counter_pos == "hidden") {
			$shortcode .= ' hide_total="yes"';
		}
		else {
			$shortcode .= ' total_counter_pos="'.$essb_total_counter_pos. '"';
		}
	}
	
	if ($fulwidth == "on") {
		$shortcode .= ' fullwidth="yes"';
		
		if ($fulwidth_fix != '') {
			$shortcode .= ' fullwidth_fix="'.$fulwidth_fix.'"';
		}
	}
	
	
	if ($fixedwidth == "on") {
		$shortcode .= ' fixedwidth="yes"';
	
		if ($fixedwidth_px != '') {
			$shortcode .= ' fixedwidth_px="'.$fixedwidth_px.'"';
		}
	}
	
	if (isset($options["networks"])) {
		foreach ( $options ['networks'] as $nw ) {
			$key = $nw. '_text';
			
			$value = isset($_POST[$key]) ? $_POST[$key] : "";
			
			if ($value != '') {
				$shortcode .= ' '.$nw.'_text="'.$value.'"';
			}
		}
	}
	
	if ($essb_template != '') {
		$shortcode .= ' template="'.$essb_template.'"';
	}
 	
	$shortcode .= "]";
}

function essb_shortcode_text_change() {
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	if (is_array ( $options )) {
		foreach ( $options ['networks'] as $k => $v ) {
				
			$is_checked = "";
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
				
			echo '<li><p style="margin: .2em 5% .2em 0;">
			<input id="network__name_' . $k . '" name="'.$k.'_text" type="text"
			class="input-element" />
			<label for="network_name_' . $k . '"><span class="essb_icon essb_icon_' . $k . '"></span>' . $network_name . '</label>
			</p></li>';
		}


	}
}

function essb_shortcode_checkbox_network_selection() {
	$y = $n = '';
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );

	if (is_array ( $options )) {
		foreach ( $options ['networks'] as $k => $v ) {

			$is_checked = "";
			$network_name = isset ( $v [1] ) ? $v [1] : $k;

			echo '<li><p style="margin: .2em 5% .2em 0;">
			<input id="network_selection_' . $k . '" value="' . $k . '" name="general_options[networks][]" type="checkbox"
			' . $is_checked . ' /><input name="general_options[sort][]" value="' . $k . '" type="checkbox" checked="checked" style="display: none; " />
			<label for="network_selection_' . $k . '"><span class="essb_icon essb_icon_' . $k . '"></span>' . $network_name . '</label>
			</p></li>';
		}


	}
}

?>

	<div class="wrap">
	<?php
	
	if ($msg != "") {
		echo '<div class="success_message">' . $msg . '</div>';
	}
	
	if ($shortcode != '') {
		print '<div style="width: 100%; display: block; text-align: center;">';
		print '<div class="essb-shortcode">';
		print '<p class="bold">Your shortcode is:</p>';
		print '<div class="sub" style="width: 60%; text-align: center;  margin: 0 auto; margin-bottom: 20px;">';
		print $shortcode;
		print '</div></div></div>';
	}
	
	?>

	
		<form name="general_form" method="post"
			action="admin.php?page=essb_settings&tab=shortcode">
			<input type="hidden" id="cmd" name="cmd" value="generate" />
			<?php wp_nonce_field('essb'); ?>
			<div class="essb-options">
		<div class="essb-options-header" id="essb-options-header">
			<div class="essb-options-title">
			Shortcode Generator<br /><span class="label" style="font-weight: 400;"><a href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo" target="_blank" style="text-decoration: none;">Easy Social Share Buttons for WordPress version <?php echo ESSB_VERSION; ?></a></span>			
		</div>		
<?php echo '<a href="http://support.creoworx.com" target="_blank" text="' . __ ( 'Need Help? Click here to visit our support center', ESSB_TEXT_DOMAIN ) . '" class="button">' . __ ( 'Support Center', ESSB_TEXT_DOMAIN ) . '</a>'; ?>		
		<?php echo '<input type="Submit" name="Submit" value="' . __ ( 'Generate Shortcode', ESSB_TEXT_DOMAIN ) . '" class="button-primary" />'; ?>
	</div>
		<div class="essb-options-sidebar">
			<ul class="essb-options-group-menu">
				<li id="essb-menu-1" class="essb-menu-item"><a href="#"
					onclick="essb_option_activate('1'); return false;">Share Buttons</a></li>
				<li id="essb-menu-2" class="essb-menu-item"><a href="#"
					onclick="essb_option_activate('2'); return false;">Like and Subscribe Buttons</a></li>
				<li id="essb-menu-3" class="essb-menu-item"><a href="#"
					onclick="essb_option_activate('3'); return false;">Additional Display Settings</a></li>
				<li id="essb-menu-4" class="essb-menu-item"><a href="#"
					onclick="essb_option_activate('4'); return false;">Custom Share and Like URL's</a>
				</li>
				
			</ul>
		</div>
		<div class="essb-options-container">
			<div id="essb-container-1" class="essb-data-container">
			
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<col width="25%" />
				<col width="75%" />
				<tr class="table-border-bottom">
					<td colspan="2" class="sub">Share Buttons
					
					</td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold" valign="top">Social Networks:<br/><span class="label" style="font-weight: 400">Select and reorder networks that you want to add shortcode. If you wish to include only native social buttons don't select options here.</span></td>
					<td class="essb_general_option"><ul id="networks-sortable"><?php essb_shortcode_checkbox_network_selection(); ?></ul></td>
				</tr>
				<tr class="even table-border-bottom">
					<td class="bold" valign="top">Customize Button Texts:<br/><span class="label" style="font-weight: 400">Select and reorder networks that you want to add shortcode. If you wish to include only native social buttons don't select options here.</span></td>
					<td class="essb_general_option"><ul id="networks-sortable22"><?php essb_shortcode_text_change(); ?></ul></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Include Share Counters:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_counters"/></td>
				</tr>
				<tr class="even table-border-bottom">
					<td class="bold">Counter Position:</td>
					<td class="essb_general_option">
						<select class="input-element" name="essb_counter_pos">
							<option value="left">Left</option>
							<option value="right">Right</option>
							<option value="inside">Inside</option>
							<option value="hidden">Hidden</option>
							</select>
					</td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Total Counter Position</td>
					<td class="essb_general_option">
						<select class="input-element" name="essb_total_counter_pos">
							<option value="">Right</option>
							<option value="rightbig">Right Big Numbers Only</option>
							<option value="left">Left</option>
							<option value="leftbig">Left Big Numbers Only</option>
							<option value="hidden">Hidden</option>
							</select>
					</td>
				</tr>				
				<tr class="even table-border-bottom">
					<td class="bold">Hide Network Names:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_hide_names"/>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Do not pop network names when Hide Network Names is active:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_hide_name_forces"/>
				</tr>
				<tr class="even table-border-bottom">
					<td class="bold">Display Texts Above Share/Like Buttons:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_message"/></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Full width share buttons:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_fullwidth"/></td>
				</tr>
				<tr class="even table-border-bottom">
					<td class="bold">Full width share buttons width (number):</td>
					<td class="essb_general_option"><input type="text" name="essb_fullwidth_fix" class="input-element"/></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Fixed width share buttons:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_fixedwidth"/></td>
				</tr>
				<tr class="even table-border-bottom">
					<td class="bold">Fixed width share buttons width (number in pixels without px):</td>
					<td class="essb_general_option"><input type="text" name="essb_fixedwidth_px" class="input-element"/></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Template</td>
					<td class="essb_general_option">
						<select class="input-element" name="essb_template">
							<option value="">Use dafault template from settings</option>
							<option value="default">Default</option>
							<option value="metro">Metro</option>
							<option value="round">Round</option>
							<option value="modern">Modern</option>
							<option value="big">Big</option>
														<option value="metro-retina">Metro (Retina)</option>
														<option value="big-retina">Big (Retina)</option>
														<option value="flat-retina">Flat (Retina)</option>
														<option value="light-retina">Light (Retina)</option>
														<option value="tiny-retina">Tiny (Retina)</option>
														<option value="round-retina">Round (Retina)</option>
														<option value="modern-retina">Modern (Retina)</option>
														</select>
					</td>
				</tr>				
				</table>
				</div>
				<div id="essb-container-2" class="essb-data-container">
			
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<col width="25%" />
				<col width="75%" />
				<tr class="table-border-bottom">
					<td colspan="2" class="sub">Like and Subscribe Buttons</td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Include Native Like Buttons:<br/><span class="label" style="font-size:400;">This option will active native socail buttons from configuration. If you wish to show button which is not active in configuration activete its option below.</span></td>
					<td class="essb_general_option"><input type="checkbox" name="essb_native"/></td>
				</tr>
								<tr class="even table-border-bottom">
					<td class="bold">Show Facebook Like:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_fblike"/></td>
				</tr>
								<tr class="odd table-border-bottom">
					<td class="bold">Show Google +1:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_plusone"/></td>
				</tr>
				
												<tr class="even table-border-bottom">
					<td class="bold">Show Twitter Follow:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_twitter"/></td>
				</tr>
				
												<tr class="odd table-border-bottom">
					<td class="bold">Show vk.com Like:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_vk"/></td>
				</tr>
												<tr class="even table-border-bottom">
					<td class="bold">Show YouTube Subscribe:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_youtube"/></td>
				</tr>
																<tr class="odd table-border-bottom">
					<td class="bold">Show Pinterest Follow:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_pinfollow"/></td>
				</tr>
				<tr class="even table-border-bottom">
					<td class="bold">Show ManagedWP.org:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_show_managedwp"/></td>
				</tr>
				</table>
				</div>
				<div id="essb-container-3" class="essb-data-container">
			
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<col width="25%" />
				<col width="75%" />
				<tr class="table-border-bottom">
					<td colspan="2" class="sub">Additional Display Settings</td>
				</tr>
																<tr class="even table-border-bottom">
					<td class="bold">Display As Sidebar:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_sidebar"/></td>
				</tr>
				<tr class="odd table-border-bottom">
					<td class="bold">Sidebar Position:</td>
					<td class="essb_general_option">
						<select class="input-element" name="essb_sidebar_pos">
							<option value="left">Left</option>
							<option value="right">Right</option>
							<option value="bottom">Bottom</option>
							</select>
					</td>
					</tr>
					<tr class="even table-border-bottom">
					<td class="bold">Display As Popup:</td>
					<td class="essb_general_option"><input type="checkbox" name="essb_popup"/></td>
				</tr>
					<tr class="odd table-border-bottom">
					<td class="bold">Popup display after (sec):</td>
					<td class="essb_general_option"><input type="text" name="essb_popafter" class="input-element"/></td>
				</tr>
				</table>
				</div>
				<div id="essb-container-4" class="essb-data-container">
			
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<col width="25%" />
				<col width="75%" />
									<tr class="table-border-bottom">
					<td colspan="2" class="sub">Custom Share and Like URL's</td>
				</tr>
	<tr class="even table-border-bottom">
					<td class="bold">Share URL:</td>
					<td class="essb_general_option"><input type="text" name="essb_url" class="input-element stretched"/></td>
				</tr>
	<tr class="odd table-border-bottom">
					<td class="bold">Share Message:</td>
					<td class="essb_general_option"><input type="text" name="essb_text" class="input-element stretched"/></td>
				</tr>
	<tr class="even table-border-bottom">
					<td class="bold">Share Image (Facebook & Pinterest only):</td>
					<td class="essb_general_option"><input type="text" name="essb_image" class="input-element stretched"/></td>
				</tr>
	<tr class="odd table-border-bottom">
					<td class="bold">Share Description (Facebook & Pinterest only):</td>
					<td class="essb_general_option"><input type="text" name="essb_description" class="input-element stretched"/></td>
				</tr>
	<tr class="even table-border-bottom">
					<td class="bold">Facebook Like URL:</td>
					<td class="essb_general_option"><input type="text" name="essb_fblike" class="input-element stretched"/></td>
				</tr>				
	<tr class="odd table-border-bottom">
					<td class="bold">Google +1 URL:</td>
					<td class="essb_general_option"><input type="text" name="essb_plusone" class="input-element stretched"/></td>
				</tr>				
				</table>
				
			</div>
		</div>
	</div>
		</form>
	</div>


<script type="text/javascript">

jQuery(document).ready(function(){
    jQuery('#networks-sortable').sortable();
    essb_option_activate('1');
});

</script>
