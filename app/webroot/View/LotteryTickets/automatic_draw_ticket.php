<?php
/******************************************
* @Created on Jan 16, 2014.
* @Package: Sportsbook.com
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/
?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="Admin panel" name="description">
		<title>1960sportsbook.com: The Nigeria sports premier bookmaker and sports betting website</title>
	</head>
<body>
	<?php	
	function function_lottery_draw($API_Endpoint){
		
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		//getting response from server
		$response = curl_exec($ch);
		if (curl_errno($ch)){
			// moving to display page to display curl errors
			$_SESSION['curl_error_no'] = curl_errno($ch) ;
			$_SESSION['curl_error_msg']= curl_error($ch);
			//Execute the Error handling module to display errors.
		} else {
			//closing the curl
			curl_close($ch);
		}
		return $response;
	}
    
	$API_Endpoint = "http://1960sportsbook.com/admin/LotteryTickets/autolotterydraw";
	$responseStr  = function_lottery_draw($API_Endpoint); 
	echo '<div><center>'.$responseStr.'</center></div>';
?>
</body>
</html>