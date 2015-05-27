<?php

class ffModalWindowLibraryColorPickerViewDefault extends ffModalWindowView {
	
	/**
	 * 
	 * @var ffModalWindowLibraryColorPickerColorPreparator
	 */
	private $colorLibraryPreparator = null;
	
	protected function _initialize() {
		$this->_setViewName('Default');
		$this->_setWrappedInnerContent( false );
	}

	protected  function _requireAssets() {
		$this->_getScriptEnqueuer()->getFrameworkScriptLoader()
										->requireSelect2()
										->requireFrsLib()
										->requireFrsLibOptions()
										->requireFrsLibModal();
		
		
		$this->_getScriptEnqueuer()->addScriptFramework('ff-modal-color-picker', '/framework/adminScreens/modalWindow/libraries/color/picker/assets/ModalWindowLibraryColorPicker.js');
										
	}
	
	

	private function _printUserColors( $data ) {
		foreach( $data as $groupName => $groupValue ) {
			echo '<div class="ff-modal-library-items-group">';
			echo '<div class="ff-modal-library-items-group-title" data-group-name="'.$groupName.'" data-font-class="placeholder-font-awesome" data-top="-171">';
			echo '<label>';
			echo str_replace('-', ' ',$groupName);
			echo '<span class="ff-modal-library-items-group-counter">';
			echo '<span class="ff-modal-library-items-group-counter-filtered">368</span>';
			echo '<span class="ff-modal-library-items-group-counter-slash">/</span>';
			echo '<span class="ff-modal-library-items-group-counter-total">368</span>';
			echo '</span>';
			echo '</label>';
			echo '</div><!-- END MODAL LIBRARY GROUP TITLE -->';
			echo '<div class="ff-modal-library-items-group-items">';
			//var_dump( $groupValue );
			foreach( $groupValue as $oneItem ) {
		
				echo '<div class="ff-modal-library-items-group-item">';
					echo '<div class="ff-modal-library-items-group-item-color" style="background-color:'.$oneItem->getColor()->getHex().'">';
						echo '&nbsp;';
						
						echo '<div class="ff-item-info">';
							echo '<input type="hidden" class="ff-item-name" value="'.$oneItem->getTitle().'">';
							echo '<input type="hidden" class="ff-item-color" value="'.$oneItem->getColor()->getHTMLColor().'">';
							echo '<input type="hidden" class="ff-item-type" value="user">';
							echo '<input type="hidden" class="ff-item-tags" value="'.$oneItem->getTags().'">';
							
							echo '<input type="hidden" class="ff-item-id" value="'.$oneItem->getId().'">';
						echo '</div>';
					echo '</div>';
					
				echo '</div><!-- END MODAL LIBRARY GROUP ITEM -->';
			}
				
			echo '</div><!-- END MODAL LIBRARY GROUP ITEMS -->';
			echo '</div><!-- END MODAL LIBRARY GROUP -->';
		}
	}
	
