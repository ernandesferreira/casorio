<?php

class ffUserColorLibrary extends ffBasicObject {

################################################################################
# CONSTANTS
################################################################################
	const LIBRARY_NAMESPACE = 'color';
	
	const ID_AUTOINCREMENT_VALUE = 'id_autoincrement';
################################################################################
# PRIVATE OBJECTS
################################################################################
	
################################################################################
# PRIVATE VARIABLES	
################################################################################
	/**
	 * 
	 * @var ffColorLibraryItemFactory
	 */
	private $_colorLibraryItemFactory = null;
	
	/**
	 * 
	 * @var ffDataStorage_OptionsPostType_NamespaceFacade
	 */
	private $_dataStorage = null;
	
	private $_currentIdMax = null;
################################################################################
# CONSTRUCTOR
################################################################################	
	public function __construct( ffUserColorLibraryItemFactory $colorLibraryItemFactory, ffDataStorage_OptionsPostType_NamespaceFacade $dataStorage ) {
		$this->_setColorLibraryItemFactory($colorLibraryItemFactory);
		$this->_setDataStorage( $dataStorage );
	}
################################################################################
# ACTIONS
################################################################################
	
################################################################################
# PUBLIC FUNCTIONS
################################################################################
	/**
	 * 
	 * @return array[ffUserColorLibraryItem]
	 */	
	public function getColors() {
		$wholeNamespace = $this->_getDataStorage()->getAllOptionsForNamespaceWithValues();
		unset( $wholeNamespace[ ffUserColorLibrary::ID_AUTOINCREMENT_VALUE ] );
		return $wholeNamespace;
	}
	
	/**
	 * 
	 * @return ffUserColorLibraryItem
	 */
	public function getNewColor() {
		$newColorItem =  $this->_colorLibraryItemFactory->createUserColorLibraryItem();
		$newColorItem->setId( $this->_getNewIdMax() );
		return $newColorItem;
	}
	
	/**
	 * 
	 * @param unknown $colorId
	 * @return ffColorLibraryItem
	 */
	public function getColor( $colorId ) {
		$color = $this->_getDataStorage()->getOption( $colorId );
		return $color;
	}
	
	public function setColor( ffUserColorLibraryItem $color ) {
		
		$this->_getDataStorage()->setOption( $color->getId(), $color);
	}
	
	public function deleteColor( ffUserColorLibraryItem $color ) {
		$this->deleteColorById( $color->getId() );
	}
	
	public function deleteColorById( $id ) {
		$this->_getDataStorage()->deleteOption( $id );
	}
	
	public function getNewId() {
		return $this->_getNewIdMax();
	}
	
################################################################################
# PRIVATE FUNCTIONS
################################################################################

	 private function _getNewIdMax() {
	 	if( $this->_currentIdMax == null ) {
	 		$this->_currentIdMax = $this->_getDataStorage()->getOption( ffUserColorLibrary::ID_AUTOINCREMENT_VALUE, 0);
	 	}
	 	
	 	$this->_currentIdMax++;
	 	
	 	$this->_getDataStorage()->setOption( ffUserColorLibrary::ID_AUTOINCREMENT_VALUE, $this->_currentIdMax );
	 	
	 	return $this->_currentIdMax;
	 	
	 }
################################################################################
# GETTERS AND SETTERS
################################################################################	
	/**
	 *
	 * @return ffColorLibraryItem
	 */
	protected function _getColorLibraryItemFactory() {
		return $this->_colorLibraryItemFactory;
	}
	
	/**
	 *
	 * @param ffColorLibraryItemFactory $colorLibraryItemFactory
	 */
	protected function _setColorLibraryItemFactory(ffUserColorLibraryItemFactory $colorLibraryItemFactory) {
		$this->_colorLibraryItemFactory = $colorLibraryItemFactory;
		return $this;
	}
	
	/**
	 *
	 * @return ffDataStorage_OptionsPostType_NamespaceFacade
	 */
	protected function _getDataStorage() {
		return $this->_dataStorage;
	}
	
	/**
	 *
	 * @param ffDataStorage_OptionsPostType_NamespaceFacade $dataStorage        	
	 */
	protected function _setDataStorage(ffDataStorage_OptionsPostType_NamespaceFacade $dataStorage) {
		$this->_dataStorage = $dataStorage;
		return $this;
	}
	
}