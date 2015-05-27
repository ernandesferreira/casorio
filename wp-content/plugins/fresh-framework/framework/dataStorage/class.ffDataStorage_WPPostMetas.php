<?php

class ffDataStorage_WPPostMetas extends ffDataStorage {
	protected function _maxOptionNameLength() { return 255; }
	
	protected function _setOption( $namespace /* = Post ID */, $name, $value ) {
		return $this->_getWPLayer()->update_post_meta($namespace, $name, $value);
	}
	protected function _getOption( $namespace /* = Post ID */, $name, $default=null ) {
		return $this->_getWPLayer()->get_post_meta( $namespace, $name, true );
	}
	protected function _deleteOption( $namespace /* = Post ID */, $name ) {
		return $this->_getWPLayer()->delete_post_meta($namespace, $name);
	}

	public function setOption($namespace, $name, $value ) {
		return $this->_setOption($namespace, $name, $value);
	}

	public function getOption( $namespace, $name, $default = null ) {
		return $this->_getOption($namespace, $name, $default );
	}

	public function deleteOption( $namespace, $name ) {
		return $this->_deleteOption($namespace, $name);
	}

}