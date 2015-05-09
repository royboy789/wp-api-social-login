<?php
/**
 * Plugin Name: WP-API SOCIAL LOGIN
 * Plugin URI: http://codecavalry.com
 * Description: Using WP-API and Hello.js allow for seamless user registration and logging in via social networks
 * Version: 1.0
 * Author: Roy Sivan
 * Author URI: http://www.roysivan.com
 * License: GPL2
*/
 
define( 'API_SOCIAL_LOGIN_VERSION', '1.0' );
define( 'API_SOCIAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'API_SOCIAL_URL', plugin_dir_url( __FILE__ ) );
define( 'API_SOCIAL_FILE',  __FILE__ );

 
require 'inc/social-db-init.php';
require 'inc/social-routes.php'; 
require 'inc/social-enqueue.php';
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
		 
	}
	 
}

$SocialLogin = new wp_api_social_login();
$SocialLogin->__init();
 
 ?>