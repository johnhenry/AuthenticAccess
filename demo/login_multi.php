 <html>
    <head>
      <title>My Facebook Login Page</title>
    </head>
    <body>
		<?php
			require_once("../bin/AuthenticAccess.php");
			require_once("info.php");

			define("API_REDIRECT_URI", urlencode("http://www.youcanthandlethis.org/demo/request_multi.php"));
			$services = Array();
			$services[] = new AuthenticAccess("facebook" ,AuthenticAccess::AUTH_URI_FACEBOOK ,FACEBOOK_CLIENT_ID ,FACEBOOK_SCOPE ,API_REDIRECT_URI);
			$services[] = new AuthenticAccess("google"   ,AuthenticAccess::AUTH_URI_GOOGLE   ,GOOGLE_CLIENT_ID   ,GOOGLE_SCOPE   ,API_REDIRECT_URI);
			$services[] = new AuthenticAccess("microsoft",AuthenticAccess::AUTH_URI_MICROSOFT,MICROSOFT_CLIENT_ID,MICROSOFT_SCOPE,API_REDIRECT_URI);
		?>
		Login With:
		<ul>
		<?php foreach($services as $service){?>
			<li><a href="<?php echo($service->get_link()); ?>"><?php echo(ucfirst($service->state)); ?></a></li>
		<?php } ?>
		</ul>
    </body>
 </html>