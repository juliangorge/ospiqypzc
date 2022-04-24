/*!
 * Start Bootstrap - SB Admin v5.1.0 (https://startbootstrap.com/template-overviews/sb-admin)
 * Copyright 2013-2019 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-sb-admin/blob/master/LICENSE)
 */

!function(l){"use strict";l("#sidebarToggle").on("click",function(o){o.preventDefault(),l("body").toggleClass("sidebar-toggled"),l(".sidebar").toggleClass("toggled")}),l("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel",function(o){if(768<l(window).width()){var e=o.originalEvent,t=e.wheelDelta||-e.detail;this.scrollTop+=30*(t<0?1:-1),o.preventDefault()}}),l(document).on("scroll",function(){100<l(this).scrollTop()?l(".scroll-to-top").fadeIn():l(".scroll-to-top").fadeOut()}),l(document).on("click","a.scroll-to-top",function(o){var e=l(this);l("html, body").stop().animate({scrollTop:l(e.attr("href")).offset().top},1e3,"easeInOutExpo"),o.preventDefault()})}(jQuery);

$(document).ready(function() {
    /*setInterval(function(){
        $.getJSON('/users/notifications', function(a) {
            //console.log('Buscando notificaciones');
            if(a.count[1] == 0){
                $("#notifiDrop .counter").text('').append(0);
                $("#notifiDrop .counter").addClass('btn-elegant');
            }else{
                $("#notifiDrop .counter").removeClass('btn-elegant');
                $("#notifiDrop .counter").text('').append(a.count[1]),
                $.each(a.text, function(a,b){
                    $("div[aria-labelledby='notifiDrop']").html('').append('<a class="dropdown-item waves-effect waves-light" href="#">'+b.text+'</a>');
                });
            }
        })
    },300000);
    $('.notifications-menu').click(function() {
        $.ajax({
            url: '/users/notifications/0',
            success: function() {
                $('#notifiDrop .counter').append('')
            }
        })
    });*/

    setTimeout(function() { 
        $('.alert').fadeOut()
    }, 1000);
});

