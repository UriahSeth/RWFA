<?php
/**
 * Template Name: calendar
 *
 */
?>

<?php get_header(); ?>
<?php
$x=0;
if(!empty($_GET["yr"]))
{
	$x=1;
}
?>

       <style>
       #sidebar{float:left;}
       </style>	   
	   <!-- left contant-->
	   <script language="javascript">
	   function display_sidebar(mode)
	   {
	   		if(mode==false)
			{
				jQuery("#sidebar").hide();
				//jQuery(".leftcol #featured").addClass("leftbox");
				jQuery(".leftbox").css({"width":"911px"});
			}
			else
			{
				jQuery("#sidebar").show();
				jQuery(".leftbox").css({"width":"654px"});
				//jQuery(".leftcol #featured").removeClass("leftbox");
			}

	   }
	
	   </script>

	   <div class="leftcol">
	    <div id="featured" class="body leftbox" style="width:654px;">
<div>
<script language="javascript">
	   jQuery(document).ready(function(){

		// right sidebar controller
		<?php if($x==1) echo 'display_sidebar(false);'; else echo 'display_sidebar(true);';?>
		
	   		
	   	//When page loads...
			$(".tab_content").hide(); //Hide all content
			$("ul.tabs li:eq(<?php echo $x;?>)").addClass("active").show(); //Activate first tab
			$(".tab_content:eq(<?php echo $x;?>)").show(); //Show first tab content
		
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
<ul class="tabs">
	<li><a href="#tab1" onclick="display_sidebar(true)">Agenda</a></li>
	<li><a href="#tab2" onclick="display_sidebar(false)">Calendar</a></li>
</ul>
<div  class="tab_container"> 
<div id="tab1" class="tab_content">

<?php 

function replacenewline($string)
{
	$str = $string;
	$order = array("\r\n", "\n", "\r");
	$replace = '<br />';
	$newstr = str_replace($order, $replace, $str);
	return $newstr;
}

				$eventQuery = "SELECT * FROM wp_calendar WHERE event_begin >='".date('Y-m-d')."'   ORDER BY event_begin LIMIT 6";

				$eventArray = mysql_query($eventQuery);

				if(mysql_num_rows($eventArray))

				{

			 		while($row1 = mysql_fetch_array($eventArray)){
						$d = explode("-",$row1['event_begin']);
						$end = explode("-",$row1['event_end']);
						$t = explode(":",$row1['event_time']);
						$mk_date = mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]);
						$print_date = date("F d, Y", $mk_date);
						
						$end_mk_date = mktime(0, 0, 0, $end[1], $end[2], $end[0]);
						$end_print_date = date("F d, Y", $end_mk_date);
						
						$print_time = date("g:i A", $mk_date);
						
						


			 	?>

				
                <h2  id="event_id_<?php echo $row1['event_id'];?>"><?php echo $row1['event_title']; ?></h2>
                <!--<input class="hiddentext" type="text" id="event_id_<?php echo $row1['event_id'];?>">-->
                <p  style="font-size:12px;"><?php echo $print_date; if($row1["event_end"] !="" && $row1["event_end"]!=$row1["event_begin"]) echo " - ".$end_print_date;//$d[1].'-'.$d[2].'-'.$d[0]; ?></p>
                 <p  style="font-size:12px;"><?php echo $print_time;//$d[1].'-'.$d[2].'-'.$d[0]; ?></p>
                <p style="padding:5px 0;"><?php echo $row1['event_desc']; ?></p>
                <p >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="14%">Location :</td>
                    <td width="86%"><?php echo replacenewline(htmlspecialchars(stripslashes($row1['event_location']))); ?></td>
                  </tr>
                </table>

                </p>
<?php

					}

	 			}else{

	 				echo "No up comming event";

	 			}

		   ?>
</div>
<script language="javascript">
jQuery(function(){
	$('html, body').animate({ scrollTop: $("#event_id_<?php echo $_GET["event_id"]?>").offset().top }, 100);
	//jQuery("#event_id_<?php echo $_GET["event_id"]?>").focus();	
	jQuery(".hiddentext").hide();

});
</script>
<div style="background:#FFD9D9;margin: 15px;">
<div id="tab2"  class="tab_content" style="background:#FFFFFF">
<?php 
		
		get_template_part( 'calendar', 'page' );
  
?>	
</div>
</div>
</div>
<!-- /#featured -->

</div>	
		
	   
	    </div><!-- /#featured -->
       	   
	   </div>
	   
       <!--end left contant-->
	   <div id="sidebar">
	  <?php get_sidebar(); ?>
      </div>
 <?php get_footer(); ?>
   
	