<?php

function ff_initFramework() {
	remove_action('admin_notices', 'ff_plugin_fresh_framework_notification');

	$configuration = array(
		'less_and_scss_compilation' => true,
		'style_minification' => false,
		'script_minification' => false,
		'minificator' => array(
								'cache_files_max_old' => 60*60*24*7*2, // 14 days
								'cache_check_interval' => 60*60*24*3, // 3 days
							),
			
		//'freshface-server-upgrading-url' => 'http://update.freshface.net/updater-server/get-info.php',
		'freshface-server-upgrading-url' => 'http://files.freshcdn.net/get-info.php',
	);
	
	require_once FF_FRAMEWORK_DIR . '/framework/developingTools.php';
	require_once FF_FRAMEWORK_DIR . '/framework/fileSystem/class.ffClassLoader.php';

	
	$classLoader = new ffClassLoader();
	
	$classLoader->loadClass('ffBasicObject');
	$classLoader->loadClass('ffContainer');
	$classLoader->loadClass('ffFactoryAbstract');
	$classLoader->loadClass('ffFactoryCenterAbstract');
	$classLoader->loadClass('ffPluginAbstract');
	$classLoader->loadClass('ffPluginContainerAbstract');
	
	
	$container = ffContainer::getInstance();
	
	$container->setConfiguration($configuration);
	$container->setClassloader($classLoader);
	
	do_action('ff_framework_initalized');
	
	// preventing to running framework when only making updates
	if( FF_FRAMEWORK_IS_INSTALLED ) {
		$container->getFramework()->run();
	}
	
	ff_tiny_mce_test();
}

ff_initFramework();


function ff_tiny_mce_test() {
	return;
	
	$fwc = ffContainer::getInstance();
	
	$fwc->getClassLoader()->loadClass('ffShortcodeObjectBasic');
	$fwc->getClassLoader()->loadClass('ffTestingShortcode');
	
	Debugger::timer('test');
	for( $i = 0; $i < 1000; $i++ ) {
	$sc = new ffTestingShortcode();
	}
	var_dump( debugger::timer('test'));
	die();
	
}

//add_action( 'init', 'wptuts_buttons' );
function wptuts_buttons() {
	add_filter( "mce_external_plugins", "wptuts_add_buttons" );
	add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}
function wptuts_add_buttons( $plugin_array ) {
	$plugin_array['wptuts'] = FF_FRAMEWORK_URL . '/framework/shortcodes/wptuts-plugin.js';
	return $plugin_array;
}
function wptuts_register_buttons( $buttons ) {
	array_push( $buttons, 'dropcap', 'showrecent' ); // dropcap', 'recentposts
	return $buttons;
}