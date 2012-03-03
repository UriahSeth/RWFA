<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
	  
	  
	   
	   
	   <!-- left contant-->
	   
	   <div class="leftcol">
	    <div id="featured" class="body">
		<article>
	    <hgroup>

		  <h2>Featured Article</h2>
		<p class="footer_date">November 24, 2010 </p>  
	    </hgroup>
		
	     <p class="featured_content">Discover how to use Graceful Degradation and Progressive Enhancement techniques to achieve an outstanding, cross-browser <a href="http://dev.w3.org/html5/spec/Overview.html" rel="external">HTML5</a> and <a href="http://www.w3.org/TR/css3-roadmap/" rel="external">CSS3</a> website today!. 
		 </p>
		 <div style="border-bottom:1px dotted #242424; padding-bottom:20px; height:15px;"> 
		 <div style="width:200px; float:left"><p class="footer_date" >Posted in <span style="color:#000000">Existentalism</span></p></div><div style="width:60px; float:right;">
		   <a href="#"><img src="images/share.jpg" width="61" height="24"></a>
		 </div><div style="width:60px; float:right;">
		   <a href="#"><img src="images/facebookicon.jpg" width="53" height="25"></a>
		 </div></div><div style="clear:both"></div>  
          
         </article>
		 
		 <br>
		 
		<article>
	    <hgroup>

		  <h2>Next Featured Article</h2>
		<p class="footer_date">November 24, 2010 </p>  
	    </hgroup>
		
	     <p class="featured_content">Discover how to use Graceful Degradation and Progressive Enhancement techniques to achieve an outstanding, cross-browser <a href="http://dev.w3.org/html5/spec/Overview.html" rel="external">HTML5</a> and <a href="http://www.w3.org/TR/css3-roadmap/" rel="external">CSS3</a> website today!
		 </p>
		 
		 <p class="footer_date">Posted in <span style="color:#000000">Existentalism</span></p>  
          
         </article>
	   
	    </div><!-- /#featured -->
       	   
	   </div>
	   
       <!--end left contant-->
	   
	  <?php get_sidebar(); ?>
 <?php get_footer(); ?>
   
	