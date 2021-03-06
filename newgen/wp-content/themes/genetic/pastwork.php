<?php
/**
 * Template Name: Past Work
 *
 */

 $rootPath = dirname(dirname(dirname(dirname(__FILE__))));
 include("$rootPath/wp-includes/class/db.inc.php");
 include("$rootPath/wp-includes/class/page.inc.php");
 $db = new DB(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
 $db->open();
 
 
 if(isset($_GET["page_id"]))
 { 
 	$post_id=$_GET["page_id"];
 }
 if(isset($_GET["p"]))
 { 
 	$post_id=$_GET["p"];
 }
 if($post->ID > 0)
 {
 	$post_id=$post->ID;
 }
 
 $meta_keywords = "Roger";
 $meta_description = "Roger's paintings";
 $meta_title = "";
 $meta_values = get_post_meta($post_id, "ks_metadata");  
 if(!empty($meta_values[0]["keywords"]))
 $meta_keywords = $meta_values[0]["keywords"];
 
 if(!empty($meta_values[0]["description"]))
 $meta_description = $meta_values[0]["description"];
 
 if(!empty($meta_values[0]["title"]))
 $meta_title = $meta_values[0]["title"];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="keywords" content="<?php echo $meta_keywords;?>">
<meta name="description" content="<?php echo $meta_description;?>">
<title><?php echo $meta_title; ?></title>
<base href="<?php bloginfo( 'siteurl' ); ?>/">
<link rel="stylesheet" href="<?php bloginfo( 'siteurl' ); ?>/css/inside_style1.css" type="text/css" />  
<link rel="stylesheet" href="<?php bloginfo( 'siteurl' ); ?>/css/tabs.css" type="text/css" />  
		<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'siteurl' ); ?>/css/superfish.css" media="screen">
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/function.js"></script>

<script src="js-1.js" type="text/javascript">
</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/hoverIntent.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>

	  <script src="<?php bloginfo( 'siteurl' ); ?>/lightbox/js/prototype.js" type="text/javascript"></script>
	<script src="<?php bloginfo( 'siteurl' ); ?>/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
	<script src="<?php bloginfo( 'siteurl' ); ?>/lightbox/js/lightbox.js" type="text/javascript"></script>

   <link rel="stylesheet" href="<?php bloginfo( 'siteurl' ); ?>/lightbox/css/lightbox.css" type="text/css" media="screen" />
	<script language="JavaScript1.2" type="text/javascript">
    <!--
    function MM_findObj(n, d) { //v4.01
      var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
        d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
      if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
      for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
      if(!x && d.getElementById) x=d.getElementById(n); return x;
    }
    function MM_swapImage() { //v3.0
      var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
       if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
    }
    function MM_swapImgRestore() { //v3.0
      var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
    }
    
    function MM_preloadImages() { //v3.0
      var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
        var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
    }
    
    //-->
    </script>
<style>
#caption
{
	display:block; float:left; size:100px;
}
#imagetitle
{
width:500px;
text-align:center; font-weight:bold;}
</style>
	 
</head>
<body style="text-align: center;">

<div id="main" style="text-align: center;" >
  

  	<div id="main_content">
	 
	   <div class="logo_clr">
         <a id="link" href="index.php"><span class="header_letter">R</span>OGER <span class="header_letter">W</span>ILLIAMS </a>
<iframe src="http://www.facebook.com/plugins/like.php?app_id=228184027214324&amp;href=<?php echo $permalink = get_permalink($post_id); ?>&amp;send=false&amp;layout=button_count&amp;width=48&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:48px; height:21px;" allowTransparency="true"></iframe>       </div>
	   
	  <!--  navigation-->	   
       <?php
	   		
	   		wp_nav_menu(array("container" => "ul","menu_id"=>"nav","menu"=>"header_navigation")); 
	   ?>
