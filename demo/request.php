<script>if(document.URL.indexOf("#") !== -1)	window.location = document.URL.replace("#","?");</script>
<?php 
	require_once("../bin/AuthenticAccess.php");
	function f_primary_email($file_contents){
		$object = json_decode($file_contents);
		return $object->email;
	}
	$email="no email";
	if(isset($_REQUEST['access_token']))
	{
		$email = AuthenticAccess::Retrive_Data(AuthenticAccess::RETRIEVE_URI_FACEBOOK,$_REQUEST['access_token'],f_primary_email);
	}
	echo $email;
?>