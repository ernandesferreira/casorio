<?php

class ffOptionsPrinterElementsAndComponentsBasic extends ffBasicObject {
	private $_cssClasses = array();
	
	private function _prepareClasses( $elementOrOption ) {
		$classes = $elementOrOption->getParam('class');
		
		if( $classes == null ) {
			return false;
		}
		
		if( !is_array( $classes ) ) {
			$classes = array( $classes );
		}
		
		$this->_cssClasses = $classes;
	}
	
	
	protected function _getClassesArray() {
		return $this->_cssClasses;
	}
	
	protected function _getClassesString() {
		return implode(' ',$this->_cssClasses);
	}
	
	public function printOption( ffOneOption $oneOption, $nameRoute, $idRoute) {
		$this->_prepareClasses( $oneOption );
		$this->_printOption( $oneOption, $nameRoute, $idRoute );
	}
	
	public function printElement( ffOneElement $element ) {
		$this->_prepareClasses( $element );
		$this->_printElement( $element );
	}
}