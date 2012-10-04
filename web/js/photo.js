$(document).ready(function(){
    var width = $('.carousel').width();
    var height = $(window).height();

    $('.carousel').height(height - 45);
    height = $('.carousel').height();

    $('#thumbnails li a').each(function() {
        var href = $(this).attr('href')+'&width='+width+'&height='+height;
        var html = '<div class="item"><img src="'+href+'" /></div>';

        $('.carousel .carousel-inner').append(html);
    });

    $('.carousel').carousel({
        interval: 5000
    });
});
