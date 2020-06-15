=== cognito-login ===
Contributors: thejoshuaevans
Tags: aws, cognito, oauth, login
Requires at least: 5.4.2
Tested up to: 5.4.2
Requires PHP: 7.2
Stable tag: 1.2.3
License: GPL-3.0
License URI: https://github.com/Trifoia/wordpress-cognito-login/blob/master/LICENSE

WordPress plugin for integrating with Cognito for User Pools

== Description ==

# Use
Add the `[cognito_login]` shortcode to create a login link that will take the user to a Cognito
login screen

No attributes for the `[cognito_login]` shortcode are required, but the following attributes
are available:
- "text" - The inner html of the login link
- "class" - The class of the login link <a> tag

## New User Creation
Using default settings, a new user will be created when logging in for the first time. As part
of the user creation process, a password will be generated for the WordPress user. This password
is not saved

This plugin supports role mapping. The plugin will look for a `custom:role` attribute to
determine what role the user should be added to

## Note on Alias Domains
Login sessions are domain specific. For example, if your server is accessible from "your-site.com"
and "www.your-site.com", and a user logs in at your-site.com, if they go to "www.your-site.com" they
will no longer be considered "logged in"

## Username Matching
For legacy reasons, if a user already exists in WordPress and is not formatted as an email, the
email attribute can still be used as the username as long as the email username (the part
before the @ symbol) matches the WordPress username. For example, the user "example-user" will
be logged in with the email "example-user@email.com"

# Settings
The following configurations can be found in the settings menu

## Cognito Auth Settings
- "User Pool ID" - The Cognito User Pool ID of the pool managing users
- "App Client ID" - The Cognito App Client ID for the app client interfacing with this plugin
- "App Client Secret" - The Cognito App Client Secret for the app client interfacing with this
                        plugin. When creating the App Client, a secret _must_ be generated
- "Redirect URL" - Redirect URL that the Cognito App Client expects
- "Web Authentication Base" - Base URL for the Cognito authentication endpoint associated with
                              the Learning Pool
- "OAuth Scopes" - OAuth scopes to use. Only 'openid' is required

## Plugin Settings
- "Homepage" - A URL to redirect users to once they have successfully logged in. Leave empty
               to not redirect the user
- "Login Link Text" - Default inner html for the login link
- "Login Link Class" - Default class for the login link <a> tag

## New User Settings
- "Create New User" - If a new user should be created when first logging in
- "Username Attribute" - User Attribute to use as a username
- "Password Length" - Length of the WP password generated for new users
- "Allow Insecure Passwords" - If a insecure randomizer should be used to generate passwords
                               when a cryptographically secure one is not available. Should
                               be left on the default (No) unless absolutely necessary
- "Password Characters" - Possible characters that can be used in the generated password
