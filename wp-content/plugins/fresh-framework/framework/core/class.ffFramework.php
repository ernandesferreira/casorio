<?php

class ffFramework extends ffBasicObject {
/******************************************************************************/
/* VARIABLES AND CONSTANTS
/******************************************************************************/
	/**
	 * 
	 * @var ffContainer
	 */
	
	private $_container = null;
	
	
	/**
	 * 
	 * @var ffPluginLoader
	 */
	private $_pluginLoader = null;
	
	/**
	 * 
	 * @var array[ffPluginAbstract]
	 */
	private $_activePlugins = null;
	
/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/
	public function __construct( ffContainer $container, ffPluginLoader $pluginLoader ) {
		$this->_setContainer( $container );
		$this->_setPluginloader( $pluginLoader );
	}
	
	public function run() {
		$this->_activePlugins = $this->_getPluginloader()->createPluginClasses();
		
		$this->_getContainer()->getWPUpgrader();
		$this->_frameworkRun();
		if( $this->_getContainer()->getWPLayer()->is_ajax() ) {
			$this->_isAjaxRequest();
		}
	}
/******************************************************************************/
/* PRIVATE FUNCTIONS
/******************************************************************************/
	private function _frameworkRun() {
		$this->_getContainer()->getLessScssCompiler();
		$this->_getContainer()->getDataStorageFactory()->createDataStoragePostTypeRegistrator()->registerOptionsPostType();
		
	}
	
	private function _isAjaxRequest() {
		$this->_getContainer()->getAjaxDispatcher()->hookActions();
		$this->_getContainer()->getModalWindowAjaxManager()->hookAjax();
		$this->_getContainer()->getOptionsFactory()->createOptionsPrinterDataboxGenerator()->hookAjax();
	}
/******************************************************************************/
/* SETTERS AND GETTERS
/******************************************************************************/	
	
	/**
	 * @return ffContainer
	 */
	protected function _getContainer() {
		return $this->_container;
	}
	
	/**
	 * @param ffContainer $_container
	 */
	protected function _setContainer(ffContainer $container) {
		$this->_container = $container;
		return $this;
	}

	/**
	 * @return ffPluginLoader
	 */
	protected function _getPluginloader() {
		return $this->_pluginLoader;
	}
	
	/**
	 * @param ffPluginLoader $_pluginLoader
	 */
	protected function _setPluginloader(ffPluginLoader $pluginLoader) {
		$this->_pluginLoader = $pluginLoader;
		return $this;
	}
	
	
}