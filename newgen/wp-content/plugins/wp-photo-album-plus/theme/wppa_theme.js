// Theme variables and functions
// This is wppa_theme.js version 3.0.1
//

var wppa_bgcolor_img = '';
var wppa_timer = new Array();
var wppa_saved_id = new Array();
var wppa_popup_nolink = false;

// Popup of thumbnail images 
function wppa_popup(mocc, elm, id, rating) {
	var topDivBig, topDivSmall, leftDivBig, leftDivSmall;
	var heightImgBig, heightImgSmall, widthImgBig, widthImgSmall, widthImgBigSpace;
	var puImg;
	
	// Ignore Duplicate call
	if (id == wppa_saved_id[mocc]) return; 
	wppa_saved_id[mocc] = id;
	
	// due to callback bug, see below, we need an extra timer 
	// stop if running 
	clearTimeout(wppa_timer[mocc]);
	
	// Give this' occurrances popup its content
	if (document.getElementById('x-'+id+'-'+mocc)) {
		if (wppa_popup_nolink) {
			jQuery('#wppa-popup-'+mocc).html('<div class="wppa-popup" style="background-color:'+wppa_bgcolor_img+'; text-align:center;"><img id="wppa-img-'+mocc+'" src="'+elm.src+'" title="" style="border-width: 0px;" /><div id="wppa-name-'+mocc+'" style="display:none; padding:2px;" class="wppa_pu_info">'+elm.alt+'</div><div id="wppa-desc-'+mocc+'" style="clear:both; display:none;" class="wppa_pu_info">'+elm.title+'</div><div id="wppa-rat-'+mocc+'" style="clear:both; display:none;" class="wppa_pu_info">'+rating+'</div></div>');
		}
		else {
			jQuery('#wppa-popup-'+mocc).html('<div class="wppa-popup" style="background-color:'+wppa_bgcolor_img+'; text-align:center;"><a id="wppa-a" href="'+document.getElementById('x-'+id+'-'+mocc).href+'"><img id="wppa-img-'+mocc+'" src="'+elm.src+'" title="" style="border-width: 0px;" /></a><div id="wppa-name-'+mocc+'" style="display:none; padding:2px;" class="wppa_pu_info">'+elm.alt+'</div><div id="wppa-desc-'+mocc+'" style="clear:both; display:none;" class="wppa_pu_info">'+elm.title+'</div><div id="wppa-rat-'+mocc+'" style="clear:both; display:none;" class="wppa_pu_info">'+rating+'</div></div>');
		}
	}
	
	// Find handle to the popup image 
	puImg = document.getElementById('wppa-img-'+mocc);
	
	// Set width of text fields to width of a landscape image	
	if (puImg)
		jQuery(".wppa_pu_info").css('width', ((puImg.clientWidth > puImg.clientHeight ? puImg.clientWidth : puImg.clientHeight) - 8)+'px');
	
	// Compute starting coords
	leftDivSmall = parseInt(elm.offsetLeft) - 7 - 5 - 1; // thumbnail_area:padding, wppa-img:padding, wppa-border; jQuery().css("padding") does not work for padding in css file, only when litaral in the tag
	topDivSmall = parseInt(elm.offsetTop) - 7 - 5 - 1;		
	// Compute starting sizes
	widthImgSmall = parseInt(elm.clientWidth);
	heightImgSmall = parseInt(elm.clientHeight);
	// Compute ending sizes
	widthImgBig = puImg.clientWidth; 
	heightImgBig = parseInt(puImg.clientHeight);
	widthImgBigSpace = puImg.clientWidth > puImg.clientHeight ? puImg.clientWidth : puImg.clientHeight;
	// Compute ending coords
	leftDivBig = leftDivSmall - parseInt((widthImgBigSpace - widthImgSmall) / 2);
	topDivBig = topDivSmall - parseInt((heightImgBig - heightImgSmall) / 2);
	
	// Setup starting properties
	jQuery('#wppa-popup-'+mocc).css({"marginLeft":leftDivSmall+"px","marginTop":topDivSmall+"px"});
	jQuery('#wppa-img-'+mocc).css({"width":widthImgSmall+"px","height":heightImgSmall+"px"});
	// Do the animation
	jQuery('#wppa-popup-'+mocc).stop().animate({"marginLeft":leftDivBig+"px","marginTop":topDivBig+"px"}, 400);
	jQuery('#wppa-img-'+mocc).stop().animate({"width":widthImgBig+"px","height":heightImgBig+"px"}, 400);
	// adding ", 'linear', wppa_popready(occ) " fails, therefor our own timer to the "show info" module
	wppa_timer[mocc] = setTimeout('wppa_popready('+mocc+')', 400);
}
function wppa_popready(mocc) {
	jQuery("#wppa-name-"+mocc).show();
	jQuery("#wppa-desc-"+mocc).show();
	jQuery("#wppa-rat-"+mocc).show();
}
function wppa_popdown(mocc) {	//	return; //debug
	jQuery('#wppa-popup-'+mocc).html("");
}
