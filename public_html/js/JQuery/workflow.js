$(function() {

$('.error').hide();
  $('input.text-input').css({backgroundColor:"#FFFFFF"});
  $('input.text-input').focus(function(){
    $(this).css({backgroundColor:"#FFDDAA"});
  });
  $('input.text-input').blur(function(){
    $(this).css({backgroundColor:"#FFFFFF"});
  });
  
  $("#submitbutton").click(function() {
 		$('.error').hide();
	 	var secwfstage = $("input[@name=wfstage]:checked").val(); 
		if (secwfstage == "") {
      $("label#wfstage_error").show();
      $("input#wfstage").focus();
      return false;
    }
		var id =  $("input#id").val(); 
		if (id == "") {
      $("label#id_error").show();
      $("input#id").focus();
      return false;
    }
		var dataString = 'wfstage='+ secwfstage + '&id=' + id;
		//alert (dataString);
		//return false;
		 $("#submitbutton").attr({ disabled:true, value:"Sending..." });  
  		  $("#submitbutton").blur();
		$.ajax({
      type: "POST",
      url: "/ajax/workflowchange/",
      data: dataString,
      success: function() {
        $('#workflowstatus').html("<div id='message'></div>");
        $('#message').html("")
        .hide()
        .fadeIn(1500, function() {
          $('#message').append("<p>Workflow status changed</p>");
		   $("#submitbutton").attr({ disabled:false, value:"Change workflow" }); 
        });
		
      }
     });
    return false;
	});
	});