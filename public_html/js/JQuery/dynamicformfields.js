// Get value of id - integer appended to dynamic form field names and ids
var id = $("#hiddenfield").val();

// Retrieve new element's html from controller
function ajaxAddField() {
    $.ajax(
        {
            type: "POST",
            url: "../../../ajax/newfield",
            data: "hiddenfield=" + id,
            success: function(newElement) {

                // Insert new element before the Add button
                //$("label[for='addFinder']").parent().prev().children().last().after(newElement);
                $("div#addFinder").prev().last().after(newElement);

                // Increment and store id
                $("#hiddenfield").val(++id);

                // Reveal the remove button
                $("#removeFinder").attr('class', "btn")
            }
        }
    );
}

function removeField() {

    // Get the last used id
    var lastId = $("#hiddenfield").val() - 1;

    // Build the attribute search string.  This will match the last added  dt and dd elements.
    // Specifically, it matches any element where the id begins with 'newName<int>-'.
    //var searchString = '*[id^=newFinder' + lastId + '-]';
    var searchId = 'newFinder' + lastId;
    var searchStringForIds = '*[id^=' + searchId + ']';
    var searchStringForLabels = 'label[for="' + searchId + '"' + ']';

    // Remove the elements that match the search string.
    $(searchStringForIds).remove();
    $(searchStringForLabels).remove();

    // Decrement and store id
    $("#hiddenfield").val(--id);

    // Hide remove button if no extra fields
    if (id <= 1) {
        $("#removeFinder").attr('class', "btn hidden")
    };
}