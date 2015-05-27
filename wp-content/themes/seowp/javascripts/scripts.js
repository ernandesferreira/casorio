/**
 * Theme JavaScript used in front-end
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * Custom theme JavaScript with all jQuery plugins minified and included
 * in the head of the file. Please note that by default in the front-end
 * we output minimized scripts.min.js (can be found in the same folder).
 *
 * If you edit/debug JavaScript then change
 * define('LBMN_SCRIPT_DEBUG', true); line in functions.php to include
 * this file instead of scripts.min.js
 *
 * @package    SEOWP WordPress Theme
 * @author     Vlad Mitkovsky <info@lumbermandesigns.com>
 * @copyright  2014 Lumberman Designs
 * @license    http://themeforest.net/licenses
 * @link       http://themeforest.net/user/lumbermandesigns
 *
 * -------------------------------------------------------------------
 *
 * Send your ideas on code improvement or new hook requests using
 * contact form on http://themeforest.net/user/lumbermandesigns
 */


/*!
 * jQuery Formalize Plugin
 * http://formalize.me
 */

var FORMALIZE=function(e,t,n,r){function i(e){var t=n.createElement("b");return t.innerHTML="<!--[if IE "+e+"]><br><![endif]-->",!!t.getElementsByTagName("br").length}var s="placeholder"in n.createElement("input"),o="autofocus"in n.createElement("input"),u=i(6),a=i(7);return{go:function(){var e,t=this.init;for(e in t)t.hasOwnProperty(e)&&t[e]()},init:{disable_link_button:function(){e(n.documentElement).on("click","a.button_disabled",function(){return!1})},full_input_size:function(){if(!a||!e("textarea, input.input_full").length)return;e("textarea, input.input_full").wrap('<span class="input_full_wrap"></span>')},ie6_skin_inputs:function(){if(!u||!e("input, select, textarea").length)return;var t=/button|submit|reset/,n=/date|datetime|datetime-local|email|month|number|password|range|search|tel|text|time|url|week/;e("input").each(function(){var r=e(this);this.getAttribute("type").match(t)?(r.addClass("ie6_button"),this.disabled&&r.addClass("ie6_button_disabled")):this.getAttribute("type").match(n)&&(r.addClass("ie6_input"),this.disabled&&r.addClass("ie6_input_disabled"))}),e("textarea, select").each(function(){this.disabled&&e(this).addClass("ie6_input_disabled")})},autofocus:function(){if(o||!e(":input[autofocus]").length)return;var t=e("[autofocus]")[0];t.disabled||t.focus()},placeholder:function(){if(s||!e(":input[placeholder]").length)return;FORMALIZE.misc.add_placeholder(),e(":input[placeholder]").each(function(){if(this.type==="password")return;var t=e(this),n=t.attr("placeholder");t.focus(function(){t.val()===n&&t.val("").removeClass("placeholder_text")}).blur(function(){FORMALIZE.misc.add_placeholder()}),t.closest("form").submit(function(){t.val()===n&&t.val("").removeClass("placeholder_text")}).on("reset",function(){setTimeout(FORMALIZE.misc.add_placeholder,50)})})}},misc:{add_placeholder:function(){if(s||!e(":input[placeholder]").length)return;e(":input[placeholder]").each(function(){if(this.type==="password")return;var t=e(this),n=t.attr("placeholder");(!t.val()||t.val()===n)&&t.val(n).addClass("placeholder_text")})}}}}(jQuery,this,this.document);jQuery(document).ready(function(){FORMALIZE.go()});

/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */

