var file_inputs= new Array();
var file_ext= new Array();
jQuery(document).ready(
function ()
	{
	
	
	jQuery('input[type="text"], textarea.required').keyup(
		function()
			{
			jQuery(this).popover('destroy')
			if(jQuery(this).val()=='')
				{
				jQuery(this).popover({trigger:'manual'});
				jQuery(this).popover('show');
				jQuery(this).parent().find('.popover').addClass(jQuery(this).attr('data-error-class'));	
				}
			else
				jQuery(this).popover('destroy')
			}
		);
		
	jQuery('.the-radios a, .the-radios .input-label').on('click', 
			function(e)
				{
				jQuery(this).closest('#the-radios').popover('destroy');
				}
			);
	jQuery('#star img').on('click', 
			function(e)
				{
				jQuery(this).parent().popover('destroy');
				}
			);
	jQuery('#select').on('change', 
			function(e)
				{
				jQuery(this).parent().find('.selectpicker').popover('destroy');
				}
			);
	jQuery('input.the_slider').change(
		function()
			{
			jQuery(this).parent().find('.error_message').popover('destroy');
			}
		);
	jQuery('.bootstrap-touchspin .btn').click(
		function()
			{
			jQuery(this).parent().parent().find('.error_message').popover('destroy');
			}
		);
	jQuery('#tags').change(
		function()
			{
			jQuery(this).parent().find('.error_message').popover('destroy');
			}
		);
	jQuery('input#selected-color').blur(
		function()
			{
			jQuery(this).parent().find('.error_message').popover('destroy');
			}
		);
	/*jQuery('input.email').keyup(
		function()
			{
			jQuery(this).popover('destroy')
			if(!IsValidEmail(jQuery(this).val()))
				{
				jQuery(this).popover({trigger:'manual'});
				jQuery(this).popover('show');
				jQuery(this).parent().find('.popover').addClass(jQuery(this).attr('data-error-class'));	
				}
			else
				jQuery(this).popover('destroy')
			}
		);*/
	jQuery('div.nex-submit').click(
		function()
			{
			jQuery(this).closest('form').submit();
			}
		)
	
	jQuery('div.ui-nex-forms-container .nex-step').live('click',
		function()
			{
			if(validate_form(jQuery(this).closest('form')))
				{
				jQuery(this).closest('.step').hide();
				jQuery(this).closest('.step').next('.step').show();
				}
			}
		);
	jQuery('div.ui-nex-forms-container .prev-step').live('click',
		function()
			{
			jQuery(this).closest('.step').hide();
			jQuery(this).closest('.step').prev('.step').show();
			}
		);
		
			
	jQuery('form.submit-nex-form').ajaxForm({
    data: {
       action: 'submit_nex_form'
    },
    dataType: 'json',
    beforeSubmit: function(formData, jqForm, options) {
		 
		 if(validate_form(jqForm))
			{
			var submit_val = jqForm.find('.nex-submit').html();
			//var get_padding = formObj.find('input[type="submit"]').css('padding-left');
			//formObj.find('input[type="submit"]').css('padding-left','20px');
			//jQuery('<span class="fa fa-spinner fa-spin"></span>').insertBefore(formObj.find('input[type="submit"]'));
			//formObj.find('.fa-spinner').css('color',formObj.find('input[type="submit"]').css('color'));
			
			jqForm.find('.nex-submit').html('<span class="fa fa-spinner fa-spin"></span>&nbsp;&nbsp;' + submit_val);
			return true;
			}
		else
			return false;
		 
		 
    },
    success : function(responseText, statusText, xhr, $form) {
      if(jQuery('#nex-forms #on_form_submmision').text()=='redirect')
			{
			$form.fadeOut('fast');
			var url = '' + jQuery('#nex-forms #confirmation_page').text()+'';    
			jQuery(location).attr('href',url);
			}
		else
			{
			$form.fadeOut('fast',
				function(){
				$form.closest('.ui-nex-forms-container').find('.nex_success_message').fadeIn('slow');
				}
			);
			var offset = $form.closest('.ui-nex-forms-container').find('.nex_success_message').offset();
			jQuery("html, body").animate(
					{
					scrollTop:offset.top-100
					},500
				);
			}
		}
	});
	}
);


