$(document).ready(function() {
    $('.row-container').on('click', function(event) {
        if ($.contains(document.querySelector('.content-paginator'), event.target) || $.contains(document.querySelector('.ajax-sort'), event.target)) {
            event.preventDefault(); //prevent default behavior of click
            var url = event.target.href;
                $.get(url, function(data) {   //configure get request and parse response
                    var responseContent = $(data).find('.content-container');
                    $(document).find('.content-container').html(responseContent);
                    console.log(data);

                    history.pushState(null, null, url); //change URL
                });
                $('body').animate({scrollTop: 0}, 600);
        }
    })
});