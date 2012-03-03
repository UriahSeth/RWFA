<!DOCTYPE html>  
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Roger Williams Art Gallery</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>

<style type="text/css">
@charset "utf-8";


@font-face {
	font-family: "Helvetica_Neue_LT-45Light";
	font-weight: normal;
    font-style: normal;
	src: url( 'Helvetica_Neue_LT-45Light.ttf'); /* IE */  
	src: local("Helvetica_Neue_LT-45Light"), url( 'Helvetica_Neue_LT-45Light.ttf') format("truetype"); /* non-IE */ ;
}

@font-face {
	font-family: "Helvetica_Neue_LT-65Medium";
	font-weight: normal;
    font-style: normal;
	src: url( 'Helvetica_Neue_LT-65Medium.ttf'); /* IE */  
	src: local("Helvetica_Neue_LT-65Medium"), url( 'Helvetica_Neue_LT-65Medium.ttf') format("truetype"); /* non-IE */ ;
}

@font-face {
	font-family: "Bell_MT";
	font-weight: normal;
    font-style: normal;
	src: url( 'Bell_MT.ttf'); /* IE */  
	src: local("Bell_MT"), url( 'Bell_MT.ttf') format("truetype"); /* non-IE */ ;
}

* {margin:0;padding:0}
/* mac hide \*/
html,body{height:100%;width:100%;}
/* end hide */
body { 
	text-align:center;
	min-height:580px;/* for ie7*/
	background-color:#999999;
}
#xouter{
	height:100%;
	width:100%;
	display:table;
	vertical-align:middle;
}
#xcontainer {
	text-align: center;
	position:relative;
	vertical-align:middle;
	display:table-cell;
	height: 580px;
}	
#xinner {
	width: 960px;
	
	height: 580px;
	text-align: center;
	margin-left:auto;
	margin-right:auto;
	
}

#nav.head_nav {
	width:100%;
	text-align:center;
	}
	
	#nav.head_nav ul li {
		display: inline;
		margin: 0px 5px;
		
	}
	
	#nav.head_nav ul li a {
		color:#DDDDDD;
		font-size: 20px;
		font-weight: normal;
		font-family:Helvetica_Neue_LT-65Medium;
		padding: 5px 8px;
		text-decoration:none;
	}
	
	#nav.head_nav ul li a:hover{
	color:#444444;
	text-decoration:none;
	}
	

/* not required for demo */
p,h1{margin-bottom:1em}
#header{margin-right:0}
.maintxt{text-align:left;margin:1em;}
/* CSS Document */


</style>

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
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
-->

<script type="text/javascript">

/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
var fadeSlideShow_descpanel={
	controls: [['x.png',7,7], ['restore.png',10,11], ['../images/loading.gif',54,55]], //full URL and dimensions of close, restore, and loading images
	fontStyle: 'normal 11px Verdana', //font style for text descriptions
	slidespeed: 200 //speed of description panel animation (in millisec)
}

//No need to edit beyond here...

jQuery.noConflict()

