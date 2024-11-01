jQuery(document).ready(function(){
 	
	jQuery("#jsrm-current-item-list, #jsrm-all-menus").tableDnD({

		onDragClass: "drag-row",
		onDrop: function(table, row) {
			var rows = table.tBodies[0].rows;

			for (var i = 0; i < rows.length; i++) {
				jQuery(rows[i]).removeClass("drag-row");
				jQuery("#order"+rows[i].id).val(rows[i].rowIndex);
			}
		},
		onDragStart: function(table, row) {
		},
	});

   
   //Styles rows clicked for deletion
   function showStriked(){
   		jQuery("#jsrm-current-item-list input[type=checkbox].strike").each(function() { 
			if( jQuery(this).attr("checked")){
				jQuery(this).closest('tr').addClass("cue-deletion");
			}
			else{
				jQuery(this).closest('tr').removeClass("cue-deletion"); 
			}
    	});
   }
   showStriked();
   
   jQuery(".strike").click(showStriked);
   
   
     //Styles rows clicked for Hide
	function showHidden(){
   		jQuery("#jsrm-current-item-list input[type=checkbox].hideit").each(function() { 
			if( jQuery(this).attr("checked")){
				jQuery(this).closest('tr').addClass("cue-hidden");
			}
			else{
				jQuery(this).closest('tr').removeClass("cue-hidden"); 
			}
    	});
	}
	showHidden();
   
   jQuery(".hideit").click(showHidden);


	//Image Uploader
	
	var target;
	var imageSrc;
	var imageLink;
	
	jQuery(".item-image").click(function() {
		jQuery("#jsrm #image-preview").hide();
		target = jQuery(this);
		var id = jQuery(this).attr('id');
		imageSrc = "#src-"+id;
		imageLink = "#link-"+id;
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		var img = jQuery('img',html).attr('src');
		var link = jQuery(html).attr('href');
		jQuery(target).addClass("has-image").css("background-image","url("+img+")").siblings("div.image-tools").show();
		jQuery(imageSrc).val(img);
		jQuery(imageLink).val(link);
		tb_remove();
	}
	
	//Remove Image
	jQuery('.remove-image').click(function() {
		var id = jQuery(this).attr('id');
		jQuery("#item-"+id).removeAttr("style").removeClass("has-image");
		jQuery("#src-item-"+id).val("");
		jQuery("#link-item-"+id).val("");
		jQuery(this).closest("div.image-tools").hide();	
	});
	
	//Image Preview
	jQuery("#jsrm .item-image").mouseleave(function(){
		jQuery("#jsrm #image-preview").hide();
	});
		
	jQuery("#jsrm .item-image").mouseover(function(){
		if(jQuery(this).hasClass("has-image")){
			var bg = jQuery(this).css("background-image");
			var offset = jQuery(this).offset();
			var y = offset.top - 138;
			jQuery("#jsrm #image-preview").css({'background-image':bg,'top':y,'left':'-10px'}).show();
			}
	});
	

	// FORM FIELD WATERMARKS
	
	
	jQuery('#add-item-form input[type=text], #add-item-form textarea').each(function(i){
		watermark = jQuery(this).attr('title');
		jQuery(this).val(watermark).addClass("form-watermark");
	});
	jQuery('#add-item-form input[type=text], #add-item-form textarea').focus(function(){
		if (jQuery(this).val() == jQuery(this).attr('title')) {
			jQuery(this).val("").removeClass("form-watermark");
			}
	});
	jQuery('#add-item-form input[type=text], #add-item-form textarea').blur(function(){
		var str = jQuery(this).val();
		str = jQuery.trim(str);
		if (str == "") {
			str = jQuery(this).attr('title');
			jQuery(this).val(str).addClass("form-watermark");
			}
		else{
			jQuery(this).val(str);
			}
	});	
	jQuery('#add-item-form').submit(function(){
		
		jQuery('#add-item-form input[type=text], #add-item-form textarea').each(function(i){
		
			if (jQuery(this).val() == jQuery(this).attr('title')) {
				jQuery(this).val("");
			}
		});
	});
		
	
   //Shortcode Generator

    function displayVals(){
      	var namecode = jQuery("#targetmenu").val();

		var customclasscode = jQuery("#selectcustomclass").val().replace(/[^\w-\s]|^\s|^\d|\s(?=\s)/g,'');

		jQuery("#selectcustomclass").val(customclasscode);


		var classcode = jQuery("#selectclass").val();
			switch (classcode) {
    			case "zebra":
    				jQuery(".customclass").fadeOut();
        			classcode = " class=\"jsrm-menu zebra\"";
        			break;
        		case "custom":
        			jQuery(".customclass").fadeIn();
        			if(customclasscode != ""){
        				classcode = " class=\"" + customclasscode.replace(/\b\s(?!.)/,'') + "\"";
        				}
        			else{
        				classcode = " ";
        				}
        			break;
        		case "basic":
        			jQuery(".customclass").fadeOut();
        			classcode = "";
        			break;
        		default:
        			jQuery(".customclass").fadeOut();
        			classcode = "";
			}
		var headercode = jQuery("#selectheader").val();
			if(headercode != "h2"){
				headercode = " header=\"" + headercode + "\"";}
			else{headercode = "";}
			
		var desccode = jQuery("#selectdesc").val();
			if(desccode != "p"){
				desccode = " desc=\"" + desccode + "\"";}
			else{desccode = "";}
		
		var displaycode = jQuery("#selectdisplay").val();
			switch (displaycode) {
	    			case "Ordered List":
	        			displaycode = " display=\"ol\"";
	        			break;
	    			case "Unordered List":
	        			displaycode = " display=\"ul\"";
	        			break;
	    			case "Definition List":
	        			displaycode = " display=\"dl\"";
	        			break;
	        		case "Divs":
	        			displaycode = " display=\"div\"";
	        			break;
	        		case "Table":
	        			displaycode = "";
	        			break;
	        		default:
	        			displaycode = "";
			}
			
		var displayvalues = jQuery("#selectvaluecols").val();
			if(displayvalues == "All"){
				displayvalues = "";
			}
			else{
				displayvalues = " valuecols=\"" + displayvalues + "\"";
			}



		var finalcode = "[simple-retail-menu id=\"" + namecode + "\"" + classcode + headercode + desccode + displaycode + displayvalues+"]";
		jQuery("#shortcodeoutput").val(finalcode);
    }

    jQuery(".shortcodeselecter").change(displayVals);
    jQuery("#selectcustomclass").keyup(displayVals);
    displayVals();
});