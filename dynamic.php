<?php

// define('MERCHANT_ID', 'merchant.uk.co.tm8.vsf2');
define('MERCHANT_ID', '234766');
define('MERCHANT_SECRET', 'nkmAfbtmB1R3D');
define('GATEWAY_HOSTNAME', 'gateway.cardstream.com');

// Create the Apple Pay merchant validation request.
$merchantValidationRequest = [
	'merchantID' => MERCHANT_ID, // Gateway MerchantID the domain being verified has been added too.
	'process' => 'applepay.validateMerchant', // The action wanted.
	'validationURL' => $_GET['validationURL'], // The validation URL passed from Apple to the onvalidatemerchant function.
	'displayName' => 'Apple Pay Demo',
	'domainName' => $_SERVER['HTTP_HOST'], // Domain being verified where the DVF is located.
];
 
// Sign the request.
$merchantValidationRequest['signature'] = signRequest($merchantValidationRequest, MERCHANT_SECRET);

// Using Curl send the validation request to the Gateway. The end point is /hosted/
$ch = curl_init('https://' . GATEWAY_HOSTNAME . '/hosted/');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);  
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($merchantValidationRequest));  
curl_setopt($ch, CURLOPT_HEADER, false);  
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
echo  curl_exec($ch);
curl_close($ch);

 
exit();


// Function used to sign a Gateway request.
function signRequest(array $data, string $key) : string 
{  
	// Sort by field name  
	ksort($data);  
	// Create the URL encoded signature string  
	$ret = http_build_query($data, '', '&');  
	// Normalise all line endings (CRNL|NLCR|NL|CR) to just NL (%0A)  
	$ret = str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);
	// Hash the signature string and the key together  
	return hash('SHA512', $ret . $key);  
}  

?>
