<?php
global $wpdb;

class social_db_init {

	function __init() {
		
		register_activation_hook( API_SOCIAL_FILE, array( $this, '__social_activation' ) );
		//register_deactivation_hook(API_SOCIAL_PATH, array( $this, '__social_deactivatio' ) );
		
	}
	
	function __social_activation() {
		
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();		
		$table_name = $wpdb->prefix . 'wp_api_social';

		$sql = "CREATE TABLE $table_name (
			created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			social_id varchar(255) NOT NULL,
			wp_user_id INT(10) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );		
		
	}
	
}

?>