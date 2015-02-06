<?php
include 'simple_html_dom.php';

$html = file_get_html('http://developers.google.com/cloud/terms/');

$file = 'lastModifiedDates.txt';
$f = fopen($file, 'r+');

$lastModified = fgets($f);
$lastModified = str_replace(array("\r", "\n"), "", $lastModified);

$desiredString = "Last modified:";
$pageFormatChanged = true;
foreach($html->find('p') as $element)
	if (strpos($element, $desiredString) != false) {
		$pageFormatChanged = false;
		if ($lastModified == "") {
			file_put_contents($file, $element);
		} else {
			if ($element != $lastModified) {
				file_put_contents($file, $element);

				$message = "Differnt Google Cloud Platform TOS Version Detected";
				$message = wordwrap($message, 70, "\r\n");
				mail("daniel.ho@workday.com", "TOSDetect", $message);
			} else {
				echo "Same TOS Version\n";
			}
		}
	}
if ($pageFormatChanged) {
	$message = "Page formatting has changed";
	$message = wordwrap($message, 70, "\r\n");
	mail("daniel.ho@workday.com", "Page Format Changed", $message);
}

fclose($f);

?>
