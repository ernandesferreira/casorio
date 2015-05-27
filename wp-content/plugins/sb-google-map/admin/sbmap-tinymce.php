<?php
/*	 TinyMCE Shortcode Custom Button Settings
 *   Version: 1.0
 *   Author: SB Themes
 *   Profile: http://codecanyon.net/user/sbthemes?ref=sbthemes
 */

/**
 * tinymce external plugin js file
 */
function sbmap_add_tinymce_plugin($plugin_array) {
	$plugin_array['sbmapshortcodes'] = SB_DIR_URL.'/assets/js/sb-map-tinymce.js';
	return $plugin_array;
}

/**
 * tinymce add buttons
 */
function sbmap_add_tinymce_button($buttons) {
	array_push($buttons, 'sbmapshortcodes');
	return $buttons;
}

/**
 * Adding tinymce
 */
function sbmap_add_tinymce() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
		return;
	add_filter('mce_external_plugins', 'sbmap_add_tinymce_plugin');
	add_filter('mce_buttons', 'sbmap_add_tinymce_button');
}
add_action('admin_head', 'sbmap_add_tinymce');


function sbmap_print_shortcodes_in_js() {
	
	global $wpdb;
	$shortcodes = $wpdb->get_results("select * from ".$wpdb->prefix."sb_google_map order by map_id desc");
	?>
	<style type="text/css">.mce-i-sb_google_map { background:url(<?php echo SB_IMG_DIR_URL; ?>/tinumce-map-icon.png) no-repeat !important; }</style>
	<script type="text/javascript">
		var sbmap_shortcodes = [];
		<?php if($shortcodes) {
			$shortcode_count = 0;
			foreach($shortcodes as $shortcode) { ?>
				sbmap_shortcodes[<?php echo $shortcode_count; ?>] = {
					'text'		: '<?php echo $shortcode->map_id.': '.$shortcode->map_title; ?>',
					'onclick'	: function() {
						tinymce.execCommand('mceInsertContent', false, '[SBMAP ID="<?php echo $shortcode->map_id; ?>"]');
					}
					
				}
		<?php $shortcode_count++;
			}
		}?>
	</script>
	<?php
}
add_action('admin_head', 'sbmap_print_shortcodes_in_js');