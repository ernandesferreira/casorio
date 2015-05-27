<?php
/**
 * ----------------------------------------------------------------------
 * Rotating tweets plugin integration
 * http://wordpress.org/plugins/rotatingtweets/
 */

if(is_plugin_active('rotatingtweets/rotatingtweets.php')){
	function lbmn_deregister_rotatingtweets_style() {
		wp_deregister_style( 'rotatingtweets' );
	}
	add_action( 'wp_enqueue_scripts', 'lbmn_deregister_rotatingtweets_style', 101, 1 ); // default priority is 20
}