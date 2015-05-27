(function($){

	"use strict";

	frslib.provide('frslib._classes');

	frslib._classes.modalWindowColorEditor = function(){

		var _this_ = frslib._classes.modalWindow();

		// Propeties
		
		_this_.currentSelectedItem = {};
		
		_this_.jquerySelectors = {};

		_this_.modalWindowColorEditor_selectors = {
			  modalWindowOpener: '.edit-attachment, .duplicate-attachment'
			, modalWindow: '#ff-modal-library-color-editor'
		};

		// Constructor

		_this_.initSelectors = _this_.modalWindowColorEditor_initSelectors = function(){
			_this_.modalWindow_initSelectors();
			_this_.updateSelectors( _this_.modalWindowColorEditor_selectors );
		};

		_this_.init = _this_.modalWindowColorEditor_init = function(){
			_this_.modalWindowColorEditor_initSelectors();
			_this_.modalWindow_init();
			_this_.initJquerySelectors();
			_this_.hookActions();
		
		};
		
		_this_.initJquerySelectors = function() {
			//_this_.$selectors.$colorEditor = _this_.$window.find('#ff-modal-library-color-editor');
			_this_.jquerySelectors.$colorEditorMinicolors = _this_.$window.find('.minicolors');
			
			_this_.jquerySelectors.$hexcode = _this_.$window.find('.ff-colorlib-color-hexcode');
			
			_this_.jquerySelectors.$opacity = _this_.$window.find('.ff-colorlib-color-opacity');
			
			_this_.jquerySelectors.$rgb_r = _this_.$window.find('.ff-colorlib-color-rgb-r');
			_this_.jquerySelectors.$rgb_g = _this_.$window.find('.ff-colorlib-color-rgb-g');
			_this_.jquerySelectors.$rgb_b = _this_.$window.find('.ff-colorlib-color-rgb-b');
			_this_.jquerySelectors.$hsb_h = _this_.$window.find('.ff-colorlib-color-hsb-h');
			_this_.jquerySelectors.$hsb_s = _this_.$window.find('.ff-colorlib-color-hsb-s');
			_this_.jquerySelectors.$hsb_b = _this_.$window.find('.ff-colorlib-color-hsb-b');
			
			_this_.jquerySelectors.$previewBefore = _this_.$window.find('.ff-colorlib-color-preview-before');
			_this_.jquerySelectors.$previewAfter = _this_.$window.find('.ff-colorlib-color-preview-after');
			
			_this_.jquerySelectors.$title = _this_.$window.find('.ff-modal-library-item-details-settings-title');
			_this_.jquerySelectors.$tags = _this_.$window.find('.ff-modal-library-item-details-settings-tags');
		}
		
		_this_.hookActions = function() {
			frslib.callbacks.addCallback(_this_.callbackSelectors.windowOpened, _this_.afterWindowOpen );
			
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors({
				opacity: true,
				change: function( hex, opacity) {
					_this_.changeValues(hex, opacity);
				},
				inline: true
			});
			
			_this_.changes_HSB_Hooks();
			_this_.changes_RGB_Hooks();
			//_this_.callbackSelectors.windowOpened
		}
		
		_this_.changeValues = function( hex, opacity ) {
			var hexWithoutSharp = hex.replace('#', '');
			//var rgb = $(this).minicolors('rgbObject');
			var rgb = frslib.colors.convert.toArray( hex );
			var hsb = frslib.colors.convert.rgbToHsl( rgb.r, rgb.g, rgb.b );
			
			_this_.jquerySelectors.$hexcode.val( hex );
			_this_.jquerySelectors.$opacity.val( opacity );
			
			_this_.jquerySelectors.$rgb_r.val( rgb.r );
			_this_.jquerySelectors.$rgb_g.val( rgb.g );
			_this_.jquerySelectors.$rgb_b.val( rgb.b );
			
			_this_.jquerySelectors.$hsb_h.val( hsb.h );
			_this_.jquerySelectors.$hsb_s.val( hsb.s );
			_this_.jquerySelectors.$hsb_b.val( hsb.b );
			
			_this_.jquerySelectors.$previewAfter.css('background-color', 'rgba('+rgb.r+','+rgb.g+','+rgb.b+','+opacity+')');
		}
//##############################################################################
//# RGB changes
//##############################################################################
		_this_.changes_RGB_Hooks = function() {
			_this_.jquerySelectors.$rgb_r.change(function() {
				var value = $(this).val();
				if( !(!isNaN(value) && parseInt(Number(value)) == value ) || value < 0 || value > 255 ) {
					value = 0;
					$(this).val(0);
				}
				
				_this_.changes_RGB();
			});
			
			_this_.jquerySelectors.$rgb_g.change(function() {
				var value = $(this).val();
				if( !(!isNaN(value) && parseInt(Number(value)) == value ) || value < 0 || value > 255 ) {
					value = 0;
					$(this).val(0);
				}
				
				_this_.changes_RGB();
			});
			
			_this_.jquerySelectors.$rgb_b.change(function() {
				var value = $(this).val();
				if( !(!isNaN(value) && parseInt(Number(value)) == value ) || value < 0 || value > 255 ) {
					value = 0;
					$(this).val(0);
				}
				
				_this_.changes_RGB();
			});
		}
		
		_this_.changes_RGB = function() {
			var r = _this_.jquerySelectors.$rgb_r.val();
			var g = _this_.jquerySelectors.$rgb_g.val();
			var b = _this_.jquerySelectors.$rgb_b.val();
			
			var hex = frslib.colors.convert.rgbToHex(r, g, b);
			var opacity = _this_.jquerySelectors.$opacity.val();
			
			//_this_.changeValues(hex, opacity);
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('value', hex);
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('opacity', opacity);
			/*
			var h = _this_.jquerySelectors.$hsb_h.val();
			var s = _this_.jquerySelectors.$hsb_s.val();
			var b = _this_.jquerySelectors.$hsb_b.val();
		
			var rgb = frslib.colors.convert.hslToRgb(h, s,b);
			var hex = frslib.colors.convert.rgbToHex(rgb.r, rgb.g, rgb.b);
			var opacity = _this_.jquerySelectors.$opacity.val();
			
			_this_.changeValues(hex, opacity);
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('value', hex);
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('opacity', opacity);*/
		}
		
//##############################################################################
//# HSB changes
//##############################################################################
		_this_.changes_HSB_Hooks = function() {
			_this_.jquerySelectors.$hsb_h.change(function() {
				var value = $(this).val();
				if( !(!isNaN(value) && parseInt(Number(value)) == value ) || value < 0 || value > 360 ) {
					value = 0;
					$(this).val(0);
				}
				
				_this_.changes_HSB();
			});
			
			_this_.jquerySelectors.$hsb_s.change(function() {
				var value = $(this).val();
				if( !(!isNaN(value) && parseInt(Number(value)) == value ) || value < 0 || value > 100 ) {
					value = 0;
					$(this).val(0);
				}
				
				_this_.changes_HSB();
			});
			
			_this_.jquerySelectors.$hsb_b.change(function() {
				var value = $(this).val();
				if( !(!isNaN(value) && parseInt(Number(value)) == value ) || value < 0 || value > 100 ) {
					value = 0;
					$(this).val(0);
				}
				
				_this_.changes_HSB();
			});
		}
		
		_this_.changes_HSB = function() {
			var h = _this_.jquerySelectors.$hsb_h.val();
			var s = _this_.jquerySelectors.$hsb_s.val();
			var b = _this_.jquerySelectors.$hsb_b.val();
		
			var rgb = frslib.colors.convert.hslToRgb(h, s,b);
			var hex = frslib.colors.convert.rgbToHex(rgb.r, rgb.g, rgb.b);
			var opacity = _this_.jquerySelectors.$opacity.val();
			
			_this_.changeValues(hex, opacity);
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('value', hex);
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('opacity', opacity);
		}
		
		
		_this_.afterWindowOpen = function() {
			var _currentSelectedColorPointer = {};
			frslib.callbacks.doCallback( frslib.modal.colorLibrary.events.getCurrentSelectedColor, _currentSelectedColorPointer );
			
			_this_.currentSelectedItem = _currentSelectedColorPointer[0];
			
			_this_.changeValues( _this_.currentSelectedItem.colors.hex, _this_.currentSelectedItem.colors.opacity );
			
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('value', _this_.currentSelectedItem.colors.hex );
			_this_.jquerySelectors.$colorEditorMinicolors.minicolors('opacity', _this_.currentSelectedItem.colors.opacity );
			
			_this_.jquerySelectors.$previewBefore.css('background-color', 'rgba('+_this_.currentSelectedItem.colors.rgb.r+','+_this_.currentSelectedItem.colors.rgb.g+','+_this_.currentSelectedItem.colors.rgb.b+','+_this_.currentSelectedItem.colors.opacity+')');
			//_this_.fillWindowFromColor();
		}
		
		
		_this_.fillWindowFromColor = function() {
			_this_.jquerySelectors.$hexcode.val(_this_.currentSelectedItem.colors.hex);
			//console.log(_this_.currentSelectedItem.colors.hex);
		}

		

		return _this_;
	};

})(jQuery);







