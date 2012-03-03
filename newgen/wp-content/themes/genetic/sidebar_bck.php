<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
 $id=$_REQUEST['main_id'];
 $subMenuQuery = "SELECT * FROM wp_posts WHERE post_parent =".$id." AND menu_order >0 AND post_type='page'";
			$subMenuArray = mysql_query($subMenuQuery);
			
?>
 <!-- start right contant-->
	   
	   <div class="rightcol">
	   
	    <div id="right_featured" class="body"><article>
	      <hgroup>

		  <ul>
		   <li><h3>
		     MEET ROGER</h3>
		    </li>
			<?php 
		if(mysql_num_rows($subMenuArray))
		{
		?>
				 <?php 
					while($row1 = mysql_fetch_array($subMenuArray)){
				 ?>
				 <li><a href="<?php echo get_bloginfo('url'); ?>?page_id=<?php echo ($row1['ID']);?>&main_id=<?php echo ($id);?>"><?php echo ($row1['post_title']);?></a></li>
				 <?php }?>
			
	 	<?php
	 	}
	   ?>
			
			
		  </ul>
	    </hgroup>

         </article>
	    </div><!-- /#featured -->
	   
	   
	   <div id="right_featured" class="body"><article>
	      <hgroup>
          <ul>
		   <li><h3>
		     UPCOMING EVENTS</h3>
		    </li>
			
			<?php 
				$eventQuery = "SELECT * FROM wp_calendar WHERE event_begin >='".date('Y-m-d')."' AND event_category =2  ORDER BY event_begin DESC LIMIT 2";
				$eventArray = mysql_query($eventQuery);
				if(mysql_num_rows($eventArray))
				{
			 		while($row1 = mysql_fetch_array($eventArray)){
			 	?>
				 	<p id="upcom"><a href="<?php echo get_bloginfo('url'); ?>?page_id=19"><?php echo $row['event_begin'] ; echo  " " , $row1['event_title'];?></a></li>
			<?php
					}
	 			}else{
	 				echo "No up comming event";
	 			}
		   ?>
			<br><br>
			<li><h3>Workshops</h3></li>
			<?php 
				$eventQuery = "SELECT * FROM wp_calendar WHERE event_begin >='".date('Y-m-d')."' AND event_category =1  ORDER BY event_begin DESC LIMIT 2";
				$eventArray = mysql_query($eventQuery);
				if(mysql_num_rows($eventArray))
				{
			 		while($row1 = mysql_fetch_array($eventArray)){
			 	?>
				 	<p id="upcom"><a href="<?php echo get_bloginfo('url'); ?>?page_id=19"><?php echo $row['event_begin'] ; echo  " " , $row1['event_title'];?></a></li>
			<?php
					}
	 			}else{
	 				echo "No up comming workshop";
	 			}
		   ?>
			
		  </ul>

	    </hgroup>

         </article>
	    </div><!-- /#featured -->
	  
	  
	  
	   <div id="right_featured" class="body"><article>
	      <hgroup>
          <ul>
		   <li><h3>ARTIST'S COMMUNITY</h3>
		    </li>
			
			<li style="border:none;"> <input type="checkbox" /> I want a show invitation</li>
					<li style="border:none;"> <input type="checkbox" />I want to know about new work</li>
					<li style="border:none;"> <input type="checkbox" />I want to know about workshops</li>
					<li style="border:none;"><input type="text" size="21" style="margin-left:4px;"/> 
<a href="<?php echo get_bloginfo('url'); ?>?page_id=293" title="I am Interested" rel="gb_page_center[640, 480]"> 
<input type="button" value="Submit" /></a>

</li>
                    <li style="border:none;"><div style="margin-left:6px; padding-top:2px;"><a href=" http://www.linkedin.com/in/rogerwilliamsart"><img style="padding-right:8px;"  src="images/Galleries- Galleries_r24_c8_s1.gif" id="b1" onMouseOver="mouseOver()"
onMouseOut="mouseOut()"></a>&nbsp;&nbsp;<a href="http://www.facebook.com/RWArt"><img  style="padding-right:5px;" src="images/Galleries- Galleries_r24_c12_s1.gif" id="b2" onMouseOver="mouseOver2()" onMouseOut="mouseOut2()" ></a>&nbsp;&nbsp; 
<a href="http://twitter.com/pintoralegre"><img src="images/Galleries- Galleries_r24_c16_s1.gif" width="26" height="25" id="b3" onMouseOver="mouseOver3()" onMouseOut="mouseOut3()" ></a>&nbsp;&nbsp; 
<a href=" http://www.youtube.com/user/pintor218"><img src="images/youtube.jpg" width="26" height="25" id="b4" onMouseOver="mouseOver4()" onMouseOut="mouseOut4()" ></a></div>
		  </ul>
						

		  </ul>

	    </hgroup>

         </article>
	    </div><!-- /#featured -->
	  	   </div>
	<!-- end right contant-->
