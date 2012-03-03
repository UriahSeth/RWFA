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
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/function.js"></script>
 <script type="text/javascript">

 
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
</script>
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
         <span style="font-size:30px;">R</span>OGER <span style="font-size:30px;">W</span>ILLIAMS 
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
	
	<li style="margin-right:0px;"><a href="#">Search</a></li>
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
		
		