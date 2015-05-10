var $ = jQuery;
var url = socialLogin.api_url;

/** FACEBOOK **/
function facebook_connect(){
	hello("facebook").login().then( function() {
		console.log('logged in to Facebook');
		hello("facebook").api("me").then(function(json){
			social_login( json );
		});
	},function(e){
		console.log(e.error_message);
	});	
}


/** TWITTER **/
function twitter_connect(){
	hello("twitter").login().then( function() {
		console.log('logged in to Twitter');
		hello("twitter").api("me").then(function(json){
			social_login( json );
		});
	},function(e){
		console.log(e.error_message);
	});	
}


/** GITHUB **/
function github_connect(){
	hello("github").login().then( function() {
		console.log('logged in to GitHub');
		hello("github").api("me").then(function(json){
			social_login( json );
		});
	},function(e){
		console.log(e.error_message);
	});	
}


/** SOCIAL LOGIN **/

function social_login( json ) {
	
	var data = {
		social_id: json.id
	}
	//if( json.email ) {
	//	data.user_email = json.email
	//}
	
	$.post( url + '/social_login', data, function(res){
		console.log( res );
	})
	
}

$(document).ready(function(){
	
	hello.init({
		facebook: "907221609321148",
		twitter: "Eb00uOj83F8khODD3lI8eqgWF",
		github: "490074a2ebeecd8ff906",
		google: "870684892978-c1mguuas1d6s0lpde78t17v6fusivfl9@developer.gserviceaccount.com"
	}, {
		scope: 'email'
	});
	
	$('body').on('click', '.facebook-login', function(e){
		e.preventDefault();
		facebook_connect();		
	});
	
	$('body').on('click', '.twitter-login', function(e){
		e.preventDefault();
		twitter_connect();		
	});
	
	$('body').on('click', '.github-login', function(e){
		e.preventDefault();
		github_connect();		
	});
			
	
})