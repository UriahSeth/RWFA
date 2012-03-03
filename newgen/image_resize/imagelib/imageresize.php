<?php

/*
	Version 1.0 Created by: Ryan Stemkoski
	Questions or comments: ryan@ipowerplant.com
	Visit us on the web at: http://www.ipowerplant.com
	Purpose:  This script can be used to resize one or more images.  It will save the file to a directory and output the path to that directory which you 
			  can display or write to a databse. 
	
	TO USE, SET: 
		$filename = image to be resized
		$newfilename = added to filename to for each use to keep from overwriting images created example thumbnail_$filename is how it will be saved.
		$path = where the image should be stored and accessed. 
		$newwidth = resized width could be larger or smaller
		$newheight = resized height could be larger or smaller
		
	SAMPLE OF FUNCTION: makeimage('image.jpg','fullimage_','imgs/',250,250)
	
	Include the file containing the function in your document and simply call the function with the correct parameters and your image will be resized.
	
*/
 
//IMAGE RESIZE FUNCTION FOLLOW ABOVE DIRECTIONS

function makeimage($filename,$newfilename,$path,$newwidth,$newheight=0) {

	//SEARCHES IMAGE NAME STRING TO SELECT EXTENSION (EVERYTHING AFTER . )
	$image_type = strstr(str_replace("./","",str_replace("../","",$filename)), '.');
	$image_quality = 100;
	//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
		switch(strtolower($image_type)) 
		{
			case '.jpg':
				$source = imagecreatefromjpeg($filename);
				break;
			case '.png':
				$source = imagecreatefrompng($filename);
				break;
			case '.gif':
				$source = imagecreatefromgif($filename);
				$image_quality = 90;
				break;
			default:
				echo("Error Invalid Image Type");
				die;
				break;
			}
	
	//CREATES THE NAME OF THE SAVED FILE
	$file = $newfilename ;
	
	//CREATES THE PATH TO THE SAVED FILE
	$fullpath = $path . $file;

	//FINDS SIZE OF THE OLD FILE
	list($width, $height) = getimagesize($filename);

	if ($newheight==0)
	{
		$newheight=$height/($width/$newwidth);
	}
/*	if($newwidth < $width)
	{
		$newwidth = $width;
	}*/

	//CREATES IMAGE WITH NEW SIZES
	$thumb = imagecreatetruecolor($newwidth, $newheight);

	//RESIZES OLD IMAGE TO NEW SIZES
	//imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	//SAVES IMAGE AND SETS QUALITY || NUMERICAL VALUE = QUALITY ON SCALE OF 1-100
	switch(strtolower($image_type)) 
		{

			case '.png':
				imagepng($thumb, $fullpath,$image_quality);
				break;
			case '.gif':
				imagegif($thumb, $fullpath,$image_quality);
				break;
			default:
				imagejpeg($thumb, $fullpath,$image_quality);
				
			}
	

	//CREATING FILENAME TO WRITE TO DATABSE
	$filepath = $fullpath;
	
	//RETURNS FULL FILEPATH OF IMAGE ENDS FUNCTION
	return $filepath;

}


//$filename,$newfilename,$path,$newwidth,$newheight=0
function resize($img, $newfilename,$path , $w, $h=0 ) {
 
 //Check if GD extension is loaded
 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
  trigger_error("GD is not loaded", E_USER_WARNING);
  return false;
 }
 
 //Get Image size info
 $imgInfo = getimagesize($img);
 switch ($imgInfo[2]) {
  case 1: $im = imagecreatefromgif($img); break;
  case 2: $im = imagecreatefromjpeg($img);  break;
  case 3: $im = imagecreatefrompng($img); break;
  default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
 }
 
 //If image dimension is smaller, do not resize
 if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
  $nHeight = $imgInfo[1];
  $nWidth = $imgInfo[0];
 }else{
                //yeah, resize it, but keep it proportional
  if ($w/$imgInfo[0] > $h/$imgInfo[1]) {
   $nWidth = $w;
   $nHeight = $imgInfo[1]*($w/$imgInfo[0]);
  }else{
   $nWidth = $imgInfo[0]*($h/$imgInfo[1]);
   $nHeight = $h;
  }
 }
 $nWidth = round($nWidth);
 $nHeight = round($nHeight);
 
 $newImg = imagecreatetruecolor($nWidth, $nHeight);
 
 /* Check if this image is PNG or GIF, then set if Transparent*/  
 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
  imagealphablending($newImg, false);
  imagesavealpha($newImg,true);
  $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
  imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
 }
 imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
 
 $newfilename=$path.$newfilename;
 //Generate the file, and rename it to $newfilename
 switch ($imgInfo[2]) {
  case 1: imagegif($newImg,$newfilename); break;
  case 2: imagejpeg($newImg,$newfilename);  break;
  case 3: imagepng($newImg,$newfilename); break;
  default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
 }
   
   return $newfilename;
}

function divFactor($tWidth,$tHeight,$imgWidth,$imgHeight)
{
	$wFactor=round($imgWidth/$tWidth,2);
	$hFactor=round($imgHeight/$tHeight,2);
	if($wFactor>=$hFactor)
	return $wFactor;
	else 
	return $hFactor;
}

function getBigWidth($bWidth,$tWidth,$imgWidth)
{
  
 if($bWidth>$imgWidth)
 {

 $bWidth=$imgWidth;
 }
return $bWidth;
}


?> 
