<?php
/**
 * Class contains functions used to generate strings
 */
class Cognito_Login_Generate_Strings {
  /**
   * URL to use in the login link login link href
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
   */
  public static function a_tag() {
    return '<a class="cognito-login-link" href="' . Cognito_Login_Generate_Strings::login_url() . '">Login Link</a>';
  }

  /**
   * String shown to the user instead of a login link when they are already logged in
   */
  public static function already_logged_in( $username ) {
    return 'You are logged in as ' . $username . ' | <a href="' . wp_logout_url() . '">Log Out</a>';
  }
}
