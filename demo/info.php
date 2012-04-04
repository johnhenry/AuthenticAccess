<?php
	/*
	define("FACEBOOK_CLIENT_ID","");//Find this in the API Console for your application ("https://developers.facebook.com/apps")
	define("GOOGLE_CLIENT_ID","");//Find this in the API Console for your application ("https://code.google.com/apis/console")
	define("MICROSOFT_CLIENT_ID","");//Find this in the API Console for your application ("https://manage.dev.live.com")
	*/

	define("FACEBOOK_CLIENT_ID","118517021611853");
	define("GOOGLE_CLIENT_ID","655032677510.apps.googleusercontent.com");
	define("MICROSOFT_CLIENT_ID","000000004409B19E");

	define("FACEBOOK_SCOPE",implode(",",Array("email")));
	define("GOOGLE_SCOPE",implode("+",Array("https://www.googleapis.com/auth/userinfo.email")));
	define("MICROSOFT_SCOPE",implode("%20",Array("wl.emails","wl.signin")));
?>