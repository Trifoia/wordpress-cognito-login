<?php

include_once( PLUGIN_PATH . 'includes/utils/generate-strings.php' );

class Cognito_Login_User {
  /**
   * Creates a new user. Takes a parsed id token from Cognito. The provided array can have
   * the following values. All values are strings
   * - `cognito:username`: The Cognito username
   * - `email`: The user's email address
   * - `given_name`: The user's first name (Optional)
   * - `family_name`: The user's family name (Optional)
   * - `custom:role`: The user's role (optional)
   * 
   * This method will create a user with a randomly generated password
   * 
   * @param array $parsed_token A parsed id token from Cognito
   * 
   * @return object|boolean The newly created WordPress user or FALSE if creation failed
   */
  public static function create_user( $parsed_token ) {
    $username_attribute = get_option( 'username_attribute' );
    $userdata = array(
      'user_login' => $parsed_token[$username_attribute],
      'user_email' => $parsed_token['email'],
      'user_pass' => Cognito_Login_Generate_Strings::password( get_option('password_length' ) )
    );

    // Check for password generation failure
    if ( $userdata['user_pass'] === FALSE ) return FALSE;

    if ( isset( $parsed_token['given_name'] )) $userdata['first_name'] = $parsed_token['given_name'];
    if ( isset( $parsed_token['family_name'] )) $userdata['last_name'] = $parsed_token['family_name'];
    if ( isset( $parsed_token['custom:role'] )) $userdata['role'] = $parsed_token['custom:role'];

    $user_id = wp_insert_user( $userdata );
    if ( is_wp_error( $user_id ) ) return FALSE;

    return get_user_by( 'id', $user_id);
  }
}
