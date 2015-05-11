# WP-API SOCIAL LOGIN
We can do many awesome things with WP-API, when building [CodeCavalry](https://codecavalry.com) we needed a way to get people signed up logged in using social networks. While trying some existing plugins were not happy with the outcome and how the flow worked with our application. We created the start to a social login utilizing WP-API to more seamlessly integrate the login and registration.
  
This is v2 of our routes and plugin that includes a new table since some social API's don't respond with information you need to check if a user exists or create a user.
  
  
# USAGE #
Creation of 2 endpoints for __WP-API__  each accepts a data object array, make sure you are passing in an object named data:
  
`var data = { user_id: XXXXXXX, user_email: XXXX@YYY.com }`  
  
`/social_login` - use this to login an existing user, will do a check if user exists and return WP Error if no user found or insufficient data  
__Data Paramenters__  
* `social_id` from API - __required__
* `user_email` from API or injected by user - _optional_
  
  
`/social_registration` - us this to register and login a new user.  
__Data Parameneters__  
* `social_id` from API - __required__
* `user_email` from API or user - __required__
* `first_name` - _optional_
* `last_name` - _optional_
* `nickname` - or WP user nicename _optional_
* `description` - _optional_
  
  
# SHORTCODE #  
Shortcode a form for your users -  
`[social_login]`  
__Shortcode Attributes__ 
*__nickname__ - `true/false` - use this to include a nickname override field (_default: false_)
*__nickname_place_holder__ - `string` - this will be the placeholder for the input field (_default: nickname_)
*__first_name__  - `true/false` - use this to include a first name override field (_default: false_)
*__first_name_placeholder__ - `string` - this will be the placeholder for the first name field (_default: First Name_)
*__last_name__  - `true/false` - use this to include a last name override field (_default: false_)
*__last_name_placeholder__ - `string` - this will be the placeholder for the last name field (_default: Last Name_)
*__submit_prefix__ - `string` - string to show before social network submit buttons (_default: Login with_)
*__networks__ - `comma seperated string` - separate with a comma all networks you want to offer (_default:facebook,twitter,github_)
*__social_action__ - `login/register` - used to identify if this is a login form or signup form (_default:login_)
