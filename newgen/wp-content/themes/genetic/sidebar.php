<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

if(isset($_POST['submit'])){

$workshop = $_POST['workshop'];

$artshow =  $_POST['artshow'];

$newwork = $_POST['newwork'];

setcookie("workshop", $workshop);

setcookie("artshow", $artshow);

setcookie("newwork", $newwork);

}

 if(isset($_GET['page_id']))
 {
	 $page_id = $_GET['page_id'];
 }
 if(isset($_GET['p']))
 {
	 $page_id = $_GET['p'];
 }
 if($post->ID > 0)
 {
 	$page_id = $post->ID;
 }	 
 
 $prntQry = $wpdb->get_results("SELECT post_parent FROM wp_posts WHERE ID =".$page_id);
 if($prntQry[0]->post_parent > 0)
 {
 	$id= $prntQry[0]->post_parent;
 }
 else
 {
 	$id= $page_id;
 }	

// $id=$_REQUEST['main_id'];
 
 $subMenuQuery = "SELECT * FROM wp_posts WHERE post_parent =$id AND menu_order >0 AND post_type='page'";
			$subMenuArray = mysql_query($subMenuQuery);
			
?>
 <!-- start right contant-->
	   
	   <div class="rightcol">
	   
	    

		  
			<?php 
		if(mysql_num_rows($subMenuArray))
		{
		?>
 <div id="right_featured" class="body"><article>
	      <hgroup>       
<ul>
		   <li><h3>
		     <!--MEET ROGER--><?php echo get_the_title($id);?></h3>
		    </li>        
				 <?php 
					while($row1 = mysql_fetch_array($subMenuArray)){
				 ?>
				 <!--<li><a href="<?php echo get_bloginfo('url'); ?>?page_id=<?php echo ($row1['ID']);?>&main_id=<?php echo ($id);?>"><?php echo ($row1['post_title']);?></a></li>-->
                 <li><a href="<?php echo get_bloginfo('url'); ?>?page_id=<?php echo ($row1['ID']);?>"><?php echo ($row1['post_title']);?></a></li>
				 <?php }?>
			</ul>
	    </hgroup>

         </article>
	    </div><!-- /#featured -->
	 	<?php
	 	}
	   ?>
			
			
		  
	   
	   

       <?php

       if($page_id!=19)

	   {

	   ?>

	   <div id="right_featured" class="body"><article>
	      <hgroup>
          <ul>
		   <li><h3>
		     UPCOMING EVENTS</h3>
		    </li>
			
			<?php 
				$eventQuery = "SELECT * FROM wp_calendar WHERE event_begin >='".date('Y-m-d')."' AND event_category =2  ORDER BY event_begin LIMIT 3";
				$eventArray = mysql_query($eventQuery);
				if(mysql_num_rows($eventArray))
				{
			 		while($row1 = mysql_fetch_array($eventArray)){

						$d = explode("-",$row1['event_begin']);
						$permalink = get_permalink(19); 

			 	?>
				 	<p id="upcom"><a href="<?php echo $permalink;?><?php echo $row1['event_id'];?>"><?php echo $d[1].'/'.$d[2]. "  ". $row1['event_title'];?></a></li>
			<?php
					}
	 			}else{
	 				echo "No up comming event";
	 			}
		   ?>
			<br><br>
			<li><h3>WORKSHOPS</h3></li>
			<?php 
				$eventQuery = "SELECT * FROM wp_calendar WHERE event_begin >='".date('Y-m-d')."' AND event_category =1  ORDER BY event_begin LIMIT 3";
				$eventArray = mysql_query($eventQuery);
				if(mysql_num_rows($eventArray))
				{
			 		while($row1 = mysql_fetch_array($eventArray)){

					$d = explode("-",$row1['event_begin']);
					$permalink = get_permalink(19); 

			 	?>
				 	<p id="upcom"><a href="<?php echo $permalink;?><?php echo $row1['event_id'];?>"><?php echo $d[1].'/'.$d[2]. "  ". $row1['event_title'];?></a></li>
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

       <?php

       }

	   ?>

	  
	  <?php

       if($page_id!=22)

	   {

			$abs_path = dirname(dirname(dirname(dirname(__FILE__))));
							if($_SERVER['SERVER_NAME']=="localhost")
							{
								$ajaxpath = "../../jxCommon.php";
							}

							else
							{
								$ajaxpath = "jxCommon.php";
							}
	   ?>
	   <script language="javascript">

						function artistContact(obj)

						{						    var serializedata = $("#artist_com_form").serialize();

							$.post("<?php echo $ajaxpath;?>", serializedata, function(data){

								data = jQuery.trim(data);

								if(data=="EMAIL_EMPTY")

								{

									jQuery("#error_mail").html("Please enter an email id.");

								}

								else if(data=="EMAIL_INVALID")

								{

									jQuery("#error_mail").html("Please enter a valid email id.");

								}

								else

								{

									jQuery("#error_mail").html("");

									jQuery("#art_success_msg").html(data);

								}

								document.getElementById("artist_com_form").reset();

							

							});

								return false;

						}

	</script>
<div id="right_featured" class="body"><article>
	      <hgroup>
          <ul>
		   <li><h3>ARTIST'S COMMUNITY</h3>
		    </li>
			<form name="artist_com_form" id="artist_com_form" method="post" onsubmit="return artistContact(this); ">

			<li style="border:none;color:green;font-size:11px;" id="art_success_msg"> </li>

			<li style="border:none; margin-bottom:0px; clear:left;"><span class="chk"> <input type="checkbox" id="artshor" name="artshow" /></span> I want a show invitation</li>
					<li style="border:none; margin-bottom:0px; clear:left;"><span  class="chk"> <input type="checkbox" id="newwork" name="newwork" /></span>I want to know about new work</li>
					<li style="border:none;margin-bottom:0px; clear:left;"><span  class="chk"> <input type="checkbox" id="workshop" name="workshop" /></span>I want to know about workshops</li>
					<li style="border:none;margin-bottom:0px; clear:left;"><div id="error_mail" style="color:red;font-size:11px;"></div>	<input type="text" name="email" id="email"  size="26" value="Enter your email id" onclick="this.value=''" style="margin-left:4px;"/> 

					

<!--<a href="<?php echo get_bloginfo('url'); ?>?page_id=293" rel="gb_page_center[640, 480]"> -->
<input type="submit" value="Submit" name="submit" /></a>

</li></form>
                    <li style="border:none;"><div style="margin-left:6px; padding-top:2px;"><a href=" http://www.linkedin.com/in/rogerwilliamsart"><img style="padding-right:8px;"  src="images/Galleries- Galleries_r24_c8_s1.gif" id="b1" onMouseOver="mouseOver()"
onMouseOut="mouseOut()"></a>&nbsp;&nbsp;<a href="http://www.facebook.com/RWArt"><img  style="padding-right:5px;" src="images/Galleries- Galleries_r24_c12_s1.gif" id="b2" onMouseOver="mouseOver2()" onMouseOut="mouseOut2()" ></a>&nbsp;&nbsp; 
<a href="http://twitter.com/pintoralegre"><img src="images/Galleries- Galleries_r24_c16_s1.gif" width="26" height="25" id="b3" onMouseOver="mouseOver3()" onMouseOut="mouseOut3()" ></a>&nbsp;&nbsp; 
<a href=" http://www.youtube.com/user/pintor218"><img src="images/youtube.jpg" width="26" height="25" id="b4" onMouseOver="mouseOver4()" onMouseOut="mouseOut4()" ></a></div>
		  </ul>
						

		  </ul>

	    </hgroup>

         </article>
	    </div>	   <!-- /#featured -->

       <?php

       }

	   ?> 
	  	   </div>
	<!-- end right contant-->
    