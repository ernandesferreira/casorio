/******************************************************************************/
/** Global fresh library object, used across all our plugins and shit. Here we
/** assign sub-libraries, important for other our stuff. It also cooperates with
/** jquery
/******************************************************************************/
"use strict";

var frslib = frslib || {};
////////////////////////////////////////////////////////////////////////////////
// FROM GOOGle CLOSURE
////////////////////////////////////////////////////////////////////////////////
frslib.global = this;
frslib.isDef = function(val) {
	  return val !== undefined;
	};
frslib.exportPath_ = function(name, opt_object, opt_objectToExportTo) {
	  var parts = name.split('.');
	  var cur = opt_objectToExportTo || frslib.global;

	  // Internet Explorer exhibits strange behavior when throwing errors from
	  // methods externed in this manner.  See the testExportSymbolExceptions in
	  // base_test.html for an example.
	  if (!(parts[0] in cur) && cur.execScript) {
	    cur.execScript('var ' + parts[0]);
	  }

	  // Certain browsers cannot parse code in the form for((a in b); c;);
	  // This pattern is produced by the JSCompiler when it collapses the
	  // statement above into the conditional loop below. To prevent this from
	  // happening, use a for-loop and reserve the init logic as below.

	  // Parentheses added to eliminate strict JS warning in Firefox.
	  for (var part; parts.length && (part = parts.shift());) {
	    if (!parts.length && frslib.isDef(opt_object)) {
	      // last part and we have an object; use it
	      cur[part] = opt_object;
	    } else if (cur[part]) {
	      cur = cur[part];
	    } else {
	      cur = cur[part] = {};
	    }
	  }
};

frslib.provide = function( name ) {
	return frslib.exportPath_(name);
};
////////////////////////////////////////////////////////////////////////////////
// HTML FORMS
////////////////////////////////////////////////////////////////////////////////
frslib.provide('frslib.htmlforms');
(function($){
	frslib.htmlforms.writeValueToCode = function( $selector ) {
		$selector.find('input').each(function(){
			var val = $(this).val();
			$(this).attr('value', val);
			
			if( $(this).attr('type') == 'checkbox' ) {
				var checked = $(this).is(':checked');
				
				if( checked ) {
					$(this).attr('checked', 'checked');
				}
				else {
					$(this).prop('checked', false);
					$(this).removeAttr('checked');
				}
			}
		});
	}
})(jQuery);
////////////////////////////////////////////////////////////////////////////////
// CALLBACKS
////////////////////////////////////////////////////////////////////////////////
frslib.provide('frslib.callbacks');

(function($){
	//console.log(frslib['htmlforms']['writeValueToCode']);
	frslib.callbacks.functions = Array();
	frslib.callbacks.addCallback = function( eventName, callback ) {
		frslib.provide('frslib.callbacks.functions.'+eventName);
		frslib.callbacks.functions[eventName] = new Array();
		frslib.callbacks.functions[eventName].push(callback);
	}
	
	frslib.callbacks.doCallback = function( eventName ) {
		
		if( !(eventName in frslib.callbacks.functions) ) {
			return false;
		}
		
		var newArguments = Array();
		
		for( var argumentsKey in arguments ) {
			if( !Number.isNaN(argumentsKey) && argumentsKey > 0 ){
				newArguments[ argumentsKey-1 ] = arguments[ argumentsKey ];
			}
		}
		
		var output = {};
		
		for( var key in frslib.callbacks.functions[eventName] ) {
			output[key] = frslib.callbacks.functions[eventName][key].apply( this,newArguments);
		}
		
		return output;
	}
})(jQuery);

////////////////////////////////////////////////////////////////////////////////
//COLORS
////////////////////////////////////////////////////////////////////////////////
frslib.provide('frslib.colors');
frslib.provide('frslib.colors.convert');
frslib.provide('frslib.colors.type');

