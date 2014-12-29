<!DOCTYPE html>

<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	require("phpQuery/phpQuery/phpQuery.php");

	// Scrape page "http://www.reddit.com/r/gifs"
	$context = stream_context_create(array(
			'http' => array(
				'timeout' => 5 // add timeout in seconds
			)
		)
	);
	$redditContent = file_get_contents("http://www.reddit.com/r/gifs", false, $context);

	if ($redditContent){
		$doc = phpQuery::newDocument($redditContent);
		phpQuery::selectDocument($doc);
		$nextPageURL = $doc[".nextprev a[rel=\"nofollow next\"]"]->attr("href");
		$prevPageURL = $doc[".nextprev a[rel=\"nofollow prev\"]"]->attr("href");

		$gifs = array();
		$titles = array();
		$commentLinks = array();

		$i = 0;
		foreach (pq("div.thing > div.entry > p.title > a.title") as $aTag){
			$link = pq($aTag)->attr("href");
			if (endsWith($link, ".gif")){
				$text = pq($aTag)->text();
				$comments = pq("div.thing > div.entry:eq(" . $i . ") > ul.flat-list > li.first > a.comments")->attr("href");

				array_push($gifs, $link);
				array_push($titles, $text);
				array_push($commentLinks, $comments);
			}
			$i++;
		}


	}

	function htmlToPrintable($html){
		return str_replace(">", "&gt;", str_replace("<", "&lt;", $html));
	};

	// Example: var_dump(endsWith("hello world", "world"));   // true
	function endsWith($haystack, $needle) {
	    // search forward starting from end minus needle length characters
	    return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
	};

?>


<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Page Title</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/main.css"/>

		<?php
			if (!$redditContent)
				echo "<script>window.location = 'timeout.html';</script>";
			else{
				$script = "
				<script>
					var gifs = " . json_encode($gifs) . ";
					var titles = " . json_encode($titles) . ";
					var comments = " . json_encode($commentLinks) . ";
				";
				if (!isset($_REQUEST["n"]))
					$script .= "var index = 0;";
				else
					$script .= "var index = " . $_REQUEST["n"] . ";";
				$script .= "</script>";
				echo $script;
			}
		?>

	</head>
	<body>

		<!--<img id="background-gif" rel:animated_src="<?php echo $gifs[0]; ?>" rel:auto_play="0" />-->
		<img id="background-gif" src="<?php echo $gifs[0]; ?>" />

		<div id="navbar" class="container">
			<div class="container">
				<div class="col-md-2">
					<button id="prev" class="btn btn-primary">Prev</button>
					<button id="next" class="btn btn-primary">Next</button>
				</div>
				<div class="col-md-10">
					<a id="gif-name" href="<?php echo $commentLinks[0]; ?>"><?php echo $titles[0]; ?></a>
				</div>
			</div>
		</div>

		<div id="gifContainer">
			<img id="gif" class="center-block" src="<?php echo $gifs[0]; ?>" />
		</div>

		<script src="js/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/utilities.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</body>
</html>