<?php
/**
 * execution
 * 
 * $ wp eval-file scripts/import-tags.php data/inv-terms.txt
 * 
 * test with wp-cli: wp term list post_tag
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
  $lt = json_decode($legacy_term, true);
  echo "tag: " . $lt['tagSlug'] . "\n";
  // print_r($lt, true);
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


  // https://developer.wordpress.org/reference/functions/wp_insert_term/
  $term_id = wp_insert_term(
    $lt['tagName'], // the term 
    'post_tag', // the taxonomy: custom, else post_tag or category
    array(
      'description'=> $lt['tagDescription'],
      'slug' => $lt['tagSlug'],
	  // not applicable in this project
      // 'parent'=> $parent_term['term_id']  // get numeric term id
	)
  );
  echo $term_id;
  print_r($term_id, false);

  if ( ! is_wp_error( $term_id ) ) {
	echo "tag: " . $term_id['term_id'] . " / taxonomy: " . $term_id['term_taxonomy_id'] . " successfully inserted"; 

	// insert meta data for this term
	// update_term_meta( $term_id['term_id'], 'legacy_tag', array('lt' => $lt['legacyTag']));
	update_term_meta( $term_id['term_id'], 'legacy_tag', $lt['legacyTag']);
  } else {
	// show error
    print_r($term_id);     
  }
}