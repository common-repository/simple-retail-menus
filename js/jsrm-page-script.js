jQuery(document).ready(function(){
	
	// Find each OL or UL Menu on the page
	
	jQuery(".jsrm-menu").each(function(index){
		var x = 1+index;
		var thisID = jQuery(this).attr("id");
		
		//Add index numbering to ID and class
		jQuery(this).attr("id", thisID+"-container"+x).addClass("page-menu-"+x);
		
		
		
		//find EACH value header
		jQuery(".value-header .value-col", this).each(function(index){
			var ind = 1+index;
			
			var w = jQuery(this).width();
			
			jQuery(this).closest(".jsrm-menu").find("li .value-"+ind).css({width: w});
			//jQuery(this),parent
			
			
		});
		

	});





});