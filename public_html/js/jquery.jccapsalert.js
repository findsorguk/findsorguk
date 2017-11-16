jQuery.fn.CapsLockAlert = function(settings) {

	return this.each(function() {
		jQuery(this).keypress(function(e) {
			    var is_shift_pressed = false;
			    if (e.shiftKey) {
			      is_shift_pressed = e.shiftKey;
			    } 
			    else if (e.modifiers) {
			      is_shift_pressed = !!(e.modifiers & 4);
			    }
			    if (((e.which >= 65 && e.which <=  90) && !is_shift_pressed) || ((e.which >= 97 && e.which <= 122) && is_shift_pressed)) {
					$("#capslock").show("slow");
			    } 
			    else {
					$("#capslock").hide("slow");
			    }
        });	
        jQuery(this).keyup(function(e) {        	
			    if(jQuery(this).val() =="") {
					//$("#capslock").hide("slow");
			    }
        });
	});
}