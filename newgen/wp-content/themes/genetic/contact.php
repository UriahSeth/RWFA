<?php
/**
 * Template Name: Contact Us
 *
 */
?>
<?php get_header(); ?>
	  
	  
	   
	   
	   <!-- left contant-->
	   
	   <?php 
if(isset($_POST['submit'])){
   echo $fname = $_POST['fname'];
   echo	$lname = $_POST['lname'];
   echo $from = $_POST['email'];
	
}
?>
<h2>Become A Part Of The Artist's Community</h2>
<h3>Notification</h3>
<div id="left_notification">
<form name="form1" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tbody>
<tr>
<td style="padding: 8px; font-size: 1.2em;">Email</td>
<td><input size="28" type="text" name="email" id="email" maxlength="222" /></td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;"></td>
<td><input type="checkbox" id="workshop" name="workshop"  />I want to know about workshop</td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;"></td>
<td><input type="checkbox" id="artshor" name="artshow"  />I want to recieve art show invitations</td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;"></td>
<td><input type="checkbox" id="newwork" name="newwork"  />I want to see new work</td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;">First Name</td>
<td><input size="28" type="text" /></td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;">Last Name</td>
<td><input size="28" type="text" /></td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;"></td>
<td><input style="width: 199px;" type="button" value="Submit" name="submit" /></td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;" height="5"></td>
</tr>
</tbody>
</table>
</form>
</div>
<h3>Networks</h3>
<div id="right_networks">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tbody>
<tr>
<td style="padding: 8px; font-size: 1.2em;" width="19%"><img src="images/Galleries- Galleries_r24_c12_s1.gif" alt="" /></td>		
<td style="padding: 8px; font-size: 1.3em; font-weight: 500;" width="81%">Find Roger on Facebook</td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;" width="19%"><img src="images/Galleries- Galleries_r24_c16_s1.gif" alt="" /></td>
<td style="padding: 8px; font-size: 1.3em; font-weight: 500;" width="81%">Follow us on Twitter</td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;" width="19%"><img src="images/Galleries- Galleries_r24_c8_s1.gif" alt="" /></td>
<td style="padding: 8px; font-size: 1.3em; font-weight: 500;" width="81%">Find Roger on Linked In</td>
</tr>
</tbody>
</table>
</div>	
&nbsp;
<div style="font-size: 1.3em;">To Contact the artist about original artwork, please use this form to email the artist directly:</div>
<div id="art_sales">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tbody>
<tr>
<td style="padding: 8px; font-size: 1.2em;">First Name</td>
<td style="padding: 8px; font-size: 1.2em;">Last Name</td>
</tr>
<tr>
<td style="padding-left: 8px;"><input size="28" type="text" /></td>
<td style="padding-left: 8px;"><input size="28" type="text" /></td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;">Email Address</td>
<td style="padding: 8px; font-size: 1.2em;">Phone Number</td>
</tr>
<tr>
<td style="padding-left: 8px;"><input size="28" type="text" /></td>
<td style="padding-left: 8px;"><input size="28" type="text" /></td>
</tr>
<tr>
<td style="padding: 8px; font-size: 1.2em;" colspan="2">Message</td>
</tr>
<tr>
<td style="padding-left: 8px;" colspan="2"><textarea cols="57" rows="5"></textarea></td>
</tr>
<tr>
<td height="10"></td>
</tr>
</tbody>
</table>
</div>
&nbsp;
	   
       <!--end left contant-->
	   
	  <?php get_sidebar(); ?>
 <?php get_footer(); ?>
   
	