<?php
/******************************************************************************/
/* TYPE TEXT
/******************************************************************************/
class ffOptionsPrinterComponent_Text extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		$label = trim( $oneOption->getTitle() );
		$labelAfter = $this->_getLabelAfter( $oneOption );
		$input = '<input type="text" '
					. ' name="'.$nameRoute.'"'
					. ' value="'.$oneOption->getValue().'"'
					. $this->_placeholder( $oneOption )
					. $this->_class( $oneOption )
					. ' >';

		echo ( empty( $label ) and empty( $labelAfter ) )
				? $input
				: '<label>' . $label . ' ' . $input . ' ' . $labelAfter . '</label> '
				;
	}
}

/******************************************************************************/
/* TYPE NUMBER
/******************************************************************************/
class ffOptionsPrinterComponent_Number extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		$label = trim( $oneOption->getTitle() );
		$labelAfter = $this->_getLabelAfter( $oneOption );
		$input = '<input type="number" '
					. ' name="'.$nameRoute.'"'
					. ' value="'.$oneOption->getValue().'"'
					. $this->_placeholder( $oneOption )
					. $this->_class( $oneOption )
					. ' >';

		echo ( empty( $label ) and empty( $labelAfter ) )
				? $input
				: '<label>' . $label . ' ' . $input . ' ' . $labelAfter . '</label> '
				;
	}
}

/******************************************************************************/
/* TYPE TEXTAREA
/******************************************************************************/
class ffOptionsPrinterComponent_Textarea extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		echo '<textarea '
					. ' name="'.$nameRoute.'"'
					. $this->_placeholder( $oneOption )
					. $this->_class( $oneOption )
					. $this->_rows( $oneOption )
					. $this->_cols( $oneOption )
					. '>'.$oneOption->getValue().'</textarea>';
		echo '<span class="description">'.$oneOption->getDescription().'</span>';
	}
}


/******************************************************************************/
/* TYPE CHECKBOX
/******************************************************************************/
class ffOptionsPrinterComponent_Checkbox extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		$label = trim( $oneOption->getTitle() );
		$labelAfter = $this->_getLabelAfter( $oneOption );
		$inputHidden = '<input type="hidden" '
					. ' value="0" '
					. ' name="'.$nameRoute.'" '
				. ' >';
		$input = '<input type="checkbox" '
					. ' name="'.$nameRoute.'"'
					. ' value="1"'
					. $this->_checkedCheckBox( $oneOption )
					. $this->_class( $oneOption )
					. ' >';

		echo ( empty( $label ) and empty( $labelAfter ) )
				? $inputHidden. $input
				: $inputHidden. '<label>' . $input . ' ' . $label . '</label>'
				;
	}
}


/******************************************************************************/
/* TYPE RADIO
 /******************************************************************************/
class ffOptionsPrinterComponent_Radio extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		$valueS = $oneOption->getSelectValues();
		$value  = $oneOption->getValue();

		foreach( $valueS as $key => $oneValue ) {
				echo '<label>';
				echo '<input type="radio" '
						. ( ( $oneValue['value'] == $value ) ? 'checked="checked"' : '' )
						. ' value="' . $oneValue['value'] . '"'
						. ' name="' . $nameRoute . '">';
				echo $oneValue['name'];
				echo '</label>';
				echo '<br />';
				echo "\n";
		}
	}
}

/******************************************************************************/
/* TYPE CODE
/******************************************************************************/
class ffOptionsPrinterComponent_Code extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		$mode     = $oneOption->getParam('mode', 'css');
		$minLines = $oneOption->getParam('minLines', '13');
		$maxLines = $oneOption->getParam('maxLines', '50');

		echo '<div class="ff-code-holder">';
		echo '<pre id="'.$idRoute.$oneOption->getId().'" data-ff-option-type="code" data-ff-option-mode="'.$mode.'" data-ff-option-min-lines="'.$minLines.'" data-ff-option-max-lines="'.$maxLines.'">';
		echo $oneOption->getValue();
		echo '</pre>';

		echo '<textarea id="'.$idRoute.$oneOption->getId().'-textarea" class="ff-code-editor-textarea" name="'.$nameRoute.'" >';
		echo $oneOption->getValue();
		echo '</textarea>';
		echo '</div>';

		//echo '<pre id="ff'
		//echo '<textarea id="'
		/*$selectValues = $oneOption->getSelectValues();
		$selectedValue = $oneOption->getValue();
		echo '<label>'.$oneOption->getTitle().'<select name="'.$nameRoute.'" >';

		if( !empty( $selectValues ) ) {
			foreach( $selectValues as $oneValue ) {
				$selected = '';
				if( $oneValue['value'] == $selectedValue ) {
					$selected = ' selected="selected" ';
				}
				echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
			}
		}
		echo '</select>';*/
	}
}

