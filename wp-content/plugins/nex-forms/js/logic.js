function populate_current_form_fields(){
	
	/*var fields = '';
	setTimeout(function()
		{
		jQuery('div.nex-forms-container div.form_field label span.the_label').each(
			function()
				{
				fields += '<option value="'+  jQuery(this).closest('.form_field').attr('id') +'">'+ jQuery(this).text() +'</option>';
				}
			);
		jQuery('select[name="current_form_fields"]').html(fields);
		},200);*/
	
}
function update_logic(current_field, current_condition, condition, value, action, target, target_name){
				var con_output = '';
				jQuery('.nex-forms-container #con_'+current_condition).remove();
				con_output = '<div class="customcon field_'+ current_field +'" id="con_'+current_condition+'" data-condition="'+condition+'" data-value="'+value+'" data-action="'+action+'" data-target="'+target+'" data-target-name="'+ target_name +'"></div>';
				
				jQuery('.nex-forms-container').append(con_output);
			}
function set_perameters(obj){
		var con_condition		= (obj.find('.setcondition').text()) 						? obj.find('.setcondition').text() 							: '';			
		
		var con_value 			= (obj.find('input[name="set_conditional_value"]').val()) 	? obj.find('input[name="set_conditional_value"]').val() 	: '';
		if(!con_value)
			con_value 			= (obj.find('select[name="set_conditional_value"]').val()) 	? obj.find('select[name="set_conditional_value"]').val() 	: '';

		var con_action 			= (obj.find('select[name="con_action"]').val()) 			? obj.find('select[name="con_action"]').val() 				: '';
		var con_target 			= (obj.find('.targetname').attr('data-target-id')) 			? obj.find('.targetname').attr('data-target-id') 			: '';
		var con_target_name 	= (obj.find('.targetname').text()) 							? obj.find('.targetname').text() 							: '';
		current_id = jQuery('div#nex-forms-field-settings .current_id').text();
		update_logic(current_id, obj.attr('id'), con_condition, con_value, con_action,con_target, con_target_name);	
}
(function($){
	
	$(window).load(function() {
		
		var current_id = '';
		
		$('.remove_condition').live('click',
			function()
				{
				$('.nex-forms-container #con_'+$(this).closest('.field_condition').attr('id')).remove();
				$(this).closest('.field_condition').remove();
				}
			);
		
		$('button.target_field').live('click',
			function()
				{
				$('.field_condition').removeClass('current');	
				$(this).closest('.field_condition').addClass('current'); 
				if($(this).hasClass('targeting'))
					{
					$(this).removeClass('targeting');
					$(this).html('<span class="fa fa-bullseye">&nbsp;</span> ' + (($(this).closest('.field_condition').find('.targetname').text()) ? 'Change' : 'Select') + ' Field');
					$('div.nex-forms-container').removeClass('selecting_conditional_target');
					$('div.nex-forms-field-settings').removeClass('selecting_conditional_target');
					}
				else
					{
					$(this).addClass('targeting');
					$(this).html('Cancel');
					$('div.nex-forms-container').addClass('selecting_conditional_target');
					$('div.nex-forms-field-settings').addClass('selecting_conditional_target');
					}
				
				
				}
			);
		
			$('ul.set_conditional_action li').live('click',
				function()
					{
					$(this).closest('.field_condition').find('.setcondition').text($(this).find('a').text());
					set_perameters($(this).closest('.field_condition'));
					}
				);
			$('select[name="set_conditional_value"],input[name="set_conditional_value"]').live('change',
				function()
					{
					set_perameters($(this).closest('.field_condition'));
					}
				);
			$('select[name="con_action"]').live('change',
				function()
					{
					$(this).closest('.field_condition').find('.setaction').text($(this).val());
					set_perameters($(this).closest('.field_condition'));
					}
				);

			$('div.nex-forms-container.selecting_conditional_target .form_field.text,			div.nex-forms-container.selecting_conditional_target .form_field.textarea,			div.nex-forms-container.selecting_conditional_target .form_field.select,			div.nex-forms-container.selecting_conditional_target .form_field.mulit-select,			div.nex-forms-container.selecting_conditional_target .form_field.radio-group,			div.nex-forms-container.selecting_conditional_target .form_field.check-group,			div.nex-forms-container.selecting_conditional_target .form_field.submit-button,			div.nex-forms-container.selecting_conditional_target .form_field.star-rating,			div.nex-forms-container.selecting_conditional_target .form_field.slider,			div.nex-forms-container.selecting_conditional_target .form_field.touch_spinner,			div.nex-forms-container.selecting_conditional_target .form_field.tags ,			div.nex-forms-container.selecting_conditional_target .form_field.autocomplete,			div.nex-forms-container.selecting_conditional_target .form_field.color_pallet,			div.nex-forms-container.selecting_conditional_target .form_field.datetime,			div.nex-forms-container.selecting_conditional_target .form_field.date,			div.nex-forms-container.selecting_conditional_target .form_field.time,			div.nex-forms-container.selecting_conditional_target .form_field.upload-single,			div.nex-forms-container.selecting_conditional_target .form_field.upload-image,			div.nex-forms-container.selecting_conditional_target .form_field.custom-prefix,			div.nex-forms-container.selecting_conditional_target .form_field.custom-postfix,			div.nex-forms-container.selecting_conditional_target .form_field.custom-pre-postfix,			div.nex-forms-container.selecting_conditional_target .form_field.paragraph,			div.nex-forms-container.selecting_conditional_target .form_field.divider,			div.nex-forms-container.selecting_conditional_target .form_field.heading').live('click',
				function()
					{
					if(!jQuery(this).hasClass('grid'))
						{
						$('.field_condition.current').find('.targetname').text(($(this).find('.the_label').text()) ? $(this).find('.the_label').text() : $(this).attr('id'))
						$('.field_condition.current').find('.targetname').attr('data-target-id',$(this).attr('id'))
						$('div.nex-forms-container').removeClass('selecting_conditional_target');
						$('div.nex-forms-field-settings').removeClass('selecting_conditional_target');
						$('.field_condition.current').find('button.target_field').html('<span class="fa fa-bullseye">&nbsp;</span> Change Field').removeClass('targeting');
						
						set_perameters(jQuery('.field_condition.current'));
						}
					}
				);
			
			$('em.targetname').live('mouseover',
				function()
					{
					var form_field = $('.nex-forms-container #'+$(this).attr('data-target-id'));
					var offset = form_field.offset();
					jQuery('.nex-forms-container').animate(
						{
							scrollTop:offset.top-(jQuery('.nex-forms-container').outerHeight()/2)
						},500
					)
					form_field.addClass('identify');
					}
				);
			$('em.targetname').live('mouseout',
				function()
					{
					$('.nex-forms-container .form_field').removeClass('identify');
					}
				);
						
			$('button.add_condition').live('click',
				function()
					{
					current_id = $('div#nex-forms-field-settings .current_id').text();
					var new_condition = $(this).parent().find('.field_condition_template').clone();
						new_condition.removeClass('hidden').removeClass('field_condition_template').addClass('field_condition');
					var set_Id = '_' + Math.round(Math.random()*99999);
					new_condition.attr('id',set_Id);
					$(this).parent().append(new_condition);	
					update_logic(current_id, set_Id,'','','','');
					}
				);
			}
		);
	
})(jQuery);