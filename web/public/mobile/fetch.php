<?php

session_start();

// show errors for development
//ini_set('display_errors', '1');
//error_reporting(E_ALL);

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

} else {

	die();

}

date_default_timezone_set('UTC');

include_once('../../tests.php');

include_once('../../functions.php');

$userLocalTime = '';
$userLocalTimezoneOffset = '';

if(isset($_GET['time'])) {

  $userLocalTime = $_GET['time'];

}

if(isset($_GET['offset'])) {

  $userLocalTimezoneOffset = $_GET['offset'];

}

// TEST
//$userLocalTime = '2016-04-22T11:40:00.000Z';
//$userLocalTimezoneOffset = '-420';

$userLocalTimestamp = getTimezoneOffsetTimestamp($userLocalTimezoneOffset, $userLocalTime);

$userLocalTime = new DateTime();
$userLocalTime->setTimestamp($userLocalTimestamp);

$entries = new SimpleXMLElement(file_get_contents('../../entries.xml'));

$entriesArray = array();

$entriesArray = createEntriesArrayFromCombinedXMLObject($entries);

$entriesArray = sortEntriesArray($entriesArray);

$entriesArray = filterEntriesArrayByDateToday($entriesArray);

//$entriesArray = $testEntriesArray;

$reverseIndex = count($entriesArray) - 1;

$_SESSION['entries_current'] = array();

foreach($entriesArray as $index => $entry) {

	array_push($_SESSION['entries_current'], $index);

}

if(isset($_SESSION['entries_previous'])) {

	$currentPrevDiff = array_diff($_SESSION['entries_current'], $_SESSION['entries_previous']);

	//echo('<h5>Diff</h5>');
	//printRVarDump($currentPrevDiff);

}

foreach($entriesArray as $entry) {

  $videoID = explode(":", $entry->id)[2];

  $entryLocalPublishTimestamp = getTimezoneOffsetTimestamp($userLocalTimezoneOffset, $entry->published);

  $entryLocalPublishTime = new DateTime();
  $entryLocalPublishTime->setTimestamp($entryLocalPublishTimestamp);

  $timeAgo = timeElapsedString($entryLocalPublishTime);

  $isNew = false;

  if(isset($currentPrevDiff) && count($currentPrevDiff) > 0) {

	  if(array_key_exists($reverseIndex, $currentPrevDiff)) {

		  $isNew = true;

	  }

  }

?>

<li class="list-item" data-video-item-id="<?php echo $videoID; ?>" data-item-id="<?php echo $reverseIndex; ?>" data-is-new="<?php echo $isNew; ?>" data-item-min="<?php echo htmlentities($entry->author->name) . ' - ' . htmlentities($entry->title); ?>">
	<a class="feed__entry-link" href="javascript:;" data-video-id="<?php echo $videoID; ?>" title="<?php echo  htmlentities($entry->title); ?>" itemscope itemtype="https://schema.org/BlogPosting">
		<div class="feed__entry-img-wrapper">
			<div class="feed__entry-img-faux" style="background-image: url('//img.youtube.com/vi/<?php echo $videoID; ?>/sddefault.jpg');"></div>
		</div>
		<meta itemprop="datePublished" content="<?php echo htmlentities($entry->published); ?>">
		<div class="feed__entry-title" itemprop="name"><?php echo htmlentities($entry->title); ?></div>
		<div class="feed__entry-author" itemprop="creator"><?php echo htmlentities($entry->author->name); ?></div>
		<div class="feed__entry-published"><?php echo htmlentities($timeAgo); ?></div>
	</a>
</li>

<?php

$reverseIndex--;

}

if(isset($_SESSION['entries_current'])) {

	$_SESSION['entries_previous'] = array();

	foreach($_SESSION['entries_current'] as $entriesIndex) {

		array_push($_SESSION['entries_previous'], $entriesIndex);

	}

}


//echo('<h5>Current</h5>');
//printRVarDump($_SESSION['entries_current']);

//echo('<h5>Previous</h5>');
//printRVarDump($_SESSION['entries_previous']);

?>
