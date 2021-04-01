<?php
/**
 * execution
 * 
 * $ wp eval-file scripts/import-stories.php data/inv-stories.json
 */

// get file path as command-line argument argument
global $argv;
// print_r($argv, false);
// $legacy_stories = get_array($argv[3]);

$legacy_stories = json_decode(file_get_contents($argv[3]), true);
// print_r($legacy_stories, false);
// print count($legacy_stories) . "\n";



// iterate over users and upsert them
foreach ($legacy_stories as $legacy_story) {
	echo $legacy_story['title'] . "\n";

}