/*
	Any site-specific scripts you might have.
	Note that <html> innately gets a class of "no-js".
	This is to allow you to react to non-JS users.
	Recommend removing that and adding "js" as one of the first things your script does.
	Note that if you are using Modernizr, it already does this for you. :-)
*/
$(document).ready(function() {
	
	if ($('html').hasClass('touch')){
		$('title').html('The Beat!');
	}
	
	
	$(window).height(); // New height
	var width = $(window).width(); // New width


	if(width>481){
		$('#logo-img').attr('src', '/wp-content/themes/_design/_img/template/beat-logo.png');
		$('body').removeClass('mobile480');
	}
	
	if(width<=480){
			$('body').addClass('mobile480');
			$('#logo-img').attr('src', '/wp-content/themes/_design/_img/template/beat-logo-mobile.png');
		}
	
	if(width>768){
		$('#side-more').sticky({
		type: 'auto',
		  track:              'y',
		  constrainTo:        'parent',
		  bufferTop:          200,
		  bufferBottom:       200,
		});
	}

	$(window).resize(function() {
	  // This will execute whenever the window is resized

	  $(window).height(); // New height
	  var width = $(window).width(); // New width


		if(width>481){
			$('#logo-img').attr('src', '/wp-content/themes/_design/_img/template/beat-logo.png');
		}
	
	  if(width<=480){
			$('#logo-img').attr('src', '/wp-content/themes/_design/_img/template/beat-logo-mobile.png');
		}
	});


	// fix the sticky.js width bug (doesnt work on resize yet)
	var stickywidth = $('.sticky-wrapper').css('width');
	$('#side-more').css('width', stickywidth);

	$('#menu-trigger').toggle(
		function(){
			$('.menu-header').show();
		},
	
		function(){
			$('.menu-header').hide();
		}
	);

});

