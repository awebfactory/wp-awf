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
  echo "\n\n" . 'Processing ' . $legacy_user['name'] . "\n";

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
    echo 'legacy uid ' . $legacy_user['uid'] . ' **IS** registered.' . "\n";
    // print_r($the_user, false);
    // retrieve ID
    echo 'legacy uid ' . $legacy_user['uid'] . ' already registered as ' . $the_user[0]->user_login . "\n";
    // update user
    $user_id = wp_update_user(
		  array(
        'ID' => $the_user[0]['ID'],
			  'user_email' => $legacy_user['mail'],
			  'user_login' => $legacy_user['name'],
			  'role'       => get_legacy_roles($legacy_user['roles']),
		  )
	  );

    // On success.
    if ( ! is_wp_error( $user_id ) ) {
      echo "User updated : ". $user_id;
        // update meta attributes via ID using:
        //   update_user_meta( int $user_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
        //   https://developer.wordpress.org/reference/functions/update_user_meta/
        //   "If the meta field for the user does not exist, it will be added."
    }
  } else {
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
        echo "User inserted : ". $user_id;
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