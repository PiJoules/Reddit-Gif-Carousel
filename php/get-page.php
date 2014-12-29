<?php

	/*
		Script for getting the next or previous page of reddit r/gifs
	*/

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	require_once("/phpQuery/phpQuery/phpQuery.php");

	$link = $_REQUEST["url"];

	$redditContent = file_get_contents($link);
	$doc = phpQuery::newDocument($redditContent);
	phpQuery::selectDocument($doc);
	$nextPageURL = $doc[".nextprev a[rel=\"nofollow next\"]"]->attr("href");
	if (!$nextPageURL)
		$nextPageURL = null;
	$prevPageURL = $doc[".nextprev a[rel=\"nofollow prev\"]"]->attr("href");
	if (!$prevPageURL)
		$prevPageURL = null;

	$gifs = array();
	foreach (pq("div.thing > div.entry > p.title > a.title") as $aTag){
		if (endsWith(pq($aTag)->attr("href"), ".gif"))
			array_push($gifs, pq($aTag)->attr("href"));
	}

	$response = array(
		"gifs" => $gifs,
		"nextPageURL" => $nextPageURL,
		"prevPageURL" => $prevPageURL
	);

	echo json_encode($response);

?>