function validate_form(object){
	
	
	jQuery('.ui-nex-forms-container input[type="file"]').each(
		function()
			{
			var items = jQuery(this).closest('.form_field').parent().find('div.get_file_ext').text();
			var set_name = new Array(jQuery(this).attr('name'))
			set_name.push(items.split('\n'));
			file_inputs.push(set_name);
			}
		);
	
	var current_form = object;
	
	//console.log(current_form)
	
	var formdata = {
                   radios : [], //an array of already validate radio groups
                   checkboxes : [], //an array of already validate checkboxes
                   runCnt : 0, //number of times the validation has been run
                   errors: 0
               }
	
	var defaultErrorMsgs = {
                    email : 'Not a valid email address',
                    number: 'Not a valid phone number',
                    required: 'Please enter a value',
                    text: 'Only text allowed'
                }
	
	var settings = {
                'requiredClass': 'required',
                'customRegex': false,
				'errors' : 0,
                'checkboxDefault': 0,
                'selectDefault': 0,
                'beforeValidation': null,
                'onError': null,
                'onSuccess': null,
                'beforeSubmit': null, 
                'afterSubmit': null,
                'onValidationSuccess': null,
                'onValidationFailed': null
            };
	
	settings.errors = 0;
	jQuery(current_form).find('input').each( function() {
		
		
		var input = jQuery(this);
		var val = input.val();                                                                                
		var name = input.attr('name');
		
		if(input.is('input'))
			{
			
			var type = input.attr('type');
			switch(type)
				{
				case 'text':
					if(input.hasClass('required') && input.is(':visible')) {
					
						if(input.hasClass('the_slider') && val==input.parent().find('#slider').attr('data-starting-value'))
							{
							settings.errors++;
							input.parent().find('.error_message').popover({trigger:'manual'});
							input.parent().find('.error_message').popover('show');
							input.parent().find('.error_message').parent().find('.popover').addClass(input.parent().find('.error_message').attr('data-error-class'));
							}
						
						
						if(input.hasClass('the_spinner') && val==input.attr('data-starting-value'))
							{
							settings.errors++;
							input.parent().find('.error_message').popover({trigger:'manual'});
							input.parent().find('.error_message').popover('show');
							input.parent().find('.error_message').parent().find('.popover').addClass(input.parent().find('.error_message').attr('data-error-class'));
							
							}
						
						
						
						if(val.length < 1 || val=='')
							{
								settings.errors++;                               
								if(input.hasClass('star_rating'))
									{
									input.closest('.error_message').popover({trigger:'manual'});
									input.closest('.error_message').popover('show');
									input.closest('.error_message').parent().find('.popover').addClass(input.closest('.error_message').attr('data-error-class'));
									}
								else if(input.hasClass('tags'))
									{
									input.parent().find('.error_message').popover({trigger:'manual'});
									input.parent().find('.error_message').popover('show');
									input.parent().find('.popover').addClass(input.parent().find('.error_message').attr('data-error-class'));
									}
								else
									{
									input.popover({trigger:'manual'});
									input.popover('show');
									input.parent().find('.popover').addClass(input.attr('data-error-class'));
									}
									
								break;
							}
						 else
							{
							//input.popover('destroy');
							//input.closest('.error_message').popover('destroy');
							if(input.hasClass('email') && input.is(':visible'))
								{
								if(!IsValidEmail(val))
									{   
									settings.errors++;
									input.popover({trigger:'manual'});
									input.popover('show');
									if(input.attr('data-secondary-message'))
										input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
									input.parent().find('.popover').addClass(input.attr('data-error-class'));
									break;
									}
								else
									{
									jQuery(this).popover('destroy')
									break;
									}
								}
							else if(input.hasClass('phone_number') && input.is(':visible'))
								{
								if(!allowedChars(val, 'tel'))
									{
									settings.errors++;
									input.popover({trigger:'manual'});
									input.popover('show');
									if(input.attr('data-secondary-message'))
										input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
									input.parent().find('.popover').addClass(input.attr('data-error-class'));
									break;
									} 
								else
									{
									jQuery(this).popover('destroy')
									break;
									}  
								}
							else if(input.hasClass('numbers_only') && input.is(':visible'))
								{
								if(!isNumber(val))
									{
									settings.errors++;
									input.popover({trigger:'manual'});
									input.popover('show');
									if(input.attr('data-secondary-message'))
										input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
									input.parent().find('.popover').addClass(input.attr('data-error-class'));
									break;
									} 
								else
									{
									jQuery(this).popover('destroy')
									break;
									} 
								}
							else if(input.hasClass('text_only') && input.is(':visible'))
								{
								if(!allowedChars(val, 'text'))
									{
									settings.errors++;
									input.popover({trigger:'manual'});
									input.popover('show');
									if(input.attr('data-secondary-message'))
										input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
									input.parent().find('.popover').addClass(input.attr('data-error-class'));
									break;
									} 
								else
									{
									jQuery(this).popover('destroy')
									break;
									} 
								}
							else if(input.hasClass('url') && input.is(':visible'))
								{
								if(!validate_url(val))
									{
									settings.errors++;
									input.popover({trigger:'manual'});
									input.popover('show');
									if(input.attr('data-secondary-message'))
										input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
									input.parent().find('.popover').addClass(input.attr('data-error-class'));
									break;
									} 
								else
									{
									jQuery(this).popover('destroy')
									break;
									}  
								}
							}
						}
				    else if(input.hasClass('email') && val!='' && input.is(':visible')) {
					   if(!IsValidEmail(val)) {  
							settings.errors++;
							input.popover({trigger:'manual'});
							input.popover('show');
							if(input.attr('data-secondary-message'))
								input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
								
							input.parent().find('.popover').addClass(input.attr('data-error-class'));
							break;
					   }
					}
					else if(input.hasClass('phone_number') && val!='' && input.is(':visible')) {
					   if(!allowedChars(val, 'tel')) {
							settings.errors++;
							input.popover({trigger:'manual'});
							input.popover('show');
							if(input.attr('data-secondary-message'))
								input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
							input.parent().find('.popover').addClass(input.attr('data-error-class'));
							break;
					   }
					}
					else if(input.hasClass('numbers_only') && val!='' && input.is(':visible')) {
					   if(!isNumber(val)) {
							settings.errors++;
							input.popover({trigger:'manual'});
							input.popover('show');
							if(input.attr('data-secondary-message'))
								input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
							input.parent().find('.popover').addClass(input.attr('data-error-class'));
							break;
					   }
					}
					else if(input.hasClass('text_only') && val!='' && input.is(':visible')) {
					   if(!allowedChars(val, 'text')) {
							settings.errors++;
							input.popover({trigger:'manual'});
							input.popover('show');
							if(input.attr('data-secondary-message'))
								input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
							input.parent().find('.popover').addClass(input.attr('data-error-class'));
							break;
					   }
					}
					else if(input.hasClass('url') && val!='' && input.is(':visible')) {
					   if(!validate_url(val)) {
							settings.errors++;
							input.popover({trigger:'manual'});
							input.popover('show');
							if(input.attr('data-secondary-message'))
								input.parent().find('.popover-content').text(input.attr('data-secondary-message'));
							input.parent().find('.popover').addClass(input.attr('data-error-class'));
							break;
					   }   
					}
				jQuery(this).popover('destroy')
				break;
				case 'file':
					if(input.closest('.form_field').hasClass('required') && input.parent().parent().find('.the_input_element').is(':visible')) {
						if(val.length < 1 || val=='' )
							{
							//console.log('no value');
							settings.errors++;                               
							input.parent().parent().find('.error_message').popover({trigger:'manual'});
							input.parent().parent().find('.error_message').popover('show');
							input.parent().parent().find('.error_message').parent().find('.popover').addClass(input.parent().find('.error_message').attr('data-error-class'));
							
							break;
							}
						 else
							{
							for (var i = 0; i < file_inputs.length; i++)
								{
								var fname = val;
							    var ext = fname.substr((~-fname.lastIndexOf(".") >>> 0) + 2);
								if(input.attr('name')==file_inputs[i][0])
									{
									if(jQuery.inArray(ext,file_inputs[i][1])<0)
										{
										//console.log('in array');
										settings.errors++;
										input.closest('.form_field').find('.error_message').popover('show');
										input.closest('.form_field').find('.popover').addClass(input.closest('.form_field').find('.error_message').attr('data-error-class'));
										input.closest('.form_field').find('.popover-content').text('file extention not allowed');
										if(input.closest('.form_field').find('.error_message').attr('data-secondary-message'))
											input.closest('.form_field').find('.popover-content').text(input.closest('.form_field').find('.error_message').attr('data-secondary-message'));
										}
									else
										{
										input.closest('.form_field').find('.error_message').popover('hide');
										break;
										}
									}
								}
							break;
							}
						}
				break;
				
				
				case 'radio':
					//avoid checking the same radio group more than once                                    
					var radioData = formdata.radios;
						if(input.closest('.radio-group').hasClass('required') && input.parent().find('a').is(':visible'))
							{
							if(radioData)
								{
								if(jQuery.inArray(name, radioData) >= 0) 
									break;
								else
									{
									var checked = false;
									input.closest('#the-radios').find('input[type="radio"]').each(
										function()
											{
											if(jQuery(this).prop('checked')==true)
												checked = true;	
											}
										);
									if(!checked)
										{
										settings.errors++;
										input.closest('.error_message').popover({trigger:'manual'});
										input.closest('.error_message').popover('show');
										input.closest('.error_message').parent().find('.popover').addClass(input.closest('.error_message').attr('data-error-class'));
										}
									 else
										{
										input.closest('.error_message').popover('destroy');
										break;
										}                                           
									radioData.push(name);
									} 
								}                                
							}	
					break;
				
				 case 'checkbox':
					//avoid checking the same radio group more than once                                    
					var checkData = formdata.checkboxes;
					//console.log(radioData);
					if(input.closest('.check-group').hasClass('required') && input.parent().find('a').is(':visible'))
							{
							if(checkData)
								{
								if(jQuery.inArray(name, checkData) >= 0) 
									break;
								else
									{
									var checked = false;
									input.closest('#the-radios').find('input[type="checkbox"]').each(
										function()
											{
											if(jQuery(this).prop('checked')==true)
												checked = true;	
											}
										);
									if(!checked)
										{
										settings.errors++;
										input.closest('.error_message').popover({trigger:'manual'});
										input.closest('.error_message').popover('show');
										input.closest('.error_message').parent().find('.popover').addClass(input.closest('.error_message').attr('data-error-class'));
										}
									 else
										{
										input.closest('.error_message').popover('destroy');
										break;
										}                                           
									checkData.push(name);
									} 
								}                                
							}
				break;
				}
			}
		}
	);                       
	
   jQuery(current_form).find('textarea').each( function() {	
		if(jQuery(this).hasClass('required') && jQuery(this).is(':visible'))
			{
			if(jQuery(this).val() == '') {
				settings.errors++;
				jQuery(this).popover({trigger:'manual'});
				jQuery(this).popover('show');
				jQuery(this).parent().find('.popover').addClass(jQuery(this).attr('data-error-class'));
				}
			 else
				{
				jQuery(this).popover('destroy');
				}
			}
  		 }
   	);
	
	jQuery(current_form).find('#select').each( function() {	
		if(jQuery(this).hasClass('required') && jQuery(this).parent().find('.selectpicker').is(':visible'))
			{
			if(jQuery(this).val() == 0) {
				jQuery(this).parent().find('.selectpicker').attr( 'data-content', jQuery(this).attr('data-content'));
				jQuery(this).parent().find('.selectpicker').attr( 'data-placement', jQuery(this).attr('data-placement'));
				jQuery(this).parent().find('.selectpicker').popover({trigger:'manual'});
				jQuery(this).parent().find('.selectpicker').popover('show');
				jQuery(this).parent().find('.selectpicker').find('.popover').addClass(jQuery(this).attr('data-error-class'));
				settings.errors++;
				}
			 else
				{
				jQuery(this).parent().find('.selectpicker').popover('destroy');
				}
			}
  		 }
   	);
if(settings.errors == 0) {
	return true;
	
}
else{
var offset = jQuery('div.popover').offset();
	jQuery("html, body").animate(
			{
			scrollTop:offset.top-180
			},700
		)
	return false;

}

}
function isNumber(n) {
   if(n!='')
		return !isNaN(parseFloat(n)) && isFinite(n);
	
	return true;
}

function IsValidEmail(email){
  if(email!=''){
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	return filter.test(email);
  }
	return true;
}
function allowedChars(input_value, accceptedchars){
	var aChars = ' -_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	if(accceptedchars)
		{
		switch(accceptedchars)
			{
			case 'tel': aChars = '1234567890-+() '; break;
			case 'text': aChars = ' abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
			default: aChars = accceptedchars; break;
			}
		}
	var valid = false;
	var txt = input_value.toString();
	
	for(var i=0;i<txt.length;i++) {
		if (aChars.indexOf(txt.charAt(i)) != -1) {
			valid = true;
		} else {
			valid = false;
			break;
		}
	 }
	return valid;
}
function validate_url(get_url) {
        var url = get_url;
        var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
        if (pattern.test(url))
            return true;
 
        return false;
}