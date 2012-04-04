<?php
class AuthenticAccess
{
	//Public Variables
	public $state        = "";
	public $api_url      = "";
	public $client_id    = "";
	public $scope        = "";
	public $redirect_uri = "";
	public $response_type= "";
	public $link         = "";
	/**
	*get_link - Returns the link to be used to begin data retrevial process.
	*/
	public function get_link(){
		return $this->link;
	}

	/**
	* Constructor - Creates API_Service Object
	*	@param		string $state			This will be returned to your redirect URI along with your access token. It's best use is if you have multiple services that direct to the same address and you need a way to differentiate between them.
	*	@param		string $api_url			This is the address that you will send the user to in order to access their data.
	*	@param		string $client_id		This is the application's Client ID.
	*	@param		string $scope			This defines the user data that your application is requesting. This string takes the form a a delimited list of scopes as defined by your applications service provider.
	*	@param		string $redirect_uri	This is the page to which the data will be delivered once the user authorizes the service provider to give your application the requested information.
	*	@param		string $response_type	While there are multiple request types that you can make, this workflow uses the "token" request type. You can actually leave this parameter out and it will automatically be set to "token".
	**/
	function __construct($state,$api_uri,$client_id,$scope,$redirect_uri,$response_type="token") {
		list($this->state,$this->api_uri,$this->client_id,$this->scope,$this->redirect_uri,$this->response_type)= 
		Array($state,$api_uri,$client_id,$scope,$redirect_uri,$response_type);

		$query_string = "?".implode("=%s&",explode(",",self::$Query_String))."=%s";
		$this->link = $this->api_uri.sprintf($query_string,$state,$client_id,$scope,$redirect_uri,$response_type);
	}
	private static $Query_String = "state,client_id,scope,redirect_uri,response_type";
	 /**
	* Retrive_Data - Retrieves Data  
	*	@param		string $retrive_uri			This is the url to which you send your access token and retrieve the requested data.
	*	@param		string $access_token		This is the access token sent by the service provider.
	*	@param		function $data_function		This is a funciton that you can define to process data retrieved from the OAuth provider. If the $data_function is omitted, AuthenticAccess::Retrieve_Data will return the raw data - usally formatted as JSON.
	*/
	public static function Retrive_Data($retrive_uri,$access_token,$data_function = null)
	{
		$result = file_get_contents($retrive_uri."?access_token=".$access_token);
		if(is_callable($data_function)){
			return $data_function($result);
		}
		return $result;
	}

	//Useful Constants
	const AUTH_URI_FACEBOOK = "https://www.facebook.com/dialog/oauth";
	const AUTH_URI_GOOGLE   = "https://accounts.google.com/o/oauth2/auth";
	const AUTH_URI_MICROSOFT= "https://oauth.live.com/authorize";

	const RETRIEVE_URI_FACEBOOK = "https://graph.facebook.com/me/";
	const RETRIEVE_URI_GOOGLE   = "https://www.googleapis.com/oauth2/v1/tokeninfo";
	const RETRIEVE_URI_MICROSOFT= "https://apis.live.net/v5.0/me/"; 
}
?>