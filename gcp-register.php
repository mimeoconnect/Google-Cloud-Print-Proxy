<?php 
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');	

// Name of Cloud Printer
$Printer_Name = "";

// Description of Cloud Printer
$Printer_Description = "";

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
$client->setUri('http://www.google.com/cloudprint/interface/register');

$client->setParameterPost('printer', $Printer_Name);
$client->setParameterPost('proxy', $Printer_Proxy);

//Pull Capabilities from PPD File
$Capabilities = file_get_contents($Printer_PPD);

$client->setParameterPost('capabilities', $Capabilities);
$client->setParameterPost('defaults', $Capabilities);
$client->setParameterPost('status', 'Online');
$client->setParameterPost('description', $Printer_Description);

$response = $client->request(Zend_Http_Client::POST);

//echo $response;

$PrinterResponse = json_decode($response->getBody());

//var_dump($PrinterResponse);

$Success = $PrinterResponse->success;
//echo "Success: " . $Success . "<br />";

// Printer Information
$Printer = $PrinterResponse->printers[0];

$Printer_ID = $Printer->id;
//echo "Printer ID: " . $Printer_ID . "<br />";

$Printer_Name = $Printer->name;
//echo "Printer Name: " . $Printer_Name . "<br />";	

$Printer_Description = $Printer->description;
//echo "Printer Description: " . $Printer_Description . "<br />";		

$Printer_Status = $Printer->status;
//echo "Printer Status: " . $Printer_Status . "<br />";	

$Printer_CreateTime = $Printer->createTime;
//echo "Printer CreateTime: " . $Printer_CreateTime . "<br />";	
?>