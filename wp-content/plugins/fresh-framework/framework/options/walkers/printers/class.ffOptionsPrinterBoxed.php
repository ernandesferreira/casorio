<?php

class ffOptionsPrinterBoxed extends ffOptionsPrinter {
	
	public function __construct( $optionsArrayData = null, $optionsStructure = null, ffOptionsPrinterComponent_Factory $printerComponentFactory, ffOptionsPrinterDataBoxGenerator $optionsPrinterDataBoxGenerator ) {
		
		parent::__construct($optionsArrayData,$optionsStructure, $printerComponentFactory, $optionsPrinterDataBoxGenerator);
		$this->_addCallbacks();
	}
	
	private function _addCallbacks() {
		$this->_addCalback( ffOptionsPrinter::ACT_BEFORE_REPEATABLE_NODE, ffOptionsPrinter::POSITION_LAST, array($this, '_printRepeatableItemHeader') );
		$this->_addCalback( ffOptionsPrinter::ACT_BEFORE_REPEATABLE_VARIABLE_NODE, ffOptionsPrinter::POSITION_LAST, array($this, '_printRepeatableItemHeader') );
		
		
		$this->_addCalback( ffOptionsPrinter::ACT_AFTER_REPEATABLE_NODE, ffOptionsPrinter::POSITION_FIRST, array($this, '_printRepeatableItemHeaderEnd') );
		$this->_addCalback( ffOptionsPrinter::ACT_AFTER_REPEATABLE_VARIABLE_NODE, ffOptionsPrinter::POSITION_FIRST, array($this, '_printRepeatableItemHeaderEnd') );
		
		$this->_addCssClass( ffOptionsPrinter::CSS_FF_REPEATABLE, 'ff-repeatable-boxed');
	}

	//_beforeRepeatableNodeCallback
	//_beforeRepeatableVariableNodeCallback
	protected function _printRepeatableItemHeader( $item ) {
		$sectionName = $item->getParam('section-name');
		
		echo '<div class="ff-repeatable-header ff-repeatable-drag ff-repeatable-handle">';
			echo '<table class="ff-repeatable-header-table"><tbody><tr>';
			echo '<td class="ff-repeatable-item-number"></td>';
				echo '<td class="ff-repeatable-title">'.$sectionName.'</td>';
				echo '<td class="ff-repeatable-description">Description</td>';
            echo '</tr></tbody></table>';
            echo '<div class="ff-repeatable-handle"></div>';
            ?>
            <div class="ff-repeatable-settings"></div>
            <ul class="ff-repeatable-settings-popup ff-popup">
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button ff-repeatable-duplicate-above">Duplicate above</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button ff-repeatable-duplicate-below">Duplicate below</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button ff-repeatable-remove">Remove</div>
                </li>
            </ul>
            <?php
		echo '</div>';
		echo '<div class="ff-repeatable-content">';
		/*
		echo '<div class="ff-repeatable-header ff-repeatable-drag">';
		echo '<h3 class="ff-repeatable-title">Background Image</h3>';
		echo '<div class="ff-repeatable-add">Add</div>';
		echo '<div class="ff-repeatable-remove">Remove</div>';
		echo '<div class="ff-repeatable-duplicate">Duplicate</div>';
		echo '<div class="ff-repeatable-handle">Open/Close</div>';
		echo '</div>';
		echo '<div class="ff-repeatable-content" style="">';
		//content here
		//</div>*/
	}
	
	protected function _printRepeatableItemHeaderEnd() {
		echo '</div>';
        echo '<div class="ff-repeatable-controls-top">';
        	echo '<div class="ff-repeatable-add-above" title="Add Item"></div>';
            ?>
            <ul class="ff-popup ff-repeatable-add-popup">
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button ff-repeatable-duplicate-above">Duplicate above</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button ff-repeatable-duplicate-below">Duplicate below</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button ff-repeatable-remove">Remove</div>
                </li>
            </ul>
            <?php
        echo '</div>';
        echo '<div class="ff-repeatable-controls-bottom">';
        	echo '<div class="ff-repeatable-add-below" title="Add Item"></div>';
            ?>
            <ul class="ff-popup ff-repeatable-add-popup">
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button">Image</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button">Color</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button">Gradient</div>
                </li>
                <li class="ff-popup-button-wrapper">
                    <div class="ff-popup-button">Icon</div>
                </li>
            </ul>
            <?php
        echo '</div>';
	}
}