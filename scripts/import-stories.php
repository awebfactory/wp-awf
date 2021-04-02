<?php
/**
 * Upsert stories from JSON migration file exported from Drupal 6
 * 
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
    $post_title = sanitize_title( $legacy_story['title']);
    $the_post = array(
        'post_type' => 'post',
        'post_title'    => $post_title,
        'post_name'    => 'node/' . $legacy_story['nid'],
        'post_content'  => $legacy_story['body'],
        'post_excerpt' => $legacy_story['teaser'],
        'post_status' => $legacy_story['status'] == 1 ? 'publish' : 'draft',
        'post_author'   => 1,


        'post_date' => $dateCreated,
        'post_date_gmt' => $dateCreated,
        'post_modified' => $dateChanged,
        'post_modified_gmt' => $dateChanged,
        'comment_status' => 'closed',
    );
    if ($post_id = post_exists($post_title)) {
        echo "|" . $post_id . "|";
        // add ID to array
        $the_post['ID'] = $post_id;
        // update post
        $post_id = wp_update_post($the_post);
        if ($post_id) {
            echo "\nupdated: " . $post_title . "\n"; 
        } else {
            echo "\nerror updating: " . $post_title . "\n"; 
        }
    } else {
        $post_id = wp_insert_post($the_post);
        if ($post_id) {
            echo "\ninserted: " . $post_title . "\n"; 
        } else {
            echo "\nerror inserting: " . $post_title . "\n"; 
        }
    }
}