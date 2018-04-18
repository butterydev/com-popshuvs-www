<?php

function getMultipleRSSFeedsXML($rssFeeds, $options = array()) {

  // initiate arrays for the individual curl resources
  // and the returned xml data
  $curlResources = array();
  $returnedStrings = array();

  // create multiple curl resource
  // need to have the resource open to start
  $multipleCURLResource = curl_multi_init();

  // loop through $rssFeeds and create individual curl resources
  // then add them to the multi-handle
  foreach($rssFeeds as $feedName => $url) {

    // create individual curl resource
    $curlResources[$feedName] = curl_init();

    // set curl options for each individual curl resource
    curl_setopt($curlResources[$feedName], CURLOPT_URL, $url); // set url
    curl_setopt($curlResources[$feedName], CURLOPT_HEADER, 0); // don't include header info
    curl_setopt($curlResources[$feedName], CURLOPT_RETURNTRANSFER, 1); // return data as string

    // add individual curl resource to the multiple curl resource
    // we will execute the multiple curl resource in a bit
    curl_multi_add_handle($multipleCURLResource, $curlResources[$feedName]);

    // close individual curl resource
    curl_close($curlResources[$feedName]);

  }

 // execute the multiple curl resources
  $curlMultiExecRunning = null;

  do {

    curl_multi_exec($multipleCURLResource,  $curlMultiExecRunning);

  } while($curlMultiExecRunning > 0);


  // get the response data (returned strings) and remove handles
  foreach($curlResources as $feedName => $curlResource) {

    $returnedStrings[$feedName] = curl_multi_getcontent($curlResource);

    curl_multi_remove_handle($multipleCURLResource, $curlResource);

  }

  // close multiple curl resource
  curl_multi_close($multipleCURLResource);

  // return the data
  return $returnedStrings;

}

function multipeRSSFeedsXMLToSingleObject($multipeRSSFeedsXML) {

  // this only works because YouTube XML is all formatted the same
  $combindedRSSFeedsXMLObject = array();

  foreach($multipeRSSFeedsXML as $xmlDoc) {

    $rssFeedXML = new SimpleXmlElement($xmlDoc);

    $combindedRSSFeedsXMLObject = (object) array_merge_recursive((array) $combindedRSSFeedsXMLObject, (array) $rssFeedXML);

  }

  return $combindedRSSFeedsXMLObject;

}

function writeRSSFeedsXMLToFile($rssFeedsXMLObject, $filename = 'entries.xml') {

  $documentText = '';
  $rssFeedsXMLEntries = '';

  foreach($rssFeedsXMLObject->entry as $entry) {

    $rssFeedsXMLEntries .= $entry->asXML();

  }

  $rssFeedsXMLEntries = '<feed xmlns:yt="http://www.youtube.com/xml/schemas/2015" xmlns:media="http://search.yahoo.com/mrss/" xmlns="http://www.w3.org/2005/Atom">' . $rssFeedsXMLEntries . '</feed>';

  //$documentText = '<?php $entriesXML = new SimpleXmlElement("' . $rssFeedsXMLEntries . '");';

  $documentText = $rssFeedsXMLEntries;

  file_put_contents($filename, $documentText);

}

function createEntriesArrayFromCombinedXMLObject($combinedXMLObject) {

  $entriesArray = array();

  foreach ($combinedXMLObject->entry as $entry) {

    array_push($entriesArray, $entry);

  }

  return $entriesArray;

}

function sortEntriesArray($entriesArray) {

  $sortedEntriesArray = array();

  usort($entriesArray, function($a, $b) {

    return strtotime($b->published) - strtotime($a->published);

  });

  $sortedEntriesArray = $entriesArray;

  return $sortedEntriesArray;

}

function filterEntriesArrayByDateToday($entriesArray) {

  $filteredEntriesArray = array();

  $filteredEntriesArray = array_filter($entriesArray, function($entry) {

    global $userLocalTime;
    global $userLocalTimezoneOffset;

    $entryLocalPublishTimestamp = getTimezoneOffsetTimestamp($userLocalTimezoneOffset, $entry->published);

    $entryLocalPublishTime = new DateTime();
    $entryLocalPublishTime->setTimestamp($entryLocalPublishTimestamp);

    if($userLocalTime->format('Y-m-d') === $entryLocalPublishTime->format('Y-m-d')) {

      return $entry;

    }

  });

  return $filteredEntriesArray;

}

function getTimezoneOffsetTimestamp($timezoneOffsetString, $dateTimeString) {

  $timezoneoffsetTimestamp = '';

  $timezoneoffsetTimestamp = strtotime($timezoneOffsetString . ' minutes', strtotime($dateTimeString));

  return $timezoneoffsetTimestamp;

}

function timeElapsedString($datetime, $full = false) {

  global $userLocalTime;

  $now = $userLocalTime;
  $ago = $datetime;
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );

  foreach ($string as $k => &$v) {

    if ($diff->$k) {

      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

    } else {

      unset($string[$k]);

    }
  }

  if(!$full) $string = array_slice($string, 0, 1);

  return $string ? implode(', ', $string) . ' ago' : 'Just Now';

}

function getScriptExecutionTime() {

  return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

}

function printRVarDump($var) {

    echo('<pre>');
    print_r(var_dump($var));
    echo('</pre>');

}
