<?php

class social_admin {

	private $social_networks = array(
		'facebook' => 'https://developer.facebook.com',
		'twitter'  => 'https://apps.twitter.com/',
		'github'   => 'https://github.com/settings/developers'
	);

	function __init() {
		add_action( 'admin_menu', array( $this, '__social_settings_init' ) );
		add_action( 'admin_init', array( $this, '_social_save_settings' ) );
	}

	public function __social_settings_init() {
		add_submenu_page( 'options-general.php', 'Social API Settings', 'Social API', 'manage_options', 'social-api-settings', array( $this, '__social_settings_page' ) );
	}

	public function __social_settings_page() {

		$social_app = $this->__get_social_apps();

		$html =  '<style> .feature-filter { padding: 20px; } strong { font-weight: bold; }</style>';
		$html .= '<div class="wrap feature-filter">';
			$html .= '<form method="post">';
			$html .= '<h2>' . __( 'WP-API Social Login Settings', 'wpapi_social_login' ) . '</h2>';
			$html .= '<table width="100%" cellpadding="5" cellspacing="5" border="0">';

				$html .= '<tr><th colspan="2" width="40%"><h3>Social Network API\'s</h3></th>';
				$html .= '<th width="10%">&nbsp;</th>';
				$html .= '<th><h3>Additional Information for OAuth</h3></th></tr>';

				$i = 0;
				foreach( $this->social_networks as $key => $value ):
					$key_cap = ucfirst( $key );
					$html .= '<tr>';
						$html .= '<th scope="row" valign="top"><label for="'.$key.'_app">' . __( $key_cap, 'wpapi_social_login' ) . '</label></th>';
						$html .= '<td>';
							$html .= '<input style="display:block;width:100%;" id="'.$key.'_app" name="_wpapi_social_'.$key.'_app" value="'.$social_app[$key].'" placeholder="'.$key_cap.' App ID" /><br/>';
							$html .= '<span class="description">You can find your '.$key_cap.' APP ID in the APP settings - <a target="_blank" href="'.$value.'">'.$key_cap.' Developer Tools</a></span>';
						$html .= '</td>';

					if( $i == 0 ) {
						$html .= '<td></td>';
						$html .= '<td rowspan="'.count($this->social_networks).'" valign="top">';
							$html .= '<p>When using certain social networks OAUTH is required, setup is easy with a free proxy server - <a target="_blank" href="https://auth-server.herokuapp.com">https://auth-server.herokuapp.com</a></p>';
							$html .= '<ol>';
								$html .= '<li>Sign up with a social account</li>';
								$html .= '<li>Add a new app under "Manage Apps"</li>';
								$html .= '<li>Put in a reference, for your own knowledge</li>';
								$html .= '<li>Put in the domain you are using, it needs to match</li>';
								$html .= '<li>Put in the client_id, and client_secret <br/> <em>client_id needs to match the app id you enter to the left</em></li>';
								$html .= '<li>Save and you should have a working OAUTH server</li>';
							$html .= '</ol>';
						$html .= '</td>';
					}
					$html .= '</tr>';

					$i++;
				endforeach;

			$html .= '</table>';
			$html .= '<p class="submit"><input type="submit" value="Save Changes" class="button-primary" name="social_save"></p>';
			$html .= '</form>';
		$html .= '</div>';

		echo $html;
	}

	private function __get_social_apps() {

		$social_app = array(
			'facebook' => get_option( '_wpapi_social_facebook_app', '' ),
			'twitter' => get_option( '_wpapi_social_twitter_app', '' ),
			'github' => get_option( '_wpapi_social_github_app', '' ),
		);

		return $social_app;

	}

	function _social_save_settings() {
		if( isset( $_POST['social_save'] ) ) {
			foreach( $_POST as $key => $value ) {
				if( strpos( $key, '_wpapi_social_' ) !== false ) {
					$value = sanitize_text_field( $value );
					update_option( $key, $value );
				}
			}
		}

	}
}

?>