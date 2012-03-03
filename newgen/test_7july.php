<?php
if(isset($_POST['submit'])){
	
	$m = "";
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$from = $_POST['email'];
	$phone = $_POST['phone'];
	$mes = $_POST['message'];
	$to = "mevikasg@gmail.com";
	//$to = "imroz055@gmail.com";
	$subject = "For Sale";
	$message = "<b>"."First Name: "."<b/>".$fname."<br \>";
	$message .= "<b>"."Last Name: "."<b/>".$lname."<br \>";
	$message .= "<b>"."Contact Number: "."<b/>".$phone."<br \>";
	$message .= "<b>"."Message: "."<b/>".$mes;
	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= "From: $from" . "\r\n";
	$send = mail($to, $subject, $message, $headers);
	if($send){
	$m = "Your request has been sent.";	
	}
	else{
	$m = "Not sent.";	
	}
	
	/* for sender */
	//$from1 = "imroz055@gmail.com";
	/*$from1 = "contactus@teamdecode.com";
	$to1 = $_POST['email'];
	$subject1 = "Thank you for contacting Teamdecode";
	$message1 = "Hi,"."<br \>";
	$message1 .= "Thank you for contacting Teamdecode. We are in receipt of your request and we will revert in 3-5 working days. In case you want to get in touch immediately, please call the following persons:"."<br \>";
	$message1 .= "<ul><li>"."G. Nagraj - "."+91 9910027922"."</li>";
	$message1 .= "<li>"."Shveta - "."+91 9910241589"."</li></ul>";
	$message1 .= "Regards,"."<br />";
	$message1 .= "Teamdecode";
	
	$headers1 = "MIME-Version: 1.0" . "\r\n";
	$headers1 .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers1 .= "From: $from1" . "\r\n";
	
	//$headers = "From:" . $from;  
	
	mail($to1, $subject1, $message1, $headers1);
		if($send){
		?>
		<script language="javascript">alert("Mail sent.");</script>
		<?php
		}
		else{
		?>
		<script llanguage="javascript">alert("Mail not sent.");</script>
		<?php
		}*/
	
	
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta charset="UTF-8" />
<title>Contact | ROGER WILLIAMS</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="http://gch.co.in/wordpress/wp-content/themes/genetic/style.css" />
<link rel="stylesheet" href="css/inside_style.css"  type="text/css" /> 

<script>
/*$(document).ready(function(){
  $(".image0").click(function(){
	var name = $(".image0").attr("src");						  
    alert(name);
  });
});
*/
</script> 
<script language="javascript" type="text/javascript">
function validateEmail(email){
       var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
       return emailPattern.test(email);
}



function validarr(){
        var fname = document.getElementById('fname').value;
        var lname = document.getElementById('lname').value;
		var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;
		var message = document.getElementById('message').value;
        
		var re10digit=/^\d{10}$/; //regular expression defining a 10 digit number
       
        if(fname == ''){
        alert('Please fill your  first name.');
		document.getElementById('fname').focus();
        return false;
        }  
		
		if(lname == ''){
        alert('Please fill your  last name.');
		document.getElementById('lname').focus();
        return false;
        }
		
		if(email == ''){
        alert('Please fill your email id.');
		document.getElementById('email').focus();   
        return false;
        }
		
		if(!validateEmail(email)){
        alert('Please fill your correct email id.');
		document.getElementById('email').select(); 
        document.getElementById('email').focus();   
        return false;
        }
		
		if( phone == ''){
        alert('Please fill your contact number.');
		document.getElementById('phone').focus();
        return false;
        }
		
		 if(!(/^[0-9]{8,15}$/.test(phone))){
        alert('Please fill your correct contact number.');
		document.getElementById('phone').select();
        document.getElementById('phone').focus();
        return false;
        }
		
		if( message == ''){
        alert('Please fill your message.');
		document.getElementById('message').focus();
        return false;
        }
		
       
              
   return true;
}
</script>
</head>

 <body style="background-color:#FFFFFF!important; background-image:none!important;">
<div class="body"  id="featured">

<div id="art_sales">
<form method="post" action="" name="form1" onSubmit="return validarr();">
		   <table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
			 <td style="padding:8px; font-size:1.2em;" width="100%" colspan="3" bgcolor="#FF0000"><?php if($m != ""){ print $m;} ?></td>
			 </tr>
            <tr>
			 <td style="padding:8px; font-size:1.2em; color:#000;" width="35%">First Name</td>
			 <td style="padding:8px; font-size:1.2em; color:#000;" width="35%">Last Name</td>
             <td style="padding:8px; font-size:1.2em; color:#000;" width="30%">&nbsp;</td>
			</tr>
			<tr>
			 <td style="padding-left:8px;" width="35%"><input type="text" size="28" name="fname" id="fname" maxlength="200"></td>
			 <td style="padding-left:8px;" width="35%"><input type="text" size="28" name="lname" id="lname" maxlength="200"></td>
             <td style="padding:8px; font-size:1.2em;" width="30%">&nbsp;</td>
			</tr>
			<tr>
			 <td style="padding:8px; font-size:1.2em; color:#000;" width="35%">Email Address</td>
			 <td style="padding:8px; font-size:1.2em; color:#000;" width="35%">Phone Number</td>
             <td style="padding:8px; font-size:1.2em;" width="30%">&nbsp;</td>
			</tr>
			<tr>
			 <td style="padding-left:8px;" width="35%"><input type="text" size="28" name="email" id="email"  maxlength="200"></td>
			 <td style="padding-left:8px;" width="35%"><input type="text" size="28" name="phone" id="phone" maxlength="15"></td>
             <td style="padding:8px; font-size:1.2em;" width="30%">&nbsp;</td>
			</tr>
			<tr>
			 <td style="padding:8px; font-size:1.2em; color:#000;" colspan="2" width="70%">Message</td>
             <td style="padding:8px; font-size:1.2em;" width="30%">&nbsp;</td>
			</tr>
			<tr>
			 <td colspan="2" style="padding-left:8px;" colspan="2" width="70%"><textarea cols="64" rows="5" name="message" id="message"></textarea></td>
			</tr>
            <tr>
            <td colspan="2" style="padding:8px; font-size:1.2em;" width="30%"><input type="submit" value="SUBMIT" name="submit" ></td>
            </tr>
			
			</table>
            </form>
		  </div>		
	   
	    </div>
	</body>