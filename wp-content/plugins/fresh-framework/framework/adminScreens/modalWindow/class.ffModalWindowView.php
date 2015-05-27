<?php

abstract class ffModalWindowView extends ffModalWindowBasicObject {
	
	private $_viewSlug = null;
	
	private $_viewName = null;
	
	private $_wrappedInnerContent = true;
	
	/**
	 * 
	 * @var ffWPLayer
	 */
	private $_WPLayer = null;
	
	public function proceedAjax( ffAjaxRequest $request ) {
		
	}
	
	public function getSlug() {
		return $this->_viewSlug;
	}
	
	public function getName() {
		return $this->_viewName;
	}
	
	public function getWrappedInnerContent() {
		return $this->_wrappedInnerContent;
	}
	
	
	public function render() {
		$this->_requireAssets();
		$this->_render();
	}
	
	public function printToolbar() {}
	
	protected function _requireAssets() {}
	abstract protected function _render();
	
	protected function _initialize() {
		
	}

	protected function _getViewSlug() {
		return $this->_viewSlug;
	}
	
	protected function _setWrappedInnerContent( $wrappedInnerContent ) {
		$this->_wrappedInnerContent = $wrappedInnerContent;
	}
	
	protected function _setViewSlug($viewSlug) {
		
		$this->_viewSlug = $this->_getWPLayer()->sanitize_title($viewSlug);
		return $this;
	}
	
	protected function _getViewName() {
		return $this->_viewName;
	}
	
	protected function _setViewName($viewName) {
		$this->_viewName = $viewName;
		$this->_setViewSlug( $viewName );
		return $this;
	}
}