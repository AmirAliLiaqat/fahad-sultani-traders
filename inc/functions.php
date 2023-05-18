<?php
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/******************************************/
/***** Redirect logged in users to home page **********/
/******************************************/
function redirect_logged_in_users()
{
	if(is_user_logged_in())
	{
		wp_redirect(get_permalink(13));
	}
}
function redirect_not_logged_in_users()
{
	if(!is_user_logged_in())
	{
		wp_redirect( trailingslashit(get_bloginfo('url'))."login" );
	}
	
}