function fadeSlideShow(settingarg){
	this.setting=settingarg
	settingarg=null
	var setting=this.setting
	setting.fadeduration=setting.fadeduration? parseInt(setting.fadeduration) : 500
	setting.curimage=(setting.persist)? fadeSlideShow.routines.getCookie("gallery-"+setting.wrapperid) : 0
	setting.curimage=setting.curimage || 0 //account for curimage being null if cookie is empty
	setting.currentstep=0 //keep track of # of slides slideshow has gone through (applicable in displaymode='auto' only)
	setting.totalsteps=setting.imagearray.length*(setting.displaymode.cycles>0? setting.displaymode.cycles : Infinity) //Total steps limit (applicable in displaymode='auto' only w/ cycles>0)
	setting.fglayer=0, setting.bglayer=1 //index of active and background layer (switches after each change of slide)
	setting.oninit=setting.oninit || function(){}
	setting.onslide=setting.onslide || function(){}
	if (setting.displaymode.randomize) //randomly shuffle order of images?
		setting.imagearray.sort(function() {return 0.5 - Math.random()})
	var preloadimages=[] //preload images
	setting.longestdesc="" //get longest description of all slides. If no desciptions defined, variable contains ""
	for (var i=0; i<setting.imagearray.length; i++){ //preload images
		preloadimages[i]=new Image()
		preloadimages[i].src=setting.imagearray[i][0]
		if (setting.imagearray[i][3] && setting.imagearray[i][3].length>setting.longestdesc.length)
			setting.longestdesc=setting.imagearray[i][3]
	}
	var closebutt=fadeSlideShow_descpanel.controls[0] //add close button to "desc" panel if descreveal="always"
	setting.closebutton=(setting.descreveal=="always")? '<img class="close" src="'+closebutt[0]+'" style="float:right;cursor:hand;cursor:pointer;width:'+closebutt[1]+'px;height:'+closebutt[2]+'px;margin-left:2px" title="Hide Description" />' : ''
	var slideshow=this
	jQuery(document).ready(function($){ //fire on DOM ready
		var setting=slideshow.setting
		var fullhtml=fadeSlideShow.routines.getFullHTML(setting.imagearray) //get full HTML of entire slideshow
		setting.$wrapperdiv=$('#'+setting.wrapperid).css({position:'relative', visibility:'visible', background:'black', overflow:'hidden', width:setting.dimensions[0], height:setting.dimensions[1]}).empty() //main slideshow DIV
		if (setting.$wrapperdiv.length==0){ //if no wrapper DIV found
			alert("Error: DIV with ID \""+setting.wrapperid+"\" not found on page.")
			return
		}
		setting.$gallerylayers=$('<div class="gallerylayer"></div><div class="gallerylayer"></div>') //two stacked DIVs to display the actual slide 
			.css({position:'absolute', left:0, top:0, width:'100%', height:'100%', background:'#999999'})
			.appendTo(setting.$wrapperdiv)
		var $loadingimg=$('<img src="'+fadeSlideShow_descpanel.controls[2][0]+'" style="position:absolute;width:'+fadeSlideShow_descpanel.controls[2][1]+';height:'+fadeSlideShow_descpanel.controls[2][2]+'" />')
			.css({left:setting.dimensions[0]/2-fadeSlideShow_descpanel.controls[2][1]/2, top:setting.dimensions[1]/2-fadeSlideShow_descpanel.controls[2][2]}) //center loading gif
			.appendTo(setting.$wrapperdiv)
		var $curimage=setting.$gallerylayers.html(fullhtml).find('img').hide().eq(setting.curimage) //prefill both layers with entire slideshow content, hide all images, and return current image
		if (setting.longestdesc!="" && setting.descreveal!="none"){ //if at least one slide contains a description (versus feature is enabled but no descriptions defined) and descreveal not explicitly disabled
			fadeSlideShow.routines.adddescpanel($, setting)
			if (setting.descreveal=="always"){ //position desc panel so it's visible to begin with
				setting.$descpanel.css({top:setting.dimensions[1]-setting.panelheight})
				setting.$descinner.click(function(e){ //asign click behavior to "close" icon
					if (e.target.className=="close"){
						slideshow.showhidedescpanel('hide')
					}
				})
				setting.$restorebutton.click(function(e){ //asign click behavior to "restore" icon
					slideshow.showhidedescpanel('show')
					$(this).css({visibility:'hidden'})
				})
			}
			else if (setting.descreveal=="ondemand"){ //display desc panel on demand (mouseover)
				setting.$wrapperdiv.bind('mouseenter', function(){slideshow.showhidedescpanel('show')})
				setting.$wrapperdiv.bind('mouseleave', function(){slideshow.showhidedescpanel('hide')})
			}
		}
		setting.$wrapperdiv.bind('mouseenter', function(){setting.ismouseover=true}) //pause slideshow mouseover
		setting.$wrapperdiv.bind('mouseleave', function(){setting.ismouseover=false})
		if ($curimage.get(0).complete){ //accounf for IE not firing image.onload
			$loadingimg.hide()
			slideshow.paginateinit($)
			slideshow.showslide(setting.curimage)
		}
		else{ //initialize slideshow when first image has fully loaded
			$loadingimg.hide()
			slideshow.paginateinit($)
			$curimage.bind('load', function(){slideshow.showslide(setting.curimage)})
		}
		setting.oninit.call(slideshow) //trigger oninit() event
		$(window).bind('unload', function(){ //clean up and persist
			if (slideshow.setting.persist) //remember last shown image's index
				fadeSlideShow.routines.setCookie("gallery-"+setting.wrapperid, setting.curimage)
			jQuery.each(slideshow.setting, function(k){
				if (slideshow.setting[k] instanceof Array){
					for (var i=0; i<slideshow.setting[k].length; i++){
						if (slideshow.setting[k][i].tagName=="DIV") //catches 2 gallerylayer divs, gallerystatus div
							slideshow.setting[k][i].innerHTML=null
						slideshow.setting[k][i]=null
					}
				}
			})
			slideshow=slideshow.setting=null
		})
	})
}

