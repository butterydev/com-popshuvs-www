<?php

session_start();

$_SESSION['entries'] = array();

function isMobile() {

	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);

}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Today In Skateboarding</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
<meta content="Keep up with today's gnar! The latest skate videos in one place &mdash; The Berrics, Thrasher Magazine, TransWorld SKATEboarding and RIDE Channel." name="description">
<link href="favicon.png" rel="icon" type="image/x-icon">
<link href="style.css" rel="stylesheet" type="text/css">
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-76463802-1', 'auto');
ga('send', 'pageview');
</script>
</head>
<body class="<?php echo (isMobile()) ? 'is-mobile' : 'is-desktop'; ?>">
<div class="watch-screen fillScreen">
	<a class="watch-screen__back-button" href="javascript:;" title="Back to the Mash">Back</a>
	<div class="watch-screen__video-wrapper">
		<iframe id="watch-screen__video" class="fillScreen" allowfullscreen frameborder="0" src="javascript:;"></iframe>
	</div>
</div>
<div class="browse-screen">
	<h1 class="browse-screen__header"><a class="browse-screen__header-link" href="javascript:;" title="Get the gnar!">Today In Skateboarding</a></h1>
	<h2 class="browse-screen__subheader">Today. In Skateboarding.</h2>
	<h5 class="browse-screen__footer"></h5>
	<ul class="browse-screen__feed clrfx">
		<li class="fillScreen">
			<div class="feed__fetch-message">No videos yet?!<br /><a class="feed__fetch-message-button" href="javascript:;" title="Tap to refresh">Get The Gnar</a>
			</div>
		</li>
	</ul>
</div>
<script>
;(function(window, document, undefined) {

  var tag = document.createElement('script');
  tag.src = 'https://www.youtube.com/iframe_api';
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

})(window, window.document);
</script>
<script src="script.js"></script>
</body>
</html>
