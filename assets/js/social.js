var $ = jQuery;
var url = socialLogin.api_url;

/** FACEBOOK **/
function facebook_connect( action, data ){
	hello("facebook").login().then( function() {
		console.log('logged in to Facebook');
		hello("facebook").api("me").then(function(json){
			if( action == 'login' ) {
				social_login( json, data );	
			} else {
				social_registration( json, data );
			}
		});
	},function(e){
		console.log(e.error_message);
	});	
}


/** TWITTER **/
function twitter_connect( action, data ){
	hello("twitter").login().then( function() {
		console.log('logged in to Twitter');
		hello("twitter").api("me").then(function(json){
			if( action == 'login' ) {
				social_login( json, data );
			} else {
				social_registration( json, data );
			}
		});
	},function(e){
		console.log(e.error_message);
	});	
}


/** GITHUB **/
function github_connect( action, data ){
	hello("github").login().then( function() {
		console.log('logged in to GitHub');
		hello("github").api("me").then(function(json){
			if( action == 'login' ) {
				social_login( json, data );	
			} else {
				social_registration( json, data );
			}
		});
	},function(e){
		console.log(e.error_message);
	});	
}


/** SOCIAL LOGIN **/

function social_login( json, form_data ) {
	var data = {
		social_id: json.id
	}
	
	if( json.email ) {
		data.user_email = json.email
	}
	
	$.post( url + '/social_login', data, function(res){
		if( res.ID ) {
			if( form_data && form_data.redirect ){
				redirect_user( form_data.redirect );
			} else {
				redirect_user( location.href );	
			}
		}
	})
	
}

/** SOCIAL REGISTRATION **/

function social_registration( json, form_data ) {
	
	var data = { social_id: json.id }
	
	if( json.email ) { data.user_email = json.email }
	if( json.first_name ) { data.first_name = json.first_name }
	if( form_data.first_name ) { data.first_name = form_data.first_name }
	if( json.last_name ) { data.last_name = json.last_name }
	if( form_data.last_name ) { data.last_name = form_data.last_name }
	if( form_data.nickname ) { data.nickname = form_data.nickname }
	
	$.post( url + '/social_registration', data, function(res){
		if( res.ID ) {
			if( form_data && form_data.redirect ){
				redirect_user( form_data.redirect );
			} else {
				redirect_user( location.href );	
			}
		}
	}).fail(function(res){
		alert( res.responseJSON[0].message );
	});
	
}


/** REDIRECT **/

function redirect_user( location ) {
	window.location.href = location;
}

$(document).ready(function(){
	
	hello.init({
		facebook: socialLogin.facebook,
		twitter: socialLogin.twitter,
		github: socialLogin.github
	}, {
		scope: 'email'
	});
	
	$('body').on('click', '.social-login', function(e){
		e.preventDefault();
		var network = $(this).data('network'),
		action = 'register',
		action = $(this).data('social-action');
		
		if( network == 'facebook' ) {
			facebook_connect( action );
		} else if( network == 'twitter' ) {
			twitter_connect( action );
		} else if( network == 'github' ) {
			github_connect( action );
		}
	})
		
	
})