(function($) {
	
	frslib.provide('frslib.metaboxes');
	frslib.provide('frslib.metaboxes.names');
//##############################################################################
//##############################################################################
//##############################################################################
//##############################################################################
//##############################################################################
// METABOXES
//##############################################################################
//##############################################################################
//##############################################################################
//##############################################################################
//##############################################################################	
	// selectors and names
	frslib.metaboxes.names.action_publishPost = 'action_publish_post';
	frslib.metaboxes.names.postForm = '#post';
	
	frslib.metaboxes.names.normalize_options_class = '.ff-metabox-normalize-options';
	
	
	$( frslib.metaboxes.names.postForm ).submit(function(){
		frslib.callbacks.doCallback( frslib.metaboxes.names.action_publishPost );
		
		//return false;
	});
	
	
	
	var $normalizeMetaboxes = $( frslib.metaboxes.names.normalize_options_class );
	

	if( $normalizeMetaboxes.length > 0 ) {
		
		frslib.callbacks.addCallback( frslib.metaboxes.names.action_publishPost, function(){
		
		$normalizeMetaboxes.each(function(i, o){
			
			var $normalizedContent = frslib.options.template.functions.normalize( $(o) );
			
			$(this).find('*').attr('name', '');
			
			$normalizedContent.css('display','none');
			
			$(this).after( $normalizedContent );
		});
		
		});
		/*frslib.callbacks.addCallback( frslib.metaboxes.names.action_publishPost, function(){
			
			var $normalizedContent = frslib.options.template.functions.normalize( $customCodeLogic.find('.ff-metabox-content') );
				
			$customCodeLogic.find('.ff-metabox-content').find('*').attr('name', '');
			
			$normalizedContent.css('display','none');
			
			$customCodeLogic.find('.ff-metabox-content').after( $normalizedContent );
		});*/
	}
	
/*	frslib.metaboxes.names.metabox_customCodeLogic = '#CustomCodeLogic';
	
	// callback for submiting the post editor screen
	$( frslib.metaboxes.names.postForm ).submit(function(){
		frslib.callbacks.doCallback( frslib.metaboxes.names.action_publishPost );
		
		//return false;
	});
	
	
	
	var $customCodeLogic = $( frslib.metaboxes.names.metabox_customCodeLogic );
	
	if( $customCodeLogic.length > 0 ) {
		frslib.callbacks.addCallback( frslib.metaboxes.names.action_publishPost, function(){
			
			var $normalizedContent = frslib.options.template.functions.normalize( $customCodeLogic.find('.ff-metabox-content') );
				
			$customCodeLogic.find('.ff-metabox-content').find('*').attr('name', '');
			
			$normalizedContent.css('display','none');
			
			$customCodeLogic.find('.ff-metabox-content').after( $normalizedContent );
		});
	}
	
	// normalizing conditional logic metaboxes
	//frslib.callbacks.addCallback();
	*/
})(jQuery);