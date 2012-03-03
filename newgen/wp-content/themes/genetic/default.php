<?php
/**
 * Template Name: default
 *
 */
?>

<?php get_header(); ?>
	   
	   <!-- left contant-->
	   
	   <div class="leftcol">
	    <div id="featured" class="body">
<?php 
		
		get_template_part( 'loop', 'page' );
  
?>		
		
	   
	    </div><!-- /#featured -->
       	   
	   </div>
	   
       <!--end left contant-->
	   
	  <?php get_sidebar(); ?>
 <?php get_footer(); ?>
   
	