fadeSlideShow.prototype={

	navigate:function(keyword){
		var setting=this.setting
		clearTimeout(setting.playtimer)
		if (setting.displaymode.type=="auto"){ //in auto mode
			setting.displaymode.type="manual" //switch to "manual" mode when nav buttons are clicked on
			setting.displaymode.wraparound=true //set wraparound option to true
		}
		if (!isNaN(parseInt(keyword))){ //go to specific slide?
			this.showslide(parseInt(keyword))
		}
		else if (/(prev)|(next)/i.test(keyword)){ //go back or forth inside slide?
			this.showslide(keyword.toLowerCase())
		}
	},

	showslide:function(keyword){
		var slideshow=this
		var setting=slideshow.setting
		if (setting.displaymode.type=="auto" && setting.ismouseover && setting.currentstep<=setting.totalsteps){ //if slideshow in autoplay mode and mouse is over it, pause it
			setting.playtimer=setTimeout(function(){slideshow.showslide('next')}, setting.displaymode.pause)
			return
		}
		var totalimages=setting.imagearray.length
		var imgindex=(keyword=="next")? (setting.curimage<totalimages-1? setting.curimage+1 : 0)
			: (keyword=="prev")? (setting.curimage>0? setting.curimage-1 : totalimages-1)
			: Math.min(keyword, totalimages-1)
		var $slideimage=setting.$gallerylayers.eq(setting.bglayer).find('img').hide().eq(imgindex).show() //hide all images except current one
		var imgdimensions=[$slideimage.width(), $slideimage.height()] //center align image
		
		if (setting.descreveal=="peekaboo" && setting.longestdesc!=""){ //if descreveal is set to "peekaboo", make sure description panel is hidden before next slide is shown
			clearTimeout(setting.hidedesctimer) //clear hide desc panel timer
			slideshow.showhidedescpanel('hide', 0) //and hide it immediately
		}
		setting.$gallerylayers.eq(setting.bglayer).css({zIndex:1000, opacity:0}) //background layer becomes foreground
			.stop().css({opacity:0}).animate({opacity:1}, setting.fadeduration, function(){ //Callback function after fade animation is complete:
				clearTimeout(setting.playtimer)
				try{
					setting.onslide.call(slideshow, setting.$gallerylayers.eq(setting.fglayer).get(0), setting.curimage)
				}catch(e){
					alert("Fade In Slideshow error: An error has occured somwhere in your code attached to the \"onslide\" event: "+e)
				}
				if (setting.descreveal=="peekaboo" && setting.longestdesc!=""){
					slideshow.showhidedescpanel('show')
					setting.hidedesctimer=setTimeout(function(){slideshow.showhidedescpanel('hide')}, setting.displaymode.pause-fadeSlideShow_descpanel.slidespeed)
				}	
				setting.currentstep+=1
				if (setting.displaymode.type=="auto"){
					if (setting.currentstep<=setting.totalsteps || setting.displaymode.cycles==0)
						setting.playtimer=setTimeout(function(){slideshow.showslide('next')}, setting.displaymode.pause)
				}
			}) //end callback function
		setting.$gallerylayers.eq(setting.fglayer).css({zIndex:999}) //foreground layer becomes background
		setting.fglayer=setting.bglayer
		setting.bglayer=(setting.bglayer==0)? 1 : 0
		setting.curimage=imgindex
		if (setting.$descpanel){
			setting.$descpanel.css({visibility:(setting.imagearray[imgindex][3])? 'visible' : 'hidden'})
			if (setting.imagearray[imgindex][3]) //if this slide contains a description
				setting.$descinner.empty().html(setting.closebutton + setting.imagearray[imgindex][3])
		}
		if (setting.displaymode.type=="manual" && !setting.displaymode.wraparound){
			this.paginatecontrol()
		}
		if (setting.$status) //if status container defined
			setting.$status.html(setting.curimage+1 + "/" + totalimages)
	},

	showhidedescpanel:function(state, animateduration){
		var setting=this.setting
		var endpoint=(state=="show")? setting.dimensions[1]-setting.panelheight : this.setting.dimensions[1]
		setting.$descpanel.stop().animate({top:endpoint}, (typeof animateduration!="undefined"? animateduration : fadeSlideShow_descpanel.slidespeed), function(){
			if (setting.descreveal=="always" && state=="hide")
				setting.$restorebutton.css({visibility:'visible'}) //show restore button
		})
	},

	paginateinit:function($){
		var slideshow=this
		var setting=this.setting
		if (setting.togglerid){ //if toggler div defined
			setting.$togglerdiv=$("#"+setting.togglerid)
			setting.$prev=setting.$togglerdiv.find('.prev').data('action', 'prev')
			setting.$next=setting.$togglerdiv.find('.next').data('action', 'next')
			setting.$prev.add(setting.$next).click(function(e){ //assign click behavior to prev and next controls
				var $target=$(this)
				slideshow.navigate($target.data('action'))
				e.preventDefault()
			})
			setting.$status=setting.$togglerdiv.find('.status')
		}
	},

	paginatecontrol:function(){
		var setting=this.setting
			setting.$prev.css({opacity:(setting.curimage==0)? 0.4 : 1}).data('action', (setting.curimage==0)? 'none' : 'prev')
			setting.$next.css({opacity:(setting.curimage==setting.imagearray.length-1)? 0.4 : 1}).data('action', (setting.curimage==setting.imagearray.length-1)? 'none' : 'next')
			if (document.documentMode==8){ //in IE8 standards mode, apply opacity to inner image of link
				setting.$prev.find('img:eq(0)').css({opacity:(setting.curimage==0)? 0.4 : 1})
				setting.$next.find('img:eq(0)').css({opacity:(setting.curimage==setting.imagearray.length-1)? 0.4 : 1})
			}
	}

	
}

