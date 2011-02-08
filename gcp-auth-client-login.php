<?php 
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');	

// Gmail User Email
$G_Email = "";

// Gmail User Password
$G_Pass = "";
			
//Actually Register the Printer
$client = Zend_Gdata_ClientLogin::getHttpClient($G_Email, $G_Pass, 'cloudprint');
 
// Get the Token 
$Client_Login_Token = $client->getClientLoginToken(); 
?>
			