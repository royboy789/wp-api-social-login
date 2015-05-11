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