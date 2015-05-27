<?php
class ffOneElement extends ffBasicObject implements ffIOneDataNode {
	const TYPE_TABLE_START = 'type_table_start';
	const TYPE_TABLE_END = 'type_table_end';
	
	const TYPE_TABLE_DATA_START = 'type_table_data_start';
	const TYPE_TABLE_DATA_END = 'type_table_data_end';
	
	const TYPE_NEW_LINE = 'type_new_line';
	const TYPE_BUTTON = 'type_button';
	const TYPE_BUTTON_PRIMARY = 'type_button_primary';
	const TYPE_HTML = 'type_html';
	const TYPE_HEADING = 'type_heading';
	const TYPE_PARAGRAPH = 'type_paragraph';
	const TYPE_DESCRIPTION = 'type_description';

	private $_type = null;
	private $_id = null;
	private $_title = null;

	private $_description = null;
	
	
	private $_params = null;
	

/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/	
	public function __construct( $type = null, $id = null, $title = null, $description = null ) {
		$this->_type = $type;
		$this->_id = $id;
		$this->_title = $title;

		$this->_description = $description;
	}
	
	
	
	public function addParam( $name, $value ) {
		//$newParam = array( 'name' => $name, 'value' => $value );
		$this->_params[ $name ][] = $value;
	}
	
		
	public function getTitle() { return $this->_title; }
	
	public function getDescription() { return $this->_description; }
	
	public function getType() { return $this->_type; }
	
	public function getParam( $name, $defaultValue = null ) {
		if( isset( $this->_params[$name ] ) ) {
			if( count( $this->_params[ $name ]) == 1 ) {
				return reset( $this->_params[ $name ] );
			} else {
				return $this->_params[ $name ];
			}
		}
	
		return $defaultValue;
	}
/******************************************************************************/
/* IOneDataNode IMPLEMENTATION
/******************************************************************************/	
	public function getId() { return $this->_id; }
	
	public function isContainer() {
		return false;
	}
	
	
}