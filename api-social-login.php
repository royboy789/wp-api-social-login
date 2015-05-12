<?php
/**
 * Plugin Name: WP-API SOCIAL LOGIN
 * Plugin URI: https://github.com/royboy789/wp-api-social-login
 * Description: Using WP-API and Hello.js allow for seamless user registration and logging in via social networks. Issues handled through GitHub - https://github.com/royboy789/wp-api-social-login
 * Version: 1.1
 * Author: Roy Sivan, Carl Alexander
 * Text Domain:		wpapi_social_login
 * Author URI: https://github.com/royboy789/wp-api-social-login
 * License: GPL2
 * GitHub Plugin URI: https://github.com/royboy789/wp-api-social-login
 * GitHub Branch: master
*/
 
define( 'API_SOCIAL_LOGIN_VERSION', '1.0' );
define( 'API_SOCIAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'API_SOCIAL_URL', plugin_dir_url( __FILE__ ) );
define( 'API_SOCIAL_FILE',  __FILE__ );

 
require 'inc/social-db-init.php';
require 'inc/social-routes.php'; 
require 'inc/social-enqueue.php';
require 'inc/social-shortcode.php';
require 'inc/social-admin.php';

global $wpdb;

class wp_api_social_login {
	 
	function __init()  {
		
		/** ACTIVATION HOOK - DB CREATION **/
		$social_db = new social_db_init();
		$social_db->__init();
		
		/** ENQUEUE SCRIPTS **/
		$social_enqueue = new social_enqueue();
		$social_enqueue->__init();
		
		/** SOCIAL ROUTES **/
		$social_routes = new api_routes_social();
		$social_routes->__init();
		
		/** SHORTCODE **/
		$social_shortcode = new social_shortcode();
		$social_shortcode->__init();
		
		/** ADMIN **/
		$social_admin = new social_admin();
		$social_admin->__init();
		 
	}
	 
}

$SocialLogin = new wp_api_social_login();
$SocialLogin->__init();
 
 ?>