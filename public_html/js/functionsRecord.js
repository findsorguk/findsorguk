$(document).ready(function () {
              
    $('#print').click(function () {
        window.print();
        return false;
    });

    $('.overlay').click(function (e) {
        e.preventDefault();
        var href = $(e.target).attr('href');
        if (href.indexOf('#') == 0) {
            $(href).modal('open');
        } else {
            $.get(href, function (data) {
                $('<div class="modal fade" >' + data + '</div>').modal();
            });
        }
        
        });
        
});

