<?php

class api_routes_social {
	
	public function __init() {
		add_filter( 'json_endpoints', array( $this, 'register_routes' ) );
	}

	public function register_routes( $routes ) {
		$routes['/cc_user_check'] = array(
			array( array( $this, 'cc_user_check'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON )
		);
		$routes['/cc_user_login'] = array(
			array( array( $this, 'cc_login'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON )
		);
		
		return $routes;
	}
	
	public function cc_user_check( $data ) {
		$return = array('user' => false)
		
		if( isset( $data['email'] ) ) { 
			$return['user'] = email_exists( $data['email'] );
		}
		
		return $this->create_response( $return );
	}
	
	public function cc_login( $data ) {
		
		if( !isset( $data['email'] ) ) {
			return new WP_Error( 'No Email', __( 'No Email Set' ), array( 'status' => 401 ) );
		}
		
		if( is_user_logged_in() ) { 
			return new WP_Error( 'Logged In', __( 'Already Logged In' ), array( 'status' => 401 ) );
		}
		
		$return = array('new_user' => false);
		$user_email = $data['email'];
		$user_id = email_exists( $user_email );
		
		if ( !$user_id ) {
			$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			
			if( isset( $data['name']['username'] ) ) {
				$username = str_replace( ' ', '_', $data['name']['username'] );	
			} else {
				$username = $data['name']['first_name'].$data['name']['last_name'];
			}
			$username = strtolower( $username );
			$user_id = wp_create_user( $username, $random_password, $data['email'] );
			
			if( is_wp_error( $user_id ) ) {
				return new WP_Error( 'Create Error', __( $user_id->get_error_message() ), array( 'status' => 401 ) ); 
			}
			flush_rewrite_rules( 'true' );
			
			if( $data['name']['first_name'] && $data['name']['last_name'] ) {
				wp_update_user( array( 'ID' => $user_id, 'first_name' => $data['name']['first_name'], 'last_name' => $data['name']['last_name'] ) );
			}
			
			$return['new_user'] = true;
		}
		
		$user = get_user_by( 'id', $user_id );
		
		$return['user'] = $user;
		$return['user_id'] = $user_id;
		
		wp_set_current_user( $user_id, $user->user_login );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user->user_login, $user );
		
		return $this->create_response( $return );
	}
	
	private function create_response( $return ) {
		$response = new WP_JSON_Response();
		$response->set_data( $return );
		return $response;
	}
}
?>
