<?php
     class Page
     {
      var $total_records=1;   ///Total Records returned by sql query
      var $records_per_page=1;    ///how many records would be displayed at a time
      var $page_name=""; ///page name on which the class is called
      var $start=0; 
      var $page=0;
      var $total_page=0;
      var $current_page;
      var $remain_page;
      var $show_prev_next=true;
      var $show_scroll_prev_next=false;
      var $show_first_last=false;
	  var $show_disabled_links=true;
      var $scroll_page=0;
	  var $qry_str="";
	  var $link_para="";

	  /* returns boolean value if it is last page or not*/	
      function is_last_page()
      {
       return $this->page>=$this->total_page-1?true:false;
      }
	  /* param : Void
		 returns boolean value if it is first page or not*/	
      function is_first_page()
      {
       return $this->page==0?true:false;
      }
      function current_page()
      {
       return $this->page+1;
      }
      function total_page()
      {
       return $this->total_page==0?1:$this->total_page;
      }
	  
	  //@param : $show = if you want to show desabled links on navigation links.
	  //
	  function show_disabled_links($show=TRUE)	
	  {
	  	$this->show_disabled_links=$show;
	  }
	  //@param : $link_para = if you want to pass any parameter to link
	  //
	  function set_link_parameter($link_para)
	  {
	  	$this->link_para=$link_para;
	  }
      function set_page_name($page_name)
      {
       $this->page_name=$page_name;
      }
	  //@param : str= query string you want to pass to links.
      function set_qry_string($str="")
      {
	  	if(isset($_REQUEST["search"])|| isset($_REQUEST["srch_button"]))
		{
			if(isset($_REQUEST["srch_string"]))
			{
				$srch_string=trim($_REQUEST["srch_string"]);
				$str.="srch_string=".$srch_string;
			}	
			if(isset($_REQUEST["srch_pattern"]))
			{
				$srch_pattern=trim($_REQUEST["srch_pattern"]);
				$str.="&srch_pattern=".$srch_pattern;
			}	
			if(isset($_REQUEST["srch_field"]))
			{
				$srch_field=trim($_REQUEST["srch_field"]);
				$str.="&srch_field=".$srch_field;
			}	
			if(isset($_REQUEST["srtorder"]))
			{
				$srtorder=trim($_REQUEST["srtorder"]);
				$str.="&srtorder=".$srtorder;
			}
			if(isset($_REQUEST["srtfld"]))
			{
				$srtfld=trim($_REQUEST["srtfld"]);
				$str.="&srtfld=".$srtfld;
			}	
		 $str=preg_replace("/page=[^&]*&?/","",$str);
		 $this->qry_str="&".$str."&srch_button=Search";		
		}else
		{
		$str=preg_replace("/page=[^&]*&?/","",$str);
		$this->qry_str="&".$str;
		}
      }
      function set_scroll_page($scroll_num=0)
      {
        if($scroll_num!=0)
			$this->scroll_page=$scroll_num;
		else
			$this->scroll_page=$this->total_records;

      }
      function set_total_records($total_records)
      {
       if($total_records<0)
          $total_records=0;
       $this->total_records=$total_records;
      }
      function set_records_per_page($records_per_page)
      {
         if($records_per_page<=0)
              $records_per_page=$this->total_records;
         $this->records_per_page=$records_per_page;
      }
      /* @params
	  * 	$page_name = Page name on which class is integrated. i.e. $_SERVER['PHP_SELF']
	  *  	$total_records=Total records returnd by sql query.
	  * 	$records_per_page=How many projects would be displayed at a time 
	  *		$scroll_num= How many pages scrolled if we click on scroll page link. 
	  * 				i.e if we want to scroll 6 pages at a time then pass argument 6.
	  * 	$show_prev_next= boolean(true/false) to show prev Next Link
	  * 	$show_scroll_prev_next= boolean(true/false) to show scrolled prev Next Link
	  * 	$show_first_last= boolean(true/false) to show first last Link to move first and last page.
	  */
	  
	  function set_page_data($page_name,$total_records,$records_per_page=1,$scroll_num=0,$show_prev_next=true,$show_scroll_prev_next=false,$show_first_last=false)
      {
       $this->set_total_records($total_records);
       $this->set_records_per_page($records_per_page);
       $this->set_scroll_page($scroll_num);
       $this->set_page_name($page_name);
       $this->show_prev_next=$show_prev_next;
       $this->show_scroll_prev_next=$show_scroll_prev_next;
       $this->show_first_last=$show_first_last;
      }
      /* @params
	  *  $user_link= if you want to display your link i.e if you want to user '>>' instead of [first] link then use
		 Page::get_first_page_nav(">>") OR for image
		 Page::get_first_page_nav("<img src='' alt='first'>") 
		 $link_para: link parameters i.e if you want ot use another parameters such as class.
		 Page::get_first_page_nav(">>","class=myStyleSheetClass")
	  */	   
	  function get_first_page_nav($user_link="",$link_para="")
      {
		if($this->total_page<=1)
			return;
	  	if(trim($user_link)=="")
			$user_link="[First]";
        if(!$this->is_first_page()&& $this->show_first_last)
            echo ' <a href="'.$this->page_name.'?page=0'.$this->qry_str.'" '.$link_para.'>'.$user_link.'</a> ';
        elseif($this->show_first_last && $this->show_disabled_links)
            echo $user_link;
      }
      function get_last_page_nav($user_link="",$link_para="")
      {
		if($this->total_page<=1)
			return;
	  	if(trim($user_link)=="")
			$user_link="[Last]";
        if(!$this->is_last_page()&& $this->show_first_last)
            echo ' <span class="pagelinkspan1"><a href="'.$this->page_name.'?page='.($this->total_page-1).$this->qry_str.'" '.$link_para.' class="pagelink">'.$user_link.'</a></span> ';
        elseif($this->show_first_last && $this->show_disabled_links)
            echo $user_link;
      }
      function get_next_page_nav($user_link="",$link_para="")
      {
		if($this->total_page<=1)
			return;
	  	if(trim($user_link)=="")
			$user_link="Next &raquo;";
        if(!$this->is_last_page()&& $this->show_prev_next)
           //echo ' <span class="pagelinkspan1 nextpagelink"><a href="'.$this->page_name.'?page='.($this->page+1).$this->qry_str.'" '.$link_para.'class="pagelink">'.$user_link.'</a></span> ';
		   echo '<span class="pagelinkspan1 nextpagelink"><a  href="'.$this->page_name.'?page='.($this->page+1).$this->qry_str.'"  onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage(\'grey_next\',\'\',\''.get_bloginfo("template_directory").'/images/orange_next.gif\',1);"><img name="grey_next" src="'.get_bloginfo("template_directory").'/images/grey_next.gif" width="24" height="41" border="0" id="grey_next" alt="next" /></a></span>';
        elseif($this->show_prev_next && $this->show_disabled_links)
            echo $user_link;
      }
      function get_prev_page_nav($user_link="",$link_para="")
      {
		if($this->total_page<=1)
			return;
	  	if(trim($user_link)=="")
			$user_link="&laquo; Prev";
        if(!$this->is_first_page()&& $this->show_prev_next)
            //echo ' <span class="pagelinkspan1 prevpagelink"><a href="'.$this->page_name.'?page='.($this->page-1).$this->qry_str.'" '.$link_para.'class="pagelink">'.$user_link.'</a></span> ';
			echo '<span class="pagelinkspan1 prevpagelink"><a href="'.$this->page_name.'?page='.($this->page-1).$this->qry_str.'"  onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage(\'grey_prev\',\'\',\''.get_bloginfo("template_directory").'/images/orange_prev.gif\',1);"><img name="grey_prev" src="'.get_bloginfo("template_directory").'/images/grey_prev.gif" width="24" height="41" border="0" id="grey_prev" alt="prev" /></a></span>';
        elseif($this->show_prev_next && $this->show_disabled_links)
            echo $user_link;
      }
      function get_scroll_prev_page_nav($user_link="",$link_para="")
      {
	  	
		if($this->scroll_page>=$this->total_page)
			return;
		if(trim($user_link)=="")
			$user_link="Prev[$this->scroll_page]";
        if($this->page>$this->scroll_page &&$this->show_scroll_prev_next)
            echo '<span class="pagelinkspan1 prevpagelink"> <a href="'.$this->page_name.'?page='.($this->page-$this->scroll_page).$this->qry_str.'" '.$link_para.'class="pagelink">'.$user_link.'</a></span> ';
        elseif($this->show_scroll_prev_next && $this->show_disabled_links)
            echo $user_link;
      }
      function get_scroll_next_page_nav($user_link="",$link_para="")
      {
		if($this->scroll_page>=$this->total_page)
			return;
	  	if(trim($user_link)=="")
			$user_link="Next[$this->scroll_page]";
        if($this->total_page>$this->page+$this->scroll_page &&$this->show_scroll_prev_next)
            echo '<span class="pagelinkspan1 nextpagelink"> <a href="'.$this->page_name.'?page='.($this->page+$this->scroll_page).$this->qry_str.'" '.$link_para.' class="pagelink">'.$user_link.'</a></span> ';
        elseif($this->show_scroll_prev_next && $this->show_disabled_links)
            echo $user_link;
      }

      function get_number_page_nav($link_para="")
	  {
        $j=0;
		$scroll_page=$this->scroll_page;
        if($this->page>($scroll_page/2))
          $j=$this->page-intval($scroll_page/2);
        if($j+$scroll_page>$this->total_page)
          $j=$this->total_page-$scroll_page;

        if($j<0)
			$i=0;
		else
			$i=$j;
		
        for(;$i<$j+$scroll_page && $i<$this->total_records;$i++)
        {
         if($i==$this->page)
            echo "<span ".$link_para." class='selectedpage'>".($i+1)."</span>";
         else
            echo '<span class="pagelinkspan"> <a href="'.$this->page_name.'?page='.$i.$this->qry_str.'" '.$link_para.'>'.($i+1).'</a> </span>';
        }
      }

      function get_page_nav()
      {
	  	if($this->total_records<=0)
			return false;
	  	if($this->total_records<=$this->records_per_page)
			return "";
		
        $this->calculate();
        $this->get_first_page_nav("",$this->link_para);
        $this->get_scroll_prev_page_nav("",$this->link_para);
        $this->get_prev_page_nav("",$this->link_para);
        $this->get_number_page_nav($this->link_para);
        $this->get_next_page_nav("",$this->link_para);
        $this->get_scroll_next_page_nav("",$this->link_para);
        $this->get_last_page_nav("",$this->link_para);
		//return true;
      }
	  
	  function get_page_nav_next_prev()
      {
	  	if($this->total_records<=0)
			return false;
	  	if($this->total_records<=$this->records_per_page)
			return "";
		
        $this->calculate();
      //  $this->get_first_page_nav("",$this->link_para);
        $this->get_scroll_prev_page_nav("",$this->link_para);
        $this->get_prev_page_nav("",$this->link_para);
       // $this->get_number_page_nav($this->link_para);
        $this->get_next_page_nav("",$this->link_para);
        $this->get_scroll_next_page_nav("",$this->link_para);
      //  $this->get_last_page_nav("",$this->link_para);
		//return true;
      }
	  
	  function get_page_nav_next()
      {
	  	if($this->total_records<=0)
			return false;
	  	if($this->total_records<=$this->records_per_page)
			return "";
		
        $this->calculate();
      
        $this->get_next_page_nav("",$this->link_para);
        $this->get_scroll_next_page_nav("",$this->link_para);
      
      }
	  function get_page_nav_prev()
      {
	  	if($this->total_records<=0)
			return false;
	  	if($this->total_records<=$this->records_per_page)
			return "";		
        $this->calculate();
      
        $this->get_scroll_prev_page_nav("",$this->link_para);
        $this->get_prev_page_nav("",$this->link_para);
       
      }
	  
	  
	  
      function calculate()
      {
	 
	  if(isset($_REQUEST["search"]))
	  {
	  if($_REQUEST["search"]==0)
	   $this->page=0;
	   
	  }else{
	   if(isset($_REQUEST['page']))
        $this->page=$_REQUEST['page'];
        if(!is_numeric($this->page))
          $this->page=0;
	  }  
        $this->start=$this->page*$this->records_per_page;
        $this->total_page=@intval($this->total_records/$this->records_per_page);

		if($this->total_records!=0 && $this->records_per_page!=0 )
        if($this->total_records%$this->records_per_page!=0)
          $this->total_page++;
      }
      function get_limit_query($qry="")
      {
        $this->calculate();
        return $qry." LIMIT $this->start,$this->records_per_page";

      }
     }
     
     
?>
