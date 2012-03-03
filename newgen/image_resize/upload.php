<?php
	include("imagelib/imageresize.php");
	$tWidth=150;
	$tHeight=150;
	$bWidth= 500;
	$bHeight=500;
	$imageDir ="uploads/";
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$tmpFile    =$_FILES['snap']['tmp_name'];
		$imageFile  =time()."_".$_FILES['snap']['name'];
		
		$imgDetail=getimagesize($tmpFile);
		$imgWidth=$imgDetail[0];
		$imgHeight=$imgDetail[1];
		$divFactor=divFactor($tWidth,$tHeight,$imgWidth,$imgHeight);
		if($divFactor>0)
		{
			$tWidth=round(($imgWidth/$divFactor),2);
			$tHeight=round(($imgHeight/$divFactor),2);
			$bWidth=getBigWidth($bWidth,$tWidth,$imgWidth);
		}
		
		
		move_uploaded_file($tmpFile,$imageDir.$imageFile) or $error = "Not A ";
		makeimage($imageDir.$imageFile, "thumb_".$imageFile ,$imageDir,$tWidth,$tHeight);
     	makeimage($imageDir.$imageFile, "big_".$imageFile ,$imageDir,$bWidth,$bHeight);
	
	}	
?>
<form name="uploadimage" id="uploadimage"  action="" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Upload :</td>
    <td><input type="file" name="snap" id="snap" ></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="submit" id="submit" name="submit"></td>
  </tr>
</table>
<div style="display:table-cell">

<?php 
$i=0;
if ($handle = opendir('uploads')) {
        /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) 
	{
        if(preg_match('/thumb_/',$file,$array))
			if(count($array)>0)
			{
				echo "
				<div style='width:150px; height:150px; border:1px solid red; padding:5px; margin:5px; float:left; text-align:center; vetical-align:middle; display:table;'>
				<div style='display:table-cell; vertical-align:middle'>
				<img src='uploads/".$file."'></div></div>";
				$i++;
			}	
	}

   

    closedir($handle);
}
?>




<table width="100%" border="1" cellspacing="0" cellpadding="2">
  <tr>

<?php 
$i=0;
if ($handle = opendir('uploads')) {
        /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if(preg_match('/thumb_/',$file,$array))
		if(count($array)>0)
		{
			echo "<td align='center' valign='middle'><img src='uploads/".$file."'></td>";
			$i++;
		}	
		if($i%4==0)
		{
			echo "</tr><tr>";
		}
    }

   

    closedir($handle);
}
?>

</table>