<script language="javascript">
function f(){
		document.getElementById('searchBox').style.width="100px";
}
function f2(){
	if(document.getElementById('searchBox').value == '')
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

jQuery(document).ready(function(){
	jQuery("#nav:last").append('<li style="margin:0px;padding:0px;"><form id="searchform" method="get" action=""><a  href="javascript:void(0);"><input type=text id="searchBox" name="s" style="width:2px;display:inline"  onblur="f2();" onClick="f();"></a></form></li>');
});

</script>
<!--[if IE 8]>
<style type="text/css">
#searchform a{padding-left:1px;padding-right:1px;padding-bottom:0px !important;padding-top:3px;}
</style>
<![endif]-->

<!--  navigation ends -->
	    
	  <div id="clear"></div> 
	   
       <div class="header_text" >Archive</div>
  </div>
 
 <?php 
 	$siteurl = get_bloginfo("siteurl");
	//echo DB_NAME;
 ?>
 <div  class="outer">
  <div id="images"> 
		<?php
		  $query = "SELECT * FROM wp_gallery_slides  WHERE uselink='N'  order by created desc";
		  $db->query($query);
		  $num=$db->numRows();
		  $page=new Page;
		  $page->show_disabled_links=false;
		  $page->set_page_data('',$num,12,10,true,true);
		  //echo $_SERVER[""];
		  $permalink = get_permalink($post_id); 
		  //$qryStr = "page_id=".$post_id;
		  $qryStr = $permalink;
		  $page->set_qry_string($qryStr);
		 
		  $db->query($page->get_limit_query($query));	 
		  //$resultArray = mysql_query("SELECT * FROM wp_gallery_slides WHERE uselink='N' ");
		  $uploadpath = dirname(dirname(dirname(__FILE__)))."/uploads/slideshow-gallery/";
		  if($num > 0)
          {
		  	 while($row=$db->fetchArray())
		     {
			 $image    = $row['image'];
			 $imageArr = explode(".",$image);
			 $smallImage = $imageArr[0]."-small.".$imageArr[1];
			 if(file_exists($uploadpath.$squareImage))
			 {
			 	$squareImage = $imageArr[0]."-square.".$imageArr[1];
				$width  = " width=''";
				$height = " height=''";
			 } 	
			 else
			 {
			 //	$squareImage = $image;
				$width  = " width='160'";
				$height = " height='160'";
			 } 	
			 $squareImage = $imageArr[0]."-square.".$imageArr[1];
			 $pastworkImage = $imageArr[0]."-pastwork.".$imageArr[1];
			 
			 
			$loc = "";
			$imgDim = ""; 
			$loc  = $row['location'];
			if($row['image_width']!="" && $row['image_height']!="")
			{
				$imgDim  = $row['image_width']." * ".$row['image_height'];
			}

			 ?>
                <div class="pad">
                <a href="<?php echo $siteurl;?>/wp-content/uploads/slideshow-gallery/<?php echo $row['image'];?>" rel="lightbox[roadtrip]" title="<?php echo $row['title'];?>" imgDim="<?php echo $imgDim;?>"  loc="<?php echo $loc;?>">
                   <img src="<?php echo $siteurl;?>/wp-content/uploads/slideshow-gallery/<?php echo $squareImage;?>" <?php echo $width; echo $height;?> border="0"/>
                 </a></div>
             <?php	
			 }
		  }
		  else
		  {
		  	
		  }
		  echo "<div style='clear:both'></div>";
			 echo "<div class='paging'>";
			 //$page->get_page_nav_next_prev();
			 $page->get_page_nav_prev(true);		 
			 $page->get_page_nav_next(true);
			 echo "</div>";		  
		  ?>
	</div>
    </div>

</div>
</div>
</div>
    <script type="text/javascript">

 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', 'UA-703844-1']);
 _gaq.push(['_setDomainName', '.rogerwilliamsart.com']);
 _gaq.push(['_trackPageview']);

 (function() {
   var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
   ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 })();

</script>

</body>
</html>

