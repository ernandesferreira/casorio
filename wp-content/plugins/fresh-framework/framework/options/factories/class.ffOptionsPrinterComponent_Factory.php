<?php

class ffOptionsPrinterComponent_Factory extends ffFactoryAbstract {
/******************************************************************************/
/* VARIABLES AND CONSTANTS
/******************************************************************************/
	/**
	 * 
	 * @var ffOptionsPrinterComponent_Text
	 */
	private $_printerText = null;	
	
	/**
	 * 
	 * @var ffOptionsPrinterElement_TableHeader
	 */
	private $_printerElementTableHeader = null;
	
	
	private $_createdObjects = array();
/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/
	
	
	
/******************************************************************************/
/* CREATE AND PRINT COMPONENTS
/******************************************************************************/	
	private function _createComponent( $className ) {
		$this->_getClassloader()->loadClass('ffOptionsPrinterElementsAndComponentsBasic');
		$this->_getClassloader()->loadClass('ffOptionsPrinterComponentsBasic');
		$this->_getClassloader()->loadClass('ffOptionsPrinterComponents');
		if( !isset( $this->_createdObjects[ $className ] ) ) {
			$this->_createdObjects[ $className ] = new $className();
		}
		
		return $this->_createdObjects[ $className ];
	}
	
	
	/**
	 * 
	 * @return ffOptionsPrinterComponent_Text
	 */
	public function createPrinterOptionText() {
		return $this->_createComponent('ffOptionsPrinterComponent_Text');
	}

	public function createPrinterOptionTextarea() {
		return $this->_createComponent('ffOptionsPrinterComponent_Textarea');
	}
	
	public function createPrinterOptionNumber() {
		return $this->_createComponent('ffOptionsPrinterComponent_Number');
	}
	
	public function createPrinterOptionCheckbox() {
		return $this->_createComponent('ffOptionsPrinterComponent_Checkbox');
	}
	
	public function createPrinterOptionRadio() {
		return $this->_createComponent('ffOptionsPrinterComponent_Radio');
	}
	
	public function createPrinterOptionSelect() {
		return $this->_createComponent('ffOptionsPrinterComponent_Select');
	}
	
	public function createPrinterOptionSelectContentType() {
		return $this->_createComponent('ffOptionsPrinterComponent_Select_ContentType');
	}	
	
	public function createPrinterOptionSelect2() {
		return $this->_createComponent('ffOptionsPrinterComponent_Select2');
	}
	
	public function createPrinterOptionSelect2Hidden() {
		return $this->_createComponent('ffOptionsPrinterComponent_Select2_Hidden');
	}
	
	public function createPrinterOptionSelect2Posts() {
		return $this->_createComponent('ffOptionsPrinterComponent_Select2_Posts');
	}
	
	public function createPrinterOptionCode() {
		return $this->_createComponent('ffOptionsPrinterComponent_Code');
	}
	
	public function createPrinterOptionConditionalLogic() {
		return $this->_createComponent('ffOptionsPrinterComponent_ConditionalLogic');
	}
	
	public function createPrinterOptionImage() {
		return $this->_createComponent('ffOptionsPrinterComponent_Image');
	}
	
	public function createPrinterColorLibrary() {
		return $this->_createComponent('ffOptionsPrinterComponent_ColorLibrary');
	}
	
/******************************************************************************/
/* CREATE AND PRINT ELEMENTS
/******************************************************************************/	
	
	private function _createElement( $className ) {
		$this->_getClassloader()->loadClass('ffOptionsPrinterElementsAndComponentsBasic');
		$this->_getClassloader()->loadClass('ffOptionsPrinterElementsBasic');
		$this->_getClassloader()->loadClass('ffOptionsPrinterElements');
		if( !isset( $this->_createdObjects[ $className ] ) ) {
			$this->_createdObjects[ $className ] = new $className;
		}
		
		return $this->_createdObjects[ $className ];
	}
	
	
	
	public function createPrinterElementTableStart() {
		return $this->_createElement('ffOptionsPrinterElement_TableStart');
	}	
	
	public function createPrinterElementTableEnd() {
		return $this->_createElement('ffOptionsPrinterElement_TableEnd');
	}
	
	
	public function createPrinterElementTableDataStart() {
		return $this->_createElement('ffOptionsPrinterElement_TableDataStart');
	}
	
	public function createPrinterElementTableDataEnd() {
		return $this->_createElement('ffOptionsPrinterElement_TableDataEnd');
	}
	
	public function createPrinterElementNewLine() {
		return $this->_createElement('ffOptionsPrinterElement_NewLine');
	}

	public function createPrinterElementButton() {
		return $this->_createElement('ffOptionsPrinterElement_Button');
	}

	public function createPrinterElementButtonPrimary() {
		return $this->_createElement('ffOptionsPrinterElement_Button_Primary');
	}

	public function createPrinterElementHtml() {
		return $this->_createElement('ffOptionsPrinterElement_Html');
	}	
	
	public function createPrinterElementHeading() {
		return $this->_createElement('ffOptionsPrinterElement_Heading');
	}
	
	public function createPrinterElementParagraph() {
		return $this->_createElement('ffOptionsPrinterElement_Paragraph');
	}

	public function createPrinterElementDescription() {
		return $this->_createElement('ffOptionsPrinterElement_Description');
	}

/******************************************************************************/
/* PRIVATE FUNCTIONS
/******************************************************************************/
	
/******************************************************************************/
/* SETTERS AND GETTERS
/******************************************************************************/	
}