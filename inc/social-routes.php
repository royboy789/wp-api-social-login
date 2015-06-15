<?php
	
class WP_REST_Social extends WP_REST_Controller {
	
	
	function __init() {
		
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		
	}

	public function register_routes() {
		
/*
		$routes['/social_login'] = array(
			array( array( $this, '__social_login'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON )
		);
		$routes['/social_registration'] = array(
			array( array( $this, '__social_registration'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON )
		);
*/
		
		register_rest_route( 'social_login', '/', array(
			
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, '__social_test' )
			
		) );
		
		register_rest_route( 'social_login', '/new', array(
			
			'methods'	=> WP_REST_Server::CREATABLE,
			'callback'  => array( $this, '__social_registration' )
			
		) );
		
		register_rest_route( 'social_login', '/login', array(
			
			'methods'	=> WP_REST_Server::CREATABLE,
			'callback'  => array( $this, '__social_login' )
			
		) );
		
	}
	
	public function __social_test() {
		
		$return = array( 'test' => 'testing' );
		
		return $this->create_response( $user );
	}
	
	public function __social_login( $data ) {
		// Expects social_id or user_email
		
		$user = $this->__user_exists_check( $data );
		
		if( !$user['user'] )
			return new WP_Error( 'No User', __( 'Not a valid user' ), array( 'status' => 401 ) );
		
		$user = $user['user'];
		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login, $user );		
		
		return $this->create_response( $user );
	}
	
	public function __social_registration( $data ) {
		// Expects social_id, user_email, and other user_info per WP user data
				
		$user = $this->__user_exists_check( $data );
		
		if( $user['user'] ) {
			return $this->create_response( $this->__social_login( $data ) );
		}
		
		$user_id = $this->__create_user( $data );
		
		if( is_wp_error( $user_id ) ) {
			return new WP_Error( 'Registration Error', __( $user_id->get_error_message() ), array( 'status' => 400 ) ); 
		}
		
		$user = get_user_by( 'id', $user_id );
		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login, $user );
				
		return $this->create_response( $user );
	}
	
	
	private function __user_exists_check( $data ) {
		// check if user exists in WP or DB
		$return = array('user' => false );
		
		if( isset( $data['user_email'] ) ) { 
			$email_check = email_exists( $data['user_email'] );
			if( $email_check ) {
				$db_user = $this->__user_db_check( $data['social_id'] );
				if( !$db_user ) {
					$this->__create_user_db( $email_check, $data['social_id'] );	
				}				
				$return['user'] = get_user_by( 'id', $email_check );
			}
		} 
		
		if( isset( $data['social_id'] ) && $return['user'] == false ) {
			$db_user = $this->__user_db_check( $data['social_id'] );
			if( $db_user ) {
				$return['user'] = get_user_by( 'id', $db_user->wp_user_id );	
			}
		} 
		
		if( !isset( $data['social_id'] ) && !isset( $data['user_email'] ) ) {
			return new WP_Error( 'No Data', __( 'Expecting social_id or user_email' ), array( 'status' => 400 ) );
		}
		
		return $return;
		
	}
	
	private function __user_db_check( $social_id ) {
		// Check wp_social_api table for user
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_api_social';
		
		$db_user_row = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE social_id = %s ", 
			$social_id 
		));

		return $db_user_row;
		
	}
	
	public function __create_user( $data ) {
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			
		if( isset( $data['nickname'] ) ) {
			$username = str_replace( ' ', '_', $data['nickname'] );	
		} else {
			$username = 'social_'.$data['social_id'];
		}
		$username = strtolower( $username );
		
		if( isset( $data['user_email'] ) ) {
			$user_email = $data['user_email'];
		} else {
			$user_email = $data['social_id'].'@'.$_SERVER['SERVER_NAME'];
		}
		
		$user_id = wp_create_user( $username, $random_password, $user_email );
		
		if( is_wp_error( $user_id ) ) {
			return new WP_Error( 'Create Error', __( $user_id->get_error_message() ), array( 'status' => 401 ) ); 
		}
		
		$user_update = array( 'ID' => $user_id );
		
		if( isset( $data['first_name'] ) ) {
			$user_update['first_name'] = $data['first_name'];
		}
		
		if( isset( $data['last_name'] ) ) {
			$user_update['last_name'] = $data['last_name'];
		}
		
		if( isset( $data['description'] ) ) {
			$user_update['description'] = $data['description'];
		}
		
		if( isset( $data['nickname'] ) ) {
			$user_update['user_nicename'] = $data['nickname'];
		}
		
		$update_user = wp_update_user( $user_update );
		
		if( is_wp_error( $update_user ) ) {
			return new WP_Error( 'Update Error', __( $update_user->get_error_message() ), array( 'status' => 400 ) );
		}
		
		$this->__create_user_db( $user_id, $data['social_id'] );
		
		return $user_id;
	}
	
	private function __create_user_db( $user_id, $social_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_api_social';
		
		$db = $wpdb->insert(
			$table_name,
			array(
				'created_time' 	=> current_time( 'mysql' ),
				'social_id' 	=> $social_id,
				'wp_user_id' 	=> $user_id
			),
			array(
				'%s',
				'%d',
				'%d'
		));
	}
	
	public function __user_delete( $user_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_api_social';
		
		$db = $wpdb->delete(
			$table_name,
			array(
				'wp_user_id' 	=> $user_id
			),
			array(
				'%d',
		));
		
	}
	
	private function create_response( $return ) {
		$response = new WP_JSON_Response();
		$response->set_data( $return );
		return $response;
	}
}
?>
