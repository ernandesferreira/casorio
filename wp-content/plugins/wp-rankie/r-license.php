<?php
function wp_rankie_admin_notice() {
	
	$licenseactive=get_option('wp_rankie_license_active','');
	
	if(trim($licenseactive) == ''){
		?>
			<div class="error">
		        <p>Wordpress Rankie is ready to go. Please update your license <a href="<?php echo admin_url('admin.php?page=wp_rankie_settings') ?>">here</a>.</p>
		    </div>
	    <?php
	}

}
add_action( 'admin_notices', 'wp_rankie_admin_notice' );