	protected function _render() {
		$colorLib = ffContainer::getInstance()->getLibManager()->createUserColorLibrary();
 
		// echo '<div class="ff-colorlib-banned-variables">';
		// 	echo json_encode( $this->_getColorLibraryPreparator()->getBannedColors() );
		// echo '</div>';
		
		
	?>
 
			<div class="attachments-browser">
				<div class="media-toolbar">
					<div class="media-toolbar-secondary">
						<select class="attachment-filters">
							<option value="all">All (324)</option>
  							<optgroup label="User">
								<option value="uploaded">blue brand (19)</option>
								<option value="uploaded">green variant (19)</option>
							</optgroup>
  							<optgroup label="Themes">
								<option value="uploaded">Sentinel (43)</option>
							</optgroup>
  							<optgroup label="Plugins">
								<option value="uploaded">Fresh Shortcodes (124)</option>
								<option value="uploaded">Fresh Social (124)</option>
								<option value="uploaded">Bootstrap (53)</option>
							</optgroup>
						</select>
						<span class="spinner" style="display: none;"></span>
					</div>
					<div class="media-toolbar-primary"><input type="search" placeholder="Search" class="search"></div>
				</div>

				<div class="ff-modal-library-items-container ff-modal-library-items-group-item-size-10">

					<div class="ff-modal-library-items-groups-titles-container">
						<div class="ff-modal-library-items-groups-titles-wrapper">
							<div class="ff-modal-library-items-groups-titles">
								<div class="ff-modal-library-items-group-title" style="background:red;" data-font-class="placeholder-font-awesome" data-top="-171">
								</div><!-- END MODAL LIBRARY GROUP TITLE -->
							</div>
						</div>
					</div><!-- END MODAL LIBRARY GROUPS TITLES -->

					<div class="ff-modal-library-items-wrapper">
						<div class="ff-modal-library-items">

						
						
<?php 

						//$variableName = '@brand-primary';
						$userColors = $this->_getColorLibraryPreparator()->getPreparedUserColors();
						$this->_printUserColors( $userColors );
						
						
						$systemColors = $this->_getColorLibraryPreparator()->getPreparedSystemColors( );
						
						
						foreach( $systemColors as $groupName => $groupValue ) {
							echo '<div class="ff-modal-library-items-group">';
							echo '<div class="ff-modal-library-items-group-title" data-font-class="placeholder-font-awesome" data-top="-171">';
							echo '<label>';
							echo str_replace('-', ' ',$groupName);
							echo '<span class="ff-modal-library-items-group-counter">';
							echo '<span class="ff-modal-library-items-group-counter-filtered">368</span>';
							echo '<span class="ff-modal-library-items-group-counter-slash">/</span>';
							echo '<span class="ff-modal-library-items-group-counter-total">368</span>';
							echo '</span>';
							echo '</label>';
							echo '</div><!-- END MODAL LIBRARY GROUP TITLE -->';
							echo '<div class="ff-modal-library-items-group-items">';
							//var_dump( $groupValue );
							foreach( $groupValue as $itemName => $oneItem ) {
								//var_Dump( $oneItem );
								echo '<div class="ff-modal-library-items-group-item">';
									echo '<div class="ff-modal-library-items-group-item-color" style="background-color:'.$oneItem.'">';

										echo '&nbsp;';
										
										echo '<div class="ff-item-info">';
											echo '<input type="hidden" class="ff-item-name" value="'.$itemName.'">';
											echo '<input type="hidden" class="ff-item-color" value="'.$oneItem.'">';
											echo '<input type="hidden" class="ff-item-type" value="system">';
											echo '<input type="hidden" class="ff-item-tags" value="Bootstrap">';
										echo '</div>';

									echo '</div>';
										
								echo '</div><!-- END MODAL LIBRARY GROUP ITEM -->';
							}
								
							echo '</div><!-- END MODAL LIBRARY GROUP ITEMS -->';
							echo '</div><!-- END MODAL LIBRARY GROUP -->';
						}
?>

						</div><!-- END MODAL LIBRARY ITEMS -->
					</div>
				</div>


				<!--
				<ul class="attachments ui-sortable ui-sortable-disabled">
				
					<?php 
					/*
						if( !empty( $colors ) ) {
							foreach( $colors as $oneColor ) {
 
								echo '<li class="attachment save-ready ff-one-color-item">';
									echo '<div class="" style="background-color:'.$oneColor->getColor()->getHex().'" data-family="ff-font-awesome" data-tags="eject player awesome" data-content="2ecf">';
										echo 'xxx';
									echo '</div>';
									echo '<div class="info">'. $oneColor->getTags().'</div>'; 
									echo '<div class="json_data">'.$this->_colorLibraryItemToJSON( $oneColor ).'</div>';
								echo '</li>';
							}
						}
					*/
					?>

				</ul>
				-->


				<?php $this->_printSidebar(); ?>
			</div>
		<?php 
	}
	
	
	private function _printSidebar() {
	?>
	
		<div class="media-sidebar">
			<div class="attachment-details save-ready">
				<h3>Color Details</h3>
				<div class="attachment-info">
					<div class="thumbnail">
						<div class="ff-modal-library-item-color" style="background: lightgreen;"></div>
					</div>
					<div class="details">
						<div class="filename"></div>
						<!--<div class="uploaded">May 9, 2014</div>-->
						
						<a class="edit-attachment" href="#" >Edit Color</a>
						<a class="duplicate-attachment" href="#" >Duplicate Color</a>
						
						<a class="delete-attachment" href="#">Delete Permanently</a>
					</div>
				</div>
				<div class="ff-modal-library-item-details-settings-row">
					<div class="ff-modal-library-item-details-settings-th">Tags</div>
					<div class="ff-modal-library-item-details-settings-td ff-modal-library-item-tedails-settings-tags">
						<p><a href=""></a></p>
					</div>
				</div>
				<div class="ff-modal-library-item-details-settings-row">
					<div class="ff-modal-library-item-details-settings-th">HEX</div>
					<div class="ff-modal-library-item-details-settings-td ff-modal-library-item-tedails-settings-hex">
						<p></p>
					</div>
				</div>
				<div class="ff-modal-library-item-details-settings-row">
					<div class="ff-modal-library-item-details-settings-th">RGBA</div>
					<div class="ff-modal-library-item-details-settings-td ff-modal-library-item-tedails-settings-rgba">
						<p></p>
					</div>
				</div>
				<div class="ff-modal-library-item-details-settings-row">
					<div class="ff-modal-library-item-details-settings-th">Math function</div>
					<div class="ff-modal-library-item-details-settings-td">
						<div class="ff-modal-library-color-math-function">
							<ul class="ff-repeatable ff-repeatable-modal-library-color-math-function">
								<li class="ff-repeatable-item ff-repeatable-item-modal-library-color-math-function">
									<select class="ff-modal-library-color-math-function-select">
										<option value="darken" selected="">darken</option>
										<option value="lighten">lighten</option>
										<option value="spin">spin</option>
									</select>
									<input type="text" value="20" class="ff-modal-library-color-math-function-value">
									<p class="ff-modal-library-color-math-function-unit">%</p>
									<a href="" class="ff-modal-library-color-math-function-remove"></a>
								</li>
							</ul>
							<input type="button" class="button button-small" value="+ Add">
						</div>
					</div>
				</div>		
			</div>
		</div>
	<?php
	}
	
