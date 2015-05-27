<?php

class ffModalWindowManagerLibraryColorPicker extends ffModalWindowManager {
	protected function _initialize() {
		$this->_setId('ff-modal-library-color-picker');
		$this->_setModalWindowClass('ff-modal-library ff-modal-library-color-picker');
		$this->addCssClass( ffModalWindowManager::CLASS_HIDE_MENU);
		$this->addCssClass( ffModalWindowManager::CLASS_HIDE_ROUTER);
		//$this->addCssClass( ffModalWindowManager::CLASS_HIDE_MODAL_WINDOW );
	}
}