fadeSlideShow.routines={

	getSlideHTML:function(imgelement){
		var layerHTML=(imgelement[1])? '<a href="'+imgelement[1]+'" target="'+imgelement[2]+'">\n' : '' //hyperlink slide?
		layerHTML+='<img src="'+imgelement[0]+'" style="border-width:0;" />\n'
		layerHTML+=(imgelement[1])? '</a>\n' : ''
		return layerHTML //return HTML for this layer
	},

	getFullHTML:function(imagearray){
		var preloadhtml=''
		for (var i=0; i<imagearray.length; i++)
			preloadhtml+=this.getSlideHTML(imagearray[i])
		return preloadhtml
	},

	adddescpanel:function($, setting){
		setting.$descpanel=$('<div class="fadeslidedescdiv"></div>')
			.css({position:'absolute', visibility:'hidden', width:'100%', left:0, top:setting.dimensions[1], font:fadeSlideShow_descpanel.fontStyle, zIndex:'1001'})
			.appendTo(setting.$wrapperdiv)
		$('<div class="descpanelbg"></div><div class="descpanelfg"></div>') //create inner nav panel DIVs
			.css({position:'absolute', left:0, top:0, width:setting.$descpanel.width()-8, padding:'4px'})
			.eq(0).css({background:'black', opacity:0.7}).end() //"descpanelbg" div
			.eq(1).css({color:'white'}).html(setting.closebutton + setting.longestdesc).end() //"descpanelfg" div
			.appendTo(setting.$descpanel)
		setting.$descinner=setting.$descpanel.find('div.descpanelfg')
		setting.panelheight=setting.$descinner.outerHeight()
		setting.$descpanel.css({height:setting.panelheight}).find('div').css({height:'100%'})
		if (setting.descreveal=="always"){ //create restore button
			setting.$restorebutton=$('<img class="restore" title="Restore Description" src="' + fadeSlideShow_descpanel.controls[1][0] +'" style="position:absolute;visibility:hidden;right:0;bottom:0;z-index:1002;width:'+fadeSlideShow_descpanel.controls[1][1]+'px;height:'+fadeSlideShow_descpanel.controls[1][2]+'px;cursor:pointer;cursor:hand" />')
				.appendTo(setting.$wrapperdiv)


		}
	},


	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value){
		document.cookie = name+"=" + value + ";path=/"
	}
}
</script>

