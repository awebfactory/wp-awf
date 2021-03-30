<?php
/**
 * execution
 * 
 * $ wp eval-file scripts/import-tags.php data/inv-terms.txt
 */

global $argv;
// print_r($argv, false);

// cannot decode right away, data given line by line, not in strict json format
// $legacy_terms = json_decode(file_get_contents($argv[3]), true);

$filename = $argv[3];
// print $filename . "\n";

$legacy_terms = file($filename);
// print_r($legacy_terms, false);

foreach($legacy_terms as $legacy_term) {
  print_r(json_decode($legacy_term, true));
  // Array
  // (
    // [tagSlug] => drush
    // [tagName] => drush
    // [tagDescription] => 
    // [vocabSlug] => freetags
    // [vocabName] => FreeTags
    // [legacyTag] => Array
        // (
            // [tagId] => 128
            // [tagSlug] => drush
            // [tagName] => drush
            // [tagDescription] => 
            // [vocabId] => 3
            // [vocabSlug] => freetags
            // [vocabName] => FreeTags
        // )
  // 
  // )
}