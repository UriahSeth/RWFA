<?php
/**
 * Template Name: blog
 *
 */
?>

<?php get_header(); ?>
	  
<?php 
	    
//mysql_connect('localhost' ,'root','') or die(' host not connect');
//$links = mysql_select_db('wordpress') or die('database not connected');
 $postArray = mysql_query("SELECT * FROM wp_posts WHERE post_type='post' order by post_date DESC LIMIT 2");

?>	   
	   <!-- left contant-->
	   
	   <div class="leftcol">
	    <div id="featured" class="body">
<?php 
		$i=0;
		  while($row = mysql_fetch_array($postArray)){
		  $i++;
		if($i>2){break;}
		  
?>		
		<article>
	    <hgroup>

		  <h2><?php echo ($row['post_title']);?></h2>
		<p class="footer_date"><?php echo (date('F d,Y',strtotime($row['post_date'])));?></p>  
	    </hgroup>
		
	     <p class="featured_content"><?php echo ($row['post_content']);?>
		 </p>
		 <div style="border-bottom:1px dotted #242424; padding-bottom:20px; height:15px;"> 
		 <div style="width:200px; float:left"><p class="footer_date" >Posted in <span style="color:#000000">Existentalism</span></p></div><div style="float:right;">
		        <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<!--<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>-->
<iframe src="http://www.facebook.com/plugins/like.php?app_id=228184027214324&amp;href=http%3A%2F%2Fgch.co.in%2Fwordpress%2F%3Fpage_id%3D9%26main_id%3D9&amp;send=false&amp;layout=button_count&amp;width=48&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:48px; height:21px;float:left;" allowTransparency="true"></iframe>
<!--<a class="addthis_button_tweet"></a>
--><a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d66e5e269f83954"></script>
<!-- AddThis Button END -->
		 </div>
		   
		 </div><div style="clear:both"></div>  
     
         </article>
<?php } ?>		 
		 <br>
		
	   
	    </div><!-- /#featured -->
       	   
	   </div>
	   
       <!--end left contant-->
	   
	  <?php get_sidebar(); ?>
 <?php get_footer(); ?>
   
	