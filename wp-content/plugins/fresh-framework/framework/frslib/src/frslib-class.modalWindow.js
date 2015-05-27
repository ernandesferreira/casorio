(function($){

	"use strict";

	frslib.provide('frslib._classes');

	frslib._classes.modalWindow = function(){

		var _this_ = {};

		// Propeties

		_this_.$window = {};
		
		_this_.selectors = {};

		_this_.callbackSelectors = {};

		_this_.modalWindow_selectors = {
			  modalWindowOpener: '.ff-open-library-button'
			, modalWindow: '.ff-media-modal.ff-modal-library'

			, _mediaModalInner: '.media-modal'

			, _mediaButtonSubmit: '.media-button-select'

			, _mediaButtonCancel: '.media-button-cancel'
			, _modalWindowCloserX: '.media-modal-close'
			, _modalWindowCloserBG: '.media-modal-backdrop'
		};

		// This element called window to open
		_this_.$windowCaller = null;

		// Construct

		_this_.initSelectors = _this_.modalWindow_initSelectors = function(){
			_this_.updateSelectors( _this_.modalWindow_selectors );
		};

		_this_.init = _this_.modalWindow_init = function(){

			_this_.$modalWindow = $(_this_.selectors.modalWindow);

			_this_.openModalWindowInit();
			_this_.closeModalWindowInit(); // Without value
			
			_this_.$window = $( _this_.selectors.modalWindow );
			
		};

		// Methods

		_this_.updateSelectors = function( selectors ){
			for ( var _name_ in selectors ) {
				_this_.selectors[ _name_ ] = selectors[ _name_ ];
			}

			_this_.callbackSelectors.windowOpened = _this_.selectors.modalWindow + '-opened';
			_this_.callbackSelectors.windowClosed = _this_.selectors.modalWindow + '-closed';
			_this_.callbackSelectors.windowSubmitted = _this_.selectors.modalWindow + '-submitted';
		};

		
		
		_this_.openWindowHook = function( $window, $button ){
			var $title = $button.find( '.ff-open-library-button-title' );

			if( 1 == $title.size() ){
				$window.find('h1').html( $title.html() );
			}
			frslib.callbacks.doCallback( _this_.callbackSelectors.windowOpened );
		};

		_this_.closeWindowHook = function( $window ){
			frslib.callbacks.doCallback( _this_.callbackSelectors.windowClosed );

			return false;
		};

		_this_.submitWindowHook = function( $window ){
			frslib.callbacks.doCallback( _this_.callbackSelectors.windowSubmitted );
			

			return false;
		};
		
		_this_.openWindow = function( $initiator ) {
			
			$( _this_.selectors.modalWindow ).addClass('ff-modal-opened');
			_this_.openWindowHook( $( _this_.selectors.modalWindow ), $initiator );
			$(window).resize();
			_this_.$windowCaller = $(this);
			
			return false;
		};

		// Open modal Window
		_this_.openModalWindowInit = function(){

			$(window).load(function(){
				$('body').on( 'click', _this_.selectors.modalWindowOpener, function(){
					_this_.openWindow( $(this) );
					return false;
 
				});
			});
		};


		// Close modal Window ( Without value )
		_this_.closeModalWindowInit = function(){

			$(window).load(function(){

				////////////////////////////////////////////////////////////////////////////////////////
				// Cancel = Closing without save
				////////////////////////////////////////////////////////////////////////////////////////

				var clickCloserSelectors = '';

				// close icon top right
				clickCloserSelectors += _this_.selectors.modalWindow + ' ' + _this_.selectors._modalWindowCloserX + ', ';

				// dark background around
				clickCloserSelectors += _this_.selectors.modalWindow + ' ' + _this_.selectors._modalWindowCloserBG + ', ';

				// cancel button
				clickCloserSelectors += _this_.selectors.modalWindow + ' ' + _this_.selectors._mediaButtonCancel;

				// Cancel window

				$('body').on( 'click', clickCloserSelectors, function(){
					_this_.closeWindowHook( $( _this_.selectors.modalWindow ) );
					$( _this_.selectors.modalWindow ).removeClass('ff-modal-opened');
					
					return false;
				});

				////////////////////////////////////////////////////////////////////////////////////////
				// Submit = Closing and save
				////////////////////////////////////////////////////////////////////////////////////////

				var clickSubmitSelectors = '';

				// Enabling double click to save
				clickSubmitSelectors += _this_.selectors.modalWindow + ' ' + '.ff-modal-library-items-group-item-active' + ', ';

				// Submit / use button
				clickSubmitSelectors += _this_.selectors.modalWindow + ' ' + _this_.selectors._mediaButtonSubmit;

				// Submit window

				$('body').on( 'click', clickSubmitSelectors, function(){
					_this_.submitWindowHook( $( _this_.selectors.modalWindow ) );
					$( _this_.selectors.modalWindow ).removeClass('ff-modal-opened');
					
					return false;
				});
			});

			$(document).keyup(function(e) {
				if( _this_.$modalWindow.hasClass('ff-modal-opened') ){
					if (e.keyCode == 27) {

						var this_z_index = _this_.$modalWindow.find(_this_.selectors._mediaModalInner).css('z-index');
						var max_z_index = this_z_index;

						$( _this_.selectors._mediaModalInner ).each(function(){
							if( $(this).parents('.ff-media-modal').hasClass('ff-modal-opened') ){
								var act_z_index = $( this ).css('z-index');
								if( act_z_index ){
									if( act_z_index > max_z_index ){
										max_z_index = act_z_index;
									}
								}
							}
						});

						if( this_z_index == max_z_index ){
							_this_.closeWindowHook( $( _this_.selectors.modalWindow ) );
							$( _this_.selectors.modalWindow ).removeClass('ff-modal-opened');
						}
					}
				}
			});
		};


		return _this_;

	};

})(jQuery);







