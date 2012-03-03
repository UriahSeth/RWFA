<?php
require_once( dirname(__FILE__) . '/wp-load.php' );
$admin_email = get_option('admin_email'); 
if(isset($_POST['submit'])){
	
	$m = "";
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$from = $_POST['email'];
	$phone = $_POST['phone'];
	$mes = $_POST['message'];
	$thumb_image = $_POST['thumb_image'];
	$defaultImgLoc = $_POST['defaultImgLoc'];
	$defaultImgDim = $_POST['defaultImgDim'];
	
	$to = $admin_email; //j4jagat@gmail.com
	$bcc = "mevikasg@gmail.com";
	//$to = "imroz055@gmail.com";
	$subject = "For Sale";
	$message = "<b>"."First Name: "."<b/>".$fname."<br \>";
	$message .= "<b>"."Last Name: "."<b/>".$lname."<br \>";
	$message .= "<b>"."Contact Number: "."<b/>".$phone."<br \>";
	$message .= "<b>"."Dimension: "."<b/>".$defaultImgDim."<br \>";
	$message .= "<b>"."Location: "."<b/>".$defaultImgLoc."<br \>";
	$message .= "<b>"."Message: "."<b/>".$mes."<br \>";
	$message .= "<img src='".$thumb_image."' width='100' style='2px solid black'><br \>";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= "From: $from" . "\r\n";
	$headers .= "Bcc: $bcc" . "\r\n";
	$send = mail($to, $subject, $message, $headers);
	if($send){
	$m = "Your request has been sent.";	
	}
	else{
	$m = "Request can't be sent due to server error.";	
	}
	
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<meta charset="UTF-8" />
<title>Contact | ROGER WILLIAMS</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="http://gch.co.in/wordpress/wp-content/themes/genetic/style.css" />
<link rel="stylesheet" href="<?php bloginfo("siteurl")?>/css/inside_style.css"  type="text/css" /> 

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
<div class="body"  id="featured_form">

<div id="art_sales_form">
<?php
if($m != "")
{ 
	print "<div style='width:100%; height:100%; padding:15px 5px 95% 5px; float:left'>$m</div>";
}
else
{
$defaultImgLoc = "";
$defaultImgDim = "";

$id = $_GET["imageID"];
$row = $wpdb->get_row("select * from wp_gallery_slides where id=$id");
$defaultImgLoc  = $row->location;
			if($row->image_width != "" && $row->image_height != "")
			{
				$defaultImgDim  = $row->image_width." * ".$row->image_height;
			}	
?>

<form method="post" action="" name="form1" onSubmit="return validarr();">
	
		   <table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
			 <td style="padding:8px; font-size:1.2em;" width="100%" colspan="3" bgcolor="#FF0000"></td>
			 </tr>
            <tr>
			 <td style="padding:8px; font-size:1.2em; color:#000;" width="35%">First Name</td>
			 <td style="padding:8px; font-size:1.2em; color:#000;" width="35%">Last Name</td>
             <td style="padding:8px; font-size:1.2em; color:#000;" width="30%" ></td>
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
			 <td colspan="2" style="padding-left:8px;" width="70%"><textarea cols="64" rows="5" name="message" id="message"></textarea></td>
			</tr>
            <tr>
            <td style="padding:8px; font-size:1.2em; color:#000;"  colspan="2" valign="top">
            <div style="float:left; margin-right:20px;"><img src="<?php echo $_GET["image"]; ?>" width="80" style="border:2px solid black;"></div>
            
            <table  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50"><strong>Name</strong></td>
                <td align="left">:</td>
                <td>&nbsp;&nbsp;<?php echo $_GET["imageTitle"];?></td>
              </tr>
              <tr>
                <td><strong>Size</strong></td>
                <td>:</td>
                <td>
				<?php //echo "Name : ".$_GET["imageTitle"]; 
						
					  echo $defaultImgDim;
				?>
            </td>
              </tr>
            </table>

            
            <input type="hidden" value="<?php echo $_GET["image"]; ?>" name="thumb_image" id="thumb_image">
            <input type="hidden" value="<?php echo $defaultImgDim?>" id="defaultImgDim" name="defaultImgDim">
            <input type="hidden" value="<?php echo $defaultImgLoc?>" id="defaultImgLoc" name="defaultImgLoc">
            </td>
            <td></td>
            <tr>
            	<td colspan="2" style="padding:8px; font-size:1.2em;" width="30%"><input type="submit" value="SUBMIT" name="submit" ></td>
            </tr>
			
			</table>
            
            </form>
         <?php
  }
  ?>        
     
		  </div>		
	   
	    </div>
	</body>