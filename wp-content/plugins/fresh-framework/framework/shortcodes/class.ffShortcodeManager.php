<?php

class ffShortcodeManager extends ffBasicObject {
	
	/**
	 * @var here are stored all shortcodes
	 */
	private $_addedShortcodes = array();
	
	/**
	 * 
	 * @var ffShortcodeFactory
	 */
	private $_shortcodeFactory = array();
	
	/**
	 * 
	 * @var ffWPLayer
	 */
	private $_WPLayer = null;
	
	public function __construct( ffShortcodeFactory $shortcodeFactory ) {
		$WPLayer = ffContainer::getInstance()->getWPLayer();
		$this->_setShortcodeFactory($shortcodeFactory);
		
		//$WPLayer->add_shortcode('pokusny_sc', array( $this, 'shortcodeProceeding') );
	}
	
	public function addShortcode( ffIShortcode $shortcode ) {
		$this->_addedShortcodes[ $shortcode->getSlug() ] = $shortcode;
	}
	
	
	
	public function shortcodeProceeding( $atts, $content, $tag ) {
		var_dump( $atts );
		var_dump( $content );
		var_dump( $tag );
		//echo 'bbb';
	}
	
	/**
	 *
	 * @return ffWPLayer
	 */
	protected function _getWPLayer() {
		return $this->_WPLayer;
	}
	
	/**
	 *
	 * @param ffWPLayer $WPLayer        	
	 */
	protected function _setWPLayer($WPLayer) {
		$this->_WPLayer = $WPLayer;
		return $this;
	}
	/**
	 * 
	 * @return ffShortcodeFactory:
	 */
	protected function _getShortcodeFactory() {
		return $this->_shortcodeFactory;
	}
	protected function _setShortcodeFactory( ffShortcodeFactory $shortcodeFactory) {
		$this->_shortcodeFactory = $shortcodeFactory;
		return $this;
	}
	
	
	

}