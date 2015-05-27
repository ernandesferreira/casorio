(function($){

	"use strict";

	frslib.provide('frslib._classes');

	frslib._classes.modalWindowAdvanced = function(){

		var _this_ = frslib._classes.modalWindow();

		// Propeties

		_this_.useFixedHeaders = true;

		_this_.modalWindowAdvanced_selectors = {
			  itemsContainer : '.ff-modal-library-items-container'
			, placeholdersContainer : '.ff-modal-library-items-groups-titles-container'
			, placeholdersWrapper : '.ff-modal-library-items-groups-titles-wrapper'
			, placeholders : '.ff-modal-library-items-groups-titles'
			, itemsWrapper : '.ff-modal-library-items'
			, itemsTitles : '.ff-modal-library-items-group .ff-modal-library-items-group-title'
			, oneItem : '.ff-modal-library-items-group-item'
		};

		// Construct

		_this_.initSelectors = _this_.modalWindowAdvanced_initSelectors = function(){
			_this_.modalWindow_initSelectors();
			_this_.updateSelectors( _this_.modalWindowAdvanced_selectors );
		};

		_this_.init = _this_.modalWindowAdvanced_init = function(){

			_this_.modalWindow_init();

			if( _this_.useFixedHeaders ){
				_this_.initFixedLikeHeaders();
			}
		};

		_this_.reinit = _this_.modalWindowAdvanced_reinit = function(){

			if( _this_.useFixedHeaders ){
				_this_.copyPlaceholders();
				_this_.placeholdersResizing();
			}
		};

		// Methods

		// Fixed Like Headers
		_this_.initFixedLikeHeaders = function(){

			_this_.$itemsContainer = _this_.$modalWindow.find( _this_.selectors.itemsContainer );

			_this_.$placeholdersContainer = _this_.$itemsContainer.find( _this_.selectors.placeholdersContainer );
			_this_.$placeholdersWrapper = _this_.$placeholdersContainer.find( _this_.selectors.placeholdersWrapper );
			_this_.$placeholders = _this_.$placeholdersWrapper.find( _this_.selectors.placeholders );

			_this_.$itemsWrapper = _this_.$itemsContainer.find( _this_.selectors.itemsWrapper );
			_this_.$itemsTitles = _this_.$itemsWrapper.find( _this_.selectors.itemsTitles );

			$(window).load(function(){
				_this_.copyPlaceholders();
				_this_.placeholdersResizing();
				_this_.placeholdersScrolling();
			})

			$(window).resize(function(){
				_this_.placeholdersResizing();
			})
		};

		// Add placeholders item into placeholder wrapper
		_this_.copyPlaceholders = function(){

			// MARVIN TODO --- TMP ---

			_this_.$placeholdersContainer.css('z-index',9999);
			_this_.$placeholdersContainer.css('height','47px');
			_this_.$placeholdersContainer.css('overflow','hidden');

			// MARVIN TODO END --- TMP ---

			_this_.$placeholders.html('');

			// Add placeholders item into placeholder wrapper
			_this_.$itemsTitles.each(function( index ){

				// --- TMP ---
				$(this).find('span').html(index);
				// --- TMP ---

				$(this).attr('data-title-index', index);
				$(this).addClass('ff-modal-library-items-group-title-' + index);
				_this_.$placeholders.append( $(this).clone() );
			});

		};

		_this_.placeholdersScrolling = function(){

			_this_.$itemsContainer.scroll(function(){

				var _scrollTop = _this_.$itemsContainer.scrollTop();

				// Container Scrolling

				_this_.$placeholdersContainer.css( 'margin-top', _scrollTop + 'px' );

				// Wrapper Scrolling

				var MAX_HEIGHT = 47;
				var new_margin = 0;

				var index_repair = 0;

				_this_.$itemsTitles.each(function( index ){
					if( $(this).hasClass('zero') ){
						index_repair ++;
						return;
					}
					var placeholderTopAttr = 1 * $(this).attr('data-top');

					if( placeholderTopAttr < _scrollTop + MAX_HEIGHT ){
						if( placeholderTopAttr < _scrollTop ){
							new_margin = (index_repair - index ) * MAX_HEIGHT;
						}else{
							new_margin = ( (index_repair - index) * MAX_HEIGHT) - _scrollTop + placeholderTopAttr;
						}
					}

				});

				_this_.$placeholdersWrapper.css('margin-top', new_margin + 'px' );
			});

		};

		_this_.placeholdersResizing = function(){

			var scroll_top = $(document).scrollTop();
			var wrapper_scroll_top = _this_.$itemsContainer.scrollTop();
			var wrapper_top = _this_.$itemsContainer.offset().top;

			_this_.$itemsTitles.each(function(){
				var placeholder_top = $(this).offset().top - wrapper_top + wrapper_scroll_top;
				var val_selector = '.ff-modal-library-items-group-title-' + $(this).attr('data-title-index');

				_this_.$placeholdersContainer.find( val_selector ).attr('data-top', placeholder_top);
				$(this).attr('data-top', placeholder_top);
			});

		};

		return _this_;

	};

})(jQuery);













