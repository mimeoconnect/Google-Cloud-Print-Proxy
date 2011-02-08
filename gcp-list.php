<?php 
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');	

// Path to Printer Capabilities in PPD Format
$Printer_Proxy = "";

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
$client->setUri('http://www.google.com/cloudprint/interface/list');

$client->setParameterPost('proxy', $Printer_Proxy);

$response = $client->request(Zend_Http_Client::POST);

$PrinterResponse = json_decode($response->getBody());

$Success = $PrinterResponse->success;

// Printer Information
$Printers = $PrinterResponse->printers;

foreach($Printers as $Printer) 
	{
	// Printer ID
	$Printer_ID = $Printer->id;

	//Printer Name
	$Printer_Name = $Printer->name;
	echo $Printer_Name;	
	
	// Printer Proxy
	$Printer_Proxy = $Printer->proxy;
	echo $Printer_Proxy;		
	}
?>