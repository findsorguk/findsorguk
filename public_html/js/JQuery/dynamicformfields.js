// Get value of id - integer appended to dynamic form field names and ids
var id = $("#id").val();

// Retrieve new element's html from controller
function ajaxAddField() {
    $.ajax(
        {
            type: "POST",
            url: "newfield/format/html",
            data: "id2=" + id,
            success: function(newElement) {

                // Insert new element before the Add button
                $("#addElement-label").before(newElement);

                // Increment and store id
                $("#id2").val(++id);
            }
        }
    );
}

function removeField() {

    // Get the last used id
    var lastId = $("#id2").val() - 1;

    // Build the attribute search string.  This will match the last added  dt and dd elements.
    // Specifically, it matches any element where the id begins with 'newName<int>-'.
    searchString = '*[id2^=newName' + lastId + '-]';

    // Remove the elements that match the search string.
    $(searchString).remove()

    // Decrement and store id
    $("#id2").val(--id);
}