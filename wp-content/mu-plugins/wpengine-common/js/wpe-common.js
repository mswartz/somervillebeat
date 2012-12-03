var url = window.location.pathname
var filename = url.substring(url.lastIndexOf('/')+1);
var warning = "Before taking this action, we at WP Engine recommend that you create a Restore Point of your site. This will allow you undo this action within minutes.";

jQuery(document).ready(function($) {    	    			
//apprise implementation

  if(filename == 'update-core.php' && $('form.upgrade').length > 0 && wpe.popup_disabled != 1 ) {
  	$('form[name="upgrade"] input[type=submit]').click(function(e) { e.preventDefault(); });
  	$('form[name="upgrade"] input[type=submit]').attr('onclick','wpe_validate_upgrade("upgrade");');

  	$('form[name="upgrade-plugins"] input[type=submit]').click(function(e) { e.preventDefault(); });
  	$('form[name="upgrade-plugins"] input[type=submit]').attr('onclick','wpe_validate_upgrade("upgrade-plugins");');  	
  	
  	$('form[name="upgrade-themes"] input[type=submit]').click(function(e) { e.preventDefault(); });
  	$('form[name="upgrade-themes"] input[type=submit]').attr('onclick','wpe_validate_upgrade("upgrade-themes");');  	
  } else if(filename == 'plugins.php' && wpe.popup_disabled !=  1) {
  	
  	if($('.update-message').length > 0 ) {
	  	$('.update-message a').each(function(i,obj) {
	  		var txt = $(obj).text();
	  		if(txt == 'update now') {
	  			$(this).click(function(e) { e.preventDefault(); });
	  			$(this).attr('onclick','wpe_upgrade_link("'+$(obj).attr('href')+'");');
	  		}
	  	});	
	  }
	  
	$('#doaction').click(function(e) { e.preventDefault(); });
	$('#doaction').attr('onclick','wpe_validate_bulk_form();');
		
  } else if( filename == 'plugin-install.php' && wpe.popup_disabled != 1) {

	$('a.install-now').each(function() { 
			$(this).click(function(e) { e.preventDefault(); });
			$(this).attr('onclick','wpe_upgrade_link("'+$(this).attr('href')+'");');
	});
	
	$('input[type="submit"]').live('click',function(e) {
		if( $(this).attr('name') != 'plugin-search-input' ) { 
			e.preventDefault();
			$(this).parent().attr('id','form-to-submit'); 
			$('input[type="submit"]').attr('onclick','wpe_validate_install()');  	
		}	
	});
  }

});

/**
	* Accepts the form identifier and applies the popup confirmation
	*
	*/
function wpe_validate_upgrade(form) {
	apprise(warning, {'confirm':true,'textCancel': "Yes, take me to my WPEngine Dashboard.",'textOk':'No thanks, I already did this.' }, function(r) { 
		if(r != false) {
			append = '<input type="hidden" name="upgrade" value="1" />';
			jQuery('form[name="'+form+'"]').append(append);
			jQuery('form[name="'+form+'"]').submit(); 
		} else {
			window.location.href = 'https://my.wpengine.com/customers/'+wpe.account+'/checkpoints/new';
		} 
  });
}

function wpe_validate_install() {
	apprise(warning, {'confirm':true,'textCancel': "Yes, take me to my WPEngine Dashboard.",'textOk':'No thanks, I already did this.' }, function(r) { 
		if(r != false) {
			append = '<input type="hidden" value="Install Now" />';
			jQuery('form').append(append);
			jQuery('#form-to-submit').submit();
		} else {
			window.location.href = 'https://my.wpengine.com/customers/'+wpe.account+'/checkpoints/new';
		} 
  });
}


function wpe_validate_bulk_form() {
	if(jQuery('select[name="action"] option:selected').val() == 'update-selected') {
		apprise(warning, {'confirm':true,'textCancel': "Yes, take me to my WPEngine Dashboard.",'textOk':'No thanks, I already did this.' }, function(r) { 
			if(r != false) {
				jQuery('#doaction').parent().parent().parent().submit();
			} else {
				window.location.href = 'https://my.wpengine.com/customers/'+wpe.account+'/checkpoints/new';
			}
		});
	} else {
		jQuery('#doaction').parent().parent().parent().submit();
	}
}

