<?php

/**

 * Template Name: For Sale

 *

 */

?>

<?php

//mysql_connect('localhost' ,'root','') or die(' host not connect');

//$link = mysql_select_db('wordpress') or die('database not connected');



?>

<html>

<head>

<script type="text/javascript">

        var GB_ROOT_DIR = "./greybox/";

    </script>



    <script type="text/javascript" src="greybox/AJS.js"></script>

    <script type="text/javascript" src="greybox/AJS_fx.js"></script>

    <script type="text/javascript" src="greybox/gb_scripts.js"></script>

	<link href="greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />



<link rel="stylesheet" href="css/inside_style2.css" type="text/css" />  

 <link rel="stylesheet" type="text/css" href="css/jquery.ad-gallery.css">

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>

  <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.ad-gallery.js"></script> 

<script src="js-1.js" type="text/javascript">

</script>

<script type="text/javascript">

  $(function() {

    $('img.image1').data('ad-desc', 'Whoa! This description is set through elm.data("ad-desc") instead of using the longdesc attribute.<br>And it contains <strong>H</strong>ow <strong>T</strong>o <strong>M</strong>eet <strong>L</strong>adies... <em>What?</em> That aint what HTML stands for? Man...');

    $('img.image1').data('ad-title', 'Title through $.data');

    $('img.image4').data('ad-desc', 'This image is wider than the wrapper, so it has been scaled down');

    $('img.image5').data('ad-desc', 'This image is higher than the wrapper, so it has been scaled down');

    var galleries = $('.ad-gallery').adGallery();

    $('#switch-effect').change(

      function() {

        galleries[0].settings.effect = $(this).val();

        return false;

      }

    );

    $('#toggle-slideshow').click(

      function() {

        galleries[0].slideshow.toggle();

        return false;

      }

    );

    $('#toggle-description').click(

      function() {

        if(!galleries[0].settings.description_wrapper) {

          galleries[0].settings.description_wrapper = $('#descriptions');

        } else {

          galleries[0].settings.description_wrapper = false;

        }

        return false;

      }

    );

  }); 

 

  </script>



<!--<script type="text/javascript">



$(document).ready(function(){

			

	$("#imint a").mouseover(function(){

	var value = $("div.ad-image-wrapper div.ad-image img").attr("src");

	//alert(value);

	var p = "test.php?name="+value;

	//$("#imagesrc").attr("value", value);

    $("#imint a").attr("href", );

	 });

	/*var items = $("ul.ad-thumb-list").children();

	var size = items.size();

	//alert(size); 

	$(items).each(function( item){

		$("." + this).click(function(){				   

						   alert(this);

									 });

						   });*/

  /*$("li a img").click(function(){

	var name = $("li a img").attr("src");						  

    alert(name);

  });*/

});

alert(value);

</script> -->

</head>





<body style="text-align: center;">



