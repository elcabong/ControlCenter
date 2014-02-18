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
	if($(".sendcontrols")[0]){ if($(".sendcontrols").css('display') != 'none') {var maxitemw = 200; } } // if send controls are available, set minimum width to send control width
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