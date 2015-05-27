<?php

$msg = "";

$cmd = isset ( $_POST ["cmd"] ) ? $_POST ["cmd"] : "";

if ($cmd == "update") {
	
	$options = $_POST ['general_options'];	
	
	if (!isset($options['customizer_is_active'])) {
		$options['customizer_is_active'] = 'false';
	}
	if (!isset($options['activate_fanscounter_customizer'])) {
		$options['activate_fanscounter_customizer'] = 'false';
	}
	
	//customizer_remove_bg_hover_effects
	if (!isset($options['customizer_remove_bg_hover_effects'])) {
		$options['customizer_remove_bg_hover_effects'] = 'false';
	}
	
	$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	foreach ($options as $k => $v) {
		$current_options[$k] = $v;
	}
	
	update_option ( EasySocialShareButtons::$plugin_settings_name, $current_options );
	
	$msg = __ ( "Settings are saved", ESSB_TEXT_DOMAIN );
}

global $color_fields;
$color_fields = array();

function essb_customizer_create_color_field($field) {
	global $color_fields;
	
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options [$field] ) ? $options [$field] : '';
		$exist = stripslashes ( $exist );
	
		echo '<input id="'.$field.'" type="text" name="general_options['.$field.']" value="' . $exist . '" class="input-element stretched" data-default-color="' . $exist . '" />';
	
	}
	
	array_push($color_fields, $field);
}

function essb_customizer_create_icon_field($field) {
	
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options [$field] ) ? $options [$field] : '';
		$exist = stripslashes ( $exist );
	
		echo '<input id="'.$field.'" type="text" name="general_options['.$field.']" value="' . $exist . '" class="input-element stretched" />';
	
	}	
}

function essb_customizer_create_css_field() {
	global $color_fields;

	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['customizer_css'] ) ? $options ['customizer_css'] : '';
		$exist = stripslashes ( $exist );

		echo '<textarea	name="general_options[customizer_css]" id="customizer_css_textarea" class="input-element stretched" rows="20" style="font-size: 12px;">'.$exist.'</textarea>';
		

	}
}

function essb_customizer_for_fanscounter() {
	global $essb_fans;
	if (!isset($essb_fans)) {
		$essb_fans = EasySocialFansCounter::get_instance ();
		$essb_fans->version = ESSB_VERSION;
	}
	
	$cnt = 0;
	foreach ($essb_fans->essb_supported_items as $single) {
		$css = (($cnt % 2) ==0 ) ? "even" : "odd";
		echo '<tr class="'.$css.' table-border-bottom">';
		echo '<td class="bold">'.$single. ' '.__('color:', ESSB_TEXT_DOMAIN).'<br />
		<span class="label" style="font-weight: 400;">'.__('Replace', ESSB_TEXT_DOMAIN).' '.__('button color or background color (depeneds on template)', ESSB_TEXT_DOMAIN).'
		</span></td>';
		echo '<td class="essb_options_general">';
		essb_customizer_create_color_field('fanscustomizer_'.$single.'_color');
		echo '</td>';
		echo '</tr>';
	}
}

