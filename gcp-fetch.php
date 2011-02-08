<?php 
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');	

// ID of the Printer
$Printer_ID = "";

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
$client->setUri('http://www.google.com/cloudprint/interface/fetch');

$client->setParameterPost('printerid', $Printer_ID);

$JobResponse = json_decode($response->getBody());

$Success = $JobResponse->success;

if(isset($JobResponse->jobs))
	{
	$Jobs = $JobResponse->jobs;	

	foreach($Jobs as $Job) 
		{
		
		$Job_ID = $Job->id;
		$Job_Title = $Job->title;
		$File_URL = $Job->fileUrl;
		
		}
	}
?>