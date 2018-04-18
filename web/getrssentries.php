<?php

include('config.php');
include('functions.php');

$multipeRSSFeedsXML = getMultipleRSSFeedsXML($youTubeRSSFeeds);

$rssFeedsXMLObject = multipeRSSFeedsXMLToSingleObject($multipeRSSFeedsXML);

writeRSSFeedsXMLToFile($rssFeedsXMLObject, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'entries.xml');

// get rss data; returns a string
// parse string as XML
//


// go to URL
// get link to video, video preview image, title, publish timestamp, publisher name, durataion, views

// <a href="{{VIDEO_LINK}}">
// <div class="image" style="background-image: url('{{IMAGE}}');">&nbsp;</div>
// <div class="title">{{TITLE}}</div>
// <div class="published">{{PUBLISHED}}</div>
// <div class="author">{{AUTHOR}}</div>
// <div class="duration">{{DURATION}}</div>
// <div class="views">{{VIEWS}}</div>
// </a>