	public function printToolbar() {
		
		echo '<div class="media-frame-toolbar">';
			echo '<div class="media-toolbar">';
				echo '<div class="media-toolbar-primary">';
					echo '<input type="submit" class="button media-button button-secondary button-large media-button-cancel" value="Cancel"> ';
					echo '<input type="submit" class="button media-button button-primary button-large media-button-select" value="Use">';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

	public function proceedAjax( ffAjaxRequest $request ) {
		var_dump( $request );

		
		switch( $request->specification['action'] ) {
			case 'delete' :
				
				break;
				
			case 'new':
				
				break;
				
			case 'edit':
				
				break;

			case 'duplicate':
				$userColors = $this->_getColorLibraryPreparator()->getPreparedUserColors();
				//var_dump( $userColors );
				$firstColor = $userColors[''][0];
				
				$groupNew = array();
				$groupNew['new-colors'][] = $firstColor;
				ob_start();
				$this->_printUserColors( $groupNew );
				$colors = ob_get_contents();
				ob_end_clean();
				
				$toPrint = array();
				$toPrint['html'] = $colors;
				$toPrint['group_name'] = 'new-colors';
				echo json_encode( $toPrint );
				
				
				
				
				break;
				//var_dump( $request->data );
				$colorInfo = $request->data['colorInfo'];
				$userColorLibrary = ffContainer::getInstance()->getLibManager()->createUserColorLibrary();
				
				$newUserColor = $userColorLibrary->getNewColor();
				
				$newUserColor->setTitle($colorInfo['title'] . '-DUPLICATE' );
				$newUserColor->setTags( $colorInfo['tags'] );
				
				$newUserColor->getColor()->setHex( $colorInfo['hex'], $colorInfo['opacity']);
				$userColorLibrary->setColor( $newUserColor );
				
				
				break;
		}
	}


	private function _printForm( $data = array() ) {
 
	}
	
	/**
	 *
	 * @return ffModalWindowLibraryColorPickerColorPreparator
	 */
	protected function _getColorLibraryPreparator() {
		return $this->_colorLibraryPreparator;
	}
	
	/**
	 *
	 * @param ffModalWindowLibraryColorPickerColorPreparator $colorLibraryPreparator        	
	 */
	public function setColorLibraryPreparator(ffModalWindowLibraryColorPickerColorPreparator $colorLibraryPreparator) {
		$this->_colorLibraryPreparator = $colorLibraryPreparator;
		return $this;
	}
	
}