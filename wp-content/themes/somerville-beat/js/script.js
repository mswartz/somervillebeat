/*
	Any site-specific scripts you might have.
	Note that <html> innately gets a class of "no-js".
	This is to allow you to react to non-JS users.
	Recommend removing that and adding "js" as one of the first things your script does.
	Note that if you are using Modernizr, it already does this for you. :-)
*/

jQuery(document).ready(function(){
	jQuery('html').removeClass('no-js');
	jQuery('html').addClass('js');

	jQuery('#menu-toggle').on('click', '#menu-trigger', function(){
		jQuery('#access').toggleClass('menu-active');
		jQuery(this).find('.menu-triangle').toggleClass('trigger-active');
		console.log('toggle');
	});

	function initDropDowns(){
		// Set dropdowns on click
		jQuery(document.body).delegate(".dropdown-trigger", "click", function() {
			var t = jQuery(this).closest(".dropdown");
			t.toggleClass("dropdown-active");
		});
		
		// Set dropdowns on hover
		jQuery(document.body).delegate(".dropdown-trigger-hover", "mouseenter mouseleave", function() {
			var t = jQuery(this).closest(".dropdown");
			t.toggleClass("dropdown-active");
		});
	}

	initDropDowns();
});

