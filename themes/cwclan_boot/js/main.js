$(".tp").tooltip({
    placement: "top"
});
$(".tp2").tooltip({
    placement: "top"
});
$(".cwtooltip").tooltip({
    placement: "top"
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
$(function() {
    $(".dropdown-menu > li > a.trigger").on("click", function(e) {
        var current = $(this).next();
        var grandparent = $(this).parent().parent();
        if ($(this).hasClass('left-caret') || $(this).hasClass('right-caret'))
            $(this).toggleClass('right-caret left-caret');
        grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
        grandparent.find(".sub-menu:visible").not(current).hide();
        current.toggle();
        e.stopPropagation();
    });
    $(".dropdown-menu > li > a:not(.trigger)").on("click", function() {
        var root = $(this).closest('.dropdown');
        root.find('.left-caret').toggleClass('right-caret left-caret');
        root.find('.sub-menu:visible').hide();
    });
});