<div id="main" style="text-align:center" >



  <div id="main_content">

	   	   <div id="logo_text">

         <a href="index.php"><span class="header_letter">R</span>OGER <span class="header_letter">W</span>ILLIAMS </a>
         <iframe src="http://www.facebook.com/plugins/like.php?app_id=248833135143154&amp;href=http%3A%2F%2Fwww.facebook.com%2FRWArt&amp;send=false&amp;layout=button_count&amp;width=48&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:48px; height:21px;" allowTransparency="true"></iframe>

       </div>

	   

	  <div>

	<ul id="nav" style="text-align:left;">

		<?php 

 		 $resultArray = mysql_query("SELECT * FROM wp_posts WHERE post_parent =0 AND menu_order >0 AND post_type='page' ORDER BY menu_order");

		  while($row = mysql_fetch_array($resultArray)){

		  		//print_r($row);

			$subMenuQuery = "SELECT * FROM wp_posts WHERE post_parent ={$row['ID']} AND menu_order >0 AND post_type='page'";

			$subMenuArray = mysql_query($subMenuQuery);

			

		  ?>	  

		<li ><a href="<?php echo get_bloginfo('url'); ?>?page_id=<?php echo ($row['ID']);?>&main_id=<?php echo ($row['ID']);?>"><?php echo ($row['post_title']);?></a>

		<?php 

		if(mysql_num_rows($subMenuArray))

		{

		?>

			<ul>

				 <?php 

					while($row1 = mysql_fetch_array($subMenuArray)){

				 ?>

				 <li><a href="<?php echo get_bloginfo('url'); ?>?page_id=<?php echo ($row1['ID']);?>&main_id=<?php echo ($row['ID']);?>"><?php echo ($row1['post_title']);?></a>

				 </li>

				 <?php }?>

			 </ul>

	 	<?php

	 	}

	   ?>

		</li>

	<?php

		}

	?>

				<style>

 #searchBox{

	background-color : #999999;
	background-repeat: repeat;
	border:none;
	background-image:url('images/search.png');

	background-repeat:no-repeat;

	padding-right:15px;

	padding-top:2px;

	padding-bottom:0px;

	background-position:right;

	font-family: Helvetica_Neue_LT-65Medium;

    font-size: 13px;

    font-weight: normal;

	margin: 1px;

	color: #000000;

	}

	</style>

	<script>



function f(){

		document.getElementById('searchBox').style.width="100px";

}



function f2(){

	if(document.getElementById('searchBox').value == '')

		//document.getElementById('searchBox').style.display="none";

		document.getElementById('searchBox').style.width="2px";

	

}



function displaySearch(){

    if(document.getElementById('searchBox').style.display =='none')

		document.getElementById('searchBox').style.display="inline";

	else

		document.getElementById('searchBox').style.display="none";

}

function imgpath(){

var imgpath = document.getElementById('imagesrc').value;

alert(imgpath);

}

</script>
<!--[if IE 8]>
<style type="text/css">
#searchform a{padding-left:1px;padding-right:1px;padding-bottom:1px !important;padding-top:3px;}
</style>
<![endif]-->
<style type="text/css">
#searchform a{padding-left:1px;padding-right:1px;padding-bottom:3px;padding-top:3px;}
</style>


		<li style="margin:0px;padding:0px;">

		<form id="searchform" method="get" action="">
		<a href="#">
			<input type=text id="searchBox" name="s" style="width:2px;display:inline"  onblur="f2();" onClick="f();">

		</a>

		</form>



		</li>

	</ul>

   </div>

  	  <div id="clear"></div> 

	 </div>





<div id="large_image" >

<?php $siteurl = get_bloginfo("siteurl");?>

<div>

 <div id="container" style="margin:0 0 0 5px;">

       <div id="gallery" class="ad-gallery" align="center">

      <div class="ad-image-wrapper">      </div>

      <div class="ad-controls">

      </div>

      <div class="ad-nav">

        <div class="ad-thumbs">

          <ul class="ad-thumb-list">

		  <?php 

		  $i=0;

		  $defaultImg = "";
		  $defaultImgName = "";
		  $defaultImgLoc = "";
		  $defaultImgDim = "";
		  $defaultID = "";
		  

		  $resultArray = mysql_query("SELECT * FROM wp_gallery_slides WHERE uselink='Y'  order by created,id");

		  while($row = mysql_fetch_array($resultArray)){

		  if($i==0)

		  {

			
		  	$defaultImg =$siteurl."/wp-content/uploads/slideshow-gallery/".$row['image'];
			$defaultImgName = $row['title'];
			$defaultID = $row['id'];
			$defaultImgLoc  = $row['location'];
			if($row['image_width']!="" && $row['image_height']!="")
			{
				$defaultImgDim  = $row['image_width']." * ".$row['image_height'];
			}		
		  }
		  
		  $imgName = $row['title'];
		  $loc  = $row['location'];
			if($row['image_width']!="" && $row['image_height']!="")
			{
				$imgDim  = $row['image_width']." * ".$row['image_height'];
			}

		  $i=1;
			$ID = $row["id"];
		  	//print_r($row);

		  ?>

            <li>

              <a href="<?php echo $siteurl;?>/wp-content/uploads/slideshow-gallery/<?php echo $row['image'];?>">

                <img src="<?php echo $siteurl;?>/wp-content/uploads/slideshow-gallery/<?php echo $row['image'];?>" imgtitle="<?php echo $imgName;?>"  imgDim="<?php echo $imgDim;?>"  loc="<?php echo $loc;?>" imgID="<?php echo $ID;?>" class="image0" width="80" height="50" onClick="setThumbnail(this)">              </a>      

		   </li>

			<?php 

			}

			?>	

          </ul>

        </div>

      </div>

    </div>

 </div>

