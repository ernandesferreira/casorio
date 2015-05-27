<?php

class ffRequest extends ffBasicObject {
	const ADMIN_SCREEN_VIEW_SLUG = 'view';
	
	public function get( $name ) {
		if( isset( $_GET[ $name ] ) ) {
			return $_GET[ $name ];
		} else {
			return null;
		}
	}
	
	public function post( $name ) {
		if( isset( $_POST[ $name ] ) ) {
			return $this->_stripSlashes($_POST[ $name ]);
		} else {
			return null;
		}
	}
	
	private function _stripSlashes( $value ) {
		return stripslashes_deep( $value );
	}
}