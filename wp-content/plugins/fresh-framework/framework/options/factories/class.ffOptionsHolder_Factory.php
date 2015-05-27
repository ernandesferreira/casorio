<?php

class ffOptionsHolder_Factory extends ffFactoryAbstract {
	
	/**
	 * 
	 * @var ffOneStructure_Factory
	 */
	private $_oneStructureFactory = null;
	
	public function __construct( ffClassLoader $classLoader, ffOneStructure_Factory $oneStructureFactory ) {
		$this->_setOnestructurefactory($oneStructureFactory);
		parent::__construct( $classLoader );
	}
	
	public function createOptionsHolder( $className ) {
		$this->_getClassloader()->loadClass('ffIOptionsHolder');
		$this->_getClassloader()->loadClass('ffOptionsHolder');
		$this->_getClassloader()->loadClass( $className );
		$optionsHolder = new $className( $this->_getOnestructurefactory(), $this );
		return $optionsHolder;
	}

	/**
	 * @return ffOneStructure_Factory
	 */
	protected function _getOnestructurefactory() {
		return $this->_oneStructureFactory;
	}
	
	/**
	 * @param ffOneStructure_Factory $oneStructureFactory
	 */
	protected function _setOnestructurefactory(ffOneStructure_Factory $oneStructureFactory) {
		$this->_oneStructureFactory = $oneStructureFactory;
		return $this;
	}
	
}