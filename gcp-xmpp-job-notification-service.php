<?php

include 'XMPPHP/XMPP.php';
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');	

// Path to Printer Capabilities in PPD Format
$Printer_Proxy = "[Printer Proxy ID]";

// Gmail User Email
$G_Email = "[User Email]";

// Gmail User Password
$G_Pass = "[User Password]";

//Actually Register the Printer
$client = Zend_Gdata_ClientLogin::getHttpClient($G_Email, $G_Pass, 'chromiumsync');
 
// Get Token and Add Headers
$Client_Login_Token = $client->getClientLoginToken(); 

echo "Client Login Token: " . $Client_Login_Token . "<br /><br />";

// Begin XMPP
$conn = new XMPPHP_XMPP('talk.google.com', 5222, $G_Email, $G_Pass, 'xmpphp', 'gmail.com', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_VERBOSE);
$conn->autoSubscribe();

$vcard_request = array();

//var_dump($conn); 

try {

    $conn->connect(); 
    
    while(!$conn->isDisconnected()) 
    	{
    	
    	$payloads = $conn->processUntil(array('message', 'session_start'));
    	
    	foreach($payloads as $event) 
    		{
    		
    		$pl = $event[1];
    		
    		switch($event[0]) 
    			{
    			
    			case 'message': 
    			
    				$Full_JID =  $conn->fulljid;
    				$Bare_JID = $conn->jid;    			
    			
    				echo "<hr />";
    				
	    			//echo  "Message from: " . $pl['from'] . "<br />";   		

	    			if($pl['from'] == 'cloudprint.google.com')
		    			{
		    			// We have received a push for this Print Proxy ID + User.   
		    			
		    			// Now we can /fetch print jobs for this Proxy, User, Printer ID
		    			
		    			echo "Print Job Notification Received for " . $Printer_Proxy . " / " . $Bare_JID . "<br />";
		    			}   				
	    				
    			break;
    			   
    			case 'session_start': 			

    				$Full_JID =  $conn->fulljid;
    				$Bare_JID = $conn->jid;
    				
				    echo "FULL JID: " . $conn->fulljid . "<br />";
				    echo "JID: " . $conn->jid . "<br />";    

				    echo "<hr />";
    			
					$Body = "<iq from='" . $Full_JID . "' to='" . $Bare_JID . "' type='set' id='1'><subscribe xmlns='google:push'><item channel='cloudprint.google.com/proxy/" . $Printer_Proxy . "' from='cloudprint.google.com'/></subscribe></iq>";				
				    
    				$conn->subscription($Body);   			
    				
    			break;
				
				
    		}
    	}
    }
} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}