/******************************************************************************/
/* TYPE SELECT
/******************************************************************************/
class ffOptionsPrinterComponent_Select extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
		$selectValues = $oneOption->getSelectValues();
		$selectedValue = $oneOption->getValue();
		$isGroup =  $oneOption->getParam('is_group', false);
		if( $oneOption->getParam('print_label', true) == true ) {
			echo '<label>';
		}
		echo $oneOption->getTitle().'<select class="'.$this->_getClassesString().'" name="'.$nameRoute.'" >';
		
			if( !$isGroup ) {
				if( !empty( $selectValues ) ) {
					foreach( $selectValues as $oneValue ) {
						$selected = '';
						if( $oneValue['value'] == $selectedValue ) {
							$selected = ' selected="selected" ';
						}
						echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
					}
				}
			} else {
				
				if( !empty( $selectValues ) ) {
					foreach( $selectValues as $groupName => $values ) {
						echo '<optgroup label="'.$groupName.'">';
						foreach( $values as $oneValue ) {
							$selected = '';
							if( $oneValue['value'] == $selectedValue ) {
								$selected = ' selected="selected" ';
							}
							echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
						}
						echo '</optgroup>';
					}
				}
			}
		echo '</select>';
		if( $oneOption->getParam('print_label', true) == true ) {
			echo '</label>';
		}
		
	}
}

/******************************************************************************/
/* TYPE SELECT_CONTENT_TYPE
/******************************************************************************/
class ffOptionsPrinterComponent_Select_ContentType extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
		$selectValues = $oneOption->getSelectValues();
		$value = ( $oneOption->getValue());
		
		$selectedValue = $oneOption->getValue();
		$isGroup =  true;//$oneOption->getParam('is_group', false);
		
		//var_Dump( $isGroup );
		echo ''.$oneOption->getTitle().'<select class="'.$this->_getClassesString().' ff-select-content-type" data-value="'.$value.'" name="'.$nameRoute.'" >';

		if( !$isGroup ) {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $oneValue ) {
					
					$selected = '';
					if( $oneValue['value'] == $selectedValue ) {
						$selected = ' selected="selected" ';
						
						
					}
					echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
				}
			}
		} else {
			
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $groupName => $values ) {
					echo '<optgroup label="'.$groupName.'">';
					foreach( $values as $oneValue ) {
						$selected = '';
						
						if( $oneValue['value'] == $selectedValue ) {
							$selected = ' selected="selected" ';
							
						}
						echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
					}
					echo '</optgroup>';
				}
			}
		}
		echo '</select>';
	}
}



/******************************************************************************/
/* TYPE SELECT2
/******************************************************************************/
class ffOptionsPrinterComponent_Select2 extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {	
		$selectValues = $oneOption->getSelectValues();
		$selectedValue = $oneOption->getValue();
		
		$selectedValueExploded = explode('--||--', $selectedValue );
		
		$multiple = $oneOption->getParam('type', '');
		
		$isGroup =  $oneOption->getParam('is_group', false);
		$width = $oneOption->getParam('width', 0);
		$style = '';
			
			echo '<div class="ff-select2-wrapper">';
			
			echo '<div class="ff-select2-value-wrapper">';
				echo '<input type="text" class="ff-select2-value" name="'.$nameRoute.'" value="'.$selectedValue.'">';
			echo '</div>';
			
			echo '<div class="ff-select2-real-wrapper">';
			echo '<select '.$multiple.' size="1" data-selected-value="'.$selectedValue.'" class="ff-select2" name="'.$nameRoute.'" '.$style.'>';
			if( !$isGroup ) {
				if( !empty( $selectValues ) ) {
					foreach( $selectValues as $oneValue ) {
						$selected = '';
						if( $this->_isValueSelected( $oneValue['value'], $selectedValueExploded)) {
							$selected = ' selected="selected" ';
						}
						echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
					}
				}
			} else {
				if( !empty( $selectValues ) ) {
					foreach( $selectValues as $groupName => $values ) {
						echo '<optgroup label="'.$groupName.'">';
						foreach( $values as $oneValue ) {
							$selected = '';
							if( $this->_isValueSelected( $oneValue['value'], $selectedValue)) {
								$selected = ' selected="selected" ';
							}
							echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
						}
						echo '</optgroup>';
					}
				}
			}
		echo '</select>';
		echo '</div>';
		
