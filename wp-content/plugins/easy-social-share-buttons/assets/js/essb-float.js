jQuery(document).ready(function($){

	function exist_element(oID) {
		   return jQuery(oID).length > 0;
		}
		 
		if (!exist_element("#essb_displayed_float")) { return; }

  var top = $('#essb_displayed_float').offset().top - parseFloat($('#essb_displayed_float').css('marginTop').replace(/auto/, 0));
  $(window).scroll(function (event) {
    // what the y position of the scroll is
    var y = $(this).scrollTop();

    // whether that's below the form
    if (y >= top) {
      // if so, ad the fixed class
      $('#essb_displayed_float').addClass('essb_fixed');
      
    } else {
      // otherwise remove it
      $('#essb_displayed_float').removeClass('essb_fixed');
    }
  });


});
