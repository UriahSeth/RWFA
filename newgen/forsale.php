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

<script type="text/javascript">
$(document).ready(function(){
  $(".image0").click(function(){
	var name = $(".image0").attr("src");						  
    alert(name);
  });
});

</script> 
</head>


<body style="text-align: center;">

<div id="main" style="text-align:center" >

  <div id="main_content">
	   	   <div id="logo_text">
         <a href="index.htm"><span class="header_letter">R</span>OGER <span class="header_letter">W</span>ILLIAMS </a>
       </div>
	   
	  <div>
	<ul id="nav">
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
	border: 1px solid #999999;
	background-repeat: repeat;
	background-image:url('images/search.png');
	background-repeat:no-repeat;
	padding-right:15px;
	padding-top:0px;
	padding-bottom:0px;
	background-position:right;
	font-family: Helvetica_Neue_LT-65Medium;
    font-size: 13px;
    font-weight: normal;
	border-style: ridge;
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
</script>

		<li style="margin:0px;padding:0px;">
		<form id="searchform" method="get" action="">
		<a href="#" style="padding-left:1px;padding-right:1px;padding-bottom:3px;padding-top:3px;">
			<input type=text id="searchBox" name="s" style="width:2px;display:inline"  onblur="f2();" onClick="f();">
		</a>
		</form>

		</li>
	</ul>
   </div>
  	  <div id="clear"></div> 
	 </div>


<div id="large_image" >

<div>
 <div id="container" align="center">
       <div id="gallery" class="ad-gallery" align="center">
      <div class="ad-image-wrapper">      </div>
      <div class="ad-controls">
      </div>
      <div class="ad-nav">
        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
		  <?php 
		  $resultArray = mysql_query("SELECT * FROM wp_gallery_slides WHERE uselink='Y'");
		  while($row = mysql_fetch_array($resultArray)){
		  	//print_r($row);
		  ?>
            <li>
              <a href="/wordpress/wp-content/uploads/slideshow-gallery/<?php echo $row['image'];?>">
                <img src="/wordpress/wp-content/uploads/slideshow-gallery/<?php echo $row['image'];?>" class="image0" width="80" height="50">              </a>      
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

<div style="width:640px; height:20px; margin:0 auto;">

<!--<div style="width:320px; height:20px; float:left">24X36</div>-->
<div id="imint" style="width:100px; height:20px; float: right">
<a href="test.php" title="I am Interested" rel="gb_page_center[640, 480]">I am Interested</a>
</div>
</div>

<div style="width:640px; height:30px; margin:0 auto;">
<div style="width:200px; height:30px; float:left;  "></div>
<div style="width:200px; height:30px; float:left;  "></div>

<div style="float:right;">
		        <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_counter addthis_pill_style"></a>
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