(function($){
	frslib.colors.convert.hexToRgb = function(hex) {
	    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	    return result ? {
	        r: parseInt(result[1], 16),
	        g: parseInt(result[2], 16),
	        b: parseInt(result[3], 16)
	    } : null;
	};
	
	frslib.colors.convert.hslToRgb = function(h, s, l){

	    var r, g, b, m, c, x

	    if (!isFinite(h)) h = 0
	    if (!isFinite(s)) s = 0
	    if (!isFinite(l)) l = 0

	    h /= 60
	    if (h < 0) h = 6 - (-h % 6)
	    h %= 6

	    s = Math.max(0, Math.min(1, s / 100))
	    l = Math.max(0, Math.min(1, l / 100))

	    c = (1 - Math.abs((2 * l) - 1)) * s
	    x = c * (1 - Math.abs((h % 2) - 1))

	    if (h < 1) {
	        r = c
	        g = x
	        b = 0
	    } else if (h < 2) {
	        r = x
	        g = c
	        b = 0
	    } else if (h < 3) {
	        r = 0
	        g = c
	        b = x
	    } else if (h < 4) {
	        r = 0
	        g = x
	        b = c
	    } else if (h < 5) {
	        r = x
	        g = 0
	        b = c
	    } else {
	        r = c
	        g = 0
	        b = x
	    }

	    m = l - c / 2
	    r = Math.round((r + m) * 255)
	    g = Math.round((g + m) * 255)
	    b = Math.round((b + m) * 255)

	    return { r: r, g: g, b: b }

      };
	
	frslib.colors.convert.rgbToHsl = function (r, g, b){
	    r /= 255, g /= 255, b /= 255;
	    var max = Math.max(r, g, b), min = Math.min(r, g, b);
	    var h, s, l = (max + min) / 2;

	    if(max == min){
	        h = s = 0; // achromatic
	    }else{
	        var d = max - min;
	        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
	        switch(max){
	            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
	            case g: h = (b - r) / d + 2; break;
	            case b: h = (r - g) / d + 4; break;
	        }
	        h /= 6;
	    }

	    return  { h:Math.floor(h * 360), s:Math.floor(s * 100), b:Math.floor(l * 100) };
	};
	
	frslib.colors.convert.toArray = function(color) {
		 
	    var cache
	      , p = parseInt // Use p as a byte saving reference to parseInt
	      , color = color.replace(/\s\s*/g,'') // Remove all spaces
	    ;//var
	    
	    var rgbaType = 0;
	    
	    // Checks for 6 digit hex and converts string to integer
	    if (cache = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})/.exec(color)) 
	        cache = [p(cache[1], 16), p(cache[2], 16), p(cache[3], 16)];
	        
	    // Checks for 3 digit hex and converts string to integer
	    else if (cache = /^#([\da-fA-F])([\da-fA-F])([\da-fA-F])/.exec(color))
	        cache = [p(cache[1], 16) * 17, p(cache[2], 16) * 17, p(cache[3], 16) * 17];
	        
	    // Checks for rgba and converts string to
	    // integer/float using unary + operator to save bytes
	    else if (cache = /^rgba\(([\d]+),([\d]+),([\d]+),([\d]+|[\d]*.[\d]+)\)/.exec(color)) {
	        cache = [+cache[1], +cache[2], +cache[3], +cache[4]];
	        rgbaType = 1;
	    }
	        
	    // Checks for rgb and converts string to
	    // integer/float using unary + operator to save bytes
	    else if (cache = /^rgb\(([\d]+),([\d]+),([\d]+)\)/.exec(color))
	        cache = [+cache[1], +cache[2], +cache[3]];
	        
	    // Otherwise throw an exception to make debugging easier
	    else throw Error(color + ' is not supported by $.parseColor');
	    
	    // Performs RGBA conversion by default
	    isNaN(cache[3]) && (cache[3] = 1);
	    
	    // Adds or removes 4th value based on rgba support
	    // Support is flipped twice to prevent erros if
	    // it's not defined
	    var parsedColor =  cache.slice(0,3 + rgbaType);
	    
	    var toReturn = {};
	    toReturn.r = parsedColor[0];
	    toReturn.g = parsedColor[1];
	    toReturn.b = parsedColor[2];
	    
	    if( rgbaType == 1 ) {
	    	toReturn.a = parsedColor[3];
	    } else {
	    	toReturn.a = 1;
	    }
	    
	    return toReturn;
	    
	}
	
	
	frslib.colors.type.rgba = 'rgba';
	frslib.colors.type.rgb = 'rgb';
	frslib.colors.type.hex = 'hex';
	
	
	frslib.colors.type.identify = function( colorValue ) {
		if( colorValue.toLowerCase().indexOf('rgba') != -1 ) {
			return frslib.colors.type.rgba;
		} else if( colorValue.toLowerCase().indexOf('rgb') != -1 ) {
			return frslib.colors.type.rgb;
		} else if( colorValue.indexOf('#') != -1 ) {
			return frslib.colors.type.hex;
		}
	};
	
	frslib.colors.convert.rgbToHex = function (r,g,b) {
		r = r.toString(16);
		g = g.toString(16);
		b = b.toString(16);
	
		if( r == 0 ) {
			r = '00';
		}
		if( g == 0 ) {
			g = '00';
		}
		if( b == 0 ) {
			b = '00';
		}

	  return '#' + r.toString(16) + g.toString(16) + b.toString(16);
	};
})(jQuery);
////////////////////////////////////////////////////////////////////////////////
//AJAX
////////////////////////////////////////////////////////////////////////////////
frslib.provide('frslib.ajax');

(function($){
	frslib.ajax.frameworkRequest = function( owner, specification, data, callback ) {
		$.post(
				ajaxurl,
				{
					'action':'ff_ajax',
					'owner': owner,
					'specification':specification,
					'data':data
				},
				callback
		);
	};
	
	frslib.ajax.adminScreenRequest = function( specification, data, callback ) {
		
		// ff-view-identification admin-screen-name admin-view-name
		var adminScreenName = $('.ff-view-identification').find('.admin-screen-name').html();
		var adminViewName =$('.ff-view-identification').find('.admin-view-name').html(); 
		
		var data = {
				'adminScreenName' : adminScreenName,
				'adminViewName' : adminViewName,
				'specification' : specification,
				'action' : 'ff_ajax_admin',
				'data' : data
		}
		
		$.post(
				ajaxurl,
				data,
				callback
		);
	}
})(jQuery);