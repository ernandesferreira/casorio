<?php
/**
 * Try to get the value from the data array. In case of failure ( for example
 * we added another option and user didn't saved them yet ) it will re-create
 * the whole option structure and try to get the value from here. If this 
 * won't help too, it will report error.
 * 
 * @author FRESHFACE
 * @since 0.1
 *
 */
class ffOptionsQuery extends ffBasicObject implements Iterator {
/******************************************************************************/
/* VARIABLES AND CONSTANTS
/******************************************************************************/
	private $_iteratorPointer = null;
	
	private $_iteratorValidHolder = null;
	
	private $_data = null;
	
	private $_path = null;
	/**
	 * 
	 * @var ffOptionsArrayConvertor
	 */
	private $_arrayConvertor = null;
	
	/**
	 * 
	 * @var ffIOptionsHolder
	 */
	private $_optionsHolder = null;
	
	private $_optionsStructureHasBeenCompared = false;
	
	private $_hasBeenComparedWithStructure = false;
/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/
	public function __construct( $data, ffIOptionsHolder $optionsHolder = null, ffOptionsArrayConvertor $arrayConvertor = null, $path = null, $optionsStructureHasBeenCompared = false ) {
		$this->_setData($data);
		$this->_setArrayConvertor($arrayConvertor);
		if( $optionsHolder != null ) {
			$this->_setOptionsHolder($optionsHolder);
		}
		$this->_setPath($path);
	}
	
	
	public function getOnlyDataPart( $query, $wrappedInSectionName = true ) {
		$exploded = explode(' ', $query);
		$arrayName = end($exploded );
		if( $wrappedInSectionName ) {
			$toReturn[ $arrayName ] = $this->_get($query);
		} else {
			$toReturn = $this->_get( $query );
		}
		return $toReturn;
		//return $this->_get($query);
	}
	
	public function resetPath() {
		$this->_setPath( null );
	}
	
	/**
	 * 
	 * @param unknown $query
	 * @return ffOptionsQuery
	 */
	public function get( $query ) {
		if( $this->_getPath() !== null ) { 
			$query = $this->_getPath() . ' ' . $query; 
		}
		$result = $this->_get( $query );
		
		if( $result == null ) {
			$this->_compareDataWithStructure();
			$result = $this->_get($query);
		}
		
		
		if( is_array( $result ) ) {
			
			if( $this->_getPath() == null ) {
				
				$result = $this->getNew( $query ); 
				//new ffOptionsQuery( $this->_getData(), $this->_getOptionsHolder(), $this->_getArrayConvertor(), $query, $this->_optionsStructureHasBeenCompared );
			} else {
				$this->_setPath( $query);
				$result = $this;
			}
		}
		
		
 		
		return $result;
	}
	
	public function getNew( $query ) {
		return new ffOptionsQuery( $this->_getData(), $this->_getOptionsHolder(), $this->_getArrayConvertor(), $query, $this->_optionsStructureHasBeenCompared );
	}
	
/******************************************************************************/
/* PRIVATE FUNCTIONS
/******************************************************************************/
	private function _compareDataWithStructure() {
		if ($this->_getOptionsstructureHasBeenCompared() == false && $this->_optionsHolder != null ) {
			$this->_setOptionsstructureHasBeenCompared(true);
			$options = $this->_getOptionsHolder()->getOptions();
			$this->_getArrayConvertor()->setOptionsArrayData( $this->_data );
			$this->_getArrayConvertor()->setOptionsStructure( $options );
			$this->_data = $this->_getArrayConvertor()->walk();
		} else if( $this->_getOptionsstructureHasBeenCompared() == false && $this->_optionsHolder == null ) {
			$this->_setOptionsstructureHasBeenCompared(true);
		}
	}
	
	private function _get( $query ) {
		$queryArray = $this->_convertQueryToArray( $query );
		$result = $this->_getFromData($queryArray);
		return $result;
	}
	
	private function _convertQueryToArray( $query ) {
		$queryArray = explode(' ', $query);
		return $queryArray;
	}	

	private function _getFromData( $queryArray ){
		$dataPointer = &$this->_data;
		
		if( empty( $dataPointer ) ) {
			return null;
		}
		
		foreach( $queryArray as $oneArraySection ) {
			if( isset( $dataPointer[ $oneArraySection ] ) ) {
				$dataPointer = &$dataPointer[ $oneArraySection ];
			} else {
				return null;
			}
		}
		
		return ( $dataPointer );
	}
	
	
/******************************************************************************/
/* ITERATOR INTERFACE
/******************************************************************************/
	public function current () {
		
		return $this->getNew( $this->_getPath() .' '.$this->_iteratorPointer);
	}
	public function key () {
		return $this->_iteratorPointer;
	}
	public function next () {
		$this->_iteratorPointer++;
	}
	public function rewind () {
		$this->_iteratorPointer = 0;
	}
	public function valid () {
		if( $this->_iteratorPointer == 0) {
			return $this->_validFirst();
		} else {
			return $this->_validNotFirst();
		}
		/*$this->_iteratorValidHolder = $this->get( $this->_getPath() . ' ' . $this->_iteratorPointer);
		if( $this->_iteratorValidHolder !== null ) {
			return true;
		} else {
			return false;
		}*/
		//if( $this->_iteratorPointer <= 2) return true;
		//return false;
	}
	
	private function _validFirst() {
		echo $this->_getPath() . ' ' . $this->_iteratorPointer;
		die();
		
		if( !($this->getOnlyDataPart( $this->_getPath() .' ' . $this->_iteratorPointer, false )) ) {
			$this->_compareDataWithStructure();
			return $this->_validNotFirst();
		} else {
			return true;
		}
	} 
	
	private function _validNotFirst() {
		
		if( ($this->getOnlyDataPart( $this->_getPath() .' ' . $this->_iteratorPointer, false )) ) {
			return true;
		} else {
			return false;
		}
	}
	
/******************************************************************************/
/* SETTERS AND GETTERS
/******************************************************************************/
	/********** DATA **********/
	private function _setData( $data ) {
		$this->_data = $data;
	}
	
	/**
	 * 
	 */
	private function _getData() {
		return $this->_data;
	}
	
	/********** ARRAY CONVERTOR **********/
	private function _setArrayConvertor(ffOptionsArrayConvertor $arrayConvertor ){
		$this->_arrayConvertor = $arrayConvertor;
	}
	
	/**
	 * 
	 * @return ffOptionsArrayConvertor
	 */
	private function _getArrayConvertor() {
		return $this->_arrayConvertor;
	}
	
	/********** OPTIONS HOLDER **********/
	private function _setOptionsHolder(ffIOptionsHolder $optionsHolder ) {
		$this->_optionsHolder = $optionsHolder;
	}
	/**
	 * 
	 * @return ffIOptionsHolder
	 */
	private function _getOptionsHolder() {
		return $this->_optionsHolder;
	}

	/**
	 * @return unknown_type
	 */
	protected function _getPath() {
		return $this->_path;
	}
	
	/**
	 * @param unknown_type $path
	 */
	protected function _setPath($path) {
		$this->_path = $path;
		return $this;
	}

	/**
	 * @return unknown_type
	 */
	protected function _getOptionsstructureHasBeenCompared() {
		return $this->_optionsStructureHasBeenCompared;
	}
	
	/**
	 * @param unknown_type $optionsStructureHasBeenCompared
	 */
	protected function _setOptionsstructureHasBeenCompared($optionsStructureHasBeenCompared) {
		$this->_optionsStructureHasBeenCompared = $optionsStructureHasBeenCompared;
		return $this;
	}
	
	
}