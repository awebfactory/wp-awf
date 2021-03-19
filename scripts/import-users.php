<?php
/**
 * execution
 * 
 */

// get file path as command-line argument argument
global $argv;
// print_r($argv, false);

// $legacy_users = json_decode(file_get_contents('data/inv-users.txt'), true);
$legacy_users = json_decode(file_get_contents($argv[3]), true);

// print_r($legacy_users, false);
// print_r($legacy_users[2], false);

// iterate over users and upsert them
foreach ($legacy_users as $legacy_user) {
  $ID = null;
  //echo $legacy_user['name'] . "\n";
  // if a user with this legacy id (meta attribute) already exist?
  $args = array(
    'meta_query' => array(
      array(
        'key' => 'legacy_id',
        'value' => $legacy_user['uid'],
        'compare' => '='
      )
    )
  );
  $the_user = get_users($args);
  if ($the_user) {
    // print_r($the_user, false);
    echo $the_user[0]->user_login;
  } else {
    // update user
    // retrieve ID
    // create the user 
    // retrieve ID
  }
  //update meta attributes (they will be updated if they exist, otherwise created)
}

function not_registered ($legacy_id) {

}