<?php
include_once( PLUGIN_PATH . 'includes/utils/generate-strings.php' );

class Cognito_Login_Auth{
  /**
   * Gets the "code" query variable, or FALSE if it doesn't exist
   */
  public static function get_code() {
    if ( isset( $_GET['code'] ) ) return $_GET['code'];
    
    return false;
  }

  /**
   * Uses a grant code to retrieve a token from the cognito user pool. Will return FALSE if
   * a token could not be retrieved
   * 
   * @param string $code Grant code
   * 
   * @return array|boolean Token object, or FALSE on token retrieval failure
   */
  public static function get_token( $code ) {
    $app_client_id = get_option('app_client_id');
    $redirect_url = get_option('redirect_url');

    $url = Cognito_Login_Generate_Strings::token_url();
    $opts = array(
      'method' => 'POST',
      'headers' => array(
        'Content-Type' => 'application/x-www-form-urlencoded',
        'Authorization' => Cognito_Login_Generate_Strings::authorization_header()
      ),
      'body' => array(
        'grant_type' => 'authorization_code',
        'code' => $code,
        'client_id' => $app_client_id,
        'redirect_uri' => $redirect_url
      )
    );

    $response = wp_remote_post($url, $opts);
    $status_code = $response['response']['code'];
    $token = json_decode($response['body'], TRUE);

    // Check failure cases
    // Non 200 status code
    if ( $status_code !== 200 ) return FALSE;

    // There is no response body, or the response body isn't valid JSON
    if ( $token === NULL ) return FALSE;

    // The returned token is missing required parameters
    if ( !isset($token['id_token'])) return FALSE;
    if ( !isset($token['access_token'])) return FALSE;
    if ( !isset($token['refresh_token'])) return FALSE;
    if ( !isset($token['expires_in'])) return FALSE;
    if ( !isset($token['token_type'])) return FALSE;

    // Done! We (probably) have a valid token
    return $token;
  }

  /**
   * Parses a JSON Web Token using the beautiful one-liner found here:
   * https://www.converticacommerce.com/support-maintenance/security/php-one-liner-decode-jwt-json-web-tokens/
   * 
   * @param string $token JSON Web Token to parse
   * 
   * @return array Parsed token data 
   */
  public static function parse_jwt( $token ) {
    return json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))), TRUE);
  }

  /**
   * Redirects the user to the provided url
   * 
   * THIS METHOD ENDS THE PHP PROCESS
   */
  public static function redirect_to( $url ) {
    wp_redirect( $url );
    exit;
  }
}
