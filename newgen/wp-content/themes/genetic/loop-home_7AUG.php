<?php
/**
 * The loop that displays a page.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-empty.php.
 *
 * @package WordPress
 * @subpackage gebetuc
 * @since Twenty Ten 1.2
 */
?>

<!DOCTYPE html>
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
<link href="css/newcss.css" rel="stylesheet" type="text/css">

<!--[if IE ]>
<link href="iecss.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lt IE 8]>
<style type="text/css">
#xouter{display:block}
#xcontainer{top:50%;display:block}
#xinner{top:-50%;position:relative}
</style>
<![endif]-->

<!--[if IE 7]>
<style type="text/css">
#xouter{
position:relative;
overflow:hidden;
}
</style>
<![endif]-->

<!--[if IE ]>
<link href="iecss.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lt IE 8]>
<style type="text/css">
#xouter{display:block}
#xcontainer{top:50%;display:block}
#xinner{top:-50%;position:relative}
</style>
<![endif]-->

<!--[if IE 7]>
<style type="text/css">
#xouter{
position:relative;
overflow:hidden;
}
</style>
<![endif]-->

<!--[if lt IE 8]>
<style type="text/css">
#xouter{display:block}
#xcontainer{top:50%;display:block}
#xinner{top:-50%;position:relative}
</style>
<![endif]-->

<!--[if IE 7]>
<style type="text/css">
#xouter{
position:relative;
overflow:hidden;
}
</style>
<![endif]-->

<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript" src="js/simplegallery.js">

/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>

<?php
$imageStr = "";
$path = get_bloginfo("siteurl")."/wp-content/uploads/slideshow-gallery/";
$results = $wpdb->get_results("select title,image from wp_gallery_slides where show_at_home=1 order by created desc");
foreach($results as $rkey => $rval)
{
	$imgArr    = explode(".",$rval->image);
	$homeimage = $imgArr[0]."-home".".".$imgArr[1];
	$imageStr .= '["'.$path.$homeimage.'", "", "", ""],';
}
$imageStr = trim($imageStr,",");
?>

<script type="text/javascript">

var mygallery=new fadeSlideShow({
	wrapperid: "fadeshow1", //ID of blank DIV on page to house Slideshow
	dimensions: [960,490], //width/height of gallery in pixels. Should reflect dimensions of largest image
	imagearray: [
		<!--["HomePage/1.jpg", "", "", ""],		["HomePage/2.jpg", "", "", ""],		["HomePage/3.jpg"],		["HomePage/4.jpg", "", "", ""]--> //<--no trailing comma after very last image element!
		<?php echo $imageStr;?>
	],
	displaymode: {type:'auto', pause:2500, cycles:0, wraparound:false},
	persist: false, //remember last viewed slide and recall within same session?
	fadeduration: 800, //transition duration (milliseconds)
	descreveal: "ondemand",
	togglerid: ""
})



</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

$(document).ready(function () {
$('.nav').fadeIn(5000);
});

});
</script>
</head>
<body>
<div id="xouter">
	<div id="xcontainer">
		<div id="xinner">
			<div id="fadeshow1"></div>
 
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
				
<?php endwhile; // end of the loop. ?>

		</div>
	</div>
</div>
</body>
</html>