function essb_customizer_for_networks() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	
	$cnt = 0;
	
	if (is_array ( $options )) {
		foreach ( $options ['networks'] as $k => $v ) {
							
			$network_name = isset ( $v [1] ) ? $v [1] : $k;
				
			echo '<tr><td colspan="2" class="sub3">'.$network_name.'</td></tr>';
			
			echo '<tr class="even table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' '.__('background color:', ESSB_TEXT_DOMAIN).'<br />
								<span class="label" style="font-weight: 400;">'.__('Replace', ESSB_TEXT_DOMAIN).' '.$v[1].' '.__('button background color', ESSB_TEXT_DOMAIN).'
							</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_color_field('customizer_'.$k.'_bgcolor');
			echo '</td>';
			echo '</tr>';
			
			echo '<tr class="odd table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' text color:<br />
								<span class="label" style="font-weight: 400;">'.__('Replace', ESSB_TEXT_DOMAIN).' '.$v[1].' button text color
							</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_color_field('customizer_'.$k.'_textcolor');
			echo '</td>';
			echo '</tr>';
				
			echo '<tr class="even table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' hover color:<br />
								<span class="label" style="font-weight: 400;">'.__('Replace', ESSB_TEXT_DOMAIN).' '.$v[1].' button hover color
							</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_color_field('customizer_'.$k.'_hovercolor');
			echo '</td>';
			echo '</tr>';
			
			echo '<tr class="odd table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' hover text color:<br />
								<span class="label" style="font-weight: 400;">'.__('Replace', ESSB_TEXT_DOMAIN).' '.$v[1].' button hover text color
							</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_color_field('customizer_'.$k.'_hovertextcolor');
			echo '</td>';
			echo '</tr>';
					
			echo '<tr class="even table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' icon:<br />
								<span class="label" style="font-weight: 400;">'.__('Replace', ESSB_TEXT_DOMAIN).' '.$v[1].' button icon
							</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_icon_field('customizer_'.$k.'_icon');
			echo '</td>';
			echo '</tr>';

			echo '<tr class="odd table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' icon background size:<br />
			<span class="label" style="font-weight: 400;">For Retina temaplates for best performance you need to provide images with resolution 42px x 42px and in this field you need to set 21px 21px
			</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_icon_field('customizer_'.$k.'_iconbgsize');
			echo '</td>';
			echo '</tr>';

			echo '<tr class="even table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' on hover icon:<br />
								<span class="label" style="font-weight: 400;">Replace '.$v[1].' button hover icon
							</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_icon_field('customizer_'.$k.'_hovericon');
			echo '</td>';
			echo '</tr>';

					echo '<tr class="odd table-border-bottom">';
			echo '<td class="bold">'.$network_name. ' icon background size:<br />
			<span class="label" style="font-weight: 400;">For Retina temaplates for best performance you need to provide images with resolution 42px x 42px and in this field you need to set 21px 21px
			</span></td>';
			echo '<td class="essb_options_general">';
			essb_customizer_create_icon_field('customizer_'.$k.'_hovericonbgsize');
			echo '</td>';
			echo '</tr>';
		}
	}	
}

function essb_customizer_is_active() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['customizer_is_active'] ) ? $options ['customizer_is_active'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="customizer_is_active" type="checkbox" name="general_options[customizer_is_active]" value="true" ' . $is_checked . ' /></p>';

	}
}

function essb_customizer_remove_bg_hover_effects() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
		$exist = isset ( $options ['customizer_remove_bg_hover_effects'] ) ? $options ['customizer_remove_bg_hover_effects'] : 'false';

		$is_checked = ($exist == 'true') ? ' checked="checked"' : '';
		echo '<p style="margin: .2em 5% .2em 0;"><input id="customizer_remove_bg_hover_effects" type="checkbox" name="general_options[customizer_remove_bg_hover_effects]" value="true" ' . $is_checked . ' /></p>';

	}
}

?>

