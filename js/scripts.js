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
		$('#wrapper').scrollTo(iframe, 800);
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
		
		$('#wrapper').scrollTo($(this).attr('href'), 800);		

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
		
		$('#wrapper').scrollTo($(this).attr('href'), 800);		
		return false;
	});	
	
	
	function refreshRooms() {
	//clearTimout(rrtimer);
	//rrtimer = setTimeout(refreshRooms, 5000);
	//if($('#roomList').css('display') == 'block') {
		$("#roomList").load("./getrooms.php");
	/*	var protocol = window.location.protocol;
		var path = document.location.href;
		var dir = path.substring(path.indexOf('/', 1)+2, path.lastIndexOf('/'));
		$("#roomList").load("http://"+dir+"/getrooms.php >*", function() { if(document.getElementById('loading').style.display != 'none') { document.getElementById('loading').style.display='none'; } });*/
	//}
	setTimeout(refreshRooms, 2500);
	}

	
refreshRooms();



//$("#room-menu").load("./room-chooser.php", function() { document.getElementById('loading').style.display='none'; });
								setTimeout(func, 5000);
								function func() {
									document.getElementById('loading').style.display='none';	
								}
});	

	function changeroom(newroom) {
		document.getElementById('loading').style.display='block';
		$("#room-menu").load("./room-chooser.php?newroom="+newroom, function() {  } );
	}