<script type="text/javascript">

var mygallery=new fadeSlideShow({
	wrapperid: "fadeshow1", //ID of blank DIV on page to house Slideshow
	dimensions: [960,490], //width/height of gallery in pixels. Should reflect dimensions of largest image
	imagearray: [
		["<?php echo get_bloginfo('url')?>/wp-includes/images/1.jpg", "", "", ""],
		["<?php echo get_bloginfo('url')?>/wp-includes/images/2.jpg", "", "", ""],
		["<?php echo get_bloginfo('url')?>/wp-includes/images/3.jpg"],
		["<?php echo get_bloginfo('url')?>/wp-includes/images/4.jpg", "", "", ""] //<--no trailing comma after very last image element!
	],
	displaymode: {type:'auto', pause:2500, cycles:0, wraparound:false},
	persist: false, //remember last viewed slide and recall within same session?
	fadeduration: 800, //transition duration (milliseconds)
	descreveal: "ondemand",
	togglerid: ""
})



</script>

<script type="text/javascript">
jQuery(document).ready(function(){

jQuery(document).ready(function () {
jQuery('.nav').fadeIn(5000);
});

});
</script>

</head>
<body>
<div id="xouter">
	<div id="xcontainer">
		<div id="xinner">
		
				 <div id="fadeshow1"></div>
	
	 <div class="nav" style="display:none;" >		
		<div style="text-align:center; padding-top:8px;">
		<img src="<?php echo get_bloginfo('url')?>/wp-includes/images/roger1.jpg" alt="Roger Williams" width="321" height="55" />
		</div>
    
	 	<div id="nav" class="head_nav">
		<ul>
			<li><a href="<?php echo get_bloginfo('url'); ?>?page_id=9">Meet</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>?page_id=25">Artwork</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>?page_id=31">Study</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>?page_id=">Showrooms</a></li>
		</ul>
		</div>
	</div>
		
		</div>
	</div>
</div>
</body>
</html>

<?php //echo get_bloginfo('url')?>
<?php
    //$theme_name = get_current_theme();
   // echo $theme_name;
?>
<?php //bloginfo('template_directory'); ?>
<?php //get_theme_root() ?> 
<?php //bloginfo( 'stylesheet_url' ); ?>