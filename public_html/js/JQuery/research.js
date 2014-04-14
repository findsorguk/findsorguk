$(function() {

  $("#submitit").click(function() {

	 	var researchtitle = $("#researchtitle").val(); 
		var label = $("#researchtitle").text();
		var findID =  $("input#findID").val(); 
		var dataString = 'researchtitle='+ researchtitle + '&findID=' + findID;
		//alert (dataString);
		//return false;
		 $("#submitit").attr({ disabled:true, value:"Sending..." });  
  		  $("#submitit").blur();
		$.ajax({
      type: "POST",
      url: "/ajax/addmyresearch/",
      data: dataString,
      success: function() {
        $('#workflowstatus').html("<div id='message'></div>");
        $('#message').html("")
        .hide()
        .fadeIn(1500, function() {
          $('#message').append("<p>Added to my agenda:  " + label + "</p>");
		    $("#submitit").attr({ disabled:false, value:"Add to agenda" }); 
        });
      }
     });
    return false;
	});
	});