
function showOps( x ) {
	if ( jQuery( x ).val() == "custom" ) {
		jQuery( x ).parent().next( '.hidden_options').show();
	} else {
		jQuery( x ).parent().next( '.hidden_options').hide();
	}
}

function showLinktitle( x ) {
	if ( jQuery( x ).attr('checked') != undefined ) {
		jQuery( x ).parent().next( '.hidden_options').show();
	} else {
		jQuery( x ).parent().next( '.hidden_options').hide();
	}
}

function showFeaturedImageOps( x ) {
	if ( jQuery( x ).attr('checked') != undefined ) {
		jQuery( x ).parent().next( '.hidden_options').show();
		jQuery( x ).parent().next().next( '.hidden_options').show();
	} else {
		jQuery( x ).parent().next( '.hidden_options').hide();
		jQuery( x ).parent().next().next( '.hidden_options').hide();
		jQuery( x ).parent().next().next().next( '.hidden_options').hide();
	}
}