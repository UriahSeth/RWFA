<?php
require_once( dirname(__FILE__) . '/wp-load.php' );
$admin_email = get_option('admin_email'); 
if(isset($_POST))
{

	if(isset($_POST["action"]))
	{
		if($_POST["action"]=="art_sales_submit")
		{
			$email   = trim($_POST["email"]);
			$lname =(empty($_POST["lname"]))?"unknown":$_POST["lname"];
			$fname =(empty($_POST["fname"]))?"unknown":$_POST["fname"];		
			$phone   = trim($_POST["phone"]);
			$msg = trim($_POST["message"]);
			
			
			$pattern = '/^[a-z0-9]{1}([\w\d])+([-_.]\w+)*([a-z0-9])?@([a-z0-9])+\.([a-z]{2,4})$/i';
			preg_match($pattern, $email, $matches, PREG_OFFSET_CAPTURE);
			if(empty($email))
			{
				echo "EMAIL_EMPTY";
			}
			elseif(count($matches)==0)
			{
				echo "EMAIL_INVALID";
			}
			else
			{
				$to = $admin_email;
				$bcc = "mevikasg@gmail.com";
				//$to = "sureyn.uat@gmail.com";
				$subject = "Original Artwork";
				$message = "<b>"."First Name: "."<b/>".$fname."<br \>";
				$message .= "<b>"."Last Name: "."<b/>".$lname."<br \>";
				$message .= "<b>"."Contact Number: "."<b/>".$phone."<br \>";
				$message .= "<b>"."Message: "."<b/>".$msg."<br \>";;
			
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				$headers .= "From: $email" . "\r\n";
				$headers .= "Bcc: $bcc" . "\r\n";
				
				$send = mail($to, $subject, $message, $headers);
				if($send){
				$m = "Thanks, We'll get back to you soon!";	
				}
				else{
				$m = "Request can't be sent due to server error.";	
				}
				
				// for roger contact form
				if($_POST["sendto_zoho"]=="yes")
				{
				
						$workshop =(empty($_POST["workshop"]))?"False":"True";		
						$artshow =(empty($_POST["artshow"]))?"False":"True";		
						$newwork =(empty($_POST["newwork"]))?"False":"True";		
						
						
						
						if(isset($_POST["workshop"]) && (isset($_POST["artshow"]) || isset($_POST["newwork"])))
						{
							//$scope = "Potential Student and Client";
							$scope = "Potential Client;Potential Student";
						}
						elseif(isset($_POST["workshop"]))
						{
						   $scope = "Potential Student";
						}
						else
						{
						   $scope = "Potential Client";
						}
						
						
						$xmlString ='<Contacts><row no="1"><FL val="Last Name">'.$lname.'</FL>
						<FL val="First Name">'.$fname.'</FL>
						<FL val="Email">'.$email.'</FL>
						<FL val="Lead Source">My Web Site</FL>
						<FL val="Description">'.$scope.'</FL>
						<FL val="Contact Type">'.$scope.'</FL>			
						<FL val="Workshops">'.$workshop.'</FL>			
						<FL val="Invitations">'.$artshow.'</FL>			
						<FL val="New Work">'.$newwork.'</FL>			
						
						</row></Contacts>';
						
						$xmldata = "xmlData=$xmlString";
						
						//$ch = curl_init("https://crm.zoho.com/crm/private/xml/Contacts/insertRecords?ticket=2c23ed129710265ebca8e807927ee793&apikey=8tNxSLFzjT9BWsly1cwE6mEXGcZ3OXi7kQaMGWeIdlI$&newFormat=1");
						$ch = curl_init("https://crm.zoho.com/crm/private/xml/Contacts/insertRecords?ticket=c8adabf84a5eb78846472fb1bba2c35e&apikey=8tNxSLFzjT9BWsly1cwE6mEXGcZ3OXi7kQaMGWeIdlI$&newFormat=1");
						
						curl_setopt($ch, CURLOPT_PORT, 443);
						//curl_setopt($ch, CURLOPT_CERTINFO, false);
						//curl_setopt($ch, CURLOPT_HTTPHEADER, "text/html;charset=iso-8859-1");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1); // off in case of get records
						curl_setopt($ch, CURLOPT_HEADER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata); // off in case of get records
						$authResult   = curl_exec($ch);
						if(!curl_errno($ch))
						{
						 //$info = curl_getinfo($ch);
							//echo "Thanks, We'll get back to you soon!";
						}
						else
						{
						   //echo "Sorry! we cannot save your contact details due to server error. Try later or contact administrator.";
						}
					}	
				// roger contact form ends here
				
				
				
				
				
				echo $m;

			}
		}
	}
	else
	{
			$email= trim($_POST["email"]);
			
			$pattern = '/^[a-z0-9]{1,1}([\w\d])+([-_.]\w+)*[a-z0-9]@([a-z0-9])+\.([a-z]{2,4})$/i';
			preg_match($pattern, $email, $matches, PREG_OFFSET_CAPTURE);
			if(empty($email))
			{
				echo "EMAIL_EMPTY";
			}
			elseif(count($matches)==0)
			{
				echo "EMAIL_INVALID";
			}
			else
			{
						$lname =(empty($_POST["lname"]))?"unknown":$_POST["lname"];
						$fname =(empty($_POST["fname"]))?"unknown":$_POST["fname"];		
						$workshop =(empty($_POST["workshop"]))?"False":"True";		
						$artshow =(empty($_POST["artshow"]))?"False":"True";		
						$newwork =(empty($_POST["newwork"]))?"False":"True";		
						
						
						
						if(isset($_POST["workshop"]) && (isset($_POST["artshow"]) || isset($_POST["newwork"])))
						{
							//$scope = "Potential Student and Client";
							$scope = "Potential Client;Potential Student";
						}
						elseif(isset($_POST["workshop"]))
						{
						   $scope = "Potential Student";
						}
						else
						{
						   $scope = "Potential Client";
						}
						
						
						$xmlString ='<Contacts><row no="1"><FL val="Last Name">'.$lname.'</FL>
						<FL val="First Name">'.$fname.'</FL>
						<FL val="Email">'.$email.'</FL>
						<FL val="Lead Source">My Web Site</FL>						
						<FL val="Contact Type">'.$scope.'</FL>			
						<FL val="Workshops">'.$workshop.'</FL>			
						<FL val="Invitations">'.$artshow.'</FL>			
						<FL val="New Work">'.$newwork.'</FL>			
						
						</row></Contacts>';
						
						$xmldata = "xmlData=$xmlString";
						
						//$ch = curl_init("https://crm.zoho.com/crm/private/xml/Contacts/insertRecords?ticket=2c23ed129710265ebca8e807927ee793&apikey=8tNxSLFzjT9BWsly1cwE6mEXGcZ3OXi7kQaMGWeIdlI$&newFormat=1");
						$ch = curl_init("https://crm.zoho.com/crm/private/xml/Contacts/insertRecords?ticket=c8adabf84a5eb78846472fb1bba2c35e&apikey=8tNxSLFzjT9BWsly1cwE6mEXGcZ3OXi7kQaMGWeIdlI$&newFormat=1");
						
						curl_setopt($ch, CURLOPT_PORT, 443);
						//curl_setopt($ch, CURLOPT_CERTINFO, false);
						//curl_setopt($ch, CURLOPT_HTTPHEADER, "text/html;charset=iso-8859-1");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($ch, CURLOPT_POST, 1); // off in case of get records
						curl_setopt($ch, CURLOPT_HEADER, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata); // off in case of get records
						$authResult   = curl_exec($ch);
						if(!curl_errno($ch))
						{
						 //$info = curl_getinfo($ch);
							echo "Thanks, We'll get back to you soon!";
						}
						else
						{
						   echo "Sorry! we cannot save your contact details due to server error. Try later or contact administrator.";
						}
						/*
						ini_set("display_errors", true);
						error_reporting(E_ALL^E_NOTICE); 
						$authResult   = curl_exec($ch);
						$responseCode = curl_getinfo($ch);
						$error        = curl_error($ch);
						echo "curl error : ".$error;
						echo "<br>".$authResult;
						*/
			}
		}	
}
?>