<script>if(document.URL.indexOf("#") !== -1)	window.location = document.URL.replace("#","?");</script>
<?php 
	require_once("../bin/AuthenticAccess.php");

	function fg_primary_email($file_contents){
		$object = json_decode($file_contents);
		return $object->email;
	}
	function m_primary_email($file_contents){
		$object = json_decode($file_contents);
		return $object->emails->preferred;
	}
	function g_primary_email($file_contents){
		$object = xml_decode($file_contents);
		return $object->email;
	}

	$email="no email";
	if(isset($_REQUEST['access_token']))
	{
		$access_token = $_REQUEST['access_token'];
		if(isset($_REQUEST['state']))
		{
			switch($_REQUEST['state'])
			{
				case "facebook":
					$email = AuthenticAccess::Retrive_Data(AuthenticAccess::RETRIEVE_URI_FACEBOOK,$access_token,fg_primary_email);
					break;
				case "google":
					$email = AuthenticAccess::Retrive_Data(AuthenticAccess::RETRIEVE_URI_GOOGLE,$access_token,fg_primary_email);
					break;
				case "microsoft":
					$email = AuthenticAccess::Retrive_Data(AuthenticAccess::RETRIEVE_URI_MICROSOFT,$access_token,m_primary_email);
					break;
			}
		}
	}

	echo $_REQUEST['state'].": ".$email;
?>