		echo '<div class="ff-select2-shadow-wrapper">';
		echo '<select '.$multiple.' data-selected-value="'.$selectedValue.'" class="ff-select2" name="'.$nameRoute.'" '.$style.'>';
		if( !$isGroup ) {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $oneValue ) {
					$selected = '';
					if( $oneValue['value'] == $selectedValue ) {
						$selected = ' selected="selected" ';
					}
					echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
				}
			}
		} else {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $groupName => $values ) {
					echo '<optgroup label="'.$groupName.'">';
					foreach( $values as $oneValue ) {
						$selected = '';
						if( $oneValue['value'] == $selectedValue ) {
							$selected = ' selected="selected" ';
						}
						echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
					}
					echo '</optgroup>';
				}
			}
		}
		echo '</select>';
		echo '</div>';
		echo '</div>';
	}
}


/******************************************************************************/
/* TYPE SELECT2
 /******************************************************************************/
class ffOptionsPrinterComponent_Select2_Hidden extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
		echo '<label>'.$oneOption->getTitle().'<input type="hidden" class="ff-select2 ff-select2-hidden" name="'.$nameRoute.'" '.$style.'>';
		echo '</label>';
	}
}

/******************************************************************************/
/* TYPE SELECT2_POSTS
/******************************************************************************/
class ffOptionsPrinterComponent_Select2_Posts extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
		$selectValues = $oneOption->getSelectValues();
		$selectedValue = $oneOption->getValue();

		$isGroup =  $oneOption->getParam('is_group', false);
		$width = $oneOption->getParam('width', 300);
		$style = 'style="width:'.$width.'px;"';

		echo ''.$oneOption->getTitle().'<select class="ff-select2" name="'.$nameRoute.'" '.$style.'>';
		if( !$isGroup ) {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $oneValue ) {
					$selected = '';
					if( $oneValue['value'] == $selectedValue ) {
						$selected = ' selected="selected" ';
					}
					echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
				}
			}
		} else {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $groupName => $values ) {
					echo '<optgroup label="'.$groupName.'">';
					foreach( $values as $oneValue ) {
						$selected = '';
						if( $oneValue['value'] == $selectedValue ) {
							$selected = ' selected="selected" ';
						}
						echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
					}
					echo '</optgroup>';
				}
			}
		}
		echo '</select>';
	}
}

class ffOptionsPrinterComponent_AceEditor extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
	
	}
}



/******************************************************************************/
/* TYPE Image
 /******************************************************************************/
class ffOptionsPrinterComponent_Image extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {

		$value = $oneOption->getValue();

		if( empty( $value ) ){
			$valueDecoded = (object) array( 'url'=>'', 'id'=>'', 'width'=>0, 'height'=>0 );
		}else{
			$valueDecoded = json_decode( $value );
		}

		$label = trim( $oneOption->getTitle() );
		if( empty( $label ) ){
			$label = 'Select Image';
		}

		$data_forced_width  = $oneOption->getParam('data-forced-width',  '');
		if( ! empty( $data_forced_width  ) ){ $data_forced_width  = ' data-forced-width="'  . $data_forced_width  . '"'; }
		$data_forced_height = $oneOption->getParam('data-forced-height', '');
		if( ! empty( $data_forced_height ) ){ $data_forced_height = ' data-forced-height="' . $data_forced_height . '"'; }

		echo '<span class="ff-open-image-library-button-wrapper">';
			echo '<a class="ff-open-library-button ff-open-image-library-button"'.$data_forced_width.$data_forced_height.'>';
				echo '<span class="ff-open-library-button-preview">';
				echo '<span class="ff-open-library-button-preview-image" style="background-image:url(\''.$this->_escapedValue( $valueDecoded->url ).'\');">';
				echo '</span>';
				echo '</span><span class="ff-open-library-button-title">'.$label.'</span>';
				echo '<input type="hidden" name="'.$nameRoute.'" id="'.$idRoute.'" class="ff-image" value="'.$this->_escapedValue( $value ).'">';
				echo '<span class="ff-open-library-button-preview-image-large-wrapper">';
				echo '<img class="ff-open-library-button-preview-image-large" src="'.$this->_escapedValue( $valueDecoded->url ).'" width="'.$valueDecoded->width.'" height="'.$valueDecoded->height.'">';
				echo '</span>';
			echo '</a>';
			echo '<a class="ff-open-library-remove" title="Clear"></a>';
		echo '</span>';

	}
}

