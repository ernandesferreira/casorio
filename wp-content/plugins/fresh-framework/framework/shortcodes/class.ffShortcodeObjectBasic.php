<?php

interface ffIShortcode {
	public function getSlug();
}

abstract class ffShortcodeObjectBasic extends ffBasicObject {
	private $_tags = null;
	
	public function __construct() {
		$this->_initialise();
	}
	
	public function getTags() {
		return $this->_tags;
	}
	
	protected function _addTag( $tag ) {
		if( $this->_tags == null ) {
			$this->_tags = array();
		}
		
		$this->_tags[] = $tag;
	}
	
	protected abstract function _initialise();
}