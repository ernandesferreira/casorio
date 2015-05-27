<?php 

class ffLessSystemColorLibraryBackend extends ffBasicObject {

################################################################################
# CONSTANTS
################################################################################
	const COLOR_BACKEND_NAMESPACE = 'color_namespace_backend';
################################################################################
# PRIVATE OBJECTS
################################################################################
	/**
	 * 
	 * @var ffDataStorage_OptionsPostType_NamespaceFacade
	 */
	private $_options = null;
################################################################################
# PRIVATE VARIABLES	
################################################################################	

################################################################################
# CONSTRUCTOR
################################################################################	
	public function __construct( ffDataStorage_OptionsPostType_NamespaceFacade $options ) {
		$this->_setOptions($options);	
		$this->_getOptions()->setNamespace(ffLessSystemColorLibraryBackend::COLOR_BACKEND_NAMESPACE);
	}
	
	//public function getColorGroups( $)
	
################################################################################
# ACTIONS
################################################################################
	
################################################################################
# PUBLIC FUNCTIONS
################################################################################	
	public function setColorHash( $hash ) {
		return $this->_setPrivateOption('color_file_hash', $hash);
	}
	public function getColorHash() {
		return $this->_getPrivateOption('color_file_hash');
	}
	
	public function setNewColorList( $colorList ) {
		$this->_getOptions()->setOption('color_list', $colorList);
	}
	
	public function setNewBannedVariables( $bannedVariables ) {
		$this->_getOptions()->setOption('banned_variables', $bannedVariables);
	}
	
	public function getBannedVariable( $variableName = null) {
		//var_Dump( $this->_getOptions()->getOption('banned_variables') );
		
		$bannedVariables = $this->_getOptions()->getOption('banned_variables');
		
		if( $variableName == null ) {
			return $bannedVariables;
		}
		
		if( isset( $bannedVariables[ $variableName ] ) ) {
			return $bannedVariables[ $variableName ];
		} else {
			return array();
		}
	} 
	
	public function getColorList() {
		return $this->_getOptions()->getOption('color_list');
	}
	
################################################################################
# PRIVATE FUNCTIONS
################################################################################
	private function _getPrivateOption( $optionName ) {
		$privateData = $this->_getOptions()->getOption('private_data');
		if( !isset( $privateData[ $optionName ] ) ) {
			return null;
		} else {
			return $privateData[ $optionName ];
		}
	}
	
	private function _setPrivateOption( $optionName, $optionValue ) {
		$privateData = $this->_getOptions()->getOption('private_data');
		$privateData[ $optionName ] = $optionValue;
		$this->_getOptions()->setOption('private_data', $privateData);
	}
################################################################################
# GETTERS AND SETTERS
################################################################################	
	/**
	 *
	 * @return ffDataStorage_OptionsPostType_NamespaceFacade
	 */
	protected function _getOptions() {
		return $this->_options;
	}
	
	/**
	 *
	 * @param ffDataStorage_OptionsPostType_NamespaceFacade $_options
	 */
	protected function _setOptions(ffDataStorage_OptionsPostType_NamespaceFacade $options) {
		$this->_options = $options;
		return $this;
	}
}