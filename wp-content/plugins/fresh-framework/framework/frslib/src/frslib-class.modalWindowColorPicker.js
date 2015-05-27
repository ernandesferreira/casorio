(function($){

	"use strict";

	frslib.provide('frslib._classes');

	frslib._classes.modalWindowColorPicker = function(){

		var _this_ = frslib._classes.modalWindowAdvanced();

		// Propeties

		_this_.useFixedHeaders = true;

		_this_.currentSelectedItem = '';

		_this_.modalWindowColorPicker_selectors = {
			  modalWindowOpener: '.ff-open-library-color-button'
			, modalWindow: '#ff-modal-library-color-picker'
			, sidebar: '.media-sidebar'
		};

		// Constructor

		_this_.initSelectors = _this_.modalWindowColorPicker_initSelectors = function(){
			_this_.modalWindowAdvanced_initSelectors();
			_this_.updateSelectors( _this_.modalWindowColorPicker_selectors );
		};

		_this_.init = _this_.modalWindowColorPicker_init = function(){
			_this_.modalWindowColorPicker_initSelectors();
			_this_.modalWindowAdvanced_init();
			_this_.bindActions();
		};

		_this_.reinit = _this_.modalWindowColorPicker_reinit = function(){

			_this_.modalWindowAdvanced_reinit();

			// Some other code, which is applied only on picker
			// ...
			// ...

		};

		// Methods

		_this_.ajaxRequest = function( action, data, callback ) {
			var specification = {
					  'managerClass' : 'ffModalWindowManagerLibraryColorPicker'
					, 'modalClass'   : 'ffModalWindowLibraryColorPicker'
					, 'viewClass'    : 'ffModalWindowLibraryColorPickerViewDefault'
					, 'action'       : action
			}


			frslib.ajax.frameworkRequest( 'ffModalWindow', specification, data, callback);
		};


		_this_.bindActions = function() {
			$('body').on('click', _this_.selectors.oneItem, function(){

				_this_.currentSelectedItem = frslib._classes.modalWindowColorLibraryColor();
				_this_.currentSelectedItem.setCurrentSelector( $(this) );
				_this_.currentSelectedItem.gatherDataFromSelector();
				_this_.changeSidebar();

				$( _this_.selectors.modalWindow ).find('.ff-modal-library-items-group-item').removeClass('ff-modal-library-items-group-item-active');
				$(this).addClass('ff-modal-library-items-group-item-active');
			});

			$('body').on('click', _this_.selectors.modalWindow + ' .delete-attachment', function() {
				_this_.deleteColor();
			});
			
			frslib.callbacks.addCallback( frslib.modal.colorLibrary.events.getCurrentSelectedColor, function( _currentSelectedItem ){
				_currentSelectedItem[0] = _this_.currentSelectedItem;
			});
		};

		_this_.changeSidebar = function() {

			var $sidebar = $(_this_.selectors.sidebar);

			$sidebar.find('.ff-modal-library-item-color').css('background', _this_.currentSelectedItem.colors.hex);

			$sidebar.find('.filename').html( _this_.currentSelectedItem.description.name);
			$sidebar.find('.ff-modal-library-item-tedails-settings-tags').find('p').html( _this_.currentSelectedItem.description.tags );
			$sidebar.find('.ff-modal-library-item-tedails-settings-hex').find('p').html( _this_.currentSelectedItem.colors.hex );

			var rgb =  _this_.currentSelectedItem.colors.rgb;
			$sidebar.find('.ff-modal-library-item-tedails-settings-rgba').find('p').html( 'rgba('+ rgb.r+',' + rgb.g+',' + rgb.b + ',' + _this_.currentSelectedItem.colors.opacity + ')' );


			$sidebar.find('.edit-attachment').show();
			$sidebar.find('.delete-attachment').show();
			if( _this_.currentSelectedItem.description.type == 'system' ){
				$sidebar.find('.edit-attachment').hide();
				$sidebar.find('.delete-attachment').hide();

			}
		};

		_this_.duplicateColor = function() {
			_this_.ajaxRequest('duplicate', _this_.currentSelectedItem.getOnlyData(), function(response){

				var parsedResponse = $.parseJSON( response );

				var $existingGroup = $( _this_.selectors.modalWindow ).find('.ff-modal-library-items-group-title[data-group-name="' + parsedResponse.groupName + '"]');

				if( $existingGroup.size() == 0 ) {
					$( _this_.selectors.modalWindow).find('.ff-modal-library-items-wrapper').find('.ff-modal-library-items').prepend( parsedResponse.html );
				} else {

				}
			});
		};

		_this_.deleteColor = function() {

			_this_.ajaxRequest('delete', _this_.currentSelectedItem.getOnlyData(), function( response ){
				console.log( response );
				_this_.currentSelectedItem.selectors.$currentSelectedColor.remove();
			});
		};

		_this_.editColor = function() {

		};

		return _this_;
	};

})(jQuery);







