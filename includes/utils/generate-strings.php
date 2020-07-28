<?php
/**
 * Class contains functions used to generate strings
 */
class Cognito_Login_Generate_Strings {
  /**
   * URL to use in the login link href
   */
  public static function login_url() {
    $app_auth_url = get_option('app_auth_url');
    $app_client_id = get_option('app_client_id');
    $oauth_scopes = get_option('oauth_scopes');
    $redirect_url = get_option('redirect_url');

    return $app_auth_url . '/login?client_id=' . $app_client_id . '&response_type=code&scope=' . $oauth_scopes . '&redirect_uri=' . $redirect_url;
  }

  /**
   * URL to use when getting tokens
   */
  public static function token_url() {
    $app_auth_url = get_option('app_auth_url');

    return $app_auth_url . '/oauth2/token';
  }

  /**
   * Authorization header used when communicating with cognito
   */
  public static function authorization_header() {
    $app_client_id = get_option('app_client_id');
    $app_client_secret = get_option('app_client_secret');

    return 'Basic ' . base64_encode( $app_client_id . ':' . $app_client_secret );
  }

  /**
   * "a" tag for the login link
   * 
   * @param array $atts Possible attributes, text and class
   */
  public static function a_tag( $atts ) {
    $url = Cognito_Login_Generate_Strings::login_url();
    $text = $atts['text'] ?: get_option( 'login_link_text' ) ?: 'Login';
    $class = $atts['class'] ?: get_option( 'login_link_class' ) ?: 'cognito-login-link';

    return '<a class="' . $class . '" href="' . $url . '">' . $text . '</a>';
  }

  /**
   * String shown to the user instead of a login link when they are already logged in
   */
  public static function already_logged_in( $username ) {
    return 'You are logged in as ' . $username . ' | <a href="' . wp_logout_url() . '">Log Out</a>';
  }

  /**
   * Generates a cryptographically secure string of the requested length
   * 
   * @param int The length of the string to generate
   * 
   * @return string|boolean Randomly generated string or FALSE if generation failed
   */
  public static function password( $length ) {
    $password = '';
    for($i = 0; $i < $length; $i++) {
      $random_character = Cognito_Login_Generate_Strings::password_char();

      // If the character is FALSE, there was an error
      if ( $random_character === FALSE ) return FALSE;

      $password .= $random_character;
    }

    return $password;
  }

  public static function password_char() {
    $password_chars = get_option( 'password_chars' );
    try {
      return $password_chars[random_int(0, strlen( $password_chars ) - 1)];
    } catch( Exception $e ) {
      // An exception means a secure random byte generator is unavailable. Generate an insecure
      // character or return FALSE
      if ( get_option( 'allow_insecure_pass') === 'true' ) {
        return $password_chars[mt_rand(0, strlen( $password_chars ) - 1)];
      }

      return FALSE;
    }
  }
}