<div class="wrap">
	
	<?php
	
	if ($msg != "") {
		echo '<div class="updated" style="padding: 10px;">' . $msg . '</div>';
	}
	
	?>
	
		<form name="general_form" method="post"
		action="admin.php?page=essb_settings&tab=customizer"
		enctype="multipart/form-data">
		<input type="hidden" id="cmd" name="cmd" value="update" />
		<input type="hidden" id="section" name="section" value="" />
		
		<div class="essb-options">
			<div class="essb-options-header" id="essb-options-header">
				<div class="essb-options-title">
					Style Settings<br />
					<span class="label" style="font-weight: 400;"><a
						href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo"
						target="_blank" style="text-decoration: none;">Easy Social Share Buttons for WordPress version <?php echo ESSB_VERSION; ?></a></span>
				</div>		
		<?php echo '<a href="http://support.creoworx.com" target="_blank" text="' . __ ( 'Need Help? Click here to visit our support center', ESSB_TEXT_DOMAIN ) . '" class="button">' . __ ( 'Support Center', ESSB_TEXT_DOMAIN ) . '</a>'; ?>				
		<?php echo '<input type="Submit" name="Submit" value="' . __ ( 'Update Settings', ESSB_TEXT_DOMAIN ) . '" class="button-primary" />'; ?>
	</div>
			<div class="essb-options-sidebar">
				<ul class="essb-options-group-menu">
					<li id="essb-menu-1" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('1'); return false;">Color Customization</a></li>
					<li id="essb-menu-3" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('3'); return false;">Fans Counter Color Customization</a></li>
					<li id="essb-menu-2" class="essb-menu-item"><a href="#"
						onclick="essb_option_activate('2'); return false;">Additional CSS</a></li>

				</ul>
			</div>
			<div class="essb-options-container" style="min-height: 450px;">
				<div id="essb-container-1" class="essb-data-container">


					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" /><tr>
							<td colspan="2" class="sub"><?php _e('Color Customization', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold">Activate color customizer:<br/><span class="label" style="font-weight: 400;">Color customizations will not be included unless you activate this option. You are able to activate customization on specific post/pages even if this option is not set to active.
							</span></td>
							<td class="essb_options_general"><?php essb_customizer_is_active(); ?>
						</td>
						</tr>
						
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">All networks color options</td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Background color:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Replace all buttons background color
							</span></td>
							<td class="essb_general_options"><?php essb_customizer_create_color_field('customizer_bgcolor');?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Text color:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Replace all buttons text color
							</span></td>
							<td class="essb_general_options"><?php essb_customizer_create_color_field('customizer_textcolor');?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td valign="top" class="bold"><?php _e('Hover color:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Replace all buttons hover background color
							</span></td>
							<td class="essb_general_options"><?php essb_customizer_create_color_field('customizer_hovercolor');?></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Hover text color:', ESSB_TEXT_DOMAIN); ?><br />
								<span class="label" style="font-weight: 400;">Replace all buttons hover text color
							</span></td>
							<td class="essb_general_options"><?php essb_customizer_create_color_field('customizer_hovertextcolor');?></td>
						</tr>
											<tr class="even table-border-bottom">
							<td class="bold">Remove effects applied from theme on hover:
							</td>
							<td class="essb_options_general"><?php essb_customizer_remove_bg_hover_effects(); ?>
						</td>
						</tr>
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">Single network color options</td>
						</tr>
<?php essb_customizer_for_networks(); ?>
					</table>
				</div>
				<div id="essb-container-2" class="essb-data-container">
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Additional CSS Customization', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							
							<td colspan="2">
							<?php essb_customizer_create_css_field(); ?>
							</td>
						</tr>

					</table>
				</div>
		<div id="essb-container-3" class="essb-data-container">


					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" /><tr>
							<td colspan="2" class="sub"><?php _e('Fans Counter Color Customization', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr class="even table-border-bottom">
							<td class="bold">Activate fans counter color customizer:<br/><span class="label" style="font-weight: 400;">Color customizations will not be included unless you activate this option. You are able to activate customization on specific post/pages even if this option is not set to active.
							</span></td>
							<td class="essb_options_general"><?php ESSB_Settings_Helper::drawCheckboxField('activate_fanscounter_customizer'); ?>
						</td>
						</tr>
						
						<tr class="table-border-bottom">
							<td colspan="2" class="sub2">Single network color options</td>
						</tr>
<?php essb_customizer_for_fanscounter(); ?>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="colorpicker"></div>


<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#networks-sortable').sortable();
    <?php $section = isset($_POST['section']) ? $_POST['section'] : '1'; if ($section == '') { $section = '1'; } ?>
    essb_option_activate('<?php echo $section; ?>');    
});



jQuery(document).ready(function($){
    //$('#customizer_bgcolor').wpColorPicker();

    <?php 
    
    foreach ($color_fields as $single) {
    	print "$('#".$single."').wpColorPicker();";
    }
    
    ?>
});


</script>