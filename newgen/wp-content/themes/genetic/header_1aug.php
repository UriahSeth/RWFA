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
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

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

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="css/inside_style.css"  type="text/css" />  
<script type="text/javascript">
        var GB_ROOT_DIR = "./greybox/";
    </script>

    <script type="text/javascript" src="greybox/AJS.js"></script>
    <script type="text/javascript" src="greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="greybox/gb_scripts.js"></script>
	<link href="greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/function.js"></script>
 <!--<script type="text/javascript">

 
 $(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
</script>-->
	
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
</head>

 <body>  
 
 
    <div id="main_content">
    
    <!-- start header-->
    <div>
	   
	   <div class="logo_text">
         <a href="index.php">ROGER WILLIAMS </a>
    
<iframe src="http://www.facebook.com/plugins/like.php?app_id=248833135143154&amp;href=http%3A%2F%2Fwww.facebook.com%2FRWArt&amp;send=false&amp;layout=button_count&amp;width=48&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:48px; height:21px;" allowTransparency="true"></iframe>
	      </div>
	   
	   <div >
	   <?php
//mysql_connect('localhost' ,'root','') or die(' host not connect');
//$link = mysql_select_db('wordpress') or die('database not connected');
 $resultArray = mysql_query("SELECT * FROM wp_posts WHERE post_parent =0 AND menu_order >0 AND post_type='page'  ORDER BY menu_order");

?>

	     <ul id="nav">
		<?php 
		  while($row = mysql_fetch_array($resultArray)){
		  		//print_r($row		);
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
				 <li><a href="<?php echo get_bloginfo('url'); ?>?page_id=<?php echo ($row1['ID']);?>&main_id=<?php echo ($row['ID']);?>"><?php echo ($row1['post_title']);?></a></li>
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
	background-color : #FFFFFF;
	border: 1px solid #FFFFFF;
	background-repeat: repeat;
	background-image:url('images/search.png');
	background-repeat:no-repeat;
	padding-right:15px;
	padding-top:0px;
	padding-bottom:0px;
	background-position:right;
	font-family: Helvetica_Neue_LT-65Medium;
    font-size: 11px;
    font-weight: normal;
	border-style: ridge;
	height:18px;
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
		<a href="#"  style="margin: 0px; padding: 3px 0px;">
			<input type=text id="searchBox" name="s" style="width:2px;display:inline;border:0px;"  onblur="f2();" onClick="f();">
		</a>
		</form>

		</li>
	
</ul>

	   </div>
       
       
       
       </div>
       
       
       
       <!--end header-->
	   <?php 
	 $titleQuery = "SELECT * FROM wp_posts WHERE ID =".$_REQUEST['page_id'];  
	
	   $titleArray = mysql_query($titleQuery);
	  
	   while($title = mysql_fetch_array($titleArray)){
	   ?>
	   <div style="clear:both; height:10px;  "></div> 
	    <div class="header_text"><?php echo $title['post_title']; ?></div>
		 <div style="clear:both; height:10px;  "></div> 
		<?php }?>
		
		