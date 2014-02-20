<?php
if(isset($_GET['addon'])) { 
	$addontype = $_GET['addon'];
	require_once "addons.php";
	}
if($addontype == 'links') {
	$allenabledaddons = explode(",", $enabledaddons);
	foreach($allenabledaddons as $thisaddon1) {
		if($thisaddon1 == '') { break; }
		$thisaddon = explode(".", $thisaddon1, 2);
		$classification = $thisaddon[0];
		$title = $thisaddon[1];
		$filename = $addonarray["$classification"]["$title"]['path']."addonquicklinks.php";
		if (file_exists($filename)) {
			include $filename;
		}	
	} ?>
<script>
		$("#addonlinks li:first-child a:first-child").removeClass('unloaded').addClass('selected');

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

	$("a.panel.persistent").click(function (e) {
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
	</script>	
<?php
	
} elseif($addontype == 'pages') {
	$allenabledaddons = explode(",", $enabledaddons);
	foreach($allenabledaddons as $theaddon) {
		$allenabledaddons = explode(".", $theaddon, 2);
		$classification = $allenabledaddons[0];
		$title = $allenabledaddons[1];
		$filename = $addonarray["$classification"]["$title"]['path']."addonquicklinks.php";
		if (file_exists($filename)) {
			include $filename;
		}			
	}
}
?>