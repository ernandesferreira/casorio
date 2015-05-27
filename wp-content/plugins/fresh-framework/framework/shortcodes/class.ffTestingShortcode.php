<?php

class ffTestingShortcode extends ffShortcodeObjectBasic {
	protected function _initialise() {
		$this->_addTag('test_pico');
	}
	
	public function addbb() {
		$a = 0;
		for( $x = 0; $x < 999999; $x++ ) {
			$a++;
		}
	}
	
	public function addbbx() {
		$a = 0;
		for( $x = 0; $x < 999999; $x++ ) {
			$a++;
		}
	}
	
	public function addabb() {
		$a = 0;
		for( $x = 0; $x < 999999; $x++ ) {
			$a++;
		}
	}
	
	
	public function addbdb() {
		$a = 0;
		for( $x = 0; $x < 999999; $x++ ) {
			$a++;
		}
	}
	
	public function adqdbb() {
		$a = 0;
		for( $x = 0; $x < 999999; $x++ ) {
			$a++;
		}
	}
	
	public function addddbb() {
		$a = 0;
		for( $x = 0; $x < 999999; $x++ ) {
			$a++;
		}
	}
}