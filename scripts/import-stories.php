<?php
/**
 * execution
 * 
 * $ wp eval-file scripts/import-stories.php data/inv-stories.txt
 */

// get file path as command-line argument argument
global $argv;
// print_r($argv, false);

$legacy_stories = file_get_contents($argv[3]);
print_r($legacy_stories, false);