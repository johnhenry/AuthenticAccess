#Authentic Access
Version: 0.5

##INTRODUCTION
	This is a library that you can use to allow your PHP application access to user data through a third party service provider.
		Important Note: while the workflow described below requires support for a non-javascript workflow, there is a step in which you may need to use Javascript.
	Keep in mind that many (if not most (if not all)) APIs should be considered beta products and are subject to changes.
		If you application does not work and you can't see to find the problem, it's entirely possible that the API's provider may have modified it, so check with them.
		However, this program is based on the OAuth 2.0 specification, so as long as the provider adheres to that, there should be no problem.

##PART I:SETTING UP YOUR APPLICATION
###I-0:Choosing a service provider.
		When choosing a service provider, you much choose one with the following two properties:
			1)Support for the OAuth 2.0 protocal (Unfortunately Twitter only supports OAuth 1.0 and so cannot be used with this library at this time).
			2)Support for a non-javascript workflow. (Unfortunately Linked In, only supports a workflow involving javascript).
		It's important to note that not all provides support the full OAuth 2.0 specification and will not work with this library.
			Popular providers NOT CURRENTLY SUPPORTED by this library include include Github, LinkedIn, and Twitter.
			Popular providers that do support this library include Facebook, Google, and Microsoft.

	###I-1: The OAuth 2.0 API Console
		Each OAuth 2.0 Provider should have an api console to manage your application online.
		####I-1a: Find it by searching the web for something like "YOUR_SERVICE_PROVIDER OAuth 2.0 API" or try contacting the service provider directly.
			A list of API Consoles URIs for a few popular providers is provided in part IV-1.
		####I-1b: Once you have found the API console, you are going to have to create a new app. The way in which you do this may vary between providers, but it should be easy to figure out.
		####I-1c: Next, set up your applications redirect URI(s). This is where the application will redirect an access token for use when retrieving data.
			Note that some providers will need you the specify the exact page to which the data will be directed (like Google), while others only need that the domain be specified (like Microsoft). Still others may not need you actually specify the redirect uri at all(Facebook?).
		####I-1d: Finally get the take note of the application's ID (also know as a lient ID). It will be used very shortly.
		####I-1e: You may notice that your application also has something called a "Secret" or an "Application Secret". You can ignore this as it is not needed for this workflow. It's generally only applicable to workflows that need javascript.

	###I-2: Coding Your Starting Page
		Your app needs a starting page. This is where the user will begin the process of allowing the OAuth provider to grand your application data.
		####I-2a: On the starting page of your app, import the included "API_Service.php" file using the PHP Standard Library Function include or require. I prefer to use require, but it doesn't matter that much.
		####I-2b: Next, use the API_Service constructor to create a new service object. Your code should like something like this:
			$service = new API_Service($state,$auth_url,$client_id,$scope,$redirect_uri,$response_type);
			But before you can actually create the service object, you need to define all of the parameters to pass into the function. Note that each parameter must be a string or a number.
				$state - This will be returned to your redirect URI along with your access token. It's best use is if you have multiple services that direct to the same address and you need a way to differentiate between them.
				$auth_uri - This is the address that you will send the user to in order to access their data.
					A list of AUTH URIs for some popular providers is provided in part IV-2.
				$client_id - This is the same as the application's Client ID that you should have gotten from the API Console In step I-1d.
				$scope - This defines the user data that your application is requesting. This string takes the form a a delimited list of scopes as defined by your applications service provider.
					Note that the delimiter for the list may also vary by provider.
					A list of scopes for some popular providers along with their necessary delimiters is provided in part IV-3.
				$redirect_uri - This is the page to which the data will be delivered once the user authorizes the service provider to give your application the requested information. This page must match the request uri described in step I-1c.
					You will be setting up this page in step I-3.
				$request_type - While there are multiple request types that you can make, this workflow uses the "token" request type. You can actually leave this parameter out and it will automatically be set to "token".
			Here is a more concrete example using Facebook:
				```$service = new API_Service("facebook" ,API_SERVICE::AUTH_URI_FACEBOOK ,FACEBOOK_CLIENT_ID ,FACEBOOK_SCOPE ,"www.myapplication.com/login.php");```
		####I-2c: All that is left to do is to direct a user to to the state's link provided by $service-> link. You may do this by adding it to an anchor's href attribute like so:
			```<a href="<?php echo($service->link); ?>">Log in with OAuth 2.0</a>```

	###I-3: Coding Your Ending Page
		Your app needs an ending page. This is where the user will be directed once he or she has authorized service provider to deliver his or her data. This page must match the request uri described in step I-1c.
		####I-3a: Your access token will be passed through a url's hash string. (Things that show up after a "#"). Unfortunately, as far as I can tell, PHP cannot read this hash string. 
			As of now the best way to do this is to use javascript to redirect the user to a web page with the hash "#" replaced by a question mark "?".
				You may place the following Javascirpt on your ending page before your PHP code in order to do such that:
					```<script> if(document.URL.indexOf("#") !== -1) window.location = document.URL.replace("#","?");</script>```
				This code turns the hash string into a query string, thereby making the passed parameters available to php's $_GET and $_REQUEST arrays. Hopefully that's enough to be able to move on...
		####I-3b: On the ending page of your app, import the included "API_Service.php" file using the PHP Standard Library Function include or require.
		####I-3c: Next, call the API_SERVICE static function, Retrive_Data to retrieve data. Your code should look something like this:
				$data = API_SERVICE::Retrive_Data($retrieve_uri,$access_token,$data_function);
				But before you can do that, you need to defile all of the parameters to pass into the function.
					$retrieve_uri - Each OAuth service provider should have a url to which you can send your access token and retrieve the requested data.
						A list of Retrieve URI's for a few popular providers is provided in Part IV-4.
					$access_token - This is the access token sent by the service provider. If you used the technique described in I-3a, it should be available through $_GET["access_token"] as well as $_REQUEST["access_token"].
					$data_function - This is a funciton that you can define to process data retrieved from the OAuth provider. If the $data_function is omitted, API_SERVICE::Retrieve_Data will return the raw data - usally formatted as JSON.
				Here is a more concrete example using Facebook:
					```$data = API_SERVICE::Retrive_Data("https://graph.facebook.com/me/",$_REQUEST['access_token']);```
		####I-3d: Now, feel free to do what you want with the retrieved data.

