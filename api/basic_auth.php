<?php 
	
	function get_header( $headerKey )
	{
		
		$header = getallheaders();		
		$headerValue = "";		
		if ( array_key_exists($headerKey, $header) ) {
			$headerValue = $header[ $headerKey ];
		}
		return $headerValue;
	}
// check if phpversion have getallheaders function 
if(function_exists('getallheaders')){}
	else 
{
	function getallheaders() 
	{ 
	
		$headers = array(); 
	   foreach ($_SERVER as $name => $value) 
	   { 
		   if (substr($name, 0, 5) == 'HTTP_') 
		   { 
			   $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
			   $headers[$key] = $value;
			
			  
		   } 
	   } 
	   
	   return $headers; 
	}
}
$auth=0;
$authorization = get_header('Authorization');
// if ( $authorization=='Basic YWRtaW46cXRjdGVrQDEyMwx==' ) //Authorization: Bearer <token>
{
	$auth=1;
}


if ($auth!='1')
{

	echo json_encode(
          array('message' => 'basic authentication incorrect ! check header again !')
        );
	exit();
}

?>