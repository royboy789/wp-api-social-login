<?php

class social_enqueue {
	
	function __init() {
		add_action( 'wp_enqueue_scripts', array( $this, '__social_scripts_enqueue' ) );
	}
	
	function __social_scripts_enqueue() {

		wp_enqueue_script( 'hello-js', API_SOCIAL_URL.'/assets/js/hello.all.js', array( 'jquery' ), API_SOCIAL_LOGIN_VERSION, false );
		wp_enqueue_script( 'social-js', API_SOCIAL_URL.'/assets/js/social.js', array( 'hello-js' ), API_SOCIAL_LOGIN_VERSION, false );
		
		wp_localize_script( 
			'social-js',
			'socialLogin',
			array(
				'api_url' => json_url()
			)
		);
		
	}
	
}