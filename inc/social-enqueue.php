<?php

class social_enqueue {
	
	function __init() {
		add_action( 'wp_enqueue_scripts', array( $this, '__social_scripts_enqueue' ) );
	}
	
	function __social_scripts_enqueue() {
		$dev = false;
		
		if( $dev ) {
			wp_enqueue_script( 'hello-js', API_SOCIAL_URL.'/assets/js/hello.all.js', array( 'jquery' ), API_SOCIAL_LOGIN_VERSION, false );
			wp_enqueue_script( 'social-js', API_SOCIAL_URL.'/assets/js/social.js', array( 'hello-js' ), API_SOCIAL_LOGIN_VERSION, false );	
		} else {
			wp_enqueue_script( 'hello-js', API_SOCIAL_URL.'/build/js/hello.all.min.js', array( 'jquery' ), API_SOCIAL_LOGIN_VERSION, false );
			wp_enqueue_script( 'social-js', API_SOCIAL_URL.'/build/js/social.min.js', array( 'hello-js' ), API_SOCIAL_LOGIN_VERSION, false );
		}
		
		
		$app_data = array(
			'api_url' => ''
		);
		
		if( function_exists( 'rest_get_url_prefix' ) ) {
			$app_data['api_url'] = get_bloginfo( 'wpurl') . '/' . rest_get_url_prefix() . '/wp/v2';
		}
		
		if( function_exists( 'json_url' ) ) {
			$app_data['api_url'] = json_url();
		}
		
		$social_app = $this->__get_social_apps();
		foreach( $social_app as $key => $value ) {
			if( !empty( $value ) )
				$app_data[$key] = $value;
		}
		
		wp_localize_script( 
			'social-js',
			'socialLogin',
			$app_data
		);
		
	}
	
	private function __get_social_apps() {
		
		$social_app = array(
			'facebook' => get_option( '_wpapi_social_facebook_app', '' ),
			'twitter' => get_option( '_wpapi_social_twitter_app', '' ),
			'github' => get_option( '_wpapi_social_github_app', '' ),
		);
		
		return $social_app;
		
	}
	
}