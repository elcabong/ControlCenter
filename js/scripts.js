$(document).ready(function() {

	if(window.location.hash) {
		$('a.panel').removeClass('selected');
		var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
		var thechild = $("a.panel[title="+hash+"]");
		thechild.addClass('selected');
		thechild.removeClass('unloaded');
		var iframe = document.getElementById(hash+' 1');
		if (!iframe.src) {
			$('iframe.' + hash).attr('src',$('iframe.' + hash).attr('data-src'));
		}
		iframe.src = iframe.src;
		$('#wrapper').scrollTo(iframe, 0);
		var myLi = thechild.parent();
		if(myLi.hasClass("sortable")) {
			$("li.sortable").each(function () {
				$(this).addClass('secondary');
				$(this).addClass('hidden');
			});
			myLi.removeClass('secondary');		
			myLi.removeClass('hidden');
		}
	} else {
      // No hash found
	$('#wrapper').scrollTo(0,0);
	}

			$("li.sortable").hover(
				function () {
					$('.sortable.secondary').removeClass('hidden');	
				}, function () {

				}
			);
		
			$("#nav-menu").hover(
				function () {

				}, function () {
					$('.sortable.secondary').addClass('hidden');
				}
			);
	
	$("#nav-show").click(function() {
			$("#header").toggleClass('nav-menu-z');
	});

	$('a.panel').click(function () {

        var href = $(this).attr('href');
		if(href == '#') { return false; }		
				
		href = href.replace(/#/g, "" );
		var iframe = document.getElementById(href + ' 1');
		if (!iframe.src) {
			$('iframe.' + href).attr('src',$('iframe.' + href).attr('data-src'));
		}

		if ($(this).hasClass('selected')) {
                	var href = $(this).attr('href');
		        href = href.replace(/#/g, "" );
			var iframe = document.getElementById(href + ' 1');
			iframe.src = iframe.src;
		        return false; 
                } else {
				$('a.panel').removeClass('selected');
				$(this).removeClass('unloaded');
				$(this).addClass('selected'); }
		
		$('#wrapper').scrollTo($(this).attr('href'), 0);
		currentview = $(this);

		var myLi = $(this).parent();
		if(myLi.hasClass("sortable")) {
			$("li.sortable").each(function () {
				$(this).addClass('secondary');
			});
			myLi.removeClass('secondary');		
			myLi.removeClass('hidden');
		}		
		return false;
	});

	$('a.panel2nd').click(function () {

        var href = $(this).attr('href');
		if(href == '#') { return false; }		

		$('a.panel').removeClass('selected');
		$('.navsettings.panel').addClass('selected');
		
		$('#wrapper').scrollTo($(this).attr('href'), 0);		
		return false;
	});	
	
	
	function refreshRooms() {
	$("#roomList").load("./getrooms.php");
	setTimeout(refreshRooms, 3500);
	}
	setTimeout(refreshRooms, 1000);

	setTimeout(func, 5000);
	function func() {
		document.getElementById('loading').style.display='none';	
	}
	
	$(".clearcover").click(function () {
		$("ul.children").fadeOut(300); //Close filters drop-downs if user taps ANYWHERE in the page
		$(".clearcover").fadeOut(300);
	});	

$("nav").children() // select your element (supports CSS selectors)
    .hover(function(){ // trigger the mouseover event
        $(".clearcover") // select the element to show (can be anywhere)
            .show(); // show the element
    }, function(){ // trigger the mouseout event
        $(".clearcover") // select the same element
            .hide(); // hide it
    });
	
});	

var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(reSizeWindow, 120);
});

function reSizeWindow() {
    theselectedpanel = $('a.panel.selected').attr('href');
	$('#wrapper').scrollTo(theselectedpanel, 0);
};