(function(e){typeof define=="function"&&define.amd?define(["jquery"],e):e(jQuery)})(function(e){function n(e){return e}function r(e){return decodeURIComponent(e.replace(t," "))}function i(e){e.indexOf('"')===0&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return s.json?JSON.parse(e):e}catch(t){}}var t=/\+/g,s=e.cookie=function(t,o,u){if(o!==undefined){u=e.extend({},s.defaults,u);if(typeof u.expires=="number"){var a=u.expires,f=u.expires=new Date;f.setDate(f.getDate()+a)}o=s.json?JSON.stringify(o):String(o);return document.cookie=[s.raw?t:encodeURIComponent(t),"=",s.raw?o:encodeURIComponent(o),u.expires?"; expires="+u.expires.toUTCString():"",u.path?"; path="+u.path:"",u.domain?"; domain="+u.domain:"",u.secure?"; secure":""].join("")}var l=s.raw?n:r,c=document.cookie.split("; "),h=t?undefined:{};for(var p=0,d=c.length;p<d;p++){var v=c[p].split("="),m=l(v.shift()),g=l(v.join("="));if(t&&t===m){h=i(g);break}t||(h[m]=i(g))}return h};s.defaults={};e.removeCookie=function(t,n){if(e.cookie(t)!==undefined){e.cookie(t,"",e.extend({},n,{expires:-1}));return!0}return!1}});

 /*!
 * FitVids 1.1
 *
 * Copyright 2013,
 * Chris Coyier - http://css-tricks.com
 * Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz -
 * http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 */

 (function(e){"use strict";e.fn.fitVids=function(t){var n={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){var r=document.head||document.getElementsByTagName("head")[0];var i=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}";var s=document.createElement("div");s.innerHTML='<p>x</p><style id="fit-vids-style">'+i+"</style>";r.appendChild(s.childNodes[1])}if(t){e.extend(n,t)}return this.each(function(){var t=["iframe[src*='player.vimeo.com']","iframe[src*='youtube.com']","iframe[src*='youtube-nocookie.com']","iframe[src*='kickstarter.com'][src*='video.html']","object","embed"];if(n.customSelector){t.push(n.customSelector)}var r=".fitvidsignore";if(n.ignore){r=r+", "+n.ignore}var i=e(this).find(t.join(","));i=i.not("object object");i=i.not(r);i.each(function(){var t=e(this);if(t.parents(r).length>0){return}if(this.tagName.toLowerCase()==="embed"&&t.parent("object").length||t.parent(".fluid-width-video-wrapper").length){return}if(!t.css("height")&&!t.css("width")&&(isNaN(t.attr("height"))||isNaN(t.attr("width")))){t.attr("height",9);t.attr("width",16)}var n=this.tagName.toLowerCase()==="object"||t.attr("height")&&!isNaN(parseInt(t.attr("height"),10))?parseInt(t.attr("height"),10):t.height(),i=!isNaN(parseInt(t.attr("width"),10))?parseInt(t.attr("width"),10):t.width(),s=n/i;if(!t.attr("id")){var o="fitvid"+Math.floor(Math.random()*999999);t.attr("id",o)}t.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",s*100+"%");t.removeAttr("height").removeAttr("width")})})}})(window.jQuery||window.Zepto)


/**
 * ----------------------------------------------------------------------
 * Skip link focus code snippet from _s theme
 */

 var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
     is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
     is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

 if ( ( is_webkit || is_opera || is_ie ) && 'undefined' !== typeof( document.getElementById ) ) {
 	var eventMethod = ( window.addEventListener ) ? 'addEventListener' : 'attachEvent';
 	window[ eventMethod ]( 'hashchange', function() {
 		var element = document.getElementById( location.hash.substring( 1 ) );

 		if ( element ) {
 			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
 				element.tabIndex = -1;

 			element.focus();
 		}
 	}, false );
 }