##PART II:TESTING AS AN END USER
	Even though your app is ready, you are still going to want to check to make sure that it works properly as a user. 
	###II-1: Signing Up as a User.
		You should first create a user account with a service provider so that you can test access to certain information. Note that different providers keep track of different information.
	###II-2: Managing Authorized Apps
		There will be times where you need to know whether or not you have authorized an application to access your accout or deauthorize an application to start from scratch.
		You can usually do this from your providers Management Consoles
			A list of Management Console URIs for a few popular providers is provided in Part IV-5.

##Part III:COMMON ISSUES
	When a user attemps to use my application, Microsoft (or some other provier) claims that my redirect uri is invalid, although I'm sure it's correct.
		It seems to take a while for this change to propogate throughout Microsoft's ecosystem. All you can really do is wait. I haven't observed a similar issue with Google or Facebook.
	I don't know how to set up the data function for API_SERVICE::Retrive_Data.
		There is no standard way in which you can expect a provider to return data, so it's best to try using
	Even though the use is logged in, Microsoft (or some other provider) insisists that the user enter his or her password before proceeding.
		You can look at this as either a bug or a feature. Microsoft specifically requires that you add the scope "wl.signin" in order for the user not to have to enter a password without having to log in. There may be a similar method for other providers.
	I can't find the proper URIs for (insert service provider here).
		I've scowered all sorts of pages and documentation to find the needed URIs for the most common service providers so that you don't have to! Unfortunately, these are not easy to find for all providers.
		I can suggest two things:
			1) I haven't used standard names for these uri (for example "auth uri" and "retrieve uri" are just names that I made up so that I could keep track of them), so try searching for terms who's definitions seem that sound similar to mine.
			2) Look through the service provider's documentation and look at variables attached to certain URIs. 
				If "access_token" is attached to a URI in the documentation, that's probably I called "retrieve uri". 
				If it has "scope", "client_id", etc. attached, that's probably what I call "auth uri".
	Can I test this locally (without having to upload it to a remote server?)
		You can do this only if the specified redirect URI is accessable to the service provider.
	Why is there no list of scopes for Google?
		 I would love to be able to provide a list for you, but I can't find one.) 
		 A few scopes include 'https://www.googleapis.com/auth/userinfo.email' and 'https://www.googleapis.com/auth/userinfo.profile'
		 And yes, they do appear to be long urls.
	I want to use a provider that is not supported.
		While I'm trying my best to provide a workflow that supports all providers, this is difficult because not all providers support the full OAuth 2.0 specification.
		I'm in the process of adding support for all possible workflows to this library, but the best thing that you can do to help is to contact your provider an ask them to include support for the full spec.
	This documentation is confusing!
		I'm still working on it...
		
