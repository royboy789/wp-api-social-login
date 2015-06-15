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
		$social_routes = new WP_REST_Social();
		$social_routes->__init();
		
		
		/** SHORTCODE **/
		$social_shortcode = new social_shortcode();
		$social_shortcode->__init();
		
		/** ADMIN **/
		$social_admin = new social_admin();
		$social_admin->__init();
		 
	}
	 
}

/** JSON REST API CHECK **/
function api_social_dep() {
    if ( ! defined( 'REST_API_VERSION' ) ) {
        function wpsd_admin_notice() {
            printf( '<div class="error"><p>%s</p></div>', __( 'Activate the WP REST API plugin.  It
            is required.' ) );
        }
        add_action( 'admin_notices', 'social_wpapi_error' );
    } else {
	    $SocialLogin = new wp_api_social_login();
	    $SocialLogin->__init();
    }
}

function social_wpapi_error(){
	echo '<div class="error"><p><strong>JSON REST API</strong> must be installed and activated for the <strong>AngularJS for WP</strong> plugin to work properly - <a href="https://wordpress.org/plugins/json-rest-api/" target="_blank">Install Plugin</a></p></div>';
}

add_action( 'admin_init', 'api_social_dep', 99 );
add_action( 'init', 'api_social_dep', 99 );
 
 ?>