(function ($) {
	"use strict";

	/**
	* ----------------------------------------------------------------------
	* Pseudo Preloader
	*/
	// // Page enter
	// $(window).load(function() {
	// 	$('body').addClass('content-loaded');
	// });

	// // Page leave
	// $(window).bind('beforeunload', function(){
	// 	$('body').removeClass('content-loaded');
	// });

	jQuery(document).ready(function ($) {

		/**
		 * ----------------------------------------------------------------------
		 * Notification panel close
		 */

		// get panel cookie id
		var panelHash = $('.notification-panel').data('uniquehash');

		// check if we have cookie with this id  or  we are in the customizer preview window
		if ( ($.cookie(panelHash) !== 'closed') && (! $('body').hasClass('in-wp-customizer'))  ) {
			$('.notification-panel').slideDown();
		}
		if ( $('body').hasClass('in-wp-customizer') ) {
			$('.notification-panel').show();
		}

		// if notification panel close button clicked
		$('.notification-panel__close').on('click', function(e){
			$('.notification-panel').slideUp();
			// generate unique panel id and set it as cookie if panel closed
			$.cookie($('.notification-panel').data('uniquehash'), 'closed', { expires: 7, path: '/' });
		});


		/**
		* ----------------------------------------------------------------------
		* Other
		*/

		// Style form buttons
		$('input[type=submit]').addClass('button'); // style all standard form buttons appropriately

		// Close help message popup
		$('.close-help-popup').on('click', function(event) {
			event.preventDefault();
			$(this).parents('.message-popup').hide();
		});

		// Make videos responsive

		$('.site-content, .site-footer').fitVids();

		/**
		* ----------------------------------------------------------------------
		* Mailchimp for WP and Newsletter Sign-up widget
		* show submit button only when some data for the email textfield has beed set
		*/

		$('.mc4wp-form [type=submit], .nsu-form [type=submit]').hide();
		$('.mc4wp-form [type=email], .nsu-form [type=email], .mc4wp-form [type=text], .nsu-form [type=text]').keyup(function() {
			if( validateEmail($(this).val()) ){
				$('.mc4wp-form [type=submit], .nsu-form [type=submit]').show();
			} else {
				$('.mc4wp-form [type=submit], .nsu-form [type=submit]').hide();
			}
		});

		function validateEmail(email) {
			if (!email) return false;
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			return emailReg.test( email );
		}

		/**
		* ----------------------------------------------------------------------
		* Easy Modal Plugin Integration
		*/

		$('.modal').each(function(index, el) {
			var modal = $(this);
			modal.addClass('theme-lumberman');
			modal.find('.title').wrapInner('<h3></h3>');
		});

		/**
		 * ----------------------------------------------------------------------
		 * Easy social buttons (full width template)
		 */

		$('.social-icons-fullwidth .essb_links_list').css('width', 'auto');

		/**
		 * ----------------------------------------------------------------------
		 * Mega Main Menu
		 * Mobile Offcanvas menu toggle
		 */

		$('.header-menu .mobile_toggle').unbind();
		// if clicked on <a class="mobile_toggle"> toggle offcanvas panel
		// by adding/removing class from closest .off-canvas-wrap element
		$('.header-menu .mobile_toggle').on('click', function(event) {
			event.preventDefault();
			$(this).closest(".off-canvas-wrap").toggleClass("move-left");
		});

		$('.exit-off-canvas').on('click', function(event) {
			event.preventDefault();
			$(".off-canvas-wrap").removeClass("move-right").removeClass("move-left");
		})

		/**
		* ----------------------------------------------------------------------
		* Make page 100% screen height if there is no content created yet
		* and put "Activate Editor" button in the center
		*/

		var contentHeight = $('.admin-bar .site-main').outerHeight();
		if ( contentHeight < 1 ) {
			var screenHeight = $(window).height();
			var cointainerHeight = $('#global-container').outerHeight();
			var newContentHeight = screenHeight - cointainerHeight;
			// console.info('screenHeight:' + screenHeight);
			// console.info('newContentHeight:' + newContentHeight);
			if ( newContentHeight < 0 ) {
				newContentHeight = 160;
			}

			$('#content').css('min-height', newContentHeight + 'px');


			$('.dslca-activate-composer-hook').clone().appendTo('#content .entry-content');

			$('body:not(.single-lbmn_footer) #content .entry-content .dslca-activate-composer-hook').css({
				'position': 'absolute',
				'bottom': '50%',
				'right': '50%',
				'margin-right':'-80px',
				'margin-bottom':'-30px',
				'padding':'20px',
				'letter-spacing':'2px'
			});
		}

		/**
		* ----------------------------------------------------------------------
		* Hide "Activate Editor" button in Theme Customizer
		*/

		if ( $("body").hasClass('in-wp-customizer') ) {
			$('.dslca-activate-composer-hook').hide();
		}

		/**
		 * ----------------------------------------------------------------------
		 * Live Composer search form improvements
		 */

		// Add form class on text input focus
		$(".nav_search_box form input[type=text]").bind("focus", function(e) {
				$(this).parent("form").addClass('search_form_focused');
		});

		$(".nav_search_box form input[type=text]").bind("blur", function(e) {
				$(this).parent("form").removeClass('search_form_focused');
		});


		/**
		* ----------------------------------------------------------------------
		* Live Composer
		* Extended web font icons
		*/

		var processingIconInputName = '';

		function lbmn_show_icons_grid(callback) {
			// If modal with icons grid not yet created
			if ( $('.dslca-modal-allicons').length < 1 ) {

				// Create a modal window with all icon available listed inside
				var screenHeight = $(window).height();
				var liveComposerPanelHeight = $('.dslca-container').outerHeight();
				var modalHeight = screenHeight - liveComposerPanelHeight - 90;

				var modalIconsList = '<div class="dslca-modal dslca-modal-allicons" data-bg="#ca564f"><div class="modal-content"></div></div><!-- .dslca-modal -->';
				modalIconsList += '<style> .dslca-modal-allicons { height:' + modalHeight + 'px;} </style>';

				// Insert modal code after Live Composer panel code
				$('.dslca-container').after(modalIconsList);

				$( ".dslca-modal-allicons .modal-content" ).load( "./wp-admin/?lbmn_listicons=all_icons" , function() {
					callback(true);
				});
			} else {
				callback(true);
			}
		}

		function lbmn_return_selected_icon_id(callback) {
			// firstly create / make sure we have created modal with icons grid
			lbmn_show_icons_grid(function(){
				// After all the icons loaded attach a click listener
				$( ".dslca-modal-allicons .icon-item").on('click', function(event) {
					// Get selected item code
					var selectedIconCode = $(this).find('.icon-item__name').text();
					// Send selected icon code as callback
					callback (selectedIconCode)
					// Close modal window
					dslc_hide_modal( '', jQuery('.dslca-modal:visible') );

					// Prevent event mutliplying (http://stackoverflow.com/a/652536)
					event.stopImmediatePropagation();
				});

			});
		}

		function lbmn_extended_icons_elements() {
			$('.dslca-module-edit-option-icon').each(function(index, el) {
				var iconOption = $(this);

				if ( !iconOption.hasClass('lbmn-icons-extended') ) {
					// Insert 'Show All' button after icon code input field
					var buttonIconsList = ' <span class="dslca-open-modal-hook dslca-module-option-button" data-modal=".dslca-modal-allicons"><span class="dslca-icon dslc-icon-th"></span></span>';
					iconOption.append(buttonIconsList);

					$(iconOption).find('.dslca-module-option-button').on('click', function(event) {
						/* Act on the event */
						processingIconInputName = $(iconOption).find('input[type=text]').attr('name');

						lbmn_return_selected_icon_id(function(selectedIconCode){
							// Update icon code in the text input
							$('input[name=' + processingIconInputName + ']').val(selectedIconCode).change();
						});
					});

					// mark icon wrapper as processed
					iconOption.addClass('lbmn-icons-extended');
				}

			}); // $('.dslca-module-edit-option-icon').each
		}

		$(document).on('click', '.dslca-module-edit-hook', function() {
			$('.dslca-options-filter-hook').on('click', function() {
				if ( $('.dslca-module-edit-option-icon:not(.lbmn-icons-extended)').length > 0 ) {
					lbmn_extended_icons_elements();
				}
			});
		});


		/**
		* ----------------------------------------------------------------------
		* Live Composer
		* Reset control value to theme default
		*/

		if ( $('body').hasClass('dslca-enabled') ) {
		// Run the code below if Live Composer is in active mode

			// When module controls initiated add "Reset to default" buttton
			// to the controls that can be reset
			$(document).on('click', '.dslca-module-edit-hook', function() {
				$('.dslca-options-filter-hook').on('click', function() {
					var resetButtonHTML = '<span class="button-reset">Reset to default</span>';
					$( '.dslca-module-edit-option-font, '+

						'.dslca-module-edit-option-css_main_font_size, '+
						'.dslca-module-edit-option-css_h1_font_size, '+
						'.dslca-module-edit-option-css_h2_font_size, '+
						'.dslca-module-edit-option-css_h3_font_size, '+
						'.dslca-module-edit-option-css_h4_font_size, '+
						'.dslca-module-edit-option-css_h5_font_size, '+
						'.dslca-module-edit-option-css_h6_font_size, '+

						'.dslca-module-edit-option-css_main_font_weight, '+
						'.dslca-module-edit-option-css_h1_font_weight, '+
						'.dslca-module-edit-option-css_h2_font_weight, '+
						'.dslca-module-edit-option-css_h3_font_weight, '+
						'.dslca-module-edit-option-css_h4_font_weight, '+
						'.dslca-module-edit-option-css_h5_font_weight, '+
						'.dslca-module-edit-option-css_h6_font_weight, '+

						'.dslca-module-edit-option-css_main_line_height, '+
						'.dslca-module-edit-option-css_h1_line_height, '+
						'.dslca-module-edit-option-css_h2_line_height, '+
						'.dslca-module-edit-option-css_h3_line_height, '+
						'.dslca-module-edit-option-css_h4_line_height, '+
						'.dslca-module-edit-option-css_h5_line_height, '+
						'.dslca-module-edit-option-css_h6_line_height, '+

						// '.dslca-module-edit-option-css_main_margin_bottom, '+
						'.dslca-module-edit-option-css_h1_margin_bottom, '+
						'.dslca-module-edit-option-css_h2_margin_bottom, '+
						'.dslca-module-edit-option-css_h3_margin_bottom, '+
						'.dslca-module-edit-option-css_h4_margin_bottom, '+
						'.dslca-module-edit-option-css_h5_margin_bottom, '+
						'.dslca-module-edit-option-css_h6_margin_bottom, '+

						'.dslca-module-edit-option-css_link_color, '+
						'.dslca-module-edit-option-css_link_color_hover, '+
						'.dslca-module-edit-option-css_main_color, '+
						'.dslca-module-edit-option-css_h1_color, '+
						'.dslca-module-edit-option-css_h2_color, '+
						'.dslca-module-edit-option-css_h3_color, '+
						'.dslca-module-edit-option-css_h4_color, '+
						'.dslca-module-edit-option-css_h5_color, '+
						'.dslca-module-edit-option-css_h6_color'
						).each(function(index, el) {
							if ( $(this).find('.button-reset').length == 0 ) {
								$(this).find('.dslca-module-edit-label').append(resetButtonHTML);
							}
					});


					// Add font loading spinner
					$('.dslca-module-edit-option-font').each(function(index, el) {
						$(this).append('<span class="dslc-icon-refresh dslc-icon-spin"></span>');
					});
				});
			});

			// dslcAllFontsArray is array of all fonts defined in /ds-live-composer/js/main.js
			// to make possible to use default theme font defined in Theme Customizer
			// we add 'inherit' option
			dslcAllFontsArray.push('');
			dslcRegularFontsArray.push('');

			// Control reset functionality
			$(document).on('click', '.dslca-module-edit-options-wrapper .button-reset', function(event) {
				var moduleBlock = $(this).closest('.dslca-module-edit-option');
				$(moduleBlock).find('input').val('').change();
				$(moduleBlock).find('.dslca-module-edit-field-font-suggest').text('');
				$(moduleBlock).find('.ui-slider-handle').css('left','0');
				$(moduleBlock).find('.sp-preview-inner').addClass('sp-clear-display').css('background-color','transparent');
			});

		}

	}); // document.ready
})(jQuery);