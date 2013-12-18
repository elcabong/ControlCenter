$(document).ready(function() {

	$(function(){
		document.oncontextmenu = function() {return false;};
	});

	if(window.location.hash) {
		$('a.panel').removeClass('selected');
		var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
		var thechild = $("a.panel[title="+hash+"]");
		thechild.addClass('selected');
		thechild.removeClass('unloaded');
		var iframe = document.getElementById(hash+'f');
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

	var timeoutId = 0;
	var mouseIsHeld = false;

	$('a.panel.persistent').on('mousedown touchstart', function(e) {
		mouseIsHeld = false;
		if(!$(this).hasClass('unloaded')) { var thislink = $(this); var ee = e;
			clearTimeout(timeoutId);
			timeoutId = setTimeout(function(){
				if (thislink.hasClass('selected')) {
					mouseIsHeld = true;
					var href = thislink.attr('href');
					href = href.replace(/#/g, "" );
					var iframe = document.getElementById(href + 'f');
					iframe.src = '';
					iframe.getAttribute("src");
					iframe.removeAttribute("src");
					thislink.addClass('unloaded');
					return false;
				}
			},1300);
		}
	}).bind('mouseup mouseleave touchend', function() {
		clearTimeout(timeoutId);
	});

	$('a.panel.nonpersistent').on('mousedown touchstart', function(e) {
		mouseIsHeld = false;
		if(!$(this).hasClass('unloaded')) { var thislink = $(this); var ee = e;
			clearTimeout(timeoutId);
			timeoutId = setTimeout(function(){
				if (thislink.hasClass('selected')) {
					mouseIsHeld = true;
					var iframe = document.getElementById('nonpersistentf');
					iframe.src = '';			
					iframe.getAttribute("src");
					iframe.removeAttribute("src");
					thislink.addClass('unloaded');
					return false;
				}
			},1300);
		}
	}).bind('mouseup mouseleave touchend', function() {
		clearTimeout(timeoutId);
	});
	
	$('a.panel.persistent').click(function (e) {
	e.preventDefault();
	if(mouseIsHeld == false) {

        var href = $(this).attr('href');
		if(href == '#') { return false; }		
				
		href = href.replace(/#/g, "" );
		var iframe = document.getElementById(href + 'f');
		if (!iframe.src) {
			$('iframe.' + href).attr('src',$('iframe.' + href).attr('data-src'));
		}

		if ($(this).hasClass('selected')) {
            var href = $(this).attr('href');
		    href = href.replace(/#/g, "" );
			var iframe = document.getElementById(href + 'f');
			iframe.src = iframe.src;
		        return false; 
        } else {
			$('a.panel').removeClass('selected');
			$(this).removeClass('unloaded');
			$(this).addClass('selected');
		}
		
		$('#wrapper').scrollTo($(this).attr('href'), 0);

		var myLi = $(this).parent();
		if(myLi.hasClass("sortable")) {
			$("li.sortable").each(function () {
				$(this).addClass('secondary');
			});
			myLi.removeClass('secondary');		
			myLi.removeClass('hidden');
			myLi.parent().prepend(myLi);
		}

		var iframeclear = document.getElementById('nonpersistentf');
		iframeclear.src = '';
		$('a.panel.nonpersistent').addClass('unloaded');
		
		return false;
	}
	});

	$('a.panel.nonpersistent').click(function (e) {
	e.preventDefault();
	if(mouseIsHeld == false) {
	
		var nonpersisthref = $(this).attr('href');
		var nonpersistiframe = document.getElementById('nonpersistentf');
 
		if ($(this).hasClass('selected')) {
			nonpersistiframe.src = '';
			nonpersistiframe.src = $(this).attr('href');
			nonpersistiframe.src = nonpersistiframe.src;
	        return false; 
        } else {
			$('a.panel').removeClass('selected');
			$(this).removeClass('unloaded');
			$(this).addClass('selected');
		}

		$('#wrapper').scrollTo('#nonpersistentf', 0);

		var myLi = $(this).parent();
		if(myLi.hasClass("sortable")) {
			$("li.sortable").each(function () {
				$(this).addClass('secondary');
			});
			myLi.removeClass('secondary');		
			myLi.removeClass('hidden');
			myLi.parent().prepend(myLi);
		}
		nonpersistiframe.src = $(this).attr('href');
		nonpersistiframe.src = nonpersistiframe.src;	

		return false;
	}	
	});
	
	$('a.panel2nd').click(function () {

        var href = $(this).attr('href');
		if(href == '#') { return false; }		

		href = href.replace(/#/g, "" );
		var iframe = document.getElementById(href + 'f');
		if (!iframe.src) {
			$('iframe.' + href).attr('src',$('iframe.' + href).attr('data-src'));
		}		
		
		$('a.panel').removeClass('selected');
		//$('.navsettings.panel').addClass('selected');
		
		$('#wrapper').scrollTo($(this).attr('href'), 0);		
		return false;
	});	
	
	$(".clearcover").bind('mousedown touchstart', function() {
	hideclearcoverandmenus();
	});

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

	var coveron = 0;
	$("nav").children().hover(function(){
		clearInterval(showclearcovertimer);
		coveron = 1;
		var showclearcovertimer=setInterval(function(){showclearcover()},800);
    }, function(){
		coveron = 0;
		clearInterval(showclearcovertimer);
		clearInterval(hideclearcovertimer);
		var hideclearcovertimer=setInterval(function(){hideclearcover()},300);
    });

	function showclearcover() {
		if(coveron==1) {
			$(".clearcover").fadeIn(300);
			coveron = 0;
		}
		//clearInterval(showclearcovertimer);
	}

	function hideclearcover() {
		hideclearcoverandmenus();
		clearInterval(hideclearcovertimer);
	}

	$(".clearcover").hover(
         function () {
           hideclearcoverandmenus();
         }
     );	

	function hideclearcoverandmenus() {
		$("ul.children").fadeOut(300);
		$(".clearcover").fadeOut(300);
		$('.sortable.secondary').addClass('hidden');
		$('.clearcover').simulate('click');
	}	
});

var resizeTimer;
$(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(reSizeWindow, 120);
});

function reSizeWindow() {
    theselectedpanel = $('a.panel.selected').attr('href');
	$('#wrapper').scrollTo(theselectedpanel, 0);
	reSizeRoomInfo();
};

function reSizeRoomInfo() {
	var rw = $('#room-menu > ul').width();
	var rwx = rw+"px";
	$('#roomList').css("right",rwx);
	var w = window.innerWidth;
	var w4 = w*.35;
	
	var maxitemw = 0;
	 var newelements = document.getElementsByClassName('scrolling');
	 for(var i=0; i < newelements.length; i++) {
		 var thiscrollingelement = newelements[i];			
		max = thiscrollingelement.scrollWidth;
		if(max > maxitemw) { var maxitemw = max; }
		};
	if(maxitemw == 0) {
	$('li.roominfo').css("width",0);
	$('#roomList > li > span > .roominfo-modal').css("width",0);
	} else if(w < (maxitemw + rw) || maxitemw == '') {
	var npw = w - rw;
	var npwx = npw+"px";
	var npmw = npw - 40;
	var npmwx = npmw+"px";
	$('li.roominfo').css("width",npwx);
	$('#roomList > li > span > .roominfo-modal').css("width",npmwx);
	} else {
	var npmw = maxitemw;
	var npmwx = npmw+"px";
	var npw = npmw + 40;
	var npwx = npw+"px";
	$('li.roominfo').css("width",npwx);
	$('#roomList > li > span > .roominfo-modal').css("width",npmwx);	
	}
};