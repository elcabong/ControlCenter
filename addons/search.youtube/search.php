<?php
if(isset($_GET['term'])) {
	$theterm = $_GET['term'];
}
$last = 1;
if(isset($_GET['last'])) {
	$last = $_GET['last'];
}
if(isset($_GET['mediaplayerip'])) {
	$to = $_GET['mediaplayerip'];
}
if(isset($_GET['play']) && isset($_GET['request']) && isset($_GET['to'])) {
		$to = $_GET['to'];
		$request = urlencode($_GET['request']);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$to/jsonrpc?request=$request");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		//print_r($output);
		exit;
}
?>
<div id="youtube_search"></div>
<style>
#youtube_search img.thumb { float:left; }
.preview { clear:both; }
</style>
<script>
$(document).ready(function() {
	$.getJSON('https://gdata.youtube.com/feeds/api/videos?q=<?php echo "$theterm"; ?>&max-results=10&start-index=<?php echo "$last";?>&v=2&alt=jsonc', function(data) {
		<?php $last = $last + 10; ?>
		var feeddiv = $('#youtube_search');
		for(var i=0; i<data.data.items.length; i++) {
		  var video_url = "http://youtube.com/watch?v=" + data.data.items[i].id;
		  var thumbnail_url = "http://img.youtube.com/vi/" + data.data.items[i].id + "/1.jpg";
		  feeddiv.append('<div class="preview" id="'+data.data.items[i].id+'"></div>');
		  feeddiv.children().last().append('<a href="#" class="play" to="<?php echo $to;?>" mediaid="'+data.data.items[i].id+'"><img class="thumb" src="'+thumbnail_url+'" /><div class="info"><b>' + data.data.items[i].title +  '</b><br><span>' + data.data.items[i].description + '</span></div></a><br>');
		}
	  <?php if($last > 11) { 
	  $prevlast = $last - 20; ?>
	  feeddiv.children().last().append('<Br style="clear:both;"><a href="#" class="last" last="<?php echo $prevlast; ?>">Previous</a>');
	  <?php } ?>
	  feeddiv.children().last().append('<Br style="clear:both;"><a href="#" class="next" last="<?php echo $last; ?>">Next</a>');

	  
		$("a.next").click(function () {
			var thisterm = encodeURIComponent(($("#SendTextField").val()));
			var last = $(this).attr('last');
			$("#searchresults").load('../addons/search.youtube/search.php?mediaplayerip=<?php echo $to; ?>&term=' + thisterm + '&last=' + last);
			return false;
		});	  

		$("a.last").click(function () {
			var thisterm = encodeURIComponent(($("#SendTextField").val()));
			var last = $(this).attr('last');
			$("#searchresults").load('../addons/search.youtube/search.php?mediaplayerip=<?php echo $to; ?>&term=' + thisterm + '&last=' + last);
			return false;
		});	

		$("a.play").click(function () {
			var to = $(this).attr('to');
			var mediaid = $(this).attr('mediaid');
			var _data = '{"jsonrpc":"2.0","method":"Player.Open","params":{"item":{"file":"plugin://plugin.video.youtube/?action%3Dplay_video%26videoid%3D'+ mediaid +'"}},"id":"1"}';
			$.ajax({
				url: "../addons/search.youtube/search.php?play=yes&to="+to+"&request=" + _data,
				type: 'post'
			});
			return false;
		});
	});
});
</script>