</div>

</div>

<script language="javascript">

function setThumbnail(obj)

{

	jQuery("#imgName").val(obj.src);
	jQuery("#imageID").val(jQuery(obj).attr("imgID"));
	jQuery("#image-name").text(jQuery(obj).attr("imgtitle"));
	jQuery("#image-size").text(jQuery(obj).attr("imgDim"));
	jQuery("#image-location").html(""+jQuery(obj).attr("loc"));
	var defaultImgName=jQuery(obj).attr("imgtitle");
	jQuery("#defaultImgName").val(defaultImgName);

}

function getImageTitle()
{

	return jQuery("#defaultImgName").val();

}

function getThumbnail()
{

	return jQuery("#imgName").val();

}
function getImageID()
{

	return jQuery("#imageID").val();

}

function setHref()
{
	//$('#im_interested').attr("href","test.php?image="+getThumbnail());
	GB_showCenter('I am Interested', "<?php echo $siteurl;?>/test.php?image="+getThumbnail()+"&imageTitle="+getImageTitle()+"&imageID="+getImageID());
}

</script>



<div style="width:610px; height:20px; margin:0 auto; padding:4px 0;">

<!--<div style="width:320px; height:20px; float:left">24X36</div>-->
<div class="image-size"><div  id='image-size'><?php echo $defaultImgDim?></div><div id="image-location"><?php if($defaultImgLoc!="") { echo "".$defaultImgLoc; }?></div></div>
<div class="image-title" id="image-name"><?php echo $defaultImgName?></div>
<div id="imint" style="width:97px; height:20px; float: right">

<input type="hidden" name="imgName" id="imgName" value="<?php echo $defaultImg;?>">
<input type="hidden" name="imageID" id="imageID" value="<?php echo $defaultID;?>">
<input type="hidden" name="defaultImgName" id="defaultImgName" value="<?php echo $defaultImgName;?>">

<a style="cursor:pointer;" onClick="setHref()" id="im_interested" title="I am Interested" rel="gb_page_center[620, 400]" >I am Interested</a>

</div>

</div>



<div style="width:610px; height:30px; margin:0 auto;">
<div style="float:right;">

		        <!-- AddThis Button BEGIN -->

<div class="addthis_toolbox addthis_default_style">
<style type="text/css">
#main_content iframe{width:46px !important;}
</style>
<style type="text/css">
.addthis_button_facebook_like iframe{width:50px !important;}
</style>
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>

<!-- AddThis Button BEGIN -->
<a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4e3454b268ed063a" class="addthis_button_compact">Share</a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e3454b268ed063a"></script>
<!-- AddThis Button END -->

</div>

<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>

<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d66e5e269f83954"></script>

<!-- AddThis Button END -->





</div>



</div>





<div id="footer" >



<div id="footer-1" >

	   	 

         <div class="footer_logo_text"><span class="footer_letter">R</span>OGER <span class="footer_letter">W</span>ILLIAMS

         <span class="footer_letter">F</span>INE <span class="footer_letter">A</span>RT 

       </div>

	    

        <div class="footer_text" >Copyright 2011</div>

        

        </div>

        

      

                       

        </div>







</div>

</body>



</html>



