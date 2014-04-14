// JavaScript Document
$("#rallyID").css("display","none");
$("#hID").css("display","none");
$("#TID").css("display","none");

$("#rally").click(function(){
if ($("#rally").is(":checked"))
        {
       $("#rallyID").show("fast");
        }
        else
        {     
            $("#rallyID").hide("fast");
        }
      });
$("#treasure").click(function(){
if ($("#treasure").is(":checked"))
        {
       $("#TID").show("fast");			$("#TID").css("display","inline");

        }
        else
        {     
            $("#TID").hide("fast");


        }
      });
$("#hoard").click(function(){
if ($("#hoard").is(":checked"))
        {
       $("#hID").show("fast");

        }
        else
        {     
            $("#hID").hide("fast");


        }
      });