##Part IV: USEFUL URIS
	Here are some useful URIs
	###IV-1: API Consoles
		Facebook : `https://developers.facebook.com/apps`
		Google   : `https://code.google.com/apis/console`
		Microsoft: `https://manage.dev.live.com`

	###IV-2: Auth URIs
		Facebook : `https://www.facebook.com/dialog/oauth`
		Google   : `https://accounts.google.com/o/oauth2/auth`
		Microsoft: `https://oauth.live.com/authorize`
			These specific urls are available as static members of the API_Service class via API_SERVICE::AUTH_URI_FACEBOOK, API_SERVICE::AUTH_URI_GOOGLE, API_SERVICE::AUTH_URI_MICROSOFT, and API_SERVICE::AUTH_URI_GITHUB.

	###IV-3: Scopes
		Facebook : `https://developers.facebook.com/docs/authentication/permissions/`
			Delimiter:`"," (A comma)`
		Google   : `(See "Common Issues" section)`
			Delimiter:`"+" (A plus sign)`
		Microsoft: `http://msdn.microsoft.com/en-us/library/live/hh243646.aspx`
			Delimiter:`"%20" (A url encoded space)`

	###IV-4: Retrieve URIs
		Facebook : `https://graph.facebook.com/me/`
		Google   : `https://www.googleapis.com/oauth2/v1/tokeninfo`
		Microsoft: `https://apis.live.net/v5.0/me/`
			These specific urls are available as static members of the API_Service class via API_SERVICE::RETRIEVE_URI_FACEBOOK, API_SERVICE::RETRIEVE_URI_GOOGLE, API_SERVICE::RETRIEVE_URI_MICROSOFT, and API_SERVICE::RETRIEVE_URI_GITHUB.

	###IV-5: Management Console URIs
		Google   : `https://accounts.google.com/b/0/IssuedAuthSubTokens`
		Facebook : `"https://www.facebook.com/settings?tab=applications`
		Microsoft: `https://profile.live.com/ (Click "Manage" under "Connected To")`

##Part V: DEMOS
	There are two demos: login.php and login_multi.php.
	Hopefully, setup is self explanatory, but just in case, in order to get them to work properly, you need to do a few things:
		A) Ensure that each login and request file includes or requires AuthenticAccess.php. Right now, they are set up to improt it from a sister directory entitled "bin".
		B) Create applications on Facebook, Google, and Microsoft as described in the above documentation and put their client ids in info.php
		C) Place request.php and request_multi.php on the web (it won't work unless they are located at public factin urls)
		D) Set "REDIRECT_URI" to point to request.php and request_multi.php within login.php and login_multi.php respectively

