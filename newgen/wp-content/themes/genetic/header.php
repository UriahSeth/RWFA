<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
 // meta work
 
 /*echo "<pre>";
 print_r($_REQUEST);
 echo "</pre>";*/
 global $post;
 
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
 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="keywords" content="<?php echo $meta_keywords;?>">
<meta name="description" content="<?php echo $meta_description;?>">

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<base href="<?php bloginfo( 'siteurl' ); ?>/">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	if($meta_title=="")
	{
	
			wp_title( '|', true, 'right' );
		
			// Add the blog name.
			bloginfo( 'name' );
		
			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " | $site_description";
		
			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );
				
	}
	else
	{
		echo $meta_title;
	}
	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'siteurl' ); ?>/css/inside_style.css"  type="text/css" />  
<script type="text/javascript">
        var GB_ROOT_DIR = "./greybox/";
    </script>

    <script type="text/javascript" src="<?php bloginfo( 'siteurl' ); ?>/greybox/AJS.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'siteurl' ); ?>/greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'siteurl' ); ?>/greybox/gb_scripts.js"></script>
	<link href="<?php bloginfo( 'siteurl' ); ?>/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/function.js"></script>
	
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
	
?>

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

</head>

 <body>  
 
 
    <div id="main_content">
    
    <!-- start header-->
    <div>
	   
	   <div class="logo_text">
         <a href="index.php">ROGER WILLIAMS </a>
    
    <iframe src="http://www.facebook.com/plugins/like.php?app_id=228184027214324&amp;href=<?php echo $permalink = get_permalink($post_id); ?>&amp;send=false&amp;layout=button_count&amp;width=48&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:48px; height:21px;" allowTransparency="true"></iframe>
	      </div>
	   
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
		document.getElementById('searchBox').style.width="12px";
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
	jQuery("#nav:last").append('<li style="margin:0px !important;padding:0px;"><form id="searchform" method="get" action=""><a  href="javascript:void(0);"><input type=text id="searchBox" name="s" style="width:12px;display:inline;"  onblur="f2();" onClick="f();"></a></form></li>');
});

</script>
<!--[if IE 8]>
<style type="text/css">
#searchform a{padding-left:1px;padding-right:1px;padding-bottom:1px !important;padding-top:3px;}
</style>
<![endif]-->

<!--  navigation ends -->

 </div>
       
       
       
       <!--end header-->
	   <?php 
	 $titleQuery = "SELECT * FROM wp_posts WHERE ID =".$post_id;  
	
	   $titleArray = mysql_query($titleQuery);
	  
	   while($title = mysql_fetch_array($titleArray)){
	   ?>
	   <div style="clear:both; height:10px;  "></div> 
	    <div class="header_text"><?php echo $title['post_title']; ?></div>
		 <div style="clear:both; height:10px;  "></div> 
		<?php }?>
		
		