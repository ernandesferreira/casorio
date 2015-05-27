<?php

class ffFileSystem_Factory extends ffFactoryAbstract {
	/**
	 * 
	 * @param ffWPLayer $WPLayer
	 * @return ffFileSystem
	 */
	public function createFileSystem( ffWPLayer $WPLayer ) {
		$this->_getClassloader()->loadClass('ffFileSystem');
		$WPLayer->WP_Filesystem();
		$WPFileSystem = $WPLayer->get_WP_filesystem();
		
		$fileSystem = new ffFileSystem( $WPLayer, $WPFileSystem);
		
		return $fileSystem;
	}
	
	

}