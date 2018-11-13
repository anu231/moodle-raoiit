<?php
function sendSMS(&$s_mobile, &$s_text)
{
	global $errors, $success;

	//initialize the request variable
	$request = "";
	//this is the key of our sms account
	$param["workingkey"] = "3693f2jl9yh7375b0o1i";//"174361n8xjqd2t60247";
	//this is the message that we want to send
	$param["message"] = stripslashes($s_text);
	//these are the recipients of the message
	$param["to"] = $s_mobile;
	//this is our sender
	$param["sender"] = "RAOIIT";//"BULKSMS";

	//traverse through each member of the param array
	foreach($param as $key=>$val){
		//we have to urlencode the values
		$request.= $key."=".urlencode($val);
		//append the ampersand (&) sign after each paramter/value pair
		$request.= "&";
	}
	//remove the final ampersand sign from the request
	$request = substr($request, 0, strlen($request)-1);

	//this is the url of the gateway's interface
	//$url = "http://promo.chandnas.com/promoservice/api/web2sms.php?$request";
	//$url = "http://alerts.prioritysms.com/api/web2sms.php?$request";
	$url = "http://alerts.prioritysms.com/api/web2sms.php";

	//echo $url;

	//initialize curl handle
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); //set the url
	curl_setopt($ch, CURLOPT_POST, count($param)); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
	$response = curl_exec($ch); //run the whole process and return the response
	curl_close($ch); //close the curl handle

	$responseary = explode("Invalid/DND Numbers:", $response);

	if (substr(trim($responseary[0]), 0, 12) == "Message GID=") // delivered successfully
	{
		$successary = explode(",", trim($responseary[0]));
		$success .= "SMS successfully delivered to " . sizeof($successary) . " mobile numbers.";
	}
	if (strlen(trim($responseary[1])) != 0) // errors
	{
		$errors .= "Invalid/DND Numbers: " . trim($responseary[1]);
	}
}
?>