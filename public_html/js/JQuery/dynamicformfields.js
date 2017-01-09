// Get value of id - integer appended to dynamic form field names and ids
var id = $("#hiddenfield").val();

// Retrieve new element's html from controller
function ajaxAddField() {
    $.ajax(
        {
            type: "POST",
            url: "/ajax/newfield",
            data: "hiddenfield=" + id,
            success: function(newElement) {

                // Insert new elements before the Add button
                $("div#addFinderDiv").prev().last().after(newElement);

                // Calls the finder dropdown on the newly created input
                finderTypeahead('input#finder'+ id);

                // Increment and store id
                $("#hiddenfield").val(++id);

                // Reveal the remove button
                $("#removeFinder").attr('class', "btn btn-warning");

                // Prevent more than 10 finders being added
                if(id >= 11){
                    $("#addFinder").attr('class', "btn btn-info hidden");
                }

            }
        }
    );
}

function removeField() {

    // Get the last used id
    var lastId = $("#hiddenfield").val() - 1;

    // Builds the search strings
    // Matches the control group that wraps the last added new finder input
    var uniqueInput = 'finder' + lastId;
    var searchStringInput = "div#" + uniqueInput + "-form-group";
    // Matches the hidden ID field of the last added new finder input
    var uniqueId = 'finder' + lastId + 'ID';
    var searchStringId = "input#" + uniqueId;

    // Remove the elements that match the search strings
    $(searchStringInput).remove();
    $(searchStringId).remove();

    // Decrement and store id
    $("#hiddenfield").val(--id);

    // Hide remove button if no extra fields
    if (id <= 2) {
        $("#removeFinder").attr('class', "btn btn-warning hidden")
    };

    // Show add button if there are fewer than 10 finders
    if(id < 11){
        $("#addFinder").attr('class', "btn btn-info");
    }
}