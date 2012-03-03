<?php
$errmsg="";
$lname = "";
$fname = "";
$email = "";
$phone = "";
$description = $_POST["description"];
$job_area = "";
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		echo "<pre>";
		print_r($_POST);
		if(empty($_POST["lname"]))
		{
			$errmsg="Plase enter last name.";
		}
		else
		{
			$lname = $_POST["lname"];
			$fname = $_POST["fname"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$description = $_POST["description"];
			if(isset($_POST["job_area"]))
			if(count($_POST["job_area"]) > 0)
			{
				$job_area = implode("|",$_POST["job_area"]);
			}
			$lname = $_POST["lname"];
			
			$xmlString ='<Contacts><row no="1"><FL val="Last Name">'.$lname.'</FL>
			<FL val="First Name">'.$fname.'</FL>
			<FL val="Email">'.$email.'</FL>
			<FL val="Home Phone">'.$phone.'</FL>
			<FL val="Description">'.$description.'</FL>
			<FL val="Campaign Source">'.$job_area.'</FL>			
			</row></Contacts>';
			
			$xmldata = "xmlData=$xmlString";
			
			$ch = curl_init("https://crm.zoho.com/crm/private/xml/Contacts/insertRecords?ticket=2c23ed129710265ebca8e807927ee793&apikey=8tNxSLFzjT9BWsly1cwE6mEXGcZ3OXi7kQaMGWeIdlI$&newFormat=1");
			curl_setopt($ch, CURLOPT_PORT, 443);
			curl_setopt($ch, CURLOPT_CERTINFO, false);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, "text/html;charset=iso-8859-1");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1); // off in case of get records
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata); // off in case of get records
			ini_set("display_errors", true);
			error_reporting(E_ALL^E_NOTICE); 
			$authResult   = curl_exec($ch);
			$responseCode = curl_getinfo($ch);
			$error        = curl_error($ch);
			echo "curl error : ".$error;
			echo "<br>".$authResult;
		}
	}
if($errmsg != "")
{
	echo "<span style='color:red;font-size:10px'>$errmsg</span>";
}
	
?>

<form name="insert" action="" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="21%">Last Name <font color="#FF0000">*</font></td>
    <td width="79%"><input name="lname" id="lname" value="" type="text"></td>
  </tr>
  <tr>
    <td>First Name</td>
    <td><input name="fname" id="fname" value="" type="text"></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><input name="email" id="email" value="" type="text"></td>
  </tr>
  <tr>
    <td>Phone</td>
    <td><input name="phone" id="phone" value="" type="text"></td>
  </tr>
  <tr>
    <td>Description</td>
    <td><textarea name="description" id="description"></textarea></td>
  </tr>
  <tr>
    <td colspan="2">
    <input type="checkbox" name="job_area[]" value="invitations">Workshops
    <input type="checkbox" name="job_area[]" value="invitations">New work
    <input type="checkbox" name="job_area[]" value="invitations">Invitations
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="Submit" id="submit" name="submit"></td>
  </tr>
</table>

</form>