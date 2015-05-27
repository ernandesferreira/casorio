<?php
$msg = "";

$cmd = isset ( $_POST ['cmd'] ) ? $_POST ['cmd'] : '';

if ($cmd == "update") {
	$options = $_POST ['general_options'];
	
	if (!isset($options['purchase_code'])) { $options['purchase_code'] = ""; }
	
	$current_options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	$current_options['purchase_code'] = $options['purchase_code'];

	update_option ( EasySocialShareButtons::$plugin_settings_name, $current_options );
	
	$msg = __ ( "Settings are saved", ESSB_TEXT_DOMAIN );
}

function essb_setting_update() {
	$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
	if (is_array ( $options )) {
	
		$exist = isset ( $options ['purchase_code'] ) ? $options ['purchase_code'] : '';
	
		echo '<input id="purchase_code" type="text" name="general_options[purchase_code]" value="' . $exist . '" class="input-element stretched" />';
	
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
		action="admin.php?page=essb_settings&tab=update"
		enctype="multipart/form-data">
		<input type="hidden" id="cmd" name="cmd" value="update" />

		<div class="essb-options">
			<div class="essb-options-header" id="essb-options-header">
				<div class="essb-options-title">
					<?php _e('Automatic Update', ESSB_TEXT_DOMAIN); ?>s<br /> <span class="label"
						style="font-weight: 400;"><a
						href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo"
						target="_blank" style="text-decoration: none;">Easy Social Share Buttons for WordPress version <?php echo ESSB_VERSION; ?></a></span>
				</div>		
<?php echo '<a href="http://support.creoworx.com" target="_blank" text="' . __ ( 'Need Help? Click here to visit our support center', ESSB_TEXT_DOMAIN ) . '" class="button">' . __ ( 'Support Center', ESSB_TEXT_DOMAIN ) . '</a>'; ?>				
		<?php echo '<input type="Submit" name="Submit" value="' . __ ( 'Update Settings', ESSB_TEXT_DOMAIN ) . '" class="button-primary" />'; ?>
	</div>
			<div class="essb-options-sidebar">
				<ul class="essb-options-group-menu">
					<li id="essb-menu-1" class="essb-menu-item active"><a href="#"
						onclick="return false;"><?php _e('Activate Automatic Update', ESSB_TEXT_DOMAIN);?></a></li>
					<li id="essb-menu-2" class="essb-menu-item">
				
				</ul>
			</div>
			<div class="essb-options-container" style="min-height: 450px;">

				<div id="essb-container-1" class="essb-data-container">
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />
						<tr>
							<td colspan="2" class="sub"><?php _e('Activate Automatic Update', ESSB_TEXT_DOMAIN); ?></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><span class="label"><?php _e('A valid license key qualifies you for support and enables automatic updates. A license key may only be used for one <strong>Easy Social Share Buttons for WordPress</strong> installation on one WordPress site at a time. If you previosly activated your license key on another site, then you should deactivate it first or obtain <a href="http://codecanyon.net/item/easy-social-share-buttons-for-wordpress/6394476?ref=appscreo" target="_blank">new license key</a>.', ESSB_TEXT_DOMAIN);?></span></td>
						</tr>
						<tr class="odd table-border-bottom">
							<td valign="top" class="bold"><?php _e('Purchase Code:', ESSB_TEXT_DOMAIN);?><br /><span class="label" style="font-weight: 400;"><a href="<?php echo ESSB_PLUGIN_URL ?>/assets/images/find-item-purchase-code.png" target="_blank">Where can I find my item purchase code?</a></span></td>
							<td class="essb_general_options"><?php essb_setting_update(); ?></td>
						</tr>
					</table>
				</div>

			</div>
		</div>
	</form>
</div>
