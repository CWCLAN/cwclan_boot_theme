$(document).ready(function() {
    //set Height so .swipe-area div on Desktops    
    resizer();
    $(window).resize(resizer);
    //Left Panel Toggle
    var cookie_enable = ($('body').prop('scrollWidth') > 950 ? true : false);
    if (cookie_enable == true) {
        if (!$.cookie_ftw("CW_ToggleStatus")) {
            $.cookie_ftw("CW_ToggleStatus", 0, {
                expires: 14, path: '/'
            });
        }
        ;
        var toggle_el = $(".swipe-toggle").data("toggle");
        if (($.cookie_ftw("CW_ToggleStatus") == 0 && $(toggle_el).hasClass("open-sidebar"))) {
            $(toggle_el).removeClass("open-sidebar");
        }
        ;
        if (($.cookie_ftw("CW_ToggleStatus") == 1 && !$(toggle_el).hasClass("open-sidebar"))) {
            $(toggle_el).addClass("open-sidebar");
        }
    }
    ;
    var state = $.cookie_ftw("CW_ToggleStatus");
    $(".swipe-toggle").click(function() {
        var toggle_el = $(this).data("toggle");
        $(toggle_el).toggleClass("open-sidebar");
        $.cookie_ftw("CW_ToggleStatus", (state == 1 ? "0" : "1"), {
            expires: 14, path: '/'
        });
    });
})
function resizer() {
    var Wheight = $('body').prop('scrollHeight'), Wwidth = $('body').prop('scrollWidth');
    $('.swipe-area').css("height", Wheight + "px");
    $('.swipe-area2').css("height", Wheight + "px");
    if (Wwidth > '950') {
        $('.swipe-sidebar').css("height", Wheight + "px");
    } else {
        $('.swipe-sidebar').css("height", "100%");
    }
}
;
$(".tp").tooltip({
    placement: "right"
});
$(".tp2").tooltip({
    placement: "right"
});
$('#login').popover({
    html: true,
    placement: "left"
});
/// FLIP FORUM USER INFO
$(function() {
    if ($('html').hasClass('csstransforms3d')) {
        $('.userinfo').removeClass('scroll').addClass('flip');
        $('.userinfo.flip').hover(
                function() {
                    $(this).find('.userinfo-wrapper').addClass('flipIt');
                },
                function() {
                    $(this).find('.userinfo-wrapper').removeClass('flipIt');
                }
        );
    } else {
        $('.userinfo').hover(
                function() {
                    $(this).find('.userinfo-detail').stop().animate({bottom: 0}, 500, 'easeOutCubic');
                },
                function() {
                    $(this).find('.userinfo-detail').stop().animate({bottom: ($(this).height() * -1)}, 500, 'easeOutCubic');
                }
        );
    }
});
// Mutilevel Dropdown BS3
$(function(){
    $(".dropdown-menu > li > a.trigger").on("click",function(e){
        var current=$(this).next();
        var grandparent=$(this).parent().parent();
        if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
            $(this).toggleClass('right-caret left-caret');
        grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
        grandparent.find(".sub-menu:visible").not(current).hide();
        current.toggle();
        e.stopPropagation();
    });
    $(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
        var root=$(this).closest('.dropdown');
        root.find('.left-caret').toggleClass('right-caret left-caret');
        root.find('.sub-menu:visible').hide();
    });
});
