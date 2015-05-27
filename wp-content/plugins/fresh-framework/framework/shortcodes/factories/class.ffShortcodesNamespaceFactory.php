<?php

class ffShortcodesNamespaceFactory extends ffFactoryAbstract {
	
	public function getShortcodeManager() {
		$this->_getClassloader()->loadClass('ffShortcodeManager');
		return new ffShortcodeManager( $this->getShortcodeFactory() );
	}
	
	public function getShortcodeFactory() {
		$this->_getClassloader()->loadClass('ffShortcodeFactory');
		return new ffShortcodeFactory( $this->_getClassloader() );
	}
}