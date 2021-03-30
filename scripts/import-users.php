<?php
/**
 * execution
 * 
 * $ wp eval-file scripts/import-users.php data/inv-users.txt
 */

// get file path as command-line argument argument
global $argv;
// print_r($argv, false);

// $legacy_users = json_decode(file_get_contents('data/inv-users.txt'), true);
$legacy_users = json_decode(file_get_contents($argv[3]), true);

/* debug to confirm soundness of legacy users input data */
/*
foreach ($legacy_users as $legacy_user) {
  echo "\n\n" . 'Found ' . $legacy_user['uid'] . ': ' . $legacy_user['name'] . "\n";
}
exit('found ' . sizeof($legacy_users) . ' legacy users' . "\n\n");
*/

// print_r($legacy_users, false);
// print_r($legacy_users[2], false);

// iterate over users and upsert them
foreach ($legacy_users as $legacy_user) {
  // skip Drupal anonymous and default admin user
  if ($legacy_user['uid'] < 2) continue;

  echo "\n\n" . 'Processing legacy user ' . $legacy_user['uid'] . ': ' . $legacy_user['name'] . "\n";

  // ref: Find All Users with Certain Meta Data in WordPress,
  // https://cullenwebservices.com/find-all-users-with-certain-meta-data-in-wordpress/
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
  // if a user with this legacy id (meta attribute) already exists
  if ($the_user) {
    // already registered, so update
    echo 'legacy uid ' . $legacy_user['uid'] . ' is already registered as ' . $the_user[0]->user_login . "\n";

    // print_r($the_user, false);
    // print_r($the_user[0]->ID);

    // update user
    $user_data_array = array(
        'ID' => $the_user[0]->ID,
			  'user_email' => $legacy_user['mail'],
			  'user_login' => $legacy_user['name'],
			  'role'       => get_legacy_roles($legacy_user['roles']),
    );
    // print_r($user_data_array, false);
    $user_id = wp_update_user(
      $user_data_array
	  );
    // echo "user id: ";
    // print_r($user_id, false);

    // On success.
    if ( ! is_wp_error( $user_id ) ) {
      // update meta attributes via ID using:
      //   update_user_meta( int $user_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
      //   https://developer.wordpress.org/reference/functions/update_user_meta/
      //   "If the meta field for the user does not exist, it will be added."

      update_user_meta($user_id, 'legacy_entity', 'awebfactory');
      update_user_meta($user_id, 'legacy_id', $legacy_user['uid']);
      echo "legacy_uid updated as ID: ". $user_id . "\n";
    } else {
      echo "update user meta fails: ";
      print_r($user_id, false);
    }
  } else {
    // Not registered, so create
    echo 'legacy uid ' . $legacy_user['uid'] . ' not registered.' . "\n";
    // print_r($legacy_user, false);

    // Check that user name and email are free before creating user
    if (email_exists($legacy_user['mail']) || username_exists($legacy_user['name'])) {
      echo 'a user is already registered under same username or email';
    } else {
      // create the user, using insert (more options) 
      // See https://developer.wordpress.org/reference/functions/wp_insert_user/

      // omit password, on first login
      // user will have to reset or request from admin

      // no first or last names since
      // no legacy profile module available in this case

      $user_id = wp_insert_user(
		    array(
			    'user_email' => $legacy_user['mail'],
			    'user_login' => $legacy_user['name'],
			    // 'user_pass'  => $legacy_user['mail'],
			    // 'user_url'   => $legacy_user['mail'],
			    // 'first_name' => $legacy_user['mail'],
			    // 'last_name'  => $legacy_user['mail'],
			    'role'       => get_legacy_roles($legacy_user['roles']),
		    )
	    );

      // On success.
      if ( ! is_wp_error( $user_id ) ) {
        update_user_meta($user_id, 'legacy_entity', 'awebfactory');
        update_user_meta($user_id, 'legacy_id', $legacy_user['uid']);
        echo "User inserted : ". $user_id . "\n\n";
        // create meta attributes via ID using:
        //   update_user_meta( int $user_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
        //   https://developer.wordpress.org/reference/functions/update_user_meta/
        //   "If the meta field for the user does not exist, it will be added."
      }
    }
  }
}

function get_legacy_roles ($legacy_user_roles) {
  // We won't work with multiple roles in this migration
  if (in_array("admin", $legacy_user_roles)) {
    return "administrator";
  }
  if (in_array("editor", $legacy_user_roles)) {
    return "editor";
  }
  if (in_array("authenticated user", $legacy_user_roles)) {
    return "author";
  }
  return "subscriber";
}