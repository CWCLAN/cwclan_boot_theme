$(document).ready(function() {
    var cookie_enable = false;
    cookie_enable = resizer();
    cookie_enable = $(window).resize(resizer);

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
        ;
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
});
function resizer() {
    var Wheight = $('body').prop('scrollHeight'), Wwidth = $('body').prop('scrollWidth');
    if (Wwidth > '950') {
        $('.swipe-sidebar').css("height", Wheight + "px");
        return true;
    } else {
        $('.swipe-sidebar').css("height", "100%");
        return false;
    }
}

$(".tp").tooltip({
    placement: "right"
});
$(".tp2").tooltip({
    placement: "right"
});
