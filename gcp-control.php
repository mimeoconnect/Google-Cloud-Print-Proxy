<?php 
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');	

// ID of the Print Job
$Job_ID = "";

// Status of the Print Job
$Job_Status = "";

// Gmail User Email
$G_Email = "";

// Gmail User Password
$G_Pass = "";

//Actually Register the Printer
 $client = Zend_Gdata_ClientLogin::getHttpClient($G_Email, $G_Pass, 'cloudprint');
 
// Get Token and Add Headers
$Client_Login_Token = $client->getClientLoginToken(); 

$client->setHeaders('Authorization','GoogleLogin auth='.$Client_Login_Token); 
$client->setHeaders('X-CloudPrint-Proxy','Mimeo'); 

//GCP Services - Register
$client->setUri('http://www.google.com/cloudprint/interface/control');

$client->setParameterPost('jobid', $Job_ID);
$client->setParameterPost('status', $Job_Status);

$response = $client->request(Zend_Http_Client::POST);

//echo $response;

$PrinterResponse = json_decode($response->getBody());

//var_dump($PrinterResponse);

$Success = $PrinterResponse->success;
//echo "Success: " . $Success . "<br />";

$Message = $PrinterResponse->message;
//echo "Message: " . $Message . "<br />";
?>