function wpe_upgrade_link(link) {
	apprise(warning, {'confirm':true,'textCancel': "Yes, take me to my WPEngine Dashboard.",'textOk':'No thanks, I already did this.' }, function(r) { 
		if(r != false) {
			window.location.href = link;
		} else {
			window.location.href = 'https://my.wpengine.com/customers/'+wpe.account+'/checkpoints/new';
		} 
  });
}           
  
/**
	* Displays popup
	* http://thrivingkings.com/apprise/
	*/
          
function apprise(string, args, callback) {
	var $ = jQuery.noConflict();
	var default_args =
		{
		'confirm'		:	false, 		// Ok and Cancel buttons
		'verify'		:	false,		// Yes and No buttons
		'input'			:	false, 		// Text input (can be true or string for default text)
		'animate'		:	false,		// Groovy animation (can true or number, default is 400)
		'textOk'		:	'Ok',		// Ok button default text
		'textCancel'	:	'Cancel',	// Cancel button default text
		'textYes'		:	'Yes',		// Yes button default text
		'textNo'		:	'No'		// No button default text
		}
	
	if(args) 
		{
		for(var index in default_args) 
			{ if(typeof args[index] == "undefined") args[index] = default_args[index]; } 
		}

	var aHeight = $(document).height();
	var aWidth = $(document).width();
	$('body').append('<div class="appriseOverlay" id="aOverlay"></div>');
	$('.appriseOverlay').css('height', aHeight).css('width', aWidth).fadeIn(100);
	$('body').append('<div class="appriseOuter"></div>');
	$('.appriseOuter').append('<div class="appriseInner"></div>');
	$('.appriseInner').append(string);
    $('.appriseOuter').css("left", ( $(window).width() - $('.appriseOuter').width() ) / 2+$(window).scrollLeft() + "px");
    
    if(args)
		{
		if(args['animate'])
			{ 
			var aniSpeed = args['animate'];
			if(isNaN(aniSpeed)) { aniSpeed = 400; }
			$('.appriseOuter').css('top', '-200px').show().animate({top:"100px"}, aniSpeed);
			}
		else
			{ $('.appriseOuter').css('top', '100px').fadeIn(200); }
		}
	else
		{ $('.appriseOuter').css('top', '100px').fadeIn(200); }
    
    if(args)
    	{
    	if(args['input'])
    		{
    		if(typeof(args['input'])=='string')
    			{
    			$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" value="'+args['input']+'" /></div>');
    			}
    		else
    			{
				$('.appriseInner').append('<div class="aInput"><input type="text" class="aTextbox" t="aTextbox" /></div>');
				}
			$('.aTextbox').focus();
    		}
    	}
    
    $('.appriseInner').append('<div class="aButtons"></div>');
    if(args)
    	{
		if(args['confirm'] || args['input'])
			{ 
			$('.aButtons').append('<button value="ok">'+args['textOk']+'</button>');
			$('.aButtons').append('<button value="cancel">'+args['textCancel']+'</button>'); 
			}
		else if(args['verify'])
			{
			$('.aButtons').append('<button value="ok">'+args['textYes']+'</button>');
			$('.aButtons').append('<button value="cancel">'+args['textNo']+'</button>');
			}
		else
			{ $('.aButtons').append('<button value="ok">'+args['textOk']+'</button>'); }
		}
    else
    	{ $('.aButtons').append('<button value="ok">Ok</button>'); }
	
	$(document).keydown(function(e) 
		{
		if($('.appriseOverlay').is(':visible'))
			{
			if(e.keyCode == 13) 
				{ $('.aButtons > button[value="ok"]').click(); }
			if(e.keyCode == 27) 
				{ $('.aButtons > button[value="cancel"]').click(); }
			}
		});
	
	var aText = $('.aTextbox').val();
	if(!aText) { aText = false; }
	$('.aTextbox').keyup(function()
    	{ aText = $(this).val(); });
   
    $('.aButtons > button').click(function()
    	{
    	$('.appriseOverlay').remove();
		$('.appriseOuter').remove();
    	if(callback)
    		{
			var wButton = $(this).attr("value");
			if(wButton=='ok')
				{ 
				if(args)
					{
					if(args['input'])
						{ callback(aText); }
					else
						{ callback(true); }
					}
				else
					{ callback(true); }
				}
			else if(wButton=='cancel')
				{ callback(false); }
			}
		});
}//end apprise
