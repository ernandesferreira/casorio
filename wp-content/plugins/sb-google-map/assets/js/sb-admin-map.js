/* Map admin scripts */
/* version 1.0 */
var sb_markers = new Array();
var sb_total_maps = 0;
(function($) {
		  
	$(document).ready(function(e) {
		
		//Focus on title field
		if($('#titlewrap #title').length) {
			$('#titlewrap #title').focus();
		}
		
		//Toggle Radio Button
		if($('.sb-radio-group').length) {
			$('.sb-radio-group input[type=radio]').click(function(){
				$(this).closest('.sb-radio-group').find('.radio-checked').removeClass('radio-checked');
				$(this).closest('label').addClass('radio-checked');
			});
		}
		
		//Tooltip
		if($('.sb-tooltip .help-icon').length) {
			$('.sb-tooltip').hover(function(){
				$(this).find('.tooltip-content').stop(true,true).fadeIn();
			}, function(){
				$(this).find('.tooltip-content').fadeOut();
			});
		}
		
		//Confirm box for on trigger delete
		$('#frm-google-map-list table .row-actions .delete a').click(function() {
			if(confirm('Are you really want to delete this record?')) {
				return;
			}
			return false;
		});
		
		//Google place autocomplete api for marker address field
		var total_markers = $('.map-marker').length;
		for(var i = 1; i <= total_markers; i++ ) {
			var autocomplete_address = (document.getElementById('address-'+i));
			var autocomplete = new google.maps.places.Autocomplete(autocomplete_address);
		}
		
		//Google place autocomplete api for center of map field
		if($('#centerpoint').length) {
			var autocomplete_address = (document.getElementById('centerpoint'));
			var autocomplete = new google.maps.places.Autocomplete(autocomplete_address);
		}

		//Add multiple marker function
		$('#add-more-marker').click(function(){
			var total_markers = $('.map-marker').length;
			total_markers++;
			var markerhtml = '';
			markerhtml += '<tr class="map-marker">';
			markerhtml += '<td><input type="text" name="address[]" class="address" id="address-'+total_markers+'"></td>';
			markerhtml += '<td style="display:none;"><input type="text" name="latitude[]" class="latitude"></td>';
			markerhtml += '<td style="display:none;"><input type="text" name="longitude[]" class="longitude"></td>';
			markerhtml += '<td><input type="text" name="textfordirectionslink[]" class="directionstext"></td>';
			markerhtml += '<td><input type="text" value="blue-1" name="icon[]" class="icon" readonly="readonly"></td>';
			markerhtml += '<td><select name="animation[]" class="animation"><option selected="selected" value="NONE">NONE</option><option value="DROP">DROP</option><option value="BOUNCE">BOUNCE</option></select></td>';
			markerhtml += '<td><select name="infowindow[]" class="infowindow"><option value="yes">yes</option><option selected="selected" value="no">no</option></select></td>';
			markerhtml += '<td><textarea name="content[]" class="content" rows="1"></textarea></td>';
			markerhtml += '<td><button type="button" class="sb-button remove-marker">x</button></td>';
			markerhtml += '</tr>';
			$('#map-markers tbody').append(markerhtml);

			//Google place autocomplete api for marker address field
			var autocomplete_address = (document.getElementById('address-'+total_markers));
			var autocomplete = new google.maps.places.Autocomplete(autocomplete_address);
			
		});
		
		//Toggle Panel
		$('#sb-google-maps-plugin .handlediv, #sb-google-maps-plugin h3.hndle').click(function(){
			$(this).parent('.postbox').toggleClass('closed');
		});
				
	});
	
	//Trim strings
	function sb_trim(string) {
		return $.trim(string);
	}
	
	//Lazyload Image Function
	function lazyload(e,viewport) {
		var a = e.offset().top;
		var b = a - viewport.scrollTop();
		if (b < viewport.height()) {
			var h = e.attr("data-src");
			e.attr('src',h).show();
		}
	}
	
	//Calling lazyload on viewport scroll event
	$('#preview-content').scroll(function () {
		lazyscroll($(this));
	});
	
	//Check all images against viewport
	function lazyscroll(viewport) {
		$('img.lazyload').each(function(){
			lazyload($(this),viewport);
		});
	}
	
	//Validating Map Form
	$('#frm-google-map').submit(function() {
		var error = false;
		
		var map_title = $('#title');
		if($.trim(map_title.val()) == '') {
			map_title.addClass('error');
			map_title.focus();
			error = true
		} else {
			map_title.removeClass('error');
		}
		if(error) {
			$('html, body').animate({scrollTop:0});
			return false;
		}
		
	});
	
	//Remove marker
	$('body').on('click','.remove-marker',function(){
		$(this).closest('tr').remove();
	});
	
	//Icons Popup
	var $iconbox = $("#icons-content");
	$iconbox.dialog({                   
		dialogClass   	:	'wp-dialog',
		modal         	: 	true,
		title			:	'Select icon or insert custom icon url',
		width			:	700,
		draggable     	: 	false,
		resizable     	: 	false,
		autoOpen      	: 	false,
		closeOnEscape 	: 	false,
		open			: 	function() {
			$('.ui-dialog-buttonpane').find('button:contains("Select")').addClass('button-primary');
		},
		buttons       	:	{
			"Select": function() {
				var final_icon = $.trim($('#final-icon').val());
				if(final_icon == '') {
					$('#final-icon').addClass('error');
					return false;
				} else {
					$('#final-icon').removeClass('error');
					$current_icon_obj.val(final_icon);
					$(this).dialog('close');
				}
			},
			"Close": function() { $(this).dialog('close'); }
		}
	});
	
	//Set current selected icon
	$('body').on('click','input.icon',function(event) {
		event.preventDefault();
		$iconbox.dialog('open');
		$current_icon_obj = $(this);
		
		$('#icons-list li').removeClass('selected');
		
		$('#final-icon').val($current_icon_obj.val());
		
		if($('#icons-list li #icon-'+$current_icon_obj.val()).length) {
			$('#icons-list li #icon-'+$current_icon_obj.val()).closest('li').addClass('selected');
		}
		
	});
	
	//Select icon
	$('#icons-list input[type=radio]').click(function(){
		$('#icons-list li').removeClass('selected');
		$(this).closest('li').addClass('selected');
		$('#final-icon').val($(this).val());
		$('#final-icon').removeClass('error');
	});
	
	//Validation for  icon popup
	$('#final-icon').keyup(function(){
		if($.trim($('#final-icon').val()) == '') {
			$('#final-icon').addClass('error');
		} else {
			$('#final-icon').removeClass('error');
		}
	});
	
	//Map Style Preview Box
	var $previewbox = $("#preview-content");
	$previewbox.dialog({                   
		dialogClass   	:	'wp-dialog',
		modal         	: 	true,
		title			:	'Click on preview to select map style',
		width			:	700,
		height			:	560,
		draggable     	: 	false,
		resizable     	: 	false,
		autoOpen      	: 	false,
		closeOnEscape 	: 	false,
		open			: 	function() {
			$('.ui-dialog-buttonpane').find('button:contains("Select")').addClass('button-primary');
		},
		buttons       	:	{
			"Close": function() { $(this).dialog('close'); }
		}
	});
	
	//Open Map Style popup and set selected style
	$('body').on('click','#map-styles-preview-link',function(event) {
		event.preventDefault();
		$('#previews-list li.selected').removeClass('selected');
		$('#preview-'+$('#mapstyles').val()).closest('li').addClass('selected');
		$previewbox.dialog('open');
	});
	
	//Select style from preview popup
	$('body').on('click','#previews-list li',function(event) {
		event.preventDefault();
		var mapstyle = $(this).children('input[type=radio]').val();
		$('#mapstyles').val(mapstyle);
		$previewbox.dialog('close');
	});

})(jQuery);