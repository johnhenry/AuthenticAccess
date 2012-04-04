 <html>
    <head>
      <title>My Facebook Login Page</title>
    </head>
    <body>
		<?php
			require_once("../bin/AuthenticAccess.php");
			require_once("info.php");

			define("API_REDIRECT_URI", urlencode("http://www.youcanthandlethis.org/demo/request.php"));
			$service = new AuthenticAccess("facebook" ,AuthenticAccess::AUTH_URI_FACEBOOK ,FACEBOOK_CLIENT_ID ,FACEBOOK_SCOPE ,API_REDIRECT_URI);
		?>
			<a href="<?php echo($service->get_link()); ?>">Login With <?php echo(ucfirst($service->state)); ?></a>
    </body>
 </html>