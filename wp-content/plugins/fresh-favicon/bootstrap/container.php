<?php
class ffPluginFreshFaviconContainer extends ffPluginContainerAbstract {
	/**
	 * @var ffPluginFreshFaviconContainer
	 */
	private static $_instance = null;

	const STRUCTURE_NAME = 'ff_fresh_favicon';

	/**
	 * @param ffContainer $container
	 * @param string $pluginDir
	 * @return ffPluginFreshFileEditorContainer
	 */
	public static function getInstance( ffContainer $container = null, $pluginDir = null ) {
		if( self::$_instance == null ) {
			self::$_instance = new ffPluginFreshFaviconContainer($container, $pluginDir);
		}

		return self::$_instance;
	}

	/**
	 * @return ffIconConvertor
	 */
	public function getIconConvertor() {
		$this->_getClassLoader()->loadClass('ffIconConvertor');
		$ffIconConvertor = new ffIconConvertor(
			$this->getFrameworkContainer()->getFileSystem(),
			$this->getFrameworkContainer()->getDataStorageCache()
		);
		return $ffIconConvertor;
	}

	protected function _registerFiles() {
		$pluginDir = $this->_getPluginDir();
		$classLoader =$this->getFrameworkContainer()->getClassLoader();

		$classLoader->addClass('ffAdminScreenFavicon', $pluginDir.'/adminScreens/favicon/class.ffAdminScreenFavicon.php');
		$classLoader->addClass('ffAdminScreenFaviconViewDefault', $pluginDir.'/adminScreens/favicon/class.ffAdminScreenFaviconViewDefault.php');

		$classLoader->addClass('ffOptionsHolderFavicon', $pluginDir.'/core/class.ffOptionsHolderFavicon.php');
		$classLoader->addClass('ffIconConvertor', $pluginDir.'/core/class.ffIconConvertor.php');

	}

}