/******************************************************************************/
/* TYPE Color Library
/******************************************************************************/
class ffOptionsPrinterComponent_ColorLibrary extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
	
		echo '<a class="ff-open-library-button ff-open-library-color-button">';
			echo '<span class="ff-open-library-button-preview">';
			echo '<span class="ff-open-library-button-preview-color" style="background:#0088cc;"></span>';
				echo '</span><span class="ff-open-library-button-title">Select Color</span>';
			echo '<input type="hidden" name="'.$nameRoute.'">';
		echo '</a>';
		
		
		return;
		
		/*
		 * 				
		 * 
		 * 
		 * <a class="ff-open-library-button ff-open-library-color-button">
					<span class="ff-open-library-button-preview">
						<span class="ff-open-library-button-preview-color" style="background:#0088cc;"></span>
					</span><span class="ff-open-library-button-title">Select Color</span>
				</a>
		 */
		
		
		$value = $oneOption->getValue();

		if( empty( $value ) ){
			$valueDecoded = (object) array( 'url'=>'', 'id'=>'' );
		}else{
			$valueDecoded = json_decode( $value );
		}

		$label = trim( $oneOption->getTitle() );
		if( empty( $label ) ){
			$label = 'Select Image';
		}

		echo '<span class="ff-open-image-library-button-wrapper">';
		echo '<a class="ff-open-library-button ff-open-image-library-button">';
		echo '<span class="ff-open-library-button-preview">';
		echo '<span class="ff-open-library-button-preview-image" style="background-image:url(\''.$this->_escapedValue( $valueDecoded->url ).'\');"></span>';
		echo '</span><span class="ff-open-library-button-title">'.$label.'</span>';
		echo '<input type="hidden" name="'.$nameRoute.'" id="'.$idRoute.'" class="ff-image" value="'.$this->_escapedValue( $value ).'">';
		echo '</a>';
		echo '<a class="ff-open-library-remove" title="Clear"></a>';
		echo '</span>';

	}
}



/******************************************************************************/
/* TYPE_CONDITIONAL_LOGIC
/******************************************************************************/
class ffOptionsPrinterComponent_ConditionalLogic extends ffOptionsPrinterComponentsBasic {
	protected function _printOption( ffOneOption $oneOption, $nameRoute, $idRoute ) {
		//die();

		$fwc = ffContainer::getInstance();
		$conditionalLogic  = $fwc->getOptionsFactory()->createOptionsHolder('ffOptionsHolderConditionalLogic');

		//vaR_dump( $conditionalLogic );

	//	$value = $fwc->getDataStorageFactory()->createDataStorageWPPostMetas_NamespaceFacade(  $post->ID )->getOption('customcode_logic');
	//	parse_str($oneOption->getValue(), $params);
		//var_dump( $params );

		$printer = $fwc->getOptionsFactory()->createOptionsPrinterLogic( $oneOption->getValue(), $conditionalLogic->getOptions() );
		$printer->setNameprefix( 'option-value' );

		echo '<div class="ff-option-conditional-logic-wrapper">';
		echo '<input type="text" class="ff-hidden-input" name="'.$nameRoute.'">';
		echo '<div class="ff-option-conditional-logic">';
		$printer->walk();
		echo '</div>';

		echo '</div>';

		/*
		$value = $oneOption->getValue();
		
		echo '<div class="ff-option-conditional-logic">';
			echo 'CONDITIONAL LOGIC';
			
			echo '<textarea type="text" class="ff-logic"></textarea>';
		echo '</div>';
		
		/*$selectValues = $oneOption->getSelectValues();
		$selectedValue = $oneOption->getValue();

		$isGroup =  $oneOption->getParam('is_group', false);
		$width = $oneOption->getParam('width', 300);
		$style = 'style="width:'.$width.'px;"';

		echo ''.$oneOption->getTitle().'<select class="ff-select2" name="'.$nameRoute.'" '.$style.'>';
		if( !$isGroup ) {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $oneValue ) {
					$selected = '';
					if( $oneValue['value'] == $selectedValue ) {
						$selected = ' selected="selected" ';
					}
					echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
				}
			}
		} else {
			if( !empty( $selectValues ) ) {
				foreach( $selectValues as $groupName => $values ) {
					echo '<optgroup label="'.$groupName.'">';
					foreach( $values as $oneValue ) {
						$selected = '';
						if( $oneValue['value'] == $selectedValue ) {
							$selected = ' selected="selected" ';
						}
						echo '<option value="'.$oneValue['value'].'" '.$selected.'>'.$oneValue['name'].'</option>';
					}
					echo '</optgroup>';
				}
			}
		}
		echo '</select>';*/
	}
}