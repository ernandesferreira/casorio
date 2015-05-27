<?php

class ffAssetsIncludingFactory extends ffFactoryAbstract {
	
/******************************************************************************/
/* LESS OBJECTS
/******************************************************************************/
	
	/**
	 * 
	 * @var ffLessUserSelectedColorsDataStorage
	 */
	private $_lessUserSelectedColorsDataStorage = null;
	
	public function getLessUserSelectedColorsDataStorage() {
		if( $this->_lessUserSelectedColorsDataStorage == null ) {
			$this->_getClassloader()->loadClass('ffLessUserSelectedColorsDataStorage');
			
			$this->_lessUserSelectedColorsDataStorage = new ffLessUserSelectedColorsDataStorage(
				ffContainer::getInstance()->getDataStorageFactory()->createDataStorageOptionsPostType_NamespaceFacade(),
				ffContainer::getInstance()->getLibManager()->createUserColorLibrary()
			);
		}
		
		return $this->_lessUserSelectedColorsDataStorage;
	}
	
	
	/**
	 * 
	 * @var ffLessManager
	 */
	private $_lessManager = null;
	
	public function getLessManager() {

		if( $this->_lessManager == null ) {
			$this->_getClassloader()->loadClass('ffLessManager');
			$this->_getClassloader()->loadClass('ffOneLessFileFactory');
			$this->_getClassloader()->loadClass('ffOneLessFile');
			
			$oneLessFileFactory = new ffOneLessFileFactory( $this->_getClassloader() );
			
			$this->_lessManager = new ffLessManager(	
					$oneLessFileFactory,
					ffContainer::getInstance()->getFileSystem(),
					ffContainer::getInstance()->getDataStorageCache(),
					ffContainer::getInstance()->getLessParser(),
					$this->getLessUserSelectedColorsDataStorage()
			);
		}
		
		
		return $this->_lessManager;
	}
	
	private $_systemColorLibrary = null;
	
	public function getLessSystemColorLibrary() {
		if( $this->_systemColorLibrary == null ) {
			$this->_getClassloader()->loadClass('ffLessSystemColorLibrary');
			
			$this->_systemColorLibrary = new ffLessSystemColorLibrary( ffContainer::getInstance()->getDataStorageFactory()->createDataStorageOptionsPostType_NamespaceFacade() );
		}

		return $this->_systemColorLibrary;
	}
	
	private $_systemColorLibraryBackend = null;
	
	public function getLessSystemColorLibraryBackend() {
		if( $this->_systemColorLibraryBackend == null ) {
			$this->_getClassloader()->loadClass('ffLessSystemColorLibraryBackend');
				
			$this->_systemColorLibraryBackend = new ffLessSystemColorLibraryBackend( ffContainer::getInstance()->getDataStorageFactory()->createDataStorageOptionsPostType_NamespaceFacade() );
		}
	
		return $this->_systemColorLibraryBackend;
	}
	
	private $_systemColorLibraryManager = null;
	
	public function getLessSystemColorLibraryManager() {
		if( $this->_systemColorLibraryManager == null ) {
			$this->_getClassloader()->loadClass('ffLessSystemColorLibraryManager');
			
			$this->_systemColorLibraryManager = new ffLessSystemColorLibraryManager(
				$this->getLessManager(),
				$this->getLessSystemColorLibrary(),
				$this->getLessSystemColorLibraryBackend(),
				$this->getLessVariableParser(),
				$this->getLessUserSelectedColorsDataStorage()
			);
			
		}
		
		return $this->_systemColorLibraryManager;
	}
	
	public function getLessVariableParser() {
		$this->_getClassloader()->loadClass('ffLessVariableParser');
		
		return new ffLessVariableParser( ffContainer::getInstance()->getLessParser() );
	}
}