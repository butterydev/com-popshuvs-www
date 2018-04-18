<?php

	if(isset($_GET['videoID'])) {

		$videoID = $_GET['videoID'];

	} else {

		$videoID = 'xGvSdTt1d68';

	}

	function isMobile() {

		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);

	}

?>
<!DOCTYPE html>
<html>
<head>
<?php include_once('inc/head.php'); ?>
<link href="css/watch.css" rel="stylesheet" type="text/css">
</head>
<body class="<?php echo (isMobile()) ? 'is-mobile' : 'is-desktop'; ?>">
<div class="watch-screen fillScreen">
	<a class="watch-screen__back-button" href="javascript:;" title="Back to the Mash">Back</a>
	<div class="watch-screen__video-wrapper">
		<!-- THE BERRICS -->
		<!-- <iframe id="watch-screen__video" class="fillScreen" allowfullscreen frameborder="0" src="//theberrics.com/video/post/embed/id/8325/"></iframe> -->
		<!-- TransWORLD SKATEBOARDING -->
		<iframe id="watch-screen__video" class="fillScreen" allowfullscreen frameborder="0" src="//video.grindnetworks.com/embed/L-so7uCow"></iframe>
	</div>
</div>
<?php include_once('inc/foot.php'); ?>
<!-- <script>
;(function(window, document, undefined) {
var tag = document.createElement('script');
tag.src = 'https://www.youtube.com/iframe_api';
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
})(window, window.document);
</script> -->
<script src="js/watch.js"></script>
<script>
;(function(window, document, undefined) {

	window.onload = playVideo;

	function playVideo() {

		var $_iframeVideo = document.querySelector('#watch-screen__video');

		console.log($_iframeVideo);

		$_iframeVideo.addEventListener('click', function() {

			console.log('$_iframeVideo clicked');

		});

		$_iframeVideo.dispatchEvent(new MouseEvent('click'));

	}

})(window, document);
</script>
</body>
</html>
