var $ = jQuery,
network = '';

$(document).ready(function(){
	$('body').on('submit', '#social_login_form', function(e){
		e.preventDefault();
		
		var data = [],
		action = $(this).data('social-action');
		form_data = $(this).serializeArray(),
		social_network = {
			name: 'social_network',
			value: network
		};
		form_data.push( social_network );
		 
		$.each( form_data, function( key, value ) {
			value.name = value.name.replace( '_social_login_', '' );
			data[value.name] = value.value;
		});
		
		if( data.social_network == 'facebook' ) {
			facebook_connect( action, data );
		}
		if( data.social_network == 'twitter' ) {
			twitter_connect( action, data );
		}
		if( data.social_network == 'github' ) {
			github_connect( action, data );
		}
		
		
	});
	$('body').on('click', '#social_login_form input[type="submit"]', function(e){
		e.preventDefault();
		network = $(this).attr('name');
		network = network.replace( '_social_login_', '' );
		$(this).parents('form#social_login_form').submit();
	});
	
})